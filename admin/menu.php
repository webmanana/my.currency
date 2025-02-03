<?php
global $APPLICATION;

if (!is_object($APPLICATION)) {
    return false;
}

if ($APPLICATION->GetGroupRight("my.currency") > "D") {
    $aMenu = [
        [
            "parent_menu" => "global_menu_marketing",
            "section" => "my_currency",
            "sort" => 500,
            "text" => "Курсы валют",
            "title" => "Курсы валют",
            "icon" => "my_currency_menu_icon",
            "page_icon" => "my_currency_page_icon",
            "items_id" => "menu_my_currency",
            "items" => [
                [
                    "text" => "Список курсов валют",
                    "url" => "my_currency_rates.php?lang=" . LANGUAGE_ID,
                    "more_url" => ["my_currency_rates_edit.php"],
                    "title" => "Список курсов валют",
                ],
            ],
        ],
    ];
    return $aMenu;
}

return false;
