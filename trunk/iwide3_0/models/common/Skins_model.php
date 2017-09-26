<?php
class Skins_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_SKINS = 'view_skins';
	const TAB_SKIN_SET = 'view_skin_set';
	const TAB_DISP_SET = 'view_disp_set';
	function get_skin_set($inter_id, $module, $hotel_id = 0) {
		$db = $this->load->database('iwide_r1',true);
		$sql = 'select * from ' . $db->dbprefix ( self::TAB_SKIN_SET ) . '
				 where inter_id=? and module=? and hotel_id in (0, ?)
				 order by hotel_id desc limit 1';
		$params = array (
				$inter_id,
				$module,
				$hotel_id
		);
		return $db->query ( $sql, $params )->row_array ();
	}
	function table_fields() {
		return array (
				'id' => '',
				'skin_name' => 'default',
				'hotel_id' => 0,
				'module' => '',
				'skin_id' => '',
				'status' => 1,
				'overall_style' => ''
		);
	}
	public function enums($type, $key = NULL) {
		switch ($type) {
			case 'skin_decora' :
				$this->load->model('common/Enum_model');
				$data = $this->Enum_model->get_enum_des('HOTEL_SKIN_DECORA');
				break;
			default :
				$data = array ();
		}
		if (isset ( $key )) {
			return isset ( $data [$key] ) ? $data [$key] : NULL;
		}
		return $data;
	}
	function update_skin_set($inter_id, $module, $set_id, $data) {
		$this->db->where ( array (
				'inter_id' => $inter_id,
				'module' => $module,
				'id' => $set_id
		) );
		return $this->db->update ( self::TAB_SKIN_SET, $data );
	}
	function add_skin_set($inter_id, $module, $data) {
		if (! empty ( $data ['skin_name'] )) {
			$skin = $this->get_module_skin ( $module, $data ['skin_name'] );
			if (empty ( $skin )) {
				return FALSE;
			} else {
				$data ['skin_id'] = $skin ['skin_id'];
			}
		} else {
			$skin = $this->get_module_skin ( $module, 'default' );
			$data ['skin_name'] = 'default';
			$data ['skin_id'] = empty ( $skin ) ? - 1 : $skin ['skin_id'];
		}
		$data ['inter_id'] = $inter_id;
		$data ['module'] = $module;
		return $this->db->insert ( self::TAB_SKIN_SET, $data );
	}
	function update_disp_set($inter_id, $module, $disp_set_id, $data) {
		$this->db->where ( array (
				'inter_id' => $inter_id,
				'module' => $module,
				'id' => $disp_set_id
		) );
		return $this->db->update ( self::TAB_DISP_SET, $data );
	}
	function add_disp_set($inter_id, $module, $data) {
		$data ['inter_id'] = $inter_id;
		$data ['module'] = $module;
		return $this->db->insert ( self::TAB_DISP_SET, $data );
	}
	function get_module_skin($module, $skin_name) {
		$db = $this->load->database('iwide_r1',true);
		return $db->get_where ( self::TAB_SKINS, array (
				'module' => $module,
				'skin_name' => $skin_name
		) )->row_array ();
	}
	function get_disp_set($inter_id, $paras) {
		$db = $this->load->database('iwide_r1',true);
		$paras = explode ( '/', $paras );
		$paras [2] = empty ( $paras [2] ) ? $paras [1] : $paras [2];
		$paras [2] = str_replace ( '.', '/', $paras [2] );
		$paras [3] = empty ( $paras [3] ) ? 0 : $paras [3];
		$sql = "SELECT s.skin_name,s.overall_style,s.id skin_set_id,a.* from " . $db->dbprefix ( 'view_skin_set' ) . " s
          	  left join " . $db->dbprefix ( 'view_disp_set' ) . " a
                 ON s.inter_id = a.inter_id and s.module=a.module
          	  		WHERE s.`inter_id` = '" . $inter_id . "' AND s.`status` = 1 AND s.`module` = '" . $paras [0] . "' AND s.`hotel_id` in (0, " . $paras [3] . ")
                  AND a.`func` = '" . $paras [1] . "' AND a.`status` = 1 order by s.hotel_id desc limit 1";
		return $db->query ( $sql )->row_array ();
	}


    /**
     * 获取某个模块的所有皮肤
     * @param $module
     * @param string $select
     * @param int $status
     * @return array
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function get_skins($module, $select = '*', $status = 1)
    {
        return $this->db->select($select)->get_where ( self::TAB_SKINS, array (
            'module' => $module,
            'status' => $status
        ) )->result_array ();
    }
}