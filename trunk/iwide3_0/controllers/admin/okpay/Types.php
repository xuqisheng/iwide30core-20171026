<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Types extends MY_Admin_Api {

    protected $label_module= '快乐付';
    protected $label_controller= '场景列表';
    protected $label_action= '场景';
    
    function __construct(){
        parent::__construct();
    }
    
    protected function main_model_name()
    {
        return 'okpay/Okpay_type_model';
    }
    function index(){
        $this->grid();
    }
    
    public function grid()
    {
        $inter_id= $this->session->get_admin_inter_id();
        $filter= array('inter_id'=>$inter_id );
        //get请求接收参数
        $params= $this->input->get();
        if(is_array($params) && count($params)>0 )
            $filter= array_merge($params, $filter);
        //post请求接收参数
        $post = $this->input->post();
        if(is_array($post)){
            $filter = array_merge($post,$filter);
        }//var_dump($filter);die;
       // $avgs = array();
        $avgs= array('inter_id'=>$inter_id );
        $avgs['name']         = $this->input->post('name');
        $avgs['hotel_id']       = $this->input->post('hotel_id');
        $avgs['status']          = $this->input->post('status');//启用状态
        $keys = $this->uri->segment(4);
        $keys = explode('_', $keys);
        if(isset($keys[0]) && !empty($keys[0])){
            $avgs['name'] = $keys[0];
        }
        if(isset($keys[1]) && !empty($keys[1])){
            $avgs['hotel_id'] = $keys[1];
        }
        if(isset($keys[2]) && !empty($keys[2])){
            $avgs['status'] = $keys[2];
        }
        if (is_array ( $filter )) {
            $this->load->model ( 'wx/publics_model' );
            $publics = $this->publics_model->get_public_hash ( $filter );
            $publics = $this->publics_model->array_to_hash ( $publics, 'name', 'inter_id' );
        }
        $this->load->library('pagination');
        $config['per_page']          = 50;
        $page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
        $config['use_page_numbers']  = TRUE;
        $config['cur_page']          = $page;
        $this->load->model('okpay/okpay_type_model');
        $res = $this->okpay_type_model->get_types_info_list($avgs,$config['per_page'],$config['cur_page']);

        //获取公众号下的酒店
        $this->load->model ( 'hotel/hotel_model' );
        $hotels = $this->hotel_model->get_hotel_hash ( array('inter_id'=>$inter_id,'status'=>1) );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
        $config['uri_segment']       = 5;
        $config['numbers_link_vars'] = array('class'=>'number');
        $config['cur_tag_open']      = '<a class="number current" href="#">';
        $config['cur_tag_close']     = '</a>';
        $config['base_url']          = site_url('okpay/types/grid/'.$avgs['name'].'_'.$avgs['hotel_id'].'_'.$avgs['status']);
        $config['total_rows']        = $this->okpay_type_model->get_types_info_count($avgs,$config['per_page'],$config['cur_page']);;
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
        $groups = $this->okpay_type_model->get_all_typesgroup();
        $view_params= array(
            'pagination' => $this->pagination->create_links(),
            'hotels'     => $hotels,
            'posts'      => $avgs,
            'res'        => $res,
            'publics'    => $publics,
            'groups'     => $groups,
            'total'      => $config['total_rows'],
        );

        $html= $this->_render_content($this->_load_view_file('grid'), $view_params, TRUE);
        echo $html;
    }

    public function edit()
    {
        $this->label_action= '信息维护';
        $this->_init_breadcrumb($this->label_action);

        $model_name= $this->main_model_name();
        $model= $this->_load_model($model_name);
        $inter_id= $this->session->get_admin_inter_id();
        if($inter_id== FULL_ACCESS) $filter= array();
        $id= intval($this->input->get('ids'));
        if($id){
            $model= $model->load($id);
        }

        if(!$model) $model= $this->_load_model();
        $fields_config= $model->get_field_config('form');

        $this->load->model ( 'hotel/hotel_model' );
        $hotels = $this->hotel_model->get_hotel_hash ( array('inter_id'=>$inter_id) );
        $hotels_info = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
        //如果是新增的，就读第一家酒店的分销员 最多读30条 编辑的就读当前酒店的.
        $hotel_id = 0;
        if(empty($model->m_get('hotel_id'))){
            $hotel_id = !empty($hotels[0]['hotel_id'])?$hotels[0]['hotel_id']:'--';
        }else{
            $hotel_id = $model->m_get('hotel_id');
        }
        $this->load->model('distribute/qrcodes_model');
        $query = $this->qrcodes_model->get_salers($inter_id,1,'','',NULL,$hotel_id,NULL,2);
        $salers = $query->result_array();
        $tmp = array();
        $saler_name = array();
        $msgsaler_ids = !empty($model->m_get('msgsaler'))?explode(',',$model->m_get('msgsaler')):array();
        if(!empty($salers)){
            foreach($salers as $k=>$v){
                $tmp[$v['qrcode_id']] = $v['name'];
                if(in_array($v['qrcode_id'],$msgsaler_ids)){
                    $saler_name[] = $v['name'];
                }
            }
        }
        $salers = $tmp;
        $msgsaler = $model->m_get('msgsaler');
        //获取会员接口优惠券
        $post_data = array(
            'inter_id'=>$inter_id,
            'token'=>$this->_token,
            'module'=>'hotel',
        );
        $coupon = array();
        /*$coupon_list = $this->doCurlPostRequest( INTER_PATH_URL."intercard/getlist" , $post_data );
        if(!empty($coupon_list['data'])){
            foreach($coupon_list['data'] as $ck=>$cv){
                if($cv['is_active' ] == 't' && ($cv['card_type' ] == 1 or $cv['card_type' ] == 2)){
                    $coupon[] = $cv;
                }
            }
        }
        unset($coupon_list);*///优惠券的先屏蔽
        //var_dump($salers);die;
        $this->load->model('okpay/okpay_type_model');
        $groups = $this->okpay_type_model->get_all_typesgroup();
        $view_params= array(
            'model'=> $model,
            'fields_config'=> $fields_config,
            'check_data'=> FALSE,
            'salers'=>$salers,
            'msgsaler'=>$msgsaler,
            'coupon'=>$coupon,
            'hotel_info'=>$hotels_info,
            'groups'=>$groups,
            'show_saler_name' => !empty($saler_name)?implode(',',$saler_name):'',
        );

        $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
        //echo $html;die;
        echo $html;
    }

    public function edit_post()
    {//var_dump($_REQUEST);die;
        $this->label_action= '编辑类型';
        $this->_init_breadcrumb($this->label_action);
    
        $model_name= $this->main_model_name();
        $model= $this->_load_model($model_name);
        $pk= $model->table_primary_key();
    
        $this->load->library('form_validation');
        $post= $this->input->post();
        $labels= $model->attribute_labels();
        $base_rules= array(
                'name'=> array(
                        'field' => 'name',
                        'label' => $labels['name'],
                        'rules' => 'trim|required',
                ),
                'hotel_id'=> array(
                        'field' => 'hotel_id',
                        'label' => $labels['hotel_id'],
                        'rules' => 'trim|required',
                ),
                'status'=> array(
                        'field' => 'status',
                        'label' => $labels['status'],
                        'rules' => 'trim',
                ),
            'group_id'=> array(
                'field' => 'group_id',
                'label' => $labels['group_id'],
                //'rules' => 'trim|required',
            ),
        );
    
        $adminid= $this->session->get_admin_id();
        if( empty($post[$pk]) ){
            //add data.
            $this->form_validation->set_rules($base_rules);
    
            $msg = "此次数据保存失败！管理员无法创建支付场景，原因：无法定位公众号，酒店id出现重复";
            if ($this->form_validation->run() != FALSE) {
                $inter_id =  $this->session->get_admin_inter_id();
                $result = false;
                if($inter_id != "ALL_PRIVILEGES"){
                    $post['create_time'] = time();
                    $post['update_time']= time();
                    $post['inter_id']    = $inter_id;
                    $result= $model->m_sets($post)->m_save($post);
                    
                    $msg = "已新增数据！";
                }
                $message= ($result)?
                $this->session->put_success_msg($msg):
                $this->session->put_notice_msg($msg);
                $this->_log($model);
                $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
    
            } else
                $model= $this->_load_model();
    
        } else {
            $this->form_validation->set_rules($base_rules);
            if ($this->form_validation->run() != FALSE) {
                
                $inter_id =  $this->session->get_admin_inter_id();
                $result = false;
                
                $msg = "此次数据修改失败！管理员无法修改支付场景，原因：无法定位公众号，酒店id出现重复";
                if($inter_id != "ALL_PRIVILEGES"){
                    $post['update_time']= time();
                    $result= $model->m_sets($post)->m_save($post);
                        
                    $msg = "已保存数据！";
                }
                
                $message= ($result)?
                $this->session->put_success_msg($msg):
                $this->session->put_notice_msg($msg);
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
    public function qrcode_front()
    {
        if($id= $this->input->get('ids')){
            $model_name= $this->main_model_name();
            $model= $this->_load_model($model_name);
            $model= $model->load($id);
            
            $ck = $this->input->get('ck');
            $paycode = $this->input->get('paycode');
            $url = "";
            if(empty($paycode)){
                $url= EA_const_url::inst()->get_front_url($model->m_get('inter_id'), 'okpay/okpay/pay_show', array('id'=> $model->m_get('inter_id'),'hotelid'=>$model->m_get('hotel_id'), 'paytype'=>$model->m_get('id'),'ck'=>$ck));
            }else{
                $url= EA_const_url::inst()->get_front_url($model->m_get('inter_id'), 'okpay/okpay/pay_show', array('id'=> $model->m_get('inter_id'),'hotelid'=>$model->m_get('hotel_id'), 'paytype'=>$model->m_get('id'),'paycode'=>$paycode,'ck'=>$ck));
            }
            
            $this->_get_qrcode_png($url);
        } else
            echo '参数错误';
    }
    
    public function check_type(){
        $typeid = $this->input->get("id",true);
        
        $inter_id   = $this->session->get_admin_inter_id();
        
        $this->load->model("okpay/Okpay_type_model");
        $result = $this->Okpay_type_model->get_okpay_type_detail_with_admin($typeid,$inter_id);
        if(!empty($result)){
            echo json_encode ( array (
                    'status' =>1,
                    'message' => '场景可用'
            ));
        }else{
            echo json_encode ( array (
                    'status' =>0,
                    'message' => '场景不存在，或者已经禁用'
            ));
        }
    }

    /*
     * ajax获取对应酒店的分销员信息
     * */
    public function get_saler_info(){
        $hotel_id = $this->input->post('hotel_id',true);
        $inter_id= $this->session->get_admin_inter_id();
        if($inter_id== FULL_ACCESS){
            echo json_encode(array('errcode'=>1,'msg'=>'error'));
        }
        $this->load->model('distribute/qrcodes_model');
        $query = $this->qrcodes_model->get_salers($inter_id,1,'','',NULL,$hotel_id,NULL,2);
        $res = $query->result_array();
        if(empty($res)){
            echo json_encode(array('errcode'=>1,'msg'=>'该酒店暂时无分销员'));
            die;
        }else{
            echo json_encode(array('errcode'=>0,'res'=>$res));
            die;
        }

    }

    /*
     * 场景页面生成订单 扫码付款
     * */
    public function create_ordor_in_type(){
        $arr = array();
        $type_id = $this->input->post("type_id",true);
        $money = $this->input->post("pay_money",true);
        if(empty($type_id) || empty($money) || $money < 0){
            echo json_encode(array('code'=>1,'msg'=>'data error!'));
            die;
        }
        $arr['pay_money'] = $arr['money'] = $money;
        $arr['inter_id'] = $this->session->get_admin_inter_id();
        if($arr['inter_id'] == FULL_ACCESS){
            echo json_encode(array('code'=>1,'msg'=>'超管账号，无指定inter_id'));
            die;
        }
        $this->load->model("okpay/Okpay_type_model");
        $type = $this->Okpay_type_model->get_okpay_type_detail_with_admin($type_id,$arr['inter_id'],1);
        if(!empty($type)){
            $arr['hotel_id'] = $type['hotel_id'];
            $arr['pay_type'] = $type['id'];
            $arr['pay_type_desc'] = $type['name'];
          //  $arr['create_time'] = $arr['update_time'] = time();
          //  $arr['status'] = 1;
           // $arr['pay_status'] = 1;
            $arr['pay_way'] = 3;//设备扫码
            //OKP+时间戳+随机三位数
            $arr['out_trade_no'] = "OP".time().rand(1000,9999);

            $this->load->model ( 'hotel/Hotel_model' );
            $hotel = $this->Hotel_model->get_hotel_detail($arr['inter_id'],$arr['hotel_id']);
            $arr['hotel_name'] = $hotel['name'];

            $this->load->model('okpay/okpay_model');
            $res = $this->okpay_model->create_new_okpay_order($arr);
            if($res){
                echo json_encode(array('code'=>0,'msg'=>'ok', 'data'=>array('order_sn'=>$arr['out_trade_no'],'order_id'=>$res,'inter_id'=>$arr['inter_id'])));
                die;
            }else{
                echo json_encode(array('code'=>2,'msg'=>'生成订单失败！'));
                die;
            }
        }else{
            echo json_encode(array('code'=>3,'msg'=>'场景不可用！'));
            die;
        }
    }

    /*
     * 发起扫码支付请求
     * */
    public function okpay_pay(){
        $order_sn = $this->input->post('order_sn',true);
        $inter_id = $this->input->post('inter_id',true);
        $auth_code = $this->input->post('auth_code',true);
        $return = array('errcode'=>1,'msg'=>'错误！','data'=>array());
        $this->db->where(array(
            'out_trade_no'=>$order_sn,
            'inter_id'=>$inter_id,
            'pay_way' =>3,//扫码枪
        ));
        $this->db->limit(1);
        $order = $this->db->get('okpay_orders')->row_array();
        if(!empty($order) && $order['pay_status'] == 3){
            $return['msg'] = '该订单已经完成！';
            echo json_encode($return);
            die;
        }else if($order){
            //请求到微信
            $pay_url = 'https://api.mch.weixin.qq.com/pay/micropay';
            $this->load->model('pay/wxpay_model');
            $this->load->model('pay/Pay_model' );
            $this->load->model('wx/publics_model');
            $public = $this->publics_model->get_public_by_id($inter_id);
            if(!empty($public['app_id'])){
                $this->wxpay_model->setParameter("appid",$public['app_id']);
                $pay_paras=$this->Pay_model->get_pay_paras($inter_id);
                if(isset($pay_paras['sub_mch_id'])){
                    $this->wxpay_model->setParameter("sub_mch_id",$pay_paras['sub_mch_id']);
                    $this->wxpay_model->setParameter("mch_id",$pay_paras['mch_id']);
                }else {
                    $this->wxpay_model->setParameter("mch_id",$pay_paras['mch_id']);
                    if(empty($pay_paras['app_id'])) //new
                        $pay_paras['app_id']=$public['app_id'];
                }
                $this->wxpay_model->setParameter("body", "快乐付");//商品描述
                $this->wxpay_model->setParameter("total_fee", $order['pay_money'] * 100);//总金额
                $this->wxpay_model->setParameter("out_trade_no", $order['out_trade_no']);//商户订单号
                $this->wxpay_model->setParameter("auth_code",$auth_code);
                $xml = $this->wxpay_model->createMicropayXml($pay_paras);
                //调用请求接口
                $res_xml = $this->wxpay_model->postXmlCurl($xml,$pay_url);
                //$res_xml = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg><appid><![CDATA[wx231a7364831a062d]]></appid><mch_id><![CDATA[1269375801]]></mch_id><nonce_str><![CDATA[fq4imfj35h3rcbqqedae5xgqheszcqci]]></nonce_str><sign><![CDATA[0325A59208FDE772123F6DE882850956]]></sign><result_code><![CDATA[SUCCESS]]></result_code><openid><![CDATA[oX3WojpUPRJH3I1cvYQPgya67QWM]]></openid><is_subscribe><![CDATA[Y]]></is_subscribe><trade_type><![CDATA[MICROPAY]]></trade_type><bank_type><![CDATA[CFT]]></bank_type><total_fee>100</total_fee><fee_type><![CDATA[CNY]]></fee_type><transaction_id><![CDATA[4001682001201612233679638300]]></transaction_id><out_trade_no><![CDATA[OP14824020529776]]></out_trade_no><attach><![CDATA[]]></attach><time_end><![CDATA[20161223104201]]></time_end><cash_fee>100</cash_fee><cash_fee_type><![CDATA[CNY]]></cash_fee_type></xml>';
                $res = $this->wxpay_model->xmlToArray($res_xml);
                $data = array();
                $data['inter_id'] = $inter_id;
                $data['openid'] = isset($res['openid'])?$res['openid']:'';
                $data['out_trade_no'] = isset($res['out_trade_no'])?$res['out_trade_no']:'';
                $data['transaction_id'] = isset($res['transaction_id'])?$res['transaction_id']:'';
                $data['pay_time'] = time();
                $data['rtn_content'] = $res_xml;
                $data['type'] = 'okpay';
                $this->db->insert('pay_log', $data );
                if(!array_key_exists("return_code", $res) || !array_key_exists("result_code", $res)){
                    $return['msg'] = isset($res['err_code_des'])?$res['err_code_des']:'失败,请确认是否输入有误！';
                    echo json_encode($return);
                    die;
                }
                //②、接口调用成功，明确返回调用失败
                if($res["return_code"] == "SUCCESS" &&
                    $res["result_code"] == "FAIL" &&
                    $res["err_code"] != "USERPAYING" &&
                    $res["err_code"] != "SYSTEMERROR")
                {
                    $return['msg'] = isset($res['err_code_des'])?$res['err_code_des']:'调用异常！';
                    echo json_encode($return);
                    die;
                }
                //获取粉丝名称

                if($res["return_code"] == "SUCCESS" && $res["result_code"] == "SUCCESS" ){
                    //签名校验数据的合法性
                    $this->load->model('pay/pay_model');
                    $pay_config= $this->pay_model->get_pay_paras($data ['inter_id']);
                    $pay_key= isset($pay_config['key'])? $pay_config['key']: '';
                    if( empty($pay_key) ){
                        MYLOG::w($data['out_trade_no'].'微信支付回调商户配置信息不完整！', 'okpay');
                        die;
                    }

                    $params= (array) $res;
                    $sign= $this->get_sign($params, $pay_key);
                    if($res['sign'] != $sign){
                        MYLOG::w($data['out_trade_no'].'签名错误！', 'okpay');
                        die;
                    }

                    //读取昵称
                    $this->load->model('okpay/okpay_fans_model');
                    $fans = $this->okpay_fans_model->get_fans_nickname($inter_id,$res['openid']);
                    $fans_nickname = isset($fans['nickname'])?$fans['nickname']:'';
                    //$queryResult = $this->check_order_status($res['transaction_id'],$inter_id);
                    //成功，更改订单状态
                    $pay_status = 3;
                    //存在订单号，则执行下面的处理
                    if(!empty($res['out_trade_no'])){
                        $this->db->where ( array (
                            'out_trade_no' => $res['out_trade_no'],
                            'inter_id' => $inter_id,
                            'pay_way' =>3,//扫码枪
                        ) );
                        //先查询是否已经更改状态
                        $order = $this->db->get ( 'okpay_orders' )->row_array ();
                        if($order && $order['pay_status'] == 1){//未更改状态
                            //========判断订单的order_id, openid, 总金额是否相符
                            $this->load->helper('soma/math');  //防止精度损失导致数额不匹配
                            $total_dif= float_precision_match($res['total_fee'], $order['pay_money'] *100);
                            if( ! $total_dif ){
                                MYLOG::w($res ['out_trade_no']. '微信支付回调返回total_fee['
                                    . $res['total_fee'] .']与订单金额[' . $order['pay_money']*100 .']不一致！','okpay');
                                die;
                            }
                            $this->db->where ( array (
                                'out_trade_no' => $res['out_trade_no'],
                                'inter_id' => $inter_id
                            ) );
                            $this->db->update ('okpay_orders', array (
                                'nickname' => $fans_nickname,
                                'pay_status' => $pay_status,
                                'trade_no'=>$res['transaction_id'],
                                'pay_time'=>time(),
                                'update_time'=>time()
                            ) );
                            //添加打印订单操作 situguanchen 2017-03-20
                            $this->load->model ( 'plugins/Print_model' );
                            $res  = $this->Print_model->print_okpay_order ($order,'okpay_pay_success');
                        }
                    }

                  //  echo 'success';
                    $return['errcode'] = 0;
                    $return['msg'] = '订单付款成功！';
                    echo json_encode($return);
                    die;
                }
                //③、确认支付是否成功
                $queryTimes = 0;
                while($queryTimes < 25)
                {
                    $queryResult = $this->check_order_status($order['out_trade_no'],$inter_id);
                    //如果需要等待1s后继续
                    if(!is_array($queryResult) && $queryResult == 2){//支付中
                        sleep(5);
                        $queryTimes+=5;
                        continue;
                    } else if(is_array($queryResult)){//查询成功
                        //签名校验数据的合法性
                        $this->load->model('pay/pay_model');
                        $pay_config= $this->pay_model->get_pay_paras($data ['inter_id']);
                        $pay_key= isset($pay_config['key'])? $pay_config['key']: '';
                        if( empty($pay_key) ){
                            MYLOG::w($queryResult['out_trade_no'].'微信支付回调商户配置信息不完整！', 'okpay');
                            die;
                        }

                        $params= (array) $queryResult;
                        $sign= $this->get_sign($params, $pay_key);
                        if($queryResult['sign'] != $sign){
                            MYLOG::w($queryResult['out_trade_no'].'签名错误！', 'okpay');
                            die;
                        }
                        $pay_status = 3;
                        //存在订单号，则执行下面的处理
                        if(!empty($queryResult['out_trade_no'])){
                            $this->db->where ( array (
                                'out_trade_no' => $queryResult['out_trade_no'],
                                'inter_id' => $inter_id
                            ) );

                            //先查询是否已经更改状态
                            $order = $this->db->get ( 'okpay_orders' )->row_array ();
                            if($order && $order['pay_status'] == 1){//未更改状态
                                //========判断订单的order_id, openid, 总金额是否相符
                                $this->load->helper('soma/math');  //防止精度损失导致数额不匹配
                                $total_dif= float_precision_match($queryResult['total_fee'], $order['pay_money'] *100);
                                if( ! $total_dif ){
                                    MYLOG::w($queryResult ['out_trade_no']. '微信支付回调返回total_fee['
                                        . $queryResult['total_fee'] .']与订单金额[' . $order['pay_money']*100 .']不一致！','okpay');
                                    die;
                                }
                                //读取昵称
                                $this->load->model('okpay/okpay_fans_model');
                                $fans = $this->okpay_fans_model->get_fans_nickname($inter_id,$queryResult['openid']);
                                $fans_nickname = isset($fans['nickname'])?$fans['nickname']:'';
                                $this->db->where ( array (
                                    'out_trade_no' => $queryResult['out_trade_no'],
                                    'inter_id' => $inter_id
                                ) );
                                $this->db->update ('okpay_orders', array (
                                    'nickname' => $fans_nickname,
                                    'pay_status' => $pay_status,
                                    'trade_no'=>$queryResult['transaction_id'],
                                    'pay_time'=>time(),
                                    'update_time'=>time()
                                ) );
                                //添加打印订单操作 situguanchen 2017-03-20
                                $this->load->model ( 'plugins/Print_model' );
                                $res  = $this->Print_model->print_okpay_order ($order,'okpay_pay_success');
                            }
                        }
                        $return['errcode'] = 0;
                        $return['msg'] = '订单付款成功';
                        echo json_encode($return);
                        die;
                    } else {//订单交易失败
                        $return['msg'] = '订单交易失败！';
                        echo json_encode($return);
                        die;
                    }
                }
                //撤销订单
               if($this->cancel_order($order['out_trade_no'],$inter_id)){
                   //关闭订单
                   $this->db->where ( array (
                       'out_trade_no' => $order_sn,
                       'inter_id' => $inter_id
                   ) );
                   $this->db->update ('okpay_orders', array (
                     //  'nickname' => $fans_nickname,
                       'pay_status' => 0,//关闭订单
                     //  'trade_no'=>$res['transaction_id'],
                     //  'pay_time'=>time(),
                       'update_time'=>time()
                   ) );
                   $return['msg'] = '支付关闭，成功撤销订单，系统已关闭订单，请重新下单！';
                   echo json_encode($return);
                   die;
               }else{
                   $return['msg'] = '系统错误！';
                   echo json_encode($return);
                   die;
               }

            }
        }else{
            $return['msg'] = '参数错误';
            echo json_encode($return);
            die;
        }
    }


    private function check_order_status($out_trade_no,$inter_id)
    {
        $this->load->library('MYLOG');
        $pay_url = 'https://api.mch.weixin.qq.com/pay/orderquery';
        $this->load->model('pay/wxpay_model');
        $this->load->model('pay/Pay_model');
        $this->load->model('wx/publics_model');
        $public = $this->publics_model->get_public_by_id($inter_id);
        $pay_paras = $this->Pay_model->get_pay_paras($inter_id);
        $mch_id = $sub_mch_id = '';
        if(isset($pay_paras['sub_mch_id'])){
            $sub_mch_id = $pay_paras['sub_mch_id'];
            $mch_id = $pay_paras['mch_id'];
        }else {
            $mch_id = $pay_paras['mch_id'];
            if(empty($pay_paras['app_id'])) //new
                $pay_paras['app_id']=$public['app_id'];
        }
            $params = array();
            $params['appid'] = $public['app_id'];
            $params['mch_id'] = $pay_paras['mch_id'];
            $params['out_trade_no'] = $out_trade_no;
            $params['nonce_str'] = $this->wxpay_model->createNoncestr();
            $params['sign'] = $this->wxpay_model->getSign($params,$pay_paras);
            $params = $this->wxpay_model->arrayToXml($params);
            //调用请求接口
            $xml_result = $this->wxpay_model->postXmlCurl($params,$pay_url);
            $result = $this->wxpay_model->xmlToArray($xml_result);
            $log_data['inter_id'] = $inter_id;
            $log_data['openid'] = isset($result['openid'])?$result['openid']:'';
            $log_data['out_trade_no'] = isset($result['out_trade_no'])?$result['out_trade_no']:'';
            $log_data['transaction_id'] = isset($result['transaction_id'])?$result['transaction_id']:'';
            $log_data['pay_time'] = time();
            $log_data['rtn_content'] = $xml_result;
            $log_data['type'] = 'okpay_check';//查询订单
            $this->db->insert('pay_log', $log_data );
        MYLOG::w('okpay_query_order:'.json_encode($result),'okpay');
            if($result["return_code"] == "SUCCESS"
                && $result["result_code"] == "SUCCESS")
            {
                //支付成功
                if($result["trade_state"] == "SUCCESS"){
                    return $result;
                }
                //用户支付中
                else if($result["trade_state"] == "USERPAYING" || $result["trade_state"]=='PAYERROR'){
                    return 2;
                }
            }
            //如果返回错误码为“此交易订单号不存在”则直接认定失败
            if(isset($result["err_code"]) && $result["err_code"] == "ORDERNOTEXIST")
            {
                return false;
            } else{
                //如果是系统错误，则后续继续
               return 2;
            }
            return false;

    }

    private function cancel_order($out_trade_no,$inter_id,$depth = 0){
        if($depth > 5){
            return false;
        }
        $this->load->model('okpay/Okpay_wxpay_model');
        $result = $this->Okpay_wxpay_model->cancel_order($inter_id,$out_trade_no);
        $log_data['inter_id'] = $inter_id;
        $log_data['openid'] = isset($result['openid'])?$result['openid']:'';
        $log_data['out_trade_no'] = isset($result['out_trade_no'])?$result['out_trade_no']:$out_trade_no;
        $log_data['transaction_id'] = isset($result['transaction_id'])?$result['transaction_id']:'';
        $log_data['pay_time'] = time();
        $log_data['rtn_content'] = json_encode($result);
        $log_data['type'] = 'okpay_cancel';//查询订单
        $this->db->insert('pay_log', $log_data);
        $this->load->library('MYLOG');
        MYLOG::w('okpay_cancel_order:'.json_encode($result),'okpay');
        if($result){
            //接口调用失败
            if($result["return_code"] != "SUCCESS"){
                return false;
            }

            //如果结果为success且不需要重新调用撤销，则表示撤销成功
            if($result["result_code"] == "SUCCESS"
                && $result["recall"] == "N"){
                return true;
            } else if($result["recall"] == "Y") {
                return $this->cancel_order($out_trade_no,$inter_id, ++$depth);
            }
        }else{
            return false;
        }
    }
    //获取微信支付签名
    private function get_sign( array $params, $key)
    {
        $fields= array('sign', );
        foreach ($params as $k => $v) {
            if( in_array($k, $fields) ) unset($params[$k]);
            elseif( !$v ) unset($params[$k]); //参数为空不参与签名

        }
        //签名步骤一：按字典序排序参数
        ksort($params);
        $string = http_build_query( $params, false ). "&key=". $key;
        return strtoupper(md5($string));
    }

    public function update_order_status(){
        $return = array('errcode'=>1,'msg'=>'错误！','data'=>array());

        $order_sn = $this->input->post('order_sn',true);
        $inter_id= $this->session->get_admin_inter_id();
        $this->db->where(array(
            'out_trade_no'=>$order_sn,
            'inter_id'=>$inter_id,
            'pay_way' =>3,//扫码枪
        ));
        $this->db->limit(1);
        $order = $this->db->get('okpay_orders')->row_array();
        if(!empty($order) && $order['pay_status'] == 3){
            $return['msg'] = '该订单已经完成！';
            echo json_encode($return);
            die;
        }
        //关闭订单
        $this->db->where ( array (
            'out_trade_no' => $order_sn,
            'inter_id' => $inter_id
        ) );
        $this->db->update ('okpay_orders', array (
            //  'nickname' => $fans_nickname,
            'pay_status' => 0,//关闭订单
            //  'trade_no'=>$res['transaction_id'],
            //  'pay_time'=>time(),
            'update_time'=>time()
        ) );
        $return['msg'] = '系统已关闭订单，请重新下单！';
        echo json_encode($return);
        die;
        //取消订单

    }
//  public function delete(){
//      $model_name= $this->main_model_name();
//      $model= $this->_load_model($model_name);
//      $pk= $model->table_primary_key();
//      $ids = $this->input->get('ids');
        
        
        
//      $result= $model->delete($ids);
//      $message= ($result)?
//      $this->session->put_success_msg('已删除数据！'):
//      $this->session->put_notice_msg('此次数据删除失败！');
//      $this->_log($model);
//      $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
//  }
    
    
}
