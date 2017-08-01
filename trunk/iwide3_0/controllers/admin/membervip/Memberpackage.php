<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	后台充值套餐
*	@author Frandon
*	@time 5月16号
*	@version www.iwide.cn
*	@
*/
class Memberpackage extends MY_Admin_Api
{
    protected $label_module = '会员中心4.0';
    protected $label_controller = '会员大礼包';
    protected $label_action = '套餐列表';

	//套餐列表
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

        $params['p.inter_id'] = $admin_profile['inter_id'];

        if(is_array($get_filter)) {
            $params = $get_filter + $params;
        }

        $inter_id = $this->session->get_admin_inter_id();
        $this->load->model('membervip/admin/Public_model','pum');

        //获取等级配置信息
        $level_config = $this->pum->get_field_by_level_config($inter_id,'member_lvl_id,lvl_name');

        $params['table_name'] = 'package';
        $params['alias'] = "p";
        $params['join'] = array(
            array('table'=>'package_element as pe','on'=>"pe.package_id = p.package_id",'type'=>'left'),
        );
//        $params['group_by'] = 'p.package_id';
        $select = array('p.*','pe.package_element_id','pe.ele_type','pe.ele_value','pe.ele_num','pe.createtime as create_time');
        $params['sort_field'] = 'p.createtime';
        $params['sort_direct'] = 'desc';

        //排序字段
        $order_columns = array('p.package_id','p.name','p.remark','pe.ele_value','pe.ele_value','pe.ele_value','p.is_active','p.createtime');
        if(isset($params['order']) && !empty($params['order'])){
            $params['sort_field'] = $order_columns[$params['order'][0]['column']];
            $params['sort_direct'] = $params['order'][0]['dir'];
            if(isset($params['order'][1]) && !empty($params['order'][1])){
                $params['sort_field'] = $order_columns[$params['order'][1]['column']];
                $params['sort_direct'] = $params['order'][1]['dir'];
            }
        }
        $params['opt'] = 2;
        $params['ui_type'] = 2;
        $params['ispackage'] = 1;
        $params['f_type'] = 2;

