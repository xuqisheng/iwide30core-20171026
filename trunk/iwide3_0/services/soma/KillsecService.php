<?php

namespace App\services\soma;

use App\libraries\Support\Log;
use App\models\soma\Activity_killsec;
use App\models\soma\Activity_killsec_instance;
use App\models\soma\Activity_killsec_user;
use App\services\BaseService;
use App\services\Result;
use Redis;
use RuntimeException;

/**
 * Class KillsecService
 * @package App\services\soma
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class KillsecService extends BaseService
{
    /**
     * @var
     */
    private static $redis;

    /**
     *
     * @return Redis
     * @author renshuai  <renshuai@jperation.cn>
     *
     */
    public function getRedis()
    {
        if (empty(self::$redis)) {

            $this->getCI()->config->load('redis', true, true);
            $redis_config = $this->getCI()->config->item('redis');
            $redis = new Redis();
            if ( ! $redis->connect($redis_config['host'], $redis_config['port'], $redis_config['timeout'])) {
                throw new RuntimeException('redis connect fail');
            }

            self::$redis = $redis;
        }
        return self::$redis;
    }

    /**
     * 获取服务实例方法
     * @return KillsecService
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }

    /**
     * 不执行旧秒杀逻辑的公众号
     *
     * @return     array  公众号数组
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function skipOldKillsecCronTaskInterId()
    {
        return [];
    }

    /**
     * @param $interID
     * @param $instanceID
     * @param $openid
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getRedisKeys($interID, $instanceID, $openid = '')
    {
        return [
            'key' => "soma_killsec2_$instanceID", //放秒杀活动的库存
            'robKey' => "soma_killsec2_{$instanceID}_{$interID}_{$openid}", //用户抢到了资格的标记
            'hkey' => "soma_killsec2_hset_$instanceID", //秒杀活动的hash key
            'userKey' => "{$interID}_{$openid}",
            'userKeyBlackList' => "{$interID}_{$openid}_black_list",
        ];
    }

    /**
     * @param $actId
     * @return Result
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function disable($actId)
    {
        $result = new Result();

        $model = new Activity_killsec();
        $act = $model->getById($actId);
        if (empty($act)) {
            $result->setMessage('不存在');
            return $result;
        }

        if($act['status'] == Activity_killsec::STATUS_FALSE ) {
            $result->setMessage('已失效');
            return $result;
        }

        $db = $this->getCI()->soma_db_conn;
        $db->trans_begin();

        $model->disableStatus($act['act_id']);
        $instanceModel = new Activity_killsec_instance();

        $instance = $instanceModel->getUsingRow($act['act_id'], $act['killsec_time'], $act['end_time']);
        if (!empty($instance)) {
            //todo
        }

        if ($db->trans_complete())
        {

            $db->trans_commit();
        }

        $db->trans_rollback();

        $result->setStatus(Result::STATUS_OK);
        return $result;
    }

    /**
     * 秒杀支付完成
     * @param $orderID
     * @param $openid
     * @param $rowQty
     * @return Result
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function payed($orderID, $openid, $rowQty)
    {
        $result = new Result();

        $redis = $this->getRedis();
        if (empty($redis)) {
            $result->setData([
                'status' => \Soma_base::STATUS_FALSE
            ]);
            $result->setMessage('系统错误');
            return $result;
        }

        $killUserModel = new Activity_killsec_user();
        $userRows = $killUserModel->get(
            [
                'order_id',
                'openid'
            ],
            [
                $orderID,
                $openid
            ]
        );
        if ($userRows && isset($userRows[0])) {
            $userID = $userRows[0]['user_id'];
            $instanceID = $userRows[0]['instance_id'];
            $interID = $userRows[0]['inter_id'];

            $instanceModel = new Activity_killsec_instance();

            $updateResult = $killUserModel->update($userID, [
                'status' => Activity_killsec_user::USER_STATUS_PAYMENT,
                'pay_time'=> date('Y-m-d H:i:s')
            ]);
            $decreaseResult = $instanceModel->decrease($instanceID, 'killsec_count', $rowQty);

            /**
             * @var $key
             * @var $robKey
             */
            $keys = $this->getRedisKeys($interID, $instanceID, $openid);
            extract($keys);

            if ($updateResult && $decreaseResult) {
                $delReidsResult = $redis->del($robKey);
                $result->setStatus(Result::STATUS_OK);
                $result->setData([
                    $decreaseResult, $updateResult
                ]);
                if ($delReidsResult != 1) {
                    Log::error('killsec payed del redis key failed', [
                        'robKey' => $robKey,
                        'delReidsResult' => $delReidsResult,
                    ]);
                }
            } else {

                Log::error('killsec payed sql update failed', [
                    'decreaseResult' => $decreaseResult,
                    'updateResult' => $updateResult,
                    'orderID' => $orderID,
                    'robKey' => $robKey,
                ]);
            }

        }

        return $result;
    }

    /**
     * 活动秒杀库存数据
     * @param $actID
     * @return Result
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getStock($actID)
    {
        $result = new Result();

        $redis = $this->getRedis();
        if (empty($redis)) {
            $result->setData([
                'status' => \Soma_base::STATUS_FALSE
            ]);
            $result->setMessage('系统错误');
            return $result;
        }

        $killsecModel = new Activity_killsec();
        $killsec = $killsecModel->getById($actID);
        if (empty($killsec)) {
            $result->setMessage('系统错误2');

            $result->setData([
                'status' => \Soma_base::STATUS_FALSE
            ]);
            return $result;
        }

        $currentTime = time();
        if (strtotime($killsec['killsec_time'])  > $currentTime ) {
            $data = array(
                'status' => \Soma_base::STATUS_TRUE,
                'total' => $killsec['killsec_count'],
                'stock' => $killsec['killsec_count'],
                'percent' => 100
            );
        } elseif (strtotime($killsec['killsec_time'])  < $currentTime && $currentTime < strtotime($killsec['end_time'])) {

            $instanceModel = new Activity_killsec_instance();
            $instance = $instanceModel->getUsingRow($killsec['act_id'], $killsec['killsec_time'], $killsec['end_time']);

            if (empty($instance)) {
                $result->setMessage('系统错误3');
                $result->setData([
                    'status' => \Soma_base::STATUS_FALSE
                ]);
                return $result;
            }

            /**
             * @var $key
             * @var $robKey
             */
            $keys = $this->getRedisKeys($killsec['inter_id'], $instance['instance_id']);
            extract($keys);

            $data['status'] = \Soma_base::STATUS_TRUE;
            $data['total'] = $killsec['killsec_count'];
            $data['stock'] = $redis->get($key);

            if ($data['stock'] < 1){
                $data['percent'] = 0;
            } else {
                $data['percent'] = round($data['stock'] / $data['total'], 2) * 100;
            }
        } else {
            $data = array(
                'status' => \Soma_base::STATUS_FALSE,
            );
        }

        $result->setStatus(Result::STATUS_OK);
        $result->setData($data);

        return $result;
    }

    /**
     * 活动秒杀库存增加
     * @param $actID
     * @return Result
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function addRedisStock($actID,$num)
    {
        $result = new Result();

        $redis = $this->getRedis();
        if (empty($redis)) {
            $result->setData([
                'status' => \Soma_base::STATUS_FALSE
            ]);
            $result->setMessage('系统错误');
            return $result;
        }

        $killsecModel = new Activity_killsec();
        $killsec = $killsecModel->getById($actID);
        if (empty($killsec)) {
            $result->setMessage('系统错误2');

            $result->setData([
                'status' => \Soma_base::STATUS_FALSE
            ]);
            return $result;
        }

        $currentTime = time();
        if (strtotime($killsec['killsec_time'])  > $currentTime ) {
            $data = array(
                'status' => \Soma_base::STATUS_TRUE,
                'total' => $killsec['killsec_count'],
                'stock' => $killsec['killsec_count'],
                'percent' => 100
            );
        } elseif (strtotime($killsec['killsec_time'])  < $currentTime && $currentTime < strtotime($killsec['end_time'])) {

            $instanceModel = new Activity_killsec_instance();
            $instance = $instanceModel->getUsingRow($killsec['act_id'], $killsec['killsec_time'], $killsec['end_time']);

            if (empty($instance)) {
                $result->setMessage('系统错误3');
                $result->setData([
                    'status' => \Soma_base::STATUS_FALSE
                ]);
                return $result;
            }

            /**
             * @var $key
             * @var $robKey
             */
            $keys = $this->getRedisKeys($killsec['inter_id'], $instance['instance_id']);
            extract($keys);

            $data['status'] = \Soma_base::STATUS_TRUE;
            $data['total'] = $killsec['killsec_count'];
            $data['stock'] = $redis->get($key);

            $newStock = $data['stock'] + $num;

            $data['status'] =   $redis->set($key,$newStock);
        } else {
            $data = array(
                'status' => \Soma_base::STATUS_FALSE,
            );
        }

        $result->setStatus(Result::STATUS_OK);
        $result->setData($data);

        return $result;
    }

    /**
     * 回滚库存数
     * 回滚秒杀活动的参加人数
     *
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function killsecRollback()
    {
        $killsecUserModel = new Activity_killsec_user();
        $rows = $killsecUserModel->getRollbackList('*');
        if(empty($rows)){
            return true;
        }

        $result = false;
        $redis = $this->getRedis();
        if (empty($redis)) {
            return $result;
        }

        $instanceUpdateArr = [];
        foreach($rows as $val) {
            //cron controller有加载这个model
            $this->getCI()->db_shard_config = $this->getCI()->model_shard_config->build_shard_config($val['inter_id']);

            /**
             * @var $key
             * @var $robKey
             * @var $hkey
             * @var $userKey
             */
            $keys = $this->getRedisKeys($val['inter_id'], $val['instance_id'], $val['openid']);
            extract($keys);

            $killsecCount = $redis->get($key);

            if ($killsecCount === false) {
                continue;
            }

            if (!isset($instanceUpdateArr[$val['instance_id']])) {
                $instanceUpdateArr[$val['instance_id']] = 0;
            }

            $result = $killsecUserModel->updateAffectRows(
                [
                    'user_id' => $val['user_id'],
                    'status !=' => $killsecUserModel::USER_STATUS_ARCHIVE
                ],
                [
                    'status' => Activity_killsec_user::USER_STATUS_ARCHIVE
                ]
            );

            if($result){
                if ($val['status'] == Activity_killsec_user::USER_STATUS_JOIN) {
                    $instanceUpdateArr[$val['instance_id']] += 1;
                    $rollbackCount = $val['max_stock'];

                    $redis->incrBy($key, $rollbackCount);
                } elseif ($val['status'] == Activity_killsec_user::USER_STATUS_ORDER) {
                    $instanceUpdateArr[$val['instance_id']] += 1;
                    $rollbackCount = $val['max_stock'];

                    $redis->incrBy($key, $rollbackCount);
                } elseif ($val['status'] == Activity_killsec_user::USER_STATUS_PAYMENT) {
                    $this->getCI()->load->model('soma/Sales_order_model','salesOrderModel');
                    $row = $this->getCI()->salesOrderModel->getByID($val['order_id']);
                    if (!empty($row)) {

                        $rollbackCount = $val['max_stock'] - $row['row_qty'];
                        $incrResult = -1;
                        if ($rollbackCount > 0 ) {
                            $incrResult = $redis->incrBy($key, $rollbackCount);
                        }

                        Log::error('killsec rollback debug', [
                            'user_id' => $val['user_id'],
                            'val_order_id' => $val['order_id'],
                            'max_stock' => $val['max_stock'],
                            'row_order_id' => $row['order_id'],
                            'row_qty' => $row['row_qty'],
                            'result' => $incrResult
                        ]);
                    }
                }

            } else {
                Log::error("killsec service rollback error, user id is {$val['user_id']}");
            }
        }

        $instanceModel = new Activity_killsec_instance();
        foreach($instanceUpdateArr as $instanceID => $count) {
            if ($count > 0) {
                $instanceModel->decrease($instanceID, 'join_count', $count);
            }
        }

    }




    /**
     * 更新周期性秒杀的时间
     *
     * 计划任务一分钟跑一次
     *
     * start_time, killsec_time, end_time
     *
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function updateScheduleCycleTime()
    {
        $killsecModel = new Activity_killsec();

        $rows = $killsecModel->getScheduleCycleRows();

        $generator = $killsecModel->calculateTimes($rows);

        while( $generator->valid() ) {
            /**
             * @var $act_id
             * @var $killsec_time
             * @var $end_time
             * @var $inter_id
             */
            $row = $generator->current();
            extract($row);
            $killsecModel->updateTimes($act_id, $killsec_time, $end_time);

            $generator->next();
        }

    }

    /**
     * 获得商品秒杀的信息
     *
     * 如果初始化失败，帮助完成初始化
     *
     * @param $productID
     *
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getInfo($productID)
    {
        $killsecModel = new Activity_killsec();
        $killsec = $killsecModel->getProductKillsec($productID);

        if (empty($killsec)) {
            return [];
        }

        $instanceModel = new Activity_killsec_instance();
        $instance = $instanceModel->getUsingRow($killsec['act_id'], $killsec['killsec_time'], $killsec['end_time']);

        $killsec['finish'] = false; //秒杀是否售罄标示
        $currentTime = time();
        if (strtotime($killsec['killsec_time']) < $currentTime && $currentTime < strtotime($killsec['end_time']) ) {

            //秒杀未初始化
            if (empty($instance)) {
                if ($this->initData($killsec['act_id'])) {
                    $instance = $instanceModel->getUsingRow($killsec['act_id'], $killsec['killsec_time'], $killsec['end_time']);
                }
            } else {
                $killsec['finish'] = $instance['killsec_count'] > 0 ? false : true;
            }
        }

        $killsec['instance'] = $instance;

        return $killsec;
    }

    /**
     *
     * @param $actID
     * @return bool
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function initData($actID)
    {
        $result = false;

        $killsecModel = new Activity_killsec();
        $activitys = $killsecModel->get('act_id', $actID);

        if (empty($activitys)) {
            return $result;
        }

        $activity = $activitys[0];

        $instanceModel = new Activity_killsec_instance();
        $instanceID = $instanceModel->checkAndSave($activity);

        return $this->initRedisData($instanceID);
    }

    /**
     * @param $interID
     * @param $instanceID
     * @param $openid
     * @return Result
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getOpporunity($interID, $instanceID, $openid)
    {
        $result = new Result();
        $redis = $this->getRedis();
        if (empty($redis)) {
            return $result;
        }

        /**
         * @var $key
         * @var $robKey
         * @var $hkey
         * @var $userKey
         */
        $keys = $this->getRedisKeys($interID, $instanceID, $openid);
        extract($keys);

        $instanceModel = new Activity_killsec_instance();
        $instance = $instanceModel->getById($instanceID);
        if (empty($instance)) {
            $result->setMessage('参数错误');
            return $result;
        }

        $currentTime = time();
        //时间判断
        if ($currentTime < strtotime($instance['start_time'])) {
            $result->setMessage($this->getCI()->lang->line('activity_not_begin_tip'));
            return $result;
        }
        if ($currentTime > strtotime($instance['close_time'])) {
            $result->setMessage($this->getCI()->lang->line('activity_not_begin_tip'));
            return $result;
        }


        if ($redis->get($robKey)) {
            $result->setStatus(Result::STATUS_OK);
            $result->setData([
                'status' => Result::STATUS_OK,
                'instance_id' => $instanceID,
                'token' => rand(0, 1000)
            ]);
            return $result;
        }

        //剩余库存判断
        $killsecCount = $redis->get($key);
        if ($killsecCount < 1) {
            $result->setMessage($this->getCI()->lang->line('paying_and_release_tip'));
            return $result;
        }

        $userModel = new Activity_killsec_user();
        $usedCount = $userModel->getUsedCount($interID, $openid, $instanceID);
        $availableCount = $instance['killsec_permax'] - $usedCount;

        if ($availableCount < 1) {
            $result->setMessage($this->getCI()->lang->line('flash_sale_is_limit_tip'));
            return $result;
        }

        if($killsecCount < $availableCount){
            $availableCount =  $killsecCount;
//            $result->setMessage($this->getCI()->lang->line('paying_and_release_tip'));
//            return $result;
        }

        if( $redis->decrBy($key, $availableCount) < 0) {
            $redis->incrBy($key, $availableCount);
            $result->setMessage($this->getCI()->lang->line('paying_and_release_tip'));
            return $result;
        }

