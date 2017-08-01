<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reward_benefit extends MY_Admin_Soma {

	//protected $label_module= NAV_MALL;		//统一在 constants.php 定义
	protected $label_controller= '分销奖励明细';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'soma/Reward_benefit_model';
	}

	public function grid()
	{
	    $this->label_action= '奖励明细';
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
	    //print_r($filter);die;

	    $ent_ids= $this->session->get_admin_hotels();
	    $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
	    if( count($hotel_ids)>0 ) $filter+= array('hotel_id'=> $hotel_ids );

	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	     
/* 兼容grid变为ajax加载加这一段 */
	    if(is_ajax_request())
	        //处理ajax请求，参数规格不一样
	        $get_filter= $this->_ajax_params_parse( $this->input->post(), $model );
	    else
	        $get_filter= $this->input->get('filter');
	     
	    if( !$get_filter) $get_filter= $this->input->get('filter');
	     
	    if(is_array($get_filter)) $filter= $get_filter+ $filter;
/* 兼容grid变为ajax加载加这一段 */

        $sts= $model->get_status_label();
        $ops= '';
        foreach( $sts as $k=> $v){
	        if( isset($filter['status']) && $filter['status']==$k ) $ops.= '<option value="'. $k. '" selected="selected">'. $v. '</option>';
	        else $ops.= '<option value="'. $k. '">'. $v. '</option>';
	    }
	    
	    if( !isset($filter['status']) || $filter['status']===NULL )
	        $active= '';
	    else
	        $active= 'btn-success';
	    
	    $jsfilter_btn= '&nbsp;&nbsp;<div class="input-group">'
	        . '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active. '"><i class="fa fa-filter"></i> 状态</button></div>'
            . '<select class="form-control input-sm" name="filter[status]" id="filter_status" >'
            . '<option value="-">全部</option>'. $ops
            . '</select>'
            . '</div>';
	    
	    $current_url= current_url();
	    $jsfilter= <<<EOF
$('#filter_status').change(function(){
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
	    $this->_grid($filter, $viewdata);
	}

	public function rebuild()
	{
	    //防止其他账号使用该功能。
	    $this->_toolkit_writelist();
	    
	    $this->label_action= '重新扫描';
		$this->_init_breadcrumb($this->label_action);
		
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
	    
	    if(is_array($filter)){
	        $this->load->model('wx/publics_model');
	        $publics= $this->publics_model->get_public_hash($filter);
	        $publics= $this->publics_model->array_to_hash($publics, 'name', 'inter_id');
	        //$publics= $publics+ array(FULL_ACCESS=>'-所有公众号-');
	    }

	    $this->load->model('soma/Reward_benefit_model');
	    $settlement= $this->Reward_benefit_model->get_settle_label();
	    
	    $sel_interid= $this->input->post('inter_id');
	    $sel_settlement= $this->input->post('settlement');
	    $start_time= $this->input->post('start_time');
	    $end_time= $this->input->post('end_time');
	    $business= 'package';
	    
	    if($start_time && $end_time && $sel_interid){

	        //数据库分片初始化
	        $this->load->model('soma/shard_config_model', 'model_shard_config');
	        $this->current_inter_id= $sel_interid;
	        $this->db_shard_config= $this->model_shard_config->build_shard_config($sel_interid);

	        $this->load->model('soma/Sales_order_model');
	        $filter= array(
	            'payment_time >'=> $start_time. ' 00:00:00',
	            'payment_time <'=> $end_time. ' 59:59:59',
	            'inter_id'=> $sel_interid,
	        );
	        if($sel_settlement) $filter['settlement']= $sel_settlement;
	        $orders= $this->Sales_order_model->get_order_list($business, $sel_interid, $filter);

	        $benefit= $this->Reward_benefit_model->get_benefit_formtime($sel_interid, $start_time. ' 00:00:00', $end_time. ' 59:59:59');
	        $benefit= $this->Reward_benefit_model->array_to_hash($benefit, 'reward_total', 'order_id');
	        foreach ($orders as $k=>$v){
	            if( array_key_exists($v['order_id'], $benefit) ){
	                $orders[$k]['send']= TRUE;
	                $orders[$k]['reward']= $benefit[$v['order_id']];
	            } else {
	                $orders[$k]['send']= FALSE;
	                $orders[$k]['reward']= '-';
	            }
	        }
	        
	    } else {
	        $orders= array();
	    }
	    
	    $view_params= array(
	        //'model'=> $model,
	        'orders'=> $orders,
	        'publics'=> $publics,
	        'settle_arr'=> $settlement,
	        'start_time'=> $start_time,
	        'end_time'=> $end_time,
	        'inter_id'=> $sel_interid,
	        'settlement'=> $sel_settlement,
	    );
	    
	    $html= $this->_render_content($this->_load_view_file('rebuild'), $view_params, TRUE);
	    //echo $html;die;
	    echo $html;
	}
	
	public function rebuild_post()
	{
	    //防止其他账号使用该功能。
	    $this->_toolkit_writelist();
	    
	    $inter_id= $this->input->get('inter_id');
	    $order_id= $this->input->get('order_id');
	    
	    //数据库分片初始化
	    $this->load->model('soma/shard_config_model', 'model_shard_config');
	    $this->current_inter_id= $inter_id;
	    $this->db_shard_config= $this->model_shard_config->build_shard_config($inter_id);
	    
	    //
	    $this->load->model('soma/Sales_order_model');
	    $order= $this->Sales_order_model->load($order_id);
	    $order->business= 'package';
	    
        $this->load->model('soma/Reward_benefit_model');
        $benefit_model= $this->Reward_benefit_model;
        $result= $benefit_model->write_benefit_queue($inter_id, $order);
	    
	    if($result) echo '成功同步。';
	    else echo '同步失败！（原因有可能为：规则过期；购买商品不符合配置原因等等）';
	}
	

	public function change_inter_id()
	{
	    //防止其他账号使用该功能。
	    $this->_toolkit_writelist();
	
	    $inter_id= $this->input->get('id');
	    if($inter_id){
	        $profile= $this->session->get_admin_profile();
	
	        if($inter_id=='del'){
	            unset($profile['temp_inter_id']);
	            $this->session->set_admin_profile($profile);
	            echo ("OK, You have reset your inter_id.");
	
	        } else {
	            $profile['temp_inter_id']= $inter_id;
	            $this->session->set_admin_profile($profile);
	            echo ("Success, You have change your inter_id.");
	        }
	
	    } else {
	        $this->load->model('wx/publics_model');
	        $publics= $this->publics_model->get_public_hash();
	        $publics= $this->publics_model->array_to_hash($publics, 'name', 'inter_id');
	        $html= '<div class="">
	            <select class="col-sm-4 selectpicker show-tick" data-live-search="true" id="inter_ids">
	            <option value=""> -请选择- </option></div>';
	        foreach ($publics as $k=> $v){
	            $html.= "<option value='{$k}'>{$v}</option>";
	        }
	        $html.= '</select>';

	        $current_inter_id= $this->session->get_temp_inter_id();
	        $current_inter_id= $current_inter_id? "目前所选公众号：{$publics[$current_inter_id]}": '';
	        
	        $base_url= Soma_const_url::inst()->get_url('*/*/*'). '?id=';
	        $js= base_url(). 'public/';
	        echo <<<EOF
<!DOCTYPE html><html><head>
<link rel="stylesheet" href="{$js}AdminLTE/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="{$js}AdminLTE/plugins/bootstrap-select/bootstrap-select.min.css">
<script src="{$js}AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="{$js}AdminLTE/bootstrap/js/bootstrap.min.js"></script>
<script src="{$js}AdminLTE/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script src="{$js}AdminLTE/plugins/bootstrap-select/i18n/defaults-zh_CN.min.js"></script>
<head>
<body><div id="wrap" style="margin:auto auto;padding:20px;">
<p>Invoke format: {$base_url}{$html}</p>
<p>Reset format: <a href="{$base_url}del" target="_blank">{$base_url}del</a></p>
<p style="color:red;">{$current_inter_id}</p>
</div>
<script>
$("#inter_ids").change(function(){
    window.location= "{$base_url}"+ $("#inter_ids").val();
});
</script>
</body></html>
EOF
;
	    }
	}
	
	
}
