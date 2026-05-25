<?php

namespace Faleev\Tender\Helpers;

use Bitrix\Main\Context;
use Bitrix\Main\ErrorCollection;

class ResponseStatusHelper
{
	public static function setStatus(ErrorCollection $errorCollection = null): void
	{
		foreach ($errorCollection->toArray() as $error) {
			switch ($error->getCode()) {
				case 400:
				case 0:
					Context::getCurrent()->getResponse()->setStatus(400);
					break;
				case 401:
					Context::getCurrent()->getResponse()->setStatus(401);
					break;
				case 403:
					Context::getCurrent()->getResponse()->setStatus(403);
					break;
				case 404:
					Context::getCurrent()->getResponse()->setStatus(404);
					break;
				case 500:
					Context::getCurrent()->getResponse()->setStatus(500);
					break;
				default:
					Context::getCurrent()->getResponse()->setStatus(200);
					break;
			}
		}
	}
}