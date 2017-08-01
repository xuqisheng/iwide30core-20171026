<?php

/**
 * 微信模板消息管理和发送
 * User: liwensong
 * Date: 2017/3/30
 * Time: 上午11:03
 */
class Wxtemp_model extends MY_Model_Member {

    const SEND_URL = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=';
    const SEND_TEMP_SUCCESS = 't'; //发送成功
    const SEND_TEMP_FAIL = 'f'; //发送失败
    const UNSEND_TEMP = 'n'; //未发送
    const PACKAGE_EXPIRE = 1; //优惠劵过期
    const AUDIT_RESULTS = 2; //会员审核
    const CREDITED_NOTICE = 3; //优惠劵到账通知
    const SENDCARD_NOTICE = 4; //优惠劵发放通知
    const REDSENDCARD_NOTICE = 5; //注册送礼包通知
    const SEND_INVITATE_NOTICE = 6; //邀请好友通知
    const SEND_CREDIT_NOTICE = 7; //积分兑换通知
    const SEND_ACTION_NOTICE = 8; //活动开始提醒
    const SEND_RECOMMEND_NOTICE = 9; //推荐成功通知
    const SEND_VERIFY_LVL_UP = 10; //审核通过，等级变更
    const SEND_VERIFY_UNPASS = 11; //会员资料审核不通过通知
    const SEND_VERIFY_REG_PASS = 12; //会员注册成功通知
    const SEND_VERIFY  = 13; //会员资料审核通知

    const TEMPLATE_FIRST = 'first';//模版内容的头部
    const TEMPLATE_REMARK = 'remark';//模版内容的尾部

    public function __construct(){
        parent::__construct();
        $this->load->library("MYLOG");
    }

