<?php /** @noinspection AutoloadingIssuesInspection */

declare(strict_types=1);

namespace Fedor\DigitalsignAuth\Components;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\{Application, Config\Option, Loader, LoaderException, Security\Sign\Signer, SystemException};
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\UserConsent\Text;
use CBitrixComponent;
use Exception;
use CUser;
use Fedor\DigitalsignAuth\Helpers\CryptoHelper;
use Fedor\DigitalsignAuth\Helpers\AuthorizeCheck;
use Fedor\DigitalsignAuth\Module;
use stdClass;
use Throwable;

/**
 * Class SystemAuthAuthorizeComponent
 * @package Bitrix\Components
 */
class SystemAuthAuthorizeComponent extends CBitrixComponent implements Controllerable
{
    protected array $needModules = [
        'fedor.digitalsign_auth'
    ];

    /**
     * @inheritDoc
     */
    public function onIncludeComponentLang()
    {
        Loc::loadMessages(__FILE__);
    }

    public function onPrepareComponentParams($arParams)
    {
        $this->checkModules();

        $arTypesAuth = Module::getOption('FEDOR_CRYPTOPRO_TYPES_AUTH');
        if(!empty($arTypesAuth)){
            $arParams['TYPE_AUTH'] = explode(',',$arTypesAuth);
        }else{
            $arParams['TYPE_AUTH'] = ['cryptopro'];
        }

        return $arParams;
    }

    /**
     * @return array[]
     */

    public function configureActions(): array
    {
        return [
            'Send' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
	                new ActionFilter\Csrf(),
                ],
                '-prefilters' => [
                    ActionFilter\Authentication::class,
                ],
            ]

        ];
    }

    /**
     * @return void
     */
    public function executeComponent(): void
    {
        global $APPLICATION;

        $this->setUidSession();

        $arParamsToDelete = array(
            "login",
            "login_form",
            "logout",
            "register",
            "forgot_password",
            "change_password",
            "confirm_registration",
            "confirm_code",
            "confirm_user_id",
            "logout_butt",
        );

        $this->arResult["AUTH_URL"] = $APPLICATION->GetCurPageParam("", $arParamsToDelete);
        $this->arResult['UID'] = $this->getUidSession();
		$this->arResult['AGREEMENT_ID'] = Option::get('main', 'new_user_agreement', '', SITE_ID);

        $this->IncludeComponentTemplate();
    }

	/**
	 * @return void
	 * @throws Exception
	 */
    protected function checkModules(): void
    {
        foreach ($this->needModules as $module) {
            if (!Loader::includeModule($module)) {
                throw new SystemException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'), 500);
            }
        }
    }

	/**
	 * @param string $signature
	 * @param array $certificateInfo
	 * @return array
	 */
	public function SendAction(string $signature = '', array $certificateInfo = []): array
    {
        try {
            $this->checkModules();

            if(empty($signature)){
                throw new Exception(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED_CRYPTOPRO'));
            }

            $crypto = new CryptoHelper();
            $signInfo = $crypto->Verify($this->getUidSession(), $signature);

            if ($signInfo['status'] === 'error') { // Подпись не валидна
                throw new Exception(Loc::getMessage('IBLOCK_MODULE_DONT_VALID_SIGN', ['#CODE_ERROR#' => $signInfo['errorMessage']]));
            }

            if ($signInfo[0]->cert->Thumbprint !== $certificateInfo['Thumbprint']) { // Подпись не валидна
                throw new Exception(Loc::getMessage('IBLOCK_MODULE_DONT_VALID_THUMBPRINT'));
            }

			if (!$signInfo[0]->cert->IsValid) {
				throw new Exception(Loc::getMessage('IBLOCK_MODULE_DONT_VALID_CERTIFICATE'));
			}

            $this->setUserInfoSession($signInfo[0]);

            $authorizeCheck = new AuthorizeCheck();
            $authorizeCheck->checkAccessSingUser($signInfo[0]->cert->Issuer->INN);

            global $USER;
            if (!is_object($USER)) $USER = new CUser;
            $arAuthResult = $USER->Login("", "", "Y", "N");
            if($arAuthResult['TYPE'] === 'ERROR'){
                throw new Exception($arAuthResult['MESSAGE']);
            }

			$USER->Authorize($USER->GetID());

			$_SERVER['REMOTE_USER'] = 'Y';
            return [
                'status' => 'success',
                'message' => $arAuthResult
            ];

        } catch (Throwable $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }

    }

    private function setUidSession(){
        $session = Application::getInstance()->getSession();
        if (!$session->has('uid_for_crypropro')) {
            $session->set('uid_for_crypropro', uniqid('crypropro_'));
        }
    }

	/**
	 * @param stdClass $data
	 * @return void
	 * @throws Exception
	 */
	private function setUserInfoSession(stdClass $data): void
    {
        $session = Application::getInstance()->getSession();
		$userInfo = serialize($data);

	    $sign = new Signer();
	    $salted = $sign->sign($userInfo);

        $session->set('userInfo', $salted);
    }

    private function getUidSession(): string
    {
        $session = Application::getInstance()->getSession();
        if ($session->has('uid_for_crypropro')) {
            return $session['uid_for_crypropro'];
        }
        return '';
    }

}