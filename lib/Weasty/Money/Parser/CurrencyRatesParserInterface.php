<?php
namespace Weasty\Money\Parser;

/**
 * Interface CurrencyRatesParserInterface
 * @package Weasty\Money\Parser
 */
interface CurrencyRatesParserInterface {

  /**
   * @param \DateTime|null $dateTime
   * @return \Weasty\Money\Currency\Rate\CurrencyRateInterface[]
   * @throws \Weasty\Money\Parser\CurrencyRatesParserException
   */
  public function parse(\DateTime $dateTime = null);

}