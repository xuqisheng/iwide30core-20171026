<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );

/**
 * 送券/送礼包
 * Class GetPackageCard
 */
class Getpackagecard extends CI_Model
{
    const TAB_FANS = 'fans';
    public function __construct() {
        parent::__construct ();
    }
    /**
     * 2016-07-21
     * @author knight
     * 关注送劵/礼包
     * @param $inter_id 微信集团ID
     * @param $openid 微信会员ID
     * @return bool|void
     */
    public function give_package_card($inter_id, $openid){
        if(!isset($inter_id) || empty($inter_id)) return;
        if(!isset($openid) || empty($openid)) return;
        $fans = $this->getFansByOpenid($inter_id,$openid);

        $log = [
            '_SERVER'=>$_SERVER,
            'namespace'=>'models/member/Getpackagecard',
            'func'=>'Getpackagecard::getFansByOpenid',
            'param'=>[$inter_id,$openid],
            'result'=>$fans
        ];
        $this->write_log(@json_encode($log),'membervip/access_log');

        if(!empty($fans) && !is_null($fans['subscribe_time'])) return;
        //获取优惠信息
        $post_card = array(
            'token'=>'',
            'inter_id'=>$inter_id,
            'type'=>'gazeini',
            'is_active'=>'t',
            'count'=>100
        );

//        $cardruleurl = PMS_PATH_URL."cardrule/get_package_card_rule_info"; //获取优惠规则
        $cardruleurl = "http://member.iwide.cn/vapi/cardrule/get_package_card_rule_info"; //获取优惠规则
        $rule_info= $this->doCurlPostRequest($cardruleurl, $post_card );

        $log = [
            'namespace'=>'models/member/Getpackagecard',
            'url'=>$cardruleurl,
            'param'=>$post_card,
            'result'=>$rule_info
        ];
        $this->write_log(@json_encode($log),'membervip/access_log');

        if(empty($rule_info['data'])) return false;

        $rule_data = $rule_info['data'];

//        $packge_url = INTER_PATH_URL.'package/give'; //领取礼包
//        $card_url = PMS_PATH_URL.'cardrule/reg_gain_card'; //领取卡劵
        $packge_url = 'http://member.iwide.cn/api/package/give'; //领取礼包
        $card_url = 'http://member.iwide.cn/vapi/cardrule/reg_gain_card'; //领取卡劵
        $res = array();
        if(!empty($rule_data) && is_array($rule_data)){
            foreach ($rule_data as $key => $item){
                if( isset($item['is_package']) && $item['is_package']=='t'){
                    $package_data = array(
                        'token'=>'',
                        'inter_id'=>$inter_id,
                        'openid'=>$openid,
                        'uu_code'=>uniqid(),
                        'package_id'=>$item['package_id'],
                        'card_rule_id'=>$item['card_rule_id'],
                        'number'=>$item['frequency']
                    );
                    $res = $this->doCurlPostRequest( $packge_url , $package_data );
                }elseif (isset($item['is_package']) && $item['is_package']=='f'){
                    $card_data = array(
                        'token'=>'',
                        'inter_id'=>$inter_id,
                        'openid'=>$openid,
                        'card_id'=>$item['card_id'],
                        'type'=>'gazeini',
                        'card_rule_id'=>$item['card_rule_id'],
                        'number'=>$item['frequency']
                    );
                    $res = $this->doCurlPostRequest( $card_url , $card_data );
                }
            }
        }
        return $res;
    }

    /**
     * 2016-07-21
     * @author knight
     * 封装curl的调用接口，post的请求方式
     * @param string URL
     * @param string POST表单值
     * @param array 扩展字段值
     * @param second 超时时间
     * @return 请求成功返回成功结构，否则返回FALSE
     */
    protected function doCurlPostRequest( $url , $post_data , $timeout = 5,$header = array()) {
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
            'namespace'=>'models/member/Getpackagecard',
            'func'=>'Getpackagecard::doCurlPostRequest',
            'curl'=>$url,
            'param'=>$post_data,
            'timeout'=>$timeout,
            'usetime'=>($endtime - $startime),
            'result'=>$res
        ];
        $this->write_log(@json_encode($log),'membervip/access_log');
        return json_decode($res,true);
    }

    public function write_log($log,$path = '',$key=''){
        $this->load->library('MYLOG');
        MYLOG::w($log,$path,$key);
    }

    /**
     * 2016-07-29
     * @author knight
     * 通过openid 获取粉丝信息  (支持单个或者以数组方式获取多个粉丝信息)
     * @param $openid
     * @return mixed
     */
    public function getFansByOpenid($inter_id,$openid){
        if(empty($inter_id) || empty($openid)) return array();
        if( is_array($openid) ){
            $this->db->where_in('openid', $openid);
            $this->db->where('inter_id', $inter_id);
        } else {
            $where = array('openid'=>$openid,'inter_id'=>$inter_id);
            $this->db->where($where);
        }
        return $this->db->get( self::TAB_FANS )->row_array();
    }
}