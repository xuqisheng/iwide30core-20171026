<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Statis_sales_model extends MY_Model_Soma {

    const K_SALE_TOTAL= 1;
    const K_SALE_COUNT= 2;
    const K_SALE_QTY  = 3;
    const K_SALE_RANK  =5;
    const K_CONSUME_QTY=4;
    
    public $redis= '';
    
    /**
     * 初始化redis实例
     * @return Statis_sales_model
     */
    public function init_service()
    {
        $cache= $this->_load_cache();
        //$cache->redis->select_db(self::REDIS_DB);  //由redis.php 配置文件自动识别哪个库
        $this->redis= $cache->redis->redis_instance();
        return $this;
    }

    /**
     * 获取对应redis键值
     * @param unknown $inter_id
     * @param string $key
     * @return string
     */
    public function redis_token_key( $inter_id, $key=NULL, $stl_key='')
    {
        $base= 'SOMA_STATIS';
        if($key==self::K_SALE_TOTAL )
            return "{$base}:SALES_{$inter_id}_TOTAL{$stl_key}";
        elseif($key==self::K_SALE_COUNT )
            return "{$base}:SALES_{$inter_id}_COUNT{$stl_key}";
        elseif($key==self::K_SALE_QTY )
            return "{$base}:SALES_{$inter_id}_QTY{$stl_key}";
        elseif($key==self::K_SALE_RANK )
            return "{$base}:SALES_{$inter_id}_RANK{$stl_key}";
        elseif($key==self::K_CONSUME_QTY )
            return "{$base}:CONSUMER_{$inter_id}_QTY";
    }

    /**
     * 检查是否需要重建所有的销售数据
     * @return boolean
     */
    public function check_sales_data()
    {
        $redis= $this->redis;
        $this->load->model('soma/Sales_order_model');
        $last_time= date('Y-m-d H:i:s', strtotime('-3 days') );
        $result= $this->_db()->where('create_time >', $last_time )
            ->where('status', Sales_order_model::STATUS_PAYMENT )
            ->limit(1)->get('soma_sales_order_idx')->result_array();
        
        $total_key= $this->redis_token_key($result[0]['inter_id'], self::K_SALE_TOTAL );
        $count_key= $this->redis_token_key($result[0]['inter_id'], self::K_SALE_COUNT );
        $qty_key= $this->redis_token_key($result[0]['inter_id'], self::K_SALE_QTY );
        
        if( $redis->zSize($total_key) && $redis->zSize($count_key) && $redis->zSize($qty_key) ){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 根据预定的时间区间进行订单数据汇总插入Redis
     * @param string $start   筛选开始时间
     * @param string $end   筛选结束时间
     * @param array $o_filter   订单额外过滤条件
     * @param array $o_filter   消费单额外过滤条件
     * @return Ambigous <multitype:, multitype:unknown >
     */
    public function update_sales_data($start=NULL, $end=NULL, $o_filter=array(), $c_filter=array() )
    {
        $redis= $this->redis;
    
        $this->load->model('soma/Sales_order_model');
    
        //默认值为统计时间的原起始日期
        $last_time= $start? $start: date('Y-m-d H:i:s', strtotime('-90 days') );
        $end_time= $end? $end: date('Y-m-d H:i:s' );
    
        $this->_db()->where('create_time >', $last_time )->where('create_time <', $end_time )
            ->where('status', Sales_order_model::STATUS_PAYMENT )->order_by('inter_id asc');
        foreach ($o_filter as $k=>$v){
            if( is_array($v) ) $this->_db()->where_in($k, $v);
            else $this->_db()->where($k, $v);
        }
        $result= $this->_db()->get('soma_sales_order_idx')->result_array();
        //print_r( $result );die;
        //echo count( $result );die;
    
//写入订单数据------------------------------
        $clear_array1= $clear_array2= $clear_array3= array();
        $sum_total= $sum_count= $sum_qty= array();
        $sum_total_arr= $sum_count_arr= $sum_qty_arr= array();
        $settlement_arr= $this->get_settle_label();
        foreach ($result as $k=>$v){
            if( empty($v['create_time']) ) continue;
            
            $member= substr( $v['create_time'], 0, 10 );  //日期形式：2016-04-02
            //$member= date('Ymd', strtotime($v['create_time']) );  //数字形式：20160402
    
            //分布到各个公众号========
            $total_key= $this->redis_token_key($v['inter_id'], self::K_SALE_TOTAL );
            $count_key= $this->redis_token_key($v['inter_id'], self::K_SALE_COUNT );
            $qty_key= $this->redis_token_key($v['inter_id'], self::K_SALE_QTY );
            if( $start && !in_array($v['inter_id'], $clear_array1 ) ){
                //更新当日数据前要先清空该日数据
                $redis->zDelete($total_key, $member);
                $redis->zDelete($count_key, $member);
                $redis->zDelete($qty_key, $member);
                $clear_array1[]= $v['inter_id'];
            }
            $redis->zIncrBy($total_key, $v['real_grand_total'], $member);
            $redis->zIncrBy($count_key, 1, $member);
            $redis->zIncrBy($qty_key, $v['row_qty'], $member);

            //分布到各个公众号酒店的销售数据========
            //if(!empty($v['hotel_id'])){
            if( true ){
                $hote_key= '_'. $v['hotel_id'];
                $total_key= $this->redis_token_key($v['inter_id']. $hote_key, self::K_SALE_TOTAL );
                $count_key= $this->redis_token_key($v['inter_id']. $hote_key, self::K_SALE_COUNT );
                $qty_key= $this->redis_token_key($v['inter_id']. $hote_key, self::K_SALE_QTY );
                if( $start && !in_array($v['inter_id']. $hote_key, $clear_array3 ) ){
                    //更新当日数据前要先清空该日数据
                    $redis->zDelete($total_key, $member);
                    $redis->zDelete($count_key, $member);
                    $redis->zDelete($qty_key, $member);
                    $clear_array3[]= $v['inter_id']. $hote_key;
                }
                $redis->zIncrBy($total_key, $v['real_grand_total'], $member);
                $redis->zIncrBy($count_key, 1, $member);
                $redis->zIncrBy($qty_key, $v['row_qty'], $member);
            }
            
            //分布到各个公众号的业务细分数据========
            if( true ){
                $stl_key= '_'. strtoupper($v['settlement']);
                $total_key= $this->redis_token_key($v['inter_id'], self::K_SALE_TOTAL, $stl_key );
                $count_key= $this->redis_token_key($v['inter_id'], self::K_SALE_COUNT, $stl_key );
                $qty_key= $this->redis_token_key($v['inter_id'], self::K_SALE_QTY, $stl_key );
                if( $start && !in_array($v['inter_id']. $stl_key, $clear_array2 ) ){
                    //更新当日数据前要先清空该日数据
                    $redis->zDelete($total_key, $member);
                    $redis->zDelete($count_key, $member);
                    $redis->zDelete($qty_key, $member);
                    $clear_array2[]= $v['inter_id']. $stl_key;
                }
                $redis->zIncrBy($total_key, $v['real_grand_total'], $member);
                $redis->zIncrBy($count_key, 1, $member);
                $redis->zIncrBy($qty_key, $v['row_qty'], $member);
            }

            //累加汇总部分========
            if( isset($sum_total[$member]) ){
                $sum_total[$member] += $v['real_grand_total'];
                $sum_count[$member] += 1;
                $sum_qty[$member] += $v['row_qty'];
            } else {
                $sum_total[$member] = $v['real_grand_total'];
                $sum_count[$member] = 1;
                $sum_qty[$member] = $v['row_qty'];
            }

            //累加汇总的结算类型========
            foreach ($settlement_arr as $pk=> $pv){
                if( $v['settlement']==$pk ){
                    if( !isset($sum_total_arr[$pk]) ) {
                        $sum_total_arr[$pk]= $sum_count_arr[$pk]= $sum_qty_arr[$pk]= array();
                    }
                    if( isset($sum_total_arr[$pk][$member]) ){
                        $sum_total_arr[$pk][$member] += $v['real_grand_total'];
                        $sum_count_arr[$pk][$member] += 1;
                        $sum_qty_arr[$pk][$member] += $v['row_qty'];
                    } else {
                        $sum_total_arr[$pk][$member] = $v['real_grand_total'];
                        $sum_count_arr[$pk][$member] = 1;
                        $sum_qty_arr[$pk][$member] = $v['row_qty'];
                    }
                }
            }
        }
        //所有公众号汇总部分========
        if( true ){
            $member= date('Y-m-d');  //今天的日期
            $total_key= $this->redis_token_key('ALL', self::K_SALE_TOTAL );
            $count_key= $this->redis_token_key('ALL', self::K_SALE_COUNT );
            $qty_key= $this->redis_token_key('ALL', self::K_SALE_QTY );
            if( $start ){
                //更新当日数据前要先清空该日数据
                $redis->zDelete($total_key, $member);
                $redis->zDelete($count_key, $member);
                $redis->zDelete($qty_key, $member);
            }
            foreach ($sum_total as $k=>$v){
                $redis->zIncrBy($total_key, $sum_total[$k], $k);
                $redis->zIncrBy($count_key, $sum_count[$k], $k);
                $redis->zIncrBy($qty_key, $sum_qty[$k], $k);
            }
        }
        //所有公众号汇总部分========
        if( true ){
            foreach ($settlement_arr as $pk=> $pv){
                $member= date('Y-m-d');  //今天的日期
                $stl_key= '_'. strtoupper($pk);
                $total_key= $this->redis_token_key('ALL', self::K_SALE_TOTAL, $stl_key );
                $count_key= $this->redis_token_key('ALL', self::K_SALE_COUNT, $stl_key );
                $qty_key= $this->redis_token_key('ALL', self::K_SALE_QTY, $stl_key );
                if( $start ){
                    //更新当日数据前要先清空该日数据
                    $redis->zDelete($total_key, $member);
                    $redis->zDelete($count_key, $member);
                    $redis->zDelete($qty_key, $member);
                }
                if( isset($sum_total_arr[$pk]) ){
                    foreach ($sum_total_arr[$pk] as $k=>$v){
                        $redis->zIncrBy($total_key, $v, $k);
                    }
                }
                if( isset($sum_count_arr[$pk]) ){
                    foreach ($sum_count_arr[$pk] as $k=>$v){
                        $redis->zIncrBy($count_key, $v, $k);
                    }
                }
                if( isset($sum_qty_arr[$pk]) ){
                    foreach ($sum_qty_arr[$pk] as $k=>$v){
                        $redis->zIncrBy($qty_key, $v, $k);
                    }
                }
            }
        }

//写入消费数据------------------------------
        $shards= $this->_db()->get('soma_shard')->result_array();
        $consum_item= array();
        $clear_array= array();
        foreach ($shards as $sk=>$sv){
            $this->_db($sv['db_resource'])->where('consumer_time >', $last_time )
            ->where('consumer_time <', $end_time )
            //->where('status', xxx )
            ;
            foreach ($c_filter as $sk=>$sv){
                if( is_array($sv) ) $this->_db($sv['db_resource'])->where_in($sk, $sv);
                else $this->_db($sv['db_resource'])->where($sk, $sv);
            }
            $consum= $this->_db($sv['db_resource'])->get('soma_consumer_order'. $sv['table_suffix'])->result_array();
    
            //写入订单总额
            foreach ($consum as $k=>$v){
                $member= substr( $v['consumer_time'], 0, 10 );  //日期形式：2016-04-02
                //$member= date('Ymd', strtotime($v['create_time']) );  //数字形式：20160402
    
                //分布到各个公众号
                $cq_key= $this->redis_token_key($v['inter_id'], self::K_CONSUME_QTY );
                if( $start && !in_array($v['inter_id'], $clear_array ) ){
                    //更新当日数据前要先清空该日数据
                    $redis->zDelete($cq_key, $member);
                    $clear_array[]= $v['inter_id'];
                }
                $redis->zIncrBy($cq_key, $v['row_qty'], $member);
    
                //所有公众号汇总部分========
                $cq_key= $this->redis_token_key('ALL', self::K_CONSUME_QTY );
                if( $k==0 && $start ){
                    //更新当日数据前要先清空该日数据
                    $redis->zDelete($cq_key, $member);
                }
                $redis->zIncrBy($cq_key, $v['row_qty'], $member);
            }
        }
        // $redis->close();
        return TRUE;
    }
    
    /**
     * 从汇总数据中查取对应条件的统计数据
     *   eg: $this->Statis_sales_model->init_service();
     *       $this->Statis_sales_model->get_sales_data('a429262687', 1, 0, 0.2, '2016-04-22', '2016-05-22', FALSE);
     * @param String $inter_id  'ALL' 为汇总信息
     * @param number $key   1|2|3|4 对应四类不同数据
     * @param string $start   筛选开始时间
     * @param string $end   筛选结束时间
     * @param string $sort   是否正序排列
     * @param number $min   筛选最小值
     * @param number $max   筛选最小值
     * @param string $stl   settlement:结算方式
     * @return Ambigous <multitype:, multitype:unknown >
     */
    public function get_sales_data($inter_id, $key=3, $start=NULL, $end=NULL, $sort=TRUE, $min=0, $max=10000000, $stl='')
    {
        $redis= $this->redis;
        
        switch ($key){
            case self::K_SALE_TOTAL:
                $total_key= $this->redis_token_key($inter_id, self::K_SALE_TOTAL, $stl );
                $array= $redis->zRangeByScore($total_key, $min, $max, array('withscores' => TRUE) );
                break;
            case self::K_SALE_COUNT:
                $count_key= $this->redis_token_key($inter_id, self::K_SALE_COUNT, $stl );
                $array= $redis->zRangeByScore($count_key, $min, $max, array('withscores' => TRUE) );
                break;
            case self::K_SALE_QTY:
                $qty_key= $this->redis_token_key($inter_id, self::K_SALE_QTY, $stl );
                $array= $redis->zRangeByScore($qty_key, $min, $max, array('withscores' => TRUE) );
                break;
            case self::K_CONSUME_QTY:
                $cq_key= $this->redis_token_key($inter_id, self::K_CONSUME_QTY, $stl );
                $array= $redis->zRangeByScore($cq_key, $min, $max, array('withscores' => TRUE) );
                break;
        }

        $return = array();
        if(count($array)){
            ksort($array);
            //print_r($array);
            if($start==NULL) $start= date('Y-m-d', strtotime('-30 days'));
            if($end==NULL) $end= date('Y-m-d');
            
            // $array 部分日期可能缺失，即当日没有数据
            foreach ($array as $k=>$v){
                if( $k>=$start && $k<=$end ) {
                    $return[$k]= $v;
                }
            }
            // 生成连续的时间数组，填补返回数据中的空缺日期
            if($start && $end){
                $date_array= array();
                while ($start != $end) {
                    $start= date('Y-m-d', strtotime($start)+ 86400);
                    $date_array[$start]= 0;  //初始值
                }
                foreach ($date_array as $k=> $v){
                    if( !isset($return[$k]) && isset($date_array[$k]) ) 
                        $return[$k]= $date_array[$k];
                }
            }
            
            if($sort==FALSE) krsort($return);
            else ksort($return);
        }
        return $return;
    }

    /**
     * 清空缓存key（key在不断增加中，为保障一致性先关闭此函数）
     * @param string $key
     * @return boolean
     */
    public function flush_sales_data( $key=NULL )
    {
        return FALSE;
        
        $redis= $this->redis;
        $this->load->model('wx/Publics_model');
        $publics= $this->Publics_model->get_public_hash();
        if($redis && count($publics)>0 ){
            foreach ($publics as $k=>$v){
				if($key==NULL) {
					$redis->delete( $this->redis_token_key($v['inter_id'], self::K_SALE_TOTAL ) );
					$redis->delete( $this->redis_token_key($v['inter_id'], self::K_SALE_COUNT ) );
					$redis->delete( $this->redis_token_key($v['inter_id'], self::K_SALE_QTY ) );
					$redis->delete( $this->redis_token_key($v['inter_id'], self::K_CONSUME_QTY ) );
				} else {
					$redis->delete( $this->redis_token_key($v['inter_id'], $key ) );
				}
            }
			if($key==NULL) {
				$redis->delete( $this->redis_token_key('ALL', self::K_SALE_TOTAL ) );
				$redis->delete( $this->redis_token_key('ALL', self::K_SALE_COUNT ) );
				$redis->delete( $this->redis_token_key('ALL', self::K_SALE_QTY ) );
				$redis->delete( $this->redis_token_key('ALL', self::K_CONSUME_QTY ) );
			} else {
				$redis->delete( $this->redis_token_key('ALL', $key ) );
			}
            return TRUE;
            
        } else {
            return FALSE;
        }
    }

    public function get_live_sales_data($inter_id, $s_time) {
        $this->init_service();

        $this->load->model('wx/publics_model', 'p_model');
        $publics = $this->p_model->get_public_hash();

        $sales_data = array();
        foreach ($publics as $row) {
            $data = $this->get_sales_data($row['inter_id'], self::K_SALE_TOTAL, $s_time);
            $sales_data[$row['inter_id']] = 0;
            foreach ($data as $date => $sales_total) {
                $sales_data[$row['inter_id']] += $sales_total;
            }
        }
        // 销售额排序
        arsort($sales_data);

        // 设置排名
        $last_value = -1;
        $last_sort = $cnt = 0;

        $ret_data = array();
        foreach($sales_data as $key => $value) {
            $cnt++;

            if($value <= 0) { continue; }

            if(count($ret_data) == 0) {
                $ret_data[$key] = array('rank' => '第1名', 'sales' => $value);
                $last_value = $value;
                continue;
            }

            if($last_value != $value) {
                $last_value = $value;
                $last_sort = $cnt;
            }

            $rank = '第' . $last_sort . '名';
            if($last_sort > 5) {
                $rank = '前' . ceil($last_sort / 10.0) * 10 . '名';
            }
            $ret_data[$key] = array('rank' => $rank, 'sales' => $value);
        }

        return isset($ret_data[$inter_id]) ? $ret_data[$inter_id] : array('rank' => '暂无排名', 'sales' => 0);
    }
}
