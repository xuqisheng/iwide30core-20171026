<?php

class MY_Admin_Mall extends MY_Admin {

    protected $label_module= NAV_MALL;		//统一在 constants.php 定义

    public function delete()
    {
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
    
    public function _grid($filter= array(), $viewdata=array())
    {
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

            $html= $this->_render_content($this->_load_view_file('grid'), $view_params, TRUE);
            //echo $html;die;
            echo $html;
        }
    }

    public function _do_export($data, $header, $type='csv', $download=TRUE )
    {
        switch ($type) {
            case 'csv':
            default:
                $tmppath= FD_. 'export'. DS;
                $urlpath= base_url('public/export'). '/';
                if(!file_exists($tmppath)) @mkdir($tmppath, 0777, TRUE);
                $tmpfile= $this->module. '_'. $this->controller. '_'. $this->action. '_'
                    . date('ymdHis_'. rand(10, 99)). '.'. $type;
    
                if($download== TRUE){
                    header( 'Content-Type: text/csv' );
                    header( 'Content-Disposition: attachment;filename='.$tmpfile);
                }
    
                $fp = fopen($tmppath. $tmpfile, 'w');
    
                //转换字符集
                array_unshift($data, $header);
                foreach ($data as $k=> $v){
                    foreach ($v as $sk=> $sv){
                        $data[$k][$sk]= convert_to_gbk($sv);
                    }
                }
                //print_r($data);die;

                if($fp){
                    //循环插入数据
                    foreach ($data as $line) {
                        if($download== TRUE){
                            echo implode(',', $line). "\n";
                        }
                        fputcsv($fp, $line, ',', '"');
                    }
                    fclose($fp);
                }
                
                break;
        }
        //上传到ftp
    
        //@unlink($tmppath. $tmpfile);
        return $urlpath. $tmpfile;
    }
    
	
}
