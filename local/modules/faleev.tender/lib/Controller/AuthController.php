<?php

namespace Faleev\Tender\Controller;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Engine\Response\AjaxJson;
use Faleev\Tender\Helpers\ResponseStatusHelper;
use Faleev\Tender\Controller\Filters\TokenAuth;

class AuthController extends Controller
{
	public function configureActions(): array
	{
		$getOptions = [
			'-prefilters' => [
				ActionFilter\Authentication::class,
				ActionFilter\Csrf::class
			],
			'prefilters' => [
				new ActionFilter\Authentication(false),
				new TokenAuth(),
				new ActionFilter\HttpMethod([
					ActionFilter\HttpMethod::METHOD_GET,
				]),
			],
		];

		return [
			'getTenderById' => $getOptions,
		];
	}

	public function getTenderByIdAction($id): array|AjaxJson
	{
		return ['status' => 'success', 'data' => 'everithing is so good!', 'id' => $id];
		ResponseStatusHelper::setStatus($this->errorCollection);
		return AjaxJson::createError($this->errorCollection);
	}
}