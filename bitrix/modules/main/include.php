<?php

/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2026 Bitrix
 */

use Bitrix\Main;
use Bitrix\Main\Session\Legacy\HealerEarlySessionStart;
use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Config\Option;
use Dev\Main\Migrator\ModuleUpdater;

require_once __DIR__ . "/start.php";

$application = Main\HttpApplication::getInstance();
$application->initializeExtendedKernel([
	"get" => $_GET,
	"post" => $_POST,
	"files" => $_FILES,
	"cookie" => $_COOKIE,
	"server" => $_SERVER,
	"env" => $_ENV
]);

if (class_exists('\Dev\Main\Migrator\ModuleUpdater'))
{
	ModuleUpdater::checkUpdates('main', __DIR__);
}

if (!Main\ModuleManager::isModuleInstalled('bitrix24'))
{
	// wwall rules
	(new Main\Security\W\WWall)->handle();

	$application->addBackgroundJob([
		Main\Security\W\WWall::class, 'refreshRules'
	]);

	// vendor security notifications
	$application->addBackgroundJob([
		Main\Security\Notifications\VendorNotifier::class, 'refreshNotifications'
	]);
}

if (defined('SITE_ID'))
{
	define('LANG', SITE_ID);
}

$context = $application->getContext();
$context->initializeCulture(defined('LANG') ? LANG : null, defined('LANGUAGE_ID') ? LANGUAGE_ID : null);

// needs to be after culture initialization
$application->start();

// Register main's services
ServiceLocator::getInstance()->registerByModuleSettings('main');

// constants for compatibility
$culture = $context->getCulture();
define('SITE_CHARSET', $culture->getCharset());
define('FORMAT_DATE', $culture->getFormatDate());
define('FORMAT_DATETIME', $culture->getFormatDatetime());
define('LANG_CHARSET', SITE_CHARSET);

$site = $context->getSiteObject();
if (!defined('LANG'))
{
	define('LANG', ($site ? $site->getLid() : $context->getLanguage()));
}
define('SITE_DIR', ($site ? $site->getDir() : ''));
if (!defined('SITE_SERVER_NAME'))
{
	define('SITE_SERVER_NAME', ($site ? $site->getServerName() : ''));
}
define('LANG_DIR', SITE_DIR);

if (!defined('LANGUAGE_ID'))
{
	define('LANGUAGE_ID', $context->getLanguage());
}
define('LANG_ADMIN_LID', LANGUAGE_ID);

if (!defined('SITE_ID'))
{
	define('SITE_ID', LANG);
}

/** @global $lang */
$lang = $context->getLanguage();

//define global application object
$GLOBALS["APPLICATION"] = new CMain;

if (!defined("POST_FORM_ACTION_URI"))
{
	define("POST_FORM_ACTION_URI", htmlspecialcharsbx(GetRequestUri()));
}

$GLOBALS["MESS"] = [];
$GLOBALS["ALL_LANG_FILES"] = [];
IncludeModuleLangFile(__DIR__."/tools.php");
IncludeModuleLangFile(__FILE__);

error_reporting((int)Option::get("main", "error_reporting", E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR | E_PARSE) & ~E_DEPRECATED & ~E_WARNING & ~E_NOTICE);

if (!defined("BX_COMP_MANAGED_CACHE") && Option::get("main", "component_managed_cache_on", "Y") != "N")
{
	define("BX_COMP_MANAGED_CACHE", true);
}

// global functions
require_once __DIR__ . "/filter_tools.php";

