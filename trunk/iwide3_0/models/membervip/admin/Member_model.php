<?php

/**
 * Created by knight.
 * User: ibuki
 * Date: 16/7/30
 * Time: 下午9:25
 */
class Member_model extends MY_Model_Member {

    const SPACE = ' '; //空格符

    /**
     * 获取会员等级配置列表
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
     * 通过等级获取用户信息
     * @param string $inter_id
     * @param array $member_ids
     * @param string $field
     * @return bool|array
     */
    public function get_user_by_lvl($inter_id = '',$member_ids = array(),$field = ''){
        $where = array(
            'inter_id'=>$inter_id,
            'is_active'=>'t',
        );
        if(empty($field)) $field = 'inter_id,open_id,member_info_id,membership_number,telephone,cellphone';
        $user_info = $this->_shard_db()->select($field)->where($where)->where_in('member_lvl_id',$member_ids)->get('member_info')->result_array();
        return $user_info;
    }

    /**
     * 通过open_id获取用户信息
     * @param $inter_id
     * @param array $openids
     * @param string $field
     * @return mixed
     */
    public function get_user_by_openids($inter_id,$openids = array(),$field=''){
        $where = array(
            'inter_id'=>$inter_id,
            'is_active'=>'t',
            'member_mode'=>1
        );
        if(empty($field)) $field = 'inter_id,open_id,member_info_id,membership_number,telephone,cellphone';
        $user_info = $this->_shard_db()->select($field)->where($where)->where_in('open_id',$openids)->get('member_info')->result_array();
        return $user_info;
    }

    /**
     * 获取会员数据／会员总数
     * @param array $params 检索参数
     * @param string $field 检索字段
     * @param bool $is_num 是否返回数量
     * @return mixed | array
     */
    public function get_member_info_list($params=array(),$field = '*',$is_num = false){
        $table = 'member_info';
        $dbfields = array_values($this->_shard_db()->list_fields($table));
        $exp=array('>','<','!=','<>');
        $where = $where_in = array();
        foreach ($params as $k => $v){
            if(in_array($k, $dbfields) ){
                if( is_array($v)){
                    $_exp = isset($v[0])?(in_array($v[0],$exp)?$v[0]:''):'';
                    if($_exp && isset($v[1]))
                        $where[$k.self::SPACE.$_exp] = $v[1];
                    else
                        $where_in[$k] = $v;
                } else {
                    $where[$k] = $v;
                }
            }
        }

        $pk= 'member_info_id';
        if(!empty($params['sort_field']) && !empty($params['sort_direct'])){
            $sort = $params['sort_field']. ' '. $params['sort_direct'];
        } else $sort= "{$pk} DESC";  //默认排序

        $offset = isset($params['offset'])?$params['offset']: 0; //获取起始行
        $page_size = isset($params['page_size'])?$params['page_size']: 5000000; //获取行数

        if($is_num === true){
            $this->_shard_db()->select($field)->from($table);
            //in条件
            if( count($where_in)>0 ){
                foreach ($where_in as $k => $v ){
                    if( count($v) ) $this->_shard_db()->where_in($k, $v);
                }
            }

            $total = $this->_shard_db()->where($where)->get()->num_rows();
            MYLOG::w(@json_encode(array('total'=>$total,'SQL'=>$this->_shard_db()->last_query())),'admin/membervip/debug-log','task-sql');
            return $total;
        }

        $this->_shard_db()->select($field)->from($table);

        if( count($where_in)>0 ){
            foreach ($where_in as $k => $v ){
                if( count($v) ) $this->_shard_db()->where_in($k, $v);
            }
        }

        $result = $this->_shard_db()->where($where)->order_by($sort)->limit($page_size, $offset)->get()->result_array();
        return $result;
    }

