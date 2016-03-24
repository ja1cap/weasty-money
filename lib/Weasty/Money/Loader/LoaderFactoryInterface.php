<?php
namespace Weasty\Money\Loader;

/**
 * Interface LoaderFactoryInterface
 * @package Weasty\Money\Loader
 */
interface LoaderFactoryInterface
{

  /**
   * @param string $currencyCode - Currency ISO-4217-ALPHA code
   *
   * @return \Weasty\Money\Loader\LoaderInterface
   * @throws \Weasty\Money\Loader\LoaderException
   */
  public function create($currencyCode);

}