<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Follower_report_model extends MY_Model {

	public function get_resource_name()
	{
		return '粉丝平台';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public function _shard_db($inter_id=NULL)
    {
        return $this->_db();
    }

    public function _shard_table($basename, $inter_id=NULL )
    {
        return $basename;
    }

	/**
	 * @return string the associated database table name
	 */
	public function table_name()
	{
		return 'fans';
	}

	//获取数据
	public function get_follower_data($inter_id = '',$date = 1){
		//ini_set ( 'memory_limit', '512M' );
		$start_time = $end_time = '';
		if($date == 1){
			$start_time = strtotime(date('Y-m-d 00:00:00',strtotime("-1 days")));//昨天
			$end_time = strtotime(date('Y-m-d 23:59:59',strtotime("-1 days")));
		}else{
			$start_time = strtotime(date('Y-m-d 00:00:00',strtotime("-2 days")));//前天
			$end_time = strtotime(date('Y-m-d 23:59:59',strtotime("-2 days")));
		}

		if(!empty($inter_id)){
			$sql = "select * from iwide_fans_change_data where inter_id = '{$inter_id}' and check_time >= {$start_time} and check_time < {$end_time}";
		}else{
			$sql = "select all_fans_count,sum(new_sub_count) as new_sub_count,sum(new_unsub_count) as new_unsub_count,sum(new_add_count) as new_add_count,sum(saler_nums) as saler_nums,sum(all_sub_count) as all_sub_count,(sum(saler_nums)/sum(new_sub_count)) as saler_rate,all_publics from iwide_fans_change_data where check_time >= {$start_time} and check_time < {$end_time}";
		}
		$query = $this->_db('iwide_r1')->query($sql)->result_array();
		return isset($query[0]) && !empty($query[0]) ? $query[0] : array();
	}

	//获取数据 对应highchar
	public function get_ajax_data($inter_id = '',$date = 1)
	{
		ini_set('memory_limit', '512M');
		$start_time = $end_time = '';

		$return =  $data =array();
		$where = "  inter_id = '{$inter_id}'";
		$new_sub = array();
		$new_unsub = array();
		$new_add = array();
		$date_arr = array();
		if ($date == 1) {//昨天数据
			$start_time = strtotime(date('Y-m-d 00:00:00', strtotime("-1 days")));
			$end_time = strtotime(date('Y-m-d 00:00:00', time()));
			if(!empty($inter_id)){
				$sql = "select hour_data from iwide_fans_change_data where " . $where . " and check_time >= {$start_time} and check_time < {$end_time}";
				$query = $this->_db('iwide_r1')->query($sql)->row();
				$data = !empty($query->hour_data)?unserialize($query->hour_data):array();
			}else{
				$sql = "select hour_data from iwide_fans_change_data where  check_time >= {$start_time} and check_time < {$end_time}";
				$query = $this->_db('iwide_r1')->query($sql)->result_array();
				if(!empty($query)){
					$data_arr = array('new_sub'=>array(),'new_unsub'=>array(),'new_add'=>array());
					foreach($query as $k=>$v){
						$tmp = !empty($v['hour_data'])?unserialize($v['hour_data']):'';
						if(is_array($tmp)){
							foreach($tmp[0]['new_sub'] as $key1 => $val1){
								if(isset($data_arr['new_sub'][$key1])){
									$data_arr['new_sub'][$key1] += $val1;
								}else{
									$data_arr['new_sub'][$key1] = $val1;
								}
							}
							foreach($tmp[0]['new_unsub'] as $key2 => $val2){
								if(isset($data_arr['new_unsub'][$key2])){
									$data_arr['new_unsub'][$key2] += $val2;
								}else{
									$data_arr['new_unsub'][$key2] = $val2;
								}
							}
							foreach($tmp[0]['new_add'] as $key3 => $val3){
								if(isset($data_arr['new_add'][$key3])){
									$data_arr['new_add'][$key3] += $val3;
								}else{
									$data_arr['new_add'][$key3] = $val3;
								}

							}
						}
					}
					$data[0] = $data_arr;
				}
			}

			for ($i = 0; $i < 24; $i++) {
				$i = str_pad($i, 2, '0', STR_PAD_LEFT);
				$date_arr[] = $i . ":00";
			}
			$data[0]['date'] = $date_arr;
			return $data;
		} else {//大于一天的按天计算
			for ($date; $date > 0; $date--) {
				$date_arr[] = date('Y-m-d', strtotime("-{$date} days"));;
				$start = strtotime(date('Y-m-d 00:00:00', strtotime("-{$date} days")));
				$end = strtotime(date('Y-m-d 23:59:59', strtotime("-{$date} days")));
				if(!empty($inter_id)){
					$sql = "select new_sub_count,new_unsub_count from iwide_fans_change_data where " . $where . "  and check_time >= {$start} and check_time < {$end}";
				}else{
					$sql = "select sum(new_sub_count) as new_sub_count,sum(new_unsub_count) as new_unsub_count from iwide_fans_change_data where check_time >= {$start} and check_time < {$end}";
				}
				$query = $this->_db('iwide_r1')->query($sql)->result_array();//var_dump($query);die;
				$new_sub_count = isset($query[0]['new_sub_count']) ? $query[0]['new_sub_count'] : 0;
				$new_unsub_count = isset($query[0]['new_unsub_count']) ? $query[0]['new_unsub_count'] : 0;
				$new_sub[] = (int)$new_sub_count;
				$new_unsub[] = (int)$new_unsub_count;
				$new_add[] = $new_sub_count - $new_unsub_count;
			}
			$data[] = array('date' => $date_arr, 'new_sub' => $new_sub, 'new_unsub' => $new_unsub, 'new_add' => $new_add);
			return $data;
		}
	}
	//获取记录数
	public function get_data_count_by_filter($filter = array()){
		$sql = "select count(distinct(inter_id)) as cc from iwide_publics where status=0 and inter_id != '' ";
		if(!empty($filter['inter_id'])){
			$sql .= " and inter_id = '" .$filter['inter_id']."' ";
		}
		if(!empty($filter['hotel_public'])){
			$sql .= " and inter_id in ('".implode("','",$filter['hotel_public']) . "')";
		}
		$sql .= "  ORDER BY inter_id desc ";
		$query = $this->_db ( 'iwide_r1' )->query($sql)->row();
		return $query->cc>0?$query->cc:0;
	}

	//获取记录
	public function get_data_by_filter($filter = array(),$limit=NULL,$offset=0){
		ini_set ( 'memory_limit', '512M' );
		$return  = array();
		//$sql = "select * from iwide_fans_change_data where 1 = 1  ";
		$sql = "select public_name,inter_id, sum(new_sub_count) as new_sub_count,sum(new_unsub_count) as new_unsub_count,sum(new_add_count) as new_add_count,sum(saler_nums) as saler_nums,all_sub_count from iwide_fans_change_data where 1 =1 ";
		if(!empty($filter['inter_id'])){
			$sql .= " and inter_id = '" .$filter['inter_id']."' ";
		}
		if(!empty($filter['hotel_public'])){
			$sql .= " and inter_id in ('".implode("','",$filter['hotel_public']) . "')";
		}
		//时间m默认昨天
		$start = date('Y-m-d 00:00:00',strtotime("-1 days"));//昨天
		$end = date('Y-m-d 23:59:59',strtotime("-1 days"));
		if(!empty($filter['start_time'])){
			$start = $filter['start_time']." 00:00:00";
		}
		if(!empty($filter['end_time'])){
			$end = $filter['end_time']." 23:59:59";
		}
		$start = strtotime($start);
		$end = strtotime($end);
		$sql .= " and check_time >= " . $start . " and check_time < " . $end;
		$sql .= " group by inter_id ";
		$sql .= "  ORDER BY inter_id desc ";
		$argvs = array();
		/*if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = $limit;
		}*///echo $sql;
		$data = array();
		$return = $this->_db('iwide_r1')->query($sql,$argvs)->result_array();//var_dump($return);die;
		if(isset($filter['count'])){
			return count($return);
		}
		if(!empty($return)) {
			foreach($return as $k=>$v){
				$data[$v['inter_id']] = $v;
			}
		}//ar_dump($data);die;
		unset($return);

			uasort ( $data, function ($a, $b) {
				return $a ['new_sub_count'] > $b ['new_sub_count'] ? - 1 : 1;
			} );
			$i = 1;
			$tmp = array ();
			foreach ( $data as $rk => $rv ) {
				if (! empty ( $rv ['inter_id'] )) {
					if (empty ( $tmp )) {
						$tmp = $rv;
					}
					if ($tmp ['new_sub_count'] != $rv ['new_sub_count']) {
						$i ++;
					}
					$data [$rk] ['new_sub_sort'] = $i;
					$tmp = $rv;
				}
			}
			if (! empty ( $filter ['inter_id'] )) {
				$result = array ();
				if (! empty ( $data [$filter ['inter_id']] )) {
					$result [$filter ['inter_id']] = $data [$filter ['inter_id']];
				}
				return $result;
			}
			if(!empty($filter['hotel_public'])){
				$result = array ();
				foreach($filter['hotel_public'] as $hotel_k=>$hotel_v){
					if(!empty($data[$hotel_v])){
						$result [$hotel_v] = $data [$hotel_v];
					}
				}
				return $result;
			}

			//unset($return);
			if (! is_null ( $limit )) {
				return array_slice ( $data, $offset, $limit );
			}
			return $data;
			//return $return;
		}


	//获取所有的公众号
	public function get_all_wx_public($inter_id = ''){
		if(empty($inter_id)){//循环脚本用到
			$sql = "select * from iwide_publics where status = 0 and inter_id != '' group by inter_id ";
		}else{
			if($inter_id == FULL_ACCESS){
				$sql = "select * from iwide_publics where status = 0 and inter_id != '' group by inter_id ";
			}else{
				$sql = "select * from iwide_publics where status = 0 and inter_id = '{$inter_id}' group by inter_id ";
			}
		}
		$query = $this->_db('iwide_r1')->query($sql)->result_array();
		return $query;
	}
	//获取平台所有粉丝量 和 所有公众号总和 截止什么时候
	public function get_all_fans_count($time=0){
		if(empty($time)){//默认截止到昨天23 59 59（00 00 00）
			$time = date('Y-m-d 00:00:00',time());
		}
		//获取总粉丝数
		$sql = "select count(*) as all_fans_count from iwide_fans where subscribe_time < '{$time}'";//echo $sql;die;
		$query = $this->_db ( 'iwide_r1' )->query($sql)->row();
		$all_fans_count = $query->all_fans_count?$query->all_fans_count:0;//平台所有粉丝数
		//在线酒店总和
		$sql = "select count(DISTINCT(inter_id)) as inter_count from iwide_publics where status = 0 and create_time < '{$time}' ";
		$query = $this->_db ( 'iwide_r1' )->query($sql)->row();
		$inter_count = $query->inter_count?$query->inter_count:0;
		return array('all_fans_count'=>$all_fans_count,'inter_count'=>$inter_count);
	}
	//处理数据
	public function update_every_day_data($inter_id = '',$data = array(),$start_time = 0,$end_time = 0){//var_dump($data);die;
		if(empty($start_time)){//默认昨天
			$start_time = date('Y-m-d 00:00:00',strtotime('-1 days'));
		}
		if(empty($end_time)){//默认今天凌晨
			$end_time = date('Y-m-d 00:00:00',time());
		}
		//先查询 根据时间和inter_id查询，没有的再插入
		$sql = "select count(*) as c from iwide_fans_change_data where inter_id = '{$inter_id}' and check_time = " . strtotime($start_time);
		$query = $query = $this->_db('iwide_r1')->query($sql)->row();
		if($query->c>0){
			$log = '数据已经插入，时间为：'.date('Y-m-d H:i:s').'| 数据：'.$inter_id.' | '.$start_time;
			$this->write_log($log);
			return true;
		}
		$return = array();
		//$sql = "select count(distinct(openid)) as new_sub_count from iwide_fans_sub_log where inter_id = '{$inter_id}' and event = 2 and event_time >='{$start_time}' and event_time < '{$end_time}' ";//公众号新关注
		$sql = "select distinct(openid),event_time from iwide_fans_sub_log where inter_id = '{$inter_id}' and event = 2 and source >= -1 and event_time >='{$start_time}' and event_time < '{$end_time}' ";//公众号新关注
		$new_sub = $this->_db ( 'iwide_r1' )->query($sql)->result_array();
		$return['new_sub_count'] = count($new_sub);
		$sql = "select distinct(openid),event_time from iwide_fans_sub_log where inter_id = '{$inter_id}' and event = 1  and event_time >='{$start_time}' and event_time < '{$end_time}' ";//公众号新取消关注
		$new_unsub = $this->_db ( 'iwide_r1' )->query($sql)->result_array();
		$return['new_unsub_count'] = count($new_unsub);
		$return['new_add_count'] = $return['new_sub_count']-$return['new_unsub_count'];
		$sql = "select count(distinct(openid)) as saler_nums from iwide_fans_subs where inter_id = '{$inter_id}' and cur_status = 1  and source > 0 and event_time >='{$start_time}' and event_time < '{$end_time}' ";
		$query = $this->_db ( 'iwide_r1' )->query($sql)->row();
		$return['saler_nums'] = $query->saler_nums?$query->saler_nums:0;
		//累计关注数
		//直到指定时间前的粉丝数
		$sql = "select count(openid) as all_sub from iwide_fans where inter_id = '{$inter_id}' and subscribe_time < '{$end_time}'";//粉丝总数=fans表的数-取消关注数 包括曾经关注过
		$query = $this->_db ( 'iwide_r1' )->query($sql)->row();
		$all_sub = $query->all_sub?$query->all_sub:0;
		//直到指定时间前的取消关注数
		$sql = "select count(distinct(openid)) as all_unsub from iwide_fans_sub_log where inter_id = '{$inter_id}' and event = 1 and event_time < '{$end_time}'";
		$query = $this->_db ( 'iwide_r1' )->query($sql)->row();
		$all_unsub = $query->all_unsub?$query->all_unsub:0;
        //取关注数
        $sql = "SELECT count(DISTINCT openid) subs_num from iwide_fans_subs where inter_id = '{$inter_id}' and cur_status = 1 and  event_time < '{$end_time}'";
        $query = $this->_db ( 'iwide_r1' )->query($sql)->row();
        $subs_num = $query->subs_num?$query->subs_num:0;
		$return['all_sub_count'] = $subs_num;//$all_sub-$all_unsub;//累计粉丝数
		$return['saler_rate'] = $return['new_sub_count']==0?0:$return['saler_nums']/$return['new_sub_count'];//分销占比
		$return['inter_id'] = $inter_id;
		$return['public_name'] = $data['public_name'];
		$return['all_fans_count'] = $data['all_fans_count'];//平台粉丝数
		$return['all_publics'] = $data['inter_count'];//公众号总和
		$return['check_time'] = strtotime($start_time);//var_dump($return);die;
		$return['hour_data'] = $this->update_hour_data_by_inter_id($inter_id,$new_sub,$new_unsub);
		return $this->db->insert('iwide_fans_change_data',$return);

	}
	//根据小时获取对应的新增关注 取消关注
	public function update_hour_data_by_inter_id($inter_id = '',$new_sub = array(),$new_unsub = array()){
		$return = array();
		$new_sub_arr = $new_unsub_arr = $new_add_arr = array();
		$arr = array();
		$date_arr = array();
		$time = 0;
		if(empty($time)){//默认昨天
			$day = date('Y-m-d',strtotime("-1 days"));
		}
		for($i = 0;$i < 24;$i++){
			$sub_count = $unsub_count = $add_count = 0 ;
			$i = str_pad($i,2,'0',STR_PAD_LEFT);
			$date_arr[] = $i.":00";
			$start = $day." {$i}:00:00";
			$end = $day." {$i}:59:59";
			if(!empty($new_sub)){//新关注数组
				foreach($new_sub as $sk=>$sv){
					if($sv['event_time'] >= $start && $sv['event_time'] < $end){
						$sub_count ++;
					}
				}
			}
			$new_sub_arr[] = $sub_count;
			if(!empty($new_unsub)){//取消关注数组
				foreach($new_unsub as $usk=>$usv){
					if($usv['event_time'] >= $start && $usv['event_time'] < $end){
						$unsub_count ++;
					}
				}
			}
			$new_unsub_arr[] = $unsub_count;
			//净增
			$new_add_arr[] = $sub_count - $unsub_count;
			// = $add_count;
			//$return[] = $arr;
		}
		$arr = array(/*'date'=>$date_arr,*/'new_sub'=>$new_sub_arr,'new_unsub'=>$new_unsub_arr,'new_add'=>$new_add_arr);
		$return[] = $arr;
		$data = serialize($return);
		unset($return);unset($arr);
		//$this->db->update('iwide_fans_change_data',$data,array('id'=>$insert_id,'inter_id'=>$inter_id));die;
		return $data;
		//return $return;

	}
	public function write_log( $content = '',$data = '' )
	{
		$file= date('Y-m-d'). '.txt';
		//echo $tmpfile;die;
		$path= APPPATH.'logs'.DS. 'auto_group'. DS;

		if( !file_exists($path) ) {
			@mkdir($path, 0777, TRUE);
		}
		if(is_array($data)){
			$data = json_encode($data);
		}

		$fp = fopen($path.$file, "a");
		//echo __FILE__
		$content = date("Y-m-d H:i:s")." | ".$_SERVER['PHP_SELF']." | ".$content." | ".$data."\n";

		fwrite($fp, $content);
		fclose($fp);
	}
	//获取分销分析数据 记录数
	public function get_distribure_analys_count($filter = array()){
		ini_set ( 'memory_limit', '1024M' );
		$sql = "SELECT count(*) as c FROM (SELECT hs.`name`,hs.hotel_name,hs.qrcode_id
				 FROM iwide_hotel_staff hs LEFT JOIN iwide_distribute_grade_all g
				ON hs.inter_id=g.inter_id AND hs.qrcode_id=g.saler
				WHERE hs.inter_id=? AND hs.is_distributed=1  AND (g.status=1 OR g.status=2) AND hs.qrcode_id>0 ";// GROUP BY hs.qrcode_id
		//ORDER BY GRADE_TOTAL DESC) a ORDER BY rank limit 50,100";
		$params[] = $filter['inter_id'];
		if(!empty($filter['start_time'])){
			$sql .= " AND g.order_time >= ?";
			$params[] = $filter['start_time'];
		}
		if(!empty($filter['end_time'])){
			$sql .= " AND g.order_time < ?";
			$params[] = $filter['end_time'] . " 23:59:59 ";
		}

		if(!empty($filter['saler_id'])){
			$sql .= " AND hs.qrcode_id=? ";
			$params [] = $filter['saler_id'];
		}
		if (! empty ( $filter['saler_name'] )) {
			$sql .= " AND hs.name LIKE ? ";
			$params [] = '%' . $filter['saler_name'] . '%';
		}
		$sql .=  " GROUP BY hs.qrcode_id ) a ";

		$query = $this->_db ( 'iwide_r1' )->query ( $sql, $params )->row();
		return !empty($query->c)?$query->c:0;
	}

	//获取分销分析数据
	public function get_distribure_analys_info($filter = array(),$limit=NULL,$offset=0){
		$sql = "SELECT a.*,@rank:=@rank+1 rank FROM (SELECT @rank:=0,hs.`name`,hs.hotel_name,hs.qrcode_id,
				SUM(IFNULL(g.grade_total,0)) 'GRADE_TOTAL',
				SUM(IF(g.`grade_table`='iwide_member_additional',g.grade_total,0)) 'GRADE_MEM',
				SUM(IF(g.`grade_table`='iwide_member_additional',1,0)) 'mem_count'
				 FROM iwide_hotel_staff hs LEFT JOIN iwide_distribute_grade_all g
				ON hs.inter_id=g.inter_id AND hs.qrcode_id=g.saler
				WHERE hs.inter_id=? AND hs.is_distributed=1 AND hs.qrcode_id>0  AND (g.status=1 OR g.status=2) ";// GROUP BY hs.qrcode_id
		//ORDER BY GRADE_TOTAL DESC) a ORDER BY rank limit 50,100";
		$params[] = $filter['inter_id'];
		if(!empty($filter['start_time'])){
			$sql .= " AND g.order_time >= ?";
			$params[] = $filter['start_time'];
		}
		if(!empty($filter['end_time'])){
			$sql .= " AND g.order_time < ?";
			$params[] = $filter['end_time'] . " 23:59:59 ";
		}
		$sql .=  " GROUP BY hs.qrcode_id ORDER BY GRADE_TOTAL DESC) a ";
		if(!empty($filter['saler_id']) || !empty($filter['saler_name'])){
			$sql .= 'HAVING 1';
		}
		if(!empty($filter['saler_id'])){
			$sql .= " AND qrcode_id=? ";
			$params [] = $filter['saler_id'];
		}
		if (! empty ( $filter['saler_name'] )) {
			$sql .= " AND name LIKE ? ";
			$params [] = '%' . $filter['saler_name'] . '%';
		}
		$sql .= " ORDER BY rank";
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$params[] = $offset;
			$params[] = $limit;
		}
		$query = $this->_db ( 'iwide_r1' )->query ( $sql, $params )->result_array();
		$data = array();
		if(!empty($query)){
			$saler_ids = array_column($query,'qrcode_id');
			foreach($query as $k=>$v){
				$data[$v['qrcode_id']] = $v;
			}
			unset($query);
			//获取粉丝数
			$sql = "SELECT COUNT(*) fans_count,source FROM iwide_fans_subs WHERE  inter_id='{$filter['inter_id']}'   AND source
>0 AND source in (".implode(',',$saler_ids) . ") ";//  group by source";
            if(!empty($filter['start_time'])){
                $sql .= " AND event_time >= '{$filter['start_time']}'";
            }
            if(!empty($filter['end_time'])){
                $sql .= " AND event_time < '" . $filter['end_time'] . " 23:59:59' ";
            }
            $sql .= " group by source ";
			$fans_count = $this->_db ( 'iwide_r1' )->query ( $sql )->result_array();//var_dump($night_res);die;
			if(!empty($fans_count)){
				foreach($fans_count as $fan_k => $fan_v){
					if(array_key_exists($fan_v['source'],$data)){
						$data[$fan_v['source']]['fans_count'] = $fan_v['fans_count'];
					}
				}
				unset($fans_count);
			}
			//获取商品数和商品绩效
			$sql = "SELECT SUM(a.grade_total) gas,SUM(i.counts) ds,a.saler FROM iwide_distribute_grade_all a INNER JOIN iwide_mall_order_summary
i ON a.inter_id=i.inter_id AND a.grade_id=i.order_id AND (substr(a.grade_table ,1,10)='iwide_soma' OR a.grade_table
='iwide_shp_orders') WHERE a.saler > 0 AND (a.status = 1 or a.status = 2) ";
			$sql .= " and a.inter_id = '{$filter['inter_id']}' and a.saler in (".implode(',',$saler_ids) . ") ";
            if(!empty($filter['start_time'])){
                $sql .= " AND a.order_time >= '{$filter['start_time']}'";
            }
            if(!empty($filter['end_time'])){
                $sql .= " AND a.order_time < '" . $filter['end_time'] . " 23:59:59' ";
            }
			$sql .= " group by a.saler ";
			$product = $this->_db ( 'iwide_r1' )->query ( $sql )->result_array();//var_dump($night_res);die;
			if(!empty($product)){
				foreach($product as $pk=>$pv){
					if(array_key_exists($pv['saler'],$data)){
						$data[$pv['saler']]['product_count'] = $pv['ds'];
						$data[$pv['saler']]['product_grade'] = $pv['gas'];
					}
				}
				unset($product);
			}
			//获取间夜数 绩效
			$sql = "SELECT SUM(a.grade_total) gas,a.saler,SUM(IF(DATEDIFF(i.enddate,i.startdate)=0,1,DATEDIFF(i.enddate,i.startdate)))
room_night FROM iwide_distribute_grade_all a INNER JOIN iwide_hotel_order_items i ON a.inter_id=i.inter_id AND a.grade_id=i.id WHERE a.saler>0 AND a.grade_table='iwide_hotels_order' and (a.status = 1 or a.status = 2) ";
			$sql .= " and a.inter_id = '{$filter['inter_id']}' and a.saler in (".implode(',',$saler_ids) . ") ";
            if(!empty($filter['start_time'])){
                $sql .= " AND order_time >= '{$filter['start_time']}'";
            }
            if(!empty($filter['end_time'])){
                $sql .= " AND order_time < '" . $filter['end_time'] . " 23:59:59' ";
            }
			$sql .= " group by a.saler ";
			$night_res = $this->_db ( 'iwide_r1' )->query ( $sql )->result_array();//var_dump($night_res);die;
			if(!empty($night_res)){
				foreach($night_res as $nk=>$nv){
					if(array_key_exists($nv['saler'],$data)){
						$data[$nv['saler']]['room_night'] = $nv['room_night'];
						$data[$nv['saler']]['room_grade'] = $nv['gas'];
					}
				}
				unset($night_res);
			}
			//获取转化率
			//产生核定成功的粉丝数
            $sql = "select count(*) fans_count,source from (select a.* from iwide_fans_subs a left join iwide_distribute_grade_all b on b.inter_id = a.inter_id and b.grade_openid = a.openid where a.inter_id = '{$filter['inter_id']}' and a.source in (".implode(',',$saler_ids) . " ) and b.saler > 0 and  (b.status = 1 or b.status = 2) and b.grade_table != 'iwide_fans_sub_log' ";
            if(!empty($filter['start_time'])){
                $sql .= " AND b.order_time >= '{$filter['start_time']}'";
            }
            if(!empty($filter['end_time'])){
                $sql .= " AND b.order_time < '" . $filter['end_time'] . " 23:59:59' ";
            }
            $sql .= " group by a.openid) c group by source ";
            $success_fans = $this->_db ( 'iwide_r1' )->query ( $sql )->result_array();//var_dump($night_res);
			if(!empty($success_fans)){
				foreach($success_fans as $fk=>$fv){
					if(array_key_exists($fv['source'],$data)){
						$data[$fv['source']]['success_fans'] = $fv['fans_count'];
					}
				}
				unset($success_fans);
			}
			//获取产生过交易的粉丝从关注到产生第一笔交易的时间总和
			$sql = "select c.source,SUM(sum_time) sum_time from (select a.*,b.source,TIMESTAMPDIFF(MINUTE,b.event_time,a.order_time) sum_time from ((select saler,order_time,grade_openid,inter_id from iwide_distribute_grade_all where inter_id = '{$filter['inter_id']}' and (status = 1 or status = 2) and grade_table != 'iwide_fans_sub_log' and grade_table != 'iwide_member_additional' and saler > 0 and saler in (" . implode(',',$saler_ids) . ") ";
			//echo $sql;die;
            if(!empty($filter['start_time'])){
                $sql .= " AND order_time >= '{$filter['start_time']}'";
            }
            if(!empty($filter['end_time'])){
                $sql .= " AND order_time < '" . $filter['end_time'] . " 23:59:59' ";
            }
            $sql .= "  group by grade_openid order by grade_time asc) a inner join (SELECT event_time,openid,inter_id,source FROM iwide_fans_subs WHERE inter_id='{$filter['inter_id']}'  AND source in (" . implode(',',$saler_ids) . ") ) as b on a.inter_id = b.inter_id  and a.grade_openid = b.openid)) as c group by c.source ";
			$sum_time = $this->_db ( 'iwide_r1' )->query ( $sql )->result_array();
			if(!empty($sum_time)){
				foreach($sum_time as $sk=>$sv){
					if(array_key_exists($sv['source'],$data)){
						$data[$sv['source']]['sum_time'] = $sv['sum_time'];
					}
				}
			}

			//die;
		}

		return $data;
	}

	//计算发展粉丝人数、粉丝交易人数
	public function get_saler_fans_data($filter = array()){
		ini_set ( 'memory_limit', '512M' );
		//查粉丝人数
		$sql = "SELECT event_time,source,openid FROM iwide_fans_subs WHERE   source > 0 ";
		if(isset($filter['inter_id'])){
			$sql .= " AND inter_id = '{$filter['inter_id']}' ";
		}
		if(isset($filter['start_time'])){
			$sql .= " AND event_time >= '" . $filter['start_time']."'";
		}
		if(isset($filter['end_time'])){
			$sql .= " AND event_time < '" . $filter['end_time']." 23:59:59'";
		}
		$sql .= " group by openid";
		$dev_fans = $this->_db ( 'iwide_r1' )->query ( $sql )->result_array();//var_dump($night_res);die;

		//粉丝交易人数(不连fans_sub表 只查产生交易的粉丝)
		$sql = "select inter_id,grade_openid,saler,order_time from iwide_distribute_grade_all where inter_id = '{$filter['inter_id']}' and (status = 1 or status = 2) and grade_table != 'iwide_fans_sub_log' and grade_table != 'iwide_member_additional' and saler > 0  ";
		if(isset($filter['start_time'])){
			$sql .= " AND order_time >= '" . $filter['start_time']."'";
		}
		if(isset($filter['end_time'])){
			$sql .= " AND order_time < '" . $filter['end_time']." 23:59:59'";
		}
		$sql .= " group by grade_openid ";
		$sale_fans = $this->_db ( 'iwide_r1' )->query ( $sql )->result_array();//var_dump($night_res);die;
		//计算时间 天数
		$day = get_room_night($filter['start_time'],$filter['end_time']." 23:59:59",'ceil');//至少有1个间夜
		$dev_return = $sale_return = $tmp = $tmpp = $date_arr= array();
		for($day;$day > 0;$day--){
			$date = date('Y-m-d',strtotime($filter['end_time']." 23:59:59")-86400*($day-1));
			for($i = 0;$i < 24;$i++) {
				$i = str_pad($i, 2, '0', STR_PAD_LEFT);
				if($day == 1){
					$date_arr[] = $i . ":00";
				}
				$start = $date . " {$i}:00:00";
				$end = $date . " {$i}:59:59";
				if(!empty($dev_fans)){//发展粉丝数组
					foreach($dev_fans as $dk=>$dv){
						if($dv['event_time'] > $start && $dv['event_time'] < $end){
							$tmp[$i] = isset($tmp[$i])? ($tmp[$i]+ 1):1;
						}else{
							$tmp[$i] = isset($tmp[$i])? ($tmp[$i]+ 0):0;
						}
					}
					$dev_return = array_values($tmp);
				}
				if(!empty($sale_fans)){//交易人数数组
					foreach($sale_fans as $sk=>$sv){
						if($sv['order_time'] > $start && $sv['order_time'] < $end){
							$tmpp[$i] = isset($tmpp[$i])? ($tmpp[$i]+ 1):1;
						}else{
							$tmpp[$i] = isset($tmpp[$i])? ($tmpp[$i]+ 0):0;
						}
					}
					$sale_return = array_values($tmpp);
				}
			}
		}
		return array('dev'=>$dev_return,'sale'=>$sale_return,'date'=>$date_arr);

	}

	//获取转化情况
	public function get_transform_data($filter = array()){
        ini_set ( 'memory_limit', '512M' );
		//获取所有发展的粉丝数
		$sql = "select count(*) as dev_fans_count from iwide_fans_subs where inter_id  = '{$filter['inter_id']}'  and
source > 0 ";
		if(isset($filter['start_time'])){
			$sql .= " AND event_time >= '" . $filter['start_time']."'";
		}
		if(isset($filter['end_time'])){
			$sql .= " AND event_time < '" . $filter['end_time']." 23:59:59'";
		}
		$query = $this->_db ( 'iwide_r1' )->query ( $sql )->row();
		$dev_fans_count = $query->dev_fans_count?$query->dev_fans_count:0;
		//和 产生过交易粉丝数
		$sql = "select count(*) sale_fans_count from (select saler from iwide_distribute_grade_all where inter_id = '{$filter['inter_id']}' and (status = 1 or
status = 2) and grade_table != 'iwide_fans_sub_log' and grade_table != 'iwide_member_additional' and saler > 0 ";
		if(isset($filter['start_time'])){
			$sql .= " AND order_time >= '" . $filter['start_time']."' ";
		}
		if(isset($filter['end_time'])){
			$sql .= " AND order_time < '" . $filter['end_time']." 23:59:59' ";
		}
		$sql .= "  group by grade_openid order by grade_time asc) a ";
		$query = $this->_db ( 'iwide_r1' )->query ( $sql )->row();//var_dump($query->sale_fans_count);die;
		$sale_fans_count = $query->sale_fans_count?$query->sale_fans_count:0;
		//分销员发展的粉丝
        $saler_sql = " (select qrcode_id  from iwide_hotel_staff where inter_id = '{$filter['inter_id']}'
		and is_distributed = 1 and openid != '' and qrcode_id > 0) ";
		$sql = "select count(*) fans_from_saler from iwide_fans_subs where inter_id  = '{$filter['inter_id']}' and source > 0  and source in  ";
        $sql .= $saler_sql;
		if(isset($filter['start_time'])){
			$sql .= " AND event_time >= '" . $filter['start_time']."'";
		}
		if(isset($filter['end_time'])){
			$sql .= " AND event_time < '" . $filter['end_time']." 23:59:59'";
		}
		$query = $this->_db ( 'iwide_r1' )->query ( $sql )->row();//var_dump($night_res);die;
		$fans_from_saler = $query->fans_from_saler?$query->fans_from_saler:0;
		//分销员发展的产生交易粉丝数
		$sql = "select count(*) sale_fans_from_saler from (select saler from iwide_distribute_grade_all where inter_id = '{$filter['inter_id']}' and (status = 1 or
