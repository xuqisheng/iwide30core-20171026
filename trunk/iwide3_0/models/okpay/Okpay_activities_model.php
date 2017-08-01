<?php
class Okpay_activities_model extends MY_Model{
    function __construct() {
        parent::__construct ();
    }

    const TAB_OKPAY_ACTIVITIES = 'okpay_activities';
    public function get_resource_name()
    {
    	return 'okpay_activities_model';
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
    	return 'okpay_activities';
    }
    
    public function table_primary_key()
    {
    	return 'id';
    }
    
    public function attribute_labels()
    {
    	return array(
    			'id'=> '编号',
    			'hotel_id'=> '酒店',
    			'inter_id'=> '公众号',
    			'type_id' => '场景',
    			'title'=> '活动标题',
    			'isfor'=> '是否每满减',
    			'isfor_money'=> '满减金额',
    			'discount_amount'=> '减免金额',
    			'begin_time'=> '开始时间',
    			'end_time'=> '结束时间',
    			'create_time'=> '创建时间',
    			'update_time'=> '更新时间',
    			'status'=> '状态',
				'cut_config' => '随机减配置',
                'no_exec_day' => '不执行日',
                'gift_limit' => '次数限制'
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
    			'hotel_id',
    			'inter_id',
    			'type_id',
    			'title',
    			'isfor',
    			'isfor_money',
    			'discount_amount',
    			'begin_time',
    			'end_time',
    			'create_time',
    			'update_time',
    			'status',
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
    	//type: numberbox数字框|combobox下拉框|text不写时默认|datebox
    	$base_util= EA_base::inst();
    	$modules= config_item('admin_panels')? config_item('admin_panels'): array();
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
					'hotel_id' => $this->_admin_hotels,
				'inter_id' => $this->_admin_inter_id//公众号下的酒店 只拿hotelid不准确
			);
		else
			$filterH = array ();
		
		if ($publics && is_array ( $filterH )) {
			$this->load->model ( 'hotel/hotel_model' );
			$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
			$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
			/*$hotels = $hotels + array (
					'0' => '-不限定-' 
			);*/
		}
		
		
		//根据$hotels 获取第一家酒店的场景
		$typeList = array();
		if(!empty($this->_admin_inter_id) && sizeof($hotels) > 0){
			$keys = array_keys($hotels);
			
			$this->load->model('okpay/okpay_type_model');
			$list = $this->okpay_type_model->get_hotel_okpay_type_list($this->_admin_inter_id,$keys[0]);
			
			foreach($list as $key=>$val){
				$typeList[$val['id']] = $val['name'];
			}
		}

    	return array(
    			'id' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '5%',
    					//'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					//'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    			),
    			'hotel_id' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '5%',
    					//'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					//'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
    					'select'=>$hotels
    			),
    			'inter_id' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '5%',
    					//'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
    					'select'=>$publics
    			),
    			'type_id' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '5%',
    					//'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					//'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
    					'select'=>$typeList
    			),
    			'title' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					//'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					//'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    			),
    			'isfor' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '5%',
    					//'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					'form_tips'=> '若选择是，则每满  “满减金额” 减去 “减免金额”，例如，每满10减1， 消费100最后需要支付90即可',
    					//'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
    					'select'=>array(2=>'否',1=>'是')
    			),
    			'isfor_money' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '5%',
    					//'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					//'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    			),
    			'discount_amount' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '5%',
    					//'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					//'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    			),
    			'begin_time' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					//'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					//'form_hide'=> TRUE,
    					'function'=> 'unix_to_human|true|cn2',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    			),
    			'end_time' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					//'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					//'form_hide'=> TRUE,
    					'function'=> 'unix_to_human|true|cn2',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    			),
    			'create_time' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					//'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					'form_hide'=> TRUE,
    					'function'=> 'unix_to_human|true|cn2',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    			),
    			'update_time' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					//'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					'form_hide'=> TRUE,
    					'function'=> 'unix_to_human|true|cn2',
    					'type'=>'text',	//textarea|text|combobox|number|email|url|price
    			),
    			'status' => array(
    					'grid_ui'=> '',
    					'grid_width'=> '10%',
    					//'form_ui'=> ' disabled ',
    					//'form_default'=> '0',
    					//'form_tips'=> '注意事项',
    					//'form_hide'=> TRUE,
    					//'function'=> 'show_price_prefix|￥',
    					'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
    					'select'=>array(0=>'未启用',1=>'已启用')
    			),
			'cut_config' => array(
				'grid_ui'=> '',
				'grid_width'=> '10%',
				//'form_ui'=> ' disabled ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				'form_hide'=> TRUE,
			),
    	);
    }
    /**
     * grid表格中默认哪个字段排序，排序方向
     */
    public static function default_sort_field()
    {
    	return array('field'=>'id', 'sort'=>'desc');
    }
    
    public function delete($id,$inter_id,$hotel_id){
    	$result = $this->db->delete(self::TAB_OKPAY_ACTIVITIES, array('id' => $id,"inter_id"=>$intel_id,"hotel_id"=>$hotel_id));
    	return $result;	
    }
    
    
    public function create_okpay_activities($arr){
        $arr['create_time'] = time();
        $arr['update_time'] = time();
        $arr['status']		= 1;

        $this->db->insert(self::TAB_OKPAY_ACTIVITIES,$arr);
        $insert_id = $this->db->insert_id();
        if($insert_id){
            return true;
        }else{
            return false;
        }
    }
	//获取单条信息
	public function get($id = 0){
		$sql = "select * from iwide_okpay_activities where id = {$id}";
		$res = $this->_db('iwide_r1')->query($sql);
		return $res->result_array()[0]?$res->result_array()[0]:false;
	}

    function get_okpay_activities_detail($inter_id, $hotel_id, $type_id = 0, $status = 1) {
    	
    	$this->_db('iwide_r1')->order_by(" create_time desc ");
    	
        $activity = $this->_db('iwide_r1')->get_where ( self::TAB_OKPAY_ACTIVITIES, array (
            'hotel_id' => $hotel_id,
            'inter_id' => $inter_id,
        	'begin_time <= '=> time(),
        	'end_time >= '=>time(),
			'type_id' => $type_id,
            'status' => $status
        ) )->row_array();

        return $activity;
    }
    
    /**
     * 查询使用过快乐付的酒店数
     * @return number
     */
    public function get_okpay_activities_used_hotel_count(){
    	$sql = "select count(tp.hotel) as cnt from (select count(hotel_id) as hotel from iwide_okpay_activities as a group by a.hotel_id) as tp ";
    	return $this->_db('iwide_r1')->query($sql)->result();
    }

	//获取优惠信息列表
	public function get_activities_info_list($filter = array(),$limit = null,$offset = 0){
		$sql = "select * from iwide_okpay_activities where 1=1 ";
		if(isset($filter['inter_id'])){
			$sql .= " and inter_id = '{$filter['inter_id']}'";
		}
		if(isset($filter['id']) && !empty($filter['id'])){
			$sql .= " and id = " . intval($filter['id']);
		}
		if(isset($filter['begin_time']) && !empty($filter['begin_time'])){
			$sql .= " and begin_time >= " . strtotime($filter['begin_time']);
		}
		if(isset($filter['end_time']) && !empty($filter['end_time'])){
			$end = strtotime($filter['end_time']." 23:59:59");
			$sql .= " and end_time < " . $end;
		}
		if(isset($filter['status']) && $filter['status'] >= 0){
			$sql .= " and status = " . $filter['status'];
		}
		if(isset($filter['isfor']) && $filter['isfor'] > 0){
			$sql .= " and isfor = " . $filter['isfor'];
		}
		if(isset($filter['hotel_id']) && $filter['hotel_id'] > 0){
			$sql .= " and hotel_id = " . $filter['hotel_id'];
		}
		if(isset($filter['type_id']) && $filter['type_id'] > 0){
			$sql .= " and type_id = " . $filter['type_id'];
		}
		$sql .= ' order by id desc';
		$argvs = array();
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = $limit;
		}

		$query = $this->_db('iwide_r1')->query($sql,$argvs);

		return $query->result_array();
	}
	//获取优惠信息数量
	public function get_activities_info_count($filter = array(),$limit = null,$offset = 0){
		$sql = "select count(*) as cc from iwide_okpay_activities where 1=1 ";
		if(isset($filter['inter_id'])){
			$sql .= " and inter_id = '{$filter['inter_id']}'";
		}
		if(isset($filter['id']) && !empty($filter['id'])){
			$sql .= " and id = " . intval($filter['id']);
		}
		if(isset($filter['begin_time']) && !empty($filter['begin_time'])){
			$sql .= " and begin_time >= " . strtotime($filter['begin_time']);
		}
		if(isset($filter['end_time']) && !empty($filter['end_time'])){
			$end = strtotime($filter['end_time']." 23:59:59");
			$sql .= " and end_time < " . $end;
		}
		if(isset($filter['status']) && $filter['status'] >= 0){
			$sql .= " and status = " . $filter['status'];
		}
		if(isset($filter['isfor']) && $filter['isfor'] > 0){
			$sql .= " and isfor = " . $filter['isfor'];
		}
		if(isset($filter['hotel_id']) && $filter['hotel_id'] > 0){
			$sql .= " and hotel_id = " . $filter['hotel_id'];
		}
		if(isset($filter['type_id']) && $filter['type_id'] > 0){
			$sql .= " and type_id = " . $filter['type_id'];
		}
		$sql .= ' order by id desc';
		$argvs = array();
		/*if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = $limit;
		}*/

		$query = $this->_db('iwide_r1')->query($sql,$argvs)->row();

		return $query->cc?$query->cc:0;
	}
	//获取所有该inter_id 的场景
	public function get_all_type_by_inter_id($inter_id = ''){
		$sql = "select * from iwide_okpay_type where inter_id = '{$inter_id}' and status = 1";
		$query = $this->_db('iwide_r1')->query($sql);
		return $query->result_array();
	}

    //查询参加的活动记录数
    public function get_act_record($inter_id = '',$act_id = 0,$openid = '',$start = 0,$end = 0){
        $sql = "select count(*) c from iwide_okpay_orders where inter_id = '{$inter_id}' and openid = '{$openid}' and activity_id = $act_id and pay_time >= {$start} and pay_time < {$end}";
        $query = $this->_db('iwide_r1')->query($sql)->row();
        return empty($query->c)?0:$query->c;
    }


}
