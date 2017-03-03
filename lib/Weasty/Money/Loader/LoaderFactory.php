<?php
namespace Weasty\Money\Loader;
use Weasty\Money\Loader\BYN\BYNLoader;
use Weasty\Money\Loader\BYR\BYRLoader;
use Weasty\Money\Loader\RUB\RUBLoader;

/**
 * Class LoaderFactory
 * @package Weasty\Money\Loader
 */
class LoaderFactory implements LoaderFactoryInterface{

  /**
   * @param string $currencyCode - Currency ISO-4217-ALPHA code
   *
   * @return \Weasty\Money\Loader\LoaderInterface
   * @throws \Weasty\Money\Loader\LoaderException
   */
  public function create( $currencyCode ) {
    switch ( $currencyCode ) {
      case 'BYR':
        $loader = new BYRLoader();
        break;
      case 'BYN':
        $loader = new BYNLoader();
        break;
      case 'RUB':
        $loader = new RUBLoader();
        break;
      default:
        throw new LoaderException( "Loader not found for currency[$currencyCode]" );
    }

    return $loader;
  }

}