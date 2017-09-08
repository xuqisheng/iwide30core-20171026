<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class SplitRule
 * 分账规则
 * 沙沙
 * 2017-06-27
 */
class SplitRule extends MY_Admin
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
     * 分账规则列表
     */
    public function rule_list()
    {
        $param = request();
        $return = array(
            'param'      => $param,
        );

        echo $this->_render_content($this->_load_view_file('rule_list'), $return, TRUE);
    }

    /**
     * 添加
     */
    public function add()
    {
        $param = request();
        $return = array(
            'param'      => $param,
        );

        echo $this->_render_content($this->_load_view_file('add'), $return, TRUE);
    }

    /**
     * 编辑
     */
    public function edit()
    {
        $param = request();
        $return = array(
            'param'      => $param,
        );

        echo $this->_render_content($this->_load_view_file('edit'), $return, TRUE);
    }

    /**
     * 导出规则
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
            die('请求参数错误');
        }

        if (empty($filter['inter_id']))
        {
            $filter['inter_id'] = $this->admin_profile['inter_id'];
        }
        if (empty($filter['hotel_id']))
        {
            $filter['hotel_id'] = $this->admin_profile['entity_id'];
        }

        $this->load->model('iwidepay/iwidepay_rule_model' );
        $status = array(1 => '正常',2 => '无效');
        $module = array('hotel'=>'订房','soma'=>'商城','vip'=>'会员','okpay'=>'快乐付','dc'=>'在线点餐','ticket' => '预约核销','base_pay' => '基础月费');
        $select = 'mi.module,mi.rule_name,mi.edit_time,mi.status,mi.regular_jfk_cost,mi.regular_jfk,mi.regular_group,mi.regular_hotel,mi.regular_base';
        $rules = $this->iwidepay_rule_model->get_hotel_rule($select,$filter,'','');
        if (!empty($rules))
        {
            foreach ($rules as $key => $rule)
            {
                $item = array();
                $item['edit_time'] = $rule['edit_time'];
                $item['name'] = !empty($rule['name']) ? $rule['name'] : '';
                $item['hotel_name'] = !empty($rule['hotel_name']) ? $rule['hotel_name'] : '所有门店';
                $item['module'] = $module[$rule['module']];

                if ($rule['module'] == 'base_pay')
                {
                    $item['regular_jfk_cost'] = '--';
                    $item['regular_jfk'] = '--';
                    $item['regular_group'] = '--';
                    $item['regular_hotel'] = '--';
                    $item['regular_base'] = $this->handle_rule($rule['regular_base']);
                }
                else
                {
                    $item['regular_jfk_cost'] = $this->handle_rule($rule['regular_jfk_cost']);
                    $item['regular_jfk'] = $this->handle_rule($rule['regular_jfk']);
                    $item['regular_group'] = $rule['regular_group'] == '-1' ? '剩余金额' : $this->handle_rule($rule['regular_group']);
                    $item['regular_hotel'] = $rule['regular_hotel'] == '-1' ? '剩余金额' : $this->handle_rule($rule['regular_hotel']);
                    $item['regular_base'] = '--';
                }


                $item['status'] = $status[$rule['status']];
                $rules[$key] = $item;
            }
        }

        $headArr = array('修改时间','公众号名称','所属门店','生效模块','金房卡手续费','金房卡分成','集团分成','门店分成','基础月费','状态');
        $widthArr = array(20,25,25,15,15,15,15,15,12);
        getExcel('门店规则',$headArr,$rules,$widthArr);
    }


    /**
     * 导出 公众号规则配置
     */
    public function ext_rule()
    {
        $param = request();
        $filter['inter_id'] = !empty($param['inter_id']) ? addslashes($param['inter_id']) : '';

        if (empty($filter['inter_id']))
        {
            $filter['inter_id'] = $this->admin_profile['inter_id'];
        }

        //获取数据
        $this->load->model('iwidepay/iwidepay_merchant_model' );
        $this->load->model('iwidepay/iwidepay_rule_model' );
        $select = 'mi.inter_id,mi.created_at';
        $split_status = array('0'=>'停用','1'=>'启用');
        $list = $this->iwidepay_merchant_model->get_inter_bank($select,$filter,'','');
        if (!empty($list))
        {
            foreach ($list as $key => $value)
            {
                //统计规格数
                $value['rule_number'] = $this->iwidepay_rule_model->count_inter_num($value);
                if ($value['rule_number'] > 0)
                {
                    $rule = $this->iwidepay_rule_model->get_rule('edit_time',array('inter_id' => $value['inter_id']),'edit_time desc');
                    $value['created_at'] = !empty($rule) ? $rule['edit_time'] : $value['created_at'];
                }

                $value['name'] = !empty($value['name']) ? $value['name'] : '';
                $value['split_status'] = isset($value['split_status']) ? $split_status[$value['split_status']] : '';

                $item['created_at'] = $value['created_at'];
                $item['name'] = $value['name'];
                $item['rule_number'] = $value['rule_number'];
                $item['split_status'] = $value['split_status'];

                $list[$key] = $item;
            }
        }

        $headArr = array('修改时间','公众号名称','规则条数','规则状态');
        $widthArr = array(20,30,15,15);
        getExcel('公众号规则管理',$headArr,$list,$widthArr);
    }

    /**
     * 处理规则设置
     * @param string $rule
     * @return array
     */
    private function handle_rule($rule = '')
    {
        $temp = explode('%',$rule);
        if (!isset($temp[1]))
        {
            $rule = $rule/100;
        }

        return $rule;
    }
}