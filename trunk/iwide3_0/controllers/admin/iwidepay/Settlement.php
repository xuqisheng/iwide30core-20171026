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


        $this->load->model('iwidepay/iwidepay_sum_record_model' );
        $select = 'sr.id,sr.amount,sr.status,sr.is_company,sr.bank,sr.bank_card_no,sr.add_time,sr.update_time,sr.type';

        $status = array(0=>'待转账',1=>'成功',2=>'失败',3=>'处理中',10=>'放弃转账');
        $list = $this->iwidepay_sum_record_model->get_settlement($select,$filter,$cur_page,$per_page);
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
    public function ext_financial()
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

        //已结清全额退款
        /*
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

         */

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
        $filter['hotel_id'] = $this->hotel_id;

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
        $widthArr = array(20,20,20,12,20,12,12);
        getExcel('转账审核',$headArr,$list,$widthArr);
    }
}