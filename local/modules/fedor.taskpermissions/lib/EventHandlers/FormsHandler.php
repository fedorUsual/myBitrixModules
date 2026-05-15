<?php

namespace Fedor\Taskpermissions\EventHandlers;

use Bitrix\Crm\Service\Container;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Event;
use Exception;

class FormsHandler
{
	/** Метод проверяет каким пользователем заполнена crm-форма и добавляет при необходимости в создаваемую сущность
	 * этого пользоателя
	 * @param Event $event
	 */
	public static function onSiteFormFilled(Event $event): void
	{
		try {
			$result = $event->getParameter('result');
			$neededField = '_ID_CREATOR';
			$userCreate = CurrentUser::get()->getId();
			// ищемм поля сущностей CRM
			$entities = [];
			$entitiesElem = [];
			foreach ($result['entities'] as $entityValue) {
				if (!empty($entityValue['ENTITY_TYPE'])) {
					$entities[] = $entityValue['ENTITY_TYPE'];
					$entitiesElem[$entityValue['ENTITY_TYPE']] = $entityValue['ENTITY_ID'];
				}
			}
			// получаем дополнительно ID и фаблрики сущностей CRM с полями _ID_CREATOR
			$neededEntities = [];
			$factories = Container::getInstance()->getTypesMap()->getFactories();
			foreach ($factories as $factory) {
				if (in_array($factory->getEntityName(), $entities)) {
					$fields = array_keys($factory->getUserFields());
					$issetNeededField = array_filter($fields, function ($v) use ($neededField) {
						return str_ends_with($v, $neededField);
					});
					if (count($issetNeededField) > 0) {
						$neededEntities[] = [
							'entityTypeName' => $factory->getEntityName(),
							'entityTypeId' => $factory->getEntityTypeId(),
							'factory' => $factory,
							'fieldName' => current($issetNeededField),
							'entityId' => $entitiesElem[$factory->getEntityName()],
						];
					}
				}
			}

			foreach ($neededEntities as $neededEntity) {
				$item = $neededEntity['factory']->getItem($neededEntity['entityId']);
				$item->set($neededEntity['fieldName'], $userCreate);
				$res = $item->save();
				if (!$res->isSuccess()) {
					file_put_contents(
						$_SERVER['DOCUMENT_ROOT'] . '/local/logs/formsErrors.log',
						date('d.m.Y H:i:s') . ' ' . implode(PHP_EOL, $res->getErrorMessages()),
						FILE_APPEND);
				}
			}
		} catch (Exception $e) {
			file_put_contents(
				$_SERVER['DOCUMENT_ROOT'] . '/local/logs/formsErrors.log',
				date('d.m.Y H:i:s') . ' ' . $e->getMessage() . PHP_EOL,
				FILE_APPEND);
		}
	}
}