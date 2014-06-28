<?php
namespace Weasty\Money\Currency\Rate;

/**
 * Interface CurrencyRateInterface
 * @package Weasty\Money\Currency\Rate
 */
interface CurrencyRateInterface {

    /**
     * Get destinationAlphabeticCode
     *
     * @return string
     */
    public function getDestinationAlphabeticCode();

    /**
     * @return string
     */
    public function getSourceAlphabeticCode();

    /**
     * @return float
     */
    public function getRate();

} 