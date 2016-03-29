<?php
namespace Weasty\Money\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Weasty\Money\Currency\CurrencyResource;
use Weasty\Money\Entity\CurrencyRate;
use Weasty\Money\Entity\OfficialCurrencyRate;

/**
 * Class CurrencyRateManager
 * @package Weasty\Bundle\MoneyBundle\Manager
 */
class CurrencyRateManager implements CurrencyRateManagerInterface
{

  /**
   * @var \Doctrine\ORM\EntityManager
   */
  protected $em;

  /**
   * @var \Weasty\Money\Currency\CurrencyResource
   */
  protected $currencyResource;

  /**
   * @var \Weasty\Money\Manager\OfficialCurrencyRateManagerInterface
   */
  protected $officialCurrencyRateManager;

  /**
   * @var \Weasty\Doctrine\Entity\AbstractRepository[]
   */
  protected $currencyRateRepositories = [];

  /**
   * CurrencyRateManager constructor.
   * @param \Doctrine\ORM\EntityManager $em
   * @param \Weasty\Money\Currency\CurrencyResource $currencyResource
   * @param \Weasty\Money\Manager\OfficialCurrencyRateManagerInterface $officialCurrencyRateManager
   * @param \Weasty\Doctrine\Entity\AbstractRepository[] $currencyRatesRepositories
   */
  public function __construct(EntityManager $em, CurrencyResource $currencyResource, OfficialCurrencyRateManagerInterface $officialCurrencyRateManager, array $currencyRatesRepositories = [])
  {
    $this->em = $em;
    $this->currencyResource = $currencyResource;
    $this->officialCurrencyRateManager = $officialCurrencyRateManager;
    $this->currencyRateRepositories = $currencyRatesRepositories;
  }

  /**
   * @param array $codes
   * @param bool $upsertDefault
   * @param bool $updateExistingFromOfficial
   * @param OutputInterface|null $output
   */
  public function upsert( array $codes = [], $upsertDefault = false, $updateExistingFromOfficial = false, OutputInterface $output = null )
  {

    if(!$output){
      $output = new NullOutput();
    }

    foreach ($this->getCurrencyRateRepositories() as $currencyRateRepository){

      $currencyResource = $this->currencyResource;
      $defaultCurrency  = $currencyResource->getCurrency( $currencyResource->getDefaultCurrency() );

      if ( $upsertDefault ) {
        $currencies = $currencyResource->getCurrencies();
      }
      else {
        $currencies = array_map(
          function ( $code ) use ( $currencyResource ) {
            return $currencyResource->getCurrency( $code );
          },
          $codes
        );
      }

      $output->writeln( "<info>Update official currency rates from remote</info>" );
      $this->getOfficialCurrencyRateManager()->updateRepositoryFromRemote( $defaultCurrency->getAlphabeticCode() );

      /**
       * @var $currencies \Weasty\Money\Currency\CurrencyInterface[]
       */
      foreach ( $currencies as $currency ) {

        if( $currency->getAlphabeticCode() == $defaultCurrency->getAlphabeticCode() ){
          continue;
        }

        $output->writeln( "<info>Update currency rate {$currency->getName()}[{$currency->getAlphabeticCode()}]</info>" );

        /**
         * @var $currencyRate \Weasty\Bundle\MoneyBundle\Entity\CurrencyRate
         */
        $currencyRate = $currencyRateRepository->findOneBy(
          [
            'sourceAlphabeticCode'      => $currency->getAlphabeticCode(),
            'destinationAlphabeticCode' => $currencyResource->getDefaultCurrency(),
          ]
        );

        $officialCurrencyRate = $this->getOfficialCurrencyRateManager()->getOfficialCurrencyRateRepository()->findOneBy(
          [
            'sourceAlphabeticCode'      => $currency->getAlphabeticCode(),
            'destinationAlphabeticCode' => $currencyResource->getDefaultCurrency(),
          ]
        );
        if ( !$officialCurrencyRate instanceof OfficialCurrencyRate ) {
          $output->writeln( "<error>Official currency rate not found[{$currency->getAlphabeticCode()}]</error>" );
          continue;
        }

        if ( !$currencyRate ) {
          $currencyRate = $currencyRateRepository->create();
          $currencyRate->setSourceAlphabeticCode( $currency->getAlphabeticCode() );
          $currencyRate->setSourceNumericCode( $currency->getNumericCode() );
          $currencyRate->setDestinationAlphabeticCode( $defaultCurrency->getAlphabeticCode() );
          $currencyRate->setDestinationNumericCode( $defaultCurrency->getNumericCode() );
          $currencyRate->setRate( $officialCurrencyRate->getRate() );
          $this->em->persist( $currencyRate );
        }
        elseif ( $updateExistingFromOfficial || $currencyRate->isUpdatableFromOfficial() || empty( $currencyRate->getRate() ) ) {
          $currencyRate->setRate( $officialCurrencyRate->getRate() );
        }

        $output->writeln( "<info>Currency rate {$currencyRate->getRate()} {$defaultCurrency->getSymbol()}</info>" );

      }

    }

    $this->em->flush();

  }