/*ZDUyZmZNjRlMjBkYzFhZTlkYjFmM2E4ZGNiOWYwNjdlMzU2NmI=*/$GLOBALS['_____454995111']= array(base64_decode('R2'.'V'.'0TW9kdWxl'.'RXZlb'.'nR'.'z'),base64_decode(''.'R'.'XhlY3V'.'0'.'ZU1vZHVsZU'.'V2ZW50'.'RXg'.'='),base64_decode('V3Jpd'.'GVGaW5hbE1lc3Nh'.'Z2U='));$GLOBALS['____825981582']= array(base64_decode('ZGVmaW'.'5'.'l'),base64_decode(''.'Y'.'mFzZTY0'.'X'.'2RlY'.'29'.'kZQ=='),base64_decode('dW5zZXJ'.'pYWx'.'pemU='),base64_decode('aXN'.'fYXJ'.'yYX'.'k='),base64_decode('a'.'W'.'5fY'.'XJyYX'.'k='),base64_decode('c2VyaWFsaXpl'),base64_decode('YmFzZTY'.'0X2VuY2'.'9k'.'ZQ=='),base64_decode('bW'.'t0aW1l'),base64_decode('ZGF0ZQ='.'='),base64_decode('ZGF0Z'.'Q=='),base64_decode('c3RybGV'.'u'),base64_decode('bWt0'.'aW1l'),base64_decode('ZGF0Z'.'Q=='),base64_decode('ZGF0ZQ=='),base64_decode('bWV0aG9k'.'X2V4aXN'.'0cw=='),base64_decode('Y2Fsb'.'F91'.'c2'.'Vy'.'X2Z1bm'.'Nf'.'YXJ'.'yYXk='),base64_decode(''.'c3RybG'.'V'.'u'),base64_decode('c2'.'VyaWFsa'.'Xp'.'l'),base64_decode('YmFzZTY0X2VuY'.'29'.'kZQ'.'='.'='),base64_decode(''.'c3RybGVu'),base64_decode('aXNf'.'YX'.'JyYXk='),base64_decode('c'.'2'.'VyaWFsaX'.'p'.'l'),base64_decode('Y'.'mFzZ'.'TY'.'0X'.'2V'.'uY29kZ'.'Q'.'=='),base64_decode('c2V'.'yaWFsaXpl'),base64_decode('YmF'.'zZTY'.'0X2VuY'.'29kZQ='.'='),base64_decode('aXNfYXJyYXk='),base64_decode(''.'aX'.'NfYXJy'.'YXk'.'='),base64_decode('aW5fY'.'XJyYXk='),base64_decode(''.'aW5f'.'YXJyYXk='),base64_decode('b'.'Wt0aW1l'),base64_decode('ZGF0'.'ZQ=='),base64_decode(''.'ZGF'.'0ZQ=='),base64_decode('ZGF0ZQ=='),base64_decode('b'.'Wt0'.'aW'.'1'.'l'),base64_decode('Z'.'GF'.'0'.'ZQ'.'=='),base64_decode('ZGF0ZQ=='),base64_decode('aW5fYXJyYXk='),base64_decode('c'.'2VyaW'.'FsaXpl'),base64_decode('YmF'.'zZTY0X2'.'VuY2'.'9kZQ=='),base64_decode('aW5'.'0d'.'mF'.'s'),base64_decode('dGltZQ'.'='.'='),base64_decode('Zm'.'l'.'sZV'.'9l'.'eGlzdH'.'M='),base64_decode('c3RyX3JlcG'.'xhY2U'.'='),base64_decode('Y2xhc3NfZX'.'hpc3R'.'z'),base64_decode('ZGVmaW'.'5l'),base64_decode('c3R'.'yc'.'mV2'),base64_decode(''.'c'.'3'.'RydG91'.'cHBlcg'.'='.'='),base64_decode('c3'.'ByaW50Z'.'g='.'='),base64_decode('c3ByaW50Z'.'g=='),base64_decode('c3Vic'.'3R'.'y'),base64_decode(''.'c3'.'Ryc'.'mV2'),base64_decode('YmF'.'zZTY0'.'X2'.'RlY2'.'9k'.'ZQ=='),base64_decode('c3Vic3Ry'),base64_decode(''.'c'.'3'.'RybG'.'Vu'),base64_decode('c3RybG'.'V'.'u'),base64_decode('Y2hy'),base64_decode('b3J'.'k'),base64_decode('b3'.'Jk'),base64_decode('bW'.'t0aW1l'),base64_decode(''.'a'.'W'.'50'.'dmFs'),base64_decode(''.'aW5'.'0'.'dmF'.'s'),base64_decode('aW'.'50d'.'mFs'),base64_decode('a3Nvcn'.'Q'.'='),base64_decode(''.'c3Vic3Ry'),base64_decode('aW1wb'.'G9kZQ=='),base64_decode('ZGVm'.'aW5'.'l'.'ZA=='),base64_decode('YmFz'.'ZTY0X2'.'RlY29kZQ=='),base64_decode('Y2'.'9'.'uc3RhbnQ='),base64_decode(''.'c3RycmV2'),base64_decode('c3ByaW50Zg='.'='),base64_decode(''.'c3RybG'.'V'.'u'),base64_decode('c3RybGVu'),base64_decode('Y2hy'),base64_decode(''.'b3Jk'),base64_decode('b3Jk'),base64_decode('bWt0'.'aW1l'),base64_decode('a'.'W'.'50d'.'mF'.'s'),base64_decode('a'.'W'.'50dmFs'),base64_decode('aW50dm'.'F'.'s'),base64_decode('c3V'.'ic3Ry'),base64_decode('c3Vic3Ry'),base64_decode('ZGV'.'ma'.'W5lZA'.'=='),base64_decode('c3'.'RycmV2'),base64_decode('c3RydG91cHB'.'l'.'c'.'g=='),base64_decode('dGl'.'tZQ=='),base64_decode('bWt0aW1'.'l'),base64_decode('bWt0aW1l'),base64_decode('ZGF0ZQ=='),base64_decode('ZGF'.'0Z'.'Q'.'=='),base64_decode('ZGVmaW'.'5l'),base64_decode('ZGVmaW5'.'l'));if(!function_exists(__NAMESPACE__.'\\___1121276681')){function ___1121276681($_2020732293){static $_278494749= false; if($_278494749 == false) $_278494749=array('SU5'.'UUkFORV'.'R'.'fRURJ'.'VElP'.'Tg==',''.'W'.'Q'.'==','b'.'WFpbg'.'==','fmN'.'wZl'.'9tY'.'XBfdm'.'Fsd'.'W'.'U'.'=','','',''.'YWxsb3dl'.'ZF9jbGFzc2Vz','Z'.'Q'.'==','Zg==','ZQ'.'==','Rg==',''.'W'.'A==',''.'Zg'.'==','b'.'WF'.'pbg==','f'.'m'.'NwZl9tYXB'.'fdmFsd'.'WU=','UG9y'.'dGF'.'s','Rg==','ZQ==','ZQ'.'==','WA'.'==','Rg==','RA==','R'.'A==','bQ='.'=','ZA'.'==',''.'WQ==',''.'Zg==','Zg==',''.'Zg'.'==','Zg'.'==','UG9ydG'.'Fs','R'.'g'.'==','ZQ'.'==','ZQ'.'==','WA==','Rg'.'==','RA==',''.'R'.'A==','bQ='.'=','ZA'.'==','WQ==','bWFpbg==','T24=','U2V0d'.'Glu'.'Z3NDaGF'.'u'.'Z2'.'U=','Zg==',''.'Zg==','Zg'.'==','Zg='.'=','bW'.'Fpb'.'g==','fm'.'NwZl'.'9tYX'.'B'.'fdmFsdWU=','ZQ==','ZQ==','RA='.'=','ZQ==','ZQ==','Z'.'g==','Zg==','Z'.'g==','ZQ'.'==',''.'bW'.'Fpbg==','fmNw'.'Zl9'.'t'.'Y'.'XB'.'fdmFsdWU=','Z'.'Q'.'==',''.'Zg==','Zg='.'=','Z'.'g='.'=','Zg==','bWF'.'pbg='.'=','fmN'.'wZ'.'l9t'.'YXB'.'f'.'dmFsdWU=','ZQ==','Zg='.'=','U'.'G'.'9ydGFs','UG'.'9yd'.'GFs',''.'ZQ==','ZQ==','U'.'G9'.'ydGFs','Rg==','WA==','Rg==','RA'.'==','ZQ==','ZQ==',''.'R'.'A==','bQ'.'==','ZA==','WQ==','ZQ==','WA==','ZQ='.'=','Rg==','ZQ'.'==',''.'R'.'A='.'=','Zg='.'=','ZQ'.'='.'=','RA==','ZQ='.'=','bQ==','Z'.'A==','WQ==','Zg==','Zg==','Zg==','Z'.'g==','Zg'.'='.'=',''.'Z'.'g==',''.'Zg'.'==','Zg'.'==','b'.'WFpbg==','f'.'mNwZl'.'9'.'tYXBfdm'.'Fs'.'dWU'.'=',''.'ZQ==',''.'ZQ==','UG9'.'ydGFs','Rg==','WA='.'=','V'.'Fl'.'QRQ='.'=',''.'REFURQ='.'=','RkVBVFVS'.'RVM=','RVhQSV'.'JFRA='.'=','V'.'FlQRQ='.'=',''.'RA'.'==','V'.'FJZX0RBW'.'VNf'.'Q09'.'VTlQ'.'=','R'.'E'.'FU'.'RQ==','V'.'FJZX0'.'RBW'.'VNfQ'.'09VTlQ=',''.'RVh'.'Q'.'SV'.'JFR'.'A==','R'.'kVB'.'VFV'.'S'.'RVM=','Zg==','Zg='.'=','RE'.'9DVU'.'1FTlRfUk'.'9PVA==','L2Jpd'.'HJpeC9tb2R1bGVzLw==',''.'L2luc3R'.'hbGwvaW5'.'k'.'ZXguc'.'Gh'.'w','Lg==',''.'Xw='.'=','c'.'2VhcmNo','Tg==','','','Q'.'UNUS'.'VZF','WQ==','c'.'29j'.'aWFsbm'.'V0d2'.'9yaw'.'='.'=','Y'.'Wxsb3dfZnJpZWxkcw==','WQ==',''.'SUQ=',''.'c2'.'9jaWFsb'.'m'.'V0'.'d29y'.'a'.'w==','YWxs'.'b'.'3'.'df'.'ZnJpZWx'.'kcw'.'==',''.'SUQ=','c2'.'9ja'.'WFsb'.'mV0d29ya'.'w==','YW'.'xs'.'b3dfZnJ'.'pZWx'.'k'.'cw==',''.'Tg==','','','Q'.'UNU'.'SVZF','WQ'.'='.'=','c'.'29ja'.'WFsb'.'mV0d'.'29yaw==','YW'.'xsb3dfbWlj'.'cm9i'.'bG9'.'nX3'.'VzZXI=',''.'WQ'.'==','S'.'UQ=',''.'c'.'29jaWF'.'sbmV0d29yaw'.'==','Y'.'Wxsb3dfbW'.'ljcm9ibG9nX3Vz'.'ZXI=','SUQ'.'=','c29ja'.'WF'.'sb'.'mV'.'0'.'d2'.'9'.'y'.'aw==','YWx'.'s'.'b3d'.'f'.'bW'.'ljcm'.'9ibG9nX3VzZXI'.'=',''.'c2'.'9jaWFsbmV0d29yaw==','YWxsb'.'3dfbWlj'.'cm9ibG9nX2dy'.'b3Vw',''.'WQ==','SUQ=','c29ja'.'WFsbmV0'.'d'.'29ya'.'w==','YWxsb3dfbWljc'.'m9ibG9nX2dyb3Vw','S'.'UQ=','c29jaWFsbmV0d29'.'yaw==','Y'.'Wxs'.'b3d'.'fbWlj'.'cm9ib'.'G9'.'n'.'X2dyb'.'3'.'V'.'w','Tg==','','','QU'.'NUSV'.'ZF','WQ==',''.'c29'.'ja'.'WF'.'sbmV'.'0d'.'29'.'ya'.'w==','YW'.'xs'.'b'.'3dfZmlsZX'.'NfdX'.'Nlcg==','WQ'.'==','SUQ'.'=','c29jaWF'.'sbmV0d'.'29y'.'aw==','YW'.'xsb3df'.'Zm'.'lsZX'.'Nf'.'dXNlcg==','SUQ'.'=',''.'c2'.'9ja'.'WFsb'.'mV0'.'d29'.'yaw==','YW'.'xsb3d'.'fZ'.'mls'.'Z'.'XNfdXN'.'l'.'cg==','Tg==','','','QUNUSVZF',''.'WQ==','c'.'29jaW'.'F'.'sbmV'.'0d29y'.'aw==','YWx'.'sb3dfYmxvZ191c2V'.'y',''.'WQ='.'=',''.'SUQ=','c'.'29'.'jaWFsb'.'mV0d29'.'yaw==','YWxsb3d'.'fY'.'mxvZ19'.'1c2Vy','SUQ=','c2'.'9j'.'aW'.'Fs'.'bm'.'V0d29yaw==',''.'Y'.'Wxsb3df'.'Ym'.'x'.'vZ191c'.'2Vy','Tg==','','',''.'Q'.'UNUSVZF','WQ==','c29jaWF'.'sbmV0d29y'.'aw==','YW'.'xsb'.'3dfcG'.'h'.'vdG9fdX'.'Nlcg==','WQ==',''.'SUQ=','c29jaWFs'.'b'.'mV'.'0d2'.'9y'.'aw==','YW'.'xs'.'b'.'3'.'dfcGhvdG'.'9fd'.'XNlcg='.'=',''.'SUQ'.'=','c29jaWFsb'.'m'.'V0d29yaw'.'='.'=','YWx'.'s'.'b3d'.'fcGhvdG9fd'.'X'.'Nlcg==','T'.'g==','','','QUNUS'.'VZ'.'F',''.'WQ='.'=','c'.'2'.'9jaWFsb'.'mV0d29'.'yaw==','Y'.'W'.'x'.'sb3dfZ'.'m9ydW1'.'fdXNlcg==','WQ'.'==','SUQ=','c29'.'jaWFsbmV0'.'d'.'29y'.'aw==','YWxsb3dfZm9'.'yd'.'W1fdXNlcg==','SU'.'Q=','c2'.'9ja'.'WFsbmV'.'0d29yaw='.'=','Y'.'Wxsb'.'3d'.'fZm9'.'yd'.'W1fd'.'XNlcg==','T'.'g='.'=','','','QUN'.'USVZF',''.'W'.'Q==',''.'c29jaW'.'Fsb'.'mV'.'0d29yaw==','YWx'.'sb3'.'d'.'fd'.'GFz'.'a3N'.'f'.'dXN'.'l'.'cg==','WQ='.'=',''.'SUQ'.'=','c2'.'9j'.'aW'.'FsbmV0d'.'29y'.'aw='.'=','YWxsb3d'.'fd'.'GFza'.'3Nfd'.'XNlcg==','S'.'UQ=','c'.'29jaW'.'F'.'sbmV0d29yaw==','Y'.'Wx'.'sb3dfdGF'.'za3N'.'f'.'dXN'.'lcg='.'=','c2'.'9'.'j'.'aWFs'.'bmV0d29'.'y'.'aw'.'==','YWxsb3'.'dfdGFza3NfZ3'.'Jv'.'d'.'X'.'A=','WQ==','SUQ'.'=','c29jaWFsb'.'mV'.'0'.'d'.'2'.'9'.'y'.'a'.'w'.'='.'=','Y'.'Wx'.'sb3dfdGFza3N'.'fZ3JvdXA'.'=','SUQ=','c2'.'9jaWFsbmV'.'0d29yaw'.'==','YWx'.'sb'.'3dfdGFz'.'a3'.'NfZ3J'.'vdXA=','dGF'.'za3M=','Tg==','','','Q'.'UN'.'USVZF','WQ==','c29jaW'.'Fsbm'.'V0d29yaw==','YWxs'.'b'.'3dfY'.'2F'.'sZW'.'5kYX'.'JfdXNlcg==','WQ='.'=','S'.'U'.'Q=','c29jaWFsb'.'mV0'.'d29ya'.'w='.'=',''.'Y'.'Wxsb3dfY2F'.'sZW5kYXJfd'.'XNlcg==','S'.'UQ=','c29j'.'aWF'.'sbmV0d'.'29'.'yaw='.'=','YWx'.'sb3d'.'fY2'.'FsZW5kYXJ'.'fdXNlcg='.'=','c29ja'.'WF'.'sbm'.'V'.'0d2'.'9y'.'a'.'w='.'=',''.'YWx'.'sb3dfY2FsZW5k'.'YXJfZ3Jv'.'dXA=','WQ='.'=','SU'.'Q=','c29jaWF'.'sb'.'mV0'.'d2'.'9y'.'a'.'w'.'==','Y'.'W'.'xsb3dfY2FsZW5'.'kYXJfZ3J'.'vdXA=',''.'SUQ=','c'.'29j'.'aWF'.'sb'.'mV0d29'.'yaw==',''.'YWxsb3dfY2'.'Fs'.'ZW5kYX'.'Jf'.'Z3Jv'.'dXA'.'=','QUNUSVZF','WQ'.'==','Tg==','Z'.'Xh0'.'cmFuZXQ=',''.'aW'.'Jsb2Nr',''.'T'.'25BZnRl'.'cklC'.'bG9'.'j'.'a0'.'VsZ'.'W1lbnRVc'.'G'.'Rhd'.'G'.'U=','aW'.'50c'.'mFuZ'.'XQ=','Q0l'.'udHJhbmV0RXZl'.'bnRIY'.'W5kbGVy'.'c'.'w==','U1BS'.'Z'.'Wd'.'p'.'c'.'3Rl'.'clVwZGF0ZW'.'RJdGV'.'t','Q0lud'.'HJhbmV0'.'U2hhcmVwb2l'.'u'.'dDo'.'6QWdlbnRM'.'aXN0'.'cy'.'gpOw='.'=',''.'aW50cmFuZXQ=','Tg==',''.'Q0'.'ludH'.'J'.'hbmV'.'0U2'.'hhcm'.'Vwb2ludDo6'.'Q'.'Wd'.'lbnRRdW'.'V1Z'.'S'.'gpOw==','aW'.'5'.'0cmF'.'uZXQ=','Tg==',''.'Q'.'0ludHJ'.'hbmV'.'0'.'U2hh'.'cm'.'Vwb2ludD'.'o6QWdl'.'bnR'.'V'.'cGRhdG'.'UoKTs=','aW5'.'0cmFu'.'ZX'.'Q=','T'.'g==','aW'.'Jsb2Nr',''.'T25BZ'.'n'.'Rlck'.'l'.'CbG9ja'.'0VsZW'.'1'.'lbnRBZGQ'.'=','aW50cmFuZXQ=',''.'Q'.'0ludHJhbmV0RXZlbnRI'.'YW5kbGVycw==','U1'.'B'.'SZWdpc'.'3'.'RlclV'.'wZGF0ZWRJdGVt','aW'.'Jsb2Nr','T25B'.'Zn'.'Rlck'.'l'.'Cb'.'G'.'9ja0VsZW'.'1l'.'bnRVcGRhdGU=','aW5'.'0'.'c'.'mFuZXQ=','Q0ludHJh'.'bmV0R'.'XZlbnRI'.'YW5'.'kbGV'.'yc'.'w==',''.'U1B'.'SZWdpc3'.'Rl'.'clV'.'wZG'.'F0ZWRJdG'.'Vt','Q0ludHJhbm'.'V0'.'U2'.'hh'.'cmVwb2lu'.'dDo6Q'.'W'.'d'.'lbnR'.'MaX'.'N'.'0'.'cygp'.'Ow==','aW50cmF'.'uZX'.'Q=','Q'.'0lu'.'dHJ'.'hbmV0'.'U2'.'hhc'.'mVwb'.'2ludDo'.'6'.'QWdl'.'bnR'.'RdWV'.'1ZSgp'.'Ow'.'==','aW50cmFuZXQ=','Q0ludH'.'Jh'.'bmV0U2'.'h'.'hcmV'.'wb'.'2lud'.'Do6QWd'.'lbn'.'RVcGRhd'.'GUoKTs=','aW'.'50cmFuZXQ'.'=','Y3Jt','bWFp'.'bg==','T25CZWZv'.'c'.'mVQ'.'cm9s'.'b2c=','bWFp'.'bg'.'==',''.'Q1dpem'.'FyZFNvbFBhbmVsSW'.'50cmFuZXQ=','U2h'.'vd'.'1'.'BhbmV'.'s','L2'.'1'.'vZH'.'VsZXMvaW50cmF'.'u'.'ZXQvc'.'GFuZ'.'W'.'x'.'fYn'.'V'.'0dG9'.'uL'.'nBo'.'cA==','ZX'.'hwaXJl'.'X'.'21lc3My','bm9pdGlkZV'.'90aW1'.'pbGVta'.'XQ=','WQ==','Z'.'HJpbl9wZ'.'X'.'Jnb'.'2tj','JTAxMHMK','R'.'UVYUElS','bW'.'Fpb'.'g==','J'.'X'.'M'.'lcw='.'=','YWRt','aGR'.'y'.'b3dzc2E=','YWRtaW4=','b'.'W'.'9kdWxlc'.'w='.'=',''.'ZGVmaW5l'.'L'.'n'.'BocA'.'==',''.'b'.'WFpbg==','Yml0cml'.'4','UkhTSVR'.'FRVg=','S'.'DR1'.'Nj'.'d'.'ma'.'Hc4N'.'1'.'ZoeXRvc'.'w'.'==','',''.'dGh'.'S','N0h'.'5cjEySHd5MHJ'.'Gcg==','V'.'F9T'.'VEVBTA='.'=','aHR0cHM6Ly9iaX'.'RyaXhz'.'b'.'2Z0L'.'mNvbS9iaXRyaXgvYnM'.'ucG'.'hw','T0'.'xE','UElSRURBVEVT',''.'RE9DV'.'U1FTlRf'.'Uk9P'.'VA==','L'.'w='.'=',''.'Lw='.'=',''.'VEVNUE'.'9SQVJZX0NB'.'Q0hF',''.'VEV'.'NUE9SQVJZX0'.'N'.'BQ'.'0hF','','T05'.'f'.'T0Q=','JX'.'Mlcw==',''.'X'.'0'.'9VUl9'.'C'.'VVM=','U0lU','RURBVE'.'VNQVBFUg==','bm9p'.'dG'.'lkZV90aW1pbGVtaXQ=',''.'b'.'Q==','ZA'.'==','W'.'Q'.'==','U0'.'N'.'SSVBUX05'.'BTUU=',''.'L2JpdHJp'.'e'.'C9jb3'.'Vwb25fYWN0'.'aXZh'.'dGlvbi'.'5waHA'.'=','U'.'0NSSVBUX05BTUU=','L'.'2JpdHJ'.'peC9zZXJ2a'.'W'.'Nlcy9tYWl'.'u'.'L2'.'Fq'.'YXgu'.'c'.'Ghw','L'.'2JpdHJpeC9jb3Vwb2'.'5fY'.'W'.'N0aXZ'.'hdG'.'lv'.'bi5waH'.'A=','U2l0Z'.'U'.'V4cG'.'lyZ'.'URhdGU=');return base64_decode($_278494749[$_2020732293]);}};$GLOBALS['____825981582'][0](___1121276681(0), ___1121276681(1));class CBXFeatures{ private static $_1386036744= 30; private static $_1376102100= array( "Portal" => array( "CompanyCalendar", "CompanyPhoto", "CompanyVideo", "CompanyCareer", "StaffChanges", "StaffAbsence", "CommonDocuments", "MeetingRoomBookingSystem", "Wiki", "Learning", "Vote", "WebLink", "Subscribe", "Friends", "PersonalFiles", "PersonalBlog", "PersonalPhoto", "PersonalForum", "Blog", "Forum", "Gallery", "Board", "MicroBlog", "WebMessenger",), "Communications" => array( "Tasks", "Calendar", "Workgroups", "Jabber", "VideoConference", "Extranet", "SMTP", "Requests", "DAV", "intranet_sharepoint", "timeman", "Idea", "Meeting", "EventList", "Salary", "XDImport",), "Enterprise" => array( "BizProc", "Lists", "Support", "Analytics", "crm", "Controller", "LdapUnlimitedUsers",), "Holding" => array( "Cluster", "MultiSites",),); private static $_216003824= null; private static $_1298142178= null; private static function __587012038(){ if(self::$_216003824 === null){ self::$_216003824= array(); foreach(self::$_1376102100 as $_1396842308 => $_2034603225){ foreach($_2034603225 as $_320176438) self::$_216003824[$_320176438]= $_1396842308;}} if(self::$_1298142178 === null){ self::$_1298142178= array(); $_1909403500= COption::GetOptionString(___1121276681(2), ___1121276681(3), ___1121276681(4)); if($_1909403500 != ___1121276681(5)){ $_1909403500= $GLOBALS['____825981582'][1]($_1909403500); $_1909403500= $GLOBALS['____825981582'][2]($_1909403500,[___1121276681(6) => false]); if($GLOBALS['____825981582'][3]($_1909403500)){ self::$_1298142178= $_1909403500;}} if(empty(self::$_1298142178)){ self::$_1298142178= array(___1121276681(7) => array(), ___1121276681(8) => array());}}} public static function InitiateEditionsSettings($_1760383481){ self::__587012038(); $_1676521681= array(); foreach(self::$_1376102100 as $_1396842308 => $_2034603225){ $_90463820= $GLOBALS['____825981582'][4]($_1396842308, $_1760383481); self::$_1298142178[___1121276681(9)][$_1396842308]=($_90463820? array(___1121276681(10)): array(___1121276681(11))); foreach($_2034603225 as $_320176438){ self::$_1298142178[___1121276681(12)][$_320176438]= $_90463820; if(!$_90463820) $_1676521681[]= array($_320176438, false);}} $_2076183648= $GLOBALS['____825981582'][5](self::$_1298142178); $_2076183648= $GLOBALS['____825981582'][6]($_2076183648); COption::SetOptionString(___1121276681(13), ___1121276681(14), $_2076183648); foreach($_1676521681 as $_1185919376) self::__332519623($_1185919376[(200*2-400)], $_1185919376[round(0+0.5+0.5)]);} public static function IsFeatureEnabled($_320176438){ if($_320176438 == '') return true; self::__587012038(); if(!isset(self::$_216003824[$_320176438])) return true; if(self::$_216003824[$_320176438] == ___1121276681(15)) $_1788234379= array(___1121276681(16)); elseif(isset(self::$_1298142178[___1121276681(17)][self::$_216003824[$_320176438]])) $_1788234379= self::$_1298142178[___1121276681(18)][self::$_216003824[$_320176438]]; else $_1788234379= array(___1121276681(19)); if($_1788234379[min(184,0,61.333333333333)] != ___1121276681(20) && $_1788234379[(239*2-478)] != ___1121276681(21)){ return false;} elseif($_1788234379[(183*2-366)] == ___1121276681(22)){ if($_1788234379[round(0+0.5+0.5)]< $GLOBALS['____825981582'][7]((201*2-402),(201*2-402),(1072/2-536), Date(___1121276681(23)), $GLOBALS['____825981582'][8](___1121276681(24))- self::$_1386036744, $GLOBALS['____825981582'][9](___1121276681(25)))){ if(!isset($_1788234379[round(0+2)]) ||!$_1788234379[round(0+0.66666666666667+0.66666666666667+0.66666666666667)]) self::__834459202(self::$_216003824[$_320176438]); return false;}} return!isset(self::$_1298142178[___1121276681(26)][$_320176438]) || self::$_1298142178[___1121276681(27)][$_320176438];} public static function IsFeatureInstalled($_320176438){ if($GLOBALS['____825981582'][10]($_320176438) <= 0) return true; self::__587012038(); return(isset(self::$_1298142178[___1121276681(28)][$_320176438]) && self::$_1298142178[___1121276681(29)][$_320176438]);} public static function IsFeatureEditable($_320176438){ if($_320176438 == '') return true; self::__587012038(); if(!isset(self::$_216003824[$_320176438])) return true; if(self::$_216003824[$_320176438] == ___1121276681(30)) $_1788234379= array(___1121276681(31)); elseif(isset(self::$_1298142178[___1121276681(32)][self::$_216003824[$_320176438]])) $_1788234379= self::$_1298142178[___1121276681(33)][self::$_216003824[$_320176438]]; else $_1788234379= array(___1121276681(34)); if($_1788234379[(1268/2-634)] != ___1121276681(35) && $_1788234379[(1256/2-628)] != ___1121276681(36)){ return false;} elseif($_1788234379[(131*2-262)] == ___1121276681(37)){ if($_1788234379[round(0+1)]< $GLOBALS['____825981582'][11]((1152/2-576),(980-2*490),(1144/2-572), Date(___1121276681(38)), $GLOBALS['____825981582'][12](___1121276681(39))- self::$_1386036744, $GLOBALS['____825981582'][13](___1121276681(40)))){ if(!isset($_1788234379[round(0+0.66666666666667+0.66666666666667+0.66666666666667)]) ||!$_1788234379[round(0+1+1)]) self::__834459202(self::$_216003824[$_320176438]); return false;}} return true;} private static function __332519623($_320176438, $_434741920){ if($GLOBALS['____825981582'][14]("CBXFeatures", "On".$_320176438."SettingsChange")) $GLOBALS['____825981582'][15](array("CBXFeatures", "On".$_320176438."SettingsChange"), array($_320176438, $_434741920)); $_1225946482= $GLOBALS['_____454995111'][0](___1121276681(41), ___1121276681(42).$_320176438.___1121276681(43)); while($_557447086= $_1225946482->Fetch()) $GLOBALS['_____454995111'][1]($_557447086, array($_320176438, $_434741920));} public static function SetFeatureEnabled($_320176438, $_434741920= true, $_320104083= true){ if($GLOBALS['____825981582'][16]($_320176438) <= 0) return; if(!self::IsFeatureEditable($_320176438)) $_434741920= false; $_434741920= (bool)$_434741920; self::__587012038(); $_891373972=(!isset(self::$_1298142178[___1121276681(44)][$_320176438]) && $_434741920 || isset(self::$_1298142178[___1121276681(45)][$_320176438]) && $_434741920 != self::$_1298142178[___1121276681(46)][$_320176438]); self::$_1298142178[___1121276681(47)][$_320176438]= $_434741920; $_2076183648= $GLOBALS['____825981582'][17](self::$_1298142178); $_2076183648= $GLOBALS['____825981582'][18]($_2076183648); COption::SetOptionString(___1121276681(48), ___1121276681(49), $_2076183648); if($_891373972 && $_320104083) self::__332519623($_320176438, $_434741920);} private static function __834459202($_1396842308){ if($GLOBALS['____825981582'][19]($_1396842308) <= 0 || $_1396842308 == "Portal") return; self::__587012038(); if(!isset(self::$_1298142178[___1121276681(50)][$_1396842308]) || self::$_1298142178[___1121276681(51)][$_1396842308][(960-2*480)] != ___1121276681(52)) return; if(isset(self::$_1298142178[___1121276681(53)][$_1396842308][round(0+1+1)]) && self::$_1298142178[___1121276681(54)][$_1396842308][round(0+0.4+0.4+0.4+0.4+0.4)]) return; $_1676521681= array(); if(isset(self::$_1376102100[$_1396842308]) && $GLOBALS['____825981582'][20](self::$_1376102100[$_1396842308])){ foreach(self::$_1376102100[$_1396842308] as $_320176438){ if(isset(self::$_1298142178[___1121276681(55)][$_320176438]) && self::$_1298142178[___1121276681(56)][$_320176438]){ self::$_1298142178[___1121276681(57)][$_320176438]= false; $_1676521681[]= array($_320176438, false);}} self::$_1298142178[___1121276681(58)][$_1396842308][round(0+1+1)]= true;} $_2076183648= $GLOBALS['____825981582'][21](self::$_1298142178); $_2076183648= $GLOBALS['____825981582'][22]($_2076183648); COption::SetOptionString(___1121276681(59), ___1121276681(60), $_2076183648); foreach($_1676521681 as $_1185919376) self::__332519623($_1185919376[(980-2*490)], $_1185919376[round(0+1)]);} public static function ModifyFeaturesSettings($_1760383481, $_2034603225){ self::__587012038(); foreach($_1760383481 as $_1396842308 => $_1521308656) self::$_1298142178[___1121276681(61)][$_1396842308]= $_1521308656; $_1676521681= array(); foreach($_2034603225 as $_320176438 => $_434741920){ if(!isset(self::$_1298142178[___1121276681(62)][$_320176438]) && $_434741920 || isset(self::$_1298142178[___1121276681(63)][$_320176438]) && $_434741920 != self::$_1298142178[___1121276681(64)][$_320176438]) $_1676521681[]= array($_320176438, $_434741920); self::$_1298142178[___1121276681(65)][$_320176438]= $_434741920;} $_2076183648= $GLOBALS['____825981582'][23](self::$_1298142178); $_2076183648= $GLOBALS['____825981582'][24]($_2076183648); COption::SetOptionString(___1121276681(66), ___1121276681(67), $_2076183648); self::$_1298142178= null; foreach($_1676521681 as $_1185919376) self::__332519623($_1185919376[(151*2-302)], $_1185919376[round(0+0.2+0.2+0.2+0.2+0.2)]);} public static function SaveFeaturesSettings($_534805936, $_258890281){ self::__587012038(); $_1100260979= array(___1121276681(68) => array(), ___1121276681(69) => array()); if(!$GLOBALS['____825981582'][25]($_534805936)) $_534805936= array(); if(!$GLOBALS['____825981582'][26]($_258890281)) $_258890281= array(); if(!$GLOBALS['____825981582'][27](___1121276681(70), $_534805936)) $_534805936[]= ___1121276681(71); foreach(self::$_1376102100 as $_1396842308 => $_2034603225){ if(isset(self::$_1298142178[___1121276681(72)][$_1396842308])){ $_1662789394= self::$_1298142178[___1121276681(73)][$_1396842308];} else{ $_1662789394=($_1396842308 == ___1121276681(74)? array(___1121276681(75)): array(___1121276681(76)));} if($_1662789394[(210*2-420)] == ___1121276681(77) || $_1662789394[min(248,0,82.666666666667)] == ___1121276681(78)){ $_1100260979[___1121276681(79)][$_1396842308]= $_1662789394;} else{ if($GLOBALS['____825981582'][28]($_1396842308, $_534805936)) $_1100260979[___1121276681(80)][$_1396842308]= array(___1121276681(81), $GLOBALS['____825981582'][29]((850-2*425),(806-2*403),(1396/2-698), $GLOBALS['____825981582'][30](___1121276681(82)), $GLOBALS['____825981582'][31](___1121276681(83)), $GLOBALS['____825981582'][32](___1121276681(84)))); else $_1100260979[___1121276681(85)][$_1396842308]= array(___1121276681(86));}} $_1676521681= array(); foreach(self::$_216003824 as $_320176438 => $_1396842308){ if($_1100260979[___1121276681(87)][$_1396842308][(1056/2-528)] != ___1121276681(88) && $_1100260979[___1121276681(89)][$_1396842308][(810-2*405)] != ___1121276681(90)){ $_1100260979[___1121276681(91)][$_320176438]= false;} else{ if($_1100260979[___1121276681(92)][$_1396842308][(160*2-320)] == ___1121276681(93) && $_1100260979[___1121276681(94)][$_1396842308][round(0+1)]< $GLOBALS['____825981582'][33](min(232,0,77.333333333333), min(138,0,46),(173*2-346), Date(___1121276681(95)), $GLOBALS['____825981582'][34](___1121276681(96))- self::$_1386036744, $GLOBALS['____825981582'][35](___1121276681(97)))) $_1100260979[___1121276681(98)][$_320176438]= false; else $_1100260979[___1121276681(99)][$_320176438]= $GLOBALS['____825981582'][36]($_320176438, $_258890281); if(!isset(self::$_1298142178[___1121276681(100)][$_320176438]) && $_1100260979[___1121276681(101)][$_320176438] || isset(self::$_1298142178[___1121276681(102)][$_320176438]) && $_1100260979[___1121276681(103)][$_320176438] != self::$_1298142178[___1121276681(104)][$_320176438]) $_1676521681[]= array($_320176438, $_1100260979[___1121276681(105)][$_320176438]);}} $_2076183648= $GLOBALS['____825981582'][37]($_1100260979); $_2076183648= $GLOBALS['____825981582'][38]($_2076183648); COption::SetOptionString(___1121276681(106), ___1121276681(107), $_2076183648); self::$_1298142178= null; foreach($_1676521681 as $_1185919376) self::__332519623($_1185919376[(902-2*451)], $_1185919376[round(0+0.5+0.5)]);} public static function GetFeaturesList(){ self::__587012038(); $_860377645= array(); foreach(self::$_1376102100 as $_1396842308 => $_2034603225){ if(isset(self::$_1298142178[___1121276681(108)][$_1396842308])){ $_1662789394= self::$_1298142178[___1121276681(109)][$_1396842308];} else{ $_1662789394=($_1396842308 == ___1121276681(110)? array(___1121276681(111)): array(___1121276681(112)));} $_860377645[$_1396842308]= array( ___1121276681(113) => $_1662789394[(1324/2-662)], ___1121276681(114) => $_1662789394[round(0+0.5+0.5)], ___1121276681(115) => array(),); $_860377645[$_1396842308][___1121276681(116)]= false; if($_860377645[$_1396842308][___1121276681(117)] == ___1121276681(118)){ $_860377645[$_1396842308][___1121276681(119)]= $GLOBALS['____825981582'][39](($GLOBALS['____825981582'][40]()- $_860377645[$_1396842308][___1121276681(120)])/ round(0+86400)); if($_860377645[$_1396842308][___1121276681(121)]> self::$_1386036744) $_860377645[$_1396842308][___1121276681(122)]= true;} foreach($_2034603225 as $_320176438) $_860377645[$_1396842308][___1121276681(123)][$_320176438]=(!isset(self::$_1298142178[___1121276681(124)][$_320176438]) || self::$_1298142178[___1121276681(125)][$_320176438]);} return $_860377645;} private static function __448354837($_362232685, $_1397661013){ if(IsModuleInstalled($_362232685) == $_1397661013) return true; $_1463344287= $_SERVER[___1121276681(126)].___1121276681(127).$_362232685.___1121276681(128); if(!$GLOBALS['____825981582'][41]($_1463344287)) return false; include_once($_1463344287); $_1632646060= $GLOBALS['____825981582'][42](___1121276681(129), ___1121276681(130), $_362232685); if(!$GLOBALS['____825981582'][43]($_1632646060)) return false; $_1047736101= new $_1632646060; if($_1397661013){ if(!$_1047736101->InstallDB()) return false; $_1047736101->InstallEvents(); if(!$_1047736101->InstallFiles()) return false;} else{ if(CModule::IncludeModule(___1121276681(131))) CSearch::DeleteIndex($_362232685); UnRegisterModule($_362232685);} return true;} protected static function OnRequestsSettingsChange($_320176438, $_434741920){ self::__448354837("form", $_434741920);} protected static function OnLearningSettingsChange($_320176438, $_434741920){ self::__448354837("learning", $_434741920);} protected static function OnJabberSettingsChange($_320176438, $_434741920){ self::__448354837("xmpp", $_434741920);} protected static function OnVideoConferenceSettingsChange($_320176438, $_434741920){} protected static function OnBizProcSettingsChange($_320176438, $_434741920){ self::__448354837("bizprocdesigner", $_434741920);} protected static function OnListsSettingsChange($_320176438, $_434741920){ self::__448354837("lists", $_434741920);} protected static function OnWikiSettingsChange($_320176438, $_434741920){ self::__448354837("wiki", $_434741920);} protected static function OnSupportSettingsChange($_320176438, $_434741920){ self::__448354837("support", $_434741920);} protected static function OnControllerSettingsChange($_320176438, $_434741920){ self::__448354837("Controller", $_434741920);} protected static function OnAnalyticsSettingsChange($_320176438, $_434741920){ self::__448354837("statistic", $_434741920);} protected static function OnVoteSettingsChange($_320176438, $_434741920){ self::__448354837("vote", $_434741920);} protected static function OnFriendsSettingsChange($_320176438, $_434741920){ if($_434741920) $_1269022160= "Y"; else $_1269022160= ___1121276681(132); $_264684601= CSite::GetList(___1121276681(133), ___1121276681(134), array(___1121276681(135) => ___1121276681(136))); while($_1842168085= $_264684601->Fetch()){ if(COption::GetOptionString(___1121276681(137), ___1121276681(138), ___1121276681(139), $_1842168085[___1121276681(140)]) != $_1269022160){ COption::SetOptionString(___1121276681(141), ___1121276681(142), $_1269022160, false, $_1842168085[___1121276681(143)]); COption::SetOptionString(___1121276681(144), ___1121276681(145), $_1269022160);}}} protected static function OnMicroBlogSettingsChange($_320176438, $_434741920){ if($_434741920) $_1269022160= "Y"; else $_1269022160= ___1121276681(146); $_264684601= CSite::GetList(___1121276681(147), ___1121276681(148), array(___1121276681(149) => ___1121276681(150))); while($_1842168085= $_264684601->Fetch()){ if(COption::GetOptionString(___1121276681(151), ___1121276681(152), ___1121276681(153), $_1842168085[___1121276681(154)]) != $_1269022160){ COption::SetOptionString(___1121276681(155), ___1121276681(156), $_1269022160, false, $_1842168085[___1121276681(157)]); COption::SetOptionString(___1121276681(158), ___1121276681(159), $_1269022160);} if(COption::GetOptionString(___1121276681(160), ___1121276681(161), ___1121276681(162), $_1842168085[___1121276681(163)]) != $_1269022160){ COption::SetOptionString(___1121276681(164), ___1121276681(165), $_1269022160, false, $_1842168085[___1121276681(166)]); COption::SetOptionString(___1121276681(167), ___1121276681(168), $_1269022160);}}} protected static function OnPersonalFilesSettingsChange($_320176438, $_434741920){ if($_434741920) $_1269022160= "Y"; else $_1269022160= ___1121276681(169); $_264684601= CSite::GetList(___1121276681(170), ___1121276681(171), array(___1121276681(172) => ___1121276681(173))); while($_1842168085= $_264684601->Fetch()){ if(COption::GetOptionString(___1121276681(174), ___1121276681(175), ___1121276681(176), $_1842168085[___1121276681(177)]) != $_1269022160){ COption::SetOptionString(___1121276681(178), ___1121276681(179), $_1269022160, false, $_1842168085[___1121276681(180)]); COption::SetOptionString(___1121276681(181), ___1121276681(182), $_1269022160);}}} protected static function OnPersonalBlogSettingsChange($_320176438, $_434741920){ if($_434741920) $_1269022160= "Y"; else $_1269022160= ___1121276681(183); $_264684601= CSite::GetList(___1121276681(184), ___1121276681(185), array(___1121276681(186) => ___1121276681(187))); while($_1842168085= $_264684601->Fetch()){ if(COption::GetOptionString(___1121276681(188), ___1121276681(189), ___1121276681(190), $_1842168085[___1121276681(191)]) != $_1269022160){ COption::SetOptionString(___1121276681(192), ___1121276681(193), $_1269022160, false, $_1842168085[___1121276681(194)]); COption::SetOptionString(___1121276681(195), ___1121276681(196), $_1269022160);}}} protected static function OnPersonalPhotoSettingsChange($_320176438, $_434741920){ if($_434741920) $_1269022160= "Y"; else $_1269022160= ___1121276681(197); $_264684601= CSite::GetList(___1121276681(198), ___1121276681(199), array(___1121276681(200) => ___1121276681(201))); while($_1842168085= $_264684601->Fetch()){ if(COption::GetOptionString(___1121276681(202), ___1121276681(203), ___1121276681(204), $_1842168085[___1121276681(205)]) != $_1269022160){ COption::SetOptionString(___1121276681(206), ___1121276681(207), $_1269022160, false, $_1842168085[___1121276681(208)]); COption::SetOptionString(___1121276681(209), ___1121276681(210), $_1269022160);}}} protected static function OnPersonalForumSettingsChange($_320176438, $_434741920){ if($_434741920) $_1269022160= "Y"; else $_1269022160= ___1121276681(211); $_264684601= CSite::GetList(___1121276681(212), ___1121276681(213), array(___1121276681(214) => ___1121276681(215))); while($_1842168085= $_264684601->Fetch()){ if(COption::GetOptionString(___1121276681(216), ___1121276681(217), ___1121276681(218), $_1842168085[___1121276681(219)]) != $_1269022160){ COption::SetOptionString(___1121276681(220), ___1121276681(221), $_1269022160, false, $_1842168085[___1121276681(222)]); COption::SetOptionString(___1121276681(223), ___1121276681(224), $_1269022160);}}} protected static function OnTasksSettingsChange($_320176438, $_434741920){ if($_434741920) $_1269022160= "Y"; else $_1269022160= ___1121276681(225); $_264684601= CSite::GetList(___1121276681(226), ___1121276681(227), array(___1121276681(228) => ___1121276681(229))); while($_1842168085= $_264684601->Fetch()){ if(COption::GetOptionString(___1121276681(230), ___1121276681(231), ___1121276681(232), $_1842168085[___1121276681(233)]) != $_1269022160){ COption::SetOptionString(___1121276681(234), ___1121276681(235), $_1269022160, false, $_1842168085[___1121276681(236)]); COption::SetOptionString(___1121276681(237), ___1121276681(238), $_1269022160);} if(COption::GetOptionString(___1121276681(239), ___1121276681(240), ___1121276681(241), $_1842168085[___1121276681(242)]) != $_1269022160){ COption::SetOptionString(___1121276681(243), ___1121276681(244), $_1269022160, false, $_1842168085[___1121276681(245)]); COption::SetOptionString(___1121276681(246), ___1121276681(247), $_1269022160);}} self::__448354837(___1121276681(248), $_434741920);} protected static function OnCalendarSettingsChange($_320176438, $_434741920){ if($_434741920) $_1269022160= "Y"; else $_1269022160= ___1121276681(249); $_264684601= CSite::GetList(___1121276681(250), ___1121276681(251), array(___1121276681(252) => ___1121276681(253))); while($_1842168085= $_264684601->Fetch()){ if(COption::GetOptionString(___1121276681(254), ___1121276681(255), ___1121276681(256), $_1842168085[___1121276681(257)]) != $_1269022160){ COption::SetOptionString(___1121276681(258), ___1121276681(259), $_1269022160, false, $_1842168085[___1121276681(260)]); COption::SetOptionString(___1121276681(261), ___1121276681(262), $_1269022160);} if(COption::GetOptionString(___1121276681(263), ___1121276681(264), ___1121276681(265), $_1842168085[___1121276681(266)]) != $_1269022160){ COption::SetOptionString(___1121276681(267), ___1121276681(268), $_1269022160, false, $_1842168085[___1121276681(269)]); COption::SetOptionString(___1121276681(270), ___1121276681(271), $_1269022160);}}} protected static function OnSMTPSettingsChange($_320176438, $_434741920){ self::__448354837("mail", $_434741920);} protected static function OnExtranetSettingsChange($_320176438, $_434741920){ $_441428886= COption::GetOptionString("extranet", "extranet_site", ""); if($_441428886){ $_364639873= new CSite; $_364639873->Update($_441428886, array(___1121276681(272) =>($_434741920? ___1121276681(273): ___1121276681(274))));} self::__448354837(___1121276681(275), $_434741920);} protected static function OnDAVSettingsChange($_320176438, $_434741920){ self::__448354837("dav", $_434741920);} protected static function OntimemanSettingsChange($_320176438, $_434741920){ self::__448354837("timeman", $_434741920);} protected static function Onintranet_sharepointSettingsChange($_320176438, $_434741920){ if($_434741920){ RegisterModuleDependences("iblock", "OnAfterIBlockElementAdd", "intranet", "CIntranetEventHandlers", "SPRegisterUpdatedItem"); RegisterModuleDependences(___1121276681(276), ___1121276681(277), ___1121276681(278), ___1121276681(279), ___1121276681(280)); CAgent::AddAgent(___1121276681(281), ___1121276681(282), ___1121276681(283), round(0+250+250)); CAgent::AddAgent(___1121276681(284), ___1121276681(285), ___1121276681(286), round(0+150+150)); CAgent::AddAgent(___1121276681(287), ___1121276681(288), ___1121276681(289), round(0+1200+1200+1200));} else{ UnRegisterModuleDependences(___1121276681(290), ___1121276681(291), ___1121276681(292), ___1121276681(293), ___1121276681(294)); UnRegisterModuleDependences(___1121276681(295), ___1121276681(296), ___1121276681(297), ___1121276681(298), ___1121276681(299)); CAgent::RemoveAgent(___1121276681(300), ___1121276681(301)); CAgent::RemoveAgent(___1121276681(302), ___1121276681(303)); CAgent::RemoveAgent(___1121276681(304), ___1121276681(305));}} protected static function OncrmSettingsChange($_320176438, $_434741920){ if($_434741920) COption::SetOptionString("crm", "form_features", "Y"); self::__448354837(___1121276681(306), $_434741920);} protected static function OnClusterSettingsChange($_320176438, $_434741920){ self::__448354837("cluster", $_434741920);} protected static function OnMultiSitesSettingsChange($_320176438, $_434741920){ if($_434741920) RegisterModuleDependences("main", "OnBeforeProlog", "main", "CWizardSolPanelIntranet", "ShowPanel", 100, "/modules/intranet/panel_button.php"); else UnRegisterModuleDependences(___1121276681(307), ___1121276681(308), ___1121276681(309), ___1121276681(310), ___1121276681(311), ___1121276681(312));} protected static function OnIdeaSettingsChange($_320176438, $_434741920){ self::__448354837("idea", $_434741920);} protected static function OnMeetingSettingsChange($_320176438, $_434741920){ self::__448354837("meeting", $_434741920);} protected static function OnXDImportSettingsChange($_320176438, $_434741920){ self::__448354837("xdimport", $_434741920);}} $_962804080= GetMessage(___1121276681(313));$_1754250814= round(0+3.75+3.75+3.75+3.75);$GLOBALS['____825981582'][44]($GLOBALS['____825981582'][45]($GLOBALS['____825981582'][46](___1121276681(314))), ___1121276681(315));$_351299879= round(0+0.5+0.5); $_1847350728= ___1121276681(316); unset($_214545340); $_1071843768= $GLOBALS['____825981582'][47](___1121276681(317), ___1121276681(318)); $_214545340= \COption::GetOptionString(___1121276681(319), $GLOBALS['____825981582'][48](___1121276681(320),___1121276681(321),$GLOBALS['____825981582'][49]($_1847350728, round(0+0.4+0.4+0.4+0.4+0.4), round(0+1.3333333333333+1.3333333333333+1.3333333333333))).$GLOBALS['____825981582'][50](___1121276681(322))); $_965610401= array(round(0+3.4+3.4+3.4+3.4+3.4) => ___1121276681(323), round(0+3.5+3.5) => ___1121276681(324), round(0+11+11) => ___1121276681(325), round(0+3+3+3+3) => ___1121276681(326), round(0+0.75+0.75+0.75+0.75) => ___1121276681(327)); $_708218718= ___1121276681(328); while($_214545340){ $_459979096= ___1121276681(329); $_745370292= $GLOBALS['____825981582'][51]($_214545340); $_854704514= ___1121276681(330); $_459979096= $GLOBALS['____825981582'][52](___1121276681(331).$_459979096,(910-2*455),-round(0+2.5+2.5)).___1121276681(332); $_553255302= $GLOBALS['____825981582'][53]($_459979096); $_1577504728=(868-2*434); for($_942337255= min(80,0,26.666666666667); $_942337255<$GLOBALS['____825981582'][54]($_745370292); $_942337255++){ $_854704514 .= $GLOBALS['____825981582'][55]($GLOBALS['____825981582'][56]($_745370292[$_942337255])^ $GLOBALS['____825981582'][57]($_459979096[$_1577504728])); if($_1577504728==$_553255302-round(0+0.33333333333333+0.33333333333333+0.33333333333333)) $_1577504728=(994-2*497); else $_1577504728= $_1577504728+ round(0+1);} $_351299879= $GLOBALS['____825981582'][58](min(46,0,15.333333333333),(1448/2-724),(164*2-328), $GLOBALS['____825981582'][59]($_854704514[round(0+3+3)].$_854704514[round(0+0.75+0.75+0.75+0.75)]), $GLOBALS['____825981582'][60]($_854704514[round(0+0.2+0.2+0.2+0.2+0.2)].$_854704514[round(0+3.5+3.5+3.5+3.5)]), $GLOBALS['____825981582'][61]($_854704514[round(0+5+5)].$_854704514[round(0+18)].$_854704514[round(0+1.4+1.4+1.4+1.4+1.4)].$_854704514[round(0+2.4+2.4+2.4+2.4+2.4)])); unset($_459979096); break;} $_110784415= ___1121276681(333); $GLOBALS['____825981582'][62]($_965610401); $_1801358426= ___1121276681(334); $_708218718= ___1121276681(335).$GLOBALS['____825981582'][63]($_708218718.___1121276681(336), round(0+2),-round(0+1));@include($_SERVER[___1121276681(337)].___1121276681(338).$GLOBALS['____825981582'][64](___1121276681(339), $_965610401)); $_15306703= round(0+0.66666666666667+0.66666666666667+0.66666666666667); while($GLOBALS['____825981582'][65](___1121276681(340))){ $_225712360= $GLOBALS['____825981582'][66]($GLOBALS['____825981582'][67](___1121276681(341))); $_1222499218= ___1121276681(342); $_110784415= $GLOBALS['____825981582'][68](___1121276681(343)).$GLOBALS['____825981582'][69](___1121276681(344),$_110784415,___1121276681(345)); $_690540516= $GLOBALS['____825981582'][70]($_110784415); $_1577504728=(940-2*470); for($_942337255= min(216,0,72); $_942337255<$GLOBALS['____825981582'][71]($_225712360); $_942337255++){ $_1222499218 .= $GLOBALS['____825981582'][72]($GLOBALS['____825981582'][73]($_225712360[$_942337255])^ $GLOBALS['____825981582'][74]($_110784415[$_1577504728])); if($_1577504728==$_690540516-round(0+1)) $_1577504728=(1144/2-572); else $_1577504728= $_1577504728+ round(0+1);} $_15306703= $GLOBALS['____825981582'][75](min(194,0,64.666666666667), min(138,0,46),(792-2*396), $GLOBALS['____825981582'][76]($_1222499218[round(0+2+2+2)].$_1222499218[round(0+3.2+3.2+3.2+3.2+3.2)]), $GLOBALS['____825981582'][77]($_1222499218[round(0+3+3+3)].$_1222499218[round(0+2)]), $GLOBALS['____825981582'][78]($_1222499218[round(0+2.4+2.4+2.4+2.4+2.4)].$_1222499218[round(0+1.75+1.75+1.75+1.75)].$_1222499218[round(0+7+7)].$_1222499218[round(0+3)])); unset($_110784415); break;} $_1071843768= ___1121276681(346).$GLOBALS['____825981582'][79]($GLOBALS['____825981582'][80]($_1071843768, round(0+0.6+0.6+0.6+0.6+0.6),-round(0+0.2+0.2+0.2+0.2+0.2)).___1121276681(347), round(0+0.25+0.25+0.25+0.25),-round(0+1+1+1+1+1));while(!$GLOBALS['____825981582'][81]($GLOBALS['____825981582'][82]($GLOBALS['____825981582'][83](___1121276681(348))))){function __f($_455248391){return $_455248391+__f($_455248391);}__f(round(0+0.5+0.5));};for($_942337255=(229*2-458),$_458034836=($GLOBALS['____825981582'][84]()< $GLOBALS['____825981582'][85]((1032/2-516),(1104/2-552),(1496/2-748),round(0+1.6666666666667+1.6666666666667+1.6666666666667),round(0+1),round(0+504.5+504.5+504.5+504.5)) || $_351299879 <= round(0+2.5+2.5+2.5+2.5)),$_1356047426=($_351299879< $GLOBALS['____825981582'][86]((248*2-496),min(30,0,10),(1396/2-698),Date(___1121276681(349)),$GLOBALS['____825981582'][87](___1121276681(350))-$_1754250814,$GLOBALS['____825981582'][88](___1121276681(351)))),$_763365954=($_SERVER[___1121276681(352)]!==___1121276681(353)&&$_SERVER[___1121276681(354)]!==___1121276681(355)); $_942337255< round(0+10),($_458034836 || $_1356047426 || $_351299879 != $_15306703) && $_763365954; $_942337255++,LocalRedirect(___1121276681(356)),exit,$GLOBALS['_____454995111'][2]($_962804080));$GLOBALS['____825981582'][89]($_708218718, $_351299879); $GLOBALS['____825981582'][90]($_1071843768, $_15306703); $GLOBALS[___1121276681(357)]= OLDSITEEXPIREDATE;/**/			//Do not remove this

