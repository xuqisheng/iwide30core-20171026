<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	后台优惠券业务规则
*	@author liwensong
*	@time 四月十一号
*	@version www.iwide.cn
*	@
*/
class Memberservicerule extends MY_Admin_Api
{
    protected $label_module = '会员中心4.0';
    protected $label_controller = '优惠券业务规则';
    protected $label_action = '优惠规则';

    /**
     * 2016-07-19
     * @author knight
     * 替换获取卡劵方法 get_card_rule_list_new, 原方法get_card_rule_list
     * 优惠券列表
     */
	public function index(){
        $admin_profile = $this->session->userdata('admin_profile');

        /* 兼容grid变为ajax加载加这一段 */
        if(is_ajax_request()){
            //处理ajax请求，参数规格不一样
            $get_filter= $this->input->post();
            $_get_filter= $this->input->get();
            if(!empty($_get_filter) && is_array($_get_filter)) $get_filter = $get_filter + $_get_filter;
        }else
            $get_filter= $this->input->get();

        if( !$get_filter) $get_filter = $this->input->get('filter');

        $params['cr.inter_id'] = $admin_profile['inter_id'];

        if(is_array($get_filter)) {
            $params = $get_filter + $params;
        }

        $this->load->model('membervip/admin/Public_model','pum');

        $params['table_name'] = 'member_card_rule';
        $params['alias'] = "cr";
        $select = array();
        $params['sort_field'] = 'cr.createtime';
        $params['sort_direct'] = 'desc';

        //排序字段
        $order_columns = array('card_rule_id','rule_title','active','frequency','card_rule_id','card_rule_id','card_rule_id','card_rule_id','createtime','is_active');
        foreach ($order_columns as &$v){
            $v = 'cr.'.$v;
        }
        if(isset($params['order']) && !empty($params['order'])){
            $params['sort_field'] = $order_columns[$params['order'][0]['column']];
            $params['sort_direct'] = $params['order'][0]['dir'];
            if(isset($params['order'][1]) && !empty($params['order'][1])){
                $params['sort_field'] = $order_columns[$params['order'][1]['column']];
                $params['sort_direct'] = $params['order'][1]['dir'];
            }
        }
        $params['opt'] = 9;
        $params['ui_type'] = 9;
        $params['ispackage'] = 0;
        $params['f_type'] = 9;

        if(is_ajax_request()){
            //处理ajax请求
            $params['page_size'] = 20;
            $result = $this->pum->get_admin_filter($params,$select);
            echo json_encode($result);exit;
        }else{
            //HTML输出
            if( !$this->label_action ) $this->label_action= '优惠规则';
            $this->_init_breadcrumb($this->label_action);

            //base grid data..
            $result = $this->pum->get_admin_filter($params,$select);
            $this->load->model('membervip/admin/config/attribute_model','ui_model');
            $fields_config = $this->ui_model->get_field_config('grid',$params['f_type']);
            $default_sort= array('field'=>'createtime', 'sort'=>$params['sort_direct']);
            $view_params= array(
                'module'=> $this->ui_model,
                'model'=> $this->pum,
                'result'=> $result,
                'fields_config'=> $fields_config,
                'default_sort'=> $default_sort,
                'get'=>$get_filter
            );
            $html = $this->_render_content($this->_load_view_file('index'), $view_params, TRUE);
            echo $html;
        }
	}

