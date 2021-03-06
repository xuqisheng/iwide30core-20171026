<?php
use App\libraries\Support\Url;

/**
 * Class MY_Front_Soma
 * @author renshuai  <renshuai@mofly.cn>
 *
 *
 * @property  Openid_rel_model $sc_openid_rel_model
 * @property Theme_config_model $Theme_config_model
 * @property Idistribute_model $Idistribute_model
 */
class MY_Front_Soma extends MY_Front
{
    /**
     * 语言包路径
     * @var string
     */
    protected $langDir = self::LANG_DIR_CN;

    /**
     * @var string
     */
    const LANG_DIR_CN = 'chinese';

    /**
     * @var string
     */
    const LANG_DIR_EN = 'english';

    /**
     * 前后分离主题的header数据
     * @var array
     */
    public $headerDatas = [];

    /**
     * 前后分离主题的footer数据
     * @var array
     */
    public $footerDatas = [];

    /**
     * 这个公众号使用威富通支付
     * @var array
     */
    public $wft_pay_inter_ids = [
        'a479457264',//厦门海旅温德姆至尊酒店
        'a482210445',//厦门帝元维多利亚大酒店
        'a489326393',//都江堰紫坪铺滑翔伞飞行营地
        'a494820079',//成都群光君悦酒店
        'a496652649',//株洲万豪
        //'a497580480',// 苏州吴宫泛太平洋酒店
        'a499046681',
        'a492763532',
        //'a498545803',
        'a484533415',
        'a498095405',
        'a499177502',
    ];

    /**
     * 金陵公众号特殊处理
     * @var array
     */
    protected $jinling_inter_ids = [
        'a450089706','a491796658'
    ];

    /**
     * 旧版皮肤路径
     * @var array
     */
    protected $oldThemePath = [
        'center',
        'default',
        'en',
        'junting',
        'mooncake',
        'mooncake1',
        'mooncake2',
        'mooncake3',
        'mooncake4',
        'spring',
        'su8',
        'ticket',
        'v1',
        'v2',
        'v3',
        'v4',
        'zongzi',
    ];

    /**
     * 分销特殊处理公众号  a490782373
     * @var array
     */
    protected $idistributInterId = [''];


    //不显示退款
    protected $cannotRefundInterId = ['a421641095'];

    public $cache_timeout = 1;
    public $cache_redis = null;
    public $open_cache = false;
    public $modelPrefix = 'soma/';
    public $open_cdn = false;

    /**
     *
     * @var array|mixed
     */
    public $themeConfig = array();

    /**
     * 主题路径文件名
     * @var string
     */
    public $theme = 'default';
    //白版、黑版
    public $version = 1;
    public $statis_code = '';
    public $sign_update_code = '';
    //商城读取vue目录
    public $path = 'soma_com';

    /**
     * 例如，雅斯特酒店不叫储值，叫雅币
     * @var string
     */
    public $show_name = '储值';

    /**
     *
     * 公众号的信息
     * @var array
     */
    public $public_info = [];

    /**
     * @var array 新版皮肤控制器列表
     */
    protected $idefaultControllers = ['gift_pack','GiftDelivery'];

