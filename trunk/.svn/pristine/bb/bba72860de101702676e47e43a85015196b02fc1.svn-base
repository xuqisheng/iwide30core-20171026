<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("Content-type:text/html;charset=utf-8");
class Dis_v1 extends MY_Front {
	private static $theme;
	private static $saler;
	protected $datas = array();
	public function __construct()
	{
		parent::__construct ();
		$this->inter_id = $this->session->userdata ( 'inter_id' );
		$this->openid = $this->session->userdata ( $this->inter_id . 'openid' );
		if (empty ( self::$saler )) {
			$this->load->model ( 'distribute/staff_model' );
			$saler_details = $this->staff_model->get_saler_details_by_openid ( $this->inter_id, $this->openid );
			// $saler_base_info = $this->get_saler_base_info ();
			if (! empty ( $saler_details ['saler_id'] ))
				$this->session->set_userdata ( $this->inter_id . 'saler', $saler_details ['saler_id'] );
		}
		$this->fans_id = $this->input->get ( 'f' );
		$identity = $this->input->get ( 't' );
		self::$theme = 'distribute/theme_1_1/';
		if ($this->input->get ( 'theme' )) {
			self::$theme = 'distribute/' . $this->input->get ( 'theme' ) . '/';
		}

		$this->datas ['inter_id'] = $this->inter_id;
		if ($this->input->get ( 'debug' )) {
			$this->output->enable_profiler ( true );
		}
		$this->load->model ( 'wx/publics_model' );
		$ac_infos = $this->publics_model->get_public_by_id ( $this->inter_id );

		// for share
		if (empty ( $saler_details ['headimgurl'] )) {
			$this->load->model ( 'distribute/fans_model' );
			$fans_info = $this->fans_model->get_fans_info_by_openid ( $this->inter_id, $this->openid );
			if (isset ( $fans_info->headimgurl ))
				$saler_details ['headimgurl'] = $fans_info->headimgurl;
		}

		$this->datas ['signPackage']       = $this->getSignPackage ();
		$this->datas ['share'] ['title']   = '全员营销-' . $ac_infos ['name']; // Index
		$this->datas ['share'] ['link']    = '';
		$this->datas ['share'] ['imgUrl']  = empty ( $saler_details ['headimgurl'] ) ? '' : $saler_details ['headimgurl'];
		$this->datas ['share'] ['desc']    = '员工闪电登记、粉丝永久归属、交易即时绩效、绩效实时发放'; // Index
		$this->datas ['share'] ['type']    = '';
		$this->datas ['share'] ['dataUrl'] = '';
		//公众号openid关联检查
		$this->load->model ( 'distribute/openid_rel_model' );
		$d_acount = $this->openid_rel_model->get_redis_key_status('__DISTRIBUTION_DELIER_ACCOUNT');
		log_message('error', '__DISTRIBUTION_DELIER_ACCOUNT | '.$d_acount);
		if($d_acount){
			if(!$this->openid_rel_model->is_relationship_exist($this->inter_id,$this->openid,$d_acount)){
				$url = '';
				if( isset($_SERVER['SERVER_SOFTWARE']) && $_SERVER['SERVER_SOFTWARE']=='nginx')
					$url =  'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'] ;
					else
						$url =  'http://' . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'] ;
						$deliver_infos = $this->publics_model->get_public_by_id ( $d_acount );
						// 				$site_url = 'http://credit.iwide.cn/index.php/distribute/dis_ext/auto_back/'.'?id='.$this->openid_rel_model->get_redis_key_status('__DISTRIBUTION_DELIER_ACCOUNT').'&f='.base64_encode($this->inter_id.'***'.$this->openid.'***'.$url);
						$site_url = prep_url($deliver_infos['domain']).'/distribute/dis_ext/auto_back/'.'?id='.$this->openid_rel_model->get_redis_key_status('__DISTRIBUTION_DELIER_ACCOUNT').'&f='.base64_encode($this->inter_id.'***'.$this->openid.'***'.$url);
						redirect($site_url);
			}
		}
	}
	function reg(){
		$this->load->model('distribute/staff_model');
		$saler_info = $this->staff_model->saler_info($this->openid,$this->inter_id);
		$this->load->model('hotel/Hotel_model');
		if($this->inter_id=='a445223616'){   //云盟的只显示有效的酒店,暂时写死
			$this->datas ['hotels'] = $this->Hotel_model->get_all_hotels ( $this->inter_id,1 );
		}else{
			$this->datas ['hotels'] = $this->Hotel_model->get_all_hotels ( $this->inter_id );
		}
		if($saler_info && $saler_info['status'] == 2){
			redirect(site_url('distribute/dis_v1/mine').'?id='.$this->inter_id);
		}elseif($saler_info && $saler_info['status'] != 2){
			redirect(site_url('distribute/dis_v1/processing').'?id='.$this->inter_id);
		}else{
			$this->load->model('distribute/distribute_model');
			$this->datas['depts'] = $this->distribute_model->get_departments($this->inter_id);
			$this->load->view(self::$theme.'header',$this->datas);
			$this->load->view(self::$theme.'new_saler');
		}
	}

	function send_verify_sms(){
		$cellphone = $this->input->post('cellphone');
		/*
		 $this->load->library('session');
		 $session= $this->session->get_userdata();

		 if(!empty($session['reg_sales'])){
		 $smsInfo = $session['reg_sales'];
		 $lastTime = $smsInfo['send_time'];
		 if( (time() - $lastTime <60)){
		 echo json_encode(array('errmsg'=>'failed','message'=>'发送验证码次数过于频繁'));
		 exit;
		 }
		 }
		 */
		if(empty($cellphone)){
			echo json_encode(array('errmsg'=>'failed','message'=>'手机为空'));
			exit;
		}
		//        $num = '';
		//        for($i=0; $i<6 ; $i++){
		//            $num.= rand(0,9);
		//        }
		$num = mt_rand(100000, 999999);
		$this->session->set_userdata('sms', $num);
		$this->load->model('member/Sms','sms');
		$this->sms->setLog();
		$templateId = 60225;
		$result = $this->sms->Sendsms( $cellphone ,array($num) , $templateId );
		echo json_encode(array('errmsg'=>'ok','message'=>$result['msg']));

	}


	function do_reg(){
		$this->load->model('distribute/staff_model');

		/*
		 *短信验证
		 */
		//         $message = $this->input->post('verifyMsg');
		//         if(empty($message)){
		//             echo json_encode(array('errmsg'=>'faild','message'=>'请输入手机验证码'));
		//             exit;
		//         }else{
		//             if($this->session->userdata('sms') != $message){
		//                 echo json_encode(array('errmsg'=>'faild','message'=>'验证码不正确'));
		//                 exit;
		//             }
		//         }

		if($this->staff_model->save_register()){
			echo json_encode(array('errmsg'=>'ok','message'=>'信息提交成功'));
		}else{
			echo json_encode(array('errmsg'=>'faild','message'=>'信息提交失败'));
		}
	}
	function processing(){
		$this->load->model ( 'distribute/staff_model' );
		$saler_info = $this->staff_model->saler_info ( $this->openid, $this->inter_id );
		//var_dump($saler_info);
		if ($saler_info) {
			if ($saler_info && ($saler_info ['status'] == 2 || $saler_info ['status'] == 4)) {
				redirect ( site_url ( 'distribute/dis_v1/mine' ) . '?id=' . $this->inter_id );
			} else {
				$datas ['status'] = '';
				if ($saler_info ['status'] == 1) {
					$this->datas ['status'] = 'processing';
				} elseif ($saler_info ['status'] == 2) {
					$this->datas ['status'] = 'complete';
				} elseif ($saler_info ['status'] == 3) {
					$this->datas ['status'] = 'faild';
				}
				$this->load->view ( self::$theme . 'header', $this->datas );
				$this->load->view ( self::$theme . 'processing' );
			}
		} else {
			redirect ( site_url ( 'distribute/dis_v1/reg' ) . '?id=' . $this->inter_id );
		}
	}
	public function getSignCard($card_id,$app_secret,$code=''){
		$timestamp = time();
		$signature = new Signature();
		$signature->add_data( $timestamp );
		$signature->add_data( $app_secret );
		if($code)
			$signature->add_data( $code );
			$signature->add_data( $card_id );
			return array('signature'=>$signature->get_signature(),'timestamp'=>$timestamp);
	}
	public function return_single_card(){
		$this->load->model('publics');
		$this->load->model('access_token_model');
		$public=$this->publics->get_public_by_inter_id($this->inter_id);
		// $card_id=$this->input->post('card_id',true);
		$card_outid=$this->input->post('card_outid',true);
		$info=$this->distribute_model->get_saler_by_openid($this->inter_id,$this->openid);
		if($card_outid&&$info){
			// $saler=$this->distribute_model->get_saler($this->inter_id,$this->saler,0);
			// if($saler){
			$signCards=$this->db->get_where('wx_cards',array('inter_id'=>$this->inter_id,'openid'=>$this->openid,'card_outid'=>$card_outid))->result();
			if($signCards){
				$data=array();
				$data['s']=1;
				$data['signPackage']=$this->access_token_model->getSignPackage($this->inter_id,$_SERVER['HTTP_REFERER']);
				$data['signCards']=array();
				foreach($signCards as $s){
					if($s->status==1){
						$tmp=array();
						$tmp['cardId']=$s->card_id;
						$p=$this->getSignCard($tmp['cardId'],$public['app_secret'],$s->card_code);
						$tmp['cardExt']=json_encode(array(
								'timestamp'=>$p['timestamp'],
								'signature'=>$p['signature'],
								'outer_id'=>$s->card_outid,
								'code'=>$s->card_code
						));
						$data['signCards'][]=$tmp;
					}
				}
				if(empty($data['signCards'])){
					$data=array('s'=>0,'err'=>'no_card_left');
				}
			}
			else{
				// $outid=time().str_pad(mt_rand(0,9999),4,'0',STR_PAD_LEFT);
				$sales_card=$this->db->get_where('wx_card_type',array('inter_id'=>$this->inter_id,'out_id'=>$card_outid))->result();
				if($sales_card){
					$t=time();
					foreach($sales_card as $sc){
						$tmp=array();
						$tmp['inter_id']=$this->inter_id;
						$tmp['openid']=$this->openid;
						$tmp['local_id']=str_replace('-','o','lc'.substr($this->openid,-2,2)).substr($t,4).str_pad(mt_rand(0,99),2,'0',STR_PAD_LEFT);
						$tmp['status']=1;
						if($sc->valid_date)
							$tmp['invalid_time']=$sc->valid_date;
							else if($sc->valid_seconds)
								$tmp['invalid_time']=time()+$sc->valid_seconds;
								$tmp['create_time']=time();
								$tmp['amount']=$sc->amount;
								$tmp['from_type']='to_saler';
								$tmp['card_id']=$sc->card_id;
								$tmp['card_code']=$this->create_card_code();
								$tmp['card_outid']=$card_outid;//分销人员渠道
								$datas[]=$tmp;
								$t++;
					}
					if($datas)
						$this->db->insert_batch ( 'wx_cards', $datas );
							
						$data = array ();
						$data ['s'] = 1;
						$data ['signPackage'] = $this->access_token_model->getSignPackage ( $this->inter_id, $_SERVER ['HTTP_REFERER'] );
						$data ['signCards'] = array ();
						foreach ( $datas as $d ) {
							$tmp = array ();
							$tmp ['cardId'] = $d ['card_id'];
							$p = $this->getSignCard ( $tmp ['cardId'], $public ['app_secret'], $d ['card_code'] );
							$tmp ['cardExt'] = json_encode ( array (
									'timestamp' => $p ['timestamp'],
									'signature' => $p ['signature'],
									'outer_id' => $d ['card_outid'],
									'code' => $d ['card_code']
							) );
							$data ['signCards'] [] = $tmp;
						}
				} else
					$data = array (
							's' => 0,
							'err' => 'no_card_type'
					);
			}
			// }
		} else {
			$data = array (
					's' => 0,
					'err' => 'can_not_get'
			);
		}
		echo json_encode ( $data );
	}
	function create_card_code() {
		return str_pad ( time () + mt_rand ( 0, 999 ), 13, mt_rand ( 0, 999 ), STR_PAD_RIGHT );
	}
	function give_you() {
		$data = array ();
		$data ['s'] = 1;
		$data ['signPackage'] = $this->access_token_model->getSignPackage($this->inter_id);
		$data['signCards']=array();
		$this->load->model('publics');
		$public=$this->publics->get_public_by_inter_id($this->inter_id);
		$card_id='po89wt7OrOIUByMND_NzeSBTaFbw';
		for($i=0;$i<6;$i++){
			$tmp=array();
			$tmp['cardId']=$card_id;
			$code=$this->create_card_code();
			$p=$this->getSignCard($tmp['cardId'],$public['app_secret'],$code);
			$tmp['cardExt']=json_encode(array(
					'timestamp'=>$p['timestamp'],
					'signature'=>$p['signature'],
					'code'=>$code
			));
			$data['signCards'][]=$tmp;
		}
		$this->db->insert('weixin_text',array('content'=>'give_you_'.$this->inter_id.json_encode($data['signCards']).'_'.$this->openid));
		$this->vars('signPackage',$data['signPackage']);
		$this->vars('signCard',json_encode($data['signCards']));
		$this->display('socard',1);
	}
	/*************************** 2015-11-14 ******************************/
	function mine(){
		$this->load->model('distribute/staff_model');
		$this->load->model('wx/publics_model');
		$saler_info = $this->staff_model->get_my_info($this->openid,$this->inter_id);
		$saler_base = $this->staff_model->get_my_base_info_openid($this->inter_id,$this->openid);
		if(!isset($saler_info['id']) || ($saler_base['status'] != 2 && $saler_base['status'] != 4)){
			redirect ( site_url ( 'distribute/dis_v1/reg' ) . '?id=' . $this->inter_id );
		}
		//把短连接二维码转换成微信参数二维码
		if(stristr($saler_info['url'],'iwide.cn') !== FALSE){
			$saler_info['url'] = $this->staff_model->update_qrcode($saler_info['id'],$this->inter_id);
		}
		$this->load->model('distribute/Idistribute_model','idistribute');
		$this->datas['total_amount']    = $this->idistribute->get_saler_grades_by_date($this->inter_id,$saler_info['id'],null,'ALL');
		$this->datas['today_amount']    = $this->idistribute->get_saler_grades_by_date($this->inter_id,$saler_info['id'],date('Y-m-d'),'ALL');
		$this->datas['yestoday_amount'] = $this->idistribute->get_saler_grades_by_date($this->inter_id,$saler_info['id'],date("Y-m-d",strtotime("-1 day")),'ALL');
		$this->datas['new_msg_count']   = $this->idistribute->get_my_new_msg_count($this->openid);
		$this->datas['saler_details']   = $saler_info;
		$this->datas['publics']         = $this->publics_model->get_public_by_id($this->inter_id);
		$this->datas['inter_id']        = $this->inter_id;

        $this->load->model('membervip/admin/Public_model','pum');
        $this->datas['deposit_card_show'] = false;
        $this->datas['balance_show'] = false;
        /*购买会员卡*/
        $deposit_card = $this->pum->get_info(array('inter_id'=>$this->inter_id,"is_distribution"=>'t','is_active'=>'t','deposit_type'=>'g'),'deposit_card');
        if(!empty($deposit_card)){
            $this->datas['deposit_card_show'] = true;
            $this->datas['deposit_card_url']  = base_url("index.php/membervip/center/qrcodecon?id=".$this->inter_id."&data=").urlencode(base_url("index.php/membervip/depositcard?id={$this->inter_id}&salesId=".$saler_info['id']));

        }
        /* end 购买会员卡*/
        /*购买储值*/
        $deposit_card = $this->pum->get_info(array('inter_id'=>$this->inter_id,"is_distribution"=>'t','is_active'=>'t','deposit_type'=>'c'),'deposit_card');
        if(!empty($deposit_card)){
            $this->datas['balance_show'] = true;
            $this->datas['balance_url']  = base_url("index.php/membervip/center/qrcodecon?id=".$this->inter_id."&data=").urlencode(base_url("index.php/membervip/depositcard/buydeposit?id={$this->inter_id}&salesId=".$saler_info['id']));

        }

        /* end 购买储值*/


        /*微信会员卡*/
        $this->load->model('membervip/front/Wechat_membercard_model',"wechatMembercard");
        $wechatCard = $this->wechatMembercard->get_config($this->inter_id);
        if(!empty($wechatCard)){
            $card_id = $wechatCard['card_id'];
            $wechatCardQrcJson = $this->wechatMembercard->get_distribution_qrc($this->inter_id,$card_id,$this->openid,$saler_info['id'] );
            $wechatCardQrc = json_decode($wechatCardQrcJson,true);
            if($wechatCardQrc && isset($wechatCardQrc['errcode'])){
                if($wechatCardQrc['errcode'] == 0){ //正常获取
                    $this->datas['wechat_card_qrcode'] =  $wechatCardQrc['show_qrcode_url'];
                }else{ //获取状态有误

                }
            }else{ //微信返回有误

            }
        }
        /* end 微信会员卡*/

		//http://【客户域名】.iwide.cn/index.php/soma/package/index?id=【客户interid】&saler=【分销员id】
		$this->datas['mall_qrcode'] = '';
		if($this->check_remote_file_exists('http://file.iwide.cn/public/qrcode/MALL'.$this->inter_id.$saler_info['id'].'.jpg')){
			$this->datas['mall_qrcode'] = 'http://file.iwide.cn/public/qrcode/MALL'.$this->inter_id.$saler_info['id'].'.jpg';
		}else{
			$url = site_url('soma/package/index').'?id='.$this->inter_id.'&saler='.$saler_info['id'];
			$this->load->model('wx/qrcode_model');
			$this->qrcode_model->grnerate_qrcode_ftp($url,'MALL'.$this->inter_id.$saler_info['id']);
			$this->datas['mall_qrcode'] = 'http://file.iwide.cn/public/qrcode/MALL'.$this->inter_id.$saler_info['id'].'.jpg';
		}
		$this->load->view(self::$theme . 'header',$this->datas );
		$this->load->view(self::$theme . 'mine');
	}

	function my_fans(){
		// $this->load->model('distribute/staff_model');
		// $this->datas['saler_details'] = $this->staff_model->get_fans_recs_all($this->session->userdata($this->inter_id.'openid'),$this->inter_id)->result();
		$this->load->model ( 'distribute/fans_model' );
		$this->datas ['saler_details'] = $this->fans_model->get_my_fans_by_saler_id ( $this->inter_id, $this->session->userdata ( $this->inter_id . 'saler' ), 0, 2000, 'event_time', 'DESC' );
		if (! empty ( $this->datas ['saler_details'] )) {
			$this->load->model('hotel/hotel_model');
			$hotels = $this->hotel_model->get_hotel_hash(array('inter_id'=>$this->inter_id),array('hotel_id','name'));
			$khotel = array();
			foreach ($hotels as $item){
				$khotel[$item['hotel_id']] = $item['name'];
			}
			$temp_arr = array ();
			$this->datas ['saler_details'] = array_reverse($this->datas ['saler_details']);
			foreach ( $this->datas ['saler_details'] as $fans ) {
				$temp_arr [] = array (
						'nickname'        => $fans ['nickname'],
						'headimgurl'      => $fans ['headimgurl'],
						'total_amount'    => $fans ['amount'],
						'event_time'      => $fans ['event_time'],
						'last_order_date' => $fans ['last_order_date'],
						'hotel_name'      => ($fans ['hotel_id'] == -1 || !isset($khotel[$fans ['hotel_id']])) ? '公众号渠道' : $khotel[$fans ['hotel_id']],
						'id'              => $fans ['id']
				);
			}
			$this->datas ['saler_details'] = json_encode ( $temp_arr, JSON_NUMERIC_CHECK );
		} else {
			$this->datas ['saler_details'] = '{}';
		}
		$this->load->view ( self::$theme . 'header', $this->datas );
		$this->load->view ( self::$theme . 'my_fans' );
	}
	function to_pocket(){
		$this->load->model('distribute/staff_model');
		$this->datas['saler_details'] = $this->staff_model->get_my_info($this->session->userdata($this->inter_id.'openid'),$this->inter_id);
		$this->load->view(self::$theme . 'header',$this->datas );
		$this->load->view(self::$theme . 'to_porket');
	}
	function pocket_success(){
		// 		$this->load->model('dist/distri_model');
		// 		$this->datas['saler_details'] = $this->distri_model->get_my_info($this->session->userdata($this->inter_id.'openid'),$this->inter_id);
		$this->load->view('wap/distribute/header',$this->datas);
		$this->load->view('wap/distribute/pocket_success');
	}
	function fans_blogs(){
		$this->load->model('distribute/staff_model');
		$this->load->model('distribute/grades_model');
		$this->load->model('hotel/hotel_model');
		$this->load->model('distribute/fans_model');
		$fans_id = $this->input->get('fid');
		$this->datas['fans_details'] = $this->fans_model->get_fans_info_by_id($this->inter_id,$fans_id);
		$this->datas['grades_types'] = $this->grades_model->grade_types;
		$this->datas['o_sts']        = $this->grades_model->order_status;
		$this->datas['logs_details'] = $this->grades_model->get_saler_fans_grades_logs($this->inter_id,$this->session->userdata($this->inter_id.'saler'),$fans_id);
		$hotels = $this->hotel_model->get_hotel_hash(array('inter_id'=>$this->inter_id),array('hotel_id','name'));
		$khotel = array();
		foreach ($hotels as $item){
			$khotel[$item['hotel_id']] = $item['name'];
		}
		$this->datas['hotels'] = $khotel;
		$deliver_config = $this->grades_model->get_deliver_setting($this->inter_id);
		if($deliver_config){
			if($deliver_config->mode == 1){
				$this->datas['deliver_config'] = TRUE;
			}else{
				$this->datas['deliver_config'] = FALSE;
			}
		}else{
			$this->datas['deliver_config'] = FALSE;
		}

		$this->load->view(self::$theme . 'header',$this->datas );
		$this->load->view(self::$theme . 'fans_blogs');
	}
	function new_drw(){
		$amount = $this->input->get('amount',true);
		if($amount > 0){
			$this->load->model('dist/distri_model');
			$my_info = $this->distri_model->get_my_info($this->session->userdata($this->inter_id.'openid'),$this->inter_id);
			if($this->distri_model->new_withdraw($my_info['id'],$this->session->userdata($this->inter_id.'openid'),$this->inter_id,$amount)){
				echo json_encode(array('errmsg'=>'ok'));
			}else{
				echo json_encode(array('errmsg'=>'faild'));
			}
		}else{
			echo json_encode(array('errmsg'=>'faild:AMOUNT_LESS_THEN_ZERO'));
		}
	}
	function drw_logs(){
		$this->load->model('distribute/staff_model');
		$this->datas['logs'] = $this->staff_model->my_drw_logs($this->openid,$this->inter_id)->result();
		$this->datas['total_fee'] = $this->staff_model->get_all_income($this->openid,$this->inter_id);
		$this->datas['my_info']   = $this->staff_model->get_my_info($this->openid,$this->inter_id);
		$this->load->view(self::$theme . 'header',$this->datas );

		$this->load->view(self::$theme . 'drw_logs');
	}
	function incomes(){
		// 		$this->load->model('distribute/staff_model');
		// 		$this->datas['logs'] = $this->staff_model->get_all_room_rec_info($this->openid,$this->inter_id)->result();


		$this->load->model('distribute/grades_model');
		$this->datas['grades'] = $this->grades_model->get_saler_grades_by_date($this->inter_id,$this->session->userdata($this->inter_id.'saler'),null,$type='ALL',null);
		$this->datas['send']   = $this->grades_model->get_saler_grades_by_date($this->inter_id,$this->session->userdata($this->inter_id.'saler'),null,$type='OLD',null);
		$this->datas['unsend'] = $this->grades_model->get_saler_grades_by_date($this->inter_id,$this->session->userdata($this->inter_id.'saler'),null,$type='NEW',null);
		// 		$this->datas['grades_pre'] = $this->grades_model->get_saler_grades_by_date($this->inter_id,$this->session->userdata($this->inter_id.'saler'),date('Y-m',strtotime('-1 month',time())),$type='ALL',$date_type='MONTH');
		// 		$this->datas['send_pre']   = $this->grades_model->get_saler_grades_by_date($this->inter_id,$this->session->userdata($this->inter_id.'saler'),date('Y-m',strtotime('-1 month',time())),$type='OLD',$date_type='MONTH');

		$this->datas['grades_times'] = $this->grades_model->get_saler_grades_times_by_date($this->inter_id,$this->session->userdata($this->inter_id.'saler'),null,$type='ALL',null);
		$this->datas['send_times']   = $this->grades_model->get_saler_grades_times_by_date($this->inter_id,$this->session->userdata($this->inter_id.'saler'),null,$type='OLD',null);
		$this->datas['unsend_times'] = $this->grades_model->get_saler_grades_times_by_date($this->inter_id,$this->session->userdata($this->inter_id.'saler'),null,$type='NEW',null);
		// 		$this->datas['grades_pre_times'] = $this->grades_model->get_saler_grades_times_by_date($this->inter_id,$this->session->userdata($this->inter_id.'saler'),date('Y-m',strtotime('-1 month',time())),$type='ALL',$date_type='MONTH');
		// 		$this->datas['send_pre_times']   = $this->grades_model->get_saler_grades_times_by_date($this->inter_id,$this->session->userdata($this->inter_id.'saler'),date('Y-m',strtotime('-1 month',time())),$type='OLD',$date_type='MONTH');

		$this->datas['grades_types'] = $this->grades_model->grade_types;
		$this->datas['grade_status'] = $this->grades_model->grade_status;
		$grade_type = 'ALL';
		if($this->input->get('g_typ') && in_array($this->input->get('g_typ'), array('ALL','PRE','OLD','NEW')))
			$grade_type = $this->input->get('g_typ');
			$this->datas['g_typ'] = $grade_type;
			$this->datas['g_sts'] = $this->grades_model->grade_status;
			$this->datas['o_sts'] = $this->grades_model->order_status;
			$this->datas['logs'] = $this->grades_model->get_saler_grades_logs_by_month($this->inter_id,$this->session->userdata($this->inter_id.'saler'),$grade_type,NULL,0,100);
			$this->load->model('hotel/hotel_model');
			$hotels = $this->hotel_model->get_hotel_hash(array('inter_id'=>$this->inter_id),array('hotel_id','name'));
			$khotel = array();
			foreach ($hotels as $item){
				$khotel[$item['hotel_id']] = $item['name'];
			}
			$this->datas['hotels'] = $khotel;

			$deliver_config = $this->grades_model->get_deliver_setting($this->inter_id);
			if($deliver_config){
				if($deliver_config->mode == 1){
					$this->datas['deliver_config'] = TRUE;
				}else{
					$this->datas['deliver_config'] = FALSE;
				}
			}else{
				$this->datas['deliver_config'] = FALSE;
			}
			$this->load->view(self::$theme . 'header',$this->datas );
			$this->load->view(self::$theme . 'incomes');
	}
	// 	function slogs(){
	// 		$this->load->model('distribute/grades_model');
	// 		$this->datas['grades'] = $this->grades_model->get_saler_grades_by_date($this->inter_id,$this->session->userdata($this->inter_id.'saler'),null,$type='ALL',null);
	// 		$this->datas['send']   = $this->grades_model->get_saler_grades_by_date($this->inter_id,$this->session->userdata($this->inter_id.'saler'),null,$type='OLD',null);

	// 		$this->datas['grades_times'] = $this->grades_model->get_saler_grades_times_by_date($this->inter_id,$this->session->userdata($this->inter_id.'saler'),null,$type='ALL',null);
	// 		$this->datas['send_times']   = $this->grades_model->get_saler_grades_times_by_date($this->inter_id,$this->session->userdata($this->inter_id.'saler'),null,$type='OLD',null);

	// 		$this->datas['grades_types'] = $this->grades_model->grade_types;
	// 		$this->datas['grade_status'] = $this->grades_model->grade_status;
	// 		$grade_type = 'ALL';
	// 		if($this->input->get('g_typ') && in_array($this->input->get('g_typ'), array('ALL','PRE','OLD','NEW')))
	// 			$grade_type = $this->input->get('g_typ');
	// 		$this->datas['g_typ'] = $grade_type;
	// 		$this->datas['g_sts'] = $this->grades_model->grade_status;
	// 		$this->datas['o_sts'] = $this->grades_model->order_status;
	// 		$this->datas['logs'] = $this->grades_model->get_saler_send_logs_by_batch_no($this->inter_id,$this->input->get('pn'),$this->session->userdata($this->inter_id.'saler'));
	// 		$this->load->model('hotel/hotel_model');
	// 		$hotels = $this->hotel_model->get_hotel_hash(array('inter_id'=>$this->inter_id),array('hotel_id','name'));
	// 		$khotel = array();
	// 		foreach ($hotels as $item){
	// 			$khotel[$item['hotel_id']] = $item['name'];
	// 		}
	// 		$this->datas['hotels'] = $khotel;
	// 		$this->load->view(self::$theme . 'header' );
	// 		$this->load->view(self::$theme . 'slogs',$this->datas);
	// 	}

	function ranking(){
		$this->load->model('distribute/staff_model');
		// 		$this->datas['saler_details'] = $this->distri_model->get_my_info($this->session->userdata($this->inter_id.'openid'),$this->inter_id);
		// 		$this->datas['saler_details']  = $this->staff_model->get_my_info($this->openid,$this->inter_id);
		// 		$type='ALL';
		// 		$carr = array('ALL','YEAR','MONTH','DAY');
		// 		$in_str = strtoupper($this->input->get('c'));
		// 		if($in_str && in_array($in_str, $carr)){
		// 			$type=$in_str;
		// 		}
		// 		$this->datas['my_rank']  = $this->staff_model->get_single_user_ranking($this->session->userdata ( 'inter_id' ),$type,$this->session->userdata($this->inter_id.'saler'))->row_array();
		// 		$this->datas['rankings'] = $this->staff_model->get_user_ranking($this->session->userdata ( 'inter_id' ),$type,50)->result();
		$this->load->view(self::$theme . 'header',$this->datas );
		$this->load->view(self::$theme . 'ranking');
	}
	function fans_ranking(){
		$this->load->model('distribute/staff_model');
		$this->datas['saler_details']  = $this->staff_model->get_my_info($this->openid,$this->inter_id);
		// $res = $this->distri_model->get_user_rank($this->session->userdata ( 'inter_id' ),$this->datas['saler_details']['id']);echo $this->db->last_query();var_dump($res);exit;
		$type='ALL';
		$carr = array('ALL','YEAR','MONTH','DAY');
		$in_str = strtoupper($this->input->get('c'));
		if($in_str && in_array($in_str, $carr)){
			$type=$in_str;
		}
		$this->datas['my_rank']  = $this->staff_model->get_user_rank($this->session->userdata ( 'inter_id' ),$type,$this->datas['saler_details']['id']);
		// 		echo $this->db->last_query();die;
		$this->datas['rankings'] = $this->staff_model->get_fans_ranking($this->session->userdata ( 'inter_id' ),$type,50)->result_array();
		$this->load->view(self::$theme . 'header' ,$this->datas);
		$this->load->view(self::$theme . 'ranking_');
	}
	function rank_asy(){
		$cache_inter_id = array('a421641095','a455510007','a441098524','a472731996','a452223043','a449675133');//配置需要緩存的inter_id 目前配置讀緩存的有：月收益，總收益，總粉絲 自动脚本跑数据也要加一次 situguanchen 2017-0418
		$type  = 'AMT';
		$range = 'DAY';
		if($this->input->get('typ') && in_array(strtoupper($this->input->get('typ')),array('AMT','FANS'))){
			$type = strtoupper($this->input->get('typ'));
		}
		if($this->input->get('range') && in_array(strtoupper($this->input->get('range')),array('ALL','YEAR','MONTH','DAY'))){
			$range = strtoupper($this->input->get('range'));
		}
		$this->load->model('distribute/staff_model');
		// 		echo $type;echo '<br/>';
		// 		echo $range;echo '<br/>';exit();
		if($type == 'FANS'){
			if($range == 'ALL' && in_array($this->inter_id,$cache_inter_id)){//總的粉絲
				$fans_data = $this->staff_model->i_get_all_fans_rank($this->inter_id,$range,$this->session->userdata($this->inter_id.'saler'));
				/*$my_rank  = $this->staff_model->i_get_user_rank($this->inter_id,$range,$this->session->userdata($this->inter_id.'saler'));
				$rankings = $this->staff_model->i_get_fans_ranking($this->inter_id,$range,50);*/
				$my_rank = $fans_data['mine'];
				$rankings = $fans_data['limit50'];
			}else{
				$my_rank  = $this->staff_model->get_user_rank($this->inter_id,$range,$this->session->userdata($this->inter_id.'saler'));
				$rankings = $this->staff_model->get_fans_ranking($this->inter_id,$range,50)->result();
			}
		}else{
			if(($range == 'ALL' || $range == 'MONTH') && in_array($this->inter_id,$cache_inter_id)){//總收入，月收入
				$income_data = $this->staff_model->i_get_all_incomes_rank($this->inter_id,$range,$this->session->userdata($this->inter_id.'saler'));
				/*$my_rank  = $this->staff_model->i_get_single_user_ranking($this->inter_id,$range,$this->session->userdata($this->inter_id.'saler'));
				$rankings = $this->staff_model->i_get_user_ranking($this->inter_id,$range,50);*/
				$my_rank = $income_data['mine'];
				$rankings = $income_data['limit50'];
			}else{
				$my_rank  = $this->staff_model->get_single_user_ranking($this->inter_id,$range,$this->session->userdata($this->inter_id.'saler'))->row_array();
				$rankings = $this->staff_model->get_user_ranking($this->inter_id,$range,50)->result();
			}
			
		}
		echo json_encode(array('my_rank'=>$my_rank,'ranking'=>$rankings),JSON_UNESCAPED_UNICODE);
		exit;
	}

	function renew(){
		$code = $this->input->post('cd');
		if($code){
			$this->load->model('dist/distri_model');
			if($this->distri_model->renew_saler($this->inter_id,$code,$this->session->userdata($this->inter_id.'openid'))){
				echo json_encode(array('errmsg'=>'ok'));
			}else{
				echo json_encode(array('errmsg'=>'faild'));
			}
		}else{
			$this->load->view('wap/distribute/header',$this->datas);
			$this->load->view('wap/distribute/renew');
		}
	}

	/**
	 * 审核没通过的人员重新登记入口
	 */
	function edit(){
		$this->load->model ( 'distribute/staff_model' );
		$saler_info = $this->staff_model->saler_info ( $this->openid, $this->inter_id );
		if ($saler_info) {
			if ($saler_info && $saler_info ['status'] == 2) {
				redirect ( site_url ( 'distribute/dis_v1/mine' ) . '?id=' . $this->inter_id );
			}elseif($saler_info && $saler_info ['status'] == 3){
				$this->load->model('hotel/Hotel_model');
				$this->datas ['hotels'] = $this->Hotel_model->get_all_hotels ( $this->inter_id );
				$this->datas['saler'] = $saler_info;
				$this->load->model('distribute/distribute_model');
				$this->datas['depts'] = $this->distribute_model->get_departments($this->inter_id);
				$this->load->view(self::$theme.'header',$this->datas);
				$this->load->view(self::$theme.'new_saler');
			} else {
				redirect (site_url('distribute/dis_v1/reg'));
			}
		} else {
			redirect ( site_url ( 'distribute/dis_v1/reg' ) . '?id=' . $this->inter_id );
		}
	}
	function file2base64(){
		try {
			//            echo base64_encode(file_get_contents( $this->input->get('url')));
			echo base64_encode($this->curl_file_get_contents( $this->input->get('url')));
		}catch (Exception $e){
			echo 'error';
		}
	}

	function curl_file_get_contents($durl){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $durl);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_USERAGENT, '');
		curl_setopt($ch, CURLOPT_REFERER,'');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$r = curl_exec($ch);
		curl_close($ch);
		return $r;
	}


	function base64_2file(){
		try {
			$data = $this->input->post('url');
			$file_name = $this->input->get('id').'_'.$this->input->get('qid').'.png';
			file_put_contents('./'.$file_name,base64_decode($data));

			// 			$this->ftp->mkdir('/public_html/foo/bar/', 0755);

			$this->ftp= $this->_ftp_server('prod');
			$base_path= 'media/distribute/';
			$to_file = $this->ftp->floder. FD_PUBLIC. '/'. $base_path;
			$up_path = realpath('./').'/'.$file_name;
			$this->ftp->upload($up_path, $to_file.$file_name, 'binary', 0775);
			$this->ftp->close();

			@unlink($file_name);
			$upload_url= $this->ftp->weburl. '/'. FD_PUBLIC. '/media/distribute/'.$file_name;

			//保存上传完之后的URL
			echo $upload_url;

		}catch (Exception $e){
			echo 'error';
		}
	}

	function msgs(){
		// 		$this->load->model('distribute/IDistribute_model','idistribute');
		// 		$msg_typ = null;
		// 		if($this->input->get('mstyp')== 'qa'){
		// 			$msg_typ = 2;
		// 		}
		// 		$this->datas['msgs'] = $this->idistribute->get_my_notices_by_category($this->openid,$this->inter_id,$msg_typ);
		$this->datas['inter_id'] = $this->inter_id;
		$this->load->view(self::$theme . 'header',$this->datas );
		$this->load->view(self::$theme . 'notices');
	}
	function msgs_asy(){

		$this->load->model('distribute/IDistribute_model','idistribute');
		$msg_typ = null;
		if($this->input->get('mstyp') == 'qa'){
			$msg_typ = 2;
		}
		echo json_encode($this->idistribute->get_my_notices_by_category($this->openid,$this->inter_id,$msg_typ,0,1000));
	}
	function msg_det(){
		$this->load->model('distribute/IDistribute_model','idistribute');
		$this->datas['msg']      = $this->idistribute->get_single_notice($this->input->get('mid'),$this->inter_id,$this->openid);
		if($this->datas['msg']->flag == 0){
			$this->idistribute->do_read_msg($this->datas['msg']->eid);
		}
		$this->datas['msg_typs'] = array(0=>'收益发放',1=>'系统消息');
		$this->datas['inter_id'] = $this->inter_id;
		$this->load->view(self::$theme . 'header',$this->datas );
		$this->load->view(self::$theme . 'notices_det');
	}
	function get_saler_base_info(){
		$this->load->model('distribute/staff_model');
		return $this->staff_model->get_my_base_info_openid($this->inter_id,$this->openid);
	}
	function bind(){
		$this->load->model('distribute/staff_model');
		$saler_info = $this->staff_model->saler_info($this->openid,$this->inter_id);
		if($saler_info && $saler_info['status'] == 2){
			redirect(site_url('distribute/dis_v1/mine').'?id='.$this->inter_id);
		}elseif($saler_info && $saler_info['status'] != 2){
			redirect(site_url('distribute/dis_v1/processing').'?id='.$this->inter_id);
		}else{
			$this->load->model('distribute/fans_model');
			$query = $this->fans_model->get_unbind_staffs($this->inter_id);
			$this->datas['query'] = $query->result();
			$this->load->view(self::$theme . 'header',$this->datas );
			$this->load->view(self::$theme . 'unbindstaffs');
		}
	}
	function dobind(){
		$qrcode = $this->input->get('qid');
		$this->db->where(array('inter_id'=>$this->inter_id,'qrcode_id'=>$qrcode,'is_distributed'=>1));
		echo $this->db->update('hotel_staff',array('status'=>2,'openid'=>$this->openid));
	}
	/**
	 * @param unknown $url
	 * @return boolean
	 */
	function check_remote_file_exists($url) {
		$curl = curl_init($url); // 不取回数据
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET'); // 发送请求
		$result = curl_exec($curl);
		$found = false; // 如果请求没有发送失败
		if ($result !== false) {

			/** 再检查http响应码是否为200 */
			$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			if ($statusCode == 200) {
				$found = true;
			}
		}
		curl_close($curl);

		return $found;
	}
	public function s(){
		$this->load->model('distribute/welfare_auth_model');
		$typ = 1;
		if($this->input->get_post('t'))
			$typ = $this->input->get_post('t');
			$id = $this->uri->segment(4);
			$params = array('typ'=>$typ);
			log_message('error', 'DIS_V1 | s | POSTS |'.json_encode($this->input->post()));
			if (! $this->input->post () && $id && $this->welfare_auth_model->is_openid_valid ( $this->inter_id, $this->openid, $typ )) {
				$params ['token'] = $id;
				$params ['type'] = 'index';
			} else if (! $this->input->post () && ! $id) {
				$params ['type'] = 'cancel';
			} else if ($this->input->post () && $this->welfare_auth_model->is_openid_valid ( $this->inter_id, $this->openid, $typ )) {
				$id = $this->input->post ( 'token' );
				if (! $id) {
					$params ['type'] = 'failed';
					$params ['errmsg'] = '授权失败';
				}
				$this->load->model ( 'distribute/welfare_auth_model' );
				if ($this->welfare_auth_model->_do_auth ( $this->inter_id, $this->openid, $this->session->userdata ( $this->inter_id . 'saler' ), $id, date ( 'Y-m-d H:i:s', time () + 1800 ) ) > 0) {
					$params ['type'] = 'success';
				} else {
					$params ['type'] = 'failed';
					$params ['errmsg'] = '授权失败';
				}
			} else {
				$params ['type'] = 'cancel';
				$params ['errmsg'] = '非法管理员';
			}
			$this->load->view('s/auth',$params);
	}
	public function getSignPackage($url='') {
		$this->load->helper('common');
		$this->load->model('wx/publics_model');
		$this->load->model('wx/access_token_model');
		$jsapiTicket = $this->access_token_model->get_api_ticket($this->session->userdata('inter_id'));
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		if(!$url)
			$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$timestamp = time();
			$nonceStr = createNonceStr();
			$public = $this->publics_model->get_public_by_id($this->session->userdata('inter_id'));
			// 这里参数的顺序要按照 key 值 ASCII 码升序排序
			$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

			$signature = sha1($string);

			$signPackage = array(
					"appId"     => $public['app_id'],
					"nonceStr"  => $nonceStr,
					"timestamp" => $timestamp,
					"url"       => $url,
					"signature" => $signature,
					"rawString" => $string
			);
			return $signPackage;
	}
}

class Signature
{
	public function __construct()
	{
		$this->data = array();
	}
	public function add_data($str)
	{
		array_push($this->data, (string)$str);
	}
	public function get_signature()
	{
		sort( $this->data,SORT_LOCALE_STRING );
		$string = implode( $this->data );
		return sha1( $string );
	}
}