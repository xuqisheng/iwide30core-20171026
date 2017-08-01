<?php
/*
 * 比价前台
 * author chenjunyu 2016-12-19
 */

defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
header("Content-type:text/html;charset=utf-8");

class Paritys extends CI_Controller{
	private $userinfo = array();
	function __construct(){
		parent::__construct();
		// $this->inter_id=$this->session->userdata('inter_id');
		// $this->openid=$this->session->userdata($this->inter_id.'openid');
		// $this->datas['inter_id'] = $this->inter_id;
		// if($this->input->get('asd')){
		// 	$this->output->enable_profiler(true);
		// }
		if($this->uri->segment(3)!='sign_in'&&$this->uri->segment(3)!='logout'&&$this->uri->segment(3)!='receive_price'){
			if(empty($this->session->userdata('userinfo'))){
				redirect(site_url('price/paritys/sign_in'));
			}else{
				$this->userinfo = $this->session->userdata('userinfo');
				if(empty($this->userinfo['head_pic'])){
					$this->userinfo['head_pic'] = base_url('public/price/default/images/name.png');
				}
			}
		}
	}

	//登录页
	public function sign_in(){
		if(!empty($this->session->userdata('userinfo'))){
			redirect(site_url('price/paritys/index'));
		}
		$data = array();
		$data['pagetitle'] = '比价-登录';
		$data['warn_text'] = $this->input->get('warn_text')?$this->input->get('warn_text'):'';// 警告提示信息
		$post = $this->input->post();
		if(!empty($post)){
			if(empty($post['username'])){
		        $data['warn_text'] = '请输入账号！';
		    } elseif(empty($post['password'])) {
		    	$data['warn_text'] = '请输入密码！';
		    }else{
		    	$this->load->model('price/paritys_model');
		    	if(!$this->paritys_model->checkSignIn($post['username'],$post['password'])){
		    		$data['warn_text'] = '账号或密码错误！';
		    	}else{
		    		redirect(site_url('price/paritys/index'));
		    	}
		    }
		}
		$this->load->view('price/default/sign_in',$data);
	}

	//退出登录
	public function logout(){
		$this->session->unset_userdata('userinfo');
		redirect(site_url('price/paritys/sign_in'));
	}

	//首页
	public function index(){
		$third_type = 'ctrip';// 后续增加对第三方平台类型判断
		$data = array();
		$third_types = array(
			'ctrip'=>'携程',
			'meituan'=>'美团',
			'alitrip'=>'阿里旅行',
			'qunar'=>'去哪儿',
			);
		$data['pagetitle'] = '比价-首页';
		$data['userinfo'] = $this->userinfo;
		$this->load->model('price/paritys_model');
		$data['uptime'] = $this->paritys_model->getNewDate(1);
		$data['lists'] = array();
		$offset = 0;
		$nums = 10;
		$condits = array();
		$condits['order'] = 'down_rate DESC,avg_diffprice DESC';
		$condits['offset'] = $offset;
		$condits['nums'] = $nums;
		$condits['hotel_ids'] = !empty($this->userinfo['hotel_ids'])?explode(',',$this->userinfo['hotel_ids']):array();
		$data['hotel_count'] = $this->paritys_model->getDownRate($this->userinfo['inter_id'],$third_type,$condits,true);
		$data['ismore'] = $data['hotel_count']>$nums?1:0;
		$down_rates = $this->paritys_model->getDownRate($this->userinfo['inter_id'],$third_type,$condits);
		// $avg_diffprices = $this->paritys_model->getAvgDiffPrice($this->userinfo['inter_id'],$third_type,$condits);
		foreach ($down_rates as $kd => $vd) {
		// 	$vd['avg_diffprice'] = $avg_diffprices[$vd['hotel_id']];
			$down_rates[$kd]['third_type'] = $third_types[$vd['third_type']];
		// 	$data['lists'][] = $vd;
		}
		$data['lists'] = $down_rates;
		$this->load->view('price/default/index',$data);
	}

