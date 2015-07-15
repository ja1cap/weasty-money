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
    protected $moneyFormatter;

    /**
     * AbstractMoneyExtension constructor.
     *
     * @param MoneyFormatterInterface $moneyFormatter
     */
    public function __construct( MoneyFormatterInterface $moneyFormatter ) {
        $this->moneyFormatter = $moneyFormatter;
    }

    /**
     * @return \Weasty\Money\Formatter\Money\MoneyFormatterInterface
     */
    public function getMoneyFormatter() {
        return $this->moneyFormatter;
    }

}