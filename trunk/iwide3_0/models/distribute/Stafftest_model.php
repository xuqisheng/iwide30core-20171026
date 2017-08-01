<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Stafftest_model extends MY_Model {

	public function get_resource_name()
	{
		return '员工信息';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function table_name()
	{
		return 'hotel_staff';
	}

	public function table_primary_key()
	{
	    return 'id';
	}
	
	public function attribute_labels(){
		return array('hotel_id'       => '酒店ID',
					 'inter_id'       => '公众号ID',
					 'name'           => '姓名',
					 'sex'            => '性别',
					 'birthday'       => '生日',
					 'education'      => '学历',
					 'graduation'     => '毕业院校',
					 'position'       => '职位/岗位',
					 'business'       => '业务分工',
					 'in_date'        => '入职日期',
					 'changes'        => '变动记录',
					 'previous_job'   => '前份工作情况',
					 'description'    => '备注信息',
					 'master_dept'    => '一级部门',
					 'second_dept'    => '二级部门',
					 'employee_id'    => '人员编码',
					 'in_group_date'  => '入集团日期',
					 'cellphone'      => '手机号码',
					 'hotel_name'     => '酒店',
					 'view_count'     => 'view_count',
					 'status'         => '状态',
					 'qrcode_id'      => '分销号',
					 'lock'           => '锁定',
					 'id_card'        => 'id_card',
					 'status_time'    => '失效时间',
					 'is_distributed' => '参与分销',
					 'verify'         => 'verify',
					 'verified'       => 'verified',
					 'id'             => '员工ID',
					 'openid'         => 'OPENID');
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
		'id',
		'name',
		'sex',
		'position',
		'business',
//		'second_dept',
		'employee_id',
		'cellphone',
		'hotel_name',
		'inter_id',
		'status',
		'qrcode_id',
		'lock',
//		'status_time',
		'is_distributed',
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
	public function attribute_ui()
	{
			/* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
			// type: numberbox数字框|combobox下拉框|text不写时默认|datebox
		$base_util = EA_base::inst ();
		$modules = config_item ( 'admin_panels' ) ? config_item ( 'admin_panels' ) : array ();
		
		$parents ['0'] = '一级分类';
		
		$status = array (
				'1' => '申请中',
				'2' => '正常',
				'3' => '未通过',
				'4' => '删除' 
		);
		
		/**
		 * 获取本管理员的酒店权限
		 */
		$this->_init_admin_hotels ();
		$publics = $hotels = array ();
		$filter = $filterH = NULL;
		
		if ($this->_admin_inter_id == FULL_ACCESS)
			$filter = array ();
		else if ($this->_admin_inter_id)
			$filter = array ('inter_id' => $this->_admin_inter_id );
		if (is_array ( $filter )) {
			$this->load->model ( 'wx/publics_model' );
			$publics = $this->publics_model->get_public_hash ( $filter );
			$publics = $this->publics_model->array_to_hash ( $publics, 'name', 'inter_id' );
			// $publics= $publics+ array(FULL_ACCESS=>'-所有公众号-');
		}
		
		if ($this->_admin_hotels == FULL_ACCESS)
			$filterH = array ();
		else if ($this->_admin_hotels)
			$filterH = array ('hotel_id' => $this->_admin_hotels);
		else
			$filterH = array ();
		if(!isset($filterH['inter_id']))$filterH['inter_id'] = $this->session->get_admin_inter_id();
		
		if ($publics && is_array ( $filterH )) {
			$this->load->model ( 'hotel/hotel_model' );
			$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
			$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
			$hotels = $hotels + array ('0' => '-不限定-' );
		}
		return array (
				'id' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
,
				'name' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui'=> ' readonly ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
,
				'sex' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type'=>'combobox',
                		'select'=> array('1'=>'男','0'=>'女'), 
				) // textarea|text|combobox|number|email|url|price
,
				'birthday' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'datebox' 
				) // textarea|text|combobox|number|email|url|price
,
				'education' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
,
				'graduation' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
,
				'position' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
,
				'business' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
,
				'in_date' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'datebox' 
				) // textarea|text|combobox|number|email|url|price
,
				'changes' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'textarea' 
				) // textarea|text|combobox|number|email|url|price
,
				'previous_job' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'textarea' 
				) // textarea|text|combobox|number|email|url|price
,
				'description' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'textarea' 
				) // textarea|text|combobox|number|email|url|price
,
				'hotel_id' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type'=>'combobox',
                		'select'=> $hotels,
				) // textarea|text|combobox|number|email|url|price
,
				'master_dept' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
,
				'second_dept' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
,
				'employee_id' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
,
				'in_group_date' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'datebox' 
				) // textarea|text|combobox|number|email|url|price
,
				'cellphone' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
,
				'hotel_name' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
,
				'inter_id' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
					    'form_ui'=> ' readonly ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
// 						'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type'=>'combobox',
                		'select'=> $publics,
				) // textarea|text|combobox|number|email|url|price
,
				'view_count' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
,
				'status' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type'=>'combobox',
                		'select'=> $status, 
				) // textarea|text|combobox|number|email|url|price
,
				'qrcode_id' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui'=> ' readonly ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'number' 
				) // textarea|text|combobox|number|email|url|price
,
				'lock' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
,
				'id_card' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
,
				'status_time' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
,
				'is_distributed' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type'=>'combobox',
                		'select'=> array('1'=>'是','2'=>'否'), 
				) // textarea|text|combobox|number|email|url|price
,
				'verify' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
,
				'verified' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
