<?php

/**
 * Created by blackkaffa.
 * User: ibuki
 * Date: 16/8/10
 * Time: 上午11:28
 */
class Buycardrecord extends MY_Admin_Member {
    protected $admin_info = '';
    protected $label_module = '会员中心4.0';
    protected $label_controller = '会员购卡';
    protected $label_action = '购卡记录';

    protected function main_model_name()
    {
        return 'member/Member_buycardrecord_model';
    }

    //会员购卡列表
    public function index(){
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
        $params = [];
        if(is_array($get_filter)) {
            $params = $get_filter + $params;
        }

        $this->load->model('membervip/admin/Public_model','pum');

        $params['table_name'] = 'deposit_card_pay';
        $params['dcp.inter_id'] = $admin_profile['inter_id'];
        $params['dcp.pay_status'] = 't';
        $params['alias'] = "dcp";
        $select = [];
        $params['sort_field'] = 'dcp.createtime';
        $params['sort_direct'] = 'desc';

        //排序字段
        $order_columns = ['dcp.deposit_card_pay_id','dcp.order_num','dcp.member_lvl_name','dcp.name','dcp.nickname','dcp.membership_number','dcp.pay_money','dcp.deposit','dcp.credit','dcp.card_count','dcp.distribution_num','dcp.createtime'];

        if(isset($params['order']) && !empty($params['order'])){
            $params['sort_field'] = $order_columns[$params['order'][0]['column']];
            $params['sort_direct'] = $params['order'][0]['dir'];
            if(isset($params['order'][1]) && !empty($params['order'][1])){
                $params['sort_field'] = $order_columns[$params['order'][1]['column']];
                $params['sort_direct'] = $params['order'][1]['dir'];
            }
        }

        $params['opt'] = 11;
        $params['ui_type'] = 11;
        $params['ispackage'] = 0;
        $params['f_type'] = 11;

        $inter_id = $admin_profile['inter_id'];
        $counts = $this->pum->_shard_db()->select('COUNT(deposit_card_pay_id) as count')
            ->where(['inter_id'=>$inter_id])
            ->get('deposit_card_pay')
            ->row_array();
        $result['data'] = [];
        $result['total'] = !empty($counts['count'])?$counts['count']:0;
        if(is_ajax_request()){
            //处理ajax请求
            $params['page_size'] = 20;
            $result = $this->pum->get_admin_filter($params,$select);
            echo json_encode($result);exit;
        }else{
            //HTML输出
            if( !$this->label_action ) $this->label_action= '会员购卡记录';
            $this->_init_breadcrumb($this->label_action);

            //base grid data..
            $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
            if($result['total'] < $num) $result = $this->pum->get_admin_filter($params,$select);
//            $result = $this->pum->get_admin_filter($params,$select);
            $this->load->model('membervip/admin/config/attribute_model','ui_model');
            $fields_config = $this->ui_model->get_field_config('grid',$params['f_type']);
            $default_sort= ['field'=>'createtime', 'sort'=>$params['sort_direct']];
            $view_params= [
                'module'=> $this->ui_model,
                'model'=> $this->pum,
                'result'=> $result,
                'fields_config'=> $fields_config,
                'default_sort'=> $default_sort,
                'get'=>$get_filter
            ];
            $html = $this->_render_content($this->_load_view_file('index'), $view_params, TRUE);
            echo $html;
        }
    }
}