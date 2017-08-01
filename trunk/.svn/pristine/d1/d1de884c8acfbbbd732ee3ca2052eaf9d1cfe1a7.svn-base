<?php 
/**
 * 根据不同的场景赠送会员礼包
 * @author     luguihong
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Config_member_package_model extends MY_Model_Soma {

    //送券环节
    const TYPE_BUY_SUCCESS      = 1;//购买成功
    const TYPE_RECEIVED_SUCCESS = 2;//接收成功
    const TYPE_CONSUMER_SUCCESS = 3;//核销成功
    const TYPE_MAIL_SUCCESS     = 4;//邮寄成功

    //送券
    const SEND_SCOPE_ORDER = 1;//按订单
    const SEND_SCOPE_COUNT = 2;//按商品数量

    //适用商品
    const SCOPE_ALL     = 1;//全部适用
    const SCOPE_PART    = 2;//适用部分商品
    
    //记录表状态
    const RECORD_STATUS_PENDING     = 1;//待发送
    const RECORD_STATUS_SUCCESS     = 2;//发送成功
    const RECORD_STATUS_FAIL        = 3;//发送失败

    //获取状态标签
    public function get_status_can_label()
    {
        return array(
            self::STATUS_CAN_YES    => '正常',
            self::STATUS_CAN_NO     => '禁止',
        );
    }

    //获取送券类型标签
    public function get_type_label()
    {
        return array(
            self::TYPE_BUY_SUCCESS      => '购买成功',
            self::TYPE_RECEIVED_SUCCESS => '转赠成功',
            self::TYPE_CONSUMER_SUCCESS => '核销成功',
            self::TYPE_MAIL_SUCCESS     => '邮寄成功',
        );
    }

    //获取商品适用标签
    public function get_scope_label()
    {
        return array(
            self::SCOPE_ALL     => '全部适用',
            self::SCOPE_PART    => '部分商品',
        );
    }

    //获取送券标签
    public function get_send_scope_label()
    {
        return array(
            self::SEND_SCOPE_ORDER => '按订单',
            self::SEND_SCOPE_COUNT => '按商品数量',
        );
    }

	public function get_resource_name()
	{
		return 'Config_member_package';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
    /**
     * @return string the associated database table name
     */
    public function table_name($interId=NULL)
    {
        return $this->_shard_table('soma_config_member_package', $interId);
    }
    public function record_table_name($interId=NULL)
    {
        return $this->_shard_table('soma_config_member_package_record', $interId);
    }
    public function table_name_r($interId=NULL)
    {
        return $this->_shard_table_r('soma_config_member_package', $interId);
    }

	public function table_primary_key()
	{
	    return 'id';
	}
	
	public function attribute_labels()
	{
		return array(
            'id'=> '规则编号',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店ID',
            'name'=> '规则名称',
            'type'=> '赠送类型',
            'card_id'=> '礼包ID',
            'send_scope'=> '送券',
            'scope'=> '适用商品',
            'product_ids'=> '商品ID列表',
            'start_time'=> '开始时间',
            'end_time'=> '结束时间',
            'sort'=> '排序',
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
            'id',
            'inter_id',
            'hotel_id',
            'name',
            'type',
            'card_id',
            // 'send_scope',
            // 'scope',
            // 'product_ids',
            'start_time',
            'end_time',
            'sort',
            'status',
	    );
	}

	/**
	 * 后台UI输出定义函数
	 *   type: grid中的表头类型定义 
	 *   function: 数值转换函数 
	 *   select: form中的类型为 combobox时，定义其下来列表
	 grid专用属性名
	 *   grid_function: grid生效的数值转换，如'grid_function'=> 'show_price_prefix|￥',
	 *   grid_width: grid的宽度
	 *   grid_ui:  grid中的属性追加
	 form专用属性名
	 *   js_config: 用于 datetime, date 等js初始化中追加此参数
	 *   input_unit: input框中的单位提示
	 *   form_ui: form中的属性补充定义，如加disabled 在< input “disabled” / > 使元素禁用
	 *   form_tips: form中的label信息提示
	 *   form_hide: form中自动化输出中剔除
	 *   form_default: form中的默认值，请用字符类型，不要用数字
	 */
	public function attribute_ui()
	{
	    /* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
	    //type: numberbox数字框|combobox下拉框|text不写时默认|datebox
	    $Somabase_util= Soma_base::inst();
        $modules= config_item('admin_panels')? config_item('admin_panels'): array();
        
        /** 获取本管理员的酒店权限  */
        $hotels_hash= $this->get_hotels_hash();
        $publics = $hotels_hash['publics'];
        $hotels = $hotels_hash['hotels'];
        $filter = $hotels_hash['filter'];
        $filterH = $hotels_hash['filterH'];
        /** 获取本管理员的酒店权限  */

        /** 会员礼包拉取 **/
        $interId = $this->session->get_admin_inter_id();
        $this->load->library('Soma/Api_member');
        $api= new Api_member( $interId );
        $result= $api->get_token();
        $api->set_token($result['data']);
        $giftAll= $api->get_package_list();
        $packages = array();
        $data = (array)$giftAll['data'];
        foreach( $data as $k=>$v ){
            $packages[$v->package_id] = $v->name;
        }

	    return array(
            'id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|date|datetime|combobox|number|logo|email|url|price
                'select'=> $publics,
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                // 'type'=>'text', //textarea|text|date|datetime|combobox|number|logo|email|url|price
                'type'=>'combobox',
                'select'=> $hotels,
            ),
            'name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'type' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> '1',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',
                'select'=> $this->get_type_label(),
            ),
            'card_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $packages,
            ),
            'send_scope' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> '1',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',
                'select'=> $this->get_send_scope_label(),
            ),
            'scope' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> '1',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',
                'select'=> $this->get_scope_label(),
            ),
            'product_ids' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'start_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> date('Y-m-d H:i:s'),
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'end_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> date('Y-m-d H:i:s'),
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'sort' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' step="1" min="0" max="127" ',
                'form_default'=> '1',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'number',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $this->get_status_can_label(),//$Somabase_util::get_status_options(),
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
     * 验证表单提交是否正确
     *
     * @param      <type>  $data   表单提交数据
     *
     * @return     <type>  验证结果true|false
     */
    public function form_validation($data) 
    {
        $this->load->library('form_validation');
        $this->form_validation->set_data($data);
        $this->form_validation->set_rules('inter_id', '公众号', 'required');
        $this->form_validation->set_rules('hotel_id', '酒店ID', 'required');
        $this->form_validation->set_rules('name', '规则名', 'required');
        return $this->form_validation->run();
    }

    /**
     * 获取配置规则
     * @author     luguihong
     * 
     * @param      string   inter_id    公众号
     * @param      array    filter      条件数组
     * 
     * @return     array                返回配置规则列表数据
     */
    public function get_cnofig( $interId, $filter=array() )
    {
        $db = $this->_shard_db_r( 'iwide_soma_r' );

        if( count($filter)>0 ){
            foreach ($filter as $k=> $v)
            {
                if(is_array($v))
                {
                   $db->where_in($k, $v);
                } else {
                    $db->where($k, $v);
                }
            }
        }

        $time = date('Y-m-d H:i:s');

        $pk = $this->table_primary_key();
        $table = $this->table_name_r( $interId );
        return  $db->where( 'inter_id', $interId )
                ->where( 'status', self::STATUS_CAN_YES )
                ->where( 'start_time < ', $time )
                ->where( 'end_time > ', $time )
                ->order_by('sort DESC')
                ->get( $table )
                ->result_array();
    }

    /**
     * 添加一条赠送记录，后面使用计划任务执行
     * @author     luguihong
     * 
     * @param      string   inter_id    公众号
     * @param      array    filter      条件数组
     * 
     * @return     int                  返回添加ID
     */
    public function insert_record( $order, $interId, $data=array() )
    {
        if( !$data ){
            return FALSE;
        }

        $table = $this->record_table_name( $interId );
        return $order->_shard_db( $interId )->insert_batch( $table, $data );
    }

    /**
     * 获取记录待发送记录
     * @author     luguihong
     * 
     * @param      string   inter_id    公众号
     * @param      int      limit       获取条数
     * 
     * @return     array                返回记录列表数据
     */
    public function get_record( $interId, $limit=100 )
    {
        $recordTable = $this->record_table_name( $interId );
        return $this->_shard_db_r('iwide_soma_r')
                        ->where( 'inter_id', $interId )
                        ->where( 'status', self::RECORD_STATUS_PENDING )
                        ->limit( $limit )
                        ->get( $recordTable )
                        ->result_array();
    }

    /**
     * 更新记录状态
     * @author     luguihong
     * 
     * @param      string   inter_id    公众号
     * @param      int      limit       获取条数
     * 
     * @return     int                  返回影响行数
     */
    public function update_record( $interId, $id, $data )
    {
        $recordTable = $this->record_table_name( $interId );
        $this->_shard_db( $interId )
                        ->where( 'inter_id', $interId )
                        ->where( 'id', $id )
                        ->update( $recordTable, $data );
        return $this->_shard_db( $interId )->affected_rows();
    }

    /**
     * 赠送会员礼包
     * @author     luguihong
     * 
     * @param      string   inter_id        公众号
     * @param      string   openid          用户openid
     * @param      string   send_id         订单编号／赠送编号
     * @param      int      product_id      商品ID
     * @param      int      num             购买数量
     * @param      int      type            赠送类型
     * 
     * @return     bool     TRUE|FALSE
     */
    public function send_package( $interId, $openId, $sendId, $productId, $num=1, $type=0 )
    {
        $debug = TRUE;

        //查找出购买类型的赠送礼包
        $filter = array();
        $filter['type'] = $type;
        $res = $this->get_cnofig( $interId, $filter );
        if( $res ){

            $typeLabel = $this->get_type_label();
            if( $debug )$this->_write_log( '公众号：'.$interId
                                            .', 编号：'.$sendId
                                            .', 产品ID：'.$productId
                                            .', 赠送数量：'.$num
                                            .', 类型：'.$typeLabel[$type]
                                            .' 赠送会员礼包开始' 
                                        );

            //存在礼包
            $send = FALSE;
            $sendScope = $cardId = 0;
            foreach( $res as $v )
            {
                if( $v['scope'] == self::SCOPE_ALL )
                {
                    //全部适用
                    $sendScope = $v['send_scope'];
                    $cardId = $v['card_id'];
                    $send = TRUE;

                    if( $debug )$this->_write_log( '筛选中的规则ID：'.$v['id'] );
                    break;
                } elseif ( $v['scope'] == self::SCOPE_PART ){
                    //部分适用
                    if( $v['product_ids'] )
                    {
                        $productIds = explode( ',', $v['product_ids'] );
                        if( in_array( $productId, $productIds ) )
                        {
                            //存在商品里面
                            $sendScope = $v['send_scope'];
                            $cardId = $v['card_id'];
                            $send = TRUE;

                            if( $debug )$this->_write_log( '筛选中的规则ID：'.$v['id'] );
                            break;
                        } else {
                            if( $debug )$this->_write_log( '跳过不符合的规则ID：'.$v['id'].' 商品ID不存在配置ID列表里面' );
                        }
                    } else {
                        if( $debug )$this->_write_log( '规则ID：'.$v['id'].' 没有配置适用的商品' );
                    }
                }
            }

            if( $send && $cardId )
            {

                if( $debug )$this->_write_log( '赠送礼包ID：'.$cardId );

                //循环核销多条
                $this->load->library('Soma/Api_member');
                $api= new Api_member($interId);//放心住
                $result= $api->get_token();
                $api->set_token($result['data']);

                //调用会员接口核销
                switch ($type) 
                {
                    case self::TYPE_BUY_SUCCESS:
                        //购买成功
                        if( $sendScope == self::SEND_SCOPE_ORDER )
                        {
                            //按订单
                            $uuCode = $interId.$sendId.$productId.$type;
                            $memberResult = $api->package_use( $openId, $cardId, $uuCode );
                            if( isset( $memberResult['err'] ) && ($memberResult['err'] == 0 || $memberResult['err'] == 1033) )
                            {
                                if( $debug )$this->_write_log( '赠送成功' );
                                return TRUE;
                            } else {
                                if( $debug )$this->_write_log( '赠送失败：'.$memberResult['msg'] );
                            }
                            break;
                        } elseif ( $sendScope == self::SEND_SCOPE_COUNT ){
                            //按数量
                        } else {
                            break;
                        }
                        
                    case self::TYPE_RECEIVED_SUCCESS://礼物接收成功
                    case self::TYPE_CONSUMER_SUCCESS://核销成功
                    case self::TYPE_MAIL_SUCCESS://邮寄成功
                    default:
                        //赠送1份以上的
                        for( $i=1; $i <= $num; $i++ )
                        {
                            $uuCode = $interId.$sendId.$productId.$type.$i;
                            $memberResult = $api->package_use( $openId, $cardId, $uuCode );
                            if( isset( $memberResult['err'] ) && ($memberResult['err'] == 0 || $memberResult['err'] == 1033) )
                            {
                                if( $debug )$this->_write_log( '赠送第'.$i.'次成功' );
                            } else {
                                if( $debug )$this->_write_log( '赠送第'.$i.'次失败：'.$memberResult['msg'] );
                            }
                        }
                        return TRUE;//赠送多份的，不管成功或者失败，都返回TRUE

                        break;
                }

            }

            if( $debug )$this->_write_log( '编号：'.$sendId.' 赠送会员礼包结束' );
        }

        return FALSE;
    }

    //记录日志
    protected function _write_log( $content )
    {
        $path= APPPATH. 'logs'. DS. 'soma'. DS. 'send_member_card'. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $file= $path. date('Y-m-d_H'). '.txt';
        $this->write_log($content, $file);
    }

}
