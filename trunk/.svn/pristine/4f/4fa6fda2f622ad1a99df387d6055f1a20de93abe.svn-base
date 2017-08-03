<?php

/**
 * Created by knight.
 * User: ibuki
 * Date: 16/7/30
 * Time: 下午9:25
 */
class Kiminvited_model extends MY_Model_Member {

    const KIM_ACT = 'kiminvited_activited_conf';

    /**
     * 获取当前时间之后最近的一条活动信息
     * @param string $inter_id 酒店集团ID
     * @param string $field 查询字段
     * @return array
     */
    public function get_recent_activity($inter_id='',$field='*'){
        if(empty($inter_id)) return array();
        $map = "(end_time >= ".time().") and end_time > start_time";
        $where = "$map and inter_id='".$inter_id."' and status=1 and isopen=1 and is_del='n'";
        $info = $this->_shard_db()->select($field)->where($where)->order_by('createtime DESC, id DESC')->get(self::KIM_ACT)->row_array();
        if(empty($info)){
            $where = "end_time > start_time and inter_id='".$inter_id."' and status=1 and is_del='n'";
            $info = $this->_shard_db()->select($field)->where($where)->order_by('createtime DESC, id DESC')->get(self::KIM_ACT)->row_array();
        }

        $this->_write_log($this->_shard_db()->last_query(),'get_recent_activity','Kiminvited/sql');
        if($info) return $info;
        return array();
    }

    /**
     * 验证字段信息
     * @param array $data
     * @param string $table
     * @return array
     */
    public function check_list_fields($data=array(),$table=''){
        if(empty($data) || empty($table)) return array();
        $list_fields = $this->_shard_db()->list_fields($table);
        foreach ($data as $item){
            if(!in_array($item, $list_fields)) unset($data[$item]);
        }
        return $data;
    }

    /**
     * 获取显示配置
     * @param array $params 条件
     * @param int $offset 开始获取行数
     * @param int $limit 需要获取返回行数
     * @return array
     */
    public function get_kiminvited_display($params,$offset,$limit){
        $inter_id = $params['inter_id'];
        if(!isset($params['field'])) $params['field'] = self::KIMINVITED_DISPLAY_CONFIG.'.*';
        $where[self::KIMINVITED_DISPLAY_CONFIG.'.inter_id'] = $inter_id;
        $info = $this->_shard_db()->select($params['field'])
                    ->get_where(self::KIMINVITED_DISPLAY_CONFIG, $where, $limit, $offset)
                    ->row_array();
        if($this->input->get('debug') == 1){
            $this->_write_log($this->_shard_db()->last_query(),'row_array-SQL','Kiminvited/sql');
            echo $this->_shard_db()->last_query();echo '<br />';
        }
        if(!is_null($info)) return $info;
        return array();
    }

    /**
     * 获取数据(单条)
     * @param $params 条件
     * @param $table 指定数据表
     * @return array
     */
    public function get_kiminvited_info($params,$table=''){
        if(empty($table)) return false;
        $inter_id = $params['inter_id'];
        if(!isset($params['field'])) $params['field'] = $table.'.*';
        $where[$table.'.inter_id'] = $inter_id;
        if(isset($params['id'])) $where[$table.'.id'] = $params['id'];
        if(isset($params['activited_id'])) {
            if(empty($params['activited_id'])) return array();
            $where[$table.'.activited_id'] = $params['activited_id'];
        }
        if(isset($params['type_code'])) $where[$table.'.type_code'] = $params['type_code'];
        $info = $this->_shard_db()->select($params['field'])
            ->get_where($table, $where)
            ->row_array();
        if($this->input->get('debug') == 1){
            $this->_write_log($this->_shard_db()->last_query(),'row_array-SQL','Kiminvited/sql');
            echo $this->_shard_db()->last_query();echo '<br />';
        }
        if(!is_null($info)) return $info;
        return array();
    }

