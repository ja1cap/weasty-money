<?php
namespace Weasty\Money\Parser;

use Weasty\Money\Currency\Rate\CurrencyRateInterface;

/**
 * Class CurrencyRateRecord
 * @package Weasty\Money\Parser
 */
class CurrencyRateRecord implements CurrencyRateInterface {

  /**
   * Currency ISO-4217-ALPHA code
   * @var string
   */
  protected $sourceAlphabeticCode;

  /**
   * Currency ISO-4217-ALPHA code
   * @var string
   */
  protected $destinationAlphabeticCode;

  /**
   * @var float
   */
  protected $rate;

  /**
   * CurrencyRateRecord constructor.
   *
   * @param string $sourceAlphabeticCode
   * @param string $destinationAlphabeticCode
   * @param float $rate
   */
  public function __construct( $sourceAlphabeticCode, $destinationAlphabeticCode, $rate ) {
    $this->sourceAlphabeticCode      = $sourceAlphabeticCode;
    $this->destinationAlphabeticCode = $destinationAlphabeticCode;
    $this->rate                      = $rate;
  }

  /**
   * Currency ISO-4217-ALPHA code
   * @return string
   */
  public function getSourceAlphabeticCode() {
    return $this->sourceAlphabeticCode;
  }

  /**
   * Currency ISO-4217-ALPHA code
   * @return string
   */
  public function getDestinationAlphabeticCode() {
    return $this->destinationAlphabeticCode;
  }

  /**
   * @return float
   */
  public function getRate() {
    return $this->rate;
  }

}