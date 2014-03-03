<?php
namespace Rosio\EncryptedCookie\CryptoSystem;

class AES_SHA1 extends AES_SHA implements iCryptoSystem
{
	const IV_SIZE = 32;
	
	protected function getHMAC ($encryptedData, $aTime, $expiration, $tid, $iv)
	{
		return hash_hmac('sha1', base64_encode($encryptedData) . base64_encode($aTime) . base64_encode($expiration) . base64_encode($tid) . base64_encode($iv), $this->HMACKey, true);
	}
}