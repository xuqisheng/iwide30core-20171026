<?php
class Iwidepay_admin_op_log_model extends MY_Model{

    const TAB_IWIDEPAY_OP_LOG = 'iwidepay_admin_op_log';
	function __construct() {
		parent::__construct ();
	}

    protected function db_read(){
        
        $db_read = $this->load->database('iwide_r1',true);
        return $db_read;
        
    }
    
    protected function db_write(){
        
        return $this->db;
    }


    /**
     * 添加日志
     *
     * @param $log
     * @return 受影响行数
     */
    public function add_log($log)
    {
        $this->db_write()->insert(self::TAB_IWIDEPAY_OP_LOG, $log);
        return $this->db_write()->insert_id();
    }

}
