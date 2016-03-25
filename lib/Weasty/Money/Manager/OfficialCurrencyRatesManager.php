<?php
namespace Weasty\Money\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Weasty\Doctrine\Entity\AbstractRepository;
use Weasty\Money\Currency\Rate\CurrencyRateInterface;
use Weasty\Money\Entity\OfficialCurrencyRate;
use Weasty\Money\Loader\Exception\RecordsNotFoundException;
use Weasty\Money\Loader\LoaderFactoryInterface;
use Weasty\Money\Mapper\CurrencyRateMapper;

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
   * @var \Weasty\Money\Mapper\CurrencyRateMapper
   */
  protected $currencyRateMapper;

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
   * @param EntityManagerInterface $em
   * @param \Weasty\Money\Mapper\CurrencyRateMapper $currencyRateMapper
   * @param AbstractRepository $officialCurrencyRateRepository
   * @param LoaderFactoryInterface $currencyRatesLoaderFactory
   */
  public function __construct(EntityManagerInterface $em, CurrencyRateMapper $currencyRateMapper, AbstractRepository $officialCurrencyRateRepository, LoaderFactoryInterface $currencyRatesLoaderFactory)
  {
    $this->em = $em;
    $this->currencyRateMapper = $currencyRateMapper;
    $this->officialCurrencyRateRepository = $officialCurrencyRateRepository;
    $this->currencyRatesLoaderFactory = $currencyRatesLoaderFactory;
  }

  /**
   * @param $currencyCode
   * @param bool $flush
   */
  public function updateRepositoryFromRemote($currencyCode, $flush = true)
  {

    $loader = $this->currencyRatesLoaderFactory->create( $currencyCode);

    try {
      $records = $loader->load(new \DateTime('tomorrow'));
    } catch (RecordsNotFoundException $e) {
      $records = $loader->load(new \DateTime('today'));
    }

    /**
     * @var OfficialCurrencyRate[] $officialCurrencyRates
     * @var OfficialCurrencyRate[] $officialCurrencyRatesIndexedByCode
     */
    $officialCurrencyRates = $this->officialCurrencyRateRepository->findAll();
    $officialCurrencyRatesIndexedByCode = array_combine(
      array_map(function (CurrencyRateInterface $currencyRate) {
        return $currencyRate->getSourceAlphabeticCode();
      }, $officialCurrencyRates),
      $officialCurrencyRates
    );

    $this->currencyRateMapper->setEntityManager($this->em);

    foreach ($records as $record) {

      if (!empty($officialCurrencyRatesIndexedByCode[$record->getSourceAlphabeticCode()])) {
        $this->currencyRateMapper->setEntity(
          $officialCurrencyRatesIndexedByCode[$record->getSourceAlphabeticCode()]
        );
      } else {
        /**
         * @var $officialCurrencyRate OfficialCurrencyRate
         */
        $officialCurrencyRate = $this->officialCurrencyRateRepository->create();
        $this->currencyRateMapper->setEntity($officialCurrencyRate);
        $this->currencyRateMapper
          ->setSourceAlphabeticCode($record->getSourceAlphabeticCode())
          ->setDestinationAlphabeticCode($record->getDestinationAlphabeticCode())
        ;
        $this->em->persist($this->currencyRateMapper->getCurrencyRate());
      }

      $officialCurrencyRate = $this->currencyRateMapper->getCurrencyRate();
      $officialCurrencyRate->setRate($record->getRate());

    }

    if ($flush) {
      $this->em->flush();
    }

  }

}