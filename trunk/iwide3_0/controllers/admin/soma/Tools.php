<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends MY_Admin_Soma {

    protected $_basic_path;

    public function __construct() {
        parent::__construct();
        $this->_basic_path = APPPATH . '..' . DS . 'www_admin' . DS . 'public' . DS . 'import' . DS;
    }


	public function export_all_item() {
		/*
		 select `i`.`openid`,`i`.`inter_id`,`i`.`order_id`,`c`.`name` as `cname`,`i`.`name` as `pname`,`i`.`qty`,`i`.`price_package`,`o`.`status` from `iwide_soma_sales_order_item_package_1001` as `i` left join `iwide_soma_customer_contact` as `c` on `i`.`openid` = `c`.`openid` and `i`.`order_id` = `c`.`order_id` left join `iwide_soma_sales_order_1001` as `o` on `i`.`order_id` = `o`.`order_id`;
		 */

		$tmp_1001 = require 'E:\Mofly\iwide30dev\www_admin\1001.php';
    	$tmp_1002 = require 'E:\Mofly\iwide30dev\www_admin\1002.php';
    	$data = array_merge($tmp_1001, $tmp_1002);

    	$this->load->model('soma/Sales_order_model', 'o_model');
        $status = $this->o_model->get_status_label();
        foreach ($data as $k => $row) {
            $data[$k]['status'] = @$status[$row['status']];
        }

        $header= array('openid', 'inter_id', '订单号', '购买人', '商品名称', '数量', '价格', '订单状态');
        $url= $this->_do_export($data, $header, 'csv', TRUE );
	}

    public function export_all_consumer_item() {

        /*
        select `i`.`inter_id`,`i`.`order_id`,`s`.`consumer_type`,`s`.`consumer_time`,`i`.`status` from `iwide_soma_consumer_order_item_package_1001` as `i` left join `iwide_soma_consumer_order_1001` as `s` on `i`.`consumer_id` = `s`.`consumer_id`;
         */

        $tmp_1001 = require 'E:\Mofly\iwide30dev\www_admin\consumer_1001.php';
        $tmp_1002 = require 'E:\Mofly\iwide30dev\www_admin\consumer_1002.php';
        $data = array_merge($tmp_1001, $tmp_1002);

        $this->load->model('soma/Consumer_item_package_model', 'ci_model');
        $this->load->model('soma/Consumer_order_model', 'c_model');

        $c_type = $this->c_model->get_type_label();
        $ci_status = $this->ci_model->get_item_status_label();

        foreach ($data as $k => $row) {
            $data[$k]['consumer_type'] = @$c_type[$row['consumer_type']];
            $data[$k]['status'] = @$ci_status[$row['status']];
        }

        $header= array('inter_id', '商品订单号', '使用方式', '使用时间', '消费状态');
        $url= $this->_do_export($data, $header, 'csv', TRUE );
    }

    public function export_all_gift_item() {

        /*
        select `o`.`inter_id`,`o`.`openid_give`,`o`.`openid_received`,`i`.`name`,`o`.`status` from `iwide_soma_gift_order_1001` as `o` left join `iwide_soma_gift_order_item_package_1001` as `i` on `o`.`gift_id` = `i`.`gift_id`;
         */

        $tmp_1001 = require 'E:\Mofly\iwide30dev\www_admin\gift_1001.php';
        $tmp_1002 = require 'E:\Mofly\iwide30dev\www_admin\gift_1002.php';
        $data = array_merge($tmp_1001, $tmp_1002);

        $this->load->model('soma/Gift_order_model', 'o_model');
        // $this->load->model('soma/Gift_item_package_model', 'i_model');

        $status = $this->o_model->get_status_label();

        foreach ($data as $k => $row) {
            $data[$k]['status'] = @$status[$row['status']];
        }

        $header= array('inter_id', '赠送人openid', '接收人openid', '赠送商品', '状态');
        $url= $this->_do_export($data, $header, 'csv', TRUE );
    }

    public function url_test() {
        // $base64_url = "aHR0cDovL21rMjAxNi5pd2lkZS5jbi9pbmRleC5waHAvc29tYS9naWZ0L3BhY2thZ2VfcmVjZWl2ZWQ_aWQ9YTQ1OTEzMDE1OCZic249cGFja2FnZSZnaWQ9MTAwMDAwMDk3NyZzaWduPWFERnFia3BOWldZMlZuVjFWVzVDT0doRWIwcHdkejA5JmZyb209c2luZ2xlbWVzc2FnZSZpc2FwcGluc3RhbGxlZD0wJmNvZGU9MDMxVmRtWU8xZHNkOVgwS1FpMVAxb1JuWU8xVmRtWTAmcmVmZXI9YUhSMGNEb3ZMMjFyTWpBeE5pNXBkMmxrWlM1amJpOXBibVJsZUM1d2FIQXZjMjl0WVM5bmFXWjBMM0JoWTJ0aFoyVmZjbVZqWldsMlpXUV9hV1E5WVRRMU9URXpNREUxT0NaaWMyNDljR0ZqYTJGblpTWm5hV1E5TVRBd01EQXdNRGszTnlaemFXZHVQV0ZFUm5GaWEzQk9XbGRaTWxadVZqRldWelZEVDBkb1JXSXdjSGRrZWpBNUptWnliMjA5YzJsdVoyeGxiV1Z6YzJGblpTWnBjMkZ3Y0dsdWMzUmhiR3hsWkQwdw";
        // echo base64_url_decode($base64_url);
    }

    /**
     * 导入管理员信息
     * @return [type] [description]
     */
    public function import_admin() {
        
        $file_name = 'admin.csv';
        $file_path = $this->_basic_path . $file_name;
        
        $csv = fopen($file_path, 'r');
        $csv_data = array(); 
        $n = 0; 
        while ($data = fgetcsv($csv)) { 
            $num = count($data); 
            for ($i = 0; $i < $num; $i++) { 
                $csv_data[$n][$i] = mb_convert_encoding($data[$i], 'utf-8', 'gbk');//$data[$i]; 
            } 
            $n++; 
        }
        unset($csv_data[0]);

        $this->load->model('core/priv_admin', 'a_model');
        $row_key = array('role_id', 'inter_id', 'entity_id', 
            'username', 'password', 'nickname', 'email', 'remark');

        $_fmt_data = array();
        foreach ($csv_data as $row) {
            $_fmt_row = array();
            foreach ($row_key as $index => $key) {
                $_fmt_row[$key] = $row[$index];
                if($key == 'password') {
                    $_fmt_row[$key] = $this->a_model->encrytion_password($row[$index]);
                }
            }
            $_fmt_row['update_time'] = $_fmt_row['create_time'] = date('Y-m-d H:i:s');
            $_fmt_row['is_wx_report'] = $_fmt_row['is_em_report'] = $_fmt_row['is_sms_report'] = 1;
            $_fmt_row['status'] = 1;
            $_fmt_row['parent_id'] = 0;
            $_fmt_data[] = $_fmt_row;
        }

        if($this->a_model->batch_save($_fmt_data)) {
            @unlink($file_path);
            echo "success";
        } else {
            echo "fail!";
        }

    }

    public function batch_sync_reward_benefit() {
        $this->_toolkit_writelist();

        $file_name = $this->input->get('fn', true);
        if(!$file_name) { $file_name = 'reward'; }
        $file_path = $this->_basic_path . $file_name . '.csv';
        $csv_data = $this->_parse_csv_file($file_path);

        $inter_id_pos = 0;
        $order_id_pos = 1;
        $saler_id_pos = 2;
        foreach ($csv_data[0] as $pos => $column) {
            if($column == 'inter_id') { $inter_id_pos = $pos; }
            if($column == 'order_id') { $order_id_pos = $pos; }
            if($column == 'saler_id') { $saler_id_pos = $pos; }
        }
        unset($csv_data[0]);

        $order_data = array();
        foreach ($csv_data as $row) {
            $_tmp_row['order_id'] = $row[$order_id_pos];
            $_tmp_row['saler_id'] = $row[$saler_id_pos];
            $_tmp_row['inter_id'] = $row[$inter_id_pos];
            $order_data[ $row[$order_id_pos] ] = $_tmp_row;
        }

        var_dump($order_data);exit;

        $this->load->model('soma/sales_order_model', 'o_model');
        $this->load->model('soma/shard_config_model', 'c_model');
        $this->load->model('soma/Reward_benefit_model', 'r_model');

        // 过滤已发绩效的数据
        $data = $this->r_model->filter_orders($order_data);

        $result = array();
        foreach ($order_data as $row) {
            try {
                if(!in_array($row['order_id'], array_keys($data))) {
                    $result[] = array(
                        'oid' => $row['order_id'],
                        'op_res' => 'fail',
                        'msg' => 'order already sync!',
                    );
                    continue;
                }

                $inter_id = $row['inter_id'];   
                $this->db_shard_config= $this->c_model->build_shard_config($inter_id);  

                $order = $this->o_model->load($row['order_id']);
                if(!$order) {
                    $result[] = array(
                        'oid' => $row['order_id'],
                        'op_res' => 'fail',
                        'msg' => 'load order fail!',
                    );
                    continue;
                }
                $saler_id = $order->m_get('saler_id');
                if(true || !$saler_id || $saler_id == '0') {
                    // 没有saler_id的更新saler_id
                    $order->m_set('saler_id', $row['saler_id'])->m_save();
                }
                $order->business= 'package';    

                $res = $this->r_model->write_benefit_queue($inter_id, $order);
                // $res = true;
                if(!$res) {
                    $result[] = array(
                        'oid' => $row['order_id'],
                        'op_res' => 'fail',
                        'msg' => 'write_benefit_queue() return false!',
                    );
                } else {
                    $result[] = array(
                        'oid' => $row['order_id'],
                        'op_res' => 'success',
                        'msg' => '',
                    );
                }
            } catch (Exception $e) {
                $result[] = array(
                    'oid' => $row['order_id'],
                    'op_res' => 'fail',
                    'msg' => $e->getMessage(),
                );
            }
        }

        $html = '<html><body><div><table border="1">';
        foreach ($result as $row) {
            $html .= '<tr';
            if($row['op_res'] == 'fail') {
                $html .= ' style="color: red;"';
            }
            $html .= '>';
            $html .= '<td>' . $row['oid'] . '</td>';
            $html .= '<td>' . $row['op_res'] . '</td>';
            $html .= '<td>' . $row['msg'] . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table></div></body></html>';

        echo $html;
    }

    protected function _parse_csv_file($file) {
        $csv = fopen($file, 'r');
        $csv_data = array(); 
        $n = 0; 
        while ($data = fgetcsv($csv)) { 
            $num = count($data); 
            for ($i = 0; $i < $num; $i++) { 
                $csv_data[$n][$i] = mb_convert_encoding($data[$i], 'utf-8', 'gbk');//$data[$i]; 
            } 
            $n++; 
        }
        return $csv_data;
    }

    /**
     * insert into iwide_soma_sales_payment value
     */
    public function sign_order_asset() {
        $this->_toolkit_writelist();

        $inter_id = $this->input->get('id', true);
        $order_id = $this->input->get('oid', true);
        $this->_init_current_inter_id($inter_id);

        $this->load->model('soma/sales_order_model', 'o_model');
        $order = $this->o_model->load($order_id);

        $res = $order->get_order_asset($order->m_get('business'), $order->m_get('inter_id'));

        if($order) {
            $res = $order->get_order_asset($order->m_get('business'), $order->m_get('inter_id'));
            if(count($res['items']) == 0) {
                $order->sign_item_to_asset($order->m_get('business'), $order->m_get('inter_id'));
                echo 'success';exit;
            } else {
                echo 'asset already exist!';exit;
            }
        }
        echo 'fail';exit;
    }

    public function statis_product() {

        $this->_toolkit_writelist();

        $s_date = $this->input->get('s_date', true);
        $e_date = $this->input->get('e_date', true);

        $s_time = date('Y-m-d', strtotime($s_date)) . ' 00:00:00';
        $e_time = date('Y-m-d', strtotime($e_date)) . ' 23:59:59';

        $this->load->model('soma/statis_product_model', 's_model');
        $this->s_model->update_statis_data($s_time, $e_time);

        echo 'success';
    }

    public function rebuild_order_total_data() {
        $this->_toolkit_writelist();

        $s_date = $this->input->get('s_date', true);
        $e_date = $this->input->get('e_date', true);

        $s_time = date('Y-m-d', strtotime($s_date)) . ' 00:00:00';
        $e_time = date('Y-m-d', strtotime($e_date)) . ' 23:59:59';

        $this->load->model('soma/Sales_order_model', 'o_model');
        $this->o_model->rebuild_order_total_data($s_time, $e_time);

        echo 'success';
    }

    public function statis_sales() {

        $this->_toolkit_writelist();

        $s_date = $this->input->get('s_date', true);
        $e_date = $this->input->get('e_date', true);

        $s_time = date('Y-m-d', strtotime($s_date)) . ' 00:00:00';
        $e_time = date('Y-m-d', strtotime($e_date)) . ' 23:59:59';

        $this->load->model('soma/statis_sales_model', 's_model');
        $this->s_model->init_service()->update_sales_data($s_time, $e_time);

        echo 'success';
    }

    /**
     * 导入管理员信息
     * @return [type] [description]
     */
    public function statis_distribute_fans_order() {

        $this->_toolkit_writelist();
        
        $file_name = 'distribute.csv';
        $file_path = $this->_basic_path . $file_name;
        
        $csv = fopen($file_path, 'r');
        $csv_data = array(); 
        $n = 0; 
        while ($data = fgetcsv($csv)) { 
            $num = count($data); 
            for ($i = 0; $i < $num; $i++) { 
                $csv_data[$n][$i] = mb_convert_encoding($data[$i], 'utf-8', 'gbk');//$data[$i]; 
            } 
            $n++; 
        }
        // unset($csv_data[0]);

        $openids = array();
        $data = array();
        foreach ($csv_data as $row) {
            $openids[] = $row[1];
            if(count($openids)>1000){
                $this->load->model('soma/Sales_order_model', 'o_model');
                $res = $this->o_model->get_order_collection(array('where' => array('openid' => $openids, 'status' => 12, 'create_time' > '2017-02-01 00:00:00', 'create_time' < '2017-02-28 23:59:59')));
                $data = array_merge($data, $res);
                $openids = array();
            }
        }

        $this->load->model('soma/Sales_order_model', 'o_model');
        $res = $this->o_model->get_order_collection(array('where' => array('openid' => $openids, 'status' => 12, 'create_time' > '2017-02-01 00:00:00', 'create_time' < '2017-02-28 23:59:59')));
        $data = array_merge($data, $res);

        $fmt_data = array();
        foreach ($data as $row) {
            if(!isset($fmt_data[$row['openid']])) {
                $fmt_data[$row['openid']] = 0;
            }
            $fmt_data[$row['openid']] ++;
        }

        $html = '<html><body><div><table border="1">';
        foreach ($fmt_data as $openid => $value) {
            $html .= '<tr>';
            $html .= '<td>' . $openid . '</td>';
            $html .= '<td>' . $value . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table></div></body></html>';

        echo $html;

    }

    public function rebuild_voucher_data()
    {
        $this->_toolkit_writelist();

        $file_name = $this->input->get('fn', true);
        if(!$file_name) { $file_name = 'voucher'; }
        $file_path = $this->_basic_path . $file_name . '.csv';
        $csv_data = $this->_parse_csv_file($file_path);
            
        $this->load->model('soma/Sales_voucher_model', 'v_model');

        foreach($csv_data as $row)
        {
            $this->v_model->load_by_code($row[1]);
            if($this->v_model)
            {
                $this->v_model->m_set('code', $row[2])->m_save();
            }
        }

        echo 'success';
    }

    public function send_template_msg()
    {
        $this->_toolkit_writelist();

        $inter_id = 'a484122795';
        $template_id = 'BM20NbWAxqv0D-EBD1csiilkMPdRoonm5QHdsjJCvOo';
        $to_url = "http://hotels.iwide.cn/index.php/soma/order/my_order_list?id=a484122795";

        $file_name = 'order.csv';
        $file_path = $this->_basic_path . $file_name;        
        $csv_data = $this->_parse_csv_file($file_path);
        unset($csv_data[0]);

        $this->load->model('soma/Sales_order_model', 'o_model');
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');

        foreach ($csv_data as $row) {
            $order = $this->o_model->load($row[0]);
            if ($order) {
                $items = $order->get_order_items('package', $inter_id);
                if(!empty($items)) {
                    $data['template_id'] = $template_id;
                    $data['touser'] = $order->m_get('openid');
                    $data['url'] = $to_url;
                    $data['topcolor'] = '#000000';
                    $subdata['first'] = array(
                        'value' => '您未消费的订单已经延期至6月30日，请尽快到店消费',
                        'color' => '#000000' 
                    );
                    $subdata['keyword1'] = array(
                        'value' => $order->m_get('order_id'),
                        'color' => '#000000'
                    );
                    $subdata['keyword2'] = array(
                        'value' => $order->m_get('create_time'),
                        'color' => '#000000'
                    );
                    $subdata['keyword3'] = array(
                        'value' => $items[0]['expiration_date'],
                        'color' => '#000000'
                    );
                    $subdata['remark'] = array(
                        'value' => '如您过期未使用，将不能再使用且不退款',
                        'color' => '#000000'
                    );
                    $data['data'] = $subdata;
                    $res = $this->t_model->send_template(json_encode($data), $inter_id);
                    $res['order_id'] = $order->m_get('order_id');
                    var_dump($res);
                } else {
                    var_dump(array('order_id' => $row[0], 'msg' => '订单细单不存在'));
                }
            } else {
                var_dump(array('order_id' => $row[0], 'msg' => '订单不存在'));
            }
        }

    }

    public function repay()
    {
        $this->_toolkit_writelist();

        $inter_id = $this->input->get('inter_id', true);
        $order_id = $this->input->get('order_id', true);

        $this->load->model('soma/sales_order_model');
        $order = $this->sales_order_model->load($order_id);

        if ($order) {
            $this->load->model('soma/sales_payment_model');
            $payment_model= $this->sales_payment_model;
            //取得商户/子商户的openid,
            //$openid = empty ( $result['sub_openid'] ) ? $result['openid'] : $result['sub_openid'];
            
            $log_data= array();
            $log_data['paid_ip']= $this->input->ip_address();
            $log_data['paid_type']= $payment_model::PAY_TYPE_WX;
            $log_data['order_id']= $order_id;
            $log_data['openid']= $order->m_get('openid');
            $log_data['business']= $order->m_get('business');
            $log_data['settlement']= $order->m_get('settlement');
            $log_data['inter_id']= $order->m_get('inter_id');
            $log_data['hotel_id']= $order->m_get('hotel_id');
            $log_data['grand_total']= $order->m_get('grand_total');
            $log_data['transaction_id']= $order->m_get('transaction_id');

            $order->order_payment( $log_data );
            $order->order_payment_post();
            
            $this->sales_payment_model->save_payment($log_data, NULL);  //校验签名时已经记录
        }

        echo 'success';
    }

    /**
     * 检查礼物回退的无核销码问题
     *
     ** @param      s_time   开始时间
     *  @param      e_time   结束时间
     * 
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function check_gift_return()
    {
        $this->_toolkit_writelist();

        $oid = 'order_id';
        $type = $this->input->get('type', true);
        if (!empty($type) && $type == 'gift') {
            $oid = 'gift_id';
        }

        $s_time = date('Y-m-d H:i:s', strtotime($this->input->get('s_time')));
        $e_time = date('Y-m-d H:i:s', strtotime($this->input->get('e_time')));
        $suffixs = ['_1001', '_1002'];
        $ids = [];

        foreach ($suffixs as $suffix) {
            $gift_table  = 'iwide_soma_gift_order' . $suffix;
            $asset_table = 'iwide_soma_asset_item_package' . $suffix;
            $code_table  = 'iwide_soma_consumer_code';

            $gift_data = $this->soma_db_conn_read
                ->select('*')
                ->where('status', 4)
                ->where('create_time >=', $s_time)
                ->where('create_time <=', $e_time)
                ->get($gift_table)->result_array();
            
            $order_ids = [];
            foreach ($gift_data as $row) {
                $order_ids[] = $row['send_order_id'];
            }
            if(empty($order_ids)) {
                break;
            }

            $asset_data = $this->soma_db_conn_read
                ->select('*')
                ->where('qty >', 0)
                ->where_in($oid, $order_ids)
                ->get($asset_table)->result_array();
            
            $aiids = [];
            foreach ($asset_data as $row) {
                $aiids[] = $row['item_id'];
            }

            $code_data = $this->soma_db_conn_read
                ->select('*')
                ->where_in('asset_item_id', $aiids)
                ->where('status', 2)
                ->get($code_table)->result_array();

            $asset_code_cnt = [];
            foreach ($code_data as $row) {
                if (empty($asset_code_cnt[ $row['asset_item_id'] ])) {
                    $asset_code_cnt[ $row['asset_item_id'] ] = 1;
                } else {
                    $asset_code_cnt[ $row['asset_item_id'] ] += 1;
                }
            }

            foreach ($asset_data as $row) {
                $least = $row['qty'];
                if (isset($asset_code_cnt[ $row['item_id'] ])) {
                    $least = $row['qty'] - $asset_code_cnt[ $row['item_id'] ];
                }
                if ($least > 0) {
                    // $data[$row['item_id']] = ['least' => $least, 'item' => $row];
                    $ids[] = [
                        'order_id' => $row['order_id'],
                        'gift_id'  => $row['gift_id'],
                        'inter_id' => $row['inter_id'],
                        'lack_qty' => $least
                    ];
                }
            }
        }
        $html = '<html><body><div><table border="1">';
        foreach ($ids as $row) {
            $html .= '<tr>';
            $html .= '<td>' . $row['order_id'] . '</td>';
            $html .= '<td>' . $row['gift_id'] . '</td>';
            $html .= '<td>' . $row['inter_id'] . '</td>';
            $html .= '<td>' . $row['lack_qty'] . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table></div></body></html>';
        echo $html;
    }

    /**
     * 礼物回退时无资产的订单进行资产增加
     *
     * @param      order_id   订单号
     * @param      inter_id   公众号
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function gift_return()
    {
        $this->_toolkit_writelist();

        $order_id = $this->input->get('order_id', true);
        $inter_id = $this->input->get('inter_id', true);
        $oid = 'order_id';
        $type = $this->input->get('type', true);
        if (!empty($type) && $type == 'gift') {
            $oid = 'gift_id';
        }

        $this->load->model('soma/shard_config_model');
        $this->current_inter_id = $inter_id;
        $this->db_shard_config = $this->shard_config_model->build_shard_config($inter_id);

        $this->load->model('soma/consumer_code_model', 'c_model');

        $asset_table = $this->c_model->_shard_table('soma_asset_item_package', $inter_id);
        $code_table  = $this->c_model->_shard_table('soma_consumer_code', $inter_id);

        $asset_data = $this->soma_db_conn_read
                ->select('*')
                ->where('qty >', 0)
                ->where($oid, $order_id)
                ->get($asset_table)->result_array();

        if (empty($asset_data)) {
            die('没有可用资产数量!');
        }

        $aiids = [];
        foreach ($asset_data as $row) {
            $aiids[] = $row['item_id'];
        }

        $code_data = $this->soma_db_conn_read
            ->select('*')
            ->where_in('asset_item_id', $aiids)
            ->where('status', 2)
            ->get($code_table)->result_array();

        $asset_code_cnt = [];
        foreach ($code_data as $row) {
            if (empty($asset_code_cnt[ $row['asset_item_id'] ])) {
                $asset_code_cnt[ $row['asset_item_id'] ] = 1;
            } else {
                $asset_code_cnt[ $row['asset_item_id'] ] += 1;
            }
        }

        $result = [];
        foreach ($asset_data as $row) {
            $least = $row['qty'];
            if (isset($asset_code_cnt[ $row['item_id'] ])) {
                $least = $row['qty'] - $asset_code_cnt[ $row['item_id'] ];
            }
            if ($least > 0) {
                // 这个资产缺少核销码
                $data = [
                    'asset_item_id' => $row['item_id'],
                    'order_item_id' => $row['order_item_id'],
                    'order_id'      => $row['order_id'],
                    'asset_id'      => $row['asset_id'],
                ];
                $tmp = $data;
                $tmp['qty'] = $least;
                $tmp['res'] =  $this->c_model->generate_asset_code($data, $least, $inter_id);
                $tmp['gift_id'] = $row['gift_id'];
                $result[] = $tmp;
            }
        }

        $html = '<html><body><div><table border="1">';
        foreach ($result as $row) {
            $html .= '<tr>';
            $html .= '<td>' . $row['asset_id'] . '</td>';
            $html .= '<td>' . $row['asset_item_id'] . '</td>';
            $html .= '<td>' . $row['order_id'] . '</td>';
            $html .= '<td>' . $row['order_item_id'] . '</td>';
            $html .= '<td>' . $row['gift_id'] . '</td>';
            $html .= '<td>' . $row['qty'] . '</td>';
            $html .= '<td>' . ($row['res'] ? 'success' : 'fail') . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table></div></body></html>';

        echo $html;
    }

}