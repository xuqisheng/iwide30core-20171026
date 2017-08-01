<?php
namespace App\services;


/**
 * Class Base_Service
 * @package App\services
 * @author lijiaping  <lijiaping@mofly.cn>
 *
 */
class MemberBaseService extends BaseService
{
    public function __construct(){

        //统一处理数据流
        $post = json_decode($this->getCI()->input->raw_input_stream,true);
        if(!empty($post)&&is_array($post)){
            foreach ($post as $key => $value) {
                if(!isset($_POST[$key])){
                    $_POST[$key] = $value;
                }
            }
        }
    }

    /**
    * 封装curl的调用接口，post的请求方式
    * @param string URL
    * @param string POST表单值
    * @param array 扩展字段值
    * @param second 超时时间
    * @return 请求成功返回成功结构，否则返回FALSE
    */
    protected function doCurlPostRequest( $url , $post_data , $timeout = 20) {
        $startime = microtime(true);
        $requestString = http_build_query($post_data);
        if ($url == "" || $timeout <= 0) {
            return false;
        }
        $url .= '?t='.time();
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
//        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); //设置HTTP头字段的数组
        //设置头文件的信息作为数据流输出
//        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //設置請求數據返回的過期時間
        curl_setopt ( $curl, CURLOPT_TIMEOUT, ( int ) $timeout );
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, true);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestString);
        //执行命令
        $res = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        $endtime = microtime(true);
        //写入日志
        $log = [
            'namespace'=>'core/MY_Front_Member',
            'curl'=>$url,
            'param'=>$post_data,
            'timeout'=>$timeout,
            'usetime'=>($endtime - $startime),
            'result'=>$res
        ];
        \MYLOG::w(@json_encode($log),'membervip/access_log');
        return json_decode($res,true);
    }


    /**
     * 解析接口返回的数据
     * @param array $return 接口返回的信息
     * @return array
     */
    protected function parse_curl_msg($return = array()){
        $result = 1004;
        $err = '40003';
        $msg = '请求失败';
        $data = array();
        if(isset($return['err'])){
            $err = $return['err'];
            if($return['err'] == '0') {
                $result = 1000;
                $msg = !empty($return['msg'])?$return['msg']:'ok';
            }else {
                $msg = !empty($return['msg'])?$return['msg']:'请求失败';
            }
        }elseif (!isset($return['err'])){
            if(!empty($return['data'])) {
                $result = 1000;
                $err = 0;
                $msg = !empty($return['msg'])?$return['msg']:'ok';
            }else {
                $msg = !empty($return['msg'])?$return['msg']:'请求失败';
            }
        }

        $res_data = array(
            'err'=>$err,
            'code'=>$result,
            'msg'=>$msg,
            'data'=>$data
        );
        if(!empty($return)){
            foreach ($return as $key => $value){
                if(!in_array($key,array('err','msg','code'))){
                    $res_data[$key] = $value;
                }
            }
        }

        return $res_data;
    }
}