<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Typesgroup extends MY_Admin {

	protected $label_module= '快乐付';
	protected $label_controller= '场景分组列表';
	protected $label_action= '场景分组';
	
	function __construct(){
		parent::__construct();
	}
	

	function index(){//var_dump($_SESSION);die;
        $admin_profile = $this->session->userdata ( 'admin_profile' );
        $inter_id = $admin_profile['inter_id'];
        if($inter_id== FULL_ACCESS) $inter_id= '';
        $this->load->model('okpay/okpay_type_model');
        $res = $this->okpay_type_model->get_type_group_info($inter_id);
        $view_params = array (
            //'pagination' => $this->pagination->create_links (),
            'res' =>$res,
            'inter_id' => $inter_id
        );
        echo $this->_render_content ( $this->_load_view_file ( 'grid' ), $view_params, TRUE );
	}
	
	public function add()
	{
        $post['name']= htmlspecialchars($this->input->post('groupname'));
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id!= FULL_ACCESS){
            $message= $this->session->put_notice_msg('不是超管!');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
        };
        $post['inter_id'] = 'default';//默认全平台能用
        $this->load->model('okpay/okpay_type_model');
        $result = $this->okpay_type_model->insert_data($post);
        $message= ($result)?
            $this->session->put_success_msg('新增成功'):
            $this->session->put_notice_msg('新增失败');
        $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
        die;
	}

    //查看场景成交数据
    public function detail(){
        $group_id = addslashes($this->input->get('id',true));
        $avgs['group_id'] = $group_id;
        $avgs ['start_time'] = $this->input->post ( 'start_time' );//开始
        $avgs ['end_time'] = $this->input->post ( 'end_time' );//结束
        if(empty($group_id)){
            $this->session->put_notice_msg('数据缺失！');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
        }
        $inter_id	= $this->session->get_admin_inter_id();
        if($inter_id== FULL_ACCESS){
            //$filter= array();
            $avgs ['inter_id'] = '';
        }else{
            $avgs ['inter_id'] = $inter_id;
        }
        //默认
        $start = date('Y-m-01', strtotime('-1 month'));
        $end = date('Y-m-t', strtotime('-1 month'));
        if(empty($avgs['start_time'])){
            $avgs['start_time'] = $start;
        }
        if(empty($avgs['end_time'])){
            $avgs['end_time'] = $end;
        }
        $this->load->model('okpay/okpay_type_model');
        /*$keys = $this->uri->segment ( 4 );
        $keys = explode ( '_', $keys );
        if (! empty ( $keys [0] )) {
            $avgs ['start_time'] = $keys [0];
        }
        if (! empty ( $keys [1] )) {
            $avgs ['end_time'] = $keys [1];
        }*/

        $res = $this->okpay_type_model->get_data_by_filter($avgs);
        //是否为导出的
        $ext = $this->input->post('export');
        if($ext && $ext==1){
            //$res = $this->okpay_model->get_data_by_filter($avgs);//var_dump($res);die;
            $this->extdata($res);
            die;
        }
        $view_params = array (
           // 'pagination' => $this->pagination->create_links (),
            'res'        => $res,
            'posts'			=>$avgs,
          //  'public'	=>$public,
         //   'select_hotel'=>isset($avgs ['hotel_public'])?$avgs ['hotel_public']:array(),
          //  'total'      => $config ['total_rows']
        );
        echo $this->_render_content ( $this->_load_view_file ( 'detail' ), $view_params, TRUE );
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

    //查询企业付款脚本
    public function check_pay()
    {
        $inter_id = $this->session->get_admin_inter_id();
        if ($inter_id != 'a450089706' && $inter_id != 'a452233816') {
            echo '权限有误';
            die;
        };
        $post = $this->input->post();
        $this->load->helper ( 'common' );
        if ($post) {//add数据
            if (empty($post['no'])) {
                echo '不能为空';
                die;
            }
            $post_inter = addslashes($post['inter_id']);
            $no = addslashes($post['no']);
            $where = array('inter_id' => $post_inter);
            $this->db->where($where);
            $query = $this->db->get('pay_params')->result();
            $account_info = array();
            foreach ($query as $item) {
                $account_info [$item->param_name] = $item->param_value;
            }
            if (!empty($account_info)) {
                $this->load->model('wx/publics_model');
                $public_info = $this->publics_model->get_public_by_id($post_inter);
                $account_info ['mch_name'] = $public_info ['name'];
                $account_info ['app_id'] = $public_info ['app_id'];
                // 收款账号与支付账号分开
                if (isset ($account_info ['pay_key'])) {
                    $account_info ['key'] = $account_info ['pay_key'];
                }
                if (isset ($account_info ['pay_mch_id'])) {
                    $account_info ['mch_id'] = $account_info ['pay_mch_id'];
                }
            } else {
                echo 'accout_info error';
                die;
            }
            if (!isset ($account_info ['app_id']) && !isset ($account_info ['pay_app_id'])) {
                $this->load->model('wx/publics_model');
                $pid = empty ($pay_id) ? $post_inter : $pay_id;
                $publics_info = $this->publics_model->get_public_by_id($pid, 'inter_id');
                if (isset ($publics_info ['app_id'])) {
                    $account_info ['app_id'] = $publics_info ['app_id'];
                    $account_info ['mch_appid'] = $publics_info ['app_id'];
                }
            }
            $this->load->helper('common');
            $this->load->model('pay/wxpay_model');
            if (isset($account_info ['pay_mch_id']))
                $account_info ['mch_id'] = $account_info ['pay_mch_id'];
            if (isset($account_info ['pay_key']))
                $account_info ['key'] = $account_info ['pay_key'];
            if (isset ( $account_info ['pay_app_id'] ) && $account_info ['pay_app_id'] != $account_info ['app_id']) {
                $account_info ['mch_appid'] = $account_info ['pay_app_id'];
                $account_info ['mch_id']    = $account_info ['pay_mch_id'];
                $account_info ['app_id']= $account_info ['mch_appid'];
            }

            $paras = $account_info;
            $arr ['appid'] = $paras ['app_id'];
            $arr ['mch_id'] = $paras ['mch_id'];
            $arr ['nonce_str'] = createNoncestr ();
            $arr ['partner_trade_no'] = $no;
            
            $this->load->model ( 'pay/wxpay_model' );
            $arr ['sign'] = $this->wxpay_model->getSign ( $arr, array ( 'key' => $paras ['key'], 'app_id' => $arr ['appid'] ) );
            $extras = array ();
            $extras ['CURLOPT_CAINFO'] = realpath ( '../' ) . DS . "certs" . DS . "rootca_" . $paras ['mch_id'] . ".pem";
            $extras ['CURLOPT_SSLCERT'] = realpath ( '../' ) . DS . "certs" . DS . "apiclient_cert_" . $paras ['mch_id'] . ".pem";
            $extras ['CURLOPT_SSLKEY'] = realpath ( '../' ) . DS . "certs" . DS . "apiclient_key_" . $paras ['mch_id'] . ".pem";
            $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo';
            $result = doCurlPostRequest ( $url, $this->wxpay_model->arrayToXml ( $arr ), $extras );

            log_message('error', 'DELIVER CHECK | '.$url.' | '.json_encode($arr).' | '.json_encode($extras));
            var_dump($result);
            $data = json_decode ( json_encode ( simplexml_load_string ( $result, NULL, LIBXML_NOCDATA ) ), true );
            var_dump($data);die;
        }
        $view_params = array();
        echo $this->_render_content ( $this->_load_view_file ( 'check' ), $view_params, TRUE );
    }
	

	
}
