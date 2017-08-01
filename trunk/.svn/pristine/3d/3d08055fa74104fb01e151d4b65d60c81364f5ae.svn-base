<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Public_ext_model extends MY_Model {

	public function get_resource_name()
	{
		return 'public_ext';
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
    		'is_authed'=> '第三方授权状态',
    		'auth_time'=> '授权/取消授权时间',
    		'auth_code'=> '授权码',
    		'auth_info'=> '授权方的公众号帐号基本信息',
    		'auth_expire_time'=>'授权过期时间',
    		'follow_page'=> '推荐图文URL',
    		'statis_code'=> '访问统计代码',
    		'white_domains'=>'白名单',
    		'run_status'=>'运行状态',
    		'arrearage_money'=>'欠费金额',
			'stop_service_time'=>'停服时间'
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
		'is_authed',
		'auth_time',
		'run_status'
		);
	}

	//定义 m_save 保存时不做转义字段
	public function unaddslashes_field()
	{
	    return array( 'statis_code', );
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
						'type' => 'combobox',
						'select'=> array('-1'=>'未认证','0'=>'微信认证','1'=>'新浪微博认证','2'=>'腾讯微博认证','3'=>'已资质认证通过但还未通过名称认证','4'=>'已资质认证通过、还未通过名称认证，但通过了新浪微博认证','5'=>'已资质认证通过、还未通过名称认证，但通过了腾讯微博认证') 
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
				'statis_code' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui'=> ' rows="5" ',
						'form_tips'=> '请填写对应的js统计代码',
						//'form_hide'=> TRUE,
						'type' => 'textarea',
				)
,
				'is_authed' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_hide'=> TRUE,
						'type' => 'combobox',
						'select' => array('1'=>'非第三方授权','2'=>'第三方授权')
				) // textarea|text|combobox|number|email|url|price
				
,
				'auth_time' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui'=> ' disabled ',
						'form_hide'=> TRUE,
						'type' => 'text',
				) // textarea|text|combobox|number|email|url|price
,
				'auth_code' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui'=> ' disabled ',
						'form_hide'=> TRUE,
						'type' => 'text',
				)
,
				'auth_expire_time' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui'=> ' disabled ',
						'form_hide'=> TRUE,
						'type' => 'text'
				)
,
				'auth_info' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui'=> ' disabled ',
						'form_hide'=> TRUE,
						'type' => 'text'
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
 ,
				'run_status' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'combobox',
						'select' => array('stop'=>'停止','arrearage'=>'欠费','running'=>'正常',''=>'正常')
				) // textarea|text|combobox|number|email|url|price
,
				'arrearage_money' => array (
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
				'stop_service_time' => array (
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
}
