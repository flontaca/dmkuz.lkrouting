<?php
namespace Dmkuz\Lkrouting\Model;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields;
use Bitrix\Main\ORM\Fields\Validators\UniqueValidator;
use Dmkuz\Lkrouting\Validators\UrlValidator;
use Bitrix\Main\Type\DateTime;

class RouteTable extends DataManager
{
    public static function getTableName()
    {
        return 'dmkuz_lkrouting_routes';
    }

    public static function getMap()
    {
        return [
            (new Fields\IntegerField('ID'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),

            (new Fields\StringField('TITLE'))
                ->configureRequired(true)
                ->configureSize(255),

            (new Fields\StringField('URL'))
                ->configureRequired(true)
                ->configureSize(500)
                ->addValidator(new UrlValidator())
                ->addValidator(new UniqueValidator('Указанный URL уже существует')),

            (new Fields\StringField('FILE_PATH'))
                ->configureRequired(true)
                ->configureSize(500),

            (new Fields\DatetimeField('CREATED_AT'))
                ->configureDefaultValue(function() {
                    return new DateTime();
                }),

            (new Fields\DatetimeField('UPDATED_AT'))
                ->configureDefaultValue(function() {
                    return new DateTime();
                })
        ];
    }
}