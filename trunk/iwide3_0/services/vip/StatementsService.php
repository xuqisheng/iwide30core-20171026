<?php

namespace App\services\vip;
use App\services\BaseService;

/**
 * Class PackageService
 * @package App\services\soma
 *
 */
class StatementsService extends BaseService
{

    /**
     *
     * @return PackageService
     * @author renshuai  <renshuai@jperation.cn>
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }


    //注册分销
    public function reg_distribution( $params = ''){
        $inter_id = $this->getCI()->session->get_admin_inter_id();
        if($inter_id != FULL_ACCESS){
            $params['inter_id'] = $inter_id ? $inter_id : 'deny';
        }

        $limit = isset($params['limit']) && !empty($params['limit']) ?  $params['limit'] : 30 ;
        $offset = isset($params['offset']) && !empty($params['offset']) ?  $params['offset'] : '' ;
        $filter = array();

        if(isset($params['hotel_id']) && !empty($params['hotel_id'])){
            $filter['hotel_id']  =  $params['hotel_id'];
        }

        if(isset($params['sales_id']) && !empty($params['sales_id'])){
            $filter['sales_id']  =  $params['sales_id'];
        }

        if(isset($params['time_type']) && $params['time_type']== 'update_time'){
            $time_type = "last_update_time";
        }else{
            $time_type = 'createtime';
        }
        //时间
        if(isset($params['start_time']) && !empty($params['start_time'])){
            $filter[$time_type ." >=" ]  = $params['start_time']." 00:00:00 ";
        }
        if(isset($params['end_time']) && !empty($params['end_time'])){
            $filter[$time_type . " <= "]  =  $params['end_time']." 00:00:00 ";
        }


//        print_r($filter);exit;
        $this->getCI()->load->model('membervip/admin/Vapi_statements','statements');
        $result = $this->getCI()->statements->distribution_list( $inter_id ,$limit ,$offset ,$filter  );
        return $result;

    }

    //导出报表
    public function reg_distribution_statements( $params = ''){

        $inter_id = $this->getCI()->session->get_admin_inter_id();
        if($inter_id != FULL_ACCESS){
            $params['inter_id'] = $inter_id ? $inter_id : 'deny';
        }

        $filter = array();
        if(isset($params['sales_id']) && !empty($params['sales_id'])){
            $filter['sales_id']  =  $params['sales_id'];
        }

        if(isset($params['time_type']) && $params['time_type']== 'update_time'){
            $time_type = "last_update_time";
        }else{
            $time_type = 'createtime';
        }
        //时间
        if(isset($params['start_time']) && !empty($params['start_time'])){
            $filter[$time_type ." >=" ]  = $params['start_time']." 00:00:00 ";
        }
        if(isset($params['end_time']) && !empty($params['end_time'])){
            $filter[$time_type . " <= "]  =  $params['end_time']." 00:00:00 ";
        }

        $this->getCI()->load->model('membervip/admin/Vapi_statements','statements');
        $result = $this->getCI()->statements->distribution_list_with_member( $inter_id ,$filter  );
        return $result;

    }

    //储值分销报表
    public function deposit_card( $params = '' ){
        $inter_id = $this->getCI()->session->get_admin_inter_id();
        if($inter_id != FULL_ACCESS){
            $params['inter_id'] = $inter_id ? $inter_id : 'deny';
        }
        $filter = array();

        /*搜索条件*/
        if(isset($params['time_type']) && $params['time_type']== 'update_time'){
            $time_type = "last_update_time";
        }else{
            $time_type = 'send_time';
        }
        //时间
        if(isset($params['start_time']) && !empty($params['start_time'])){
            $filter[$time_type ." >=" ]  = $params['start_time']." 00:00:00 ";
        }
        if(isset($params['end_time']) && !empty($params['end_time'])){
            $filter[$time_type . " <= "]  =  $params['end_time']." 00:00:00 ";
        }
        /*end 搜索条件*/

        $this->getCI()->load->model('membervip/admin/Vapi_statements','statements');
        $result = $this->getCI()->statements->deposit_card_list( $inter_id ,$filter  );

