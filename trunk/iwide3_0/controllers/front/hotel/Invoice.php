<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Invoice extends MY_Front {
    private static $theme;
	public $common_data = array();
	public $openid;


    function __construct() {
        parent::__construct ();
        $this->inter_id = $this->session->userdata ( 'inter_id' );
        $this->openid = $this->session->userdata ( $this->inter_id . 'openid' );
        MYLOG::hotel_tracker($this->openid,  $this->inter_id);
        $this->load->model ( 'wx/Publics_model' );
        $this->load->model ( 'wx/Access_token_model' );
        $this->public = $this->Publics_model->get_public_by_id ( $this->inter_id );
        $this->common_data ['signPackage'] = $this->Access_token_model->getSignPackage ( $this->inter_id );
        $this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
        $this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
    }


    public function check_out()         //预约退房
    {

        $data = $this->common_data;
        $openid=$this->openid;
        $inter_id=$this->inter_id;

        $this->load->model('invoice/Invoice_model');
        $this->load->model('hotel/Hotel_model');

        $get_data = $this->input->get();

        $data['pagetitle'] = '预约退房';

        $data['hid'] = 0;
        $data['oid'] = 0;

        if(isset($get_data['oid'])){    //oid为订单ID，如果存在订单ID以订单ID为先，记录orderid，没有则记录酒店ID
            $data['oid'] = $get_data['oid'];
        }else{
            if(isset($get_data['hid'])){   //hid为酒店ID，扫码退房必须带有该参数
                $data['hid'] = $get_data['hid'];
            }
        }


        if($data['oid']!=0){

            $order = $this->Invoice_model->getOrderById($data['oid'],$inter_id);    //获取订单信息

            if(!$order)redirect(site_url('hotel/hotel/index?id=').$inter_id);

            $data['name'] = $order['name'];
            $data['tel'] = $order['tel'];

            $data['hotel'] = $this->Hotel_model->get_hotel_detail($inter_id,$order['hotel_id']);
            $data['hid'] =  $order['hotel_id'];

            $check = $this->Invoice_model->getCheckOutByOrderid($order['orderid']);    //检查订单是否预约过退房

        }else{

            $data['hotel'] = $this->Hotel_model->get_hotel_detail($inter_id,$data['hid']);

            if(!$data['hotel'])redirect(site_url('hotel/hotel/index?id=').$inter_id);

            $check = $this->Invoice_model->check_checkout($inter_id,$openid,$data['hid']);    //检查当天该openid在该酒店是否已经预约过退房

        }

        if($check){
            if($check['invoice_list_id']==0){     //预约退房的酒店是否支持开发票
                redirect(site_url('hotel/Invoice/submit_result').'?type=1&id='.$this->inter_id);
            }else{
                redirect(site_url('hotel/Invoice/submit_result').'?id='.$this->inter_id);
            }
        }

        $retreat_time = json_decode($data['hotel']['retreat_time']);
        $now = date("H",time()).'00';
        $start_time = substr_replace($retreat_time->start,':00',2);
        $end_time = substr_replace($retreat_time->end,':00',2);

//        if($now+100 >= $retreat_time->end){
//            redirect(site_url('hotel/Invoice/processing').'?id='.$this->inter_id.'&h='.$data['hid'].'&s='.$start_time.'&e='.$end_time);
//        }

        $this->display('invoice/check_out', $data);


    }


    public  function  checkout_post(){

        $this->load->model('invoice/Invoice_model');
        $this->load->model('hotel/Order_model');
        $this->load->library('MYLOG');

        $openid=$this->openid;
        $inter_id=$this->inter_id;

        $need = 0;

        $info = array(
            'code' => 1,
            'msg' => '提交失败'
        );

        $data = $this->input->post();

        if(isset($data['code']) && preg_match("/[\x7f-\xff]/", $data['code'])){
            $info['msg']='识别号输入错误';
            echo json_encode($info);return;
        }


        $createtime = date('Y-m-d H:i:s',time());

        $order = $this->Invoice_model->getOrderById($data['oid'],$inter_id);

        if(isset($data['invoice_list_id']) && $data['invoice_list_id']!=0){   //存在下订单时就提交发票的情况下获取发票信息

            $invoice_list_id = $data['invoice_list_id'];

        }else{

            $invoice_id = 0;

            if(isset($data['isneed']) && $data['isneed']==1){     //需要发票

                $need = $data['isneed'];

                $post_invoice = array(
                    'openid'=>$openid,
                    'inter_id'=>$inter_id,
                    'title'=>$data['title'],
                    'status'=>1,
                    'createtime'=>date('Y-m-d H:i:s',time())
                );

                $content = array();

                $content['code'] = $data['code'];

                if($data['receipt']==2){    //增值税发票

                    $post_invoice['type']=2;

                    $content['bank'] = $data['bank'];
                    $content['account'] = $data['account'];
                    $content['phonecall'] = $data['phonecall'];
                    $content['address'] = $data['address'];

                }

                $post_invoice['content'] = json_encode($content);

                $invoice_id =  $this->Invoice_model->new_invoice($post_invoice);    //返回新的发票抬头

                $invoice_list = array(
                    'amount'=>$data['amount'],
                    'inter_id'=>$inter_id,
                    'openid'=>$openid,
                    'createtime'=>date('Y-m-d H:i:s',time()),
                    'invoice_id' =>$invoice_id
                );


                if(isset($data['title']) && isset($data['receipt'])){

                    $content['title'] = $data['title'];
                    $content['type'] = $data['receipt'];

                    if(!empty($data['remark'])) $content['remark']=$data['remark'];

                    $post_invoice['content'] = json_encode($content);
                    $invoice_list['invoice_content'] = $post_invoice['content'];

                }

                if($order){

                    $invoice_list['amount']=$order['price'];
                    $invoice_list['orderid']=$order['orderid'];
                    $invoice_list['hotel_id']=$order['hotel_id'];

                }

                $invoice_list_id = $this->Invoice_model->book_invoice($invoice_list);

            }

        }

        if(!isset($invoice_list_id))$invoice_list_id = 0;

        $post_data = array(
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'room_num'=>$data['room_nums'],
            'check_out_time'=>date('Y-m-d H:i',$data['checkout_time']),
            'invoice_list_id'=>$invoice_list_id,
            'create_time'=>$createtime,
            'channel' => 'scan',
            'hotel_id' => $data['hid']
        );

        $detail = array();

        if($order){

            $order_item = $this->Invoice_model->getOrderItem($order['orderid']);

            $hotel_name = $this->Invoice_model->getHotelName($order['orderid']);

            $detail = array(
                'hn'=>$hotel_name['name'],
                'rn'=>$order_item['roomname'],
                'pn'=>$order_item['price_code_name'],
                'name'=>$data['name'],
                'tel'=>$data['tel']
            );

            $post_data['detail'] = json_encode($detail);
            $post_data['room_id'] = $order_item['room_id'];
            $post_data['orderid']=$order['orderid'];
            $post_data['hotel_id']=$order['hotel_id'];
            $post_data['channel'] = 'weixin';
        }

        if(!isset($post_data['detail']) && $data['hid']!=0){

            $this->load->model('hotel/Hotel_model');

            $hotel_info = $this->Hotel_model->get_hotel_detail($inter_id,$data['hid']);

            if(!empty($hotel_info)){
                $detail['hn']=$hotel_info['name'];
                $detail['name']=$data['name'];
                $detail['tel']=$data['tel'];
                $post_data['detail'] = json_encode($detail);
            }
        }


        $res = $this->Invoice_model->new_checkout($post_data);

        if($res){

            if($order){
                $this->Invoice_model->update_order_invoice($inter_id,$openid,$order['orderid']);
            }

            $this->load->model('plugins/template_msg_model');

            $hotel_checkout_notice = array(
                'inter_id'=>$inter_id,
                'openid'=>$openid,
                'type'=>'预约退房',
                'check_out_time'=>date('Y-m-d H:i',$data['checkout_time'])//这个传实际记录用户申请的退房的时间
            );

            $return = $this->template_msg_model->send_checkout_or_invoice_msg($hotel_checkout_notice,'hotel_checkout_notice',1);

            MYLOG::w('send:'.json_encode($hotel_checkout_notice).';return:'.json_encode($return),'checkoutnotify');

            if(isset($detail['hn']) && isset($data['hid']) && $data['hid']!=0){

                $params = array(
                    'inter_id' =>$inter_id,
                    'hotel'=>$detail['hn'],
                    'hotel_id'=>$data['hid'],
                    'check_out_time'=>date('Y-m-d H:i',$data['checkout_time']),
                    'room_num'=>$data['room_nums']
                );

                $this->Invoice_model->send_checkout_apply_notice($params);
            }

            $info['code'] = 2;
            $info['msg'] = '提交成功';
            $info['need'] = $need;
        }

        echo json_encode($info);

    }


    public function my_invoice(){

        $data = $this->common_data;

        $post_data = $this->input->post();

        if(!empty($post_data)){
            $this->session->set_userdata($this->openid.'hotel_invoice_bookroom',$post_data);
        }else{
            $post_data = $this->session->userdata($this->openid.'hotel_invoice_bookroom');
        }

        $data['pagetitle'] = '选择发票';

        $this->load->model('invoice/Invoice_model');

        $openid = $this->openid;
        $inter_id = $this->inter_id;

        $data['list'] = $this->Invoice_model->my_invoice($inter_id,$openid);

        $this->display('invoice/receipt_list', $data);

    }


    public function choose_invoice(){

        $data = $this->common_data;

        $this->load->model('invoice/Invoice_model');

        $openid = $this->openid;
        $inter_id = $this->inter_id;

        echo json_encode($this->Invoice_model->my_invoice($inter_id,$openid));


    }


    public function choose(){     //开具发票

        $data = $this->common_data;

        $this->load->model('invoice/Invoice_model');
        $this->load->model('hotel/Hotel_model');

        $data['pagetitle'] = '开具发票';

        $data['hid']=0;

        $checkout_info = $this->input->post();

        if(!isset($checkout_info['hid']) && !isset($checkout_info['oid'])){
            redirect(site_url('hotel/Invoice/submit_result').'?id='.$this->inter_id);
        }



        if(isset($checkout_info['oid']) && (empty($checkout_info['oid']) || $checkout_info['oid']==0)){  //检查当日是否已经有扫码退过房
            if(isset($checkout_info['hid']) && $checkout_info['hid']!=0){
                $this->get_checkout($checkout_info['hid']);
            }
        }


        if(isset($checkout_info['oid']) && $checkout_info['oid']!=0){    //有订单退房

            $order = $this->Invoice_model->getOrderById($checkout_info['oid'],$this->inter_id);

            $data['hotel'] = $this->Hotel_model->get_hotel_detail($this->inter_id,$order['hotel_id']);
            $data['hid'] =  $order['hotel_id'];

            $check = $this->Invoice_model->getCheckOutByOrderid($order['orderid']);

            if($check){
                if($check['invoice_list_id']==0){
                    redirect(site_url('hotel/Invoice/submit_result').'?type=1&id='.$this->inter_id);
                }else{
                    redirect(site_url('hotel/Invoice/submit_result').'?id='.$this->inter_id);
                }
            }

        }

        $data['checkout_time'] = '';
        $data['room_nums'] = '';
        $data['name'] = '';
        $data['tel'] = '';

        if(isset($checkout_info['checkout_time']) && isset($checkout_info['room_nums'])){
            $data['checkout_time'] = $checkout_info['checkout_time'];
            $data['room_nums'] = $checkout_info['room_nums'];
        }

        if(isset($checkout_info['name']))$data['name']=$checkout_info['name'];
        if(isset($checkout_info['tel']))$data['tel']=$checkout_info['tel'];

        if(isset($checkout_info['oid'])){

            $data['oid'] = $checkout_info['oid'];
            $order = $this->Invoice_model->getOrderById($data['oid'],$this->inter_id);

            if($order){              //订单如果有开发票则获取发票信息

                $data['order'] = $order;

                $invoice_list = $this->Invoice_model->getInvoiceListByOid($order['orderid']);

                $data['invoice'] = $invoice_list;

                if(!empty($invoice_list['invoice_content'])){
                    $data['invoice']['invoice_content'] = json_decode($invoice_list['invoice_content']);
                }

                $data['hid'] = $order['hotel_id'];

            }

        }else{
            $data['oid']='';
        }

        if(isset($checkout_info['hid']) && !isset($order['hotel_id']))$data['hid']=$checkout_info['hid'];

        $data ['hotel'] = $this->Hotel_model->get_hotel_detail ( $this->inter_id, $data['hid']);

        if(!isset($data['invoice'])){

            $invoice_list = $this->Invoice_model->getLastInvoiceList($this->openid);

            if($invoice_list){

                unset($invoice_list['invoice_list_id']);

                $data['invoice'] = $invoice_list;

                if(!empty($invoice_list['invoice_content'])){
                    $data['invoice']['invoice_content'] = json_decode($invoice_list['invoice_content']);
                }
            }



        }

        $this->display('invoice/step2', $data);
    }


    public function edit_invoice(){

        $data = $this->common_data;

        $data['pagetitle'] = '新增发票';

        $openid = $this->openid;

        $this->display('invoice/receipt_info', $data);

    }


    public function submit_result(){

        $data = $this->common_data;

        $data['type'] = $this->input->get('type');

        $data['pagetitle'] = '预约成功';

        $this->display('invoice/submit_status', $data);

    }


    public function invoice_post(){

        $data = $this->input->post();
        $openid = $this->openid;
        $inter_id = $this->inter_id;

        $this->load->model('invoice/Invoice_model');

        $post_invoice = array(
            'openid'=>$openid,
            'inter_id'=>$inter_id,
            'title'=>$data['title'],
            'status'=>1,
            'createtime'=>date('Y-m-d H:i:s',time())
        );

        $content = array();

        if($data['receipt']==2){

            $post_invoice['type']=2;
            $content = array(
                'code'=>$data['code'],
                'bank'=>$data['bank'],
                'account'=>$data['account'],
//                    'amount'=>$data['amount'],
                'phonecall'=>$data['phonecall'],
                'address'=>$data['address']
            );

        }


            $content['title']=$data['title'];
            $content['type'] =$data['receipt'];


        $post_invoice['content'] = json_encode($content);

        $invoice_id =  $this->Invoice_model->new_invoice($post_invoice);

        $info = array(
            'code' => 1,
            'msg' => '新增失败'
        );


        if($invoice_id){

            $info['code'] = 2;
            $info['msg'] = '新增成功';
        }

        echo json_encode($info);

    }


    public function book_checkout(){

        $this->load->model('invoice/Invoice_model');
        $this->load->model('hotel/Order_model');
        $this->load->library('MYLOG');

        $openid=$this->openid;
        $inter_id=$this->inter_id;

        $data = $this->input->post();

        $createtime = date('Y-m-d H:i:s',time());

        $post_data = array(
            'inter_id'=>$inter_id,
            'openid'=>$openid,
            'room_num'=>$data['room_nums'],
            'check_out_time'=>date('Y-m-d H:i',$data['checkout_time']),
            'create_time'=>$createtime,
            'channel' => 'scan',
            'hotel_id' => $data['hid']
        );

        $detail = array();

        if(isset($data['oid']) && $data['oid']!=0){

            $order = $this->Invoice_model->getOrderById($data['oid'],$inter_id);

            if($order){

                $order_item = $this->Invoice_model->getOrderItem($order['orderid']);

                $hotel_name = $this->Invoice_model->getHotelName($order['orderid']);

                $detail = array(
                    'hn'=>$hotel_name['name'],
                    'rn'=>$order_item['roomname'],
                    'pn'=>$order_item['price_code_name'],
                    'name'=>$data['name'],
                    'tel'=>$data['tel']
                );

                $post_data['detail'] = json_encode($detail);
                $post_data['room_id'] = $order_item['room_id'];
                $post_data['orderid']=$order['orderid'];
                $post_data['hotel_id']=$order['hotel_id'];
                $post_data['channel'] = 'weixin';
            }
        }

        if(!isset($post_data['detail']) && $data['hid']!=0){

            $this->load->model('hotel/Hotel_model');

            $hotel_info = $this->Hotel_model->get_hotel_detail($inter_id,$data['hid']);

            if(!empty($hotel_info)){
                $detail['hn']=$hotel_info['name'];
                $detail['name']=$data['name'];
                $detail['tel']=$data['tel'];
                $post_data['detail'] = json_encode($detail);
            }
        }


        $res = $this->Invoice_model->new_checkout($post_data);

        if($res){

            if($order){
                $this->Invoice_model->update_order_invoice($inter_id,$openid,$order['orderid']);
            }

            $this->load->model('plugins/template_msg_model');

            $hotel_checkout_notice = array(
                'inter_id'=>$inter_id,
                'openid'=>$openid,
                'type'=>'预约退房',
                'check_out_time'=>date('Y-m-d H:i',$data['checkout_time'])//这个传实际记录用户申请的退房的时间
            );

            $return = $this->template_msg_model->send_checkout_or_invoice_msg($hotel_checkout_notice,'hotel_checkout_notice',1);

            MYLOG::w('send:'.json_encode($hotel_checkout_notice).';return:'.json_encode($return),'checkoutnotify');

            if(isset($detail['hn']) && isset($data['hid']) && $data['hid']!=0){

                $params = array(
                    'inter_id' =>$inter_id,
                    'hotel'=>$detail['hn'],
                    'hotel_id'=>$data['hid'],
                    'check_out_time'=>date('Y-m-d H:i',$data['checkout_time']),
                    'room_num'=>$data['room_nums']
                );

                $this->Invoice_model->send_checkout_apply_notice($params);
            }
        }

        redirect(site_url('hotel/Invoice/submit_result').'?type=1&id='.$this->inter_id);

    }


    public function get_checkout($hotel_id='',$orderid=''){   //判断是否可以预约退房

        $this->load->model('invoice/Invoice_model');
        $this->load->library('MYLOG');

        if(!empty($orderid)){      //

            $check = $this->Invoice_model->getCheckOutByOrderid($orderid);

            $order = $this->Invoice_model->getOrderByOrderid($orderid,$this->inter_id);

            $hotel = $this->Hotel_model->get_hotel_detail($this->inter_id,$order['hotel_id']);



        }elseif(!empty($hotel_id)){      //

            $check =  $this->Invoice_model->check_checkout($this->inter_id,$this->openid,$hotel_id);

            $hotel = $this->Hotel_model->get_hotel_detail($this->inter_id,$hotel_id);

        }else{

            $check = false;
        }

        if($check){
            if($hotel['invoice']==1){
                redirect(site_url('hotel/Invoice/submit_result').'?type=1&id='.$this->inter_id);
            }else{
                redirect(site_url('hotel/Invoice/submit_result').'?id='.$this->inter_id);
            }
        }

    }

    public function processing(){

        $this->load->model('invoice/Invoice_model');

        $data = $this->input->get();

        $this->display('invoice/processing', $data);

    }
    
    public function asyn_invoices(){
        $this->load->model ( 'invoice/Invoice_model' );
        $invoice = $this->Invoice_model->my_invoice ( $this->inter_id, $this->openid );
        $info = array (
                's' => 0,
                'data' => array () 
        );
        if (! empty ( $invoice )) {
            $default_content = $this->Invoice_model->default_zz_content;
            $info ['s'] = 1;
            foreach ( $invoice as $inv ) {
                $tmp = array (
                        'invoice_id' => $inv ['invoice_id'],
                        'title' => $inv ['title'] 
                );
                if ($inv ['type'] == 1) {
                    $info ['data'] ['pp'] [] = $tmp;
                } else {
                    $tmp += array_merge ( $default_content, json_decode ( $inv ['content'], TRUE ) );
                    $info ['data'] ['zz'] [] = $tmp;
                }
            }
        }
        echo json_encode ( $info, JSON_UNESCAPED_UNICODE );
    }
}