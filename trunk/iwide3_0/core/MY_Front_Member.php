<?php

class MY_Front_Member extends MY_Front
{
    protected $_token;
    protected $_template;
    protected $_raw_template;
    protected $_skin_theme = 'default';
    protected $_template_filed_names;
    protected $_file_name;
    public $user_info;

    public function __construct()
    {
        parent::__construct();

        MYLOG::member_tracker($this->openid, $this->inter_id);

        $this->get_Token();
        if (!$this->is_restful()) {
            $this->create_member_info($this->inter_id, $this->openid);
            //获取到微信信息，开始修改保存
//            $this->updateWxInfo( $this->inter_id , $this->openid );
        }

        //获取默认模板
        $this->member_template($this->inter_id);

        //自定义文字设定
        $this->template_filed_name_set();
    }

    //获取授权token
    protected function get_Token()
    {
        $post_token_data = array(
            'id' => 'vip',
            'secret' => 'iwide30vip',
        );
        $token_info = $this->doCurlPostRequest(INTER_PATH_URL . "accesstoken/get", $post_token_data);
        $this->_token = isset($token_info['data']) ? $token_info['data'] : "";
    }

    //会员模块信息建立
    protected function create_member_info($inter_id, $openid)
    {
        //获取用户的信息
        $post_create_member = array(
            'inter_id' => $inter_id,
            'token' => $this->_token,
            'openid' => $openid,
        );
        $result = $this->doCurlPostRequest(INTER_PATH_URL . "member/create_update_member_info", $post_create_member);
        if (!empty($result['data'])) {
            $this->user_info = $result['data'];
        }
    }

