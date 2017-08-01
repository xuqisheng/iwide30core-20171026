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
        $select = 'sr.id,sr.amount,sr.status,sr.is_company,sr.bank,sr.bank_card_no,sr.add_time,sr.update_time';

        $status = array(0=>'--',1=>'成功',2=>'失败',3=>'异常');
        $list = $this->iwidepay_sum_record_model->get_sum_record($select,$filter,$cur_page,$per_page);
        if ($list)
        {
            foreach ($list as $key => $value)
            {
                $item = array();
                $item['add_time'] = $value['add_time'];

                if ($value['type'] == 'jfk')
                {
                    $value['name'] = $value['hotel_name'] = '金房卡';
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
}