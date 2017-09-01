<?php

class MY_Admin_Cprice extends MY_Admin {

    protected $label_module= '';		//统一在 constants.php 定义

    public function c_grid($filter= array(), $viewdata=array(),$linkpath)
    {
        $model_name= $this->main_model_name();
        $model= $this->_load_model($model_name);

//        print_r($model_name);exit;

        //filter params: the same with table fields...
        //sort params: sort_direct, sort_field
        //page params: page_size, page_num
        $params= $this->input->get();

        if($this->input->get('ids')){

            if($model_name=='company/Staff_list_model'){
                $params['cp_id']=$params['ids'];
            }else{
                $params['company_id']=$params['ids'];
            }
        }

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

            $view_params= array(
                'module'=> $this->module,
                'model'=> $model,
                'result'=> $result,
                'fields_config'=> $fields_config,
                'default_sort'=> '',
            );

//            $view_params= $view_params+ $viewdata;
            $html= $this->_render_content($this->_load_view_file($linkpath), $view_params, TRUE);
            //echo $html;die;
            echo $html;
        }
    }


    public function m_grid($filter= array(), $viewdata=array())
    {
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
            $result= $this->m_filter($params);
            $fields_config= $model->get_field_config('grid');
            $default_sort= $model::default_sort_field();

            $view_params= array(
                'module'=> $this->module,
                'model'=> $model,
                'result'=> $result,
                'fields_config'=> $fields_config,
                'default_sort'=> $default_sort,
            );
            $view_params= $view_params+ $viewdata;

            $html= $this->_render_content($this->_load_view_file('grid'), $view_params, TRUE);
            //echo $html;die;
            echo $html;
        }
    }


    public function m_filter( $params=array(), $select= array(), $format='array',$table_list=array() )
    {

        $model_name= $this->main_model_name();
        $model= $this->_load_model($model_name);

        $table= $model->table_name();
        $where= $where_in= array();
        $dbfields= array_values($fields= $model->_shard_db()->list_fields($table));
        foreach ($params as $k=>$v){
            //过滤非数据库字段，以免产生sql报错，把in匹配另外处理
            if(in_array($k, $dbfields) ){
                if( is_array($v) ){
                    $where_in[$k]= $v;
                } else {
                    $where[$k]= $v;
                }
            }
        }

        if( isset($params['sort_field']) && isset($params['sort_direct']) ){
            $sort= $params['sort_field']. ' '. $params['sort_direct'];
        } else
            $pk= $model->table_primary_key();
        $sort= "{$pk} DESC";  //默认排序

        $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
        $page_size= isset($params['page_size'])? $params['page_size']: $num;
        $current_page= isset($params['page_num'])? $params['page_num']: 1;


        if(!empty($params['sql'])){
            $select= $model->grid_fields();
            $select= implode(',', $select);
        }else{
            if(count($select)==0) {
                $select= $model->grid_fields();
            }
            $select= count($select)==0? '*': implode(',', $select);
            $total= $model->_shard_db()->select(" {$select} ")->get_where($table, $where)->num_rows();
            //echo $total;
        }

        //echo $select;die;
        $offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;
        if( count($where_in)>0 ){
            foreach ($where_in as $k => $v ){
                if( count($v) ) $model->_shard_db()->where_in($k, $v);
            }
        }

        if( count($where_in)>0 ){
            foreach ($where_in as $k => $v ){
                if( count($v) ) $model->_shard_db()->where_in($k, $v);
            }
        }

        if(!empty($params['sql'])){
            $result=$model->db->query($params['sql'])->result_array();
            $total=$model->db->query($params['sql'])->num_rows();

        }else{
            $result= $model->_shard_db()->select(" {$select} ")->order_by($sort)
                ->limit($page_size, $offset)->get_where($table, $where)
                ->result_array();
        }

        if($format=='array'){
            $tmp= array();
            $field_config= $model->get_field_config('grid');
            foreach ($result as $k=> $v){
                //判断combobox类型需要对值进行转换
                foreach($field_config as $sk=>$sv){
                    if($field_config[$sk]['type']=='combobox') {
                        if( isset($field_config[$sk]['select'][$v[$sk]])){
                            $v[$sk]= $field_config[$sk]['select'][$v[$sk]];
                        }
                        else $v[$sk]= '--';
                    }
                    if( $field_config[$sk]['grid_function'] ) {
                        $funp= explode('|', $field_config[$sk]['grid_function']);
                        $fun= $funp[0];
                        $funp[0]= $v[$sk];
                        $v[$sk]= call_user_func_array ($fun, $funp);
                    } else if( $field_config[$sk]['function'] ) {
                        $funp= explode('|', $field_config[$sk]['function']);
                        $fun= $funp[0];
                        $funp[0]= $v[$sk];
                        $v[$sk]= call_user_func_array ($fun, $funp);
                    }
                }//---

                $el= array_values($v);
                $el['DT_RowId']= $v[$model->table_primary_key()];
                $tmp[]= $el;
            }
            $result= $tmp;
        }

        return array(
            'total'=>$total,
            'data'=>$result,
            'page_size'=>$page_size,
            'page_num'=>$current_page,
        );
    }


    
	
}
