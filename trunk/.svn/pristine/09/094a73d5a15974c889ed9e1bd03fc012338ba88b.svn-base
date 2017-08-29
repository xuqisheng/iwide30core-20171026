<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Capital
 * 资金概览
 * 沙沙
 * 2017-06-27
 */

class Capital extends MY_Admin
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

        $inter_id = $this->admin_profile['inter_id'];

        //获取数据
        $this->load->model('iwidepay/Iwidepay_capital_model' );
        $this->load->model('iwidepay/iwidepay_merchant_model' );
        $where_arr = array(
            'inter_id' => $filter['inter_id'],
            'hotel_id' => $filter['hotel_id'],
        );
        if (empty($filter['inter_id']))
        {
            $where_arr['inter_id'] = $inter_id;
        }

        if (empty($filter['hotel_id']))
        {
            $where_arr['hotel_id'] = $this->admin_profile['entity_id'];
        }

        //查询账户信息
        $select = 'mi.inter_id,mi.hotel_id,mi.type,mi.type';
        $list = $this->iwidepay_merchant_model->get_band_accounts($select,$where_arr,'','');
        if ($list)
        {
            foreach ($list as $key => $value)
            {
                if ($value['type'] == 'jfk')
                {
                    $value['name'] = $value['hotel_name'] = $value['account_aliases'] = '金房卡分成';
                }
                else if($value['type'] == 'group')
                {
                    $value['hotel_name'] = '集团';
                }

                $item['name'] = !empty($value['name']) ? $value['name'] : '';
                $item['hotel_name'] = !empty($value['hotel_name']) ? $value['hotel_name'] : '';

                //查询统计金额
                $value_arr = array(
                    'inter_id' => $value['inter_id'],
                    'hotel_id' => $value['hotel_id'],
                    'start_time' => $filter['start_time'],
                    'end_time' => $filter['end_time'],
                );

                $total_amount = $this->Iwidepay_capital_model->total_amount($value_arr);
                $pay_amount = $this->Iwidepay_capital_model->pay_amount($value_arr);
                $refund_amount = $this->Iwidepay_capital_model->refund_amount($value_arr);
                $withdraw_amount = $this->Iwidepay_capital_model->withdraw_amount($value_arr);
                $commission = $this->Iwidepay_capital_model->commission($value_arr);
                $distribution = $this->Iwidepay_capital_model->distribution($value_arr);
                $arrears_amount = $this->Iwidepay_capital_model->arrears_amount($value_arr);

                $item['total_amount'] = formatMoney($total_amount/100);
                $item['pay_amount'] = formatMoney($pay_amount/100);
                $item['refund_amount'] = formatMoney($refund_amount/100);
                $item['withdraw_amount'] = formatMoney($withdraw_amount/100);
                $item['commission'] = formatMoney($commission/100);
                $item['distribution'] = formatMoney($distribution/100);
                $item['arrears_amount'] = formatMoney($arrears_amount/100);
                $list[$key] = $item;
            }
        }


        $headArr = array('所属公众号','所属门店','监管账户余额','用户支付金额','退款金额','提现金额','金房卡佣金','分销佣金','欠款金额');
        $widthArr = array(20,20,20,20,20,20,12,12,12);
        getExcel('资金概览',$headArr,$list,$widthArr);
    }
}