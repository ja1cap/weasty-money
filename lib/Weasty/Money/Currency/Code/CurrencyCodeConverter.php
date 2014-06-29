<?php
namespace Weasty\Money\Currency\Code;

use Weasty\Money\Currency\Type\CurrencyTypeConverterFactory;
use Weasty\Money\Currency\CurrencyInterface;
use Weasty\Money\Currency\CurrencyResource;

/**
 * Class CurrencyCodeConverter
 * @package Weasty\Money\Currency\Code
 */
class CurrencyCodeConverter implements CurrencyCodeConverterInterface {

    /**
     * @var \Weasty\Money\Currency\Type\CurrencyTypeConverterFactory
     */
    protected $currencyTypeConverterFactory;

    /**
     * @param CurrencyTypeConverterFactory $currencyTypeCodeConverterFactory
     */
    function __construct(CurrencyTypeConverterFactory $currencyTypeCodeConverterFactory)
    {
        $this->currencyTypeConverterFactory = $currencyTypeCodeConverterFactory;
    }

    /**
     * Get currency code type
     *
     * @param $currency
     * @return integer|string|\Weasty\Money\Currency\CurrencyInterface
     * @throws \Exception
     */
    public function getCurrencyCodeType($currency)
    {

        if(!$currency || $currency instanceof CurrencyInterface){
            return CurrencyResource::CODE_TYPE_ISO_4217_ALPHABETIC;
        }

        $types = $this->getCurrencyTypeConverterFactory()->getConverterTypes();
        foreach($types as $type){
            $converter = $this->getCurrencyTypeConverterFactory()->createConverter($type);
            if($converter->isValidCurrencyCode($currency)){
                return $type;
                break;
            }
        }

        throw new \Exception(sprintf('Currency code type converter not found %s', $currency));

    }

    /**
     * @param integer|string|\Weasty\Money\Currency\CurrencyInterface $currency Source currency
     * @param integer|string $destinationType Destination currency type
     * @param null|integer|string $sourceType Source currency type
     * @return mixed
     */
    public function convert($currency, $destinationType, $sourceType = null)
    {

        if($currency instanceof CurrencyInterface){

            /**
             * Check destination type is in common types list
             */
            switch($destinationType){
                case CurrencyResource::CODE_TYPE_ISO_4217_ALPHABETIC:

                    return $currency->getAlphabeticCode();

                case CurrencyResource::CODE_TYPE_ISO_4217_NUMERIC:

                    return $currency->getNumericCode();

                default:

                    /**
                     * If destination type is not in common types list
                     * assign @var $sourceCurrencyCode string ISO 4217 alphabetic code
                     * assign @var $sourceCurrencyAlphabeticCode int ISO 4217 alphabetic code type
                     */
                    $sourceCurrencyCode = $currency->getAlphabeticCode();
                    $sourceCurrencyAlphabeticCode = CurrencyResource::CODE_TYPE_ISO_4217_ALPHABETIC;

            }

        } else {

            /**
             * Force cast @var $currency mixed as string
             * to prevent other types except integer and string
             */
            $sourceCurrencyCode = (string)$currency;

            /**
             * Check source type
             * if it is not defined - try get currency type by currency code format
             */
            if(!$sourceType){
                $sourceType = $this->getCurrencyCodeType($sourceCurrencyCode);
            }

            /**
             * Check if source and destination currencies are equal
             */
            if($destinationType == $sourceType){
                return $sourceCurrencyCode;
            }

            /**
             * Convert source currency code to ISO 4217 alphabetic code
             */
            if($sourceType == CurrencyResource::CODE_TYPE_ISO_4217_ALPHABETIC){

                $sourceCurrencyAlphabeticCode = $sourceCurrencyCode;

            } else {

                $sourceTypeConverter = $this->getCurrencyTypeConverterFactory()->createConverter($sourceType);
                $sourceCurrencyAlphabeticCode = $sourceTypeConverter->getAlphabeticCurrencyCode($sourceCurrencyCode);

            }

        }

        if(!$sourceCurrencyAlphabeticCode){
            return $sourceCurrencyCode;
        }

        /**
         * Build destination currency code type converter
         * get @var $destinationCurrencyCode mixed in necessary type
         * using universal @var $sourceCurrencyAlphabeticCode string ISO 4217 alphabetic code
         */
        $destinationTypeConverter = $this->getCurrencyTypeConverterFactory()->createConverter($destinationType);
        $destinationCurrencyCode = $destinationTypeConverter->getCurrencyCode($sourceCurrencyAlphabeticCode);

        if(!$destinationCurrencyCode){
            return $sourceCurrencyCode;
        }

        return $destinationCurrencyCode;

    }

    /**
     * @return \Weasty\Money\Currency\Type\CurrencyTypeConverterFactory
     */
    public function getCurrencyTypeConverterFactory()
    {
        return $this->currencyTypeConverterFactory;
    }

} 