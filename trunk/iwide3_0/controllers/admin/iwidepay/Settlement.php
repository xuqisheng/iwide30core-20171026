<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Settlement
 * 结算记录管理
 * 沙沙
 * 2017-06-27
 */

class Settlement extends MY_Admin
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
     * 转账 管理
     */
    public function transfer_accounts()
    {
        $param = request();

        echo $this->_render_content($this->_load_view_file('transfer_accounts'), $param, TRUE);
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

        if (empty($filter['inter_id']))
        {
             $filter['inter_id'] = $this->admin_profile['inter_id'];
        }
        if (empty($filter['hotel_id']))
        {
            $filter['hotel_id'] = $this->admin_profile['entity_id'];
        }


        $this->load->model('iwidepay/iwidepay_sum_record_model' );
        $select = 'sr.id,sr.amount,sr.status,sr.is_company,sr.bank,sr.bank_card_no,sr.add_time,sr.update_time,sr.type';

        $status = array(0=>'待转账',1=>'成功',2=>'失败',3=>'处理中',10=>'放弃转账');
        $list = $this->iwidepay_sum_record_model->get_settlement($select,$filter,'','');
        if ($list)
        {
            foreach ($list as $key => $value)
            {
                $item = array();
                $item['add_time'] = $value['add_time'];

                if ($value['type'] == 'jfk')
                {
                    $value['name'] = $value['hotel_name'] = '金房卡分成';
                }
                else if($value['type'] == 'group')
                {
                    $value['hotel_name'] = '集团';
                }
                $item['name'] = !empty($value['name']) ? $value['name'] : '';
                $item['hotel_name'] = !empty($value['hotel_name']) ? $value['hotel_name'] : '';

                $item['amount'] = formatMoney($value['amount']/100);
                $item['status'] = $status[$value['status']];
                $item['update_time'] = $value['update_time'];
                $list[$key] = $item;
            }
        }

        $headArr = array('转账时间','所属公众号','所属门店','转账金额','转账状态','返回状态时间');
        $widthArr = array(20,20,20,20,12,20);
        getExcel('结算记录',$headArr,$list,$widthArr);

    }

    /**
     * 导出当前记录对账单 【时间，公众号，酒店】
     */
    public function ext_financial_old()
    {
        $param = request();
        $filter['record_id'] = !empty($param['record_id']) ? intval($param['record_id']) : '';
        $transfer_status = isset($param['status']) ? intval($param['status']) : '';

        //$filter['transfer_status'] = 3;//已分账
        $this->load->model('iwidepay/iwidepay_order_model' );
        $this->load->model('iwidepay/iwidepay_refund_model' );
        $this->load->model('iwidepay/iwidepay_transfer_model' );
        $select = 'o.id,o.inter_id,o.hotel_id,o.module,o.order_no,o.pay_no,o.order_status,o.transfer_status,o.trans_amt,o.is_dist,o.pay_time';
        $status = array(0=>'--',1=>'待定',2=>'待分',3=>'已分',4=>'异常',5=>'待定未分完',6=>'退款',7=>'已结清全额退款',8=>'部分退款',9=>'已结清部分退款');
        $module = array('hotel'=>'订房','soma'=>'商城','vip'=>'会员','okpay'=>'快乐付','dc'=>'在线点餐','ticket' => '预约核销','base_pay' => '基础月费');
        $list = $this->iwidepay_order_model->get_financial_orders($select,$filter,'','');

        //已分账
        $sort_time = array();
        if ($list)
        {
            foreach ($list as $key => $value)
            {
                $order_no[] = $value['order_no'];
            }
            $transfer = $this->iwidepay_transfer_model->get_transfer('order_no,type,amount,add_time,module,name',$order_no,$transfer_status);

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



        $headArr = array('交易时间','所属公众号','所属门店','来源模块','平台订单号','支付订单号','交易类型','分账状态','分账时间','交易/退款金额(元)','核销门店','交易手续费','金房卡分成','集团分成','门店分成','分销员分成');
        $widthArr = array(20,20,20,12,20,20,12,12,12,12,12,12,12,14);
        getExcel('转账对账表',$headArr,$list,$widthArr);
    }

    /**
     * 导出当前数据
     *
     */
    public function ext_report()
    {
        $param = request();
        $filter['status'] = isset($param['status']) ? addslashes($param['status']) : '';
        $filter['start_time'] = !empty($param['start_time']) ? addslashes($param['start_time']) : '';
        $filter['end_time'] = !empty($param['end_time']) ? addslashes($param['end_time']) : '';

        $filter['inter_id'] = $this->admin_profile['inter_id'];

        //集团账号
        $filter['hotel_id'] = $this->admin_profile['entity_id'];

        if ($filter['status'] === '' || $filter['status'] < 0)
        {
            unset($filter['status']);
        }
        else if ($filter['status'] == 99)
        {
            $param['status'] = $filter['status'] = 0;
        }

        $select = 'sr.id,sr.amount,sr.status,sr.is_company,sr.bank,sr.bank_card_no,sr.add_time,sr.update_time,sr.remark,sr.bank_card_no,sr.bank_user_name';
        $this->load->model('iwidepay/iwidepay_sum_record_model' );
        $status = array(0=>'待转账',1=>'成功',2=>'失败',3=>'处理中',10 => '放弃转账');
        $list = $this->iwidepay_sum_record_model->get_sum_record($select,$filter,'','');
        if ($list)
        {
            foreach ($list as $key => $value)
            {
                $item = array();
                $item['add_time'] = $value['add_time'];

                if ($value['type'] == 'jfk')
                {
                    $value['name'] = $value['hotel_name'] = '金房卡分成';
                }
                else if($value['type'] == 'group')
                {
                    $value['hotel_name'] = '集团';
                }
                //$item['name'] = !empty($value['name']) ? $value['name'] : '';
                //$item['hotel_name'] = !empty($value['hotel_name']) ? $value['hotel_name'] : '';
                $item['bank_user_name'] = $value['bank_user_name'];
                $item['bank_card_no'] = $value['bank_card_no'];
                $item['amount'] = formatMoney($value['amount']/100);
                $item['update_time'] = $value['update_time'];
                $item['status'] = $status[$value['status']];
                $item['remark'] = $value['remark'];
                $list[$key] = $item;
            }
        }


        $headArr = array('转账时间','账户名','账号','转账金额','返回状态时间','转账状态','备注');
        $widthArr = array(20,30,25,15,20,12,25);
        getExcel('转账审核',$headArr,$list,$widthArr);
    }


    /**
     * 新财务对账单
     * 2017-08-29
     */
    public function ext_financial()
    {
        $param = request();
        $record_id = !empty($param['record_id']) ? intval($param['record_id']) : '';
        if (empty($record_id))
        {
            echo '暂无数据';
            exit();
        }

        $this->load->model('iwidepay/iwidepay_order_model' );
        $this->load->model('iwidepay/iwidepay_refund_model' );
        $this->load->model('iwidepay/iwidepay_transfer_model' );
        $this->load->model('iwidepay/Iwidepay_financial_model' );
        $module = array('hotel'=>'订房','soma'=>'商城','vip'=>'会员','okpay'=>'快乐付','dc'=>'在线点餐','ticket' => '预约核销','base_pay' => '基础月费','dist' => '分销','balance' => '结余');

        # 一、交易对账单
        $list_transfer = $this->Iwidepay_financial_model->transfer_accounts_order($record_id);

        $list = array();
        if (!empty($list_transfer))
        {
            $temp = $order_types = array();
            foreach ($list_transfer as $value)
            {
                $add_key = $value['module'] .'_'.$value['order_no'];
                $temp[$add_key]['module'] = $value['module'];
                $temp[$add_key]['amount'] = $value['orig_amount'];
                $temp[$add_key]['order_no_main'] = $value['order_no_main'];
                $temp[$add_key]['pay_no'] = $value['pay_no'];
                $temp[$add_key]['order_no'] = $value['order_no'];
                $temp[$add_key]['transfer_date'] = date('Y-m-d',strtotime($value['transfer_date']));
                $temp[$add_key]['inter_id'] = $value['inter_id'];
                $temp[$add_key]['hotel_id'] = $value['hotel_id'];
                $temp[$add_key]['trade_time'] = $value['add_time'];
                $temp[$add_key]['write_off_hotel_id'] = $value['write_off_hotel_id'];
                $temp[$add_key]['name'] = $value['name'];
                $temp[$add_key]['hotel_name'] = $value['hotel_name'];

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
                    $item = array();
                    $add_key = $value['module'] .'_'.$value['order_no'];
                    $item['trade_time'] = $temp[$add_key]['trade_time'];
                    $item['name'] = $temp[$add_key]['name'];
                    $item['hotel_name'] = !empty($temp[$add_key]['hotel_name']) ? $temp[$add_key]['hotel_name'] : '--';
                    $item['module'] = !empty($module[$temp[$add_key]['module']]) ? $module[$temp[$add_key]['module']] : '--';
                    $item['order_no'] = $temp[$add_key]['order_no'];
                    $item['pay_no'] = $temp[$add_key]['pay_no'];

                    $item['order_status'] = '交易';
                    $item['transfer_status'] = '已分账';
                    //部分分账
                    if ($value['off_hotel_id'] == '9999999')
                    {
                        $item['transfer_status'] = '部分分账';
                    }

                    $item['transfer_date'] = $temp[$add_key]['transfer_date'];
                    $item['amount'] = formatMoney($temp[$add_key]['amount']/100);

                    //核销门店
                    $item['write_off_hotel_id'] = '';
                    if ($value['module'] == 'soma' && $temp[$add_key]['hotel_id'] == '9999999' && $value['off_hotel_id'] != '9999999')
                    {
                        $hotel = $this->Iwidepay_financial_model->get_hotel_info($temp[$add_key]['inter_id'],$value['off_hotel_id']);
                        $item['write_off_hotel_id'] = !empty($hotel['name']) ? $hotel['name'] : '--';
                    }

                    $status_key = $value['order_no'].'_'.$value['off_hotel_id'];
                    $item['cost_amount'] = !empty($tmp[$status_key]['cost']) ? formatMoney($tmp[$status_key]['cost']/100) : '--';
                    $item['jfk_amount'] = !empty($tmp[$status_key]['jfk']) ? formatMoney($tmp[$status_key]['jfk']/100) : '--';
                    $item['group_amount'] = !empty($tmp[$status_key]['group']) ? formatMoney($tmp[$status_key]['group']/100) : '--';
                    $item['hotel_amount'] = !empty($tmp[$status_key]['hotel']) ? formatMoney($tmp[$status_key]['hotel']/100) : '--';
                    $item['dist_amount'] = !empty($tmp[$status_key]['dist']) ? formatMoney($tmp[$status_key]['dist']/100) : '--';

                    $list[] = $item;
                }
            }
        }

        $tmp = $order_types = $temp = null;

        # 二、欠款记录
        # 1、通过 $record_id => settlement 表

        $settlement = $this->Iwidepay_financial_model->sum_settlement($record_id);
        if (!empty($settlement))
        {
            $add = array();
            foreach ($settlement as $item_set)
            {
                # 2、通过 set_id 查询订单

                if ($item_set['type'] == 'hotel')
                {
                    $where_sql = ' DR.set_id = '.$item_set['id'];
                }
                else if ($item_set['type'] == 'jfk')
                {
                    $where_sql = ' DR.jfk_id = '.$item_set['id'];
                }
                else if ($item_set['type'] == 'group')
                {
                    $where_sql = ' DR.group_id = '.$item_set['id'];
                }

                $list_debt = $this->Iwidepay_financial_model->transfer_accounts_debt_order($where_sql);

                if (!empty($list_debt))
                {
                    foreach ($list_debt as $value)
                    {
                        //解决门店集团同一账户，订单重复的问题
                        if(in_array($value['id'],$add))
                        {
                            continue;
                        }
                        $add[] = $value['id'];

                        $item = array();
                        $item['trade_time'] = $value['add_time'];
                        $item['name'] = !empty($value['name']) ? $value['name'] : '--';
                        $item['hotel_name'] = !empty($value['hotel_name']) ? $value['hotel_name'] : '--';

                        $item['module'] = !empty($module[$value['module']]) ? $module[$value['module']] : '--';
                        //来源模块
                        $order_type = '交易';
                        $jfk_amount = 0;
                        $group_amount = 0;
                        $dist_amount = 0;
                        $hotel_amount = $value['amount'];
                        if ($value['order_type'] == 'base_pay')
                        {
                            $item['module'] = '月费';
                            $order_type = '基础月费';
                            $jfk_amount = $hotel_amount;
                        }
                        else if ($value['order_type'] == 'extra_dist')
                        {
                            $item['module'] = '分销';
                            $order_type = '分销奖励';
                            $dist_amount = $hotel_amount;
                        }
                        else if ($value['order_type'] == 'refund')
                        {
                            $order_type = '垫付退款';
                            //$jfk_amount = $hotel_amount;
                        }
                        else if ($value['order_type'] == 'order')
                        {
                            $ext_info = json_decode($value['ext_info'],true);
                            $value['amount'] = !empty($ext_info['orig_amount']) ? $ext_info['orig_amount'] : 0;
                            $jfk_amount = !empty($ext_info['jfk_amount']) ? $ext_info['jfk_amount'] : 0;
                            $group_amount = !empty($ext_info['group_amount']) ? $ext_info['group_amount'] : 0;
                            $dist_amount = !empty($ext_info['dist_amount']) ? $ext_info['dist_amount'] : 0;

                            $hotel_amount = $jfk_amount + $group_amount + $dist_amount;
                        }

                        $item['order_no'] = !empty($value['order_no']) ? $value['order_no'] : '--';
                        $item['pay_no'] = !empty($value['ori_pay_no']) ? $value['ori_pay_no'] : '--';

                        $item['order_status'] = $order_type;
                        $item['transfer_status'] = '已结清';
                        $item['transfer_date'] = date('Y-m-d',strtotime($value['up_time']));

                        $item['amount'] = formatMoney($value['amount']/100);
                        $item['write_off_hotel_id'] = '';//核销门店

                        $item['cost_amount'] = '--';
                        $item['jfk_amount'] = !empty($jfk_amount) ? formatMoney($jfk_amount/100) : '--';
                        $item['group_amount'] = !empty($group_amount) ? formatMoney($group_amount/100) : '--';

                        $item['hotel_amount'] = '-'.formatMoney($hotel_amount/100);

                        $item['dist_amount'] = !empty($dist_amount) ? formatMoney($dist_amount/100) : '--';

                        $list[] = $item;
                    }
                }

                //结余记录
                if (!empty($item_set['hotel_id']) && $item_set['hotel_id'] > 0)
                {
                    $balance_order = $this->Iwidepay_financial_model->balance_order($item_set['inter_id'],$item_set['hotel_id'],$item_set['id']);
                    if (!empty($balance_order))
                    {
                        $item = array();
                        $item['trade_time'] = $balance_order['add_time'];
                        $item['name'] = !empty($balance_order['name']) ? $balance_order['name'] : '--';
                        $item['hotel_name'] = !empty($balance_order['hotel_name']) ? $balance_order['hotel_name'] : '--';

                        $item['module'] = !empty($module[$balance_order['module']]) ? $module[$balance_order['module']] : '--';
                        //来源模块
                        $order_type = '结余';
                        $hotel_amount = $balance_order['amount'];

                        $item['order_no'] = !empty($balance_order['order_no']) ? $balance_order['order_no'] : '--';
                        $item['pay_no'] = !empty($balance_order['ori_pay_no']) ? $balance_order['ori_pay_no'] : '--';

                        $item['order_status'] = $order_type;
                        $item['transfer_status'] = '未结清';
                        $item['transfer_date'] = date('Y-m-d',strtotime($balance_order['up_time']));

                        $item['amount'] = formatMoney($balance_order['amount']/100);
                        $item['write_off_hotel_id'] = '';//核销门店

                        $item['cost_amount'] = '--';
                        $item['jfk_amount'] = '--';
                        $item['group_amount'] = '--';

                        $item['hotel_amount'] = '-'.formatMoney($hotel_amount/100);

                        $item['dist_amount'] = '--';

                        $list[] = $item;
                    }
                }

            }
        }

        $headArr = array('交易时间','所属公众号','所属门店','来源模块','平台订单号','支付订单号','交易类型','分账状态','分账时间','交易/退款金额(元)','核销门店','交易手续费','金房卡分成','集团分成','门店分成','分销员分成');
        $widthArr = array(20,20,20,12,25,25,12,12,12,18,12,12,12,12,12,15);
        getExcel('转账对账表',$headArr,$list,$widthArr);
    }

}