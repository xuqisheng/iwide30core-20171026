<?php
class MY_Admin_Member extends MY_Admin {
    public $db_shard_config= array();
    public $current_inter_id= '';
    public $view_file= '';

    public function __construct(){
        parent::__construct();
    }

    public function _grid($filter= array(), $viewdata=array()) {
        //print_r($filter);die;
        $model_name= $this->main_model_name();
        $model= $this->_load_model($model_name);

        //filter params: the same with table fields...
        //sort params: sort_direct, sort_field
        //page params: page_size, page_num
        $params= $this->input->get();
        if(is_array($filter) && count($filter)>0 )
            $params= array_merge($params, $filter);

        if(is_ajax_request()){
            //处理ajax请求
            $result= $model->filter_json($params );
            echo json_encode($result);

        } else {
            //HTML输出
            if( !$this->label_action ) $this->label_action= '信息列表';
            $this->_init_breadcrumb($this->label_action);

            //base grid data..
            $result= $model->filter($params);
            $fields_config= $model->get_field_config('grid');
            $default_sort= $model::default_sort_field();
            //print_r($fields_config);die;

            $view_params= array(
                'module'=> $this->module,
                'model'=> $model,
                'result'=> $result,
                'fields_config'=> $fields_config,
                'default_sort'=> $default_sort,
            );

            $view_params= $view_params+ $viewdata;

            $view_file= $this->view_file? $this->view_file: 'grid';
            $html= $this->_render_content($this->_load_view_file($view_file), $view_params, TRUE);
            //echo $html;die;
            echo $html;
        }
    }

    /**
     * 删除数据
     */
    public function delete() {
        try {
            $model_name= $this->main_model_name();
            $model= $this->_load_model($model_name);

            $ids= explode(',', $this->input->get('ids'));
            $result= $model->delete_in($ids);
            $this->_log($model);

            if( $result ){
                $this->session->put_success_msg("删除成功");

            } else {
                $this->session->put_error_msg('删除失败');
            }

        } catch (Exception $e) {
            $message= '删除失败过程中出现问题！';
            //$message= $e->getMessage();
            $this->session->put_error_msg('删除失败');
        }
        $url= EA_const_url::inst()->get_url('*/*/grid');
        $this->_redirect($url);
    }

    public function _get_real_inter_id( $is_prevent=FALSE ) {
        $inter_id= $this->session->get_temp_inter_id();
        if( !$inter_id ) $inter_id= $this->session->get_admin_inter_id();

        if( $inter_id==FULL_ACCESS && $is_prevent ) {
            $this->session->put_error_msg('不能用跨公众号账号进行此操作。');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
        } else {
            return $inter_id;
        }
    }
}