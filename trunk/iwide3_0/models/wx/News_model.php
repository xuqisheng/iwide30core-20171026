<?php
/**
 * 微信自定义菜单
 */
class News_model extends MY_Model {
	public function get_resource_name()
	{
		return 'news';
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
		return 'reply_news';
	}
	
	public function table_primary_key()
	{
		return 'id';
	}
	 
	public function attribute_labels()
	{
		return array(
				'id'          => 'Id',
				'title'       => '标题',
				'description' => '描述',
				'pic_url'     => '图片链接',
				'url'         => '图文链接',
				'create_time' => '创建时间',
				'type'        => '类型',
				'inter_id'    => '公众号',
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
				'title',
				'description',
				'pic_url',
				'url',
				'create_time'
		);
	}
	
	/**
	 * 在EasyUI grid中的 date-option 定义，包括宽度，是否排序等等
	 * type: grid中的表头类型定义
	 * form_type: form中的元素类型定义
	 * form_ui: form中的属性补充定义，如加disabled 在< input “disabled” / > 使元素禁用
	 * form_tips: form中的label信息提示
	 * form_hide: form中自动化输出中剔除
	 * form_default: form中的默认值，请用字符类型，不要用数字
	 * select: form中的类型为 combobox时，定义其下来列表
	 */
	public function attribute_ui()
	{
		/* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
		//type: numberbox数字框|combobox下拉框|text不写时默认|datebox
		$base_util= EA_base::inst();
		$modules= config_item('admin_panels')? config_item('admin_panels'): array();
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
		return array(
				'id' => array(
						'grid_ui'=> '',
						'grid_width'=> '10%',
						//'form_ui'=> ' disabled ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				//'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
						'type'=>'text',    //textarea|text|combobox|number|email|url|price
				),
				'title' => array(
						'grid_ui'=> '',
						'grid_width'=> '10%',
						//'form_ui'=> ' disabled ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				//'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
						'type'=>'text',    //textarea|text|combobox|number|email|url|price
				),
				'description' => array(
						'grid_ui'=> '',
						'grid_width'=> '10%',
						//'form_ui'=> ' disabled ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				//'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
						'type'=>'textarea',    //textarea|text|combobox|number|email|url|price
				),
				'pic_url' => array(
						'grid_ui'=> '',
						'grid_width'=> '10%',
						//'form_ui'=> ' disabled ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				//'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
						'type'=>'text',    //textarea|text|combobox|number|email|url|price
				),
				'url' => array(
						'grid_ui'=> '',
						'grid_width'=> '10%',
						//'form_ui'=> ' disabled ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				//'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
						'type'=>'text',    //textarea|text|combobox|number|email|url|price
				),
				'create_time' => array(
						'grid_ui'=> '',
						'grid_width'=> '10%',
						//'form_ui'=> ' disabled ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
						'type'=>'text',    //textarea|text|combobox|number|email|url|price
				),
				'type' => array(
						'grid_ui'=> '',
						'grid_width'=> '10%',
						//'form_ui'=> ' disabled ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
						'type'=>'text',    //textarea|text|combobox|number|email|url|price
				),
				'inter_id' => array(
						'grid_ui'=> '',
						'grid_width'=> '10%',
						//'form_ui'=> ' disabled ',
				//'form_default'=> '0',
				//'form_tips'=> '注意事项',
				//'form_hide'=> TRUE,
				//'function'=> 'show_price_prefix|￥',
						'type'=>'combobox',    //textarea|text|combobox|number|email|url|price
						'select'=>$publics
				),
		);
	}
	 function get_all_news($inter_id){
		 $db_read = $this->load->database('iwide_r1',true);
		 $db_read->where(array('inter_id'=>$inter_id));
		 $db_read->order_by('id desc');
	 	return $db_read->get('reply_news');
	 }
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
		return array('field'=>'id', 'sort'=>'desc');
	}
}