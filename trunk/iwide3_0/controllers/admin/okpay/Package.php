<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Package extends MY_Admin_Api {

	protected $label_module= '快乐付';
	protected $label_controller= '礼包规则列表';
	protected $label_action= '列表';
	
	function __construct(){
		parent::__construct();
	}
	
	protected function main_model_name()
	{
		return 'okpay/Okpay_package_model';
	}
	
	function grid(){
		//$this->grid();
		$inter_id= $this->session->get_admin_inter_id();
		$filter= array('inter_id'=>$inter_id );
		//get请求接收参数
		$params= $this->input->get();
		if(is_array($params) && count($params)>0 )
			$filter= array_merge($params, $filter);
		//post请求接收参数
		$post = $this->input->post();
		if(is_array($post)){
			$filter = array_merge($post,$filter);
		}//var_dump($filter);die;
		$avgs = array();
		$avgs['id']  	  = $this->input->post('id');
		$avgs['start_time']  	  = $this->input->post('start_time');
		$avgs['end_time']		  = $this->input->post('end_time');
		$avgs['status']          = $this->input->post('status');//启用状态
		$avgs['hotel_id']         = $this->input->post('hotel_id');
		$avgs['type_id']         = $this->input->post('type_id');//关联场景
		$keys = $this->uri->segment(4);
		$keys = explode('_', $keys);
		if(isset($keys[0]) && !empty($keys[0])){
			$avgs['id'] = $keys[0];
		}
		if(isset($keys[1]) && !empty($keys[1])){
			$avgs['start_time'] = $keys[1];
		}
		if(isset($keys[2]) && !empty($keys[2])){
			$avgs['end_time'] = $keys[2];
		}
		if(isset($keys[3]) && !empty($keys[3])){
			$avgs['status'] = $keys[3];
		}
		if(isset($keys[4]) && !empty($keys[4])){
			$avgs['hotel_id'] = $keys[4];
		}
		if(isset($keys[5]) && !empty($keys[5])){
			$avgs['type_id'] = $keys[5];
		}
		if(!empty($avgs)){
			$filter = array_merge($filter,$avgs);
		}
		//export
		/*if(isset($filter['export']) && $filter['export']){
			$this->ext_data($filter);
			die;
		}*/
		//$this->grid();
		$this->load->library('pagination');
		$config['per_page']          = 50;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$this->load->model('okpay/okpay_package_model');
		$res = $this->okpay_package_model->get_package_info_list($filter,$config['per_page'],$config['cur_page']);

		//获取所有type
		$list = $this->okpay_package_model->get_all_type_by_inter_id($inter_id);
		$type_arr = array();
		if(!empty($list)){
			foreach($list as $key=>$val){
				$type_arr[$val['id']] = $val['name'];
			}
		}
//var_dump($type_arr);die;
		//获取公众号下的酒店
		$this->load->model ( 'hotel/hotel_model' );
		$hotels = $this->hotel_model->get_hotel_hash ( array('inter_id'=>$inter_id) );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		$config['uri_segment']       = 5;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url('okpay/activities/index/'.$filter['id'].'_'.$filter['start_time'].'_'.$filter['end_time'].'_'.$filter['status'].'_'.$filter['hotel_id'].'_'.$filter['type_id']);
		$config['total_rows']        = $this->okpay_package_model->get_package_info_count($filter,$config['per_page'],$config['cur_page']);;
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
			'hotels'	 => $hotels,
			'types'  	 => $type_arr,
			'posts'		 => $filter,
			'res'        => $res,
			'total'      => $config['total_rows'],
		);

		$html= $this->_render_content($this->_load_view_file('grid'), $view_params, TRUE);
		echo $html;
	}


	//add
	public function add(){
		$inter_id= $this->session->get_admin_inter_id();
		$filter= array('inter_id'=>$inter_id );
		$post = $this->input->post();
		if(is_array($post)){
			$filter = array_merge($post,$filter);
		}//var_dump($filter);die;
        //调取会员接口 查询礼包
        $post_data = array(
            'inter_id'=>$inter_id,
            'num'=>10
        );
        //请求礼包信息URL
        $package = array();
        $package_list = $this->doCurlPostRequest( INTER_PATH_URL."package/getlist" , $post_data );
        if(!empty($package_list['data'])){
            $package = $package_list['data'];
        }
		//如果是add
		$submit = addslashes($this->input->post('submit'));
		if($submit){//add
			//var_dump($filter);die;
			if(empty($filter['type_id'])){
				$this->session->put_notice_msg('场景不能为空！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/add'));
			}
            if(empty($filter['package_id'])){
                $this->session->put_notice_msg('礼包不能为空！');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/add'));
            }
			$data = array();
			$data['inter_id'] = $filter['inter_id'];
			$data['hotel_id'] = $filter['hotel_id'];
			$data['type_id'] = $filter['type_id'];
			$data['package_name'] = isset($package[$filter['package_id']])?$package[$filter['package_id']]['name']:'--';
            $data['package_id'] = $filter['package_id'];
			$data['start_money'] = $filter['start_money'];
            $data['count'] = $filter['count'];
			$data['start_time'] = $filter['start_time'];
			$data['end_time'] = $filter['end_time'];
			$data['sort_order'] = isset($filter['sort_order'])?$filter['sort_order']:0;
			$data['status'] = $filter['status'];
			$data['create_time'] = date('Y-m-d H:i:s',time());
			$data['update_time'] = date('Y-m-d H:i:s',time());
            if(isset($filter['no_exec_day']) && is_array($filter['no_exec_day'])){//不执行日
                $data['no_exec_day'] = implode(',',$filter['no_exec_day']);
            }else{
                $data['no_exec_day'] = '';
            }
            //优惠限制
            if(isset($filter['date']) && isset($filter['use_count'])){
                $data['gift_limit'] = $filter['date'] . '|' . $filter['use_count'];
            }else{
                $data['gift_limit'] = '';
            }

			$res = $this->db->insert('okpay_package',$data);
			$message= ($res)?
				$this->session->put_success_msg('已新增数据！'):
				$this->session->put_notice_msg('此次数据新增失败！');
			$this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
			die;
		}

		//获取公众号下的酒店
		$this->load->model ( 'hotel/hotel_model' );
		$filterH = array('inter_id'=>$inter_id);
		if(!empty($this->session->get_admin_hotels())){
			$filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
		}
		$hotels = $this->hotel_model->get_hotel_hash ($filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		$keys = array_keys($hotels);
		//获取公众号下的场景
		$this->load->model('okpay/okpay_type_model');
		$first_hotel = isset($keys[0])?$keys[0]:0;
		$list = $this->okpay_type_model->get_hotel_okpay_type_list($inter_id,$first_hotel);//先获取第一家酒店下的场景
		$view_params = array(
			'hotel' => $hotels,
			'type'  => $list,
            'package' => $package
		);
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		echo $html;
	}


	// edit
	public function edit(){
		$inter_id= $this->session->get_admin_inter_id();
		$filter= array('inter_id'=>$inter_id );
		$post = $this->input->post();
		if(is_array($post)){
			$filter = array_merge($post,$filter);
		}//var_dump($filter);die;
		$id = $this->input->get('ids');
		if(!$id){
			echo 'data error!';
			die;
		}
        //调取会员接口 查询礼包
        $post_data = array(
            'inter_id'=>$inter_id,
            'num'=>100
        );
        //请求礼包信息URL
        $package = array();
        $package_list = $this->doCurlPostRequest( INTER_PATH_URL."package/getlist" , $post_data );
        if(!empty($package_list['data'])){
            $package = $package_list['data'];
        }

		//如果是update
		$submit = addslashes($this->input->post('submit'));
		if($submit && $id){//add
			//var_dump($filter);die;
			if(empty($filter['type_id'])){
				$this->session->put_notice_msg('场景不能为空！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_edit?ids='.$id));
			}
            if(empty($filter['package_id'])){
                $this->session->put_notice_msg('礼包不能为空！');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/add'));
            }
            $data = array();
            $data['inter_id'] = $filter['inter_id'];
            $data['hotel_id'] = $filter['hotel_id'];
            $data['type_id'] = $filter['type_id'];
            $data['package_name'] = isset($package[$filter['package_id']])?$package[$filter['package_id']]['name']:'--';
            $data['package_id'] = $filter['package_id'];
            $data['start_money'] = $filter['start_money'];
            $data['count'] = $filter['count'];
            $data['start_time'] = $filter['start_time'];
            $data['end_time'] = $filter['end_time'];
            $data['sort_order'] = isset($filter['sort_order'])?$filter['sort_order']:0;
            $data['status'] = $filter['status'];
            $data['update_time'] = date('Y-m-d H:i:s',time());
            if(isset($filter['no_exec_day']) && is_array($filter['no_exec_day'])){//不执行日
                $data['no_exec_day'] = implode(',',$filter['no_exec_day']);
            }else{
                $data['no_exec_day'] = '';
            }
            //优惠限制
            if(isset($filter['date']) && isset($filter['use_count'])){
                $data['gift_limit'] = $filter['date'] . '|' . $filter['use_count'];
            }else{
                $data['gift_limit'] = '';
            }
			$res = $this->db->update('okpay_package',$data,array('id'=>$id));
			$message= ($res)?
				$this->session->put_success_msg('已更新数据！'):
				$this->session->put_notice_msg('此次数据更新失败！');
			$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
			die;
		}
		//根据id获取单条记录
		$this->load->model('okpay/okpay_package_model');
		$res = $this->okpay_package_model->get($id,$inter_id);
        //不执行日
        $no_exec_day = isset($res['no_exec_day'])&&!empty($res['no_exec_day'])?explode(',',$res['no_exec_day']):array();
        //限制
        $gift_limit = isset($res['gift_limit'])&&!empty($res['gift_limit'])?explode('|',$res['gift_limit']):array();
		//获取公众号下的酒店
		$this->load->model ( 'hotel/hotel_model' );
		$filterH = array('inter_id'=>$inter_id);
		if(!empty($this->session->get_admin_hotels())){
			$filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
		}
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		$keys = array_keys($hotels);
		//获取公众号下的场景
		$this->load->model('okpay/okpay_type_model');
		$list = $this->okpay_type_model->get_hotel_okpay_type_list($inter_id,$res['hotel_id']);//先获取第一家酒店下的场景
		$view_params = array(
			'id'	=> $id,
			'posts'	=> $res,
			'hotel' => $hotels,
			'type'  => $list,
            'package' => $package,
            'no_exec_day'=>$no_exec_day,
            'gift_limit' =>$gift_limit
		);
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		echo $html;
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
	
	
	public function get_type_list(){
		$hotelid = $this->input->post("hotelid",true);
		$inter_id	= $this->session->get_admin_inter_id();
		
		
		//根据$hotels 获取第一家酒店的场景
		$typeList = array();
		if(!empty($inter_id) && !empty($hotelid)){
				
			$this->load->model('okpay/okpay_type_model');
			$typeList = $this->okpay_type_model->get_hotel_okpay_type_list($inter_id,$hotelid);
				
			/* foreach($list as $key=>$val){
				$typeList[$val['id']] = $val['name'];
			} */
		}
		
		if(sizeof($typeList) > 0){
			echo json_encode ( array (
					'status' =>1,
					'message' => '读取成功',
					'data'=>$typeList
			));
		}else{
			echo json_encode ( array (
					'status' =>0,
					'message' => '读取失败，或者当前酒店没有添加场景'
			));
		}
	}
	
	
	
}
