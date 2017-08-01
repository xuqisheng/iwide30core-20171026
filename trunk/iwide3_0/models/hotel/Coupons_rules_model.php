<?php
class Coupons_rules_model extends MY_Model {
	function __construct() {
		parent::__construct ();
	}

    public function table_primary_key()
    {
        return 'rule_id';
    }

    public function attribute_labels()
    {
        return array(
            'rule_id'=> 'ID',
            'inter_id'=> '公众号',
            'rule_name'=> '规则名称',
            'rule_type'=> '类型',
            'coupon_ids'=> '发放内容',
            'hotel_rooms'=> 'Hotel_rooms',
            'rule_dates'=> 'Rule_dates',
            'extra_rule'=> 'Extra_rule',
            'status'=> '状态',
            'create_time'=> '创建时间',
            'update_time'=> '更新时间',
            'send_condition'=> 'Send_condition',
            'trigger_times'=> 'Trigger_times',
        );
    }

    public function rules_list_labels()
    {
        return array(
            'rule_id'=> 'ID',
            'rule_name'=> '规则名称',
            'coupon_ids'=> '发放内容',
            'status'=> '状态',
            'create_time'=> '创建时间',
            'update_time'=> '更新时间',
        );
    }


    public function coupons_status()
    {
        return array(
            '1'=> '激活',
            '2'=> '未激活',
            '3'=> '已删除',
        );
    }


    public function operate_type(){

        return array(
            'add'=> '增加',
            'update'=> '修改',
            'check'=> '查看',
        );


    }


    public function getCouponTitle($data){     //获取会员模块的优惠券信息

        $res=array();

        if(isset($data['data']) && is_array($data['data'])){

            foreach($data['data'] as $arr){

                $res[$arr['card_id']]=$arr['title'];

            }
        }

        return $res;

    }


    function give_rules_list($inter_id){    //所有优惠券发放规则列表

        $db_read = $this->load->database('iwide_r1',true);

        $res=$db_read->query("SELECT * FROM `iwide_hotel_coupon_grules` where inter_id='{$inter_id}'")->result();

        if(!empty($res)){

            foreach($res as $key=>$arr){

                $res[$key]->coupon_ids=explode(',',$arr->coupon_ids);

            }

        }
        return $res;

	}

    function add_giverule($inter_id, $data) {   //增加规则
        $db = $this->_load_db ();
        $data ['inter_id'] = $inter_id;
        $data ['create_time'] = date ( 'Y-m-d H:i:s' );
        $data ['update_time'] = date ( 'Y-m-d H:i:s' );

        $result=$db->insert ( 'iwide_hotel_coupon_grules', $data );

        if ($result){
            $rule_id=$db->insert_id();
            $this->load->model('hotel/Hotel_log_model');
            unset($data ['inter_id']);
            unset($data ['create_time']);
            $this->Hotel_log_model->add_admin_log('hotel_coupon_grules#'.$rule_id,'add',$data);
        }
        return $result;
    }


    function _load_db() {
        return $this->db;
    }


    function OrderStatusField(){

        return array(
            'left'=>'订单离店后',
            'ensure'=>'订单确认后',
            'in'=>'订单入住后',
            'hotel_cancel'=>'酒店取消后',
            'custom_cancel'=>'用户取消后'
        );

    }


    function update_giverule($inter_id, $rule_id, $updata) {    //修改规则
//        $db = $this->_load_db ();
//        $updata ['update_time'] = date ( 'Y-m-d H:i:s' );
//        $db->where ( array (
//            'inter_id' => $inter_id,
//            'rule_id' => $rule_id
//        ) );
//        return $db->update ( 'iwide_hotel_coupon_grules', $updata );
        $db = $this->_load_db ();
        $this->load->model('hotel/Coupons_model');
        $check = $this->Coupons_model->get_giverule ( $inter_id, $rule_id, FALSE );
        if (empty ( $check )) {
            return FALSE;
        }
        $db->where ( array (
            'inter_id' => $inter_id,
            'rule_id' => $rule_id
        ) );
        $result=$db->update ( 'iwide_hotel_coupon_grules', $updata );
        if ($result&&$db->affected_rows()>0){
            $db->where ( array (
                'inter_id' => $inter_id,
                'rule_id' => $rule_id
            ) );
            $result=$db->update ( 'iwide_hotel_coupon_grules', array('update_time'=>date ( 'Y-m-d H:i:s' )) );

            $update_diff=array();
            foreach ($check as $k=>$c){
                if (isset($updata[$k])&&$check[$k]!=$updata[$k]){
                    $update_diff[$k]=array('old'=>$c,'new'=>$updata[$k]);
                }
            }
            $this->load->model('hotel/Hotel_log_model');
            $this->Hotel_log_model->add_admin_log('hotel_coupon_grules#'.$rule_id,'edit',$update_diff);
        }
        return $result;
    }


