<?php
namespace My\Currency;

use Bitrix\Main\Entity;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\ORM\Data\DataManager;

class CurrencyTable extends DataManager
{
    public static function getTableName()
    {
        return 'my_currency_rates';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),
            new Entity\StringField('CODE', [
                'required' => true,
                'size' => 10
            ]),
            new Entity\DatetimeField('DATE', [
                'required' => true
            ]),
            new Entity\FloatField('COURSE', [
                'required' => true
            ])
        ];
    }
}
