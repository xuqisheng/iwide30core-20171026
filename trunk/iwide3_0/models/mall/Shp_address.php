<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Shp_address extends MY_Model_Mall {

	public function get_resource_name()
	{
		return 'shp_address';
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
		return 'shp_address';
	}

	public function table_primary_key()
	{
	    return 'id';
	}
	
	const STATUS_ACTIVE		= '0';
	const STATUS_UNACTIVE	= '1';

	public function attribute_labels()
	{
		return array(
			'id'=> 'Id',
			'openid'=> 'Openid',
			'hotel_id'=> '酒店ID',
			'inter_id'=> '公众号',
			'country'=> '国家',
			'province'=> '省份',
			'city'=> '城市',
			'region'=> '区',
			'address'=> '街道',
			'zip_code'=> '区号',
			'phone'=> '电话',
			'contact'=> '联系人',
			'status'=> '状态',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
	    return array(
			'id',
			'contact',
            'phone',
			'province',
			'city',
            'region',
            'address',
			'zip_code',
            //'country',
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

	    return array(
            'id' => array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'function'=> 'hide_string_prefix|6',
                'type'=>'text',	//textarea|text|combobox
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'country' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'province' => array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'city' => array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'region' => array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'address' => array(
                'grid_ui'=> '',
                //'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'zip_code' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'phone' => array(
                'grid_ui'=> '',
                'grid_width'=> '12%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'contact' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $base_util::get_status_options_(),
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
	
	/* 以上为AdminLTE 后台UI输出配置函数 */
	
	
	
	
	
	
	
	

	function create_address($inter_id,$hotel_id = null,$arr){
	    if($this->db->insert('shp_address',$arr) > 0){
	        return $this->db->insert_id();
	    }else{
	        return false;
	    }
	}
	function del_address($inter_id,$hotel_id = null,$id,$openid){
	
	}
	function update_address($arr){
	    $this->db->where(array('id'=>$arr['id'],'openid'=>$arr['openid'],'hotel_id'=>$arr['hotel_id'],'inter_id'=>$arr['inter_id']));
	    if($this->db->update('shp_address', array('country'=>$arr['country'], 'province'=>$arr['province'],'city'=>$arr['city'],'region'=>$arr['region'],'address'=>$arr['address'],'zip_code'=>$arr['zip_code'],'contact'=>$arr['contact'],'phone'=>$arr['phone'])>0)){
	        return true;
	    }else{
	        return false;
	    }
	}
	function get_address($inter_id,$hotel_id = null,$openid){
	    $this->db->where(array('inter_id'=>$inter_id,'openid'=>$openid));
	    if(!empty($hotel_id))
	        $this->db->where('hotel_id',$hotel_id);
	    return $this->db->get('shp_address')->result_array();
	}
	function get_single_address($inter_id,$hotel_id = null,$openid,$address_id){
	    $this->db->where(array('inter_id'=>$inter_id,'openid'=>$openid,'id'=>$address_id,'status'=>0));
	    if(isset($hotel_id)){
	        $this->db->where('hotel_id',$hotel_id);
	    }
	    $this->db->limit(1);
	    return $this->db->get('shp_address')->row_array();
	}
	function rand_single_address($inter_id,$hotel_id = null,$openid){
	    $sql = 'SELECT * FROM (SELECT addr_id FROM ' . $this->db->dbprefix ( 'shp_order_items' )
	    . ' WHERE openid=? AND NOT ISNULL(addr_id) ORDER BY order_time DESC LIMIT 1) aid LEFT JOIN '
	        . $this->db->dbprefix ( 'shp_address' ) . ' a ON a.id=aid.addr_id LIMIT 1';
	    $adr = $this->db->query ( $sql, array ( $openid ) )->row_array ();
	    if ($adr) {
	        return $adr;
	    } else {
	        $this->db->where ( array ( 'inter_id' => $inter_id, 'openid' => $openid, 'status' => 0 ) );
	        if (isset ( $hotel_id )) {
	            $this->db->where ( 'hotel_id', $hotel_id );
	        }
	        $this->db->limit ( 1 );
	        return $this->db->get ( 'shp_address' )->row_array ();
	    }
	}
	function get_openid_info($inter_id,$openid){
	    $this->db->where(array('inter_id'=>$inter_id,'openid'=>$openid));
	    $this->db->limit(1);
	    return $this->db->get('fans')->row_array();
	}
	function get_fans_details($inter_id,$openid){
	    $this->db->where(array('inter_id'=>$inter_id,'openid'=>$openid));
	    $this->db->limit(1);
	    return $this->db->get('fans')->row_array();
	}
	function get_fans_details_by_id($inter_id,$fans_id){
	    $this->db->where(array('inter_id'=>$inter_id,'id'=>$fans_id));
	    $this->db->limit(1);
	    return $this->db->get('fans')->row_array();
	}
	
}
