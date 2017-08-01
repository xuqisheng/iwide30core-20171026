<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Label extends MY_Admin {
    protected $label_module = NAV_HOTEL;
    protected $label_controller = '酒店标签';
    protected $label_action = '';
    function __construct() {
        parent::__construct ();
        $this->inter_id = $this->session->get_admin_inter_id ();
        $this->module = 'hotel';
        $this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
        $this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
        // $this->output->enable_profiler ( true );
    }
    protected function main_model_name() {
        return 'hotel/Label_model';
    }
    protected function main_model() {
        if (! isset ( $this->m_model )) {
            $this->load->model ( $this->main_model_name (), 'm_model' );
        }
        return $this->m_model;
    }
    public function index() {
        $data = $this->common_data;
        $model = $this->main_model ();
        $data ['fields_config'] = $model->type_fields_config ();
        $data ['list'] = $model->get_label_types ( $this->inter_id, 'room' );
        $this->_render_content ( $this->_load_view_file ( 'index' ), $data, false );
    }
    public function edit() {
        $data = $this->common_data;
        $model = $this->main_model ();
        $type_id = $this->input->get ( 'type' );
        if (! empty ( $type_id )) {
            $data ['list'] = $model->get_label_type ( $this->inter_id, $type_id );
            if (empty ( $data ['list'] )) {
                redirect ( site_url ( 'hotel/tag/index' ) );
            }
        } else {
            $data ['list'] = $model->type_table_fields ();
        }
        $data ['type_desc'] = $model::$label_types;
        $this->_render_content ( $this->_load_view_file ( 'edit' ), $data, FALSE );
    }
    public function edit_post() {
        $model = $this->main_model ();
        $type_id = $this->input->post ( 'type_id' );
        $name = $this->input->post ( 'label_name' );
        $status = $this->input->post ( 'status' );
        $type = $this->input->post ( 'label_type' );
        
        if (! empty ( $name )) {
            $data ['label_name'] = htmlspecialchars ( $name );
            $data ['type_id'] = $type_id;
            $data ['type'] = $type;
            $data ['status'] = intval ( $status ) == 1 ? 1 : 2;
            $data ['sort'] = intval ( $this->input->post ( 'sort' ) );
            if (! empty ( $type_id )) {
                $model->save_type ( $this->inter_id, $data );
            } else {
                $model->save_type ( $this->inter_id, $data, 'add' );
            }
        }
        redirect ( site_url ( 'hotel/label/index' ) );
    }
    public function room_labels() {
        $data = $this->common_data;
        $model = $this->main_model ();
        $this->load->model ( 'hotel/Hotel_model' );
        $entity_id = $this->session->get_admin_hotels ();
        if (! empty ( $entity_id )) {
            $data ['hotels'] = $this->Hotel_model->get_hotel_by_ids ( $this->inter_id, $entity_id );
        } else {
            $data ['hotels'] = $this->Hotel_model->get_all_hotels ( $this->inter_id, 1 );
        }
        $data ['types'] = $model->get_label_types ( $this->inter_id, 'room' );
        empty ( $data ['types'] ) or $data ['labels'] = $model->type_labels_check ( $this->inter_id, array_column ( $data ['types'], 'type_id' ), 'room', $entity_id );
        $this->_render_content ( $this->_load_view_file ( 'room_labels' ), $data, FALSE );
    }
    public function room_item_save() {
        $model = $this->main_model ();
        $data = $this->input->post ();
        $this->load->helper ( 'array' );
        $data = jqjson2arr ( json_decode ( $data ['datas'], TRUE ) );
        $info = array (
                'status' => 2,
                'message' => 'error' 
        );
        $item = array ();
        $label_types = array ();
        if (! empty ( $data ['label_types'] )) {
            foreach ( $data ['label_types'] as $lt ) {
                $tmp_label = explode ( '|', $lt ); // hotel_id|room_id|type_id
                $label_types [$tmp_label [0]] [$tmp_label [1]] [$tmp_label [2]] = 1;
            }
        }
        $entity_id = $this->session->get_admin_hotels ();
        $this->load->model ( 'hotel/Hotel_model' );
        if (! empty ( $entity_id )) {
            $hotels = $this->Hotel_model->get_hotel_by_ids ( $this->inter_id, $entity_id );
        } else {
            $hotels = $this->Hotel_model->get_all_hotels ( $this->inter_id );
        }
        $data ['types'] = $model->get_label_types ( $this->inter_id, 'room' );
        if ($model->update_label_item ( $this->inter_id, $label_types, array_column ( $data ['types'], 'type_id' ), 'room', array_column ( $hotels, 'hotel_id' ) )) {
            $info ['status'] = 1;
            $info ['message'] = '保存成功';
        } else {
            $info ['message'] = '保存失败';
        }
        echo json_encode ( $info );
        exit ();
    }
}
