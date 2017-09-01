<?php
Class Lib_kargo extends EA_base
{
    public $session;
    //测试环境参数------------------------------------
//     //protected $_apiurl= 'https://uat.digital.kargocard.com:8443/DigitalREST/api/ver1.1/order';
//     protected $_apiurl= 'https://uat.digital.kargotest.com:8443/DigitalREST/api/ver1.1/order';
//     protected $_authid= 'mer-123456';
//     protected $_secret= 'kargocard';
//     protected $_merid= '00062000000';
    
//     //AES算法 uat.digital.kargocard.com环境
//     protected $_tokenurl= 'https://uat.digital.kargocard.com/CHolder/control/token';
//     protected $_key= 'KARGOANDEGIFTING';
//     protected $_iv= 'FHAE5908SAGAG8AC';

    //获取openid方面，需要调整以下参数：跳回url 和appid：wx5f969321cf58a9d5/ 32e64d96956c200d19524698c3f59bc6
    //http://uat.digital.kargotest.com/iwidemall?calbak=
    
    
    //生产环境参数--------------------------------------
//     protected $_apiurl= 'https://digital.kargocard.com/DigitalREST/api/ver1.1/order';
//     protected $_authid= 'merchant-DigitalRest';
//     protected $_secret= 'K@rg0K@rd';
//     protected $_merid= '98621000129';

    protected $_tokenurl= 'https://mycard.kargocard.com/CHolder/control/token';
    protected $_key= 'KARGOANDEGIFTINB';
    protected $_iv= 'FHAE5908SAGAG8AB';

    //获取openid方面，需要调整以下参数：跳回url 和appid：wx992e5c06624b1a6e/ 901647c10c10a2b8a0487324d8794706
    //http://mycard.kargocard.com/iwidemall?calbak=
    
    
	public function __construct()
	{
	     return parent::__construct();
	}

	public static function inst($className=__CLASS__)
	{
		return parent::inst($className);
	}

	protected function _write_log( $content, $type='response' )
	{
	    $dir= dirname(__FILE__). DS. 'logs';
	    if( !file_exists($dir) ) mkdir($dir, 0777, TRUE);
	     
	    $tmpfile= $dir. DS. get_class($this). '_'. date('Y-m-d'). '.log';
	    //echo $tmpfile;die;
	    $fp = fopen( $tmpfile, 'a');
	     
	    $content= str_repeat('-', 40). "\n[". $type. ' : '. date('Y-m-d H:i:s'). ']'
	       ."\n". $content. "\n";
	    fwrite($fp, $content);
	    fclose($fp);
	}
	
	protected function _request($message, $endpoint= '/api/ver1.1/order', $verb= 'POST')
	{
        $this->_write_log($message, 'request');
	    
	    $rtime= time()* 1000;//time();
	    $to_sign= $verb. "\n". strtoupper( md5($message) ). "\n". $rtime. "\n". $endpoint;
	    $sign= hash_hmac( "sha256", utf8_encode( $to_sign ), utf8_encode( $this->_secret ) );
	    $result= "kc-". $this->_authid. ":". strtoupper($sign);
	     
	    $crl = curl_init();
	    $headr = array();
	    $headr[] = 'Authorization:'. $result;
	    $headr[] = 'kc-utc-date:'. $rtime;
	    $headr[] = 'Content-Type: application/json;charset=UTF-8';
	    //print_r($headr);die;
	    curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);
	    
	    curl_setopt($crl, CURLOPT_URL, $this->_apiurl);
	    curl_setopt($crl, CURLOPT_POSTFIELDS, $message);
	    curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($crl, CURLOPT_POST, true);
	     
	    curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false );
	    curl_setopt($crl, CURLOPT_SSL_VERIFYHOST, false );
	    
	    $rest = curl_exec($crl);
        $this->_write_log($rest, 'response');
        
	    curl_close($crl);
	    return json_decode($rest);
	}
	
	public function order_create($order, $items, $payment=TRUE )
	{
	    die;
	    if( $order && $items ){
	        $ord_sender= array(
    	        "sender-id"=> $order['openid'],
    	    );
    	    $ord_receiver= array(
    	        "receiver-id"=> "",
    	    );
    	    $ord_items= array();
	        foreach ( $items as $k=> $v) {
	            if( isset($ord_items[$v['gs_id']]) ) $ord_items[$v['gs_id']]['qty']++;
	            else $ord_items[$v['gs_id']]= array(
	                "qty"=> 1,
	                "face-value"=> $v['market_price'],
	                "price"=> $v['price'],
	                "upc"=> $v['sku'],
// "upc"=> "8862100000060",
// "face-value"=> 100,
// "price"=> 100,
	            );
	        }
	        $message= json_encode( array(
	            "get-ord-details"=> FALSE,
	            "mer-id"=> $this->_merid,
	            "mer-ord-id"=> $order['out_trade_no'],
// "mer-ord-id"=> "20160226000898",
	            "ord-create-date"=> date("YmdHis"),
	            "ord-delivery-date"=> date("YmdHis"),
	            "ord-sender"=> $ord_sender,
	            "ord-receiver"=> $ord_receiver,
	            "ord-item"=> array_values( $ord_items ),
	            "ord-pymt-rcvd"=> $payment,
	            "ord-pymt-details"=> $order['transaction_id'],
	        ));
	        //print_R($message);die;
	        
	        $result= (array) $this->_request($message);
	        //print_r($result);
	        
	        /**
	         * stdClass Object ( 
	         * [kc-response-code] => 00000 
	         * [kc-response-message] => success 
	         * [mer-ord-id] => 20160226000893 
	         * [kc-ord-id] => 1173502 
	         * [kc-ord-key] => I2DVAKOS6FZYUSFO 
	         * [kc-request-status] => rcvd )
	         * **/
	        if( $result && isset($result['kc-response-code']) && $result['kc-response-code']== '00000'){
	            //$kc_ord_id= $result['kc-ord-id'];
	            return $result;
	        }
	        
	    } else {
	        return '';
	    }
	}

	public function order_datail($order )
	{
	    die;
        if( $order && isset($order['out_order_id']) && $order['out_order_id'] ){
	        $message= json_encode(array(
	            "mer-id"=> $this->_merid,
	            "mer-ord-id"=> $order['out_trade_no'],
	            "kc-ord-id"=> $order['out_order_id'],
// "mer-ord-id"=> '20160226000898',
// "kc-ord-id"=> '1173512',
	            "get-ord-details"=> TRUE,
	            "include-code-details"=> TRUE,
	        ));
            //print_R($message);die;
        
            $result= (array) $this->_request($message);
            //print_r($result);die;
        
            /**
             * Array (
    [kc-response-code] => 00000
    [kc-response-message] => success
    [mer-ord-id] => 20160226000893
    [kc-ord-id] => 1173502
    [kc-request-status] => OSM_SHIPPED
    [ord-item] => Array (
            [0] => stdClass Object (
                    [upc] => 8862100000060
                    [code] => Array (
                            [0] => stdClass Object (
                                    [card-no-encr] => UY55FHAAYEY3UM79D2507CB034161EE51A3C6BB6CD5DAC24845020D41F99AF93743B01ABA51F7328
                                    [card-first6] => 888888
                                    [card-last4] => 0968
                                    [code-encr] => GNHSV163VG78N0GE55F65B8E29F308BB77C2DA4871F6CC42
                                    [redemption-attribute] => 
                                )
                        )
                    [qty] => 1
                    [face-value] => 100.00
                    [display-data] => Array (
                            [0] => 
                        )
                )
        )
)
            * **/
            if( $result && isset($result['kc-response-code']) && $result['kc-response-code']== '00000'){
                $ord_item= (array) $result['ord-item'];
                $cards= $this->_parse_cardno($ord_item, $order['out_order_key'] );
                $order['cards']= $cards;
                return $order;
                
            } else {
                return FALSE;
            }
        
        } else {
            return FALSE;
        }
	}

	/**
	 *  Array (
        [upc] => 8862100000060
        [code] => Array (
            [0] => stdClass Object (
                [card-no-encr] => Q33F7Y2L73QMGKCGD397AABA7B701ACC0DBCDA1B9D54E77AA32B59B58A01A1EE359C974F52D979FB
                [card-first6] => 888888
                [card-last4] => 0976
                [code-encr] => TRXRLVTSWTQIWRHW363428D15597392CB91E203C627661EC
                [redemption-attribute] => 
            )
        )
        [qty] => 1
        [face-value] => 100.00
        [display-data] => Array (
            [0] => 
        )
    )
	 */
	public function _parse_cardno($ord_item, $key)
	{
	    $return_cards= array();
	    foreach ($ord_item as $k=> $v){
            $code_tmp= (array) $v;
            
            foreach ($code_tmp['code'] as $sk=> $sv){
                $code_tmp2 = (array) $sv;
                
                if(isset($code_tmp2['card-no-encr']) && $code_tmp2['card-no-encr']){
                    $card_no= $this->decrytion( $code_tmp2['card-no-encr'], $key);
                    $return_cards[$code_tmp['upc']][$sk]['card_no']= $card_no;
                }
                if(isset($code_tmp2['code-encr']) && $code_tmp2['code-encr']){
                    $code= $this->decrytion( $code_tmp2['code-encr'], $key);
                    $return_cards[$code_tmp['upc']][$sk]['code']= $code;
                }
            }
	    }
	    //print_r($return_item);die;
	    return $return_cards;
	}
	
    /**
     * 根据加密后的字符串和key解密卡号
     * @param String $data  卡购系统加密后的加密串
     * @param String $key
     * @return string
     */
	public function decrytion($data, $key)
	{
	    $iv = mb_substr($data, 0, 16, 'utf8');
	    $data = mb_substr($data, 16, mb_strlen($data, 'utf8'), 'utf8');
	    $td = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, hex2bin( $data ), MCRYPT_MODE_CBC, $iv );
	    return trim($td);
	}
    /**
     * 根据vi和key加密卡号
     * @param String $data
     * @param String $key
     * @return string
     */
	public function encrytion($data, $key=NULL, $iv=NULL)
	{
	    if($key==NULL) $key= $this->_key;
	    if($iv==NULL) $iv= $this->_iv; 
	    // 是卡购系统加密时插入的不明字符串
	    $td = bin2hex( mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, trim($data). "", MCRYPT_MODE_CBC, $iv ) );
	    return trim($td);
	}

	public function get_token_api_url()
	{
	    return $this->_tokenurl;
	}
	
}










