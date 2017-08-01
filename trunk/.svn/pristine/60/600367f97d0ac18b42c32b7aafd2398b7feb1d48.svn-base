<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 积分兑换储值
 *
 * @author 杨成峰
 * @copyright www.iwide.cn
 * @version 4.0
 *          @Email 445315045@qq.com
 *         
 */
class Exchange extends MY_Front_Member
{

    protected $_token;

    public $ratio = 0.02;
    // 兑换比例，即一积分等于多少储值
    protected $member_info = null;

    function __construct()
    {
        parent::__construct();
        $this->get_member_info();
        if (empty($this->member_info))
            redirect('membervip/center/index?id=' . $this->inter_id);
    }

    public function index()
    {
        // 兑换展示页
        $data['member_info'] = $this->member_info;
        $data['ratio'] = $this->ratio;
        $data['credit_name']=$this->_template_filed_names['credit_name'];
        $data['balance_name']=$this->_template_filed_names['balance_name'];
        $this->template_show('member',$this->_template,'exchange',$data);
    }

    public function credit_exchange()
    {
        $exchange_credit = $_POST['credit'];
        $balance = $exchange_credit * $this->ratio;
        if ($exchange_credit > $this->member_info['credit']) {
            $this->_ajaxReturn("兑换数额不能大于拥有{$this->_template_filed_names['credit_name']}的数额", '', 40003);
        }
        $post_credit_url = INTER_PATH_URL . "credit/useoff";
        $post_credit_data = array(
            'remark' => $exchange_credit . $this->_template_filed_names['credit_name'].'兑换' . $balance . $this->_template_filed_names['balance_name'],
            'inter_id' => $this->inter_id,
            'openid' => $this->openid,
            'module' => 'vip',
            'uu_code' => time() . rand(0, 9999),
            'count' => $exchange_credit
        );
        $post_credit = $this->doCurlPostRequest($post_credit_url, $post_credit_data);
        if ($post_credit['err'] == '0') {
            // 成功扣除积分，执行增加储值流程
            
            $post_balance_url = INTER_PATH_URL . "deposit/add";
            $post_balance_data = array(
                'note' =>$exchange_credit . $this->_template_filed_names['credit_name'].'兑换' . $balance . $this->_template_filed_names['balance_name'],
                'inter_id' => $this->inter_id,
                'member_info_id' => $this->member_info['member_info_id'],
                'openid' => $this->openid,
                'count' => $balance,
                'module' => 'vip',
                'uu_code' => time() . rand(0, 9999),
                'count' => $balance
            );
            $post_credit = $this->doCurlPostRequest($post_balance_url, $post_balance_data);
            if ($post_credit['err'] == '0') {
                $this->_ajaxReturn('兑换成功', '', 0);
            } else {
                // 增加储值失败，回滚积分
                $this->get_Token();
                $url = INTER_PATH_URL . 'credit/add';
                $give_back_data = array(
                    'token' => $this->_token,
                    'inter_id' => $this->inter_id,
                    'member_info_id' => $this->member_info['member_info_id'],
                    'count' => $exchange_credit,
                    'module' => 'vip',
                    'scene' => 'vip',
                    'uu_code' => uniqid() . time(),
                    $exchange_credit . $this->_template_filed_names['credit_name'].'兑换' . $balance . $this->_template_filed_names['balance_name'],
                    
                    'remark' => "兑换$this->_template_filed_names['balance_name']失败，回滚$this->_template_filed_names['credit_name']"
                );
                $result = $this->doCurlPostRequest( $url , $give_back_data );
                
            }
        }
        
        $this->_ajaxReturn('兑换失败', '', 40003);
    }

    protected function get_member_info()
    {
        $this->load->model('wx/Publics_model');
        $data['info'] = $this->Publics_model->get_fans_info($this->openid);
        $post_center_url = PMS_PATH_URL . "member/center";
        $post_center_data = array(
            'inter_id' => $this->inter_id,
            'openid' => $this->openid
        );
        // 获取用户信息
        $data = $this->doCurlPostRequest($post_center_url, $post_center_data);
        $this->member_info = ! empty($data['data']) ? $data['data'] : null;
    }
}

?>