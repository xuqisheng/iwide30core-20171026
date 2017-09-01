<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Topics extends MY_Admin_Mall {

	//protected $label_module= NAV_MALL;		//统一在 constants.php 定义
	protected $label_controller= '专题配置';		//在文件定义
	protected $label_action= '';				//在方法中定义
	
	protected function main_model_name()
	{
		return 'mall/shp_topic';
	}
	
	public function grid()
	{
		$this->label_action= '商城皮肤';
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
	    //print_r($filter);die;

	    $ent_ids= $this->session->get_admin_hotels();
	    $hotel_ids= $ent_ids? explode(',', $ent_ids ): array();
	    if( count($hotel_ids)>0 ) $filter+= array('hotel_id'=> $hotel_ids );
	     
/* 兼容grid变为ajax加载加这一段 */
	    if(is_ajax_request())
	        //处理ajax请求，参数规格不一样
	        $get_filter= $this->input->post();
	    else
	        $get_filter= $this->input->get('filter');
	     
	    if( !$get_filter) $get_filter= $this->input->get('filter');
	     
	    if(is_array($get_filter)) $filter= $get_filter+ $filter;
/* 兼容grid变为ajax加载加这一段 */
	    	  
	    $this->_grid($filter);
	}

	public function delete()
	{
	    try {
	        $model_name= $this->main_model_name();
	        $model= $this->_load_model($model_name);
	
	        $ids= explode(',', $this->input->get('ids'));
	        $result= $model->delete_in($ids);
	
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
	
	public function edit()
	{
		$this->label_action= '专题管理';
		$this->_init_breadcrumb($this->label_action);
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		
		$id= intval($this->input->get('ids'));
		if($id){
			//for edit page.
			$model= $model->load($id);
		}

        if(!$model) $model= $this->_load_model();
		$fields_config= $model->get_field_config('form');

		//越权查看数据跳转
		if( !$this->_can_edit($model) ){
            $this->session->put_error_msg('找不到该数据');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
		}

		//按照酒店权限寻找可用商品，可用焦点图
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
	    
		$this->load->model('mall/shp_advs');
		$this->load->model('mall/shp_goods');
		$this->load->model('mall/shp_category');

		$advs= $this->shp_advs->get_sdata_filter($filter);
		$goods= $this->shp_goods->get_data_filter($filter);
		$category= $this->shp_category->get_data_filter($filter);
		$advs= $this->shp_advs->array_to_hash_multi($advs, 'cate|name|link', 'id');
		$goods= $this->shp_goods->array_to_hash_multi($goods, 'gs_wx_price|gs_name|gs_nums', 'gs_id');
		$category= $this->shp_category->array_to_hash($category, 'cat_name', 'cat_id');
		//按照酒店权限寻找可用商品，可用焦点图
		
		$view_params= array(
		    'model'=> $model,
		    'fields_config'=> $fields_config,
		    'check_data'=> FALSE,
		    'advs'=> $advs,
		    'goods'=> $goods,
		    'category'=> $category,
		);
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}
	
	public function edit_post()
	{
	    $this->label_action= '专题管理';
	    $this->_init_breadcrumb($this->label_action);
	
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	
	    $this->load->library('form_validation');
	    $post= $this->input->post();
	    
	    $labels= $model->attribute_labels();
	    $base_rules= array(
	        'identity'=> array(
	            'field' => 'identity',
                'label' => $labels['identity'],
	            'rules' => array(
	                'trim',
	                'required',
	                'min_length[5]',
	                'max_length[12]',
	            ),
	        ),
	        'page_theme'=> array(
	            'field' => 'page_theme',
	            'label' => $labels['page_theme'],
	            'rules' => 'required',
	        ),
	        'page_title'=> array(
	            'field' => 'page_title',
	            'label' => $labels['page_title'],
	            'rules' => 'trim|required',
	        ),
	        'share_title'=> array(
	            'field' => 'share_title',
	            'label' => $labels['share_title'],
	            'rules' => 'trim|required',
	        ),
			/*
	        'share_link'=> array(
	            'field' => 'share_link',
	            'label' => $labels['share_link'],
	            'rules' => 'trim|required',
	        ),
	        'share_link_gift'=> array(
	            'field' => 'share_link_gift',
	            'label' => $labels['share_link_gift'],
	            'rules' => 'trim|required',
	        ),*/
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
	    );
	    
	    if( $post['page_theme']==$model::THEME_DEFAULT ){
	        $base_rules['theme_color']= array(
	            'field' => 'theme_color',
	            'label' => $labels['theme_color'],
	            'rules' => 'trim|required',
	        );
	    }

		$post['identity'] = str_replace(array('@','!','.','&','?','='), array('-','-','-','_','_','_'), $post['identity']);

	    //分享链接取默认
	    $post['share_link']= front_site_url($post['inter_id']). '/index.php/mall/wap/topic?id='
	    	. $post['inter_id']. '&t=' . $post['identity'];
	    //$post['share_link_gift']= '';
	    
	    //检测并上传文件。
	    $post= $this->_do_upload($post, 'share_img');
	    $post= $this->_do_upload($post, 'share_img_gift');
	    $post= $this->_do_upload($post, 'theme_image');
	    
	    if($post['page_starttime']> $post['page_endtime']){
	        $post['page_endtime']= $post['page_starttime'];
	    }

		if( !isset($post['adv_ids']) || count($post['adv_ids'])< 1 ){
    	    $this->session->put_error_msg('请至少选择一个轮播图！');
	        $this->_redirect(EA_const_url::inst()->get_url('*/*/edit', array('ids'=> $post[$pk] ) ));
		}
		if( $post['page_theme']==$model::THEME_LESS && (!isset($post['good_ids']) || count($post['good_ids'])< 1) ){
			$this->session->put_error_msg('请至少选择一个产品！');
			$this->_redirect(EA_const_url::inst()->get_url('*/*/edit', array('ids'=> $post[$pk] ) ));
		}
		if( $post['page_theme']==$model::THEME_MULTI && (!isset($post['category_ids']) || count($post['category_ids'])< 1) ){
    	    $this->session->put_error_msg('请至少选择一个显示分类！');
	        $this->_redirect(EA_const_url::inst()->get_url('*/*/edit', array('ids'=> $post[$pk] ) ));
		}

	    if( empty($post[$pk]) ){
	        //add data.
	        $base_rules['identity']['rules'][]= 'is_unique[shp_topic.identity]';
	        $this->form_validation->set_rules($base_rules);
	        if ($this->form_validation->run() != FALSE) {
	            //$post['add_date']= date('Y-m-d H:i:s');
	            //$post['add_user']= $adminid;
	            
	            $result= $model->m_sets($post)->m_save($post);
	            $message= ($result)?
    	            $this->session->put_success_msg('已新增数据！'):
    	            $this->session->put_notice_msg('此次数据保存失败！');
				//$this->_log($model);
	            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	
	        } else
	            $model= $this->_load_model();
	
	    } else {
    	    $base_rules['identity']['rules'][]= 'callback__id_check['. $post[$pk]. ']';
	        $this->form_validation->set_rules($base_rules);
	        if ($this->form_validation->run() != FALSE) {
	            //$post['last_update_time']= date('Y-m-d H:i:s');
	            //$post['last_update_user']= $adminid;
	
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
	
	    $fields_config= $model->get_field_config('form');

		//按照酒店权限寻找可用商品，可用焦点图
	    $inter_id= $this->session->get_admin_inter_id();
	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
		
		$this->load->model('mall/shp_advs');
		$this->load->model('mall/shp_goods');
		$this->load->model('mall/shp_category');

		$advs= $this->shp_advs->get_data_filter($filter);
		$goods= $this->shp_goods->get_data_filter($filter);
		$category= $this->shp_category->get_data_filter($filter);
		$advs= $this->shp_advs->array_to_hash_multi($advs, 'name|link', 'id');
		$goods= $this->shp_goods->array_to_hash_multi($goods, 'gs_wx_price|gs_name|gs_nums', 'gs_id');
		$category= $this->shp_category->array_to_hash($category, 'cat_name', 'cat_id');
		//按照酒店权限寻找可用商品，可用焦点图

	    $view_params= array(
	        'model'=> $model,
	        'fields_config'=> $fields_config,
	        'check_data'=> TRUE,
		    'advs'=> $advs,
		    'goods'=> $goods,
		    'category'=> $category,
	    );
	    $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    echo $html;
	}
	

	public function _id_check($identity, $id)
	{
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $data= $model->get_data_filter( array('identity'=>$identity) );
	    if( count($data)>0 && $data['0']['topic_id']!= $id){
	        $this->form_validation->set_message('_id_check', $identity .'"已经被占用了  。');
	        return FALSE;
	    } else 
	        return TRUE;
	}
	
	public function qrcode_front()
	{
	    if($id= $this->input->get('ids')){
	        $model_name= $this->main_model_name();
	        $model= $this->_load_model($model_name);
	        $model= $model->load($id);
	        $url= EA_const_url::inst()->get_front_url($model->m_get('inter_id'), 'mall/wap/topic', array('id'=> $model->m_get('inter_id'), 't'=>$model->m_get('identity')));
	        $this->_get_qrcode_png($url);
	    } else 
	        echo '参数错误';
	}
	
	
	
}
