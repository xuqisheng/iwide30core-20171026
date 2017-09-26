<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 礼包派送 Model
 * Created by Wuqd on.
 * Created Time 2017-09-03 10:00
 */
use \App\libraries\Iapi\BaseConst;

class Gift_delivery_model extends MY_Model_Soma{


    /**
     * 获取礼包列表
     */
    public function getGiftListData($params){

        $pageStart = $params['page']*20;
        $pageLength = 20;
        //入库查询
        $giftListRes = $this->db->select(['id','inter_id','product_id'])->where(['inter_id'=>$params['inter_id']])
            ->limit($pageLength,$pageStart)->get('soma_gift')->result_array();

        $count = $this->db->where(['inter_id'=>$params['inter_id']])->count_all_results('soma_gift');

        $pageCount = ceil($count/$pageLength);

        //获取商品name与商品库存stock
        $productIdArr = arrayColumn($giftListRes,'product_id');
        $productInfoRes = $this->db->select(['product_id','name','stock','cat_id','status'])->where(['inter_id'=>$params['inter_id']])->where_in('product_id',$productIdArr)
            ->get('soma_catalog_product_package')->result_array();

        //数组结果转换
        $productInfoFieldRes = arrayField($productInfoRes,'product_id');

        //获取分类名称
        $catIdArr = arrayColumn($productInfoRes,'cat_id');
        $catInfoRes = $this->db->select(['cat_id','inter_id','cat_name'])->where(['inter_id'=>$params['inter_id']])->where_in('cat_id',$catIdArr)
            ->get('soma_catalog_category_package')->result_array();
        //数组结果转换
        $catInfoFieldRes = arrayField($catInfoRes,'cat_id');
        foreach($giftListRes as $key=>$val){
            $giftListRes[$key]['name'] = empty($productInfoFieldRes[$val['product_id']]['name']) ? '' : $productInfoFieldRes[$val['product_id']]['name'];
            $giftListRes[$key]['stock'] = empty($productInfoFieldRes[$val['product_id']]['stock']) ? '' : $productInfoFieldRes[$val['product_id']]['stock'];
            $giftListRes[$key]['cat_name'] = empty($catInfoFieldRes[$productInfoFieldRes[$val['product_id']]['cat_id']]['cat_name']) ? '' : $catInfoFieldRes[$productInfoFieldRes[$val['product_id']]['cat_id']]['cat_name'];
            $giftListRes[$key]['status'] = empty($productInfoFieldRes[$val['product_id']]['status']) ? '' : $productInfoFieldRes[$val['product_id']]['status'];
        }

        //查询礼包有效时间
        $timeInfo = $this->db->select(['start_time','end_time'])->where(['inter_id'=>$params['inter_id']])->get('iwide_soma_gift_effective_time')->row_array();
        $start_time = date('Y-m-d',$timeInfo['start_time']);
        $end_time = date('Y-m-d',$timeInfo['end_time']);
        //结果返回
        return ['start_time'=>$start_time,'end_time'=>$end_time,'page_size'=>$pageLength,'count'=>$count,'pageCount'=>$pageCount,'giftListData'=>$giftListRes];
    }


    /***
     * 获取可添加礼包的商品列表
     */
    public function getProductListData($params){

        //未删除 and 上架 and 不属于多规格 and 库存大于0的商品
        $productWhere = ['inter_id'=>$params['inter_id'],'status'=>1,'stock >'=>0];
        if(!empty($params['name'])){
            $productWhere['name like'] = '%'.$params['name'].'%';
        }
        //hotel_id酒店id筛选
        if(!empty($params['hotel_id'])){
            $productWhere['hotel_id'] = $params['hotel_id'];
        }

        //获取已添加到礼包的商品
        $giftProId = $this->db->select(['product_id'])->where(['inter_id'=>$params['inter_id']])->get('soma_gift')->result_array();

        $specifiWhere['inter_id'] = $params['inter_id'];
        if(!empty($params['hotel_id'])){
            $specifiWhere['hotel_id'] = $params['hotel_id'];
        }
        //获取多规格商品
        $msfProductId = $this->db->select(['product_id'])->where($specifiWhere)->group_by('product_id')->get('soma_product_specification_setting')->result_array();

        $msfProductIdArr = arrayColumn($msfProductId,'product_id');
        $giftProIdArr = arrayColumn($giftProId,'product_id');
        $giftProIdArr = array_merge($msfProductIdArr,$giftProIdArr);
        $productListRes = $this->db->select(['product_id','stock','cat_id','name'])->where($productWhere)
            ->where_in('goods_type',['1','3'])->where_not_in('product_id',$giftProIdArr)->get('soma_catalog_product_package')->result_array();
        //分类id
        $catIdArr = arrayColumn($productListRes,'cat_id');
        //查询分类名称
        $catWhere = ['inter_id'=>$params['inter_id']];
        $catNameRes = $this->db->select(['cat_id','inter_id','cat_name'])->where($catWhere)->where_in('cat_id',$catIdArr)
            ->get('soma_catalog_category_package')->result_array();

        //数组转换
        $catNameFieldRes = arrayField($catNameRes,'cat_id');
        //获取已添加至礼包的商品
        $giftWhere = ['inter_id'=>$params['inter_id']];
        $giftListRes = $this->db->select(['inter_id','product_id'])->get_where('soma_gift',$giftWhere)->result_array();
        $productIdArr = arrayColumn($giftListRes,'product_id');

        //数组整合
        foreach($productListRes as $key=>$val){
            //分类名称
            $productListRes[$key]['cat_name'] = empty($catNameFieldRes[$val['cat_id']]['cat_name']) ? '' : $catNameFieldRes[$val['cat_id']]['cat_name'];
            //商品是否已添加至礼包
            $productListRes[$key]['is_exist'] = in_array($val['product_id'],$productIdArr) ? 1 : 0;
        }

        return ['productListData'=>$productListRes];

    }


