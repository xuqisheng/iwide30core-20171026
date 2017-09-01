<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class Activity_killsec_group_model
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class Activity_killsec_group_model extends MY_Model_Soma
{
    /**
     * 正常
     */
    const STATUS_OK = 'a';
    /**
     * 禁用
     */
    const STATUS_STOP = 'b';

    /**
     * @return string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function table_name()
    {
        return 'soma_activity_killsec_group';
    }

    public function default_values()
    {
        return array(
            'id' => '',
            'inter_id' => '',
            'name' => '',
            'bg_img' => '',
            'start_time' => '',
            'end_time' => '',
            'show_time' => '',
            'kill_time' => '',
            'share_info' => array(
                'title' => '',
                'img' => '',
                'desc' => ''
            ),
            'redirect_info' => array(
                'name' => '',
                'url' => ''
            ),
            'info' => array(
                'name' => ''
            ),
            'buy_limit' => '',
            'last_time' => '',
            'status' => self::STATUS_OK
        );
    }

    /**
     * @param array $group
     * @return DateTime
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getShowTime(Array $group)
    {
        return date_create(date('Y-m-d') . ' ' . date('H:i:s', strtotime($group['show_time'])));
    }

    /**
     * @param array $group
     * @return DateTime
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getKillTime(Array $group)
    {
        return date_create(date('Y-m-d') . ' ' . date('H:i:s', strtotime($group['kill_time'])));
    }

    /**
     * @param array $group
     * @return DateTime
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getKillEndTime(Array $group)
    {
        return $this->getKillTime($group)->modify('+' . $group['last_time'] . ' hour');
    }

    /**
     * @param $id
     * @return mixed
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getByID($id)
    {
        $params = array(
            'id' => $id
        );
        $result = $this->db_conn_read->where($params)->get($this->table_name())->result_array();

        if (empty($result)) {
            return array();
        }
        return $result[0];
    }

    /**
     * @param $id
     * @return mixed
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getByInterID($id)
    {
        $params = array(
            'inter_id' => $id
        );
        $result = $this->db_conn_read->where($params)->get($this->table_name())->result_array();

        if (empty($result)) {
            return array();
        }

        $result[0]['show_time'] = substr($result[0]['show_time'], 10);
        $result[0]['kill_time'] = substr($result[0]['kill_time'], 10);
        $result[0]['share_info'] = json_decode($result[0]['share_info'], true);
        $result[0]['redirect_info'] = json_decode($result[0]['redirect_info'], true);
        return $result[0];
    }

    /**
     * @param array $arr
     * @return mixed0
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function data(Array $arr)
    {
        $result['rows'] = $this->search($arr);
        $result['count'] = $this->count($arr);
        return $result;
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
     * @param array $arr
     * @return int
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function count(Array $arr)
    {
        return $this->db_conn_read->where($arr)->count_all($this->table_name());
    }

    /**
     * @param array $arr
     * @return boolean
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function create(Array $arr)
    {
        if (isset($arr['products'])) {
            unset($arr['products']);
        }
        if (isset($arr['fileselect'])) {
            unset($arr['fileselect']);
        }
        if (isset($arr['share_info'])) {
            $arr['share_info'] = json_encode($arr['share_info']);
        }
        if (isset($arr['redirect_info'])) {
            $arr['redirect_info'] = json_encode($arr['redirect_info']);
        }

        if (isset($arr['show_time'])) {
            $arr['show_time'] = date('Y-m-d') . ' ' . $arr['show_time'];
        }
        if (isset($arr['kill_time'])) {
            $arr['kill_time'] = date('Y-m-d') . ' ' . $arr['kill_time'];
        }
        return $this->db_conn->insert($this->table_name(), $arr);
    }

    /**
     * @param string $groupID
     * @param array $arr
     * @return object
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function update($groupID, Array $arr)
    {
        if (isset($arr['products'])) {
            unset($arr['products']);
        }
        if (isset($arr['fileselect'])) {
            unset($arr['fileselect']);
        }
        if (isset($arr['share_info'])) {
            $arr['share_info'] = json_encode($arr['share_info']);
        }
        if (isset($arr['redirect_info'])) {
            $arr['redirect_info'] = json_encode($arr['redirect_info']);
        }

        if (isset($arr['show_time'])) {
            $arr['show_time'] = date('Y-m-d') . ' ' . $arr['show_time'];
        }
        if (isset($arr['kill_time'])) {
            $arr['kill_time'] = date('Y-m-d') . ' ' . $arr['kill_time'];
        }

        $this->db_conn->where('id', $groupID);
        return $this->db_conn->update($this->table_name(), $arr);
    }


}
