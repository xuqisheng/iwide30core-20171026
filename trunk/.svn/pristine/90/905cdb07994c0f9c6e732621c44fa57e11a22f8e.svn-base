<?php
//error_reporting(E_ALL^E_NOTICE);
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Co_company extends MY_Admin_Cprice {
    protected $label_module = '协议价';
    protected $label_controller = '协议价';
    protected $label_action = '';
    function __construct() {
        parent::__construct ();
    }

    protected function main_model_name()
    {
        return 'company/Co_company_model';
    }

    public function co_list()
    {
        $inter_id = $this->session->get_admin_inter_id ();
        if ($inter_id == FULL_ACCESS)
            $filter = array ();
        else if ($inter_id)
            $filter = array (
                'inter_id' => $inter_id
            );
        else
            $filter = array (
                'inter_id' => 'deny'
            );
        $entity_id = $this->session->get_admin_hotels ();
        if (! empty ( $entity_id )) {
            // $filter['hotel_id']
        }
        // print_r($filter);die;

        /* 兼容grid变为ajax加载加这一段 */
        if (is_ajax_request ())
            // 处理ajax请求，参数规格不一样
        $get_filter = $this->input->post ();
        else
            $get_filter = $this->input->get ( 'filter' );

        if (! $get_filter)
            $get_filter = $this->input->get ( 'filter' );

        if (is_array ( $get_filter ))
            $filter = $get_filter + $filter;
        /* 兼容grid变为ajax加载加这一段 */

        $this->c_grid ( $filter,'','company_price' );

    }


    public function edit() {
        $this->label_action = '合作单位';
        $this->_init_breadcrumb ( $this->label_action );

        $model_name = $this->main_model_name ();
        $model = $this->_load_model ( $model_name );
        $id = intval ( $this->input->get ( 'ids' ) );
        $this->load->model ( 'company/Co_company_model' );
        if ($id) {
            // for edit page.
            // $model= $this->hotel_ext_model->load($id);
            $model = $model->load ( $id );
            $fields_config = $model->get_field_config ( 'form' );
            $detail_field = array ();
            if (count ( $detail_field ) > 0) {
                $detail_field = $detail_field [0] ['attr_value'];
            } else {
                $detail_field = '';
            }
        } else {
            // for add page.
            // $model= $this->hotel_ext_model->load($id);
            $model = $model->load ( $id );

            if (! $model)
                $model = $this->_load_model ();
            $fields_config = $model->get_field_config ( 'form' );


            $detail_field = '';
        }

        $view_params = array (
            'model' => $model,
            'fields_config' => $fields_config,
            'check_data' => FALSE,
            'detail_field' => $detail_field,
            'services' => '',
            'hotel_ser' => ''
        )

            // 'gallery'=> $gallery,
        ;
        $html = $this->_render_content ( $this->_load_view_file ( 'company_edit' ), $view_params, TRUE );

        echo $html;
    }


    public function edit_post() {

        $this->label_action = '信息维护';
        $this->_init_breadcrumb ( $this->label_action );

        $inter_id = $this->session->get_admin_inter_id ();

        $model_name = $this->main_model_name ();
        $model = $this->_load_model ( $model_name );
        $pk = $model->table_primary_key ();



        $this->load->library ( 'form_validation' );
        $post = $this->input->post ();


        $adminid = $this->session->get_admin_id ();

        $labels = $model->attribute_labels ();


        $base_rules = array (
            'company_name' => array (
                'field' => 'company_name',
                'label' => $labels ['company_name'],
                'rules' => 'trim|required'
            ),
            'company_id' => array (
                'field' => 'company_id',
                'label' => $labels ['company_id'],
                'rules' => 'trim'
            ),
//            'address' => array (
//                'field' => 'address',
//                'label' => $labels ['address'],
//                'rules' => 'trim|required'
//            ),
        );

        // 检测并上传文件。
        $post = $this->_do_upload ( $post, 'intro_img' );

        $adminid = $this->session->get_admin_id ();


        if (empty ( $post [$pk] )) {

            // add data.
            $this->form_validation->set_rules ( $base_rules );

            if ($this->form_validation->run () != FALSE) {
                $post ['add_time'] = date ( 'Y-m-d H:i:s' );
                $post ['inter_id'] = $inter_id;
                $post ['company_status'] = '1';

                $result = $model->m_sets ( $post )->m_save ( $post );

                $message = ($result) ? $this->session->put_success_msg ( '已新增数据！' ) : $this->session->put_notice_msg ( '此次数据保存失败！' );
                 $this->_log($model);

                $this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/co_list' ) );
            } else
                $model = $this->_load_model ();
        } else {

            $this->form_validation->set_rules ( $base_rules );
            if ($this->form_validation->run () != FALSE) {
                $post ['last_update_time'] = date ( 'Y-m-d H:i:s' );
                $post ['last_update_user'] = $adminid;

                $result = $model->load ( $post [$pk] )->m_sets ( $post )->m_save ( $post );
//                $model->save_services ( $result );
                $message = ($result) ? $this->session->put_success_msg ( '已保存数据！' ) : $this->session->put_notice_msg ( '此次数据修改失败！' );
                $this->_log ( $model );
                $this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/co_list' ) );
            } else
                $model = $model->load ( $post [$pk] );
        }

        // 验证失败的情况
        $validat_obj = _get_validation_object ();
        $message = $validat_obj->error_html ();
        // 页面没有发生跳转时用寄存器存储消息
        $this->session->put_error_msg ( $message, 'register' );

        $fields_config = $model->get_field_config ( 'form' );
        $view_params = array (
            'model' => $model,
            'fields_config' => $fields_config,
            'check_data' => TRUE
        );
        $html = $this->_render_content ( $this->_load_view_file ( 'edit' ), $view_params, TRUE );
        echo $html;
    }
}
