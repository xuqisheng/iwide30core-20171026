<?php
if(!defined('BASEPATH')){
    exit ('No direct script access allowed');
}


/**
 * 分页方法
 *
 * @param  int $total 记录总条数
 * @param int  $page_cur 当前页码
 * @param  int $page_size 每页记录条数
 * @return  array $page
 */
function get_page($total = 0, $page_cur = 1, $page_size = 30)
{
    $page = array(
        'start'     => 0,  //当前页面起始记录编号
        'end'       => 0,
        'total'     => 1,	 //页码数
        'current'   => 1	 //当前页码
    );

    $total=intval($total);
    $page_cur=intval($page_cur);
    $page_size=intval($page_size);
    $page_size=empty($page_size)?10:$page_size;
    $page_total = ceil($total/$page_size);	//总页数
    $page_start = 0;	//当前页面起始记录编号

    //处理当前页
    $page_cur=max($page_cur,1);
    //$page_cur=min($page_cur,$page_total);
    //处理当前页面起始记录编号
    if($page_cur != 1)
    {
        $page_start = ($page_cur - 1) * $page_size;
    }

    $page['start'] = $page_start;
    $page['end'] = $page_size;
    $page['page_size'] = $page_size;
    $page['page_total'] = $page_total;
    $page['total'] = $total;
    $page['current'] = $page_cur;
    return $page;
}

/**
 * 分页
 * @param int $count
 * @param int $page_cur
 * @param int $page_total
 * @param string $url
 * @param int $max
 * @param string $ajaxCallBack
 * @return string
 */
function pagehtml($count, $page_cur, $page_total, $url, $max = null, $ajaxCallBack = '')
{
    if ($page_total <= 1)
    {
        return '';
    }
    list($count, $page_cur, $page_total, $max) = array(intval($count), intval($page_cur), intval($page_total), intval($max));
    if ($page_total <= 0) return '';
    ($max && $page_total > $max) && $page_total = $max;

    $ajaxurl = $ajaxCallBack ? " onclick=\"return $ajaxCallBack(this.href);\"" : '';
    @list($url, $mao) = explode('#', $url);
    $mao && $mao = '#' . $mao;
    $pages = '<div class="pages" id="pages">';
    $preArrow = $nextArrow = $firstPage = $lastPage = '';
    if ($page_total >7) {
        list($pre, $next) = array($page_cur - 1, $page_cur + 1);
        // 		$page_cur > 1 && $preArrow = "<a class=\"pages_pre\" href=\"{$url}page={$pre}$mao\"{$ajaxurl}>&#x4E0A;&#x4E00;&#x9875;</a>";
        $page_cur > 1 && $preArrow = "<a class=\"<\" href=\"{$url}page={$pre}$mao\"{$ajaxurl}><</a>";
        // 		$page_cur < $page_total && $nextArrow = "<a class=\"pages_next\" href=\"{$url}page={$next}$mao\"{$ajaxurl}>&#x4E0B;&#x4E00;&#x9875;</a>";
        $page_cur < $page_total && $nextArrow = "<a class=\">\" href=\"{$url}page={$next}$mao\"{$ajaxurl}>></a>";
    }
    $page_cur != 1 && $firstPage = "<a href=\"{$url}page=1$mao\"{$ajaxurl}>" . (($page_total > 7 && $page_cur - 3 > 1) ? '1</a>' : '1</a>');
    $page_cur != $page_total && $lastPage = "<a href=\"{$url}page={$page_total}$mao\"{$ajaxurl}>" . (($page_total > 7 && $page_cur + 3 < $page_total) ? "$page_total</a>" : "$page_total</a>");

    list($tmpPages, $preFlag, $nextFlag) = array('', 0, 0);
    $leftStart = ($page_total - $page_cur >= 3) ? $page_cur - 2 : $page_cur - (5 - ($page_total - $page_cur));
    for ($i = $leftStart; $i < $page_cur; $i++) {
        if ($i <= 1) continue;
        $tmpPages .= "<a href=\"{$url}page=$i$mao\"{$ajaxurl}>$i</a>";
        $preFlag++;
    }
    $tmpPages .= "<span class='current'>{$page_cur}</span>";
    $nextFlag = 4 - $preFlag + (!$firstPage ? 1 : 0);
    if ($page_cur < $page_total) {
        for ($i = $page_cur + 1; $i < $page_total && $i <= $page_cur + $nextFlag; $i++) {
            $tmpPages .= "<a href=\"{$url}page=$i$mao\"{$ajaxurl}>$i</a>";
        }
    }
    $pages .= $preArrow . $firstPage . $tmpPages . $lastPage . $nextArrow;
    $jsString = "var page=(value>$page_total) ? $page_total : value; " . ($ajaxurl ? "$ajaxCallBack('{$url}page='+page);" : " location='{$url}page='+page+'{$mao}';") . " return false;";
    $pages .= '</div>';
    return $pages;
}

