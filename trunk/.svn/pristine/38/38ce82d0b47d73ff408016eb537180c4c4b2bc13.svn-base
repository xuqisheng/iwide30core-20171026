<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class Activity_killsec_group_product_model
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class Activity_killsec_group_product_model extends MY_Model_Soma
{

    /**
     * @return string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function table_name()
    {
        return 'soma_activity_killsec_group_product';
    }

    /**
     * @param $groupID
     * @return mixed
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getByGroupID($groupID, $select = '*')
    {
        $params = array(
            'group_id' => $groupID
        );
        return $this->db_conn_read->select($select)->where($params)->get($this->table_name())->result_array();
    }

    /**
     * @param array $arr
     * @return boolean
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function create(Array $arr)
    {
        if (isset($arr['schedule']) && is_array($arr['schedule'])) {
            $arr['schedule'] = json_encode($arr['schedule']);
        }
        $arr['created_at'] = date('Y-m-d H:i:s');

        return $this->db_conn->insert($this->table_name(), $arr);
    }

    /**
     * @param $groupID
     * @param $productID
     * @param array $arr
     * @return object
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function update($groupID, $productID, Array $arr)
    {
        $this->db_conn->where('group_id', $groupID)->where('product_id', $productID);
        if (isset($arr['schedule']) && is_array($arr['schedule'])) {
            $arr['schedule'] = json_encode($arr['schedule']);
        }
        return $this->db_conn->update($this->table_name(), $arr);
    }

    /**
     * @param $groupID
     * @param $productID
     * @return mixed
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function delete($groupID, $productID)
    {
        return $this->db_conn->where('group_id', $groupID)->where('product_id', $productID)->delete($this->table_name());
    }


    /**
     * @param array $arr
     * @return mixed
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function search(Array $arr)
    {
        $query = $this->db_conn_read;

        if (isset($arr['where'])) {
            $query->where($arr['where']);
        }

        if (isset($arr['pagination'])) {
            $query->limit($arr['pagination']['limit'], $arr['pagination']['offset']);
        }
        return $query->get($this->table_name())->result_array();
    }


    /**
     * @param $killId
     * @param string $select
     * @return mixed
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getByKillId($killId, $select = '*')
    {
        $params = array(
            'kill_id' => $killId
        );
        return $this->db_conn_read->select($select)->where($params)->get($this->table_name())->result_array();
    }

}
