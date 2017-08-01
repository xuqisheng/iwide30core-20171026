<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goods_Group extends MY_Admin_Roomservice {

	protected $label_module= '房间订餐';
	protected $label_controller= '商品分组列表';
	protected $label_action= '分组';
	
	function __construct(){
		parent::__construct();
	}
	

	function index(){
        $filter = array();
        $filter['inter_id'] = $this->inter_id;
        $filter['sale_type'] = 2;
        $filter['hotel_id'] = empty($this->hotel_id)?'':$this->hotel_id;
        $filter['wd'] = $this->input->get('wd')?addslashes($this->input->get('wd')):'';
        $search_url = '?';
        if($filter['wd']!=''){
            $search_url .= 'wd='.$filter['wd'];
        }
        $per_page =30;
        $cur_page = empty($this->uri->segment(4)) ? 0 : ($this->uri->segment(4));
        $filter['is_delete'] = 0;
        $this->load->model('roomservice/roomservice_goods_group_model');
        $res = $this->roomservice_goods_group_model->get_page($filter,$cur_page,$per_page);//var_dump($res);die;
        $data = isset($res[1])?$res[1]:array();
        $total_count = isset($res[0])?$res[0]:0;
        $base_url = site_url('/eat-in/goods_group/index/');
        $first_url = site_url('/eat-in/goods_group/index/').$search_url;
        $suffix = $search_url;
        //获取公众号下的酒店
        $this->load->model ( 'hotel/hotel_model' );
        $filterH = array('inter_id'=>$this->inter_id);
        if(!empty($this->session->get_admin_hotels())){
            $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
        }
        $hotels = $this->hotel_model->get_hotel_hash ($filterH );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
        //分页
        $this->pagination($per_page,$cur_page,$base_url,$total_count,4,$first_url,$search_url,$suffix);
        $view_params = array (
            'pagination' => $this->pagination->create_links (),
            'hotels'=>$hotels,
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
        $post =  $this->input->post ();//var_dump($post);die;
        //$submit = addslashes($this->input->post('submit'));
        $filter = array();
        $filter['sale_type'] = 2;
        if($post){//add数据
            if(empty($post['group_name'])){
                $this->session->put_notice_msg('名字不能为空！');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/add'));
            }
            $filter['inter_id'] = $this->inter_id;
            $filter['group_name'] = !empty($post['group_name'])?$post['group_name']:'';
            $filter['hotel_id'] = $post['hotel_id'];
            $filter['shop_id'] = $post['shop_id'];
            $filter['p_id'] = 0;
            $filter['sort_order'] = !empty($post['sort_order'])?$post['sort_order']:0;
            $filter['add_time'] = date('Y-m-d H:i:s');
            $filter['status'] = 1;
            $result =  $this->db->insert('roomservice_goods_group',$filter);
            $message= ($result)?
                $this->session->put_success_msg('新增成功'):
                $this->session->put_notice_msg('新增失败');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
            die;
        }
        //页面
        $filter['inter_id'] = $this->inter_id;
       // $filter['hotel_id'] = $this->hotel_id;

        $this->load->model('roomservice/roomservice_shop_model');
        $shop = $this->roomservice_shop_model->get_list($filter);
        $view_params = array(
            'shop' => $shop,
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
        $filter = array();
        $filter['sale_type'] = 2;
        if($post){//add数据
            if(empty($post['group_name']) ){
                $this->session->put_notice_msg('名字不能为空！');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/edit?ids='.$id));
            }

            $filter['inter_id'] = $this->inter_id;
            $filter['group_name'] = !empty($post['group_name'])?$post['group_name']:'';
            $filter['hotel_id'] = $post['hotel_id'];
            $filter['shop_id'] = $post['shop_id'];
            $filter['p_id'] = 0;
            $filter['sort_order'] = !empty($post['sort_order'])?$post['sort_order']:0;
            $filter['status'] = 1;
            $result =  $this->db->update('roomservice_goods_group',$filter,array('group_id'=>$id));
            $message= ($result)?
                $this->session->put_success_msg('更新成功'):
                $this->session->put_notice_msg('更新失败');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
            die;
        }
        $res = $this->db->get_where('roomservice_goods_group',array('group_id'=>$id))->row_array();
        if(!empty($res)) {
            //页面
            $filter['inter_id'] = $this->inter_id;
            // $filter['hotel_id'] = $this->hotel_id;
            $this->load->model('roomservice/roomservice_shop_model');
            $shop = $this->roomservice_shop_model->get_list($filter);
            //查询该分组的店铺名字
            $shop_info = $this->db->get_where('roomservice_shop',array('shop_id'=>$res['shop_id']))->row_array();
            $view_params = array(
                'shop_info'=>$shop_info,
                'shop' => $shop,
                'posts' => $res,
                'id' => $id,
            );
            $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
            echo $html;
        }else{
            echo 'empty data';
            die;
        }


    }

    //ajax读取门店和店铺信息
    public function ajax_get_shop_info(){
        $shop_id = $this->input->post('shop_id',true);
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());
        if(!$shop_id){
            $return['msg'] = 'data error';
            echo json_encode($return);
            die;
        }
        $shop = $this->db->get_where('roomservice_shop',array('shop_id'=>$shop_id))->row_array();
        if(!empty($shop)){
            //获取酒店信息
            //获取公众号下的酒店
            $this->load->model ( 'hotel/hotel_model' );
            $filterH = array('inter_id'=>$this->inter_id);
            if(!empty($this->session->get_admin_hotels())){
                $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
            }
            $hotels = $this->hotel_model->get_hotel_hash ($filterH );
            $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
            $shop['hotel_name'] = isset($hotels[$shop['hotel_id']])?$hotels[$shop['hotel_id']]:'--';
            $sale_type = array(1=>'客房内',2=>'堂食',3=>'外卖');
            $shop['sale_name'] = isset($sale_type[$shop['sale_type']])?$sale_type[$shop['sale_type']]:'--';
            $day = array(1=>'周一',2=>'周二',3=>'周三',4=>'周四',5=>'周五',6=>'周六',7=>'周日');
            $tmp = '';
            if($shop['sale_days']){
                $sale_days = explode(',',$shop['sale_days']);
                foreach($day as $k=>$v){
                    if(in_array($k,$sale_days)){
                        $tmp .= $v .' , ';
                    }
                }
            }
            $shop['shop_time'] = $tmp.$shop['start_time'].'-'.$shop['end_time'];
            $return['errcode'] = 0;
            $return['msg'] = 'ok';
            $return['data'] = $shop;
            echo json_encode($return);
            die;
        }else{
            $return['msg'] = '无该店铺';
            echo json_encode($return);
            die;
        }
    }

    //ajax 根据inter_id hotel_id shop_id 获取分组
    public function ajax_get_group_info(){
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());
        $shop_id = $this->input->post('shop_id',true)?$this->input->post('shop_id',true):'';
        $inter_id = $this->inter_id;
       // $hotel_id = $this->hotel_id;
        $where['inter_id'] = $inter_id;
        $where['is_delete'] = 0;
        if($shop_id){
            $where['shop_id'] = $shop_id;
        }
        /*if($hotel_id){
            $where['hotel_id'] = $hotel_id;
        }*/
        $group = $this->db->get_where('roomservice_goods_group',$where)->result_array();
        if(!empty($group)){
            $return['errcode'] = 0;
            $return['msg'] = 'ok';
            $return['data'] = $group;
            echo json_encode($return);
            die;
        }else{
            $return['errcode'] = 1;
            $return['msg'] = '无分组数据';
            echo json_encode($return);
            die;
        }
    }

    //ajax
    public function del_group(){
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());
        $group_id = $this->input->post('group_id',true)?$this->input->post('group_id',true):'';
        $shop_id = $this->input->post('shop_id',true)?$this->input->post('shop_id',true):'';
        $inter_id = $this->inter_id;
        $hotel_id = $this->hotel_id;

        $update = $this->db->update('roomservice_goods_group',array('is_delete'=>1),array('inter_id'=>$inter_id,'shop_id'=>$shop_id,'group_id'=>$group_id));
        if(!empty($update)){
            $return['errcode'] = 0;
            $return['msg'] = 'ok';
            echo json_encode($return);
            die;
        }else{
            $return['errcode'] = 1;
            $return['msg'] = '无数据';
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
