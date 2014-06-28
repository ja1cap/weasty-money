<?php
namespace Weasty\Money\Converter;

/**
 * Interface CurrencyCodeConverterInterface
 * @package Weasty\Money\Converter
 */
interface CurrencyCodeConverterInterface {

    /**
     * Get currency code type
     *
     * @param string|integer|\Weasty\Money\Currency\CurrencyInterface $currencyCode
     * @return integer|string
     */
    public function getCurrencyCodeType($currencyCode);

    /**
     * @param integer|string|\Weasty\Money\Currency\CurrencyInterface $currency Source currency code
     * @param integer|string $destinationCodeType Destination currency code type
     * @param null|integer|string $sourceCodeType Source currency code type
     * @return mixed
     */
    public function convert($currency, $destinationCodeType, $sourceCodeType = null);

} 