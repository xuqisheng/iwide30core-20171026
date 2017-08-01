<?php
class Iwidepay_bankcode_model extends MY_Model{

    const TAB_IWIDEPAY_OP_LOG = 'iwidepay_bankcode';
    const TAB_IWIDEPAY_BANKCODE = 'iwide_iwidepay_bankcode';
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
     * 获取银行信息
     * @author 沙沙
     * @param string $select
     * @param array $where 条件
     * @return array $data
     * @date 2017-6-28
     */
    public function get_one($select = '*',$where = array())
    {
        $res = $this->db_read()->select($select)->where($where)->get(self::TAB_IWIDEPAY_OP_LOG)->row_array();
        return $res;
    }

    /**
     * 获取银行
     * @param string $select
     * @param array $where
     * @return
     */
    public function get_bank($select = '*',$where = array())
    {
        $res = $this->db_read()->select($select)->where($where)->limit(100)->get(self::TAB_IWIDEPAY_BANKCODE)->result_array();
        return $res;
    }


    /**
     * 获取银行 模糊搜索
     * @param string $select
     * @param array $where
     * @param string $keyword
     * @return
     */
    public function get_branch($select = '*',$where = array(),$keyword = '')
    {
        $res = $this->db_read()->select($select)->where($where)->like('branch', $keyword, 'both')->limit(300)->get(self::TAB_IWIDEPAY_BANKCODE)->result_array();
        return $res;
    }


}
