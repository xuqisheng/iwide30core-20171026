<?php
// +------------------------------------------------------------------
// | 前端标签数据处理器
// +------------------------------------------------------------------
// | Author：liwensong
// +------------------------------------------------------------------
// | Email: septet-l@outlook.com
// +------------------------------------------------------------------
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * Class Vapi_logic
 * @property Public_model $common_model
 * @property Card_model $card_model
 * @property Package_model $package_model
 */
class Vapi_statements extends MY_Model_Member {


    private $tag_data = array();
    private $tag_data_group = array();

    protected $statement_module = array(
        'dc',                        //快乐送
        'vip',                       //会员
        'soma',                      //商城
        'okpay',                     //快乐付
        'admin'                      //后台调整
    );
    public function __construct(){
        parent::__construct();
        $this->tag_data = array(
            'status'=>1000,
            'err'=>0,
            'msg'=>'OK',
            'msg_type'=>'toast',
        );
        $this->tag_data_group = array(
            'csrf_token'=>$this->security->get_csrf_token_name(),
            'csrf_value'=>$this->security->get_csrf_hash()
        );
    }

    //酒店列表
    /**
     * @param $inter_id
     * @param string $select
     * @return array
     * @author zhangyi  <zhangyi@mofly.cn>
     */
    public function hotel_list( $inter_id ,$select = '*',$hotel_ids = array()){
        $where = array(
            'inter_id'  => $inter_id
        );

        $this->db->from('hotels')->select( $select );
        $result = $this->db
            ->where($where);
        if(!empty($hotel_ids) && is_array($hotel_ids)){
            $result = $result->where_in('hotel_id',$hotel_ids);
        }

        $result = $result
            ->get()
            ->result_array();
        return $result;
//        $this->tag_data_group['data']  = $result;
//        $this->tag_data['web_data'] = $this->tag_data_group;
//        return  $this->tag_data;

    }

    //注册分销列表
    /**
     * @param $inter_id
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @param string $type
     * @return mixed
     * @author zhangyi  <zhangyi@mofly.cn>
     */
    public function distribution_list( $inter_id ,$limit = 100 ,$offset = 0 , $filter = array() ,$type = 'reg'){
        $filter['inter_id'] = $inter_id;
        $filter['type'] = $type;
        $order_by = "dr.last_update_time";
        $where = array();
        foreach($filter as $k => $v){
            if( $k == 'hotel_id' && is_array($v)){
                $where_in = $v;
            }else if(strpos($k,"send_time") === false){
                $where["dr.".$k] = $v;
            }else{
                $order_by = "dsr.send_time";
                $where["dsr.".$k] = $v;
            }
        }

        $result = $this->_shard_db()->from('distribution_record dr')
            ->select("dsr.batch_no,dsr.send_time,dr.*,i.member_info_id,i.membership_number,i.telephone,i.name")
            ->join('member_info as i','dr.open_id = i.open_id and dr.sn = i.membership_number','left')
            ->join('distribution_send_record as dsr',' dsr.sn = dr.record_id AND dsr.inter_id = dr.inter_id','left')
            ->where("dr.sales_id is not null")
            ->where("dr.last_update_time >", 0)
            ->where("dr.sn !=","")
            ->where($where);

        if(isset($where_in)){
            $result = $result->where_in('dr.hotel_id',$where_in);
        }

        $return = array();

        $return['total'] = $result->get()->num_rows();              //总数
        $total_page =  (int) ($return['total'] / $limit);
        $return['total_page'] =  ($total_page <= 0 ) ? 1 : $total_page;
        $return['per_page'] = $limit;
        $data_db_obj = $this->_shard_db()->from('distribution_record dr')
            ->select("dsr.batch_no,dsr.send_time,dr.*,i.member_info_id,i.membership_number,i.telephone,i.name")
            ->join('member_info as i','dr.open_id = i.open_id and dr.sn = i.membership_number','left')
            ->join('distribution_send_record as dsr',' dsr.sn = dr.record_id AND dsr.inter_id = dr.inter_id','left')
            ->where("dr.sales_id is not null")
            ->where("dr.last_update_time >", 0)
            ->where("dr.sn !=","");

        if(isset($where_in)){
            $data_db_obj = $data_db_obj->where_in('dr.hotel_id',$where_in);
        }
        $return['data']  = $data_db_obj
            ->where($where)
            ->limit($limit)
            ->offset($offset)
            ->order_by($order_by,"desc")
            ->get()
            ->result_array();   //数据

        return $return;
    }

