<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Booking extends MY_Front {
	public $openid;
    protected $_token;
    protected $common_data;

	function __construct()
    {
		parent::__construct ();
        /*$this->session->set_userdata (array (
            'inter_id' => 'a429262687' ,
            'hotel_id' => 1026,
            'pay_code' => 9,
            'a429262687openid' => 'oX3Wojj8h46C_CN5NoRPfbXwxND8'
        ));*/
       // $this->get_Token();
        $this->load->model('wx/Access_token_model');
        $this->common_data = $this->Access_token_model->getSignPackage($this->inter_id);

        $this->load->helper('appointment');
	}

    /**
     * 餐厅首页模板
     */
    public function index()
    {
        $data['inter_id'] = $this->inter_id;
        $this->display('appointment/index',$data);
    }

    /**
     * 预订座位模板
     */
	public function offer_show()
    {
        $get = $this->input->get();
        $get['dining_room_id'] = intval($get['dining_room_id']);
        //查询餐厅信息
        $this->load->model('appointment/appointment_dining_room_model');
        $this->load->model('appointment/appointment_opentime_model');
        $dining_room = $this->appointment_dining_room_model->get_one($get['dining_room_id']);

        //检查是否非法操作
        if (($dining_room['inter_id'] != $this->inter_id))
        {
            redirect(site_url('appointment/booking/index?id='.$this->inter_id));
        }

        //查询营业时段
        $opentime = $this->appointment_opentime_model->getby_dining_room_id($get['dining_room_id']);
        $range = $text = array();
        if (!empty($opentime))
        {
            foreach ($opentime as $value)
            {
                $range[] = $value['start_time'] .'-'. $value['end_time'];
                $text[] = $value['name'];
            }
        }

        //获取酒店信息
        $hotel = $this->get_hotel_info($dining_room);

        $json_opentime = array(
            'range'     => $range,
            'text'      => $text,
            'increment' => 30,
        );
        $data = array(
            'signPackage'   => $this->common_data,
            'hotel'   => $hotel,
            'dining_room'   => $dining_room,
            'opentime'      => json_encode($json_opentime),
        );

        $this->display('appointment/offer_show',$data);
    }

    /**
     * 排队取号模板
     */
    public function take_show()
    {
        $get = $this->input->get();
        $get['dining_room_id'] = intval($get['dining_room_id']);
        //查询餐厅信息
        $this->load->model('appointment/appointment_dining_room_model');
        $this->load->model('appointment/appointment_desk_type_model');
        $this->load->model('appointment/appointment_opentime_model');
        $dining_room = $this->appointment_dining_room_model->get_one($get['dining_room_id']);

        //检查是否非法操作
        if (($dining_room['inter_id'] != $this->inter_id) || $dining_room['book_style'] == 3)
        {
            redirect(site_url('appointment/booking/index?id='.$this->inter_id));
        }

        //查询营业时段
        $desk_type = $this->appointment_desk_type_model->getby_dining_room_id($get['dining_room_id']);

        if (!empty($desk_type))
        {
            $this->load->model('appointment/appointment_desk_sales_model');
            foreach ($desk_type as $key => $item)
            {
                //查询正在排队人数
                $where = array(
                    'dining_room_id'    => $get['dining_room_id'],
                    'desk_type_id'      => $item['desk_type_id'],
                );
                $item['wait_num'] = $this->appointment_desk_sales_model->get_num_where($where);

                //预估等待时间
                $item['wait_min'] = $item['wait_num'] * intval($item['wait_time']);
                $desk_type[$key] = $item;
            }
        }

        //营业时段
        $opentime = $this->appointment_opentime_model->getby_dining_room_id($dining_room['dining_room_id']);
        if (!empty($opentime))
        {
            $dining_room['is_take'] = 0;
            foreach ($opentime as $value)
            {
                //判断配置是否可取号
                $cf_start_time = $dining_room['start_time'] * 60;
                $cf_end_time = $dining_room['end_time'] * 60;

                $start_time = strtotime(date("Y-m-d {$value['start_time']}")) - $cf_start_time;
                $end_time = strtotime(date("Y-m-d {$value['end_time']}")) - $cf_end_time;

                if ($start_time <= time() && $end_time >= time())
                {
                    $dining_room['is_take'] = 1;
                }
            }
        }
        else
        {
            ajax_return('0','尚不可取号');
        }

        //获取酒店信息
        $hotel = $this->get_hotel_info($dining_room);

        $data = array(
            'hotel'         => $hotel,
            'dining_room'   => $dining_room,
            'desk_type'     => $desk_type,
            'signPackage'   => $this->common_data,
        );

        $this->display('appointment/take_show',$data);
    }

    /**
     * 我的预约详情模板
     */
    public function offer_detail()
    {
        $get = $this->input->get();
        $get['order_id'] = intval($get['order_id']);
        //查询餐厅信息
        $this->load->model('appointment/appointment_dining_room_model');
        $this->load->model('appointment/appointment_order_model');

        $openid		= $this->session->userdata($this->inter_id."openid");

        //查询客户预约
        $order = $this->appointment_order_model->get_one($get['order_id']);

        //检查是否非法操作
        if (empty($order) || $order['openid'] != $openid || $order['book_type'] == 2)
        {
            redirect(site_url('appointment/booking/index?id='.$this->inter_id));
        }

        $dining_room = $this->appointment_dining_room_model->get_one($order['dining_room_id'],'unit,shop_name,shop_address,inter_id,hotel_id');

        $order = !empty($dining_room) ? array_merge($order,$dining_room) : $order;

        //获取酒店信息
        $hotel = $this->get_hotel_info($dining_room);

        $order['hotel'] = $hotel;
        $order['signPackage'] = $this->common_data;
        $this->display('appointment/offer_detail',$order);
    }

    /**
     * 我的取号详情模板
     */
    public function take_detail()
    {
        $get = $this->input->get();
        $get['order_id'] = intval($get['order_id']);
        //查询餐厅信息
        $this->load->model('appointment/appointment_order_model');
        $this->load->model('appointment/appointment_dining_room_model');

        $openid		= $this->session->userdata($this->inter_id."openid");

        //查询客户预约
        $order = $this->appointment_order_model->get_one($get['order_id']);

        //检查是否非法操作
        if (empty($order) || $order['openid'] != $openid || $order['book_type'] == 1)
        {
            redirect(site_url('appointment/booking/index?id='.$this->inter_id));
        }

        $dining_room = $this->appointment_dining_room_model->get_one($order['dining_room_id'],'unit,shop_name,shop_address,inter_id,hotel_id');

        $order = !empty($dining_room) ? array_merge($order,$dining_room) : $order;

        //查询排队等待数
        $where = array($order['dining_room_id'],$order['desk_type_id'],"{$order['book_add_time']}");
        $order['wait_num'] = $this->appointment_order_model->count_wait_num($where);

        //获取酒店信息
        $hotel = $this->get_hotel_info($dining_room);

        $order['hotel'] = $hotel;
        $order['signPackage'] = $this->common_data;

        $this->display('appointment/take_detail',$order);
    }
    /**
     * 跳转到我的预约，
     */
    public function my_booking()
    {
        $get = $this->input->get();
        $get['inter_id'] = $this->inter_id;
        $this->display('appointment/my_booking',$get);
    }



    /***-------------------预订前台接口地址-----------------***/

    /**
     * 首页餐厅列表接口
     */
    public function dining_room_list()
    {
        //查询餐厅信息
        $param = $this->input->post();

        $per_page = $param['page_show'] ? $param['page_show'] : 15;
        $cur_page = $param['page_num'] ? $param['page_num'] : 1;
        $filter['inter_id'] = $this->inter_id;

        $this->load->model('appointment/appointment_dining_room_model');
        //查询总记录数
        $total = $this->appointment_dining_room_model->dining_room_count($filter);
        $arr_page = get_page($total, $cur_page, $per_page);
        //查询数据列表
        $field = 'dining_room_id,inter_id,unit,book_style,shop_status,shop_name,shop_image,shop_profiles';
        $list = $this->appointment_dining_room_model->dining_room_list($field,$filter,$cur_page,$per_page);

        if (!empty($list))
        {
            $this->load->model('appointment/appointment_desk_sales_model');
            $this->load->model('appointment/appointment_time_sales_model');
            foreach ($list as $key => $value)
            {
                //查询预约数
                if ($value['book_style'] == 3)
                {
                    $count = $this->appointment_time_sales_model->get_num_today(array($value['dining_room_id'],date('Y-m-d')));
                    $value['url'] = site_url('appointment/booking/offer_show?id='.$this->inter_id.'&dining_room_id='.$value['dining_room_id']);
                }
                //查询排队数
                else
                {
                    $count = $this->appointment_desk_sales_model->get_count($value['dining_room_id']);
                    $value['url'] = site_url('appointment/booking/take_show?id='.$this->inter_id.'&dining_room_id='.$value['dining_room_id']);
                }
                $value['count'] = $count;
                $list[$key] = $value;
            }
        }

        $data = array(
            'list' => $list,
            'page' => $arr_page,
        );
        ajax_return(1,'成功',$data);
    }


    /**
     * 立即预约接口
     */
    public function save_order()
    {
        $post = $this->input->post();
        $book_datetime = trim($post['book_datetime']);
        $book_datetime = $book_datetime/1000;
        $insert = array(
            'book_number'   => addslashes(trim($post['book_number'])),
            'book_datetime' => date('Y-m-d H:i:s',$book_datetime),
            'book_name'     => addslashes($post['book_name']),
            'book_phone'    => addslashes($post['book_phone']),
            'book_info'     => addslashes($post['book_info']),
            'book_type'     => 1,
            'book_add_type' => 2,
            'book_op_status' => 0,
            'desk_type_id'  => 0,
            'opentime_id'   => 0,
            'dining_room_id' => 0,
            'openid'        => 0,
            'inter_id'      => 0,
            'book_add_time' => date('Y-m-d H:i:s'),
        );

        $insert['openid']		= $this->session->userdata($this->inter_id."openid");
        $insert['inter_id']	    = $this->session->userdata("inter_id");

        $this->load->model('appointment/appointment_desk_type_model');
        $this->load->model('appointment/appointment_time_sales_model');
        $this->load->model('appointment/appointment_dining_room_model');
        $this->load->model('appointment/appointment_opentime_model');
        $this->load->model('appointment/appointment_order_model');

        //查询餐厅配置
        $dining_room = $this->appointment_dining_room_model->get_one(intval($post['dining_room_id']),'dining_room_id,unit,book_style,book_day,shop_name');
        if (empty($dining_room) || ($dining_room['book_style'] == 2) || ($dining_room['book_style'] == 0))
        {
            ajax_return(0,'餐厅已停止预约');
        }

        //判断提取预约时间
        if (date('Y-m-d',$book_datetime) > date('Y-m-d',strtotime("+{$dining_room['book_day']} days")))
        {
            if ($dining_room['book_day'] == 0)
            {
                $tips = '只能提前24小时预订';
            }
            else
            {
                $tips = "只可提前{$dining_room['book_day']}天预订";
            }

            ajax_return(0,$tips);
        }

        $insert['dining_room_id'] = $dining_room['dining_room_id'];

        //检查用户是否已预订
       // $where = array($insert['openid'],$insert['dining_room_id'],'"'.date('Y-m-d 00:00:00',$book_datetime).'"','"'.date('Y-m-d 23:59:59',$book_datetime).'"');
        $where = array($insert['openid'],$insert['dining_room_id'],date('Y-m-d 00:00:00',$book_datetime),date('Y-m-d 23:59:59',$book_datetime));

        $order = $this->appointment_order_model->get_user_one($where);
        if (!empty($order))
        {
            ajax_return(400,'该时段您预约过',array('url'=>site_url('/appointment/booking/offer_detail?id='.$this->inter_id.'&order_id=').$order['order_id']));
        }

        //根据人数查询桌型ID
        $where = array($insert['dining_room_id'],$insert['book_number']);
        $desk_type = $this->appointment_desk_type_model->getby_num_range($where);
        if (empty($desk_type) || ($desk_type['stock'] < 1))
        {
            ajax_return('-1','当前桌型库存不足');
        }
        $insert['desk_type_id'] = $desk_type['desk_type_id'];
        $insert['desk_name']    = $desk_type['name'];
        $insert['desk_man']     = $this->desk_name($desk_type).'人';

        //根据时间查询时段ID
        $where = array($insert['dining_room_id'],date('H:i',$book_datetime));
        $opentime = $this->appointment_opentime_model->getby_time_range($where);
        if (empty($opentime))
        {
            ajax_return('-1','预订时间不在营业时间内');
        }
        $insert['opentime_id'] = $opentime['opentime_id'];

        //查询餐厅预约时段预约数
        $where = array($insert['dining_room_id'],$opentime['opentime_id'],date('Y-m-d',$book_datetime));
        $time_sales = $this->appointment_time_sales_model->get_num_where($where);

        if ($time_sales >= $desk_type['stock'])
        {
            ajax_return('-1','该时段库存不足');
        }

        //不存在则插入时段预约
        if ($time_sales == 0)
        {
            $insert_sales = array(
                'dining_room_id'    => $insert['dining_room_id'],
                'opentime_id'       => $insert['opentime_id'],
                'add_date'          => '"'.date('Y-m-d',$book_datetime).'"',
                'book_number'       => 0,
            );
            $this->appointment_time_sales_model->insert($insert_sales);
        }

        //开启事务
        $this->db->trans_begin();
        //插入预订信息
        $order_id = $this->appointment_order_model->insert($insert);
        //更新时段预订数
        $where = array(
           'dining_room_id' => $insert['dining_room_id'],
            'opentime_id' => $insert['opentime_id'],
            'date' => date('Y-m-d',$book_datetime),
            'stock' => $desk_type['stock']
        );
        $update = $this->appointment_time_sales_model->update_book_num($where);
        //成功 提交事务
        if ($order_id > 0 && $update > 0)
        {
            $this->db->trans_commit();

            //发送模板消息
            $insert['item'] = $insert['desk_name'].$insert['desk_man'];
            $insert['time'] = $insert['book_datetime'];
            $insert['shop_name'] = $dining_room['shop_name'];
            $this->load->model('plugins/Template_msg_model');
            $this->Template_msg_model->send_appointment_msg($insert, 'appointment_offer_success');

            //发送待接单给管理员
            $saler = $this->appointment_dining_room_model->get_shop_saler_info($insert['inter_id'],$insert['dining_room_id']);
            if($saler)
            {
                foreach($saler as $v)
                {
                    $insert['openid'] = $v['openid'];//给管理员
                    $this->Template_msg_model->send_appointment_msg($insert, 'appointment_offer_success_shop');
                }
            }

            $data['url'] = site_url('/appointment/booking/offer_detail?id='.$this->inter_id.'&order_id='.$order_id);
            ajax_return('1','预订座位成功',$data);
        }
        //回滚事务
        else
        {
            $this->db->trans_rollback();
            ajax_return('-1','服务器繁忙，预订座位失败');
        }
    }


    /**
     * 用户取消预约接口
     */
    public function cancel_order()
    {
        $post = $this->input->post();
        $openid		= $this->session->userdata($this->inter_id."openid");

        $this->load->model('appointment/appointment_order_model');
        $this->load->model('appointment/appointment_time_sales_model');
        $this->load->model('appointment/appointment_dining_room_model');
        $order = $this->appointment_order_model->get_one(intval($post['order_id']));
        //校验是否非法操作
        if (empty($order) || $order['openid'] != $openid)
        {
            ajax_return('0','Illegal operation');
        }

        if ($order['book_op_status'] != 0)
        {
            ajax_return(0,'您已取消');
        }

        //更改预约数
        $where = array(
            'dining_room_id' => $order['dining_room_id'],
            'opentime_id'    => $order['opentime_id'],
            'add_date'       => date('Y-m-d',strtotime($order['book_datetime'])),
        );
        $this->appointment_time_sales_model->update($where);

        //更改预订状态
        $where = array(
            'order_id'  => $order['order_id'],
        );
        $res = $this->db->update('appointment_order',array('book_op_type'=>2,'book_op_status'=>2,'is_op'=>1,'offer_op_time'=>date('Y-m-d H:i:s')),$where);

        if ($res > 0)
        {
            $dining_room = $this->appointment_dining_room_model->get_one($order['dining_room_id']);
            //发送模板消息
            $order['item'] = $order['desk_name'].$order['desk_man'];
            $order['time'] = $order['book_datetime'];
            $order['shop_name'] = $dining_room['shop_name'];
            $order['reason'] = '用户取消';
            $this->load->model('plugins/Template_msg_model');
            $this->Template_msg_model->send_appointment_msg($order, 'appointment_offer_cancel');

            //发送待接单给管理员
            $saler = $this->appointment_dining_room_model->get_shop_saler_info($order['inter_id'],$order['dining_room_id']);
            if($saler)
            {
                foreach($saler as $v)
                {
                    $order['openid'] = $v['openid'];//给管理员
                    $this->Template_msg_model->send_appointment_msg($order, 'appointment_offer_cancel_shop');
                }
            }

            ajax_return(1,'取消成功');
        }
        else
        {
            ajax_return(0,'服务器繁忙，请稍后再试');
        }
    }


    /**
     * 用户排队取号接口（立即取号）
     */
    public function save_take_num()
    {
        $post = $this->input->post();
        $post['book_number'] = intval($post['book_number']);

        $openid         = $this->session->userdata($this->inter_id."openid");

        $this->load->model('appointment/appointment_desk_type_model');
        $this->load->model('appointment/appointment_desk_sales_model');
        $this->load->model('appointment/appointment_dining_room_model');
        $this->load->model('appointment/appointment_order_model');
        $this->load->model('appointment/appointment_opentime_model');

        //查询餐厅预约配置
        $dining_room = $this->appointment_dining_room_model->get_one(intval($post['dining_room_id']));
        if (empty($dining_room) || ($dining_room['inter_id'] != $this->inter_id))
        {
            ajax_return('0','Illegal operation');
        }

        if (($dining_room['book_style'] == 3) || ($dining_room['book_style'] == 0))
        {
            ajax_return('0','餐厅不可取号哦');
        }

        //营业时段
        $opentime = $this->appointment_opentime_model->getby_dining_room_id(intval($post['dining_room_id']));
        if (!empty($opentime))
        {
            $is_take = 0;
            foreach ($opentime as $value)
            {
                //判断配置是否可取号
                $cf_start_time = $dining_room['start_time'] * 60;
                $cf_end_time = $dining_room['end_time'] * 60;

                $start_time = strtotime(date("Y-m-d {$value['start_time']}")) - $cf_start_time;
                $end_time = strtotime(date("Y-m-d {$value['end_time']}")) - $cf_end_time;


                if ($start_time <= time() && $end_time >= time())
                {
                    $is_take =  2;
                }
                else if(($start_time <= time()) && ($value['end_time'] > date('H:i')))
                {
                    $is_take =  1;
                }

            }

            if ($is_take == 0)
            {
                ajax_return('0','当前取号时间不在营业时间范围');
            }
            else if($is_take == 1)
            {
                ajax_return(0,"结业前{$dining_room['end_time']}分钟不可取号哦");
            }
        }
        else
        {
            ajax_return('0','尚不可取号');
        }

        //查询桌型
        $desk_type = $this->appointment_desk_type_model->getby_dining_room_id($dining_room['dining_room_id']);
        if (!empty($desk_type))
        {
            $pre = array('A','B','C','D','E','F','G','H','I','J');
            foreach ($desk_type as $k => $item)
            {
                if ($item['min_num'] <= $post['book_number'] && $item['max_num'] >= $post['book_number'])
                {
                    $desk       = $item;
                    $pre_num    = $pre[$k];
                }
            }
        }
        if (empty($desk))
        {
            ajax_return(0,'桌型人数不符');
        }

        //检查用户是否已取号
        $desk_where = array("$openid",$dining_room['dining_room_id']);//,$desk['desk_type_id']这个条件可限制到桌型取号，当前只限制有没取号
        $desk_one = $this->appointment_order_model->get_desk_one($desk_where);

        if (!empty($desk_one))
        {
            ajax_return(400,'您已取号',array('url'=>site_url('/appointment/booking/take_detail?id='.$this->inter_id.'&order_id=').$desk_one['order_id']));
        }

        $cache = $this->_load_cache();
        $redis = $cache->redis->redis_instance();
        //保证key唯一
        $key_redis = 'appointment_take_number_'.$dining_room['dining_room_id'].'_'.$desk['desk_type_id'];
        $number = $redis->incr($key_redis);

        if ($number > 999)
        {
            $redis->set($key_redis,0);
            $number = $redis->incr($key_redis);
        }

        $offer_name = $pre_num.sprintf('%03s', $number);

        //插入取号信息
        $order = array(
            'book_number'   => $post['book_number'],
            'book_type'     => 2,
            'book_add_type' => 2,
            'book_op_status' => 0,
            'desk_type_id'  => $desk['desk_type_id'],
            'opentime_id'   => 0,
            'dining_room_id' => $dining_room['dining_room_id'],
            'openid'        => $this->session->userdata($this->inter_id."openid"),
            'inter_id'      => $this->inter_id,
            'book_add_time' => date('Y-m-d H:i:s'),
            'offer_type'    => 1,
            'offer_name'    => $offer_name,//桌号生成
            'desk_name'     => $desk['name'],
        );

        $order_id = $this->appointment_order_model->insert($order);
        if ($order_id > 0)
        {
            //更新排队数
            $update_num = array($dining_room['dining_room_id'],$desk['desk_type_id'],1);
            $this->appointment_desk_sales_model->update_num($update_num);

            //发送模板消息

            //查询排队等待数
            $where = array($order['dining_room_id'],$order['desk_type_id'],"{$order['book_add_time']}");
            $wait_num = $this->appointment_order_model->count_wait_num($where);

            $order['shop_name'] = $dining_room['shop_name'];
            $order['name'] = $order['offer_name'];
            $order['time'] = $order['book_add_time'];
            $order['wait'] = $wait_num;
            $this->load->model('plugins/Template_msg_model');
            $this->Template_msg_model->send_appointment_msg($order, 'appointment_take_success');

            ajax_return(1,'取号成功',array('url'=>site_url('/appointment/booking/take_detail?id='.$this->inter_id.'&order_id='.$order_id)));
        }
        else
        {
            ajax_return(0,'取号失败');
        }
    }

    /**
     * 我的预约接口
     */
    public function booking_list()
    {
        //查询餐厅信息
        $param = $this->input->post();

        $type     = $param['type'] ? trim($param['type']) : 'booking';
        $per_page = $param['page_show'] ? $param['page_show'] : 15;
        $cur_page = $param['page_num'] ? $param['page_num'] : 1;

        $openid         = $this->session->userdata($this->inter_id."openid");

        $this->load->model('appointment/appointment_order_model');
        //查询总记录数

        switch($type)
        {
            case 'booking':
                $type = 0;
                break;
            case 'finish':
                $type = 1;
                break;
            case 'cancel':
                $type = 2;
                break;
            case 'all':
                $type = 99;
                break;
            default :
                $type = 0;
                break;
        }
        $bind = array($this->inter_id,$openid,$type);
        $bool = false;
        if ($type == 99)
        {
            $bool = true;
            unset($bind[2]);
        }

        $total = $this->appointment_order_model->count_inter_orders($bind,$bool);
        $arr_page = get_page($total, $cur_page, $per_page);

        //查询数据列表
        $list = $this->appointment_order_model->get_inter_orders($bind,$bool,$cur_page,$per_page);

        if (!empty($list))
        {
            $this->load->model('appointment/appointment_dining_room_model');

            $status_name = array(
                '10' => '预约中',
                '11' => '已用餐',
                '12' => '已取消',
                '20' => '排队中',
                '21' => '已使用',
                '22' => '已过号',
            );
            foreach ($list as $key => $value)
            {
                $value['count'] = 0;
                //餐厅店铺配置信息
                $shop_info = $this->appointment_dining_room_model->get_one($value['dining_room_id'],'unit,shop_name,shop_image');
                $value = !empty($shop_info) ? array_merge($value,$shop_info) : $value;
                //查询等待数
                if ($value['book_type'] == 2)
                {
                    //查询排队等待数
                    $where = array($value['dining_room_id'],$value['desk_type_id'],"{$value['book_add_time']}");
                    $value['count'] = $this->appointment_order_model->count_wait_num($where);
                }

                $value['status_name'] = $status_name[$value['book_type'].$value['book_op_status']];
                $list[$key] = $value;
            }
        }

        $data = array(
            'list' => $list,
            'page' => $arr_page,
        );
        if ($list)
        {
            ajax_return(1,'成功',$data);
        }
        else
        {
            ajax_return(0,'暂无数据');
        }
    }


    /**
     * 加载缓存
     * @param string $name
     * @return mixed
     */
    protected function _load_cache($name='Cache')
    {
        if(!$name || $name=='cache')
            $name='Cache';
        $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'dis_ato_'), $name);
        return $this->$name;
    }

    //处理桌型名称
    protected function desk_name($item)
    {
        return $item['min_num'] == $item['max_num'] ? $item['max_num'] : $item['min_num'] .'-'.$item['max_num'];
    }

    //获取酒店信息
    protected function get_hotel_info($dining_room)
    {
        $this->load->model('hotel/hotel_model');
        return $this->hotel_model->get_hotel_detail($dining_room['inter_id'],$dining_room['hotel_id']);
    }
}