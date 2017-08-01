<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Message_wxtemp_model extends MY_Model_Member {

    const QUEUE_STATUS_BOOKING = 1;//计划任务预备发送
    const QUEUE_STATUS_SUCCESS = 2;//计划任务发送成功
    const QUEUE_STATUS_FAIL = 3;//计划任务发送失败
    const SEND_MSG_TYPE = 1;
    const SEND_URL = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=';
    const TEMPLATE_ID = 'ZiI-XPKcr_4tKU7SopJg76u3oBKNr7tl9gXoPvPrFsQ';
    const PACKAGE_EXPIRE = 1; //优惠劵过期
    const AUDIT_RESULTS = 2;
    const CREDITED_NOTICE = 3;
    const SENDCARD_NOTICE = 4;
    const REDSENDCARD_NOTICE = 5;
    const SEND_INVITATE_NOTICE = 6;
    const SEND_CREDIT_NOTICE = 7;
    const SEND_ACTION_NOTICE = 8;
    const SEND_RECOMMEND_NOTICE = 9;
    const SEND_VERIFY_LVL_UP = 10;//审核通过，等级变更
    const SEND_VERIFY_UNPASS = 11;
    const SEND_VERIFY_REG_PASS = 12;
    const SEND_VERIFY  = 13;
    const TEMPLATE_OTHER = 101;//其它

    const TEMPLATE_FIRST = 'first';//模版内容的头部
    const TEMPLATE_REMARK = 'remark';//模版内容的尾部

    protected $template_id,$type,$username; //模版ID、消息类型
    protected  $headers = array(
        "Content-type: text/xml;charset=\"utf-8\"",
        "Accept: text/xml",
        "Cache-Control: no-cache",
    );

    /**
     * 获取发送类型
     * @return array
     */
    public function get_send_type(){
        return self::SEND_MSG_TYPE;
    }

    /**
     * 获取模版ID
     * @return string
     */
    public function get_send_tmp_id(){
        return self::TEMPLATE_ID;
    }

    /**
     * 根据模版类型查找模版详情
     * @param $type 模版类型
     * @param $inter_id 酒店集团ID
     * @return array|bool
     */
    public function get_template_detail_byType( $type, $inter_id )
    {
        if( !$type ){
            return FALSE;
        }

        $filter = array();
        $filter['type'] = $type;
        $filter['inter_id'] = $inter_id;
        $filter['status'] = parent::STATUS_TRUE;

        $result = $this->find( $filter );
        if( !$result ){
            //使用类型查找不到模版
            $this->_write_log( '使用类型查找不到模版'.json_encode( $filter ) );
        }
        return $result;
    }

    /**
     * 发送优惠劵相关通知
     * @param $inter_id
     * @param null $openid
     * @param $data
     * @param string $jump_url
     * @return string
     */
    public function send_template_coupon_msg($inter_id, $openid = null,$type = 1,$data = array()){
        $this->load->library("MYLOG");
        MYLOG::w(json_encode(func_get_args()),'membervip/message_wxtemp/send_template_coupon_msg','func_get_args');
        if(empty($inter_id)) return $this->json_return('4001','集团酒店ID不能为空');
        if(empty($openid)) return $this->json_return('4001','微信ID不能为空');
        if(empty($data)) return $this->json_return('4001','内容不能为空');
        if(empty($type)) $type = $this->get_send_type();
        $createInfo = array();

        //使用缓存机制
        $templateInfo = $this->session->tempdata("{$inter_id}_{$type}_send_template_coupon_msg");
        if(empty($templateInfo)){
            $templateInfo = $this->get_template_detail_byType($type,$inter_id);
            MYLOG::w(json_encode(array('res'=>$templateInfo,'param'=>array('type'=>$type,'inter_id'=>$inter_id))),'membervip/message_wxtemp/send_template_coupon_msg','template_info_bymysql');
            $this->session->set_tempdata("{$inter_id}_{$type}send_template_coupon_msg", json_encode($templateInfo), 600);
        }else{
            MYLOG::w(json_encode(array('res'=>$templateInfo,'param'=>array('type'=>$type,'inter_id'=>$inter_id))),'membervip/message_wxtemp/send_template_coupon_msg','template_info_byredis');
            $templateInfo = @json_decode($templateInfo,true);
        }

        switch ($type){
            case self::PACKAGE_EXPIRE:
                if($templateInfo){
                    $_data = array();
                    $_data['name'] = $data['title'];
                    $_data['expiration_date'] = date('Y年m月d日',$data['expire_time']);// 过期时间
                    $_data['member_card_id'] = $data['member_card_id'];
                }
                break;
            case self::AUDIT_RESULTS:
                if($templateInfo){
                    $_data = array();
                    $_data['name'] = $data['name']; //姓名
                    $_data['telephone'] = $data['telephone'];// 手机号码
                    $_data['audittime'] = date('Y年m月d日 H:i',$data['audittime']); //审核时间
                }
                break;
            case self::CREDITED_NOTICE:
                if($templateInfo){
                    $_data = array();
                    $_data['name'] = $data['name']; //账户名称
                    $_data['count'] = $data['count'];// 数量
                    $_data['curtime'] = date('Y年m月d日',$data['curtime']); //通知时间
                }
                break;
            case self::SENDCARD_NOTICE:
                if($templateInfo){
                    $_data = array();
                    $_data['name'] = $data['name']; //账户名称
                    $_data['count'] = $data['count'];// 数量
                    $_data['curtime'] = date('Y年m月d日',$data['curtime']); //通知时间
                }
                break;
            case self::REDSENDCARD_NOTICE:
                if($templateInfo){
                    $_data = array();
                    $_data['name'] = $data['name']; //账户名称
                    $_data['count'] = $data['count'];// 数量
                    $_data['curtime'] = date('Y年m月d日',$data['curtime']); //通知时间
                }
                break;
            case self::SEND_INVITATE_NOTICE:
                if($templateInfo){
                    $_data = array('username'=>$data['username'],'lvl_name'=>$data['lvl_name']);
                }
                break;
            default:break;
        }
        if(isset($_data) && !empty($_data)) $createInfo = $this->create_template_message($inter_id,$openid,$templateInfo,$_data);
        MYLOG::w(json_encode(array('res'=>$createInfo,'param'=>array('inter_id'=>$inter_id,'openid'=>$openid,'templateInfo'=>$templateInfo,'_data'=>$_data))),'membervip/message_wxtemp/send_template_coupon_msg','create_template_message');
        if(!empty($createInfo) && $createInfo['status'] == 1){
            $res = $this->save_template_record($createInfo,$inter_id);
            MYLOG::w(json_encode(array('res'=>$res,'param'=>array('inter_id'=>$inter_id,'createInfo'=>$createInfo))),'membervip/message_wxtemp/send_template_coupon_msg','save_template_record');
            return $res;
        }
        return $this->json_return('1002','发送失败!');
    }

    /**
     * 发送模版消息
     * @param $json_data
     * @return string
     */
    public function send_template_msg($inter_id = null,$json_data = array()){
        if(empty($inter_id || empty($json_data))) return $this->json_return('9999','缺少必要参数!');
        $this->load->model('wx/access_token_model');
        $access_token= $this->access_token_model->get_access_token($inter_id);
        $url = self::SEND_URL.$access_token;
        $this->load->helper('common_helper');
        $this->_write_log('send_template_msg--json_data'.json_encode($json_data));
        $result = doCurlPostRequest($url,$json_data);
        $this->_write_log('send_template_msg--result'.$result);
        $result_data = json_decode($result,true);
        if($result_data['errcode'] == 0 && $result_data['errmsg'] == 'ok'){
            return $this->json_return('1001','发送成功');
        }elseif ($result_data['errcode'] == '40001'){
            $access_token = $this->access_token_model->reflash_access_token ( $inter_id );
            $url = self::SEND_URL.$access_token;
            $result = doCurlPostRequest($url,$json_data);
            $result_data = json_decode($result,true);
            if($result_data['errcode'] == 0 && $result_data['errmsg'] == 'ok'){
                return $this->json_return('1001','发送成功');
            }
        }elseif ($result_data['errcode'] == '42001'){
            $access_token = $this->access_token_model->reflash_access_token ( $inter_id );
            $url = self::SEND_URL.$access_token;
            $result = doCurlPostRequest($url,$json_data);
            $result_data = json_decode($result,true);
            if($result_data['errcode'] == 0 && $result_data['errmsg'] == 'ok'){
                return $this->json_return('1001','发送成功');
            }
        }
        return $this->json_return('1002','发送失败!');
    }


    /*
     * 组装发送模版内容
     * @param $openid 要发送到哪个用户
     * @param $template_id 模版ID
     * @param $datas 组装好发送的数据(一维数组)
     * @param $inter_id 公众号
     * @return array('status'=>'1创建模版消息成功，2失败','message'=>'模版消息','data'=>'保存到队列的数据')
    */
    public function create_template_message($inter_id,$openid,$templateInfo,$datas,$business='coupon'){
        $this->load->library("MYLOG");
        MYLOG::w(json_encode(func_get_args()),'membervip/message_wxtemp/create_template_message','func_get_args');
        $return = array();//返回的数据
        if( !$openid || count( $templateInfo ) == 0 || !$inter_id || count( $datas ) == 0 ){
            $return['status'] = 2;
            $return['message'] = '传入的条件不足';

            $log = array();
            $log['openid'] = $openid;
            $log['template_id'] = $templateInfo['template_id'];
            $log['type'] = $templateInfo['type'];
            $log['array'] = $datas;
            $log['inter_id'] = $inter_id;
            $log['business'] = $business;
            $this->_write_log( json_encode( $log ) );
            return $return;
        }

        $message = array();//模版消息
        $type = $templateInfo['type']; //模版类型
        $template_id = $templateInfo['template_id']; //微信模版ID

        //模版内容
        $content = @unserialize( base64_decode( $templateInfo['content'] ) );
        if( !$content ){
            $content = unserialize( $templateInfo['content'] );//Message: unserialize(): Error at offset 0 of 258 bytes
        }

        $message['touser'] = $openid;//发送给哪个用户
        $message['template_id'] = $template_id;//微信模版ID

        //链接处理
        $url = '';
        $param = array('id'=>$inter_id);
        $templateUrl = $templateInfo['link'];//需要处理链接
        if( $templateUrl ){
            $exp = explode( '_', $templateUrl, 3 );
            switch ($templateUrl) {
                case 'membervip_center':
                    $url = EA_const_url::inst()->get_front_url( $inter_id, $exp[0].'/'.$exp[1],$param);
                    break;
                case 'membervip_card':
                    $url = EA_const_url::inst()->get_front_url( $inter_id, $exp[0].'/'.$exp[1],$param);
                    break;
                case 'membervip_card_cardinfo':
                    $param['member_card_id'] = isset( $datas['member_card_id'] ) ? $datas['member_card_id'] : '';
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
        if(!empty($datas['username'])){
            $content['first']['value'] = str_replace(array('{username}'), array($datas['username']),$content['first']['value']);
        }

        //解析替换头部文本（替换等级名称）
        if(!empty($datas['lvl_name'])){
            $content['first']['value'] = str_replace(array('{lvlname}'), array($datas['lvl_name']),$content['first']['value']);
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
        $templateMapping = $this->get_template_field_mapping( $type );
        MYLOG::w(json_encode(array('res'=>$templateMapping,'content'=>$content,'type'=>$type)),'membervip/message_wxtemp/create_template_message','get_template_field_mapping');
        foreach ($templateMapping as $k => $v) {//循环字段映射
            foreach( $content as $sk=>$sv ){//循环模版内容
                if( $sv['key'] == $k ){//如果模版内容的key＝字段映射的key

                    $value = '';
                    $key = '';
                    if( $sv['key'] == self::TEMPLATE_FIRST ){
                        $value = $sv['value'];
                        $key = self::TEMPLATE_FIRST;
                    }elseif( $sv['key'] == self::TEMPLATE_REMARK ){
                        $value = $sv['value'];
                        $key = self::TEMPLATE_REMARK;
                    }else{
                        $value = !empty($datas[$v['field']])?$datas[$v['field']]:$sv['value'];
                        $key = $v['key'];
                    }

                    //赋值
                    $message['data'][$key] = array( 'value'=>$value, 'color'=>$sv['color'] );
                }
            }
        }

        $json = json_encode( $message );
        $data = array();
        $data['inter_id'] = $inter_id;
        $data['hotel_id'] = $templateInfo['hotel_id'];
        $data['temp_id'] = $templateInfo['temp_id'];
        $data['template_id'] = $template_id;
        $data['openid'] = $openid;
        $data['type'] = $type;
        $data['msg'] = $json;
        $data['create_time'] = date( "Y-m-d H:i:s", time() );
        $data['sort'] = isset( $datas['sort'] ) ? $datas['sort'] : NULL;//计划任务排序，传入值时添加
        $data['status'] = 1;

        $return['status'] = 1;
        $return['message'] = $json;
        $return['data'] = $data;
        return $return;
    }

    //保存发送的模版消息到record
    public function save_template_record( $createInfo, $inter_id=NULL ) {
        $this->load->library("MYLOG");
        MYLOG::w(json_encode(func_get_args()),'membervip/message_wxtemp/save_template_record','func_get_args');
        if( count( $createInfo ) == 0 || !$inter_id ){
            $this->_write_log('createInfo:'.count( $createInfo ).'--->inter_id:'.$inter_id);
            return $this->json_return('1002','发送失败!');
        }

        $json_result = $createInfo['message'];
        $sendResult = $this->send_template_msg($inter_id,$json_result);
        MYLOG::w(json_encode(array('res'=>$sendResult,'param'=>array('inter_id'=>$inter_id,'json_result'=>$json_result))),'membervip/message_wxtemp/save_template_record','send_template_msg');

        $sendResults = json_decode($sendResult,true);

        //保存发送记录
        try{
            $this->load->model( 'member/Message_wxtemp_record_model', 'record_model' );
            $record_model = $this->record_model;

            $data = $createInfo['data'];
            $data['result'] = $sendResult;
            $data['create_time'] = date( "Y-m-d H:i:s", time() );
            $data['status'] = (isset($sendResults['code']) && $sendResults['code']=='1001')?$record_model::STATUS_SUCCESS:$record_model::STATUS_FAIL;//STATUS_FAIL
            unset( $data['sort'] );
            //保存到record
            $res = $record_model->save_record($data,$inter_id);
            MYLOG::w(json_encode(array('res'=>$res,'param'=>$data)),'membervip/message_wxtemp/save_template_record','save_record');
            if($sendResults['code'] == '1001'){
                //发送成功
                return $sendResult;
            }
        }catch (Exception $e){
            MYLOG::w(json_encode(array('msg'=>$e->getMessage(),'file'=>$e->getFile(),'line'=>$e->getLine())),'membervip/message_wxtemp/save_template_record','save_record');
            if($sendResults['code'] == '1001'){
                //发送成功
                return $sendResult;
            }
        }
        return $this->json_return('1002','发送失败!');
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

    //模版类型
    public function get_template_type()
    {
        return array(
            self::PACKAGE_EXPIRE=>'优惠劵到期提醒',
            self::AUDIT_RESULTS=>'审核结果通知',
            self::CREDITED_NOTICE=>'优惠券到账通知',
            self::SENDCARD_NOTICE=>'优惠券发放通知',
            self::REDSENDCARD_NOTICE=>'注册送礼包通知',
            self::SEND_INVITATE_NOTICE=>'邀请好友通知',
            self::SEND_CREDIT_NOTICE=>'积分兑换通知',
            self::SEND_ACTION_NOTICE =>'活动开始提醒',
            self::SEND_RECOMMEND_NOTICE => '推荐成功通知',
            self::SEND_VERIFY_LVL_UP => '会员等级变更通知',
            self::SEND_VERIFY_UNPASS => '会员资料审核不通过通知',
            self::SEND_VERIFY_REG_PASS => '会员注册成功通知',
            self::SEND_VERIFY => '会员资料审核通知',
        );
    }

    /**
     * HTTP POST请求
     * @param string $url 请求地址
     * @param string $parameter
     * @param array $header
     * @return mixed
     */
    protected function cURLPost($url,$parameter,$header=array()){
        $header = $header ? $header : $this->headers;
        $curlhandle = curl_init();
        curl_setopt($curlhandle, CURLOPT_URL, $url);
        curl_setopt($curlhandle, CURLOPT_HTTPHEADER, $header); //设置HTTP头字段的数组
        curl_setopt($curlhandle, CURLOPT_SSL_VERIFYPEER, 0); //对认证证书来源的检查
        curl_setopt($curlhandle, CURLOPT_SSL_VERIFYHOST, 1); //从证书中检查SSL加密算法是否存在
        curl_setopt($curlhandle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:23.0) Gecko/20100101 Firefox/23.0');
        curl_setopt($curlhandle, CURLOPT_FOLLOWLOCATION, 0); //使用自动跳转
        curl_setopt($curlhandle, CURLOPT_AUTOREFERER, 0); //自动设置Referer
        curl_setopt($curlhandle, CURLOPT_POST, 1); //发送一个常规的Post请求
        curl_setopt($curlhandle, CURLOPT_POSTFIELDS, $parameter);//微信接口要就json数据
        curl_setopt($curlhandle, CURLOPT_COOKIE, ''); //读取储存的Cookie信息
        curl_setopt($curlhandle, CURLOPT_TIMEOUT, 30); //设置超时限制防止死循环
        curl_setopt($curlhandle, CURLOPT_HEADER, 0); //显示返回的Header区域内容
        curl_setopt($curlhandle, CURLOPT_RETURNTRANSFER, 1); //获取的信息以文件流的形式返回
        $result = curl_exec($curlhandle);
        curl_close($curlhandle);
        return $result;
    }

    //模版链接
    public function get_template_url(){
        return array(
            '0'=>'－－无链接－－',
            'membervip_center'=>'会员中心',
            'membervip_card'=>'优惠劵中心',
            'membervip_card_cardinfo'=>'优惠劵详情',
            'membervip_reg'=>'会员注册',
            'membervip_invitate'=>'邀请好友'
        );
    }

    /**
     * 取出模版字段
     * @return array
     */
    public function get_template_field_name(){
        return array(
            //优惠劵到期
            'packageName'=>'到期优惠劵名称',
            'packageExpDate'=>'到期时间',
            'username'=>'姓名',
            'usertel'=>'手机号',
            'usernum'=>'会员号',
            'audittime'=>'审核时间',
            'account'=>'账户名称',
            'number'=>'数量',
            'curtime'=>'时间',
            'service_name'=>'服务名称',
            'service_progress'=>'服务进度',
            'customize_content'=>'自定义内容',
        );
    }

    //后台反序列化content字段，输出到详情
    public function unserialize_content()
    {
        if( isset( $this->_data['content'] ) && !empty( $this->_data['content'] ) ){
            $content = @unserialize( base64_decode( $this->_data['content'] ) );
            if( !$content ){
                $content = unserialize( $this->_data['content'] );//Message: unserialize(): Error at offset 0 of 258 bytes 数据库字段类型不够长
            }
            return $content;
        }else{
            //优惠劵内容
            return array(
                'first'=>array( 'key'=>'first', 'value'=>'', 'color'=>'' ),
                '1'=>array( 'key'=>'', 'value'=>'', 'color'=>'' ),
                'remark'=>array( 'key'=>'remark', 'value'=>'', 'color'=>'' ),
            );
        }

    }

    /**
     * @return string the associated database table name
     */
    public function table_name($inter_id=NULL){
        return 'member_message_template';
    }

    /**
     * 返回模版信息表member_message_template的主键
     * @return string
     */
    public function table_primary_key()
    {
        return 'temp_id';
    }

    /**
     * 后台模版表格表头字典
     * @return array
     */
    public function attribute_labels() {
        return array(
            'temp_id'=> 'ID',
            'inter_id'=> '公众号',
            'hotel_id'=> '酒店',
            'template_id'=> '模版ID',
            'type'=> '模版类型',
            'content'=> '模版内容',
            'link'=> '链接',
            'create_time'=> '创建时间',
            'update_time'=> '更新时间',
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
            'temp_id',
            'inter_id',
            'hotel_id',
            'type',
            'link',
            'create_time',
            'status',
        );
    }

    //定义 m_save 保存时不做转义字段
    public function unaddslashes_field()
    {
        return array(
            'msg',
            'result',
            'content',
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
        /** 获取本管理员的酒店权限  */

        return array(
            'temp_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $publics,
            ),'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $hotels,
            ),
            'template_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'type' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $this->get_template_type(),
            ),
            'content' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_hide'=> TRUE,
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'link' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_default'=> 'http://',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=>$this->get_template_url(),
            ),
            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_default'=> date('Y-m-d H:i:s'),
                'form_hide'=> TRUE,
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'update_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_default'=> date('Y-m-d H:i:s'),
                'form_hide'=> TRUE,
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type'=>'combobox', //textarea|text|combobox|number|email|url|price
                'select'=> $Somabase_util::get_status_options(),
            ),
        );
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
        if( isset($params['sort_field']) && isset($params['sort_direct']) ){
            $sort= $params['sort_field']. ' '. $params['sort_direct'];
        } else $sort= "{$pk} DESC";  //默认排序

        $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
        $page_size= isset($params['page_size'])? $params['page_size']: $num;
        $current_page= isset($params['page_num'])? $params['page_num']: 1;

        if(count($select)==0) {
            $select= $this->grid_fields();
        }
        $select= count($select)==0? '*': implode(',', $select);

        //echo $select;die;
        $offset= ($current_page-1)>=0? ($current_page-1)*$page_size: 0;
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
        if($this->input->get('debug')=='1') echo $this->_shard_db()->last_query();
        if($format=='array'){
            $tmp= array();
            $field_config= $this->get_field_config('grid');
            unset($field_config['inter_id']);
            foreach ($result as $k=> $v){
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

        return array(
            'total'=>$total,
            'data'=>$result,
            'page_size'=>$page_size,
            'page_num'=>$current_page,
        );
    }

    /**
     * grid表格中默认哪个字段排序，排序方向
     */
    public static function default_sort_field()
    {
        return array('field'=>'temp_id', 'sort'=>'desc');
    }
    /* 以上为AdminLTE 后台UI输出配置函数 */

    //获取模版字段映射到表字段
    public function get_template_field_mapping( $type )
    {
        $info = array();
        switch ($type) {
            case self::PACKAGE_EXPIRE:
                $info = $this->get_package_exp_template_field();//优惠劵过期
                break;
            case self::AUDIT_RESULTS:
                $info = $this->get_audit_template_field();//优惠劵过期
                break;
            case self::CREDITED_NOTICE:
                $info = $this->get_credited_notice_template_field();//优惠劵到账通知
                break;
            case self::SENDCARD_NOTICE:
                $info = $this->get_credited_notice_template_field();//优惠劵发放通知
                break;
            case self::REDSENDCARD_NOTICE:
                $info = $this->get_credited_notice_template_field();//优惠劵发放通知
                break;
            case self::SEND_INVITATE_NOTICE:
                $info = $this->get_invitate_template_field();//邀请好友发送通知
                break;
            case self::SEND_CREDIT_NOTICE:
                $info = $this->get_credit_exchange_template_field(); //积分兑换通知
            default:
                # code...
                break;
        }

        return $info;
    }

    //优惠劵过期
    public function get_package_exp_template_field()
    {
        //key是发送模版内容的key,field是对应的字段根据传入的array取出数据
        return array(
            'packageName'=>array('key'=>'name','field'=>'name'),//商品名称
            'packageExpDate'=>array('key'=>'expDate','field'=>'expiration_date'),//过期时间
        );
    }

    //员工业主审核结果
    public function get_audit_template_field(){
        //key是发送模版内容的key,field是对应的字段根据传入的array取出数据
        return array(
            'username'=>array('key'=>'keyword1','field'=>'name'),//姓名
            'usertel'=>array('key'=>'keyword2','field'=>'telephone'),//手机号
            'audittime'=>array('key'=>'keyword3','field'=>'audittime'),//审核时间
        );
    }

    //到账通知
    public function get_credited_notice_template_field(){
        //key是发送模版内容的key,field是对应的字段根据传入的array取出数据
        return array(
            'account'=>array('key'=>'keyword1','field'=>'name'),//账户名称
            'number'=>array('key'=>'keyword2','field'=>'count'),//数量
            'curtime'=>array('key'=>'keyword3','field'=>'curtime'),//时间
        );
    }

    //发送模版消息-邀请好友
    public function get_invitate_template_field(){
        //key是发送模版内容的key,field是对应的字段根据传入的array取出数据
        return array(
            'service_name'=>array('key'=>'keyword1','field'=>'service_name'),//服务名称
            'service_progress'=>array('key'=>'keyword2','field'=>'service_progress'),//服务进度
        );
    }

    //发送模版消息-积分兑换
    public function get_credit_exchange_template_field(){
        //key是发送模版内容的key,field是对应的字段根据传入的array取出数据
        return array(
            'title_key1'=>array('key'=>'FieldName','field'=>'name'),//标题一
            'title_key1'=>array('key'=>'change','field'=>'change'),//标题二
            'data_key1'=>array('key'=>'Account','field'=>'account'),//内容一
            'data_key2'=>array('key'=>'CreditChange','field'=>'creditchange'),//内容二
            'data_key3'=>array('key'=>'CreditTotal','field'=>'credittotal'),//内容三

        );
    }

    public function _write_log( $content ){
        $path= APPPATH. 'logs'. DS. 'member'. DS. 'wxtemp'. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $file= $path. date('Y-m-d_H'). '.txt';
        $this->write_log($content, $file);
    }
}
