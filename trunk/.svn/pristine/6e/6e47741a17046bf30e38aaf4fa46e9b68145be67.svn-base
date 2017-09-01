<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by blackkaffa.
 * User: ibuki
 * Date: 16/8/16
 * Time: 上午11:49
 */
class Member_info_model extends MY_Model_Member {

    public function table_name(){
        return 'member_info';
    }

    /**
     * grid表格中默认哪个字段排序，排序方向
     */
    public static function default_sort_field(){
        return array('field'=>'subtime,createtime', 'sort'=>'desc');
    }

    public static function get_state_options($type=1, $alias= array() ){
        $array = array();
        if( count($alias)>1 ){
            $array= $alias;

        } else {
            switch ($type){
                case 1:
                    $array= array(
                        't'=> '正常',
                        'f'=> '禁用',
                    );
                    break;
                case 2:
                    $array= array(
                        't'=> '已登录',
                        'f'=> '未登录',
                    );
                    break;
                case 3:
                    $array= array(
                        '1'=> '男',
                        '2'=> '女',
                        '3'=>'未知'
                    );
                    break;
                case 4:
                    $array= array(
                        '97'=> '员工',
                        '98'=>'业主'
                    );
                    break;
                case 5:
                    $array= array(
                        '1'=> '<font color="green">已审核</font>',
                        '2'=>'未审核',
                        '0'=>'<font color="red">不通过</font>'
                    );
                    break;
            }

        }
        return $array;
    }

    //定义 m_save 保存时不做转义字段
    public function unaddslashes_field(){
        return array(
            'msg',
            'result',
            'content',
        );
    }

    /**
     * 后台管理的表格中要显示哪些字段
     */
    public function grid_fields()
    {
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
        return array('member_info_id','inter_id' ,'nickname', 'name', 'sex', 'membership_number', 'telephone','member_lvl_id', 'credit', 'balance', 'is_active', 'is_login','member_type','company_name','employee_id','createtime','audit');
    }

    /**
     * 后台管理的模糊查询的字段
     */
    public function like_fields()
    {
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
        return array('member_info_id', 'nickname', 'name', 'membership_number', 'member_lvl_id','telephone');
    }

    /**
     * 返回模版信息表member_message_template的主键
     * @return string
     */
    public function table_primary_key(){
        return 'member_info_id';
    }

    public function _parsedate(){
        $data = func_get_args();
        $date = date('Y-m-d H:i',$data[0]);
        return $date;
    }

    public function _parsemembership_number(){
        $data = func_get_args();
        $levels = $this->_shard_db()->select("lvl_name")->where(array("member_lvl_id"=>$data[0],'inter_id'=>$data[1]))->get("member_lvl")->row_array();
        if(!empty($levels)) return $levels['lvl_name'];
        return '微信粉丝';
    }

    //获取指定等级ID的等级信息
    public function _get_member_lvl($member_lvl_ids){
        if(empty($member_lvl_ids)) return array();
        $_levels = $this->_shard_db()->select("member_lvl_id,lvl_name")->where_in('member_lvl_id',$member_lvl_ids)->get("member_lvl")->result_array();
        $levels = array();
        if(!empty($_levels)){
            foreach ($_levels as $key=>$item){
                $levels[$item['member_lvl_id']] = $item['lvl_name'];
            }
        }
        return $levels;
    }

