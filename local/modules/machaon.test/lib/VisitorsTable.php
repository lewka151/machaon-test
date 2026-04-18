<?php

namespace Machaon\Test;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\SystemException;

class VisitorsTable extends DataManager
{
    /**
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'l_site_visitors';
    }

    public static function getObjectClass(): string
    {
        return Visitor::class;
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

            (new StringField('IP'))
                ->configureRequired()
                ->configureUnique()
                ->configureSize(45),

            (new OneToMany('VISITS', VisitsTable::class, 'VISITOR'))
                ->configureJoinType('left'),
        ];
    }
}