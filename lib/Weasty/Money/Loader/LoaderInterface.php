<?php
namespace Weasty\Money\Loader;

/**
 * Interface LoaderInterface
 * @package Weasty\Money\Loader
 */
interface LoaderInterface {

  /**
   * @param \DateTime|null $dateTime
   * @return \Weasty\Money\Currency\Rate\CurrencyRateInterface[]
   * @throws \Weasty\Money\Loader\LoaderException
   */
  public function load(\DateTime $dateTime = null);

}