        if(is_ajax_request()){
            //处理ajax请求
            $params['page_size'] = 20;
            $result = $this->pum->get_admin_filter($params,$select,$level_config);
            echo json_encode($result);exit;
        }else{
            //HTML输出
            if( !$this->label_action ) $this->label_action= '礼包套餐';
            $this->_init_breadcrumb($this->label_action);

            //base grid data..
            $result = $this->pum->get_admin_filter($params,$select,$level_config);
            $_moedel = $result['ui'];
            $fields_config = $_moedel->get_field_config('grid',2);
            $default_sort= array('field'=>'createtime', 'sort'=>$params['sort_direct']);
            $this->load->model('membervip/admin/config/attribute_model','ui_model');
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

	//增加套餐
	public function add(){
	    $package_id = $this->input->get('package_id'); //礼包套餐ID
        $inter_id = $this->session->get_admin_inter_id(); //酒店ID
        $this->load->model('membervip/admin/Card_model','c_model');
        $this->load->model('membervip/admin/Package_model','p_model');

        //请求套餐信息URL
        $packageInfo = $this->p_model->get_package_element($inter_id,$package_id);
        if(!empty($package_id) && empty($packageInfo)){
            $this->session->put_error_msg('找不到礼包信息！');
            $this->_redirect(EA_const_url::inst()->get_url('*/*'));
        }

        /**
         * 2016-07-19
         * @author knight
         * 等级配置信息
         */
        $post_data = array(
            'inter_id'=>$inter_id,
            'field'=>'member_lvl_id,inter_id,lvl_name,lvl_pms_code,lvl_up_sort'
        );
        $level_config = $this->doCurlPostRequest( PMS_PATH_URL."adminmember/get_all_level_config" , $post_data );

        //优惠券配置信息
        $card_info = $this->c_model->get_can_received($inter_id);

        if(empty($card_info)){
            $packageInfo['card'] = array();
        }

        $card_list = array();
        foreach ($card_info as $key => &$item){
            $item['status_color'] = 'color:#18BF0E;';
            $item['card_disabled'] = '';
            $item['err_msg'] = '有效';
            $todaystart = strtotime(date('Y-m-d'));
            $todayend = strtotime(date('Y-m-d 23:59:59'));

            if($item['time_start'] > $todayend){
                $item['status_color'] = 'color:#fe8f00;';
                $item['card_disabled'] = 'disabled';
                $item['err_msg'] = '领取时间未到';
                continue;
            }

            if($item['time_end'] < $todaystart){
                $item['status_color'] = 'color:#fe8f00;';
                $item['card_disabled'] = 'disabled';
                $item['err_msg'] = '已过领取时间';
                continue;
            }

            if ($item['use_time_end_model']=='g') {
                $use_time_end = strtotime(date('Y-m-d 23:59:59',$item['use_time_end']));
                if($use_time_end < time()){
                    $item['status_color'] = 'color:#fe8f00;';
                    $item['card_disabled'] = 'disabled';
                    $item['err_msg'] = '使用期限已过';
                    continue;
                }
            }
        }

        if(!empty($packageInfo['card'])){
            foreach ($packageInfo['card'] as $k => &$vo){
                $vo['status_color'] = 'color:#18BF0E;';
                $vo['card_disabled'] = '';
                $vo['err_msg'] = '有效';
                if(empty($card_info[$vo['card_id']])){
                    unset($packageInfo['card'][$k]);
                }else{
                    $card_data = $card_info[$vo['card_id']];
                    $todaystart = strtotime(date('Y-m-d'));
                    $todayend = strtotime(date('Y-m-d 23:59:59'));

                    if($card_data['time_start'] > $todayend){
                        $vo['status_color'] = 'color:#fe8f00;';
                        $vo['err_msg'] = '领取时间未到';
                        continue;
                    }

                    if($card_data['time_end'] < $todaystart){
                        $vo['status_color'] = 'color:#fe8f00;';
                        $vo['err_msg'] = '已过领取时间';
                        continue;
                    }

                    if ($card_data['use_time_end_model']=='g') {
                        $use_time_end = strtotime(date('Y-m-d 23:59:59',$card_data['use_time_end']));
                        if($use_time_end < time()){
                            $vo['status_color'] = 'color:#fe8f00;';
                            $vo['err_msg'] = '使用期限已过';
                            continue;
                        }
                    }
                }
            }
        }

        //配置模版参数
        $data = array(
            'package_id'=>isset($package_id) ? $package_id : null,
            'level_list'=>isset($level_config['data']) ? $level_config['data'] : array(),
            'packageInfo'=>$packageInfo,
            'card_list'=>$card_info,
            'card_data'=>$card_list
        );

        $this->_render_content($this->_load_view_file('add'),$data,false);
	}


    /**
     * 2016-07-19
     * @author knight
     * 增加是否为ajax提交逻辑
     * 保存套餐
     */
	public function edit_post(){
        $retrun['isadd'] = false;
        $this->load->model('membervip/common/Public_model','pm_model');
		$inter_id = $this->session->get_admin_inter_id();
		$data = $_POST;
//		unset($data['package_id']);
        $package_id = !empty($data['package_id'])?$data['package_id']:'';
        if(!empty($data['cards'])){
            foreach ($data['cards'] as $key => $value) {
                if($value){
                    /**
                     * 2016-07-19
                     * 数量为0或者空时,mysql数据操作方法不会保存
                     */
                    $Num = intval($data['cardvalue'][$key]);
                    if(!$Num && $this->is_ajax) $this->_ajaxReturn('赠送数量必须大于0!',$retrun,0);
                    $data['card'][$value]['num']=$Num;

                    if(isset($data['element_id'][$key])){
                        if(isset($data['element_id'][$key]) && !empty($data['element_id'][$key])){
                            $data['card'][$value]['element_id']=$data['element_id'][$key];
                        }

                        if(isset($data['cardvalue'][$key]) && !empty($data['cardvalue'][$key])){
                            $data['card'][$value]['num']=$data['cardvalue'][$key];
                        }

                        if(isset($data['status'][$key]) && !empty($data['status'][$key])){
                            $data['card'][$value]['status']=$data['status'][$key];
                        }
                    }
                    /*end*/
                }
            }
        }

		unset($data['cards']);
		unset($data['cardvalue']);
        unset($data['element_id']);
        unset($data['status']);

        if(!empty($data['card'])){
            $card_ids = array();
            foreach ($data['card'] as $cid => $v){
                $card_ids[] = "{$cid}:{$v['num']}";
            }
            $data['log_card_ids'] = implode(',',$card_ids);
        }

        $this->load->model('membervip/common/Public_log_model','logm');
        $this->load->model('membervip/admin/Package_model','pk_model');
        $info = $this->pk_model->get_package_element($inter_id,$package_id);

        if(!empty($info)){
            if(!empty($info['card'])){
                $card_ids = array();
                foreach ($info['card'] as $vv){
                    $card_ids[] = "{$vv['card_id']}:{$vv['count']}";
                }
                $info['log_card_ids'] = implode(',',$card_ids);
            }
            $info['deposit'] = $info['balance'];
            $info['membership'] = $data['membership'];
        }

        unset($info['card']);

        $logs = array(
            'title'=>'大礼包配置',
            'filter'=>array('createtime','last_update_time'),
            'rule_name'=>$this->module.'/'.$this->controller.'/'.$this->action,
            'name'=>!empty($info['name'])?$info['name']:''
        );

        $post_addpack_url = INTER_PATH_URL.'package/add';
		$post_addpack_data = $data;
		$post_addpack_data['token']= $this->member_token();
		$post_addpack_data['inter_id']= $inter_id;

        //保存或者添加方法
		$add_result = $this->doCurlPostRequest( $post_addpack_url , $post_addpack_data );
        $result = @json_encode($add_result);
        unset($post_addpack_data['card']);

        if(!empty($info)){
            $_result = $add_result['err'] == '0' ? 1 : 0;
            $this->logm->save_log_init($info,$post_addpack_data,$info['package_id'],$_result,'package',$this->pm_model,$logs); //添加操作记录
            $retrun = $add_result;
            $retrun['isadd'] = false;
            if($add_result['err']=='0'){
                if($this->is_ajax){
                    $this->_ajaxReturn('保存成功!',$retrun,1);
                }else{
                    redirect('membervip/memberpackage');
                }
            }else{
                if($this->is_ajax){
                    $this->_ajaxReturn('保存失败!',$retrun,0);
                }else{
                    redirect('membervip/memberpackage/add');
                }
            }
        }else{
            $logs['name'] = !empty($post_addpack_data['name'])?$post_addpack_data['name']:'';
            $this->logm->save_log_init($info,$post_addpack_data,0,$result,'package',$this->pm_model,$logs); //添加操作记录
            $retrun = $add_result;
            $retrun['isadd'] = true;
            if($add_result['err']=='0'){
                if($this->is_ajax){
                    $this->_ajaxReturn('添加成功!',$retrun,1);
                }else{
                    redirect('membervip/memberpackage');
                }
            }else{
                if($this->is_ajax){
                    $this->_ajaxReturn('添加失败!',$retrun,0);
                }else{
                    redirect('membervip/memberpackage/add');
                }
            }
        }
	}

    /**
     * 2016-07-19
     * @author knight
     * 删除礼包卡劵信息
     */
    public function del_element(){
        if($this->is_ajax){
            $this->load->model('membervip/common/Public_log_model','logm');
            $this->load->model('membervip/common/Public_model','pm_model');
            $inter_id = $this->session->get_admin_inter_id();
            $element_id = intval($this->input->get('element_id')); //子规则ID
            if(!$element_id) $this->_ajaxReturn('参数错误',null,0);
            $where = array(
                'package_element_id'=>$element_id,
                'inter_id' => $inter_id
            );
            $elements = $this->pm_model->get_info($where,'package_element');
            if(empty($elements)){
                $this->_ajaxReturn('数据已删除',$elements,2);
            }

            $_where = array(
                'card_id'=>$elements['ele_value'],
                'inter_id' => $inter_id
            );
            $card_info = $this->pm_model->get_info($_where,'card');

            $_where = array(
                'package_id'=>$elements['package_id'],
                'inter_id' => $inter_id
            );
            $package_info = $this->pm_model->get_info($_where,'package');

            $del_res = $this->pm_model->delete_data($where,'package_element');

            $logs = array(
                'title'=>'大礼包配置',
                'rule_name'=>$this->module.'/'.$this->controller.'/'.$this->action,
                'name'=>!empty($package_info['name'])?$package_info['name']:'',
                'log_msg'=>!empty($card_info['title'])?'删除礼包的优惠券 -- '.$card_info['title']:'删除礼包的优惠券'
            );

            $this->logm->save_log_init('','',$element_id,$del_res,'package',$this->pm_model,$logs); //添加操作记录

            if(!$del_res){
                $this->_ajaxReturn('删除失败',$del_res,0);
            }
            $this->_ajaxReturn('删除成功',$del_res,1);
        }
        $this->_ajaxReturn('请求错误',null,0);
    }

    /**
     * 获取验证token
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

    /**
     * 2016-07-19
     * @author knight
     * [逆序列化]
     * @param  [string] $serial_str [需转换的字符串]
     * @return [array]             [返回数组]
     */
    protected function mb_unserialize($serial_str) {
        $serial_str= preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str );
        $serial_str= str_replace("\r", "", $serial_str);
        $serial_str=htmlspecialchars_decode($serial_str);
        return unserialize($serial_str);
    }
}
?>