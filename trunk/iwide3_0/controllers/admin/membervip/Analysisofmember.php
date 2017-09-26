<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	购卡
*	@author Frandon
*	@time 四月十一号
*	@version www.iwide.cn
*	@
*/
class Analysisofmember extends MY_Admin_Api
{


	//
	public function index(){

        $view_params = array();
        $html = $this->_render_content($this->_load_view_file('index'), $view_params, TRUE);
        echo $html;
	}

    //注册分销
    public function reg_distribution_statements(){
        $view_params = array();
        $html = $this->_render_content($this->_load_view_file('reg_distribution_statements'), $view_params, TRUE);
        echo $html;
    }

    //购卡分销
    public function deposit_distribution_statements(){
        $view_params = array();
        $html = $this->_render_content($this->_load_view_file('deposit_distribution_statements'), $view_params, TRUE);
        echo $html;
    }


    //粉丝分析
    public function fans_analysis(){
        $view_params = array();
        $html = $this->_render_content($this->_load_view_file('fans_analysis'), $view_params, TRUE);
        echo $html;
    }
}
?>