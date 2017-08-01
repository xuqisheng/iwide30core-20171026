<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 用户注册
 * 
 * @author 杨成峰
 * @copyright www.iwide.cn
 * @version 4.0
 *          @Email 445315045@qq.com
 *         
 */
class Verify extends MY_Front_Member
{
    const SEND_URL = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=';

    public function index()
    {
        $this->member_witch();
    }

    public function save_verify()
    {
        // 保存未审核的会员信息，目前只给洲际和长春名人用
        $err = [
            'err' => 40003,
            'msg' => '信息不合法'
        ];
        $data = $_POST;
        if ($this->inter_id == 'a476352963') {
            //长春名人
            if (empty($data['membership_number']) || empty($data['name']) || empty($data['telephone']) || empty($data['id_card_no'])) {
                echo json_encode($err);
                exit();
            }
        } else {
            if ($data['type'] == 'old' && empty($data['membership_number']) || empty($data['name']) || empty($data['telephone']) || empty($data['email']) || empty($data['id_card_no'])) {
                echo json_encode($err);
                exit();
            }
        }

        $data['audit'] = '2';
        $data['open_id'] = $this->openid;
        $data['inter_id'] = $this->inter_id;
        $data['membership_number']=isset($_POST['membership_number'])&&!empty($_POST['membership_number'])?$_POST['membership_number'] : ' ';

        if ($data['inter_id'] == 'a476352963') {
            //长春名人
            if (preg_match('/^[A-Za-z]{4}[0-9]{5}$/', $data['membership_number']) != 1) {
                echo json_encode([
                    'err' => 40003,
                    'msg' => '会员卡号录入有误，会员号为9位, 前4位为字母，后5位为数字，请修改后重新提交'
                ]);
                exit();
            }
        } else {
            if ($data['type'] == 'old'){
                if( !is_numeric($data['membership_number'])||strlen($data['membership_number'])!=9){
                    echo json_encode([
                        'err' => 40003,
                        'msg' => '请输入九位会员号'
                    ]);
                    exit();
                }
            }
        }

        $this->load->model('membervip/admin/Public_model', 'pum');
        $ext_where = [
            'inter_id' => $this->inter_id,
            'open_id' => $this->openid
        ];
        // 查询会员info数据
        $info = $this->pum->_shard_db()
            ->where($ext_where)
            ->get('member_info')
            ->row();
        $data['member_lvl_id'] = $info->member_lvl_id;
        $data['nickname'] = $info->nickname;
        // 查询审核表是否存在
        $ext = $this->pum->_shard_db()
            ->where($ext_where)
            ->get('member_verify')
            ->row();
        if (is_object($ext) && $ext->id > 0) {
            if ($ext->audit == '1') {
                echo json_encode([
                    'err' => 40003,
                    'msg' => '您已经通过审核，无需再次提交'
                ]);
                exit();
            }
            // 已经存在记录，继续提交则更新记录
            $res = $this->pum->_shard_db()->update('member_verify', $data, array(
                'id' => $ext->id
            ));
            if ($this->pum->_shard_db()->affected_rows() == 0) {
                echo json_encode([
                    'err' => 1,
                    'msg' => '所提交资料无变更'
                ]);
                exit();
            }
        } else {
            $data['subtime'] = time();
            $res = $this->pum->_shard_db()->insert('member_verify', $data);
        }
        if ($this->pum->_shard_db()->affected_rows() > 0) {
            // 发送模板消息
            // 发送模板消息 注册成功发送等级变更通知
            $this->load->model('member/Message_wxtemp_model', 'wxtemp_model');
            $wxtemp_model = $this->wxtemp_model;
            $type = $wxtemp_model::SEND_VERIFY;
            $temps_where = array(
                'inter_id' => $this->inter_id,
                'type' => $type
            );
            $temps = $this->pum->get_info($temps_where, 'member_message_template');
            if (empty($temps)) {
                echo json_encode([
                    'err' => 1,
                    'msg' => '模板消息未配置'
                ]);
                exit();
            }
            $templateUrl = $temps['link'];
            $url = $this->geturl($templateUrl, $this->inter_id);
            $message = [];
            $message['touser'] = $data['open_id']; // 发送给哪个用户
            $message['template_id'] = $temps['template_id']; // 微信模版ID
            $message['url'] = $url; // url
            $message['data']['first'] = array(
                'value' => '您已提交了会员资料，我们会在24小时内进行审核。',
                'color' => '#000000'
            );
            $message['data']['remark'] = array(
                'value' => '审核结果会进行通知您，感谢关注。',
                'color' => '#000000'
            );
            $message['data']['keyword1'] = [
                'value' => '审核中'
            ];
            $message['data']['keyword2'] = [
                'value' => '资料提交审核中'
            ];

            $json_data = @json_encode($message);
            // 组装模板消息数据 END
            $sendResult = $this->request_send_template($this->inter_id, $json_data); // 发送模板消息

            echo json_encode([
                'err' => 0,
                'msg' => '提交成功，请等待审核'
            ]);
        }
    }