    /***
     * 商品添加至礼包
     */
    public function selectProductAddGift($addGiftProductId = '',$delGiftProductId = '',$type = '',$params){

        //开启事务
        $this->db->trans_begin();
        //删除inter_id 的所有礼包
        if(empty($addGiftProductId) && $type == 'delete'){
           $this->db->delete('soma_gift',['inter_id'=>$params['inter_id']]);
           $this->db->delete('soma_gift_effective_time',['inter_id'=>$params['inter_id']]);
        }

        //商品添加至礼包
        if(!empty($addGiftProductId) && $type == 'add'){
            $data = array();
            $addGiftProductId = array_values($addGiftProductId); //重新索引排序
            foreach($addGiftProductId as $key=>$val){
                $data[$key]['inter_id'] = $params['inter_id'];
                $data[$key]['product_id'] = $val;
                $data[$key]['status'] = 1;
                $data[$key]['updated_by'] = '';
                $data[$key]['updated_time'] = '';
                $data[$key]['created_by'] = '';
                $data[$key]['created_time'] = date('Y-m-d H:i:s',time());
            }
            $this->db->insert_batch('soma_gift', $data);

            //判断添加or修改
            $countRes = $this->db->where(['inter_id'=>$params['inter_id']])->count_all_results('soma_gift_effective_time');
            $effectiveTimeData = array();
            if($countRes > 0){
                $effectiveTimeData['start_time'] = strtotime($params['start_time']);
                $effectiveTimeData['end_time'] = strtotime($params['end_time']);
                $effectiveTimeData['updated_by'] = '';
                $effectiveTimeData['updated_time'] = date('Y-m-d H:i:s',time());
                $this->db->where(['inter_id'=>$params['inter_id']])->update('soma_gift_effective_time',$effectiveTimeData);
            }else{
                $effectiveTimeData['inter_id'] = $params['inter_id'];
                $effectiveTimeData['start_time'] = strtotime($params['start_time']);
                $effectiveTimeData['end_time'] = strtotime($params['end_time']);
                $effectiveTimeData['status'] = 1;
                $effectiveTimeData['updated_by'] = '';
                $effectiveTimeData['updated_time'] = '';
                $effectiveTimeData['created_by'] = '';
                $effectiveTimeData['created_time'] = date('Y-m-d H:i:s',time());
                $this->db->insert('soma_gift_effective_time', $effectiveTimeData);
            }
        }

        //删除商品礼包
        if(!empty($delGiftProductId) && $type == 'add'){
            $this->db->where_in('product_id',$delGiftProductId)->delete('soma_gift');
            //更新有效时间
            $effectiveTimeData = array();
            $effectiveTimeData['start_time'] = strtotime($params['start_time']);
            $effectiveTimeData['end_time'] = strtotime($params['end_time']);
            $effectiveTimeData['updated_by'] = '';
            $effectiveTimeData['updated_time'] = date('Y-m-d H:i:s',time());
            $this->db->where(['inter_id'=>$params['inter_id']])->update('soma_gift_effective_time',$effectiveTimeData);
        }

        //结果返回
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return ['status'=>false,'message'=>'保存失败!'];
        }else{
            $this->db->trans_commit();
            return ['status'=>true,'message'=>'保存成功!'];
        }
    }


    /***
     * 删除商品礼包
     */
    public function deleteProductGiftData($params){

        //删除商品礼包
        $result = $this->db->delete('soma_gift',['inter_id'=>$params['inter_id'],'product_id'=>$params['product_id']]);

        if($result === false){
            return ['status'=>BaseConst::OPER_STATUS_FAIL_TOAST,'message'=>'删除商品礼包失败!'];
        }

        //查询剩余商品礼包
        $countNum = $this->db->where(['inter_id'=>$params['inter_id']])->count_all_results('soma_gift');

        if($countNum <= 0){
            //删除商品礼包时间
            $this->db->delete('soma_gift_effective_time',['inter_id'=>$params['inter_id']]);
        }


        return ['status'=>BaseConst::OPER_STATUS_SUCCESS,'message'=>'删除成功!'];
    }


    /***
     * 获取已领取的礼包详情
     */
    public function getGiftReceiveDetail($params,$Gift_detail_model){

        $this->inter_id = $params['inter_id'];
        $pageSize = 20;
        $pageStart = $params['page'] * $pageSize;

        //条件筛选
        if(!empty($params['start_time']) && !empty($params['end_time'])){
            $giftDetailWhere = ['inter_id'=>$params['inter_id'],'is_receive'=>2,'updated_time >'=>$params['start_time'],'updated_time <'=>$params['end_time']];
        }elseif(!empty($params['start_time'])){
            $giftDetailWhere = ['inter_id'=>$params['inter_id'],'is_receive'=>2,'updated_time >'=>$params['start_time']];
        }elseif(!empty($params['end_time'])){
            $giftDetailWhere = ['inter_id'=>$params['inter_id'],'is_receive'=>2,'updated_time <'=>$params['end_time']];
        }else{
            $giftDetailWhere = ['inter_id'=>$params['inter_id'],'is_receive'=>2];
        }

        $Gift_detail_model->_shard_db_r($this->inter_id)->select(['inter_id','gift_id','record_info','gift_num','orther_remark','saler_name','updated_time as add_time','openid','order_id'])
            ->where($giftDetailWhere);
        if(!empty($orderIdArr)){
            $Gift_detail_model->_shard_db_r($this->inter_id)->where_in('order_id',$orderIdArr);
        }
        //订单筛选
        if(!empty($params['order_id'])){
            $Gift_detail_model->_shard_db_r($this->inter_id)->like('order_id',$params['order_id']);
        }
        //创建人
        if(!empty($params['saler_name'])){
            $Gift_detail_model->_shard_db_r($this->inter_id)->like('saler_name',$params['saler_name']);
        }
        //登记信息
        if(!empty($params['record_info'])){
            $Gift_detail_model->_shard_db_r($this->inter_id)->like('record_info',$params['record_info']);
        }
        $giftDetail = $Gift_detail_model->_shard_db_r($this->inter_id)->order_by('updated_time','DESC')->limit($pageSize,$pageStart)->get('iwide_soma_gift_detail')->result_array();

        $Gift_detail_model->_shard_db_r($this->inter_id)->select(['inter_id','gift_id','record_info','gift_num','orther_remark','saler_name','updated_time as add_time','openid','order_id'])->where($giftDetailWhere);
        if(!empty($orderIdArr)){
            $Gift_detail_model->_shard_db_r($this->inter_id)->where_in('order_id',$orderIdArr);
        }
        //订单筛选
        if(!empty($params['order_id'])){
            $Gift_detail_model->_shard_db_r($this->inter_id)->like('order_id',$params['order_id']);
        }
        //创建人
        if(!empty($params['saler_name'])){
            $Gift_detail_model->_shard_db_r($this->inter_id)->like('saler_name',$params['saler_name']);
        }
        //登记信息
        if(!empty($params['record_info'])){
            $Gift_detail_model->_shard_db_r($this->inter_id)->like('record_info',$params['record_info']);
        }
        $count = $Gift_detail_model->_shard_db_r($this->inter_id)->count_all_results('iwide_soma_gift_detail');

        $orderIdArr = arrayColumn($giftDetail,'order_id');

        if(empty($orderIdArr) && !empty($params['order_id'])){
            //获取组合商品子订单号
            $order_id = trim($params['order_id']);
            $orderInfo = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['master_oid','order_id'])->where(['inter_id'=>$params['inter_id'],'order_id'=>$order_id,'status'=>12])
                ->get('soma_sales_order_1001')->row_array();
            $orderInfo['master_oid'] = empty($orderInfo['master_oid']) ? '' : intval($orderInfo['master_oid']);
            //获取礼包详情
            $giftDetail = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['inter_id','gift_id','record_info','gift_num','orther_remark','saler_name','updated_time as add_time','openid','order_id'])
                ->where(['inter_id'=>$params['inter_id'],'is_receive'=>2,'order_id'=>$orderInfo['master_oid']])->get('soma_gift_detail')->result_array();
            $count = count($giftDetail);
            if($count > 0){
                $giftDetail[0]['order_id'] = $orderInfo['order_id'];
            }

            //获取组合商品子商品product_id
            $orderProductInfo = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['order_id','product_id'])->where(['inter_id'=>$params['inter_id'],'order_id'=>$orderInfo['order_id']])
                ->get('soma_sales_order_product_record')->result_array();

        }
        $orderIdArr = empty($orderIdArr) ? ['01'] : $orderIdArr;
        //获取组合商品子订单号
        $sonOrderInfo = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['master_oid','order_id'])->where(['inter_id'=>$params['inter_id']])
            ->where_in('master_oid',$orderIdArr)->get('soma_sales_order_1001')->result_array();
        $sonOrderFieldInfo = arrayField($sonOrderInfo,'order_id');
        $sonOrderIdArr = arrayColumn($sonOrderInfo,'order_id');

        //获取子商品券码核销
        $sonConsumerCodeInfo = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['count(order_id) as consume_num','order_id','status'])->where(['inter_id'=>$params['inter_id'],'status'=>3])->where_in('order_id',$sonOrderIdArr)
            ->group_by('order_id')
            ->get('soma_consumer_code')->result_array();

        $sonOrderCodeInfo = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['count(order_id) as order_code_num','order_id','status'])->where(['inter_id'=>$params['inter_id']])->where_in('order_id',$sonOrderIdArr)
            ->group_by('order_id')
            ->get('soma_consumer_code')->result_array();

        $sonConsumerFieldInfo = arrayField($sonConsumerCodeInfo,'order_id');
        $sonOrderCodeFieldInfo = arrayField($sonOrderCodeInfo,'order_id');

        //获取组合商品子商品product_id
        $sonProductInfo = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['order_id','product_id'])->where(['inter_id'=>$params['inter_id']])
            ->where_in('order_id',$sonOrderIdArr)->get('soma_sales_order_product_record')->result_array();

        $sonProductIdArr = arrayColumn($sonProductInfo,'product_id');
        //获取商品名称
        $sonProductNameRes = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['product_id','name'])->where(['inter_id'=>$params['inter_id']])
            ->where_in('product_id',$sonProductIdArr)->get('soma_catalog_product_package')->result_array();

        $sonProductFieldNameRes = arrayField($sonProductNameRes,'product_id');
        foreach($sonProductInfo as $key=>$val){
            $sonProductInfo[$key]['name'] = empty($sonProductFieldNameRes[$val['product_id']]['name']) ? '' : $sonProductFieldNameRes[$val['product_id']]['name'];
            $sonProductInfo[$key]['master_oid'] = empty($sonOrderFieldInfo[$val['order_id']]['master_oid']) ? '' : $sonOrderFieldInfo[$val['order_id']]['master_oid'];
            $sonProductInfo[$key]['consume_num'] = empty($sonConsumerFieldInfo[$val['order_id']]['consume_num']) ? 0 : $sonConsumerFieldInfo[$val['order_id']]['consume_num'];
            $sonProductInfo[$key]['order_code_num'] = empty($sonOrderCodeFieldInfo[$val['order_id']]['order_code_num']) ? 0 : $sonOrderCodeFieldInfo[$val['order_id']]['order_code_num'];
        }

        $pageCount = ceil($count/$pageSize);
        $openIdArr = arrayColumn($giftDetail,'openid');
        $giftIdArr = arrayColumn($giftDetail,'gift_id');

        $productIdInfo = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['id','product_id'])->where(['inter_id'=>$params['inter_id']])->where_in('id',$giftIdArr)->get('soma_gift')->result_array();

        $giftFieldInfo = arrayField($productIdInfo,'id');
        $productIdArr = !empty($orderProductInfo) ? arrayColumn($orderProductInfo,'product_id') : arrayColumn($productIdInfo,'product_id');
        //获取商品名称
        $productListRes = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['product_id','name'])->where(['inter_id'=>$params['inter_id']])
            ->where_in('product_id',$productIdArr)->get('soma_catalog_product_package')->result_array();
        $productFieldInfo = arrayField($productListRes,'product_id');

        //获取订单
        $orderIdArr = arrayColumn($giftDetail,'order_id');
        $consumerCodeInfo = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['count(order_id) as consume_num','order_id','status'])->where(['inter_id'=>$params['inter_id'],'status'=>3])->where_in('order_id',$orderIdArr)
            ->group_by('order_id')
            ->get('soma_consumer_code')->result_array();

        $orderCodeInfo = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['count(order_id) as order_code_num','order_id','status'])->where(['inter_id'=>$params['inter_id']])->where_in('order_id',$orderIdArr)
            ->group_by('order_id')
            ->get('soma_consumer_code')->result_array();

        $consumerFieldInfo = arrayField($consumerCodeInfo,'order_id');
        $orderCodeFieldInfo = arrayField($orderCodeInfo,'order_id');
        //获取领取人姓名
        $openIdInfo = $this->db->select(['openid','nickname'])->where(['inter_id'=>$params['inter_id']])->where_in('openid',$openIdArr)->get('fans')->result_array();

        //获取openid名称
        $openFiellInfo = arrayField($openIdInfo,'openid');
        foreach($giftDetail as $key=>$val){
            $giftDetail[$key]['name'] = empty($productFieldInfo[$giftFieldInfo[$val['gift_id']]['product_id']]['name']) ? '' : $productFieldInfo[$giftFieldInfo[$val['gift_id']]['product_id']]['name'];
            $giftDetail[$key]['nickname'] = empty($openFiellInfo[$val['openid']]['nickname']) ? '' : $openFiellInfo[$val['openid']]['nickname'];
            $giftDetail[$key]['consume_num'] = empty($consumerFieldInfo[$val['order_id']]['consume_num']) ? 0 : $consumerFieldInfo[$val['order_id']]['consume_num'];
            $giftDetail[$key]['order_code_num'] = empty($orderCodeFieldInfo[$val['order_id']]['order_code_num']) ? 0 : $orderCodeFieldInfo[$val['order_id']]['order_code_num'];
        }

        //特殊情况赋值
        if(!empty($orderProductInfo)){
            $giftDetail[0]['name'] = $productListRes[0]['name'];
        }

        $giftFieldDetail = arrayField($giftDetail,'order_id');
        //子商品整合
        foreach($sonProductInfo as $key=>$val){
            $tempArr = $giftFieldDetail[$val['master_oid']];
            $tempArr['name'] = $val['name'];
            $tempArr['order_id'] = $val['order_id'];
            $tempArr['consume_num'] = $val['consume_num'];
            $tempArr['order_code_num'] = $val['order_code_num'];
            $giftFieldDetail[$val['order_id']] = $tempArr;
        }
        krsort($giftFieldDetail);
        $giftFieldDetail = array_values($giftFieldDetail);
        return ['status'=>BaseConst::OPER_STATUS_SUCCESS,'message'=>$giftFieldDetail,'page_size'=>$pageSize,'count'=>$count,'pageCount'=>$pageCount];

    }


    /***
     * 导出礼包订单
     */
    public function exportGiftOrderData($params,$Gift_detail_model){

        $this->inter_id = $params['inter_id'];
        $orderWhere = array();
        //条件筛选
        if(!empty($params['start_time']) && !empty($params['end_time'])){
            $orderWhere = ['inter_id'=>$params['inter_id'],'create_time >'=>$params['start_time'],'create_time <'=>$params['end_time']];
        }elseif(!empty($params['start_time'])){
            $orderWhere = ['inter_id'=>$params['inter_id'],'create_time >'=>$params['start_time']];
            $params['end_time'] = strtotime('+3 month');
        }elseif(!empty($params['end_time'])){
            $orderWhere = ['inter_id'=>$params['inter_id'],'create_time <'=>$params['end_time']];
            $params['start_time'] = strtotime('-3 month');
        }else{
            $params['start_time'] = date('Y-m-d H:i:s',strtotime('-3 month'));
            $params['end_time'] = date('Y-m-d H:i:s',time());
        }

        if(!empty($orderWhere)){
            $orderWhere['status'] = 12;
            $orderIdInfo = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['order_id'])->where($orderWhere)->get('iwide_soma_sales_order_1001')->result_array();
            $orderIdArr = arrayColumn($orderIdInfo,'order_id');
        }

        $giftDetailWhere = ['inter_id'=>$params['inter_id'],'is_receive'=>2];
        $Gift_detail_model->_shard_db_r($this->inter_id)->select(['inter_id','gift_id','record_info','gift_num','orther_remark','saler_id','saler_name','add_time','openid','order_id'])->where($giftDetailWhere);
        if(!empty($orderIdArr)){
            $Gift_detail_model->_shard_db_r($this->inter_id)->where_in('order_id',$orderIdArr);
        }

        //订单筛选
        if(!empty($params['order_id'])){
            $Gift_detail_model->_shard_db_r($this->inter_id)->like('order_id',$params['order_id']);
        }

        //创建人
        if(!empty($params['saler_name'])){
            $Gift_detail_model->_shard_db_r($this->inter_id)->like('saler_name',$params['saler_name']);
        }

        //登记信息
        if(!empty($params['record_info'])){
            $Gift_detail_model->_shard_db_r($this->inter_id)->like('record_info',$params['record_info']);
        }

        $pageSize = 20;
        $pageStart = $params['page'] * $pageSize;
        $giftDetail = $Gift_detail_model->_shard_db_r($this->inter_id)->get('iwide_soma_gift_detail')->result_array();

        $orderIdArr = arrayColumn($giftDetail,'order_id');
        $orderFieldArr = arrayField($giftDetail,'order_id');

        //获取组合商品子商品订单id
        $sonOrderInfo = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['master_oid','order_id'])->where(['inter_id'=>$params['inter_id']])
            ->where_in('master_oid',$orderIdArr)->get('soma_sales_order_1001')->result_array();
        $sonOrderIdArr = arrayColumn($sonOrderInfo,'order_id');

        $orderIdArr = empty($orderIdArr) ? [] : $orderIdArr;
        $sonOrderIdArr = empty($sonOrderIdArr) ? [] : $sonOrderIdArr;
        $orderIdArr = array_merge($orderIdArr,$sonOrderIdArr);
        // 旧版导出数据
        $item_field= array( 'hotel_id','name','sku','price_package', 'qty' );
        $this->load->model('soma/Sales_order_model','somaSalesOrderModel');
        $export_data = $this->somaSalesOrderModel->export_item('package', $params['inter_id'], array('order_id' => $orderIdArr), $item_field, $params['start_time'], $params['end_time']);
        $export_header = ['订单号','购买人','购买电话','openID','登记信息','创建人','创建人分销ID',
                         '下单时间','购买方式','消费状态','酒店名称','商品名称','单价','购买件数','订单总额',
                         '实付金额(含储值)','获得数量','已赠送','已邮寄','已自提','未核销','未核销总金额', '已过期',
                         '已过期总金额','过期时间','已核销总数(含赠送已核销)','核销总金额','邮寄编号','会员号','备注','其他'];

        $giftOrderData = array();
        //整合导出数据
        foreach($export_data as $key=>$val){

            $giftOrderData[$key]['order_id'] = $val['order_id'];
            $giftOrderData[$key]['openid'] = $val['openid'];
            $giftOrderData[$key]['mobile'] = $val['mobile'];
            $giftOrderData[$key]['openID'] = $val['openID'];
            $giftOrderData[$key]['record_info'] = empty($orderFieldArr[$val['order_id']]['record_info']) ? '' : $orderFieldArr[$val['order_id']]['record_info'];
            $giftOrderData[$key]['saler_name'] = empty($orderFieldArr[$val['order_id']]['saler_name']) ? '' : $orderFieldArr[$val['order_id']]['saler_name'];
            $giftOrderData[$key]['saler_id'] = empty($orderFieldArr[$val['order_id']]['saler_id']) ? '' : $orderFieldArr[$val['order_id']]['saler_id'];
            $giftOrderData[$key]['create_time'] = $val['create_time'];
            $giftOrderData[$key]['settlement'] = $val['settlement'];
            $giftOrderData[$key]['consume_status'] = $val['consume_status'];
            $giftOrderData[$key]['hotel_id'] = $val['hotel_id'];
            $giftOrderData[$key]['name'] = $val['name'];
            $giftOrderData[$key]['price_package'] = $val['price_package'];
            $giftOrderData[$key]['row_qty'] = $val['row_qty'];
            $giftOrderData[$key]['total'] = $val['total'];
            $giftOrderData[$key]['real_grand_total'] = $val['real_grand_total'];
            $giftOrderData[$key]['qty'] = $val['qty'];
            $giftOrderData[$key]['gift_total'] = $val['gift_total'];
            $giftOrderData[$key]['shipping_total'] = $val['shipping_total'];
            $giftOrderData[$key]['consumer_total'] = $val['consumer_total'];
            $giftOrderData[$key]['not_verificated_num'] = $val['not_verificated_num'];
            $giftOrderData[$key]['not_verificated_amount'] = $val['not_verificated_amount'];
            $giftOrderData[$key]['overdue_num'] = $val['overdue_num'];
            $giftOrderData[$key]['overdue_amount'] = $val['overdue_amount'];
            $giftOrderData[$key]['expiration_date'] = $val['expiration_date'];
            $giftOrderData[$key]['verificated_num'] = $val['verificated_num'];
            $giftOrderData[$key]['verificated_amount'] = $val['verificated_amount'];
            $giftOrderData[$key]['shipping_ids'] = $val['shipping_ids'];
            $giftOrderData[$key]['member_card_id'] = $val['member_card_id'];
            $giftOrderData[$key]['remark'] = $val['remark'];
            $giftOrderData[$key]['orther_remark'] = empty($orderFieldArr[$val['order_id']]['orther_remark']) ? '' : $orderFieldArr[$val['order_id']]['orther_remark'];
        }

        return ['data'=>$giftOrderData,'title'=>$export_header];
    }

    /******************前台接口model*******************************/

    /**
     * 生成礼包数据
     */
    public function generateGiftData($params){

        $time = time();
        $params['add_time'] = $time;
        $params['created_time'] = $time;

        $addGiftRes = $this->db->insert('soma_gift_detail',$params);
        //返回结果
        if($addGiftRes === false){
            return false;
        }

        return $this->db->insert_id();
    }


    /**
     * 获取礼包详情
     */
    public function getGiftDetailData($params){

        //获取领取人信息
        $giftInfo = $this->db->select(['gift_id','gift_num','record_info','orther_remark'])->where(['id'=>$params['gift_detail_id'],'inter_id'=>$params['inter_id'],'request_token'=>$params['request_token']])
            ->get('soma_gift_detail')->row_array();

        if(empty($giftInfo)){
            return ['status'=>false,'message'=>'请求参数有误!'];
        }

        //获取礼包商品id
        $giftProInfo = $this->db->select(['product_id'])->where(['id'=>$giftInfo['gift_id']])->get('soma_gift')->row_array();

        if(empty($giftProInfo)){
            return ['status'=>false,'message'=>'礼包不存在!'];
        }

        //获取商品信息
        $productInfo = $this->db->select(['product_id','name','goods_type','stock','price_market','validity_date','date_type','use_date','expiration_date'])->where(['inter_id'=>$params['inter_id'],'product_id'=>$giftProInfo['product_id']])
            ->get('soma_catalog_product_package')->row_array();

        $productInfo['validity_date'] = date('Y-m-d',time());
        $productInfo['expiration_date'] = $productInfo['date_type'] == 2 ? date("Y年m月d日",strtotime("+".$productInfo['use_date']." day",strtotime($productInfo['validity_date']))) : date('Y年m月d日',strtotime($productInfo['expiration_date']));
        //是否是组合商品
        if($productInfo['goods_type'] == 3){
            //获取子商品名称
            $groupProductInfo = $this->db->select(['parent_pid','child_pid','num'])->where(['inter_id'=>$params['inter_id'],'parent_pid'=>$productInfo['product_id']])
                ->get('soma_product_package_link')->result_array();
            //获取子商品id
            $childProductIdArr = arrayColumn($groupProductInfo,'child_pid');
            $childProductInfo = $this->db->select(['product_id','name'])->where(['inter_id'=>$params['inter_id']])->where_in('product_id',$childProductIdArr)
                ->get('soma_catalog_product_package')->result_array();
            //数组转换
            $childProductInfo = arrayField($childProductInfo,'product_id');
            foreach($groupProductInfo as $key=>$val){
                $groupProductInfo[$key]['name'] = empty($childProductInfo[$val['child_pid']]['name']) ? '' : $childProductInfo[$val['child_pid']]['name'];
                $groupProductInfo[$key]['gift_num'] = empty($giftInfo['gift_num']) ? '' : $giftInfo['gift_num'];
                unset($groupProductInfo[$key]['parent_pid']);
                unset($groupProductInfo[$key]['child_pid']);
            }
            $productInfo['child_product_info'] = $groupProductInfo;

        }else{
            $productInfo['child_product_info'] = [['name'=>$productInfo['name'],'num'=>1,'gift_num'=>$giftInfo['gift_num']]];
        }

        $productInfo['gift_record_info'] = $giftInfo;

        //结果返回
        return ['status'=>true,'message'=>$productInfo];
    }


    /**
     * 获取二维码确认领取详情页面
     */
    public function getQrcodeGiftDetailData($params){

        //获取礼包信息
        $giftDetailInfo = $this->db->select(['gift_id','record_info','orther_remark','gift_num'])->where($params)->get('soma_gift_detail')->row_array();

        //获取礼包商品id
        $giftProductInfo = $this->db->select(['product_id'])->where(['inter_id'=>$params['inter_id'],'id'=>$params['gift_id']])
            ->get('soma_gift')->row_array();

        if(empty($giftProductInfo)){
            //结果返回
            return ['status'=>false,'message'=>'礼包不存在!'];
        }

        //获取商品信息
        $productInfo = $this->db->select(['product_id','name','goods_type','stock','price_market','validity_date','date_type','use_date','expiration_date'])->where(['inter_id'=>$params['inter_id'],'product_id'=>$giftProductInfo['product_id']])
            ->get('soma_catalog_product_package')->row_array();

        //礼包登记信息
        $productInfo['gift_record_info'] = $giftDetailInfo;
        $productInfo['validity_date'] = date('Y-m-d',time());
        $productInfo['expiration_date'] = $productInfo['date_type'] == 2 ? date("Y年m月d日",strtotime("+".$productInfo['use_date']." day",strtotime($productInfo['validity_date']))) : date('Y年m月d日',strtotime($productInfo['expiration_date']));

        //是否是组合商品
        if($productInfo['goods_type'] == 3){
            //获取子商品名称
            $groupProductInfo = $this->db->select(['parent_pid','child_pid','num'])->where(['inter_id'=>$params['inter_id'],'parent_pid'=>$productInfo['product_id']])
                ->get('soma_product_package_link')->result_array();
            //获取子商品id
            $childProductIdArr = arrayColumn($groupProductInfo,'child_pid');
            $childProductInfo = $this->db->select(['product_id','name'])->where(['inter_id'=>$params['inter_id']])->where_in('product_id',$childProductIdArr)
                ->get('soma_catalog_product_package')->result_array();
            //数组转换
            $childProductInfo = arrayField($childProductInfo,'product_id');
            foreach($groupProductInfo as $key=>$val){
                $groupProductInfo[$key]['name'] = empty($childProductInfo[$val['child_pid']]['name']) ? '' : $childProductInfo[$val['child_pid']]['name'];
                unset($groupProductInfo[$key]['parent_pid']);
                unset($groupProductInfo[$key]['child_pid']);
            }
            $productInfo['child_product_info'] = $groupProductInfo;

        }else{
            $productInfo['child_product_info'] = [['name'=>$productInfo['name'],'num'=>1]];
        }
        //结果返回
        return ['status'=>true,'message'=>$productInfo];
    }


    /**
     * 确认领取礼包 生成订单
     */
    public function giftOrderCreate($params){


        $this->load->model('soma/Gift_detail_model', 'Gift_detail_model');
        $Gift_detail_model = $this->Gift_detail_model;

        //获取礼包信息
        $giftDetailRes = $Gift_detail_model->_shard_db_r($this->inter_id)->select(['sf.product_id','sfd.gift_num','sfd.saler_id','sfd.inter_id'])->from('soma_gift sf')
            ->join('soma_gift_detail sfd','sfd.gift_id = sf.id','left')
            ->where(['sfd.inter_id'=>$params['inter_id'],'sfd.id'=>$params['id'],'sfd.request_token'=>$params['request_token']])
            ->get()->row_array();

        //根据saler_id获取hotel_id
        $hotelIdInfo = $this->db->select(['hotel_id','qrcode_id'])->where(['inter_id'=>$giftDetailRes['inter_id'],'qrcode_id'=>$giftDetailRes['saler_id']])->get('hotel_staff')->row_array();

        //获取粉丝的姓名
        $fansName = $this->db->select(['nickname'])->where(['openid'=>$params['openid']])->get('iwide_fans')->row_array();

        $giftOrderData = array();
        $giftOrderData['act_id'] = "";
        $giftOrderData['address_id'] = "";
        $giftOrderData['bpay_passwd'] = "giftPasswd";
        $giftOrderData['business'] = "package";
        $giftOrderData['csrf_token'] = "f";
        $giftOrderData['grid'] = "";
        $giftOrderData['inid'] = "";
        $giftOrderData['mcid'] = "";
        $giftOrderData['name'] = empty($fansName['nickname']) ? '' : $fansName['nickname'];
        $giftOrderData['password'] = "";
        $giftOrderData['phone'] = "";
        $giftOrderData['product_id'] = $giftDetailRes['product_id'];
        $giftOrderData['psp_setting'] = "";
        $giftOrderData['qty'] = $giftDetailRes['gift_num'];
        $giftOrderData['quote'] = "";
        $giftOrderData['quote_type'] = "";
        $giftOrderData['scope_product_link_id'] = "";
        $giftOrderData['settlement'] = "giftType"; //礼包领取
        $giftOrderData['token'] = "";
        $giftOrderData['type'] = "";
        $giftOrderData['u_type'] = "-1";
        //获取
        $giftOrderData['hotel_id'] = $hotelIdInfo['hotel_id'];
        $giftOrderData['saler'] = $hotelIdInfo['qrcode_id'];
        $giftOrderData['fans_saler'] = '';
        $giftOrderData['saler_group'] = '';
        $giftOrderData['inter_id'] = $giftDetailRes['inter_id'];
        $giftOrderData['openid'] = $params['openid'];
        //订单
        $result = \App\services\soma\OrderService::getInstance()->create($giftOrderData);
        return ['result'=>$result,'params'=>$giftOrderData];

    }


    /**
     * 微信支付回调操作
     * URL format: index.php/soma/payment/wxpay_return/15301616967
     */
    public function gift_receive_callback($order_id = '')
    {
        $xml = file_get_contents ( 'php://input' );
        $this->load->model('soma/Sales_order_model');

        $out_trade_no = '';
        $order_simple= $this->Sales_order_model->get_order_simple($order_id);
        //初始化数据库分片配置
        if($order_simple['inter_id'] ){
            $this->load->model('soma/shard_config_model', 'model_shard_config');
            $this->current_inter_id= $order_simple['inter_id'];
            $this->db_shard_config= $this->model_shard_config->build_shard_config($order_simple['inter_id']);
            //print_r($this->db_shard_config);
        }else{
            return false;
        }

        //签名校验数据的合法性
        $this->load->model('pay/pay_model');
        $pay_config= $this->pay_model->get_pay_paras($order_simple['inter_id']);
        $pay_key= isset($pay_config['key'])? $pay_config['key']: '';
        if(empty($pay_key) ){
           return false;
        }

        //处理结果成功与否
        $this->load->helper('soma/package');
        $debug = true;
        if ($debug) write_log('soma payment wxpay_return invoked');
        //公共保存部分
        $this->load->model('soma/sales_payment_model');
        $payment_model= $this->sales_payment_model;

        $log_data= array();
        $log_data['paid_ip']= $this->input->ip_address();
        $log_data['paid_type']= $payment_model::PAY_TYPE_WX;
        $log_data['order_id']= $order_id;
        $log_data['openid']= $order_simple['openid'];
        $log_data['business']= $order_simple['business'];
        $log_data['settlement']= $order_simple['settlement'];
        $log_data['inter_id']= $order_simple['inter_id'];
        $log_data['hotel_id']= $order_simple['hotel_id'];
        $log_data['grand_total']= $order_simple['grand_total'];
        $log_data['transaction_id']= '';
        $this->Sales_order_model->order_payment( $log_data );
        $this->Sales_order_model->order_payment_post();
        return true;
    }


}