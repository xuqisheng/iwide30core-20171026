<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends MY_Admin {

	protected $label_module= '自定义菜单信息';
	protected $label_controller= '自定义菜单列表';
	protected $label_action= '';
	private $inter_id='';
	
	function __construct(){
		parent::__construct();
		$user_profiler = $this->session->userdata('admin_profile');
		$this->inter_id = $user_profiler['inter_id'];
		if(!$this->inter_id || $this->inter_id == 'ALL_PRIVILEGES')$this->inter_id= 'a429262687';
	}
	
	public function index(){
// 		$this->output->enable_profiler(true);
		$this->label_action= '自定义菜单';
		$this->_init_breadcrumb($this->label_action);
		
// 		$this->inter_id= 'a429262687';
// 		$inter_id= $this->session->get_admin_inter_id();
// 		$hotels = array();
// 		if( $inter_id==FULL_ACCESS ){
// 			$this->db->where(array('status'=>1));
// 			$this->db->select(array('hotel_id','name'));
// 			$hotels = $this->db->get('hotels')->result_array();
// 		}else{
// 			$this->load->model('hotel/hotel_model');
// 			$user_profiler = $this->session->userdata('admin_profile');
// 			$inter_id = $user_profiler['inter_id'];
// 		}
		
		$this->load->model('wx/menu_model');
		$menus    = $this->menu_model->get_menus($this->inter_id);
		$sys_menu = $this->menu_model->_get_sys();
		$data     = array('menus'=>$menus,'sys_menu' => $sys_menu);
		$html     = $this->_render_content($this->_load_view_file('index'),$data,true);
		echo $html;
	}
	
	public function save_menu_item(){
// 		$this->output->enable_profiler(true);
// 		$inter_id= 'a429262687';
		$this->load->model('wx/menu_model');
		echo $this->menu_model->save_menu_item($this->inter_id);//exit; 
	}
	public function save_menu(){
// 		$inter_id= 'a429262687';
		$this->load->model('wx/menu_model');
		echo $this->menu_model->save_menu($this->inter_id);exit; 
	}
	public function delete_menu_item(){
// 		$inter_id= 'a429262687';
		$this->load->model('wx/menu_model');
		echo $this->menu_model->delete_menu_item($this->inter_id,$this->input->post('ids'));exit; 
	}
	public function delete(){
// 		$inter_id= 'a429262687';
		$this->load->model('wx/menu_model');
		echo $this->menu_model->delete($inter_id);exit; 
	}
	public function generate(){
// 		$inter_id= 'a429262687';
		$this->load->model('wx/publics_model');
		$this->load->model('wx/menu_model');
		$this->load->model('wx/access_token_model');
		$publics = $this->publics_model->get_public_by_id($this->inter_id);
// 		echo $this->menu_model->generate_menu($publics['app_id'],$publics['app_secret'],$this->inter_id);exit;
		$accesstoken = $this->access_token_model->reflash_access_token($this->inter_id) ;
		echo $this->menu_model->generate_menu_access_token($this->inter_id,$accesstoken);exit;
	}
}
