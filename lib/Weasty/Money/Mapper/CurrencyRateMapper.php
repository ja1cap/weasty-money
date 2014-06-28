<?php
namespace Weasty\Money\Mapper;

use Weasty\Money\Currency\CurrencyResource;
use Weasty\Money\Entity\CurrencyRate;
use Weasty\Money\Converter\CurrencyCodeConverterInterface;

/**
 * Class CurrencyRateMapper
 * @package Weasty\Money\Mapper
 */
class CurrencyRateMapper {

    /**
     * @var \Weasty\Money\Entity\CurrencyRate
     */
    protected $currencyRate;

    /**
     * @var \Weasty\Money\Converter\CurrencyCodeConverterInterface
     */
    protected $currencyCodeConverter;

    /**
     * @param CurrencyRate $currencyRate
     * @param \Weasty\Money\Converter\CurrencyCodeConverterInterface $currencyCodeConverter
     */
    function __construct(CurrencyRate $currencyRate, CurrencyCodeConverterInterface $currencyCodeConverter)
    {
        $this->currencyRate = $currencyRate;
        $this->currencyCodeConverter = $currencyCodeConverter;
    }

    /**
     * @return \Weasty\Money\Entity\CurrencyRate
     */
    public function getCurrencyRate()
    {
        return $this->currencyRate;
    }

    /**
     * @param $rate
     * @return $this
     */
    public function setRate($rate){
        $this->getCurrencyRate()->setRate($rate);
        return $this;
    }

    /**
     * @return float
     */
    public function getRate(){
        return $this->getCurrencyRate()->getRate();
    }

    /**
     * @param $alphabeticCode
     * @return $this
     */
    public function setSourceAlphabeticCode($alphabeticCode){

        $numericCode = $this->getCurrencyCodeConverter()->convert($alphabeticCode, CurrencyResource::CODE_TYPE_ISO_4217_NUMERIC);

        $this->getCurrencyRate()
            ->setSourceAlphabeticCode($alphabeticCode)
            ->setSourceNumericCode($numericCode);

        return $this;

    }

    /**
     * @return string
     */
    public function getSourceAlphabeticCode(){
        return $this->getCurrencyRate()->getSourceAlphabeticCode();
    }

    /**
     * @param $alphabeticCode
     * @return $this
     */
    public function setDestinationAlphabeticCode($alphabeticCode){

        $numericCode = $this->getCurrencyCodeConverter()->convert($alphabeticCode, CurrencyResource::CODE_TYPE_ISO_4217_NUMERIC);

        $this->getCurrencyRate()
            ->setDestinationAlphabeticCode($alphabeticCode)
            ->setDestinationNumericCode($numericCode);

        return $this;

    }

    /**
     * @return string
     */
    public function getDestinationAlphabeticCode(){
        return $this->getCurrencyRate()->getDestinationAlphabeticCode();
    }

    /**
     * @return \Weasty\Money\Converter\CurrencyCodeConverterInterface
     */
    public function getCurrencyCodeConverter()
    {
        return $this->currencyCodeConverter;
    }

} 