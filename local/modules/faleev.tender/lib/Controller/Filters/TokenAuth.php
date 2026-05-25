<?php

namespace Faleev\Tender\Controller\Filters;

namespace Faleev\Tender\Controller\Filters;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Engine\ActionFilter\Base;
use Bitrix\Main\Error;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;

class TokenAuth extends Base
{
	public function onBeforeAction(Event $event): ?EventResult
	{
		$options = Option::getForModule('faleev.tender');
		$authHeader =
			$_SERVER['HTTP_AUTHORIZATION']
			?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
			?? getallheaders()['Authorization']
			?? '';
		if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
			$this->addError(new Error('Unauthorized full'));
			return new EventResult(EventResult::ERROR, null, null, $this);
		}

		$token = $matches[1];
		if ($token !== $options['secret']) {
			$this->addError(new Error('Forbidden'));
			return new EventResult(EventResult::ERROR, null, null, $this);
		}

		global $USER;
		$USER->Authorize(1);
		return new EventResult(EventResult::SUCCESS, null, null, $this);
	}
}