    /**
     * 获取数据(列表)
     * @param $params 条件
     * @param $offset 开始获取行数
     * @param $limit 需要获取返回行数
     * @return array
     */
    public function get_kiminvited_list($params,$offset,$limit,$table='',$condition=array()){
        if(empty($table)) return false;
        $inter_id = $params['inter_id'];
        if(!isset($params['field'])) $params['field'] = $table.'.*';
        $where[$table.'.inter_id'] = $inter_id;
        if(!empty($condition)) $where = array_merge($where,$condition);
        $this->_shard_db()->select($params['field'])->where($where);
        $list = $this->_shard_db()->order_by($table.'.createtime DESC')->limit($limit, $offset)->get($table)->result_array();
        if($this->input->get('debug') == 1){
            $this->_write_log($this->_shard_db()->last_query(),'row_array-SQL','Kiminvited/sql');
            echo $this->_shard_db()->last_query();echo '<br />';
        }
        if(!is_null($list)) return $list;
        return array();
    }

    /**
     * 数据集 总数
     */
    public function get_kiminvited_total($params=array(),$table='',$condition=array()){
        $inter_id = $params['inter_id'];
        $where=array($table.'.inter_id'=>$inter_id);
        if(!empty($condition)) $where = array_merge($where,$condition);
        $this->_shard_db()->where($where);
        $count = $this->_shard_db()->get($table)->num_rows();
        return $count;
    }

    /**
     * 更新数据
     * @param $params 条件
     * @param $data 更新数据
     * @return bool
     */
    public function update_save($params=array(),$data=array(),$table=''){
        if(!isset($params['inter_id']) || !isset($params['id'])) return false;
        if(empty($table)) return false;
        if(empty($data) && is_string($data)) return false;
        $where['inter_id'] = $params['inter_id'];
        $where['id'] = $params['id'];
        $result = $this->_shard_db(true)->where($where)->set($data)->update($table);
        if($this->input->get('debug') == 1){
            $this->_write_log($this->_shard_db(true)->last_query(),'update-SQL','Kiminvited/sql');
        }
        return $result;
    }

    /**
     * 添加显示配置
     * @param $data 添加数据
     * @return bool
     */
    public function add_data($data=array(),$table=''){
        if(empty($table)) return false;
        if(empty($data) && is_string($data)) return false;
        $result = $this->_shard_db()->set($data)->insert($table);
        $this->_write_log($this->_shard_db()->last_query(),'insert-SQL','Kiminvited/sql');

        if($this->input->get('debug') == 1){
            $this->_write_log($this->_shard_db()->last_query(),'insert-SQL','Kiminvited/sql');
        }
        if($result){
            return $this->_shard_db()->insert_id();
        }
        return $result;
    }

    public function get_display_config($inter_id='',$field='*'){
        if(empty($inter_id)) return array();
        $table = 'kiminvited_display_config';
        $info = $this->_shard_db()->select($field)->get_where($table,array('inter_id'=>$inter_id))->row_array();
        if(!empty($info)){
            $info['face_invite_config']=(isset($info['face_invite_config'])&&!empty($info['face_invite_config']))?unserialize($info['face_invite_config']):'';
            $info['share_config']=(isset($info['share_config'])&&!empty($info['share_config']))?unserialize($info['share_config']):'';
            $info['center_config']=(isset($info['center_config'])&&!empty($info['center_config']))?unserialize($info['center_config']):'';
            $info['title_config']=(isset($info['title_config'])&&!empty($info['title_config']))?unserialize($info['title_config']):'';
            $info['rank_config']=(isset($info['rank_config'])&&!empty($info['rank_config']))?unserialize($info['rank_config']):'';
            $info['canon_config']=(isset($info['canon_config'])&&!empty($info['canon_config']))?unserialize($info['canon_config']):'';
            $info['regnotice_config']=(isset($info['regnotice_config'])&&!empty($info['regnotice_config']))?unserialize($info['regnotice_config']):'';
            $info['act_config']=(isset($info['act_config'])&&!empty($info['act_config']))?unserialize($info['act_config']):'';
            $this->session->set_tempdata($inter_id.'vip_display_config',$info,120);
            return $info;
        }
        return array();
    }

