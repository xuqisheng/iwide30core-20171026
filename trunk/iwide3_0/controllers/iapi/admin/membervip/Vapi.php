<?php
// +----------------------------------------------------------------------
// | 前端模版数据处理模块
// +----------------------------------------------------------------------
// | Author: liwensong <septet-l@outlook.com>
// +----------------------------------------------------------------------
// | Vapi.php 2017-06-16
// +----------------------------------------------------------------------

use App\services\vip\StatementsService;

defined('BASEPATH') or exit('No direct script access allowed');

class Vapi extends MY_Admin_Iapi
{

    private $hotels = '';
    private $hotel_ids = array();

    private $admin_profile = array();
    public function __construct()
    {
        parent::__construct();
        $this->load->model('membervip/common/Public_model', 'common_model');
        $this->load->model('membervip/common/Public_log_model', 'common_logm');
        $this->load->model('membervip/admin/Vapi_logic', 'vapi_logic');
        $this->admin_profile = $this->session->userdata['admin_profile'];

        $this->hotels =  $this->session->get_admin_hotels();
        if(!empty($this->hotels))
            $this->hotel_ids  = explode(",",$this->hotels);

    }

    public function coupon_task()
    {
        $msg = array(
            'status' => 1004,
            'err'    => '9999',
            'msg'    => '请求失败',
        );
        if (!empty($this->admin_profile['inter_id'])) {
            $inter_id = $this->admin_profile['inter_id'];
            $tag_data = $this->vapi_logic->coupon_task_list($inter_id);
            $this->_ajaxReturn($tag_data);
        }
        $this->_ajaxReturn($msg);
    }

    public function task_item()
    {
        $msg = array(
            'status' => 1004,
            'err'    => '9999',
            'msg'    => '请求失败',
        );

        if (!empty($this->admin_profile['inter_id'])) {
            $inter_id = $this->admin_profile['inter_id'];
            $task_id  = $this->input->get('id');
            if (empty($task_id)) {
                $task_id = $this->input->post('id');
            }
            if (empty($task_id)) {
                $msg['err'] = 9000;
                $msg['msg'] = '参数错误';
                $this->_ajaxReturn($msg);
            }
            $tag_data = $this->vapi_logic->task_item($inter_id, $task_id);
            $this->_ajaxReturn($tag_data);
        }
        $this->_ajaxReturn($msg);
    }

    //优惠批量发放配置
    public function create_coupon_task()
    {
        $msg = array(
            'status' => 1004,
            'err'    => '9999',
            'msg'    => '请求失败',
        );

        if (!empty($this->admin_profile['inter_id'])) {
            $inter_id = $this->admin_profile['inter_id'];
            $task_id  = $this->input->get('id');
            if (empty($task_id)) {
                $task_id = $this->input->post('id');
            }
            $tag_data = $this->vapi_logic->coupon_task_tag($inter_id, $task_id);
            $this->_ajaxReturn($tag_data);
        }
        $this->_ajaxReturn($msg);
    }

    public function get_temp_conf()
    {
        $msg = array(
            'status' => 1004,
            'err'    => '9999',
            'msg'    => '请求失败',
        );

        if (!empty($this->admin_profile['inter_id'])) {
            $inter_id = $this->admin_profile['inter_id'];
            $type     = $this->input->post('temp_type');
            if (empty($type)) {
                $type = $this->input->post('temp_type');
            }
            $temp_conf = $this->vapi_logic->get_temp_conf($inter_id, $type);
            if (empty($temp_conf)) {
                $temp_conf = $this->_get_default_temp_conf($type);
                if (empty($temp_conf)) {
                    $msg['msg']    = '找不到数据';
                    $msg['status'] = 1002;
                    $msg['err']    = '40050';
                } else {
                    $msg = array(
                        'status'   => 1000,
                        'err'      => 0,
                        'msg'      => 'OK',
                        'msg_type' => 'toast',
                        'web_data' => $temp_conf,
                    );
                }
            } else {
                $msg = array(
                    'status'   => 1000,
                    'err'      => 0,
                    'msg'      => 'OK',
                    'msg_type' => 'toast',
                    'web_data' => $temp_conf,
                );
            }
            $this->_ajaxReturn($msg);
        }
        $this->_ajaxReturn($msg);
    }

