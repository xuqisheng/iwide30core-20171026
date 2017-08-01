<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Priv_admin_authid extends MY_Model {

	public function get_resource_name()
	{
		return '管理员授权账号';
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
		return 'core_admin_authid';
	}

	public function table_primary_key()
	{
	    return 'auth_id';
	}
	
    const STATUS_APPLY = 1;
    const STATUS_CHECK = 2;
    const STATUS_CANCLE= 3;

    public function get_status_label()
    {
        return array(
            self::STATUS_APPLY => '申请中',
            self::STATUS_CHECK => '审核通过',
            self::STATUS_CANCLE=> '禁用中',
        );
    }

	public function attribute_labels()
	{
		return array(
    		'auth_id'=> 'ID',
    		'admin_id'=> '授权管理员',
    		'inter_id'=> '所属公众号',
    		'openid'=> 'Openid',
    		'nickname'=> '昵称',
    		'headimgurl'=> '头像',
    		'apply_time'=> '申请授权',
    		'auth_time'=> '授权时间',
    		'delete_time'=> '取消授权',
    		'last_operation'=> '最后操作时间',
    		'status'=> '状态',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
    		'auth_id',
    		'admin_id',
    		'openid',
    		'nickname',
    		'headimgurl',
    		'auth_time',
    		'last_operation',
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
            'auth_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'admin_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'nickname' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'headimgurl' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'logo',	//textarea|text|combobox|number|email|url|price
            ),
            'apply_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'datebox',	//textarea|text|combobox|number|email|url|price
            ),
            'auth_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'datebox',	//textarea|text|combobox|number|email|url|price
            ),
            'delete_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'datebox',	//textarea|text|combobox|number|email|url|price
            ),
            'last_operation' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'datebox',	//textarea|text|combobox|number|email|url|price
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
	            'type'=>'combobox',
	            'select'=> $base_util::get_status_options(),
            ),
        );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'auth_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	/**
	 * 前端是否已经得到授权
	 * @param unknown $openid
	 */
	public function can_access($openid)
	{
	    $filter= array('openid'=>$openid, 'status'=>self::STATUS_CHECK );
	    $data= $this->find($filter);
	    if($data && count($data)>0 ){
	        return TRUE;
	    } else {
	        return FALSE;
	    }
	}
	/**
	 * 寻找已经存在的授权记录 (包括申请中、审核、删除状态)
	 * @param unknown $openid
	 */
	public function find_record($openid)
	{
	    $result= $this->_db()->where('openid', $openid)
	       ->where_in('status', array(self::STATUS_APPLY, self::STATUS_CHECK, self::STATUS_CANCLE) )
	       ->get($this->table_name())->result_array();
	    
	    if( $result && count($result)>0 ){
	        return $result[0];
	        
	    } else {
	        return FALSE;
	    }
	}

	public function save_record($openid, $admin_id, $inter_id)
	{
	    $data= array(
	        'apply_time'=> date("Y-m-d H:i:s"),
	        'status'=> self::STATUS_APPLY,
	        'admin_id'=> $admin_id,
	        'inter_id'=> $inter_id,
	        'openid'=> $openid,
	    );
	    $fan= $this->_db()->get_where('fans', array('openid'=> $openid) )->result_array();
	    if( isset($fan[0]) ){
	        $data['nickname']= $fan[0]['nickname'];
	        $data['headimgurl']= $fan[0]['headimgurl'];
	    }
	    $result= $this->_db()->get_where($this->table_name(), array('openid'=> $openid) )->result_array();
	    if( $result ){
	        $id= $result[0]['auth_id'];
	        return $this->_db()->where( array('auth_id'=>$id) )
	           ->update($this->table_name(), $data );
	        
	    } else {
	        return $this->_db()->insert($this->table_name(), $data );
	    }
	}
	
	public function status_toggle()
	{
	    $staus= ($this->m_get('status')%2)+1;
	    if($staus==self::STATUS_CHECK) $this->m_set('auth_time', date("Y-m-d H:i:s"));
	    else $this->m_set('delete_time', date("Y-m-d H:i:s"));
	    $this->m_set('status', $staus);
	    $this->m_save();
	}
	
	public function update_last_operation($openid)
	{
	    $result= $this->_db()->where(array('openid'=> $openid) )->update($this->table_name(), array(
	        'last_operation'=> date('Y-m-d H:i:s'),
	    ));
	    return $result;
	}
}
