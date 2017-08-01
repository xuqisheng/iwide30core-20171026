<?php
// +----------------------------------------------------------------------
// | 优惠发放任务
// +----------------------------------------------------------------------
// | Author: liwensong <septet-l@outlook.com>
// +----------------------------------------------------------------------
// Membertask.php 2017-06-16
// +----------------------------------------------------------------------
defined('BASEPATH') or exit('No direct script access allowed');
class Membertask extends MY_Admin_Member{
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $assign_params = array();
        $this->_render_content($this->_load_view_file('index'), $assign_params, false);
    }

    public function item(){
        $assign_params = array();
        $this->_render_content($this->_load_view_file('item'), $assign_params, false);
    }

    public function create(){
        $assign_params = array();
        $this->_render_content($this->_load_view_file('create'), $assign_params, false);
    }
}