    function getAllCouponRules($inter_id,$status='1'){

        $db_read = $this->load->database('iwide_r1',true);

        $res=$db_read->query("SELECT * FROM `iwide_hotel_coupon_grules` where inter_id='{$inter_id}' AND status={$status}")->result();

        if(!empty($res)){

             return $res;

        }else{

            return false;

        }

    }


    function getSumCoupons($rule_id,$openid,$inter_id){

        $db_read = $this->load->database('iwide_r1',true);

        $res=$db_read->query("SELECT `amount` FROM `iwide_hotel_coupon_count` where inter_id='{$inter_id}' AND openid='{$openid}' AND give_rule_id={$rule_id}")->row();

        if(!empty($res)){

            return $res->amount;

        }else{

            $time=date("Y-m-d H:i:s");

            $res=$this->db->query("INSERT INTO `iwide_hotel_coupon_count`(`inter_id`,`openid`,`give_rule_id`,`amount`,`create_time`) values ('{$inter_id}','{$openid}',$rule_id,0,'{$time}')");

            return 0;
        }

    }


    public function getTotalOrders($inter_id,$openid){

        $db_read = $this->load->database('iwide_r1',true);

        $res=$db_read->query("SELECT COUNT(id) as total FROM `iwide_hotel_orders` where inter_id='{$inter_id}' AND openid='{$openid}' AND status=3")->row();

        if(empty($res->total)){

            return 0;

        }else{

            return $res->total;
        }

    }



    public function getCouponsByRules($module, $handle, $inter_id, $params,$openid)
    {

        try {
            $ruleObjectList = $this->getGiveCoupon($module, $handle, $inter_id, $params,$openid);
            return $ruleObjectList;
        } catch (Exception $e) {
            $error = new stdClass();
            $error->error = true;
            $error->message = $e->getMessage();
            $error->code = $e->getCode();
            $error->file = $e->getFile();
            $error->line = $e->getLine();

            return $error;
        }
    }


