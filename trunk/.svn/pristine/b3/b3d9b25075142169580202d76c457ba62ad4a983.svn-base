<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Product_specification_setting_model extends MY_Model_Soma {

	public function get_resource_name()
	{
		return 'Product_specification_setting';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
    public function table_name($inter_id=NULL)
    {
        return $this->_shard_table('soma_product_specification_setting', $inter_id);
    }
    public function table_name_r($inter_id=NULL)
    {
        return $this->_shard_table_r('soma_product_specification_setting', $inter_id);
    }

	public function table_primary_key()
	{
	    return 'setting_id';
	}
	
	public function attribute_labels()
	{
		return array(
            'setting_id'=> '规格设置ID',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店',
            'type'=> '规格类型',
            'product_id'=> '商品ID',
            'setting_spec_compose'=> '规格信息',
            'spec_price'=> '规格价格',
            'spec_stock'=> '规格库存',
            'outter_sku'=> '外部sku',
            'spec_face_img'=> '缩略图',
		);
	}

	public function field_mapping()
	{
		return array(
            'setting_id'=> 'setting_id',
            'inter_id'=> 'inter_id',
            'hotel_id'=> 'hotel_id',
            'type'=> 'type',
            'product_id'=> 'product_id',
            'setting_spec_compose'=> 'setting_spec_compose',
            'spec_price'=> 'spec_price',
            'spec_stock'=> 'spec_stock',
            'outter_sku'=> 'outter_sku',
            'spec_face_img'=> 'spec_face_img',
		);
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'setting_id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

 	/**
 	 * Gets the specification compose.
 	 *
 	 * @param      <type>  $inter_id  The inter id
 	 * @param      <type>  $pid       The product id
 	 * @param      <type>  $sid       The setting id
 	 *
 	 * @return     array   The specification compose.
 	 */
	public function get_specification_compose($inter_id, $pid, $sid = null, $type=null ) {

		$res = $this->get_specification_setting($inter_id, $pid, $sid, $type);

		$data = array();
		foreach ($res as $row) {
			if( $row['setting_spec_compose'] ){
				$_tmp = json_decode($row['setting_spec_compose'], true);
				foreach ($_tmp as $key => $value) {
					$data[$key] = $value;
					$data[$key]['setting_id']   = $row['setting_id'];
					$data[$key]['spec_stock']   = $row['spec_stock'];
					$data[$key]['spec_price']   = $row['spec_price'];
					$data[$key]['type']         = $row['type'];
				}
			}
		}

		if(false && empty($data)) {
			// 无规格设置时，取旧数据组合成一个通用的规格信息
			// 产品需求，无规格的产品不需要通用规格，前端隐藏多规格选项
			$this->load->model('soma/Product_package_model', 'p_model');
			$product = $this->p_model->load($pid);
			$data[] = array(
				'0' => array(
					'specid' => '0',
					'spec_type1' => '规格',
					'spec_type_id1' => 0,
					'spec_name1' => '通用',
					'spec_name_id1' => 0, 
					'specprice'=> $product->m_get('price_package'),
					'stock' => $product->m_get('stock'),
					'sku' => $product->m_get('sku'),
					'setting_id' => -1,
				),
			);
        }

		return $data;
	}

	/**
	 * Gets the full specification compose.
	 *
	 * @param      <type>  $inter_id  The inter identifier
	 * @param      <type>  $pid       The pid
	 */
	public function get_full_specification_compose($inter_id, $pid, $type=NULL) {
		$this->load->model('soma/Product_specification_model', 'sp_model');
		$setting = $this->sp_model->get_spec_list($inter_id, $pid, $type);
		if(!empty($setting)) {
		    $data = array();
		    foreach( $setting as $v )
		    {
                $data = json_decode($v['spec_compose'], true);
                $data['data'] = $this->get_specification_compose($inter_id, $pid, null, $type);
            }
			return $data;
		}
		return array();
	}

	/**
	 * @author luguihong 
	 * $productModel->spec_setting_data = $spec_setting_data;
	 * 规格保存
	*/
	public function setting_batch_save( $ProductModel, $inter_id )
	{
		$data = $ProductModel->spec_setting_data;
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
	 * 规格更新
	*/
	public function setting_batch_update( $ProductModel, $inter_id, $product_id, $setting_id, $data )
	{
		if( !$data || !$setting_id ){
			return FALSE;
		}

		$where = array();
		$where['product_id'] = $product_id;
		$where['inter_id'] = $inter_id;
		$where['setting_id'] = $setting_id;

		$table_name = $this->table_name( $inter_id );
		$ProductModel->_shard_db( $inter_id )->where( $where )->limit(1)->update( $table_name, $data );
		if( $ProductModel->_shard_db( $inter_id )->affected_rows() > 0 ){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	/**
	 * @author luguihong 
	 * 规格删除
	*/
	public function setting_batch_delete( $ProductModel, $inter_id, $product_id, $settingIds )
	{
		if( !$settingIds ){
			return FALSE;
		}

		$where = array();
		$where['product_id'] = $product_id;
		$where['inter_id'] = $inter_id;

		$table_name = $this->table_name( $inter_id );
		$ProductModel->_shard_db( $inter_id )
						->where( $where )
						->where_in('setting_id',$settingIds)
						->limit(count($settingIds))
						->delete( $table_name );
		if( $ProductModel->_shard_db( $inter_id )->affected_rows() > 0 ){
			return TRUE;
		}else{
			return FALSE;
		}
	}



	/**
	 * Gets the specification setting.
	 *
	 * @param      <type>  $inter_id  The inter id
	 * @param      <type>  $pid       The product id
	 * @param      <type>  $sid       The setting id
	 * @param      <type>  $type      The type
	 *
	 * @return     <type>  The specification setting.
	 */
	public function get_specification_setting($inter_id, $pid, $sid = null, $type=null ) {
		$db = $this->_shard_db_r('iwide_soma_r');
		$tb = $this->table_name_r($inter_id);
		$db->where('inter_id', $inter_id);
		$db->where('product_id', $pid);
		if($sid) {
			$db->where('setting_id', $sid);
		}
		if( $type )
        {
            $db->where('type', $type);
        }
		// $db->order_by('spec_price', 'ASC');
		return $db->get($tb)->result_array();
	}

	//获取规格下一个自增id
	public function get_package_auto_increment_id()
	{
		$sql = "SELECT Auto_increment
        FROM information_schema.`TABLES`
        WHERE Table_Schema='iwide30soma'
        AND table_name = 'iwide_soma_product_specification_setting'";
        $result = $this->_shard_db_r('iwide_soma_r')->query($sql)->row_array();
        // var_dump( $result );die;
        if( $result ){
        	return $result['Auto_increment'];
        }else{
        	return 0;
        }
	}

	/**
	 * Gets the inter product specifier setting.
	 *
	 * @param      <type>  $inter_id     The inter identifier
	 * @param      <type>  $product_ids  The product identifiers
	 */
	public function get_inter_product_spec_setting($inter_id, $product_ids) {
		$db = $this->_shard_db_r('iwide_soma_r');
		$tb = $this->table_name_r($inter_id);
		$db->where('inter_id', $inter_id);
		$db->where_in('product_id', $product_ids);
		$db->order_by('spec_price', 'ASC');
		$res = $db->get($tb)->result_array();
//		$data = array();
//		foreach($res as $row) {
//			$data[$row['product_id']][] = $row;
//		}
		return $res;
	}

	/**
	 * Gets the combine specifier information.
	 *
	 * @param      <type>  $sids   The sids
	 *
	 * @return     array   The combine specifier information.
	 */
	public function getCombineSpecInfo($sids)
	{
		$data = $this->find_all(array('setting_id' => $sids));
		$fmt_data = array();
		foreach($data as $row)
		{
			$fmt_data[$row['setting_id']] = $row;
		}
		return $fmt_data;
	}
}
