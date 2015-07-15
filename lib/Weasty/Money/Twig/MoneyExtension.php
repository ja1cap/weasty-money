<?php
namespace Weasty\Money\Twig;

/**
 * Class MoneyExtension
 * @package Weasty\Money\Twig
 */
class MoneyExtension extends AbstractMoneyExtension
{

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('weasty_price', array($this->getPriceFormatter(), 'formatPrice'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('weasty_money', array($this->getPriceFormatter(), 'formatMoney'), array('is_safe' => array('html'))),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'weasty_price_extension';
    }

} 