/**
 * 以下内容为 AES加密参考类，与上面功能无关。
 */

class AESMcrypt {
    public $iv = null;
    public $key = null;
    public $bit = 128;
    private $cipher;
    public function __construct($bit, $key, $iv, $mode) {
        if(empty($bit) || empty($key) || empty($iv) || empty($mode))
            return NULL;
        $this->bit = $bit;
        $this->key = $key;
        $this->iv = $iv;
        $this->mode = $mode;
        switch($this->bit) {
            case 192:$this->cipher = MCRYPT_RIJNDAEL_192; break;
            case 256:$this->cipher = MCRYPT_RIJNDAEL_256; break;
            default: $this->cipher = MCRYPT_RIJNDAEL_128;
        }
        switch($this->mode) {
            case 'ecb':$this->mode = MCRYPT_MODE_ECB; break;
            case 'cfb':$this->mode = MCRYPT_MODE_CFB; break;
            case 'ofb':$this->mode = MCRYPT_MODE_OFB; break;
            case 'nofb':$this->mode = MCRYPT_MODE_NOFB; break;
            default: $this->mode = MCRYPT_MODE_CBC;
        }
    }
    public function encrypt($data) {
        $data = base64_encode(mcrypt_encrypt( $this->cipher, $this->key, $data, $this->mode, $this->iv));
        return $data;
    }
    public function decrypt($data) {
        $data = mcrypt_decrypt( $this->cipher, $this->key, base64_decode($data), $this->mode, $this->iv);
        $data = rtrim(rtrim($data), "..");
        return $data;
    }
}
//使用方法
// $aes = new AESMcrypt($bit = 128, $key = 'abcdef1234567890', $iv = '0987654321fedcba', $mode = 'cbc');
// $c = $aes->encrypt('haowei.me');
// var_dump($aes->decrypt($c));

