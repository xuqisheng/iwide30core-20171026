<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Product_package_model
 *
 * @property Product_specification_model $somaProductSpecificationModel
 * @property Product_specification_setting_model $somaProductSpecificationSettingModel
 */
class Product_package_model extends MY_Model_Soma
{

    const CAN_T = 1;
    const CAN_F = 2;

    const IS = 1;
    const IS_NOT = 2;

    const STOCK_ADD = 1;//增加库存
    const STOCK_MINUS = 2;//扣减库存

    const STATUS_ACTIVE = 1;
    const STATUS_UNACTIVE = 3;
    const STATUS_HIDDEN = 2;

    const DATE_TYPE_STATIC = 1;
    const DATE_TYPE_FLOAT = 2;

    // 商品类型定义
    const SPEC_TYPE_SCOPE   = 1;//规格通用
    const SPEC_TYPE_TICKET  = 2;//门票
    const SPEC_TYPE_COMBINE = 3;//组合商品
    const SPEC_TYPE_ROOM    = 4;//升级房券

    // 微信订房
    const CAN_WX_BOOKING = 1;
    const CAN_NOT_WX_BOOKING = 2;
    const CAN_PASS_TO_HOTEL_MODEL = 3;

    //商品标签
    const PRODUCT_TAG_EXCLUSIVE = 1;
    const PRODUCT_TAG_KILLSEC = 2;
    const PRODUCT_TAG_GROUPON = 3;
    const PRODUCT_TAG_REDUCED = 4;
    const PRODUCT_TAG_COMBINED= 5;
    const PRODUCT_TAG_BALANCE = 6;
    const PRODUCT_TAG_POINT = 7;

    //商品活动类型
    const PRODUCT_ACTIVITY_DEFAULT = 1;
    const PRODUCT_ACTIVITY_KILLSEC = 2;

    //对接设备
    const DEVICE_NO_CONN    = 1;//不对接
    const DEVICE_ZHIYOUBAO  = 2;//智游宝

    const LANG_CN = 1;
    const LANG_EN = 2;

    public function get_conn_devices()
    {
        return array(
            self::DEVICE_NO_CONN    => '不对接',
            self::DEVICE_ZHIYOUBAO  => '智游宝',
        );
    }

    public function get_goods_type_label()
    {
        return array(
            self::SPEC_TYPE_SCOPE   => '通用',
            self::SPEC_TYPE_TICKET  => '门票',
            self::SPEC_TYPE_COMBINE => '组合',
            self::SPEC_TYPE_ROOM    => '升级房券',
        );
    }


    public function get_status_label()
    {
        return array(
            self::STATUS_ACTIVE => '上架',
            self::STATUS_UNACTIVE => '下架',
            self::STATUS_HIDDEN => '隐藏',
        );
    }

    public function get_can_label()
    {
        return array(
            self::CAN_T => '能',
            self::CAN_F => '不能',
        );
    }


    public function get_is_label()
    {
        return array(
            self::IS => '是',
            self::IS_NOT => '不是',
        );
    }

    public function get_date_type()
    {
        return array(
            self::DATE_TYPE_STATIC => '固定失效时间',
            self::DATE_TYPE_FLOAT => '存活时间',
        );
    }

    public function get_can_wx_booking_label()
    {
        return array(
            self::CAN_WX_BOOKING => '支持（套票转预订）',
            self::CAN_NOT_WX_BOOKING => '不支持',
            self::CAN_PASS_TO_HOTEL_MODEL => '支持（订房套餐预定）',
        );
    }

