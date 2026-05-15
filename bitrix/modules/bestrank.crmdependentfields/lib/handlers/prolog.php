<?php

namespace Bestrank\CrmDependentFields\Handlers;

use Bitrix\Crm\Attribute\FieldAttributeManager;
use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Loader;
use Bestrank\CrmDependentFields\Helpers;

class Prolog
{
    public static function onPrologHandler()
    {
        $url = parse_url($_SERVER['REQUEST_URI']);
        self::stageChangeHandler($url);
    }


    /**
     * ����� ������ �������� (���, ������, ��)
     * @param $url
     */
    private static function stageChangeHandler($url)
    {
        parse_str($url['query'] ?? '', $urlQuery);

        //region ��, ��������� ������
        if ((
            $url['path'] == '/bitrix/services/main/ajax.php') &&
            (
                (($urlQuery['mode'] == 'class')
                    && ($urlQuery['c'] == 'bitrix:crm.item.details')
                    && ($urlQuery['action'] == 'save')
                ) ||
                (
                    $urlQuery['action'] == 'crm.controller.item.update'
                )
            )
        )
        {
            if (!Loader::includeModule('crm')) {
                return;
            }

            ServiceLocator::getInstance()->addInstance(
                'crm.service.container',
                new \Bestrank\CrmDependentFields\Service\Container()
            );

        }
        //endregion

        if (($url['path'] == '/bitrix/services/main/ajax.php') && ($urlQuery['action'] == 'crm.api.item.getEditor')) {
            if (!Loader::includeModule('crm')) {
                return;
            }

            try {
                $request = \Bitrix\Main\Context::getCurrent()->getRequest();

                $reflection = new \ReflectionClass($request);
                $postData = $reflection->getProperty('postData');
                $postData->setAccessible(true);
                $postDataValues = $postData->getValue($request);


                $postDataReflection = new \ReflectionClass($postDataValues);
                $values = $postDataReflection->getProperty('values');
                $values->setAccessible(true);
                $valuesValues = $values->getValue($postDataValues);


                $params = $valuesValues['params'];
                if (empty($params['requiredFields'])) {
                    return;
                }

                $crmHelper = new Helpers\Crm();
                $codeParam = $crmHelper->getCodeParam($postDataValues['entityTypeId'], $postDataValues['id']);
                $option = Helpers\Options::getJsonOption($codeParam);
                $fields = $params['requiredFields'];

                foreach ($fields as $field) {
                    if (!key_exists(
                            $field,
                            $option
                        ) || $option[$field]['type'] != 'bind' || empty($option[$field]['fields']['required'])) {
                        continue;
                    }

                    foreach ($option[$field]['fields']['required'] as $requiredField) {
                        $params['requiredFields'] = array_merge($params['requiredFields'], $requiredField);
                    }
                }

                //region ������������� ��������� ������������ ���� �� ������� ����������� �� ������
                $params['requiredFields'] = array_merge(
                    $params['requiredFields'],
                    self::getStageRequiredFieldsDynamic($valuesValues['entityTypeId'], $valuesValues['id'], $valuesValues['stageId'], $option)
                );
                //endregion ������������� ��������� ������������ ���� �� ������� ����������� �� ������

                $params['requiredFields'] = array_unique($params['requiredFields']);
                $params['requiredFields'] = $crmHelper->getFilteredEmptyFields(
                    $postDataValues['entityTypeId'],
                    $postDataValues['id'],
                    $params['requiredFields']
                );
                if (!empty($params['requiredFields'])) {
                    $valuesValues['params'] = $params;
                    $values->setValue($postDataValues, $valuesValues);
                    $postData->setValue($request, $postDataValues);
                }
            } catch (\Exception $exception) {
                return;
            }
        }

        //region ����� ������ ���� ��� ������
        if (
            (in_array($url['path'], [
                '/bitrix/components/bitrix/crm.deal.details/ajax.php',
                '/bitrix/components/bitrix/crm.lead.details/ajax.php'
            ])) &&
            ($_POST['ACTION'] == 'PREPARE_EDITOR_HTML')
        ) {
            if (!Loader::includeModule('crm')) {
                return;
            }
            try {
                $entityTypeName = $_POST['ACTION_ENTITY_TYPE_NAME'];
                $entityTypeId = \CCrmOwnerType::ResolveID($entityTypeName);
                $entityId = $_POST['ACTION_ENTITY_ID'];
                $categoryId = $_POST['CONTEXT']['PARAMS']['CATEGORY_ID'];

                $crmHelper = new \Bestrank\CrmDependentFields\Helpers\Crm();
                $optionCode = $crmHelper->getCodeParam($entityTypeId, $entityId, $categoryId);
                $option = \Bestrank\CrmDependentFields\Helpers\Options::getJsonOption($optionCode);

                $fields = $_POST['FIELDS'];

                foreach ($fields as $field) {
                    if (!key_exists($field, $option) ||
                        $option[$field]['type'] != 'bind' || empty($option[$field]['fields']['required'])) {
                        continue;
                    }

                    foreach ($option[$field]['fields']['required'] as $key => $requiredField) {
                        $requiredField = array_intersect($option[$field]['fields']['bind'][$key] ?? [], $requiredField);
                        $_POST['FIELDS'] = array_merge($_POST['FIELDS'], $requiredField);
                    }
                }

                //region ������������� ��������� ������������ ���� �� ������� ����������� �� ������
                $stageIdPropName = 'STAGE_ID';
                $stageId = $_POST['CONTEXT']['STAGE_ID'];
                if (!$stageId) {
                    $stageId = $_POST['CONTEXT']['STATUS_ID'];
                    $stageIdPropName = 'STATUS_ID';
                }
                $requiredFields = self::getStageRequiredFields($entityTypeId, $entityId, $stageId, $stageIdPropName, $option);
                $requiredFields = $crmHelper->getFilteredEmptyFields($entityTypeId, $entityId, $requiredFields);
                $_POST['FIELDS'] = array_merge(
                    $_POST['FIELDS'] ?? [],
                        $requiredFields
                );
                //endregion ������������� ��������� ������������ ���� �� ������� ����������� �� ������

                $_POST['FIELDS'] = array_unique($_POST['FIELDS'] ?? []);
            } catch (\Exception $exception) {
                return;
            }
        }
        //endregion ����� ������ ���� ��� ������
    }


