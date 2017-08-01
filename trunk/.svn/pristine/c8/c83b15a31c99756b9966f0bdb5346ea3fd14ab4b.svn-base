<?php

/**
 * @param $targetTime //DateFormat YYYY-mm-dd HH:ii:ss
 * @return string
 */
if ( ! function_exists('time_left'))
{
    function time_left($targetTime, $convert=TRUE)
    {
    
        //计算截止时间
        if($convert) $timeLeft = strtotime($targetTime) - time();
        else $timeLeft = $targetTime - time();
        if ($timeLeft > 0) {
            $hour = intval($timeLeft / 3600);
            $minute = intval(($timeLeft - $hour * 3600) / 60);
            $second = $timeLeft % 60;
    
            if ($hour < 10) $hour = '0' . $hour;
            if ($minute < 10) $minute = '0' . $minute;
            if ($second < 10) $minute = '0' . $second;
            $timeLeft = $hour . ':' . $minute . ":" . $second;
        } else {
            $timeLeft = "00:00:00";
        }
    
        return $timeLeft;
    
    }
}
if ( ! function_exists('day_left'))
{
    function day_left($expireDate, $convert=TRUE)
    {
        if($convert) $expireDate = strtotime($targetTime);
        $nowDate = time();
        return floor( ( $expireDate - $nowDate) /(3600*24) );
    }
}
if ( ! function_exists('last_week_date'))
{
    function last_week_date($week_numeric, $next='+0')
    {
        $match= array(
            1=> 'Monday',
            2=> 'Tuesday',
            3=> 'Wednesday',
            4=> 'Thursday',
            5=> 'Friday',
            6=> 'Saturday',
            7=> 'Sunday',
        );
        if(array_key_exists($week_numeric, $match)){
            return date("Y-m-d", strtotime("{$next} week {$match[$week_numeric]}"));
        } else {
            return '';
        }
    }
}