// Component 2.0 template engines
$GLOBALS['arCustomTemplateEngines'] = [];

// User fields manager
$GLOBALS['USER_FIELD_MANAGER'] = new CUserTypeManager;

if (file_exists(($_fname = __DIR__ . "/classes/general/update_db_updater.php")))
{
	$US_HOST_PROCESS_MAIN = false;
	include $_fname;
}

if (($_fname = getLocalPath("init.php")) !== false)
{
	include_once $_SERVER["DOCUMENT_ROOT"] . $_fname;
}

if (($_fname = getLocalPath("php_interface/init.php", BX_PERSONAL_ROOT)) !== false)
{
	include_once $_SERVER["DOCUMENT_ROOT"] . $_fname;
}

if (($_fname = getLocalPath("php_interface/" . SITE_ID . "/init.php", BX_PERSONAL_ROOT)) !== false)
{
	include_once $_SERVER["DOCUMENT_ROOT"] . $_fname;
}

if ((!(defined("STATISTIC_ONLY") && STATISTIC_ONLY && !str_starts_with($GLOBALS["APPLICATION"]->GetCurPage(), BX_ROOT . "/admin/"))) && Option::get("main", "include_charset", "Y") == "Y" && LANG_CHARSET != '')
{
	header("Content-Type: text/html; charset=".LANG_CHARSET);
}

