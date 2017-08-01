<?php 
class Gridmember extends MY_Model
{
	const TABLE_MEMBER             = 'member';
	const TABLE_MEMBER_INFO        = 'member_additional';
	
	public function table_name()
	{
		return 'member';
	}
	
	public function table_primary_key()
	{
		return 'mem_id';
	}
	
	public function attribute_labels()
	{
		return array(
			'mem_id'=>'会员ID',
			'inter_id'=>'酒店',
			'name'=>'会员名',
			'mem_card_no'=>'会员号',
			'level'=>'会员等级',
			'membership_number'=>'卡号',
			'bonus'=>'积分',
			'balance'=>'余额'
		);
	}
	
	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
		return array('mem_id', 'inter_id','name', 'mem_card_no', 'level', 'membership_number', 'bonus', 'balance');
	}
	
	public function attribute_ui()
	{
		

		/** 获取本管理员的酒店权限  */
		$this->_init_admin_hotels();
		$publics = $hotels= $topics= array();
		$filter= $filterH= NULL;
		 
		if( $this->_admin_inter_id== FULL_ACCESS ) $filter= array();
		else if( $this->_admin_inter_id ) $filter= array('inter_id'=> $this->_admin_inter_id);
		if(is_array($filter)){
			$this->load->model('wx/publics_model');
			$publics= $this->publics_model->get_public_hash($filter);
			$publics= $this->publics_model->array_to_hash($publics, 'name', 'inter_id');
			//$publics= $publics+ array(FULL_ACCESS=>'-所有公众号-');
			 
			$this->load->model('mall/shp_topic');
			$topics= $this->shp_topic->get_data_filter($filter);
			$topics= $this->shp_topic->array_to_hash_multi($topics, 'identity|page_title', 'topic_id');
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
			'mem_id' => array(
				'grid_ui'=> '',
				'grid_width'=> '3%',
				'form_ui'=> '',
				'type'=>'text',
				//'form_type'=> 'hidden',
			),
			'name' => array(
				'grid_ui'=> '',
				'grid_width'=> '3%',
				'form_ui'=> '',
				'type'=>'text',
			),
			'mem_card_no' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> '',
				'type'=>'text',
					//'form_type'=> 'hidden',
			),
			'level' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> '',
				'type'=>'combobox',
				'select'=>$this->getLevelHash(),
			),
			'membership_number' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> '',
				'type'=>'text',
				//'form_type'=> 'hidden',
			),
			'bonus' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> '',
				'type'=>'text',
				//'form_type'=> 'hidden',
			),
			'balance' => array(
				'grid_ui'=> '',
				'grid_width'=> '5%',
				'form_ui'=> '',
				'type'=>'text',
				//'form_type'=> 'hidden',
			),
			'inter_id' => array(
					'grid_ui'=> '',
					'grid_width'=> '5%',
					'form_ui'=> ' disabled ',
					//'form_default'=> '0',
					//'form_tips'=> '注意事项',
					//'form_hide'=> TRUE,
					'type'=>'combobox',
					'select'=> $publics,
			),	
		);
	}
	
	public static function default_sort_field()
	{
		return array('field'=>'mem_id', 'sort'=>'desc');
	}
	
	public function filter_json( $params=array(), $select= array() )
	{
		$table= $this->table_name();
		$where= array();
		$dbfields= array_values($fields= $this->_db()->list_fields($table));
		foreach ($params as $k=>$v){
			//过滤非数据库字段，以免产生sql报错
			if(in_array($k, $dbfields) || ($k==self::TABLE_MEMBER.'.inter_id')) $where[$k]= $v;
		}
	
		if( isset($params['order'][0]['column']) && isset($params['order'][0]['dir']) ){
			$field= $this->field_name_in_grid($params['order'][0]['column']);
			$sort= $field. ' '. $params['order'][0]['dir'];
				
		} else {
			$pk= $this->table_primary_key();
			$sort= "{$pk} DESC";  //默认排序
		}
	
		if(count($select)==0) {
			$select= $this->grid_fields();
		}
		$select= count($select)==0? '*': implode(',', $select);
		
		$select = str_replace('mem_id', self::TABLE_MEMBER.'.mem_id', $select);
		$select = str_replace('inter_id', self::TABLE_MEMBER.'.inter_id', $select);
	
		$total= $this->_db()->get_where($table, $where)->num_rows();
		//echo $total;
		
		$result= $this->_db()->select(" {$select} ")->order_by($sort)
			->join(self::TABLE_MEMBER_INFO, self::TABLE_MEMBER.'.mem_id='.self::TABLE_MEMBER_INFO.'.mem_id', 'left')
			->limit($this->input->post('length'),$this->input->post('start'))->get_where($table, $where)
			->result_array();
	
		$tmp= array();
		$field_config= $this->get_field_config('grid');
		foreach ($result as $k=> $v){
			//判断combobox类型需要对值进行转换
			foreach($field_config as $sk=>$sv){
				if($field_config[$sk]['type']=='combobox') {
					if( isset($field_config[$sk]['select'][$v[$sk]]))
						$v[$sk]= $field_config[$sk]['select'][$v[$sk]];
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
			}//-----
	
			$el= array_values($v);
			$el['DT_RowId']= $v[$this->table_primary_key()];
			$tmp[]= $el;
		}
		$result= $tmp;
		return array(
			'draw'=> isset($params['draw'])? $params['draw']: 1,
			'data'=> $result,
			'recordsTotal'=>$total,
			'recordsFiltered'=>$total,
		);
	}
	
	public function filter( $params=array(), $select= array(), $format='array' )
	{
		$table= $this->table_name();
		$where= array();
		$dbfields= array_values($fields= $this->_db()->list_fields($table));
		foreach ($params as $k=>$v){
			//过滤非数据库字段，以免产生sql报错
			if(in_array($k, $dbfields) || ($k==self::TABLE_MEMBER.'.inter_id')) $where[$k]= $v;
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
	
		$select = str_replace('mem_id', self::TABLE_MEMBER.'.mem_id', $select);
		$select = str_replace('inter_id', self::TABLE_MEMBER.'.inter_id', $select);
		//echo $select;die;
		$offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;
		$total= $this->_db()->get_where($table, $where)->num_rows();

		$result= $this->_db()->select(" {$select} ")
		->join(self::TABLE_MEMBER_INFO, self::TABLE_MEMBER.'.mem_id='.self::TABLE_MEMBER_INFO.'.mem_id', 'left')
		->order_by($sort)
		->limit($page_size, $offset)->get_where($table, $where)
		->result_array();

		if($format=='array'){
			$tmp= array();
			$field_config= $this->get_field_config('grid');
			foreach ($result as $k=> $v){
				//判断combobox类型需要对值进行转换
				foreach($field_config as $sk=>$sv){
					if($field_config[$sk]['type']=='combobox') {
						if( isset($field_config[$sk]['select'][$v[$sk]]))
							$v[$sk]= $field_config[$sk]['select'][$v[$sk]];
						else $v[$sk]= '--';
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
	
	protected function getLevelHash()
	{
		$this->load->model('member/config','mconfig');
		$result = $this->mconfig->getConfig('level',true,$this->_admin_inter_id);
	
		if($result) {
			$ret = array();
			foreach($result->value as $k=>$v) {
				$ret[$k] = $v;
			}
			return $ret;
		} else {
			return array();
		}
	}

    /**
     * 速8定制(获取导出会员绑定的数据)
     * @param array $params 条件
     * @param int $startime 开始时间
     * @param int $endtime 结束时间
     * @return array
     */
	public function get_bind_member($params=array(),$startime=0,$endtime=0){
	    if(empty($params['inter_id'])) return array();
	    $where=array('inter_id'=>$params['inter_id'],'is_send'=>2,'send_count >'=>0);
	    $this->_db()->select('open_id,send_time')->from('auto_send_record')->where($where);
	    if(!empty($startime)){
            $this->_db()->where('send_time >=',$startime);
	    }

        if(!empty($startime)){
            $this->_db()->where('send_time <=',$endtime);
        }

	    $send_record = $this->_db()->where('send_time <>','')->where('send_time >',0)->group_by('open_id')->order_by('send_time desc')->get()->result_array();
	    if($this->input->get('debug')=='1') {
            echo $this->_db()->last_query();
            echo '<pre>';
            print_r($send_record);
	    }
        if(!empty($send_record)){
            $openids = array();
            foreach ($send_record as $vo){
                $openids[]=$vo['open_id'];
            }
            $where=array('a.inter_id'=>$params['inter_id'],'b.membership_number <>'=>'','b.membership_number >'=>0);
            $member_info = $this->_db()->from('member as a')->select('a.create_time,b.membership_number,b.name,b.telephone')
                                ->join('member_additional as b','b.mem_id=a.mem_id','left')
                                ->where($where)->where_in('openid',$openids)
                                ->group_by('b.ma_id')->order_by('a.create_time desc')->get()->result_array();
            if($this->input->get('debug')=='1'){
                echo '<pre>';
                echo $this->_db()->last_query();
                print_r($member_info);
            }
            return $member_info;
	    }
	    return array();
	}

    /**
     * 速8定制(会员绑定统计)
     * @param array $params
     * @param array $select
     * @param string $format
     * @return array
     */
    public function filter_bind_member($params=array(),$select= array(),$format='array'){
        $table= 'auto_send_record';
        $exp=array('>','<','!=','<>');
        $where= $where_in= array();
        $dbfields= array_values($fields= $this->_db()->list_fields($table));
        foreach ($params as $k=>$v){
            //过滤非数据库字段，以免产生sql报错，把in匹配另外处理
            if(in_array($k, $dbfields) ){
                if( is_array($v)){
                    $_exp=isset($v[0])?(in_array($v[0],$exp)?$v[0]:''):'';
                    if($_exp && isset($v[1]))
                        $where[$k.' '.$_exp]=$v[1];
                    else
                        $where_in[$k]= $v;
                } else {
                    $where[$k]= $v;
                }
            }

            if(strpos($k,'.')!==false){
                $fk = explode('.',$k);
                if(in_array($fk[1], $dbfields)) {
                    if( is_array($v)){
                        $_exp=isset($v[0])?(in_array($v[0],$exp)?$v[0]:''):'';
                        if($_exp && isset($v[1]))
                            $where[$k.' '.$_exp]=$v[1];
                        else
                            $where_in[$k]= $v;
                    } else {
                        $where[$k]= $v;
                    }
                }
            }
        }

        if(isset($params['search']) && is_array($params['search']) && !empty($params['search'])){
            $params['f_like'] = $params['search'];
        }

        if( isset($params['sort_field']) && isset($params['sort_direct']) ){
            $sort= $params['sort_field']. ' '. $params['sort_direct'];
        } else{
            $pk= 'a.send_time';
            $sort= "{$pk} DESC";  //默认排序
        }

        $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
        $page_size= isset($params['page_size'])? $params['page_size']: $num;
        if(isset($params['length'])){
            $page_size = $params['length'];
        }
        $current_page= isset($params['page_num'])? $params['page_num']: 1;

        if(count($select)==0) {
            $select= array('a.id','a.inter_id','a.open_id','a.createtime','a.send_time');
        }
        $select= count($select)==0? '*': implode(',', $select);

        if( isset($params['f_like']) && count($params['f_like'])>0 ){
            //模糊匹配参数
            $like_field=$this->like_fields();
            $or_like = '';
            foreach ($like_field as $sv) {
                if(isset($params['f_like']['value']) && !empty($params['f_like']['value']))
                    $or_like .= $sv.' LIKE \'%'.$params['f_like']['value'].'%\' OR ';
            }
            if(!empty($or_like)){
                $or_likes = '('.substr($or_like, 0,-3).')';
                $this->_db()->where($or_likes);
            }
        }

        //echo $select;die;
        $offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;
        if(isset($params['start'])){
            $offset = $params['start'];
        }
        $num_rows = $this->_db()->select("{$select}")->from($table.' as a')->where($where)->where_in($where_in)->group_by('a.open_id')->get()->num_rows();
        if( isset($params['f_like']) && count($params['f_like'])>0 ){
            //模糊匹配参数
            $like_field=$this->like_fields();
            $or_like = '';
            foreach ($like_field as $sv) {
                if(isset($params['f_like']['value']) && !empty($params['f_like']['value']))
                    $or_like .= $sv.' LIKE \'%'.$params['f_like']['value'].'%\' OR ';
            }
            if(!empty($or_like)){
                $or_likes = '('.substr($or_like, 0,-3).')';
                $this->_db()->where($or_likes);
            }
        }
        $result= $this->_db()->select("{$select}")->from($table.' as a')
            ->where($where)->where_in($where_in)
            ->group_by('a.open_id')
            /*->limit($page_size, $offset)*/->order_by($sort)->get()
            ->result_array();
        $send_total = count($result);
        $lists = array();
        if(!empty($result)){
            //以一分钟为周期整理人数
            foreach ($result as $key=>$value){
                $push_count = 1;
                $send_time = strtotime($value['send_time']);
                if(floatval($send_time)<=0) $send_time = 0;
                $datetime = date('Y-m-d H:i',$send_time);
                if(isset($lists[$datetime]['push_count'])){
                    $push_count += floatval($lists[$datetime]['push_count']);
                }
                $lists[$datetime]['id'] = $value['id'];
                $lists[$datetime]['inter_id'] = $value['inter_id'];
                $lists[$datetime]['send_time'] = strtotime($datetime);
                $lists[$datetime]['push_count'] = $push_count;
            }
        }

        $lists = array_slice($lists,$offset,$page_size,true);

        if($this->input->get('debug')=='1') {
            echo '<pre>';
            echo $this->_db()->last_query();
//            print_r($lists);
            $a = array_slice($lists,$offset,$page_size,true);
            echo '<pre>';
            print_r($a);
        }
        $total = count($lists);

        if($format=='array'){
            $tmp= array();
            $field_config= $this->_get_field_config('grid',$table);
            foreach ($lists as $k=> $v){
                $vo = array();
                //判断combobox类型需要对值进行转换
                foreach($field_config as $sk=>$sv){
                    $vo[$sk] = $v[$sk];
                    if($field_config[$sk]['type']=='combobox') {
                        if( isset($field_config[$sk]['select'][$v[$sk]]))
                            $vo[$sk]= $field_config[$sk]['select'][$v[$sk]];
                        else $vo[$sk]= '--';
                    }

                    if( $field_config[$sk]['grid_function'] ) {
                        $funp= explode('|', $field_config[$sk]['grid_function']);
                        $fun= $funp[0];
                        $funp[0]= $v[$sk];
                        $funp[1] = $v['inter_id'];
                        $vo[$sk]= call_user_func_array (array($this, $fun), $funp);
                    }
                }//---

                $el= array_values($vo);
                $el['DT_RowId']= $v['id'];
                $tmp[]= $el;
            }
            $result= $tmp;
        }

        if(is_ajax_request()){
            return array(
                'total'=>$total,
                'send_total'=>$send_total,
                'num_rows'=>$num_rows,
                'draw'=> isset($params['draw'])? $params['draw']: 1,
                'data'=> $result,
                'recordsTotal'=>$num_rows,
                'recordsFiltered'=>$num_rows,
            );
        }else{
            return array(
                'send_total'=>$send_total,
                'num_rows'=>$num_rows,
                'total'=>$total,
                'data'=>$result,
                'page_size'=>$page_size,
                'page_num'=>$current_page,
            );
        }
    }

    /**
     * @param String $type   grid|form
     * 统一生成字段配置数组，赋予模板
     */
    public function _get_field_config($type='grid',$table='')
    {
        $data= array();
        if($type=='grid'){
            $show= array('send_time','push_count');
        } else {
            //有时需要取数据库以外的字段，如 密码确认字段，在模板手动添加
            $show= $this->_db()->list_fields($table);
        }

        $fields= $this->_attribute_labels();
        $fields_ui= $this->_attribute_ui();
        foreach ($show as $v){
            $data[$v]['label']= $fields[$v];

            if($type=='grid'){
                //grid所需配置信息
                if( array_key_exists($v, $fields_ui) ){
                    $data[$v]['grid_ui'] = isset($fields_ui[$v]['grid_ui'])?$fields_ui[$v]['grid_ui']: '';
                    $data[$v]['grid_width'] = isset($fields_ui[$v]['grid_width'])?$fields_ui[$v]['grid_width']: "";
                    $data[$v]['grid_function'] = isset($fields_ui[$v]['grid_function'])? $fields_ui[$v]['grid_function']: FALSE;
                    $data[$v]['function'] = isset($fields_ui[$v]['function'])? $fields_ui[$v]['function']: FALSE;
                    $data[$v]['type'] = isset($fields_ui[$v]['type'])?$fields_ui[$v]['type']: 'text';
                    if( $data[$v]['type']=='combobox' ) $data[$v]['select'] = $fields_ui[$v]['select'];
                }

            } else if($type=='form') {
                //form所需配置信息
                $data[$v]['js_config'] = isset($fields_ui[$v]['js_config'])? $fields_ui[$v]['js_config']: '';
                $data[$v]['input_unit'] = isset($fields_ui[$v]['input_unit'])? "<div class='input-group-addon'>{$fields_ui[$v]['input_unit']}</div>" : '';
                $data[$v]['form_ui'] = isset($fields_ui[$v]['form_ui'])? $fields_ui[$v]['form_ui']: '';
                $data[$v]['form_tips'] = !empty($fields_ui[$v]['form_tips'])? $fields_ui[$v]['form_tips']: NULL;
                $data[$v]['form_default'] = isset($fields_ui[$v]['form_default'])? $fields_ui[$v]['form_default']: NULL;
                $data[$v]['form_hide'] = isset($fields_ui[$v]['form_hide'])? $fields_ui[$v]['form_hide']: FALSE;
                $data[$v]['function'] = isset($fields_ui[$v]['function'])? $fields_ui[$v]['function']: FALSE;
                $data[$v]['type'] = isset($fields_ui[$v]['type'])? $fields_ui[$v]['type']: 'text';
                if( $data[$v]['type']=='combobox' ) $data[$v]['select'] = $fields_ui[$v]['select'];
                if( isset($fields_ui[$v]['form_type'])) $data[$v]['type'] = $fields_ui[$v]['form_type'];
            }
        }
        return $data;
    }

    public function _parsedatetime(){
        $data = func_get_args();
        $date = date('Y-m-d H:i',$data[0]);
        return $date;
    }

    /**
     * 后台模版表格表头字典
     * @return array
     */
    public function _attribute_labels(){
        return array(
            'send_time'=> '消息发送时间',
            'push_count'=> '推送人数',
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
    public function _attribute_ui()
    {
        /* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
        //type: numberbox数字框|combobox下拉框|text不写时默认|datebox
        return array(
            'send_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
                'grid_function'=>'_parsedatetime'
            ),
            'push_count' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            )
        );
    }

    /**
     * 后台管理的模糊查询的字段
     */
    public function like_fields($flag=1)
    {
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
        switch ($flag){
            case 2:
                $like_fields = array('c.name');
                break;
            case 3:
                $like_fields = array('a.use_credit','b.title');
                break;
            case 4:
                $like_fields = array('b.name');
                break;
            default:
                $like_fields = array('a.open_id');
        }
        return $like_fields;
    }


}