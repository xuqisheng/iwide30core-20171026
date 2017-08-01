<?php
/**
 * @desc 	餐厅管理
 * @author 	Shacaisheng
 * @date   	2017/02/22
 * @version	1.0
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Restaurant extends MY_Admin {

	protected $label_module= '预约';
	protected $label_controller= '餐厅管理';
	protected $label_action= '分销';
	protected $admin_profile;
	function __construct()
	{
		parent::__construct();
		$this->admin_profile = $this->session->userdata('admin_profile');
        $this->load->helper('appointment');
	}
	
	protected function main_model_name()
	{
		return 'appointment/appointment_model';
	}


	/**
	 * 首页列表
	 */
	public function index()
	{
		//查询餐厅信息
		$param = $this->input->get();
		$filter['wd'] = $param['wd'] ? addslashes($param['wd']) : '';
        $filter['inter_id'] = $this->admin_profile['inter_id'];
		$per_page = 15;
		$cur_page = $param['page'] ? intval($param['page']) : 1;
		$this->load->model('appointment/appointment_dining_room_model');
		$this->load->model('hotel/hotel_model');

		//查询总记录数
        $total = $this->appointment_dining_room_model->get_count($filter);

        //分页
        $arr_page = get_page($total, $cur_page, $per_page);

		//查询数据列表
		$data = $this->appointment_dining_room_model->get_list($filter,'',$cur_page,$per_page);
        if (!empty($data))
        {
            $this->load->model('appointment/appointment_dining_room_model');
            $this->load->model('appointment/appointment_desk_sales_model');
            $this->load->model('appointment/appointment_time_sales_model');
            foreach ($data as $key => $value)
            {
                //查询酒店名称
                $hotel = $this->hotel_model->get_hotel_detail($value['inter_id'],$value['hotel_id']);
                $value['hotel_name'] = $hotel['name'];
                //查询排队数
                $value['take_num'] = $this->appointment_desk_sales_model->get_count($value['dining_room_id']);
                //查询订座数
                $value['offer_num'] = $this->appointment_time_sales_model->get_count($value['dining_room_id']);

                $data[$key] = $value;
            }
        }

		//设置分页
        if ($filter)
        {
            $http_build_query = http_build_query($filter).'&';
        }
        $url = site_url('/appointment/restaurant/index?'.$http_build_query);
        $pagehtml = pagehtml($total, $cur_page, $arr_page['page_total'], $url);

        $return = array(
            'pagehtml'  => $pagehtml,
            'list'      => $data,
            'param'      => $param,
        );

		echo $this->_render_content($this->_load_view_file('index'), $return, TRUE);
	}

	/**
	 * 添加餐厅
	 */
	public function add()
	{
		if($this->admin_profile['inter_id']== FULL_ACCESS)
		{
			$this->session->put_notice_msg('超管!');
			$this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
		};
		$post = $this->input->post();
		//提交数据
		if (!empty($post['dosubmit']))
		{
			if(empty($post['shop_name']) && empty($post['shop_id']))
			{
				$this->session->put_notice_msg('名字不能为空！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/add'));
			}

			$this->load->model('appointment/appointment_dining_room_model');
			$this->load->model('roomservice/roomservice_shop_model');
			$this->load->model('appointment/appointment_desk_type_model');
			$this->load->model('appointment/appointment_opentime_model');

            //校验是否重复添加餐厅
            if (!empty($post['shop_id']) && $post['shop_status'] == 1)
            {
                $check_where = array(
                    'shop_id' => intval($post['shop_id']),
                );
                $check_shop = $this->appointment_dining_room_model->check_shop($check_where);
            }
            else
            {
                $check_where = array(
                    'hotel_id'  => intval($post['hotel_id']),
                    'shop_name' => addslashes($post['shop_name']),
                );
                $check_shop = $this->appointment_dining_room_model->check_shop($check_where);
            }

            if (!empty($check_shop))
            {
                $this->session->put_notice_msg('不能重复添加店铺哦');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/add'));
            }

            //获取店铺信息
			if (!empty($post['shop_id']) && $post['shop_status'] == 1)
			{
				$shop = $this->roomservice_shop_model->get_shop_info(intval($post['shop_id']));
			}

			//添加餐厅预约配置
            $insert_dining_room = array(
                'inter_id' 		=> $this->admin_profile['inter_id'],
                'shop_id'	    => $shop ? $shop['shop_id'] : 0,
                'hotel_id'	    => !empty($shop['hotel_id']) ? $shop['hotel_id'] : $post['hotel_id'],
                'shop_status'	=> $post['shop_status'],
                'book_style'	=> $post['book_style'],
                'unit' 			=> $post['unit'],
                'toplimit' 		=> trim($post['toplimit']),
                'start_time' 	=> trim($post['start_time']),
                'end_time' 		=> trim($post['end_time']),
                'give_info' 	=> $post['give_info'],
                'book_day' 		=> trim($post['book_day']),
                'add_time' 		=> date('Y-m-d H:i:s'),
                'shop_name'     => !empty($shop['shop_name']) ? $shop['shop_name'] : $post['shop_name'],
                'shop_tel' 		=> $post['shop_tel'],
                'shop_address' 	=> $post['shop_address'],
                'shop_profiles' => $post['shop_profiles'],
                'shop_image' 	=> $post['shop_image'],
                'msgsaler'	    => trim($post['msgsaler']),
            );

			$post['time_range'] = json_decode($post['time_range'],true);
            $open_time = array();
			if (is_array($post['time_range']) && !empty($post['time_range']))
			{
                $post['time_range'] = array_values($post['time_range']);
                foreach ($post['time_range'] as $value)
                {
                    $open_time[] = $value['start_time'].'-'.$value['end_time'];
                }
			}

            if (!empty($open_time))
            {
                $insert_dining_room['open_time'] = implode(',',$open_time);
            }
            $dining_room_id = $this->appointment_dining_room_model->insert($insert_dining_room);

            //添加营业时间
            if (!empty($post['time_range']))
            {
                foreach ($post['time_range'] as $value)
                {
                    $value['dining_room_id'] 	= $dining_room_id;
                    $value['wait_time'] 		= trim($value['wait_time']);
                    $this->appointment_opentime_model->insert($value);
                }
            }

			//添加餐厅桌型
			$post['desk_list'] = json_decode($post['desk_list'],true);
			if ($post['desk_list'])
			{
                $post['desk_list'] = array_values($post['desk_list']);
				foreach ($post['desk_list'] as $value)
				{
					$value['dining_room_id'] 	= $dining_room_id;
					$value['min_num'] 			= trim($value['min_num']);
					$value['max_num'] 			= trim($value['max_num']);
					$value['stock'] 			= trim($value['stock']);
					$this->appointment_desk_type_model->insert($value);
				}
			}

			$dining_room_id > 0 ?
				$this->session->put_success_msg('新增成功') :
                $this->session->put_notice_msg('新增失败');

            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
		}
		//展示模板
		else
		{
			$filter = array();
			$this->load->model('roomservice/roomservice_shop_model');
			$this->load->model('hotel/hotel_model');

			if(!empty($this->admin_profile['inter_id']))
			{
				$filter['hotel_id'] = $this->session->get_admin_hotels();
                $filter['inter_id'] = $this->admin_profile['inter_id'];
			}

			//获得关联店铺
			$shops = $this->roomservice_shop_model->get_list($filter);
			//获得公众号下酒店
			$hotels = $this->hotel_model->get_hotel_hash(array('inter_id'=>$this->admin_profile['inter_id']));
			$hotels = $this->hotel_model->array_to_hash($hotels, 'name', 'hotel_id');
			$view_params = array(
				'shops' 	=> $shops,
				'hotels' 	=> $hotels,
			);

			echo $this->_render_content($this->_load_view_file('add'), $view_params, TRUE);
		}
	}


	/**
	 * 编辑餐厅
	 */
	public function edit()
	{
		if($this->admin_profile['inter_id']== FULL_ACCESS)
		{
			$this->session->put_notice_msg('超管!');
			$this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
		};
		$post = $this->input->post();
		$get = $this->input->get();

        $this->load->model('appointment/appointment_dining_room_model');
        $this->load->model('roomservice/roomservice_shop_model');
        $this->load->model('appointment/appointment_desk_type_model');
        $this->load->model('appointment/appointment_opentime_model');
		//提交数据
		if (!empty($post['dosubmit']))
		{
			if(empty($post['shop_name']) && empty($post['shop_id']))
			{
				$this->session->put_notice_msg('名字不能为空！');
				$this->_redirect(EA_const_url::inst()->get_url('*/*/edit?ids='.$get['ids']));
			}

            //校验是否重复添加店铺
            if (!empty($post['shop_id']) && $post['shop_status'] == 1)
            {
                $check_where = array(
                    'shop_id' => intval($post['shop_id']),
                );
                $check_shop = $this->appointment_dining_room_model->check_shop($check_where);
            }
            else
            {
                $check_where = array(
                    'hotel_id'  => intval($post['hotel_id']),
                    'shop_name' => addslashes($post['shop_name']),
                );
                $check_shop = $this->appointment_dining_room_model->check_shop($check_where);
            }

            if (!empty($check_shop) && $check_shop['dining_room_id'] != $post['dining_room_id'])
            {
                $this->session->put_notice_msg('不能重复添加店铺哦');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/edit?ids='.$get['ids']));
            }

            //更改餐厅预约配置
            $insert_dining_room = array(
                'inter_id' 		=> $this->admin_profile['inter_id'],
                'shop_status'	=> $post['shop_status'],
                'book_style'	=> $post['book_style'],
                'unit' 			=> $post['unit'],
                'toplimit' 		=> trim($post['toplimit']),
                'start_time' 	=> trim($post['start_time']),
                'end_time' 		=> trim($post['end_time']),
                'give_info' 	=> $post['give_info'],
                'book_day' 		=> trim($post['book_day']),
                'shop_tel' 		=> $post['shop_tel'],
                'shop_address' 	=> $post['shop_address'],
                'shop_profiles' => $post['shop_profiles'],
                'shop_image' 	=> $post['shop_image'],
                'shop_name' 	=> $post['shop_name'],
                'hotel_id'	    => $post['hotel_id'],
                'msgsaler'	    => trim($post['msgsaler']),
            );

			if (!empty($post['shop_id']) && $post['shop_status'] == 1)
			{
				$shop = $this->roomservice_shop_model->get_shop_info(intval($post['shop_id']));
                $insert_dining_room['shop_id'] = $shop['shop_id'];
                $insert_dining_room['hotel_id'] = $shop['hotel_id'];
                $insert_dining_room['shop_name'] = $shop['shop_name'];
			}

			//添加/更改营业时间
			$post['time_range'] = json_decode($post['time_range'],true);
            $open_time = array();
			if (is_array($post['time_range']) && !empty($post['time_range']))
			{
                $post['time_range'] = array_values($post['time_range']);

				foreach ($post['time_range'] as $value)
				{
                    $open_time[] = $value['start_time'].'-'.$value['end_time'];
                    if (!empty($value['opentime_id']))
                    {
                        $opentime_id = $value['opentime_id'];
                        unset($value['opentime_id']);
                        $this->db->update('appointment_opentime',$value,array('opentime_id'=>intval($opentime_id)));
                    }
                    else
                    {
                        $value['dining_room_id'] 	= intval($post['dining_room_id']);
                        $value['wait_time'] 		= trim($value['wait_time']);
                        $this->appointment_opentime_model->insert($value);
                    }
				}
			}

			//添加/更改餐厅桌型
			$post['desk_list'] = json_decode($post['desk_list'],true);
			if ($post['desk_list'])
			{
                $post['desk_list'] = array_values($post['desk_list']);
				foreach ($post['desk_list'] as $value)
				{
                    if (!empty($value['desk_type_id']))
                    {
                        $desk_type_id = $value['desk_type_id'];
                        unset($value['desk_type_id']);
                        $this->db->update('appointment_desk_type',$value,array('desk_type_id'=>intval($desk_type_id)));
                    }
                    else
                    {
                        $value['dining_room_id'] 	= intval($post['dining_room_id']);
                        $value['min_num'] 			= trim($value['min_num']);
                        $value['max_num'] 			= trim($value['max_num']);
                        $value['stock'] 			= trim($value['stock']);
                        $this->appointment_desk_type_model->insert($value);
                    }
				}
			}

            if (!empty($open_time))
            {
                $insert_dining_room['open_time'] = implode(',',$open_time);
            }
            $result = $this->db->update('appointment_dining_room',$insert_dining_room,array('dining_room_id'=>intval($post['dining_room_id'])));

            $result > 0 ?
				$this->session->put_success_msg('新增成功') :
				$this->session->put_notice_msg('新增失败');

			$this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
		}
		//展示模板
		else
		{
			$filter = array();
			$this->load->model('hotel/hotel_model');

			if(!empty($this->admin_profile['inter_id']))
			{
				$filter['hotel_id'] = $this->session->get_admin_hotels();
                $filter['inter_id'] = $this->admin_profile['inter_id'];
			}

			//餐厅配置
			$rest = $this->appointment_dining_room_model->get_one(intval($get['ids']));

            //餐厅桌型
            $desk_type = $this->appointment_desk_type_model->getby_dining_room_id($get['ids']);

            //营业时段
            $opentime = $this->appointment_opentime_model->getby_dining_room_id($get['ids']);

			//获得关联店铺
			$shops = $this->roomservice_shop_model->get_list($filter);
			//获得公众号下酒店
			$hotels = $this->hotel_model->get_hotel_hash(array('inter_id'=>$this->admin_profile['inter_id']));
			$hotels = $this->hotel_model->array_to_hash($hotels, 'name', 'hotel_id');

            //读取分销员信息
            $hotel_id = $rest['hotel_id'];
            $this->load->model('distribute/qrcodes_model');
            $query = $this->qrcodes_model->get_salers($rest['inter_id'],1,'','',NULL,$hotel_id,NULL,2);
            $salers = $query->result_array();
            $tmp = array();
            $saler_name = array();
            $msgsaler_ids = !empty($rest['msgsaler'])?explode(',',$rest['msgsaler']):array();
            if(!empty($salers)){
                foreach($salers as $k=>$v){
                    $tmp[$v['qrcode_id']] = $v['name'];
                    if(in_array($v['qrcode_id'],$msgsaler_ids)){
                        $saler_name[] = $v['name'];
                    }
                }
            }
            $salers = $tmp;

			$view_params = array(
				'id' 	    => $get['ids'],
				'shops' 	=> $shops,
				'hotels' 	=> $hotels,
				'rest' 		=> $rest,
				'desk_type'	=> $desk_type,
				'opentime'	=> $opentime,
				'salers'	=> $salers,
                'show_saler_name' => !empty($saler_name)?implode(',',$saler_name):'',
			);

			echo $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
		}
	}

    /**
     * 更改状态接口
     */
    public function change_status()
    {
        $post = $this->input->post();
        $dining_room_id = intval($post['dining_room_id']);
        $type =  $post['type'];

        $book_style_type = $post['book_style'];
        if($dining_room_id)
        {
            $this->load->model('appointment/appointment_dining_room_model');

            $rest = $this->appointment_dining_room_model->get_one($dining_room_id);
            $where['dining_room_id'] = $dining_room_id;
            //取号
            if ($type == 1)
            {
                if ($book_style_type == 'off')//取号+预约
                {
                    $book_style = $rest['book_style'] == 1 ? 3 : 0;
                }
                else
                {
                    $book_style = $rest['book_style'] == 0 ? 2 : 1;
                }
            }
            //订座
            else
            {
                if ($book_style_type == 'off')//取号+预约
                {
                    $book_style = $rest['book_style'] == 1 ? 2 : 0;
                }
                else
                {
                    $book_style = $rest['book_style'] == 0 ? 3 : 1;
                }
            }
            $update = $this->db->update('appointment_dining_room',array('book_style' => $book_style), $where);
        }

        if($update > 0)
        {
            ajax_return(1,'成功');
        }
        else
        {
            ajax_return(0,'失败');
        }
    }

    /**
    * ajax获取对应酒店的分销员信息
    * */
    public function get_saler_info(){
        $hotel_id = $this->input->post('hotel_id',true);
        $inter_id= $this->session->get_admin_inter_id();
        if($inter_id== FULL_ACCESS){
            echo json_encode(array('errcode'=>1,'msg'=>'error'));
        }
        $this->load->model('distribute/qrcodes_model');
        $query = $this->qrcodes_model->get_salers($inter_id,1,'','',NULL,$hotel_id,NULL,2);
        $res = $query->result_array();
        if(empty($res)){
            echo json_encode(array('errcode'=>1,'msg'=>'该酒店暂时无分销员'));
            die;
        }else{
            echo json_encode(array('errcode'=>0,'res'=>$res));
            die;
        }

    }

}
