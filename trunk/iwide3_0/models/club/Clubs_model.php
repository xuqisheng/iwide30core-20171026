<?php
class Clubs_model extends MY_Model {
    function __construct() {
        parent::__construct ();
    }
    const TAB_CLUB_STA = 'club_staff';
    const TAB_HOTEL_STA = 'hotel_staff';
    const TAB_CLUB_LIST = 'club_list';
    const TAB_CLUB_CUSTOMER = 'club_customer';
    const TAB_FANS = 'fans';
    const TAB_HPI = 'hotel_price_info';
    const TAB_HPS = 'hotel_price_set';
    const TAB_HOTEL_ROOM = 'hotel_rooms';
    const TAB_PUB = 'publics';
    const TAB_QRCODE = 'qrcode';
    const TAB_ORDER_ITEMS = 'hotel_order_items';
    const TAB_ORDERS = 'hotel_orders';
    const TAB_CLUB_CONFIG = 'hotel_club_config';

    function _load_db() {
        return $this->db;
    }

    function get_hotel_protrol_price_codes($inter_id, $hotel_id, $status = 1) {    //获取公众下的某个酒店所有协议价类型价格代码
        $db = $this->load->database('iwide_r1',true);
        $db->select ( 'i.*' );
        $db->from ( self::TAB_HPI . ' i' );
        $db->join ( self::TAB_HPS . ' s', 'i.inter_id=s.inter_id and i.price_code=s.price_code' );
        $db->where ( 'i.type', 'protrol' );
        $db->where ( 's.inter_id', $inter_id );
        $db->where ( 'i.status', 1 );
        isset ( $status ) ? $db->where_in ( 's.status', explode ( ',', $status ) ) : $db->where ( 's.status', 1 );
        $db->where ( 's.hotel_id', $hotel_id );
        $db->group_by ( 's.price_code' );

        return $db->get ()->result_array ();
    }

    function get_all_price_codes($inter_id, $status = 1) {   //获取公众号下所有协议价类型价格代码
        $db = $this->load->database('iwide_r1',true);
        $db->where ( 'inter_id', $inter_id );
        $db->where ( 'type', 'protrol' );
        isset ( $status ) ? $db->where_in ( 'status', explode ( ',', $status ) ) : $db->where_in ( 'status', array (
            1,
            2
        ) );
        $result = $db->get ( self::TAB_HPI )->result_array ();
        $data = array ();
        foreach ( $result as $r ) {
            $data [$r ['price_code']] = $r;
        }
        return $data;
    }

    function check_club($openid,$status=1) {    //检测社群客权限是否存在
        $db = $this->load->database('iwide_r1',true);
        $db->where ( 'openid', $openid );
        $db->where ( 'status', $status );
        $result = $db->get ( self::TAB_CLUB_STA )->row_array ();

        return $result;
    }


    function getHotelStaff($inter_id,$openid,$status=2) {    //酒店员工信息
        $db = $this->load->database('iwide_r1',true);
        $db->where ( 'inter_id', $inter_id );
        $db->where ( 'openid', $openid );
        $db->where ( 'status', $status );
        $result = $db->get ( self::TAB_HOTEL_STA )->row_array ();

        return $result;
    }


    function getHeadImg($openid) {    //获取头像地址
        $db = $this->load->database('iwide_r1',true);
        $db->select('headimgurl');
        $db->where ( 'openid', $openid );
        $result = $db->get ( self::TAB_FANS )->row_array ();

        if(!empty($result)){
            return $result['headimgurl'];
        }

        return $result;
    }


    function staff_club($openid) {    //分销员的社群客列表

        $db = $this->load->database('iwide_r1',true);
        $db->where ( 'openid', $openid );
//        $db->order_by ( 'status ASC' );
        $db->order_by ( 'create_time DESC' );
        $db->order_by ( 'hotel_id ASC' );
        $result = $db->get ( self::TAB_CLUB_LIST )->result_array ();

        if(empty($result)){
            return false;
        }else{
            return $result;
        }

    }


    function getHotelInfo($inter_id){   //公众号下的酒店信息，包括酒店名称与价格代码

        $this->load->model('hotel/Hotel_model');
        $this->load->model ( 'hotel/Price_code_model' );
        $hotel = $this->Hotel_model->get_all_hotels ($inter_id);
        $codes = $this->get_all_price_codes( $inter_id );

        $hotel_name=array();
        $price_code=array();

        if(!empty($hotel)){
            foreach($hotel as $arr){
                $hotel_name[$arr['hotel_id']]=$arr['name'];
            }
        }

        $result['hotel_name']=$hotel_name;
        $result['price_code']=$codes;

        return $result;

    }


    function check_customer($openid,$club_id) {    //验证是否已经加入了该社群客
        $db = $this->load->database('iwide_r1',true);
        $db->where ( 'openid', $openid );
        $db->where ( 'club_id', $club_id );
        $result = $db->get ( self::TAB_CLUB_CUSTOMER )->row_array ();

        return $result;
    }


    function count_customer($club_id) {    //统计销售员已经开通了的社群客

        $db = $this->load->database('iwide_r1',true);

        $sql="SELECT count('customer_id') as total FROM `iwide_club_customer` WHERE club_id={$club_id}";

        $result= $db->query($sql)->row_array();

        return $result['total'];
    }


    function check_club_validated($club_id,$status=1){   //验证社群客有效性，包括有效时间与人数上限

        $result = $this->get_club_by_id($club_id,$status);

        if(!empty($result)){

            $valid_time=explode('-',$result['valid_time']);

            if(isset($valid_time[0])&&isset($valid_time[1])){

                $s_time=strtotime($valid_time[0]);
                $e_time=strtotime($valid_time[1]);
                $now=time();

                if($now>=$s_time && $now< ($e_time + 86400)){

                    $now_amount=$this->count_customer($club_id);

                    if($now_amount<$result['limited_amount']){
                        return 3;  //社群客有效，可以加入
                    }else{
                        return 2;  //超过人数
                    }
                }elseif($now<$s_time){
                    return 5;  //未到期
                }else{
                    return 1;    //超过有效期
                }
            }else{
                return 4;   //社群客有效期出错
            }
        }else{
            return 0;     //不存在该社群组织，出错
        }

    }


