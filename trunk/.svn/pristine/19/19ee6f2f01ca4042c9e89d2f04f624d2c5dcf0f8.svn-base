<?php

/**
 * 会员4.0后台数据处理模块
 * Created by knight.
 * User: ibuki
 * Date: 16/7/30
 * Time: 下午9:25
 */
class Public_model extends MY_Model_Member {

    const SPACE = ' '; //空格符
    protected $_pk = '';

    /**
     * 获取数据表的字段列表
     * @param $table 表名
     * @return array
     */
    protected function get_list_fields($table){
        $result = $this->_shard_db()->query("SHOW FULL COLUMNS FROM iwide_$table")->result_array();
        $fields = array();
        foreach ($result as $k=>$vo){
            if($vo['Key']=='PRI') $this->_pk = $vo['Field'];
            $fields[] = $vo['Field'];
        }
        return $fields;
    }

    /**
     * 模糊查询字段
     * @param string $alias
     * @param int $type
     * @return array
     */
    protected function get_like_field($alias='',$type=1){
        $like_field = array();
        switch ($type){
            case 1:
                $like_field = array($alias.'.member_info_id',$alias.'.nickname',$alias.'.name', $alias.'.membership_number', $alias.'.member_lvl_id',$alias.'.telephone',$alias.'.cellphone');
                break;
            case 2:
                $like_field = array($alias.'.package_id',$alias.'.name',$alias.'.remark');
                break;
            case 3:
                $like_field = array($alias.'.card_id',$alias.'.title',$alias.'.description',$alias.'.card_stock');
                break;
            case 4:
                $like_field = array($alias.'.member_info_id',$alias.'.coupon_code','m.membership_number','m.nickname','m.name','c.title',$alias.'.remark');
                break;
            case 5:
                $like_field = array('mi.name','mi.membership_number','ml.lvl_name');
                break;
            case 6:
                $like_field = array($alias.'.nickname',$alias.'.membership_number',$alias.'.name',$alias.'.telephone',$alias.'.sex',$alias.'.company_name',$alias.'.employee_id');
                break;
            case 8:
                $like_field = array($alias.'.name',$alias.'.nickname',$alias.'.company_name',$alias.'.duty',$alias.'.remark',$alias.'.unpass_reason',$alias.'.membership_number',$alias.'.last_update_time');
                break;
            case 9:
                $like_field = array($alias.'.card_rule_id',$alias.'.rule_title');
                break;
            case 10:
                $like_field = array($alias.'.credit_log_id','m.nickname','m.name','m.membership_number',$alias.'.note',$alias.'.remark');
                break;
            case 11:
                $like_field = array($alias.'.deposit_card_pay_id',$alias.'.member_lvl_name',$alias.'.name',$alias.'.nickname',$alias.'.membership_number');
                break;
            case 12:
                $like_field = array($alias.'.balance_log_id','m.nickname','m.name','m.membership_number',$alias.'.note');
                break;
            case 13:
                $like_field = array($alias.'.coupon_code','m.telephone','m.cellphone','m.membership_number');
                break;
            case 15:
                $like_field = array('mc.coupon_code',$alias.'.card_id','c.title','mc.useoff_time','mc.is_use','mc.is_useoff','mc.is_active');
                break;
        }
        return $like_field;
    }

    /**
     * 获取会员等级配置列表（默认等级排在第一）
     * @param int $inter_id
     * @param string $field
     * @return array
     */
    public function get_admin_member_lvl($inter_id=0,$field='*'){
        if(empty($inter_id)) return array();
        if(is_array($field) && count($field) > 0){
            $field = implode(',',$field);
        }
        $where = array('inter_id'=>$inter_id);
        $member_lvl = $this->_shard_db()->select($field)->where($where)->get('member_lvl')->result_array();
        $member_lvl = $this->custom_sort($member_lvl,'is_default','t');
        return $member_lvl;
    }

    /**
     * 获取会员等级配置 （格式：array([key]=>[value])）
     * @param int $inter_id
     * @param string $field
     * @return array
     */
    public function get_field_by_level_config($inter_id=0,$field='*'){
        if(empty($inter_id)) return array();
        $where = array('inter_id'=>$inter_id);
        $member_lvl = $this->_shard_db()->select($field)->where($where)->get('member_lvl')->result_array();
        $member_lvl = $this->field_by_value($member_lvl,'member_lvl_id','lvl_name');
        return $member_lvl;
    }

    /**
     * 重组数组，改变键值
     * @param array $data 数据集
     * @param string $key 指定键值
     * @param string $vkey 指定$key所对应的值
     * @return array
     */
    public function field_by_value($data=array(),$key='',$vkey=''){
        if(empty($data)) return array();
        $list = array();
        $this->load->helper('common_helper');
        uasort($data,"my_sort"); //对分组排序，由小到大根据键值排序
        foreach ($data as $k => $vo){
            $list[$vo[$key]] = $vo[$vkey];
        }

        return $list;
    }

    /**
     * 根据某字段的值自定义排序
     * @param array $data 数组
     * @param string $field 字段
     * @param string $value 字段的值
     * @return array
     */
    protected function custom_sort($data=array(),$field='',$value=''){
        if(empty($data)) return array();
        $first = array();
        foreach ($data as $key => $item){
            if(isset($item[$field]) && $item[$field]==$value){
                $first = $item;
                unset($data[$key]);
            }
        }

        if(!empty($first)) array_unshift($data,$first); //插入到最開始的位置
        return $data;
    }

