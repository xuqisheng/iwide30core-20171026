<?php 
class Gridcard extends MY_Model
{
	public function table_name()
	{
		return 'iwide_member_card_infomation';
	}
	
	public function table_primary_key()
	{
		return 'ci_id';
	}
	
	public function attribute_labels()
	{
		return array(
			'ci_id'=>'ID',
			'title'=>'卡劵名称',
			'ct_id'=>'卡劵类型',
			'sku_quantity'=>'库存',
			'create_time'=>'创建日期'
		);
	}
	
	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
		return array('ci_id','title','ct_id','sku_quantity','create_time');
	}
	
	/**
	 * 在EasyUI grid中的 date-option 定义，包括宽度，是否排序等等
	 *   type: grid中的表头类型定义
	 *   form_type: form中的元素类型定义
	 *   form_ui: form中的属性补充定义，如加disabled 在<input “disabled” /> 使元素禁用
	 *   form_tips: form中的label信息提示
	 *   form_hide: form中自动化输出中剔除
	 *   form_default: form中的默认值，请用字符类型，不要用数字
	 *   select: form中的类型为 combobox时，定义其下来列表
	 */
	public function attribute_ui()
	{
		/* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
		//type: numberbox数字框|combobox下拉框|text不写时默认|datebox
		// @see http://www.jeasyui.com/documentation/index.php#
		$base_util= EA_base::inst();
		$this->load->model('core/priv_admin_role', 'priv_admin_role');
		$role_array= $this->priv_admin_role->get_role_array();
		$roles= $this->array_to_hash($role_array, 'role_label', 'role_id');
		$publics= array();
		return array(
			'ci_id' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> '',
				'type'=>'text',
			),
			'title' => array(
				'grid_ui'=> '',
				'grid_width'=> '10%',
				'form_ui'=> '',
				'type'=>'text',
			),
			'ct_id' => array(
				'grid_ui'=> '',
				'grid_width'=> '8%',
				'form_ui'=> '',
				'type'=>'combobox',
				'select'=> $this->getCardtypes(),
			),
			'sku_quantity' => array(
				'grid_ui'=> '',
				'grid_width'=> '8%',
				'form_ui'=> '',
				'type'=>'text'
			),
			'create_time' => array(
				'grid_ui'=> '',
				'grid_width'=> '8%',
				'form_ui'=> '',
				'type'=>'text'
			),
		);
	}
	
	protected function getCardtypes()
	{
		$this->load->model('member/icard');
		$cardTypes = $this->icard->getCardTypeList();
		
		$cts = array();
		foreach($cardTypes as $cardtype) {
			$cts[$cardtype->ct_id]=$cardtype->type_name;
		}
		
		return $cts;
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
		return array('field'=>'ci_id', 'sort'=>'desc');
	}
}