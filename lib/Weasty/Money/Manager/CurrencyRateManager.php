<?php
namespace Weasty\Money\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Weasty\Money\Currency\CurrencyResource;
use Weasty\Money\Currency\Rate\OfficialCurrencyRateInterface;
use Weasty\Money\Currency\Rate\UpdatableFromOfficialCurrencyRateInterface;

/**
 * Class CurrencyRateManager
 * @package Weasty\Bundle\MoneyBundle\Manager
 */
class CurrencyRateManager implements CurrencyRateManagerInterface
{

    /**
     * @var \Weasty\Money\Currency\CurrencyResource
     */
    protected $currencyResource;

    /**
     * @var \Weasty\Money\Manager\OfficialCurrencyRateManagerInterface
     */
    protected $officialCurrencyRateManager;

    /**
     * @var \Weasty\Doctrine\Entity\AbstractRepository[]
     */
    protected $currencyRateRepositories = [];

    /**
     * CurrencyRateManager constructor.
     * @param \Weasty\Money\Currency\CurrencyResource $currencyResource
     * @param \Weasty\Money\Manager\OfficialCurrencyRateManagerInterface $officialCurrencyRateManager
     * @param \Weasty\Doctrine\Entity\AbstractRepository[] $currencyRatesRepositories
     */
    public function __construct(CurrencyResource $currencyResource, OfficialCurrencyRateManagerInterface $officialCurrencyRateManager, array $currencyRatesRepositories = [])
    {
        $this->currencyResource = $currencyResource;
        $this->officialCurrencyRateManager = $officialCurrencyRateManager;
        $this->currencyRateRepositories = $currencyRatesRepositories;
    }

    /**
     * @param array $codes
     * @param bool $upsertDefault
     * @param bool $updateExistingFromOfficial
     * @param OutputInterface|null $output
     * @param \Doctrine\ORM\EntityManager|null $em
     */
    public function upsert(array $codes = [], $upsertDefault = false, $updateExistingFromOfficial = false, OutputInterface $output = null, EntityManager $em)
    {

        if (!$output) {
            $output = new NullOutput();
        }

        $currencyResource = $this->currencyResource;
        $destinationCurrency = $currencyResource->getCurrency($currencyResource->getDefaultCurrency());

        if ($upsertDefault) {
            $currencies = $currencyResource->getCurrencies();
        } else {
            $currencies = array_map(
                function ($code) use ($currencyResource) {
                    return $currencyResource->getCurrency($code);
                },
                $codes
            );
        }

        $output->writeln("<info>Update official currency rates from remote[{$destinationCurrency->getAlphabeticCode()}]</info>");
        $this->getOfficialCurrencyRateManager()->updateRepositoryFromRemote($destinationCurrency->getAlphabeticCode());

        foreach ($this->getCurrencyRateRepositories() as $currencyRateRepository) {

            foreach ($currencies as $currency) {

                if ($currency->getAlphabeticCode() == $destinationCurrency->getAlphabeticCode()) {
                    continue;
                }

                $output->writeln("<info>Update currency rate {$currency->getName()}[{$currency->getAlphabeticCode()}]</info>");

                /**
                 * @var $currencyRate \Weasty\Money\Entity\CurrencyRate
                 */
                $currencyRate = $currencyRateRepository->findOneBy(
                    [
                        'sourceAlphabeticCode' => $currency->getAlphabeticCode(),
                        'destinationAlphabeticCode' => $destinationCurrency->getAlphabeticCode(),
                    ]
                );

                if (!$currencyRate) {
                    $currencyRate = $currencyRateRepository->create();
                    $currencyRate->setSourceAlphabeticCode($currency->getAlphabeticCode());
                    $currencyRate->setSourceNumericCode($currency->getNumericCode());
                    $currencyRate->setDestinationAlphabeticCode($destinationCurrency->getAlphabeticCode());
                    $currencyRate->setDestinationNumericCode($destinationCurrency->getNumericCode());
                    $em->persist($currencyRate);
                }

                if ($updateExistingFromOfficial || $currencyRate->isUpdatableFromOfficial() || empty($currencyRate->getRate())) {
                    try {
                        $this->updateCurrencyFromOfficial($currencyRate);
                    } catch (\Exception $e) {
                        $output->writeln("<error>[{$e->getCode()}]{$e->getMessage()}</error>");
                        continue;
                    }
                }

                $output->writeln("<info>Currency rate {$currencyRate->getRate()} {$destinationCurrency->getSymbol()}</info>");

            }

        }

        $em->flush();

    }