    function check_staff_validated($inter_id,$qrcode_id){     //验证社群客销售员有效性，包括有状态与开通个数上限

        $db = $this->load->database('iwide_r1',true);
        $db->where ( 'qrcode_id', $qrcode_id );
        $db->where ( 'inter_id', $inter_id );
        $result = $db->get ( self::TAB_CLUB_STA )->row_array();

        if($result['status']==1&&$result['limited_amount']>$result['amount']){
            return true;
        }else{
            return false;
        }
    }


    function new_club_list($params,$inter_id,$hotel=NULL,$status=0){    //新增社群客

        $db = $this->_load_db ();

        $data=array();

        $b_time=str_replace('-','',$params['b_time']);
        $e_time=str_replace('-','',$params['e_time']);

        $create_time=date('Y-m-d H:i:s');


        if(isset($params['price_code']) && !empty($params['price_code']))$price_code = $params['price_code'];else $price_code='';
        if(isset($params['soma_code']) && !empty($params['soma_code']))$soma_code = $params['soma_code'];else $soma_code='';

        $data=array(
            'valid_time'=>$b_time.'-'.$e_time,
            'limited_amount'=>$params['amount'],
            'club_name'=>$params['name'],
            'price_code'=>$price_code,
            'soma_code'=>$soma_code,
            'inter_id'=>$inter_id,
            'id'=>$params['qrcode_id'],
            'hotel_id'=>$hotel,
            'create_time'=>$create_time,
            'update_time'=>$create_time,
            'openid'=>$params['openid'],
            'status'=>$status
        );

        $db->trans_begin();

        $db->insert ( self::TAB_CLUB_LIST, $data );

        $db->query("UPDATE `iwide_club_staff` SET amount=amount+1 WHERE qrcode_id={$params['qrcode_id']} AND inter_id='{$inter_id}'");

        $result=$this->check_club($params['openid']);

        if ($db->trans_status () === FALSE) {
            $$db->trans_rollback ();

            return false;
        }

        $db->trans_complete();

        if(!empty($result)){
            return $result;
        }else{
            return false;
        }


    }


    function club_info($inter_id,$club_id){   //社群客信息

        $db = $this->load->database('iwide_r1',true);
        $db->where ( 'inter_id', $inter_id );
        $db->where ( 'club_id', $club_id );
        $result = $db->get ( self::TAB_CLUB_LIST )->row_array();

        return $result;

    }


    public  function interIdMulty($inter_id){   //酒店单体还是集团

        $db = $this->load->database('iwide_r1',true);
        $db->select('is_multy');
        $db->where ( 'inter_id', $inter_id );
        $result = $db->get ( self::TAB_PUB )->row_array();

        return $result['is_multy'];

    }


    public function join_club($data,$inter_id,$openid,$status=1){     //扫码加入社群客

        $create_time=date('Y-m-d H:i:s');

        $add_data=array(
            'name'=>$data['name'],
            'tel'=>$data['tel'],
            'club_id'=>$data['cid'],
            'apply_time'=>$create_time,
            'update_time'=>$create_time,
            'status'=>$status,
            'inter_id'=>$inter_id,
            'openid'=>$openid,
        );

        $db = $this->_load_db ();

        $db->trans_begin();

        $db->insert ( self::TAB_CLUB_CUSTOMER, $add_data );

        $db->query("UPDATE `iwide_club_list` SET amount=amount+1 WHERE club_id={$data['cid']}");

        if ($db->trans_status () === FALSE) {
            $$db->trans_rollback ();
            return false;
        }

        $this->load->model ( 'plugins/Template_msg_model' );

        $club_info = $this->get_club_by_id($data['cid']);

        if($club_info){

            //激活社群客提醒（给用户的）
            $club_staff = $this->check_club($club_info['openid']);
            $params=array(
                'openid'=>   $openid,
                'keyword1'=>    $club_info['club_name'],
                'keyword2'=>   $club_staff['name'],
                'keyword3'=>    $club_info['valid_time']

            );
            $this->Template_msg_model->hotel_club_templates ($inter_id , $params , 'hotel_join_club' );

            //社群客成员加入通知：（给销售员的）
            $params=array(
                'openid'=>   $club_info['openid'],
                'keyword1'=>  $data['name'],
                'keyword2'=>   date('Y-m-d H:i:s',time()),

            );
            $this->Template_msg_model->hotel_club_templates ($inter_id,$params , 'hotel_new_customer' );

        }


        $db->trans_complete();

        return true;

    }




