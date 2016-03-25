<?php
namespace Weasty\Money\Currency;

use Symfony\Component\Intl\Intl;

/**
 * Class CurrencyResource
 * @package Weasty\Money\Currency
 */
class CurrencyResource {

  const CODE_TYPE_ISO_4217_NUMERIC = 'ISO-4217-NUM';
  const CODE_TYPE_ISO_4217_ALPHABETIC = 'ISO-4217-ALPHA';

  /**
   * @var array
   */
  protected $currencies;

  /**
   * @var string
   */
  protected $defaultCurrency;

  /**
   * @var string
   */
  protected $locale;

  /**
   * @var \Symfony\Component\Intl\ResourceBundle\CurrencyBundle
   */
  protected $currencyBundle;

  /**
   * @param $currencies
   * @param $defaultCurrency
   * @param $locale
   */
  function __construct( $currencies = [ ], $defaultCurrency, $locale ) {

    $this->currencyBundle = Intl::getCurrencyBundle();

    $this->defaultCurrency = $defaultCurrency;
    $this->locale          = $locale;

    $this->currencies = [ ];

    if ( empty( $currencies ) ) {
      $currencies = array_fill_keys( $this->getCurrencyBundle()->getCurrencies(), [ ] );
    }

    if ( is_array( $currencies ) ) {
      foreach ( $currencies as $alphabeticCode => $data ) {
        $currency = $this->buildCurrency( $alphabeticCode, $data );
        if ( $currency ) {
          $this->currencies[ $alphabeticCode ] = $currency;
        }
      }
    }

  }

  /**
   * @param $alphabeticCode
   * @param array $data
   *
   * @return Currency|null
   */
  protected function buildCurrency( $alphabeticCode, array $data = array() ) {

    $defaultData = [
      'name'               => $alphabeticCode,
      'symbol'             => $alphabeticCode,
      'alphabeticCode'     => $alphabeticCode,
      'numericCode'        => 0,
      'decimalDigits'      => 2,
      'decimalPoint'       => '.',
      'thousandsSeparator' => ' ',
    ];

    try {

      $defaultData['name']          = $this->getCurrencyBundle()->getCurrencyName(
        $alphabeticCode,
        $this->getLocale()
      );
      $defaultData['symbol']        = $this->currencyBundle->getCurrencySymbol( $alphabeticCode, $this->getLocale() );
      $defaultData['numericCode']   = $this->getCurrencyBundle()->getNumericCode( $alphabeticCode );
      $defaultData['decimalDigits'] = $this->getCurrencyBundle()->getFractionDigits( $alphabeticCode );

    }
    catch ( \Exception $e ) {
      trigger_error( $e->getMessage(), E_USER_WARNING );
    }

    $currency = new Currency( $data + $defaultData );

    return $currency;

  }

  /**
   * @param $alphabeticCode
   *
   * @return null|Currency|array
   */
  public function getCurrency( $alphabeticCode ) {
    if ( !isset( $this->currencies[ $alphabeticCode ] ) ) {
      $this->currencies[ $alphabeticCode ] = $this->buildCurrency( $alphabeticCode );
    }

    return $this->currencies[ $alphabeticCode ];
  }

  /**
   * @param $alphabeticCode
   * @param $parameterName
   *
   * @return mixed
   */
  public function getCurrencyParameter( $alphabeticCode, $parameterName ) {

    $currency = $this->getCurrency( $alphabeticCode );

    if ( $currency && isset( $currency[ $parameterName ] ) ) {
      return $currency[ $parameterName ];
    }

    return null;

  }

  /**
   * @return array
   */
  public function getCurrencies() {
    return $this->currencies;
  }

  /**
   * @return array
   */
  public function getCurrencyAlphabeticCodes() {
    return array_keys( $this->getCurrencies() );
  }

  /**
   * @param $currencyAlphabeticCode
   *
   * @return null|string
   */
  public function getCurrencyName( $currencyAlphabeticCode ) {
    return $this->getCurrencyParameter( $currencyAlphabeticCode, 'name' );
  }

  /**
   * @param $currencyAlphabeticCode
   *
   * @return null|string
   */
  public function getCurrencySymbol( $currencyAlphabeticCode ) {
    return $this->getCurrencyParameter( $currencyAlphabeticCode, 'symbol' );
  }

  /**
   * @param $currencyAlphabeticCode
   *
   * @return int|null
   */
  public function getCurrencyDecimalDigits( $currencyAlphabeticCode ) {
    return $this->getCurrencyParameter( $currencyAlphabeticCode, 'decimalDigits' );
  }

  /**
   * @param $currencyAlphabeticCode
   *
   * @return string
   */
  public function getCurrencyDecimalPoint( $currencyAlphabeticCode ) {
    return $this->getCurrencyParameter( $currencyAlphabeticCode, 'decimalPoint' );
  }

  /**
   * @param $currencyAlphabeticCode
   *
   * @return string
   */
  public function getCurrencyThousandsSeparator( $currencyAlphabeticCode ) {
    return $this->getCurrencyParameter( $currencyAlphabeticCode, 'thousandsSeparator' );
  }

  /**
   * @param $currencyAlphabeticCode
   *
   * @return null|integer
   */
  public function getCurrencyNumericCode( $currencyAlphabeticCode ) {
    return $this->getCurrencyParameter( $currencyAlphabeticCode, 'numericCode' );
  }

  /**
   * @param $numericCode
   *
   * @return null|string
   */
  public function getCurrencyAlphabeticCode( $numericCode ) {

    foreach ( $this->getCurrencies() as $alphabeticCode => $currency ) {
      if ( isset( $currency['numericCode'] ) && $currency['numericCode'] == $numericCode ) {
        return $alphabeticCode;
        break;
      }
    }

    return null;

  }

  /**
   * @return string
   */
  public function getDefaultCurrency() {
    return $this->defaultCurrency;
  }

  /**
   * @return string
   */
  public function getLocale() {
    return $this->locale;
  }

  /**
   * @return \Symfony\Component\Intl\ResourceBundle\CurrencyBundle
   */
  protected function getCurrencyBundle() {
    return $this->currencyBundle;
  }

} 