    public function get_resource_name()
    {
        return '套票商品';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function table_name($inter_id = NULL)
    {
        return $this->_shard_table('soma_catalog_product_package', $inter_id);
    }

    public function table_name_r($inter_id = NULL)
    {
        return $this->_shard_table_r('soma_catalog_product_package', $inter_id);
    }

    public function en_table_name($inter_id = NULL)
    {
        return $this->_shard_table('soma_catalog_product_package_en', $inter_id);
    }

    public function extra_table_name($inter_id = NULL)
    {
        return $this->_shard_table('soma_catalog_product_package_extra', $inter_id);
    }

    public function table_primary_key()
    {
        return 'product_id';
    }

    public function can_edit_attribute()
    {
        return array('expiration_date', 'can_pickup', 'can_mail', 'can_gift', 'can_invoice', 'can_reserve',);
    }

    //定义 m_save 保存时不做转义字段
    public function unaddslashes_field()
    {
        return array(
            'img_detail',
            'order_notice',
            'compose',
            'hotel_address',
            'wx_booking_config',
        );
    }

    public function en_fields()
    {
        return array(
            'name',
            'compose',
            'order_notice',
            'img_detail',
            'hotel_address',
        );
    }

    public function extra_fields()
    {
        return array(
            'product_id',
            'inter_id',
            'hotel_id',
            'hotel_ids_str',
        );
    }

    public function attribute_labels()
    {
        return array(
            'product_id' => '编号',
            'inter_id' => '公众号',
            'hotel_id' => '酒店',
            'cat_id' => '商品分类',
            'sku' => 'SKU',
            'type' => '产品类型',
            'goods_type' => '商品类型',
            'name' => '商品名',
            'card_id' => '卡券ID',
            'stock' => '库存量',
            'conn_devices' => '对接设备',
            'latitude' => '经度',
            'longitude' => '纬度',
            'product_city' => '城市',
            'price_market' => '门市价',
            'price_package' => '微信价',
            'keyword' => '关键词描述',
            // 'compose' => '套票构成，序列号内容',
            'order_notice' => '订购须知',
            'img_detail' => '图文详情',
            'face_img' => '封面图',
            'transparent_img' => '透明封面图',
            'is_hide' => '首页是否显示',
            'use_cnt' => '使用次数',
            'can_split_use' => '能否分时使用',
            'can_wx_booking' => '能否微信订房',
            'wx_booking_config' => '能否微信订房配置',
            'can_refund' => '能否退款',
            'can_mail' => '能否邮寄',
            'shipping_fee_unit' => '邮费单位',
            'shipping_product_id' => '补邮商品',
            'shipping_instruction' => '邮费说明',
            'can_gift' => '能否赠送',
            'can_invoice' => '能否开发票',
            'can_pickup' => '到店自提/到店用券',
            'can_sms_notify' => '短信通知',
            'can_reserve' => '是否需要预约',
            'is_hide_reserve_date' => '能否显示预约发货',
            'max_reserve' => '可预约数',
            'show_sales_cnt' => '是否显示销售数量',
            'sales_cnt' => '销售数量',
            'room_id' => '预约商品名称',//'预约房型',
            'hotel_name' => '酒店名',
            'hotel_address' => '地址详情',
            'hotel_tel' => '预约电话',
            'validity_date' => '上架时间',
            'un_validity_date' => '下架时间',
            'date_type' => '失效模式',
            'use_date' => '存活天数',
            'expiration_date' => '过期失效时间',
            'sort' => '排序',
            'status' => '状态',
        );
    }

    /**
     * 后台管理的表格中要显示哪些字段
     */
    public function grid_fields()
    {
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
        return array(
            'product_id',
            // 'inter_id',
            // 'hotel_id',
            'cat_id',
            //'sku',
            'type',
            'goods_type',
            'name',
            // 'card_id',
            'stock',
            // 'latitude',
            // 'longitude',
            // 'product_city',
            // 'price_market',
            'price_package',
            //'keyword',
            // 'compose',
            // 'order_notice',
            // 'img_detail',
            'face_img',
            'transparent_img',
            'is_hide',
            'can_refund',
            'can_mail',
            'can_gift',
            'can_pickup',
            //'can_invoice',
            'can_reserve',
            // 'max_reserve',
            'show_sales_cnt',
            // 'sales_cnt',
            // 'can_wx_booking',
            // 'wx_booking_config',
            // 'room_id',
            // 'hotel_name',
            // 'hotel_address',
            // 'hotel_tel',
            // 'validity_date',
            // 'un_validity_date',
            // 'expiration_date',
            'sort',
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
        $Somabase_util = Soma_base::inst();
        $modules = config_item('admin_panels') ? config_item('admin_panels') : array();

        /** 获取本管理员的酒店权限  */
        $hotels_hash = $this->get_hotels_hash();
        $publics = $hotels_hash['publics'];
        $hotels = $hotels_hash['hotels'];
        $filter = $hotels_hash['filter'];
        $filterH = $hotels_hash['filterH'];
        /** 获取本管理员的酒店权限  */

        //房型
        $inter_id = $this->session->get_admin_inter_id();
//测试使用
// $inter_id = 'a429262687';//
        $ent_ids = $this->session->get_admin_hotels();
        $hotel_ids = $ent_ids ? explode(',', $ent_ids) : array();
        $this->load->model('hotel/hotel_model');
        $hotels_list = $this->hotel_model->get_all_hotels($inter_id);
        if ($hotels_list) {
            $rooms_list = $this->hotel_model->get_hotel_rooms($inter_id, $hotels_list[0]['hotel_id']);

        } else {
            $rooms_list = array();
        }
// var_dump( $rooms_list );exit;
        $rooms = array();
        foreach ($rooms_list as $k => $v) {
            $rooms[$v['room_id']] = $v['name'];
        }

        //是否显示选择房型
        $show_room_id = FALSE;
        if (defined('PROJECT_AREA') && PROJECT_AREA == 'mooncake') {
            $show_room_id = TRUE;
        }

        $this->load->model('soma/category_package_model');
        $cat_inter_id = isset($filter['inter_id']) ? $filter['inter_id'] : NULL;
        $cats = $this->category_package_model->get_cat_tree_option($cat_inter_id);

        /** 会员礼包拉取 **/
        $this->load->library('Soma/Api_member');
        $api = new Api_member($inter_id);
        $result = $api->get_token();
        $api->set_token($result['data']);
        $giftAll = $api->get_package_list();
        $packages = array();
        $data = (array)$giftAll['data'];
        foreach ($data as $k => $v) {
            $packages[$v->package_id] = $v->name;
        }

        return array(
            'product_id' => array(
                'grid_ui' => '',
                'grid_width' => '6%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'text',    //textarea|text|combobox|number|email|url|price
            ),
            'inter_id' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                // 'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                // 'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                // 'type'=>'text',	//textarea|text|combobox|number|email|url|price
                'type' => 'combobox',
                'select' => $publics,
            ),
            'hotel_id' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                // 'type'=>'text',	//textarea|text|combobox|number|email|url|price
                'type' => 'combobox',
                'select' => $hotels,
            ),
            'cat_id' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'combobox',    //textarea|text|combobox|number|email|url|price
                'select' => $cats,
            ),
            'sku' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'text',    //textarea|text|combobox|number|email|url|price
            ),
            'type' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                'form_default' => '1',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'combobox', //textarea|text|combobox|number|email|url|price
                'select' => $this->get_product_type_label(),
            ),
            'goods_type' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                'form_default' => '1',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'combobox', //textarea|text|combobox|number|email|url|price
                'select' => $this->get_goods_type_label(),
            ),
            'name' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'text',    //textarea|text|combobox|number|email|url|price
            ),
            'card_id' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                // 'form_ui'=> ' disabled ',
                // 'form_default'=> '1',
                //'form_tips'=> '注意事项',
                // 'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'combobox', //textarea|text|combobox|number|email|url|price
                'select' => $packages,
            ),
            'stock' => array(
                'grid_ui' => '',
                'grid_width' => '8%',
                'form_ui' => ' step="1" min="0" max="100000" ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'number', //textarea|text|combobox|number|email|url|price
            ),
            'conn_devices' => array(
                'grid_ui' => '',
                'grid_width' => '8%',
                'form_ui' => ' step="1" min="0" max="100000" ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'combobox', //textarea|text|combobox|number|email|url|price
                'select'=>$this->get_conn_devices(),
            ),
            'latitude' => array(
                'grid_ui' => '',
                'grid_width' => '6%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide' => TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'text', //textarea|text|combobox|number|email|url|price
            ),
            'longitude' => array(
                'grid_ui' => '',
                'grid_width' => '6%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide' => TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'text', //textarea|text|combobox|number|email|url|price
            ),
            'product_city' => array(
                'grid_ui' => '',
                'grid_width' => '8%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide' => TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'text', //textarea|text|combobox|number|email|url|price
            ),
            'price_market' => array(
                'grid_ui' => '',
                'grid_width' => '6%',
                //'form_ui'=> ' disabled ',
                'form_default' => '999.00',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function' => 'show_price_prefix|￥',
                'type' => 'price',    //textarea|text|combobox|number|email|url|price
            ),
            'price_package' => array(
                'grid_ui' => '',
                'grid_width' => '6%',
                //'form_ui'=> ' disabled ',
                'form_default' => '0.01',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function' => 'show_price_prefix|￥',
                'type' => 'price',    //textarea|text|combobox|number|email|url|price
            ),
            'keyword' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'text',    //textarea|text|combobox|number|email|url|price
            ),
            'compose' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide' => TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'textarea',    //textarea|text|combobox|number|email|url|price
            ),
            'order_notice' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'textarea', //textarea|text|combobox|number|email|url|price
            ),
            'img_detail' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'textarea', //textarea|text|combobox|number|email|url|price
            ),
            'face_img' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function' => 'show_cat_img|100|',
                'type' => 'logo',    //textarea|text|combobox|number|email|url|price
            ),
            'transparent_img' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function' => 'show_cat_img|100|',
                'type' => 'logo', //textarea|text|combobox|number|email|url|price
            ),
            'is_hide' => array(
                'grid_ui' => '',
                'grid_width' => '5%',
                //'form_ui'=> ' disabled ',
                'form_default' => self::CAN_T,
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                // 'type'=>'text',  //textarea|text|combobox|number|email|url|price
                'type' => 'combobox',
                'select' => self::get_status_yes_label(),
            ),
            'use_cnt' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                'form_ui' => ' step="1" min="1" max="200" ',
                'form_default' => '1',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'number', //textarea|text|combobox|number|email|url|price
            ),
            'can_split_use' => array(
                'grid_ui' => '',
                'grid_width' => '5%',
                //'form_ui'=> ' disabled ',
                'form_default' => self::CAN_T,
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                // 'type'=>'text',  //textarea|text|combobox|number|email|url|price
                'type' => 'combobox',
                'select' => self::get_status_can_label(),
            ),
            'can_refund' => array(
                'grid_ui' => '',
                'grid_width' => '5%',
                //'form_ui'=> ' disabled ',
                'form_default' => self::CAN_REFUND_STATUS_SEVEN,
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                // 'type'=>'text',	//textarea|text|combobox|number|email|url|price
                'type' => 'combobox',
                'select' => self::get_refund_status_label(),
            ),
            'can_mail' => array(
                'grid_ui' => '',
                'grid_width' => '5%',
                //'form_ui'=> ' disabled ',
                'form_default' => self::CAN_F,
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'combobox',
                'select' => self::get_status_can_label(),
            ),
            'shipping_fee_unit' => array(
                'grid_ui' => '',
                'grid_width' => '5%',
                //'form_ui'=> ' disabled ',
                'form_default' => 1,
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'text',
                // 'select'=> self::get_status_can_label(),
            ),
            'shipping_product_id' => array(
                'grid_ui' => '',
                'grid_width' => '5%',
                //'form_ui'=> ' disabled ',
                // 'form_default'=> self::CAN_F,
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'combobox',
                'select' => self::get_status_can_label(),
            ),
            'shipping_instruction' => array(
                'grid_ui' => '',
                'grid_width' => '5%',
                //'form_ui'=> ' disabled ',
                // 'form_default'=> self::CAN_F,
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'text',
                // 'select'=> self::get_status_can_label(),
            ),
            'can_gift' => array(
                'grid_ui' => '',
                'grid_width' => '5%',
                //'form_ui'=> ' disabled ',
                'form_default' => self::CAN_T,
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'combobox',
                'select' => self::get_status_can_label(),
            ),
            'can_pickup' => array(
                'grid_ui' => '',
                'grid_width' => '5%',
                //'form_ui'=> ' disabled ',
                'form_default' => self::CAN_F,
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'combobox',
                'select' => self::get_status_can_label(),
            ),
            'can_invoice' => array(
                'grid_ui' => '',
                'grid_width' => '5%',
                //'form_ui'=> ' disabled ',
                'form_default' => self::CAN_F,
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'combobox',
                'select' => self::get_status_can_label(),
            ),
            'is_hide_reserve_date' => array(
                'grid_ui' => '',
                'grid_width' => '5%',
                //'form_ui'=> ' disabled ',
                'form_default' => self::CAN_T,
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                // 'type'=>'text',  //textarea|text|combobox|number|email|url|price
                'type' => 'combobox',
                'select' => self::get_status_can_label(),
            ),
            'can_sms_notify' => array(
                'grid_ui' => '',
                'grid_width' => '5%',
                //'form_ui'=> ' disabled ',
                'form_default' => self::CAN_T,
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                // 'type'=>'text',  //textarea|text|combobox|number|email|url|price
                'type' => 'combobox',
                'select' => self::get_status_yes_label(),
            ),
            'can_reserve' => array(
                'grid_ui' => '',
                'grid_width' => '5%',
                //'form_ui'=> ' disabled ',
                'form_default' => self::CAN_T,
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                // 'type'=>'text',	//textarea|text|combobox|number|email|url|price
                'type' => 'combobox',
                'select' => self::get_status_yes_label(),
            ),
            'max_reserve' => array(
                'grid_ui' => '',
                'grid_width' => '8%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide' => TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'number',    //textarea|text|combobox|number|email|url|price
            ),
            'show_sales_cnt' => array(
                'grid_ui' => '',
                'grid_width' => '5%',
                //'form_ui'=> ' disabled ',
                'form_default' => self::CAN_T,
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                // 'type'=>'text',  //textarea|text|combobox|number|email|url|price
                'type' => 'combobox',
                'select' => self::get_status_yes_label(),
            ),
            'sales_cnt' => array(
                'grid_ui' => '',
                'grid_width' => '5%',
                //'form_ui'=> ' disabled ',
                // 'form_default'=> self::CAN_T,
                //'form_tips'=> '注意事项',
                'form_hide' => TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'text',  //textarea|text|combobox|number|email|url|price
            ),
            'can_wx_booking' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide' => TRUE,//TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'combobox', //textarea|text|combobox|number|email|url|price
                'select' => self::get_can_wx_booking_label(),
            ),
            'wx_booking_config' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide' => TRUE,//TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'combobox', //textarea|text|combobox|number|email|url|price
                'select' => self::get_status_yes_label(),
            ),
            'room_id' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide' => TRUE,//$show_room_id,//
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'combobox',    //textarea|text|combobox|number|email|url|price
                'select' => $rooms,
            ),
            'hotel_name' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide' => TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'text', //textarea|text|combobox|number|email|url|price
            ),
            'hotel_address' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                // 'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide' => TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'text',    //textarea|text|combobox|number|email|url|price
            ),
            'hotel_tel' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                // 'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide' => TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'text', //textarea|text|combobox|number|email|url|price
            ),
            'validity_date' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                'form_default' => date('Y-m-d H:i:s'),
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'datetime', //textarea|text|combobox|number|email|url|price
            ),
            'un_validity_date' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                'form_default' => date('Y-m-d H:i:s'),
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'datetime',    //textarea|text|combobox|number|email|url|price
            ),
            'date_type' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                'form_default' => '1',
                // 'form_default'=> date('Y-m-d H:i:s'),
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'combobox', //textarea|text|combobox|number|email|url|price
                'select' => $this->get_date_type(),
            ),
            'use_date' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                'form_ui' => ' step="1" min="1" max="10000" ',
                'form_default' => '1',
                // 'form_default'=> date('Y-m-d H:i:s'),
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'number', //textarea|text|combobox|number|email|url|price
            ),
            'expiration_date' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                'form_default' => date('Y-m-d H:i:s'),
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'datetime', //textarea|text|combobox|number|email|url|price
            ),
            'sort' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                'form_tips' => '从大到小排列',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'number',    //textarea|text|combobox|number|email|url|price
            ),
            'status' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type' => 'combobox',    //textarea|text|combobox|number|email|url|price
                'select' => $this->get_status_label(),//$Somabase_util::get_status_options(),
            ),
        );
    }

    /**
     * grid表格中默认哪个字段排序，排序方向
     */
    public static function default_sort_field()
    {
        return array('field' => 'product_id', 'sort' => 'desc');
    }

    /* 以上为AdminLTE 后台UI输出配置函数 */

    //对应不同的产品类型，价格字段各不相同
    public function product_price_fieldname()
    {
        return array(
            'package' => 'price_package',
            'killsec' => 'price_killsec',
        );
    }

    //反序列化compose字段，输出到详情
    public function unserialize_compose()
    {
        if (isset($this->_data['compose']) && !empty($this->_data['compose'])) {
            // return unserialize( $this->_data['compose'] );
            $content = @unserialize($this->_data['compose']);
            if ($content) {
                return $content;
            }
        }

        //套票内容,可以设置多个
        return array(
            '1' => array('content' => '', 'num' => ''),
            '2' => array('content' => '', 'num' => ''),
            '3' => array('content' => '', 'num' => ''),
        );

    }

    //反序列化compose字段，输出到详情
    public function unserialize_compose_en()
    {
        if (isset($this->_en_data['compose_en']) && !empty($this->_en_data['compose_en'])) {
            // return unserialize( $this->_data['compose'] );
            $content = @unserialize($this->_en_data['compose_en']);
            if ($content) {
                return $content;
            }
        }

        //套票内容,可以设置多个
        return array(
            '1' => array('content' => '', 'num' => ''),
            '2' => array('content' => '', 'num' => ''),
            '3' => array('content' => '', 'num' => ''),
        );

    }

    //获取套票商品列表
    public function get_product_package_list($catIds = '', $interId = NULL, $pageNum = NULL, $listNum = NULL, $isTicket=FALSE)
    {
        $inter_id = empty($interId) ? $this->session->get_admin_inter_id() : $interId;
        $table_name = $this->table_name_r($inter_id);
        $db = $this->_shard_db_r('iwide_soma_r');

        //如果$catIds等于空显示所有
        $where = array();
        if( $catIds && is_array( $catIds ) )
        {
            $db->where_in('cat_id',$catIds);
        } else if ($catIds && $catIds >= 1) {
            $where['cat_id'] = intval($catIds);
        }

        $where['inter_id'] = $inter_id;
        $where['status'] = parent::STATUS_TRUE;
        $db->where($where);

        //过滤条件过期时间
        $time = date('Y-m-d H:i:s', time());
        $db->where('validity_date < ', $time);
        $db->where('un_validity_date > ', $time);

        $sort = isset($sort) ? $sort : 'sort DESC';
        $db->order_by($sort);

        //如果$pageNum等于空显示所有
        if( $isTicket )
        {
            //如果是门店设置获取，就不限制取出条数
        } else {
            $pageNum = empty($pageNum) ? 1 : $pageNum;
            $listNum = empty($listNum) ? 20 : $listNum;
            $startNum = ($pageNum - 1) * $listNum;
            $db->limit($listNum, $startNum);
        }

        $result = $db->get($table_name)->result_array();
//        echo $db->last_query();die;
        return $result;
    }

    public function get_package_list($inter_id, $filter=array(), $limit = NULL, $offset = 0)
    {
        // $table = $this->table_name( $inter_id );
        $table = $this->table_name_r($inter_id);

        //添加一个条件，如果禁用就不查找出来
        $filter['status'] = parent::STATUS_TRUE;
        //过滤条件过期时间
        $time = date('Y-m-d H:i:s', time());

        if (!empty($limit)) {
            $result = $this->_shard_db_r('iwide_soma_r')
                ->where('validity_date < ', $time)
                ->where('un_validity_date > ', $time)
                ->order_by('sort DESC')
                ->limit($limit, $offset)
                ->get_where($table, $filter)
                ->result_array();
        } else {
            $result = $this->_shard_db_r('iwide_soma_r')
                ->where('validity_date < ', $time)
                ->where('un_validity_date > ', $time)
                ->order_by('sort DESC')
                ->get_where($table, $filter)
                ->result_array();
        }
        return $result;
    }

    /**
     * author Jake
     * @param $lat //经度
     * @param $lng //纬度
     * @param $catId //分类ID
     * @param string $pageNum //页数
     * @param int $listNum //每页展示，默认20一页
     * @return array
     */
    public function get_packages_nearby($lat, $lng, $catId = null, $inter_id, $pageNum = NULL, $listNum = 20)
    {
        if ($pageNum) {
            $offset = ($pageNum - 1) * $listNum;
        } else {
            $offset = 0;
        }

        //添加一个条件，如果禁用就不查找出来
        $status = parent::STATUS_TRUE;

        //过滤条件过期时间
        $time = date('Y-m-d H:i:s', time());

        $table = $this->_db()->dbprefix('soma_catalog_product_package');

        if (!empty($catId)) {
            $sql = "SELECT *,
            (POWER(MOD(ABS(longitude - $lng),360),2) + POWER(ABS(latitude - $lat),2)) AS distance
            FROM `{$table}`
            where cat_id = {$catId} and
            inter_id = '{$inter_id}' and 
            validity_date < '{$time}' and 
            status = '{$status}' 
            ORDER BY distance LIMIT {$offset},{$listNum}
            ";
        } else {
            $sql = "SELECT *,
            (POWER(MOD(ABS(longitude - $lng),360),2) + POWER(ABS(latitude - $lat),2)) AS distance
            FROM `{$table}` where
            inter_id = '{$inter_id}' and 
            validity_date < '{$time}' and 
            status = '{$status}' 
            ORDER BY distance LIMIT {$offset},{$listNum}
            ";
        }


        $result = $this->_db()->query($sql)->result_array();

        //--------------------------------debug-----------------------------
        //print_r($result);

        return $result;

    }

    //根据product_id获取套票商品信息
    public function get_product_package_detail_by_product_id($productId = '', $interId = NULL, $status = self::STATUS_TRUE)
    {
        if (!$productId) {
            return false;
        }

        if ($interId) {
            $inter_id = $interId;
        } else {
            $inter_id = $this->session->get_admin_inter_id();
        }

        $where = array();
        $where['inter_id'] = $inter_id;
        $where['product_id'] = intval($productId);

        //过滤条件过期时间
        $time = date('Y-m-d H:i:s', time());
        $where['validity_date < '] = $time;
        $where['un_validity_date > '] = $time;

        //添加一个条件，如果禁用就不查找出来
        if ($status) {
            $where['status'] = $status;
        }

        $table_name = $this->table_name($inter_id);
        return $this->_shard_db_r('iwide_soma_r')
            ->where($where)
            ->get($table_name)
            ->row_array();
    }

    //根据product_id获取套票商品信息
    public function get_product_package_phone_by_product_id($productId = '', $interId = NULL)
    {
        if (!$productId) {
            return false;
        }

        if ($interId) {
            $inter_id = $interId;
        } else {
            $inter_id = $this->session->get_admin_inter_id();
        }

        $where = array();
        $where['inter_id'] = $inter_id;
        $where['product_id'] = intval($productId);

        $table_name = $this->table_name_r($inter_id);
        $rs = $this->_shard_db_r('iwide_soma_r')
            ->where($where)
            ->get($table_name)
            ->row_array();
        return $rs;
    }

    public function get_product_package_by_ids($ids, $interId = NULL, $select = '*', $sort=NULL)
    {

        $db = $this->_shard_db_r('iwide_soma_r');

        if (!$ids) {
            return false;
        }
        if ($interId) {
            $inter_id = $interId;
        } else {
            $inter_id = $this->session->get_admin_inter_id();
        }

        if ($inter_id) {
            $db->where(array('inter_id' => $inter_id));
        }

        //添加一个条件，如果禁用就不查找出来
        $status = parent::STATUS_TRUE;

        //过滤条件过期时间
        $time = date('Y-m-d H:i:s', time());

        if( $sort )
        {
            $db->order_by($sort);
        }

        $table_name = $this->table_name($inter_id);
        $rs = $db->where('validity_date < ', $time)
            ->where_in('product_id', $ids)
            ->where(array('status' => $status))
            ->select($select)
            ->get($table_name)->result_array();
        return $rs;
    }

    //获取相册记录
    public function get_gallery()
    {
        $inter_id = $this->session->get_admin_inter_id();
        $pk = $this->table_primary_key();
        if ($pkv = $this->m_get($pk)) {
            $table = 'soma_catalog_product_gallery';
            $items = $this->_shard_db_r('iwide_soma_r')->get_where($table, array('product_id' => $pkv))->result_array();
            return $items;

        } else {
            return array();
        }
    }

    //前端获取相册
    public function get_gallery_front($product_id, $inter_id)
    {
        if (!$product_id) {
            return array();
        }

        $where = array();
        $where['product_id'] = $product_id + 0;
        // $where['inter_id'] = $inter_id;

        $table = 'soma_catalog_product_gallery';
        $items = $this->_shard_db_r('iwide_soma_r')->get_where($table, $where)->result_array();
        return $items;
    }

    //新增相册记录
    public function plus_gallery($data)
    {
        $inter_id = $this->session->get_admin_inter_id();
        $table = 'soma_catalog_product_gallery';
        $result = $this->_shard_db($inter_id)->insert($table, $data);
        return $result;
    }

    //删除相册记录
    public function delete_gallery($ids, $pkv)
    {
        $inter_id = $this->session->get_admin_inter_id();
        $pk = $this->table_primary_key();
        if ($pkv) {
            $table = 'soma_catalog_product_gallery';
            $result = $this->_shard_db($inter_id)->where(array('product_id' => $pkv))->where_in('gry_id', $ids)->delete($table);
            return $result;

        } else {
            return array();
        }
    }

    /**
     * 保存套票内容
     * Usage: $model->post = $post;
     * Usage: $model->product_package_save($business, $inter_id);
     * @author luguihong
     */
    public function product_package_save($business, $inter_id)
    {
        $post = $this->post;

        $pk = $this->table_primary_key();

        $business = strtolower($business);

        $this->_shard_db($inter_id)->trans_begin();

        //添加idx
        $data = array();
        $data[$pk] = $post[$pk];
        $data['business'] = $business;
        $data['inter_id'] = $post['inter_id'];
        $data['hotel_id'] = $post['hotel_id'];

        $table = $this->_shard_db($inter_id)->dbprefix('soma_catalog_product_idx');
        $idx_result = $this->_shard_db($inter_id)->insert($table, $data);
        $result = $this->_m_save($post);
        // return $result;
        //保存规格信息（setting）

        $spec_save_sign = isset($this->spec_save_sign) && !empty($this->spec_save_sign) ? $this->spec_save_sign : FALSE;
        $spec_result = $spec_setting_result = TRUE;
        if ($spec_save_sign) {
            $spec_setting_data = isset($this->spec_setting_data) && !empty($this->spec_setting_data) ? $this->spec_setting_data : FALSE;
            if ($spec_setting_data) {
                $this->load->model('soma/Product_specification_setting_model', 'ProductSettingModel');
                $ProductSettingModel = $this->ProductSettingModel;
                $spec_setting_result = $ProductSettingModel->setting_batch_save($this, $inter_id);
            }

            //保存全部规格信息
            $spec_data = isset($this->spec_data) && !empty($this->spec_data) ? $this->spec_data : FALSE;
            if ($spec_data) {
                $this->load->model('soma/Product_specification_model', 'ProductSpecModel');
                $ProductSpecModel = $this->ProductSpecModel;
                $spec_result = $ProductSpecModel->spec_list_save($this, $inter_id);
            }
        }
        
        // 保存产品英文信息
        // var_dump($post['language'] == self::LANG_EN);exit;
        $en_result = TRUE;
        if(isset($post['language']) && $post['language'] == self::LANG_EN)
        {
            $en_data['product_id']    = $post['product_id'];
            foreach ($this->en_fields() as $key)
            {
                $en_data[$key] = $post[$key . '_en'];
            }

            $en_table = $this->en_table_name($inter_id);
            $en_result = $this->_shard_db($inter_id)->replace($en_table, $en_data);
        }
        // var_dump($en_result);exit;

        // 保存产品额外信息
        $extra_result = TRUE;
        foreach ($this->extra_fields() as $key) {
            $extra_data[$key] = $post[$key];
        }
        if(!empty($extra_data)) {
            $extra_table = $this->extra_table_name($inter_id);
            $extra_result = $this->_shard_db($inter_id)->replace($extra_table, $extra_data);
        }

        //保存产品相册
        $gallery_result = TRUE;
        if (isset($post['gallery']) && !empty($post['gallery'])) {
            $post['gallery'] = explode('","', trim(trim($post['gallery'], '[""'), '""]'));
            $gallery_data = array();
            $gallery_table = 'soma_catalog_product_gallery';

            if (is_array($post['gallery'])) {
                foreach ($post['gallery'] as $v) {
                    $ga_data = array(
                        'gry_url' => $v,
                        'gry_intro' => '',
                        'product_id' => $post[$pk],
                    );
                    $gallery_data[] = $ga_data;
                }
            } else {
                $gallery_data[] = array(
                    'gry_url' => $post['gallery'],
                    'gry_intro' => '',
                    'product_id' => $post[$pk],
                );
            }

            if ($gallery_data) {
                $gallery_result = $this->_shard_db($inter_id)->insert_batch($gallery_table, $gallery_data);
            }
        }

        // 保存产品子商品信息
        $combine_product_result = true;
        if($post['goods_type'] == self::SPEC_TYPE_COMBINE)
        {
            $this->load->model('soma/Product_package_link_model', 'p_combine_model');
            $combine_product_result = $this->p_combine_model->saveCombineProductInfo($this->combine_data);
        }
// var_dump( $result, $idx_result, $spec_setting_result );die;

        if ($result && $idx_result && $spec_setting_result && $spec_result && $gallery_result && $en_result && $combine_product_result && $extra_result) {
            if ($spec_save_sign) {
                //同步信息需要记录操作员是谁
                $CI = &get_instance();
                $remote_ip = $CI->input->ip_address();
                $username = $this->session->get_admin_username();

                $log_tags = "规格信息变动数据：\r\n";
                $log_tags .= "保存人信息：ip:{$remote_ip} 操作人:{$username} 公众号ID：{$inter_id} 产品ID：{$post[$pk]}\r\n";
                $log_tags .= "保存的规格信息：" . json_encode($this->spec_setting_data) . "\r\n";
                $log_path = APPPATH . 'logs' . DS . 'soma' . DS . 'product_setting';
                if (!file_exists($log_path)) {
                    @mkdir($log_path, 0777, TRUE);
                }
                $this->write_log($log_tags, $log_path . DS . date('Y-m-d') . '.txt');
            }
        } else {
            $this->_shard_db($inter_id)->trans_rollback();
            return FALSE;
        }

        if ($this->_shard_db($inter_id)->trans_status() === FALSE) {
            $this->_shard_db($inter_id)->trans_rollback();
            return FALSE;

        } else {
            $this->_shard_db($inter_id)->trans_commit();
            return TRUE;
        }

    }

    /**
     * 更新套票内容
     * Usage: $model->post = $post;
     * Usage: $model->product_package_save($business, $inter_id);
     * @author luguihong
     */
    public function product_package_update($business, $inter_id)
    {
        // 额外字段要去除，不然主表保存不了

        $this->_shard_db($inter_id)->trans_begin();
        $pk = $this->table_primary_key();
        $post = $this->post;
        // 额外字段要去除，不然主表保存不了
        unset($post['hotel_ids_str']);
        unset($this->_data['hotel_ids_str']);
        $result = $this->m_sets($post)->m_save($post);
        $post = $this->post;
        
        $this->load->model('soma/Product_specification_setting_model', 'ProductSettingModel');
        $ProductSettingModel = $this->ProductSettingModel;

        /**
         * @var Product_specification_model $ProductSpecModel
         */
        $this->load->model('soma/Product_specification_model', 'ProductSpecModel');
        $ProductSpecModel = $this->ProductSpecModel;

        //规格操作
        $spec_save_sign = isset($this->spec_save_sign) && !empty($this->spec_save_sign) ? $this->spec_save_sign : FALSE;
        $spec_result = $result_add = $result_update = $result_delete = TRUE;
        if ($spec_save_sign) {
            //保存全部规格信息
            $spec_data = isset($this->spec_data) ? $this->spec_data : FALSE;
//            var_dump( $spec_data );die;
            if ($spec_data) {
                //判断是否已经存在
                $specList = $ProductSpecModel->get_spec_list($inter_id, $post[$pk]);
//                var_dump( $specList );die;
                if (!$specList) {
                    $spec_result = $ProductSpecModel->spec_list_save($this, $inter_id);
                } else {
                    //判断原内容是否和修改的内容一致
                    /*foreach( $specList as $spec )
                    {
                        if ( $spec_data[$spec['type']]['spec_compose'] != $spec['spec_compose'] )
                        {
                            $spec_result = $ProductSpecModel->spec_list_update(
                                                                                $this,
                                                                                $inter_id,
                                                                                $post[$pk],
                                                                                $spec['spec_id'],
                                                                                array('spec_compose'=>$spec_data[$spec['type']]['spec_compose'])
                                                                            );
                            //只要有一个更新失败都回滚
                            if (!$spec_result) {
                                break;
                            }
                        } else {
                            //有可能拿出来的规格类型不是当前要处理的类型
                            //$spec_result = $ProductSpecModel->spec_list_save($this, $inter_id);
                        }
                    }*/
                    foreach( $spec_data as $type=> $data )
                    {
                        if ( isset( $specList[$type] ) )
                        {
                            if( $specList[$type]['spec_compose'] != $data['spec_compose'] )
                            {
                                $spec_result = $ProductSpecModel->spec_list_update(
                                    $this,
                                    $inter_id,
                                    $post[$pk],
                                    $specList[$type]['spec_id'],
                                    array('spec_compose' => $data['spec_compose'])
                                );
                                //只要有一个更新失败都回滚
                                if ( ! $spec_result)
                                {
                                    break;
                                }
                            }
                        } else {
                            //有可能拿出来的规格类型不是当前要处理的类型
                            $spec_result = $ProductSpecModel->spec_list_save($this, $inter_id);
                        }
                    }
                }
            }

            $spec_setting_data = isset($this->spec_setting_data) ? $this->spec_setting_data : FALSE;
            if ($spec_setting_data) {
                //添加操作
                $result_add = $ProductSettingModel->setting_batch_save($this, $inter_id);
            }

            $update_spec_setting_data = isset($this->update_spec_setting_data) ? $this->update_spec_setting_data : FALSE;
            if ($update_spec_setting_data) {
                //更新操作
                foreach ($update_spec_setting_data as $k => $v) {
                    $result_update = $ProductSettingModel->setting_batch_update($this, $inter_id, $post[$pk], $k, $v);
                    //只要有一个更新失败都回滚
                    if (!$result_update) {
                        break;
                    }
                }
            }

            $delete_spec_setting_data = isset($this->delete_spec_setting_data) ? $this->delete_spec_setting_data : FALSE;
            if ($delete_spec_setting_data) {
                //删除操作
                $settingIds = array();
                foreach ($delete_spec_setting_data as $k => $v) {
                    $settingIds[] = $v['setting_id'];
                }

                if ($settingIds) {
                    $result_delete = $ProductSettingModel->setting_batch_delete($this, $inter_id, $post[$pk], $settingIds);
                }
            }
        }

        // 修改产品英文信息
        $en_result = TRUE;
        if(isset($post['language']) && $post['language'] == self::LANG_EN)
        {
            $en_data['product_id']    = $post['product_id'];
            foreach ($this->en_fields() as $key)
            {
                $en_data[$key] = $post[$key . '_en'];
            }

            $en_table = $this->en_table_name($inter_id);
            $en_result = $this->_shard_db($inter_id)->replace($en_table, $en_data);
        }

        // 保存产品额外信息
        $extra_result = TRUE;
        foreach ($this->extra_fields() as $key) {
            $extra_data[$key] = $post[$key];
        }
        if(!empty($extra_data)) {
            $extra_table = $this->extra_table_name($inter_id);
            $extra_result = $this->_shard_db($inter_id)->replace($extra_table, $extra_data);
        }

        //修改产品相册
        $gallery_add_result = $gallery_delete_result = TRUE;
        if (isset($post['gallery']) && !empty($post['gallery'])) {
            $post['gallery'] = explode('","', trim(trim($post['gallery'], '[""'), '""]'));
            $gallery_add_data = array();
            $gallery_table = 'soma_catalog_product_gallery';

            // 查找相册列表
            $gallery_list = $this->get_gallery();
            $gallertyIds = array();
            if ($gallery_list) {
                foreach ($gallery_list as $k => $v) {
                    $gallertyIds[$v['gry_url']] = $v['gry_id'];
                }

                foreach ($post['gallery'] as $v) {
                    if (isset($gallertyIds[$v])) {
                        //已经存在，不管
                        unset($gallertyIds[$v]);
                    } else {
                        //不存在的
                        $ga_data = array(
                            'gry_url' => $v,
                            'gry_intro' => '',
                            'product_id' => $post[$pk],
                        );
                        $gallery_add_data[] = $ga_data;
                    }
                }
            } else {
                if (is_array($post['gallery'])) {
                    foreach ($post['gallery'] as $v) {
                        $ga_data = array(
                            'gry_url' => $v,
                            'gry_intro' => '',
                            'product_id' => $post[$pk],
                        );
                        $gallery_add_data[] = $ga_data;
                    }
                } else {
                    $gallery_add_data[] = array(
                        'gry_url' => $post['gallery'],
                        'gry_intro' => '',
                        'product_id' => $post[$pk],
                    );
                }

            }

            if ($gallery_add_data) {
                $gallery_add_result = $this->_shard_db($inter_id)->insert_batch($gallery_table, $gallery_add_data);
            }
            // var_dump( $gallertyIds, $gallery_add_data );die;
            if ($gallertyIds) {
                $gryIds = array_values($gallertyIds);
                $gallery_delete_result = $this->_shard_db($inter_id)->where_in('gry_id', $gryIds)->limit(count($gryIds))->delete($gallery_table);
            }
        }

        // 保存产品子商品信息
        $combine_product_result = true;
        if($post['goods_type'] == self::SPEC_TYPE_COMBINE)
        {
            $this->load->model('soma/Product_package_link_model', 'p_combine_model');
            $combine_product_result = $this->p_combine_model->saveCombineProductInfo($this->combine_data);
        }

// var_dump( $result, $spec_result, $result_add, $result_update, $result_delete, $gallery_add_result, $gallery_delete_result, $en_result, $combine_product_result, $extra_result);die;
        if ($result && $spec_result && $result_add && $result_update && $result_delete && $gallery_add_result && $gallery_delete_result && $en_result && $combine_product_result && $extra_result) {
            if ($spec_save_sign) {
                //同步信息需要记录操作员是谁
                $CI = &get_instance();
                $remote_ip = $CI->input->ip_address();
                $username = $this->session->get_admin_username();

                $setting_list = $ProductSettingModel->get_specification_compose($inter_id, $post[$pk]);
                $log_tags = "规格信息变动数据：\r\n";
                $log_tags .= "修改人信息：ip:{$remote_ip} 操作人:{$username} 公众号ID：{$inter_id} 产品ID：{$post[$pk]}\r\n";
                $log_tags .= "旧的规格信息：" . json_encode($setting_list) . "\r\n";
                $log_tags .= "添加的规格信息：" . json_encode($this->spec_setting_data) . "\r\n";
                $log_tags .= "更新的规格信息：" . json_encode($this->update_spec_setting_data) . "\r\n";
                $log_tags .= "删除的规格信息：" . json_encode($this->delete_spec_setting_data) . "\r\n";
                $log_path = APPPATH . 'logs' . DS . 'soma' . DS . 'product_setting';
                if (!file_exists($log_path)) {
                    @mkdir($log_path, 0777, TRUE);
                }
                $this->write_log($log_tags, $log_path . DS . date('Y-m-d') . '.txt');
            }

        } else {
            $this->_shard_db($inter_id)->trans_rollback();
            return FALSE;
        }

        if ($this->_shard_db($inter_id)->trans_status() === FALSE) {
            $this->_shard_db($inter_id)->trans_rollback();
            return FALSE;

        } else {
            $this->_shard_db($inter_id)->trans_commit();
            return TRUE;
        }

    }

    /**
     * 根据商品id获取最大预约数
     * Usage: $model->get_max_reserve($product_id, $inter_id);
     * @author luguihong
     */
    public function get_max_reserve($product_id, $inter_id)
    {
        if (!$product_id || !$inter_id) {
            return FALSE;
        }

        $where = array();
        $where['product_id'] = $product_id + 0;
        $where['inter_id'] = $inter_id;

        $select = 'max_reserve';

        return $this->find($where, $select);
    }

    /**
     * 更新产品属性时需要更新相关的表，仅限开发使用
     * Product_package::update_post
     * @return [type] [description]
     */
    public function update_related_table($data, $filter = array())
    {
        $log_tags = "Product_package_model::update_related_table()\r\n";
        $log_path = APPPATH . 'logs' . DS . 'soma' . DS . date('Y-m-d') . 'txt';
        $this->write_log($log_tags . json_encode($data), $log_path);

        $tables = array(
            // 'table_name' => 'pk'
            'soma_sales_order_item_package' => 'item_id',
            'soma_asset_item_package' => 'item_id',
            'soma_consumer_order_item_package' => 'item_id',
        );

        $update_key = $this->can_edit_attribute();
        $update_data = array();
        foreach ($update_key as $key) {
            if (isset($data[$key]) && !empty($data[$key])) {
                $update_data[$key] = $data[$key];
            }
        }

        $where = array(
            'product_id' => $this->m_get('product_id'),
            'inter_id' => $this->m_get('inter_id'),
        );

        if (count($filter) > 0) {
            foreach ($filter as $k => $v) {
                $where[$k] = $v;
            }
        }

        $db_link = $this->_db();
        $db_link->trans_begin();

        // 去除额外字段，防止延期时报错
        unset($this->_data['hotel_ids_str']);

        $this->m_sets($update_data)->m_save();

        foreach ($tables as $table => $pk) {

            $update_table = $this->_shard_table($table);

            $update_ids = $db_link->select(array($pk))
                ->from($update_table)->where($where)->get()->result_array();
            $ids_str = json_encode($update_ids);
            $log_content = $log_tags . "table:{$update_table}\r\n" . "ids:{$ids_str}\r\n";
            $this->write_log($log_content, $log_path);

            $res = $db_link->update($update_table, $update_data, $where);
            if (!$res) {
                $db_link->trans_rollback();
                return false;
            }
        }
        $db_link->trans_commit();
        return true;
    }

    /**
     * 导入产品销售数据
     *
     * @param      array $data The data
     *
     * @return     boolean  导入成功返回TRUE，失败返回FALSE.
     */
    public function import_sales_count($data)
    {
        try {
            $table = $this->table_name();
            $this->_shard_db()->update_batch($table, $data, 'product_id');
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function get_shipping_product_list($inter_id, $hotel_id = array())
    {
        $db = $this->_shard_db_r('iwide_soma_r');
        $table = $this->_shard_table('soma_catalog_product_package', $inter_id);
        $db->select(array('product_id', 'name'));
        $db->where('inter_id', $inter_id);
        $db->where('type', self::PRODUCT_TYPE_SHIPPING);
        if (is_array($hotel_id) && count($hotel_id) > 0) {
            $db->where_in('hotel_id', $hotel_id);
        }
        $db->where('status', self::STATUS_TRUE);
        $now_date = date('Y-m-d H:i:s');
        $db->where("((`expiration_date` > '{$now_date}' and `validity_date` < '{$now_date}') or `expiration_date` is NULL)");
        $data = $db->get($table)->result_array();
        // echo $db->last_query();die;
        return $data;
    }

    public function get_shipping_product_select_html($product_list)
    {

        $html = '';

        $data = $this->array_to_hash($product_list, 'name', 'product_id');
        $option = array('' => '请选择一个商品') + $data;

        foreach ($option as $k => $v) {
            $html .= "<option value='$k'";
            if ($k == $this->m_get('shipping_product_id')) {
                $html .= " selected='selected'";
            }
            $html .= ">$v</option>";
        }

        return $html;
    }

    //库存修改
    public function update_stock($inter_id, $product_id, $num = 1, $edit_status = self::STOCK_MINUS)
    {
        if (!$inter_id || !$product_id) {
            return FALSE;
        }

        $status = NULL;//不筛选状态
        $product_detail = $this->get_product_package_detail_by_product_id($product_id, $inter_id, NULL);
        if (!$product_detail) {
            return FALSE;
        } else {
            $stock = $product_detail['stock'];
        }

        $product_name = $this->_shard_db($inter_id)->dbprefix($this->table_name($inter_id));
        $data = array();
        if ($edit_status == self::STOCK_MINUS) {
            $data['stock'] = $stock - $num;
            //扣减库存
            // $sql = "update {$product_name} set `stock`=`stock`-{$num} where `product_id`={$product_id} and `inter_id`='{$inter_id}' and `stock`>={$num}";
            return $this->_shard_db($inter_id)
                ->where('product_id', $product_id)
                ->where('inter_id', $inter_id)
                ->where('stock >= ', $num)
                ->update($product_name, $data);
        } elseif ($edit_status == self::STOCK_ADD) {
            $data['stock'] = $stock + $num;
            //增加库存
            // $sql = "update {$product_name} set `stock`=`stock`+{$num} where `product_id`={$product_id} and `inter_id`='{$inter_id}'";
            return $this->_shard_db($inter_id)
                ->where('product_id', $product_id)
                ->where('inter_id', $inter_id)
                ->update($product_name, $data);
        } else {
            return FALSE;
        }

        // $res = $this->_shard_db($inter_id)->query($sql, array(), true);
        // return $res;
    }

    //计划任务，修改下架状态
    public function update_status($inter_id = NULL)
    {
        if (!$inter_id) {
            return FALSE;
        }

        $time = date('Y-m-d H:i:s');
        $status = self::STATUS_UNACTIVE;//下架
        $product_name = $this->_shard_db($inter_id)->dbprefix($this->table_name($inter_id));
        return $this->_shard_db($inter_id)
            ->where('inter_id', $inter_id)
            ->where_in('status', array(self::STATUS_ACTIVE, self::STATUS_HIDDEN))
            ->where('un_validity_date < ', $time)
            ->where('goods_type != ', self::STATUS_FALSE)
            ->update($product_name, array('status' => $status));
    }

    //计划任务，修改下架状态
    public function update_hotel_tel($inter_id = NULL)
    {
        if (!$inter_id) {
            return FALSE;
        }

        $filter = array();
        $product_name = $this->_shard_db($inter_id)->dbprefix($this->table_name($inter_id));
        // $products = $this->get_package_list( $inter_id, $filter );
        $products = $this->_shard_db_r('iwide_soma_r')
            ->where('inter_id', $inter_id)
            ->get($product_name)
            ->result_array();

        if (!$products) {
            return FALSE;
        }

        $productIds = $hotelIds = array();
        $hotel_ids = '';
        foreach ($products as $k => $v) {
            $productIds[$v['product_id']] = $v;
            $hotel_ids .= $v['hotel_id'] . ',';
        }

        if (!$hotel_ids) {
            return FALSE;
        }

        $this->load->model('hotel/hotel_model');
        $hotel_infos = $this->hotel_model->get_hotel_by_ids($inter_id, trim($hotel_ids, ','));
        if (!$hotel_infos) {
            return FALSE;
        }

        foreach ($hotel_infos as $k => $v) {
            $hotelIds[$v['hotel_id']] = $v['tel'];
        }

        foreach ($productIds as $k => $v) {
            if (isset($hotelIds[$v['hotel_id']]) && !$v['hotel_tel'] && '2016-12-26 00:00:00' >= $v['validity_date']) {
                $hotel_tel = $hotelIds[$v['hotel_id']];
                $this->_shard_db($inter_id)
                    ->where('inter_id', $inter_id)
                    ->where('product_id', $v['product_id'])
                    ->update($product_name, array('hotel_tel' => $hotel_tel));
            }
        }

    }

    public function filter($params = array(), $select = array(), $format = 'array')
    {
        $ori_data = parent::filter($params, $select, $format);
        return $this->get_new_backend_grid_data($ori_data);
    }

    public function get_new_backend_grid_data($ori_data)
    {
        if (empty($ori_data)) {
            return $ori_data;
        }
        $p_ids = array();
        foreach ($ori_data['data'] as $row) {
            $p_ids[] = $row['DT_RowId'];
        }
        $p_data = $this->find_all(array('product_id' => $p_ids));

        $fmt_data = array();
        foreach ($p_data as $row) {
            $fmt_data[$row['product_id']] = $row;
        }

        $new_data = $ori_data;
        foreach ($ori_data['data'] as $key => $row) {
            $new_data['data'][$key]['new_info'] = array();
            if (isset($fmt_data[$row['DT_RowId']])) {
                $new_data['data'][$key]['new_info'] = $fmt_data[$row['DT_RowId']];
            }
        }
        return $new_data;
    }

    /**
     * @param array $ids
     * @param string $interId
     * @param string $select
     * @return Array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getByIds(Array $ids, $interId, $select = '*')
    {
        if (empty($ids)) {
            return array();
        }

        $rs = $this->db_conn_read
            ->where(array('inter_id' => $interId))->where_in('product_id', $ids)
            ->select($select)->get($this->table_name($interId))->result_array();
        return $rs;
    }

    /**
     * 商品是否可用
     * @param array $arr
     * @return boolean
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function isAvaliable(Array $arr)
    {

        if (isset($arr['status']) && $arr['status'] == parent::STATUS_FALSE) {
            return false;
        }

        $currentTime = date('Y-m-d H:i:s');

        if (isset($arr['validity_date']) && $arr['validity_date'] > $currentTime) {
            return false;
        }

        if (isset($arr['un_validity_date']) && $arr['un_validity_date'] < $currentTime) {
            return false;
        }

        if( $arr['goods_type'] != self::SPEC_TYPE_TICKET && $arr['date_type'] == self::DATE_TYPE_STATIC ){

            $expireTime = isset( $arr['expiration_date'] ) ? strtotime( $arr['expiration_date'] ) : null;
            if( $expireTime && $expireTime < time() ){
                return false;
            }
        }

        return true;
    }

    /**
     * @param $interId
     * @param string $select
     * @return mixed
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getByInterID($interId, $select = '*')
    {
        $rs = $this->db_conn_read
            ->where(array('inter_id' => $interId))
            ->select($select)->get($this->table_name($interId))->result_array();
        return $rs;
    }

    /**
     * @param int $id
     * @param string $interId
     * @param string $select
     * @return array
     *
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getByID($id, $interId, $select = '*')
    {
        $result = $this->db_conn_read
            ->where(array($this->table_primary_key() => $id))
            ->limit(1)
            ->select($select)->get($this->table_name($interId))->result_array();

        if (empty($result)) {
            return array();
        }
        return $result[0];
    }

    /**
     * @param array $modelArr
     * @return bool|null
     *
     * 商品下架状态判断
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function isOff(Array $modelArr)
    {
        if(empty($modelArr)) {
            return null;
        }

        if($modelArr['status'] == self::STATUS_UNACTIVE) {
            return true;
        }

        $currentTime = time();
        if(strtotime($modelArr['un_validity_date']) < $currentTime) {
            return true;
        }

        if(strtotime($modelArr['validity_date']) > $currentTime) {
            return true;
        }

        if ($modelArr['goods_type'] != self::SPEC_TYPE_TICKET && $modelArr['date_type'] == self::DATE_TYPE_STATIC) {
            $expireTime = strtotime($modelArr['expiration_date']);
            if ($expireTime < $currentTime) {
                return true;
            }
        }

        return false;
    }


    public function load($id)
    {
        $return = parent::load($id);
        if($return)
        {
            $this->loadProductEnInfo();
            $this->loadProductExtraInfo();
        }

        return $return;
    }

    protected $_en_data = array();
    /**
     * Loads a product english information.
     */
    public function loadProductEnInfo()
    {
        $pk = $this->table_primary_key();
        if(!$this->m_get($pk))
        {
            Soma_base::inst()->show_exception('Please Load Model first.');
        }

        $table = $this->en_table_name($this->m_get('inter_id'));
        $result = $this->_shard_db_r('iwide_soma_r')
            ->get_where($table, array('product_id' => $this->m_get($pk)))->row_array();

        if(!empty($result))
        {
            foreach ($result as $key => $value)
            {
                $this->_en_data[$key . '_en'] = $value;
            }
        }
    }

    /**
     * Loads a product extra information.
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function loadProductExtraInfo()
    {
        $pk = $this->table_primary_key();
        if(!$this->m_get($pk))
        {
            Soma_base::inst()->show_exception('Please Load Model first.');
        }

        $table = $this->extra_table_name($this->m_get('inter_id'));
        $result = $this->soma_db_conn_read
            ->get_where($table, array('product_id' => $this->m_get($pk)))->row_array();

        if(!empty($result))
        {
            foreach ($result as $key => $value)
            {
                $this->_data[ $key ] = $value;
            }
        }
    }

    /**
     * 如果从原有的数据里面获取不到信息，则寻找英文数据里面是否有信息
     *
     * @param      <type>  $name   The name
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function m_get($name)
    {
        if(parent::m_get($name) !== null)
        {
            return parent::m_get($name);
        }

        return isset($this->_en_data[$name]) ? $this->_en_data[$name] : null;
    }


    /**
     * 替换产品原字段信息为英文信息
     */
    public function translateToEnInfo()
    {
        if($this->m_get('language') == self::LANG_EN)
        {
            if(empty($this->_en_data))
            {
                $this->loadProductEnInfo();
            }

            foreach ($this->en_fields() as $key)
            {
                if(isset($this->_en_data[$key . '_en']))
                {
                    $this->_data[$key] = $this->_en_data[$key . '_en'];
                }
            }
        }
    }

    /**
     * Gets the product en information list.
     *
     * @param      <type>  $pids      The pids
     * @param      <type>  $inter_id  The inter identifier
     */
    public function getProductEnInfoList($pids, $inter_id = null)
    {
        if(empty($pids))
        {
            return array();
        }
        
        $table = $this->en_table_name($inter_id);
        $result = $this->_shard_db_r('iwide_soma_r')
            ->where_in('product_id', $pids)->get($table)->result_array();

        $fmt_res = array();
        foreach($result as $row)
        {
            $fmt_res[$row['product_id']] = $row;
        }
        return $fmt_res;
    }

    /**
     * @param $pid
     * @return mixed
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getEnInfo($pid)
    {
        $table = $this->en_table_name();
        $product = $this->_shard_db_r('iwide_soma_r')->where('product_id', $pid)->limit(1)->get($table)->result_array();
        if (empty($product)) {
            return array();
        }
        return $product[0];
    }

    /**
     * 追加商品的英文信息
     * @param array $product
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function appendEnInfo(&$product)
    {
        if (empty($product)) {
            return;
        }

        $productEnInfo = $this->getEnInfo($product['product_id']);
        foreach($this->en_fields() as $field)
        {
            $product[$field . '_en'] = empty($productEnInfo) ? '' : $productEnInfo[$field];
        }
    }

    /**
     * @param array $product
     * @param int $settingID
     * @param string $settlement
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function rewiteInfo(&$product, $settingID, $settlement = 'default')
    {
        if (empty($product)) {
            return;
        }

        $this->load->model('soma/Product_specification_setting_model', 'somaProductSpecificationSettingModel');
        $setting = $this->somaProductSpecificationSettingModel->get($this->somaProductSpecificationSettingModel->table_primary_key(), $settingID);

        if (empty($setting)) {
            return;
        }

        if ($setting[0]['product_id'] != $product['product_id']) {
            return;
        }

        if($settlement == 'default') {

            $this->load->model('soma/Product_specification_model', 'somaProductSpecificationModel');
            $product['price_package'] = $setting[0]['spec_price'];
            $product['stock'] = $setting[0]['spec_stock'];
            $product['setting_id'] = $setting[0]['setting_id'];

            $specType = $setting[0]['type'];
            $spec_list = $this->somaProductSpecificationModel->get_spec_list($this->inter_id, $product['product_id'], $specType);
            $spec_list_info = json_decode($spec_list[$specType]['spec_compose'], true);

            $compose = json_decode($setting[0]['setting_spec_compose'], true);
            $setting_compose = current($compose);

            $product_spec_name = array();
            if( isset( $spec_list_info['spec_type']) ) {
                foreach ($spec_list_info['spec_type']  as $key => $type_name) {
                    $product_spec_name[] = $type_name . ':' . $setting_compose['spec_name'][$key];
                }
            }

            if( $specType == self::SPEC_TYPE_SCOPE )
            {
                $product['name'] .= "(" . implode(';', $product_spec_name) . ")";
            } elseif( $specType == self::SPEC_TYPE_TICKET ) {
                $product['setting_date']     = Soma_base::STATUS_TRUE;//这里是新加的字段，如果是时间规格的，那么过期时间就是规格时间
                $product['expiration_date']  = date('Y-m-d 23:59:59', strtotime( $setting_compose['date'] ) );
                $product['name'] .= "(" . $setting_compose['spec_name'][0]. ")";
            }
        }

    }

    /**
     * @param array $arr
     * @param $interId
     * @param string $select
     * @return mixed
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function search(Array $arr, $interId, $select = '*')
    {
        return $this->db_conn_read
            ->where($arr)
            ->select($select)->get($this->table_name($interId))->result_array();
    }

    /**
     * Gets the compose product base information.
     *
     * @param      array  $filter  The filter
     *
     * @return     array  The compose product base information.
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function getComposeProductBaseInfo($filter)
    {
        $this->load->model('soma/Category_package_model', 'c_model');
        $p_tb_name = $this->table_name($filter['inter_id']);
        $c_tb_name = $this->c_model->table_name($filter['inter_id']);
        $p_full_name = $this->db_conn_read->dbprefix($p_tb_name);
        $c_full_name = $this->db_conn_read->dbprefix($c_tb_name);

        $data = array();
        foreach($filter['where_group'] as $where)
        {
            $this->db_conn_read
                ->select('p.product_id, p.type, p.name, p.price_package, p.can_split_use, p.stock, p.use_cnt, p.cat_id, c.cat_name')
                ->from($p_full_name . ' as p')
                ->join($c_full_name . ' as c', 'p.cat_id = c.cat_id', 'left')
                ->where('p.inter_id', $filter['inter_id'])
                ->where('p.hotel_id !=', \App\models\soma\SeparateBilling::MULITPLE_HOTEL_ID);

            if(isset($filter['hotel_id']) && !empty($filter['hotel_id']))
            {
                $this->db_conn_read->where_in('p.hotel_id', $filter['hotel_id']);
            }

            if(isset($filter['name']))
            {
                $this->db_conn_read->like('p.name', $filter['name']);
            }

            foreach($where as $key => $value)
            {
                if(is_array($value))
                {
                    $this->db_conn_read->where_in('p.' . $key, $value);
                }
                else
                {
                    $this->db_conn_read->where('p.' . $key, $value);
                }
            }

            $res = $this->db_conn_read->get()->result_array();

            $fmt_data = array();
            foreach($res as $row)
            {
                $fmt_data[$row['product_id']] = $row;
                if($row['can_split_use'] == Soma_base::STATUS_FALSE)
                {
                    $fmt_data[$row['product_id']]['use_cnt'] = 1;
                }
            }
            $data += $fmt_data;
        }

        $data = array_values($data);
        if(isset($filter['page']))
        {
            $start = $filter['page']['page_size'] * ($filter['page']['page_num'] - 1);
            if($start < 0)
            {
                $start = 0;
            }
            $page_data = array();
            for($i = $start; $i < count($data); $i++)
            {
                $page_data[] = $data[$i];
                if(count($page_data) >= $filter['page']['page_size'])
                {
                    break;
                }
            }
            $data = $page_data;
        }
        return $data;
    }

    /**
     * 检查组合商品的产品ID，规格ID是否存在(因为需要比对数据库，暂时不设检查)
     *
     * @param      array  $main_data  The main data
     * @param      array  $spec_data  The specifier data
     *
     * @return     bool   True
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function checkCombineProducts($main_data, $spec_data)
    {
        return true;
    }

    /**
     * Gets the combine child product information.
     *
     * @return     array  The combine child product information.
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function getCombineChildProductInfo()
    {
        if(!$this->m_get('product_id'))
        {
            return array();
        }

        $this->load->model('soma/Product_package_link_model', 'p_combine_model');
        $base_info = $this->p_combine_model
            ->getCombineChildProductList($this->m_get('product_id'), $this->m_get('inter_id'));
        if(empty($base_info))
        {
            return array();
        }

        $child_pids = $child_spec_ids = array();
        foreach($base_info as $info)
        {
            $child_pids[]   = $info['child_pid'];
            $child_spec_ids[] = $info['spec_id'];
        }

        $child_filter['inter_id'] = $this->m_get('inter_id');
        $child_filter['where_group'][] = array('product_id' => $child_pids);
        $data = $this->getComposeProductBaseInfo($child_filter);
        $child_pinfo = array();
        foreach($data as $row)
        {
            $child_pinfo[$row['product_id']] = $row;
        }

        $this->load->model('soma/Product_specification_setting_model', 'spec_model');
        $spec_info = $this->spec_model->getCombineSpecInfo($child_spec_ids);

        $fmt_info = array();
        foreach($base_info as $row)
        {
            $tmp_row = $row;
            if(isset($child_pinfo[$row['child_pid']]))
            {
                $tmp_row['cat_id']        = $child_pinfo[$row['child_pid']]['cat_id'];
                $tmp_row['cat_name']      = $child_pinfo[$row['child_pid']]['cat_name'];
                $tmp_row['name']          = $child_pinfo[$row['child_pid']]['name'];
                $tmp_row['price_package'] = $child_pinfo[$row['child_pid']]['price_package'];
                $tmp_row['stock']         = $child_pinfo[$row['child_pid']]['stock'];
                $tmp_row['use_cnt']       = $child_pinfo[$row['child_pid']]['use_cnt'];
            }
            if(isset($spec_info[$row['spec_id']]))
            {
                $spec_compose             = json_decode($spec_info[$row['spec_id']]['setting_spec_compose'], true);
                $first_spec_info          = current($spec_compose);
                $tmp_row['name']         .= implode(';', $first_spec_info['spec_name']);
                $tmp_row['price_package'] = $spec_info[$row['spec_id']]['spec_price'];
                $tmp_row['stock']         = $spec_info[$row['spec_id']]['spec_stock'];
            }
            $fmt_info[] = $tmp_row;
        }
        return $fmt_info;
    }

    // 订房套餐相关数据获取方法
    
    /**
     * Gets the hotel package product list.
     *
     * @param      string   $inter_id     The inter identifier
     * @param      array    $product_ids  The product identifiers
     * @param      int      $page_num     The page number
     * @param      int      $page_size    The page size
     * @param      boolean  $is_count     Return total count if True, 0
     *                                    otherwise.
     * @param      int      $status       The status
     *
     * @return     array    The hotel package product list.
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    public function getHotelPackageProductList(
        $inter_id,
        $product_ids = array(),
        $page_num = null,
        $page_size = null,
        $is_count = false,
        $status = null)
    {
        $result = array('data' => array(), 'total' => 0);

        if(!$this->soma_db_conn_read)
        {
            $this->load->somaDatabaseRead($this->db_soma_read);
        }

        $tb_name = $this->table_name_r($inter_id);
        $this->soma_db_conn_read->select('*')->from($tb_name);
        $this->soma_db_conn_read->where('inter_id', $inter_id);
        $this->soma_db_conn_read->where('can_wx_booking', self::CAN_PASS_TO_HOTEL_MODEL);

        if($status !== null)
        {
            $this->soma_db_conn_read->where('status', $status);
        }

        if(!empty($product_ids) && is_array($product_ids))
        {
            if(!empty($product_ids['in']))
            {
                $this->soma_db_conn_read->where_in('product_id', $product_ids['in']);
            }
            if(!empty($product_ids['not_in']))
            {
                $this->soma_db_conn_read->where_not_in('product_id', $product_ids['not_in']);
            }
        }

        if($is_count)
        {
            $result['total'] = $this->soma_db_conn_read->count_all_results('', false);
        }

        if(is_int($page_num) && $page_num > 0 
            && is_int($page_size) && $page_size > 0)
        {
            $this->soma_db_conn_read->limit($page_size, $page_size * ($page_num - 1));
        }

        $this->soma_db_conn_read->order_by('product_id', 'DESC');

        $result['data'] = $this->soma_db_conn_read->get()->result_array();

        return $result;
    }

    /**
     * 获取推荐位产品
     *
     * @param      string   $inter_id  公众号
     * @param      integer  $limit     获取数量
     *
     * @return     array    推荐商品信息
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function getRecommendedProducts($inter_id, $limit = 2)
    {

        $tb_name = $this->table_name_r($inter_id);
        $this->soma_db_conn_read->select('*')->from($tb_name);

        $this->soma_db_conn_read->where('inter_id', $inter_id);
        $this->soma_db_conn_read->where('status', self::STATUS_ACTIVE);
        $this->soma_db_conn_read->where('validity_date <=', date('Y-m-d H:i:s'));
        $this->soma_db_conn_read->where('un_validity_date >=', date('Y-m-d H:i:s'));

        // 销量有限，其次优先级，再次新增优先
        $this->soma_db_conn_read->order_by('sales_cnt', 'DESC');
        $this->soma_db_conn_read->order_by('sort', 'DESC');
        $this->soma_db_conn_read->order_by('product_id', 'DESC');

        $this->soma_db_conn_read->limit($limit);

        $result = $this->soma_db_conn_read->get()->result_array();

        $products = array();
        foreach ($result as $row) {
            $products[ $row['product_id'] ] = $row;
        }

        // var_dump($products);exit;

        return $products;
    }


    /**
     * 获取套票列表
     * @param array $params
     * @author: liguanglong  <liguanglong@mofly.cn>
     */
    public function getProductsList($params = []){

        $field = [];
        $value = [];
        $orderBy = '';
        $page = isset($params['page']) ? ($params['page'] <= 0 ? 1 : $params['page']) : 1;
        $limit = isset($params['page_size']) ? ($params['page_size'] <= 0 ? 20 : $params['page_size']) : 20;
        $offset = ($page - 1) * $limit;

        if(isset($params['word']) && $params['word']){
            $field[] = "and name like";
            $value[] = "%".$params['word']."%";
        }
        if(isset($params['status']) && $params['status']){
            $field[] = "and status =";
            $value[] = $params['status'];
        }
        if(isset($params['cat']) && $params['cat']){
            $field[] = "and cat_id =";
            $value[] = $params['cat'];
        }
        if(isset($params['inter_id']) && $params['inter_id']){
            $field[] = "and inter_id =";
            $value[] = $params['inter_id'];
        }
        if(isset($params['is_hide']) && $params['is_hide']){
            $field[] = "and is_hide =";
            $value[] = $params['is_hide'];
        }
        if(!empty($params['hotel_id'])){
            $field[] = "and hotel_id in";
            $value[] = "(" . implode(',', $params['hotel_id']) . ")";
        }

        if(isset($params['sortid']) && $params['sortid']){
            $orderBy .= "product_id ".$params['sortid'].',';
        }
        if(isset($params['sortprice']) && $params['sortprice']){
            $orderBy .= "price_package ".$params['sortprice'].',';
        }
        if(isset($params['sortstock']) && $params['sortstock']){
            $orderBy .= "stock ".$params['sortstock'].',';
        }
        if(isset($params['sortdate']) && $params['sortdate']){
            $orderBy .= "validity_date ".$params['sortdate'].',';
        }
        if(isset($params['sort']) && $params['sort']){
            $orderBy .= "sort ".$params['sort'].',';
        }

        $return = [
            'product_id',
            'inter_id',
            'cat_id',
            'goods_type',
            'face_img',
            'name',
            'price_market',
            'price_package',
            'stock',
            'is_hide',
            'validity_date',
            'sort',
            'status',
            'type'
        ];

        $options = [
            'limit' => $limit,
            'offset' => $offset,
            'page' => $page,
            'orderBy' => $orderBy.$this->table_primary_key().' desc',
        ];

        $result = $this->paginate($field, $value, $return, $options);

        if(count($result['data'])){

            $this->load->model('soma/category_package_model', 'categoryPackageModel');
            $categoryPackageModel = $this->categoryPackageModel;

            $catList = $categoryPackageModel->get(
                    ['cat_id'],
                    [array_column($result['data'], 'cat_id')],
                    ['cat_id', 'cat_name'],
                    ['limit' => count($result['data'])]
                );

            foreach($result['data'] as $key => &$val){
                //商品分类
                if(count($catList)){
                    foreach($catList as $vale){
                        if($vale['cat_id'] == $val['cat_id']){
                            $val['cat_id'] = $vale['cat_name'];
                            break;
                        }
                    }
                }
                //首页是否显示
                foreach($this->get_status_yes_label() as $item => $value){
                    if($val['is_hide'] == $item){
                        $val['is_hide'] = $value;
                        break;
                    }
                }
                //状态
                foreach($this->get_status_label() as $item => $value){
                    if($val['status'] == $item){
                        $val['status'] = $value;
                        break;
                    }
                }
                //商品类型
                foreach($this->get_product_type_label() as $item => $value){
                    //替换原来的goods_type，实际显示type的值
                    if($val['type'] == $item){
                        $val['goods_type'] = $value;
                        break;
                    }
                }
            }

        }

        return $result;
    }
}
