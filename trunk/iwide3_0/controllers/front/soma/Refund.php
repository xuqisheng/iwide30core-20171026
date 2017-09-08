<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//退款管理
class Refund extends MY_Front_Soma {

    public  $themeConfig;
    public $theme = 'default';

	public function __construct()
	{
        parent::__construct();
        //theme
        $this->load->model('soma/Theme_config_model');
        $this->themeConfig = $themeConfig = $this->Theme_config_model->get_using_theme($this->inter_id);
        $this->theme = $themeConfig['theme_path'];

        $ticketId = '';
        if( $this->session->userdata('tkid') )
        {
            $ticketId = $this->session->userdata('tkid');
        }

        //门票皮肤没有详情页，先默认为v1皮肤的。ticket有自己的header头
        $themeArr = array(
            'ticket',
            'zongzi',
        );
        if( in_array( $this->theme, $themeArr ) || $ticketId )
        {
            $this->theme = 'v1';
        }
	}

//http://ihotels.iwide.cn/index.php/soma/refund/apply?openid=oX3WojpcRoRbRvDJDAU9xymibGfc&id=a429262687&oid=1000000416&bsn=package

//测试自动退款
//http://ihotels.iwide.cn/index.php/soma/refund/apply?openid=oX3WojpcRoRbRvDJDAU9xymibGfc&id=a429262687&oid=1000000306&bsn=package
//http://credit.iwide.cn/index.php/soma/refund/apply?openid=o9VbtwzbArtx7-6ptmYgUSHrNWvs&id=a450089706&oid=1000000738&bsn=package

