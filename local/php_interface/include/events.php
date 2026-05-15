<?php

use Bitrix\Crm\Service\Container;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Error;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Result;
use Bitrix\Crm\Item;
use Bitrix\Crm\Service;
use Bitrix\Crm\Service\Operation;
use Bitrix\Crm\Service\Context;
use Bitrix\Main\DI;
use Bitrix\Main\Loader;

AddEventHandler('crm', 'onCrmDynamicItemUpdate_137', 'onMeetingUpdate');
/**
 * @param Item $item
 * @param $entityTypeId
 * @throws ArgumentException
 */
function onMeetingUpdate(Item $item, $entityTypeId): void
{
    CModule::IncludeModule('crm');
    CModule::IncludeModule('calendar');

    $fields = $item->toArray();

    if (
        isset($fields['entityTypeId'])
        && $fields['entityTypeId'] == ENTITY_TYPE_ID
        && isset($fields[EVENT_NEEDED_TO_CREATE_FIELD_CODE])
        && $fields[EVENT_NEEDED_TO_CREATE_FIELD_CODE] == EVENT_NEEDED_TO_CREATE_VALUE_CODE
    ) {

        $container = new Bitrix\Crm\Service\Container();
        $factory = $container->getFactory(ENTITY_TYPE_ID);
        $item = $factory->getItem($fields['id']);

        // получаем список участников мероприятия и распределяем его как для указания id участников,
        // так и для указания кодов участников для корректного отображения внутри события календаря
        $attendees = [$fields['assignedById']];
        $attendeCodes = ['U' . $fields['assignedById']];
        foreach ($fields['observers'] as $attendeId) {
            $attendees[] = $attendeId;
            $attendeCodes[] = 'U' . $attendeId;
        }

        // ищем место проведения встречи в смарт-процессе по имени
        $locationName = getCrmFieldEnum($fields[EVENT_LOCATION_FIELD_CODE]);
        $resLocations = \CCalendarLocation::getList();
        $neededLocation = array_search($locationName, array_column($resLocations, 'NAME'));
        $locationId = '';
        if (!empty($neededLocation)) {
            $locationId = $neededLocation['ID'];
        }

        // ищем секцию календаря для добавления события
        $section = \CCalendarSect::GetList([
            'arFilter' => [
                'CAL_TYPE' => 'user',
                'OWNER_ID' => $fields['assignedById'],
            ],
        ]);
        $sectionId = current($section)['ID'];

        // смотрим время в которое должно начаться событие и ставим занятость +1 час.
        $eventTime = strtotime($fields[EVENT_TIME_FIELD_CODE]);
        $fromTs = date(DateTimeInterface::ATOM, $eventTime);
        $toTs = date(DateTimeInterface::ATOM, ($eventTime + 3600));

        // дополняем описание события ссылкой на элемент смарт-процесса
        $description = $fields[EVENT_DESCRIPTION_FIELD_CODE]
            . PHP_EOL
            . '[URL=https://tandem-consult.ru/page/organizatsiya_vstrech/organizatsiya_vstrech/type/'
            . ENTITY_TYPE_ID . '/details/' . $fields['id'] . '/]' . $fields['title'] . '[/URL]';

        $arFields = array(
            "OWNER_ID" => $fields['assignedById'],
            "MEETING_CREATOR" => $fields['assignedById'],
            "CAL_TYPE" =>'user',
            "SECTIONS" => $sectionId,
            "NAME" => $fields['title'],
            "DESCRIPTION" => $description,
            "IS_MEETING" => true,
            "MEETING_HOST" => $fields['assignedById'],
            "RRULE" => false,
            "SKIP_TIME" => false,
            "DATE_FROM" => CRestUtil::unConvertDateTime($fromTs),
            "DATE_TO" => CRestUtil::unConvertDateTime($toTs),
            "ALLOW_INVITE" => false,
            "REMIND" => [
                [
                    "type" => "min",
                    "count" => EVENT_REMIND_MINUTES,
                ],
                [
                    "type" => "hour",
                    "count" => EVENT_REMIND_HOURS,
                ],
            ],
            "MEETING" => [
                'HOST_NAME' => \CCalendar::GetUserName($fields['assignedById']),
                "TEXT" => "Приглашение на событие " . $fields['title'],
                "OPEN" => true,
                "NOTIFY" => true,
                "REINVITE" => false,
                "HIDE_GUESTS" => true,
            ],
            "COLOR" => "DE6E2E",
            "ATTENDEES" => $attendees,
            "ATTENDEES_CODES" => $attendeCodes,
        );

        if (!empty($fields[EVENT_ID_FIELD_CODE])) {
            $arFields['ID'] = $fields[EVENT_ID_FIELD_CODE];
        }

        if (!empty($locationId)) {
            $arFields['LOCATION'] = 'calendar_' . $locationId;
        }
        $CalEventId = \CCalendar::SaveEvent(
            [
                'arFields' => $arFields,
                'autoDetectSection' => true,
            ]
        );

        // Выключаем кеширование, запоминая предыдущее значение
        $oldCacheTime = \CCalendar::CacheTime();
        \CCalendar::CacheTime(0);
        $updateEventCrmField = CCalendarEvent::UpdateUserFields($CalEventId, ['UF_CRM_CAL_EVENT' => ['T89_' . $fields['id']]]);
        // Возвращаем все как было
        \CCalendar::CacheTime($oldCacheTime);
        unset($oldCacheTime);

        // добавляем id события и ссылку на него в измененный элемент смарт-процесса
        if ($updateEventCrmField) {
            $item->set(EVENT_ID_FIELD_CODE_UPPER_CASE, $CalEventId);

            $eventLink = 'https://tandem-consult.ru/company/personal/user/' . $fields['assignedById'] . '/calendar/?EVENT_ID=' . $CalEventId;
            $item->set(
                EVENT_LINK_FIELD_CODE,
                $eventLink
            );
            $item->save();

            $saveOperation = $factory ->getUpdateOperation($item);
            $saveOperation->launch();
        }

    }

}

// подключение кастомизированных скриптов
AddEventHandler('main', 'OnEpilog', 'addCustomJsScripts');
function addCustomJsScripts(): void
{
    global $APPLICATION;
    CJSCore::Init(["calendar", "popup", "date"]);

    Loader::includeModule('fedor.taskpermissions');
    /** скрипт добавляет поле крайнего срока к задаче*/
    Asset::getInstance()->addJs("/local/js/fedor/interfacecustomisation/tasks.js", true);
}
