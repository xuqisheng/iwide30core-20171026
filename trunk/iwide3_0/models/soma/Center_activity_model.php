<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Center_activity_model extends MY_Model_Soma {

    const SYNC_TYPE_GROUPON=1;//同步团购类型
    const SYNC_TYPE_KILLSEC=2;//同步秒杀类型

    const STATUS_SALES  = 1;//上线
    const STATUS_UNSALES= 2;//下线
    const STATUS_UNREVIEW= 3;//未审核，同步到中心平台，未做过任何处理

    public function get_sync_type_label()
    {
        return array(
                self::SYNC_TYPE_KILLSEC=>'秒杀',
                self::SYNC_TYPE_GROUPON=>'团购',
            );
    }

    public function get_status_label()
    {
        return array(
            self::STATUS_SALES   => '上线',
            self::STATUS_UNSALES => '下线',
            self::STATUS_UNREVIEW => '未审核',
        );
    }

	public function get_resource_name()
	{
		return 'Center_activity_model';
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
		return 'soma_center_activity';
	}

	public function table_primary_key()
	{
	    return 'center_act_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'center_act_id'=> '同步ID',
            'inter_id'=> '中心平台公众号',
            'hotel_id'=> '中心平台酒店ID',
            'hotel_inter_id'=> '酒店公众号',
            'hotel_hotel_id'=> '酒店ID',
            'act_id'=> '活动编号',
            'sync_type'=> '同步类型',
            'post_admin'=> '酒店平台同步操作人',
            'post_admin_ip'=> '酒店平台IP地址',
            'create_time'=> '创建时间',
            'update_time'=> '更新时间',
            'center_update_time'=> '中心平台更新时间',
            'center_post_admin'=> '中心平台操作人',
            'center_post_admin_ip'=> '中心平台ID地址',
            'link'=> '链接',
            'sort'=> '排序',
            'status'=> '状态',
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
            'center_act_id',
            'hotel_inter_id',
            // 'hotel_hotel_id',
            'act_id',
            'sync_type',
            // 'post_admin',
            // 'post_admin_ip',
            'create_time',
            // 'update_time',
            // 'center_update_time',
            // 'center_post_admin',
            // 'center_post_admin_ip',
            'sort',
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
	    $Somabase_util= Soma_base::inst();
	    $modules= config_item('admin_panels')? config_item('admin_panels'): array();

        /** 获取本管理员的酒店权限  */
        $hotels_hash= $this->get_hotels_hash();
        $publics = $hotels_hash['publics'];
        $hotels = $hotels_hash['hotels'];
        $filter = $hotels_hash['filter'];
        $filterH = $hotels_hash['filterH'];
        /** 获取本管理员的酒店权限  */

        //获取该公众号列表
        $this->load->model('wx/publics_model');
        $interIds= $this->publics_model->get_public_hash();
        $interIds= $this->publics_model->array_to_hash($interIds, 'name', 'inter_id');

	    return array(
            'center_act_id' => array(
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
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
                'select'=> $publics,
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|date|datetime|combobox|number|logo|email|url|price
                'select'=> $hotels,
            ),
            'hotel_inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|date|datetime|combobox|number|logo|email|url|price
                'select'=> $interIds,
            ),
            'hotel_hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                // 'form_hide'=> TRUE,
                'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
                'select'=> $hotels,
            ),
            'act_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'sync_type' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
                'select'=>$this->get_sync_type_label(),
            ),
            'post_admin' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'post_admin_ip' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
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
                'form_ui'=> ' disabled ',
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
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'center_update_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'center_post_admin' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'center_post_admin_ip' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'form_type'=> TRUE,
                //'input_unit'=> '单位',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
            ),
            'sort' => array(
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
                'type'=>'combobox',	//textarea|text|date|datetime|combobox|number|logo|email|url|price
                'select'=>$this->get_status_label(),
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'center_act_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

    //获取已经上线的活动
    public function get_activity_list( $filter, $orderby='center_act_id DESC', $limit='20' )
    {
        //没有条件传人，就默认查找所有的上线活动
        if( count( $filter ) > 0 ){
            foreach ($filter as $k=>$v) {
                if( is_array($v) ){
                    $this->_shard_db()->where_in($k, $v);
                } else {
                    $this->_shard_db()->where($k, $v);
                }
            }
        }

        if( $limit ){
            $this->_shard_db()->limit( $limit );
        }

        $this->_shard_db()->where( 'status', self::STATUS_SALES );

        $table_name = $this->table_name();
        return $this->_shard_db()->order_by($orderby)->get($table_name)->result_array();
        // $this->_shard_db()->order_by($orderby)->get($table_name)->result_array();
        // var_dump( $this->_shard_db()->last_query());die;

    }

    //因为酒店那边修改了活动状态或者商品状态，所以中心平台这边都要把对应的活动下线(在活动列表的时候进行判断)
    public function update_status_byActIds( $actIds )
    {
        if( count( $actIds ) == 0 ){
            return FALSE;
        }

        $data = array();
        $data['status'] = self::STATUS_UNSALES;

        $table_name = $this->table_name();
        return $this->_shard_db()->where_in( 'act_id', $actIds )->update( $table_name, $data );
        // var_dump( $actIds, $this->_shard_db()->last_query() );die;
    }

    //查找同步数据
    public function get_list_by_actIds( $actIds, $select='*', $orderby='center_act_id DESC', $limit='' )
    {
        $table_name = $this->table_name();

        if( $limit ){
            $this->_shard_db()->limit( $limit );
        }

        $result = $this->_shard_db()
                        ->where_in( 'act_id', $actIds )
                        ->select( $select )
                        ->order_by( $orderby )
                        ->get( $table_name )
                        ->result_array();

        return $result;
    }

    /**
    * 接收同步信息接口
    */
    public function sync_activitys_to_center( $json )
    {
        $msg = array();
        if( !empty( $json ) ){
            $json = json_decode( $json, TRUE );
            $data = $json['data'];//同步活动ID
            $hotel_inter_id = $json['id'];//公众号
            $hotelIds = $json['hid'];//酒店ID
            $syncType = $json['type'];//同步类型
            // $link = $json['link'];//同步类型
            $ip = $json['ip'];//IP地址
            $admin = $json['admin'];//操作员
            if( !$ip || !$admin ){
                $msg['return_code'] = 'SUCCESS';
                $msg['result_code'] = 'FAIL';
                $msg['message'] = '没有查找到操作员或IP地址，不能进行下一步操作！';
                // echo json_encode( $msg );die;
                return json_encode( $msg );
            }

            if( count( $data ) == 0 ){
                $msg['return_code'] = 'SUCCESS';
                $msg['result_code'] = 'FAIL';
                $msg['message'] = '没有找到同步数据！';
                // echo json_encode( $msg );die;
                return json_encode( $msg );
            }

            $actIds = array();
            foreach( $data as $k=>$v ){
                $actIds[] = $v['act_id'];
            }

            if( count( $actIds ) > 0 ){
                //查找是否已经同步过
                $failList = array();
                $syncList = $this->get_list_by_actIds( $actIds, 'center_act_id,act_id' );
                if( count( $syncList ) > 0 ){
                    //排除已经同步到数据
                    foreach ($syncList as $k => $v) {
                        if( in_array( $v['act_id'], $actIds ) ){
                            $fail = array();
                            $fail['act_id'] = $v['act_id'];
                            $fail['message'] = '已经同步';
                            $failList[] = $fail;

                            unset( $data[$v['act_id']] );
                        }
                    }
                }
            }else{
                $msg['return_code'] = 'SUCCESS';
                $msg['result_code'] = 'FAIL';
                $msg['message'] = '没有找到活动ID！';
                // echo json_encode( $msg );die;
                return json_encode( $msg );
            }

            $insertDatas = array();
            $successList = array();
            if( count( $data ) > 0 ){
                //剩下的是没有同步的
                foreach ($data as $k => $v) {
                    //组装同步信息
                    $insert_data['act_id'] = $v['act_id'];
                    $insert_data['link'] = $v['link'];
                    $insert_data['inter_id'] = $this->get_center_inter_id();
                    $insert_data['hotel_inter_id'] = $hotel_inter_id;
                    $insert_data['hotel_hotel_id'] = $hotelIds;
                    $insert_data['post_admin'] = $admin;
                    $insert_data['post_admin_ip'] = $ip;
                    $insert_data['sync_type'] = $syncType;
                    $insert_data['create_time'] = date('Y-m-d H:i:s');
                    // $insert_data['link'] = $link;
                    $insert_data['status'] = self::STATUS_UNREVIEW;

                    $insertDatas[] = $insert_data;

                    $success = array();
                    $success['act_id'] = $v['act_id'];
                    $success['message'] = '同步成功';
                    $successList[] = $success;
                }
            }

            $result = FALSE;
            if( count( $insertDatas ) > 0 ){
                $table_name = $this->table_name();
                $result = $this->_shard_db()->insert_batch( $table_name, $insertDatas );
            }

            if( $result ){
                $msg['return_code'] = 'SUCCESS';
                $msg['result_code'] = 'SUCCESS';
                $msg['success_list'] = $successList;
                $msg['fail_list'] = $failList;
                $msg['message'] = '同步成功';
            }else{
                $msg['return_code'] = 'SUCCESS';
                $msg['result_code'] = 'FAIL';
                $msg['success_list'] = $successList;
                $msg['fail_list'] = $failList;
                if( count( $successList ) > 0 ){
                    $message = '有需要同步的数据，但是同步过程中失败了！';
                }elseif( count( $failList ) > 0 ){
                    $message = '已经同步，无需再操作！';
                }else{
                    $message = '没有数据需要同步的。请查看是否已经同步或者不能进行同步操作！';
                }
                $msg['message'] = $message;
            }

            // echo json_encode( $msg );die;
            return json_encode( $msg );

        }else{
            $msg['return_code'] = 'FAIL';
            $msg['result_code'] = 'FAIL';
            $msg['message'] = '发送未知错误！';
            // echo json_encode( $msg );die;
            return json_encode( $msg );
        }

    }

	
}
