<?php

namespace Fedor\DigitalsignAuth\Components;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Error;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Loader;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\UI\Filter\Options;
use CAllMain;
use CAllUser;
use CBitrixComponent;
use CMain;
use CUser;
use Exception;
use Fedor\DigitalsignAuth\ORM\UserCertificatesTable;

class UserCertificatesList extends CBitrixComponent implements Controllerable
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
        if (!Loader::includeModule('fedor.digitalsign_auth')
        ) {
            throw new Exception(GetMessage('REGISTER_EXCEPTION'));
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

    public function configureActions()
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
		    ShowError(Loc::getMessage('ADMIN_CERTIFICATES_LIST_ACCESS_DENIED'));
		    return;
	    }

        if ($this->errorCollection->count() > 0) {
            /** @var Error $error */
            foreach ($this->errorCollection as $error) {
                ShowError($error->getMessage());
            }
            return;
        }

        $columns = [];
        foreach (UserCertificatesTable::getMap() as $field) {
            $columns[] = [
                'id' => $field->getName(),
                'name' => $field->getTitle(),
                'default' => true,
            ];
        }
        $this->arResult['COLUMNS'] = $columns;

        // FILTER ON
        $filter = [
            [
				'id' => 'ID',
	            'name' => 'ID',
	            'type' => 'number'
            ],
            [
				'id' => 'USER_ID',
	            'name' => Loc::getMessage('ADMIN_CERTIFICATES_LIST_USER'),
	            'type' => 'number',
	            'default' => true
            ],
            [
				'id' => 'VALID_FROM_DATE',
	            'name' => Loc::getMessage('ADMIN_CERTIFICATES_LIST_VALID_FROM_DATE'),
	            'type' => 'date',
	            'default' => true
            ],
            [
				'id' => 'VALID_TO_DATE',
	            'name' => Loc::getMessage('ADMIN_CERTIFICATES_LIST_VALID_TO_DATE'),
	            'type' => 'date',
	            'default' => true
            ],
        ];
        $this->arResult['FILTER'] = $filter;

        // Получаем данные для фильтрации
        $filterOptions = new Options("test_drive_list_filter");
        $filterFields = $filterOptions->getFilter($filter);

        if ($filterFields['FILTER_APPLIED']) {
            $queryFilter = Query::filter();
            if ($filterFields['ID_from'] > 0) {
                $queryFilter->where('ID', '>=', $filterFields['ID_from']);
            }
            if ($filterFields['ID_to'] > 0) {
                $queryFilter->where('ID', '<=', $filterFields['ID_to']);
            }

            if ($filterFields['USER_ID_from'] > 0) {
                $queryFilter->where('USER_ID', '>=', $filterFields['USER_ID_from']);
            }
            if ($filterFields['USER_ID_to'] > 0) {
                $queryFilter->where('USER_ID', '<=', $filterFields['USER_ID_to']);
            }
        }

        $colQuery = UserCertificatesTable::query()
            ->addSelect('*');

        if (isset($queryFilter)) {
            $colQuery->where($queryFilter);
        }

        $col = $colQuery->fetchCollection();

        $rows = [];
        foreach ($col as $item) {
            $rowColumns = [];
            foreach ($columns as ['id' => $columnCode]) {
                $rowColumns[$columnCode] = $item->get($columnCode);
            }

            $rows[] = [
                'id' => $item->getId(),
                'columns' => $rowColumns,
	            'actions' => [
		            [
			            'text' => Loc::getMessage('ADMIN_TEST_DRIVE_LIST_EDIT_ROW_TEXT'),
			            'onclick' => 'openRequestSidePanel('. $item->getId() . ')',
			            'default' => true,
		            ],
	            ],
            ];
        }
        $this->arResult['ROWS'] = $rows;

        $this->includeComponentTemplate();
    }
}
