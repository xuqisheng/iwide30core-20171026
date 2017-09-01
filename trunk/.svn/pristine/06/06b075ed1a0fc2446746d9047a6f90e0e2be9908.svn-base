<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Message_wxtemp_template extends MY_Admin_Soma {
	// protected $label_module = NAV_HOTEL;
	protected $label_controller = '模板消息';
	protected $label_action = '';

	protected function main_model_name()
	{
		return 'soma/Message_wxtemp_template_model';
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
	     
/* 兼容grid变为ajax加载加这一段 */
	    if(is_ajax_request())
	        //处理ajax请求，参数规格不一样
	        $get_filter= $this->input->post();
	    else
	        $get_filter= $this->input->get('filter');
	    
	    if( !$get_filter) $get_filter= $this->input->get('filter');
	    
	    if(is_array($get_filter)) $filter= $get_filter+ $filter;
/* 兼容grid变为ajax加载加这一段 */
	    // echo 'ad';exit;
	    $this->_grid($filter);
	}

	public function edit() 
	{
		$this->label_action= '模版信息修改';
		$this->_init_breadcrumb($this->label_action);
		
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		
		$id= intval($this->input->get('ids'));

		$model= $model->load($id);
        if(!$model) $model= $this->_load_model();
		$fields_config= $model->get_field_config('form');

		//越权查看数据跳转
		if( !$this->_can_edit($model) ){
            $this->session->put_error_msg('找不到该数据');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
		}

		$temp_field_name = $model->get_template_field_name();
		$temp_option = '';
		foreach ($temp_field_name as $k => $v) {
			$temp_option .= '<option value="'.$k.'">'.$v.'</option>';
		}

		//反序列化content，输出到模版详情
		$content_str = '<div id="content">';
		$content_first = '';
		$content_remark = '';
		$value = 'value';
		$color = 'color';
		$contents = array();
		$contents = $model->unserialize_content();
		$i = 0;
		foreach( $contents as $sk=>$sv ){
			if( $sk=='first' ){
				$content_first .= '
	          <div class="form-group ">
	              <label for="el_content_first" class="col-sm-2 control-label">模版头部内容</label>
	              <div class="col-sm-2">
	                  <select class="form-control " name="content[first][key]" id="el_content_key_first">
	                  	<option value="{{first.DATA}}">头部内容</option>
	                  </select>
	              </div>
	              <div class="col-sm-4">
	                  <input type="text" class="form-control " name="content[first][value]" id="el_content_value_first" placeholder="内容" value="'.$sv['value'].'">
	              </div>
				    <div class="col-sm-2">
				        <div class=" input-group color">
				            <input type="text" class="form-control cp-preventtouchkeyboardonshow el_theme_color" name="content[first][color]" value="#000000" tabindex="-1" readonly="" style="color: rgb(0, 0, 0); background: rgb(238, 215, 0);">
				            <span class="input-group-addon"><i class="fa fa-dashboard"></i></span>
				        </div>
				    </div>
	          </div>
	          ';
			}elseif( $sk=='remark' ){
				$content_remark .= '
	          <div class="form-group ">
	              <label for="el_content_remark" class="col-sm-2 control-label">模版尾部内容</label>
	              <div class="col-sm-2">
	                  <select class="form-control " name="content[remark][key]" id="el_content_key_remark">
	                  	<option value="{{remark.DATA}}">尾部内容</option>
	                  </select>
	              </div>
	              <div class="col-sm-4">
	                  <input type="text" class="form-control " name="content[remark][value]" id="el_content_value_remark" placeholder="内容" value="'.$sv['value'].'">
	              </div>
				    <div class="col-sm-2">
				        <div class=" input-group color">
				            <input type="text" class="form-control cp-preventtouchkeyboardonshow el_theme_color" name="content[remark][color]" value="#000000" tabindex="-1" readonly="" style="color: rgb(0, 0, 0); background: rgb(238, 215, 0);">
				            <span class="input-group-addon"><i class="fa fa-dashboard"></i></span>
				        </div>
				    </div>
	          </div>
	          ';
			}else{

				$content_option = '';
				foreach ($temp_field_name as $ssk => $ssv) {
// var_dump( $contents );exit;
					if( $sv['key'] == $ssk ){
						$content_option .= '<option value="'.$ssk.'" selected >'.$ssv.'</option>';
					}else{
						$content_option .= '<option value="'.$ssk.'" >'.$ssv.'</option>';
					}
				}

				$content_str .= '
		          <div class="form-group ">
		              <label for="el_content" class="col-sm-2 control-label">模版内容</label>
		              <div class="col-sm-2">
		                  <select class="form-control " name="content['.$sk.'][key]" id="el_content_key'.$sk.'">
		                  	'.$content_option.'
		                  </select>
		              </div>
		              <div class="col-sm-4">
		                  <input type="text" class="form-control " name="content['.$sk.'][value]" id="el_content_value_'.$sk.'" placeholder="内容" value="'.$sv['value'].'">
		              </div>
					    <div class="col-sm-2">
					        <div class=" input-group color">
					            <input type="text" class="form-control cp-preventtouchkeyboardonshow el_theme_color" name="content['.$sk.'][color]" value="#000000" tabindex="-1" readonly="" style="color: rgb(0, 0, 0); background: rgb(238, 215, 0);">
					            <span class="input-group-addon"><i class="fa fa-dashboard"></i></span>
					        </div>
					    </div>
		          </div>
		          ';
				$i = $sk;
			}
		}
		$content_str .= '</div>';

		$add_content_str = '
		          <div class="form-group ">
		              <label for="el_content" class="col-sm-2 control-label">模版内容</label>
		              <div class="col-sm-2">
		                  <select class="form-control " name="content[][key]" id="el_content_key">
		                  	'.$temp_option.'
		                  </select>
		              </div>
		              <div class="col-sm-4">
		                  <input type="text" class="form-control " name="content[][value]" id="el_content_value_" placeholder="内容" value="">
		              </div>
					    <div class="col-sm-2">
					        <div class=" input-group color">
					            <input type="text" class="form-control cp-preventtouchkeyboardonshow el_theme_color" name="content[][color]" value="#000000" tabindex="-1" readonly="" style="color: rgb(0, 0, 0); background: rgb(238, 215, 0);">
					            <span class="input-group-addon"><i class="fa fa-dashboard"></i></span>
					        </div>
					    </div>
		          </div>
		          ';

		$view_params= array(
		    'model'=> $model,
		    'fields_config'=> $fields_config,
		    'check_data'=> FALSE,
		    'content_first'=> $content_first,
		    'content_str'=> $content_str,
		    'content_remark'=> $content_remark,
		    'temp_option'=> $temp_option,
		    'i'=> $i+1,
		);
		
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		//echo $html;die;
		echo $html;
	}

	public function edit_post() 
	{
		$this->label_action= '信息维护';
	    $this->_init_breadcrumb($this->label_action);
	
	    $model_name= $this->main_model_name();
	    $model= $this->_load_model($model_name);
	    $pk= $model->table_primary_key();
	
	    $this->load->library('form_validation');
	    $post= $this->input->post();
// var_dump( $post );exit;
	    $inter_id= $this->session->get_admin_inter_id();
		$post['inter_id'] = $inter_id;
     
	    $labels= $model->attribute_labels();
	    $base_rules= array(
	        'template_id'=> array(
	            'field' => 'template_id',
	            'label' => $labels['template_id'],
	            'rules' => 'trim|required',
	        ),
	    );

	    $post['content'] = base64_encode( serialize( $post['content'] ) );
	    $post['create_time'] = date('Y-m-d H:i:s',time());

	    if( empty($post[$pk]) ){
	        //add data.
	        
	        $this->form_validation->set_rules($base_rules);
	         
	        if ($this->form_validation->run() != FALSE) {

	        	//如果是新添加的，关于过期提醒需要设置更新时间
	        	$post['update_time'] = date( 'Y-m-d H:i:s', time() );

	            $result= $model->m_sets($post)->m_save();
	            $message= ($result)?
    	            $this->session->put_success_msg('已新增数据！'):
    	            $this->session->put_notice_msg('此次数据保存失败！');
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	
	        } else
	            $model= $this->_load_model();
	         
	    } else {

	        $this->form_validation->set_rules($base_rules);
	         
	        if ($this->form_validation->run() != FALSE) {
	        	if( isset( $post['create_time'] ) ){
	        		unset( $post['create_time'] );
	        	}
	        	$post['update_time'] = date( 'Y-m-d H:i:s', time() );
	            $result= $model->load($post[$pk])->m_sets($post)->m_save();
	            $message= ($result)?
    	            $this->session->put_success_msg('已保存数据！'):
    	            $this->session->put_notice_msg('此次数据修改失败！');
	            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
	
	        } else
	            $model= $model->load($post[$pk]);
	    }
	
	    //验证失败的情况
	    $validat_obj= _get_validation_object();
	    $message= $validat_obj->error_html();
	    //页面没有发生跳转时用寄存器存储消息
	    $this->session->put_error_msg($message, 'register');
	
	    $fields_config= $model->get_field_config('form');
	    $view_params= array(
	        'model'=> $model,
	        'fields_config'=> $fields_config,
	        'check_data'=> TRUE,
	    );
	    // $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
	    // echo $html;
	    
    	$this->session->put_notice_msg('此次数据修改失败,有可能没有填写模版ID！');
	    $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit',array('ids'=>$post[$pk])));
	}

}
