<?php

class Encrypt {
    
    private $key = "I9sgh-73j2g-lsghm-cHh6l_";

    public function __construct($key=NULL)
    {
        if( $key!==NULL || strlen($key) > 24 ) {
            echo 'key is not valid';
            exit();
        }
    }
    //加密
    public function encrypt($value)
    {
        $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');
        mcrypt_enc_get_block_size($td);
        $value = $this->PaddingPKCS7($value);
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $this->key, $iv);
        $ret = base64_encode(mcrypt_generic($td, $value));
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $ret;
    }

    //解密
    public function decrypt($value)
    {
        $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $this->key, $iv);
        $ret = trim(mdecrypt_generic($td, base64_decode($value)));
        $ret = $this->UnPaddingPKCS7($ret);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $ret;
    }

    private function PaddingPKCS7($data)
    {
        if(is_array($data)) {
            $data = json_encode($data);
        }

        $block_size = mcrypt_get_block_size('tripledes', MCRYPT_MODE_ECB);
        $padding_char = $block_size - (strlen($data) % $block_size);
        $data .= str_repeat(chr($padding_char), $padding_char);
        return $data;
    }

    private function UnPaddingPKCS7($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if($pad > strlen($text)) {
            return false;
        }
        if(strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }
    
    
}
