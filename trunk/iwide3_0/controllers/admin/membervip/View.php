<?php
use App\services\member\admin\ViewService;
defined('BASEPATH') OR exit('No direct script access allowed');
// +----------------------------------------------------------------------
// | Email: <septet-l@outlook.com>
// +----------------------------------------------------------------------
// | Author: liwensong
// +----------------------------------------------------------------------
// | Version: 4.0
// +----------------------------------------------------------------------
// | View 会员显示配置
// +----------------------------------------------------------------------
class View extends MY_Admin
{
    public function index()
    {
        $data['data'] = array();
        $this->load->helper('member_helper');
        $inter_id = $this->session->get_admin_inter_id();
        if(!check_separate_backend_frontend($inter_id)){
            $data = ViewService::getInstance()->index($inter_id);
        }
        $this->_render_content($this->_load_view_file($this->action), $data['data'], false);
    }

    //皮肤配置
    public function skin()
    {
        $data['data'] = array();
        $this->load->helper('member_helper');
        $inter_id = $this->session->get_admin_inter_id();
        if(!check_separate_backend_frontend($inter_id)){
            $data = ViewService::getInstance()->skin($inter_id);
        }
        $this->_render_content($this->_load_view_file($this->action), $data['data'], false);
    }
}