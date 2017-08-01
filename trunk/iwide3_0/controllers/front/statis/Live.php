<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 数据直播
 */
class Live extends MY_Front_Soma {

	protected $statis_start = '2016-12-01';
	// protected $statis_start = '2016-11-11 00:00:00';

	public function index() {

		//点击分享之后开启这些按钮
        $js_menu_show = array( 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:copyUrl' );
        $uparams= $this->input->get()+ array('id'=> $this->inter_id); 

        //取出分享配置
        $this->load->model( 'soma/Share_config_model', 'ShareConfigModel' );
        $ShareConfigModel = $this->ShareConfigModel;
        $position = $ShareConfigModel::POSITION_DEFAULT;//分享类型
        $share_config_detail = $ShareConfigModel->get_share_config_list( $position, $this->inter_id );
        $this->load->helper('soma/package');
        // write_log(json_encode( $share_config_detail ), 'share_config_detail.txt' );
         $default_share_config = $this->get_default_sharing();

        $share_config = array(
            'title'=> '微信酒店双十二数据直播－金房卡',
            'desc'=> '快看看你家酒店排第几，哪些产品最热销',
            'link'=> Soma_const_url::inst()->get_share_url( $this->openid, '*/*/*', $uparams ),//$share_config_detail['share_link'],
            'imgUrl'=> isset( $share_config_detail['share_img'] ) && !empty( $share_config_detail['share_img'] ) ? $share_config_detail['share_img'] : $default_share_config['share_img'],
        );

		//查找出公众号名
		$this->load->model( 'wx/Publics_model' );
		$publics = $this->Publics_model->get_public_by_id($this->inter_id);
        if( $publics ){
			$inter_id_name = $publics['name'];
		}else{
			$inter_id_name = '微信酒店';
		}

		$data = array('inter_id' => $this->inter_id, 'inter_name' => $inter_id_name, 'js_share_config' => $share_config);

		// 渲染视图
		$this->_view("header", array());
        $this->_view("index", $data);
	}

	public function login_post() {

		$op_res['status']  = Soma_base::STATUS_FALSE;
		$op_res['message'] = '登录失败，请检查账号密码后重新尝试!';

		$username = $this->input->post('username', true);
		$password = $this->input->post('password', true);

		$this->load->model('soma/Live_user_model', 'u_model');

		// if($data = $this->get_login_session()
		// 	|| $data = $this->u_model->valid_user($username, $password, $this->inter_id)) {
		if($data = $this->u_model->valid_user($username, $password, $this->inter_id)) {
			// 记入session，登录标识生命周期与session一致，需要存入inter_id，hotel_id
			
			$this->load->model( 'wx/Publics_model' );
	        $publics = $this->Publics_model->get_public_by_id($data['inter_id']);
	        if( $publics ){
				$data['inter_name'] = $publics['name'];
	        }else{
				$data['inter_name'] = '微信酒店';
	        }

			$this->session->set_userdata(array('live_user:' . $this->inter_id => $data));

			$op_res['status']  = Soma_base::STATUS_TRUE;
			$op_res['data']	   = array('inter_name' => $data['inter_name']);
			$op_res['message'] = '登录成功!';
		}

		echo json_encode($op_res);
	}

	public function ajax_live_data() {
		
		$op_res['status']  = Soma_base::STATUS_FALSE;
		$op_res['data']	   = array();
		$op_res['message'] = '用户尚未登录，或会话已过期!';
		
		if($session = $this->get_login_session($this->inter_id)) {
			$this->load->model('soma/Statis_sales_model', 'ss_model');
			$this->load->model('soma/Statis_product_model', 'sp_model');
			$sales_rank = $this->ss_model->get_live_sales_data($session['inter_id'], $this->statis_start);
			$product_rank = $this->sp_model->get_live_sales_data($session['inter_id'], $this->statis_start);
			$op_res['status']  = Soma_base::STATUS_TRUE;
			$op_res['data']    = array('sales_rank' => $sales_rank, 'product_rank' => $product_rank);
			$op_res['message'] = '获取信息成功！';
		}

		echo json_encode($op_res);
	}

	public function get_login_session($inter_id) {
		$data = $this->session->userdata('live_user:' . $inter_id);
		return $data ? $data : false;
	}

	public function check_login() {
		$op_res['status']  = Soma_base::STATUS_FALSE;
		$op_res['message'] = '用户尚未登录!';
		if($session = $this->get_login_session($this->inter_id)) {
			$op_res['status']  = Soma_base::STATUS_TRUE;
			$op_res['data']	   = array('inter_name' => $session['inter_name']);
			$op_res['message'] = '用户已经登录!';
		}
		echo json_encode($op_res);
	}

	protected function _view($file, $datas=array() ) {
        parent::_view('live'. DS. $file, $datas);
    }

    //日志写入
    public function write_log( $content, $dir = 'live')
    {
        $file= date('Y-m-d'). '.txt';
        //echo $tmpfile;die;
        $path= APPPATH.'logs'.DS. 'soma' . DS .$dir . DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $fp = fopen( $path. $file, 'a');

        $CI = & get_instance();
        $ip= $CI->input->ip_address();
        $content= str_repeat('-', 40). "\n[". date('Y-m-d H:i:s'). ']'
            ."\n". $ip. "\n". $content. "\n";
        fwrite($fp, $content);
        fclose($fp);
    }

    public function get_default_sharing()
    {
      if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ){
        $share_img = base_url('public/soma/images/sharing_mooncake.png');
        $default_title = '月饼说，送您一份中秋好礼物';
        $default_desc = '微信送礼更有趣';
      } else {
        $share_img = base_url('public/soma/images/sharing_package.png');
        $default_title = '发现一家好去处，快点开看看';
        $default_desc = '优惠不等人';
      }

      $default_share_config = array(
          'share_img' => $share_img,
          'default_title' => $default_title,
          'default_desc' => $default_desc,
        );
      return $default_share_config;
    }

}