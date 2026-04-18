<?php

namespace Machaon\Test;


use Bitrix\Main\Application;
use Bitrix\Main\Service\GeoIp\Manager;
use Bitrix\Main\Type\DateTime;


class Visitor extends EO_Visitors
{
    protected static self $current;

    public static function current(): self
    {
        if (isset(self::$current)) {
            return self::$current;
        }

        return self::$current = self::getOrCreateCurrent();
    }

    protected static function getOrCreateCurrent(): self
    {
        $currentVisitor = VisitorsTable::query()
            ->where('IP', '=', Manager::getRealIp())
            ->setLimit(1)
            ->fetchObject();

        if(!is_null($currentVisitor)) {
            return $currentVisitor;
        }

        $currentVisitor = VisitorsTable::createObject()->setIp(Manager::getRealIp());
        $result = $currentVisitor->save();

        if(!$result->isSuccess()) {
            throw new \Exception('visitor creation failed');
        }

        return $currentVisitor;
    }

    public function registerVisit()
    {
        return VisitsTable::createObject()
            ->setVisitorId($this->getId())
            ->setUrl(Application::getInstance()->getContext()->getRequest()->getRequestUri())
            ->setReferrer($_SERVER['HTTP_REFERER'])
            ->setDateTime(new DateTime())
            ->save();
    }

}