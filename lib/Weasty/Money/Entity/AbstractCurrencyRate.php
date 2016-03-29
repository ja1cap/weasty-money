<?php
namespace Weasty\Money\Entity;

use Weasty\Doctrine\Entity\AbstractEntity;
use Weasty\Money\Currency\Rate\CurrencyRateInterface;

/**
 * Class AbstractCurrencyRate
 * @package Weasty\Money\Entity
 */
class AbstractCurrencyRate extends AbstractEntity implements CurrencyRateInterface, \JsonSerializable {

  /**
   * @var integer
   */
  protected $id;

  /**
   * @var string
   */
  protected $sourceAlphabeticCode;

  /**
   * @var integer
   */
  protected $sourceNumericCode;

  /**
   * @var string
   */
  protected $destinationAlphabeticCode;

  /**
   * @var integer
   */
  protected $destinationNumericCode;

  /**
   * @var float
   */
  protected $rate;

  /**
   * @var \DateTime
   */
  protected $createDate;

  /**
   * @var \DateTime
   */
  protected $updateDate;

  /**
   * AbstractCurrencyRate constructor.
   * @param \DateTime $createDate
   */
  public function __construct(\DateTime $createDate = null)
  {
    $this->createDate = ( $createDate ?: new \DateTime() );
  }

  public function prePersist()
  {
    $this->setCreateDate(new \DateTime());
  }

  public function preUpdate()
  {
    $this->setUpdateDate(new \DateTime());
  }

  /**
   * Get id
   *
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getName(){
    return ($this->getSourceAlphabeticCode().'/'.$this->getDestinationAlphabeticCode());
  }

  /**
   * Set sourceAlphabeticCode
   *
   * @param string $sourceAlphabeticCode
   * @return $this
   */
  public function setSourceAlphabeticCode($sourceAlphabeticCode)
  {
    $this->sourceAlphabeticCode = $sourceAlphabeticCode;

    return $this;
  }

  /**
   * Get sourceAlphabeticCode
   *
   * @return string
   */
  public function getSourceAlphabeticCode()
  {
    return $this->sourceAlphabeticCode;
  }

  /**
   * Set sourceNumericCode
   *
   * @param integer $sourceNumericCode
   * @return $this
   */
  public function setSourceNumericCode($sourceNumericCode)
  {
    $this->sourceNumericCode = $sourceNumericCode;

    return $this;
  }

  /**
   * Get sourceNumericCode
   *
   * @return integer
   */
  public function getSourceNumericCode()
  {
    return $this->sourceNumericCode;
  }

  /**
   * Set destinationAlphabeticCode
   *
   * @param string $destinationAlphabeticCode
   * @return $this
   */
  public function setDestinationAlphabeticCode($destinationAlphabeticCode)
  {
    $this->destinationAlphabeticCode = $destinationAlphabeticCode;

    return $this;
  }

  /**
   * Get destinationAlphabeticCode
   *
   * @return string
   */
  public function getDestinationAlphabeticCode()
  {
    return $this->destinationAlphabeticCode;
  }

  /**
   * Set destinationNumericCode
   *
   * @param integer $destinationNumericCode
   * @return $this
   */
  public function setDestinationNumericCode($destinationNumericCode)
  {
    $this->destinationNumericCode = $destinationNumericCode;

    return $this;
  }

  /**
   * Get destinationNumericCode
   *
   * @return integer
   */
  public function getDestinationNumericCode()
  {
    return $this->destinationNumericCode;
  }

  /**
   * Set rate
   *
   * @param float $rate
   * @return $this
   */
  public function setRate($rate)
  {
    $this->rate = $rate;

    return $this;
  }

  /**
   * Get rate
   *
   * @return float
   */
  public function getRate()
  {
    return $this->rate;
  }

  /**
   * Set createDate
   *
   * @param \DateTime $createDate
   * @return $this
   */
  public function setCreateDate($createDate)
  {
    $this->createDate = $createDate ?: new \DateTime();

    return $this;
  }

  /**
   * Get createDate
   *
   * @return \DateTime
   */
  public function getCreateDate()
  {
    return $this->createDate;
  }

  /**
   * Set updateDate
   *
   * @param \DateTime $updateDate
   * @return $this
   */
  public function setUpdateDate($updateDate)
  {
    $this->updateDate = $updateDate ?: new \DateTime();

    return $this;
  }

  /**
   * Get updateDate
   *
   * @return \DateTime
   */
  public function getUpdateDate()
  {
    return $this->updateDate;
  }

  /**
   * @return array
   */
  public function toArray()
  {
    return [
        'id' => $this->getId(),
        'sourceCurrencyAlphabeticCode' => $this->getSourceAlphabeticCode(),
        'sourceNumericCode' => $this->getSourceNumericCode(),
        'destinationAlphabeticCode' => $this->getDestinationAlphabeticCode(),
        'destinationNumericCode' => $this->getDestinationAlphabeticCode(),
        'rate' => $this->getRate(),
    ];
  }

  /**
   * Specify data which should be serialized to JSON
   * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
   * @return mixed data which can be serialized by <b>json_encode</b>,
   * which is a value of any type other than a resource.
   * @since 5.4.0
   */
  function jsonSerialize()
  {
    return $this->toArray();
  }

}