    //注册分销列表(含会员信息)
    public function distribution_list_with_member( $inter_id ,$filter = array() ,$type = 'reg'){
        //SELECT dr.*,i.membership_number FROM `iwide_distribution_record` as dr
        //left join iwide_member_info i on dr.open_id = i.open_id and dr.sn = i.membership_number
        //WHERE dr.`createtime` >= '2017-07-25 00:00:00 ' AND dr.`createtime` <= '2017-09-25 00:00:00 ' AND dr.`inter_id` = 'a450089706' AND dr.`type` = 'reg' and i.member_mode =2
        $filter['inter_id'] = $inter_id;
        $filter['type'] = $type;

        $order_by = "dr.last_update_time";
        $where = array();
        foreach($filter as $k => $v){
            if( $k == 'hotel_id' && is_array($v)){
                $where_in = $v;
            }else if(strpos($k,"send_time") === false){
                $where["dr.".$k] = $v;
            }else{
                $order_by = "dsr.send_time";
                $where["dsr.".$k] = $v;
            }
        }
        $data_db_obj = $this->_shard_db()->from('distribution_record dr')
            ->select("dsr.batch_no,dsr.send_time,dr.*,i.member_info_id,i.membership_number,i.telephone,i.name")
            ->join('member_info as i','dr.open_id = i.open_id and dr.sn = i.membership_number','left')
            ->join('distribution_send_record as dsr',' dsr.sn = dr.record_id AND dsr.inter_id = dr.inter_id','left')
            ->where("dr.sales_id is not null")
            ->where("dr.last_update_time >", 0)
            ->where("dr.sn !=","");
        if(isset($where_in)){
            $data_db_obj = $data_db_obj->where_in("dr.hotel_id",$where_in);
        }
        $result = $data_db_obj
            ->where($where)
            ->order_by($order_by,"desc")
            ->get()->result_array();

        return $result;
    }

    //分销员工信息
    public function hotel_staffs($inter_id,$filter=array(),$select = " * "){
        $filter['inter_id'] = $inter_id;

        $staffs =  $this->db->from('hotel_staff')
                    ->select($select)
                    ->where($filter)
                    ->get()->result_array();
        return $staffs;
    }


    //购卡储值分销列表
    public function deposit_card_list( $inter_id  ,$limit = 100 ,$offset = 0 , $filter = array() , $status = 't' ){
        $filter['inter_id'] = $inter_id;
        $filter['pay_status'] = $status;
        $order_by = "dcp.last_update_time";
        $where = array();
        foreach($filter as $k => $v){
            if( $k == 'hotel_id' && is_array($v)){
                $where_in = $v;
            }else if(strpos($k,"send_time") === false){
                $where["dcp.".$k] = $v;
            }else{
                $order_by = "dsr.send_time";
                $where["dsr.".$k] = $v;
            }
        }
        $where['dc.is_distribution'] = 't';
        $result = $this->_shard_db()->from('iwide_deposit_card_pay dcp')
            ->select("dsr.batch_no,dcp.*, FROM_UNIXTIME( `dcp`.createtime) as createtime,dc.distribution_money,dc.title,dc.money,i.member_info_id,i.membership_number,i.telephone,i.name")
            ->join('deposit_card as dc','dcp.deposit_card_id = dc.deposit_card_id','left')
            ->join('member_info as i','dcp.member_info_id = i.member_info_id ','left')
            ->join('distribution_send_record as dsr',' dsr.sn = dcp.deposit_card_id AND dsr.inter_id = dcp.inter_id','left')
            ->where("dcp.distribution_num !=","")
            ->where($where);

        if(isset($where_in)){
            $result = $result->where_in("dcp.hotel_id",$where_in);
        }

        $return = array();
        $return['total'] = $result->get()->num_rows();              //总数
        $total_page =  (int) ($return['total'] / $limit);
        $return['total_page'] =  ($total_page <= 0 ) ? 1 : $total_page;
        $return['per_page'] = $limit;
        $data_db_obj = $this->_shard_db()->from('iwide_deposit_card_pay dcp')
            ->select("dsr.batch_no,dsr.send_time,dcp.*, FROM_UNIXTIME( `dcp`.createtime) as createtime,dc.distribution_money,dc.deposit_type,dc.title,dc.money,i.member_info_id,i.membership_number,i.telephone,i.name")
            ->join('deposit_card as dc','dcp.deposit_card_id = dc.deposit_card_id','left')
            ->join('member_info as i','dcp.member_info_id = i.member_info_id ','left')
            ->join('distribution_send_record as dsr',' dsr.sn = dcp.deposit_card_pay_id AND dsr.inter_id = dcp.inter_id','left')
            ->where("dcp.distribution_num !=","");
        if(isset($where_in)){
            $data_db_obj = $data_db_obj->where_in("dcp.hotel_id",$where_in);
        }
        $return['data']  = $data_db_obj
            ->where($where)
            ->limit($limit)
            ->offset($offset)
            ->order_by($order_by,"desc")
            ->get()->result_array();   //数据
        return $return;
    }