    public function get_myrecord_info($params=array(),$offset=0,$limit=50){
        if(!isset($params['inter_id']) || !isset($params['member_info_id']) || !isset($params['activited_id'])) return array();
        $tab1='kiminvited_credits_value';
        $tab2='member_info';
        $where[$tab1.'.inter_id'] = $params['inter_id'];
        $where[$tab1.'.member_info_id'] = $params['member_info_id'];
        $where[$tab1.'.activited_id'] = $params['activited_id'];
        $where[$tab2.'.inter_id'] = $params['inter_id'];
        $field=$tab1.'.*,'.$tab2.'.name,'.$tab2.'.createtime as reg_time';
        $info = $this->_shard_db()->select($field)
                     ->join($tab2, $tab2.'.member_info_id = '.$tab1.'.invited_userid', 'left')
                     ->where($where)
                     ->order_by($tab1.'.createtime DESC')->limit($limit, $offset)->get($tab1)->result_array();
        if($this->input->get('debug') == 1){
            $this->_write_log($this->_shard_db()->last_query(),'select-SQL','Kiminvited/sql');
        }
        if(empty($info)) return array();
        return $info;
    }

    public function handle_myrecord_info($data=array()){
        if(empty($data)) return array();
        $retrun = array();
        $total = 0;
        foreach ($data as $key => $val){
            $data[$key]['reward_name'] = '金';
            $data[$key]['reward_count'] = $val['credit_value'];
            $total+=floatval($val['credit_value']);
            if(!empty($val['name'])){
                $data[$key]['name'] = substr($val['name'], 0,3).'**';
            }else{
                $data[$key]['name'] = '***';
            }
        }
        $retrun['data'] = $data;
        $retrun['total_value'] = $total;
        return $retrun;
    }

    public function get_recommend_info($params=array(),$offset=0,$limit=50,$mymemid=0){
        if(!isset($params['inter_id']) || !isset($params['activited_id'])) return array();
        $tab='`iwide_kiminvited_record`';
        $_tab='`iwide_member_info`';
        $map = "AND reg_time >= 0";
        if(isset($params['where'])){
            switch ($params['where']){
                case '1'://日排行
                    $firsttime = strtotime(date('Y-m-d') . ' 00:00:00'); //当天时间段
                    $lastttime = strtotime(date('Y-m-d') . ' 23:59:59');
                    $map="AND reg_time >= $firsttime AND reg_time<=$lastttime";
                    break;
                case '2'://月排行
                    $firsttime = strtotime(date('Y-m-d', strtotime("-1 month")) . ' 00:00:00'); //当月时间段
                    $lastttime = strtotime(date('Y-m-d') . ' 23:59:59');
                    $map="AND reg_time >= $firsttime AND reg_time<=$lastttime";
                    break;
                default:
                    break;
            }
        }
        $sql = "SELECT $tab.id,$tab.inter_id,$tab.activited_id,COUNT($tab.touser_id) AS total_recom,$tab.fromuser_id,$tab.from_openid,$_tab.name FROM $tab LEFT JOIN $_tab ON $_tab.member_info_id = $tab.fromuser_id WHERE $tab.inter_id=? AND $tab.activited_id=? $map GROUP BY $tab.fromuser_id ORDER BY total_recom DESC LIMIT $offset,$limit";
        $info = $this->_shard_db()->query($sql, array($params['inter_id'], $params['activited_id']))->result_array();
        $this->_write_log($this->_shard_db()->last_query(),'get_recommend_info-SQL','Kiminvited/sql');
        $first_list = array();
        $rank_list = array();
        $myrank = array();
        $return = array();
        $icon = array(
            base_url("public/member/nvitedkim").'/images/gold.png',
            base_url("public/member/nvitedkim").'/images/silver.png',
            base_url("public/member/nvitedkim").'/images/copper.png'
        );
        if($info){
            $lvl=0;
            $old_recom = 0;
            foreach ($info as $key=>$item){
                if($key=='0') {
                    $old_recom = $item['total_recom'];
                    $lvl++;
                }

                if(floatval($item['total_recom']) < floatval($old_recom)) {
                    $lvl++;
                }

                $kc = $lvl-1;
                $fans = $this->get_fans_info($item['from_openid'],'nickname,headimgurl');
                $this->_write_log($fans,'get_fans_info');

                if($kc>2){
                    $rank_list[$item['fromuser_id']] = $item;
                    $rank_list[$item['fromuser_id']]['ranking'] = $lvl;
                    $rank_list[$item['fromuser_id']]['icon'] = '';
                    $rank_list[$item['fromuser_id']]['nickname'] = isset($fans['nickname'])?$fans['nickname']:'';
                    $rank_list[$item['fromuser_id']]['headimgurl'] = isset($fans['headimgurl'])?$fans['headimgurl']:'';
                }else{
                    $first_list[$item['fromuser_id']] = $item;
                    $first_list[$item['fromuser_id']]['ranking'] = $lvl;
                    $first_list[$item['fromuser_id']]['icon'] = $icon[$kc];
                    $first_list[$item['fromuser_id']]['nickname'] = isset($fans['nickname'])?$fans['nickname']:'';
                    $first_list[$item['fromuser_id']]['headimgurl'] = isset($fans['headimgurl'])?$fans['headimgurl']:'';
                }
                $old_recom = $item['total_recom'];
            }
        }
        $myrank = isset($first_list[$mymemid])?$first_list[$mymemid]:array();
        if(!isset($myrank['ranking']) || empty($myrank['ranking'])) $myrank = isset($rank_list[$mymemid])?$rank_list[$mymemid]:array();
        $return['first_list'] = $first_list;
        $return['rank_list'] = $rank_list;
        $return['myrank'] = $myrank;
        return $return;
    }


