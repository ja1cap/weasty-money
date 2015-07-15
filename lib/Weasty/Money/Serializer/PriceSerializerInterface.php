<?php
namespace Weasty\Money\Serializer;

use Weasty\Money\Price\PriceInterface;

/**
 * Interface PriceSerializerInterface
 * @package Weasty\Money\Serializer
 */
interface PriceSerializerInterface {

  /**
   * @param PriceInterface $price
   *
   * @return array
   */
  public function serializeAssoc( PriceInterface $price );

  /**
   * @param PriceInterface $price
   *
   * @return string
   */
  public function serializeJson( PriceInterface $price );

  /**
   * @param PriceInterface $price
   *
   * @return string
   */
  public function serialize( PriceInterface $price );

}