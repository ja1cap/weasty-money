<?php
namespace Weasty\Money\Twig;

use Weasty\Money\Formatter\Money\MoneyFormatterInterface;

/**
 * Class AbstractMoneyExtension
 * @package Weasty\Money\Twig
 */
abstract class AbstractMoneyExtension extends \Twig_Extension {

    /**
     * @var MoneyFormatterInterface
     */
    protected $priceFormatter;

    /**
     * AbstractMoneyExtension constructor.
     *
     * @param MoneyFormatterInterface $priceFormatter
     */
    public function __construct( MoneyFormatterInterface $priceFormatter ) {
        $this->priceFormatter = $priceFormatter;
    }

    /**
     * @return \Weasty\Money\Formatter\MoneyFormatterInterface
     */
    public function getPriceFormatter() {
        return $this->priceFormatter;
    }

}