//        $redis->watch($key);
//        $redis->multi();
//        $redis->set($key, $killsecCount - $availableCount);
//        $redis->hIncrBy($hkey, $userKey, 1);
//        $rob_result = $redis->exec();
//        if(empty($rob_result)){
//            $result->setMessage($this->getCI()->lang->line('paying_and_release_tip'));
//            return $result;
//        }


        $db = $this->getCI()->soma_db_conn;
        $db->trans_begin();

        $instanceModel->increase($instanceID, 'join_count', 1);
        $killsecUserID = $userModel->save($instanceID, $instance['act_id'], $interID, $openid, $availableCount);

        if (!$db->trans_complete()) {
            $db->trans_rollback();
            $result->setMessage($this->getCI()->lang->line('paying_and_release_tip'));
            return $result;
        }

        $db->trans_commit();
        $redis->set($robKey, $killsecUserID);
        $redis->expire($robKey, 300);

        $result->setStatus(Result::STATUS_OK);
        $result->setData([
            'status' => Result::STATUS_OK,
            'instance_id' => $instanceID,
            'token' => rand(0, 1000)
        ]);
        return $result;
    }

    /**
     * @param $interID
     * @param $instanceID
     * @param $openid
     * @return Result
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function vaild($interID, $instanceID, $openid)
    {
        $result = new Result();
        $redis = $this->getRedis();
        if (empty($redis)) {
            return $result;
        }

        /**
         * @var $key
         * @var $robKey
         *
         */
        $keys = $this->getRedisKeys($interID, $instanceID, $openid);
        extract($keys);

        $userID = $redis->get($robKey);

        $userModel = new Activity_killsec_user();
        $row = $userModel->getById($userID);
        if (empty($row) || !isset($row['openid']) || $row['openid'] !== $openid ) {
            $result->setMessage('错误');
            return $result;
        }

        $result->setStatus(Result::STATUS_OK);
        $result->setData($row);
        return $result;
    }


    private function initRedisData($instanceID)
    {
        $result = false;
        $redis = $this->getRedis();
        if (empty($redis)) {
            return $result;
        }

        $instanceModel = new Activity_killsec_instance();
        $instance = $instanceModel->getById($instanceID);

        if (empty($instance)) {
            return $result;
        }

        /**
         * @var $key
         * @var $hkey
         */
        $keys = $this->getRedisKeys($instance['inter_id'], $instanceID);
        extract($keys);

        //有缓存，初始化就算成功了
        if ($redis->get($key)) {
            $result = true;
            return $result;
        }

        $expireTime = strtotime($instance['close_time']);

        if (!$redis->setnx($key, $instance['killsec_count'])) {
            return $result;
        }

        if (!$redis->expireAt($key, $expireTime)) {
            return $result;
        }

        if (!$redis->hSetNx($hkey, '1', '1')) {
            return $result;
        }

        if (!$redis->expireAt($hkey, $expireTime)) {
            return $result;
        }

        $result = true;
        return $result;
    }


    public function checkIntersect($product_id,$schedule_type,$killsec_time,$end_time,$act_id='',$cycle_stime='',$cycle_etime='',$schedule=''){
        $return = array(
            'status' => false,
            'killsec'   => array()
        );


        if($schedule_type== 2 && (empty($cycle_stime) || empty($cycle_etime) || empty($schedule) )){
            return  array(
                'status' => true,
                'killsec'   => array()
            );;
        }

        $killsecModel = new Activity_killsec();
        if(isset($schedule_type) && $schedule_type == 2){        //添加的属于循环
            $startTimestamp = strtotime($killsec_time);
            $endTimestamp =    strtotime($end_time);
            if(date("Y-m-d",$endTimestamp) > date("Y-m-d",$startTimestamp)){
                $crossDateFlag = true; //跨日
            }else{
                $crossDateFlag = false; //没有跨日
            }

            $scheduleScope = $killsecModel->getScheduleScope($cycle_stime,$cycle_etime,$schedule);

            $clockStartTime =  substr($killsec_time, 11, 8);
            $clockEndTime =  substr($end_time, 11, 8);

            //循环-固定配对
            $stationaryRows =  $killsecModel->getStationaryKillsecByScope($product_id,$cycle_stime,$cycle_etime);
            if(!empty($stationaryRows)){
                foreach($stationaryRows as $singleStationary){   //循环配对
                    if(!empty($act_id) && $singleStationary['act_id'] == $act_id)
                        continue;
                    $stationaryStartDate = substr($singleStationary['killsec_time'], 0, 10)  ;
                    $stationaryEndDate = substr($singleStationary['end_time'], 0, 10)  ;
                    if(in_array($stationaryStartDate,$scheduleScope) || in_array($stationaryEndDate,$scheduleScope)){
                        $scheduleStartDate = $stationaryStartDate." ".$clockStartTime;
                        if($crossDateFlag){
                            $scheduleEndDate = date("Y-m-d H:i:s", strtotime($stationaryStartDate." ".$clockEndTime) + 86400);
                        }else{
                            $scheduleEndDate = $stationaryStartDate." ".$clockEndTime;
                        }
                        $compareResult = $killsecModel->settledTimeCompare($scheduleStartDate,$scheduleEndDate,$singleStationary['killsec_time'],$singleStationary['end_time'] );
                        if($compareResult){
                            //有重复
                            //交集数据 $singleStationary
                            $return = array(
                                'status' => true,
                                'killsec'   => $singleStationary
                            );
                            return $return;
                            break;
                        }
                    }

                }

            }
            //循环-循环配对
            $loopScheduleRows =  $killsecModel->getScheduleKillsecByScope($product_id,$cycle_stime,$cycle_etime);
            if(!empty($loopScheduleRows)){
                foreach($loopScheduleRows as $singleSchedule){   //循环配对
                    if(!empty($act_id) && $singleSchedule['act_id'] == $act_id)
                        continue;
                    $existScheduleScope = $killsecModel->getScheduleScope($singleSchedule['cycle_stime'],$singleSchedule['cycle_etime'],$singleSchedule['schedule']); //有效的日期时间
                    $existStartTime =  substr($singleSchedule['killsec_time'], 11, 8);
                    $existEndTime =  substr($singleSchedule['end_time'], 11, 8);
                    $compareResult = $killsecModel->scheduleScopeCompare($scheduleScope,$existScheduleScope,$clockStartTime,$clockEndTime,$existStartTime,$existEndTime);
                    if($compareResult){
                        //有重复
                        //交集数据 $singleStationary
                        $return = array(
                            'status' => true,
                            'killsec'   => $singleSchedule
                        );
                        return $return;
                        break;
                    }

                }
            }
        }else{  //固定日期的秒杀
            //固定-固定配对
            $killsecStartDate = substr($killsec_time, 0, 10);
            $killsecEndDate = substr($end_time, 0, 10);
            $stationaryRows =  $killsecModel->getStationaryKillsecByScope($product_id,$killsec_time,$end_time);
            if(!empty($stationaryRows)){
                /*不用配对了，有结果就应该是有冲突  */
                foreach($stationaryRows as $singleStationary){   //循环配对
                    if(!empty($act_id) && $singleStationary['act_id'] == $act_id)
                        continue;
                    else{ //有重复的
                        $return = array(
                            'status' => true,
                            'killsec'   => $singleStationary
                        );
                        return $return;
                        break;
                    }


                }
//
//                echo 'repeat !';
//
            }
            //固定-循环配对
            $loopScheduleRows =  $killsecModel->getScheduleKillsecByScope($product_id,$killsec_time,$end_time);
            if(!empty($loopScheduleRows)){
                foreach($loopScheduleRows as $singleSchedule){   //循环配对
                    if(!empty($act_id) && $singleSchedule['act_id'] == $act_id)
                        continue;

                    $existScheduleScope = $killsecModel->getScheduleScope($singleSchedule['cycle_stime'],$singleSchedule['cycle_etime'],$singleSchedule['schedule']); //有效的日期时间
                    if(in_array($killsecStartDate,$existScheduleScope) || in_array($killsecEndDate,$existScheduleScope)) { //在循环列表里面
                        $startTimestamp = strtotime($singleSchedule['killsec_time']);
                        $endTimestamp =    strtotime($singleSchedule['end_time']);
                        $clockStartTime =  substr($singleSchedule['killsec_time'], 11, 8);
                        $clockEndTime =  substr($singleSchedule['end_time'], 11, 8);

                        $compareStartDate  = $killsecStartDate." ".$clockStartTime;
                        if(date("Y-m-d",$endTimestamp) > date("Y-m-d",$startTimestamp)){
                            $compareEndDate = date("Y-m-d H:i:s", strtotime($killsecStartDate." ".$clockEndTime) + 86400);
                        }else{
                            $compareEndDate = $killsecStartDate." ".$clockEndTime;
                        }

                        $compareResult = $killsecModel->settledTimeCompare($compareStartDate,$compareEndDate,$killsec_time,$end_time );
                        if($compareResult){
                            $return = array(
                                'status' => true,
                                'killsec'   => $singleSchedule
                            );
                            return $return;
                            break;
                        }
                    }


                }
            }

        }

        return $return;

    }

    /**
     * @param $instanceID
     * @param $interID
     * @param $openid
     * @return Result
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function orderValid($instanceID, $interID, $openid)
    {
        $result = new Result();
        $redis = $this->getRedis();
        if (empty($redis)) {
            return $result;
        }

        /**
         * @var $key
         * @var $robKey
         * @var $hkey
         * @var $userKey
         */
        $keys = $this->getRedisKeys($interID, $instanceID, $openid);
        extract($keys);

        $userID = $redis->get($robKey);
        if (empty($userID)) {
            $result->setMessage('提交订单已超时，订单已被释放');
            return $result;
        }

        $userModel = new Activity_killsec_user();
        $row = $userModel->getById($userID);
        if (empty($row)) {
            $result->setMessage('提交订单已超时，订单已被释放');
            return $result;
        }

        if ($row['status'] == Activity_killsec_user::USER_STATUS_PAYMENT) {
            $result->setMessage('秒杀活动数量有限，请勿重复参加。');
            return $result;
        }

        if ($row['status'] == Activity_killsec_user::USER_STATUS_ARCHIVE) {
            $result->setMessage('提交订单已超时，订单已被释放');
            return $result;
        }

        if ($row['status'] == Activity_killsec_user::USER_STATUS_ORDER) {
            $result->setMessage('订单继续支付');
            $result->setData(
                [
                    'orderId' => $row['order_id']
                ]
            );
        }

        $result->setStatus(Result::STATUS_OK);

        return $result;
    }

    /**
     * 获取用户订阅的秒杀
     * @param array $actids
     * @param $inter_id
     * @param $openid
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function getOpenidSubscribActid(Array $actids, $inter_id, $openid)
    {
        $this->getCI()->load->model('soma/Activity_killsec_notice_model');
        $killSecModel = $this->getCI()->Activity_killsec_notice_model;

        $field = array('inter_id', 'openid', 'act_id');
        $field_value = array($inter_id, $openid, $actids);
        $select = 'act_id';
        $option = array(
            'limit' => count($actids),
        );
        $result = $killSecModel->get($field, $field_value, $select, $option);

        return $result;

    }

    /**
     * 获取用户订阅的秒杀
     * @param array $actids
     * @param $inter_id
     * @param $openid
     * @param $killsec
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function getOpenidSubscribKilltime(Array $actids, $inter_id, $openid, $killsec)
    {
        $this->getCI()->load->model('soma/Activity_killsec_notice_model');
        $killSecModel = $this->getCI()->Activity_killsec_notice_model;

        $field = array('inter_id', 'openid', 'killsec_time');
        $field_value = array($inter_id, $openid, $killsec);
        $select = 'act_id';
        $option = array(
            'limit' => count($actids),
        );
        $result = $killSecModel->get($field, $field_value, $select, $option);

        return $result;

    }


}