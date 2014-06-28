<?php
namespace Weasty\Money\Converter\CurrencyCodeType;

use Weasty\Money\Currency\CurrencyResource;

/**
 * Class CurrencyTypeIso4217NumericCodeConverter
 * @package Weasty\Money\Converter\CurrencyCodeType
 */
class CurrencyTypeIso4217NumericCodeConverter implements CurrencyTypeCodeConverterInterface {

    /**
     * @var CurrencyResource
     */
    protected $currencyResource;

    /**
     * @param \Weasty\Money\Currency\CurrencyResource $currencyResource
     */
    function __construct(CurrencyResource $currencyResource)
    {
        $this->currencyResource = $currencyResource;
    }

    /**
     * Currency type unique identifier
     * @return string|integer
     */
    static function getType()
    {
        return CurrencyResource::CODE_TYPE_ISO_4217_NUMERIC;
    }

    /**
     * @return \Weasty\Money\Currency\CurrencyResource
     */
    public function getCurrencyResource()
    {
        return $this->currencyResource;
    }

    /**
     * Check is currency code is valid for current type
     * @param $currencyCode
     * @return boolean
     */
    function isValidCurrencyCode($currencyCode)
    {
        return (preg_match('/^([0-9]{3})$/', $currencyCode) === 1);
    }

    /**
     * @param $currencyCode
     * @return string The 3-letter ISO 4217 currency code
     */
    function getAlphabeticCurrencyCode($currencyCode)
    {
        return $this->getCurrencyResource()->getCurrencyAlphabeticCode($currencyCode);
    }

    /**
     * @param string $currencyAlphabeticCode The 3-letter ISO 4217 currency code
     * @return mixed
     */
    function getTypeCurrencyCode($currencyAlphabeticCode)
    {
        return $this->getCurrencyResource()->getCurrencyNumericCode($currencyAlphabeticCode);
    }

} 