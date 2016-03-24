<?php
namespace Weasty\Money\Loader;

use Weasty\Money\Currency\Rate\CurrencyRateInterface;

/**
 * Class Record
 * @package Weasty\Money\Loader
 */
class Record implements CurrencyRateInterface {

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
   * Record constructor.
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