<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH. 'models'. DS. 'soma'. DS. 'Activity_model.php');
class Activity_groupon_model extends Activity_model {
    
    /**
     * 购买商品对象(数组)，内含各类产品model对象 ，如 Product_package_model
     * @var Array 
     */
    public $product= array();

    const GROUP_NO_TEXIST = 1;  //活动失效、不存在
    const GROUP_EXPIRATION = 2; //活动已过期
    const GROUP_NOT_START = 3; //活动还没开始

    const GROUP_STATUS_WAITING_PAY  = 1;  //等待第一个用户支付
    const GROUP_STATUS_ING          = 2;   //进行中
    const GROUP_STATUS_FINISHED    = 3;  //成功
    const GROUP_STATUS_FAILED       = 4;  //人数不够失效

    const GROUP_USER_VALIDATE   = 1;    //用户拼团信息有效
    const GROUP_USER_INVALIDATE = 2;    //用户拼团信息无效
    const GROUP_USER_WAITING_PAY = 3;    //用户拼团等待支付
    const GROUP_USER_FINISHED    = 4;    //用户拼团完成
    const GROUP_USER_EXPIRATION  = 5;    //用户拼团已失效（不能再次发起支付)
    const GROUP_USER_REFUND = 6;        //用户拼团失败，申请退款


    const GROUP_ADD_STATUS_SUCCESS = 1;   //用户拼团支付成功
    const GROUP_ADD_STATUS_WAITING_PAY = 2;      //用户等待支付
    const GROUP_ADD_STATUS_EXPIRATION = 3;  //支付已超过有效期
    const GROUP_ADD_STATUS_FAILED = 4;      //用户等待支付

    const PRICE_PERCENT_LIMIT = 0;    //低于此价格倍数不允许下单
    const PRICE_PERCENT_NOTICE = 0.5;    //低于此价格倍数发出报警
    
    public function get_validate_msg(){
        return array(
            self::GROUP_NO_TEXIST  => '拼团不存在',
            self::GROUP_EXPIRATION => '此拼团已经超过有效期',
            self::GROUP_NOT_START  => '拼团尚未开始'
        );
    }

    public function get_group_status(){
        return array(
            self::GROUP_STATUS_ING          => '拼团订单进行中',
            self::GROUP_STATUS_FINISHED    => '成功拼团',
            self::GROUP_STATUS_FAILED       => '拼团人数不够'
//            self::GROUP_STATUS_EXPIRATION  => '拼团订单过期',
        );
    }

    public function get_group_status_msg($num = NULL){
        if(empty($num)){
            return array(
                self::GROUP_STATUS_ING          => '我要入团！点击购买',
                self::GROUP_STATUS_FINISHED    => '组团成功！点击查看我的订单',
                self::GROUP_STATUS_FAILED       => '我不服，再开一团！'
            );
        }else{
            return array(
                self::GROUP_STATUS_ING          => '还差'.$num.'人！点击邀请好友入团',
                self::GROUP_STATUS_FINISHED    => '组团成功！点击查看我的订单',
                self::GROUP_STATUS_FAILED       => '我不服，再开一团！'
            );
        }


    }

