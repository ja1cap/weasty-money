<?php

namespace Weasty\Money\Entity;

use Weasty\Doctrine\Entity\AbstractRepository;

/**
 * Class CurrencyRateRepository
 * @package Weasty\Money\Entity
 */
abstract class CurrencyRateRepository extends AbstractRepository {

  /**
   * @return \Weasty\Money\Entity\CurrencyRate[]
   * @throws \Exception
   */
  public function updateFromOfficial() {
    $currencies        = $this->findAll();
    $updatedCurrencies = [ ];
    foreach ( $currencies as $currency ) {
      if ( $currency instanceof CurrencyRate ) {
        if ( !$currency->isUpatableFromOfficial() ) {
          continue;
        }
        $officialCurrency = $this->getOfficialCurrencyRepository()->findOneBy(
          [
            'sourceAlphabeticCode'    => $currency->getSourceAlphabeticCode(),
            'destinationCurrencyCode' => $currency->getDestinationAlphabeticCode(),
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
   * @return \Weasty\Doctrine\Entity\AbstractRepository
   */
  abstract protected function getOfficialCurrencyRepository();

}
