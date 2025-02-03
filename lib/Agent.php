<?php
namespace My\Currency;

use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use My\Currency\CurrencyRateTable;

class Agent
{
    public static function loadCurrencyData()
    {
        $url = "https://www.cbr.ru/scripts/XML_daily.asp";
        $xmlContent = file_get_contents($url);

        if ($xmlContent) {
            $xml = simplexml_load_string($xmlContent);

            foreach ($xml->Valute as $valute) {
                $code = (string) $valute->CharCode;
                $course = (float) str_replace(',', '.', (string) $valute->Value);

                CurrencyRateTable::add([
                    'CODE' => $code,
                    'COURSE' => $course,
                    'DATE' => new DateTime(),
                ]);
            }

            return "Курс валют обновлен успешно.";
        } else {
            return "Ошибка при загрузке данных.";
        }
    }
}
