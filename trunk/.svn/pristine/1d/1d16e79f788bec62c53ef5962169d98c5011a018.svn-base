<?php
/**
 * HTTP接口发送短信，参数说明见文档，需要安装CURL扩展
 * 
 * 使用示例：
 * $sendSms = new SendSmsHttp();
 * $sendSms->SpCode = '123456';
 * $sendSms->LoginName = 'abc123';
 * $sendSms->Password = '123abc';
 * $sendSms->MessageContent = '测试短信';
 * $sendSms->UserNumber = '15012345678,13812345678';
 * $sendSms->SerialNumber = '';
 * $sendSms->ScheduleTime = '';
 * $sendSms->ExtendAccessNum = '';
 * $sendSms->f = '';
 * $res = $sendSms->send();
 * echo $res ? '发送成功' : $sendSms->errorMsg;
 * 
 */
class Bgysms extends CI_Model {
  private $_apiUrl = 'http://gd.ums86.com:8899/sms/Api/Send.do'; // 发送短信接口地址
  public $SpCode = '221245';
  public $LoginName = 'admin0';
  public $Password = '#bgy2015@2016';
  public $MessageContent;
  public $UserNumber;
  public $SerialNumber;
  public $ScheduleTime = '';
  public $ExtendAccessNum = 1;
  public $f = '';
  public $errorMsg;
  
  /**
   * 发送短信
   * @return boolean
   */
  public function send() {
      $this->load->library('session');
      
//     if($this->session->has_userdata('sms_send_num')) {
//       if($this->session->sms_send_num>=10) {
//           if(!$this->session->has_userdata('sms_send_time') || (time()-$this->session->sms_send_time<3600*24)) {
//             return false;
//           } 
//       }
//     }
    
    $params = array(
      "SpCode" => $this->SpCode,
      "LoginName" => $this->LoginName,
      "Password" => $this->Password,
      "MessageContent" => iconv("UTF-8", "GB2312//IGNORE", $this->MessageContent),
      "UserNumber" => $this->UserNumber,
      "SerialNumber" => $this->getSerialNumber(),
      "ScheduleTime" => $this->ScheduleTime,
      "ExtendAccessNum" => $this->ExtendAccessNum,
      "f" => $this->f,
    );
    
    $data = http_build_query($params);
    $res = iconv('GB2312', 'UTF-8//IGNORE', $this->_httpClient($data));
    $resArr = array();
        parse_str($res, $resArr);
  
    if (!empty($resArr) && $resArr["result"] == 0) {
      return true;
    } else {
      return false;
    }
  }
  
  protected function getSerialNumber()
  {
    $time = explode(' ',microtime());
    return $time[1].str_replace('.','',$time[0]).mt_rand(0,9);
  }
  
  
  /**
   * POST方式访问接口
   * @param string $data
   * @return mixed
   */
  private function _httpClient($data) {
    try {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$this->_apiUrl);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      $res = curl_exec($ch);
      curl_close($ch);
      return $res;
    } catch (Exception $e) {
      $this->errorMsg = $e->getMessage();
      return false;
    }
  }
}
