<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Cms_block_model extends MY_Model_Soma {

    const TYPE_COMMON = 1;

	public function get_resource_name()
	{
		return 'Cms_block_model';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function get_type()
    {
        return array(
                self::TYPE_COMMON=>'通用',
            );
    }
	
	/**
	 * @return string the associated database table name
	 */
	// public function table_name()
	// {
	// 	return 'soma_cms_block';
	// }

    public function table_name($inter_id=NULL)
    {
        return $this->_shard_table('soma_cms_block', $inter_id);
    }
    public function block_product_table_name($inter_id=NULL)
    {
        return $this->_shard_table('soma_cms_block_product', $inter_id);
    }

	public function table_primary_key()
	{
	    return 'block_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'block_id'=> '推荐位ID',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店',
            'title'=> '名称',
            'type'=> '类型',
            'link'=> '更多链接',
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
            'block_id',
            'inter_id',
            'hotel_id',
            'title',
            'type',
            //'link',
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
            'block_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                // 'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $publics,
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $hotels,
            ),
            'title' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'type' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=>$this->get_type(),
            ),
            'link' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                'form_default'=> 'http://',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'sort' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'number',	//textarea|text|combobox|number|email|url|price
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                'select'=> $Somabase_util::get_status_options(),
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'block_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

    //后台保存
    public function block_save( $post, $inter_id=NULL )
    {
        if( !$post ){
            return FALSE;
        }

        $block_id = '';
        $pk = $this->table_primary_key();
        if( !$post[$pk] ){
            //add
            $this->m_sets( $post )->m_save();
            $block_id = $this->_shard_db($inter_id)->insert_id();

        }else{
            //edit   
            $this->load($post[$pk])->m_sets($post)->m_save();
            $block_id = $post[$pk];

        }

        //如果选择了要添加的商品
        // $product_id = isset( $post['product_id'] ) ? $post['product_id'] + 0 : 0;
        // if( $product_id ){
        //     unset( $post['product_id'] );

        //     //获取商品信息
        //     $this->load->model( 'soma/Product_package_model' );
        //     $product_detail = $this->Product_package_model->get_product_package_detail_by_product_id( $product_id, $inter_id );

        //     $data = array();
        //     $data['block_id'] = $block_id;
        //     $data['product_id'] = $product_id;
        //     // $data['name'] = isset( $product_detail['name'] ) ? $product_detail['name'] : '';
        //     $data['sort'] = isset( $product_detail['sort'] ) ? $product_detail['sort'] : $post['sort'];
        //     $data['status'] = isset( $product_detail['status'] ) ? $product_detail['status'] : $post['status'];

        //     $block_product_table_name = $this->block_product_table_name($inter_id);
        //     $this->_shard_db($inter_id)->insert( $block_product_table_name, $data );
        // }

        return $block_id;

    }

    //后台添加商品保存
    public function product_save( $post, $inter_id=NULL )
    {
        if( !$post ){
            return FALSE;
        }

        //如果选择了要添加的商品
        $product_id = isset( $post['product_id'] ) ? $post['product_id'] + 0 : 0;
        if( !$product_id ){
            return FALSE;
        }

        //获取商品信息
        $this->load->model( 'soma/Product_package_model' );
        $product_detail = $this->Product_package_model->get_product_package_detail_by_product_id( $product_id, $inter_id );
        
        $block_id = '';
        $pk = $this->table_primary_key();
        
        $block_product_table_name = $this->block_product_table_name($inter_id);
        $filter = array();
        $filter['block_id'] = $post[$pk];
        $filter['product_id'] = $post['product_id'];
        $block_product_detail = $this->_shard_db_r('iwide_soma_r')->where( $filter )->get( $block_product_table_name )->row_array();

        if( !$block_product_detail ){
            //add
            $data = array();
            $data['block_id'] = $post[$pk];
            $data['product_id'] = $product_id;
            // $data['name'] = isset( $product_detail['name'] ) ? $product_detail['name'] : '';
            $data['sort'] = isset( $post['product_sort'] ) ? $post['product_sort'] : $product_detail['sort'];
            $data['status'] = isset( $product_detail['status'] ) ? $product_detail['status'] : $post['status'];
            $result = $this->_shard_db($inter_id)->insert( $block_product_table_name, $data );

        }else{
            //edit   
            $data = array();
            // $data['name'] = isset( $product_detail['name'] ) ? $product_detail['name'] : '';
            $data['sort'] = isset( $post['product_sort'] ) ? $post['product_sort'] : $product_detail['sort'];
            $data['status'] = isset( $product_detail['status'] ) ? $product_detail['status'] : $post['status'];
            $result = $this->_shard_db($inter_id)->where( $filter )->update( $block_product_table_name, $data );

        }

        return $result;

    }

    //后台添加商品保存
    public function product_edit( $post, $inter_id=NULL )
    {
        if( !isset( $post['sort'] ) ){
            return FALSE;
        }

        $block_product_table_name = $this->block_product_table_name($inter_id);
        foreach( $post['sort'] as $k=>$v ){

            $filter = array();
            $filter['block_id'] = $post['block_id'];
            $filter['product_id'] = $k;

            $data = array();
            $data['sort'] = $v;
            $result = $this->_shard_db($inter_id)->where( $filter )->update( $block_product_table_name, $data );
        }


        return $result;

    }


    //获取全部推荐位
    public function get_cms_block_list( $inter_id=NULL, $status=NULL )
    {
        $inter_id = empty( $inter_id ) ? $this->session->get_admin_inter_id() : $inter_id;
        
        $where = array();
        $where['inter_id'] = $inter_id;
        $where['status'] = empty( $status ) ? parent::STATUS_TRUE : $status;

        $table_name = $this->table_name( $inter_id );
        return $this->_shard_db_r('iwide_soma_r')->where( $where )->get( $table_name )->result_array();
    }

    /*
        * 获取一个推荐位的信息
        * $filter = array( 'inter_id'=>1, 'type'=>1 );
       */
    public function get_cms_block_detail( $filter, $limit=0, $page=1)
    {
        if( isset( $filter['inter_id'] ) ){
            $inter_id = $filter['inter_id'];
        }else{
            $inter_id = '';
        }

        //状态过滤
        $status_arr = array( parent::STATUS_TRUE, parent::STATUS_FALSE );
        if( isset( $filter['status'] ) && !empty( $filter['status'] ) && in_array( $filter['status'], $status_arr ) ){

        }else{
            $filter['status'] = parent::STATUS_TRUE;
        }

        $table_name = $this->table_name( $inter_id );
        $result = $this->_shard_db_r( 'iwide_soma_r' )->where( $filter )->get( $table_name )->result_array();

        if( $result ){
            $detail = $result[0];

            $block_id = $detail['block_id'];
            //取出block关联的商品
            $block_product = $this->get_cms_block_product( $block_id, $limit, $inter_id, $page);
            $detail['products'] = $block_product['products'];
            $detail['count'] = $block_product['count'];

            return $detail;
        }else{
            return FALSE;
        }
    }

    //根据block获取商品ID
    public function get_cms_block_product( $block_id, $limit=0, $inter_id, $page=1)
    {
        if( !$block_id ){
            return FALSE;
        }

        $where = array();
        $where['block_id'] = $block_id + 0;

        // $db = $this->_shard_db( $inter_id );
        $db = $this->_shard_db_r( 'iwide_soma_r' );

        //取出条数
        $block_product_table_name = $this->block_product_table_name( $inter_id );
        $count = $db->where( $where )->get( $block_product_table_name )->result_array();
        $count = count($count);

        //limit＝0取出全部
        if( $limit ){
            $startNum = ($page - 1)*$limit;//从哪条开始取
            $limitNum = $limit + 0;//取多少条
            $db->limit( $limitNum, $startNum );
        }

        $res = array(
            'count' => $count,
            'products' =>  $db->where( $where )->get( $block_product_table_name )->result_array()
        );
        return $res;
    }

    /**
     * 输出单个block HTML内容
     * @return String
     */
    public function show_in_page($uri, $filter, $limit=0, $page=1)
    {
        $match_url= array(
            1=> 'soma_package_package_detail',//商品详情
            2=> 'soma_order_order_detail',//赠送页面,礼物被全部赠送时显示，未被赠送完全不显示
            3=> 'soma_consumer_consumer_detail',//核销页面,商品已被核销（已使用），未被使用不显示
            4=> 'soma_consumer_package_booking',//电话预约页面
            5=> 'soma_refund_detail',//退款详情
            6=> 'soma_gift_package_detail',//赠送---已被人领取页面
            7=> 'soma_consumer_package_usage',//立即用券，已经使用

        );
        $match_type= array(
            1=> self::TYPE_COMMON,
            2=> self::TYPE_COMMON,
            3=> self::TYPE_COMMON,
            4=> self::TYPE_COMMON,
            5=> self::TYPE_COMMON,
            6=> self::TYPE_COMMON,
            7=> self::TYPE_COMMON,

        );

        if( $key = array_search( $uri, $match_url) ){
            $filter['type'] = $match_type[$key];
        }

        $block_detail = $this->get_cms_block_detail( $filter, $limit, $page);
        if( !$block_detail ){
            return FALSE;

        } else {
            $pids = $this->array_to_hash($block_detail['products'], 'product_id');
            return $pids;
        }


    }
    
    
}
