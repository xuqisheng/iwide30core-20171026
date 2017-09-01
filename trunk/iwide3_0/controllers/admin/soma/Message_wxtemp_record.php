<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Message_wxtemp_record extends MY_Admin_Soma {
    
	protected $label_controller = '模板消息记录';
	protected $label_action = '';

	protected function main_model_name()
	{
		return 'soma/Message_wxtemp_record_model';
	}

	public function grid() 
	{
		$this->label_action= '模版管理';
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

		if(is_ajax_request()) 
            //处理ajax请求，参数规格不一样
            $get_filter= $this->_ajax_params_parse( $this->input->post(), $model );
		    
        else 
		    $get_filter= $this->input->get('filter');
        
		if(is_array($get_filter)) $filter= $get_filter+ $filter;

        //过滤状态=============
		$sts= $model->get_status_label();
		$ops= '';
		foreach( $sts as $k=> $v){
		    if( isset($filter['status']) && $filter['status']==$k ) $ops.= '<option value="'. $k. '" selected="selected">'. $v. '</option>';
		    else $ops.= '<option value="'. $k. '">'. $v. '</option>';
		}
		if( !isset($filter['status']) || $filter['status']===NULL ) $active= 'disabled';
		else $active= 'btn-success';


		//过滤业务类型=============
        if($inter_id == FULL_ACCESS){
            $export_btn= '';
        } else {
            $export_btn= '<span class="input-group-btn"><button id="export_order_btn" type="button" class="btn btn-sm btn-success"><i class="fa fa-download"></i> 导出</button></span>';
        }
        
        
        $jsfilter_btn= '&nbsp;&nbsp;<div class="input-group">'
// 			. '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active2. '"><i class="fa fa-filter"></i> 业务</button></div>'
// 			. '<select class="form-control input-sm" name="filter[business]" id="filter_business" >'
// 			. '<option value="-">全部</option>'. $ops2
// 			. '</select>' 
			. '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active. '"><i class="fa fa-filter"></i> 状态</button></div>'
			. '<select class="form-control input-sm" name="filter[status]" id="filter_status" >'
			. '<option value="-">全部</option>'. $ops
			. '</select>' 
			//. $export_btn
			. '</div>';

        //echo $ops;die;
        $current_url= current_url();
        $export_url= Soma_const_url::inst()->get_url('*/*/export_list' );
        $jsfilter= <<<EOF
$('#filter_status').change(function(){
	var go_url= '?'+ $(this).attr('name')+ '='+  $(this).val();
	//alert(go_url);
	if($(this).val()=='-') window.location= '{$current_url}';
	else window.location= '{$current_url}'+ go_url;
});
$('#export_order_btn').click(function(){
	var status= $('#filter_status').val();
	var url= '{$export_url}';
    if( !isNaN(status) ){
	    var p= '?status='+ status;
	} else {
	    var p= '';
	}
	window.location= url+= p;
});
EOF;
        $viewdata= array(
            'js_filter_btn'=> $jsfilter_btn,
            'js_filter'=> $jsfilter,
        );
	    $this->_grid($filter, $viewdata);
	}

	public function edit() 
	{
	}

	public function edit_post()
	{
	}

	/**
	 * 禁止进行删除操作
	 */
	public function delete()
	{
	    $url= Soma_const_url::inst()->get_url('*/*/index');
	    redirect($url);
	}
	
}
