<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Report extends MY_Admin
{
    protected $admin_profile;

    public function __construct()
    {
        parent::__construct();
        $this->admin_profile = $this->session->userdata('admin_profile');
        $this->load->helper('appointment');
    }


    public function wx_article_total(){

        $param = request();
        $return = array(
            'param'      => $param,
        );

        echo $this->_render_content($this->_load_view_file('wx_article_total'), $return, TRUE);

    }
}