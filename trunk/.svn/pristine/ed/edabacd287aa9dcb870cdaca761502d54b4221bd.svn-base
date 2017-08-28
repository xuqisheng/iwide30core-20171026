<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Report_member_model extends MY_Model_Member {

    public function _parsemodule(){
        $data = func_get_args();
        $module_key = array('hotel'=>'订房','shop'=>'商城','soma'=>'套票','vip'=>'会员');
        return $module_key[$data[0]];
    }

    public function _parse_card_status(){
        $data = func_get_args();
        $status = '未使用';
        if($data[1]=='t') $status = '已使用';
        if($data[2]=='t') $status = '已核销';
        if($data[3]=='t') $status = '转赠中';
        return $status;
    }

    public function _parse_card_module(){
        $data = func_get_args();
        $module_key = array('hotel'=>'订房','shop'=>'商城','soma'=>'套票','vip'=>'会员');
        $use_scene = isset($module_key[$data[0]])?$module_key[$data[0]]:'';
        if(isset($data[1]) && !empty($data[1])) $use_scene.=' - '.$data[1];
        return $use_scene;
    }

    public function _parse_datetime(){
        $data = func_get_args();
        if(empty($data[0])) return '------';
        return date('Y-m-d H:i:s',$data[0]);
    }

    public function _parse_datehm(){
        $data = func_get_args();
        if(empty($data[0])) return '------';
        return date('Y-m-d H:i',$data[0]);
    }

    public function _parse_date(){
        $data = func_get_args();
        if(empty($data[0])) return '------';
        return date('Y-m-d',$data[0]);
    }

    public function _parse_useoff_time(){
        $data = func_get_args();
        if(empty($data) || $data[0]!='t') return '------';
        return date('Y-m-d',$data[1]);
    }

    /**
     * 获取优惠券所属礼包信息
     * @param array $params
     * @return bool
     */
    public function get_package_info($params=array()){
        if(empty($params['inter_id']) || empty($params['card_id'])) return false;
        $this->_shard_db()->select('a.package_id,b.name')->from('package_element as a')
                          ->join('package as b','b.package_id=a.package_id','left');

        if(is_array($params['card_id'])){
            $this->_shard_db()->where_in('a.ele_value',$params['card_id']);
        }else{
            $this->_shard_db()->where('a.ele_value',$params['card_id']);
        }
        $result = $this->_shard_db()->group_by('a.package_id')->order_by('a.createtime desc')->get()->result_array();
        return $result;
    }

    /**
     * 获取优惠券使用模块范围
     * @param array $params
     * @return bool
     */
    public function get_card_module($id=null){
        if(empty($id)) return false;
        $this->_shard_db()->select('card_id,module')->from('card_module');
        if(is_array($id)){
            $this->_shard_db()->where_in('card_id',$id);
        }else{
            $this->_shard_db()->where('card_id',$id);
        }
        $result = $this->_shard_db()->order_by('card_module_id desc')->get()->result_array();
        $list=array();
        foreach ($result as $item){
            $list[$item['card_id']][]=$item['module'];
        }
        return $list;
    }

    /**
     * 获取用户领取优惠券明细
     * @param array $params
     * @param int $startime
     * @param int $endtime
     * @return mixed
     */
    public function get_membercard_v($params=array()){
        $table= 'iwide_member_card';
        $btable='iwide_card';
        $ctable='iwide_member_info';
        $select= array('a.coupon_code','a.member_card_id','a.card_id','a.inter_id','b.title','a.member_info_id','c.nickname','c.name','a.receive_module','a.receive_time','a.expire_time','a.is_useoff','a.is_use','a.is_active','a.is_giving','a.use_module','a.use_scene','a.use_time','a.useoff_module','a.useoff_scene','a.useoff_time','c.membership_number');
        $select= implode(',', $select);
        $_sql = '';
        if(isset($params['time_mode']) && $params['time_mode']=='1'){
            if(isset($params['begin_time']) && !empty($params['begin_time'])) $_sql.=" AND receive_time > ".$params['begin_time'];
            if(isset($params['end_time']) && !empty($params['end_time'])) $_sql.=" AND receive_time <= ".$params['end_time'];
        }elseif (isset($params['time_mode']) && $params['time_mode']=='2'){
            if(isset($params['begin_time']) && !empty($params['begin_time'])) $_sql.=" AND expire_time > ".$params['begin_time'];
            if(isset($params['end_time']) && !empty($params['end_time'])) $_sql.=" AND expire_time <= ".$params['end_time'];
        }
        $sql = "SELECT $select FROM $table a LEFT JOIN $btable b ON b.card_id=a.card_id LEFT JOIN $ctable c ON c.member_info_id=a.member_info_id WHERE a.inter_id = ? AND a.is_active = 't'";
        $sql_params = array($params['inter_id']);
        if(isset($params['card_id']) && !empty($params['card_id'])){
            $sql_params[]=$params['card_id'];
            $sql.=" AND a.card_id=?";
        }
        $sql.=" AND a.card_id > 0 $_sql GROUP BY a.member_card_id ORDER BY a.receive_time DESC";
        $result = $this->_shard_db()->query($sql, $sql_params)->result_array();
        if(!empty($result)){
            if(isset($params['card_id']) && !empty($params['card_id'])){
                $card_ids=$params['card_id'];
                $where = array('inter_id'=>$params['inter_id'],'card_id'=>$params['card_id']);
            }else{
                $card_ids=array();
                foreach ($result as $vo){
                    if(!in_array($vo['card_id'],$card_ids)) $card_ids[]=$vo['card_id'];
                }
                $where = array('inter_id'=>$params['inter_id'],'card_id'=>$card_ids);
            }
            $card_module = $this->get_card_module($card_ids);

            $packages = $this->get_package_info($where);
            foreach ($result as $key=>$item){
                $package_ids = array();
                $package_names = array();
                if(!empty($packages)){
                    foreach ($packages as $k=>$v){
                        $package_ids[]=$v['package_id'];
                    }
                    foreach ($packages as $k=>$v){
                        $package_names[]=$v['name'];
                    }
                }
                $result[$key]['package_ids'] = implode(',',$package_ids);
                $result[$key]['package_names'] = implode(',',$package_names);

                $module_key = array('hotel'=>'订房','shop'=>'商城','soma'=>'套票','vip'=>'会员');

                $_module = array();
                if(isset($card_module[$item['card_id']]) && !empty($card_module[$item['card_id']])){
                    foreach ($card_module[$item['card_id']] as $k=>$v){
                        $_module[]=isset($module_key[$v])?$module_key[$v]:'';
                    }
                }
                $result[$key]['card_module'] = implode('/',$_module);
                $result[$key]['expire_time'] = strtotime(date('Y-m-d',$item['expire_time']).' 23:59:59');
                $result[$key]['coupon_code'] = "'{$item['coupon_code']}'";
            }
        }
        return $result;
    }

    /**
     * 获取礼包领取详情,包括优惠券使用情况
     * @param array $params
     * @param int $startime
     * @param int $endtime
     * @return array
     */
    public function get_member_paceage_card($params=array(),$startime=0,$endtime=0){
        $rdata = array();
        if(!isset($params['inter_id']) && empty($params['inter_id'])) return array();
        if(!isset($params['package_id']) && empty($params['package_id'])) return array();
        $where = array('inter_id'=>$params['inter_id'],'package_id'=>$params['package_id']);
        $this->_shard_db()->select('createtime,member_info_id')->where($where);
        if(!empty($startime)){
            $this->_shard_db()->where('createtime >=',$startime);
        }

        if(!empty($endtime)){
            $this->_shard_db()->where('createtime <=',$endtime);
        }
        $_package = $this->_shard_db()->order_by('createtime desc')->get('member_package')->result_array();
        $package = array();
        $packages = array();
        if(!empty($_package)){
            //以一天为周期整理人数
            foreach ($_package as $key=>$value){
                $datetime = date('Y-m-d',$value['createtime']);
                if(!isset($package[$datetime])) $package[$datetime] = array();
                if(!in_array($value['member_info_id'],$package[$datetime])){
                    $receive_count = 1;
                    $package[$datetime][] = $value['member_info_id'];
                    $packages[$datetime]['receive_time'] = strtotime($datetime);
                    if(isset($packages[$datetime]['receive_count'])) $receive_count += $packages[$datetime]['receive_count'];
                    $packages[$datetime]['receive_count'] = $receive_count;
                }
            }
        }
        if($this->input->get('debug')=='1'){
            echo $this->_shard_db()->last_query();
        }
        if(!empty($packages)){
            rsort($packages); //重新排序
            $rdata['s1'] = $packages;
            $tab = 'package_element as a';
            $select = 'COUNT(b.member_card_id) as use_count,a.package_id,a.ele_value,b.member_card_id,b.member_info_id,b.card_id,b.use_time,b.useoff_time,c.name,b.is_use,b.is_useoff,d.card_id,d.title';
            $_where = array('a.inter_id'=>$params['inter_id'],'a.package_id'=>$params['package_id'],'a.ele_type'=>'card','b.inter_id'=>$params['inter_id'],'b.is_active'=>'t','b.expire_time >='=>strtotime(date('Y-m-d').' 23:59:59'),'c.status'=>1);
            $_info = $this->_shard_db()->select($select)->from($tab)
                            ->join('member_card as b','b.card_id=a.ele_value','left')
                            ->join('package as c','c.package_id=a.package_id','left')
                            ->join('card as d','d.card_id=a.ele_value','left')
                            ->where($_where)
                            ->group_start()
                            ->or_where('b.is_use','t')
                            ->or_where('b.is_useoff','t')
                            ->group_end()
                            ->group_by('member_card_id')->order_by('b.use_time desc,b.useoff_time desc')->get()->result_array();

            $useinfo = array();
            $useinfos = array();
            $useoffinfo = array();
            $useoffinfos = array();
            if(!empty($_info)){
                foreach ($_info as $key=>$value){
                    if($value['is_use']=='t'){ //取得使用人數
                        //以一天为周期整理人数
                        $use_time = date('Y-m-d',$value['use_time']);
                        if(!isset($useinfo[$use_time])) $useinfo[$use_time] = array();
                        if(!in_array($value['member_info_id'],$useinfo[$use_time])){
                            $use_count = 1;
                            $useinfo[$use_time][] = $value['member_info_id'];
                            $useinfos[$use_time]['card_id'] = $value['card_id'];
                            $useinfos[$use_time]['member_info_id'][] = $value['member_info_id'];
                            $useinfos[$use_time]['title'] = $value['title'];
                            $useinfos[$use_time]['is_use'] = $value['is_use'];
                            $useinfos[$use_time]['use_time'] = strtotime($use_time);
                            if(isset($useinfos[$use_time]['use_count'])) $use_count += $useinfos[$use_time]['use_count'];
                            $useinfos[$use_time]['use_count'] = $use_count;
                        }
                    }

                    if($value['is_useoff']=='t'){ //取得核銷人數
                        $useoff_time = date('Y-m-d',$value['useoff_time']);
                        if(!isset($useoffinfo[$useoff_time])) $useoffinfo[$useoff_time] = array();
                        if(!in_array($value['member_info_id'],$useoffinfo[$useoff_time])){
                            $useoff_count = 1;
                            $useoffinfo[$useoff_time][] = $value['member_info_id'];
                            $useoffinfos[$useoff_time]['card_id'] = $value['card_id'];
                            $useoffinfos[$useoff_time]['member_info_id'][] = $value['member_info_id'];
                            $useoffinfos[$useoff_time]['title'] = $value['title'];
                            $useoffinfos[$useoff_time]['is_useoff'] = $value['is_useoff'];
                            $useoffinfos[$useoff_time]['useoff_time'] = strtotime($useoff_time);
                            if(isset($useoffinfos[$useoff_time]['use_count'])) $useoff_count += $useoffinfos[$useoff_time]['use_count'];
                            $useoffinfos[$useoff_time]['use_count'] = $useoff_count;
                        }
                    }
                }
                $rdata['s1_name'] = $_info[0]['name'].'领取数据';
                $rdata['s2_name'] = $_info[0]['name'].' - 优惠券使用数据';
                $rdata['s3_name'] = $_info[0]['name'].' - 优惠券核销数据';
            }
            if($this->input->get('debug')=='1'){
                echo $this->_shard_db()->last_query();
            }
            if(!empty($useinfos)){
                $rdata['s2'] = $useinfos;
            }

            if(!empty($useoffinfos)){
                $rdata['s3'] = $useoffinfos;
            }
        }
        return $rdata;
    }

    /**
     * 会员信息明细
     * @param array $params
     * @param int $startime
     * @param int $endtime
     * @return array
     */
    public function get_member_info($params=array(),$startime=0,$endtime=0){
        if(!isset($params['inter_id']) && empty($params['inter_id'])) return array();
        $where = array('is_useoff'=>'f','is_use'=>'f','is_active'=>'t','expire_time >='=>time(),'inter_id'=>$params['inter_id']);
        $_member_card = $this->_shard_db()->select('COUNT(member_card_id) as count,member_info_id')->where($where)->group_by('member_info_id')->get('member_card')->result_array();
        $member_card = array();
        if(!empty($_member_card)){
            foreach ($_member_card as $key=>$item){
                $member_card[$item['member_info_id']] = $item;
            }
        }

        $table= 'iwide_member_info';
        $btable='iwide_member_lvl';
        $select= array('a.member_info_id','a.open_id','a.nickname','a.member_mode','a.name','a.birth','a.membership_number','a.telephone','a.cellphone','a.email','a.lvl_pms_code','b.lvl_name','a.credit','a.balance','a.is_active','a.is_login','a.createtime');
        $select= implode(',', $select);
        $_sql = "";
        if(!empty($startime)) $_sql .= " AND a.createtime > {$startime}";
        if(!empty($endtime)) $_sql .= " AND a.createtime <= {$endtime}";
        $sql = "SELECT {$select} FROM {$table} a LEFT JOIN {$btable} b ON b.member_lvl_id = a.member_lvl_id WHERE a.inter_id = ? {$_sql} ORDER BY a.createtime DESC";
        $result = $this->_shard_db()->query($sql, array($params['inter_id']))->result_array();
        if(!empty($result)){
            foreach ($result as $key => $vo){
                $result[$key]['member_mode_ext'] = $vo['member_mode'];
                $result[$key]['member_card_count'] = isset($member_card[$vo['member_info_id']]['count'])?$member_card[$vo['member_info_id']]['count']:0;
                $member_mode = ' -- ';
                if(empty($vo['cellphone'])){
                    $member_mode = '粉丝会员';
                }elseif(!empty($vo['cellphone'])){
                    $member_mode = '正式会员';
                }
                $result[$key]['member_mode'] = $member_mode;
                $is_active = ' -- ';
                if($vo['is_active']=='f'){
                    $is_active = '否';
                }elseif($vo['is_active']=='t'){
                    $is_active = '是';
                }
                $result[$key]['is_active'] = $is_active;

                $is_login = ' -- ';
                if($vo['is_login']=='t'){
                    $is_login = '是';
                }elseif($vo['is_login']=='f'){
                    $is_login = '否';
                }
                $result[$key]['is_login'] = $is_login;
            }
        }else{
            return array();
        }

        //取得注册分销信息
        $this->load->model('membervip/common/Public_model','Public_model');
        $where = array(
            'inter_id' => $params['inter_id'],
            'sales_id !=' => NULL,
            'sales_id <>' => '',
            'open_id !=' => '',
            'type' => 'reg'
        );

        $distribution_record_ext = $this->Public_model->get_list($where,'distribution_record','open_id,sales_id,sales_hotel',5000);
        if($this->input->get('pr_debug')==1){
            echo $this->_shard_db()->last_query();
            echo '<pre>';
            print_r($distribution_record_ext);
            echo '</pre>';
        }
        if(!empty($distribution_record_ext)){
            $distribution_record = array();
            foreach ($distribution_record_ext as $key => $item){
                $distribution_record[$item['open_id']] = $item;
            }

            $qrcode_ids = array();
            foreach ($distribution_record as $_record){
                $qrcode_ids[] = $_record['sales_id'];
            }

            $where = array(
                'inter_id' => $params['inter_id'],
                'openid !=' => NULL,
                'openid <>' => ''
            );
            $staff = $this->master_db('iwide_r1')->select('qrcode_id,master_dept,hotel_name')->where($where)->where_in('qrcode_id', $qrcode_ids)->get('hotel_staff')->result_array();
            if($this->input->get('pr_debug')==1){
                echo $this->_shard_db()->last_query();
                echo '<pre>';
                print_r($staff);
                echo '</pre>';
            }
            if(!empty($staff)){
                $staffs = array();
                foreach ($staff as $sta){
                    $staffs[$sta['qrcode_id']] = $sta;
                }
            }

            foreach ($result as &$res){
                if(!empty($distribution_record[$res['open_id']]['sales_id']) && $res['member_mode_ext'] == 2) {
                    $res['sales_id'] = $distribution_record[$res['open_id']]['sales_id']; //所属分销员ID
                    if(!empty($staffs[$res['sales_id']]['hotel_name'])) $res['hotel_name'] = $staffs[$res['sales_id']]['hotel_name']; //分销员所属酒店
                    if(!empty($staffs[$res['sales_id']]['master_dept'])) $res['master_dept'] = $staffs[$res['sales_id']]['master_dept']; //分销员所属部门
                }
            }
        }

        return $result;
    }

    /**
     * 邀请信息
     * @param array $params
     * @param int $startime
     * @param int $endtime
     * @return array
     */
    public function get_invited_record($params=array(),$startime=0,$endtime=0){
        if(!isset($params['inter_id']) && empty($params['inter_id'])) return array();
        $where = array('ir.inter_id'=>$params['inter_id'],'ir.act_id >'=>0,'ir.invited_lvl_id >'=>0,'mi.member_info_id >'=>0);
        if(!empty($startime)) $where['ir.createtime >='] = $startime;
        if(!empty($endtime)) $where['ir.createtime <='] = $endtime;

        $result = $this->_shard_db()->from('invited_record as ir')
                       ->select('ir.*,mi.membership_number,mi.createtime as mi_createtime,mi.name,ml.lvl_name')
                       ->join('member_info as mi','mi.member_info_id = ir.accept_mid','left')
                       ->join('member_lvl as ml','ml.member_lvl_id = ir.member_lvl_id','left')
                       ->where($where)->get()->result_array();
        if(!empty($result)){
            $memids=array();
            foreach ($result as $key=>$vo){
                $memids[] = $vo['to_mid'];
            }
            $where = array('inter_id'=>$params['inter_id'],'is_active'=>'t');
            $_member_list = $this->_shard_db()->select('member_info_id,name,membership_number')->where($where)->where_in('member_info_id',$memids)->get('member_info')->result_array();
            $member_list = array();
            if(!empty($_member_list)){
                foreach ($_member_list as $k=>$v){
                    $member_list[$v['member_info_id']] = $v;
                }
            }

            foreach ($result as &$vo){
                $vo['ir_membership_number'] = !empty($member_list[$vo['to_mid']]['membership_number'])?$member_list[$vo['to_mid']]['membership_number']:' --- ';
                $vo['ir_name'] = !empty($member_list[$vo['to_mid']]['name'])?$member_list[$vo['to_mid']]['name']:' --- ';
            }
        }
        return $result;
    }


    public function get_package_mixed($params=array(),$begin_time=0,$end_time=0){
        $return=array();
        if(empty($params) || empty($params['inter_id'])) return $return;
        $package_id = empty($params['package_id'])?0:$params['package_id'];
        $select = (isset($params['select']) && !empty($params['select']))?$params['select']:'mp.*';
        $this->_shard_db()->select($select)->from('member_package as mp')
                       ->join('member_info as m','m.member_info_id=mp.member_info_id','left');

        if(!empty($begin_time) && is_numeric($begin_time)){
            $this->_shard_db()->where('mp.createtime >=',$begin_time);
        }

        if(!empty($end_time) && is_numeric($end_time)){
            $this->_shard_db()->where('mp.createtime <=',$end_time);
        }

        //获取礼包领取记录
        $where = array('mp.inter_id'=>$params['inter_id'],'mp.package_id'=>$package_id);
        $mplist = $this->_shard_db()->where($where)->group_by('mp.member_info_id')->get()->result_array();
        if(empty($mplist)) return $return;

        $return['list']=$mplist;

        //获取等级配置
        $this->load->model('membervip/admin/public_model','public');
        $member_lvl = $this->public->get_field_by_level_config($params['inter_id'],'member_lvl_id,lvl_name');

        //获取礼包信息
        $this->load->model('membervip/admin/package_model','pm');
        $select = 'p.package_id,p.name,pe.ele_type,pe.ele_value,pe.ele_num';
        $where = array('is_active'=>'t','status'=>1,'lvl_name'=>$member_lvl);
        $package_info = $this->pm->get_package_element($params['inter_id'],$package_id,$select,$where);
        if(empty($package_info) || !is_array($package_info)) return array();
        if(isset($package_info['card']) && !empty($package_info['card'])){
            $this->load->model('membervip/admin/card_model','cardm');
            $card_id=array();
            foreach ($package_info['card'] as $cid){
                $card_id[$cid['card_id']]=$cid['count'];
            }

            //获取卡券信息
            $card_info = $this->cardm->get_card_info($params['inter_id'],$card_id,'card_id,title,can_give_friend,use_time_end_model,use_time_end_day,use_time_end');
            $package_info['card'] = $card_info;
        }
        $pname = $package_info['name'];
        $return['package']=$package_info;
        $return['pname']=$pname;
        return $return;
    }

    /**
     * 获取导出的购卡记录信息
     * @param array $params 条件参数集
     * @param int $begin_time 创建开始时间
     * @param int $end_time 创建结束时间
     * @return array|bool
     */
    public function buycard_record($params = array(),$begin_time=0,$end_time=0){
        if(empty($params['inter_id'])) return array();
        $where = [
            'inter_id'=>$params['inter_id'],
            'pay_status'=>'t',
            'createtime >='=>$begin_time,
            'createtime <='=>$end_time
        ];

        //获取购卡记录
        $deposit_card_pay = $this->_shard_db()->where($where)->order_by('createtime desc')->get('deposit_card_pay')->result_array();
        if(empty($deposit_card_pay)) return false;

        $distribution_nums = [];
        foreach ($deposit_card_pay as $key=>$item){
            if(!empty($item['distribution_num'])) $distribution_nums[] = $item['distribution_num'];
        }
        $distribution_nums = array_unique($distribution_nums);

        $where = [
            'inter_id'=>$params['inter_id']
        ];

        if(!empty($distribution_nums)){
            if($this->input->get('debug')=='1'){
                //获取分销信息
                $_dbhs = $this->db->select('qrcode_id,name,hotel_name')->where($where)->where_in('qrcode_id',$distribution_nums)->group_by('qrcode_id')->get('hotel_staff');//->result_array();
                var_dump($_dbhs);
                echo $this->db->last_query().'<br/>';
                $_distribution = $_dbhs->result_array();
                echo $this->db->last_query().'<br/>';
                var_dump($_distribution);exit;
            }else{
                //获取分销信息
                $_distribution = $this->db->select('qrcode_id,name,hotel_name')->where($where)->where_in('qrcode_id',$distribution_nums)->group_by('qrcode_id')->get('hotel_staff')->result_array();
            }

            $distribution = [];
            foreach ($_distribution as $vv){
                $distribution[$vv['qrcode_id']] = $vv;
            }
        }


        foreach ($deposit_card_pay as &$val){
            $val['staff_name'] = !empty($distribution[$val['distribution_num']]['name'])?$distribution[$val['distribution_num']]['name']:'';
            $val['hotel_name'] = !empty($distribution[$val['distribution_num']]['hotel_name'])?$distribution[$val['distribution_num']]['hotel_name']:'';
            if($val['distribution_num']==0) $val['distribution_num'] = '';
            $val['order_num'] = "'{$val['order_num']}'";
        }
        return $deposit_card_pay;
    }

    /**
     * 获取积分记录
     * @param array $params 条件参数集
     * @param int $begin_time 记录开始时间
     * @param int $end_time 记录结束时间
     * @return array|bool
     */
    public function get_credit_record($params = array(),$begin_time=0,$end_time=0){
        if(empty($params['inter_id'])) return false;
        $where = [
            'c.inter_id'=>$params['inter_id'],
            'c.createtime >='=>$begin_time,
            'c.createtime <='=>$end_time
        ];

        //获取购卡记录
        $result = $this->_shard_db()->from('credit_log c')
                       ->select('c.*,m.member_info_id,m.name,m.nickname,m.membership_number')
                       ->where($where)
                       ->join('member_info m','m.member_info_id = c.member_info_id','left')
                       ->order_by('c.createtime desc')
                       ->get()->result_array();
        if(empty($result)) return false;
        foreach ($result as &$val){
            $val['note'] = !empty($val['note'])?$val['note']:$val['remark'];
            $prefix = $val['log_type']==1?'+':'-';
            $val['amount'] = $prefix.$val['amount'];
        }
        return $result;
    }

    /**
     * 获取储值记录
     * @param array $params 条件参数集
     * @param int $begin_time 记录开始时间
     * @param int $end_time 记录结束时间
     * @return array|bool
     */
    public function get_deposit_record($params = array(),$begin_time=0,$end_time=0){
        if(empty($params['inter_id'])) return false;
        $where = [
            'b.inter_id'=>$params['inter_id'],
            'b.createtime >='=>$begin_time,
            'b.createtime <='=>$end_time
        ];

        //获取购卡记录
        $result = $this->_shard_db()->from('balance_log b')
                       ->select('b.*,m.member_info_id,m.name,m.nickname,m.membership_number')
                       ->where($where)
                       ->join('member_info m','m.member_info_id = b.member_info_id','left')
                       ->order_by('b.createtime desc')
                       ->get()->result_array();
        if(empty($result)) return false;
        foreach ($result as &$val){
            $prefix = $val['log_type']==1?'+':'-';
            $val['amount'] = $prefix.$val['amount'];
            $val['order_id'] = "'{$val['order_id']}'";
        }
        return $result;
    }

    /**
     * 获取优惠券使用记录
     * @param array $params 检索条件
     * @return array
     */
    public function get_useoff_record($params = array()){
        $select = array('cl.*','mc.member_card_id','mc.coupon_code','mc.use_time','mc.card_id','mc.is_online','c.title','m.member_info_id','m.membership_number','m.name','m.nickname','m.telephone','m.cellphone','mc.use_time','mc.useoff_time','mc.is_use','mc.is_useoff','mc.is_active','mc.is_giving','mc.expire_time');
        $fields = implode(',',$select);
        $params['join'] = array(
            array('table'=>'member_card as mc','on'=>"mc.member_card_id = cl.member_card_id",'type'=>'left'),
            array('table'=>'member_info as m','on'=>"m.member_info_id = cl.member_info_id",'type'=>'left'),
            array('table'=>'card as c','on'=>"c.card_id = cl.card_id",'type'=>'left')
        );

        //获取优惠券使用记录
        $result = $this->_shard_db()->from('card_log cl')
            ->select($fields)
            ->where($params['_string'])
            ->where_in('cl.log_type',array(0,2,3,4))
            ->join('member_card mc','mc.member_card_id = cl.member_card_id','left')
            ->join('member_info m','m.member_info_id = cl.member_info_id','left')
            ->join('card c','c.card_id = cl.card_id','left')
            ->group_by('mc.member_card_id')
            ->order_by('mc.use_time desc,cl.card_log_id desc')
            ->get()->result_array();
        foreach ($result as &$val){
            $val['status'] = '未使用';
            if($val['is_active'] == 'f'){
                $val['status'] = '无效';
            }elseif($val['is_use'] == 't' && $val['is_useoff'] == 'f'){
                $val['status'] = '已使用';
            }elseif($val['is_useoff'] == 't'){
                $val['status'] = '已核销';
            }
            if(empty($val['telephone']) && !empty($val['cellphone'])){
                $val['telephone'] = $val['cellphone'];
            }

            $val['name'] = !empty($val['name'])?$val['name']:$val['nickname'];
            $val['coupon_code'] = "{$val['coupon_code']}\t\t";
            $val['telephone'] = "{$val['telephone']}\t\t";

            switch ($val['is_online']){
                case 1:
                    $val['is_online'] = '线上';
                    break;
                case 2:
                    $val['is_online'] = '线下';
                    break;
                case 3:
                    $val['is_online'] = '线上/线下';
                    break;
                default:
                    $val['is_online'] = ' ---- ';
                    break;
            }

            switch ($val['use_type']){
                case 1:
                    $val['use_type'] = '券码核销';
                    break;
                case 2:
                    $val['use_type'] = '密码核销';
                    break;
                case 3:
                    $val['use_type'] = '扫码核销';
                    break;
                case 4:
                    $val['use_type'] = '订房使用';
                    break;
                case 5:
                    $val['use_type'] = '商城使用';
                    break;
                default:
                    $val['use_type'] = '无';
                    break;
            }

            if(!empty($val['operator'])){
                $operators = explode('_@@@_',$val['operator']);
                if(empty($operators[0])){
                    $val['operator'] = '无';
                }else{
                    $val['operator'] = $operators[0];
                }
            }
        }
        return $result;
    }

    /**
     * 导出csv
     * @param $result 导出的数据
     * @param $fields_conf 导出的字段
     * @return string
     */
    public function export_csv($result,$fields_conf,$type='',$inter='',$begin_time=0,$end_time=0){
        if(empty($result) || empty($fields_conf)) return false;
        $_fields_conf = reset($fields_conf);
        if ($type=='member'){
            //会员需求临时增加等级统计
            $this->load->model('membervip/admin/Member_model');

            $where="m.inter_id = '{$inter}' and m.createtime  >= {$begin_time} and m.createtime<={$end_time}";
            $lvl_list = $this->_shard_db()->from('member_info as m')->select('COUNT(m.member_info_id) as count,m.member_lvl_id,ml.lvl_name')
                              ->join('member_lvl as ml','m.member_lvl_id = ml.member_lvl_id','inner')
                              ->where($where)->group_by('m.member_lvl_id')->get()->result_array();

            $csvData = array();
            $csvData[0][] = '会员等级';
            foreach ($lvl_list as $key=>$val){
                $csvData[0][] = $val['lvl_name'];
            }
            $csvData[0][] = '合计';
            $num=0;
            $csvData[1][] = '统计';
            foreach ($lvl_list as $key=>$val){
                $csvData[1][] = $val['count'];
                $num+=$val['count'];
            }
            $csvData[1][] = $num;
        }

        foreach ($_fields_conf as $key=>$item){
            $csvData[2][] = $item['name'];
        }

        $row = 3;
        foreach ($result as $key => $vo ){
            if(is_array($vo)){
                foreach ($vo as $item){
                    foreach ($fields_conf[$key] as $v){
                        switch ($v['field']){
                            case 'card_status':
                                $_data[0] = !empty($item['is_active'])?$item['is_active']:'f';
                                $_data[1] = !empty($item['is_use'])?$item['is_use']:'f';
                                $_data[2] = !empty($item['is_useoff'])?$item['is_useoff']:'f';
                                $_data[3] = !empty($item['is_giving'])?$item['is_giving']:'f';
                                $card_status = call_user_func_array (array($this, '_parse_card_status'),$_data);
                                $tab_data = !empty($card_status)?$card_status:'------';
                                break;
                            case 'use_module':
                                $use_data[0] = !empty($item['use_module'])?$item['use_module']:'';
                                $use_data[1] = !empty($item['use_scene'])?$item['use_scene']:'';
                                $use_module = call_user_func_array (array($this, '_parse_card_module'),$use_data);
                                $tab_data = !empty($use_module)?$use_module:'------';
                                break;
                            case 'useoff_module':
                                $use_data[0] = !empty($item['useoff_module'])?$item['useoff_module']:'';
                                $use_data[1] = !empty($item['useoff_scene'])?$item['useoff_scene']:'';
                                $use_module = call_user_func_array (array($this, '_parse_card_module'),$use_data);
                                $tab_data = !empty($use_module)?$use_module:'------';
                                break;
                            case 'use_time':
                                $_data[0] = !empty($item['is_use'])?$item['is_use']:'f';
                                $_data[1] = !empty($item['use_time'])?$item['use_time']:0;
                                $use_time = call_user_func_array (array($this, $v['func']),$_data);
                                $tab_data = !empty($use_time)?"{$use_time}\t\t":'------';
                                break;
                            case 'useoff_time':
                                $_data[0] = !empty($item['is_useoff'])?$item['is_useoff']:'f';
                                $_data[1] = !empty($item['useoff_time'])?$item['useoff_time']:0;
                                $useoff_time = call_user_func_array (array($this, $v['func']),$_data);
                                $tab_data = !empty($useoff_time)?"{$useoff_time}\t\t":'------';
                                break;
                            case 'membership_number':
                                $tab_data = !empty($item['membership_number'])?"{$item['membership_number']}\t\t":'------';
                                break;
                            case 'telephone':
                                $tab_data = !empty($item['telephone'])?"{$item['telephone']}\t\t":'------';
                                break;
                            default:
                                if(!empty($v['func'])){
                                    $_date=array($item[$v['field']]);
                                    $item[$v['field']] = call_user_func_array(array($this, $v['func']),$_date);
                                }
                                $tab_data = isset($item[$v['field']]) ? $item[$v['field']] : '------';
                                break;
                        }
                        $tab_data= str_replace(PHP_EOL, '', $tab_data); //处理换行-微信昵称可能包含换行符

                        $pattern = '/[^\x00-\x80]/';
                        if(preg_match($pattern,$tab_data) && !empty($tab_data)){//mb_convert_encoding
                            $csvData[$row][] = $tab_data;
                        }else{
                            $csvData[$row][] = $tab_data;
                        }
                    }
                    $row++;
                }
            }
        }
        return $csvData;
    }

    /**
     * 导出excel
     * @param $result 导出的数据
     * @param $fields_conf 导出的字段
     * @return string
     */
    public function export_excel($result=array(),$fields_conf=array()){
        if(empty($result) || empty($fields_conf)) return false;
        $this->load->library ( 'PHPExcel' );
        $this->load->library ( 'PHPExcel/IOFactory' );
        $objPHPExcel = new PHPExcel ();
        $objPHPExcel->setActiveSheetIndex(0);  //设置活动表
        $objPHPExcel->getProperties()->setTitle ( "export" )->setDescription ( "none" );
        $col = 1;
        $lv=0;
        foreach ($fields_conf as $kc=>$vc){
            $sheet_title = isset($result[$kc.'_name'])?$result[$kc.'_name']:$kc.'数据';
            if($lv=='0'){
                $objPHPExcel->getSheet($lv)->setTitle($sheet_title);
                foreach ($vc as $key=>$vo){
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($key, $col,$vo['name']);
                }
            }else{
                $objPHPExcel->createSheet();
                $objPHPExcel->setActiveSheetIndex($lv);   //设置第$lv个表为活动表，提供操作句柄
                $objPHPExcel->getSheet($lv)->setTitle($sheet_title);
                foreach ($vc as $key=>$vo){
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($key, $col,$vo['name']);
                }
            }
            $lv++;
        }

        // Fetching the table data
        $objPHPExcel->setActiveSheetIndex(0);
        if(!empty($result)){
            $index = 0;
            foreach($result as $key=>$vo){
                if(is_array($vo)){
                    $row = 2;
                    $objPHPExcel->setActiveSheetIndex($index);
                    foreach ($vo as $ki=>$item){
                        foreach ($fields_conf[$key] as $k=>$v){
                            switch ($v['field']){
                                case 'card_status':
                                    $_data[0] = !empty($item['is_active'])?$item['is_active']:'f';
                                    $_data[1] = !empty($item['is_use'])?$item['is_use']:'f';
                                    $_data[2] = !empty($item['is_useoff'])?$item['is_useoff']:'f';
                                    $_data[3] = !empty($item['is_giving'])?$item['is_giving']:'f';
                                    $card_status = call_user_func_array (array($this, '_parse_card_status'),$_data);
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( $k, $row, !empty($card_status)?$card_status:'------');
                                    break;
                                case 'use_module':
                                    $use_data[0] = !empty($item['use_module'])?$item['use_module']:'';
                                    $use_data[1] = !empty($item['use_scene'])?$item['use_scene']:'';
                                    $use_module = call_user_func_array (array($this, '_parse_card_module'),$use_data);
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( $k, $row, !empty($use_module)?$use_module:'------');
                                    break;
                                case 'useoff_module':
                                    $use_data[0] = !empty($item['useoff_module'])?$item['useoff_module']:'';
                                    $use_data[1] = !empty($item['useoff_scene'])?$item['useoff_scene']:'';
                                    $use_module = call_user_func_array (array($this, '_parse_card_module'),$use_data);
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( $k, $row, !empty($use_module)?$use_module:'------');
                                    break;
                                case 'use_time':
                                    $_data[0] = !empty($item['is_use'])?$item['is_use']:'f';
                                    $_data[1] = !empty($item['use_time'])?$item['use_time']:0;
                                    $use_time = call_user_func_array (array($this, $v['func']),$_data);
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($k, $row, !empty($use_time)?$use_time:'------');
                                    break;
                                case 'useoff_time':
                                    $_data[0] = !empty($item['is_useoff'])?$item['is_useoff']:'f';
                                    $_data[1] = !empty($item['useoff_time'])?$item['useoff_time']:0;
                                    $useoff_time = call_user_func_array (array($this, $v['func']),$_data);
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($k, $row, !empty($useoff_time)?$useoff_time:'------');
                                    break;
                                default:
                                    if(isset($v['func']) && !empty($v['func'])){
                                        $_date=array($item[$v['field']]);
                                        $item[$v['field']] = call_user_func_array(array($this, $v['func']),$_date);
                                    }
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( $k, $row, isset($item[$v['field']]) ? $item[$v['field']] : '------' );
                                    break;
                            }
                        }
                        $row++;
                    }
                    $index++;
                }
            }
        }
        return $objPHPExcel;
    }

    /**
     * 邀请好友记录分析数据
     * @param $params 参数集
     * @return string|array
     */
    public function get_invited_info($params){
        if(empty($params['inter_id'])) return 'inter_id is null';
        if(empty($params['member_lvl'])) return '平台等级配置为空';
        $member_lvl = $params['member_lvl'];
        $lvls = array_keys($member_lvl);
        array_shift($lvls);
        $st = strtotime(date('Y-01-01 00:00:00'));
        $et = strtotime(date('Y-m-t 23:59:59',time()));
        $invited_record = $this->_shard_db()->select('r.id,r.act_id,r.inter_id,r.to_mid,r.accept_mid,r.member_lvl_id,r.createtime')
                               ->from('invited_record r')
                               ->join('member_info as m','r.accept_mid = m.member_info_id')
                               ->where(array('r.inter_id'=>$params['inter_id'],'r.act_id >'=>0,'r.invited_lvl_id >'=>0,'m.member_info_id >'=>0,'r.createtime >='=>$st,'r.createtime <='=>$et))
                               ->where_in('r.member_lvl_id',$lvls)
                               ->get()->result_array();

        if(empty($invited_record)) return '邀请记录为空';
        $invited_lvls = array(); //邀请的等级
        foreach ($invited_record as $item){//取会员等级
            $invited_lvls[] = $item['member_lvl_id'];
        }
        $invited_lvls = array_unique($invited_lvls);
        rsort($invited_lvls);

        $month = intval(date('m')); //统计到当月
        $invited_lvl_count = array(); //每月邀请的等级的数量
        $invited_totals = array(); //每月邀请的的总数
        foreach ($invited_lvls as $lvlid){
            foreach ($invited_record as $item){//取会员等级
                $mymonth = intval(date('m',$item['createtime']));
                for ($i=1;$i<=$month;$i++){
                    if($lvlid==$item['member_lvl_id'] && $mymonth==$i) {
                        if(empty($invited_lvl_count[$lvlid][$i]))
                            $invited_lvl_count[$lvlid][$i] = 1;
                        else
                            $invited_lvl_count[$lvlid][$i] = $invited_lvl_count[$lvlid][$i] + 1;

                        if(empty($invited_totals[$i])) $invited_totals[$i] = 1; else $invited_totals[$i] = $invited_totals[$i] + 1;
                    }
                }
            }
        }

        $ring_ratio = array(); //环比增长率
        foreach ($invited_totals as $k => $vo){
            $ring_ratio[$k] = 0;
            $compare_time = $this->get_compare($invited_totals[$k],0);
            $compare_time = round($compare_time, 4) * 100 . '%';
            $ring_ratio[$k] = $compare_time;
            if($k>1) {
                $compare_time = $this->get_compare($invited_totals[$k],$invited_totals[$k-1]);
                $compare_time = round($compare_time, 4) * 100 . '%';
                $ring_ratio[$k] = $compare_time;
            }
        }

        $ret['invited_lvls'] = $invited_lvls;
        $ret['invited_lvl_count'] = $invited_lvl_count;
        $ret['invited_totals'] = $invited_totals;
        $ret['ring_ratio'] = $ring_ratio;
        return $ret;
    }

    /**
     * 获取发送任务结果记录
     * @param array $params
     * @return bool|array
     */
    public function get_task_item($params = array()){
        $task_id = !empty($params['id'])?$params['id']:0;
        $inter_id = $this->session->get_admin_inter_id();

        $where = array(
            'sk.inter_id'=>$inter_id,
            'sk.task_id'=>$task_id,
            'sk.is_active'=>'t'
        );
        $field = "sk.*";
        $this->_shard_db()->from('send_task sk')->select($field);
        $info = $this->_shard_db()
            ->where($where)->get()->row_array();
        $this->load->library("MYLOG");
        MYLOG::w(@json_encode(array($info,$where,$this->_shard_db()->last_query())),'admin/membervip/debug-log','task-export');


        if(empty($info)) return false;

        $this->load->model('membervip/common/Public_model','common_model');
        $member_lvl = $this->common_model->get_field_by_level_config($inter_id,'member_lvl_id,lvl_name,is_default');

        //获取优惠券信息
        $this->load->model('membervip/admin/Card_model','card_model');
        $card_list = $this->card_model->get_field_by_field($inter_id);

        //获取礼包信息
        $this->load->model('membervip/admin/Package_model','package_model');
        $package_list = $this->package_model->get_field_by_field($inter_id);

        $where = array(
            'ske.inter_id'=>$inter_id,
            'ske.task_id'=>$task_id
        );

        $field = "ske.*,m.member_lvl_id,m.membership_number as membernum,m.telephone as mem_telephone,m.cellphone as mem_cellphone,m.name,m.nickname";
        $this->_shard_db()->from('send_task_event ske')->select($field);

        if(!empty($params['keywords'])){
            $this->_shard_db()->or_like('ske.membership_number',$params['keywords']);
            $this->_shard_db()->or_like('skem.telephone',$params['keywords']);
            $this->_shard_db()->or_like('m.cellphone',$params['keywords']);
        }

        $send_type_value = '';
        if($info['send_type']==1){
            $send_type_value = !empty($card_list[$info['send_value']])?$card_list[$info['send_value']]:'';
        }elseif ($info['send_type']==2){
            $send_type_value = !empty($package_list[$info['send_value']])?$package_list[$info['send_value']]:'';
        }

        $list = $this->_shard_db()
            ->join('member_info m','m.member_info_id = ske.member_info_id','left')
            ->where($where)->get()->result_array();
        MYLOG::w(@json_encode(array($list,$where,$this->_shard_db()->last_query())),'admin/membervip/debug-log','task-export');
        if(!empty($list)){
            foreach ($list as &$item){
                $item['state'] = $item['state'] == 1 ? '成功' : '失败';
                $item['member_lvl_id'] = !empty($member_lvl[$item['member_lvl_id']]) ? $member_lvl[$item['member_lvl_id']] : '';
                $item['send_name'] = $send_type_value;
                $item['send_count'] = $info['send_count'];
                $item['err_msg'] = $item['state'] == 1 ? $item['msg'] : $item['msg'];

                $entry_method = $item['entry_method'];
                $openid = '';
                if($info['send_target'] == 3 && $entry_method === 0){
                    json_decode($info['target_value']);
                    if((json_last_error() == JSON_ERROR_NONE)){
                        $target_value = @json_decode($info['target_value'],true);
                        $send_target_field = !empty($target_value['field'])?$target_value['field']:0;
                        if($send_target_field == 4){
                            $openid = $item['openid'];
                        }
                    }
                }elseif ($entry_method == 4){
                    $openid = $item['openid'];
                }

                $item['openid'] = $openid;
            }
        }

        return $list;
    }

    /**
     * 环比统计
     * @param $current int
     * @param $prev int
     * @return int|float
     */
    public function get_compare($current, $prev)
    {
        if (floatval($current) == 0)
            return 0;

        if (floatval($prev) == 0)
            return 1;

        return ($current - $prev) / $prev;
    }
}