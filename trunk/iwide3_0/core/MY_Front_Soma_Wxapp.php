<?php
class MY_Front_Soma_Wxapp extends MY_Front_Wxapp {

    public $db_shard_config= array();
    public $current_inter_id= '';

    public $cache_timeout= '60';
    public $cache_redis= NULL;
    public $open_cache= FALSE;
    #public $open_cache= TRUE;
    public $open_cdn= TRUE;

    public $themeConfig= array();
    public $theme = 'default';
    public $statis_code = '';
    public $sign_update_code = '';

    public function __construct()
    {
        parent::__construct ();

        $_POST = $this->source['send_data'];
        $_GET = $this->source['send_data'];
        
        //初始化数据库分片配置
        if( $this->inter_id ){
            $this->load->model('soma/shard_config_model', 'model_shard_config');
            $this->current_inter_id= $this->inter_id;
            $this->db_shard_config= $this->model_shard_config->build_shard_config($this->inter_id);
            //print_r($this->db_shard_config);
        }
        
        //MYLOG::soma_tracker($this->inter_id,$this->openid);
        
        if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
            $success = Soma_base::inst()->check_cache_redis();
            if( !$success){
                //redis故障关闭cache
                $this->open_cache= FALSE;
            }
            $this->open_cache= TRUE;
            $this->open_cdn= TRUE;
            
        } else {
            $this->cache_timeout= 1;  
            $this->open_cache= TRUE;  //非生产环境自动关闭cache
            $this->open_cdn= FALSE;  //非生产环境自动关闭CDN
        }
        $this->open_cache= FALSE;
        //加载缓存，如果没有缓存不起作用跳过
        $params= $this->input->get();
        $this->_load_cache_html($this->inter_id, $this->module, $this->controller, $this->action, $params);

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $current_url= "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
       /* $sign_update_url= Soma_const_url::inst()->get_url('soma/api/get_sign_ajax');
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
EOF
;*/
        //theme
        $this->load->model('soma/Theme_config_model');
        $themeConfig = $this->Theme_config_model->get_using_theme($this->inter_id);
        if( $themeConfig ){
            $this->themeConfig = $themeConfig;
            $this->theme = $themeConfig['theme_path'];
            
            //把公众号配置的特殊信息放入配置
            $this->statis_code = $this->_get_statis_code($this->inter_id, $themeConfig);
        }

        //用于统计 例如：soma/package/index
        $segments = $this->uri->segments;
        $module = $segments[1];
        $controller = isset($segments[2]) ? $segments[2] : 'index';
        $action = isset($segments[3]) ? $segments[3] : 'index';

        if (!$this->session->userdata(session_id())) {
            $this->session->set_userdata(session_id(), "$module/$controller/$action");
        }

        // 建立openid映射信息
        // $this->_bulid_center_openid_map($this->inter_id, $this->openid);
    }

    //从配置中获取统计代码
    protected function _get_statis_code($inter_id, $themeConfig)
    {
        $disable_action= array(
            'package_pay', 'groupon_pay', 'killsec_pay',
        );
        if( in_array($this->action, $disable_action) ) 
            return '';
        
        if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ) {
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
            if( isset($themeConfig['statis_code']) ) {
                return $themeConfig['statis_code'];
            } else {
                $this->load->model('wx/publics_model');
                $public_info= $this->publics_model->get_public_by_id( $inter_id );
                if( !empty($public_info['statis_code']) ){
                    return $public_info['statis_code'];
                }
            }
            return '';
        }
    }
    
    /**
    ==基本类
    举报: "menuItem:exposeArticle"
    调整字体: "menuItem:setFont"
    日间模式: "menuItem:dayMode"
    夜间模式: "menuItem:nightMode"
    刷新: "menuItem:refresh"
    查看公众号（已添加）: "menuItem:profile"
    查看公众号（未添加）: "menuItem:addContact"
    ==传播类
    发送给朋友: "menuItem:share:appMessage"
    分享到朋友圈: "menuItem:share:timeline"
    分享到QQ: "menuItem:share:qq"
    分享到Weibo: "menuItem:share:weiboApp"
    收藏: "menuItem:favorite"
    分享到FB: "menuItem:share:facebook"
    分享到 QQ 空间/menuItem:share:QZone
    ==保护类
    编辑标签: "menuItem:editTag"
    删除: "menuItem:delete"
    复制链接: "menuItem:copyUrl"
    原网页: "menuItem:originPage"
    阅读模式: "menuItem:readMode"
    在QQ浏览器中打开: "menuItem:openWithQQBrowser"
    在Safari中打开: "menuItem:openWithSafari"
    邮件: "menuItem:share:email"
    一些特殊公众号: "menuItem:share:brand"
     */
    //展示为以后的皮肤做扩展
    //$pathArr = array('package','default')
