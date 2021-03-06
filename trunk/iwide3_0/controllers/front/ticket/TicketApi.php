<?php
/**
 * @desc : 预约核销接口
 * @author : Shacaisheng
 * @date  : 2017-05-12
 * @version : V1.0
 *
 */
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class TicketApi extends MY_Front {
    public  $themeConfig;
    public  $theme = 'default';//皮肤
    public $openid;
    public $module;
    public $put_args;
    public $limit_num = 50;
    public $common_data = array();
    //protected $_token;
	function __construct() {
		parent::__construct ();
        $this->inter_id = $this->session->userdata ( 'inter_id' );
        $this->openid = $this->session->userdata ( $this->inter_id . 'openid' );
        //统计探针
        $this->load->library('MYLOG');
        MYLOG::distribute_tracker($this->session->userdata ( $this->inter_id . 'openid' ),   $this->session->userdata ( 'inter_id' ));

        $this->common_data['csrf_token'] = $this->security->get_csrf_token_name ();
        $this->common_data['csrf_value'] = $this->security->get_csrf_hash ();

        //$this->load->model('wx/Access_token_model');
        //$this->common_data = $this->Access_token_model->getSignPackage($this->inter_id);
        $this->load->helper('appointment');
        //header('Access-Control-Allow-Origin:*');
    }

    //店铺列表接口
    public function scenic_list()
    {
        $param = $this->input->post();
        $ajax_arr['inter_id'] = $this->inter_id;
        $per_page = isset($param['page_show']) && $param['page_show'] ? $param['page_show'] : 20;
        $cur_page = isset($param['page_num']) && $param['page_num'] ? $param['page_num'] : 1;
        //获取店铺数量
        $this->load->model('roomservice/roomservice_shop_model');

        $filter = array();
        $filter['sale_type']    = 4;
        $filter['inter_id']     = $this->inter_id;
        $filter['is_delete']    = 0;
        $filter['status']       = 1;

        $res = $this->roomservice_shop_model->get_page($filter,$cur_page,$per_page);
        $list = isset($res[1]) ? $res[1] : array();
        $total_count = isset($res[0]) ?$res[0] : 0;

        $arr_page = get_page($total_count, $cur_page, $per_page);
        $ajax_arr = array(
            'list' => $list,
            'page' => $arr_page,
        );

        $ajax_arr = array_merge($ajax_arr,$this->common_data);
        ajax_return(200,'成功',$ajax_arr);
    }

    /**
     * 商品列表接口
     * author ：沙沙
     * date    : 2017-05-27
     */
    public function goods_list()
    {
        $param = request();

        $inter_id = !empty($param['id']) ? addslashes($param['id']) : '';
        $per_page = !empty($param['limit']) ? intval($param['limit']) : 20;
        $cur_page = !empty($param['offset']) ?intval($param['offset']) : 1;
        $shop_id = !empty($param['shop_id']) ? intval($param['shop_id']) : '';
        $hotel_id = !empty($param['hotel_id']) ? intval($param['hotel_id']) : '';

        if (empty($shop_id) || empty($inter_id) || empty($hotel_id))
        {
            ajax_return(400,'请求参数错误');
        }

        //获取店铺信息
        $this->load->model('roomservice/roomservice_shop_model');
        $this->load->model('roomservice/roomservice_goods_model');
        //店铺信息
        $shop = $this->roomservice_shop_model->get(array('shop_id' => $shop_id,'inter_id'=>$inter_id),'one','shop_name,share_img,share_title,share_spec,advance_book_num');

        if (empty($shop))
        {
            ajax_return(404,'店铺不存在');
        }

        //获取酒店信息
        $this->load->model('hotel/hotel_model');
        $hotel =  $this->hotel_model->get_hotel_detail($inter_id,$hotel_id);
        $shop['hotel_name'] = !empty($hotel) ? $hotel['name'] : '商品列表';

        $filter = array(
            'shop_id'  => $shop_id,
            'inter_id' => $inter_id,
            'sale_status' => 1,
            'sale_now' => 1,
            'is_delete' => 0,
        );
        $goods_res = $this->roomservice_goods_model->get_page($filter,$cur_page,$per_page,array('sort_order'=>'DESC','goods_id'=>'DESC'));
        $goods = array();

        //处理分页
        $arr_page = get_page($goods_res[0], $cur_page, $per_page);
        if (!empty($goods_res[1]))
        {
            $goods = $goods_res[1];
        }

        //处理商品价格->最低价
        if (!empty($goods))
        {
            $ids = array();
            foreach ($goods as $key => $value)
            {
                $ids[] = $value['goods_id'];
            }

            //获取商品最低价格
            $start = date('Y-m-d');
            $end = date('Y-m-d',time() + 86400 * intval($shop['advance_book_num']));
            $where_arr = array(
                'inter_id' => $inter_id,
                'goods_price > ' => 0,
                'date' => " date between '{$start}' and '{$end}'",
            );

            $this->load->model('roomservice/roomservice_ticket_dateprice_model');
            $price = $this->roomservice_ticket_dateprice_model->get_goods_price($where_arr,$ids);

            $arr= array();
            if (!empty($price))
            {
                foreach ($price as $key=>$value)
                {
                    $arr[$value['goods_id']]['stock'][] = $value['goods_stock'];
                    $arr[$value['goods_id']]['price'][] = $value['goods_price'];
                }
            }
 
            //组装数据
            foreach ($goods as $key => $value)
            {
                $item = array();
                $item['goods_price'] = 0;
                $item['stock'] = 0;
                $item['goods_img'] = !empty($value['goods_img']) ? json_decode($value['goods_img'],true)[0] : '';
                $item['goods_id'] = $value['goods_id'];
                $item['goods_name'] = $value['goods_name'];
                $item['goods_alias'] = $value['goods_alias'];
                $item['sale_num'] = $value['sale_num'];
                $item['shop_price'] =  $this->auto_handle_price($value['shop_price']);
                $item['url'] = site_url('/ticket/book/goods_detail?id='.$this->inter_id.'&shop_id='.$value['shop_id'].'&hotel_id='.$value['hotel_id'].'&goods_id='.$value['goods_id']);
                if (!empty($arr[$value['goods_id']]))
                {
                    $item['stock'] = min($arr[$value['goods_id']]['stock']);
                    $item['goods_price'] = min($arr[$value['goods_id']]['price']);
                }

                $item['goods_price'] = $this->auto_handle_price($item['goods_price']);
                $goods[$key] = $item;
            }
        }

        //购物车数量
        $filter = array(
            'shop_id' => $shop_id,
            'inter_id' => $inter_id,
            'openid' => $this->openid,
            'buy_type' => 1,
        );

        $ajax_arr = array(
            'shop'  => $shop,
            'goods' => $goods,
            'page'  => $arr_page,
            'cart'  => $this->count_cart_num($filter),
        );

        $ajax_arr = array_merge($ajax_arr,$this->common_data);

        ajax_return(200,'成功',$ajax_arr);
    }


    /**
     * 商品列表接口
     * author ：沙沙
     * date    : 2017-05-27
     */
    public function goods_detail()
    {
        $param = request();

        $this->load->model('roomservice/roomservice_goods_model');
        $filter = array(
            'shop_id'   => !empty($param['shop_id']) ? intval($param['shop_id']) : '',
            'inter_id'  => !empty($param['id']) ? addslashes($param['id']) : '',
            'goods_id'  => !empty($param['goods_id']) ? intval($param['goods_id']) : '',
            'is_delete' => 0,
        );

        if (empty($filter['goods_id']) || empty($filter['shop_id']))
        {
            ajax_return(400,'请求参数错误');
        }

        //获取商品信息
        $select = 'inter_id,hotel_id,shop_id,goods_id,goods_name,hotel_id,sale_status,sale_num,goods_img,
                    goods_desc,goods_alias,share_img,share_title,share_spec,sale_now,goods_notice,shop_price,
                    ticket_sale_time,ticket_credits,ticket_day,ticket_limit,ticket_style';
        $goods = $this->roomservice_goods_model->get_goods_detail($filter,$select);

        if (!empty($goods))
        {
            $goods['goods_notice'] = !empty($goods['goods_notice']) ? $goods['goods_notice'] : '';

            //获取酒店信息
            $this->load->model('hotel/hotel_model');
            $hotel =  $this->hotel_model->get_hotel_detail($goods['inter_id'],$goods['hotel_id']);

            $hotel_info['hotel_name'] = !empty($hotel['name']) ? $hotel['name'] : '';
            $hotel_info['hotel_address'] = !empty($hotel['address']) ? $hotel['address'] : '';
            $hotel_info['hotel_latitude'] = !empty($hotel['latitude']) ? $hotel['latitude'] : '';
            $hotel_info['hotel_longitude'] = !empty($hotel['longitude']) ? $hotel['longitude'] : '';
            $hotel_info['hotel_tel'] = !empty($hotel['tel']) ? $hotel['tel'] : '';
            $hotel_info['hotel_img'] = !empty($hotel['intro_img']) ? $hotel['intro_img'] : '';

            //获取spu
            $this->load->model('roomservice/roomservice_ticket_spu_model');
            $this->load->model('roomservice/roomservice_ticket_dateprice_model');

            $filter = array(
                'goods_id' => $goods['goods_id'],
                'inter_id' => $goods['inter_id'],
            );
            $spu = $this->roomservice_ticket_spu_model->goods_spu($filter);

            $arr['shop_id'] = $goods['shop_id'];
            $arr['status'] = 1;//开业
            $arr['is_delete'] = 0;//正常
            $this->load->model ( 'roomservice/roomservice_shop_model' );
            $shop = $this->roomservice_shop_model->get($arr,'one','shop_name,advance_book_num,time_range');
            $book_num = !empty($shop['advance_book_num']) ? intval($shop['advance_book_num']) : 0;
            $hotel_info['shop_name'] = $shop['shop_name'];

            $goods['book_day'] = $book_num;
            $goods['goods_img'] = !empty($goods['goods_img']) ? json_decode($goods['goods_img'],true) : array();

            //当天消费时段判断
            $goods['today_status'] = 0;//1 当天可以预约，0 不可预约
            if (!empty($goods['ticket_sale_time']))
            {
                $goods['today_status'] = check_time_range($shop['time_range'],$goods['ticket_sale_time']);
            }

            $goods['today_date'] = date('Y-m-d');
            $goods['goods_price'] = 0;
            if (!empty($spu))
            {
                $end_date = date('Y-m-d',time() + $book_num * 86400);
                $filter['date'] = date('Y-m-t',time() + $book_num * 86400);
                $start_time = strtotime(date('Y-m-01'));
                $end_time = strtotime($filter['date']);
                //价格日历时间
                $date_price = $this->roomservice_ticket_dateprice_model->goods_date_price($filter);

                $date_data = array();
                $spu_data = array();
                if (!empty($date_price))
                {
                    foreach ($spu as $key => $value)
                    {
                        $item = array(
                            'date_id' => '',
                            'inter_id' => $value['inter_id'],
                            'goods_id' => $value['goods_id'],
                            'spu_id' => $value['spu_id'],
                            'goods_price' => '0',
                            'goods_stock' => '0',
                            'shop_id' => '0',
                            'date' => '',
                        );
                        $spu_data_set[$value['spu_id']] = $item;
                    }

                    $cur_month_data = array();
                    foreach ($date_price as $value)
                    {
                        if ($value['date'] <= $end_date)
                        {
                            $spu_data[$value['spu_id']][] = $value;
                        }

                        //判断当前月份是否存在数据
                        $month = date('m',strtotime($value['date']));

                        $cur_month_data[] = $month;

                        //处理商品优惠
                        if ($value['goods_price'] > 0)
                        {
                            $value['goods_price'] = handle_ticket_price($goods,$value['goods_price'],$value['date']);
                            $value['goods_price'] = $this->auto_handle_price($value['goods_price']);
                        }
                        $date_data[$value['date']][$value['spu_id']] = $value;
                    }

                    while($start_time <= $end_time)
                    {
                        //补全未设置数据
                        $end_time_date = date('Y-m-d',$end_time);
                        $in_month = date('m',$end_time);

                        if (empty($date_data[$end_time_date]) && in_array($in_month,$cur_month_data,true))
                        {
                            $date_data[$end_time_date] = $spu_data_set;
                        }
                        $end_time = $end_time - 86400;
                    }

                    //处理最低规格库存&价格
                    $sku_price_data = sku_price_data($spu_data, 'goods_price');
                    $low_price = array();
                    foreach ($spu as $key => $value)
                    {
                        $value['price'] =  $value['stock'] = 0;
                        if (!empty($sku_price_data[$value['spu_id']]))
                        {
                            if ($sku_price_data[$value['spu_id']]['price'] > 0)
                            {
                                $low_price[] = $sku_price_data[$value['spu_id']]['price'];
                            }

                            $value['price'] = $this->auto_handle_price($sku_price_data[$value['spu_id']]['price']);
                            $value['stock'] = $sku_price_data[$value['spu_id']]['stock'];
                        }
                        $spu[$key] = $value;
                    }

                    //处理默认选中最低价格
                    $low_price = min($low_price);
                    $default_spu = '';
                    foreach ($spu as $key => $value)
                    {
                        $value['prime_price'] = $this->auto_handle_price($value['prime_price']);
                        $value['selected'] = 0;
                        if ($value['price'] == $low_price && empty($default_spu))
                        {
                            $goods['goods_price'] = $this->auto_handle_price($value['price']);
                            $value['selected'] = 1;
                            $default_spu = $value['spu_id'];
                        }
                        $spu[$key] = $value;
                    }

                    //找出最早日期
                    $goods['default_day'] = '';

                    if (!empty($default_spu))
                    {
                        $dates = array();
                        foreach ($date_price as $item)
                        {
                            if ($item['spu_id'] == $default_spu && $item['date'] >= $goods['today_date'] && !empty($item['goods_price']) && !empty($item['goods_stock']))
                            {
                                $dates[] = $item['date'];
                            }
                        }

                        $goods['default_day'] = min($dates);
                    }
                }
            }

            //购物车数量
            $filter = array(
                'shop_id' => $goods['shop_id'],
                'inter_id' => $goods['inter_id'],
                'openid' => $this->openid,
                'buy_type' => 1,
            );
            $res = array(
                'goods' => $goods,
                'hotel' => $hotel_info,
                'spu'   => $spu,
                'date'  => $date_data,
                'cart'  => $this->count_cart_num($filter),
            );

            $res = array_merge($res,$this->common_data);

            ajax_return(200,'成功',$res);
        }
        else
        {
            ajax_return(404,'请求的资源不存在');
        }
    }

    /**
     * 加入购物车
     */
    public function add_cart()
    {
        $param  = request();
        $filter['goods_id'] = !empty($param['goods_id']) ? intval($param['goods_id']) : '';
        $filter['shop_id'] = !empty($param['shop_id']) ? intval($param['shop_id']) : '';
        $filter['inter_id'] = !empty($param['id']) ? addslashes($param['id']) : '';
        $data = array();
        $data['buy_type'] = !empty($param['buy_type']) ? intval($param['buy_type']) : 1;
        $data['goods_num'] = !empty($param['goods_num']) ? intval($param['goods_num']) : 1;
        $data['spu_id'] = !empty($param['spu_id']) ? intval($param['spu_id']) : '';
        $data['spu_name'] = !empty($param['spu_name']) ? addslashes($param['spu_name']) : '';
        $data['goods_price'] = !empty($param['goods_price']) ? addslashes($param['goods_price']) : 0;
        $data['book_day'] = !empty($param['book_day']) ? addslashes($param['book_day']) : date('Y-m-d');
        $data['openid'] = $this->openid;

        if (empty($filter['goods_id']) || empty($filter['inter_id']))
        {
            ajax_return(400,'请求参数错误');
        }


        //校验日期是否可以预约
        $shop_info = $shop_info = $this->shop_info($filter['inter_id'],'',$filter['shop_id']);
        $this->check_is_book_goods($data,$shop_info);

        //获取商品信息
        $select = 'inter_id,hotel_id,shop_id,goods_id,goods_name,hotel_id,sale_status,sale_now';
        $this->load->model('roomservice/roomservice_goods_model');
        $goods = $this->roomservice_goods_model->get_goods_detail($filter,$select);
        if (!empty($goods) && $goods['sale_now'] == 1)
        {
            $data = array_merge($goods,$data);
            $this->load->model('ticket/ticket_cart_model');
            //查询是否购物车存在商品
            $where = array(
                'goods_id'  => $goods['goods_id'],
                'spu_id'    => $data['spu_id'],
                'book_day'    => $data['book_day'],
                'openid'    => $data['openid'],
                'inter_id'    => $filter['inter_id'],
                'buy_type'    => $data['buy_type'],
            );
            $cart_info = $this->ticket_cart_model->get_cart_info($where,'cart_id,goods_num');
            //更改购物车数量
            if (!empty($cart_info))
            {
                $where_arr = array(
                    'cart_id' => $cart_info['cart_id'],
                );

                //立即购买
                if ($data['buy_type'] == 2)
                {
                    $cart_info['goods_num'] = 0;
                }

                $update = array(
                    'goods_num' => $data['goods_num'] + $cart_info['goods_num'],
                    'add_time' => date('Y-m-d H:i:s'),
                );

                //检测库存
                $this->check_goods_stock($data,$update['goods_num']);

                if ($update['goods_num'] > $this->limit_num)
                {
                    ajax_return(422,'购买数量已超过限制购买数量');
                }

                $res = $this->ticket_cart_model->update_cart($update,$where_arr);
            }
            else
            {
                //检测库存
                $this->check_goods_stock($data,$data['goods_num']);
                $res = $this->ticket_cart_model->add_cart($data);
                $cart_info['cart_id'] = $res;
            }

            if ($res > 0)
            {
                $filter_arr['buy_type'] = 1;
                $filter_arr['shop_id'] = $filter['shop_id'];
                $filter_arr['openid'] = $this->openid;
                $count = $this->select_cart_goods($filter_arr);

                $res_ajax = array(
                    'cart_id' => $cart_info['cart_id'],
                    'buy_type' => $data['buy_type'],
                    'count' => $count['total'],
                    'url' => "/index.php/ticket/book/checkout?id={$filter['inter_id']}&hotel_id={$goods['hotel_id']}&shop_id={$filter['shop_id']}&cart_id={$cart_info['cart_id']}",
                );
                ajax_return(200,'加入购物车成功',$res_ajax);
            }
            else
            {
                ajax_return(422,'加入购物车失败');
            }
        }
        else
        {
            ajax_return(404,'商品已经下架');
        }
    }

    /**
     * 编辑购物车
     */
    public function update_cart()
    {
        $param  = request();
        $cart_id = !empty($param['cart_id']) ? intval($param['cart_id']) : '';
        $shop_id = !empty($param['cart_id']) ? intval($param['shop_id']) : '';
        $filter['inter_id'] = !empty($param['id']) ? addslashes($param['id']) : '';
        $data = array();
        $data['goods_num'] = !empty($param['goods_num']) ? intval($param['goods_num']) : 1;
        $data['openid'] = $this->openid;

        if (empty($cart_id) || empty($filter['inter_id']) || empty($shop_id))
        {
            ajax_return(400,'请求参数错误');
        }

        if ($data['goods_num'] > $this->limit_num)
        {
            ajax_return(422,'购买数量已超过限制购买数量');
        }

        $this->load->model('ticket/ticket_cart_model');
        //查询是否购物车存在商品
        $where = array(
            'cart_id'  => $cart_id,
            'openid'    => $data['openid'],
            'inter_id'    => $filter['inter_id'],
        );
        $cart_info = $this->ticket_cart_model->get_cart_info($where,'cart_id,inter_id,shop_id,goods_id,spu_id,book_day');
        $data['spu_id'] = $cart_info['spu_id'];
        $data['book_day'] = $cart_info['book_day'];
        if (!empty($cart_info))
        {
            //获取商品信息
            $select = 'inter_id,hotel_id,shop_id,goods_id,goods_name,hotel_id,sale_status,sale_now';
            $this->load->model('roomservice/roomservice_goods_model');
            unset($cart_info['cart_id'],$cart_info['spu_id'],$cart_info['book_day']);
            $goods = $this->roomservice_goods_model->get_goods_detail($cart_info,$select);
            if (!empty($goods) && $goods['sale_now'] == 1)
            {
                $data = array_merge($goods,$data);
                //检测库存
                $this->check_goods_stock($data,$data['goods_num']);

                //更改购物车数量

                $where_arr = array(
                    'cart_id' => $cart_id,
                );
                $update = array(
                    'goods_num' => $data['goods_num'],
                    'add_time' => date('Y-m-d H:i:s'),
                    'selected' => 1,
                );
                $res = $this->ticket_cart_model->update_cart($update,$where_arr);

                if ($res > 0)
                {
                    $filter['buy_type'] = 1;
                    $filter['shop_id'] = $shop_id;
                    $filter['openid'] = $this->openid;
                    $count = $this->select_cart_goods($filter);
                    ajax_return(200,'更改购物车成功',$count['total']);
                }
                else
                {
                    ajax_return(204,'更改购物车失败');
                }
            }
            else
            {
                ajax_return(404,'商品已经下架');
            }
        }
        else
        {
            ajax_return(404,'商品不在您的购物车');
        }
    }

    /**
     * 删除购物车
     */
    public function del_cart()
    {
        $param  = request();
        $shop_id = !empty($param['shop_id']) ? intval($param['shop_id']) : '';
        $cart_id = !empty($param['cart_id']) ? intval($param['cart_id']) : '';
        $inter_id = !empty($param['id']) ? addslashes($param['id']) : '';

        if (empty($cart_id) || empty($inter_id) || empty($shop_id))
        {
            ajax_return(400,'请求参数错误');
        }

        $this->load->model('ticket/ticket_cart_model');
        $where_arr = array(
            'cart_id' => $cart_id,
            'inter_id' => $inter_id,
            'openid' => $this->openid,
        );

        $cart_info = $this->ticket_cart_model->get_cart_info($where_arr);
        if (!empty($cart_info))
        {
            $where_del = array(
                'cart_id' => $cart_info['cart_id'],
            );
            $this->ticket_cart_model->del_cart($where_del);

            $filter['buy_type'] = 1;
            $filter['shop_id'] = $shop_id;
            $filter['openid'] = $this->openid;
            $filter['inter_id'] = $inter_id;
            $count = $this->select_cart_goods($filter);

            ajax_return(200,'删除成功',$count['total']);
        }
        else
        {
            ajax_return(404,'已删除商品');
        }
    }

    /**
     * 用户购物车
     */
    public function cart_list()
    {
        $param = request();
        $shop_id = !empty($param['shop_id']) ? intval($param['shop_id']) : '';
        $inter_id = !empty($param['id']) ? addslashes($param['id']) : '';
        if (empty($inter_id) || empty($shop_id))
        {
            ajax_return(400,'请求参数错误');
        }

        //店铺信息
        $shop_info = $this->shop_info($inter_id,'',$shop_id);

        if (empty($shop_info))
        {
            ajax_return(404,'店铺不存在');
        }

        # 获取购物车信息
        $where_arr = array(
            'shop_id'	=> $shop_id,
            'buy_type'	=> 1,
            'openid'	=>  $this->openid,
        );

        $cart_goods =  $this->select_cart_goods($where_arr,$shop_info);
        $cart_goods['shop'] = array(
            'shop_name' => $shop_info['shop_name'],
        );

        $cart_goods = array_merge($cart_goods,$this->common_data);
        ajax_return(200,'成功',$cart_goods);
    }

    /**
     * 清空购物车
     */
    public function clear_cart()
    {
        $param  = request();
        $filter['shop_id'] = !empty($param['shop_id']) ? intval($param['shop_id']) : '';
        $filter['inter_id'] = !empty($param['id']) ? addslashes($param['id']) : '';

        if (empty($filter['shop_id']) || empty($filter['inter_id']))
        {
            ajax_return(400,'请求参数错误');
        }
        $filter['openid'] = $this->openid;

        $this->load->model('ticket/ticket_cart_model');
        $res = $this->ticket_cart_model->del_cart($filter);
        if ($res['rows'] > 0)
        {
            ajax_return(200,'清空购物车成功');
        }
        else
        {
            ajax_return(404,'购物车已清空');
        }
    }

    /**
     * 	tog_selected
     *	description : 更改购物车选中状态
     *	author		: loven
     *	date		: 2017-5-31
     */
    public function tag_selected()
    {
        $param = request();

        $cart_id 		= !empty($param['cart_id']) 	? trim($param['cart_id']) 	: '';
        $shop_id 		= !empty($param['shop_id']) 	? trim($param['shop_id']) 	: '';
        $is_selected 	= !empty($param['is_selected']) ? intval($param['is_selected']) : 1; # 1：选择，2：未选中
        $inter_id 	    = !empty($param['id']) ? addslashes($param['id']) : '';
        $type 	        = !empty($param['type']) ? intval($param['type']) : 0; # 0：单选，1：全选

        # 判断购物车ID 是否存在
        if (empty($cart_id) && $type == 0)
        {
            ajax_return(400,'请求参数错误');
        }

        $this->load->model('ticket/ticket_cart_model');
        //查询是否购物车存在商品
        $where = array(
            'cart_id'  => $cart_id,
            'openid'    => $this->openid,
            'inter_id'    => $inter_id,
            'buy_type'    => 1,
        );
        $cart_info = $this->ticket_cart_model->get_cart_info($where);

        if (!empty($cart_info))
        {
            $update['selected'] = $is_selected;
            $this->ticket_cart_model->update_cart($update,array('cart_id'=>$cart_info['cart_id']));

            $filter['buy_type'] = 1;
            $filter['shop_id'] = $shop_id;
            $filter['openid'] = $this->openid;
            $filter['inter_id'] = $inter_id;
            $count = $this->select_cart_goods($filter);
            ajax_return(200,'操作成功',$count['total']);
        }
        else
        {
            ajax_return(404,'请求的资源不存在');
        }
    }

    /**
     * 获取商品
     */
    public function get_ticket_goods()
    {
        $inter_id   = $this->inter_id;
        $shop_id    = $this->input->post('shop_id',true);
        $group_id   = $this->input->post('group_id',true);
        if(empty($inter_id) || empty($shop_id))
        {
            ajax_return(0,'参数错误');
        }
        //查询商铺是否营业
        $shop = $this->shop_info($inter_id,'',$shop_id);
        if(empty($shop))
        {
            ajax_return(0,'店铺不存在');
        }

        //查询分组下的商品信息
        $goods_where = array(
            'inter_id'  => $inter_id,
            'shop_id'   => $shop_id,
            'is_delete' => 0,
            'sale_type' => 4,
            'sale_now'  => 1,
        );

        if(!empty($group_id))
        {
            if(is_array($group_id))
            {
                $goods_where['in_group_id'] = $group_id;
            }
            else if($group_id == 'is_re')
            {
                $goods_where['is_recommend'] = 1;
            }
            else
            {
                $goods_where['group_id'] = $group_id;
            }
        }

        $this->load->model('roomservice/roomservice_goods_model');
        $goods_info = $this->roomservice_goods_model->get_front_goods_list($goods_where);
        $goods_ids = $recommend_ids = array();
        $group_arr = array();
        if($goods_info)
        {
            //查询所有分组的信息
            $group_where = array(
                'inter_id'  => $this->inter_id,
                'shop_id'   => $shop_id,
                'status'    => 1,
                'is_delete' => 0,
                'sale_type' => 4,
            );

            $this->load->model('roomservice/roomservice_goods_group_model');
            $group = $this->roomservice_goods_group_model->get_goods_group_info($group_where);
            if(!empty($group))
            {
                foreach($group as $k=>$v)
                {
                    $group_arr[$v['group_id']] = $v;
                }
                unset($group);
            }

            //过滤掉没到时间开售的商品
            foreach ($goods_info as $gk => $gv)
            {
                $goods_info[$gk]['btn_status'] = 1;
                $curtime = date('H:i');
                if($gv['sale_now'] ==3 || ($gv['sale_now'] == 2 && ($gv['sale_start_time'] > "{$curtime}" || $gv['sale_end_time'] < "{$curtime}" ) ) ||$gv['sale_status'] ==2){//定时开售

                    $goods_info[$gk]['btn_status'] = 0;
                }

                $goods_ids[] = $gv['goods_id'];
                if($gv['is_recommend'] == 1)
                {
                    //记录热门推荐id
                    $recommend_ids[] = $gv['goods_id'];
                }
                //图片数据处理 取第一张
                $goods_img = !empty($gv['goods_img']) ? json_decode($gv['goods_img'],true) : '';
                if (!empty($goods_img))
                {
                    $goods_img = !empty($goods_img[0]) ? $goods_img[0] : '';
                }

                $goods_info[$gk]['goods_img'] = $goods_img ;
            }
        }

        if(!empty($goods_ids))
        {
            $date = date('Y-m-d');
            //处理核销规格信息
            $this->load->model('roomservice/roomservice_ticket_spu_model');
            $this->load->model('roomservice/roomservice_ticket_dateprice_model');
            $spec_arr = $this->roomservice_ticket_spu_model->get_goods_spu_info(array('inter_id' => $this->inter_id), $goods_ids);
            $dateprice = $this->roomservice_ticket_dateprice_model->get_goods_dateprice_info(array('goods_id' => $goods_ids,'date' => "$date",'goods_price' => 0));
            //print_r($spec_arr); print_r($dateprice);exit;
            if (!empty($spec_arr))
            {
                foreach ($goods_info as $k => $v)
                {
                    $spec_list = array();//规格数组
                    if (isset($spec_arr[$v['goods_id']]) && !empty($spec_arr[$v['goods_id']]) )
                    {
                        foreach ($spec_arr[$v['goods_id']] as $sk => $sv)
                        {
                            if (isset($dateprice[$v['goods_id']][$sv['spu_id']]) && !empty($dateprice[$v['goods_id']][$sv['spu_id']]))
                            {
                                $sv['date'] = $dateprice[$v['goods_id']][$sv['spu_id']]['date'];
                                $sv['specprice'] = $dateprice[$v['goods_id']][$sv['spu_id']]['goods_price'];
                                $sv['stock'] = $dateprice[$v['goods_id']][$sv['spu_id']]['goods_stock'];
                                $sv['spec_id'] = $sk +1;
                                $spec_list[$sv['spu_id']] = $sv;
                            }
                            //未设置日历价格
                            else
                            {
                                $sv['date'] = '';
                                $sv['specprice'] = 0;
                                $sv['stock'] = 0;
                                $sv['spec_id'] = $sk +1;
                                $spec_list[$sv['spu_id']] = $sv;
                            }
                        }
                    }
                    $goods_info[$k]['spec_list'] = json_encode($spec_list, JSON_UNESCAPED_UNICODE);
                }
            }
            //按照分组组装数据

            foreach($goods_info as $k=>$v)
            {
                if(isset($group_arr[$v['group_id']]) && !empty($group_arr[$v['group_id']]))
                {
                    $group_arr[$v['group_id']]['goods_info'][] = $v;
                }
            }
            $sort_arr = array();
            foreach($group_arr as $k=>$v)
            {
                if(isset($v['goods_info']) && !empty($v['goods_info']))
                {
                    $sort_arr[] = $v; //去掉索引
                }
                else
                {
                    //删掉没数据的分组
                    unset($group_arr[$k]);
                }
            }
            unset($group_arr);
        }

        if($goods_info)
        {
            if(!empty($group_id) && $group_id == 'is_re')
            {
                $return['data'] = $goods_info;
            }
            else
            {
                $return['data'] = $sort_arr;
            }
            ajax_return('200','成功',$return['data']);
        }
        else
        {
            ajax_return('200','无商品');
        }
    }

    /**
     * 结算接口
     */
    public function checkout()
    {
        $param = request();
        $cart_ids = !empty($param['cart_id']) ? addslashes($param['cart_id']) : '';
        $inter_id = !empty($param['id']) ? addslashes($param['id']) : '';
        $shop_id = !empty($param['shop_id']) ? intval($param['shop_id']) : '';
        $hotel_id = !empty($param['hotel_id']) ? intval($param['hotel_id']) : '';

        if (empty($cart_ids) || empty($inter_id) || empty($shop_id))
        {
            ajax_return(400,'请求参数错误');
        }

        //获取店铺信息
        $this->load->model('roomservice/roomservice_shop_model');
        //店铺信息
        $shop_info = $this->shop_info($inter_id,'',$shop_id);
        if (empty($shop_info))
        {
            ajax_return(404,'店铺不存在');
        }

        # 获取购物车信息
        $where_arr = array(
            'shop_id'	=> $shop_id,
            'inter_id'	=> $inter_id,
            'openid'	=> $this->openid,
            'selected'	=> 1,
            'cart_id'	=> explode(',',$cart_ids),
        );

        $cart_goods =  $this->select_cart_goods($where_arr,$shop_info);
        if (empty($cart_goods['goods']))
        {
            $res_ajax= array(
                'cart_url' => site_url('ticket/book/cart_list?id='.$inter_id.'&shop_id='.$shop_id.'&hotel_id='.$hotel_id),
            );
            ajax_return(410,'暂无结算商品',$res_ajax);
        }
        //金额
        $total = array(
            'discount_fee' => 0,
            'total_fee' => $cart_goods['total']['money'],
        );

        //用户信息
        $user_info = array(
            'username' => '',
            'phone' => '',
            'verify_id' => '',
        );
        $this->load->model('roomservice/roomservice_verify_model');
        $verify_where = array(
            'shop_id' => $shop_id,
            'openid'   => $this->openid,
            'inter_id' => $inter_id,
        );
        $verity = $this->roomservice_verify_model->get_one($verify_where,true);
        if (!empty($verity))
        {
            $user_info = array(
                'username' => $verity['verify_name'],
                'phone' => $verity['verify_phone'],
                'verify_id' => $verity['verify_id'],
            );
        }

        //计算优惠信息
        if (!empty($cart_goods['goods']))
        {
            //提前预约优惠金额
            $goods_info = $cart_goods['goods'];
            $order_goods = array();
            $total_fee = $discount_fee = $pay_fee = 0;
            $cart_id = array();
            foreach ($goods_info as $key => $value)
            {
                //按照规格ID 预约日期 拆单处理
                $order_goods[$key]['goods'][] = $value;

                //提前预约优惠金额
                $ticket_discount_fee = count_ticket_fee($order_goods[$key]['goods']);

                $this->load->model('roomservice/roomservice_orders_model');
                $total = $this->roomservice_orders_model->calculate_total($ticket_discount_fee['goods_info'],$shop_info,$ticket_discount_fee['discount_fee']);

                $total['discount'] = formatMoney($ticket_discount_fee['discount_fee']);
                $total['book_day'] = $value['book_day'];

                $total['row_total'] = round($total['row_total'],2);
                $total['discount_total'] = round($total['discount_total'],2);
                $total['sub_total'] = round($total['sub_total'],2);

                //处理子单优惠金额问题
                $discount_total = $total['row_total'] - $total['sub_total'];
                if ($discount_total < $total['discount_total'])
                {
                    $total['discount_total'] = $discount_total;
                }

                //总订单金额信息
                $total_fee += $total['row_total'];
                $discount_fee += $total['discount_total'];
                $pay_fee += $total['sub_total'];
            }

            foreach ($cart_goods['goods'] as $key => $value)
            {
                $value['goods_price'] = $value['shop_price'];
                $cart_goods['goods'][$key] = $value;
            }
        }

        $total = array();
        $total['discount_fee'] = $discount_fee;
        $total['row_total'] = $total_fee;
        $total['total_fee'] = $pay_fee;

        //支付方式
        $payment = array('1'=>'微信支付');

        $ajax_arr = array(
            'goods' => $cart_goods['goods'],
            'total' => $total,
            'user_info' => $user_info,
            'shop' => array('shop_name' => $shop_info['shop_name']),
            'payment' => $payment,
        );

        write_log($ajax_arr);
        $ajax_arr = array_merge($ajax_arr,$this->common_data);

        ajax_return(200,'成功',$ajax_arr);
    }

    /**
     * 生成订单接口
     */
    public function save_order()
    {
        $param = request();
        $order = array();
        $cart_ids = !empty($param['cart_id']) ? addslashes($param['cart_id']) : '';
        $order['inter_id'] = !empty($param['id']) ? addslashes($param['id']) : '';
        $order['shop_id'] = !empty($param['shop_id']) ? intval($param['shop_id']) : '';
        $order['username'] = !empty($param['username']) ? addslashes($param['username']) : '';
        $order['phone'] = !empty($param['phone']) ? addslashes($param['phone']) : '';
        $order['user_note'] = !empty($param['user_note']) ? addslashes($param['user_note']) : '';
        $verify_id = !empty($param['verify_id']) ? intval($param['verify_id']) : '';

        if (empty($cart_ids) || empty($order['inter_id']) || empty($order['shop_id']))
        {
            ajax_return(400,'请求参数错误');
        }

        if (empty($order['username']) || empty($order['phone']))
        {
            ajax_return(400,'请填写完整预约信息');
        }

        //获取店铺信息
        $this->load->model('roomservice/roomservice_shop_model');
        //店铺信息
        $shop_info = $this->shop_info($order['inter_id'],'',$order['shop_id']);
        if (empty($shop_info))
        {
            ajax_return(404,'店铺不存在');
        }
        $order['hotel_id'] = $shop_info['hotel_id'];

        # 获取购物车信息
        $where_arr = array(
            'shop_id'	=> $order['shop_id'],
            'inter_id'	=> $order['inter_id'],
            'openid'	=> $this->openid,
            'selected'	=> 1,
            'cart_id'	=> explode(',',$cart_ids),
        );

        $cart_goods =  $this->select_cart_goods($where_arr,$shop_info);

        //添加预约信息
        $this->load->model('roomservice/roomservice_verify_model');
        if (!empty($verify_id))
        {
            if (!empty($order['username']))
            {
                $verify_data['verify_name'] = $order['username'];
            }
            if (!empty($order['username']))
            {
                $verify_data['verify_phone'] = $order['phone'];
            }
            $verify_data['add_time'] = date('Y-m-d H:i:s');
            $this->roomservice_verify_model->update_data($verify_data,array('verify_id'=>$verify_id));
        }
        else
        {
            //插入数据
            $verify_data = array(
                'shop_id' => $order['shop_id'],
                'inter_id' => $order['inter_id'],
                'hotel_id' => $order['hotel_id'],
                'openid' => $this->openid,
                'verify_name' => !empty($order['username']) ? $order['username'] : '',
                'verify_phone' => !empty($order['phone']) ? $order['phone'] : '',
                'add_time' => date('Y-m-d H:i:s'),
            );
            $this->roomservice_verify_model->insert_data($verify_data);
        }

        //计算优惠信息
        if (!empty($cart_goods['goods']))
        {
            $goods_info = $cart_goods['goods'];
            $order_goods = array();
            $total_fee = $discount_fee = $pay_fee = 0;
            $cart_id = array();
            foreach ($goods_info as $key => $value)
            {
                //检测库存
                if ($value['status'] == 2)
                {
                    ajax_return(422,"预约时间已过期，请重新预约");
                }
                else if($value['status'] == 3)
                {
                    ajax_return(422," 预约时间已无库存，请重新预约");
                }
                else if($value['status'] == 4)
                {
                    ajax_return(422,"{$value['goods_name']} 商品已下架");
                }

                //$this->check_goods_stock($value,$value['goods_num']);
                //校验日期是否可以预约
                $this->check_is_book_goods($value,$shop_info);

                //按照规格ID 预约日期 拆单处理
                $order_goods[$key]['goods'][] = $value;

                //提前预约优惠金额
                $ticket_discount_fee = count_ticket_fee($order_goods[$key]['goods']);
                $goods = $ticket_discount_fee['goods_info'];
                $this->load->model('roomservice/roomservice_orders_model');
                $total = $this->roomservice_orders_model->calculate_total($ticket_discount_fee['goods_info'],$shop_info,$ticket_discount_fee['discount_fee']);

                $total['discount'] = formatMoney($ticket_discount_fee['discount_fee']);
                $total['book_day'] = $value['book_day'];

                $total['row_total'] = round($total['row_total'],2);
                $total['discount_total'] = round($total['discount_total'],2);
                $total['sub_total'] = round($total['sub_total'],2);

                //处理子单优惠金额问题
                $discount_total = $total['row_total'] - $total['sub_total'];
                if ($discount_total < $total['discount_total'])
                {
                    $total['discount_total'] = $discount_total;
                }

                //总订单金额信息
                $total_fee += $total['row_total'];
                $discount_fee += $total['discount_total'];
                $pay_fee += $total['sub_total'];

                $order_goods[$key]['goods'] = $goods;
                $order_goods[$key]['total'] = $total;

                //购物车ID
                $cart_id[] = $value['cart_id'];
            }

            //创建订单
            //扣减库存（先减库存，再生成订单）
            $this->db->trans_begin(); //开启事务

            //处理支付金额精度问题 防止 优惠 + 支付金额 < 总金额
            $pay_fee_order = formatMoney($pay_fee);
            $discount_fee = formatMoney($discount_fee);
            $total_fee = formatMoney($total_fee);

            $pay_fee = $total_fee - $discount_fee;
            if ($pay_fee_order > $pay_fee)
            {
                $pay_fee = $pay_fee_order;
            }

            //主单数据
            $orders_merge = array(
                'inter_id' => $order['inter_id'],
                'shop_id' => $order['shop_id'],
                'hotel_id' => $order['hotel_id'],
                'openid' => $this->openid,
                'order_no' => getOrderNo(),
                'add_time' => date('Y-m-d H:i:s'),
                'pay_fee' => $pay_fee,
                'discount_fee' => $discount_fee,
                'total_fee' => $total_fee,
                'user_note' => $order['user_note'],
                'username' => $order['username'],
                'phone' => $order['phone'],
                'pay_way' => 1,
            );

            write_log($orders_merge);
            $this->load->model('ticket/ticket_orders_merge_model');
            $merge_orderId = $this->ticket_orders_merge_model->add_order($orders_merge);

            //按照购物车拆单
            if ($merge_orderId > 0)
            {
                //扣减库存（先减库存，再生成订单）
                $this->load->model('roomservice/roomservice_ticket_dateprice_model');
                $stock = $this->roomservice_ticket_dateprice_model->reduce_item_stock($cart_goods['goods']);
                if($stock == false)
                {
                    $this->db->trans_rollback();//回滚
                    ajax_return(404,'库存不足,扣减失败');
                }

                $this->load->model('roomservice/roomservice_orders_model');
                $this->load->model('roomservice/roomservice_orders_item_model');
                //创建子订单
                foreach ($order_goods as $item)
                {
                    //子订单号
                    $order_sn_num = 'HX' . getOrderNo();
                    //组装订单数据
                    $order_info = array(
                        'inter_id'  => $order['inter_id'],
                        'hotel_id'  => $order['hotel_id'],
                        'shop_id'   => $order['shop_id'],
                        'openid'    => $this->openid,
                        'order_sn'  => $order_sn_num,
                        'merge_order_no'  => $orders_merge['order_no'],//总订单号
                        'order_status' => 0,//订单状态 未确认
                        'pay_status' => 2,//付款状态 未付款
                        'pay_way'   => 1,//付款方式 1微信支付 2储值  3线下支付
                        'row_total' => formatMoney($item['total']['row_total']),//商品总额
                        'sub_total' => formatMoney($item['total']['sub_total']),//订单应付金额=商品总额-各种优惠-各种活动+配送费+服务费
                       //'pay_money' => formatMoney($item['total']['sub_total']),//订单应付金额=商品总额-各种优惠-各种活动+配送费+服务费
                        'discount_fee' => formatMoney($item['total']['sub_total']),//订单优惠后金额=商品总额-各种优惠-各种活动
                        'discount_id' => 0,
                        'cover_charge' => 0,//服务费
                        'discount_money' => formatMoney($item['total']['discount_total']),
                        'consignee' => $order['username'],//收货人姓名 或者房间 桌号
                        'phone'     => $order['phone'],
                        'type'      => 4,//类型 1房间 2堂食 3 外卖 ，4 预约核销
                        'is_lock'   => 0,//是否锁定订单
                        'add_time'  => date('Y-m-d H:i:s'),
                        'note'      => $order['user_note'],
                        'from_ip'   =>  get_client_ip(),
                        'dissipate'   => !empty($item['total']['book_day']) ? $item['total']['book_day'] : '',
                        'ticket_discount_fee'   => !empty($item['total']['discount']) ? $item['total']['discount'] : 0,
                    );

                    write_log($order_info);
                    //生成子订单
                    $order_id = $this->roomservice_orders_model->create_order($order_info);
                    if ($order_id > 0)
                    {
                        //创建订单商品 ORDERS_ITEM
                        $order_item = array();
                        foreach($item['goods'] as $gk=>$gv)
                        {
                            $order_item[$gk]['order_id'] = $order_id;
                            $order_item[$gk]['inter_id'] = $order['inter_id'];
                            $order_item[$gk]['shop_id']  = $order['shop_id'];
                            $order_item[$gk]['openid']   = $this->openid;
                            $order_item[$gk]['goods_id'] = $gv['goods_id'];
                            $order_item[$gk]['setting_id']  = $gv['spu_id'];
                            $order_item[$gk]['spec_id']  = $gv['spu_id'];
                            $order_item[$gk]['goods_name'] = $gv['goods_name'];
                            $order_item[$gk]['spec_name'] = $gv['spu_name'];
                            $order_item[$gk]['goods_num'] = $gv['goods_num'];
                            $order_item[$gk]['goods_price'] = $gv['shop_price'];
                            $order_item[$gk]['goods_img'] = $gv['goods_img'];
                            $order_item[$gk]['book_day'] = $gv['book_day'];
                            $order_item[$gk]['ticket_discount_fee'] = !empty($gv['discount']) ? $gv['discount'] : 0;
                        }

                        write_log($order_item);
                        $res_order_item = $this->roomservice_orders_item_model->insert_batch_item($order_item);
                        if (!$res_order_item)
                        {
                            $this->db->trans_rollback();
                            ajax_return(404,'插入订单详情失败');
                        }

                        //插入数据 记录action
                        $this->db->insert('roomservice_action',array('inter_id'=>$order['inter_id'],'order_id'=>$order_id,'type'=>2,'content'=>'订单提交成功','add_time'=>date('Y-m-d H:i:s'),'order_status'=>0));
                    }
                }
            }

            if ($this->db->trans_status() == FALSE)
            {
                $this->db->trans_rollback();
                ajax_return(404,'创单失败');
            }
            else if($order_id > 0 && $merge_orderId > 0)
            {
                //提交事务
                $this->db->trans_complete();

                //删除购物车
                if (!empty($cart_id))
                {
                    $this->load->model('ticket/ticket_cart_model');
                    $where_del = array(
                        'cart_id' => $cart_id,
                    );
                    $this->ticket_cart_model->batch_del_cart($where_del);
                }
            }

            $pay_url = site_url('wxpay/ticket_book_pay') . '?id=' .$order['inter_id'] .'&hotel_id='.$order['hotel_id'].'&order_id=' .$merge_orderId;
            $res = array(
                'orderId' => $merge_orderId,
                'orderNo' => $orders_merge['order_no'],
                'pay_money' => $orders_merge['pay_fee'],
                'inter_id' => $order['inter_id'],
                'pay_url' => $pay_url,
            );

            write_log($res);
            ajax_return(200,'成功',$res);
        }
        else
        {
            ajax_return(404,'创单失败，购物车已无商品');
        }
    }

    /**
     * 用户订单中心
     */
    public function order_list()
    {
        $param = request();
        $type = !empty($param['type']) ? intval($param['type']) : '';//1-待消费，2-已消费，0-全部
        $shop_id = !empty($param['shop_id']) ? intval($param['shop_id']) : '';
        $inter_id = !empty($param['id']) ? addslashes($param['id']) : '';
        $per_page = !empty($param['limit']) ? intval($param['limit']) : 20;
        $cur_page = !empty($param['offset']) ?intval($param['offset']) : 1;

        if (empty($shop_id) || empty($inter_id))
        {
            ajax_return(400,'请求参数错误');
        }

        $this->load->model('roomservice/roomservice_goods_model');
        $this->load->model('ticket/ticket_orders_merge_model');
        $this->load->model('roomservice/roomservice_orders_model');
        $this->load->model('roomservice/roomservice_orders_item_model');;
        $filter = array(
            'inter_id' => $inter_id,
            'shop_id' => $shop_id,
            'openid' => $this->openid,
        );

        if (!empty($type))
        {
            $filter['order_status'] = $type;
        }

        //查询记录数
        $total = $this->ticket_orders_merge_model->get_count($filter);

        $arr_page = get_page($total, $cur_page, $per_page);

        $orders = $this->ticket_orders_merge_model->get_list($filter, $cur_page, $per_page);

        $order_list = array();
        if (!empty($orders))
        {
            foreach ($orders as $key => $value)
            {
                $again_url = site_url('ticket/book/goods_list') . '?id=' .$value['inter_id'] .'&hotel_id='.$value['hotel_id'].'&shop_id=' .$value['shop_id'];
                $url = site_url('/ticket/book/order_detail?id='.$this->inter_id.'&hotel_id='.$value['hotel_id'].'&shop_id='.$value['shop_id'].'&order_id='.$value['merge_orderId']);
                $status_btn = $value['pay_status'] == 0 && $value['order_status'] == 0 ? 4 : $value['order_status'];
                $pay_url = '';
                if ($status_btn == 4)
                {
                    $pay_url = site_url('wxpay/ticket_book_pay') . '?id=' .$value['inter_id'] .'&hotel_id='.$value['hotel_id'].'&order_id=' .$value['merge_orderId'];
                }

                $info = array(
                    'order_id' => $value['merge_orderId'],
                    'order_no' => $value['order_no'],
                    'discount_fee' => $value['discount_fee'],
                    'total_fee' => $this->auto_handle_price($value['total_fee']),
                    'pay_fee' => $this->auto_handle_price($value['pay_fee']),
                    'pay_status' => $value['pay_status'],
                    'order_status' => $value['order_status'],
                    'status_name' => $this->status_name($value),
                    'status_btn' => $status_btn > 0 ? $status_btn : 1,// 1-待消费，2-已消费，3-已取消,4-未支付
                    'url' => $url,
                    'pay_url' => $pay_url,
                    'again_url' => $again_url,
                );

                $order_list[$value['order_no']]['goods'] = array();
                $order_list[$value['order_no']]['info'] = $info;
                $merge_order[] = $value['order_no'];
            }

            $filter = array(
                'shop_id' => $shop_id,
                'openid' => $this->openid,
            );
            $select = 'order_id,order_sn,order_status,pay_status,pay_way,row_total,sub_total,discount_fee,pay_time,add_time,merge_order_no';
            $orders_info = $this->roomservice_orders_model->get_orders($filter,$merge_order,$select);
            $order_tmp = array();
            foreach ($orders_info as $value)
            {
                $order_tmp[$value['merge_order_no']][$value['order_id']] = $value;
                $order_id[] = $value['order_id'];
            }

            $select = 'goods_id,spec_id,goods_name,goods_num,goods_price,spec_name,book_day,goods_img,order_id';
            $order_item = $this->roomservice_orders_item_model->get_order_item($filter,$order_id,$select);

            $goods_item = array();
            if (!empty($order_item))
            {
                foreach ($order_tmp as $key => $item)
                {
                    foreach ($item as $k => $value)
                    {
                        foreach ($order_item as $val)
                        {
                            if ($k == $val['order_id'])
                            {
                                $goods_item[$key][] = $val;
                            }
                        }
                    }
                }
            }

            //组装数据
            foreach ($order_list as $key => $value)
            {
                $goods_list = $goods_item[$key];
                $goods_num = 0;
                foreach ($goods_list as $k=>$item)
                {
                    $item['goods_price'] = $this->auto_handle_price($item['goods_price']);
                    $goods_list[$k] = $item;
                    $goods_num += $item['goods_num'];
                }
                $order_list[$key]['goods'] = $goods_list;
                $order_list[$key]['info']['goods_num'] = $goods_num;
            }
        }

        //购物车数量
        $filter_cart = array(
            'shop_id' => $shop_id,
            'inter_id' => $inter_id,
            'openid' => $this->openid,
            'buy_type' => 1,
        );

        $ajax_arr = array(
            'list' => array_values($order_list),
            'page'  => $arr_page,
            'cart'  => $this->count_cart_num($filter_cart),
        );

        $ajax_arr = array_merge($ajax_arr,$this->common_data);

        ajax_return(200,'成功',$ajax_arr);
    }

    /**
     * 取消订单
     */
    public function cancel_order()
    {
        $param = request();
        $filter['shop_id'] = !empty($param['shop_id']) ? intval($param['shop_id']) : '';
        $filter['inter_id'] = !empty($param['id']) ? addslashes($param['id']) : '';
        $filter['order_id'] = !empty($param['order_id']) ? intval($param['order_id']) : '';

        if (empty($filter['order_id']) || empty($filter['inter_id']))
        {
            ajax_return(400,'请求参数错误');
        }

        $filter['openid'] = $this->openid;
        $this->load->model('ticket/ticket_orders_merge_model');
        $order_info = $this->ticket_orders_merge_model->get_order_info($filter);
        write_log($order_info);

        if (empty($order_info))
        {
            ajax_return(404,'订单不存在');
        }
        else if ($order_info['pay_status'] > 0)
        {
            ajax_return(401,'订单已支付，不可取消订单');
        }
        else if ($order_info['order_status'] != 0)
        {
            ajax_return(401,'不可操作取消订单');
        }

        /**
         * 查询总单下的子订单 更改子单状态 => 更改总单状态 开启事务，成功提交
         */

        $this->load->model('roomservice/roomservice_orders_model');
        $where_arr = array(
            'inter_id' => $filter['inter_id'],
            'openid' => $filter['openid'],
            'merge_order_no' => $order_info['order_no'],
        );
        $orders = $this->roomservice_orders_model->get_orders($where_arr,'','*');
        write_log($orders);
        if(!empty($orders))
        {
            $this->db->trans_begin(); //开启事务

            foreach ($orders as $key => $order)
            {
                $orderModel = $this->roomservice_orders_model;
                if($order['order_status'] == $orderModel::OS_PER_CANCEL || $order['order_status'] == $orderModel::OS_HOL_CANCEL ||
                    $order['order_status'] == $orderModel::OS_SYS_CANCEL || $order['order_status'] == $orderModel::OS_FINISH)
                {
                    //取消或者完成
                   continue;
                }

                if($order['order_status'] == $orderModel::OS_CONFIRMED)
                {
                    #订单已经接单，请电话联系取消
                    continue;
                }

                //记录订单操作日志
                $order_log = array(
                    'inter_id' => $order['inter_id'],
                    'order_id' => $order['order_id'],
                    'hotel_id' => $order['hotel_id'],
                    'shop_id'  => $order['shop_id'],
                    'operation' => '',
                    'order_status'=>$orderModel::OS_PER_CANCEL,
                    'add_time'=> date('Y-m-d H:i:s'),
                    'action_note'=>'前台用户主动取消',
                    'types' => 1,//
                );
                //查询付款状态
                if($order['pay_status'] == $orderModel::IS_PAYMENT_NOT)
                {
                    //未支付 无须退款
                    //取消
                    $res = $this->roomservice_orders_model->cancel_order($order);
                    if($res)
                    {
                        //发送模板消息
                        $orderModel->handle_order($order['inter_id'],$order['order_id'],'',25);
                        $array = array(
                            'inter_id'=> $order['inter_id'],
                            'openid' =>'',
                            'order_id' => $order['order_id'],
                            'type' => 2,//跟踪
                            'content'=>'订单已取消',
                            'order_status'=>$orderModel::OS_PER_CANCEL,
                            'add_time' => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('roomservice_action',$array);
                        $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录
                        write_log($array);
                    }
                    else
                    {
                        $this->db->trans_rollback();//回滚
                        ajax_return(404,'取消订单失败');
                    }
                }
                else
                {
                    $this->db->trans_rollback();//回滚
                    ajax_return(404,'订单不可取消');
                }
            }

            //更改总单状态
            $update = array(
                'update_time' => date('Y-m-d H:i:s'),
                'order_status' => 3,
            );
            $where = array(
                'merge_orderId' => $order_info['merge_orderId'],
                'order_status' => 0,
                'pay_status' => 0,
            );
            $res = $this->ticket_orders_merge_model->update_order($update,$where);
            if ($res > 0)
            {
                //提交事务
                $this->db->trans_complete();
                ajax_return(200,'取消订单成功');
            }
            else
            {
                $this->db->trans_rollback();//回滚
                ajax_return(422,'取消订单失败');
            }
        }
        else
        {
            ajax_return(404,'暂无操作订单');
        }
    }

    /**
     * 订单详情
     */
    public function order_detail()
    {
        $param = request();
        $filter['shop_id'] = !empty($param['shop_id']) ? intval($param['shop_id']) : '';
        $filter['inter_id'] = !empty($param['id']) ? addslashes($param['id']) : '';
        $filter['order_id'] = !empty($param['order_id']) ? intval($param['order_id']) : '';

        if (empty($filter['order_id']) || empty($filter['inter_id']))
        {
            ajax_return(400,'请求参数错误');
        }

        $filter['openid'] = $this->openid;
        $this->load->model('ticket/ticket_orders_merge_model');
        $order_info = $this->ticket_orders_merge_model->get_order_info($filter);
        if (empty($order_info))
        {
            ajax_return(404,'订单不存在');
        }
        $order_info['status_name'] = $this->status_name($order_info);
        $order_info['pay_url'] = site_url('wxpay/ticket_book_pay') . '?id=' .$order_info['inter_id'] .'&hotel_id='.$order_info['hotel_id'].'&order_id=' .$order_info['merge_orderId'];
        $order_info['again_url'] = site_url('ticket/book/goods_list') . '?id=' .$order_info['inter_id'] .'&hotel_id='.$order_info['hotel_id'].'&shop_id=' .$order_info['shop_id'];

        $status_btn = $order_info['pay_status'] == 0 && $order_info['order_status'] == 0 ? 4 : $order_info['order_status'];
        $order_info['order_btn'] = $status_btn;//0-待确认，1-待消费，2-已消费，3-已取消,4-未支付

        $order_info['out_time'] = 0;
        if ($status_btn == 4)
        {
            //返回倒计时
            $order_info['out_time'] = strtotime($order_info['add_time']) + (15 * 60) - time();
            if ($order_info['out_time'] < 0)
            {
                $order_info['out_time'] = 0;
            }
            else
            {
                $order_info['out_time'] = $order_info['out_time'] * 1000;
            }
        }

        //获取店铺信息
        $this->load->model('roomservice/roomservice_shop_model');
        $this->load->model('roomservice/roomservice_goods_model');
        $this->load->model('roomservice/roomservice_orders_model');
        $this->load->model('roomservice/roomservice_orders_item_model');
        //店铺信息
        $shop = $this->roomservice_shop_model->get(array('shop_id' => $order_info['shop_id'],'inter_id'=>$order_info['inter_id']),'one','shop_name');

        //获取酒店信息
        $this->load->model('hotel/hotel_model');
        $hotel =  $this->hotel_model->get_hotel_detail($order_info['inter_id'],$order_info['hotel_id']);
        $shop['hotel_name'] = !empty($hotel['name']) ? $hotel['name'] : '';
        $shop['hotel_address'] = !empty($hotel['address']) ? $hotel['address'] : '';
        $shop['hotel_latitude'] = !empty($hotel['latitude']) ? $hotel['latitude'] : '';
        $shop['hotel_longitude'] = !empty($hotel['longitude']) ? $hotel['longitude'] : '';
        $shop['hotel_tel'] = !empty($hotel['tel']) ? $hotel['tel'] : '';
        $shop['hotel_img'] = !empty($hotel['intro_img']) ? $hotel['intro_img'] : '';

        //查询订单商品
        $filter = array(
            'merge_order_no' => $order_info['order_no'],
            'shop_id' => $order_info['shop_id'],
            'openid' => $this->openid,
        );
        $select = 'order_id,order_sn,order_status,pay_status,pay_way,row_total,sub_total,discount_fee,pay_time,add_time,merge_order_no';
        $orders_info = $this->roomservice_orders_model->get_orders($filter,'',$select);
        $order_id = array();
        foreach ($orders_info as $value)
        {
            $order_id[] = $value['order_id'];
            $orders_info[$value['order_id']] = $value;
        }

        $filter = array(
            'shop_id' => $order_info['shop_id'],
            'openid' => $this->openid,
        );
        $select = 'goods_id,spec_id,goods_name,goods_num,goods_price,spec_name,book_day,goods_img,order_id';
        $order_item = $this->roomservice_orders_item_model->get_order_item($filter,$order_id,$select);

        if (!empty($order_item))
        {
            $order_name = array('0'=> '待确认','5'=>'待消费','20'=>'已消费','25'=>'已取消','26'=>'已取消','27'=>'已取消');
            foreach ($order_item as $key => $value)
            {
                $value['goods_price'] = $this->auto_handle_price($value['goods_price']);
                $value['pay_status'] = $orders_info[$value['order_id']]['pay_status'];
                $value['order_status'] = $orders_info[$value['order_id']]['order_status'];
                $value['order_name'] = !empty($order_name[$value['order_status']]) ? $order_name[$value['order_status']] : '';
                $order_item[$key] = $value;
            }
        }

        $res_ajax = array(
            'shop' => $shop,
            'order' => $order_info,
            'goods' => $order_item,
        );

        $res_ajax = array_merge($res_ajax,$this->common_data);
        ajax_return(200,'成功',$res_ajax);
    }

    /**
     * 微信配置
     */
    public function wx_config()
    {
        $param = request();
        $inter_id = !empty($param['id']) ? addslashes($param['id']) : '';
        $shop_id = !empty($param['shop_id']) ? intval($param['shop_id']) : '';
        $hotel_id = !empty($param['hotel_id']) ? intval($param['hotel_id']) : '';
        $url = !empty($param['url']) ? addslashes($param['url']) : '';
        $this->load->model('wx/Access_token_model');

        $config_wx = $this->Access_token_model->getSignPackage($inter_id,$url);
        $res_ajax = array(
            'wx_config' => $config_wx,
            'host' => site_url(),
            'index_url' => site_url('ticket/book/goods_list?id='.$inter_id.'&shop_id='.$shop_id.'&hotel_id='.$hotel_id),
            'cart_url' => site_url('ticket/book/cart_list?id='.$inter_id.'&shop_id='.$shop_id.'&hotel_id='.$hotel_id),
            'order_url' => site_url('ticket/book/order_list?id='.$inter_id.'&shop_id='.$shop_id.'&hotel_id='.$hotel_id),
            'checkout_url' => site_url('ticket/book/checkout?id='.$inter_id.'&shop_id='.$shop_id.'&hotel_id='.$hotel_id),
        );

        $res_ajax = array_merge($res_ajax,$this->common_data);
        ajax_return(200,'成功',$res_ajax);
    }


    /**
     * 处理订单状态
     * @param $order
     * @return string
     */
    private function status_name($order)
    {
        $name = array('待确认','待消费','已消费','已取消');//0-待确认，1-待消费，2-已消费，3-已取消
        if ($order['pay_status'] == 0 && $order['order_status'] == 0)
        {
            return '未支付';
        }
        else
        {
            return $name[$order['order_status']];
        }
    }


    /**
     * 查询购物车商品
     */
    private function select_cart_goods($filter,$shop_info = '')
    {
        $this->load->model('ticket/ticket_cart_model');
        $select = 'cart_id,spu_id,goods_id,shop_id,inter_id,buy_type,spu_name,goods_num,book_day,selected';
        $cart_info = $this->ticket_cart_model->user_cart_info($filter,$select);

        $total = array(
            'num' => '0',
            'money' => '0',
        );
        if (!empty($cart_info))
        {
            $this->load->model('roomservice/roomservice_ticket_dateprice_model');
            foreach ($cart_info as $key => $value)
            {
                //获取商品信息
                $this->load->model('roomservice/roomservice_goods_model');
                $filter = array(
                    'shop_id'   => $value['shop_id'],
                    'inter_id'  => $value['inter_id'],
                    'goods_id'  => $value['goods_id'],
                    'is_delete' => '0',
                );

                //获取商品信息
                $select = 'goods_name,sale_status,sale_num,goods_img,sale_now,shop_price,ticket_credits,ticket_day,ticket_style,ticket_limit,ticket_sale_time';
                $goods = $this->roomservice_goods_model->get_goods_detail($filter,$select);

                $value['goods_price'] = '0';
                $value['goods_stock'] = '0';
                $value['status'] = '4';
                $value['status_name'] = '已下架';

                $value['goods_name'] = !empty($goods['goods_name']) ? $goods['goods_name'] : '';
                $value['goods_img'] = !empty($goods['goods_img']) ? json_decode($goods['goods_img'],true)[0] : '';

                if (!empty($goods) && $goods['sale_now'] == 1)
                {
                    $value['ticket_credits'] = $goods['ticket_credits'];
                    $value['ticket_day'] = $goods['ticket_day'];
                    $value['ticket_style'] = $goods['ticket_style'];
                    $value['ticket_limit'] = $goods['ticket_limit'];

                    $value['status'] = '1';//1-正常，2-已过期，3-库存不足,4-已下架
                    $value['status_name'] = '正常';
                    //判断库存
                    $where = array(
                        'goods_id' => $value['goods_id'],
                        'spu_id' => $value['spu_id'],
                        'inter_id' => $value['inter_id'],
                        'date' => $value['book_day'],
                    );
                    $price = $this->roomservice_ticket_dateprice_model->check_goods_price($where);
                    if (!empty($price))
                    {
                        if ($price['goods_stock'] == 0 || ($price['goods_stock'] < $value['goods_num']))
                        {
                            $value['status'] = '3';
                            $value['status_name'] = '库存不足';
                            $value['selected'] = '2';
                        }

                        $value['goods_price'] = $price['goods_price'];
                        $value['goods_stock'] = $price['goods_stock'];
                    }
                    //不存在SKU
                    else
                    {
                        $value['status'] = '4';
                        $value['selected'] = '2';
                        $value['status_name'] = '已下架';
                    }

                    //过期优先展示
                    $status_today = 1;
                    if (!empty($shop_info))
                    {
                        $status_today = check_time_range($shop_info['time_range'],$goods['ticket_sale_time']);
                    }

                    if ($value['book_day'] < date('Y-m-d') || ($status_today == 0 && $value['book_day'] == date('Y-m-d')))
                    {
                        $value['status'] = '2';
                        $value['status_name'] = '已过期';
                        $value['selected'] = '2';
                    }
                }
                else
                {
                    $value['selected'] = '2';
                }

                //处理优惠价格
                $value['shop_price'] = $this->auto_handle_price($value['goods_price']);
                $value['goods_price'] = handle_ticket_price($goods,$value['goods_price'],$value['book_day']);
                $value['goods_price'] = $this->auto_handle_price($value['goods_price']);

                //计算购物车数量和选中总计金额
                $total['num'] += $value['goods_num'];

                if ($value['selected'] == 1 && $value['status'] == 1)
                {
                    $total['money'] += $value['goods_price'] * $value['goods_num'];
                }
                $value['num'] = $value['goods_num'];

                $cart_info[$key] = $value;
            }
        }

        $total['money'] = $this->auto_handle_price(formatMoney($total['money']));
        $res = array(
            'goods' => $cart_info,
            'total' => $total,
        );

        return $res;
    }

    /**
     * 计算购物车商品数量
     */
    private function count_cart_num($filter)
    {
        $num = 0;
        $this->load->model('ticket/ticket_cart_model');
        $select = 'goods_num';
        $cart_info = $this->ticket_cart_model->user_cart_info($filter,$select);
        if (!empty($cart_info))
        {
            foreach ($cart_info as $value)
            {
                $num += $value['goods_num'];
            }
        }

        return $num;
    }

    /**
     * 校验价格日历库存
     * @param $goodsInfo
     * @param $goods_number
     */
    private function check_goods_stock($goodsInfo,$goods_number)
    {
        $this->load->model('roomservice/roomservice_ticket_dateprice_model');
        $filter = array(
            'goods_id' => $goodsInfo['goods_id'],
            'spu_id' => $goodsInfo['spu_id'],
            'inter_id' => $goodsInfo['inter_id'],
            'date' => $goodsInfo['book_day'],
        );
        $goods_price = $this->roomservice_ticket_dateprice_model->check_goods_price($filter);
        if (!empty($goods_price))
        {
            //判断库存
            if ($goods_price['goods_stock'] < $goods_number)
            {
                ajax_return(410,'预约的日期无更多库存');
            }
        }
        else
        {
            ajax_return(410,'预约的日期暂时不开售');
        }
    }

    //获取商铺信息
    private function shop_info($inter_id = '',$hotel_id = '',$shop_id = '')
    {
        $arr = array();
        $arr['inter_id'] = $inter_id;
        if($hotel_id)
        {
            $arr['hotel_id'] = $hotel_id;
            //读取酒店信息
            $this->load->model ( 'hotel/Hotel_model' );
            $hotel = $this->Hotel_model->get_hotel_detail($inter_id,$hotel_id);
            if(empty($hotel))
            {
                return false;//酒店不存在或者停用了
            }
        }
        $arr['shop_id'] = $shop_id;
        $arr['status'] = 1;//开业
        $arr['is_delete'] = 0;//正常
        $this->load->model ( 'roomservice/Roomservice_shop_model' );
        $shop = $this->Roomservice_shop_model->get($arr);
        if($shop)
        {
            //店铺是正常的状态  查看是否在营业时间
            //判断店铺营业时间
            $shop_status = 0;//歇业
            $date = date('w')?date('w'):7;//星期几
            $sale_days = empty($shop['sale_days'])?array():explode(',',$shop['sale_days']);
            if(in_array($date,$sale_days))
            {
                $shop_status = 1;//正常营业
            }
            //优惠数据处理
            if(!empty($shop['discount_type']) && !empty($shop['discount_config']))
            {
                $shop['discount_config'] = unserialize($shop['discount_config']);
            }
            $shop['shop_status'] = $shop_status;
            return $shop;
        }
        else
        {
            return false;//店铺已经删除 或不存在
        }
    }

    /**
     * 校验日期是否可以预约
     * @param $goods
     * @param $shop_info
     */
    private function check_is_book_goods($goods, $shop_info)
    {
        //判断消费限制 为0时暂不限制
        $date = strtotime("+ {$shop_info['advance_book_num']}days");
        $out_date = date('Y-m-d 23:59:59',$date);

        if ($out_date < $goods['book_day'])
        {
            if ($shop_info['advance_book_num'] > 0)
            {
                $msg = '可提前'.$shop_info['advance_book_num'].'天预订';
            }
            else
            {
                $msg = '只能提前24小时预订';
            }
            ajax_return(422,$msg);
        }
    }

    /**
     * 自动省略价格后面 00
     * @param $price
     * @return int
     */
    private function auto_handle_price($price)
    {
        $temp = explode('.', $price);
        if (!empty($temp[1]) && $temp[1] > 0)
        {
            return $price;
        }
        else
        {
            return $price > 0 ? intval($price) : 0;
        }
    }

}