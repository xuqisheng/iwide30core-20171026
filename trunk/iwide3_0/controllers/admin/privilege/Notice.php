<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notice extends MY_Admin_Priv
{

    protected $label_module = NAV_PRIVILEGE;        //统一在 constants.php 定义
    protected $label_controller = '公告列表';        //在文件定义
    protected $label_action = '';                //在方法中定义


    /**
     * 查询的模块
     * @see Priv_notice
     * @return string
     */
    protected function main_model_name()
    {
        return 'core/priv_notice';
    }

    /**
     * 显示后台菜单左栏
     * @deprecated
     * @param String $type
     */
    public function _show_menu_html()
    {
        return $this->_ajax_menu('html');
    }

    /**
     * @deprecated json返回左栏菜单
     */
    public function _show_menu_json()
    {
        return $this->_ajax_menu('json');
    }

    protected function _ajax_menu($type = 'html')
    {
        $menu = $this->_load_menu();
        if ($type == 'json') {
            echo EA_block_admin::inst()->json_menu($menu);;

        } else {
            $this->_load_view($this->priv_dir . '/left', array('menu' => $menu));
        }
    }

    public function grid()
    {
        $this->_grid('', array(
            'is_edit' => $this->is_super_admin(), // 是否超管
        ));
    }

    /**
     * 新增 || 修改
     */
    public function edit_post()
    {
        if ($this->is_super_admin() === false) {
            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
        }

        $this->label_action = '信息维护';
        $this->_init_breadcrumb($this->label_action);

        $model_name = $this->main_model_name();
        $model = $this->_load_model($model_name);
        $pk = $model->table_primary_key();

        $this->load->library('form_validation');
        $post = $this->input->post();

        if (empty($post[$pk])) { // 新增
            $post['create_time'] = date('Y-m-d H:i:s');
            $post['ymd'] = date('Y-m-d');
            $post['create_by'] = '';

            $_run_msg_success = '已新增数据！';
            $_run_msg_error = '此次数据保存失败！';
        } else { // 修改
            $post['update_time'] = date('Y-m-d H:i:s');
            $post['update_by'] = '';

            $model = $model->load($post[$pk]);

            $_run_msg_success = '已保存数据！';
            $_run_msg_error = '此次数据修改失败！';
        }

        $base_rules = array(
            'title' => array(
                'field' => 'title',
                'label' => '公告标题',
                'rules' => 'trim|required',
            ),
            'content' => array(
                'field' => 'content',
                'label' => '公告内容',
                'rules' => 'trim|required',
            ),
            'file_url' => array(
                'field' => 'file_url',
                'label' => '操作手册',
                'rules' => 'trim|required',
            ),
            'file_name' => array(
                'field' => 'file_name',
                'label' => '操作手册',
                'rules' => 'trim|required',
            ),
        );
        $this->form_validation->set_rules($base_rules);


        if ($this->form_validation->run() != FALSE) { // 验证成功
            $post['content'] = html_escape($post['content']);
            $result = $model->m_sets($post)->m_save();
            $message = ($result) ? $_run_msg_success : $_run_msg_error;
            $this->_log($model);
            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
        }

        //验证失败的情况
        $validat_obj = _get_validation_object();

        $message = $validat_obj->error_html();
        //页面没有发生跳转时用寄存器存储消息
        $this->session->put_error_msg($message, 'register');

        $fields_config = $model->get_field_config('form');


        $view_params = array(
            'model' => $model,
            'fields_config' => $fields_config,
            'check_data' => TRUE,
        );

        $html = $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
        echo $html;
    }

    /**
     * 是否超管
     * @return bool
     */
    private function is_super_admin()
    {
        if (!$this->session) {
            return false;
        }
        if ($this->session->get_admin_inter_id() == FULL_ACCESS) {
            return true;
        }
        return false;
    }

    /**
     * 详情
     */
    public function detail()
    {
        $this->label_action = '公告详情';
        $id = intval($this->input->get('ids'));
        if (!$id) {
            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
        }
        $model_name = $this->main_model_name();
        $model = $this->_load_model($model_name);
        $model = $model->load($id);
        $view_params = array(
            'model' => $model,
            'fields_config' => [],
            'check_data' => TRUE,
        );
        $html = $this->_render_content($this->_load_view_file('detail'), $view_params, TRUE);
        echo $html;
    }

    /**
     * 远程文件下载，并重命名
     */
    public function download()
    {
        $downfile = function ($file_url, $file_name) {
            ob_start();
            header("Content-type:  application/octet-stream ");
            header("Accept-Ranges:  bytes ");
            header("Content-Disposition:  attachment;  filename= {$file_name}");
            $size = readfile($file_url);
            header("Accept-Length: " . $size);
        };
        $file_url = $this->input->get('url');
        $file_name = $this->input->get('name');
        $downfile($file_url, $file_name);
    }

}