    public function apply()
    {
        $data = [];
        if (!$this->isNewTheme()) {
            //根据order_id获取订单信息
            $inter_id = $this->inter_id;
            $business = $this->input->get('bsn'); //分类
            $openid = $this->openid;

            //获取订单号
            $order_id = $this->input->get('oid');
            if( !$order_id ){
                redirect( Soma_const_url::inst()->get_soma_order_list() );
            }

            //跳转参数
            $param = array();
            $param['bsn'] = $business;
            // $param['id'] = $inter_id;
            $param['oid'] = $order_id;

            $this->load->model('soma/Sales_order_model','sales_order_model');
            $sales_order_model = $this->sales_order_model;

            $sales_order_model->business = $business;
            $order_detail = $sales_order_model->load($order_id)->get_order_detail($business,$inter_id);

            if($order_detail['can_refund'] == $sales_order_model::CAN_REFUND_STATUS_SEVEN) {
                //退款不能超过支付后7天
                $paymentTime = $order_detail['payment_time'];
                $isOverRefund = FALSE;//没有超过7天
                if( $paymentTime ){
                    $overTime = strtotime( $paymentTime ) + 7*24*60*60;
                    $nowTime = time();
                    if( $overTime < $nowTime ){
                        $isOverRefund = TRUE;//超过7天
                        die($this->lang->line('refund_in_7_day_tip'));
                    }
                }
            }

            $time = time();
            $expireTime = isset( $order_detail['items'][0]['expiration_date'] ) ? strtotime( $order_detail['items'][0]['expiration_date'] ) : NULL;
            if( $expireTime && $expireTime < $time ){
                die($this->lang->line('can_not_refund_tip'));
            }

// var_dump( $order_detail );exit;
            //订单不存在，返回到订单列表
            if( !$order_detail ){
                redirect( Soma_const_url::inst()->get_soma_order_list() );
            }

            //不是自己的订单，返回到订单列表
            if( $openid !== $order_detail['openid'] ){
                redirect( Soma_const_url::inst()->get_soma_order_list() );
            }

            //判断是否已经提交过退款申请或已经退款
            $can_refund = $sales_order_model->can_refund_order();
            if( $can_refund ){
                //no
            }else{
                //不可以退
                // die('cannot refund');
                redirect( Soma_const_url::inst()->get_soma_order_list() );
            }

            //套票详情链接
            foreach( $order_detail['items'] as $k=>$v ){
                $order_detail['items'][$k]['detail_url'] = Soma_const_url::inst()->get_soma_order_detail($param);
            }

            $data = array();

            //点击分享之后开启这些按钮
            $js_menu_hide = array( 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:favorite', 'menuItem:copyUrl' );
            $data['js_menu_hide'] = $js_menu_hide;

            $data['detail'] = $order_detail;

            if($this->langDir == self::LANG_DIR_EN)
            {
                $title = array('title' => $this->lang->line('refund_application'));
            }
            else
            {
                $title = array( 'title'=>CONSTANTS_BSN.'退款申请' );
            }

            $tips = $this->lang->line('refund_success_tip');
            $data['tips'] = $tips;

            //退款原因
            $cause = array(
                $this->lang->line('reservation_not_available'),
                // '酒店预约上，但是没客房',
                $this->lang->line('poor_reviews'),
                $this->lang->line('exceeded_order_limit'),
                $this->lang->line('no_time_to_use'),
                $this->lang->line('unable_contact_hotel'),
                $this->lang->line('found_cheaper_alternative'),
            );
            $data['cause'] = $cause;

            //退款方式
            $data['type'] = $this->lang->line('refund_original');

            //获取支付类型
            $this->load->model('soma/Sales_payment_model','paymentModel');
            $paymentModel = $this->paymentModel;
            $paidTypes = $paymentModel->get_paid_type_byOrderIds( array($order_id), $inter_id, 'paid_type' );
            if( !$paidTypes ){
                //查找不到信息，在payment查找不到信息，就是说可能不是微信支付或者储值支付
                redirect( Soma_const_url::inst()->get_soma_order_list() );
            }
            $pay_type = isset( $paidTypes[0]['paid_type'] ) ? $paidTypes[0]['paid_type'] : '';

            $this->load->model('soma/Sales_refund_model','refundModel');
            $refundModel = $this->refundModel;
            if( $pay_type == $paymentModel::PAY_TYPE_WX ){
                $param['rtype'] = $refundModel::REFUND_TYPE_WX;
            }elseif( $pay_type == $paymentModel::PAY_TYPE_CZ ){
                $param['rtype'] = $refundModel::REFUND_TYPE_CZ;
            }elseif( $pay_type == $paymentModel::PAY_TYPE_JF ){
                $param['rtype'] = $refundModel::REFUND_TYPE_JF;
            }

            //退款提交链接
            $post_url = Soma_const_url::inst()->get_soma_refund_apply_post( $param );
            $data['post_url'] = $post_url;

            // 双语化翻译
            if($this->langDir == self::LANG_DIR_EN)
            {
                foreach($data['detail']['items'] as $key => $item)
                {
                    if(!empty($item['name_en']))
                    {
                        $data['detail']['items'][$key]['name'] = $item['name_en'];
                    }
                }
            }

            $this->_view("header", $title );
        }
        $this->headerDatas['title'] = '订单退款申请';
        $this->_view( 'refund', $data );
    }

    //提交处理
    public function apply_post(){

        //根据order_id获取订单信息
        $inter_id = $this->inter_id;
        $business = $this->input->get('bsn'); //分类
        $openid = $this->openid;

        $refund_type = $this->input->get('rtype');//退款类型，1:微信，2:储值
        
        //获取订单号
        $order_id = $this->input->get('oid');
        if( !$order_id ){
            die('order_id not exists');
        }
        
        //跳转参数
        $param = array();
        $param['bsn'] = $business;
        // $param['id'] = $inter_id;
        $param['oid'] = $order_id;
        
        //加载订单model
        $this->load->model('soma/Sales_order_model','sales_order_model');
        $sales_order_model = $this->sales_order_model;
        $sales_order_model->business = $business;

        //获取详情
        $sales_order_model = $sales_order_model->load($order_id);
//   var_dump($sales_order_model);exit;
        /**
         * @var Sales_order_model $sales_order_model
         */
        $order_detail = $sales_order_model->get_order_detail($business,$inter_id);
        if( !$order_detail ){
            redirect( Soma_const_url::inst()->get_soma_order_list() );
        }

        //不是自己的订单，返回到订单列表
        if( $openid !== $order_detail['openid'] ){
            redirect( Soma_const_url::inst()->get_soma_order_list() );
        }

        //判断是否是拼团的，拼团不能退
        $settlement = $sales_order_model->m_get( 'settlement' );
        $settlement = strtolower( $settlement );
        if( $settlement == 'groupon' ){
            redirect( Soma_const_url::inst()->get_soma_order_list() );
        }
        
        //判断是否已经提交过退款申请或已经退款
        $can_refund = $sales_order_model->can_refund_order();
        if( $can_refund ){
            //no
        }else{
            //不可以退
            // die('cannot refund');
            redirect( Soma_const_url::inst()->get_soma_order_list() );
        }

        //退款原因
        $cause = $this->input->post('cause');
        $cause_str = '';
        foreach( $cause as $k=>$v ){
            if( $v ){
                $cause_str .= $v . ';';
            }
        }
        $save = array();
        $save['cause'] = $cause_str;
        $save['refund_type'] = $refund_type;
        
        //加载退款model
        $this->load->model( 'soma/sales_refund_model' );
        $sales_refund_model = $this->sales_refund_model;
        /**
         * @var sales_refund_model $sales_refund_model
         */

        //设置退款参数
        $refund = array('refund_status'=>$sales_order_model::REFUND_ALL); //主单退款状态全部退
        $sales_order_model->refund = $refund;

        $refund_item = array( 'is_refund'=>$sales_order_model::STATUS_ITEM_REFUNDING );//细单退款状态已申请
        $sales_order_model->refund_item = $refund_item;
        $sales_refund_model->order = $sales_order_model;

        $sales_refund_model->product = $order_detail['items'];
        $sales_refund_model->business = $business;
        $sales_refund_model->save = $save;

        //如果是对接核销设备的，需要在生成退款单前检查
        $orderItems = isset( $order_detail['items'] ) ? $order_detail['items'] : '';
        if( !$orderItems )
        {
            redirect( Soma_const_url::inst()->get_soma_order_list() );
        }

        $orderItem = current( $orderItems );

        $this->load->model('soma/Product_package_model','somaProductPackageModel');
        $somaProductPackageModel = $this->somaProductPackageModel;
        if( isset( $orderItem['conn_devices'] ) && $orderItem['conn_devices'] != $somaProductPackageModel::DEVICE_NO_CONN )
        {
            //对接了设备
            switch ( $orderItem['conn_devices'] )
            {
                case $somaProductPackageModel::DEVICE_ZHIYOUBAO:
                    //对接智游宝，放到定时任务里面
                    $this->load->library('Soma/Api_zhiyoubao');
                    $api= new Api_zhiyoubao( $orderItem['inter_id'] );
                    $refundOrderResult = $api->refund_order( $orderItem['order_id'], $orderItem['qty'] );
                    if( $refundOrderResult )
                    {
                        $save['retreat_batch_no'] = $refundOrderResult;
                        $sales_refund_model->save = $save;
                    } else {
                        redirect( Soma_const_url::inst()->get_soma_order_list() );
                    }
                    break;
                default:
                    break;
            }
        }

        // 退款处理，把分账记录取消
        $this->soma_db_conn->trans_start();
        $result          = $sales_refund_model->order_save( $business, $inter_id );
        $billing_service = \App\services\soma\SeparateBillingService::getInstance();
        $billing_result  = $billing_service->updateOrderSeparateBillingInfo($order_id);
        if( $result && $billing_result){
            $this->soma_db_conn->trans_complete();
            //显示申请退款成功后的页面
            redirect( Soma_const_url::inst()->get_soma_refund_detail( $param ) );

        }else{
            redirect( Soma_const_url::inst()->get_soma_order_list() );
        }
    }

    //查看退款状态
    public function detail(){
        $data = [];
        if (!$this->isNewTheme()) {
            $order_id = $this->input->get('oid');//order_id
            // $inter_id = $this->input->get('id');//公众号
            $inter_id = $this->inter_id;//公众号
            $business = $this->input->get('bsn');//业务类型
            $openid = $this->openid;

            //获取订单号
            if( !$order_id ){
                die('order_id not exists');
            }

            $this->load->model( 'soma/sales_refund_model' );
            $sales_refund_model = $this->sales_refund_model;

            //查找退款主单信息
            $refund_info = $sales_refund_model->get_refund_order_detail_byOrderId( $order_id, $inter_id );
            if( !$refund_info ){
                redirect(Soma_const_url::inst()->get_soma_order_list());
            }

            // 加载订单信息
            $this->load->model('soma/Sales_order_model', 'sales_order_model');
            $order = $this->sales_order_model->load($order_id);
            if(!$order)
            {
                redirect(Soma_const_url::inst()->get_soma_order_list());
            }
            $order_items = $order->get_order_items($order->m_get('business'), $order->m_get('inter_id'));
            if(!is_array($order_items) || empty($order_items))
            {
                redirect(Soma_const_url::inst()->get_soma_order_list());
            }

            // 加载产品信息（电话）
            $this->load->model('soma/Product_package_model', 'product_model');
            $product = $this->product_model->load($order_items[0]['product_id']);
            if(!$product)
            {
                redirect(Soma_const_url::inst()->get_soma_order_list());
            }

            $data = array();
            $data['product'] = $product;
            $data['order_id'] = $refund_info['order_id'];
            $data['refund_total'] = $refund_info['refund_total'];
            $data['refund_status'] = $refund_info['status'];
            $data['refund_recv'] = $this->lang->line('refund_original');//isset( $result['refund_recv_accout_0'] ) ? $result['refund_recv_accout_0'] : '原路退款';//退款入账方

            //发送一个查询，到微信那边获取退款状态
            $order_id = $refund_info['order_id'];
            $business = $business;
            $inter_id = $this->inter_id;

            //审核状态
            $waiting = $sales_refund_model::STATUS_WAITING;//已申请
            $pending = $sales_refund_model::STATUS_PENDING;//已审核
            $successing = $sales_refund_model::STATUS_REFUND;//已退款
            $pending_str = '';
            $success = '';
            $waiting_str = '';
            $processing = '';
            if( $refund_info['refund_type'] == $sales_refund_model::REFUND_TYPE_WX ){
                //如果已经退款成功状态，不必再查询微信
                if( $successing == $refund_info['status'] ){
                    //退款成功
                    $processing = 'class="active"';
                    $success = 'class="active cur"';
                    $pending_str = 'class="active"';
                    $waiting_str = '';
                }else{
                    //微信退款
                    $result = $sales_refund_model->wx_refund_check($order_id, $business, $inter_id);
                    $result_status = $result['refund_status_0'];//退款状态

                    // var_dump( $result );exit;
                    if( $result_status == 'PROCESSING' ){
                        //退款中
                        $processing = 'class="active cur"';
                    }elseif( $result_status == 'SUCCESS' ){
                        //退款成功
                        $processing = 'class="active"';
                        $success = 'class="active cur"';
                    }

                    if( $result_status == 'PROCESSING' || $result_status == 'SUCCESS' ){
                        $pending_str = 'class="active"';
                        $waiting_str = '';
                    }elseif( $waiting == $refund_info['status'] ){
                        $waiting_str = 'cur';
                    }elseif( $pending == $refund_info['status'] ){
                        $pending_str = 'class="active cur"';
                    }
                }
                $refund_name = $this->lang->line('wechat');
            }elseif( $refund_info['refund_type'] == $sales_refund_model::REFUND_TYPE_CZ ){
                //储值退款
                if( $successing == $refund_info['status'] ){
                    //退款成功
                    $processing = 'class="active"';
                    $success = 'class="active cur"';
                    $pending_str = 'class="active"';
                    $waiting_str = '';
                }elseif( $waiting == $refund_info['status'] ){
                    $waiting_str = 'cur';
                }elseif( $pending == $refund_info['status'] ){
                    $pending_str = 'class="active cur"';
                }
                $refund_name = $this->lang->line('stored_value');
            }
            elseif( $refund_info['refund_type'] == $sales_refund_model::REFUND_TYPE_JF ){
                //储值退款
                if( $successing == $refund_info['status'] ){
                    //退款成功
                    $processing = 'class="active"';
                    $success = 'class="active cur"';
                    $pending_str = 'class="active"';
                    $waiting_str = '';
                }elseif( $waiting == $refund_info['status'] ){
                    $waiting_str = 'cur';
                }elseif( $pending == $refund_info['status'] ){
                    $pending_str = 'class="active cur"';
                }
                $refund_name = $this->lang->line('point');
            }

            $status_str = '';
            $status_str = '
            <li class="active '.$waiting_str.'"><em></em><p>' . $this->lang->line('checking') . '</p></li>
            <li '.$pending_str.'><em></em><hr><p>' . $this->lang->line('confirm_refund') . '</p></li>
            ';

            $translate_tpl = $this->lang->line('refunding');
            $refund_message = str_replace('[0]', $refund_name, $translate_tpl);

            $status_str .= '
            <li '.$processing.'><em></em><hr><p>'. $refund_message .'</p></li>
            <li '.$success.'><em></em><hr><p>' . $this->lang->line('reffund_success') . '</p></li>
        ';
            $data['status_str'] = $status_str;

            //点击分享之后开启这些按钮
            $js_menu_hide = array( 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:favorite', 'menuItem:copyUrl' );
            $data['js_menu_hide'] = $js_menu_hide;

            $time = date( 'Y-m-d', time()+4*24*3600 );
            // $data['time'] = '预计2016年03月18日前';
            $data['time'] = $this->lang->line('back_to_account');//'预计'.$time.'前';

            $data['model'] = $sales_refund_model;

            //获取推荐位
            $uri = 'soma_refund_detail';
            $block = $this->get_page_block( $uri );
            $data['block']= $block;

            $title = array(
                'title' => $this->lang->line('refund_detail')
            );
            $this->_view("header", $title );
        }
        $this->headerDatas['title'] = '退款详情';
        $this->_view('refund_status', $data );
    }

    //展示为以后的皮肤做扩展
    protected function _view($file, $datas=array() )
    {
        parent::_view( 'package'. DS. $file, $datas);
    }

    // //测试自动退款链接
    // public function wx_refund_send()
    // {
    //     $this->load->model( 'soma/sales_refund_model' );
    //     $model = $this->sales_refund_model;
    //     $order_id = $this->input->get('oid');
    //     $order_id = isset( $order_id ) ? $order_id : '1000000294';
    //     $business = 'package';
    //     $inter_id = 'a429262687';
    //     $rs = $model->wx_refund_send( $order_id, $business, $inter_id );
    //     var_dump( $rs );
    // }

    // //测试查询自动退款链接
    // public function wx_refund_check()
    // {
    //     $this->load->model( 'soma/sales_refund_model' );
    //     $model = $this->sales_refund_model;
    //     $order_id = $this->input->get('oid');
    //     $order_id = isset( $order_id ) ? $order_id : '1000000294';
    //     $business = 'package';
    //     $inter_id = 'a429262687';
    //     $rs = $model->wx_refund_check( $order_id, $business, $inter_id );
    //     var_dump( $rs );
    // }

    // //测试查询自动退款链接
    // public function wx_order_close()
    // {
    //     $this->load->model( 'soma/sales_refund_model' );
    //     $model = $this->sales_refund_model;
    //     $order_id = $this->input->get('oid');
    //     $order_id = isset( $order_id ) ? $order_id : '1000000294';
    //     $business = 'package';
    //     $inter_id = 'a429262687';
    //     $rs = $model->wx_order_close( $order_id, $business, $inter_id );
    //     var_dump( $rs );
    // }
    
}
