<?php
/**
* 比价系统后台
* author chenjunyu
* date 2016-10-30
*/
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Paritys extends MY_Admin{

	private $hotelgroupname = array(
		// 'a421641095'=>'碧桂园酒店集团',//碧桂园
		// 'a449675133'=>'书香酒店',//书香
		// 'a441098524'=>'逸柏酒店',//逸柏
		// // 'a445223616'=>'云盟酒店',//云盟
		// 'a464919542'=>'清沐连锁酒店',//清沐
		// 'a426755343'=>'岭南佳园连锁酒店',//岭南佳园
		// 'a452223043'=>'莫林风尚连锁酒店',//莫林风尚
		// 'a464177542'=>'百时快捷酒店',//百时快捷
		// 'a434597274'=>'流花宾馆',//流花宾馆
		// 'a455510007'=>'速8酒店',//速8
		// 'a450682197'=>'江门柏丽宜居连锁酒店',//江门柏丽宜居
		// 'a456970175'=>'君亭酒店',//君亭
		// // 'a454320235'=>'智尚酒店',//智尚
		// 'a457946152'=>'隐居酒店',//隐居
		// 'a468209719'=>'戴斯酒店集团',//戴斯
		// 'a472731074'=>'金泰旅游',//金泰旅游
		// // 'a440577876'=>'远洲酒店',//远洲
		// 'a472731996'=>'雅斯特酒店',//雅斯特
		// 'a452839067'=>'广州三英温泉度假酒店',//广州三英温泉度假酒店
		// 'a467780350'=>'广州白天鹅宾馆',//广州白天鹅宾馆
		// 'a478659714'=>'北京亚洲大酒店',//北京亚洲大酒店
		// 'a474876699'=>'高远文旅',//高远文旅
		// 'a463564674'=>'上海中油阳光大酒店',//上海中油阳光大酒店
		// 'a476756979'=>'银座佳驿酒店',//银座佳驿酒店
		// 'a481611502'=>'广州恒东商务酒店',//广州恒东商务酒店
		// 'a458179178'=>'成都城市名人酒店',//成都城市名人酒店
		// 'a448960938'=>'成都瑞城名人酒店',
		// 'a468919145'=>'恒大酒店集团',
		);
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '比价系统';
	protected $label_action = '';
	function __construct(){
		parent::__construct();
		$this->inter_id = $this->session->get_admin_inter_id ();
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
	}

	public function index(){
		redirect(site_url('price/paritys/hotel_index'));
	}

	// 获取开通比价的酒店列表
	protected function get_hotels_group(){
		$this->load->model('price/paritys_model');
		$hotels = $this->paritys_model->get_hotels_group();
		foreach($hotels as $hotel){
			$this->hotelgroupname[$hotel['inter_id']] = $hotel['name'];
		}
	}

	//比价首页(管理员版)
	public function admin_index(){
		$data = $this->common_data;
		$this->label_action = '运营版';
		$this->_init_breadcrumb ( $this->label_action );
		$this->get_hotels_group();
		$third_type = 'ctrip';//后续增加这个第三方类型的判断
		$data = array();
		$data['s'] = $this->input->get('s')?$this->input->get('s'):2;
		$sort = $this->input->get('s')==1?'down_rate ASC':'down_rate DESC';
		$data['n'] = $this->input->get('n')?addslashes($this->input->get('n')):20;
		$data['wd'] = $this->input->get('wd')?$this->input->get('wd'):'';
		$search_url = '?';
		if($data['wd']!=''){
			$search_url .= 'wd='.$data['wd'];
		}
		if($this->input->get('s')){
		 	$search_url .= 's='.$this->input->get('s').'&';
		}
		if($this->input->get('n')){
			$search_url .= 'n='.$this->input->get('n').'&';
		}
		if($search_url=='?'){
			$search_url = '';
		}else{
			$search_url = rtrim($search_url,'&');
		}
		$hnames = array();
		if($data['wd']!=''){
			foreach ($this->hotelgroupname as $hk => $hname) {
				if(mb_strpos($hname,$data['wd'])!==false){
					$hnames[$hk] = $hname;
				}
			}
		}else{
			$hnames = $this->hotelgroupname;
		}
		$data['ss'] = array(
			1=>'携程倒挂率从低到高',
			2=>'携程倒挂率从高到低',
			);
		$data['ns'] = array(
			10,
			20,	
			50,
			100,
			);
		$this->load->model('price/paritys_model');
		//分页
		$this->load->library('pagination');
	 	$config['base_url'] = base_url ( "index.php/price/paritys/admin_index" );
	 	$config ['first_url'] = base_url ( "index.php/price/paritys/admin_index" ).$search_url;
	 	$config ['suffix'] = $search_url;
	 	$config ['uri_segment'] = 4;
	 	$count = count($hnames);
	 	$config['total_rows'] = $count;
	 	$config['per_page'] = '3';
	 	$config ['first_link'] = '首页';
  		$config ['last_link'] = '末页';
  		$config ['next_link'] = '下一页';
  		$config ['prev_link'] = '上一页';
  		$config['cur_tag_open'] = ' <a class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</a>'; 
  		$config['use_page_numbers'] = true;
	 	$p = $this->uri->segment(4)?max(1,min(ceil($config['total_rows']/$config['per_page']),$this->uri->segment(4))):1;
	 	$data['pages'] = ceil($config['total_rows']/$config['per_page']);
	 	$offset =  ($p-1)*$config['per_page'];
	 	$config ['cur_page'] = $offset;
	 	$this->pagination->initialize($config);
	 	$data['page'] = $this->pagination->create_links(); 
		$out = array_slice($hnames, $offset, $config['per_page']);
		foreach ($out as $kh => $vh) {
			$lists = $this->paritys_model->getDownRate($kh,$third_type,array('order'=>$sort,'offset'=>0,'nums'=>$data['n']));
			$no_hotel_ids = array();
			foreach ($lists as $k => $v) {
				$data['list'][$vh][] = array(
					'hotel_name' => $v['hotel_name'],
					'hotel_id' => $v['hotel_id'],
					'percent' => $v['down_rate'],
					);
				$no_hotel_ids[] = $v['hotel_id'];
			}
			//补充没有生成的比价结果的酒店
			$hotels = $this->paritys_model->getHotels($kh);
			foreach($hotels as $hotel){
				if(empty($data['list'][$vh])||(!in_array($hotel['hotel_id'],$no_hotel_ids)&&count($data['list'][$vh])<20)){
					$data['list'][$vh][] = array(
						'hotel_name'=>$hotel['name'],
						'hotel_id'=>$hotel['hotel_id'],
						'percent'=>'--',
						);
				}
			}
			$data['inter_ids'][$vh] = $kh;
		}
		$this->_render_content ( $this->_load_view_file ( 'admin_index' ), $data, false );
	}

	//比价首页(酒店版)
	public function hotel_index(){
		$data = $this->common_data;
		$this->label_action = '酒店版';
		$this->_init_breadcrumb ( $this->label_action );
		$this->get_hotels_group();
		$third_type = 'ctrip';//后续增加这个第三方类型的判断
		$data = array();
		$inter_id = $this->input->get('inter_id')?addslashes($this->input->get('inter_id')):$this->inter_id;
		if($inter_id == FULL_ACCESS){
			redirect(site_url('price/paritys/admin_index'));
		}
		$data['inter_id'] = $inter_id;
		$data['hname'] = $this->hotelgroupname[$inter_id];
		$this->load->model('price/paritys_model');
	 	$lists = $this->paritys_model->getDownRate($inter_id,$third_type,array('order'=>'down_rate DESC'));
		// 计算倒挂率
		$no_hotel_ids = array();
		foreach ($lists as $k => $v) {
			$data['list'][] = array(
				'hotel_name' => $v['hotel_name'],
				'hotel_id' => $v['hotel_id'],
				'percent' => $v['down_rate'],
				);
			$no_hotel_ids[] = $v['hotel_id'];
		}
		$data['is_list'] = 1;
		// if(empty($data['list'])){
		// 	$data['is_list'] = 0;
		// }
		if(!in_array($this->inter_id,array_keys($this->hotelgroupname))){
			$data['is_list'] = 0;
		}
		//补充没有生成的比价结果的酒店
		$hotels = $this->paritys_model->getHotels($inter_id);
		foreach($hotels as $hotel){
			if(!in_array($hotel['hotel_id'],$no_hotel_ids)){
				$data['list'][] = array(
					'hotel_name'=>$hotel['name'],
					'hotel_id'=>$hotel['hotel_id'],
					'percent'=>'--',
					);
			}
		}
		$data['hotel_nums'] = count($hotels);
		// 获取当前酒店当天剩余可生成比价次数
		$data['num'] = $this->paritys_model->getParityNum();
		$this->_render_content( $this->_load_view_file( 'hotel_index' ), $data, false);
	}

	//比价结果页
	public function detail(){
		$data = $this->common_data;
		$this->label_action = '详情';
		$this->_init_breadcrumb ( $this->label_action );
		$data['ttype'] = $third_type = $this->input->get('ttype')?$this->input->get('ttype'):'ctrip'; //后续增加这个第三方类型的判断
		$data['inter_id'] = $this->input->get('inter_id')?addslashes($this->input->get('inter_id')):$this->inter_id;
		if($data['inter_id'] == FULL_ACCESS){
			redirect(site_url('price/paritys/admin_index'));
		}
		$data['s'] = $this->input->get('s')?$this->input->get('s'):0;
		$data['wd'] = $this->input->get('wd')?addslashes($this->input->get('wd')):'';
		$data['hotel_id'] = '';
		if($this->input->get('hotel_id')>0){
			$hotel_ids = array(addslashes($this->input->get('hotel_id')));
			$data['hotel_id'] = $data['one_hotel'] = $this->input->get('hotel_id'); 
		}elseif($this->input->get('hotel_ids')>0){
			$hotel_ids = array(addslashes($this->input->get('hotel_ids')));
			$data['hotel_id'] = $this->input->get('hotel_ids');
		}else{
			$hotel_ids = $this->session->get_admin_hotels ();
		}
		$num = $this->input->get('n')?$this->input->get('n'):5;
		$search_conditions = array();
		$search_url = '?inter_id='.$data['inter_id'];
		if(!empty($hotel_ids)){
			$search_url .= '&hotel_ids='.$data['hotel_id'];
			$search_conditions['hotel_ids'] = $hotel_ids;
		}
		if($data['wd']!=''){
			$search_url .= '&wd='.$data['wd'];
			$search_conditions['hotel_name'] = $data['wd'];
		}
		if($this->input->get('s')>0){
		 	$search_url .= '&s='.$this->input->get('s');
		}
	 	$search_conditions['order'] = $data['s']==1?'asc':($data['s']==2?'desc':'');
		$data['ss'] = array(
			1=>'按差价从低到高',
			2=>'按差价从高到低',
			);
		$data['third_type'] = array(
			'ctrip'=>'携程房型',
			'meituan'=>'美团房型',
			'alitrip'=>'阿里房型',
			'qunar'=>'去哪儿房型',
			);
		$this->load->model('price/paritys_model');
		$data['hotel_ids'] = $this->paritys_model->getHotels($data['inter_id'],$this->session->get_admin_hotels ());
		//分页
		$this->load->library('pagination');
		$config ['suffix'] = $search_url;
	 	$config['base_url'] = base_url('index.php/price/paritys/detail');
	 	$config ['first_url'] = base_url ( "index.php/price/paritys/detail" ).$search_url;
	 	$config ['uri_segment'] = 4;
	 	$count = $this->paritys_model->getParitys($data['inter_id'],$third_type,$search_conditions,true);
	 	$config['total_rows'] = $count;
	 	$config['per_page'] = $num;
	 	$config ['first_link'] = '首页';
  		$config ['last_link'] = '末页';
  		$config ['next_link'] = '下一页';
  		$config ['prev_link'] = '上一页';
  		$config['cur_tag_open'] = ' <a class="current">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</a>'; 
  		$config['use_page_numbers'] = true; 
	 	$p = $this->uri->segment(4)?max(1,min(ceil($config['total_rows']/$config['per_page']),$this->uri->segment(4))):1;
	 	$data['pages'] = ceil($config['total_rows']/$config['per_page']);
	 	$offset =  ($p-1)*$config['per_page'];
	 	$config ['cur_page'] = $offset;
		$this->pagination->initialize($config);
	 	$data['page'] = $this->pagination->create_links();
	 	$lists = $this->paritys_model->getParitys($data['inter_id'],$third_type,$search_conditions,false,$offset,$config['per_page']);
	 	$hotelids = array();
	 	foreach ($lists as $k => $val) {
	 		if(!empty($val)){
	 			$hotelids['hotel_ids'][] = $val[0]['hotel_id'];
	 		}
	 		foreach($val as $kv=>$v){
		 		if($v['book_status']=='full'){
		 			$lists[$k][$kv]['book_status'] = '<span style="color:#FF0000;">(满)</span>';
		 		}else{
		 			$lists[$k][$kv]['book_status'] = '';
		 		}
		 		$lists[$k][$kv]['ibreakfast'] = !empty($v['ibreakfast'])?'--'.$v['ibreakfast']:'';
		 	}
	 	}
	 	$down_rates = $this->paritys_model->getDownRate($data['inter_id'],$third_type,$hotelids);
	 	foreach ($down_rates as $k => $val) {
	 		$data['down_rates'][$val['hotel_id']] = $val['down_rate'];
	 	}
	 	//兼容页面合并单元格
	 	foreach($lists as $k=>$list){
		 	$o = 1;
	 		foreach($list as $ko=>$vo){
		 		$f = $vo['iwide_name'].'--'.$vo['iwide_price_name'].$vo['ibreakfast'].$vo['book_status'];
		 		$s = $list[$ko-1]['iwide_name'].'--'.$list[$ko-1]['iwide_price_name'].$list[$ko-1]['ibreakfast'].$list[$ko-1]['book_status'];
		 		if($ko>=1&&$f==$s&&!empty($vo['iwide_name'])){
		 			$lists[$k][$ko]['non'] = 1;
					$o++;
					if($ko==(count($list)-1)){
						$lists[$k][$ko-$o+1]['cop'] = $o;
					}
				}else{
					if($ko==0){
						$lists[$k][0]['cop'] = $o;
					}else{
						$lists[$k][$ko-$o]['cop'] = $o;
					}
					$o=1;
				}
			}
	 	}
	 	$data['lists'] = $lists;
		$this->_render_content( $this->_load_view_file( 'detail' ), $data, false);
	}

	//房型匹配(运营管理)
	public function admin_match(){
		$data = $this->common_data;
		$this->label_action = '匹配统计';
		$this->_init_breadcrumb ( $this->label_action );
		$this->get_hotels_group();
		$third_type = 'ctrip';//后续增加这个第三方类型的判断
		$this->load->model('price/paritys_model');
		$hotel_nums = $this->paritys_model->getAllInterids();
		$lists = array();
		$room_nums = $this->paritys_model->getInterRooms($third_type);
		foreach ($this->hotelgroupname as $key => $value) {
			$list['inter_id'] = $key;
			$list['public_name'] = $value;
			$list['hotel_num'] = $hotel_nums['inter_count'][$key];
			$list['hotel_al_num'] = count($hotel_nums['hotel_ids'][$key]);
			$list['hotel_nal_num'] = $hotel_nums['inter_count'][$key]-count($hotel_nums['hotel_ids'][$key]);
			$list['room_num'] = $room_nums['room_nums'][$key];
			$list['room_al_num'] = $room_nums['al_nums'][$key];
			$list['room_nal_num'] = $room_nums['room_nums'][$key] - $room_nums['al_nums'][$key];
			$lists[] = $list;
		}
		$data['lists'] = $lists;
		$this->_render_content( $this->_load_view_file( 'admin_match' ), $data, false);
	}

	//房型匹配(酒店集团》房型匹配)
	public function hotels_match(){
		$data = $this->common_data;
		$this->label_action = '匹配概览';
		$this->_init_breadcrumb ( $this->label_action );
		$this->get_hotels_group();
		$third_type = 'ctrip';//后续增加这个第三方类型的判断
		$data['inter_id'] = $this->input->get('inter_id')?$this->input->get('inter_id'):$this->inter_id;
		if($data['inter_id'] == FULL_ACCESS){
			redirect(site_url('price/paritys/admin_match'));
		}
		$this->load->model('price/paritys_model');
		$hotel_nums = $this->paritys_model->getAllInterids($data['inter_id']);
		$room_nums = $this->paritys_model->getInterRooms($third_type,$data['inter_id']);
		$data['hotel_num'] = $hotel_nums['inter_count'][$data['inter_id']];
		$data['hotel_al_num'] = count($hotel_nums['hotel_ids'][$data['inter_id']]);
		$data['hotel_nal_num'] = $hotel_nums['inter_count'][$data['inter_id']]-count($hotel_nums['hotel_ids'][$data['inter_id']]);
		$data['room_num'] = $room_nums['room_nums'][$data['inter_id']];
		$data['room_al_num'] = $room_nums['al_nums'][$data['inter_id']];
		$data['room_nal_num'] = $room_nums['room_nums'][$data['inter_id']] - $room_nums['al_nums'][$data['inter_id']];
		$data['public_name'] = $this->hotelgroupname[$data['inter_id']];
		$data['parity_lists'] = $this->paritys_model->getParityInfo($data['inter_id'],$third_type);
		$this->_render_content( $this->_load_view_file( 'hotels_match' ), $data, false);
	}

	//酒店/房型匹配修改
	public function hotel_edit(){
		$data = $this->common_data;
		$this->label_action = '修改';
		$this->_init_breadcrumb ( $this->label_action );
		$this->get_hotels_group();
		$third_type = 'ctrip';//后续增加这个第三方类型的判断
		$data['inter_id'] = $this->input->get('inter_id')?$this->input->get('inter_id'):$this->inter_id;
		if($data['inter_id'] == FULL_ACCESS){
			redirect(site_url('price/paritys/admin_match'));
		}
		$data['hotel_id'] = $this->input->get('hotel_id')?$this->input->get('hotel_id'):0;
		$data['public_name'] = $this->hotelgroupname[$data['inter_id']];
		$this->load->model('price/paritys_model');
		$data['hotel_info'] = $this->paritys_model->getRoomParity($data['inter_id'],$data['hotel_id'],$third_type);
		$data['third_rooms'] = $this->paritys_model->getThirdRooms($data['inter_id'],$data['hotel_id'],$third_type);
		$this->_render_content( $this->_load_view_file( 'hotel_edit' ), $data, false);
	}

	// 异步修改酒店/房型匹配
	public function hotel_edit_ajax(){
		$edit_type = $this->input->get('t')?$this->input->get('t'):1;
		$inter_id = $this->input->get('iid')?addslashes($this->input->get('iid')):$this->inter_id;
		if($this->inter_id!=FULL_ACCESS&&$inter_id!=$this->inter_id){
			exit('无此inter_id的权限');
		}
		$third_type  = $this->input->get('third_type')?addslashes($this->input->get('third_type')):'ctrip';
		if($inter_id == FULL_ACCESS){
			echo 'inter_id参数不对';exit;
		}
		$hotel_id = $this->input->get('hid')?addslashes($this->input->get('hid')):0;
		$pid = $this->input->get('pid')?addslashes($this->input->get('pid')):0;//匹配表id
		$third_room_id = $this->input->get('tr_id')?addslashes($this->input->get('tr_id')):0;
		$third_id = $this->input->get('tid')?addslashes($this->input->get('tid')):'';
		$third_name = $this->input->get('thn')?addslashes($this->input->get('thn')):'';
		$athour = $this->input->get('ot')?addslashes($this->input->get('ot')):2;
		if($third_id==''){
			echo 'third_id参数不对';exit;
		}
		$this->load->model('price/paritys_model');
		if($edit_type==1){ // 酒店匹配修改
			$data = array(
				'ctrip_id' => $third_id,
				'ctrip_name' => $third_name,
				'grab_flag' => 1,
				'grab_time' => time(),
				);
			$status = $this->paritys_model->editHotelParity($inter_id,$hotel_id,$data);
		}else{ // 房型匹配修改
			$data = array(
				'third_id' => $third_id,
				'third_room_id' => $third_room_id,
				'athour' => $athour,
				'addtime'=> date('Y-m-d H:i:s'),
				);
			$status = $this->paritys_model->editRoomParity($inter_id,$hotel_id,$pid,$third_type,$data);
			if($status=='new'){
				$status = '数据已更新，请刷新页面再操作！';
			}
		}
		echo $status;
	}

  	//智能调价配置
	public function smart_price(){
		$third_type = 'ctrip';//后续增加这个第三方类型的判断
		$data = $this->common_data;
		$this->label_action = '智能调价配置';
		$this->_init_breadcrumb ( $this->label_action );
		$get = $this->input->get();
		$data['err'] = !empty($get['err'])?$get['err']:'';
		$this->load->model('price/paritys_model');
		$entity_ids = $this->session->get_admin_hotels();
		$hotels = $this->paritys_model->getAllHotels($entity_ids);
		$data['hotels'] = array();
		foreach ($hotels as $k => $hotel) {
			$data['hotels'][$hotel['hotel_id']] = $hotel;
		}
		$data['hotel_name'] = !empty($get['hotel_id'])?$data['hotels'][$get['hotel_id']]['name']:'全部酒店';
		//获取当前酒店最新更新时间
		$data['hotel_id'] = !empty($get['hotel_id'])?$get['hotel_id']:0;
		$up_result = $this->paritys_model->getUptime($data['hotel_id'] ,$this->inter_id);
		$data['uptime'] = $up_result['addtime'];
		//获取房型信息
		$data['rooms'] = array();
		if($data['hotel_id']>0){
			$data['rooms'] = $this->paritys_model->getRooms($data['hotel_id'],$third_type);
		}
		// var_dump($data['rooms']);exit;
		$data['count_room'] = count($data['rooms']);
		// 获取配置信息
		$configs = $this->paritys_model->getSmartConfig($data['hotel_id']);
		// var_dump($configs);exit;
		$data['configs'] = $configs['configs'];
		$data['configs_list'] = $configs['configs_list'];
		$data['rooms_list'] = $configs['rooms_list'];
		$data['price_codes_check'] = explode(',',$configs['configs']['price_codes']);
		foreach($data['rooms'] as $kd=>$vd){
			if(!empty($vd['room'])){
				foreach($vd['room'] as $d=>$r){
					foreach($data['rooms_list'] as $kr=>$vr){
						if($r['hotel_id']==$vr['hotel_id']&&$r['room_id']==$vr['room_id']&&$r['price_code']==$vr['price_code']&&$r['price_code']==$vr['price_code']){
							$data['rooms'][$kd]['room'][$d]['conf_type'] = $vr['conf_type'];
							$data['rooms'][$kd]['room'][$d]['range_type'] = $vr['range_type'];
							$data['rooms'][$kd]['room'][$d]['compare_type'] = $vr['compare_type'];
							$data['rooms'][$kd]['room'][$d]['conf_type'] = $vr['conf_type'];
							$data['rooms'][$kd]['room'][$d]['price_section'] = $vr['price_section'];
							$data['rooms'][$kd]['room'][$d]['low_section'] = $vr['low_section'];
							$data['rooms'][$kd]['room'][$d]['conf_type'] = $vr['conf_type'];
							$data['rooms'][$kd]['room'][$d]['conf_type'] = $vr['conf_type'];
						}
					}
				}
			}
		}
		//查出全部价格代码类型
		$this->load->model('hotel/price_code_model');
		if($data['hotel_id']>0){
			$data['price_codes'] = $this->price_code_model->get_hotel_price_codes($this->inter_id,$data['hotel_id']);
		}else{
			$data['price_codes'] = $this->price_code_model->get_hotel_price_codes($this->inter_id);
		}
		// var_dump($data['price_codes']);exit;
		$this->_render_content( $this->_load_view_file( 'smart_price' ), $data, false);
	}

	// 智能调价配置保存
	public function smart_price_post(){
		$post = $this->input->post();
		$confs = array();
		$time = date('Y-m-d H:i:s');
		$confs['inter_id'] = $this->inter_id;
		$confs['hotel_id'] = $post['hotel_id']?$post['hotel_id']:0;
		$confs['conf_type'] = $post['conf_type'];
		$confs['exec_date'] = json_encode($post['exec_date']);
		$confs['adjust_type'] = !empty($post['adjust_type'])?$post['adjust_type']:1;
		$confs['effect_time'] = !empty($post['effect_time'])?$post['effect_time']:30;
		$confs['price_codes'] = !empty($post['price_codes'])?implode(',',$post['price_codes']):'';
		$confs['addtime'] = $time;
		$confs['uptime'] = $time;
		$confs_list = array();
		$confs_list['inter_id'] = $confs['inter_id'];
		$confs_list['hotel_id'] = $confs['hotel_id'];
		$confs_list['addtime'] = $time;
		$confs_list['uptime'] = $time;
		$this->load->model('price/paritys_model');
		if($post['conf_type']==1){
			$confs_list['room_id'] = 0;
			$confs_list['price_code'] = 0;
			$confs_list['range_type'] = $post['range_type'];
			$confs_list['conf_type'] = $post['conf_type'];
			if($post['range_type']==1){
				$confs_list['price_section'] = json_encode($post['wx_fix']);
			}elseif($post['range_type']==2){
				$confs_list['price_section'] = json_encode($post['wx_per']);
			}
			$confs_list['compare_type'] = $post['compare_type'];
			if($post['compare_type']==1){
				$confs_list['low_section'] = $post['ch_fix'];
			}elseif ($post['compare_type']==2) {
				$confs_list['low_section'] = $post['ch_per'];
			}
			$res = $this->paritys_model->editConfig($confs,$confs_list);
		}elseif($post['conf_type']==2){
			$confs_list['conf_type'] = $post['conf_type'];
			$res = $this->paritys_model->editConfig($confs);
		}
		// var_dump($res);exit;
		$hid = '';
		if($post['hotel_id']>0){
			$hid = '&hotel_id='.$post['hotel_id'];
		}
		if($res){
			if($post['hotel_id']>0){
				$hid = '?hotel_id='.$post['hotel_id'];
			}
			redirect(site_url('price/paritys/smart_price'.$hid));
		}
		redirect(site_url('price/paritys/smart_price?err=1'.$hid));
	}

	public function get_operate_logs_ajax(){
		$data = $this->common_data;
		$this->load->model('price/paritys_model');
		//分页
		$this->load->library('pagination');
		// $config ['suffix'] = '?p='.$p;
	 	$config['base_url'] = base_url('index.php/price/paritys/get_operate_logs_ajax');
	 	$config ['first_url'] = base_url ( "index.php/price/paritys/get_operate_logs_ajax" );
	 	$config ['uri_segment'] = 4;
	 	$data['count'] = $this->paritys_model->getSmartLogs($this->inter_id,true);
	 	$config['total_rows'] = $data['count'];
	 	$config['per_page'] = 3;
	 	$config ['first_link'] = '首页';
  		$config ['last_link'] = '末页';
  		$config ['next_link'] = '下一页';
  		$config ['prev_link'] = '上一页';
  		$config['cur_tag_open'] = '<ib class="btn2" style="border:0px;cursor:default;">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</ib>';
		$config['num_tag_open'] = '<ib class="btn2" style="margin-left:5px;">'; // 数字链接样式
		$config['num_tag_close'] = '</ib>';
		$config['next_tag_open'] = '<ib class="btn" style="margin-left:5px;">';
		$config['next_tag_close'] = '</ib>';
		$config['prev_tag_open'] = '<ib class="btn" style="margin-left:5px;">';
		$config['prev_tag_close'] = '</ib>'; 
		$config['attributes'] = array('class' => 'ajax_fpage');
  		$config['use_page_numbers'] = true; 
	 	$p = $this->uri->segment(4)?max(1,min(ceil($config['total_rows']/$config['per_page']),$this->uri->segment(4))):1;
	 	$data['pages'] = ceil($config['total_rows']/$config['per_page']);
	 	$offset =  ($p-1)*$config['per_page'];
	 	$config ['cur_page'] = $offset;
		$this->pagination->initialize($config);
	 	$data['page'] = $this->pagination->create_links();
	 	//获取操作日志
		$data['logs'] = $this->paritys_model->getSmartLogs($this->inter_id,false,$offset,$config['per_page']);
		$this->_load_content( $this->_load_view_file( 'smart_price_logs_ajax' ), $data, false);
	}

	public function save_room_config_ajax(){
		$data = $this->common_data;
		$this->load->model('price/paritys_model');
		$post = $this->input->post();
		$lists = array();
		$time = date('Y-m-d H:i:s');
		if(!empty($post['hid'])){
			$hotel_id = $post['hid'];
		}else{
			exit('hotel_id error');
		}
		if(!empty($post['rid'])){
			$room_id = $post['rid'];
		}else{
			exit('room_id error');
		}
		$tmp = array();
		$pcode = '';
		if(!empty($post['data'])){
			foreach ($post['data'] as $k => $v) {
				if($v['name']=='pid'.$v['value']){
					$pcode = $v['value'];
				}
				if(strpos($v['name'],$pcode)){
					$tmp[$pcode][] = $v;
				}	
			}
		}
				// var_dump($tmp);exit;
		foreach ($tmp as $key => $val) {
			$list = array(
				'inter_id'=>$this->inter_id,
				'hotel_id'=>$hotel_id,
				'room_id'=>$room_id,
				'price_code'=>$key,
				'conf_type'=>2,
				);
			$list['range_type'] = '';
			$list['compare_type'] = '';
			$wx = array();
			$ch = '';
			foreach ($val as $kv => $vv) {
				if($vv['name']=='range_type'.$room_id.$key){
					$list['range_type'] = $vv['value'];
				}
				if($list['range_type']==1){
					if($vv['name']=='wx_fix'.$room_id.$key.'[min]'){
						$wx['min'] = $vv['value'];
					}elseif($vv['name']=='wx_fix'.$room_id.$key.'[max]'){
						$wx['max'] = $vv['value'];
					}
				}elseif($list['range_type']==2){
					if($vv['name']=='wx_per'.$room_id.$key.'[min]'){
						$wx['min'] = $vv['value'];
					}elseif($vv['name']=='wx_per'.$room_id.$key.'[max]'){
						$wx['max'] = $vv['value'];
					}
				}
				if($vv['name']=='compare_type'.$room_id.$key){
					$list['compare_type'] = $vv['value'];
				}
				if($list['compare_type']==1){
					if($vv['name']=='ch_fix'.$room_id.$key){
						$ch = $vv['value'];
					}
				}elseif($list['compare_type']==2){
					if($vv['name']=='ch_per'.$room_id.$key){
						$ch = $vv['value'];
					}
				}				
			}
			if(empty($list['range_type'])||empty($list['compare_type'])||empty($ch)||empty($wx['min'])||empty($wx['max'])){
				// exit('null');
				continue;
			}			
			$list['price_section'] = json_encode($wx);
			$list['low_section'] = $ch;
			$list['addtime'] = $time;
			$list['uptime'] = $time;
			$lists[] = $list;
		}
		// var_dump($lists);exit;
		$res = $this->paritys_model->editRoomConfig($lists);
		if($res===true){
			exit('1');
		}elseif ($res=='null') {
			exit('null');
		}
		exit('0');
	}

	// 调用生成比价接口
	public function request_price(){
		// 获取当前酒店当天剩余可生成比价次数
		$this->load->model('price/paritys_model');
		$num = $this->paritys_model->getParityNum();
		if($num<1){
			exit('今天生成比价的次数已用完');
		}
		$key = 'EqX91CUha4PNjVYM';
		$sign = md5($key.$this->inter_id);
		$data = array('inter_id'=>$this->inter_id,'n'=>10-$num+1,'sign'=>$sign);
		$this->load->library('MYLOG');
		MYLOG::w('传递到生成比价接口的参数:'.json_encode($data),'paritys');
		$url = 'http://tprice.iwide.cn/index.php/creates/http_receive'; //测试
		// $url = 'http://price.iwide.cn/index.php/creates/http_receive'; //生产
		$this->load->helper('common');
		$res = doCurlGetRequest($url,$data,2);
		$this->paritys_model->redisGo(md5($this->inter_id.$num),'set','doing');
		MYLOG::w('请求生成比价接口返回结果：'.json_encode($res),'paritys');
		exit("1");
	}

	//生成调价结果
	public function result_price(){
		$third_type = 'ctrip';//后续增加这个第三方类型的判断
		$data = $this->common_data;
		$this->label_action = '智能调价详细';
		$this->_init_breadcrumb ( $this->label_action );
		$inter_id = !empty($this->input->get('inter_id'))?$this->input->get('inter_id'):$this->inter_id;
		$data['inter_id'] = $inter_id;
		$hotel_id = $this->input->get('hotel_id');
		$data['hotel_id'] = $hotel_id;
		$this->load->model('price/paritys_model');
		//记录操作日志
		$info = array(
			'inter_id'=>$inter_id,
			'hotel_id'=>$hotel_id,
			);
		$up_result = $this->paritys_model->getUptime($hotel_id ,$inter_id);
		if(!empty($up_result)){
			$data['uptime'] = $up_result['addtime'];
			$data['date'] = $up_result['adddate'];
			$data['batch'] = $up_result['batch'];
		}
		//获取智能调价配置
		$data['configs'] = $this->paritys_model->getSmartConfig($hotel_id ,$inter_id);
		$paritys = $this->paritys_model->getHotelParity($inter_id,$hotel_id,'ctrip');
		$data['ctrip_url'] = 'http://hotels.ctrip.com/hotel/'.$paritys['ctrip_id'].'.html';
		$hotels = $this->paritys_model->getAllHotels($hotel_id);
		$data['hotel_name'] = !empty(current($hotels)['name'])?current($hotels)['name']:'';
		// var_dump($data['configs']);exit;
		//获取比价数据
		$rooms = $this->paritys_model->getRooms($hotel_id,'ctrip',$inter_id);
		//计算调价结果
		$data['rooms'] = $this->paritys_model->getResultPrice($rooms,$data['configs']);
		$data['room_num'] = count($data['rooms']);
		$data['price_code_num'] = 0;
		foreach($data['rooms'] as $room){
			$data['price_code_num'] +=  count($room);
		}
		$info = $data['rooms'];
		$info['batch'] = $data['batch'];
		// 查看成功
		if(!empty($data['rooms'])&&!empty($up_result)){
			$admin = 'adminid_'.$this->session->get_admin_id ();
			$this->paritys_model->saveAdjustLog($inter_id,$hotel_id,$admin,$data['date'],$data['batch'],'see',1,$info);
		}
		// var_dump($data);exit;
		//判断此次调价是否已关闭
		$adjust = $this->paritys_model->getAdjustInfo($inter_id,$hotel_id,$data['date'],$data['batch'],'cancel');
		$data['h'] = 'new';
		if(!empty($adjust)&&$adjust['operate_type']=='cancel'&&$adjust['operate_result']==1){
			$data['h'] = 'close';
		}
		$adjust = $this->paritys_model->getAdjustInfo($inter_id,$hotel_id,$data['date'],$data['batch'],'confirm');
		if(!empty($adjust)&&$adjust['operate_type']=='confirm'&&$adjust['operate_result']==1){
			$data['h'] = 'close';
		}
		if(!empty($data['configs']['configs'])){
			$oktime = strtotime($data['uptime'])+$data['configs']['configs']['effect_time']*60;
			if($oktime<time()){
				$data['h'] = 'close';
			}
		}
		$this->_render_content( $this->_load_view_file('result_price'), $data, false);
	}

	//确认调价
	public function confirm(){
		// var_dump($this->input->get());exit;
		$optype = $this->input->get('h');
		$opresult = 0;
		$date = $this->input->get('date');
		$batch = $this->input->get('batch');
		$inter_id = !empty($this->input->get('id'))?$this->input->get('id'):$this->inter_id;
		$hotel_id = $this->input->get('hotel_id');
		$this->load->library('MYLOG');
		$this->load->model('price/paritys_model');
		$admin = 'adminid_'.$this->session->get_admin_id ();
		//记录操作日志 暂不处理
		if($optype=='cancel'){
			$info = array();
			$opresult = 1;
			$res = $this->paritys_model->saveAdjustLog($inter_id,$hotel_id,$admin,$date,$batch,$optype,$opresult,$info);
			if($res){
				exit('ok');
			}elseif($res=='al'){
				exit('al');
			}else{
				exit('关闭调价失败,请稍后再试');
			}
		}
		//确认调价
		if($optype=='ok'){
			//判断此次调价是否已关闭
			$adjust = $this->paritys_model->getAdjustInfo($inter_id,$hotel_id,$date,$batch,'cancel');
			if(!empty($adjust)&&$adjust['operate_type']=='cancel'&&$adjust['operate_result']==1){
				exit('此次调价已关闭');
			}
			$adjust = $this->paritys_model->getAdjustInfo($inter_id,$hotel_id,$date,$batch,'confirm');
			if(!empty($adjust)&&$adjust['operate_type']=='confirm'&&$adjust['operate_result']==1){
				exit('此次调价已关闭');
			}
			$info = array();
			$up_result = $this->paritys_model->getUptime($hotel_id ,$inter_id);
			$data['uptime'] = $up_result['addtime'];
			$nowbatch = $up_result['batch'];
			//获取智能调价配置
			$data['configs'] = $this->paritys_model->getSmartConfig($hotel_id ,$inter_id);
			//校验执行日期和有效时长
			if(!empty($data['configs']['configs'])){
				//有效时长
				$oktime = strtotime($data['uptime'])+$data['configs']['configs']['effect_time']*60;
				if($oktime<time()||$nowbatch>$batch){
					exit('此次调价已过期');
				}
				//校验执行日期
				$exec_date = $data['configs']['configs']['exec_date'];
				if(!empty($exec_date)&&(time()<strtotime($exec_date[0])||time()>strtotime($exec_date[1]))){
					exit('此次调价已过期');
				}
			}
			//修改价格
			//获取比价数据
			$rooms = $this->paritys_model->getRooms($hotel_id,'ctrip',$inter_id);
			//计算调价结果
			$result_prices = $this->paritys_model->getResultPrice($rooms,$data['configs']);
			//调价
			$day = date('Ymd');
			$res = $this->paritys_model->save_room_price($inter_id, $hotel_id,$result_prices, $day,$batch,$admin);
			$opresult = !$res?0:1;
			if($opresult==1){
				$this->load->model('plugins/template_msg_model');
				$condits = array(
					// 'adddate'=>date('Y-m-d'),
					'hotel_ids'=>array($hotel_id),
					'batch'=>$batch,
				);
				$hotels = $this->paritys_model->getDownRate($inter_id,'ctrip',$condits);
				// var_dump($hotels);exit;
				$this->load->model('hotel/hotel_notify_model');
				foreach ($hotels as $k => $v) {
					//查出符合接收模板消息的人员信息
					$hotel_ids = array('hotel_ids'=>array(0,$v['hotel_id']));
					$regs = $this->hotel_notify_model->get_hotels_reg($inter_id,$hotel_ids,true);
					if(!empty($regs)){
						foreach($regs as $r=>$reg){
							if($this->hotel_notify_model->check_reg($reg,'change')){
								// 发送模板消息 智能调价通知
								$info = array(
									'inter_id'=>$inter_id,
									'hotel_id'=>$v['hotel_id'],
									'batch'=>$batch,
									'openid'=>$reg['openid'],
									'hotel'=>$v['hotel_name'],
									'warn_type'=> 'change',
									'remark_type'=> 'change',
									'warndate'=> date('Y-m-d H:i:s'),
								);
								$result = $this->template_msg_model->send_smart_price_msg ( $inter_id,$info,'smart_price_complete_notice');
								if($result['s']!=1||$result['errmsg']!='ok'){
									MYLOG::w('智能调价完成模板消息发送失败:'.json_encode($info).'|'.json_encode($result),'smarts');
								}
							}
						}
					}
				}
				exit('ok');
			}else{
				MYLOG::w('调价失败：'.$inter_id.'-'.$hotel_id.'-'.$day.'-'.json_encode($result_prices).'-'.json_encode($res),'smarts');
			}
		}
	}

	//计算比价结果生成数
	public function request_num(){
		$inter_id = !empty($this->input->get('inter_id'))?$this->input->get('inter_id'):$this->inter_id;
		if($inter_id == FULL_ACCESS){
			exit('inter_id参数不对');
		}
		$num = !empty($this->input->get('num'))?$this->input->get('num'):0;
		$this->load->model('price/paritys_model');
		$ky = md5($inter_id.$num);
		$stat = $this->paritys_model->redisGo($ky);
		if($stat=='complete'){
			$m['stat'] = 'complete';
		}elseif($stat=='doing'){
			$m['stat'] = 'doing';
		}
		//查询本次比价结果是否已生成
		$maxbatch = $this->paritys_model->getMaxBatch($inter_id);
		if($maxbatch==(10-$num+1)){
			$this->paritys_model->redisGo($ky,'set','complete',600);
			$m['stat'] = 'complete';
			echo json_encode($m);exit;
		}
		//请求比价服务器获取已完成的数量
		$key = 'EqX91CUha4PNjVYM';
		$sign = md5($key.$this->inter_id);
		// 获取当前酒店当天剩余可生成比价次数
		// $num = $this->paritys_model->getParityNum();
		$data = array('inter_id'=>$this->inter_id,'n'=>10-$num+1,'sign'=>$sign);
		$url = 'http://tprice.iwide.cn/index.php/creates/http_alnum'; //测试
		// $url = 'http://price.iwide.cn/index.php/creates/http_alnum'; //生产
		$this->load->helper('common');
		$res = doCurlGetRequest($url,$data,10);
		if(!is_numeric($res)){
			MYLOG::w('计算比价结果生成数结果返回异常：'.json_encode($res),'paritys');
			exit('请求异常');
		}
		$m['n'] = $res;  
		echo json_encode($m);
	}

	//自动开通比价
	public function auto_parity(){
		if($this->inter_id==FULL_ACCESS){
			redirect(site_url('price/paritys/admin_index'));
		}
		$this->load->library('MYLOG');
		$this->load->helper('common');
		//同步公众号信息和酒店信息
		$this->load->model('price/paritys_model');
		$res = $this->paritys_model->syncHotelInfo();
		if($res==='exist'){
			//已开通
			exit('exist');
		}
		if(!$res){
			MYLOG::w('公众号和酒店信息同步失败:'.json_encode($res),'paritys');
			exit('fail');
		}
		//调用酒店匹配接口，完成新的酒店匹配
		$key = 'EqX91CUha4PNjVYM';
		$sign = md5($key.$this->inter_id);
		$data = array('inter_id'=>$this->inter_id,'sign'=>$sign);
		MYLOG::w('传递到酒店匹配接口的参数auto:'.json_encode($data),'paritys');
		$url = 'http://tprice.iwide.cn/index.php/hotel/hotel_match/http_catch_hotel'; //测试
		// $url = 'http://price.iwide.cn/index.php/hotel/hotel_match/http_catch_hotel'; //生产
		$res = doCurlGetRequest($url,$data,27);
		MYLOG::w('酒店匹配结果'.$this->inter_id.':'.$res,'paritys');
		// var_dump($res);exit;
		if($res){
			//调用房型比价生成接口，生成比价信息
			$num = 10;
			$data = array('inter_id'=>$this->inter_id,'n'=>10-$num+1,'sign'=>$sign);
			MYLOG::w('传递到生成比价接口的参数auto:'.json_encode($data),'paritys');
			$url = 'http://tprice.iwide.cn/index.php/creates/http_receive'; //测试
			// $url = 'http://price.iwide.cn/index.php/creates/http_receive'; //生产
			$res = doCurlGetRequest($url,$data,1);
			$this->paritys_model->redisGo(md5($this->inter_id.$num),'set','doing');
			// var_dump($res);exit;
			exit('finish');
		}
	}
}