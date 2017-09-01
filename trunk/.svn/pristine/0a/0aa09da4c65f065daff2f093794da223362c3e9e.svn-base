<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
if ($this->form_validation->run('signup') == FALSE) {
    $this->load->view('myform');
} else {
    $this->load->view('formsuccess');
}
 */
$config = array(
    'core_priv_node' => array(
        array(
            'field' => 'module',
            'label' => '所属模块',   //用在错误信息提示中文名
            'rules' => 'trim|required',
        ),
        array(
            'field' => 'project',
            'label' => '菜单分组',
            'rules' => 'trim|required',
        ),
        array(
            'field' => 'p_href',
            'label' => 'Href属性',
            'rules' => 'trim|required',
        ),
        array(
            'field' => 'p_label',
            'label' => 'Label属性',
            'rules' => 'trim|required',
        ),
        array(
            'field' => 'p_icon',
            'label' => 'Icon属性',
            'rules' => 'trim|required',
        ),
    ),
    
    'core_priv_admin' => array(), //需要特别定制的验证，定义在controller中
    'core_priv_admin_role' => array(),
    
);
$config['error_prefix'] = "<span class='glyphicon form-control-feedback glyphicon-remove-sign ' aria-hidden='true'></span><code>";
$config['error_suffix'] = '</code>';



