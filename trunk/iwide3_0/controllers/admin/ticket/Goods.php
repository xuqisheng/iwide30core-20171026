<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goods extends MY_Admin_Ticket {

	protected $label_module= '房间订餐';
	protected $label_controller= '商品列表';
	protected $label_action= '商品列表';
	
	function __construct(){
		parent::__construct();
        $this->load->helper('appointment');
	}
	

	function index(){

        $filter = array();
        $filter['inter_id'] = $this->inter_id;
        $filter['sale_type'] = 4;
        //get请求接收参数
        $params= $this->input->get();
        $filter['sale_status'] = $this->input->get('sale_status');
        $filter['shop_id'] = $this->input->get('shop_id')?$this->input->get('shop_id'):'';
        $filter['group_id'] = $this->input->get('group_id')?$this->input->get('group_id'):'';
        $filter['wd'] = $this->input->get('wd')?addslashes($this->input->get('wd')):'';
        if(is_array($params) && count($params)>0 )
            $params= array_merge($params, $filter);
        //post请求接收参数
        $post = $this->input->post();
        if(is_array($post)){
            $params = array_merge($post,$filter);
        }//var_dump($this->uri->segment(3));die;
        $search_url = '?';
        if($filter['wd']!=''){
            $search_url .= 'wd='.$filter['wd'].'&';
        }
        if($filter['shop_id']){
            $search_url .= 'shop_id='.$filter['shop_id'].'&';
        }
        if($filter['group_id']){
            $search_url .= 'group_id='.$filter['group_id'].'&';
        }
        if($filter['sale_status']){
            $search_url .= 'sale_status='.$filter['sale_status'].'&';
        }
        if($search_url=='?'){
            $search_url = '';
        }else{
            $search_url = rtrim($search_url,'&');
        }
        $per_page = 30;
        $cur_page = empty($this->uri->segment(4)) ? 0 : ($this->uri->segment(4));
        $this->load->model('roomservice/roomservice_shop_model');
        $filterH = array('inter_id'=>$this->inter_id);
        if(!empty($this->session->get_admin_hotels())){
            $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
            if(empty($filter['hotel_id'])){
                $filter['hotel_id'] = $this->session->get_admin_hotels();
            }
        }

        //处理关键词搜索导致店铺找不到问题
        $wd = $filter['wd'];
        unset($filter['wd']);
        $shops_arr = $this->roomservice_shop_model->get_list($filter);
        $shops = array();
        if($shops_arr){
            foreach($shops_arr as $v){
                $shops[$v['shop_id']] = $v;
            }
            unset($shops_arr);
        }
        $filter['wd'] = $wd;

        //酒店信息
        //获取公众号下的酒店
        $this->load->model ( 'hotel/hotel_model' );
        $hotels = $this->hotel_model->get_hotel_hash ($filterH );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );

        //inter_id下的分组
        $this->load->model('roomservice/roomservice_goods_group_model');
        $group = $this->roomservice_goods_group_model->get_list($filter);//var_dump($res);die;

        //获取商品信息
        $this->load->model('roomservice/roomservice_goods_model');

        $res = $this->roomservice_goods_model->get_page($filter,$cur_page,$per_page);
        $data = isset($res[1])?$res[1]:array();
        $total_count = isset($res[0])?$res[0]:0;

        if(!empty($data)){

            $this->load->model('roomservice/roomservice_ticket_dateprice_model');

            foreach ($data as $k => $v) {
                $where_spec = array(
                    'goods_id' => $v['goods_id'],
                );
                $spec_arr = $this->roomservice_ticket_dateprice_model->get_low_price($where_spec);

                $data[$k]['shop_price'] = !empty($spec_arr['goods_price']) ? $spec_arr['goods_price'] : 0;
            }
        }
        //分页
        $base_url = site_url('/ticket/goods/index/');
        $first_url = site_url('/ticket/goods/index/').$search_url;
        $suffix = $search_url;
        $this->pagination($per_page,$cur_page,$base_url,$total_count,4,$first_url,$search_url,$suffix);
        $view_params = array (
            'pagination' => $this->pagination->create_links (),
            'shops' => $shops,
          //  'group' => $group,
            'filter'=>$filter,
            'hotels'=>$hotels,
            'res' =>$data,
            'inter_id' => $this->inter_id,
            'total'=>$total_count,
        );
        echo $this->_render_content ( $this->_load_view_file ( 'index' ), $view_params, TRUE );
	}

    public function time_price()
    {
        $view_params = array (

        );
        echo $this->_render_content ( $this->_load_view_file ( 'time_price' ), $view_params, TRUE );
    }
	public function add()
	{//var_dump($_POST['spec_list']);die;
		if($this->inter_id== FULL_ACCESS){
            $message= $this->session->put_notice_msg('超管!');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
        };
       $post =  $this->input->post ();//var_dump($post);die;
        if($post){//add数据
            if(empty($post['goods_name']) ){
                $this->session->put_notice_msg('名字不能为空！');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/add'));
            }
            $data = array();
            $data['sale_type'] = 4;//门票类型
            $data['inter_id'] = $this->inter_id;
            $data['goods_name'] = $post['goods_name'];
            $data['hotel_id'] = $post['hotel_id'];
            $data['shop_id'] = $post['shop_id'];
            $data['is_recommend'] = isset($post['is_recommend'])?$post['is_recommend']:0;
            $data['group_id'] = $post['group_id'];
            $data['stock'] = isset($post['stock'])&&!empty($post['stock'])?$post['stock']:0;
            $data['sale_status'] = 2;
            $data['shop_price'] = isset($post['shop_price'])?$post['shop_price']:0;
            $data['is_show_stock'] = isset($post['is_show_stock'])?$post['is_show_stock']:0;
            $data['goods_sn'] = isset($post['goods_sn'])&&!empty($post['goods_sn'])?$post['goods_sn']:'';
            $data['goods_img'] = isset($post['goods_img'])&&!empty($post['goods_img'])?$post['goods_img']:'';
            $data['sort_order'] = isset($post['sort_order'])&&!empty($post['sort_order'])?$post['sort_order']:0;
            $data['add_time'] = date('Y-m-d H:i:s');
            //规格
            $data['spec_list'] = !empty($post['spec_list'])?$post['spec_list']:'';

            $data['goods_desc'] = !empty($post['goods_desc'])?$post['goods_desc']:'';
            $data['goods_notice'] = !empty($post['goods_notice'])?$post['goods_notice']:'';
            $data['goods_alias'] = !empty($post['goods_alias'])?addslashes($post['goods_alias']):'';

            //消费时段
            $data['ticket_sale_time'] = !empty($post['ticket_sale_time']) ? json_encode($post['ticket_sale_time']) : '';

            //开售时间
            $data['sale_now'] = isset($post['sale_now'])&&!empty($post['sale_now'])?$post['sale_now']:1;
            if($data['sale_now'] == 1){//立即开售
                $data['sale_time'] = '';
                $data['sale_start_time'] = '';
                $data['sale_end_time'] = '';
                $data['sale_status'] = 1;//上架状态-上架中
            }
            else if($data['sale_now'] == 2) //定时开售
            {
                $data['sale_time'] = isset($post['sale_time'])&&!empty($post['sale_time'])?$post['sale_time']:'';
                $data['sale_status'] = 1;//上架状态-上架中
                $data['sale_start_time'] = isset($post['sale_start_time'])&&!empty($post['sale_start_time'])?trim($post['sale_start_time']):'';
                $data['sale_end_time'] = isset($post['sale_end_time'])&&!empty($post['sale_end_time'])?trim($post['sale_end_time']):'';
            }
            else //不开售
            {
                //$data['sale_status'] = 2;//上架状态-下架中
            }

            //门票优惠
            $data['ticket_credits'] = isset($post['ticket_credits'])&&!empty($post['ticket_credits'])? intval($post['ticket_credits']) : 1;
            $data['ticket_day'] = isset($post['ticket_day'])&&!empty($post['ticket_day'])? intval(trim($post['ticket_day'])) : 0;
            $data['ticket_style'] = isset($post['ticket_style'])&&!empty($post['ticket_style'])? intval($post['ticket_style']) : 1;
            $data['ticket_limit'] = isset($post['ticket_limit'])&&!empty($post['ticket_limit'])? trim($post['ticket_limit']) : 0;

            //分享内容
            $data['share_img'] = addslashes(trim($post['share_img']));
            $data['share_title'] = addslashes(trim($post['share_title']));
            $data['share_spec'] = addslashes(trim($post['share_spec']));

            //$filter['sort_order'] = $post['sort_order'];
            $this->load->model('roomservice/roomservice_goods_model');
            $goods_id = $this->roomservice_goods_model->save_goods_data($data);//规格信息在这个方法里面加入

            if (!empty($goods_id))
            {
                //添加、编辑spu 和 价格日历生成
                if (!empty($post['spu']))
                {
                    $this->load->model('roomservice/roomservice_ticket_spu_model');
                    $this->load->model('roomservice/roomservice_ticket_dateprice_model');
                    $this->load->model('ticket/ticket_goods_log_model');

                    //添加规格
                    $goods = array(
                        'goods_id' => $goods_id,
                        'inter_id' => $this->inter_id,
                    );

                    $spu_res = $this->roomservice_ticket_spu_model->update_spu_data($post,$goods);
                    $spu_ids = $spu_res['spu_ids'];
                    //生成价格日历
                    //获取配置
                    $cache = $this->_load_cache();
                    $redis = $cache->redis->redis_instance();
                    $key_redis = 'ticket_goods__'.$this->inter_id.$this->user_agent();
                    $setting = $redis->get($key_redis);

                    if (!empty($setting))
                    {
                        $setting = json_decode($setting,true);
                        $this->roomservice_ticket_dateprice_model->ticket_dateprice($setting,$this->inter_id,$goods_id,$spu_ids);
                        $redis->del($key_redis);
                    }

                    //处理单个日期
                    $key_redis_one = 'ticket_goods_one__'.$this->inter_id.$this->user_agent();
                    $setting_one = $redis->get($key_redis_one);

                    if (!empty($setting_one))
                    {
                        $setting_one = json_decode($setting_one,true);
                        $this->roomservice_ticket_dateprice_model->ticket_dateprice_one($setting_one,$this->inter_id,$goods_id,$spu_res);
                        $redis->del($key_redis_one);
                    }

                    //写入操作日志
                    $this->admin_profile = $this->session->userdata('admin_profile');
                    $add_log = array(
                        'key_redis' => $key_redis,
                        'key_redis_one' => $key_redis_one,
                        'spu' => $post['spu'],
                        'setting' => !empty($setting) ? $setting : '',
                        'setting_one' => !empty($setting_one) ? $setting_one : '',
                    );
                    $data['goods_id'] = $goods_id;
                    $this->ticket_goods_log_model->add_log($data,$this->admin_profile,json_encode($add_log),1);
                }
            }

            $message= ($goods_id)?
                $this->session->put_success_msg('新增成功'):
                $this->session->put_notice_msg('新增失败');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
            die;
        }
        //页面
        //获取公众号下的酒店
        $this->load->model ( 'hotel/hotel_model' );
        $filterH = array('inter_id'=>$this->inter_id);
        if(!empty($this->session->get_admin_hotels())){
            $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
        }
        $hotels = $this->hotel_model->get_hotel_hash ($filterH );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
        //获取店铺
        $this->load->model('roomservice/roomservice_shop_model');
        //$filter['inter_id'] = $this->inter_id;
        $filterH['sale_type'] = 4;
        $shops = $this->roomservice_shop_model->get_list($filterH);
        //获取商品分组
        $this->load->model('roomservice/roomservice_goods_group_model');
      //  $filterH['shop_id'] = $shops[0]['shop_id'];//取第一个
       // $group = $this->db->get_where('roomservice_goods_group',$filterH)->result_array();
        $this->load->model('roomservice/roomservice_spec_setting_model');
        $auto_increment_id = $this->roomservice_spec_setting_model->get_spec_auto_increment_id();

        $spu_data = array(
            'data' => date('Y/n'),
            'month' => $this->month_spu_price(date('Y-n')),
        );
        $spu_data = json_encode($spu_data);

        $view_params = array(
            'hotel' => $hotels,
            'shops' => $shops,
         //   'group'=>$group,
            'inter_id'=>$this->inter_id,
            'auto_increment_id' => $auto_increment_id,
            'spu_data' => $spu_data,
        );
        $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
        echo $html;
	}

    //编辑
    public function edit()
    {
        if($this->inter_id== FULL_ACCESS){
            $message= $this->session->put_notice_msg('超管!');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
        };
        $post =  $this->input->post ();
        $id = $this->input->get('ids',true);
        if(empty($id)){
            echo 'empty id';
            die;
        }
        //获取该条信息
        $res = $this->db->get_where('roomservice_goods',array('goods_id'=>$id))->row_array();
        if($post){//update数据
            if(empty($post['goods_name'])){
                $this->session->put_notice_msg('名字不能为空！');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/edit?ids='.$id));
            }
            //删除存在的spu
            $del_spu_id = !empty($post['del_spu_id']) ? trim($post['del_spu_id'],',') : '';

            $data = array();
            $data['sale_type'] = 4;//门票类型
            $data['inter_id'] = $this->inter_id;
            $data['goods_name'] = $post['goods_name'];
            $data['hotel_id'] = $post['hotel_id'];
            $data['shop_id'] = $post['shop_id'];
            $data['is_recommend'] = isset($post['is_recommend'])?$post['is_recommend']:0;
            $data['group_id'] = $post['group_id'];
            $data['stock'] = isset($post['stock'])&&!empty($post['stock'])?$post['stock']:0;
            $data['shop_price'] = isset($post['shop_price'])?$post['shop_price']:0;
            $data['is_show_stock'] = isset($post['is_show_stock'])?$post['is_show_stock']:0;
            $data['goods_sn'] = isset($post['goods_sn'])&&!empty($post['goods_sn'])?$post['goods_sn']:'';
            $data['goods_img'] = isset($post['goods_img'])&&!empty($post['goods_img'])?$post['goods_img']:'';
            $data['sort_order'] = isset($post['sort_order'])&&!empty($post['sort_order'])?$post['sort_order']:0;
            //规格
            $data['spec_list'] = !empty($post['spec_list'])?$post['spec_list']:'';
            $data['goods_desc'] = !empty($post['goods_desc'])?$post['goods_desc']:'';
            $data['goods_notice'] = !empty($post['goods_notice'])?$post['goods_notice']:'';

            $data['goods_alias'] = !empty($post['goods_alias'])?addslashes($post['goods_alias']):'';

            //消费时段
            $data['ticket_sale_time'] = !empty($post['ticket_sale_time']) ? json_encode($post['ticket_sale_time']) : '';

            //开售时间
            $data['sale_now'] = isset($post['sale_now'])&&!empty($post['sale_now'])?$post['sale_now']:1;

            if($data['sale_now'] == 1){//立即开售
                $data['sale_time'] = '';
                $data['sale_start_time'] = '';
                $data['sale_end_time'] = '';
                $data['sale_status'] = 1;//上架状态-上架中
            }
            else if($data['sale_now'] == 2) //定时开售
            {
                $data['sale_time'] = isset($post['sale_time'])&&!empty($post['sale_time'])?$post['sale_time']:'';
                $data['sale_status'] = 1;//上架状态-上架中
                $data['sale_start_time'] = isset($post['sale_start_time'])&&!empty($post['sale_start_time'])?trim($post['sale_start_time']):'';
                $data['sale_end_time'] = isset($post['sale_end_time'])&&!empty($post['sale_end_time'])?trim($post['sale_end_time']):'';
            }
            else //不开售
            {
                //$data['sale_status'] = 2;//上架状态-下架中
            }

            //门票优惠
            $data['ticket_credits'] = isset($post['ticket_credits'])&&!empty($post['ticket_credits'])? intval($post['ticket_credits']) : 1;
            $data['ticket_day'] = isset($post['ticket_day'])&&!empty($post['ticket_day'])? intval(trim($post['ticket_day'])) : 0;
            $data['ticket_style'] = isset($post['ticket_style'])&&!empty($post['ticket_style'])? intval($post['ticket_style']) : 1;
            $data['ticket_limit'] = isset($post['ticket_limit'])&&!empty($post['ticket_limit'])? trim($post['ticket_limit']) : 0;

            //分享内容
            $data['share_img'] = addslashes(trim($post['share_img']));
            $data['share_title'] = addslashes(trim($post['share_title']));
            $data['share_spec'] = addslashes(trim($post['share_spec']));

            $this->load->model('roomservice/roomservice_goods_model');
            $where = array(
                'goods_id' => $id,
                'inter_id' => $this->inter_id,
            );
            $result = $this->roomservice_goods_model->update_ticket_goods($data,$where);

            //处理规格
            if ($result['data'] == 1)
            {
                $this->load->model('roomservice/roomservice_ticket_spu_model');
                $this->load->model('roomservice/roomservice_ticket_dateprice_model');
                $this->load->model('ticket/ticket_goods_log_model');
                //删除spu
                if (!empty($del_spu_id))
                {
                    $where_del = array(
                        'goods_id'  => $id,
                        //'inter_id'  => $this->inter_id,
                        'spu_id'    => explode(',',$del_spu_id),
                    );
                    $this->roomservice_ticket_spu_model->delete_spu($where_del);
                    $this->roomservice_ticket_dateprice_model->delete_dateprice($where_del);
                }

                //添加、编辑spu 和 价格日历生成
                if (!empty($post['spu']))
                {
                    $goods = array(
                        'goods_id' => $id,
                        'inter_id' => $this->inter_id,
                    );

                    $spu_res = $this->roomservice_ticket_spu_model->update_spu_data($post,$goods);
                    $spu_ids = $spu_res['spu_ids'];
                    //生成价格日历
                    //获取配置
                    $cache = $this->_load_cache();
                    $redis = $cache->redis->redis_instance();
                    $key_redis = 'ticket_goods_'. $id .'_'.$this->inter_id.$this->user_agent();
                    $setting = $redis->get($key_redis);
                    if (!empty($setting))
                    {
                        $setting = json_decode($setting,true);
                        $this->roomservice_ticket_dateprice_model->ticket_dateprice($setting,$this->inter_id,$id,$spu_ids);
                        $redis->del($key_redis);
                    }

                    //处理单个日期
                    $key_redis_one = 'ticket_goods_one_'. $id.'_'.$this->inter_id.$this->user_agent();
                    $setting_one = $redis->get($key_redis_one);
                    if (!empty($setting_one))
                    {
                        $setting_one = json_decode($setting_one,true);
                        $this->roomservice_ticket_dateprice_model->ticket_dateprice_one($setting_one,$this->inter_id,$id,$spu_ids);
                        $redis->del($key_redis_one);
                    }

                    //写入操作日志
                    $this->admin_profile = $this->session->userdata('admin_profile');
                    $add_log = array(
                        'key_redis' => $key_redis,
                        'key_redis_one' => $key_redis_one,
                        'spu' => $post['spu'],
                        'setting' => !empty($setting) ? $setting : '',
                        'setting_one' => !empty($setting_one) ? $setting_one : '',
                    );
                    $data['goods_id'] = $id;
                    $this->ticket_goods_log_model->add_log($data,$this->admin_profile,json_encode($add_log),2);
                }
            }

            $message= ($result)?
                $this->session->put_success_msg('更新成功'):
                $this->session->put_notice_msg('更新失败');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
            die;
        }
        //页面

        if(!empty($res)){//var_dump($res);die;
            //获取公众号下的酒店
            $this->load->model ( 'hotel/hotel_model' );
            $filterH = array('inter_id'=>$this->inter_id);
            if(!empty($this->session->get_admin_hotels())){
                $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
            }
            $hotels = $this->hotel_model->get_hotel_hash ($filterH );
            $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
            //获取店铺
            $this->load->model('roomservice/roomservice_shop_model');
            //$filter['inter_id'] = $this->inter_id;
            $filterH['sale_type'] = 4;
            $shops = $this->roomservice_shop_model->get_list($filterH);
            //获取商品分组
            $this->load->model('roomservice/roomservice_goods_group_model');
            $filter['shop_id'] = $res['shop_id'];//取第一个
            $filter['inter_id'] = $this->inter_id;
            $group = $this->db->get_where('roomservice_goods_group',$filter)->result_array();
            //处理规格
            $this->load->model('roomservice/roomservice_ticket_spu_model');
            $spec_arr = $this->roomservice_ticket_spu_model->goods_spu(array('inter_id' => $this->inter_id,'goods_id' => $id));

            //查找店铺
            $shop_sale_time = array();
            if ($shops)
            {
                foreach ($shops as $value)
                {
                    if ($value['shop_id'] == $res['shop_id'])
                    {
                        $shop_sale_time = !empty($value['time_range']) ? json_decode($value['time_range'],true) : array();
                    }
                }
            }

            if (!empty($shop_sale_time))
            {

                $ticket_sale_time = !empty($res['ticket_sale_time']) ? json_decode($res['ticket_sale_time'],true) : array();
                foreach ($shop_sale_time as $key =>  $item)
                {
                    $item['checked'] = 0;
                    if (!empty($ticket_sale_time) && in_array($item['name'],$ticket_sale_time,true))
                    {
                        $item['checked'] = 1;
                    }
                    $shop_sale_time[$key] = $item;
                }
            }

            //获取本月价格日历
            $this->load->model('roomservice/roomservice_ticket_dateprice_model');

            $start = date('Y-m-01');
            $end_time = date('Y-m-t');
            $where_arr = array(
                'goods_id'  => $id,
                'date'      => "date between '{$start}' and '{$end_time}'",
            );

            $price_data = $this->roomservice_ticket_dateprice_model->get_goods_dateprice_info($where_arr,2);
            $price_data = $this->get_low_price($price_data);
            $spu_data = array(
                'data' => date('Y/n'),
                'month' => $this->month_spu_price(date('Y-n'),$price_data),
            );
            $spu_data = json_encode($spu_data);

            $view_params = array(
                'spu' => $spec_arr,
                'posts' => $res,
                'hotel' => $hotels,
                'shops' => $shops,
                'group'=>$group,
                'inter_id'=>$this->inter_id,
                'ids' => $id,
                'shop_sale_time' => $shop_sale_time,
                'spu_data' => $spu_data,
             );

            $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
            echo $html;
        }else{
            echo 'empty data';
            die;
        }

    }

    //开售 售罄 状态
    public function sale_status(){
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());
        $goods_id = $this->input->post('goods_id',true)?$this->input->post('goods_id',true):'';
        $shop_id = $this->input->post('shop_id',true)?$this->input->post('shop_id',true):'';
        $status = $this->input->post('status',true);
        $inter_id = $this->inter_id;
        $hotel_id = $this->hotel_id;
        $where = array();
        $where['inter_id'] = $inter_id;
        $where['shop_id'] = $shop_id;
        $where['goods_id'] = $goods_id;
        $where['is_delete'] = 0;
        if($status==1){//说明商品是售罄
            //先查询商品库存

            $goods = $this->db->get_where('roomservice_goods',$where)->row_array();
            if($goods){
                if($goods['stock']== 0){
                    $return['msg'] = '库存为0,无法开售';
                    echo json_encode($return);
                    die;
                }else{
                    //处理更新
                    $update = $this->db->update('roomservice_goods',array('sale_status'=>1),$where);
                    if(!empty($update)){
                        $return['errcode'] = 0;
                        $return['msg'] = '开售成功';
                        echo json_encode($return);
                        die;
                    }else{
                        $return['msg'] = '更新失败';
                        echo json_encode($return);
                        die;
                    }
                }
            }
        }else{
            $update = $this->db->update('roomservice_goods',array('sale_status'=>2),$where);
            if(!empty($update)){
                $return['errcode'] = 0;
                $return['msg'] = '更新成功';
                echo json_encode($return);
                die;
            }else{
                $return['msg'] = '更新失败';
                echo json_encode($return);
                die;
            }
        }
    }


    /**
     * 处理价格
     * @param array $list
     * @return array
     */
    public function get_low_price($list = array())
    {
        if (!empty($list))
        {
            foreach ($list as $key => $value)
            {
                $list[$key] = !empty($value) ? $value[0] : array();
            }
        }
        return $list;
    }
    /**
     * 获取 日历价格
     */
    public function get_dateprice_info()
    {
        $param = $this->input->post();
        $type = isset($param['type']) && !empty($param['type']) ? intval($param['type']) : 0;//获取方式
        $goods_id = isset($param['goods_id']) && !empty($param['goods_id']) ? intval($param['goods_id']) : '';
        $month = isset($param['month']) && !empty($param['month']) ? intval($param['month']) : date('n');
        $year = isset($param['year']) && !empty($param['year']) ? intval($param['year']) : date('Y');

        //获取缓存按照月份价格
        $price_data = $this->cache_date_price($year,$month,$goods_id);
        //编辑场景
        if (!empty($goods_id))
        {
            $this->load->model('roomservice/roomservice_ticket_dateprice_model');

            $start = date('Y-m-01',strtotime($year.'-'.$month));
            $end_time = date('Y-m-t',strtotime($year.'-'.$month));
            $where_arr = array(
                'goods_id'  => $goods_id,
                'date'      => "date between '{$start}' and '{$end_time}'",
            );

            $price_data_table = $this->roomservice_ticket_dateprice_model->get_goods_dateprice_info($where_arr,2);
            $price_data_table = $this->get_low_price($price_data_table);
            //合并 cache 覆盖数据库的数据
            if (!empty($price_data))
            {
                foreach ($price_data as $key => $value)
                {
                    if (!empty($price_data_table[$key]) && empty($value['goods_price']))
                    {
                        $price_data[$key]['goods_price'] = $price_data_table[$key]['goods_price'];
                        $price_data[$key]['goods_stock'] = $price_data_table[$key]['goods_stock'];
                        unset($price_data_table[$key]);
                    }
                    else if(!empty($price_data_table[$key]) && !empty($value['goods_price']))
                    {
                        unset($price_data_table[$key]);
                    }
                }
            }

            if (!empty($price_data_table) && !empty($price_data))
            {
                $price_data = array_merge($price_data,$price_data_table);
            }
            else if(!empty($price_data_table) && empty($price_data))
            {
                $price_data = $price_data_table;
            }

            //输出每月价格
            $spu_data = array(
                'data' => $year.'/'.$month,
                'month' => $this->month_spu_price($year.'-'.$month,$price_data),
            );
        }
        //新增场景
        else
        {
            //输出每月价格
            $spu_data = array(
                'data' => $year.'/'.$month,
                'month' => $this->month_spu_price($year.'-'.$month,$price_data),
            );
        }
        $this->load->model('roomservice/roomservice_ticket_spu_model');
        $this->load->model('roomservice/roomservice_ticket_dateprice_model');

        ajax_return(200,'成功',$spu_data);
    }

    /**
     * 按照月份获取缓存返回每月价格日历数据
     * @param $year
     * @param $month
     * @param $goods_id
     * @return array
     */
    public function cache_date_price($year,$month,$goods_id = '')
    {
        //获取配置
        $cache = $this->_load_cache();
        $redis = $cache->redis->redis_instance();
        $key_redis = 'ticket_goods_'. $goods_id.'_'.$this->inter_id.$this->user_agent();
        $setting = $redis->get($key_redis);

        $price_data = array();
        $min_arr = array();
        if (!empty($setting))
        {
            $setting = json_decode($setting,true);
            $arr2 = array();
            if(!empty($setting['spu_data']))
            {
                //处理spu 最低价格作为页面展示
                foreach($setting['spu_data'] as $k=>$v)
                {
                    if (!empty($v['spu_data_price']))
                    {
                        $arr2[$k] = trim($v['spu_data_price']);
                    }
                }
                $min = '-1';
                if (!empty($arr2))
                {
                    $min = min($arr2);
                }

                foreach($setting['spu_data'] as $k=>$v)
                {
                    if (!empty($v['spu_data_price']) && $v['spu_data_price'] == $min)
                    {
                        $min_arr = array(
                            'spu_id'        => $v['spu_id'],
                            'goods_price'   => trim($v['spu_data_price']),
                            'goods_stock'   => trim($v['spu_data_stock']),
                            'date_id'       => 0,
                        );
                    }
                }
            }

            //处理开始到结束日期的日历
            $firstday = $year.'-'.$month.'-1';
            $this_start_month   = strtotime($firstday);
            $this_end_month     = strtotime("{$firstday} +1 month -1 day");

            $start_time = strtotime($setting['price_start_time']);//开始时间
            $end_time = strtotime($setting['price_end_time']);//结束时间
            while($start_time <= $end_time)
            {
                //判断取出当月数据

                if ($end_time >= $this_start_month && $end_time <= $this_end_month)
                {
                    //判断某天是周几
                    $this_week = date('w', $end_time);
                    $this_week = $this_week == 0 ? 7 : $this_week;//周日
                    if (!empty($setting['week']) && !in_array($this_week, $setting['week']))
                    {
                        $min_arr_ontin = array(
                            'spu_id' => 0,
                            'goods_price' => 0,
                            'goods_stock' => 0,
                            'date_id' => 0,
                        );
                    }

                    //组装数据
                    $price_data[date('Y-n-j', $end_time)] = !empty($min_arr_ontin) ? $min_arr_ontin : $min_arr;//得到dataarr的日期数组。
                }
                $end_time = $end_time - 86400;
                unset($min_arr_ontin);
            }
        }
        return $price_data;
    }


    /**
     * 获取店铺营业时段
     */
    public function get_shop_time_range()
    {
        $param = $this->input->post();
        $shop_id = !empty($param['shop_id']) ? intval($param['shop_id']) : '';
        $return = array();
        $status = 0;
        if (!empty($shop_id))
        {
            $this->load->model('roomservice/roomservice_shop_model');
            $data = $this->roomservice_shop_model->get_shop_info($shop_id);
            if (!empty($data) && $data['time_range'])
            {
                $return = json_decode($data['time_range'],true);
            }
        }

        if (!empty($return))
        {
            $status = 200;
        }
        ajax_return($status,'暂无设置营业时段',$return);
    }


    /**
     * 导出数据
     * @param array $res
     * @return bool
     * @throws PHPExcel_Reader_Exception
     */
    public function extdata($res = array()){
        if(empty($res)){
            return false;
        }
        $this->load->library ( 'PHPExcel' );
        $this->load->library ( 'PHPExcel/IOFactory' );
        $objPHPExcel = new PHPExcel ();
        $objPHPExcel->getProperties ()->setTitle ( "export" )->setDescription ( "none" );
        $col = 0;
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, 1, '分组id' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, 1, 'inter_id' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, 1, '酒店公众号名称' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, 1, '所属门店' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, 1, '场景名称' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, 1, '添加时间' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, 1, '成交订单数' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, 1, '成交总额' );
        $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, 1, '成交分组占比' );

        // Fetching the table data
        $row = 2;
        foreach ( $res as $k=>$item ) {
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 0, $row, $item['group_id'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 1, $row, $item['inter_id']);
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 2, $row, $item['inter_name'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 3, $row, $item['hotel_name'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 4, $row, $item['type_name'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 5, $row, date('Y-m-d',$item['create_time']) );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 6, $row, $item['order_count'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 7, $row, $item['trade_money'] );
            $objPHPExcel->getActiveSheet ()->setCellValueByColumnAndRow ( 8, $row, $item['rate'] );
            $row ++;
        }
        $objPHPExcel->setActiveSheetIndex ( 0 );
        $objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
        // 发送标题强制用户下载文件
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.xls"' );
        header ( 'Cache-Control: max-age=0' );
        $objWriter->save ( 'php://output' );
    }


    /**
     * 处理日历价格
     * @param 当前年月 $time string 格式： 2017-05
     * @param $dateprice array 日历价格数据
     * @return array|string
     */
    public function month_spu_price($time,$dateprice = array())
    {
        $tmp = strtotime($time);
        $length = date('t',$tmp);

        for ($i = 1;$i<= $length;$i++)
        {
            $time = date("Y-n-{$i}",$tmp);
            $price = !empty($dateprice[$time]['goods_price']) ? $dateprice[$time]['goods_price'] : 0;
            $stock =  !empty($dateprice[$time]['goods_stock']) ? $dateprice[$time]['goods_stock'] : 0;
            $date_id =  !empty($dateprice[$time]['date_id']) ? $dateprice[$time]['date_id'] : 0;
            $per = array(
                'time'  => $time,
                'money' => '¥'.$price,
                'stock' => $stock,
                'psp_sid' => $time,
            );
            $data[$i-1] = $per;
        }

        return $data;
    }


    /**
     * 保存价格日历配置
     */
    public function save_setting()
    {
        $param = $this->input->post();
        if (empty($param['spu_data']))
        {
            ajax_return(0,'请先添加商品规格');
        }

        $id = !empty($param['goods_id']) ? intval($param['goods_id']) : '';
        $cache = $this->_load_cache();
        $redis = $cache->redis->redis_instance();
        $key_redis = 'ticket_goods_'. $id.'_'.$this->inter_id.$this->user_agent();
        $this->write_log($key_redis,'key',json_encode($param));

        $redis->set($key_redis,json_encode($param),7200);

        $year = !empty($param['year']) ? intval($param['year']) : date('Y');
        $month = !empty($param['mouth']) ? intval($param['mouth']) : date('n');

        $price_data = $this->cache_date_price($year,$month,$id);

        //编辑
        if (!empty($id))
        {
            //即时添加
            $this->load->model('roomservice/roomservice_ticket_spu_model');
            $info = array(
                'inter_id' => $this->inter_id,
                'goods_id' => $id,
            );
            $param = $this->roomservice_ticket_spu_model->add_spu_data($param,$info);

            $this->load->model('roomservice/roomservice_ticket_dateprice_model');
            $this->roomservice_ticket_dateprice_model->ticket_dateprice_ajax($param,$this->inter_id,$id);

            $redis->del($key_redis);
            //添加完毕

            $start = date('Y-m-01',strtotime($year.'-'.$month));
            $end_time = date('Y-m-t',strtotime($year.'-'.$month));
            $where_arr = array(
                'goods_id'  => $id,
                'date'      => "date between '{$start}' and '{$end_time}'",
            );

            $price_data_table = $this->roomservice_ticket_dateprice_model->get_goods_dateprice_info($where_arr,2);
            $price_data_table = $this->get_low_price($price_data_table);
            //合并 cache 覆盖数据库的数据
            if (!empty($price_data))
            {
                foreach ($price_data as $key => $value)
                {
                    if (!empty($price_data_table[$key]) && empty($value['goods_price']))
                    {
                        $price_data[$key]['goods_price'] = $price_data_table[$key]['goods_price'];
                        $price_data[$key]['goods_stock'] = $price_data_table[$key]['goods_stock'];
                        unset($price_data_table[$key]);
                    }
                    else if(!empty($price_data_table[$key]) && !empty($value['goods_price']))
                    {
                        unset($price_data_table[$key]);
                    }
                }
            }

            if (!empty($price_data_table) && !empty($price_data))
            {
                $price_data = array_merge($price_data,$price_data_table);
            }
            else if(!empty($price_data_table) && empty($price_data))
            {
                $price_data = $price_data_table;
            }
        }
        else
        {
            //覆盖单天设置的
            $key_redis = 'ticket_goods_one__'.$this->inter_id.$this->user_agent();
            $setting = $redis->get($key_redis);

            if(!empty($param))
            {
                $setting = !empty($setting) ? json_decode($setting,true) : '';
                if (!empty($setting))
                {
                    $start_time = strtotime($param['price_start_time']);//开始时间
                    $end_time = strtotime($param['price_end_time']);//结束时间

                    //循环按照日期生成记录
                    while($start_time <= $end_time)
                    {
                        //判断某天是周几
                        $this_week = date('w', $end_time);
                        $this_day = date('Y-n-j', $end_time);

                        if (!empty($setting[$this_day]))
                        {
                            $this_week = $this_week == 0 ? 7 : $this_week;//周日
                            if (!empty($param['week']) && in_array($this_week, $param['week']))
                            {
                                $setting[$this_day] = $param['spu_data'];
                            }
                        }

                        $end_time = $end_time - 86400;
                    }
                    //重新设置
                    $redis->set($key_redis,json_encode($setting),7200);
                }

                //print_r($res);exit;
            }
        }

        $spu_data = array(
            'data' => date('Y/n',strtotime($year.'-'.$month)),
            'month' => $this->month_spu_price(date('Y-n',strtotime($year.'-'.$month)),$price_data),
            'spu_data' => $param['spu_data'],//暂时没处理
        );
        ajax_return(200,'成功',$spu_data);
    }

    /**
     * 单个设置
     */
    public function save_one_setting()
    {
        $param = $this->input->post();

        if (empty($param['spu_data']))
        {
            ajax_return(0,'请先添加商品规格');
        }

        $id = !empty($param['goods_id']) ? intval($param['goods_id']) : '';
        $cache = $this->_load_cache();
        $redis = $cache->redis->redis_instance();
        $key_redis = 'ticket_goods_one_'. $id.'_'.$this->inter_id.$this->user_agent();
        $this->write_log($key_redis,'key',json_encode($param));
        //先获取
        $old_setting = $redis->get($key_redis);
        $old_setting = !empty($old_setting) ? json_decode($old_setting,true) : array();
        //$redis->del($key_redis);
        if (!empty($param['spu_data']))
        {

            $old_setting[$param['setting_date']] = $param['spu_data'];
            $redis->set($key_redis,json_encode($old_setting),7200);
        }

        //点击商品实时保存数据
        if (!empty($id))
        {
            $this->load->model('roomservice/roomservice_ticket_spu_model');
            $info = array(
                'inter_id' => $this->inter_id,
                'goods_id' => $id,
            );
            $param = $this->roomservice_ticket_spu_model->add_spu_data($param,$info);
            $this->load->model('roomservice/roomservice_ticket_dateprice_model');
            $this->roomservice_ticket_dateprice_model->one_ticket_dateprice_ajax($param,$this->inter_id,$id);

            $redis->del($key_redis);
        }


        //处理spu 最低价格作为页面展示
        $arr_price = array();
        $price = $stock = 0;
        foreach($param['spu_data'] as $k=>$v)
        {
            if (!empty($v['spu_data_price']))
            {
                $arr_price[$k] = trim($v['spu_data_price']);
            }
        }

        if (!empty($arr_price))
        {
            $price = min($arr_price);
        }

        $spu_data = array(
            'price' => '¥'.formatMoney($price),
            'stock' => $stock,//暂时没处理
            'spu_data' => $param['spu_data'],//暂时没处理
        );
        ajax_return(200,'成功',$spu_data);
    }

    /**
     * 获取某天信息
     */
    public function get_date_info()
    {
        $param = $this->input->post();
        $goods_id = !empty($param['goods_id']) ? intval($param['goods_id']) : '';
        $date = !empty($param['date']) ? $param['date'] : '';

        $res = array();
        if (!empty($goods_id) && !empty($date))
        {
            $where = array(
                'goods_id' => $goods_id,
                'date' => date('Y-m-d',strtotime($date)),
            );

            $this->load->model('roomservice/roomservice_ticket_dateprice_model');
            $res = $this->roomservice_ticket_dateprice_model->get_date_info($where);
        }
        else if(!empty($date))
        {
            //获取配置
            $cache = $this->_load_cache();
            $redis = $cache->redis->redis_instance();
            $key_redis = 'ticket_goods_one__'.$this->inter_id.$this->user_agent();
            $setting_one = $redis->get($key_redis);

            $key_redis = 'ticket_goods__'.$this->inter_id.$this->user_agent();
            $setting = $redis->get($key_redis);


            $setting_one = !empty($setting_one) ? json_decode($setting_one,true) :'';

            if (!empty($setting_one) && !empty($setting_one[$date]))
            {
                foreach ($setting_one[$date] as $key => $value)
                {
                    $item = array();
                    $item['date'] = $date;
                    $item['date_id'] = '';
                    $item['spu_id'] = $value['spu_id'];
                    $item['spu_name'] = $value['spu_name'];
                    $item['goods_price'] = $value['spu_data_price'];
                    $item['goods_stock'] = $value['spu_data_stock'];
                    $res[] = $item;
                }
            }
            else if(!empty($setting))
            {
                $setting = json_decode($setting,true);
                $start_time = strtotime($setting['price_start_time']);//开始时间
                $end_time = strtotime($setting['price_end_time']);//结束时间

                //循环按照日期生成记录
                while($start_time <= $end_time)
                {
                    //判断某天是周几
                    $this_week = date('w', $end_time);
                    $this_day = date('Y-n-j', $end_time);

                    if ($this_day == $date)
                    {
                        $this_week = $this_week == 0 ? 7 : $this_week;//周日

                        if (!empty($setting['week']) && in_array($this_week, $setting['week']))
                        {
                            //判断添加或者编辑
                            $spu_data = $setting['spu_data'];
                            foreach ($spu_data as $key => $value)
                            {
                                $item = array();
                                $item['date'] = $date;
                                $item['date_id'] = '';
                                $item['spu_id'] = $value['spu_id'];
                                $item['spu_name'] = $value['spu_name'];
                                $item['goods_price'] = $value['spu_data_price'];
                                $item['goods_stock'] = $value['spu_data_stock'];
                                $res[] = $item;
                            }
                        }
                        break;
                    }

                    $end_time = $end_time - 86400;
                }
                //print_r($res);exit;
            }

        }

        ajax_return(200,'成功',$res);
    }

    private function write_log( $data,$re = '',$result = '',$file=NULL, $path=NULL )
    {
        if(!$file) $file= date('Y-m-d'). '.txt';
        if(!$path) $path= APPPATH. 'logs'. DS. 'roomservice'. DS;

        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }

        if(is_array($data)){
            $data=json_encode($data);
        }
        if(is_array($result)){
            $result=json_encode($result);
        }
        $fp = fopen($path.$file, "a");
        $content = date("Y-m-d H:i:s")." | ".getmypid()." | ".$_SERVER['PHP_SELF']." | ".session_id()." | ".$data." | ".$re." | ".$result."\n";

        fwrite($fp, $content);
        fclose($fp);
    }

    /**
     * 设置用户浏览器唯一标识
     */
    protected function user_agent()
    {
        return md5($_SERVER['HTTP_USER_AGENT']);
    }
}
