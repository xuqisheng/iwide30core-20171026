<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	后台会员管理
*	@author Frandon
*	@time 四月十二号
*	@version www.iwide.cn
*	@
*/
class Membermanage extends MY_Admin_Api
{
    protected $admin_info = '';
    protected $label_module = '会员中心4.0';
    protected $label_controller = '会员管理';
    protected $label_action = '会员列表';
    protected $module= '';


    //会员管理列表
	public function index(){
        $avgs = array();
        $this->load->model('membervip/admin/Public_model','pum');
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

        $params = array();
        if(is_array($get_filter)) {
            $params = $get_filter + $params;
        }


        if(isset($params['member_lvl']) && !empty($params['member_lvl'])){
            $params['mb.member_lvl_id'] = $params['member_lvl'];
        }

        $params['alias'] = "mb";
//        $select = array('member_info_id','inter_id','open_id','member_mode','nickname','name','membership_number','telephone','cellphone','member_lvl_id','credit','balance','is_active','is_login','createtime','COUNT(mc.member_card_id) as mcount','lvl.lvl_name');
        $select = array('member_info_id','inter_id','open_id','member_mode','nickname','name','membership_number','telephone','cellphone','member_lvl_id','credit','balance','is_active','is_login','createtime');
        foreach ($select as &$fv){
            $fv = $params['alias'].'.'.$fv;
        }

        $params['table_name'] = 'member_info';
        $params['pk'] = 'member_info_id';
        $params['sort_field'] = 'mb.createtime';
        $params['sort_direct'] = 'desc';
        $params['mb.inter_id'] = $admin_profile['inter_id'];
        $params['mb.is_active'] = 't';
        $params['mcount'] = true;

        $params['opt'] = 1;
        $params['ui_type'] = 1;
        $params['ispackage'] = 0;
        $params['f_type'] = 1;

        //排序字段
        $order_columns = array('mb.member_info_id','mb.nickname','mb.member_mode','mb.name','mb.membership_number','mb.member_lvl_id','mb.credit','mb.balance','mb.createtime','mb.is_active','mb.is_login','mb.createtime');
        if(isset($params['order']) && !empty($params['order'])){
            $params['sort_field'] = $order_columns[$params['order'][0]['column']];
            $params['sort_direct'] = $params['order'][0]['dir'];
            if(isset($params['order'][1]) && !empty($params['order'][1])){
                $params['sort_field'] = $order_columns[$params['order'][1]['column']];
                $params['sort_direct'] = $params['order'][1]['dir'];
            }
        }

        $member_mode = $this->pum->get_member_mode($admin_profile['inter_id']);
        $member_lvl = $this->pum->get_admin_member_lvl($admin_profile['inter_id']);
        $params['member_lvl_data'] = $member_lvl;
        $inter_id = $admin_profile['inter_id'];
        $counts = $this->pum->_shard_db()->query("SELECT COUNT(member_info_id) as count FROM iwide_member_info WHERE inter_id = '$inter_id' AND is_active='t'")->row_array();
        $result['data'] = array();
        $result['total'] = $counts['count'];
        if(is_ajax_request()){
            //处理ajax请求
            $params['page_size'] = 20;
            $result = $this->pum->get_admin_filter($params,$select,$member_mode);
            echo json_encode($result);exit;
        } else {
            //HTML输出
            if( !$this->label_action ) $this->label_action= '会员列表';
            $this->_init_breadcrumb($this->label_action);

            //base grid data..
            $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
            $params['mstatistics'] = true;
            if($result['total'] < $num) $result = $this->pum->get_admin_filter($params,$select,$member_mode);
            $lvl_sql="SELECT count(*) as count ,mb.member_lvl_id,ml.lvl_name FROM `iwide_member_info` as mb,iwide_member_lvl as ml where mb.inter_id ='".$admin_profile['inter_id']."'and ml.member_lvl_id=mb.member_lvl_id  group by member_lvl_id ";
            $lvl_list = $this->pum->_shard_db()->query($lvl_sql)->result_array();
            $this->load->model('membervip/admin/config/attribute_model','ui_model');
            $fields_config = $this->ui_model->get_field_config('grid',1);
            $default_sort= array('field'=>'createtime', 'sort'=>$params['sort_direct']);

            $view_params= array(
                'module'=> $this->ui_model,
                'model'=> $this->pum,
                'result'=> $result,
                'fields_config'=> $fields_config,
                'default_sort'=> $default_sort,
                'member_lvl'=>$member_lvl,
                'get'=>$get_filter,
                'lvl_list'=>$lvl_list
            );
            $html = $this->_render_content($this->_load_view_file('index'), $view_params, TRUE);
            echo $html;
        }
	}

