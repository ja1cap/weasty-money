<?php
namespace Weasty\Money\Price\Formatter;

/**
 * Interface PriceFormatterInterface
 * @package Weasty\Money\Price\Formatter
 */
interface PriceFormatterInterface {

  /**
   * @param string|null|\Weasty\Money\Price\PriceInterface $value
   * @param null $sourceCurrency
   * @param null $destinationCurrency
   * @return string
   */
  public function formatPrice($value, $sourceCurrency = null, $destinationCurrency = null);

  /**
   * @param $value
   * @param $currency
   * @param boolean $appendSymbol
   * @return string
   */
  public function formatMoney($value, $currency = null, $appendSymbol = true);

}