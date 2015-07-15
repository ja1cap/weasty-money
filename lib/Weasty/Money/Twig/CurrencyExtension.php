<?php
namespace Weasty\Money\Twig;

use Weasty\Money\Currency\Formatter\CurrencyFormatterInterface;

/**
 * Class CurrencyExtension
 * @package Weasty\Money\Twig
 */
class CurrencyExtension extends \Twig_Extension {

    /**
     * @var CurrencyFormatterInterface
     */
    protected $currencyFormatter;

    /**
     * @param CurrencyFormatterInterface $currencyFormatter
     */
    function __construct(CurrencyFormatterInterface $currencyFormatter)
    {
        $this->currencyFormatter = $currencyFormatter;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('weasty_currency_name', array($this->getCurrencyFormatter(), 'formatName'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('weasty_currency_symbol', array($this->getCurrencyFormatter(), 'formatSymbol'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('weasty_currency_code', array($this->getCurrencyFormatter(), 'formatCode'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('weasty_currency_numeric_code', array($this->getCurrencyFormatter(), 'formatNumericCode'), array('is_safe' => array('html'))),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'weasty_money_currency';
    }

    /**
     * @return CurrencyFormatterInterface
     */
    public function getCurrencyFormatter() {
        return $this->currencyFormatter;
    }

} 