	//会员信息详细
	public function add(){
		$member_info_id = $this->input->get('member_info_id');
		$inter_id = $this->session->get_admin_inter_id();
		$post_data = array(
			'inter_id'=>$inter_id,
			'member_info_id'=>$member_info_id,
        );
		//请求卡券的详细信息(修改时有结果)
        $member_info = $this->doCurlPostRequest( PMS_PATH_URL."adminmember/get_member_info" , $post_data );
        $member_info = isset($member_info['data'])?$member_info['data']:[];
		if(empty($member_info)){
            $this->session->put_error_msg('找不到会员信息！会员ID：'.$member_info_id);
            $this->_redirect(EA_const_url::inst()->get_url('*/*'));
		}
		if($member_info_id){
			//请求会员升级记录
			$post_lvllog_url = INTER_PATH_URL.'lvllog/getlist';
			$post_lvllog_data = array(
				'token'=>$this->_token,
				'inter_id'=>$this->session->get_admin_inter_id(),
				'openid'=>isset($member_info['open_id'])?$member_info['open_id']:'',
            );
            $member_info['lvllog'] = $this->doCurlPostRequest( $post_lvllog_url , $post_lvllog_data )['data'];
		}else{
            $member_info['lvllog'] = array();
		}
		if(!empty($member_info)){ unset($member_info['password']); }
		$this->_render_content($this->_load_view_file('add'),$member_info,false);
	}

	//积分调整
	public function edit_credit(){
        $this->admin_info = $this->session->get_admin_profile();
		$inter_id = $this->session->get_admin_inter_id();
		$member_info_id = $this->input->get('member_info_id');
		$amount = $this->input->get('creditamount');
		$note = $this->input->get('note');
		if($amount==0){
			echo json_encode( array( 'err'=>1000 , 'msg'=>'调整积分不能为零' ) );exit;
		}
		if(!$note){
			echo json_encode( array( 'err'=>1000 , 'msg'=>'调整备注不能为空' ) );exit;
		}
		if( strstr($amount,'-') ){
			$amount = str_replace("-", "", $amount);
			$url = INTER_PATH_URL.'credit/useoff';
			$post_data = array(
				'token'=>$this->_token,
				'inter_id'=>$inter_id,
				'member_info_id'=>$member_info_id,
				'count'=>$amount,
				'module'=>'admin',
				'scene'=>'vip',
				'uu_code'=>uniqid().time(),
				'remark'=>$note,
				);
			$result = $this->doCurlPostRequest( $url , $post_data );
            $result['count'] = $amount;
            $result['mark'] = '2';
            echo json_encode($result);exit;
		}else{
			$amount = str_replace("+", "", $amount);
			$url = INTER_PATH_URL.'credit/add';
			$post_data = array(
				'token'=>$this->_token,
				'inter_id'=>$inter_id,
				'member_info_id'=>$member_info_id,
				'count'=>$amount,
				'module'=>'admin',
				'scene'=>'vip',
				'uu_code'=>uniqid().time(),
				'remark'=>$note,
				);
			$result = $this->doCurlPostRequest( $url , $post_data );
            $result['count'] = $amount;
            $result['mark'] = '1';
			echo json_encode($result);exit;
		}


	}

	//储值调整
	public function edit_balance(){
        $this->admin_info = $this->session->get_admin_profile();
		$inter_id = $this->session->get_admin_inter_id();
		$member_info_id = $this->input->get('member_info_id');
		$amount = $this->input->get('balanceamount');
		$note = $this->input->get('note');
		if($amount==0){
			echo json_encode( array( 'err'=>1000 , 'msg'=>'调整储值不能为零' ) );exit;
		}

		$_amount = abs(intval($amount));
        if($_amount > 20000){
            echo json_encode( array( 'err'=>1089 , 'msg'=>'充值金额过大' ) );exit;
        }

		if(!$note){
			echo json_encode( array( 'err'=>1000 , 'msg'=>'调整备注不能为空' ) );exit;
		}

		$exc_type = false;
        $result = array();
		if( strstr($amount,'-') ){
			$amount = str_replace("-", "", $amount);
			$url = INTER_PATH_URL.'deposit/useoff';
			$post_data = array(
				'token'=>$this->_token,
				'inter_id'=>$inter_id,
				'member_info_id'=>$member_info_id,
				'count'=>$amount,
				'uu_code'=>uniqid().time(),
				'module'=>'admin',
				'scene'=>'vip',
				'note'=>$note,
				);
			$result = $this->doCurlPostRequest( $url , $post_data );
            $result['count'] = $amount;
            $result['mark'] = '2';
		}else{
            $exc_type = true;
			$amount = str_replace("+", "", $amount);
			$url = INTER_PATH_URL.'deposit/add';
			$post_data = array(
				'token'=>$this->_token,
				'inter_id'=>$inter_id,
				'member_info_id'=>$member_info_id,
				'count'=>$amount,
				'uu_code'=>uniqid().time(),
				'module'=>'admin',
				'scene'=>'vip',
				'note'=>$note,
            );
			$result = $this->doCurlPostRequest( $url , $post_data );
            $result['count'] = $amount;
            $result['mark'] = '1';
		}
		if(!empty($result) && $result['err'] == 0){
            $url = INTER_PATH_URL.'deposit/save_record_log';
            $post_data = array(
                'token'=>$this->_token,
                'inter_id'=>$inter_id,
                'admin_id'=>$this->admin_info['admin_id'],
                'member_info_id'=>$member_info_id,
                'original'=>isset($result['data']) ? floatval($result['data']) : '',
                'amount'=>$amount,
                'module'=>'admin',
                'scene'=>'vip',
                'note'=>$note,
                'ip'=>$this->input->ip_address(),
                'execut_time'=>time(),
                'mode'=>$exc_type ? 1 : 2
            );
            $res = $this->doCurlPostRequest($url,$post_data);
            $result['res'] = $res;
        }
        echo json_encode($result);exit;
    }