    /**
     * @param array $params 条件参数
     * @param array $select 获取字段
     * @param string $format
     * @return array
     */
    public function filter($params=array(), $select= array(), $format='array'){
        $exp=array(' >',' <',' !=');
        $table= $this->table_name();
        $where= $where_in= array();
        $dbfields= array_values($fields= $this->_shard_db()->list_fields($table));
        foreach ($params as $k=>$v){
            //过滤非数据库字段，以免产生sql报错，把in匹配另外处理
            if(in_array($k, $dbfields) ){
                if( is_array($v)){
                    $_exp=isset($v[0])?(in_array($v[0],$exp)?$v[0]:''):'';
                    if($_exp && isset($v[1]))
                        $where[$k.$_exp]=$v[1];
                    else
                        $where_in[$k]= $v;
                } else {
                    $where[$k]= $v;
                }
            }
        }
        $pk= $this->table_primary_key();
        if(isset($params['sort_field']) && isset($params['sort_direct'])){
            $sort= $params['sort_field']. ' '. $params['sort_direct'];
        } else $sort= "{$pk} DESC";  //默认排序

        $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
        $page_size= isset($params['page_size'])? $params['page_size']: $num;
        if(isset($params['length'])){
            $page_size = $params['length'];
        }
        $current_page= isset($params['page_num'])? $params['page_num']: 1;

        if(count($select)==0) {
            $select= $this->grid_fields();
        }
        $select= count($select)==0? '*': implode(',', $select);

        //echo $select;die;
        $offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;
        if(isset($params['start'])){
            $offset = $params['start'];
        }
        if( count($where_in)>0 ){
            foreach ($where_in as $k => $v ){
                if( count($v) ) $this->_shard_db()->where_in($k, $v);
            }
        }

        $total= $this->_shard_db()->select(" {$select} ")->get_where($table, $where)->num_rows();

        if( count($where_in)>0 ){
            foreach ($where_in as $k => $v ){
                if( count($v) ) $this->_shard_db()->where_in($k, $v);
            }
        }
        $result= $this->_shard_db()->select(" {$select} ")->order_by($sort)
            ->limit($page_size, $offset)->get_where($table, $where)
            ->result_array();

        $member_lvl_ids = array();
        if(!empty($result)){
            foreach ($result as $key=>$item){
                $member_lvl_ids[] = $item['member_lvl_id'];
            }
        }

        $member_lvl = array();
        if(!empty($member_lvl_ids)){
            $member_lvl = $this->_get_member_lvl($member_lvl_ids);
        }


        if($format=='array'){
            $tmp= array();
            $field_config= $this->get_field_config('grid');
//            unset($field_config['inter_id']);
            foreach ($result as $k=> $v){
                $result[$k]['member_lvl_id'] = isset($member_lvl[$v['member_lvl_id']])?$member_lvl[$v['member_lvl_id']]:'微信粉丝';
                $v['member_lvl_id'] = $result[$k]['member_lvl_id'];
                //判断combobox类型需要对值进行转换
                foreach($field_config as $sk=>$sv){
                    if($sk=='subtime' && !$v[$sk]){
                        $v[$sk] = $v['createtime'];
                    }
                    if($field_config[$sk]['type']=='combobox') {
                        if( isset($field_config[$sk]['select'][$v[$sk]])){
                            $v[$sk]= $field_config[$sk]['select'][$v[$sk]];
                        }
                        else $v[$sk]= '--';
                    }
                    if( $field_config[$sk]['grid_function'] ) {
                        $funp= explode('|', $field_config[$sk]['grid_function']);
                        $fun= $funp[0];
                        $funp[0]= $v[$sk];
                        $funp[1] = $v['inter_id'];
                        $v[$sk]= call_user_func_array (array($this, $fun), $funp);
                    } else if( $field_config[$sk]['function'] ) {
                        $funp= explode('|', $field_config[$sk]['function']);
                        $fun= $funp[0];
                        $funp[0]= $v[$sk];
                        $funp[1] = $v['inter_id'];
                        $v[$sk]= call_user_func_array (array($this, $fun),$funp);
                    }
                }//---

                $el= array_values($v);
                $el['DT_RowId']= $v[$this->table_primary_key()];
                $tmp[]= $el;
            }
            $result= $tmp;
        }
        if($this->input->get('debug')=='1') {
            echo '<pre>';
            echo $this->_shard_db()->last_query();
            print_r($field_config);
            print_r($result);
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

    /**
     * @param String $type   grid|form
     * 统一生成字段配置数组，赋予模板
     */
    public function get_field_config($type='grid')
    {
        $data= array();
        if($type=='grid'){
            $show= $this->grid_fields();
            //grid多选状态必须有主键
            array_unshift( $show, $this->table_primary_key());

        } else {
            //有时需要取数据库以外的字段，如 密码确认字段，在模板手动添加
            $show= $this->_shard_db()->list_fields($this->table_name());
        }

        $fields= $this->attribute_labels();
        $fields_ui= $this->attribute_ui();
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
     * 后台模版表格表头字典
     * @return array
     */
    public function attribute_labels() {
        return array(
            'member_info_id'=> '会员信息ID',
            'inter_id'=> '酒店ID',
            'nickname'=> '昵称',
            'name'=> '会员名称',
            'sex'=> '性别',
            'membership_number'=> '会员卡编号',
            'telephone'=>'手机号',
            'member_lvl_id'=> '会员等级',
            'credit'=> '积分',
            'balance'=> '储值',
            'is_active'=> '是否冻结',
            'is_login'=> '是否登录',
            'member_type'=>'业主/员工',
            'company_name'=>'公司名称',
            'employee_id'=>'员工号',
            'createtime'=>'注册时间',
            'audit'=>'是否审核'
        );
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
    public function attribute_ui()
    {
        /* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
        //type: numberbox数字框|combobox下拉框|text不写时默认|datebox
        return array(
            'member_info_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'nickname' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'name' => array(
                'grid_ui'=> '',
                'grid_width'=> '7%',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'sex' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=>$this->get_state_options(3)
            ),
            'membership_number' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'telephone' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'member_lvl_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_hide'=> TRUE,
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
            'is_active' => array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $this->get_state_options(1),
            ),
            'is_login' => array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $this->get_state_options(2),
            ),
            'member_type'=>array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $this->get_state_options(4),
            ),
            'company_name'=>   array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'employee_id'=>array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'createtime'=>array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
                'grid_function'=>'_parsedate'
            ),
            'audit'=>array(
                'grid_ui'=> '',
                'grid_width'=> '6%',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $this->get_state_options(5),
            ),
        );
    }

    /**
     * 功能跟filter差不多，作用于datatable grid ajax查询，下列为 $params 中带有的参数
    order[0][column]:6 排序列索引
    order[0][dir]:desc 排序方向
    start:0	开始记录
    length:20 每页条数
    search[value]: 搜索字眼
    search[regex]:false
     */
    public function filter_json($params=array(), $select= array() ){
        $exp=array(' >',' <',' !=');
        $table= $this->table_name();
        $where= $where_in= array();
        $dbfields= array_values($fields= $this->_shard_db()->list_fields($table));
        foreach ($params as $k=>$v){
            //过滤非数据库字段，以免产生sql报错，把in匹配另外处理
            if(in_array($k, $dbfields) ){
                if( is_array($v)){
                    $_exp=isset($v[0])?(in_array($v[0],$exp)?$v[0]:''):'';
                    if($_exp && isset($v[1]))
                        $where[$k.$_exp]=$v[1];
                    else
                        $where_in[$k]= $v;
                } else {
                    $where[$k]= $v;
                }
            }
        }

        if(isset($params['search']) && is_array($params['search']) && !empty($params['search'])){
            $params['f_like'] = $params['search'];
        }

        if( isset($params['order'][0]['column']) && isset($params['order'][0]['dir']) ){
            $field= $this->field_name_in_grid($params['order'][0]['column']);
            $sort= $field. ' '. $params['order'][0]['dir'];

        } else {
            $pk= $this->table_primary_key();
            $sort= "{$pk} DESC";  //默认排序
        }

        if(count($select)==0) {
            $select= $this->grid_fields();
        }
        $select= count($select)==0? '*': implode(',', $select);


        /** 总条数计算  **/
        $search= $this->_shard_db()->select(" {$select},createtime");
        if( count($where_in)>0 ){
            foreach ($where_in as $k => $v ){
                if( count($v) ) $this->_shard_db()->where_in($k, $v);
            }
        }
        if( isset($params['f_like']) && count($params['f_like'])>0 ){
            //模糊匹配参数
            $like_field=$this->like_fields();
            $or_like = '';
            foreach ($like_field as $sv) {
                if(isset($params['f_like']['value']) && !empty($params['f_like']['value']))
                    $or_like .= $sv.' LIKE \'%'.$params['f_like']['value'].'%\' OR ';
            }
            if(!empty($or_like)){
                $or_likes = '('.substr($or_like, 0,-3).')';
                $search= $search->where($or_likes);
            }
        }

        if( isset($params['f_match']) && count($params['f_match'])>0 ){
            //准确匹配参数
            foreach ($params['f_match'] as $sk=> $sv) $search= $search->where($sk, $sv);
        }
        $total= $search->get_where($table, $where)->num_rows();

        /** 数据查询 **/
        $search= $this->_shard_db()->select(" {$select} ");
        if( count($where_in)>0 ){
            foreach ($where_in as $k => $v ){
                if( count($v) ) $this->_shard_db()->where_in($k, $v);
            }
        }
        if( isset($params['f_like']) && count($params['f_like'])>0 ){
            //模糊匹配参数
//            foreach ($params['f_like'] as $sk=> $sv) $search= $search->like($sk, $sv);
            $like_field=$this->like_fields();
            $or_like = '';
            foreach ($like_field as $sv) {
                if(isset($params['f_like']['value']) && !empty($params['f_like']['value']))
                    $or_like .= $sv.' LIKE \'%'.$params['f_like']['value'].'%\' OR ';
            }
            if(!empty($or_like)){
                $or_likes = '('.substr($or_like, 0,-3).')';
                $search= $search->where($or_likes);
            }
        }
        if( isset($params['f_match']) && count($params['f_match'])>0 ){
            //准确匹配参数
            foreach ($params['f_match'] as $sk=> $sv) $search= $search->where($sk, $sv);
        }
        $result= $search->order_by($sort)
            ->limit($params['length'], $params['start'])->get_where($table, $where)
            ->result_array();


        $tmp= array();
        $field_config= $this->get_field_config('grid');
        foreach ($result as $k=> $v){
            //判断combobox类型需要对值进行转换
            foreach($field_config as $sk=>$sv){
                if($sk=='subtime' && !$v[$sk]){
                    $v[$sk] = $v['createtime'];
                }
                if($field_config[$sk]['type']=='combobox') {
                    if( isset($field_config[$sk]['select'][$v[$sk]]))
                        $v[$sk]= $field_config[$sk]['select'][$v[$sk]];
                    else $v[$sk]= '--';
                }
                if( $field_config[$sk]['grid_function'] ) {
                    $funp= explode('|', $field_config[$sk]['grid_function']);
                    $fun= $funp[0];
                    $funp[0]= $v[$sk];
                    $funp[1] = $v['inter_id'];
                    $v[$sk]= call_user_func_array (array($this, $fun), $funp);
                } else if( $field_config[$sk]['function'] ) {
                    $funp= explode('|', $field_config[$sk]['function']);
                    $fun= $funp[0];
                    $funp[0]= $v[$sk];
                    $funp[1] = $v['inter_id'];
                    $v[$sk]= call_user_func_array (array($this, $fun), $funp);
                }
            }//-----

            $el= array_values($v);
            $el['DT_RowId']= $v[$this->table_primary_key()];
            $tmp[]= $el;
        }
        $result= $tmp;
        return array(
            'draw'=> isset($params['draw'])? $params['draw']: 1,
            'data'=> $result,
            'recordsTotal'=>$total,
            'recordsFiltered'=>$total,
        );
    }
}