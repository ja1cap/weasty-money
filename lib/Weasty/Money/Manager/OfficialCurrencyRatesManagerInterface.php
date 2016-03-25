<?php
namespace Weasty\Money\Manager;

/**
 * Interface OfficialCurrencyRatesManagerInterface
 * @package Weasty\Money\Manager
 */
interface OfficialCurrencyRatesManagerInterface
{

  /**
   * @param $currencyCode
   * @param bool $flush
   */
  public function updateRepositoryFromRemote($currencyCode, $flush = true);

}