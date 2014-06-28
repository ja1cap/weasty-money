<?php
namespace Weasty\Money\Converter;

use Doctrine\Common\Persistence\ObjectRepository;
use Weasty\Money\Currency\Rate\CurrencyRateInterface;
use Weasty\Money\Currency\CurrencyResource;
use Weasty\Money\Price\PriceInterface;

/**
 * Class CurrencyConverter
 * @package Weasty\Money\Converter
 */
class CurrencyConverter implements CurrencyConverterInterface {

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $currencyRateRepository;

    /**
     * @var \Weasty\Money\Converter\CurrencyCodeConverterInterface
     */
    protected $currencyCodeConverter;

    /**
     * @var \Weasty\Money\Currency\CurrencyResource
     */
    protected $currencyResource;

    /**
     * @param $currencyResource
     * @param $currencyRateRepository
     * @param $currencyCodeConverter
     */
    function __construct(CurrencyResource $currencyResource, ObjectRepository $currencyRateRepository, CurrencyCodeConverterInterface $currencyCodeConverter)
    {
        $this->currencyResource = $currencyResource;
        $this->currencyRateRepository = $currencyRateRepository;
        $this->currencyCodeConverter = $currencyCodeConverter;
    }

    /**
     * @param string|integer|float|\Weasty\Money\Price\PriceInterface $value
     * @param string|integer|\Weasty\Money\Currency\CurrencyInterface|null $sourceCurrency
     * @param string|integer|\Weasty\Money\Currency\CurrencyInterface|null $destinationCurrency
     * @return string|integer|float|null
     */
    public function convert($value, $sourceCurrency = null, $destinationCurrency = null)
    {

        if($value instanceof PriceInterface){

            $price = $value;
            $value = $price->getValue();
            $sourceCurrency = $sourceCurrency ?: $price->getCurrency();

        } else {

            $sourceCurrency = $sourceCurrency ?: $this->getCurrencyResource()->getDefaultCurrency();

        }

        $sourceCurrencyAlphabeticCode = $this
            ->getCurrencyCodeConverter()
            ->convert(
                $sourceCurrency,
                CurrencyResource::CODE_TYPE_ISO_4217_ALPHABETIC
            );

        $destinationCurrency = $destinationCurrency ?: $this->getCurrencyResource()->getDefaultCurrency();

        $destinationCurrencyAlphabeticCode = $this
            ->getCurrencyCodeConverter()
            ->convert(
                $destinationCurrency,
                CurrencyResource::CODE_TYPE_ISO_4217_ALPHABETIC
            );

        if($destinationCurrencyAlphabeticCode != $sourceCurrencyAlphabeticCode) {

            $value = $this->exchange($value, $sourceCurrencyAlphabeticCode, $destinationCurrencyAlphabeticCode);

        }

        return $value;

    }

    /**
     * @param $value
     * @param $sourceCurrencyAlphabeticCode
     * @param $destinationCurrencyAlphabeticCode
     * @return float|integer|string
     */
    protected function exchange($value, $sourceCurrencyAlphabeticCode, $destinationCurrencyAlphabeticCode){

        $currencyRate = $this->getCurrencyRateRepository()->findOneBy(array(
            'sourceAlphabeticCode' => $sourceCurrencyAlphabeticCode,
            'destinationAlphabeticCode' => $destinationCurrencyAlphabeticCode,
        ));

        if($currencyRate instanceof CurrencyRateInterface){
            $value = ($value * $currencyRate->getRate());
        }

        return $value;

    }

    /**
     * @return \Weasty\Money\Data\\Weasty\Money\Currency\CurrencyResource
     */
    public function getCurrencyResource()
    {
        return $this->currencyResource;
    }

    /**
     * @return \Weasty\Money\Converter\CurrencyCodeConverterInterface
     */
    public function getCurrencyCodeConverter()
    {
        return $this->currencyCodeConverter;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getCurrencyRateRepository()
    {
        return $this->currencyRateRepository;
    }

} 