<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	后台优惠券
*	@author liwensong
*	@time 四月十一号
*	@version www.iwide.cn
*	@
*/
class Membercard extends MY_Admin_Api
{
    protected $label_module = '会员中心4.0';
    protected $label_controller = '优惠券配置';
    protected $label_action = '优惠券列表';

	//优惠券列表
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

        $params['c.inter_id'] = $admin_profile['inter_id'];

        if(is_array($get_filter)) {
            $params = $get_filter + $params;
        }

        $inter_id = $this->session->get_admin_inter_id();
        $this->load->model('membervip/admin/Public_model','pum');

        $params['table_name'] = 'card';
        $params['alias'] = "c";
        $params['group_by'] = 'c.card_id';
        $select = array('c.card_id','c.card_type','c.inter_id','c.title','c.sub_title','c.notice','c.description','c.card_stock','c.exchange','c.createtime','c.last_update_time','c.is_active');
        $params['sort_field'] = 'c.createtime,c.last_update_time';
        $params['sort_direct'] = 'desc';

        //排序字段
        $order_columns = array('c.card_id','c.title','c.description','c.card_type','c.card_stock','c.createtime,c.last_update_time','c.is_active');
        if(isset($params['order']) && !empty($params['order'])){
            $params['sort_field'] = $order_columns[$params['order'][0]['column']];
            $params['sort_direct'] = $params['order'][0]['dir'];
            if(isset($params['order'][1]) && !empty($params['order'][1])){
                $params['sort_field'] = $order_columns[$params['order'][1]['column']];
                $params['sort_direct'] = $params['order'][1]['dir'];
            }
        }
        $params['opt'] = 3;
        $params['ui_type'] = 3;
        $params['ispackage'] = 0;
        $params['f_type'] = 3;