    public function get_my_ranking($params=array(),$mymemid=0,$offset=0,$limit=50){
        if(!isset($params['inter_id']) || !isset($params['activited_id'])) return array();
        $tab='`iwide_kiminvited_record`';
        $_tab='`iwide_member_info`';

        $sql = "SELECT $tab.id,$tab.inter_id,$tab.activited_id,COUNT($tab.touser_id) AS total_recom,$tab.fromuser_id,$tab.from_openid,$_tab.name FROM $tab LEFT JOIN $_tab ON $_tab.member_info_id = $tab.fromuser_id WHERE $tab.inter_id=? AND $tab.activited_id=? GROUP BY $tab.fromuser_id ORDER BY total_recom DESC LIMIT $offset,$limit";

        $info = $this->_shard_db()->query($sql, array($params['inter_id'], $params['activited_id']))->result_array();
        $myrank = array();
        $rank_list = array();
        if($info){
            $lvl=0;
            $old_recom = 0;
            foreach ($info as $key=>$item){
                if($key=='0') {
                    $old_recom = $item['total_recom'];
                    $lvl++;
                }
                if(floatval($item['total_recom']) < floatval($old_recom)) $lvl++;
                $rank_list[$item['fromuser_id']] = $item;
                $rank_list[$item['fromuser_id']]['ranking'] = $lvl;
                $old_recom = $item['total_recom'];
            }
            $myrank = isset($rank_list[$mymemid])?$rank_list[$mymemid]:array();
        }
        $this->_write_log($rank_list,'get_my_ranking_info');
        return $myrank;
    }