	//比价列表页
	public function parity_list(){
		$third_type = 'ctrip';// 后续增加对第三方平台类型判断
		$data = array();
		$third_types = array(
			'ctrip'=>'携程',
			'meituan'=>'美团',
			'alitrip'=>'阿里旅行',
			'qunar'=>'去哪儿',
			);
		$data['pagetitle'] = '比价-列表';
		$this->load->model('price/paritys_model');
		$data['lists'] = array();
		$offset = 0;
		$nums = 20;
		$condits = array();
		$condits['order'] = 'down_rate DESC';
		$data['wd'] = $condits['wd'] = $this->input->get('wd')?$this->input->get('wd'):'';
		$condits['nums'] = $nums;
		$condits['hotel_ids'] = !empty($this->userinfo['hotel_ids'])?explode(',',$this->userinfo['hotel_ids']):array();
		$data['hotel_count'] = $this->paritys_model->getDownRate($this->userinfo['inter_id'],$third_type,$condits,true);
		$data['ismore'] = $data['hotel_count']>$nums?1:0;
		$data['maxpage'] = ceil($data['hotel_count']/$nums);
		$p = $this->input->get('p')?max(1,min(ceil($data['hotel_count']/$nums),$this->input->get('p'))):1;
		$condits['offset'] = ($p-1)*$nums;
		$down_rates = $this->paritys_model->getDownRate($this->userinfo['inter_id'],$third_type,$condits);
		// $avg_diffprices = $this->paritys_model->getAvgDiffPrice($this->userinfo['inter_id'],$third_type,$condits);
		foreach ($down_rates as $kd => $vd) {
		// 	$vd['avg_diffprice'] = $avg_diffprices[$vd['hotel_id']];
			$down_rates[$kd]['third_type'] = $third_types[$vd['third_type']];
		// 	$data['lists'][] = $vd;
		}
		$data['lists'] = $down_rates;
		$data['url'] = site_url('price/paritys/parity_list?wd='.$condits['wd']);
		if($this->input->get('p')){
			$this->load->view('price/default/parity_list_ajax',$data);
		}else{
			$this->load->view('price/default/parity_list',$data);
		}
	}

	//搜索结果页
	public function search_result(){
		$third_type = 'ctrip';// 后续增加对第三方平台类型判断
		$data = array();
		$third_types = array(
			'ctrip'=>'携程',
			'meituan'=>'美团',
			'alitrip'=>'阿里旅行',
			'qunar'=>'去哪儿',
			);
		$data['third_type'] = $third_types[$third_type]; 
		$data['pagetitle'] = '比价-搜索结果';
		$condits = array();
		$data['lists'] = array();
		$offset = 0;
		$nums = 5;
		$data['wd'] = $condits['wd'] = $this->input->get('wd')?$this->input->get('wd'):'';
		$data['inter_id'] = $this->input->get('inter_id')?$this->input->get('inter_id'):$this->userinfo['inter_id'];
		$condits['hotel_ids'] = $this->input->get('hotel_id')?array($this->input->get('hotel_id')):(!empty($this->userinfo['hotel_ids'])?explode(',',$this->userinfo['hotel_ids']):array());
		$this->load->model('price/paritys_model');
		$data['hotel_count'] = $this->paritys_model->getParitys($data['inter_id'],$third_type,$condits,true);
		$data['ismore'] = $data['hotel_count']>$nums?1:0;
		$data['maxpage'] = ceil($data['hotel_count']/$nums);
		$p = $this->input->get('p')?max(1,min(ceil($data['hotel_count']/$nums),$this->input->get('p'))):1;
		$offset = ($p-1)*$nums;
		$data['lists'] = $this->paritys_model->getParitys($data['inter_id'],$third_type,$condits,false,$offset,$nums);
		foreach ($data['lists'] as $k => $val) {
	 		foreach($val as $kv=>$v){
		 		$data['lists'][$k][$kv]['ibreakfast'] = !empty($v['ibreakfast'])?'_'.$v['ibreakfast']:'';
		 	}
	 	}
		$condits_dr['hotel_ids'] = $condits['hotel_ids'];
		$condits_dr['wd'] = $condits['wd'];
		$down_rates = $this->paritys_model->getDownRate($data['inter_id'],$third_type,$condits_dr);
		$data['hotel_city'] = '';
		if(!empty($down_rates)){
			foreach ($down_rates as $kd => $vd) {
				$data['down_rates'][$vd['hotel_id']] = $vd['down_rate'];
			}
			$data['hotel_city'] = $down_rates[0]['city'];
		}
		$data['url'] = site_url('price/paritys/search_result?wd='.$condits['wd']);
		if($this->input->get('p')){
			$this->load->view('price/default/search_result_ajax',$data);
		}else{
			$this->load->view('price/default/search_result',$data);
		}
	}

