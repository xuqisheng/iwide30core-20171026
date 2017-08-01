<?php
class Club_report_model extends MY_Model {
    function __construct() {
        parent::__construct ();
    }
    const TAB_CLUB_STA = 'club_staff';

    function _load_db() {
        return $this->db;
    }



    function get_club_report($inter_id){

        $sql = "
            SELECT
                t1.id,t1.orderid,t1.startdate,t1.enddate,t1.club_id,t1.iprice,t3.order_time
            FROM
                `iwide_distribute_grade_all` t2,
                `iwide_hotel_order_items` t1,
                `iwide_hotel_orders` t3
            WHERE
                t1.inter_id = '{$inter_id}'
            AND
                t1.inter_id = t2.inter_id
            AND
                t2.grade_table = 'iwide_hotels_order'
            AND
                t2.grade_typ = 2
            AND
                t1.id = t2.grade_id
            AND
                t2.status in (1,2,5)
            AND
                t1.club_id !=''
            AND
                t1.orderid = t3.orderid
        ";

        $res = $this->db->query($sql)->result_array();

        return $res;

    }


    function near_year_orders($inter_id,$time){

        $sql = "
            SELECT
                t1.id,t1.orderid,t1.iprice,t1.startdate,t1.enddate,t1.webs_orderid,t1.club_id,t2.order_time
            FROM
                `iwide_hotel_order_items` t1,
                `iwide_hotel_orders` t2
            WHERE
                t1.inter_id = '{$inter_id}'
            AND
                t1.orderid = t2.orderid
            AND
                t2.order_time >= '{$time}'
        ";

        return $this->db->query($sql)->result_array();

    }






}