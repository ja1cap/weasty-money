<?php
namespace Weasty\Money\Price;

use Weasty\Money\Currency\CurrencyInterface;

/**
 * Class Price
 * @package Weasty\Money\Data
 */
class Price implements \ArrayAccess, PriceInterface {

    /**
     * @var integer|float|string
     */
    protected $value;

    /**
     * @var integer|string|\Weasty\Money\Currency\CurrencyInterface
     */
    protected $currency;

    /**
     * @param integer|float|string $value
     * @param integer|string|\Weasty\Money\Currency\CurrencyInterface $currency
     */
    function __construct($value = null, $currency = null)
    {
        $this->value = $value;
        $this->currency = $currency;
    }

    /**
     * @param int|string|CurrencyInterface $currency
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @param float|int|string $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return integer|float|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return integer|string|\Weasty\Money\Currency\CurrencyInterface
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    function __toString()
    {
        return (string)$this->getValue();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return in_array($offset, array('value', 'currency'));
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        switch($offset){
            case 'value':
                return $this->getValue();
                break;
            case 'currency':
                return $this->getCurrency();
                break;
            default:
                return null;
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        switch($offset){
            case 'value':
                $this->setValue($value);
                break;
            case 'currency':
                $this->setCurrency($value);
                break;
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        switch($offset){
            case 'value':
                $this->setValue(null);
                break;
            case 'currency':
                $this->setCurrency(null);
                break;
        }
    }

}