    /**
     * 发送模版消息
     *
     * @param
     *            $json_data
     * @return string
     */
    public function request_send_template($inter_id = null, $json_data = array())
    {
        MYLOG::w(json_encode(array(
            'inter_id' => $inter_id,
            'data' => $json_data
        )), 'front/membervip/api/openapi', 'request_send_template');
        if (empty($inter_id) || empty($json_data))
            return $this->return_json('缺少必要参数!', - 1, true);
        
        $this->load->model('wx/access_token_model');
        $access_token = $this->access_token_model->get_access_token($inter_id);
        $url = self::SEND_URL . $access_token;
        $result = $this->doCurlPostRequest_wx($url, $json_data);
        // 保存日志
        MYLOG::w(json_encode(array(
            'res' => $result,
            'url' => $url,
            'data' => $json_data
        )), 'front/membervip/verify', 'request_send_template');
        
        $result_data = json_decode($result, true);
        if ($result_data['errcode'] == 0 && $result_data['errmsg'] == 'ok') {
            return $this->return_json('发送成功', $result_data['errcode'], true);
        } elseif ($result_data['errcode'] == '40001') {
            $access_token = $this->access_token_model->reflash_access_token($inter_id);
            $url = self::SEND_URL . $access_token;
            $result = $this->doCurlPostRequest_wx($url, $json_data);
            // 保存日志
            MYLOG::w(json_encode(array(
                'res' => $result,
                'url' => $url,
                'data' => $json_data
            )), 'admin/membervip/verify', 'request_send_template');
            
            $result_data = json_decode($result, true);
            if ($result_data['errcode'] == 0 && $result_data['errmsg'] == 'ok') {
                return $this->return_json('发送成功', $result_data['errcode'], true);
            }
        } elseif ($result_data['errcode'] == '42001') {
            $access_token = $this->access_token_model->reflash_access_token($inter_id);
            $url = self::SEND_URL . $access_token;
            $result = $this->doCurlPostRequest_wx($url, $json_data);
            // 保存日志
            MYLOG::w(json_encode(array(
                'res' => $result,
                'url' => $url,
                'data' => $json_data
            )), 'admin/membervip/verify', 'request_send_template');
            
            $result_data = json_decode($result, true);
            if ($result_data['errcode'] == 0 && $result_data['errmsg'] == 'ok') {
                return $this->return_json('发送成功', $result_data['errcode'], true);
            }
        }
        return $this->return_json('发送失败！', '40001', true);
    }