//    protected function _view($file, $datas=array(),$pathArr = NULL )
    protected function _view($file, $datas=array())
    {
    	if(strpos($file, "header")){
    		return;
    	}
    	if(strpos($file, "footer")){
    		return;
    	}
    	$this->out_put_msg(1,'',$datas,$file);
    	return;
    	
        /*
         * js_api_list: eg: array('hideMenuItems', 'showMenuItems', 'onMenuShareTimeline', 'onMenuShareAppMessage' ); 一般不需要修改
         * js_menu_hide: eg: array( 'menuItem:setFont', 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:favorite', 'menuItem:copyUrl' ); 主动隐藏
         * *** 关注 js_menu_show: eg: array( 'menuItem:share:timeline', 'menuItem:favorite', 'menuItem:copyUrl' ); 主动显示
         * *** 关注 js_share_config: eg: array('title','desc','link','imgUrl')
         */
        $js_api_list= $menu_show_list= $menu_hide_list= '';
        $datas['wx_config'] = $this->_get_sign_package($this->inter_id);
        $datas['base_api_list'] = array('hideMenuItems', 'showMenuItems', 'onMenuShareTimeline', 'onMenuShareAppMessage' );
        if( isset($datas['js_api_list']) ) {
            $datas['js_api_list']+= $datas['base_api_list'];
        } else {
            $datas['js_api_list']= $datas['base_api_list'];
        }
        foreach ($datas['js_api_list'] as $v){
            $js_api_list.= "'{$v}',";
        }
        $datas['js_api_list']= substr($js_api_list, 0, -1);

        //统计代码
        $this->load->model("statistics/Statistics_model");
        $title = isset($datas['title'])?$datas['title']:"";
        $datas['statistics_js'] = '';//$this->Statistics_model->outputJs($this->inter_id,$this->openid,$title);

        //主动显示某些菜单
        if( !isset($datas['js_menu_show']) )
            $datas['js_menu_show']= array( 'menuItem:setFont', 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:favorite', 'menuItem:copyUrl' );
        foreach ($datas['js_menu_show'] as $v){
            $menu_show_list.= "'{$v}',";
        }
        $datas['js_menu_show']= substr($menu_show_list, 0, -1);

        //主动隐藏某些菜单
        if( !isset($datas['js_menu_hide']) )
            $datas['js_menu_hide']= array( 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:copyUrl', 'menuItem:share:email', 'menuItem:originPage' );
        foreach ($datas['js_menu_hide'] as $v){
            $menu_hide_list.= "'{$v}',";
        }
        $datas['js_menu_hide']= substr($menu_hide_list, 0, -1);

        if( !isset($datas['js_share_config']) )
            $datas['js_share_config']= FALSE;   //array('title','desc','link','imgUrl')

        $datas['uri']= array(
            'module'=> $this->module,
            'controller'=> $this->controller,
            'action'=> $this->action,
        );
        $datas['inter_id']= $this->inter_id;  //id
        $datas['openid']= $this->openid;

        $datas['business']= $this->input->get('bsn')? $this->input->get('bsn'): ($this->input->post('bsn')? $this->input->post('bsn'): '' ) ;
        $datas['settlement']= $this->input->get('stl')? $this->input->get('stl'): ($this->input->post('stl')? $this->input->post('stl'): '' ) ;
        $datas['saler']= $this->input->get('saler')? $this->input->get('saler'): ($this->input->post('saler')? $this->input->post('saler'): '' ) ;
        $datas['fans_saler']= $this->input->get('fans_saler')? $this->input->get('fans_saler'): ($this->input->post('fans_saler')? $this->input->post('fans_saler'): '' ) ;
        $datas['fans']= $this->input->get('fans')? $this->input->get('fans'): ($this->input->post('fans')? $this->input->post('fans'): '' ) ;
        $path= 'soma'. DS;
//        if(is_array($pathArr) && !empty($pathArr)){
//           foreach($pathArr as $v){
//               $path .= $v. Ds;
//           }
//        }

        if( !file_exists(VIEWPATH. $path. $this->theme. DS. $file. ".php") ){
            if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' 
                && file_exists(VIEWPATH. $path. 'mooncake'. DS. $file. ".php")) {
                $html= $this->load->view($path. 'mooncake'. DS. $file, $datas, TRUE);
            } else {
                $html= $this->load->view($path. 'default'. DS. $file, $datas, TRUE);
            }
        } else {
            $html= $this->load->view($path. $this->theme. DS. $file, $datas, TRUE);
        }

        //CDN URL 替换。
        $html= $this->_replace_cdn_url($html);

        $cdn_url= $this->_match_url($this->module, $this->controller, $this->action);
        if( $cdn_url ){
            // 替换为文件缓存
            
            $this->load->driver('cache');
            $params= $this->input->get();
            $key = md5($this->_cache_html_key($this->inter_id, $cdn_url, $params));

            if( substr($html, -6) =='header' ){
                $this->cache->file->save($key, $html, $this->cache_timeout);
            } else {
                $header= $this->cache->file->get($key);
                //通过替换加入统计js
                $html= str_replace( array('//[<sign_update_code>]', '</html>'), 
                    array("{$this->sign_update_code}\n", "{$this->statis_code}\n</html>"), 
                $html);
                $this->cache->file->save($key, $header. $html, $this->cache_timeout);
            }

            /*            
            if( !$this->cache_redis ){
                $cache= $this->_load_cache();
                $this->cache_redis= $cache->redis->redis_instance();
            }
            $params= $this->input->get();
            $cache_key= $this->_cache_html_key($this->inter_id, $cdn_url, $params);
            if( substr($html, -6) =='header' ){
                $this->cache_redis->setex($cache_key, $this->cache_timeout, $html );
            } else {
                $header= $this->cache_redis->get($cache_key);
                //通过替换加入统计js
                $html= str_replace( array('//[<sign_update_code>]', '</html>'), 
                    array("{$this->sign_update_code}\n", "{$this->statis_code}\n</html>"), 
                $html);
                $this->cache_redis->setex($cache_key, $this->cache_timeout, $header. $html );
            }
            */
        } else {
            //通过替换加入统计js
            $html= str_replace('</html>', "{$this->statis_code}\n</html>", $html);
        }
        echo $html;
    }
    
    //# Redis 缓存html start #########################################
    protected function _load_cache_html($inter_id, $module, $controller, $action, $params)
    {
        $cdn_url= $this->_match_url($module, $controller, $action);
        if( $cdn_url ){
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

            if($html) {
                die($html);
            }
        }
    }
    protected function _replace_cdn_url($html)
    {
       
                $search= array(
                    'http://file.iwide.cn/public',
                );
                $replace= array(
                    'http://7n.cdn.iwide.cn/public',
                );
          
            return str_replace($search, $replace, $html);

    }
    
    protected function _replace_cdn_url_json($html)
    {
    	if( $this->open_cdn ){
    		if( 1 ||  isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
    			$search= array(
    					'http:\\/\\/file.iwide.cn\\/public',
    			);
    			$replace= array(
    					'http:\\/\\/7n.cdn.iwide.cn\\/public',
    			);
    		} else {
    			$search= array(
    					'http://30.iwide.cn:821/public',
    			);
    			$replace= array(
    					'http://soma.cdn.iwide.cn/public',
    			);
    		}
    		return str_replace($search, $replace, $html);
    
    	} else {
    		return $html;
    	}
    }
    protected function _cache_html_key($inter_id, $cdn_url, $params)
    {
        if( isset($params['id']) ) unset($params['id']);
        if( isset($params['openid']) ) unset($params['openid']);
        $cache_key= 'SOMA_HTML:'. $inter_id. ':'. $cdn_url;
        $param_key= implode('_', $params);
        if( count($params)>0 )
            return $cache_key. ':'. $param_key;
        else
            return $cache_key;
    }
    protected function _match_url($module, $controller, $action)
    {
        if( $this->open_cache ){
            $url= strtolower($module). '_'. strtolower($controller). '_'. strtolower($action);
            $matchs= array(
                'soma_package_index',
                'soma_package_package_list',
                'soma_package_package_detail',
                'soma_package_category_list',
                'soma_package_success',
                'soma_package_mooncake_list',
            );
            if( in_array($url, $matchs) ) return $url;
        }
        return FALSE;
    }
    //# Redis 缓存html end #########################################


    protected function _get_sign_package($inter_id, $url='')
    {
        $this->load->helper('common');
        $this->load->model('wx/publics_model', 'publics');
        $this->load->model('wx/access_token_model');
        $jsapiTicket = $this->access_token_model->get_api_ticket( $inter_id );
        //$jsapiTicket = $this->access_token_model->get_api_ticket($this->session->userdata('inter_id'), $this->openid);

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        if(!$url)
            $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = createNonceStr();
        $public = $this->publics->get_public_by_id( $inter_id );

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
            "appId"     => $public['app_id'],
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    /**
     * 带智能检测用户关注情况，视情况进行高级授权跳转
     */
    public function _get_wx_userinfo()
    {
        $this->load->model('wx/publics_model');
        $fans= $this->publics_model->get_fans_info( $this->openid );

        $this->write_log("_get_wx_userinfo():fans : " . var_export($fans, true));

        if( !$fans || empty($fans['nickname']) ){
            $userinfo= $this->publics_model->get_wxuser_info($this->inter_id, $this->openid );

            $this->write_log("_get_wx_userinfo():userinfo : " . var_export($userinfo, true));

            if( isset($userinfo['subscribe']) && $userinfo['subscribe']==0 ){
// return array();//BUG未解决先返回空数据

                    //微信返回的信息显示没有关注，则进行高级授权验证
                if( isset($_SERVER['SERVER_SOFTWARE']) && $_SERVER['SERVER_SOFTWARE']=='nginx' )
                    $refer =  'http://'. $_SERVER ['HTTP_HOST']. $_SERVER ['REQUEST_URI'] ;
                else
                    $refer =  'http://'. $_SERVER ['SERVER_NAME']. $_SERVER ['REQUEST_URI'] ;
            
                $inter_id= $this->inter_id;
                $url = front_site_url($inter_id, FALSE);
                if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ) {
                    // 月饼说跳转过来，如果存在refer这个参数，证明这是第二次跳转，不进行封装
                    $r = $this->input->get('refer');
                    $this->write_log("_get_wx_userinfo(): get_refer : " . $r);
                    if(!$r) {
                        $refer = base64_url_encode($refer);
                    } else {
                        $refer = $r;
                    }
                    $refer = $url . "/index.php/soma/api/mooncake_decode_cb/?refer=".$refer;
                }
                
                $refer= urlencode($refer);

                $redirect_url = $url . "/index.php/Public_oauth/index?scope=snsapi_userinfo&id={$inter_id}&refer={$refer}";

                $this->write_log('_get_wx_userinfo($redirect_url) : ' . $redirect_url);

                redirect(  $redirect_url  );

            } else {
                $this->publics_model->update_wxuser_info($this->inter_id, $this->openid );
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
    protected function _load_cache( $name='Cache' )
    {
        $success = Soma_base::inst()->check_cache_redis();
        if( !$success){
            //redis故障关闭cache
            Soma_base::inst()->show_exception('当前访问用户过多，请稍后再试！', TRUE );
        }
        if(!$name || $name=='cache') //不能为小写cache
        $name='Cache';

        $this->load->driver('cache',
            array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'soma_'),
            $name
        );
        return $this->$name;
    }

    /**
     * 对于需要跳转站外域名获取code的，根据inter_id 做区分跳转
     * @param unknown $inter_id
     * @param unknown $refer
     */
    protected function _wx_redirect($inter_id, $refer)
    {
        if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ){
            //月饼说专用授权跳转
            $this->load->model('wx/Publics_model');
            $public=$this->Publics_model->get_public_by_id($this->input->get('id'));

            if (! $this->input->get ( 'code' )) {
                // 将refer_url转码，以免被微信服务器对url参数进行拆解
                $refer_url = base64_url_encode($refer);

                $inter_id = $this->input->get('id');
                $url = front_site_url($inter_id, FALSE);

                $scope = 'snsapi_base';
                if ($this->input->get ( 'scope' )) {
                    $scope = $this->input->get ( 'scope' );
                }
                $url .= "/index.php/soma/api/mooncake_decode_cb/?refer=".$refer_url;

                $this->write_log('Base refer url :' .$url."\n");
                $url = urlencode ($url);

                $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $public ['app_id'] 
                    . "&redirect_uri=$url&response_type=code&scope=$scope&state=STATE#wechat_redirect";

                $this->write_log('Front Soma get Code Url :' .$url ."\n");
                redirect ( $url );
                exit();
                
            } else {
                //有code参数，进行数据存储。
                $code = $this->input->get ( 'code' );
                // $redirect_uri = urldecode($this->input->get ( 'refer' ));
                $redirect_uri = base64_url_decode($this->input->get ( 'refer' ));
                $this->write_log('Code :' .$code ."\n"."redirect_uri : " .$redirect_uri ."\n");
                $inter_id = $this->input->get('id');
                $this->write_log("Get Params :" . json_encode($_GET));

                $result = $this->_auth_res($this->input->get ( 'code' ),$this->input->get('id'));
                $result = json_decode ( $result, TRUE );
                $openid = isset( $result ['openid'] ) ? $result ['openid'] : '';
                $this->session->set_userdata ( array ( $this->session->userdata ( 'inter_id' ) . 'openid' => $openid ) );
                if ($openid) {
                    $accessstoken = null;
                    if ($this->input->get ( 'scope' )) {
                        $accessstoken = $result ['access_token'];
                    }
                    $this->Publics_model->update_wxuser_info ( $this->session->userdata ( 'inter_id' ), $openid, $accessstoken );
                }

                redirect ( $redirect_uri );
                exit();
            }
            
        } else {
            //正常URL跳转
            redirect( site_url ( 'public_oauth/index' ) . '?id=' . $inter_id. '&refer='. urlencode($refer) );
        }
    }


    /**
     * 网页授权通过code获取用户信息
     * @param String code
     * @param String 公众号识别码
     * @return JSON 请求微信返回结果
     */
    private function _auth_res($code,$inter_id)
    {
        $this->load->model('wx/Publics_model');
        $public=$this->Publics_model->get_public_by_id($inter_id);
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $public ['app_id'] 
            . "&secret=" . $public ['app_secret'] . "&code=$code&grant_type=authorization_code";

        $this->load->helper('common');
        return doCurlGetRequest($url);
    }

    /**
     * 获取中心平台公众号ID
     */
    protected function get_center_inter_id() {
        if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
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
     * @param      string  $inter_id  公众号ID
     * @param      string  $openid    openid
     */
    protected function _bulid_center_openid_map($inter_id, $openid) {
        $this->load->model('soma/center_openid_map_model', 'om_model');
        // 中心平台的openid不需要进行跳转，直接写入即可
        if($this->get_center_inter_id() != $this->inter_id) {
            $this->load->model('soma/center_openid_map_model', 'om_model');
            $center_info = $this->om_model->get_center_openid_info($inter_id, $openid);
            $api_record = $this->session->userdata('bulid_openid_map_record');  

            // 查找不到中心平台openid信息,构造页面跳转逻
            // 查找有openid映射记录或本次session有调用记录，不跳转,以免出现死循环。
            if(!$api_record && count($center_info) <= 0) {
                $this->session->set_userdata(array('bulid_openid_map_record' => 1));
                $origin = 'http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
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
            $hotel_data['openid']   = $openid;
            $_fmt_data = $this->om_model->format_map_record_data($this->inter_id, $this->openid, $hotel_data);
            $result = array('success' => false, 'msg' => '写入数据失败');
            if($this->om_model->data_validation($_fmt_data)) {
                if($this->om_model->save_map_record($_fmt_data)) {
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
    public function build_openid_map_notify() {

        $result = $this->input->get('res', true);
        $extra = $this->input->get('extra', true);
        $this->write_log(var_export($result, true) . var_export($extra, true), 'soma' . DS . 'center');

        $this->session->set_userdata(array('bulid_openid_map_record' => 1));
        $request_url = base64_url_decode($extra['origin_url']);
        redirect($request_url);

    }

    //日志写入
    public function write_log( $content, $dir = 'mooncake')
    {
        $file= date('Y-m-d'). '.txt';
        //echo $tmpfile;die;
        $path= APPPATH.'logs'.DS. $dir . DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $fp = fopen( $path. $file, 'a');

        $CI = & get_instance();
        $ip= $CI->input->ip_address();
        $content= str_repeat('-', 40). "\n[". date('Y-m-d H:i:s'). ']'
            ."\n". $ip. "\n". $content. "\n";
        fwrite($fp, $content);
        fclose($fp);
    }

    /**
     * 拉取商品列表HTML
     */
    public function get_page_block( $uri )
    {
        $current_url= $uri;
        $filter= array('inter_id'=> $this->inter_id);
        $this->load->model('soma/Cms_block_model');
        $this->load->model('soma/Product_package_model');
        $pids= $this->Cms_block_model->show_in_page($current_url, $filter);
        
        $products = array();
        if( $pids ){
            $products= $this->Product_package_model->get_product_package_by_ids($pids, $this->inter_id);
        }

        //获取酒店城市列表
        $this->load->model('hotel/hotel_model','HotelModel');
        $params = array(
            'inter_id'  => $this->inter_id
        );
        $HotelModel = $this->HotelModel;

        foreach($products as $k => $p){
            $productCites = $HotelModel->get_hotel_hash(array('inter_id'=>$this->inter_id,'hotel_id'=> $p['hotel_id']),array('city'),'array');
            $products[$k]['city'] = isset( $productCites[0]['city'] ) ? $productCites[0]['city'] : NULL;
        }

        //var_dump($pids);die;
        $html = '';
        if( $pids && $products && count($products)>0 ){
            if( $this->theme == 'default' ){

                $html= '<div id="load_page_block" class="tp_list bgcolor_fff border martop"><div style="padding-bottom:3%;padding-left:3%; margin-bottom:3%" class="border_bottom h2">其他用户还看了</div>';
                foreach ($products as $k=>$v ){
                    $url= Soma_const_url::inst()->get_url('soma/package/package_detail', array('id'=>$this->inter_id, 'pid'=> $v['product_id']) );
                    $can_gift= ($v['can_gift']== Product_package_model::CAN_T)? '<div class="fn"><span>可赠好友</span></div>': '';
                    $default_pic= base_url('public/soma/images/default.jpg');
                    $html.= 
"<a href='{$url}' class='item'>
  <div class='img'><img src='{$v['face_img']}' />{$can_gift}</div>
  <p class='txtclip'>{$v['name']}</p>
  <div class='foot h2'>
      <p class='color_fff m_bg tp_price'>
        <span>惊喜价</span>
      <span class='y'>{$v['price_package']}</span>
          <span class='m_bg2'>去购买<em class='iconfont'>&#xe61b;</em></span>
      </p>
      <p class='tp_local txtclip'>{$v['city']}</p>
  </div>
</a>";
                }
                $html.= '</div>';

            } elseif( $this->theme == 'v1' ){
                $is_odd= ( count($products) % 2 )>0;
                if( $is_odd ) array_pop($products);
                
                $html= '<link href="'.base_url("public/soma/v1/v1.css"). config_item("css_debug").'" rel="stylesheet">
                <div id="load_page_block" class="tp_list bgcolor_fff border martop"><div style="padding-bottom:3%;padding-left:3%; margin-bottom:3%" class="border_bottom h2">其他用户还看了</div>';
                foreach ($products as $k=>$v ){
                    $url= Soma_const_url::inst()->get_url('soma/package/package_detail', array('id'=>$this->inter_id, 'pid'=> $v['product_id']) );
                    $default_pic= base_url('public/soma/images/default.jpg');
                    $html.= 
"<a href='{$url}' class='item bg_fff'>
  <div class='img'>
      <img src='{$v['face_img']}' />
  </div>
  <p class='h3 color_888'>{$v['name']}</p>
  <p class='item_foot'>惊喜价<em>|</em><span class='color_main y'>{$v['price_package']}</span></p>
</a>";
                }
                $html.= '</div>';
            }
            
        }

        $html = '<div id="load_page_block" >'.$html.'</div>';
        return $html;
    }
    
}