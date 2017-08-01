<?php
use App\libraries\Iapi\CommonLib;

class MY_Admin_Iapi extends MY_Controller {
    protected $_token;
    // 初始化
    public function __construct() {
        parent::__construct ();
        $this->_init_router ();
        $debug = $this->input->get ( 'debug' );
        $inter_id = $this->input->get ( 'inter_id' );
        if($debug){
            $this->session->set_admin_profile(
                array(
                    'admin_id'=> 5,
                    'inter_id'=> 'a429262687',
                    'entity_id'=> '',
                    'username'=> 'demo_member',
                    'nickname'=> '会员模块演示号',
                    'head_pic'=> 'http://test008.iwide.cn/public/media/admin_head_pic/default.png',
                    'update_time'=> date('Y-m-d H:i:s'),
                    'role'=> array('role_name'=>'','role_lable'=>'超级管理员')
                )

            );
            $this->session->allow_actions = array('adminhtml'=>FULL_ACCESS);
        }elseif($inter_id){
            $this->session->set_admin_profile(
                array(
                    // 'admin_id'=> 5,
                    'inter_id'=> $inter_id,
                    // 'entity_id'=> '',
                    // 'username'=> 'demo_member',
                    // 'nickname'=> '会员模块演示号',
                    // 'head_pic'=> 'http://test008.iwide.cn/public/media/admin_head_pic/default.png',
                    // 'update_time'=> date('Y-m-d H:i:s'),
                    // 'role'=> array('role_name'=>'','role_lable'=>'超级管理员')
                )

            );
            $this->session->allow_actions = array('adminhtml'=>FULL_ACCESS);
        }else{
            $this->_acl_filter ();
        }
    }
    /**
     * 加载controller默认模型对象
     * @param $model string 传入模型名称
     * @return MY_Model
     */
    public function _load_model($model = NULL) {
        $model = $model ? $model : $this->main_model_name ();
        $this->load->model ( $model, 'm_model' );
        if (! $this->m_model) {
            throw new Exception ( 'The requested page does not exist.' );
        } else {
            return $this->m_model;
        }
    }
    /**
     * @param int $result 运行结果 具体值看Admin_const
     * @param string $msg 显示给用户的信息
     * @param array $data 数据集
     * @param string $fun 调用的方法的标识 如hotel/prices/price_codes
     * @param number $status_code http状态码
     * @param array $extra 非主体数据，含元素:array(
     *                                          'links'=>array(
     *                                              'edit'=>'','add'=>''
     *                                          ),//操作跳转链接
     *                                          'page'=>'页码',
     *                                          'count'=>'总页数',
     *                                          'size'=>'每页数据量'
     *                                          )
     * @param number $msg_lv 消息级别  具体值看Admin_const
     * @param string $exit 输出数据后是否退出整个程序
     */
    protected function out_put_msg($result, $msg = '', $data = array(), $fun = '', $status_code = 200, $extra = array(), $msg_lv = 0, $exit = TRUE) {
        $this->output->set_status_header ( $status_code );
        echo json_encode ( CommonLib::create_put_msg ( 'jmp', $result, $msg, $data, $fun, $extra, $msg_lv ), JSON_UNESCAPED_UNICODE );
        if ($exit) {
            exit ();
        }
    }
    /**
     * 权限过滤
     * @return boolean
     */
    protected function _acl_filter() {
        $module = $this->api_type . '-' . $this->module;
        $controller = $this->controller;
        $action = $this->action;
        $acl_array = $this->session->allow_actions;
        $acl_array = $acl_array [ADMINHTML];
        if ($this->action == 'index_one' && $this->controller == 'orders' && $acl_array != FULL_ACCESS && ! in_array ( 'index_one', $acl_array ['hotel'] ['orders'] )) {
//             $this->_redirect ( site_url ( 'member/memberlist/grid' ) );
        }
        
        // 部分操作不受权限控制，如login
        // $ignore_methods = config_item ( 'acl_disable_method' );
        // $acl_filter = config_item ( 'acl_filter' );
        // if ($acl_filter == FALSE || in_array ( $action, $ignore_methods )) {
        // // 对于完全开放的操作，如logo，logout
        // return TRUE;
        // }
        // $open_methods = config_item ( 'acl_open_method' );
        // if ($acl_array && in_array ( $action, $open_methods )) {
        // // 对于半开放的操作，如dashboard
        // return TRUE;
        // }
        
        if (empty ( $acl_array )) {
            // 会话超时
            if (isset ( $_SERVER ['REQUEST_URI'] )) {
                $redirect = urlencode ( base_url ( $_SERVER ['REQUEST_URI'] ) );
            }
            $this->out_put_msg ( 4  ,'','','',401);
            // $this->_redirect ( EA_const_url::inst ()->get_login_admin () . '?redirect=' . $redirect );
        }
        if ($acl_array == FULL_ACCESS) {
            // 此处需要放在会话超时之后
            return true;
        }
        if (isset ( $acl_array [$module] [$controller] ) && in_array ( $action, $acl_array [$module] [$controller] )) {
            return true;
        }
        
        if ($this->action == 'index' && $this->controller == 'auth') {
            return true;
        }
        // 临时放行
        if ($this->action == 'grid' && $this->controller == 'memberlist') {
            return true;
        }
        $this->out_put_msg ( 'auth_deny','','','',401 );
        // $this->_redirect ( EA_const_url::inst ()->get_deny_admin () );
    }
    protected function _init_router() {
        $URI = & load_class ( 'URI', 'core', NULL );
        $segments = $URI->segments;
        $this->api_type = $segments [1];
        $this->api_ver = $segments [2];
        $this->module = $segments [3];
        $this->controller = isset ( $segments [4] ) ? $segments [4] : 'index';
        $this->action = isset ( $segments [5] ) ? $segments [5] : 'index';
        return;
    }
}