,
				'openid' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
 
		);
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */
	
	function saler_exist($openid,$inter_id){
		$this->db->where(array('inter_id'=>$inter_id,'openid'=>$openid));
		return $this->db->get('hotel_staff')->num_rows() > 0;
	}
	function saler_info($openid,$inter_id){
		$sql = "SELECT s.*,q.url FROM (SELECT * FROM iwide_hotel_staff  WHERE inter_id=? AND openid=?) s
				LEFT JOIN (SELECT * FROM iwide_qrcode WHERE inter_id=?) q ON s.inter_id=q.inter_id AND s.qrcode_id=q.id";
		$rs = $this->db->query($sql,array($inter_id,$openid,$inter_id));
		if($rs->num_rows() > 0){
			$bs_info = $rs->row_array();
			$this->db->where(array('inter_id'=>$inter_id,'openid'=>$openid));
			$bs_info['ext'] = $this->db->get('fans')->row_array();
			return $bs_info;
		}else{
			return NULL;
		}
	}
	
	/**
	 * 分销员状态是否有效
	 * @param unknown $inter_id
	 * @param unknown $openid
	 * @return boolean
	 */
	function saler_is_valid($inter_id,$openid){
		$this->db->where(array('openid'=>$openid,'inter_id'=>$inter_id,'status'=>2));
		return $this->db->get('hotel_staff')->num_rows() > 0;
	}
	
	function get_my_info($openid,$inter_id){
// 		$sql = 'SELECT count(DISTINCT openid) fans_count FROM (SELECT * FROM '.$this->db->dbprefix('fans_sub_log').' WHERE `event`=2 AND inter_id=? GROUP BY openid) ds WHERE `source`=?';
		$sql = 'SELECT COUNT(*) fans_count FROM (SELECT * FROM iwide_fans_sub_log WHERE `event`=2 AND inter_id=? GROUP BY openid) a WHERE a.source=?';
		$saler_details = $this->saler_info($openid,$inter_id);
		$fans_query = $this->db->query($sql,array($inter_id,$saler_details['qrcode_id']))->row_array();
		$sql = "";
		$sql = "SELECT SUM(grade_total) total_fee FROM iwide_distribute_grade_all WHERE saler=? AND inter_id=? AND `status`=1";
		$fee_query = $this->db->query($sql,array($saler_details['qrcode_id'],$inter_id))->row_array();
		$total_fee = 0;
		if(isset($fee_query['total_fee']))$total_fee = $fee_query['total_fee'];
		return array('name'=>$saler_details['name'],'total_fee'=>$total_fee,'headimgurl'=>$saler_details['ext']['headimgurl'],'url'=>$saler_details['url'],'id'=>$saler_details['qrcode_id'],'fans_count'=>$fans_query['fans_count']);
	}
	/**
	 * 粉丝产生收益
	 * @param unknown $openid
	 * @param unknown $inter_id
	 */
	function get_fans_recs_all($openid,$inter_id){
		$saler_info = $this->get_my_base_info_openid($inter_id, $openid);
		if(empty($saler_info)){
			return null;
		}
		$fans_details = $this->get_my_fans_by_openid($openid,$inter_id)->result_array();
		$openids_arr  = array_column($fans_details,'openid');
		$openids_str  = implode($openids_arr,"','");
		$sql = "SELECT * FROM (SELECT `grade_openid`,`inter_id`,SUM(dg.grade_total) total_fee,SUM(dg.grade_amount) total_amount,SUM(dg.order_amount) order_amount,count(*) total FROM ".$this->db->dbprefix("distribute_grade_all")." dg WHERE inter_id=? AND saler=? GROUP BY grade_openid) g RIGHT JOIN (SELECT `nickname`,`headimgurl`,`openid`,`inter_id`,`id` fid FROM ".$this->db->dbprefix("fans")." WHERE inter_id=? AND openid IN ('".$openids_str."')) f ON f.inter_id=g.inter_id AND f.openid=g.grade_openid";
		return $this->db->query($sql,array($inter_id,$saler_info['qrcode_id'],$inter_id));
	}
	/**
	 * 粉丝收入详细
	 * @param int 粉丝openID
	 * @param int 分销员ID
	 * @param string 公众号标识
	 */
	function get_fans_recs($fans_openid,$saler_id,$inter_id){
		$sql = 'SELECT ga.*,ge.cellphone,ge.distribute,ge.hotel_name,ge.order_id,ge.product,ge.staff_name,f.nickname,f.headimgurl,f.id fid FROM iwide_distribute_grade_all ga INNER JOIN iwide_distribute_grade_ext ge ON ga.inter_id=ge.inter_id AND ga.id=ge.grade_id AND ga.inter_id=? AND ga.saler=? AND ga.grade_openid=? LEFT JOIN iwide_fans f ON f.inter_id=ga.inter_id AND f.openid=ga.grade_openid';
		return $this->db->query($sql,array($inter_id,$saler_id,$fans_openid));
	}
	
	/**
	 * @todo 通过openid获取我的粉丝列表
	 * @param string 分销号
	 * @param string 公众号识别码
	 * @param int 取的数量
	 * @param int 起始位置
	 * @return Fans Query
	 */
	function get_my_fans_by_openid($openid,$inter_id,$limit=null,$offset=0){
// 		$sql = 'SELECT f.openid,f.nickname,f.headimgurl,f.inter_id,fl.event_time subcribe_time FROM (SELECT * FROM '.$this->db->dbprefix('fans').' WHERE inter_id=?) f RIGHT JOIN (SELECT openid,event_time,inter_id FROM (SELECT * FROM '.$this->db->dbprefix('fans_sub_log').' WHERE `event`=2 AND inter_id=? GROUP BY openid) ds WHERE `source`=? GROUP BY openid ORDER BY event_time DESC) fl ON f.openid=fl.openid AND f.inter_id=fl.inter_id WHERE NOT ISNULL(f.openid)';
		$saler_details = $this->get_my_base_info_openid($inter_id,$openid);
// 		return $this->db->query($sql,array($inter_id,$inter_id,$saler_details['qrcode_id']));
		return $this->get_my_fans_by_saler_id($saler_details['qrcode_id'], $inter_id,$limit=null,$offset=0);
	}
	
	/**
	 * @todo 通过saler_id获取我的粉丝列表
	 * @param string 分销号
	 * @param string 公众号识别码
	 * @param int 取的数量
	 * @param int 起始位置
	 * @return Fans Query
	 */
	function get_my_fans_by_saler_id($saler_id,$inter_id,$limit=null,$offset=0){
		$sql = 'SELECT f.openid,f.nickname,f.headimgurl,f.inter_id,fl.event_time subcribe_time FROM (SELECT * FROM '.$this->db->dbprefix('fans').' WHERE inter_id=?) f RIGHT JOIN (SELECT openid,event_time,inter_id FROM (SELECT * FROM '.$this->db->dbprefix('fans_sub_log').' WHERE `event`=2 AND inter_id=? GROUP BY openid) ds WHERE `source`=? GROUP BY openid ORDER BY event_time DESC) fl ON f.openid=fl.openid AND f.inter_id=fl.inter_id';
		// 		return $this->db->query($sql,array($inter_id,$saler_id));
		return $this->db->query($sql,array($inter_id,$inter_id,$saler_id));
	}
	
