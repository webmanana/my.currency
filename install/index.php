<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use My\Currency\CurrencyTable;

class my_currency extends CModule
{
    public $MODULE_ID = "my.currency";
    public $MODULE_VERSION = "1.0.0";
    public $MODULE_VERSION_DATE = "2025-01-30";
    public $MODULE_NAME = "Модуль курсов валют";
    public $MODULE_DESCRIPTION = "Модуль для загрузки и отображения курсов валют";
    public $PARTNER_NAME = "Ваше имя";
    public $PARTNER_URI = "https://example.com";

    function __construct()
    {
        $this->MODULE_ID = "my.currency";
        $this->MODULE_NAME = "Модуль курсов валют";
        $this->MODULE_DESCRIPTION = "Модуль для загрузки и отображения курсов валют";
        $this->PARTNER_NAME = "Ваше имя";
        $this->PARTNER_URI = "https://example.com";
    }

    function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installDB();
        $this->installFiles();
        $this->installAgent();
    }

    function DoUninstall()
    {
        $this->uninstallDB();
        $this->uninstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    function installDB()
    {
        if (!Loader::includeModule($this->MODULE_ID)) {
            return false;
        }

        $connection = \Bitrix\Main\Application::getConnection();
        $tableName = \My\Currency\CurrencyTable::getTableName();

        if ($connection->isTableExists($tableName)) {
            $connection->queryExecute("DELETE FROM {$tableName}");
        }

        if (!$connection->isTableExists($tableName)) {
            $connection->queryExecute("
                CREATE TABLE {$tableName} (
                    ID INT AUTO_INCREMENT PRIMARY KEY,
                    CODE VARCHAR(10) NOT NULL,
                    DATE DATETIME NOT NULL,
                    COURSE FLOAT NOT NULL
                )
            ");
        }
    }


    function uninstallDB()
    {
        if (!Loader::includeModule($this->MODULE_ID)) {
            return false;
        }

        $connection = \Bitrix\Main\Application::getConnection();
        $tableName = \My\Currency\CurrencyTable::getTableName();

        if ($connection->isTableExists($tableName)) {
            $connection->dropTable($tableName);
        }
    }



    function installFiles()
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/local/modules/" . $this->MODULE_ID . "/install/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin", true);
    }

    function uninstallFiles()
    {
        DeleteDirFiles($_SERVER["DOCUMENT_ROOT"] . "/local/modules/" . $this->MODULE_ID . "/install/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
    }

    function installAgent()
    {
        \CAgent::AddAgent(
            "\\My\\Currency\\Agent::loadCurrencyData();",
            $this->MODULE_ID,
            "Y",
            86400,
            "",
            "Y"
        );
    }

}
