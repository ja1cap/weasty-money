<?php
namespace Weasty\Money\Price;

/**
 * Interface PriceInterface
 * @package Weasty\Money\Price
 */
interface PriceInterface {

    /**
     * @return integer|float|string
     */
    public function getValue();

    /**
     * @return integer|string|\Weasty\Money\Currency\CurrencyInterface
     */
    public function getCurrency();

} 