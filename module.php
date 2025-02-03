<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    "my.currency",
    [
        "My\\Currency\\CurrencyRateAgent" => "lib/agent.php",
        "My\\Currency\\CurrencyRateTable" => "lib/CurrencyRateTable.php",
    ]
);
