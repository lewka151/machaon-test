<?php


use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\ORM\Entity;
use Machaon\Test\VisitorsTable;
use Machaon\Test\VisitsTable;

class machaon_test extends CModule
{
    var $MODULE_ID = 'machaon.test';
    var $MODULE_NAME;
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_DESCRIPTION;
    var $PARTNER_NAME;

    public function __construct()
    {
        $arModuleVersion = [];

        include(__DIR__ . '/version.php');

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->MODULE_NAME = 'machaon.test - счетчик визитов';
        $this->MODULE_DESCRIPTION = 'Модуль для тестовой задачи';
        $this->PARTNER_NAME = 'l151';

        include_once(__DIR__ . '/../lib/VisitorsTable.php');
        include_once(__DIR__ . '/../lib/VisitsTable.php');
    }

    public function DoInstall(): void
    {
        ModuleManager::registerModule($this->MODULE_ID);

        $this->InstallEvents();
        $this->InstallFiles();
        $this->InstallDB();
    }

    public function DoUninstall(): void
    {
        $this->UnInstallDB();
        $this->UnInstallFiles();
        $this->UnInstallEvents();

        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function InstallEvents(): void
    {
        EventManager::getInstance()->registerEventHandler(
            'main',
            'OnProlog',
            $this->MODULE_ID,
            '\\Machaon\\Test\\EventsHandler',
            'registerVisit',
        );
    }

    public function UnInstallEvents(): void
    {
        EventManager::getInstance()->unRegisterEventHandler(
            'main',
            'OnProlog',
            $this->MODULE_ID,
            '\\Machaon\\Test\\EventsHandler',
            'registerVisit',
        );
    }

    public function InstallFiles(): void
    {
        CopyDirFiles(
            __DIR__ . '/components/machaon.test/',
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/machaon.test/',
            true,
            true
        );
        CopyDirFiles(
            __DIR__ . '/pages/',
            $_SERVER['DOCUMENT_ROOT'] . '/',
            true,
            true
        );
    }

    public function UnInstallFiles(): void
    {
        DeleteDirFiles(
            __DIR__ . '/pages/',
            $_SERVER['DOCUMENT_ROOT'] . '/'
        );
        \Bitrix\Main\IO\Directory::deleteDirectory(
            $_SERVER['DOCUMENT_ROOT'] .'/bitrix/components/machaon.test/'
        );
    }

    public function InstallDB(): void
    {
        $connection = Application::getConnection();

        if (!$connection->isTableExists(VisitorsTable::getTableName())) {
            Entity::getInstance(VisitorsTable::class)->createDbTable();
        }

        if (!$connection->isTableExists(VisitsTable::getTableName())) {
            Entity::getInstance(VisitsTable::class)->createDbTable();
        }
    }

    public function UnInstallDB(): void
    {
        $connection = Application::getConnection();

        if ($connection->isTableExists(VisitsTable::getTableName())) {
            $connection->dropTable(VisitsTable::getTableName());
        }

        if ($connection->isTableExists(VisitorsTable::getTableName())) {
            $connection->dropTable(VisitorsTable::getTableName());
        }
    }
}