        $staffs_mapping = array();
        if(!empty($result)){
            $staffs = $this->getCI()->statements->hotel_staffs($inter_id);
            foreach($staffs as $s){
                $staffs_mapping[$s['qrcode_id']]  = $s['name'];
            }
            foreach($result as $key => $v){
                  if( !empty($staffs_mapping[$v['distribution_num']]) && isset($staffs_mapping[$v['distribution_num']]) && !empty($staffs_mapping[$v['distribution_num']])){
                      $result[$key]['distribution_name']  = $staffs_mapping[$v['distribution_num']];
                  }else{
                      $result[$key]['distribution_name']  = '';
                  }
            }

        }

        return $result;
    }

    //储值增加与使用分析
    public function deposit_analysis( $params = ''){
        $inter_id = $this->getCI()->session->get_admin_inter_id();
        if($inter_id != FULL_ACCESS){
            $params['inter_id'] = $inter_id ? $inter_id : 'deny';
        }
        $filter = array();

        $start_date = $params['start_date'];
        $end_date  = $params['end_date'];

        $filter['last_update_time >='] = $start_date;
        $filter['last_update_time <='] = $end_date;

        $this->getCI()->load->model('membervip/admin/Vapi_statements','statements');

        $hotels[0] = array(
            'hotel_id'  => 0,
            'name'    => '总部'
        );
        $hotels_list = $this->getCI()->statements->hotel_list($inter_id);
        foreach($hotels_list as $h){
            $hotels[$h['hotel_id']] = $h;
        }

        $result = $this->getCI()->statements->balance_statics_group_module( $inter_id ,$filter  );
        $balance_use_detail = $this->getCI()->statements->summary_format_data($start_date,$end_date,$result ,$hotels);

        $result = $this->getCI()->statements->balance_statics_group_module_add( $inter_id ,$filter , 1 );
        $balance_add_detail = $this->getCI()->statements->summary_balance_add_format_data($start_date,$end_date,$result,$hotels , array('admin','c','g'));

        $return_data['use_detail'] = $balance_use_detail;        //使用详情
        $return_data['add_detail'] = $balance_add_detail;        //增加详情


        $total_use = $total_add = 0;
        //各个模块的使用总额
        $add_summary = $this->getCI()->statements->balance_statics_group_add_summary( $inter_id ,$filter ,1 );                 //各个模块增加的总额
        $return_data[ "charge_add"] = $return_data[ "package_add"]  = $return_data['admin_add'] = 0;
        foreach($add_summary as $add_total_detail){
            $total_add += $add_total_detail['amount'];
            if(empty($add_total_detail['log_type_name']) && $add_total_detail['module'] == 'vip')
                $return_data[ "charge_add"] += $add_total_detail['amount'];
            elseif($add_total_detail['log_type_name'] == 'c'){
                $return_data[ "charge_add"] += $add_total_detail['amount'];
            }elseif($add_total_detail['log_type_name'] == 'g'){
                $return_data[ "package_add"] += $add_total_detail['amount'];
            }elseif($add_total_detail['module'] == 'admin'){
                $return_data[ "admin_add"] += $add_total_detail['amount'];
            }
        }

        //会员储值使用概况
        $module_array = array(
            'vip',
            'soma',
            'okpay',
            'dc',
            'admin'
        );
        foreach($module_array as $v){
            $filter['module'] =  $v;
            $total_use += $return_data[ $v."_use"] = $this->getCI()->statements->balance_statics_group_module_total( $inter_id ,$filter);    //各个模块使用总额
        }

        $return_data['total_use'] = $total_use;
        $return_data['total_add'] = $total_add;

        //环比
        //上一个周期
        $datetime1 = date_create( $start_date);
        $datetime2 = date_create($end_date);
        $interval = date_diff($datetime1, $datetime2);
        $counts = $interval->days;
        $filter['last_update_time >='] = date('Y-m-d',strtotime("$start_date -$counts day"));
        $filter['last_update_time <='] = date('Y-m-d',strtotime("$end_date -$counts day"));
        $return_data['mom_add_total'] = $this->getCI()->statements->balance_statics_group_module_total( $inter_id ,$filter ,1 );
        $return_data['mom_use_total'] = $this->getCI()->statements->balance_statics_group_module_total( $inter_id ,$filter );
        //end环比

        return $return_data;

    }

    //储值增加与使用列表(by具体日期)
    public function deposit_analysis_detail_by_date( $params = ''){
        $inter_id = $this->getCI()->session->get_admin_inter_id();
        if($inter_id != FULL_ACCESS){
            $params['inter_id'] = $inter_id ? $inter_id : 'deny';
        }
        $filter = array();

        $start_date = $params['start_date'];
        $end_date  = $params['end_date'];

        $filter['last_update_time >='] = $start_date ." 00:00:00";
        $filter['last_update_time <='] = $end_date ." 23:59:59";

        $this->getCI()->load->model('membervip/admin/Vapi_statements','statements');

        $hotels[0] = array(
            'hotel_id'  => 0,
            'name'    => '总部'
        );
        $hotels_list = $this->getCI()->statements->hotel_list($inter_id);
        foreach($hotels_list as $h){
            $hotels[$h['hotel_id']] = $h;
        }

        if(isset($params['log_type'])){
            $log_type = $params['log_type'];
        }else{
            $log_type = 2;  //默认是使用的
        }

        if($log_type == 2){
            $result = $this->getCI()->statements->balance_statics_group_module( $inter_id ,$filter  );
            $return = $this->getCI()->statements->format_data($start_date,$end_date,$result ,$hotels);
        }else{
            $result = $this->getCI()->statements->balance_statics_group_module_add( $inter_id ,$filter , 1 );
            $return = $this->getCI()->statements->balance_add_format_data($start_date,$end_date,$result,$hotels , array('admin','c','g'));

        }

        return $return;

    }


    //积分分析
    public function credit_analysis( $params = '' ){
        $inter_id = $this->getCI()->session->get_admin_inter_id();
        if($inter_id != FULL_ACCESS){
            $params['inter_id'] = $inter_id ? $inter_id : 'deny';
        }
        $filter = array();

        $start_date = $params['start_date'];
        $end_date  = $params['end_date'];

        $filter['last_update_time >='] = $start_date ." 00:00:00";
        $filter['last_update_time <='] = $end_date ." 23:59:59";

        $this->getCI()->load->model('membervip/admin/Vapi_statements','statements');

        $hotels[0] = array(
            'hotel_id'  => 0,
            'name'    => '总部'
        );
        $hotels_list = $this->getCI()->statements->hotel_list($inter_id);
        foreach($hotels_list as $h){
            $hotels[$h['hotel_id']] = $h;
        }

        $return = array();

        $modules = array(
            'sign',                      //签到
            'vip',                       //会员
            'hotel',                      //商城
            'admin'                      //后台调整
        );
        foreach($modules as $v){
              $total_add[$v] = 0;
        }
        $result = $this->getCI()->statements->credit_statics_group_module( $inter_id ,$filter,1 );
        $return['add_detail'] = $credit_add_summary = $this->getCI()->statements->summary_format_data($start_date,$end_date,$result ,$hotels ,$modules);      //发放详情
        $add_results = $this->getCI()->statements->credit_statics_group_module_total($inter_id,$filter,1,$modules);
        foreach($add_results as $v){
            $total_add[$v['module']] += $v['amount'];
        }


        $modules = array(
            'vip',                       //会员
            'soma',                      //商城
            'admin'                      //后台调整
        );
        foreach($modules as $v){
            $total_use[$v] = 0;
        }
        $result = $this->getCI()->statements->credit_statics_group_module( $inter_id ,$filter );
        $return['use_detail'] = $credit_use_summary = $this->getCI()->statements->summary_format_data($start_date,$end_date,$result ,$hotels , $modules);     //使用详情
        $use_results = $this->getCI()->statements->credit_statics_group_module_total($inter_id,$filter,2,$modules);
        foreach($use_results as $v){
            $total_use[$v['module']] += $v['amount'];
        }


        $return['add_total'] = $total_add;
        $return['use_total'] = $total_use;


        //环比
        //上一个周期
        $datetime1 = date_create( $start_date);
        $datetime2 = date_create($end_date);
        $interval = date_diff($datetime1, $datetime2);
        $counts = $interval->days;
        $filter['last_update_time >='] = date('Y-m-d',strtotime("$start_date -$counts day"));
        $filter['last_update_time <='] = date('Y-m-d',strtotime("$end_date -$counts day"));
        $return['mom_add_total'] = $this->getCI()->statements->credit_statics_group_module_amount_total( $inter_id ,$filter ,1 );
        $return['mom_use_total'] = $this->getCI()->statements->credit_statics_group_module_amount_total( $inter_id ,$filter );
        //end环比

        print_r($return);exit;

    }

}