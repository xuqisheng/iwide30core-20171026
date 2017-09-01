<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 间夜升级规则配置
 * @author liwensong
 *         @time 2017.02.23
 * @version 4.0
 *         
 */
class Adminupgrade extends MY_Admin
{
    protected $label_module = '会员中心4.0';
    protected $label_controller = '间夜升级';
    protected $label_action = '间夜升级规则配置';

    public function __construct(){
        parent::__construct();
    }

    //间夜升级规则配置
    public function rule(){
        $view_params = array();
        $this->load->model('membervip/admin/Public_model','pm');
        $this->load->helper('common_helper');
        $inter_id= $this->session->get_admin_inter_id();
        //获取等级配置
        $member_lvl = $this->pm->get_admin_member_lvl($inter_id,'member_lvl_id,lvl_name,is_default,upgrade_night,keep_night,lvl_up_sort');
        usort($member_lvl,'my_sort');

        //支付方式
        $this->load->model ( 'pay/Pay_model' );
        $pay_ways = $this->Pay_model->get_pay_way(array(
            'inter_id' => $inter_id,
            'module' => 'hotel',
            'status' => 1,
            'key' => 'value'
        ));
        $view_params['pay_ways'] = !empty($pay_ways)?json_decode(json_encode($pay_ways),true):array();

        //价格代码
        $this->load->model ( 'hotel/Price_code_model' );
        $price_codes = $this->Price_code_model->get_price_codes($inter_id,1);
        $view_params['price_codes'] = !empty($price_codes)?$price_codes:array();

        //获取规则配置
        $night_upgrade_rule = $this->pm->get_info(array('inter_id'=>$inter_id),'night_upgrade_rule');
        $calculation_default_checked = 'checked';
        $isclear_default_checked = 'checked';
        $pay_code_checked = 'checked';
        $price_code_checked = 'checked';
        $view_params['calculation'] = '';
        if(!empty($night_upgrade_rule)){
            $night_upgrade_rule['calculate_rules'] = json_decode($night_upgrade_rule['calculate_rules'],true);
            foreach ($night_upgrade_rule['calculate_rules'] as $k=>$v){
                $view_params[$k] = $v;
                if(is_array($v)){
                    if($k=='pay_code') $pay_code_checked = ''; elseif ($k=='price_code') $price_code_checked = '';
                }else{
                    if($k=='pay_code' && empty($v)) $pay_code_checked = '';elseif ($k=='price_code' && empty($v)) $pay_code_checked = '';
                }
            }
            if(!empty($night_upgrade_rule['calculate_rules']['calculation'])) $calculation_default_checked = '';
            if(!empty($night_upgrade_rule['calculate_rules']['isclear'])) $isclear_default_checked = '';
        }else{
            $pay_code_checked = '';
            $price_code_checked = '';
        }


        $view_params['pay_code_checked'] = $pay_code_checked;
        $view_params['price_code_checked'] = $price_code_checked;
        $view_params['calculation_default_checked'] = $calculation_default_checked;
        $view_params['isclear_default_checked'] = $isclear_default_checked;

        $view_params['member_lvl'] = $member_lvl;
        $view_params['expiremonth'] = !empty($night_upgrade_rule['expiremonth'])?$night_upgrade_rule['expiremonth']:'';

        $view_params['pay_code'] = !empty($night_upgrade_rule['calculate_rules']['pay_code'])?$night_upgrade_rule['calculate_rules']['pay_code']:array();
        $view_params['price_code'] = !empty($night_upgrade_rule['calculate_rules']['price_code'])?$night_upgrade_rule['calculate_rules']['price_code']:array();
        $this->_init_breadcrumb($this->label_action);
        $html = $this->_render_content($this->_load_view_file('rule'), $view_params, TRUE);
        echo $html;
    }

    public function save_rule(){
        $this->load->model('membervip/admin/Public_model','pm');
        // 获取酒店集团的信息
        $inter_id = $this->session->get_admin_inter_id();
        $post_data = $this->input->post();
        //获取等级配置
        $member_lvl = $this->pm->get_admin_member_lvl($inter_id,'member_lvl_id,lvl_name,lvl_icon,is_default,upgrade_night,keep_night,lvl_up_sort');
        if(empty($member_lvl)) $this->_ajaxReturn('没有配置平台等级!',null,0);

        //获取规则配置
        $night_upgrade_rule = $this->pm->get_info(array('inter_id'=>$inter_id),'night_upgrade_rule');
        $params['inter_id'] = $inter_id;
        $this->pm->_shard_db(true)->trans_begin(); //开启事务
        $retrun=array();
        try{
            $retrun['isadd'] = false;
            $retrun['url'] = site_url('membervip/adminupgrade/rule');
            //保持会员间夜升级规则
            foreach ($member_lvl as $key => &$data){
                $data['lvl_up_sort'] = $post_data['lvl_up_sort'][$data['member_lvl_id']];
                $data['upgrade_night'] = $post_data['upgrade_night'][$data['member_lvl_id']];
                $data['keep_night'] = $post_data['keep_night'][$data['member_lvl_id']];
                $params['pk'] = 'member_lvl_id';
                $params['member_lvl_id'] = $data['member_lvl_id'];
                $lvl_update = $this->pm->update_save($params,$data,'member_lvl');
                $retrun['result'] = $lvl_update;
                if($lvl_update===false) throw new Exception();
            }


            //保存升级间夜统计方式和会员等级资格有效期
            $pay_code = !empty($post_data['pay_code'])?$post_data['pay_code']:'';
            if(!empty($post_data['pay_code_all'])) $pay_code = $post_data['pay_code_all'];
            $price_code = !empty($post_data['price_code'])?$post_data['price_code']:'';
            if(!empty($post_data['price_code_all'])) $price_code = $post_data['price_code_all'];

            $_data = array(
                'pay_code'=>$pay_code,
                'price_code'=>$price_code,
                'calculation'=>$post_data['calculation'],
                'night'=>$post_data['night'],
                'isclear'=>$post_data['isclear'],
            );
            if(!empty($night_upgrade_rule)){
                $data = array(
                    'inter_id'=>$inter_id,
                    'calculate_rules'=>json_encode($_data),
                    'expiremonth'=>$post_data['expiremonth'],
                );
                $params['pk'] = 'id';
                $params['id'] = $night_upgrade_rule['id'];
                $rule_update = $this->pm->update_save($params,$data,'night_upgrade_rule');
                $retrun['result'] = $rule_update;
                if($rule_update===false) throw new Exception();
            }else{
                $retrun['isadd'] = true;
                $save_data = array(
                    'inter_id'=>$inter_id,
                    'calculate_rules'=>json_encode($_data),
                    'expiremonth'=>$post_data['expiremonth'],
                    'createtime'=>date('Y-m-d H:i:s')
                );
                $rule_add = $this->pm->add_data($save_data,'night_upgrade_rule');
                $retrun['result'] = $rule_add;
                if(!$rule_add) throw new Exception();
            }

            $this->pm->_shard_db(true)->trans_commit();// 事务提交
            $this->_ajaxReturn('保存成功!',$retrun,1);
        }catch (Exception $e){
            $this->pm->_shard_db(true)->trans_rollback(); //回滚事务
            $this->_ajaxReturn('保存失败!',null,0);
        }
    }
}
?>