    public function get_resource_name()
    {
        return '拼团信息';
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function table_name($inter_id=NULL)
    {
		return $this->_shard_table('soma_activity_groupon', $inter_id);
    }
    public function table_name_r($inter_id=NULL)
    {
        return $this->_shard_table_r('soma_activity_groupon', $inter_id);
    }

    public function group_table_name($inter_id=NULL)
    {
        return $this->_shard_table('soma_activity_groupon_group', $inter_id);
    }

    public function groupon_user_table_name($inter_id=NULL)
    {

        return $this->_shard_table('soma_activity_groupon_user', $inter_id);
    }

    public function table_primary_key()
    {
        // return 'group_id';
        return 'act_id';
    }

    public function attribute_labels()
    {
        return array(            
            'act_id'=> '活动编号',
            'inter_id'=> '公众号ID',
            'hotel_id'=> '酒店',
            'banner_url'=> '封面图',
            'act_type'=> '活动类型',
            'act_name'=> '活动名称',
            'product_id'=> '选择商品',
            'product_name'=> '商品名',
            'group_price'=> '团购价',
            'group_count'=> '拼团人数',
            'group_deadline'=> '拼团天数',
            'keyword'=> '关键词描述',
            'start_time'=> '开始时间',
            'end_time'=> '结束时间',
            'create_time'=> '创建时间',
            'status'=> '状态',
        );
    }

    /**
     * 后台管理的表格中要显示哪些字段
     */
    public function grid_fields()
    {
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
        return array(            
            'act_id',
            'inter_id',
            //'hotel_id',
            'banner_url',
            'act_type',
            'act_name',
            // 'product_id',
            'product_name',
            // 'group_price',
            // 'group_count',
            // 'group_deadline',
            'keyword',
            'start_time',
            'end_time',
            // 'create_time',
            'status',
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
        $Somabase_util= Soma_base::inst();
        $modules= config_item('admin_panels')? config_item('admin_panels'): array();

        /** 获取本管理员的酒店权限  */
        $hotels_hash= $this->get_hotels_hash();
        $publics = $hotels_hash['publics'];
        $hotels = $hotels_hash['hotels'];
        $filter = $hotels_hash['filter'];
        $filterH = $hotels_hash['filterH'];
        /** 获取本管理员的酒店权限  */

        //获取该公众号下的套票商品列表
        $this->load->model( 'soma/product_package_model', 'product_package' );

        //测试使用
        $cat_id = '';
        $temp_id= $this->session->get_temp_inter_id();
        if($temp_id) $inter_id= $temp_id;
        else $inter_id= $this->session->get_admin_inter_id();
        // $inter_id = $this->session->get_admin_inter_id();   //'a429262687';//

        $products_arr = $this->product_package->get_product_package_list($cat_id,$inter_id,NULL,NULL,true);
        //把套票商品转成array(product_id=>product_name)数组
        $products = array();
        foreach( $products_arr as $k => $v ){
            $products[$v['product_id']] = $v['name'];
        }

        return array(
            'act_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                // 'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                // 'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $publics,
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                // 'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $hotels,
            ),
            'banner_url' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_cat_img|100|',
                'type'=>'logo', //textarea|text|combobox|number|email|url|price
            ),
            'act_type' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> parent::act_type_status(),
            ),
            'act_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'product_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $products,
            ),
            'product_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'group_price' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> '0.01',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'price', //textarea|text|combobox|number|email|url|price
            ),
            'group_count' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' required step="1" min="1" max="2000"',
                //'form_default'=> '0',
                'form_tips'=> '一般团人数不超过100，特别热门活动可以适当调高',
                'input_unit'=> '人/次',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'number', //textarea|text|combobox|number|email|url|price
            ),
            'group_deadline' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' required step="1" min="1" max="7"',
                'input_unit'=> '天',
                // 'form_default'=> date('Y-m-d H:i:s'),
                'form_tips'=> '活动持续天数，必填项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'number', //textarea|text|combobox|number|email|url|price
            ),
            'keyword' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'start_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> date('Y-m-d H:i:s'),
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime', //textarea|text|combobox|number|email|url|price
            ),
            'end_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> date('Y-m-d H:i:s'),
                'form_tips'=> '结束时间不能大于开始时间＋7天',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime', //textarea|text|combobox|number|email|url|price
            ),
            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> date('Y-m-d H:i:s'),
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'datetime', //textarea|text|combobox|number|email|url|price
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'grid_function'=> 'show_price_prefix|￥',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $Somabase_util::get_status_options(),
            ),
        );
    }

    /**
     * grid表格中默认哪个字段排序，排序方向
     */
    public static function default_sort_field()
    {
        // return array('field'=>'', 'sort'=>'desc');
        return array('field'=>'act_id', 'sort'=>'desc');
    }

    /* 以上为AdminLTE 后台UI输出配置函数 */


    /**
     * //新增拼团（admin操作)
     * @param $productId
     * @return array
     */
    public function groupon_add($post_arr,$inter_id = NULL){
        /**
         *产品关联验证未做
         */
        $table = $this->group_table_name($inter_id);
        $fields= $this->_shard_db_r('iwide_soma_r')->list_fields($table);
        $insertArr = array();
        foreach ($post_arr as $k=>$v){
            if(in_array($k,$fields)) $insertArr[$k]= $v;
        }

        if($this->_shard_db()->insert($table,$insertArr)){
            return array(
                'status'    => self::GROUP_ADD_STATUS_SUCCESS,
                'msg'       => '添加成功'
            );
        }else{
            return array(
                'status'    => self::GROUP_ADD_STATUS_FAILED,
                'msg'       => '添加失败'
            );
        }

    }


    /**
     * @param int $gpId //具体拼团ID
     * @return mixed //拼团详细
     */
    public function groupon_detail($actId,$inter_id = NULL){
        $actId = intval($actId);
        if(empty($actId)){
            return null;
        }
        $table = $this->table_name($inter_id);
        $filter = array(
            'act_id' => $actId
        );
        $result = $this->_shard_db_r('iwide_soma_r')->where($filter)->get($table)->row_array();
        return $result;
    }

    /**
     * @param $productIds Array套票ids
     * @param null $inter_id
     * @return array
     */
    public function groupon_list_by_productIds($productIds,$inter_id = NULL){
        if(!is_array($productIds) || empty($productIds)) return array();
        // $table = $this->table_name($inter_id);
        $table = $this->table_name_r($inter_id);
        $nowDateTime = date('Y-m-d H:i:s',time());

        $result = $this->_shard_db_r('iwide_soma_r')
            ->where('status',self::STATUS_TRUE)
            ->where('start_time <',$nowDateTime)
            ->where('end_time >',$nowDateTime)
            ->where_in('product_id',$productIds)
            ->order_by ( 'create_time desc' )
            ->get($table)
            ->result_array();

        return $result;


    }

    /**
     * //获取产品团购列表
     * @param $productId
     * @return mixed
     */
    public function groupon_list($productId,$inter_id = NULL){
        $productId = intval($productId);
        if(empty($productId)){
            return null;
        }

        // $table = $this->table_name($inter_id);
        $table = $this->table_name_r($inter_id);

        $filter = array(
            'product_id' => $productId,
            'status' => parent::STATUS_TRUE
        );

        $nowDateTime = date('Y-m-d H:i:s',time());

        $result = $this->_shard_db_r( 'iwide_soma_r' )
                    ->where($filter)
                    ->where('start_time <',$nowDateTime)
                    ->where('end_time >',$nowDateTime)
                    ->order_by ( 'create_time desc' )
                    ->get($table)
                    ->result_array();

        return $result;
    }

    /*************************************** user list ********************************************************/

    /**
     * //获取用户参团信息
     * @param $openid
     * @param null $status
     * @return mixed
     */
    public function my_groupon_order( $filter = array() ,$inter_id = NULL , $offset=null, $limit = 20){
        $db = $this->_shard_db_r('iwide_soma_r');
        $table = $this->groupon_user_table_name($inter_id);
        if($limit) $db->limit($limit, $offset);
        $result = $db->where($filter)->get($table)->result_array();
        return $result;
    }


    /*************************************** order *************************************************************/


    /**
     * @param $actId
     * @param $openid
     * @param null $inter_id
     * @return array|int
     */
    public function add_groupon_group($actId, $openid, $inter_id = NULL){

        $errMsg = array(
            'status'    => self::GROUP_ADD_STATUS_FAILED,
            'msg'       => '开团失败'
        );

        $table = $this->group_table_name($inter_id);

        $grouponInfo = $this->groupon_detail($actId);
        if(empty($grouponInfo)){
            return $errMsg;
        }

        $nowTime =  date( "Y-m-d H:i:s" ,time());
        $deadline = date( "Y-m-d H:i:s" ,time() + $grouponInfo['group_deadline'] *3600*24);
        //组装数据
        $groupInsertArr = array(
            'act_id'    => $actId,
            'inter_id'  => $grouponInfo['inter_id'],
            'hotel_id'  => $grouponInfo['hotel_id'],
            'create_openid' => $openid,
            'create_time'   => $nowTime,
            'deadline'  => $deadline,
            'join_count'    =>  1,
            'status'    => self::GROUP_STATUS_WAITING_PAY
        );
        $fields= $this->_shard_db_r('iwide_soma_r')->list_fields($table);
        $insertArr = array();
        foreach ($groupInsertArr as $k=>$v){
            if(in_array($k,$fields)) $insertArr[$k]= $v;
        }
       $rs =  $this->_shard_db()->insert($table,$insertArr);
        if($rs){
            return $this->_shard_db( $inter_id )->insert_id();
        }


    }

    /**
     * //新增开团用户（首个开团用户）
     * @param $groupId
     * @param $userInfo
     * @return mixed
     */
    public function groupon_user_add($groupId,$userInfo,$inter_id = NULL){
        $table = $this->groupon_user_table_name($inter_id);
        $userInfo['group_id'] = $groupId;
        $fields= $this->_shard_db_r('iwide_soma_r')->list_fields($table);
        $insertArr = array();
        foreach ($userInfo as $k=>$v){
            if(in_array($k,$fields)) $insertArr[$k]= $v;
        }
        $rs = $this->_shard_db()->insert($table, $insertArr);

        return $rs;
    }

    /**
     * //更改拼团单用户状态
     * @param $groupId  //group_id
     * @param $openid   //openid
     * @param $status //要更改的状态
     * @param $filterArr //更改的where条件数组
     */
    public function groupon_user_update($groupId,$orderId,$openid,$status,$filterArr = null,$inter_id = NULL ){

        if(empty($filterArr)){
            $filterArr =  array(
                'group_id'  => $groupId,
                'order_id'  => $orderId,
                'openid'    => $openid
            );
        }else{
            $filterArr = $filterArr;
        }
        $table = $this->groupon_user_table_name($inter_id);
        $this->_shard_db($inter_id)->where($filterArr);
        $rs = $this->_shard_db($inter_id)->update($table, array(
            'status'=> $status
        ));
        return $rs;
    }


    /**
     * //更改拼团订单状态
     * @param $groupId //团单id
     * @param $status //更改的目标status
     */
    public function update_groupon_group($groupId,$status,$inter_id = NULL){

        $this->_shard_db($inter_id)->where(array(
            'group_id'  => $groupId
        ));
        $table = $this->group_table_name($inter_id);
        return $this->_shard_db($inter_id)->update($table, array(
            'status'=> $status
        ));

    }


    /**
     * @param $groupArr[]具体团单数组
     * @param $users[]成功参团用户
     * @return int
     */
    public function groupon_status_check($groupArr , $users){
        $groupon_detail = $this->groupon_detail($groupArr['act_id'],$groupArr['inter_id']);
        if( $groupon_detail['group_count'] == count($users)
                && $groupArr['status'] == self::GROUP_STATUS_ING ){
            return self::GROUP_STATUS_FINISHED;
        }else{
            return $groupArr['status'];
        }

    }





    /**
     * //用户参团
     * @param $groupId //团单id
     * @param $post     //用户基本信息
     */
    public  function join_groupon_group($post,$inter_id=NULL){

        $table = $this->groupon_user_table_name($inter_id);

        $fields= $this->_shard_db_r('iwide_soma_r')->list_fields($table);

        $insertArr = array();
        foreach ($post as $k=>$v){
            if(in_array($k,$fields)) $insertArr[$k]= $v;
        }
        $result= $this->_shard_db()->insert($table, $insertArr);
        return $result;

    }


    /**
     * //具体团单明细
     * @param $groupId
     * @return array
     */
    public function groupon_group_detail($groupId , $inter_id = NULL )
    {
        $groupId = intval($groupId);
        if (empty($groupId)) {
            return null;
        }
        $table = $this->group_table_name($inter_id);

        if($inter_id){
            $filter = array(
                'group_id' => $groupId,
                'inter_id'  => $inter_id
            );
        }else{
            $filter = array(
                'group_id' => $groupId
            );
        }

        $result = $this->_shard_db()->where($filter)->get($table)->row_array();

        return $result;

    }


    /**
     * //更新参团人数
     * @param $groupId
     * @param string $type //type: join参加拼团；release 支付逾时释放
     */
    public function update_groupon_group_join($groupId,$type='join',$inter_id = NULL){

        $table = $this->_shard_db( $inter_id )->dbprefix($this->group_table_name($inter_id));

        $this->groupon_group_detail($groupId,$inter_id);

        //参加拼团参加人数加1
        if($type == 'join'){
            $sql= "update `{$table}`
                set join_count=(join_count+1)
                where group_id = {$groupId}";
        }else if($type == 'release'){
            $sql= "update `{$table}`
                set join_count=(join_count - 1 )
                where group_id = {$groupId}";
        }

       return $this->_shard_db($inter_id)->query($sql);



    }



    /**
     * //获取具体拼团所有已参加用户
     * @param $groupId
     * @param null $status
     * @param null $inter_id
     * @return null
     */
    public function groupon_group_users($groupId, $status = null,$inter_id = NULL){

        $groupId = intval($groupId);

        if (empty($groupId)) {
            return null;
        }
        $table = $this->groupon_user_table_name($inter_id);
        if(empty($status)){
            $filter = array(
                'group_id' => $groupId
            );
        }else{
            $filter = array(
                'group_id'  => $groupId,
                'status'    => $status
            );
        }

        $result = $this->_shard_db_r('iwide_soma_r')->where($filter)->get($table)->result_array();
        return $result;

    }


    /**
     * //通过order_id获取user信息
     * @param $log_data
     * @return mixed
     */
    public function get_users_by_order_id($orderId,$inter_id = NULL){
        $table = $this->groupon_user_table_name($inter_id);
        $filter = array(
            'order_id'  => $orderId
        );
        $result = $this->_shard_db_r('iwide_soma_r')->where($filter)->get($table)->row_array();
        return $result;

    }


    /**
     * //通过order_id获取groupon信息
     * @param $log_data
     * @return mixed
     */
    public function get_groupon_by_order_id($order_id,$inter_id){
        $user_table = $this->groupon_user_table_name($inter_id);
        $groupon_table = $this->group_table_name($inter_id);

        $db = $this->_shard_db_r('iwide_soma_r');
        $db->select('u.order_id,g.*');
        $db->from("{$user_table} as u");
        $db->join("{$groupon_table} as g", 'g.group_id = u.group_id',"right");
        $db->where("u.order_id = {$order_id}");
        $query = $db->get();
        return $query->row_array();
    }


    /**
     * //通过order_id获取groupon group_id信息
     * @param $log_data
     * @return mixed
     */
    public function get_group_id_by_order_id($order_id,$inter_id){
        $table = $this->groupon_user_table_name($inter_id);
        $db = $this->_shard_db_r('iwide_soma_r');
        $db->select('group_id');
        $db->where("order_id = {$order_id}");
        $query = $db->get($table);
        $result =  $query->row_array();
        return $groupId = $result['group_id'];
    }


    /**
     * @param $inter_id
     * @param array $filter
     * @param int $seconds
     * @return mixed
     */
    public function get_unavailable_group_user($inter_id,$filter = array(),$seconds = 480){
        $expirationTime = time()-$seconds;
        $filter['status'] = self::GROUP_ADD_STATUS_WAITING_PAY;
        $filter['join_time <']  = $expirationTime;
        $table = $this->groupon_user_table_name($inter_id);

        $db = $this->_shard_db_r('iwide_soma_r');
        $db->where($filter);
        $query = $db->get($table);
        return $query->result_array();

    }


    /**
     * @param $inter_id
     * @param array $filter
     * @param $SalesRefundModel
     * @param string $business
     * @param int $close_order_seconds
     * @return array
     */
    public function set_unavailable_group_user($inter_id ,$filter = array(),$SalesRefundModel, $business = 'package' , $close_order_seconds = 480){
        $table = $this->groupon_user_table_name($inter_id);
        $order_expiration_time = date("Y-m-d H:i:s" ,time()- $close_order_seconds);
        $filter['status'] = self::GROUP_ADD_STATUS_WAITING_PAY;
        $filter['join_time <']  = $order_expiration_time;

        $db = $this->_shard_db_r('iwide_soma_r');
        $db->where($filter);
        $query = $db->get($table);
        $closeOrderArr =  $query->result_array();

        $closeOrderRs = array();
        if(!empty($closeOrderArr)){
            foreach($closeOrderArr as $v){
                $closeOrderRs[$v['order_id']]['status'] = $SalesRefundModel->wx_order_close( $v['order_id'], $business, $inter_id ); //关闭订单
                $closeOrderRs[$v['order_id']]['user'] = $v;
            }
        }
        if(!empty($closeOrderRs)){
            foreach($closeOrderRs as $key => $v2){
                if($v2['status']){
                    $groupId = $v2['user']['group_id'];
                    $openid = $v2['user']['openid'];
                    $status = self::GROUP_ADD_STATUS_EXPIRATION;
                    $this->update_groupon_group_join($groupId,'release',$inter_id);
                    $this->groupon_user_update($groupId,$key,$openid,$status, null,$inter_id );
                }
            }
        }
        return $closeOrderRs;
    }

    /**
     * 获取失效拼团
     * @param $inter_id
     * @return array
     */
    public function get_unavailable_groupon($inter_id,$limit = 100, $offset = 0 , $orderBy = 'deadline ASC'){
        $table = $this->group_table_name($inter_id);
        $expiration_time = date("Y-m-d H:i:s" ,time());
        $filter = array(
             'deadline <' => $expiration_time,
            'status'    => self::GROUP_STATUS_ING
        );

        $db = $this->_shard_db_r('iwide_soma_r');
        $db->where($filter);
        $query = $db
                ->limit($limit,$offset)
                ->order_by($orderBy)
                ->get($table);
        $expiration_groupon =  $query->result_array();
        return $expiration_groupon;
    }

    /**
     * *把失效拼团与用户数据复制到待处理的表
     * @param $group
     * @param $inter_id
     */
    public function move_unavailable_groupon($group,$inter_id){
        $this->load->model('soma/Reward_benefit_model','RewardBenefitModel');

        $refund_group_table = $this->_shard_db($inter_id)->dbprefix('soma_activity_groupon_group_refund');
        $refund_user_table  = $this->_shard_db($inter_id)->dbprefix('soma_activity_groupon_user_refund');
        $groupRs = $this->_shard_db($inter_id)->insert($refund_group_table, $group);
        if($groupRs){
            $this->update_groupon_group($group['group_id'],self::GROUP_STATUS_FAILED,$inter_id);
            $users = $this->groupon_group_users($group['group_id'], self::GROUP_ADD_STATUS_SUCCESS,$inter_id);
            if(!empty($users)){
                foreach($users as $user){
                    $user['inter_id'] = $group['inter_id'];
                    $this->_shard_db($inter_id)->insert($refund_user_table, $user);
                    /*待取消业绩发放*/
                    if($user['openid'] == $group['create_openid'] ){

                        $cancelList[] = array(
                            'inter_id'  => $user['inter_id'],
                            'openid' => $user['openid'],
                            'order_id'  => $user['order_id'],
                        );

//                        $bonusHeader  = '取消分销业绩计算写入: \n'.$group['group_id']."\n"."inter : " .  $user['inter_id']."\n";
//                        $RewardBenefitModel = $this->RewardBenefitModel;
//                        $this->load->model('soma/Sales_order_model','SalesOrderModel');
//                        $OrderModel = $this->SalesOrderModel->load( $user['order_id'] );
//
//                        $bonusFlag = $RewardBenefitModel->modify_benefit_queue_refund(  $group['inter_id'], $OrderModel );
//                        if($bonusFlag)
//                            write_log($bonusHeader." Success");
//                        else
//                            write_log($bonusHeader." Failed");
                    }
                    /*取消业绩发放 end*/
                }
            }
            $result = array(
                'groupId' => $group['group_id']  ,
                'users'   => $users,
                'flag'      => true,
                'cancelList' => $cancelList
            );
        }else{
            $result = array(
                'groupId' => $group['group_id']  ,
                'users'   => 'NONE',
                'flag'      => false,
                'cancelList'  => array()
            );
        }
        return $result;
    }

    /**
     * 拼团待退款用户获取
     * @param int $limit
     * @param int $offset
     * @param string $orderBy
     * @return mixed
     */
    public function refund_users($limit = 100, $offset = 0 , $orderBy = 'join_time ASC'){
        $this->load->helper('soma/package');

        $refund_user_table  = $this->_shard_db()->dbprefix('soma_activity_groupon_user_refund');
        $filter = array(
            'status'    => self::GROUP_USER_VALIDATE
        );

        $db = $this->_shard_db_r('iwide_soma_r');
        $db->where($filter);
        $query = $db
            ->limit($limit,$offset)
            ->order_by($orderBy)
            ->get($refund_user_table);
        $userRs = $query->result_array();
        return $userRs;
    }

    /**
     * *拼团退款执行
     * @param $SalesOrderModel
     * @param $SalesRefundModel
     * @param string $business
     * @param int $limit
     * @param int $offset
     * @param string $orderBy
     */
    public function refund_exc($SalesOrderModel,$SalesRefundModel,$user,$business = 'package'){
        $this->load->helper('soma/package');

        $strHeader = "Refund order id is : ".$user['order_id']."\n";
        $refundState = $SalesRefundModel->groupon_fail( $user['order_id'], $business, $user['inter_id'] ); //发送退款请求
        write_log('退款申请成功，开始更改订单状态：\n');
        if($refundState){
            $strHeader.= 'Refund Success.\n';
            $this->groupon_user_update($user['group_id'],$user['order_id'],$user['openid'],self::GROUP_USER_REFUND,NULL,$user['inter_id']); //更改用户状态变成申请退款
            //更改退款表的状态
            $refund_user_table  = $this->_shard_db($user['inter_id'])->dbprefix('soma_activity_groupon_user_refund');
            $refundUserTable = $this->_shard_db()
                ->update($refund_user_table,array('status' =>self::GROUP_USER_REFUND ) ,array('order_id'=>$user['order_id'] ));
            if($refundUserTable)
                $refundUserTableStr = 'User refund table update Success.\n';
            else
                $refundUserTableStr = 'User refund table update Failed.\n';
            write_log($refundUserTableStr);
//            $RefundModel = $SalesOrderModel->load($user['order_id']);
//            $changeStatus = array(
//                'refund_status' => $SalesOrderModel::REFUND_ALL
//            ) ;
//            $RefundModel->refund = $changeStatus;
//            $RefundModel->order_refund_status($business,$user['inter_id']);//更改订单状态为退款
        }else{
            $strHeader.= 'Refund Failed.\n';
        }
        write_log($strHeader.json_encode($user));


    }


    /**
     * 获取失效拼团
     * @param $inter_id
     * @return array
     */
    public function get_unavailable_groupon_failed($inter_id,$limit = 100, $offset = 0 , $orderBy = 'deadline ASC'){
        $table = $this->group_table_name($inter_id);
        $expiration_time = date("Y-m-d H:i:s" ,time());
        $filter = array(
            'deadline <' => $expiration_time,
            'status'    => self::GROUP_STATUS_FAILED
        );

        $db = $this->_shard_db_r('iwide_soma_r');
        $db->where($filter);
        $query = $db
            ->limit($limit,$offset)
            ->order_by($orderBy)
            ->get($table);
        $expiration_groupon =  $query->result_array();
        return $expiration_groupon;
    }

    /**
     * 拼团失效检索并改为失效状态、用户退款、订单变为退款
     * @param array $grouponArr
     * @param $SalesRefundModel
     * @param string $business
     * @return array|null
     */
    public function set_unavailable_groupon($grouponArr=array(),$SalesOrderModel,$SalesRefundModel,$business = 'package'){
        if(!is_array($grouponArr) && empty($grouponArr))
            return NULL;

        $result = array();
        $this->load->helper('soma/package');

        write_log('Soma unavailable Group Start: '. json_encode($grouponArr));
        foreach($grouponArr as $k => $v){
            $result[$k]['groupUpdate'] = $groupState =  $this->update_groupon_group($v['group_id'],self::GROUP_STATUS_FAILED,$v['inter_id']);//更改团单状态变成失效
            $result[$k]['group_id'] = $v['group_id']; //记录退款单
            if($groupState){
                $groupUsers = $this->groupon_group_users($v['group_id'], self::GROUP_ADD_STATUS_SUCCESS,$v['inter_id']); //该订单下成功支付的
                $result[$k]['groupUsers'] = $groupUsers; //记录退款用户
                foreach($groupUsers as $user){
                    $refundState = $SalesRefundModel->wx_refund_send( $user['order_id'], $business, $v['inter_id'] ); //发送退款请求
                    $result[$k]['orders'][ $user['order_id']] = $refundState; //每位用户的退款记录
                    if($refundState){
                        $result[$k]['userUpdate'] = $this->groupon_user_update($v['group_id'],$user['order_id'],$user['openid'],self::GROUP_USER_REFUND,NULL,$v['inter_id']); //更改用户状态变成申请退款
                        $RefundModel = $SalesOrderModel->load($user['order_id']);
                        $changeStatus = array(
                            'refund_status' => $SalesOrderModel::REFUND_ALL
                        ) ;
                        $RefundModel->refund = $changeStatus;
                        $result[$k]['orderupdate']= $RefundModel->order_refund_status($business,$v['inter_id']);//更改订单状态为退款
                    }
                }
            }

        }

        return $result;
    }

    /*************************************** order  /end *************************************************************/