$license = $application->getLicense();
header("X-Powered-CMS: Bitrix Site Manager (" . ($license->isDemoKey() ? "DEMO" : $license->getPublicHashKey()) . ")");

if (Option::get("main", "update_devsrv") == "Y")
{
	header("X-DevSrv-CMS: Bitrix");
}

//agents
if (Option::get("main", "check_agents", "Y") == "Y")
{
	$application->addBackgroundJob(["CAgent", "CheckAgents"], [], Main\Application::JOB_PRIORITY_LOW);
}

//send email events
if (Option::get("main", "check_events", "Y") !== "N")
{
	$application->addBackgroundJob(['\Bitrix\Main\Mail\EventManager', 'checkEvents'], [], Main\Application::JOB_PRIORITY_LOW - 1);
}

$healerOfEarlySessionStart = new HealerEarlySessionStart();
$healerOfEarlySessionStart->process($application->getKernelSession());

$kernelSession = $application->getKernelSession();
$kernelSession->start();
$application->getSessionLocalStorageManager()->setUniqueId($kernelSession->getId());

foreach (GetModuleEvents("main", "OnPageStart", true) as $arEvent)
{
	ExecuteModuleEventEx($arEvent);
}

//define global user object
$GLOBALS["USER"] = new CUser;

//session control from group policy
$arPolicy = $GLOBALS["USER"]->GetSecurityPolicy();
$currTime = time();
if (
	(
		//IP address changed
		$kernelSession['SESS_IP']
		&& $arPolicy["SESSION_IP_MASK"] != ''
		&& (
			(ip2long($arPolicy["SESSION_IP_MASK"]) & ip2long($kernelSession['SESS_IP']))
			!=
			(ip2long($arPolicy["SESSION_IP_MASK"]) & ip2long($_SERVER['REMOTE_ADDR']))
		)
	)
	||
	(
		//session timeout
		$arPolicy["SESSION_TIMEOUT"] > 0
		&& $kernelSession['SESS_TIME'] > 0
		&& ($currTime - $arPolicy["SESSION_TIMEOUT"] * 60) > $kernelSession['SESS_TIME']
	)
	||
	(
		//signed session
		isset($kernelSession["BX_SESSION_SIGN"])
		&& $kernelSession["BX_SESSION_SIGN"] !== bitrix_sess_sign()
	)
	||
	(
		//session manually expired, e.g. in $User->LoginHitByHash
		isSessionExpired()
	)
)
{
	$compositeSessionManager = $application->getCompositeSessionManager();
	$compositeSessionManager->destroy();

	$application->getSession()->setId(Main\Security\Random::getString(32));
	$compositeSessionManager->start();

	$GLOBALS["USER"] = new CUser;
}
$kernelSession['SESS_IP'] = $_SERVER['REMOTE_ADDR'] ?? null;
if (empty($kernelSession['SESS_TIME']))
{
	$kernelSession['SESS_TIME'] = $currTime;
}
elseif (($currTime - $kernelSession['SESS_TIME']) > 60)
{
	$kernelSession['SESS_TIME'] = $currTime;
}
if (!isset($kernelSession["BX_SESSION_SIGN"]))
{
	$kernelSession["BX_SESSION_SIGN"] = bitrix_sess_sign();
}

