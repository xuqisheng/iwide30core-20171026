<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('gen_unique_rand'))
{
    /**
     * array unique_rand( int $min, int $max, int $num )
     * 生成一定数量的不重复随机数
     * $min 和 $max: 指定随机数的范围
     * $num: 指定生成数量
     */
    function gen_unique_rand($min, $max, $num)
    {
        if( ($max-$min) < $num *5 )
            die('生成随机数范围过小，必须大于生成数量5倍才有较好运行效果');
        $count = 0;
        $return = array();
        while ($count < $num) {
            $return[] = mt_rand($min, $max);
            $return = array_flip(array_flip($return));
            $count = count($return);
        }
        shuffle($return);
        return $return;
    }
}

if ( ! function_exists('render_percent_number'))
{
    /**
     * 显示百分比数字
     * @param Number $number  -1至1的浮点数字
     * @return String
     */
    function render_percent_number( $number )
    {
        if($number==0){
            return '-';
        } elseif ($number>0){
            return '<span style="color:red;"><i class="fa fa-arrow-up"></i> '. round($number*100, 2). '%</span>';
        } elseif ($number<0){
            return '<span style="color:green;"><i class="fa fa-arrow-down"></i> '. round(abs($number)*100, 2). '%</span>';
        }
    }
}

if ( ! function_exists('float_precision_match'))
{
    /**
     * 浮点精度比较
     * @param float $precision  -1至1的浮点数字
     * @return Boolean TRUE 相等  FALSE 不等
     */
    function float_precision_match($number1, $number2, $precision= '0.01' )
    {
        $total_dif= $number1- $number2;
        if( $total_dif< - $precision || $total_dif> $precision ){
            return FALSE;
        } else {
            return TRUE;
        }
    }
}

