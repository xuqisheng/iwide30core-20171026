<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Roomservice extends MY_Front {
    public  $themeConfig;
    public  $theme = 'default';//皮肤
    public $openid;
    public $module;
    //protected $_token;
	function __construct() {
		parent::__construct ();
        $this->inter_id = $this->session->userdata ( 'inter_id' );
        $this->openid = $this->session->userdata ( $this->inter_id . 'openid' );
        //统计探针
        $this->load->library('MYLOG');
        MYLOG::distribute_tracker($this->session->userdata ( $this->inter_id . 'openid' ),   $this->session->userdata ( 'inter_id' ));
	}
	
	public function index(){

		$data['inter_id'] = $this->inter_id;
		$data['hotel_id'] = $this->input->get('hotel_id', TRUE );
		$data['shop_id'] = $this->input->get('shop_id', TRUE );
		$data['type_id'] = $this->input->get('type_id', TRUE );
        //查询店铺信息
        $shop = $this->shop_info($data['inter_id'],$data['hotel_id'],$data['shop_id']);
        if(!$shop){
            echo '店铺不存在';
            die;
        }
        if(!empty($data['inter_id']) && !empty($data['hotel_id']) && !empty($data['shop_id'])){
            $this->session->set_userdata (array (
                'inter_id' => $this->inter_id ,
                'hotel_id' => $data['hotel_id'],
                'shop_id' => $data['shop_id']
            ));
        }
        //获取商品分组  根据inter_id,hotel_id,shop_id
        $group_where = array(
            'inter_id' => $data['inter_id'],
            'hotel_id' => $data['hotel_id'],
            'shop_id'  => $data['shop_id'],
            'status' =>1,
            'is_delete' => 0,
         //   'goods_num >' => 0,
        );
        $this->load->model('roomservice/roomservice_goods_group_model');
        $group = $this->roomservice_goods_group_model->get_goods_group_info($group_where);
        if(empty($group)){
            echo '店铺暂时没有商品';
            die;
        }
        //计算分组的数量 为0不显示
        $group_ids = array_column($group,'group_id');
        $exit_group_ids = $this->roomservice_goods_group_model->get_group_count($group_ids,$this->inter_id,4);
        if(empty($exit_group_ids)){
            echo '店铺暂时没有商品';
            die;
        }
        $tmp_group = array_column($exit_group_ids,'group_id');//获取存在商品的分组
        foreach($group as $k=>$v){
            //$res = $this->db->get_where('roomservice_goods',array('inter_id'=>$data['inter_id'],'group_id'=>$v['group_id'],'is_delete'=>0))->result_array();
            if(!in_array($v['group_id'],$tmp_group)){//不在里面说明没有商品
                unset($group[$k]);
            }
            /*if(empty($res)){//无商品
                unset($group[$k]);
            }*/
        }
        //取出热门推荐商品信息 根据 inter_id shop_id
        $goods_where = array(
            'inter_id' => $data['inter_id'],
            'hotel_id' => $data['hotel_id'],
            'shop_id'  => $data['shop_id'],
            'is_recommend' => 1,
            //'sale_status !=' => 0,
            'is_delete' => 0,
            'sale_now' => 1,
        );
        $is_recommend = 0;
        $this->load->model('roomservice/roomservice_goods_model');
        $goods_info = $this->roomservice_goods_model->get_goods_info($goods_where);
        if(empty($goods_info)){//无热门推荐商品 取第一组商品
           /* unset($goods_where['is_recommend']);
            $goods_where['group_id'] = $group[0]['group_id'];
            $goods_info = $this->db->where($goods_where)->order_by('sort_order','desc')->get('roomservice_goods')->result_array();*/
        }else{
            //加多一个
            $is_recommend = 1;
        }
        $data['is_recommend'] = $is_recommend;

        $data['shop'] = $shop;
        $data['group'] = $group;
        $data['goods'] = $goods_info;
        $this->display('roomservice/index',$data);
	}


    //获取商铺信息
    private function shop_info($inter_id = '',$hotel_id='',$shop_id=''){
        $arr = array();
        $arr['inter_id'] = $inter_id;
        if($hotel_id){
            $arr['hotel_id'] = $hotel_id;
            //读取酒店信息
            $this->load->model ( 'hotel/Hotel_model' );
            $hotel = $this->Hotel_model->get_hotel_detail($inter_id,$hotel_id);
            if(empty($hotel)){
                return false;//酒店不存在或者停用了
            }
        }
        $arr['shop_id'] = $shop_id;
        $arr['status'] = 1;//开业
        $arr['is_delete'] = 0;//正常
        $this->load->model ( 'roomservice/Roomservice_shop_model' );
        $shop = $this->Roomservice_shop_model->get($arr);
        if($shop){//店铺是正常的状态  查看是否在营业时间
            //判断店铺营业时间
            $shop_status = 0;//歇业
            $date = date('w')?date('w'):7;//星期几
            $sale_days = empty($shop['sale_days'])?array():explode(',',$shop['sale_days']);
            if(in_array($date,$sale_days)){
                $now_hour = time();//var_dump($shop['start_time'] <= $now_hour);die;
                //优先判断 time_range 营业时段 不存在则 判断原来的逻辑开店时间
                if (!empty($shop['time_range']))
                {
                    //判断当前时间是否符合营业时段
                    $time_range = json_decode($shop['time_range'],true);
                    foreach ($time_range as $value)
                    {
                        if(strtotime(date("Y-m-d {$value['start_time']}")) <= $now_hour && $now_hour <= strtotime(date("Y-m-d {$value['end_time']}")))
                        {
                            $shop_status = 1;//正常营业
                        }
                    }
                }
                else
                {
                    if(strtotime(date("Y-m-d {$shop['start_time']}")) <= $now_hour && $now_hour <= strtotime(date("Y-m-d {$shop['end_time']}")))
                    {
                        $shop_status = 1;//正常营业
                    }
                }
            }
            //优惠数据处理
            if(!empty($shop['discount_type']) && !empty($shop['discount_config'])){
                $shop['discount_config'] = unserialize($shop['discount_config']);
            }
            $shop['shop_status'] = $shop_status;
            return $shop;
        }else{
            return false;//店铺已经删除 或不存在
        }
    }

    /*
     * ajax获取分组下的商品信息
     * */
    public function get_goods(){
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());
       // $inter_id = $this->input->post('inter_id',true)?$this->input->post('inter_id',true):'';
        $inter_id = $this->inter_id;
        $shop_id = $this->input->post('shop_id',true)?$this->input->post('shop_id',true):'';
        $group_id = $this->input->post('group_id',true)?$this->input->post('group_id',true):'';
        if(empty($inter_id) || empty($shop_id)){
            $return['msg'] = 'empty data!';
            echo json_encode($return);
            die;
        }
        //查询商铺是否营业
        $shop = $this->shop_info($inter_id,'',$shop_id);
        if(empty($shop)){
            $return['msg'] = '店铺不存在';
            echo json_encode($return);
            die;
        }
        //查询分组下的商品信息
        $goods_where = array(
            'inter_id'=>$inter_id,
            'shop_id'=>$shop_id,
            'is_delete'=>0,
            'sale_now'=>1,
        );
        if(!empty($group_id)){
            if(is_array($group_id)){
                $goods_where['in_group_id'] = $group_id;
            }elseif($group_id == 'is_re'){
                $goods_where['is_recommend'] = 1;
            }else{
                $goods_where['group_id'] = $group_id;
            }
        }

        /*if($group_id == 'is_re'){//查找推荐的
            $goods_where['is_recommend'] = 1;
        }else{
            $goods_where['group_id'] = $group_id;
        }*/
        $this->load->model('roomservice/roomservice_goods_model');
        $goods_info = $this->roomservice_goods_model->get_front_goods_list($goods_where);
        //$goods_info = $this->db->where($goods_where)->order_by('sort_order','desc')->get('roomservice_goods')->result_array();
        $goods_ids = $recommend_ids = array();
        $group_arr = array();
        if($goods_info) {
            //查询所有分组的信息
            $group_where = array(
                'inter_id' => $this->inter_id,
               // 'hotel_id' => $data['hotel_id'],
                'shop_id'  => $shop_id,
                'status' =>1,
                'is_delete' => 0,
                //   'goods_num >' => 0,
            );
            $this->load->model('roomservice/roomservice_goods_group_model');
            $group = $this->roomservice_goods_group_model->get_goods_group_info($group_where);
            if($group){
                foreach($group as $k=>$v){
                    $group_arr[$v['group_id']] = $v;
                }
                unset($group);
            }
            //过滤掉没到时间开售的商品
            foreach ($goods_info as $gk => $gv) {
                $goods_info[$gk]['btn_status'] = 1;
                $curtime = date('H:i');
                if($gv['sale_now'] ==3 || ($gv['sale_now'] == 2 && ($gv['sale_start_time'] > "{$curtime}" || $gv['sale_end_time'] < "{$curtime}" ) ) ||$gv['sale_status'] ==2){//定时开售

                    $goods_info[$gk]['btn_status'] = 0;
                }
                $goods_ids[] = $gv['goods_id'];
                if($gv['is_recommend'] == 1){//记录热门推荐id
                    $recommend_ids[] = $gv['goods_id'];
                }
                //图片数据处理 取第一张
               $goods_info[$gk]['goods_img'] = !empty($gv['goods_img'])?json_decode($gv['goods_img'],true)[0]:'';
            }
        }
        if(!empty($goods_ids)) {//处理规格信息
            $this->load->model('roomservice/roomservice_spec_setting_model');
            $spec_arr = $this->roomservice_spec_setting_model->get_goods_sepc_info(array('inter_id' => $this->inter_id), $goods_ids);
            if (!empty($spec_arr)) {//返回的格式是 array(goods_id=>array())
                foreach ($goods_info as $k => $v) {
                    if (isset($spec_arr[$v['goods_id']]) && !empty($spec_arr[$v['goods_id']]) && !empty($v['spec_list'])) {
                        $array = array();//规格数组
                        foreach ($spec_arr[$v['goods_id']] as $sk => $sv) {
                            $_v = json_decode($sv['setting_spec_compose'], true);
                            foreach ($_v as $kk => $vv) {
                                $vv['admin_setting_id'] = $sv['setting_id'];//规格的唯一id
                                $vv['stock'] = $sv['spec_stock'];
                                $array[$kk] = $vv;
                            }
                        }
                        $spec_list = json_decode($v['spec_list'], true);
                        $spec_list['data'] = $array;
                        $goods_info[$k]['spec_list'] = json_encode($spec_list, JSON_UNESCAPED_UNICODE);
                    }

                }
            }
            //按照分组组装数据
            $recommend_goods = array();
            foreach($goods_info as $k=>$v){
                /*if(in_array($v['goods_id'],$recommend_ids)){
                    $recommend_goods[] = $v;
                }*/
                if(isset($group_arr[$v['group_id']]) && !empty($group_arr[$v['group_id']])){
                    $group_arr[$v['group_id']]['goods_info'][] = $v;
                }
            }
            $sort_arr = array();
            foreach($group_arr as $k=>$v){
                if(isset($v['goods_info']) && !empty($v['goods_info'])){
                    $sort_arr[] = $v; //去掉索引
                }else{//删掉没数据的分组
                    unset($group_arr[$k]);
                }
            }
            unset($group_arr);
            /*if(count($recommend_goods) > 0){
                $group_arr['is_re']['group_name'] = '热门推荐';
                $group_arr['is_re']['goods_info'] = $recommend_goods;
            }*/
        }
//var_dump($group_arr);die;
        if($goods_info){
            if(!empty($group_id) && $group_id=='is_re'){
                $return['data'] = $goods_info;
            }else{
                $return['data'] = $sort_arr;
            }
            $return['errcode'] = 0;
            $return['msg'] = '成功';
            //$return['data'] = array('group_goods_info'=>$group_arr);
            //$return['data_new'] = $group_arr;
        }else{
            $return['msg'] = '无商品';
        }

        /*}else{
            $return['msg'] = '分组下无商品';
        }*/
        echo json_encode($return);
        die;
    }

    //订单确认页
    public function checkout(){
        $data = array();
        //检查店铺信息
        $data['inter_id'] = $this->inter_id;
        $openid = $this->openid;
        $data['hotel_id'] = $this->input->get('hotel_id', TRUE );
        $data['shop_id'] = $this->input->get('shop_id', TRUE );
        $data['type_id'] = $this->input->get('type_id', TRUE );
        //查询店铺信息
        $shop = $this->shop_info($data['inter_id'],$data['hotel_id'],$data['shop_id']);
        if(!$shop){
            echo '店铺不存在';
            die;
        }
        if(!$shop['shop_status']){//店铺歇业
            echo '店铺正在休息中，不能下单！';
            die;
        }
        //计算预计送达时间
        $shop['delivery_time'] = date('H:i',time()+$shop['wait_time']*60);
        //储值的金额
        $data ['membermoney'] = 0;
        $data['all_pay_type'] = array('1'=>'微信支付','2'=>'储值支付','3'=>'线下支付','4'=>'微信支付');//这个顺序不能变  4=>威富通支付 名字改为微信支付
        if(!empty($shop['pay_type'])){
            $pay_type = explode(',',$shop['pay_type']);
            $shop['pay_type'] = $pay_type;
            if(in_array('2',$pay_type)){//支持储值支付 查询该用户的储值
                $this->load->library ( 'PMS_Adapter', array (
                    'inter_id' => $this->inter_id,
                    'hotel_id' => $data['hotel_id']
                ), 'pub_pmsa' );
                //获取用户信息
                $member = $this->pub_pmsa->check_openid_member ( $this->inter_id, $openid, array (
                    'create' => TRUE,
                    'update' => TRUE
                ) );
                $data ['membermoney'] = $member->balance;
            }
        }

        //核对信息
        if(!empty($shop['verify_info'])){
            $shop['verify_info'] = explode(',',$shop['verify_info']);
        }else{
            $shop['verify_info'] = array();
        }

        $data['shop'] = $shop;
        //获取卡券列表

        //获取配送费
        $data['shipping_cost'] = 0;
        if ($shop['sale_type'] == 3)// && $shop['shipping_type'] == 2 备注：外卖通用设置配送费
        {
            $data['shipping_cost'] = $shop['shipping_cost'];//获取配送费
        }

        //蜂鸟配送
        $this_time = date('H'); //22:00 - 02:00
        if ($shop['shipping_type'] == 3 && (22 <= $this_time || $this_time <= 2))
        {
            $data['shipping_cost'] = $data['shipping_cost'] + 3.5;
        }

        //读取酒店信息
        $this->load->model ( 'hotel/Hotel_model' );
        $hotel = $this->Hotel_model->get_hotel_detail($data['inter_id'],$data['hotel_id']);
        $data['hotel'] = $hotel;

        //判断外卖 堂食 房间
        $data['user_address'] = '';
        if($shop['sale_type'] == 3){//外卖
            //读取已有的地址
            $address = $this->db->where(
                                    array('inter_id'=>$this->inter_id,
                                          'hotel_id'=>$data['hotel_id'],
                                          'openid'=>$openid,
                                          'status'=>1))
                                 ->order_by('update_time','desc')
                               // ->limit(1)
                                ->get('iwide_roomservice_address')
                                ->result_array();
            $data['user_address'] = $address;
        }
        //获取用户核对信息
        if ($shop['sale_type'] == 1)
        {
            $this->load->model('roomservice/roomservice_verify_model');
            $verify_where = array(
                'shop_id' => $data['shop_id'],
                'openid' => $openid,
            );
            $data['verify'] = $this->roomservice_verify_model->get_one($verify_where);
        }


        $this->display('roomservice/checkout',$data);
    }
    //获取优惠信息：优惠，券，积分
    public function get_all_discount(){
        $return = array('errcode'=>0,'msg'=>'成功','data'=>array());
        $data['inter_id'] = $this->inter_id;
        $data['hotel_id'] = $this->input->post('hotel_id', TRUE );
        $data['shop_id'] = $this->input->post('shop_id', TRUE );
        $goods_info = $this->input->post('goods_info', TRUE );
        //查询店铺信息
        $shop = $this->shop_info($data['inter_id'],$data['hotel_id'],$data['shop_id']);
        if(!$shop){
            $return['errcode'] = 1;
            $return['msg'] = '店铺不存在';
            echo json_encode($return);
            die;
        }
        if(!$shop['shop_status']){//店铺歇业
            $return['errcode'] = 1;
            $return['msg'] = '店铺正在休息中，不能下单！';
            echo json_encode($return);
            die;
        }
        //处理优惠信息
        $info = array();
        if(!empty($shop['discount_type']) && !empty($shop['discount_config'])){
            if($shop['discount_start_time'] <= date('Y-m-d H:i:s') && $shop['discount_end_time']> date('Y-m-d H:i:s')){
                if($shop['discount_type'] == 1){//单满减
                    $info['discount_type'] = 1;
                    $info['discount_name'] = '单满减';
                    $info['discount_config'] = array('sum'=>$shop['discount_config'][0],'cut'=>$shop['discount_config'][1]);
                }elseif($shop['discount_type'] == 2){//每满减
                    $info['discount_type'] = 2;
                    $info['discount_name'] = '每满减';
                    $info['discount_config'] = array('sum'=>$shop['discount_config'][0],'cut'=>$shop['discount_config'][1]);
                }elseif($shop['discount_type'] == 3){//折扣
                    $info['discount_type'] = 3;
                    $info['discount_name'] = '折扣';
                    $info['discount_config'] = array('discount'=>$shop['discount_config'][0]);
                }
                $return['msg'] = '成功';
                $return['data'] = $info;
            }else{
                $return['errcode'] = 1;
                $return['msg'] = '不在优惠时间内';
            }
        }else{
            $return['errcode'] = 1;
            $return['msg'] = '没有优惠';
        }

        //计算优惠金额
        if (!empty($goods_info))
        {
            //查询商品表
            $this->load->model('roomservice/roomservice_goods_model');
            $this->load->model('roomservice/roomservice_orders_model');
            $goodsids = array();
            foreach($goods_info as $k=>$v){
                $goodsids[] = $v['goods_id'];
            }
            $goods_res = $this->roomservice_goods_model->get_order_goods_info($data,$goods_info,$goodsids);


            if(empty($goods_res))
            {
                $return['msg'] = '产品库存不足或已暂停售卖';
                echo json_encode($return);
                die;
            }

            $ticket_discount_fee = !empty($data['ticket_discount_fee']) ? $data['ticket_discount_fee'] : 0;
            $total= $this->roomservice_orders_model->calculate_total($goods_res,$shop,$ticket_discount_fee);
        }

        $return['price'] = !empty($total) ? $total['en_count_total'] : 0;
        echo json_encode($return);
        die;

    }

    //收货地址列表
    public function address_list(){
        $openid = $this->openid;
        $data = array();
        $data['inter_id'] = $this->inter_id;
        $data['hotel_id'] = $this->input->get('hotel_id', TRUE );
        $data['shop_id'] = $this->input->get('shop_id', TRUE );
        $address = $this->db->where(
                        array('inter_id'=>$this->inter_id,
                            'hotel_id'=>$data['hotel_id'],
                            'shop_id' =>$data['shop_id'],
                            'openid'=>$openid,
                            'status'=>1))
                        ->order_by('address_id','desc')
                        ->get('iwide_roomservice_address')
                        ->result_array();
        $data['user_address'] = $address;
        $this->display('roomservice/address_list',$data);
    }
    //新增 编辑收货地址页
    public function address(){
        $openid = $this->openid;
        $data = array();
        $data['inter_id'] = $this->inter_id;
        $data['hotel_id'] = intval($this->input->get('hotel_id', TRUE ));
        $data['shop_id'] = intval($this->input->get('shop_id', TRUE ));
        $id = $this->input->get('addr_id', TRUE );
        $id = intval($id);
        $data['addr_id'] = $id;
        $data['user_address'] = '';
        if($id){//编辑
            $address = $this->db->where(
                array('inter_id'=>$this->inter_id,
                    'hotel_id'=>$data['hotel_id'],
                    'shop_id' =>$data['shop_id'],
                    'openid'=>$openid,
                    'address_id' => $id,
                    'status'=>1))
                ->get('iwide_roomservice_address')
                ->row_array();
            $data['user_address'] = $address;
            if(empty($data['user_address'])){
                echo '联系信息有误';
                die;
            }
        }
        $this->display('roomservice/address',$data);
    }

    //地图页
    public function map(){
        $openid = $this->openid;
        $data = array();
        $data['inter_id'] = $this->inter_id;
        $data['hotel_id'] = intval($this->input->get('hotel_id', TRUE ));
        $data['shop_id'] = intval($this->input->get('shop_id', TRUE ));
        $id = intval($this->input->get('addr_id', TRUE ));
        $data['addr_id'] = empty($id)?'':$id;
        $this->display('roomservice/map',$data);

    }

    //保存 更新地址信息
    public function saveAddress(){
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());
        $inter_id = $this->inter_id;
        $shop_id = $this->input->post('shop_id',true)?intval($this->input->post('shop_id',true)):'';
        $hotel_id = $this->input->post('hotel_id',true)?intval($this->input->post('hotel_id',true)):'';
        $addr_id = $this->input->post('addr_id',true)?intval($this->input->post('addr_id',true)):'';
        $post =  $this->input->post ();
        $arr = array();
        if(empty($post['contact']) || empty($post['phone']) || empty($post['select_addr']) || empty($post['address'])/* || empty($post['longitude']) || empty($post['latitude'])*/){
            $return['msg'] = '不能有空数据';
            echo json_encode($return);
            die;
        }
        //获取店铺设置的外卖距离
        $shop = $this->shop_info($inter_id,$hotel_id,$shop_id);
        if(empty($shop)){
            $return['msg'] = 'shop data error!';
            echo json_encode($return);
            die;
        }
        //查询酒店
        $this->load->model ( 'hotel/Hotel_model' );
        $hotel = $this->Hotel_model->get_hotel_detail($this->inter_id,$hotel_id);
        if(empty($hotel)){
            $return['msg'] = 'hotel data error!';
            echo json_encode($return);
            die;
        }
        $this->load->helper ( 'calculate' );
        $distance = get_distance($hotel['longitude'],$hotel['latitude'],$post['longitude'],$post['latitude']);
        if($distance > $shop['sale_around'] && $inter_id !='a469543253'&& $inter_id !='a469428180'){
            $return['msg'] = '距离超出配送范围，请重新选择！';
            echo json_encode($return);
            die;
        }

        $arr['contact'] = addslashes($post['contact']);
        $arr['phone'] = addslashes($post['phone']);
        $arr['select_addr'] = addslashes($post['select_addr']);
        $arr['address'] = addslashes($post['address']);
        $arr['longitude'] = $post['longitude'];//纬度
        $arr['latitude'] = $post['latitude'];//经度
        $arr['status'] = 1;
        $url = site_url('roomservice/roomservice/checkout?id='.$inter_id.'&hotel_id='.$hotel_id.'&shop_id='.$shop_id);
        if($addr_id){//更新
            $res = $this->db->update('roomservice_address',$arr,array('openid'=>$this->openid,'inter_id'=>$inter_id,'hotel_id'=>$hotel_id,'shop_id'=>$shop_id,'address_id'=>$addr_id));
            if($res){
                $return['errcode'] = 0;
                $return['msg'] = '更新成功';
                $return['data']['addr_id'] = $addr_id;
                $return['data']['url'] = $url;
            }else{
                $return['msg'] = '更新失败';
            }
        }else{//新增
            $arr['openid'] = $this->openid;
            $arr['inter_id'] = $inter_id;
            $arr['hotel_id'] = $hotel_id;
            $arr['shop_id'] = $shop_id;
            $arr['add_time'] = date('Y-m-d H:i:s');
            $res = $this->db->insert('roomservice_address',$arr);
            if($res){
                $return['errcode'] = 0;
                $return['msg'] = '新增成功';
                $return['data']['addr_id'] = $this->db->insert_id();
                $return['data']['url'] = $url;
            }else{
                $return['msg'] = '新增失败';
            }
        }
        echo json_encode($return);
        die;
    }

    //保存订单
    public function saveOrder(){

        $this->load->helper('appointment');
        if(!is_ajax_request()) return;
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());
        $inter_id = $this->inter_id;
        $openid = $this->openid;
        if(empty($openid)){
            $return['msg'] = 'openid error';
            echo json_encode($return);
            die;
        }
        $post = $this->input->post();
        $order = array();
        $order['inter_id'] = $inter_id;
        $order['hotel_id'] = intval($post['hotel_id']);
        $order['shop_id'] = intval($post['shop_id']);
        $goods_info = $post['goods_info'];//var_dump($goods_info);die;
        //模拟
       // $goods_info = array(0=>array('goods_id'=>1,'num'=>2,'spec_id'=>'1'),1=>array('goods_id'=>12,'num'=>1,'spec_id'=>'234'));
        //查询店铺信息
        $shop_info = $this->shop_info($inter_id,$order['hotel_id'],$order['shop_id']);
        if(empty($shop_info['shop_status'])){//歇业
            $return['msg'] = '该店铺已歇业，不能下单';
            echo json_encode($return);
            die;
        }
        $type_id = isset($post['type_id'])?addslashes($post['type_id']):'';
        $addr_id = isset($post['address_id'])?intval($post['address_id']):'';
        if($shop_info['sale_type'] == 3){//外卖
            if(empty($addr_id)){
                $return['msg'] = '联系信息有误';
                echo json_encode($return);
                die;
            }
        }else{
            if(empty($type_id) && $shop_info['identify_type'] == 1){
                $return['msg'] = '场景信息有误';
                echo json_encode($return);
                die;
            }
            else if($shop_info['sale_type'] == 1 && $shop_info['identify_type'] == 2 && empty($post['room_name']))
            {
                $return['msg'] = '请填写房间号信息';
                echo json_encode($return);
                die;
            }
        }
        //判断 客房 核对人信息
        if ($shop_info['sale_type'] == 1 && !empty($shop_info['verify_info']))
        {
            $shop_info['verify_info'] = explode(',',$shop_info['verify_info']);

            if (in_array(1,$shop_info['verify_info']) && empty($post['verify_name']))
            {
                $return['msg'] = '请输入您的姓名';
                echo json_encode($return);
                die;
            }

            if (in_array(2,$shop_info['verify_info']) && empty($post['verify_phone']))
            {
                $return['msg'] = '请输入您的手机号';
                echo json_encode($return);
                die;
            }
        }


        //$order['addr_id'] = $addr_id;
       // $order['type_id'] = $type_id;
        $order = array_merge($post,$order);//合并数据
        $order['type'] = $shop_info['sale_type'];//房间 or堂食 外卖
        if(empty($post['pay_type'])){
            $return['msg'] = '支付信息有误';
            echo json_encode($return);
            die;
        }
        $pay_type = explode(',',$shop_info['pay_type']);
        if(!in_array($post['pay_type'],$pay_type)){
            $return['msg'] = '选择支付方式有误';
            echo json_encode($return);
            die;
        }
        $order['pay_type'] = intval($post['pay_type']);
        $goodsids = array();
        foreach($goods_info as $k=>$v){
            $goodsids[] = $v['goods_id'];
        }

        /**
            2017-3-30增加达达配送费 请求接口数据author:沙沙
         **/
        $order['shipping_cost'] = 0;
        $order['shipping_type'] = 0;
        if ($order['type'] == 3) //  && $shop_info['shipping_type'] == 2 备注：外卖通用设置配送费
        {
            $order['shipping_cost'] = $shop_info['shipping_cost'];//获取配送费
            $order['shipping_type'] = $shop_info['shipping_type'];
        }

        //增加服务费
        $order['cover_charge'] = 0;
        if ($shop_info['cover_charge'] > 0)
        {
            $order['cover_charge'] = $shop_info['cover_charge']/100;//比率
        }

        //unset($goods_info);
        //查询商品表
        $this->load->model('roomservice/roomservice_goods_model');

        $goods_res = $this->roomservice_goods_model->get_order_goods_info($order,$goods_info,$goodsids);

        if(empty($goods_res)){
            $return['msg'] = '产品库存不足或已暂停售卖';
           echo json_encode($return);
            die;
        }//var_dump($goods_res);die;

        $order['goods'] = $goods_res;
        $order['openid'] = $this->openid;

        //外卖配送方式

        //插入订单表
        $this->load->model('roomservice/roomservice_orders_model');
        $result = $this->roomservice_orders_model->checkout($order,$shop_info);
        if($result['errcode'] == 1)
        {
            echo json_encode($result);
            die;
        }

        //添加客房核对信息
        if ($shop_info['sale_type'] == 1 && !empty($shop_info['verify_info']))
        {
            $this->load->model('roomservice/roomservice_verify_model');
            if (!empty($order['verify_id']))
            {
                if (!empty($order['verify_name']))
                {
                    $verify_data['verify_name'] = $order['verify_name'];
                }
                if (!empty($order['verify_phone']))
                {
                    $verify_data['verify_phone'] = $order['verify_phone'];
                }
                $verify_data['add_time'] = date('Y-m-d H:i:s');
                $this->roomservice_verify_model->update_data($verify_data,array('verify_id'=>$order['verify_id']));
            }
            else
            {
                //插入数据
                $verify_data = array(
                    'shop_id' => $order['shop_id'],
                    'inter_id' => $order['inter_id'],
                    'hotel_id' => $order['hotel_id'],
                    'openid' => $openid,
                    'verify_name' => !empty($order['verify_name']) ? $order['verify_name'] : '',
                    'verify_phone' => !empty($order['verify_phone']) ? $order['verify_phone'] : '',
                    'add_time' => date('Y-m-d H:i:s'),
                );
                $this->roomservice_verify_model->insert_data($verify_data);
            }
        }

        //其他处理
        //插入数据 记录action
        $this->db->insert('roomservice_action',array('inter_id'=>$inter_id,'order_id'=>$result['data']['order_id'],'type'=>2,'content'=>'订单提交成功','add_time'=>date('Y-m-d H:i:s'),'order_status'=>0));
        if($result['data']['pay_type'] == 1){//微信支付
            $result['data']['pay_url'] = site_url('wxpay/roomservice_pay') . '?id=' .$inter_id .'&order_id=' .$result['data']['order_id'];//默认使用微信原生配置;
        }elseif($result['data']['pay_type'] == 2){//储值支付
            $res = $this->roomservice_orders_model->pay_order_in_banlance($inter_id,$result['data']['order_id']);
            if($res){
                $result['msg'] = '支付成功';
                $result['data']['pay_url'] = site_url('roomservice/roomservice/order_detail?id='.$inter_id.'&hotel_id='.$order['hotel_id'].'&oid='.$result['data']['order_id']);
            }else{
                $result['errcode'] = 1;
                $result['msg'] = '支付失败';
            }
        }elseif($result['data']['pay_type'] == 3){//线下支付
            //发送模板消息
            $this->roomservice_orders_model->handle_order($inter_id,$result['data']['order_id'],$this->openid,1);
            //打印
            //查一次订单信息吧
            $order = $this->roomservice_orders_model->get_one(array('inter_id'=>$inter_id,'order_id'=>$result['data']['order_id']));

            //打印机
            $order['order_detail'] = $this->db->get_where('roomservice_orders_item',array('order_id'=>$order['order_id']))->result_array();
            $this->load->model ( 'plugins/Print_model' );
            //$this->Print_model->print_roomservice_order ( $order, 'new_order' );
            $this->Print_model->print_roomservice_order ( $order, 'ensure_order' );

            $result['data']['pay_url'] = site_url('roomservice/roomservice/order_detail?id='.$inter_id.'&hotel_id='.$order['hotel_id'].'&oid='.$result['data']['order_id']);
        }elseif($result['data']['pay_type'] == 4){//威富通支付
            $result['data']['pay_url'] = site_url('wftpay/roomservice_pay') . '?id=' .$inter_id .'&order_id=' .$result['data']['order_id'];//默认使用威富通配置;
        }
        echo json_encode($result);
        die;
        //$order_info = $this->db->where(array('inter_id'=>$inter_id,'shop_id'=>$order['shop_id'],'openid'=>$order['openid'],'order_id'=>$result['data']['order_id']));

        // $arr['pay_type'] =
    }

    function order_detail(){
        $openid		= $this->session->userdata($this->inter_id."openid");
        $inter_id	= $this->inter_id;
        $order_id   = addslashes($this->input->get('oid'));
        if(empty($openid)||empty($inter_id)||empty($order_id)){
            echo 'empty data';
            die;
        }

        $this->load->model('roomservice/roomservice_orders_model');
        $data['order'] = $this->roomservice_orders_model->get_order_simple(array('inter_id'=>$inter_id,'openid'=>$openid,'order_id'=>$order_id));
        if(!empty($data['order'])){
            //读取店铺信息
            $data['shop'] = $this->shop_info($inter_id,$data['order']['hotel_id'],$data['order']['shop_id']);
            if(empty($data['shop'])){
                echo '店铺已经关闭';
                die;
            }
            //处理该订单的支付链接
            $data['order']['pay_url'] = '';
            if($data['order']['pay_way'] == 1){//wx
                $data['order']['pay_url'] = site_url('wxpay/roomservice_pay') . '?id=' .$inter_id .'&order_id=' .$data['order']['order_id'];//默认使用微信原生配置;
            }elseif($data['order']['pay_way'] == 4){//威富通
                $data['order']['pay_url'] = site_url('wftpay/roomservice_pay') . '?id=' .$inter_id .'&order_id=' .$data['order']['order_id'];//默认使用微信原生配置;
            }

            //读取酒店信息
            $this->load->model ( 'hotel/Hotel_model' );
            $data['hotel'] = $this->Hotel_model->get_hotel_detail($inter_id,$data['order']['hotel_id']);

            //计算预计送达时间
            $data['delivery_time'] = date('H:i',time()+$data['shop']['wait_time']*60);
            //订单商品
            $data['order_goods'] = $this->roomservice_orders_model->get_order_item_detail(array('inter_id'=>$inter_id,'openid'=>$openid,'order_id'=>$order_id));
            $data['orderModel'] = $this->roomservice_orders_model;
            $this->display('roomservice/order_detail',$data);
        }else{
            echo 'data error';
            die;
        }
    }
    //订单记录跟踪
    public function orderfollow(){
        $openid		= $this->session->userdata($this->inter_id."openid");
        $inter_id	= $this->inter_id;
        $order_id   = addslashes($this->input->get('oid'));
        if(empty($openid)||empty($inter_id)||empty($order_id)){
            echo 'empty data';
            die;
        }
        $this->load->model('roomservice/roomservice_orders_model');
        $order = $this->roomservice_orders_model->get_order_simple(array('inter_id'=>$inter_id,'openid'=>$openid,'order_id'=>$order_id));
        if($order){
            $data['order'] = $order;
            $action = $this->db->where(array('inter_id'=>$inter_id,'order_id'=>$order_id,'type'=>2))->order_by('id','desc')->get('roomservice_action')->result_array();
            //$action = $this->db->get_where('roomservice_action',array('inter_id'=>$inter_id,'order_id'=>$order_id,'type'=>2))->result_array();
            $data['action'] = $action;
            $this->display('roomservice/orderfollow',$data);
        }else{
            echo 'error data';
            die;
        }

    }

    //催单
    public function reminder(){
        if(!is_ajax_request()) return;
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());

        $openid		= $this->session->userdata($this->inter_id."openid");
        $inter_id	= $this->inter_id;
        $order_id   = addslashes($this->input->post('oid'));
        if(empty($openid)||empty($inter_id)||empty($order_id)){
            $return['msg'] = 'empty data';
            echo json_encode($return);
            die;
        }
        $this->load->model('roomservice/roomservice_orders_model');
        $order = $this->roomservice_orders_model->get_order_simple(array('inter_id'=>$inter_id,'openid'=>$openid,'order_id'=>$order_id));
        if(!empty($order)) {
            //读取店铺信息
            $data['shop'] = $this->shop_info($inter_id, $order['hotel_id'], $order['shop_id']);
            if (empty($data['shop'])) {
                $return['msg'] = '店铺已经关闭';
                echo json_encode($return);
                die;
            }
            $orderModel = $this->roomservice_orders_model;
            if($order['order_status']==$orderModel::OS_PER_CANCEL ||$order['order_status']==$orderModel::OS_HOL_CANCEL||$order['order_status']==$orderModel::OS_SYS_CANCEL || $order['order_status'] == $orderModel::OS_FINISH){//取消或者完成
                $return['msg'] = '订单已经完成或者取消';
                echo json_encode($return);
                die;
            }
            if($order['order_status'] == $orderModel::OS_UNCONFIRMED){
                $return['msg'] = '订单还没确认';
                echo json_encode($return);
                die;
            }
            //查看订单是否下单超过5分钟
            if(strtotime($order['add_time']) > (time() - 180)){
                $return['msg'] = '刚刚下单，请稍等一会';
                echo json_encode($return);
                die;
            }
            //查询action表是否有催单记录
            $time = time() - 180;
            $this->db->where(array(
                'inter_id'=>$inter_id,
                'openid' =>$openid,
                'order_id' => $order_id,
                'type' => 1,//催单
                'add_time >' => date('Y-m-d H:i:s',$time),
            ));
            $this->db->limit(1);
            $res =  $this->db->get('roomservice_action')->row_array();
            if($res){
                $return['msg'] = '下单后每3分钟仅可催单1次哦~';
                echo json_encode($return);
                die;
            }else{
                $array = array(
                    'inter_id'=>$inter_id,
                    'openid' =>$openid,
                    'order_id' => $order_id,
                    'hotel_id' => $order['hotel_id'],
                    'shop_id' =>$order['shop_id'],
                    'type' => 1,//催单
                    'order_status'=>$order['order_status'],
                    'add_time' => date('Y-m-d H:i:s')
                );
                $this->db->insert('roomservice_action',$array);
                //发送模板消息
                $res = $orderModel->handle_order($inter_id,$order_id,'',2);

                //用户催单触发打印
                $order['order_detail'] = $this->db->get_where('roomservice_orders_item',array('order_id'=>$order['order_id']))->result_array();
                $this->load->model ( 'plugins/Print_model' );
                $this->Print_model->print_roomservice_order ( $order, 'remind_order' );
                $return['errcode'] = 0;
                $return['msg'] = '成功催单';
                echo json_encode($return);
                die;
            }
        }else{
            $return['msg'] = 'data error';
            echo json_encode($return);
            die;
        }
    }

    //取消订单
    public function cancelOrder(){
        if(!is_ajax_request()) return;
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());

        $openid		= $this->session->userdata($this->inter_id."openid");
        $inter_id	= $this->inter_id;
        $order_id   = addslashes($this->input->post('oid'));
        if(empty($openid)||empty($inter_id)||empty($order_id)){
            $return['msg'] = 'empty data';
            echo json_encode($return);
            die;
        }
        $this->load->model('roomservice/roomservice_orders_model');
        $order = $this->roomservice_orders_model->get_order_simple(array('inter_id'=>$inter_id,'openid'=>$openid,'order_id'=>$order_id));
        if(!empty($order)) {
            //读取店铺信息
            $data['shop'] = $this->shop_info($inter_id, $order['hotel_id'], $order['shop_id']);
            if (empty($data['shop'])) {
                $return['msg'] = '店铺已经关闭';
                echo json_encode($return);
                die;
            }
            $orderModel = $this->roomservice_orders_model;
            if($order['order_status']==$orderModel::OS_PER_CANCEL ||$order['order_status']==$orderModel::OS_HOL_CANCEL||$order['order_status']==$orderModel::OS_SYS_CANCEL || $order['order_status'] == $orderModel::OS_FINISH){//取消或者完成
                $return['msg'] = '订单已经完成或者取消';
                echo json_encode($return);
                die;
            }
            if($order['order_status'] == $orderModel::OS_CONFIRMED){
                $return['msg'] = '订单已经确认，请电话联系取消';
                echo json_encode($return);
                die;
            }
            //记录订单操作日志
            $order_log = array(
                'inter_id'=>$this->inter_id,
                'order_id' => $order_id,
                'hotel_id' => $order['hotel_id'],
                'shop_id'  => $order['shop_id'],
                'operation'=> '',
                'order_status'=>$orderModel::OS_PER_CANCEL,
                'add_time'=> date('Y-m-d H:i:s'),
                'action_note'=>'前台用户主动取消',
                'types' =>1,//
            );
            //查询付款状态
            if($order['pay_status'] == $orderModel::IS_PAYMENT_NOT){//未支付 无须退款
                //取消
                $res = $this->roomservice_orders_model->cancel_order($order);
                if($res){
                    //发送模板消息
                    $orderModel->handle_order($inter_id,$order_id,'',25);
                    $array = array(
                        'inter_id'=>$this->inter_id,
                        'openid' =>'',
                        'order_id' => $order_id,
                        'type' => 2,//跟踪
                        'content'=>'订单已取消',
                        'order_status'=>$orderModel::OS_PER_CANCEL,
                        'add_time' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('roomservice_action',$array);
                    $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录
                    $return['errcode'] = 0;
                    $return['msg'] = '订单取消成功';
                    echo json_encode($return);
                    die;//发送模板消息
                }else{
                    $return['msg'] = '取消失败';
                    echo json_encode($return);
                    die;
                }
            }elseif($order['pay_status'] == $orderModel::IS_PAYMENT_YES){//已支付 先退款
                //判断金额
                /*
                if($order['sub_total'] <= 0){
                    $return['msg'] = '订单金额为0，不支持退款';
                    echo json_encode($return);
                    die;
                }
                */
                //判断金额
                if($order['pay_money'] <= 0)
                {
                    /*
                    $return['msg'] = '订单金额为0，不支持退款';
                    echo json_encode($return);
                    die;
                    */
                    //取消
                    $res = $this->roomservice_orders_model->cancel_order($order);
                    if($res)
                    {
                        //发送模板消息
                        $orderModel->handle_order($inter_id,$order_id,'',25);
                        $array = array(
                            'inter_id'=>$this->inter_id,
                            'openid' =>'',
                            'order_id' => $order_id,
                            'type' => 2,//跟踪
                            'content'=>'订单已取消',
                            'order_status'=>$orderModel::OS_PER_CANCEL,
                            'add_time' => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('roomservice_action',$array);
                        $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录
                        $return['errcode'] = 0;
                        $return['msg'] = '订单取消成功';
                        echo json_encode($return);
                        die;//发送模板消息
                    }
                    else
                    {
                        $return['msg'] = '取消失败';
                        echo json_encode($return);
                        die;
                    }
                }

                //组装退款数据
                $reund_sn = 'TK'.$order['type'].time().rand(1000,9999);
                $refund = array(
                    'inter_id'  =>  $inter_id,
                    'hotel_id'  =>  $order['hotel_id'],
                    'shop_id'   =>  $order['shop_id'],
                    'openid'    =>  $openid,
                    'order_sn'  =>  $order['order_sn'],
                    'trade_no'  =>  $order['trade_no'],
                    'refund_sn' =>  $reund_sn,
                    'refund_way'=>  $order['pay_way'],
                    'refund_status' => 0,//申请退款
                    'refund_money'  => $order['pay_money'],
                );
                //先生成一条退款记录
                $this->db->insert('roomservice_refund',$refund);
                $id = $this->db->insert_id();
                if($id){
                    //判断是哪种支付方式 调用相应的退款
                    if(1 == $order['pay_way']){//微信支付
                        $refund_fee = intval($order['pay_money'] * 100);
                        $total_fee = intval($order['sub_total'] * 100);
                        $this->load->model('roomservice/Roomservice_wxpay_model');
                        $this->load->model('iwidepay/iwidepay_model');
                        //分账退款
                        $iwidepay_refund = array(
                            'orderDate' => date('Ymd'),
                            'orderNo' => $reund_sn,
                            'requestNo' => md5(time()),
                            'transAmt' => $refund_fee,//单位：分
                            'returnUrl'=>'http://cmbcpay.jinfangka.com/index.php',
                            'refundReson' => '退款',
                        );
                        $refund_result = $this->iwidepay_model->refund($iwidepay_refund,$order['order_sn']);
                        if(isset($refund_result['status']) && isset($refund_result['message']) &&$refund_result['status']==2 && $refund_result['message']=='empty'){//不是分账
                            $refund_result = $this->Roomservice_wxpay_model->refund($order['inter_id'],$order['trade_no'],$total_fee,$refund_fee,$reund_sn);
                        }
                        //分账退款
                        /*if(is_array($refund_result) && "WAITING_FAIL" == $refund_result['return_code'] ) {
                            //分账这里 只处理失败状态包含中间状态 按照正常状态返回
                            //更新订单表扣减订单实付金额、记录退款金额
                            $set_item = array(
                                'refund_money'  => 'refund_money + '.$order['pay_money'],
                                'pay_money'     => 'pay_money - '.$order['pay_money'],
                            );

                            $where_item = array(
                                'order_id' => $order['order_id'],
                            );
                            $res_money = $this->roomservice_orders_model->update_data($set_item,$where_item);//更新表
                            //更新退款表数据
                            $this->db->update('roomservice_refund',array('id'=>$id),array('refund_status'=>11,'refund_fee'=>$refund_fee));
                            $res = $this->roomservice_orders_model->cancel_order($order);
                            if($res)
                            {
                                //发送模板消息
                                $orderModel->handle_order($inter_id,$order_id,'',25);
                                $array = array(
                                    'inter_id'=>$this->inter_id,
                                    'openid' =>'',
                                    'order_id' => $order_id,
                                    'type' => 2,//跟踪
                                    'content'=>'订单已取消',
                                    'order_status'=>$orderModel::OS_PER_CANCEL,
                                    'add_time' => date('Y-m-d H:i:s')
                                );
                                $this->db->insert('roomservice_action',$array);
                                $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录
                                $return['errcode'] = 0;
                                $return['msg'] = '订单取消成功，退款处理中';
                                echo json_encode($return);
                                die;//发送模板消息
                            }
                            else
                            {
                                $return['msg'] = '取消失败';
                                echo json_encode($return);
                                die;
                            }
                        }*/

                        //分账退款end
                        if(is_array($refund_result) && "SUCCESS" == $refund_result['return_code'] && "SUCCESS" == $refund_result['result_code']){

                            //更新订单表扣减订单实付金额、记录退款金额
                            $set_item = array(
                                'refund_money'  => 'refund_money + '.$order['pay_money'],
                                'pay_money'     => 'pay_money - '.$order['pay_money'],
                            );

                            $where_item = array(
                                'order_id' => $order['order_id'],
                            );
                            $res_money = $this->roomservice_orders_model->update_data($set_item,$where_item);

                            $trade_no = $refund_result['transaction_id'];
                            $out_refund_no = $reund_sn;//就是 refund_sn  2017-06-14 分账的退款参数兼容 先改成这个

                            $refund_data = array();
                            $refund_data['refund_id'] = $refund_result['refund_id'];
                            $refund_data['refund_fee'] = $total_fee;
                            $refund_data['id'] = $id;
                            //更新退款表 订单表 库存
                            $update_res = $this->roomservice_orders_model->update_refund_data($order,$out_refund_no,$trade_no,$refund_data);

                            if($update_res){
                                //发送模板消息
                                $res = $orderModel->handle_order($inter_id,$order_id,'',26);
                                $array = array(
                                    'inter_id'=>$this->inter_id,
                                    'openid' =>'',
                                    'order_id' => $order_id,
                                    'type' => 2,//跟踪
                                    'content'=>'订单已取消',
                                    'order_status'=>$orderModel::OS_PER_CANCEL,
                                    'add_time' => date('Y-m-d H:i:s')
                                );
                                $this->db->insert('roomservice_action',$array);
                                $order_log['action_note'] = '前台主动取消，申请微信退款';
                                $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录
                                echo json_encode ( array (
                                    'errcode' =>0,
                                    'msg' => '申请退款成功，请刷新订单'
                                ));
                                die;
                            }else{
                                echo json_encode ( array (
                                    'errcode' =>1,
                                    'msg' => '退款异常'
                                ));
                                die;
                            }
                        }else{
                            echo json_encode ( array (
                                'errcode' =>0,
                                'msg' => '发起退款的时候失败，请稍后再试'
                            ) );
                            die;
                        }
                    }elseif(2 == $order['pay_way']){//储值支付
                        //处理余额退款
                        $balance_refund = $orderModel->balance_refund($order['inter_id'],$order['openid'], $order['pay_money']);
                        if($balance_refund){//退款成功

                            //更新订单表扣减订单实付金额、记录退款金额
                            $set_item = array(
                                'refund_money'  => 'refund_money + '.$order['pay_money'],
                                'pay_money'     => 'pay_money - '.$order['pay_money'],
                            );

                            $where_item = array(
                                'order_id' => $order['order_id'],
                            );
                            $res_money = $this->roomservice_orders_model->update_data($set_item,$where_item);

                            $refund_data = array();
                            $refund_data['refund_id'] = 'banlance';
                            $refund_data['refund_fee'] = $order['pay_money'];
                            $refund_data['id'] = $id;
                            //更新退款表 订单表 库存
                            $update_res = $this->roomservice_orders_model->update_refund_data($order,$reund_sn,'',$refund_data);
                            if($update_res){
                                //发送模板消息
                                $res = $orderModel->handle_order($inter_id,$order_id,'',26);
                                $array = array(
                                    'inter_id'=>$this->inter_id,
                                    'openid' =>'',
                                    'order_id' => $order_id,
                                    'type' => 2,//跟踪
                                    'content'=>'订单已取消',
                                    'order_status'=>$orderModel::OS_PER_CANCEL,
                                    'add_time' => date('Y-m-d H:i:s')
                                );
                                $this->db->insert('roomservice_action',$array);
                                $order_log['action_note'] = '前台主动取消，申请储值退款';
                                $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录
                                echo json_encode ( array (
                                    'errcode' =>0,
                                    'msg' => '已退款成功，请刷新订单'
                                ));
                                die;
                            }else{
                                echo json_encode ( array (
                                    'errcode' =>1,
                                    'msg' => '异常，更新退款信息失败'
                                ));
                                die;
                            }
                        }else{
                            echo json_encode ( array (
                                'errcode' =>1,
                                'msg' => '退款失败'
                            ));
                            die;
                        }
                    }elseif(4 == $order['pay_way']){//威富通支付
                        $refund_fee = intval($order['pay_money'] * 100);
                        $total_fee = intval($order['sub_total'] * 100);
                        $this->load->model('roomservice/Roomservice_wxpay_model');
                        $refund_result = $this->Roomservice_wxpay_model->weifutong_refund($order['inter_id'],$order['trade_no'],$total_fee,$refund_fee,$reund_sn,$order['hotel_id']);
                        if(is_array($refund_result)){

                            //更新订单表扣减订单实付金额、记录退款金额
                            $set_item = array(
                                'refund_money'  => 'refund_money + '.$order['pay_money'],
                                'pay_money'     => 'pay_money - '.$order['pay_money'],
                            );

                            $where_item = array(
                                'order_id' => $order['order_id'],
                            );
                            $res_money = $this->roomservice_orders_model->update_data($set_item,$where_item);

                            $trade_no = $refund_result['transaction_id'];
                            $out_refund_no = $refund_result['out_refund_no'];//就是 refund_sn

                            $refund_data = array();
                            $refund_data['refund_id'] = $refund_result['refund_id'];
                            $refund_data['refund_fee'] = $total_fee;
                            $refund_data['id'] = $id;
                            //更新退款表 订单表 库存
                            $update_res = $this->roomservice_orders_model->update_refund_data($order,$out_refund_no,$trade_no,$refund_data);

                            if($update_res){
                                //发送模板消息
                                $res = $orderModel->handle_order($inter_id,$order_id,'',26);
                                $array = array(
                                    'inter_id'=>$this->inter_id,
                                    'openid' =>'',
                                    'order_id' => $order_id,
                                    'type' => 2,//跟踪
                                    'content'=>'订单已取消',
                                    'order_status'=>$orderModel::OS_PER_CANCEL,
                                    'add_time' => date('Y-m-d H:i:s')
                                );
                                $this->db->insert('roomservice_action',$array);
                                $order_log['action_note'] = '前台主动取消，申请威富通退款';
                                $this->db->insert('roomservice_orders_log',$order_log);//记录订单操作记录
                                echo json_encode ( array (
                                    'errcode' =>0,
                                    'msg' => '申请退款成功，请刷新订单'
                                ));
                                die;
                            }else{
                                echo json_encode ( array (
                                    'errcode' =>1,
                                    'msg' => '退款异常'
                                ));
                                die;
                            }
                        }else{
                            echo json_encode ( array (
                                'errcode' =>1,
                                'msg' => '向服务器发起退款的时候失败，请稍后再试'
                            ) );
                            die;
                        }
                    }
                }
            }else{
                $return['msg'] = 'data error';
                echo json_encode($return);
                die;
            }
        }else{
            $return['msg'] = 'data error';
            echo json_encode($return);
            die;
        }
    }

    //订单列表
    public function orderlist(){
        $openid		= $this->session->userdata($this->inter_id."openid");
        $inter_id	= $this->inter_id;
        $type= $this->input->get('type')?intval($this->input->get('type')):1;//默认客房
        $data = array();
        $data['inter_id'] = $this->inter_id;
        $data['type'] = $type;
        //查询公众号信息
        $this->load->model ( 'wx/Publics_model' );
        $public = $this->Publics_model->get_public_by_id ( $this->inter_id );
        $data['public'] = $public;
        //查询该inter_id 下的所有店铺信息
        $this->load->model ( 'roomservice/Roomservice_shop_model' );
        $shops = $this->Roomservice_shop_model->get(array('inter_id'=>$inter_id,'is_delete'=>0),'all');
       // $shops = $this->db->where(array('inter_id'=>$inter_id,'is_delete'=>0))->get('roomservice_shop')->result_array();
        $shoplist = array();
        if($shops){
            foreach($shops as $sk=>$sv){
                $shoplist[$sv['shop_id']] = $sv['shop_name'];
            }
        }
        unset($shops);
        $data['shoplist'] = $shoplist;
        $this->load->model('roomservice/roomservice_orders_model');
        $data['orderModel'] = $this->roomservice_orders_model;
        //查询订单信息
        /*$this->db->where(array('inter_id'=>$inter_id,'openid'=>$openid,'type'=>$type));
        $this->db->order_by('order_id','desc');
        $order = $this->db->get('roomservice_orders')->result_array();*/
        $order = $this->roomservice_orders_model->get_list(array('inter_id'=>$inter_id,'openid'=>$openid));//,'type'=>$type 注释售卖类型 BY 沙沙
        $order_detail  =  array();
        if($order){
            $order_ids = array_column($data,'order_id');//order_ids 传数组
            $order_goods = $this->roomservice_orders_model->get_order_goods_info(array('inter_id'=>$this->inter_id,'order_ids'=>$order_ids));
            foreach($order_goods as $k=>$v){//订单详情处理
                $order_detail[$v['order_id']][] = $v;
                unset($order_goods);
            }
            foreach($order as $k=>$v){
                //查询商品详情
               // $detail = $this->roomservice_orders_model->get_order_item_detail(array('inter_id'=>$inter_id,'openid'=>$openid,'order_id'=>$v['order_id']));
                $order[$k]['show_name'] = !empty($order_detail[$v['order_id']][0]['goods_name'])?$order_detail[$v['order_id']][0]['goods_name']  . '等' . count($order_detail[$v['order_id']]) . '份 商品':'';//(isset($detail[0])?$detail[0]['goods_name']:'') . '等' . count($detail) . '份 商品';
                $order[$k]['pay_url'] = '';
                if($v['pay_way'] == 1){//wxpay
                    $order[$k]['pay_url'] = site_url('wxpay/roomservice_pay') . '?id=' .$inter_id .'&order_id=' .$v['order_id'];//默认使用微信原生配置;;
                }elseif($v['pay_way'] == 4){//wft
                    $order[$k]['pay_url'] = site_url('wftpay/roomservice_pay') . '?id=' .$inter_id .'&order_id=' .$v['order_id'];//默认使用威富通配置;;
                }
            }
        }
        $data['orderlist'] = $order;
        $this->display('roomservice/orderlist',$data);
    }


    /**
     * 封装curl的调用接口，post的请求方式
     * @param string URL
     * @param string POST表单值
     * @param array 扩展字段值
     * @param second 超时时间
     * @return 请求成功返回成功结构，否则返回FALSE
     */
    protected function doCurlPostRequest( $url , $post_data , $timeout = 20) {
        $requestString = http_build_query($post_data);
        if ($url == "" || $timeout <= 0) {
            return false;
        }
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //設置請求數據返回的過期時間
        curl_setopt ( $curl, CURLOPT_TIMEOUT, ( int ) $timeout );
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, true);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestString);
        //执行命令
        $res = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //写入日志
        $log_data = array(
            'url'=>$url,
            'post_data'=>$post_data,
            'result'=>$res,
        );
        $this->api_write_log(serialize($log_data) );
        return json_decode($res,true);
    }

    /**
     * 把请求/返回记录记入文件
     * @param String content
     * @param String type
     */
    protected function api_write_log( $content, $type='request' )
    {
        $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'. DS. 'front'. DS. 'membervip'. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $CI = & get_instance();
        $ip= $CI->input->ip_address();
        $fp = fopen( $path. $file, 'a');

        $content= str_repeat('-', 40). "\n[". $type. ' : '. date('Y-m-d H:i:s'). ' : '. $ip. ']'
            . "\n". $content. "\n";
        fwrite($fp, $content);
        fclose($fp);
    }

    //获取授权token
    protected function get_Token(){
        $post_token_data = array(
            'id'=>'vip',
            'secret'=>'iwide30vip',
        );
        $token_info = $this->doCurlPostRequest( INTER_PATH_URL."accesstoken/get" , $post_token_data );
        $this->_token = isset($token_info['data'])?$token_info['data']:"";
    }

    /**
     * 根据经纬度
     * 获取达达配送费
     * author:沙沙
     */
    protected function get_shipping_cost($address)
    {
        $cost = 0.05;
        return $cost;
    }
}