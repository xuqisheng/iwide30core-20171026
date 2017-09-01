<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Keywords_model extends MY_Model {

	public function get_resource_name()
	{
		return 'keywords';
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
		return 'keywords';
	}

	public function table_primary_key()
	{
	    return 'id';
	}
	
	public function attribute_labels()
	{
		return array(
		'id'=> 'Id',
		'keyword'=> '关键字',
		'match_type'=> '匹配类型',
		'create_time'=> '创建时间',
		'inter_id'=> '公众号'
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
		'keyword',
		'match_type',
		'create_time',
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
				'id' => array (
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
				'keyword' => array (
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
				'match_type' => array (
						'grid_ui' => '',
						'grid_width' => '10%',
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						// 'form_hide'=> TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'combobox',
						'select'=>array('1'=>'模糊匹配','2'=>'精确匹配')
				) // textarea|text|combobox|number|email|url|price
,
				'create_time' => array (
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
						// 'form_ui'=> ' disabled ',
						// 'form_default'=> '0',
						// 'form_tips'=> '注意事项',
						'form_hide' => TRUE,
						// 'function'=> 'show_price_prefix|￥',
						'type' => 'text' 
				) // textarea|text|combobox|number|email|url|price
 
		);
	}
	function save($inter_id){
		$datas['keyword']    = $this->input->post('keyword',true);
		$datas['match_type'] = $this->input->post('match_type',true);
		$articals = $this->input->post('ars');
		$art_arr = array();
		$datas['inter_id']    = $inter_id;
		$datas['create_time'] = date('Y-m-d H:i:s');
		$this->db->trans_begin();
		$this->db->insert('keywords',$datas);
		$kid = $this->db->insert_id();
		if($this->uri->segment(4) == 'text'){
			$this->db->insert('reply_news',array('title'=>$articals,'description'=>$articals,'create_time'=>date('Y-m-d H:i:s'),'inter_id'=>$inter_id,'type'=>2));
			$news_id = $this->db->insert_id();
			$this->db->insert('keyword_reply_rel',array('inter_id'=>$inter_id,'keyword_id'=>$kid,'sort'=>0,'news_id'=>$news_id));
		}else{
			$temp_arr = explode(',', $articals);
			foreach ($temp_arr as $item){
				$temp = explode(':', $item);
				$this->db->insert('keyword_reply_rel',array('inter_id'=>$inter_id,'keyword_id'=>$kid,'sort'=>$temp[0],'news_id'=>$temp[1]));
			}
		}
		$this->db->trans_complete();
		if($this->db->trans_complete() === FALSE){
			$this->db->trans_rollback();
			return json_encode(array('errmsg'=>'保存失败'));
		}else{
			$this->db->trans_commit();
			return json_encode(array('errmsg'=>'ok'));
		}
	}
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */
	function delete(){
		$this->db->where(array('id'=>$this->input->get('ids')));
		return $this->db->delete('keywords') > 0;
	}
	
}
