<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Application;

class MyCurrencyUninstall
{
    public static function DoUninstall()
    {
        global $DB, $DBType, $APPLICATION;

        if (Loader::includeModule('my.currency')) {
            $errors = [];
            $errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/local/modules/my.currency/install/db/' . $DBType . '/uninstall.sql');
            if (!empty($errors)) {
                $APPLICATION->ThrowException(implode('', $errors));
                return false;
            }

            DeleteDirFilesEx("/local/components/my.currency/");

            \CAgent::RemoveAgent("\\My\\Currency\\CurrencyRateAgent::updateCurrencyRates();", "my.currency");

            CAdminNotify::RemoveByTag("my_currency_install");
        } else {
            $APPLICATION->ThrowException("Не удалось удалить модуль.");
        }
    }
}