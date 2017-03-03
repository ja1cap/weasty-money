<?php
namespace Weasty\Money\Loader\RUB;

use Weasty\Money\Loader\Exception\RecordsNotFoundException;
use Weasty\Money\Loader\LoaderException;
use Weasty\Money\Loader\LoaderInterface;
use Weasty\Money\Loader\Record;

/**
 * Class RUBLoader
 * @package Weasty\Money\Loader\RUB
 */
class RUBLoader implements LoaderInterface
{

    const DESTINATION_CURRENCY_CODE = 'RUB';
    const DEFAULT_URL = 'http://www.cbr.ru/scripts/XML_daily.asp';

    /**
     * @var string
     */
    protected $url;

    /**
     * RUBLoader constructor.
     *
     * @param string $url
     */
    public function __construct($url = self::DEFAULT_URL)
    {
        $this->url = $url;
    }

    /**
     * @param \DateTime|null $dateTime
     * @return \Weasty\Money\Currency\Rate\CurrencyRateInterface[]
     * @throws \Weasty\Money\Loader\LoaderException
     */
    public function load(\DateTime $dateTime = null)
    {
        if (!$dateTime) {
            $dateTime = new \DateTime();
        }

        $url = $this->getUrl() . "?date_req=" . $dateTime->format('d/m/Y');
        $response = file_get_contents($url);
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $responseData = json_decode($json, true);

        if (empty($responseData['Valute'])) {
            throw new RecordsNotFoundException("Currency rates not found[$response][$url]");
        }

//        if (empty($responseData['@attributes']['Date'])) {
//            throw new LoaderException("Date not found in response[$response][$url]");
//        }
//        $responseDate = $responseData['@attributes']['Date'];
//
//        $parseDate = $dateTime->format('d.m.Y');
//        if ($parseDate != $responseDate) {
//            throw new LoaderException("Parse date[$parseDate] is not equal to response date[$responseDate][$url]");
//        }

        $records = $responseData['Valute'];
        $currencyRecords = array_filter(
            array_map(
                function ($record) {
                    $code = $record['CharCode'];
                    switch ($code) {
                        case 'XDR':
                            // External Data Representation is not common currency
                            $record = null;
                            break;
                        default:
                            $rate = filter_var(str_replace(',', '.', $record['Value']), FILTER_VALIDATE_FLOAT);
                            $record = new Record($code, self::DESTINATION_CURRENCY_CODE, $rate);
                    }

                    return $record;
                },
                $records
            )
        );

        return $currencyRecords;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

}