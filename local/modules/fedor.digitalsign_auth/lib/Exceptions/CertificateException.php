<?php

namespace Fedor\DigitalsignAuth\Exceptions;

use Bitrix\Main\SystemException;
use Exception;

class CertificateException extends SystemException implements ExceptionInterface
{
	public function __construct($message = "", Exception $previous = null)
	{
		parent::__construct(('Ошибка данных сертификата: ' . $message ?: "Ошибка данных сертификата."), 403, $previous);
	}
}