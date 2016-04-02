<?php
namespace Weasty\Money\Currency\Rate;

/**
 * Interface UpdatableFromOfficialCurrencyRateInterface
 * @package Weasty\Money\Currency\Rate
 */
interface UpdatableFromOfficialCurrencyRateInterface extends CurrencyRateInterface
{

    const OFFICIAL_OFFSET_TYPE_PERCENT = 1;
    const OFFICIAL_OFFSET_TYPE_VALUE = 2;

    /**
     * @return boolean
     */
    public function isUpdatableFromOfficial();

    /**
     * @return boolean
     */
    public function getUpdatableFromOfficial();

    /**
     * @param boolean $updatableFromOfficial
     */
    public function setUpdatableFromOfficial($updatableFromOfficial);

    /**
     * @return int
     */
    public function getOfficialOffsetType();

    /**
     * @param int $officialOffsetType
     */
    public function setOfficialOffsetType($officialOffsetType);

    /**
     * @return float
     */
    public function getOfficialOffsetPercent();

    /**
     * @param float $officialOffsetPercent
     */
    public function setOfficialOffsetPercent($officialOffsetPercent);

    /**
     * @return float
     */
    public function getOfficialOffsetValue();

    /**
     * @param float $officialOffsetValue
     */
    public function setOfficialOffsetValue($officialOffsetValue);

    /**
     * Set rate
     *
     * @param float $rate
     * @return $this
     */
    public function setRate($rate);

}