    //用戶兌換紀錄
    public function get_exchange_record($params=array(),$offset=0,$limit=100){
        if(empty($params['inter_id']) || empty($params['activited_id']) || empty($params['member_info_id'])) return array();
        $tab='kiminvited_exchange_reward';
        $tab2='card';
        $where = array($tab.'.member_info_id'=>$params['member_info_id'],$tab.'.inter_id'=>$params['inter_id'],$tab.'.activited_id'=>$params['activited_id'],$tab.'.reward_type'=>'1',$tab.'.is_ok'=>'t',$tab2.'.inter_id'=>$params['inter_id']);
        if(isset($params['reward_type'])) $where['reward_type'] = $params['reward_type'];
        $list = $this->_shard_db()->select("$tab.*,$tab2.card_id,$tab2.title")->from($tab)->join($tab2, $tab2.'.card_id = '.$tab.'.reward_cardid','left')->where($where)->order_by($tab.'.createtime DESC')->limit($limit, $offset)->get()->result_array();
        $this->_write_log($list,'exchange_record');
        $this->_write_log($this->_shard_db()->last_query(),'get_exchange_record');
        return $list;
    }

    //获取邀请奖励记录
    public function get_reward_record($params=array(),$offset=0,$limit=100){
        if(empty($params['inter_id']) || empty($params['activited_id']) || empty($params['member_info_id'])) return array();
        $where = array('inter_id'=>$params['inter_id'],'activited_id'=>$params['activited_id'],'member_info_id'=>$params['member_info_id'],'member_type'=>'2');
        $list = $this->_shard_db()->where($where)->where_in('reward_type',array('1','2','3','4'))->order_by('createtime DESC')->limit($limit, $offset)->get('kiminvited_reward_record')->result_array();
        $this->_write_log($list,'reward_record');
        $this->_write_log($this->_shard_db()->last_query(),'get_reward_record');
        if(!empty($list)){
            foreach ($list as $key => $vo){
                switch ($vo['reward_type']){
                    case '1':
                        $list[$key]['reward_name'] = '积分';
                        $list[$key]['reward_count'] = $vo['reward_value'];
                        break;
                    case '2':
                        $list[$key]['reward_name'] = '储值';
                        $list[$key]['reward_count'] = $vo['reward_value'];
                        break;
                    case '3':
//                        $where['card_id'] = $vo['reward_value'];
//                        $where['inter_id'] = $vo['inter_id'];
                        $where = array('card_id'=>$vo['reward_value'],'inter_id'=>$vo['inter_id']);
                        $list[$key]['reward_name'] = $this->get_info($where,'title','card');
                        $list[$key]['reward_count'] = 1;
                        break;
                    case '4':
//                        $where['package_id'] = $vo['reward_value'];
//                        $where['inter_id'] = $vo['inter_id'];
                        $where = array('package_id'=>$vo['reward_value'],'inter_id'=>$vo['inter_id']);
                        $list[$key]['reward_name'] = $this->get_info($where,'name','package');
                        $list[$key]['reward_count'] = 1;
                        break;
                }
            }
        }
        return $list;
    }

    //查询当前活动获得总邀金
    public function get_total_integral($params=array()){
        if(empty($params['inter_id']) || empty($params['activited_id']) || empty($params['member_info_id'])) return 0;
        $where = array('member_info_id'=>$params['member_info_id'],'inter_id'=>$params['inter_id'],'activited_id'=>$params['activited_id']);
        $reward_value = $this->_shard_db()->where($where)->select_sum('credit_value')->group_by('member_info_id')->get('kiminvited_credits_value')->row_array();
        $reward_value['reward_value'] = $reward_value['credit_value'];
        $this->_write_log($this->_shard_db()->last_query(),'get_total_integral');
        return $reward_value;
    }

    public function get_total_number($params=array()){
        if(empty($params['inter_id']) || empty($params['activited_id']) || empty($params['member_info_id'])) return 0;
        $reward_value = $this->_shard_db()->where($params)->get('kiminvited_reward_record')->num_rows();
        return $reward_value;
    }

