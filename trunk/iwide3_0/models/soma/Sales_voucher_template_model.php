<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_voucher_template_model extends MY_Model_Soma {

    const CODE_LEN = 10;
    const CODE_CHARS_LEN = 36; // 默认取$code_chars前36位作为码的字符域
    // 码字符集
    protected $code_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

	public function get_resource_name()
	{
		return 'Sales_voucher_template_model';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function table_name()
	{
		return 'soma_sales_voucher_template';
	}

	public function table_primary_key()
	{
	    return 'template_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'template_id'=> '模板ID',
            'business'=> '业务类型',
            'inter_id'=> '公众号ID',
            'hotel_id'=> '酒店ID',
            'name'=> '模板名字',
            'rule'=> '使用规则',
            'product_id'=> '券码产品',
            'product_name'=> '产品名',
            'product_price'=> '产品价格',
            'batch_no'=> '批号',
            'effective_time'=> '生效期',
            'expiration_time'=> '失效期',
            'create_time'=> '创建时间',
            'update_time'=> '更新时间',
            'op_user'=> '操作人',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
            'template_id',
            // 'business',
            // 'inter_id',
            // 'hotel_id',
            'name',
            // 'rule',
            // 'product_id',
            'product_name',
            // 'product_price',
            // 'batch_no',
            'effective_time',
            'expiration_time',
            'create_time',
            'update_time',
            'op_user',
	    );
	}

	/**
	 * 后台UI输出定义函数
	 *   type: grid中的表头类型定义 
	 *   function: 数值转换函数 
	 *   select: form中的类型为 combobox时，定义其下来列表
	 grid专用属性名
	 *   grid_function: grid生效的数值转换，如'grid_function'=> 'show_price_prefix|￥',
	 *   grid_width: grid的宽度
	 *   grid_ui:  grid中的属性追加
	 form专用属性名
	 *   js_config: 用于 datetime, date 等js初始化中追加此参数
	 *   input_unit: input框中的单位提示
	 *   form_ui: form中的属性补充定义，如加disabled 在< input “disabled” / > 使元素禁用
	 *   form_tips: form中的label信息提示
	 *   form_hide: form中自动化输出中剔除
	 *   form_default: form中的默认值，请用字符类型，不要用数字
	 */
	public function attribute_ui()
	{
	    /* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
	    //type: numberbox数字框|combobox下拉框|text不写时默认|datebox
	    $Somabase_util= Soma_base::inst();
        $modules= config_item('admin_panels')? config_item('admin_panels'): array();
        
        /** 获取本管理员的酒店权限  */
        $hotels_hash= $this->get_hotels_hash();
        $publics = $hotels_hash['publics'];
        $hotels = $hotels_hash['hotels'];
        $filter = $hotels_hash['filter'];
        $filterH = $hotels_hash['filterH'];
        /** 获取本管理员的酒店权限  */

	    return array(
            'template_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'business' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                // 'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',
                'select'=> $publics,
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                // 'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',
                'select'=> $hotels,
            ),
            'name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'rule' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'product_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'product_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'product_price' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'batch_no' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'effective_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'expiration_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'update_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'op_user' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'template_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

    public function get_product_list($inter_id, $hotel_id = array()) {
        $db = $this->_shard_db_r('iwide_soma_r');
        $table = $this->_shard_table('soma_catalog_product_package', $inter_id);
        $db->select(array('product_id', 'name'));
        $db->where('inter_id', $inter_id);
        if( is_array($hotel_id) && count($hotel_id)>0 ) {
            $db->where_in('hotel_id', $hotel_id);
        }
        $db->where('status', self::STATUS_TRUE);
        $data = $db->get($table)->result_array();
        return $data;
    }

    public function get_product_select_html($product_list) {

        $html = '';

        $data = $this->array_to_hash($product_list, 'name', 'product_id');
        $option = array('' => '请选择一个商品') + $data;

        foreach ($option as $k => $v) {
            $html .= "<option value='$k'";
            if($k == $this->m_get('product_id')) {
                $html .= " selected='selected'";
            }
            $html .= ">$v</option>";
        }

        return $html;
    }

    public function get_batch_options() {

        $inter_id = $this->m_get('inter_id');
        $template_id = $this->m_get('template_id');
        if(!$inter_id || !$template_id) { return array(); }

        $db = $this->_shard_db_r('iwide_soma_r');
        $table = $this->_shard_table('soma_sales_voucher', $inter_id);

        $res = $db->select('batch_no')
                    ->where('template_id', $template_id)
                    ->group_by('batch_no')
                    ->get($table)
                    ->result_array();

        $data = $this->array_to_hash($res, 'batch_no', 'batch_no');

        return array('' => '请选择导出批次') + $data;
    }

    public function get_batch_select_html() {
        $option = $this->get_batch_options();
        foreach ($option as $k => $v) {
            $html .= "<option value='$k'";
            // 要不要显示最新批次
            if($k == $this->m_get('batch_no')) {
                $html .= " selected='selected'";
            }
            $html .= ">$v</option>";
        }
        return $html;
    }

    /**
     * 1.获取当前模板的id
     * 2.获取当前模板的批次
     * 3.将模板的id和批次转换为36进制字符串，连接成为码前缀
     * 4.获取剩余字符数量，生成$num个唯一字符串
     * 
     * @param  [type] $num  [description]
     * @param  [type] $code 券码数组，默认为空，只用于后台券码导入（书香）
     * @return [type]       [description]
     */
    public function generate_sales_code($num, $code = array()) {

        try {

            $primary_key= $this->table_primary_key();
            if( !$this->m_get($primary_key) ){
                throw new Exception('Please Load Model first.', 1);
            }

            $tpl_id = $this->m_get('template_id');

            // batch_no:带日期的批次，用于常规表示
            // batch：不带日期的批次，用于生成券码唯一表示
            $batch_no = $this->_reflash_batch_no();
            $batch = intval(substr($batch_no, 8));

            $_prefix = $this->_number_to_code($tpl_id) . $this->_number_to_code($batch);    

            $remain_len = self::CODE_LEN - strlen($_prefix);
            $_suffix_arr = $this->_code_generate($num, $remain_len);    
            
            $code_set = array();
            if(is_array($code) && count($code) > 0) {
                // 后台导入
                $code_set = $code;
            } else {
                foreach ($_suffix_arr as $_suffix) {
                    $code_set[] = $_prefix . $_suffix;
                }
            }

            // print_r($code_set); exit;

            // 券码保存
            $this->load->model('soma/Sales_voucher_model', 'v_model');
            return $this->v_model->batch_save($this, $code_set);

        } catch (Exception $e) { throw $e; }
    }

    /**
     * 生成当前批号并保存，在生成券码之前操作
     * 
     * @return [type] [description]
     */
    protected function _reflash_batch_no() {
        
        $primary_key= $this->table_primary_key();
        if( !$this->m_get($primary_key) ){
            throw new Exception('Please Load Model first.', 1);
        }

        $cur_batch_no = $this->m_get('batch_no');
        $_prefix = date('Ymd');
        $_suffix = '01';
        if(!empty($cur_batch_no)) {
            // 批次按天循环1,2,3,4……的话可能会出现券码重复
            // 因此改为按模板循环1,2,3,4……
            /* 
            $_cur_prefix = substr($cur_batch_no, 0, 8);
            if($_cur_prefix == $_prefix) {
                $_cur_suffix = substr($cur_batch_no, 8);
                $_suffix = intval($_cur_suffix) + 1;
                if(strlen($_suffix) < 2) {
                    $_suffix = '0' . $_suffix;
                }
            }
            */
            $_cur_suffix = substr($cur_batch_no, 8);
            $_suffix = intval($_cur_suffix) + 1;
            if(strlen($_suffix) < 2) {
                $_suffix = '0' . $_suffix;
            }
        }

        $new_batch_no = $_prefix . $_suffix;

        $this->m_set('batch_no', $new_batch_no)->m_save();
        return $new_batch_no;
    }

    /**
     * 获取code_cnt个code_len长度不重复的码，其组成为$key_chars的前$ken_len个字符的组合
     * @param  [type]  $code_cnt 生成数量
     * @param  [type]  $code_len 码长
     * @param  integer $key_len  字符域
     * @return [type]            array
     */
    protected function _code_generate($code_cnt, $code_len, $key_len = self::CODE_CHARS_LEN) {   

        // 可产生的总数量比需要数量的5倍还要小，拒绝生成
        if(pow($key_len, $code_len) < ($code_cnt*5)) {
            throw new Exception("Can't produce $code_cnt code", 1);
        }

        $cnt = 0;
        $code_set = array();
        while ($cnt < $code_cnt) {
            $code_set[] = $this->_code_rand($code_len, $key_len);
            $code_set = array_flip(array_flip($code_set));
            $cnt = count($code_set);
        }
        shuffle($code_set);
        return $code_set;
    }

    /**
     * 随机生成一个券码
     * @param  [type]  $code_len 券码长度
     * @param  integer $key_len  字符区间长度
     * @return [type]            [description]
     */
    protected function _code_rand($code_len, $key_len = self::CODE_CHARS_LEN) {
        $code = '';
        for ( $i = 0; $i < $code_len; $i++ ) {
            $code .= $this->code_chars[ mt_rand(0, $key_len - 1) ];
        }   
        return $code;
    }
     
    /**
     * 将数字转换为券码，用于将唯一ID转换为券码，作为券码唯一的种子
     * 原理：将10进制转换为其他进制，依据码的字符区间而定,只适合正整数
     *
     * 注：别乱传参数，没有保护机制，默认从10进制转换到36进制，非规范进制转换
     * 如需转换2,8,16进制，请勿用此函数
     * 
     * @param  [type]  $number       数值，默认10进制数值
     * @param  integer $base_radix   $number进制，默认10进制
     * @param  integer $return_radix 需要返回的数值进制
     * @return [type]                转换后的数值
     */
    protected function _number_to_code($number, $base_radix = 10, $return_radix = self::CODE_CHARS_LEN) {
        
        $base_radix_str = substr($this->code_chars, 0, $base_radix);
        $base_radix_arr = str_split($base_radix_str);
        $base_radix_flip = array_flip($base_radix_arr);

        $return_radix_str = substr($this->code_chars, 0, $return_radix);
        $return_radix_arr = str_split($return_radix_str);

        $ten_radix_num = 0;
        $number_arr = array_reverse(str_split($number));
        $len = count($number_arr) - 1;
        for ($i = $len; $i >= 0; $i--) {
            $mod_num = $base_radix_flip[$number_arr[$i]];
            $ten_radix_num += ($mod_num * pow($base_radix, $i));
        }

        $ret_radix_num = '';
        $num = $ten_radix_num;
        $radix = floatval($return_radix);
        while ($num != 0) {
            $mod = fmod($num ,$radix);
            $ret_radix_num = $return_radix_arr[$mod] . $ret_radix_num;
            $num = floor($num / $radix);
        }

        return $ret_radix_num;

    }

    /**
     * 校验模板信息
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function template_validation($data) {
        $this->load->library('form_validation');
        $this->form_validation->set_data($data);
        if(empty($data['template_id'])) {
            $this->form_validation->set_rules('inter_id', '公众号', 'required');
            // $this->form_validation->set_rules('hotel_id', '公众号', 'required');
            $this->form_validation->set_rules('name', '模板名', 'required');
            $this->form_validation->set_rules('product_id', '产品ID', 'required');
            $this->form_validation->set_rules('effective_time', '生效期', 'required');
            $this->form_validation->set_rules('expiration_time', '失效期', 'required');
        } else {
            $this->form_validation->set_rules('template_id', '生成数量', 'required|integer');
            $this->form_validation->set_rules('produce_cnt', '生成数量', 'required|integer');
        }
        return $this->form_validation->run();
    }

    /**
     * 格式化数据
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function format_template_data($data) {

        $this->load->model('soma/Product_package_model');
        $p_model = $this->Product_package_model->load($data['product_id']);
        if(!$p_model) { throw new Exception("Can't load product", 1); }

        $fmt_data = array();
        $fmt_data['business'] = $data['business'];
        $fmt_data['inter_id'] = $data['inter_id'];
        $fmt_data['hotel_id'] = isset($data['hotel_id'])?$data['hotel_id']:null;
        $fmt_data['name'] = $data['name'];
        $fmt_data['rule'] = '';  // 暂时不需要该字段
        $fmt_data['product_id'] = $p_model->m_get('product_id');
        $fmt_data['product_name'] = $p_model->m_get('name');
        $fmt_data['product_price'] = $p_model->m_get('price_package');
        // $fmt_data['batch_no'] = date('Y-m-d') . '00'; //新建没有批号
        $fmt_data['effective_time'] = $data['effective_time'];
        $fmt_data['expiration_time'] = $data['expiration_time'];
        $fmt_data['create_time'] = date('Y-m-d H:i:s');
        $fmt_data['update_time'] = $fmt_data['create_time'];
        $fmt_data['op_user'] = $data['op_user'];

        return $fmt_data;
    }

    /**
     * 券码导出
     * 券码id，模板名，批次，券码，生效期，失效期
     * @param  [type] $batch_no [description]
     * @return [type]           [description]
     */
    public function batch_export($batch_no) {
        
        $inter_id = $this->m_get('inter_id');
        $this->load->model('soma/Sales_voucher_model', 'v_model');
        
        $db = $this->_shard_db_r('iwide_soma_r');
        $table_name = $this->v_model->table_name();
        $table = $this->_shard_table($table_name, $inter_id);

        $where = array(
            'template_id' => $this->m_get('template_id'),
            'batch_no' => $batch_no,
        );

        $voucher_data = $db->select('*')->where($where)->get($table)->result_array();
        $status = $this->v_model->get_status_label();

        $fmt_data = array();
        $cnt = 1;
        foreach ($voucher_data as $voucher_row) {
            $fmt_row['id'] = $cnt++;
            $fmt_row['template'] = $this->m_get('name');
            $fmt_row['batch_no'] = $batch_no;
            $fmt_row['code'] = $voucher_row['code'];
            $fmt_row['effective_time'] = $this->m_get('effective_time');
            $fmt_row['expiration_time'] = $this->m_get('expiration_time');
            $fmt_row['status'] = $status[ $voucher_row['status'] ];
            $fmt_data[] = $fmt_row;
        }
        return $fmt_data;
    }

    public function export_header() {
        return array('券码序号', '模板名', '批次', '券码', '起效期', '失效期', '状态');
    }

    /**
     * 新后台重写此方法,提供新后台的数据，注意保持ori_data中的原数据格式，避免其他地方调用异常
     *
     * @param      array   $params  The parameters
     * @param      array   $select  The select
     * @param      string  $format  The format
     */
    public function filter( $params=array(), $select= array(), $format='array' ) {
        $ori_data = parent::filter($params, $select, $format);
        return $this->get_new_backend_order_data($ori_data);
    }

    public function get_new_backend_order_data($ori_data) {
        $ids = array();
        foreach ($ori_data['data'] as $row) {
            $ids[] = $row['DT_RowId'];
        }
        $record_data = $this->find_all(array('template_id' => $ids));

        foreach ($record_data as $row) {
            $p_ids[] = $row['product_id'];
        }
        $this->load->model('soma/Product_package_model', 'somaProductModel');
        $p_data = $this->somaProductModel->find_all(array('product_id' => $p_ids));

        $fmt_data = array();
        foreach ($record_data as $row) {
            $fmt_data[$row['template_id']] = $row;
            foreach($p_data as $p_row) {
                if($p_row['product_id'] == $row['product_id']) {
                    $fmt_data[$row['template_id']]['product_info'] = $p_row;
                }
            }
        }

        $new_data = $ori_data;
        foreach ($ori_data['data'] as $key => $row) {
            $new_data['data'][$key]['new_info'] = array();
            if(isset($fmt_data[$row['DT_RowId']])) {
                $new_data['data'][$key]['new_info'] = $fmt_data[$row['DT_RowId']];
            }
        }
        return $new_data;
    }

}
