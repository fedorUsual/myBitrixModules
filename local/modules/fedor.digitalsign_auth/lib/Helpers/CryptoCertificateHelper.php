<?php

namespace Fedor\DigitalsignAuth\Helpers;

use CPSignedData;
use CPSigner;

/**
 * Wrapper for CPCertficate object
 */
class CryptoCertificateHelper {
    private $original;
    private $pin;

    public $Subject;
    public $Issuer;
    public $Version;
    public $SerialNumber;
    public $Thumbprint;
    public $ValidFrom;
    public $ValidTo;
    public $HasPrivate;
    public $IsValid;

    function __construct($certificate) {
        $this->original = $certificate;
        $this->Subject = $this->parseDN($certificate->get_SubjectName());
        $this->Issuer = $this->parseDN($certificate->get_IssuerName());
        $this->Version = $certificate->get_Version();
        $this->SerialNumber = $certificate->get_SerialNumber();
        $this->Thumbprint = $certificate->get_Thumbprint();
        $this->ValidFrom = $certificate->get_ValidFromDate();
        $this->ValidTo = $certificate->get_ValidToDate();
        $this->HasPrivate = $certificate->HasPrivateKey();
        $this->IsValid = $certificate->IsValid()->get_Result();
    }

    function GetCertificate() {
        return $this->original;
    }

    function SetPin($pin) {
        $this->pin = $pin;
    }

    function GetPin() {
        return $this->pin;
    }

    private function parseDN(string $dn): object
    {
        $tags = [
            'CN' => 'FULL_NAME',
            'S' => 'REGION',
            'ST' => 'REGION',
            'L' => 'CITY',
            'STREET' => 'ADDRESS',
            'O' => 'COMPANY',
            'OU' => 'POST_TYPE',
            'T' => 'POST',
            'TITLE' => 'POST',
            'ОГРН' => 'OGRN',
            'OGRN' => 'OGRN',
            'СНИЛС' => 'SNILS',
            'SNILS' => 'SNILS',
            'ИНН' => 'INN',
            'INN' => 'INN',
            'ИНН ЮЛ' => 'INN',
	        'INNLE' => 'INN',
            'E' => 'EMAIL',
            'G' => 'GIVEN_NAME',
            'GN' => 'GIVEN_NAME',
            'SN' => 'SUR_NAME',
        ];

		// Меняем экранированные запятые на плейсхолдер, чтобы не ошибиться при парсинге и не взять лишнего в атрибуты
	    $placeholder = "\x00ESC_COMMA\x00";
	    $protected = str_replace('\,', $placeholder, $dn);

	    preg_match_all('/(?:^\s*|\s)(\w+\s\w+|\w+)=/u', $protected, $matches);

	    $buf = $protected;
	    $unknown = []; // сюда попадут теги, которых нет в $tags

        $fields = array_reduce(array_reverse($matches[0]), function($acc, $cur) use (&$buf, &$unknown,
	        $placeholder, $tags) {
            $pos = strpos($buf, $cur);

            $v = substr($buf, $pos);
            $v = str_replace($cur, '', $v);
            $v = preg_replace('/\s*"?(.*?)"?,?\s?$/', '$1', $v);
            $v = preg_replace('/""/', '"', $v);

	        // Возвращаем настоящую запятую на место плейсхолдера
	        $v = str_replace($placeholder, ',', $v);

            $tag = trim(str_replace('=', '', $cur));

            if (array_key_exists($tag, $tags)) {
                $acc[$tags[$tag]] = $v;
            } else {
				$unknown[$tag] = $v;
            }

            $buf = substr($buf, 0, $pos);

            return $acc;
        }, []);

	    if (!empty($unknown)) {
		    $fields['UNKNOWN'] = $unknown;
	    }

        return (object)$fields;
    }

}