    public function  getGiveCoupon($module, $handle, $inter_id, $params,$openid){

        $rules = $this->getAllCouponRules($inter_id);
        $activeRules = array();

        $this->load->model ( 'hotel/Member_new_model','Member_new_model');

        if(!empty($rules)){

            foreach($rules as $rule) {

                $extra_rule=json_decode($rule->extra_rule);
                $rule_dates=json_decode($rule->rule_dates);
                $hotel_rooms=json_decode($rule->hotel_rooms);

                //过滤会员
                if(isset($extra_rule->level) && $extra_rule->level!='' && is_array($extra_rule->level)) {

                    $memberInfo=$this->Member_new_model->getMemberByOpenId($inter_id,$openid);

                    if(isset($memberInfo->member_lvl_id) && !empty($memberInfo->member_lvl_id)){
                        $level=$memberInfo->member_lvl_id;
                    }else{
                        $level=0;
                    }

                    if(in_array($level,$extra_rule->level)) {
                        unset($extra_rule->level);
                    } else {
                        continue;
                    }
                }

                //支付方式
                if(isset($extra_rule->paytype) && $extra_rule->paytype!='' && is_array($extra_rule->paytype)) {
                    if(isset($params['paytype'])){
                        if(in_array($params['paytype'],$extra_rule->paytype)) {
                            unset($extra_rule->paytype);
                        } else {
                            continue;
                        }
                    }
                }


                //过滤消费金额满
                if(isset($extra_rule->min_amount) && $extra_rule->min_amount!='') {
                    if(isset($params['amount']) && $params['amount']>=$extra_rule->min_amount) {
                        unset($extra_rule->min_amount);
                    } else {
                        continue;
                    }
                }


                //过滤选定的酒店、房型与价格代码
                if(isset($hotel_rooms) && !empty($hotel_rooms)) {

                    $hotel_id=$params['hotel'];
                    $room_id=$params['room_id'];

                    if(isset($hotel_rooms->{$hotel_id}->{$room_id}) && in_array($params['price_code'],$hotel_rooms->{$hotel_id}->{$room_id})){
                        unset($rule->hotel_rooms);
                    } else {
                        continue;
                    }
                }

                //满足订单数
                if(isset($extra_rule->order_nums) && $extra_rule->order_nums!=0) {
                    $total_orders = $this->getTotalOrders($inter_id,$openid);
                    if($total_orders>=$extra_rule->order_nums) {
                        unset($extra_rule->order_nums);
                    } else {
                        continue;
                    }
                }

                //同一用户执行规则的数量
                if(isset($rule->trigger_times)) {
                    $times=$this->getSumCoupons($rule->rule_id,$openid,$inter_id);
                    if($rule->trigger_times==0 || $rule->trigger_times>$times) {
                        unset($rule->trigger_times);
                    } else {
                        continue;
                    }
                }



                //过滤不执行的星期
                if(isset($rule_dates->d->r->week) && $rule_dates->d->r->week!='') {
                    $week=explode(',',$rule_dates->d->r->week);
                    $startdate=strtotime($params['startdate']);
                    $w=date("w",$startdate);
                    if(in_array($w,$week)){
                        continue;
                    }
                }

                //过滤不执行时间段
                if(isset($rule_dates->d->d) && $rule_dates->d->d!='') {
                    $d=explode(',',$rule_dates->d->d);
                    foreach($d as $arr){
                        $dd=explode('-',$arr);
                        if(isset($dd[1]) && !empty($dd[1])){
                            if($dd[0]<=$params['startdate'] && $params['startdate']<=$dd[1]){
                                continue 2;
                            }
                        }elseif(isset($dd[0]) && !empty($dd[0])){
                            if($dd[0]==$params['startdate']){
                                continue 2;
                            }
                        }
                    }
                }


                //过滤随机发放规则
                if(isset($extra_rule->is_random) && $extra_rule->is_random==2) {
                    $random_nums = rand(0,100);
/*                    $random_give_amounts = $this->countGiveCounpon($inter_id,$rule->rule_id);
                    if(is_null($random_give_amounts['total'])){
                        $random_give_amounts['total'] = 0;
                    }*/

                    if(!empty($extra_rule->random_percent) && !empty($extra_rule->random_amounts)){
                        if($random_nums<=$extra_rule->random_percent && $rule->random_times < $extra_rule->random_amounts){
                            unset($extra_rule->random_percent);
                            unset($extra_rule->random_amounts);
                        } else {
                            continue;
                        }
                    }else{
                        continue;
                    }
                }

                //返回奖励详情
                $activeRules[$rule->rule_id] = array('rule_name'=>$rule->rule_name,'reward'=>$rule);

                if($rule->all_times==0){               //重新统计规则执行次数
                    $count_times = $this->countGiveCounpon($inter_id,$rule->rule_id);
                    if($count_times['total']!=0){
                        $this->update_coupon_all_times($inter_id,$rule->rule_id,$count_times['total']);
                    }
                }

                if(isset($extra_rule->is_random) && $extra_rule->is_random==2) {    //更新随机执行次数
                    $this->update_random_coupon_rules_times($inter_id,$rule->rule_id);
                }else{
                    $this->update_coupon_rules_times($inter_id,$rule->rule_id);
                }

            }

        }

//        var_dump($activeRules);exit;

        return $activeRules;
    }


    function add_user_coupon($inter_id, $order_id, $coupon,$add_type = 'create', $give_condition,$rule_id,$title,$openid,$give_num=1) {
        switch ($add_type) {
            case 'create' :
                $status = 4;
                break;
            case 'give' :
                $status = 5;
                break;
            default :
                break;
        }

        $time=date("Y-m-d H:i:s");

        $coupon_ids=explode(',',$coupon);

        $insert_data = array();

        foreach($coupon_ids as $coupon_id){

            for ($i=0; $i<$give_num; $i++){

                $insert_data[] = array(
                    'inter_id'=>$inter_id,
                    'orderid'=>$order_id,
                    'card_id'=>$coupon_id,
                    'title'=>$title,
                    'give_condition'=>$give_condition,
                    'rel_type'=>2,
                    'status'=>$status,
                    'create_time'=>$time,
                    'rule_id'=>$rule_id,
                );
            }
        }

        $order_coupons = $this->check_order_coupons($inter_id,$rule_id,$order_id);

        if(!empty($order_coupons) && !empty($insert_data)){
            foreach($insert_data as $i_keys=>$i_data){
                foreach($order_coupons as $o_keys=>$o_coupon){
                    if($o_coupon['card_id']==$i_data['card_id'] && $o_coupon['orderid']==$i_data['orderid'] && $o_coupon['rule_id']==$i_data['rule_id']){
                        unset($insert_data[$i_keys]);
                        unset($order_coupons[$o_keys]);
                        break;
                    }
                }
            }
        }

        if(!empty($insert_data)){
            $res = $this->db->insert_batch('iwide_hotel_order_coupons',$insert_data);
        }else{
            return false;
        }


        if($res){

            return true;

        }else{

            return false;

        }

    }


