<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	后台用户模式配置
*	@author Frandon
*	@time 三月三十一号
*	@version www.iwide.cn
*	@
*/
class Membermodel extends MY_Admin_Api
{

	//配置设置
	public function index(){
		$post_data = array(
			'inter_id'=>$this->session->get_admin_inter_id(),
			);
		//请求登录配置信息URL
		$level_config = $this->doCurlPostRequest( PMS_PATH_URL."adminmember/get_member_model" , $post_data );
		$data = array(
			'modelconfig' =>isset($level_config['data'])?$level_config['data']:array(),
			);
		$html= $this->_render_content($this->_load_view_file('edit'),$data,false);

	}

	//增加或修改配置
	public function edit_post(){
		$post_model_data = $_POST;
		$post_data['inter_id'] =$this->session->get_admin_inter_id();
		$model_config = $this->doCurlPostRequest( PMS_PATH_URL."adminmember/get_member_model" , $post_data );
		//存在则修改，否则增加
		if(!$model_config['data']){
			$post_update_data['inter_id'] =$this->session->get_admin_inter_id();
			$post_update_data['data'] =$post_model_data;
			$model_config = $this->doCurlPostRequest( PMS_PATH_URL."adminmember/add_member_model" , $post_update_data );
		}else{
			$post_add_data['inter_id'] =$this->session->get_admin_inter_id();
			$post_add_data['data'] =$post_model_data;
			$model_config = $this->doCurlPostRequest( PMS_PATH_URL."adminmember/update_member_model" , $post_add_data );
		}
		redirect('membervip/membermodel');
		exit;

	}

    //自定义栏目与字段展示
    public function custom(){
        $data = array();
        $post_data['inter_id'] =$this->session->get_admin_inter_id();
        $custom_config =  $this->doCurlPostRequest( PMS_PATH_URL."adminmember/get_custom_field_rule" , $post_data );
        if(isset($custom_config['value']) && !empty($custom_config['value'])){
            $data = json_decode($custom_config['value'],true);
            $data['config_id'] = $custom_config['id'];
        }
        $this->_render_content($this->_load_view_file('custom_edit'),$data,false);
    }


    //自定义栏目保存
    public function custom_edit(){
        if(empty($_POST)){
            $result = array(
                'err'  => 40003,
                'msg'   => '保存数据为空,请检查输入是否有误'
            );
            echo json_encode($result);
            exit;
        }

        $data = $_POST;
        $postData = array();

        //启用余额
        if(!isset($data['balance_use']) || $data['balance_use'] != 'f'){
            $postData['balance_use'] = 't';
        }else{
            $postData['balance_use'] = 'f';
        }
        //启用积分
        if(!isset($data['credit_use']) || $data['credit_use'] != 'f'){
            $postData['credit_use'] = 't';
        }else{
            $postData['credit_use'] = 'f';
        }
        if($data['credit_default_name'] == 'f' && !empty($data['credit_default_name_value'])) {
            $postData['credit']['default'] = 'f';
            $postData['credit']['name'] = $data['credit_default_name_value'];
        } else{
            $postData['credit']['default'] = 't';
            $postData['credit']['name'] = '积分';
        }
        if($data['balance_default_name'] == 'f' && !empty($data['balance_default_name_value'])) {
            $postData['balance']['default'] = 'f';
            $postData['balance']['name'] = $data['balance_default_name_value'];
        } else{
            $postData['balance']['default'] = 't';
            $postData['balance']['name'] = '余额';
        }
        $postData['inter_id'] = $this->session->get_admin_inter_id();


        if(!$data['config_id']){
            $result = $this->doCurlPostRequest( PMS_PATH_URL."adminmember/add_custom_field_rule" , $postData );
        }else{
            $postData['config_id'] = $data['config_id'];
            $result = $this->doCurlPostRequest( PMS_PATH_URL."adminmember/update_custom_field_rule" , $postData );
        }


        echo json_encode($result);

    }

}
?>