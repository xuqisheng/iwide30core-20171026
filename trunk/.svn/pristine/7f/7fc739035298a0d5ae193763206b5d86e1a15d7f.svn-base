<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	后台用户等级配置
*	@author Frandon
*	@time 三月三十一号
*	@version www.iwide.cn
*	@
*/
class Memberlevel extends MY_Admin_Api
{

	//配置列表
	public function index(){
		$post_data = array(
			'inter_id'=>$this->session->get_admin_inter_id(),
			);
		//请求登录配置信息URL
		$level_config = $this->doCurlPostRequest( PMS_PATH_URL."adminmember/get_all_level_config" , $post_data );
		$data = array(
			'levelconfig' =>$level_config['data'],
            'inter_id'=>$this->session->get_admin_inter_id()
        );
		$html= $this->_render_content($this->_load_view_file('edit'),$data,false);
	}

	//增加或修改配置
	public function edit_post(){
		$post_level_data = $_POST;
		$post_data['inter_id'] =$this->session->get_admin_inter_id();
		$level_config = $this->doCurlPostRequest( PMS_PATH_URL."adminmember/get_all_level_config" , $post_data );
		//对修改的数据进行处理
        if(isset($level_config['data']) && !empty($level_config['data'])){
            foreach ($level_config['data'] as $key => $value) {
                $level_id = $value['member_lvl_id'];
                //进行修改
                $post_lvl_data = array(
                    'inter_id'=>$this->session->get_admin_inter_id(),
                    'lvl_name'=> $post_level_data['lvl_name_'.$level_id],
                    'lvl_pms_code'=> $post_level_data['lvl_pms_code_'.$level_id],
                    'member_lvl_id'=>$value['member_lvl_id'],
                    'base_discount'=>$post_level_data['base_discount_'.$level_id],
                    'bonus_size'=>$post_level_data['bonus_size_'.$level_id],
                    'consume_bonus_size'=>$post_level_data['consume_bonus_size_'.$level_id],
                    'lvl_up_sort'=>$post_level_data['lvl_up_sort_'.$level_id],
                );

                if(isset($post_level_data['lvl_pms_code_type_'.$level_id]) && !empty($post_level_data['lvl_pms_code_type_'.$level_id]))
                    $post_lvl_data['lvl_pms_code_type']=$post_level_data['lvl_pms_code_type_'.$level_id];

                if($level_id == $post_level_data['is_default']){
                    $post_lvl_data['is_default'] = 't';
                }else{
                    $post_lvl_data['is_default'] = 'f';
                }
                $this->doCurlPostRequest( PMS_PATH_URL."adminmember/update_level_config" , $post_lvl_data );
            }
        }

		//增加
		if( $post_level_data['lvl_name'] && $post_level_data['lvl_pms_code'] ){
			$add_lvl_date = array(
				'inter_id'=>$this->session->get_admin_inter_id(),
				'lvl_name'=> $post_level_data['lvl_name'],
				'lvl_pms_code'=> $post_level_data['lvl_pms_code'],
				'base_discount'=>$post_level_data['base_discount'],
				'bonus_size'=>$post_level_data['bonus_size'],
				'consume_bonus_size'=>$post_level_data['consume_bonus_size'],
				'lvl_up_sort'=>$post_level_data['lvl_up_sort'],
				'createtime'=>time(),
            );

            if(isset($post_level_data['lvl_pms_code_type']) && !empty($post_level_data['lvl_pms_code_type']))
                $add_lvl_date['lvl_pms_code_type']=$post_level_data['lvl_pms_code_type'];

			$this->doCurlPostRequest( PMS_PATH_URL."adminmember/add_level_config" , $add_lvl_date );
		}
		redirect('membervip/memberlevel');
		exit;
	}

}
?>