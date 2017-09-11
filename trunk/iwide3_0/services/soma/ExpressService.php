<?php
/**
 * Date: 2017/6/22
 * Time: 15:05
 */

namespace App\services\soma;


use App\services\BaseService;
use App\services\Result;

class ExpressService extends BaseService
{
    /**
     * @return ExpressService
     * @author renshuai  <renshuai@jperation.cn>
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }


    /**
     * @return Result
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function regionTree()
    {
        $result = new Result();

        $redisKey = 'SOMA:RegionTree';
        $redis = $this->getCI()->get_redis_instance();

        if ($redis) {
            if ($tree = $redis->get($redisKey)) {

                $tree = json_decode($tree, true);

            } else {
                $this->getCI()->load->model('soma/Cms_region_model', 'RegionModel');
                $list = $this->getCI()->RegionModel->all();
                $tree = $this->createTree($list);

                $redis->set($redisKey, json_encode($tree));
            }
            $result->setData($tree);
            $result->setStatus(Result::STATUS_OK);
        }

        return $result;
    }


    /**
     * @param $array
     * @param int $pid
     * @return array
     * @author renshuai  <renshuai@jperation.cn>
     */
    protected function createTree($array, $pid = 0){
        $ret = array();
        foreach($array as $key => $value){
            if($value['parent_id'] == $pid){
                $tmp= $value;
                unset($array[$key]);
                $arr = $this->createTree($array, $value['region_id']);
                !empty($arr) && $tmp['children'] = $arr;
                $ret[] = $tmp;
            }
        }

        return $ret;
    }


    public function getUserAddressList($openId,$interId,$limit = 5){

        $this->getCI()->load->model('soma/Customer_address_model','customerAddressModel');
        $customerAddressModel = $this->getCI()->customerAddressModel;

        $userAddressList = $customerAddressModel->get(
            [
                'inter_id',
                'openid'
            ],
            [
                $interId,
                $openId
            ],
            '*',
            [
                'limit' => $limit,
                'orderBy' => 'updated_at desc',
            ]
        );

        if(empty($userAddressList)) return array();

        $this->getCI()->load->model('soma/Cms_region_model', 'RegionModel');
        $wholeArea = $this->getCI()->RegionModel->all();

        foreach($userAddressList as $key => $singleAddress){
            $province = $singleAddress['province'] ;
            $city =  $singleAddress['city'] ;
            $region = $singleAddress['region'];

            $province_name = $city_name = $region_name = '';

            foreach($wholeArea as $mark){

                if($mark['region_id'] == $province){
                    $province_name =  $mark['region_name'];
                }
                if($mark['region_id'] == $city){
                    $city_name =  $mark['region_name'];
                }
                if($mark['region_id'] == $region){
                    $region_name =  $mark['region_name'];
                }

                /*过滤一些不必要的数据*/
                if(isset($singleAddress['openid'])) unset($userAddressList[$key]['openid']);
                if(isset($singleAddress['hotel_id'])) unset($userAddressList[$key]['hotel_id']);
                if(isset($singleAddress['inter_id'])) unset($userAddressList[$key]['inter_id']);
                if(isset($singleAddress['created_at'])) unset($userAddressList[$key]['created_at']);
//                if(isset($singleAddress['updated_at'])) unset($userAddressList[$key]['updated_at']);

                if($province_name && $city_name && $region_name){
                    break 1;
                }

            }
            $userAddressList[$key]['province_name'] = $province_name;
            $userAddressList[$key]['city_name'] = $city_name;
            $userAddressList[$key]['region_name'] = $region_name;

        }

        return $userAddressList;
    }


