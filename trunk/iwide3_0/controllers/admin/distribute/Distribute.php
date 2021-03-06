<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Distribute extends MY_Admin {

// 	protected $label_module= NAV_HOTELS;
	protected $label_module= '分销';
	protected $label_controller= '分销';
	protected $label_action= '';
	
	function __construct(){
		parent::__construct();
	}
	
	protected function main_model_name()
	{
		return 'distribute/distribute_model';
	}
	public function index(){
		$this->grid();
	}
	public function grid()
	{
	    $inter_id= $this->session->get_admin_inter_id();
        $entity_id = $this->session->get_admin_hotels ();

	    if($inter_id== FULL_ACCESS) $filter= array();
	    else if($inter_id) $filter= array('inter_id'=>$inter_id );
	    else $filter= array('inter_id'=>'deny' );
	    //print_r($filter);die;
	    if ($this->input->get('t') == 2){
	    	$filter['saler'] = -1;
	    }elseif ($this->input->get('t') == 3){
	    	$filter['saler'] = -2;
	    }elseif ($this->input->get('t') == 4){
	    	$filter['saler'] = -3;
	    }else{
	    	$filter['saler >'] = 0;
	    }

        if (! empty ( $entity_id )) {
            $filter ['hotel_id'] = explode ( ',', $entity_id );
        }
// 	    if(is_ajax_request())
// 	    	$get_filter= $this->input->post();
//     	else
//     		$get_filter= $this->input->get('filter');
    	  
//     	if( !$get_filter) $get_filter= $this->input->get('filter');
    	  
//     	if(is_array($get_filter)) $filter= $get_filter+ $filter;
		$filter = array_merge($_POST, $filter);
	    $this->_grid($filter);
	}
	public function incomes(){
		$inter_id = $this->session->get_admin_inter_id ();
		$sort = 'ALL';
		if($this->input->get('sort')){
			$this->session->set_userdata('income_sort_type',$this->input->get('sort'));
		}
		if($this->session->userdata('income_sort_type'))
			$sort = $this->session->userdata('income_sort_type');
		if ($inter_id == FULL_ACCESS)
			$filter = array ();
		else if ($inter_id)
			$filter = array ( 'inter_id' => $inter_id );
		else
			$filter = array ( 'inter_id' => 'deny' );
		if ($this->input->get ( 't' ) == 2) {
			$filter ['saler'] = - 1;
		} elseif ($this->input->get ( 't' ) == 3) {
			$filter ['saler'] = - 2;
		} elseif ($this->input->get ( 't' ) == 4) {
			$filter ['saler'] = - 3;
		} else {
			$filter ['saler >'] = 0;
		}
		$filter = array_merge ( $_POST, $filter );
		$this->load->model ( 'distribute/distribute_model' );
		$key = $this->input->get_post ( 'key' ) ? trim ( $this->input->get_post ( 'key' ) ) : NULL;
		$begin_time = $this->input->get_post ( 'begin_time' ) ? trim ( $this->input->get_post ( 'begin_time' ) ) : NULL;
		$end_time = $this->input->get_post ( 'end_time' ) ? trim ( $this->input->get_post ( 'end_time' ) ) : NULL;
		$sub_fix = '?a=';
		if (! empty ( $key )) {
			$sub_fix .= '&key=' . $key;
		}
		if (! empty ( $begin_time )) {
			$sub_fix .= '&begin_time=' . $begin_time;
		}
		if (! empty ( $end_time )) {
			$sub_fix .= '&end_time=' . $end_time;
		}
		$this->load->library ( 'pagination' );
		$config ['per_page'] = 20;
		$page = empty ( $this->uri->segment ( 4 ) ) ? 0 : ($this->uri->segment ( 4 ) - 1) * $config ['per_page'];
		$config ['use_page_numbers'] = TRUE;
		$config ['cur_page'] = $page;
		$config ['uri_segment'] = 4;
		$config ['suffix'] = $sub_fix;
		$config ['numbers_link_vars'] = array ( 'class' => 'number' );
		$config ['cur_tag_open'] = '<a class="number current" href="#">';
		$config ['cur_tag_close'] = '</a>';
		$config ['base_url'] = base_url ( "index.php/distribute/distribute/incomes" );
		$config ['total_rows'] = $this->distribute_model->get_all_incomes_count ( $inter_id, NULL, $key, $begin_time, $end_time, 1, $sort );
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
		$query = $this->distribute_model->get_all_incomes ( $inter_id, NULL, $key, $begin_time, $end_time, $page, $config ['per_page'], 1, $sort );
		$view_params = array ( 'pagination' => $this->pagination->create_links (), 'res' => $query->result () );
		$html = $this->_render_content ( $this->_load_view_file ( 'incomes' ), $view_params, TRUE );
		echo $html;
	}
	public function income_salers(){
		$inter_id= $this->session->get_admin_inter_id();
		$sort = 'ALL';
		if($this->input->get('sort')){
			$this->session->set_userdata('income_saler_sort_type',$this->input->get('sort'));
		}
		if($this->session->userdata('income_saler_sort_type'))
			$sort = $this->session->userdata('income_saler_sort_type');
		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );
		if ($this->input->get('t') == 2){
			$filter['saler'] = -1;
		}elseif ($this->input->get('t') == 3){
			$filter['saler'] = -2;
		}elseif ($this->input->get('t') == 4){
			$filter['saler'] = -3;
		}else{
			$filter['saler >'] = 0;
		}
		$filter = array_merge($_POST, $filter);
		$this->load->model('distribute/distribute_model');
		$key        = $this->input->get_post('key') ? trim($this->input->get_post('key')) : NULL;
		$begin_time = $this->input->get_post('begin_time') ? trim($this->input->get_post('begin_time')) : NULL;
		$end_time   = $this->input->get_post('end_time') ? trim($this->input->get_post('end_time')) : NULL;
		$sub_fix = '?a=';
		if($key){
			$sub_fix .= '&key='.$key;
		}
		if($begin_time){
			$sub_fix .= '&begin_time='.$begin_time;
		}
		if($end_time){
			$sub_fix .= '&end_time='.$end_time;
		}
		$this->load->library('pagination');
		$config['per_page']          = 20;
		$page = empty($this->uri->segment(4)) ? 0 : ($this->uri->segment(4) - 1) * $config['per_page'];
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$config['uri_segment']       = 4;
		$config['suffix']            = $sub_fix;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = base_url("index.php/distribute/distribute/income_salers");
		$config['total_rows']        = $this->distribute_model->get_all_incomes_group_count($inter_id,NULL,$key,$begin_time,$end_time,1,$sort);
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
		$query      = $this->distribute_model->get_all_incomes_group($inter_id,NULL,$key,$begin_time,$end_time,$page,$config['per_page'],1, $sort);
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'key'        => $key,
				'begin_time' => $begin_time,
				'end_time'   => $end_time,
				'res'        => $query->result()
		);
		$html= $this->_render_content($this->_load_view_file('group_saler'), $view_params, TRUE);
		echo $html;
	}
	public function hotel_config(){
		$this->load->model('distribute/distribute_model');
		$inter_id = $this->session->get_admin_inter_id();
		$publics  = array();
		if($inter_id== FULL_ACCESS && $this->input->get('inter_id')){
			$inter_id = $this->input->get('inter_id');
			$this->load->model('wx/publics_model');
			$publics  = $this->publics_model->get_public();
		}else if($inter_id == FULL_ACCESS){
			$this->load->model('wx/publics_model');
			$publics  = $this->publics_model->get_public();//print_r($publics);die;
// 			$publics_0 = $publics[0];
			$inter_id = $publics[0]->inter_id;
		}
		$config_res = $this->distribute_model->get_hotel_config($inter_id)->result();
		$protection_config = $this->distribute_model->get_distribution_protection_config($inter_id);
		$sub_config  = array();
		$room_config = array();
		$pac_config  = array();
		$mall_config  = array();
		foreach($config_res as $cfg){
			if($cfg->excitation_category == 1){
				$sub_config['val'] = $cfg->excitation_value;
				$sub_config['typ'] = $cfg->excitation_type;
			}elseif($cfg->excitation_category == 2){
				$room_config['type']  = $cfg->excitation_type;
				$room_config['val_staff'] = $cfg->excitation_value;
				$room_config['val_hotel'] = $cfg->hotel_value;
				$room_config['val_jfk']   = $cfg->jfk_value;
				$room_config['val_group'] = $cfg->group_value;
			}elseif($cfg->excitation_category == 3){
				$pac_config['val'] = $cfg->excitation_value;
			}elseif($cfg->excitation_category == 4){
				$mall_config['type']  = $cfg->excitation_type;
				$mall_config['val'] = $cfg->excitation_value;
			}
		}
		//获取绩效期限信息
		$distribute_config_data = array();
		$distribute_time = $this->distribute_model->get_distribute_config_data($inter_id)->row_array();
		if(!empty($distribute_time)){
			$dis_value = !empty($distribute_time['value'])?unserialize($distribute_time['value']):array();
			$distribute_config_data['distribute_time'] = isset($dis_value['distribute_time'])?$dis_value['distribute_time']:'';
			$distribute_config_data['distribute_status'] = isset($dis_value['distribute_status'])?$dis_value['distribute_status']:0;
		}
		$this->load->model('distribute/fans_model');
		$dis_config = json_decode($this->fans_model->get_redis_key_status('FANS_SOURCE_INFO_UPDATE'.$inter_id));
		$view_params= array(
				'publics'     => $publics,
				'sub_config'  => $sub_config,
				'room_config' => $room_config,
				'mall_config' => $mall_config,
				'dis_config'  => $dis_config,
				'pac_config'  => $pac_config,
				'protection_config'  => $protection_config,
				'distribute_config_data'  => $distribute_config_data,
		);
	
		$html= $this->_render_content($this->_load_view_file('hotel_config'), $view_params, TRUE);
		echo $html;
	}

	public function save_distribute_data_config(){
		$distribute_time = $this->input->post('distribute_time',true);
		$distribute_status = $this->input->post('distribute_status',true);
		//$distribute_time = 1;//默认一年
		$inter_id = $this->session->get_admin_inter_id();
		$arr = array('distribute_time'=>$distribute_time,'distribute_status'=>$distribute_status);
		$data = array();
		$data['inter_id'] = $inter_id;
		$data['code'] = 'distribute_time_limit';//绩效期限时间 标志
		$data['value'] = serialize($arr);
		$data['add_time'] = time();
		$this->load->model('distribute/distribute_model');
		$res = $this->distribute_model->save_distribute_config_data($inter_id,$data);
		if($res){
			MYLOG::w('更新数据成功，数据为：'.json_encode($data), 'admin_dis__conf');
			echo json_encode(array('errmsg'=>'ok'));
		}else{
			echo json_encode(array('errmsg'=>'faild'));
		}
	}
	
	public function save_fans_config(){
		$amount = $this->input->post('amount',true);
		$this->load->model('distribute/distribute_model');
		$type = $this->input->post('type',true);
		if($this->distribute_model->save_hotel_config($this->session->get_admin_inter_id(),1,$amount,$type)){
			$this->load->model('distribute/fans_model');
			$this->fans_model->set_fans_source_update_config($this->session->get_admin_inter_id(),$this->input->post('can_update'),$this->input->post('update_before'));
			echo json_encode(array('errmsg'=>'ok'));
		}else{
			echo json_encode(array('errmsg'=>'faild'));
		}
	}
	public function save_package_config(){
		$amount = $this->input->post('amount',true);
		$this->load->model('distribute/distribute_model');
		$type = $this->input->post('type',true);
		if($this->distribute_model->save_hotel_config($this->session->get_admin_inter_id(),3,$amount,$type)){
			echo json_encode(array('errmsg'=>'ok'));
		}else{
			echo json_encode(array('errmsg'=>'faild'));
		}
	}
	public function save_mall_config(){
		$amount = $this->input->post('amount',true);
		$this->load->model('distribute/distribute_model');
		$type = $this->input->post('type',true);
		if($this->distribute_model->save_hotel_config($this->session->get_admin_inter_id(),4,$amount,$type)){
			echo json_encode(array('errmsg'=>'ok'));
		}else{
			echo json_encode(array('errmsg'=>'faild'));
		}
	}
	public function save_room_config(){
		$amount = $this->input->post('staff_amount',true);
		$type = $this->input->post('type',true);
		$this->load->model('distribute/distribute_model');
		if($this->distribute_model->save_hotel_config($this->session->get_admin_inter_id(),2,$amount,$type,floatval($this->input->post('jfk_amount')),floatval($this->input->post('group_amount')),floatval($this->input->post('hotel_amount')))){
			echo json_encode(array('errmsg'=>'ok'));
		}else{
			echo json_encode(array('errmsg'=>'faild'));
		}
	}
	
	public function send_items(){
		$this->load->model('distribute/distribute_model');
		$ids = $this->input->post('ids');
		if(stripos(',',$ids) !== FALSE){
			$ids = array($ids);
		}else{
			$ids = explode(',',$ids);
		}
		$res = $this->distribute_model->send_grades_by_ids($this->session->get_admin_inter_id(),$ids);
		echo json_encode($res);
	}
	public function send_saler(){
		$this->load->model('distribute/distribute_model');
		$ids = $this->input->post('ids');
// 		if(stripos(',',$ids) !== FALSE){
// 			$ids = array($ids);
// 		}else{
// 			$ids = explode(',',$ids);
// 		}

		$begin_date = $this->input->post('bd');
		$end_date   = $this->input->post('ed');
		
		$res = $this->distribute_model->send_grades_by_saler($this->session->get_admin_inter_id(),$ids,'',$begin_date,$end_date);
		echo json_encode($res);
	}
	public function edit()
	{
		$this->label_action= '分销管理';
		$this->_init_breadcrumb($this->label_action);
	
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
	
		$id= intval($this->input->get('ids'));
		if($id){
			//for edit page.
			$model= $model->load($id);
			$fields_config= $model->get_field_config('form');
// 			$sql= "select a.* from {$this->db->dbprefix}shp_goods_attr as a left join {$this->db->dbprefix}shp_attrbutes as b on a.attr_id=b.attr_id where a.gs_id=". $id;
// 			$detail_field= $this->db->query($sql)->result_array();
			$detail_field = array();
			if( count($detail_field)>0 ){
				$detail_field= $detail_field[0]['attr_value'];
			} else {
				$detail_field= '';
			}
				
		} else {
			//for add page.
			$model= $model->load($id);
			if(!$model) $model= $this->_load_model();
			$fields_config= $model->get_field_config('form');
			$detail_field= '';
		}
	
		//获取相册数组
// 		$gallery= $model->get_gallery();
	
		$view_params= array(
				'model'=> $model,
				'fields_config'=> $fields_config,
				'check_data'=> FALSE,
				'detail_field'=> $detail_field,
// 				'gallery'=> $gallery,
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
	
		$labels= $model->attribute_labels();
		$base_rules= array(
				'name'=> array(
						'field' => 'name',
						'label' => $labels ['name'],
						'rules' => 'trim|required' 
				),
				'price' => array (
						'field' => 'price',
						'label' => $labels ['price'],
						'rules' => 'trim|required' 
				),
				'oprice' => array (
						'field' => 'oprice',
						'label' => $labels ['oprice'],
						'rules' => 'trim|required' 
				),
				'description' => array (
						'field' => 'description',
						'label' => $labels ['description'],
						'rules' => 'trim|required' 
				),
				'nums' => array (
						'field' => 'nums',
						'label' => $labels ['nums'],
						'rules' => 'trim|required' 
				),
				'bed_num' => array (
						'field' => 'bed_num',
						'label' => $labels ['bed_num'],
						'rules' => 'trim|required' 
				),
				'area' => array (
						'field' => 'area',
						'label' => $labels ['area'],
						'rules' => 'trim|required' 
				),
				'status' => array (
						'field' => 'status',
						'label' => $labels ['status'],
						'rules' => 'trim|required' 
				),
				'sort' => array (
						'field' => 'sort',
						'label' => $labels ['sort'],
						'rules' => 'trim|required' 
				),
				'room_id' => array (
						'field' => 'room_id',
						'label' => $labels ['room_id'],
						'rules' => 'trim|required' 
				),
				'hotel_id' => array (
						'field' => 'hotel_id',
						'label' => $labels ['hotel_id'],
						'rules' => 'trim|required' 
				),
				'inter_id' => array (
						'field' => 'inter_id',
						'label' => $labels ['inter_id'],
						'rules' => 'trim|required' 
				) 
		);
		
		// 检测并上传文件。
		$post = $this->_do_upload ( $post, 'gs_logo');
		 
		$adminid= $this->session->get_admin_id();
		 
		if( empty($post[$pk]) ){
			//add data.
			$this->form_validation->set_rules($base_rules);
	
			if ($this->form_validation->run() != FALSE) {
				$post['add_date']= date('Y-m-d H:i:s');
				$post['add_user']= $adminid;
				 
				$result= $model->m_sets($post)->m_save($post);
				$message= ($result)?
				$this->session->put_success_msg('已新增数据！'):
				$this->session->put_notice_msg('此次数据保存失败！');
				//$this->_log($model);
				$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	
			} else
				$model= $this->_load_model();
	
		} else {
			$this->form_validation->set_rules($base_rules);
			if ($this->form_validation->run() != FALSE) {
				$post['last_update_time']= date('Y-m-d H:i:s');
				$post['last_update_user']= $adminid;
	
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
		$view_params= array(
				'model'=> $model,
				'fields_config'=> $fields_config,
				'check_data'=> TRUE,
		);
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		echo $html;
	}
	public function batch_update(){
		set_time_limit(0);
		$config ['upload_path'] = './';
		$config ['allowed_types'] = '*';
		$config ['file_name'] = date ( 'YmdHis' ) . rand ( 10, 99 );
		// $config['allowed_types'] ='png|jpg|jpeg|bmp|gif';
		$config ['max_size'] = '20000';
		$this->load->library ( 'upload', $config );
		$this->upload->initialize ( $config );
		
		if ($this->upload->do_upload ( 'userfile' )) {
			$a = $this->upload->data ();
			$file = realpath('./'. $a ['file_name']);
			$this->load->library ( 'Spreadsheet_Excel_Reader' );
			$data = new Spreadsheet_Excel_Reader ();
			$data->setOutputEncoding ( 'utf-8' );
			$data->read ( $file );
			$datas = array ();
			echo '<html><head></head><body>';
			for($i = 2; $i <= $data->sheets [0] ['numRows']; $i ++) {
				$inter_id = $this->session->get_admin_inter_id();
				$this->load->model('distribute/distribute_model');
				if($this->distribute_model->update_distribute_info($data->sheets [0] ['cells'] [$i] [1],$data->sheets [0] ['cells'] [$i] [2],$data->sheets [0] ['cells'] [$i] [3],$data->sheets [0] ['cells'] [$i] [4],$data->sheets [0] ['cells'] [$i] [5] == '住哲')){
					echo '订单：'.$data->sheets [0] ['cells'] [$i] [1].'更新成功..<br />';
				}else{
					echo '订单：'.$data->sheets [0] ['cells'] [$i] [1].'更新失败..<br />';
				}
				
			}
			echo '</body></html>';
			if (file_exists ( $file )) {
				unlink ( $file );
			}
		} else {
			echo  $this->upload->display_errors('<p>', '</p>');exit;
		}
	
	}
	
	public function deli_config(){
		$this->load->model('distribute/grades_model');
		$inter_id = $this->session->get_admin_inter_id();
		$publics  = array();
		if($inter_id== FULL_ACCESS && $this->input->get('inter_id')){
			$inter_id = $this->input->get('inter_id');
			$this->load->model('wx/publics_model');
			$publics  = $this->publics_model->get_public();
		}else if($inter_id == FULL_ACCESS){
			$this->load->model('wx/publics_model');
			$publics  = $this->publics_model->get_public();//print_r($publics);die;
			// 			$publics_0 = $publics[0];
			$inter_id = $publics[0]->inter_id;
		}
		$conf = $this->grades_model->get_deliver_setting($inter_id);
		$view_params= array(
				'publics' => $publics,
				'conf'    => $conf
		);
		
		echo $this->_render_content($this->_load_view_file('deliver_config'), $view_params, TRUE);
	}
	public function save_deliv_config(){
		$this->load->model('distribute/grades_model');
		$param['inter_id']        = $this->session->get_admin_inter_id();
		$param['mode']            = $this->input->post('mode');
		$param['cycle']           = $this->input->post('send_cycle');
		$param['send_time']       = $this->input->post('send_time');
		$param['send_after_time'] = empty($this->input->post('after_time')) ? '0000-00-00 00:00:00' : $this->input->post('after_time');
		if($this->grades_model->set_deliver_setting($param)){
			echo json_encode(array('errmsg'=>'ok'));
		}else{
			echo json_encode(array('errmsg'=>'faild'));
		}
	}
	public function save_protection_config(){
		$this->load->model('distribute/distribute_model');
		if($this->distribute_model->save_distribution_protection_config($this->session->get_admin_inter_id(), $this->input->post('status'), empty($this->input->post('time')) ? 0 : intval($this->input->post('time')) * 3600)){
			echo json_encode(array('errmsg'=>'ok'));
		}else{
			echo json_encode(array('errmsg'=>'faild'));
		}
	}
	
	/**
	 * 已配置发放配置的公众号列表
	 */
	public function accounts_cfgs() {
		$this->load->model ( 'distribute/grades_model' );
		$this->load->model ( 'distribute/distribute_model' );
		$this->load->model ( 'wx/publics_model' );
		if ($this->input->post ()) {
			$inter_ids = $this->input->post ( 'asts[]' );
			if ($this->grades_model->update_setting_deliver ( $inter_ids,TRUE ))
				echo 'success';
			else
				echo 'success';
			exit ();
		}
		$admin_inter_ids = $this->session->get_admin_inter_id () == 'ALL_PRIVILEGES' ? '' : $this->session->get_admin_inter_id ();
		$params = array ();
		if (! empty ( $admin_inter_ids ))
			$params = array ( 'inter_id' => $admin_inter_ids );
		$publics = $this->publics_model->get_public_hash ( $params, array ( 'inter_id', 'name' ) );
		$datas ['deliver_account'] = $this->distribute_model->get_redis_key_status('__DISTRIBUTION_DELIER_ACCOUNT');
		$datas ['publics'] = $this->publics_model->array_to_hash ( $publics, 'name', 'inter_id' );
		$datas ['ac_settings'] = $this->grades_model->get_deliver_setting ( $admin_inter_ids );
		echo $this->_render_content ( $this->_load_view_file ( 'accounts' ), $datas, TRUE );
	}
	public function ds_set(){
		if(!empty($this->input->get('iid'))){
			$this->load->model ( 'distribute/distribute_model' );
			if($this->distribute_model->set_redis_key_status('__DISTRIBUTION_DELIER_ACCOUNT',$this->input->get('iid'))){
				echo 'success';
				exit;
			}
		}
		echo 'fail';
		exit;
	}
}
