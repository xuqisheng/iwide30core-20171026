<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @todo 分销分组模块
 * @author 司徒冠琛
 * @since 2016-08-20
 * @version:1.0
 */
class Distribute_group_model extends MY_Model {

	public function get_resource_name()
	{
		return '分销分组';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function table_name()
	{
		return 'distribute_group';
	}

	public function table_primary_key()
	{
	    return 'group_id';
	}

	/**获取单条记录
	* @param: string $id 分组id
	 * @return：array() eg:$arr = array(0=>array('data))
	*/
	public function get($id = '',$inter_id = ''){
		$sql = "select * from iwide_distribute_group where group_id = '".strval($id)."' and inter_id = '{$inter_id}'";//SD000001
		$query = $this->_db('iwide_r1')->query($sql);
		return $query?$query->result_array():'';
	}

	/**
	 * 获取分销分组表的分组信息
	 * @param:array():带筛选参数的数组
	 * @param:int : limit
	 * @param:int :offset
	 * @return:array():返回符合筛选条件的数据
	 */
	public function get_distribute_group_info($filter = array(),$limit=NULL,$offset=0){
		$inter_id = '';
		if(isset($filter['inter_id']) && $filter['inter_id'] != 'deny') {
			$inter_id = $filter['inter_id'];
			$sql = "select * from iwide_distribute_group where inter_id ='".$inter_id."' and is_delete = 0 ";
		}else{
			$sql = 'select * from iwide_distribute_group where is_delete = 0 ';
		}
		if(isset($filter['type']) && !empty($filter['type'])){
			$sql .= ' and type = '.intval($filter['type']);
		}
		if(isset($filter['group_id']) && !empty($filter['group_id'])){
			$sql .= " and group_id = '".strval($filter['group_id'])."'";//group_id:SD000001
		}
		if(isset($filter['begin_time']) && !empty($filter['begin_time'])){
			$start_create = strtotime($filter['begin_time']);//转换为时间戳
			$sql .= ' and create_time >= ' . $start_create;
		}
		if(isset($filter['end_time']) && !empty($filter['end_time'])){
			$end_create = strtotime($filter['end_time']);//转换为时间戳
			$sql .= ' and create_time < ' . $end_create;
		}
		if(isset($filter['source']) && $filter['source'] > -1){
			$sql .= " and source = ".intval($filter['source']);//
		}
		if(isset($filter['status']) && $filter['status'] > -1){
			$sql .= " and status = ".intval($filter['status']);//
		}
		//if(isset($filter['source']) && $filter['source'] > 0)
		$sql .= ' order by group_id desc';
		$argvs = array();
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = $limit;
		}

		$query = $this->_db('iwide_r1')->query($sql,$argvs);

		return $query;

	}

	/**输出给商城那边
	 * 获取分销分组表的分组信息
	 * @param:array():带筛选参数的数组
	 * @param:int : limit
	 * @param:int :offset
	 * @return:array():返回符合筛选条件的数据
	 */
	public function get_distribute_group_info_array($filter = array(),$limit=NULL,$offset=0){
		$inter_id = '';
		if(isset($filter['inter_id']) && $filter['inter_id'] != 'deny') {
			$inter_id = $filter['inter_id'];
			$sql = "select * from iwide_distribute_group where inter_id ='".$inter_id."' and is_delete = 0 ";
		}else{
			$sql = 'select * from iwide_distribute_group where is_delete = 0 ';
		}
		if(isset($filter['type']) && !empty($filter['type'])){
			$sql .= ' and type = '.intval($filter['type']);
		}
		if(isset($filter['group_id']) && !empty($filter['group_id'])){
			$sql .= " and group_id = '".strval($filter['group_id'])."'";//group_id:SD000001
		}
		if(isset($filter['begin_time']) && !empty($filter['begin_time'])){
			$start_create = strtotime($filter['begin_time']);//转换为时间戳
			$sql .= ' and create_time >= ' . $start_create;
		}
		if(isset($filter['end_time']) && !empty($filter['end_time'])){
			$end_create = strtotime($filter['end_time']);//转换为时间戳
			$sql .= ' and create_time < ' . $end_create;
		}
		if(isset($filter['source']) && $filter['source'] > -1){
			$sql .= " and source = ".intval($filter['source']);//
		}
		if(isset($filter['status']) && $filter['status'] > -1){
			$sql .= " and status = ".intval($filter['status']);//
		}
		//if(isset($filter['source']) && $filter['source'] > 0)
		$sql .= ' order by group_id desc';
		$argvs = array();
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = $limit;
		}

		$query = $this->_db('iwide_r1')->query($sql,$argvs)->result_array();

