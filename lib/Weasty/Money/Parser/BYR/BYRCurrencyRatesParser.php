<?php
namespace Weasty\Money\Parser\BYR;

use Weasty\Money\Parser\CurrencyRatesParserException;
use Weasty\Money\Parser\CurrencyRatesParserInterface;
use Weasty\Money\Parser\CurrencyRateRecord;

/**
 * Class BYRCurrencyRatesParser
 * @package Weasty\Money\Parser\BYR
 */
class BYRCurrencyRatesParser implements CurrencyRatesParserInterface {

  const SOURCE_CURRENCY_CODE = 'BYR';
  const DEFAULT_URL = 'http://www.nbrb.by/Services/XmlExRates.aspx';

  /**
   * @var string
   */
  protected $url;

  /**
   * BYRCurrencyRatesParser constructor.
   *
   * @param string $url
   */
  public function __construct( $url = self::DEFAULT_URL ) {
    $this->url = $url;
  }

  /**
   * @param \DateTime|null $dateTime
   *
   * @return \Weasty\Money\Currency\Rate\CurrencyRateInterface[]
   * @throws \Weasty\Money\Parser\CurrencyRatesParserException
   */
  public function parse( \DateTime $dateTime = null ) {

    if ( !$dateTime ) {
      $dateTime = new \DateTime();
    }

    $parseDate = $dateTime->format( 'm/d/Y' );

    $response     = file_get_contents( $this->getUrl()."?ondate=$parseDate" );
    $xml          = simplexml_load_string( $response, "SimpleXMLElement", LIBXML_NOCDATA );
    $json         = json_encode( $xml );
    $responseData = json_decode( $json, true );

    if ( empty( $responseData['Currency'] ) ) {
      throw new CurrencyRatesParserException( "Currency rates not found[$response]" );
    }

    if ( empty( $responseData['@attributes']['Date'] ) ) {
      throw new CurrencyRatesParserException( "Date not found in response[$response]" );
    }
    $responseDate = $responseData['@attributes']['Date'];

    if ( $parseDate != $responseDate ) {
      throw new CurrencyRatesParserException( "Parse date[$parseDate] is not equal to response date[$responseDate]" );
    }

    $records = $responseData['Currency'];
    $currencyRecords = array_map(function($record){
      $code = $record['CharCode'];
      $rate = filter_var( $record['Rate'], FILTER_VALIDATE_FLOAT );
      return new CurrencyRateRecord( self::SOURCE_CURRENCY_CODE, $code, $rate);
    }, $records);
    
    return $currencyRecords;

  }

  /**
   * @return string
   */
  public function getUrl() {
    return $this->url;
  }

}