    //导出
    public function deposit_card_list_for_exprot( $inter_id ,$filter = array() , $status = 't' ){
        $filter['inter_id'] = $inter_id;
        $filter['pay_status'] = $status;
        $order_by = "dcp.last_update_time";
        $where = array();
        foreach($filter as $k => $v){
            if( $k == 'hotel_id' && is_array($v)){
                $where_in = $v;
            }else if(strpos($k,"send_time") === false){
                $where["dcp.".$k] = $v;
            }else{
                $order_by = "dsr.send_time";
                $where["dsr.".$k] = $v;
            }
        }
        $where['dc.is_distribution'] = 't';
        $result = $this->_shard_db()->from('iwide_deposit_card_pay dcp')
            ->select("dsr.batch_no,dsr.send_time,dcp.*, FROM_UNIXTIME( `dcp`.createtime) as createtime,dc.distribution_money,dc.deposit_type,dc.title,dc.money,i.member_info_id,i.membership_number,i.telephone,i.name")
            ->join('deposit_card as dc','dcp.deposit_card_id = dc.deposit_card_id','left')
            ->join('member_info as i','dcp.member_info_id = i.member_info_id ','left')
            ->join('distribution_send_record as dsr',' dsr.sn = dcp.deposit_card_pay_id AND dsr.inter_id = dcp.inter_id','left')
            ->where("dcp.distribution_num !=","");
        if(isset($where_in)){
            $result = $result->where_in("dcp.hotel_id",$where_in);
        }

        return $result
            ->where($where)
            ->order_by($order_by,"desc")
            ->get()->result_array();   //数据
    }


    //储值使用情况
    public function balance_statics_group_module( $inter_id , $filter = array() ,$log_type = 2 , $limit = 300 ,$offset = 0 ){
        $filter['inter_id'] = $inter_id;
        if(! isset($filter['log_type']))
            $filter['log_type'] = $log_type;
        //SELECT DATE_FORMAT( last_update_time, "%Y-%m-%d") ,module, sum(amount) FROM `iwide_balance_log` where last_update_time > '2017-08-01' and log_type = 2 group by DATE_FORMAT( last_update_time, "%Y-%m-%d"),module
        $where = $filter;
        $result = $this->_shard_db()->from('balance_log')
            ->select('DATE_FORMAT( last_update_time, "%Y-%m-%d") as date ,module, sum(amount) as amount,hotel_id')
            ->where($where)
            ->group_by('DATE_FORMAT( last_update_time, "%Y-%m-%d"),hotel_id,module')
            ->limit($limit)
            ->offset($offset)
            ->get()->result_array();
        return $result;

    }

    //储值增加情况
    public function balance_statics_group_module_add($inter_id , $filter = array() ,$log_type = 2 , $limit = 300 ,$offset = 0){
        $filter['inter_id'] = $inter_id;
        if(! isset($filter['log_type']))
            $filter['log_type'] = $log_type;
        $where = $filter;
        $result = $this->_shard_db()->from('balance_log')
            ->select('DATE_FORMAT( last_update_time, "%Y-%m-%d") as date ,module, sum(amount) as amount , log_type_name ,hotel_id')
            ->where_in('module',array('vip','admin'))
            ->where($where)
            ->group_by('DATE_FORMAT( last_update_time, "%Y-%m-%d"),module,hotel_id,log_type_name')
            ->limit($limit)
            ->offset($offset)
            ->get()->result_array();
        return $result;
    }

