<?php
namespace Weasty\Money\Manager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Output\OutputInterface;
use Weasty\Money\Currency\Rate\UpdatableFromOfficialCurrencyRateInterface;

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
   * @param OutputInterface|null $output
   * @param \Doctrine\ORM\EntityManager|null $em
   */
  public function upsert(array $codes = [], $upsertDefault = false, $updateExistingFromOfficial = false, OutputInterface $output = null, EntityManager $em);

  /**
   * @param string|null $sourceAlphabeticCode
   * @param string|null $destinationCurrencyCode
   * @param \Doctrine\ORM\EntityManager $em
   *
   * @return \Weasty\Money\Currency\Rate\UpdatableFromOfficialCurrencyRateInterface[]
   * @throws \Exception
   */
  public function updateFromOfficial($sourceAlphabeticCode = null, $destinationCurrencyCode = null, EntityManager $em);

  /**
   * @param \Weasty\Money\Currency\Rate\UpdatableFromOfficialCurrencyRateInterface $currencyRate
   * @return bool
   * @throws \Exception
   */
  public function updateCurrencyFromOfficial(UpdatableFromOfficialCurrencyRateInterface $currencyRate);

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