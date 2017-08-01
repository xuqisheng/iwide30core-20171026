<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Hotel_ext_model extends MY_Model {
	public function get_resource_name() {
		return '酒店信息';
	}
	public static function model($className = __CLASS__) {
		return parent::model ( $className );
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function table_name() {
		return 'hotels';
	}
	public function table_primary_key() {
		return 'hotel_id';
	}
	public function attribute_labels() {
		return array (
				'hotel_id' => '酒店ID',
				'inter_id' => '公众号ID',
				'name' => '*酒店名称',
				'address' => '*地址',
				'latitude' => '*纬度',
				'longitude' => '*经度',
				'tel' => '电话',
				'intro' => '酒店介绍',
				'short_intro' => '简短介绍',
				'intro_img' => '酒店介绍图',
				'services' => '提供服务',
				'characters' => '酒店特色',
				'email' => '邮箱',
				'fax' => '传真',
				'star' => '星级',
				'country' => '*国家',
				'province' => '*省份',
				'web' => '网址',
				'status' => '状态',
				'city' => '*城市',
                'area' => '行政区',
				'sort' => '排序',
				'book_policy' => '预定说明', 
				'arounds' => '酒店周边',
				'invoice'=>'发票服务',
				'retreat_time'=>'退房时间',
		        'multiple_inner'=>'支持多入住人',
		);
	}
	
	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields() {
		return array (
				'hotel_id',
				'name',
				'status',
				'tel',
				'city',
				'province',
				'country',
				'address',
				'inter_id'
		);
	}
	
	/**
	 * 在EasyUI grid中的 date-option 定义，包括宽度，是否排序等等
	 *   type: grid中的表头类型定义 
	 *   form_type: form中的元素类型定义
	 *   form_ui: form中的属性补充定义，如加disabled 在< input “disabled” / > 使元素禁用
	 *   form_tips: form中的label信息提示
	 *   form_hide: form中自动化输出中剔除
	 *   form_default: form中的默认值，请用字符类型，不要用数字
	 *   select: form中的类型为 combobox时，定义其下来列表
	 */
	public function attribute_ui() {
		/* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
		// type: numberbox数字框|combobox下拉框|text不写时默认|datebox
		$base_util = EA_base::inst ();
		$modules = config_item ( 'admin_panels' ) ? config_item ( 'admin_panels' ) : array ();
		// $parents= $this->get_cat_tree_option();
		
		$parents ['0'] = '一级分类';
		
		$status = array (
				'1' => '可用',
				'2' => '不可用' 
		);
		$star = array (
				'0'=>'无',
				'1' => '一星级',
				'2' => '二星级', 
				'3' => '三星级', 
				'4' => '四星级', 
				'5' => '五星级',
		);
		$invoice = array (
				'1' => '无',
				'2' => '有'
		);
		/** 获取本管理员的酒店权限  */
		$this->_init_admin_hotels ();
		$publics = $hotels = array ();
		$filter = $filterH = NULL;
		
		if ($this->_admin_inter_id == FULL_ACCESS)
			$filter = array ();
		else if ($this->_admin_inter_id)
			$filter = array (
					'inter_id' => $this->_admin_inter_id 
			);
		if (is_array ( $filter )) {
			$this->load->model ( 'wx/publics_model' );
			$publics = $this->publics_model->get_public_hash ( $filter );
			$publics = $this->publics_model->array_to_hash ( $publics, 'name', 'inter_id' );
			// $publics= $publics+ array(FULL_ACCESS=>'-所有公众号-');
		}
		
		if ($this->_admin_hotels == FULL_ACCESS)
			$filterH = array ();
		else if ($this->_admin_hotels)
			$filterH = array (
					'hotel_id' => $this->_admin_hotels 
			);
		else
			$filterH = array ();
		
		if ($publics && is_array ( $filterH )) {
			$this->load->model ( 'hotel/hotel_model' );
			$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
			$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
			$hotels = $hotels + array (
					'0' => '-不限定-' 
			);
		}
		/** 获取本管理员的酒店权限  */
		
		return array (
				'name' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'text' 
				) // textarea|text|combobox
,
				'email' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'text' 
				) // textarea|text|combobox
,
				'tel' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'text' 
				) // textarea|text|combobox
,
				'fax' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'text' 
				) // textarea|text|combobox
,
				'sort' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'text' 
				) // textarea|text|combobox
,
				'latitude' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'text' 
				) // textarea|text|combobox
,
				'longitude' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'text' 
				) // textarea|text|combobox
