<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(E_ALL);
class Msgs extends MY_Admin {
	
	public function __construct(){
		parent::__construct();
		if($this->input->get('debug') == 1){
			$this->output->enable_profiler(true);
		}
	}
	
	public function edit(){
		$this->label_action= '编辑消息';
		$this->_init_breadcrumb($this->label_action);
		$this->load->model('hotel/hotel_model');
		
		$view_params= array(
				'check_data'=> FALSE,
				'hotels'=>$this->hotel_model->get_hotel_hash(array('inter_id'=>$this->session->userdata('inter_id')),array('hotel_id','name'))
		);
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		echo $html;
	}
	public function save_edit(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', '标题', 'required',array('required' => '请填写%s.'));
        $this->form_validation->set_rules('content', '内容', 'required',array('required' => '请填写%s.'));

        if ($this->form_validation->run() == FALSE){
	        $validat_obj= _get_validation_object();
	        $message= $validat_obj->error_html();
	        $this->session->put_error_msg($message, 'register');
	        
	        $view_params= array(
	        		'form_data'=> $this->input->post () 
			);
			echo $this->_render_content ( $this->_load_view_file ( 'edit' ), $view_params, TRUE );
		} else {
			$this->load->model ( 'distribute/idistribute_model' );
			$admin_profile = $this->session->userdata ( 'admin_profile' );
			$params = $this->input->post ();
			$params ['inter_id'] = $admin_profile ['inter_id'];
			$params ['msg_typ'] = 1;
			if($this->uri->segment(4) == 'qa'){
				$params ['msg_typ'] = 2;
			}
			if ($this->idistribute_model->create_notice ( $params )) {
				$this->session->put_success_msg ( '发送成功' );
				redirect ( 'distribute/msgs/logs' );
			} else {
				$this->session->put_error_msg ( '发送失败' );
				$view_params = array (
						'form_data' => $this->input->post () 
				);
				echo $this->_render_content ( $this->_load_view_file ( 'edit' ), $view_params, TRUE );
			}
		}
	}
	public function save_push(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', '标题', 'required',array('required' => '请填写%s.'));
        $this->form_validation->set_rules('content', '内容', 'required',array('required' => '请填写%s.'));
        $this->form_validation->set_rules('salers', '推送对象', 'required',array('required' => '请选择%s.'));

        if ($this->form_validation->run() == FALSE){
	        $validat_obj= _get_validation_object();
	        $message= $validat_obj->error_html();
	        $this->session->put_error_msg($message, 'register');
	        
	        $view_params= array(
	        		'form_data'=> $this->input->post () 
			);
			echo $this->_render_content ( $this->_load_view_file ( 'edit_push' ), $view_params, TRUE );
		} else {
			$this->load->model ( 'distribute/idistribute_model' );
			$admin_profile = $this->session->userdata ( 'admin_profile' );
			$params = $this->input->post ();
			$params ['inter_id']  = $admin_profile ['inter_id'];
			$params ['msg_typ']   = 1;
			$params ['top']       = 2;
			$params ['push_time'] = $this->input->post('push_time');
			$salers = $this->input->post('salers');
			$salers = explode(',', $salers);
			$qrcodes = array();
			foreach ($salers as $saler){
				$id = substr($saler,0,10);
				$qrcode_id = substr($saler, 10);
				$qrcodes[$id][] = $qrcode_id;
			}
			
			$ext_package = array();
			if($this->input->post('package') && !empty($this->input->post('package'))){
				$ext_package['package'] = array('inter_id'=>substr($this->input->post('package'),0,10),'pid'=>substr($this->input->post('package'),10));
			}
			if($this->input->post('btn_guide')){
				$ext_package['guide'] = array('button'=>$this->input->post('btn_guide'),'link'=>$this->input->post('lnk_guide'));
			}
			$params['remark'] = serialize($ext_package);
			$success_count = 0;
			$failed_count  = 0;
			foreach ($qrcodes as $k=>$v){
				$params['inter_id'] = $k;
				$params['qrcodes']   = $v;
				if ($this->idistribute_model->create_notice ( $params )) {
					$success_count ++;
				} else {
					$failed_count ++;
				}
			}
			if($success_count > 0){
				$this->session->put_success_msg ( '发送成功' );
				redirect ( 'distribute/msgs/logs/1' );
			}else{
				$this->session->put_error_msg ( '发送失败' );
				$view_params = array ( 'form_data' => $this->input->post () );
				echo $this->_render_content ( $this->_load_view_file ( 'edit_push' ), $view_params, TRUE );
			}
		}
	}
	public function logs() {
		$inter_id = $this->session->get_admin_inter_id ();
		$this->load->model ( 'distribute/distribute_notice_model' );
		$this->load->library ( 'pagination' );
		$config ['per_page'] = 20;
		$page = empty ( $this->uri->segment ( 5 ) ) ? 0 : ($this->uri->segment ( 5 ) - 1) * $config ['per_page'];
		$config ['use_page_numbers'] = TRUE;
		$config ['cur_page'] = $page;
		$config ['uri_segment'] = 5;
		$config ['numbers_link_vars'] = array (
				'class' => 'number' 
		);
		$cate = '1';
		if($this->uri->segment(4)){
			$cate = $this->uri->segment(4);
		}
		$config ['cur_tag_open'] = '<a class="number current" href="#">';
		$config ['cur_tag_close'] = '</a>';
		$config ['base_url'] = base_url ( "index.php/distribute/msgs/logs/".$cate );
		$config ['total_rows'] = $this->distribute_notice_model->get_notices_count ( $inter_id, $cate);
		$config ['cur_tag_open'] = '<li class="paginate_button active"><a>';
		$config ['cur_tag_close'] = '</a></li>';
		$config ['num_tag_open'] = '<li class="paginate_button">';
		$config ['num_tag_close'] = '</li>';
		$config ['first_tag_open'] = '<li class="paginate_button first">';
		$config ['first_tag_close'] = '</li>';
		$config ['last_tag_open'] = '<li class="paginate_button last">';
		$config ['last_tag_close'] = '</li>';
		$config ['prev_tag_open'] = '<li class="paginate_button previous">';
		$config ['prev_tag_close'] = '</li>';
		$config ['next_tag_open'] = '<li class="paginate_button next">';
		$config ['next_tag_close'] = '</li>';
		$this->pagination->initialize ( $config );
		$query = $this->distribute_notice_model->get_notices ( $inter_id,$cate , $config ['per_page'],$page );
		$view_params = array (
				'pagination' => $this->pagination->create_links (),
				'res' => $query,
				'msg_typs' => array(0 => '绩效发放',1 => '关注提醒',2 => '常见问题',10 => '绩效发放' , 11 => '系统消息',12 => '常见问题')
		);
		$html = $this->_render_content ( $this->_load_view_file ( 'msgs' ), $view_params, TRUE );
		echo $html;
	}
	public function edit_push(){
		$this->label_action= '编辑消息';
		$this->_init_breadcrumb($this->label_action);
		$this->load->model('hotel/hotel_model');
		//会员礼包数据
		$post_data = array(
				'inter_id'=>$this->session->get_admin_inter_id(),
				'token'=>$this->member_token(),
				'num'=>60
		);
		$this->load->helper('common');
		//请求套餐信息URL
		$package_list = json_decode(doCurlPostRequest( INTER_PATH_URL."package/getlist" , http_build_query($post_data )));
		$view_params= array(
				'packages'   => $package_list->data,
				'check_data' => FALSE,
				'hotels'=>$this->hotel_model->get_hotel_hash(array('inter_id'=>$this->session->get_admin_inter_id()),array('hotel_id','name'))
		);
		$html= $this->_render_content($this->_load_view_file('edit_push'), $view_params, TRUE);
		echo $html;
	}
	//公众号
	public function pls(){
		$inter_id = $this->session->get_admin_inter_id();
		$this->load->model('wx/publics_model');
		$params = array();
		if($inter_id != 'ALL_PRIVILEGES')
			$params = array('inter_id' => explode(',', $inter_id));
		if($this->input->post('kpls')){
			$params['name LIKE'] = "%{$this->input->post('kpls')}%";
		}
		$pls = $this->publics_model->get_public_hash($params,array('inter_id','name'));
		echo json_encode($this->publics_model->array_to_hash($pls, 'name', 'inter_id'));
	}
	//酒店
	public function hts(){
		$inter_id = $this->session->get_admin_inter_id();
		$this->load->model('wx/publics_model');
		$params = array();
		if($inter_id != 'ALL_PRIVILEGES')
			$params = array('inter_id' => explode(',', $inter_id));
		$model= $this->_load_model('core/priv_admin');
		$hotel_ids= $model->load($this->session->get_admin_id())->m_get('entity_id');
		if(!empty($hotel_ids)){
			$params['hotel_id'] = explode(',',$hotel_ids);
		}
		$this->load->model('hotel/hotel_model');
		$key = trim($this->input->post('khls'));
		if($key){
			$params['name LIKE'] = "%{$key}%";
		}
		$hotels = $this->hotel_model->get_hotel_hash($params);
		echo json_encode($this->hotel_model->array_to_hash($hotels, 'name', 'hotel_id'));
	}
	//部门
	public function depts(){
		$inter_id = $this->session->get_admin_inter_id();
		$params = array();
		if($inter_id != 'ALL_PRIVILEGES')
			$inter_id = explode(',', $inter_id);
		if($inter_id == 'ALL_PRIVILEGES')
			$inter_id = '';
		$this->load->model('distribute/qrcodes_model');
		$hotels = $this->input->post('hls');
		if(!empty($hotels)){
			$hotels = explode(',', $hotels);
		}
		$depts = $this->qrcodes_model->get_staff_depts($inter_id,trim($this->input->post('kdls')),$hotels);
		$depts = $this->qrcodes_model->get_hash_map($depts,'master_dept');
		$jsos = array();
		foreach ($depts as $dept){
			$jsos[] = $dept->master_dept;
		}
		echo json_encode($jsos);
	}
	//分销员
	public function sals(){
		$this->load->model('distribute/qrcodes_model');
		$inter_id = $this->session->get_admin_inter_id();

		$params = array();
		if($inter_id != 'ALL_PRIVILEGES')
			$params = array('inter_id' => explode(',', $inter_id));
		$params['status'] = 2;
		$params['is_distributed'] = 1;
		$key = trim($this->input->post('ksls'));
		if($key){
			$params['name LIKE'] = "%{$key}%";
		}
		$hotels = $this->input->post('khls');
		if(!empty($hotels)){
			$params['hotel_id'] = explode(',', $hotels);
		}
		$depts = $this->input->post('kdls');
		if(!empty($depts)){
			$params['master_dept'] = explode(',', $depts);
		}
		$salers = $this->qrcodes_model->get_qrcodes_base($params,array('inter_id','name','qrcode_id'),'object');
		$arr = array();
		foreach ($salers as $item){
			$arr[$item->inter_id.$item->qrcode_id] = $item ;
		}
		echo json_encode($arr);
	}
	
	/**
	 * 会员 - 获取验证token
	 * @return string
	 */
	protected function member_token(){
		$post_token_data = array(
				'id'=>'vip',
				'secret'=>'iwide30vip',
		);
		$this->load->helper('common');
		$token_info = doCurlPostRequest( INTER_PATH_URL."accesstoken/get" , http_build_query($post_token_data ));
		return isset($token_info['data'])?$token_info['data']:"";
	}
}