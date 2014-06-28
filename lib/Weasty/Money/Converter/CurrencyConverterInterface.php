<?php
namespace Weasty\Money\Converter;

/**
 * Interface CurrencyConverterInterface
 * @package Weasty\Money\Converter
 */
interface CurrencyConverterInterface {

    /**
     * @param string|integer|float|\Weasty\Money\Price\PriceInterface $value
     * @param string|integer|\Weasty\Money\Currency\CurrencyInterface|null $sourceCurrency
     * @param string|integer|\Weasty\Money\Currency\CurrencyInterface|null $destinationCurrency
     * @return string|integer|float|null
     */
    public function convert($value, $sourceCurrency = null, $destinationCurrency = null);

    /**
     * @return \Weasty\Money\Currency\CurrencyResource
     */
    public function getCurrencyResource();

} 