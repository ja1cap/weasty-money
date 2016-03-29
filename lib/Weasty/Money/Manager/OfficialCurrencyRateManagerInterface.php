<?php
namespace Weasty\Money\Manager;

/**
 * Interface OfficialCurrencyRateManagerInterface
 * @package Weasty\Money\Manager
 */
interface OfficialCurrencyRateManagerInterface
{

  /**
   * @param $currencyCode
   * @param bool $flush
   */
  public function updateRepositoryFromRemote($currencyCode, $flush = true);

  /**
   * @return \Weasty\Doctrine\Entity\AbstractRepository
   */
  public function getOfficialCurrencyRateRepository();

}