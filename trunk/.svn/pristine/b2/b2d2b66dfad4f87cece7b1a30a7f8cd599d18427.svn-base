<?php
/**
 * @desc 	预约管理
 * @author 	Shacaisheng
 * @date   	2017/02/22
 * @version	1.0
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Book_orders extends MY_Admin {

// 	protected $label_module= NAV_HOTELS;
	protected $label_module= '预约';
	protected $label_controller= '预约管理';
	protected $label_action= '预约';
    protected $admin_profile;

	function __construct()
	{
		parent::__construct();
        $this->admin_profile = $this->session->userdata('admin_profile');
        $this->load->helper('appointment');
        error_reporting(-1);
        ini_set('display_errors', 0);
        $this->load->model ( 'plugins/Template_msg_model' );
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
        $filter['start_time'] = $param['start_time'] ? addslashes($param['start_time']) : '';
        $filter['end_time'] = $param['end_time'] ? addslashes($param['end_time']) : '';
        $filter['dining_room_id'] = $param['dining_room_id'] ? intval($param['dining_room_id']) : '';
        $filter['type'] = $param['type'] = $param['type'] ? intval($param['type']) : '0';//预约状态 => 全部为99

        $per_page = 15;
        $cur_page = $param['page'] ? $param['page'] : 1;
        $this->load->model('appointment/appointment_order_model');
        $this->load->model('appointment/appointment_dining_room_model');

        $shop = $this->appointment_dining_room_model->get_inter_shop($this->admin_profile['inter_id'], 'dining_room_id,shop_name');

        $bind = array($this->admin_profile['inter_id']);
        //查询总记录数
        $total = $this->appointment_order_model->get_count($bind,$filter);

        //分页
        $arr_page = get_page($total, $cur_page, $per_page);

        //查询数据列表
        $data = $this->appointment_order_model->get_list($bind,$filter,$cur_page,$per_page);

        if (!empty($data))
        {
            foreach ($data as $key => $value)
            {
                //获得店铺名称
                $shop_info = $this->get_shop_info($shop,$value['dining_room_id']);
                $value['shop_name'] = $shop_info['shop_name'];
                $value['book_date'] = date('Y-m-d',strtotime($value['book_datetime']));
                $value['book_time'] = date('H:i',strtotime($value['book_datetime']));

                $value['add_date'] = date('Y-m-d',strtotime($value['book_add_time']));
                $value['add_time'] = date('H:i',strtotime($value['book_add_time']));

                $data[$key] = $value;
            }
        }

        //设置分页
        if ($filter)
        {
            $http_build_query = http_build_query($filter).'&';
        }
        $url = site_url('/appointment/book_orders/index?'.$http_build_query);
        $pagehtml = pagehtml($total, $cur_page, $arr_page['page_total'], $url);

        $op_status = array(
            '11' => '已用餐',
            '12' => '已用餐',
            '21' => '酒店取消',
            '22' => '用户取消',
        );
        $return = array(
            'pagehtml'  => $pagehtml,
            'list'      => $data,
            'param'     => $param,
            'shop'      => $shop,
            'op_status' => $op_status,
        );

		echo $this->_render_content($this->_load_view_file('index'), $return, TRUE);
	}

    /**
     * 更改状态接口
     */
    public function change_status()
    {
        $post = $this->input->post();
        $order_id = intval($post['order_id']);

        $status = addslashes($post['status']);
        if(!empty($order_id))
        {
            $this->load->model('appointment/appointment_dining_room_model');
            $this->load->model('appointment/appointment_order_model');
            $this->load->model('appointment/appointment_time_sales_model');
            //查询用户预约
            $order = $this->appointment_order_model->get_one($order_id);

            if (empty($order) || ($order['book_op_status'] != 0) || ($order['inter_id'] != $this->admin_profile['inter_id']))
            {
                ajax_return(0,'无更改预约');
            }

            $book_op_status = $status == 'on' ? 1 : 2;

            //更新预约数
            if ($book_op_status == 2 )
            {
                $where = array(
                    'dining_room_id' => $order['dining_room_id'],
                    'opentime_id'   => $order['opentime_id'],
                    'add_date'      => date('Y-m-d',strtotime($order['book_datetime'])),
                );

                $this->appointment_time_sales_model->update($where);
            }

            $where = array();
            $where['order_id'] = $order_id;

            //更改状态
            $update = $this->db
                ->update('appointment_order',array('offer_op_time'=>date('Y-m-d H:i:s'),'is_op'=>1,'book_op_type'=>1,'book_op_status' => $book_op_status), $where);
        }

        if($update > 0)
        {
            //推送模板消息
           if (!empty($order['openid']))
           {
               $dining_room = $this->appointment_dining_room_model->get_one($order['dining_room_id']);
               $order['item'] = $order['desk_name'].$order['desk_man'];
               $order['time'] = $order['book_datetime'];
               $order['shop_name'] = $dining_room['shop_name'];

               if ($book_op_status == 2)//取消
               {
                   $type = 'appointment_offer_cancel';
                   $order['reason'] = '酒店取消';
               }
               else
               {
                   $type = 'appointment_offer_used';
               }
               $this->load->model('plugins/Template_msg_model');
               $this->Template_msg_model->send_appointment_msg($order, $type);
           }

            ajax_return(1,'成功');
        }
        else
        {
            ajax_return(0,'失败');
        }
    }


    /**
     * 新增预约接口
     */
    public function save_order()
    {
        $post = $this->input->post();
        $book_datetime = strtotime($post['book_datetime']);

        if ($book_datetime < time())
        {
            ajax_return('0','请选择正确预约时间');
        }

        if (!check_phone($post['book_phone']))
        {
            ajax_return('0','电话号码格式错误');
        }
        $insert = array(
            'book_number'   => addslashes(trim($post['book_number'])),
            'book_datetime' => date('Y-m-d H:i:s',$book_datetime),
            'book_name'     => addslashes($post['book_name']),
            'book_phone'    => addslashes($post['book_phone']),
            'book_info'     => addslashes($post['book_info']),
            'book_type'     => 1,
            'book_add_type' => 1,
            'book_op_status' => 0,
            'desk_type_id'  => 0,
            'opentime_id'   => 0,
            'dining_room_id' => 0,
            'openid'        => 0,
            'inter_id'      => 0,
            'book_add_time' => date('Y-m-d H:i:s'),
        );

        $insert['inter_id'] = $this->admin_profile['inter_id'];

        $this->load->model('appointment/appointment_desk_type_model');
        $this->load->model('appointment/appointment_time_sales_model');
        $this->load->model('appointment/appointment_dining_room_model');
        $this->load->model('appointment/appointment_opentime_model');
        $this->load->model('appointment/appointment_order_model');

        //查询餐厅配置
        $dining_room = $this->appointment_dining_room_model->get_one(intval($post['dining_room_id']),'dining_room_id,unit,book_style,book_day,inter_id');
        if (empty($dining_room) || ($dining_room['book_style'] == 2) || ($dining_room['book_style'] == 0))
        {
            ajax_return(0,'餐厅已停止预约');
        }

        if ($this->admin_profile['inter_id'] != $dining_room['inter_id'])
        {
            ajax_return(0,'餐厅账号无权限预约');
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
        $where = array($insert['dining_room_id'],$insert['book_phone'],date('Y-m-d 00:00:00',$book_datetime),date('Y-m-d 23:59:59',$book_datetime));

        $order = $this->appointment_order_model->get_phone_one($where);
        if (!empty($order))
        {
            ajax_return('-1','该用户预约过');
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
        $insert['desk_man']     = $this->desk_name($desk_type);

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
            'opentime_id'   => $insert['opentime_id'],
            'date'          => date('Y-m-d',$book_datetime),
            'stock'         => $desk_type['stock']
        );

        $update = $this->appointment_time_sales_model->update_book_num($where);
        //陈倩如成功 提交事务
        if ($order_id > 0 && $update > 0)
        {
            $this->db->trans_commit();
            //发送模板消息


            ajax_return('1','预订座位成功');
        }
        //回滚事务
        else
        {
            $this->db->trans_rollback();
            ajax_return('-1','服务器繁忙，预订座位失败');
        }

        //推送消息
    }

    /**
     * 返回当前店铺ID的信息
     * @param array $shop 店铺数组
     * @param int $id 店铺ID
     * @return array
     */
    protected function get_shop_info($shop,$id)
    {
        if (!empty($shop))
        {
            foreach ($shop as $item)
            {
                if ($item['dining_room_id'] == $id)
                {
                    return $item;
                }
            }
        }
    }

    //处理桌型名称
    protected function desk_name($item)
    {
        return $item['min_num'] == $item['max_num'] ? $item['max_num'] : $item['min_num'] .'-'.$item['max_num'];
    }

}
