<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminlog extends MY_Admin_Priv {

	protected $label_module= NAV_PRIVILEGE;		//统一在 constants.php 定义
	protected $label_controller= '管理员日志';	//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'core/priv_admin_log';
	}
	
	
	public function grid()
	{
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
	    //print_r($filter);die;

/** 添加 过滤条件js开始  **/
        $model_name= $this->main_model_name();
        $model= $this->_load_model($model_name);
        
        if(is_ajax_request())
            //处理ajax请求，参数规格不一样
            $get_filter= $this->_ajax_params_parse( $this->input->post(), $model );
        else
            $get_filter= $this->input->get('filter');
        
        if(is_array($get_filter)) $filter= $get_filter+ $filter;

        $sts= $model->get_action_type();
        $field_name= 'action_type';
        $ops= '';
        foreach( $sts as $k=> $v){
            if( isset($filter[$field_name]) && $filter[$field_name]==$k ) 
                $ops.= '<option value="'. $k. '" selected="selected">'. $v. '</option>';
            else $ops.= '<option value="'. $k. '">'. $v. '</option>';
        }
        
        if( !isset($filter[$field_name]) || $filter[$field_name]===NULL )
            $active= 'disabled';
        else
            $active= 'btn-success';
        $jsfilter_btn= '&nbsp;&nbsp;<div class="input-group">'
            . '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active
            . '"><i class="fa fa-filter"></i> 日志类型</button></div>'
            . '<select class="form-control input-sm" name="filter['. $field_name. ']" id="filter_'. $field_name. '" >'
            . '<option value="-">全部</option>'. $ops
            . '</select>'
            . '</div>';
        
        //echo $ops;die;
        $current_url= current_url();
        $jsfilter= <<<EOF
$('#filter_{$field_name}').change(function(){
	var go_url= '?'+ $(this).attr('name')+ '='+  $(this).val();
	//alert(go_url);
	if($(this).val()=='-') window.location= '{$current_url}';
	else window.location= '{$current_url}'+ go_url;
});
EOF;
        $viewdata= array(
            'js_filter_btn'=> $jsfilter_btn,
            'js_filter'=> $jsfilter,
        );
/** 添加 过滤条件js结束  **/
        
	    $this->_grid($filter, $viewdata);
	}
	
	
	
}
