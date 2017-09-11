<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/***
 * 礼包派送 Controller
 * Created by Wuqd on.
 * Created Time 2017-09-03
 */


class Gift_delivery extends MY_Admin_Soma{


    /***
     * 后台礼包首页路由
     */
    public function gift_index(){

        $this->_render_content($this->_load_view_file('gift_index'), [], false);
    }




}