    /**
     * MY_Front_Soma constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();

        MYLOG::soma_tracker($this->inter_id, $this->openid);

        $this->current_inter_id = $this->inter_id;
        $this->public_info = $this->public;
        // 去掉MY_Front定义的public变量，防止后续代码出错
        unset($this->public);

        //theme
        if(in_array($this->controller, $this->idefaultControllers)){
            // 专用idefault新版皮肤
            $this->theme = 'default';
        }else{
            $this->load->model('soma/Theme_config_model');
            $themeConfig = $this->Theme_config_model->get_using_theme($this->inter_id);
            if ($themeConfig) {
                $this->themeConfig = $themeConfig;
                $this->theme = $themeConfig['theme_path'];
                if(isset($themeConfig['theme_id'])){
                    $themeConfigInfo = $this->Theme_config_model->get(['theme_id'], [$themeConfig['theme_id']]);
                    if($themeConfigInfo){
                        if($themeConfigInfo[0]['version']){
                            $this->version = $themeConfigInfo[0]['version'];
                        }
                    }
                }
                //把公众号配置的特殊信息放入配置
                $this->statis_code = $this->_get_statis_code($this->inter_id, $themeConfig);
            }

            $this->getTicketTheme();
        }

        if (ENVIRONMENT != 'production') {
            if ($theme_path = $this->input->get('theme', true)) {
                $this->theme = $theme_path;
            }
        }

        //用于统计 例如：soma/package/index
        $session_id = session_id();
        if (!$this->session->userdata($session_id)) {
            $this->session->set_userdata($session_id, "{$this->module}/{$this->controller}/{$this->action}");
        }

        $this->_load_lang();

        //新旧主题的逻辑分离
        if ($this->isNewTheme()) {
            $this->load->model('soma/shard_config_model', 'model_shard_config');
            $this->db_shard_config = $this->model_shard_config->build_shard_config($this->inter_id);

            //定制版本商城
            $this->load->config('soma_theme_config.php');
            $configInterIds = $this->config->item('inter_id');
            $items = empty($configInterIds[$this->inter_id]) ? [] : $configInterIds[$this->inter_id];
            if(!empty($items)){
                foreach ($items as $val){
                    if($val['tkid'] == $this->input->get('tkid')){
                        $_GET['brandname'] = $val['brandname'];
                        $this->version = 2;
                        $this->path = 'soma_accor';
                        break;
                    }
                }
            }

            $this->saveQueryParams();
            $this->initViewData();

        } else {

            //加载缓存，如果没有缓存不起作用跳过
            $params = $this->input->get();
            $this->_load_cache_html($this->inter_id, $this->module, $this->controller, $this->action, $params);


            $this->load->somaDatabase($this->db_soma);
            $this->load->somaDatabaseRead($this->db_soma_read);

            //例如，雅斯特酒店不叫储值，叫雅币
            if( $this->inter_id == 'a472731996' ) {
                $this->show_name = '雅币';
            }

            if ( ENVIRONMENT === 'production') {
                $success = Soma_base::inst()->check_cache_redis();
                if ($success) {
                    //redis故障关闭cache
                    $this->open_cache = true;
                }
                $this->open_cdn = true;

                $this->cache_timeout = 60;
            }

            //初始化数据库分片配置
            $this->load->model('soma/shard_config_model', 'model_shard_config');
            $this->db_shard_config = $this->model_shard_config->build_shard_config($this->inter_id);

            $current_url = \App\libraries\Support\Url::current();
            $sign_update_url = Soma_const_url::inst()->get_url('soma/api/get_sign_ajax');
            $this->sign_update_code = <<<EOF
wx.error(function(res){
$.ajax({
    type: 'POST',
    url: '{$sign_update_url}',
    data: {id:'{$this->inter_id}', url:'{$current_url}'},
    success: function(data){ if(data.signature){
    package_obj.appId= data.appId;
    package_obj.timestamp= data.timestamp;
    package_obj.nonceStr= data.nonceStr;
    package_obj.signature= data.signature;
    } }, dataType: 'json'
});
});
EOF;

        }


        //渠道来源
        $channel = $this->input->get_post('channel', null, 0);
        if ($channel) {
            $this->session->set_tempdata('channel', $channel, 24 * 3600);
        }

        // 不是ajax请求的时候，才能进行静默授权
        // 泛分销静默授权，不是粉丝不跳转，是粉丝的话判断链接是否存在rel_res参数，
        // 存在说明已经跳转过，不管成功失败，不要再次跳转造成死循环，rel_res参数当次访问一直存在
        // 存在fans_act这个参数再进行静默授权
         if(ENVIRONMENT != 'dev' && !$this->input->get('rel_res') && !$this->input->is_ajax_request() && (!in_array($this->inter_id, ['a421641095','a429262688']) || ($this->input->get('fans_act') && time() >= strtotime("2017-08-31 18:30:00")))) {
             $this->load->library('Soma/Api_idistribute', null);
             $saler_info = $this->api_idistribute->get_saler_info($this->inter_id, $this->openid);
             if(empty($saler_info)) {
                 // 激活泛分销
                 $this->load->model('distribute/openid_rel_model', 'sc_openid_rel_model');
                 $deliver_infos = $this->Publics_model->get_public_by_id ( $this->sc_openid_rel_model->get_redis_key_status('__DISTRIBUTION_DELIER_ACCOUNT') );
                    $params = $this->input->get(null, true);
                    if (isset($params['fans_act'])) {
                        unset($params['fans_act']);
                    }
                 $url =  Soma_const_url::inst()->get_url('*/*/*', $params);
                 $act_url = prep_url($deliver_infos['domain']).'/distribute/dis_ext/auto_back/'.'?id='.$this->sc_openid_rel_model->get_redis_key_status('__DISTRIBUTION_DELIER_ACCOUNT').'&f='.base64_encode($this->inter_id.'***'.$this->openid.'***'.$url);
                 redirect($act_url);
             }
         }

        $this->datas['refund'] = true;
        if(in_array($this->inter_id, $this->cannotRefundInterId)){
            $this->datas['refund'] = false;
        }


        // 分销保护期处理
        $this->_salarProtection();
    }

    public function getTicketTheme(){
        $ticketId = $this->input->get('tkid');
        //获取皮肤
        if(!empty($ticketId)){
            $this->load->model('soma/Product_package_ticket_model', 'ProductPackageTicketModel');
            $ticket = $this->ProductPackageTicketModel->get_product_package_ticket_byIds([$ticketId], $this->inter_id);
            if(!empty($ticket)){
                $this->load->model('soma/Theme_config_use_model', 'ThemeConfigUseModel');
                $this->load->model('soma/Theme_config_model', 'themeConfigModel');
                $themeConfigUse = $this->ThemeConfigUseModel->get(
                    ['inter_id', 'theme_id'],
                    [$this->inter_id, $ticket[0]['theme_id']]
                );
                $themeConfig = $this->themeConfigModel->get(
                    ['theme_id'],
                    [$ticket[0]['theme_id']]
                );
                if(!empty($themeConfig)){
                    if(!empty($themeConfigUse)){
                        $this->themeConfig['index_bg'] = $themeConfigUse[0]['index_bg'];
                        $this->themeConfig['cat_bg'] = $themeConfigUse[0]['cat_bg'];
                    }
                    $this->theme = $themeConfig[0]['theme_path'];
                    $this->version = $themeConfig[0]['version'];
                }
            }

        }
    }


    /**
     *
     * @return     boolean  True if new theme, False otherwise.
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     *
     * 新旧皮肤判断 $this->isNewTheme() , false为旧，true为新
     */
    public function isNewTheme()
    {
        return !in_array($this->theme, $this->oldThemePath);
    }

    /**
     * 将一些重要参数写入session temp data 中,
     * 方便前后分离中使用
     * @author renshuai  <renshuai@jperation.cn>
     */
    private function saveQueryParams()
    {
        $ttl = 24 * 3600;

        $redis = $this->get_redis_instance();

        //分销员ID
        $saler_id = $this->input->get('saler', null, 0);
        if($saler_id) {
            $this->session->set_tempdata('saler', $saler_id, $ttl);
        } else{
            $this->session->set_tempdata('saler');
        }

        //粉丝ID
        $fans_id = $this->input->get('fans', null, 0);
        if($fans_id) {
            $this->session->set_tempdata('fans', $fans_id, $ttl);
        } else{
            $this->session->set_tempdata('fans');
        }

        //泛分销粉丝ID
        $fans_saler_id = $this->input->get('fans_saler', null, 0);
        if($fans_saler_id) {
            $this->session->set_tempdata('fans_saler', $fans_saler_id, $ttl);
        } else{
            $this->session->set_tempdata('fans_saler');
        }

        /* add by chencong 20170829 分销保护期 start */
        if(!$saler_id && !$fans_saler_id){
            $this->load->model('distribute/Idistribute_model');
            $trueSaler = $this->Idistribute_model->get_protection_saler($this->openid, $this->inter_id);
            if($trueSaler){
                if($trueSaler >= 10000000){// 泛分销10000000起的
                    $this->session->set_tempdata('fans_saler', $trueSaler, $ttl);
                }else{
                    $this->session->set_tempdata('saler', $trueSaler, $ttl);
                }
            }
        }
        /* add by chencong 20170829 分销保护期 end */

        $ttl = 3600;

        //渠道来源
        $channel = $this->input->get('channel', null, 0);
        if ($channel) {
            $this->session->set_tempdata('channel', $channel, $ttl);
        }

        //直播code
        $zbcode = $this->input->get('zbcode', null, '');
        if($zbcode) {
            $this->session->set_tempdata('zbcode', $zbcode, $ttl);
        }

        //直播渠道
        $channelid = $this->input->get('channelid', null, 0);
        if($channelid) {
            $this->session->set_tempdata('channelid', $channelid, $ttl);
        }

        //直播地址
        $zburl = $this->input->get('zburl', null, '');
        if($zburl) {
            $this->session->set_tempdata('zburl', $zburl, $ttl);
        }

        $rel_res = $this->input->get('rel_res', null, '');
        if($rel_res) {
            $this->session->set_tempdata('rel_res', $rel_res, $ttl);
        }

        //tkid
        $tkid = $this->input->get('tkid', null, '');
        $this->session->set_tempdata('theme_tkid', $tkid, $ttl);

        //brandname
        $brandname = $this->input->get('brandname', null, '');
        $this->session->set_tempdata('theme_brandname', $brandname, $ttl);

        //layout
        $layout = $this->input->get('layout', null, '');
        if($layout){
            $this->session->set_tempdata('theme_layout', $layout, $ttl);

        }

    }

