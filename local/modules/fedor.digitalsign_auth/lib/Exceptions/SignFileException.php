<?php

namespace Fedor\DigitalsignAuth\Exceptions;

use Bitrix\Main\SystemException;
use Exception;

class SignFileException extends SystemException implements ExceptionInterface
{
	public function __construct($message = "", Exception $previous = null)
	{
		parent::__construct(('Ошибка записи подписи в файл: ' . $message ?: "Ошибка записи подписи в файл"), 403, $previous);
	}
}