<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
header ( "Content-type:text/html;charset=utf-8" );
/**
 *
 * @author John
 *         全民分销
 * @since 2016-09-08
 */
class Dis_ext extends MY_Front {
	private static $theme;
	private static $saler;
	protected $datas = array();
	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->userdata ( 'inter_id' );
		$this->openid = $this->session->userdata ( $this->inter_id . 'openid' );
		self::$theme = 'distribute/dis_ext_default/';
		if ($this->input->get ( 'theme' )) {
			self::$theme = 'distribute/' . $this->input->get ( 'theme' ) . '/';
		}
		$this->datas ['inter_id'] = $this->inter_id;
		if(empty(self::$saler)){
			$this->load->model('distribute/fans_model');
			$fans_info = $this->fans_model->get_fans_info_by_openid($this->inter_id,$this->openid);
			if (!empty($fans_info))
			self::$saler = $fans_info->fans_key;
		}
	}
	
	/**
	 * 激活全民分销引导页
	 * 可以把链接放到公众号菜单上或者图文上，召集分销人员注册激活
	 */
	public function guide(){
		$inter_id = $this->inter_id;
		$openid   = $this->session->userdata ( $this->inter_id . 'openid' );
		$this->load->model('distribute/distribute_ext_model');
		$user_info = $this->distribute_ext_model->check_fans($inter_id,$openid);
		$user_info = json_decode($user_info);
		if($user_info && $user_info->typ == "STAFF"){
			redirect(site_url('distribute/dis_v1/mine').'?id='.$inter_id);
		}elseif ($user_info && $user_info->typ == "FANS"){
			redirect(site_url('distribute/dis_ext/mine').'?id='.$inter_id);
		}
		$rinter_id = $this->distribute_ext_model->get_redis_key_status ( '__DISTRIBUTION_DELIER_ACCOUNT' );
		if(!$rinter_id)
			$rinter_id = $inter_id;
		$this->load->model('wx/publics_model');
		$acc_info = $this->publics_model->get_public_by_id($rinter_id);
		$datas = array (
				'inter_id' => $this->session->userdata ( 'inter_id' ),
				'rinter_id'=> $rinter_id,
				'acc_info' => $acc_info,
				'openid'   => $this->session->userdata ( $this->session->userdata ( 'inter_id' ) . 'openid' ),
				'rtn_url'  => urlencode ( site_url('distribute/dis_ext/guide').'?id='.$this->session->userdata ( 'inter_id' ) )
		);
		$this->load->view ( 'distribute/dis_ext_default/guide', $datas );
	}
	
	/**
	 * 授权激活确认
	 * 分销人员激活确认页，可提供给其他模块引用，使用时必须已POST方式传输其当前公众号的openid和inter_id以做绑定
	 * @param POST['openid'] 当前公众号的openid
	 * @param POST['inter_id'] 当前公众号的inter_id
	 * @param POST['rtn_url'] 操作完成后返回的经过urlencode的url，url上必须带上inter_id参数，否则会出现账号信息错误
	 */
	public function act_confirm() {
		$this->load->model('wx/publics_model');
		$rtn_url   = '';
		$openid    = '';
		$inter_id  = '';
		if($this->input->get('t')){
			$avgs = base64_decode(urldecode($this->input->get('t')));
			if(!$avgs){
				redirect ( site_url ( '/distribute/dis_ext/perror' ) . '?id=' . $this->input->get ( 'id' ) );
			}else{
				$avgs = explode('***', $avgs);
				$rtn_url   = urldecode($avgs[0]);
				$openid    = $avgs[1];
				$inter_id  = $avgs[2];
			}
		}else if(empty($this->input->post ( 'openid' )) || empty($this->input->post ( 'inter_id' )))
			redirect ( site_url ( '/distribute/dis_ext/perror' ) . '?id=' . $this->input->get ( 'id' ) );
		else {
			$rtn_url  = urldecode($this->input->get_post('rtn_url'));
			$openid   = $this->input->post('openid');
			$inter_id = $this->input->post('inter_id');
		}
		$user_info = $this->publics_model->get_wxuser_info($inter_id,$openid);
		if(isset($user_info) && $user_info['subscribe'] == 0){
			$acc_info = $this->publics_model->get_public_by_id($inter_id);
// 			redirect(site_url ( '/distribute/dis_ext/unsubs' ) . '?id=' . $this->input->get ( 'id' ));
			redirect(prep_url($acc_info['follow_page']));
			exit;
		}
		$this->load->model('distribute/distribute_ext_model');
		$user_info = $this->distribute_ext_model->check_fans($inter_id,$openid);
		$user_info = json_decode($user_info);
		if($user_info && $user_info->typ == "STAFF"){
			if(empty($rtn_url)){
				redirect(site_url('distribute/dis_v1/mine').'?id='.$inter_id);
			}else 
				redirect($rtn_url);
		}elseif ($user_info && $user_info->typ == "FANS"){
			if(empty($rtn_url)){
				redirect(site_url('distribute/dis_ext/mine').'?id='.$inter_id);
			}else{
// 				echo $rtn_url;die;
				redirect($rtn_url);
			}
		}
		$this->load->model ( 'distribute/openid_rel_model' );
		
		
		$this->openid_rel_model->new_rel ( array (
				'openid'     => $openid,
				'inter_id'   => $inter_id,
				'm_inter_id' => $this->session->userdata ( 'inter_id' ),
				'm_openid'   => $this->session->userdata ( $this->session->userdata ( 'inter_id' ) . 'openid' ) 
		) );
		$datas = array (
				'title'                 => '激活确认',
				'result_icon'           => 'info',
				'operation_result'      => '激活确认',
				'operation_description' => '确认激活全民分销身份吗？',
				'confirm_btn'           => TRUE,
				'confirm_btn_text'      => '激活并返回',
				'confirm_url'           => site_url('distribute/dis_ext/do_activation').'?id='.$this->session->userdata('inter_id'),
				'cancel_btn'            => TRUE,
				'cancel_btn_text'       => '取消',
				'cancel_url'            => $rtn_url,
				'confirm_form'          => true, 
				'posts'                 => array('rtn_url'=>urlencode($rtn_url),'openid'=>$openid,'inter_id'=>$inter_id) 
		);
		$this->load->view ( 'distribute/dis_ext_default/pmsg', $datas );
	}
	/**
	 * 登记OPENID关联
	 */
	public function auto_back(){
		$sem = $this->input->get('f');
		if($sem) $sem = base64_decode($sem);
		$segment_arr = explode('***', $sem);
		if (empty($segment_arr[0]) || empty($segment_arr[1]))
			redirect ( site_url ( '/distribute/dis_ext/perror' ) . '?id=' . $this->input->get ( 'id' ) );
		$this->load->model ( 'distribute/openid_rel_model' );
		$burl = $segment_arr[2];
		if(!$this->openid_rel_model->new_rel ( array (
				'openid'     => $segment_arr[1],
				'inter_id'   => $segment_arr[0],
				'm_inter_id' => $this->session->userdata ( 'inter_id' ),
				'm_openid'   => $this->session->userdata ( $this->session->userdata ( 'inter_id' ) . 'openid' ) 
		) )){
			log_message ( 'error', '公众号openid关联失败，FROM:' . $this->input->post ( 'inter_id' ) . '-' . $this->input->post ( 'openid' ) . ' TO:' . $this->input->post ( 'inter_id' ) );
			if(stripos($burl,'?') === FALSE)
				$burl = $burl.'?rel_res=faild';
			else
				$burl = $burl.'&rel_res=faild';
		}else{
			if(stripos($burl,'?') === FALSE)
				$burl = $burl.'?rel_res=ok';
			else
				$burl = $burl.'&rel_res=ok';
		}
			
		redirect($burl);
	}
	public function do_activation(){
		$this->load->model('distribute/distribute_ext_model');
		if($this->distribute_ext_model->do_active_fans($this->input->post('inter_id'),$this->input->post('openid'))){
			$this->load->model('plugins/Template_msg_model');
			$this->load->model('distribute/openid_rel_model');
			$fans_info = $this->openid_rel_model->get_openid_relationship($this->input->post('inter_id'),$this->input->post('openid'));
			$this->Template_msg_model->send_fans_dist_msg(array('inter_id'=>$fans_info->inter_id,'openid'=>$fans_info->openid,'nickname'=>$fans_info->nickname,'actv_time'=>$fans_info->actv_time),'fans_dist_activ_completed');
			redirect(urldecode($this->input->post('rtn_url')));
		}else{
			echo '<script>history.back();</script>';
			exit();
		}
	}
	public function perror() {
		$datas = array (
				'title'                 => '参数错误',
				'result_icon'           => 'warn',
				'operation_result'      => '操作失败',
				'operation_description' => '缺少参数，请返回重试！' 
		);
		$this->load->view ( 'distribute/dis_ext_default/pmsg', $datas );
	}
	public function unsubs() {
		$datas = array (
				'title'                 => '参数错误',
				'result_icon'           => 'warn',
				'operation_result'      => '操作失败',
				'operation_description' => '用户未关注' 
		);
		$this->load->view ( 'distribute/dis_ext_default/pmsg', $datas );
	}
	public function mine() {
		$this->load->model ( 'distribute/distribute_ext_model' );
		$user_info = json_decode ( $this->distribute_ext_model->check_fans ( $this->inter_id, $this->openid ) );
		if ($user_info && $user_info->typ == 'STAFF') {
			redirect ( site_url ( 'distribute/dis_v1/mine' ) . '?id=' . $this->inter_id );
		} 
		
		if(!$user_info){
			$rinter_id = $this->distribute_ext_model->get_redis_key_status ( '__DISTRIBUTION_DELIER_ACCOUNT' );
			if(!$rinter_id)
				$rinter_id = $inter_id;
			$this->load->model('wx/publics_model');
			$acc_info = $this->publics_model->get_public_by_id($rinter_id);
			redirect(prep_url($acc_info['domain']).'/index.php/distribute/dis_ext/auto_back?id='.$rinter_id.'&t='.base64_encode($rtn_url.'***'.$openid.'***'.$inter_id));
		}
		
		//免激活，跳过激活检查
// 		elseif (! $user_info) {
// 			echo '<script>history.back();</script>';
// 			exit ();
// 		}
		$this->load->model ( 'distribute/fans_model' );
		$this->datas ['saler_info']      = $this->fans_model->get_fans_info_by_id ( $this->inter_id, $this->openid, 'openid' );
// 		var_dump($this->datas ['saler_info']);die;
		$this->datas ['total_amount']    = $this->distribute_ext_model->get_saler_grades_by_date ( $this->inter_id, self::$saler, null, $type = 'ALL' )->total;
		$this->datas ['today_amount']    = $this->distribute_ext_model->get_saler_grades_by_date ( $this->inter_id, self::$saler, date ( 'Y-m-d' ), $type = 'ALL' )->total;
		$this->datas ['yestoday_amount'] = $this->distribute_ext_model->get_saler_grades_by_date ( $this->inter_id, self::$saler, date ( "Y-m-d", strtotime ( "-1 day" ) ), $type = 'ALL' )->total;
		if (empty ( $this->datas ['total_amount'] ))
			$this->datas ['total_amount'] = 0;
		if (empty ( $this->datas ['today_amount'] ))
			$this->datas ['today_amount'] = 0;
		if (empty ( $this->datas ['yestoday_amount'] ))
			$this->datas ['yestoday_amount'] = 0;
		$this->load->view ( self::$theme . 'header', $this->datas );
		$this->load->view ( self::$theme . 'mine' );
	}
	public function incomes(){
		$this->load->model ( 'distribute/distribute_ext_model' );
		$this->load->model ( 'distribute/grades_model' );
		$this->datas ['grades'] = $this->distribute_ext_model->get_saler_grades_by_date ( $this->inter_id, self::$saler, null, $type = 'ALL', null );
		$this->datas ['send']   = $this->distribute_ext_model->get_saler_grades_by_date ( $this->inter_id, self::$saler, null, $type = 'OLD', null );
		$this->datas ['unsend'] = $this->distribute_ext_model->get_saler_grades_by_date ( $this->inter_id, self::$saler, null, $type = 'NEW', null );
		
		$this->datas ['grades_types'] = $this->grades_model->grade_types;
		$this->datas ['grade_status'] = $this->grades_model->grade_status;
		$grade_type = 'ALL';
		if ($this->input->get ( 'g_typ' ) && in_array ( $this->input->get ( 'g_typ' ), array ( 'ALL', 'PRE', 'OLD', 'NEW' ) ))
			$grade_type = $this->input->get ( 'g_typ' );
		$this->datas ['g_typ'] = $grade_type;
		$this->datas ['g_sts'] = $this->grades_model->grade_status;
		$this->datas ['o_sts'] = $this->grades_model->order_status;
		$this->datas ['logs']  = $this->distribute_ext_model->get_saler_grades_logs_by_month ( $this->inter_id, self::$saler, $grade_type, NULL, 0, 100 );
		$this->load->model ( 'hotel/hotel_model' );
		$hotels = $this->hotel_model->get_hotel_hash ( array ( 'inter_id' => $this->inter_id ), array ( 'hotel_id', 'name' ) );
		$khotel = array ();
		foreach ( $hotels as $item ) {
			$khotel [$item ['hotel_id']] = $item ['name'];
		}
		$this->datas ['hotels'] = $khotel;
		
		$deliver_config = $this->grades_model->get_deliver_setting ( $this->inter_id );
		if ($deliver_config) {
			if ($deliver_config->mode == 1) {
				$this->datas ['deliver_config'] = TRUE;
			} else {
				$this->datas ['deliver_config'] = FALSE;
			}
		} else {
			$this->datas ['deliver_config'] = FALSE;
		}
		$this->load->view ( self::$theme . 'header', $this->datas );
		$this->load->view ( self::$theme . 'incomes' );
	}
	public function msgs(){
		$this->datas['inter_id'] = $this->inter_id;
		$this->load->view(self::$theme . 'header',$this->datas );
		$this->load->view(self::$theme . 'notices');
	}
	function msg_det(){
		$this->load->model('distribute/IDistribute_model','idistribute');
		$this->datas['msg']      = $this->idistribute->get_single_notice($this->input->get('mid'),$this->inter_id,$this->openid);
		if($this->datas['msg']->flag == 0){
			$this->idistribute->do_read_msg($this->datas['msg']->eid);
		}
		$this->datas['msg_typs'] = array(10=>'收益发放',12=>'系统消息');
		$this->datas['inter_id'] = $this->inter_id;
		$this->load->view(self::$theme . 'header',$this->datas );
		$this->load->view(self::$theme . 'notices_det');
	}
	function msgs_asy(){
	
		$this->load->model('distribute/IDistribute_model','idistribute');
		$msg_typ = 10;
		if($this->input->get('mstyp') == 'qa'){
			$msg_typ = 12;
		}
		echo json_encode($this->idistribute->get_my_notices_by_category($this->openid,$this->inter_id,$msg_typ));
	}
}