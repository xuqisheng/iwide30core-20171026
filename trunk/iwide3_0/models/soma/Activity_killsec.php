<?php

namespace App\models\soma;
use App\models\soma\Activity_killsec_instance as ActivityKillsecInstanceModel;
/**
 * Class Activity_killsec
 * @package App\models\soma
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class Activity_killsec extends \MY_Model_Soma
{

    const PREVIEW_TIME= 1800;  //活动提前多久生成实例，同时作为活动手动结束的时间限制
    const END_PREVIEW_TIME = 900;  //活动结束后多久才能重新编辑
    const PRESTART_TIME= 60;
    const WARNING_TIME = 600;

    /**
     * 固定日期
     */
    const SCHEDULE_TYPE_FIX = 1;

    /**
     * 周期循环
     */
    const SCHEDULE_TYPE_CYC = 2;

    const SYNC_STATUS_TRUE = 1;
    const SYNC_STATUS_FALSE = 2;

    const STATUS_TRUE  = 1;
    const STATUS_FALSE = 2;

    /**
     * 秒杀方式 按照名额
     */
    const TYPE_PLACES = 1;
    /**
     * 秒杀方式 按照库存
     */
    const TYPE_STOCK = 2;

    /**
     * @return string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function table_primary_key()
    {
        return 'act_id';
    }

    /**
     * @return string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function table_name()
    {
        return 'soma_activity_killsec';
    }


    /**
     * @param $id
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getById($id)
    {
        $row = $this->get($this->table_primary_key(), $id);
        if (empty($row) || empty($row[0])) {
            return [];
        }

        return $row[0];
    }

    /**
     *
     * 如果当前周期秒杀活动已过时
     * 计算出下一个最新的秒杀的开始和结束时间
     *
     * @param array $rows
     * @return \Generator
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function calculateTimes(Array $rows)
    {
        $this->load->helper('soma/time_calculate');

        foreach($rows as $key => $val) {
            $currentTime = time();
            if ($currentTime > strtotime($val['end_time'])) {
                $scheduleArr = explode(',', $val['schedule']);

                $killsec_time_times = substr($val['killsec_time'], -8);

                $tmp = [];
                foreach ($scheduleArr as $v){
                    $tmp[]= last_week_date($v). ' '. $killsec_time_times;
                    $tmp[]= last_week_date($v, '+1'). ' '. $killsec_time_times;
                }

                sort($tmp);

                $date = null;
                foreach ($tmp as $v){
                    if( date('Y-m-d H:i:s', time()-60) < $v ){
                        $date = $v;
                        break;
                    }
                }

                if (!empty($date)) {
                    $c_hours = round( ( strtotime($val['end_time']) - strtotime($val['killsec_time']) )/3600, 0 );
                    $val['killsec_time'] = $date;
                    $val['end_time'] = date('Y-m-d H:i:s', strtotime($val['killsec_time']) + $c_hours* 3600 );

                    yield $val;
                }

            }
        }

    }

    /**
     * 拉取符合条件的周期性秒杀
     *
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getScheduleCycleRows()
    {
        $currentDate = date('Y-m-d H:i:s');

        $rows = $this->get(
            [
                'schedule_type',
                'status',
                'cycle_stime<',
                'cycle_etime>'
            ],
            [
                self::SCHEDULE_TYPE_CYC,
                self::STATUS_TRUE,
                $currentDate,
                $currentDate
            ],
            '*',
            [
                'limit' => 500,
//                'debug' => true
            ]
        );

        return $rows;
    }

    /**
     * @param $actID
     * @param $killsec_time
     * @param $end_time
     * @return object
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function updateTimes($actID, $killsec_time, $end_time)
    {
        $data = [
            'killsec_time' => $killsec_time,
            'end_time' => $end_time
        ];
        return $this->soma_db_conn->set($data)->where($this->table_primary_key(), $actID)->update($this->table_name());
    }


    /**
     *
     * 获取正在秒杀的或者即将开始的秒杀记录
     * 
     * @param $productID
     * @return array
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function getProductKillsec($productID)
    {
        $currentDate = date('Y-m-d H:i:s');

        //先拉取正在秒杀的记录
        $rows = $this->get(
            [
                'status',
                'product_id',
                'killsec_time<',
                'end_time>='
            ],
            [
                self::STATUS_TRUE,
                $productID,
                $currentDate,
                $currentDate
            ],
            '*',
            [
                'orderBy' => 'killsec_time asc',
            ]
        );

        if (empty($rows) || empty($rows[0])) {
            //没有正在秒杀的记录，就拉快开始的
            $rows = $this->get(
                [
                    'status',
                    'product_id',
                    'start_time<',
                    'killsec_time>='
                ],
                [
                    self::STATUS_TRUE,
                    $productID,
                    $currentDate,
                    $currentDate
                ],
                '*',
                [
                    'orderBy' => 'killsec_time asc',
                ]
            );

            if (empty($rows) || empty($rows[0])) {
                return [];
            }
        }
        return $rows[0];
    }


    /**
     * 拉取到了快要开始秒杀的活动
     * @param $select
     * @param $limit
     * @return mixed
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getPrepareList($select, $limit)
    {
        $table_name = $this->soma_db_conn_read->dbprefix($this->table_name());
        $current_time = date('Y-m-d H:i:s');
        $sql = "SELECT $select FROM $table_name WHERE status = ? AND  date_sub(killsec_time, interval 55 minute) < ?  AND ? < date_sub(killsec_time, interval 1 minute) limit $limit";
        $query = $this->soma_db_conn->query($sql, [
            self::STATUS_TRUE,
            $current_time,
            $current_time
        ]);
        return $query->result_array();
    }


    /**
     * 后台显示秒杀列表
     * @param $interID
     * @param $status
     * @param int $page
     * @param int $limit
     * @param string $orderBy
     * @return array|string
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getKillsecList($interID, $status='', $page = 1, $limit = 10, $orderBy = 'act_id desc')
    {

        $fieldArr = array(
            'inter_id',
        );
        $valueArr = array(
            $interID,
        );

        $nowTime = date('Y-m-d H:i:s');
        if( $status == ActivityKillsecInstanceModel::STATUS_PREVIEW )
        {
            $fieldArr[] = 'start_time < ';
            $valueArr[] = $nowTime;
            $fieldArr[] = 'killsec_time > ';
            $valueArr[] = $nowTime;
        } elseif( $status == ActivityKillsecInstanceModel::STATUS_GOING ) {
            $fieldArr[] = 'killsec_time < ';
            $valueArr[] = $nowTime;
            $fieldArr[] = 'end_time > ';
            $valueArr[] = $nowTime;
        } elseif( $status == ActivityKillsecInstanceModel::STATUS_FINISH ) {
            $fieldArr[] = 'end_time < ';
            $valueArr[] = $nowTime;
        }

        return $this->get(
            $fieldArr,
            $valueArr,
            '*',
            array(
                'limit' => $limit,
                'offset' => ($page - 1) * $limit,
                'orderBy' => $orderBy,
                'debug' => false
            )
        );
    }


    /**
     * 后台显示秒杀列表
     * @param $interID
     * @param $status
     * @param int $page
     * @param int $limit
     * @param string $orderBy
     * @return array|string
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getKillsecListByPids($interID, $pids = [])
    {

        $fieldArr = array(
            'inter_id',
        );
        $valueArr = array(
            $interID,
        );

        $nowTime = date('Y-m-d H:i:s');
        $fieldArr[] = 'start_time < ';
        $valueArr[] = $nowTime;
        $fieldArr[] = 'end_time > ';
        $valueArr[] = $nowTime;
        $fieldArr[] = 'product_id';
        $valueArr[] = $pids;

        return $this->get(
            $fieldArr,
            $valueArr,
            '*',
            array(
                'limit' => null,
                'debug' => false
            )
        );
    }


    /**
     * 搜索秒杀列表
     * @param $interID
     * @param $search
     * @return mixed
     * @author luguihong  <luguihong@jperation.com>
     */
    public function searchKillsecList($interID, $search)
    {
        $table = $this->table_name();
        $result = $this->soma_db_conn_read
            ->where('inter_id',$interID)
            ->where("(
                                `act_id` LIKE '%{$search}%' ESCAPE '!' 
                                OR `act_name` LIKE '%{$search}%' ESCAPE '!'
                                OR `product_name` LIKE '%{$search}%' ESCAPE '!'
                            )")
            ->get($table)
            ->result_array();
        //echo $this->soma_db_conn_read->last_query();die;
        return $result;
    }

    /**
     * 获取公众号下面有多少条记录
     * @param $interID
     * @param $status
     * @return mixed
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getKillsecTotal( $interID, $status='' )
    {
        $nowTime = date('Y-m-d H:i:s');
        if( $status == ActivityKillsecInstanceModel::STATUS_PREVIEW )
        {
            $this->soma_db_conn_read->where( 'start_time < ', $nowTime );
            $this->soma_db_conn_read->where( 'killsec_time > ', $nowTime );
        } elseif( $status == ActivityKillsecInstanceModel::STATUS_GOING ) {
            $this->soma_db_conn_read->where( 'killsec_time < ', $nowTime );
            $this->soma_db_conn_read->where( 'end_time > ', $nowTime );
        } elseif( $status == ActivityKillsecInstanceModel::STATUS_FINISH ) {
            $this->soma_db_conn_read->where( 'end_time < ', $nowTime );
        }

        $table = $this->table_name();
        return $this->soma_db_conn_read
            ->where( 'inter_id', $interID )
            ->from( $table )
            ->count_all_results();
    }

    /**
     * 让秒杀活动失效
     * @param $actId
     * @return bool
     * @author luguihong  <luguihong@jperation.com>
     */
    public function disableStatus( $actId )
    {

        $data = array( 'status' => self::STATUS_FALSE );
        $table = $this->table_name();
        return $this->soma_db_conn
                ->where( 'act_id', $actId )
                ->where( 'status', self::STATUS_TRUE )
                ->limit(1)
                ->update( $table, $data );

    }


    /**
     * *useless, pending remove
     * @param $productId
     * @param $startTime
     * @param $endTime
     * @param int $schedule_type
     * @return mixed
     * @author zhangyi  <zhangyi@mofly.cn>
     */
    public function getStationaryKillsecCount($productId,$startTime,$endTime ,$schedule_type = 1){
        $count = 0;
        $table = $this->table_name();
        $count += $this->soma_db_conn_read
                    ->where( 'product_id' , $productId)
                    ->where( 'schedule_type' , $schedule_type)
                    ->where( 'killsec_time <=    ', $startTime )
                    ->where( 'end_time > ', $startTime )
                    ->from( $table )
                    ->count_all_results();
        ;
        $count += $this->soma_db_conn_read
            ->where( 'product_id' , $productId)
            ->where( 'schedule_type' , $schedule_type)
            ->where( 'killsec_time < ', $endTime )
            ->where( 'end_time >= ', $endTime )
            ->from( $table )
            ->count_all_results();
        ;
        $count += $this->soma_db_conn_read
            ->where( 'product_id' , $productId)
            ->where( 'schedule_type' , $schedule_type)
            ->where( 'killsec_time >= ', $startTime )
            ->where( 'end_time <= ', $endTime )
            ->from( $table )
            ->count_all_results();
        ;
        return $count;
    }


    /**
     * *获取固定类秒杀
     * @param $productId
     * @param $startTime   //秒杀开始时间
     * @param $endTime    //秒杀结束时间
     * @param int $schedule_type        //默认为1
     * @return mixed
     * @author zhangyi  <zhangyi@mofly.cn>
     */
    public function getStationaryKillsecByScope($productId,$startTime,$endTime,$schedule_type = 1 ,$status = 1){
        $table = $this->table_name();
        $rows = $this->soma_db_conn_read
            ->where( "

                 `schedule_type` = {$schedule_type}
                AND `product_id` = {$productId}
                AND (
                       (`killsec_time` <= '{$startTime}'    AND `end_time` >= '{$endTime}' )
                    OR (`killsec_time` <= '{$startTime}'    AND `end_time` <= '{$endTime}' AND `end_time` > '{$startTime}' )
                    OR (`killsec_time` <= '{$startTime}'    AND `end_time` >= '{$endTime}' AND `killsec_time` < '{$endTime}')
                    OR (`killsec_time` >= '{$startTime}'    AND `end_time` <= '{$endTime}' )

                )
                AND `status` = $status
              ")
            ->get($table)
            ->result_array();

        return $rows;


    }

    /**
     * @param $productId
     * @param $startTime //周期开始时间
     * @param $endTime  //周期结束时间
     * @param int $schedule_type    //默认2，周期循环类
     * @return mixed
     * @author zhangyi  <zhangyi@mofly.cn>
     */
    public function getScheduleKillsecByScope($productId,$startTime,$endTime,$schedule_type = 2,$status = 1){
//        echo "
//                `schedule_type` = {$schedule_type}
//                AND `product_id` = {$productId}
//                AND (
//                       (`cycle_stime` <= '{$startTime}'    AND `cycle_etime` <= '{$endTime}' )
//                    OR (`cycle_stime` >= '{$startTime}'    AND `cycle_etime` <= '{$endTime}' )
//                    OR (`cycle_stime` <= '{$startTime}'    AND `cycle_etime` >= '{$endTime}' )
//                    OR (`cycle_stime` >= '{$startTime}'    AND `cycle_etime` <= '{$endTime}' )
//
//                )";exit;
        $table = $this->table_name();
        $rows = $this->soma_db_conn_read
            ->where( "
                `schedule_type` = {$schedule_type}
                AND `product_id` = {$productId}
                AND (
                       (`cycle_stime` <= '{$startTime}'    AND `cycle_etime` >= '{$endTime}' )
                    OR (`cycle_stime` <= '{$startTime}'    AND `cycle_etime` <= '{$endTime}' AND `cycle_etime` >= '{$startTime}' )
                    OR (`cycle_stime` <= '{$startTime}'    AND `cycle_etime` >= '{$endTime}' AND `cycle_stime` <= '{$endTime}')
                    OR (`cycle_stime` >= '{$startTime}'    AND `cycle_etime` <= '{$endTime}' )
                    OR (  `cycle_stime` = '{$startTime}'   AND  `cycle_etime` = '{$endTime}' )

                )
                AND `status` = $status
              ")
            ->get($table)
            ->result_array();

        return $rows;
    }

    /**
     * @param $startTime  /秒杀开始时间
     * @param $endTime  /秒杀结束时间
     * @param $compareStart          /对比的开始时间
     * @param $compareEnd   /对比的结束时间
     * @param bool $onlyHourMin   /true 只对比时、分, false 对比日期
     * @param bool /true有交集，flase没有交集
     * @author zhangyi  <zhangyi@mofly.cn>
     */
    public function settledTimeCompare($startTime,$endTime,$compareStart,$compareEnd , $onlyHourMin = false){
        $sTime =   strtotime($startTime);
        $eTime =   strtotime($endTime);
        $cSTime =   strtotime($compareStart);
        $cETime =   strtotime($compareEnd);

//
//        echo       '$sTime:'.date("Y-m-d H:i:s",$sTime)."\n";
//        echo       '$eTime:'.date("Y-m-d H:i:s",$eTime)."\n";
//        echo       '$cSTime:'.date("Y-m-d H:i:s",$cSTime)."\n";
//        echo       '$cETime:'.date("Y-m-d H:i:s",$cETime)."\n";


        if($onlyHourMin){
            if(date("Y-m-d",$eTime) > date("Y-m-d",$sTime)){
                $endTime = date("Y-m-d",strtotime("+1 day"))." ".date("H:i:s",strtotime(($endTime)));
            }else{
                $endTime = date("Y-m-d")." ".date("H:i:s",strtotime(($endTime)));
            }
            $startTime = date("Y-m-d")." ".date("H:i:s",strtotime(($startTime)));

            if(date("Y-m-d",$cETime) > date("Y-m-d",$cSTime)){
                $compareEnd = date("Y-m-d",strtotime("+1 day"))." ".date("H:i:s",strtotime(($compareEnd)));
            }else{
                $compareEnd = date("Y-m-d")." ".date("H:i:s",strtotime(($compareEnd)));
            }
            $compareStart = date("Y-m-d")." ".date("H:i:s",strtotime(($compareStart)));
        }

        if( $startTime <= $compareStart && $endTime > $compareStart){     //部分交集
//            echo '1';
            return true;
        }

        if( $startTime >= $compareStart && $startTime < $compareEnd ){  //部分交集
//            echo '2';
            return true;
        }

        if($startTime <= $compareStart && $endTime >= $compareEnd){            //新的完全覆盖
//            echo '3';
            return true;
        }

        if($startTime >= $compareStart && $endTime <= $compareEnd){    //旧的完全覆盖
//            echo '4';
            return true;
        }
        return false;



    }


    /**
     * //周期对比
     * @param $loopScheduleRows    //周期项目集
     * @param $existScheduleArr     //已存在周期
     * @param $kstime      //周期秒杀开始时间点
     * @param $ketime        //周期秒杀结束时间点
     * @param $existStartTime    //已存在周期秒杀开始时间点
     * @param $existEndTime       //已存在周期秒杀开始时间点
     * @return bool
     * @author zhangyi  <zhangyi@mofly.cn>
     */
    public function scheduleScopeCompare($loopScheduleRows,$existScheduleArr,$kstime,$ketime,$existStartTime,$existEndTime){
        $compareResult = false;
        $intersection = array_intersect($loopScheduleRows, $existScheduleArr); //交集
        if (!empty($intersection)) {
            //1个星期的循环足够检测
            $weekDateRecordArr = array();
            foreach ($intersection as $singlePlan) {

                $weekDate =  date("w",strtotime($singlePlan)) ;
                if(in_array($weekDate,$weekDateRecordArr)){
                    break;
                }else{
                    $weekDateRecordArr[] =  $weekDate;
                }


                $checkStartTime = $singlePlan . " " . $kstime; //重复日期下的开始时间
                $checkStartTime = date("Y-m-d H:i:s",strtotime($checkStartTime));
                $checkEndTime =  $singlePlan . " " . $ketime;         //周期重复不会大于24小时
                if ($checkStartTime > $checkEndTime) {
                    $checkEndTime = date("Y-m-d", strtotime($checkEndTime) + 86400); //重复日期下的结束时间
                }

                //对比日期
                $compareStartTime = $singlePlan . " " . $existStartTime; //重复日期下的开始时间
                $compareEndTime = $singlePlan . " " . $existEndTime; //重复日期下的结束时间
                if ($compareStartTime > $compareEndTime) {
                    $compareEndTime = date("Y-m-d", strtotime($compareEndTime) + 86400); //重复日期下的结束时间
                }

//                //对比日期
//                $compareStartTime = $singlePlan . " " . substr($dbsStime, 11, 8); //重复日期下的开始时间
//                if (substr($dbSetime, 0, 10) > substr($dbsStime, 0, 10)) {
//                    $compareEndTime = date("Y-m-d", strtotime($dbSetime) + 86400) . " " . substr($dbSetime, 11, 8); //重复日期下的结束时间
//                } else {
//                    $compareEndTime = $singlePlan . " " . substr($dbSetime, 11, 8); //重复日期下的结束时间
//                }
                $compareResult =  $this->settledTimeCompare($checkStartTime, $checkEndTime, $compareStartTime, $compareEndTime);
//                if ($compareResult) {
//                    echo   $checkStartTime."\n";
//                    echo   $checkEndTime."\n";
//                    echo   $compareStartTime."\n";
//                    echo   $compareEndTime."\n";
//                    echo 'repeated !';
//                    break;
//                }

            }
        }
        return $compareResult;
    }


    /**
     * *获取指定范围内的日期列表
     * @param $startDate
     * @param $endDate
     * @param $scheduleCsv   | example "3,4,5"
     * @return array  | example array('2017-06-27','2017-06-30');
     * @author zhangyi  <zhangyi@mofly.cn>
     */
    function getScheduleScope($startDate,$endDate,$scheduleCsv){
        $scheduleArr = explode(",",$scheduleCsv);

        $sDate = date("Y-m-d",strtotime($startDate));
        $eDate = date("Y-m-d",strtotime($endDate));

        $countDateFlag = strtotime($sDate);

        $result = array();

        while(date("Y-m-d",$countDateFlag) <= $eDate){
            $weekDay = date("w", $countDateFlag);
            if($weekDay == 0){
                $weekDay = 7;
            }

            if(in_array($weekDay,$scheduleArr)){
                $result[] = date("Y-m-d",$countDateFlag);
            }
            $countDateFlag += 86400;

        }
        return $result;
    }

}