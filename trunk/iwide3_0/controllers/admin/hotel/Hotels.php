<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Hotels extends MY_Admin {
	protected $label_module = NAV_HOTELS;
	protected $label_controller = '酒店列表';
	protected $label_action = '';
	function __construct() {
		parent::__construct ();
	}
	protected function main_model_name() {
		return 'hotel/hotel_ext_model';
	}
	public function index() {
        $inter_id = $this->session->get_admin_inter_id ();
        if ($inter_id == FULL_ACCESS)
            $filter = array ();
        else if ($inter_id)
            $filter = array (
                'inter_id' => $inter_id
            );
        else
            $filter = array (
                'inter_id' => 'deny'
            );
        $entity_id = $this->session->get_admin_hotels ();
        $hotel_id = $this->input->get ( 'h' );
        $this->load->model ( 'hotel/hotel_model' );
        $data = array (
            'hotel_id' => $hotel_id
        );

        if (! empty ( $entity_id )) {
            $hotel_ids = explode ( ',', $entity_id );
            $data ['hotels'] = $this->hotel_model->get_hotel_by_ids ( $filter ['inter_id'], $entity_id );
            if (! empty ( $hotel_id ) && in_array ( $hotel_id, $hotel_ids )) {
                $filter ['hotel_id'] = $hotel_id;
            } else {
                $filter ['hotel_id'] = $hotel_ids;
            }
        } else {
            $data ['hotels'] = $this->hotel_model->get_all_hotels ( $filter ['inter_id'] );
            if (! empty ( $hotel_id )) {
                $filter ['hotel_id'] = $hotel_id;
            }
        }
        // print_r($filter);die;

        /* 兼容grid变为ajax加载加这一段 */
        if (is_ajax_request ())
            // 处理ajax请求，参数规格不一样
        $get_filter = $this->input->post ();
        else
            $get_filter = $this->input->get ( 'filter' );

        if (! $get_filter)
            $get_filter = $this->input->get ( 'filter' );

        if (is_array ( $get_filter ))
            $filter = $get_filter + $filter;
        /* 兼容grid变为ajax加载加这一段 */

        $filter ['status'] = array (
            1,
            2
        );

        $this->_grid ( $filter,$data );
	}
	public function grid() {
		$inter_id = $this->session->get_admin_inter_id ();
		if ($inter_id == FULL_ACCESS)
			$filter = array ();
		else if ($inter_id)
			$filter = array (
					'inter_id' => $inter_id 
			);
		else
			$filter = array (
					'inter_id' => 'deny' 
			);
		$entity_id = $this->session->get_admin_hotels ();
        $hotel_id = $this->input->get ( 'h' );
        $this->load->model ( 'hotel/hotel_model' );
        $data = array (
            'hotel_id' => $hotel_id
        );

        if (! empty ( $entity_id )) {
            $hotel_ids = explode ( ',', $entity_id );
            $data ['hotels'] = $this->hotel_model->get_hotel_by_ids ( $filter ['inter_id'], $entity_id );
            if (! empty ( $hotel_id ) && in_array ( $hotel_id, $hotel_ids )) {
                $filter ['hotel_id'] = $hotel_id;
            } else {
                $filter ['hotel_id'] = $hotel_ids;
            }
        } else {
            $data ['hotels'] = $this->hotel_model->get_all_hotels ( $filter ['inter_id'],1 );
            if (! empty ( $hotel_id )) {
                $filter ['hotel_id'] = $hotel_id;
            }
        }
		// print_r($filter);die;
		
		/* 兼容grid变为ajax加载加这一段 */
		if (is_ajax_request ())
			// 处理ajax请求，参数规格不一样
			$get_filter = $this->input->post ();
		else
			$get_filter = $this->input->get ( 'filter' );
		
		if (! $get_filter)
			$get_filter = $this->input->get ( 'filter' );
		
		if (is_array ( $get_filter ))
			$filter = $get_filter + $filter;
			/* 兼容grid变为ajax加载加这一段 */

		$this->_grid ( $filter,$data );
	}
	public function edit() {
		$this->label_action = '酒店管理';
		$this->_init_breadcrumb ( $this->label_action );
		
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$id = intval ( $this->input->get ( 'ids' ) );
		$this->load->model ( 'hotel/hotel_ext_model' );
		$this->load->model ( 'hotel/Hotel_model' );
		$arounds='';
		$retreat_time=array('start'=>'0000','end'=>'2300');
		if ($id) {
			// for edit page.
			// $model= $this->hotel_ext_model->load($id);
			$model = $model->load ( $id );
			if (! $model){
				redirect(site_url('hotel/hotels/index'));
			}
			$fields_config = $model->get_field_config ( 'form' );
			$detail_field = array ();
			if (count ( $detail_field ) > 0) {
				$detail_field = $detail_field [0] ['attr_value'];
			} else {
				$detail_field = '';
			}
			$hotel=$this->Hotel_model->get_hotel_detail($this->session->get_admin_inter_id (),$id,array('not_del'=>1),NULL);
			if ($hotel){
				$arounds=$hotel['arounds'];
				if(!empty($hotel['retreat_time'])){
					$retreat_time=json_decode($hotel['retreat_time'],true);
				}
			}else{
				redirect(site_url('hotel/hotels/index'));
			}
		} else {
			// for add page.
			// $model= $this->hotel_ext_model->load($id);
			$model = $model->load ( $id );
			if (! $model)
				$model = $this->_load_model ();
			$fields_config = $model->get_field_config ( 'form' );
			$detail_field = '';
		}
		
// 		$this->db->where ( array (
// 				'inter_id' => $this->session->get_admin_inter_id (),
// 				'type' => 'hotel_service',
// 				'hotel_id' => $id 
// 		) );
// 		$hotel_ser = $this->db->get ( 'hotel_images' )->result_array ();
		$hotel_ser = $this->Hotel_model->get_imgs('hotel_service',$this->session->get_admin_inter_id (),$id);
		$hotel_ser = array_column ( $hotel_ser, 'image_url' );
		$this->db->where ( array (
				'inter_id' => 'defaultimg',
				'type' => 'hotel_service' 
		) );
		$services = $this->db->get ( 'hotel_images' )->result ();
		
		// 获取相册数组
		// $gallery= $model->get_gallery();
		$view_params = array (
				'model' => $model,
				'fields_config' => $fields_config,
				'check_data' => FALSE,
				'detail_field' => $detail_field,
				'services' => $services,
				'hotel_ser' => $hotel_ser,
				'arounds'=>$arounds,
				'retreat_time'=>$retreat_time
		)
		// 'gallery'=> $gallery,
		;
		
		$html = $this->_render_content ( $this->_load_view_file ( 'edit' ), $view_params, TRUE );
		// echo $html;die;
		echo $html;
	}
	public function edit_post() {
		$this->label_action = '信息维护';
		$this->_init_breadcrumb ( $this->label_action );
		
		$model_name = $this->main_model_name ();
		$model = $this->_load_model ( $model_name );
		$pk = $model->table_primary_key ();
		
		$this->load->library ( 'form_validation' );
		$post = $this->input->post ();
		$jingwei = explode(',',$post['jingwei']);
		$post['latitude'] = $jingwei[1];
		$post['longitude'] = $jingwei[0];
		$_POST['latitude'] = $jingwei[1];
		$_POST['longitude'] = $jingwei[0];
		$labels = $model->attribute_labels ();
		$base_rules = array (
				'name' => array (
						'field' => 'name',
						'label' => $labels ['name'],
						'rules' => 'trim|required' 
				),
				'address' => array (
						'field' => 'address',
						'label' => $labels ['address'],
						'rules' => 'trim|required' 
				),
				'latitude' => array (
						'field' => 'latitude',
						'label' => $labels ['latitude'],
						'rules' => 'trim|required' 
				),
				'longitude' => array (
						'field' => 'longitude',
						'label' => $labels ['longitude'],
						'rules' => 'trim|required' 
				),
				'tel' => array (
						'field' => 'tel',
						'label' => $labels ['tel'],
						'rules' => 'trim' 
				),
				'intro' => array (
						'field' => 'intro',
						'label' => $labels ['intro'],
						'rules' => 'trim' 
				),
				'short_intro' => array (
						'field' => 'short_intro',
						'label' => $labels ['short_intro'],
						'rules' => 'trim' 
				),
				'intro_img' => array (
						'field' => 'intro_img',
						'label' => $labels ['intro_img'],
						'rules' => 'trim' 
				),
				'services' => array (
						'field' => 'services',
						'label' => $labels ['services'],
						'rules' => 'trim' 
				),
				'email' => array (
						'field' => 'email',
						'label' => $labels ['email'],
						'rules' => 'trim' 
				),
				'fax' => array (
						'field' => 'fax',
						'label' => $labels ['fax'],
						'rules' => 'trim' 
				),
				'star' => array (
						'field' => 'star',
						'label' => $labels ['star'],
						'rules' => 'trim' 
				),
				'country' => array (
						'field' => 'country',
						'label' => $labels ['country'],
						'rules' => 'trim|required' 
				),
				'province' => array (
						'field' => 'province',
						'label' => $labels ['province'],
						'rules' => 'trim|required' 
				),
				'status' => array (
						'field' => 'status',
						'label' => $labels ['status'],
						'rules' => 'trim' 
				),
				'web' => array (
						'field' => 'web',
						'label' => $labels ['web'],
						'rules' => 'trim' 
				),
				'city' => array (
						'field' => 'city',
						'label' => $labels ['city'],
						'rules' => 'trim|required' 
				),
				'sort' => array (
						'field' => 'sort',
						'label' => $labels ['sort'],
						'rules' => 'trim' 
				),
				'book_policy' => array (
						'field' => 'book_policy',
						'label' => $labels ['book_policy'],
						'rules' => 'trim' 
				),
				'hotel_id' => array (
						'field' => 'hotel_id',
						'label' => $labels ['hotel_id'],
						'rules' => 'trim' 
				),
				'inter_id' => array (
						'field' => 'inter_id',
						'label' => $labels ['inter_id'],
						'rules' => 'trim|required' 
				),
				'invoice' => array (
						'field' => 'invoice',
						'label' => $labels ['invoice'],
						'rules' => 'trim' 
				) 
		);
		
		// 检测并上传文件。
		$post = $this->_do_upload ( $post, 'intro_img' );
		
		$adminid = $this->session->get_admin_id ();
		
		if (!empty($post['arounds'])){
			$post['arounds']=str_replace('script', '', $post['arounds']);
		}
		if (!empty($post['retreat_time'])){
			$post['retreat_time']=json_encode($post['retreat_time']);
		}
		if (empty ( $post [$pk] )) {
			// add data.
			$this->form_validation->set_rules ( $base_rules );
			
			if ($this->form_validation->run () != FALSE) {
				$post ['add_date'] = date ( 'Y-m-d H:i:s' );
				$post ['add_user'] = $adminid;
				
				$result = $model->m_sets ( $post )->m_save ( $post );
				$model->save_services ( $result );
				$message = ($result) ? $this->session->put_success_msg ( '已新增数据！' ) : $this->session->put_notice_msg ( '此次数据保存失败！' );
				// $this->_log($model);
				$this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/index' ) );
			} else
				$model = $this->_load_model ();
		} else {
			$this->form_validation->set_rules ( $base_rules );
			if ($this->form_validation->run () != FALSE) {
				$post ['last_update_time'] = date ( 'Y-m-d H:i:s' );
				$post ['last_update_user'] = $adminid;
				
				$result = $model->load ( $post [$pk] )->m_sets ( $post )->m_save ( $post );
				$model->save_services ( $result );
				$message = ($result) ? $this->session->put_success_msg ( '已保存数据！' ) : $this->session->put_notice_msg ( '此次数据修改失败！' );
				$this->_log ( $model );
				$this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/index' ) );
			} else
				$model = $model->load ( $post [$pk] );
		}
		$this->db->where ( array (
				'inter_id' => $this->session->get_admin_inter_id (),
				'type' => 'hotel_service',
				'hotel_id' => $post [$pk] 
		) );
		$hotel_ser = $this->db->get ( 'hotel_images' )->result_array ();
		$hotel_ser = array_column ( $hotel_ser, 'image_url' );
		$this->db->where ( array (
				'inter_id' => 'defaultimg',
				'type' => 'hotel_service' 
		) );
		$services = $this->db->get ( 'hotel_images' )->result ();
		// 验证失败的情况
		$validat_obj = _get_validation_object ();
		$message = $validat_obj->error_html ();
		// 页面没有发生跳转时用寄存器存储消息
		$this->session->put_error_msg ( $message, 'register' );
		
		$fields_config = $model->get_field_config ( 'form' );
		$view_params = array (
				'model' => $model,
				'fields_config' => $fields_config,
				'check_data' => TRUE,
				'services' => $services,
				'hotel_ser' => $hotel_ser 
		);
		$html = $this->_render_content ( $this->_load_view_file ( 'edit' ), $view_params, TRUE );
		echo $html;
	}

	public function book_date(){
		$inter_id = $this->session->get_admin_inter_id();

		$this->load->model('hotel/hotel_model');

		$hotel_id = $this->input->get('h');
		$city = $this->input->get('c');
		$keyword = $this->input->get('k');

		$entity_id = $this->session->get_admin_hotels();
		if(!empty ($entity_id)){
			$hotel_ids = explode(',', $entity_id);
			$data ['hotel_select'] = $this->hotel_model->get_hotel_by_ids($inter_id, $entity_id);
			if(!empty ($hotel_id) && !in_array($hotel_id, $hotel_ids)){
				$hotel_id = 0;
			}

			$city_list = [];
			foreach($data ['hotel_select'] as $v){
				$city_list[] = $v['city'];
			}
		} else{
			$data['hotel_select'] = $this->hotel_model->get_hotels_by_params($inter_id, array(
				'keyword' => $keyword,
				'city'    => $city,
			));

			$all_hotels = $this->hotel_model->get_all_hotels($inter_id);

			$city_list = [];
			foreach($all_hotels as $v){
				$city_list[] = $v['city'];
			}

		}
		$city_list = array_unique($city_list);

		$py = array();
		$this->load->helper('string');
		foreach($city_list as $c){
			$city_py = get_first_py($c);
			$py [$city_py] [] = array(
				'city' => $c,
			);
		}
		ksort($py);

		$data['city_list'] = $py;

		$data['city'] = $city;
		$data['hotel_id'] = $hotel_id;

		$data['list'] = [];
		$map=[];
		if($city){
			$map['city']=$city;
		}
		if($keyword){
			$map['keyword']=$keyword;
		}
		if($hotel_id){
			$map['hotel_id']=$hotel_id;
		}
		$hotel_list = $this->hotel_model->get_hotels_by_params($inter_id, $map);


		array_unshift($hotel_list, ['hotel_id' => 0, 'name' => '所有酒店']);

		foreach($hotel_list as $v){
			$hotel_ids[] = $v['hotel_id'];
		}
		$pre_date_sets = $this->hotel_model->get_hotel_predate_set($inter_id, $hotel_ids);

		foreach($hotel_list as $v){
			$conf = isset($pre_date_sets[$v['hotel_id']]) ? $pre_date_sets[$v['hotel_id']] : [];
			if($conf){
				$conf['compare_name'] = $conf['compare'] == 'less' ? '前' : '后';
				$conf['curr_status'] = $conf['priority'] > -1 ? '可用' : '不可用';
				$conf['fill1'] = '每天';
				$conf['val']*=-1;
			}
			$set_config = [
				'id'           => isset($conf['id']) ? $conf['id'] : 0,
				'module'       => isset($conf['module']) ? $conf['module'] : 'HOTEL',
				'param_name'   => isset($conf['param_name']) ? $conf['param_name'] : 'BOOK_DATE_VALIDATE',
				'priority'     => isset($conf['priority']) ? $conf['priority'] : 1,
				'compare'      => isset($conf['compare']) ? $conf['compare'] : 'less',
				'hour'         => isset($conf['hour']) ? $conf['hour'] : '',
				'val'          => !empty($conf['val']) ? $conf['val'] : 0,
				'compare_name' => isset($conf['compare_name']) ? $conf['compare_name'] : '前',
				'fill1'        => '每天',
				'fill2'=>'状态：',
			];
			$data['list'][] = [
				'hotel_id'   => $v['hotel_id'],
				'name'       => $v['name'],
				'config'     => $conf,
				'set_config' => $set_config
			];
		}

		$model = $this->_load_model($this->main_model_name());
		$data ['fields_config'] = $model->date_set_grid_fields();
		$this->_render_content($this->_load_view_file('book_date'), $data, false);
	}

	public function ajax_hotel(){
		$inter_id = $this->session->get_admin_inter_id();
		$this->load->model('hotel/hotel_model');

		$city = $this->input->get('c');
		$map=[];
		if($city){
			$map['city']=$city;
		}
		$hotel_list = $this->hotel_model->get_hotels_by_params($inter_id,$map);

		echo json_encode($hotel_list);
	}

	public function quick_save_set(){
		$input = $this->input->get();
		$result = ['status' => false];
		if(is_numeric($input['hour'])){
			$inter_id = $this->session->get_admin_inter_id();
			$param = [
				'id'         => !empty($input['id']) ? $input['id'] : 0,
				'module'     => $input['module'],
				'param_name' => $input['param_name'],
				'priority'   => $input['priority'],
				'inter_id'   => $inter_id,
				'hotel_id'   => $input['hotel_id'],
			];
			unset($input['module'], $input['id'], $input['param_name'], $input['priority'], $input['hotel_id']);
//			$input['val'] = 1;
			if($input['compare'] == 'less'){
				$input['val'] *= -1;
			}
			
			$param_value['startdate'] = [$input];
			$param['param_value'] = json_encode($param_value);
			$this->load->model('hotel/hotel_config_model');
			$res = $this->hotel_config_model->replace_config($param);
			if($res){
				$result = [
					'status' => 1,
				];
				if(is_numeric($res)){
					$result['conf_id'] = $res;
				}
			}
		}
		echo json_encode($result);
	}

    public function hot_city(){    //获取热门城市

        $model = $this->_load_model ( $this->main_model_name () );

        $inter_id = $this->session->get_admin_inter_id ();
        $entity_id = $this->session->get_admin_hotels ();

        $this->load->model('hotel/Hotel_config_model');
        $config_data = $this->Hotel_config_model->get_hotel_config ( $inter_id, 'HOTEL', 0, array (
            'HOT_CITY_SEARCH',
            'FIRST_CITY_SEARCH',
            'HOT_CITY_NUM_SEARCH'
        ) );


        $view_params = array();

        if(isset($config_data['HOT_CITY_SEARCH'])){
            $view_params = array (
                'hot_city' => json_decode($config_data['HOT_CITY_SEARCH'])
            );
        }else{
            $view_params = array (
                'hot_city' => null
            );
        }

        if(!isset($config_data['FIRST_CITY_SEARCH'])){$view_params['d_city']=null;}else{$view_params['d_city']=$config_data['FIRST_CITY_SEARCH'];};
        if(!isset($config_data['HOT_CITY_NUM_SEARCH'])){$view_params['amount']=null;}else{$view_params['amount']=$config_data['HOT_CITY_NUM_SEARCH'];};

        $this->_render_content ( $this->_load_view_file ( 'hot_city' ), $view_params, false );

    }


    public function hot_city_post(){

        $inter_id = $this->session->get_admin_inter_id ();
        $post_data['module'] = 'HOTEL';
        $post_data['inter_id'] = $inter_id;

        $info=array(
          'status'=>1,
          'msg'=>'保存成功'
        );

        $this->load->model ( 'hotel/Hotel_model' );

        $city = $this->input->get('city');
        $amount = $this->input->get('amount');
        $default_city = $this->input->get('default_city');

        if(!empty($city)){
            $post_data['param_value'] = json_encode($city);
        }else{
            $post_data['param_value'] = null;
        }

        $post_data['param_name'] = 'HOT_CITY_SEARCH';
        if(!$this->Hotel_model->hot_city_post($post_data,$inter_id)) $info=array( 'status'=>0,'msg'=>'修改热门城市失败');

        $post_data['param_value'] = $default_city;
        $post_data['param_name'] = 'FIRST_CITY_SEARCH';
        if(!$this->Hotel_model->hot_city_post($post_data,$inter_id,$post_data['param_name'])) $info=array( 'status'=>0,'msg'=>'修改默认城市失败');


        $post_data['param_value'] = $amount;
        $post_data['param_name'] = 'HOT_CITY_NUM_SEARCH';
        if(!$this->Hotel_model->hot_city_post($post_data,$inter_id,$post_data['param_name'])) $info=array( 'status'=>0,'msg'=>'修改热门城市数量失败');

        echo json_encode($info);


    }


}
