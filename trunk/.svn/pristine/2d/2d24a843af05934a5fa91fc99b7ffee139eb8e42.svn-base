<?php

namespace App\models\soma;


/**
 * Class Activity_killsec_intance
 * @package App\models\soma
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class Activity_killsec_instance extends \MY_Model_Soma
{
    const STATUS_PREVIEW = 1;
    const STATUS_GOING = 2;
    const STATUS_FINISH = 3;

    /**
     * @return string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function table_primary_key()
    {
        return 'instance_id';
    }

    /**
     * @return string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function table_name()
    {
        return 'soma_activity_killsec_instance';
    }

    public function isAvaliable(Array $arr)
    {

    }

    /**
     * 获取可以操作失效的状态值
     * @return array
     * @author luguihong  <luguihong@jperation.com>
     */
    public function get_can_disable_status()
    {
        return array(
            self::STATUS_PREVIEW,//准备开始
            self::STATUS_GOING,//正在进行中
        );
    }

    /**
     * @param $id
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getById($id)
    {
        $rows = $this->get($this->table_primary_key(), $id);
        if (empty($rows)) {
            return [];
        }
        return $rows[0];
    }

    /**
     *
     * @param array $activity (activity_killsec_model array)
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getData(Array $activity)
    {
        $data = [];
        $data['act_id'] = $activity['act_id'];
        $data['inter_id'] = $activity['inter_id'];
        $data['hotel_id'] = $activity['hotel_id'];
        $data['start_time'] = $activity['killsec_time'];
        $data['close_time'] = $activity['end_time'];
        $data['product_id'] = $activity['product_id'];
        $data['schedule_type'] = $activity['schedule_type'];
        $data['schedule'] = $activity['schedule'];
        $data['killsec_price'] = $activity['killsec_price'];
        $data['killsec_count'] = $activity['killsec_count'] > 10000 ? 10000 : $activity['killsec_count'];
        $data['killsec_permax'] = $activity['killsec_permax'];
        $data['join_count'] = 0;
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['status'] = Activity_killsec_instance::STATUS_PREVIEW;
        return $data;
    }

    public function checkAndSave(Array $activity)
    {
        $arr = $this->getData($activity);

        $rows = $this->get(
            [
                'act_id',
                'start_time',
                'close_time',
            ],
            [
                $arr['act_id'],
                $arr['start_time'],
                $arr['close_time'],
            ]
        );

        if (!empty($rows)) {
            return $rows[0]['instance_id'];
        }

        return $this->add($arr);
    }

    /**
     * @param array $arr
     * @return int
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function add(Array $arr)
    {
        $this->soma_db_conn->set($arr)->insert($this->table_name());

        return $this->soma_db_conn->insert_id();
    }

    /**
     * 获取秒杀活动对应的实例数据
     *
     * @param $actID
     * @param $startTime
     * @param $endTime
     * @return array
     * @author renshuai  <renshuai@jperation.cn>
     */
	public function getUsingRow($actID, $startTime, $endTime)
    {
        $rows = $this->get(
            [
                'act_id',
                'start_time',
                'close_time',
            ],
            [
                $actID,
                $startTime,
                $endTime,
            ]
        );

        if (empty($rows) || !isset($rows[0])) {
            return [];
        }

        return $rows[0];
    }
    


}