<?php

namespace Fedor\DigitalsignAuth\Handlers;

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Security\Sign\Signer;
use Bitrix\Main\UserTable;
use CUser;
use CUtil;
use Exception;
use Fedor\DigitalsignAuth\Exceptions\CertificateException;
use Fedor\DigitalsignAuth\Helpers\AuthorizeCheck;
use Fedor\DigitalsignAuth\Helpers\CryptoCertificateHelper;
use Fedor\DigitalsignAuth\Helpers\CryptoHelper;
use Fedor\DigitalsignAuth\Module;
use Fedor\DigitalsignAuth\ORM\UserCertificatesTable;
use Bitrix\Main\Type\DateTime;
use Fedor\DigitalsignAuth\Services\EventLogLogger;
use stdClass;

Loc::loadMessages(__FILE__);
class Users
{
    public static function OnExternalAuthList(){
        return [
           [ "ID" => "CRYPTOPRO_AUTH", "NAME" => Loc::getMessage("CRYPTOPRO_EXT_AUTH_NAME") ]
        ];
    }

    public static function onUserLoginExternal(&$arParams)
    {
        if(!empty($arParams['LOGIN']) && !empty($arParams['PASSWORD'])) { //Проверка, что вход через пару логин/пароль
            return 0;
        }
		$logger = new EventLogLogger();

        $session = Application::getInstance()->getSession();

	    $sign = new Signer();
	    $userInfo = $sign->unsign($session['userInfo']);
        $extUserInfo = unserialize($userInfo, [
			'allowed_classes' => [
				stdClass::class,
				CryptoCertificateHelper::class,
				CPCertificate::class,
			]
        ]);

		$certExpiredDate = new DateTime($extUserInfo->cert->ValidTo);
		if ($certExpiredDate->getTimestamp() < time()){
			$errMessage = Loc::getMessage('CRYPTOPRO_EXT_USER_CERTIFICATE_EXPIRED');
			$logger->error($errMessage);
			throw new CertificateException($errMessage);
		}
		if (!preg_match('/^[0-9a-fA-F]{40}$/', trim($extUserInfo->cert->Thumbprint))) {
			$errMessage = Loc::getMessage('CRYPTOPRO_EXT_USER_CERTIFICATE_THUMBPRINT');
			$logger->error($errMessage);
			throw new CertificateException($errMessage);
		}

		if (!$extUserInfo->cert->IsValid) {
			$errMessage = Loc::getMessage('CRYPTOPRO_EXT_CERTIFICATE_IS_NOT_VALID');
			$logger->error($errMessage);
			throw new CertificateException($errMessage);
		}

	    if (empty($extUserInfo->cert->Subject->SNILS)) {
		    $errMessage = Loc::getMessage('CRYPTOPRO_EXT_CERTIFICATE_IS_NOT_VALID');
		    $logger->error($errMessage);
		    throw new CertificateException($errMessage);
	    }

        $arNames = explode(' ', $extUserInfo->cert->Subject->GIVEN_NAME);
        $login = Cutil::translit(
			$extUserInfo->cert->Subject->SUR_NAME." ".$arNames[0],
			"ru",
			["replace_space" => ".", "replace_other" => ""]);

        $arFields = [
            "LOGIN" => $login,
            "LAST_NAME" => $extUserInfo->cert->Subject->SUR_NAME,
            "NAME" => $arNames[0],
            "SECOND_NAME" => $arNames[1],
            "UF_INN_COMPANY" => $extUserInfo->cert->Issuer->INN,
            "PERSONAL_STATE" => $extUserInfo->cert->Subject->REGION,
            "PERSONAL_CITY" => $extUserInfo->cert->Subject->CITY,
            "EXTERNAL_AUTH_ID" => "CRYPTOPRO_AUTH",
            "ACTIVE" => 'Y',
            "LID" => SITE_ID
        ];

        $oUser = new CUser;

        //Проверка, что внеш юзер есть у нас
	    $extUser = UserTable::getList([
		    'select' => ['ID', 'NAME', 'UF_SNILS', 'EXTERNAL_AUTH_ID'],
		    'filter' => [
			    "=EXTERNAL_AUTH_ID" => "CRYPTOPRO_AUTH",
			    "=UF_SNILS" => $extUserInfo->cert->Subject->SNILS
		    ]
	    ])->fetch();

		$authorizeCheck = new AuthorizeCheck();   //Добавляем в группы по-умолчанию при регистрации
	    $arUserGroups = Module::getOption('FEDOR_CRYPTOPRO_ADD_GROUPS');
	    if(!$authorizeCheck->checkAccessSingUser($extUserInfo->cert->Issuer->INN)) {
		    $arUserGroups = Module::getOption('FEDOR_CRYPTOPRO_ADD_GROUPS_NO_COMPYNY');
	    }
		$arUserGroups = explode(',', $arUserGroups);
        if(!$extUser) {
            $canUserRegistration = Module::getOption('FEDOR_CRYPTOPRO_USER_REGISTRATION');
            if($canUserRegistration === 'N') {
                $errMessage = Loc::getMessage("CRYPTOPRO_EXT_NOCAN_USER_REGISTR");
				$logger->error($errMessage);
				throw new Exception($errMessage); //Не разрешено
            }

            $arFields = array_merge($arFields,
                                    [
										"EMAIL" => $extUserInfo->cert->Subject->EMAIL,
                                        "UF_SNILS" => $extUserInfo->cert->Subject->SNILS,
                                    ]);

            $ID = $oUser->Add($arFields);
            if($ID > 0) {
                self::setCertificateUser($ID,$extUserInfo);
                CUser::SetUserGroup($ID, $arUserGroups);
            } else {
                throw new Exception($oUser->LAST_ERROR);
            }
        } else {
            $ID = $extUser["ID"];
            self::setCertificateUser($ID,$extUserInfo);

            $oUser->Update($ID, $arFields);
        }

        return $ID;
    }

    public static function setCertificateUser(int|string $userId, stdClass $userInfo){

        $certificate = UserCertificatesTable::getList(['select'=> ['ID'],'filter' => ['USER_ID' => $userId]])->fetch();

        $arFields = [
            'ISSUER_NAME' => $userInfo->cert->Issuer->COMPANY,
            'INN_FIELD' => $userInfo->cert->Subject->INN,
            'SNEELS_FIELD' => $userInfo->cert->Subject->SNILS,
            'SERIAL_NUMBER' => $userInfo->cert->SerialNumber,
            'SUBJECT_NAME' => $userInfo->cert->Subject->FULL_NAME,
            'THUMBPRINT' => $userInfo->cert->Thumbprint,
            'VALID_FROM_DATE' => new DateTime($userInfo->cert->ValidFrom),
            'VALID_TO_DATE' => new DateTime($userInfo->cert->ValidTo),
            'VERSION' => $userInfo->cert->Version,
        ];

        if($certificate['ID']) {
            UserCertificatesTable::update($certificate['ID'], $arFields);
        } else {
            $arFields['USER_ID'] = $userId;
            UserCertificatesTable::add($arFields);
        }
    }
}