    //查詢已使用邀金
    public function get_use_integral($params=array()){
        if(empty($params['inter_id']) || empty($params['activited_id']) || empty($params['member_info_id'])) return 0;
        $where = array($params['member_info_id'],$params['inter_id'],$params['activited_id']);
        $sql = "SELECT SUM(use_credit) as use_credit FROM iwide_kiminvited_exchange_reward WHERE member_info_id=? AND inter_id=? AND activited_id=? AND is_ok='t' GROUP BY member_info_id";
        $use_credit = $this->_shard_db()->query($sql,$where)->row_array();
        $this->_write_log($use_credit,'get_use_credit');
        $this->_write_log($this->_shard_db()->last_query(),'get_use_integral');
        if(isset($use_credit['use_credit']) && !empty($use_credit['use_credit'])) return $use_credit['use_credit'];
        return 0;
    }

    /**
     * 处理奖励事务
     * @param array $data
     * @return bool|请求成功返回成功结构
     */
    public function handle_rewards($data=array()){
        $return['data'] = array();
        $return['code'] = '501';
        $return['issend'] = '2';
        $return['msg'] = 'fail';
        $this->_write_log($data,'handle_rewards_data');
        if(!$data || empty($data)) return false;
        $token = $this->get_Token();
        $remark='';
        if($data['member_type']==1){
            $remark = '新会员赠送'.floatval($data['reward_value']);
        }elseif ($data['member_type']==2){
            $remark = '旧会员赠送'.floatval($data['reward_value']);
        }

        $request=array(
            'token'=>$token,
            'inter_id'=>$data['inter_id'],
            'openid'=>$data['openid'],
            'member_info_id'=>$data['member_info_id'],
            'uu_code'=>time().uniqid('',true)
        );

        switch ($data['reward_type']){
            case '1'://送积分
                $request['count'] = floatval($data['reward_value']);
                $request['module'] = 'vip';
                $request['scene'] = '邀金令';
                $request['remark'] = $remark.'积分';
                $tourl = INTER_PATH_URL.'credit/add';
                $result = $this->doCurlPostRequest($tourl,$request);
                $this->_write_log($result,'credit/add');
                $return['code'] = '502';
                $return['msg'] = '赠送积分失败!';
                if(isset($result['err']) && $result['err']=='0') {
                    $return['code'] = '100';
                    $return['msg'] = '赠送积分成功!';
                }
                break;
            case '2'://送储值
                $request['count'] = floatval($data['reward_value']);
                $request['module'] = 'vip';
                $request['scene'] = '邀金令';
                $request['remark'] = $remark.'储值';
                $request['deposit_type'] = 'c';
                $tourl = INTER_PATH_URL.'deposit/add';
                $result = $this->doCurlPostRequest($tourl,$request);
                $this->_write_log($result,'deposit/add');
                $return['code'] = '503';
                $return['msg'] = '赠送储值失败!';
                if(isset($result['err']) && $result['err']=='0') {
                    $return['code'] = '100';
                    $return['msg'] = '赠送储值成功!';
                }
                break;
            case '3'://送优惠券
                $request['card_id'] = floatval($data['reward_value']);
                $request['module'] = 'vip';
                $request['scene'] = '邀金令';
                $tourl = INTER_PATH_URL.'intercard/receive';
                $result = $this->doCurlPostRequest($tourl,$request);
                $this->_write_log($result,'intercard/receive');
                $return['code'] = '504';
                $return['msg'] = '赠送优惠券失败!';
                if(isset($result['data']) && floatval($result['data'])>0) {
                    $return['code'] = '100';
                    $return['msg'] = '赠送优惠券成功!';
                    $subdata = array();
                    if($data['inter_id']=='a421641095'){
                        $return['issend'] = '1';
                        $subdata['inter_id'] = $data['inter_id'];
                        $subdata['openid'] = $data['openid'];
                        $subdata['type'] = 4;
                        $where = array('member_info_id'=>$data['member_info_id']);
                        $subdata['name'] = $this->get_info($where,'name','member_info','nickname');
                        $subdata['count'] = 1;
                        $subdata['curtime'] = time();
//                        $this->_send_tmp($data['inter_id'],$data['openid'],$subdata,4);
                    }
                    $return['data'] = $subdata;
                }
                break;
            case '4'://送礼包
                $request['package_id'] = floatval($data['reward_value']);
                $tourl = INTER_PATH_URL.'package/receive';
                $result = $this->doCurlPostRequest($tourl,$request);
                $this->_write_log($result,'package/receive');
                $return['code'] = '505';
                $return['msg'] = '赠送礼包失败!';
                if(isset($result['err']) && $result['err']=='0') {
                    $return['code'] = '100';
                    $return['msg'] = '赠送礼包成功!';
                    $subdata = array();
                    if($data['inter_id']=='a421641095'){
                        $return['issend'] = '1';
                        $subdata['inter_id'] = $data['inter_id'];
                        $subdata['openid'] = $data['openid'];
                        $subdata['type'] = 5;
                        $where = array('member_info_id'=>$data['member_info_id']);
                        $subdata['name'] = $this->get_info($where,'name','member_info','nickname');
                        $subdata['count'] = 1;
                        $subdata['curtime'] = time();

//                        $retrun =  $this->_send_tmp($data['inter_id'],$data['openid'],$subdata,5);
//                        $this->_write_log($retrun,'_send_tmp');
                    }
                    $return['data'] = $subdata;
                }
                break;
        }

        return $return;
    }

