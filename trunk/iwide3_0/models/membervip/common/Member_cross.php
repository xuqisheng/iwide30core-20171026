<?php
/**
 * User: zhangyi <zhangyi@mofly.cn>
 * Date: 17-8-7
 * Time: 上午10:51
 */

class Member_cross extends MY_Model_Member {

    const DEPOSIT_CARD_PAY = 'deposit_card_pay';

    public function get_sales_info_by_orderid($inter_id,$order_id){
        $where = array(
            'deposit_card_pay_id' => $order_id,
            'inter_id'  => $inter_id
        );
        $deposit_info = $this->_shard_db()->select('*')
            ->get_where(self::DEPOSIT_CARD_PAY, $where)
            ->row_array();
        if(!empty($deposit_info) && $deposit_info['distribution_num'] > 0 ){
            $this->load->model('distribute/Staff_model');
            $sales = $this->Staff_model->get_my_base_info_saler($inter_id,$deposit_info['distribution_num']);
            if(!empty($sales)){
                return $sales;
            }
            return array();
        }else{

            return array();
        }



    }


}


?>