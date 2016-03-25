<?php
namespace Weasty\Money\Mapper;

use Weasty\Doctrine\Mapper\AbstractEntityMapper;
use Weasty\Money\Currency\Code\CurrencyCodeConverterInterface;
use Weasty\Money\Currency\CurrencyResource;
use Weasty\Money\Entity\AbstractCurrencyRate;

/**
 * Class CurrencyRateMapper
 * @package Weasty\Money\Mapper
 */
class CurrencyRateMapper extends AbstractEntityMapper {

    /**
     * @var \Weasty\Money\Currency\Code\CurrencyCodeConverterInterface
     */
    private $currencyCodeConverter;

    /**
     * @return \Weasty\Money\Entity\AbstractCurrencyRate
     * @throws \Exception
     */
    public function getCurrencyRate()
    {
        if(!$this->getEntity() instanceof AbstractCurrencyRate){
            throw new \Exception('CurrencyRateMapper::$entity must be instance of '.AbstractCurrencyRate::class);
        }
        return $this->getEntity();
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
     * @return CurrencyCodeConverterInterface
     * @throws \Exception
     */
    public function getCurrencyCodeConverter()
    {
        if(!$this->currencyCodeConverter instanceof CurrencyCodeConverterInterface){
            throw new \Exception('CurrencyCodeConverter must implement \Weasty\Money\Currency\Code\CurrencyCodeConverterInterface', 500);
        }
        return $this->currencyCodeConverter;
    }

    /**
     * @param \Weasty\Money\Currency\Code\CurrencyCodeConverterInterface $currencyCodeConverter
     * @return $this
     */
    public function setCurrencyCodeConverter( $currencyCodeConverter ) {
        $this->currencyCodeConverter = $currencyCodeConverter;
        return $this;
    }

}