    /**
     * 封装curl的调用接口，post的请求方式
     * @param string URL
     * @param string POST表单值
     * @param array 扩展字段值
     * @param second 超时时间
     * @return 请求成功返回成功结构，否则返回FALSE
     */
    protected function doCurlPostRequest($url, $post_data, $timeout = 20)
    {
        $startime = microtime(true);
        $requestString = http_build_query($post_data);
        if ($url == "" || $timeout <= 0) {
            return false;
        }
        $url .= '?t=' . time();
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
//        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); //设置HTTP头字段的数组
        //设置头文件的信息作为数据流输出
//        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //設置請求數據返回的過期時間
        curl_setopt($curl, CURLOPT_TIMEOUT, ( int )$timeout);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, true);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestString);
        //执行命令
        $res = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        $endtime = microtime(true);
        //写入日志
        $log = [
            'namespace' => 'core/MY_Front_Member',
            'curl' => $url,
            'param' => $post_data,
            'timeout' => $timeout,
            'usetime' => ($endtime - $startime),
            'result' => $res
        ];
        $this->write_log(@json_encode($log), 'membervip/access_log');
        return json_decode($res, true);
    }

    public function write_log($log, $path = '', $key = '')
    {
        $this->load->library('MYLOG');
        MYLOG::w($log, $path, $key);
    }

    /**
     * 把请求/返回记录记入文件
     * @param String content
     * @param String type
     */
    protected function api_write_log($content, $type = 'request')
    {
        $file = date('Y-m-d') . '.txt';
        $path = APPPATH . 'logs' . DS . 'front' . DS . 'membervip' . DS;
        if (!file_exists($path)) {
            @mkdir($path, 0777, TRUE);
        }
        $CI = &get_instance();
        $ip = $CI->input->ip_address();
        $fp = fopen($path . $file, 'a');

        $content = str_repeat('-', 40) . "\n[" . $type . ' : ' . date('Y-m-d H:i:s') . ' : ' . $ip . ']'
            . "\n" . $content . "\n";
        fwrite($fp, $content);
        fclose($fp);
    }

    protected function updateWxInfo($inter_id, $openid)
    {
        //获取用户的信息
        $this->load->model('wx/Publics_model');
        $userinfo = $this->Publics_model->get_fans_info($openid);
        //更新用户的信息
        $updateInfo = array(
            'nickname' => $userinfo['nickname'],
            'is_auto' => 1,
        );
        $post_savevip_url = PMS_PATH_URL . "member/save_memberinfo";
        $post_savevip_data = array(
            'inter_id' => $this->inter_id,
            'openid' => $this->openid,
            'data' => $updateInfo,
        );
        $this->doCurlPostRequest($post_savevip_url, $post_savevip_data);
    }

    protected function member_template($inter_id)
    {
        $post_tem_url = PMS_PATH_URL . "member/member_template";
        $post_tem_data = array(
            'inter_id' => $this->inter_id,
            'openid' => $this->openid,
        );
        $result = $this->doCurlPostRequest($post_tem_url, $post_tem_data);
        $this->_template = $result['data'];
        $this->_raw_template = $result['data'];
        if (strpos($this->_template, '#') != FALSE) {
            $theme = explode('#', $this->_template, 2);
            $this->_template = $theme[0];
            $this->_skin_theme = $theme[1];
        }
    }

    protected function template_filed_name_set($inter_id = '')
    {
        $fields_array = array(
            'credit' => '积分',
            'balance' => '余额',
            'coupon' => '优惠券'
        );
        $post_data = array(
            'inter_id' => empty($inter_id) ? $this->inter_id : $inter_id
        );
        $custom_config = $this->doCurlPostRequest(PMS_PATH_URL . "adminmember/get_custom_field_rule", $post_data);
        if (isset($custom_config['value']) && !empty($custom_config['value'])) {
            $data = json_decode($custom_config['value'], true);
            $data['config_id'] = $custom_config['id'];
        }
        foreach ($fields_array as $key => $v) {
            if (isset($data[$key]['name']) && !empty($data[$key]['name'])) {
                $fields_array[$key] = $data[$key]['name'];
            }
        }

        foreach ($fields_array as $k => $v) {
            $display_arr[$k . "_name"] = $v;
        }
        $this->_template_filed_names = $display_arr;

    }

    /**
     * Ajax方式返回数据到客户端
     * @param string $message
     * @param array $data
     * @param int $status
     */
    protected function _ajaxReturn($message = '', $data = array(), $status = 0)
    {
        $result = new stdClass();
        $result->message = $message;
        $result->data = $data;
        $result->status = $status;
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($result));
    }

    /**
     * @param $route
     * @param $template
     * @param $file_name
     * @param array $data
     */
    protected function template_show($route, $template, $file_name, $data = array())
    {
        $this->_file_name = $file_name;
        $view_path = $route . "/";
        $data['_skin_theme'] = $this->_skin_theme;
        if (!empty($template)) {
            $view_path .= $template . "/";
        }
        $file = VIEWPATH . $view_path . $file_name . ".php";
        if ((file_exists($file))) {
            $display_path = $view_path . $file_name;
        } else if ($template != 'version4') {
            $display_path = $route . "/phase2/" . $file_name;
        } else {
            $display_path = $route . "/version4/" . $file_name;
        }
        $data = $this->_get_view_commondata($data);
        $data['url_param'] = !empty($_GET) ? json_encode($_GET) : '';
        $this->load->view($display_path, $data);
    }

    protected function _get_view_commondata($data)
    {
        $this->load->model('wx/Access_token_model');
        $data ['signPackage'] = isset ($data ['wx_config']) ? $data ['wx_config'] : (isset ($data ['signpackage']) ? $data ['signpackage'] : $this->Access_token_model->getSignPackage($this->inter_id));
        $default_js_api_list = "'openLocation','onMenuShareTimeline','onMenuShareAppMessage','getLocation'";
        $data ['js_api_list'] = isset ($data ['js_api_list']) ? $data ['js_api_list'] . ',' . $default_js_api_list : $default_js_api_list;
        $default_js_menu_hide = '';
        $data ['js_menu_hide'] = isset ($data ['js_menu_hide']) ? $data ['js_menu_hide'] . ',' . $default_js_menu_hide : $default_js_menu_hide;
        $default_js_menu_show = '';
        $data ['js_menu_show'] = isset ($data ['js_menu_show']) ? $data ['js_menu_show'] . ',' . $default_js_menu_show : $default_js_menu_show;

        $data ['js_share_config'] ['title'] = isset ($data ['js_share_config']['title']) ? $data ['js_share_config']['title'] : $this->public ['name'] . '-微信会员';
        $slink = 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
        if (strpos($slink, '?'))
            $slink = $slink . "&id=" . $this->inter_id;
        else
            $slink = $slink . "?id=" . $this->inter_id;
        $data ['js_share_config'] ['link'] = isset($data ['js_share_config'] ['link']) ? $data ['js_share_config'] ['link'] : $slink;
        $data ['js_share_config'] ['desc'] = isset($data ['js_share_config'] ['desc']) ? $data ['js_share_config'] ['desc'] : $this->public ['name'] . '欢迎您使用会员服务,享受会员特权...';
        $data ['js_share_config'] ['type'] = isset($data ['js_share_config'] ['type']) ? $data ['js_share_config'] ['type'] : '';
        $data ['js_share_config'] ['dataUrl'] = isset($data ['js_share_config'] ['dataUrl']) ? $data ['js_share_config'] ['dataUrl'] : '';

        if (empty($data ['js_share_config'] ['imgUrl'])) {
            $skin_config = $this->get_skin_config($this->_raw_template);
            $data ['js_share_config'] ['imgUrl'] = $skin_config['share_img'];
        }
        isset ($data ['inter_id']) or $data ['inter_id'] = $this->inter_id;
        isset ($data ['page_title']) or $data ['page_title'] = $this->public ['name'];
        $data ['csrf_token'] = $this->security->get_csrf_token_name();
        $data ['csrf_value'] = $this->security->get_csrf_hash();
        return $data;
    }

    //是否前后端分离
    protected function is_restful()
    {
        $file_names = array(
            'highclass',
            'highclass#white'
        );
        if (in_array($this->_template, $file_names)) {
            return true;
        }
        return false;
    }

    protected function get_skin_config($skin_name)
    {
        $config = array(
            'allskins' => array(
                'share_img' => 'http://7n.cdn.iwide.cn/public/uploads/201709/qf111530113850.jpg',//默认
                'buydeposit' => array('share_img' => 'http://7n.cdn.iwide.cn/public/uploads/201709/qf111529093025.jpg'),//充值
                'depositcard' => array('share_img' => 'http://7n.cdn.iwide.cn/public/uploads/201709/qf111529404765.jpg'),//购卡列表
                'depositcardinfo' => array('share_img' => 'http://7n.cdn.iwide.cn/public/uploads/201709/qf111529404765.jpg'),//购卡详情
                'sign_index' => array('share_img' => 'http://7n.cdn.iwide.cn/public/uploads/201709/qf111529573875.jpg')//签到
            ),
            'highclass' => array(
                'share_img' => 'http://7n.cdn.iwide.cn/public/uploads/201708/qf081454028608.jpg',
                'buydeposit' => array('share_img' => 'http://7n.cdn.iwide.cn/public/uploads/201709/qf081738567940.jpg'),//充值
                'depositcard' => array('share_img' => 'http://7n.cdn.iwide.cn/public/uploads/201709/qf081739235743.jpg'),//购卡列表
                'depositcardinfo' => array('share_img' => 'http://7n.cdn.iwide.cn/public/uploads/201709/qf081739235743.jpg'),//购卡详情
                'sign_index' => array('share_img' => 'http://7n.cdn.iwide.cn/public/uploads/201709/qf081739443522.jpg')//签到
            )
        );
        if (empty ($config [$skin_name])) {
            $skin_name = 'allskins';
        }
        return empty ($config [$skin_name][$this->_file_name]) ? $config [$skin_name] : $config [$skin_name][$this->_file_name];
    }

    /**
     * 检查是否为已登录的会员卡
     * @param string $inter_id 酒店集团ID
     * @param string $openid 微信用户ID
     * @return bool|integer
     */
    protected function check_member_card_ogin($inter_id = '', $openid = '')
    {
        $this->load->model('membervip/front/Member_model', 'member_model');
        $userinfo = $this->member_model->get_user_info($inter_id, $openid, 'member_info_id,member_mode,is_login');
        if (empty($userinfo) OR $userinfo['member_mode'] == 1 OR $userinfo['is_login'] == 'f') return false;
        return $userinfo['member_info_id'];
    }
}