    /**
     * 2016-07-19
     * @author knight
     * 新增获取礼包配置信息
     * 增加业务规则
     */
	public function add(){
		$card_rule_id = $this->input->get('card_rule_id');
		$inter_id = $this->session->get_admin_inter_id();
        $token = $this->member_token(); //认证token
        $post_data = array(
			'inter_id'=>$this->session->get_admin_inter_id(),
			'card_rule_id'=>$card_rule_id,
        );
		//请求卡券的详细信息(修改时有结果)

        $cardinfo = $this->doCurlPostRequest( PMS_PATH_URL."cardrule/get_card_rule_info" , $post_data );
        $rule_info = !empty($cardinfo['data'])?$cardinfo['data']:[];
        $rule_info['channel'] = [
            'gazeini'=>'关注送券（默认领取）',
            'reg'=>'注册送券',
            'perfect'=>'完善资料送券',
            'gaze'=>'关注送券（自主领取）',
        ];

        $rule_info['pk_disabled_t'] = 'disabled';
        $rule_info['pk_display_t'] = 'style="display:none;"';
        $rule_info['pk_disabled_f'] = 'disabled';
        $rule_info['pk_display_f'] = 'style="display:none;"';
        if(!empty($rule_info['is_package'])){
            switch ($rule_info['is_package']){
                case 't':
                    $rule_info['pk_disabled_t'] = '';
                    $rule_info['pk_display_t'] = '';
                    break;
                case 'f':
                    $rule_info['pk_disabled_f'] = '';
                    $rule_info['pk_display_f'] = '';
                    break;
            }
        }

		$post_card_info = array(
			'inter_id'=>$this->session->get_admin_inter_id(),
            'field'=>'card_id,card_type,inter_id,title',
            'is_active'=>'t'
        );
		//请求优惠券配置信息URL
		$card_info = $this->doCurlPostRequest( PMS_PATH_URL."adminmember/get_inter_card" , $post_card_info );

        /*获取礼包*/
        $con_data=array(
            'token'=>$token,
            'inter_id'=>$inter_id,
            'num'=>30,
            'is_active'=> 't'
        );

        //请求套餐信息URL
        $package_list = $this->doCurlPostRequest( INTER_PATH_URL."package/getlist" , $con_data );
        /*end*/

        $rule_info ['cardlist'] = $card_info['data'];
        $rule_info ['package_list'] = isset($package_list['data']) ? $package_list['data'] : array();
        $html= $this->_render_content($this->_load_view_file('add'),$rule_info,false);
	}

    /**
     * 2016-07-19
     * @author knight
     * 新增ajax提交逻辑
     * 保存增加或修改优惠券
     */
	public function edit_post(){
        $data = $this->input->post();
        $inter_id = $this->session->get_admin_inter_id();
		$card_rule_id = !empty($data['card_rule_id'])?$data['card_rule_id']:'';
		foreach ($data as $key => $value) {
			if(!$value) $data[$key] = '';
		}
		//如果ID存在则为修改否则增加
		if($card_rule_id){
			unset($data['card_rule_id']);
			$post_data = array(
				'inter_id'=>$inter_id,
				'data'=>$data,
				'card_rule_id'=>$card_rule_id,
				);
			$update_result = $this->doCurlPostRequest( PMS_PATH_URL."cardrule/update_card_rule_info" , $post_data );
            if(is_ajax_request()){
                $retrun = $update_result;
                $retrun['isadd'] = false;
                $msg = !empty($update_result['msg'])?$update_result['msg']:'';
                if(isset($update_result['err']) && $update_result['err']==0){
                    $this->_ajaxReturn($msg,$retrun,1);
                }
                $this->_ajaxReturn($msg,$retrun,0);
            }
            redirect('membervip/memberservicerule/add?card_rule_id='.$card_rule_id);
        }else{
			$data['inter_id'] = $inter_id;
			$add_result = $this->doCurlPostRequest( PMS_PATH_URL."cardrule/add_card_rule_info" , $data );
            $msg = !empty($add_result['msg'])?$add_result['msg']:'';
            if(is_ajax_request()){
                $retrun = $add_result;
                $retrun['isadd'] = true;
                if(isset($add_result['err']) && $add_result['err']==0){
                    $this->_ajaxReturn($msg,$retrun,1);
                }
                $this->_ajaxReturn($msg,$retrun,0);
            }
            redirect('membervip/memberservicerule');
		}
	}

    /**
     * 2016-07-19
     * @author knight
     * 获取会员认证token
     * @return string
     */
    protected function member_token(){
        $post_token_data = array(
            'id'=>'vip',
            'secret'=>'iwide30vip',
        );
        $token_info = $this->doCurlPostRequest( INTER_PATH_URL."accesstoken/get" , $post_token_data );
        return isset($token_info['data'])?$token_info['data']:"";
    }
}
?>