    /**
     * 分销保护期处理
     * @author chencong <chencong@mofly.cn>
     */
    private function _salarProtection(){
        $salerId = (int)$this->input->get('saler');
        $fansSalerId = (int)$this->input->get('fans_saler');

        if($salerId || $fansSalerId){
            if (isset($_SERVER['SERVER_SOFTWARE']) && $_SERVER['SERVER_SOFTWARE'] == 'nginx') {
                $source = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            } else {
                $source = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            }
            $trueSaler = $salerId ?: $fansSalerId;
            $this->load->model('distribute/Idistribute_model');
            $this->Idistribute_model->save_saler_protection_info($this->inter_id, $this->openid, $source, $trueSaler, '', 'soma');
        }
    }

    /**
     * @author renshuai  <renshuai@mofly.cn>
     */
    private function _load_lang()
    {
        //load lang file
        $lang_cookie_key = 'lang_' . $this->inter_id;
        $lang_cookie = $this->input->cookie($lang_cookie_key, true);
        $lang_input = $this->input->get('lang', true);
        $lang = $lang_input && in_array($lang_input, array('english', 'chinese')) ? $lang_input : ($lang_cookie ? $lang_cookie : 'chinese');
        if (!$lang_cookie || ($lang_cookie !== $lang)) {
            $this->load->helper('cookie');
            set_cookie($lang_cookie_key, $lang, 864000);
        }

        $this->lang->load('soma_lang', $lang);

        //雅斯特需要把储值两个字改为雅币
        if ($this->inter_id == 'a472731996' && $lang == 'chinese')
        {
            $this->lang->language['stored_price']               = "{$this->show_name}价";
            $this->lang->language['soted_value_buy']            = "{$this->show_name}购买";
            $this->lang->language['stored_balance']             = "{$this->show_name}余额";
            $this->lang->language['store_password']             = "{$this->show_name}密码";
            $this->lang->language['balance_note_enough_tip']    = "您的{$this->show_name}余额不足，不能完成下单";
            $this->lang->language['stored_value']               = "{$this->show_name}";
            $this->lang->language['refund_success_tip']         = "小提示：退款成功后，使用的积分、{$this->show_name}将自动返还至您的账户，购买获得的积分将被扣除";
            $this->lang->language['use_ponint_coupon_fail_tip'] = "抱歉，暂无法使用优惠券、积分、{$this->show_name}，请稍后再试~";
            $this->lang->language['stord_fail_tip']             = "{$this->show_name}使用失败";
        }

        if (in_array($this->inter_id, $this->idistributInterId)  && $lang === 'chinese' ) {
            $this->lang->language['provide_by'] = "此活动内容由[0]提供";
            $this->lang->language['terms_and_conditions'] = "注意啦";
            $this->lang->language['buy_now'] = "立即申请";
            $this->lang->language['purchase_quantity'] = "申请数量";
            $this->lang->language['sold'] = "剩余";
            $this->lang->language['single_purchase_price'] = "保证金";
            $this->lang->language['surprise_offer'] = "推荐";
            $this->lang->language['my_puchases'] = "申请的商品";
            $this->lang->language['purchase_successful'] = "申请成功";
            $this->lang->language['purchase_tips'] = "小提示:请提前拨打电话进行预约";

        }

        $this->langDir = $lang;
    }

