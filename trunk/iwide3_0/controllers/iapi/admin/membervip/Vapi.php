<?php
// +----------------------------------------------------------------------
// | 前端模版数据处理模块
// +----------------------------------------------------------------------
// | Author: liwensong <septet-l@outlook.com>
// +----------------------------------------------------------------------
// | Vapi.php 2017-06-16
// +----------------------------------------------------------------------

use App\services\vip\StatementsService;

defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Vapi extends MY_Admin_Iapi{

    private   $admin_profile = array();
    public function __construct(){
        parent::__construct();
        $this->load->model('membervip/common/Public_model','common_model');
        $this->load->model('membervip/common/Public_log_model','common_logm');
        $this->load->model('membervip/admin/Vapi_logic','vapi_logic');
        $this->admin_profile = $this->session->userdata['admin_profile'];
    }

    public function coupon_task(){
        $msg = array(
            'status'=>1004,
            'err'=>'9999',
            'msg'=>'请求失败'
        );
        if(!empty($this->admin_profile['inter_id'])){
            $inter_id = $this->admin_profile['inter_id'];
            $tag_data = $this->vapi_logic->coupon_task_list($inter_id);
            $this->_ajaxReturn($tag_data);
        }
        $this->_ajaxReturn($msg);
    }

    public function task_item(){
        $msg = array(
            'status'=>1004,
            'err'=>'9999',
            'msg'=>'请求失败'
        );

        if(!empty($this->admin_profile['inter_id'])){
            $inter_id = $this->admin_profile['inter_id'];
            $task_id = $this->input->get('id');
            if(empty($task_id)){
                $task_id = $this->input->post('id');
            }
            if(empty($task_id)){
                $msg['err'] = 9000;
                $msg['msg'] = '参数错误';
                $this->_ajaxReturn($msg);
            }
            $tag_data = $this->vapi_logic->task_item($inter_id,$task_id);
            $this->_ajaxReturn($tag_data);
        }
        $this->_ajaxReturn($msg);
    }

    //优惠批量发放配置
    public function create_coupon_task(){
        $msg = array(
            'status'=>1004,
            'err'=>'9999',
            'msg'=>'请求失败'
        );

        if(!empty($this->admin_profile['inter_id'])){
            $inter_id = $this->admin_profile['inter_id'];
            $task_id = $this->input->get('id');
            if(empty($task_id)){
                $task_id = $this->input->post('id');
            }
            $tag_data = $this->vapi_logic->coupon_task_tag($inter_id,$task_id);
            $this->_ajaxReturn($tag_data);
        }
        $this->_ajaxReturn($msg);
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
    public function hotels_list(){
        $msg = array(
            'status'=>1004,
            'err'=>'9999',
            'msg'=>'请求失败'
        );
        if(!empty($this->admin_profile['inter_id'])){
            $inter_id = $this->admin_profile['inter_id'];
            $this->load->model('membervip/admin/Vapi_statements','statements');

            $select = "hotel_id,name";
            $tag_data = $this->statements->hotel_list($inter_id ,$select);
            $returnData = $this->initReturnData($tag_data);
            $this->_ajaxReturn($returnData);
        }
        $this->_ajaxReturn($msg);
    }

    /**
     * @SWG\Get(
     *     tags={"Vapi"},
     *     path="vapi/reg_distribution_statements",
     *     summary="注册分销绩效列表",
     *     description="会员注册分销绩效",
     *     operationId="reg_distribution_statements",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="分销员ID（分销号)",
     *         in="query",
     *         name="sales_id",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="时间类型:createtime核定时间，update_time发放时间",
     *         in="query",
     *         name = "time_type",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="开始日期,example:2017-09-04",
     *         in="query",
     *         name = "start_time",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="结束日期",
     *         in="query",
     *         name = "end_time",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="酒店ID",
     *         in="query",
     *         name = "hotel_id",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              ref="#/definitions/RegDistributionStatements"
     *         )
     *     )
     * )
     */
    public function reg_distribution_statements(){
        $returnData = array(
            'status'=>1004,
            'err'=>'9999',
            'msg'=>'请求失败'
        );

        $request_params = $this->input->get();
//        if(empty($request_params))
//            $this->_ajaxReturn($returnData);
//        $request_params['sales_id'] = 36;
//        $request_params['time_type'] = 'createtime';
//        $request_params['start_time'] ='2017-07-25';
//        $request_params['end_time'] ='2017-09-25';
        $result = StatementsService::getInstance()->reg_distribution($request_params);

        if(empty($result)){
            $returnData['status'] = 1000;
            $returnData['err'] = 0;
            $returnData['msg'] = '数据为空';
            $this->_ajaxReturn($returnData);
        }

        $returnData = $this->initReturnData($result);
        $this->_ajaxReturn($returnData);

    }

    //购卡分销
    /**
     * @SWG\Get(
     *     tags={"Vapi"},
     *     path="vapi/deposit_card_statements",
     *     summary="购卡充值分销绩效列表",
     *     description="购卡充值分销绩效",
     *     operationId="deposit_card_statements",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="分销员ID（分销号)",
     *         in="query",
     *         name="sales_id",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="时间类型:send_time核定时间，update_time发放时间",
     *         in="query",
     *         name = "time_type",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="开始日期,example:2017-09-04",
     *         in="query",
     *         name = "start_time",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="结束日期",
     *         in="query",
     *         name = "end_time",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="酒店ID",
     *         in="query",
     *         name = "hotel_id",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful operation",
     *         @SWG\Schema(
     *              ref="#/definitions/DepositDistributionStatements"
     *         )
     *     )
     * )
     */
    public function deposit_card_statements(){
        $returnData = array(
            'status'=>1004,
            'err'=>'9999',
            'msg'=>'请求失败'
        );

        $request_params = $this->input->get();
        $result = StatementsService::getInstance()->deposit_card($request_params);

        if(empty($result)){
            $returnData['status'] = 1000;
            $returnData['err'] = 0;
            $returnData['msg'] = '数据为空';
            $this->_ajaxReturn($returnData);
        }

        $returnData = $this->initReturnData($result);
        $this->_ajaxReturn($returnData);
    }


    public function initReturnData( $data ,$err = 0,$status = 1000, $msg = 'OK', $msg_type =''){
        $tag_data = array(
            'status'=>$status,
            'err'=> $err,
            'msg'=> $msg,
            'msg_type'=> $msg_type,
        );
        $tag_data_group = array(
            'csrf_token'=>$this->security->get_csrf_token_name(),
            'csrf_value'=>$this->security->get_csrf_hash()
        );
        $tag_data_group['data']  = $data;
        $tag_data['web_data'] = $tag_data_group;
        return $tag_data;
}

    /**
     * Ajax方式返回数据到客户端
     * @param array $data 要返回的数据
     * @param string $type AJAX返回数据格式
     * @param int $json_option JSON 常量
     */
    protected function _ajaxReturn($data = array(), $type = '',$json_option=0) {

        $data['referer'] = !empty($data['url']) ? $data['url'] : "";
        $data['state']= (!empty($data['status']) && $data['status'] == '1000') ? "success" : "fail";
        if(empty($type)) $type  =   'JSON';
        switch (strtoupper($type)){
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data,$json_option));
            case 'XML'  :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit($this->common_model->xml_encode($data));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler  =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
                exit($handler.'('.json_encode($data,$json_option).');');
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
            case 'AJAX_UPLOAD':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:text/html; charset=utf-8');
                exit(json_encode($data,$json_option));
            default :
                // 中断程序
                exit(0);
        }
    }
}