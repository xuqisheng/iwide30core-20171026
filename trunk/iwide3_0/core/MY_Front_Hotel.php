<?php
class MY_Front_Hotel extends MY_Front {
	public $inter_id;
	public $public;
	public $my_saler_id;
	public $member_info;
	public function __construct() {
		parent::__construct ();
		if (empty ( $this->public )) {
			$this->load->model ( 'wx/Publics_model' );
			$this->public = $this->Publics_model->get_public_by_id ( $this->inter_id );
		}
		require_once APPPATH . DIRECTORY_SEPARATOR . "libraries" . DIRECTORY_SEPARATOR . "Hotel" . DIRECTORY_SEPARATOR . "Hotel_base.php";
		require_once APPPATH . DIRECTORY_SEPARATOR . "libraries" . DIRECTORY_SEPARATOR . "Hotel" . DIRECTORY_SEPARATOR . "Hotel_const.php";

		$this->my_saler_id = $this->session->userdata ( $this->inter_id . $this->openid . '_h_saler' );
		$my_saler_expire = $this->session->userdata ( $this->inter_id . $this->openid . '_h_saler_rtime' );
		$seg = $this->module . '/' . $this->controller . '/' . $this->action;
		if (! isset ( $this->my_saler_id ) || Hotel_const::enums ( 'saler_redirect_url', NULL, $seg ) || $my_saler_expire<time()) {
			$this->load->model ( 'hotel/user/User_info_model' );
			$self_saler = $this->User_info_model->get_saler_info ( $this->inter_id, $this->openid, 'valid' );
			if ($self_saler && ! empty ( $self_saler ['qrcode_id'] )) {
				$this->my_saler_id = $self_saler ['qrcode_id'];
			} else {
				$this->my_saler_id = 0;
			}
			$this->session->set_userdata ( $this->inter_id . $this->openid . '_h_saler', $this->my_saler_id );
			$this->session->set_userdata ( $this->inter_id . $this->openid . '_h_saler_rtime', time()+180 );
		}
		if (! empty ( $this->my_saler_id )) {
			Hotel_base::$_basic_param ['own_saler'] = $this->my_saler_id;
		}
		$this->url_param = Hotel_base::inst ()->url_param ();
		$not_default=array(
				'a429262687',
				'a449664652',
				'a451037398',
				'a455510007',
				'a456970175',
				'a457062971',
				'a457946152',
				'a464177542',
				'a467780350',
				'a483407432'
		);
		if (! empty ( Hotel_base::$_basic_param ['saler_redirect'] ) && Hotel_const::enums ( 'saler_redirect_url', NULL, $seg ) && !in_array($this->inter_id, $not_default)) {
			redirect ( Hotel_base::inst ()->get_url ( $seg, $this->input->get () ) );
		}
		$this->link_saler_id=intval($this->input->get('lsaler'));
		$this->ori_saler_id=intval($this->input->get('osaler'));

		if($this->link_saler_id || $this->ori_saler_id){//分销保护期
			$this->load->model('distribute/Idistribute_model');
			if (isset($_SERVER['SERVER_SOFTWARE']) && $_SERVER['SERVER_SOFTWARE'] == 'nginx') {
			    $source = 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
			} else {
			    $source = 'http://' . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'];
			}
			if($this->ori_saler_id){
				$true_saler = $this->ori_saler_id;
			}else{
				$true_saler = $this->link_saler_id;
			}
			$this->Idistribute_model->save_saler_protection_info($this->inter_id, $this->openid, $source, $true_saler,'', 'hotel');
		}

		if ( Hotel_const::enums ( 'query_member_controller', NULL, $this->controller ) ) {
		    $member_session_key=$this->inter_id . $this->openid . '_memberinfo';
		    if ( Hotel_const::enums ( 'fresh_memberinfo_url', NULL, $seg ) ) {
		        $this->member_info = $this->get_member_info();
		        empty($this->member_info->mem_id) or $this->session->set_userdata ( $member_session_key,json_encode($this->member_info));
		    }else{
		        $this->member_info = $this->session->userdata ( $member_session_key);
		        $this->member_info=json_decode( $this->member_info);
		        $cur_login_status = $this->session->userdata ($this->inter_id . $this->openid . '_logined');
		        $cur_login_member = $this->session->userdata ($this->inter_id . $this->openid . '_member_info_id');
		        if (empty($this->member_info) || (isset($cur_login_status) && $cur_login_status != $this->member_info->logined) || $cur_login_member != $this->member_info->member_info_id){
		            $this->member_info = $this->get_member_info();
		            empty($this->member_info->mem_id) or $this->session->set_userdata ( $member_session_key,json_encode($this->member_info));
		        }
		    }
		}
	}
	public function get_member_info(){
	    $this->load->library ( 'PMS_Adapter', array (
	            'inter_id' => $this->inter_id,
	            'hotel_id' => 0
	    ), 'pub_pmsa' );
	    $member = $this->pub_pmsa->check_openid_member ( $this->inter_id, $this->openid, array (
	            'create' => TRUE,
	            'update' => TRUE
	    ) );
	    if (! empty ( $member ) && isset ( $member->mem_id )) {
	        return $member;
	    }else {
	        $member = new stdClass();
	        return $member;
	    }
	}
	function display($paras, $data, $skin = '', $extra_views = array(), $return = false) {
		if ($this->session->userdata ( $this->inter_id . 'skin' )) {
			$skin = $this->session->userdata ( $this->inter_id . 'skin' );
		}
		$data = $this->_get_view_commondata($data);
		if (empty ( $extra_views ['module_view'] )) {
			$extra_views ['module_view'] = $this->get_display_view ( $paras );
		}
		if ($return == TRUE)
			return parent::display ( $paras, $data, $skin, $extra_views, $return );
			parent::display ( $paras, $data, $skin, $extra_views, $return );
	}
	protected function _get_view_commondata($data){
	    if (! isset ( $data ['signPackage'] )) {
	        $this->load->model ( 'wx/Access_token_model' );
	        $data ['signPackage'] = $this->Access_token_model->getSignPackage ( $this->inter_id );
	    }
	    isset ( $data ['pagetitle'] ) or $data ['pagetitle'] = $this->public ['name'];
	    $this->load->model ( 'hotel/Hotel_config_model' );
	    $config_data = $this->Hotel_config_model->get_hotel_config ( $this->inter_id, 'HOTEL', 0, array (
	            'SHARE_SETTING'
	    ) );
	    if (empty($data ['share'] ['link'])){
	        $slink = $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
	        if (strpos ( $slink, '?' ))
	            $slink = $slink . "&id=" . $this->inter_id;
	            else
	                $slink = $slink . "?id=" . $this->inter_id;
	                $data ['share'] ['link'] = 'http://' . $slink;
	    }
	    $data ['share'] ['type'] = '';
	    $data ['share'] ['dataUrl'] = '';
	    if (! empty ( $config_data ['SHARE_SETTING'] ) && $share_config = json_decode($config_data ['SHARE_SETTING'],TRUE)) {
	        $data ['share'] ['title'] = $share_config['page_title'];
	        $data ['share'] ['imgUrl'] = $share_config['share_icon'];
	        $data ['share'] ['desc'] = $share_config['page_desc'];
	    } else {
	        $data ['share'] ['title'] = $this->public ['name'] . '-微信订房';
	        $data ['share'] ['imgUrl'] = 'http://7n.cdn.iwide.cn/public/uploads/201609/qf051934149038.jpg';
	        $data ['share'] ['desc'] = $this->public ['name'] . '欢迎您使用微信订房,享受快捷服务...';
	    }
	    return $data;
	}
	function get_display_view($paras) {
		$view = parent::get_display_view ( $paras );
		if (empty ( $view )) {
			$view = array (
					'skin_name' => isset ( $this->default_skin ) ? $this->default_skin : 'default',
					'overall_style' => '',
					'extra_style' => NULL,
					'view_subfix' => NULL,
					'extra_preview' => NULL,
					'extra_subview' => NULL
			);
		}
		return $view;
	}