// 例子、附一个可加密可解密类
// 复制代码 代码如下:

/**
 * AES加密、解密类
* @author hushangming
*
* 用法：
* <pre>
* // 实例化类
* // 参数$_bit：格式，支持256、192、128，默认为128字节的
* // 参数$_type：加密/解密方式，支持cfb、cbc、nofb、ofb、stream、ecb，默认为ecb
* // 参数$_key：密钥，默认为abcdefghijuklmno
* $tcaes = new TCAES();
* $string = 'laohu';
* // 加密
* $encodeString = $tcaes->encode($string);
* // 解密
* $decodeString = $tcaes->decode($encodeString);
* </pre>
*/
class TCAES{
    private $_bit = MCRYPT_RIJNDAEL_256;
    private $_type = MCRYPT_MODE_CBC;
    //private $_key = 'abcdefghijuklmno0123456789012345';
    private $_key = 'abcdefghijuklmno'; // 密钥
    private $_use_base64 = true;
    private $_iv_size = null;
    private $_iv = null;

    /**
     * @param string $_key 密钥
     * @param int $_bit 默认使用128字节
     * @param string $_type 加密解密方式
     * @param boolean $_use_base64 默认使用base64二次加密
     */
    public function __construct($_key = '', $_bit = 128, $_type = 'ecb', $_use_base64 = true){
        // 加密字节
        if(192 === $_bit){
            $this->_bit = MCRYPT_RIJNDAEL_192;
        }elseif(128 === $_bit){
            $this->_bit = MCRYPT_RIJNDAEL_128;
        }else{
            $this->_bit = MCRYPT_RIJNDAEL_256;
        }
        // 加密方法
        if('cfb' === $_type){
            $this->_type = MCRYPT_MODE_CFB;
        }elseif('cbc' === $_type){
            $this->_type = MCRYPT_MODE_CBC;
        }elseif('nofb' === $_type){
            $this->_type = MCRYPT_MODE_NOFB;
        }elseif('ofb' === $_type){
            $this->_type = MCRYPT_MODE_OFB;
        }elseif('stream' === $_type){
            $this->_type = MCRYPT_MODE_STREAM;
        }else{
            $this->_type = MCRYPT_MODE_ECB;
        }
        // 密钥
        if(!empty($_key)){
            $this->_key = $_key;
        }
        // 是否使用base64
        $this->_use_base64 = $_use_base64;

        $this->_iv_size = mcrypt_get_iv_size($this->_bit, $this->_type);
        $this->_iv = mcrypt_create_iv($this->_iv_size, MCRYPT_RAND);
    }

