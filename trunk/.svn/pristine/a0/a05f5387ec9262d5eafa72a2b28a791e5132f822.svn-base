<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	购卡
*	@author Frandon
*	@time 四月十一号
*	@version www.iwide.cn
*	@
*/
class Depositcard extends MY_Admin_Api
{

	//购卡列表
	public function index(){
        $avgs = array();
        $this->load->model('membervip/admin/Member_model');
        $admin_profile = $this->session->userdata('admin_profile');
        $this->load->library('pagination');
        $config['per_page']          = 20;
        $page = empty($this->uri->segment(4)) ? 0 : ($this->uri->segment(4) - 1) * $config['per_page'];
//        $filterH ['inter_id'] = $admin_profile['inter_id'];
        $config['use_page_numbers']  = TRUE;
        $config['cur_page']          = $page;
        $avgs['inter_id'] = $admin_profile['inter_id'];
        $avgs['field'] = 'deposit_card_id,title,deposit_type,money,is_package,package_id,is_active';
        $res = $this->Member_model->get_deposit_card($avgs,$config['per_page'],$config['cur_page']);

        $config['uri_segment']       = 4;

        $config['numbers_link_vars'] = array('class'=>'number');
        $config['cur_tag_open']      = '<a class="number current" href="#">';
        $config['cur_tag_close']     = '</a>';
        $config['base_url']          = site_url("membervip/depositcard/index");
        $config['total_rows']        = $this->Member_model->get_deposit_card_total($avgs);
        $config['cur_tag_open'] = '<li class="paginate_button active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="paginate_button">';
        $config['num_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="paginate_button first">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="paginate_button last">';
        $config['last_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="paginate_button previous">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="paginate_button next">';
        $config['next_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $view_params= array(
            'pagination' => $this->pagination->create_links(),
            'cardlist'        => $res,
            'posts'      => $avgs,
            'total'      => $config['total_rows'],
        );

		$html= $this->_render_content($this->_load_view_file('edit'),$view_params,false);
	}

	//增加业务规则
	public function add(){
		$deposit_card_id = $this->input->get('deposit_card_id');
		$inter_id = $this->session->get_admin_inter_id();
        $post_data = array(
            'inter_id'=>$inter_id,
            'deposit_card_id'=>$deposit_card_id,
        );
		//请求卡券的详细信息(修改时有结果)
		$cardinfo = $this->doCurlPostRequest( PMS_PATH_URL."depositcard/getinfo" , $post_data );
        $data['cardinfo'] = !empty($cardinfo['data'])?$cardinfo['data']:array();
        $wechat_checked = 'checked';
        $balance_checked = '';
        $balance_disabled = '';
		if(!empty($data['cardinfo'])){
            if($data['cardinfo']['deposit_type'] == 'c'){
                $balance_disabled = 'disabled';
            }

            $pay_type = explode(',',$data['cardinfo']['pay_type']);
            $wechat_checked = '';
            foreach ($pay_type as $vo){
                switch ($vo){
                    case 'wechat':
                        $wechat_checked = 'checked';
                        break;
                    case 'balance':
                        $balance_checked = 'checked';
                        break;
                }
            }
        }
        $data['wechat_checked'] = $wechat_checked;
        $data['balance_checked'] = $balance_checked;
        $data['balance_disabled'] = $balance_disabled;
        $this->_render_content($this->_load_view_file('add'),$data,false);
	}


	//保存增加或修改优惠券
	public function edit_post(){
        $inter_id = $this->session->get_admin_inter_id();
		$deposit_card_id = $this->input->post('deposit_card_id');
		$data = $this->input->post();
		if(empty($data['pay_type'])){
            $this->_ajaxReturn('请选择支付方式!',null,0);
        }

        if(in_array('balance',$data['pay_type']) && $data['deposit_type'] == 'c'){
            $this->_ajaxReturn('储值支付方式不支持 "直接储值" 类型!',null,0);
        }

        if(in_array('balance',$data['pay_type']) && $data['is_balance'] == 't'){
            $this->_ajaxReturn('储值支付方式不可计入余额!',null,0);
        }

        $pay_type = implode(',',$data['pay_type']);

        $data['pay_type'] = $pay_type;
		foreach ($data as $key => $value) {
			if(!$value) unset($data[$key]);
		}

        $data['inter_id'] = $inter_id;
		unset($data['card_id']);
		//如果ID存在则为修改否则增加
		if($deposit_card_id){
			unset($data['deposit_card_id']);
			$post_data = array(
				'inter_id'=>$inter_id,
				'data'=>$data,
				'deposit_card_id'=>$deposit_card_id,
				);
			$update_result = $this->doCurlPostRequest( PMS_PATH_URL."depositcard/update" , $post_data );
            if(is_ajax_request()){
                $retrun = $update_result;
                $retrun['isadd'] = false;
                if($update_result['err']>0){
                    $this->_ajaxReturn('保存失败!',null,0);
                }
                $this->_ajaxReturn('保存成功!',$retrun,1);
            }
            redirect('membervip/depositcard/add?deposit_card_id='.$deposit_card_id);
		}else{
			$data['inter_id'] = $inter_id;
			$add_result = $this->doCurlPostRequest( PMS_PATH_URL."depositcard/add" , $data );
            if(is_ajax_request()){
                $retrun = $add_result;
                $retrun['isadd'] = true;
                if($add_result['err']>0){
                    $this->_ajaxReturn('添加失败!',$retrun,0);
                }
                $this->_ajaxReturn('添加成功!',$retrun,1);
            }
			redirect('membervip/depositcard');
		}
	}

}
?>