<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model_Soma extends MY_Model
{

    const STATUS_TRUE = 1;
    const STATUS_FALSE = 2;

    const STATUS_CAN_YES = 1;//能
    const STATUS_CAN_NO = 2;//否

    const SETTLE_DEFAULT = 'default';
    const SETTLE_GROUPON = 'groupon';
    const SETTLE_KILLSEC = 'killsec';
    const SETTLE_WHOLESALE = 'wholesale';
    const SETTLE_VOUCHER = 'voucher';
    const SETTLE_HOTEL_PACKAGE = 'hotel';

    const PRODUCT_TYPE_DEFAULT = 1;
    const PRODUCT_TYPE_PRIVILEGES_VOUCHER = 2;
    const PRODUCT_TYPE_BALANCE = 3;
    const PRODUCT_TYPE_SHIPPING = 4;
    const PRODUCT_TYPE_POINT = 5;

    const OPEN_READING_WRITING_SEPARATION = true;//是否打开读写分离

    // 添加7天退款，随时退款，兼容旧数据，2为不能退款，1为7天退款
    // 订单模型与产品模型共用，所以写在父类
    const CAN_REFUND_STATUS_FAIL = 2;
    const CAN_REFUND_STATUS_SEVEN = 1;
    const CAN_REFUND_STATUS_ANY_TIME = 3;

    public $langDir = null;

    public $show_name = '储值';//例如，雅斯特酒店不叫储值，叫雅币

    public function __construct()
    {
        parent::__construct();

        $interId = $this->input->get('id', true);
        $lang_cookie_key = 'lang_' . $interId;
        $lang_cookie = $this->input->cookie($lang_cookie_key, true);
        $lang_input = $this->input->get('lang', true);
        $lang = $lang_input && in_array($lang_input, array('english', 'chinese')) ? $lang_input : ($lang_cookie ? $lang_cookie : 'chinese');

        $this->lang->load('soma_lang', $lang);

        //例如，雅斯特酒店不叫储值，叫雅币
        if( $interId == 'a472731996' )
        {
            $this->show_name = '雅币';
        }

        //雅斯特需要把储值两个字改为雅币
        if( $interId == 'a472731996' && $lang == 'chinese' )
        {
            $this->lang->language['stored_price']               = "{$this->show_name}价";
            $this->lang->language['soted_value_buy']            = "{$this->show_name}购买";
            $this->lang->language['stored_balance']             = "{$this->show_name}余额";
            $this->lang->language['store_password']             = "{$this->show_name}密码";
            $this->lang->language['balance_note_enough_tip']    = "您的{$this->show_name}余额不足，不能完成下单";
            $this->lang->language['stored_value']               = "{$this->show_name}";
            $this->lang->language['refund_success_tip']         = "小提示：退款成功后，使用的积分、{$this->show_name}将自动返还至您的账户，购买获得的积分将被扣除";
            $this->lang->language['use_ponint_coupon_fail_tip'] = "抱歉，暂无法使用优惠券、积分、{$this->show_name}，请稍后再试~";
            $this->lang->language['stord_fail_tip']             = "{$this->show_name}使用失败";
        }

        $this->langDir = $lang;

        /**
         * @author renshuai
         * 注意isset 的用法
         * @link http://php.net/manual/zh/function.isset.php#51113
         */
        $CI =& get_instance();
        if(!property_exists($CI, 'soma_db_conn') || empty($CI->soma_db_conn))
        {
            $this->load->somaDatabase($this->db_soma);
        }
        if(!property_exists($CI, 'soma_db_conn_read') || empty($CI->soma_db_conn_read))
        {
            $this->load->somaDatabaseRead($this->db_soma_read);
        }
        $this->soma_db_conn && $this->setDbConn($this->soma_db_conn);
        $this->soma_db_conn_read && $this->setDbConnRead($this->soma_db_conn_read);

    }

    /**
     * @var null|CI_DB_query_builder $db_conn
     */
    protected $db_conn = null;
    /**
     * * @var null|CI_DB_query_builder $db_conn_read
     */
    protected $db_conn_read = null;

    /**
     * @param CI_DB_query_builder $db_conn
     */
    public function setDbConn($db_conn)
    {
        if (empty($this->db_conn)) {
            $this->db_conn = $db_conn;
        }
    }

    /**
     * @param CI_DB_query_builder $db_conn_read
     */
    public function setDbConnRead($db_conn_read)
    {
        if (empty($this->db_conn_read)) {
            $this->db_conn_read = $db_conn_read;
        }
    }


    public function get_refund_status_label()
    {
        return array(
            self::CAN_REFUND_STATUS_FAIL => '不能退款',
            self::CAN_REFUND_STATUS_SEVEN => '7天退款',
            self::CAN_REFUND_STATUS_ANY_TIME => '随时退款',
        );
    }

    public function get_settle_label()
    {
        return array(
            self::SETTLE_DEFAULT => '普通购买',
            self::SETTLE_GROUPON => '拼团购买',
            self::SETTLE_KILLSEC => '秒杀购买',
            self::SETTLE_WHOLESALE => '大客户预订',
            self::SETTLE_VOUCHER => '礼品卡券',
            self::SETTLE_HOTEL_PACKAGE => '订房套餐',
        );
    }

    public function get_product_type_label()
    {
        return array(
            self::PRODUCT_TYPE_DEFAULT => '套票类',
            self::PRODUCT_TYPE_PRIVILEGES_VOUCHER => '特权券',
            self::PRODUCT_TYPE_BALANCE => '储值商品',
            self::PRODUCT_TYPE_SHIPPING => '运费补差',
            self::PRODUCT_TYPE_POINT => '积分商品',
        );
    }

    public function get_status_can_label()
    {
        return array(
            self::STATUS_CAN_YES => '能',
            self::STATUS_CAN_NO => '否',
        );
    }

    public function get_status_yes_label()
    {
        return array(
            self::STATUS_CAN_YES => '是',
            self::STATUS_CAN_NO => '否',
        );
    }

    const BUSINESS_PACKAGE = 'package';

    //const BUSINESS_PACKAGE = '';

    public function get_business_type()
    {
        return array(
            self::BUSINESS_PACKAGE => '套票',
        );
    }

    /**
     * 定义需要生成的数据表
     * @return array
     */
    public function shard_tables()
    {
        return array(
            'soma_asset_customer', 'soma_asset_item_package',
            'soma_sales_order', 'soma_sales_order_item_package',
            'soma_consumer_order', 'soma_consumer_order_item_package',
            'soma_gift_order', 'soma_gift_order_item_package', 'soma_gift_order_receiver',
        );
    }

    /**
     * @param null $inter_id
     * @param null $db_active_group
     * @return CI_DB_mysqli_driver|CI_DB_query_builder
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function _shard_db($inter_id = NULL, $db_active_group = null)
    {
        $inter_id = empty($inter_id) ? $this->current_inter_id : $inter_id;
        $db_active_group = empty($db_active_group) ? $this->db_soma : $db_active_group;

        $shard_config = $this->db_shard_config ? $this->db_shard_config : array();
        if ($inter_id && array_key_exists($inter_id, $shard_config)) {
            $db_active_group = $shard_config[$inter_id]['*']['db_resource'];
        }
        return $this->_db($db_active_group);
    }

    /**
     * @param null|string $db_active_group
     * @return CI_DB_mysqli_driver|CI_DB_query_builder
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function _shard_db_r($db_active_group = NULL)
    {
        $db_active_group = empty($db_active_group) ? $this->db_soma_read : $db_active_group;

        if (self::OPEN_READING_WRITING_SEPARATION) {
            return $this->_shard_db(null, $db_active_group);
        } else {
            return $this->_shard_db();
        }
    }

    /**
     * 加载分片所用数据库后缀
     * @author libinyan@mofly.cn
     * @param string $basename
     * @param string $inter_id
     *
     * @return string
     */
    public function _shard_table($basename, $inter_id = NULL)
    {
        $shard_config = $this->db_shard_config;

        if (in_array($basename, $this->shard_tables()) && isset($shard_config['*']['table_suffix'])) {
            $table_suffix = $shard_config['*']['table_suffix'];
            return $basename . $table_suffix;
        }
        return $basename;
    }

    /**
     *
     * @deprecated
     *
     * @param $basename
     * @param null $inter_id
     * @return string
     *
     */
    public function _shard_table_r($basename, $inter_id = NULL)
    {
        if (self::OPEN_READING_WRITING_SEPARATION) {
            return $basename;
        } else {
            return $this->_shard_table($basename, $inter_id);
        }
    }

    /**
     * @param null $db_active_group
     * @return CI_DB_mysqli_driver|CI_DB_query_builder
     * @author renshuai  <renshuai@mofly.cn>  修改
     */
    protected function _db($db_active_group = NULL)
    {
//        $select = $select ? $select : $this->db_soma;
//
//        if (!isset($this->db_resource[$select])) {
//            $this->db_resource[$select] = $this->load->database($select, TRUE);
//        }
//        return $this->db_resource[$select];

        //这两个数据库连接在soma的父类控制器来建立的
        if ($db_active_group == $this->db_soma_read) {
            if (!$this->soma_db_conn_read) {
                $this->load->somaDatabaseRead($this->db_soma_read);
            }
            return $this->soma_db_conn_read;
        } elseif($db_active_group == $this->db_write) {
            return $this->load->database($db_active_group, true);
        } elseif($db_active_group == $this->db_read) {
            return $this->load->database($db_active_group, true);
        } else {
            if (!$this->soma_db_conn) {
                $this->load->somaDatabase($this->db_soma);
            }
            return $this->soma_db_conn;
        }
    }

    protected function _dbforge($select = NULL)
    {
        $select = $select ? $select : $this->db_soma;
        if (!isset($this->db_dbforge[$select])) {
            $this->db_dbforge[$select] = $this->load->dbforge($select, TRUE);
        }
        return $this->db_dbforge[$select];
    }

    protected function _dbutil($select = NULL)
    {
        $select = $select ? $select : $this->db_soma;
        if (!isset($this->db_dbutil[$select])) {
            $this->db_dbutil[$select] = $this->load->dbutil($select, TRUE);
        }
        return $this->db_dbutil[$select];
    }

    public function write_log($content, $tmpfile)
    {
        //echo $tmpfile;die;
        $CI = &get_instance();
        $ip = $CI->input->ip_address();
        $fp = fopen($tmpfile, 'a');

        $content = str_repeat('-', 40) . "\n[" . date('Y-m-d H:i:s') . ']'
            . "\n" . $ip . "\n" . $content . "\n";
        fwrite($fp, $content);
        fclose($fp);
    }

    /**
     * 加载缓存组件
     * @see MY_Controller::_load_cache()
     */
    protected function _load_cache($name = 'Cache')
    {
        $success = Soma_base::inst()->check_cache_redis();
        if (!$success) {
            //redis故障关闭cache
            Soma_base::inst()->show_exception('当前访问用户过多，请稍后再试！', TRUE);
        }
        if (!$name || $name == 'cache') //不能为小写cache
        {
            $name = 'Cache';
        }

        $this->load->driver('cache',
            array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'soma_'),
            $name
        );
        return $this->$name;
    }

    /**
     * 获取客户的联系信息
     * @param array $filter
     * @return Ambigous <boolean, unknown>
     */
    public function get_customer_contact($filter, $return_all = FALSE)
    {
        $db = $this->_shard_db_r('iwide_soma_r');
        $table = $this->_shard_table('soma_customer_contact');
        if (count($filter) > 0) {
            foreach ($filter as $k => $v) {
                is_array($v) ? $db->where_in($k, $v) : $db->where($k, $v);
            }
        }
        if ($return_all) {
            $data = $db->order_by('create_time DESC')->get($table)->result_array();
            //echo $this->_shard_db()->last_query();
            return $data;
        } else {
            $data = $db->order_by('create_time DESC')->limit(1)->get($table)->result_array();
            //echo $this->_shard_db()->last_query();
            return isset($data[0]) ? $data[0] : FALSE;
        }
    }

    /**
     * 更新客户的联系信息
     * @param array $filter
     * @return Ambigous <boolean, unknown>
     */
    public function update_customer_contact($filter, $data)
    {
        $table = $this->_shard_table('soma_customer_contact');
        if (count($filter) > 0) {
            foreach ($filter as $k => $v) {
                is_array($v) ? $this->_shard_db()->where_in($k, $v) : $this->_shard_db()->where($k, $v);
            }
        }
        $data = $this->_addslashes($data);
        return $this->_shard_db()->update($table, $data);

    }

    /**
     * $filter 以防重复生成记录
     */
    public function save_customer_contact($data, $filter = array())
    {
        $table = $this->_shard_table('soma_customer_contact');
        if (count($filter) > 0) {
            foreach ($filter as $k => $v) {
                is_array($v) ? $this->_shard_db()->where_in($k, $v) : $this->_shard_db()->where($k, $v);
            }
            $find = $this->_shard_db()->get($table)->result_array();
        } else {
            $find = NULL;
        }

        if (count($find) == 0) {
            $data = $this->_addslashes($data);
            $result = $this->_shard_db()->insert($table, $data);

        } else {
            foreach ($filter as $k => $v) {
                is_array($v) ? $this->_shard_db()->where_in($k, $v) : $this->_shard_db()->where($k, $v);
            }
            $result = $this->_shard_db()->update($table, $data);
            //$result= FALSE; 
        }
        return $result;
    }

    //TODO 这个有点。。。
    public function virtual_field()
    {
        return array();
    }

    public function get_field_config($type = 'grid')
    {
        $data = array();
        if ($type == 'grid') {
            $show = $this->grid_fields();
            //grid多选状态必须有主键
            array_unshift($show, $this->table_primary_key());

        } else {
            //有时需要取数据库以外的字段，如 密码确认字段，在模板手动添加
            $show = $this->_shard_db()->list_fields($this->table_name());
        }

        $virtual_field = $this->virtual_field();
        $show = array_merge($show, $virtual_field);

        $fields = $this->attribute_labels();

        $fields_ui = $this->attribute_ui();
        foreach ($show as $v) {
            if (!isset($fields[$v]) || !isset($fields_ui[$v])) {
                continue;
            }

            $data[$v]['label'] = $fields[$v];

            if ($type == 'grid') {
                //grid所需配置信息
                if (array_key_exists($v, $fields_ui)) {
                    $data[$v]['grid_ui'] = isset($fields_ui[$v]['grid_ui']) ? $fields_ui[$v]['grid_ui'] : '';
                    $data[$v]['grid_width'] = isset($fields_ui[$v]['grid_width']) ? $fields_ui[$v]['grid_width'] : "";
                    $data[$v]['grid_function'] = isset($fields_ui[$v]['grid_function']) ? $fields_ui[$v]['grid_function'] : FALSE;
                    $data[$v]['function'] = isset($fields_ui[$v]['function']) ? $fields_ui[$v]['function'] : FALSE;
                    $data[$v]['type'] = isset($fields_ui[$v]['type']) ? $fields_ui[$v]['type'] : 'text';
                    if ($data[$v]['type'] == 'combobox') {
                        $data[$v]['select'] = $fields_ui[$v]['select'];
                    }
                }

            } else {
                if ($type == 'form') {
                    //form所需配置信息
                    $data[$v]['js_config'] = isset($fields_ui[$v]['js_config']) ? $fields_ui[$v]['js_config'] : '';
                    $data[$v]['input_unit'] = isset($fields_ui[$v]['input_unit']) ? "<div class='input-group-addon'>{$fields_ui[$v]['input_unit']}</div>" : '';
                    $data[$v]['form_ui'] = isset($fields_ui[$v]['form_ui']) ? $fields_ui[$v]['form_ui'] : '';
                    $data[$v]['form_tips'] = !empty($fields_ui[$v]['form_tips']) ? $fields_ui[$v]['form_tips'] : NULL;
                    $data[$v]['form_default'] = isset($fields_ui[$v]['form_default']) ? $fields_ui[$v]['form_default'] : NULL;
                    $data[$v]['form_hide'] = isset($fields_ui[$v]['form_hide']) ? $fields_ui[$v]['form_hide'] : FALSE;
                    $data[$v]['function'] = isset($fields_ui[$v]['function']) ? $fields_ui[$v]['function'] : FALSE;
                    $data[$v]['type'] = isset($fields_ui[$v]['type']) ? $fields_ui[$v]['type'] : 'text';
                    if ($data[$v]['type'] == 'combobox') {
                        $data[$v]['select'] = $fields_ui[$v]['select'];
                    }
                    if (isset($fields_ui[$v]['form_type'])) {
                        $data[$v]['type'] = $fields_ui[$v]['form_type'];
                    }
                }
            }
        }
        return $data;
    }

    public function get_hotels_hash()
    {
        $this->_init_admin_hotels();
        $publics = $hotels = array();
        $filter = $filterH = NULL;

        if ($this->_admin_inter_id == FULL_ACCESS) {
            $filter = array();
        } else {
            if ($this->_admin_inter_id) {
                $filter = array('inter_id' => $this->_admin_inter_id);
            }
        }
        if (is_array($filter)) {
            $this->load->model('wx/publics_model');
            $publics = $this->publics_model->get_public_hash($filter);
            $publics = $this->publics_model->array_to_hash($publics, 'name', 'inter_id');
            //$publics= $publics+ array(FULL_ACCESS=>'-所有公众号-');
        }

        if ($this->_admin_hotels == FULL_ACCESS) {
            $filterH = array();
        } else {
            if (is_array($this->_admin_hotels) && count($this->_admin_hotels) > 0) {
                $filterH = array('inter_id' => $this->_admin_inter_id, 'hotel_id' => $this->_admin_hotels);
            } else {
                $filterH = array('inter_id' => $this->_admin_inter_id);
            }
        }

        if ($publics && is_array($filterH)) {
            $this->load->model('hotel/hotel_model');
            $hotels = $this->hotel_model->get_hotel_hash($filterH);
            $hotels = $this->hotel_model->array_to_hash($hotels, 'name', 'hotel_id');
            //$hotels= $hotels+ array('0'=>'-不限定-');
        }
        return array('filter' => $filter, 'filterH' => $filterH, 'publics' => $publics, 'hotels' => $hotels);
    }

    /** --------------- 共用函数：luguihong@mofly.cn ------------------- **/
    /**
     * _m_save方法，目的是适应自分配主键值
     * 保存模型数据，3种用法：
     *     1，实例化、load方法之后，m_set多个变量之后 m_save保存[不传入参数]
     *     2，实例化、load方法之后，直接m_save保存[需传入参数]
     *     3，实例化后直接 m_save保存[需传入参数]，用于插入新数据
     * @param boolean $update 新增且主键字段为手工生成时，update=FALSE -- 2015-12-07 ounianfeng --
     * @return boolean
     */
    public function _m_save($data = null)
    {
        parent::m_save($data, FALSE);
        //不能使用insert_id()来判断，不是自增的返回0
        return $this->_shard_db()->affected_rows();
    }

    public function find_all($where = array(), $sort = NULL, $limit = NULL, $select = '*')
    {
        $db = $this->_shard_db_r('iwide_soma_r');
        $table = $this->table_name();
        $db->select(" {$select} ")
            ->order_by($sort)
            ->limit($limit);

        $dbfields = array_values($fields = $this->_shard_db_r('iwide_soma_r')->list_fields($table));
        foreach ($where as $k => $v) {
            if (in_array($k, $dbfields) && is_array($v)) {
                if (!empty($v)) {
                    $db->where_in($k, $v);
                }
            } else {
                if (in_array($k, $dbfields)) {
                    $db->where($k, $v);
                }
            }
        }
        $result = $db->get($table)->result_array();

        return $result;
    }

    //筛选属于自己的资产，群发接受的礼物，根据gift_id获取的资产有其他人的
    public function filter_items_by_openid($items, $openid)
    {
        if ($items && $openid && is_array($items)) {
            $data = array();
            foreach ($items as $k => $v) {
                if (isset($v['openid']) && $v['openid'] == $openid) {
                    $data[] = $v;
                }
            }
            $items = $data;
        }

        return $items;
    }

    //转义
    public function _addslashes($str)
    {
        if ($str) {
            if (is_string($str)) {
                return addslashes($str);
            } elseif (is_array($str)) {
                $data = array();
                foreach ($str as $key => $value) {
                    $data[$key] = addslashes($value);
                }
                return $data;
            }
        }

        return $str;
    }

    /**
     * 获取中心平台公众号ID
     */
    protected function get_center_inter_id()
    {
        if (isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV'] == 'production') {
            // return 'a429262688';a476864535
            return 'a476864535';
        } else {
            // 测试环境中心平台公众号
            return "a471258436";
        }
    }

    /** --------------- 共用函数：zhangyi@mofly.cn ------------- **/

    /**
     * 标准签名函数
     * @author libinyan@mofly.cn
     * @param array $params
     * @return string
     */
    public function get_redis_sign(array $params, $fields = array())
    {
        $key = config_item('encryption_key');
        foreach ($params as $k => $v) {
            if (in_array($k, $fields)) {
                unset($params[$k]);
            } elseif (!$v) {
                unset($params[$k]);
            }
        }
        ksort($params);
        $string = http_build_query($params, false) . "&key=" . $key;
        return strtoupper(md5($string));
    }

    public function wx_out_trade_no_encode($order_id, $settlement, $business = 'package')
    {
        $char = '';
        if (defined('PROJECT_AREA') && PROJECT_AREA == 'mooncake') {
            $char .= 'M';
        } else {
            switch ($business) {
                case self::BUSINESS_PACKAGE:
                default:
                    $char .= 'P';
                    break;
            }
        }
        switch ($settlement) {
            case self::SETTLE_DEFAULT:
                $char .= 'D';
                break;
            case self::SETTLE_GROUPON:
                $char .= 'G';
                break;
            case self::SETTLE_KILLSEC:
                $char .= 'K';
                break;
            case self::SETTLE_WHOLESALE:
                $char .= 'W';
                break;
            default:
                $char .= '_';
                break;
        }
        return $char . $order_id;
    }

    public function wx_out_trade_no_decode($out_trade_no)
    {
        return substr($out_trade_no, -10);
    }

    /**
     * Gets the redis instance.
     *
     * @param      string $select The select
     *
     * @return     Redis|null  The redis instance.
     */
    public function get_redis_instance($select = 'soma_redis')
    {
        $this->load->library('Redis_selector');
        if ($redis = $this->redis_selector->get_soma_redis($select)) {
            return $redis;
        }
        return null;
    }

    //todo 没用吧～
    public function sales_order_table_name($inter_id = NULL)
    {
        return $this->_shard_table("soma_sales_order", $inter_id);
    }

    //检查订单是否消费完毕 20170116 luguihong $model是为了事务一致性
    public function change_order_consumer_status($model, $inter_id, $business, $order_ids, $consumer_qty = 1)
    {
        $surplus = 0;//剩余数量，包括待发送，赠送中，接受中的
        //标记订单的 consumer_status，锁定不可退款
        if ($order_ids) {
            $CI = &get_instance();
            $CI->load->model('soma/Sales_order_model');

            $over = array();
            $not_over = array();
            foreach ($order_ids as $k => $v) {
                $asset_items = $this->Sales_order_model->load($v)->get_order_asset($business, $inter_id);
                $giftIds = $orderIds = array();
                if (isset($asset_items['items']) && !empty($asset_items['items'])) {

                    // $CI = & get_instance();
                    $CI->load->model('soma/Gift_order_model');

                    //待发送，赠送中，接受中
                    $status_array = array(Gift_order_model::STATUS_PENDING, Gift_order_model::STATUS_GIFTING, Gift_order_model::STATUS_GETTING);

                    //如果是赠送中的了？20170111 luguihong 已经赠送的不管，只要购买人能用数量为0就是消费完毕
                    foreach ($asset_items['items'] as $sk => $sv) {
                        if (isset($sv['qty']) && !empty($sv['qty']) && empty($sv['gift_id'])) {
                            $surplus += $sv['qty'];
                        }

                        if (isset($sv['gift_id']) && !empty($sv['gift_id'])) {
                            $giftIds[] = $sv['gift_id'];
                        } else {
                            $orderIds[] = $sv['order_id'];
                        }
                    }

                    if ($giftIds) {
                        // $gift_gifts = $this->Gift_order_model->get_order_list($business, $inter_id, array( 'send_order_id'=>$giftIds, 'send_from'=>Soma_base::STATUS_FALSE, 'status'=>$status_array ) );
                        // // var_dump( count($gift_gifts) );
                        // if( $gift_gifts ){
                        //  $surplus += count( $gift_gifts );
                        // }
                    }

                    if ($orderIds) {
                        $order_gifts = $this->Gift_order_model->get_order_list($business, $inter_id, array('send_order_id' => $orderIds, 'send_from' => Soma_base::STATUS_TRUE, 'status' => $status_array));
                        // var_dump( count($order_gifts) );
                        if ($order_gifts) {
                            $surplus += count($order_gifts);
                        }
                    }
                }

                if (!$surplus || $surplus < 1 || $surplus - $consumer_qty < 1) {
                    //消费完毕
                    $over[] = $v;
                } else {
                    $not_over[] = $v;
                }
            }
// var_dump( $over, $not_over );die;
            $sales_order_table = $this->sales_order_table_name($inter_id);

            if ($over) {
                $model->_shard_db($inter_id)->where_in('order_id', $over)
                    ->update($sales_order_table, array('consume_status' => Sales_order_model::CONSUME_ALL));
            }

            if ($not_over) {
                $model->_shard_db($inter_id)->where_in('order_id', $not_over)
                    ->update($sales_order_table, array('consume_status' => Sales_order_model::CONSUME_PART));
            }

        }

    }


    /**
     *
     * 不想用limit的话， options array里的limit key 传null！！！！！！
     * 233333
     *
     *
     * @param string|array $field etc 'name !=' ; 'id <'
     * @param string|array $value
     * @param string $returnFields
     * @param array $options
     * @return array|string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function get($field, $value, $returnFields = '*', $options = array())
    {
        $defaultOptions = array(
            'limit' => 1,
            'offset' => 0,
            'orderBy' => $this->table_primary_key() . ' desc',
            'table_name' => $this->table_name(),
            'debug' => false
        );

        if (empty($options)) {
            $options = $defaultOptions;
        } else {
            $options = array_merge($defaultOptions, $options);
        }

        $query = null;
        if (is_array($field) && is_array($value)) {

            $whereInParams = array();

            $params = array_combine($field, $value);
            foreach ($params as $key => $val) {

                if (is_array($val)) {
                    unset($params[$key]);

                    $whereInParams[$key] = $val;
                }

            }

            if (!empty($params)) {
                $query = $this->soma_db_conn_read->where($params);
            }

            if ($whereInParams) {

                foreach ($whereInParams as $key => $val) {
                    $query = $this->soma_db_conn_read->where_in($key, $val);
                }

            }

        } elseif (is_string($field) && is_array($value)) {

            //单个字段拉取数据
            $query = $this->soma_db_conn_read->where_in($field, $value);

        } else {

            //单个字段拉取数据
            $params = array(
                $field => $value
            );

            $query = $this->soma_db_conn_read->where($params);
        }

        /**
         * @var $limit
         * @var $offset
         * @var $orderBy
         * @var $debug
         * @var $table_name
         */
        extract($options, EXTR_OVERWRITE);

        if ($debug) {
            $result = $query->select($returnFields)->order_by($orderBy)->limit($limit, $offset)->get_compiled_select($table_name);
        } else {
            $result = $query->select($returnFields)->order_by($orderBy)->limit($limit, $offset)->get($table_name)->result_array();
        }

        return $result;
    }


    /**
     * 分页
     * @param $field
     * @param $value
     * @param string $returnFields
     * @param array $options
     * @return mixed
     * @author: liguanglong  <liguanglong@mofly.cn>
     */
    public function paginate($field, $value, $returnFields = '*', $options = array())
    {

        $defaultOptions = array(
            'limit' => 1,
            'offset' => 0,
            'page' => 1,
            'orderBy' => $this->table_primary_key() . ' desc',
            'table_name' => $this->table_name(),
            'debug' => false
        );

        if(empty($options)){
            $options = $defaultOptions;
        }
        else{
            $options = array_merge($defaultOptions, $options);
        }

        /**
         * @var $limit
         * @var $offset
         * @var $orderBy
         * @var $debug
         * @var $table_name
         */
        extract($options, EXTR_OVERWRITE);

        $table_name = $this->soma_db_conn_read->dbprefix($table_name);

        $select  = '';
        $where   = ' where 1 = 1 ';
        $limit   = ' limit '.$options['offset'].', '.$options['limit'];
        $orderBy = ' order by '.$orderBy;
        if(is_array($returnFields) && count($returnFields)){
            $select .= 'select '.implode(',', $returnFields).' from '.$table_name;
        }
        else{
            $select .= ' select '.$returnFields.' from '.$table_name;
        }

        if(is_array($field) && is_array($value) && count($field) && count($value)){
            foreach(array_combine($field, $value) as $key => $val){
                if(is_array($val) && count($val)){
                    $val = "'".implode("','", $val )."'";
                }
                else if(!$val){
                    continue;
                }

                if(substr($key, -3) == ' in'
                    || substr($val, -1) == ')'){
                    $where .= " $key $val ";
                }else{
                    $where .= " $key '$val' ";
                }
            }
        }
// echo $select . $where . $orderBy . $limit;exit;
        $result = $this->soma_db_conn_read->query($select.$where.$orderBy.$limit)->result_array();
        $total  = $this->soma_db_conn_read->query('select count(*) as page_count from '.$table_name.$where)->result_array();

        return [
           'total' => (int)$total[0]['page_count'],
           'page_size' => (int)$options['limit'],
           'page_num' => (int)$options['page'],
           'data' => $result
        ];
    }


    /**
     * 根据主键给某个字段自增
     * @param $id
     * @param $filed
     * @param $count
     * @return bool
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function increase($id, $filed, $count)
    {
        return $this->soma_db_conn
            ->set($filed, "$filed + $count", false)
            ->where($this->table_primary_key(), $id)
            ->update($this->table_name());
    }

    /**
     * @param $id
     * @param $filed
     * @param $count
     * @return bool
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function decrease($id, $filed, $count)
    {
        $primaryKey = $this->table_primary_key();
        $where = [
            $primaryKey => $id,
            "$filed>=" => $count
        ];
        return $this->soma_db_conn
            ->set($filed, "$filed - $count", false)
            ->where($where)
            ->update($this->table_name());
    }

}
