<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Firstorder_reward_model extends MY_Model {

	public function get_resource_name()
	{
		return '首单奖励';
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
		return 'firstorder_reward';
	}

    /**查询单条信息的具体信息
     * @param array $filter
     * @return array
     */
    public function get($filter = array()){
        $sql = "select * from iwide_firstorder_reward where inter_id = '{$filter['inter_id']}' and id = {$filter['id']}";
        $query = $this->_db('iwide_r1')->query($sql)->result_array();
        return $query;
    }

    /*根据interid 查询有效的规则信息
     *
     * */
    public function check_the_first_order_rule($inter_id = ''){
        $sql = "select * from iwide_firstorder_reward where inter_id = '{$inter_id}' and status = 1";
        $query = $this->_db('iwide_r1')->query($sql)->result_array();
        return $query;
    }

    /*
     * 获取记录信息
     * */
    public function get_rule_info($filter = array(),$limit=NULL,$offset=0){
        $sql = "select * from iwide_firstorder_reward where inter_id = '{$filter['inter_id']}'";
        if(isset($filter['status']) && $filter['status'] >-1){
            $sql .= " and status = " .intval($filter['status']);
        }
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
         * 获取记录信息
         * */
    public function get_rule_info_count($filter = array()){
        $sql = "select count(*) cc from iwide_firstorder_reward where inter_id = '{$filter['inter_id']}'";
        if(isset($filter['status']) && $filter['status'] >-1){
            $sql .= " and status = " .intval($filter['status']);
        }
        $argvs = array();
        $query = $this->_db('iwide_r1')->query($sql,$argvs)->row();
        return $query->cc;
    }

    /*
     * 查询类型是否符合
     */
    public function check_type_exist($type ,$inter_id,$action = ''){
        if($action == 'add'){
            $sql = "select count(*) c from iwide_firstorder_reward where inter_id = '{$inter_id}' and type = {$type}";
            $query = $this->_db('iwide_r1')->query($sql)->row();
            if(!empty($query->c)){
                return 1;//不能添加
            }
        }
        if($type == 3){
            $sql = "select count(*) cc from iwide_firstorder_reward where inter_id = '{$inter_id}' and (type = 1 or type = 2) and status = 1";
        }else{
            $sql = "select count(*) cc from iwide_firstorder_reward where inter_id = '{$inter_id}' and (type = {$type} or type = 3) and status = 1";
        }
            $query = $this->_db('iwide_r1')->query($sql)->row();
            return empty($query->cc)?0:$query->cc;
    }

    /*
     * 首单奖励发放明细
     */
    public function get_reward_detail_info($filter = array(),$limit=NULL,$offset=0){
        //$sql = "select * from iwide_distribute_grade_all where inter_id = '{$filter['inter_id']}' and grade_table = 'iwide_firstorder_reward'";
        $sql = "select b.order_id,b.product,b.hotel_name,a.order_amount,a.grade_total,a.grade_time,a.saler,b.staff_name,a.hotel_id,a.send_time from iwide_distribute_grade_all a left join iwide_distribute_grade_ext b on a.inter_id = b.inter_id and a.id = b.grade_id left join iwide_firstorder_reward_log c on c.id = a.grade_id and c.inter_id = a.inter_id where a.inter_id = '{$filter['inter_id']}' and a.grade_table = 'iwide_firstorder_reward' and c.reward_id = {$filter['id']} ";
        if(isset($filter['hotel_id']) && $filter['hotel_id']>-1){
            $sql .= " and a.hotel_id = " . $filter['hotel_id'];
        }
        if(isset($filter['saler']) && !empty($filter['saler'])){
            $sql .= " and a.saler = " . $filter['saler'];
        }
        if(isset($filter['start_time']) && !empty($filter['start_time'])){
            $sql .= " and a.grade_time >= '{$filter['start_time']}'";
        }
        if(isset($filter['end_time']) && !empty($filter['end_time'])){
            $sql .= " and a.grade_time < '{$filter['end_time']} 23:59:59'";
        }
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
     * 首单奖励发放明细数量
     */
    public function get_reward_detail_info_count($filter = array()){
       // $sql = "select count(*) cc from iwide_distribute_grade_all where inter_id = '{$filter['inter_id']}' and grade_table = 'firstorder_reward'";
        $sql = "select count(*) cc from iwide_distribute_grade_all a left join iwide_distribute_grade_ext b on a.inter_id = b.inter_id and a.id = b.grade_id left join iwide_firstorder_reward_log c on c.id = a.grade_id and c.inter_id = a.inter_id where a.inter_id = '{$filter['inter_id']}' and a.grade_table = 'iwide_firstorder_reward' and c.reward_id = {$filter['id']}";
        if(isset($filter['hotel_id']) && $filter['hotel_id']>-1){
            $sql .= " and a.hotel_id = " . $filter['hotel_id'];
        }
        if(isset($filter['saler']) && !empty($filter['saler'])){
            $sql .= " and a.saler = " . $filter['saler'];
        }
        if(isset($filter['start_time']) && !empty($filter['start_time'])){
            $sql .= " and a.grade_time >= '{$filter['start_time']}'";
        }
        if(isset($filter['end_time']) && !empty($filter['end_time'])){
            $sql .= " and a.grade_time < '{$filter['end_time']} 23:59:59'";
        }
        $argvs = array();

        $query = $this->_db('iwide_r1')->query($sql,$argvs)->row();
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
		$content = date("Y-m-d H:i:s")." | ".$_SERVER['PHP_SELF']." | ".$content." | ".$data."\n";

		fwrite($fp, $content);
		fclose($fp);
	}


}