// 	function get_my_fans_by_openid($openid,$inter_id,$limit=null,$offset=0){
// 		$sql = 'SELECT f.openid,f.nickname,f.headimgurl,f.inter_id,fl.event_time subcribe_time FROM (SELECT * FROM '.$this->db->dbprefix('fans').' WHERE inter_id=?) f RIGHT JOIN (SELECT openid,event_time,inter_id FROM '.$this->db->dbprefix('fans_sub_log').' WHERE `event`=2 AND inter_id=? AND `source`=? ORDER BY event_time DESC) fl ON f.openid=fl.openid AND f.inter_id=fl.inter_id WHERE NOT ISNULL(f.openid)';
// 		$saler_details = $this->get_my_base_info_openid($inter_id, $openid);
// // 		$saler_details = $this->get_saler_details_by_openid($inter_id,$openid);
// 		return $this->db->query($sql,array($inter_id,$inter_id,$saler_details['qrcode_id']));
// 	}
	
// 	function get_my_fans_by_saler_id($saler_id,$inter_id,$limit=null,$offset=0){
// 		$sql = 'SELECT f.openid,f.nickname,f.headimgurl,f.inter_id,fl.event_time subcribe_time FROM (SELECT * FROM '.$this->db->dbprefix('fans').' WHERE inter_id=?) f RIGHT JOIN (SELECT openid,event_time,inter_id FROM '.$this->db->dbprefix('fans_sub_log').' WHERE `event`=2 AND inter_id=? AND `source`=? ORDER BY event_time DESC) fl ON f.openid=fl.openid AND f.inter_id=fl.inter_id';
// 		return $this->db->query($sql,array($inter_id,$inter_id,$saler_id));
// 	}
	
	function get_saler_details_by_openid($inter_id,$openid){
// 		$sql = "SELECT sd.*,f.nickname,f.headimgurl FROM (SELECT hs.cellphone,hs.hotel_name,hs.hotel_id,hs.inter_id,hs.`name`,ds.saler saler_id,ds.saler_openid openid
// 				FROM (SELECT * FROM iwide_hotel_staff WHERE inter_id=?) hs LEFT JOIN (SELECT * FROM iwide_distribute_salers WHERE inter_id=?) ds ON hs.qrcode_id=ds.saler AND hs.inter_id=ds.inter_id WHERE saler_openid=? AND ds.inter_id=? limit 1) sd
// 				LEFT JOIN (SELECT `nickname`,`headimgurl`,`openid`,`inter_id` FROM iwide_fans WHERE inter_id=?) f ON f.openid=sd.openid AND f.inter_id=sd.inter_id limit 1";
		$sql = "SELECT sd.*,f.nickname,f.headimgurl FROM (SELECT hs.cellphone,hs.hotel_name,hs.hotel_id,hs.inter_id,hs.`name`,hs.qrcode_id saler_id,hs.openid
                FROM (SELECT * FROM iwide_hotel_staff WHERE inter_id=?) hs 
								WHERE hs.openid=? AND hs.inter_id=? limit 1) sd
								LEFT JOIN (SELECT `nickname`,`headimgurl`,`openid`,`inter_id` FROM iwide_fans WHERE inter_id=?) f ON f.openid=sd.openid AND f.inter_id=sd.inter_id limit 1";
		return $this->db->query($sql,array($inter_id,$openid,$inter_id,$inter_id))->row_array();
	}
	function get_saler_details_by_salerid($inter_id,$saler){
		$sql = "SELECT sd.*,f.nickname,f.headimgurl FROM (SELECT hs.cellphone,hs.hotel_name,hs.hotel_id,hs.inter_id,hs.`name`,ds.saler saler_id,ds.saler_openid openid
				FROM (SELECT * FROM iwide_hotel_staff WHERE inter_id=?) hs LEFT JOIN (SELECT * FROM iwide_distribute_salers WHERE inter_id=?) ds ON hs.qrcode_id=ds.saler AND hs.inter_id=ds.inter_id WHERE saler=? AND ds.inter_id=? limit 1) sd
				LEFT JOIN (SELECT `nickname`,`headimgurl`,`openid`,`inter_id` FROM iwide_fans WHERE inter_id=?) f ON f.openid=sd.openid AND f.inter_id=sd.inter_id limit 1";
	
		return $this->db->query($sql,array($inter_id,$inter_id,$saler,$inter_id,$inter_id))->row_array();
	}
	/**
	 * @todo 根据inter_id、openid获取员工基本信息
	 * @param string $inter_id
	 * @param string $openid
	 * return saler row
	 */
	function get_my_base_info_openid($inter_id,$openid){
		$this->db->where(array('inter_id'=>$inter_id,'openid'=>$openid));
		$this->db->limit(1);
		return $this->db->get('hotel_staff')->row_array();
	}
	/**
	 * @todo 根据inter_id,saler_id获取员工基本信息
	 * @param string $inter_id
	 * @param string $saler_id
	 * @return saler row
	 */
	function get_my_base_info_saler($inter_id,$saler_id){
		$this->db->where(array('inter_id'=>$inter_id,'qrcode_id'=>$saler_id));
		$this->db->limit(1);
		return $this->db->get('hotel_staff')->row_array();
	}
	function save_register(){
		$data['name']        = trim($this->input->post('name',true));
		$data['id_card']     = trim($this->input->post('idnum',true));
		$data['cellphone']   = trim($this->input->post('cellphone',true));
		$data['hotel_id']    = trim($this->input->post('hotel',true));
		$data['master_dept'] = trim($this->input->post('department',true));
		$data['openid']      = $this->session->userdata($this->session->userdata('inter_id').'openid');
		$data['inter_id']    = $this->session->userdata('inter_id');
		$data['status_time'] = date('Y-m-d H:i:s');
		$data['status']      = 1;//默认申请状态
		$this->load->model ( 'hotel/hotel_model' );
		$hotels = $this->hotel_model->get_hotel_hash ( array('inter_id'=>$this->session->userdata('inter_id')) );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		$data['hotel_name'] = $hotels[$data['hotel_id']];
// 		var_dump($this->input->post());exit;
        $pid= $this->input->post('id');
		if(!empty($pid)){
			$this->db->where(array('id'=>$this->input->post('id'),'inter_id'=>$this->session->userdata('inter_id')));
			if($this->db->update('hotel_staff',$data) > 0){
				return $this->input->post('id');
			}else{
				return -1;
			}
		}else{
			//Prevent double insertion
			$this->db->where(array('inter_id'=>$this->session->userdata('inter_id'),'openid'=>$data['openid']));
			$this->db->limit(1);
			$query = $this->db->get('hotel_staff')->num_rows();
			if($query < 1){
				if($this->db->insert('hotel_staff',$data) > 0){
					return $this->db->insert_id();
				}else{
					return -1;
				}
			}else{
				return -1;
			}
		}
	}
	/**
	 * 保存员工信息到saler表
	 * @param string 公众号识别码
	 * @param int 员工分销号
	 * @return boolean
	 */
	function save_staff_to_saler($inter_id,$saler){
		$sql = 'SELECT COUNT(*) nums FROM iwide_distribute_salers WHERE inter_id=? AND saler=? LIMIT 1';
		$count_query = $this->db->query($sql,array($inter_id,$saler))->row_array();
		if($count_query['nums'] < 1){
			$sql = 'INSERT INTO iwide_distribute_salers (saler,saler_openid,inter_id,hotel_id,`status`,create_time,`level`) SELECT qrcode_id,openid,inter_id,hotel_id,1,NOW(),1 FROM iwide_hotel_staff WHERE qrcode_id=? AND inter_id=? LIMIT 1';
			return $this->db->query($sql,array($saler,$inter_id)) > 0;
			
		}
		return true;
	}
	function get_room_rec_info($openid,$inter_id){
		$my_info = $this->saler_info($openid, $inter_id);
		$sql = 'SELECT bs.*,f.nickname,f.headimgurl FROM (SELECT dga.*,hoi.orderid,hoi.room_id,hoi.iprice,hoi.startdate,hoi.enddate,hoi.allprice,hoi.roomname FROM 
				(SELECT * from iwide_distribute_grade_all WHERE inter_id=? AND saler=?) dga 
				LEFT JOIN (SELECT * FROM iwide_hotel_order_items WHERE inter_id=?) hoi ON hoi.id=dga.grade_id) bs LEFT JOIN (SELECT * FROM iwide_fans WHERE inter_id=?) f ON bs.grade_openid=f.openid';
		return $this->db->query($sql,array($inter_id,$my_info['qrcode_id'],$inter_id,$inter_id));
	}
	
	/**
	 * 获取所有产生交易的粉丝信息
	 * @param unknown $openid
	 * @param unknown $inter_id
	 */
	function i_get_room_rec_info($openid,$inter_id){
		$key = $inter_id."_get_room_rec_info_".$openid;
		$obj = $this->redis()->get($key);
		if(empty($obj)){
			unset($obj);
				
			$my_info = $this->saler_info($openid, $inter_id);
			$sql = 'SELECT bs.*,f.nickname,f.headimgurl FROM (SELECT dga.*,hoi.orderid,hoi.room_id,hoi.iprice,hoi.startdate,hoi.enddate,hoi.allprice,hoi.roomname FROM
				(SELECT * from iwide_distribute_grade_all WHERE inter_id=? AND saler=?) dga
				LEFT JOIN (SELECT * FROM iwide_hotel_order_items WHERE inter_id=?) hoi ON hoi.id=dga.grade_id) bs LEFT JOIN (SELECT * FROM iwide_fans WHERE inter_id=?) f ON bs.grade_openid=f.openid';
				
			$obj = $this->db->query($sql,array($inter_id,$my_info['qrcode_id'],$inter_id,$inter_id))->result_array();
			$str = $this->my_serialize($obj);
			$this->redis()->set($key,$str,60);
			return $obj;
		}else{
			$obj_result = $this->my_unserialize($obj);
			return $obj_result;
		}
	
	}
	
	function get_fans_details($fans_id,$inter_id){
		$this->db->where(array('id'=>$fans_id,'inter_id'=>$inter_id));
		$this->db->select(array('id','headimgurl','nickname','openid'));
		$this->db->limit(1);
		$fans_details = $this->db->get('fans')->row_array();
		$sql = "SELECT SUM(dg.grade_total) total_fee,count(dg.grade_id) total FROM ".$this->db->dbprefix("distribute_grade_all")." dg WHERE inter_id=? AND grade_openid = ? AND saler > 0 ";
		$fee_query = $this->db->query($sql,array($inter_id,$fans_details['openid']))->row_array();
		return array('total_fee'=>$fee_query['total_fee'],'total'=>$fee_query['total'],'fans_info'=>$fans_details);
	}
	/**
	 * 收入列表
	 * @param string 分销员openid
	 * @param unknown $inter_id
	 */
	function get_all_room_rec_info($openid,$inter_id){
		$saler_details = $this->get_saler_details_by_openid($inter_id, $openid);
		return $this->get_all_room_rec_info_by_salerid($saler_details['saler_id'], $inter_id);
	}
	function get_all_room_rec_info_by_salerid($saler,$inter_id){
		$sql = "SELECT bs.*,f.nickname,f.headimgurl FROM (SELECT dge.hotel_name name,dg.grade_openid openid,dg.inter_id,dg.grade_total,dg.`status` gstatus,dg.grade_time,dg.grade_table,dg.grade_rate_type,dg.order_amount,dge.order_id,dg.order_amount iprice,dge.product roomname,dg.grade_time order_time FROM
                (SELECT * FROM iwide_distribute_grade_all WHERE inter_id=? AND saler=? AND grade_table<>'iwide_fans_sub_log') dg
								LEFT JOIN iwide_distribute_grade_ext dge ON dg.id=dge.grade_id AND dg.inter_id=dge.inter_id ) bs LEFT JOIN (SELECT headimgurl,nickname,inter_id,openid FROM iwide_fans WHERE inter_id=?) f ON f.inter_id=bs.inter_id AND bs.openid=f.openid
                UNION
                SELECT  dge.hotel_name name,dg.grade_openid openid,dg.inter_id,dg.grade_total,dg.`status` gstatus,dg.grade_time,dg.grade_table,dg.grade_rate_type,dg.order_amount allprice,dge.order_id orderid,dg.order_amount iprice,'关注',dg.grade_time order_time,f.nickname,f.headimgurl FROM (SELECT * FROM iwide_distribute_grade_all WHERE inter_id=? AND saler=? AND grade_table='iwide_fans_sub_log') dg 
