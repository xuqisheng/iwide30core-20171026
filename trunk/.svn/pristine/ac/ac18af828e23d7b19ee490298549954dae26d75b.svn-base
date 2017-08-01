<?php

// 大客户预订购前端控制器

class Reserve extends MY_Front_Soma {

    public  $themeConfig;
    public  $theme = 'default';

    public function __construct(){

        parent::__construct();
        //theme
        $this->load->model('soma/Theme_config_model');
        $this->themeConfig = $themeConfig = $this->Theme_config_model->get_using_theme($this->inter_id);
        $this->theme = $themeConfig['theme_path'];
    }

	public function index() {
		$this->product_list();
	}

	/**
	 * 活动列表界面
	 * @return [type] [description]
	 */
	public function product_list() {
        
		// $inter_id = $this->input->get('id', true);
		$inter_id = $this->inter_id;
		$this->load->model('soma/Sales_reserve_product_model', 'reserve_pm');
		$products = $this->reserve_pm->get_product_list($inter_id);
		$header['title'] = '大客户预订';
		
        //点击分享之后开启这些按钮
        $data['js_menu_hide'] = array( 'menuItem:copyUrl','menuItem:share:email','menuItem:originPage' );
		$data['product_list'] = $products;
		$data['inter_id'] = $inter_id;
		// var_dump($data);exit;
		# 前台每个产品需要显示两个数据：产品名和产品图片
		# 产品名：$product['product_name']
		# 产品图：$product['product_face_img']
		
        $this->_view("header", $header);
        $this->_view('product_list', $data);
	}

	/**
	 * 产品页面，订购信息填写界面
	 * 
	 * @return [type] [description]
	 */
	public function reserve_page() {
        // $header = array('title'=>'预定页面');
        // $this->_view("header",$header);
        // $this->_view('reserve');exit;
		$productId = intval($this->input->get('pid', true));
		if(empty($productId)){
            return '';
        }

		$this->load->model('soma/Sales_reserve_product_model');
		$model = $this->Sales_reserve_product_model;
		$product = $model->get_product_detail($this->inter_id, $productId);

		$header['title'] = '大客户预订';

		//点击分享之后开启这些按钮
        $data['js_menu_hide'] = array( 'menuItem:copyUrl','menuItem:share:email','menuItem:originPage' );
		$data['product'] = $product;
		$data['inter_id'] = $this->inter_id;

		# 前台需要显示两个数据：产品名和产品图片
		# 产品名：$product['product_name']
		# 产品图：$product['product_face_img']
		
		$this->_view('header', $header);
		$this->_view('reserve', $data);
	}

	/**
	 * 订购信息提交接口
	 * 
	 * @return [type] [description]
	 */
	public function reserve_post() {

		# 数据模拟：用户从表单提交过来的数据
		
		// $_POST['product_id'] = '10021';
		// $_POST['qty'] = 10;
		// $_POST['customer_name'] = '冯忠诚';
		// $_POST['customer_tel'] = '13422280480';
		// $_POST['customer_com'] = '广州金房卡信息科技有限公司';

		# ==================================

		$post = $this->input->post(null, true);

		$this->load->library('form_validation');
		$this->form_validation->set_data($post);
		$this->form_validation->set_rules('product_id', '产品ID', 'required',
			array('required' => '产品ID不能为空'));
		$this->form_validation->set_rules('qty', '购买数量', 'required|integer',
			array('required' => '购买数量不能为空', 'integer' => '购买数量必须为整数'));
		$this->form_validation->set_rules('customer_name', '联系人', 'required',
			array('required' => '联系人不能为空'));
		$this->form_validation->set_rules('customer_tel', '联系电话', 'required',
			array('required' => '联系电话不能为空'));
		if($this->form_validation->run() == false) {
			$error_arr = $this->form_validation->error_array();
			$error = implode('<br />', $error_arr);
			die(json_encode(array("result" => false, "msg" => $error)));
		}

		$this->load->model('soma/Sales_reserve_product_model', 'p_model');
		$product = $this->p_model->get_product_detail($this->inter_id, $post['product_id']);

		if(!$product) { die(json_encode(array("result" => false, "msg" => "无效产品信息，请稍后重新尝试下单操作"))); }

		$this->load->model('soma/Sales_reserve_model','r_model');
		$model = $this->r_model;

		$data['inter_id'] = $this->inter_id;
		$data['hotel_id'] = $product['hotel_id'];
		$data['openid'] = $this->openid;
		$data['sku'] = $product['sku'];
		$data['product_id'] = $product['product_id'];
		$data['name'] = $product['name'];
		$data['qty'] = $post['qty'];
		$data['customer_name'] = $post['customer_name'];
		$data['customer_tel'] = $post['customer_tel'];
		$data['customer_com'] = $post['customer_com'];
		$data['salesman'] = $post['salesman'];
		$data['business'] = 'package';  // 月饼说业务类型归属于套票业务
		$data['create_time'] = date('Y-m-d H:i:s');
		$data['update_time'] = date('Y-m-d H:i:s');
		$data['comfirmed_status'] = $model::STATUS_WAITTING; // 默认订单处于等待确认状态
		$data['reviewed_status'] = $model::STATUS_WAITTING; // 默认订单处于等待审核状态

		$reserve_id = $model->m_sets($data)->m_save();

		if(!$reserve_id) { die(json_encode(array("result" => false, "msg" => "无有效订单号，请稍后重新尝试下单操作"))); }

		$data['reserve_id'] = $reserve_id;

		echo json_encode(array('result' => true, 'reserve_id' => $reserve_id));

		// $this->reserve_order($data);
	}

	/**
	 * 订购订单信息页面
	 * @return [type] [description]
	 */
	public function reserve_order($order = null) {

		if($order == null) {
			$reserve_id = $this->input->get('reserve_id', true);
			if(!$reserve_id) { return "无效订单号"; }
			$this->load->model('soma/Sales_reserve_model','r_model');
			$order = $this->r_model->load($reserve_id)->m_data();
			if($order == null) { return "没有找到订单号为$reserve_id的订单记录"; }
		}

		// 查询酒店信息:电话
		$this->load->model('hotel/Hotel_model');
		$hotel = $this->Hotel_model->get_hotel_detail($this->inter_id, $order['hotel_id']);

		// 查询产品信息:图片
		$this->load->model('soma/Product_package_model', 'p_model');
		$product = $this->p_model->load($order['product_id'])->m_data();

		// 订单状态：允许使用/不允许使用，与后台审核状态关联
		$order_status = $order['reviewed_status'] == Sales_reserve_model::STATUS_SUCCESS?true:false;

		$header['title'] = "大客户预订";
		$data = array(
			'order' => $order,
			'hotel' => $hotel,
			'product' => $product,
			'order_status' => $order_status,
		);

		$this->_view('header', $header);
		$this->_view('submit_status', $data);

	}

	public function error_page() {
		echo "预订操作异常，请稍后再重新尝试";
	}

	// 展示为以后的皮肤做扩展
    protected function _view($file, $datas=array() ) {
        parent::_view('reserve'. DS. $file, $datas);
    }

}