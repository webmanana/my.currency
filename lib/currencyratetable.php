<?php
namespace My\Currency;

use Bitrix\Main;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields;
use Bitrix\Main\Type\DateTime;

class CurrencyRateTable extends DataManager
{
    public static function getTableName()
    {
        return 'b_my_currency_rate';
    }

    public static function getMap()
    {
        return [
            new Fields\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            new Fields\StringField('CODE', [
                'required' => true,
                'size' => 3,
            ]),
            new Fields\FloatField('COURSE', [
                'required' => true,
            ]),
            new Fields\DateTimeField('DATE', [
                'required' => true,
            ]),
        ];
    }

    public static function createTable()
    {
        if (!Main\Application::getConnection()->isTableExists(self::getTableName())) {
            self::getEntity()->createDbTable();
        }
    }

    public static function dropTable()
    {
        if (Main\Application::getConnection()->isTableExists(self::getTableName())) {
            self::getEntity()->getConnection()->queryExecute("DROP TABLE IF EXISTS " . self::getTableName());
        }
    }
}