,
				'country' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'text' 
				) // textarea|text|combobox
,
				'province' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'text' 
				) // textarea|text|combobox
,
				'city' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'text' 
				) // textarea|text|combobox
,
				'address' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'text' 
				) // textarea|text|combobox
,
				'star' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'combobox',
						'select' => $star 
				),
				'web' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'text' 
				) // textarea|text|combobox
,
				'book_policy' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'textarea' 
				) // textarea|text|combobox
,
				'intro' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'textarea' 
				) // textarea|text|combobox
,
				'intro_img' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'text' 
				) // textarea|text|combobox
,				
				'characters' => array(
					'grid_ui' => '',
					'grid_width' => '10%',
					'type' => 'text'
				),
				'hotel_id' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'text' 
				),
				'status' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'combobox',
						'select' => $status 
				),
				'inter_id' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'combobox',
						'select' => $publics 
				),
				'services' => array(
					'form_hide'=>true,
				),
				'email' => array(
					'form_hide'=>true,
				),
				'fax' => array(
					'form_hide'=>true,
				),
				'web' => array(
					'form_hide'=>true,
				),
				'arounds' => array(
					'form_hide'=>true
				),
				'invoice' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'type' => 'combobox',
						'select' => $invoice 
				),
				'retreat_time' => array(
					'form_hide'=>true,
				),
		);
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field() {
		return array (
				'field' => 'hotel_id',
				'sort' => 'desc' 
		);
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */
	public function get_cat_tree_option() {
		$array = '';
		// $array['_'. $k]= '+'. $v['label'];
		$tmp = $this->get_data_filter ( array (
				'parent_id' => '0' 
		) );
		// print_r($tmp);die;
		foreach ( $tmp as $sv ) {
			$array [$sv ['cat_id']] = '+' . $sv ['cat_name'];
			$tmp2 = $this->get_data_filter ( array (
					'parent_id' => $sv ['cat_id'] 
			) );
			// print_r($array);die;
			foreach ( $tmp2 as $ssv ) {
				$array [$ssv ['cat_id']] = '+---' . $ssv ['cat_name'];
			}
		}
		// print_r($array);die;
		return $array;
	}
	function get_focus_s(){
		$db_read = $this->load->database('iwide_r1',true);
		$this->_init_admin_hotels ();
		$publics = $hotels = array ();
		$filter = $filterH = NULL;
		$inter_id = $this->_admin_inter_id;
		if ($inter_id == FULL_ACCESS)
			$filter = array ();
		else if ($inter_id)
			$filter = array ('inter_id' => $inter_id );
		if (is_array ( $filter )) {
			$this->load->model ( 'wx/publics_model' );
			$publics = $this->publics_model->get_public_hash ( $filter );
			$publics = $this->publics_model->array_to_hash ( $publics, 'name', 'inter_id' );
			// $publics= $publics+ array(FULL_ACCESS=>'-所有公众号-');
		}
		
		if ($this->_admin_hotels == FULL_ACCESS)
			$filterH = array ();
		else if ($this->_admin_hotels)
			$filterH = array ('hotel_id' => $this->_admin_hotels );
		else
			$filterH = array ();
		$filterH ['status'] = array(1);
		if(!isset($filterH['inter_id']))$filterH['inter_id'] = $this->session->get_admin_inter_id();
		if ($publics && is_array ( $filterH )) {
			$this->load->model ( 'hotel/hotel_model' );
			$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
			$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		}
		$hotel_id = 0;
		$keys = array_keys( $hotels);
		if($this->input->get('hid')){
			if(key_exists($this->input->get('hid'), $hotels))
			$hotel_id = $this->input->get('hid');
		}else{
			$hotel_id = empty($keys[0])?0:$keys[0];
		}
		if ($inter_id == FULL_ACCESS) $inter_id = 'a429262687';
		$db_read->where(array('inter_id'=>$inter_id,'hotel_id'=>$hotel_id,'type'=>'hotel_lightbox','status'=>1));
		$focus_query = $db_read->get('hotel_images')->result();
		return array('hotels'=>$hotels,'focus'=>$focus_query,'hotel_id'=>$hotel_id,'inter_id'=>$inter_id);
	}
	function save_focus(){
		$datas['image_url']  = trim($this->input->post('imgurl'));
		$datas['info']       = trim($this->input->post('describe'));
		$datas['sort']       = $this->input->post('sort');
		//if(empty($this->input->post('key'))){
		$post_key= $this->input->post('key'); //php5.3下报错
		if(empty($post_key)){
			$datas['inter_id']   = $this->input->post('inter_id');
			$datas['hotel_id']   = $this->input->post('hotel_id');
			$datas['status']     = 1;
			$datas['type']       = 'hotel_lightbox';
			return $this->db->insert('hotel_images',$datas) > 0;
		}else{
			$this->db->where(array('inter_id'=>$this->input->post('inter_id'),'hotel_id'=>$this->input->post('hotel_id'),'id'=>$this->input->post('key')));
			return $this->db->update('hotel_images',$datas) > 0;
		}
	}
	function del_focus(){
		$this->db->where(array('hotel_id'=>$this->input->get('hotel_id'),'inter_id'=>$this->input->get('inter_id'),'id'=>$this->input->get('key')));
		return $this->db->delete('hotel_images') > 0;
	}

	function get_h_img_by_id($id){
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->where(array('id'=>$id));
		return $db_read->get('hotel_images')->row_array();
	}

    function update_focus(){
        $this->db->where(array('hotel_id'=>$this->input->get('hotel_id'),'inter_id'=>$this->input->get('inter_id'),'id'=>$this->input->get('key')));
        $data = array (
            'info' =>$this->input->get('info'),
            'sort' => $this->input->get('sort')
        );
        $imgurl = trim($this->input->get('imgurl'));
    	if(!empty($imgurl)){
    		$data['image_url'] = $imgurl;
    	}
        $this->db->update ( 'hotel_images', $data );
        return true;
    }

	function save_services($hotel_id = NULL){
		$db_read = $this->load->database('iwide_r1',true);
		$inter_id=$this->session->get_admin_inter_id();
		if($this->input->post('hotel_id'))$hotel_id=$this->input->post('hotel_id');
		$ser_kv = array('&#xe7;'=> '停车','&#xed;'=>'接机服务','&#xea'=>'餐厅','&#xe3;'=>'上网','&#xe5;'=>'叫醒服务','&#xe9;'=>'行李寄存','&#xe4;'=>'吹风机','&#xe8;'=>'Wifi','&#xeb;'=>'热水');
		$services = $this->input->post('ser[]');
		$db_read->where(array('inter_id'=>$inter_id,'hotel_id'=>$hotel_id,'room_id'=>0,'type'=>'hotel_service'));
		$cur_service=$db_read->get('hotel_images')->result_array();
		$cur_service=array_column($cur_service, NULL,'image_url');
// 		$this->db->delete('hotel_images');
		foreach ($services as $item) {
			if (isset($cur_service[$item])){
				if ($cur_service[$item]['status']!=1){
					$this->db->where(array('id'=>$cur_service[$item]['id'],'inter_id'=>$inter_id));
					$this->db->update('hotel_images',array('status'=>1));
				}
				unset($cur_service[$item]);
			}else {
				$sql = 'INSERT INTO '.$this->db->dbprefix('hotel_images')." (inter_id,hotel_id,room_id,sort,type,info,image_url,status) select ?,?,?,0,'hotel_service',info,image_url,1 FROM ".$this->db->dbprefix('hotel_images')." WHERE inter_id='defaultimg' AND type='hotel_service' AND image_url=?";
				$this->db->query($sql,array($inter_id,$hotel_id,0,htmlspecialchars_decode($item)));
			}
		}
		if (!empty($cur_service)){
			$this->db->where_in('id',array_column($cur_service, 'id'));
			$this->db->where(array('inter_id'=>$inter_id,'hotel_id'=>$hotel_id,'room_id'=>0,'type'=>'hotel_service'));
			$this->db->update('hotel_images',array('status'=>2));
		}
	}
	public function load($id)
	{
		$pk= $this->table_primary_key();
		$values= $this->find(array($pk=> $id,'inter_id'=>$this->session->get_admin_inter_id()));
		if($values){
			$table= $this->table_name();
			$fields= $this->_db()->list_fields($table);
			$this->_attribute= array_values($fields);
				
			foreach ($fields as $v) {
				$this->_data[$v]= $values[$v];
			}
			//确保 $this->_data_org 的值是完整的
			$this->_data_org = $this->_data;
			return $this;
				
		} else {
			return NULL;
		}
	}
	public function m_save($data=NULL,$update = TRUE)
	{
		$pk= $this->table_primary_key();
		$table= $this->table_name();
		$fields= $this->_db()->list_fields($table);
		//手工生成主键字段，update=FALSE -- 2015-12-07 ounianfeng
		// 	    if( isset($this->_data[$pk]) && $this->_data[$pk]>0 ) {
		if(!isset($this->_data['inter_id']))$this->_data['inter_id'] = $this->session->get_admin_inter_id();
		if( isset($this->_data[$pk]) && !empty($this->_data[$pk]) && $update ) {
			if($data){
				foreach ($data as $k=>$v){
					if(in_array($k,$fields)) $this->_data[$k]= $v;
				}
			}
			$where= array( $pk=> $this->_data[$pk] ,'inter_id'=>$this->session->get_admin_inter_id());
	        $this->_db()->where($where);
			$result= $this->_db()->update($table, $this->_data);
			return $result;
	
		} else {
			if($data){
				foreach ($data as $k=>$v){
					if(in_array($k,$fields)) $this->_data[$k]= $v;
				}
			}
			//手工生成主键字段时，不释放主键的变量 -- 2015-12-07 ounianfeng --
			if($update)unset($this->_data[$pk]);
			$result= $this->_db()->insert($table, $this->_data);
			//成功插入后返回last insert id
			if($result==TRUE){
				return $this->_db()->insert_id();
			} else {
				return $result;
			}
		}
	}

	/**
	 * 指定时间房间预订数据
	 * @param unknown $inter_id
	 * @param string $btime 下单开始时间
	 * @param string $etime 下单结束时间
	 * @param string $limit
	 * @param number $offset
	 */
	public function get_booking_summary($inter_id,$btime = '',$etime = '',$limit=NULL,$offset=0){
		$sql = "SELECT o.hotel_id,COUNT(i.id) total_count,SUM(i.istatus=4 OR i.istatus=5) cancel_count,SUM(o.paytype='weixin' OR o.paytype='balance') prepay_count,SUM(i.istatus=2) check_in_count,SUM(IF(i.istatus=2,i.iprice,0)) check_in_amount,SUM(i.istatus=3) check_out_count,SUM(IF(i.istatus=3,i.iprice,0)) check_out_amount FROM iwide_hotel_orders o LEFT JOIN iwide_hotel_order_items i ON o.inter_id=i.inter_id AND o.orderid=i.orderid WHERE o.inter_id=?";
		$params[] = $inter_id;
		if(!empty($btime)){
			$sql .= ' AND o.order_time>=?';
			$params[] = $btime;
		}
		if(!empty($etime)){
			$sql .= ' AND o.order_time<=?';
			$params[] = $etime;
		}
		$sql .= '  GROUP BY o.hotel_id';
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$params[] = $offset;
			$params[] = $limit;
		}
		return $this->_db('iwide_r1')->query($sql,$params);
	}
	public function get_booking_summary_count($inter_id,$btime = '',$etime = ''){
		$sql = 'SELECT COUNT(DISTINCT o.hotel_id) counts FROM iwide_hotel_orders o WHERE o.inter_id=?';
		$params[] = $inter_id;
		if(!empty($btime)){
			$sql .= ' AND o.order_time>=?';
			$params[] = $btime;
		}
		if(!empty($etime)){
			$sql .= ' AND o.order_time<=?';
			$params[] = $etime;
		}
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$params[] = $offset;
			$params[] = $limit;
		}
		return $this->_db('iwide_r1')->query($sql,$params)->row()->counts;
	}

	public function date_set_grid_fields(){
		return array(
			'hotel_id'     => array(
				'label' => '酒店ID'
			),

			'name'         => array(
				'label' => '酒店'
			),
			'config'       => array(
				'label' => '当前配置',
				'array' => array(
					'fill1'=>[],
					'hour'=>[
						'label'=>'点'
					],
					'compare_name'  => [
						'label'=>'可预订前',
					],
					'val'      => [
						'label'  => '天的房',
					],
					'curr_status'=>[
						'label'=>'',
					],

				),
			),
			'set_config'       => array(
				'label' => '修改配置',
				'array' => array(
					'id'       => [
						'hidden' => true,
					],
					'compare'  => [
						'hidden' => true,
					],
					
					'fill1'=>[
						'label'=>'',
					],
					'hour'=>[
						'label'=>'点',
						'enable' => true,
					],
					'compare_name'  => [
						'label'=>'可预订前',
					],
					'val'      => [
						'label'  => '天的房',
						'select'=>[
							'0'=>'0',
							'1'=>'1',
						],
					],
					'fill2'=>[],
					'priority' => [
						'label'  => '',
						'select' => [
							'0'  => '可用',
							'-1' => '无效',
						]
					],
					
					'module'=>['hidden'=>true],
					'param_name'=>['hidden'=>true],
				
				),
			),
		);
	}

}