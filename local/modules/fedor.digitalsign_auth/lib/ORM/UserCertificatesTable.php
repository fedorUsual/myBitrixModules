<?php

namespace Fedor\DigitalsignAuth\ORM;

use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\Query\Join;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Event;
use Bitrix\Main\ORM\Fields;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\UserTable;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
class UserCertificatesTable extends DataManager
{
    public static function getTableName()
    {
        return 'fedor_cryptopro_user_certificates';
    }

    public static function getMap()
    {
        return [
            (new Fields\IntegerField('ID'))
                ->configurePrimary()
                ->configureAutocomplete(),
            (new Fields\DatetimeField('DATE_CREATE'))
                ->configureDefaultValue(new DateTime())
                ->configureTitle(Loc::getMessage('FEDOR_CRYPTOPRO_DATE_CREATE')),
            (new Fields\DatetimeField('TIMESTAMP'))
                ->configureDefaultValue(new DateTime())
                ->configureTitle(Loc::getMessage('FEDOR_CRYPTOPRO_TIMESTAMP')),
            (new Fields\IntegerField('USER_ID'))
                ->configureTitle(Loc::getMessage('FEDOR_CRYPTOPRO_USER_ID')),
            (new ReferenceField('USER',
                \Bitrix\Main\UserTable::class,
                Join::on('this.USER_ID', 'ref.ID'),
                ['title' => Loc::getMessage('FEDOR_CRYPTOPRO_USER_ID')]
            )),
            (new Fields\StringField('ISSUER_NAME'))
                ->configureTitle(Loc::getMessage('FEDOR_CRYPTOPRO_ISSUER_NAME')),
            (new Fields\StringField('INN_FIELD'))
                ->configureTitle(Loc::getMessage('FEDOR_CRYPTOPRO_INN_FIELD'))
	            ->addValidator([__CLASS__, 'validateInn']),
            (new Fields\StringField('SNEELS_FIELD'))
                ->configureTitle(Loc::getMessage('FEDOR_CRYPTOPRO_SNEELS_FIELD'))
	            ->addValidator([__CLASS__, 'validateSneels']),
            (new Fields\StringField('SERIAL_NUMBER'))
                ->configureTitle(Loc::getMessage('FEDOR_CRYPTOPRO_SERIAL_NUMBER'))
	            ->configureUnique(),
            (new Fields\StringField('SUBJECT_NAME'))
                ->configureNullable()
                ->configureTitle(Loc::getMessage('FEDOR_CRYPTOPRO_SUBJECT_NAME')),
            (new Fields\StringField('THUMBPRINT'))
                ->configureNullable()
                ->configureTitle(Loc::getMessage('FEDOR_CRYPTOPRO_THUMBPRINT')),
            (new Fields\DatetimeField('VALID_FROM_DATE'))
                ->configureDefaultValue(new DateTime())
                ->configureTitle(Loc::getMessage('FEDOR_CRYPTOPRO_VALID_FROM_DATE')),
            (new Fields\DatetimeField('VALID_TO_DATE'))
                ->configureDefaultValue(new DateTime())
                ->configureTitle(Loc::getMessage('FEDOR_CRYPTOPRO_VALID_TO_DATE')),
            (new Fields\StringField('VERSION'))
                ->configureNullable()
                ->configureTitle(Loc::getMessage('FEDOR_CRYPTOPRO_VERSION')),
        ];
    }

    public static function onBeforeAdd(Event $event)
    {
        $result = new EventResult;
        $result->modifyFields(['DATE_CREATE' => new DateTime()]);

        return $result;
    }
    public static function OnBeforeUpdate(Event $event)
    {
        $result = new EventResult;
        $result->modifyFields(['TIMESTAMP' => new DateTime()]);

        return $result;
    }

	/**
	 * Метод для валидации значений переданных в поле ИНН
	 * @param $value
	 * @return true|string|null
	 */
	public static function validateInn($value): true|string|null
	{
		if (empty($value)) {
			return true;
		}

		if (!is_numeric($value)) {
			return Loc::getMessage('FEDOR_CRYPTOPRO_INN_FORMAT_ERROR');
		}
		$num = preg_replace('/[^0-9]/', '', $value);

		return preg_match('/^\d{10}|\d{12}$/', $num)
			? true
			: Loc::getMessage('FEDOR_CRYPTOPRO_INN_NUMBERS_COUNT_ERROR');
	}

	/**
	 * Метод для валидации значений переданных в поле СНИЛС
	 * @param $value
	 * @return true|string|null
	 */
	public static function validateSneels($value): true|string|null
	{
		if (empty($value)) {
			return true;
		}

		if (!is_numeric($value)) {
			return Loc::getMessage('FEDOR_CRYPTOPRO_SNEELS_FORMAT_ERROR');
		}
		$num = preg_replace('/[^0-9]/', '', $value);

		return preg_match('/^\d{11}$/', $num)
			? true
			: Loc::getMessage('FEDOR_CRYPTOPRO_SNEELS_NUMBERS_COUNT_ERROR');
	}
}