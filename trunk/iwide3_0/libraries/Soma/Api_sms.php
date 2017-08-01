<?php
/*
 *  Copyright (c) 2014 The CCP project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a Beijing Speedtong Information Technology Co.,Ltd license
 *  that can be found in the LICENSE file in the root of the web site.
 *
 *   http://www.yuntongxun.com
 *
 *  An additional intellectual property rights grant can be found
 *  in the file PATENTS.  All contributing project authors may
 *  be found in the AUTHORS file in the root of the source tree.
 */

/**
 * 短信接口，参考云通信（http://www.yuntongxun.com）
 */
class Api_sms extends Soma_base
{
    private $AccountSid;
    private $AccountToken;
    private $AppId;
    private $ServerIP;
    private $ServerPort;
    private $SoftVersion;
    private $Batch;  //时间戳
    private $BodyType = "xml";//包体格式，可填值：json 、xml
    private $enabeLog = true; //日志开关。可填值：true、
    private $Filename="./log.txt"; //日志文件
    private $Handle;

    /**
     * __construct
     *
     * @author     F.oris <fengzhongcheng@mofly.com>
     */
    public function __construct()
    {
        $this->initApiDefaultSetting();
    }

    /**
     * 初始化接口配置
     *
     * @author     F.oris <fengzhongcheng@mofly.com>
     */
    protected function initApiDefaultSetting()
    {
        $this->AccountSid   = '8a48b5515147eb6d01516792643c4f82';
        $this->AccountToken = 'aec001c50d9c4ca9ad75979a7f713300';
        $this->AppId        = 'aaf98f89516bf50b01517b3c6ba31c43';
        $this->ServerIP     = 'app.cloopen.com';
        $this->ServerPort   = '8883';
        $this->SoftVersion  = '2013-12-26';
        $this->Batch        = date('YmdHis');
        $this->BodyType     = 'json';
        $this->enabeLog     = true;

        $path = APPPATH. 'logs'. DS. 'soma'. DS. 'api_sms'. DS;
        if( !file_exists($path) ) { @mkdir($path, 0777, TRUE); }

        $this->Filename     = $path . date('Y-m-d_H'). '.txt';
        $this->Handle       = fopen($this->Filename, 'a');
    }

    /**
     * 设置主帐号
     *
     * @param      <type>  $AccountSid    主帐号
     * @param      <type>  $AccountToken  主帐号Token
     */
    public function setAccount($AccountSid, $AccountToken)
    {
        $this->AccountSid   = $AccountSid;
        $this->AccountToken = $AccountToken;
    }
    
    /**
     * 设置应用ID
     *
     * @param      <type>  $AppId  应用ID
     */
    public function setAppId($AppId)
    {
        $this->AppId = $AppId;
    }
    
    /**
     * 打印日志
     *
     * @param      string  $log    日志内容
     */
    protected function showlog($log)
    {
        if ($this->enabeLog) {
            $CI = & get_instance();
            $ip= $CI->input->ip_address();
            $log= str_repeat('-', 40). "\n[:"
            . date('Y-m-d H:i:s'). ' : '. $ip. ']'. "\n". $log. "\n";
            fwrite($this->Handle, $log."\n");
        }
    }
    
    /**
     * 发起HTTPS请求
     *
     * @param      <type>          $url     The url
     * @param      <type>          $data    The data
     * @param      <type>          $header  The header
     * @param      integer         $post    The post
     *
     * @return     boolean|string  ( description_of_the_return_value )
     */
    protected function curl_post($url, $data, $header, $post = 1)
    {
        //初始化curl
        $ch = curl_init();
        //参数设置
        $res= curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, $post);
        if ($post) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec ($ch);
        //连接失败
        if ($result == false) {
            if ($this->BodyType=='json') {
                $result = "{\"statusCode\":\"172001\",\"statusMsg\":\"网络错误\"}";
            } else {
                $result = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Response><statusCode>172001</statusCode><statusMsg>网络错误</statusMsg></Response>";
            }
        }

