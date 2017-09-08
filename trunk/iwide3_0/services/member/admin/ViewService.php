<?php

namespace App\services\member\admin;

use App\services\MemberBaseService;
use MYLOG;

/**
 * Class ViewService
 * @package App\services\member
 * @author liwensong [septet-l@outlook.com]
 */
class ViewService extends MemberBaseService
{
    protected $saler_protected = false;
    protected $saler_protected_info = array();
    protected $saler_id = 0;
    protected $res_data = array();

    public function __construct()
    {
        $this->res_data = array(
            'status'=>2,
            'data'=>array()
        );
    }

    /**
     * 获取服务实例方法
     * @return ViewService
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }

    public function index($inter_id = ''){
        $data = array();
        $this->getCI()->load->model('membervip/admin/Public_model','mem_public_model');
        $this->getCI()->load->model('membervip/admin/config/attribute_model','ui_model');

        //获取酒店集团的信息
        if(!empty($inter_id)) {
            $this->getCI()->load->model('wx/Publics_model');
            $public = $this->getCI()->Publics_model->get_public_by_id($inter_id);
            $data['public'] = $public;
        }else{
            return array();
        }

        //会员中心链接
        $data['member_center_link'] = str_replace('vapi/', '', PMS_PATH_URL).'tool/qr/get?str='."http://".$public['domain']."/index.php/membervip/center?id=".$public['inter_id'];

        //栏目设置
        $post_data = array(
            'inter_id' => $inter_id
        );
        $custom_config =  $this->doCurlPostRequest( PMS_PATH_URL."adminmember/get_custom_field_rule" , $post_data );
        if(isset($custom_config['value']) && !empty($custom_config['value'])){
            $data['custom_config'] = json_decode($custom_config['value'],true);
            $data['custom_config']['config_id'] = $custom_config['id'];
        }else{
            $data['custom_config'] = self::getDefaultColumn();
        }

        //订单入口
        $where = array(
            'inter_id' => $inter_id,
            'type_code' => 'order_gate'
        );
        $order_gate_data = $this->getCI()->mem_public_model->get_info($where,'inter_member_config');
        $order_gate = array();
        if(!empty($order_gate_data)){
            $order_gate = json_decode($order_gate_data['value'],true);
        }else{
            $order_gate = array(
                'hotel_switch' => 't',
                'shop_switch' => 't'
            );
        }

        //菜单栏目
        $icon_conf = $this->getCI()->ui_model->get_uiicon();
        $data['icon_conf'] = $icon_conf;
        $nav_info = $this->getCI()->mem_public_model->get_info(array('inter_id'=>$inter_id),'member_nav');
        $nav_conf = array();
        if(isset($nav_info['nav_conf']) && !empty($nav_info['nav_conf'])) $nav_conf = json_decode($nav_info['nav_conf'],true);
        $data['nav_conf'] = $nav_info;


        $this->res_data['status'] = 1;
        $this->res_data['msg_lvl'] = 1;
        $this->res_data['msg'] = 'ok';
        $this->res_data['data'] = $data;
        return $this->res_data;
    }

    private static function getDefaultColumn(){
        $conf = array(
            'balance_use' => 't',
            'credit_use' => 't',
            'balance' => array(
                'default' => 'f',
                'name' => '余额'
            ),
            'credit' => array(
                'default' => 'f',
                'name' => '积分'
            ),
        );
        return $conf;
    }
}