    /**
     * 获取会员模式
     * @param int $inter_id 酒店集团ID
     * @return array
     */
    public function get_member_mode($inter_id=0){
        if(empty($inter_id)) return array();
        $where = array('inter_id'=>$inter_id,'type_code'=>'member');
        $member_mode = $this->_shard_db()->select('value')->where($where)->get('inter_member_config')->row_array();
        if(isset($member_mode['value'])) return $member_mode['value'];
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
        foreach ($data as $key => $item){
            if(!in_array($key, $list_fields)) unset($data[$key]);
        }
        return $data;
    }

    /**
     * 添加显示配置
     * @param $data 添加数据
     * @return bool
     */
    public function add_data($data=array(),$table=''){
        if(empty($table)) return false;
        if(empty($data) || is_string($data)) return false;
        $result = $this->_shard_db(true)->set($data)->insert($table);
        $this->_write_log($this->_shard_db(true)->last_query(),'insert-SQL','Public_model/sql');
        if($this->input->get('debug') == 1){
            $this->_write_log($this->_shard_db(true)->last_query(),'insert-SQL','Public_model/sql');
        }
        if($result){
            return $this->_shard_db(true)->insert_id();
        }
        return $result;
    }

    /**
     * 更新数据
     * @param $params 条件
     * @param $data 更新数据
     * @return bool
     */
    public function update_save($params=array(),$data=array(),$table=''){
        if(!isset($params['inter_id'])) return false;
        if(empty($table)) return false;
        if(empty($data) || is_string($data)) return false;
        $where['inter_id'] = $params['inter_id'];
        $list_fields = $this->_shard_db(true)->list_fields($table);
        foreach ($list_fields as $field){
            if(isset($params[$field])) $where[$field] = $params[$field];
        }

        $result = $this->_shard_db(true)->where($where)->set($data)->update($table);
        if($this->input->get('debug') == 1){
            $this->_write_log($this->_shard_db(true)->last_query(),'update-SQL','Public_model/sql');
        }
        if($result===true) $this->_shard_db(true)->affected_rows();
        return $result;
    }

    /**
     * 删除数据
     * @param array $params 条件
     * @param string $table 表名
     * @return bool
     */
    public function delete_data($params = array(),$table = ''){
        if(empty($params)) return false;
        if(empty($table)) return false;
        $result = $this->_shard_db(true)->where($params)->delete($table);
        return $result;
    }

    /**
     * 数据集 总数
     */
    public function get_total($params=array(),$table='',$condition=array()){
        $inter_id = $params['inter_id'];
        $where=array($table.'.inter_id'=>$inter_id);
        if(!empty($condition)) $where = array_merge($where,$condition);
        $this->_shard_db()->where($where);
        $count = $this->_shard_db()->get($table)->num_rows();
        return $count;
    }

