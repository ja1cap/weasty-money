<?php

namespace Weasty\Money\Entity;

use Weasty\Doctrine\Entity\AbstractRepository;

/**
 * Class CurrencyRateRepository
 * @package Weasty\Money\Entity
 */
abstract class CurrencyRateRepository extends AbstractRepository {

  /**
   * @param string|null $sourceAlphabeticCode
   * @param string|null $destinationCurrencyCode
   *
   * @return \Weasty\Money\Entity\CurrencyRate[]
   * @throws \Exception
   */
  public function updateFromOfficial( $sourceAlphabeticCode = null, $destinationCurrencyCode = null ) {

    if ( $sourceAlphabeticCode || $destinationCurrencyCode ) {
      $currencies = $this->findBy(
        array_filter(
          [
            'sourceAlphabeticCode'    => (string) $sourceAlphabeticCode,
            'destinationAlphabeticCode' => (string) $destinationCurrencyCode,
          ]
        )
      );
    }
    else {
      $currencies = $this->findAll();
    }

    $updatedCurrencies = [ ];
    foreach ( $currencies as $currency ) {
      if ( $currency instanceof CurrencyRate ) {
        if ( !$currency->isUpdatableFromOfficial() ) {
          continue;
        }
        $officialCurrency = $this->getOfficialCurrencyRepository()->findOneBy(
          [
            'sourceAlphabeticCode'    => $currency->getSourceAlphabeticCode(),
            'destinationAlphabeticCode' => $currency->getDestinationAlphabeticCode(),
          ]
        );
        if ( !$officialCurrency instanceof OfficialCurrencyRate ) {
          continue;
        }
        switch ( $currency->getOfficialOffsetType() ) {
          case CurrencyRate::OFFICIAL_OFFSET_TYPE_PERCENT:
            $newRate = $officialCurrency->getRate() * ( ( 100 + $currency->getOfficialOffsetPercent() ) / 100 );
            break;
          case CurrencyRate::OFFICIAL_OFFSET_TYPE_VALUE:
            $newRate = $officialCurrency->getRate() + $currency->getOfficialOffsetValue();
            break;
          default:
            throw new \Exception( "Undefined official currency offset type[{$currency->getOfficialOffsetType()}]" );
        }
        if ( $newRate != $currency->getRate() ) {
          $currency->setRate( $newRate );
          $updatedCurrencies[] = $currency;
        }
      }
    }

    if ( $updatedCurrencies ) {
      $this->getEntityManager()->flush( $updatedCurrencies );
    }

    return $updatedCurrencies;
  }

  /**
   * @return \Weasty\Money\Entity\OfficialCurrencyRateRepository
   */
  abstract protected function getOfficialCurrencyRepository();

}