    /**
     * @param string|null $sourceAlphabeticCode
     * @param string|null $destinationCurrencyCode
     * @param \Doctrine\ORM\EntityManager $em
     *
     * @return \Weasty\Money\Entity\CurrencyRate[]
     * @throws \Exception
     */
    public function updateFromOfficial($sourceAlphabeticCode = null, $destinationCurrencyCode = null, EntityManager $em)
    {

        $updatedCurrencyRates = [];

        foreach ($this->getCurrencyRateRepositories() as $currencyRateRepository) {

            if ($sourceAlphabeticCode || $destinationCurrencyCode) {
                $currencyRates = $currencyRateRepository->findBy(
                    array_filter(
                        [
                            'sourceAlphabeticCode' => (string)$sourceAlphabeticCode,
                            'destinationAlphabeticCode' => (string)$destinationCurrencyCode,
                        ]
                    )
                );
            } else {
                $currencyRates = $currencyRateRepository->findAll();
            }

            foreach ($currencyRates as $currencyRate) {
                if ($currencyRate instanceof UpdatableFromOfficialCurrencyRateInterface) {

                    $oldValue = $currencyRate->getRate();
                    $this->updateCurrencyFromOfficial($currencyRate);
                    if ($currencyRate->getRate() != $oldValue) {
                        $updatedCurrencyRates[] = $currencyRate;
                    }

                }
            }

        }

        if ($updatedCurrencyRates) {
            $em->flush($updatedCurrencyRates);
        }

        return $updatedCurrencyRates;
    }

    /**
     * @param \Weasty\Money\Currency\Rate\UpdatableFromOfficialCurrencyRateInterface $currencyRate
     * @return bool
     * @throws \Exception
     */
    public function updateCurrencyFromOfficial(UpdatableFromOfficialCurrencyRateInterface $currencyRate)
    {

        if (!$currencyRate->isUpdatableFromOfficial()) {
            return false;
        }

        $officialCurrency = $this->getOfficialCurrencyRateManager()->getOfficialCurrencyRateRepository()->findOneBy(
            [
                'sourceAlphabeticCode' => $currencyRate->getSourceAlphabeticCode(),
                'destinationAlphabeticCode' => $currencyRate->getDestinationAlphabeticCode(),
            ]
        );

        if (!$officialCurrency instanceof OfficialCurrencyRateInterface) {
            throw new \Exception("Official currency rate not found[{$currencyRate->getSourceAlphabeticCode()}]");
        }

        switch ($currencyRate->getOfficialOffsetType()) {
            case UpdatableFromOfficialCurrencyRateInterface::OFFICIAL_OFFSET_TYPE_PERCENT:
                $newRate = $officialCurrency->getRate() * ((100 + $currencyRate->getOfficialOffsetPercent()) / 100);
                break;
            case UpdatableFromOfficialCurrencyRateInterface::OFFICIAL_OFFSET_TYPE_VALUE:
                $newRate = $officialCurrency->getRate() + $currencyRate->getOfficialOffsetValue();
                break;
            default:
                throw new \Exception("Undefined official currency offset type[{$currencyRate->getOfficialOffsetType()}]");
        }

        if ($newRate != $currencyRate->getRate()) {
            $currencyRate->setRate($newRate);
        }

        return true;

    }

    /**
     * @param \Weasty\Doctrine\Entity\AbstractRepository $repository
     */
    public function addCurrencyRateRepository($repository)
    {
        $this->currencyRateRepositories[] = $repository;
    }

    /**
     * @return \Weasty\Doctrine\Entity\AbstractRepository[]
     */
    public function getCurrencyRateRepositories()
    {
        return $this->currencyRateRepositories;
    }

    /**
     * @return \Weasty\Money\Manager\OfficialCurrencyRateManagerInterface
     */
    public function getOfficialCurrencyRateManager()
    {
        return $this->officialCurrencyRateManager;
    }

}