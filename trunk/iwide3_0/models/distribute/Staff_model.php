<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_model extends MY_Model {

	public function get_resource_name()
	{
		return '员工信息';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function _shard_db($inter_id=NULL)
	{
		return $this->_db();
	}

	public function _shard_table($basename, $inter_id=NULL )
	{
		return $basename;
	}

	/**
	 * @return string the associated database table name
	 */
	public function table_name()
	{
		return 'hotel_staff';
	}

	public function department_table_name()
	{
		return 'distribute_departments';
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
			'master_dept'       => '所属部门',
			'in_date'        => '入职日期',
			'changes'        => '变动记录',
			'previous_job'   => '前份工作情况',
			'description'    => '备注信息',
			'master_dept'    => '部门',
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
			'openid'         => 'OPENID',
			'is_club'   =>'参与社群客',
			'distribute_hidden'   =>'隐藏分销中心',
			'source'=>'来源',
			'audit_time'=>'审核时间'

		);
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
			'master_dept',
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
			'is_club',
			'distribute_hidden'
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
		$publics = $hotels = $departments = array ();
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

		//---------------部门----------
		if ($this->_admin_hotels == FULL_ACCESS)
			$filterD = array ();
		else if ($this->_admin_hotels)
			$filterD = array ('hotel_id' => $this->_admin_hotels);
		else
			$filterD = array ();
		if(!isset($filterD['inter_id']))$filterD['inter_id'] = $this->session->get_admin_inter_id();

		//---------------------------Test---------------------------------------
		if($filterD['inter_id'] == 'ALL_PRIVILEGES') unset($filterD['inter_id']);
		//---------------------------end Test---------------------------------------

		if ($publics && is_array ( $filterD )) {
			$departments = $this->get_distribute_department($filterD );
			$departments = $this->hotel_model->array_to_hash ( $departments, 'dept_name', 'id' );
		}
		//--------------end 部门---------------

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
				'type' => 'text',
//                        'select'    => $departments
			) // textarea|text|combobox|number|email|url|price
		,
			'second_dept' => array (
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
				'form_ui'=> ' disabled ',
				// 'form_default'=> '0',
				// 'form_tips'=> '注意事项',
//						 'form_hide'=> TRUE,
				// 'function'=> 'show_price_prefix|￥',
				'type'=>'combobox',
				'select'=> array('1'=>'是','2'=>'否'),
			) // textarea|text|combobox|number|email|url|price
		,
			'is_club' => array (
				'grid_ui' => '',
				'grid_width' => '10%',
//                 'form_ui'=> ' disabled ',
//                 'form_default'=> '0',
				// 'form_tips'=> '注意事项',
				// 'form_hide'=> TRUE,
				// 'function'=> 'show_price_prefix|￥',
				'type'=>'combobox',
				'select'=> array('0'=>'否','1'=>'是'),
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
		,
			'distribute_hidden' => array (
				'grid_ui' => '',
				'grid_width' => '10%',
//                 'form_ui'=> ' disabled ',
//                 'form_default'=> '0',
				// 'form_tips'=> '注意事项',
				// 'form_hide'=> TRUE,
				// 'function'=> 'show_price_prefix|￥',
				'type'=>'combobox',
				'select'=> array('0'=>'否','1'=>'是'),
			) // textarea|text|combobox|number|email|url|price
		,

			'source' => array (
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
			'audit_time' => array (
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
	function get_distribute_department($params = array(), $select = array(), $format = 'array'){
		$select = count ( $select ) == 0 ? '*' : implode ( ',', $select );
		$this->_db('iwide_r1')->select ( " {$select} " );
		$table = $this->department_table_name();
		$dbfields = array_values ( $fields = $this->_db('iwide_r1')->list_fields ( $table ) );
		foreach ( $params as $k => $v ) {
			// 过滤非数据库字段，以免产生sql报错
			if (in_array ( $k, $dbfields ) && is_array ( $v )) {
				$this->_db('iwide_r1')->where_in ( $k, $v );
			} else if (in_array ( $k, $dbfields )) {
				$this->_db('iwide_r1')->where ( $k, $v );
			}
		}
		$result = $this->_db('iwide_r1')->get ( $table );
		if ($format == 'object')
			return $result->result ();
		else
			return $result->result_array ();
	}

	function saler_exist($openid,$inter_id){
		$this->_db('iwide_r1')->where(array('inter_id'=>$inter_id,'openid'=>$openid));
		return $this->_db('iwide_r1')->get('hotel_staff')->num_rows() > 0;
	}
	function saler_info($openid,$inter_id,$get_ext=TRUE){
// 		$sql = "SELECT s.*,q.url FROM (SELECT * FROM iwide_hotel_staff  WHERE inter_id=? AND openid=?) s
// 				LEFT JOIN (SELECT * FROM iwide_qrcode WHERE inter_id=?) q ON s.inter_id=q.inter_id AND s.qrcode_id=q.id";
		$sql = "SELECT s.*,q.url FROM iwide_hotel_staff s LEFT JOIN iwide_qrcode q ON s.inter_id=q.inter_id AND s.qrcode_id=q.id WHERE s.openid=? AND s.inter_id=? LIMIT 1";
		$rs = $this->_db('iwide_r1')->query($sql,array($openid,$inter_id));
		if($rs->num_rows() > 0){
			$bs_info = $rs->row_array();
			if ($get_ext){
    			$this->_db('iwide_r1')->where(array('inter_id'=>$inter_id,'openid'=>$openid));
				$bs_info['ext'] = $this->_db('iwide_r1')->get('fans')->row_array();
			}
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
		$this->_db('iwide_r1')->where(array('openid'=>$openid,'inter_id'=>$inter_id,'status'=>2));
		return $this->_db('iwide_r1')->get('hotel_staff')->num_rows() > 0;
	}

	function get_my_info($openid,$inter_id){
		// 		$sql = 'SELECT count(DISTINCT openid) fans_count FROM (SELECT * FROM '.$this->db->dbprefix('fans_sub_log').' WHERE `event`=2 AND inter_id=? GROUP BY openid) ds WHERE `source`=?';
		// 		$sql = 'SELECT COUNT(*) fans_count FROM (SELECT * FROM iwide_fans_sub_log WHERE `event`=2 AND inter_id=? GROUP BY openid) a WHERE a.source=?';
		$sql = 'SELECT COUNT(*) fans_count FROM iwide_fans_subs WHERE `event`=2 AND inter_id=? AND source=?';
// 		$sql = "SELECT COUNT(*) fans_count FROM `iwide_distribute_grade_all` WHERE `saler` = ? AND `grade_table` LIKE 'iwide_fans_sub_log' AND inter_id=?";
        $saler_details = $this->saler_info($openid,$inter_id);
        $saler_details['exts'] = unserialize($saler_details['exts']);
        $join_gift = empty($saler_details['exts']) || $saler_details['exts']['join_gift'] == 1 ? 1 : 2;
        $fans_query = $this->_db('iwide_r1')->query($sql,array($inter_id,$saler_details['qrcode_id']))->row_array();
        $sql = "";
        $sql = "SELECT SUM(grade_total) total_fee FROM iwide_distribute_grade_all WHERE saler=? AND inter_id=? AND `status`=1";
        $fee_query = $this->_db('iwide_r1')->query($sql,array($saler_details['qrcode_id'],$inter_id))->row_array();
        $total_fee = 0;
        if(isset($fee_query['total_fee']))$total_fee = $fee_query['total_fee'];
        return array('name'=>$saler_details['name'],'hotel_name'=>$saler_details['hotel_name'],'total_fee'=>$total_fee,'headimgurl'=>$saler_details['ext']['headimgurl'],'url'=>$saler_details['url'],'id'=>$saler_details['qrcode_id'],'fans_count'=>$fans_query['fans_count'],'master_dept'=>$saler_details['master_dept'],'join_gift'=>$join_gift);
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
        $sql = "SELECT * FROM (SELECT `grade_openid`,`inter_id`,SUM(dg.grade_total) total_fee,SUM(dg.grade_amount) total_amount,SUM(dg.order_amount) order_amount,count(*) total FROM ".$this->_db('iwide_r1')->dbprefix("distribute_grade_all")." dg WHERE inter_id=? AND saler=? GROUP BY grade_openid) g RIGHT JOIN (SELECT `nickname`,`headimgurl`,`openid`,`inter_id`,`id` fid FROM ".$this->_db('iwide_r1')->dbprefix("fans")." WHERE inter_id=? AND openid IN ('".$openids_str."')) f ON f.inter_id=g.inter_id AND f.openid=g.grade_openid";
        return $this->_db('iwide_r1')->query($sql,array($inter_id,$saler_info['qrcode_id'],$inter_id));
    }
    /**
     * 粉丝收入详细
     * @param int 粉丝openID
     * @param int 分销员ID
     * @param string 公众号标识
     */
    function get_fans_recs($fans_openid,$saler_id,$inter_id){
        $sql = 'SELECT ga.*,ge.cellphone,ge.distribute,ge.hotel_name,ge.order_id,ge.product,ge.staff_name,f.nickname,f.headimgurl,f.id fid FROM iwide_distribute_grade_all ga INNER JOIN iwide_distribute_grade_ext ge ON ga.inter_id=ge.inter_id AND ga.id=ge.grade_id AND ga.inter_id=? AND ga.saler=? AND ga.grade_openid=? LEFT JOIN iwide_fans f ON f.inter_id=ga.inter_id AND f.openid=ga.grade_openid';
        return $this->_db('iwide_r1')->query($sql,array($inter_id,$saler_id,$fans_openid));
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
// 		$sql = 'SELECT f.openid,f.nickname,f.headimgurl,f.inter_id,fl.event_time subcribe_time FROM (SELECT * FROM '.$this->_db('iwide_rw')->dbprefix('fans').' WHERE inter_id=?) f RIGHT JOIN (SELECT openid,event_time,inter_id FROM (SELECT * FROM '.$this->_db('iwide_rw')->dbprefix('fans_sub_log').' WHERE `event`=2 AND inter_id=? GROUP BY openid) ds WHERE `source`=? GROUP BY openid ORDER BY event_time DESC) fl ON f.openid=fl.openid AND f.inter_id=fl.inter_id';
// 		$sql = 'SELECT f.openid,f.nickname,f.headimgurl,f.inter_id,fl.event_time subcribe_time FROM (SELECT * FROM '.$this->_db('iwide_rw')->dbprefix('fans').' WHERE inter_id=?) f RIGHT JOIN (SELECT openid,event_time,inter_id FROM '.$this->_db('iwide_rw')->dbprefix('fans_sub_log').' WHERE id IN (SELECT min(id) FROM '.$this->_db('iwide_rw')->dbprefix('fans_sub_log').' WHERE `event`=2 AND inter_id=? GROUP BY openid) and `source`=?) fl ON f.openid=fl.openid AND f.inter_id=fl.inter_id';
		$sql = 'SELECT f.openid, f.nickname, f.headimgurl, f.inter_id, fl.event_time subcribe_time FROM iwide_fans AS f RIGHT JOIN iwide_fans_subs as fl ON ( f.openid   =fl.openid AND f.inter_id=fl.inter_id) WHERE f.inter_id=? AND fl.`source`=? AND fl.id';
		// 		return $this->db->query($sql,array($inter_id,$saler_id));
		return $this->_db('iwide_r1')->query($sql,array($inter_id,$saler_id));
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
// 		$sql = "SELECT sd.*,f.nickname,f.headimgurl FROM (SELECT hs.cellphone,hs.hotel_name,hs.hotel_id,hs.inter_id,hs.`name`,hs.qrcode_id saler_id,hs.openid
//                 FROM (SELECT * FROM iwide_hotel_staff WHERE inter_id=?) hs
// 								WHERE hs.openid=? AND hs.inter_id=? limit 1) sd
// 								LEFT JOIN (SELECT `nickname`,`headimgurl`,`openid`,`inter_id` FROM iwide_fans WHERE inter_id=?) f ON f.openid=sd.openid AND f.inter_id=sd.inter_id limit 1";
		$sql = "SELECT hs.cellphone, hs.hotel_name, hs.hotel_id, hs.inter_id, hs.`name`, hs.qrcode_id saler_id, hs.openid, hs.`status`, f.nickname, f.headimgurl FROM iwide_hotel_staff AS hs LEFT JOIN iwide_fans AS f ON( f.openid   = hs.openid AND f.inter_id=hs.inter_id ) WHERE hs.inter_id=? AND hs.openid=? limit 1";
		return $this->_db('iwide_r1')->query($sql,array($inter_id,$openid))->row_array();
	}
	function get_saler_details_by_salerid($inter_id,$saler){
		$sql = "SELECT sd.*,f.nickname,f.headimgurl FROM (SELECT hs.cellphone,hs.hotel_name,hs.hotel_id, hs.`status`,hs.inter_id,hs.`name`,ds.saler saler_id,ds.saler_openid openid
				FROM (SELECT * FROM iwide_hotel_staff WHERE inter_id=?) hs LEFT JOIN (SELECT * FROM iwide_distribute_salers WHERE inter_id=?) ds ON hs.qrcode_id=ds.saler AND hs.inter_id=ds.inter_id WHERE saler=? AND ds.inter_id=? limit 1) sd
				LEFT JOIN (SELECT `nickname`,`headimgurl`,`openid`,`inter_id` FROM iwide_fans WHERE inter_id=?) f ON f.openid=sd.openid AND f.inter_id=sd.inter_id limit 1";

		return $this->_db('iwide_r1')->query($sql,array($inter_id,$inter_id,$saler,$inter_id,$inter_id))->row_array();
	}
	/**
	 * @todo 根据inter_id、openid获取员工基本信息
	 * @param string $inter_id
	 * @param string $openid
	 * return saler row
	 */
	function get_my_base_info_openid($inter_id,$openid){
		$this->_db('iwide_r1')->where(array('inter_id'=>$inter_id,'openid'=>$openid));
		$this->_db('iwide_r1')->limit(1);
		return $this->_db('iwide_r1')->get('hotel_staff')->row_array();
	}
	/**
	 * @todo 根据inter_id,saler_id获取员工基本信息
	 * @param string $inter_id
	 * @param string $saler_id
	 * @return saler row
	 */
	function get_my_base_info_saler($inter_id,$saler_id){
		$this->_db('iwide_r1')->where(array('inter_id'=>$inter_id,'qrcode_id'=>$saler_id));
		$this->_db('iwide_r1')->limit(1);
		return $this->_db('iwide_r1')->get('hotel_staff')->row_array();
	}
	function check_saler_status($inter_id,$saler_id,$status=NULL){
		$this->_db('iwide_r1')->where(array('inter_id'=>$inter_id,'qrcode_id'=>$saler_id));
		$this->_db('iwide_r1')->limit(1);
		if (!is_null($status)){
			switch ($status){
				case 'valid_saler':
					$this->_db('iwide_r1')->where(array('status'=>2,'is_distributed'=>1));
					break;
				default:
					break;
			}
		}
		return $this->_db('iwide_r1')->get('hotel_staff')->row_array();
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
		$data['is_distributed'] = 1;
// 		var_dump($this->input->post());exit;
		$id = $this->input->post('id');
		if(!empty($id)){
			$this->_db('iwide_rw')->where(array('id'=>$this->input->post('id'),'inter_id'=>$this->session->userdata('inter_id')));
			if($this->_db('iwide_rw')->update('hotel_staff',$data) > 0){
				return $this->input->post('id');
			}else{
				return -1;
			}
		}else{
			//Prevent double insertion
			$this->_db('iwide_r1')->where(array('inter_id'=>$this->session->userdata('inter_id'),'openid'=>$data['openid']));
			$this->_db('iwide_r1')->limit(1);
			$query = $this->_db('iwide_r1')->get('hotel_staff')->num_rows();
			if($query < 1){
				if($this->_db('iwide_rw')->insert('hotel_staff',$data) > 0){
					$this->load->model('plugins/Template_msg_model');
					$arr = array('inter_id'=>$data['inter_id'],'openid'=>$data['openid'],'nickname'=>$data['name'],'audit_time'=>date('Y-m-d H:i:s'));
					$this->Template_msg_model->send_staff_status_msg($arr,'staff_status_new');
					return $this->_db('iwide_rw')->insert_id();
				}else{
					return -1;
				}
			}else{
				return -1;
			}
		}
	}
	function save_staff_to_saler($inter_id,$saler){
		$sql = 'SELECT COUNT(*) nums FROM iwide_distribute_salers WHERE inter_id=? AND saler=? LIMIT 1';
		$count_query = $this->_db('iwide_r1')->query($sql,array($inter_id,$saler))->row_array();
		if($count_query['nums'] < 1){
			$sql = 'INSERT INTO iwide_distribute_salers (saler,saler_openid,inter_id,hotel_id,`status`,create_time,`level`) SELECT qrcode_id,openid,inter_id,hotel_id,1,NOW(),1 FROM iwide_hotel_staff WHERE qrcode_id=? AND inter_id=? LIMIT 1';
			return $this->_db('iwide_rw')->query($sql,array($saler,$inter_id)) > 0;

		}
		return true;
	}
	function get_room_rec_info($openid,$inter_id){
		$my_info = $this->saler_info($openid, $inter_id);
		$sql = 'SELECT bs.*,f.nickname,f.headimgurl FROM (SELECT dga.*,hoi.orderid,hoi.room_id,hoi.iprice,hoi.startdate,hoi.enddate,hoi.allprice,hoi.roomname FROM
				(SELECT * from iwide_distribute_grade_all WHERE inter_id=? AND saler=?) dga
				LEFT JOIN (SELECT * FROM iwide_hotel_order_items WHERE inter_id=?) hoi ON hoi.id=dga.grade_id) bs LEFT JOIN (SELECT * FROM iwide_fans WHERE inter_id=?) f ON bs.grade_openid=f.openid';
		return $this->_db('iwide_r1')->query($sql,array($inter_id,$my_info['qrcode_id'],$inter_id,$inter_id));
	}
	function get_fans_details($fans_id,$inter_id){
		$this->_db('iwide_r1')->where(array('id'=>$fans_id,'inter_id'=>$inter_id));
		$this->_db('iwide_r1')->select(array('id','headimgurl','nickname','openid'));
		$this->_db('iwide_r1')->limit(1);
		$fans_details = $this->_db('iwide_r1')->get('fans')->row_array();
		$sql = "SELECT SUM(dg.grade_total) total_fee,count(dg.grade_id) total FROM ".$this->_db('iwide_r1')->dbprefix("distribute_grade_all")." dg WHERE inter_id=? AND grade_openid = ? AND saler > 0 ";
		$fee_query = $this->_db('iwide_r1')->query($sql,array($inter_id,$fans_details['openid']))->row_array();
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
		return $this->_db('iwide_r1')->query($sql,array($inter_id,$saler,$inter_id,$inter_id,$saler));

	}
	function my_drw_logs($openid,$inter_id){
		$this->_db('iwide_r1')->where(array('saler_openid'=>$openid,'inter_id'=>$inter_id));
		$this->_db('iwide_r1')->order_by('order_time DESC');
		return $this->_db('iwide_r1')->get('distribute_withdraw');
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
		$sql = "SELECT SUM(dg.grade_total) total_fee FROM ".$this->_db('iwide_r1')->dbprefix("distribute_grade_all")." dg WHERE inter_id=? AND `status`=1 AND grade_openid in ('".$openids_str."')";
		$fee_query = $this->_db('iwide_r1')->query($sql,array($inter_id))->row_array();
		return $fee_query['total_fee'];
	}
	/**
	 * 收入排行
	 * @param Char $inter_id
	 * @param String ALL|MONTH|YEAR
	 * @param number $limit
	 */
	function get_user_ranking($inter_id,$type='MONTH',$limit=15){
		$sql = 'SELECT a.`saler`, a.total_amount, a.inter_id, a.nums , @ranking:= @ranking + 1 rank ,a.`name`,a.hotel_name,a.hotel_id,a.nickname,a.headimgurl
			FROM (SELECT dga.*,hs.`name`,hs.hotel_name,hs.hotel_id,f.nickname,f.headimgurl, (SELECT @ranking := 0 ) b
				FROM (SELECT SUM(grade_total) total_amount, COUNT(*) nums, saler,inter_id
				FROM iwide_distribute_grade_all WHERE inter_id=? AND saler >0 AND (status=1 OR status=2 OR status=9)';
		if($type == 'DAY'){
			//$sql .= " AND DATE_FORMAT(grade_time,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d')";
			$sql .= " AND grade_time >= '" . date('Y-m-d') . "' AND grade_time <= '" . date('Y-m-d 23:59:59') ."'";
		}elseif($type == 'MONTH'){
			//$sql .= " AND DATE_FORMAT(grade_time,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')";
			$sql .= " AND grade_time >= '" . date('Y-m-01') . "' AND grade_time <= '" . date('Y-m-d 23:59:59') ."'";
		}elseif($type == 'YEAR'){
			//$sql .= " AND DATE_FORMAT(grade_time,'%Y')=DATE_FORMAT(NOW(),'%Y')";
			$sql .= " AND grade_time >= '" . date('Y-01-01') . "' AND grade_time <= '" . date('Y-m-d 23:59:59') ."'";
		}
		$sql .= " GROUP BY saler ORDER BY total_amount) dga
				RIGHT JOIN iwide_hotel_staff AS hs ON dga.saler =hs.qrcode_id AND hs.inter_id =dga.inter_id
				LEFT JOIN iwide_fans f ON f.openid=hs.openid AND f.inter_id=hs.inter_id
				WHERE  hs.inter_id=? AND hs.is_distributed=1 AND hs.`status` =2 AND hs.openid<>'' GROUP BY hs.qrcode_id ORDER BY total_amount DESC LIMIT ? ) a ";
		$param = array($inter_id,$inter_id,$limit);

		return $this->_db('iwide_r1')->query($sql,$param);
	}
	/**
	 * 收入排行 - 我的排名
	 * @param unknown $inter_id
	 * @param string $type
	 * @param unknown $saler
	 */
	function get_single_user_ranking($inter_id,$type='MONTH',$saler){
		$sql = 'SELECT * FROM (SELECT a.`saler`, a.total_amount, a.inter_id, a.nums , @ranking:= @ranking + 1 rank ,a.`name`,a.hotel_name,a.hotel_id,a.nickname,a.headimgurl,a.qrcode_id
			FROM (SELECT dga.*,hs.`name`,hs.hotel_name,hs.hotel_id,f.nickname,f.headimgurl,hs.qrcode_id, (SELECT @ranking := 0 ) b
				FROM (SELECT SUM(grade_total) total_amount, COUNT(*) nums, saler,inter_id
				FROM iwide_distribute_grade_all WHERE inter_id=? AND saler >0 AND (status=1 OR status=2 OR status=9)';
		if($type == 'DAY'){
			//$sql .= " AND DATE_FORMAT(grade_time,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d')";
			$sql .= " AND grade_time >= '" . date('Y-m-d') . "' AND grade_time <= '" . date('Y-m-d 23:59:59') ."'";
		}elseif($type == 'MONTH'){
			//$sql .= " AND DATE_FORMAT(grade_time,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')";
			$sql .= " AND grade_time >= '" . date('Y-m-01') . "' AND grade_time <= '" . date('Y-m-d 23:59:59') ."'";
		}elseif($type == 'YEAR'){
			//$sql .= " AND DATE_FORMAT(grade_time,'%Y')=DATE_FORMAT(NOW(),'%Y')";
			$sql .= " AND grade_time >= '" . date('Y-01-01') . "' AND grade_time <= '" . date('Y-m-d 23:59:59') ."'";
		}
		$sql .= " GROUP BY saler ORDER BY total_amount DESC) dga
				RIGHT JOIN iwide_hotel_staff AS hs ON dga.saler =hs.qrcode_id AND hs.inter_id =dga.inter_id
				LEFT JOIN iwide_fans f ON f.openid=hs.openid AND f.inter_id=hs.inter_id
				WHERE  hs.inter_id=? AND hs.is_distributed=1 AND hs.`status` =2 AND hs.openid<>'' GROUP BY hs.qrcode_id ORDER BY total_amount DESC) a ) aa WHERE aa.qrcode_id=?";
		$param = array($inter_id,$inter_id,$saler);

		return $this->_db('iwide_r1')->query($sql,$param);
	}
	/**
	 * 粉丝排名
	 * @param unknown $inter_id
	 * @param string $type
	 * @param number $limit
	 */
	function get_fans_ranking($inter_id,$type='MONTH',$limit=15){

		$sql = "SELECT a.* FROM
		(SELECT *,@ranking:= @ranking + 1 rank  FROM (SELECT dsa.*,hs.`name`,hs.business,hs.master_dept,hs.hotel_id,hs.cellphone,hs.hotel_name,hs.`status`,hs.is_distributed,hs.openid,f.nickname,f.headimgurl
			FROM (select inter_id,source,event_time,count(*) fans_count from iwide_fans_subs where inter_id=? and source > 0";//改成读fans_subs 表 situguanchen 20161208
		if($type == 'DAY'){
			//$sql .= " AND DATE_FORMAT(event_time,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d') ";
			$sql .= " AND event_time >= '" . date('Y-m-d') . "' AND event_time <= '".date('Y-m-d 23:59:59') ."'";
		}elseif($type == 'MONTH'){
			//$sql .= " AND DATE_FORMAT(event_time,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m') ";
			$sql .= " AND event_time >= '" . date('Y-m-01') . "' AND event_time <= '".date('Y-m-d 23:59:59') ."'";
		}elseif($type == 'YEAR'){
			//$sql .= " AND DATE_FORMAT(event_time,'%Y')=DATE_FORMAT(NOW(),'%Y') ";
			$sql .= " AND event_time >= '" . date('Y-01-01') . "' AND event_time <= '".date('Y-m-d 23:59:59') ."'";
		}
		$sql .= " GROUP BY source) dsa
		RIGHT JOIN (SELECT *,(SELECT @ranking := 0) b FROM iwide_hotel_staff WHERE inter_id=? AND is_distributed=1 AND `status`=2 AND openid<>'') hs
		ON hs.inter_id=dsa.inter_id AND hs.qrcode_id=dsa.source LEFT JOIN iwide_fans f ON f.inter_id=hs.inter_id AND hs.openid=f.openid ORDER BY fans_count DESC";
		$param = array($inter_id,$inter_id);
		if(!is_null($limit)){
			$sql .= " LIMIT ?";
			array_push($param,$limit);
		}

		$sql .= ") t ) a ORDER BY rank ASC";
		return $this->_db('iwide_r1')->query($sql,$param);
	}
	/**
	 * 粉丝排名 - 我的排名
	 * @param unknown $inter_id
	 * @param string $type
	 * @param unknown $saler
	 */
	function get_user_rank($inter_id,$type='MONTH',$saler){
		$sql = "SELECT a.* FROM
		(SELECT *,@ranking:= @ranking + 1 rank  FROM (SELECT dsa.*,hs.`name`,hs.business,hs.master_dept,hs.hotel_id,hs.cellphone,hs.hotel_name,hs.`status`,hs.is_distributed,hs.openid,hs.qrcode_id,f.nickname,f.headimgurl
			FROM (select inter_id,source,event_time,count(*) fans_count from iwide_fans_subs where inter_id=? and source > 0";//改成读fans_subs 表 situguanchen 20161208
		if($type == 'DAY'){
			//$sql .= " AND DATE_FORMAT(event_time,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d') ";
			$sql .= " AND event_time >= '" . date('Y-m-d') . "' AND event_time <= '".date('Y-m-d 23:59:59') ."'";
		}elseif($type == 'MONTH'){
			//$sql .= " AND DATE_FORMAT(event_time,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m') ";
			$sql .= " AND event_time >= '" . date('Y-m-01') . "' AND event_time <= '".date('Y-m-d 23:59:59') ."'";
		}elseif($type == 'YEAR'){
			//$sql .= " AND DATE_FORMAT(event_time,'%Y')=DATE_FORMAT(NOW(),'%Y') ";
			$sql .= " AND event_time >= '" . date('Y-01-01') . "' AND event_time <= '".date('Y-m-d 23:59:59') ."'";
		}
		$sql .= " GROUP BY source) dsa
		RIGHT JOIN (SELECT *,(SELECT @ranking := 0) b FROM iwide_hotel_staff WHERE inter_id=? AND is_distributed=1 AND `status`=2 AND openid<>'') hs
		ON hs.inter_id=dsa.inter_id AND hs.qrcode_id=dsa.source LEFT JOIN iwide_fans f ON f.inter_id=hs.inter_id AND hs.openid=f.openid ORDER BY fans_count DESC) t ) a WHERE a.qrcode_id=? limit 1";

		$param = array($inter_id,$inter_id,$saler);
		return $this->_db('iwide_r1')->query($sql,$param)->row_array();
	}
	function get_qr_code($inter_id,$intro,$keyword,$name,$id=NULL) {
		$this->load->model ( 'wx/access_token_model' );
		if(is_null($id)){
			$sql = "SELECT MAX(id) id FROM ".$this->_db('iwide_r1')->dbprefix('qrcode')." WHERE inter_id='".$inter_id."'";
			$max_query = $this->_db('iwide_r1')->query($sql)->row_array();
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
			$this->db->insert('qrcode',array('id'=>$id,'intro'=>$intro,'keyword'=>$keyword,'name'=>$name,'inter_id'=>$inter_id,'url'=>'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($jsoninfo ['ticket']),'create_date'=>date('Y-m-d H:i:s')));
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
			return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($jsoninfo ['ticket']);
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
			$fields= $this->_db('iwide_r1')->list_fields($table);
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
					if(in_array($k,$fields)) {
						if($k == 'exts')
							$this->_data[$k]= serialize($v);
						else							
							$this->_data[$k]= $v;
					}
				}
			}
			$where= array( $pk=> $this->_data[$pk],'inter_id'=>$this->_data['inter_id'] );
			$this->_db()->where($where);
			$result= $this->_db()->update($table, $this->_data);
			return $result;

		} else {
			if($data){
				foreach ($data as $k=>$v){
					if(in_array($k,$fields)) {
						if($k == 'exts')
							$this->_data[$k]= serialize($v);
						else
							$this->_data[$k]= $v;
					}
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
		$this->_db('iwide_r1')->where(array('inter_id'=>$inter_id,'status'=>1));
		$query = $this->_db('iwide_r1')->get('hotel_staff');
		$res = $query->result_array();
		if($query->num_rows() > 0){
			$success_count = 0;
			foreach ($res as $saler){
				if(empty($saler['qrcode_id']) && $saler['status'] == 1){
					$qrcode_id = $this->get_qr_code($saler['inter_id'],$saler['name'],'','');
					$this->save_staff_to_saler($saler['inter_id'],$qrcode_id);
					$this->db->where(array('inter_id'=>$inter_id,'id'=>$saler['id']));
					$this->db->update('hotel_staff',array('status'=>2,'is_distributed'=>1,'qrcode_id'=>$qrcode_id));
				}
				$success_count ++;
			}
			$keys = array_column($res, 'id');
			return array('errmsg'=>'ok','success'=>$success_count);
		}else{
			return array('errmsg'=>'ok','success'=>0);
		}
	}
	/**
	 * 获取所有产生交易的粉丝信息
	 * @param unknown $openid
	 * @param unknown $inter_id
	 */
	function i_get_room_rec_info($openid,$inter_id){
		$key = $inter_id."_get_room_rec_info_".$openid;
		$obj = $this->redis->get($key);
		if(empty($obj)){
			unset($obj);

			$my_info = $this->saler_info($openid, $inter_id);
			$sql = 'SELECT bs.*,f.nickname,f.headimgurl FROM (SELECT dga.*,hoi.orderid,hoi.room_id,hoi.iprice,hoi.startdate,hoi.enddate,hoi.allprice,hoi.roomname FROM
				(SELECT * from iwide_distribute_grade_all WHERE inter_id=? AND saler=? AND (status=1 OR status=2)) dga
				LEFT JOIN (SELECT * FROM iwide_hotel_order_items WHERE inter_id=?) hoi ON hoi.id=dga.grade_id) bs LEFT JOIN (SELECT * FROM iwide_fans WHERE inter_id=?) f ON bs.grade_openid=f.openid';

			$obj = $this->_db('iwide_r1')->query($sql,array($inter_id,$my_info['qrcode_id'],$inter_id,$inter_id))->result_array();
			$str = $this->my_serialize($obj);
			$this->redis->set($key,$str,300);

			$this->add_redis_log("mysql",$key);

			return $obj;
		}else{

			$this->add_redis_log("redis",$key);

			$obj_result = $this->my_unserialize($obj);
			return $obj_result;
		}

	}


	/**
	 * 根据分销员获取所有订房分销收入
	 * @param unknown $saler
	 * @param unknown $inter_id
	 */
	function i_get_all_room_rec_info_by_salerid($saler,$inter_id){
		$key = $inter_id."_get_all_room_rec_info_by_salerid_".$saler;
		$obj = $this->redis->get($key);
		if(empty($obj)){
			unset($obj);

			$sql = "SELECT bs.*,f.nickname,f.headimgurl FROM (SELECT dge.hotel_name name,dg.grade_openid openid,dg.inter_id,dg.grade_total,dg.`status` gstatus,dg.grade_time,dg.grade_table,dg.grade_rate_type,dg.order_amount,dge.order_id,dg.order_amount iprice,dge.product roomname,dg.grade_time order_time FROM
                (SELECT * FROM iwide_distribute_grade_all WHERE inter_id=? AND saler=? AND grade_table<>'iwide_fans_sub_log' AND (status=1 OR status=2)) dg
								LEFT JOIN iwide_distribute_grade_ext dge ON dg.id=dge.grade_id AND dg.inter_id=dge.inter_id ) bs LEFT JOIN (SELECT headimgurl,nickname,inter_id,openid FROM iwide_fans WHERE inter_id=?) f ON f.inter_id=bs.inter_id AND bs.openid=f.openid
                UNION
                SELECT  dge.hotel_name name,dg.grade_openid openid,dg.inter_id,dg.grade_total,dg.`status` gstatus,dg.grade_time,dg.grade_table,dg.grade_rate_type,dg.order_amount allprice,dge.order_id orderid,dg.order_amount iprice,'关注',dg.grade_time order_time,f.nickname,f.headimgurl FROM (SELECT * FROM iwide_distribute_grade_all WHERE inter_id=? AND saler=? AND grade_table='iwide_fans_sub_log') dg
LEFT JOIN iwide_distribute_grade_ext dge ON dg.grade_id=dge.grade_id AND dg.inter_id=dge.inter_id
LEFT JOIN iwide_fans f ON f.openid=dg.grade_openid AND f.inter_id=dg.inter_id ORDER BY grade_time DESC";
			$obj = $this->_db('iwide_r1')->query($sql,array($inter_id,$saler,$inter_id,$inter_id,$saler))->result_array();

			$this->add_redis_log("mysql",$key);

			$str = $this->my_serialize($obj);
			$this->redis->set($key,$str,300);

			return $obj;
		}else{
			$this->add_redis_log("redis",$key);

			$obj_result = $this->my_unserialize($obj);
			return $obj_result;
		}
	}


	/**
	 * 收入排行
	 * @param Char $inter_id
	 * @param String ALL|MONTH|YEAR
	 * @param number $limit
	 */
	function i_get_user_ranking($inter_id,$type='MONTH',$limit=15){
		$key = $inter_id."_get_user_ranking_".$type;
		$obj = $this->redis->get($key);

		if(empty($obj)){
			unset($obj);

			$sql = 'SELECT a.`saler`, a.total_amount, a.inter_id, a.nums , @ranking:= @ranking + 1 rank ,a.`name`,a.hotel_name,a.hotel_id,a.nickname,a.headimgurl
			FROM (SELECT dga.*,hs.`name`,hs.hotel_name,hs.hotel_id,f.nickname,f.headimgurl, (SELECT @ranking := 0 ) b
				FROM (SELECT SUM(grade_total) total_amount, COUNT(*) nums, saler,inter_id
				FROM iwide_distribute_grade_all WHERE inter_id=? AND saler >0 AND (status=1 OR status=2)';
		if($type == 'DAY'){
			//$sql .= " AND DATE_FORMAT(grade_time,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d')";
			$sql .= " AND grade_time >= '" . date('Y-m-d') . "' AND grade_time <= '" . date('Y-m-d 23:59:59') ."'";
		}elseif($type == 'MONTH'){
			//$sql .= " AND DATE_FORMAT(grade_time,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')";
			$sql .= " AND grade_time >= '" . date('Y-m-01') . "' AND grade_time <= '" . date('Y-m-d 23:59:59') ."'";
		}elseif($type == 'YEAR'){
			//$sql .= " AND DATE_FORMAT(grade_time,'%Y')=DATE_FORMAT(NOW(),'%Y')";
			$sql .= " AND grade_time >= '" . date('Y-01-01') . "' AND grade_time <= '" . date('Y-m-d 23:59:59') ."'";
		}
		$sql .= " GROUP BY saler ORDER BY total_amount) dga
				RIGHT JOIN iwide_hotel_staff AS hs ON dga.saler =hs.qrcode_id AND hs.inter_id =dga.inter_id
				LEFT JOIN iwide_fans f ON f.openid=hs.openid AND f.inter_id=hs.inter_id
				WHERE  hs.inter_id=? AND hs.is_distributed=1 AND hs.`status` =2 AND hs.openid<>'' GROUP BY hs.qrcode_id ORDER BY total_amount DESC LIMIT ? ) a ";
			$param = array($inter_id,$inter_id,$limit);
			$obj = $this->_db('iwide_r1')->query($sql,$param)->result_array();


			$this->add_redis_log("mysql",$key);

			$str = $this->my_serialize($obj);
			$this->redis->set($key,$str,600);

			return $obj;
		}else{
			$this->add_redis_log("redis",$key);

			$obj_result = $this->my_unserialize($obj);
			return $obj_result;
		}

	}


	/**
	 * 粉丝排行
	 * @param unknown $inter_id
	 * @param string $type
	 * @param number $limit
	 */
	function i_get_fans_ranking($inter_id,$type='MONTH',$limit=15){

		$key = $inter_id."_get_fans_ranking_".$type;
		$obj = $this->redis->get($key);
		if(empty($obj)){
			unset($obj);
		$sql = "SELECT a.* FROM
				(SELECT *,@ranking:= @ranking + 1 rank  FROM (SELECT dsa.*,hs.`name`,hs.business,hs.master_dept,hs.hotel_id,hs.cellphone,hs.hotel_name,hs.`status`,hs.is_distributed,hs.openid,f.nickname,f.headimgurl
					FROM (select inter_id,source,event_time,count(*) fans_count from iwide_fans_subs where inter_id=? and source > 0";//改成读fans_subs 表 situguanchen 20161208
				if($type == 'DAY'){
					//$sql .= " AND DATE_FORMAT(event_time,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d') ";
					$sql .= " AND event_time >= '" . date('Y-m-d') . "' AND event_time <= '".date('Y-m-d 23:59:59') ."'";
				}elseif($type == 'MONTH'){
					//$sql .= " AND DATE_FORMAT(event_time,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m') ";
					$sql .= " AND event_time >= '" . date('Y-m-01') . "' AND event_time <= '".date('Y-m-d 23:59:59') ."'";
				}elseif($type == 'YEAR'){
					//$sql .= " AND DATE_FORMAT(event_time,'%Y')=DATE_FORMAT(NOW(),'%Y') ";
					$sql .= " AND event_time >= '" . date('Y-01-01') . "' AND event_time <= '".date('Y-m-d 23:59:59') ."'";
				}
				$sql .= " GROUP BY source) dsa
				RIGHT JOIN (SELECT *,(SELECT @ranking := 0) b FROM iwide_hotel_staff WHERE inter_id=? AND is_distributed=1 AND `status`=2 AND openid<>'') hs
				ON hs.inter_id=dsa.inter_id AND hs.qrcode_id=dsa.source LEFT JOIN iwide_fans f ON f.inter_id=hs.inter_id AND hs.openid=f.openid ORDER BY fans_count DESC";
				$param = array($inter_id,$inter_id);
				if(!is_null($limit)){
					$sql .= " LIMIT ?";
					array_push($param,$limit);
				}

				$sql .= ") t ) a ORDER BY rank ASC";
			$obj = $this->_db('iwide_r1')->query($sql,$param)->result_array();

			$this->add_redis_log("mysql",$key);

			$str = $this->my_serialize($obj);
			$this->redis->set($key,$str,600);
			return $obj;
		}else{

			$this->add_redis_log("redis",$key);

			$obj_result =$this->my_unserialize($obj);
			return $obj_result;
		}
	}

	function i_get_single_user_ranking($inter_id,$type='MONTH',$saler){
		$key = $inter_id."_get_single_user_ranking_".$type."_".$saler;
		$obj = $this->redis->get($key);
		if(empty($obj)){
			unset($obj);
		$sql = 'SELECT * FROM (SELECT a.`saler`, a.total_amount, a.inter_id, a.nums , @ranking:= @ranking + 1 rank ,a.`name`,a.hotel_name,a.hotel_id,a.nickname,a.headimgurl,a.qrcode_id
					FROM (SELECT dga.*,hs.`name`,hs.hotel_name,hs.hotel_id,f.nickname,f.headimgurl,hs.qrcode_id, (SELECT @ranking := 0 ) b
						FROM (SELECT SUM(grade_total) total_amount, COUNT(*) nums, saler,inter_id
						FROM iwide_distribute_grade_all WHERE inter_id=? AND saler >0 AND (status=1 OR status=2)';
				if($type == 'DAY'){
					//$sql .= " AND DATE_FORMAT(grade_time,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d')";
					$sql .= " AND grade_time >= '" . date('Y-m-d') . "' AND grade_time <= '" . date('Y-m-d 23:59:59') ."'";
				}elseif($type == 'MONTH'){
					//$sql .= " AND DATE_FORMAT(grade_time,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')";
					$sql .= " AND grade_time >= '" . date('Y-m-01') . "' AND grade_time <= '" . date('Y-m-d 23:59:59') ."'";
				}elseif($type == 'YEAR'){
					//$sql .= " AND DATE_FORMAT(grade_time,'%Y')=DATE_FORMAT(NOW(),'%Y')";
					$sql .= " AND grade_time >= '" . date('Y-01-01') . "' AND grade_time <= '" . date('Y-m-d 23:59:59') ."'";
				}
				$sql .= " GROUP BY saler ORDER BY total_amount DESC) dga
						RIGHT JOIN iwide_hotel_staff AS hs ON dga.saler =hs.qrcode_id AND hs.inter_id =dga.inter_id
						LEFT JOIN iwide_fans f ON f.openid=hs.openid AND f.inter_id=hs.inter_id
						WHERE  hs.inter_id=? AND hs.is_distributed=1 AND hs.`status` =2 AND hs.openid<>'' GROUP BY hs.qrcode_id ORDER BY total_amount DESC) a ) aa WHERE aa.qrcode_id=?";
			$param = array($inter_id,$inter_id,$saler);
			$obj = $this->_db('iwide_r1')->query($sql,$param)->row_array();

			$this->add_redis_log("redis", $key);

			$str = $this->my_serialize($obj);
			$this->redis->set($key,$str,600);
			return $obj;
		}else{

			$this->add_redis_log("redis",$key);

			$obj_result =$this->my_unserialize($obj);
			return $obj_result;
		}
	}

	function i_get_user_rank($inter_id,$type='MONTH',$saler){

		$key = $inter_id."_get_user_rank_".$type."_".$saler;
		$obj = $this->redis->get($key);
		if(empty($obj)){
			unset($obj);

			$sql = "SELECT a.* FROM
				(SELECT *,@ranking:= @ranking + 1 rank  FROM (SELECT dsa.*,hs.`name`,hs.business,hs.master_dept,hs.hotel_id,hs.cellphone,hs.hotel_name,hs.`status`,hs.is_distributed,hs.openid,hs.qrcode_id,f.nickname,f.headimgurl
					FROM (select inter_id,source,event_time,count(*) fans_count from iwide_fans_subs where inter_id=? and source > 0";//改成读fans_subs 表 situguanchen 20161208
				if($type == 'DAY'){
					//$sql .= " AND DATE_FORMAT(event_time,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d') ";
					$sql .= " AND event_time >= '" . date('Y-m-d') . "' AND event_time <= '".date('Y-m-d 23:59:59') ."'";
				}elseif($type == 'MONTH'){
					//$sql .= " AND DATE_FORMAT(event_time,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m') ";
					$sql .= " AND event_time >= '" . date('Y-m-01') . "' AND event_time <= '".date('Y-m-d 23:59:59') ."'";
				}elseif($type == 'YEAR'){
					//$sql .= " AND DATE_FORMAT(event_time,'%Y')=DATE_FORMAT(NOW(),'%Y') ";
					$sql .= " AND event_time >= '" . date('Y-01-01') . "' AND event_time <= '".date('Y-m-d 23:59:59') ."'";
				}
				$sql .= " GROUP BY source) dsa
				RIGHT JOIN (SELECT *,(SELECT @ranking := 0) b FROM iwide_hotel_staff WHERE inter_id=? AND is_distributed=1 AND `status`=2 AND openid<>'') hs
				ON hs.inter_id=dsa.inter_id AND hs.qrcode_id=dsa.source LEFT JOIN iwide_fans f ON f.inter_id=hs.inter_id AND hs.openid=f.openid ORDER BY fans_count DESC) t ) a WHERE a.qrcode_id=? limit 1";

			$param = array($inter_id,$inter_id,$saler);
			$obj = $this->_db('iwide_r1')->query($sql,$param)->row_array();

			$this->add_redis_log("mysql", $key);

			$str = $this->my_serialize($obj);
			$this->redis->set($key,$str,600);
			return $obj;

		}else{

			$this->add_redis_log("redis",$key);

			$obj_result =$this->my_unserialize($obj);
			return $obj_result;
		}


	}
	//脚本判断执行sql
	public function get_auto_data_res($inter_id,$range='FANS',$type='MONTH'){
		if($range == 'FANS'){//粉丝排行
			$key = $inter_id."_get_all_fans_rank_".$type;
			$sql = "SELECT *,@ranking:= @ranking + 1 rank  FROM (SELECT dsa.*,hs.`name`,hs.hotel_name,hs.qrcode_id,f.headimgurl FROM (select inter_id,source,count(*) fans_count from iwide_fans_subs where inter_id=? and source > 0  GROUP BY source) dsa RIGHT JOIN (SELECT *,(SELECT @ranking := 0) b FROM iwide_hotel_staff WHERE inter_id=? AND is_distributed=1 AND `status`=2 AND openid<>'') hs
		ON hs.inter_id=dsa.inter_id AND hs.qrcode_id=dsa.source LEFT JOIN iwide_fans f ON f.inter_id=hs.inter_id AND hs.openid=f.openid ORDER BY fans_count DESC) t";
			$param = array($inter_id,$inter_id);
			$obj = $this->_db('iwide_r1')->query($sql,$param)->result_array();

			$str = $this->my_serialize($obj);
			$this->redis->set($key,$str,900);//存储15分钟
			return $obj;

		}else{
			$key = $inter_id."_get_all_incomes_rank_".$type;
			$sql = "SELECT a.`saler`, a.total_amount, a.inter_id, @ranking:= @ranking + 1 rank ,a.`name`,a.hotel_name,a.headimgurl,a.qrcode_id FROM (SELECT dga.*,hs.`name`,hs.hotel_name,hs.hotel_id,f.headimgurl,hs.qrcode_id, (SELECT @ranking := 0 ) b FROM (SELECT SUM(grade_total) total_amount, saler,inter_id FROM iwide_distribute_grade_all WHERE inter_id=? AND saler >0 AND (status=1 OR status=2) ";
			if($type == 'MONTH'){
				$sql .= " AND grade_time >= '" . date('Y-m-01') . "' AND grade_time <= '" . date('Y-m-d 23:59:59') ."'";
			}
			$sql .= " GROUP BY saler ORDER BY total_amount DESC) dga RIGHT JOIN iwide_hotel_staff AS hs ON dga.saler =hs.qrcode_id AND hs.inter_id =dga.inter_id LEFT JOIN iwide_fans f ON f.openid=hs.openid AND f.inter_id=hs.inter_id WHERE hs.inter_id=? AND hs.is_distributed=1 AND hs.`status` =2 AND hs.openid<>'' GROUP BY hs.qrcode_id ORDER BY total_amount DESC) a";
			$param = array($inter_id,$inter_id);
			$obj = $this->_db('iwide_r1')->query($sql,$param)->result_array();

			$str = $this->my_serialize($obj);
			$this->redis->set($key,$str,900);//存储15分钟
			return $obj;
		}
	}
	//获取所有粉丝排名数据
	public function i_get_all_fans_rank($inter_id,$type='MONTH',$saler){
		$key = $inter_id."_get_all_fans_rank_".$type;
		$obj = $this->redis->get($key);
		$return = array();
		if(!empty($obj)){
			$this->add_redis_log("redis",$key);

			$obj_result =$this->my_unserialize($obj);
			if(!empty($obj_result)){
				foreach($obj_result as $k=>$v){
					if(isset($v['rank']) && $v['rank'] <= 50){
						$return['limit50'][] = $v;
					}
					if(isset($v['qrcode_id']) && $v['qrcode_id'] == $saler){
						$return['mine'] = $v;
					}
				}
			}
		}
		if(empty($return)){
			$return['mine'] = $this->get_user_rank($inter_id,$type,$saler);
			$return['limit50'] = $this->get_fans_ranking($inter_id,$type,50)->result_array();
		}
		return $return ;
	}

	//获取所有收益排名数据
	public function i_get_all_incomes_rank($inter_id,$type='MONTH',$saler){
		$key = $inter_id."_get_all_incomes_rank_".$type;
		$obj = $this->redis->get($key);
		$return = array();
		if(!empty($obj)){
			$this->add_redis_log("redis",$key);

			$obj_result =$this->my_unserialize($obj);
			if(!empty($obj_result)){
				foreach($obj_result as $k=>$v){
					if(isset($v['rank']) && $v['rank'] <= 50){
						$return['limit50'][] = $v;
					}
					if(isset($v['qrcode_id']) && $v['qrcode_id'] == $saler){
						$return['mine'] = $v;
					}
				}
			}
		}
		if(empty($return)){
			$return['mine'] = $this->get_single_user_ranking($inter_id,$type,$saler)->row_array();
			$return['limit50'] = $this->get_user_ranking($inter_id,$type,50)->result_array();
		}
		return $return ;
	}

	private function redis(){
		//120.27.132.97
		//30.iwide.cn
		$redis = new Redis();
		$redis->connect('10.168.162.35', 6379,5);//允许最大3秒的连接超时时间
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

	protected $redis;
	public function __construct()
	{
		parent::__construct();
		//载入配置文件
		$config =& get_config();
		$this->redis = new Redis();
		if(isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV'] == "production"){
			$this->redis->connect($config['prod_redis_host'], $config['prod_redis_port'],$config['prod_redis_expire']);//允许最大3秒的连接超时时间
			$this->redis->select($config['prod_redis_db']);
		}else{
			$this->redis->connect($config['test_redis_host'], $config['test_redis_port'],$config['test_redis_expire']);//允许最大3秒的连接超时时间
			$this->redis->select($config['test_redis_db']);
		}
	}

	public function getcf(){
		$config =& get_config();

		return $config;
	}

	public function add_redis_log($key,$txt){
		$txt = date("y-M-d H:i:s",time())." ==> ".$txt.", 累计第：".$this->redis->incr($key);
		return $this->redis->rPush($key,$txt);
	}

	public function get_redis_log($key,$begin=0,$end=-1){
		return $this->redis->range($key,0,-1);
	}

	public function __destruct(){
		$this->redis->close();
	}

	public function delete_by_key($key){
		return $this->redis->delete($key);
	}
}