//session control from security module
if (
	(Option::get("main", "use_session_id_ttl", "N") == "Y")
	&& ((int)Option::get("main", "session_id_ttl", 0) > 0)
	&& !defined("BX_SESSION_ID_CHANGE")
)
{
	if (!isset($kernelSession['SESS_ID_TIME']))
	{
		$kernelSession['SESS_ID_TIME'] = $currTime;
	}
	elseif (($kernelSession['SESS_ID_TIME'] + (int)Option::get("main", "session_id_ttl")) < $kernelSession['SESS_TIME'])
	{
		$compositeSessionManager = $application->getCompositeSessionManager();
		$compositeSessionManager->regenerateId();

		$kernelSession['SESS_ID_TIME'] = $currTime;
	}
}

define("BX_STARTED", true);

if (isset($kernelSession['BX_ADMIN_LOAD_AUTH']))
{
	define('ADMIN_SECTION_LOAD_AUTH', 1);
	unset($kernelSession['BX_ADMIN_LOAD_AUTH']);
}

$formType = null;
$secureForms = false;
$bRsaError = false;
$USER_LID = false;

if (!defined("NOT_CHECK_PERMISSIONS") || NOT_CHECK_PERMISSIONS !== true)
{
	$doLogout = isset($_REQUEST["logout"]) && (strtolower($_REQUEST["logout"]) == "yes");

	if ($doLogout && $GLOBALS["USER"]->IsAuthorized())
	{
		$secureLogout = (Option::get("main", "secure_logout", "N") == "Y");

		if (!$secureLogout || check_bitrix_sessid())
		{
			$GLOBALS["USER"]->Logout();

			//store cookies for next hit (see CMain::GetSpreadCookieHTML())
			$GLOBALS["APPLICATION"]->StoreCookies();

			LocalRedirect($GLOBALS["APPLICATION"]->GetCurPageParam('', ['logout', 'sessid']));
		}
	}

	// authorize by cookies
	if (!$GLOBALS["USER"]->IsAuthorized())
	{
		$GLOBALS["USER"]->LoginByCookies();
	}

	$arAuthResult = false;

	//http basic and digest authorization
	if (($httpAuth = $GLOBALS["USER"]->LoginByHttpAuth()) !== null)
	{
		$arAuthResult = $httpAuth;
		$GLOBALS["APPLICATION"]->SetAuthResult($arAuthResult);
	}

	//Authorize user from authorization html form
	//Only POST is accepted
	if (!empty($_POST["AUTH_FORM"]))
	{
		if (Option::get('main', 'use_encrypted_auth', 'N') == 'Y')
		{
			//possible encrypted user password
			$sec = new CRsaSecurity();
			if (($arKeys = $sec->LoadKeys()))
			{
				$sec->SetKeys($arKeys);
				$errno = $sec->AcceptFromForm(['USER_PASSWORD', 'USER_CONFIRM_PASSWORD', 'USER_CURRENT_PASSWORD']);
				if ($errno == CRsaSecurity::ERROR_SESS_CHECK)
				{
					$arAuthResult = ["MESSAGE" => GetMessage("main_include_decode_pass_sess"), "TYPE" => "ERROR"];
				}
				elseif ($errno < 0)
				{
					$arAuthResult = ["MESSAGE" => GetMessage("main_include_decode_pass_err", ["#ERRCODE#" => $errno]), "TYPE" => "ERROR"];
				}

				if ($errno < 0)
				{
					$bRsaError = true;
				}
			}
		}

		if (!$bRsaError)
		{
			if (!defined("ADMIN_SECTION") || ADMIN_SECTION !== true)
			{
				$USER_LID = SITE_ID;
			}

			$formType = $_POST["TYPE"] ?? null;

			if (!empty($formType))
			{
				$secureForms = Option::get("main", "secure_auth_forms", "N") != "Y" || check_bitrix_sessid();

				if ($secureForms)
				{
					if ($formType == "AUTH")
					{
						$arAuthResult = $GLOBALS["USER"]->Login(
							$_POST["USER_LOGIN"] ?? '',
							$_POST["USER_PASSWORD"] ?? '',
							$_POST["USER_REMEMBER"] ?? ''
						);
					}
					elseif ($formType == "OTP")
					{
						$arAuthResult = $GLOBALS["USER"]->LoginByOtp(
							$_POST["USER_OTP"] ?? '',
							$_POST["OTP_REMEMBER"] ?? '',
							$_POST["captcha_word"] ?? '',
							$_POST["captcha_sid"] ?? ''
						);
					}
					elseif ($formType == "SEND_PWD")
					{
						$arAuthResult = CUser::SendPassword(
							$_POST["USER_LOGIN"] ?? '',
							$_POST["USER_EMAIL"] ?? '',
							$USER_LID,
							$_POST["captcha_word"] ?? '',
							$_POST["captcha_sid"] ?? '',
							$_POST["USER_PHONE_NUMBER"] ?? ''
						);
					}
					elseif ($formType == "CHANGE_PWD")
					{
						$arAuthResult = $GLOBALS["USER"]->ChangePassword(
							$_POST["USER_LOGIN"] ?? '',
							$_POST["USER_CHECKWORD"] ?? '',
							$_POST["USER_PASSWORD"] ?? '',
							$_POST["USER_CONFIRM_PASSWORD"] ?? '',
							$USER_LID,
							$_POST["captcha_word"] ?? '',
							$_POST["captcha_sid"] ?? '',
							true,
							$_POST["USER_PHONE_NUMBER"] ?? '',
							$_POST["USER_CURRENT_PASSWORD"] ?? ''
						);
					}
				}

				if ($formType == "AUTH" || $formType == "OTP")
				{
					//special login form in the control panel
					if ($arAuthResult === true && defined('ADMIN_SECTION') && ADMIN_SECTION === true)
					{
						//store cookies for next hit (see CMain::GetSpreadCookieHTML())
						$GLOBALS["APPLICATION"]->StoreCookies();
						$kernelSession['BX_ADMIN_LOAD_AUTH'] = true;

						// die() follows
						CMain::FinalActions('<script>window.onload=function(){(window.BX || window.parent.BX).AUTHAGENT.setAuthResult(false);};</script>');
					}
				}
			}
		}
		$GLOBALS["APPLICATION"]->SetAuthResult($arAuthResult);
	}
	elseif (!$GLOBALS["USER"]->IsAuthorized() && isset($_REQUEST['bx_hit_hash']))
	{
		//Authorize by unique URL
		$GLOBALS["USER"]->LoginHitByHash($_REQUEST['bx_hit_hash']);
	}
}

