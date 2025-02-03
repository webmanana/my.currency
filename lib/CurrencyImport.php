<?php
namespace My\Currency;

use Bitrix\Main\Type\DateTime;

class CurrencyImport {
    /**
     * Получает курсы валют с сайта ЦБ РФ
     * @return array
     */
    public static function getRatesFromCBR() {
        $date = date("d/m/Y"); // Текущая дата в формате ЦБ РФ
        $url = "https://www.cbr.ru/scripts/XML_daily.asp?date_req={$date}";

        $xml = @simplexml_load_file($url);
        if (!$xml) {
            return [];
        }

        $currencyData = [];
        foreach ($xml->Valute as $valute) {
            $currencyData[] = [
                "CODE" => (string) $valute->CharCode,
                "COURSE" => floatval(str_replace(",", ".", (string) $valute->Value)),
                "DATE" => date("Y-m-d"),
            ];
        }

        return $currencyData;
    }

    /**
     * Обновляет курсы валют в базе
     * @param array $currencyData
     */
    public static function updateCurrencyRates($currencyData) {
        foreach ($currencyData as $currency) {
            // Ищем запись в базе для данной валюты и даты
            $existingRecord = CurrencyRateTable::getList([
                'filter' => [
                    'CODE' => $currency['CODE'],
                    'DATE' => new DateTime($currency['DATE']),
                ],
            ])->fetch();

            if ($existingRecord) {
                // Обновляем курс
                CurrencyRateTable::update($existingRecord['ID'], [
                    'COURSE' => $currency['COURSE'],
                ]);
            } else {
                // Добавляем новый курс
                CurrencyRateTable::add([
                    'CODE' => $currency['CODE'],
                    'COURSE' => $currency['COURSE'],
                    'DATE' => new DateTime($currency['DATE']),
                ]);
            }
        }
    }
}
