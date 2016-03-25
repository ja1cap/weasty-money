<?php

namespace Weasty\Money\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CurrencyRate
 * @package Weasty\Money\Entity
 */
class CurrencyRate extends AbstractCurrencyRate
{

  const OFFICIAL_OFFSET_TYPE_PERCENT = 1;
  const OFFICIAL_OFFSET_TYPE_VALUE = 2;

  /**
   * @var boolean
   */
  protected $upatableFromOfficial = true;

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
  public function isUpatableFromOfficial() {
    return $this->upatableFromOfficial;
  }

  /**
   * @param boolean $upatableFromOfficial
   */
  public function setUpatableFromOfficial( $upatableFromOfficial ) {
    $this->upatableFromOfficial = $upatableFromOfficial;
  }

  /**
   * @return int
   */
  public function getOfficialOffsetType() {
    return $this->officialOffsetType;
  }

  /**
   * @param int $officialOffsetType
   */
  public function setOfficialOffsetType( $officialOffsetType ) {
    $this->officialOffsetType = $officialOffsetType;
  }

  /**
   * @return float
   */
  public function getOfficialOffsetPercent() {
    return $this->officialOffsetPercent;
  }

  /**
   * @param float $officialOffsetPercent
   */
  public function setOfficialOffsetPercent( $officialOffsetPercent ) {
    $this->officialOffsetPercent = $officialOffsetPercent;
  }

  /**
   * @return float
   */
  public function getOfficialOffsetValue() {
    return $this->officialOffsetValue;
  }

  /**
   * @param float $officialOffsetValue
   */
  public function setOfficialOffsetValue( $officialOffsetValue ) {
    $this->officialOffsetValue = $officialOffsetValue;
  }

}