//logout or re-authorize the user if something importand has changed
$GLOBALS["USER"]->CheckAuthActions();

//magic short URI
if (defined("BX_CHECK_SHORT_URI") && BX_CHECK_SHORT_URI && CBXShortUri::CheckUri())
{
	//local redirect inside
	die();
}

//application password scope control
if (($applicationID = $GLOBALS["USER"]->getContext()->getApplicationId()) !== null)
{
	$appManager = Main\Authentication\ApplicationManager::getInstance();
	if ($appManager->checkScope($applicationID) !== true)
	{
		$event = new Main\Event("main", "onApplicationScopeError", ['APPLICATION_ID' => $applicationID]);
		$event->send();

		$context->getResponse()->setStatus("403 Forbidden");
		$application->end();
	}
}

//define the site template
if (!defined("ADMIN_SECTION") || ADMIN_SECTION !== true)
{
	$siteTemplate = "";
	if (!empty($_REQUEST["bitrix_preview_site_template"]) && is_string($_REQUEST["bitrix_preview_site_template"]) && $GLOBALS["USER"]->CanDoOperation('view_other_settings'))
	{
		//preview of site template
		$signer = new Main\Security\Sign\Signer();
		try
		{
			//protected by a sign
			$requestTemplate = $signer->unsign($_REQUEST["bitrix_preview_site_template"], "template_preview".bitrix_sessid());

			$aTemplates = CSiteTemplate::GetByID($requestTemplate);
			if ($template = $aTemplates->Fetch())
			{
				$siteTemplate = $template["ID"];

				//preview of unsaved template
				if (isset($_GET['bx_template_preview_mode']) && $_GET['bx_template_preview_mode'] == 'Y' && $GLOBALS["USER"]->CanDoOperation('edit_other_settings'))
				{
					define("SITE_TEMPLATE_PREVIEW_MODE", true);
				}
			}
		}
		catch (Main\Security\Sign\BadSignatureException)
		{
		}
	}
	if ($siteTemplate == "")
	{
		$siteTemplate = CSite::GetCurTemplate();
	}

	if (!defined('SITE_TEMPLATE_ID'))
	{
		define("SITE_TEMPLATE_ID", $siteTemplate);
	}

	if (!defined('SITE_TEMPLATE_PATH'))
	{
		define("SITE_TEMPLATE_PATH", getLocalPath('templates/'.SITE_TEMPLATE_ID, BX_PERSONAL_ROOT));
	}
}
else
{
	// prevents undefined constants
	if (!defined('SITE_TEMPLATE_ID'))
	{
		define('SITE_TEMPLATE_ID', '.default');
	}

	define('SITE_TEMPLATE_PATH', '/bitrix/templates/.default');
}

