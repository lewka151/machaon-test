<?php

namespace Machaon\Test\Components;


use Bitrix\Main\LoaderException;
use \Exception;
use Machaon\Test\VisitorsTable;
use Machaon\Test\VisitsTable;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class VisitorLogComponent extends \CBitrixComponent
{
    protected const LAST_VISITS_LIMIT = 30;
    protected const SELECT_VISITS = [
        'ID', 'DATE_TIME', 'URL', 'REFERRER', 'VISITOR.IP'
    ];

    public function executeComponent(): void
    {
        try {
            $this->init();
            vdump($this->arResult);
        } catch (\Exception $e) {
            vdump($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    protected function init(): void
    {
        $this->arResult['VISITS_COUNTER'] = $this->getVisitsCounter();
        $this->arResult['VISITORS_COUNTER'] = $this->getVisitorsCounter();
        $this->arResult['VISITS_RECORDS'] = $this->getLastVisitsRecords();
    }

    protected function getVisitsCounter(): int
    {
        return VisitsTable::getCount();
    }

    protected function getVisitorsCounter(): int
    {
        return VisitorsTable::getCount();
    }

    protected function getLastVisitsRecords(): array
    {
        $visits = [];

        $visitsCollection = VisitsTable::query()
            ->setLimit(self::LAST_VISITS_LIMIT)
            ->setOrder(['ID' => 'DESC'])
            ->setSelect(self::SELECT_VISITS)
            ->fetchCollection();

        foreach ($visitsCollection as $visit) {
            $visits[] = [
                'ID' => $visit->getId(),
                'IP' => $visit->getVisitor()?->getIp(),
                'DATE_TIME' => $visit->getDateTime()?->toString(),
                'URL' => $visit->getUrl(),
                'REFERRER' => $visit->getReferrer(),
            ];
        }

        return $visits;
    }


    /**
     * @throws LoaderException
     * @throws Exception
     */
    public function onPrepareComponentParams($arParams): array
    {
        if(!\Bitrix\Main\Loader::includeModule('machaon.test')) {
            throw new Exception('machaon.test module is not installed');
        }

        return parent::onPrepareComponentParams($arParams);
    }
}