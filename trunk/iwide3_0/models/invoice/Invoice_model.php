<?php
class Invoice_model extends MY_Model {
    public $default_zz_content=array (
            'code' => '',
            'bank' => '',
            'account' => '',
            'phonecall' => '',
            'address' => ''
    );//增值税填写内容默认值
    function __construct() {
        parent::__construct ();
    }
    const TAB_CHECK_OUT = 'hotel_check_out';
    const TAB_INVOICE_INFO = 'hotel_invoice_info';
    const TAB_INVOICE_LIST = 'hotel_invoice_get_list';
    const TAB_H_ORDERS = 'hotel_orders';
    const TAB_H_OD = 'hotel_order_additions';
    const TAB_H_OT = 'hotel_order_items';
    const TAB_HNR = 'hotels_notify_reg';
    const TAB_HNC = 'hotels_notify_config';

    function _load_db($type='main') {
    	switch ($type){
    		case 'read':
    			if (!isset($this->_read_db)){
    				$this->_read_db=$this->load->database('iwide_r1',true);
    			}
    			return $this->_read_db;
    			break;
    		default:
    			return $this->db;
    			break;
    	}
    }


    function new_checkout($data){

        $db = $this->_load_db ();

        return $db->insert ( self::TAB_CHECK_OUT, $data );

    }


    function new_invoice($data){

        $db = $this->_load_db ();

        if($db->insert ( self::TAB_INVOICE_INFO, $data )){

            return $db->insert_id();

        }

        return 0;

    }
    function add_invoice($inter_id,$openid,$title,$type,$content=array()){
        $this->load->helper('string');
        if (! $title)
            return array (
                    's' => 0,
                    'errmsg' => '请填写发票标题'
            );
            $data = array (
                    'openid' => $openid,
                    'inter_id' => $inter_id,
                    'title' => $title,
                    'status' => 1,
                    'createtime' => date ( 'Y-m-d H:i:s', time () )
            );
            if ($type == 1) {
                $data ['type'] = 1;
            } else if ($type == 2) { // 增值税发票
                foreach ( $this->default_zz_content as $k => $c ) {
                    if (! empty ( $content [$k] )) {
                        $data ['content'] [$k] = $content [$k];
                    } else {
                        return array (
                                's' => 0,
                                'errmsg' => '增值票信息不完整'
                        );
                    }
                }
                $data ['content'] = json_encode ( $data ['content'] );
                $data ['type'] = 2;
            } else {
                return array (
                        's' => 0,
                        'errmsg' => '发票类型错误'
                );
            }
            $db = $this->_load_db ();
            if ($db->insert ( self::TAB_INVOICE_INFO, $data )) {
                return array (
                        's' => 1,
                        'invoice_id' => $db->insert_id ()
                );
            }
            return array (
                    's' => 0,
                    'errmsg' => '添加失败'
            );
    }


    function my_invoice($inter_id,$openid,$status = 1){

        $db = $this->_load_db ('read');
        $db->where ( 'inter_id', $inter_id );
        $db->where ( 'openid', $openid );
        $db->where ( 'status', $status );

        $result = $db->get ( self::TAB_INVOICE_INFO )->result_array();

        return $result;

    }


    function  getInvoiceById($openid,$invoice_id){

        $db = $this->_load_db ('read');
        $db->where ( 'invoice_id', $invoice_id );
        $db->where ( 'openid', $openid );

        $result = $db->get ( self::TAB_INVOICE_INFO )->row_array();

        return $result;

    }


    function  getInvoiceListByOid($orderid){

        $db = $this->_load_db ('read');
        $db->where ( 'orderid', $orderid );

        $result = $db->get ( self::TAB_INVOICE_LIST )->row_array();

        return $result;

    }

    function  getLastInvoiceList($openid){

        $db = $this->_load_db ('read');
        $db->where ( 'openid', $openid );
        $db->order_by('invoice_list_id', 'DESC');
        $result = $db->get ( self::TAB_INVOICE_LIST )->row_array();

        return $result;

    }


    function book_invoice($data){

        $db = $this->_load_db ();

        if($db->insert ( self::TAB_INVOICE_LIST, $data )){
            return $db->insert_id();
        }else{
            return 0;
        }

    }


    function check_book_out($openid,$time){

        $db = $this->_load_db ('read');

        $sql = "SELECT
                    *
                FROM
                  `iwide_hotel_check_out`
                WHERE
                    TO_DAYS(create_time)=TO_DAYS($time)
                AND
                   openid = '{$openid}'
        ";

       return  $db->query($sql)->row_array();

    }


    function check_order_invoice($orderid){

        $db = $this->_load_db ('read');
        $db->select ( 'invoice_content' );
        $db->where ( 'orderid', $orderid );

        $result = $db->get ( self::TAB_INVOICE_LIST )->row_array();

        return json_decode($result['invoice_content']);


    }