    /**
     * 加密
     * @param string $string 待加密字符串
     * @return string
     */
    public function encode($string){
        if(MCRYPT_MODE_ECB === $this->_type){
            $encodeString = mcrypt_encrypt($this->_bit, $this->_key, $string, $this->_type);
        }else{
            $encodeString = mcrypt_encrypt($this->_bit, $this->_key, $string, $this->_type, $this->_iv);
        }
        if($this->_use_base64)
            $encodeString = base64_encode($encodeString);
        return $encodeString;
    }

    /**
     * 解密
     * @param string $string 待解密字符串
     * @return string
     */
    public function decode($string){
        if($this->_use_base64)
            $string = base64_decode($string);

        $string = $this->toHexString($string);
        if(MCRYPT_MODE_ECB === $this->_type){
            $decodeString = mcrypt_decrypt($this->_bit, $this->_key, $string, $this->_type);
        }else{
            $decodeString = mcrypt_decrypt($this->_bit, $this->_key, $string, $this->_type, $this->_iv);
        }
        return $decodeString;
    }

    /**
     * 将$string转换成十六进制
     * @param string $string
     * @return stream
     */
    private function toHexString ($string){
        $buf = "";
        for ($i = 0; $i < strlen($string); $i++){
            $val = dechex(ord($string{$i}));
            if(strlen($val)< 2)
                $val = "0".$val;
            $buf .= $val;
        }
        return $buf;
    }

    /**
     * 将十六进制流$string转换成字符串
     * @param stream $string
     * @return string
     */
    private function fromHexString($string){
        $buf = "";
        for($i = 0; $i < strlen($string); $i += 2){
            $val = chr(hexdec(substr($string, $i, 2)));
            $buf .= $val;
        }
        return $buf;
    }
}