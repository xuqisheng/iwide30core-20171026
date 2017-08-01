<?php

/**
 * Created by knight.
 * User: ibuki
 * Date: 16/7/30
 * Time: 下午9:25
 */
class Member_related_model extends MY_Model_Member {

    const STATUS_ISUSE = 'f';

    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function table_name()
    {
        return 'member_card';
    }

    /**
     * 返回用户优惠劵表的主键
     * @return string
     */
    public function table_primary_key(){
        return 'member_card_id';
    }

    /**
     * 获取优惠劵表
     * @return string
     */
    public function member_card_table_name(){
        return 'member_card';
    }

    //回滚优惠劵状态检测
    public function can_rollback_status(){
        return array(self::STATUS_ISUSE);
    }

    /**
     * 获取即将过期的优惠劵
     */
    public function get_expired_coupon( $limit=100 ){
        if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
            //生产环境往后7天时间
            $expired_time = strtotime('+1 week');
        } else {
            //其他环境往后一天时间
            $expired_time= strtotime('+1 day');
        }

        $select = "a.member_card_id,a.card_id,a.inter_id,a.open_id,a.member_id,a.member_info_id,a.is_use,a.expire_time,a.is_send,b.title";
        $join = "iwide_card b ON b.card_id = a.card_id";
        $where = "a.expire_time BETWEEN ".time()." AND {$expired_time} AND a.is_use = '".self::STATUS_ISUSE."'";

        $m_ct = $this->member_card_table_name();
        $sql = "SELECT {$select} FROM iwide_{$m_ct} a INNER JOIN {$join} WHERE {$where} ORDER BY expire_time asc LIMIT {$limit}";
        $list = $this->_shard_db()->query($sql)->result_array();
        if(empty($list)) return false;
        $member_info_ids = array();
        $member_card_list = array();
        foreach ($list as $v){
            $member_info_ids[] = $v['member_info_id'];
            $key = $v['open_id'].'_@_'.$v['card_id'];
            $member_card_list[$key] = $v;
        }

        $member_info_ids = array_unique($member_info_ids);
        $sql = "SELECT member_info_id,open_id FROM iwide_member_info WHERE member_mode = 1 AND member_info_id in (".implode(',',$member_info_ids).")";
        $member1 = $this->_shard_db()->query($sql)->result_array();


        $sql = "SELECT member_info_id,open_id FROM iwide_member_info WHERE member_mode = 2 AND member_info_id in (".implode(',',$member_info_ids).") AND is_login = 't'";
        $member2 = $this->_shard_db()->query($sql)->result_array();
        $member_info = array_merge($member1,$member2);
        if(empty($member_info)) return false;
        $member_infos = array();
        foreach ($member_info as $mv){
            $member_infos[$mv['open_id']] = $mv;
        }

        foreach ($member_card_list as $k => $value){
            $ks = explode('_@_',$k);
            $openid = $ks[0];
            if(empty($member_infos[$openid])) unset($member_card_list[$k]);
        }

