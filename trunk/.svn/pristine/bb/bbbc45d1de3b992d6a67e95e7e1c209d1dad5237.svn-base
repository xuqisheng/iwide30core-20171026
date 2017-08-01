<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goods extends MY_Admin_Roomservice {

	protected $label_module= '房间订餐';
	protected $label_controller= '商品列表';
	protected $label_action= '商品列表';
	
	function __construct(){
		parent::__construct();
	}
	

	function index(){

        $filter = array();
        $filter['inter_id'] = $this->inter_id;
        $filter['sale_type'] = '3';
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
            $goods_ids = array_column($data,'goods_id');
            $this->load->model('roomservice/roomservice_spec_setting_model');
            $spec_arr = $this->roomservice_spec_setting_model->get_goods_sepc_info(array('inter_id' => $this->inter_id), $goods_ids);
            if (!empty($spec_arr)) {//返回的格式是 array(goods_id=>array())
                foreach ($data as $k => $v) {
                    if (isset($spec_arr[$v['goods_id']]) && !empty($spec_arr[$v['goods_id']]) && !empty($v['spec_list'])) {
                        $low_price = $spec_arr[$v['goods_id']][0]['spec_price'];
                        $data[$k]['shop_price'] = $low_price;
                    }
                }
            }
        }
        //分页
        $base_url = site_url('/take-away/goods/index/');
        $first_url = site_url('/take-away/goods/index/').$search_url;
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
            $data['inter_id'] = $this->inter_id;
            $data['goods_name'] = $post['goods_name'];
            $data['hotel_id'] = $post['hotel_id'];
            $data['shop_id'] = $post['shop_id'];
            $data['is_recommend'] = isset($post['is_recommend'])?$post['is_recommend']:0;
            $data['is_discount'] = isset($post['is_discount'])?$post['is_discount']:1;//店铺优惠状态
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

            $data['sale_type'] = '3';
            //$filter['sort_order'] = $post['sort_order'];
            $this->load->model('roomservice/roomservice_goods_model');
            $result = $this->roomservice_goods_model->save_goods_data($data);//规格信息在这个方法里面加入
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
        //获取店铺
        $this->load->model('roomservice/roomservice_shop_model');
        //$filter['inter_id'] = $this->inter_id;
        $filterH['sale_type'] = '3';
        $shops = $this->roomservice_shop_model->get_list($filterH);
        //获取商品分组
        $this->load->model('roomservice/roomservice_goods_group_model');
      //  $filterH['shop_id'] = $shops[0]['shop_id'];//取第一个
       // $group = $this->db->get_where('roomservice_goods_group',$filterH)->result_array();
        $this->load->model('roomservice/roomservice_spec_setting_model');
        $auto_increment_id = $this->roomservice_spec_setting_model->get_spec_auto_increment_id();
        $view_params = array(
            'hotel' => $hotels,
            'shops' => $shops,
         //   'group'=>$group,
            'inter_id'=>$this->inter_id,
            'auto_increment_id' => $auto_increment_id,
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
            $data = array();
            $data['inter_id'] = $this->inter_id;
            $data['goods_name'] = $post['goods_name'];
            $data['hotel_id'] = $post['hotel_id'];
            $data['shop_id'] = $post['shop_id'];
            $data['is_recommend'] = isset($post['is_recommend'])?$post['is_recommend']:0;
            $data['is_discount'] = isset($post['is_discount'])?$post['is_discount']:1;//店铺优惠状态
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
            $data['sale_type'] = '3';

            //处理规格信息
            $ori_spec_list = array();//保存原来的规格信息 后面做比较
            $ori_spec_res = $this->db->where(array('inter_id'=>$this->inter_id,'goods_id'=>$res['goods_id']))->get('iwide_roomservice_spec_setting')->result_array();
            if($ori_spec_res){
                foreach($ori_spec_res as $k=>$v){
                    $ori_spec_list[$v['setting_id']] = $v;
                }
                unset($ori_spec_res);
            }
            // $data['end_time'] = $post['end_time'];
            //$filter['sort_order'] = $post['sort_order'];
            $this->load->model('roomservice/roomservice_goods_model');
            $result = $this->roomservice_goods_model->update_goods_data($data,$id,$ori_spec_list);
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
            $filterH['sale_type'] = '3';
            $shops = $this->roomservice_shop_model->get_list($filterH);
            //获取商品分组
            $this->load->model('roomservice/roomservice_goods_group_model');
            $filter['shop_id'] = $res['shop_id'];//取第一个
            $filter['inter_id'] = $this->inter_id;
            $group = $this->db->get_where('roomservice_goods_group',$filter)->result_array();
            //处理规格
            if(!empty($res['spec_list'])){
                //$this->db->select('setting_spec_compose');
                $spec = $this->db->where(array('inter_id'=>$this->inter_id,'goods_id'=>$res['goods_id']))->get('iwide_roomservice_spec_setting')->result_array();
                if(!empty($spec)){
                    $array = $arr = array();
                    foreach($spec as $k=>$v){
                        $_v = json_decode($v['setting_spec_compose'],true);
                        //$_v['setting'] = $v['setting_id'];
                        foreach($_v as $kk=>$vv){
                            $vv['admin_setting_id'] = $v['setting_id'];
                            $vv['stock']  = $v['spec_stock'];
                            $array[$kk] = $vv;
                        }//var_dump($array);die;
                       // $arr = $arr + $array;
                    }//var_dump($arr);die;

                    $spec_list = json_decode($res['spec_list'],true);
                    $spec_list['data'] = $array;//var_dump($spec_list);die;
                    $res['spec_list'] = json_encode($spec_list,JSON_UNESCAPED_UNICODE);
                }
            }
            $this->load->model('roomservice/roomservice_spec_setting_model');
            $auto_increment_id = $this->roomservice_spec_setting_model->get_spec_auto_increment_id();
            $view_params = array(
                'posts' => $res,
                'hotel' => $hotels,
                'shops' => $shops,
                'group'=>$group,
                'inter_id'=>$this->inter_id,
                'ids' => $id,
                'auto_increment_id' => $auto_increment_id,
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