status = 2) and grade_table != 'iwide_fans_sub_log' and grade_table != 'iwide_member_additional' and saler > 0 and saler in ";
        $sql .= $saler_sql;
		if(isset($filter['start_time'])){
			$sql .= " AND order_time >= '" . $filter['start_time']."' ";
		}
		if(isset($filter['end_time'])){
			$sql .= " AND order_time < '" . $filter['end_time']." 23:59:59' ";
		}
        $sql .= " group by grade_openid  ) a";
        $query = $this->_db ( 'iwide_r1' )->query ( $sql )->row();//var_dump($night_res);die;
        $sale_fans_from_saler = $query->sale_fans_from_saler?$query->sale_fans_from_saler:0;
        //场景来源
        $saler_sql = " in (select qrcode_id  from iwide_hotel_staff where inter_id = '{$filter['inter_id']}'
		and is_distributed = 0 and openid = '' and qrcode_id > 0) ";
        $sql = "select count(*) fans_from_sence from iwide_fans_subs where inter_id  = '{$filter['inter_id']}' and source > 0 and source ";
        $sql .= $saler_sql;
        if(isset($filter['start_time'])){
            $sql .= " AND event_time >= '" . $filter['start_time']."'";
        }
        if(isset($filter['end_time'])){
            $sql .= " AND event_time < '" . $filter['end_time']." 23:59:59'";
        }
        $query = $this->_db ( 'iwide_r1' )->query ( $sql )->row();//var_dump($night_res);die;
        $fans_from_sence = $query->fans_from_sence?$query->fans_from_sence:0;
        //场景产生交易粉丝数
        $sql = "select count(*) sale_fans_from_sence from (select saler from iwide_distribute_grade_all where inter_id = '{$filter['inter_id']}' and (status = 1 or