        return $member_card_list;
    }

    /**
     * get赠送超时的优惠券
     */
    public function get_giving_expired_coupon($limit = 1000 , $day = 1){
        $expire_date = time() - (86400 * $day) ;
        $m_ct = $this->member_card_table_name();
        $list = $this->_shard_db()
            ->select($m_ct.'.*')
            ->where(array('is_giving_time < ' => $expire_date , 'is_giving '=>'t','is_use'=> 'f','is_useoff '=>'f' ))
            ->order_by('is_giving_time asc')->limit($limit)
            ->get($m_ct)->result_array();
        return $list;

    }

    /**
     * rollback过期券
     */
    public function rollback_giving_coupon($member_card,$day = 1){
        $inter_id = $member_card['inter_id'];
        $member_card_id = $member_card['member_card_id'];

        $expire_date = time() - (86400 * $day) ;
        $this->_shard_db(true)->trans_begin ();
        $this->_shard_db(true)
            ->where(array('inter_id'=>$inter_id,'member_card_id'=>$member_card_id,'is_giving_time < ' => $expire_date , 'is_giving '=>'t','is_use'=> 'f','is_useoff '=>'f'))
            ->update($this->member_card_table_name(),array('is_giving'=>'f','is_giving_time' => 0));
        $res = $this->_shard_db(true)->affected_rows();


        $this->_shard_db(true)->trans_complete ();

        if ($this->_shard_db(true)->trans_status () === FALSE) {
            $this->_shard_db(true)->trans_rollback ();
            return  -1;
        } else {
            $this->_shard_db(true)->trans_commit ();
            return  $res;
        }
    }

    /*赠送回滚记录增加*/
    public function rollback_giving_coupon_record($member_card){
        $data = array(
            'card_id' => $member_card['card_id'],
            'inter_id' => $member_card['inter_id'],
            'member_card_id' => $member_card['member_card_id'],
            'member_info_id_accept' => $member_card['member_info_id'],
            'member_info_id_give' => 0,
            'module' => 'vip',
            'scene' => 'system return',
            'remark' => '赠送超时回退',
            'createtime' => time(),

        );
        $res = $this->_shard_db(true)->insert('iwide_card_give', $data);
        if($res) return $res;
        return false;
    }

    /**
     * 获取微信消息模板
     * @param null $inter_id 微信酒店集团ID
     * @param int $type 模板消息类型
     * @return array
     */
    public function member_card_temp($inter_id = null,$type = 1){
        if(!isset($inter_id) || empty($inter_id)) return array();
        $member_template_table = 'member_message_template';
        $count = $this->_shard_db()
            ->where(array('inter_id'=>$inter_id,'type'=>$type))
            ->get($member_template_table)->num_rows();
        if($this->input->get('debug')=='1') echo $this->_shard_db()->last_query();
        return $count;
    }

    /**
     * 修改发送模版消息状态
     * @param null $inter_id 微信酒店集团ID
     * @param null $open_id 会员微信ID
     * @param null $card_id 会员卡ID
     * @param string $is_send 发送模板消息状态
     * @return bool
     */
    public function save_member_card($inter_id=null,$open_id=null,$card_id=null,$is_send='f'){
        if(!isset($inter_id) || !isset($open_id)) return false;
        if(!isset($card_id) || empty($card_id)) return false;

        if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
            //生产环境往后7天时间
            $expired_time = strtotime('+1 week');
        } else {
            //其他环境往后一天时间
            $expired_time = strtotime('+1 day');
        }
        $res = $this->_shard_db(true)
            ->where(array('inter_id'=>$inter_id,'open_id'=>$open_id,'card_id'=>$card_id,'expire_time >'=>time(),'expire_time <'=>$expired_time))
            ->update($this->member_card_table_name(),array('is_send'=>$is_send));
        if($this->input->get('debug')=='1') echo $this->_shard_db(true)->last_query();
        if($res) return $res;
        return $this->_shard_db(true)->last_query();
    }

    /**
     * 获取会员模式
     * @param string $inter_id 酒店集团ID
     * @return null|string
     */
    protected function get_member_mode($inter_id=''){
        $mode = $this->_shard_db()->select('value')->where(array('inter_id'=>$inter_id,'type_code'=>'member'))->get('inter_member_config')->row_array();
        if($mode) return $mode['value'];
        return null;
    }

    public function get_user_list($where_in=array(),$inter_id,$flag=1,$card_id=0,$offset=0,$limit=1000,$fields='a.*'){
        $member_mode = $this->get_member_mode($inter_id);
        $field = '';
        $_where = array('a.inter_id'=>$inter_id,'a.is_active'=>'t');
        switch ($flag){
            case '1':
                $field = 'a.nickname';
                $_where['member_mode'] = '1';
                break;
            case '2':
                $field = 'a.name';
                $_where['member_mode'] = '1';
                break;
            case '3':
                $field = 'a.member_info_id';
                break;
            case '4':
                $field = 'a.telephone';
                if($member_mode=='perfect' && $field=='a.telephone'){
                    $field = 'a.cellphone';
                }
                break;
            case '5':
                $field = 'a.membership_number';
                break;
            case '6':
                $field = 'a.open_id';
                $_where['member_mode'] = '1';
                break;
        }
        if(empty($field)) return false;

        $this->_shard_db()->select($fields)->from('member_info as a');

        if($where_in){
            $this->_shard_db()->where_in($field,$where_in);
        }
        $this->_shard_db()->where($_where);
        $res = $this->_shard_db()->group_by('a.member_info_id')->order_by('a.member_info_id','desc')->limit($limit,$offset)->get()->result_array();
        if($this->input->get('debug')=='1') echo $this->_shard_db()->last_query();
        $mids=array();
        $result=array();
        foreach ($res as $k=>$v){
            $mids[]=$v['member_info_id'];
            $result[$v['member_info_id']]=$v;
        }
        $card_count=$this->get_card_count($inter_id,$mids,$card_id);
        if(!empty($card_count)){
            foreach ($result as $vo){
                $result[$vo['member_info_id']]['count']=isset($card_count[$vo['member_info_id']])?$card_count[$vo['member_info_id']]:0;
            }
        }
        return $result;
    }

    public function send_user_list($where=array()){
        $member = $this->create_member_info($where['inter_id'],$where['openid']);
        $this->_write_log(json_encode($member), 'member');
        if(!isset($member['err']) || floatval($member['err'])>0) return array();
        $this->_shard_db()->where('inter_id',$where['inter_id']);
        $this->_shard_db()->where('open_id',$where['openid']);
        $res = $this->_shard_db()->get('member_info')->result_array();
//        return $this->_shard_db()->last_query();
        $this->_write_log($this->_shard_db()->last_query(), 'send_user_list_sql');
        $this->_write_log($res, 'sql');
        return $res[0];
    }

    public function get_ignore_user_list($where,$inter_id){
        $field = 'card_id';
        if($where){
            $this->_shard_db()->where_in($field,$where);
        }
        $this->_shard_db()->where('inter_id',$inter_id);
        $res = $this->_shard_db()->get('member_card')->result_array();
        if($this->input->get('debug')=='1') echo $this->_shard_db()->last_query();
        $arr=array();
        if($res){
            foreach ($res as $ko => $vo){
                $arr[]=$vo['member_info_id'];
            }
        }
        return $arr;
    }

    public function get_card_count($inter_id='',$mids=array(),$card_id){
        if(empty($mids)) return false;
        $where = array('inter_id'=>$inter_id,'card_id'=>$card_id,'origin_member_info_id >'=>0,'friend_member_info_id'=>0);
        $res = $this->_shard_db()->select('COUNT(member_card_id) as count,member_info_id')->where($where)->where_in('member_info_id',$mids)->group_by('member_info_id')->get('member_card')->result_array();
        if($this->input->get('debug')=='1') echo $this->_shard_db()->last_query();
        $result=array();
        foreach ($res as $k=>$v){
            $result[$v['member_info_id']]=$v['count'];
        }
        return $result;
    }


    public function send_card_count($inter_id,$member_info_id,$card_id){
        $this->_shard_db()->where('inter_id',$inter_id);
        $this->_shard_db()->where('member_info_id',$member_info_id);
        $this->_shard_db()->where('card_id',$card_id);
        $this->_shard_db()->where('is_use','f');
        $this->_shard_db()->where('is_useoff','f');
        $count = $this->_shard_db()->get('member_card')->num_rows();
        return $count;
    }


    public function get_card_info($inter_id='',$card_id=0,$field='*'){
        $where = array('inter_id'=>$inter_id,'card_id'=>$card_id);
        $res = $this->_shard_db()->select($field)->where($where)->get('card')->row_array();
        if($this->input->get('debug')=='1') echo $this->_shard_db()->last_query();
        if($res){
            return $res;
        }
        return array();
    }

    public function send_card_info($inter_id,$card_id){
        $this->_shard_db()->where('inter_id',$inter_id);
        $this->_shard_db()->where('card_id',$card_id);
        $res = $this->_shard_db()->get('card')->result_array();
        $this->_write_log($this->_shard_db()->last_query(), 'send_user_list_sql');
        $this->_write_log($res, 'sql');
        if($res){
            return $res[0];
        }
    }

    public function insert_card($data){
        $res = $this->_shard_db(true)->insert('member_card', $data);
        if($this->input->get('debug')=='1') echo $this->_shard_db(true)->last_query();
        if($res) return $res;
        return false;
    }


    public function _card_stock($inter_id='',$num=0,$card_id){
        if($num <= '0') return false;
        $this->_shard_db(true)->where('inter_id',$inter_id);
        $this->_shard_db(true)->where('card_id',$card_id);
        $card_stock = $num - 1;
        $this->_shard_db(true)->set('card_stock', $card_stock);
        $res = $this->_shard_db(true)->update('card');
        if($this->input->get('debug')=='1') echo $this->_shard_db(true)->last_query();
        if($res) return $res;
        return false;
    }

    public function send_card_stock($inter_id,$card_id){
        $this->_shard_db(true)->where('inter_id',$inter_id);
        $this->_shard_db(true)->where('card_id',$card_id);
        $this->_shard_db(true)->set('card_stock', 'card_stock-1', FALSE);
        $res = $this->_shard_db(true)->update('card');
        if($res) return $res;
        return false;
    }

    /**
     * 读取绑定会员明细表
     */
    public function get_bind_member_fans($params,$limit=NULL,$offset=0){
        $inter_id = $params['inter_id'];
        $this->_shard_db()->select('member_info_id,inter_id,open_id,membership_number,createtime');
        $where=array('inter_id'=>$inter_id,'membership_number !='=>'','member_mode'=>2);
        $this->_shard_db()->where($where);
        if(!empty($params['begin_time'])){
            $begin_time = strtotime($params['begin_time']);
            $this->_shard_db()->where('createtime >',$begin_time);
        }

        if(!empty($params['end_time'])){
            $end_time = strtotime($params['end_time']);
            $this->_shard_db()->where('createtime <=',$end_time);
        }

        $list = $this->_shard_db()
                    ->order_by('createtime desc')
                    ->limit($limit,$offset)
                    ->get('member_info')->result_array();

        if($this->input->get('debug') == 1){
            echo $this->_shard_db()->last_query();echo '<br />';
        }
        return $list;
    }

    /**
     * 读取绑定会员明细表 总数
     */
    public function get_bind_member_fans_count($params){
        $inter_id = $params['inter_id'];
        $where=array('inter_id'=>$inter_id,'membership_number !='=>'','member_mode'=>2);
        $this->_shard_db()->where($where);

        if(!empty($params['begin_time'])){
            $begin_time = strtotime($params['begin_time']);
            $this->_shard_db()->where(array('createtime >'=>$begin_time));
        }

        if(!empty($params['end_time'])){
            $end_time = strtotime($params['end_time']);
            $this->_shard_db()->where(array('createtime <='=>$end_time));
        }

        $count = $this->_shard_db()->get('member_info')->num_rows();
        return $count;
    }

    /**
     * 运行日志记录
     * @param String $content
     */
    protected function _write_log( $content,$type ) {
        $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'. DS. 'membervip'. DS. 'customize'. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $ip= $this->input->ip_address();
        $fp = fopen( $path. $file, 'a');

        $content= "\n[". date('Y-m-d H:i:s'). '] [' . $ip. "] $type '". $content. "' starting...";
        fwrite($fp, $content);
        fclose($fp);
    }

    //会员模块信息建立
    protected function create_member_info( $inter_id , $openid ){
        $post_create_member = array(
            'inter_id'=>$inter_id,
            'token' =>$this->get_Token(),
            'openid'=>$openid,
        );
        return $this->doCurlPostRequest( INTER_PATH_URL."member/notify_new" , $post_create_member );
    }

    //获取授权token
    protected function get_Token(){
        $post_token_data = array(
            'id'=>'vip',
            'secret'=>'iwide30vip',
        );
        $token_info = $this->doCurlPostRequest( INTER_PATH_URL."accesstoken/get" , $post_token_data );
        return isset($token_info['data'])?$token_info['data']:"";
    }

    /**
     * 封装curl的调用接口，post的请求方式
     * @param string URL
     * @param string POST表单值
     * @param array 扩展字段值
     * @param second 超时时间
     * @return 请求成功返回成功结构，否则返回FALSE
     */
    protected function doCurlPostRequest( $url , $post_data , $timeout = 5) {
        $requestString = http_build_query($post_data);
        if ($url == "" || $timeout <= 0) {
            return false;
        }
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //設置請求數據返回的過期時間
        curl_setopt ( $curl, CURLOPT_TIMEOUT, ( int ) $timeout );
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, true);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestString);
        //执行命令
        $res = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //写入日志
        $log_data = array(
            'url'=>$url,
            'post_data'=>$post_data,
            'result'=>$res,
        );
        $this->_write_log(serialize($log_data) );
        return json_decode($res,true);
    }
}