	//保存修改会员信息
	public function edit_post(){
		$inter_id = $this->session->get_admin_inter_id();
		$card_id = $this->input->post('card_id');
		$data = $this->input->post();
		//图片上传
		$data= $this->_do_upload($data, 'logo_url');
		unset($data['card_id']);
		//如果ID存在则为修改否则增加
		if($card_id){
			if(!$data['logo_url']){unset($data['logo_url']);}
			$post_data = array(
				'inter_id'=>$inter_id,
				'data'=>$data,
				'card_id'=>$card_id,
				);
			$update_result = $this->doCurlPostRequest( PMS_PATH_URL."adminmember/update_inter_card_info" , $post_data );
			redirect('membervip/membercard/add?card_id='.$card_id);
		}else{
			$post_data = array(
				'inter_id'=>$inter_id,
				'data'=>$data,
				);
			$add_result = $this->doCurlPostRequest( PMS_PATH_URL."adminmember/add_inter_card_info" , $post_data );
			redirect('membervip/membercard');
		}
		exit;
	}

    /**
     * 2016-08-18
     * @author knight
     * 同步未设置等级的会员
     */
    public function synchronize_member_lvl(){
        if($this->is_ajax){
            $inter_id = $this->session->get_admin_inter_id();
            if(!$inter_id || empty($inter_id)) $this->_ajaxReturn('fail','无效的公众号',0);
            $where['inter_id'] = trim($inter_id);
            $where['is_default'] = 't';
            $model= $this->_load_model('member/Member_related_model');
            $member_lvl = $model->_shard_db()->select("member_lvl_id,lvl_name")->where($where)->order_by('member_lvl_id desc')->get('member_lvl')->row_array();
//            $model->_shard_db()->last_query();
            if(!empty($member_lvl)){
                $where = array(
                    'member_lvl_id'=>0,
                    'inter_id'=>$inter_id
                );
                $member_info = $model->_shard_db()->select("member_info_id,member_id,open_id,member_lvl_id")->where($where)->order_by('member_info_id desc')->get('member_info')->result_array();
                if(!empty($member_info) && is_array($member_info)){
                    $_batch=array();
                    foreach ($member_info as $key => $val){
                        $_batch[]=array(
                            'member_info_id'=>$val['member_info_id'],
                            'member_lvl_id'=>$member_lvl['member_lvl_id']
                        );
                    }
                    if(!empty($_batch)){
                        $res = $model->_shard_db()->update_batch('member_info',$_batch,'member_info_id');
                        $this->_ajaxReturn('ok',$res,1);
                    }
                }
                $this->_ajaxReturn('complete',null,1);
            }
            $this->_ajaxReturn('null',null,1);
        }
        $this->_ajaxReturn('fail','请求失败',0);
    }

