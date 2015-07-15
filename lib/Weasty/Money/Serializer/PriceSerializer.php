<?php
namespace Weasty\Money\Serializer;
use Weasty\Money\Currency\CurrencyResource;
use Weasty\Money\Formatter\Currency\CurrencyFormatterInterface;
use Weasty\Money\Formatter\Money\MoneyFormatterInterface;
use Weasty\Money\Price\DiscountPriceInterface;
use Weasty\Money\Price\PriceInterface;

/**
 * Class PriceSerializer
 * @package Weasty\Money\Serializer
 */
class PriceSerializer implements PriceSerializerInterface
{
  /**
   * @var CurrencyFormatterInterface
   */
  protected $currencyFormatter;
  /**
   * @var MoneyFormatterInterface
   */
  protected $moneyFormatter;

  /**
   * PriceSerializer constructor.
   *
   * @param MoneyFormatterInterface $moneyFormatter
   * @param CurrencyFormatterInterface $currencyFormatter
   */
  public function __construct( MoneyFormatterInterface $moneyFormatter, CurrencyFormatterInterface $currencyFormatter ) {
    $this->moneyFormatter    = $moneyFormatter;
    $this->currencyFormatter = $currencyFormatter;
  }

  /**
   * @param PriceInterface $price
   *
   * @return array
   */
  public function serializeAssoc( PriceInterface $price )
  {

    $value = $price->getValue();
    $currency = $price->getCurrency();

    $moneyFormat = $this->moneyFormatter->formatMoney( $value, $currency );
    $priceFormat = $this->moneyFormatter->formatPrice( $value, $currency );

    $currencyName = $this->currencyFormatter->formatName( $currency );
    $currencySymbol = $this->currencyFormatter->formatSymbol( $currency );
    $currencyAlphaCode = $this->currencyFormatter->formatCode( $currency, CurrencyResource::CODE_TYPE_ISO_4217_ALPHABETIC );
    $currencyNumericCode = $this->currencyFormatter->formatCode( $currency, CurrencyResource::CODE_TYPE_ISO_4217_NUMERIC );

    $data = [
      'value' => $value,
      'moneyFormat' => $moneyFormat,
      'priceFormat' => $priceFormat,
      'currency' => $currencyAlphaCode,
      'currencyName' => $currencyName,
      'currencySymbol' => $currencySymbol,
      'currencyAlphaCode' => $currencyAlphaCode,
      'currencyNumericCode' => $currencyNumericCode,
    ];

    if( $price instanceof DiscountPriceInterface ){
      $data['discountPercent'] = $price->getDiscountPercent();
      $data['originalPrice'] = $this->serializeAssoc( $price->getOriginalPrice() );
    }

    return $data;
  }

  /**
   * @param PriceInterface $price
   *
   * @return string
   */
  public function serializeJson( PriceInterface $price )
  {
    return json_encode( $this->serializeAssoc( $price ) );
  }

  /**
   * @param PriceInterface $price
   *
   * @return string
   */
  public function serialize( PriceInterface $price )
  {
    return serialize( $this->serializeAssoc( $price ) );
  }

}