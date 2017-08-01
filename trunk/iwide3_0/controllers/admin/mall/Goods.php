<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goods extends MY_Admin_Mall {

	//protected $label_module= NAV_MALL;		//统一在 constants.php 定义
	protected $label_controller= '商品管理';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'mall/shp_goods';
	}
	
	public function delete()
	{
	    
	}
	

	public function grid()
	{
		$this->label_action= '商品管理';
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

		$this->load->model('mall/shp_category');
		$f_inter_id= isset($filter['inter_id'])? $filter['inter_id']: NULL;
        $cats= $this->shp_category->get_cat_tree_option( $f_inter_id );
        
        $ops= '';
        foreach( $cats as $k=> $v){
        	if( isset($filter['cat_id']) && $filter['cat_id']==$k ) $ops.= '<option value="'. $k. '" selected="selected">'. $v. '</option>';
        	else $ops.= '<option value="'. $k. '">'. $v. '</option>';
        }
        
        if( !isset($filter['cat_id']) || $filter['cat_id']===NULL )
            $active= '';
        else 
            $active= 'btn-success';

        $jsfilter_btn= '&nbsp;&nbsp;<div class="input-group">'
			. '<div class="input-group-btn"><button type="button" class="btn btn-sm '. $active. '"><i class="fa fa-filter"></i> 分类筛选</button></div>'
			. '<select class="form-control input-sm" name="filter[cat_id]" id="filter_status" >'
			. '<option value="-">全部</option>'. $ops
			. '</select>'
			. '</div>';
//      $jsfilter_btn.= '&nbsp;&nbsp;<div class="input-group">'
			// . '<div class="input-group-btn"><button type="button" class="btn btn-sm btn-success">支付状态</button></div>'
			// . '<select class="form-control input-sm" name="filter[status]" id="filter_status" >'
			// . '<option value="-">全部</option>'. $ops
			// . '</select>'
			// . '</div>';

        //echo $ops;die;
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

	public function edit()
	{
		$this->label_action= '商品管理';
		$this->_init_breadcrumb($this->label_action);
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);

		$detail_field= '';
		$id= intval($this->input->get('ids'));
		if($id){
			//for edit page.
			$model= $model->load($id);
			if( $model ){
				$sql= "select a.* from {$this->db->dbprefix}shp_goods_attr as a left join {$this->db->dbprefix}shp_attrbutes as b on a.attr_id=b.attr_id where a.gs_id=". $id;
				$detail_field= $this->db->query($sql)->result_array();
				if( count($detail_field)>0 ){
					$detail_field= $detail_field[0]['attr_value'];
				} else {
					$detail_field= '';
				}
			}
	        if(!$model) $model= $this->_load_model();
			$fields_config= $model->get_field_config('form');
			
		} else {
			//for add page.
	        $model= $model->load($id);
	        if(!$model) $model= $this->_load_model();
			$fields_config= $model->get_field_config('form');
		}
		
		//获取相册数组
		$gallery= $model->get_gallery();

		$view_params= array(
		    'model'=> $model,
		    'fields_config'=> $fields_config,
		    'check_data'=> FALSE,
		    'detail_field'=> $detail_field,
		    'gallery'=> $gallery,
		);
		
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}
	
	public function edit_post()
	{
	    $this->label_action= '产品修改';
	    $this->_init_breadcrumb($this->label_action);
	
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	
	    $this->load->library('form_validation');
	    $post= $this->input->post();
	    
	    $wx_card_id_hid= $this->input->post('wx_card_id_');
	    if( $this->input->post('wx_card_id') ) {
	        $post['wx_card_id']= $this->input->post('wx_card_id');
	    }
	    if($wx_card_id_hid) {
	        $post['wx_card_id']= $wx_card_id_hid;
	    }
	    
	    $labels= $model->attribute_labels();
	    $base_rules= array(
	        'gs_name'=> array(
	            'field' => 'gs_name',
	            'label' => $labels['gs_name'],
	            'rules' => 'trim|required',
	        ),
// 	        'sku'=> array(
// 	            'field' => 'sku',
// 	            'label' => $labels['sku'],
// 	            'rules' => 'trim|required',
// 	        ),
	        'cat_id'=> array(
	            'field' => 'cat_id',
	            'label' => $labels['cat_id'],
	            'rules' => 'trim|required',
	        ),
	        'gs_nums'=> array(
	            'field' => 'gs_nums',
	            'label' => $labels['gs_nums'],
	            'rules' => 'trim|required',
	        ),
	        'gs_market_price'=> array(
	            'field' => 'gs_market_price',
	            'label' => $labels['gs_market_price'],
	            'rules' => 'trim|required',
	        ),
	        'gs_wx_price'=> array(
	            'field' => 'gs_wx_price',
	            'label' => $labels['gs_wx_price'],
	            'rules' => 'trim|required',
	        ),
	        'hotel_id'=> array(
	            'field' => 'hotel_id',
	            'label' => $labels['hotel_id'],
	            'rules' => 'trim|required',
	        ),
	        'inter_id'=> array(
	            'field' => 'inter_id',
	            'label' => $labels['inter_id'],
	            'rules' => 'trim|required',
	        ),
	        'can_mail'=> array(
	            'field' => 'can_mail',
	            'label' => $labels['can_mail'],
	            'rules' => 'callback__check_conflict['. $post['can_pickup']. ']',
	        ),
	        'is_virtual'=> array(
	            'field' => 'is_virtual',
	            'label' => $labels['is_virtual'],
	            'rules' => 'callback__type_setting_conflict['. $wx_card_id_hid. ']',
	        ),
	    );

	    //检测并上传文件。
	    $post= $this->_do_upload($post, 'gs_logo');
	    
	    $adminid= $this->session->get_admin_id();
	    
	    if( empty($post[$pk]) ){
	        //add data.
	        $this->form_validation->set_rules($base_rules);
	
	        if ($this->form_validation->run() != FALSE) {
	            $post['add_date']= date('Y-m-d H:i:s');
	            $post['add_user']= $adminid;
	            if($post['gs_nums']<=0) $post['onsale']= $model::STATUS_F;
	            
	            $result= $model->m_sets($post)->m_save($post);
	            $message= ($result)?
		            $this->session->put_success_msg('已新增商品，为丰富效果，请添加对应的图片相册吧'):
		            $this->session->put_notice_msg('此次数据保存失败！');
				//$this->_log($model);
	            $this->_redirect(EA_const_url::inst()->get_url('*/*/edit',  array('ids'=> $result, 'tab'=> '2' ) ));

	        } else
	            $model= $this->_load_model();
	
	    } else {
	        $this->form_validation->set_rules($base_rules);
	        if ($this->form_validation->run() != FALSE) {
	            $post['last_update_time']= date('Y-m-d H:i:s');
	            $post['last_update_user']= $adminid;
	            if($post['gs_nums']<=0) $post['onsale']= $model::STATUS_F;

	            $result= $model->load($post[$pk])->m_sets($post)->m_save($post);
	            $message= ($result)?
    	            $this->session->put_success_msg('已保存数据！'):
    	            $this->session->put_notice_msg('此次数据修改失败！');
				$this->_log($model);
	            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	
	        } else
	            $model= $model->load($post[$pk]);
	    }

	    //验证失败的情况
	    $validat_obj= _get_validation_object();
	    $message= $validat_obj->error_html();
	    //页面没有发生跳转时用寄存器存储消息
	    $this->session->put_error_msg($message, 'register');

	    if( $model && $post[$pk]  ){
	        $sql= "select a.* from {$this->db->dbprefix}shp_goods_attr as a left join {$this->db->dbprefix}shp_attrbutes as b on a.attr_id=b.attr_id where a.gs_id=". $post[$pk];
	        $detail_field= $this->db->query($sql)->result_array();
	        if( count($detail_field)>0 ){
	            $detail_field= $detail_field[0]['attr_value'];
	        } else {
	            $detail_field= '';
	        }
	    }
	    
	    //获取相册数组
	    $gallery= $model->get_gallery();
	    
	    $fields_config= $model->get_field_config('form');
	    $view_params= array(
	        'model'=> $model,
	        'fields_config'=> $fields_config,
	        'check_data'=> TRUE,
		    'detail_field'=> $detail_field,
		    'gallery'=> $gallery,
	    );
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    echo $html;
	}
	
	public function edit_focus()
	{
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	    $post= $this->input->post();

	    if($post['del_gallery']){
	        $model->delete_gallery($post['del_gallery'], $post[$pk]);
	    }
	    //检测并上传新的文件。
	    $post= $this->_do_upload($post, 'gallery');
	    if(isset($post['gallery'])){
	        $data= array(
	            'gry_url'=> $post['gallery'],
	            'gry_desc'=> $post['gry_desc'],
	            'gs_id'=> $post['gs_id'],
	        );
	        $model->plus_gallery($data);
	    }
	    $this->session->put_success_msg('成功保存产品相册，请继续编辑产品信息');
	    $this->_redirect(EA_const_url::inst()->get_url('*/*/edit', array('ids'=> $post[$pk]) ));
	}

	//邮寄状态冲突检测
	public function _check_conflict($mail, $pickup)
	{
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    if ( $mail==$model::STATUS_F && $pickup==$model::STATUS_F  ) {
	        $this->form_validation->set_message('_check_conflict', '商品不能同时禁止“邮寄”和“自提”。');
	        return FALSE;
	
	    } else {
	        return TRUE;
	    }
	}
	//商品模型冲突检测
	public function _type_setting_conflict($type, $field1 )
	{
// 	    $model_name= $this->main_model_name();
// 	    $model= $this->_load_model($model_name);
// 	    switch ( $type ) {
// 	        case $model::GS_TYPE_2:
// 	            if( !$field1 ){
// 	                $this->form_validation->set_message('_type_setting_conflict', '选用该商品模型，还需选择正确card_id');
// 	                return FALSE;
// 	            }
//     	        break;

//     	    case $model::GS_TYPE_1:
// 	        default:
// 	           break;
// 	    }
	    return TRUE;
	}
    /**
     * 关联微信卡券时，拉取卡券了列表
     */
    public function ajax_cardlist()
    {
	    $inter_id= $this->session->get_admin_inter_id();
	    //$inter_id= 'a453956624';
        $return= array('status'=> '2', 'data'=> array(), 'message'=> 'inter_id错误，必须以商户账号登陆才能拉取卡券。', );
        if( $inter_id==FULL_ACCESS ){
            echo json_encode($return);
            
        } else {
            $post= $this->input->post();
            //print_r($post);
            $cCount= $count= $this->input->post('count');

            $i= 1;
            $cards= array();
            $first= TRUE;
            while ( $cCount== $count && $i<500){
                if($first){
                    $first= FALSE;
                    $post['offset']= '0';
                } else 
                    $post['offset']+= $cCount;
                
                $api_url= 'https://api.weixin.qq.com/card/batchget?access_token=';
                $result = (array) $this->_wxapi_post( $api_url, $post, $inter_id );
                //print_r( $result );die;
                
                if( isset($result['card_id_list']) && count($result['card_id_list'])>0 ){
                    $cCount= count($result['card_id_list']);
                    $return['total']= $result['total_num'];
                    $return['message']= '成功拉取微信卡券';
                    $api_url= 'https://api.weixin.qq.com/card/get?access_token=';
                    foreach ($result['card_id_list'] as $k=>$v ){
                        $result = $this->_wxapi_post( $api_url, array("card_id"=>$v ), $inter_id );
                        //print_r( $result );die;
                        if( $result->card->gift->base_info->title ){
                            $cards[$v]= $i++. '. '. $result->card->gift->base_info->title ;
                        }
                    }
                    //print_r($cards);die;
                    $return['status']=1;
                    $return['data']+= $cards;
                
                } else {
                    $cCount= 0;
                    $return['message']= '微信卡券中，卡类型数量为 0';
                }
            }
            echo json_encode($return);
        }
    }
    protected function _wxapi_post($api_url, $data, $inter_id, $type='json')
    {
        $this->load->helper ( 'common' );
        $this->load->model('wx/access_token_model');
        $access_token= $this->access_token_model->get_access_token( $inter_id );
        
        $api_url= $api_url. $access_token;
        $send_content = json_encode ( $data );
        $content = doCurlPostRequest ( $api_url, $send_content );
        $rj= json_decode($content);
        
        if( $rj && isset($rj->errcode ) && in_array($rj->errcode , array(40001,40014,41001,42001) ) ){
            $access_token= $this->access_token_model->get_access_token( $inter_id );
            $api_url= $api_url. $access_token;
            $send_content = http_build_query ( $post );
            $content = doCurlPostRequest ( $api_url, $send_content );
        }
        return $type=='json'? json_decode($content): $content;
    }
    
    /**
     * 关联微信卡券时，点选redio提交关联动作
     */
    public function ajax_cardsave()
    {
        $return= array('status'=> '2', 'data'=> array(), 'message'=> '保存数据失败', );
        $gs_id= $this->input->post('gs_id');
        $card_id= $this->input->post('card_id');
        //print_r($post);
        if( $gs_id && $card_id ){
    	    $model_name= $this->main_model_name();
    	    $model= $this->_load_model($model_name)->load($gs_id);
    	    if($model){
    	        $result= $model->m_set('wx_card_id', $card_id)->m_save();
    	        if($result){
    	            $return['status']= 1;
    	            $return['message']= '成功关联微信卡券';
    	            echo json_encode($return);die;
    	        }
    	    }
        }
    	echo json_encode($return);
    }

	
}
