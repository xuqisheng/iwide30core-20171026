<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SignIn_model
 *
 * @author vencelyang
 */
class Signin_model extends MY_Model_Member
{
    protected $table_record = 'iwide_sign_in_record';// 签到记录表
    protected $table_stat = 'iwide_sign_in_stat';// 签到统计表
    protected $table_conf = 'iwide_sign_in_conf';// 签到设置表

    /**
     * 获取签到统计数据
     * @param string $inter_id 集团微信id
     * @return array|null
     */
    public function get_stat_data($inter_id)
    {
        if (empty($inter_id))
            return null;

        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
        $yesterdayStart = date('Y-m-d 00:00:00', strtotime('-1 day'));
        $yesterdayEnd = date('Y-m-d 23:59:59', strtotime('-1 day'));
        $thisWeekStart = date('N') == 1 ? date('Y-m-d 00:00:00') : date('Y-m-d 00:00:00', strtotime('last Monday'));
        $thisWeekEnd = date('Y-m-d 23:59:59');
        $lastWeekStart = date('N') == 1 ? date('Y-m-d 00:00:00', strtotime('last Monday')) : date('Y-m-d 00:00:00', strtotime('-1 week last Monday'));
        $lastWeekEnd = date('Y-m-d 23:59:59', strtotime('last Sunday'));
        $thisMonthStart = date('Y-m-01 00:00:00');
        $thisMonthEnd = date('Y-m-t 23:59:59');
        $lastMonthStart = date('Y-m-01 00:00:00', strtotime('-1 month'));
        $lastMonthEnd = date('Y-m-t 23:59:59', strtotime('-1 month'));

        $data = array();
        $data['time_today'] = $this->get_stat_times_count($inter_id, $todayStart, $todayEnd);
        $data['time_yesterday'] = $this->get_stat_times_count($inter_id, $yesterdayStart, $yesterdayEnd);
        $data['time_this_week'] = $this->get_stat_times_count($inter_id, $thisWeekStart, $thisWeekEnd);
        $data['time_last_week'] = $this->get_stat_times_count($inter_id, $lastWeekStart, $lastWeekEnd);
        $data['time_this_month'] = $this->get_stat_times_count($inter_id, $thisMonthStart, $thisMonthEnd);
        $data['time_last_month'] = $this->get_stat_times_count($inter_id, $lastMonthStart, $lastMonthEnd);
        $data['bonus_this_month'] = $this->get_stat_bonus_sum($inter_id, $thisMonthStart, $thisMonthEnd);
        $data['this_month'] = date('n');

        return $data;
    }

    /**
     * 根据时间段获取签到人次
     * @param string $inter_id 集团微信id
     * @param string $startDate 开始日期
     * @param string $endDate 结束日期
     * @return int|null
     */
    public function get_stat_times_count($inter_id, $startDate, $endDate)
    {
        if (empty($inter_id))
            return null;

        if (empty($startDate) || empty($endDate))
            return null;

        $result = $this->_shard_db()->select('count(*) as total')
            ->from($this->table_record)
            ->where("inter_id = '{$inter_id}' and sign_at between '{$startDate}' and '{$endDate}'")
            ->get()->row_array();

        return $result['total'];
    }

    /**
     * 根据时间段获取签到积分
     * @param string $inter_id 集团微信id
     * @param string $startDate 开始日期
     * @param string $endDate 结束日期
     * @return int|null
     */
    public function get_stat_bonus_sum($inter_id, $startDate, $endDate)
    {
        if (empty($inter_id))
            return null;

        $result = $this->_shard_db()->select('(sum(bonus) + sum(bonus_extra)) as bonus_sum')
            ->from($this->table_record)
            ->where("inter_id = '{$inter_id}' and sign_at between '{$startDate}' and '{$endDate}'")
            ->get()->row_array();

        if (empty($result['bonus_sum']))
            return 0;

        return $result['bonus_sum'];
    }

