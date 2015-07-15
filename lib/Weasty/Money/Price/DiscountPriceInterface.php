<?php
namespace Weasty\Money\Price;

/**
 * Interface DiscountPriceInterface
 * @package Weasty\Money\Price
 */
interface DiscountPriceInterface extends PriceInterface {

  /**
   * @return \Weasty\Money\Price\PriceInterface
   */
  public function getOriginalPrice();

  /**
   * @return float|null
   */
  public function getDiscountPercent();

}