<?php

namespace Fedor\DigitalsignAuth\Helpers;

use Bitrix\Main\Localization\Loc;
use CPSignedData;
use CPSigner;
use CPStore;
use Exception;
use Fedor\DigitalsignAuth\Exceptions\CadesException;
use Fedor\DigitalsignAuth\Exceptions\SignatureException;
use Fedor\DigitalsignAuth\Exceptions\SignFileException;
use Fedor\DigitalsignAuth\Helpers\CryptoCertificateHelper as CryptoCertificateHelper;
use Fedor\DigitalsignAuth\Services\EventLogLogger;
use stdClass;

/**
 * Crypto Helper class
 */
class CryptoHelper {
    const SIGN_DETACHED = false;

	/**
	 * Get valid certificates from store
	 * @param string $search - filter by subject name
	 * @param integer $location - https://docs.microsoft.com/ru-ru/windows/win32/seccrypto/capicom-store-location
	 * @param string $store
	 * @return CryptoCertificateHelper[]
	 * @throws CadesException
	 */
    public function GetCertificates($search = '', $location = null, $store = ''): array
    {
		$logger = new EventLogLogger();
		try {
			$store = $this->getStore($location, $store);
			$certs = $store->get_Certificates();

			if ($search) {
				$certs = $certs->Find(CERTIFICATE_FIND_SUBJECT_NAME, $search, 0);
			} else {
				$certs = $certs->Find(CERTIFICATE_FIND_TIME_VALID, date('YmdHis'), 0);
			}

			$certsArr = [];

			for ($i = 1; $i <= $certs->Count(); $i++) {
				$cert = new CryptoCertificateHelper($certs->Item($i));
				$certsArr[] = $cert;
			}

			return $certsArr;
		} catch (Exception $e) {
			$logger->error($e->getMessage(), [
				'ITEM_ID' => self::class,
				'FILE:LINE' => "{$e->getFile()}:{$e->getLine()}"
			]);
			throw new CadesException($e->getMessage());
		}
    }

	/**
	 * Sign string data type by chosen certificate
	 *
	 * @param CryptoCertificateHelper $certificate
	 * @param string $data
	 * @param boolean $toBase64
	 * @return boolean
	 * @throws SignatureException
	 */
    public function Sign($certificate, $data, bool $toBase64 = true): string
    {
		$logger = new EventLogLogger();
	    try {
	        $signer = new CPSigner();
	        $signer->set_Certificate($certificate->GetCertificate());
	        $signer->set_Options(CERTIFICATE_INCLUDE_WHOLE_CHAIN);

	        if ($certificate->GetPin()) {
	            $signer->set_KeyPin($certificate->GetPin());
	        }

	        $signedData = new CPSignedData();
	        $signedData->set_ContentEncoding(BASE64_TO_BINARY);
	        $signedData->set_Content($toBase64 ? base64_encode($data) :  $data);

            $signedMessage = $signedData->SignCades($signer, CADES_BES, self::SIGN_DETACHED, ENCODE_BASE64);
            return $signedMessage;
        } catch (Exception $e) {
		    $logger->error($e->getMessage(), [
			    'ITEM_ID' => self::class,
			    'FILE:LINE' => "{$e->getFile()}:{$e->getLine()}"
		    ]);
            throw new SignatureException($e->getMessage());
        }
    }

	/**
	 * Sign file by chosen certificate
	 *
	 * @param CryptoCertificateHelper $certificate
	 * @param string $dataFilePath
	 * @param string $signFilePath
	 * @return string
	 * @throws SignatureException|SignFileException
	 */
    public function SignFile($certificate, $dataFilePath, $signFilePath = null): string
    {
		if (!file_exists($dataFilePath)) {
			throw new SignFileException(Loc::getMessage('FEDOR_CRYPTO_HELPER_NO_FILE_ERROR'));
		}

		if (!is_writable($signFilePath)) {
			throw new SignFileException(Loc::getMessage('FEDOR_CRYPTO_HELPER_NO_FILE_TO_SIGN_ERROR'));
		}

        $data = file_get_contents($dataFilePath);
        $sign = $this->Sign($certificate, $data, true);
		file_put_contents($signFilePath, $sign);

        return $sign;
    }

	/**
	 * Verify data sign
	 *
	 * @param string $data
	 * @param string $sign
	 * @param int $signType
	 * @param boolean $toBase64
	 * @return array
	 */
    public function Verify(string $data, string $sign, $signType = CADES_BES , bool $toBase64 = true): array
    {
		$logger = new EventLogLogger();
        $signedData = new CPSignedData();
        $signedData->set_ContentEncoding(BASE64_TO_BINARY);
        $signedData->set_Content($toBase64 ? base64_encode($data) : $data);
        $sign = trim(str_replace(["\r", "\n"], '', $sign));

        try {
            $signedData->VerifyCades($sign, $signType, self::SIGN_DETACHED);
            $signers = $signedData->get_Signers();
            $count = $signers->get_Count();
            $signs = [];

            for ($i = 1; $i <= $count; $i += 1) {
                $signer = $signers->get_Item($i);
                $cert = $signer->get_Certificate();

                $signs[] = (object)[
                    'ts' => $signer->get_SigningTime(),
                    'cert' => new CryptoCertificateHelper($cert)
                ];
            }

            return $signs;
        } catch (Exception $e) {
	        $logger->error($e->getMessage(), [
		        'ITEM_ID' => self::class,
		        'FILE:LINE' => "{$e->getFile()}:{$e->getLine()}"
	        ]);
            return [
                'status' => 'error',
                'errorMessage' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify file sign
     *
     * @param string $dataFilePath
     * @param string $signFilePath
     * @return array
     */
    public function VerifyFile($dataFilePath, $signFilePath): array
    {
        $data = file_get_contents($dataFilePath);
        $sign = file_get_contents($signFilePath);
        return $this->Verify($data, $sign, true);
    }

	/**
	 * Create and open store
	 *
	 * @param integer $location
	 * @param string $name
	 * @param integer $mode
	 * @return CPStore
	 * @throws CadesException
	 */
    private function getStore($location = null, $name = null, $mode = null): CPStore
    {
		$logger = new EventLogLogger();
	    try {
		    $location = $location ?: CURRENT_USER_STORE;
		    $name = $name ?: 'My';
		    $mode = $mode ?: STORE_OPEN_READ_ONLY;

		    $store = new CPStore();
		    $store->Open($location, $name, $mode);
		    return $store;
	    } catch (Exception $e) {
		    $logger->error($e->getMessage(), [
			    'ITEM_ID' => self::class,
			    'FILE:LINE' => "{$e->getFile()}:{$e->getLine()}"
		    ]);
		    throw new CadesException($e->getMessage());
	    }
    }
}