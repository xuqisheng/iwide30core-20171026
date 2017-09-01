<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Staff_model_ extends MY_Model {
	public function get_resource_name(){
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
	
	public function attribute_labels()
	{
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
					 'view_count'     => '--',
					 'status'         => '状态，1:申请中,2:正常,3:未通过4:删除',
					 'qrcode_id'      => '参数二维码id',
					 'lock'           => '锁定',
					 'id_card'        => '--',
					 'status_time'    => '失效的时间',
					 'is_distributed' => '是否参与分销',
					 'verify'         => '--',
					 'verified'       => '--',
					 'id'             => '员工ID',
					 'openid'         => '用户OPENID');
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
	    return array(
			'name',
			'sex',
			'position',
			'cellphone',
			'employee_id',
			'qrcode_id',
			'hotel_id',
			'inter_id',
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
		//$parents= $this->get_cat_tree_option();

		$parents['0']= '一级分类';
		
		$status = array('1'=>'申请中','2'=>'正常','3'=>'未通过','4'=>'删除');

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
            'name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'sex' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'radio',	//textarea|text|combobox
            ),
            'birthday' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'datebox',	//textarea|text|combobox
            ),
            'education' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'graduation' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'position' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'business' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'in_date' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'datebox',	//textarea|text|combobox
            ),
            'changes' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'textarea',	//textarea|text|combobox
            ),
            'previous_job' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'description' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'master_dept' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'second_dept' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'employee_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'textarea',	//textarea|text|combobox
            ),
            'in_group_date' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'textarea',	//textarea|text|combobox
            ),
            'cellphone' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'hotel_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',
            ),
            'view_count' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',
            ),
            'qrcode_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',
            ),
            'lock' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',
            ),
            'id_card' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',
            ),
            'status_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',
            ),
            'is_distributed' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',
            ),
            'verify' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',
            ),
            'verified' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',
            ),
            'openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'combobox',
                'select'=> $publics,
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'combobox',
                'select'=> $hotels,
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
	

	public function get_cat_tree_option()
	{
	    $array= '';
        //$array['_'. $k]= '+'. $v['label'];
        $tmp= $this->get_data_filter(array('parent_id'=> '0' ));
        //print_r($tmp);die;
        foreach ($tmp as $sv){
            $array[$sv['cat_id']]= '+'. $sv['cat_name'];
            $tmp2= $this->get_data_filter(array('parent_id'=> $sv['cat_id']));
            //print_r($array);die;
            foreach ($tmp2 as $ssv) {
                $array[$ssv['cat_id']]= '+---'. $ssv['cat_name'];
            }
        }
	    //print_r($array);die;
	    return $array;
	}
	function create_staff($arr){
		if($this->db->insert('hotel_staff',$arr) > 0){
			return $this->db->insert_id();
		}else{
			return -1;
		}
	}
	
}