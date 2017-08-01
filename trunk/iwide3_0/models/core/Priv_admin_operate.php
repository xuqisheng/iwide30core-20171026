<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Priv_admin_operate extends MY_Model {

    public $proected_username = array('admin', );    //不能被删除账号
    public $proected_ids = array('1', );    //不能被删除账号
    
	public function get_resource_name()
	{
		return '管理员';
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function table_name()
	{
		return 'core_admin';
	}

	public function table_primary_key()
	{
	    return 'admin_id';
	}
	
	public function attribute_labels()
	{
		return array(
			'admin_id' => 'ID',
			'role_id' => '权限角色',
			'inter_id' => '关联公众号',
			'entity_id' => '关联酒店',
			'username' => '用户名',
			'password' => '密码',
			'password_cf' => '重复密码',
			'nickname' => '昵称',
			'head_pic' => '头像',
			'parent_id' => '创建人',
			'email' => 'Email',
			'create_time' => '创建时间',
			'update_time' => '最后登录',
			'wx_code' => '微信Code',
			'is_wx_report' => '微信通知',
			'is_em_report' => '邮件通知',
			'is_sms_report' => '短信通知',
			'status' => '状态',
			'remark' => '备注',
            'publics'=>'管理公众号'
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
	    return array('admin_id','inter_id','role_id', 'head_pic', 'username', 'nickname', 'create_time', 'update_time', 'status');
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
	    $users= $this->get_data_filter();
	    $users= $this->array_to_hash($users, 'nickname', 'admin_id');
	    $users= array('0'=> '系统创建')+ $users;

	    /** 获取本管理员的酒店权限  */
	    $this->_init_admin_hotels();
	    $publics = $hotels= array();
	    $filter= $filterH= NULL;
	    
	    $admin_username= $this->session->get_admin_username();

	    if( $this->_admin_inter_id== FULL_ACCESS || $admin_username== 'admin' ){
            $filter= array();
        }elseif( $this->_admin_inter_id ){
//            $filter= array('inter_id'=> $this->_admin_inter_id);
            $this->load->model('wx/Public_admin_model');
            $inter_id=$this->_admin_inter_id;
            $adminid= $this->session->get_admin_id();
            $publics=$this->Public_admin_model->getPublicsById($adminid,$inter_id);

            if(!$publics){
                $publics=$inter_id;
            }else{
                $publics[]=$inter_id;
            };

            $publics=$this->inter_id_array($publics);

//            $filter= array('inter_id'=>$publics );
        }

	    if(is_array($filter)){
	        $this->load->model('wx/publics_model');
	        $publics= $this->publics_model->get_public_hash($filter);
	        $publics= $this->publics_model->array_to_hash($publics, 'name', 'inter_id');

	        if( $this->_admin_inter_id== FULL_ACCESS || $admin_username== 'admin' )
	        	$publics= $publics+ array(FULL_ACCESS=> '-所有公众号-');
	    }

	    if( $publics ){
	        $this->load->model('hotel/hotel_model');
	        if( $this->_admin_inter_id== FULL_ACCESS || $admin_username== 'admin' )
	            $hotels= $this->hotel_model->get_hotel_hash( array() );
	        else 
	            $hotels= $this->hotel_model->get_hotel_hash( array('inter_id'=> $this->_admin_inter_id));
	        $hotels= $this->hotel_model->array_to_hash($hotels, 'name', 'hotel_id');
	    }
	    /** 获取本管理员的酒店权限  */
	    
	    return array(
	        'admin_id' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '5%',
	            'form_ui'=> '',
	            'type'=>'text',
	            //'form_type'=> 'hidden',
	        ),
	        'role_id' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '10%',
	            'form_ui'=> '',
	            'type'=>'combobox',
	            'select'=> $roles,
	        ),
	        'inter_id' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '10%',
	            'form_ui'=> '',
	            'form_tips'=> '公众号的标识ID，格式如“a445220098”，NULL代表全部公众号',
	            'type'=>'combobox',
	            'select'=> $publics,
	        ),
	        'entity_id' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '10%',
	            'form_ui'=> '',
	            'form_hide'=> TRUE,
	            'form_tips'=> '酒店的ID，格式如“12,532,3,2”，NULL代表公众号下全部酒店',
	            'type'=>'combobox',
	            'select'=> $hotels,
	        ),
	        'email' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '10%',
	            'form_ui'=> '',
	            'type'=>'email',
	        ),
	        'username' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '10%',
	            'form_ui'=> '',
	            'type'=>'text',
	        ),
	        'password' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '15%',
	            'form_ui'=> '',
	            'form_hide'=> TRUE,
	            'form_default'=> '******',
	            'type'=>'password',
	        ),
	        'nickname' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '15%',
	            'form_ui'=> '',
	            'type'=>'text',
	        ),
	        'head_pic' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '8%',
	            'form_ui'=> '',
	            'grid_function'=> 'show_admin_head|80',
	            'type'=>'logo',
	        ),
	        'parent_id' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '15%',
	            'form_ui'=> 'disabled ',
	            'type'=>'combobox',
	            'select'=> $users,
	        ),
	        'create_time' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '15%',
	            'form_hide'=> TRUE,
	            'form_ui'=> 'disabled ',
	            'type'=>'datebox',
	        ),
	        'update_time' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '15%',
	            'form_hide'=> TRUE,
	            'form_ui'=> 'disabled ',
	            'type'=>'datebox',
	        ),
	        'wx_code' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '10%',
	            'form_ui'=> '',
	            'type'=>'text',
	            'form_hide'=> TRUE,
	        ),
	        'is_sms_report' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '8%',
	            'form_ui'=> '',
	            'type'=>'combobox',
	            'form_hide'=> TRUE,
	            'select'=> $base_util::get_status_options(),
	        ),
	        'is_wx_report' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '8%',
	            'form_ui'=> '',
	            'type'=>'combobox',
	            'form_hide'=> TRUE,
	            'select'=> $base_util::get_status_options(),
	        ),
	        'is_em_report' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '8%',
	            'form_ui'=> '',
	            'type'=>'combobox',
	            'form_hide'=> TRUE,
	            'select'=> $base_util::get_status_options(),
	        ),
	        'status' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '8%',
	            'form_ui'=> '',
	            // label,text,textarea,checkbox,numberbox,validatebox,datebox,combobox,combotree
	            'type'=>'combobox',
	            'select'=> $base_util::get_status_options(),
	        ),
	        'remark' => array(
	            'grid_ui'=> '',
	            'grid_width'=> '15%',
	            'form_ui'=> 'rows="3" ',
	            'type'=>'textarea',
	        ),
            'publics' => array(
                'grid_ui'=> '',
                'grid_width'=> '15%',
                'form_ui'=> 'rows="3" ',
                'type'=>'textarea',
                'form_hide'=> TRUE,
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'status', 'sort'=>'desc');
	}
	
	public function get_default_head()
	{
	    return URL_MEDIA. '/admin_head_pic/default.png';
	}
	
	/**
	 * grid表格中的过滤器匹配方式数组
	 */
	// 	public function filter_option()
	// 	{
	// 	    /* contains,equal,notequal,beginwith,endwith,less,lessorequal,greater,greaterorequal. */
	// 	    return array( 'equal','notequal','less','greater' );
	// 	}
	public function load_by_username($username)
	{
	    $where= array('username'=>$username);
	    $table= $this->table_name();
	    $data= $this->_db()->get_where($table, $where)->result_array();
	    if($data){
	        $data= $data[0];
	        $this->m_sets($data);
	        
	        $role= $this->_db()->select('role_name, role_label')
	           ->get_where('core_admin_role', array('role_id'=> $data['role_id']))
	           ->result_array();
	        if(isset($role[0])) $this->role= $role[0];
	        //print_r($this);die;
	        return $this;
	    }
	    else return FALSE;
	}
	
	/***** 以下上为必填函数信息  *****/
	/**
	 * 管理员密码加密
	 * @param String $password
	 * @return string
	 */
	public function encrytion_password($password)
	{
	    return do_hash($password);
	}
	
	/**
	 * 认证账号密码（必须账号密码正确，状态处于正常状态）
	 * @param String $username
	 * @param String $password
	 * @return boolean
	 */
	public function authenticate($username, $password)
	{
	    if( $this->load_by_username($username) ){
	        if($this->m_get('password')== $this->encrytion_password($password) &&
	           $this->m_get('status')== EA_base::STATUS_TRUE ){
	            return TRUE;
	            
	        } else 
	            return FALSE;
	    } else 
	        return FALSE;
	}
	
	public function has_inter_id($inter_id=NULL )
	{
	    $inter_id= $inter_id? $inter_id: $this->m_get('inter_id');
	    if( $inter_id ){
	        if(preg_match('/\w\d{9}/i', $inter_id )){
	            return TRUE;
	        } else {
	            return FALSE;
	        }
	        
	    } else {
	        return FALSE;
	    }
	}

    private function inter_id_array($inter_id){

        if(is_array($inter_id)){
            foreach($inter_id as $key=>$arr){
                $inter_id[$key]='\''.$arr.'\'';
            }
            $inter_id=implode(',',$inter_id);
            $publics= $this->_db()->query("SELECT `name`,`inter_id` FROM `iwide_publics` WHERE inter_id in ({$inter_id})")->result_array();
        }else{
            $publics= $this->_db()->query("SELECT `name`,`inter_id` FROM `iwide_publics` WHERE inter_id='{$inter_id}'")->result_array();
        }

        $res=array();
        foreach($publics as $arr){
            $res[$arr['inter_id']]=$arr['name'];
        }

        return $res;

    }


    public function getEditPublics($admin_id){

       $publics = $this->_db()->query("SELECT `publics` FROM `iwide_core_admin` WHERE admin_id={$admin_id}")->row_array();

       if(!empty($publics['publics'])){
           $res=explode(',',$publics['publics']);
           return $res;
       }

        return false;
    }


    public function getAllInter(){

      $result = $this->_db()->query("SELECT `name`,`inter_id` FROM `iwide_publics` WHERE status=0")->result_array();

      $res=array();
      foreach($result as $arr){
          $res[$arr['inter_id']]=$arr['name'];
      }

      return $res;

    }


    public function update_publics($data){

        $result = $this->_db()->query("UPDATE `iwide_core_admin` SET `publics`='{$data['publics']}' WHERE admin_id={$data['admin_id']}");

        if($result){
            return true;
        }else{
            return false;
        }

    }


}