    //储值增加概况
    public function balance_statics_group_add_summary($inter_id,$filter = array() ,$log_type = 1){
        $filter['inter_id'] = $inter_id;
        if(! isset($filter['log_type']))
            $filter['log_type'] = $log_type;
        $where = $filter;
        $result = $this->_shard_db()->from('balance_log')
            ->select('log_type_name, sum(amount) as amount,module')
            ->where_in('module',array('vip','admin'))
            ->where($where)
            ->group_by('module,log_type_name')
            ->get()->result_array();
        return $result;
    }

    public function balance_statics_group_module_total( $inter_id , $filter = array() ,$log_type = 2){
        $filter['inter_id'] = $inter_id;
        if(! isset($filter['log_type']))
            $filter['log_type'] = $log_type;
        $where = $filter;
        $result = $this->_shard_db()->from('balance_log')
            ->select('sum(amount) as amount')
            ->where($where)
            ->get()->row_array();
        if(empty($result['amount']))
            return 0;
        else
            return $result['amount'];

    }

    //格式化
    //储值使用的格式化
    public function summary_format_data( $start_date, $end_date , $source_data ,$hotels=array(), $modules = array()){
        $temp = $start_date;
        $date_mapping = array();
        $date_mapping[$start_date] = array();
        while($temp < $end_date){
            $temp = date('Y-m-d',strtotime("$temp +1 day"));
            $date_mapping[$temp] = array();
        }
        $return = $data = array();
        if(!empty($source_data)){
//            foreach($source_data as $single_day){
//                $hotel_id = empty($single_day['hotel_id']) ? 0 : $single_day['hotel_id'];
//                $day_data[$single_day['date']][$hotel_id][$single_day['module']] =   $single_day['amount'];
//            }
            foreach($source_data as $val){
                isset($data[$val['date']][$val['module']]) ? $data[$val['date']][$val['module']]+= $val['amount'] : $data[$val['date']][$val['module']] = $val['amount'] ;
            }
        }
//        $data = array_merge($date_mapping,$day_data);
        $data = array_merge($date_mapping,$data);
        if(empty($modules))
            $modules = $this->statement_module;


        if(!empty($data)){
            foreach($data as $d => $val){

                foreach($modules as $m){
                    if(!isset($val[$m])) $val[$m] = 0;              //快乐送
                }
                $val['total'] = array_sum($val);
                $val['date'] = $d;
                $return[] = $val;
            }
        }
        return $return;
    }


    //储值使用的格式化
    public function format_data( $start_date, $end_date , $source_data ,$hotels=array(), $modules = array()){
        $temp = $start_date;
        $date_mapping = array();
        $date_mapping[$start_date] = array();
        while($temp < $end_date){
            $temp = date('Y-m-d',strtotime("$temp +1 day"));
            $date_mapping[$temp] = array();
        }
        $return = $data = array();
        if(!empty($source_data)){
            foreach($source_data as $single_day){
                $hotel_id = empty($single_day['hotel_id']) ? 0 : $single_day['hotel_id'];
                $day_data[$single_day['date']][$hotel_id][$single_day['module']] =   $single_day['amount'];
            }
//            foreach($source_data as $val){
//                $data[$val['date']][$val['module']] = $val['amount'];
//            }
        }else{
            return $data; //empty array
        }
        $data = array_merge($date_mapping,$day_data);

        if(empty($modules))
            $modules = $this->statement_module;

        if(!empty($data)){
            foreach($data as $d => $val){
                foreach($hotels as $hotel){
                    $hotel_data = array();
                    $hotel_name = $hotel['name'];
                    if(!isset($val[$hotel['hotel_id']])){
                        foreach($modules as $m){
                            $hotel_data[$m] = 0;
                        }
                        $hotel_data['total'] = array_sum($hotel_data);
                        $hotel_data['hotel_name'] = $hotel_name;
                        $hotel_data['hotel_id'] = $hotel['hotel_id'];
                        $return[$d][] = $hotel_data;
                    }else{
                        $temp = $val[$hotel['hotel_id']];
                        foreach($modules as $m){
                            if(!isset($temp[$m]))
                                $hotel_data[$m] = 0;
                            else
                                $hotel_data[$m] = $temp[$m];
                        }
                        $hotel_data['total'] = array_sum($hotel_data);
                        $hotel_data['hotel_name'] = $hotel_name;
                        $hotel_data['hotel_id'] = $hotel['hotel_id'];
                        $return[$d][] = $hotel_data;
                    }
                }
            }
        }
        return $return;
    }

