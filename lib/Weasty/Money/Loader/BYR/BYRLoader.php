<?php
namespace Weasty\Money\Loader\BYR;

use Weasty\Money\Loader\Exception\RecordsNotFoundException;
use Weasty\Money\Loader\LoaderException;
use Weasty\Money\Loader\LoaderInterface;
use Weasty\Money\Loader\Record;

/**
 * Class BYRLoader
 * @package Weasty\Money\Loader\BYR
 */
class BYRLoader implements LoaderInterface {

  const DESTINATION_CURRENCY_CODE = 'BYR';
  const DEFAULT_URL = 'http://www.nbrb.by/Services/XmlExRates.aspx';

  /**
   * @var string
   */
  protected $url;

  /**
   * BYRLoader constructor.
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
   * @throws \Weasty\Money\Loader\LoaderException
   */
  public function load( \DateTime $dateTime = null ) {

    if ( !$dateTime ) {
      $dateTime = new \DateTime();
    }

    $parseDate = $dateTime->format( 'm/d/Y' );

    $response     = file_get_contents( $this->getUrl()."?ondate=$parseDate" );
    $xml          = simplexml_load_string( $response, "SimpleXMLElement", LIBXML_NOCDATA );
    $json         = json_encode( $xml );
    $responseData = json_decode( $json, true );

    if ( empty( $responseData['Currency'] ) ) {
      throw new RecordsNotFoundException( "Currency rates not found[$response]" );
    }

    if ( empty( $responseData['@attributes']['Date'] ) ) {
      throw new LoaderException( "Date not found in response[$response]" );
    }
    $responseDate = $responseData['@attributes']['Date'];

    if ( $parseDate != $responseDate ) {
      throw new LoaderException( "Parse date[$parseDate] is not equal to response date[$responseDate]" );
    }

    $records         = $responseData['Currency'];
    $currencyRecords = array_filter(
      array_map(
        function ( $record ) {
          $code = $record['CharCode'];
          switch ( $code ) {
            case 'XDR':
              // External Data Representation is not common currency
              $record = null;
              break;
            default:
              $rate   = filter_var( $record['Rate'], FILTER_VALIDATE_FLOAT );
              $record = new Record( $code, self::DESTINATION_CURRENCY_CODE, $rate );
          }

          return $record;
        },
        $records
      )
    );

    return $currencyRecords;

  }

  /**
   * @return string
   */
  public function getUrl() {
    return $this->url;
  }

}