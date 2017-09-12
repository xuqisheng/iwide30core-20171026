<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Transaction
 * 财务对账单
 * 沙沙
 * 2017-06-27
 */

class Financial extends MY_Admin
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

		set_time_limit(0);
        @ini_set('memory_limit','1624M');
    }


    /**
     * 首页管理列表
     *
     */
    public function index()
    {
        $param = request();
        $return = array(
            'param' => $param,
        );

        echo $this->_render_content($this->_load_view_file('index'), $return, TRUE);
    }

    /**
     * 导出数据
     */
    public function ext_financial()
    {
        $param = request();
        $filter['inter_id'] = !empty($param['inter_id']) ? addslashes($param['inter_id']) : $this->admin_profile['inter_id'];
        $filter['pay_start_time'] = !empty($param['start_time']) ? addslashes($param['start_time']) : '';
        $filter['pay_end_time'] = !empty($param['end_time']) ? addslashes($param['end_time']) : '';
        $filter['transfer_status'] = '3,5,7,8,9';//已分账

        //集团账号
        $filter['hotel_id'] = $this->admin_profile['entity_id'];

        $this->load->model('iwidepay/iwidepay_order_model' );
        $this->load->model('iwidepay/iwidepay_refund_model' );
        $this->load->model('iwidepay/iwidepay_transfer_model' );
        $select = 'o.id,o.inter_id,o.hotel_id,o.module,o.order_no,o.pay_no,o.order_status,o.transfer_status,o.trans_amt,o.is_dist,o.pay_time';
        $status = array(0=>'--',1=>'待定',2=>'待分',3=>'已分',4=>'异常',5=>'待定未分完',6=>'退款',7=>'已结清全额退款',8=>'部分退款',9=>'已结清部分退款');
        $module = array('hotel'=>'订房','soma'=>'商城','vip'=>'会员','okpay'=>'快乐付','dc'=>'在线点餐','ticket' => '预约核销','base_pay' => '基础月费');
        $list = $this->iwidepay_order_model->get_orders($select,$filter,'','');

        //已分账
        $sort_time = array();

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

                        $temp[$val['type'].'_'.$val['order_no']][] = !empty($val['name']) ? $val['name'] : '';
                        $transfer_hotel[$val['order_no']] = implode(',',$temp[$val['type'].'_'.$val['order_no']]);

                        $tmp[$val['type'].'_'.$val['order_no']][] = formatMoney($val['amount']/100);
                        $transfer_data[$val['type'].'_'.$val['order_no']] = implode(',',$tmp[$val['type'].'_'.$val['order_no']]);
                    }
                    else
                    {
                        $transfer_data[$val['type'].'_'.$val['order_no']] = formatMoney($val['amount']/100);
                    }

                    $transfer_time[$val['order_no']] = $val['add_time'];
                }
            }

            unset($transfer);

            foreach ($list as $key => $value)
            {
                $item = array();
                $item['add_time'] = $value['pay_time'];
                $item['name'] = !empty($value['name']) ? $value['name'] : '';
                $item['hotel_name'] = !empty($value['hotel_name']) ? $value['hotel_name'] : '';
                $item['module'] = $module[$value['module']];
                $item['order_no'] = $value['order_no'];
                $item['pay_no'] = $value['pay_no'];
                $item['order_status'] = '交易';
                $value['trans_amt'] = formatMoney($value['trans_amt']/100);

                if ($value['transfer_status'] == 6 || $value['transfer_status'] == 7)
                {
                    $value['trans_amt'] = '-' . $value['trans_amt'];
                }

                $item['transfer_status'] = '已分账';
                $item['transfer_time'] = !empty($transfer_time[$value['order_no']]) ? date('Y-m-d',strtotime($transfer_time[$value['order_no']])) : '--';

                $item['trans_amt'] =  $value['trans_amt'];
                $item['sell_hotel_id'] = !empty($transfer_hotel[$value['order_no']]) ? $transfer_hotel[$value['order_no']] : '';

                //分成
                $item['cost'] = !empty($transfer_data['cost'.'_'.$value['order_no']]) ? $transfer_data['cost'.'_'.$value['order_no']] : '--';
                $item['jfk'] = !empty($transfer_data['jfk'.'_'.$value['order_no']]) ? $transfer_data['jfk'.'_'.$value['order_no']] : '--';
                $item['group'] = !empty($transfer_data['group'.'_'.$value['order_no']]) ? $transfer_data['group'.'_'.$value['order_no']] : '--';
                $item['hotel'] = !empty($transfer_data['hotel'.'_'.$value['order_no']]) ? $transfer_data['hotel'.'_'.$value['order_no']] : '--';
                $item['dist'] = !empty($transfer_data['dist'.'_'.$value['order_no']]) ? $transfer_data['dist'.'_'.$value['order_no']] : '--';

                $list[$key] = $item;

                $sort_time[] = $item['add_time'];
            }
        }

        //已结清全额退款
        $where_arr = $filter;
        $where_arr['start_time'] = $filter['pay_start_time'];
        $where_arr['end_time'] = $filter['pay_end_time'];
        $where_arr['type'] = 2;
        $select = 'R.inter_id,R.hotel_id,R.module,R.hotel_id,R.amount,R.orig_order_no,R.refund_amt,R.add_time,R.charge';
        $list_refund = $this->iwidepay_refund_model->get_refund($select,$where_arr,'','');

        if (!empty($list_refund))
        {
            foreach ($list_refund as $key => $value)
            {
                $item = array();
                $item['add_time'] = $value['add_time'];
                $item['name'] = !empty($value['name']) ? $value['name'] : '';
                $item['hotel_name'] = !empty($value['hotel_name']) ? $value['hotel_name'] : '';
                $item['module'] = $module[$value['module']];
                $item['order_no'] = $value['orig_order_no'];
                $item['pay_no'] = '--';
                $item['order_status'] = '退款';
                $item['transfer_status'] = '已结清全额退款';
                $item['transfer_time'] = '--';
                $item['trans_amt'] = formatMoney($value['refund_amt']/100);
                $item['sell_hotel_id'] = '--';

                //分成
                $item['cost'] = formatMoney($value['charge']/100);
                $item['jfk'] = '0';
                $item['group'] = '0';
                $item['hotel'] = formatMoney(($value['refund_amt'] - $value['charge'])/100);
                $item['dist'] = '0';

                $list_refund[$key] = $item;

                $sort_time[] = $item['add_time'];
            }

            $list = array_merge($list,$list_refund);
        }

        array_multisort($sort_time, SORT_DESC, $list);

        $headArr = array('交易时间','所属公众号','所属门店','来源模块','平台订单号','支付订单号','交易类型','分账状态','分账时间','交易/退款金额(元)','核销门店','交易手续费','金房卡分成','集团分成','门店分成','分销员分成');
        $widthArr = array(20,20,20,12,20,20,12,12,12,12,12,12,12,14);
        getExcel('分账财务对账表',$headArr,$list,$widthArr);

    }


    /**
     * 导出 对账单
     */
    public function ext_data()
    {
        $param = request();
        $filter['inter_id'] = !empty($param['inter_id']) ? addslashes($param['inter_id']) : $this->admin_profile['inter_id'];
        $filter['start_time'] = !empty($param['start_time']) ? addslashes($param['start_time']) : '';
        $filter['end_time'] = !empty($param['end_time']) ? addslashes($param['end_time']) : '';

        //集团账号
        $filter['hotel_id'] = $this->admin_profile['entity_id'];

        $this->load->model('iwidepay/Iwidepay_financial_model');
        $list = $this->Iwidepay_financial_model->get_financial('fc.*',$filter);
        $module = array('hotel'=>'订房','soma'=>'商城','vip'=>'会员','okpay'=>'快乐付','dc'=>'在线点餐','ticket' => '预约核销','base_pay' => '基础月费','dist' => '分销');
        $trade_type = array(1 => '交易', 2 => '垫付退款', 3 => '原款退款', 4 => '首单奖励', 5 => '额外奖励', 6 => '月费基础', 7 => '交易');
        $transfer_status = array(1 => '部分分账', 2 => '已分账', 3 => '已结清');
        if (!empty($list))
        {
            foreach ($list as $key => $value)
            {
                $item['trade_time'] = $value['trade_time'];
                $item['name'] = !empty($value['name']) ? $value['name'] : '';
                $item['hotel_name'] = !empty($value['hotel_name']) ? $value['hotel_name'] : '--';
                $item['module'] = !empty($module[$value['module']]) ? $module[$value['module']] : '--';
                $item['order_no'] = $value['order_no'];
                $item['pay_no'] = $value['pay_no'];
                $item['trade_type'] = !empty($trade_type[$value['trade_type']]) ? $trade_type[$value['trade_type']] : '--';
                $item['transfer_status'] = !empty($transfer_status[$value['transfer_status']]) ? $transfer_status[$value['transfer_status']] : '--';
                $item['transfer_date'] = date('Y-m-d',strtotime($value['transfer_date']));
                $item['amount'] = formatMoney($value['amount']/100);

                //核销门店
                $item['write_off_hotel_id'] = '';
                if ($value['module'] == 'soma' && $value['hotel_id'] == '9999999' && $value['write_off_hotel_id'] != '9999999')
                {
                    $hotel = $this->Iwidepay_financial_model->get_hotel_info($value['inter_id'],$value['write_off_hotel_id']);
                    $item['write_off_hotel_id'] = !empty($hotel['name']) ? $hotel['name'] : '--';
                }

                $item['cost_amount'] = !empty($value['cost_amount']) ? formatMoney($value['cost_amount']/100) : '--';
                $item['jfk_amount'] = !empty($value['jfk_amount']) ? formatMoney($value['jfk_amount']/100) : '--';
                $item['group_amount'] = !empty($value['group_amount']) ? formatMoney($value['group_amount']/100) : '--';

                //线下支付
                if ($value['trade_type'] == 7)
                {
                    $item['hotel_amount'] = '-' . formatMoney(($value['jfk_amount'] + $value['group_amount'] + $value['dist_amount'])/100);
                }
                else if (in_array($value['trade_type'],array(2,4,5,6)))
                {
                    $item['hotel_amount'] = '-' . $item['amount'];
                }
                else
                {
                    $item['hotel_amount'] = !empty($value['hotel_amount']) ? formatMoney($value['hotel_amount']/100) : '--';
                }

                $item['dist_amount'] = !empty($value['dist_amount']) ? formatMoney($value['dist_amount']/100) : '--';
                $list[$key] = $item;
            }
        }

        $headArr = array('交易时间','所属公众号','所属门店','来源模块','平台订单号','支付订单号','交易类型','分账状态','分账时间','交易/退款金额(元)','核销门店','交易手续费','金房卡分成','集团分成','门店分成','分销员分成');
        $widthArr = array(20,20,20,12,25,25,12,12,20,18,20,12,12,12,12,15);
        getExcel('分账财务对账表',$headArr,$list,$widthArr);

    }


    /**
     * 计划任务生成 财务对账单 => 每天退款订单
     */
    public function run_refund_financial()
    {
        die('no allow');
        $this->load->model('iwidepay/Iwidepay_financial_model');

        //退款记录
        $stat_time = date('Y-m-d',strtotime('-1 days'));
        $end_time = date('Y-m-d 23:59:60',strtotime('-1 days'));
        $list_refund = $this->Iwidepay_financial_model->refund_order($stat_time,$end_time,'1,3');
        if (!empty($list_refund))
        {
            //插入对账单表
            foreach ($list_refund as $value)
            {
                $item = array(
                    'module'    => $value['module'],
                    'order_no'  => $value['orig_order_no'],
                    'pay_no'    => $value['ori_pay_no'],
                    'trade_type' => in_array($value['type'],array(1,3)) ? 3 : 2, //2-垫付退款,3-原款退款
                    'transfer_status' => 3, //3-已结清
                    'transfer_date' => date('Y-m-d',strtotime($value['add_time'])),
                    'amount' => $value['refund_amt'],
                    'inter_id' => $value['inter_id'],
                    'hotel_id' => $value['hotel_id'],
                    'trade_time' => $value['add_time'],
                    'add_time' => date('Y-m-d H:i:s'),
                );
                $this->Iwidepay_financial_model->insert_order($item);
            }
        }
    }


    /**
     * 计划任务生成 财务对账单 => 每天欠款订单
     */
    public function run_debt_financial()
    {
        die('no allow');
        $this->load->model('iwidepay/Iwidepay_financial_model');

        //退款记录
        $stat_time = date('Y-m-d',strtotime('-50 days'));//,strtotime('-50 days')
        $end_time = date('Y-m-d 23:59:60');
        $list_debt = $this->Iwidepay_financial_model->debt_order($stat_time,$end_time);
        if (!empty($list_debt))
        {
            //插入对账单表
            foreach ($list_debt as $value)
            {
                $item = array(
                    'module'    => $value['module'],
                    'order_no'  => $value['order_no'],
                    'pay_no'    => $value['ori_pay_no'],
                    'trade_type' => $this->get_financial_type($value['order_type']), //3-原款退款
                    'transfer_status' => 3, //3-已结清
                    'transfer_date' => date('Y-m-d',strtotime($value['up_time'])),
                    'inter_id' => $value['inter_id'],
                    'hotel_id' => $value['hotel_id'],
                    'trade_time' => $value['add_time'],
                    'add_time' => date('Y-m-d H:i:s'),
                    'amount' => $value['amount'],
                );

                //线下交易
                if ($item['trade_type'] == 7)
                {
                    $ext_info = json_decode($value['ext_info'],true);
                    $item['amount'] = !empty($ext_info['orig_amount']) ? $ext_info['orig_amount'] : 0;
                    $item['jfk_amount'] = !empty($ext_info['jfk_amount']) ? $ext_info['jfk_amount'] : 0;
                    $item['group_amount'] = !empty($ext_info['group_amount']) ? $ext_info['group_amount'] : 0;
                    $item['dist_amount'] = !empty($ext_info['dist_amount']) ? $ext_info['dist_amount'] : 0;
                }
                else if ($item['trade_type'] == 6)
                {
                    $item['module'] = 'base_pay';
                    $item['jfk_amount'] = $value['amount'];
                }
                else if (in_array($item['trade_type'],array(4,5)))
                {
                    $item['module'] = 'dist';
                    $item['dist_amount'] = $value['amount'];
                }

                $this->Iwidepay_financial_model->insert_order($item);
            }
        }
    }

    /**
     * 计划任务生成 财务对账单 => 每天分账订单
     */
    public function run_transfer_financial()
    {
        die('no allow');
        $this->load->model('iwidepay/Iwidepay_financial_model');
        $this->load->model('iwidepay/Iwidepay_order_model');
        $this->load->model('iwidepay/Iwidepay_transfer_model');

        //退款记录
        $stat_time = date('Y-m-d');//,strtotime('-1 days')
        $end_time = date('Y-m-d 23:59:60');//,strtotime('-1 days')
        $list_transfer = $this->Iwidepay_financial_model->transfer_order($stat_time,$end_time);

        if (!empty($list_transfer))
        {
            $temp = $order_types = array();
            //插入对账单表
            foreach ($list_transfer as $value)
            {
                $add_key = $value['module'] .'_'.$value['order_no'];
                $temp[$add_key]['module'] = $value['module'];
                $temp[$add_key]['amount'] = $value['orig_amount'];
                $temp[$add_key]['order_no_main'] = $value['order_no_main'];
                $temp[$add_key]['pay_no'] = $value['pay_no'];
                $temp[$add_key]['order_no'] = $value['order_no'];
                $temp[$add_key]['transfer_date'] = $value['transfer_date'];
                $temp[$add_key]['inter_id'] = $value['inter_id'];
                $temp[$add_key]['hotel_id'] = $value['hotel_id'];
                $temp[$add_key]['trade_time'] = $value['add_time'];
                $temp[$add_key]['write_off_hotel_id'] = $value['write_off_hotel_id'];
                $temp[$add_key]['add_time'] = date('Y-m-d H:i:s');

                //核销订单
                $status_key = $value['order_no'].'_'.$value['write_off_hotel_id'];
                $order_types[$status_key] = array(
                    'off_hotel_id' => $value['write_off_hotel_id'],
                    'order_no' => $value['order_no'],
                    'module' => $value['module'],
                );

                //分成金额
                $amount_key = $status_key;
                if ($value['module'] == 'soma' && $value['type'] == 'hotel')
                {
                    isset($tmp[$amount_key][$value['type']]) ? $tmp[$amount_key][$value['type']] += $value['amount'] : $tmp[$amount_key][$value['type']] = $value['amount'];
                }
                else
                {
                    $tmp[$amount_key][$value['type']] = $value['amount'];
                }
            }

            unset($list_transfer);
            $list_transfer = null;

            if (!empty($order_types))
            {
                foreach ($order_types as $key => $value)
                {
                    $add_key = $value['module'] .'_'.$value['order_no'];
                    $item = $temp[$add_key];
                    $item['transfer_status'] = 2;
                    //部分分账
                    if ($value['off_hotel_id'] == '9999999')
                    {
                        $item['transfer_status'] = 1;
                    }

                    $item['write_off_hotel_id'] = $value['off_hotel_id'];
                    $item['trade_type'] = 1;

                    $status_key = $value['order_no'].'_'.$value['off_hotel_id'];
                    $item['cost_amount'] = !empty($tmp[$status_key]['cost']) ? $tmp[$status_key]['cost'] : 0;
                    $item['jfk_amount'] = !empty($tmp[$status_key]['jfk']) ? $tmp[$status_key]['jfk'] : 0;
                    $item['group_amount'] = !empty($tmp[$status_key]['group']) ? $tmp[$status_key]['group'] : 0;
                    $item['hotel_amount'] = !empty($tmp[$status_key]['hotel']) ? $tmp[$status_key]['hotel'] : 0;
                    $item['dist_amount'] = !empty($tmp[$status_key]['dist']) ? $tmp[$status_key]['dist'] : 0;

                    $this->Iwidepay_financial_model->insert_order($item);
                }
            }
        }
    }

    /**
     * 修复 对账单记录
     */
    public function run_settlement_record_id()
    {
        die('no allow');
        $sql = "SELECT * FROM iwide_iwidepay_sum_record WHERE handle_date = '20170910' AND status = 10";
        $data = $this->db->query($sql)->result_array();
        $num = 0;
        if (!empty($data))
        {
            foreach ($data as $value)
            {
                $where_arr = array(
                    'status' => 10,
                    'handle_date' => '20170909',
                    'bank_card_no' => trim($value['bank_card_no']),
                );

                $this->db->where($where_arr);

                $this->db->update('iwidepay_settlement',array('record_id' => $value['id']));
                $row = $this->db->affected_rows();
                if ($row > 0)
                {
                    $num = $num + $row;
                }
            }
        }

        echo '今天总行号：'.count($data).',更改行数：'.$num;

    }


    /**
     * 或者对账单类型
     * @param $order_type
     * @return int
     */
    protected function get_financial_type($order_type)
    {
        switch($order_type)
        {
            case 'order':
                $trade_type = 7;
                break;
            case 'base_pay':
                $trade_type = 6;
                break;
            case 'refund':
                $trade_type = 2;
                break;
            case 'orderReward':
                $trade_type = 4;
                break;
            case 'extraReward':
                $trade_type = 5;
                break;
            default :
                $trade_type = 0;
                break;
        }

        return $trade_type;
    }

}
