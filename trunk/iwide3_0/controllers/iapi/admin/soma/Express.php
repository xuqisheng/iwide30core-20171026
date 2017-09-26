<?php

use App\controllers\iapi\admin\traits\Soma;
use App\services\soma\express\ExpressProvider;

use App\libraries\Iapi\AdminConst;
use App\services\Result;
use \App\libraries\Support\Log;
use App\services\soma\ExpressService;
use App\models\soma\SeparateBilling;


/**
 * Class Express
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class Express extends MY_Admin_Iapi
{
    use Soma;

    /**
     * 订单列表类型
     */
    const OTHER_SHIPPING_TYPE = 1;

    /**
     * 顺丰订单列表类型
     */
    const SHUNFENG_TYPE = 2;


    public function index()
    {
        $data = [
            '1' => 2
        ];
        $ext['count'] = 1;

        $this->out_put_msg(1, '', $data, 'hotel/goods/get_list', 200, $ext);
    }

    /**
     * 其他物流发货（除了顺丰对接，即手动发货后填运单）
     *
     * POST request format array('shipping_id' => '557', 'distributor' => '', 'tracking_no' => '')
     * @author daikanwu
     */
    public function create_other_shipping_order()
    {
        $post = json_decode($this->input->raw_input_stream, true);

        if (empty($post['shipping_id'])) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, 'shippingId不能为空', '', $this->route);
        }

        //检查物流商
        if (empty($post['distributor'])) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, '快递商不能为空', '', $this->route);
        }

        if (empty($post['tracking_no'])) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, '快递单号不能为空', '', $this->route);
        }

        $this->load->model('soma/Consumer_shipping_model', 'shipping_model');
        $model = $this->shipping_model;
        $pk = $model->table_primary_key();

        //检查运单是否存在
        $model = $model->load($post[$pk]);

        if (!$model) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, '该运单不存在', '', $this->route);
        }

        //检查订单是否存在
        $this->load->model('soma/sales_order_model', 'sale_order_model');
        $order_detail = $this->sale_order_model->getByID($model->m_get('order_id'));
        if (empty($order_detail)) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, '订单不存在');
        }

        //检查订单状态
        if ($model->m_get('status') == $model::STATUS_SHIPPED) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, '该订单已发货', '', $this->route);
        }

        //拉取分账表数据
        $separate_model = new SeparateBilling();
        $separate_info = $separate_model->getOrderBillingInfo($model->m_get('order_id'));
        $post['status'] = $model::STATUS_SHIPPED;

        if (empty($separate_info)) {
            //分帐表数据空的话只走发货流程
            //更新订单状态与发送模板消息
            $this->updateShippingStatusAndSendNotice($post, $model, $pk);

            $this->out_put_msg(AdminConst::OPER_STATUS_SUCCESS, '');
        } else {
            $product_id = $model->m_get('product_id');
            $this->load->model('soma/Product_package_model', 'product_model');
            $product_detail = $this->product_model->get_product_package_phone_by_product_id($product_id, $this->inter_id);

            if ($product_detail['send_hotel'] == -1) {
                $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, '发货酒店没配置，不能发货');
            }
            if ($product_detail['send_hotel'] < 0) {
                $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, 'send_hotel小于0, 不能发货');
            }

            //更新订单状态与发送模板消息
            $this->updateShippingStatusAndSendNotice($post, $model, $pk);

            //分帐
            try {
                $this->saveSeparateBill($separate_model, $separate_info, $product_detail);
            } catch (\Exception $e) {
                $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, $e->getMessage());
            }

            $this->out_put_msg(AdminConst::OPER_STATUS_SUCCESS, '');
        }
    }

    /**
     * 批量下单
     *
     * POST请求
     * @example /index.php/iapi/v1/soma/express/batch_create_order
     * POST request format array('shipping_id' => '557,558')
     * return json format
     * <code>
     * {
     * "status": 1,
     * "msg": "",
     * "msg_type": "",
     * "web_data":{
     * 'shipping_id' => array(
     * 'message' => '下单成功',
     * 'order_id' => '994594859',
     * 'tracking_no' => '98988989'
     * )
     * }
     * }
     * </code>
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function batch_create_order()
    {
        Log::error('baccccorderr');
        $post = json_decode($this->input->raw_input_stream, true);

        $shipping_ids = explode(',', $post['shipping_id']);
        if (empty($post['shipping_id'])) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_ALERT, 'shippingId不能为空', '', $this->route);
        }

        //如果还未配月结卡号 暂不支持该接口
        $this->config->load('express', true, true);

        $config = $this->config->item('express');
        if (ENVIRONMENT == 'production') {
            $inter_ids = array_keys($config['shunfeng']['productCustid']);
        } else {
            $inter_ids = array_keys($config['shunfeng']['devCustid']);
        }
        if (!in_array($this->inter_id, $inter_ids)) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_ALERT, '无月结卡号，暂不支持该接口');
        }

        //获取shipping信息
        $this->load->model('soma/Consumer_shipping_model', 'shipping_model');
        $model = $this->shipping_model;
        $pk= $model->table_primary_key();
        $shipping_info = $model->get_shipping_info(array('shipping_id' => $shipping_ids), $this->inter_id);

        if (empty($shipping_info)) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_ALERT, '订单不存在', '', $this->route);
        }

        //记录校验不通过的 order
        $check_fail = array();
        $create_fail = $create_success = array();
        $separate_model = new SeparateBilling();
        $this->load->model('soma/Product_package_model', 'product_model');
        foreach ($shipping_info as $v) {
            if ($v['status'] == $model::STATUS_SHIPPED) {
                $check_fail[$v['shipping_id']] = array(
                    'message' => '该订单已发货',
                    'order_id' => $v['order_id']
                );
                continue;
            }
            if (empty($v['order_id'])) {
                $check_fail[$v['shipping_id']] =  array(
                    'message' => '订单号为空',
                    'order_id' => $v['order_id']
                );
                continue;
            }
            if (empty($v['name'])) {
                $check_fail[$v['shipping_id']] =  array(
                    'message' => '货物名称为空',
                    'order_id' => $v['order_id']
                );
                continue;
            }
            if (empty($v['address'])) {
                $check_fail[$v['shipping_id']] =  array(
                    'message' => '收货地址为空',
                    'order_id' => $v['order_id']
                );
                continue;
            }
            if (empty($v['contacts'])) {
                $check_fail[$v['shipping_id']] =  array(
                    'message' => '收件人为空',
                    'order_id' => $v['order_id']
                );
                continue;
            }
            if (empty($v['phone'])) {
                $check_fail[$v['shipping_id']] =  array(
                    'message' => '收件人手机为空',
                    'order_id' => $v['order_id']
                );
                continue;
            }

            $model = $model->load($v[$pk]);
            //拉取分账表数据 重构
            $separate_info = $separate_model->getOrderBillingInfo($model->m_get('order_id'));
            $update_data['distributor'] = 'a_sf';

            if (empty($separate_info)) {
                //分帐表数据空的话只走发货流程
                //调用顺丰接口
                $provider = new ExpressProvider();
                $express = $provider->resolve($provider::TYPE_SF);
                $res = $express->createShippingOrder($v);
                if ($res->getStatus() == Result::STATUS_FAIL) {
                    $create_fail[$v['shipping_id']] =  array(
                        'message' => $res->getMessage(),
                        'order_id' => $v['order_id'],
                        'tracking_no' => ''
                    );
                    $update_data['status'] = Consumer_shipping_model::STATUS_SHIPPED_FAIL;
                    $update_data['post_time'] = date('Y-m-d H:i:s');
                    $update_data['shipping_id'] = $v[$pk];
                } else {
                    $create_success[$v['shipping_id']] = array(
                        'message' => '下单成功',
                        'order_id' => $v['order_id'],
                        'tracking_no' => $res->getData()
                    );
                    $update_data['status'] = Consumer_shipping_model::STATUS_SHIPPED;
                    $update_data['tracking_no'] = $res->getData();
                    $update_data['post_time'] = date('Y-m-d H:i:s');
                    $update_data['shipping_id'] = $v[$pk];
                }

                //更新订单状态与发送模板消息
                $this->updateShippingStatusAndSendNotice($update_data, $model, $pk);
            } else {
                $product_id = $model->m_get('product_id');
                $product_detail = $this->product_model->get_product_package_phone_by_product_id($product_id, $this->inter_id);
                if ($product_detail['send_hotel'] == -1) {
                    $check_fail[$v[$pk]]['message'] = '发货酒店没配置，不能发货';
                    $check_fail[$v[$pk]]['order_id'] = $v['order_id'];
                    continue;
                }
                if ($product_detail['send_hotel'] < 0) {
                    $check_fail[$v[$pk]]['message'] = 'send_hotel小于0, 不能发';
                    $check_fail[$v[$pk]]['order_id'] = $v['order_id'];
                    continue;
                }

                //调用顺丰接口
                $provider = new ExpressProvider();
                $express = $provider->resolve($provider::TYPE_SF);
                $res = $express->createShippingOrder($v);
                if ($res->getStatus() == Result::STATUS_FAIL) {
                    $create_fail[$v['shipping_id']] =  array(
                        'message' => $res->getMessage(),
                        'order_id' => $v['order_id'],
                        'tracking_no' => ''
                    );
                    $update_data['status'] = Consumer_shipping_model::STATUS_SHIPPED_FAIL;
                    $update_data['post_time'] = date('Y-m-d H:i:s');
                    $update_data['shipping_id'] = $v[$pk];
                } else {
                    $create_success[$v['shipping_id']] = array(
                        'message' => '下单成功',
                        'order_id' => $v['order_id'],
                        'tracking_no' => $res->getData()
                    );
                    $update_data['status'] = Consumer_shipping_model::STATUS_SHIPPED;
                    $update_data['tracking_no'] = $res->getData();
                    $update_data['post_time'] = date('Y-m-d H:i:s');
                    $update_data['shipping_id'] = $v[$pk];
                }

                //更新订单状态与发送模板消息
                $this->updateShippingStatusAndSendNotice($update_data, $model, $pk);

                //分帐
                $this->saveSeparateBill($separate_model, $separate_info, $product_detail);
            }
        }

        //计算成功单数和失败单数
        $tmp = $check_fail+$create_fail;
//        $create = $create_fail+$create_success;
        $count = count($shipping_info);
        $success_count = count($create_success);
        $fail_count = count($tmp);

        if ($fail_count == $count) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, '选择'.$count.'单,全部发货失败', $tmp, $this->route);
        }

        if ( $fail_count > 0)  {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, '选择'.$count."单，{$success_count}单成功"."{$fail_count}单失败，失败订单可手动推送", $tmp, $this->route);
        }

        $this->out_put_msg(AdminConst::OPER_STATUS_SUCCESS, '选择'.$count.'单，全部发货成功', $create_success, $this->route);

    }


    /**
     * 获取订单列表
     *
     * @example GET /index.php/iapi/v1/soma/express/get_order_list?like=77&status=1&begin_time=&end_time=&page_num=1&page_size=20&type=1
     * $_GET['like'] 搜索条件
     * $_GET['status'] 状态 传1未发货 2发货 空搜全部
     * @author daikanwu
     */
    public function get_order_list()
    {
        $data = $this->input->get();

        // 分页
        $page = array('page_num' => 1, 'page_size' => 20);
        if ($page_size = $this->input->get('page_size', true)) {
            $page['page_size'] = $page_size;
        }
        if ($page_num = $this->input->get('page_num', true)) {
            $page['page_num'] = $page_num;
        }
        if (empty($data['type'])) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, 'type类型不能为空', '', $this->route);
        }
        if (!in_array($data['type'], array(self::OTHER_SHIPPING_TYPE, self::SHUNFENG_TYPE))) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, 'type只能为1或2', '', $this->route);
        }

        $this->load->model('soma/Consumer_shipping_model', 'shipping_model');
        $model = $this->shipping_model;

        //组装搜索条件
        $filter = array(
            'inter_id' => $this->inter_id,
        );
        if (!empty($data['begin_time'])) {
            $filter['create_time >='] = $data['begin_time'];
        }
        if (!empty($data['end_time'])) {
            $filter['create_time <='] = $data['end_time'] . ' 23:59:59';
        }
        if (!empty($data['begin_time']) && !empty($data['end_time'])) {
            if ($data['begin_time'] > $data['end_time']) {
                $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, '开始时间不能大于结束时间', '', $this->route);
            }
        }
        if (!empty($data['status'])) {
            if ($data['type'] == self::SHUNFENG_TYPE) {
                $filter['status'] = (int)$data['status'];
            } else {
                if ($data['status'] != $model::STATUS_SHIPPED) {
                    $filter['status'] = (int)$data['status'];
                } else {
                    $filter['status'] = array($model::STATUS_SHIPPED, $model::STATUS_FINISHED);
                }
            }
        }
        if ($data['type'] == self::SHUNFENG_TYPE && !empty($data['status']) && $data['status'] != $model::STATUS_APPLY) {
            $filter['distributor'] = 'a_sf';
        }

        $like_condition = trim($data['like']);

        //快递中文 =》 快递英文
        $dist_result = $model->get_express();
        $dist_label = array_column($dist_result, 'dist_label');
        $dist_map = array_column($dist_result, 'dist_name', 'dist_label');
        if (in_array($like_condition, $dist_label)) {
            $like_condition = $dist_map[$like_condition];
        }

        $like = array();
        if (!empty($like_condition)) {
            $like = [
                ['and', 'order_id', $like_condition], ['or', 'name', $like_condition], ['or', 'contacts', $like_condition],
                ['or', 'phone', $like_condition], ['or', 'tracking_no', $like_condition], ['or', 'distributor', $like_condition],
            ];
        }

        $select = array('shipping_id,order_id,shipping_order,name,qty,inter_id,openid,address,contacts,phone,create_time,distributor,tracking_no,status,note');

        //获取数据
        $result = $model->get_list($filter, $this->inter_id, $select, $page, $like, $data['type']);

        $ext['page'] = $result['page_num'];
        $ext['size'] = $result['page_size'];
        $ext['count'] = $result['total'];

        $tmp = array(
            'data' => $result['data'],
            'csrf' => $this->common_data
        );

        $this->out_put_msg(AdminConst::OPER_STATUS_SUCCESS, '', $tmp, $this->route, 200, $ext);
    }


    /**
     * 导出订单列表
     *
     * @example GET /index.php/iapi/v1/soma/express/export_order_list?&like=&status=&begin_time=&end_time=&type
     * $_GET['like'] 搜索条件
     * $_GET['status'] 状态 传1未发货 2发货 空搜全部
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function export_order_list()
    {
//        $this->load->model('soma/Consumer_shipping_model');
        $start = $this->input->get('begin_time');
        $end = $this->input->get('end_time');
        $status = $this->input->get('status');
        $like_condition = $this->input->get('like');
        $type = (int)$this->input->get('type');
        $inter_id = $this->inter_id;
        if (empty($type)) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, 'type类型不能为空', '', $this->route);
        }
        if (!in_array($type, array(self::OTHER_SHIPPING_TYPE, self::SHUNFENG_TYPE))) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, 'type只能为1或2', '', $this->route);
        }

        if ($inter_id == FULL_ACCESS) {
            $inter_id = $this->current_inter_id;
        }

        $this->load->model('soma/Consumer_shipping_model');
        $shipping_model = $this->Consumer_shipping_model;
        $filter = array();
        if ($status) {
            if ($type == self::SHUNFENG_TYPE) {
                $filter['status'] = (int)$status;
            } else {
                if ($status != $shipping_model::STATUS_SHIPPED) {
                    $filter['status'] = (int)$status;
                } else {
                    $filter['status'] = array($shipping_model::STATUS_SHIPPED, $shipping_model::STATUS_FINISHED);
                }
            }
        }
        if ($start) $filter['create_time >='] = $start;
        if ($end) $filter['create_time <='] = $end . ' 23:59:59';

        if ($type == self::SHUNFENG_TYPE && !empty($status)) {
            $filter['distributor'] = 'a_sf';
        }
        //快递中文 =》 快递英文
//        $this->load->model('soma/Consumer_shipping_model');
        $dist_result = $this->Consumer_shipping_model->get_express();
        $dist_label = array_column($dist_result, 'dist_label');
        $dist_map = array_column($dist_result, 'dist_name', 'dist_label');
        if (in_array($like_condition, $dist_label)) {
            $like_condition = $dist_map[$like_condition];
        }

        $like = array();
        if ($like_condition) {
            $like = [
                ['and', 'order_id', $like_condition], ['or', 'name', $like_condition], ['or', 'contacts', $like_condition],
                ['or', 'phone', $like_condition], ['or', 'tracking_no', $like_condition], ['or', 'distributor', $like_condition],
            ];
        }
        $select = array('shipping_id,order_id,shipping_order,name,qty,inter_id,openid,address,contacts,phone,create_time,distributor,tracking_no,status,note');

        $data = $this->Consumer_shipping_model->export_order($filter, $inter_id, $select, $like, $type);

        if (empty($data)) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, '数据为空，无法导出', '');
        }
        $tmp = array();
        foreach ($data as $v) {
            $tmp[] = array(
                'shipping_id' => $v['shipping_id'],
                'create_time' => $v['create_time'],
                'name' => $v['name'],
                'per_price' => $v['per_price'],
                'qty' => $v['qty'],
                'order_id' => $v['order_id'],
                'real_pay' => $v['real_pay'],
                'buyer' => $v['buyer'],
                'buyer_phone' => $v['buyer_phone'],
                'contacts' => $v['contacts'],
                'phone' => $v['phone'],
                'address' => $v['address'],
                'remark' => empty($v['remark']) ? '' : $v['remark'],
                'saler' => ($type == self::OTHER_SHIPPING_TYPE) ? $v['saler_id'] . '/' . $v['saler_name'] : $v['status'],
                'distributor' => empty($v['distributor']) ? '' : $v['distributor'],
                'tracking_no' => empty($v['tracking_no']) ? '' : $v['tracking_no'],
            );
        }
        $header = array('物流序号', '提交时间', '商品名称', '发货价格', '发货数量', '订单号', '订单实付', '购买人', '购买人联系电话', '收件人', '联系电话', '地址');
        if ($type == self::OTHER_SHIPPING_TYPE) {
            array_push($header, '备注', '分销员&ID', '物流公司', '快递单号');
        } else {
            array_push($header, '备注', '状态', '物流公司', '快递单号');
        }
        $this->_do_export($tmp, $header, 'csv', true);

    }


    /**
     * 批量导入订单
     *
     * @example POST /index.php/iapi/v1/soma/express/batch_post
     * $_POST['distributor'] 快递商
     * $_POST['path'] 路径
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function batch_post()
    {
        $post = json_decode($this->input->raw_input_stream, true);
        $distributor = $post['distributor'];
        $path = $post['path'];

        if (empty($distributor)) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, '请选择快递商', '', $this->route);
        }
        if (empty($path)) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, '文件路径为空', '', $this->route);
        }


        //组装上传的数据＝》array
        $obj = fopen($path, 'r');

        $batch_data = array();
        $n = 0;
        while ($data = fgetcsv($obj)) {
            $num = count($data);
            for ($i = 0; $i < $num; $i++) {
                $batch_data[$n][$i] = $data[$i];
            }
            $n++;
        }

        //组成shipping_id => tracking_no 映射
        unset($batch_data[0]);//第一行数据是中文描述头，第二行开始才是数据

        $shippingIds = array();
        foreach ($batch_data as $k => $v) {
            $shippingIds[$v[0]] = isset($v[15]) ? htmlspecialchars($v[15]) : '';
        }

        //校验表格格式
        if (empty($shippingIds)) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, '文件数据为空，请重选');
        }
        $tmp_keys = array_keys($shippingIds);
        if (!is_numeric($tmp_keys[0])) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, '物流序号不是数字，请重选表格');
        }

        $this->load->model('soma/Consumer_shipping_model', 'shipping_model');
        $model = $this->shipping_model;
        $pk = $model->table_primary_key();

        //查找物流信息
        $list = $model->get_shipping_info(['shipping_id' => $tmp_keys], $this->inter_id);
        if (empty($list)) {
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, '物流信息不存在');
        }

        $update_data = array();
        $update_data['distributor'] = $distributor;
        $update_data['status'] = $model::STATUS_SHIPPED;
        $update_data['post_admin'] = $this->session->get_admin_username();
        $update_data['remote_ip'] = $this->input->ip_address();

        $fail_data = $openids = array();
        $separate_model = new SeparateBilling();
        $this->load->model('soma/Product_package_model', 'product_model');
        foreach ($list as $k => $v) {
            if (empty($v['address'])) {
                $fail_data[$k]['message'] = '地址信息不能为空';
                $fail_data[$k][$pk] = $v[$pk];
                continue;
            }
            if ($v['status'] == $model::STATUS_SHIPPED) {
                $fail_data[$k]['message'] = '已发货';
                $fail_data[$k][$pk] = $v[$pk];
                continue;
            }
            if (empty($shippingIds[$v[$pk]])) {
                $fail_data[$k]['message'] = '快递单不能为空';
                $fail_data[$k][$pk] = $v[$pk];
                continue;
            }
            if (strpos($shippingIds[$v[$pk]], 'E+') !== false) {
                $fail_data[$k]['message'] = 'csv文件的快递单号含有有E+符号！请转化成纯数字';
                $fail_data[$k][$pk] = $v[$pk];
                continue;
            }

            $update_data['tracking_no'] = $shippingIds[$v[$pk]];
            $update_data['post_time'] = date('Y-m-d H:i:s');
            $update_data['shipping_id'] = $v[$pk];

            $model = $model->load($v[$pk]);

            //拉取分账表数据 重构
            $separate_info = $separate_model->getOrderBillingInfo($model->m_get('order_id'));
            if (empty($separate_info)) {
                //分帐表数据空的话只走发货流程
                //更新订单状态与发送模板消息
                $this->updateShippingStatusAndSendNotice($update_data, $model, $pk);
            } else {
                $product_id = $model->m_get('product_id');
                $product_detail = $this->product_model->get_product_package_phone_by_product_id($product_id, $this->inter_id);
                if ($product_detail['send_hotel'] == -1) {
                    $fail_data[$k]['message'] = '发货酒店没配置，不能发货';
                    $fail_data[$k][$pk] = $v[$pk];
                    continue;
                }
                if ($product_detail['send_hotel'] < 0) {
                    $fail_data[$k]['message'] = 'send_hotel小于0, 不能发';
                    $fail_data[$k][$pk] = $v[$pk];
                    continue;
                }

                //更新订单状态与发送模板消息
                $this->updateShippingStatusAndSendNotice($update_data, $model, $pk);

                //分帐
                $this->saveSeparateBill($separate_model, $separate_info, $product_detail);
            }
        }

        $success_count = count($shippingIds) - count($fail_data);
        $notice = '成功' . $success_count . '单,' . '失败' . count($fail_data) . '单';
        if (count($fail_data) > 0) {
            $tmp = [];
            foreach ($fail_data as $v) {
                $tmp[] = $v;
            }
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, $notice, $tmp);
        }

        $this->out_put_msg(AdminConst::OPER_STATUS_SUCCESS, $notice);
    }

    /**
     * 上传文件
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function do_upload()
    {
        $tmppath = FD_ . 'upload' . DS;
        if (!file_exists($tmppath)) @mkdir($tmppath, 0777, TRUE);
        $urlpath = base_url('public/upload') . '/';
        $config['upload_path'] = './public/upload/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = 1024;
        $config['file_name'] = 'soma_shipping_upload_' . (microtime(true) * 10000) . '.csv';
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            $error = array('error' => $this->upload->display_errors());
            $this->out_put_msg(AdminConst::OPER_STATUS_FAIL_TOAST, '上传失败', $error, $this->route);
        }

        $this->out_put_msg(AdminConst::OPER_STATUS_SUCCESS, '', array('path' => './public/upload/' . $config['file_name']));
    }


    /**
     * 导出表格
     * @param $data
     * @param $header
     * @param string $type
     * @param bool $download
     * @return string
     * @author daikanwu <daikanwu@jperation.com>
     */
    protected function _do_export($data, $header, $type = 'csv', $download = TRUE)
    {
        switch ($type) {
            case 'csv':
                $tmppath = FD_ . 'export' . DS;
                $urlpath = base_url('public/export') . '/';
                if (!file_exists($tmppath)) @mkdir($tmppath, 0777, TRUE);
                $tmpfile = $this->module . '_' . $this->controller . '_' . $this->action . '_'
                    . date('ymdHis_' . rand(10, 99)) . '.' . $type;

                if ($download == TRUE) {
                    header('Content-Type: text/csv');
                    header('Content-Disposition: attachment;filename=' . $tmpfile);
                }

                $fp = fopen($tmppath . $tmpfile, 'w');

                //转换字符集 把逗号替换成分号，不然用excel打开有问题
                array_unshift($data, $header);
                foreach ($data as $k => $v) {
                    foreach ($v as $sk => $sv) {
                        $data[$k][$sk] = convert_to_gbk($sv);
                        if (strpos($data[$k][$sk], ',') !== false) {
                            $data[$k][$sk] = str_replace(',', ';', $data[$k][$sk]);
                        }
                    }
                }

                if ($fp) {
                    //循环插入数据
                    foreach ($data as $line) {
                        if ($download == TRUE) {
                            echo implode(',', $line) . "\n";
                        }
                        fputcsv($fp, $line, ',', '"');
                    }
                    fclose($fp);
                }
                break;
            default:
                return '';
        }
        //上传到ftp

        //@unlink($tmppath. $tmpfile);
        return $urlpath . $tmpfile;
    }

    /**
     * 快递下拉列表
     * @return mixed
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function get_express_list()
    {
        $this->load->model('soma/Consumer_shipping_model', 'shipping_model');
        $shipping_model = $this->shipping_model;
        $result = $shipping_model->get_express();

        $res = array(
            'data' => $result,
            'csrf' => $this->common_data
        );
        $this->out_put_msg(AdminConst::OPER_STATUS_SUCCESS, '', $res, $this->route);

    }


    /**
     * 更新运单状态并发送模板消息
     * @param $post
     * @param $model
     * @param $pk
     * @return bool
     * @author daikanwu <daikanwu@jperation.com>
     */
    protected function updateShippingStatusAndSendNotice($post, $model, $pk)
    {
//        $response = new Result();
        //更新运单状态
        $post['post_admin'] = $this->session->get_admin_username();
        $post['remote_ip'] = $this->input->ip_address();

        $result = $model->load($post[$pk])->post_shipping($post);
//        if (!$result) {
//            $response->setMessage('更新订单状态失败');
//            return $response;
//        }

        //发送模板消息
        $this->load->model('soma/Message_wxtemp_template_model', 'MessageWxtempTemplateModel');
        $MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;
        $inter_id = $this->inter_id;
        $business = 'package';
        $model = $model->load($post[$pk]);
        $openid = $model->m_get('openid');
        $model->distributor = $post['distributor'];
        $model->tracking_no = $post['tracking_no'];
        $model->consumer_id = $model->m_get('consumer_id');
        $MessageWxtempTemplateModel->send_template_by_shipping_success($model, $openid, $inter_id, $business);

//        $response->setStatus(Result::STATUS_OK);
//        return $response;
    }

    /**
     * 保存分帐信息
     * @param $separate_model 分账模型
     * @param $separate_info 分账具体信息
     * @param $product_detail 商品详情
     * @return bool
     * @author daikanwu <daikanwu@jperation.com>
     */
    protected function saveSeparateBill($separate_model, $separate_info, $product_detail)
    {
//        $separate_info = $separate_model->getOrderBillingInfo($model->m_get('order_id'));
        $save_data['bill_hotel'] = $product_detail['send_hotel'];
        $save_data['bill_id'] = $separate_info[0]['bill_id'];
        $save_data['status'] = SeparateBilling::STATUS_CAN_PAY_YES;
        $separate_model->saveBilling($save_data);
    }

}