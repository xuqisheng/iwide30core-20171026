<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_model extends MY_Model_Soma {

    const SMS_TYPE_ORDER_SUCCESS = 1;

    const SMS_MSG_STATUS_SUCCESS = 1;
    const SMS_MSG_STATUS_READY   = 2;
    const SMS_MSG_STATUS_FAILURE = 3;
    const SMS_MSG_STATUS_SENDING = 4;

    // const SMS_TEMPLATE_ORDER_SUCCESS = '180350';
    const SMS_TEMPLATE_ORDER_SUCCESS = '182780';

    private $active_db;

    public function __construct()
    {
        $this->active_db = $this->soma_db_conn_read;
    }

    public function switch_db($type)
    {
        if($type == $this->db_soma_read)
        {
            $this->active_db = $this->soma_db_conn_read;
        }
        else
        {
            $this->active_db = $this->soma_db_conn;
        }
    }

    public function get_status_label()
    {
        return array(
            self::SMS_MSG_STATUS_SUCCESS => '发送成功',
            self::SMS_MSG_STATUS_READY   => '待发送',
            self::SMS_MSG_STATUS_FAILURE => '发送失败',
            self::SMS_MSG_STATUS_SENDING => '发送中',
        );
    }

    public function get_msg_type_label()
    {
        return array(
            self::SMS_TYPE_ORDER_SUCCESS => '下单成功',
        );
    }

	public function get_resource_name()
	{
		return 'Sms_model';
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
		return 'soma_sms_log';
	}

	public function table_primary_key()
	{
	    return 'sms_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'sms_id'=> 'Sms_id',
            'inter_id'=> 'Inter_id',
            'type'=> 'Type',
            'unique_id'=> 'Unique_id',
            'to'=> 'To',
            'data'=> 'Data',
            'temp_id'=> 'Temp_id',
            'create_time'=> 'Create_time',
            'status'=> 'Status',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
            'sms_id',
            'inter_id',
            'type',
            'unique_id',
            'to',
            'data',
            'temp_id',
            'create_time',
            'status',
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
	    $base_util= EA_base::inst();
	    $modules= config_item('admin_panels')? config_item('admin_panels'): array();

	    return array(
            'sms_id' => array(
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
            'inter_id' => array(
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
            'type' => array(
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
            'unique_id' => array(
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
            'to' => array(
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
            'data' => array(
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
            'temp_id' => array(
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
            'status' => array(
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
	    return array('field'=>'sms_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

    /**
     * 插入短信记录
     *
     * @param      string   $inter_id   The inter identifier
     * @param      int      $type       The type
     * @param      string   $unique_id  The unique identifier
     * @param      array    $sms_data   The sms data
     *
     * @return     boolean  True if insert success, false otherwise.
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    public function sms_insert($inter_id, $type, $unique_id, $sms_data)
    {
        $this->switch_db($this->db_soma);
        if($this->sms_exists($type, $unique_id))
        {
            return false;
        }

        $data['inter_id']    = $inter_id;
        $data['type']        = $type;
        $data['unique_id']   = $unique_id;
        $data['to']          = $sms_data['to'];
        $data['data']        = json_encode($sms_data['datas']);
        $data['temp_id']     = $sms_data['temp_id'];
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['status']      = self::SMS_MSG_STATUS_READY;

        $this->soma_db_conn->insert($this->table_name(), $data);
        return ($this->soma_db_conn->affected_rows()) == 1;
    }

    /**
     * 更新短信记录状态
     *
     * @param      int      $type       The type
     * @param      string   $unique_id  The unique identifier
     * @param      int      $status     The status
     *
     * @return     boolean  True if update success, false otherwise.
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    public function sms_update($type, $unique_id, $status)
    {
        $this->soma_db_conn->where('type', $type);
        $this->soma_db_conn->where('unique_id', $unique_id);
        $this->soma_db_conn->set('status', $status);

        $this->soma_db_conn->update($this->table_name());
        return ($this->soma_db_conn->affected_rows()) == 1;
    }

    /**
     * 检查短信是否存在（检测短信是不是已经发送过了）
     *
     * @param      int      $type       The type
     * @param      string   $unique_id  The unique identifier
     * @param      int      $status     The status
     *
     * @return     boolean  True if sms exist, false otherwise.
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    public function sms_exists($type, $unique_id, $status = self::SMS_MSG_STATUS_SUCCESS)
    {
        $this->active_db->where('type', $type);
        $this->active_db->where('unique_id', $unique_id);
        $this->active_db->where('status', $status);
        $this->active_db->from($this->table_name());

        return ($this->active_db->count_all_results()) > 0;
    }

    /**
     * 获取短信信息（默认获取就绪状态的短信信息）.
     *
     * @param      int    $status  The status
     * @param      int    $limit   The limit
     *
     * @return     array  短信信息.
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    public function get_sms($status = self::SMS_MSG_STATUS_READY, $limit = 100)
    {
        $this->active_db->where('status', $status);
        $this->active_db->limit($limit);
        $this->active_db->from($this->table_name());

        return $this->active_db->get()->result_array();
    }

    public function send_sms()
    {
        $sms = $this->get_sms();
        $this->load->library('Soma/Api_sms', null);
        foreach ($sms as $item)
        {
            $this->sms_update($item['type'], $item['unique_id'], self::SMS_MSG_STATUS_SENDING);
            $datas = json_decode($item['data'], true);
            if($this->api_sms->sendTemplateSMS($item['to'], $datas, $item['temp_id']))
            {
                $this->sms_update($item['type'], $item['unique_id'], self::SMS_MSG_STATUS_SUCCESS);
            }
            else
            {
                $this->sms_update($item['type'], $item['unique_id'], self::SMS_MSG_STATUS_FAILURE);
            }
        }
    }

    /**
     * Gets the order success sms.
     *
     * @param      string  $order_id  The order identifier
     *
     * @return     array  The order success sms info.
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    public function get_order_success_sms($order_id)
    {
        if($this->sms_exists(self::SMS_TYPE_ORDER_SUCCESS, $order_id))
        {
            return array('res' => false, 'msg' => "订单[$order_id]下单成功的短信已成功发送");
        }

        $this->load->model('soma/Sales_order_model', 'o_model');
        $this->load->model('soma/Consumer_code_model', 'cc_model');
        $this->load->model('wx/publics_model', 'sc_publics_model');

        $order = $this->o_model->load($order_id);
        if(empty($order))
        {
            return array('res' => false, 'msg' => "找不到订单[$order_id]信息");
        }
        if(empty($order->m_get('mobile')))
        {
            return array('res' => false, 'msg' => "找不到订单[$order_id]联系人信息");
        }

        $publics = $this->sc_publics_model->get_public_by_id($order->m_get('inter_id'));
        if(empty($publics))
        {
            return array('res' => false, 'msg' => "找不到订单[$order_id]对应的公众号信息");
        }

        $asset = $order->get_order_asset($order->m_get('business'), $order->m_get('inter_id'));
        if(empty($asset['items']))
        {
            return array('res' => false, 'msg' => "找不到订单[$order_id]对应的资产信息");
        }

        $origin_asset = null;
        foreach ($asset['items'] as $row)
        {
            if(empty($row['openid_origin']))
            {
                $origin_asset = $row;
                break;
            }
        }
        if($origin_asset == null)
        {
            return array('res' => false, 'msg' => "找不到订单[$order_id]对应的资产信息");
        }

        $code = $this->cc_model->get_code_by_assetItemIds($origin_asset['item_id'], $order->m_get('inter_id'));
        if(empty($code))
        {
            return array('res' => false, 'msg' => "找不到订单[$order_id]对应的资产核销码信息");
        }

        $code_arr = array();
        $cnt = 0;
        $suffix = '';
        foreach($code as $row)
        {
            if($cnt >= 20)
            {
                $suffix .= '...';
                break;
            }
            $code_arr[] =  $row['code'];
            $cnt ++;
        }

        $datas[] = $order->m_get('contact');
        $datas[] = strip_tags($origin_asset['name']);
        $datas[] = $order_id;
        $datas[] = implode(';', $code_arr) . $suffix;
        $datas[] = $origin_asset['expiration_date'];
        $datas[] = '关注"' . "{$publics['name']}" . '"公众号';                        
        return array(
            'res'  => true,
            'data' => array(
                'to'     => $order->m_get('mobile'),
                'datas'  => $datas,
                'temp_id' => self::SMS_TEMPLATE_ORDER_SUCCESS
            ),
        );
    }
	
}