    /**
     * 获取会员信息列表
     * @param array $params 条件参数组
     * @param array $select 查询字段
     * search[value]: 搜索字眼
     * @return mixed
     */
    public function get_admin_member_info_list($params=array(),$select=array()){
        $return['total'] = 0;
        $return['data'] = array();
        $table = 'member_info';
        $dbfields = array_values($this->_shard_db()->list_fields($table));
        $exp=array('>','<','!=','<>');
        $where = $where_in = array();
        foreach ($params as $k=>$v){
            //过滤非数据库字段，以免产生sql报错，把in匹配另外处理
            if(in_array($k, $dbfields) ){
                if( is_array($v)){
                    $_exp=isset($v[0])?(in_array($v[0],$exp)?$v[0]:''):'';
                    if($_exp && isset($v[1]))
                        $where[$k.self::SPACE.$_exp]=$v[1];
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
                        $_exp=isset($v[0])?(in_array($v[0],$exp)?$v[0]:''):'';
                        if($_exp && isset($v[1]))
                            $where[$k.self::SPACE.$_exp]=$v[1];
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

        $pk= $alias.'.'.$params['pk'];
        if(isset($params['sort_field']) && isset($params['sort_direct'])){
            $sort= $params['sort_field']. ' '. $params['sort_direct'];
        } else $sort= "{$pk} DESC";  //默认排序

        $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
        $page_size= isset($params['page_size'])? $params['page_size']: $num; //获取行数
        if(isset($params['length'])){
            $page_size = $params['length'];
        }

        $return['page_size'] = $page_size;

        $current_page = isset($params['page_num'])? $params['page_num']: 1;

        $return['current_page'] = $current_page;

        if(count($select)==0) $select = array($alias.'.member_info_id',$alias.'.inter_id' ,$alias.'.nickname', $alias.'.name', $alias.'.sex', $alias.'.membership_number', $alias.'.telephone',$alias.'.member_lvl_id', $alias.'.credit', $alias.'.balance', $alias.'.is_active', $alias.'.is_login',$alias.'.member_type',$alias.'.company_name',$alias.'.employee_id',$alias.'.createtime',$alias.'.audit');
        $select= count($select)==0? '*': implode(',', $select);
        $offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0; //获取起始行
        if(isset($params['start'])){
            $offset = $params['start'];
        }

        $this->_shard_db()->select("COUNT($alias.member_info_id) as count")->from($table.' as '.$alias);

        //in条件
        if( count($where_in)>0 ){
            foreach ($where_in as $k => $v ){
                if( count($v) ) $this->_shard_db()->where_in($k, $v);
            }
        }

        if(isset($params['f_like']['value']) && !empty($params['f_like']['value'])){
            //模糊匹配参数
            $like_field=array($alias.'.member_info_id',$alias.'.nickname',$alias.'.name', $alias.'.membership_number', $alias.'.member_lvl_id',$alias.'.telephone');
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
        $total= $this->_shard_db()->where($where)->get()->row_array();

        if($this->input->get('debug')=='1') {
            echo $this->_shard_db()->last_query();
            echo '<br/>';
        }
        if($tbug=='1'){
            echo 'cendtime:'.date('Y-m-d H:i:s');
            echo '<br/>';
        }
        $return['total'] = $total['count'];

        $this->_shard_db()->select("{$select}")->from($table.' as '.$alias);

        //联表查询
        if(isset($params['join']) && !empty($params['join']) && is_array($params['join'])){
            $join = $params['join'];
            foreach ($join as $key=>$item){
                $this->_shard_db()->join($item['table'],$item['on'],$item['type']);
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
            $like_field=array($alias.'.member_info_id',$alias.'.nickname',$alias.'.name', $alias.'.membership_number', $alias.'.member_lvl_id',$alias.'.telephone');
            $this->_shard_db()->group_start();
            foreach ($like_field as $sv) {
                $this->_shard_db()->or_like($sv,$params['f_like']['value']);
            }
            $this->_shard_db()->group_end();
        }

        //分组查询
        if(isset($params['group_by']) && !empty($params['group_by'])) $this->_shard_db()->group_by($params['group_by']);
        if($tbug=='1'){
            echo 'startime:'.date('Y-m-d H:i:s');
            echo '<br/>';
        }
        $result = $this->_shard_db()->where($where)->order_by($sort)->limit($page_size, $offset)->get()->result_array();

        if($params['mcount']===true){ //计算会员的有效优惠券数量
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
        }

        if(!empty($params['member_lvl_data'])){
            $member_lvl = array();
            foreach ($params['member_lvl_data'] as $lvl){
                $member_lvl[$lvl['member_lvl_id']] = $lvl['lvl_name'];
            }

            foreach ($result as &$mem){ //会员等级名称整合到会员数据中
                $mem['lvl_name'] = !empty($member_lvl[$mem['member_lvl_id']])?$member_lvl[$mem['member_lvl_id']]:' --- ';
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
     * 获取默认排序字段在grid罗列字段中的索引序号（grid模板datatable.js中使用）
     * @param unknown $field
     * @return Ambigous <number, unknown>
     */
    public function field_index_in_grid($field)
    {
        $index= 0;
        $fields= $this->grid_fields();
        foreach($fields as $k=>$v){
            if($v==$field) $index= $k;
        }
        return $index;
    }

    /**
     * @param array $params 条件参数组
     * @param array $select 查询字段
     * @param string $format
     * @return array
     */
    public function get_admin_member_filter($params=array(),$select=array(),$member_mode='',$format='array'){
        $return = $this->get_admin_member_info_list($params,$select);
        $total = $return['total'];
        $result = $return['data'];
        $page_size = $return['page_size'];
        $current_page = $return['current_page'];
        $tbug = $this->input->get('tbug');
        if($tbug=='1'){
            echo 'arraystartime:'.date('Y-m-d H:i:s');
            echo '<br/>';
        }
        if($format=='array'){
            $tmp= array();
            $field_config= $this->get_field_config('grid');
            foreach ($result as $k=> $v){
                $vo = array();
                //判断combobox类型需要对值进行转换
                foreach($field_config as $sk=>$sv){
                    if($sk=='operation') $vo[$sk] = ''; else $vo[$sk] = $v[$sk];
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
                        $funp[1] = $v['inter_id'];
                        switch ($sk){
                            case 'member_mode':
                                $funp[2] = $member_mode;
                                $funp[3] = $v['cellphone'];
                                break;
                            case 'is_login':
                                $funp[2] = $member_mode;
                                $funp[3] = $v['cellphone'];
                                break;
                            case 'operation':
                                $funp[2] = $v['member_info_id'];
                                $funp[3] = $v['open_id'];
                                $funp[4] = $v['membership_number'];
                                $funp[5] = $v['name'];
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
                    $el['DT_RowId']= $v[$params['pk']];
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
            return array(
                'draw'=> isset($params['draw'])? $params['draw']: 1,
                'data'=> $result,
                'recordsTotal'=>$total,
                'recordsFiltered'=>$total,
            );
        }
        return array(
            'total'=>$total,
            'data'=>$result,
            'page_size'=>$page_size,
            'page_num'=>$current_page,
        );
    }

    protected function _get_operation(){
        $data = func_get_args();
        $member_info_id = $data[2];
        $openid = $data[3];
        $membership_number = $data[4];
        $name = $data[5];
        $url = EA_const_url::inst()->get_url('membervip/membermanage/add',array('member_info_id'=>$member_info_id));
        $button = '<a class="btn btn-sm btn-default" href="'.$url.'">查看详细</a>';
        $button .= '<button type="button" dataid="'.$member_info_id.'" attrno="'.$membership_number.'" attrname="'.$name.'" class="btn btn-sm btn-default adjustment">调整储值</button>';
        $button .= '<a dataid="'.$member_info_id.'" attrno="'.$membership_number.'" attrname="'.$name.'" class="btn btn-sm btn-default integral">积分调整</a>';
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

    protected function _get_is_login(){
        $data = func_get_args();
        $is_login = $data[0];
        $member_mode = $data[2];
        $cellphone = $data[3];
        $name = ' -- ';
        if($is_login=='t'){
            $name = '默认登录';
            if($member_mode=='login' && !empty($cellphone)) $name = '<font color="#26EC0E">已登录</font>';
        }elseif ($is_login=='f'){
            $name = '未登录';
        }
        return $name;
    }

    protected function _parsedatetime(){
        $data = func_get_args();
        $date = date('Y-m-d H:i:s',$data[0]);
        return $date;
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
        }
        return $show;
    }

    /**
     * 后台模版表格表头字典
     * @return array
     */
    public function attribute_labels($type=1) {
        $attribute = array();
        switch ($type){
            case 1:
                $attribute =  array(
                    'member_info_id'=> '会员ID',
                    'nickname'=> '会员昵称',
                    'member_mode'=> '会员类型',
                    'name'=> '会员名称',
                    'membership_number'=> '会员卡号',
                    'lvl_name'=> '会员等级',
                    'credit'=> '会员积分',
                    'balance'=> '储值余额',
                    'mcount'=>'有效卡券总数',
                    'is_active'=> '是否冻结',
                    'is_login'=> '是否登录',
                    'createtime'=>'注册时间',
                    'operation'=>'操作'
                );
                break;
        }
        return $attribute;
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
    public function attribute_ui($type=1)
    {
        $attribute_ui = array();
        switch ($type){
            case 1:
                $attribute_ui=array(
                    'member_info_id' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '7%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'nickname' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '8%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                        'bSortable'=>false
                    ),
                    'member_mode' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '6%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                        'grid_function'=>'_get_member_mode'
                    ),
                    'name' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '6%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'membership_number' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '7%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'lvl_name' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '6%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'credit' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '6%',
                        'form_hide'=> TRUE,
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'balance' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '6%',
                        'form_hide'=> TRUE,
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'mcount' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '8%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'is_active' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '6%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                        'grid_function'=>'_get_is_active'
                    ),
                    'is_login' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '6%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                        'grid_function'=>'_get_is_login'
                    ),
                    'createtime' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '6%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                        'grid_function'=>'_parsedatetime'
                    ),
                    'operation' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '16%',
                        'form_hide'=> TRUE,
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                        'grid_function'=>'_get_operation'
                    )
                );
                break;
        }
        return $attribute_ui;
    }

    /**
     * @param String $type   grid|form
     * 统一生成字段配置数组，赋予模板
     */
    public function get_field_config($type='grid',$flag=1,$table_name='')
    {
        $data = array();
        if($type=='grid'){
            $show= $this->grid_fields($flag);
        } else {
            //有时需要取数据库以外的字段，如 密码确认字段，在模板手动添加
            $show= $this->_shard_db()->list_fields($table_name);
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

    /**
     * 读取优惠券规则列表
     */
    public function get_card_rule($params,$limit=NULL,$offset=0){
        $inter_id = $params['inter_id'];
        $mcrtab = 'member_card_rule';
        $ctab = 'card';
        $ptab = 'package';
        if(!isset($params['field'])) $params['field'] = $mcrtab.'.*';
        if(strpos($params['field'], ',')!==FALSE) {
            $fields = explode(',', $params['field']);
            $field = '';
            foreach ($fields as $v){
                $field.=$mcrtab.'.'.$v.',';
            }
            $params['field'] = substr($field, 0,-1);
        }

        $params['field'] .= ','.$ctab.'.title,'.$ctab.'.card_type,'.$ctab.'.card_stock,'.$ctab.'.card_note';
        $params['field'] .= ','.$ptab.'.name,'.$ptab.'.remark';

        $this->_shard_db()->select($params['field']);
        $this->_shard_db()->join($ctab,$ctab.'.card_id = '.$mcrtab.'.card_id','left');
        $this->_shard_db()->join($ptab,$ptab.'.package_id = '.$mcrtab.'.package_id','left');
        $where=array($mcrtab.'.inter_id'=>$inter_id);
        $this->_shard_db()->where($where);
        /*if(!empty($params['begin_time'])){
            $begin_time = strtotime($params['begin_time']);
            $this->_shard_db()->where('createtime >',$begin_time);
        }

        if(!empty($params['end_time'])){
            $end_time = strtotime($params['end_time']);
            $this->_shard_db()->where('createtime <=',$end_time);
        }*/

        $list = $this->_shard_db()
            ->order_by('createtime desc')
            ->limit($limit,$offset)
            ->get($mcrtab)->result_array();
//        echo '<pre>';
//        print_r($list);exit;

        if($this->input->get('debug') == 1){
            echo $this->_shard_db()->last_query();echo '<br />';
        }
        $this->_shard_db()->get($mcrtab)->free_result();
        return $list;
    }

    /**
     * 读取优惠券规则列表 总数
     */
    public function get_card_rule_total($params){
        $inter_id = $params['inter_id'];
        $mcrtab = 'member_card_rule';
        $ctab = 'card';
        $ptab = 'package';
        $this->_shard_db()->join($ctab,$ctab.'.card_id = '.$mcrtab.'.card_id','left');
        $this->_shard_db()->join($ptab,$ptab.'.package_id = '.$mcrtab.'.package_id','left');
        $where=array($mcrtab.'.inter_id'=>$inter_id);
        $this->_shard_db()->where($where);

        /*if(!empty($params['begin_time'])){
            $begin_time = strtotime($params['begin_time']);
            $this->_shard_db()->where(array('createtime >'=>$begin_time));
        }

        if(!empty($params['end_time'])){
            $end_time = strtotime($params['end_time']);
            $this->_shard_db()->where(array('createtime <='=>$end_time));
        }*/

        $count = $this->_shard_db()->get($mcrtab)->num_rows();
        return $count;
    }

    /**
     * 读取优惠券列表
     */
    public function getMemberCard($params,$limit=NULL,$offset=0){
        $inter_id = $params['inter_id'];
        $ctab = 'card';
        if(!isset($params['field'])) $params['field'] = $ctab.'.*';

        $this->_shard_db()->select($params['field']);
        $where=array($ctab.'.inter_id'=>$inter_id);
        $this->_shard_db()->where($where);
        $list = $this->_shard_db()
            ->order_by('card_id desc')
            ->limit($limit,$offset)
            ->get($ctab)->result_array();

        if($this->input->get('debug') == 1){
            echo $this->_shard_db()->last_query();echo '<br />';
        }
        $this->_shard_db()->get($ctab)->free_result();
        return $list;
    }

    /**
     * 读取优惠券列表 总数
     */
    public function getMemberCardTotal($params){
        $inter_id = $params['inter_id'];
        $ctab = 'card';
        $where=array($ctab.'.inter_id'=>$inter_id);
        $this->_shard_db()->where($where);
        $count = $this->_shard_db()->get($ctab)->num_rows();
        return $count;
    }

    /**
     * 读取储值规则列表
     */
    public function get_deposit_card($params,$limit=NULL,$offset=0){
        $inter_id = $params['inter_id'];
        $cdrtab = 'deposit_card';
        if(!isset($params['field'])) $params['field'] = $cdrtab.'.*';

        $this->_shard_db()->select($params['field']);
        $where=array($cdrtab.'.inter_id'=>$inter_id);
        $this->_shard_db()->where($where);
        $list = $this->_shard_db()
            ->order_by('createtime desc')
            ->limit($limit,$offset)
            ->get($cdrtab)->result_array();
//        echo '<pre>';
//        print_r($list);exit;

        if($this->input->get('debug') == 1){
            echo $this->_shard_db()->last_query();echo '<br />';
        }
        $this->_shard_db()->get($cdrtab)->free_result();
        return $list;
    }

    /**
     * 读取储值规则列表 总数
     */
    public function get_deposit_card_total($params){
        $inter_id = $params['inter_id'];
        $cdrtab = 'deposit_card';
        $where=array($cdrtab.'.inter_id'=>$inter_id);
        $this->_shard_db()->where($where);
        $count = $this->_shard_db()->get($cdrtab)->num_rows();
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
}