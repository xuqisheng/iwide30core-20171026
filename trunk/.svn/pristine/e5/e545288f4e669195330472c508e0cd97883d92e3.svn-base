<?php
//error_reporting(E_ALL^E_NOTICE);
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Member_card extends MY_Admin_Cprice {
    protected $label_module = '会员储值卡';
    protected $label_controller = '会员储值卡';
    protected $label_action = '';
    function __construct() {
        parent::__construct ();
    }

    protected function main_model_name()
    {
        return 'member/Member_card_model';
    }

    public function vcard()
    {
        $inter_id = $this->session->get_admin_inter_id ();
        if ($inter_id == FULL_ACCESS)
            $filter = array ();
        else if ($inter_id)
            $filter = array (
                'inter_id' => $inter_id,
                'paid'=>1
            );
        else
            $filter = array (
                'inter_id' => 'deny'
            );
        $entity_id = $this->session->get_admin_hotels ();
        if (! empty ( $entity_id )) {
            // $filter['hotel_id']
        }
        // print_r($filter);die;

        /* 兼容grid变为ajax加载加这一段 */
        if (is_ajax_request ())
            // 处理ajax请求，参数规格不一样
        $get_filter = $this->input->post ();
        else
            $get_filter = $this->input->get ( 'filter' );

        if (! $get_filter)
            $get_filter = $this->input->get ( 'filter' );

        if (is_array ( $get_filter ))
            $filter = $get_filter + $filter;
        /* 兼容grid变为ajax加载加这一段 */

        $filter['sql']="SELECT
                            t1.co_id,t1.mem_id,t1.order_number,t1.ci_id,t1.amount,t2.name,t2.telephone,t2.identity_card,t1.create_time,t2.distribution_no,t3.name as saler_name
                        FROM
                            `iwide_member_card_order` as t1,
                            `iwide_member_vcard` as t2,
                            `iwide_hotel_staff` as t3
                        WHERE
                            t1.co_id = t2.co_id
                        AND
                            t1.inter_id = '{$inter_id}'
                        AND
                            t1.paid=1
                        AND
                            t2.distribution_no = t3.qrcode_id
                        AND
                            t3.inter_id =  '{$inter_id}'
                        GROUP BY
                            t1.co_id
                            ";

        $this->m_grid ( $filter );

    }


}
