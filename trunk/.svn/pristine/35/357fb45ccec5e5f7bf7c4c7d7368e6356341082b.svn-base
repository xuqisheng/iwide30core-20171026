<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Public_admin_model extends MY_Model {

	public function get_resource_name()
	{
		return 'public_admin';
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
		return 'publics';
	}

	public function table_primary_key()
	{
	    return 'inter_id';
	}
	
	public function attribute_labels()
	{
		return array(
    		'name'=> '公众号名称',
    		'public_id'=> '公众号ID',
    		'wechat_name'=> '微信昵称',
    		'app_id'=> 'App Id',
    		'app_secret'=> 'App Secret',
    		'app_type'=> '公众号类型',
    		'alipay_id'=> '支付类型',
    		'inter_id'=> '公众号内部ID',
    		'status'=> '状态',
    		'create_time'=> '创建时间',
    		'del_time'=> '删除时间',
    		'crypt_type'=> '加密',
    		'aes_key'=> '秘钥',
    		'email'=> 'Email',
    		'logo'=> 'Logo',
    		'domain'=> '域名',
    		'is_multy'=> '酒店数量',
    		'token'=>'token',
    		'follow_page'=> '推荐图文URL',
    		'white_domains'=>'白名单'
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
		'inter_id',
		'name',
		'public_id',
		'wechat_name',
		'domain',
		'app_id',
		'app_secret',
		'email',
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
			// type: numberbox数字框|combobox下拉框|text不写时默认|datebox
		$base_util = EA_base::inst ();
		$modules = config_item ( 'admin_panels' ) ? config_item ( 'admin_panels' ) : array ();
		
		return array (
				'name' => array (
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
				'public_id' => array (
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
				'wechat_name' => array (
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
				'app_id' => array (
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
				'app_secret' => array (
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
				'app_type' => array (
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
				'alipay_id' => array (
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
				'inter_id' => array (
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
				'status' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'combobox',
						'select'=>array('0'=>'正常','1'=>'停用')
				) // textarea|text|combobox|number|email|url|price
,
				'create_time' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui'=> ' hidden ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'datebox' 
				) // textarea|text|combobox|number|email|url|price
,
				'del_time' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui'=> ' hidden ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
,
				'crypt_type' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'combobox',
						'select'=>array('1'=>'普通','2'=>'加密')
				) // textarea|text|combobox|number|email|url|price
,
				'aes_key' => array (
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
				'email' => array (
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
				'logo' => array (
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
				'domain' => array (
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
				'is_multy' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'combobox',
						'select' => array('1'=>'单体酒店','2'=>'多酒店')
				) // textarea|text|combobox|number|email|url|price
				
,
				'token' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text',
				) // textarea|text|combobox|number|email|url|price
,
				'follow_page' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui'=> ' required ',
						//'form_hide'=> TRUE,
						'type' => 'url',
				)
,
				'white_domains' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text',
				) // textarea|text|combobox|number|email|url|price
 
		);
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'inter_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	public function delete($inter_id){
		$this->db->where(array('inter_id'=>$inter_id));
		return $this->db->delete('publics') > 0;
	}


    public function getPublicsById($admin_id,$inter_id){
		$db_read = $this->load->database('iwide_r1',true);
		$db_read->select('publics');
		$db_read->where('admin_id',$admin_id);
		$db_read->where('inter_id',$inter_id);
        $res = $db_read->get ('iwide_core_admin')->row_array ();

        if(isset($res['publics'])&&!empty($res['publics'])){
            $result=explode(',',$res['publics']);
        }else{
            $result=false;
        }

        return $result;
    }


    public function update_publics($admin_id,$inter_id,$new_inter_id){

        $publics=$this->getPublicsById($admin_id,$inter_id);

        if($publics){
            $new_publics=implode(',',$publics);
            $new_publics=$new_publics.','.$new_inter_id;
        }else{
            $new_publics=$new_inter_id;
        }


        $res=$this->db->query("
            UPDATE
                `iwide_core_admin`
            SET
                publics='{$new_publics}'
            WHERE
                admin_id={$admin_id}
            AND
                inter_id='{$inter_id}'
                ");

        return $res;
    }
    
    function check_publics_runstatus($publics,$check_type=''){
		$db_read = $this->load->database('iwide_r1',true);
    	if (empty($publics)||!is_string($publics)){
    		return FALSE;
    	}
    	$publics=explode(',', $publics);
    	$check_types=array(
    			'arrear'=>array(
    					'arrearage',
    					'stop'
    			),
    			'normal'=>array(
    					'running',
    					''
    			),
    			'stop'=>array(
    					'stop'
    			)
    	);
		$db_read->select('inter_id,name,run_status,arrearage_money,stop_service_time');
		$db_read->where_in('inter_id',$publics);
    	isset($check_types[$check_type]) and $db_read->where_in('run_status',$check_types[$check_type]);
    	$data=$db_read->get('publics')->result_array();
    	if (!empty($data)){
    		$data=array_column($data, NULL,'inter_id');
    		return array_intersect_key($data, array_flip($publics));
    	}
    	return array();
    }

}
