<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/***
 * 礼包派送 Controller
 * Created by Wuqd on.
 * Created Time 2017-09-03
 */


class Gift_delivery extends MY_Admin_Soma{

    static public $inter_id;

    /***
     * Gift_delivery constructor.
     * 构造方法
     */
    public function __construct(){

        parent::__construct();

        //获取商家inter_id
        self::$inter_id = $this->session->get_admin_inter_id();
        //inter_id校验
        if(empty(self::$inter_id)){
            //返回提示
            return $this->json(['status'=>false,'message'=>'获取inter_id失败!']);
        }
    }


    /***
     * 获取礼包列表
     */
    public function getGiftList(){

        $params = array();
        $params['page'] = $this->input->post('page');
        $params['page'] = empty($params['page']) ? 0 : intval($params['page'] - 1);
        //获取商家inter_id
        $params['inter_id'] = self::$inter_id;
        //加载gift_delivery_model
        $this->load->model('soma/gift_delivery_model');
        //获取礼包列表
        $resultInfo = $this->gift_delivery_model->getGiftListData($params);

        //结果返回
        return $this->json($resultInfo['giftListData']);
    }


    /**
     * 获取可添加礼包的商品列表
     */
    public function getProductList(){

        $params = array();
        $params['name'] = $this->input->post('name');
        $params['inter_id'] = self::$inter_id;
        //加载gift_delivery_model
        $this->load->model('soma/gift_delivery_model');
        //获取商品列表
        $resultInfo = $this->gift_delivery_model->getProductListData($params);

        //结果返回
        return $this->json($resultInfo['productListData']);

    }


    /**
     * 选择添加礼包
     */
    public function selectAddGift(){

        $productIdArr = $this->input->post('product_id');
        $params = array();
        $params['start_time'] = $this->input->post('start_time');
        $params['end_time'] = $this->input->post('end_time');
//        $productIdArr = ['1','3','10023','10271'];
//        $productIdArr = [];
//        $params['start_time'] = '2017-09-01 00:00:00';
//        $params['end_time'] = '2017-09-05 23:59:59';
        if(empty($params['start_time']) || empty($params['end_time'])){
            return $this->json(['status'=>false,'message'=>'礼包有限时间不能为空!']);
        }

        if(strtotime($params['start_time']) > strtotime($params['end_time'])){
            return $this->json(['status'=>false,'message'=>'开始时间不能大于结束时间!']);
        }

        $params['inter_id'] = self::$inter_id;
        //商品校验是否属于inter_id 不属于则过滤
        $productWhere = ['inter_id'=>$params['inter_id']];
        $productIdArr = empty($productIdArr) ? '' : $productIdArr;
        $productIdRes = $this->db->select(['product_id','inter_id'])->where($productWhere)->where_in('product_id',$productIdArr)
            ->get('soma_catalog_product_package')->result_array();

        $productIdArr = arrayColumn($productIdRes,'product_id');
        //加载gift_delivery_model
        $this->load->model('soma/gift_delivery_model');
        if(empty($productIdArr)){

            //删除inter_id 礼包
            $resultInfo = $this->gift_delivery_model->selectProductAddGift($productIdArr,'','delete',$params);
        }else{

            //获取已添加至礼包的product_id
            $giftWhere = ['inter_id'=>$params['inter_id']];
            $giftProductRes = $this->db->select(['inter_id','product_id'])->where($giftWhere)->get('soma_gift')->result_array();
            //已添加至礼包的product_id
            $giftProductIdArr = arrayColumn($giftProductRes,'product_id');
            $giftProductIdArr = empty($giftProductIdArr) ? [] : $giftProductIdArr;
            //求出差集(获取要添加至礼包的product_id)
            $addGiftProductId = array_diff($productIdArr,$giftProductIdArr);
            //求出差集(获取要删除礼包的product_id)
            $delGiftProductId = array_diff($giftProductIdArr,$productIdArr);
            //获取商品列表
            $resultInfo = $this->gift_delivery_model->selectProductAddGift($addGiftProductId,$delGiftProductId,'add',$params);

        }

        return $this->json($resultInfo);

    }








}