	// 接收比价结果生成完毕通知
	public function receive_price(){
		$key = 'EqX91CUha4PNjVYM';
		$inter_id = $this->input->get('inter_id');
		$batch = $this->input->get('batch');
		$sign = md5($key.$inter_id);
		if($sign!=$this->input->get('sign')){
			exit('非法访问');
		}
		$this->load->model('price/paritys_model');
		$this->load->model('hotel/hotel_notify_model');
		$this->load->model('plugins/template_msg_model');
		$this->load->library('MYLOG');
		$condits = array(
			'adddate'=>date('Y-m-d'),
			'batch'=>$batch,
			);
		$hotels = $this->paritys_model->getDownRate($inter_id,'ctrip',$condits);
		//比价生成开始时间
		$startinfo = $this->paritys_model->getStartInfo($inter_id,$condits);
		$usehotels = $this->paritys_model->getUseSmartRule($inter_id);
		foreach($hotels as $vk=>$vh){
			if(!in_array($vh['hotel_id'],$usehotels)){
				unset($hotels[$vk]);
			}
		}
		MYLOG::w('要发送的酒店：'.json_encode($hotels).'|'.json_encode($usehotels),'smarts');
		// var_dump($hotels);exit;
		foreach ($hotels as $k => $v) {
			//查出符合接收模板消息的人员信息
			$hotel_ids = array('hotel_ids'=>array(0,$v['hotel_id']));
			$regs = $this->hotel_notify_model->get_hotels_reg($inter_id,$hotel_ids,true);
			MYLOG::w('要发送的人员：'.json_encode($regs),'smarts');
			// var_dump($regs);exit;
			if(!empty($regs)){
				foreach($regs as $r=>$reg){
					if($this->hotel_notify_model->check_reg($reg,'down')){
						MYLOG::w('要发送的人员'.$reg['id'].'：'.json_encode($reg),'smarts');
						// 发送模板消息 价格倒挂提醒
						$info = array(
							'inter_id'=>$inter_id,
							'hotel_id'=>$v['hotel_id'],
							'batch'=>$batch,
							'openid'=>$reg['openid'],
							'hotel'=>$v['hotel_name'],
							'warn_type'=> 'down',
							'remark_type'=> 'down',
							'starttime'=> $startinfo['addtime'],
							'endtime'=> $v['addtime'],
							'warndate'=> date('Y-m-d H:i:s'),
							);
						$result = $this->template_msg_model->send_smart_price_msg ( $inter_id,$info,'smart_price_notice');
						$opresult = 1;
						if($result['s']!=1||$result['errmsg']!='ok'){
							$opresult = 0;
							MYLOG::w('智能调价确认模板消息发送失败:'.json_encode($info).'|'.json_encode($result),'smarts');
						}
						$this->paritys_model->saveAdjustLog($inter_id,$v['hotel_id'],$reg['openid'],date('Y-m-d'),$batch,'send',$opresult,array());
					}
				}
			}
		}
		echo 'ok';
	}
}