    //增加储值的返回格式化处理
    /**
     * @param $start_date           //起始日期
     * @param $end_date             //结束日期
     * @param $source_data          //数据源
     * @param array $hotels         //酒店列表
     * @param array $modules        //需要展示的module类型
     * @return array       //g-购卡、礼包,c-储值,admin-后台调整
     * @author zhangyi  <zhangyi@mofly.cn>
     */
    public function summary_balance_add_format_data($start_date, $end_date , $source_data ,$hotels= array() ,$modules = array()){
        $temp = $start_date;
        $date_mapping = array();
        $date_mapping[$start_date] = array();
        while($temp < $end_date){
            $temp = date('Y-m-d',strtotime("$temp +1 day"));
            $date_mapping[$temp] = array();
        }
        $return = $data = array();
        if(!empty($source_data)){
            foreach($source_data as $val){

                $hotel_id = empty($val['hotel_id']) ? 0 : $val['hotel_id'];
                if($val['module'] == 'vip' && empty($val['log_type_name'])){
                    $val['module'] = 'c';
                }

                if($val['module'] == 'vip'){
                    $val['module'] =   $val['log_type_name'];
                }
                $data[$val['date']][$hotel_id][$val['module']] = $val['amount'];

            }
        }else{
            return $data; //empty array
        }
        $data = array_merge($date_mapping,$data);
        if(empty($modules))
            $modules = $this->statement_module;
        if(!empty($data)){
            foreach($data as $d => $val){
                $total = 0;
                $summary = array(
                    'admin' => 0,
                    'c' => 0,
                    'g' => 0,
                    'total' => 0
                );
                foreach($val as $hotel_id => $s){
                    foreach($modules as $m){
                        if(!isset($s[$m]))
                            $summary[$m] = 0;              //快乐送
                        else
                            $summary[$m] += $s[$m];
                    }
                    $summary['total'] = array_sum($s);
                    $total +=  array_sum($s);
                }
                $summary['date'] = $d;
                $return[] = $summary;
            }
        }
        return $return;
    }