    protected function _get_default_temp_conf($type = '')
    {
        if (empty($type)) {
            return false;
        }

        $conf = array();
        switch ($type) {
            case 'interest_account':
                $conf = array(
                    'temp_title_field' => 'interest_account',
                    'temp_title_value' => '会员权益到账通知',
                    'first'            => '您好，你的会员奖励已经成功到账',
                    'keyword1'         => '{membernum}{username}',
                    'keyword2'         => '{temp_content}',
                    'keyword3'         => '会员奖励',
                    'keyword4'         => '{send_time}',
                    'remark'           => '马上查看',
                    'temp_hint'        => array(
                        '优惠发放模板默认使用该模板，请在微信公众号后台将该模板添加到模板库，并确保模板ID填写正确。',
                        '模板行业：酒店旅游 - 酒店',
                        '模板编号：OPENTM400417346',
                        '模板标题：会员权益到账通知',
                    ),
                    'temp_contet_hint' => array(
                        '{{first.DATA}}',
                        '会员卡号：{{keyword1.DATA}}',
                        '成功到账：{{keyword2.DATA}}',
                        '来源：{{keyword3.DATA}}',
                        '到账时间：{{keyword4.DATA}}',
                        '{{remark.DATA}}',
                    ),
                    'temp_field_hint'  => array(
                        '会员卡号：{membernum}',
                        '优惠券发送内容：{temp_content}',
                        '发送时间：{send_time}',
                        '会员名称：{username}',
                    ),
                );
                break;
            case 'coupon_expiration':
                $conf = array(
                    'temp_title_field' => 'coupon_expiration',
                    'temp_title_value' => '会员到期提醒',
                    'first'            => '您的优惠券马上到期，请尽快使用',
                    'name'             => '请填写到期内容',
                    'expDate'          => '请填写到期日',
                    'remark'           => '马上查看',
                    'temp_hint'        => array(
                        '模板名称：会员到期提醒',
                        '模板行业：IT科技 - 互联网|电子商务',
                        '模板编号：TM00008',
                    ),
                    'temp_contet_hint' => array(
                        '{{first.DATA}}',
                        '您的{{name.DATA}}有效期至{{expDate.DATA}}。',
                        '{{remark.DATA}}',
                    ),
                    'temp_field_hint'  => array(
                        '无',
                    ),
                );
                break;
            case 'membership_review':
                $conf = array(
                    'temp_title_field' => 'membership_review',
                    'temp_title_value' => '会员资料审核提醒',
                    'first'            => '您的审核结果',
                    'keyword1'         => '审核结果',
                    'keyword2'         => '审核结果原因',
                    'remark'           => '马上查看',
                    'temp_hint'        => array(
                        '模板名称：会员资料审核提醒',
                        '模板行业：IT科技-互联网|电子商务',
                        '模板编号：OPENTM201057607',
                    ),
                    'temp_contet_hint' => array(
                        '{{first.DATA}}',
                        '审核结果：{{keyword1.DATA}}',
                        '原因：{{keyword2.DATA}}',
                        '{{remark.DATA}}',
                    ),
                    'temp_field_hint'  => array(
                        '无',
                    ),
                );
                break;
            case 'service_status':
                $conf = array(
                    'temp_title_field' => 'service_status',
                    'temp_title_value' => '服务状态提醒',
                    'first'            => '',
                    'keyword1'         => '服务名称',
                    'keyword2'         => '服务进度',
                    'remark'           => '',
                    'temp_hint'        => array(
                        '模板名称：会员资料审核提醒',
                        '模板行业：IT科技-互联网|电子商务',
                        '模板编号：OPENTM401684051',
                    ),
                    'temp_contet_hint' => array(
                        '{{first.DATA}}',
                        '服务名称：{{keyword1.DATA}}',
                        '服务进度：{{keyword2.DATA}}',
                        '{{remark.DATA}}',
                    ),
                    'temp_field_hint'  => array(
                        '无',
                    ),
                );
                break;
            default:
                # code...
                break;
        }
        return $conf;
    }

    /**
     * @SWG\Get(
     *     tags={"Vapi"},
     *     path="vapi/hotels_list",
     *     summary="酒店列表",
     *     description="酒店列表",
     *     operationId="hotels_list",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              @SWG\Property(
     *                  property="hotel_id",
     *                  description="酒店id",
     *                  type = "integer"
     *              ),
     *              @SWG\Property(
     *                  property="name",
     *                  description="酒店名",
     *                  type = "string"
     *              )
     *         )
     *      )
     * )
     */
    public function hotels_list()
    {
        $msg = array(
            'status' => 1004,
            'err'    => '9999',
            'msg'    => '请求失败',
        );
        if (!empty($this->admin_profile['inter_id'])) {
            $inter_id = $this->admin_profile['inter_id'];
            $this->load->model('membervip/admin/Vapi_statements', 'statements');

            $select     = "hotel_id,name";
            $tag_data   = $this->statements->hotel_list($inter_id, $select,$this->hotel_ids);
            $returnData = $this->initReturnData($tag_data);
            $this->_ajaxReturn($returnData);
        }
        $this->_ajaxReturn($msg);
    }