    /**
     * 根据openId、interId、addressId返回详细地址字符串
     * @param $openId
     * @param $interId
     * @param $addressId
     * @return null|string
     * @author liguanglong  <liguanglong@jperation.cn>
     */
    public function getRegion($openId, $interId, $addressId){

        $address = array();

        $this->getCI()->load->model('soma/Customer_address_model','customerAddressModel');
        $this->getCI()->load->model('soma/Cms_region_model','cmsRegionModel');
        $customerAddressModel = $this->getCI()->customerAddressModel;
        $cmsRegionModel = $this->getCI()->cmsRegionModel;

        $field = ['openid = ', 'address_id = ', 'status = ', 'inter_id = '];
        $value = [$openId, $addressId, $customerAddressModel::STATUS_ACTIVE, $interId];
        $model = $customerAddressModel->get($field, $value);

        if(!empty($model)){
            $regionIds = [$model[0]['province'], $model[0]['city'], $model[0]['region']];
            $regionList = $cmsRegionModel->cmsRegionModel->get(
                ['region_id'],
                [$regionIds],
                '*',
                ['limit' => count($regionIds), 'offset' => 0]
            );
            if(count($regionList)){
                foreach ($regionIds as $val){
                    foreach ($regionList as $vale){
                        if($vale['region_id'] == $val){
                            $address[] = $vale['region_name'];
                            break;
                        }
                    }
                }
            }
        }

        return $address;
    }


    /**
     * 保存地址
     * @param array $item
     * @return bool / address_id
     * @author liguanglong  <liguanglong@jperation.cn>
     */
    public function saveRegion($item = []){

        $arg = 'insert';

        $this->getCI()->load->model('soma/Customer_address_model','customerAddressModel');
        $customerAddressModel = $this->getCI()->customerAddressModel;

        $data = [
            'openid'     => isset($item['openid']) ? $item['openid'] : null,
            'hotel_id'   => isset($item['hotel_id']) ? $item['hotel_id'] : null,
            'inter_id'   => isset($item['inter_id']) ? $item['inter_id'] : null,
            'country'    => isset($item['country']) ? $item['country'] : null,
            'province'   => isset($item['province']) ? $item['province'] : null,
            'city'       => isset($item['city']) ? $item['city'] : null,
            'region'     => isset($item['region']) ? $item['region'] : null,
            'address'    => isset($item['address']) ? $item['address'] : null,
            'zip_code'   => isset($item['zip_code']) ? $item['zip_code'] : null,
            'phone'      => isset($item['phone']) ? $item['phone'] : null,
            'contact'    => isset($item['contact']) ? $item['contact'] : null,
            'status'     => $customerAddressModel::STATUS_ACTIVE,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if(isset($item['address_id']) && !is_null($item['address_id']) && $item['address_id'] > 0){
            $result = $customerAddressModel->get(
                [
                    'address_id',
                    'openid',
                    'inter_id'
                ],
                [
                    $item['address_id'],
                    $item['openid'],
                    $item['inter_id']
                ]);
            if(!empty($result)){
                $data['address_id'] = $item['address_id'];
                $arg = 'update';
            }
            else{
                $customerAddressModel->get_increase_id();
            }
        }
        else{
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['address_id'] = $customerAddressModel->get_increase_id();
        }

        $result = $customerAddressModel->soma_db_conn;

        if($arg === 'update'){
            $result = $result->where([
                        'address_id' => $item['address_id'],
                        'openid' => $item['openid'],
                        'inter_id' => $item['inter_id']
                      ]);
        }

        if($result->$arg($customerAddressModel->table_name(), $data)){
            return   $data['address_id'];
        }else{
            return false;
        }
    }


    /**
     * 删除地址
     * @param $addressId
     * @param $openId
     * @param $interId
     * @return bool
     * @author liguanglong  <liguanglong@jperation.cn>
     */
    public function deleteRegion($addressId, $openId, $interId){

        $this->getCI()->load->model('soma/Customer_address_model', 'customerAddressModel');
        $customerAddressModel = $this->getCI()->customerAddressModel;
        $result = $customerAddressModel->soma_db_conn
                                       ->where(['address_id' => $addressId, 'openid' => $openId, 'inter_id' => $interId])
                                       ->delete($customerAddressModel->table_name());

        return $result;
    }


}