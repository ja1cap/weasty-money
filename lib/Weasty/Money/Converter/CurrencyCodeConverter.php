<?php
namespace Weasty\Money\Converter;

use Weasty\Money\Currency\CurrencyInterface;
use Weasty\Money\Currency\CurrencyResource;

/**
 * Class CurrencyCodeConverter
 * @package Weasty\Money\Converter
 */
class CurrencyCodeConverter implements CurrencyCodeConverterInterface {

    /**
     * @var CurrencyTypeCodeConverterFactory
     */
    protected $currencyTypeCodeConverterFactory;

    /**
     * @param CurrencyTypeCodeConverterFactory $currencyTypeCodeConverterFactory
     */
    function __construct(CurrencyTypeCodeConverterFactory $currencyTypeCodeConverterFactory)
    {
        $this->currencyTypeCodeConverterFactory = $currencyTypeCodeConverterFactory;
    }

    /**
     * Get currency code type
     *
     * @param $currencyCode
     * @return integer|string|\Weasty\Money\Currency\CurrencyInterface
     * @throws \Exception
     */
    public function getCurrencyCodeType($currencyCode)
    {

        if(!$currencyCode || $currencyCode instanceof CurrencyInterface){
            return CurrencyResource::CODE_TYPE_ISO_4217_ALPHABETIC;
        }

        $types = $this->getCurrencyTypeCodeConverterFactory()->getConverterTypes();
        foreach($types as $type){
            $converter = $this->getCurrencyTypeCodeConverterFactory()->createConverter($type);
            if($converter->isValidCurrencyCode($currencyCode)){
                return $type;
                break;
            }
        }

        throw new \Exception(sprintf('Currency code type converter not found %s', $currencyCode));

    }

    /**
     * @param integer|string|\Weasty\Money\Currency\CurrencyInterface $currency Source currency code
     * @param integer|string $destinationCodeType Destination currency code type
     * @param null|integer|string $sourceCodeType Source currency code type
     * @return mixed
     */
    public function convert($currency, $destinationCodeType, $sourceCodeType = null)
    {

        if($currency instanceof CurrencyInterface){
            $currencyCode = $currency->getAlphabeticCode();
        } else {
            $currencyCode = $currency;
        }

        if(!$sourceCodeType){
            $sourceCodeType = $this->getCurrencyCodeType($currencyCode);
        }

        if($destinationCodeType == $sourceCodeType){
            return $currencyCode;
        }

        if($currency instanceof CurrencyInterface){

            $alphabeticCurrencyCode = $currency->getAlphabeticCode();

        } else {

            $sourceTypeConverter = $this->getCurrencyTypeCodeConverterFactory()->createConverter($sourceCodeType);
            $alphabeticCurrencyCode = $sourceTypeConverter->getAlphabeticCurrencyCode($currencyCode);

        }

        $destinationTypeConverter = $this->getCurrencyTypeCodeConverterFactory()->createConverter($destinationCodeType);

        if(!$alphabeticCurrencyCode){
            return $currencyCode;
        }

        $destinationCurrencyCode = $destinationTypeConverter->getTypeCurrencyCode($alphabeticCurrencyCode);

        if(!$destinationCurrencyCode){
            return $currencyCode;
        }

        return $destinationCurrencyCode;

    }

    /**
     * @return \Weasty\Money\Converter\CurrencyTypeCodeConverterFactory
     */
    public function getCurrencyTypeCodeConverterFactory()
    {
        return $this->currencyTypeCodeConverterFactory;
    }

} 