<?php
namespace Weasty\Money\Loader;
use Weasty\Money\Loader\BYR\BYRLoader;

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
        $parser = new BYRLoader();
        break;
      default:
        throw new LoaderException( "Loader not found for currency[$currencyCode]" );
    }

    return $parser;
  }

}