  /**
   * @param string|null $sourceAlphabeticCode
   * @param string|null $destinationCurrencyCode
   *
   * @return \Weasty\Money\Entity\CurrencyRate[]
   * @throws \Exception
   */
  public function updateFromOfficial($sourceAlphabeticCode = null, $destinationCurrencyCode = null)
  {

    $updatedCurrencies = [];

    foreach ($this->getCurrencyRateRepositories() as $currencyRateRepository) {

      if ($sourceAlphabeticCode || $destinationCurrencyCode) {
        $currencies = $currencyRateRepository->findBy(
          array_filter(
            [
              'sourceAlphabeticCode' => (string)$sourceAlphabeticCode,
              'destinationAlphabeticCode' => (string)$destinationCurrencyCode,
            ]
          )
        );
      } else {
        $currencies = $currencyRateRepository->findAll();
      }

      foreach ($currencies as $currency) {
        if ($currency instanceof CurrencyRate) {
          if (!$currency->isUpdatableFromOfficial()) {
            continue;
          }
          $officialCurrency = $this->getOfficialCurrencyRateManager()->getOfficialCurrencyRateRepository()->findOneBy(
            [
              'sourceAlphabeticCode' => $currency->getSourceAlphabeticCode(),
              'destinationAlphabeticCode' => $currency->getDestinationAlphabeticCode(),
            ]
          );
          if (!$officialCurrency instanceof OfficialCurrencyRate) {
            continue;
          }
          switch ($currency->getOfficialOffsetType()) {
            case CurrencyRate::OFFICIAL_OFFSET_TYPE_PERCENT:
              $newRate = $officialCurrency->getRate() * ((100 + $currency->getOfficialOffsetPercent()) / 100);
              break;
            case CurrencyRate::OFFICIAL_OFFSET_TYPE_VALUE:
              $newRate = $officialCurrency->getRate() + $currency->getOfficialOffsetValue();
              break;
            default:
              throw new \Exception("Undefined official currency offset type[{$currency->getOfficialOffsetType()}]");
          }
          if ($newRate != $currency->getRate()) {
            $currency->setRate($newRate);
            $updatedCurrencies[] = $currency;
          }
        }
      }

    }

    if ($updatedCurrencies) {
      $this->em->flush($updatedCurrencies);
    }

    return $updatedCurrencies;
  }

  /**
   * @param \Weasty\Doctrine\Entity\AbstractRepository $repository
   */
  public function addCurrencyRateRepository($repository)
  {
    $this->currencyRateRepositories[] = $repository;
  }

  /**
   * @return \Weasty\Doctrine\Entity\AbstractRepository[]
   */
  public function getCurrencyRateRepositories()
  {
    return $this->currencyRateRepositories;
  }

  /**
   * @return \Weasty\Money\Manager\OfficialCurrencyRateManagerInterface
   */
  public function getOfficialCurrencyRateManager()
  {
    return $this->officialCurrencyRateManager;
  }

}