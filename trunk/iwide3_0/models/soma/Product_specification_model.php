<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Product_specification_model extends MY_Model_Soma {

	const SPEC_TYPE_COLOR          = 1;
	const SPEC_TYPE_SIZE           = 2;
	const SPEC_TYPE_USED_LIMIT_CNT = 3;
	const SPEC_TYPE_TYPES          = 4;
	const SPEC_TYPE_OTHER          = 5;

	public function get_resource_name()
	{
		return 'Product_specification_model';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function get_sepc_type_label()
	{
		return array(
			self::SPEC_TYPE_COLOR          => '颜色',
			self::SPEC_TYPE_SIZE           => '尺寸',
			self::SPEC_TYPE_USED_LIMIT_CNT => '人数',
			self::SPEC_TYPE_TYPES          => '类型',
			self::SPEC_TYPE_OTHER          => '其他',
		);
	}

	public function get_sepc_type_label_lang_ley()
	{
		return array(
			self::SPEC_TYPE_COLOR          => 'colour',
			self::SPEC_TYPE_SIZE           => 'size',
			self::SPEC_TYPE_USED_LIMIT_CNT => 'people_num',
			self::SPEC_TYPE_TYPES          => 'types',
			self::SPEC_TYPE_OTHER          => 'other',
		);
	}

	/**
	 * @return string the associated database table name
	 */
    public function table_name($inter_id=NULL)
    {
        return $this->_shard_table('soma_product_specification', $inter_id);
    }
    public function table_name_r($inter_id=NULL)
    {
        return $this->_shard_table_r('soma_product_specification', $inter_id);
    }

	public function table_primary_key()
	{
	    return 'spec_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'spec_id'=> '规格ID',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店',
            'product_id'=> '商品ID',
            'level'=> '层次',
            'type'=> '规格类型',
            'name'=> '规格名称',
            'spec_compose'=> '规格信息，json字符串',
		);
	}

	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'spec_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	/**
	 * @author luguihong 
	 * $productModel->spec_data = $spec_data;
	 * 规格信息保存
	*/
	public function spec_list_save( $ProductModel, $inter_id )
	{
		$data = $ProductModel->spec_data;
		$table_name = $this->table_name( $inter_id );
		$ProductModel->_shard_db( $inter_id )->insert_batch( $table_name, $data );
        if( $ProductModel->_shard_db( $inter_id )->affected_rows() > 0 ){
            return TRUE;
        }else{
            return FALSE;
        }
	}

	/**
	 * @author luguihong 
	 * $productModel->spec_data = $spec_data;
	 * 规格信息更新
	*/
	public function spec_list_update( $ProductModel, $inter_id, $product_id, $spec_id, $data )
	{

        $where = array();
        $where['product_id'] = $product_id;
        $where['inter_id'] = $inter_id;
        $where['spec_id'] = $spec_id;

        $table_name = $this->table_name( $inter_id );
        $ProductModel->_shard_db( $inter_id )
                        ->where( $where )
                        ->limit( 1 )
                        ->update( $table_name, $data );
        if( $ProductModel->_shard_db( $inter_id )->affected_rows() > 0 ){
            return TRUE;
        }else{
            return FALSE;
        }

	}

	/**
	 * @author luguihong 
	 * 获取规格信息
	*/
	public function get_spec_list( $inter_id, $product_id, $type=null )
	{
		$table_name = $this->table_name( $inter_id );
        $db = $this->_shard_db_r('iwide_soma_r');
        if( $type )
        {
            $db->where( 'type', $type );
        }

		$result = $db->where( 'inter_id', $inter_id )
					->where( 'product_id', $product_id )
					->order_by( 'spec_id DESC' )
					->get( $table_name )
					->result_array();

        //处理一下数据，防止同一种类型有多条纪录
        if( $result )
        {
            $result_new = array();
            foreach( $result as $res )
            {
                $result_new[$res['type']] = $res;
            }
            $result = $result_new;
        }

        return $result;
	}


	
}
