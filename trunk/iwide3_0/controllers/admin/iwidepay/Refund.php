<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Transaction
 * 退款记录管理
 * 沙沙
 * 2017-06-27
 */

class Refund extends MY_Admin
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
        $filter['start_time'] = !empty($param['start_time']) ? addslashes($param['start_time']) : '';
        $filter['end_time'] = !empty($param['end_time']) ? addslashes($param['end_time']) : '';
        $filter['orig_order_no'] = !empty($param['order_no']) ? addslashes($param['order_no']) : '';

        //集团账号
        $filter['inter_id'] = $this->admin_profile['inter_id'];
        $filter['hotel_id'] = $this->admin_profile['entity_id'];

        $this->load->model('iwidepay/iwidepay_refund_model' );
        $select = 'R.inter_id,R.hotel_id,R.module,R.hotel_id,R.amount,R.orig_order_no,R.refund_amt,R.add_time,R.charge,R.type,R.refund_status';
        $list = $this->iwidepay_refund_model->get_refund($select,$filter,'','');

        $module = array('hotel'=>'订房','soma'=>'商城','vip'=>'会员','okpay'=>'快乐付','dc'=>'在线点餐');
        $refund_status = array('0'=>'--','1'=>'成功','2'=>'失败','3'=>'异常');
        $type = array('0'=>'--','1'=>'原路退回','2'=>'已结清全额退款','3'=>'部分原路退回','4'=>'已结清部分退款');

        if ($list)
        {
            foreach ($list as $key => $value)
            {
                $item = array();
                $item['add_time'] = $value['add_time'];
                $item['name'] = !empty($value['name']) ? $value['name'] : '';
                $item['hotel_name'] = !empty($value['hotel_name']) ? $value['hotel_name'] : '';
                $item['orig_order_no'] = $value['orig_order_no'];
                $item['module'] = $module[$value['module']];
                $item['amount'] = formatMoney($value['amount']/100);
                $item['refund_amt'] = formatMoney($value['refund_amt']/100);
                $item['type'] = $type[$value['type']];
                $item['refund_status'] = $refund_status[$value['refund_status']];
                $list[$key] = $item;
            }
        }


        $headArr = array('交易时间','所属公众号','所属门店','订单号','退款模块','交易金额','退款金额','退款方式','退款状态');
        $widthArr = array(20,20,20,20,20,20,12,12,12);
        getExcel('退款记录',$headArr,$list,$widthArr);
    }
}