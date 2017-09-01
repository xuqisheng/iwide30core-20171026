<?php
class Wxapp_decrypt{
	
 function decryptData( $encryptedData,$sessionKey,$iv,$appid='' ){
// 		if (strlen($sessionKey) != 24) {
// 			return ErrorCode::$IllegalAesKey;
// 		}
		$aesKey=base64_decode($sessionKey);

        
// 		if (strlen($iv) != 24) {
// 			return ErrorCode::$IllegalIv;
// 		}
		$aesIV=base64_decode($iv);

		$aesCipher=base64_decode($encryptedData);

		$result = $this->decrypt($aesCipher,$aesIV,$aesKey);
        
		if ($result[0] != 0) {
			return $result[0];
		}
     
        $dataObj=json_decode( $result[1] );
        if( $dataObj  == NULL )
        {
            return FALSE;
        }
//         if( $dataObj->watermark->appid != $appid )
//         {
//             return ErrorCode::$IllegalBuffer;
//         }
		return $result[1];
	}
    
  function decrypt( $aesCipher, $aesIV ,$aesKey)
	{

		try {
			
			$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
			
			mcrypt_generic_init($module, $aesKey, $aesIV);

			//解密
			$decrypted = mdecrypt_generic($module, $aesCipher);
			mcrypt_generic_deinit($module);
			mcrypt_module_close($module);
		} catch (Exception $e) {
			return FALSE;
		}


		try {
			//去除补位字符
			$result = $this->decode($decrypted);

		} catch (Exception $e) {
			//print $e;
			return FALSE;
		}
		return array(0, $result);
	}
    function decode($text)
	{

		$pad = ord(substr($text, -1));
		if ($pad < 1 || $pad > 32) {
			$pad = 0;
		}
		return substr($text, 0, (strlen($text) - $pad));
	}	
}