<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Customer_address_model
 *
 *
 */
class Customer_address_model extends MY_Model_Soma
{
    /**
     * 配送信息（数组）
     * @var Gift_order_attr_customer
     */
    public $shipping_info = array();

    const STATUS_ACTIVE = 1;
    const STATUS_HIDDEN = 2;

    public function get_status_label()
    {
        return array(
            self::STATUS_ACTIVE => '正常',
            self::STATUS_HIDDEN => '隐藏',
        );
    }

    public function get_resource_name()
    {
        return '用户地址';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function table_name($inter_id = null)
    {
        return $this->_shard_table('soma_customer_address', $inter_id);
    }

    public function table_primary_key()
    {
        return 'address_id';
    }

    public function get_field_mapping()
    {
        return array(
            'address_id' => 'address_id',
            'openid'     => 'openid',
            'inter_id'   => 'inter_id',
            'hotel_id'   => 'hotel_id',
            'country'    => 'country',
            'province'   => 'province',
            'city'       => 'city',
            'region'     => 'region',
            'address'    => 'address',
            'zip_code'   => 'zip_code',
            'phone'      => 'phone',
            'contact'    => 'contact',
            'status'     => 'status',
        );
    }

    public function attribute_labels()
    {
        return array(
            'address_id' => 'ID',
            'openid'     => 'Openid',
            'inter_id'   => '公众号',
            'hotel_id'   => '酒店ID',
            'country'    => '国家',
            'province'   => '省份',
            'city'       => '城市',
            'region'     => '区',
            'address'    => '街道',
            'zip_code'   => '邮编',
            'phone'      => '电话',
            'contact'    => '联系人',
            'status'     => '状态',
        );
    }

    /**
     * 后台管理的表格中要显示哪些字段
     */
    public function grid_fields()
    {
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
        return array(
            'address_id',
            'province',
            'city',
            'region',
            'address',
            'phone',
            'contact',
            'status',
        );
    }

    /**
     * 在EasyUI grid中的 date-option 定义，包括宽度，是否排序等等
     *   type: grid中的表头类型定义
     *   form_type: form中的元素类型定义
     *   form_ui: form中的属性补充定义，如加disabled 在< input “disabled” / > 使元素禁用
     *   form_tips: form中的label信息提示
     *   form_hide: form中自动化输出中剔除
     *   form_default: form中的默认值，请用字符类型，不要用数字
     *   select: form中的类型为 combobox时，定义其下来列表
     */
    public function attribute_ui()
    {
        /* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
        //type: numberbox数字框|combobox下拉框|text不写时默认|datebox
        $base_util = EA_base::inst();
        $modules = config_item('admin_panels') ? config_item('admin_panels') : array();

        return array(
            'address_id' => array(
                'grid_ui'    => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'       => 'text',    //textarea|text|combobox|number|email|url|price
            ),
            'openid'     => array(
                'grid_ui'    => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'       => 'text',    //textarea|text|combobox|number|email|url|price
            ),
            'hotel_id'   => array(
                'grid_ui'    => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'       => 'text',    //textarea|text|combobox|number|email|url|price
            ),
            'inter_id'   => array(
                'grid_ui'    => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'       => 'text',    //textarea|text|combobox|number|email|url|price
            ),
            'country'    => array(
                'grid_ui'    => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'       => 'text',    //textarea|text|combobox|number|email|url|price
            ),
            'province'   => array(
                'grid_ui'    => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'       => 'text',    //textarea|text|combobox|number|email|url|price
            ),
            'city'       => array(
                'grid_ui'    => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'       => 'text',    //textarea|text|combobox|number|email|url|price
            ),
            'region'     => array(
                'grid_ui'    => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'       => 'text',    //textarea|text|combobox|number|email|url|price
            ),
            'address'    => array(
                'grid_ui'    => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'       => 'text',    //textarea|text|combobox|number|email|url|price
            ),
            'zip_code'   => array(
                'grid_ui'    => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'       => 'text',    //textarea|text|combobox|number|email|url|price
            ),
            'phone'      => array(
                'grid_ui'    => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'       => 'text',    //textarea|text|combobox|number|email|url|price
            ),
            'contact'    => array(
                'grid_ui'    => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'       => 'text',    //textarea|text|combobox|number|email|url|price
            ),
            'status'     => array(
                'grid_ui'    => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'       => 'text',    //textarea|text|combobox|number|email|url|price
            ),
        );
    }

    /**
     * grid表格中默认哪个字段排序，排序方向
     */
    public static function default_sort_field()
    {
        return array('field' => 'address_id', 'sort' => 'desc');
    }

    /* 以上为AdminLTE 后台UI输出配置函数 */


    /**
     * 获取插入主键
     * @return string
     */
    public function get_increase_id()
    {
        $this->load->model('soma/ticket_center_model');
        return $this->ticket_center_model->get_increment_id_address('default');
    }

    /**
     * 根据id获取指定用户地址
     * @param $openid
     * @param $interId
     * @param $address_id
     * @return bool
     * @author zhangyi  <zhangyi@mofly.cn>
     */
    public function get_address_by_id($openid,$interId,$address_id){
        if (!$openid || !$interId) {
            return false;
        }

        $db = $this->_shard_db_r('iwide_soma_r');
        return $db->where(
            array(
                'openid'    => $openid,
                'inter_id'  => $interId,
                $this->table_primary_key() => $address_id
            )
        )->get($this->table_name())->row_array();

    }

    /**
     * 拉取用户地址信息列表
     */
    public function get_addresses($openid, $filter = array(), $limit = 0)
    {
        if (!$openid) {
            return false;
        }

        $db = $this->_shard_db_r('iwide_soma_r');

        //limit＝0取出全部
        if ($limit) {
            $startNum = 0;//从哪条开始取
            $limitNum = $limit + 0;//取多少条
            $db->limit($limitNum, $startNum);
        }

        $filter['openid'] = $openid;
        $table_name = $this->table_name();

        return $db->where($filter)->get($table_name)->result_array();
    }

    /**
     *
     * @param $interID
     * @param $openid
     * @param int $limit
     * @return array|string
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function get_list($interID, $openid, $limit = 1)
    {
        return $this->get(
            [
                'status',
                'inter_id',
                'openid'
            ],
            [
                self::STATUS_ACTIVE,
                $interID,
                $openid
            ],
            '*',
            [
                'limit' => $limit
            ]
        );
    }

    /**
     * 保存\更新用户地址信息
     *
     * @param $data
     * @param null $addressId
     * @return bool|null
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function save_address($data, $addressId = null)
    {
        if (empty($data)) {
            return false;
        }

        if (empty($addressId)) {

            $this->load->model('soma/ticket_center_model');
            $addressId = $this->ticket_center_model->get_increment_id_address('default');
            $pk = $this->table_primary_key();
            $data[$pk] = $addressId;

            $result = $this->_m_save($data);
        } else {
            //更新
            $addressId = $addressId + 0;
            $result = $this->load($addressId)->m_sets($data)->m_save();
        }

        if ($result) {
            return $addressId;
        } else {
            return false;
        }
    }

}
