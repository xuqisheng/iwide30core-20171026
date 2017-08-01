<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends MY_Admin_Roomservice {

	protected $label_module= '房间订餐';
	protected $label_controller= '商铺列表';
	protected $label_action= '商铺列表';
	
	function __construct(){
		parent::__construct();
	}
    protected function pay_type(){
        $pay_type = array(3=>'线下支付');
        //$pay_type = array(1=>'微信支付'/*,2=>'储值支付'*/,3=>'线下支付',4=>'威富通支付');
        //查询订房的支付方式 主要是支持微信支付还是威富通支付 以后还有银联 拉卡拉之类的
        $this->load->model ( 'pay/Pay_model' );
        $pay_ways = $this->Pay_model->get_pay_way ( array (
            'inter_id' => $this->inter_id,
            'module' => 'hotel',//暂用订房的配置
            'status' => 1,
        ));
        if(!empty($pay_ways)){
            foreach($pay_ways as $k=>$v){
                if($v->pay_type=='weixin'){
                    $pay_type[1]='微信支付';
                }elseif($v->pay_type=='balance'){
                    $pay_type[2]='储值支付';
                }elseif($v->pay_type=='weifutong'){
                      $pay_type[4]='威富通支付';
                }
            }
        }
        ksort($pay_type);
        return $pay_type;
    }
	

	public function index(){

        $filter = array();
        $filter['inter_id'] = $this->inter_id;
        $filter['sale_type'] = '2';
        //get请求接收参数
        $params= $this->input->get();
        if(is_array($params) && count($params)>0 )
            $params= array_merge($params, $filter);
        //post请求接收参数
        $post = $this->input->post();
        if(is_array($post)){
            $params = array_merge($post,$filter);
        }//var_dump($this->uri->segment(3));die;
       // $filter['hotel_id'] = empty($this->hotel_id)?'':$this->hotel_id;
        $filter['wd'] = $this->input->get('wd')?addslashes($this->input->get('wd')):'';
        $search_url = '?';
        if($filter['wd']!=''){
            $search_url .= 'wd='.$filter['wd'];
        }
        $filter['is_delete'] = 0;
        $per_page = 30;
        $cur_page = empty($this->uri->segment(4)) ? 0 : ($this->uri->segment(4));
        $this->load->model('roomservice/roomservice_shop_model');//var_dump($filter);die;
        $res = $this->roomservice_shop_model->get_page($filter,$cur_page,$per_page);
        $data = isset($res[1])?$res[1]:array();
        $total_count = isset($res[0])?$res[0]:0;
        $base_url = site_url('/eat-in/shop/index/');
        $first_url = site_url('/eat-in/shop/index/').$search_url;
        $suffix = $search_url;
        //var_dump($base_url);die;
        //获取公众号下的酒店
        $this->load->model ( 'hotel/hotel_model' );
        $filterH = array('inter_id'=>$this->inter_id);
        if(!empty($this->session->get_admin_hotels())){
            $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
        }
        $hotels = $this->hotel_model->get_hotel_hash ($filterH );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
        $sale_type = array(2=>'堂食');
        //优惠信息
        $discount_type = array(0=>'无',1=>'单满减',2=>'每满减',3=>'折扣',4=>'随机减');
        //分页
        $this->pagination($per_page,$cur_page,$base_url,$total_count,4,$first_url,$search_url,$suffix);
        $view_params = array (
            'pagination' => $this->pagination->create_links (),
            'hotels'=>$hotels,
            'sale_type'=>$sale_type,
            'discount_type' => $discount_type,
            'res' =>$data,
            'inter_id' => $this->inter_id,
            'total'=>$total_count,
        );
        echo $this->_render_content ( $this->_load_view_file ( 'index' ), $view_params, TRUE );
	}
	
	public function add()
	{
		if($this->inter_id== FULL_ACCESS){
            $message= $this->session->put_notice_msg('超管!');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
        };
       $post =  $this->input->post ();
       // $submit = addslashes($this->input->post('submit'));
        if($post){//add数据
            if(empty($post['shop_name']) ){
                $this->session->put_notice_msg('名字不能为空！');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/add'));
            }
            $filter = array();
            $filter['inter_id'] = $this->inter_id;
            $filter['shop_name'] = !empty($post['shop_name'])?htmlspecialchars($post['shop_name']):'';
            $filter['hotel_id'] = $post['hotel_id'];
            $filter['status'] = $post['status'];
            $filter['sale_type'] = !empty($post['sale_type'])?$post['sale_type']:'1';
            $filter['sale_around'] = empty($post['sale_around'])?0:$post['sale_around'];
            $filter['pay_type'] = $post['pay_type'];
            if(isset($post['pay_type']) && is_array($post['pay_type'])){//支持的支付方式
                $filter['pay_type'] = implode(',',$post['pay_type']);
            }else{
                $filter['pay_type'] = '1';//默认微信
            }
            if(isset($post['sale_days']) && is_array($post['sale_days'])){//执行日
                $filter['sale_days'] = implode(',',$post['sale_days']);
            }else{
                $filter['sale_days'] = '';
            }
            $filter['msgsaler'] =  isset($post['msgsaler'])?$post['msgsaler']:'';
            $filter['add_time'] = date('Y-m-d H:i:s');
            $filter['start_time'] = isset($post['start_time'])?$post['start_time']:'';
            $filter['end_time'] = isset($post['end_time'])?$post['end_time']:'';
            $filter['wait_time'] = $post['wait_time'];
            //优惠信息
            $filter['discount_type'] = !empty($post['discount_type'])?$post['discount_type']:0;
            if($filter['discount_type'] == 0){
                $filter['discount_start_time'] = '';
                $filter['discount_end_time'] = '';
            }else{
                $filter['discount_start_time'] = !empty($post['discount_start_time'])?$post['discount_start_time']:'';
                $filter['discount_end_time'] = !empty($post['discount_end_time'])?$post['discount_end_time']:'';
            }
            if(!empty($post['config'])){
                $filter['discount_config'] = serialize($post['config']);
            }
            $filter['shipping_type'] = intval($post['shipping_type']);
            $filter['identify_type'] = intval($post['identify_type']);
            $filter['sale_dispatching'] = intval(trim($post['sale_dispatching']));
            $filter['wait_status'] = intval($post['wait_status']);
            $filter['shipping_cost'] = intval(trim($post['shipping_cost']));
            $filter['cover_charge'] = intval(trim($post['cover_charge']));

            $filter['time_range'] = trim($post['time_range']);

            //$filter['sort_order'] = $post['sort_order'];
            $result =  $this->db->insert('roomservice_shop',$filter);
            $message= ($result)?
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
       // $pay_type = array(1=>'微信支付',2=>'储值支付',3=>'线下支付');
        $sale_type = array(2=>'堂食');

        //优惠信息
        $discount_type = array(0=>'无',1=>'单满减',2=>'每满减',3=>'折扣',4=>'随机减');
        $view_params = array(
            'hotel' => $hotels,
            'pay_type'  => $this->pay_type(),
            'sale_type' => $sale_type,
            'discount_type' => $discount_type,
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
        }//var_dump($post);die;
       // $submit = addslashes($this->input->post('submit'));
        if($post&&$id){//update数据
            $filter = array();
            $filter['inter_id'] = $this->inter_id;
            $filter['shop_name'] = !empty($post['shop_name'])?htmlspecialchars($post['shop_name']):'';
            //$filter['hotel_id'] = $post['hotel_id']; 编辑不允许改变hotel_id 不然后面下单存的hotel_id就会有问题
            $filter['status'] = $post['status'];
            $filter['sale_type'] = !empty($post['sale_type'])?$post['sale_type']:'1';//默认客房
            $filter['sale_around'] = empty($post['sale_around'])?0:$post['sale_around'];
            //$filter['pay_type'] = $post['pay_type'];
            if(isset($post['pay_type']) && is_array($post['pay_type'])){//支持的支付方式
                $filter['pay_type'] = implode(',',$post['pay_type']);
            }else{
                $filter['pay_type'] = '1';
            }
            if(isset($post['sale_days']) && is_array($post['sale_days'])){//执行日
                $filter['sale_days'] = implode(',',$post['sale_days']);
            }else{
                $filter['sale_days'] = '';
            }
            $filter['msgsaler'] =  isset($post['msgsaler'])?$post['msgsaler']:'';
            $filter['start_time'] = isset($post['start_time'])?$post['start_time']:'';
            $filter['end_time'] = isset($post['end_time'])?$post['end_time']:'';
            $filter['wait_time'] = $post['wait_time'];
            //优惠信息
            $filter['discount_type'] = !empty($post['discount_type'])?$post['discount_type']:0;
            if($filter['discount_type'] == 0){
                $filter['discount_start_time'] = '';
                $filter['discount_end_time'] = '';
            }else{
                $filter['discount_start_time'] = !empty($post['discount_start_time'])?$post['discount_start_time']:'';
                $filter['discount_end_time'] = !empty($post['discount_end_time'])?$post['discount_end_time']:'';
            }

            if(!empty($post['config'])){
                $filter['discount_config'] = serialize($post['config']);
            }

            //增加配送费,房号识别和配送方式
            $filter['shipping_type'] = intval($post['shipping_type']);
            $filter['identify_type'] = intval($post['identify_type']);
            $filter['wait_status'] = intval($post['wait_status']);
            $filter['sale_dispatching'] = intval(trim($post['sale_dispatching']));
            $filter['shipping_cost'] = intval(trim($post['shipping_cost']));
            $filter['cover_charge'] = intval(trim($post['cover_charge']));

            $filter['time_range'] = trim($post['time_range']);
            //$filter['sort_order'] = $post['sort_order'];
            $result =  $this->db->update('roomservice_shop',$filter,array('shop_id'=>$id));
            $message= ($result)?
                $this->session->put_success_msg('更新成功'):
                $this->session->put_notice_msg('更新失败');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
            die;
        }
        //页面
        //获取该条信息
        $res = $this->db->get_where('roomservice_shop',array('shop_id'=>$id))->row_array();
        if(!empty($res)){
            if(!empty($res['pay_type'])){
                $res['pay_type'] = explode(',',$res['pay_type']);
            }
            if(!empty($res['sale_days'])){
                $res['sale_days'] = explode(',',$res['sale_days']);
            }else{
                $res['sale_days'] = array();
            }
            if(!empty($res['discount_config'])){
                $res['discount_config'] = unserialize($res['discount_config']);
            }
           // var_dump($res);die;
            //获取公众号下的酒店
            $this->load->model ( 'hotel/hotel_model' );
            $filterH = array('inter_id'=>$this->inter_id);
            if(!empty($this->session->get_admin_hotels())){
                $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
            }
            $hotels = $this->hotel_model->get_hotel_hash ($filterH );
            $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );

            //读取分销员信息
            $hotel_id = $res['hotel_id'];
            $this->load->model('distribute/qrcodes_model');
            $query = $this->qrcodes_model->get_salers($this->inter_id,1,'','',NULL,$hotel_id,NULL,2);
            $salers = $query->result_array();
            $tmp = array();
            $saler_name = array();
            $msgsaler_ids = !empty($res['msgsaler'])?explode(',',$res['msgsaler']):array();
            if(!empty($salers)){
                foreach($salers as $k=>$v){
                    $tmp[$v['qrcode_id']] = $v['name'];
                    if(in_array($v['qrcode_id'],$msgsaler_ids)){
                        $saler_name[] = $v['name'];
                    }
                }
            }
            $salers = $tmp;
          //  $pay_type = array(1=>'微信支付',2=>'储值支付',3=>'线下支付');
            $sale_type = array(2=>'堂食');
            //优惠信息
            $discount_type = array(0=>'无',1=>'单满减',2=>'每满减',3=>'折扣',4=>'随机减');
            //修复营业时段 以前的开店时间旧数据 BY 沙沙
            if (empty($res['time_range']))
            {
                $time_range[] = array(
                    'name'          => '默认',
                    'start_time'    => !empty($res['start_time']) ? $res['start_time'] : '',
                    'end_time'      => !empty($res['end_time']) ? $res['end_time'] : '',
                );
                $res['time_range'] = json_encode($time_range);
            }

            $view_params = array(
                'id'=>$id,
                'posts' =>$res,
                'hotel' => $hotels,
                'pay_type'  => $this->pay_type(),
                'sale_type' => $sale_type,
                'discount_type' => $discount_type,
                'salers' => $salers,
                'show_saler_name' => !empty($saler_name)?implode(',',$saler_name):'',
            );
            $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
            echo $html;
        }else{
            echo 'empty data';
            die;
        }

    }
    /*
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

    //改变店铺状态
    public function change_status(){
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());
        $shop_id = $this->input->post('shop_id',true)?$this->input->post('shop_id',true):'';
        $status = $this->input->post('status',true);
        $inter_id = $this->inter_id;
        $hotel_id = $this->hotel_id;
        $where['inter_id'] = $inter_id;
        $where['is_delete'] = 0;
        if($shop_id){
            $where['shop_id'] = $shop_id;
        }
        $update = $this->db->update('roomservice_shop',array('status'=>$status),$where);
        if(!empty($update)){
            $return['errcode'] = 0;
            $return['msg'] = '操作成功';
           // $return['data'] = $group;
            echo json_encode($return);
            die;
        }else{
            $return['errcode'] = 1;
            $return['msg'] = '操作失败';
            echo json_encode($return);
            die;
        }
    }


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
	

	
}
