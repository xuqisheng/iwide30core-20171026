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

class Analysis extends MY_Admin_Iapi{

    private   $admin_profile = array();
    public function __construct(){
        parent::__construct();
        $this->admin_profile = $this->session->userdata['admin_profile'];
    }


    //储值数据分析
    //sales_id
    //hotel_id
    //time_type  [update_time,createtime]
    //start_time
    //end_time
    public function balance_analysis(){
        $returnData = array(
            'status'=>1004,
            'err'=>'9999',
            'msg'=>'请求失败'
        );

        $request_params = $this->input->get();

        $start_date = $this->input->get('start_date');
        $end_date  = $this->input->get('end_date');


        /*测试数据*/
        $request_params['start_date'] = $start_date = '2017-08-11';
        $request_params['end_date'] = $end_date = '2017-09-21';
        /*测试数据*/

        if(empty($start_date) || empty($end_date)){
            $returnData['msg'] = '起始日期和结束日期不能为空';
            $this->_ajaxReturn($returnData);
        }elseif($start_date > $end_date){
            $returnData['msg'] = '起始日期不能大于结束日期';
            $this->_ajaxReturn($returnData);
        }

        list($start_y,$start_m,$start_d)=explode('-',$start_date);
        list($end_y,$end_m,$end_d)=explode('-',$end_date);
        if(!checkdate($start_m,$start_d,$start_y) || !checkdate($end_m,$end_d,$end_y)){
            $returnData['msg'] = '日期格式不正确';
            $this->_ajaxReturn($returnData);
        }

//
        $result = StatementsService::getInstance()->deposit_analysis($request_params);
        $returnData = $this->initReturnData($result);
        $this->_ajaxReturn($returnData);

    }


    //储值数据分析
    //sales_id
    //hotel_id
    //time_type  [update_time,createtime]
    //start_time
    //end_time
    public function balance_analysis_by_date(){
        $returnData = array(
            'status'=>1004,
            'err'=>'9999',
            'msg'=>'请求失败'
        );

        $request_params = $this->input->get();

        $start_date = $this->input->get('start_date');
        $end_date  = $this->input->get('end_date');


        /*测试数据*/
        $request_params['start_date'] = $start_date = '2017-09-11';
        $request_params['end_date'] = $end_date = '2017-09-11';
        $request_params['log_type'] = 1;
        /*测试数据*/

        if(empty($start_date) || empty($end_date)){
            $returnData['msg'] = '起始日期和结束日期不能为空';
            $this->_ajaxReturn($returnData);
        }elseif($start_date > $end_date){
            $returnData['msg'] = '起始日期不能大于结束日期';
            $this->_ajaxReturn($returnData);
        }

        list($start_y,$start_m,$start_d)=explode('-',$start_date);
        list($end_y,$end_m,$end_d)=explode('-',$end_date);
        if(!checkdate($start_m,$start_d,$start_y) || !checkdate($end_m,$end_d,$end_y)){
            $returnData['msg'] = '日期格式不正确';
            $this->_ajaxReturn($returnData);
        }

//
        $result = StatementsService::getInstance()->deposit_analysis_detail_by_date($request_params);
        $returnData = $this->initReturnData($result);
        $this->_ajaxReturn($returnData);

    }

    //积分数据分析
    //sales_id
    //hotel_id
    //time_type  [update_time,createtime]
    //start_time
    //end_time
    public function credit_analysis(){
        $returnData = array(
            'status'=>1004,
            'err'=>'9999',
            'msg'=>'请求失败'
        );

        $request_params = $this->input->get();

        $start_date = $this->input->get('start_date');
        $end_date  = $this->input->get('end_date');

        $hotel_id = $this->input->get('hotel_id');

        /*测试数据*/
        $request_params['start_date'] = $start_date = '2017-08-11';
        $request_params['end_date'] = $end_date = '2017-09-21';
        /*测试数据*/


        list($start_y,$start_m,$start_d)=explode('-',$start_date);
        list($end_y,$end_m,$end_d)=explode('-',$end_date);
        if(!checkdate($start_m,$start_d,$start_y) || !checkdate($end_m,$end_d,$end_y)){
            $returnData['msg'] = '日期格式不正确';
            $this->_ajaxReturn($returnData);
        }

        if(empty($start_date) || empty($end_date)){
            $returnData['msg'] = '起始日期和结束日期不能为空';
            $this->_ajaxReturn($returnData);
        }elseif($start_date > $end_date){
            $returnData['msg'] = '起始日期不能大于结束日期';
            $this->_ajaxReturn($returnData);
        }


        $result = StatementsService::getInstance()->credit_analysis($request_params);
        print_r($result);
//        $returnData = $this->initReturnData($result);
//        $this->_ajaxReturn($returnData);

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