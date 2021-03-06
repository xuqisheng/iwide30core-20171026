<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_refund_model extends MY_Model_Soma {
    public $business;
    /**
     * 订单对象
     * @var Sales_order_model
     */
    public $order= '';
    
    /**
     * 资产对象
     * @var Asset_customer_model
     */
    public $asset= '';

    /**
     * 保存主单信息(数组)
     * @var Array
    */
    public $save= array();
    
    /**
     * 细单对象(数组)
     * @var Array
    */
    public $item= array();
    
    const STATUS_WAITING = 1;
    const STATUS_PENDING = 2;
    const STATUS_REFUND  = 3;
    const STATUS_CANCLE  = 4;
    const STATUS_HOLDING = 5;
    const STATUS_PROCESSING = 6;

    const STATUS_ITEM_UNREFUND = 1;
    const STATUS_ITEM_PENDING = 2;
    const STATUS_ITEM_REFUND = 3;

    const REFUND_TYPE_WX = 1;//微信支付退款
    const REFUND_TYPE_CZ = 2;//储值支付退款
    const REFUND_TYPE_JF = 3;//积分支付退款
    
    public function get_status_label(){
        return array(
            self::STATUS_WAITING => '已申请',
            self::STATUS_PENDING => '已审核',
            self::STATUS_REFUND  => '已退款',
            self::STATUS_CANCLE  => '取消',
            self::STATUS_HOLDING => '挂起',
            self::STATUS_PROCESSING => '微信退款中',
        );
    }
    
    public function get_item_status_label(){
        return array(
            self::STATUS_ITEM_UNREFUND=> '未处理',
            self::STATUS_ITEM_PENDING=> '待处理',
            self::STATUS_ITEM_REFUND => '已处理',
        );
    }
    
    public function get_resource_name()
    {
        return 'Sales_refund';
    }
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * @return string the associated database table name
     */
    public function table_name($inter_id=NULL)
    {
        return $this->_shard_table('soma_sales_refund', $inter_id);
    }
    public function item_table_name($business, $inter_id=NULL)
    {
        $business= strtolower($business);
        return $this->_shard_table('soma_sales_refund_item_'. $business, $inter_id);
    }

    public function table_primary_key()
    {
        return 'refund_id';
    }
    
    public function attribute_labels()
    {
        return array(
            'refund_id'=> 'ID',
            'business'=> '所属业务',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店',
            'order_id'=> '订单ID',
            'openid'=> 'Openid',
            'nickname'=> '退款人',
            'subtotal'=> '商品总计',
            'refund_total'=> '退款总额',
            'create_time'=> '申请时间',
            'update_time'=> '更新时间',
            'refund_cause'=> '退款理由',
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
            'refund_id',
            'business',
            'inter_id',
            'hotel_id',
            'order_id',
            // 'openid',
            // 'nickname',
            'subtotal',
            'refund_total',
            'create_time',
            // 'update_time',
            // 'refund_cause',
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
        $Somabase_util= Soma_base::inst();
        $modules= config_item('admin_panels')? config_item('admin_panels'): array();
        /** 获取本管理员的酒店权限  */
        $hotels_hash= $this->get_hotels_hash();
        $publics = $hotels_hash['publics'];
        $hotels = $hotels_hash['hotels'];
        $filter = $hotels_hash['filter'];
        $filterH = $hotels_hash['filterH'];
        /** 获取本管理员的酒店权限  */

        return array(
            'refund_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'business' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $this->get_business_type(),
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                // 'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                // 'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                // 'type'=>'text',  //textarea|text|combobox|number|email|url|price
                'type'=>'combobox',
                'select'=> $publics,
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                // 'type'=>'text',  //textarea|text|combobox|number|email|url|price
                'type'=>'combobox',
                'select'=> $hotels,
            ),
            'order_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'number',   //textarea|text|combobox|number|email|url|price
            ),
            'openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'nickname' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                // 'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'subtotal' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_price_prefix|￥',
                'type'=>'price',    //textarea|text|combobox
            ),
            'refund_total' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_price_prefix|￥',
                'type'=>'price',    //textarea|text|combobox
            ),
            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                'form_default'=> date('Y-m-d H:i:s'),
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime', //textarea|text|combobox|number|email|url|price
            ),
            'update_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                'form_default'=> date('Y-m-d H:i:s'),
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime', //textarea|text|combobox|number|email|url|price
            ),
            'refund_cause' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'textarea', //textarea|text|combobox|number|email|url|price
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> self::get_status_label(),
            ),
        );
    }
    
    /**
     * grid表格中默认哪个字段排序，排序方向
     */
    public static function default_sort_field()
    {
        return array('field'=>'refund_id', 'sort'=>'desc');
    }
    
    /* 以上为AdminLTE 后台UI输出配置函数 */

    public function write_log( $content, $tmpfile )
    {
        //echo $tmpfile;die;
        $fp = fopen( $tmpfile, 'a');
    
        $content= str_repeat('-', 40). "\n[". date('Y-m-d H:i:s'). ']'
            ."\n". $content. "\n";
        fwrite($fp, $content);
        fclose($fp);
    }
    
    /*
     * $type        send/check/close
     * $write_xml   微信返回来的xml信息
    */
    public function save_refund($type, $write_xml= NULL)
    {
        //insert refund record; 
        if($write_xml){
            $path= APPPATH. 'logs'. DS. 'soma'. DS. 'refund'. DS . $type. DS;
            if( !file_exists($path) ) {
                @mkdir($path, 0777, TRUE);
            }
            $file= $path. date('Y-m-d'). '.txt';
            $this->write_log($write_xml, $file);
        }
    }

    /**
     * 保存退款订单
     * @param String $business
     * @param String $inter_id
     */
    public function order_save($business, $inter_id)
    {
        $debug= TRUE;
        try {
            $business= strtolower($business);
            
            $this->_shard_db($inter_id)->trans_begin ();
            
            $business= strtolower($business);

            //创建主订单信息
            $order = $this->order;
            
            // $table= $this->table_name($inter_id);
            // $item_table= $this->item_table_name($business, $inter_id);

            if($debug)$this->save_refund('save', '订单号：'.$order->m_get('order_id').'保存退款信息开始' );

            //根据业务类型初始化对象
            $item_object_name= "Sales_refund_item_{$business}_model";
            require_once dirname(__FILE__). DS. "$item_object_name.php";
            $object= new $item_object_name();

            //标记退款单状态、退款单明细

            $openid = $order->m_get('openid');
            $user_info = $this->db->where_in( 'openid', $openid )->select('id,nickname,inter_id')->get('fans' )->result_array();
            if( $user_info ){
                $nickname = $user_info[0]['nickname'];
            }else{
                $nickname = '';
            }

            $save = $this->save;
            //组装退款申请数据
            $data = array(
                'business'=> $business,
                'inter_id'=> $inter_id,
                'hotel_id'=> $order->m_get('hotel_id'),
                'order_id'=> $order->m_get('order_id'),
                'openid'=> $order->m_get('openid'),
                'nickname'=> $nickname,
                'subtotal'=> $order->m_get('subtotal'),
                'refund_total'=> $order->m_get('grand_total'),
                'create_time'=> date( 'Y-m-d H:i:s', time() ),
                // 'update_time'=> '更新时间',
                'refund_type'=> isset( $save['refund_type'] ) ? $save['refund_type'] : self::REFUND_TYPE_WX,//默认为微信退款
                'refund_cause'=> isset( $save['cause'] ) ? $save['cause'] : '',
                'retreat_batch_no'=> isset( $save['retreat_batch_no'] ) ? $save['retreat_batch_no'] : '',
                'status'=> isset( $save['status'] ) ? $save['status'] : self::STATUS_WAITING,
            );

            //保存主退款订单信息
            // $id = $this->m_sets($data)->m_save();

            // $id = $this->m_sets($data)->m_save();
            $table_name = $this->table_name($inter_id);
            $this->_shard_db( $inter_id )->insert( $table_name, $data );
            $insert_id = $this->_shard_db( $inter_id )->insert_id();
            if($debug)$this->save_refund('save', '订单号：'.$order->m_get('order_id').'保存退款主单id:'.$insert_id );

            $pk = $this->table_primary_key();
            $this->{$pk} = $insert_id;

            //创建各个退款细单
            $item_id = $object->save_item( $this, $inter_id );
            if($debug)$this->save_refund('save', '订单号：'.$order->m_get('order_id').'保存退款细单信息:'.$item_id );

            //标记业务订单状态位
            // $order->refund_status = $order::REFUND_ALL;//主单退款状态全部退
            // $order->consume_status = $order::CONSUME_PART;//主单消费状态为部分消费
            // $order->is_refund = $order::STATUS_ITEM_REFUNDING;//细单退款状态已申请
            $order_result = $order->order_refund_status( $business, $inter_id, $this );
            if($debug)$this->save_refund('save', '订单号：'.$order->m_get('order_id').'改变订单退款状态:'.json_encode( $order_result ) );

            //标记资产库细单数量、状态
            // $asset = $this->asset;
            // $asset->minus = true;//减操作
            // $asset->order_refund_status( $business, $inter_id, $this );
            
            //处理券
            $this->load->model('soma/Sales_order_discount_model');
            $discount_model= $this->Sales_order_discount_model;
            $discount_model->rollback_discount($order->m_get('order_id'), $inter_id);

            //处理分销
            $this->load->model('soma/Reward_benefit_model','RewardBenefitModel');
            $RewardBenefitModel = $this->RewardBenefitModel;
            $RewardBenefitModel->modify_benefit_queue_refund( $inter_id, $this->order );

            if($debug)$this->save_refund('save', '订单号：'.$order->m_get('order_id').'保存退款信息结束' );
            
            // $this->_shard_db($inter_id)->trans_complete();
            if( $insert_id && $item_id && $order_result ){

            }else{
                $this->_shard_db($inter_id)->trans_rollback();
                return FALSE;
            }
            
            if ($this->_shard_db($inter_id)->trans_status() === FALSE) {
                $this->_shard_db($inter_id)->trans_rollback();
                if($debug)$this->save_refund('save', '订单号：'.$order->m_get('order_id').'保存退款信息时，事务发生回滚' );
                return FALSE;
            
            } else {
                $this->_shard_db($inter_id)->trans_commit();

                // 发送后台通知
                if (ENVIRONMENT != 'dev') {
                    $this->load->model('soma/message_wxtemp_template_model', 'message_model');
                    if ($message = $this->message_model->getRefundAdminNoticeMessage($insert_id)) {
                        $this->load->model('hotel/hotel_notify_model');
                        $this->hotel_notify_model->insert_wxmsg_queue(
                            $inter_id,
                            $order->m_get('hotel_id'),
                            'soma',
                            16,
                            $message
                        );
                    }
                }

                return $insert_id;
            }
            
        } catch (Exception $e) {
            if($debug)$this->save_refund('save', '订单号：'.$order->m_get('order_id').'保存退款信息时，发生错误'.$e->getMessage() );
            return FALSE;
        }
    }
    /**
     * 审核退款订单
     * @param String $business
     * @param String $inter_id
     */
    public function order_check($business, $inter_id)
    {
        try {
            $business= strtolower($business);
            
            $this->_shard_db($inter_id)->trans_begin ();
            
            $table= $this->table_name($inter_id);
            $item_table= $this->item_table_name($business, $inter_id);

            //修改主订单的状态位
            $this->m_set( 'status', self::STATUS_PENDING )->m_save();
            
            
            //修改各个细单的状态
            
            
            //$this->_shard_db($inter_id)->trans_complete();
            
            if ($this->_shard_db($inter_id)->trans_status() === FALSE) {
                $this->_shard_db($inter_id)->trans_rollback();
                return FALSE;
            
            } else {
                $this->_shard_db($inter_id)->trans_commit();
                return TRUE;
            }
            
        } catch (Exception $e) {
             
            return FALSE;
        }
    }

    //微信关闭订单
    public function wx_order_close( $order_id, $business, $inter_id )
    {
        return $this->out_trade_no_encode( $order_id, $business, $inter_id, 'close' );
    }

    //发送微信退款请求
    public function wx_refund_send( $order_id, $business, $inter_id, $return_err_msg=FALSE )
    {
        return $this->out_trade_no_encode( $order_id, $business, $inter_id, 'send', $return_err_msg );
    }

    //发送储值退款请求
    public function cz_refund_send( $order_id, $business, $inter_id, $return_err_msg=FALSE )
    {
        //储值支付，发送数据给会员相应的接口
        $return = array();
        $return['status'] = 2;
        $return['message'] = '暂时未接通会员储值退款接口';
        return $return;
    }

    //发送积分退款请求
    public function jf_refund_send( $order_id, $business, $inter_id, $return_err_msg=FALSE )
    {
        //储值支付，发送数据给会员相应的接口
        $return = array();
        $return['status'] = 2;
        $return['message'] = '暂时未接通会员积分退款接口';
        return $return;
    }

    //发送微信查询退款状态
    public function wx_refund_check( $order_id, $business, $inter_id )
    {
        return $this->out_trade_no_encode( $order_id, $business, $inter_id, 'check' );
    }

    //查询微信订单
    public function wx_order_query( $order_id, $business, $inter_id )
    {
        return $this->out_trade_no_encode( $order_id, $business, $inter_id, 'query' );
    }

    //对订单号进行wx_out_trade_no_encode,如果返回码为TRANSACTION_ID_INVALID,再发送一次不进行wx_out_trade_no_encode
    public function out_trade_no_encode( $order_id, $business, $inter_id, $action, $return_err_msg=FALSE )
    {
        $is_true = FALSE;
        $result = $this->wx_refund( $order_id, $business, $inter_id, $action );
        if( isset( $result['status'] ) && $result['status'] == Soma_base::STATUS_TRUE ){
            if( $action == 'check' ){
                //如果是查询订单，返回退款状态和退款入账方
                return $result['data'];
            }
            // return TRUE;
            $is_true = TRUE;
        }else{
            if( isset( $result['err_code'] ) 
                && ( $result['err_code'] == 'TRANSACTION_ID_INVALID' //订单退款
                        || $result['err_code'] == 'REFUNDNOTEXIST' //订单查询
                        || $result['err_code'] == 'ERROR' ) ){//订单关闭
                $orderFlag = FALSE;//订单号不进行encode处理
                $result = $this->wx_refund( $order_id, $business, $inter_id, $action, $orderFlag );
                if( isset( $result['status'] ) && $result['status'] == Soma_base::STATUS_TRUE ){
                    // return TRUE;
                    $is_true = TRUE;
                }
            }
        }

        if( $return_err_msg == TRUE ){
            return $result;
        }else{
            return $is_true;
        }
    }

    /**
     * 接口退款
     * @param String $order_id                      订单号
     * @param String $business                
     * @param String $inter_id                      公众号
     * @param String $action    send/check/close    发送退款／查询退款/关闭订单
     * @param bool $orderFlag   true/false          订单号是否wx_out_trade_no_encode
     * @return bool
     * @author luguihong
     */
    public function wx_refund( $order_id, $business, $inter_id, $action='', $orderFlag=TRUE )
    {
        $debug= TRUE;  
        try{
            $return = array();

            if($debug)$this->save_refund($action, '订单号：'.$order_id.', 公众号：'.$inter_id.', 进入微信操作流程开始' );

            $order_id = isset( $order_id ) ? $order_id + 0 : '';
            if( !$order_id ){
                // Soma_base::inst()->show_exception($order_id. '找不到订单ID');
                if($debug)$this->save_refund($action, '订单号：'.$order_id.', 公众号：'.$inter_id. ', 找不到订单ID' );
                // return FALSE;
                $return['status'] = 2;
                $return['message'] = '找不到订单ID';
                return $return;
            }

            $business= strtolower($business);
            $action= strtolower($action);

            //订单信息
            $this->load->model('soma/sales_order_model');
            $sales_order_model = $this->sales_order_model;
            $sales_order_model->business = $business;
            
            //使用load的时候，如果没有加载到数据，就会返回NULL
            $sales_order_model = $sales_order_model->load( $order_id );
            if( !$sales_order_model ){
                // Soma_base::inst()->show_exception($order_id. 'sales_order_model初始化失败');
                if($debug)$this->save_refund($action, '订单号：'.$order_id.', 公众号：'.$inter_id. ', sales_order_model初始化失败' );
                // return FALSE;
                $return['status'] = 2;
                $return['message'] = 'sales_order_model初始化失败';
                return $return;
            }

            $order_detail = $sales_order_model->get_order_detail( $business, $inter_id );
            if( !$order_detail ){
                // Soma_base::inst()->show_exception($order_id. '找不到订单信息');
                if($debug)$this->save_refund($action, '订单号：'.$order_id.', 公众号：'.$inter_id. ', 找不到订单信息' );
                // return FALSE;
                $return['status'] = 2;
                $return['message'] = '找不到订单信息';
                return $return;
            }

            if( isset( $order_detail['grand_total'] ) && $order_detail['grand_total'] <= 0 ){
                if($debug)$this->save_refund($action, '订单号：'.$order_id.', 公众号：'.$inter_id. ', 订单实付金额为0, 不进行微信退款, 储值全额支付。' );
                // return FALSE;
                $return['status'] = 2;
                $return['message'] = '订单实付金额为0';
                return $return;
            }
            $this->load->model('iwidepay/iwidepay_model');
            $split_order = $this->iwidepay_model->get_iwidepay_order($order_id);
            if($split_order){
                //var_dump($order_detail);die;
                if($debug)$this->save_refund($action, '订单号：'.$order_id.', 公众号：'.$inter_id. ', 使用分账功能' );
                $refund_res = $this->refund_in_transfer($action,$sales_order_model,$order_detail,$orderFlag);
                return $refund_res;
            }
            //获取商户号(mch_id)
            $this->load->model('pay/Pay_model' );
            $pay_paras = $this->Pay_model->get_pay_paras( $inter_id );
            $mch_id = isset( $pay_paras['mch_id'] ) ? $pay_paras['mch_id'] : '';
            if( !$mch_id ){
                // Soma_base::inst()->show_exception('订单号：'.$order_id.', 公众号：'.$inter_id. ', 找不到商户号');
                if($debug)$this->save_refund($action, '订单号：'.$order_id.', 公众号：'.$inter_id. ', 找不到mch_id' );
                // return FALSE;
                $return['status'] = 2;
                $return['message'] = '找不到mch_id';
                return $return;
            }
            
            //获取appid
            $this->load->model('wx/publics_model');
            $public = $this->publics_model->get_public_by_id( $inter_id );
            $appid = isset( $public['app_id'] ) ? $public['app_id'] : '';
            if( !$appid ){
                // Soma_base::inst()->show_exception($order_id. '找不到公众账号ID');
                if($debug)$this->save_refund($action, '订单号：'.$order_id.', 公众号：'.$inter_id. ', 找不到appid' );
                // return FALSE;
                $return['status'] = 2;
                $return['message'] = '找不到appid';
                return $return;
            }

            //生成随机字符串、签名
            $this->load->model('pay/wxpay_model');
            $this->wxpay_model->setParameter("body", "退款申请");//设置参数使用此函数
            
            $nonce_str = $this->wxpay_model->createNoncestr();//获取随机字符串

            $out_trade_no = $orderFlag ? $sales_order_model->wx_out_trade_no_encode(
                $order_id, $order_detail['settlement'], $order_detail['business'] )
                : $order_detail['order_id'] + 0;

            // $order_id = $order_detail['order_id'] + 0;
            // $out_trade_no = $sales_order_model->wx_out_trade_no_encode(
            //     $order_id, $order_detail['settlement'], $order_detail['business'] );

            $refund_fee = $order_detail['grand_total'];

            $jsApiObj = array();
            $jsApiObj['appid']          = isset($pay_paras['app_id']) && !empty($pay_paras['app_id']) ? $pay_paras['app_id'] : $appid;//公众账号ID
            $jsApiObj['mch_id']         = $mch_id;//商户号

            //是否设置了子商户号
            if( isset( $pay_paras['sub_mch_id'] ) && !empty( $pay_paras['sub_mch_id'] ) ){
                $sub_mch_id = $pay_paras['sub_mch_id'];//子商户号

                //自商户分账-----------
                if( !empty($pay_paras['sub_mch_id_h_'. $order_detail['hotel_id']]) ){
                    $sub_mch_id = $pay_paras['sub_mch_id_h_'. $order_detail['hotel_id']];
                }
                
                $jsApiObj['sub_mch_id'] = $sub_mch_id;//子商户号
                
                $transaction_id = isset( $order_detail['transaction_id'] ) ? $order_detail['transaction_id'] : NULL;
                if( $action == 'send' ){
                    $jsApiObj['transaction_id'] = $transaction_id;
                }
            }

            $jsApiObj['nonce_str']      = $nonce_str;//随机字符串
            $jsApiObj['out_trade_no']   = $out_trade_no;//商户订单号

            $extras = array();
            //是发送退款请求
            if( $action == 'send' ){

                $select_mch_id = $mch_id;//isset($sub_mch_id) && !empty($sub_mch_id) ? $sub_mch_id : $mch_id;//选择商户号

                // 证书路径
                $extras = array();
                $extras['CURLOPT_CAINFO'] = realpath('../certs').DS."rootca_" . $select_mch_id . '.pem';
                $extras['CURLOPT_SSLCERT'] = realpath('../certs').DS."apiclient_cert_" . $select_mch_id . '.pem';
                $extras['CURLOPT_SSLKEY'] = realpath('../certs').DS."apiclient_key_" . $select_mch_id . '.pem';
                
                //判断证书是否存在
                if( !file_exists( $extras['CURLOPT_SSLCERT'] ) || !file_exists( $extras['CURLOPT_SSLKEY'] ) ){
                    if($debug)$this->save_refund($action, '订单号：'.$order_id.', 公众号：'.$inter_id. ', 没有找到证书, '.$select_mch_id );
                    // return FALSE;
                    $return['status'] = 2;
                    $return['message'] = '没有找到证书';
                    return $return;
                }
                
                $jsApiObj['op_user_id']     = $select_mch_id;//操作员
                $jsApiObj['out_refund_no']  = $out_trade_no;//商户退款单号
                $jsApiObj['refund_fee']     = $refund_fee*100;//退款金额
                $jsApiObj['total_fee']      = $refund_fee*100;//支付金额

                $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
            }elseif( $action == 'check' ){
                //发送查询退款请求
                $url = 'https://api.mch.weixin.qq.com/pay/refundquery';
            }elseif( $action == 'close' ){
                //微信关闭订单
                $url = 'https://api.mch.weixin.qq.com/pay/closeorder';
            }elseif( $action == 'query' ){
                //微信关闭订单
                $url = 'https://api.mch.weixin.qq.com/pay/orderquery';
            }else{
                if($debug)$this->save_refund('default', '订单号：'.$order_id.', 公众号：'.$inter_id. ', 发生未知错误，没有明确的操作信息' );
                // return FALSE;
                $return['status'] = 2;
                $return['message'] = '发生未知错误，没有明确的操作信息';
                return $return;
            }
            
            //获取签名
            $jsApiObj['sign'] = $this->wxpay_model->getSign( $jsApiObj, $pay_paras );

            //array to xml
            $xml = $this->wxpay_model->arrayToXml( $jsApiObj );

            if($debug)$this->save_refund($action, '订单号：'.$order_id.', 公众号：'.$inter_id.', 发送给微信的xml信息'."\r\n".$xml );

            //发送数据
            $this->load->helper('common_helper');
            $result = doCurlPostRequest( $url, $xml, $extras );

            if($debug)$this->save_refund($action, '订单号：'.$order_id.', 公众号：'.$inter_id.', 微信返回来的xml信息'."\r\n".$result );
            if($debug)$this->save_refund($action, '订单号：'.$order_id.', 公众号：'.$inter_id.', 进入微信操作流程结束' );

            //判断是否成功 
            $result = $this->wxpay_model->xmlToArray( $result );
            $return_code = isset( $result['return_code'] ) ? $result['return_code'] : '';
            $result_code = isset( $result['result_code'] ) ? $result['result_code'] : '';
            if( $return_code == 'SUCCESS' && $result_code == 'SUCCESS' ){
                if( $action == 'check' ){
                    //如果是查询订单，返回退款状态和退款入账方
                    $return['data'] = $result;
                    // return $result;
                }

                // return TRUE;
                $return['status'] = 1;
                $return['message'] = 'SUCCESS';
                return $return;
            }else{

                //以下代码修改是为了解决使用wx_out_trade_no_encode，微信返回TRANSACTION_ID_INVALID(订单号非法)
                if( isset( $result['err_code'] ) 
                    && ( $result['err_code'] == 'TRANSACTION_ID_INVALID' //订单退款
                        || $result['err_code'] == 'REFUNDNOTEXIST' //订单查询
                        || $result['err_code'] == 'ERROR' ) ){//订单关闭
                    $return['status'] = 2;
                    $return['err_code'] = $result['err_code'];
                    return $return;
                }

                //如果是交易未结算资金不足，就改用余额再发送一次退款请求
                if( isset( $result['err_code'] ) && $result['err_code'] == 'NOTENOUGH' ){
                    //如果是交易未结算资金不足，就改用余额再发送一次退款请求
                    unset($jsApiObj['sign']);
                    $jsApiObj['refund_account'] = 'REFUND_SOURCE_RECHARGE_FUNDS';//余额退款
                    $jsApiObj['sign'] = $this->wxpay_model->getSign( $jsApiObj, $pay_paras );

                    if($debug)$this->save_refund($action, '订单号：'.$order_id.', 公众号：'.$inter_id.', 使用余额退款, 进入微信操作流程开始' );

                    //array to xml
                    $xml = $this->wxpay_model->arrayToXml( $jsApiObj );

                    if($debug)$this->save_refund($action, '订单号：'.$order_id.', 公众号：'.$inter_id.', 使用余额退款, 发送给微信的xml信息'."\r\n".$xml );

                    //发送数据
                    $this->load->helper('common_helper');
                    $result = doCurlPostRequest( $url, $xml, $extras );

                    if($debug)$this->save_refund($action, '订单号：'.$order_id.', 公众号：'.$inter_id.', 使用余额退款, 微信返回来的xml信息'."\r\n".$result );
                    if($debug)$this->save_refund($action, '订单号：'.$order_id.', 公众号：'.$inter_id.', 使用余额退款, 进入微信操作流程结束' );

                    $result = $this->wxpay_model->xmlToArray( $result );
                    $return_code = isset( $result['return_code'] ) ? $result['return_code'] : '';
                    $result_code = isset( $result['result_code'] ) ? $result['result_code'] : '';
                    if( $return_code == 'SUCCESS' && $result_code == 'SUCCESS' ){
                        $return['status'] = 1;
                        $return['message'] = 'SUCCESS';
                        return $return;
                    }
                }

                // return FALSE;
                $return['status'] = 2;
                $return['message'] = isset( $result['err_code_des'] ) ? $result['err_code_des'] : '微信退款失败';
                return $return;
            }
        }catch( Exception $e ){
            if($debug)$this->save_refund($action, '订单号：'.$order_id.', 公众号：'.$inter_id.'进入微信操作流程发生错误'.$e->getMessage() );
            // return FALSE;
            $return['status'] = 2;
            $return['message'] = $e->getMessage();
            return $return;
        }
    }

    /**
     * 分账退款逻辑 20170606 situguanchen
     */
    public function refund_in_transfer($action,$sales_order_model,$soma_order,$orderFlag){
        $this->load->model('iwidepay/Iwidepay_model');
        $return = array('status'=>0,'data'=>array(),'message'=>'');
        if($action == 'send'){//发起退款
            $out_trade_no = $orderFlag ? $sales_order_model->wx_out_trade_no_encode(
                $soma_order['order_id'], $soma_order['settlement'], $soma_order['business'] )
                : $soma_order['order_id'] + 0;
            //分账退款
            $this->load->model ( 'iwidepay/iwidepay_model' );
            $iwidepay_refund = array(
                'orderDate' => date('Ymd'),
                'orderNo' => $out_trade_no.time().rand(1000,9999),
                'requestNo' => md5(time()),
                'transAmt' => $soma_order['grand_total'] * 100,//单位：分
                'returnUrl'=>'http://cmbcpay.jinfangka.com/index.php',
                'refundReson' => '商城退款',
            );
            if(1)$this->save_refund($action, '订单号：'.$soma_order['order_id'].', 公众号：'.$soma_order['inter_id']. ', 组装分账退款数据'.json_encode($iwidepay_refund) );
            $res = $this->iwidepay_model->refund($iwidepay_refund,$soma_order['order_id']);
            if(1)$this->save_refund($action, '订单号：'.$soma_order['order_id'].', 公众号：'.$soma_order['inter_id']. ', 分账退款数据返回结果'.json_encode($res) );
             //var_dump($res);die;
            if(isset($res['respCode']) && $res['respCode'] === '0000'){
                $return['status'] = 1;
                $return['message'] = 'SUCCESS';
                return $return;
            }elseif(isset($res['respCode']) && ($res['respCode'] == 'P000'||$res['respCode'] == '9999'||$res['respCode'] == '9997'||$res['respCode'] == '0028')){
                //中间状态 记录日志
                MYLOG::w('商城分账退款返回中间状态:按成功处理 订单号：'.$soma_order['order_id'].', 公众号：'.$soma_order['inter_id'] , 'iwidepay/refund_soma');
                if(1)$this->save_refund($action, '订单号：'.$soma_order['order_id'].', 公众号：'.$soma_order['inter_id']. ', 商城分账退款返回中间状态:按成功处理');
                $return['status'] = 1;
                $return['message'] = 'SUCCESS';
                return $return;               
            }elseif(isset($res['respCode']) && ($res['respCode'] == '0042' || $res['respCode'] == '0066')){
                MYLOG::w('商城分账退款返回（微信没钱状态）成功处理 订单号：'.$soma_order['order_id'].', 公众号：'.$soma_order['inter_id'] , 'iwidepay/refund_soma');
                if(1)$this->save_refund($action, '订单号：'.$soma_order['order_id'].', 公众号：'.$soma_order['inter_id']. ', 商城分账退款返回微信没钱状态:按成功处理');
                $return['status'] = 1;//微信没钱 也返回成功
                $return['message'] = 'SUCCESS';
                return $return;
            }else{
                $return['status'] = 2;
                $return['message'] = isset($res['respDesc'])?$res['respDesc']:'error';
                return $return;      
            }
        }elseif($action == 'check' || $action == 'query'){//目前商城只有退款查询，这里也只做退款查询
            $res = $this->Iwidepay_model->order_query($soma_order['order_id']);
            if(1)$this->save_refund($action, '订单号：'.$soma_order['order_id'].', 公众号：'.$soma_order['inter_id']. ', 商城分账退款订单查询结果' . json_encode($res));
            if($res){
                if(isset($res['origRespCode']) && $res['origRespCode'] === '0000'){//原状态 这里只判断退款金额 
                    if($res['refundAmt'] > 0){
                        $return['data'] = array('refund_status_0'=>array('SUCCESS'));//兼容微信支付查询订单
                        $return['status'] = 1;
                        $return['message'] = 'SUCCESS' ;
                        return $return; 
                    }else{
                        $return['status'] = 1;
                        $return['message'] = 'PROCESSING' ;
                        return $return; 
                    }
                }elseif($res['respCode'] == 'P000' || $res['respCode'] == '9999'|| $res['respCode'] == '9997'|| $res['respCode'] == '0028'){
                    $return['status'] = 1;
                    $return['message'] = 'PROCESSING';
                    return $return; 
                }else{
                    $return['status'] = 2;
                    $return['message'] = $res['respCode'];
                    return $return;
                }
            }else{
                $return['status'] = 2;
                $return['message'] = '查询失败';
                return $return;  
            }
        }elseif($action == 'close'){//关闭订单
            $res = $this->Iwidepay_model->close_order($soma_order['order_id']);//兼容微信支付关闭订单
            if(1)$this->save_refund($action, '订单号：'.$soma_order['order_id'].', 公众号：'.$soma_order['inter_id']. ', 商城分账订单关闭结果' . json_encode($res));
            if($res){
                $return['status'] = 1;
                $return['message'] = 'SUCCESS';
                return $return;
            }else{
                $return['status'] = 2;
                $return['message'] = $res['respDesc'];
                return $return;
            }
        }
    }

    /**
     * 接口退款给客户  20160822 luguihong 这里的事务执行失败，事务实例不一致。
     * @param String $business
     * @param String $inter_id
     */
    public function order_payment( $business, $inter_id )
    {
        $debug= TRUE; 
        //调取接口退款成功后，标记退款订单状态即可
        try {
            //查询退款状态
            // $result = $this->check_wx_refund( $inter_id );
            // $return_code = isset( $result['return_code'] ) ? $result['return_code'] : '';
            // if( $return_code == 'FAIL' ){
            //     var_dump( $result['return_msg'] );
            //     exit;
            // }elseif( $return_code == 'SUCCESS' ){
            //     return $this->order_payment( $business, $inter_id );
            // }
            

            //以下为业务处理
            $business= strtolower($business);

            $this->_shard_db($inter_id)->trans_begin ();

            $order = $this->order;
            $orderId = $order->m_get('order_id');

            if($debug)$this->save_refund('send', '订单号：'.$orderId.'退款成功后续操作开始' );

            //标记退款单状态、退款单明细
            $data = array();
            $data['update_time'] = date( 'Y-m-d H:i:s', time() );
            $data['status'] = self::STATUS_REFUND;//已退款
            // $result = $this->m_sets( $data )->m_save();
            $table_name = $this->table_name( $inter_id );
            $this->_shard_db( $inter_id )->where( array('order_id'=>$orderId,'inter_id'=>$inter_id,'refund_id'=>$this->m_get('refund_id')) )->update( $table_name, $data );
            $result = FALSE;
            if( $this->_shard_db( $inter_id )->affected_rows() > 0 ){
                $result = TRUE;
            }
            if($debug)$this->save_refund('send', '订单号：'.$orderId.'退款成功后续操作, 改变退款主单状态:'.$result );
            
            //标记业务订单状态位、细单状态
            // $order->refund_status = $order::REFUND_ALL;//主单退款状态全部退
            // $order->is_refund = $order::STATUS_ITEM_REFUNDED;//细单退款状态已退款
            $order_result = $order->order_refund_status( $business, $inter_id, $this );
            if($debug)$this->save_refund('send', '订单号：'.$orderId.'退款成功后续操作, 改变订单细单是否退款状态:'.$order_result );
             
            //标记资产库细单数量、状态
            $asset = $this->asset;
            $asset->minus = true;//减操作
            $asset_result = $asset->order_refund_status( $business, $inter_id, $this );
            if($debug)$this->save_refund('send', '订单号：'.$orderId.'退款成功后续操作, 改变资产数量:'.$asset_result );

            //退款要不要标记消费码的状态
            //改变码的状态为已使用
            $consumer_code_object_name= "Consumer_code_model";
            require_once dirname(__FILE__). DS. "Consumer_code_model.php";
            $Consumer_code_model= new $consumer_code_object_name();
            $filter = array();
            $filter['order_id'] = $orderId;
            $code_result = FALSE;
            if( $filter['order_id'] ){
                $code_result = $Consumer_code_model->consume_code_by_refund( $filter, $inter_id, $this );
                if($debug)$this->save_refund('send', '订单号：'.$orderId.'退款成功后续操作, 改变消费码状态:'.$code_result );
            }

            //处理券
            // $this->load->model('soma/Sales_order_discount_model');
            // $discount_model= $this->Sales_order_discount_model;
            // $discount_model->rollback_discount($orderId, $inter_id);

            //处理分销
            // $this->load->model('soma/Reward_benefit_model','RewardBenefitModel');
            // $RewardBenefitModel = $this->RewardBenefitModel;
            // $RewardBenefitModel->modify_benefit_queue_refund( $inter_id, $this->order );
            
            if($debug)$this->save_refund('send', '订单号：'.$orderId.'退款成功后续操作结束' );

            if( $result && $order_result && $asset_result  && $code_result ){

            }else{
                $this->_shard_db($inter_id)->trans_rollback();
                return FALSE;
            }

            // $this->_shard_db($inter_id)->trans_complete();
            if ($this->_shard_db($inter_id)->trans_status() === FALSE) {
                if($debug)$this->save_refund('send', '订单号：'.$orderId.'退款成功后续操作, 事物发生回滚' );
                $this->_shard_db($inter_id)->trans_rollback();
                return FALSE;
        
            } else {
                $this->_shard_db($inter_id)->trans_commit();
                return TRUE;
            }
        
        } catch (Exception $e) {
            if($debug)$this->save_refund('send', '订单号：'.$orderId.'退款成功后续操作出现错误'.$e->getMessage() );
            return FALSE;
        }
        
    }
    
    /**
     * 退款订单取消处理
     * @param String $business
     * @param String $inter_id
     */
    public function order_cancel($business, $inter_id)
    {
        $debug= TRUE;
        try {
            $business= strtolower($business);
            if($debug)$this->save_refund('cancel', '拒绝退款申请开始' );

            $this->_shard_db($inter_id)->trans_begin ();

            //标记退款单状态、退款单明细
            $data = array();
            $data['status'] = self::STATUS_CANCLE;//取消
            $result = $this->m_sets( $data )->m_save();
            if($debug)$this->save_refund('cancel', '修改退款主单状态：'.$result );
            
            //标记业务订单状态位、细单状态
            $order = $this->order;
            // $order->refund_status = $order::REFUND_PENDING;//主单退款状态无退款
            // $order->is_refund = $order::STATUS_ITEM_UNREFUND;//细单退款状态无申请
            $order_reslut = $order->order_refund_status( $business, $inter_id, $this );
            if($debug)$this->save_refund('cancel', '修改订单状态：'.$order_reslut );
             
            //标记资产库细单数量、状态
            // $asset = $this->asset;
            // $asset->plus = TRUE;
            // $asset->order_refund_status( $business, $inter_id, $this );

            //处理分销
            $this->load->model('soma/Reward_benefit_model','RewardBenefitModel');
            $RewardBenefitModel = $this->RewardBenefitModel;
            $RewardBenefitModel->modify_benefit_queue_refund_refuse( $inter_id, $this->order );

            if( $result && $order_reslut )
            {

            } else {
                $this->_shard_db($inter_id)->trans_rollback();
                return FALSE;
            }

            if($debug)$this->save_refund('cancel', '拒绝退款申请结束' );
                    
            //$this->_shard_db($inter_id)->trans_complete();
            if ($this->_shard_db($inter_id)->trans_status() === FALSE) {
                $this->_shard_db($inter_id)->trans_rollback();
                return FALSE;
                 
            } else {
                $this->_shard_db($inter_id)->trans_commit();
                return TRUE;
            }
             
        } catch (Exception $e) {
             
            return FALSE;
        }
    }
    
    /**
     * 退款订单挂起处理
     * @param String $business
     * @param String $inter_id
     */
    public function order_holding($business, $inter_id)
    {
       try {
            $business= strtolower($business);
            
            $this->_shard_db($inter_id)->trans_begin ();
            
            //调用借口，原路返回
            
            
            //标记退款单状态、退款单明细
            $data = array();
            $data['status'] = self::STATUS_HOLDING;//挂起
            $this->m_sets( $data )->m_save();
            
            //标记业务订单状态位、细单状态
//             $order = $this->order;
//             $order->refund_status = $order::REFUND_ALL;//主单退款状态全部退
//             $order->is_refund = $order::STATUS_ITEM_REFUNDED;//细单退款状态已退款
//             $order->order_refund_status( $business, $inter_id, $this );
             
            //标记资产库细单数量、状态
//             $asset = $this->$asset;
//             $asset->order_refund_status( $business, $inter_id, $this );
        
            //$this->_shard_db($inter_id)->trans_complete();
            if ($this->_shard_db($inter_id)->trans_status() === FALSE) {
                $this->_shard_db($inter_id)->trans_rollback();
                return FALSE;
        
            } else {
                $this->_shard_db($inter_id)->trans_commit();
                return TRUE;
            }
        
        } catch (Exception $e) {
             
            return FALSE;
        }
        
    }

    /**
     * 获取显示订单详情
     * Usage: $model->load($id)->get_order_detail();
     */
    public function get_order_detail($business, $inter_id)
    {
        $primary_key= $this->table_primary_key();
        if( !$this->m_get($primary_key) ){
            throw new Exception('Please Load Model first.');
        }
        $detail= $this->m_data();
        $detail['items']= $this->get_order_items($business, $inter_id);
        return $detail;
    }

    /**
     * 显示订单细单明细
     * Usage: $model->load($id)->get_order_items();
     */
    public function get_order_items($business, $inter_id)
    {
        $primary_key= $this->table_primary_key();
        if( !$this->m_get($primary_key) ){
            throw new Exception('Please Load Model first.');
        }
        //根据业务类型初始化对象
        $business= strtolower($business);
        $item_object_name= "Sales_refund_item_{$business}_model";
        require_once dirname(__FILE__). DS. "$item_object_name.php";
        $object= new $item_object_name();
        
        //细单订单保存支付
        $result= $object->get_order_items($this, $inter_id);
        return $result;
        
    }

    /**
     * 获取显示退款订单详情
     * $model->get_refund_order_detail_byOrderId( $order_id, $inter_id);
     * @author luguihong@mofly.cn
     */
    public function get_refund_order_detail_byOrderId( $order_id, $inter_id )
    {
        $order_id = isset( $order_id ) ? $order_id + 0 : '';

        if( !$order_id ){
            return FALSE;
        }
        
        $pk = $this->table_primary_key();

        $where = array();
        $where['order_id'] = $order_id;
        $where['inter_id'] = $inter_id;
        
        $table_name = $this->table_name($inter_id);

        $result = $this->_shard_db_r('iwide_soma_r')
                        ->where( $where )
                        ->order_by( $pk, 'desc')
                        ->limit(1)
                        ->get( $table_name )
                        ->result_array();

        if( $result ){
            return $result[0];
        }else{
            return FALSE;
        }
    }

    //拼团失败，生成退款单操作
    public function groupon_fail( $order_id, $business, $inter_id )
    {
        $debug= TRUE;  //开启团购失败退款节点记录

        if($debug)$this->save_refund('groupon_send', '订单号：'.$order_id.', 公众号：'.$inter_id.', 拼团失败进行退款操作开始' );

        //获取订单号
        if( !$order_id ){
            $this->save_refund('groupon_send', $order_id.' not exists' );
            return FALSE;
        }

        //之前没有退款或者退款失败
        $rs = $this->wx_refund_send( $order_id, $business, $inter_id );
        if( !$rs ){
            if($debug)$this->save_refund('groupon_send', '订单号：'.$order_id.', 公众号：'.$inter_id.', 拼团失败进行退款操作, 退款失败' );
            return FALSE;
        }
       
        //微信已经退款或者退款中

        //检查是否已经存在退款单
        $is_exists = $this->check_refund_order_is_exists( $order_id, $inter_id );
        if( $is_exists ){
            return TRUE;
        }
        
        //加载订单model
        $this->load->model('soma/Sales_order_model','sales_order_model');
        $sales_order_model = $this->sales_order_model;
        $sales_order_model->business = $business;

        //获取详情
        $sales_order_model = $sales_order_model->load($order_id);
        if( !$sales_order_model ){
            if($debug)$this->save_refund('groupon_send', '订单号：'.$order_id.', 公众号：'.$inter_id.', 拼团失败进行退款操作, sales_order_model 初始化失败' );
            return FALSE;
        }

        //获取订单详情
        $order_detail = $sales_order_model->get_order_detail($business,$inter_id);
        if( !$order_detail ){
            if($debug)$this->save_refund('groupon_send', '订单号：'.$order_id.', 公众号：'.$inter_id.', 拼团失败进行退款操作, 获取订单详情信息失败' );
            return FALSE;
        }

        //需要保存到退款主单的信息
        $save = array();

        //退款原因
        $save['cause'] = '拼团失败';

        //设置退款参数
        $refund = array('refund_status'=>$sales_order_model::REFUND_ALL, //主单退款状态全部退
                                'status'    => $sales_order_model::STATUS_REFUND,//主单状态设置为退款
                    );
        $sales_order_model->refund = $refund;

        $refund_item = array( 'is_refund'=>$sales_order_model::STATUS_ITEM_REFUNDED );//细单退款状态已退款
        $sales_order_model->refund_item = $refund_item;
        $this->order = $sales_order_model;

        $this->product = $order_detail['items'];
        $this->business = $business;

        //退款处理
        $save['status'] = self::STATUS_REFUND;
        $this->save = $save;
        $result = $this->order_save( $business, $inter_id );
        if( $result ){
            //拼团申请退款成功
            
            if($debug)$this->save_refund('groupon_send', '订单号：'.$order_id.', 公众号：'.$inter_id.', 拼团失败进行退款操作结束' );

            //发送模版消息
            $this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
            $MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;

            $refundInfo = $this->check_refund_order_is_exists( $order_id, $inter_id );
            $refund_id = isset( $refundInfo['refund_id'] ) ? $refundInfo['refund_id'] : '';

            $this->load( $refund_id );
            $openid = $refundInfo['openid'];//发送给那个用户
            $MessageWxtempTemplateModel->send_template_by_refund_success( $this, $openid, $inter_id, $business);

            return TRUE;
        }else{
            if($debug)$this->save_refund('groupon_send', '订单号：'.$order_id.', 公众号：'.$inter_id.', 拼团失败进行退款操作, 生成退款单失败' );
            return FALSE;
        }
    }

    //检查退款主单是否存在
    public function check_refund_order_is_exists( $order_id, $inter_id )
    {
        $table_name = $this->table_name( $inter_id );
        return $this->_shard_db_r('iwide_soma_r')
                    ->where( array( 'order_id' => $order_id ) )
                    ->select('refund_id,openid')
                    ->get( $table_name )
                    ->row_array();
    }

    public function filter( $params=array(), $select= array(), $format='array' ) {
        $ori_data = parent::filter($params, $select, $format);
        return $this->get_new_backend_grid_data($ori_data);
    }

    public function get_new_backend_grid_data($ori_data) {
        $ro_ids = $o_ids = array();
        foreach ($ori_data['data'] as $row) {
            $ro_ids[] = $row['DT_RowId'];
        }
        $ro_data = $this->find_all(array('refund_id' => $ro_ids));

        foreach ($ro_data as $row) {
            $o_ids[] = $row['order_id'];
        }

        $o_filter = array('where' => array('order_id' => $o_ids));
        $this->load->model('soma/Sales_order_model', 'somaOrderModel');
        $o_data = $this->somaOrderModel->get_order_collection($o_filter);

        $o_hash = array();
        foreach ($o_data as $row) {
            $o_hash[$row['order_id']] = $row;
        }

        $fmt_data = array();
        foreach ($ro_data as $row) {
            $fmt_data[$row['refund_id']] = $row;
            $order_info = isset($o_hash[$row['order_id']]) ? $o_hash[$row['order_id']] : array();
            $fmt_data[$row['refund_id']]['order_info'] = $order_info;
        }

        $new_data = $ori_data;
        foreach ($ori_data['data'] as $key => $row) {
            $new_data['data'][$key]['new_info'] = array();
            if(isset($fmt_data[$row['DT_RowId']])) {
                $new_data['data'][$key]['new_info'] = $fmt_data[$row['DT_RowId']];
            }
        }
        
        // var_dump($new_data);exit;
        return $new_data;
    }

    /**
     * 获取某一时间段内的订单数量
     *
     * @param      string  $inter_id   公众号
     * @param      string  $s_time     开始时间
     * @param      array   $hotel_ids  酒店id集合
     * @param      int     $status     订单状态
     *
     * @return     int     订单数量
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function getOrderQty(
        $inter_id,
        $start_time,
        $hotel_ids = array(),
        $status = self::STATUS_WAITING)
    {
        $this->load->model('soma/shard_config_model', 'model_shard_config');
        $CI = &get_instance();
        $CI->db_shard_config = $this->model_shard_config->build_shard_config($inter_id);
        $CI->current_inter_id = $inter_id;

        $this->soma_db_conn_read->from($this->table_name($inter_id));
        $this->soma_db_conn_read->where('inter_id', $inter_id);
        $this->soma_db_conn_read->where('create_time >=', $start_time);
        if (!empty($hotel_ids)) {
            $this->soma_db_conn_read->where_in('hotel_id', $hotel_ids);
        }
        if (is_array($status)) {
            $this->soma_db_conn_read->where_in('status', $status);
        } else {
            $this->soma_db_conn_read->where('status', $status);
        }

        return $this->soma_db_conn_read->count_all_results();
    }
    
}
