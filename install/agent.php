<?php
namespace My\Currency;

class Agent
{
    public static function loadCurrencyData()
    {
        $connection = \Bitrix\Main\Application::getConnection();
        $tableName = \My\Currency\CurrencyTable::getTableName();
        
        if ($connection->isTableExists($tableName)) {
            $connection->queryExecute("DELETE FROM {$tableName}");
        }

        $url = 'https://www.cbr.ru/scripts/XML_daily.asp?date_req=' . date('d/m/Y');
        $response = file_get_contents($url);

        if ($response !== false) {
            $xml = simplexml_load_string($response);
            if ($xml) {
                foreach ($xml->Valute as $valute) {
                    $code = (string) $valute->CharCode;
                    $course = (float) str_replace(',', '.', $valute->Value);
                    $date = new \Bitrix\Main\Type\DateTime();

                    \My\Currency\CurrencyTable::add([
                        'CODE' => $code,
                        'COURSE' => $course,
                        'DATE' => $date
                    ]);
                }
            }
        }
    }
}