    /**
     * 获取导出数据
     * @param string $inter_id 集团微信id
     * @param int $ym 年月
     * @return array|null
     */
    public function get_export_data($inter_id, $ym)
    {
        if (empty($inter_id) || empty($ym))
            return null;

        // 获取签到人次、积分数据
        $time = strtotime($ym . '01');
        $date = date('Y-m-d H:i:s', $time);
        $thisMonthStart = date('Y-m-01 00:00:00', $time);
        $thisMonthEnd = date('Y-m-t 23:59:59', $time);
        $lastMonthStart = date('Y-m-01 00:00:00', strtotime($date . '-1 month'));
        $lastMonthEnd = date('Y-m-t 23:59:59', strtotime($date . '-1 month'));
        $time_this_month = $this->get_stat_times_count($inter_id, $thisMonthStart, $thisMonthEnd);
        $time_last_month = $this->get_stat_times_count($inter_id, $lastMonthStart, $lastMonthEnd);
        $bonus_this_month = $this->get_stat_bonus_sum($inter_id, $thisMonthStart, $thisMonthEnd);
        $bonus_last_month = $this->get_stat_bonus_sum($inter_id, $lastMonthStart, $lastMonthEnd);

        // 整理导出数据
        $data = array();
        $thisMonth = date('n', $time);
        $lastMonth = date('n', strtotime($date . ' -1 month'));
        $data[] = array($thisMonth . '月统计情况');
        $data[] = array('', '签到人次', '发放积分');
        $data[] = array($thisMonth . '月', $time_this_month, $bonus_this_month);
        $data[] = array($lastMonth . '月', $time_last_month, $bonus_last_month);
        $compare_time = $this->get_compare($time_this_month, $time_last_month);
        $compare_bonus = $this->get_compare($bonus_this_month, $bonus_last_month);
        $compare_time = round($compare_time, 4) * 100 . '%';
        $compare_bonus = round($compare_bonus, 4) * 100 . '%';
        $data[] = array('环比增长', $compare_time, $compare_bonus);
        $data[] = array();

        // 获取本月每天签到人次数据
        $res_this_month = $this->_shard_db()->select('sign_at,count(*) as sign_time,(sum(bonus) + sum(bonus_extra)) as sign_bonus')
            ->from($this->table_record)
            ->where("inter_id = '{$inter_id}' and sign_at between '{$thisMonthStart}' and '{$thisMonthEnd}'")
            ->group_by('ymd')
            ->order_by('sign_at')
            ->get()->result_array();

        $data[] = array('日期', '签到人次', '发放积分');
        foreach ($res_this_month as $v) {
            $sign_date = date('Y-m-d', strtotime($v['sign_at']));
            $v['sign_bonus'] = !empty($v['sign_bonus']) ? $v['sign_bonus'] : 0;
            $data[] = array($sign_date, $v['sign_time'], $v['sign_bonus']);
        }

        return $data;
    }

