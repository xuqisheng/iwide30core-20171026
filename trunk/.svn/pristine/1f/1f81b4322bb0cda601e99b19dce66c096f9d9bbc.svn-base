<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Activity_idx
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class Activity_idx_model extends MY_Model_Soma{

    CONST ACT_TYPE_KILL = 2;

    CONST ACT_TYPE_GROUPON = 1;


    CONST STATUS_Y = 1;
    CONST STATUS_N = 2;

    /**
     * @return string
     * @author renshuai  <renshuai@mofly.cn>
     */
	public function table_name()
	{
		return 'soma_activity_idx';
	}


    /**
     * @param array $arr
     * @return bool
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function create(Array $arr)
    {
        return $this->db_conn->insert($this->table_name(), $arr);
    }
}
