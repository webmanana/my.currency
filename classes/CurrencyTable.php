<?php
namespace My\Currency;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class CurrencyTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'b_my_currency_rate';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            new Entity\StringField('CODE', [
                'required' => true,
                'size' => 3,
            ]),
            new Entity\DatetimeField('DATE', [
                'required' => true,
            ]),
            new Entity\FloatField('COURSE', [
                'required' => true,
            ]),
        ];
    }
}