//magic parameters: show page creation time
if (isset($_GET["show_page_exec_time"]))
{
	if ($_GET["show_page_exec_time"] == "Y" || $_GET["show_page_exec_time"] == "N")
	{
		$kernelSession["SESS_SHOW_TIME_EXEC"] = $_GET["show_page_exec_time"];
	}
}

//magic parameters: show included file processing time
if (isset($_GET["show_include_exec_time"]))
{
	if ($_GET["show_include_exec_time"] == "Y" || $_GET["show_include_exec_time"] == "N")
	{
		$kernelSession["SESS_SHOW_INCLUDE_TIME_EXEC"] = $_GET["show_include_exec_time"];
	}
}

//magic parameters: show include areas
if (!empty($_GET["bitrix_include_areas"]))
{
	$GLOBALS["APPLICATION"]->SetShowIncludeAreas($_GET["bitrix_include_areas"]=="Y");
}

//magic sound
if ($GLOBALS["USER"]->IsAuthorized())
{
	$cookie_prefix = Option::get('main', 'cookie_name', 'BITRIX_SM');
	if (!isset($_COOKIE[$cookie_prefix.'_SOUND_LOGIN_PLAYED']))
	{
		$GLOBALS["APPLICATION"]->set_cookie('SOUND_LOGIN_PLAYED', 'Y', 0);
	}
}

