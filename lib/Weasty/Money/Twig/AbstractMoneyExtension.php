<?php
namespace Weasty\Money\Twig;

use Weasty\Money\Formatter\Money\MoneyFormatterInterface;

/**
 * Class AbstractMoneyExtension
 * @package Weasty\Money\Twig
 */
abstract class AbstractMoneyExtension extends \Twig_Extension implements MoneyFormatterInterface
{

    /**
     * @var MoneyFormatterInterface
     */
    protected $moneyFormatter;

    /**
     * AbstractMoneyExtension constructor.
     *
     * @param MoneyFormatterInterface $moneyFormatter
     */
    public function __construct(MoneyFormatterInterface $moneyFormatter)
    {
        $this->moneyFormatter = $moneyFormatter;
    }

    /**
     * @return \Weasty\Money\Formatter\Money\MoneyFormatterInterface
     */
    public function getMoneyFormatter()
    {
        return $this->moneyFormatter;
    }

    /**
     * @param string|null|\Weasty\Money\Price\PriceInterface $value
     * @param null $sourceCurrency
     * @param null $destinationCurrency
     * @return string
     */
    public function formatPrice($value, $sourceCurrency = null, $destinationCurrency = null)
    {
        return $this->getMoneyFormatter()->formatPrice($value, $sourceCurrency, $destinationCurrency);
    }

    /**
     * @param $value
     * @param $currency
     * @param boolean $appendSymbol
     * @return string
     */
    public function formatMoney($value, $currency = null, $appendSymbol = true)
    {
        return $this->getMoneyFormatter()->formatMoney($value, $currency, $appendSymbol);
    }

}