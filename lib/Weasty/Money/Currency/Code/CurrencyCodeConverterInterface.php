<?php
namespace Weasty\Money\Currency\Code;

/**
 * Interface CurrencyCodeConverterInterface
 * @package Weasty\Money\Currency\Code
 */
interface CurrencyCodeConverterInterface {

    /**
     * Get currency code type
     *
     * @param string|integer|\Weasty\Money\Currency\CurrencyInterface $currency
     * @return integer|string
     */
    public function getCurrencyCodeType($currency);

    /**
     * Convert currency code to necessary(destination) type
     *
     * @param integer|string|\Weasty\Money\Currency\CurrencyInterface $currency Source currency code
     * @param integer|string $destinationType Destination currency code type
     * @param null|integer|string $sourceType Source currency code type
     * @return mixed
     */
    public function convert($currency, $destinationType, $sourceType = null);

} 