LEFT JOIN iwide_distribute_grade_ext dge ON dg.grade_id=dge.grade_id AND dg.inter_id=dge.inter_id
LEFT JOIN iwide_fans f ON f.openid=dg.grade_openid AND f.inter_id=dg.inter_id ORDER BY grade_time DESC";
		return $this->db->query($sql,array($inter_id,$saler,$inter_id,$inter_id,$saler));
	
	}
	
	/**
	 * 根据分销员获取所有订房分销收入
	 * @param unknown $saler
	 * @param unknown $inter_id
	 */
	function i_get_all_room_rec_info_by_salerid($saler,$inter_id){
		$key = $inter_id."_get_all_room_rec_info_by_salerid_".$saler;
		$obj = $this->redis()->get($key);
		if(empty($obj)){
			unset($obj);
	
			$sql = "SELECT bs.*,f.nickname,f.headimgurl FROM (SELECT dge.hotel_name name,dg.grade_openid openid,dg.inter_id,dg.grade_total,dg.`status` gstatus,dg.grade_time,dg.grade_table,dg.grade_rate_type,dg.order_amount,dge.order_id,dg.order_amount iprice,dge.product roomname,dg.grade_time order_time FROM
                (SELECT * FROM iwide_distribute_grade_all WHERE inter_id=? AND saler=? AND grade_table<>'iwide_fans_sub_log') dg
								LEFT JOIN iwide_distribute_grade_ext dge ON dg.id=dge.grade_id AND dg.inter_id=dge.inter_id ) bs LEFT JOIN (SELECT headimgurl,nickname,inter_id,openid FROM iwide_fans WHERE inter_id=?) f ON f.inter_id=bs.inter_id AND bs.openid=f.openid
                UNION
                SELECT  dge.hotel_name name,dg.grade_openid openid,dg.inter_id,dg.grade_total,dg.`status` gstatus,dg.grade_time,dg.grade_table,dg.grade_rate_type,dg.order_amount allprice,dge.order_id orderid,dg.order_amount iprice,'关注',dg.grade_time order_time,f.nickname,f.headimgurl FROM (SELECT * FROM iwide_distribute_grade_all WHERE inter_id=? AND saler=? AND grade_table='iwide_fans_sub_log') dg
LEFT JOIN iwide_distribute_grade_ext dge ON dg.grade_id=dge.grade_id AND dg.inter_id=dge.inter_id
LEFT JOIN iwide_fans f ON f.openid=dg.grade_openid AND f.inter_id=dg.inter_id ORDER BY grade_time DESC";
			$obj = $this->db->query($sql,array($inter_id,$saler,$inter_id,$inter_id,$saler))->result_array();
	
			$str = $this->my_serialize($obj);
			$this->redis()->set($key,$str,60);
				
			return $obj;
		}else{
			$obj_result = $this->my_unserialize($obj);
			return $obj_result;
		}
	}
	
	function my_drw_logs($openid,$inter_id){
		$this->db->where(array('saler_openid'=>$openid,'inter_id'=>$inter_id));
		$this->db->order_by('order_time DESC');
		return $this->db->get('distribute_withdraw');
	}
	/**
	 * 总收益
	 * @param unknown $openid
	 * @param unknown $inter_id
	 * @return unknown
	 */
	function get_all_income($openid,$inter_id){
		$fans_details = $this->get_my_fans_by_openid($openid,$inter_id)->result_array();
		$openids_arr  = array_column($fans_details,'openid');
		$openids_str  = implode($openids_arr,"','");
// 		$openids_str  = "'".$openids_str."'";
		$sql = "SELECT SUM(dg.grade_total) total_fee FROM ".$this->db->dbprefix("distribute_grade_all")." dg WHERE inter_id=? AND `status`=1 AND grade_openid in ('".$openids_str."')";
		$fee_query = $this->db->query($sql,array($inter_id))->row_array();
		return $fee_query['total_fee'];
	}
	/**
	 * 收入排行
	 * @param Char $inter_id
	 * @param String ALL|MONTH|YEAR
	 * @param number $limit
	 */
	function get_user_ranking($inter_id,$type='MONTH',$limit=15){
		$sql = 'SELECT sl.*,f.nickname,f.headimgurl,f.openid,@ranking:= @ranking + 1 rank FROM 
(SELECT s.*,hs.hotel_id,hs.`name`,hs.openid,hs.`cellphone`,hs.hotel_name FROM 
	(SELECT a.`saler`,a.total_amount,a.inter_id,a.order_amount,a.grade_amount,a.nums FROM 
		(SELECT SUM(grade_total) total_amount,COUNT(*) nums,dga.saler,dga.inter_id,dga.order_amount,dga.grade_amount, (SELECT @ranking := 0) b 
			FROM iwide_distribute_grade_all dga WHERE inter_id=?';
		if($type == 'DAY'){
			$sql .= " AND DATE_FORMAT(dga.grade_time,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d')";
		}elseif($type == 'MONTH'){
			$sql .= " AND DATE_FORMAT(dga.grade_time,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')";
		}elseif($type == 'YEAR'){
			$sql .= " AND DATE_FORMAT(dga.grade_time,'%Y')=DATE_FORMAT(NOW(),'%Y')";
		}
		$sql .= ' GROUP BY dga.saler) a) s
				INNER JOIN (SELECT * FROM iwide_hotel_staff WHERE inter_id=? AND is_distributed=1 AND `status`=2) hs ON s.saler=hs.qrcode_id AND hs.inter_id=s.inter_id WHERE s.inter_id=? ORDER BY s.total_amount DESC ';
		$param = array($inter_id,$inter_id,$inter_id);
		if($limit){
			$sql .= ' LIMIT ?';
			array_push($param,$limit);
		}
		$sql .= ') sl LEFT JOIN (SELECT * FROM iwide_fans WHERE inter_id=?) f ON sl.inter_id=f.inter_id AND sl.openid=f.openid';
		array_push($param,$inter_id);
// 		array_push($param,$inter_id);print $sql;die;
		return $this->db->query($sql,$param);
	}
	
	/**
	 * 收入排行
	 * @param Char $inter_id
	 * @param String ALL|MONTH|YEAR
	 * @param number $limit
	 */
	function i_get_user_ranking($inter_id,$type='MONTH',$limit=15){
		$key = $inter_id."_get_user_ranking_".$type;
		$obj = $this->redis()->get($key);
		if(empty($obj)){
			unset($obj);
	
			$sql = 'SELECT sl.*,f.nickname,f.headimgurl,f.openid,@ranking:= @ranking + 1 rank FROM
(SELECT s.*,hs.hotel_id,hs.`name`,hs.openid,hs.`cellphone`,hs.hotel_name FROM
	(SELECT a.`saler`,a.total_amount,a.inter_id,a.order_amount,a.grade_amount,a.nums FROM
		(SELECT SUM(grade_total) total_amount,COUNT(*) nums,dga.saler,dga.inter_id,dga.order_amount,dga.grade_amount, (SELECT @ranking := 0) b
			FROM iwide_distribute_grade_all dga WHERE inter_id=?';
			if($type == 'DAY'){
				$sql .= " AND DATE_FORMAT(dga.grade_time,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d')";
			}elseif($type == 'MONTH'){
				$sql .= " AND DATE_FORMAT(dga.grade_time,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')";
			}elseif($type == 'YEAR'){
				$sql .= " AND DATE_FORMAT(dga.grade_time,'%Y')=DATE_FORMAT(NOW(),'%Y')";
			}
			$sql .= ' GROUP BY dga.saler) a) s
					INNER JOIN (SELECT * FROM iwide_hotel_staff WHERE inter_id=? AND is_distributed=1 AND `status`=2) hs ON s.saler=hs.qrcode_id AND hs.inter_id=s.inter_id WHERE s.inter_id=? ORDER BY s.total_amount DESC ';
			$param = array($inter_id,$inter_id,$inter_id);
			if($limit){
				$sql .= ' LIMIT ?';
				array_push($param,$limit);
			}
			$sql .= ') sl LEFT JOIN (SELECT * FROM iwide_fans WHERE inter_id=?) f ON sl.inter_id=f.inter_id AND sl.openid=f.openid';
			array_push($param,$inter_id);
			$obj = $this->db->query($sql,$param)->result_array();
	
			$str = $this->my_serialize($obj);
			$this->redis()->set($key,$str,120);
	
			return $obj;
		}else{
			$obj_result = $this->my_unserialize($obj);
			return $arr_result;
		}
	
	}
	
	function get_fans_ranking($inter_id,$type='MONTH',$limit=15){
		// $sql = 'SELECT bs.*,f.nickname,f.headimgurl FROM (SELECT *,@rank:=@rank+1 rank FROM (SELECT hs.`name`,hs.cellphone,hs.hotel_id,hs.hotel_name,hs.qrcode_id,hs.master_dept,hs.is_distributed,hs.position,hs.second_dept,hs.openid,sr.* FROM (SELECT * FROM iwide_hotel_staff WHERE inter_id=? AND is_distributed=1) hs 
		// 		INNER JOIN (SELECT s.saler,s.inter_id,COUNT(saler) fans_count,(SELECT @rank:=0) FROM 
		// 		(SELECT source saler,openid,event_time sub_time,inter_id FROM iwide_fans_sub_log WHERE id IN (SELECT MAX(id) FROM iwide_fans_sub_log WHERE source>0 AND inter_id=? AND `event`=2 GROUP BY openid)'; 
		// if($type == 'DAY'){
		// 	$sql .= " AND DATE_FORMAT(event_time,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d')";
		// }elseif($type == 'MONTH'){
		// 	$sql .= " AND DATE_FORMAT(event_time,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')";
		// }elseif($type == 'YEAR'){
		// 	$sql .= " AND DATE_FORMAT(event_time,'%Y')=DATE_FORMAT(NOW(),'%Y')";
		// }
		// $sql .= ') s GROUP BY s.saler ORDER BY fans_count DESC) sr ON sr.saler=hs.qrcode_id AND sr.inter_id=hs.inter_id)s ORDER BY fans_count DESC) bs
		// 		LEFT JOIN (SELECT * FROM iwide_fans WHERE inter_id=?) f ON bs.openid=f.openid AND bs.inter_id=f.inter_id ORDER BY rank ASC';
		// $param = array($inter_id,$inter_id,$inter_id);
		// if(!is_null($limit)){
		// 	$sql .= ' limit ?';
		// 	array_push($param,$limit);
		// }

		$sql = "SELECT a.*,f.nickname,f.headimgurl FROM 
		(SELECT *,@ranking:= @ranking + 1 rank  FROM (SELECT dsa.*,hs.`name`,hs.business,hs.master_dept,hs.hotel_id,hs.cellphone,hs.hotel_name,hs.`status`,hs.is_distributed,hs.openid
			FROM (SELECT inter_id,saler,grade_time,count(*) fans_count FROM iwide_distribute_grade_all 
			WHERE inter_id=? AND grade_table='iwide_fans_sub_log'";
		if($type == 'DAY'){
			$sql .= " AND DATE_FORMAT(grade_time,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d') ";
		}elseif($type == 'MONTH'){
			$sql .= " AND DATE_FORMAT(grade_time,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m') ";
		}elseif($type == 'YEAR'){
			$sql .= " AND DATE_FORMAT(grade_time,'%Y')=DATE_FORMAT(NOW(),'%Y') ";
		}
			$sql .= " GROUP BY saler) dsa 
		INNER JOIN (SELECT *,(SELECT @ranking := 0) b FROM iwide_hotel_staff WHERE inter_id=? AND is_distributed=1 AND `status`=2) hs 
		ON hs.inter_id=dsa.inter_id AND hs.qrcode_id=dsa.saler ORDER BY fans_count DESC";
		$param = array($inter_id,$inter_id);
		if(!is_null($limit)){
			$sql .= " LIMIT ?";
			array_push($param,$limit);
		}
	
		$sql .= ") t ) a
		INNER JOIN (SELECT nickname,headimgurl,inter_id,openid FROM iwide_fans WHERE inter_id=?) f ON f.inter_id=a.inter_id AND a.openid=f.openid ORDER BY rank ASC";
		array_push($param,$inter_id);
		return $this->db->query($sql,$param);
	}
	
	/**
	 * 粉丝排行
	 * @param unknown $inter_id
	 * @param string $type
	 * @param number $limit
	 */
	function i_get_fans_ranking($inter_id,$type='MONTH',$limit=15){
		
	
		$key = $inter_id."_get_fans_ranking_".$type;
		$obj = $this->redis()->get($key);
		if(empty($obj)){
			unset($obj);
	
			$sql = "SELECT a.*,f.nickname,f.headimgurl FROM
		(SELECT *,@ranking:= @ranking + 1 rank  FROM (SELECT dsa.*,hs.`name`,hs.business,hs.master_dept,hs.hotel_id,hs.cellphone,hs.hotel_name,hs.`status`,hs.is_distributed,hs.openid
			FROM (SELECT inter_id,saler,grade_time,count(*) fans_count FROM iwide_distribute_grade_all
			WHERE inter_id=? AND grade_table='iwide_fans_sub_log'";
			if($type == 'DAY'){
				$sql .= " AND DATE_FORMAT(grade_time,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d') ";
			}elseif($type == 'MONTH'){
				$sql .= " AND DATE_FORMAT(grade_time,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m') ";
			}elseif($type == 'YEAR'){
				$sql .= " AND DATE_FORMAT(grade_time,'%Y')=DATE_FORMAT(NOW(),'%Y') ";
			}
			$sql .= " GROUP BY saler) dsa
			INNER JOIN (SELECT *,(SELECT @ranking := 0) b FROM iwide_hotel_staff WHERE inter_id=? AND is_distributed=1 AND `status`=2) hs
			ON hs.inter_id=dsa.inter_id AND hs.qrcode_id=dsa.saler ORDER BY fans_count DESC";
			$param = array($inter_id,$inter_id);
			if(!is_null($limit)){
				$sql .= " LIMIT ?";
				array_push($param,$limit);
			}
	
			$sql .= ") t ) a
			INNER JOIN (SELECT nickname,headimgurl,inter_id,openid FROM iwide_fans WHERE inter_id=?) f ON f.inter_id=a.inter_id AND a.openid=f.openid ORDER BY rank ASC";
			array_push($param,$inter_id);
			$obj = $this->db->query($sql,$param)->result_array();
			
			$str = $this->my_serialize($obj);
			$this->redis()->set($key,$str,30);
			
			echo "from ---mysql ---<br/>";
			return $obj;
		}else{
			echo "from ---redis ---<br/>";
			$obj_result =$this->my_unserialize($obj);
			return $obj_result;
		}
	
	
	}
	
	function get_user_rank($inter_id,$type='MONTH',$saler){
// 		$sql = 'SELECT bs.*,f.nickname,f.headimgurl FROM (SELECT * FROM (SELECT *,@rank:=@rank+1 rank FROM (SELECT hs.`name`,hs.cellphone,hs.hotel_id,hs.hotel_name,hs.qrcode_id,hs.master_dept,hs.is_distributed,hs.position,hs.second_dept,hs.openid,sr.* FROM (SELECT * FROM iwide_hotel_staff WHERE inter_id=? AND is_distributed=1) hs
// 	INNER JOIN (SELECT s.saler,s.inter_id,COUNT(saler) fans_count,(SELECT @rank:=0) FROM
// 	(SELECT source saler,openid,event_time sub_time,inter_id FROM iwide_fans_sub_log WHERE id IN (SELECT MAX(id) FROM iwide_fans_sub_log WHERE source>0 AND inter_id=? AND `event`=2 GROUP BY openid)';
// 		if($type == 'DAY'){
// 			$sql .= " AND DATE_FORMAT(event_time,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d')";
// 		}elseif($type == 'MONTH'){
// 			$sql .= " AND DATE_FORMAT(event_time,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')";
// 		}elseif($type == 'YEAR'){
// 			$sql .= " AND DATE_FORMAT(event_time,'%Y')=DATE_FORMAT(NOW(),'%Y')";
// 		}
// 	$sql .= ') s GROUP BY s.saler ORDER BY fans_count DESC) sr ON sr.saler=hs.qrcode_id AND sr.inter_id=hs.inter_id)s ORDER BY fans_count DESC) ll WHERE saler=?) bs
// LEFT JOIN (SELECT * FROM iwide_fans WHERE inter_id=?) f ON bs.openid=f.openid AND bs.inter_id=f.inter_id';
	
// 		return $this->db->query($sql,array($inter_id,$inter_id,$saler,$inter_id))->row_array();
		$sql = "SELECT a.*,f.nickname,f.headimgurl FROM 
		(SELECT *,@ranking:= @ranking + 1 rank  FROM (SELECT dsa.*,hs.`name`,hs.business,hs.master_dept,hs.hotel_id,hs.cellphone,hs.hotel_name,hs.`status`,hs.is_distributed,hs.openid
			FROM (SELECT inter_id,saler,grade_time,count(*) fans_count FROM iwide_distribute_grade_all 
			WHERE inter_id=? AND grade_table='iwide_fans_sub_log'";
		if($type == 'DAY'){
			$sql .= " AND DATE_FORMAT(grade_time,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d') ";
		}elseif($type == 'MONTH'){
			$sql .= " AND DATE_FORMAT(grade_time,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m') ";
		}elseif($type == 'YEAR'){
			$sql .= " AND DATE_FORMAT(grade_time,'%Y')=DATE_FORMAT(NOW(),'%Y') ";
		}
			$sql .= " GROUP BY saler) dsa 
		INNER JOIN (SELECT *,(SELECT @ranking := 0) b FROM iwide_hotel_staff WHERE inter_id=? AND is_distributed=1 AND `status`=2) hs 
		ON hs.inter_id=dsa.inter_id AND hs.qrcode_id=dsa.saler ORDER BY fans_count DESC) t ) a
		INNER JOIN (SELECT nickname,headimgurl,inter_id,openid FROM iwide_fans WHERE inter_id=?) f ON f.inter_id=a.inter_id AND a.openid=f.openid AND a.saler=? limit 1";
		
		$param = array($inter_id,$inter_id,$inter_id,$saler);
		return $this->db->query($sql,$param)->row_array();
	}
	function get_single_user_ranking($inter_id,$type='MONTH',$saler){
		
		$sql = 'SELECT sl.*,f.nickname,f.headimgurl,f.openid FROM (SELECT s.*,hs.hotel_id,hs.`name`,hs.`cellphone`,hs.openid,hs.hotel_name FROM (SELECT a.`saler`,a.total_amount,a.inter_id,a.order_amount,a.grade_amount,a.nums,@ranking:= @ranking + 1 rank
				FROM (SELECT SUM(grade_total) total_amount,COUNT(*) nums,dga.saler,dga.inter_id,dga.order_amount,dga.grade_amount
				FROM iwide_distribute_grade_all dga WHERE inter_id=?';
		if($type == 'DAY'){
			$sql .= " AND DATE_FORMAT(dga.grade_time,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d')";
		}elseif($type == 'MONTH'){
			$sql .= " AND DATE_FORMAT(dga.grade_time,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')";
		}elseif($type == 'YEAR'){
			$sql .= " AND DATE_FORMAT(dga.grade_time,'%Y')=DATE_FORMAT(NOW(),'%Y')";
		}
		$sql .= ' GROUP BY dga.saler) a, (SELECT @ranking := 0) b ORDER BY a.`total_amount` DESC) s
				INNER JOIN (SELECT * FROM iwide_hotel_staff WHERE inter_id=? AND is_distributed=1 AND `status`=2) hs ON s.saler=hs.qrcode_id AND hs.inter_id=s.inter_id WHERE s.inter_id=? ORDER BY s.rank ASC';
		$param = array($inter_id,$inter_id,$inter_id);
		$sql .= ') sl LEFT JOIN (SELECT * FROM iwide_fans WHERE inter_id=?) f ON sl.inter_id=f.inter_id AND sl.openid=f.openid WHERE saler=?';
		array_push($param,$inter_id);
		array_push($param,$saler);
		return $this->db->query($sql,$param);
		
	}
	function get_qr_code($inter_id,$intro,$keyword,$name,$id=NULL) {
		$this->load->model ( 'wx/access_token_model' );
		if(is_null($id)){
			$sql = "SELECT MAX(id) id FROM ".$this->db->dbprefix('qrcode')." WHERE inter_id='".$inter_id."'";
			$max_query = $this->db->query($sql)->row_array();
			$id = $max_query['id'] + 1;
		}
		$this->load->helper ( 'common' );
		$access_token = $this->access_token_model->get_access_token ( $inter_id );
		$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
		$qrcode = '{"action_name": "QR_LIMIT_SCENE","action_info": {"scene": {"scene_id": ' . $id . '}}';
		$output = doCurlPostRequest ( $url, $qrcode );
		$jsoninfo = json_decode ( $output, true );
		if(isset($jsoninfo['errcode']) && ($jsoninfo['errcode'] == '40001' || $jsoninfo['errcode'] == '42001')){
			$access_token = $this->access_token_model->reflash_access_token ( $inter_id );
			$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
			$qrcode = '{"action_name": "QR_LIMIT_SCENE","action_info": {"scene": {"scene_id": ' . $id . '}}';
		}
		$output = doCurlPostRequest ( $url, $qrcode );
		$jsoninfo = json_decode ( $output, true );
		if (isset ( $jsoninfo ['url'] )){
			$this->db->insert('qrcode',array('id'=>$id,'intro'=>$intro,'keyword'=>$keyword,'name'=>$name,'inter_id'=>$inter_id,'url'=>'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $jsoninfo ['ticket'],'create_date'=>date('Y-m-d H:i:s')));
			return $id;
		}else
			return $jsoninfo;
	}
	private function get_qr_code_up($id,$inter_id) {
		$this->load->model ( 'wx/access_token_model' );
			$access_token = $this->access_token_model->get_access_token ( $inter_id );
			$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
			// 临时码
			// $qrcode = '{"expire_seconds": 1800,"action_name": "QR_SCENE","action_info": {"scene": {"scene_id": '.$ticket_num.'}}}';
			// 永久码
			$qrcode = '{"action_name": "QR_LIMIT_SCENE","action_info": {"scene": {"scene_id": ' . $id . '}}';
			$this->load->helper ( 'common' );
			$output = doCurlPostRequest ( $url, $qrcode );
			$jsoninfo = json_decode ( $output, true );
			if(isset($jsoninfo['errcode']) && ($jsoninfo['errcode'] == '40001' || $jsoninfo['errcode'] == '42001')){
				$access_token = $this->access_token_model->reflash_access_token ( $inter_id );
				$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
				$qrcode = '{"action_name": "QR_LIMIT_SCENE","action_info": {"scene": {"scene_id": ' . $id . '}}';
			}
			$output = doCurlPostRequest ( $url, $qrcode );
			$jsoninfo = json_decode ( $output, true );
			if (isset ( $jsoninfo ['url'] ))
				return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $jsoninfo ['ticket'];
				else
					return $jsoninfo;
	}
	/**
	 * 更新参数二维码
	 */
	function update_qrcode($qrcode,$inter_id){
		$qrcode_url = $this->get_qr_code_up($qrcode,$inter_id );
		$this->db->where(array('id'=>$qrcode,'inter_id'=>$inter_id));
		$this->db->update('qrcode',array('url'=>$qrcode_url));
		return $qrcode_url;
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
			$where= array( $pk=> $this->_data[$pk] );
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
	 * 批量通过审核未通过人员
	 * 
	 * @param unknown $inter_id
	 * @return string[]|number[]
	 */
	function batch_auth($inter_id){
		$this->db->where(array('inter_id'=>$inter_id,'status'=>0));
		$query = $this->db->get('hotel_staff');
		$res = $query->result_array();
		if($query->num_rows() > 0){
			$success_count = 0;
			foreach ($res as $saler){
				if(empty($saler['qrcode_id']) && $saler['status'] == 0){
					$qrcode_id = $this->get_qr_code($saler['inter_id'],$saler['name'],'','');
					$this->save_staff_to_saler($saler['inter_id'],$qrcode_id);
					$success_count ++;
				}
			}
			$keys = array_column($res, 'id');
			$this->db->where_in('id',$keys);
			$this->db->where(array('inter_id'=>$inter_id));
			$this->db->update('hotel_staff',array('status'=>2));
			return array('errmsg'=>'ok','success'=>$success_count);
		}else{
			return array('errmsg'=>'ok','success'=>0);
		}
	}
	
	private function redis(){
		//120.27.132.97
		//30.iwide.cn
		$redis = new Redis();
		$redis->connect('120.27.132.97', 16379,3);//允许最大3秒的连接超时时间
		return $redis;
	}
	
	function my_serialize( $obj )
	{
		return base64_encode(gzcompress(serialize($obj)));
	}
	
	//反序列化
	function my_unserialize($txt)
	{
		return unserialize(gzuncompress(base64_decode($txt)));
	}
}
