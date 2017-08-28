<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Distribute_model extends MY_Model {
	
	protected $_distribution_protection_key_prefix = 'DISTRIBUTION_PROTECTION_';
	
	public function get_resource_name(){
		return '分销收入信息';
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
		return 'v_distribute_incomes';
	}

	public function table_primary_key()
	{
	    return 'id';
	}
	
	public function attribute_labels()
	{
		return array('id'                => '绩效ID',
					 'inter_id'          => '公众号',
					 'hotel_id'          => '酒店',
					 'saler'             => '分销号',
					 'grade_openid'      => '客户OPENID',
					 'grade_table'       => '绩效类型',
					 'grade_id'          => '绩效订单ID',
					 'grade_id_name'     => '订单ID字段名',
					 'order_amount'      => '订单金额',
					 'grade_amount'      => '计算金额',
					 'grade_total'       => '绩效金额',
					 'grade_time'        => '产生时间',
					 'status'            => '状态',
					 'grade_amount_rate' => '绩效值',
					 'grade_rate_type'   => '绩效类型',
					 'remark'            => '备注',
					 'deliver_batch'     => '发放批次',
					 'last_update_time'  => '更新时间',
					 'partner_trade_no'  => '发放单号',
					 'send_time'         => '发放时间',
					 'hotel_name'        => '酒店名',
					 'staff_name'        => '员工名',
					 'cellphone'         => '电话',
					 'product'           => '产品',
					 'order_id'          => '订单号',
					 'distribute'        => '分销员',
					 'nickname'          => '昵称',
					 'headimgurl'        => '头像');
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
	    return array(
			 'id',
			 'saler',
			 'staff_name',
			 'cellphone',
			 'hotel_name',
			 'order_id',
			 'product',
			 // 'nickname',
			 'order_amount',
			 'grade_total',
			 'grade_amount',
			 'grade_time',
			 'status');
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
		
		$status = array('1'=>'可用','2'=>'不可用');

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
            'saler' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'grade_openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'grade_table' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'grade_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'grade_id_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'order_amount' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'grade_total' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'grade_amount' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'grade_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'type'=>'combobox',	//textarea|text|combobox
                'select'=>array('1'=>'未发放','2'=>'已发放','3'=>'发放失败')
            ),
            'grade_amount_rate' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'grade_rate_type' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'remark' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'deliver_batch' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'textarea',	//textarea|text|combobox
            ),
            'last_update_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'textarea',	//textarea|text|combobox
            ),
            'partner_trade_no' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'send_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',
            ),
            'hotel_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',
            ),
            'staff_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'type'=>'text',
            ),
            'cellphone' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'textarea',	//textarea|text|combobox
            ),
            'product' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'textarea',	//textarea|text|combobox
            ),
            'order_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',	//textarea|text|combobox
            ),
            'distribute' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',
            ),
            'nickname' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',
            ),
            'headimgurl' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text',
            ),
            'id' => array(
                'grid_ui'=> '',
            	'form_hide'=> TRUE,
                'grid_width'=> '10%',
                'type'=>'text',
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'combobox',
                'select'=> $hotels,
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'combobox',
                'select'=> $publics,
            )
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'id', 'sort'=>'desc');
	}
	function get_distribute_config_data($inter_id,$code='distribute_time_limit'){
		$this->_db('iwide_r1')->where(array('inter_id'=>$inter_id,'code'=>$code));
		return $this->_db('iwide_r1')->get('distribute_config_data');
	}
	function save_distribute_config_data($inter_id,$data,$code='distribute_time_limit'){
		$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id,'code'=>$code));
		$res = $this->_db('iwide_rw')->get('distribute_config_data')->row_array();
		if(!empty($res)){//存在数据
			$dis_value = !empty($res['value'])?unserialize($res['value']):array();
			if(isset($dis_value['distribute_status']) && $dis_value['distribute_status']==1){//开启了，就不允许更新
				return false;
			}else{
				$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id,'code'=>$code));
				return $this->_db('iwide_rw')->update('distribute_config_data',$data) > 0;
			}
		}else{
			return $this->_db('iwide_rw')->insert('distribute_config_data',$data) > 0;
		}
	}
	function get_hotel_config($inter_id){
		$this->_db('iwide_r1')->where(array('inter_id'=>$inter_id));
		return $this->_db('iwide_r1')->get('distribute_config');
	}
	function save_hotel_config($inter_id,$category,$value,$type,$jfk_config = 0,$group_config = 0,$hotel_config = 0){
		$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id,'excitation_category'=>$category));
		if($this->_db('iwide_rw')->get('distribute_config')->num_rows() > 0){
			$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id,'excitation_category'=>$category));
			return $this->_db('iwide_rw')->update('distribute_config',array('excitation_value'=>$value,'excitation_type'=>$type,'jfk_value'=>$jfk_config,'group_value'=>$group_config,'hotel_value'=>$hotel_config)) > 0;
		}else{
			return $this->_db('iwide_rw')->insert('distribute_config',array('excitation_value'=>$value,'excitation_type'=>$type,'excitation_category'=>$category,'inter_id'=>$inter_id,'jfk_value'=>$jfk_config,'group_value'=>$group_config,'hotel_value'=>$hotel_config)) > 0;
		}
	}
	/**
	 * 分销部门列表
	 * @param unknown $inter_id
	 * @return NULL
	 */
	public function get_departments($inter_id){
		$this->_db('iwide_r1')->where(array('inter_id'=>$inter_id,'status'=>1));
		$query = $this->_db('iwide_r1')->get('distribute_departments');
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return NULL;
		}
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
	/**
	 * @param Array GET 参数（过滤，排序，分页）
	 * @param String $format 有2种数据规格：
	 * 		'array':返回datatable组件所需要的数组形式
	 * 		'':返回普通的对象数组
	 * grid过滤，排序，分页时，过滤参数
	 * 如需定制，请重写此函数
	 */
	public function filter( $params=array(), $select= array(), $format='array' )
	{
		$table= $this->table_name();
        $where= $where_in= array();
		if(isset($params['t']))unset($params['t']);
		$dbfields= array_values($fields= $this->_db()->list_fields($table));
        foreach ($params as $k=>$v){
            //过滤非数据库字段，以免产生sql报错，把in匹配另外处理
            if(in_array($k, $dbfields) ){
                if( is_array($v) ){
                    $where_in[$k]= $v;
                } else {
                    $where[$k]= $v;
                }
            }
        }

		if( isset($params['sort_field']) && isset($params['sort_direct']) ){
			$sort= $params['sort_field']. ' '. $params['sort_direct'];
		} else
			$pk= $this->table_primary_key();
		$sort= "{$pk} DESC";  //默认排序

		$num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
		$page_size= isset($params['page_size'])? $params['page_size']: $num;
		$current_page= isset($params['page_num'])? $params['page_num']: 1;

		if(count($select)==0) {
			$select= $this->grid_fields();
		}
		$select= count($select)==0? '*': implode(',', $select);

		//echo $select;die;
		$offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;

        if( count($where_in)>0 ){
            foreach ($where_in as $k => $v ){
                if( count($v) ) $this->_shard_db()->where_in($k, $v);
            }
        }

		$total= $this->_db()->select(" {$select} ")->get_where($table, $where)->num_rows();

        if( count($where_in)>0 ){
            foreach ($where_in as $k => $v ){
                if( count($v) ) $this->_shard_db()->where_in($k, $v);
            }
        }

		//echo $total;

		$result= $this->_db()->select(" {$select} ")->order_by($sort)
		->limit($page_size, $offset)->get_where($table, $where)
		->result_array();
		// 	    print_r($result);
		if($format=='array'){
			$tmp= array();
			$field_config= $this->get_field_config('grid');
			foreach ($result as $k=> $v){
				//判断combobox类型需要对值进行转换
				foreach($field_config as $sk=>$sv){
					if($field_config[$sk]['type']=='combobox') {
						if( isset($field_config[$sk]['select'][$v[$sk]])){
							$v[$sk]= $field_config[$sk]['select'][$v[$sk]];
						}
						else $v[$sk]= '--';
					}
					if( $field_config[$sk]['grid_function'] ) {
						$funp= explode('|', $field_config[$sk]['grid_function']);
						$fun= $funp[0];
						$funp[0]= $v[$sk];
						$v[$sk]= call_user_func_array ($fun, $funp);
					} else if( $field_config[$sk]['function'] ) {
						$funp= explode('|', $field_config[$sk]['function']);
						$fun= $funp[0];
						$funp[0]= $v[$sk];
						$v[$sk]= call_user_func_array ($fun, $funp);
					}
				}//---

				$el= array_values($v);
				$el['DT_RowId']= $v[$this->table_primary_key()];
				$tmp[]= $el;
			}
			$result= $tmp;
		}

		return array(
				'total'=>$total,
				'data'=>$result,
				'page_size'=>$page_size,
				'page_num'=>$current_page,
		);
	}
	
	public function get_all_incomes_group($inter_id,$hotel_id = NULL,$key = NULL,$begin_time = NULL,$end_time = NULL,$offset = NULL,$limit = 0,$distribute_type=NULL,$val_only = 'ALL'){
		$sql = "SELECT id,inter_id,hotel_id,saler,order_amount,sum(grade_total) total,grade_amount,hotel_name,staff_name,cellphone FROM ".$this->_db('iwide_r1')->dbprefix('v_distribute_incomes')." WHERE `status`=1 AND saler > 0 AND inter_id=?";
// 		$sql = "SELECT * FROM ".$this->_db('iwide_rw')->dbprefix('v_distribute_incomes')." WHERE inter_id=? AND saler > 0 ";
		$param = array($inter_id);
		if(!empty($distribute_type)){
			$sql .= ' AND distribute=?';
			array_push($param, $distribute_type);
		}
		if(!empty($hotel_id)){
			$sql .= ' AND hotel_id=?';
			array_push($param, $hotel_id);
		}
		if($val_only == 'VALID'){
			$sql .= ' AND grade_total>0';
		}
		if(!empty($key)){
			$sql .= ' AND (hotel_name like ? OR staff_name like ? )';
			array_push($param, '%'.$key.'%');
			array_push($param, '%'.$key.'%');
		}
		if(!empty($begin_time)){
			$sql .= " AND DATE(grade_time)>=DATE('".$begin_time."')";
		}
		if(!empty($end_time)){
			$sql .= " AND DATE(grade_time)<=DATE('".$end_time."')";
		}
		$sql .= " GROUP BY saler";
		if(!is_null($offset)){
			$sql .= ' limit ?,?';
			array_push($param, $offset);
			array_push($param, $limit);
		}
		return $this->_db('iwide_r1')->query($sql,$param);
	}
	public function get_all_incomes_group_count($inter_id,$hotel_id = NULL,$key = NULL,$begin_time = NULL,$end_time = NULL,$distribute_type=NULL,$val_only = 'ALL'){
		$sql = "SELECT COUNT(*) nums FROM (SELECT * FROM ".$this->_db('iwide_r1')->dbprefix('v_distribute_incomes')." WHERE `status`=1 AND saler > 0 AND inter_id=?";
// 		$sql = "SELECT * FROM ".$this->_db('iwide_rw')->dbprefix('v_distribute_incomes')." WHERE inter_id=? AND saler > 0 ";
		$param = array($inter_id);
		if(!empty($distribute_type)){
			$sql .= ' AND distribute=?';
			array_push($param, $distribute_type);
		}
		if(!empty($hotel_id)){
			$sql .= ' AND hotel_id=?';
			array_push($param, $hotel_id);
		}
		if($val_only == 'VALID'){
			$sql .= ' AND grade_total>0';
		}
		if(!empty($key)){
			$sql .= ' AND (hotel_name like ? OR staff_name like ? )';
			array_push($param, '%'.$key.'%');
			array_push($param, '%'.$key.'%');
		}
		if(!empty($begin_time)){
			$sql .= " AND DATE(grade_time)>=DATE('".$begin_time."')";
		}
		if(!empty($end_time)){
			$sql .= " AND DATE(grade_time)<=DATE('".$end_time."')";
		}
		$sql .= " GROUP BY saler)a";
		$query = $this->_db('iwide_r1')->query($sql,$param)->row_array();
		return $query['nums'];
	}
	
	/**
	 * @todo 分销业绩发放记录
	 * @param string 公众号识别码
	 * @param string 酒店ID
	 * @param string 查询关键字
	 * @param datetime 起始时间
	 * @param datetime 结束时间
	 * @param int 
	 * @param int
	 * @return Query Result
	 */
	function get_send_records($inter_id,$hotel_id = NULL,$key = NULL,$begin_time = NULL,$end_time = NULL,$offset = NULL,$limit = 0){
		$sql = 'SELECT s.*,h.`name`,h.hotel_name FROM (SELECT inter_id,hotel_id,saler,partner_trade_no,send_time,SUM(grade_total) total FROM (SELECT * FROM iwide_distribute_grade_all WHERE inter_id=?) i WHERE `status`=2 GROUP BY partner_trade_no) s 
INNER JOIN (SELECT * FROM iwide_hotel_staff WHERE inter_id=?) h ON h.inter_id=s.inter_id AND s.saler=h.qrcode_id ';
		
		return $this->_db('iwide_r1')->last_query ( $sql );
	}

	/**
	 *
	 * @param Array $params
	 *        	{"inter_id":"公众号ID","hotel_id":"酒店ID","saler":"分销号","grade_openid":"粉丝openid","grade_table":"记录产生绩效订单的表","grade_id":"记录产生绩效的表的ID值","grade_id_name":"记录产生绩效的表的ID名称","order_amount":"产生绩效的订单的金额","grade_total":"绩效总金额","grade_amount":"计算绩效的金额","grade_time":"绩效产生时间","status":"1:已核定未发放/线下发放，2:已核定已发放，3:未核定","grade_amount_rate":"绩效值","grade_rate_type":"计算类型","remark":"备注","deliver_bath":"","last_update_time":"","partner_trade_no":"","send_time":"","hotel_name":"酒店","staff_name":"员工","product":"产品","distribute":"分销类型","order_id":"订单号"}
	 * @return boolean
	 */
	public function create_grade($params) {
		if (empty ( $params ['inter_id'] ) || empty ( $params ['hotel_id'] ) || empty ( $params ['grade_openid'] ) || empty ( $params ['grade_table'] ) || empty ( $params ['grade_id'] ) || empty ( $params ['grade_total'] ) || empty ( $params ['product'] ))
			return false;
		$this->data->trans_begin ();
		$datas ['inter_id']          = $params ['inter_id'];
		$datas ['hotel_id']          = $params ['hotel_id'];
		$datas ['saler']             = $params ['saler'];
		$datas ['grade_openid']      = $params ['grade_openid'];
		$datas ['grade_table']       = $params ['grade_table'];
		$datas ['grade_id_name']     = $params ['grade_id_name'];
		$datas ['grade_id']          = $params ['grade_id'];
		$datas ['order_amount']      = $params ['order_amount'];
		$datas ['grade_total']       = $params ['grade_total'];
		$datas ['grade_time']        = date('Y-m-d H:i:s');
		$datas ['status']            = 1;
		$datas ['grade_amount_rate'] = isset($params ['grade_amount_rate']) ? $params ['grade_amount_rate'] : 0;
		$datas ['grade_rate_type']   = isset($params ['grade_rate_type']) ? $params ['grade_amount_rate'] : 0;
		$datas ['remark']            = isset($params ['remark']) ? $params ['grade_amount_rate'] : '';
		
		$this->_db('iwide_rw')->insert('distribute_grade_all',$datas);
		
		$exts ['inter_id']   = $params ['inter_id'];
		$exts ['hotel_name'] = $params ['hotel_name'];
		$exts ['staff_name'] = $params ['staff_name'];
		$exts ['product']    = $params ['product'];
		$exts ['distribute'] = $params ['distribute'];
		$exts ['order_id']   = $params ['order_id'];
		$this->_db('iwide_rw')->insert('distribute_grade_all_ext',$exts);
		
		if ($this->_db('iwide_rw')->trans_status () === FALSE) {
			$this->_db('iwide_rw')->trans_rollback ();
			return FALSE;
		} else {
			$this->_db('iwide_rw')->trans_commit ();
			return TRUE;
		}
		return false;
	}
	
	/**
	 * 发放记录
	 * @param unknown $inter_id
	 * @param unknown $hotel_id
	 * @param unknown $key
	 * @param unknown $begin_time
	 * @param unknown $end_time
	 * @param unknown $offset
	 * @param number $limit
	 */
	function get_send_records_count($inter_id,$hotel_id = NULL,$key = NULL,$begin_time = NULL,$end_time = NULL,$offset = NULL,$limit = 0){
		$sql = 'SELECT s.*,h.`name`,h.hotel_name FROM (SELECT inter_id,hotel_id,saler,partner_trade_no,send_time,SUM(grade_total) total FROM (SELECT * FROM iwide_distribute_grade_all WHERE inter_id=?) i WHERE `status`=2 GROUP BY partner_trade_no) s 
INNER JOIN (SELECT * FROM iwide_hotel_staff WHERE inter_id=?) h ON h.inter_id=s.inter_id AND s.saler=h.qrcode_id ';
		
		return $this->_db('iwide_r1')->last_query($sql);
	}
	
	/**
	 * @todo 取分销业绩
	 * @param unknown $inter_id
	 * @param unknown $hotel_id
	 * @param unknown $key 关键字
	 * @param unknown $begin_time 起始时间
	 * @param unknown $end_time 结束时间
	 * @param unknown $offset
	 * @param number $limit
	 * @param number $distribute_type 分销类别：区域0，人员1
	 * @param number $val_only 有效绩效：ALL|VALID|0
	 */
	public function get_all_incomes($inter_id,$hotel_id = NULL,$key = NULL,$begin_time = NULL,$end_time = NULL,$offset = NULL,$limit = 0,$distribute_type=NULL,$val_only = 'ALL'){
		$sql = "SELECT * FROM ".$this->_db('iwide_r1')->dbprefix('v_distribute_incomes')." WHERE inter_id=? AND saler > 0 AND `status`=1 ";
		$param = array($inter_id);
		if(!empty($hotel_id)){
			$sql .= ' AND hotel_id=?';
			array_push($param, $hotel_id);
		}
		if(!empty($distribute_type)){
			$sql .= ' AND distribute=?';
			array_push($param, $distribute_type);
		}
		if($val_only == 'VALID'){
			$sql .= ' AND grade_total>0';
		}
		if(!empty($key)){
			$sql .= ' AND (hotel_name like ? OR staff_name like ? OR cellphone like ? OR product like ? OR order_id like ?)';
			array_push($param, '%'.$key.'%');
			array_push($param, '%'.$key.'%');
			array_push($param, '%'.$key.'%');
			array_push($param, '%'.$key.'%');
			array_push($param, '%'.$key.'%');
		}
		if(!empty($begin_time)){
			$sql .= " AND DATE(grade_time)>=DATE('".$begin_time."')";
		}
		if(!empty($end_time)){
			$sql .= " AND DATE(grade_time)<=DATE('".$end_time."')";
		}
		if(!is_null($offset)){
			$sql .= ' limit ?,?';
			array_push($param, $offset);
			array_push($param, $limit);
		}
		return $this->_db('iwide_r1')->query($sql,$param);
	}
	public function get_all_incomes_count($inter_id,$hotel_id = NULL,$key = NULL,$begin_time = NULL,$end_time = NULL,$distribute_type=NULL,$val_only = 'ALL'){
		$sql = "SELECT count(*) nums FROM ".$this->_db('iwide_r1')->dbprefix('v_distribute_incomes')." WHERE inter_id=? AND saler > 0 AND `status`=1 ";
		$param = array($inter_id);
		if(!empty($hotel_id)){
			$sql .= ' AND hotel_id=?';
			array_push($param, $hotel_id);
		}
		if(!empty($distribute_type)){
			$sql .= ' AND distribute=?';
			array_push($param, $distribute_type);
		}
		if($val_only == 'VALID'){
			$sql .= ' AND grade_total>0';
		}
		if(!empty($key)){
			$sql .= ' AND (hotel_name like ? OR staff_name like ? OR cellphone like ? OR product like ? OR order_id like ?)';
			array_push($param, '%'.$key.'%');
			array_push($param, '%'.$key.'%');
			array_push($param, '%'.$key.'%');
			array_push($param, '%'.$key.'%');
			array_push($param, '%'.$key.'%');
		}
		if(!empty($begin_time)){
			$sql .= " AND DATE(grade_time)>=DATE('".$begin_time."')";
		}
		if(!empty($end_time)){
			$sql .= " AND DATE(grade_time)<=DATE('".$end_time."')";
		}
		if(!empty($offset)){
			$sql .= ' limit ?,?';
			array_push($param, $offset);
			array_push($param, $limit);
		}
		$query = $this->_db('iwide_r1')->query($sql,$param)->row_array();
		return $query['nums'];
	}
	function send_grades_by_saler($inter_id,$saler,$batch_no = '',$begin_time = '',$end_time = ''){
		$sql = 'SELECT gs.*,ds.openid FROM (SELECT SUM(grade_total) total,saler,inter_id,GROUP_CONCAT(id) ids FROM iwide_distribute_grade_all WHERE `status`=1 AND `inter_id`=? AND saler=?';
		$params = array($inter_id,$saler);
		if (! empty ( $begin_time )) {
			$sql .= ' AND grade_time >= ?';
			$params [] = $begin_time;
		}
		if (! empty ( $end_time )) {
			$sql .= ' AND grade_time <= ?';
			$params [] = $end_time;
		}
		$sql .= ' GROUP BY saler) gs
				LEFT JOIN (SELECT * FROM iwide_hotel_staff WHERE inter_id=?)ds ON ds.inter_id=gs.inter_id AND ds.qrcode_id=gs.saler';
		$params [] = $inter_id;
		$amount_query = $this->_db('iwide_r1')->query ( $sql,$params )->result_array ();
		$err_count = 0;
		$suc_count = 0;
		foreach ($amount_query as $amount_item){
			$amount = $amount_item ['total'];
			if ($amount < 0.01) {
				$err_count++;
			} else if ($amount > 2000) {
				$err_count++;
			} else {
				$this->load->model('pay/company_pay_model');
				// $up_param['last_update_time'] = date('Y-m-d H:i:s');
				$flag = $this->company_pay_model->company_pay ( $amount_item ['openid'], $amount * 100 ,$inter_id,$amount_item['ids'],$amount_item['saler'],'绩效激励',$batch_no);
// 				$this->load->model('distribute/distribute_notice_model');
// 				$msg = '<p>核定绩效截止时间：'.date('Y-m-d 23:59:59',strtotime('-1 day',time())).'</p><p>核定发放绩效金额：'.$amount.'</p><p>发放状态：';
				if ($flag['errmsg'] == 'ok') {
					$suc_count++;
					$up_param['status'] = 2;
					$up_param['partner_trade_no'] = $flag['partner_trade_no'];
					$up_param['send_time'] = date('Y-m-d H:i:s');
					$up_where = array('inter_id'=>$inter_id,'saler'=>$saler,'status'=>1);
					if (! empty ( $begin_time )) {
						$up_where ['grade_time >='] = $begin_time;
					}
					if (! empty ( $end_time )) {
						$up_where ['grade_time <='] = $end_time;
					}
					$this->_db('iwide_rw')->where($up_where);
// 					$this->_db('iwide_rw')->where_in('id',$ids);
					$this->_db('iwide_rw')->update ( 'distribute_grade_all', $up_param );
					
					//发放系统消息
// 					$msg .= '成功</p><p>亲，绩效金额已发放至您的微信“钱包-零钱”中，请查看。再接再厉，多多的绩效等着您...</p>';
// 					$this->distribute_notice_model->create_deliver_notice_content($inter_id,$amount,date('Y-m-d 23:59:59',strtotime('-1 day',time())),$msg,$amount_item ['openid'],$batch_no);
				}else{
					//记录发放失败次数
					$this->update_deliver_fails_by_saler($inter_id,$saler);
					//发放系统消息
// 					$msg .= '失败</p><p>亲，别着急，今天发放不成功，明天还会继续发放哦</p>';
// 					$msg .= '<p>失败原因：'.$flag['return_msg'].'</p>';
// 					$this->distribute_notice_model->create_deliver_notice_content($inter_id,$amount,date('Y-m-d 23:59:59',strtotime('-1 day',time())),$msg,$amount_item ['openid'],$batch_no);
					$err_count++;
				}
			}
		}
		return array('success'=>$suc_count,'error'=>$err_count);
	}
	function send_grades_by_saler_yestoday($inter_id,$saler,$batch_no = ''){
		$this->load->model('distribute/grades_model');
		$deliver_config = $this->grades_model->get_deliver_setting($inter_id);
		$sql = 'SELECT gs.*,ds.openid FROM (SELECT SUM(grade_total) total,saler,inter_id,GROUP_CONCAT(id) ids FROM iwide_distribute_grade_all WHERE `status`=1 AND `inter_id`=? AND saler=? AND grade_time <? AND grade_time > ? GROUP BY saler) gs
				LEFT JOIN (SELECT * FROM iwide_hotel_staff WHERE inter_id=?)ds ON ds.inter_id=gs.inter_id AND ds.qrcode_id=gs.saler';
		$amount_query = $this->_db('iwide_r1')->query ( $sql,array($inter_id,$saler,date('Y-m-d 00:00:00'),$deliver_config->send_after_time,$inter_id) )->result_array ();
		$err_count = 0;
		$suc_count = 0;

		$deliver_id = '';
		if(isset($deliver_config->deliver) && $deliver_config->deliver == 2){
			$distribution_delier_account = $this->get_redis_key_status('__DISTRIBUTION_DELIER_ACCOUNT');
			if($distribution_delier_account) $deliver_id = $distribution_delier_account;
		}
		foreach ($amount_query as $amount_item){
		
			$amount = $amount_item ['total'];
			if ($amount < 0.01 || $amount > 20000) {
				$err_count++;
			} else {
				if($amount < 1){//小于1块的直接update失败次数
					//记录发放失败次数
					$this->update_deliver_fails_by_saler($inter_id,$saler);
					continue;
				}
				//添加余额不足公众号判断 situguanchen 2017-03-28
				$notenough_data = $inter_id_arr = array();
				if($this->get_redis_key_status('_NOTENOUGH_INTER_ID')){
					$notenough_data = json_decode($this->get_redis_key_status('_NOTENOUGH_INTER_ID'),true);
					if($notenough_data['date'] == date('Ymd')){
						$inter_id_arr = $notenough_data['inter_id_arr'];
					}
				}
				if(in_array($inter_id,$inter_id_arr)){//如果是余额不足，直接update失败次数
					//记录发放失败次数
					$this->update_deliver_fails_by_saler($inter_id,$saler);
					continue;
				}
				$this->load->model('pay/company_pay_model');
				// $up_param['last_update_time'] = date('Y-m-d H:i:s');
				if(isset($amount_item['saler']) && $amount_item['saler'] > 0){
					$flag = $this->company_pay_model->company_pay ( $amount_item ['openid'], $amount * 100 ,$inter_id,$amount_item['ids'],$amount_item['saler'],'绩效激励',$batch_no,$deliver_id);
					$this->load->model('distribute/distribute_notice_model');
					$msg = '<p>核定绩效截止时间：'.date('Y-m-d 23:59:59',strtotime('-1 day',time())).'</p><p>核定发放绩效金额：'.$amount.'</p><p>发放状态：';
					if(!empty($flag['rid'])){
						//写入发放关联订单数据
						$sql = "INSERT IGNORE INTO iwide_distribute_send_grade_rel (sr_id,ga_id) SELECT ?,id FROM iwide_distribute_grade_all WHERE inter_id=? AND saler=? AND `status`=1 AND grade_time<? AND grade_time>?";
						$this->_db('iwide_rw')->query($sql,array($flag['rid'],$inter_id,$saler,date('Y-m-d 00:00:00'),$deliver_config->send_after_time));
					}
					if ($flag['errmsg'] == 'ok') {
						$suc_count++;
						$up_param['status'] = 2;
						$up_param['partner_trade_no'] = $flag['partner_trade_no'];
						$up_param['send_time'] = date('Y-m-d H:i:s');
						$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id,'saler'=>$saler,'status'=>1,'grade_time <'=>date('Y-m-d 00:00:00'),'grade_time >'=>$deliver_config->send_after_time));
	// 					$this->_db('iwide_rw')->where_in('id',$ids);
						if(!$this->_db('iwide_rw')->update ( 'distribute_grade_all', $up_param )){
							$this->set_redis_key_status('CONTINUE_DELIVER','false');
						}else{
							$this->set_redis_key_status('CONTINUE_DELIVER','true');
						}
						
						//发放系统消息
						$msg .= '成功</p><p>亲，绩效金额已发放至您的微信“钱包-零钱”中，请查看。再接再厉，多多的绩效等着您...</p>';
						$this->distribute_notice_model->create_deliver_notice_content($inter_id,$amount,date('Y-m-d 23:59:59',strtotime('-1 day',time())),$msg,$amount_item ['openid'],$batch_no);
					}else if($flag['errmsg'] == 'faild'){
						//记录发放失败次数
						$this->update_deliver_fails_by_saler($inter_id,$saler);
						//发放系统消息
						$msg .= '失败</p><p>亲，别着急，今天发放不成功，明天还会继续发放哦</p>';
						$msg .= '<p>失败原因：'.$flag['return_msg'].'</p>';
						$this->distribute_notice_model->create_deliver_notice_content($inter_id,$amount,date('Y-m-d 23:59:59',strtotime('-1 day',time())),$msg,$amount_item ['openid'],$batch_no);
						$err_count++;
					}else if($flag['errmsg'] == 'duplicate'){
						//加多一个重复的判断，不然都跑异常去了
					}else if($flag['errmsg'] == 'notenough'){//余额不足 
						//记录发放失败次数
						$this->update_deliver_fails_by_saler($inter_id,$saler);
						//发放系统消息
						$msg .= '失败</p><p>亲，别着急，今天发放不成功，明天还会继续发放哦</p>';
						$msg .= '<p>失败原因：'.$flag['return_msg'].'</p>';
						$this->distribute_notice_model->create_deliver_notice_content($inter_id,$amount,date('Y-m-d 23:59:59',strtotime('-1 day',time())),$msg,$amount_item ['openid'],$batch_no);
						$err_count++;
						//余额不足的inter_id 放进redis 每天更新一次
						$notenough_data = $inter_id_arr = array();
						if($this->get_redis_key_status('_NOTENOUGH_INTER_ID')){
							$notenough_data = json_decode($this->get_redis_key_status('_NOTENOUGH_INTER_ID'),true);
							if($notenough_data['date'] == date('Ymd')){
								$inter_id_arr = $notenough_data['inter_id_arr'];
								$inter_id_arr[] = $inter_id;//如果是余额不足，新增进去
								$notenough_data['inter_id_arr'] = $inter_id_arr;
								$this->set_redis_key_status('_NOTENOUGH_INTER_ID',json_encode($notenough_data));
							}else{
								$inter_id_arr[] = $inter_id;//如果是余额不足，新增进去
								$notenough_data['inter_id_arr'] = $inter_id_arr;
								$notenough_data['date']   = date('Ymd');
								$this->set_redis_key_status('_NOTENOUGH_INTER_ID',json_encode($notenough_data));
							}
						}else{
							$inter_id_arr[] = $inter_id;//如果是余额不足，新增进去
							$notenough_data['inter_id_arr'] = $inter_id_arr;
							$notenough_data['date']   = date('Ymd');
							$this->set_redis_key_status('_NOTENOUGH_INTER_ID',json_encode($notenough_data));
						}
					}else{
						//发放异常
						$up_param['status'] = 9;
						$up_param['send_time'] = date('Y-m-d H:i:s');
						$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id,'saler'=>$saler,'status'=>1,'grade_time <'=>date('Y-m-d 00:00:00'),'grade_time>'=>$deliver_config->send_after_time));
// 						$this->_db('iwide_rw')->update ( 'distribute_grade_all', $up_param );
						if(!$this->_db('iwide_rw')->update ( 'distribute_grade_all', $up_param )){
							$this->set_redis_key_status('CONTINUE_DELIVER','false');
						}else{
							$this->set_redis_key_status('CONTINUE_DELIVER','true');
						}
					}
				}
			}
		}
		return array('success'=>$suc_count,'error'=>$err_count);
	}
	function send_grades_by_ids($inter_id,$ids){
		$sql = 'SELECT gs.*,ds.openid FROM (SELECT SUM(grade_total) total,saler,inter_id,GROUP_CONCAT(id) ids FROM iwide_distribute_grade_all WHERE `status`=1 AND `inter_id`=? AND id IN ('.implode(',',$ids).') GROUP BY saler) gs
				LEFT JOIN (SELECT * FROM iwide_hotel_staff WHERE inter_id=?)ds ON ds.inter_id=gs.inter_id AND ds.qrcode_id=gs.saler';
		$amount_query = $this->_db('iwide_r1')->query ( $sql,array($inter_id,$inter_id) )->result_array ();
	
		$err_count = 0;
		$suc_count = 0;
		foreach ($amount_query as $amount_item){
			$amount = $amount_item ['total'];
			if ($amount < 0.01) {
				$err_count++;
			} else if ($amount > 2000) {
				$err_count++;
			} else {
				$this->load->model('pay/company_pay_model');
				// $up_param['last_update_time'] = date('Y-m-d H:i:s');
				$flag = $this->company_pay_model->company_pay ( $amount_item ['openid'], $amount * 100 ,$inter_id,$amount_item['ids'],$amount_item['saler'],'绩效激励');
				if ($flag['errmsg'] == 'ok') {
					$suc_count ++;
					$up_param ['status'] = 2;
					$up_param ['partner_trade_no'] = $flag ['partner_trade_no'];
					$up_param ['send_time'] = date ( 'Y-m-d H:i:s' );
					$this->_db('iwide_rw')->where ( array (
							'inter_id' => $inter_id 
					) );
					$this->_db('iwide_rw')->where_in ( 'id', $ids );
					$this->_db('iwide_rw')->update ( 'distribute_grade_all', $up_param );
				} else {
					$err_count ++;
				}
			}
		}
		return array (
				'success' => $suc_count,
				'error' => $err_count 
		);
	}
	
	/**
	 * 更新订单信息，同时更新绩效
	 * @param $order_id 订单号
	 * @param $start_date 起始时间
	 * @param $end_date 结束时间
	 * @param $price 订单金额
	 * @param $is_suborder 是否子订单
	 * @return boolean
	 */
	function update_distribute_info($order_id,$start_date,$end_date,$price,$is_suborder = TRUE){
		$order_info = '';
		$inter_id   = $this->session->get_admin_inter_id();
		if($is_suborder){
			$order_info = $this->_db('iwide_r1')->get_where ( 'hotel_order_items', array ('orderid' => $order_id,'inter_id' => $inter_id ) )->limit ( 1 );
		}else{
			$sql = 'SELECT * FROM '.$this->_db('iwide_r1')->dbprefix('hotel_order_items').' WHERE orderid= (SELECT orderid FROM '.$this->_db('iwide_r1')->dbprefix('hotel_order_additions').' WHERE web_orderid=? AND inter_id=? LIMIT 1) AND inter_id=? LIMIT 1';
			$order_info = $this->_db('iwide_r1')->query($sql,array($order_id,$inter_id,$inter_id));
		}
		if ($order_info != '' && $order_info->num_rows () > 0) {
			$order_info = $order_info->result ();
			
			//分销配置信息
			$this->_db('iwide_r1')->select(array('excitation_type','excitation_category','excitation_value','jfk_value','group_value','hotel_value'));
			$this->_db('iwide_r1')->where(array('inter_id' => $inter_id,'excitation_category' => 2));
			$this->_db('iwide_r1')->limit(1);
			$distribute_config_query = $this->_db('iwide_r1')->get('distribute_config');
			$distribute_info = $distribute_config_query->result();
			
			$this->_db('iwide_rw')->trans_begin ();
			//更新订单状态
			if($is_suborder){
				$this->_db('iwide_rw')->where(array('inter_id' => $inter_id,'webs_orderid' => $order_id));
			}else{
				$this->_db('iwide_rw')->where(array('inter_id' => $inter_id,'orderid' => $order_info[0]->orderid));
			}
			$this->_db('iwide_rw')->update('hotel_order_items',array('startdate'=>$start_date,'enddate'=>$end_date,'iprice'=>$price));
			
			//saler,hotel=-3,group=-2,jfk=-1
// 			$saler_total = $hotel_total = $group_total = $jfk_total = 0;
			$saler_value = $hotel_value = $group_value = $jfk_value = 0;
			if(!empty($distribute_info[0]->excitation_value)) 
				$saler_value = $distribute_info[0]->excitation_value;
			if(!empty($distribute_info[0]->hotel_value)) 
				$hotel_value = $distribute_info[0]->excitation_value;
			if(!empty($distribute_info[0]->group_value)) 
				$group_value = $distribute_info[0]->excitation_value;
			if(!empty($distribute_info[0]->jfk_value)) 
				$jfk_value = $distribute_info[0]->excitation_value;

// 				IF @ex_type=1 OR @ex_type=2 THEN
// 				IF @ex_val=0 AND @hotel_value=0 THEN SET @group_value=10-@jfk_total; END IF;
// 				IF @ex_val=0 AND @hotel_value>0 THEN SET @group_value=10-@jfk_total-@hotel_value; END IF;
			$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id,'grade_id'=>$order_info[0]->id,'saler >' => 0));
			$this->_db('iwide_rw')->update('distribute_grade_all',array('grade_total'=>$saler_value*$price,'grade_amount'=>$price));
			$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id,'grade_id'=>$order_info[0]->id,'saler' => -1));
			$this->_db('iwide_rw')->update('distribute_grade_all',array('grade_total'=>$jfk_value*$price,'grade_amount'=>$price));
			$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id,'grade_id'=>$order_info[0]->id,'saler' => -2));
			$this->_db('iwide_rw')->update('distribute_grade_all',array('grade_total'=>$group_value*$price,'grade_amount'=>$price));
			$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id,'grade_id'=>$order_info[0]->id,'saler' => -3));
			$this->_db('iwide_rw')->update('distribute_grade_all',array('grade_total'=>$hotel_value*$price,'grade_amount'=>$price));
			if ($this->_db('iwide_rw')->trans_status () === FALSE) {
				$this->_db('iwide_rw')->trans_rollback ();
				return FALSE;
			} else {
				$this->_db('iwide_rw')->trans_commit ();
				return TRUE;
			}
		} else {
			//查询不到订单项目，更新失败
			return FALSE;
		}
	}

	/**
	 * 取有绩效未发放的分销员
	 * @param unknown $inter_id
	 * @param number $limit
	 * @param number $offset
	 */
	public function get_unsend_salers($inter_id,$limit=20,$offset=0){
		$sql = "SELECT ga.saler,hs.openid,ga.inter_id FROM iwide_distribute_grade_all ga LEFT JOIN iwide_hotel_staff hs ON ga.inter_id=hs.inter_id AND ga.saler=hs.qrcode_id WHERE ga.grade_total>0 AND ga.`status`=1 AND hs.openid<>'' AND hs.is_distributed=1 AND ga.inter_id=? GROUP BY saler";
		$params[] = $inter_id;
		if($limit > 0){
			$sql .= ' LIMIT ?,?';
			$params[] = $offset;
			$params[] = $limit;
		}
		return $this->_db('iwide_r1')->query($sql,$params)->result();
	}
	
	/**
	 * @todo 取上次发放时间不是今天的（发放完更新最后发放时间，发放时间是今天说明今天的绩效已经发放了）、循环周期到今天的、自动发放的、绩效金额大于0的分销员
	 */
	public function get_auto_deliver_salers(){
		//取上次发放时间不是今天的（发放完更新最后发放时间，发放时间是今天说明今天的绩效已经发放了）、循环周期到今天的、自动发放的、绩效金额大于0的分销员
		$sql = "SELECT dc.inter_id,ga.saler FROM iwide_distribute_deliver_config dc 
				LEFT JOIN iwide_distribute_grade_all ga ON ga.inter_id=dc.inter_id 
				LEFT JOIN iwide_hotel_staff hs ON ga.inter_id=hs.inter_id AND ga.saler=hs.qrcode_id  
				WHERE dc.`mode`=0 AND DATEDIFF(NOW(),dc.last_send_time) >= dc.`cycle` AND dc.send_time<=? AND DATE_FORMAT(dc.last_send_time,'%Y-%m-%d')<>? AND ga.grade_total>0 AND ga.`status`=1 AND ga.grade_time<? AND ga.grade_time>dc.send_after_time AND hs.openid<>'' AND hs.is_distributed=1 AND ga.deliver_fail=0 GROUP BY saler";
		return $this->_db('iwide_r1')->query($sql,array(date('H:i:s'),date('Y-m-d'),date('Y-m-d 00:00:00')))->result();
		// return $this->_db('iwide_rw')->query($sql,array(date('H:i:s'),'1970-01-01',date('Y-m-d H:i:s')))->result();
	}
	
	/**
	 * 更新最后发放时间
	 */
	public function update_last_deliver_time() {
		$sql = "UPDATE iwide_distribute_deliver_config dc,(SELECT count(*) counts,ga.inter_id FROM iwide_distribute_grade_all ga LEFT JOIN iwide_hotel_staff hs ON ga.inter_id=hs.inter_id AND ga.saler=hs.qrcode_id WHERE ga.inter_id in (select inter_id from iwide_distribute_deliver_config where mode = 0) and ga.saler >0 and ga.grade_total>0 AND ga.`status`=1 AND hs.openid<>'' AND hs.is_distributed=1 GROUP BY ga.inter_id)a SET dc.last_send_time=NOW() WHERE a.inter_id=dc.inter_id AND a.counts = 0 AND date_format(dc.last_send_time,'%Y%m%d')<>?";
		$this->_db ( 'iwide_rw' )->query ( $sql, array (date ( 'Ymd' )) );
	}
	
	/**
	 * 更新发放失败次数
	 * 
	 * @param unknown $inter_id        	
	 * @param unknown $saler        	
	 */
	public function update_deliver_fails_by_saler($inter_id, $saler) {
		$sql = "UPDATE iwide_distribute_grade_all set deliver_fail=deliver_fail+1 WHERE grade_time<=? AND inter_id=? AND saler=?";
		return $this->_db('iwide_rw')->query ( $sql, array ( date ( 'Y-m-d 23:59:59', strtotime ( '-1 day', time () ) ), $inter_id, $saler ) );
	}
	/**
	 * 重置60天内发放失败的绩效记录
	 */
	public function reset_fails_grades(){
		$sql = "UPDATE iwide_distribute_grade_all set deliver_fail=0 WHERE grade_time>? AND deliver_fail>0 AND status=1";
		 $this->_db('iwide_rw')->query ( $sql, array ( date ( 'Y-m-d 23:59:59', strtotime ( '-60 day', time () ) ) ) );
		 echo $this->_db('iwide_rw')->last_query();
	}
	
	/**
	 * 取分销保护状态配置
	 * @param string $inter_id
	 * @return Object status->CLOSED|OPENED
	 */
	public function get_distribution_protection_config($inter_id){
		$res = $this->get_redis_key_status($this->_distribution_protection_key_prefix.$inter_id);
		return empty($res) ? json_decode('{"status":"CLOSED","protection_time":0}') : json_decode($res);
	}
	/**
	 * 保存分销保护配置信息
	 * @param string $inter_id        	
	 * @param string $status        	
	 * @param int $protection_time        	
	 * @return Object
	 */
	public function save_distribution_protection_config($inter_id, $status, $protection_time = 86400) {
		return $this->set_redis_key_status ( $this->_distribution_protection_key_prefix.$inter_id, json_encode ( [ 'status' => $status, 'protection_time' => $protection_time ] ) );
	}
	/**
	 * 查询受保护的分销员
	 * @param string $openid 用户openid        	
	 * @param string $inter_id        	
	 * @return int 查询到受保护的分销员时返回分销员的分销号，查询没有结果则返回0
	 */
	public function get_protection_saler($openid, $inter_id = '') {
		$where = [ 'openid' => $openid, 'protect_to >= ' => time () ];
		if (! empty ( $inter_id )) {
			$where ['inter_id'] = $inter_id;
		}
		$res = $this->_db ( 'iwide_r1' )->limit ( 1 )->where ( $where )->order_by ( 'id desc' )->get ( 'distribution_protection' )->row ();
		return empty ( $res->saler ) ? 0 : $res->saler;
	}
	/**
	 * 保存分销员分销保护源信息
	 * 
	 * @param string $inter_id        	
	 * @param string $source_openid
	 *        	来源用户openid
	 * @param string $source
	 *        	来源链接
	 * @param int $saler
	 *        	分销号
	 * @param int $current_time
	 *        	受保护开始时间戳，默认当前时间戳
	 * @param string $module
	 *        	模块名称，默认为空
	 * @return boolean
	 */
	public function save_saler_protection_info($inter_id, $source_openid, $source, $saler, $current_time = '', $module = '') {
		if (empty ( $source ) || empty ( $source_openid ) || empty ( $saler ))
			return FALSE;
		if (empty ( $current_time ))
			$current_time = time();
		$protection_info = $this->get_distribution_protection_config ( $inter_id );
		if (! $protection_info){
			$current_time = time();
		}else {
			$current_time += intval ( $protection_info->protection_time );
		}
		if (empty ( $module ))
			$module = $this->get_module_name_from_url ( $source );
		$sql = 'INSERT IGNORE INTO iwide_distribution_protection (`inter_id`,`openid`,`protect_to`,`created_time`,`saler`,`module`,`slink`) VALUES (?,?,?,?,?,?,?)';
		$this->_db('iwide_rw')->query($sql,[$inter_id,$source_openid,$current_time,date('Y-m-d H:i:s'),$saler,$module,$source]);
		// $this->_db ( 'iwide_rw' )->insert ( 'distribution_protection', [ 
		// 		'inter_id'     => $inter_id,
		// 		'openid'       => $source_openid,
		// 		'protect_to'   => $current_time,
		// 		'created_time' => date ( 'Y-m-d H:i:s' ),
		// 		'saler'        => $saler,
		// 		'module'       => $module,
		// 		'slink'        => $source 
		// ] );
		return $this->_db ( 'iwide_rw' )->affected_rows () > 0;
	}
	
	/**
	 *
	 * @param string $url        	
	 */
	private function get_module_name_from_url($url) {
		if (empty ( $url ))
			return '';
		$url = str_ireplace ( 'http://', '', $url );
		$url = str_ireplace ( 'https://', '', $url );
		$parts = explode ( '/', $url );
		return isset ( $parts [1] ) ? $parts [1] : '';
	}
	
	protected function _load_cache( $name='Cache' ){
		if(!$name || $name=='cache')
			$name='Cache';
		$this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'dis_ato_'), $name );
		return $this->$name;
	}
	public function get_redis_key_status($key = 'CONTINUE_DELIVER'){
		$cache= $this->_load_cache();
		$redis= $cache->redis->redis_instance();
		return $redis->get( $key );
	}
	public function set_redis_key_status($key = 'CONTINUE_DELIVER',$val = 'false'){
		$cache= $this->_load_cache();
		$redis= $cache->redis->redis_instance();
		return $redis->set( $key , $val);
	}
}