		return $query;

	}

	/*
	*查询一个分销员的分组组别所属信息
	 * @param:$inter_id : string 对应inter_id
	 * @param:$saler :int 对应分销员号
	 * @return:array() 返回存在的分组信息
	 */
	public function get_saler_group_info($inter_id = '',$saler = 0){
		$now = time();//group_id,group_name,inter_id,status,type,create_time,start_time,end_time,sd_member_ids
		$sql = "select * from iwide_distribute_group where inter_id = '{$inter_id}' and status = 1 and `type`=1 and start_time <= {$now} and end_time >= {$now}";
		$query = $this->_db('iwide_r1')->query($sql)->result_array();
		$return = array();
		if(!empty($query)){//获取手动分组的组别信息
			foreach($query as $k=>$v){
				if(!empty($v['sd_member_ids'])){
					$sd_member_ids = explode(',',$v['sd_member_ids']);
					if(in_array($saler,$sd_member_ids)){
						$return[] = $v;
					}
				}
			}
		}
		//获取自动分组的组别信息
		$sql = "select b.*,a.week_num from iwide_distribute_group_member a left join iwide_distribute_group b on a.inter_id = b.inter_id and a.group_id = b.group_id where a.inter_id = '{$inter_id}' and a.saler_id = '{$saler}' and b.status = 1 and b.type = 2 and  start_time <= {$now} and end_time >= {$now}  group by a.group_id";
		$res = $this->_db('iwide_r1')->query($sql)->result_array();
		if(!empty($res)){
			foreach($res as $rk=>$rv){
				//查询所在分组周期
				//$week_num = $this->get_week_num_by_date($rv['start_time'],$rv['type']);
				$return[]= $rv;
			}
		}
		return $return;
	}

	/**
	 * 获取分销分组表的分组行数
	 * @param:array():带筛选参数的数组
	 * @param:int : limit
	 * @param:int :offset
	 * @return:array():返回符合筛选条件的数据的行数
	 */
	public function get_distribute_group_count($filter = array(),$limit=NULL,$offset=0){
		$inter_id = '';
		if(isset($filter['inter_id']) && $filter['inter_id'] != 'deny') {
			$inter_id = $filter['inter_id'];
			$sql = "select count(*) as c from iwide_distribute_group where inter_id ='".$inter_id."'";
		}else{
			$sql = 'select count(*) as c from iwide_distribute_group where 1';
		}
		if(isset($filter['type']) && !empty($filter['type'])){
			$sql .= ' and type = '.intval($filter['type']);
		}
		if(isset($filter['group_id']) && !empty($filter['group_id'])){
			$sql .= " and group_id = '".strval($filter['group_id'])."'";//group_id:SD000001
		}
		if(isset($filter['begin_time']) && !empty($filter['begin_time'])){
			$start_create = strtotime($filter['begin_time']);//转换为时间戳
			$sql .= ' and create_time >= ' . $start_create;
		}
		if(isset($filter['end_time']) && !empty($filter['end_time'])){
			$end_create = strtotime($filter['end_time']);//转换为时间戳
			$sql .= ' and create_time < ' . $end_create;
		}
		if(isset($filter['source']) && $filter['source'] > -1){
			$sql .= " and source = ".intval($filter['source']);//
		}
		if(isset($filter['status']) && $filter['status'] > -1){
			$sql .= " and status = ".intval($filter['status']);//
		}
		//if(isset($filter['source']) && $filter['source'] > 0)
		$sql .= ' order by group_id desc';
		$argvs = array();
		/*if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = $limit;
		}*/
		$query = $this->_db('iwide_r1')->query($sql,$argvs)->row();;
		return $query->c;
	}

	/**获取inter_id对应的所有酒店
	 * @param string $inter_id
	 * @return array()
	 */
	public function get_hotel_info_by_inter_id($inter_id = ''){
		if(!empty($inter_id)){
			$sql = "select hotel_id,name from iwide_hotels where inter_id = '".$inter_id."'";
			$query = $this->_db('iwide_r1')->query($sql);
			return $query;
		}else{
			return '';
		}
	}

	//获取inter_id对应的部门
	public function get_department_by_inter_id($inter_id = ''){
		if(!empty($inter_id)){
			$sql = "select master_dept from iwide_hotel_staff where inter_id = '".$inter_id."' group by master_dept";
			$query = $this->_db('iwide_r1')->query($sql);
			return $query;
		}else{
			return '';
		}
	}

	/**获取目前最大的group_id
	 * @param array $filter
	 * @return mixed
	 */
	public function get_max_group_id($filter = array()){
		$sql = "select max(group_id) as max_group_id from iwide_distribute_group where 1 and type = ".$filter['type'];
		$query = $this->_db('iwide_r1')->query($sql)->result_array();
		return $query;
	}

	/**查询分销组中的会员列表
	 * @param array $filter
	 * @param null $limit
	 * @param int $offset
	 * @return array()
	 */
	public function get_group_member_list($filter = array(),$limit=NULL,$offset=0){
		$sql = "select * from iwide_distribute_group_member where 1 = 1 ";
        if(isset($filter['inter_id']) && !empty($filter['inter_id'])){
            $sql .= " and inter_id = '{$filter['inter_id']}'";
        }
        if(isset($filter['group_id']) && !empty($filter['group_id'])){
            $sql .= " and group_id = '{$filter['group_id']}'";
        }
        if(isset($filter['reward_id']) && !empty($filter['reward_id'])){
            $sql .= " and reward_id = '{$filter['reward_id']}'";
        }
        if(isset($filter['reward_status']) && $filter['reward_status'] > -1){
            $sql .= " and reward_status = " . $filter['reward_status'];
        }
        if(isset($filter['orderby']) && !empty($filter['orderby'])){
            $sql .= $filter['orderby'];
        }else{
            $sql .= " order by id desc ";
        }
		$argvs = array();
		if(!empty($limit)){
			$sql .= ' LIMIT ?,?';
			$argvs[] = $offset;
			$argvs[] = $limit;
		}
		$query = $this->_db('iwide_r1')->query($sql,$argvs);
		return $query;
	}

	/**查询分销组中会员数
	 * @param array $filter
	 * @param null $limit
	 * @param int $offset
	 * @return int
	 */
	public function get_group_member_count($filter = array(),$limit=NULL,$offset=0){
		$sql = "select count(*) as c from iwide_distribute_group_member where 1=1";
        if(isset($filter['inter_id']) && !empty($filter['inter_id'])){
            $sql .= " and inter_id = '{$filter['inter_id']}'";
        }
        if(isset($filter['group_id']) && !empty($filter['group_id'])){
            $sql .= " and group_id = '{$filter['group_id']}'";
        }
        if(isset($filter['reward_id']) && !empty($filter['reward_id'])){
            $sql .= " and reward_id = '{$filter['reward_id']}'";
        }
        if(isset($filter['reward_status']) && $filter['reward_status'] > -1){
            $sql .= " and reward_status = " . $filter['reward_status'];
        }
        if(isset($filter['orderby']) && !empty($filter['orderby'])){
            $sql .= $filter['orderby'];
        }else{
            $sql .= " order by id desc ";
        }
		$query = $this->_db('iwide_r1')->query($sql)->row();
		return $query->c;
	}


	/**获取所有的inter_id
	 * @return mixed
	 */
	public function get_all_inter_id(){
		$sql = 'select inter_id from iwide_publics where status = 0 group by inter_id';
		$query = $this->_db('iwide_r1')->query($sql);
		return $query->result_array();
	}

	/**获取有效自动分组信息
	 * @return array
	 */
	public function get_all_zd_group($filter = array()){
		$sql ="select * from iwide_distribute_group where  start_time <= ".time() ." and end_time > ".time()."  and status = 1 and is_delete = 0";
        if(isset($filter['type']) && !empty($filter['type'])){
            $sql .= " and  type = " . $filter['type'];
        }
        if(isset($filter['last_run_time']) && !empty($filter['last_run_time'])){
        	$e_time = time() - $filter['last_run_time'];
            $sql .= " and last_run_time < " . $e_time ;//脚本运行时间大于23小时
        }
        if(isset($filter['limit']) && !empty($filter['limit'])){
            $sql .= " limit " . $filter['limit'];
        }
		$query = $this->_db('iwide_r1')->query($sql);
		return $query->result_array();
	}

	/**获取相关分销员信息
	 * @param string $inter_id
	 * @param array $group
	 * @return array
	 */
	public function get_salers_info_list($inter_id = '',$group = array()){

		$sql = "select name,qrcode_id,openid from iwide_hotel_staff where inter_id = '".$inter_id."' and status=2 and is_distributed = 1 ";// and hoter_id in (".$group['hotel_ids'].") and master_dept in (".$group['department_ids'].")";
		if(!empty($group['hotel_ids'])){
			$sql .= " and hotel_id in(".$group['hotel_ids'].") ";
		}
		if(!empty($group['department_ids'])){
			$tmp = explode(',',$group['department_ids']);
			$tmp_arr = array();
			foreach($tmp as $tk=>$tv){
				$tmp_arr[] = "'".$tv."'";
			}
			$group['department_ids'] = implode(',',$tmp_arr);
			$sql .= " and master_dept in (".$group['department_ids'].") ";
		}
		$sql .= ' group by openid  order by id asc';
		$query = $this->_db('iwide_r1')->query($sql);
		return $query->result_array();
	}

    /**根据分组设定的情况计算符合条件的分销员
     * @param string $inter_id
     * @param array $saler
     * @param array $group
     * @return array|bool
     */
    public function check_count_result($inter_id = '',$salers = array(),$group = array()){
        $start_time = $group['start_time'];
        $end_time = $group['end_time'];
        $start_count_time = 0;//开始计算时间
        $end_count_time = 0;//结算计算时间
        $week_num = 0;//计算周期
        $check_type = $group['check_type'];//核定方式 1间夜 2订单 3交易额
        $check_count = $group['check_count'];//核定数量 间夜（间夜）、订单(单)、交易额(元)
        $week_num = $this->get_week_num_by_date($group['start_time'],$group['check_date']);//计算第几个周期
        if($group['check_date'] == 1){//周 计算出目前是第几周，并且算出周一和周日时间戳 拿周一和周日时间和填写的开始和结束时间进行比较
            //该周周一时间戳
            $curweekday = date('w');
            //为0是 就是 星期七
            $curweekday = $curweekday?$curweekday:7;
            $start_count_time = date('Y-m-d 00:00:00',time() - ($curweekday-1)*86400);
            $end_count_time = date('Y-m-d 23:59:59',time() + (7 - $curweekday)*86400);
        }elseif($group['check_date'] == 2){//按月计算 算出当前是第几个月 并且算出1号和30号日期
            $start_count_time = date('Y-m-01 00:00:00',time());//开始计算时间 1号
            $end_count_time = date('Y-m-d 23:59:59',strtotime(date('Y-m-01 23:59:59', time()) . ' +1 month -1 day'));//结束计算时间 最后一天
        }
        //和开始$group['start_time']  结束时间$group['end_time']比较
        if(strtotime($start_count_time) < $group['start_time']){//取填写的 开始时间
            $start_count_time = date('Y-m-d 00:00:00',$group['start_time']);
        }
        if(strtotime($end_count_time) > $group['end_time']){//取结束
            $end_count_time = date('Y-m-d 23:59:59',$group['end_time']);
        }
        $new_saler = $saler_ids= $saler_infos = $res= $saler_income = array();
        $return = array('week_num'=>$week_num);
        //先统计订房
        if($group['source']==1) {//订房
            //取出distriute_all表中的grade_id
            $grade_id_sql = "select grade_id from iwide_distribute_grade_all  where  inter_id = '{$inter_id}' and grade_table = 'iwide_hotels_order' and saler >0 and (status=1 OR status =2) and grade_time >= '{$start_count_time}' and grade_time < '{$end_count_time}'";
            if ($check_type == 1) {//核定方式 1间夜 2订单 3交易额 //sum(DATEDIFF(enddate,startdate))
                $sql = "select saler,startdate,enddate from (select startdate,enddate,id,inter_id from iwide_hotel_order_items where inter_id = '{$inter_id}' and id in (".$grade_id_sql.")) a join (select grade_id,saler,inter_id from iwide_distribute_grade_all where inter_id = '{$inter_id}' and grade_table = 'iwide_hotels_order' and (status=1 OR status =2) and grade_time >= '{$start_count_time}' and grade_time < '{$end_count_time}' and saler > 0 ) b on b.inter_id = a.inter_id and b.grade_id = a.id";
                $res = $this->_db('iwide_r1')->query($sql)->result_array();
            } elseif ($check_type == 2) {//计算订单量 //计算订单数（同一单多个子单记一个）
                $sql = "select count(*) order_count,saler from ((select id,inter_id,orderid from iwide_hotel_order_items where inter_id = '{$inter_id}' and id in (".$grade_id_sql.") group by orderid) a join (select grade_id,saler,inter_id from iwide_distribute_grade_all where inter_id = '{$inter_id}' and grade_table = 'iwide_hotels_order' and (status=1 OR status =2) and grade_time >= '{$start_count_time}'  and grade_time < '{$end_count_time}' and saler > 0) b on b.inter_id = a.inter_id and b.grade_id = a.id) group by saler";
                $res = $this->_db('iwide_r1')->query($sql)->result_array();
            } elseif ($check_type == 3) {//交易额
                $sql = "select sum(iprice) trade_amount,saler from ((select id,iprice,inter_id,orderid from iwide_hotel_order_items where inter_id = '{$inter_id}' and id in (".$grade_id_sql.")) a join (select grade_id,saler,inter_id from iwide_distribute_grade_all where inter_id = '{$inter_id}' and grade_table = 'iwide_hotels_order' and (status=1 OR status =2) and grade_time >= '{$start_count_time}'  and grade_time < '{$end_count_time}' and saler > 0) b on b.inter_id = a.inter_id and b.grade_id = a.id) group by saler";
                $res = $this->_db('iwide_r1')->query($sql)->result_array();
            }
        }elseif($group['source']==2){//商城
            $grade_id_sql = "select grade_id from iwide_distribute_grade_all  where inter_id = '{$inter_id}' and grade_table in ('iwide_soma_sales_order:default','iwide_soma_sales_order:groupon','iwide_shp_orders','iwide_soma_sales_order:killsec','iwide_soma_mooncake_order:default') and saler > 0 and  (status = 1 OR status=2) and grade_time >= '{$start_count_time}' and grade_time < '{$end_count_time}'";
            if ($check_type == 2) {//按订单
                $sql = "select saler,count(*) order_count from ((select order_id,inter_id from iwide_mall_order_summary where inter_id = '{$inter_id}' and order_id in (".$grade_id_sql.")) a join (select grade_id,saler,inter_id from iwide_distribute_grade_all where inter_id = '{$inter_id}' and grade_table in ('iwide_soma_sales_order:default','iwide_soma_sales_order:groupon','iwide_shp_orders','iwide_soma_sales_order:killsec','iwide_soma_mooncake_order:default') and (status=1 OR status =2) and grade_time >= '{$start_count_time}' and grade_time < '{$end_count_time}' and saler > 0) b on b.inter_id = a.inter_id and b.grade_id = a.order_id) group by saler";
                $res = $this->_db('iwide_r1')->query($sql)->result_array();
            } elseif ($check_type == 3) {//按交易额
                $sql = "select saler,sum(actually_paid) trade_amount from ((select order_id,inter_id,actually_paid from iwide_mall_order_summary where inter_id = '{$inter_id}' and order_id in (".$grade_id_sql.")) a join (select grade_id,saler,inter_id from iwide_distribute_grade_all where inter_id = '{$inter_id}' and grade_table in ('iwide_soma_sales_order:default','iwide_soma_sales_order:groupon','iwide_shp_orders','iwide_soma_sales_order:killsec','iwide_soma_mooncake_order:default') and (status=1 OR status =2) and grade_time >= '{$start_count_time}' and grade_time < '{$end_count_time}' and saler > 0) b on b.inter_id = a.inter_id and b.grade_id = a.order_id) group by saler";
                $res = $this->_db('iwide_r1')->query($sql)->result_array();
            }
        }
        if (!empty($res)) {
            //遍历分销员
            foreach($salers as $skey=>$svalue){
                $saler_ids[] = $svalue['qrcode_id'];
                $saler_infos[$svalue['qrcode_id']] = $svalue;
            }
            foreach ($res as $key => $value) {
                if(in_array($value['saler'],$saler_ids)){
                    if($group['source']==1){//订房
                        if($check_type == 1){//间夜
                            $tmp_room = 0;
                            /*$tmp_room = round(strtotime($value['enddate']) - strtotime($value['startdate'])) / 86400;
                            $tmp_room = $tmp_room <= 0 ? 1 : $tmp_room;//间夜数*/
                            $tmp_room = get_room_night($value['startdate'],$value['enddate'],'round',$value);
                            $new_saler[$value['saler']] = isset($new_saler[$value['saler']])?$new_saler[$value['saler']]+$tmp_room:$tmp_room;
                        }elseif($check_type == 2){//订单数量
                            $new_saler[$value['saler']] = $value['order_count'];
                        }elseif($check_type == 3){//交易额
                            $new_saler[$value['saler']] = $value['trade_amount'];
                        }
                    }elseif($group['source']==2){//商城
                        if($check_type == 2){//订单数量
                            $new_saler[$value['saler']] = $value['order_count'];
                        }elseif($check_type == 3){//交易额
                            $new_saler[$value['saler']] = $value['trade_amount'];
                        }
                    }
                }
            }
            if(!empty($new_saler)){
                //这里统计分销员绩效
                $grade_total = $this->get_saler_grades_between_date($inter_id,$start_count_time,$end_count_time,$group['source']);
                if(!empty($grade_total)){
                    foreach($grade_total as $gk=>$gv){
                        if(in_array($gv['saler'],$saler_ids)){
                            $saler_income[$gv['saler']] = $gv['total'];
                        }
                    }
                }
                foreach($new_saler as $nk=>$nv){
                    if($nv >= $check_count){
                        $return['complete_count'] = 1;
                        $return['extra'] = $nv;
                        $member_record = $this->get_member_record($week_num,$group['group_id'],$nk);
                        if(empty($member_record)){
                            $return['saler_id'] = $nk;
                            $return['openid'] = isset($saler_infos[$nk]['openid'])?$saler_infos[$nk]['openid']:'';
                            $return['saler_name'] = isset($saler_infos[$nk]['name'])?$saler_infos[$nk]['name']:'';;
                            $return['group_id'] = $group['group_id'];
                            $return['create_time'] = time();
                            $return['status'] = 1;
                            $return['inter_id'] = $group['inter_id'];
                            $return['total_income'] = isset($saler_income[$nk])?$saler_income[$nk]:'';
                            $return['complete_time'] = time();
                            $insert = $this->db->insert('distribute_group_member',$return);
                            if($insert){
                                $log  = 'member_insert_success|'. date('Y-m-d H:i:s').' : '.microtime(TRUE).'|'.json_encode($return);
                                $this->write_log($log);
                            }else{
                                $log  = 'member_insert_error|'. date('Y-m-d H:i:s').' : '.microtime(TRUE).' done...'.json_encode($return);
                                $this->write_log($log);
                            }
                        }else{//有记录 更新
                            if($member_record['reward_status'] == 1){//奖励过的不刷新数据（一般不存在，因为奖励都是周期结束后）
                                $log  = 'member_id:'.$member_record['id'].':已经奖励过，不刷新数据'. date('Y-m-d H:i:s').json_encode($return);
                                $this->write_log($log);
                            }
                            if(($saler_income[$nk] != $member_record['total_income'] || $nv != $member_record['extra'])){
                                $data['extra'] = $nv;
                                $data['total_income'] = $saler_income[$nk];
                                $update = $this->db->update('distribute_group_member',$data,array('id'=>$member_record['id']));
                                if($update){
                                    $log  = 'member_update_success|'. date('Y-m-d H:i:s').' : '.microtime(TRUE).'|'.json_encode($return);
                                    $this->write_log($log);
                                }else{
                                    $log  ='member_update_error'. date('Y-m-d H:i:s').' : '.microtime(TRUE).' |'.json_encode($return);
                                    $this->write_log($log);
                                }
                            }else{
                                $log  ='saler_id:'.$nk.":记录无更新". date('Y-m-d H:i:s').' |'.json_encode($nv);
                                $this->write_log($log);
                            }
                        }
                    }else{
                        $log  ='saler_id:'.$nk.":条件不符合". date('Y-m-d H:i:s').' |'.json_encode($nv);
                        $this->write_log($log);
                    }
                }
            }
        }else{
            $log  ='分组:'.$group['group_id'].":组内没有符合数据". date('Y-m-d H:i:s').' |'.json_encode($group).'|'.json_encode($res);
            $this->write_log($log);
        }
        return true;//符合 返回该周期，查询绩效总数，达到时间用脚本时间 达到条件次数
    }


    /**计算分销员在指定时间内绩效总额
     * @param string $inter_id
     * @param int $saler_id
     * @param string $start_time
     * @param string $end_time
     * @param int $source
     * @return decimal
     */
    public function get_saler_grades_between_date($inter_id = '',$start_time = '',$end_time = '',$source = 1){
        $sql = "SELECT saler,SUM(grade_total) total FROM iwide_distribute_grade_all WHERE inter_id='{$inter_id}' AND saler>0 and grade_time >= '{$start_time}' and grade_time < '{$end_time}' and (status = 1 OR status = 2) ";
        if($source == 1){//订房
            $sql .= " and grade_table =  'iwide_hotels_order' ";
        }elseif($source == 2){ // 商城
            $sql .= " and grade_table in ('iwide_soma_sales_order:default','iwide_soma_sales_order:groupon','iwide_shp_orders','iwide_soma_sales_order:killsec','iwide_soma_mooncake_order:default') ";
        }
        $sql .= " GROUP BY saler ";
        $query = $this->_db('iwide_r1')->query($sql)->result_array();
        return $query;
    }

	/**根据所在周期和组记录和分销号查询记录
	 * @param int $week_num
	 * @param int $group_id
	 * @param int $saler_id
	 * @return string
	 */
	public function get_member_record($week_num = 0,$group_id = 0,$saler_id = 0){
		$sql = "select * from iwide_distribute_group_member where week_num = {$week_num} and group_id = '{$group_id}' and saler_id = $saler_id";
		$query = $this->_db('iwide_r1')->query($sql)->result_array();
		return empty($query[0]) ? '' : $query[0];
	}

	/**计算组内人数和历史人数
	 * @param array $group
	 * @return array
	 */
	public function get_group_member_count_group_by_openid($group = array()){
		//获取组内历史人数
		$sql = "select count(distinct(openid)) as c from iwide_distribute_group_member where 1 and group_id ='".$group['group_id']."'";
		$query = $this->_db('iwide_r1')->query($sql)->row();
		$his_count = $query->c>0?$query->c:0;
		//计算当前周期
		$week_num = $this->get_week_num_by_date($group['start_time'],$group['check_date']);
		//获取组内当前周期的人数
		$sql = "select count(distinct(openid)) as cc from iwide_distribute_group_member where 1 and group_id ='".$group['group_id']."' and week_num = ".$week_num;
		$query = $this->_db('iwide_r1')->query($sql)->row();
		$week_count = $query->cc>0?$query->cc:0;
		return array('his_count'=>$his_count,'week_count'=>$week_count);
	}

	/**获取指定时间的所在周期算出当前是第几个月或者是第几周
	 * @param string $start_time 开始计算的时间
	 * @param int $type	类型 周：1 月：2
	 * @return float|int
	 */
	public function get_week_num_by_date($start_time = '',$type = 1){
		$week_num = 1;
		if($type == 1){//按周
			$start_week_day = date('w',$start_time)?date('w',$start_time):7;
			$start_moday = $start_time - ($start_week_day-1)*86400;
			$week_time = time()-$start_moday;
			$week_num = ceil($week_time/(3600*24*7));//第几个周期
		}elseif($type == 2){//按月
			$start_mon = date('m',$start_time);
			$week_time = time()-strtotime(date('Y-'.$start_mon.'-01 00:00:00',$start_time));
			$days = date("t");
			$week_num = ceil($week_time/(3600*24*$days));//第几个周期
		}
		return $week_num;
	}

    /*获取分销分组奖励规则信息
     *param array $filter
     * @param:int : limit
	 * @param:int :offset
     * @return :array 记录结果
     * */
    public function get_group_reward_list($filter = array() ,$limit=NULL,$offset=0){
        $sql = "select * from iwide_distribute_group_reward where inter_id = '{$filter['inter_id']}'";
        if(isset($filter['reward_id']) && $filter['reward_id']){
            $sql .= " and reward_id = '{$filter['reward_id']}'";
        }
        if(isset($filter['start_time']) && !empty($filter['start_time'])){
            $sql .= " and start_time >= '" . $filter['start_time']."'";
        }
        if(isset($filter['end_time']) && !empty($filter['end_time'])){
            $sql .= " and end_time < '" . $filter['end_time'] . " 23:59:59'";
        }
        if(isset($filter['source']) && $filter['source'] > 0){
            $sql .= " and source = " . $filter['source'];
        }
        if(isset($filter['status']) && $filter['status'] > -1){
            $sql .= " and status = " . $filter['status'];
        }
        $sql .= " order by reward_id desc ";
        $argvs = array();
        if(!empty($limit)){
            $sql .= ' LIMIT ?,?';
            $argvs[] = $offset;
            $argvs[] = $limit;
        }
        $query = $this->_db('iwide_r1')->query($sql,$argvs)->result_array();
        return $query;
    }
    /*获取分销分组奖励规则数量
    *param array $filter
    * @param:int : limit
    * @param:int :offset
    * @return :int count
    * */
    public function get_group_reward_list_count($filter = array()){
        $sql = "select count(*) as c from iwide_distribute_group_reward where inter_id = '{$filter['inter_id']}'";
        if(isset($filter['reward_id']) && $filter['reward_id']){
            $sql .= " and reward_id = '{$filter['reward_id']}'";
        }
        if(isset($filter['start_time']) && !empty($filter['start_time'])){
            $sql .= " and start_time >= '" . $filter['start_time']."'";
        }
        if(isset($filter['end_time']) && !empty($filter['end_time'])){
            $sql .= " and end_time < '" . $filter['end_time'] . " 23:59:59'";
        }
        if(isset($filter['source']) && $filter['source'] > 0){
            $sql .= " and source = " . $filter['source'];
        }
        if(isset($filter['status']) && $filter['status'] > -1){
            $sql .= " and status = " . $filter['status'];
        }
        $query = $this->_db('iwide_r1')->query($sql)->row();
        return $query->c?$query->c:0;
    }

    /**获取目前最大的分组奖励id
     * @param array $filter
     * @return mixed
     */
    public function get_max_reward_id($filter = array()){
        $sql = "select max(reward_id) as max_reward_id from iwide_distribute_group_reward";
        $query = $this->_db('iwide_r1')->query($sql)->row();
        return $query->max_reward_id?$query->max_reward_id:'';
    }

    /*记录奖励记录信息
     *@param string $inter_id
     * @param array $reward 奖励规则
     * */
    public function update_reward_member_record($inter_id = '',$reward = array(),$group = array()){
        //查看差多少到限制人数
        $count = $reward['limit_count'] - $reward['reward_count'];
        //查询所有的未奖励的组内成员
        $sql = "select * from iwide_distribute_group_member where inter_id = '{$inter_id}' and group_id = '{$reward['group_id']}' and status = 1  and reward_status = 0 order by id asc";
        $query = $this->_db('iwide_r1')->query($sql)->result_array();
        if(!empty($query)){
            $this->load->model('distribute/grades_model');
            $num = 0;
            foreach($query as $k=>$v){
                if($num <$count){
                    //推送给分销中心
                    $money = 0;
                    if($reward['reward_type'] == 1){//按全部订单计数奖励
                        if($reward['reward_check'] == 1 || $reward['reward_check'] == 2){//按订单核算 OR按间夜
                            $money = $reward['reward'] * $v['extra'];
                        }elseif($reward['reward_check'] == 3){//按交易额
                            $money = $reward['reward'] * $v['extra'] / 100;
                        }
                    }elseif($reward['reward_type'] == 2){//超过部分订单奖励
                        if($reward['reward_check'] == 1 || $reward['reward_check'] == 2){//按订单核算 OR按间夜
                            $money = ($v['extra'] - $group['check_count']) * $reward['reward'];
                        }elseif($reward['reward_check'] == 3){//按交易额
                            $money = $reward['reward'] * ($v['extra'] - $group['check_count']) / 100;
                        }
                    }elseif($reward['reward_type'] == 3){//不计数奖励
                        $money = $reward['reward'];
                    }
                    $data = array();
                    $data['inter_id'] = $inter_id;
                    $data['grade_openid'] = '';
                    $data['grade_id_name'] = 'id';
                    $data['grade_table'] = 'iwide_distribute_group_member';//类型
                    $data['grade_id'] = $v['id'];
                    $data['saler'] = $v['saler_id'];
                    $data['remark'] = $data['product'] = '分组绩效奖励';
                    $data['order_id'] = $reward['reward_id'];
                    $data['grade_time'] = $data['order_time'] =  date('Y-m-d H:i:s',$v['create_time']);
                    $data['grade_total'] = $money;//根据上面的计算得出
                    $data['grade_typ'] = 1;
                    $data['status'] = 1;//先设定为这个状态
                    $data['hotel_id'] = -1;
                    if( $this->grades_model->_create_grade($data)){
                        $now = time();
                        //更新奖励信息
                        $sql = "update iwide_distribute_group_member set grade_total='{$money}',reward_status = 1,reward_id = '{$reward['reward_id']}',reward_time = '{$now}' where id = {$v['id']}";
                        $res = $this->db->query($sql);
                        $num++;
                        $log  = 'inter_id:'.$inter_id.'member_id：'.$v['id'].'推送更新成功|'. date('Y-m-d H:i:s').json_encode($data).'|'.json_encode($money).'|'.json_encode($res);
                        $this->write_log($log);
                    }else{
                        //推送失败
                        $log  = 'inter_id:'.$inter_id.'member_id：'.$v['id'].'推送分销中心失败|'. date('Y-m-d H:i:s').' done...';
                        $this->write_log($log);
                    }
                }else{
                    $log  = 'inter_id:'.$inter_id.'member_id：'.$v['id'].'已经超出限制数量|'. date('Y-m-d H:i:s').' done...';
                    $this->write_log($log);
                    break;
                }
            }
            //更新奖励规则信息
           // $this->db->where(array('reward_id'=>$reward['reward_id'],'inter_id'=>$inter_id));
            $sum_count = $reward['reward_count'] + $num;
            $this->db->update('distribute_group_reward',array(
                'reward_count'=>$sum_count,'locked'=>0 //解锁
            ),array('reward_id'=>$reward['reward_id'],'inter_id'=>$inter_id));
        }
    }

    //连表获取奖励记录(要获取绩效奖励发放的状态 )
    public function get_reward_member_list($filter = array(),$limit=NULL,$offset=0){
        $sql = "select a.*,b.send_time  from ((select * from iwide_distribute_group_member where inter_id = ? and reward_id = ? and reward_status = 1 and reward_id != '' limit ?,?) a left join (select status,send_time,grade_id,inter_id  from iwide_distribute_grade_all where inter_id = ? and grade_table = 'iwide_distribute_group_member' and saler >0) b on b.inter_id = a.inter_id and b.grade_id = a.id) order by a.reward_time asc";
        $param[] = $filter['inter_id'];
        $param[] = $filter['reward_id'];
        $param[] = $offset;
        $param[] = $limit;
        $param[] = $filter['inter_id'];
        $query = $this->_db('iwide_r1')->query($sql,$param);

        return $query;
    }
    //连表获取奖励记录数量
    public function get_reward_member_count($filter = array()){
        $sql = "select count(*) as cc from iwide_distribute_group_member where inter_id = ? and reward_id = ? and  reward_status = 1 and reward_id != '' ";
        $param[] = $filter['inter_id'];
        $param[] = $filter['reward_id'];
        $query = $this->_db('iwide_r1')->query($sql,$param)->row();

        return $query->cc;
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
		$content = date("Y-m-d H:i:s")." | ".getmypid()." | ".$_SERVER['PHP_SELF']." | ".session_id()." | ".$content." | ".$data."\n";

		fwrite($fp, $content);
		fclose($fp);
	}


	/* 以上为AdminLTE 后台UI输出配置函数 */



  /*  public function distribute_group_count($filter)
    {
        if(isset($filter['inter_id']) && $filter['inter_id'] != 'deny')
        {
            $inter_id = $filter['inter_id'];
            $sql = "select COUNT(group_id) AS num from iwide_distribute_group where is_delete =0 AND inter_id ='".$inter_id."'";
        }
        else
        {
            $sql = "select COUNT(group_id) AS num from iwide_distribute_group where is_delete =0";
        }

        if(isset($filter['type']) && !empty($filter['type']))
        {
            $sql .= ' and type = '.intval($filter['type']);
        }

        if(isset($filter['group_name']) && !empty($filter['group_name']))
        {
            $sql .= " and group_name = '".addslashes($filter['group_name'])."'";//group_id:SD000001
        }

        if(isset($filter['start_time']) && !empty($filter['start_time']))
        {
            $start_create = strtotime($filter['start_time']);//转换为时间戳
            $sql .= ' and create_time >= ' . $start_create;
        }

        if(isset($filter['end_time']) && !empty($filter['end_time']))
        {
            $end_create = strtotime($filter['end_time']);//转换为时间戳
            $sql .= ' and create_time < ' . $end_create;
        }

        if(isset($filter['source']) && $filter['source'] > 0)
        {
            $sql .= " and source = ".intval($filter['source']);//
        }
        $data = $this->_db('iwide_r1')->query($sql)->row_array();
        return $data['num'];
    }*/


    /**
     * 查询分销分组
     * @param array $filter 条件
     * @param string $field 字段
     * @param int $cur_page 页码
     * @param int $per_page 显示数量
     */
   /* public function distribute_group_list($filter,$field = '*',$cur_page=1,$per_page=20)
    {

        if(isset($filter['inter_id']) && $filter['inter_id'] != 'deny')
        {
            $inter_id = $filter['inter_id'];
            $sql = "select {$field} from iwide_distribute_group where is_delete = 0 and inter_id ='".$inter_id."'";
        }
        else
        {
            $sql = "select {$field} from iwide_distribute_group where is_delete =0";
        }

        if(isset($filter['type']) && !empty($filter['type']))
        {
            $sql .= ' and type = '.intval($filter['type']);
        }

        if(isset($filter['group_name']) && !empty($filter['group_name']))
        {
            $sql .= " and group_name = '".addslashes($filter['group_name'])."'";//group_id:SD000001
        }

        if(isset($filter['start_time']) && !empty($filter['start_time']))
        {
            $start_create = strtotime($filter['start_time']);//转换为时间戳
            $sql .= ' and create_time >= ' . $start_create;
        }

        if(isset($filter['end_time']) && !empty($filter['end_time']))
        {
            $end_create = strtotime($filter['end_time']);//转换为时间戳
            $sql .= ' and create_time < ' . $end_create;
        }

        if(isset($filter['source']) && $filter['source'] > 0)
        {
            $sql .= " and source = ".intval($filter['source']);//
        }

        $sql .= " order by group_id desc";
        $sql .= $this->set_limit($cur_page,$per_page);

        $data = $this->_db('iwide_r1')->query($sql)->result_array();
        return $data;
    }*/

    //设置分段
   /* private function set_limit($page,$page_size)
    {
        return $page_size > 0 ? (' LIMIT ' . max(0, ($page-1)*$page_size) . ', ' . max(1, $page_size)) : '';
    }*/


    /**
     * 查询公众号/酒店部门
     * @param string $inter_id
     * @param string $hotel_id
     */
   /* public function get_department($inter_id,$hotel_id)
    {
        $where = "where inter_id = '".$inter_id."'";
        $where .= !empty($hotel_id) ?  "AND hotel_id in('".$hotel_id."')": '';
        $sql = "select master_dept from iwide_hotel_staff {$where} group by master_dept";
        $query = $this->_db('iwide_r1')->query($sql)->result_array();
        return $query;
    }*/


}
