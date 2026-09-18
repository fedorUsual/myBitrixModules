<?php

namespace Fedor\DigitalsignAuth\Exceptions;

use Bitrix\Main\SystemException;
use Exception;

class CadesException extends SystemException implements ExceptionInterface
{
	public function __construct($message = "", Exception $previous = null)
	{
		parent::__construct(('Ошибка Cades: ' . $message ?: "Ошибка Cades Плагина."), 403, $previous);
	}
}