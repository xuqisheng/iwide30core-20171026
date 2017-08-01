<?php
/**
 * User: daikanwu
 * Date: 2017-7-6
 * Time: 17:14
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Express extends MY_Admin_Soma {

    /**
     * 列表页面
     * @author daikanwu <daikanwu@jperation.com>
     *
     */
    public function order_list()
    {
        $html= $this->_render_content($this->_load_view_file('order_list'));
        echo $html;
    }

    /**
     * 导入页面
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function batch()
    {
        $html= $this->_render_content($this->_load_view_file('batch'));
        echo $html;
    }

    /**
     * 物流工具首页
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function express_tool()
    {
        $html= $this->_render_content($this->_load_view_file('express_tool'));
        echo $html;
    }

    /**
     * 顺丰列表
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function shunfeng_list()
    {
        $html= $this->_render_content($this->_load_view_file('shunfeng_list'));
        echo $html;
    }

}