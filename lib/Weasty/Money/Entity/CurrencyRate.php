<?php

namespace Weasty\Money\Entity;

use Doctrine\ORM\Mapping as ORM;
use Weasty\Money\Currency\Rate\UpdatableFromOfficialCurrencyRateInterface;

/**
 * Class CurrencyRate
 * @package Weasty\Money\Entity
 */
class CurrencyRate extends AbstractCurrencyRate implements UpdatableFromOfficialCurrencyRateInterface
{

    /**
     * @var boolean
     */
    protected $updatableFromOfficial = true;

    /**
     * @var integer
     */
    protected $officialOffsetType = self::OFFICIAL_OFFSET_TYPE_PERCENT;

    /**
     * @var float
     */
    protected $officialOffsetPercent = 0;

    /**
     * @var float
     */
    protected $officialOffsetValue = 0;

    /**
     * @return boolean
     */
    public function isUpdatableFromOfficial()
    {
        return $this->updatableFromOfficial;
    }

    /**
     * @return boolean
     */
    public function getUpdatableFromOfficial()
    {
        return $this->isUpdatableFromOfficial();
    }

    /**
     * @param boolean $updatableFromOfficial
     */
    public function setUpdatableFromOfficial($updatableFromOfficial)
    {
        $this->updatableFromOfficial = $updatableFromOfficial;
    }

    /**
     * @return int
     */
    public function getOfficialOffsetType()
    {
        return $this->officialOffsetType;
    }

    /**
     * @param int $officialOffsetType
     */
    public function setOfficialOffsetType($officialOffsetType)
    {
        $this->officialOffsetType = $officialOffsetType;
    }

    /**
     * @return float
     */
    public function getOfficialOffsetPercent()
    {
        return $this->officialOffsetPercent;
    }

    /**
     * @param float $officialOffsetPercent
     */
    public function setOfficialOffsetPercent($officialOffsetPercent)
    {
        $this->officialOffsetPercent = $officialOffsetPercent;
    }

    /**
     * @return float
     */
    public function getOfficialOffsetValue()
    {
        return $this->officialOffsetValue;
    }

    /**
     * @param float $officialOffsetValue
     */
    public function setOfficialOffsetValue($officialOffsetValue)
    {
        $this->officialOffsetValue = $officialOffsetValue;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return parent::toArray() + [
            'updatableFromOfficial' => $this->getUpdatableFromOfficial(),
            'officialOffsetType' => $this->getOfficialOffsetType(),
            'officialOffsetPercent' => $this->getOfficialOffsetPercent(),
            'officialOffsetValue' => $this->getOfficialOffsetValue(),
        ];
    }

}
