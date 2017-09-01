<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Shp_comments extends MY_Model_Mall {

	public function get_resource_name()
	{
		return 'shp_comments';
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
		return 'shp_comments';
	}

	public function table_primary_key()
	{
	    return 'id';
	}
	
	public function attribute_labels()
	{
		return array(
			'id'=> 'ID',
			'hotel_id'=> '酒店ID',
			'inter_id'=> '公众号',
			'order_id'=> '对应订单',
			'gs_id'=> '商品ID',
			'openid'=> 'OPENID',
			'create_time'=> '创建时间',
			'contents'=> '评论内容',
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
			'inter_id',
			'order_id',
			'gs_id',
			'contents',
			'create_time',
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
		$this->_init_admin_hotels();
		$publics = $hotels= array();
		$filter= $filterH= NULL;
		 
		if( $this->_admin_inter_id== FULL_ACCESS ) $filter= array();
		else if( $this->_admin_inter_id ) $filter= array('inter_id'=> $this->_admin_inter_id);
		if(is_array($filter)){
			$this->load->model('wx/publics_model');
			$publics= $this->publics_model->get_public_hash($filter);
			$publics= $this->publics_model->array_to_hash($publics, 'name', 'inter_id');
			//$publics= $publics+ array(FULL_ACCESS=>'-所有公众号-');
		}

		if( $this->_admin_hotels== FULL_ACCESS ) $filterH= array();
		else if( $this->_admin_hotels ) $filterH= array('hotel_id'=> $this->_admin_hotels);
		else $filterH= array();
		 
		if( $publics && is_array($filterH)){
			$this->load->model('hotel/hotel_model');
			$hotels= $this->hotel_model->get_hotel_hash($filterH);
			$hotels= $this->hotel_model->array_to_hash($hotels, 'name', 'hotel_id');
			$hotels= $hotels+ array('0'=>'-不限定-');
		}
		/** 获取本管理员的酒店权限  */

	    return array(
            'id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $hotels,
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $publics,
            ),
            'order_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'gs_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
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
            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
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
            'contents' => array(
                'grid_ui'=> '',
                'grid_width'=> '40%',
                'form_ui'=> ' rows="3" ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'textarea',	//textarea|text|combobox
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

	/**
	 * 评论详细信息
	 * @param int 公众号唯一标识码
	 * @param int 酒店唯一标识码
	 * @param int 评论唯一标识码
	 * @return NULL
	 */
	function get_comment_details($inter_id,$hotel_id,$comment_id){
	    $this->load->model('mall/shp_orders', 'orders_model');
	    $sql = 'SELECT sc.*,f.nickname,f.headimgurl FROM '.$this->db->dbprefix('shp_comments').' sc LEFT JOIN '.$this->db->dbprefix('fans').' f ON sc.openid=f.openid AND f.inter_id=sc.inter_id WHERE sc.inter_id=? AND sc.id=? limit 1';
	    $comment = $this->db->query($sql,array($inter_id,$comment_id))->row_array();
	    if(!empty($comment)){
	        $comment['order_info'] = $this->orders_model->get_order_details($inter_id,$hotel_id,$comment['order_id']);
	        return $comment;
	    }else{
	        return NULL;
	    }
	}
	
	/**
	 * 新增评论
	 * @param array $detail_arrid array('inter_id'=>,'hotel_id'=>,'order_id'=>,'gs_id'=>商品ID,'openid'=>openid,'status'=>0,'contents'=>评论内容)
	 * @return boolean
	 */
	function create_comment($detail_arr){
	    $detail_arr['create_time'] = date('Y-m-d H:i:s');
	    $detail_arr['status']      = 0;
	    if($this->db->insert('shp_comments',$detail_arr) > 0)
	        return $this->db->insert_id();
	    else
	        return -1;
	}

	
}
