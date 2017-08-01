<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Printer extends MY_Admin_Roomservice {

	protected $label_module= '房间订餐';
	protected $label_controller= '打印机列表';
	protected $label_action= '打印机列表';
    public  $type = array('new_order'=>'新订单提醒','ensure_order'=>'接单打印','handle_print'=>'后台手工打印','remind_order'=>'催单提醒');
    public $okpay_print_type = array('okpay_pay_success'=>'支付成功');
    public $offer_print_type = array('appointment'=>'后台取号');


    function __construct(){
		parent::__construct();
	}
	

	public function index(){

        $filter = array();
        $filter['inter_id'] = $this->inter_id;
        //get请求接收参数
        $params= $this->input->get();
        if(is_array($params) && count($params)>0 )
            $params= array_merge($params, $filter);
        //post请求接收参数
        $post = $this->input->post();
        if(is_array($post)){
            $params = array_merge($post,$filter);
        }//var_dump($this->uri->segment(3));die;
        $filter['shop_id'] = $this->input->get('shop_id')?addslashes($this->input->get('shop_id')):'';
        $filter['wd'] = $this->input->get('wd')?addslashes($this->input->get('wd')):'';
        $search_url = '?';
        if($filter['wd']!=''){
            $search_url .= 'wd='.$filter['wd'];
        }
        if($filter['shop_id']!=''){
            $search_url .= 'shop_id='.$filter['shop_id'];
        }
        $filterH = array('inter_id'=>$this->inter_id);
        if(!empty($this->session->get_admin_hotels())){
            $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
            //$filter['in_hotel_id'] = explode(',',$this->session->get_admin_hotels());
        }
        $per_page = 30;
        $cur_page = empty($this->uri->segment(4)) ? 0 : ($this->uri->segment(4));
        $this->load->model('roomservice/roomservice_printer_model');//var_dump($filter);die;
        $res = $this->roomservice_printer_model->get_page($filter,$cur_page,$per_page);
        $data = isset($res[1])?$res[1]:array();
        $total_count = isset($res[0])?$res[0]:0;
        $base_url = site_url('/take-away/printer/index/');
        $first_url = site_url('/take-away/printer/index/').$search_url;
        $suffix = $search_url;
        //var_dump($base_url);die;
        //获取公众号下的酒店
        $this->load->model ( 'hotel/hotel_model' );

        $hotels = $this->hotel_model->get_hotel_hash ($filterH );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
        //分页
        $this->pagination($per_page,$cur_page,$base_url,$total_count,4,$first_url,$search_url,$suffix);
        $this->load->model('roomservice/roomservice_shop_model');
        $shops_arr = $this->roomservice_shop_model->get_list($filterH);
        $shops = array();
        if($shops_arr){
            foreach($shops_arr as $v){
                $shops[$v['shop_id']] = $v;
            }
            unset($shops_arr);
        }
        $this->load->model ( 'plugins/Print_model' );
        if(!empty($data)){

            foreach($data as $k=>$v){
                $tmp ='';
                $data[$k]['show_type'] = '';
                $data[$k]['print_status'] = '';
                //查询打印机状态
                $print_status = $this->Print_model->check_printer_status($v['printer_no'],$v['printer_key']);
                if($print_status){
                    $print_status = json_decode($print_status,true);
                    if(isset($print_status['responseCode']) && $print_status['responseCode'] == 0){
                        $data[$k]['print_status'] = $print_status['msg'];
                    }else{
                        $data[$k]['print_status'] = '异常';
                    }
                }
                $v['type'] = json_decode($v['type'],true);
                if(empty($v['type'])){
                    continue;
                }
                if($v['source']==1){
                    foreach($v['type'] as $tk=>$tv){
                        $tmp .= $this->type[$tv] . '+';
                    }
                    $data[$k]['show_type'] =  trim($tmp,'+');
                }else if($v['source']==2){
                    $data[$k]['show_type'] =  empty($v['type'])?'':'支付成功';
                }
                else if($v['source']==3)
                {
                    foreach($v['type'] as $tk=>$tv){
                        $tmp .= $this->offer_print_type[$tv] . '+';
                    }
                    $data[$k]['show_type'] =  trim($tmp,'+');
                }

            }
        }
        //获取快乐付场景
        $this->load->model('okpay/okpay_type_model');
        $okpay_types = $this->okpay_type_model->get_types_info_list(array('inter_id'=>$this->inter_id));
        $okpay_types_arr = array();
        if($okpay_types){
            foreach($okpay_types as $ok=>$ov){
                $okpay_types_arr[$ov['id']] = $ov;
            }
            unset($okpay_types);
        }
        $view_params = array (
            'pagination' => $this->pagination->create_links (),
            'filter'=>$filter,
            'hotels'=>$hotels,
            'shops' => $shops,
            'type'=>$this->type,
            'res' =>$data,
            'inter_id' => $this->inter_id,
            'total'=>$total_count,
            'okpay_types_arr'=>$okpay_types_arr,
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
        if($post)
        {//add数据
            if(empty($post['name']) ||empty($post['printer_no']) || empty($post['printer_key']) || empty($post['type'])){
                $this->session->put_notice_msg('关键数据不能为空！');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/add'));
            }
            $filter = array();
            $filter['inter_id'] = $this->inter_id;
            $filter['name'] = $post['name'];
            $filter['shop_id'] = $post['shop_id'];//快乐送表示 店铺id  快乐送表示场景id
            //查出shop_id对应的hotel_id
            $shop = $this->db->get_where('roomservice_shop',array('inter_id'=>$this->inter_id,'shop_id'=>intval($filter['shop_id']),'is_delete'=>0))->row_array();
            $filter['hotel_id'] = !empty($shop)?$shop['hotel_id']:0;
            $filter['status'] =2;//默认停止
            $filter['printer_no'] = !empty($post['printer_no'])?$post['printer_no']:'';
            $filter['printer_key'] = empty($post['printer_key'])?'':$post['printer_key'];
            $filter['dev_type'] = 'feie';
            $filter['apitype'] = 'php';
            $filter['type']=empty($post['type'])?'':json_encode($post['type']);
            $filter['source']=empty($post['source'])?'':$post['source'];//来源 快乐送 1 或者是快乐付 2
            $filter['add_time'] = date('Y-m-d H:i:s');

            $result =  $this->db->insert('roomservice_printer_info',$filter);
            $message= ($result)?
                $this->session->put_success_msg('新增成功'):
                $this->session->put_notice_msg('新增失败');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
            die;
        }
        $s = !empty($this->input->get ('s'))?$this->input->get ('s'):1;

        //页面
        //获取公众号下的酒店
     //   $this->load->model ( 'hotel/hotel_model' );
        $filterH = array('inter_id'=>$this->inter_id,'hotel_id'=>'');
        if(!empty($this->session->get_admin_hotels())){
            $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
        }
        $this->load->model('roomservice/roomservice_shop_model');

        $shops_arr = $this->roomservice_shop_model->get_list($filterH);
        $shops = array();
        if($shops_arr){
            foreach($shops_arr as $v){
                $shops[$v['shop_id']] = $v;
            }
            unset($shops_arr);
        }
        //获取快乐付场景
        $this->load->model('okpay/okpay_type_model');
        $okpay_types = $this->okpay_type_model->get_types_info_list(array('inter_id'=>$this->inter_id,'in_hotel_id'=>$filterH['hotel_id']));
        $view_params = array(
         //   'hotel' => $hotels,
            'shops'=>$shops,
            'type' =>$this->type,
            'okpay_types'=>$okpay_types,//快乐付场景
            'okpay_print_type'=>$this->okpay_print_type,//打印的类型
            'offer_print_type'=>$this->offer_print_type,//取号
            's'=>$s,
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
        if($post&&$id){//update数据
            if(empty($post['name']) ||empty($post['printer_no']) || empty($post['printer_key'])  || empty($post['type'])){
                $this->session->put_notice_msg('关键数据不能为空！');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/edit?ids='.$id));
            }
            $filter = array();
            $filter['name'] = $post['name'];
            $filter['shop_id'] = $post['shop_id'];//快乐送表示 店铺id  快乐送表示场景id
            //查出shop_id对应的hotel_id
            $shop = $this->db->get_where('roomservice_shop',array('inter_id'=>$this->inter_id,'shop_id'=>intval($filter['shop_id']),'is_delete'=>0))->row_array();
            $filter['hotel_id'] = !empty($shop)?$shop['hotel_id']:0;
            $filter['printer_no'] = !empty($post['printer_no'])?$post['printer_no']:'';
            $filter['printer_key'] = empty($post['printer_key'])?'':$post['printer_key'];
            $filter['type']=empty($post['type'])?'':json_encode($post['type']);
            $filter['source']=empty($post['source'])?'':$post['source'];//来源 快乐送 1 或者是快乐付 2
            $result =  $this->db->update('roomservice_printer_info',$filter,array('id'=>$id));
            $message= ($result)?
                $this->session->put_success_msg('更新成功'):
                $this->session->put_notice_msg('更新失败');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
            die;
        }
        //页面
        //获取该条信息
        $res = $this->db->get_where('roomservice_printer_info',array('id'=>$id))->row_array();
        if(!empty($res)){
            if(!empty($res['type'])){
                $res['type'] = json_decode($res['type'],true);
            }else{
                $res['type']= array();
            }
            if(!empty($res['okpay_print_type'])){
                $res['okpay_print_type'] = json_decode($res['okpay_print_type'],true);
            }else{
                $res['okpay_print_type']= array();
            }

            //获取公众号下的酒店
            $this->load->model ( 'hotel/hotel_model' );
            $filterH = array('inter_id'=>$this->inter_id,'hotel_id'=>'');
            if(!empty($this->session->get_admin_hotels())){
                $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
            }
            $hotels = $this->hotel_model->get_hotel_hash ($filterH );
            $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );

            $this->load->model('roomservice/roomservice_shop_model');
            $shops_arr = $this->roomservice_shop_model->get_list($filterH);
            $shops = array();
            if($shops_arr){
                foreach($shops_arr as $v){
                    $shops[$v['shop_id']] = $v;
                }
                unset($shops_arr);
            }
            //获取快乐付场景
            $this->load->model('okpay/okpay_type_model');
            $okpay_types = $this->okpay_type_model->get_types_info_list(array('inter_id'=>$this->inter_id,'in_hotel_id'=>$filterH['hotel_id']));
            $okpay_types_arr = array();
            if($okpay_types){
                foreach($okpay_types as $ok=>$ov){
                    $okpay_types_arr[$ov['id']] = $ov;
                }
                unset($okpay_types);
            }
            $view_params = array(
                'id'=>$id,
                'posts' =>$res,
                'hotel' => $hotels,
                'type'=>$this->type,
                'shops'=>$shops,
                'okpay_types'=>$okpay_types_arr,//快乐付场景
                'okpay_print_type'=>$this->okpay_print_type,//打印的类型
                'offer_print_type'=>$this->offer_print_type,//取号
                's' => $res['source'],
            );
            $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
            echo $html;
        }else{
            echo 'empty data';
            die;
        }

    }

    //改变状态
    public function change_status(){
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());
        $id = $this->input->post('id',true)?$this->input->post('id',true):'';
        $status = $this->input->post('status',true);
        $inter_id = $this->inter_id;
        $where['inter_id'] = $inter_id;
        $where['id'] = $id;
        $update = $this->db->update('roomservice_printer_info',array('status'=>$status),$where);
        if(!empty($update)){
            $return['errcode'] = 0;
            $return['msg'] = '操作成功';
           // $return['data'] = $group;
            echo json_encode($return);
            die;
        }else{
            $return['errcode'] = 1;
            $return['msg'] = '失败';
            echo json_encode($return);
            die;
        }
    }

}
