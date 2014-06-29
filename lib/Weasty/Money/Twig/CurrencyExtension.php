<?php
namespace Weasty\Money\Twig;

use Weasty\Money\Currency\Code\CurrencyCodeConverterInterface;
use Weasty\Money\Currency\CurrencyResource;

/**
 * Class CurrencyExtension
 * @package Weasty\Money\Twig
 */
class CurrencyExtension extends \Twig_Extension {

    /**
     * @var CurrencyResource
     */
    protected $currencyResource;

    /**
     * @var \Weasty\Money\Currency\Code\CurrencyCodeConverterInterface
     */
    protected $currencyCodeConverter;

    /**
     * @param \Weasty\Money\Currency\CurrencyResource $currencyResource
     * @param \Weasty\Money\Currency\Code\CurrencyCodeConverterInterface $currencyCodeConverter
     */
    function __construct(CurrencyResource $currencyResource, CurrencyCodeConverterInterface $currencyCodeConverter)
    {

        $this->currencyResource = $currencyResource;
        $this->currencyCodeConverter = $currencyCodeConverter;

    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('weasty_currency_name', array($this, 'currencyName'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('weasty_currency_symbol', array($this, 'currencySymbol'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('weasty_currency_code', array($this, 'currencyCode'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('weasty_currency_numeric_code', array($this, 'currencyNumericCode'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @param $currency
     * @return null|string
     */
    public function currencyName($currency){
        $code = $this->getCurrencyCodeConverter()->convert($currency, CurrencyResource::CODE_TYPE_ISO_4217_ALPHABETIC);
        return $this->getCurrencyResource()->getCurrencyName($code);
    }

    /**
     * @param $currency
     * @return null|string
     */
    public function currencySymbol($currency){
        $code = $this->getCurrencyCodeConverter()->convert($currency, CurrencyResource::CODE_TYPE_ISO_4217_ALPHABETIC);
        return $this->getCurrencyResource()->getCurrencySymbol($code);
    }

    /**
     * @param $currency
     * @param $type
     * @return null|integer
     */
    public function currencyCode($currency, $type = CurrencyResource::CODE_TYPE_ISO_4217_ALPHABETIC){
        return $this->getCurrencyCodeConverter()->convert($currency, $type);
    }

    /**
     * @param $currency
     * @return null|integer
     */
    public function currencyNumericCode($currency){
        return $this->getCurrencyCodeConverter()->convert($currency, CurrencyResource::CODE_TYPE_ISO_4217_NUMERIC);
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
     * @return \Weasty\Money\Currency\CurrencyResource
     */
    public function getCurrencyResource()
    {
        return $this->currencyResource;
    }

    /**
     * @return \Weasty\Money\Currency\Code\CurrencyCodeConverterInterface
     */
    public function getCurrencyCodeConverter()
    {
        return $this->currencyCodeConverter;
    }

} 