    function getOrderById($oid,$inter_id){

        $db = $this->_load_db ('read');
        $db->where ( 'id', $oid );
        $db->where ( 'inter_id', $inter_id );

        return $db->get ( self::TAB_H_ORDERS )->row_array();

    }


    function getOrderByOrderid($orderid,$inter_id){

        $db = $this->_load_db ('read');
        $db->where ( 'orderid', $orderid );
        $db->where ( 'inter_id', $inter_id );

        return $db->get ( self::TAB_H_ORDERS )->row_array();

    }

    function getHotelName($orderid){

        $db = $this->_load_db ('read');

        $sql="
                SELECT
                     t2.name
                FROM
                    `iwide_hotel_orders` t1,
                    `iwide_hotels` t2
               WHERE
                     t1.orderid = '{$orderid}'
               AND
                     t1.inter_id = t2.inter_id
               AND
                    t1.hotel_id = t2.hotel_id
        ";

        return $db->query($sql)->row_array();

    }


    function getOrderItem($orderid){

        $db = $this->_load_db ('read');
        $db->where ( 'orderid', $orderid );

        return $db->get ( self::TAB_H_OT )->row_array();

    }


    function update_order_invoice($inter_id,$openid,$orderid,$is_invoice=2){

        $db = $this->_load_db ();
        $db->where ( 'orderid', $orderid );
        $db->where ( 'inter_id', $inter_id );
        $db->where ( 'openid', $openid );

        $db->update( self::TAB_H_ORDERS,array('is_invoice'=>$is_invoice));

    }


 function get_list($condit,$count = false){
        $db = $this->_load_db ('read');
        $db->select ( 'c.*,o.price oprice,o.startdate,o.enddate,o.paytype,o.name oname,o.tel,o.paid,l.amount,l.invoice_content' );
        if(isset($condit['inter_id']) && !empty($condit['inter_id'])){
            $db->where ( 'c.inter_id', $condit['inter_id'] );
        }
        if(isset($condit['hotel_id']) && $condit['hotel_id']>0){
            $db->where ( 'c.hotel_id', $condit['hotel_id'] );
        }
        if(!empty($condit['entity_id'])){
            $hotel_ids = explode ( ',', $condit['entity_id'] );
            $db->where_in ( 'c.hotel_id', $hotel_ids );
        }
        if(isset($condit['status'])){
            if($condit['status']=='wait'){
                $db->where ( 'c.status', 0 );
            }elseif($condit['status']=='wechat'){
                $db->where ( 'c.channel', 'weixin' );
            }elseif($condit['status']=='scan'){
                $db->where ( 'c.channel', 'scan' );
            }
        }
        if(isset($condit['keywords']) && !empty($condit['keywords'])){
            $db->like ( 'c.orderid', $condit['keywords'] );
            $db->or_like ( 'c.detail', $condit['keywords'] );
            $db->or_like ( 'c.detail', $condit['keywords'] );
        }
        $db->from ( self::TAB_CHECK_OUT . ' c' );
        $db->join ( 'hotel_orders o', 'c.orderid=o.orderid AND c.orderid != ""' , 'left');
        $db->join ( 'hotel_invoice_get_list l', 'l.invoice_list_id=c.invoice_list_id' , 'left');
        $db->order_by ( 'c.check_out_id desc' );
        // $db->join ( 'hotels h', 'h.hotel_id=c.hotel_id AND h.inter_id=c.inter_id' , 'left');
        // $db->join ( 'hotel_rooms r', 'r.room_id=c.room_id AND r.inter_id=c.inter_id AND h.hotel_id=c.hotel_id' , 'left');
        if($count){
            return $db->count_all_results ();
        }
        if(isset($condit['size']) && isset($condit['page'])){
            $db->limit($condit['size'], $condit['page']);
        }
        return $db->get ()->result_array ();
        // return $db->last_query();

    }

    function get_count($condit){
        $db = $this->_load_db ('read');
        $db->select ( 'status,channel' );
        if(isset($condit['inter_id']) && !empty($condit['inter_id'])){
            $db->where ( 'inter_id', $condit['inter_id'] );
        }
        if(isset($condit['hotel_id']) && $condit['hotel_id']>0){
            $db->where ( 'hotel_id', $condit['hotel_id'] );
        }
        if(isset($condit['keywords']) && !empty($condit['keywords'])){
            $db->like ( 'orderid', $condit['keywords'] );
            $db->or_like ( 'detail', $condit['keywords'] );
            $db->or_like ( 'detail', $condit['keywords'] );
        }
        $db->from ( self::TAB_CHECK_OUT );

        $list =  $db->get ()->result_array ();
        $wait = 0;
        $wechat = 0;
        $scan = 0;
        foreach ($list as $value) {
            if($value['channel']=='weixin'){
                $wechat++;
            }
            if($value['channel']=='scan'){
                $scan++;
            }
            if($value['status']==0){
                $wait++;
            }
        }
        $return = array(
            'wait'=>$wait,
            'wechat'=>$wechat,
            'scan'=>$scan,
            'all'=>count($list)
        );
        return $return;
    }

