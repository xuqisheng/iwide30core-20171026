<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/30
 * Time: 15:40
 */

class Order extends MY_Controller {


    public function get_order(){

        function guid() {
            $charid = strtoupper(md5(uniqid(mt_rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid =
                 substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,6);

            return $uuid;
        }

        //组合
//        $combineList = [
//            1000371820 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371944 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371954 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372007 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372032 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372084 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372095 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371910 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371996 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372090 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372100 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372102 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372105 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372110 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372114 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372115 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372116 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371897 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371906 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371952 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371717 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371732 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371949 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371975 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371636 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371885 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371889 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371893 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371431 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371433 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371435 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371647 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371687 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371691 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371759 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371761 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371763 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371966 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371970 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372016 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372029 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372043 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372070 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372085 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372120 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372149 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372150 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372151 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372159 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372160 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372163 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371807 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371814 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371827 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371936 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372018 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372036 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372047 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371723 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371724 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371725 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371726 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371727 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371728 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371729 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371731 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371737 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371741 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371757 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371766 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371768 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371770 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371776 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371780 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371783 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371786 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371796 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371798 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371803 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371819 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371822 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371825 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371828 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371836 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371850 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371860 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371861 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371874 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371884 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371948 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372015 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372020 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372067 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371396 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371572 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371576 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371585 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371773 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371851 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371618 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371624 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371967 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371536 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371551 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371587 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371635 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371649 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371650 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371657 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371663 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371664 => ['transaction_id' => 'SOMA-'.guid()],
//            1000371679 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372077 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372086 => ['transaction_id' => 'SOMA-'.guid()],
//            1000271814 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372379 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372372 => ['transaction_id' => 'SOMA-'.guid()],
//            1000372369 => ['transaction_id' => 'SOMA-'.guid()],
//
//        ];

        $combineList = array(
            '1000371396' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371431' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371433' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371435' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371551' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371576' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371585' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371587' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371618' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371624' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371635' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371649' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371657' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371663' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371664' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371691' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371723' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371724' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371725' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371726' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371727' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371728' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371729' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371732' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371737' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371741' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371757' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371761' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371763' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371766' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371768' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371773' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371776' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371780' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371786' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371803' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371807' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371820' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371822' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371825' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371827' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371828' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371861' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371874' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371884' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371897' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371906' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371936' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371944' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371948' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371954' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371966' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000371970' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000372007' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000372015' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000372016' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000372020' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000372029' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000372032' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000372043' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000372067' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000372070' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000372084' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000372085' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000372095' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000372369' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000372372' => ['transaction_id' => 'SOMA-'.guid()],
//            '1000372379' => ['transaction_id' => 'SOMA-'.guid()],
        );

EXIT('FFF');
        $this->load->model('soma/Sales_order_model', 'salesOrderModel');
        $salesOrderModel = $this->salesOrderModel;

        //订单
        $orderList = $salesOrderModel->get(['order_id'], [array_keys($combineList)], ['limit' => null]);

        if(!empty($orderList)){

            $orderIDs = array_column($orderList, 'order_id');

            //明细
            $this->load->model('soma/Sales_item_package_model', 'salesItemPackageModel');
            $salesItemPackageModel = $this->salesItemPackageModel;
            $orderItemList = $salesItemPackageModel->get(['order_id'], [$orderIDs], ['limit' => null]);

            //支付成功
            foreach ($orderList as $val){

                $this->load->model('soma/sales_payment_model');
                $paymentModel= $this->sales_payment_model;

                //主单
                $this->load->model('soma/Sales_order_model', 'salesOrderModel');
                $salesOrderModel = $this->salesOrderModel;

                //初始化
                $salesOrderModel->load($val['order_id']);

                //细单
                if(!empty($orderItemList)){
                    foreach ($orderItemList as $vale){
                        if($vale['order_id'] == $val['order_id']){
                            $salesOrderModel->item = [$vale];
                            break;
                        }
                    }
                }
                if(!$salesOrderModel->item){
                    write_log('手工支付失败，原因：没有订单明细。订单号：'.$val['order_id']."\r\n");
                    continue;
                }

                //微信订单号
                $transactionId = null;
                foreach ($combineList as $item => $vale){
                    if($item == $val['order_id']){
                        $transactionId = $vale['transaction_id'];
                        break;
                    }
                }
                if(!$transactionId){
                    write_log('手工支付失败，原因：没有微信订单号。订单号：'.$val['order_id']."\r\n");
                    continue;
                }

                $log_data['paid_ip'] = '0.0.0.0';
                $log_data['paid_type'] = $paymentModel::PAY_TYPE_WX;
                $log_data['order_id'] = $val['order_id'];
                $log_data['openid'] = $val['openid'];
                $log_data['business'] = $val['business'];
                $log_data['settlement'] = $val['settlement'];
                $log_data['inter_id'] = $val['inter_id'];
                $log_data['hotel_id'] = $val['hotel_id'];
                $log_data['grand_total'] = $val['grand_total'];
                $log_data['transaction_id'] = $transactionId;

                //保存
                $salesOrderModel->order_payment($log_data);
                $salesOrderModel->order_payment_post();
                $paymentModel->save_payment($log_data, NULL);

            }

        }

        exit('success');
    }

}