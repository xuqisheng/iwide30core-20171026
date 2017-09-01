<?php 
class Yzsms extends CI_Model
{
	const URL = 'http://60.191.133.2:8080/ema/httpSms/sendSms';
	
	public function sendSms($phone,$number)
	{
		$xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><com.ctc.ema.server.jwsserver.sms.MtMessage><content>'.$number.'（会员卡绑定验证码）为了保护您的账号安全，验证短信请勿转发！如非本人，请忽略！2分钟内有效。</content><phoneNumber>'.$phone.'</phoneNumber></com.ctc.ema.server.jwsserver.sms.MtMessage>';
		$data = array(
			'account'=>'yzjdwx06',
			'password'=>md5('yzjtdx88888'),
			'smsType'=>3,
			'message'=>$xml
		);
		
		$data = http_build_query($data);
		$result = $this->http_post(self::URL, $data);
		
		return $result;
	}
	
	public function sendNewpwd($phone,$pwd)
	{
		$xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><com.ctc.ema.server.jwsserver.sms.MtMessage><content>您重置的新密码为'.$pwd.'，请注意保存!</content><phoneNumber>'.$phone.'</phoneNumber></com.ctc.ema.server.jwsserver.sms.MtMessage>';
		$data = array(
				'account'=>'yzjdwx06',
				'password'=>md5('yzjtdx88888'),
				'smsType'=>3,
				'message'=>$xml
		);
	
		$data = http_build_query($data);
		$result = $this->http_post(self::URL, $data);
		$this->db->insert('weixin_text',array('content'=>'YZ:'.json_encode($data).'####'.json_encode($result),'edit_date'=>date('Y-m-d H:i:s')));
		return $result;
	}
	
	public function check()
	{
		$data = array(
				'account'=>'yzjdwx06',
				'password'=>md5('yzjtdx88888'),
		);
		
		$data = http_build_query($data);
	
		$r = $this->http_post('http://60.191.133.2:8080/ema/httpSms/getSmsState', $data);
		$result=simplexml_load_string($r);
		print_r($result);
	}
	
    protected function http_post($url,$data)
    {
    	$curl = curl_init();
    	curl_setopt($curl, CURLOPT_URL, $url);
    	curl_setopt($curl, CURLOPT_POST, 1);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    
    	$result = curl_exec($curl);
    	if (curl_errno($curl)) {
    		return 'ERROR '.curl_error($curl);
    	}
    	curl_close($curl);
    	return $result;
    }
}