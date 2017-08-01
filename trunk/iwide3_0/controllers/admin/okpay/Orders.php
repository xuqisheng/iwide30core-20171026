<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends MY_Admin {

    protected $label_module= '快乐付';
    protected $label_controller= '订单列表';
    protected $label_action= '订单';
    protected $admin_profile;

    function __construct(){
        parent::__construct();
        $this->load->helper('appointment');
        $this->admin_profile = $this->session->userdata ( 'admin_profile' );
        $this->admin_profile['ip'] = get_client_ip();
    }

    protected function main_model_name()
    {
        return 'okpay/Okpay_model';
    }

    function index(){
        $this->grid();
    }

    public function grid_bak_()
    {
        $inter_id= $this->session->get_admin_inter_id();
        if($inter_id== FULL_ACCESS) $filter= array();
        else if($inter_id) $filter= array('inter_id'=>$inter_id );
        else $filter= array('inter_id'=>'deny' );
        if(is_ajax_request())
            $get_filter= $this->input->post();
        else
            $get_filter= $this->input->get('filter');

        if( !$get_filter) $get_filter= $this->input->get('filter');

        if(is_array($get_filter)) $filter= $get_filter+ $filter;
        $this->_grid($filter);
    }

    public function edit_post()
    {
        $this->label_action= '编辑订单';
        $this->_init_breadcrumb($this->label_action);

        $model_name= $this->main_model_name();
        $model= $this->_load_model($model_name);
        $pk= $model->table_primary_key();

        $this->load->library('form_validation');
        $post= $this->input->post();
        $labels= $model->attribute_labels();
        $base_rules= array(
            'out_trade_no'=> array(
                'field' => 'out_trade_no',
                'label' => $labels['out_trade_no'],
                'rules' => 'trim|required',
            ),
            'trade_no'=> array(
                'field' => 'trade_no',
                'label' => $labels['trade_no'],
                'rules' => 'trim|required',
            ),
            'money'=> array(
                'field' => 'money',
                'label' => $labels['money'],
                'rules' => 'trim|required',
            ),
            'pay_money'=> array(
                'field' => 'pay_money',
                'label' => $labels['pay_money'],
                'rules' => 'trim|required',
            ),
            'discount_money'=> array(
                'field' => 'discount_money',
                'label' => $labels['discount_money'],
                'rules' => 'trim|required',
            ),
            'pay_type_desc'=> array(
                'field' => 'sale',
                'label' => $labels['pay_type_desc'],
                'rules' => 'trim|required',
            ),
            'sale'=> array(
                'field' => 'sale',
                'label' => $labels['sale'],
                'rules' => 'trim|required',
            ),
            'hotel_name'=> array(
                'field' => 'hotel_name',
                'label' => $labels['hotel_name'],
                'rules' => 'trim|required',
            ),
            'pay_status'=> array(
                'field' => 'pay_status',
                'label' => $labels['pay_status'],
                'rules' => 'trim|required',
            )
        );

        $adminid= $this->session->get_admin_id();
        if( empty($post[$pk]) ){
            //add data.
            $this->form_validation->set_rules($base_rules);

            if ($this->form_validation->run() != FALSE) {
                $post['create_time'] = time();
                $post['inter_id']    = $this->session->get_admin_inter_id();
                $result= $model->m_sets($post)->m_save($post);
                $message= ($result)?
                    $this->session->put_success_msg('已新增数据！'):
                    $this->session->put_notice_msg('此次数据保存失败！');
                $this->_log($model);
                $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));

            } else
                $model= $this->_load_model();

        } else {
            $this->form_validation->set_rules($base_rules);
            if ($this->form_validation->run() != FALSE) {
                $post['update_time']= time();
                $result= $model->m_sets($post)->m_save($post);
                $message= ($result)?
                    $this->session->put_success_msg('已保存数据！'):
                    $this->session->put_notice_msg('此次数据修改失败！');
                $this->_log($model);
                $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));

            } else
                $model= $model->load($post[$pk]);
        }

        //验证失败的情况
        $validat_obj= _get_validation_object();
        $message= $validat_obj->error_html();
        //页面没有发生跳转时用寄存器存储消息
        $this->session->put_error_msg($message, 'register');

        $fields_config= $model->get_field_config('form');
        $view_params= array(
            'model'=> $model,
            'fields_config'=> $fields_config,
            'check_data'=> TRUE,
        );
        $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
        echo $html;
    }

    /**
     * 更改订单备注
     * $id  订单ID
     * $remark 备注信息
     */
    public function save_remark()
    {
        $id     = $this->input->post("id",true);
        $remark = $this->input->post("remark",true);
        if (empty($id) || empty($remark))
        {
            ajax_return(0,'请输入订单备注');
        }
        $this->load->model('okpay/okpay_model');

        $where = array('id'=>intval($id));
        $data = array('remark'=>addslashes($remark));

        $res = $this->db->update('okpay_orders',$data,$where);

        if ($res > 0)
        {
            ajax_return(1,'保存成功');
        }
        else
        {
            ajax_return(0,'保存失败');
        }
    }

    /**
     * 退款
     * $id 订单ID
     * $id 退款金额
     */
    public function refund()
    {
        $this->load->helper('appointment');
        $id = $this->input->post("id",true);
        $money = $this->input->post("money",true);

        $inter_id= $this->session->get_admin_inter_id();

        if (empty($money) || $money < '0.01')
        {
            echo json_encode ( array (
                'status' =>0,
                'message' => "请输入退款金额"
            ));
            die;
        }

        $preg = '/^([\d]{0,10}|0)(\.[\d]{1,2})?$/';
        if(!preg_match($preg,$money))
        {
            echo json_encode ( array (
                'status' =>0,
                'message' => "请输入正确的金额"
            ));
            die;
        }


        if(!empty($id) && !empty($inter_id)){
            $this->load->model('okpay/okpay_model');
            $result = $this->okpay_model->get_order_info($inter_id,$id);

            if(!empty($result)){
                if(3 == intval($result['pay_status'])){

                        //判断金额最多可退{%} 元 author:沙
                    $money = $money * 100;
                    $pay_money = $result['pay_money'] * 100;
                    if ($money > $pay_money)
                    {
                        echo json_encode ( array (
                            'status' =>0,
                            'message' => "最多可退{$result['pay_money']}元"
                        ));
                        die;
                    }
                    else
                    {
                        $refund_fee = $money;
                    }

                    //添加退款时间限制：付款完成30分钟后不允许退款 stgc20161018

                    /**  2017.3-20 产品需求 关闭30分钟内退款操作 author:沙沙
                    if(0 && time() - $result['pay_time'] > 1800){
                        echo json_encode ( array (
                            'status' =>1,
                            'message' => '付款完成30分钟后不允许退款'
                        ));
                        die;
                    }
                     */

                    //创建退款订单成功后， 在执行微信退款操作
                    $out_refund_no =  "TK".time().rand(1000,9909);
                    $data = array();
                    $data['inter_id']       = $result['inter_id'];
                    $data['hotel_id']       = $result['hotel_id'];
                    $data['nickname']       = $result['nickname'];
                    $data['openid']         = $result['openid'];
                    $data['refund_money']   = formatMoney($refund_fee/100);//退款金额不能超过实际支付金额单位:分
                    $data['trade_no']       = $result['trade_no'];
                    $data['out_trade_no']   = $result['out_trade_no'];
                    $data['refund_status']  = 1;
                    $data['out_refund_no']  = $out_refund_no;

                    //增加操作账号信息
                    $data['admin_name'] = $this->admin_profile['username'];
                    $data['client_ip']  = $this->admin_profile['ip'];

                    $this->load->model("okpay/Okpay_refund_model");
                    $r = $this->Okpay_refund_model->create_okpay_refund($data);

                    //print_r($result['pay_way']);exit;

                    if($r){
                        //判断是哪种支付方式
                        if(2 == $result['pay_way']){//余额支付 stgc 20160912
                            //处理余额退款
                            $balance_refund = $this->okpay_model->balance_refund($result['inter_id'],$result['openid'], formatMoney($refund_fee/100));
                            if($balance_refund){//退款成功
                                //修改退款单 和订单表的订单状态
                                $refund_data = array();
                                $refund_data['refund_time'] = time();
                                $refund_data['update_time'] = time();
                                $refund_data['refund_status'] = 3;
                                $refund_data['refund_fee'] = $refund_fee;//数据表中的字段按单位：分 来设计的 要*100换算元
                                //开启事务
                                $this->db->trans_begin();
                                $refund_r = $this->Okpay_refund_model->set_okpay_refund($out_refund_no,$result['trade_no'],$refund_data);
                                if($refund_r){
                                    //修改订单表，表明订单退款状态
                                    $this->load->model('okpay/Okpay_model');
                                    //print_r($result);exit;
                                    $up_order = $this->Okpay_model->set_okpay_model($result['trade_no'],$result['out_trade_no'],4,formatMoney($refund_fee/100));

                                    //提交事务
                                    if ($up_order > 0)
                                    {
                                        $this->db->trans_commit();
                                        echo json_encode ( array (
                                            'status' =>1,
                                            'message' => '已退款成功，请刷新订单'
                                        ));
                                        die;
                                    }
                                    //回滚事务
                                    else
                                    {
                                        $this->db->trans_rollback();
                                    }

                                }else{
                                    echo json_encode ( array (
                                        'status' =>1,
                                        'message' => '已经提交退款的操作，请刷新订单'
                                    ));
                                    die;
                                }
                            }else{
                                echo json_encode ( array (
                                    'status' =>0,
                                    'message' => '暂时无法退款，发起退款失败，请稍后再试'
                                ) );
                                die;
                            }
                        }elseif(1 == $result['pay_way'] || 3==$result['pay_way']){//微信支付 设备搜
                            $total_fee = intval($result['pay_money'] * 100);

                            $this->load->model('okpay/Okpay_wxpay_model');

                            //分账退款
                            $this->load->model ( 'iwidepay/iwidepay_model' );
                            $iwidepay_refund = array(
                                'orderDate' => date('Ymd'),
                                'orderNo' => $out_refund_no,
                                'requestNo' => md5(time()),
                                'transAmt' => $refund_fee,//单位：分
                                'returnUrl'=>'http://cmbcpaytest.jinfangka.com/index.php/iwidepay/cmbc/pay/success',
                                'refundReson' => '快乐付退款',
                            );
                            $refund_result = $this->iwidepay_model->refund($iwidepay_refund,$result['out_trade_no']);
                            if(isset($refund_result['status']) && isset($refund_result['message']) &&$refund_result['status']==2 && $refund_result['message']=='empty'){
                            $refund_result = $this->Okpay_wxpay_model->refund($result['inter_id'],$result['trade_no'],$total_fee,$refund_fee,$out_refund_no);
                            }
                            if(is_array($refund_result) && "SUCCESS" == $refund_result['return_code'])
                            {
                                $trade_no = $refund_result['transaction_id'];
                                //$out_refund_no = $refund_result['out_refund_no'];

                                $refund_data = array();
                                $refund_data['appid'] = isset($refund_result['appid'])?$refund_result['appid']:'';
                                $refund_data['mch_id'] = isset($refund_result['mch_id'])?$refund_result['mch_id']:'';
                                $refund_data['refund_id'] = $refund_result['refund_id'];
                                $refund_data['refund_fee'] = $refund_result['refund_fee'];
                                $refund_data['coupon_refund_fee'] = isset($refund_result['coupon_refund_fee'])?$refund_result['coupon_refund_fee']:'';
                                //开启事务
                                $this->db->trans_begin();
                                $refund_r = $this->Okpay_refund_model->set_okpay_refund($out_refund_no,$result['trade_no'],$refund_data);
                                if($refund_r){
                                    //修改订单表，表明订单退款状态
                                    $this->load->model('okpay/Okpay_model');
                                    $up_order = $this->Okpay_model->set_okpay_model($result['trade_no'],$result['out_trade_no'],4,formatMoney($refund_fee/100));

                                    //提交事务
                                    if ($up_order > 0)
                                    {
                                        $this->db->trans_commit();
                                        echo json_encode ( array (
                                            'status' =>1,
                                            'message' => '退款处理中，请刷新订单'
                                        ));
                                        die;
                                    }
                                    //回滚事务
                                    else
                                    {
                                        $this->db->trans_rollback();
                                    }

                                }else{
                                    echo json_encode ( array (
                                        'status' =>1,
                                        'message' => '已经提交退款的操作，请刷新订单'
                                    ));
                                }

                            }else{
                                echo json_encode ( array (
                                    'status' =>0,
                                    'message' => "向服务器发起退款的时候失败，请稍后再试 {$refund_result['return_msg']}"
                                ) );
                            }
                        }elseif(11 == $result['pay_way']){//威富通
                            $total_fee = intval($result['pay_money'] * 100);
                            $this->load->model('okpay/Okpay_wxpay_model');
                            $refund_result = $this->Okpay_wxpay_model->weifutong_refund($result['inter_id'],$result['trade_no'],$total_fee,$refund_fee,$out_refund_no,$result['hotel_id']);
                            if(is_array($refund_result)){
                                $trade_no = $refund_result['transaction_id'];
                                $out_refund_no = $refund_result['out_refund_no'];

                                $refund_data = array();
                              //  $refund_data['appid'] = $refund_result['appid'];
                             //   $refund_data['mch_id'] = $refund_result['mch_id'];
                                $refund_data['refund_id'] = $refund_result['refund_id'];
                                $refund_data['refund_fee'] = $refund_result['refund_fee'];
                                $refund_data['coupon_refund_fee'] = $refund_result['coupon_refund_fee'];
                                //开启事务
                                $this->db->trans_begin();
                                $refund_r = $this->Okpay_refund_model->set_okpay_refund($out_refund_no,$trade_no,$refund_data);

                                if($refund_r){
                                    //修改订单表，表明订单退款状态
                                    $this->load->model('okpay/Okpay_model');
                                    $up_order = $this->Okpay_model->set_okpay_model($trade_no,$result['out_trade_no'],4,formatMoney($refund_fee/100));

                                    //提交事务
                                    if ($up_order > 0)
                                    {
                                        $this->db->trans_commit();
                                        echo json_encode ( array (
                                            'status' =>1,
                                            'message' => '已退款成功，请刷新订单'
                                        ));
                                        die;
                                    }
                                    //回滚事务
                                    else
                                    {
                                        $this->db->trans_rollback();
                                    }

                                }else{
                                    echo json_encode ( array (
                                        'status' =>1,
                                        'message' => '已经提交退款的操作，请刷新订单'
                                    ));
                                }

                            }else{
                                echo json_encode ( array (
                                    'status' =>0,
                                    'message' => '发起退款的时候失败，请稍后再试'
                                ) );
                            }
                        }
                    }else{
                        echo json_encode ( array (
                            'status' =>0,
                            'message' => '暂时无法退款，发起退款失败，请稍后再试'
                        ) );
                    }
                }else if(4 == intval($result['pay_status'])){
                    echo json_encode ( array (
                        'status' =>0,
                        'message' => '用户已经退款，无法重复退款'
                    ) );
                }else{
                    echo json_encode ( array (
                        'status' =>0,
                        'message' => '无法退款，用户尚未支付'
                    ) );
                }

            }else{
                echo json_encode ( array (
                    'status' =>0,
                    'message' => '无效的数据信息'
                ) );
            }
        }else{
            echo json_encode ( array (
                'status' =>0,
                'message' => '无效的操作信息'
            ) );
        }
    }

    public function grid(){
        $inter_id= $this->session->get_admin_inter_id();
        if($inter_id== FULL_ACCESS) $filter= array();
        else if($inter_id) $filter= array('inter_id'=>$inter_id );
        else $filter= array('inter_id'=>'deny' );
        $entity_id = $this->session->get_admin_hotels ();//var_dump($entity_id);die;
        $filter['entiti_hotel_id'] = array();//
        if(!empty($entity_id)){
            $hotel_ids = explode ( ',', $entity_id );
            $filter['entiti_hotel_id'] = $hotel_ids;
        }
        $has_refund_acl = 1;
        //查看是否有退款权限
        $acl_array = $this->session->allow_actions;
        $acl_array = $acl_array [ADMINHTML];
        if (($acl_array != FULL_ACCESS) && (! isset ( $acl_array ['okpay'] ['orders'] ) || ! in_array ( 'refund', $acl_array ['okpay'] ['orders'] ))) {
            $has_refund_acl = 0;//没有权限
        }


        $avgs = array();
        $avgs['hotel_name']         = $this->input->post('hotel_name');
        $avgs['pay_begin_time']       = $this->input->post('begin_time');
        $avgs['pay_end_time']         = $this->input->post('end_time');
        $avgs['pay_type']          = $this->input->post('pay_type');//c场景id
        $avgs['out_trade_no']       = $this->input->post('out_trade_no');
        $avgs['pay_status']         = $this->input->post('pay_status');
        $ext = $this->input->post('export');

        $keys = $this->uri->segment(4);
        $keys = explode('_', $keys);

        if(!empty($keys[0])){
            $avgs['hotel_name'] = urldecode($keys[0]);
        }
        if(!empty($keys[1])){
            $avgs['pay_begin_time'] = $keys[1];
        }
        if(!empty($keys[2])){
            $avgs['pay_end_time'] = $keys[2];
        }
        if(!empty($keys[3])){
            $avgs['pay_type'] = $keys[3];
        }
        if(!empty($keys[4])){
            $avgs['out_trade_no'] = $keys[4];
        }
        if(!empty($keys[5])){
            $avgs['pay_status'] = $keys[5];
        }
        if(!empty($avgs)){
            $filter = array_merge($filter,$avgs);
        }
        //系统默认加载的数据为一周
        if(empty($filter['pay_begin_time']) && empty($filter['pay_end_time'])){
            $filter['pay_begin_time'] = date('Y-m-d',strtotime(date('Y-m-d 00:00:00',time()-7*24*3600)));
            $filter['pay_end_time'] = date('Y-m-d',strtotime(date('Y-m-d 23:59:59',time())));
        }
        $config['per_page']          = 30;
        $page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
        //是否导出
        if($ext && $ext=='1'){
            $this->ext_okpay_orders_report($filter,$config['per_page'],$page);
            die;
        }
        $this->load->model('okpay/okpay_model');
        $confs = $this->okpay_model->grid_fields();
        $all_keys = $this->okpay_model->attribute_labels();

        $this->load->library('pagination');


        $config['use_page_numbers']  = TRUE;
        $config['cur_page']          = $page;
        $res = $this->okpay_model->get_okpay_orders_list($filter,$config['per_page'] ,$config['cur_page']);

        $config['uri_segment']       = 5;

        $config['numbers_link_vars'] = array('class'=>'number');
        $config['cur_tag_open']      = '<a class="number current" href="#">';
        $config['cur_tag_close']     = '</a>';
        $config['base_url']          = site_url("okpay/orders/grid/".$avgs['hotel_name'].'_'.$avgs['pay_begin_time'].'_'.$avgs['pay_end_time'].'_'.$avgs['pay_type'].'_'.$avgs['out_trade_no'].'_'.$avgs['pay_status']);
        $config['total_rows']        = $this->okpay_model->get_okpay_orders_list_count($filter);
        $config['cur_tag_open'] = '<li class="paginate_button active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="paginate_button">';
        $config['num_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="paginate_button first">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="paginate_button last">';
        $config['last_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="paginate_button previous">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="paginate_button next">';
        $config['next_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        //查询所在公众号的场景
        $ground_types = $this->okpay_model->get_hotel_okpay_type_list($filter['inter_id'],$filter['entiti_hotel_id']);
        $oktypes = array();
        if(!empty($ground_types)){
            foreach($ground_types as $ok=>$ov){
                $oktypes[$ov['id']]=$ov['name'];
            }
        }
        //var_dump($ground_types);die;
        $view_params= array(
            'pagination' => $this->pagination->create_links(),
            'res'        => $res->result_array(),
            'confs'      => $confs,
            'all_keys'      => $all_keys,
            'ground_types'     => $oktypes,
            'posts'      => $filter,
            'total'      => $config['total_rows'],
            'has_refund_acl'=>$has_refund_acl,
            'paid'       => array(0=>'未支付',1=>'微信已支付',2=>'门店付款'),
            'pay_status_list' => array(1=>'未支付',3=>'已支付',4=>'已退款'),
            'paytype'    => array('1'=>'微信支付','2'=>'储值','3'=>'扫码设备','11'=>'威富通'),
            'hotels'    => $hotels,
        );

        $html= $this->_render_content($this->_load_view_file('grid'), $view_params, TRUE);
        echo $html;
    }

    public function ext_okpay_orders_report($filter = array(),$per_page = 30,$page=1){

        if(empty($filter)){
            echo 'data error!';
            die;
        }
        /*$per_page       = 30;
        $page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $per_page;*/

        $this->load->model('okpay/okpay_model');
        //$admin_profile = $this->session->userdata('admin_profile');
        //$avgs['inter_id'] = $admin_profile['inter_id'];

        $confs = $this->okpay_model->grid_fields();  //需要列
        $all_keys = $this->okpay_model->attribute_labels(); //所有列
        $confs[] = 'pay_way';
        $data = "";
        foreach ($confs as $key=>$item){
            $data = $data.iconv('utf-8','gb2312',$all_keys[$item]).",";
        }
        $res = $this->okpay_model->get_okpay_orders_list($filter);
        $result = $res->result_array();

        $data = $data."\n";
        $paystatus = array(1=>'未支付',3=>'已支付',4=>'已退款(部分)');
        foreach ($result as $item){
            foreach ($confs as $key=>$val){
                if($val == "create_time" || $val == "update_time" || $val == "pay_time"){
                    $time = empty($item[$val]) ? "":date("Y-m-d H:i:s",$item[$val]);
                    $data = $data.iconv('utf-8','gb2312',$time." ").",";
                }elseif($val == "pay_money"){
                    $data = $data . formatMoney(($item[$val]*100 - $item['refund_money']*100)/100).',';
                }else{
                    if($val == "pay_status"){
                        if(bcsub($item['pay_money'],$item['refund_money'],2) == 0){//说明全部退款
                            $data = $data.iconv('utf-8','gb2312','全部退款').",";
                        }else{
                            $pay_st = isset($paystatus[$item[$val]])?$paystatus[$item[$val]]:'';
                            $data = $data.iconv('utf-8','gb2312',$pay_st).",";
                        }
                    }elseif($val == "remark"){
                        $data = $data.iconv('utf-8','gb2312',str_replace("\n",' ',str_replace("\r",' ',$item[$val]))).",";
                    }elseif($val == "pay_way"){
                        $pay_way = $item[$val]==1?'微信':'余额';
                        $data = $data.iconv('utf-8','gb2312',$pay_way).",";
                    }else{
                        $data = $data.iconv('utf-8','gb2312',$item[$val]).",";
                    }
                }
            }
            $data = $data."\n";
        }
        // 发送标题强制用户下载文件
        header ('Content-Type: text/csv' );
        header ('Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.csv"' );
        header ('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $data;
    }
}
