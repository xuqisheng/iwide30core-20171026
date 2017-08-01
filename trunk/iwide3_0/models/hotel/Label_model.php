<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Label_model extends MY_Model {
    function __construct() {
        parent::__construct ();
    }
    const TAB_LABEL_TYPE = 'hotel_label_types';
    const TAB_HOTEL_LABELS = 'hotel_labels';
    static $label_types = array (
            'roomtype' => '房型分类',
//             'promotion' => '促销标签' 
    );
    static $label_tabs = array (
            'room' => '房型',
            'package' => '套票' 
    );
    function type_fields_config() {
        $user_operations = array (
                'edit' => array (
                        '<a href="',
                        'key' => site_url ( 'hotel/label/edit' ),
                        '" class="btn btn-success btn-xs" title="编辑"><i class="fa fa-edit"></i> 编辑</a>' 
                ) 
        );
        // $acl_array = $this->session->allow_actions;
        // $acl_array = $acl_array [ADMINHTML];
        // foreach ( $user_operations as $oper => $link ) {
        // if (($acl_array != FULL_ACCESS) && (! isset ( $acl_array ['hotel'] ['coupons'] ) || ! in_array ( $oper, $acl_array ['hotel'] ['coupons'] ))) {
        // unset ( $user_operations [$oper] );
        // }
        // }
        return array (
                'label_name' => array (
                        'label' => '标签名' 
                ),
                'type' => array (
                        'label' => '类型',
                        'select' => self::$label_types 
                ),
                'sort' => array (
                        'label' => '排序(越大越前)' 
                ),
                'status' => array (
                        'label' => '状态',
                        'select' => array (
                                '1' => '有效',
                                '2' => '无效',
                                '3' => '删除' 
                        ) 
                ),
                'create_time' => array (
                        'label' => '创建时间' 
                ),
                'update_time' => array (
                        'label' => '最后更新时间' 
                ),
                'user_operations' => array (
                        'label' => '操作',
                        'user_operations' => $user_operations 
                ) 
        );
    }
    protected function _load_db($type = 'main') {
        switch ($type) {
            case 'read' :
                return $this->load->database ( 'iwide_r1', true );
                break;
            default :
                return $this->db;
                break;
        }
    }
    public function get_label_types($inter_id, $label_tab = 'room', $status = NULL, $nums = NULL, $offset = NULL) {
        $db = $this->_load_db ( 'read' );
        $db->where ( array (
                'inter_id' => $inter_id,
                'label_tab' => $label_tab 
        ) );
        is_null ( $status ) ? $db->where_in ( 'status', array (
                1,
                2 
        ) ) : $db->where ( 'status', $status );
        is_null ( $nums ) or $db->limit ( $nums, $offset );
        return $db->get ( self::TAB_LABEL_TYPE )->result_array ();
    }
    public function get_label_type($inter_id, $type_id, $status = NULL) {
        $db = $this->_load_db ( 'read' );
        $db->where ( array (
                'inter_id' => $inter_id,
                'type_id' => $type_id 
        ) );
        is_null ( $status ) ? $db->where_in ( 'status', array (
                1,
                2 
        ) ) : $db->where ( 'status', $status );
        $db->limit ( 1 );
        return $db->get ( self::TAB_LABEL_TYPE )->row_array ();
    }
    function type_table_fields() {
        return array (
                'type_id' => '',
                'label_name' => '',
                'type' => 'roomtype',
                'status' => 1,
                'sort' => 0 
        );
    }
    function save_type($inter_id, $data, $mode = 'update') {
        $db = $this->_load_db ();
        if ($mode == 'add') {
            unset ( $data ['type_id'] );
            $data ['inter_id'] = $inter_id;
            $data ['create_time'] = date ( 'Y-m-d H:i:s' );
            return $db->insert ( self::TAB_LABEL_TYPE, $data );
        } else if ($mode == 'update') {
            if (! empty ( $data ['type_id'] ) && ! empty ( $this->get_label_type ( $inter_id, $data ['type_id'] ) )) {
                $db->where ( array (
                        'inter_id' => $inter_id,
                        'type_id' => $data ['type_id'] 
                ) );
                unset ( $data ['type_id'] );
                unset ( $data ['inter_id'] );
                return $db->update ( self::TAB_LABEL_TYPE, $data );
            }
        }
        return FALSE;
    }
    public function get_label_items($inter_id, $type_id, $hotel_ids = '', $status = NULL) {
        $db = $this->_load_db ( 'read' );
        $db->where ( array (
                'inter_id' => $inter_id,
                'label_type' => $type_id 
        ) );
        empty ( $hotel_ids ) or $db->where_in ( 'hotel_id', explode ( ',', $hotel_ids ) );
        is_null ( $status ) ? $db->where_in ( 'status', array (
                1,
                2 
        ) ) : $db->where ( 'status', $status );
        return $db->get ( self::TAB_HOTEL_LABELS )->result_array ();
    }
    public function get_hotel_tab_labels($inter_id, $hotel_id, $labe_type, $labe_tab = 'room', $check_status = 'valid', $params = array()) {
        $db = $this->_load_db ( 'read' );
        $selects = 't.label_name,t.sort,l.*';
        $db->select ( $selects );
        $db->from ( self::TAB_LABEL_TYPE . ' t' );
        $db->join ( self::TAB_HOTEL_LABELS . ' l', 't.inter_id=l.inter_id and l.label_type=t.type_id' );
        $db->where ( array (
                't.inter_id' => $inter_id,
                'l.hotel_id' => $hotel_id 
        ) );
        switch ($check_status) {
            case 'valid' :
            default :
                $db->where ( array (
                        't.status' => 1,
                        'l.status' => 1 
                ) );
                break;
        }
        empty ( $params ['tab_ids'] ) or $db->where_in ( 'l.tab_id', $params ['tab_ids'] );
        $db->order_by ( 't.sort desc' );
        $result = $db->get ()->result_array ();
        if (! empty ( $result ) && ! empty ( $params ['format'] )) {
            $data = array ();
            $types = array ();
            foreach ( $result as $r ) {
                $data [$r ['tab_id']] [$r ['label_type']] [$r ['label_id']] = $r;
				if(isset ( $types [$r ['label_type']] )){
					$types [$r ['label_type']]['counts'] ++;
				}else{
					$types [$r ['label_type']] = array (
							'name' => $r ['label_name'] ,
							'counts' => 1
					);
				}
            }
            return array (
                    'types' => $types,
                    'labels' => $data 
            );
        }
        return $result;
    }
    function type_labels_check($inter_id, $type_ids, $label_tab = 'room', $hotel_ids = '', $check_valid = TRUE) {
        $db = $this->_load_db ( 'read' );
        $selects = 'h.name hotel_name,h.status hstatus,h.hotel_id hid,tab.room_id rid,tab.status rstatus,tab.name room_name,t.type_id label_type_id,l.status,l.label_id';
        $db->select ( $selects );
        $db->from ( 'hotels h' );
        $db->join ( 'hotel_rooms tab', 'h.inter_id=tab.inter_id and h.hotel_id=tab.hotel_id' );
        $db->join ( self::TAB_LABEL_TYPE . ' t', 'h.inter_id=t.inter_id' );
        $db->join ( self::TAB_HOTEL_LABELS . ' l', 'l.inter_id=h.inter_id and l.hotel_id=h.hotel_id and l.tab_id=tab.room_id and l.label_type=t.type_id', 'left' );
        $db->where ( 'h.inter_id', $inter_id );
        if ($check_valid) {
            $db->where ( array (
                    'h.status' => 1,
                    'tab.status' => 1 
            ) );
        } else {
            $db->where_in ( 'h.status', array (
                    1,
                    2 
            ) );
            $db->where_in ( 'tab.status', array (
                    1,
                    2 
            ) );
        }
        $db->where_in ( 't.type_id', $type_ids );
        empty ( $hotel_ids ) or $db->where_in ( 'h.hotel_id', explode ( ',', $hotel_ids ) );
        $result = $db->get ()->result_array ();
        $labels = array ();
        foreach ( $result as $r ) {
            if (! isset ( $labels [$r ['hid']] ['name'] )) {
                $labels [$r ['hid']] ['name'] = $r ['hotel_name'];
                $labels [$r ['hid']] ['status'] = $r ['hstatus'];
            }
            if (! isset ( $labels [$r ['hid']] ['rooms'] [$r ['rid']] ['name'] )) {
                $labels [$r ['hid']] ['items'] [$r ['rid']] ['name'] = $r ['room_name'];
                $labels [$r ['hid']] ['items'] [$r ['rid']] ['status'] = $r ['rstatus'];
            }
            if (empty ( $r ['label_id'] )) {
                $labels [$r ['hid']] ['items'] [$r ['rid']] ['types'] [$r ['label_type_id']] ['check'] = 0;
            } else {
                $labels [$r ['hid']] ['items'] [$r ['rid']] ['types'] [$r ['label_type_id']] ['check'] = $r ['status'] == 1 ? 1 : 0;
                $labels [$r ['hid']] ['items'] [$r ['rid']] ['types'] [$r ['label_type_id']] ['label_id'] = $r ['label_id'];
            }
        }
        return $labels;
    }
    function update_label_item($inter_id, $label_types, $type_ids, $label_tab = 'room', $hotel_ids = array()) {
        $label_check = $this->type_labels_check ( $inter_id, $type_ids, $label_tab, implode ( ',', $hotel_ids ), FALSE );
        $create_time = date ( 'Y-m-d H:i:s' );
        if ($label_check) {
            $new_data = array ();
            $valid_data = array ();
            $invalid_data = array ();
            foreach ( $label_check as $hotel_id => $label ) {
                if (! empty ( $label ['items'] )) {
                    foreach ( $label ['items'] as $item_id => $room ) {
                        if (! empty ( $room ['types'] )) {
                            foreach ( $room ['types'] as $type_id => $type_check ) {
                                if (! empty ( $label_types [$hotel_id] [$item_id] [$type_id] )) { // 该商品有此标签被选择
                                    if ($type_check ['check'] == 0) { // 之前保存数据里没有此标签或无效
                                        if (empty ( $type_check ['label_id'] )) { // 无则新增
                                            $new_data [] = array (
                                                    'inter_id' => $inter_id,
                                                    'hotel_id' => $hotel_id,
                                                    'tab_id' => $item_id,
                                                    'label_type' => $type_id,
                                                    'create_time' => $create_time 
                                            );
                                        } else { // 有则改为有效
                                            $valid_data [] = array (
                                                    'label_id' => $type_check ['label_id'],
                                                    'status' => 1 
                                            );
                                        }
                                    }
                                } else if ($type_check ['check'] == 1) { // 此标签未被选择但之前保存数据里有
                                    $invalid_data [] = array (
                                            'label_id' => $type_check ['label_id'],
                                            'status' => 2 
                                    );
                                }
                            }
                        }
                    }
                }
            }
            $main_db = $this->_load_db ();
            if (! empty ( $new_data )) {
                $main_db->insert_batch ( self::TAB_HOTEL_LABELS, $new_data );
            }
            if (! empty ( $valid_data )) {
                $main_db->update_batch ( self::TAB_HOTEL_LABELS, $valid_data, 'label_id' );
            }
            if (! empty ( $invalid_data )) {
                $main_db->update_batch ( self::TAB_HOTEL_LABELS, $invalid_data, 'label_id' );
            }
            return TRUE;
        }
        return FALSE;
    }
}