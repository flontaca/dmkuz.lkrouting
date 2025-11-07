<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use \Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Entity\Base;
use \Bitrix\Main\Config\Option;

class dmkuz_lkrouting extends CModule
{

    function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . "/version.php");

        $this->MODULE_ID = 'dmkuz.lkrouting';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::GetMessage('DMKUZ_LK_ROUTING_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::GetMessage('DMKUZ_LK_ROUTING_MODULE_DESCRIPTION');

        $this->PARTNER_NAME = Loc::GetMessage('DMKUZ_LK_ROUTING_PARTNER_NAME');
        $this->PARTNER_URI = Loc::GetMessage('DMKUZ_LK_ROUTING_REST_PARTNER_URI');

        $this->MODULE_SORT = 2;
    }

    /**
     * Установка модуля
     */
    public function DoInstall()
    {
        global $APPLICATION;

        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallDB();
        $this->InstallEvents();
        $this->InstallFiles();

        $APPLICATION->IncludeAdminFile(Loc::getMessage("DMKUZ_LK_ROUTING_INSTALL_TITLE"), $this->GetPath() . '/install/step.php');
    }

    /**
     * Удаление модуля
     */
    public function DoUninstall()
    {
        global $APPLICATION;

        $this->UnInstallEvents();
        $this->UnInstallDB();
        $this->UnInstallFiles();

        ModuleManager::UnRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('DMKUZ_LK_ROUTING_UNINSTALL_TITLE'),
            $this->getPath() . '/install/unstep.php'
        );
    }

    public function InstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        if (!Application::getConnection(\Dmkuz\Lkrouting\Model\RouteTable::getConnectionName())->isTableExists(
            Base::getInstance('\Dmkuz\Lkrouting\Model\RouteTable')->getDBTableName()
        )
        ) {
            Base::getInstance('\Dmkuz\Lkrouting\Model\RouteTable')->createDbTable();
        }
    }

    public function UnInstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        Application::getConnection(\Dmkuz\Lkrouting\Model\RouteTable::getConnectionName())->
        queryExecute('drop table if exists ' . Base::getInstance('\Dmkuz\Lkrouting\Model\RouteTable')->getDBTableName());

    }


    /**
     * Определяем место размещения модуля
     */
    public function GetPath($notDocumentRoot = false)
    {
        if ($notDocumentRoot)
            return str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__));
        else
            return dirname(__DIR__);
    }

    public function InstallEvents()
    {
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->registerEventHandler(
            "main",
            "OnEpilog",
            "dmkuz.lkrouting",
            "Dmkuz\Lkrouting\EventHandler",
            "loadLkLinksExtension"
        );

        $eventManager->registerEventHandler(
            "main",
            "OnPageStart",
            "dmkuz.lkrouting",
            "Dmkuz\Lkrouting\EventHandler",
            "loadStatic"
        );
    }

    public function UnInstallEvents()
    {
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->unRegisterEventHandler(
            "main",
            "OnEpilog",
            "dmkuz.lkrouting",
            "Dmkuz\Lkrouting\EventHandler",
            "loadLkLinksExtension"
        );

        $eventManager->unRegisterEventHandler(
            "main",
            "OnPageStart",
            "dmkuz.lkrouting",
            "Dmkuz\Lkrouting\EventHandler",
            "loadStatic"
        );
    }

    public function InstallFiles()
    {
        CopyDirFiles(__DIR__ . '/js', $_SERVER['DOCUMENT_ROOT'] . '/local/js/dmkuz/lkrouting', true, true);
    }

    public function UnInstallFiles()
    {
        DeleteDirFilesEx('/local/js/dmkuz/lkrouting');
    }

}