/*****************************lu添加活动后台start********************************/
    /**
     * 添加一个活动(注：合并到父类里)
     * @author luguihong
     * @deprecated
    */
    public function activity_save( $post, $inter_id=NULL )
    {

        try {

            $this->_shard_db($inter_id)->trans_begin ();

            $product_id = isset( $post['product_id'] ) ? $post['product_id'] : '';
            $act_name = isset( $post['act_name'] ) ? $post['act_name'] : '';
            $status = $post['status'];

            //添加活动主单内容
            //添加活动类型和活动名称到activity_idx表
            $data = array();
            $data['act_name'] = $act_name;
            $data['act_type'] = $post['act_type'];
            $data['status'] = $status;

            $table = $this->_shard_db( $inter_id )->dbprefix('soma_activity_idx');
            $this->_shard_db( $inter_id )->insert( $table, $data );
            $act_id = $this->_shard_db( $inter_id )->insert_id();

            //添加活动价格表内容
            $data = array();
            $data['act_id'] = $act_id;
            $data['act_name'] = $act_name;
            $data['product_id'] = $product_id;
            $data['price'] = $post['group_price'];

            $table = $this->_shard_db( $inter_id )->dbprefix('soma_activity_product_price');
            $this->_shard_db( $inter_id )->insert( $table, $data );
            $this->_shard_db( $inter_id )->insert_id();

            //添加团购内容
            $this->load->model( 'soma/product_package_model', 'product_package' );
            $product_info = $this->product_package->get_product_package_detail_by_product_id( $product_id, $inter_id );
            if( $product_info ){
                $product_name = $product_info['name'];
            }else{
                $product_name = '';
            }

            //组装添加团购活动内容
            $da = array(
                'act_id'=> $act_id,
                'inter_id'=> $post['inter_id'],
                'hotel_id'=> $post['hotel_id'],
                'banner_url'=> isset( $post['banner_url'] ) ? $post['banner_url'] : '',
                'act_name'=> $act_name,
                'product_id'=> $product_id,
                'product_name'=> $product_name,
                'group_price'=> $post['group_price'],
                'group_count'=> $post['group_count'],
                'group_deadline'=> $post['group_deadline'],
                'keyword'=> $post['keyword'],
                'start_time'=> $post['start_time'],
                'end_time'=> $post['end_time'],
                'create_time'=> date( 'Y-m-d H:i:s', time() ),
                'status'=> $status,
            );

            //保存团购内容
            $result = $this->_m_save($da);
            
            $this->_shard_db($inter_id)->trans_complete();
            
            if ($this->_shard_db($inter_id)->trans_status() === FALSE) {
                $this->_shard_db($inter_id)->trans_rollback();
                return FALSE;
            
            } else {
                $this->_shard_db($inter_id)->trans_commit();
                return TRUE;
            }
            
        } catch (Exception $e) {
             
            return FALSE;
        }
    }
    
    /**
     * 修改一个活动(注：合并到父类里)
     * @author luguihong
     * @deprecated
    */
    public function activity_edit( $post, $inter_id=NULL )
    {
    
        try {
    
            $this->_shard_db($inter_id)->trans_begin ();
            
            $pk = $this->table_primary_key();
            
            $product_id = isset( $post['product_id'] ) ? $post['product_id'] : '';
            $act_name = isset( $post['act_name'] ) ? $post['act_name'] : '';
            $status = isset( $post['status'] ) ? $post['status'] : '';
            $pk_v = $post[$pk];
    
            //添加活动主单内容
            //添加活动类型和活动名称到activity_idx表
            $data = array();
            $data['act_name'] = $act_name;
            $data['act_type'] = $post['act_type'];//'团购';//暂时都是团购 1.团购  2.秒杀 3...
            $data['status'] = $status;
            
            $where = array();
            $where[$pk] = $pk_v;
    
            $table = $this->_shard_db( $inter_id )->dbprefix('soma_activity_idx');
            $this->_shard_db( $inter_id )->where( $where )->update( $table, $data );
    
            //添加活动价格表内容
            $data = array();
            $data['act_name'] = $act_name;
            $data['product_id'] = $product_id;
            $data['price'] = $post['group_price'];
            
            $where = array();
            $where[$pk] = $pk_v;
    
            $table = $this->_shard_db( $inter_id )->dbprefix('soma_activity_product_price');
            $this->_shard_db( $inter_id )->where( $where )->update( $table, $data );
    
            //添加团购内容
            // $this->load->model( 'soma/product_package_model', 'product_package' );
            // $product_info = $this->product_package->get_product_package_detail_by_product_id( $product_id, $inter_id );
            // if( $product_info ){
            //     $post['product_name'] = $product_info['name'];
            // }else{
            //     $post['product_name'] = '';
            // }
//     var_dump( $post, $pk_v );exit;
            //保存团购内容
            $result = $this->load( $pk_v )->m_sets( $this->product )->m_save();
    
            $this->_shard_db($inter_id)->trans_complete();
    
            if ($this->_shard_db($inter_id)->trans_status() === FALSE) {
                $this->_shard_db($inter_id)->trans_rollback();
                return FALSE;
    
            } else {
                $this->_shard_db($inter_id)->trans_commit();
                return TRUE;
            }
    
        } catch (Exception $e) {
             
            return FALSE;
        }
    }
/*****************************lu添加活动后台end**********************************/

}