        curl_close($ch);
        return $result;
    }
    
    /**
     * 发送模板短信
     *
     * @param      <type>  $to      短信接收彿手机号码集合,用英文逗号分开
     * @param      <type>  $datas   内容数据
     * @param      <type>  $tempId  模板Id
     *
     * @return     string  ( description_of_the_return_value )
     */
    function sendTemplateSMS($to, $datas, $tempId)
    {
        //主帐号鉴权信息验证，对必选参数进行判空。
        $auth = $this->accAuth();
        if ($auth!="") {
            return $auth;
        }
        // 拼接请求包体
        if ($this->BodyType=="json") {
            $data="";
            for ($i=0; $i<count($datas); $i++) {
                $data = $data. "'".$datas[$i]."',";
            }
            $body= "{'to':'$to','templateId':'$tempId','appId':'$this->AppId','datas':[".$data."]}";
        } else {
            $data="";
            for ($i=0; $i<count($datas); $i++) {
                $data = $data. "<data>".$datas[$i]."</data>";
            }
            $body="<TemplateSMS>
                    <to>$to</to> 
                    <appId>$this->AppId</appId>
                    <templateId>$tempId</templateId>
                    <datas>".$data."</datas>
                  </TemplateSMS>";
        }
        $this->showlog("request body = \n".$body);
        // 大写的sig参数
        $sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
        // 生成请求URL
        $url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/SMS/TemplateSMS?sig=$sig";
        $this->showlog("request url = \n".$url);
        // 生成授权：主帐户Id + 英文冒号 + 时间戳。
        $authen = base64_encode($this->AccountSid . ":" . $this->Batch);
        // 生成包头
        $header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
        // 发送请求
        $result = $this->curl_post($url, $body, $header);
        $this->showlog("response body = \n".$result);
        if ($this->BodyType=="json") {//JSON格式
            $datas=json_decode($result);
        } else { //xml格式
            $datas = simplexml_load_string(trim($result, " \t\n\r"));
        }
      //  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
        //重新装填数据
        if ($datas->statusCode == 0) {
            if ($this->BodyType == "json") {
                  $datas->TemplateSMS = $datas->templateSMS;
                  unset($datas->templateSMS);
            }
        }
 
        return $datas;
    }
   
    /**
     * 主帐号鉴权
     *
     * @return     stdClass  ( description_of_the_return_value )
     */
    function accAuth()
    {
        if ($this->ServerIP=="") {
            $data = new stdClass();
            $data->statusCode = '172004';
            $data->statusMsg = 'IP为空';
            return $data;
        }
        if ($this->ServerPort<=0) {
            $data = new stdClass();
            $data->statusCode = '172005';
            $data->statusMsg = '端口错误（小于等于0）';
            return $data;
        }
        if ($this->SoftVersion=="") {
            $data = new stdClass();
            $data->statusCode = '172013';
            $data->statusMsg = '版本号为空';
            return $data;
        }
        if ($this->AccountSid=="") {
            $data = new stdClass();
            $data->statusCode = '172006';
            $data->statusMsg = '主帐号为空';
            return $data;
        }
        if ($this->AccountToken=="") {
            $data = new stdClass();
            $data->statusCode = '172007';
            $data->statusMsg = '主帐号令牌为空';
            return $data;
        }
        if ($this->AppId=="") {
            $data = new stdClass();
            $data->statusCode = '172012';
            $data->statusMsg = '应用ID为空';
            return $data;
        }
    }

    /**
     * 发送下单成功短信接口.
     *
     * 【金房卡】亲爱的{1}，您已成功订购了{2}，订单号：{3}；券码：{4}；有效期至{5}，“{6}”查看更多详情
     *
     * @param      string  $to        短信接收人：'13800138000,13800138001,……'
     * @param      string  $order_id  The order
     *
     * @return     array   ( description_of_the_return_value )
     *
     * @author     F.oris <fengzhongcheng@mofly.com>
     */
    public function sendOrderSuccessSMS($order_id)
    {
        $this->load->model('soma/Sms_model', 'sms_model');
        $data = $this->sms_model->get_order_success_sms($order_id);
        if($data['res'])
        {
            $data = $data['data'];
            return $this->sendTemplateSMS($data['to'], $data['datas'], $data['temp_id']);
        }
        $this->showlog($data['msg']);
        return false;
    }

}
