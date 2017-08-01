<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activities extends MY_Admin {

	protected $label_module= '快乐付';
	protected $label_controller= '活动列表';
	protected $label_action= '活动';
	
	function __construct(){
		parent::__construct();
	}
	
	protected function main_model_name()
	{
		return 'okpay/Okpay_activities_model';
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
		$avgs['begin_time']  	  = $this->input->post('begin_time');
		$avgs['end_time']		  = $this->input->post('end_time');
		$avgs['status']          = $this->input->post('status');//启用状态
		$avgs['isfor']       = $this->input->post('isfor');
		$avgs['hotel_id']         = $this->input->post('hotel_id');
		$avgs['type_id']         = $this->input->post('type_id');//关联场景
		$keys = $this->uri->segment(4);
		$keys = explode('_', $keys);
		if(isset($keys[0]) && !empty($keys[0])){
			$avgs['id'] = $keys[0];
		}
		if(isset($keys[1]) && !empty($keys[1])){
			$avgs['begin_time'] = $keys[1];
		}
		if(isset($keys[2]) && !empty($keys[2])){
			$avgs['end_time'] = $keys[2];
		}
		if(isset($keys[3]) && !empty($keys[3])){
			$avgs['status'] = $keys[3];
		}
		if(isset($keys[4]) && !empty($keys[4])){
			$avgs['isfor'] = $keys[4];
		}
		if(isset($keys[5]) && !empty($keys[5])){
			$avgs['hotel_id'] = $keys[5];
		}
		if(isset($keys[6]) && !empty($keys[6])){
			$avgs['type_id'] = $keys[6];
		}
		if(!empty($avgs)){
			$filter = array_merge($filter,$avgs);
		}
		//export
		if(isset($filter['export']) && $filter['export']){
			$this->ext_data($filter);
			die;
		}
		//$this->grid();
		$this->load->library('pagination');
		$config['per_page']          = 50;
		$page = empty($this->uri->segment(5)) ? 0 : ($this->uri->segment(5) - 1) * $config['per_page'];
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$this->load->model('okpay/okpay_activities_model');
		$res = $this->okpay_activities_model->get_activities_info_list($filter,$config['per_page'],$config['cur_page']);
		if(!empty($res)){
			foreach($res as $k=>$v){
				if($v['isfor'] == 3){//新加的一种方式 随机减
					//反序列化后得到数组 array(con1 => array('min'=>2,'max'=>5,rate=>'12',con2 => array('min'=>2,'max'=>5,rate=>'12',con3 => array('min'=>2,'max'=>5,rate=>'12')
					$conf = unserialize($v['cut_config']);//var_dump($config);
					$min = $conf['con1'][0];
					if(isset($conf['con3'][1])&& !empty($conf['con3'][1])){
						$max = $conf['con3'][1];
					}elseif(isset($conf['con2'][1])&& !empty($conf['con2'][1])){
						$max = $conf['con2'][1];
					}else{
						$max = $conf['con1'][1];
					}
					$res[$k]['minmax'] = $min.'% -'.$max.'%';
				}
			}
		}
		//获取所有type
		$list = $this->okpay_activities_model->get_all_type_by_inter_id($inter_id);
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
		$config['base_url']          = site_url('okpay/activities/index/'.$filter['id'].'_'.$filter['begin_time'].'_'.$filter['end_time'].'_'.$filter['status'].'_'.$filter['isfor'].'_'.$filter['hotel_id'].'_'.$filter['type_id']);
		$config['total_rows']        = $this->okpay_activities_model->get_activities_info_count($filter,$config['per_page'],$config['cur_page']);;
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
			'isfor'		=> array(1=>'每满减',2=>'单满减',3=>'随机减'),
			'total'      => $config['total_rows'],
		);

		$html= $this->_render_content($this->_load_view_file('grid'), $view_params, TRUE);
		echo $html;
	}
	
	public function grid_bak()
	{
		$inter_id= $this->session->get_admin_inter_id();
		if($inter_id== FULL_ACCESS) $filter= array();
		else if($inter_id) $filter= array('inter_id'=>$inter_id );
		else $filter= array('inter_id'=>'deny' );
		if(is_ajax_request())
			$get_filter= $this->input->post();
			else
				$get_filter= $this->input->get('filter');
					
				if( !$get_filter) $get_filter= $this->input->get('filter');
					
				if(is_array($get_filter)) $filter= $get_filter+ $filter;
				$this->_grid($filter);
	}

    public function edit()
    {
        $this->label_action= '信息维护';
        $this->_init_breadcrumb($this->label_action);

        $model_name= $this->main_model_name();
        $model= $this->_load_model($model_name);
        $inter_id= $this->session->get_admin_inter_id();
        if($inter_id== FULL_ACCESS) $filter= array();
        $id= intval($this->input->get('ids'));
        $this->load->model ( 'hotel/hotel_model' );
        $hotels = $this->hotel_model->get_hotel_hash ( array('inter_id'=>$inter_id) );
        if($id){
            $model= $model->load($id);
            $this->load->model('okpay/okpay_type_model');
            $list = $this->okpay_type_model->get_hotel_okpay_type_list($inter_id,$model->m_get('hotel_id'));
            $typeList = '';
            foreach($list as $key=>$val){
                $typeList[$val['id']] = $val['name'];
            }
            $view_params= array(
                  'model'=> $model,
                'hotels'=>$hotels,
                'typelist'=>$typeList,
            );
            $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
            echo $html;
            die;
        }
        $view_params= array(
            'model'=> $model,
            'hotels'=>$hotels,
        );

        $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
        echo $html;
    }

	
	public function edit_post()
	{
		$this->label_action= '信息维护';
		$this->_init_breadcrumb($this->label_action);
	
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		$pk= $model->table_primary_key();
	
		$this->load->library('form_validation');
		$post= $this->input->post();
		$labels= $model->attribute_labels();
		$base_rules= array(
				'title'=> array(
						'field' => 'title',
						'label' => $labels['title'],
						'rules' => 'trim|required',
				),
				'hotel_id'=> array(
						'field' => 'hotel_id',
						'label' => $labels['hotel_id'],
						'rules' => 'trim|required',
				),
				'type_id'=>array(
						'field'=>'type_id',
						'label'=>$labels['type_id'],
						'rules'=>'trim|required',
				),
				'discount_amount'=> array(
						'field' => 'discount_amount',
						'label' => $labels['discount_amount'],
						'rules' => 'trim|required',
				),
				'begin_time'=> array(
						'field' => 'begin_time',
						'label' => $labels['begin_time'],
						'rules' => 'trim|required',
				),
				'end_time'=> array(
						'field' => 'end_time',
						'label' => $labels['end_time'],
						'rules' => 'trim|required',
				),
				'isfor'=> array(
						'field' => 'isfor',
						'label' => $labels['isfor'],
						'rules' => 'trim|required',
				),
				'isfor_money'=> array(
						'field' => 'isfor_money',
						'label' => $labels['isfor_money'],
						'rules' => 'trim|required',
				)
		);
	
		//检测并上传文件。
		$post= $this->_do_upload($post, 'logo');
		 
		$adminid= $this->session->get_admin_id();
		if( empty($post[$pk]) ){
			//add data.
			$this->form_validation->set_rules($base_rules);
	
			if ($this->form_validation->run() != FALSE) {
                if(isset($post['no_exec_day']) && is_array($post['no_exec_day'])){//不执行日
                    $post['no_exec_day'] = implode(',',$post['no_exec_day']);
                }else{
                    $post['no_exec_day'] = '';
                }
                //优惠限制
                if(isset($post['date']) && isset($post['use_count'])){
                    $post['gift_limit'] = $post['date'] . '|' . $post['use_count'];
                }else{
                    $post['gift_limit'] = '';
                }
				$post['inter_id']    = $this->session->get_admin_inter_id();
				$post['create_time'] = time();
				$post['begin_time']  = strtotime($post['begin_time']);
				$post['end_time']    = strtotime($post['end_time']);
				$result= $model->m_sets($post)->m_save($post);
				$message= ($result)?
				$this->session->put_success_msg('已新增数据！'):
				$this->session->put_notice_msg('此次数据保存失败！');
				$this->_log($model);
				$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	
			} else
				$model= $this->_load_model();
	
		} else {
			$this->form_validation->set_rules($base_rules);
			if ($this->form_validation->run() != FALSE) {
                if(isset($post['no_exec_day']) && is_array($post['no_exec_day'])){//不执行日
                    $post['no_exec_day'] = implode(',',$post['no_exec_day']);
                }else{
                    $post['no_exec_day'] = '';
                }
                //优惠限制
                if(isset($post['date']) && isset($post['use_count'])){
                    $post['gift_limit'] = $post['date'] . '|' . $post['use_count'];
                }else{
                    $post['gift_limit'] = '';
                }
				$post['update_time']= time();
				$post['begin_time']  = strtotime($post['begin_time']);
				$post['end_time']    = strtotime($post['end_time']);
				$result= $model->load($post[$pk])->m_sets($post)->m_save($post);
				$message= ($result)?
				$this->session->put_success_msg('已保存数据！'):
				$this->session->put_notice_msg('此次数据修改失败！');
				$this->_log($model);
				$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
	
			} else
				$model= $model->load($post[$pk]);
		}
	
		//验证失败的情况
		$validat_obj= _get_validation_object();
		$message= $validat_obj->error_html();
		//页面没有发生跳转时用寄存器存储消息
		$this->session->put_error_msg($message, 'register');
	
		$fields_config= $model->get_field_config('form');

		$view_params= array(
				'model'=> $model,
				'fields_config'=> $fields_config,
				'check_data'=> TRUE,
                'typelist' => $typeList,
		);
		$html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		echo $html;
	}

	//随机减add && edit
	public function sj_add(){
		$inter_id= $this->session->get_admin_inter_id();
		$filter= array('inter_id'=>$inter_id );
		$post = $this->input->post();
		if(is_array($post)){
			$filter = array_merge($post,$filter);
		}//var_dump($filter);die;
		//如果是add
		$submit = addslashes($this->input->post('submit'));
		if($submit){//add
			//var_dump($filter);die;
			if(empty($filter['type_id'])){
				$this->session->put_notice_msg('场景不能为空！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_add'));
			}
			$data = array();
			$data['inter_id'] = $filter['inter_id'];
			$data['hotel_id'] = $filter['hotel_id'];
			$data['type_id'] = $filter['type_id'];
			$data['title'] = $filter['title'];
			$data['isfor'] = 3;//随机减
			$data['isfor_money'] = $filter['isfor_money'];
			$data['begin_time'] = strtotime($filter['begin_time']);
            $data['end_time'] = strtotime($filter['end_time']." 23:59:59");
			$data['status'] = $filter['status'];
			$data['create_time'] = time();
			$data['update_time'] = time();
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
			$con1 = isset($filter['con1'])?$filter['con1']:array();
			$con2 = isset($filter['con2'])?$filter['con2']:array();
			$con3 = isset($filter['con3'])?$filter['con3']:array();
			//规则1
			if(empty($con1[0]) || empty($con1[1] || empty($con1[2]))){//下标：0:最小百分比 1:最大百分比 2:对应概率
				$this->session->put_notice_msg('请完善规则1的内容！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_add'));
			}elseif($con1[0] >= $con1[1]){//最小百分比大于最大百分比
				$this->session->put_notice_msg('规则1中最大百分比必须需大于最小百分比！！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_add'));
			}
			//规则2
			if(empty($con2[0]) && empty($con2[1]) && empty($con2[2])){
				//$this->session->put_notice_msg('请完善规则2的内容！');
				//$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_add'));
			}elseif(empty($con2[0]) || empty($con2[1]) || empty($con2[2])){
				$this->session->put_notice_msg('请完善规则2的内容！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_add'));
			}elseif($con2[0] >= $con2[1]){
				$this->session->put_notice_msg('规则2中最大百分比必须需大于最小百分比！！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_add'));
			}
			//规则三
			if(empty($con3[0]) && empty($con3[1]) && empty($con3[2])){
				//$this->session->put_notice_msg('请完善规则3的内容！');
				//$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_add'));
			}elseif(empty($con3[0]) || empty($con3[1]) || empty($con3[2])){
				$this->session->put_notice_msg('请完善规则3的内容！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_add'));
			}elseif($con3[0] >= $con3[1]){
				$this->session->put_notice_msg('规则3中最大百分比必须需大于最小百分比！！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_add'));
			}
			//比较
			if(!empty($con3[0]) && !empty($con2[1])){
				if($con3[0] <= $con2[1]){
					$this->session->put_notice_msg('规则3最小百分比需大于规则2最大百分比！');
					$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_add'));
				}
			}
			if(!empty($con2[0]) && !empty($con1[1])){
				if($con2[0] <= $con1[1]){
					$this->session->put_notice_msg('规则2最小百分比需大于规则1最大百分比！');
					$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_add'));
				}
			}
			if(!empty($con3[0]) && !empty($con1[1])){
				if($con3[0] <= $con1[1]){
					$this->session->put_notice_msg('规则3最小百分比需大于规则1最大百分比！');
					$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_add'));
				}
			}
			//比例百分比
			if(!empty($con1[2] && !empty($con2[2]) && !empty($con3[2]))){
				if($con1[2]+$con2[2]+$con3[2]!=100){
					$this->session->put_notice_msg('有效规则总概率需等于100%！');
					$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_add'));
				}
			}elseif($con1[2] && $con2[2]){
				if($con1[2]+$con2[2]!=100){
					$this->session->put_notice_msg('有效规则总概率需等于100%！');
					$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_add'));
				}
			}
			elseif($con1[2] && $con3[2]){
				if($con1[2]+$con3[2]!=100){
					$this->session->put_notice_msg('有效规则总概率需等于100%！');
					$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_add'));
				}
			}elseif($con1[2] != 100){
				$this->session->put_notice_msg('有效规则总概率需等于100%！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_add'));
			}
			$config = array_merge(array('con1'=>$con1),array('con2'=>$con2),array('con3'=>$con3));
			$data['cut_config'] = serialize($config);
			$res = $this->db->insert('okpay_activities',$data);
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
			'type'  => $list
		);
		$html= $this->_render_content($this->_load_view_file('sj_edit'), $view_params, TRUE);
		echo $html;
	}


	//随机减add && edit
	public function sj_edit(){
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
		//如果是update
		//$submit = addslashes($this->input->post('submit'));
		if(count($post)>0 && $id){//add
			//var_dump($filter);die;
			if(empty($filter['type_id'])){
				$this->session->put_notice_msg('场景不能为空！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_edit?ids='.$id));
			}
			$data = array();
			$data['inter_id'] = $filter['inter_id'];
			$data['hotel_id'] = $filter['hotel_id'];
			$data['type_id'] = $filter['type_id'];
			$data['title'] = $filter['title'];
			$data['isfor_money'] = $filter['isfor_money'];
			$data['isfor'] = 3;//随机减
			$data['begin_time'] = strtotime($filter['begin_time']);
			$data['end_time'] = strtotime($filter['end_time']." 23:59:59");
			$data['status'] = $filter['status'];
			//$data['create_time'] = time();
			$data['update_time'] = time();
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
			$con1 = isset($filter['con1'])?$filter['con1']:array();
			$con2 = isset($filter['con2'])?$filter['con2']:array();
			$con3 = isset($filter['con3'])?$filter['con3']:array();
			//规则1
			if((empty($con1[0]) && $con1[0]!=0) || empty($con1[1] || empty($con1[2]))){//下标：0:最小百分比 1:最大百分比 2:对应概率
				$this->session->put_notice_msg('请完善规则1的内容！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_edit?ids='.$id));
			}elseif($con1[0] >= $con1[1]){//最小百分比大于最大百分比
				$this->session->put_notice_msg('规则1中最大百分比必须需大于最小百分比！！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_edit?ids='.$id));
			}
			//规则2
			if(empty($con2[0]) && empty($con2[1]) && empty($con2[2])){
				//$this->session->put_notice_msg('请完善规则2的内容！');
				//$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_add'));
			}elseif(empty($con2[0]) || empty($con2[1]) || empty($con2[2])){
				$this->session->put_notice_msg('请完善规则2的内容！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_edit?ids='.$id));
			}elseif($con2[0] >= $con2[1]){
				$this->session->put_notice_msg('规则2中最大百分比必须需大于最小百分比！！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_edit?ids='.$id));
			}
			//规则三
			if(empty($con3[0]) && empty($con3[1]) && empty($con3[2])){
				//$this->session->put_notice_msg('请完善规则3的内容！');
				//$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_add'));
			}elseif(empty($con3[0]) || empty($con3[1]) || empty($con3[2])){
				$this->session->put_notice_msg('请完善规则3的内容！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_edit?ids='.$id));
			}elseif($con3[0] >= $con3[1]){
				$this->session->put_notice_msg('规则3中最大百分比必须需大于最小百分比！！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_edit?ids='.$id));
			}
			//比较
			if(!empty($con3[0]) && !empty($con2[1])){
				if($con3[0] <= $con2[1]){
					$this->session->put_notice_msg('规则3最小百分比需大于规则2最大百分比！');
					$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_edit?ids='.$id));
				}
			}
			if(!empty($con2[0]) && !empty($con1[1])){
				if($con2[0] <= $con1[1]){
					$this->session->put_notice_msg('规则2最小百分比需大于规则1最大百分比！');
					$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_edit?ids='.$id));
				}
			}
			if(!empty($con3[0]) && !empty($con1[1])){
				if($con3[0] <= $con1[1]){
					$this->session->put_notice_msg('规则3最小百分比需大于规则1最大百分比！');
					$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_edit?ids='.$id));
				}
			}
			//比例百分比
			if(!empty($con1[2] && !empty($con2[2]) && !empty($con3[2]))){
				if($con1[2]+$con2[2]+$con3[2]!=100){
					$this->session->put_notice_msg('有效规则总概率需等于100%！');
					$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_edit?ids='.$id));
				}
			}elseif($con1[2] && $con2[2]){
				if($con1[2]+$con2[2]!=100){
					$this->session->put_notice_msg('有效规则总概率需等于100%！');
					$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_edit?ids='.$id));
				}
			}
			elseif($con1[2] && $con3[2]){
				if($con1[2]+$con3[2]!=100){
					$this->session->put_notice_msg('有效规则总概率需等于100%！');
					$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_edit?ids='.$id));
				}
			}elseif($con1[2] != 100){
				$this->session->put_notice_msg('有效规则总概率需等于100%！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/sj_edit?ids='.$id));
			}
			$config = array_merge(array('con1'=>$con1),array('con2'=>$con2),array('con3'=>$con3));
			$data['cut_config'] = serialize($config);
			$res = $this->db->update('okpay_activities',$data,array('id'=>$id));
			$message= ($res)?
				$this->session->put_success_msg('已更新数据！'):
				$this->session->put_notice_msg('此次数据更新失败！');
			$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
			die;
		}
		//根据id获取单条记录
		$this->load->model('okpay/okpay_activities_model');
		$res = $this->okpay_activities_model->get($id);
		$config = unserialize($res['cut_config']);
		//获取公众号下的酒店
		$this->load->model ( 'hotel/hotel_model' );
		$filterH = array('inter_id'=>$inter_id);
		if(!empty($this->session->get_admin_hotels())){
			$filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
		}
        //不执行日
        $no_exec_day = isset($res['no_exec_day'])&&!empty($res['no_exec_day'])?explode(',',$res['no_exec_day']):array();
        //限制
        $gift_limit = isset($res['gift_limit'])&&!empty($res['gift_limit'])?explode('|',$res['gift_limit']):array();
		$hotels = $this->hotel_model->get_hotel_hash ( $filterH );
		$hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
		$keys = array_keys($hotels);
		//获取公众号下的场景
		$this->load->model('okpay/okpay_type_model');
		$list = $this->okpay_type_model->get_hotel_okpay_type_list($inter_id,$res['hotel_id']);//先获取第一家酒店下的场景
		$view_params = array(
			'id'	=> $id,
			'posts'	=> $res,
			'config'=> $config,
			'hotel' => $hotels,
			'type'  => $list,
            'no_exec_day'=>$no_exec_day,
            'gift_limit' =>$gift_limit
		);
		$html= $this->_render_content($this->_load_view_file('sj_edit'), $view_params, TRUE);
		echo $html;
	}
	
	public function delete(){
		$model_name= $this->main_model_name();
		$model= $this->_load_model($model_name);
		$pk= $model->table_primary_key();
		$ids = $this->input->get('ids');
		
		
		$inter_id= $this->session->get_admin_inter_id();
		$result= $model->delete($ids,$inter_id,$hotel_id);
		
		$message= ($result)?$this->session->put_success_msg('已删除数据！'):$this->session->put_notice_msg('此次数据删除失败！');
		
		$this->_log($model);
		$this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
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