/**
 * @param int $status =状态1正常，0异常；$msg=返回提示信息；$data=返回数据，支持数组
 * @param string $msg
 * @param array $data
 * @param string $type
 * @param bool $rsa
 * @return string 返回ajax请求结果
 * 返回ajax请求结果
 */
function ajax_return($status = 1, $msg = '', $data = array(), $type = 'json')
{
    $arr = array(
        "status"    => $status,
        "msg"       => $msg,
        "data"      => $data,
    );

    if($type == 'json')
    {
        header('content-type:application/json;charset=utf-8');
        echo json_encode($arr);
    }
    exit;
}

/**
 * 校验手机号码是否合法
 * @param $phone 手机号码
 * @return bool
 */
function check_phone($phone)
{
    $pattern_phone = "/^1[1345678]\d{9}$/i";
    if(preg_match($pattern_phone, $phone)){
        return true;
    }
    return false;
}

/**
 * formatMoney 金额浮点精度问题，避免四舍五入
 *
 * @param string $money
 * @static
 * @access public
 * @return void
 */
function formatMoney($money)
{
    return substr(sprintf('%.3f', $money), 0, -1);
}

/**
 * 获取IP
 */
function get_client_ip(){
    if(isset ($_SERVER)){
        if(isset ($_SERVER ["HTTP_X_FORWARDED_FOR"])){
            $realip = $_SERVER ["HTTP_X_FORWARDED_FOR"];
        } else{
            if(isset ($_SERVER ["HTTP_CLIENT_IP"])){
                $realip = $_SERVER ["HTTP_CLIENT_IP"];
            } else{
                $realip = $_SERVER ["REMOTE_ADDR"];
            }
        }
    } else{
        if(getenv("HTTP_X_FORWARDED_FOR")){
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else{
            if(getenv("HTTP_CLIENT_IP")){
                $realip = getenv("HTTP_CLIENT_IP");
            } else{
                $realip = getenv("REMOTE_ADDR");
            }
        }
    }
    return $realip;
}


/**
 * 优先返回post数据，如果为空则返回get数据
 * @param string $index
 * @param bool $xss_clean
 * @return array
 */
function request($index = null, $xss_clean = FALSE)
{
    $index = trim($index);

    $method = $_SERVER['REQUEST_METHOD'];
    if (in_array($method,array('PUT','OPTIONS','DELETE','PATCH')))
    {
        $raw = file_get_contents('php://input');
        $raw = !empty($raw) ? json_decode($raw, true) : array();

        if (!empty($index))
        {
            return !empty($raw[$index]);
        }
        else
        {
            return $raw;
        }
    }

    if(empty($index))
    {
        $index = null;
    }
    $CI =& get_instance();
    $data_post = $CI->input->post($index, $xss_clean);
    $data_get = $CI->input->get($index, $xss_clean);
    if(empty($index))
    {
        if(empty($data_get))
        {
            $data_get = array();
        }
        if(empty($data_post))
        {
            $data_post = array();
        }
        $data = array_merge($data_get,$data_post);
    }
    else
    {
        $data = $data_post;
        if(empty($data))
        {
            $data = $data_get;
        }
    }
    return $data;
}


/**
 * 获取请求体
 * @return mixed|null
 */
function getBodyParams()
{
    $raw = file_get_contents('php://input');
    if (empty($raw))
    {
        return array();
    }
    return json_decode($raw, true);
}

function sku_price_data($arr, $key)
{
    $res_arr = array();
    foreach ($arr as $k => $v)
    {
        $low_price = sortMultiArray($v,$key);
        $res_arr[$k]['price'] = $low_price[0]['goods_price'];
        $res_arr[$k]['stock'] = $low_price[0]['goods_stock'];
    }
    return $res_arr;
}

// 使用冒泡排序法进行排序
function sortMultiArray($arr, $key)
{
    $len = count($arr);
    for ($i=0; $i<$len-1; $i++)
    {
        for ($j=$i+1; $j<$len; $j++)
        {
            if ($arr[$i][$key] > $arr[$j][$key])
            {
                $tmp = $arr[$i];
                $arr[$i] =  $arr[$j];
                $arr[$j] = $tmp;
            }
        }
    }
    return $arr;
}

/**
 * 返回门票商品提前预约优惠金额
 * @param $goods_res 商品数组
 * @return array
 */
function count_ticket_fee($goods_res)
{
    $discount_fee = 0;
    if (!empty($goods_res))
    {
        foreach ($goods_res as $key => $value)
        {
            $discount = 0;//优惠金额
            //提前预约优惠
            if ($value['ticket_credits'] == 2)
            {
                if ($value['ticket_day'] > 0 && $value['ticket_limit'] > 0)
                {
                    $days = $value['ticket_day'] - 1;
                    $date = date('Y-m-d 00:00:00',strtotime("+ {$days} days"));
                    if ($date <= $value['book_day'])
                    {
                        //折扣
                        if ($value['ticket_style'] == 2)
                        {
                            $discount = $value['goods_num'] * $value['shop_price'] * (1 - $value['ticket_limit']/10);
                        }
                        //立减
                        else
                        {
                            $discount = $value['goods_num'] * $value['ticket_limit'];
                        }
                    }

                }
            }
            $discount_fee += $discount;
            $goods_res[$key]['discount'] = formatMoney($discount);
        }
    }

    $data = array(
        'goods_info'    => $goods_res,
        'discount_fee'  => formatMoney($discount_fee),
    );
    return $data;
}



/**
 *  @description : 获取订单号
 *  @author      : jane
 *  @date        : 2015-12-22
 *  @param $data
 *  @return void
 */
function getOrderNo($data = array())
{
    /*生成20位 创建订单*/
    $year   = !empty($data['year'])?sprintf("%04d",$data['year']):'Y';
    $month  = !empty($data['month'])?sprintf("%02d",$data['month']):'m';
    $date   = !empty($data['date'])?sprintf("%02d",$data['date']):'d';
    $time   = !empty($data['time'])?sprintf("%02d",$data['time']):'H';
    $branch = !empty($data['branch'])?sprintf("%02d",$data['branch']):'i';
    $second = !empty($data['second'])?sprintf("%02d",$data['second']):'s';

    $date_str = date($year.$month.$date.$time.$branch.$second);
    $date_str = date('YmdHis',strtotime($date_str));

    list($usec, $sec) = explode(" ", microtime());
    $str = (float)$usec*1000000;
    $str = (string)$str;
    $date_str .= $str;
    $len_des  = 20 - strlen($date_str);
    if($len_des>0)
    {
        for($i=0;$i<$len_des;$i++)
        {
            $date_str.= 0;
        }
    }
    return $date_str;
}

function write_log($data,$re = '',$result = '',$file=NULL, $path=NULL,$dir = 'ticket')
{
    if(!$file) $file= date('Y-m-d'). '.txt';
    if(!$path) $path= APPPATH. 'logs'. DS. $dir. DS;

    if( !file_exists($path) ) {
        @mkdir($path, 0777, TRUE);
    }

    if(is_array($data)){
        $data=json_encode($data);
    }
    if(is_array($result)){
        $result=json_encode($result);
    }
    $fp = fopen($path.$file, "a");
    $content = date("Y-m-d H:i:s")." | ".getmypid()." | ".$_SERVER['PHP_SELF']." | ".session_id()." | ".$data." | ".$re." | ".$result."\n";

    fwrite($fp, $content);
    fclose($fp);
}

/**
 * 检测店铺当天消费时段
 * @param  $shop_data 店铺营业时段
 * @param  $goods_data 商品消费时段设置
 * @return bool
 */
function check_time_range($shop_data,$goods_data)
{
    $cur_time = date('H:i',time());
    //判断当前时间 是否在商品设置消费时段范围
    if (!empty($shop_data) && !empty($goods_data))
    {
        $shop_data = json_decode($shop_data,true);
        $goods_data = json_decode($goods_data,true);
        $end_time = array();
        foreach ($shop_data as $item)
        {
            if (in_array($item['name'],$goods_data,true))
            {
                $end_time[] = $item['end_time'];
            }
        }
        if (!empty($end_time))
        {
            $end_time =  max($end_time);
            if ($end_time > $cur_time)
            {
                return 1;
            }
        }
    }

    return 0;
}


/**
 * 更改总订单状态
 * @param $merge_order
 * @param $order_status int 子单状态
 * @param $update_status int 总单状态
 */
function update_merge_order_status($merge_order,$order_status = 20,$update_status = 2)
{
    if (!empty($merge_order))
    {
        $CI =& get_instance();
        $merge_order = array_unique($merge_order);//去掉重复值
        $CI->load->model('ticket/ticket_orders_merge_model');
        $CI->load->model('roomservice/roomservice_orders_model');
        foreach ($merge_order as $item)
        {
            $filter = array(
                'merge_order_no' => $item,
                'type' => 4,
            );
            $orders = $CI->roomservice_orders_model->get_orders($filter,'','','w');//防止主从延时
            if (!empty($orders))
            {
                $count = count($orders);
                $num = 0;
                $finish_num = 0;
                foreach ($orders as $order)
                {
                    if ($order['order_status'] == $order_status || $order['order_status'] == 26)
                    {
                        $num++;
                    }
                    else if ($order['order_status'] == 20)
                    {
                        $finish_num++;
                    }
                }

                $had_finish = $finish_num > 0 ? $finish_num + $num : 0;

                //更改总单状态
                $update = array(
                    'update_time' => date('Y-m-d H:i:s'),
                    'order_status' => $update_status,
                );
                $where = array(
                    'order_no' => $item,
                );
                //全部
                if ($count == $num || ($num > 0 && $order_status == 5))
                {
                    $CI->ticket_orders_merge_model->update_order($update,$where);
                }
                //存在完成，其他订单都取消的情况
                else if ($count == $had_finish)
                {
                    $update['order_status'] = 2;
                    $CI->ticket_orders_merge_model->update_order($update,$where);
                }
            }
        }
    }
}

/**
 * 精确获取毫秒
 * @return float
 */
function getMillisecond()
{
    list($t1, $t2) = explode(' ', microtime());
    return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
}


function handle_ticket_price($goods,$price,$book_day)
{
    $discount = 0;//优惠金额
    //提前预约优惠
    if ($goods['ticket_credits'] == 2)
    {
        if ($goods['ticket_day'] > 0 && $goods['ticket_limit'] > 0)
        {
            $days = $goods['ticket_day'] - 1;
            $date = date('Y-m-d 00:00:00',strtotime("+ {$days} days"));
            if ($date <= $book_day)
            {
                //折扣
                if ($goods['ticket_style'] == 2)
                {
                    $discount = 1 * $price * (1 - $goods['ticket_limit']/10);
                }
                //立减
                else
                {
                    $discount = 1 * $goods['ticket_limit'];
                }
            }

        }
    }
    $price = formatMoney($price - $discount);
    return $price > 0 ? $price : 0;
}

/**
 *  分账系统
 *  自动生成金房卡内部ID
 * @param $id
 * @param string $pre
 * @return string
 */
function create_merchant_no($id,$pre = 'FZZH')
{
    return $pre . $id;
}


/**
 *
 *   phpexcel 导出文件处理函数
 *   $fileName 文件名
 *   $headArr 标题名
 *   $headArr 数据
 *   $widthArr 列宽
 * @param $fileName
 * @param $headArr
 * @param $data
 * @param $widthArr
 */
function getExcel($fileName,$headArr,$data,$widthArr = array())
{
    if(empty($data) || !is_array($data))  die('暂无导出数据');
    if(empty($fileName)) exit;
    $date = date("Y_m_d",time());
    $fileName .= "_{$date}.xlsx";
    #注意表格列只能少于26
    $zm='ABCDEFGHIJKLMNOPQRSTUVWSYZ';
    //创建新的PHPExcel对象
    $CI =& get_instance();
    $CI->load->library('PHPExcel');
    $CI->load->library('PHPExcel/IOFactory');
    $objPHPExcel = new PHPExcel();
    $objProps = $objPHPExcel->getProperties();
    //水平居中
    foreach($headArr as $k=>$v){
        $objPHPExcel->getActiveSheet()->getStyle($zm[$k])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    }
    //垂直居中
    foreach($headArr as $k=>$v){
        $objPHPExcel->getActiveSheet()->getStyle($zm[$k])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    }
    //宽度
    if(!empty($widthArr) && is_array($widthArr)){
        foreach($widthArr as $k=>$v){
            $objPHPExcel->getActiveSheet()->getColumnDimension($zm[$k])->setWidth($v);
        }
    }
    //设置表头
    $key = ord("A");
    foreach($headArr as $v){
        $colum = chr($key);
        $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
        $key += 1;
    }
    $column = 2;
    $objActSheet = $objPHPExcel->getActiveSheet();
    foreach($data as $key => $rows){ //行写入
        $span = ord("A");
        foreach($rows as $keyName=>$value){// 列写入
            $j = chr($span);
            $objActSheet->setCellValue($j.$column, $value);
            $span++;
        }
        $column++;
    }
    $file_Name=$fileName;
   // $file_Name=$fileName = iconv("utf-8", "utf-8", $fileName);
    //$fileName='../../output/'.$fileName;
    //重命名表
    $objPHPExcel->getActiveSheet()->setTitle('Simple');
    //设置活动单指数到第一个表,所以Excel打开这是第一个表
    $objPHPExcel->setActiveSheetIndex(0);
    //将输出重定向到一个客户端web浏览器(Excel2007)
    $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');//低版本
     //if(!empty($_GET['excel'])){
    //	$objWriter->save('php://output'); //文件通过浏览器下载
    //}else{
    //  $objWriter->save($fileName); //脚本方式运行，保存在当前目录
    //}
    #直接浏览器下载文件
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename='.$file_Name);
    header('Cache-Control: max-age=0');
    $objWriter->save('php://output');
    exit;
}

/**
 * 处理 分账 交易流水订单状态
 */
function return_iwidepay_status($data)
{
    if ($data['transfer_status'] >= 6 && $data['transfer_status'] <= 7)
    {
        $status = '全额退款成功';
    }
    else if ($data['transfer_status'] >= 8 && $data['transfer_status'] <= 9)
    {
        $status = '部分退款成功';
    }
    else
    {
        $status = '用户已支付';
    }
    return $status;
}

/**
 * 分账规则 格式化数据
 * @param $data
 * @return string
 */
function handle_rule_value($data)
{
    $value['regular_jfk_cost'] = $data['select']['regular_jfk_cost'] == 1 ? $data['regular_jfk_cost'] .'%' : $data['regular_jfk_cost'];
    $value['regular_jfk'] = $data['select']['regular_jfk'] == 1 ? $data['regular_jfk'] .'%' : $data['regular_jfk'];
    $value['regular_group'] = $data['select']['regular_group'] == 1 ? $data['regular_group'] .'%' : $data['regular_group'];
    $value['regular_hotel'] = $data['select']['regular_hotel'] == 1 ? $data['regular_hotel'] .'%' : $data['regular_hotel'];
    return $value;
}

/**
 * 添加分账操作日志
 * @param $data
 * @param string $op_type
 */
function add_iwidepay_admin_op_log($data, $op_type= 'edit')
{
    $CI =& get_instance();
    $CI->load->model('iwidepay/iwidepay_admin_op_log_model');
    $user_info = $CI->session->userdata('admin_profile');
    $log['inter_id'] = $user_info['inter_id'];
    $log['username'] = $user_info['username'];
    $log['uid'] = $user_info['admin_id'];
    $log['jfk_no'] = $data['jfk_no'];
    $log['op_type'] = $op_type;
    $log['op_time'] = date('Y-m-d H:i:s');
    $log['ip'] = get_client_ip();
    $log['data'] = json_encode($data);
    $CI->iwidepay_admin_op_log_model->add_log($log);
}