    /**
     * ������������ ���� �� ������� ����������� �� ������
     * @param int $entityTypeId
     * @param int $entityId
     * @param string $stageId
     * @param string $stageIdPropName
     * @param array $option
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     */
    protected static function getStageRequiredFields(
        int $entityTypeId,
        int $entityId,
        string $stageId = null,
        string $stageIdPropName = null,
        array $option = []
    ): array {
        $stageRequired = [];
        if (empty($stageId) || empty($stageIdPropName)) {
            return $stageRequired;
        }

        $stageRequiredFields = FieldAttributeManager::getRequiredFields(
            $entityTypeId,
            $entityId,
            [$stageIdPropName => $stageId]
        );

        if (!empty($stageRequiredFields)) {
            foreach ($stageRequiredFields as $fieldCodes) {
                $stageRequired = array_merge($stageRequired, $fieldCodes);
            }
            $stageRequired = array_unique($stageRequired);

            if (empty($stageRequired)) {
                return $stageRequired;
            }

            $factory = \Bitrix\Crm\Service\Container::getInstance()->getFactory($entityTypeId);
            $item = $factory ? $factory->getItem($entityId) : null;

            if (!$item) {
                return $stageRequired;
            }


            foreach ($stageRequired as $key => $fieldCode) {
                if ($item->hasField($fieldCode) && !is_null($item->get($fieldCode))) {
                    unset($stageRequired[$key]);
                }
            }


            $option['STAGE_ID'] = [
                'type' => 'bind',
                'target' => 'fields',
                'fields' => [
                    'bind' => [
                        $stageId => [],
                    ],
                    'required' => [
                        $stageId => [],
                    ],
                ]
            ];
            $option[$stageIdPropName]['fields']['required'][$stageId] = array_merge(
                $option['STAGE_ID']['fields']['required'][$stageId] ?? [],
                $stageRequired
            );
            $option[$stageIdPropName]['fields']['bind'][$stageId] = array_merge(
                $option['STAGE_ID']['fields']['bind'][$stageId] ?? [],
                $stageRequired
            );

            foreach ($stageRequired as $fieldCode) {
                $stageRequired = array_merge(
                    $stageRequired,
                    Helpers\Crm::getRequiredFields($option, $fieldCode)
                );
            }
        }

        return $stageRequired;
    }

    /**
     * ������������ ���� �� ������� ����������� �� ������ ��� ��
     * @param int $entityTypeId
     * @param int $entityId
     * @param string $stageId
     * @param array $option
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     */
    protected static function getStageRequiredFieldsDynamic(
        int $entityTypeId,
        int $entityId,
        string $stageId,
        array $option = []
    ): array
    {
        $result = [];
        $factory = \Bitrix\Crm\Service\Container::getInstance()->getFactory($entityTypeId);
        $item = $factory ? $factory->getItem($entityId) : null;

        if (!$item) {
            return $result;
        }

        $fieldsData = FieldAttributeManager::getList(
            $item->getEntityTypeId(),
            FieldAttributeManager::getItemConfigScope($item)
        );

        $categoryId = $item->isCategoriesSupported() ? $item->getCategoryId() : null;
        $stages = $factory->getStages($categoryId);
        $stageRequiredFields = FieldAttributeManager::processFieldsForStages(
            $fieldsData,
            $stages,
            $stageId,
        );

        if (!empty($stageRequiredFields)) {
            $requiredFields = array_map(static function($fieldCode) use ($option) {
                return Helpers\Crm::getRequiredFields($option, $fieldCode);
            }, $stageRequiredFields);
            $result = array_merge($result, ...$requiredFields);
        }

        return $result;
    }
}