<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 礼包派送 Model
 * Created by Wuqd on.
 * Created Time 2017-09-03 10:00
 */

class Gift_delivery_model extends MY_Model_Soma{

//    /**
//     * @return string the associated database table name
//     */
//    public function table_name($inter_id=NULL)
//    {
//        return $this->_shard_table('soma_gift', $inter_id);
//    }
//
//    public function table_primary_key()
//    {
//        return 'id';
//    }

    /**
     * 获取礼包列表
     */
    public function getGiftListData($params){

        $pageStart = $params['page']*20;
        //入库查询
        $giftListRes = $this->db->select(['id','inter_id','product_id'])->where(['inter_id'=>$params['inter_id']])
            ->limit($pageStart,20)->get('soma_gift')->result_array();

        //获取商品name与商品库存stock
        $productIdArr = arrayColumn($giftListRes,'product_id');
        $productInfoRes = $this->db->select(['product_id','name','stock','cat_id'])->where(['inter_id'=>$params['inter_id']])->where_in('product_id',$productIdArr)
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
            $giftListRes[$key]['name'] = $productInfoFieldRes[$val['product_id']]['name'];
            $giftListRes[$key]['stock'] = $productInfoFieldRes[$val['product_id']]['stock'];
            $giftListRes[$key]['cat_name'] = $catInfoFieldRes[$productInfoFieldRes[$val['product_id']]['cat_id']]['cat_name'];
        }
        //结果返回
        return ['giftListData'=>$giftListRes];
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
        $productListRes = $this->db->select(['product_id','stock','cat_id','name'])->where($productWhere)
            ->where_in('goods_type',['1','3'])->get('soma_catalog_product_package')->result_array();

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
            $productListRes[$key]['cat_name'] = $catNameFieldRes[$val['cat_id']]['cat_name'];
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

        $giftInfo = $this->db->select(['gift_id'])->where(['id'=>$params['gift_detail_id'],'inter_id'=>$params['inter_id'],'request_token'=>$params['request_token']])
            ->get('soma_gift_detail')->row_array();

        if(empty($giftInfo)){
            return ['status'=>false,'message'=>'请求参数有误!'];
        }

        //获取礼包商品id
        $giftInfo = $this->db->select(['product_id'])->where(['id'=>$giftInfo['gift_id']])->get('soma_gift')->row_array();

        if(empty($giftInfo)){
            return ['status'=>false,'message'=>'礼包不存在!'];
        }

        //获取商品信息
        $productInfo = $this->db->select(['product_id','name','goods_type','stock','price_market','date_type','use_date','expiration_date'])->where(['inter_id'=>$params['inter_id'],'product_id'=>$giftInfo['product_id']])
            ->get('soma_catalog_product_package')->row_array();

        //是否是组合商品
        if($productInfo['goods_type']){
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
                $groupProductInfo[$key]['name'] = $childProductInfo[$val['child_pid']]['name'];
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
     * 获取二维码确认领取详情页面
     */
    public function getQrcodeGiftDetailData($params){

        //获取礼包信息
        $giftDetailInfo = $this->db->select(['gift_id','record_info','orther_remark'])->where($params)->get('soma_gift_detail')->row_array();

        //获取礼包商品id
        $giftProductInfo = $this->db->select(['product_id'])->where(['inter_id'=>$params['inter_id'],'id'=>$params['gift_id']])
            ->get('soma_gift')->row_array();

        //获取商品信息
        $productInfo = $this->db->select(['product_id','name','goods_type','stock','price_market','date_type','use_date','expiration_date'])->where(['inter_id'=>$params['inter_id'],'product_id'=>$giftProductInfo['product_id']])
            ->get('soma_catalog_product_package')->row_array();

        //礼包登记信息
        $productInfo['gift_record_info'] = $giftDetailInfo;

        //是否是组合商品
        if($productInfo['goods_type']){
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
                $groupProductInfo[$key]['name'] = $childProductInfo[$val['child_pid']]['name'];
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

        //获取礼包信息
        $giftDetailRes = $this->db->select(['sf.product_id','sfd.gift_num','sfd.saler_id','sfd.inter_id'])->from('soma_gift sf')
            ->join('soma_gift_detail sfd','sfd.gift_id = sf.id','left')
            ->where(['sfd.inter_id'=>$params['inter_id'],'sfd.id'=>$params['id'],'sfd.request_token'=>$params['request_token']])
            ->get()->row_array();

        $this->db->db_select('iwide30dev_2016011813');
        //根据saler_id获取hotel_id
        $hotelIdInfo = $this->db->select(['hotel_id','qrcode_id'])->where(['inter_id'=>$giftDetailRes['inter_id'],'id'=>$giftDetailRes['saler_id']])->get('hotel_staff')->row_array();
        $giftOrderData = array();
        $giftOrderData['act_id'] = "";
        $giftOrderData['address_id'] = "";
        $giftOrderData['bpay_passwd'] = "";
        $giftOrderData['business'] = "package";
        $giftOrderData['csrf_token'] = "f";
        $giftOrderData['grid'] = "";
        $giftOrderData['inid'] = "";
        $giftOrderData['mcid'] = "";
        $giftOrderData['name'] = "wwwqqqddd";
        $giftOrderData['password'] = "";
        $giftOrderData['phone'] = "13610117050";
//        $giftOrderData['product_id'] = $giftDetailRes['product_id'];
        $giftOrderData['product_id'] = 14044;
        $giftOrderData['psp_setting'] = "";
        $giftOrderData['qty'] = $giftDetailRes['gift_num'];
        $giftOrderData['quote'] = "";
        $giftOrderData['quote_type'] = "";
        $giftOrderData['scope_product_link_id'] = "";
        $giftOrderData['settlement'] = "default"; //礼包领取
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
        $orderRes = \App\services\soma\OrderService::getInstance()->create($giftOrderData);
        echo '<pre>';
        var_dump($orderRes);die;

    }








}