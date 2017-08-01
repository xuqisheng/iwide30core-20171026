<?php

/**
 * Created by knight.
 * User: ibuki
 * Date: 16/7/30
 * Time: 下午9:25
 */
class Kiminvited_model extends MY_Model_Member {

    const KIMINVITED_DISPLAY_CONFIG = 'kiminvited_display_config';


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
     * 获取显示配置
     * @param $params 条件
     * @param $offset 开始获取行数
     * @param $limit 需要获取返回行数
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
        if(isset($params['is_del'])) $where[$table.'.is_del'] = $params['is_del'];
        if(isset($params['activited_id'])) $where[$table.'.activited_id'] = $params['activited_id'];
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
        foreach ($list as $key=>$item){
            $start_time = strtotime(date('Y-m-d',$item['start_time']).' 00:00:00');
            $end_time = strtotime(date('Y-m-d',$item['end_time']).' 23:59:59');
            $list[$key]['code'] = 0;
            $list[$key]['state'] = ' -- ';
            if($item['status']=='2'){
                $list[$key]['code'] = 0;
                $list[$key]['state'] = '<strong><font color="#f39c12">未激活</font></strong>';
            }elseif($item['status']=='1'){
                if($item['isopen']=='2'){
                    $list[$key]['code'] = 0;
                    $list[$key]['state'] = '<strong><font color="#dd4b39">停用</font></strong>';
                }elseif($item['isopen']=='1'){
                    if($start_time<=time() && $end_time>=time()){
                        $list[$key]['code'] = 1;
                        $list[$key]['state'] = '<strong><font color="#00a65a">正在进行...</font></strong>';
                    }elseif ($start_time>time()){
                        $list[$key]['code'] = 0;
                        $list[$key]['state'] = '<strong><font color="#f39c12">未开始</font></strong>';
                    }elseif ($end_time<time()){
                        $list[$key]['code'] = 0;
                        $list[$key]['state'] = '<strong><font color="#000">已结束</font></strong>';
                    }
                }
            }
        }
        if($this->input->get('debug') == 1){
            $this->_write_log($this->_shard_db()->last_query(),'row_array-SQL','Kiminvited/sql');
            echo $this->_shard_db()->last_query();echo '<br />';
        }
        if(!is_null($list)) return $list;
        return array();
    }

    /**
     * 获取数据(列表)
     * @param $params 条件
     * @param $offset 开始获取行数
     * @param $limit 需要获取返回行数
     * @return array
     */
    public function get_activited_list($where=array(),$field='*'){
        $list = $this->_shard_db()->select($field)->where($where)->order_by('createtime DESC')->get('kiminvited_activited_conf')->result_array();
        if($this->input->get('debug') == 1){
            $this->_write_log($this->_shard_db()->last_query(),'row_array-SQL','Kiminvited/sql');
            echo $this->_shard_db()->last_query();echo '<br />';
        }
        if(!is_null($list)) return $list;
        return array();
    }

    /**
     * 获取推荐排行榜
     * @param $params 条件
     * @param $offset 开始获取行数
     * @param $limit 需要获取返回行数
     * @param string $table 表名
     * @param array $condition 额外条件 （数组形式）
     * @return array|bool
     */
    public function get_kiminvited_ranklist($params,$offset=0,$limit=30){
        if(!isset($params['inter_id']) || !isset($params['activited_id'])) return array();
        $tab='`iwide_kiminvited_record`';
        $_tab='`iwide_member_info`';
        $_tab1='`iwide_kiminvited_activited_conf`';
        $map = '';
        if(isset($params['where'])) $map = $params['where'];
        $sql = "SELECT $tab.id,$tab.inter_id,$tab.activited_id,COUNT($tab.touser_id) AS total_recom,$tab.fromuser_id,$tab.from_openid,$_tab.name,$_tab1.name as actname,$_tab1.isopen FROM $tab LEFT JOIN $_tab ON $_tab.member_info_id = $tab.fromuser_id LEFT JOIN $_tab1 ON $tab.activited_id = $_tab1.id WHERE $tab.inter_id=? AND $tab.activited_id= ? AND $_tab1.is_del='n' $map GROUP BY $tab.fromuser_id ORDER BY total_recom DESC LIMIT $offset,$limit";
        $info = $this->_shard_db()->query($sql, array($params['inter_id'], $params['activited_id']))->result_array();
        $this->_write_log($this->_shard_db()->last_query(),'get_recommend_info-SQL','Kiminvited/sql');
        $rank_list = array();
        if($info){
            $where['inter_id'] = $params['inter_id'];
            $where['activited_id'] = isset($params['activited_id'])?$params['activited_id']:0;
            $reward_info = $this->get_kiminvited_info($where,'kiminvited_reward');
            $lvl=$offset;
            foreach ($info as $key=>$item){
                $rlv = $lvl+1;
                $fans = $this->get_fans_info($item['from_openid'],'nickname,headimgurl');
                $this->_write_log($fans,'get_fans_info');
                $rank_list[$item['fromuser_id']] = $item;
                $rank_list[$item['fromuser_id']]['ranking'] = $rlv;
                $rank_list[$item['fromuser_id']]['nickname'] = isset($fans['nickname'])?$fans['nickname']:'';
                $rank_list[$item['fromuser_id']]['headimgurl'] = isset($fans['headimgurl'])?$fans['headimgurl']:'';
                $rank_list[$item['fromuser_id']]['issend'] = 0;
                if($reward_info['mode']=='2' && $rlv <= floatval($reward_info['full_rank']) && $item['isopen']=='2'){
                    $_where['inter_id'] = $item['inter_id'];
                    $_where['member_info_id'] = $item['fromuser_id'];
                    $_where['activited_id'] = $item['activited_id'];
                    $_where['reward_type'] = '2';
                    $count = $this->get_count($_where,'kiminvited_exchange_reward');
                    if(!$count || empty($count)) {
                        $rank_list[$item['fromuser_id']]['issend'] = 1;
                        $rank_list[$item['fromuser_id']]['reward_value'] = $reward_info['exchange_card'];
                    }
                }
                $lvl++;
            }
        }
        return $rank_list;
    }


    /**
     * 获取活动排行榜数据(直接访问)
     * @param array $params
     * @param string $format
     * @return array
     */
    public function filter_rank($params=array(),$format='array'){
        $type = isset($params['type'])?$params['type']:4;
        $table='`iwide_kiminvited_record`';
        $btable='`iwide_member_info`';
        $ctable='`iwide_kiminvited_activited_conf`';

        if(isset($params['search']) && is_array($params['search']) && !empty($params['search'])){
            $params['f_like'] = $params['search'];
        }

        $pk='id';
        if( isset($params['sort_field']) && isset($params['sort_direct']) ){
            $sort= $params['sort_field']. ' '. $params['sort_direct'];
        } else $sort= "{$pk} DESC";  //默认排序

        $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
        $page_size= isset($params['page_size'])? $params['page_size']: $num;
        $current_page= isset($params['page_num'])? $params['page_num']: 1;

        $select= array($table.'.id',$table.'.inter_id',$table.'.activited_id','COUNT('.$table.'.touser_id) AS total_recom',$table.'.fromuser_id',$table.'.from_openid',$btable.'.name',$ctable.'.name as actname',$ctable.'.isopen');

        $select= implode(',', $select);

        $map = '';
        if(isset($params['where'])) $map = $params['where'];

        $offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;
        $or_likes = '';
        if( isset($params['f_like']) && count($params['f_like'])>0 ){
            //模糊匹配参数
            $like_field=$this->like_fields($type);
            $or_like='';
            foreach ($like_field as $sv) {
                if(isset($params['f_like']['value']) && !empty($params['f_like']['value']))
                    $or_like .= $sv.' LIKE \'%'.$params['f_like']['value'].'%\' OR ';
            }
            if(!empty($or_like)){
                $or_likes = 'AND ('.substr($or_like, 0,-3).')';
            }
        }

        $sql = "SELECT $select FROM $table LEFT JOIN $btable ON $btable.member_info_id = $table.fromuser_id LEFT JOIN $ctable ON $table.activited_id = $ctable.id WHERE $table.inter_id=? AND $table.activited_id= ? AND $ctable.is_del='n' $map $or_likes GROUP BY $table.fromuser_id ORDER BY total_recom DESC LIMIT $offset,$page_size";

        $factor = array($params['inter_id'], $params['activited_id']);
        $total= $this->_shard_db()->query($sql, $factor)->num_rows(); //总行数
        $result = $this->_shard_db()->query($sql, $factor)->result_array(); //结果
        if($this->input->get('debug')=='1') echo $this->_shard_db()->last_query();

        if($format=='array'){
            $tmp= array();
            $field_config= $this->get_field_config('grid',$type);
            $rank_list = array();
            if($result){
                $where['inter_id'] = $params['inter_id'];
                $where['activited_id'] = isset($params['activited_id'])?$params['activited_id']:0;
                $reward_info = $this->get_kiminvited_info($where,'kiminvited_reward');
                $lvl=$offset;
                $old_num = 0;
                foreach ($result as $key=>$item){
                    if($key=='0') {
                        $old_num = $item['total_recom'];
                        $lvl++;
                    }
                    if(floatval($item['total_recom'])<floatval($old_num)) $lvl++;
                    $fans = $this->get_fans_info($item['from_openid'],'nickname,headimgurl');
                    $this->_write_log($fans,'get_fans_info');
                    $result[$key]['ranking'] = $lvl;
                    $item['ranking'] = $lvl;
                    $result[$key]['nickname'] = isset($fans['nickname'])?$fans['nickname']:'';;
                    $item['nickname'] = isset($fans['nickname'])?$fans['nickname']:'';;
                    $result[$key]['headimgurl'] = isset($fans['headimgurl'])?$fans['headimgurl']:'';
                    $item['headimgurl'] = isset($fans['headimgurl'])?$fans['headimgurl']:'';
                    $result[$key]['issend'] = 0;
                    $item['issend'] = 0;

                    if($reward_info['mode']=='2' && $lvl <= floatval($reward_info['full_rank']) && $item['isopen']=='2'){
                        $_where['inter_id'] = $item['inter_id'];
                        $_where['member_info_id'] = $item['fromuser_id'];
                        $_where['activited_id'] = $item['activited_id'];
                        $_where['reward_type'] = '2';
                        $count = $this->get_count($_where,'kiminvited_exchange_reward');
                        if(!$count || empty($count)) {
                            $rank_list[$item['fromuser_id']]['issend'] = 1;
                            $result[$key]['issend'] = 1;
                            $item['issend'] = 1;
                            $result[$key]['reward_value'] = $reward_info['exchange_card'];
                            $item['reward_value'] = $reward_info['exchange_card'];
                        }
                    }

                    $old_num = $item['total_recom'];
                    $vo = array();
                    foreach($field_config as $sk=>$sv){
                        if($sk=='operating'){
                            if(isset($item['issend']) && $item['issend']=='1')
                                $vo['operating']='<span style="cursor: pointer;color: #337ab7;" data-actid="'.$item['activited_id'].'" data-memid="'.$item['fromuser_id'].'" data-openid="'.$item['from_openid'].'" data-value="'.$item['reward_value'].'" class="send-rw">发放奖励</span>';
                            else
                                $vo['operating'] = '';
                        }
                        else $vo[$sk] = $item[$sk];
                        if($field_config[$sk]['type']=='combobox') {
                            if( isset($field_config[$sk]['select'][$item[$sk]])){
                                $vo[$sk]= $field_config[$sk]['select'][$item[$sk]];
                            }
                            else $vo[$sk]= '--';
                        }
                        if( $field_config[$sk]['grid_function'] ) {
                            $funp= explode('|', $field_config[$sk]['grid_function']);
                            $fun= $funp[0];
                            $funp[0]= $item[$sk];
                            $funp[1] = $item['inter_id'];
                            $vo[$sk]= call_user_func_array (array($this, $fun), $funp);
                        } else if( $field_config[$sk]['function'] ) {
                            $funp= explode('|', $field_config[$sk]['function']);
                            $fun= $funp[0];
                            $funp[0]= $item[$sk];
                            $funp[1] = $item['inter_id'];
                            $vo[$sk]= call_user_func_array (array($this, $fun),$funp);
                        }
                    }
                    $el= array_values($vo);
                    $el['DT_RowId']= $item['id'];
                    $tmp[]= $el;
                }
                $result= $tmp;
            }
        }

        if(is_ajax_request()){
            return array(
                'draw'=> isset($params['draw'])? $params['draw']: 1,
                'data'=> $result,
                'recordsTotal'=>$total,
                'recordsFiltered'=>$total,
            );
        }else{
            return array(
                'total'=>$total,
                'data'=>$result,
                'page_size'=>$page_size,
                'page_num'=>$current_page,
            );
        }
    }

    public function get_kiminvited_ranklist_total($params){
        if(!isset($params['inter_id']) || !isset($params['activited_id'])) return array();
        $tab='`iwide_kiminvited_record`';
        $_tab='`iwide_member_info`';
        $_tab1='`iwide_kiminvited_activited_conf`';
        $map = '';
        if(isset($params['where'])) $map = $params['where'];
        $sql = "SELECT * FROM $tab LEFT JOIN $_tab ON $_tab.member_info_id = $tab.fromuser_id LEFT JOIN $_tab1 ON $tab.activited_id = $_tab1.id WHERE $tab.inter_id=? AND $tab.activited_id= ? AND $_tab1.is_del='n' $map GROUP BY $tab.fromuser_id";
        $count = $this->_shard_db()->query($sql, array($params['inter_id'], $params['activited_id']))->num_rows();
        $this->_write_log($this->_shard_db()->last_query(),'get_recommend_info-SQL','Kiminvited/sql');
        return $count;
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

    public function get_kiminvited_count($where=array(),$table=''){
        if(empty($where) || empty($table)) return false;
        $count = $this->_shard_db()->where($where)->get($table)->num_rows();
        $this->_write_log($this->_shard_db()->last_query(),'count-SQL','Kiminvited/sql');
        return $count;
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
        if(empty($data) && is_string($data)) return false;
        $where['inter_id'] = $params['inter_id'];
        if(isset($params['id'])) $where['id'] = $params['id'];
        if(isset($params['reward_id'])) $where['reward_id'] = $params['reward_id'];
        if(isset($params['activited_id'])) $where['activited_id'] = $params['activited_id'];
        $result = $this->_shard_db(true)->where($where)->set($data)->update($table);
        $this->_write_log($this->_shard_db(true)->last_query(),'update-SQL','Kiminvited/sql');
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
        $result = $this->_shard_db(true)->set($data)->insert($table);
        $this->_write_log($this->_shard_db(true)->last_query(),'insert-SQL','Kiminvited/sql');

        if($this->input->get('debug') == 1){
            $this->_write_log($this->_shard_db(true)->last_query(),'insert-SQL','Kiminvited/sql');
        }
        $last_id = $this->_shard_db(true)->insert_id();
        return $last_id;
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

    public function get_info($where=array(),$name='',$table=''){
        $info = $this->_shard_db()->select($name)
            ->get_where($table, $where)
            ->row_array();
        if(!empty($info)) return $info[$name];
        return false;
    }

    public function get_count($where=array(),$table=''){
        $count = $this->_shard_db()->get_where($table, $where)->num_rows();
        $this->_write_log($this->_shard_db()->last_query(),'get_count-SQL','Kiminvited/sql');
        return $count;
    }

    public function _parsedate(){
        $data = func_get_args();
        $date = date('Y-m-d H:i',$data[0]);
        return $date;
    }

    public function _parsechannel(){
        $data = func_get_args();
        if($data[0]=='1') return '当面邀请';else return '分享邀请';
    }

    public function _parseimg(){
        $data = func_get_args();
        if(isset($data[0])) return '<img width="50" height="50" src="'.$data[0].'" alt="'.$data[0].'">';
        return '';
    }

    public function _parsepeple(){
        $data = func_get_args();
        if(isset($data[0])) return $data[0].'人';
        return '';
    }

    public function _parseranking(){
        $data = func_get_args();
        if(isset($data[0])) return '第'.$data[0].'名';
        return '';
    }

    /**
     * 后台模版表格表头字典
     * @return array
     */
    public function attribute_labels($flag=1) {
        switch ($flag){
            case 2:
                $labels = array(
                    'name'=> '会员名称',
                    'reg_time'=> '注册时间',
                    'total_value'=> '获得活动积分',
                    'channel'=>'来源',
                    'title'=>'获得活动奖励',
                    'count'=>'数量'
                );
                break;
            case 3:
                $labels = array(
                    'createtime'=> '使用时间',
                    'use_credit'=> '使用积分',
                    'title'=> '获得活动奖励',
                );
                break;
            case 4:
                $labels = array(
                    'headimgurl'=> '头像',
                    'name'=> '推荐人',
                    'total_recom'=> '推荐数量',
                    'ranking'=> '排行',
                    'operating'=> '操作',
                );
                break;
            default:
                $labels = array(
                    'rank_lv'=> '名次',
                    'name'=> '会员名称',
                    'nickname'=> '昵称',
                    'membership_number'=> '会员卡号',
                    'total_user'=> '推荐数',
                    'total_value'=> '获得积分',
                    'operating'=>'操作'
                );
        }
        return $labels;
    }

    /**
     * 在EasyUI grid中的 date-option 定义，包括宽度，是否排序等等
     *   type: grid中的表头类型定义
     *   form_type: form中的元素类型定义
     *   form_ui: form中的属性补充定义，如加disabled 在< input “disabled” / > 使元素禁用
     *   form_tips: form中的label信息提示
     *   form_hide: form中自动化输出中剔除
     *   form_default: form中的默认值，请用字符类型，不要用数字
     *   select: form中的类型为 combobox时，定义其下来列表
     */
    public function attribute_ui($flag=1)
    {
        switch ($flag){
            case 2:
                $attribute = array(
                    'name' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '10%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'reg_time' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '7%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                        'grid_function'=>'_parsedate'
                    ),
                    'total_value' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '6%',
                        'form_hide'=> TRUE,
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'channel' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '6%',
                        'form_hide'=> TRUE,
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                        'grid_function'=>'_parsechannel'
                    ),
                    'title' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '10%',
                        'form_hide'=> TRUE,
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'count' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '6%',
                        'form_hide'=> TRUE,
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    )
                );
                break;
            case 3:
                $attribute = array(
                    'createtime' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '10%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                        'grid_function'=>'_parsedate'
                    ),
                    'use_credit' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '10%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'title' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '7%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    )
                );
                break;
            case 4:
                $attribute = array(
                    'headimgurl'=> array(
                        'grid_ui'=> '',
                        'grid_width'=> '8%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                        'grid_function'=>'_parseimg'
                    ),
                    'name'=> array(
                        'grid_ui'=> '',
                        'grid_width'=> '10%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'total_recom'=> array(
                        'grid_ui'=> '',
                        'grid_width'=> '10%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                        'grid_function'=>'_parsepeple'
                    ),
                    'ranking'=> array(
                        'grid_ui'=> '',
                        'grid_width'=> '10%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                        'grid_function'=>'_parseranking'
                    ),
                    'operating'=> array(
                        'grid_ui'=> '',
                        'grid_width'=> '8%',
                        'form_hide'=> TRUE,
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    )
                );
                break;
            default:
                $attribute = array(
                    'rank_lv' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '6%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'name' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '10%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'nickname' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '7%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'membership_number' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '10%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'total_user' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '10%',
                        'form_hide'=> TRUE,
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'total_value' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '6%',
                        'form_hide'=> TRUE,
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'operating' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '8%',
                        'form_hide'=> TRUE,
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    )
                );
        }
        return $attribute;
    }

    /**
     * 后台管理的模糊查询的字段
     */
    public function like_fields($flag=1)
    {
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
        switch ($flag){
            case 2:
                $like_fields = array('c.name');
                break;
            case 3:
                $like_fields = array('a.use_credit','b.title');
                break;
            case 4:
                $like_fields = array('b.name');
                break;
            default:
                $like_fields = array('c.membership_number','c.name', 'c.nickname');
        }
        return $like_fields;
    }

    /**
     * @param String $type   grid|form
     * 统一生成字段配置数组，赋予模板
     */
    public function get_field_config($type='grid',$flag=1)
    {
        $data= array();
        switch ($flag){
            case 2:
                $show = array('name','reg_time', 'total_value','channel','title','count');
                break;
            case 3:
                $show = array('createtime','use_credit','title');
                break;
            case 4:
                $show = array('headimgurl','name','total_recom','ranking','operating');
                break;
            default:
                $show = array('rank_lv','name','nickname','membership_number', 'total_user', 'total_value','operating');
        }

        $fields= $this->attribute_labels($flag);
        $fields_ui= $this->attribute_ui($flag);
        foreach ($show as $v){
            $data[$v]['label']= $fields[$v];
            if($type=='grid'){
                //grid所需配置信息
                if( array_key_exists($v, $fields_ui) ){
                    $data[$v]['grid_ui'] = isset($fields_ui[$v]['grid_ui'])?$fields_ui[$v]['grid_ui']: '';
                    $data[$v]['grid_width'] = isset($fields_ui[$v]['grid_width'])?$fields_ui[$v]['grid_width']: "";
                    $data[$v]['grid_function'] = isset($fields_ui[$v]['grid_function'])? $fields_ui[$v]['grid_function']: FALSE;
                    $data[$v]['function'] = isset($fields_ui[$v]['function'])? $fields_ui[$v]['function']: FALSE;
                    $data[$v]['type'] = isset($fields_ui[$v]['type'])?$fields_ui[$v]['type']: 'text';
                    if( $data[$v]['type']=='combobox' ) $data[$v]['select'] = $fields_ui[$v]['select'];
                }

            } else if($type=='form') {
                //form所需配置信息
                $data[$v]['js_config'] = isset($fields_ui[$v]['js_config'])? $fields_ui[$v]['js_config']: '';
                $data[$v]['input_unit'] = isset($fields_ui[$v]['input_unit'])? "<div class='input-group-addon'>{$fields_ui[$v]['input_unit']}</div>" : '';
                $data[$v]['form_ui'] = isset($fields_ui[$v]['form_ui'])? $fields_ui[$v]['form_ui']: '';
                $data[$v]['form_tips'] = !empty($fields_ui[$v]['form_tips'])? $fields_ui[$v]['form_tips']: NULL;
                $data[$v]['form_default'] = isset($fields_ui[$v]['form_default'])? $fields_ui[$v]['form_default']: NULL;
                $data[$v]['form_hide'] = isset($fields_ui[$v]['form_hide'])? $fields_ui[$v]['form_hide']: FALSE;
                $data[$v]['function'] = isset($fields_ui[$v]['function'])? $fields_ui[$v]['function']: FALSE;
                $data[$v]['type'] = isset($fields_ui[$v]['type'])? $fields_ui[$v]['type']: 'text';
                if( $data[$v]['type']=='combobox' ) $data[$v]['select'] = $fields_ui[$v]['select'];
                if( isset($fields_ui[$v]['form_type'])) $data[$v]['type'] = $fields_ui[$v]['form_type'];
            }
        }

        return $data;
    }

    //获取活动统计数据
    public function get_statistics_info($params=array()){
        $table= 'iwide_kiminvited_record';
        $btable='iwide_kiminvited_reward_record';
        $ctable='iwide_member_info';
        if(floatval($params['reward']['old_reward_type'])>0){
            $cipher = 'SUM';
            if(isset($params['reward']['old_reward_type']) && ($params['reward']['old_reward_type']=='3' || $params['reward']['old_reward_type']=='4')) $cipher = 'COUNT';

            $select= array('a.id','a.inter_id','a.activited_id','a.fromuser_id','a.reg_time','a.channel',$cipher.'(b.reward_value) as total_value','COUNT(a.touser_id) as total_user','c.name','c.nickname','c.membership_number');
            $select= implode(',', $select);
            $sql = "SELECT $select FROM $table a LEFT JOIN $btable b ON b.record_id=a.id LEFT JOIN $ctable c ON c.member_info_id=a.fromuser_id WHERE a.inter_id=? AND a.activited_id=? AND b.member_type=2 GROUP BY a.fromuser_id ORDER BY total_user DESC";
        }else{
            $select= array('a.id','a.inter_id','a.activited_id','a.fromuser_id','a.reg_time','a.channel','COUNT(a.touser_id) as total_user','c.name','c.nickname','c.membership_number');
            $select= implode(',', $select);
            $sql = "SELECT $select FROM $table a LEFT JOIN $ctable c ON c.member_info_id=a.fromuser_id WHERE a.inter_id=? AND a.activited_id=? GROUP BY a.fromuser_id ORDER BY total_user DESC";
        }

        $result = $this->get_result($sql,$params);
        $rank_lv = 0;
        $old_num = 0;
        foreach ($result as $k=> $v){
            if(floatval($params['reward']['old_reward_type'])==0){
                $result[$k]['total_value'] = 0;
            }
            if($k=='0') {
                $old_num = $v['total_user'];
                $rank_lv++;
            }
            if(floatval($v['total_user'])<floatval($old_num)) $rank_lv++;
            $old_num = $v['total_user'];
            $result[$k]['rank_lv'] = $rank_lv;
        }
        return $result;
    }

    //获取某个字段的值
    public function get_point($where=array(),$key='',$table=''){
        if(empty($where) || empty($table)) return false;
        $number = $this->_shard_db()->select($key)->where($where)->get($table)->row_array();
        if(isset($number[$key])) return $number[$key];
        return 0;
    }

    //获取邀请奖励数据
    public function get_reward($where=array()){
        if(empty($where)) return false;
        $where = array_values($where);
        $sql = "SELECT inter_id,reward_type,reward_value FROM iwide_kiminvited_reward_record WHERE inter_id = ? AND member_type=? AND reward_type IN ? AND member_info_id=? AND record_id=?";
        $result = $this->_shard_db()->query($sql,$where)->row_array();
        $this->_write_log($this->_shard_db()->last_query(),'get_reward-SQL','Kiminvited/sql');
        if(!empty($result)){
            if($result['reward_type']=='3'){
                $map = array('card_id'=>$result['reward_value'],'inter_id'=>$result['inter_id']);
                $card_name = $this->get_info($map,'title','iwide_card');
                $result['title'] = $card_name;
                $result['count'] = 1;
            }elseif ($result['reward_type']=='4'){
                $map = array('package_id'=>$result['reward_value'],'inter_id'=>$result['inter_id']);
                $package_name = $this->get_info($map,'name','iwide_package');
                $result['title'] = $package_name;
                $result['count'] = 1;
            }else{
                $result['title'] = $result['reward_value']=='2'?'储值':'积分';
                $result['count'] = $result['reward_value'];
            }
            return $result;
        }
        return array();
    }

    /**
     * @param array $params 条件参数
     * @param array $select 获取字段
     * @param string $format
     * @return array
     */
    public function filter_statistics($params=array(),$format='array'){
        $table= 'iwide_kiminvited_record';
        $btable='iwide_kiminvited_reward_record';
        $ctable='iwide_member_info';

        if(isset($params['search']) && is_array($params['search']) && !empty($params['search'])){
            $params['f_like'] = $params['search'];
        }

        $pk='id';
        if( isset($params['sort_field']) && isset($params['sort_direct']) ){
            $sort= $params['sort_field']. ' '. $params['sort_direct'];
        } else $sort= "{$pk} DESC";  //默认排序

        $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
        $page_size= isset($params['page_size'])? $params['page_size']: $num;
        $current_page= isset($params['page_num'])? $params['page_num']: 1;


        if(floatval($params['reward']['old_reward_type'])>0){
            $cipher = 'SUM';
            if(isset($params['reward']['old_reward_type']) && ($params['reward']['old_reward_type']=='3' || $params['reward']['old_reward_type']=='4')) $cipher = 'COUNT';

            $select= array('a.id','a.inter_id','a.activited_id','a.fromuser_id','a.reg_time','a.channel',$cipher.'(b.reward_value) as total_value','COUNT(a.touser_id) as total_user','c.name','c.nickname','c.membership_number');

            $select= implode(',', $select);
        }else{
            $select= array('a.id','a.inter_id','a.activited_id','a.fromuser_id','a.reg_time','a.channel','COUNT(a.touser_id) as total_user','c.name','c.nickname','c.membership_number');
            $select= implode(',', $select);
        }


        //echo $select;die;
        $offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;
        $or_likes = '';
        if( isset($params['f_like']) && count($params['f_like'])>0 ){
            //模糊匹配参数
            $like_field=$this->like_fields();
            $or_like='';
            foreach ($like_field as $sv) {
                if(isset($params['f_like']['value']) && !empty($params['f_like']['value']))
                    $or_like .= $sv.' LIKE \'%'.$params['f_like']['value'].'%\' OR ';
            }
            if(!empty($or_like)){
                $or_likes = 'AND ('.substr($or_like, 0,-3).')';
            }
        }

        if(floatval($params['reward']['old_reward_type'])>0){
            $sql = "SELECT $select FROM $table a LEFT JOIN $btable b ON b.record_id=a.id LEFT JOIN $ctable c ON c.member_info_id=a.fromuser_id WHERE a.inter_id=? AND a.activited_id=? AND b.member_type=2 $or_likes GROUP BY a.fromuser_id,a.touser_id ORDER BY total_user DESC";
        }else{
            $sql = "SELECT $select FROM $table a LEFT JOIN $ctable c ON c.member_info_id=a.fromuser_id WHERE a.inter_id=? AND a.activited_id=? $or_likes GROUP BY a.fromuser_id ORDER BY total_user DESC";
        }

        $_sql = "SELECT a.* FROM $table a LEFT JOIN $ctable c ON c.member_info_id=a.fromuser_id WHERE a.inter_id=? AND a.activited_id=? $or_likes GROUP BY a.fromuser_id";
        $total = $this->_shard_db()->query($_sql, array($params['inter_id'], $params['activited_id']))->num_rows();
        $result = $this->get_result($sql,$params,$offset,$page_size);
        $results=array();
        if(floatval($params['reward']['old_reward_type'])>0){
            foreach ($result as $key => $vo) {
                if(isset($results[$vo['fromuser_id']]['total_value'])){
                    $vo['total_value']=floatval($vo['total_value'])+floatval($results[$vo['fromuser_id']]['total_value']);
                }
                if(isset($results[$vo['fromuser_id']]['total_user'])){
                    $vo['total_user']=floatval($vo['total_user'])+floatval($results[$vo['fromuser_id']]['total_user']);
                }
                $results[$vo['fromuser_id']]=$vo;
            }
            $result=$results;
        }
        if($this->input->get('debug')=='1') {
            echo '<pre>';
            echo $this->_shard_db()->last_query();
            print_r($result);
        }
        $sql = "SELECT COUNT(touser_id) as total_user,channel FROM $table WHERE inter_id=? AND activited_id=? GROUP BY channel";
        $share_toface = $this->_shard_db()->query($sql, array($params['inter_id'], $params['activited_id']))->result_array();
        if($this->input->get('debug')=='1') echo "<br>".$this->_shard_db()->last_query();
        $total_user = 0;
        $total_toface = 0;
        $total_share = 0;
        if(!empty($share_toface)){
            foreach ($share_toface as $key => $item){
                $total_user += floatval($item['total_user']);
                if($item['channel']=='2')
                    $total_share += floatval($item['total_user']);
                else
                    $total_toface += floatval($item['total_user']);
            }
        }
        if($format=='array'){
            $tmp= array();
            $field_config= $this->get_field_config('grid');
            $rank_lv = floatval($offset);
            $fk=0;
            foreach ($result as $k=> $v){
                if(floatval($params['reward']['old_reward_type'])==0){
                    $result[$k]['total_value'] = 0;
                }

                if($fk=='0') {
                    $old_num = $v['total_user'];
                    $rank_lv++;
                }
                if(floatval($v['total_user'])<floatval($old_num)) $rank_lv++;
                $old_num = $v['total_user'];
                $v['rank_lv'] = $rank_lv;
                //判断combobox类型需要对值进行转换
                $vo = array();
                foreach($field_config as $sk=>$sv){
//                    if($sk=='rank_lv') $v[$sk] = $vo['rank_lv'];
                    if($sk=='operating'){
                        $vo['operating']='<span data-uid="'.$v['fromuser_id'].'" data-aid="'.$v['activited_id'].'" data-type="1" class="detailes">获得明细</span> &nbsp;|&nbsp; ';
                        $vo['operating'].='<span data-uid="'.$v['fromuser_id'].'" data-aid="'.$v['activited_id'].'" data-type="2" class="detailes">使用明细</span>';
                    }
                    else $vo[$sk] = $v[$sk];
                    if($field_config[$sk]['type']=='combobox') {
                        if( isset($field_config[$sk]['select'][$v[$sk]])){
                            $vo[$sk]= $field_config[$sk]['select'][$v[$sk]];
                        }
                        else $vo[$sk]= '--';
                    }
                    if( $field_config[$sk]['grid_function'] ) {
                        $funp= explode('|', $field_config[$sk]['grid_function']);
                        $fun= $funp[0];
                        $funp[0]= $v[$sk];
                        $funp[1] = $v['inter_id'];
                        $vo[$sk]= call_user_func_array (array($this, $fun), $funp);
                    } else if( $field_config[$sk]['function'] ) {
                        $funp= explode('|', $field_config[$sk]['function']);
                        $fun= $funp[0];
                        $funp[0]= $v[$sk];
                        $funp[1] = $v['inter_id'];
                        $vo[$sk]= call_user_func_array (array($this, $fun),$funp);
                    }
                }//---
                $el= array_values($vo);
                $el['DT_RowId']= $v['id'];
                $tmp[]= $el;
                $fk++;
            }
            $result= $tmp;
        }

        if(is_ajax_request()){
            return array(
                'total'=>$total,
                'total_user'=>$total_user,
                'total_toface'=>$total_toface,
                'total_share'=>$total_share,
                'draw'=> isset($params['draw'])? $params['draw']: 1,
                'data'=> $result,
                'recordsTotal'=>$total,
                'recordsFiltered'=>$total,
            );
        }else{
            return array(
                'total'=>$total,
                'total_user'=>$total_user,
                'total_toface'=>$total_toface,
                'total_share'=>$total_share,
                'data'=>$result,
                'page_size'=>$page_size,
                'page_num'=>$current_page,
            );
        }
    }

    public function get_result($sql='',$params=array(),$offset=0,$page_size=0){
        if(empty($sql) || empty($params)) return array();
        $_sql = '';
        if($page_size) $_sql = " LIMIT $offset,$page_size";
        $sql.=$_sql;
        $result = $this->_shard_db()->query($sql, array($params['inter_id'], $params['activited_id']))->result_array();
        return $result;
    }

    public function filter_detailes($params=array(), $format='array'){
        $type=2;
        if(isset($params['type']) && $params['type']=='2'){
            $type = 3;
        }
        $table= 'iwide_kiminvited_record';
        $btable='iwide_kiminvited_reward_record';
        $ctable='iwide_member_info';

        if($type==3){
            $table= 'iwide_kiminvited_exchange_reward';
            $btable='iwide_card';
        }

        if(isset($params['search']) && is_array($params['search']) && !empty($params['search'])){
            $params['f_like'] = $params['search'];
        }

        $pk='id';
        if( isset($params['sort_field']) && isset($params['sort_direct']) ){
            $sort= $params['sort_field']. ' '. $params['sort_direct'];
        } else $sort= "{$pk} DESC";  //默认排序

        $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
        $page_size= isset($params['page_size'])? $params['page_size']: $num;
        $current_page= isset($params['page_num'])? $params['page_num']: 1;

        $select= array('a.id','a.inter_id','a.activited_id','a.touser_id','a.reg_time','a.channel','b.reward_value as total_value','c.name');
        if($type==3){
            $select= array('a.id','a.inter_id','a.activited_id','a.use_credit','a.reward_cardid','a.reward_type','a.is_ok','a.createtime','b.title');
        }

        $select= implode(',', $select);

        $offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;
        $or_likes = '';
        if( isset($params['f_like']) && count($params['f_like'])>0 ){
            //模糊匹配参数
            $like_field=$this->like_fields($type);
            $or_like='';
            foreach ($like_field as $sv) {
                if(isset($params['f_like']['value']) && !empty($params['f_like']['value']))
                    $or_like .= $sv.' LIKE \'%'.$params['f_like']['value'].'%\' OR ';
            }
            if(!empty($or_like)){
                $or_likes = 'AND ('.substr($or_like, 0,-3).')';
            }
        }

        $sql = "SELECT $select FROM $table a LEFT JOIN $btable b ON b.record_id=a.id LEFT JOIN $ctable c ON c.member_info_id=a.touser_id WHERE a.inter_id=? AND a.activited_id=? AND a.fromuser_id=? $or_likes GROUP BY a.touser_id ORDER BY a.reg_time DESC LIMIT $offset,$page_size";
        if($type==3){
            $sql = "SELECT $select FROM $table a LEFT JOIN $btable b ON b.card_id=a.reward_cardid WHERE a.inter_id=? AND a.activited_id=? AND a.member_info_id=? AND a.is_ok='t' $or_likes ORDER BY a.createtime DESC LIMIT $offset,$page_size";
        }

        $factor = array($params['inter_id'], $params['activited_id'],$params['fromuser_id']);
        $total= $this->_shard_db()->query($sql, $factor)->num_rows(); //总行数
        $result = $this->_shard_db()->query($sql, $factor)->result_array(); //结果
        if($this->input->get('debug')=='1') echo $this->_shard_db()->last_query();
        if($format=='array'){
            $tmp= array();
            $field_config= $this->get_field_config('grid',$type);
            foreach ($result as $k=> $v){
                if($type==2){
                    //获取活动积分
                    $where = array('member_info_id'=>$params['fromuser_id'],'inter_id'=>$v['inter_id'],'activited_id'=>$v['activited_id'],'invited_userid'=>$v['touser_id']);
                    $reward_value = $this->get_point($where,'credit_value','kiminvited_credits_value');
                    $v['total_value'] = $reward_value;
                    $result[$k]['total_value'] = $reward_value;
                    //end

                    $where = array('inter_id'=>$v['inter_id'],'member_type'=>'2','reward_type'=>array('1','2','3','4'),'member_info_id'=>$params['fromuser_id'],'record_id'=>$v['id']);
                    $reward = $this->get_reward($where);
                    $v['count'] = ' -- ';
                    $result[$k]['count'] = ' -- ';
                    $v['title'] = ' -- ';
                    $result[$k]['title'] = ' -- ';
                    if(!empty($reward)){
                        $v['count'] = $reward['count'];
                        $result[$k]['count'] = $reward['count'];
                        $v['title'] = $reward['title'];
                        $result[$k]['title'] = $reward['title'];
                    }
                }

                //判断combobox类型需要对值进行转换
                $vo = array();
                foreach($field_config as $sk=>$sv){
                    $vo[$sk] = $v[$sk];
                    if($field_config[$sk]['type']=='combobox') {
                        if( isset($field_config[$sk]['select'][$v[$sk]])){
                            $vo[$sk]= $field_config[$sk]['select'][$v[$sk]];
                        }
                        else $vo[$sk]= '--';
                    }
                    if( $field_config[$sk]['grid_function'] ) {
                        $funp= explode('|', $field_config[$sk]['grid_function']);
                        $fun= $funp[0];
                        $funp[0]= $v[$sk];
                        $funp[1] = $v['inter_id'];
                        $vo[$sk]= call_user_func_array (array($this, $fun), $funp);
                    } else if( $field_config[$sk]['function'] ) {
                        $funp= explode('|', $field_config[$sk]['function']);
                        $fun= $funp[0];
                        $funp[0]= $v[$sk];
                        $funp[1] = $v['inter_id'];
                        $vo[$sk]= call_user_func_array (array($this, $fun),$funp);
                    }
                }//---
                $el= array_values($vo);
                $el['DT_RowId']= $v['id'];
                $tmp[]= $el;
            }

            $result= $tmp;
        }

        if(is_ajax_request()){
            return array(
                'total'=>$total,
                'draw'=> isset($params['draw'])? $params['draw']: 1,
                'data'=> $result,
                'recordsTotal'=>$total,
                'recordsFiltered'=>$total,
            );
        }else{
            return array(
                'total'=>$total,
                'data'=>$result,
                'page_size'=>$page_size,
                'page_num'=>$current_page,
            );
        }
    }

    /**
     * 运行日志记录
     * @param String $content
     */
    public function _write_log($content,$type,$dir_path='Kiminvited') {
        if(is_array($content) || is_object($content))
            $content = json_encode($content);
        $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'. DS. 'membervip'. DS. $dir_path. DS;
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