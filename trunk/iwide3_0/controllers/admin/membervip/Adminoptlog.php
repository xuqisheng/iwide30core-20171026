<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 后台用户中心配置
 *
 * @author Frandon
 *         @time 三月三十一号
 * @version www.iwide.cn
 *          @
 *         
 */
class Adminoptlog extends MY_Admin
{
    protected $label_module = '会员中心4.0';
    protected $label_controller = '邀请好友';
    protected $label_action = '显示设置';

    public function __construct(){
        parent::__construct();
    }

    //邀请好友统计
    public function index(){
        $type = array('invite_viewconf','invite_level_equity','invite_settings');
        $this->label_action= '邀请好友操作记录';
        $result = $this->get_grid($type);
        if(is_ajax_request()){
            echo json_encode($result);
        }else{
            $html = $this->_render_content($this->_load_view_file('index'), $result, TRUE);
            echo $html;
        }
    }


    //优惠券配置操作记录
    public function coupon(){
        $type = 'coupon';
        $this->label_action= '优惠券配置操作记录';
        $result = $this->get_grid($type);
        if(is_ajax_request()){
            echo json_encode($result);
        }else{
            $html = $this->_render_content($this->_load_view_file('index'), $result, TRUE);
            echo $html;
        }
    }


    //大礼包配置操作记录
    public function package(){
        $type = 'package';
        $this->label_action= '大礼包配置操作记录';
        $result = $this->get_grid($type);
        if(is_ajax_request()){
            echo json_encode($result);
        }else{
            $html = $this->_render_content($this->_load_view_file('index'), $result, TRUE);
            echo $html;
        }
    }

    public function verification(){
        $type = 'verification';
        $this->label_action= '优惠券核销操作记录';
        $result = $this->get_grid($type);
        if(is_ajax_request()){
            echo json_encode($result);
        }else{
            $html = $this->_render_content($this->_load_view_file('index'), $result, TRUE);
            echo $html;
        }
    }

    //会员解绑操作记录
    public function memunbind(){
        $type = 'member_unbind';
        $this->label_action= '会员解绑操作记录';
        $result = $this->get_grid($type);
        if(is_ajax_request()){
            echo json_encode($result);
        }else{
            $html = $this->_render_content($this->_load_view_file('index'), $result, TRUE);
            echo $html;
        }
    }

    public function get_grid($type = ''){
        if(empty($type)) return array();
        $admin_profile = $this->session->userdata('admin_profile');

        /* 兼容grid变为ajax加载加这一段 */
        if(is_ajax_request()){
            //处理ajax请求，参数规格不一样
            $get_filter= $this->input->post();
            $_get_filter= $this->input->get();
            if(!empty($_get_filter) && is_array($_get_filter)) $get_filter = $get_filter + $_get_filter;
        }else
            $get_filter= $this->input->get();

        if( !$get_filter) $get_filter = $this->input->get('filter');

        $params['ol.inter_id'] = $admin_profile['inter_id'];
        $params['ol.log_type'] = $type;

        if(is_array($get_filter)) {
            $params = $get_filter + $params;
        }

        $inter_id = $this->session->get_admin_inter_id();
        $this->load->model('membervip/admin/Public_model','pum');

        $params['table_name'] = 'admin_operation_log';
        $params['alias'] = "ol";

        $select = array('ol.*');
        $params['sort_field'] = 'ol.createtime';
        $params['sort_direct'] = 'desc';

        //排序字段
        $order_columns = array('ol.log_title','ol.log_type','ol.content','ol.createtime','ol.admin_id','ol.result');
        if(isset($params['order']) && !empty($params['order'])){
            $params['sort_field'] = $order_columns[$params['order'][0]['column']];
            $params['sort_direct'] = $params['order'][0]['dir'];
            if(isset($params['order'][1]) && !empty($params['order'][1])){
                $params['sort_field'] = $order_columns[$params['order'][1]['column']];
                $params['sort_direct'] = $params['order'][1]['dir'];
            }
        }
        $params['opt'] = 6;
        $params['ui_type'] = 7;
        $params['f_type'] = 7;

        $counts = $this->pum->_shard_db()->query("SELECT COUNT(log_id) as count FROM iwide_admin_operation_log WHERE inter_id = '$inter_id'")->row_array();
        $result['data'] = array();
        $result['total'] = $counts['count'];
        if(is_ajax_request()){
            //处理ajax请求
            $params['page_size'] = 20;
            $result = $this->pum->get_admin_filter($params,$select);
            return $result;
        }else{
            //HTML输出

            $this->_init_breadcrumb($this->label_action);

            //base grid data..
            if($result['total'] < 500) $result = $this->pum->get_admin_filter($params,$select);
            $this->load->model('membervip/admin/config/attribute_model','ui_model');
            $_moedel = $this->ui_model;
            $fields_config = $_moedel->get_field_config('grid',$params['ui_type']);
            $default_sort= array('field'=>'createtime', 'sort'=>$params['sort_direct']);
            $view_params= array(
                'module'=> $this->ui_model,
                'model'=> $this->pum,
                'result'=> $result,
                'fields_config'=> $fields_config,
                'default_sort'=> $default_sort,
                'get'=>$get_filter,
            );
            return $view_params;
        }
    }
}
?>