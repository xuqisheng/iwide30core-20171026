<?php

namespace App\models\soma;


/**
 * Class Activity_killsec_user
 * @package App\models\soma
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class Activity_killsec_user extends \MY_Model_Soma
{
    const USER_STATUS_JOIN  = 1;
    const USER_STATUS_ORDER = 2;
    const USER_STATUS_PAYMENT= 3;

    /**
     * 归档，已处理过的
     */
    const USER_STATUS_ARCHIVE = 4;

    /**
     * @return string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function table_primary_key()
    {
        return 'user_id';
    }

    /**
     * @return string
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function table_name()
    {
        return 'soma_activity_killsec_user';
    }

    /**
     * @param $instanceID
     * @param $actID
     * @param $interID
     * @param $openid
     * @param $maxStock
     * @param string $token
     * @return int
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function save($instanceID, $actID, $interID, $openid, $maxStock, $token = '')
    {
        $data = [
            'business' => 'package',
            'instance_id' => $instanceID,
            'token' => $token,
            'act_id' => $actID,
            'inter_id' => $interID,
            'openid' => $openid,
            'join_time' => date('Y-m-d H:i:s'),
            'max_stock' => $maxStock,
            'remote_ip'=> $this->input->ip_address(),
            'status' => self::USER_STATUS_JOIN
        ];

        $this->soma_db_conn->set($data)->insert($this->table_name());

        return $this->soma_db_conn->insert_id();
    }

    /**
     * @param $interID
     * @param $openid
     * @param $instanceID
     * @return int
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getUsedCount($interID, $openid, $instanceID)
    {
        $count = 0;
        $rows = $this->get(
            [
                'inter_id',
                'instance_id',
                'openid',
            ],
            [
                $interID,
                $instanceID,
                $openid,
            ],
            '*',
            [
                'limit' => 20
            ]

        );

        if (empty($rows)) return $count;

        $this->load->model('soma/Sales_order_model','salesOrderModel');
        foreach($rows as $row) {
            switch ($row['status']) {
                case self::USER_STATUS_JOIN:

                    $count += $row['max_stock'];
                    break;

                case self::USER_STATUS_ORDER:

                    $count += $row['max_stock'];
                    break;

                case self::USER_STATUS_PAYMENT:
                    $this->load->model('soma/Sales_order_model','salesOrderModel');
                    $order = $this->salesOrderModel->getByID($row['order_id']);
                    $count += $order['row_qty'];
                    break;
                case self::USER_STATUS_ARCHIVE:
                    if(!empty($row['pay_time'])) {
                        $order = $this->salesOrderModel->getByID($row['order_id']);
                        $count += $order['row_qty'];
                    }
                    break;
            }
        }

        return $count;
    }

    /**
     * @param $select
     * @return mixed
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function getRollbackList($select)
    {
        $table_name = $this->soma_db_conn_read->dbprefix($this->table_name());
        $current_time = date('Y-m-d H:i:s');
        $sql = "SELECT $select FROM $table_name WHERE
        (status =? AND  date_add(join_time, interval 5 minute) < ? AND ? < date_add(join_time, interval 15 minute) )
        or ( status = ? AND date_add(order_time, interval 5 minute) < ? AND ? < date_add(order_time, interval 10 minute) )
        or ( status = ? AND date_add(pay_time, interval 1 minute) < ?  AND ? < date_add(pay_time, interval 10 minute) )
        order by user_id desc limit 800";
        $query = $this->soma_db_conn->query($sql, [
            self::USER_STATUS_JOIN,
            $current_time,
            $current_time,
            self::USER_STATUS_ORDER,
            $current_time,
            $current_time,
            self::USER_STATUS_PAYMENT,
            $current_time,
            $current_time
        ]);

        return $query->result_array();
    }

    /**
     * @param $id
     * @param array $data
     * @return object
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function update($id, Array $data)
    {
        return $this->soma_db_conn->set($data)->where($this->table_primary_key(), $id)->update($this->table_name());
    }


    /**
     * @param $where
     * @param array $data
     * @return mixed
     * @author: liguanglong  <liguanglong@mofly.cn>
     */
    public function updateAffectRows(Array $where, Array $data){
        $this->soma_db_conn->set($data)->where($where)->update($this->table_name());
        return $this->soma_db_conn->affected_rows();
    }


    /**
     * @param array $ids
     * @param $status
     * @return object
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function updateStatus(Array $ids, $status)
    {
        if (empty($ids)) {
            return false;
        }
        return $this->soma_db_conn->set('status', $status)->where_in($this->table_primary_key(), $ids)->update($this->table_name());
    }

    /**
     * @param $userID
     * @return mixed
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function delete($userID)
    {
        return $this->soma_db_conn->where($this->table_primary_key(), $userID)->delete($this->table_name());
    }

    /**
     * @param $id
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getById($id)
    {
        $rows = $this->get($this->table_primary_key(), $id);
        if (empty($rows)) {
            return [];
        }
        return $rows[0];
    }


}