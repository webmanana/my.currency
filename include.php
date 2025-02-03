<?php
use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses('my.currency', [
    'My\Currency\CurrencyRateTable' => 'lib/currencyratetable.php',
    'My\Currency\CurrencyImport' => 'lib/currencyimport.php',
]);

if (Loader::includeModule('my.currency')) {
    require_once __DIR__ . '/admin/menu.php';
}