//magic cache
Main\Composite\Engine::shouldBeEnabled();

// should be before proactive filter on OnBeforeProlog
$userPassword = $_POST["USER_PASSWORD"] ?? null;
$userConfirmPassword = $_POST["USER_CONFIRM_PASSWORD"] ?? null;

foreach(GetModuleEvents("main", "OnBeforeProlog", true) as $arEvent)
{
	ExecuteModuleEventEx($arEvent);
}

// need to reinit
$GLOBALS["APPLICATION"]->SetCurPage(false);

if (!defined("NOT_CHECK_PERMISSIONS") || NOT_CHECK_PERMISSIONS !== true)
{
	//Register user from authorization html form
	//Only POST is accepted
	if (!empty($_POST["AUTH_FORM"]) && $formType == "REGISTRATION")
	{
		if (!$bRsaError && $secureForms)
		{
			if (Option::get("main", "new_user_registration", "N") == "Y" && (!defined("ADMIN_SECTION") || ADMIN_SECTION !== true))
			{
				$arAuthResult = $GLOBALS["USER"]->Register(
					$_POST["USER_LOGIN"] ?? '',
					$_POST["USER_NAME"] ?? '',
					$_POST["USER_LAST_NAME"] ?? '',
					$userPassword,
					$userConfirmPassword,
					$_POST["USER_EMAIL"] ?? '',
					$USER_LID,
					$_POST["captcha_word"] ?? '',
					$_POST["captcha_sid"] ?? '',
					false,
					$_POST["USER_PHONE_NUMBER"] ?? ''
				);

				$GLOBALS["APPLICATION"]->SetAuthResult($arAuthResult);
			}
		}
	}
}

if ((!defined("NOT_CHECK_PERMISSIONS") || NOT_CHECK_PERMISSIONS !== true) && (!defined("NOT_CHECK_FILE_PERMISSIONS") || NOT_CHECK_FILE_PERMISSIONS !== true))
{
	$real_path = $context->getRequest()->getScriptFile();

	if (!$GLOBALS["USER"]->CanDoFileOperation('fm_view_file', [SITE_ID, $real_path]) || (defined("NEED_AUTH") && NEED_AUTH && !$GLOBALS["USER"]->IsAuthorized()))
	{
		if ($GLOBALS["USER"]->IsAuthorized() && empty($arAuthResult["MESSAGE"]))
		{
			$arAuthResult = ["MESSAGE" => GetMessage("ACCESS_DENIED").' '.GetMessage("ACCESS_DENIED_FILE", ["#FILE#" => $real_path]), "TYPE" => "ERROR"];

			if (Option::get("main", "event_log_permissions_fail", "N") === "Y")
			{
				CEventLog::Log(CEventLog::SEVERITY_SECURITY, "USER_PERMISSIONS_FAIL", "main", $GLOBALS["USER"]->GetID(), $real_path);
			}
		}

		if (defined("ADMIN_SECTION") && ADMIN_SECTION === true)
		{
			if (isset($_REQUEST["mode"]) && ($_REQUEST["mode"] === "list" || $_REQUEST["mode"] === "settings"))
			{
				echo "<script>top.location='".$GLOBALS["APPLICATION"]->GetCurPage()."?".DeleteParam(["mode"])."';</script>";
				die();
			}
			elseif (isset($_REQUEST["mode"]) && $_REQUEST["mode"] === "frame")
			{
				echo "<script>
					const w = (opener? opener.window:parent.window);
					w.location.href='" .$GLOBALS["APPLICATION"]->GetCurPage()."?".DeleteParam(["mode"])."';
				</script>";
				die();
			}
			elseif (defined("MOBILE_APP_ADMIN") && MOBILE_APP_ADMIN === true)
			{
				echo json_encode(["status" => "failed"]);
				die();
			}
		}

		/** @noinspection PhpUndefinedVariableInspection */
		$GLOBALS["APPLICATION"]->AuthForm($arAuthResult);
	}
}

/*ZDUyZmZNzVhYjA3N2QxM2JjY2IyMTk1NWQ3YzljYjM1ZjE0MWY=*/$GLOBALS['____1519397012']= array(base64_decode('bXRfc'.'mFuZA=='),base64_decode('Y2FsbF91c2VyX2'.'Z'.'1bmM='),base64_decode('c3RycG9z'),base64_decode('ZX'.'h'.'wbG'.'9'.'kZQ=='),base64_decode('cGFjaw='.'='),base64_decode('bWQ1'),base64_decode('Y2'.'9uc'.'3Rh'.'bnQ='),base64_decode('aGFzaF'.'9ob'.'W'.'Fj'),base64_decode(''.'c3RyY'.'21'.'w'),base64_decode('Y2F'.'s'.'bF91c2VyX2Z1bm'.'M'.'='),base64_decode('Y2FsbF'.'91c2'.'VyX'.'2'.'Z1bmM'.'='),base64_decode('aXNfb2JqZWN0'),base64_decode('Y2F'.'s'.'bF91c2VyX2Z1bmM='),base64_decode('Y2FsbF91c2VyX2'.'Z'.'1'.'bmM='),base64_decode(''.'Y2FsbF91c2V'.'y'.'X2Z1bmM='),base64_decode('Y2FsbF91c2VyX2Z1bmM='),base64_decode('Y2Fs'.'bF91'.'c2'.'V'.'y'.'X2Z1bm'.'M'.'='),base64_decode('Y2FsbF9'.'1'.'c2VyX2Z'.'1b'.'mM='),base64_decode('Z'.'G'.'VmaW5lZ'.'A=='),base64_decode('c'.'3RybG'.'Vu'));if(!function_exists(__NAMESPACE__.'\\___1553033087')){function ___1553033087($_565818584){static $_723723693= false; if($_723723693 == false) $_723723693=array('X'.'EN'.'PcHR'.'pb246Okdld'.'E'.'9wdG'.'lvblN0c'.'ml'.'uZw==','bW'.'Fp'.'bg==',''.'flBBUkFNX0'.'1BW'.'F9VU0VSUw='.'=','Lg==',''.'Lg==','S'.'Co'.'=','Y'.'ml0'.'cml4','T'.'ElDRU'.'5TR'.'V9LRV'.'k=','c2hh'.'Mj'.'U2','XENPcHRpb246Okd'.'ld'.'E9'.'wdGlvb'.'lN0c'.'ml'.'uZ'.'w'.'='.'=','bWFp'.'bg='.'=',''.'U'.'EFSQU1fT'.'UF'.'YX1VTRVJT',''.'X'.'E'.'Jp'.'d'.'HJpe'.'Fx'.'NYWluXENv'.'bmZ'.'pZ1xPcHRpb246'.'On'.'NldA==','b'.'WF'.'p'.'b'.'g==',''.'UEFSQU'.'1f'.'TUFYX1V'.'T'.'RV'.'JT','VVNFUg'.'==','VVNFUg'.'==','VVNFUg==','SXNB'.'dXRob3'.'JpemVk','VVN'.'FU'.'g==','SXNB'.'ZG1pb'.'g==','Q'.'VBQTElDQVRJT04'.'=','U'.'mV'.'zdGFydE'.'J'.'1Zm'.'Zlcg='.'=','T'.'G9j'.'YWxSZWR'.'pcmVjdA='.'=',''.'L2xpY2'.'Vu'.'c'.'2Vfc'.'mV'.'zdHJ'.'pY3Rpb'.'24uc'.'Gh'.'w','XENPcH'.'Rp'.'b2'.'46O'.'kdld'.'E9wdGlvblN'.'0cmluZw==','bWFpbg==','UEFSQU1f'.'TUF'.'Y'.'X1VTRVJT','XEJpd'.'HJpeFx'.'NYWlu'.'XEN'.'vbmZpZ'.'1x'.'PcHRpb24'.'6OnNl'.'dA'.'==','bWFpbg==','U'.'EFSQ'.'U1fTUFYX1'.'VTRV'.'JT','T0xEU0lURUV'.'YU'.'ElSR'.'URBVE'.'U'.'=','ZXh'.'waXJl'.'X21lc3My');return base64_decode($_723723693[$_565818584]);}};if($GLOBALS['____1519397012'][0](round(0+0.25+0.25+0.25+0.25), round(0+4+4+4+4+4)) == round(0+7)){ $_515481345= $GLOBALS['____1519397012'][1](___1553033087(0), ___1553033087(1), ___1553033087(2)); if(!empty($_515481345) && $GLOBALS['____1519397012'][2]($_515481345, ___1553033087(3)) !== false){ list($_1387418254, $_1318104022)= $GLOBALS['____1519397012'][3](___1553033087(4), $_515481345); $_1738177697= $GLOBALS['____1519397012'][4](___1553033087(5), $_1387418254); $_2096624824= ___1553033087(6).$GLOBALS['____1519397012'][5]($GLOBALS['____1519397012'][6](___1553033087(7))); $_462960301= $GLOBALS['____1519397012'][7](___1553033087(8), $_1318104022, $_2096624824, true); if($GLOBALS['____1519397012'][8]($_462960301, $_1738177697) !==(126*2-252)){ if($GLOBALS['____1519397012'][9](___1553033087(9), ___1553033087(10), ___1553033087(11)) != round(0+2.4+2.4+2.4+2.4+2.4)){ $GLOBALS['____1519397012'][10](___1553033087(12), ___1553033087(13), ___1553033087(14), round(0+3+3+3+3));} if(isset($GLOBALS[___1553033087(15)]) && $GLOBALS['____1519397012'][11]($GLOBALS[___1553033087(16)]) && $GLOBALS['____1519397012'][12](array($GLOBALS[___1553033087(17)], ___1553033087(18))) &&!$GLOBALS['____1519397012'][13](array($GLOBALS[___1553033087(19)], ___1553033087(20)))){ $GLOBALS['____1519397012'][14](array($GLOBALS[___1553033087(21)], ___1553033087(22))); $GLOBALS['____1519397012'][15](___1553033087(23), ___1553033087(24), true);}}} else{ if($GLOBALS['____1519397012'][16](___1553033087(25), ___1553033087(26), ___1553033087(27)) != round(0+3+3+3+3)){ $GLOBALS['____1519397012'][17](___1553033087(28), ___1553033087(29), ___1553033087(30), round(0+2.4+2.4+2.4+2.4+2.4));}}} while(!$GLOBALS['____1519397012'][18](___1553033087(31)) || $GLOBALS['____1519397012'][19](OLDSITEEXPIREDATE) <=(165*2-330) || OLDSITEEXPIREDATE != SITEEXPIREDATE)die(GetMessage(___1553033087(32)));/**/       //Do not remove this