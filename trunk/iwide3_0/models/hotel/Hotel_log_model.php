<?php
class Hotel_log_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	public $log_types = array (
			'to_add' => '添加',
			'add' => '添加成功',
			'add_fail' => '添加失败',
			'edit' => '编辑',
			'save' => '保存',
			'save_fail' => '保存失败',
			'del' => '删除',
			'del_fail' => '删除失败',
			'check' => '查看' 
	);
	// `log_id`, `inter_id`, `admin_id`, `url`, `ip`, `ident`, `recore_time`, `key_data`, `log_type`
	// log_type:to_add,add,edit,save,del,check
	// 2017-3-10 10:54:07 增加批量插入操作，日志类型及路径必须相同
	/**
	 *
	 * @param string $ident
	 *        	用来标识一类记录，用于搜索记录时用，由记录者自定义，须带上表名，且以‘表名#’开头，如某条数据的记录可用 "表名#主键" ，
	 *        	数组形式时为多条记录，下标须为自动生成的数字索引
	 * @param string $log_type
	 *        	记录类型 基本类型：to_add'=>'','add'=>'','add_fail'=>'','edit'=>'','save'=>'','save_fail'=>'','del'=>'','del_fail'=>'','check 可自定义
	 * @param string $key_data
	 *        	要记录的数据,若由此方法自动生成，传NULL，数组形式时为多条记录，且下标与$ident中对应数据一致
	 * @param array $source_data
	 *        	源数据，二维数组形式时为多条记录，且下标与$ident中对应数据一致
	 * @param array $new_data
	 *        	更新数据x，与源数据比较，生成key_data，且下标与$ident中对应数据一致
	 * @param array $unset
	 *        	key_data中不需记录的数据
	 */
	function add_admin_log($ident, $log_type = '', $key_data = NULL, $source_data = array(), $new_data = array(), $unset = array()) {
		$admin_profile = $this->session->admin_profile;
		$data ['url'] = $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
		$data ['admin'] = '';
		if (! empty ( $admin_profile ['inter_id'] )) {
			$data ['inter_id'] = $admin_profile ['inter_id'];
		} else if (! empty ( $this->session->userdata ( 'inter_id' ) )) {
			$data ['inter_id'] = $this->session->userdata ( 'inter_id' );
		} else {
			$data ['inter_id'] = 'unknownitd';
		}
		if (! empty ( $admin_profile )) {
			$data ['admin'] ['id'] = empty ( $admin_profile ['admin_id'] ) ? 0 : $admin_profile ['admin_id'];
			$data ['admin'] ['nm'] = empty ( $admin_profile ['username'] ) ? 0 : $admin_profile ['username'];
		} else {
			if (stripos ( $data ['url'], '/Auto_gogogo/' ) !== FALSE) {
				$data ['admin'] ['id'] = '-1';
				$data ['admin'] ['nm'] = 'autorder';
			} else if (! empty ( $this->session->userdata ( $data ['inter_id'] . 'openid' ) )) {
				$data ['admin'] ['id'] = '-2';
				$data ['admin'] ['nm'] = $this->session->userdata ( $data ['inter_id'] . 'openid' );
			}
		}
		$data ['admin'] = json_encode ( $data ['admin'] );
		$data ['record_time'] = date ( 'Y-m-d H:i:s' );
		$this->load->helper ( 'common' );
		$data ['ip'] = getIp ();
		$data ['log_type'] = $log_type;
		
		if (is_array ( $ident )) {
			$datas = array ();
			$log_dir = '';
			foreach ( $ident as $k => $i ) {
				if (empty ( $log_dir )) {
					$log_dir = $this->create_log_dirname ( $i );
				}
				$data ['ident'] = $i;
				$data ['key_data'] = '';
				if (isset ( $key_data [$k] )) {
					$data ['key_data'] = $key_data [$k];
				} else {
					if (isset ( $new_data [$k] )) {
						if (! isset ( $source_data [$k] )) {
							$data ['key_data'] = $new_data [$k];
						} else {
							foreach ( $source_data [$k] as $sk => $sd ) {
								if (isset ( $new_data [$k] [$sk] ) && $sd != $new_data [$k] [$sk]) {
									$data ['key_data'] [$sk] = array (
											'old' => $sd,
											'new' => $new_data [$k] [$sk] 
									);
								}
							}
						}
					} else {
						$data ['key_data'] = '';
					}
				}
				if (is_array ( $data ['key_data'] )) {
					if (! empty ( $unset )) {
						foreach ( $unset as $u ) {
							unset ( $data ['key_data'] [$u] );
						}
					}
					$data ['key_data'] = json_encode ( $data ['key_data'], JSON_UNESCAPED_UNICODE );
				}
				$datas [] = $data;
			}
			$this->db->insert_batch ( 'hotel_admin_log', $datas );
			$log_datas = array ();
			foreach ( $datas as $log ) {
				$content = '';
				foreach ( $log as $k => $d ) {
					$content .= $k . ':' . $d . '|';
				}
				$log_datas [] = $content;
			}
			MYLOG::w ( $log_datas, 'hotel'.DS.'admin_log' . DS . $log_dir );
		} else {
			$data ['ident'] = $ident;
			$data ['key_data'] = '';
			if (isset ( $key_data )) {
				$data ['key_data'] = $key_data;
			} else {
				if (! empty ( $new_data )) {
					if (empty ( $source_data )) {
						$data ['key_data'] = $new_data;
					} else {
						foreach ( $source_data as $sk => $sd ) {
							if (isset ( $new_data [$sk] ) && $sd != $new_data [$sk]) {
								$data ['key_data'] [$sk] = array (
										'old' => $sd,
										'new' => $new_data [$sk] 
								);
							}
						}
					}
				} else {
					$data ['key_data'] = '';
				}
			}
			if (is_array ( $data ['key_data'] )) {
				if (! empty ( $unset )) {
					foreach ( $unset as $u ) {
						unset ( $data ['key_data'] [$u] );
					}
				}
				$data ['key_data'] = json_encode ( $data ['key_data'], JSON_UNESCAPED_UNICODE );
			}
			
			$content = '';
			foreach ( $data as $k => $d ) {
				$content .= $k . ':' . $d . '|';
			}
			$this->db->insert ( 'hotel_admin_log', $data );
			MYLOG::w ( $content, 'hotel'.DS.'admin_log' . DS . $this->create_log_dirname ( $ident ) );
		}
	}
	
	/**
	 *
	 * @param string $inter_id        	
	 * @param array $params
	 *        	$params=array(
	 *        	'log_id'=>'记录id，单个id或id数组',
	 *        	'log_type'=>'记录类型，单个或数组',
	 *        	'ip'=>'单个或数组',
	 *        	'admin_id'=>'操作的用户的id',
	 *        	'admin_name'=>'操作的用户名',
	 *        	'ident'=>'',
	 *        	'begin_time'=>'记录开始时间',
	 *        	'end_time'=>'记录结束时间',
	 *        	'offset'=>'取数据偏移',
	 *        	'nums'=>'取数据数量',
	 *        	'log_des'=>'日志描述格式,形如 "{admin_name}于{record_time}{type_des}了记录" 则输出如 admin于2016-06-24 19:03:00修改了记录'
	 *        	);
	 */
	function get_admin_log($inter_id, $params = array(), $format = TRUE) {
		$db = $this->load->database ( 'iwide_r1', true );
		$select = ' `log_id`, `inter_id`, `admin`, `ip`, `ident`, `record_time`, `log_type` ';
		if (! empty ( $params ['need_data'] )) {
			$select .= ' ,`url`, `key_data` ';
		}
		$db->select ( $select );
		$db->where ( 'inter_id', $inter_id );
		if (! empty ( $params ['log_id'] )) {
			is_array ( $params ['log_id'] ) ? $db->where_in ( 'log_id', $params ['log_id'] ) : $db->where ( 'log_id', $params ['log_id'] );
		}
		if (! empty ( $params ['log_type'] )) {
			is_array ( $params ['log_type'] ) ? $db->where_in ( 'log_type', $params ['log_type'] ) : $db->where ( 'log_type', $params ['log_type'] );
		}
		if (! empty ( $params ['ip'] )) {
			is_array ( $params ['ip'] ) ? $db->where_in ( 'ip', $params ['ip'] ) : $db->where ( 'ip', $params ['ip'] );
		}
		empty ( $params ['admin_id'] ) ?: $db->like ( 'admin', '{"id":"' . $params ['admin_id'] . '"', 'after' );
		empty ( $params ['admin_name'] ) ?: $db->like ( 'admin', '"nm":"' . $params ['admin_name'] . '"}', 'before' );
		empty ( $params ['ident'] ) ?: $db->where ( 'ident', $params ['ident'] );
		empty ( $params ['begin_time'] ) ?: $db->where ( 'record_time >= ', $params ['begin_time'] );
		empty ( $params ['end_time'] ) ?: $db->where ( 'end_time >= ', $params ['end_time'] );
		isset ( $params ['offset'] ) ? $db->limit ( $params ['nums'], $params ['offset'] ) : 0;
		
		empty ( $params ['order_by'] ) ? $db->order_by ( 'log_id desc' ) : $db->order_by ( $params ['order_by'] );
		
		$result = $db->get ( 'hotel_admin_log' )->result_array ();
		if (! empty ( $result ) && $format) {
			if (! empty ( $params ['type_des'] )) {
				$this->log_types = array_merge ( $this->log_types, $params ['type_des'] );
			}
			if (! empty ( $params ['log_des'] )) {
				$search = array_keys ( $result [0] );
				$search [] = 'admin_id';
				$search [] = 'admin_name';
				$search [] = 'type_des';
				foreach ( $search as &$s ) {
					$s = '{' . $s . '}';
				}
			}
			foreach ( $result as &$r ) {
				$admin = json_decode ( $r ['admin'], TRUE );
				$r ['admin_id'] = $admin ['id'];
				$r ['admin_name'] = $admin ['nm'];
				$r ['type_des'] = empty ( $this->log_types [$r ['log_type']] ) ? $r ['log_type'] : $this->log_types [$r ['log_type']];
				$replace = array_values ( $r );
				if (! empty ( $params ['log_des'] )) {
					$r ['log_des'] = str_replace ( $search, $replace, $params ['log_des'] );
				}
			}
		}
		return $result;
	}
	function create_log_dirname($ident) {
		$check = strpos ( $ident, '#' );
		$log_dir = $check !== FALSE ? substr ( $ident, 0, $check ) : $ident;
		return strtolower ( str_replace ( array (
				'/',
				'\\' 
		), '_', $log_dir ) );
	}
}