  function doCurlPostRequest_wx($url, $requestString, $extra = array(), $timeout = 20)
    {
        if ($url == "" || $requestString == "" || $timeout <= 0) {
            return false;
        }
        $con = curl_init((string) $url);
        curl_setopt($con, CURLOPT_HEADER, false);
        curl_setopt($con, CURLOPT_POSTFIELDS, $requestString);
        curl_setopt($con, CURLOPT_POST, true);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($con, CURLOPT_TIMEOUT, (int) $timeout);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($con, CURLOPT_SSL_VERIFYHOST, 0);
        
        if (! empty($extra) && is_array($extra)) {
            $headers = array();
            foreach ($extra as $opt => $value) {
                if (strexists($opt, 'CURLOPT_')) {
                    curl_setopt($con, constant($opt), $value);
                } elseif (is_numeric($opt)) {
                    curl_setopt($con, $opt, $value);
                } else {
                    $headers[] = "{$opt}: {$value}";
                }
            }
            if (! empty($headers)) {
                curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
            }
        }
        $res = curl_exec($con);
        // var_dump(curl_error($con));
        return $res;
    }
    
    function geturl($key, $inter_id)
    {
        $param = array(
            'id' => $inter_id
        );
        $exp = explode('_', $key, 3);
        switch ($key) {
            case 'membervip_center':
                $url = EA_const_url::inst()->get_front_url($inter_id, $exp[0] . '/' . $exp[1], $param);
                break;
            case 'membervip_card':
                $url = EA_const_url::inst()->get_front_url($inter_id, $exp[0] . '/' . $exp[1], $param);
                break;
            case 'membervip_card_cardinfo':
                $param['member_card_id'] = isset($datas['member_card_id']) ? $datas['member_card_id'] : '';
                $url = EA_const_url::inst()->get_front_url($inter_id, $exp[0] . '/' . $exp[1] . '/' . $exp[2], $param);
                break;
            case 'membervip_reg':
                $url = EA_const_url::inst()->get_front_url($inter_id, $exp[0] . '/' . $exp[1], $param);
                break;
            case 'membervip_invitate':
                $url = EA_const_url::inst()->get_front_url($inter_id, $exp[0] . '/' . $exp[1], $param);
                break;
            default:
                break;
        }
        return $url;
    }

    /**
     * 输出JSON提示
     *
     * @param string $errmsg
     *            提示信息
     * @param int $errcode
     *            状态码
     */
    protected function return_json($errmsg = '系统繁忙', $errcode = -1, $flag = false)
    {
        header('Content-Type:application/json; charset=utf-8');
        $result = new stdClass();
        $result->errcode = $errcode;
        $result->errmsg = $errmsg;
        if ($flag === true)
            return json_encode($result);
        exit(json_encode($result));
    }

    public function show_old_member_reg()
    {
        //获取会员审核表数据
        $where['open_id'] = $this->openid;
        $where['inter_id'] = $this->inter_id;
        $this->load->model('membervip/admin/Public_model', 'pum');
        $data = $this->pum->_shard_db()
        ->where($where)
        ->get('member_verify')
        ->row();
        if (is_object($data)&&$data->type=='old'){
            $view_data['member_info']=json_decode(json_encode($data),true);
            $this->template_show('member', $this->_template, 'member_old',$view_data);
        }else{
            $this->template_show('member', $this->_template, 'member_old');
        }
    }

    public function member_witch()
    {
        $this->template_show('member', $this->_template, 'member_witch');
    }

    public function show_new_member_reg()
    {
        //获取会员审核表数据
        $where['open_id'] = $this->openid;
        $where['inter_id'] = $this->inter_id;
        $this->load->model('membervip/admin/Public_model', 'pum');
        $data = $this->pum->_shard_db()
        ->where($where)
        ->get('member_verify')
        ->row();
        if (is_object($data)&&$data->type=='new'){
            $view_data['member_info']=json_decode(json_encode($data),true);
            $this->template_show('member', $this->_template,'member_new',$view_data);
        }else{
            $this->template_show('member',$this->_template,'member_new');
        }
    }
    /*protected function member_template( $inter_id ){
        
    }*/
    protected function get_Token(){
        
    }
     protected function create_member_info($inter_id , $openid){
         
     }
     protected function updateWxInfo( $inter_id , $openid ){
         
     }
     
}

?>