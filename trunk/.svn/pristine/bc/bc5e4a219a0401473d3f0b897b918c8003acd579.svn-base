<?php

/**
 * User: renshuai <renshuai@mofly.cn>
 * Date: 2017/3/3
 * Time: 11:15
 *
 * @property Activity_killsec_group_model $somaActivityKillsecGroupModel
 * @property Activity_killsec_model $somaActivityKillsecModel
 * @property Activity_killsec_group_product_model $somaActivityKillsecGroupProductModel
 * @property Product_package_model $somaProductPackageModel
 * @property Activity_idx_model $somaActivityIdxModel
 *
 * @property Hotel_model $hotelModel
 *
 * @property Soma_Logger $kill_cron_logger
 *
 */
class Kill_Service extends MY_Service
{
    /**
     * Kill_Service constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $path = $this->modelName(Activity_killsec_group_model::class);
        $alias = $this->modelAlias(Activity_killsec_group_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);

        $path = $this->modelName(Activity_killsec_group_product_model::class);
        $alias = $this->modelAlias(Activity_killsec_group_product_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);


        $path = $this->modelName(Product_package_model::class);
        $alias = $this->modelAlias(Product_package_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);


        $path = $this->modelName(Activity_killsec_model::class);
        $alias = $this->modelAlias(Activity_killsec_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);

        $path = $this->modelName(Activity_idx_model::class);
        $alias = $this->modelAlias(Activity_idx_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);
    }

    //for test
    public function create_killsec()
    {
        $actID = 321;
        $data = [
            'status' => 2
        ];
        return $executeResult  = $this->somaActivityKillsecModel->update($actID, $data);

        $data = [
            'act_id' => 321,
            'create_time' => date('Y-m-d H:i:s')
        ];

//        return $this->somaActivityKillsecModel->create($data);
    }

    //todo maybe
    public function send_killsec_group_notice()
    {
        return 1;
    }

    //todo maybe
    public function subscribe_killsec_group()
    {

    }

    /**
     * @param $killId
     * @return bool
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function groupProductHasKill($killId)
    {
        $rows = $this->somaActivityKillsecGroupProductModel->getByKillId($killId);
        if (count($rows) > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param $productId
     * @param $interId
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getKillsecOfProduct($productId, $interId)
    {
        return $this->somaActivityKillsecModel->killsec_by_product_id($productId, $interId);
    }

    /**
     * @param array $group
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function groupTimes(Array $group)
    {
        return array(
            'show_time' => $this->somaActivityKillsecGroupModel->getShowTime($group)->getTimestamp(),
            'kill_time' => $this->somaActivityKillsecGroupModel->getKillTime($group)->getTimestamp(),
            'end_time' => $this->somaActivityKillsecGroupModel->getKillEndTime($group)->getTimestamp() //活动结束时间
        );
    }


    /**
     * @param $interID
     * @return mixed
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function groupOnly($interID)
    {
        return $this->somaActivityKillsecGroupModel->getByInterID($interID);
    }

    /**
     * 抢尾房主页面数据
     * @param $interId
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function groupMain($interId)
    {
        $group = $this->somaActivityKillsecGroupModel->getByInterID($interId);

        $hotelProducts = array();

        if ( !empty($group)) {
            //找到对应的秒杀和商品
            $temps = $this->somaActivityKillsecGroupProductModel->getByGroupID($group['id'], 'group_id, product_id, kill_id');

            $productIds = array();
            $killIds = array();
            foreach($temps as $temp) {
                $productIds[] = $temp['product_id'];
                $killIds[] = $temp['kill_id'];
            }

            //排除不可用商品
            $avaliableProducts = array();
            $products = $this->somaProductPackageModel->getByIds($productIds, $interId);

            $hotelIDs = array();
            foreach($products as $product) {
                if ($this->somaProductPackageModel->isAvaliable($product) ) {
                    $avaliableProducts[] = $product;
                    $hotelIDs[] = $product['hotel_id'];
                }
            }


            $this->load->model('hotel/Hotel_model', 'hotelModel');
            $hotels = array();
            if (!empty($hotelIDs)) {
                $hotels = $this->hotelModel->get_hotel_by_ids($interId, implode($hotelIDs, ','));
            }

            $kills = array();
            if (!empty($killIds)) {
                $kills = $this->somaActivityKillsecModel->get_available_killsec_list_byActIds($killIds);
            }

            $times = $this->groupTimes($group);

            foreach($hotels as $hotel) {
                foreach ($avaliableProducts as $avaliableProduct) {
                    if ($hotel['hotel_id'] == $avaliableProduct['hotel_id']) {
                        foreach($kills as $kill) {
                            if ($kill['product_id'] == $avaliableProduct['product_id']) {

                                $kill['over'] = false;

                                $currentTime = time();
                                if ( $times['kill_time'] < $currentTime && $currentTime < $times['end_time']) {

                                    $instances = $this->somaActivityKillsecModel->get_aviliable_instance( array(
                                        'act_id' => $kill['act_id'],
                                        'status' => array_keys($this->somaActivityKillsecModel->get_instance_status())
                                    ) );

                                    $kill['instances'] = $instances;

                                    if( !empty($instances[0]) ){
                                        $instance = $instances[0];
                                        if ($instance['status'] == Activity_killsec_model::INSTANCE_STATUS_FINISH ) {
                                            $kill['over'] = true;
                                        }
                                    }
                                }

                                $avaliableProduct['kill'] = $kill;
                            }
                        }
                        $hotelProducts[$hotel['hotel_id']]['products'][] = $avaliableProduct;
                        $hotelProducts[$hotel['hotel_id']]['hotel'] = $hotel;
                    }
                }
            }
        }

        $result = array(
            'group' => $group,
            'hotelProducts' => $hotelProducts
        );
        return $result;

    }

    /**
     * 抢尾房后台详情页面数据
     * @param string $interId
     * @return mixed
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function groupInfo($interId)
    {
        $this->load->model('wx/Publics_model', 'publicModel');

        $group = $this->somaActivityKillsecGroupModel->getByInterID($interId);

        $temps = array();
        if ( !empty($group)) {
            $temps = $this->somaActivityKillsecGroupProductModel->getByGroupID($group['id']);
            $productIds = array();
            foreach($temps as $temp) {
                $productIds[] = $temp['product_id'];
            }

            $products = array();
            if (!empty($productIds)) {
                $products = $this->somaProductPackageModel->getByIds($productIds, $interId);
            }

            foreach($temps as &$temp) {
                foreach($products as $product) {
                    if ($temp['product_id']== $product['product_id']) {
                        $temp['product_name'] = $product['name'];
                    }
                }
                $temp['schedule'] = json_decode($temp['schedule'], true);
            }

        } else {
            $group = array_merge($group, $this->somaActivityKillsecGroupModel->default_values());
        }
        $info = $this->publicModel->get_public_by_id($interId);
        $group['info'] = $info;

        //排除积分类商品
        $searchArr = array(
            'type !=' => Product_package_model::PRODUCT_TYPE_POINT,
            'inter_id' => $interId
        );
        $productList = $this->somaProductPackageModel->search($searchArr, $interId, 'product_id, inter_id, name');

        $result = array(
            'group' => $group,
            'extra' => $temps,
            'productList' => $productList
        );
        return $result;
    }


    /**
     * @param array $arr
     * @return bool
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function createGroup(Array $arr)
    {
        if (!isset($arr['inter_id'])) {
            return false;
        }

        $group = $this->somaActivityKillsecGroupModel->getByInterID($arr['inter_id']);


        if ($group) {
            return false;
        }

        if (!isset($arr['products'])) {
            return false;
        }
        $arr['products'] = json_decode($arr['products'], true);

        $result = false;

        $this->db->trans_begin();
        $arr['created_at'] = date('Y-m-d H:i:s');
        $createGroup = $this->somaActivityKillsecGroupModel->create($arr);
        if ($createGroup) {

            $groupID = $this->db->insert_id();

            $createGroupProductResult = false;
            $productIDs = array();
            foreach ($arr['products'] as $product) {

                //防止创建的时候选择同样的商品
                if (in_array($product['product_id'], $productIDs)) {
                    continue;
                }
                $productIDs[] = $product['product_id'];

                $product['group_id'] = $groupID;
                $product['kill_id'] = 0;
                $createGroupProductResult = $this->somaActivityKillsecGroupProductModel->create($product);
                if (!$createGroupProductResult) {
                    break;
                }
            }

            if ($createGroupProductResult) {
                $result = true;
            }
        }

        if (!$result || $this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->_log($this->somaActivityKillsecGroupModel);
            $this->_log($this->somaActivityKillsecGroupProductModel);
            $this->db->trans_commit();
        }

        return $result;
    }

    /**
     * @param array $arr
     * @return bool
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function updateGroup(Array $arr)
    {
//        $this->load->library('Soma_Logger', array(
//            'options' => array(
//                'prefix'       => 'soma_',
//            ),
//            'logDirectory' => APPPATH . 'logs' . DIRECTORY_SEPARATOR . 'soma' . DIRECTORY_SEPARATOR . 'killsec_group_cron',
//        ), 'kill_cron_logger');
//
//
//        $this->kill_cron_logger->info('update group ', $arr);


        if (!isset($arr['id'])) {
            return false;
        }

        $result = false;
        $group = $this->somaActivityKillsecGroupModel->getByID($arr['id']);
        if (empty($group)) {
            return false;
        }

        //开始秒杀前半个小时 到结束时间内不能修改数据
        $currentTime = time();
        $times = $this->groupTimes($group);
        if ( ($times['kill_time']-1800) < $currentTime && $currentTime < $times['end_time']) {
            return true;
        }

        $this->db->trans_begin();
        $updateGroupResult = $this->somaActivityKillsecGroupModel->update($group['id'], $arr);

        if ($updateGroupResult) {

            $updateGroupProductResult = $createGroupProductResult = $deleteGroupProductResult = true;

            $temps = $this->somaActivityKillsecGroupProductModel->getByGroupID($group['id']);

            $productIDs = array();
            foreach($temps as $item) {
                $productIDs[] = $item['product_id'];
            }

            $inputProductIDs = array();
            if (isset($arr['products'])) {
                $arr['products'] = json_decode($arr['products'], true);
                foreach($arr['products'] as $product) {
                    $inputProductIDs[] = $product['product_id'];
                }
            }

            //for update
            $updateProductIDs = array_intersect($productIDs, $inputProductIDs);
            if (isset($arr['products'])) {
                foreach($arr['products'] as $product) {
                    if (in_array($product['product_id'], $updateProductIDs)) {

                        $productID = $product['product_id'];
                        $updateGroupProductResult = $this->somaActivityKillsecGroupProductModel->update($group['id'], $productID, $product);
                        if (!$updateGroupProductResult) {
                            break;
                        }
                    }
                }
            }

            //for create
            $createProductIDs = array_diff($inputProductIDs, $productIDs);
            if (isset($arr['products'])) {
                foreach($arr['products'] as $product) {
                    if (in_array($product['product_id'], $createProductIDs)) {
                        $product['group_id'] = $group['id'];
                        $product['kill_id'] = 0;
                        $createGroupProductResult = $this->somaActivityKillsecGroupProductModel->create($product);
                        if (!$createGroupProductResult) {
                            break;
                        }
                    }
                }
            }

            //for delete
            $deleteProductIDs = array_diff($productIDs, $inputProductIDs);
            foreach($temps as $item) {
                if (in_array($item['product_id'], $deleteProductIDs)) {
                    $deleteGroupProductResult = $this->somaActivityKillsecGroupProductModel->delete($group['id'], $item['product_id']);
                    if (!$deleteGroupProductResult) {
                        break;
                    }
                }
            }

            $result = $updateGroupProductResult && $createGroupProductResult && $deleteGroupProductResult;

        }

        if (!$result || $this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->_log($this->somaActivityKillsecGroupModel);
            $this->_log($this->somaActivityKillsecGroupProductModel);
            $this->db->trans_commit();
        }

//        $test = [
//            'create' => $createProductIDs,
//            'update' => $updateProductIDs,
//            'delete' => $deleteProductIDs,
//            'test' => in_array(5, $deleteProductIDs),
//        ];

        return $result;
    }

    /**
     *
     * 批量添加／更新秒杀
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function killsecBatch()
    {

        $this->load->library('Soma_Logger', array(
            'options' => array(
                'prefix'       => 'soma_',
            ),
            'logDirectory' => APPPATH . 'logs' . DIRECTORY_SEPARATOR . 'soma' . DIRECTORY_SEPARATOR . 'killsec_group_cron',
        ), 'kill_cron_logger');


        $params = array(
            'pagination' => array(
                'limit' => 50,
                'offset' => 0,
                'page' => 0
            )
        );

        $resultArr = array();
        //找到记录
        while($rows = $this->somaActivityKillsecGroupProductModel->search($params)) {

            $this->kill_cron_logger->info('killsec_group_product count is ' . count($rows));

            $executeResult = true;

            $this->db->trans_begin();
            foreach($rows as $row) {

                $group = $this->somaActivityKillsecGroupModel->getByID($row['group_id']);

                //活动时间内才更新
                $currentTime = time();
                if ( date_create($group['start_time'])->getTimestamp() > $currentTime || $currentTime > date_create($group['end_time'])->getTimestamp() ) {
                    continue;
                }

                if (!empty($group)){

                    $group['product_id'] = $row['product_id'];
                    $data = $this->_getKillsec($group, $row['schedule']);

                    if (!empty($data)){


                        //添加秒杀
                        if ($row['kill_id'] == 0) {
                            //活动期间不能添加
                            $killTime = $this->somaActivityKillsecGroupModel->getKillTime($group);
                            $endTime = $killTime->modify('+' . $group['last_time'] . ' hour');

                            echo 'kill create kill time is ' . $killTime->format('Y-m-d H:i:s'), ' end time is ' . $endTime->format('Y-m-d H:i:s'), ' current time is ' . date('Y-m-d H:i:s'), PHP_EOL;

                            if ( time() > $killTime->modify('-35 minute')->getTimestamp() && time() < $endTime->modify('+5 minute')->getTimestamp()) {
                                $executeResult = true;

                                continue;
                            }

                            $idxArr = array(
                                'act_type' => Activity_idx_model::ACT_TYPE_KILL,
                                'act_name' => $group['name'],
                                'status' => Activity_idx_model::STATUS_Y,
                            );

                            $executeResult = $this->somaActivityIdxModel->create($idxArr);
                            $killID = $this->db->insert_id();
                            if ($executeResult) {
                                $data['act_id'] = $killID;
                                $data['create_time'] = date('Y-m-d H:i:s');
                                $executeResult  = $this->somaActivityKillsecModel->create($data);

                                if ($executeResult) {
                                    $executeResult = $this->somaActivityKillsecGroupProductModel->update($row['group_id'], $group['product_id'], array(
                                        'kill_id' => $killID
                                    ));
                                }
                            }

                        } else { //更新秒杀
                            $actID = $row['kill_id'];

                            $kill = $this->somaActivityKillsecModel->getByID($actID);

                            //活动期间不能更新
                            $killTime = date_create($kill['killsec_time']);
                            $endTime = date_create($kill['end_time']);

                            echo 'kill update kill time is ' . $killTime->format('Y-m-d H:i:s'), ' end time is ' . $endTime->format('Y-m-d H:i:s'), ' current time is ' . date('Y-m-d H:i:s'), PHP_EOL;
                            if ( time() > $killTime->modify('-35 minute')->getTimestamp() && time() < $endTime->modify('+5 minute')->getTimestamp()) {
                                $executeResult = true;

                                continue;
                            }

                            $executeResult  = $this->somaActivityKillsecModel->update($actID, $data);
                        }
                    }

                }

                if (! $executeResult) {
                    break;
                }
            }

            if (!$executeResult || $this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();

                $resultArr[] = false;
            } else {
                $this->db->trans_commit();

                $params['pagination']['page'] += 1;
                $params['pagination']['offset'] += $params['pagination']['page'] * $params['pagination']['limit'];

                $resultArr[] = true;
            }
        }

        return $resultArr;
    }

    /**
     * @param array $arr
     * @param $schedule
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    private function _getKillsec(Array $arr, $schedule)
    {
        $product = $this->somaProductPackageModel->getByID($arr['product_id'], $arr['inter_id']);
        $productName = '';
        $hotelId = 0;
        if (!empty($product)) {
            $productName = $product['name'];
            $hotelId = $product['hotel_id'];
        }

        $killArr = array(
            'inter_id' => $arr['inter_id'],
            'hotel_id' => $hotelId,
            'act_name' => $arr['name'],
            'act_type' => Activity_killsec_model::ACT_TYPE_KILLSEC,
            'schedule_type' => Activity_killsec_model::SCHEDULE_TYPE_FIX,
            'product_id' => $arr['product_id'],
            'product_name' => $productName,
            'killsec_price' => '',
            'killsec_count' => '',
            'killsec_permax' => $arr['buy_limit'],
            'start_time' => '',
            'killsec_time' => '',
            'end_time' => '',
            'status' => Activity_killsec_model::STATUS_TRUE
        );


        $scheduleArr = json_decode($schedule, true);


        $result = array();

        foreach($scheduleArr as $item) {
            if ( isset($item['time']) && date('w') === strval($item['time']) ) {
                //必须填写了 价格和库存 这两个参数
                if (empty($item['num']) || empty($item['price'] )) {
                    continue;
                }
                $killArr['killsec_count'] = $item['num'];
                $killArr['killsec_price'] = $item['price'];
                $killArr['killsec_time'] = date('Y-m-d') . ' ' . date('H:i:s', strtotime($arr['kill_time']));
                $killArr['start_time'] = date('Y-m-d') . ' ' . date('H:i:s', strtotime($arr['show_time']));
                $killArr['end_time'] = date_create($killArr['killsec_time'])->modify(' +' . $arr['last_time'] . ' hour')->format('Y-m-d H:i:s');
                $result = $killArr;
            }
        }

        return $result;
    }

    # 秒杀监控程序开始

    /**
     * Gets the killsec monitor data.
     *
     * @param      string  $inter_id  The inter identifier
     * @param      int     $act_id    The activity identifier
     *
     * @return     array   The killsec monitor data.
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    public function getKillsecMonitorData($inter_id = null, $act_id = null)
    {
        $path  = $this->modelName(Activity_killsec_model::class);
        $alias = $this->modelAlias(Activity_killsec_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);

        $killsecData   = $this->somaActivityKillsecModel->monitorActivityInfo($inter_id, $act_id);
        $instanceData  = $this->somaActivityKillsecModel->monitorInstanceInfo(array_keys($killsecData), $inter_id);
        $redisConnData = $this->somaActivityKillsecModel->monitorRedisConnInfo();
        $redisKillData = $this->somaActivityKillsecModel->monitorRedisKillsecInfo($instanceData['ins_ids']);

        $data = $this->_formatMonitorGridData($killsecData, $instanceData, $redisConnData, $redisKillData);

        return $data;
    }

    /**
     * Gets the killsec monitor grid header.
     *
     * @return     array  The killsec monitor grid header.
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    public function getKillsecMonitorGridHeader() {
        $key_map = array(
            'act_id'          => '活动ID',
            'act_name'        => '活动名',
            'product_id'      => '产品ID',
            'product_name'    => '产品名',
            'killsec_time'    => '秒杀时间',
            'end_time'        => '结束时间',
            'ins_create_time' => '实例创建时间',
            'instance_id'     => '实例ID',
            'killsec_count'   => '总名额',
            'least_count'     => '剩余名额',
            'instance_status' => '实例状态',
            'k_server_conn'   => '秒杀Redis',
            'l_server_conn'   => '加锁Redis',
            'ins_init_lock'   => '实例锁',
            'ins_user_lock'   => '订单锁',
        );

        $header = array();
        foreach ($key_map as $index => $title) {
            $header[] = array('title' => $title);
        }
        return $header;
    }

    /**
     * 将提取到的秒杀信息组合成监控列表信息
     *
     * @param      array  $actData        The activity data
     * @param      array  $insData        The instance data
     * @param      array  $redisData      The redis connect data
     * @param      array  $redisKillData  The redis killsec data
     *
     * @return     array  监控列表信息
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    protected function _formatMonitorGridData($actData, $insData, $redisData, $redisKillData)
    {
        // 拼接秒杀信息到活动信息中，多个秒杀实例时，以秒杀记录个数为准
        $ins_grid = array();
        foreach($actData as $act) {
            $row                    = array();
            $row['act_id']          = $act['act_id'];
            $row['act_name']        = $act['act_name'];
            $row['product_id']      = $act['product_id'];
            $row['product_name']    = $act['product_name'];
            $row['killsec_time']    = $act['killsec_time'];
            $row['end_time']        = $act['end_time'];
            $row['ins_create_time'] = date('Y-m-d H:i:s', strtotime($act['killsec_time']) - Activity_killsec_model::PREVIEW_TIME);
            $row['instance_id']     = '未生成';
            $row['killsec_count']   = '未生成';
            $row['least_count']     = '未生成';
            $row['instance_status'] = '未生成';

            if(isset($insData[$act['act_id']]))
            {
                foreach ($insData[$act['act_id']] as $instance)
                {
                    $_tmp = $row;
                    $_tmp['instance_id'] = $instance['instance_id'];
                    if($instance['status'] == Activity_killsec_model::INSTANCE_STATUS_PREVIEW)
                    {
                        $_tmp['instance_status'] = '活动准备中';
                    }
                    else if($instance['status'] == Activity_killsec_model::INSTANCE_STATUS_GOING)
                    {
                        $_tmp['instance_status'] = '活动进行中';
                    }
                    else if($instance['status'] == Activity_killsec_model::INSTANCE_STATUS_FINISH)
                    {
                        $_tmp['instance_status'] = '活动已结束';
                    }
                    else
                    {
                        $_tmp['instance_status'] = '<span style="color:red">未知活动状态信息</span>';
                    }

                    $_tmp['killsec_count'] = $instance['killsec_count'];
                    if(isset($redisKillData['killsec'][$instance['instance_id']]))
                    {
                        $data = $redisKillData['killsec'][$instance['instance_id']];
                        if(isset($data['token_key'])
                            && $data['token_key']['exist'] == Soma_base::STATUS_TRUE)
                        {
                            $_tmp['least_count'] = $data['token_key']['info']['size'];
                        }
                        else
                        {
                            $_tmp['least_count'] = 0;
                        }
                    }
                    else
                    {   if($instance['status'] == Activity_killsec_model::INSTANCE_STATUS_GOING
                        || ($instance['status'] == Activity_killsec_model::INSTANCE_STATUS_PREVIEW
                            && time() > strtotime($instance['start_time'])))
                        {
                            $_tmp['least_count'] = '<span style="color:red">检测不到Redis信息，请检查服务器运行状况</span>';
                        }
                    }

                    // 修复秒杀时间，结束时间，实例创建时间为实例对应时间,循环秒杀已经修改了上一次活动时间
                    $_tmp['killsec_time']    = $instance['start_time'];
                    $_tmp['end_time']        = $instance['close_time'];
                    $_tmp['ins_create_time'] = date('Y-m-d H:i:s', strtotime($instance['start_time']) - Activity_killsec_model::PREVIEW_TIME);

                    $ins_grid[] = $_tmp;
                }
            }
            else
            {
                if(time() - strtotime($row['ins_create_time']) > Activity_killsec_model::WARNING_TIME)
                {
                    $row['killsec_time'] = '<span style="color:red">' . $row['killsec_time'] . '</span>';
                    $row['ins_create_time'] = '<span style="color:red">' . $row['ins_create_time'] . '</span>';
                }
                $ins_grid[] = $row;
            }
        }

        // 拼接Redis信息到记录中，冗余数据，仅为显示给运营查看
        $grid = array();
        foreach ($ins_grid as $row)
        {
            $_tmp = $row;
            if($redisData['k_server_conn'] == Soma_base::STATUS_TRUE)
            {
                $_tmp['k_server_conn'] = '链接成功';
            }
            else
            {
                $_tmp['k_server_conn'] = '<span style="color:red;">链接失败</span>';
            }

            if($redisData['l_server_conn'] == Soma_base::STATUS_TRUE)
            {
                $_tmp['l_server_conn'] = '链接成功';
            }
            else
            {
                $_tmp['l_server_conn'] = '<span style="color:red;">链接失败</span>';
            }

            if($redisData['lock']['init_key'] == Soma_base::STATUS_TRUE)
            {
                $_tmp['ins_init_lock'] = '未加锁';
            }
            else
            {
                $_tmp['ins_init_lock'] = '<span style="color:red;">已加锁</span>';
            }

            if($redisData['lock']['user_key'] == Soma_base::STATUS_TRUE)
            {
                $_tmp['ins_user_lock'] = '未加锁';
            }
            else
            {
                $_tmp['ins_user_lock'] = '<span style="color:red;">已加锁</span>';
            }
            $grid[] = array_values($_tmp);
        }

        return $grid;
    }

    /**
     * 删除秒杀相关锁
     *
     * 1:实例锁 2:订单锁
     *
     * @param      int   $type   The type [1|2]
     *
     * @return     bool  成功返回true,失败返回false
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    public function deleteKillsecLock($type)
    {
        $path  = $this->modelName(Activity_killsec_model::class);
        $alias = $this->modelAlias(Activity_killsec_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);

        return $this->somaActivityKillsecModel->deleteKillsecLock($type);
    }


    /**
     * 定时任务监控秒杀状态
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    public function monitorKillsecService()
    {
        $path  = $this->modelName(Activity_killsec_model::class);
        $alias = $this->modelAlias(Activity_killsec_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);

        $killsecData   = $this->somaActivityKillsecModel->monitorActivityInfo();
        $instanceData  = $this->somaActivityKillsecModel->monitorInstanceInfo(array_keys($killsecData));
        $redisConnData = $this->somaActivityKillsecModel->monitorRedisConnInfo();
        $redisKillData = $this->somaActivityKillsecModel->monitorRedisKillsecInfo($instanceData['ins_ids']);

        $this->load->library('Soma_Logger', array(
            'options' => array(
                'prefix'       => 'soma_',
            ),
            'logDirectory' => APPPATH . 'logs' . DIRECTORY_SEPARATOR . 'soma' . DIRECTORY_SEPARATOR . 'killsec_monitor',
        ), 'monitor_logger');

        $this->monitor_logger->info('check killsec sevice start!');

        $this->_checkRedisConnInfo($redisConnData);
        $this->_checkKillsecInstance($killsecData, $instanceData);
        $this->_checkKillsecRedis($instanceData, $redisKillData['killsec']);

        $this->monitor_logger->info('check killsec sevice end!');
    }

    /**
     * 检测Redis链接并记录相关日志
     *
     * @param      array  $connData  The redis connection data
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    protected function _checkRedisConnInfo($connData)
    {
        if($connData['k_server_conn'] == Soma_base::STATUS_FALSE)
        {
            $this->monitor_logger->warning('killsec redis server disconnect!');
        }

        if($connData['l_server_conn'] == Soma_base::STATUS_FALSE)
        {
            $this->monitor_logger->warning('cron lock redis server disconnect!');
        }
        else
        {
            if($connData['lock']['init_key'] == Soma_base::STATUS_FALSE)
            {
                // $this->monitor_logger->warning('killsec initial cronjob is locked!');
            }
            if($connData['lock']['init_key'] == Soma_base::STATUS_FALSE)
            {
                // $this->monitor_logger->warning('killsec order cronjob is locked!');
            }
        }
    }

    /**
     * 检查秒杀活动是否正确产生活动实例，并记录相关日志
     *
     * @param      array  $activitys  The activitys
     * @param      array  $instances  The instances
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    protected function _checkKillsecInstance($activitys, $instances)
    {
        $nowTime = date('Y-m-d H:i:s');
        $nonExistIns = $repeatIns = array();
        foreach ($activitys as $activity)
        {
            $insCrateTime = date('Y-m-d H:i:s', strtotime($activity['killsec_time']) - Activity_killsec_model::PREVIEW_TIME);
            $warningFlag = (bool) ((strtotime($nowTime) - strtotime($insCrateTime) > Activity_killsec_model::WARNING_TIME) && strtotime($activity['end_time']) > strtotime($nowTime));
            if(isset($instances[$activity['act_id']]))
            {
                // 可能实例信息不一定跟活动信息匹配，也应报警
                $existFlag = false;
                foreach ($instances[$activity['act_id']] as $instance) {
                    if($activity['killsec_time'] == $instance['start_time']
                        && $activity['end_time'] == $instance['close_time'])
                    {
                        if($existFlag)
                        {
                            // 重复实例
                            $repeatIns[] = $activity['act_id'];
                        }
                        $existFlag = true;
                        break;
                    }
                }
                if(!$existFlag && $warningFlag)
                {
                    $nonExistIns[] = $activity['act_id'];
                }

            }
            else
            {
                if($warningFlag)
                {
                    $nonExistIns[] = $activity['act_id'];
                }
            }
        }

        if(count($nonExistIns) > 0)
        {
            $idStr = implode(',', $nonExistIns);
            $this->monitor_logger->warning("The activity [$idStr] did not produce an instance in ten minutes!");
        }

        if(count($repeatIns) > 0)
        {
            $idStr = implode(',', $repeatIns);
            $this->monitor_logger->warning("Activity [$idStr] instance is repeated!");
        }
    }

    /**
     * 检查实例与Redis信息对应关系
     *
     * @param      <type>  $instances  The instances
     * @param      <type>  $redisData  The redis data
     */
    protected function _checkKillsecRedis($instances, $redisData)
    {
        $nonExistIns = $lostCountIns = array();

        unset($instances['ins_ids']);
        foreach ($instances as $act_id => $act_instances)
        {
            foreach ($act_instances as $instance)
            {
                if($instance['status'] == Activity_killsec_model::INSTANCE_STATUS_PREVIEW)
                {
                    $redisCreateTime = date('Y-m-d H:i:s', strtotime($instance['start_time']) - Activity_killsec_model::PRESTART_TIME);
                    if(time() - strtotime($redisCreateTime) > Activity_killsec_model::WARNING_TIME)
                    {
                        $nonExistIns[] = $instance['instance_id'];
                    }
                }

                if($instance['status'] == Activity_killsec_model::INSTANCE_STATUS_GOING)
                {
                    if(!isset($redisData[$instance['instance_id']]))
                    {
                        $nonExistIns[] = $instance['instance_id'];
                    }
                    else
                    {
                        $data       = $redisData[$instance['instance_id']];
                        $redisCount = 0;
                        $redisCount += $data['token_key']['info']['size'];
                        $redisCount += $data['cache_key']['info']['size'];
                        $redisCount += $data['order_key']['info']['size'];
                        if($redisCount < $instance['killsec_count']
                            && $data['token_key']['info']['size'] <= ceil($instance['killsec_count']/2))
                        {
                            // redis中的秒杀名额少于秒杀总配额，并且剩余名额少于总名额一半
                            $lostCountIns[] = $instance['instance_id'];
                        }
                    }
                }
            }
        }

        if(count($nonExistIns) > 0)
        {
            $idStr = implode(',', $nonExistIns);
            $this->monitor_logger->warning("The instance [$idStr] did not produce an redis data in ten minutes!");
        }

        if(count($lostCountIns) > 0)
        {
            $idStr = implode(',', $lostCountIns);
            $this->monitor_logger->warning("instance [$idStr] redis killsec info seems losted!");
        }
    }

    public function monitorKillsecServiceLogSimple()
    {
        $this->load->library('Soma_Logger', array(
            'options' => array(
                'prefix'       => 'soma_',
            ),
            'logDirectory' => APPPATH . 'logs' . DIRECTORY_SEPARATOR . 'soma' . DIRECTORY_SEPARATOR . 'killsec_monitor',
        ), 'monitor_logger');

        $this->monitor_logger->info('check killsec sevice start!');

        $this->monitor_logger->warning('killsec redis server disconnect!');
        $this->monitor_logger->warning('cron lock redis server disconnect!');
        $this->monitor_logger->warning('killsec initial cronjob is locked!');
        $this->monitor_logger->warning('killsec order cronjob is locked!');
        $this->monitor_logger->warning("The activity [1,2,3] did not produce an instance in ten minutes!");
        $this->monitor_logger->warning("Activity [1,2,3] instance is repeated!");
        $this->monitor_logger->warning("The instance [1,2,3] did not produce an redis data in ten minutes!");
        $this->monitor_logger->warning("instance [1,2,3] redis killsec info seems losted!");

        $this->monitor_logger->info('check killsec sevice end!');
    }

}