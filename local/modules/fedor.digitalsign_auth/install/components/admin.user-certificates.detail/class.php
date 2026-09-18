<?php

namespace Fedor\DigitalsignAuth\Components;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Entity\Field;
use Bitrix\Main\Error;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\Relations\Relation;
use Bitrix\Main\ORM\Objectify\EntityObject;
use CAllMain;
use CAllUser;
use CBitrixComponent;
use CMain;
use CUser;
use Exception;
use Fedor\DigitalsignAuth\ORM\UserCertificatesTable;

class UserCertificatesDetail extends CBitrixComponent implements Controllerable
{
    protected ErrorCollection $errorCollection;

    /**
     * Component constructor.
     *
     * @param CBitrixComponent | null $component
     */
    public function __construct($component = null)
    {
        $this->errorCollection = new ErrorCollection();
        parent::__construct($component);
    }

    /**
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->errorCollection->toArray();
    }

    /**
     * @param string $code Code of error.
     *
     * @return Error
     */
    public function getErrorByCode(string $code): Error
    {
        return $this->errorCollection->getErrorByCode($code);
    }

    /**
     * Проверка наличия модулей требуемых для работы компонента
     *
     * @return bool
     * @throws Exception
     */
    private function _checkModules()
    {
        if (!Loader::includeModule('fedor.digitalsign_auth')) {
            throw new Exception(Loc::getMessage('ADMIN_CERTIFICATES_DETAIL_REGISTER_EXCEPTION'));
        }

        return true;
    }

    /**
     * Обертка над глобальной переменной
     *
     * @return CAllMain|CMain
     */
    private function _app()
    {
        global $APPLICATION;
        return $APPLICATION;
    }

    /**
     * Обертка над глобальной переменной
     *
     * @return CAllUser|CUser
     */
    private function _user()
    {
        global $USER;
        return $USER;
    }

    /**
     * @return array[]
     */
    public function configureActions(): array
    {
        return [];
    }

    /**
     * Подготовка параметров компонента
     *
     * @param $arParams
     *
     * @return mixed
     */
    public function onPrepareComponentParams($arParams)
    {
        if (empty($arParams['REQUEST_ID']) || $arParams['REQUEST_ID'] <= 0) {
            $this->errorCollection->setError(new Error(Loc::getMessage('ADMIN_CERTIFICATES_DETAIL_REQUIRED_PARAM_EMPTY')));
        }

        return $arParams;
    }

    /**
     * Точка входа в компонент
     * Должна содержать только последовательность вызовов вспомогательых ф-ий и минимум логики
     * всю логику стараемся разносить по классам и методам
     */
    public function executeComponent()
    {
        $this->_checkModules();

		global $APPLICATION;

	    if ($APPLICATION->GetUserRight('fedor.digitalsign_auth') === 'D') {
		    ShowError(Loc::getMessage('ADMIN_CERTIFICATES_DETAIL_ACCESS_DENIED'));
		    return;
	    }
        if ($this->errorCollection->count() > 0) {
            /** @var Error $error */
            foreach ($this->errorCollection as $error) {
                ShowError($error->getMessage());
            }
            return;
        }

        $resData = UserCertificatesTable::query()
            ->addFilter('ID', $this->arParams['REQUEST_ID'])
            ->addSelect('*')
            ->fetchObject();

        if (!$resData) {
            ShowError(Loc::getMessage('ADMIN_CERTIFICATES_DETAIL_NO_CERTIFICATE'));
            return;
        }

        $resFields = UserCertificatesTable::getMap();
        $fields = [];
        foreach ($resFields as $field) {
            /** @var Field $field */
            if (!($field instanceof Relation)) {
                $fields[] = [
                    'CODE' => $field->getName(),
                    'NAME' => $field->getTitle() ?: $field->getName(),
                ];
            }
        }

        /** @var EntityObject $resData */
        $preparedItem = [];
        foreach ($fields as $fieldName) {
            $preparedItem[$fieldName['NAME']] = $resData->get($fieldName['CODE']);
        }
        $this->arResult['ITEM'] = $preparedItem;

        $this->includeComponentTemplate();
    }
}