    /**
     * 获取数据(单条)
     * @param $params 条件
     * @param $table 指定数据表
     * @return array
     */
    public function get_info($where=array(),$table='',$field='*'){
        if(empty($table) || empty($where)) return false;
        $info = $this->_shard_db()->select($field)
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
     * 获取数据列表
     * @param array $params 条件参数组
     * @param array $select 查询字段
     * search[value]: 搜索字眼
     * @return mixed
     */
    public function get_admin_list($params=array(),$select=array(),$map=array()){
        $return['total'] = 0;
        $return['data'] = array();
        $table = $params['table_name'];
        $dbfields = $this->get_list_fields($table);
        $exp=array('>','<','!=','<>','>=','<=');
        $where = $where_in = array();
        foreach ($params as $k=>$v){
            //过滤非数据库字段，以免产生sql报错，把in匹配另外处理
            if(in_array($k, $dbfields) ){
                if( is_array($v)){
                    if(!empty($v[0]) && isset($v[1]) && in_array($v[0],$exp))
                        $where[$k.self::SPACE.$v[0]]=$v[1];
                    else
                        $where_in[$k]= $v;
                } else {
                    $where[$k]= $v;
                }
            }

            if(strpos($k,'.')!==false){
                $fk = explode('.',$k);
                if(in_array($fk[1], $dbfields)) {
                    if( is_array($v)){
                        if(!empty($v[0]) && isset($v[1]) && in_array($v[0],$exp))
                            $where[$k.self::SPACE.$v[0]]=$v[1];
                        else
                            $where_in[$k]= $v;
                    } else {
                        $where[$k]= $v;
                    }
                }
            }
        }

        if(isset($params['search']) && is_array($params['search']) && !empty($params['search'])){
            $params['f_like'] = $params['search'];
        }

        $alias = 'a';
        if(isset($params['alias']) && !empty($params['alias'])) $alias = $params['alias']; //主表别名

        $pk= $alias.'.'.$this->_pk;
        if(isset($params['sort_field']) && isset($params['sort_direct'])){
            if(strpos($params['sort_field'],',')!==false){
                $sort_field = explode(',',$params['sort_field']);
                $sort = implode(' '.$params['sort_direct'].',',$sort_field).' '.$params['sort_direct'];
            }else{
                $sort= $params['sort_field']. ' '. $params['sort_direct'];
            }
        } else $sort= "{$pk} DESC";  //默认排序

        $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
        $page_size= isset($params['page_size'])? $params['page_size']: $num; //获取行数
        if(isset($params['length'])){
            $page_size = $params['length'];
        }

        $return['page_size'] = $page_size;

        $current_page = isset($params['page_num'])? $params['page_num']: 1;

        $return['current_page'] = $current_page;

        if(count($select)==0) $select = array($alias.'.*');
        $select= count($select)==0? '*': implode(',', $select);
        $offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0; //获取起始行
        if(isset($params['start'])){
            $offset = $params['start'];
        }

        $this->_shard_db()->select("COUNT($alias.$this->_pk) as count")->from($table.' as '.$alias);

        //联表查询
        if(isset($params['join']) && !empty($params['join']) && is_array($params['join'])){
            $join = $params['join'];
            foreach ($join as $key=>$item){
                $this->_shard_db()->join($item['table'],$item['on'],$item['type']);
                if(isset($item['exp']) && !empty($item['exp'])){
                    $this->_shard_db()->where($item['exp']);
                }
            }
        }

        //in条件
        if( count($where_in)>0 ){
            foreach ($where_in as $k => $v ){
                if( count($v) ) $this->_shard_db()->where_in($k, $v);
            }
        }
        if(isset($params['f_like']['value']) && !empty($params['f_like']['value'])){
            //模糊匹配参数
            $like_field = $this->get_like_field($alias,$params['f_type']);
            $this->_shard_db()->group_start();
            foreach ($like_field as $sv) {
                $this->_shard_db()->or_like($sv,$params['f_like']['value']);
            }
            $this->_shard_db()->group_end();
        }

        $tbug = $this->input->get('tbug');
        if($tbug=='1'){
            echo 'cstartime:'.date('Y-m-d H:i:s');
            echo '<br/>';
        }

        if(!empty($params['extendedWhere'])){
            if(count($params['extendedWhere'])!=count($params['extendedWhere'],1)){
                foreach ($params['extendedWhere'] as $k=>$w){
                    if(count($w)!=count($w,1)){
                        foreach ($w as $v){
                            if(!empty($v[0]) && isset($v[1]) && in_array($v[0],$exp))
                                $this->_shard_db()->where($k.self::SPACE.$v[0],$v[1]);
                            else
                                $this->_shard_db()->where($k,$v);
                        }
                    }else{
                        if(!empty($w[0]) && isset($w[1]) && in_array($w[0],$exp))
                            $this->_shard_db()->where($k.self::SPACE.$w[0],$w[1]);
                        else
                            $this->_shard_db()->where($k,$w);
                    }
                }
            }elseif (is_string($params['extendedWhere'])){
                $this->_shard_db()->where($params['extendedWhere']);
            }
        }

        //分组查询
        if(isset($params['group_by']) && !empty($params['group_by'])) $this->_shard_db()->group_by($params['group_by']);

        $total= $this->_shard_db()->where($where)->get()->result_array();

        if($this->input->get('debug')=='1') {
            echo $this->_shard_db()->last_query();
            echo '<br/>';
        }
        if($tbug=='1'){
            echo 'cendtime:'.date('Y-m-d H:i:s');
            echo '<br/>';
        }

        if(isset($params['group_by']) && !empty($params['group_by'])){
            $return['total'] = count($total);
        }else{
            $return['total'] = $total[0]['count'];
        }

        $this->_shard_db()->select("{$select}")->from($table.' as '.$alias);

        //联表查询
        if(isset($params['join']) && !empty($params['join']) && is_array($params['join'])){
            $join = $params['join'];
            foreach ($join as $key=>$item){
                $this->_shard_db()->join($item['table'],$item['on'],$item['type']);
                if(isset($item['exp']) && !empty($item['exp'])){
                    $this->_shard_db()->where($item['exp']);
                }
            }
        }

        //in条件
        if( count($where_in)>0 ){
            foreach ($where_in as $k => $v ){
                if( count($v) ) $this->_shard_db()->where_in($k, $v);
            }
        }

        if(isset($params['f_like']['value']) && !empty($params['f_like']['value'])){
            //模糊匹配参数
            $like_field = $this->get_like_field($alias,$params['f_type']);
            $this->_shard_db()->group_start();
            foreach ($like_field as $sv) {
                $this->_shard_db()->or_like($sv,$params['f_like']['value']);
            }
            $this->_shard_db()->group_end();
        }


        if($tbug=='1'){
            echo 'startime:'.date('Y-m-d H:i:s');
            echo '<br/>';
        }

        if(!empty($params['extendedWhere'])){
            if(count($params['extendedWhere']) != count($params['extendedWhere'],1)){
                foreach ($params['extendedWhere'] as $k=>$w){
                    if(count($w) != count($w,1)){
                        foreach ($w as $v){
                            if(!empty($v[0]) && isset($v[1]) && in_array($v[0],$exp))
                                $this->_shard_db()->where($k.self::SPACE.$v[0],$v[1]);
                            else
                                $this->_shard_db()->where($k,$v);
                        }
                    }else{
                        if(!empty($w[0]) && isset($w[1]) && in_array($w[0],$exp))
                            $this->_shard_db()->where($k.self::SPACE.$w[0],$w[1]);
                        else
                            $this->_shard_db()->where($k,$w);
                    }
                }
            }elseif (is_string($params['extendedWhere'])){
                $this->_shard_db()->where($params['extendedWhere']);
            }
        }

        //分组查询
        if(isset($params['group_by']) && !empty($params['group_by'])) $this->_shard_db()->group_by($params['group_by']);

        $result = $this->_shard_db()->where($where)->order_by($sort)->limit($page_size, $offset)->get()->result_array();
        if(isset($params['opt']) && !empty($result)){
            switch ($params['opt']){
                case '1':
                    //计算会员的有效优惠券数量
                    if(!empty($result)){
                        $mids = array();
                        foreach ($result as $mid){
                            $mids[] = $mid['member_info_id'];
                        }
                        $mids = array_unique($mids); //去除重复会员ID
                        $expire = strtotime(date('Y-m-d 00:00:00'));
                        $where = array('is_useoff'=>'f','is_use'=>'f','is_active'=>'t','expire_time >'=>$expire);
                        $_member_card = $this->_shard_db()->select('COUNT(member_card_id) as mcount,member_info_id')->where_in('member_info_id',$mids)->where($where)->group_by('member_info_id')->get('member_card')->result_array(); //每个会员拥有的优惠券数量

                        $member_card = array();
                        foreach ($_member_card as $k=>$v){
                            $member_card[$v['member_info_id']] = $v['mcount'];
                        }

                        foreach ($result as &$mem){ //优惠券数量整合到会员数据中
                            $mem['mcount'] = !empty($member_card[$mem['member_info_id']])?$member_card[$mem['member_info_id']]:0;
                        }

                        $this->load->helper('common_helper');
                        if(!empty($params['order'][0]['column']) && $params['order'][0]['column']=='8'){
                            if($params['order'][0]['dir']=='desc') usort($result,"my_rsort");
                            if($params['order'][0]['dir']=='asc') usort($result,"my_sort");
                        }

                        $member_lvl = array();
                        foreach ($params['member_lvl_data'] as $lvl){
                            $member_lvl[$lvl['member_lvl_id']] = $lvl['lvl_name'];
                        }

                        foreach ($result as &$mem){ //会员等级名称整合到会员数据中
                            $mem['lvl_name'] = !empty($member_lvl[$mem['member_lvl_id']])?$member_lvl[$mem['member_lvl_id']]:' --- ';
                        }
                    }
                    break;
                case '4':
                    $memids=array();
                    foreach ($result as $key=>$vo){
                        $memids[] = $vo['to_mid'];
                    }
                    $where = array('inter_id'=>$params[$params['alias'].'.inter_id'],'is_active'=>'t');

                    $this->_shard_db()->select('member_info_id,name,membership_number');
                    if(!empty($params['f_like']['value'])){
                        $this->_shard_db()->group_start();
                        $this->_shard_db()->or_like('name',$params['f_like']['value']);
                        $this->_shard_db()->or_like('membership_number',$params['f_like']['value']);
                        $this->_shard_db()->group_end();
                    }

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
                    break;
                case '5':
                    $admin_profile = $this->session->userdata('admin_profile');
                    $member_lvl = $this->pum->get_field_by_level_config($admin_profile['inter_id'],'member_lvl_id,lvl_name,lvl_up_sort');
                    foreach ($result as &$da){
                        if(!empty($member_lvl[$da['member_lvl_id']])) $da['lvl_name'] = $member_lvl[$da['member_lvl_id']];
                        if(empty($da['subtime'])) $da['subtime'] = $da['createtime'];
                    }
                    break;
                case '6':
                    $admin_profile = $this->session->userdata('admin_profile');
                    $where= array('inter_id'=>$admin_profile['inter_id']);
                    $table= 'core_admin';
                    $core_admin = $this->db->select('admin_id,username,nickname')->get_where($table, $where)->result_array();
                    if(!empty($core_admin)){
                        $c_admin = array();
                        foreach ($core_admin as $key => $ca){
                            $c_admin[$ca['admin_id']] = $ca;
                        }

                        foreach ($result as &$vo){
                            $vo['admin_id'] = !empty($c_admin[$vo['admin_id']]['username'])?$c_admin[$vo['admin_id']]['username']:$c_admin[$vo['admin_id']]['nickname'];
                            if(!empty($vo['result'])) $vo['result'] = '成功';
                            $vo['content'] = '<div style="word-break:break-all;word-wrap:break-word;">'.$vo['content'].'</div>';//JSON_UNESCAPED_UNICODE
                        }
                    }
                    break;
                case '9':
                    $card_ids = [];
                    $package_ids = [];
                    foreach ($result as $item){
                        $card_ids[] = $item['card_id'];
                        $package_ids[] = $item['package_id'];
                    }
                    $card_ids = array_unique($card_ids);
                    $package_ids = array_unique($package_ids);
                    $ext_package = [];
                    if(!empty($package_ids)){
                        $_ext_package = $this->_shard_db()->where('inter_id',$params[$params['alias'].'.inter_id'])->where_in('package_id',$package_ids)->get('package')->result_array();
                        foreach ($_ext_package as $v){
                            $ext_package[$v['package_id']] = $v;
                        }
                    }

                    if(!empty($card_ids)){
                        $_ext_card = $this->_shard_db()->where('inter_id',$params[$params['alias'].'.inter_id'])->where_in('card_id',$card_ids)->get('card')->result_array();
                        $ext_card = [];
                        foreach ($_ext_card as $v){
                            $ext_card[$v['card_id']] = $v;
                        }
                    }
                    foreach ($result as &$item){
                        if($item['is_package']=='t'){
                            $item['common_name'] = !empty($ext_package[$item['package_id']]['name'])?$ext_package[$item['package_id']]['name']:' -- ';
                            $item['common_notice'] = !empty($ext_package[$item['package_id']]['remark'])?$ext_package[$item['package_id']]['remark']:' -- ';
                            $item['common_type'] = '礼包';
                            $item['common_stock'] = ' -- ';
                        }else{
                            $item['common_name'] = !empty($ext_card[$item['card_id']]['title'])?$ext_card[$item['card_id']]['title']:' -- ';
                            $item['common_notice'] = !empty($ext_card[$item['card_id']]['card_note'])?$ext_card[$item['card_id']]['card_note']:' -- ';
                            $item['common_type'] = ' -- ';
                            if(!empty($ext_card[$item['card_id']]['card_type'])){
                                $card_type = $ext_card[$item['card_id']]['card_type'];
                                switch ($card_type){
                                    case 1:$item['common_type'] = '抵用券';break;
                                    case 2:$item['common_type'] = '折扣券';break;
                                    case 3:$item['common_type'] = '兑换券';break;
                                    case 4:$item['common_type'] = '储值券';break;
                                }
                            }
                            $item['common_stock'] = !empty($ext_card[$item['card_id']]['card_stock'])?$ext_card[$item['card_id']]['card_stock']:' -- ';
                        }
                        if(strpos($item['createtime'],' ')!==false){
                            $item['createtime'] = implode('<br>',explode(' ',$item['createtime']));
                        }
                    }
                    break;
                case '10':
                    if(!empty($result)){
                        foreach ($result as &$val){
                            if(empty($val['remark'])) $val['remark'] = '';
                            $val['note'] = !empty($val['note'])?$val['note']:$val['remark'];
                            $prefix = $val['log_type']==1?'+':'-';
                            $val['amount'] = $prefix.$val['amount'];
                        }
                    }
                    break;
                case '13':
                    if(!empty($result)){
                        foreach ($result as &$val){
                            $expire_time = strtotime(date('Y-m-d 23:59:59',$val['expire_time']));//取当天结束时间
                            $val['status'] = '未使用';
                            if($val['is_active'] == 'f'){
                                $val['status'] = '无效';
                            }elseif($expire_time < time() && $val['is_use'] == 'f' && $val['is_useoff'] == 'f'){
                                $val['status'] = '已过期';
                            }elseif($val['is_use'] == 't' && $val['is_useoff'] == 'f'){
                                $val['status'] = '已使用';
                            }elseif($val['is_useoff'] == 't' && $val['is_use'] == 't'){
                                $val['status'] = '已核销';
                            }elseif ($val['is_giving'] == 't'){
                                $val['status'] = '转赠中';
                            }
                            if(empty($val['telephone']) && !empty($val['cellphone'])){
                                $val['telephone'] = $val['cellphone'];
                            }

                            $val['name'] = !empty($val['name'])?$val['name']:$val['nickname'];
                        }
                    }
                    break;
                case '15':
                    if(!empty($result)){
                        foreach ($result as &$val){
                            $val['status'] = '未使用';
                            if($val['is_active'] == 'f'){
                                $val['status'] = '无效';
                            }elseif($val['is_use'] == 't' && $val['is_useoff'] == 'f'){
                                $val['status'] = '已使用';
                            }elseif($val['is_useoff'] == 't' && $val['is_use'] == 't'){
                                $val['status'] = '已核销';
                            }elseif ($val['is_giving'] == 't'){
                                $val['status'] = '转赠中';
                            }
                            if(empty($val['telephone']) && !empty($val['cellphone'])){
                                $val['telephone'] = $val['cellphone'];
                            }

                            $val['name'] = !empty($val['name'])?$val['name']:$val['nickname'];


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
                    }
                    break;
            }
        }

        if($tbug=='1'){
            echo 'endtime:'.date('Y-m-d H:i:s');
        }
        if(!empty($result)){
            $return['data'] = $result;
        }
        return $return;
    }

    /**
     * @param array $params 条件参数组
     * @param array $select 查询字段
     * @param string $format
     * @return array
     */
    public function get_admin_filter($params=array(),$select=array(),$other='',$format='array'){
        $return = $this->get_admin_list($params,$select);

        $total = $return['total'];
        $result = $return['data'];
        if(isset($params['ispackage']) && $params['ispackage']=='1'){
            $this->load->model('membervip/admin/package_model','pk_model');
            $result = $this->pk_model->parse_package($result,$other); //处理礼包信息的所包含内容
        }

        if(isset($params['iscard']) && $params['iscard']=='1'){
            $this->load->model('membervip/admin/card_model','c_model');
            $card_id = $params[$params['alias'].'.card_id'];
            $results = $this->c_model->parse_member_card_by_data($result,$card_id); //添加优惠券使用范围
            $result=isset($results['data'])?$results['data']:array();
        }

        $page_size = $return['page_size'];
        $current_page = $return['current_page'];
        $tbug = $this->input->get('tbug');
        if($tbug=='1'){
            echo 'arraystartime:'.date('Y-m-d H:i:s');
            echo '<br/>';
        }
        $this->load->model('membervip/admin/config/attribute_model','ui_model');
        if($format=='array'){
            $tmp= array();
            $field_config = $this->ui_model->get_field_config('grid',$params['ui_type']);
            foreach ($result as $k=> $v){
                $vo = array();
                //判断combobox类型需要对值进行转换
                foreach($field_config as $sk=>$sv){
                    if(!isset($v[$sk])) $vo[$sk] = ''; else $vo[$sk] = $v[$sk];
                    if($field_config[$sk]['type']=='combobox') {
                        if( isset($field_config[$sk]['select'][$v[$sk]])){
                            $vo[$sk]= $field_config[$sk]['select'][$v[$sk]];
                        }
                        else $vo[$sk]= '--';
                    }

                    if( $field_config[$sk]['grid_function'] ) {
                        $funp= explode('|', $field_config[$sk]['grid_function']);
                        $fun= $funp[0];
                        $funp[0] = isset($v[$sk])?$v[$sk]:'';
                        $funp[1] = $v;
                        switch ($sk){
                            case 'member_mode':
                                $funp[2] = $other;
                                $funp[3] = $v['cellphone'];
                                break;
                            case 'is_login':
                                $funp[2] = $other;
                                $funp[3] = $v['cellphone'];
                                break;
                            case 'operation':
                                $funp[1] = $v;
                                $funp[2] = $params['opt'];
                                break;
                        }
                        $vo[$sk] = call_user_func_array(array($this,$fun),$funp);
                    } else if( $field_config[$sk]['function'] ) {
                        $funp= explode('|', $field_config[$sk]['function']);
                        $fun= $funp[0];
                        $funp[0]= $v[$sk];
                        $funp[1] = $v['inter_id'];
                        $vo[$sk]= call_user_func_array (array($this,$fun),$funp);
                    }
                }//---
                if(!empty($vo)){
                    $el= array_values($vo);
                    $el['DT_RowId']= $v[$this->_pk];
                    $tmp[]= $el;
                }
            }
            $result= $tmp;
        }
        if($this->input->get('debug')=='1') {
            echo $total;
            echo '<pre>';
            echo $this->_shard_db()->last_query();
        }
        if($tbug=='1'){
            echo 'arrayendime:'.date('Y-m-d H:i:s');
            echo '<br/>';exit;
        }

        if(is_ajax_request()){
            $return_data = array(
                'draw'=> isset($params['draw'])? $params['draw']: 1,
                'data'=> $result,
                'recordsTotal'=>$total,
                'recordsFiltered'=>$total,
            );
        }else{
            $return_data = array(
                'ui'=>$this->ui_model,
                'total'=>$total,
                'data'=>$result,
                'page_size'=>$page_size,
                'page_num'=>$current_page,
            );
        }
        if(isset($params['iscard']) && $params['iscard']=='1'){
            $return_data['use_num']=isset($results['use_num'])?$results['use_num']:0;
            $return_data['useoff_num']=isset($results['useoff_num'])?$results['useoff_num']:0;
            $return_data['expire_num']=isset($results['expire_num'])?$results['expire_num']:0;
            $return_data['giving_num']=isset($results['giving_num'])?$results['giving_num']:0;
            $return_data['is_get']=isset($results['is_get'])?$results['is_get']:2;
            $return_data['title']=isset($results['title'])?$results['title']:'';
        }
        return $return_data;
    }

    protected function _get_operation(){
        $data = func_get_args();
        $arr = $data[1];
        $opt = $data[2];
        $button = '';
        switch ($opt){
            case '1':
                $member_info_id = $arr['member_info_id'];
                $membership_number = $arr['membership_number'];

                $name = $arr['name'];
                $url = EA_const_url::inst()->get_url('membervip/membermanage/add',array('member_info_id'=>$member_info_id));
                $button .= '<a class="color_F99E12" href="'.$url.'">查看详细</a>';
                $button .= '<a type="button" data-mid="'.$member_info_id.'" data-mnum="'.$membership_number.'" data-name="'.$name.'" class="color_F99E12 s-balance">储值调整</a>';
                $button .= '<a data-mid="'.$member_info_id.'" data-mnum="'.$membership_number.'" data-name="'.$name.'" class="color_F99E12 s-integral">积分调整</a>';
                break;
            case '2':
                $package_id = $arr['package_id'];
                $url = EA_const_url::inst()->get_url('membervip/memberpackage/add',array('package_id'=>$package_id));
                $button .= '<a class="btn btn-sm btn-default" href="'.$url.'">编辑</a>';
                $exurl = EA_const_url::inst()->get_url('*/memberexport/package_excel');
                $button .= '<a class="btn btn-sm btn-default memberexport" data-action="'.$exurl.'" href="javascript:void(0);">导出</a>';
                break;
            case '3':
                $card_id = $arr['card_id'];
                $url = EA_const_url::inst()->get_url('*/membercard/add',array('card_id'=>$card_id));
                $button .= '<a class="color_F99E12" href="'.$url.'">编辑</a>';
                $url2 = EA_const_url::inst()->get_url('*/membercard/card_user_info/'.$card_id);
                $button .= '<a class="color_F99E12 adjustment" href="'.$url2.'">领取详情</a>';
                break;
            case '9':
                $card_rule_id = $arr['card_rule_id'];
                $url = EA_const_url::inst()->get_url('*/*/add',array('card_rule_id'=>$card_rule_id));
                $button .= '<a href="'.$url.'">编辑</a>';
                break;
            case '13':
                $coupon_code = $arr['coupon_code'];
                $expire_time = strtotime(date('Y-m-d 23:59:59',$arr['expire_time']));//取当天结束时间
                if($arr['is_use']=='f' && $arr['is_useoff']=='f' && $arr['is_active']=='t' && $expire_time >= time()){
                    $url = EA_const_url::inst()->get_url('*/membercardevent/chargeoff');
                    $button .= '<div class="btn font_14 color_b69b69 border_b69b69_1 radius_3 text_nowrap write_off_btn chargeoff" data-code="'.$coupon_code.'" data-mid="'.$arr['member_info_id'].'" data-url="'.$url.'">核销</div>'."\t\t";
                }

                if($arr['is_active']=='t' && $arr['is_use']=='f' && $arr['is_useoff']=='f' && $expire_time >= time()){
                    $url2 = EA_const_url::inst()->get_url('*/membercardevent/chargeinvalid');
                    $button .= '<div class="btn font_14 color_b69b69 border_b69b69_1 radius_3 text_nowrap write_no_btn chargeoff" data-code="'.$coupon_code.'" data-mid="'.$arr['member_info_id'].'" data-url="'.$url2.'">设成无效</div>';
                }

                break;
            case '14':
                $url = EA_const_url::inst()->get_url('*/membercardevent/invalidauth');
                $button .= '<div data-url="'.$url.'" data-aid="'.$arr['id'].'" class="btn font_14 color_b69b69 border_b69b69_1 radius_3 text_nowrap invalidauth">取消授权</div>';
                break;
        }
        return $button;
    }

    protected function _get_member_mode(){
        $data = func_get_args();
        $mode_value = $data[0];
        $member_mode = $data[2];
        $cellphone = $data[3];
        $name = ' -- ';
        if($member_mode=='login'){
            $name = '粉丝会员';
            if($mode_value=='2') $name = '正式会员';
        }elseif($member_mode=='perfect'){
            $name = '粉丝会员';
            if(!empty($cellphone)) $name = '正式会员';
        }
        return $name;
    }

    protected function _parse_card_type(){
        $data = func_get_args();
        $name='';
        switch ($data[0]){
            case '1':$name='抵用';break;
            case '2':$name='折扣';break;
            case '3':$name='兑换';break;
            case '4':$name='储值';break;
        }
        return $name;
    }

    protected function _get_is_active(){
        $data = func_get_args();
        $name = ' -- ';
        if($data[0]=='t'){
            $name = '正常';
        }elseif ($data[0]=='f'){
            $name = '已冻结';
        }
        return $name;
    }

    protected function _parse_is_active(){
        $data = func_get_args();
        $name = ' -- ';
        if($data[0]=='t'){
            $name = '<span style="color:#18BF0E;"><strong>启用</strong></span>';
        }elseif ($data[0]=='f'){
            $name = '<span style="color:red;" ><strong>禁用</strong></span>';
        }
        return $name;
    }

    protected function _get_is_login(){
        $data = func_get_args();
        $is_login = $data[0];
        $member_mode = $data[2];
        $cellphone = $data[3];
        $name = ' -- ';
        if($is_login=='t'){
            $name = '<span style="color: #18BF0E;"><strong>默认登录</strong></span>';
            if($member_mode=='login' && !empty($cellphone)) $name = '<span style="color: green;"><strong>已登录</strong></span>';
        }elseif ($is_login=='f'){
            $name = '<span><strong>未登录</strong></span>';
        }
        return $name;
    }

    protected function _parse_member_type(){
        $data = func_get_args();
        $name = ' -- ';
        if($data[0]=='97'){
            $name = '<span><strong>员工</strong></span>';
        }elseif ($data[0]=='98'){
            $name = '<span><strong>业主</strong></span>';
        }
        return $name;
    }

    protected function _parse_sex(){
        $data = func_get_args();
        $name = ' -- ';
        if($data[0]=='1'){
            $name = '男';
        }elseif ($data[0]=='2'){
            $name = '女';
        }
        return $name;
    }

    protected function _parse_audit(){
        $data = func_get_args();
        $name = ' -- ';
        switch ($data[0]){
            case '0':
                $name = '<span style="color: red;"><strong>不通过</strong></span>';
                break;
            case '1':
                $name = '<span style="color: green;"><strong>审核通过</strong></span>';
                break;
            case '2':
                $name = '<span><strong>未审核</strong></span>';
                break;
        }
        return $name;
    }

    protected function _parsedatetime(){
        $data = func_get_args();
        if(empty($data[0])) return '------';
        $date = date('Y-m-d,H:i:s',$data[0]);
        if(strpos($date,',')!==false){
            $date = implode('<br>',explode(',',$date));
        }
        return $date;
    }

    protected function _parsedate(){
        $data = func_get_args();
        if(empty($data[0])) return '------';
        $date = date('Y-m-d',$data[0]);
        return $date;
    }

    protected function _parse_createtime(){
        $data = func_get_args();
        $_time = $data[0];
        if(empty($data[0]) && isset($data[1]['last_update_time']) && !empty($data[1]['last_update_time'])){
            $_time = strtotime($data[1]['last_update_time']);
        }
        $date = date('Y-m-d,H:i:s',$_time);
        if(strpos($date,',')!==false){
            $date = implode('<br>',explode(',',$date));
        }
        return $date;
    }

    protected function _parse_module(){
        $data = func_get_args();
        $name='------';
        $model = array(
            'vip'=>'<span style="color:#26EC0E" >会员模块</span>',
            'hotel'=>'<span style="color:#26EC0E" >订房模块</span>',
            'shop'=>'<span style="color:#26EC0E" >商城模块</span>',
            'soma'=>'<span style="color:#26EC0E" >套票模块</span>',
        );
        if(isset($model[$data[0]])) $name=$model[$data[0]];
        return $name;
    }

    protected function _parse_use_module(){
        $data = func_get_args();
        $name='------';
        $model = array(
            'vip'=>'<span style="color:#E88927" >会员模块</span>',
            'hotel'=>'<span style="color:#E88927" >订房模块</span>',
            'shop'=>'<span style="color:#E88927" >商城模块</span>',
            'soma'=>'<span style="color:#E88927" >套票模块</span>',
        );
        if(isset($model[$data[0]])) $name=$model[$data[0]];
        return $name;
    }

    protected function _parse_useoff_module(){
        $data = func_get_args();
        $name='------';
        $model = array(
            'vip'=>'<span style="color:#EA0041">会员模块</span>',
            'hotel'=>'<span style="color:#EA0041" >订房模块</span>',
            'shop'=>'<span style="color:#EA0041" >商城模块</span>',
            'soma'=>'<span style="color:#EA0041" >套票模块</span>',
        );
        if(isset($model[$data[0]])) $name=$model[$data[0]];
        return $name;
    }

    protected function _parse_use_in(){
        $data = func_get_args();
        if(empty($data[0])) return '';
        $model = array(
            'vip'=> '<span>会员模块</span>',
            'hotel'=>'<span>订房模块</span>',
            'shop'=>'<span>商城模块</span>',
            'soma'=>'<span>套票模块</span>',
        );
        $arr=array();
        foreach ($data[0] as $item){
            $arr[]=$model[$item];
        }
        return implode('/',$arr);
    }

    protected function _parse_channel(){
        $data = func_get_args();
        $name=' -- ';
        $model = array(
            'gazeini'=>'<span style="color:#ff9900" >关注送券（默认领取）</span>',
            'gaze'=>'<span style="color:#ff9900" >关注送券（自主领取）</span>',
            'perfect'=>'<span style="color:#ff9900" >完善资料送券</span>',
            'reg'=>'<span style="color:#ff9900" >注册送券</span>',
        );
        if(isset($model[$data[0]])) $name=$model[$data[0]];
        return $name;
    }

    protected function _parse_is_expire(){
        $data = func_get_args();
        $_time=isset($data[1]['expire_time'])?$data[1]['expire_time']:0;
        $exp=strtotime(date('Y-m-d 23:59:59',$_time));
        $name='否';
        if(time()>$exp) $name='是';
        return $name;
    }

    protected function _parse_card_state(){
        $data = func_get_args();
        $is_use = isset($data[1]['is_use'])?$data[1]['is_use']:'';
        $is_useoff = isset($data[1]['is_useoff'])?$data[1]['is_useoff']:'';
        $is_giving = isset($data[1]['is_giving'])?$data[1]['is_giving']:'';
        $arr=array();
        if($is_use=='t') $arr[]='<span style="color:#EA0041">已使用</span>';
        if($is_useoff=='t') $arr[]='<span style="color:#EA0041">已核销</span>';
        if(empty($arr)){
            if($is_giving=='t') {
                $arr[]='<span style="color:#EA0041">转赠中</span>';
            }
        }
        if(empty($arr)) $arr[]='<span style="color:#26EC0E">未使用</span>';
        $name=implode('/',$arr);
        return $name;
    }

    protected function _parselog_type(){
        $keymap = array(
            'invite_viewconf'=>'邀请好友显示配置',
            'invite_settings'=>'邀请好友活动配置',
            'invite_level_equity'=>'邀请好友权益配置',
            'coupon'=>'优惠券配置',
            'package'=>'大礼包配置',
            'member_unbind'=>'会员解除绑定'
        );
        $data = func_get_args();
        $keyname = !empty($keymap[$data[0]])?$keymap[$data[0]]:$data[0];
        return $keyname;
    }


    /**
     * 后台管理的表格中要显示哪些字段
     */
    public function grid_fields($type=1)
    {
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
        $show = array();
        switch ($type){
            case 1:
                $show = array('member_info_id','nickname','member_mode','name','membership_number','lvl_name','credit', 'balance','mcount','is_active','is_login','createtime','operation');
                break;
            case 2:
                $show = array('package_id','name','remark','credit','balance','lvl_name','is_active','createtime','operation');
                break;
        }
        return $show;
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
}