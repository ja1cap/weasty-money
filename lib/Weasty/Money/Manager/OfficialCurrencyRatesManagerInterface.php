<?php
namespace Weasty\Money\Manager;

/**
 * Interface OfficialCurrencyRatesManagerInterface
 * @package Weasty\Money\Manager
 */
interface OfficialCurrencyRatesManagerInterface
{

  /**
   * @param $sourceCurrencyCode
   * @param bool $flush
   */
  public function updateRepositoryFromRemote($sourceCurrencyCode, $flush = true);

}