        if(is_ajax_request()){
            //处理ajax请求
            $params['page_size'] = 20;
            $result = $this->pum->get_admin_filter($params,$select);
            echo json_encode($result);exit;
        }else{
            //HTML输出
            if( !$this->label_action ) $this->label_action= '优惠券列表';
            $this->_init_breadcrumb($this->label_action);

            //base grid data..
            $result = $this->pum->get_admin_filter($params,$select);
            $this->load->model('membervip/admin/config/attribute_model','ui_model');
            $fields_config = $this->ui_model->get_field_config('grid',3);
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
	//增加优惠券
	public function add(){
        $this->load->model('membervip/common/Public_model','pm_model');
		$card_id = $this->input->get('card_id');
		$inter_id = $this->session->get_admin_inter_id();
        $where = [
            'inter_id'=>$inter_id,
            'card_id'=>$card_id
        ];
        //查询优惠券的详细信息
        $card_info = $this->pm_model->get_info($where,'card');
        if(!empty($card_id)){
            if(empty($card_info)){
                $this->session->put_error_msg('找不到该优惠券的信息');
                $this->_redirect(EA_const_url::inst()->get_url('*/membercard'));
            }
        }
        unset($where['inter_id']);
		$card_model = $this->pm_model->get_list($where,'card_module','module');
        $_model = [];
		if(!empty($card_model)){
		    foreach ($card_model as $item){
                $_model[] = $item['module'];
            }
        }
        $_model = array_unique($_model);
		if(!empty($card_info)){
            $card_info['module'] = $_model;
        }
        $data = [
            'ct_disabled1'=>'disabled',
            'ct_disabled2'=>'disabled',
            'ct_disabled3'=>'disabled',
            'ct_disabled4'=>'disabled',
            'ct_display1'=>'style="display:none;"',
            'ct_display2'=>'style="display:none;"',
            'ct_display3'=>'style="display:none;"',
            'ct_display4'=>'style="display:none;"',
            'tm_disabled_g'=>'disabled',
            'tm_disabled_y'=>'disabled',
            'tm_display_g'=>'style="display:none;"',
            'tm_display_y'=>'style="display:none;"',
        ];
        $data['inter_id'] = $inter_id;
		$data['cardinfo'] = !empty($card_info)?$card_info:array();
        if(!empty($data['cardinfo'])){
            switch ($data['cardinfo']['card_type']){
                case '1':
                    $data['ct_disabled1'] = '';
                    $data['ct_display1'] = '';
                    break;
                case '2':
                    $data['ct_disabled2'] = '';
                    $data['ct_display2'] = '';
                    break;
                case '3':
                    $data['ct_disabled3'] = '';
                    $data['ct_display3'] = '';
                    break;
                case '4':
                    $data['ct_disabled4'] = '';
                    $data['ct_display4'] = '';
                    break;
            }

            switch ($data['cardinfo']['use_time_end_model']){
                case 'g':
                    $data['tm_disabled_g'] = '';
                    $data['tm_display_g'] = '';
                    break;
                case 'y':
                    $data['tm_disabled_y'] = '';
                    $data['tm_display_y'] = '';
                    break;
            }
        }

        //获取所有INTER酒店信息
	    $this->load->model('wx/publics_model');
		$data['publics']  = $this->publics_model->get_public();
		$html= $this->_render_content($this->_load_view_file('add'),$data,false);
	}


	//保存增加或修改优惠券
	public function edit_post(){
        $this->load->model('membervip/common/Public_model','pm_model');
        $vfield = [
	        'brand_name'=>'请填写商户名称',
            'title'=>'请填写优惠券名称',
            'time_start'=>'请选择领取起始时间',
            'time_end'=>'请选择领取结束时间',
            'use_time_start'=>'请选择使用开始时间',
            'use_time_end'=>'请选择使用失效时间',
            'card_stock'=>'请填写库存',
            'least_cost'=>'抵用券起用金额',
            'over_limit'=>'请填写优惠劵抵用上限金额',
            'reduce_cost'=>'请填写抵用券减免金额',
            'discount'=>'请填写折扣劵打折额度',
            'money'=>'请填写储值券金额'
        ];
		$inter_id = $this->session->get_admin_inter_id();
		$card_id = $this->input->post('card_id');
		$data = $this->input->post();

        //验证数据
        foreach ($vfield as $fk => $fv){
            if(isset($data[$fk]) && empty($data[$fk])){
                $this->_ajaxReturn($fv,null,0);
            }
            switch ($fk){
                case 'time_start':
                    if(!$this->pm_model->isDate($data['time_start'])){
                        $this->_ajaxReturn('领取起始时间格式错误!',null,0);
                    }
                    $data[$fk] = strtotime($data[$fk]);
                    break;
                case 'time_end':
                    if(!$this->pm_model->isDate($data['time_end'])){
                        $this->_ajaxReturn('领取结束时间格式错误!',null,0);
                    }
                    $data[$fk] = strtotime($data[$fk]);
                    break;
                case 'use_time_start':
                    if(!$this->pm_model->isDate($data['use_time_start'])){
                        $this->_ajaxReturn('使用开始时间格式错误!',null,0);
                    }
                    $data[$fk] = strtotime($data[$fk]);
                    break;
                case 'use_time_end':
                    if($data['use_time_end_model'] == 'g'){
                        if(!$this->pm_model->isDate($data['use_time_end']) ){
                            $this->_ajaxReturn('使用失效时间格式错误!',null,0);
                        }
                        $data[$fk] = strtotime($data[$fk]);
                    }
                    break;
                default:
                    break;
            }
        }
        if($data['card_stock'] <= 0) $this->_ajaxReturn('库存必须大于零!',null,0);

        if($data['time_start'] > $data['time_end']) $this->_ajaxReturn('领取起始时间不可大于领取结束时间!',null,0);

        if($data['time_start'] > $data['use_time_start']) $this->_ajaxReturn('领取起始时间应小于或等于使用开始时间!',null,0);

        if($data['use_time_end_model'] == 'g'){
            $use_time_end = $data['use_time_end'];
            if($data['time_end'] > $use_time_end) $this->_ajaxReturn('领取结束时间应小于或等于使用失效时间!',null,0);
            if($data['use_time_start'] > $data['use_time_end']) $this->_ajaxReturn('使用开始时间应小于使用失效时间!',null,0);
        }elseif ($data['use_time_end_model'] == 'y'){
            if($data['use_time_end_day'] <= 0) $this->_ajaxReturn('存活天数不能为零!',null,0);
        }else{
            $this->_ajaxReturn('请选择优惠券过期的失效模式!',null,0);
        }

		if(empty($data['is_f'])) $data['is_f'] = 'f';

		$data['inter_id'] = $inter_id;
		unset($data['card_id']);
        $module = !empty($data['module'])?$data['module']:[];
        if(!in_array('vip', $module)){
            $module[] = 'vip';
        }
        $this->pm_model->_shard_db(true)->trans_begin(); //开启事务
        try{

            $this->load->model('membervip/common/Public_log_model','logm');

            $info = $this->pm_model->get_info(array('inter_id'=>$inter_id,'card_id'=>$card_id),'card');
            $logs = array(
                'title'=>'优惠券配置',
                'filter'=>array('createtime','last_update_time'),
                'rule_name'=>$this->module.'/'.$this->controller.'/'.$this->action,
                'name'=>!empty($info['title'])?$info['title']:''
            );

            //如果ID存在则为修改否则增加
            if(!empty($info)){
                $where = [
                    'inter_id'=>$inter_id,
                    'card_id'=>$card_id
                ];
                $card_info = $this->pm_model->get_info($where,'card','card_id');
                if(empty($card_info)) $this->_ajaxReturn('找不到该优惠券的信息!',null,0);

                if(!$data['logo_url']) $data['logo_url'] = '';

                $data['last_update_time'] = date('Y-m-d H:i:s');
                $save_data = $this->pm_model->check_list_fields($data,'card');
                $params = [
                    'card_id'=>$card_info['card_id']
                ];
                $update_result = $this->pm_model->update_save($params,$save_data,'card');
                $this->logm->save_log_init($info,$save_data,$card_id,$update_result,'coupon',$this->pm_model,$logs); //添加操作记录
                if(is_ajax_request()){
                    if($update_result === false){
                        throw new Exception('保存失败!1',0);
                    }
                    $_w = [
                        'card_id' =>$card_info['card_id']
                    ];
                    $del_cmodel = $this->pm_model->delete_data($_w,'card_module');
                    if($del_cmodel === false){
                        throw new Exception('保存失败!2',0);
                    }
                    foreach ($module as $key => $value) {
                        $svae = [
                            'card_id'=>$card_info['card_id'],
                            'module'=>$value
                        ];
                        $card_module = $this->pm_model->add_data($svae,'card_module');
                        if(!$card_module){
                            throw new Exception('保存失败!3',0);
                        }
                    }
                    $this->pm_model->_shard_db(true)->trans_commit();// 事务提交
                    $retrun['result'] = $update_result;
                    $retrun['isadd'] = false;
                    $this->_ajaxReturn('保存成功!4',$retrun,1);
                }
                redirect('membervip/membercard/add?card_id='.$card_id);
            }else{
                $data['createtime'] = time();
                $save_data = $this->pm_model->check_list_fields($data,'card');
                $add_result = $this->pm_model->add_data($save_data,'card');
                $logs['name'] = !empty($save_data['title'])?$save_data['title']:'';
                $this->logm->save_log_init($info,$save_data,$add_result,$add_result,'coupon',$this->pm_model,$logs); //添加操作记录
                if(is_ajax_request()){
                    $retrun['result'] = $add_result;
                    $retrun['isadd'] = true;
                    if(!$add_result){
                        if(!$add_result){
                            throw new Exception('保存失败!5'.json_encode($save_data),0);
                        }
                    }
                    foreach ($module as $key => $value) {
                        $svae = [
                            'card_id'=>$add_result,
                            'module'=>$value
                        ];
                        $card_module = $this->pm_model->add_data($svae,'card_module');
                        if(!$card_module){
                            throw new Exception('保存失败!',0);
                        }
                    }
                    $this->pm_model->_shard_db(true)->trans_commit();// 事务提交
                    $this->_ajaxReturn('添加成功!',$retrun,1);
                }
                redirect('membervip/membercard');
            }
        }catch (Exception $e){
            $msg = !empty($e->getMessage())?$e->getMessage():'保存失败!';
            $code = !empty($e->getCode())?$e->getCode():0;
            $this->pm_model->_shard_db(true)->trans_rollback(); //回滚事务
            if(!empty($card_id)){
                $retrun['isadd'] = false;
            }else{
                $retrun['isadd'] = true;
            }
            $this->_ajaxReturn($msg,$retrun,$code);
        }
	}

	//获取某券的领取情况
	public function card_user_info(){
        $admin_profile = $this->session->userdata('admin_profile');
        $this->load->model('membervip/admin/Public_model','pum');
        /* 兼容grid变为ajax加载加这一段 */
        $get_filter=array();
        if(is_ajax_request()){
            //处理ajax请求，参数规格不一样
            $get_filter= $this->input->post();
        }

        $params['mc.inter_id'] = $admin_profile['inter_id'];
        $params['mc.is_active'] = 't';

        if(is_array($get_filter)) {
            $params = $get_filter + $params;
        }

        $card_id = $this->uri->segment(4);

        $card_name = $this->pum->_shard_db()->select('title')->where(['inter_id'=>$admin_profile['inter_id'],'card_id'=>$card_id])->get('card')->row_array();
        $card_title = !empty($card_name['title'])?$card_name['title']:'';
        $get_filter['card_id']=$card_id;
        $params['mc.card_id'] = $card_id;
        $params['table_name'] = 'member_card';
        $params['alias'] = "mc";
        $params['join'] = array(
            array('table'=>'member_info as m','on'=>"m.member_info_id = mc.member_info_id",'type'=>'left'),
            array('table'=>'card as c','on'=>"c.card_id = mc.card_id",'type'=>'left'),
        );
        $params['group_by'] = 'mc.member_card_id';
        $select = array('mc.coupon_code','mc.member_card_id','mc.card_id','mc.member_info_id','mc.inter_id','m.membership_number','m.nickname','m.name','c.title','mc.receive_module','mc.receive_time','mc.use_module','mc.use_time','mc.useoff_module','mc.useoff_time','mc.is_useoff','mc.is_use','mc.is_active','mc.is_giving','mc.is_giving_time','mc.expire_time','mc.remark','c.card_stock','c.is_active','c.time_start','c.time_end','c.use_time_end_model','c.use_time_end_day','c.use_time_end');
        $params['sort_field'] = 'mc.receive_time,mc.expire_time';
        $params['sort_direct'] = 'desc';

        if(isset($params['order']) && !empty($params['order'])){
            //排序字段
            $order_columns = array('mc.coupon_code','mc.member_info_id','m.membership_number','m.nickname','m.name','c.title','mc.receive_module','mc.receive_time','mc.expire_time','mc.expire_time','mc.expire_time','mc.use_module','mc.use_time','mc.useoff_module','mc.useoff_time','mc.use_module','mc.remark');
            $params['sort_field'] = $order_columns[$params['order'][0]['column']];
            $params['sort_direct'] = $params['order'][0]['dir'];
            if(isset($params['order'][1]) && !empty($params['order'][1])){
                $params['sort_field'] = $order_columns[$params['order'][1]['column']];
                $params['sort_direct'] = $params['order'][1]['dir'];
            }
        }

        $params['iscard'] = 1;
        $params['ui_type'] = 4;
        $params['ispackage'] = 0;
        $params['f_type'] = 4;

        if(is_ajax_request()){
            //处理ajax请求
            $params['page_size'] = 20;
            $result = $this->pum->get_admin_filter($params,$select);
            echo json_encode($result);exit;
        }else{
            $this->label_action= $card_title;
            //HTML输出
            $this->_init_breadcrumb($this->label_action);

            //base grid data..
            $params['mstatistics'] = true;
            $result = $this->pum->get_admin_filter($params,$select);
            $this->load->model('membervip/admin/config/attribute_model','ui_model');
            $fields_config = $this->ui_model->get_field_config('grid',4);
            $default_sort= array('field'=>'receive_time', 'sort'=>$params['sort_direct']);
            $view_params= array(
                'module'=> $this->ui_model,
                'model'=> $this->pum,
                'result'=> $result,
                'fields_config'=> $fields_config,
                'default_sort'=> $default_sort,
                'get'=>$get_filter,
                'card_title'=>$card_title
            );
            $html = $this->_render_content($this->_load_view_file('cardinfo'), $view_params, TRUE);
            echo $html;
        }
	}

	//扫码核销
	public function useoff_code(){
		$inter_id = $this->session->get_admin_inter_id();
		//获取酒店集团的信息
		if($this->session->get_admin_inter_id()) {
			$this->load->model('wx/Publics_model');
			$data['public']= $this->Publics_model->get_public_by_id($inter_id);
		}
		$data['inter_id'] = $inter_id;
		$html= $this->_render_content($this->_load_view_file('useoff_code'),$data,false);
	}

    /*qrc生成*/
    public function qrc(){
        $str = $this->input->get("str");
        $this->load->helper ('phpqrcode');
        $url = urldecode($str);
        $margin = isset($_GET['margin']) ? $_GET['margin']:0;
        QRcode::png($url,false,'Q',30,$margin,true);
    }



    public function reclist(){
        ini_set('memory_limit','512M');
        $inter_id = $this->session->get_admin_inter_id();
        $keys = $this->uri->segment(4);
        $begin_time = $this->input->post('begin_time');
        $end_time   = $this->input->post('end_time');
        $keys = explode('_', $keys);
        if(!empty($keys[0])){
            $begin_time = $keys[0];
        }
        if(!empty($keys[1])){
            $end_time = $keys[1];
        }

        if(!empty($begin_time)){
            $begin_time = strtotime($begin_time);
        }

        if(!empty($end_time)){
            $end_time = strtotime($end_time);
        }

        if(empty($begin_time) && empty($end_time)){
            $begin_time = 0;
            $end_time   = 0;
        }

        $this->load->model('membervip/admin/report_member_model');
        $params['inter_id'] = $inter_id;
        $res = $this->report_member_model->get_membercard_v($params,$begin_time,$end_time);

        $this->load->library ( 'PHPExcel' );
        $this->load->library ( 'PHPExcel/IOFactory' );
        $objPHPExcel = new PHPExcel ();
        $fields_conf = $this->get_fields_conf();
        $objPHPExcel->getProperties()->setTitle ( "export" )->setDescription ( "none" );
        $col = 0;
        foreach ($fields_conf as $key=>$vo){
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow ($key, 1,$vo['name']);
        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        // Fetching the table data
        $row = 2;
        foreach ( $res as $item ) {
            foreach ($fields_conf as $k=>$v){
                if(isset($v['func']) && !empty($v['func'])){
                    $_date=array($item[$v['name']]);
                    $item[$v['name']] = call_user_func_array(array($this, $v['func']),$_date);
                }
                switch ($v['name']){
                    case 'card_status':
                        $_data[0] = !empty($item['is_active'])?$item['is_active']:'f';
                        $_data[1] = !empty($item['is_use'])?$item['is_use']:'f';
                        $_data[2] = !empty($item['is_useoff'])?$item['is_useoff']:'f';
                        $_data[3] = !empty($item['is_giving'])?$item['is_giving']:'f';
                        $card_status = call_user_func_array (array($this->report_member_model, '_parse_card_status'),$_data);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow ( $k, $row, !empty($card_status)?$card_status:'------');
                        break;
                    case 'use_module':
                        $use_data[0] = !empty($item['use_module'])?$item['use_module']:'';
                        $use_data[1] = !empty($item['use_scene'])?$item['use_scene']:'';
                        $use_module = call_user_func_array (array($this->report_member_model, '_parse_card_module'),$use_data);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow ( $k, $row, !empty($use_module)?$use_module:'------');
                        break;
                    case 'useoff_module':
                        $use_data[0] = !empty($item['useoff_module'])?$item['useoff_module']:'';
                        $use_data[1] = !empty($item['useoff_scene'])?$item['useoff_scene']:'';
                        $use_module = call_user_func_array (array($this->report_member_model, '_parse_card_module'),$use_data);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow ( $k, $row, !empty($use_module)?$use_module:'------');
                        break;
                    default:
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow ( 0, $row, isset($item[$v['name']]) ? $item[$v['name']] : '------' );
                        break;
                }
            }
            $row ++;
        }
        $objPHPExcel->setActiveSheetIndex ( 0 );
        $objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
        // 发送标题强制用户下载文件
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="优惠券领取明细' . date ( 'YmdHis' ) . '.xls"' );
        header ( 'Cache-Control: max-age=0' );
        $objWriter->save ( 'php://output' );
    }

    public function _parse_datetime(){
        $data = func_get_args();
        if(empty($data)) return false;
        return date('Y-m-d H:i:s',$data[0]);
    }

    public function _parse_date(){
        $data = func_get_args();
        if(empty($data)) return false;
        return date('m-d',$data[0]);
    }

    /**
     * excel表字段配置
     * @param int $type
     * @return array
     */
    public function get_fields_conf($type=1){
        $fields_conf = array();
        switch ($type){
            case 1:
                $fields_conf = array(
                    array(
                        'name'=>'卡券ID',
                        'field'=>'card_id',
                    ),
                    array(
                        'name'=>'卡券名称',
                        'field'=>'title',
                    ),
                    array(
                        'name'=>'会员ID',
                        'field'=>'member_info_id',
                    ),
                    array(
                        'name'=>'会员昵称',
                        'field'=>'nickname',
                    ),
                    array(
                        'name'=>'会员姓名',
                        'field'=>'name',
                    ),
                    array(
                        'name'=>'领取来源',
                        'field'=>'receive_module',
                    ),
                    array(
                        'name'=>'领取时间',
                        'field'=>'receive_time',
                        'func'=>'_parse_datetime'
                    ),
                    array(
                        'name'=>'失效时间',
                        'field'=>'expire_time',
                        'func'=>'_parse_datetime'
                    ),
                    array(
                        'name'=>'优惠券状态',
                        'field'=>'card_status',
                    ),
                    array(
                        'name'=>'使用场景',
                        'field'=>'use_module',
                    ),
                    array(
                        'name'=>'使用时间',
                        'field'=>'use_time',
                        'func'=>'_parse_datetime'
                    ),
                    array(
                        'name'=>'核销场景',
                        'field'=>'useoff_module',
                    ),
                    array(
                        'name'=>'核销时间',
                        'field'=>'useoff_time',
                        'func'=>'_parse_datetime'
                    ),
                    array(
                        'name'=>'使用范围',
                        'field'=>'card_module',
                    ),
                    array(
                        'name'=>'关联礼包编号',
                        'field'=>'package_ids',
                    ),
                    array(
                        'name'=>'关联礼包名称',
                        'field'=>'package_names',
                    ),
                );
                break;
            case 2:
                $fields_conf = array(
                    array(
                        'name'=>'优惠券名称',
                        'field'=>'title',
                    ),
                    array(
                        'name'=>'使用人数',
                        'field'=>'use_count',
                    ),
                    array(
                        'name'=>'使用时间',
                        'field'=>'use_time',
                        'func'=>'_parse_date'
                    ),
                );
                break;
        }
        return $fields_conf;
    }

    public function verifie(){
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

        $params['mc.inter_id'] = $admin_profile['inter_id'];
        $params['m.inter_id'] = $admin_profile['inter_id'];
        $params['c.inter_id'] = $admin_profile['inter_id'];

        if(is_array($get_filter)) {
            $params = $get_filter + $params;
        }
        $params['search']['value'] = !empty($params['searchval'])?$params['searchval']:'';

        $this->load->model('membervip/admin/Public_model','mempublics_model');

        $params['table_name'] = 'member_card';
        $params['alias'] = "mc";
        $params['join'] = array(
            array('table'=>'member_info as m','on'=>"m.member_info_id = mc.member_info_id",'type'=>'left'),
            array('table'=>'card as c','on'=>"c.card_id = mc.card_id",'type'=>'left'),
        );
        $params['group_by'] = 'mc.member_card_id';
        $select = array('mc.member_card_id','mc.coupon_code','mc.use_time','mc.card_id','c.title','m.member_info_id','m.membership_number','m.name','m.nickname','m.telephone','m.cellphone','mc.is_use','mc.is_useoff','mc.is_active','mc.is_giving','mc.expire_time');
        $params['sort_field'] = 'mc.use_time,mc.member_card_id';
        $params['sort_direct'] = 'desc';

        //排序字段
        $order_columns = array('mc.coupon_code','mc.use_time','mc.card_id','c.title','m.membership_number','m.name','m.telephone','mc.coupon_code');
        if(isset($params['order']) && !empty($params['order'])){
            $params['sort_field'] = $order_columns[$params['order'][0]['column']];
            $params['sort_direct'] = $params['order'][0]['dir'];
            if(isset($params['order'][1]) && !empty($params['order'][1])){
                $params['sort_field'] = $order_columns[$params['order'][1]['column']];
                $params['sort_direct'] = $params['order'][1]['dir'];
            }
        }
        $params['opt'] = 13;
        $params['ui_type'] = 13;
        $params['f_type'] = 13;

        if(is_ajax_request()){
            //处理ajax请求
            $params['page_size'] = 20;
            $result = $this->mempublics_model->get_admin_filter($params,$select);
//            exit(json_encode(array(0=>array(''))));
            exit(json_encode($result));
        }else{
            //HTML输出
            if( !$this->label_action ) $this->label_action= '券码核销';
            $this->_init_breadcrumb($this->label_action);
            $this->load->model('wx/Publics_model');
            $public = $this->Publics_model->get_public_by_id($admin_profile['inter_id']);
            $rand_code = $this->randCode(16).microtime(true);

            //base grid data..
            $this->load->model('membervip/admin/config/attribute_model','ui_model');
            $fields_config = $this->ui_model->get_field_config('grid',13);
            $default_sort= array('field'=>'createtime', 'sort'=>$params['sort_direct']);
            $view_params= array(
                'module'=> $this->ui_model,
                'model'=> $this->mempublics_model,
                'result'=> array(),
                'fields_config'=> $fields_config,
                'default_sort'=> $default_sort,
                'get'=>$get_filter,
                'public'=>$public,
                'rand_code'=>$rand_code,
                'scanauth_info'=>$this->get_scanauth_field($params)
            );
        }
        $html= $this->_render_content($this->_load_view_file('verifie'),$view_params,true);
        exit($html);
    }

    public function get_scanauth_field($params = array()){
        unset($params['mc.inter_id']);
        unset($params['c.inter_id']);

        $fields_config = $this->ui_model->get_field_config('grid',14);
        $default_sort = array('field'=>'authtime', 'sort'=>'desc');
        $view_params= array(
            'fields_config'=> $fields_config,
            'default_sort'=> $default_sort,
        );
        return $view_params;
    }

    public function get_scanauth_info(){
        $this->load->model('membervip/admin/Public_model','mempublics_model');
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

        $params['m.inter_id'] = $admin_profile['inter_id'];
        $params['sa.inter_id'] = $admin_profile['inter_id'];
        $params['sa.status'] = 1;

        if(is_array($get_filter)) {
            $params = $get_filter + $params;
        }
        $this->load->model('membervip/admin/Public_model','mempublics_model');

        $params['table_name'] = 'scanqr_auth';
        $params['alias'] = "sa";
        $params['join'] = array(
            array('table'=>'member_info as m','on'=>"m.open_id = sa.openid",'type'=>'left'),
        );

        $params['group_by'] = 'sa.id';
        $select = array('sa.*','m.name','m.nickname');
        $params['sort_field'] = 'sa.authtime,sa.id';
        $params['sort_direct'] = 'desc';

        //排序字段
        $order_columns = array('sa.id','m.name','sa.authtime','sa.id');
        if(isset($params['order']) && !empty($params['order'])){
            $params['sort_field'] = $order_columns[$params['order'][0]['column']];
            $params['sort_direct'] = $params['order'][0]['dir'];
            if(isset($params['order'][1]) && !empty($params['order'][1])){
                $params['sort_field'] = $order_columns[$params['order'][1]['column']];
                $params['sort_direct'] = $params['order'][1]['dir'];
            }
        }
        $params['opt'] = 14;
        $params['ui_type'] = 14;
        $params['f_type'] = 14;
        //处理ajax请求
        $params['page_size'] = 20;
        $result = $this->mempublics_model->get_admin_filter($params,$select);
        exit(json_encode($result));
    }

    public function uselist(){
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

        $params['cl.inter_id'] = $admin_profile['inter_id'];
        $params['cl.log_type'] = array(0,2,3,4);
        $params['extendedWhere'] = "(mc.is_use = 't' OR mc.is_useoff = 't' OR mc.is_active = 'f') AND mc.is_giving = 'f'";

        if(is_array($get_filter)) {
            $params = $get_filter + $params;
        }

        if(!empty($params['keywords'])){
            $params['extendedWhere'] .= " AND (c.title = '{$params['keywords']}' OR mc.card_id = '{$params['keywords']}')";
        }

        if(!empty($params['useoff_sttime'])){
            $useoff_sttime = strtotime(date('Y-m-d',strtotime($params['useoff_sttime'])));
            $params['extendedWhere'] .= " AND mc.useoff_time >= '{$useoff_sttime}'";
        }

        if(!empty($params['useoff_edtime'])){
            $useoff_edtime = strtotime(date('Y-m-d 23:59:59',strtotime($params['useoff_edtime'])));
            $params['extendedWhere'] .= " AND mc.useoff_time <= '{$useoff_edtime}'";
        }

        if(!empty($params['coupon_code'])){
            $params['extendedWhere'] .= " AND mc.coupon_code = '{$params['coupon_code']}'";
        }

        if(!empty($params['status'])){
            switch ($params['status']){
                case 1:
                    $params['extendedWhere'] .= " AND mc.is_use = 't' AND mc.is_useoff = 'f' AND mc.is_active = 't'";
                    break;
                case 2:
                    $params['extendedWhere'] .= " AND mc.is_use = 't' AND mc.is_useoff = 't' AND mc.is_active = 't'";
                    break;
                case 3:
                    $params['extendedWhere'] .= " AND mc.is_active = 'f'";
                    break;
            }
        }

        $params['search']['value'] = !empty($params['searchval'])?$params['searchval']:'';

        $this->load->model('membervip/admin/Public_model','mempublics_model');

        $params['table_name'] = 'card_log';
        $params['alias'] = "cl";
        $params['join'] = array(
            array('table'=>'member_card as mc','on'=>"mc.member_card_id = cl.member_card_id",'type'=>'left'),
            array('table'=>'member_info as m','on'=>"m.member_info_id = cl.member_info_id",'type'=>'left'),
            array('table'=>'card as c','on'=>"c.card_id = cl.card_id",'type'=>'left')
        );
        $params['group_by'] = 'mc.member_card_id';
        $select = array('cl.*','mc.member_card_id','mc.coupon_code','mc.use_time','mc.card_id','mc.is_online','c.title','m.member_info_id','m.membership_number','m.name','m.nickname','m.telephone','m.cellphone','mc.use_time','mc.useoff_time','mc.is_use','mc.is_useoff','mc.is_active','mc.is_giving','mc.expire_time');
        $params['sort_field'] = 'mc.use_time';
        $params['sort_direct'] = 'desc';

        //排序字段
        $order_columns = array('mc.coupon_code','mc.use_time','mc.card_id','c.title','m.membership_number','m.name','m.telephone','cl.remark');
        if(isset($params['order']) && !empty($params['order'])){
            $params['sort_field'] = $order_columns[$params['order'][0]['column']];
            $params['sort_direct'] = $params['order'][0]['dir'];
            if(isset($params['order'][1]) && !empty($params['order'][1])){
                $params['sort_field'] = $order_columns[$params['order'][1]['column']];
                $params['sort_direct'] = $params['order'][1]['dir'];
            }
        }
        $params['opt'] = 15;
        $params['ui_type'] = 15;
        $params['f_type'] = 15;
        $params['sort_field'] .= ",cl.card_log_id";
        if(is_ajax_request()){
            //处理ajax请求
            $params['page_size'] = 20;
            $result = $this->mempublics_model->get_admin_filter($params,$select);
            exit(json_encode($result));
        }else{
            //HTML输出
            $this->label_action= '优惠券使用数据';
            $this->_init_breadcrumb($this->label_action);
            $this->load->model('wx/Publics_model');
            $public = $this->Publics_model->get_public_by_id($admin_profile['inter_id']);

            //base grid data..
            $this->load->model('membervip/admin/config/attribute_model','ui_model');
            $fields_config = $this->ui_model->get_field_config('grid',15);
            $default_sort= array('field'=>'use_time', 'sort'=>$params['sort_direct']);
            $view_params= array(
                'module'=> $this->ui_model,
                'model'=> $this->mempublics_model,
                'result'=> array(),
                'fields_config'=> $fields_config,
                'default_sort'=> $default_sort,
                'get'=>$get_filter,
                'public'=>$public,
            );
        }
        $html= $this->_render_content($this->_load_view_file('uselist'),$view_params,true);
        exit($html);
    }


    /**
    +----------------------------------------------------------
     * 生成随机字符串
    +----------------------------------------------------------
     * @param int       $length  要生成的随机字符串长度
    +----------------------------------------------------------
     * @return string
    +----------------------------------------------------------
     */
    public function randCode($length = 5,$srand='') {
        $string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $count = strlen($string) - 1;
        $code = '';
        if(!empty($srand)) srand($srand);
        for ($i = 0; $i < $length; $i++) {
            $code .= $string[mt_rand()%$count];
        }
        return $code;
    }
}
?>