    protected function redis_setting(){
        if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
            $config = array(
                'task'=> array(
                    'socket_type'   => 'tcp',
                    'password'      => NULL,
                    'timeout'       => 5,
                    'cachedb'       => 14,
                    'host'          => 'redis02',
                    'port'          => 6381
                ),
            );
        } else {
            $config = array(
                'task'=> array(
                    'socket_type'   => 'tcp',
                    'password'      => NULL,
                    'timeout'       => 5,
                    'cachedb'       => 2,
                    'host'          => '120.27.132.97',
                    'port'          => 16379
                ),
            );
        }
        return $config;
    }

    protected function get_vip_redis($select = 'task') {
        $redis_config = $this->redis_setting();
        $config = $redis_config[$select];
        if(!is_array($config)) {
            return false;
        }
        $redis = new Redis();
        $success = $redis->connect($config['host'], $config['port'], $config['timeout']);
        if(!$success) {
            return false;
        }
        return $redis;
    }

    /**
     * 获取模版字段映射到表字段
     * @param string $kind 类型
     * @return bool|mixed
     */
    public function get_template_field_mapping($kind = ''){
        if(empty($kind)) return false;
        $mapping = array(
            //优惠劵过期
            self::PACKAGE_EXPIRE => array(
                'packageName'=>array('key'=>'name','field'=>'name'),//商品名称
                'packageExpDate'=>array('key'=>'expDate','field'=>'expiration_date'),//过期时间
            ),
            //会员审核
            self::AUDIT_RESULTS => array(
                'username'=>array('key'=>'keyword1','field'=>'name'),//姓名
                'usertel'=>array('key'=>'keyword2','field'=>'telephone'),//手机号
                'audittime'=>array('key'=>'keyword3','field'=>'audittime'),//审核时间
            ),
            //优惠劵到账通知
            self::CREDITED_NOTICE => array(
                'account'=>array('key'=>'keyword1','field'=>'name'),//账户名称
                'number'=>array('key'=>'keyword2','field'=>'count'),//数量
                'curtime'=>array('key'=>'keyword3','field'=>'curtime'),//时间
            ),
            //优惠劵发放通知
            self::SENDCARD_NOTICE => array(
                'account'=>array('key'=>'keyword1','field'=>'name'),//账户名称
                'number'=>array('key'=>'keyword2','field'=>'count'),//数量
                'curtime'=>array('key'=>'keyword3','field'=>'curtime'),//时间
            ),
            //注册送礼包通知
            self::REDSENDCARD_NOTICE => array(
                'account'=>array('key'=>'keyword1','field'=>'name'),//账户名称
                'number'=>array('key'=>'keyword2','field'=>'count'),//数量
                'curtime'=>array('key'=>'keyword3','field'=>'curtime'),//时间
            ),
            //邀请好友通知
            self::SEND_INVITATE_NOTICE => array(
                'service_name'=>array('key'=>'keyword1','field'=>'service_name'),//服务名称
                'service_progress'=>array('key'=>'keyword2','field'=>'service_progress'),//服务进度
            ),
            //积分兑换通知
            self::SEND_CREDIT_NOTICE => array(
                'title_key1'=>array('key'=>'FieldName','field'=>'name'),//标题一
                'title_key2'=>array('key'=>'change','field'=>'change'),//标题二
                'data_key1'=>array('key'=>'Account','field'=>'account'),//内容一
                'data_key2'=>array('key'=>'CreditChange','field'=>'creditchange'),//内容二
                'data_key3'=>array('key'=>'CreditTotal','field'=>'credittotal'),//内容三
            ),
            //会员注册成功通知
            self::SEND_VERIFY_REG_PASS => array(
                'usernum'=>array('key'=>'keyword1','field'=>'usernum'),//会员号
                'username'=>array('key'=>'keyword2','field'=>'username'),//会员名
                'usertel'=>array('key'=>'keyword3','field'=>'usertel'),//手机号
            )
        );
        return !empty($mapping[$kind])?$mapping[$kind]:false;
    }

    /**
     * 通过模板类型获取模板消息内容
     * @param string $inter_id 酒店集团ID
     * @param string $type 模板消息类型
     * @param bool $single 返回数据行数 [ture: 单条，false: 多条]
     * @param string $field 返回字段
     * @param mixed $parse 附加检索条件
     * @return bool
     */
    public function get_template($inter_id = '', $type = '', $single = true, $field = '*', $parse = null){
        if(empty($inter_id) OR empty($type)) return false;
        $filter = [
            'inter_id'=>$inter_id,
            'type'=>$type,
            'status'=>1
        ];
        if(!empty($parse) && !is_string($parse)){
            if(is_object($parse)){
                $parse = get_object_vars($parse);
            }
            $filter = array_merge($filter,$parse);
        }
        if($single){
            $result = $this->_shard_db()->select($field)->where($filter)->get('member_message_template')->row_array();
        }else{
            $result = $this->_shard_db()->select($field)->where($filter)->get('member_message_template')->result_array();
        }
        $sql = $this->_shard_db()->last_query();
        MYLOG::w("get_template | ".@json_encode(array('result'=>$result,'where'=>$filter,'sql'=>$sql)),'membervip/debug-log','wxtemp_model');
        if($this->input->get('debug')==1){
            echo $sql."<br/>";
        }
        return $result;
    }

    public function get_template_by_type($type = '', $single = true, $field = '*', $parse = null){
        if(empty($type)) return false;
        $filter = [
            'type'=>$type,
            'status'=>1
        ];
        if(!empty($parse) && !is_string($parse)){
            if(is_object($parse)){
                $parse = get_object_vars($parse);
            }
            $filter = array_merge($filter,$parse);
        }
        if($single){
            $result = $this->_shard_db()->select($field)->where($filter)->get('member_message_template')->row_array();
        }else{
            $result = $this->_shard_db()->select($field)->where($filter)->get('member_message_template')->result_array();
        }
        $sql = $this->_shard_db()->last_query();
        MYLOG::w("get_template_by_type | ".@json_encode(array('result'=>$result,'where'=>$filter,'sql'=>$sql)),'membervip/debug-log','wxtemp_model');
        if($this->input->get('debug')==1){
            echo $sql."<br/>";
        }
        return $result;
    }

    /**
     * 获取消息队列
     * @param string $inter_id 酒店集团ID
     * @param string $type 消息类型
     * @param int $offset 检索开始行
     * @param int $limit 获取行数
     * @param bool $single 是否返回单条
     * @param string $field 获取字段
     * @param null $parse 附加条件
     * @return bool|array
     */
    public function get_message_queue($inter_id = '', $type = '', $offset = 0, $limit = 100, $single = false, $field = '*', $parse = null){
        if(empty($inter_id) OR empty($type)) return false;
        $filter = [
            'inter_id'=>$inter_id,
            'message_type'=>$type,
            'is_success'=>self::UNSEND_TEMP,
            'expiretime >='=>time()
        ];
        if(!empty($parse) && !is_string($parse)){
            if(is_object($parse)){
                $parse = get_object_vars($parse);
            }
            $filter = array_merge($filter,$parse);
        }
        if($single){
            $result = $this->_shard_db()->select($field)->where($filter)->get('template_message_queue')->row_array();
        }else{
            $result = $this->_shard_db()->select($field)->where($filter)->group_by('openid')->limit($limit,$offset)->get('template_message_queue')->result_array();
        }
        $sql = $this->_shard_db()->last_query();
        MYLOG::w(@json_encode(array('result'=>$result,'where'=>$filter,'sql'=>$sql)),'membervip/debug_log','wxtemp_model');
        if($this->input->get('debug')==1){
            echo $sql."<br/>";
        }
        return $result;
    }

    /**
     * 添加消息队列
     * @param array $data 保存到队列的数据
     * @param string $content 消息内容数据
     * @return bool|string
     */
    public function create_message_queue($data = array(), $content = ''){
        if(empty($data) OR empty($content)) return false;
        $map_keys = array(
            'inter_id',
            'openid',
            'business_model',
            'message_type',
        );

        $warning = '';
        $save_data = array(
            'content'=>$content,
            'expiretime'=>strtotime("+1 day")
        );
        foreach ($map_keys as $v){ //检查保存的数据
            if(empty($data[$v])){
                $warning = "参数 '{$data[$v]}' 缺失，或者为空";break;
            }
            $save_data[$v] = $data[$v];
        }
        if(!empty($warning)) return $warning;
        $save_data['createtime'] = time();
        $save_data['lastupdatetime'] = date('Y-m-d H:i:s');
        $save_data['is_success'] = self::UNSEND_TEMP;
        $result = $this->_shard_db(true)->set($save_data)->insert('template_message_queue');
        $sql = $this->_shard_db(true)->last_query();
        MYLOG::w(@json_encode(array('result'=>$result,'save_data'=>$save_data,'sql'=>$sql)),'membervip/debug_log','wxtemp_model');
        if($this->input->get('debug')==1){
            echo $sql."<br/>";
        }
        if($result){
            return $this->_shard_db(true)->insert_id();
        }
        return $result;
    }

    /**
     * 组装发送模版内容
     * @param array $message_queue 单条队列消息
     * @param array $template 模板内容数据
     * @param null $parse 附加数据
     * @return array|bool
     */
    public function create_template_message($message_queue = array(), $template = array(), $parse = null){
        if(empty($message_queue) OR empty($template)) return false;

        if(empty($template['content']) OR empty($message_queue['openid']) OR empty($message_queue['inter_id'])) return false;
        $message = array();//模版消息
        $type = $template['type']; //模版类型
        $template_id = $template['template_id']; //微信模版ID

        //模版内容
        $content = @unserialize( base64_decode( $template['content'] ) );
        if( !$content ){
            $content = unserialize( $template['content'] );//Message: unserialize(): Error at offset 0 of 258 bytes
        }

        $message['touser'] = $message_queue['openid'];//发送给哪个用户
        $message['template_id'] = $template_id;//微信模版ID

        $inter_id = $message_queue['inter_id'];

        //链接处理
        $url = '';
        $param = array('id'=>$inter_id);
        $templateUrl = $template['link'];//需要处理链接
        if(!empty($templateUrl)){
            $exp = explode( '_', $templateUrl, 3 );
            switch ($templateUrl) {
                case 'membervip_center':
                    $url = EA_const_url::inst()->get_front_url( $inter_id, $exp[0].'/'.$exp[1],$param);
                    break;
                case 'membervip_card':
                    $url = EA_const_url::inst()->get_front_url( $inter_id, $exp[0].'/'.$exp[1],$param);
                    break;
                case 'membervip_card_cardinfo':
                    $param['member_card_id'] = isset( $parse['member_card_id'] ) ? $parse['member_card_id'] : '';
                    $url = EA_const_url::inst()->get_front_url( $inter_id, $exp[0].'/'.$exp[1].'/'.$exp[2],$param);
                    break;
                case 'membervip_reg':
                    $url = EA_const_url::inst()->get_front_url( $inter_id, $exp[0].'/'.$exp[1],$param);
                    break;
                case 'membervip_invitate':
                    $url = EA_const_url::inst()->get_front_url( $inter_id, $exp[0].'/'.$exp[1],$param);
                    break;
                default:
                    break;
            }
        }

        $message['url'] = $url;
        $message['data'] = array();//发送的内容

        //解析替换头部文本（替换用户名）
        if(!empty($parse['username'])){
            $content['first']['value'] = str_replace(array('{username}'), array($parse['username']),$content['first']['value']);
        }

        //解析替换头部文本（替换等级名称）
        if(!empty($parse['lvl_name'])){
            $content['first']['value'] = str_replace(array('{lvlname}'), array($parse['lvl_name']),$content['first']['value']);
        }

        $message['data']['first'] = array(
            'value'=>$content['first']['value'],
            'color'=>$content['first']['color'],
        );
        $message['data']['remark'] = array(
            'value'=>$content['remark']['value'],
            'color'=>$content['remark']['color'],
        );

        //获取模版字段映射
        $templateMapping = $this->get_template_field_mapping($type);

        if(empty($templateMapping)) return false;

        foreach ($templateMapping as $k => $v) {//循环字段映射
            foreach( $content as $sk=>$sv ){//循环模版内容
                if( $sv['key'] == $k){//如果模版内容的key＝字段映射的key
                    if($sv['key'] == self::TEMPLATE_FIRST ){
                        $value = $sv['value'];
                        $key = self::TEMPLATE_FIRST;
                    }elseif($sv['key'] == self::TEMPLATE_REMARK ){
                        $value = $sv['value'];
                        $key = self::TEMPLATE_REMARK;
                    }else{
                        $value = !empty($parse[$v['field']])?$parse[$v['field']]:$sv['value'];
                        $key = $v['key'];
                    }

                    //赋值
                    $message['data'][$key] = array( 'value'=>$value, 'color'=>$sv['color'] );
                }
            }
        }
        return $message;
    }

    /**
     * 通过消息队列发送模板消息
     * @param string $pattern 发送模式 【指定酒店true，反之false】
     * @param string $type 消息类型
     * @param int $offset 开始检索行 默认0
     * @param int $limit 获取行 默认100
     * @param null $parse 附加条件
     * @return string
     */
    public function send_template_message($pattern = '', $type = '', $offset = 0, $limit = 100 , $parse = null){
        if(empty($type)) {
            return $this->json_return('4001','集团酒店ID和消息类型不能为空');
        }

        if($pattern){
            $inter_id = !empty($parse['inter_id'])?$parse['inter_id']:'';
            $template = $this->get_template($inter_id,$type,true); //获取消息模板
            if(empty($template)){
                return $this->json_return('4002','集团酒店ID不能为空');
            }
            $return = $this->template_send_queue($inter_id,$template,$type,$offset,$limit,$parse);
            return $return;
        }else{
            $template = $this->get_template_by_type($type,false); //获取消息模板
            if(empty($template)){
                return $this->json_return('4002','集团酒店ID不能为空');
            }
            $return = array();
            foreach ($template as $temp){
                $inter_id = $temp['inter_id'];
                $return[] = @json_decode($this->template_send_queue($inter_id,$temp,$type,$offset,$limit,$parse),true);
            }
            return @json_encode($return);
        }
    }

    public function template_send_queue($inter_id = '',$template = array(),$type = '',$offset = 0, $limit = 100, $parse = null){
        if(empty($inter_id OR empty($template))) return $this->json_return('4001','集团酒店ID和模版不能为空');
        if(empty($type)) {
            return $this->json_return('4001','消息类型不能为空');
        }
        $scount = 0;
        $fcount = 0;
        $message_queue = $this->get_message_queue($inter_id, $type, $offset, $limit, false ,'*', $parse); //获取消息队列
        $_message_queue = array();
        $use_message_queue = array();
        $redis = $this->get_vip_redis();

        if(!empty($message_queue)){
            foreach ($message_queue as $items){
                $_message_queue[$items['openid']] = $items;
            }
            $openids = array_keys($_message_queue);
            if(!empty($openids)){
                $_where = [
                    'inter_id'=>$inter_id,
                ];
                $member_info = $this->_shard_db()->select('member_info_id,open_id,member_mode,is_login')
                    ->where($_where)->where_in('open_id',$openids)
                    ->get('member_info')->result_array();
                $_member_info = array();
                if(!empty($member_info)){
                    foreach ($member_info as $vv){
                        $_member_info[$vv['open_id']] = $vv;
                    }

                    foreach ($_message_queue as $key => $vvo){
                        if(!empty($_member_info[$key])){
                            $minfo = $_member_info[$key];
                            if($minfo['is_login']=='t'){
                                $use_message_queue[$key] = $vvo;
                            }else if($minfo['member_mode']==1){
                                $use_message_queue[$key] = $vvo;
                            }
                        }
                    }
                }else{
                    return $this->json_return('4007','找不到发送会员信息');
                }
            }else{
                return $this->json_return('4006','不存在待发通知');
            }
        }else{
            return $this->json_return('4005','不存在待发通知');
        }
        MYLOG::w("wxtemp_use_message_queue | ".@json_encode($use_message_queue),'membervip/debug-log','wxtemp_model');

        if(empty($use_message_queue)){
            return $this->json_return('4003','不存在待发通知');
        }

        $_data = array();
        $rcount = count($use_message_queue);

        foreach ($use_message_queue as $key => $item){
            $redis_test = $redis->get('wxtemp_redis_message_queue');
            if(!empty($redis_test)){
                echo "Stop the program\n";exit(0);
            }

            $data = explode('|',$item['content']);
            switch ($type){
                case self::PACKAGE_EXPIRE:
                    $_data['name'] = !empty($data[0])?$data[0]:'';
                    $_data['expiration_date'] = !empty($data[1])?date('Y年m月d日',$data[1]):'';// 过期时间
                    $_data['member_card_id'] = !empty($data[2])?$data[2]:'';
                    break;
                case self::AUDIT_RESULTS:
                    $_data['name'] = !empty($data[0])?$data[0]:''; //姓名
                    $_data['telephone'] = !empty($data[1])?$data[1]:'';// 手机号码
                    $_data['audittime'] = !empty($data[2])?date('Y年m月d日',$data[2]):'';   //审核时间
                    break;
                case self::CREDITED_NOTICE:
                case self::SENDCARD_NOTICE:
                case self::REDSENDCARD_NOTICE:
                    $_data['name'] = !empty($data[0])?$data[0]:''; //账户名称
                    $_data['count'] = !empty($data[1])?$data[1]:'';// 数量
                    $_data['curtime'] = !empty($data[2])?date('Y年m月d日',$data[2]):''; //通知时间
                    break;
                case self::SEND_INVITATE_NOTICE:
                    $_data = array('username'=>!empty($data[0])?$data[0]:'','lvl_name'=>!empty($data[1])?$data[1]:'');
                    break;
                case self::SEND_VERIFY_REG_PASS:
                    $_data = array(
                        'usernum'=>!empty($data[0])?$data[0]:'',
                        'username'=>!empty($data[1])?$data[1]:'',
                        'usertel'=>!empty($data[2])?$data[2]:'',
                        'lvl_name'=>!empty($data[3])?$data[3]:''
                    );
                    break;
                default:break;
            }
            $message = $this->create_template_message($item,$template,$_data);
            if(empty($message)){
                $this->json_return('4004','发送模板内容为空');
            }
            $_message = @json_encode($message);
            $result = $this->execute_send_template($inter_id,$_message);
            if($result['errcode']=='0'){
                $scount++;
                MYLOG::w("wxtemp_success | SUCCESS | TOTAL: {$rcount} | success_count: {$scount} | fail_count: {$fcount} |  openid: {$item['openid']}",'membervip/debug_log','wxtemp_model');
            }else{
                $fcount++;
                MYLOG::w("wxtemp_fail | FAIL | TOTAL: {$rcount} | success_count: {$scount} | fail_count: {$fcount} |  openid: {$item['openid']}",'membervip/debug_log','wxtemp_model');
            }

            $rdata = [
                'inter_id'=>$inter_id,
                'hotel_id'=>$template['hotel_id'],
                'temp_id'=>$template['temp_id'],
                'template_id'=>$template['template_id'],
                'openid'=>$item['openid'],
                'type'=>$item['message_type'],
                'msg'=>$_message,
                'result'=>@json_encode($result),
                'create_time'=>date( "Y-m-d H:i:s",time()),
                'status'=>$result['errcode']=='0'?1:2
            ];
            //保存到record
            $res = $this->_shard_db(true)->set($rdata)->insert('message_wxtemp_record');
            $sql = $this->_shard_db(true)->last_query();
            MYLOG::w("wxtemp_record | ".@json_encode(array('result'=>$res,'save_data'=>$rdata,'sql'=>$sql)),'membervip/debug-log','wxtemp_model');
            $rdata = [
                'is_success'=>$result['errcode']=='0'?self::SEND_TEMP_SUCCESS:self::SEND_TEMP_FAIL,
                'send_count'=>($item['send_count'] + 1)
            ];
            $where = [
                'id'=>$item['id']
            ];
            $res = $this->_shard_db(true)->where($where)->set($rdata)->update('template_message_queue');
            $sql = $this->_shard_db(true)->last_query();
            MYLOG::w("wxtemp_queue | ".@json_encode(array('result'=>$res,'save_data'=>$rdata,'where'=>$where,'sql'=>$sql)),'membervip/debug_log','wxtemp_model');
        }
        return $this->json_return('1001',"TOTAL: {$rcount} | success_count: {$scount} | fail_count: {$fcount}");
    }

    /**
     * 发送模版消息
     * @param $json_data
     * @return string
     */
    public function execute_send_template($inter_id = null,$json_data = array()){
        if(empty($inter_id || empty($json_data))) return array('errcode'=>'9999','errmsg'=>'缺少必要参数!');
        $this->load->model('wx/access_token_model');
        $access_token= $this->access_token_model->get_access_token($inter_id);
        $url = self::SEND_URL.$access_token;
        $this->load->helper('common_helper');
        $result = doCurlPostRequest($url,$json_data);
        MYLOG::w(@json_encode(array('result'=>$result,'json_data'=>$json_data)),'membervip/debug_log','wxtemp_template_send');
        $result_data = json_decode($result,true);
        if($result_data['errcode'] == 0 && $result_data['errmsg'] == 'ok'){
            return $result_data;
        }elseif ($result_data['errcode'] == '40001'){
            $access_token = $this->access_token_model->reflash_access_token ( $inter_id );
            $url = self::SEND_URL.$access_token;
            $result = doCurlPostRequest($url,$json_data);
            MYLOG::w(@json_encode(array('result'=>$result,'json_data'=>$json_data)),'membervip/debug_log','wxtemp_template_send');
            $result_data = json_decode($result,true);
            if($result_data['errcode'] == 0 && $result_data['errmsg'] == 'ok'){
                return $result_data;
            }elseif (isset($result_data['errcode'])){
                return $result_data;
            }
        }elseif ($result_data['errcode'] == '42001'){
            $access_token = $this->access_token_model->reflash_access_token ( $inter_id );
            $url = self::SEND_URL.$access_token;
            $result = doCurlPostRequest($url,$json_data);
            MYLOG::w(@json_encode(array('result'=>$result,'json_data'=>$json_data)),'membervip/debug_log','wxtemp_template_send');
            $result_data = json_decode($result,true);
            if($result_data['errcode'] == 0 && $result_data['errmsg'] == 'ok'){
                return $result_data;
            }elseif (isset($result_data['errcode'])){
                return $result_data;
            }
        }elseif (isset($result_data['errcode'])){
            return $result_data;
        }
        return array('errcode'=>'90001','errmsg'=>'发送失败!');
    }

    /**
     * 反馈请求信息
     * @param int $code 信息代码
     * @param null $msg 信息内容
     * @param bool $type 返回方式 true --- 直接输出,false --- return返回
     * @return string
     */
    public function json_return($code = 0,$msg = null,$type = false){
        if($type===true){
            $data['msg'] = $msg;
            $data['code'] = $code;
            echo json_encode($data);exit;
        }
        $data['msg'] = $msg;
        $data['code'] = $code;
        return json_encode($data);
    }
}