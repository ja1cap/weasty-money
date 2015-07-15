<?php
namespace Weasty\Money\Price;

/**
 * Class DiscountPrice
 * @package Weasty\Money\Price
 */
class DiscountPrice extends Price implements DiscountPriceInterface {


  /**
   * @var \Weasty\Money\Price\PriceInterface
   */
  protected $originalPrice;

  /**
   * @var null|float
   */
  protected $discountPercent;

  /**
   * @return \Weasty\Money\Price\PriceInterface
   */
  public function getOriginalPrice()
  {
    return $this->originalPrice;
  }

  /**
   * @param \Weasty\Money\Price\PriceInterface $originalPrice
   * @return $this
   */
  public function setOriginalPrice($originalPrice)
  {
    $this->originalPrice = $originalPrice;
    return $this;
  }

  /**
   * @return float|null
   */
  public function getDiscountPercent()
  {
    if($this->discountPercent === null){

      $perPercent = ($this->getOriginalPrice()->getValue() / 100);
      $diff = ($this->getOriginalPrice()->getValue() - $this->getValue());

      if($diff >= $perPercent){
        $discountPercent = ($diff / $perPercent);
        $this->discountPercent = round(floatval($discountPercent));
      } else {
        $this->discountPercent = 0;
      }

    }
    return $this->discountPercent;
  }


  /**
   * @param float|null $discountPercent
   * @return $this
   */
  public function setDiscountPercent($discountPercent)
  {
    $this->discountPercent = $discountPercent;
    return $this;
  }

}