    //增加储值的返回格式化处理                                                                                                                                                                                                                                                =
    /**
     * @param $start_date           //起始日期
     * @param $end_date             //结束日期
     * @param $source_data          //数据源
     * @param array $hotels         //酒店列表
     * @param array $modules        //需要展示的module类型
     * @return array       //g-购卡、礼包,c-储值,admin-后台调整
     * @author zhangyi  <zhangyi@mofly.cn>
     */
    public function balance_add_format_data($start_date, $end_date , $source_data ,$hotels= array() ,$modules = array()){
        $temp = $start_date;
        $date_mapping = array();
        $date_mapping[$start_date] = array();
        while($temp < $end_date){
            $temp = date('Y-m-d',strtotime("$temp +1 day"));
            $date_mapping[$temp] = array();
        }
        $return = $data = array();
        if(!empty($source_data)){
            foreach($source_data as $val){

                $hotel_id = empty($val['hotel_id']) ? 0 : $val['hotel_id'];
                if($val['module'] == 'vip' && empty($val['log_type_name'])){
                    $val['module'] = 'c';
                }

                if($val['module'] == 'vip'){
                    $val['module'] =   $val['log_type_name'];
                }
                $data[$val['date']][$hotel_id][$val['module']] = $val['amount'];

            }
        }else{
            return $data; //empty array
        }
        $data = array_merge($date_mapping,$data);
        if(empty($modules))
            $modules = $this->statement_module;

        if(!empty($data)){
            foreach($data as $d => $val){
                foreach($hotels as $hotel){
                    $hotel_data = array();
                    $hotel_name = $hotel['name'];


                   if(!isset($val[$hotel['hotel_id']])){
                       foreach($modules as $m){
                           $hotel_data[$m] = 0;
                       }
                       $hotel_data['total'] = array_sum($hotel_data);
                       $hotel_data['hotel_name'] = $hotel_name;
                       $hotel_data['hotel_id'] = $hotel['hotel_id'];
                       $return[$d][] = $hotel_data;
//                       $return[$d][] = array(
//                           'hotel_name' => $hotel_name,
//                           'total' => 0,
//                           'g' => 0,
//                           'c' => 0,
//                           'admin' => 0,
//                       );
                   }else{
                       $temp = $val[$hotel['hotel_id']];
                       foreach($modules as $m){
                           if(!isset($temp[$m]))
                               $hotel_data[$m] = 0;
                           else
                               $hotel_data[$m] = $temp[$m];
                       }
                       $hotel_data['total'] = array_sum($hotel_data);
                       $hotel_data['hotel_name'] = $hotel_name;
                       $hotel_data['hotel_id'] = $hotel['hotel_id'];
                       $return[$d][] = $hotel_data;
//                       $return[$d][] = array(
//                           'hotel_name' => $hotel_name,
//                           'total' => array_sum($temp),
//                           'g' => isset($temp['g']) ? $temp['g'] :0,
//                           'c' => isset($temp['c']) ? $temp['c'] :0,
//                           'admin' => isset($temp['admin']) ? $temp['admin'] :0,
//                       );
                   }
                }
            }
        }
        /*
        if(!empty($data)){
            foreach($data as $d => $val){
                $total = 0;
                $source = array();
                foreach($val as $hotel_id => $s){
                    $hotel_name = $hotels[$hotel_id]['name'];
                    foreach($modules as $m){
                        if(!isset($s[$m])) $s[$m] = 0;              //快乐送
                    }
                    $s['hotel_name'] = $hotel_name;
                    $s['total'] = array_sum($s);
                    $source[] = $s;
                    $total +=  array_sum($s);
                }
                if(empty($source)) continue;
                $date_data['source'] = $source;
                $date_data['total'] = $total;
                $date_data['date'] = $d;
                $return[] = $date_data;
            }
        }
        */
        return $return;
    }




    /*-----------------------------------积分---------------------------------------*/

    //积分情况
    public function credit_statics_group_module( $inter_id , $filter = array() ,$log_type = 2 , $limit = 300 ,$offset = 0 ){
        $filter['inter_id'] = $inter_id;
        if(! isset($filter['log_type']))
            $filter['log_type'] = $log_type;
        $where = $filter;
        $result = $this->_shard_db()->from('credit_log')
            ->select('FROM_UNIXTIME(createtime,"%Y-%m-%d") as date ,module, sum(amount) as amount  ,hotel_id')
            ->where($where)
            ->group_by('date,module,hotel_id')
            ->limit($limit)
            ->offset($offset)
            ->get()->result_array();
        return $result;
    }


    //按日期与模块统计积分
    public function credit_statics_group_module_total( $inter_id , $filter = array() ,$log_type = 2 , $module=array(), $limit = 300 ,$offset = 0){
        $filter['inter_id'] = $inter_id;
        if (!isset($filter['log_type'])) {
            $filter['log_type'] = $log_type;
        }
        $where = $filter;
        $db = $this->_shard_db()->from('credit_log')
            ->select('module, sum(amount) as amount')
            ->where($where);
        if (!empty($module) && is_array($module)) {
            $db->where_in('module', $module);
        }
        $result = $db
            ->group_by('module')
            ->limit($limit)
            ->offset($offset)
            ->get()->result_array();
        return $result;
    }

    //按日期统计积分使用
    public function credit_statics_group_module_amount_total( $inter_id , $filter = array() ,$log_type = 2 , $module=array()){
        $filter['inter_id'] = $inter_id;
        if (!isset($filter['log_type'])) {
            $filter['log_type'] = $log_type;
        }
        $where = $filter;
        $db = $this->_shard_db()->from('credit_log')
            ->select('sum(amount) as amount')
            ->where($where);
        if (!empty($module) && is_array($module)) {
            $db->where_in('module', $module);
        }
        $result = $db
            ->get()->row_array();

        if(!empty($result)){
            return $result['amount'];
        }else{
            return 0;
        }

    }



}