    /**
     *
     * 从配置中获取统计代码
     * @param $inter_id
     * @param $themeConfig
     * @return string
     *
     */
    protected function _get_statis_code($inter_id, $themeConfig)
    {
        $disable_action = array(
            'package_pay', 'groupon_pay', 'killsec_pay',
        );

        if (in_array($this->action, $disable_action)) {
            return '';
        }

        if (defined('PROJECT_AREA') && PROJECT_AREA == 'mooncake') {
            //月饼说统一域名代码
            return '<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?43e5308bf08ac9f80b94acf3c6ab8a99";
  var s = document.getElementsByTagName("script")[0];
  s.parentNode.insertBefore(hm, s);
})();
</script>';
        } else {
            if (isset($themeConfig['statis_code'])) {
                return $themeConfig['statis_code'];
            } else {
                $public_info = $this->public_info;
                if (!empty($public_info['statis_code'])) {
                    return $public_info['statis_code'];
                }
            }

            return '';
        }
    }

    /**
     * ==基本类
     * 举报: "menuItem:exposeArticle"
     * 调整字体: "menuItem:setFont"
     * 日间模式: "menuItem:dayMode"
     * 夜间模式: "menuItem:nightMode"
     * 刷新: "menuItem:refresh"
     * 查看公众号（已添加）: "menuItem:profile"
     * 查看公众号（未添加）: "menuItem:addContact"
     * ==传播类
     * 发送给朋友: "menuItem:share:appMessage"
     * 分享到朋友圈: "menuItem:share:timeline"
     * 分享到QQ: "menuItem:share:qq"
     * 分享到Weibo: "menuItem:share:weiboApp"
     * 收藏: "menuItem:favorite"
     * 分享到FB: "menuItem:share:facebook"
     * 分享到 QQ 空间/menuItem:share:QZone
     * ==保护类
     * 编辑标签: "menuItem:editTag"
     * 删除: "menuItem:delete"
     * 复制链接: "menuItem:copyUrl"
     * 原网页: "menuItem:originPage"
     * 阅读模式: "menuItem:readMode"
     * 在QQ浏览器中打开: "menuItem:openWithQQBrowser"
     * 在Safari中打开: "menuItem:openWithSafari"
     * 邮件: "menuItem:share:email"
     * 一些特殊公众号: "menuItem:share:brand"
     */
    //展示为以后的皮肤做扩展
    protected function _view($file, $datas = array())
    {
        //新主题使用一个header文件
        if ($this->isNewTheme()) {
            if (strpos($file, 'header')) {
                return;
            }
            $this->newThemeView($file, $datas);
            return;
        }
        $datas['token'] = [
            'name' => $this->security->get_csrf_token_name(),
            'value' => $this->security->get_csrf_hash()
        ];

        //语言包
        $datas['lang'] = $this->lang;
        $datas['langDir'] = $this->langDir;
        $datas['show_name'] = $this->show_name;

        /*
         * js_api_list: eg: array('hideMenuItems', 'showMenuItems', 'onMenuShareTimeline', 'onMenuShareAppMessage' ); 一般不需要修改
         * js_menu_hide: eg: array( 'menuItem:setFont', 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:favorite', 'menuItem:copyUrl' ); 主动隐藏
         * *** 关注 js_menu_show: eg: array( 'menuItem:share:timeline', 'menuItem:favorite', 'menuItem:copyUrl' ); 主动显示
         * *** 关注 js_share_config: eg: array('title','desc','link','imgUrl')
         */
        $js_api_list = $menu_show_list = $menu_hide_list = '';
        $datas['wx_config'] = $this->_get_sign_package($this->inter_id);
        $datas['base_api_list'] = array('hideMenuItems', 'showMenuItems', 'onMenuShareTimeline', 'onMenuShareAppMessage');
        if (isset($datas['js_api_list'])) {
            $datas['js_api_list'] += $datas['base_api_list'];
        } else {
            $datas['js_api_list'] = $datas['base_api_list'];
        }
        foreach ($datas['js_api_list'] as $v) {
            $js_api_list .= "'{$v}',";
        }
        $datas['js_api_list'] = substr($js_api_list, 0, -1);

        //统计代码
        $this->load->model("statistics/Statistics_model");
        $datas['statistics_js'] = '';//$this->Statistics_model->outputJs($this->inter_id,$this->openid,$title);

        //主动显示某些菜单
        if (!isset($datas['js_menu_show'])) {
            $datas['js_menu_show'] = array('menuItem:setFont', 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:favorite', 'menuItem:copyUrl');
        }
        foreach ($datas['js_menu_show'] as $v) {
            $menu_show_list .= "'{$v}',";
        }
        $datas['js_menu_show'] = substr($menu_show_list, 0, -1);

        //主动隐藏某些菜单
        if (!isset($datas['js_menu_hide'])) {
            $datas['js_menu_hide'] = array('menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:copyUrl', 'menuItem:share:email', 'menuItem:originPage');
        }
        foreach ($datas['js_menu_hide'] as $v) {
            $menu_hide_list .= "'{$v}',";
        }
        $datas['js_menu_hide'] = substr($menu_hide_list, 0, -1);

        if (!isset($datas['js_share_config'])) {
            $datas['js_share_config'] = false;
        }

        $datas['uri'] = array(
            'module'     => $this->module,
            'controller' => $this->controller,
            'action'     => $this->action,
        );
        $datas['inter_id'] = $this->inter_id;
        $datas['openid'] = $this->openid;
        $datas['business'] = $this->input->get_post('bsn', null, '');
        $datas['settlement'] = $this->input->get_post('stl', null, '');
        $datas['saler'] = $this->input->get_post('saler', null, '');
        $datas['fans_saler'] = $this->input->get_post('fans_saler', null, '');
        $datas['fans'] = $this->input->get_post('fans', null, '');
        $path = 'soma' . DS;

        if (!file_exists(VIEWPATH . $path . $this->theme . DS . $file . ".php")) {
            if (defined('PROJECT_AREA') && PROJECT_AREA == 'mooncake'
                && file_exists(VIEWPATH . $path . 'mooncake' . DS . $file . ".php")
            ) {
                $html = $this->load->view($path . 'mooncake' . DS . $file, $datas, true);
            } else {
                $html = $this->load->view($path . 'default' . DS . $file, $datas, true);
            }
        } else {
            $html = $this->load->view($path . $this->theme . DS . $file, $datas, true);
        }

        //CDN URL 替换。
        $html = $this->_replace_cdn_url($html);

        $cdn_url = $this->_match_url($this->module, $this->controller, $this->action);
        if ($cdn_url) {
            // 替换为文件缓存

            $this->load->driver('cache');
            $params = $this->input->get();
            $key = md5($this->_cache_html_key($this->inter_id, $cdn_url, $params));

            if (substr($html, -6) == 'header') {
                $this->cache->file->save($key, $html, $this->cache_timeout);
            } else {
                $header = $this->cache->file->get($key);
                //通过替换加入统计js
                $html = str_replace(array('//[<sign_update_code>]', '</html>'),
                    array("{$this->sign_update_code}\n", "{$this->statis_code}\n</html>"),
                    $html);
                $this->cache->file->save($key, $header . $html, $this->cache_timeout);
            }

        } else {
            //通过替换加入统计js
            $html = str_replace('</html>', "{$this->statis_code}\n</html>", $html);
        }

        /**
         * 悦榕庄皮肤
         */
        if(in_array($this->inter_id, ['a493015889', 'a497864944', 'a498449733'])) {
            $url = get_cdn_url('public/soma/v1/style_s.css');
            $html = str_replace('<body>', "<body>\n<link href=\"$url\" rel=\"stylesheet\">", $html);
        }

        echo $html;
    }

    /**
     *
     * Redis 缓存html start #########################################
     * @param $inter_id
     * @param $module
     * @param $controller
     * @param $action
     * @param $params
     * @author renshuai  <renshuai@jperation.cn>
     */
    protected function _load_cache_html($inter_id, $module, $controller, $action, $params)
    {
        $cdn_url = $this->_match_url($module, $controller, $action);
        if ($cdn_url) {
            // 更换为从文件缓存读取html
            /*
            if( !$this->cache_redis ){
                $cache= $this->_load_cache();
                $this->cache_redis= $cache->redis->redis_instance();
            }
            $key= $this->_cache_html_key($inter_id, $cdn_url, $params);
            $html= $this->cache_redis->get($key);
            */

            $this->load->driver('cache');
            $key = md5($this->_cache_html_key($inter_id, $cdn_url, $params));
            $html = $this->cache->file->get($key);

            if ($html) {
                die($html);
            }
        }
    }

    /**
     * @param $html
     * @return mixed
     * @author renshuai  <renshuai@jperation.cn>
     */
    protected function _replace_cdn_url($html)
    {
        if ($this->open_cdn) {
            if (ENVIRONMENT === 'production') {
                $search = array(
                    'http://file.iwide.cn/public',
                );
                $replace = array(
                    'http://7n.cdn.iwide.cn/public',
                );
            } else {
                $search = array(
                    'http://30.iwide.cn:821/public',
                );
                $replace = array(
                    'http://soma.cdn.iwide.cn/public',
                );
            }

            return str_replace($search, $replace, $html);

        } else {
            return $html;
        }
    }

    /**
     * @param $inter_id
     * @param $cdn_url
     * @param $params
     * @return string
     * @author renshuai  <renshuai@jperation.cn>
     */
    protected function _cache_html_key($inter_id, $cdn_url, $params)
    {
        if (isset($params['id'])) {
            unset($params['id']);
        }
        if (isset($params['openid'])) {
            unset($params['openid']);
        }
        $cache_key = 'SOMA_HTML:' . $inter_id . ':' . $cdn_url;
        $param_key = implode('_', $params);
        if (count($params) > 0) {
            return $cache_key . ':' . $param_key;
        } else {
            return $cache_key;
        }
    }

    /**
     * 需要缓存的页面
     * @param $module
     * @param $controller
     * @param $action
     * @return bool|string
     * @author renshuai  <renshuai@jperation.cn>
     */
    protected function _match_url($module, $controller, $action)
    {
        if ($this->open_cache) {
            $url = strtolower($module) . '_' . strtolower($controller) . '_' . strtolower($action);
            $matchs = array(
                'soma_package_index',
                'soma_package_package_list',
                'soma_package_package_detail',
                'soma_package_category_list',
                'soma_package_success',
                'soma_package_mooncake_list',
            );
            if (in_array($url, $matchs)) {
                return $url;
            }
        }

        return false;
    }

    //# Redis 缓存html end #########################################


    /**
     * @param $inter_id
     * @param string $url
     * @return array
     */
    protected function _get_sign_package($inter_id, $url = '')
    {
        $this->load->helper('common');
        $this->load->model('wx/Access_token_model', 'access_token_model');
        //$jsapiTicket = $this->access_token_model->get_api_ticket($inter_id);
        $jsapiTicket = $this->access_token_model->get_ticket_db($inter_id, 1);
        if (!$url) {
            $url = Url::current();
        }

        $timestamp = time();
        $nonceStr = $this->access_token_model->createNonceStr();
        $public = $this->public_info;

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
            "appId"     => $public['app_id'],
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string,
        );

        return $signPackage;
    }

    /**
     * 带智能检测用户关注情况，视情况进行高级授权跳转
     */
    public function _get_wx_userinfo()
    {
        $fans = $this->Publics_model->get_fans_info($this->openid);

        $this->write_log("_get_wx_userinfo():fans : " . var_export($fans, true));

        if (!$fans || empty($fans['nickname'])) {
            $userinfo = $this->Publics_model->get_wxuser_info($this->inter_id, $this->openid);

            $this->write_log("_get_wx_userinfo():userinfo : " . var_export($userinfo, true));

            if (isset($userinfo['subscribe']) && $userinfo['subscribe'] == 0) {

                //微信返回的信息显示没有关注，则进行高级授权验证
                if (isset($_SERVER['SERVER_SOFTWARE']) && $_SERVER['SERVER_SOFTWARE'] == 'nginx') {
                    $refer = 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
                } else {
                    $refer = 'http://' . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'];
                }

                $inter_id = $this->inter_id;
                $url = front_site_url($inter_id, false);
                if (defined('PROJECT_AREA') && PROJECT_AREA == 'mooncake') {
                    // 月饼说跳转过来，如果存在refer这个参数，证明这是第二次跳转，不进行封装
                    $r = $this->input->get('refer');
                    $this->write_log("_get_wx_userinfo(): get_refer : " . $r);
                    if (!$r) {
                        $refer = base64_url_encode($refer);
                    } else {
                        $refer = $r;
                    }
                    $refer = $url . "/index.php/soma/api/mooncake_decode_cb/?refer=" . $refer;
                }

                $refer = urlencode($refer);

                $redirect_url = $url . "/index.php/Public_oauth/index?scope=snsapi_userinfo&id={$inter_id}&refer={$refer}";

                $this->write_log('_get_wx_userinfo($redirect_url) : ' . $redirect_url);

                redirect($redirect_url);

            } else {
                $this->Publics_model->update_wxuser_info($this->inter_id, $this->openid);

                return $userinfo;
            }

        } else {
            return $fans;
        }
    }

    /**
     * 加载缓存组件
     * @see MY_Controller::_load_cache()
     */
    protected function _load_cache($name = 'Cache')
    {
        $success = Soma_base::inst()->check_cache_redis();
        if (!$success) {
            //redis故障关闭cache
            Soma_base::inst()->show_exception('当前访问用户过多，请稍后再试！', true);
        }
        if (!$name || $name == 'cache') //不能为小写cache
        {
            $name = 'Cache';
        }

        $this->load->driver('cache',
            array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'soma_'),
            $name
        );

        return $this->$name;
    }

    /**
     * 对于需要跳转站外域名获取code的，根据inter_id 做区分跳转
     * @param string $inter_id
     * @param string $refer
     */
    protected function _wx_redirect($inter_id, $refer)
    {
        if (defined('PROJECT_AREA') && PROJECT_AREA == 'mooncake') {
            //月饼说专用授权跳转
            $this->load->model('wx/Publics_model');
            $public = $this->Publics_model->get_public_by_id($this->input->get('id'));

            if (!$this->input->get('code')) {
                // 将refer_url转码，以免被微信服务器对url参数进行拆解
                $refer_url = base64_url_encode($refer);

                $inter_id = $this->input->get('id');
                $url = front_site_url($inter_id, false);

                $scope = 'snsapi_base';
                if ($this->input->get('scope')) {
                    $scope = $this->input->get('scope');
                }
                $url .= "/index.php/soma/api/mooncake_decode_cb/?refer=" . $refer_url;

                $this->write_log('Base refer url :' . $url . "\n");
                $url = urlencode($url);

                $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $public ['app_id']
                    . "&redirect_uri=$url&response_type=code&scope=$scope&state=STATE#wechat_redirect";

                $this->write_log('Front Soma get Code Url :' . $url . "\n");
                redirect($url);
                exit();

            } else {
                //有code参数，进行数据存储。
                $code = $this->input->get('code');
                // $redirect_uri = urldecode($this->input->get ( 'refer' ));
                $redirect_uri = base64_url_decode($this->input->get('refer'));
                $this->write_log('Code :' . $code . "\n" . "redirect_uri : " . $redirect_uri . "\n");
                $inter_id = $this->input->get('id');
                $this->write_log("Get Params :" . json_encode($_GET));

                $result = $this->_auth_res($this->input->get('code'), $this->input->get('id'));
                $result = json_decode($result, true);
                $openid = isset($result ['openid']) ? $result ['openid'] : '';
                $this->session->set_userdata(array($this->session->userdata('inter_id') . 'openid' => $openid));
                if ($openid) {
                    $accessstoken = null;
                    if ($this->input->get('scope')) {
                        $accessstoken = $result ['access_token'];
                    }
                    $this->Publics_model->update_wxuser_info($this->session->userdata('inter_id'), $openid, $accessstoken);
                }

                redirect($redirect_uri);
                exit();
            }

        }
        //正常URL跳转
        redirect(site_url('public_oauth/index') . '?id=' . $inter_id . '&refer=' . urlencode($refer));
    }


    /**
     * 网页授权通过code获取用户信息
     * @param String code
     * @param String 公众号识别码
     * @return JSON 请求微信返回结果
     */
    private function _auth_res($code, $inter_id)
    {
        $this->load->model('wx/Publics_model');
        $public = $this->Publics_model->get_public_by_id($inter_id);
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $public ['app_id']
            . "&secret=" . $public ['app_secret'] . "&code=$code&grant_type=authorization_code";

        $this->load->helper('common');

        return doCurlGetRequest($url);
    }

    /**
     * 获取中心平台公众号ID
     */
    protected function get_center_inter_id()
    {
        if (isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV'] == 'production') {
            // return 'a429262688';a476864535
            return 'a476864535';
        } else {
            // 测试环境中心平台公众号
            return "a471258436";
        }
    }

    /**
     * 检查并建立当前酒店openid与中心平台的openid映射关系
     *
     * @param      string $inter_id 公众号ID
     * @param      string $openid openid
     */
    protected function _bulid_center_openid_map($inter_id, $openid)
    {
        $this->load->model('soma/center_openid_map_model', 'om_model');
        // 中心平台的openid不需要进行跳转，直接写入即可
        if ($this->get_center_inter_id() != $this->inter_id) {
            $this->load->model('soma/center_openid_map_model', 'om_model');
            $center_info = $this->om_model->get_center_openid_info($inter_id, $openid);
            $api_record = $this->session->userdata('bulid_openid_map_record');

            // 查找不到中心平台openid信息,构造页面跳转逻
            // 查找有openid映射记录或本次session有调用记录，不跳转,以免出现死循环。
            if (!$api_record && count($center_info) <= 0) {
                $this->session->set_userdata(array('bulid_openid_map_record' => 1));
                $origin = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                $notify = Soma_const_url::inst()->get_url('*/*/build_openid_map_notify');
                $center_domain = front_site_url($this->get_center_inter_id());
                $url = $center_domain
                    . '/index.php/soma/center/bulid_openid_map_record?id='
                    . $this->get_center_inter_id()
                    . "&hotel_info[inter_id]=" . $inter_id
                    . "&hotel_info[openid]=" . $openid
                    . "&extra[origin_url]=" . base64_url_encode($origin)
                    . "&notify_url=" . base64_url_encode($notify);
                redirect($url);
            }
        } else {
            $this->write_log('build self openid map', 'soma' . DS . 'center');
            $hotel_data['inter_id'] = $inter_id;
            $hotel_data['openid'] = $openid;
            $_fmt_data = $this->om_model->format_map_record_data($this->inter_id, $this->openid, $hotel_data);
            $result = array('success' => false, 'msg' => '写入数据失败');
            if ($this->om_model->data_validation($_fmt_data)) {
                if ($this->om_model->save_map_record($_fmt_data)) {
                    $result = array('success' => true, 'msg' => '');
                }
            }
            $this->write_log('result' . var_export($result, true), 'soma' . DS . 'center');
        }
    }

    /**
     * 建立openid映射记录回调接口
     * 不管调用结果如何，均进行页面跳转，设置本次session已调用接口，免得出现死循环
     */
    public function build_openid_map_notify()
    {
        $result = $this->input->get('res', true);
        $extra = $this->input->get('extra', true);
        $this->write_log(var_export($result, true) . var_export($extra, true), 'soma' . DS . 'center');

        $this->session->set_userdata(array('bulid_openid_map_record' => 1));
        $request_url = base64_url_decode($extra['origin_url']);
        redirect($request_url);

    }

    /**
     * 日志写入
     * @param string $content
     * @param string $dir
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function write_log($content, $dir = 'mooncake')
    {
        $file = date('Y-m-d') . '.txt';
        $path = APPPATH . 'logs' . DS . $dir . DS;
        if (!file_exists($path)) {
            @mkdir($path, 0777, true);
        }
        $fp = fopen($path . $file, 'a');

        $CI = &get_instance();
        $ip = $CI->input->ip_address();
        $content = str_repeat('-', 40) . "\n[" . date('Y-m-d H:i:s') . ']'
            . "\n" . $ip . "\n" . $content . "\n";
        fwrite($fp, $content);
        fclose($fp);
    }

    /**
     * 拉取商品列表HTML
     */
    public function get_page_block($uri)
    {
        $current_url = $uri;
        $filter = array('inter_id' => $this->inter_id);
        $this->load->model('soma/Cms_block_model');
        $this->load->model('soma/Product_package_model');
        $pids = $this->Cms_block_model->show_in_page($current_url, $filter);

        $products = array();
        if ($pids) {
            $products = $this->Product_package_model->get_product_package_by_ids($pids, $this->inter_id);
        }


        // 如果没有配置推荐位产品，抽取产品销量最高的显示
        if (empty($products) || count($products) < 2) {
            $products = $this->Product_package_model->getRecommendedProducts($this->inter_id);
            $pids = array_keys($products);
        }


        //首页不显示商品剔除
        if(!empty($products)){
            $productPackageModel = $this->Product_package_model;
            foreach ($products as $key => $val){
               if($val['is_hide'] == $productPackageModel::IS_NOT){
                   unset($products[$key]);
               }
            }
        }


        //获取酒店城市列表
        $this->load->model('hotel/Hotel_model', 'MyFrontSomaHotelModel');
        $MyFrontSomaHotelModel = $this->MyFrontSomaHotelModel;
        $params = array(
            'inter_id' => $this->inter_id,
        );

        foreach ($products as $k => $p) {
            $productCites = $MyFrontSomaHotelModel->get_hotel_hash(array('inter_id' => $this->inter_id, 'hotel_id' => $p['hotel_id']), array('city'), 'array');
            $products[$k]['city'] = isset($productCites[0]['city']) ? $productCites[0]['city'] : null;
        }

        //var_dump($pids);die;
        $html = '';
        if ($pids && $products && count($products) > 0) {

            // 双语翻译
            if ($this->langDir == self::LANG_DIR_EN) {
                $new_products = $products;
                $en_info = $this->Product_package_model->getProductEnInfoList($pids, $this->inter_id);
                foreach ($products as $key => $product) {
                    if (isset($en_info[$product['product_id']])) {
                        foreach ($this->Product_package_model->en_fields() as $field) {
                            if (!empty($en_info[$product['product_id']][$field])) {
                                $new_products[$key][$field] = $en_info[$product['product_id']][$field];
                            }
                        }
                    }
                }
                $products = $new_products;
            }

            if ($this->theme == 'default') {

                $html = '<div class="bd h28 pad3 bg_fff">' . $this->lang->line('trending') . '</div><div class="tp_list bg_fff bd_bottom">';
                foreach ($products as $k => $v) {
                    $url = Soma_const_url::inst()->get_url('soma/package/package_detail', array('id' => $this->inter_id, 'pid' => $v['product_id']));
                    $can_gift = ($v['can_gift'] == Product_package_model::CAN_T) ? '<div class="fn"><span>' . $this->lang->line('gift_friends') . '</span></div>' : '';
                    $default_pic = base_url('public/soma/images/default.jpg');
                    $html .=
                        "<a href='{$url}' class='item'>
  <div class='img'><img src='{$v['face_img']}' />{$can_gift}</div>
  <p class='txtclip'>{$v['name']}</p >
  <div class='foot h2'>
      <p class='color_fff m_bg tp_price'>
        <span>" . $this->lang->line('surprise_offer') . "</span>
      <span class='y'>{$v['price_package']}</span>
          <span class='m_bg2'>" . $this->lang->line('buy') . "<em class='iconfont'>&#xe61b;</em></span>
      </p >
      <p class='tp_local txtclip'>{$v['city']}</p >
  </div>
</a>";
                }
                $html .= '</div>';

            } elseif ($this->theme == 'v1') {

//                $is_odd = (count($products) % 2) > 0;
//                if ($is_odd) {
//                    array_pop($products);
//                }

                $html = '<link href="' . base_url("public/soma/v1/v1.css") . config_item("css_debug") . '" rel="stylesheet">
                <div class="bd h28 pad3 bg_fff">' . $this->lang->line('trending') . '</div><div class="tp_list bg_fff bd_bottom">';
                foreach ($products as $k => $v) {
                    $url = Soma_const_url::inst()->get_url('soma/package/package_detail', array('id' => $this->inter_id, 'pid' => $v['product_id']));
                    $default_pic = base_url('public/soma/images/default.jpg');
                    $html .=
                        "<a href='{$url}' class='item bg_fff'>
  <div class='img'>
      <img src='{$v['face_img']}' />
  </div>
  <p class='h30 color_888'>{$v['name']}</p >
  <p class='item_foot'>" . $this->lang->line('surprise_offer') . "<em>|</em><span class='color_main y'>{$v['price_package']}</span></p >
</a>";

                }
                $html .= '</div>';
            }
        }

        $html = '<div id="load_page_block" >' . $html . '</div>';

        return $html;
    }

    //会员登录
    public function check_member_is_login($url = '')
    {
        $dir = 'soma' . DS . 'member_login';
        $this->write_log('公众号：' . $this->inter_id . ', 检测会员是否登录开始', $dir);

        $this->load->library('Soma/Api_member');
        $api = new Api_member($this->inter_id);
        $result = $api->get_token();
        $api->set_token($result['data']);
        $result = $api->get_member_info($this->openid);
        $this->write_log('公众号：' . $this->inter_id . ", 获取会员登录信息：\n" . json_encode($result), $dir);
        if ($result) {
            $data = (array)$result['data'];

            if ($data['member_mode'] == 2 && $data['is_login'] == 't') {
                $this->write_log('公众号：' . $this->inter_id . ', 会员登录通过', $dir);

                return true;
            } elseif ($data['member_mode'] == 1 && $data['login_type'] == 'login') {
                $this->write_log('公众号：' . $this->inter_id . ', 会员登录不通过, 跳转至会员登录页面', $dir);
                $api->goto_member_login($this->inter_id, $url);
            } else {
                $this->write_log('公众号：' . $this->inter_id . ', 判断会员是否登录条件不足', $dir);

                //会员没有返回相应的信息
                return false;
            }

        } else {
            $this->write_log('公众号：' . $this->inter_id . ', 会员信息返回空', $dir);

            return false;
        }
        $this->write_log('公众号：' . $this->inter_id . ', 检测会员是否登录结束', $dir);
    }


    /**
     * Gets the redis instance.
     *
     * @param      string $select The select
     *
     * @return     Redis|null  The redis instance.
     */
    public function get_redis_instance($select = 'soma_redis')
    {
        $this->load->library('Redis_selector');
        if ($redis = $this->redis_selector->get_soma_redis($select)) {
            return $redis;
        }

        return null;
    }

    /**
     * @param $serviceName
     * @param string $prefix
     * @return string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function serviceAlias($serviceName, $prefix = 'soma')
    {
        return $prefix . '_' . strtolower($serviceName);
    }

    /**
     * @param string $serviceName
     * @param string $prefix
     * @return string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function serviceName($serviceName, $prefix = 'soma')
    {
        return "$prefix/$serviceName";
    }


    /**
     *
     * response json
     *
     * @param $arr
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function json($arr)
    {
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($arr));
    }


    /**
     * 处理分销号和泛分销号
     *
     * @author luguihong  <luguihong@jperation.com>
     */
    public function handleDistribute()
    {
        $salerId        = $this->input->get('saler');
        $fansSalerId    = $this->input->get('fans_saler');

        /* add by chencong 20170826 分销保护期 start */
        if(!$salerId && !$fansSalerId){
            $this->load->model('distribute/Idistribute_model');
            $trueSaler = $this->Idistribute_model->get_protection_saler($this->openid, $this->inter_id);
            if($trueSaler){
                if($trueSaler >= 10000000){// 泛分销10000000起的
                    $fansSalerId = $trueSaler;
                }else{
                    $salerId = $trueSaler;
                }
            }
        }
        /* add by chencong 20170826 分销保护期 end */

        //需要跳转
        $url = Url::current();

        if( $salerId ) {
            //如果链接存在分销号，就不刷新链接的分销号，转发刷新，购买计算绩效
            $this->session->set_userdata( 'giveDistribute'.$this->inter_id.$this->openid, $salerId );
        } else {

            //如果链接不存在分销号，判断是否为分销员
            $staff = $this->get_user_saler_or_fans_id();
            if ($staff)
            {
                $saler_type = $staff['saler_type'];
                $saler_id   = $staff['saler_id'];
                if ($saler_id && $saler_type)
                {
                    if ($saler_type == 'STAFF')
                    {
                        $this->session->set_userdata( 'isSaler'.$this->inter_id.$this->openid, json_encode($staff) );

                        //是分销员，跳转链接，带上自己的分销号，购买计算绩效
                        $url .= "&saler={$saler_id}";
                        header("Location: $url");
                        die;
                    } elseif ($saler_type == 'FANS') {
                        //是泛分销员，判断页面是否带有泛分销号
                        if( $fansSalerId )
                        {
                            //带泛分销号，是否为自己的泛分销号
                            if( $fansSalerId == $saler_id )
                            {
                                //是自己的泛分销号，购买不赠送绩效
                                $this->session->set_userdata( 'giveDistribute'.$this->inter_id.$this->openid, '' );
                            } else {
                                //不是自己的泛分销号，购买后计算绩效给泛分销号员
                                $this->session->set_userdata( 'giveDistribute'.$this->inter_id.$this->openid, $fansSalerId );
                            }
                        } else {
                            //不带泛分销号，静默授权泛分销号，跳转链接带上泛分销号，购买不赠送绩效
                            //静默授权已经在父类进行，无需在这里操作
                            $url .= "&fans_saler={$saler_id}";
                            header("Location: $url");
                            die;
                        }
                    }
                }
            }
        }
    }

    /**
     * 获取链接上的分销号和泛分销号
     * @param $datas
     * @return mixed
     * @author luguihong  <luguihong@jperation.com>
     */
    public function getDistribute( $datas )
    {
        $salerId                    = $this->input->get('saler');
        $fansSalerId                = $this->input->get('fans_saler');
        $datas['saler_self']        = $salerId;
        $datas['fans_saler_self']   = $fansSalerId;

        return $datas;
    }

    /**
     * 判断当前用户是否为分销员或者是泛分销
     * @return array
     * @author luguihong  <luguihong@jperation.com>
     */
    public function get_user_saler_or_fans_id()
    {
        $staff = $this->_get_saler_id($this->inter_id, $this->openid);
        if ($staff)
        {
            //判断是分销员还是泛分销 $staff['typ'] ＝ 'STAFF'(分销员), 'FANS'(泛分销)
            $saler_type     = isset($staff['typ']) && ! empty($staff['typ']) ? $staff['typ'] : '';
            $saler_id       = isset($staff['info']['saler']) && ! empty($staff['info']['saler']) ? $staff['info']['saler'] : 0;
            if ($saler_id && $saler_type)
            {
                //分销员名称
                $saler_name     = isset($staff['info']['name']) && ! empty($staff['info']['name']) ? $staff['info']['name'] : '';

                //返回分销员信息
                $salesArr = array(
                    'saler_id'      => $saler_id,
                    'saler_type'    => $saler_type,
                    'saler_name'    => $saler_name,
                );

                return $salesArr;
            }
        }

        return array();
    }

    /**
     *
     * 获取当前openid的分销员ID
     * @param $inter_id
     * @param $openid
     * @return mixed
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function _get_saler_id($inter_id, $openid)
    {
        $this->load->library('Soma/Api_idistribute');
        return $this->api_idistribute->get_saler_info($inter_id, $openid);
    }

    /**
     * @author zhangyi  <zhangyi@mofly.cn>
     * 新主题header，footer默认值
     */
    protected function initViewData()
    {
        $datas = [];
        $js_api_list = $menu_show_list = $menu_hide_list = '';
        $datas['wx_config'] = $this->_get_sign_package($this->inter_id);

        $datas['base_api_list'] = array('hideMenuItems', 'showMenuItems', 'onMenuShareTimeline', 'onMenuShareAppMessage');
        if (isset($datas['js_api_list'])) {
            $datas['js_api_list'] += $datas['base_api_list'];
        }
        else {
            $datas['js_api_list'] = $datas['base_api_list'];
        }
        $datas['js_api_list'] = "'" . implode("','", $datas['js_api_list']) . "'";

        //主动显示某些菜单
        if (!isset($datas['js_menu_show'])) {
            //$datas['js_menu_show'] = array('menuItem:setFont', 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:favorite', 'menuItem:copyUrl');
            $datas['js_menu_show'] = array(
                'menuItem:setFont',
                'menuItem:favorite',
            );
        }
        $datas['js_menu_show'] = "'" . implode("','", $datas['js_menu_show']) . "'";

        //主动隐藏某些菜单
        if (!isset($datas['js_menu_hide'])) {
            $datas['js_menu_hide'] = array(
                'menuItem:share:appMessage',
                'menuItem:share:timeline',
                'menuItem:share:email',
                'menuItem:copyUrl',
                'menuItem:originPage'
            );
        }
        $datas['js_menu_hide'] = "'" . implode("','", $datas['js_menu_hide']) . "'";


        if (!isset($datas['js_share_config'])) {
            $datas['js_share_config'] = false;
        }

        $datas['inter_id'] = $this->inter_id;
        $datas['business'] = $this->input->get_post('bsn', null, '');
        $datas['settlement'] = $this->input->get_post('stl', null, '');
        $datas['saler'] = $this->input->get_post('saler', null, '');
        $datas['fans_saler'] = $this->input->get_post('fans_saler', null, '');
        $datas['fans'] = $this->input->get_post('fans', null, '');
        $datas['token'] = [
            'name' => $this->security->get_csrf_token_name(),
            'value' => $this->security->get_csrf_hash()
        ];

        $cdn_url = $this->_match_url($this->module, $this->controller, $this->action);
        if ($cdn_url) {
            $datas['statistics_js'] = $this->statis_code;
        }else{
            $datas['statistics_js'] = $this->sign_update_code;
        }

        $datas['version'] = $this->version;
        $datas['path'] = $this->path;
        $datas['title'] = isset($this->headerDatas['title']) ? $this->headerDatas['title'] : '商城';
        $this->headerDatas = $datas;
        $this->footerDatas = $datas;
    }

    /**
     * @param array $arr
     * @author renshuai  <renshuai@jperation.cn>
     */
    protected function changeViewData(Array $arr)
    {
        if (!empty($arr)) {

            if (isset($arr['js_share_config'])  && is_array($arr['js_share_config'])) {
                $this->footerDatas['js_share_config'] = $arr['js_share_config'];
            }

            if (isset($arr['js_api_list'])  && is_array($arr['js_api_list'])) {
                $js_api_list = "'" . implode("','", $arr['js_api_list']) . "'";
                $this->footerDatas['js_api_list'] = $js_api_list;
            }

            if (isset($arr['js_menu_show'])  && is_array($arr['js_menu_show'])) {
                $js_menu_show = "'" . implode("','", $arr['js_menu_show']) . "'";
                $this->footerDatas['js_menu_show'] = $js_menu_show;
            }

            if(isset($arr['js_menu_hide'])  && is_array($arr['js_menu_hide'])) {
                $js_menu_hide = "'" . implode("','", $arr['js_menu_hide']) . "'";
                $this->footerDatas['js_menu_hide'] = $js_menu_hide;
            }
        }
    }

    /**
     * @param $file
     * @param array $datas
     * @author zhangyi  <zhangyi@mofly.cn>
     * 前后端分离的新view方法
     */
    protected function newThemeView($file, $datas = [])
    {
        $html = '';
        $path = 'soma' . DS;

        $this->changeViewData($datas);

        $view_file_path = $path . $this->theme . DS . $file;
        $html .= $this->load->view($path . $this->theme . DS . 'header', $this->headerDatas, true);
        $html .= $this->load->view($path . $this->theme . DS . $file, $datas, true);
        $html .= $this->load->view($path . $this->theme . DS . 'footer', $this->footerDatas, true);
        if (ENVIRONMENT != 'production' && $this->isNewTheme()) {
            $html = '<!-- 视图文件路径:' . VIEWPATH . $view_file_path . '.php -->' . $html;
        }

        echo $html;
    }
}