    /**
     * 获取某月导出数据
     * @param string $inter_id 集团微信id
     * @param string $ym 年月
     * @return array|null
     */
    public function get_export_list($inter_id = '', $ym = ''){
        if (empty($inter_id) || empty($ym)) return null;
        // 获取签到人次、积分数据
        $time = strtotime($ym . '01');
        $thisMonthStart = date('Y-m-01 00:00:00', $time);
        $thisMonthEnd = date('Y-m-t 23:59:59', $time);

        // 获取设置数据
        $confInfo = $this->get_conf_info($inter_id,'serial_days');
        // 获取本月每天签到人次数据
        $res_this_month = $this->_shard_db()->select('re.sign_at,re.member_info_id,mi.membership_number,mi.name,re.bonus,re.bonus_extra,(sum(re.bonus) + sum(re.bonus_extra)) as sign_bonus,re.serial_days')
            ->from("{$this->table_record} re")
            ->join('member_info mi','mi.member_info_id = re.member_info_id','left')
            ->where("re.inter_id = '{$inter_id}' and re.sign_at between '{$thisMonthStart}' and '{$thisMonthEnd}'")
            ->group_by('re.id')
            ->order_by('re.sign_at')
            ->get()->result_array();
        $data = array();
        $data[] = array('签到时间', '会员ID', '会员号', '会员名称', '获得总积分', '备注');
        $serial_days = !empty($confInfo['serial_days']) ? $confInfo['serial_days'] : 0;
        foreach ($res_this_month as $val){
            $is_extra = $val['serial_days'] % $serial_days;
            if($is_extra === 0){
                $val['note'] = "签到，赠送{$val['bonus']}积分；连续签到，额外赠送{$val['bonus_extra']}积分";
            }else{
                $val['note'] = "签到，赠送{$val['bonus']}积分";
            }
            $data[] = array("{$val['sign_at']}\t", $val['member_info_id'], $val['membership_number'], $val['name'], $val['sign_bonus'], $val['note']);
        }

        return $data;
    }

    /**
     * 环比统计
     * @param $current int
     * @param $prev int
     * @return int|float
     */
    public function get_compare($current, $prev)
    {
        if (floatval($current) == 0)
            return 0;

        if (floatval($prev) == 0)
            return 1;

        return ($current - $prev) / $prev;
    }

    /**
     * 获取配置数据
     * @param string $inter_id 集团微信id
     * @param string string $field
     * @return array|null
     */
    public function get_conf_info($inter_id, $field = '*')
    {
        if (empty($inter_id))
            return null;

        return $this->_shard_db()->select($field)->from($this->table_conf)->where(array('inter_id' => $inter_id))->get()->row_array();
    }

    /**
     * 新增签到配置
     * @param string $inter_id 集团微信id
     * @param array $params post数据
     * @return bool
     */
    public function conf_add($inter_id, $params)
    {
        if (empty($inter_id) || empty($params))
            return false;

        // 新增签到记录
        $date_time = date('Y-m-d H:i:s');
        $data = array();
        $data['inter_id'] = $inter_id;
        $data['bonus_day'] = $params['bonus_day'];
        $data['bonus_extra'] = $params['bonus_extra'];
        $data['serial_days'] = 7;
        $data['serial_content'] = $params['serial_content'];
        $data['serial_reward_content'] = $params['serial_reward_content'];
        $data['is_active'] = $params['is_active'];
        $data['active_at'] = $params['is_active'] ? $date_time : '0000-00-00 00:00:00';
        $data['create_at'] = $date_time;
        $data['update_at'] = $date_time;

        $result = $this->_shard_db(true)->insert($this->table_conf, $data);
        if (!$result) {// 判断新增是否成功
            return false;
        }

        return true;
    }

    /**
     * 修改签到配置
     * @param string $inter_id 集团微信id
     * @param array $params post数据
     * @return bool
     */
    public function conf_edit($inter_id, $params)
    {
        if (empty($inter_id) || empty($params))
            return false;

        // 新增签到记录
        $date_time = date('Y-m-d H:i:s');
        $data = array();
        $data['bonus_day'] = $params['bonus_day'];
        $data['bonus_extra'] = $params['bonus_extra'];
        $data['serial_content'] = $params['serial_content'];
        $data['serial_reward_content'] = $params['serial_reward_content'];
        $data['is_active'] = $params['is_active'];
        $data['update_at'] = $date_time;

        $res_conf = $this->get_conf_info($inter_id, 'is_active');
        if ($res_conf['is_active'] == 0 && $params['is_active'] == 1) {// 判断是否启动
            $data['active_at'] = $date_time;
        }

        $res_record_update = $this->_shard_db(true)->update($this->table_conf, $data, array('id' => $params['id']));
        if (!$res_record_update) {// 判断更新是否成功
            return false;
        }

        return true;
    }
}
