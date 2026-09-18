<?php

namespace Fedor\DigitalsignAuth\Helpers;

use Bitrix\Main\Composite\Internals\AutomaticArea;
use Bitrix\Main\Localization\Loc;
use CMain;
use CModule;
use COption;
use Exception;
use Fedor\DigitalsignAuth\Module;

Loc::loadMessages(__FILE__);
class AuthorizeCheck
{
	public function AuthForm($mess, $show_prolog = true, $show_epilog = true, $not_show_links = "N", $do_die = true): void
	{
		/** @global CMain $APPLICATION */
		global $APPLICATION, $forgot_password, $change_password, $register, $confirm_registration;

		if (str_starts_with($APPLICATION->GetCurDir(), BX_ROOT . "/admin/") || (defined("ADMIN_SECTION") && ADMIN_SECTION === true))
		{
			$isAdmin = "_admin";
		}
		else
		{
			$isAdmin = "";
		}

		if (isset($APPLICATION->arAuthResult) && $APPLICATION->arAuthResult !== true && (is_array($APPLICATION->arAuthResult) || $APPLICATION->arAuthResult <> ''))
		{
			$arAuthResult = $APPLICATION->arAuthResult;
		}
		else
		{
			$arAuthResult = $mess;
		}

		//page title
		$APPLICATION->SetTitle(GetMessage("AUTH_TITLE"));

		if (
			is_array($arAuthResult)
			&& isset($arAuthResult["TYPE"])
			&& isset($arAuthResult["ERROR_TYPE"])
			&& $arAuthResult["TYPE"] === "ERROR"
			&& $arAuthResult["ERROR_TYPE"] === "CHANGE_PASSWORD"
		)
		{
			//require to change the password after N days
			$change_password = "yes";
		}

		$inc_file = "";
		if ($forgot_password == "yes")
		{
			//pass request form
			$APPLICATION->SetTitle(GetMessage("AUTH_TITLE_SEND_PASSWORD"));
			$comp_name = "bitrix:system.auth.forgotpasswd";
			$inc_file = "forgot_password";
		}
		elseif ($change_password == "yes")
		{
			//pass change form
			$APPLICATION->SetTitle(GetMessage("AUTH_TITLE_CHANGE_PASSWORD"));
			$comp_name = "bitrix:system.auth.changepasswd";
			$inc_file = "change_password";
		}
		elseif ($register == "yes" && $isAdmin == "" && COption::GetOptionString("main", "new_user_registration", "N") == "Y")
		{
			//registration form
			$APPLICATION->SetTitle(GetMessage("AUTH_TITLE_REGISTER"));
			$comp_name = "bitrix:system.auth.registration";
		}
		elseif (($confirm_registration === "yes") && ($isAdmin === "") && (COption::GetOptionString("main", "new_user_registration_email_confirmation", "N") === "Y"))
		{
			//confirm registartion
			$APPLICATION->SetTitle(GetMessage("AUTH_TITLE_CONFIRM"));
			$comp_name = "bitrix:system.auth.confirmation";
		}
		elseif (
			CModule::IncludeModule("security")
			&& \Bitrix\Security\Mfa\Otp::isOtpRequired()
			&& (!isset($_REQUEST["login_form"]) || $_REQUEST["login_form"] !== "yes")
		)
		{
			//otp form
			$APPLICATION->SetTitle(GetMessage("AUTH_TITLE_OTP"));
			$comp_name = "bitrix:system.auth.otp";
			$inc_file = "otp";
		}
		else
		{
			header('X-Bitrix-Ajax-Status: Authorize');

			//auth form
			$comp_name = "fedor.digitalsign_auth:system.auth.authorize";
			$inc_file = "authorize";
		}

		if ($show_prolog)
		{
			CMain::PrologActions();

			define("BX_AUTH_FORM", true);
			include($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/include/prolog" . $isAdmin . "_after.php");
		}

		if ($isAdmin == "")
		{
			// form by Components 2.0
			$APPLICATION->IncludeComponent(
				$comp_name,
				COption::GetOptionString("main", "auth_components_template", ""),
				[
					"AUTH_RESULT" => $arAuthResult,
					"NOT_SHOW_LINKS" => $not_show_links,
				]
			);
		}
		else
		{
			include($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/interface/auth/wrapper.php");
		}

		$autoCompositeArea = AutomaticArea::getCurrentArea();
		$autoCompositeArea?->end();

		if ($show_epilog)
		{
			include($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/include/epilog" . $isAdmin . ".php");
		}

		if ($do_die)
		{
			die();
		}
	}

	/**
	 * @param int|string $companyInn
	 * @return true
	 * @throws Exception
	 */
    public function checkAccessSingUser(int|string $companyInn):bool
    {
        global $APPLICATION;

        $isSendError = Module::getOption('FEDOR_CRYPTOPRO_IS_SEND_ERROR');
        if($isSendError === 'N'){
            return true;
        }

        $accessUserLoginInnsSettings = Module::getOption('FEDOR_CRYPTOPRO_ACCESS_USERLOGIN_INN');
        if(!empty($accessUserLoginInnsSettings)){
            $rsInns = explode(',', $accessUserLoginInnsSettings);
            $arInns = [];
            foreach ($rsInns as $arrInn) {
                if (empty($arrInn)) {
	                continue;
                }

                $arInns[] = trim($arrInn);
            }

            if (!in_array($companyInn, $arInns)){
                throw new Exception(Loc::getMessage('FEDOR_CRYPTOPRO_ACCESS_DENIED_BY_COMPANY_INN'));
            } else {
				return false;
            }
        }
        return true;
    }
}