    /**
     * 积分兑换操作，兼容独立积分和账户积分
     * @param $params 必要条件参数
     * @return mixed
     */
    public function exchange_reward($params){
        $return['status'] = 0;
        $return['msg'] = 'fail';
        if(!$params || empty($params)) return $return;
        if(floatval($params['condition']) <= 0) return $return;
        $token = $this->get_Token();
        $request=array(
            'token'=>$token,
            'inter_id'=>$params['inter_id'],
            'openid'=>$params['openid'],
            'member_info_id'=>$params['member_info_id'],
            'uu_code'=>time().uniqid('',true),
            'order_id'=>0
        );

        //添加活动邀金使用记录
        $_request['inter_id'] = $params['inter_id'];
        $_request['openid'] = $params['openid'];
        $_request['member_info_id'] = $params['member_info_id'];
        $_request['use_credit'] = floatval($params['condition']);
        $_request['activited_id'] = $params['activited_id'];
        $_request['reward_cardid'] = floatval($params['value']);
        $_request['reward_type'] = '1';
        $_request['createtime'] = time();
        $res = $this->add_data($_request,'kiminvited_exchange_reward');

        if($res){
            $request['card_id'] = floatval($params['value']);
            $request['module'] = 'vip';
            $request['scene'] = '邀金令-积分兑换';
            $tourl = INTER_PATH_URL.'intercard/receive';
            $result = $this->doCurlPostRequest($tourl,$request); //兑换优惠券
            $this->_write_log($result,'exchange_reward_res');

            if(isset($result['data']) && !isset($result['err'])) {
                $_params['inter_id'] = $params['inter_id'];
                $_params['member_info_id'] = $params['member_info_id'];
                $_params['activited_id'] = $params['activited_id'];
                $_params['reward_type'] = '1';
                $res = $this->modify_exchange_reward($_params); //优惠券兑换成功
                $this->_write_log($res,'modify_exchange_reward');
                $card_id = floatval($params['value']);
                $where = array('card_id'=>$card_id);
                $return['status'] = 1;
                $return['msg'] = 'ok';
                $return['name'] = $this->get_info($where,'title','card');
                return $return;
            }
        }
        return $return;
    }

    public function modify_exchange_reward($params=array()){
        $where = array(
            'inter_id'=>$params['inter_id'],
            'activited_id'=>$params['activited_id'],
            'member_info_id'=>$params['member_info_id'],
            'reward_type'=>$params['reward_type'],
        );
        $exchange_reward = $this->_shard_db(true)->set('is_ok','t')->where($where)->update('kiminvited_exchange_reward');
        $this->_write_log($this->_shard_db(true)->last_query(),'kiminvited_exchange_reward','Kiminvited/sql');
        return $exchange_reward;
    }