	/**
	 * 返回皮肤特有配置，用于判断当前所用皮肤有某配置时才进行特定操作
	 *
	 * @param unknown $skin_name
	 * @param unknown $fun
	 */
	function get_skin_config($skin_name, $fun) {
		$config = array (
				'default2' => array (
						'hotel/sresult' => array (
								'no_hotel_list' => 1
						),
                        'hotel/search' => array (
                            'show_area' => 1
                        ),
                        'hotel/hotel_comment' => array (
                            'comment_pages' => 1
                        )
				),
				'junting' => array (
						'hotel/search' => array (
								'fans_info' => 1
						),
						'hotel/sresult' => array (
								'no_hotel_list' => 1
						)
				),
                'bigger' => array (
                    'hotel/sresult' => array (
                        'no_hotel_list' => 1
                    ),
                    'hotel/hotel_photo'=>array(
                        'all_photo' => 1
                    ),
                    'hotel/hotel_comment'=>array(
                        'comment_pages' => 1
                    )
                )
		);
		return empty ( $config [$skin_name] [$fun] ) ? array () : $config [$skin_name] [$fun];
	}
	public function is_restful($skin_name)
	{
		$file_names = array(
            'highclass'
        );
        if(in_array($skin_name,$file_names)){
            return true;
        }
		return false;
	}
}

