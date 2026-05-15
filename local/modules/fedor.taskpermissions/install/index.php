<?php

use Bitrix\Forum\TopicTable;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Loader;
use Fedor\Taskpermissions\Helper;

Loc::loadMessages(__FILE__);

class fedor_taskpermissions extends CModule
{
    public const MODULE_ID = 'fedor.taskpermissions';

    public $MODULE_ID = self::MODULE_ID;
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;
    public $MODULE_GROUP_RIGHTS = 'Y';

    const EVENTS = [
        'tasks' => [
            'OnBeforeTaskAdd',
        ],
        'im' => [
            'OnBeforeMessageNotifyAdd'
        ],
	    'forum' => [
		    'OnAfterMessageAdd'
	    ],
	    'FormsHandler' => [
			'onSiteFormFilled'
	    ]
    ];

    public function __construct()
    {
        $arModuleVersion = [];
        include __DIR__ . '/version.php';
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('FEDOR_TASKPERMISSIONS_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('FEDOR_TASKPERMISSIONS_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('FEDOR_TASKPERMISSIONS_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('FEDOR_TASKPERMISSIONS_PARTNER_URI');
    }

    public function InstallEvents()
    {
		Loader::includeModule('forum');
		Loader::includeModule('iblock');

        $toModuleId = $this->MODULE_ID;
        foreach (self::EVENTS as $fromModuleId => $moduleEvents) {
            if (str_contains('iblock', $fromModuleId)) {
                $handlerClassName = 'IBlock';
            } elseif (str_contains('FormsHandler', $fromModuleId)) {
				$handlerClassName = $fromModuleId;
				$fromModuleId = 'crm';
            } else {
                $handlerClassName = ucfirst($fromModuleId);
            }
			
            $className = '\\Fedor\\Taskpermissions\\EventHandlers\\' . $handlerClassName;
            foreach ($moduleEvents as $eventName) {
//				if ($fromModuleId === 'forum') {
//					EventManager::getInstance()->registerEventHandler(
//						$fromModuleId,
//						$eventName::getEntity()->getFullName() . '::' . DataManager::EVENT_ON_AFTER_ADD,
//						$toModuleId,
//						$className,
//						'OnAfterTopicAdd'
//					);
//				} else {
					EventManager::getInstance()->registerEventHandler(
						$fromModuleId,
						$eventName,
						$toModuleId,
						$className,
						$eventName
					);
//				}
            }
        }
        return true;
    }

    public function UnInstallEvents()
    {
        $toModuleId = $this->MODULE_ID;
        foreach (self::EVENTS as $fromModuleId => $moduleEvents) {
            if (str_contains('iblock', $fromModuleId)) {
                $handlerClassName = 'IBlock';
            } else {
                $handlerClassName = ucfirst($fromModuleId);
            }

            $className = 'Norbit\Loyalty\EventHandlers\\' . $handlerClassName;
            foreach ($moduleEvents as $eventName) {
                EventManager::getInstance()->unRegisterEventHandler(
                    $fromModuleId,
                    $eventName,
                    $toModuleId,
                    $className,
                    $eventName
                );
            }
        }

        return true;
    }

    public function InstallDB()
    {
        Loader::includeModule('iblock');
        Loader::includeModule($this->MODULE_ID);

        $iblockType = new \CIBlockType();
        $iblockTypeFields = array (
            'ID' => 'rest_entity',
            'SECTIONS' => 'Y',
            'EDIT_FILE_BEFORE' => NULL,
            'EDIT_FILE_AFTER' => NULL,
            'IN_RSS' => 'N',
            'SORT' => '1000',
            'LANG' =>
                array (
                    'ru' =>
                        array (
                            'NAME' => 'Хранилище данных для приложений',
                            'SECTION_NAME' => 'Разделы',
                            'ELEMENT_NAME' => 'Элементы',
                        ),
                ),
        );
        $iblockType->Add($iblockTypeFields);

        $iblock = new \CIBlock();
        $iblockFields = array (
            'IBLOCK_TYPE_ID' => 'rest_entity',
            'LID' =>
                array (
                    0 => 'gm',
                    1 => 'pq',
                    2 => 'rj',
                    3 => 's1',
                ),
            'CODE' => 'TASK_CREATE_FORBIDDENS',
            'API_CODE' => NULL,
            'REST_ON' => 'N',
            'NAME' => 'Запреты на постановки задач',
            'ACTIVE' => 'Y',
            'SORT' => '500',
            'LIST_PAGE_URL' => '#SITE_DIR#/rest_entity/index.php?ID=#IBLOCK_ID#',
            'DETAIL_PAGE_URL' => '#SITE_DIR#/rest_entity/detail.php?ID=#ELEMENT_ID#',
            'SECTION_PAGE_URL' => '#SITE_DIR#/rest_entity/list.php?SECTION_ID=#SECTION_ID#',
            'CANONICAL_PAGE_URL' => '',
            'PICTURE' => NULL,
            'DESCRIPTION' => '',
            'DESCRIPTION_TYPE' => 'text',
            'RSS_TTL' => '24',
            'RSS_ACTIVE' => 'Y',
            'RSS_FILE_ACTIVE' => 'N',
            'RSS_FILE_LIMIT' => NULL,
            'RSS_FILE_DAYS' => NULL,
            'RSS_YANDEX_ACTIVE' => 'N',
            'XML_ID' => NULL,
            'INDEX_ELEMENT' => 'Y',
            'INDEX_SECTION' => 'Y',
            'WORKFLOW' => 'N',
            'BIZPROC' => 'N',
            'SECTION_CHOOSER' => 'L',
            'LIST_MODE' => '',
            'RIGHTS_MODE' => 'S',
            'SECTION_PROPERTY' => 'N',
            'PROPERTY_INDEX' => 'N',
            'VERSION' => '1',
            'LAST_CONV_ELEMENT' => '0',
            'SOCNET_GROUP_ID' => NULL,
            'EDIT_FILE_BEFORE' => '',
            'EDIT_FILE_AFTER' => '',
            'SECTIONS_NAME' => 'Разделы',
            'SECTION_NAME' => 'Раздел',
            'ELEMENTS_NAME' => 'Элементы',
            'ELEMENT_NAME' => 'Элемент',
            'EXTERNAL_ID' => NULL,
            'LANG_DIR' => '/site_rj/',
            'IPROPERTY_TEMPLATES' =>
                array (
                ),
            'ELEMENT_ADD' => 'Добавить элемент',
            'ELEMENT_EDIT' => 'Изменить элемент',
            'ELEMENT_DELETE' => 'Удалить элемент',
            'SECTION_ADD' => 'Добавить раздел',
            'SECTION_EDIT' => 'Изменить раздел',
            'SECTION_DELETE' => 'Удалить раздел',
        );

        $iblockId = $iblock->Add($iblockFields);

        $fieldsFields = array (
            'IBLOCK_SECTION' =>
                array (
                    'NAME' => 'Привязка к разделам',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' =>
                        array (
                            'KEEP_IBLOCK_SECTION_ID' => 'N',
                        ),
                    'VISIBLE' => 'Y',
                ),
            'ACTIVE' =>
                array (
                    'NAME' => 'Активность',
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => 'Y',
                    'VISIBLE' => 'Y',
                ),
            'ACTIVE_FROM' =>
                array (
                    'NAME' => 'Начало активности',
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => '=now',
                    'VISIBLE' => 'Y',
                ),
            'ACTIVE_TO' =>
                array (
                    'NAME' => 'Окончание активности',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => '',
                    'VISIBLE' => 'Y',
                ),
            'SORT' =>
                array (
                    'NAME' => 'Сортировка',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => '500',
                    'VISIBLE' => 'Y',
                ),
            'NAME' =>
                array (
                    'NAME' => 'Название',
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => 'Правило',
                    'VISIBLE' => 'Y',
                ),
            'PREVIEW_PICTURE' =>
                array (
                    'NAME' => 'Картинка для анонса',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' =>
                        array (
                            'FROM_DETAIL' => 'N',
                            'UPDATE_WITH_DETAIL' => 'N',
                            'DELETE_WITH_DETAIL' => 'N',
                            'SCALE' => 'N',
                            'WIDTH' => '',
                            'HEIGHT' => '',
                            'IGNORE_ERRORS' => 'N',
                            'METHOD' => 'resample',
                            'COMPRESSION' => 95,
                            'USE_WATERMARK_TEXT' => 'N',
                            'WATERMARK_TEXT' => '',
                            'WATERMARK_TEXT_FONT' => '',
                            'WATERMARK_TEXT_COLOR' => '',
                            'WATERMARK_TEXT_SIZE' => '',
                            'WATERMARK_TEXT_POSITION' => 'tl',
                            'USE_WATERMARK_FILE' => 'N',
                            'WATERMARK_FILE' => '',
                            'WATERMARK_FILE_ALPHA' => '',
                            'WATERMARK_FILE_POSITION' => 'tl',
                            'WATERMARK_FILE_ORDER' => '',
                        ),
                    'VISIBLE' => 'Y',
                ),
            'PREVIEW_TEXT_TYPE' =>
                array (
                    'NAME' => 'Тип описания для анонса',
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => 'text',
                    'VISIBLE' => 'Y',
                ),
            'PREVIEW_TEXT' =>
                array (
                    'NAME' => 'Описание для анонса',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => '',
                    'VISIBLE' => 'Y',
                ),
            'DETAIL_PICTURE' =>
                array (
                    'NAME' => 'Детальная картинка',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' =>
                        array (
                            'SCALE' => 'N',
                            'WIDTH' => '',
                            'HEIGHT' => '',
                            'IGNORE_ERRORS' => 'N',
                            'METHOD' => 'resample',
                            'COMPRESSION' => 95,
                            'USE_WATERMARK_TEXT' => 'N',
                            'WATERMARK_TEXT' => '',
                            'WATERMARK_TEXT_FONT' => '',
                            'WATERMARK_TEXT_COLOR' => '',
                            'WATERMARK_TEXT_SIZE' => '',
                            'WATERMARK_TEXT_POSITION' => 'tl',
                            'USE_WATERMARK_FILE' => 'N',
                            'WATERMARK_FILE' => '',
                            'WATERMARK_FILE_ALPHA' => '',
                            'WATERMARK_FILE_POSITION' => 'tl',
                            'WATERMARK_FILE_ORDER' => '',
                        ),
                    'VISIBLE' => 'Y',
                ),
            'DETAIL_TEXT_TYPE' =>
                array (
                    'NAME' => 'Тип детального описания',
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => 'text',
                    'VISIBLE' => 'Y',
                ),
            'DETAIL_TEXT' =>
                array (
                    'NAME' => 'Детальное описание',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => '',
                    'VISIBLE' => 'Y',
                ),
            'XML_ID' =>
                array (
                    'NAME' => 'Внешний код',
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => '',
                    'VISIBLE' => 'Y',
                ),
            'CODE' =>
                array (
                    'NAME' => 'Символьный код',
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' =>
                        array (
                            'UNIQUE' => 'Y',
                            'TRANSLITERATION' => 'Y',
                            'TRANS_LEN' => 100,
                            'TRANS_CASE' => 'L',
                            'TRANS_SPACE' => '-',
                            'TRANS_OTHER' => '-',
                            'TRANS_EAT' => 'Y',
                            'USE_GOOGLE' => 'N',
                        ),
                    'VISIBLE' => 'Y',
                ),
            'TAGS' =>
                array (
                    'NAME' => 'Теги',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => '',
                    'VISIBLE' => 'Y',
                ),
            'SECTION_NAME' =>
                array (
                    'NAME' => 'Название',
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => '',
                    'VISIBLE' => 'Y',
                ),
            'SECTION_PICTURE' =>
                array (
                    'NAME' => 'Картинка для анонса',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' =>
                        array (
                            'FROM_DETAIL' => 'N',
                            'UPDATE_WITH_DETAIL' => 'N',
                            'DELETE_WITH_DETAIL' => 'N',
                            'SCALE' => 'N',
                            'WIDTH' => '',
                            'HEIGHT' => '',
                            'IGNORE_ERRORS' => 'N',
                            'METHOD' => 'resample',
                            'COMPRESSION' => 95,
                            'USE_WATERMARK_TEXT' => 'N',
                            'WATERMARK_TEXT' => '',
                            'WATERMARK_TEXT_FONT' => '',
                            'WATERMARK_TEXT_COLOR' => '',
                            'WATERMARK_TEXT_SIZE' => '',
                            'WATERMARK_TEXT_POSITION' => 'tl',
                            'USE_WATERMARK_FILE' => 'N',
                            'WATERMARK_FILE' => '',
                            'WATERMARK_FILE_ALPHA' => '',
                            'WATERMARK_FILE_POSITION' => 'tl',
                            'WATERMARK_FILE_ORDER' => '',
                        ),
                    'VISIBLE' => 'Y',
                ),
            'SECTION_DESCRIPTION_TYPE' =>
                array (
                    'NAME' => 'Тип описания',
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => 'text',
                    'VISIBLE' => 'Y',
                ),
            'SECTION_DESCRIPTION' =>
                array (
                    'NAME' => 'Описание',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => '',
                    'VISIBLE' => 'Y',
                ),
            'SECTION_DETAIL_PICTURE' =>
                array (
                    'NAME' => 'Детальная картинка',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' =>
                        array (
                            'SCALE' => 'N',
                            'WIDTH' => '',
                            'HEIGHT' => '',
                            'IGNORE_ERRORS' => 'N',
                            'METHOD' => 'resample',
                            'COMPRESSION' => 95,
                            'USE_WATERMARK_TEXT' => 'N',
                            'WATERMARK_TEXT' => '',
                            'WATERMARK_TEXT_FONT' => '',
                            'WATERMARK_TEXT_COLOR' => '',
                            'WATERMARK_TEXT_SIZE' => '',
                            'WATERMARK_TEXT_POSITION' => 'tl',
                            'USE_WATERMARK_FILE' => 'N',
                            'WATERMARK_FILE' => '',
                            'WATERMARK_FILE_ALPHA' => '',
                            'WATERMARK_FILE_POSITION' => 'tl',
                            'WATERMARK_FILE_ORDER' => '',
                        ),
                    'VISIBLE' => 'Y',
                ),
            'SECTION_XML_ID' =>
                array (
                    'NAME' => 'Внешний код',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => '',
                    'VISIBLE' => 'Y',
                ),
            'SECTION_CODE' =>
                array (
                    'NAME' => 'Символьный код',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' =>
                        array (
                            'UNIQUE' => 'N',
                            'TRANSLITERATION' => 'N',
                            'TRANS_LEN' => 100,
                            'TRANS_CASE' => 'L',
                            'TRANS_SPACE' => '-',
                            'TRANS_OTHER' => '-',
                            'TRANS_EAT' => 'Y',
                            'USE_GOOGLE' => 'N',
                        ),
                    'VISIBLE' => 'Y',
                ),
            'LOG_SECTION_ADD' =>
                array (
                    'NAME' => 'LOG_SECTION_ADD',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => NULL,
                    'VISIBLE' => 'Y',
                ),
            'LOG_SECTION_EDIT' =>
                array (
                    'NAME' => 'LOG_SECTION_EDIT',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => NULL,
                    'VISIBLE' => 'Y',
                ),
            'LOG_SECTION_DELETE' =>
                array (
                    'NAME' => 'LOG_SECTION_DELETE',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => NULL,
                    'VISIBLE' => 'Y',
                ),
            'LOG_ELEMENT_ADD' =>
                array (
                    'NAME' => 'LOG_ELEMENT_ADD',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => NULL,
                    'VISIBLE' => 'Y',
                ),
            'LOG_ELEMENT_EDIT' =>
                array (
                    'NAME' => 'LOG_ELEMENT_EDIT',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => NULL,
                    'VISIBLE' => 'Y',
                ),
            'LOG_ELEMENT_DELETE' =>
                array (
                    'NAME' => 'LOG_ELEMENT_DELETE',
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => NULL,
                    'VISIBLE' => 'Y',
                ),
        );
        \CIBlock::SetFields($iblockId, $fieldsFields);

        \CIBlock::SetPermission($iblockId, ['administrators' => 'X']);

        $departmentsProperty = Helper::getIblockByCode('departments', 'structure');
        $property_1 = new CIBlockProperty();
        $property_1->Add(array (
            'IBLOCK_ID' => $iblockId,
            'NAME' => 'От сотрудников отдела',
            'ACTIVE' => 'Y',
            'SORT' => '500',
            'CODE' => 'FROM_DEPARTMENT',
            'DEFAULT_VALUE' => '',
            'PROPERTY_TYPE' => 'G',
            'ROW_COUNT' => '1',
            'COL_COUNT' => '30',
            'LIST_TYPE' => 'L',
            'MULTIPLE' => 'N',
            'XML_ID' => NULL,
            'FILE_TYPE' => '',
            'MULTIPLE_CNT' => '5',
            'LINK_IBLOCK_ID' => $departmentsProperty,
            'WITH_DESCRIPTION' => 'N',
            'SEARCHABLE' => 'N',
            'FILTRABLE' => 'Y',
            'IS_REQUIRED' => 'Y',
            'VERSION' => '1',
            'USER_TYPE' => NULL,
            'USER_TYPE_SETTINGS' => 'a:0:{}',
            'HINT' => '',
            'FEATURES' =>
                array (
                    0 =>
                        array (
                            'MODULE_ID' => 'iblock',
                            'FEATURE_ID' => 'DETAIL_PAGE_SHOW',
                            'IS_ENABLED' => 'Y',
                        ),
                    1 =>
                        array (
                            'MODULE_ID' => 'iblock',
                            'FEATURE_ID' => 'LIST_PAGE_SHOW',
                            'IS_ENABLED' => 'Y',
                        ),
                ),
        ));

        $property_2 = new CIBlockProperty();
        $property_2->Add(array (
            'IBLOCK_ID' => $iblockId,
            'NAME' => 'Сотрудникам отдела',
            'ACTIVE' => 'Y',
            'SORT' => '500',
            'CODE' => 'TO_DEPARTMENT',
            'DEFAULT_VALUE' => '',
            'PROPERTY_TYPE' => 'G',
            'ROW_COUNT' => '1',
            'COL_COUNT' => '30',
            'LIST_TYPE' => 'L',
            'MULTIPLE' => 'N',
            'XML_ID' => NULL,
            'FILE_TYPE' => '',
            'MULTIPLE_CNT' => '5',
            'LINK_IBLOCK_ID' => $departmentsProperty,
            'WITH_DESCRIPTION' => 'N',
            'SEARCHABLE' => 'N',
            'FILTRABLE' => 'Y',
            'IS_REQUIRED' => 'Y',
            'VERSION' => '1',
            'USER_TYPE' => NULL,
            'USER_TYPE_SETTINGS' => 'a:0:{}',
            'HINT' => '',
            'FEATURES' =>
                array (
                    0 =>
                        array (
                            'MODULE_ID' => 'iblock',
                            'FEATURE_ID' => 'DETAIL_PAGE_SHOW',
                            'IS_ENABLED' => 'Y',
                        ),
                    1 =>
                        array (
                            'MODULE_ID' => 'iblock',
                            'FEATURE_ID' => 'LIST_PAGE_SHOW',
                            'IS_ENABLED' => 'Y',
                        ),
                ),
        ));

        $property_3 = new CIBlockProperty();
        $property_3->Add(array (
            'IBLOCK_ID' => $iblockId,
            'NAME' => 'От отдела с подотделами',
            'ACTIVE' => 'Y',
            'SORT' => '500',
            'CODE' => 'FROM_DEP_WITH_SUBS',
            'DEFAULT_VALUE' => '',
            'PROPERTY_TYPE' => 'L',
            'ROW_COUNT' => '1',
            'COL_COUNT' => '30',
            'LIST_TYPE' => 'C',
            'MULTIPLE' => 'N',
            'XML_ID' => NULL,
            'FILE_TYPE' => '',
            'MULTIPLE_CNT' => '5',
            'LINK_IBLOCK_ID' => '0',
            'WITH_DESCRIPTION' => 'N',
            'SEARCHABLE' => 'N',
            'FILTRABLE' => 'N',
            'IS_REQUIRED' => 'Y',
            'VERSION' => '1',
            'USER_TYPE' => NULL,
            'USER_TYPE_SETTINGS' => NULL,
            'HINT' => '',
            'VALUES' =>
                array (
                    0 =>
                        array (
                            'VALUE' => 'Да',
                            'DEF' => 'N',
                            'SORT' => '500',
                            'XML_ID' => 'FROM_WITH_SUBS_YES',
                        ),
                    1 =>
                        array (
                            'VALUE' => 'Нет',
                            'DEF' => 'Y',
                            'SORT' => '500',
                            'XML_ID' => 'FROM_WITH_SUBS_NO',
                        ),
                ),
            'FEATURES' =>
                array (
                    0 =>
                        array (
                            'MODULE_ID' => 'iblock',
                            'FEATURE_ID' => 'DETAIL_PAGE_SHOW',
                            'IS_ENABLED' => 'Y',
                        ),
                    1 =>
                        array (
                            'MODULE_ID' => 'iblock',
                            'FEATURE_ID' => 'LIST_PAGE_SHOW',
                            'IS_ENABLED' => 'Y',
                        ),
                ),
        ));

        $property_4 = new CIBlockProperty();
        $property_4->Add(array (
            'IBLOCK_ID' => $iblockId,
            'NAME' => 'Отделу с подотделами',
            'ACTIVE' => 'Y',
            'SORT' => '500',
            'CODE' => 'TO_DEP_WITH_SUBS',
            'DEFAULT_VALUE' => '',
            'PROPERTY_TYPE' => 'L',
            'ROW_COUNT' => '1',
            'COL_COUNT' => '30',
            'LIST_TYPE' => 'C',
            'MULTIPLE' => 'N',
            'XML_ID' => NULL,
            'FILE_TYPE' => '',
            'MULTIPLE_CNT' => '5',
            'LINK_IBLOCK_ID' => '0',
            'WITH_DESCRIPTION' => 'N',
            'SEARCHABLE' => 'N',
            'FILTRABLE' => 'N',
            'IS_REQUIRED' => 'Y',
            'VERSION' => '1',
            'USER_TYPE' => NULL,
            'USER_TYPE_SETTINGS' => NULL,
            'HINT' => '',
            'VALUES' =>
                array (
                    0 =>
                        array (
                            'VALUE' => 'Да',
                            'DEF' => 'N',
                            'SORT' => '500',
                            'XML_ID' => 'TO_WITH_SUBS_YES',
                        ),
                    1 =>
                        array (
                            'VALUE' => 'Нет',
                            'DEF' => 'Y',
                            'SORT' => '500',
                            'XML_ID' => 'TO_WITH_SUBS_NO',
                        ),
                ),
            'FEATURES' =>
                array (
                    0 =>
                        array (
                            'MODULE_ID' => 'iblock',
                            'FEATURE_ID' => 'DETAIL_PAGE_SHOW',
                            'IS_ENABLED' => 'Y',
                        ),
                    1 =>
                        array (
                            'MODULE_ID' => 'iblock',
                            'FEATURE_ID' => 'LIST_PAGE_SHOW',
                            'IS_ENABLED' => 'Y',
                        ),
                ),
        ));

    }

    public function UnInstallDB(): void
    {
        Loader::includeModule('iblock');
        Loader::includeModule($this->MODULE_ID);

        $iblockId = Helper::getIblockByCode('TASK_CREATE_FORBIDDENS', 'rest_entity');

        \CIBlock::Delete($iblockId);
    }

    public function GetModuleRightList()
    {
        return [
            'reference_id' => ['D', 'R', 'W'],
            'reference' => [
                Loc::getMessage('FEDOR_TASKPERMISSIONS_MODULE_ACCESS_DENIED'),
                Loc::getMessage('FEDOR_TASKPERMISSIONS_MODULE_ACCESS_OPENED'),
                Loc::getMessage('FEDOR_TASKPERMISSIONS_MODULE_ACCESS_FULL'),
            ],
        ];
    }

    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);

        $this->InstallDB();
        $this->InstallEvents();


        return true;
    }

    public function DoUninstall()
    {
        $this->UnInstallEvents();
        $this->UnInstallDB();

        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}