status = 2) and grade_table != 'iwide_fans_sub_log' and grade_table != 'iwide_member_additional' and saler > 0 and saler ";
        $sql .= $saler_sql;
        if(isset($filter['start_time'])){
            $sql .= " AND order_time >= '" . $filter['start_time']."' ";
        }
        if(isset($filter['end_time'])){
            $sql .= " AND order_time < '" . $filter['end_time']." 23:59:59' ";
        }
        $sql .= " group by grade_openid  ) a";

        $query = $this->_db ( 'iwide_r1' )->query ( $sql )->row();//var_dump($night_res);die;
        $sale_fans_from_sence = $query->sale_fans_from_sence?$query->sale_fans_from_sence:0;
        //计算转化时间
        $sql = "select a.*,TIMESTAMPDIFF(MINUTE,b.event_time,a.order_time) sum_time from ((select saler,order_time,grade_openid,inter_id from iwide_distribute_grade_all where inter_id = '{$filter['inter_id']}' and (status = 1 or status = 2) and grade_table != 'iwide_fans_sub_log' and grade_table != 'iwide_member_additional' and saler > 0";
        if(isset($filter['start_time'])){
            $sql .= " AND order_time >= '" . $filter['start_time']."' ";
        }
        if(isset($filter['end_time'])){
            $sql .= " AND order_time < '" . $filter['end_time']." 23:59:59' ";
        }
        $sql .= "  group by grade_openid ) a inner join (SELECT event_time,openid,inter_id,source FROM iwide_fans_subs WHERE inter_id='{$filter['inter_id']}' AND source >0 ) as b on a.inter_id = b.inter_id  and a.grade_openid = b.openid) where a.saler > 0  ";
        $query = $this->_db ( 'iwide_r1' )->query ( $sql )->result_array();//var_dump($night_res);die;
        $time = array('one'=>0,'two'=>0,'three'=>0,'four'=>0,'five'=>0,'six'=>0,'seven'=>0);
        if(!empty($query)){
            foreach($query as $k=>$v){
                if($v['sum_time'] <= 15){//15分钟内
                    $time['one'] = isset($time['one'])?($time['one'] + 1):1;
                }elseif($v['sum_time'] <= 60){//一个小时内
                    $time['two'] = isset($time['two'])?($time['two'] + 1):1;
                }elseif($v['sum_time'] <= 720){//12小时内
                    $time['three'] = isset($time['three'])?($time['three'] + 1):1;
                }elseif($v['sum_time'] <= 1440){
                    $time['four'] = isset($time['four'])?($time['four'] + 1):1;//1天
                }elseif($v['sum_time'] <= 10080){
                    $time['five'] = isset($time['five'])?($time['five'] + 1):1;//1周
                }elseif($v['sum_time'] <= 302400){
                    $time['six'] = isset($time['six'])?($time['six'] + 1):1;//1月
                }else{
                    $time['seven'] = isset($time['seven'])?($time['seven'] + 1):1;//1月以上
                }
            }
        }
        return array('dev_fans_count'=>$dev_fans_count,'sale_fans_count'=>$sale_fans_count,'fans_from_saler'=>$fans_from_saler,'sale_fans_from_saler'=>$sale_fans_from_saler,'sale_fans_from_sence'=>$sale_fans_from_sence,'fans_from_sence'=>$fans_from_sence,'time'=>$time);
	}

    //获取分销员画像数据
    public function get_saler_picture($filter = array()){
        //分销员性别
        $sql = "select sum(if(sex = 1,1,0)) man,sum(if(sex=2,1,0)) women,sum(if(sex =3,1,0)) unknow from (select id_card, CASE WHEN LENGTH(id_card) = 18 THEN IF(MOD(SUBSTRING(id_card,-2,1),2)>0,1,2) WHEN LENGTH(id_card) = 15 THEN IF(MOD(SUBSTRING(id_card,-1,1),2)>0,1,2) ELSE 3 END as sex FROM iwide_hotel_staff where inter_id = '{$filter['inter_id']}' AND id_card != '' AND is_distributed = 1 AND qrcode_id > 0) a ";
        $sex = $this->_db ( 'iwide_r1' )->query ( $sql )->row();//var_dump($night_res);die;
        //新分销员占比
        $time = date('Y-m-d',strtotime("-1 week"));//一周前
        $sql = "select count(*) all_saler,sum(if(status_time > '{$time}',1,0)) as new_saler from iwide_hotel_staff where inter_id = '{$filter['inter_id']}' and is_distributed = 1 and qrcode_id > 0";
        $new_saler = $this->_db ( 'iwide_r1' )->query ( $sql )->result_array();//var_dump($night_res);die;
        //性别转化占比
        $sql = "select sum(if(sex = 1,1,0)) man,sum(if(sex=2,1,0)) women from (select id_card, CASE WHEN LENGTH(id_card) = 18 THEN IF(MOD(SUBSTRING(id_card,-2,1),2)>0,1,2) WHEN LENGTH(id_card) = 15 THEN IF(MOD(SUBSTRING(id_card,-1,1),2)>0,1,2) END as sex FROM iwide_hotel_staff where inter_id = '{$filter['inter_id']}' AND id_card != '' AND is_distributed = 1 AND qrcode_id > 0 and qrcode_id in (select saler  from iwide_distribute_grade_all where inter_id = '{$filter['inter_id']}' and (status = 1 or status = 2) and grade_table != 'iwide_fans_sub_log' and grade_table != 'iwide_member_additional' and saler > 0 group by saler order by grade_time asc) ) a ";
        $sex_rate = $this->_db ( 'iwide_r1' )->query ( $sql )->row();//var_dump($night_res);die;
        //年龄段分布
        $sql = "select (date_format(now(),'%Y')- SUBSTRING(id_card,7,4)) as age from iwide_hotel_staff where inter_id ='{$filter['inter_id']}' and  is_distributed = 1 AND qrcode_id > 0 and id_card != ''";
        $age_arr = $this->_db ( 'iwide_r1' )->query ( $sql )->result_array();//var_dump($night_res);die;
        $age_data = array('one'=>0,'two'=>0,'three'=>0,'four'=>0,'five'=>0);
        if(!empty($age_arr)){
            foreach($age_arr as $value){
                if($value['age'] >= 18 && $value['age'] <= 24){
                    $age_data['one'] += 1;
                }elseif($value['age'] >= 25 && $value['age'] <= 29){
                    $age_data['two'] += 1;
                }elseif($value['age'] >= 30 && $value['age'] <= 34){
                    $age_data['three'] += 1;
                }elseif($value['age'] >= 35 && $value['age'] <= 39){
                    $age_data['four'] += 1;
                }elseif($value['age'] >= 40 && $value['age'] < 60){
                    $age_data['five'] += 1;
                }
            }
        }
        return array('sex'=>$sex,'new_saler'=>$new_saler,'sex_rate'=>$sex_rate,'age_data'=>$age_data);
    }


}
