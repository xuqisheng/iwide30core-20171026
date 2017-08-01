<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Transaction
 * 交易流水管理
 * 沙沙
 * 2017-06-27
 */

class Transaction extends MY_Admin
{
    protected $label_module = '列表';
    protected $label_controller = '列表';
    protected $label_action = '列表';
    public $username = '';

    public function __construct()
    {
        parent::__construct();
        $this->admin_profile = $this->session->userdata('admin_profile');
        $this->load->helper('appointment');
    }


    /**
     * 首页管理列表
     *
     */
    public function index()
    {
        $param = request();
        $return = array(
            'param'      => $param,
        );

        echo $this->_render_content($this->_load_view_file('index'), $return, TRUE);
    }

    /**
     * 导出数据
     */
    public function ext_data()
    {
        $param = request();
        $filter['inter_id'] = !empty($param['inter_id']) ? addslashes($param['inter_id']) : '';
        $filter['hotel_id'] = !empty($param['hotel_id']) ? intval($param['hotel_id']) : '';
        $filter['start_time'] = !empty($param['start_time']) ? addslashes($param['start_time']) : '';
        $filter['end_time'] = !empty($param['end_time']) ? addslashes($param['end_time']) : '';
        $filter['module'] = !empty($param['module']) ? addslashes($param['module']) : '';
        $filter['order_status'] = !empty($param['order_status']) ? addslashes($param['order_status']) : '';
        $filter['transfer_status'] = !empty($param['transfer_status']) ? addslashes($param['transfer_status']) : '';
        $filter['order_no'] = !empty($param['order_no']) ? addslashes($param['order_no']) : '';
        $per_page = !empty($param['limit']) ? intval($param['limit']) : '';//显示数量
        $cur_page = !empty($param['offset']) ? intval($param['offset']) : '';//页码

        if (empty($filter['inter_id']))
        {
            $filter['inter_id'] = $this->admin_profile['inter_id'];
        }
        if (empty($filter['hotel_id']))
        {
            $filter['hotel_id'] = $this->admin_profile['entity_id'];
        }

        $this->load->model('iwidepay/iwidepay_order_model' );
        $this->load->model('iwidepay/iwidepay_transfer_model' );
        $select = 'o.id,o.inter_id,o.hotel_id,o.module,o.order_no,o.pay_no,o.order_status,o.transfer_status,o.orig_amount,o.trans_amt,o.is_dist,o.add_time';
        $status = array(0=>'--',1=>'待定',2=>'待分',3=>'已分',4=>'异常',5=>'待定未分完',6=>'退款',7=>'已结清全额退款',8=>'部分退款',9=>'已结清部分退款',10=>'退款异常');
        $module = array('hotel'=>'订房','soma'=>'商城','vip'=>'会员','okpay'=>'快乐付','dc'=>'在线点餐');
        $is_dist = array('0'=>'否','1'=>'是','2'=>'是','3'=>'是');
        $list = $this->iwidepay_order_model->get_orders($select,$filter,$cur_page,$per_page);

        if ($list)
        {
            foreach ($list as $key => $value)
            {
                $order_no[] = $value['order_no'];
            }
            $transfer = $this->iwidepay_transfer_model->get_transfer('order_no,type,amount,add_time,module,name',$order_no);

            $transfer_data = $transfer_time = $transfer_hotel = array();
            if (!empty($transfer))
            {
                foreach ($transfer as $val)
                {
                    if ($val['module'] == 'soma')
                    {
                        $transfer_hotel[$val['order_no']] = $transfer_data[$val['type'].'_'.$val['order_no']] = array();
                        $transfer_hotel[$val['order_no']][] = !empty($val['name']) ? $val['name'] : '';
                        $transfer_hotel[$val['order_no']] = implode(',',$transfer_hotel[$val['order_no']]);

                        $transfer_data[$val['type'].'_'.$val['order_no']][] = formatMoney($val['amount']/100);
                        $transfer_data[$val['type'].'_'.$val['order_no']] = implode(',',$transfer_data[$val['type'].'_'.$val['order_no']]);
                    }
                    else
                    {
                        $transfer_data[$val['type'].'_'.$val['order_no']] = formatMoney($val['amount']/100);
                    }

                    $transfer_time[$val['order_no']] = $val['add_time'];
                }
            }

            foreach ($list as $key => $value)
            {
                $item = array();
                $item['add_time'] = $value['add_time'];
                $item['name'] = !empty($value['name']) ? $value['name'] : '';
                $item['hotel_name'] = !empty($value['hotel_name']) ? $value['hotel_name'] : '';
                $item['module'] = $module[$value['module']];
                $item['order_no'] = $value['order_no'];
                $item['pay_no'] = $value['pay_no'];
                $item['order_status'] = return_iwidepay_status($value);
                // $value['trans_amt'] = formatMoney($value['trans_amt']/100);

                // if ($value['transfer_status'] == 6 || $value['transfer_status'] == 7)
                // {
                //     $value['trans_amt'] = '-' . $value['trans_amt'];
                // }
                //add by chenjunyu 2017-07-27 19:20:00 改为显示原始交易金额，退款不显示负号
                $value['trans_amt'] = formatMoney($value['orig_amount']/100);

                $item['transfer_status'] = $status[$value['transfer_status']];
                $item['trans_amt'] =  $value['trans_amt'];
                $item['sell_hotel_id'] = !empty($transfer_hotel[$value['order_no']]) ? $transfer_hotel[$value['order_no']] : '';

                //分成
                $item['cost'] = !empty($transfer_data['cost'.'_'.$value['order_no']]) ? $transfer_data['cost'.'_'.$value['order_no']] : '--';
                $item['jfk'] = !empty($transfer_data['jfk'.'_'.$value['order_no']]) ? $transfer_data['jfk'.'_'.$value['order_no']] : '--';
                $item['group'] = !empty($transfer_data['group'.'_'.$value['order_no']]) ? $transfer_data['group'.'_'.$value['order_no']] : '--';
                $item['hotel'] = !empty($transfer_data['hotel'.'_'.$value['order_no']]) ? $transfer_data['hotel'.'_'.$value['order_no']] : '--';
                $item['dist'] = !empty($transfer_data['dist'.'_'.$value['order_no']]) ? $transfer_data['dist'.'_'.$value['order_no']] : '--';

                $list[$key] = $item;
            }
        }

        $headArr = array('交易时间','所属公众号','所属门店','来源模块','平台订单号','支付订单号','交易状态','分账状态','交易金额(元)','核销门店','交易手续费','金房卡分成','集团分成','门店分成','分销员分成');
        $widthArr = array(20,20,20,12,20,20,12,12,12,12,12,12,12,14);
        getExcel('交易流水',$headArr,$list,$widthArr);
    }
}