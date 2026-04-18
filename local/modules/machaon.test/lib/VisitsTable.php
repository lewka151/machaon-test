<?php

namespace Machaon\Test;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\SystemException;

class VisitsTable extends DataManager
{
    /**
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'l_site_visits';
    }

    public static function getObjectClass(): string
    {
        return Visit::class;
    }

    /**
     *
     * @return array
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return [
            (new IntegerField('ID'))
                ->configurePrimary()
                ->configureAutocomplete(),

            (new IntegerField('VISITOR_ID'))
                ->configureRequired(),

            (new DatetimeField('DATE_TIME'))
                ->configureRequired(),

            (new TextField('URL')),

            (new TextField('REFERRER')),

            (new Reference('VISITOR', VisitorsTable::class, ['=this.VISITOR_ID' => 'ref.ID']))
                ->configureJoinType('inner'),
        ];
    }
}