    protected function get_info($where=array(),$name='',$table='',$subname=''){
        $select = $name;
        if(!empty($subname)) $select = "$name,$subname";
        $info = $this->_shard_db()->select($select)
            ->get_where($table, $where)
            ->row_array();
        if(!empty($info)) {
            $rname = $info[$name];
            if(empty($rname) && !empty($subname)){
                $rname = $info[$subname];
            }
            return $rname;
        }
        return false;
    }

    //获取授权token
    protected function get_Token(){
        $post_token_data = array(
            'id'=>'vip',
			'secret'=>'iwide30vip',
        );
        $token_info = $this->doCurlPostRequest( INTER_PATH_URL."accesstoken/get" , $post_token_data );
        $this->_token = isset($token_info['data'])?$token_info['data']:"";
    }

    /**
     * 获取粉丝信息
     * @param $openid 微信ID
     * @return mixed
     */
    function get_fans_info($openid='',$field='*') {
        $fans_info = $this->db->select($field)->get_where ('fans', array (
            'openid' => $openid
        ) )->row_array ();
        $this->_write_log($this->db->last_query(),'get_fans_info-SQL','Kiminvited/sql');
        return $fans_info;
    }

    public function _send_tmp($inter_id='',$open_id='',$data=array(),$type=''){
        $_return['code'] = '501';
        $_return['msg'] = '参数不全';
        $this->_write_log(array($inter_id,$open_id,$data),'Message_Data');
        if(empty($inter_id) || empty($inter_id) || empty($data) || empty($type)) return $_return;

        $this->load->model('member/Message_wxtemp_model','wxtemp_model');
        $wxtemp_model = $this->wxtemp_model;
        //获取已配置发送模版消息的公众号的模版信息
        $this->load->model('member/member_related_model');
        $this->_write_log($type,'Message_Type');
        $temp = $this->member_related_model->member_card_temp($inter_id,$type);
        $this->_write_log(json_encode($temp),'_send_tmp');
        $_return['code'] = '503';
        $_return['msg'] = '消息模版不存在';
        if($temp){
            $res = $wxtemp_model->send_template_coupon_msg($inter_id,$open_id,$type,$data);
            $this->_write_log('发送结果send_template_coupon_msg result --> '.$res);
            $_return['code'] = '504';
            $_return['msg'] = '发送失败';
            if($res){
                $return = json_decode($res,true);
                $_return = $return;
                if($return['code'] == '1001'){
                    $_return['code'] = $return['code'];
                    $_return['msg'] = '发送成功!';
                }
            }
        }
        return $_return;
    }

    /**
     * 封装curl的调用接口，post的请求方式
     * @param string URL
     * @param string POST表单值
     * @param array 扩展字段值
     * @param second 超时时间
     * @return 请求成功返回成功结构，否则返回FALSE
     */
    protected function doCurlPostRequest( $url , $post_data , $timeout = 20) {
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
        $this->_write_log($log_data,'doCurlPostRequest');
        return json_decode($res,true);
    }

    public function vip_authencode($string,$key=''){
        return $this->vip_authcode($string,"ENCODE",$key);
    }

    public function vip_authdecode($string,$key=''){
        return $this->vip_authcode($string,"DECODE",$key);
    }

    public function vip_authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        $ckey_length = 4;

        $key = md5($key ? $key : 'AUTHCODE');
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }

    }

    /**
     * 运行日志记录
     * @param String $content
     */
    public function _write_log($content,$type,$dir_path='Kiminvited_model') {
        if(is_array($content) || is_object($content))
            $content = json_encode($content);
        $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'.DS. 'front'. DS. 'membervip'. DS. $dir_path. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $ip= $this->input->ip_address();
        $fp = fopen( $path. $file, 'a');
        $content= "\n[". date('Y-m-d H:i:s'). '] [' . $ip. "] $type '". $content. "' starting...";
        fwrite($fp, $content);
        fclose($fp);
    }
}