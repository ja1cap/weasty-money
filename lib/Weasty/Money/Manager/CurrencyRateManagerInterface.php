<?php
namespace Weasty\Money\Manager;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface CurrencyRateManagerInterface
 * @package Weasty\Bundle\MoneyBundle\Manager
 */
interface CurrencyRateManagerInterface
{

  /**
   * @param array $codes
   * @param bool $upsertDefault
   * @param bool $updateExistingFromOfficial
   * @param \Symfony\Component\Console\Output\OutputInterface|null $output
   */
  public function upsert( array $codes = [], $upsertDefault = false, $updateExistingFromOfficial = false, OutputInterface $output = null );

  /**
   * @param string|null $sourceAlphabeticCode
   * @param string|null $destinationCurrencyCode
   *
   * @return \Weasty\Money\Entity\CurrencyRate[]
   * @throws \Exception
   */
  public function updateFromOfficial($sourceAlphabeticCode = null, $destinationCurrencyCode = null);

  /**
   * @param \Weasty\Doctrine\Entity\AbstractRepository $repository
   */
  public function addCurrencyRateRepository($repository);

  /**
   * @return \Weasty\Doctrine\Entity\AbstractRepository
   */
  public function getCurrencyRateRepositories();

  /**
   * @return \Weasty\Money\Manager\OfficialCurrencyRateManagerInterface
   */
  public function getOfficialCurrencyRateManager();
  
}