    public function reg_distribution_statements()
    {
        $returnData = array(
            'status' => 1004,
            'err'    => '9999',
            'msg'    => '请求失败',
        );

        $request_params = $this->input->get();
//        if(empty($request_params))
        //            $this->_ajaxReturn($returnData);
        //        $request_params['sales_id'] = 54;
        //        $request_params['time_type'] = 'send_time';
        //        $request_params['start_time'] ='2017-09-20';
        //        $request_params['end_time'] ='2017-09-22';
        //        $request_params['hotel_id'] ='180';
        //        $request_params['page'] = 2;

        if (isset($request_params['hotel_id']) && !empty($request_params['hotel_id'])) {
            $hotel_id = $request_params['hotel_id'];
            if (!empty($this->hotel_ids) && is_array($this->hotel_ids)) {
                if(!in_array($hotel_id,$this->hotel_ids)){
                    $returnData['status'] = 1000;
                    $returnData['err'] = 0;
                    $returnData['msg'] = '权限不足，无法查看其它酒店数据';
                    $this->_ajaxReturn($returnData);
                }
            }
        }else if( !empty($this->hotel_ids) && is_array($this->hotel_ids)){
            $request_params['hotel_id'] = $this->hotel_ids;
        }

        $result                = StatementsService::getInstance()->reg_distribution($request_params);
        $result['export_link'] = base_url("index.php/membervip/memberexport/export_reg_distribution");
        if (empty($result)) {
            $returnData['status'] = 1000;
            $returnData['err']    = 0;
            $returnData['msg']    = '数据为空';
            $this->_ajaxReturn($returnData);
        }
//        print_r($result);exit;
        $returnData = $this->initReturnData($result);
        $this->_ajaxReturn($returnData);

    }

    //购卡分销
    public function deposit_card_statements()
    {
        $returnData = array(
            'status' => 1004,
            'err'    => '9999',
            'msg'    => '请求失败',
        );

        $request_params = $this->input->get();

//          $request_params['sales_id'] = 8;
        //        $request_params['time_type'] = 'update_time';
        //        $request_params['start_time'] ='2017-08-11';
        //        $request_params['end_time'] ='2017-09-25';
        //        $request_params['hotel_id'] ='180';
        //        $request_params['page'] = 1;

        //酒店权限
        if (isset($request_params['hotel_id']) && !empty($request_params['hotel_id'])) {
            $hotel_id = $request_params['hotel_id'];
            if (!empty($this->hotel_ids) && is_array($this->hotel_ids)) {
                if(!in_array($hotel_id,$this->hotel_ids)){
                    $returnData['status'] = 1000;
                    $returnData['err'] = 0;
                    $returnData['msg'] = '权限不足，无法查看其它酒店数据';
                    $this->_ajaxReturn($returnData);
                }
            }
        }else if( !empty($this->hotel_ids) && is_array($this->hotel_ids)){
            $request_params['hotel_id'] = $this->hotel_ids;
        }

        $result                = StatementsService::getInstance()->deposit_card($request_params);
        $result['export_link'] = base_url("index.php/membervip/memberexport/export_card_pay_distribution");
        if (empty($result)) {
            $returnData['status'] = 1000;
            $returnData['err']    = 0;
            $returnData['msg']    = '数据为空';
            $this->_ajaxReturn($returnData);
        }

        $returnData = $this->initReturnData($result);
        $this->_ajaxReturn($returnData);
    }

    public function initReturnData($data, $err = 0, $status = 1000, $msg = 'OK', $msg_type = '')
    {
        $tag_data = array(
            'status'   => $status,
            'err'      => $err,
            'msg'      => $msg,
            'msg_type' => $msg_type,
        );
        $tag_data_group = array(
            'csrf_token' => $this->security->get_csrf_token_name(),
            'csrf_value' => $this->security->get_csrf_hash(),
        );
        $tag_data_group['data'] = $data;
        $tag_data['web_data']   = $tag_data_group;
        return $tag_data;
    }

    /**
     * Ajax方式返回数据到客户端
     * @param array $data 要返回的数据
     * @param string $type AJAX返回数据格式
     * @param int $json_option JSON 常量
     */
    protected function _ajaxReturn($data = array(), $type = '', $json_option = 0)
    {

        $data['referer'] = !empty($data['url']) ? $data['url'] : "";
        $data['state']   = (!empty($data['status']) && $data['status'] == '1000') ? "success" : "fail";
        if (empty($type)) {
            $type = 'JSON';
        }

        switch (strtoupper($type)) {
            case 'JSON':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data, $json_option));
            case 'XML':
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit($this->common_model->xml_encode($data));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler = isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
                exit($handler . '(' . json_encode($data, $json_option) . ');');
            case 'EVAL':
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
            case 'AJAX_UPLOAD':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:text/html; charset=utf-8');
                exit(json_encode($data, $json_option));
            default:
                // 中断程序
                exit(0);
        }
    }
}
