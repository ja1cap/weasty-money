<?php
namespace Weasty\Money\Formatter\Currency;
use Weasty\Money\Currency\CurrencyInterface;
use Weasty\Money\Currency\CurrencyResource;

/**
 * Interface CurrencyFormatterInterface
 * @package Weasty\Money\Currency\Formatter
 */
interface CurrencyFormatterInterface {

  /**
   * @param int|string|CurrencyInterface $currency
   * @return null|string
   */
  public function formatName($currency);

  /**
   * @param int|string|CurrencyInterface $currency
   * @return null|string
   */
  public function formatSymbol($currency);

  /**
   * @param int|string|CurrencyInterface $currency
   * @param $type
   * @return null|integer
   */
  public function formatCode($currency, $type = CurrencyResource::CODE_TYPE_ISO_4217_ALPHABETIC);

  /**
   * @param $currency
   * @return null|integer
   */
  public function formatNumericCode($currency);

}