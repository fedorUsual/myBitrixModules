<?php

namespace Fedor\DigitalsignAuth\Exceptions;

use Bitrix\Main\SystemException;
use Exception;

class SignatureException extends SystemException implements ExceptionInterface
{
	public function __construct($message = "", Exception $previous = null)
	{
		parent::__construct(('Ошибка при проверке подписи плагином: ' . $message ?: "Ошибка КриптоПро."), 403, $previous);
	}
}