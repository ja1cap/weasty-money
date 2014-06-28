<?php
namespace Weasty\Money\Currency;

/**
 * Interface CurrencyInterface
 * @package Weasty\Money\Currency
 */
interface CurrencyInterface {

    /**
     * @return string|null
     */
    public function getSymbol();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return integer|string
     */
    public function getNumericCode();

    /**
     * @return integer|string
     */
    public function getAlphabeticCode();

} 