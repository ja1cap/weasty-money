<?php
namespace Weasty\Money\Formatter\Currency;

use Weasty\Money\Currency\Code\CurrencyCodeConverterInterface;
use Weasty\Money\Currency\CurrencyInterface;
use Weasty\Money\Currency\CurrencyResource;

/**
 * Class CurrencyFormatter
 * @package Weasty\Money\Currency\Formatter
 */
class CurrencyFormatter implements CurrencyFormatterInterface {

  /**
   * @var CurrencyResource
   */
  protected $currencyResource;

  /**
   * @var \Weasty\Money\Currency\Code\CurrencyCodeConverterInterface
   */
  protected $currencyCodeConverter;

  /**
   * @param \Weasty\Money\Currency\CurrencyResource $currencyResource
   * @param \Weasty\Money\Currency\Code\CurrencyCodeConverterInterface $currencyCodeConverter
   */
  function __construct(CurrencyResource $currencyResource, CurrencyCodeConverterInterface $currencyCodeConverter)
  {

    $this->currencyResource = $currencyResource;
    $this->currencyCodeConverter = $currencyCodeConverter;

  }

  /**
   * @param int|string|CurrencyInterface $currency
   *
   * @return null|string
   */
  public function formatName( $currency ) {
    $code = $this->getCurrencyCodeConverter()->convert($currency, CurrencyResource::CODE_TYPE_ISO_4217_ALPHABETIC);
    return $this->getCurrencyResource()->getCurrencyName($code);
  }

  /**
   * @param int|string|CurrencyInterface $currency
   *
   * @return null|string
   */
  public function formatSymbol( $currency ) {
    $code = $this->getCurrencyCodeConverter()->convert($currency, CurrencyResource::CODE_TYPE_ISO_4217_ALPHABETIC);
    return $this->getCurrencyResource()->getCurrencySymbol($code);
  }

  /**
   * @param int|string|CurrencyInterface $currency
   * @param $type
   *
   * @return null|integer
   */
  public function formatCode( $currency, $type = CurrencyResource::CODE_TYPE_ISO_4217_ALPHABETIC ) {
    return $this->getCurrencyCodeConverter()->convert($currency, $type);
  }

  /**
   * @param $currency
   *
   * @return null|integer
   */
  public function formatNumericCode( $currency ) {
    return $this->getCurrencyCodeConverter()->convert($currency, CurrencyResource::CODE_TYPE_ISO_4217_NUMERIC);
  }

  /**
   * @return \Weasty\Money\Currency\CurrencyResource
   */
  public function getCurrencyResource()
  {
    return $this->currencyResource;
  }

  /**
   * @return \Weasty\Money\Currency\Code\CurrencyCodeConverterInterface
   */
  public function getCurrencyCodeConverter()
  {
    return $this->currencyCodeConverter;
  }

}