<?php

namespace App\services\member\admin;

use App\services\MemberBaseService;
use MYLOG,EA_const_url;

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
            'status' => 2,
            'data' => array()
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

    /**
     * 会员中心显示配置
     * @param string $inter_id 酒店集团ID
     * @return array
     */
    public function index($inter_id = '')
    {
        $data = array();
        $this->getCI()->load->model('membervip/admin/Public_model', 'mem_public_model');
        $this->getCI()->load->model('membervip/admin/config/attribute_model', 'ui_model');

        //获取皮肤配置
        $info = $this->getCI()->mem_public_model->get_info(array('inter_id'=>$inter_id,'type_code'=>'membertemplate'),'inter_member_config');
        $template = !empty($info['value']) ? $info['value'] : 'phase2';
        $data['template'] = $template;

        //获取酒店集团的信息
        if (!empty($inter_id)) {
            $this->getCI()->load->model('wx/Publics_model');
            $public = $this->getCI()->Publics_model->get_public_by_id($inter_id);
//            $data['public'] = $public;
        } else {
            return array();
        }

        //会员中心链接
        $data['member_center_qr'] = str_replace('vapi/', '', PMS_PATH_URL) . 'tool/qr/get?str=' . "http://" . $public['domain'] . "/index.php/membervip/center?id=" . $public['inter_id'];
        $data['member_center_link'] = "http://" . $public['domain'] . "/index.php/membervip/center?id=" . $public['inter_id'];

        //栏目设置
        $post_data = array(
            'inter_id' => $inter_id
        );
        $custom_config = $this->doCurlPostRequest(PMS_PATH_URL . "adminmember/get_custom_field_rule", $post_data);
        if (isset($custom_config['value']) && !empty($custom_config['value'])) {
            $data['custom_config'] = json_decode($custom_config['value'], true);
            $data['custom_config']['config_id'] = $custom_config['id'];
        } else {
            $data['custom_config'] = PublicService::getDefaultColumn();
        }

        if(!PublicService::getAuth(1,$template)){
            unset($data['custom_config']['balance_use']);
            unset($data['custom_config']['credit_use']);
        }

        //订单入口
        if(PublicService::getAuth(3,$template)){
            $where = array(
                'inter_id' => $inter_id,
                'type_code' => 'order_gate'
            );
            $order_gate_data = $this->getCI()->mem_public_model->get_info($where, 'inter_member_config');
            $order_gate = array();
            if (!empty($order_gate_data)) {
                $order_gate = json_decode($order_gate_data['value'], true);
            } else {
                $order_gate = array(
                    'hotel_switch' => 't',
                    'shop_switch' => 't'
                );
            }

            $data['order_gate'] = $order_gate;
        }


        //颜色设置
        if(PublicService::getAuth(4,$template)){
            $color_default_conf = array(
                'banner_color' => 'rgba(251, 213, 50)',
                'button_color' => 'rgba(251, 213, 50)',
                'button_text_color' => 'rgba(72, 152, 191)'
            );
            $data['color_default_conf'] = $color_default_conf;
        }

        //置顶广告栏设置
        if(PublicService::getAuth(10,$template)){
            $ad_conf = array(
                'ad_text' => '',
                'ad_logo' => ''
            );
            $data['ad_conf'] = $ad_conf;
        }

        //菜单栏目
        $icon_conf = $this->getCI()->ui_model->get_uiicon();
        $data['icon_conf'] = $icon_conf;
        $nav_info = $this->getCI()->mem_public_model->get_info(array('inter_id' => $inter_id), 'member_nav');
        $nav_conf = array();
        if (isset($nav_info['nav_conf']) && !empty($nav_info['nav_conf'])) $nav_conf = json_decode($nav_info['nav_conf'], true);
        if(!empty($nav_conf) && self::getMaxArrDim($nav_conf) > 2){
            $_nav_conf = $nav_conf;
            $nav_conf = array();
            foreach ($_nav_conf as $item){
                foreach ($item as $_menu){
                    $nav_conf[] = $_menu;
                }
            }
        }

        $this->getCI()->load->helper('member_helper');
        usort($nav_conf,'my_listorder');
        $data['nav_conf'] = $nav_conf;

        //模块链接
        $module_link = PublicService::get_module_link($inter_id);
        $data['module_link'] = $module_link;

        $data['page_field'] = array(
            'column' => array(
                'credit' => array(
                    'credit_use',
                    'credit_default_custom',
                    'credit_custom_value'
                ),
                'balance' => array(
                    'balance_use',
                    'balance_default_custom',
                    'balance_custom_value'
                ),
            ),
            'nav' => array(
                'listorder[]',
                'icon[]',
                'modelname[]',
                'modelurl[]',
                'link[]',
                'is_login[]'
            ),
        );

        $this->res_data['status'] = 1;
        $this->res_data['msg_lvl'] = 1;
        $this->res_data['msg'] = 'ok';
        $this->res_data['data'] = $data;
        return $this->res_data;
    }

    /**
     * 皮肤配置
     * @param string $inter_id 酒店集团ID
     * @return array
     */
    public function skin($inter_id = '')
    {
        $post_tem_url = PMS_PATH_URL . "member/member_template";
        $post_tem_data = array(
            'inter_id' => $inter_id,
            'openid' => 'admin',
        );
        $result = $this->doCurlPostRequest($post_tem_url, $post_tem_data);
        $result = $this->parse_curl_msg($result);
        $template = $result['data'];

        $theme_mapping = array(
            'version4' => '普通版',
            'phase2' => '黄色版',
            'highclass' => '高端黑',
            'highclass#white' => '高端白'
        );

        $data['template'] = $template;
        $data['themes'] = $theme_mapping;
        $data['member_center_qr'] = str_replace('vapi/', '', PMS_PATH_URL) . 'tool/qr/get?str=' . "http://" . $public['domain'] . "/index.php/membervip/center?id=" . $public['inter_id'];
        $this->res_data['status'] = 1;
        $this->res_data['msg_lvl'] = 1;
        $this->res_data['msg'] = 'ok';
        $this->res_data['data'] = $data;
        return $this->res_data;
    }

    /**
     * 保存会员中心显示配置
     * @param string $inter_id 酒店集团ID
     * @return array
     */
    public function save_post($inter_id = ''){
        if(is_ajax_request()){
            if(empty($this->getCI()->input->post())){
                $this->res_data['status'] = 3;
                $this->res_data['msg'] = '保存数据为空,请检查输入是否有误';
                return $this->res_data;
            }

            $this->getCI()->load->model('membervip/admin/Public_model', 'mem_public_model');

            $post_data = $this->getCI()->input->post();
            $postData = array();


            // <-- 保存栏目配置start -->
            //启用余额
            if(!isset($post_data['balance_use']) || $post_data['balance_use'] != 'f'){
                $postData['balance_use'] = 't';
            }else{
                $postData['balance_use'] = 'f';
            }
            //启用积分
            if(!isset($post_data['credit_use']) || $post_data['credit_use'] != 'f'){
                $postData['credit_use'] = 't';
            }else{
                $postData['credit_use'] = 'f';
            }
            if($post_data['credit_default_custom'] == 'f' && !empty($post_data['credit_custom_value'])) {
                $postData['credit']['default'] = 'f';
                $postData['credit']['name'] = $post_data['credit_custom_value'];
            } else{
                $postData['credit']['default'] = 't';
                $postData['credit']['name'] = '积分';
            }
            if($post_data['balance_default_custom'] == 'f' && !empty($post_data['balance_custom_value'])) {
                $postData['balance']['default'] = 'f';
                $postData['balance']['name'] = $post_data['balance_custom_value'];
            } else{
                $postData['balance']['default'] = 't';
                $postData['balance']['name'] = '余额';
            }
            $postData['inter_id'] = $inter_id;

            $where = array(
                'inter_id' => $inter_id
            );
            $column_info = $this->getCI()->mem_public_model->get_info($where,'member_custom_name');

            if(!empty($column_info)){
                $params['inter_id'] = $inter_id;
                $postData['config_id'] = $column_info['id'];
                $sdata = array(
                    'value'     => json_encode($postData),
                );
                $result = $this->getCI()->mem_public_model->update_save($params,$sdata,'member_custom_name');
            }else{
                $sdata = array(
                    'inter_id' => $inter_id,
                    'value'     => json_encode($postData),
                    'createtime' => date('Y-m-d H:i:s')
                );
                $result = $this->getCI()->mem_public_model->add_data($sdata,'member_custom_name');
            }

            if($result===false){
                $this->res_data['status'] = 3;
                $this->res_data['msg'] = '栏目设置保存失败';
                return $this->res_data;
            }
            //<-- 保存栏目配置end -->

            //<-- 保存订单入口配置start -->
            $order_gate_data = array(
                'hotel_switch' => !empty($post_data['hotel_switch']) ? $post_data['hotel_switch'] : 't',
                'shop_switch' => !empty($post_data['shop_switch']) ? $post_data['shop_switch'] : 't',
            );

            $where = array(
                'inter_id' => $inter_id,
                'type_code' => 'order_gate'
            );
            $order_gate_info = $this->getCI()->mem_public_model->get_info($where, 'inter_member_config');
            if(!empty($order_gate_info)){
                $sdata = array(
                    'value'     => json_encode($order_gate_data),
                );
                $result = $this->getCI()->mem_public_model->update_save($where,$sdata,'inter_member_config');
            }else{
                $sdata = array(
                    'inter_id' => $inter_id,
                    'value'     => json_encode($order_gate_data),
                    'type_code' => 'order_gate',
                    'sync_config' => 0,
                    'createtime' => time()
                );
                $result = $this->getCI()->mem_public_model->add_data($sdata,'inter_member_config');
            }

            if($result===false){
                $this->res_data['status'] = 3;
                $this->res_data['msg'] = '订单入口配置保存失败';
                return $this->res_data;
            }
            //<-- 保存订单入口配置end -->


            //<-- 保存颜色配置start -->
            $where = array(
                'inter_id' => $inter_id,
                'type_code' => 'color_conf'
            );
            $color_conf = $this->getCI()->mem_public_model->get_info($where, 'inter_member_config');

            $color_conf = array(
                'button' => !empty($post_data['button_color']) ? $post_data['button_color'] : 'rgba(251, 213, 50)',
                'button_text' => !empty($post_data['button_text_color']) ? $post_data['button_text_color'] : 'rgba(72, 152, 191)',
            );

            if(!empty($color_conf)){
                $sdata = array(
                    'value'     => json_encode($color_conf),
                );
                $result = $this->getCI()->mem_public_model->update_save($where,$sdata,'inter_member_config');
            }else{
                $sdata = array(
                    'inter_id' => $inter_id,
                    'value'     => json_encode($color_conf),
                    'type_code' => 'color_conf',
                    'sync_config' => 0,
                    'createtime' => time()
                );
                $result = $this->getCI()->mem_public_model->add_data($sdata,'inter_member_config');
            }
            if($result===false){
                $this->res_data['status'] = 3;
                $this->res_data['msg'] = '颜色设置保存失败';
                return $this->res_data;
            }
            //<-- 保存颜色配置end -->

            //<-- 置顶广告栏设置start -->
            $where = array(
                'inter_id' => $inter_id,
                'type_code' => 'TOP_AD'
            );
            $top_ad_conf = $this->getCI()->mem_public_model->get_info($where, 'inter_member_config');

            $top_ad_conf_data = array(
                'ad_text' => !empty($post_data['ad_text']) ? $post_data['ad_text'] : '',
                'ad_logo' => !empty($post_data['ad_logo']) ? $post_data['ad_logo'] : '',
            );

            if(!empty($top_ad_conf)){
                $sdata = array(
                    'value'     => json_encode($top_ad_conf_data),
                );
                $result = $this->getCI()->mem_public_model->update_save($where,$sdata,'inter_member_config');
            }else{
                $sdata = array(
                    'inter_id' => $inter_id,
                    'value'     => json_encode($top_ad_conf_data),
                    'type_code' => 'TOP_AD',
                    'sync_config' => 0,
                    'createtime' => time()
                );
                $result = $this->getCI()->mem_public_model->add_data($sdata,'inter_member_config');
            }
            if($result===false){
                $this->res_data['status'] = 3;
                $this->res_data['msg'] = '置顶广告栏设置保存失败';
                return $this->res_data;
            }
            //<-- 置顶广告栏设置end -->

            //<-- 保存菜单设置start -->
            $nav_post = array();
            foreach ($post_data['listorder'] as $knum => $post_value){
                if(empty($post['modelname'][$knum])){
                    $this->res_data['status'] = 3;
                    $this->res_data['msg'] = '菜单名称不能为空';
                    return $this->res_data;
                }

                if(empty($post['link'][$knum])){
                    $this->res_data['status'] = 3;
                    $this->res_data['msg'] = '菜单链接不能为空';
                    return $this->res_data;
                }

                $nav_post[$knum] = array(
                    'icon' => !empty($post['icon'][$knum]) ? $post['icon'][$knum] : '',
                    'modelname' => !empty($post['modelname'][$knum]) ? $post['modelname'][$knum] : '',
                    'modelurl' => !empty($post['modelurl'][$knum]) ? $post['modelurl'][$knum] : '',
                    'link' => !empty($post['link'][$knum]) ? $post['link'][$knum] : '',
                    'is_login' => !empty($post['is_login'][$knum]) ? $post['is_login'][$knum] : '',
                    'listorder' => $post_value
                );
            }

            if(!empty($nav_post)){
                $info = $this->getCI()->mem_public_model->get_info(array('inter_id'=>$inter_id),'member_nav');
                $sdata = array();
                $sdata['nav_conf'] = json_encode($nav_post);
                $sdata['inter_id'] = $inter_id;
                $sdata = $this->getCI()->mem_public_model->check_list_fields($sdata,'member_nav');

                $params['inter_id'] = $inter_id;
                if(!empty($info)){
                    $params['nav_id'] = $info['nav_id'];
                    $result = $this->getCI()->mem_public_model->update_save($params,$sdata,'member_nav');
                }else{
                    $sdata['createtime'] = time();
                    $result = $this->getCI()->mem_public_model->add_data($sdata,'member_nav');
                }

                if($result===false){
                    $this->res_data['status'] = 3;
                    $this->res_data['msg'] = '菜单设置保存失败';
                    return $this->res_data;
                }
            }
            //<-- 保存菜单设置end -->

            $this->res_data['status'] = 1;
            $this->res_data['msg_lvl'] = 1;
            $this->res_data['msg'] = '保存成功';
            return $this->res_data;
        }else{
            $this->res_data['status'] = 3;
            $this->res_data['msg'] = '请求失败，请联系管理员';
            return $this->res_data;
        }
    }

    /**
     * 保存皮肤配置
     * @param string $inter_id 酒店集团ID
     * @return array
     */
    public function save_skin($inter_id = ''){
        $this->getCI()->load->model('membervip/admin/Public_model', 'mem_public_model');
        $post_data = $this->getCI()->input->post();
        if(empty($post_data) OR empty($post_data['theme'])) {
            $this->res_data['status'] = 3;
            $this->res_data['msg'] = '保存失败，没有选择皮肤';
            return $this->res_data;
        }

        $info = $this->getCI()->mem_public_model->get_info(array('inter_id'=>$inter_id,'type_code'=>'membertemplate'),'inter_member_config');

        if(!empty($info)){
            $params['inter_id'] = $inter_id;
            $params['member_inter_config_id'] = $info['member_inter_config_id'];
            $sdata = array(
                'type_code'=>'membertemplate',
                'value'     => $post_data['theme']
            );
            $result = $this->getCI()->mem_public_model->update_save($params,$sdata,'inter_member_config');
        }else{
            $sdata = array(
                'inter_id' => $inter_id,
                'type_code'=>'membertemplate',
                'value'     => $post_data['theme']
            );
            $result = $this->getCI()->mem_public_model->add_data($sdata,'inter_member_config');
        }

        if($result === 0){
            $this->res_data['status'] = 3;
            $this->res_data['msg'] = '没有更改任何数据';
            return $this->res_data;
        }elseif($result===false){
            $this->res_data['status'] = 3;
            $this->res_data['msg'] = '保存失败';
            return $this->res_data;
        }

        $data['jump_url'] = site_url('membervip/view/skin');

        $this->res_data['status'] = 1;
        $this->res_data['msg_lvl'] = 1;
        $this->res_data['msg'] = '保存成功';
        return $this->res_data;
    }

    private static function getMaxArrDim($arr = array()){
        if(empty($arr) OR !is_array($arr)) return 0;
        $dim = 0;
        foreach ($arr as $item){
            $level = self::getMaxArrDim($item);
            if($level > $dim) $dim = $level;
        }
        return $dim + 1;
    }
}