    function edit_checkout($inter_id,$cid,$data){

        $db = $this->_load_db ();

        $this->db->where ( array (
                'inter_id' => $inter_id,
                'check_out_id' => $cid,
                'status' => 0
        ) );
        $mydata = array(
            'done_time' => date('Y-m-d H:i:s'),
            'remark' => isset($data['remark'])? $data['remark']:'',
            'realprice' => isset($data['realprice'])? $data['realprice']:0,
            'status' => 1
        );

        return $this->db->update (self::TAB_CHECK_OUT, $mydata );

    }

    function get_invoice_detail($orderid){

        $db = $this->_load_db ('read');
        $db->select ( 'invoice_content' );
        $db->where ( 'orderid', $orderid );

        $result = $db->get ( self::TAB_INVOICE_LIST )->row_array();

        $return =  json_decode($result['invoice_content'],true);

        return $return;
    }


    function check_checkout($inter_id,$openid,$hotel_id){     //获得当天的扫码预约退房信息

        $db = $this->_load_db('read');

        $date = date("Y-m-d",time()).' 00:00:00';
        $end_date = date("Y-m-d",(time()+172800)).' 00:00:00';

        $sql = "select * from `iwide_hotel_check_out` where inter_id = '{$inter_id}' and orderid ='' and hotel_id ={$hotel_id} and openid='{$openid}' and check_out_time between '{$date}' and '{$end_date}'";

        $res = $db->query($sql)->row_array();

        return $res;

    }

    function getCheckOutByOrderid($orderid){

        $db = $this->_load_db ('read');
        $db->where ( 'orderid', $orderid );

        $result = $db->get ( self::TAB_CHECK_OUT )->row_array();

        return $result;
    }

    // 获取新退房预约申请数
    function get_new_checkout($inter_id,$condits=array()){
        if(empty($inter_id)){
            return false;
        }
        $db = $this->_load_db('read');
        $db->where(array(
            'inter_id' => $inter_id,
            'status' => 0,
            ));
        if(!empty($condits['check_time'])){
            $db->where('create_time >=',date('Y-m-d H:i:s',$condits['check_time']));
        }
        if(!empty($condits['hotel_ids'])){
            $db->where_in('hotel_id',$condits['hotel_ids']);
        }
        return $db->get(self::TAB_CHECK_OUT)->num_rows();
    }

    // 获取指定退房记录
    function get_checkout_byid($cid){
        $db = $this->_load_db ('read');
        $db->select ( 'c.room_num,c.openid,c.detail,c.invoice_list_id,l.amount,l.invoice_content' );

        $db->where(array(
            'check_out_id' => $cid
        ));
        $db->from ( self::TAB_CHECK_OUT . ' c' );
        $db->join ( 'hotel_invoice_get_list l', 'l.invoice_list_id=c.invoice_list_id' , 'left');
        return $db->get()->row_array();
    }

    // 发送预约退房提醒(给酒店)
    function send_checkout_apply_notice($checkinfo = array()){
        if(empty($checkinfo)){
            return false;
        }
        $db = $this->_load_db ('read');
        $this->load->library('MYLOG');
        // $db->where(array(
        //     'inter_id' => $checkinfo['inter_id'],
        //     ));
        // $config = $db->get(self::TAB_HNC)->row_array();
        // if($config['is_weixin']==1&&($config['wx_notify']=='all'||strpos($config['wx_notify'],'checkout')!==false)){
            $db->where(array(
                'inter_id' => $checkinfo['inter_id'],
                'status' => 1,
                ));
            $db->where_in('hotel_id',array(0,$checkinfo['hotel_id']));
            $regs = $db->get(self::TAB_HNR)->result_array();
            if(!empty($regs)){
                $this->load->model ( 'plugins/Template_msg_model' );
                $this->load->model('hotel/hotel_notify_model');
                foreach ($regs as $kr => $vr) {
                    if(!$this->hotel_notify_model->check_reg($vr,'checkout')){
                        continue;
                    }
                    $msg = array(
                        'inter_id' => $checkinfo['inter_id'],
                        'openid' => $vr['openid'],
                        'hotel' => $checkinfo['hotel'],
                        'check_out_time'=>$checkinfo['check_out_time'],
                        'room_num'=>$checkinfo['room_num'],
                        );
                    $result = $this->template_msg_model->send_checkout_or_invoice_msg($msg,'hotel_checkout_apply_notice',1);
                    if($result['s']==0){
                        MYLOG::w('发送预约退房提醒(给酒店)失败:'.json_encode($msg).'|'.json_encode($result),'checkoutnotify');
                    }
                }
            }else{
                MYLOG::w('该酒店未有人登记申请或无人审核通过获取微信模板消息提醒：'.json_encode($checkinfo),'checkoutnotify');
            }
        // }else{
        //     MYLOG::w('该酒店未开启退房预约消息提醒：'.json_encode($config),'checkoutnotify');
        // }
    }
}