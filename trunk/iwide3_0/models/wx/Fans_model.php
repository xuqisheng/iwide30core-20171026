<?php
class Fans_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}

    /**
     * 关注;
     */
    const CUR_STATUS_YES = 1;
    /**
     * 取消关注
     */
    const CUR_STATUS_NO = 2;

    const TAB_FANS = 'fans';
    const TAB_FANS_SUBS = 'fans_subs';

    /**
     * @return string
     * @author renshuai  <renshuai@jperation.cn>
     */
    private function table_name()
    {
        return 'iwide_fans_subs';
    }

    function count_all_fans($inter_id){

        $db = $this->load->database('iwide_r1',true);

        $sql = "
                SELECT
                    t1.id,t1.event,t1.source,t1.cur_status,t2.name,t2.hotel_id,t2.is_distributed,t1.event_time
                FROM
                    `iwide_fans_subs` t1
                LEFT JOIN
                    `iwide_hotel_staff` t2
                ON
                   t1.source = t2.qrcode_id
                WHERE
                   t1.inter_id = '{$inter_id}'
                AND
                   t1.inter_id = t2.inter_id
                AND
                   t1.source > 0
                GROUP BY
                    t1.openid
                ORDER BY
                    t1.event_time
                DESC
        ";


       return  $db->query($sql)->result_array();

    }


    function get_all_fans($inter_id,$offset=array(),$keyword=''){

        $db = $this->load->database('iwide_r1',true);

        $condition = '';

        $sql = "
                SELECT
                    t1.*,t2.event,t2.source,t2.cur_status
                FROM
                    `iwide_fans` t1,
                    `iwide_fans_subs` t2
                WHERE
                   t1.inter_id = '{$inter_id}'
                AND
                   t2.inter_id = '{$inter_id}'
                AND
                   t1.openid = t2.openid
        ";

        if(!empty($keyword)){
            $condition .=" AND t1.nickname like '%$keyword%'";
        }

        if(isset($offset['hotel_id'])){
            $condition .=" AND t2.hotel_id = '{$offset['hotel_id']}'";
        }

        $condition .=' GROUP BY t1.id';

        $limit = '';
        if(isset($offset['page']) && isset($offset['nums'])){
            $page = $offset['page'];
            $nums = $offset['nums'];
            $total = ($offset['page']-1)*$offset['nums'];
            $limit=" LIMIT {$total},{$nums}";
        }


        $res['data']=$db->query($sql.$condition.$limit)->result_array();

        $count_sql = "SELECT count(id) total FROM ({$sql}{$condition}) c1";

        $res['count'] = $db->query($count_sql)->row_array();

        return $res;


    }


    function count_self_fans($inter_id){

        $db = $this->load->database('iwide_r1',true);

        $sql = "SELECT count(fans.id) total FROM (SELECT id FROM `iwide_fans_subs` WHERE inter_id = '{$inter_id}' and source < 0 group by openid) fans";

       return  $db->query($sql)->row_array();

    }

    function recently_fans($inter_id){

        $today = date('Y-m-d H:i:s',strtotime(date("Y-m-d",time())));
        $last_day = date('Y-m-d H:i:s',(strtotime(date("Y-m-d",time())) - 86400));

        $db = $this->load->database('iwide_r1',true);

        $sql = "
                SELECT
                    *
                FROM
                    `iwide_fans_subs`
                WHERE
                   inter_id = '{$inter_id}'
                AND
                   event_time >=  '{$today}'
                AND
                   event_time < '{$last_day}'
                GROUP BY
                    openid
        ";

        return  $db->query($sql)->result_array();

    }

    /**
     * @param $interID
     * @param $openid
     * @return bool
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function subscribeStatus($interID, $openid)
    {
        $where = [
            'inter_id' => $interID,
            'openid' => $openid
        ];
        $row = $this->db->where($where)->order_by('event_time desc')->limit(1)->get($this->table_name())->result_array();

        if (!empty($row) && !empty($row[0]) && $row[0]['cur_status'] == self::CUR_STATUS_YES) {
            return true;
        }
        return false;
    }


    function count_hotel_fans($inter_id){

        $db = $this->load->database('iwide_r1',true);

        $db->select("count('id') total");
//        $db->select('hotel_id');
        $db->from('iwide_fans_subs');
        $db->where('inter_id',$inter_id);
        $db->where('cur_status',1);
//        $db->group_by('hotel_id');

        return $db->get()->row_array();

    }


    function  lastday_cancel($inter_id,$today,$last_day){

        $db = $this->load->database('iwide_r1',true);

        $db->select("count('id') total");
        $db->select('hotel_id');
        $db->from('iwide_fans_subs');
        $db->where('inter_id',$inter_id);
        $db->where('cur_status',2);
        $db->where('unsubcribe_time >=',$last_day);
        $db->where('unsubcribe_time <=',$today);

        $db->group_by('hotel_id');

        $res = $db->get()->result_array();

        return $res;

    }

    function distributed_fans($inter_id,$distribute=1){

        $db = $this->load->database('iwide_r1',true);

        $sql = "
        SELECT
          count(*) total,c1.hotel_id
        FROM
          (SELECT
              t2.*
          FROM
              `iwide_fans_subs` t1,
              `iwide_hotel_staff` t2
          WHERE
              t1.inter_id ='{$inter_id}'
          AND
              t1.cur_status=1
          AND
              t1.source > 0
          AND
              t1.inter_id = t2.inter_id
          AND
              t1.source = t2.qrcode_id
          AND
              t2.is_distributed = '{$distribute}') c1
          GROUP BY c1.hotel_id
        ";

        $res = $db->query($sql)->result_array($sql);

        return $res;

    }


    function total_fans($inter_id,$params=array()){

        $db = $this->load->database('iwide_r1',true);

        $db->select('*');
        $db->select("count(id) total");
        $db->from(self::TAB_FANS_SUBS);
        $cur_status = isset($params['cur_status'])?$params['cur_status']:1;

        if(isset($params['hotel_id']))$db->where('hotel_id',$params['hotel_id']);

        if(isset($params['startdate'])){
            $db->where('event_time >',$params['startdate']);
        }

        if(isset($params['enddate'])){
            $db->where('event_time <',$params['enddate']);
        }

        $db->where('inter_id',$inter_id);

        $db->group_by('cur_status');
        $db->order_by('event_time');

        $result = $db->get()->result_array();

        $res = array(
            'total'=>0,
            'cancel'=>0
        );

        if(!empty($result)){
            foreach($result as $arr){
                if($arr['cur_status']==1){
                    $res['total'] = $arr['total'];
                }else{
                    $res['cancel'] = $arr['total'];
                }
            }
        }

        return $res;


    }



    function count_con_fans($inter_id,$params=array(),$type="hotel",$source = ''){

        $db = $this->load->database('iwide_r1',true);
        $order_by = 'id DESC';

        if($type=='date'){
            $db->select("DATE_FORMAT(event_time,'%Y-%m-%d') date");
            $group_by = 'date';
            $order_by = "date ASC";
        }elseif($type=='hotel'){
            $db->select("hotel_id");
            $group_by = 'hotel_id';
        }

        $db->select("count(id) total");

        $db->from(self::TAB_FANS_SUBS);
        $cur_status = isset($params['cur_status'])?$params['cur_status']:1;

        if(isset($params['hotel_id']))$db->where('hotel_id',$params['hotel_id']);

        if(isset($params['startdate'])){
            $db->where('event_time >',"{$params['startdate']}");
        }

        if(isset($params['enddate'])){
            $db->where('event_time <',"{$params['enddate']}");
        }

        if($source == -1){
            $db->where('source <=',0);
        }elseif($source == 1){
            $db->where('source >',0);
        }

        $db->where('inter_id',$inter_id);
        $db->where('cur_status',$cur_status);

        $db->order_by($order_by);
        $db->group_by($group_by);

        $res = $db->get()->result_array();

        if(!empty($res)){
            $result = array();
            foreach($res as $temp){
                if($type=='hotel'){
                    $result[$temp['hotel_id']]['total'] = $temp['total'];
                    $result[$temp['hotel_id']]['hotel_id'] = $temp['hotel_id'];
                }else{
                    $result[$temp['date']]['total'] = $temp['total'];
                    $result[$temp['date']]['date'] = $temp['date'];
                }
            }
            $res = $result;
        }

        return $res;


    }


    function dis_fans($inter_id,$params,$type='hotel',$distribute = 1){

        $db = $this->load->database('iwide_r1',true);
        $con = '';
        $order_by = '';

        if($type=='hotel'){
            $select = "SELECT count(t1.id) total,t1.hotel_id ";
            $group = " GROUP BY t1.hotel_id";
        }else{
            $select = "SELECT count(t1.id) total,DATE_FORMAT(t1.event_time,'%Y-%m-%d') date ";
            $group = " GROUP BY date";
            $order_by = " ORDER BY date ASC";
        }

        if(isset($params['startdate']) && isset($params['enddate'])){
            $con = " AND t1.event_time > '{$params['startdate']}' AND t1.event_time < '{$params['enddate']}'";
        }

        $cur_status = isset($params['cur_status'])?$params['cur_status']:1;
        $con .= " AND t1.cur_status = {$cur_status}";

        if($distribute==1){
            $con .= " AND t2.is_distributed = 1";
        }else{
            $con .= " AND t2.is_distributed != 1";
        }

        $sql = $select."
            FROM
                `iwide_fans_subs` t1,
                `iwide_hotel_staff` t2
            WHERE
                t1.inter_id = '{$inter_id}'
            AND
                t1.inter_id = t2.inter_id
            AND
                t1.source > 0
            AND
                t1.source = t2.qrcode_id
            ".$con.$group.$order_by;

        $res =  $db->query($sql)->result_array();

        if(!empty($res)){
            $result = array();
            foreach($res as $temp){
                if($type=='hotel'){
                    $result[$temp['hotel_id']]['total'] = $temp['total'];
                    $result[$temp['hotel_id']]['hotel_id'] = $temp['hotel_id'];
                }else{
                    $result[$temp['date']]['total'] = $temp['total'];
                    $result[$temp['date']]['date'] = $temp['date'];
                }
            }
            $res = $result;
        }

        return $res;

    }



    function dept_fans($inter_id,$params,$cur_status = 1){

        $db = $this->load->database('iwide_r1',true);

        $select = "SELECT count(t1.id) total,t2.master_dept,DATE_FORMAT(t1.event_time,'%Y-%m-%d') date ";
        $group = " GROUP BY date,t2.master_dept";
        $order_by = " ORDER BY date ASC";

        if(isset($params['startdate']) && isset($params['enddate'])){
            $con = " AND t1.event_time > '{$params['startdate']}' AND t1.event_time < '{$params['enddate']}'";
        }

        $con .= " AND t1.cur_status = {$cur_status}";

        if(isset($params['hotel_id'])){
            $con .= " AND t1.hotel_id = {$params['hotel_id']}";
        }

        $sql = $select."
            FROM
                `iwide_fans_subs` t1,
                `iwide_hotel_staff` t2
            WHERE
                t1.inter_id = '{$inter_id}'
            AND
                t1.inter_id = t2.inter_id
            AND
                t1.source > 0
            AND
                t1.source = t2.qrcode_id
            AND
                t2.master_dept !=''
            ".$con.$group.$order_by;

        $res =  $db->query($sql)->result_array();

        return $res;

    }


    function getarticlesummary($inter_id){

        $ci =& get_instance();
        $ci->load->helper('common');
        $ci->load->library('Cache/Redis_proxy',array(
            'not_init'=>FALSE,
            'module'=>'common',
            'refresh'=>FALSE,
            'environment'=>ENVIRONMENT
        ),'redis_proxy');

        $redis=$ci->redis_proxy;

        $key = 'wx_article_'.$inter_id;

        $res = $redis->hGetAll($key);

        return $res;

    }


    function setarticlesummary($inter_id,$date='',$data=array()){

        $ci =& get_instance();
        $ci->load->helper('common');
        $ci->load->library('Cache/Redis_proxy',array(
            'not_init'=>FALSE,
            'module'=>'common',
            'refresh'=>FALSE,
            'environment'=>ENVIRONMENT
        ),'redis_proxy');

        $redis=$ci->redis_proxy;

        $key = 'wx_article_'.$inter_id;

        $data = json_encode($data);

        $res = $redis->hSet($key, $date, $data);

        return $res;

    }

}