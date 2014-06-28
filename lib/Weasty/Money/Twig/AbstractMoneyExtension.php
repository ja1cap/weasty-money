<?php
namespace Weasty\Money\Twig;

use Symfony\Component\Intl\Intl;
use Weasty\Money\Currency\CurrencyResource;
use Weasty\Money\Price\PriceInterface;
use Weasty\Money\Converter\CurrencyCodeConverterInterface;
use Weasty\Money\Converter\CurrencyConverterInterface;

/**
 * Class AbstractMoneyExtension
 * @package Weasty\Money\Twig
 */
abstract class AbstractMoneyExtension extends \Twig_Extension {

    /**
     * @var CurrencyResource
     */
    protected $currencyResource;

    /**
     * @var \Weasty\Money\Converter\CurrencyCodeConverterInterface
     */
    protected $currencyCodeConverter;

    /**
     * @var \Weasty\Money\Converter\CurrencyConverterInterface
     */
    protected $currencyConverter;

    /**
     * @param CurrencyResource $currencyResource
     * @param CurrencyCodeConverterInterface $currencyCodeConverter
     * @param CurrencyConverterInterface $currencyConverter
     */
    function __construct(CurrencyResource $currencyResource, CurrencyCodeConverterInterface $currencyCodeConverter, CurrencyConverterInterface $currencyConverter)
    {

        $this->currencyResource = $currencyResource;
        $this->currencyConverter = $currencyConverter;
        $this->currencyCodeConverter = $currencyCodeConverter;

    }

    /**
     * @param string|null|\Weasty\Money\Price\PriceInterface $value
     * @param null $sourceCurrency
     * @param null $destinationCurrency
     * @return string
     */
    public function formatPrice($value, $sourceCurrency = null, $destinationCurrency = null)
    {

        $destinationCurrency = $destinationCurrency ?: $this->getCurrencyResource()->getDefaultCurrency();

        if ($value instanceof PriceInterface) {

            $value = $this
                ->getCurrencyConverter()
                ->convert(
                    $value,
                    $value->getCurrency(),
                    $destinationCurrency
                );

        } else {

            if($sourceCurrency) {

                $value = $this
                    ->getCurrencyConverter()
                    ->convert(
                        $value,
                        $sourceCurrency,
                        $destinationCurrency
                    );

            }

        }

        return $this->formatMoney($value, $destinationCurrency);

    }

    /**
     * @param $value
     * @param $currency
     * @param boolean $appendSymbol
     * @return string
     */
    public function formatMoney($value, $currency = null, $appendSymbol = true){

        $currency = $currency ?: $this->getCurrencyResource()->getDefaultCurrency();

        $currencyAlphabeticCode = $this
            ->getCurrencyCodeConverter()
            ->convert(
                $currency,
                CurrencyResource::CODE_TYPE_ISO_4217_ALPHABETIC
            );

        $fractionDigits = 0;

        if($fractionDigits === null){
            $currencyBundle = Intl::getCurrencyBundle();
            $fractionDigits = $currencyBundle->getFractionDigits($currencyAlphabeticCode);
        }

        $value = floatval($value);

        $result = number_format($value, $fractionDigits, ',', ' ');

        if($appendSymbol){

            $currencySymbol = $this->getCurrencyResource()->getCurrencySymbol($currencyAlphabeticCode);
            $prependCurrencySymbol = false;
            if($prependCurrencySymbol){
                $result = $currencySymbol . ' ' . $result;
            } else {
                $result .= ' ' . $currencySymbol;
            }

        }

        return $result;

    }

    /**
     * @return \Weasty\Money\Currency\CurrencyResource
     */
    public function getCurrencyResource()
    {
        return $this->currencyResource;
    }

    /**
     * @return \Weasty\Money\Converter\CurrencyCodeConverterInterface
     */
    public function getCurrencyCodeConverter()
    {
        return $this->currencyCodeConverter;
    }

    /**
     * @return \Weasty\Money\Converter\CurrencyConverterInterface
     */
    public function getCurrencyConverter()
    {
        return $this->currencyConverter;
    }

} 