    public function updateCouponStatus($inter_id,$orderid,$coupon_id,$rule_id,$give_condition,$status){

        $time=date("Y-m-d H:i:s");

        $res=$this->db->query("UPDATE
                                    `iwide_hotel_order_coupons`
                                SET
                                    status=$status,
                                    update_time='{$time}'
                                WHERE
                                    inter_id='{$inter_id}'
                                AND
                                    orderid='{$orderid}'
                                AND
                                    card_id='{$coupon_id}'
                                AND
                                     rule_id=$rule_id
                                AND
                                    give_condition='{$give_condition}'
                                AND
                                    rel_type=2
                                AND
                                    status=4
                                LIMIT
                                    1
        ");

        return $res;

    }



    public function giveCouponTo($order,$give_info,$give_condition){

        $this->load->model ( 'hotel/Coupon_new_model','Coupon_new_model');

        foreach($give_info->$give_condition as $key=>$arr){

            $coupon_ids=explode(',',$arr->coupon_ids);

            foreach($coupon_ids as $coupon_id){

                for($x=1;$x<=$arr->card_nums;$x++){

                    $uu_code=$arr->uu_code.$coupon_id.$x;

                    $getNewCoupon=$this->Coupon_new_model->getNewCoupon($order['openid'],$order['inter_id'],$coupon_id,$uu_code);

                    if(isset($getNewCoupon['data']) && !empty($getNewCoupon['data'])){

                       $res=$this->updateCouponStatus($order['inter_id'],$order['orderid'],$coupon_id,$key,$give_condition,5);

                    }else{

                        $res=$this->updateCouponStatus($order['inter_id'],$order['orderid'],$coupon_id,$key,$give_condition,6);
                    }

                }

            }

            if($res){

                $res=$this->updateCouponCount($key,$order['openid'],$order['inter_id']);

            }

        }

        if($res){

            return true;

        }else{

            return false;

        }

    }


    function updateCouponCount($rule_id,$openid,$inter_id){

        $res=$this->db->query("UPDATE
                                    `iwide_hotel_coupon_count`
                                SET
                                    amount=amount+1
                                WHERE
                                    inter_id='{$inter_id}'
                                AND
                                    openid='{$openid}'
                                AND
                                    give_rule_id=$rule_id
        ");

        return $res;

    }



    function  getRuleOperateLog($inter_id,$ident){

        $db_read = $this->load->database('iwide_r1',true);

        $res=$db_read->query("SELECT `log_id`,`record_time`,`admin`,`log_type`,`ip` FROM `iwide_hotel_admin_log` WHERE `inter_id`='{$inter_id}' AND `ident` = '{$ident}' ORDER BY record_time DESC")->result();

        return $res;

    }



    function countGiveCounpon($inter_id,$rule_id){

        $db_read = $this->load->database('iwide_r1',true);

        $sql = "
            SELECT
                SUM(amount) AS  total
            FROM
                `iwide_hotel_coupon_count`
            WHERE
                inter_id = '{$inter_id}'
            AND
                give_rule_id = {$rule_id}
        ";

        $res = $db_read->query($sql)->row_array();

        return $res;

    }


    function update_random_coupon_rules_times($inter_id,$rule_id){

        $sql = "
            UPDATE
                `iwide_hotel_coupon_grules`
            SET
                random_times = random_times + 1,
                all_times = all_times + 1
            WHERE
                inter_id = '{$inter_id}'
            AND
                rule_id = $rule_id
        ";

        $res = $this->db->query($sql);

        return $res;

    }


    function update_coupon_all_times($inter_id,$rule_id,$count){

        $sql = "
            UPDATE
                `iwide_hotel_coupon_grules`
            SET
                all_times = $count
            WHERE
                inter_id = '{$inter_id}'
            AND
                rule_id = $rule_id
        ";

        $res = $this->db->query($sql);

        return $res;

    }


    function update_coupon_rules_times($inter_id,$rule_id){

        $sql = "
            UPDATE
                `iwide_hotel_coupon_grules`
            SET
                all_times = all_times + 1
            WHERE
                inter_id = '{$inter_id}'
            AND
                rule_id = $rule_id
        ";

        $res = $this->db->query($sql);

        return $res;

    }



    function check_order_coupons($inter_id,$rule_id,$order_id){

        $db = $this->load->database('iwide_r1',true);

        $db->where('inter_id',$inter_id);
        $db->where('rule_id',$rule_id);
        $db->where('orderid',$order_id);

        return $db->get('iwide_hotel_order_coupons')->result_array();

    }




}