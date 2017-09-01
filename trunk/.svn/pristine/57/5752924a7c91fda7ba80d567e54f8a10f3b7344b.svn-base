<?php
/**
 * @author     fengzhongcheng <fengzhongcheng@mofly.com>
 */
class Order_Service extends MY_Service
{
	/**
	 * Gets the new backend list data.
	 *
	 * @param      <type>  $filter  The filter
	 *
	 * @return     <type>  The new backend list data.
	 */
	public function getNewBackendListData($filter)
	{
		$path  = $this->modelName(Sales_order_model::class);
        $alias = $this->modelAlias(Sales_order_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);

		$data['settleLabel']  = $this->somaSalesOrderModel->get_settle_label();
		$data['consumeLabel'] = $this->somaSalesOrderModel->get_consume_label();
		$data['statusLabel']  = $this->somaSalesOrderModel->get_status_label();
		$data['refundLabel']  = $this->somaSalesOrderModel->get_refund_label();
		$data['orderList']	  = $this->somaSalesOrderModel->getNewBackendListData($filter);

        return $data;
	}

	public function getExportListData($filter)
	{
		$path  = $this->modelName(Sales_order_model::class);
        $alias = $this->modelAlias(Sales_order_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);

        $order_data = $this->somaSalesOrderModel->getNewBackendListData($filter);

        // 旧版导出数据
        $item_field= array( 'hotel_id','name','sku','price_package', 'qty' );
        $export_data = $this->somaSalesOrderModel->export_item('package', $filter['inter_id'], array('order_id' => array_keys($order_data['data'])), $item_field, $filter['create_start_time'], $filter['create_end_time']);
        $export_header = $this->somaSalesOrderModel->export_header();

        return array('data' => $export_data, 'header' => $export_header);
	}

}