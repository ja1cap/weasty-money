<?php
namespace Weasty\Money\Parser;
use Weasty\Money\Parser\BYR\BYRCurrencyRatesParser;

/**
 * Class CurrencyRatesParserFactory
 * @package Weasty\Money\Parser
 */
class CurrencyRatesParserFactory {

  /**
   * @param string $currencyCode - Currency ISO-4217-ALPHA code
   *
   * @return \Weasty\Money\Parser\CurrencyRatesParserInterface
   * @throws \Weasty\Money\Parser\CurrencyRatesParserException
   */
  public function create( $currencyCode ) {
    switch ( $currencyCode ) {
      case 'BYR':
        $parser = new BYRCurrencyRatesParser();
        break;
      default:
        throw new CurrencyRatesParserException( "Parser not found for currency[$currencyCode]" );
    }

    return $parser;
  }

}