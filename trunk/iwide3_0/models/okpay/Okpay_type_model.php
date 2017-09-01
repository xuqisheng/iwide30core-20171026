<?php
class Okpay_type_model extends MY_Model{
    function __construct() {
        parent::__construct ();
    }


    const TAB_OKPAY_TYPE = 'okpay_type';
    public function get_resource_name()
    {
        return 'Okpay_type_model';
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function table_name()
    {
        return self::TAB_OKPAY_TYPE;
    }

    public function table_primary_key()
    {
        return 'id';
    }

    public function attribute_labels()
    {
        return array(
            'id'=> '编号',
            'name'=> '场景名称',
            'create_time'=> '创建时间',
            'update_time'=> '更新时间',
            'status'=> '状态',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店',
            'store_name'=>'按钮名称',
            'store_url'=>'跳转地址',
            'group_id' =>'分组',
            'msgsaler' =>'模板消息通知人',
            'no_sale'  =>'是否显示不优惠金额选项',

        );
    }

    /**
     * 后台管理的表格中要显示哪些字段
     */
    public function grid_fields()
    {
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
        return array(
            'id',
            'name',
            'create_time',
            'update_time',
            'inter_id',
            'status',
            'hotel_id',
            'store_name',
            'store_url',
            'group_id',
            // 'msgsaler',
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
        $base_util = EA_base::inst();
        $modules   = config_item('admin_panels')? config_item('admin_panels'): array();
        /** 获取本管理员的酒店权限  */
        $this->_init_admin_hotels ();
        $publics = $hotels = array ();
        $filter = $filterH = NULL;
        $types = array('0'=>'');
        $salers = array('0'=>'',1=>'');
        if ($this->_admin_inter_id == FULL_ACCESS)
            $filter = array ();
        else if ($this->_admin_inter_id)
            $filter = array (
                'inter_id' => $this->_admin_inter_id
            );
        if (is_array ( $filter )) {
            $this->load->model ( 'wx/publics_model' );
            $publics = $this->publics_model->get_public_hash ( $filter );
            $publics = $this->publics_model->array_to_hash ( $publics, 'name', 'inter_id' );
            // $publics= $publics+ array(FULL_ACCESS=>'-所有公众号-');
        }

        if ($this->_admin_hotels == FULL_ACCESS){
            $filterH = array ();
        }else if ($this->_admin_hotels){
            $filterH = array (
                'hotel_id' => $this->_admin_hotels,
                'inter_id'=>$this->_admin_inter_id
            );
        }else{
            $filterH = array ();
        }
        //$filterH['status'] = 1;

        if ($publics && is_array ( $filterH )) {
            $this->load->model ( 'hotel/hotel_model' );
            $hotels = $this->hotel_model->get_hotel_hash ( $filterH );
            $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
            /* $hotels = $hotels + array (
                    '0' => '-不限定-'
            ); */
            //场景分组
            $tmp = $this->get_all_typesgroup();
            $types = $types+$tmp;
            //对应的分销员
        }
        return array(
            'id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'update_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'function'=> 'unix_to_human|true|cn2',
                'type'=>'datebox',  //textarea|text|combobox|number|email|url|price
            ),
            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                'function'=> 'unix_to_human|true|cn2',
                'type'=>'datebox',  //textarea|text|combobox|number|email|url|price
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
//                      'function'=> 'unix_to_human|true|cn',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select' => array(0 => '不可用', 1 => '可用')
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=>$publics
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select' => $hotels
            ),
            'store_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),'store_url' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'url',  //textarea|text|combobox|number|email|url|price
            ),
            'group_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select' => $types
            ),
            'msgsaler' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select' => $salers
            ),
            'no_sale' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select' => array(0 => '不显示', 1 => '显示')
            ),

        );
    }

    /**
     * grid表格中默认哪个字段排序，排序方向
     */
    public static function default_sort_field()
    {
        return array('field'=>'id', 'sort'=>'desc');
    }

    public function create_okpay_type($arr){
        $arr['create_time'] = time();
        $arr['update_time'] = time();
        $arr['status']      = 1;

        $this->db->insert(self::TAB_OKPAY_TYPE,$arr);
        $insert_id = $this->db->insert_id();
        if($insert_id){
            return true;
        }else{
            return false;
        }
    }

    function get_okpay_type_detail($typeid,$inter_id,$hotel_id,$status = 1) {
        $type = $this->_db('iwide_r1')->get_where ( self::TAB_OKPAY_TYPE, array (
            'id'=>$typeid,
            'inter_id'=>$inter_id,
            'hotel_id'=>$hotel_id,
            'status' => $status
        ) )->row_array();

        return $type;
    }

    /**
     * 获取 指定公众号 酒店的 场景列表
     * @param string $inter_id
     * @param string $hotel_id
     * @param Int $status
     */
    function get_hotel_okpay_type_list($inter_id,$hotel_id,$status = 1){
        $this->_db('iwide_r1')->select('id,name');
        $type = $this->_db('iwide_r1')->get_where ( self::TAB_OKPAY_TYPE, array (
            'inter_id'=>$inter_id,
            'hotel_id'=>$hotel_id,
            'status' => $status
        ) )->result_array();
        //$this->db->last_query();
        return $type;
    }

    function get_okpay_type_detail_with_admin($typeid,$inter_id,$status=1){

        $filterH = array ();
        /* if ($this->_admin_inter_id != FULL_ACCESS){
            $filterH['inter_id'] = $this->_admin_inter_id;
        }
        //很操蛋。。。这权限也是没啥好说了。
        if ($this->_admin_hotels != FULL_ACCESS){
            $filterH['hotel_id'] = $this->_admin_hotels;
        } */
        $filterH['id']      =   $typeid;
        $filterH['status']  =   $status;

        $type = $this->_db('iwide_r1')->get_where (self::TAB_OKPAY_TYPE,$filterH)->row_array();
        return $type;
    }

    /*
     * 获取列表信息
     * */
    public function get_types_info_list($filter = array(),$limit = null,$offset = 0){
        $sql = "select * from iwide_okpay_type where 1=1 ";
        if(isset($filter['inter_id'])){
            $sql .= " and inter_id = '{$filter['inter_id']}'";
        }
        if(isset($filter['name']) && !empty($filter['name'])){
            $sql .= " and name like '%{$filter['name']}%'";
        }
        if(isset($filter['id']) && !empty($filter['id'])){
            $sql .= " and id = " . intval($filter['id']);
        }
        if(isset($filter['status']) && $filter['status'] >= 0){
            $sql .= " and status = " . $filter['status'];
        }
        if(isset($filter['hotel_id']) && $filter['hotel_id'] > 0){
            $sql .= " and hotel_id = " . $filter['hotel_id'];
        }
        if(isset($filter['in_hotel_id']) && is_array($filter['in_hotel_id'])){
            $sql .= " and hotel_id in (" . implode(',',$filter['in_hotel_id']) . ")";
        }
        $sql .= ' order by id desc';
        $argvs = array();
        if(!empty($limit)){
            $sql .= ' LIMIT ?,?';
            $argvs[] = $offset;
            $argvs[] = $limit;
        }
        $query = $this->_db('iwide_r1')->query($sql,$argvs);

        return $query->result_array();
    }

    public function get_types_info_count($filter = array(),$limit = null,$offset = 0){
        $sql = "select count(*) as c from iwide_okpay_type where 1=1 ";
        if(isset($filter['inter_id'])){
            $sql .= " and inter_id = '{$filter['inter_id']}'";
        }
        if(isset($filter['id']) && !empty($filter['id'])){
            $sql .= " and id = " . intval($filter['id']);
        }
        if(isset($filter['status']) && $filter['status'] >= 0){
            $sql .= " and status = " . $filter['status'];
        }
        if(isset($filter['hotel_id']) && $filter['hotel_id'] > 0){
            $sql .= " and hotel_id = " . $filter['hotel_id'];
        }
       // $sql .= ' order by id desc';
        $argvs = array();

        $query = $this->_db('iwide_r1')->query($sql,$argvs)->row_array();

        return $query['c']?$query['c']:0;
    }
    
    /**
     * 查询使用过快乐付的酒店数
     * @return number
     */
    public function get_okpay_used_hotel_count(){
        $sql = "select count(tp.hotel) as cnt from (select count(id) as hotel from iwide_okpay_type as t group by hotel_id) as tp";
        return $this->_db('iwide_r1')->query($sql)->result();
    }

    //场景分组
    //获取场景分组的信息
    public function get_type_group_info($inter_id = ''){
        $sql = "select * from iwide_okpay_type_group where 1 = 1";
        $inter_id_sql = '';
        if(!empty($inter_id)){
            $inter_id_sql = " and inter_id = '{$inter_id}' ";
        }
        $res = $this->_db('iwide_r1')->query($sql)->result_array();
        if(!empty($res)){
            $order_count = 0;
            foreach($res as $k=>$v){
                //获取使用酒店数 和 关联场景数 是一样的
                $sql = "select count(*) c from iwide_okpay_type where group_id = '{$v['id']}' ".$inter_id_sql;
                $res[$k]['hotel_count'] = $res[$k]['type_count'] = $this->_db('iwide_r1')->query($sql)->row()->c;
                //获取订单数 和成交额
                $sql = "select count(*) order_count,sum(pay_money) trade_money from iwide_okpay_orders where pay_status=3 and pay_type in (select id from iwide_okpay_type where group_id = '{$v['id']}' ".$inter_id_sql.")";
                $query = $this->_db('iwide_r1')->query($sql)->row();
                $order_count += $query->order_count;
                $res[$k]['order_count'] = $query->order_count;
                $res[$k]['trade_money'] = $query->trade_money;
            }
            foreach($res as $key => $value){//渠道占比
                $res[$key]['rate'] = $order_count == 0?0:round($value['order_count'] / $order_count,4)*100 . "%";
            }
        }
        return $res;
    }

    //插入新数据 分组
    public function insert_data($data = array()){
        //查询最大的id  KLF100开始。。。3
        $new_id = '';
        $sql = "select max(id) as id from iwide_okpay_type_group";
        $maxid = $this->_db('iwide_r1')->query($sql)->row()->id;
        if(empty($maxid)) {//空数据时
            $new_id = 'KLF101';
        }else{
            $new_id = substr($maxid,3);
            $new_id = 'KLF' . ($new_id+1);
        }
        $data['id'] = $new_id;
        $res = $this->db->insert('okpay_type_group',$data);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    //获取数据
    public function get_data_by_filter($filter = array(),$limit=NULL,$offset=0){
        //查询group_id 是否有数据
        /*$sql = "select id,inter_id from iwide_okpay_type_group where id = '{$filter['group_id']}'";
        if(!empty($filter['inter_id'])){//非超级管理员权限走这里
            $sql .= " and inter_id = '{$filter['inter_id']}'";
        }
        $query = $this->db->query($sql)->row();
        if(empty($query)){
            return false;
        }*/

        if(!empty($filter['start_time'])){
            $start = strtotime($filter['start_time']." 00:00:00");
        }
        if(!empty($filter['end_time'])){
            $end = strtotime($filter['end_time']." 23:59:59");
        }else{
            $end = strtotime(date('Y-m-t', strtotime('-1 month')));
        }
        $sql = "select c.name as inter_name,a.id,a.inter_id,a.group_id,a.hotel_id,b.name as hotel_name,a.name as type_name,a.create_time from iwide_okpay_type a left join iwide_hotels b on b.inter_id = a.inter_id and a.hotel_id = b.hotel_id left join iwide_publics c on c.inter_id = a.inter_id where a.create_time <= {$end} and a.group_id = '{$filter['group_id']}'";
        if(!empty($filter['inter_id'])){//非超级管理员走这
            $sql .= " and a.inter_id = '{$filter['inter_id']}'";
        }
        $data = $this->_db('iwide_r1')->query($sql)->result_array();
        $sum_trade_money = 0;
        if(!empty($data)){
            foreach($data as $k=>$v){
                //查询成交数 和总额
                $sql = "select count(*) order_count,sum(pay_money) trade_money from iwide_okpay_orders where pay_status = 3 and  pay_type = {$v['id']}";
                if(!empty($filter['inter_id'])){//非超级管理员走这
                    $sql .= " and inter_id = '{$filter['inter_id']}'";
                }
                $sql .= " and pay_time >= {$start} and pay_time < {$end}";
                $res = $this->_db('iwide_r1')->query($sql)->row();
                $data[$k]['order_count'] = $res->order_count;
                $sum_trade_money += $res->trade_money;
                $data[$k]['trade_money'] = $res->trade_money;
            }
        }
        if(!empty($data)){
            foreach($data as $key =>$value){
                $data[$key]['rate'] = empty($sum_trade_money)?0:round($value['trade_money']/$sum_trade_money,4) * 100 ."%";
            }
        }
        return $data;

    }

    //获取所有场景分组
    public function get_all_typesgroup($filter = array()){
        $sql = "select * from iwide_okpay_type_group";
        if(isset($filter['inter_id']) && !empty($filter['inter_id'])){
            $sql .= " where inter_id = '{$filter['inter_id']}'";
        }
        $res = $this->_db('iwide_r1')->query($sql)->result_array();
        $data = array();
        if(!empty($res)){
            foreach($res as $k=>$v){
                $data[$v['id']] = $v['name'];
            }
        }
        return $data;
    }
    //获取场景的分销员信息
    public function get_type_saler_info($inter_id = '',$type_id = 0){
        /*$sql = "select a.qrcode_id,a.openid from iwide_hotel_staff a left join iwide_okpay_type b on a.inter_id = b.inter_id and a.qrcode_id = b.msgsaler where a.inter_id = '{$inter_id}' and b.id = {$type_id}";
        $res = $this->_db('iwide_r1')->query($sql)->result_array();
        return $res;*/
        //先查出对应shop的分销员数据
        $sql = "select msgsaler from iwide_okpay_type where inter_id = '{$inter_id}' and id = {$type_id} limit 1";
        $saler = $this->_db('iwide_r1')->query($sql)->row_array();
        if(!empty($saler) && !empty($saler['msgsaler'])){
            $saler = $saler['msgsaler'];
            $sql = "select qrcode_id,openid from iwide_hotel_staff where inter_id = '{$inter_id}' and qrcode_id in ({$saler})";
            $res = $this->_db('iwide_r1')->query($sql)->result_array();
        return $res;
        }else{
            return '';
    }
    }

}
