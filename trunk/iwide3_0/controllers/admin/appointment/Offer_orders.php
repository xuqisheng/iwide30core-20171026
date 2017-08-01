<?php
/**
 * @desc 	取号管理
 * @author 	Shacaisheng
 * @date   	2017/02/22
 * @version	1.0
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Offer_orders extends MY_Admin {

// 	protected $label_module= NAV_HOTELS;
	protected $label_module= '预约';
	protected $label_controller= '取号管理';
	protected $label_action= '预约';
	protected $admin_profile;

	function __construct()
	{
		parent::__construct();
        $this->admin_profile = $this->session->userdata('admin_profile');
        $this->load->helper('appointment');
        error_reporting(-1);
        ini_set('display_errors', 0);
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
        $get = $this->input->get();

        $this->load->model('appointment/appointment_order_model');
        $this->load->model('appointment/appointment_dining_room_model');
        $this->load->model('appointment/appointment_desk_type_model');
        $this->load->model('appointment/appointment_desk_sales_model');
        //获取餐厅店铺
        $desk_type = array();
        $num_history = 0;
        $shop = $this->appointment_dining_room_model->get_inter_shop($this->admin_profile['inter_id'], 'dining_room_id,shop_name');
        if (!empty($shop))
        {
            if (!empty($get['dining_room_id']))
            {
                foreach ($shop as $value)
                {
                    if ($value['dining_room_id'] == $get['dining_room_id'])
                    {
                        $dining_room = $value;
                    }
                }
                setcookie("dining_room_id_".$this->admin_profile['inter_id'],$get['dining_room_id']);
            }
            else
            {
                $dining_room_last = '';
                foreach ($shop as $value)
                {
                    if ($value['dining_room_id'] == $_COOKIE['dining_room_id_'.$this->admin_profile['inter_id']])
                    {
                        $dining_room_last = $value;
                    }
                }
                $dining_room = $dining_room_last ? $dining_room_last : $shop[0];
            }

            $dining_room = $dining_room ?  $dining_room : $shop[0];

            //获取店铺下的桌型
            $desk_type = $this->appointment_desk_type_model->getby_dining_room_id($dining_room['dining_room_id']);
            if (!empty($desk_type))
            {
                foreach ($desk_type as $key => $value)
                {
                    $where = array($value['dining_room_id'],$value['desk_type_id']);
                    $num = $this->appointment_desk_sales_model->get_num_where($where);
                    $value['num'] = $num;
                    $desk_type[$key] = $value;
                }

                $num_history = $this->appointment_order_model->count_offer_num(array($value['dining_room_id']));
            }
        }

		$view_params = array(
            'dining_room'  => $dining_room,
            'shop'      => $shop,
            'desk_type' => $desk_type,
            'history'   => $num_history,
        );

		echo $this->_render_content($this->_load_view_file('index'), $view_params, TRUE);
	}


    /**
     * 加载分类数据接口
     */
    public function load_type_data()
    {
        $get = $this->input->post();
        $dining_room_id = $get['dining_room_id'] ? intval($get['dining_room_id']) : 0;
        $desk_type_id = $get['desk_type_id'] ? intval($get['desk_type_id']) : 0;
        $type = $desk_type_id ? 1 : 0;//0-历史数据
        //获取店铺下的排队号
        $this->load->model('appointment/appointment_order_model');
        $order = $this->appointment_order_model->get_offer_list(array($this->admin_profile['inter_id'],$dining_room_id,$desk_type_id),$type);

        $data = array(
            'list' => $order
        );
        ajax_return(1,'成功',$data);
    }

    /**
     * 更改状态接口[1-使用/2-过号]
     */
    public function change_status()
    {
        $post = $this->input->post();
        $type = intval($post['type']);
        $itemid = intval($post['itemid']);//当前第三的ID.用来推送消息
        $this->load->model('appointment/appointment_order_model');
        $this->load->model('appointment/appointment_dining_room_model');
        $this->load->model('appointment/appointment_desk_sales_model');

        $order = $this->appointment_order_model->get_one(intval($post['order_id']));

        $type_name = array(1=>'已使用',2=>'已过号');
        if (empty($order) || $order['book_op_status'] != 0)
        {
            ajax_return(0,$type_name[$type]);
        }

        if ($order['inter_id'] != $this->admin_profile['inter_id'])
        {
            ajax_return('0','操作失败');
        }

        $update = array(
            'book_op_status' => $type == 1 ? 1 : 2,
            'offer_op_time' => date('Y-m-d H:i:s'),
            'book_datetime' => date('Y-m-d H:i:s'),
            'is_op'         => 1,
        );

        $where = array(
            'order_id' => intval($post['order_id']),
            'book_op_status' => 0,
        );
        //更改预约状态
        $res = $this->db->update('appointment_order',$update,$where);
        if ($res > 0)
        {
            $where = array(
                'dining_room_id'    => $order['dining_room_id'],
                'desk_type_id'      => $order['desk_type_id'],
            );
            $res = $this->appointment_desk_sales_model->update($where);

            //发送模板消息
            if (!empty($order['openid']))
            {
                $dining_room = $this->appointment_dining_room_model->get_one($order['dining_room_id']);
                if ($type == 2)//取消
                {
                    $type = 'appointment_take_outdate';
                }
                else//使用
                {
                    $type = 'appointment_take_used';
                }

                $order['name'] = $order['offer_name'];
                $order['number'] = $order['book_number'];
                $order['shop_name'] = $dining_room['shop_name'];

                //查询排队等待数
                $where = array($order['dining_room_id'],$order['desk_type_id'],"{$order['book_add_time']}");
                $wait_num = $this->appointment_order_model->count_wait_num($where);
                $order['wait'] = $wait_num;
                $this->load->model('plugins/Template_msg_model');
                $this->Template_msg_model->send_appointment_msg($order, $type);
            }

            //模板消息通知顺位第三
            if (!empty($itemid))
            {
                $this->send_third_msg($itemid);
            }

            ajax_return(1,'成功',$res);
        }
        ajax_return(0,'更改失败');
    }

    //推送消息给顺位第三用户
    protected function send_third_msg($order_id)
    {
        $this->load->model('appointment/appointment_order_model');
        $this->load->model('appointment/appointment_desk_sales_model');
        $item = $this->appointment_order_model->get_one(intval($order_id));
        if (($item['inter_id'] == $this->admin_profile['inter_id']) && !empty($item['openid']))
        {
            $dining_room = $this->appointment_dining_room_model->get_one($item['dining_room_id']);

            $item['shop_name'] = $dining_room['shop_name'];
            $item['name'] = $item['offer_name'];
            $item['wait'] = 3;
            $item['unit'] = $item['book_number'].'人'.$dining_room['unit'];
            $this->load->model('plugins/Template_msg_model');
            $this->Template_msg_model->send_appointment_msg($item, 'appointment_take_wait');
        }
    }

    /**
     * 客服取号接口/拆队取号
     */
    public function save_take_num()
    {
        $post = $this->input->post();
        $post['book_number'] = intval($post['book_number']);
        $post['book_phone'] = addslashes(trim($post['book_phone']));
        $offer_type = intval($post['offer_type']);//取号类型 1-排队取号，2-插队取号
        if (!empty($post['book_phone']) && !check_phone($post['book_phone']))
        {
            ajax_return(0,'手机号码格式错误');
        }

        $this->load->model('appointment/appointment_desk_type_model');
        $this->load->model('appointment/appointment_desk_sales_model');
        $this->load->model('appointment/appointment_dining_room_model');
        $this->load->model('appointment/appointment_order_model');
        $this->load->model('appointment/appointment_opentime_model');

        //查询餐厅预约配置
        $dining_room = $this->appointment_dining_room_model->get_one(intval($post['dining_room_id']));
        if (empty($dining_room) || ($dining_room['inter_id'] !=  $this->admin_profile['inter_id']))
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
                else if($start_time <= time() && $end_time < time())
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
        $click_key = 0;
        $desk_type = $this->appointment_desk_type_model->getby_dining_room_id($dining_room['dining_room_id']);
        if (!empty($desk_type))
        {
            $pre = array('A','B','C','D','E','F','G','H','I','J');
            $pre_vip = array('L','M','N','O','P','Q','R','S','W','Y');
            foreach ($desk_type as $k => $item)
            {
                if ($item['min_num'] <= $post['book_number'] && $item['max_num'] >= $post['book_number'])
                {
                    $desk       = $item;
                    $pre_num    = $offer_type == 2 ? $pre_vip[$k] : $pre[$k]; ;
                    $click_key  = $k;
                }
            }
        }

        if (empty($desk))
        {
            ajax_return(0,'桌型人数不符');
        }

        //检查用户是否已取号
        /*
        $desk_where = array($dining_room['dining_room_id'],"{$post['book_phone']}");
        $desk_one = $this->appointment_order_model->get_desk_by_phone($desk_where);

        if (!empty($desk_one))
        {
            ajax_return(0,'不能重复取号哦');
        }

        */

        $cache = $this->_load_cache();
        $redis = $cache->redis->redis_instance();
        //保证key唯一
        $_num_key   =  $offer_type == 2 ? 'appointment_vip_take_number_' : 'appointment_take_number_';
        $key_redis  = $_num_key.$dining_room['dining_room_id'].'_'.$desk['desk_type_id'];
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
            'book_add_type' => 1,
            'book_op_status' => 0,
            'desk_type_id'  => $desk['desk_type_id'],
            'opentime_id'   => 0,
            'dining_room_id' => $dining_room['dining_room_id'],
            'inter_id'      => $this->admin_profile['inter_id'],
            'book_add_time' => date('Y-m-d H:i:s'),
            'offer_type'    => $offer_type == 2 ? 2 : 1,
            'offer_name'    => $offer_name,//桌号生成
            'desk_name'     => $desk['name'],
            'book_phone'     => $post['book_phone'],
        );

        $order_id = $this->appointment_order_model->insert($order);
        if ($order_id > 0)
        {
            //更新排队数
            $update_num = array($dining_room['dining_room_id'],$desk['desk_type_id'],1);
            $this->appointment_desk_sales_model->update_num($update_num);
            //成功发送模板消息
            $return['click_key'] = $click_key;
            $return['order_id'] = $order_id;
            $return = array_merge($return,$order);

            //查询排队等待数
            $where = array($order['dining_room_id'],$order['desk_type_id'],"{$order['book_add_time']}");
            if ($order['offer_type'] == 1)
            {
                $wait_num = $this->appointment_order_model->count_wait_num($where);
            }
            else
            {
                $wait_num = $this->appointment_order_model->count_wait_num_vip($where);
            }

            //打印
            $this->load->model('plugins/print_model');
            $offer = array(
                'title' => '排队编号',
                'name'  => $offer_name,
                'time'  => $order['book_add_time'],
                'wait'  => $wait_num,
                'text'  => "您前面还有{$wait_num}位同类型用户等待就餐，请您耐心等候及留意系统叫号提示",
                'inter_id'  => $dining_room['inter_id'],
                'hotel_id'  => 0,
                'shop_id'   => $dining_room['dining_room_id'],
            );

            $this->print_model->print_appointment_offer($offer, 'appointment');

            ajax_return(1,'取号成功',$return);
        }
        else
        {
            ajax_return(0,'取号失败');
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
        $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'dis_ato_'), $name );
        return $this->$name;
    }
}
