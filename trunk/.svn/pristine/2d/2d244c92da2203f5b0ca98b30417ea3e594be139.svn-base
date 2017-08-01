<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	会员接口中心
*	@author  liwensong
*	@copyright www.iwide.cn
*	@version 4.0
*	@Email 171708252@qq.com
*/
class Openapi extends MY_Controller{

    const SEND_URL = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=';
    protected $vars = array();
    protected $_token = '';
    protected $client_ip = '';
    public function __construct(){
        parent::__construct();
        $this->load->library("MYLOG");
        $this->load->helper('common_helper');
        $this->client_ip = $this->input->ip_address();
        $this->vars = get_args(); //获取URL参数数据
        MYLOG::w(json_encode(array('args'=>$this->vars,'client_ip'=>$this->client_ip)),'front/membervip/api/openapi','call_args');
    }

    /**
     * 此方法用于检测任务的可否执行。计划任务分来3类：
     * 1 类是可以重复执行的，不加任何限制；
     * 2 类是绝对不能重复执行的，要在执行之前加一个 remote_ip 的判断，只允许某一个服务器触发，其他ip一律不认
     * 3 类是 担心会漏发（这个特许授权服务器ip挂掉了），必须在其他服务器加以保障的，跟第1类的区别是，第1类可以少发无实质性影响
     * @return bool TRUE可执行 false不可执行
     */
    protected function _check_access(){
        if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
            $ip_whitelist= array(
                '218.13.33.126', //碧桂园
                '59.37.49.171', //碧桂园测试
                '10.25.168.86', //redis01
                '10.25.3.85',  //redis02
                '10.27.232.209', //预发布
            );
            $client_ip = $this->client_ip;
            if( in_array($client_ip, $ip_whitelist) ){
                return TRUE;

            } else {
                $msg = $this->action. ' 拒绝非法IP执行';
                MYLOG::w(json_encode(array('msg'=>$msg,'client_ip'=>$client_ip)),'front/membervip/api/openapi','check_access');
                exit($msg);
            }

        } else {
            return TRUE;
        }
    }


    /**
     * 发送模版消息接口 （暂时提供给碧桂园使用）
     */
    public function send_template(){
        $this->_check_access();
        if(empty($this->vars['icNum'])) $this->return_json('会员卡号icNum不能为空','49003');
        if(empty($this->vars['content'])) $this->return_json('消息内容content不能为空','49004');
        $this->load->model('member/Message_wxtemp_model','wxtemp_model');
        $this->load->model('membervip/common/Public_model','pm');
        try{
            //获取用户信息
            $where = array(
                'membership_number'=>$this->vars['icNum'],
                'is_active'=>'t',
                'member_mode'=>2
            );
            $member_info = $this->pm->get_info($where,'member_info');
            MYLOG::w(json_encode(array('res'=>$member_info,'param'=>$where)),'front/membervip/api/openapi','send_template');
            if(empty($member_info)){
                $this->return_json('找不到用户的openid','49002');
            }

            $openid = $member_info['open_id'];

            $wxtemp_model = $this->wxtemp_model;
            $type = $wxtemp_model::SEND_CREDIT_NOTICE;
            $where = array('inter_id'=>$member_info['inter_id'],'type'=>$type);
            $temps = $this->pm->get_info($where,'member_message_template');
            if(empty($temps)){
                $this->return_json('请配置消息模版','49001');
            }
            $inter_id = $temps['inter_id'];

            $content = @unserialize(base64_decode($temps['content']));
            if(empty($content)){
                $content = unserialize($temps['content']);//Message: unserialize(): Error at offset 0 of 258 bytes
            }

            $post_content = $this->vars['content'];
            $key_map = array('First','Remark','FieldName','Account','change','CreditChange','CreditTotal');
            foreach ($key_map as $fv){
                if(!isset($post_content[$fv])) $this->return_json("参数{$fv}缺失",'49005');
            }

            $content['first']['value'] = $post_content['First'];
            $content['remark']['value'] = $post_content['Remark'];


            $message['touser'] = $openid;//发送给哪个用户
            $message['template_id'] = $temps['template_id'];//微信模版ID

            //链接处理
            $url = '';
            $param = array('id'=>$inter_id);
            $templateUrl = $temps['link'];//需要处理链接
            if( $templateUrl ){
                $exp = explode( '_', $templateUrl, 3 );
                switch ($templateUrl) {
                    case 'membervip_center':
                        $url = EA_const_url::inst()->get_front_url( $inter_id, $exp[0].'/'.$exp[1],$param);
                        break;
                    case 'membervip_card':
                        $url = EA_const_url::inst()->get_front_url( $inter_id, $exp[0].'/'.$exp[1],$param);
                        break;
                    case 'membervip_card_cardinfo':
                        $param['member_card_id'] = isset( $datas['member_card_id'] ) ? $datas['member_card_id'] : '';
                        $url = EA_const_url::inst()->get_front_url( $inter_id, $exp[0].'/'.$exp[1].'/'.$exp[2],$param);
                        break;
                    case 'membervip_reg':
                        $url = EA_const_url::inst()->get_front_url( $inter_id, $exp[0].'/'.$exp[1],$param);
                        break;
                    case 'membervip_invitate':
                        $url = EA_const_url::inst()->get_front_url( $inter_id, $exp[0].'/'.$exp[1],$param);
                        break;
                    default:
                        break;
                }
            }
            $message['url'] = $url;
            if(!empty($this->vars['url'])){
                $message['url'] = $this->vars['url'];
            }
            $message['data']['first'] = array(
                'value'=>$content['first']['value'],
                'color'=>$content['first']['color'],
            );
            $message['data']['remark'] = array(
                'value'=>$content['remark']['value'],
                'color'=>$content['remark']['color'],
            );

            $message['data']['FieldName'] = array(
                'value'=>$post_content['FieldName'],
                'color'=>'#000000',
            );

            $message['data']['Account'] = array(
                'value'=>$post_content['Account'],
                'color'=>'#000000',
            );

            $message['data']['change'] = array(
                'value'=>$post_content['change'],
                'color'=>'#000000',
            );

            $message['data']['CreditChange'] = array(
                'value'=>$post_content['CreditChange'],
                'color'=>'#000000',
            );

            $message['data']['CreditTotal'] = array(
                'value'=>$post_content['CreditTotal'],
                'color'=>'#000000',
            );

            $json_data = @json_encode($message);
            if(empty($json_data)){
                $this->return_json();
            }

            $sendResult = $this->request_send_template($inter_id,$json_data);
            MYLOG::w(json_encode(array('res'=>$sendResult,'param'=>array('inter_id'=>$inter_id,'json_data'=>$json_data))),'front/membervip/api/openapi','send_template');
            $_sendResult = @json_decode($sendResult,true);
            if(empty($_sendResult)){
                $this->return_json();
            }

            $data = array(
                'inter_id'=>$inter_id,
                'hotel_id'=>$temps['hotel_id'],
                'temp_id'=>$temps['temp_id'],
                'template_id'=>$temps['template_id'],
                'openid'=>$openid,
                'type'=>$type,
                'msg'=>$json_data,
                'result'=>$sendResult,
                'create_time'=>date( "Y-m-d H:i:s"),
                'status'=>$_sendResult['errcode']=='0'?1:2
            );
            $data['result'] = $sendResult;
            $data['create_time'] = date( "Y-m-d H:i:s");
            $data['status'] = $_sendResult['errcode']=='0'?1:2;//STATUS_FAIL

            //保存到record
            $res = $this->pm->add_data($data,'message_wxtemp_record');
            MYLOG::w(json_encode(array('res'=>$res,'param'=>$data)),'front/membervip/api/openapi','message_wxtemp_record');
            echo $sendResult;exit;
        }catch (Exception $e){
            MYLOG::w(array('FILE'=>$e->getFile(),'LINE'=>$e->getLine(),'code'=>$e->getCode(),'message'=>$e->getMessage()),'front/membervip/api/openapi', 'Exception');
            $this->return_json('系统繁忙',-1);
        }
    }

    public function send_template_v2(){
        //活动开始 消息推送接口
        $this->_check_access();
        if(empty($this->vars['icNum'])) $this->return_json('会员卡号icNum不能为空','49003');
        if(empty($this->vars['type'])) $this->return_json('类型type不能为空','49003');
        if(empty($this->vars['content'])) $this->return_json('消息内容content不能为空','49004');
        $this->load->model('member/Message_wxtemp_model','wxtemp_model');
        $this->load->model('membervip/common/Public_model','pm');
        try{
            //获取用户信息
            $where = array(
                'membership_number'=>$this->vars['icNum'],
                'is_active'=>'t',
                'member_mode'=>2
            );
            $member_info = $this->pm->get_info($where,'member_info');
            MYLOG::w(json_encode(array('res'=>$member_info,'param'=>$where)),'front/membervip/api/openapi','send_template');
            if(empty($member_info)){
                $this->return_json('找不到用户的openid','49002');
            }
    
            $openid = $member_info['open_id'];
    
            $wxtemp_model = $this->wxtemp_model;
            $type_post=$this->vars['type'];
            if ($type_post=='action'){
            $type = $wxtemp_model::SEND_ACTION_NOTICE;
            }else if ($type_post=='recommend'){
            $type = $wxtemp_model::SEND_RECOMMEND_NOTICE;
            }
            $where = array('inter_id'=>$member_info['inter_id'],'type'=>$type);
            $temps = $this->pm->get_info($where,'member_message_template');
            if(empty($temps)){
                $this->return_json('请配置消息模版','49001');
            }
            $inter_id = $temps['inter_id'];
    
            $content = @unserialize(base64_decode($temps['content']));
            if(empty($content)){
                $content = unserialize($temps['content']);//Message: unserialize(): Error at offset 0 of 258 bytes
            }
           
            $post_content = $this->vars['content'];
            if ($type_post=='action'){
            $key_map = array('First','Remark','Theme','Time','Address','Detail');
            }elseif ($type_post=='recommend'){
            $key_map = array('First','Remark','Recommend','Recommended');
            }
            
            foreach ($key_map as $fv){
                if(!isset($post_content[$fv])) $this->return_json("参数{$fv}缺失",'49005');
            }
            
            $message['touser'] = $openid;//发送给哪个用户
            $message['template_id'] = $temps['template_id'];//微信模版ID
            $message['url'] = $this->vars['url'];//url
            $message['data']['first'] = array(
                'value'=>$post_content['First'],
                'color'=>'#000000',
            );
            $message['data']['remark'] =array(
                'value'=>$post_content['Remark'],
                'color'=>'#000000',
            ); //备注
            unset($post_content['First']);
            unset($post_content['Remark']);
            unset($key_map[0]);
            unset($key_map[1]);
            foreach($key_map as $key=>$val){
                //整理参数
                $post_content[]=$post_content[$val];
                unset($post_content[$val]);
            }
            $i=0;
            foreach ($post_content as $key=>$val){
                //拼装数据
                $i++;
                $message['data']['keyword'.$i]=['value'=>$val];
            }
            $json_data = @json_encode($message);
            if(empty($json_data)){
                $this->return_json();
            }
    
            $sendResult = $this->request_send_template($inter_id,$json_data);
            MYLOG::w(json_encode(array('res'=>$sendResult,'param'=>array('inter_id'=>$inter_id,'json_data'=>$json_data))),'front/membervip/api/openapi','send_template');
            $_sendResult = @json_decode($sendResult,true);
            if(empty($_sendResult)){
                $this->return_json();
            }
    
            $data = array(
                'inter_id'=>$inter_id,
                'hotel_id'=>$temps['hotel_id'],
                'temp_id'=>$temps['temp_id'],
                'template_id'=>$temps['template_id'],
                'openid'=>$openid,
                'type'=>$type,
                'msg'=>$json_data,
                'result'=>$sendResult,
                'create_time'=>date( "Y-m-d H:i:s"),
                'status'=>$_sendResult['errcode']=='0'?1:2
            );
            $data['result'] = $sendResult;
            $data['create_time'] = date( "Y-m-d H:i:s");
            $data['status'] = $_sendResult['errcode']=='0'?1:2;//STATUS_FAIL
    
            //保存到record
            $res = $this->pm->add_data($data,'message_wxtemp_record');
            MYLOG::w(json_encode(array('res'=>$res,'param'=>$data)),'front/membervip/api/openapi','message_wxtemp_record');
            echo $sendResult;exit;
        }catch (Exception $e){
            MYLOG::w(array('FILE'=>$e->getFile(),'LINE'=>$e->getLine(),'code'=>$e->getCode(),'message'=>$e->getMessage()),'front/membervip/api/openapi', 'Exception');
            $this->return_json('系统繁忙',-1);
        }
    }
    
    
    
    /**
     * 发送模版消息
     * @param $json_data
     * @return string
     */
    public function request_send_template($inter_id = null,$json_data = array()){
        MYLOG::w(json_encode(array('inter_id'=>$inter_id,'data'=>$json_data)),'front/membervip/api/openapi','request_send_template');
        if(empty($inter_id || empty($json_data))) return $this->return_json('缺少必要参数!',-1,true);

        $this->load->model('wx/access_token_model');
        $access_token= $this->access_token_model->get_access_token($inter_id);
        $url = self::SEND_URL.$access_token;
        $result = doCurlPostRequest($url,$json_data);
        //保存日志
        MYLOG::w(json_encode(array('res'=>$result,'url'=>$url,'data'=>$json_data)),'front/membervip/api/openapi','request_send_template');

        $result_data = json_decode($result,true);
        if($result_data['errcode'] == 0 && $result_data['errmsg'] == 'ok'){
            return $this->return_json('发送成功',$result_data['errcode'],true);
        }elseif ($result_data['errcode'] == '40001'){
            $access_token = $this->access_token_model->reflash_access_token ( $inter_id );
            $url = self::SEND_URL.$access_token;
            $result = doCurlPostRequest($url,$json_data);
            //保存日志
            MYLOG::w(json_encode(array('res'=>$result,'url'=>$url,'data'=>$json_data)),'front/membervip/api/openapi','request_send_template');

            $result_data = json_decode($result,true);
            if($result_data['errcode'] == 0 && $result_data['errmsg'] == 'ok'){
                return $this->return_json('发送成功',$result_data['errcode'],true);
            }
        }elseif ($result_data['errcode'] == '42001'){
            $access_token = $this->access_token_model->reflash_access_token ( $inter_id );
            $url = self::SEND_URL.$access_token;
            $result = doCurlPostRequest($url,$json_data);
            //保存日志
            MYLOG::w(json_encode(array('res'=>$result,'url'=>$url,'data'=>$json_data)),'front/membervip/api/openapi','request_send_template');

            $result_data = json_decode($result,true);
            if($result_data['errcode'] == 0 && $result_data['errmsg'] == 'ok'){
                return $this->return_json('发送成功',$result_data['errcode'],true);
            }
        }
        return $this->return_json('发送失败！','40001',true);
    }

    /**
     * 获取微信用户的关注状态
     */
    public function user_subscribe(){
        $this->_check_access();
        if(empty($this->vars['openid'])){
            $this->return_json('openid is null！','49002');
        }

        $openid = $this->vars['openid'];

        $inter_id = 'a421641095';
        if(!empty($this->vars['id'])){
            $inter_id = $this->vars['id'];
        }

        $this->load->model ('wx/Access_token_model','token_model');
        $access_token = $this->token_model->get_access_token($inter_id);
        $this->load->model('wx/publics_model', 'publics');
        $user_info = $this->publics->get_wxuser_info($inter_id,$openid,$access_token);
        if(empty($user_info) OR (isset($user_info['errcode']) && $user_info['errcode'])){
            $errcode = !empty($user_info['errcode'])?$user_info['errcode']:-1;
            $errmsg = !empty($user_info['errmsg'])?$user_info['errmsg']:'系统繁忙，请求失败！';
            $this->return_json($errmsg,$errcode);
        }else{
            if(!empty($user_info['subscribe'])) {
                echo json_encode(array('errcode'=>0,'subscribe'=>$user_info['subscribe']));
                exit(0);
            }
            $subscribe = $user_info['subscribe'] === 0 ? 0 :  $user_info['subscribe'];
            echo json_encode(array('errcode'=>0,'subscribe'=>$subscribe));
            exit(0);
        }
    }

    /**
     * 输出JSON提示
     * @param string $errmsg 提示信息
     * @param int $errcode 状态码
     * @param boolean $flag 返回形式：true - return  |  false - exit()
     * @return string
     */
    protected function return_json($errmsg = '系统繁忙',$errcode = -1,$flag=false){
        header('Content-Type:application/json; charset=utf-8');
        $result= new stdClass();
        $result->errcode= $errcode;
        $result->errmsg= $errmsg;
        if($flag===true) return json_encode($result);
        exit(json_encode($result));
    }
}
?>