    public function unbind(){
        if(is_ajax_request()){
            $this->load->model('membervip/admin/Public_model','Pub_model');
            $inter_id = $this->session->get_admin_inter_id();
            $member_info_id = $this->input->post('member_info_id');
            $openid = $this->input->post('openid');
            if(!$inter_id || empty($inter_id)) $this->_ajaxReturn('无效的公众号');
            $member_info = $this->Pub_model->get_info(array('member_info_id'=>$member_info_id),'member_info');
            if(empty($member_info)) {
                $this->_ajaxReturn('该会员信息不存在');
            }

            if($member_info['is_login']=='f'){
                $this->_ajaxReturn('该会员已解除绑定，无需再次解绑');
            }

            $url = PMS_PATH_URL.'member/outlogin'; //退出登录 （解绑）
            $post_data = array(
                'inter_id'=>$inter_id,
                'openid'=>$openid,
            );
            $logs = array(
                'title'=>'会员解绑操作',
                'filter'=>array(),
                'rule_name'=>$this->module.'/'.$this->controller.'/'.$this->action,
                'name'=>!empty($member_info['name'])?"{$member_info['name']}({$member_info['membership_number']})":"{$member_info['nickname']}({$member_info['membership_number']})"
            );
            $res = $this->doCurlPostRequest($url,$post_data);
            $_result = (isset($res['err']) && $res['err'] == 0)?1:0;

            $this->load->model('membervip/common/Public_log_model','logm');
            $this->logm->save_log_init(array('is_login'=>$member_info['is_login']),array('is_login'=>'f'),$member_info['member_info_id'],$_result,'member_unbind',$this->Pub_model,$logs); //添加操作记录

            if(!isset($res['err']) OR $res['err'] > 0){
                $this->_ajaxReturn('解绑失败');
            }
            $this->_ajaxReturn('解绑成功',null,1);
        }
        $this->_ajaxReturn('fail','请求失败',0);
    }

    /**
     * 新增绑定会员明细表
     */
    public function new_bind_member_fans(){
        $keys = $this->uri->segment(4);
        $avgs = array();
        $avgs['begin_time']		= $this->input->post('begin_time');
        $avgs['end_time']		= $this->input->post('end_time');

        $keys = explode('_', $keys);

        if(!empty($keys[0])){
            $avgs['begin_time'] = $keys[0];
        }
        if(!empty($keys[1])){
            $avgs['end_time'] = $keys[1];
        }

        $this->load->model('member/Member_related_model');
        $admin_profile = $this->session->userdata('admin_profile');


        $this->load->library('pagination');
        $config['per_page']          = 30;
        $page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];


        $filterH ['inter_id'] = $admin_profile['inter_id'];


        $config['use_page_numbers']  = TRUE;
        $config['cur_page']          = $page;
        $avgs['inter_id'] = $admin_profile['inter_id'];
        $res = $this->Member_related_model->get_bind_member_fans($avgs,$config['per_page'],$config['cur_page']);

        $config['uri_segment']       = 5;

        $config['numbers_link_vars'] = array('class'=>'number');
        $config['cur_tag_open']      = '<a class="number current" href="#">';
        $config['cur_tag_close']     = '</a>';
        $config['base_url']          = site_url("membervip/membermanage/new_bind_member_fans/".$avgs['begin_time'].'_'.$avgs['end_time']);
        $config['total_rows']        = $this->Member_related_model->get_bind_member_fans_count($avgs);
        $config['cur_tag_open'] = '<li class="paginate_button active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="paginate_button">';
        $config['num_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="paginate_button first">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="paginate_button last">';
        $config['last_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="paginate_button previous">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="paginate_button next">';
        $config['next_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $view_params= array(
            'pagination' => $this->pagination->create_links(),
            'res'        => $res,
//            'confs'      => $confs,
            //'hotels'     => $hotels,
            'posts'      => $avgs,
            'total'      => $config['total_rows'],
        );

        $html= $this->_render_content($this->_load_view_file('new_bind_member_fans'), $view_params, TRUE);
        echo $html;
    }


    /**
     * 导出  新增绑定会员明细表
     */
    public function ext_new_bind_member_fans(){
        $keys = $this->uri->segment(4);

        $avgs = array();
        $avgs['begin_time']		= $this->input->post('begin_time');
        $avgs['end_time']		= $this->input->post('end_time');
        $keys = explode('_', $keys);

        if(!empty($keys[0])){
            $avgs['begin_time'] = $keys[0];
        }
        if(!empty($keys[1])){
            $avgs['end_time'] = $keys[1];
        }

        $this->load->model('member/Member_related_model');
        $admin_profile = $this->session->userdata('admin_profile');
        $confs = array('会员卡编号','粉丝openid','会员创建时间');

        $avgs['inter_id'] = $admin_profile['inter_id'];
        $res = $this->Member_related_model->get_bind_member_fans($avgs);

        $data = "";
        foreach ($confs as $key=>$item){
            $data = $data.iconv('utf-8','gb2312',$item).",";
        }

        $data = $data."\n";
        foreach ($res as $item ){
            $data = $data.$item['membership_number']." ,";
            $data = $data.$item['open_id']." ,";
            $data = $data.date('Y-m-d H:i:s', $item['createtime'])." ,";
            $data = $data."\n";
        }
        // 发送标题强制用户下载文件
        header ('Content-Type: text/csv' );
        header ('Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.csv"' );
        header ('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $data;
    }
}
?>