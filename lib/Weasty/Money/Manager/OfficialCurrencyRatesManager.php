<?php
namespace Weasty\Money\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Weasty\Doctrine\Entity\AbstractRepository;
use Weasty\Money\Currency\Rate\CurrencyRateInterface;
use Weasty\Money\Entity\OfficialCurrencyRate;
use Weasty\Money\Loader\Exception\RecordsNotFoundException;
use Weasty\Money\Loader\LoaderFactoryInterface;

/**
 * Class OfficialCurrencyRatesManager
 * @package Weasty\Money\Manager
 */
class OfficialCurrencyRatesManager implements OfficialCurrencyRatesManagerInterface
{

  /**
   * @var \Doctrine\ORM\EntityManagerInterface
   */
  protected $em;

  /**
   * @var \Weasty\Doctrine\Entity\AbstractRepository
   */
  protected $officialCurrencyRateRepository;

  /**
   * @var \Weasty\Money\Loader\LoaderFactoryInterface
   */
  protected $currencyRatesLoaderFactory;

  /**
   * OfficialCurrencyRatesManager constructor.
   * @param \Doctrine\ORM\EntityManagerInterface $em
   * @param AbstractRepository $officialCurrencyRateRepository
   * @param LoaderFactoryInterface $currencyRatesLoaderFactory
   */
  public function __construct(EntityManagerInterface $em, AbstractRepository $officialCurrencyRateRepository, LoaderFactoryInterface $currencyRatesLoaderFactory)
  {
    $this->em = $em;
    $this->officialCurrencyRateRepository = $officialCurrencyRateRepository;
    $this->currencyRatesLoaderFactory = $currencyRatesLoaderFactory;
  }

  /**
   * @param $sourceCurrencyCode
   * @param bool $flush
   */
  public function updateRepositoryFromRemote($sourceCurrencyCode, $flush = true)
  {

    $loader = $this->currencyRatesLoaderFactory->create($sourceCurrencyCode);

    try {
      $records = $loader->load(new \DateTime('tomorrow'));
    } catch (RecordsNotFoundException $e) {
      $records = $loader->load(new \DateTime('today'));
    }

    /**
     * @var \Weasty\Money\Currency\Rate\CurrencyRateInterface[] $recordsIndexedByCode
     */
    $recordsIndexedByCode = array_combine(
      array_map(function (CurrencyRateInterface $record) {
        return $record->getDestinationAlphabeticCode();
      }, $records),
      $records
    );

    /**
     * @var OfficialCurrencyRate[] $officialCurrencies
     */
    $officialCurrencies = $this->officialCurrencyRateRepository->findAll();
    foreach ($officialCurrencies as $officialCurrency) {
      if (empty($recordsIndexedByCode[$officialCurrency->getDestinationAlphabeticCode()])) {
        continue;
      }
      $record = $recordsIndexedByCode[$officialCurrency->getDestinationAlphabeticCode()];
      $officialCurrency->setRate($record->getRate());
    }

    if ($flush) {
      $this->em->flush();
    }

  }

}