    public function checkByOpenid($params){   //验证是否已经加入了该社群客

        $db_read = $this->load->database('iwide_r1',true);

        $this->load->model ( 'hotel/Price_code_model' );

        $all_price_codes = $this->Price_code_model->get_price_codes($params['inter_id'],1);

        if(!empty($all_price_codes)){
            foreach($all_price_codes as $key =>$price_code_arr){
                if($price_code_arr['type']!='protrol'){
                    unset($all_price_codes[$key]);
                }
            }
        }

        $club_list = $db_read->query("SELECT
                                          t1.customer_id,t1.club_id,t1.openid as customer_openid,t1.tel,t1.name,t2.*
                                      FROM
                                            `iwide_club_customer` as t1,
                                            `iwide_club_list` as t2
                                      WHERE
                                            t1.openid='{$params['openid']}'
                                      AND
                                            t1.inter_id='{$params['inter_id']}'
                                      AND
                                            t1.club_id=t2.club_id
                                      AND
                                            t1.status = 1
                                      AND
                                      		t2.status = 1
                                      AND
                                            t1.inter_id = t2.inter_id
        ")->result();

        $res=array();
        $result=array();

        if(!empty($club_list)){
            foreach($club_list as $key => $arr){
                $temp_price_code = explode(',',$arr->price_code);
                foreach($temp_price_code as $temp_arr){
                    if(isset($all_price_codes[$temp_arr])){
                        $t_result = clone $arr;
                        $t_result->price_code = $temp_arr;
                        $t_result->price_name = $all_price_codes[$temp_arr]['price_name'];
                        $t_result->type = $all_price_codes[$temp_arr]['type'];
                        $result[] = $t_result;
                    }
                }
            }
        }


        if(!empty($result)){
            foreach($result as $key=>$arr){
               //验证社群客有效时间
                $valid_time=explode('-',$arr->valid_time);
                $s_time=strtotime($valid_time[0]);
                $e_time=strtotime($valid_time[1]) + 86400;
                $now=time();

                if($now>=$s_time && $now<$e_time){
                    $result[$key]->company_name=$result[$key]->club_name;
                    if($arr->hotel_id==0 || $arr->hotel_id==NULL){
                        $result[$key]->hotel_id=$params['hotel_id'];
                        $res[]=$result[$key];
                    }elseif($arr->hotel_id==$params['hotel_id']){
                        $res[]=$arr;
                    }else{
                        $hotels_id=explode(',',$arr->hotel_id);
                        foreach($hotels_id as $ids){
                            if($ids==$params['hotel_id']){
                                $res[]=$arr;
                                break;
                            }
                        }
                    }
                }
            }
        }

        return $res;
    }


    public function update_qrcode_info($inter_id,$club_id,$params){   //更新社群客信息

        $db = $this->_load_db ();
        $db->where ('club_id',$club_id );
        $db->where ('inter_id',$inter_id );
        $res=$db->update ( self::TAB_CLUB_LIST, $params );

        return $res;
    }


    function getClubStaffById($id,$inter_id) {    //获取社群客客人员信息
        $db = $this->load->database('iwide_r1',true);
        $db->where ( 'qrcode_id', $id );
        $db->where ( 'inter_id', $inter_id );
        $result = $db->get ( self::TAB_CLUB_STA )->row_array ();

        return $result;
    }

    function getHotesByIds($id,$inter_id,$status=1) {    //获取酒店列表
        $db = $this->load->database('iwide_r1',true);
        if($id==0 || empty($id)){
            $result = $db->query("SELECT name FROM `iwide_hotels` WHERE inter_id='{$inter_id}' AND status={$status}")->result();
        }else{
            $result = $db->query("SELECT name FROM `iwide_hotels` WHERE inter_id='{$inter_id}' AND hotel_id in ($id) AND status={$status}")->result();
        }
        if($result){
            return $result;
        }else{
            return false;
        }

    }

    function getUrlByOpenid($openid,$status=1) {    //获取社群客二维码信息
        $db = $this->load->database('iwide_r1',true);
        $db->select ( 'img_url');
        $db->select ( 'club_id');
        $db->select ( 'club_code');
        $db->where ( 'openid', $openid );
        $db->where ( 'status', $status );
        $result = $db->get ( self::TAB_CLUB_LIST )->result ();

        if(!empty($result)){
            $res=array();
            foreach($result as $arr){
                $res[$arr->club_code]=$arr->img_url;
            }
            return $res;
        }

        return false;
    }



    function follow_page($inter_id){       //获取提示关注页

        $db = $this->load->database('iwide_r1',true);
        $db->select ( 'follow_page');
        $db->where ( 'inter_id', $inter_id );

        $result = $db->get ( self::TAB_PUB )->row_array ();

        if($result){
            return $result['follow_page'];
        }else{
            return false;
        }

    }



    function get_wx_new_qrcode($inter_id,$intro,$keyword,$name,$id=NULL){

        $db = $this->load->database('iwide_r1',true);

        $this->load->model ( 'wx/access_token_model' );
        if(is_null($id)){
            $sql = "SELECT id,inter_id FROM iwide_qrcode WHERE inter_id='".$inter_id."'";
            $query = $db->query($sql)->row_array();
            if(!empty($query)){
                $sql = "INSERT INTO iwide_qrcode(`id`,`inter_id`) SELECT MAX(id)+1 as id,inter_id FROM iwide_qrcode WHERE inter_id='".$inter_id."'";
                $this->db->query($sql);
                $id = $this->db->insert_id();
                $this->db->where('id',$id);
                $this->db->where('inter_id',$inter_id);
                $this->db->update('qrcode',array(
                    'id'=>$id,
                    'intro'=>$intro,
                    'keyword'=>$keyword,
                    'name'=>$name,
                    'url'=>'',
                    'create_date'=>date('Y-m-d H:i:s')
                ));
            }else{
                $this->db->insert('qrcode',array('id'=>1,'intro'=>$intro,'keyword'=>$keyword,'name'=>$name,'inter_id'=>$inter_id,'url'=>'','create_date'=>date('Y-m-d H:i:s')));
                $id = $this->db->insert_id();
            }
        }

        $access_token = $this->access_token_model->get_access_token ( $inter_id );
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
        $qrcode = '{"action_name": "QR_LIMIT_SCENE","action_info": {"scene": {"scene_id": ' . $id . '}}';
        $output = $this->doCurlPostRequest ( $url, $qrcode );
        $jsoninfo = json_decode ( $output, true );
        if(isset($jsoninfo['errcode']) && ($jsoninfo['errcode'] == '40001' || $jsoninfo['errcode'] == '42001')){
            $access_token = $this->access_token_model->reflash_access_token ( $inter_id );
            $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
            $qrcode = '{"action_name": "QR_LIMIT_SCENE","action_info": {"scene": {"scene_id": ' . $id . '}}}';
        }
        $output = $this->doCurlPostRequest ( $url, $qrcode );
        $jsoninfo = json_decode ( $output, true );

        if (isset ( $jsoninfo ['url'] )){
//            $this->db->insert('qrcode',array('id'=>$id,'intro'=>$intro,'keyword'=>$keyword,'name'=>$name,'inter_id'=>$inter_id,'url'=>'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $jsoninfo ['ticket'],'create_date'=>date('Y-m-d H:i:s')));
            $this->db->where('id',$id);
            $this->db->where('inter_id',$inter_id);
            $this->db->update('qrcode',array(
                'url'=>'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $jsoninfo ['ticket']
            ));
            $jsoninfo['qrcode_id']=$id;
            return $jsoninfo;
        }else{
            return $jsoninfo;
        }
    }


    function getClubByQrcode($inter_id,$qrcode_id){   //根据二维码ID获取社群客信息
        $db = $this->load->database('iwide_r1',true);
        $db->where ( 'inter_id', $inter_id );
        $db->where ( 'qrcode_id', $qrcode_id );

        $result = $db->get ( self::TAB_CLUB_LIST )->row_array ();

        return $result;

    }



    function check_club_qrcode($inter_id,$qrcode_id){
        $db = $this->load->database('iwide_r1',true);
        $result=$db->query("SELECT `keyword` FROM `iwide_qrcode` WHERE id='{$qrcode_id}' AND inter_id='{$inter_id}'")->row_array();
        $this->db->insert('weixin_text',array('content'=>$result['keyword'],'edit_date'=>date('Y-m-d H:i:s')));

        if($result){
            return $result['keyword'];
        }else{
            return false;
        }

    }


    public function getPublicName($inter_id,$status=0){    //公众号

        $db = $this->load->database('iwide_r1',true);
        $db->select ('name' );
        $db->where ('inter_id',$inter_id );
        $db->where ('status',$status );
        $res=$db->get( self::TAB_PUB )->row_array();

        if($res){
            return $res['name'];
        }else{
            return false;
        }

    }


    public function getQrcodeTicket($inter_id,$qrcode_id){
        $db = $this->load->database('iwide_r1',true);
        $db->select ('url' );
        $db->where ('inter_id',$inter_id );
        $db->where ('id',$qrcode_id );
        $res=$db->get( self::TAB_QRCODE )->row_array();

        return $res['url'];

    }

    public function getPriceName($inter_id,$price_code,$status=1){    //价格代码名称

        $db = $this->load->database('iwide_r1',true);
        $db->select ('price_name' );
        $db->where ('inter_id',$inter_id );
        $db->where ('price_code',$price_code );
        $db->where ('status',$status );
        $res=$db->get( self::TAB_HPI )->row_array();

        if($res){
            return $res['price_name'];
        }else{
            return false;
        }

    }


    public function getClubByCode($inter_id,$code){    //根据随机串获取社群客信息
        $db = $this->load->database('iwide_r1',true);
        $db->where ('inter_id',$inter_id );
        $db->where ('club_code',$code );
        $res=$db->get( self::TAB_CLUB_LIST )->row_array();

        return $res;
    }


    private function  doCurlPostRequest($url, $requestString, $extra = array(), $timeout = 5){
        if($url == "" || $requestString == "" || $timeout <= 0){
            return FALSE;
        }
        $con = curl_init(( string )$url);
        curl_setopt($con, CURLOPT_HEADER, FALSE);
        curl_setopt($con, CURLOPT_POSTFIELDS, $requestString);
        curl_setopt($con, CURLOPT_POST, TRUE);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($con, CURLOPT_TIMEOUT, ( int )$timeout);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($con, CURLOPT_SSL_VERIFYHOST, 0);

        if(!empty ($extra) && is_array($extra)){
            $headers = array();
            foreach($extra as $opt => $value){
                if(strexists($opt, 'CURLOPT_')){
                    curl_setopt($con, constant($opt), $value);
                } elseif(is_numeric($opt)){
                    curl_setopt($con, $opt, $value);
                } else{
                    $headers [] = "{$opt}: {$value}";
                }
            }
            if(!empty ($headers)){
                curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
            }
        }
        $res = curl_exec($con);
// 	var_dump(curl_error($con));
        return $res;
    }


    public function getSalerByClubQrcode($inter_id,$qrcode_id){     //社群客分销人员的绩效开关

        $db = $this->load->database('iwide_r1',true);
        $res=$db->query(
            "SELECT
                t2.qrcode_id,t2.is_grade
            FROM
                `iwide_club_list` as t1,
                `iwide_club_staff` as t2
            WHERE
                t1.inter_id='{$inter_id}'
            AND
                t2.inter_id='{$inter_id}'
            AND
                t1.qrcode_id = '{$qrcode_id}'
            AND
                t1.id=t2.qrcode_id
             "
        )->row_array();

        return $res;
    }


    public function getClubOrders($inter_id,$clubs_csv,$type,$time='',$status=3){   //社群客产生的间夜（CSV）

        $db = $this->load->database('iwide_r1',true);

        if($time!=''){

            $time = date('Y-m-d',($time+86400));

            $condition=" AND MONTHNAME(t1.startdate) = MONTHNAME('{$time}') AND YEAR(t1.startdate) = YEAR('{$time}')";

        }else{

            if($type=='D'){
                $condition=' AND DAYOFYEAR(t1.startdate) = DAYOFYEAR(CURDATE()) AND YEAR(t1.startdate) = YEAR(CURDATE())';
            }elseif($type=='W'){
                $condition=' AND YEARWEEK(t1.startdate) = YEARWEEK(CURDATE())';
            }elseif($type=='M'){
                $condition=' AND MONTHNAME(t1.startdate) = MONTHNAME(CURDATE()) AND YEAR(t1.startdate) = YEAR(CURDATE())';
            }elseif($type=='A'){
                $condition='';
            }else{
                $condition='';
            }



        }


        $res=$db->query(
            "SELECT
                t1.roomname,t1.iprice,t1.orderid,t1.inter_id,t1.startdate,t1.enddate,t1.istatus,t1.club_id,t2.hotel_id,t2.name
            FROM
                iwide_hotel_order_items as t1,
                iwide_hotel_orders as t2
            WHERE
                t1.inter_id='{$inter_id}'
            AND
                t1.istatus in (2,3)
            AND
                t1.inter_id = t2.inter_id
            AND
                t1.orderid = t2.orderid
            AND
                t1.club_id in ($clubs_csv)".$condition

            )->result_array();

        return $res;

    }


    public function getClubOrdersById($inter_id,$club_id,$type,$time='',$status=3){   //社群客产生的间夜

        $db = $this->load->database('iwide_r1',true);
//        $db->select ('orderid','inter_id','startdate','enddate','istatus','club_id' );
//        $db->where ('inter_id',$inter_id );
//        $db->where ('club_id',$club_id );
//        $db->where ('istatus',$status );
//
//        $res=$db->get( self::TAB_ORDER_ITEMS )->result_array();

        if($type=='D'){
            $condition=' AND DAYOFYEAR(t1.startdate) = DAYOFYEAR(CURDATE()) AND YEAR(t1.startdate) = YEAR(CURDATE())';
        }elseif($type=='W'){
            $condition=' AND YEARWEEK(t1.startdate) = YEARWEEK(CURDATE())';
        }elseif($type=='M'){
            $condition=' AND MONTHNAME(t1.startdate) = MONTHNAME(CURDATE()) AND YEAR(t1.startdate) = YEAR(CURDATE())';
        }elseif($type=='A'){
            $condition='';
        }else{
            $condition='';
        }

        $res=$db->query(
            "SELECT
                t1.roomname,t1.orderid,t1.iprice,t1.inter_id,t1.startdate,t1.enddate,t1.istatus,t1.club_id,t2.hotel_id,t2.name
            FROM
                iwide_hotel_order_items as t1,
                iwide_hotel_orders as t2
            WHERE
                t1.inter_id='{$inter_id}'
            AND
                t1.istatus in (2,3)
            AND
                t1.inter_id = t2.inter_id
            AND
                t1.orderid = t2.orderid
            AND
                t1.club_id = $club_id
             ".$condition
        )->result_array();

        return $res;

    }


    public function getClubCustomers($inter_id,$club_id,$status=1){    //社群客成员列表

        $db = $this->load->database('iwide_r1',true);

        $res=$db->query(
            "SELECT
                t1.name,t1.update_time,t2.headimgurl
            FROM
                iwide_club_customer as t1,
                iwide_fans as t2
            WHERE
                t1.club_id = {$club_id}
            AND
                t1.status = $status
            AND
                t1.inter_id = '{$inter_id}'
            AND
                t1.inter_id = t2.inter_id
            AND
                t1.openid = t2.openid
             "
        )->result_array();

        return $res;
    }



    public function count_club_orders($inter_id,$club,$type,$status=3){   //社群客订单统计

        $db = $this->load->database('iwide_r1',true);

        $condition='';

        if($type=='part'){
            $club_con=' AND t1.club_id = '.$club;
        }elseif($type=='all'){
            $club_con=' AND t1.club_id in ('.$club.')';
        }

        $sql="SELECT
                t1.id,t1.startdate,t1.enddate
            FROM
                iwide_hotel_order_items as t1,
                iwide_hotel_orders as t2
            WHERE
                t1.inter_id='{$inter_id}'
            AND
                t1.istatus in (2,3)
            AND
                t1.inter_id = t2.inter_id
            AND
                t1.orderid = t2.orderid
             ".$club_con;

        $sql_a=$sql;
        $res['all']=$this->count_night($db->query($sql_a)->result_array());

        $condition=' AND DAYOFYEAR(t1.startdate) = DAYOFYEAR(CURDATE()) AND YEAR(t1.startdate) = YEAR(CURDATE())';
        $sql_d=$sql.$condition;
        $res['day']=$this->count_night($db->query($sql_d)->result_array());

        $condition=' AND YEARWEEK(t1.startdate) = YEARWEEK(CURDATE())';
        $sql_w=$sql.$condition;
        $res['week']=$this->count_night($db->query($sql_w)->result_array());

        $condition=' AND MONTHNAME(t1.startdate) = MONTHNAME(CURDATE()) AND YEAR(t1.startdate) = YEAR(CURDATE())';
        $sql_m=$sql.$condition;
        $res['month']=$this->count_night($db->query($sql_m)->result_array());

        return $res;


    }


    function count_night($orders){   //根据订单返回间夜数

        if($orders){
            foreach($orders as $arr){
                $time = strtotime($arr['enddate'])-strtotime($arr['startdate']);
                $night = ceil($time/86400);
                if(!isset($total)){
                    $total=$night;
                }else{
                    $total=$total + $night;
                }
            }
        }else{

            $total = 0;
        }
        return $total;
    }



    function get_all_club_orders($inter_id,$type='D',$status=3){    //获取公众号所有订单

        $db = $this->load->database('iwide_r1',true);

        $sql="SELECT
                t1.id,t1.orderid,t1.startdate,t1.enddate,t1.club_id,t2.id qrcode_id
            FROM
                iwide_hotel_order_items t1,
                iwide_club_list t2
            WHERE
                t1.inter_id='{$inter_id}'
            AND
                t2.inter_id='{$inter_id}'
            AND
                t1.istatus in (2,3)
            AND
                t1.club_id !=''
            AND
                t1.club_id =t2.club_id";


        if($type=='day'){
            $condition=' AND DAYOFYEAR(t1.startdate) = DAYOFYEAR(CURDATE()) AND YEAR(t1.startdate) = YEAR(CURDATE())';
        }elseif($type=='month'){
            $condition=' AND MONTHNAME(t1.startdate) = MONTHNAME(CURDATE()) AND YEAR(t1.startdate) = YEAR(CURDATE())';
        }elseif($type=='all'){
            $condition='';
        }else{
            $condition='';
        }

        $sql=$sql.$condition;

        $res = $db->query($sql)->result_array();

        if($res){
            $orders=array();
            foreach($res as $arr){
                $orders[$arr['club_id']][]=$arr;
                $staff_orders[$arr['qrcode_id']][] = $arr;
            }

            foreach($orders as $club_id => $order){
                $count[$club_id]=$this->count_night($order);
            }

            foreach($staff_orders as $qrcode_id => $order){
                $staff_count[$qrcode_id]=$this->count_night($order);
            }

            $return =array(
                'orders'=>$orders,
                'count'=>$count,
                'staff_count'=>$staff_count
            );

            return $return;

        }else{

            return false;

        }

    }


    function  getAllClubStaff($inter_id,$status=1){   //公众号下所有的社群客销售员

        $db = $this->load->database('iwide_r1',true);

        $result=$db->query(
            "SELECT
                t1.*,t2.hotel_name,t3.headimgurl
            FROM
                iwide_club_staff as t1,
                iwide_hotel_staff as t2,
                iwide_fans as t3
            WHERE
                t1.inter_id = '{$inter_id}'
            AND
                t1.status = $status
            AND
                t1.inter_id = t2.inter_id
            AND
                t1.qrcode_id = t2.qrcode_id
            AND
                t1.openid = t3.openid
            GROUP BY
                t2.qrcode_id
             "
        )->result_array();

        return $result;

    }


    function getAllClub($inter_id){   //公众号下所有的社群客club_id

        $db = $this->load->database('iwide_r1',true);
        $db->where ( 'inter_id', $inter_id );
        $result = $db->get ( self::TAB_CLUB_LIST )->result_array();

        $res=array();

        if($result){
            foreach($result as $arr){
                $res[$arr['id']][]=$arr['club_id'];
            }
        }

        return $res;

    }


    function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){   //排序
        if(is_array($arrays)){
            foreach ($arrays as $array){
                if(is_array($array)){
                    $key_arrays[] = $array[$sort_key];
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
        array_multisort($key_arrays,$sort_order,$sort_type,$arrays);
        return $arrays;
    }


    function getNearYear($num=12){    //获取最近的月份

        $now = time();

        $res = array();

        for($i=0;$i<$num;$i++){

            $year = date("Y",time());

            $month = date("m",time()) - $i;

            if($month <= 0){

                $year= $year-1;

                $month = $month + 12;
            }

            if($month<10 && $month >0){

                $month = '0'.$month;
            }

            $res[]=array(
                'info'=>strtotime($year.$month.'00'),
                'name'=>$year.'年'.$month.'月',
                'count'=>0

            );

        }

       return $res;

    }


    function getMyClubOrder($inter_id,$type='D',$status=3){    //获取公众号所有订单

        $db = $this->load->database('iwide_r1',true);

        $sql="SELECT
                id,orderid,startdate,enddate,club_id
            FROM
                iwide_hotel_order_items
            WHERE
                inter_id='{$inter_id}'
            AND
                istatus in (2,3)
            AND
                club_id !=''";


        if($type=='day'){
            $condition=' AND DAYOFYEAR(startdate) = DAYOFYEAR(CURDATE()) AND YEAR(startdate) = YEAR(CURDATE())';
        }elseif($type=='month'){
            $condition=' AND MONTHNAME(startdate) = MONTHNAME(CURDATE()) AND YEAR(startdate) = YEAR(CURDATE())';
        }elseif($type=='all'){
            $condition='';
        }else{
            $condition='';
        }

        $sql=$sql.$condition;

        $res = $db->query($sql)->result_array();

        if($res){
            $orders=array();
            foreach($res as $arr){
                $orders[$arr['club_id']][]=$arr;
            }

            foreach($orders as $club_id => $order){
                $count[$club_id]=$this->count_night($order);
            }

            $return =array(
                'orders'=>$orders,
                'count'=>$count
            );

            return $return;

        }else{

            return false;

        }

    }


    function count_clubs_by_type($inter_id,$key,$status=1){  //统计类型社群客

        $db = $this->load->database('iwide_r1',true);

        $sql="SELECT count(*) as total FROM `iwide_club_staff` WHERE club_type='{$key}' AND status = $status AND inter_id = '{$inter_id}'";

        $result= $db->query($sql)->row_array();

        return $result['total'];


    }

    function get_club_config($inter_id,$key,$status=1,$type='views'){    //社群客配置

        $db = $this->load->database('iwide_r1',true);

        $db->where ( 'inter_id', $inter_id );
        $db->where ( 'status', $status );
        $db->where ( 'key', $key );
        $db->where ( 'type', $type );

        $result = $db->get ( self::TAB_CLUB_CONFIG )->row_array ();

        return $result;

    }



    function get_all_club($inter_id,$remark=2){

        $db = $this->load->database('iwide_r1',true);
        $db->select('club_name');
        $db->where(array('inter_id'=>$inter_id,'remark'=>$remark));
        $result=$db->get('club_list')->result_array();

        return $result;

    }

    function count_p_mem($inter_id,$remark=2){    //统计有特定标识的社群客

        $db = $this->load->database('iwide_r1',true);

        $sql = "SELECT count(*) as total FROM `iwide_club_list` where inter_id = '{$inter_id}' and remark = $remark";

        $result= $db->query($sql)->row_array($sql);

        return $result;

    }



    function count_club_list($inter_id,$status=1){     //统计公众号下所有社群客

        $db = $this->load->database('iwide_r1',true);

        $sql = "SELECT count(*) as total FROM `iwide_club_list` where inter_id = '{$inter_id}' and status='{$status}'";

        $result= $db->query($sql)->row_array($sql);

        return $result;

    }


    function count_all_club_customer($inter_id){  //统计公众号下所有的社群客成员

        $db = $this->load->database('iwide_r1',true);

        $sql = "SELECT count(*) as total FROM `iwide_club_customer` where inter_id = '{$inter_id}'";

        $result= $db->query($sql)->row_array($sql);

        return $result;

    }



    function get_club_by_id($club_id,$status=1){  //根据社群客ID获取社群客

        $db = $this->load->database('iwide_r1',true);
        $db->where ( 'club_id', $club_id );
        $db->where ( 'status', $status );

        $result = $db->get ( self::TAB_CLUB_LIST )->row_array();

        return $result;


    }


    function get_club_grade_ext($inter_id,$type,$offset=''){

        $db = $this->load->database('iwide_r1',true);

        $sql = "
            SELECT
                t2.inter_id,t2.saler,SUM(t2.grade_total) grade_total,SUM(t1.iprice) total_price,t1.club_id
            FROM
                `iwide_distribute_grade_all` t2,
                `iwide_hotel_order_items` t1
            WHERE
                t1.inter_id = '{$inter_id}'
            AND
                t1.inter_id = t2.inter_id
            AND
                t2.grade_table = 'iwide_hotels_order'
            AND
                t2.grade_typ = 2
            AND
                t1.id = t2.grade_id
            AND
                t2.status in (1,2,5)
        ";

        if($type=='day'){
            $condition=' AND DAYOFYEAR(t1.startdate) = DAYOFYEAR(CURDATE()) AND YEAR(t1.startdate) = YEAR(CURDATE())';
        }elseif($type=='month'){
            $condition=' AND MONTHNAME(t1.startdate) = MONTHNAME(CURDATE()) AND YEAR(t1.startdate) = YEAR(CURDATE())';
        }elseif($type=='all'){
            $condition='';
        }else{
            $condition='';
        }

        if(empty($offset)){
            $sql=$sql.$condition.' GROUP BY t2.saler';
        }else{
            $sql=$sql.$condition.' GROUP BY t1.'.$offset;
        }



        $res = $db->query($sql)->result_array();
        $grades = array();
        if(!empty($res)){
            foreach($res as $arr){
                $grades[$arr['saler']] = $arr;
            }
        }
        return $grades;
    }


        function get_club_dist_orders($inter_id,$type){

        $db = $this->load->database('iwide_r1',true);

            $sql = "
            SELECT
                t1.id,t1.orderid,t1.startdate,t1.enddate,t1.club_id
            FROM
                `iwide_distribute_grade_all` t2,
                `iwide_hotel_order_items` t1
            WHERE
                t1.inter_id = '{$inter_id}'
            AND
                t1.inter_id = t2.inter_id
            AND
                t2.grade_table = 'iwide_hotels_order'
            AND
                t2.grade_typ = 2
            AND
                t1.id = t2.grade_id
            AND
                t2.status in (1,2,5)
        ";

            if($type=='day'){
                $condition=' AND DAYOFYEAR(t1.startdate) = DAYOFYEAR(CURDATE()) AND YEAR(t1.startdate) = YEAR(CURDATE())';
            }elseif($type=='month'){
                $condition=' AND MONTHNAME(t1.startdate) = MONTHNAME(CURDATE()) AND YEAR(t1.startdate) = YEAR(CURDATE())';
            }elseif($type=='all'){
                $condition='';
            }else{
                $condition='';
            }

            $sql=$sql.$condition;

        $res = $db->query($sql)->result_array();

            if($res){
                $orders=array();
                foreach($res as $arr){
                    $orders[$arr['club_id']][]=$arr;
                }

                foreach($orders as $club_id => $order){
                    $count[$club_id]=$this->count_night($order);
                }

                $return =array(
                    'orders'=>$orders,
                    'count'=>$count
                );

                return $return;

            }else{

                return false;

            }

        }


    function getClubConfigById($inter_id,$config_id){

        $db = $this->load->database('iwide_r1',true);
        $db->where ( 'inter_id', $inter_id );
        $db->where ( 'id', $config_id );

        return $db->get(self::TAB_CLUB_CONFIG)->row_array();

    }


    function  upgrade_club($inter_id,$openid,$member_code){    //升级对应的社群客

        $res = $this->get_club_config($inter_id,'normal',1,'default_staff');
        $this->load->model('club/Club_model');
        $this->load->model('club/Club_list_model');

        $info = array(
            'code' => 1,
            'message'=>'没有社群客配置，不用升级'
        );

        if(empty($res)){   //没有配置
            return $info;
        }

        $club_config = json_decode($res['value']);


        $res = $this->match_club_config($club_config,$member_code);


        if(!isset($res->amount) || !isset($res->price_code)){
            $info['message']='会员等级没有对应价格代码';
            return $info;
        }

        $staff = $this->getHotelStaff($inter_id,$openid);
        $club_staff = $this->check_club($openid);
        $clubs = $this->staff_club($openid);

        if(empty($staff)){
            $info['message']='还不是分销员';
            return $info;
        }

        if(empty($club_staff)){
            $info['message']='还不是社群客销售员';
            return $info;
        }

        if(!$clubs){
            $info['message']='还没有开通对应的社群客';
            return $info;
        }


        $staff_updata['club_price_code'] = $res->price_code;
        $staff_updata['auth_price_code'] = $res->price_code;
        $staff_updata['status'] = 1;
        $staff_updata['update_time'] = date('Y-m-d H:i:s',time());;

        $staff_data['qrcode_id'] = $club_staff['qrcode_id'];
        $staff_data['inter_id'] = $club_staff['inter_id'];
        $staff_data['openid'] = $club_staff['openid'];

        $update_res = $this->Club_model->update_club($staff_data,$staff_updata);


        foreach($clubs as $club){

            $club_updata['price_code'] = $res->price_code;
            $club_updata['limited_amount'] = $res->amount;
            $club_updata['status'] = 1;
            $club_updata['update_time'] = date('Y-m-d H:i:s',time());;

            $this->Club_list_model->save_club($inter_id,$club['club_id'],$club_updata);
        }


        if($update_res){
            $info['code']=2;
            $info['message']='升级成功';
        }

        return $info;



    }


    function match_club_config($club_config,$member_code){   //匹配对应会员等级的社群客配置
        $res = array();
        foreach($club_config as $arr){
            if($arr->member_lv==$member_code){
                return $arr;
            }
        }
        return $res;
    }


    function upgrade_club_queue($inter_id,$openid,$to_lv,$ori_lv='',$member_info_id){   //升级会员插入队列

        $res = $this->get_club_config($inter_id,'normal',1,'default_staff');

        if(empty($res)){   //没有配置
            return;
        }

        $staff = $this->getHotelStaff($inter_id,$openid);
        $club_staff = $this->check_club($openid);
        $clubs = $this->staff_club($openid);

        if(!empty($staff) && !empty($club_staff) && $clubs){

            $this->load->model ( 'hotel/user/User_notify_model' );
            if($this->User_notify_model->add_queue ( $inter_id, $openid, 'club_member_levelup', array (
                'ex_data' => array (
                    'to_lv' => $to_lv, //升到的级别
                    'ori_lv' => $ori_lv //原有的级别
                ),
                'sub_ident' => $member_info_id
            ), FALSE )){
                return true;
            };

        }

    }


    function show_club_reg($inter_id,$openid,$member_code){    //会员中心验证是否显示注册社群客入口

        $res = $this->get_club_config($inter_id,'normal',1,'default_staff');

        $this->load->model('common/Webservice_model');
        $this->Webservice_model->add_webservice_record($inter_id, 'yasite', 'club/show_club_reg', json_encode(array('inter_id'=>$inter_id,'openid'=>$openid,'lev'=>$member_code)),$res,'webservice', time(), microtime (), '');

        $info = array(
            'status'=>2,
            'message'=>'没有配置',
            'config_id'=>0
        );

        if(empty($res)){   //没有配置
            return $info;
        }

        $club_config = json_decode($res['value']);

        $match_res = $this->match_club_config($club_config,$member_code);

        if(empty($match_res)){
            $info['message']='会员没有对应的社群客价格代码';
            return $info;
        }


        $staff = $this->getHotelStaff($inter_id,$openid);
        $club_staff = $this->check_club($openid);
        $clubs = $this->staff_club($openid);

        if(empty($staff) && empty($club_staff) && !$clubs){
            $info['message']='可以注册';
            $info['status']=1;
            $info['config_id']=$res['id'];
            return $info;
        }

        return $info;


    }

    function get_soma_code($inter_id){

        $this->load->helper ( 'common' );
        $this->load->model ( 'wx/Publics_model' );

        $publics = $this->Publics_model->get_public_by_id($inter_id);
        $domain = $publics['domain'];

        $params = array(
            'id'=>$inter_id
        );

        $url = 'http://'.$domain.'/soma/inner_api/scope_list';

        $soma_club = doCurlGetRequest($url,$params);

        return json_decode($soma_club);


    }


    function check_group_club_validated($inter_id,$club_ids){   //验证社群客有效性，包括有效时间与人数上限

        $club_list = $this->get_csv_clubs($inter_id,$club_ids);
        $res = array();

        if(!empty($club_list)){

            $club_ids = implode(',',$club_ids);
            $now_amount=$this->count_customer_by_ids($inter_id,$club_ids);
            $count_customer = array();
            if(!empty($now_amount)){
                foreach($now_amount as $temp){
                    $count_customer[$temp['club_id']] = $temp['total'];
                }
            }
            foreach($club_list as $result){
                $valid_time=explode('-',$result['valid_time']);
                if(isset($valid_time[0])&&isset($valid_time[1])){
                    $s_time=strtotime($valid_time[0]);
                    $e_time=strtotime($valid_time[1]);
                    $now=time();
                    if($now>=$s_time && $now< ($e_time + 86400)){
                        if(!isset($count_customer[$result['club_id']]) || $count_customer[$result['club_id']]<$result['limited_amount']){
                            $res[$result['club_id']]['valid'] = 3;  //社群客有效，可以加入
                        }else{
                            $res[$result['club_id']]['valid'] = 2;  //超过人数
                        }
                    }elseif($now<$s_time){
                        $res[$result['club_id']]['valid'] = 5;  //未到期
                    }else{
                        $res[$result['club_id']]['valid'] = 1;    //超过有效期
                    }
                }else{
                    $res[$result['club_id']]['valid'] = 4;  //社群客有效期出错
                }

            }
        }

        return $res;

    }


    function get_csv_clubs($inter_id,$club_ids){   //根据社群客ID获取社群客

        $db = $this->load->database('iwide_r1',true);
        $db->where ( 'inter_id', $inter_id );
        $db->where_in ( 'club_id', $club_ids );

        $result = $db->get ( self::TAB_CLUB_LIST )->result_array();

        return $result;


    }


    function count_customer_by_ids($inter_id,$csv_clubs_id) {    //统计销售员已经开通了的社群客

        $db = $this->load->database('iwide_r1',true);

        $sql="SELECT count('customer_id') as total,club_id FROM `iwide_club_customer` WHERE inter_id = '{$inter_id}' AND club_id in ({$csv_clubs_id}) group by club_id";

        $result= $this->db->query($sql)->result_array();

        return $result;
    }

}