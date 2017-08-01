<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends MY_Admin_Roomservice {

    protected $label_module= '房间订餐';
    protected $label_controller= '订单列表';
    protected $label_action= '订单列表';
    public $username = '';
    public $dada_host = 'http://newopen.qa.imdada.cn'; //测试的域名
    public $dada_cancel_reation = '{"reason":"没有达达接单","id":1},{"reason":"达达没来取货","id":2},{"reason":"达达态度太差","id":3},{"reason":"顾客取消订单","id":4},{"reason":"订单填写错误","id":5},{"reason":"其他","id":10000}';
    function __construct(){
        parent::__construct();
        $admin_profile = $this->session->userdata ( 'admin_profile' );
        $this->username=$admin_profile['username'];
    }

    public function index(){
        $filter = array();
        $filter['inter_id'] = $this->inter_id;
        $filter['type'] = 4;
        //get请求接收参数
        $params= $this->input->get();
        $filter['shop_id'] = $this->input->get('shop_id')?$this->input->get('shop_id'):'';
        $filter['start_time'] = $this->input->get('start_time')?$this->input->get('start_time'):'';//下单时间
        $filter['end_time'] = $this->input->get('end_time')?$this->input->get('end_time'):'';
        $filter['wd'] = $this->input->get('wd')?addslashes($this->input->get('wd')):'';
        $filter['order_status'] = $this->input->get('order_status')?$this->input->get('order_status'):'99';
        $filter['time_type'] = $this->input->get('time_type')?$this->input->get('time_type'):'1';

        $filter['book_start_time'] = $this->input->get('book_start_time')?$this->input->get('book_start_time'):'';//下单时间
        $filter['book_end_time'] = $this->input->get('book_end_time')?$this->input->get('book_end_time'):'';
        $filter['merge_order_no'] = '';
        $search_url = '?';
        if($filter['wd']!=''){
            $search_url .= 'wd='.$filter['wd'] .'&';
        }
        if($filter['shop_id']){
            $search_url .= 'shop_id='.$filter['shop_id'].'&';
        }
        if($filter['start_time']){
            $search_url .= 'start_time='.$filter['start_time'].'&';
        }
        if($filter['end_time']){
            $search_url .= 'end_time='.$filter['end_time'].'&';
        }

        if($filter['book_start_time']){
            $search_url .= 'book_start_time='.$filter['book_start_time'].'&';
        }
        if($filter['book_end_time']){
            $search_url .= 'book_end_time='.$filter['book_end_time'].'&';
        }

        if($filter['order_status']){
            $search_url .= 'order_status='.$filter['order_status'].'&';
        }
        if($search_url=='?'){
            $search_url = '';
        }else{
            $search_url = rtrim($search_url,'&');
        }
        $per_page = 15;
        $cur_page = empty($this->uri->segment(4)) ? 0 : ($this->uri->segment(4));
        $this->load->model('roomservice/roomservice_shop_model');
        $filterH = array('inter_id'=>$this->inter_id);
        if(!empty($this->session->get_admin_hotels())){
            $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
        }
        if(!empty($this->canteen_shop_id)){
            $filterH['shop_id'] = $this->canteen_shop_id;//数组
        }

        //执行导出
        $ext = $this->input->get('export');
        if($ext && $ext == '导出')
        {
            $this->ext_orders_report($filter);
            exit;
        }

        //查询该inter_id 下的所有店铺信息
        $shops = $this->db->where(array('inter_id'=>$this->inter_id,'is_delete'=>0,'sale_type'=>4))->get('roomservice_shop')->result_array();
        $shoplist = array();
        if($shops){
            foreach($shops as $sk=>$sv){
                $shoplist[$sv['shop_id']] = $sv['shop_name'];
            }
        }
        //酒店信息
        //获取公众号下的酒店
        $this->load->model ( 'hotel/hotel_model' );
        $hotels = $this->hotel_model->get_hotel_hash ($filterH );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
        //inter_id下的分组
        $this->load->model('roomservice/roomservice_goods_group_model');
        $group = $this->roomservice_goods_group_model->get_list($filterH);//var_dump($res);die;
        //获取订单信息
        $this->load->model('roomservice/roomservice_orders_model');
        $ordermodel = $this->roomservice_orders_model;
        $res = $this->roomservice_orders_model->get_page($filter,$cur_page,$per_page);
        $data = isset($res[1])?$res[1]:array();
        $total_count = isset($res[0])?$res[0]:0;
        $order_detail = $action_arr = $action_data =  array();
        if(!empty($data)){
            $order_ids = array_column($data,'order_id');//order_ids 传数组
            $order_goods = $this->roomservice_orders_model->get_order_goods_info(array('inter_id'=>$this->inter_id,'order_ids'=>$order_ids));
            foreach($order_goods as $k=>$v){//订单详情处理
                $order_detail[$v['order_id']][] = $v;
                unset($order_goods);
            }
            //查询催单信息
            $this->load->model('roomservice/roomservice_action_model');
            $action_list = $this->roomservice_action_model->get_order_remind($this->inter_id,array('order_ids'=>$order_ids,'type'=>1));
            if($action_list){
                foreach($action_list as $k=>$v){
                    $action_arr[$v['order_id']][] = $v;
                }
                foreach($action_arr as $k=>$v){
                    $action_data[$k]['remind_count'] = count($v);
                    $action_data[$k]['last_remind_time'] = $v[0]['add_time'];
                }
                unset($action_list);
                unset($action_arr);
            }
            //var_dump($order_ids);die;
            foreach($data as $k=>$v){
                //查询商品详情
                //$detail = $this->db->where(array('inter_id'=>$this->inter_id,'order_id'=>$v['order_id']))->get('roomservice_orders')->result_array();
                //$detail = $this->roomservice_orders_model->get_order_goods_info(array('inter_id'=>$this->inter_id,'order_id'=>$v['order_id']));
                //$data[$k]['order_goods'] = $detail;
                $data[$k]['order_goods'] = !empty($order_detail[$v['order_id']])?$order_detail[$v['order_id']]:array();
                if(in_array($v['order_status'],array(0,20,25,26,27)) ){//未确认 已取消 已完成的不显示了
                    unset($action_data[$v['order_id']]);
                }
                $data[$k]['remind_info'] = !empty($action_data[$v['order_id']])?$action_data[$v['order_id']]:array();
            }
        }
        $base_url = site_url('/ticket/orders/index/');
        //var_dump($base_url);die;
        $sale_type = array(1=>'客房内',2=>'堂食',3=>'外卖');
        //优惠信息
        $discount_type = array(0=>'无',1=>'单满减',2=>'每满减',3=>'折扣',4=>'随机减');
        $pay_type = array(1=>'微信支付',2=>'储值支付',3=>'线下支付');
        //分页
        $first_url = $base_url.$search_url;
        $suffix = $search_url;
        $this->pagination($per_page,$cur_page,$base_url,$total_count,4,$first_url,$search_url,$suffix);
        $view_params = array (
            'pagination' => $this->pagination->create_links (),
            'orderModel' => $ordermodel,
            'shops' => $shoplist,
            'group' => $group,
            'filter'=>$filter,
            'hotels'=>$hotels,
            'res' =>$data,
            'pay_type_arr'=>$ordermodel->pay_way_array,
            'inter_id' => $this->inter_id,
            'total'=>$total_count,
            //   'searchurl' => $first_url,
        );
        echo $this->_render_content ( $this->_load_view_file ( 'index' ), $view_params, TRUE );
    }

    public function order_list()
    {
        $this->load->helper('appointment');
        $filter = array();
        $filter['inter_id'] = $this->inter_id;
        $filter['type'] = 4;
        //get请求接收参数
        $filter['shop_id'] = $this->input->get('shop_id')?$this->input->get('shop_id'):'';
        $filter['start_time'] = $this->input->get('start_time')?$this->input->get('start_time'):'';//下单时间
        $filter['end_time'] = $this->input->get('end_time')?$this->input->get('end_time'):'';
        $filter['wd'] = $this->input->get('wd')?addslashes($this->input->get('wd')):'';
        $filter['order_status'] = $this->input->get('order_status')?$this->input->get('order_status'):'99';
        $filter['time_type'] = $this->input->get('time_type')?$this->input->get('time_type'):'1';

        $filter['book_start_time'] = $this->input->get('book_start_time')?$this->input->get('book_start_time'):'';//下单时间
        $filter['book_end_time'] = $this->input->get('book_end_time')?$this->input->get('book_end_time'):'';

        $per_page = 10;
        $cur_page = !empty($this->input->get('page')) ? intval($this->input->get('page')) : 1;
        $this->load->model('roomservice/roomservice_shop_model');
        $filterH = array('inter_id'=>$this->inter_id);
        if(!empty($this->session->get_admin_hotels())){
            $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
        }
        if(!empty($this->canteen_shop_id)){
            $filterH['shop_id'] = $this->canteen_shop_id;//数组
        }

        //执行导出
        $ext = $this->input->get('ext_order');
        if($ext && $ext == '1')
        {
            $this->ext_orders_report($filter);
            exit;
        }

        //查询该inter_id 下的所有店铺信息
        $shops = $this->db->where(array('inter_id'=>$this->inter_id,'is_delete'=>0,'sale_type'=>4))->get('roomservice_shop')->result_array();
        $shoplist = array();
        if($shops){
            foreach($shops as $sk=>$sv){
                $shoplist[$sv['shop_id']] = $sv['shop_name'];
            }
        }
        //酒店信息
        //获取公众号下的酒店
        $this->load->model ( 'hotel/hotel_model' );
        $hotels = $this->hotel_model->get_hotel_hash ($filterH );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );

        //获取订单信息
        $this->load->model('roomservice/roomservice_orders_model');
        $ordermodel = $this->roomservice_orders_model;
        $res = $this->roomservice_orders_model->get_page_ticket($filter,$cur_page,$per_page);
        $data = isset($res[1])?$res[1]:array();
        $total_count = isset($res[0])?$res[0]:0;
        $order_detail = $action_arr = $action_data = $merge_order = array();

        //分页
        $arr_page = get_page($total_count, $cur_page, $per_page);

        if(!empty($data)){
            $order_ids = array_column($data,'order_id');//order_ids 传数组
            $order_goods = $this->roomservice_orders_model->get_order_goods_info(array('inter_id'=>$this->inter_id,'order_ids'=>$order_ids));
            foreach($order_goods as $k=>$v){//订单详情处理
                $order_detail[$v['order_id']][] = $v;
                unset($order_goods);
            }
            //查询催单信息
            $this->load->model('roomservice/roomservice_action_model');
            $action_list = $this->roomservice_action_model->get_order_remind($this->inter_id,array('order_ids'=>$order_ids,'type'=>1));
            if($action_list){
                foreach($action_list as $k=>$v){
                    $action_arr[$v['order_id']][] = $v;
                }
                foreach($action_arr as $k=>$v){
                    $action_data[$k]['remind_count'] = count($v);
                    $action_data[$k]['last_remind_time'] = $v[0]['add_time'];
                }
                unset($action_list);
                unset($action_arr);
            }

            foreach($data as $k=>$v)
            {
                $v['order_goods'] = !empty($order_detail[$v['order_id']])?$order_detail[$v['order_id']]:array();
                if(in_array($v['order_status'],array(0,20,25,26,27)) ){//未确认 已取消 已完成的不显示了
                    unset($action_data[$v['order_id']]);
                }
                $v['remind_info'] = !empty($action_data[$v['order_id']])?$action_data[$v['order_id']]:array();

                $item = array(
                    'orderNO' => $v['merge_order_no'],
                    'add_time' => $v['add_time'],
                    'consignee' => $v['consignee'],
                    'phone' => $v['phone'],
                    'note' => $v['note'],
                );
                $merge_order[$v['merge_order_no']]['order_info'][] = $v;
                $merge_order[$v['merge_order_no']]['merge_info'] = $item;
            }

            $merge_order = array_values($merge_order);
        }

        //设置分页
        unset($filter['type'],$filter['inter_id']);
        $filter = array_filter($filter);
        $http_build_query = '';
        if ($filter)
        {
            $http_build_query = http_build_query($filter).'&';
        }
        $url = site_url('/ticket/orders/order_list?'.$http_build_query);
        $pagehtml = pagehtml($total_count, $cur_page, $arr_page['page_total'], $url);

        $sale_type = array(1=>'客房内',2=>'堂食',3=>'外卖');
        //优惠信息
        $discount_type = array(0=>'无',1=>'单满减',2=>'每满减',3=>'折扣',4=>'随机减');
        $pay_type = array(1=>'微信支付',2=>'储值支付',3=>'线下支付');

        $view_params = array (
//            'pagination' => $this->pagination->create_links (),
            'orderModel' => $ordermodel,
            'shops' => $shoplist,
            'filter'=>$filter,
            'hotels'=>$hotels,
            'res' =>$merge_order,
            'pay_type_arr'=>$ordermodel->pay_way_array,
            'inter_id' => $this->inter_id,
            'arr_page'=>$arr_page,
            'pagehtml'=>$pagehtml,
        );
        echo $this->_render_content ( $this->_load_view_file ( 'orderlist' ), $view_params, TRUE );
    }

    //订单详情 打印
    public function print_order(){
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());
        if($this->inter_id== FULL_ACCESS){
            $message= $this->session->put_notice_msg('超管!');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
        };
        $order_id   = addslashes($this->input->post('oid'));
        if(empty($order_id)){
            $return['msg'] = 'empty data';
            echo json_encode($return);
            die;
        }
        $res = $this->db->where(array('order_id'=>$order_id,'inter_id'=>$this->inter_id))->get('roomservice_orders')->row_array();
        if($res){
            $res['order_detail'] = $this->db->where(array('order_id'=>$order_id,'inter_id'=>$this->inter_id))->get('roomservice_orders_item')->result_array();
            $this->load->model ( 'plugins/Print_model' );
            $this->Print_model->print_roomservice_order ( $res, 'ensure_order' );
            $return['errcode'] = 0;
            $return['msg'] = '执行成功';
            echo json_encode($return);
            die;
        }else{
            $return['msg'] = 'empty data error';
            echo json_encode($return);
            die;
        }
        /* $shop = $this->db->where(array('shop_id'=>$res['shop_id'],'inter_id'=>$this->inter_id))->get('roomservice_shop')->row_array();
         $this->load->model('roomservice/roomservice_orders_model');
         $orderModel = $this->roomservice_orders_model;
         $data['orderModel'] = $orderModel;
         $data['shop'] = $shop;
         $data['order'] = $res;
         //是否打印
         $print = $this->input->get ( 'print' );
         $view = empty($print)? 'edit' : 'view';
         $this->_render_content ( $this->_load_view_file ( $view ), $data, false );*/
        //die;
    }

    //更新订单状态
    public function update_order_status(){
        $this->load->helper('appointment');
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());
        $order_id   = addslashes($this->input->post('oid'));
        $status   = addslashes($this->input->post('status'));
        if(empty($order_id)||empty($status)){
            $return['msg'] = 'empty data';
            echo json_encode($return);
            die;
        }
        $this->load->model('roomservice/roomservice_orders_model');
        $orderModel = $this->roomservice_orders_model;
        $order = $this->db->where(array('inter_id'=>$this->inter_id,'order_id'=>$order_id,'type'=>4,'is_delete'=>0))->get('roomservice_orders')->row_array();
        if(!empty($order)){
            if($order['order_status']==$orderModel::OS_PER_CANCEL ||$order['order_status']==$orderModel::OS_HOL_CANCEL||$order['order_status']==$orderModel::OS_SYS_CANCEL || $order['order_status']==$orderModel::OS_FINISH){
                    $return['msg'] = '订单已经完成或者取消';
                    echo json_encode($return);
                    die;
            }
            //订单跟踪数组
            $array = array(
                'inter_id'=>$this->inter_id,
                'openid' =>'',
                'order_id' => $order_id,
                'type' => 2,//跟踪
                'order_status'=>$status,//更新后的订单状态
                'add_time' => date('Y-m-d H:i:s')
            );
            //记录订单操作日志
            $order_log = array(
                'inter_id'=>$this->inter_id,
                'order_id' => $order_id,
                'hotel_id' => $order['hotel_id'],
                'shop_id'  => $order['shop_id'],
                'operation'=> $this->username,
                'order_status'=>$status,
                'add_time'=> date('Y-m-d H:i:s'),
                'types' =>2,//后台
            );
            $note = '更新订单状态：从 ' . $orderModel->os_array[$order['order_status']] . ' 更新到 '.$orderModel->os_array[$status] . ',订单信息：'.json_encode($order);
            if($order['order_status'] == $status){
                $return['msg'] = '状态更新中，请刷新页面';
                echo json_encode($return);
                die;
            }
            if($status == $orderModel::OS_CONFIRMED){//需要接单
                //先判断订单状态
                if($order['order_status'] == $orderModel::OS_CONFIRMED) {
                    $return['msg'] = '订单已经接单';
                    echo json_encode($return);
                    die;
                }
                if($order['pay_way'] != 3) {//线上支付是否已经付款
                    //判断是否支付
                    if ($order['pay_status'] != $orderModel::IS_PAYMENT_YES) {
                        if ($order['order_status'] == $orderModel::OS_CONFIRMED) {
                            $return['msg'] = '线上支付订单还没有支付';
                            echo json_encode($return);
                            die;
                        }
                    }
                }
                //更新订单状态
                $res = $this->db->update('roomservice_orders',array('order_status'=>$orderModel::OS_CONFIRMED),array('inter_id'=>$this->inter_id,'order_id'=>$order_id));
                if($res){
                    //发送模板消息
                    $orderModel->handle_order($this->inter_id,$order_id,$openid='',$orderModel::OS_CONFIRMED);
                    $array['content'] = '订单已接单';
                    $this->db->insert('roomservice_action',$array);//插入订单跟踪表
                    $return['errcode'] = 0;
                    $return['msg'] = '更改订单状态为：已接单';

                    $order_log['action_note'] = $note;
                    $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录

                    //后台操作“接单”触发打印 屏蔽打印
                    /*
                    $order['order_status'] = $orderModel::OS_CONFIRMED;//打印使用这个状态
                    $order['order_detail'] = $this->db->get_where('roomservice_orders_item',array('order_id'=>$order['order_id']))->result_array();
                    $this->load->model ( 'plugins/Print_model' );
                    $this->Print_model->print_roomservice_order ( $order, 'ensure_order' );
                    */
                    //  echo json_encode($return);
                    //  die;
                }
            }elseif($status == $orderModel::OS_SHPPING){//需要配送  这里就直接更改状态了
                //更新订单状态
                $res = $this->db->update('roomservice_orders',array('order_status'=>$orderModel::OS_SHPPING),array('inter_id'=>$this->inter_id,'order_id'=>$order_id));
                if($res){
                    //发送模板消息
                    $orderModel->handle_order($this->inter_id,$order_id,$openid='',$orderModel::OS_SHPPING);

                    $array['content'] = '订单配送中';
                    $this->db->insert('roomservice_action',$array);//插入订单跟踪表

                    $order_log['action_note'] = $note;
                    $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录
                    $return['errcode'] = 0;
                    $return['msg'] = '更改订单状态为：配送中';
                    //  echo json_encode($return);
                    //  die;
                }
            }elseif($status == $orderModel::OS_FINISH){//需要更改为完成  这里就直接更改状态了
                //更新订单状态
                $res = $this->db->update('roomservice_orders',array('order_status'=>$orderModel::OS_FINISH),array('inter_id'=>$this->inter_id,'order_id'=>$order_id));
                if($res){
                    //发送模板消息
                    $orderModel->handle_order($this->inter_id,$order_id,$openid='',$orderModel::OS_FINISH);
                    $array['content'] = '订单已核销';
                    $this->db->insert('roomservice_action',$array);//插入订单跟踪表

                    $order_log['action_note'] = $note;
                    $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录
                    $return['errcode'] = 0;
                    $return['msg'] = '更改订单状态为：已核销';
                    // echo json_encode($return);
                    // die;
                }
            }elseif($status == $orderModel::OS_HOL_CANCEL){//需要更改为 酒店取消
                //判断是否支付
                if($order['pay_status'] == $orderModel::IS_PAYMENT_NOT){//未支付 无须退款
                    //取消
                    $res = $this->roomservice_orders_model->cancel_order($order,$orderModel::OS_HOL_CANCEL);
                    if($res){
                        //发送模板消息
                        $orderModel->handle_order($this->inter_id,$order_id,$openid='',$orderModel::OS_HOL_CANCEL);
                        $array['content'] = '订单已取消';
                        $this->db->insert('roomservice_action',$array);//插入订单跟踪表

                        $order_log['action_note'] = $note;
                        $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录
                        $return['errcode'] = 0;
                        $return['msg'] = '更改订单状态为：已取消';
                        // echo json_encode($return);
                        // die;//发送模板消息
                    }
                }elseif($order['pay_status'] == $orderModel::IS_PAYMENT_YES){//退款操作
                    if($order['pay_money'] <= 0)
                    {
                        /*
                        $return['msg'] = '订单金额为0，不支持退款';
                        echo json_encode($return);
                        die;
                        */
                        //取消
                        $res = $this->roomservice_orders_model->cancel_order($order);
                        if($res)
                        {
                            //发送模板消息
                            $orderModel->handle_order($this->inter_id,$order_id,'',$orderModel::OS_HOL_CANCEL);
                            $array = array(
                                'inter_id'=>$this->inter_id,
                                'openid' =>'',
                                'order_id' => $order_id,
                                'type' => 2,//跟踪
                                'content'=>'订单已取消',
                                'order_status'=>$orderModel::OS_PER_CANCEL,
                                'add_time' => date('Y-m-d H:i:s')
                            );
                            $this->db->insert('roomservice_action',$array);
                            $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录
                            $return['errcode'] = 0;
                            $return['msg'] = '订单取消成功';
                            echo json_encode($return);
                            die;//发送模板消息
                        }
                        else
                        {
                            $return['msg'] = '取消失败';
                            echo json_encode($return);
                            die;
                        }
                    }
                    //组装退款数据
                    $reund_sn = 'TK'.$order['type'].time().rand(1000,9999);
                    $refund = array(
                        'inter_id'  =>  $this->inter_id,
                        'hotel_id'  =>  $order['hotel_id'],
                        'shop_id'   =>  $order['shop_id'],
                        'openid'    =>  $order['openid'],
                        'order_sn'  =>  $order['order_sn'],
                        'trade_no'  =>  $order['trade_no'],
                        'refund_sn' =>  $reund_sn,
                        'refund_way'=>  $order['pay_way'],
                        'refund_status' => 0,//申请退款
                        'refund_money'  => $order['pay_money'],
                        'admin_name'  => $this->username,
                        'client_ip'  => get_client_ip(),
                    );
                    //先生成一条退款记录
                    $this->db->insert('roomservice_refund',$refund);
                    $id = $this->db->insert_id();
                    log_message("error","roomservice_refund：".$id);
                    log_message("error","roomservice_refund：".json_encode($refund));
                    if($id){

                        //获取总单金额
                        $this->load->model('ticket/ticket_orders_merge_model');
                        $filter_merge = array(
                            'order_no' => $order['merge_order_no'],
                            'pay_status' => 2,
                            'inter_id' => $order['inter_id'],
                            'shop_id' => $order['shop_id'],
                        );
                        $order_merge = $this->ticket_orders_merge_model->order_info($filter_merge);
                        if (!empty($order_merge))
                        {
                            //重置订单金额
                            $order['sub_total'] = $order_merge['pay_fee'];
                        }
                        log_message("error","roomservice_refund_money：".$order['sub_total'] );

                        //判断是哪种支付方式 调用相应的退款
                        if(1 == $order['pay_way']){//微信支付
                            $refund_fee = intval($order['pay_money'] * 100);
                            $total_fee = intval($order['sub_total'] * 100);//订单优惠后金额
                            $this->load->model('roomservice/Roomservice_wxpay_model');
                            $refund_result = $this->Roomservice_wxpay_model->refund($order['inter_id'],$order['trade_no'],$total_fee,$refund_fee,$reund_sn);
                            if(is_array($refund_result) && "SUCCESS" == $refund_result['return_code'] && "SUCCESS" == $refund_result['result_code']){

                                //更新订单表扣减订单实付金额、记录退款金额
                                $set_item = array(
                                    'refund_money'  => 'refund_money + '.$order['pay_money'],
                                    'pay_money'     => 'pay_money - '.$order['pay_money'],
                                );

                                $where_item = array(
                                    'order_id' => $order['order_id'],
                                );
                                $res_money = $this->roomservice_orders_model->update_data($set_item,$where_item);

                                $trade_no = $refund_result['transaction_id'];
                                $out_refund_no = $refund_result['out_refund_no'];//就是 refund_sn

                                $refund_data = array();
                                $refund_data['refund_id'] = $refund_result['refund_id'];
                                $refund_data['refund_fee'] = $total_fee;
                                $refund_data['id'] = $id;

                                //更新退款表 订单表 库存
                                $update_res = $this->roomservice_orders_model->update_refund_data($order,$out_refund_no,$trade_no,$refund_data,$orderModel::OS_HOL_CANCEL);

                                if($update_res){
                                    //发送模板消息
                                    $orderModel->handle_order($this->inter_id,$order_id,$openid='',$orderModel::OS_HOL_CANCEL);
                                    $array['content'] = '订单已取消';
                                    $this->db->insert('roomservice_action',$array);//插入订单跟踪表
                                    $note .= " | 发起微信退款";
                                    $order_log['action_note'] = $note;
                                    $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录
                                    $return['errcode'] = 0;
                                    $return['msg'] = '申请退款成功，更改订单状态为：已取消';
                                    // echo json_encode($return);
                                    // die;//发送模板消息
                                }else{
                                    $return['msg'] = '退款失败';
                                    //echo json_encode($return);
                                    // die;//发送模板消息
                                }
                            }else{
                                $return['msg'] = '微信返回结果：失败';
                                //echo json_encode($return);
                                //die;//发送模板消息
                            }
                        }elseif(2 == $order['pay_way']){//储值支付
                            //处理余额退款
                            $balance_refund = $orderModel->balance_refund($order['inter_id'],$order['openid'], $order['pay_money']);
                            if($balance_refund){//退款成功

                                //更新订单表扣减订单实付金额、记录退款金额
                                $set_item = array(
                                    'refund_money'  => 'refund_money + '.$order['pay_money'],
                                    'pay_money'     => 'pay_money - '.$order['pay_money'],
                                );

                                $where_item = array(
                                    'order_id' => $order['order_id'],
                                );
                                $res_money = $this->roomservice_orders_model->update_data($set_item,$where_item);

                                $refund_data = array();
                                $refund_data['refund_id'] = 'banlance';
                                $refund_data['refund_fee'] = $order['pay_money'];
                                $refund_data['id'] = $id;
                                //更新退款表 订单表 库存
                                $update_res = $this->roomservice_orders_model->update_refund_data($order,$reund_sn,'',$refund_data,$orderModel::OS_HOL_CANCEL);
                                if($update_res){
                                    //发送模板消息
                                    $res = $orderModel->handle_order($order['inter_id'],$order_id,'',26);
                                    $array['content'] = '订单已取消';
                                    $this->db->insert('roomservice_action',$array);//插入订单跟踪表
                                    $note .= " | 发起储值退款";
                                    $order_log['action_note'] = $note;
                                    $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录
                                    $return['errcode'] = 0;
                                    $return['msg'] = '申请退款成功， 更改订单状态为：已取消';
                                    // echo json_encode($return);
                                    //  die;//发送模板消息
                                }else{
                                    $return['msg'] = '更新失败';
                                    // echo json_encode($return);
                                    // die;//发送模板消息
                                }
                            }else{
                                $return['msg'] = '退款失败';
                                // echo json_encode($return);
                                // die;//发送模板消息
                            }
                        }elseif(4 == $order['pay_way']){//威富通支付
                            $refund_fee = intval($order['pay_money'] * 100);
                            $total_fee = intval($order['sub_total'] * 100);
                            $this->load->model('roomservice/Roomservice_wxpay_model');
                            $refund_result = $this->Roomservice_wxpay_model->weifutong_refund($order['inter_id'],$order['trade_no'],$total_fee,$refund_fee,$reund_sn,$order['hotel_id']);
                            if(is_array($refund_result)){

                                //更新订单表扣减订单实付金额、记录退款金额
                                $set_item = array(
                                    'refund_money'  => 'refund_money + '.$order['pay_money'],
                                    'pay_money'     => 'pay_money - '.$order['pay_money'],
                                );

                                $where_item = array(
                                    'order_id' => $order['order_id'],
                                );
                                $res_money = $this->roomservice_orders_model->update_data($set_item,$where_item);


                                $trade_no = $refund_result['transaction_id'];
                                $out_refund_no = $refund_result['out_refund_no'];//就是 refund_sn

                                $refund_data = array();
                                $refund_data['refund_id'] = $refund_result['refund_id'];
                                $refund_data['refund_fee'] = $total_fee;
                                $refund_data['id'] = $id;
                                //更新退款表 订单表 库存
                                $update_res = $this->roomservice_orders_model->update_refund_data($order,$out_refund_no,$trade_no,$refund_data,$orderModel::OS_HOL_CANCEL);

                                if($update_res){
                                    //发送模板消息
                                    $res = $orderModel->handle_order($this->inter_id,$order_id,'',26);
                                    $array['content'] = '订单已取消';
                                    $this->db->insert('roomservice_action',$array);//插入订单跟踪表
                                    $note .= " | 发起威富通退款";
                                    $order_log['action_note'] = $note;
                                    $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录
                                    echo json_encode ( array (
                                        'errcode' =>0,
                                        'msg' => '申请退款成功，请刷新订单'
                                    ));
                                    die;
                                }else{
                                    echo json_encode ( array (
                                        'errcode' =>1,
                                        'msg' => '退款异常'
                                    ));
                                    die;
                                }
                            }else{
                                echo json_encode ( array (
                                    'errcode' =>0,
                                    'msg' => '向服务器发起退款的时候失败，请稍后再试'
                                ) );
                                die;
                            }
                        }
                    }
                    else
                    {
                        $return['msg'] = '操作失败';
                    }
                }
            }

            //操作总单状态
            if (!empty($order['merge_order_no']))
            {
                $merge_orderNO = array($order['merge_order_no']);
                //确认 5 => 1-待消费
                $update_status = 0;
                if ($status == 5)
                {
                    $update_status = 1;
                }
                // 核销 20 => 2-已消费
                else if($status == 20)
                {
                    $update_status = 2;
                }
                //已支付订单酒店取消
                else if ($status == 26)
                {
                    $update_status = 3;
                }

                if ($update_status > 0)
                {
                    update_merge_order_status($merge_orderNO,$status,$update_status);
                }
            }

            echo json_encode($return);
            die;
        }else{
            $return['msg'] = 'empty data';
            echo json_encode($return);
            die;
        }
    }

    public function extdata($res = array()){
        if(empty($res)){
            return false;
        }
        $this->load->library ( 'PHPExcel' );
        $this->load->library ( 'PHPExcel/IOFactory' );
        $objPHPExcel = new PHPExcel ();
        $objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
        $col = 0;
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '分组id' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, 'inter_id' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '酒店公众号名称' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '所属门店' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '场景名称' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '添加时间' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '成交订单数' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '成交总额' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '成交分组占比' );

        // Fetching the table data
        $row = 2;
        foreach ( $res as $k=>$item ) {
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item['group_id'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item['inter_id']);
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item['inter_name'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item['hotel_name'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item['type_name'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, date('Y-m-d',$item['create_time']) );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item['order_count'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item['trade_money'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, $item['rate'] );
            $row ++;
        }
        $objPHPExcel->setActiveSheetIndex ( 0 );
        $objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
        // 发送标题强制用户下载文件
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.xls"' );
        header ( 'Cache-Control: max-age=0' );
        $objWriter->save ( 'php://output' );
    }


    /**
     * 商品单独退款接口
     * author:沙沙
     */
    public function refund_goods()
    {
        $this->load->helper('appointment');
        $param = $this->input->post();
        $order_id   = intval($param['oid']);
        $item_id    = intval($param['item_id']);
        $quantity   = intval($param['quantity']);

        if (empty($quantity) || empty($item_id) || empty($order_id))
        {
            ajax_return(0,'参数错误');
        }

        $this->load->model('roomservice/roomservice_orders_model');
        $this->load->model('roomservice/roomservice_orders_item_model');
        $this->load->model('roomservice/roomservice_refund_goods_model');

        $where = array(
            'inter_id' => $this->inter_id,
            'order_id' => $order_id,
        );
        $_orders = $this->roomservice_orders_model->get_one($where);

        //判断订单状态是否符合退款
        if (($_orders['pay_status'] == 1 && in_array($_orders['pay_way'],array(1,2))) && in_array($_orders['order_status'],array(0,5,10)))
        {
            //判断商品是否符合退款（根据商品数量）
            $where['item_id'] = $item_id;
            $orders_item = $this->roomservice_orders_item_model->get_one($where);
            if (($orders_item['goods_num'] >= $quantity) && ($orders_item['refund_num'] < $orders_item['goods_num']))
            {
                $_orders['refund_price'] = $orders_item['goods_price'] * $quantity;
                log_message("error","实付金额：".$_orders['refund_price']);
                //计算退款数量总退款金额 => 应退金额
                $refund_money =  $this->refund_price($_orders);
                log_message("error","实际退款：".$refund_money['refund_total']);
                $refund_money['refund_total'] = formatMoney($refund_money['refund_total']);
                log_message("error","实际退款：".$refund_money['refund_total']);
                //插入退款商品记录
                $refund_goods = array(
                    'inter_id'      => $_orders['inter_id'],
                    'hotel_id'      => $_orders['hotel_id'],
                    'shop_id'       => $_orders['shop_id'],
                    'order_id'      => $_orders['order_id'],
                    'item_id'       => $orders_item['item_id'],
                    'goods_id'      => $orders_item['goods_id'],//商品ID
                    'openid'        => $_orders['openid'],
                    'order_sn'      => $_orders['order_sn'],//订单号
                    'trade_no'      => $_orders['trade_no'],//流水号
                    //'refund_sn'     => $_orders['refund_sn'],//退款号
                    //'refund_id'     => $_orders['refund_id'],//退款id 微信返回
                    'refund_way'    => $_orders['pay_way'],//退款方式
                    'refund_money'  => $refund_money['refund_total'],   //应退金额
                    'refund_price'  => $orders_item['goods_price'],//商品单价
                    'refund_fee'    => $refund_money['refund_total'] *100,//实际退金额单位:分
                    'refund_num'    => $quantity,
                    'refund_status' => 0, //退款状态
                    'type'          => $_orders['type'],
                    'add_time'      => date('Y-m-d H:i:s'),
                    'update_time'   => date('Y-m-d H:i:s'),
                    'admin_name'   => $this->username,
                    'client_ip'   => get_client_ip(),
                );

                $insert_id = $this->roomservice_refund_goods_model->insert($refund_goods);

                //开启事务
                $this->db->trans_begin();

                //订单商品表 更改记录退款数量
                $set_item = array(
                    'refund_num' => 'refund_num + '.$quantity,
                );
                $where_item = array(
                    'item_id' => $orders_item['item_id'],
                    'goods_num >=' => $quantity + $orders_item['refund_num'],//处理并发下的问题
                );
                $res_num = $this->roomservice_orders_item_model->update_data($set_item,$where_item);

                //更新订单表扣减订单实付金额、记录退款金额
                $set_item = array(
                    'refund_money'  => 'refund_money + '.$refund_money['refund_total'],
                    'pay_money'     => 'pay_money - '.$refund_money['refund_total'],
                );

                $where_item = array(
                    'order_id' => $_orders['order_id'],
                );
                $res_money = $this->roomservice_orders_model->update_data($set_item,$where_item);

                //操作sku信息 若存在规则ID 就更新 规格表库存否则更新商品表库存
                if (!empty($orders_item['setting_id']))
                {
                    $this->load->model('roomservice/roomservice_spec_setting_model');
                    $where_item = array(
                        'setting_id' => $orders_item['setting_id'],
                    );

                    $set_item = array(
                        'spec_stock'  => 'spec_stock + '.$quantity,
                    );
                    $res_setting = $this->roomservice_spec_setting_model->update_data($set_item,$where_item);
                }

                $this->load->model('roomservice/roomservice_goods_model');

                $where_item = array(
                    'goods_id' => $orders_item['goods_id'],
                );

                $set_goods = array(
                    'stock'  => 'stock + '.$quantity,
                    'sale_num'  =>'sale_num - '.$quantity,//商品销量
                );

                $res_goods = $this->roomservice_goods_model->update_data($set_goods,$where_item);

                //发起退款逻辑
                $res_refund = 0;
                if(1 == $_orders['pay_way'])//微信支付
                {
                    $refund_fee = $refund_money['refund_total'] * 100;
                    $sub_total = $_orders['sub_total'] * 100;
                    log_message("error","退款金额：".$refund_fee);
                    log_message("error","实付金额：".$sub_total);

                    $this->load->model('roomservice/Roomservice_wxpay_model');
                    $refund_sn = 'TK'.$_orders['type'].time().rand(1000,9999);

                    $refund_result = $this->Roomservice_wxpay_model->refund($_orders['inter_id'], $_orders['trade_no'], $sub_total, $refund_fee, $refund_sn);
                    if (is_array($refund_result) && "SUCCESS" == $refund_result['return_code'] && "SUCCESS" == $refund_result['result_code'])
                    {
                        $refund_data = array();
                        $refund_data['refund_id'] = $refund_result['refund_id'];//refund_id
                        $refund_data['refund_sn'] = $refund_result['out_refund_no'];//就是 refund_sn
                        $refund_data['refund_status'] = 1;//退款状态为成功

                        $res_refund = $this->roomservice_refund_goods_model->update_data($refund_data,array('refund_goods_id'=>$insert_id));
                    }
                }
                else if(2 == $_orders['pay_way'])//余额退款
                {
                    $balance_refund = $this->roomservice_orders_model->balance_refund($_orders['inter_id'],$_orders['openid'],$refund_money['refund_total']);
                    if($balance_refund)
                    {
                        $refund_data = array();
                        $refund_data['refund_id'] = 'banlance';
                        $refund_data['refund_status'] = 1;//退款状态为成功

                        $res_refund = $this->roomservice_refund_goods_model->update_data($refund_data,array('refund_goods_id'=>$insert_id));
                    }
                }

                log_message("error","成功状态：".$res_goods.'-'.$res_money.'-'.$res_num.'-'.$res_refund);
                //提交事务
                if (($res_goods > 0) && ($res_money > 0) && ($res_num > 0) && ($res_refund > 0))
                {
                    $this->db->trans_commit();

                    ajax_return(1,'退款成功');
                }
                else
                {
                    $this->db->trans_rollback();
                    ajax_return(0,'退款失败');
                }
            }
            else
            {
                ajax_return(0,'退款份额超出可退数量');
            }
        }
        else
        {
            ajax_return(2,'当前订单状态不能退款');
        }
    }

    /**
     * 计算实际退款金额
     * @param $order
     * @return array
     */
    protected function refund_price($order)
    {
        $refund = array(
            'refund_total' => 0,
        );

        //折算公式：[（实付金额÷订单金额）×退款金额]
        $rate = $order['discount_fee']/$order['row_total'];
        $rate = substr(sprintf("%.4f",$rate),0,-1); //$rate=0.1265489 结果：0.12
        //$rate = round($rate,2,PHP_ROUND_HALF_DOWN);//舍去小数
        $refund['refund_total'] = $rate * $order['refund_price'];
        return $refund;
    }

    /**
     * 更改订单备注
     * $id  订单ID
     * $remark 备注信息
     */
    public function save_remark()
    {
        $this->load->helper('appointment');
        $id     = $this->input->post("id",true);
        $remark = $this->input->post("remark",true);
        if (empty($id) || empty($remark))
        {
            ajax_return(0,'请输入订单备注');
        }
        //$this->load->model('roomservice/roomservice_orders_model');

        $where = array('inter_id'=>$this->inter_id,'order_id'=>intval($id));
        $data = array('shop_note'=>addslashes($remark));

        $res = $this->db->update('roomservice_orders',$data,$where);

        if ($res > 0)
        {
            ajax_return(1,'保存成功');
        }
        else
        {
            ajax_return(0,'保存失败');
        }
    }

    /*
     * 推送达达 新增订单接口
     * */
    protected function sendOrderToDada($order = array()){
        $return = array('errcode'=>1,'msg'=>'');
        //获取达达配置信息
        $this->load->model('roomservice/roomservice_dada_model');
        $dadaInfo = $this->roomservice_dada_model->get(array('inter_id'=>$this->inter_id,'hotel_id'=>$order['hotel_id'],'status'=>1));
        if(empty($dadaInfo)){
            $return['msg'] = '没有达达配送信息';
            return $return;
        }
        $res = $this->handleOrderData($order,$dadaInfo);
        return $res;
    }
    /**
     * 推送达达 重发订单接口 ：/api/order/readdOrder
     * */
    public function reSendOrderToDada(){
        $this->load->helper('appointment');
        $id     = $this->input->post("oid",true);
        if (empty($id)) {
            ajax_return(0,'orderid有误');
        }
        $this->load->model('roomservice/roomservice_orders_model');
        $orderModel = $this->roomservice_orders_model;
        $order = $orderModel->get_one(array('order_id'=>$id,'inter_id'=>$this->inter_id));
        if(empty($order))
        {
            ajax_return(0,'无该订单！');
        }
       /* else if($order['order_status'] != 0)
        {
            ajax_return(0,'当前订单状态不可推送');
        }*/
        else if($order['shipping_type'] == 2 && $order['type'] == 3 && ($order['shipping_cost'] <= 0))
        {
            ajax_return(0,'订单非达达配送');
        }

        //获取达达配置信息
        $this->load->model('roomservice/roomservice_dada_model');
        $dadaInfo = $this->roomservice_dada_model->get(array('inter_id'=>$this->inter_id,'hotel_id'=>$order['hotel_id'],'status'=>1));
        if(empty($dadaInfo)){
            ajax_return(0,'无达达配置数据！');
        }
        $return = $this->handleOrderData($order,$dadaInfo,2);
        if($return['erroce']==0)
        {
            //更改达达订单状态
            $this->update_dada_status($order,1);
            ajax_return(1,'推送成功！');
        }
        else
        {
            ajax_return(0,$return['msg']);
        }
    }

    /*
     * 新增订单和重发订单数据处理
     * */
    private function handleOrderData($order,$dadaInfo,$type=1){
        $return = array('errcode'=>1,'msg'=>'');
        //初始化
        $config = array();
        $config['app_key'] = $dadaInfo['app_key'];
        $config['app_secret'] = $dadaInfo['app_secret'];
        $config['source_id'] = $dadaInfo['source_id'];
        if($type==2){//重发订单接口url
            $config['url'] = $this->dada_host . '/api/order/reAddOrder';
        }else{
            $config['url'] = $this->dada_host.'/api/order/addOrder';
        }
        $this->load->library('Dada/DadaOpenapi',$config,'DadaOpenapi');
        //组装发单数组
        $data = array(
            'shop_no'=> $dadaInfo['shop_no'],
            'origin_id'=> $order['order_sn'],
            'city_code'=> $dadaInfo['city_code'],
            // 'pay_for_supplier_fee'=> 0.0,
            // 'fetch_from_receiver_fee'=> 0.0,
            //  'deliver_fee'=> 0.0,
            'tips'=> 0,
            // 'info'=> '测试订单',
            'cargo_type'=> 5,
            // 'cargo_weight'=> 10,
            'cargo_price'=> $order['discount_fee'],//订单金额 = $order['sub_total']-$order['shipping_fee'] 、、除去运费的订单金额
            // 'cargo_num'=> 2,
            'is_prepay'=> $dadaInfo['is_prepay'],
            'expected_fetch_time'=> time() + $dadaInfo['expected_fetch_time'] * 60,
            // 'expected_finish_time'=> 0,
            //  'invoice_title'=> '测试',
            'receiver_name'=> $order['consignee'],
            'receiver_address'=> $order['address'],
            'receiver_phone'=> $order['phone'],
            'receiver_lat'=> $order['latitude'],
            'receiver_lng'=> $order['longitude'],
            //'callback'=>'http://dc.jinfangka.cn/index.php/dadareturn/dada_rtn/'.$this->inter_id,
            'callback'=>'http://dingfang.liyewl.com/index.php/dadareturn/dada_rtn/'.$this->inter_id,//测试用
        );

        MYLOG::w('推送达达请求'.json_encode($data),'roomservice/dada');
        $reqRes = $this->DadaOpenapi->makeRequest($data);
        MYLOG::w('配置数据：'.json_encode($config).' | 推送达达数据'.json_encode($data),'roomservice/dada');
        MYLOG::w('推送达达返回'.json_encode($reqRes),'roomservice/dada');
        if($reqRes){//成功
            if( $this->DadaOpenapi->getCode()==0 && $this->DadaOpenapi->getStatus()=='success'){
                if($this->DadaOpenapi->getResult()){
                    $res = $this->DadaOpenapi->getResult();
                    //更新订单信息
                    $param = array(
                        'dada_status'=>1,
                        'dada_shipping_cost'=>$res['fee'],
                        'dada_distance'=>$res['distance'],
                    );
                    $where = array('order_id'=>$order['order_id']);
                    $update = $this->db->update('roomservice_orders',$param,$where);
                    if($update){
                        $return['errcode'] = 0;
                        $return['msg'] = '成功';
                        return $return;
                    }else{
                        $return['msg'] = '更新达达状态失败';
                        return $return;
                    }
                }else{
                    $return['msg'] = '异常';
                    return $return;
                }
            }else{
                $return['msg'] = $this->DadaOpenapi->getMsg();
                return $return;
            }
        }else{
            $return['msg'] = '异常';
            return $return;
        }
    }

    /**
     * 取消订单接口
     * */
    public function dadaCancelOrder()
    {
        $id = $this->input->post("oid",true);

        $this->load->helper('appointment');
        //获取订单信息
        $this->load->model('roomservice/roomservice_orders_model');
        $orderModel = $this->roomservice_orders_model;
        $order = $orderModel->get_one(array('order_id'=>$id,'inter_id'=>$this->inter_id));

        if (empty($order))
        {
            ajax_return(0,'您没有该订单');
        }

        //获取达达配置信息
        $this->load->model('roomservice/roomservice_dada_model');
        $dadaInfo = $this->roomservice_dada_model->get(array('inter_id'=>$this->inter_id,'hotel_id'=>$order['hotel_id'],'status'=>1));
        if(empty($dadaInfo)){
            ajax_return(0,'无达达配置数据！');
        }
        //初始化
        $config = array();
        $config['app_key'] = $dadaInfo['app_key'];
        $config['app_secret'] = $dadaInfo['app_secret'];
        $config['source_id'] = $dadaInfo['source_id'];
        $config['url'] = $this->dada_host . '/api/order/formalCancel';

        $this->load->library('Dada/DadaOpenapi',$config,'DadaOpenapi');
        //组装数组
        $data = array(
            'order_id'=> $order['order_sn'],//推送达达的是订单号
            'cancel_reason_id'=> 4,
          //  'cancel_reason'=> '{"reason":"顾客取消订单","id":4}',

        );
        MYLOG::w('取消推送达达请求'.json_encode($data),'roomservice/dada');
        $reqRes = $this->DadaOpenapi->makeRequest($data);
        MYLOG::w('取消推送达达返回'.json_encode($reqRes),'roomservice/dada');
        if($reqRes)
        {
            if( $this->DadaOpenapi->getCode()==0 && $this->DadaOpenapi->getStatus()=='success'){
                if($this->DadaOpenapi->getResult()){
                    $res = $this->DadaOpenapi->getResult();
                    if($res['deduct_fee']){
                        //更新扣除的违约金
                        $this->db->update('roomservice_orders',array('deduct_fee'=>$res['deduct_fee']),array('order_id'=>$order['order_id']));
                    }
                }
                //更改达达订单状态
                $this->update_dada_status($order,8);
                ajax_return(1,'操作成功！');
            }else{
                ajax_return(0,'返回异常！');
            }
        }else{
            ajax_return(0,$this->DadaOpenapi->getMsg());
        }
        ajax_return(0,'发起取消达达订单失败！');
    }

    //更改达达配送订单状态
    protected function update_dada_status($order,$dada_status)
    {
        $update = array(
            'dada_status' => $dada_status,
        );

        if ($update['dada_status'] == 8)
        {
            $data = array();
            $data['cancel_reason']   = '商家主动取消';
            $data['cancel_from']     = 2;
            $this->db->update('dada_log',$data,array('inter_id'=>$order['inter_id'],'order_sn'=>$order['order_sn']));
        }
        $this->db->update('roomservice_orders',$update,array('order_id'=>$order['order_id']));
        return $this->db->affected_rows();
    }


    public function orderlist()
    {
        $view_params = array (

        );
        echo $this->_render_content ( $this->_load_view_file ( 'orderlist' ), $view_params, TRUE );
    }

}
