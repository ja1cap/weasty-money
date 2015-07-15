<?php
namespace Weasty\Money\Twig;

use Symfony\Component\Intl\Intl;
use Weasty\Money\Currency\CurrencyResource;
use Weasty\Money\Price\Formatter\PriceFormatterInterface;
use Weasty\Money\Price\PriceInterface;
use Weasty\Money\Currency\Code\CurrencyCodeConverterInterface;
use Weasty\Money\Currency\Converter\CurrencyConverterInterface;

/**
 * Class AbstractMoneyExtension
 * @package Weasty\Money\Twig
 */
abstract class AbstractMoneyExtension extends \Twig_Extension {

    /**
     * @var PriceFormatterInterface
     */
    protected $priceFormatter;

    /**
     * AbstractMoneyExtension constructor.
     *
     * @param PriceFormatterInterface $priceFormatter
     */
    public function __construct( PriceFormatterInterface $priceFormatter ) {
        $this->priceFormatter = $priceFormatter;
    }

    /**
     * @return PriceFormatterInterface
     */
    public function getPriceFormatter() {
        return $this->priceFormatter;
    }

}