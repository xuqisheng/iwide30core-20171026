<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Checkout extends MY_Admin {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '预约退房';
	protected $label_action = '';
	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->get_admin_inter_id ();
		$this->module = 'hotel';
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
		// $this->output->enable_profiler ( true );
	}
	protected function main_model_name() {
		return 'invoice/invoice_model';
	}
	public function grid() {
		$this->label_action = '退房管理';
		$this->_init_breadcrumb ( $this->label_action );
		$data['hotel_id'] = $this->input->get ( 'h' );
		$data['keywords'] = $this->input->get ( 'k' );
		$data['status'] = $this->input->get ( 's' );
		$this->load->model ( 'hotel/hotel_model' );

		$entity_id = $this->session->get_admin_hotels ();
		if (! empty ( $entity_id )) {
			$hotel_ids = explode ( ',', $entity_id );
			if (! empty ( $data ['hotel_id'] ) && ! in_array ( $data ['hotel_id'], $hotel_ids )) {
				$data ['hotel_id'] = 0;
			}
			$data ['hotels'] = $this->hotel_model->get_hotel_by_ids ( $this->inter_id, $entity_id );
		} else
			$data ['hotels'] = $this->hotel_model->get_all_hotels ( $this->inter_id,1 );

		if (! empty ( $data ['hotel_id'] )) {
		} else {
			$data ['hotel_id'] = 0;
		}
		$per_page = 20;
		$page = intval ( $this->uri->segment ( 4 ) ) <= 0 ? 0 : (intval ( $this->uri->segment ( 4 ) ) - 1) * $per_page;
		$this->load->model ( 'invoice/invoice_model' );
		$condit = array(
			'inter_id'=>$this->inter_id,
			'hotel_id'=>$data['hotel_id'],
			'keywords'=>$data['keywords'],
			'status'=>$data['status'],
			'size'=>$per_page,
			'page'=>$page,
			'entity_id'=>$entity_id
		);
		$data['list'] = $this->invoice_model->get_list($condit);
		$uri = "?h=".$data['hotel_id']."&k=".$data['keywords']."&s=".$data['status'];
		$this->load->library ( 'pagination' );
		$config ['per_page'] = $per_page;
		$config ['use_page_numbers'] = TRUE;
		$config ['cur_page'] = $page;
		$config ['uri_segment'] = 4;
		$config ['numbers_link_vars'] = array (
				'class' => 'number' 
		);
		$config ['suffix'] = $uri;
		$config ['suffix'] .= '';
		$config ['cur_tag_open'] = '<a class="number current" href="#">';
		$config ['cur_tag_close'] = '</a>';
		$config ['base_url'] = base_url ( "index.php/hotel/checkout/index" );
		$config ['first_url'] = base_url ( "index.php/hotel/checkout/index" ) . $uri;
		$config ['first_url'] .= '';
		$config ['total_rows'] = $this->invoice_model->get_list($condit,true);
		$this->pagination->initialize ( $config );
		$data ['pagination'] = $this->pagination->create_links ();

		$data['channel'] = array(
			'weixin'=>'微信',
			'scan'=>'扫码'
		);
		
		if(!empty($data['list'])){
			$this->load->model ( 'common/Enum_model' );
			$status_des = $this->Enum_model->get_enum_des ( array (
					'HOTEL_ORDER_PAY_STATUS',
					'HOTEL_INVOICE_TYPE'
			) );
			$this->load->model ( 'pay/Pay_model' );
			$pay_ways = $this->Pay_model->get_pay_way ( array (
					'inter_id' => $this->inter_id,
					'module' => $this->module,
					'key' => 'value' 
			) );
			$pay_ways ['bonus'] = new stdClass ();
			$pay_ways ['bonus']->pay_name = '积分支付';
			foreach ($data['list'] as $k => $v) {
				if(!empty($v['detail'])){
					$detail = json_decode($v['detail'],true);
					if(isset($detail['hn'])){
						$data['list'][$k]['hotel_name'] = $detail['hn'];
					}
					if(isset($detail['rn'])){
						$data['list'][$k]['room_name'] = $detail['rn'];
					}
					if(isset($detail['pn'])){
						$data['list'][$k]['price_name'] = $detail['pn'];
					}
					if(isset($detail['name'])){
						$data['list'][$k]['oname'] = $detail['name'];
					}
					if(isset($detail['tel'])){
						$data['list'][$k]['tel'] = $detail['tel'];
					}
				}
				if(!empty($v['invoice_content'])){
					$invoice_content = json_decode($v['invoice_content'],true);
					if(!empty($invoice_content)){
						$data['list'][$k]['invoice_content'] = $invoice_content;
						$data['list'][$k]['type'] = $invoice_content['type'];
						$v ['type'] = $invoice_content['type'];
						$data['list'][$k]['title'] = $invoice_content['title'];
					}
				}
				if(!empty($v ['paytype'])){
	                if(isset($pay_ways [$v ['paytype']]->pay_name)){
	                    $data['list'] [$k] ['paytype'] = $pay_ways [$v ['paytype']]->pay_name;
	                }else{
	                    $data['list'] [$k] ['paytype']='';
	                }
				}
				if(isset($v ['paid'])){
					$data['list'] [$k] ['is_paid'] = $status_des ['HOTEL_ORDER_PAY_STATUS'] [$v ['paid']];
					if(($v ['paytype'] == 'weixin' || $v ['paytype'] == 'weifutong' ||$v ['paytype'] == 'lakala'||$v ['paytype'] == 'lakala_y' || $v ['paytype'] == 'unionpay') && $v ['paid'] == 0){
						$data['list'] [$k] ['is_paid'] = '未支付';
					}
				}
				if(isset($v ['type'])){
					$data['list'] [$k] ['itype'] = $status_des ['HOTEL_INVOICE_TYPE'] [$v ['type']];
				}
			}

		}
		$data['div'] = $this->invoice_model->get_count($condit);
		$this->_render_content ( $this->_load_view_file ( 'index' ), $data, false );
	}

	function edit_post(){
		$cid = intval ( $this->input->post ( 'cid' ) );
		$real_price = $this->input->post ( 'real_price' );
		$remark = $this->input->post ( 'remark' );
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$entity_id = $this->session->get_admin_hotels ();
		if(!empty($real_price)&&!is_numeric($real_price)){
			echo 'failds';exit;
		}
		$re = $model->edit_checkout ( $this->inter_id,$cid, array (
				'realprice' => $real_price,
				'remark' => $remark
		) );
		if ($re) {
			$row = $model->get_checkout_byid($cid);
			$this->load->model('plugins/template_msg_model');
			$detail = json_decode($row['detail'],true);
			if(isset($row['invoice_list_id']) && $row['invoice_list_id']>0){
				$invoice_content = json_decode($row['invoice_content'],true);

				$this->load->model ( 'common/Enum_model' );
				$status_des = $this->Enum_model->get_enum_des ( array (
						'HOTEL_INVOICE_TYPE'
				) );
				if(isset($invoice_content ['type'])){
					$type = $status_des ['HOTEL_INVOICE_TYPE'] [$invoice_content ['type']];
				}else{
					$type = $status_des ['HOTEL_INVOICE_TYPE'] [1];
				}
				$this->load->model('plugins/template_msg_model');
				$config = array(
					'inter_id'=>$this->inter_id,
					'openid'=>$row['openid'],
					'hotel'=>$detail['hn'],//这个传实际用户住的酒店
					'check_out_time'=>date('Y-m-d H:i'),//这个传实际记录的时间
					'amount' => $real_price,//这个传开票的金额
					'type'=>$type,//这个传发票类型
					'project'=>'住宿费',//这个传发票项目
					'title'=>$invoice_content['title'],//发票抬头
				);
				$result = $this->template_msg_model->send_checkout_or_invoice_msg($config,'hotel_invoice_notice',1);
			}else{
				$config = array(
					'inter_id'=>$this->inter_id,
					'openid'=>$row['openid'],
					'hotel'=>$detail['hn'],//这个传实际用户住的酒店
					'room_num'=>$row['room_num']//房间号
				);
				$result = $this->template_msg_model->send_checkout_or_invoice_msg($config,'hotel_checkout_success_notice',1);
			}
			$this->load->library('MYLOG');
			$config['result'] = $result;
			MYLOG::w(json_encode($config),'checkoutnotify');
			echo 'success';
		} else {
			echo 'faild';
		}
	}
}
