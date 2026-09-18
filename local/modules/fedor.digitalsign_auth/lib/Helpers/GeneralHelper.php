<?php

namespace Fedor\DigitalsignAuth\Helpers;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SiteTable;
use Bitrix\Main\SiteTemplateTable;
use Exception;

class GeneralHelper
{
	/**
	 * Изменяет условия шаблонов для сайтов, сравнивая их списки
	 * @param array $newSites
	 * @param array $oldSites
	 * @return void
	 * @throws Exception
	 */
	public static function addRemoveSiteTemplateOptions(array $newSites, array $oldSites): void
	{
		$sitesToAdd = array_diff($newSites, $oldSites);
		$sitesToDel = array_diff($oldSites, $newSites);

		foreach ($sitesToAdd as $site) {
			$siteAdded = SiteTable::query()
				->addFilter('LID', $site)
				->setSelect(['LID', 'NAME', 'DEF'])
				->fetch();
			if ($siteAdded) {
				self::checkAgreementFormForSite($siteAdded['LID']);
				$siteCondition = [
					'SITE_ID' => $siteAdded['LID'],
					'CONDITION' => 'defined("NEED_AUTH") && NEED_AUTH === true && !$USER->IsAuthorized()',
					'SORT' => 10,
					'TEMPLATE' => 'auth_template',
				];
				SiteTemplateTable::add($siteCondition);
			}
		}

		foreach ($sitesToDel as $site) {
			$siteDel = SiteTable::query()
				->addFilter('LID', $site)
				->setSelect(['LID', 'NAME', 'DEF'])
				->fetch();
			if ($siteDel) {
				$condition = SiteTemplateTable::query()
					->setFilter(['SITE_ID' => $siteDel['LID'], 'TEMPLATE' => 'auth_template'])
					->addSelect('ID')
					->fetch();
				SiteTemplateTable::delete($condition['ID']);
			}
		}
	}

	/**
	 * Проверяет наличие пользовательского соглашения для сайта
	 * @param string $siteId
	 * @return void
	 * @throws Exception
	 */
	public static function checkAgreementFormForSite(string $siteId): void
	{
		$formAgreementId = Option::get('main', 'new_user_agreement', '', $siteId);
		if (empty($formAgreementId)) {
			throw new Exception(Loc::getMessage(
				'FEDOR_CRYPTO_GENERAL_HELPER_THERE_IS_NO_FORM',
				['#SITE_ID#' => $siteId])
			);
		}
	}

	/**
	 * Возвращает ID текущего дефолтного сайта
	 * @return string
	 * @throws Exception
	 */
	public static function getDefaultSiteLid(): string
	{
		$siteDefault = SiteTable::query()
			->addFilter('DEF', 'Y')
			->setSelect(['LID', 'NAME', 'DEF'])
			->fetch();

		return $siteDefault['LID'] ?? 's1';
	}
}