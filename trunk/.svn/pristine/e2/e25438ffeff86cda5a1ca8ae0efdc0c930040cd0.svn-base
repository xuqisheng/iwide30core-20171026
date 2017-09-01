<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Booking extends MY_Front {
	public $openid;
    protected $_token;

	function __construct() {

		parent::__construct ();
        /*$this->session->set_userdata (array (
            'inter_id' => 'a429262687' ,
            'hotel_id' => 1026,
            'pay_code' => 9,
            'a429262687openid' => 'oX3Wojj8h46C_CN5NoRPfbXwxND8'
        ));*/
       // $this->get_Token();
	}
    public function index(){
        $data['inter_id'] = $this->inter_id ;
        //查询预约量
        $this->db->where(array(
            'inter_id'=>$this->inter_id,
           // 'shop_id'=>1,
            'book_time >='=> date('Y-m-d'),
            'book_time <' => date('Y-m-d 23:59:59')
        ));
        $res = $this->db->get('booking_item')->result_array();
        $shop1 = $shop2 = 0;
        if($res){
            foreach($res as $k=>$v){
                if($v['shop_id']==1){
                    $shop1++;
                }
                if($v['shop_id']==2){
                    $shop2++;
                }
            }
        }
        $data['shop1'] = $shop1;
        $data['shop2'] = $shop2;
        $this->display('booking/index',$data);
    }
	
	public function show(){
        $shop_id = $this->input->get('sid', TRUE );
        if(empty($shop_id)){
            redirect(site_url('booking/booking/index?id='.$this->inter_id));
            die;
        }
		$data = array();
        $data['shop_id'] = $shop_id;
        $data['shop'] = array(1=>'凤轩中餐厅',2=>'天堂岛池畔餐厅');
        $data['inter_id'] = $this->inter_id ;

        if(!empty($data['inter_id']) && !empty($data['hotel_id'])){
            $this->session->set_userdata (array (
                'inter_id' => $this->inter_id ,
               // 'hotel_id' => $data['hotel_id'],
            ));
        }
        $openid	= $this->session->userdata($this->inter_id."openid");
        if(!$openid){
            echo 'error';
            die;
        }
        //取最后第一次信息

        $this->db->where(array(
            'openid'=>$openid,
            'inter_id'=>$this->inter_id,
        ));
        $this->db->order_by('id','desc');
        $this->db->limit(1);
        $last_info = $this->db->get('booking_item')->row_array();
        $data['name'] = $data['phone'] = '';
        if($last_info){
            $data['name'] = $last_info['name'];
            $data['phone'] = $last_info['phone'];
        }

        $this->display('booking/show',$data);
	}
	
	/**
	 * ajax 保存订单，
	 * 适用于：[default];
	 */
	public function create_booking()
	{//print_r($_POST);die;
		$arr['openid']		= $this->session->userdata($this->inter_id."openid");
		$arr['inter_id']	= $this->session->userdata("inter_id");

        $arr['hotel_id'] = $this->input->post('hotel_id', TRUE );
       // $arr['shop_id'] = $this->input->post('shop_id', TRUE );
        $arr['shop_name'] = addslashes($this->input->post('shop_name', TRUE ));
        if($arr['shop_name'] == '凤轩中餐厅'){
            $arr['shop_id'] =1;
            $arr['img'] = base_url('public/booking/default/images/egimg/img2.jpg');
        }elseif($arr['shop_name'] == '天堂岛池畔餐厅'){
            $arr['shop_id'] =2;
            $arr['img'] = base_url('public/booking/default/images/egimg/img1.jpg');
        }
        $arr['book_time'] = addslashes($this->input->post('book_time', TRUE ));
		$arr['num'] = $this->input->post('num', TRUE );
		$arr['name'] = addslashes($this->input->post('name', TRUE ));
        $arr['phone'] = addslashes($this->input->post('phone', TRUE ));
        $arr['note'] = addslashes($this->input->post('note', TRUE ));
        $return = array('errcode'=>1,'msg'=>'失败','data'=>array());
        if(empty($arr['book_time'])|| empty($arr['num']) || empty($arr['name']) || empty($arr['phone'])){
            $return['msg'] = 'input data error!';
            echo json_encode($return);
            die;
        }
        $arr['add_time'] = date('Y-m-d H:i:s');
        //控制下 一个openid最多100条
        $res = $this->db->get_where('booking_item',array('openid'=>$arr['openid']))->result_array();
        if(!empty($res) && count($res) >99){
            $return['msg'] = 'max booking num in this openid!';
            echo json_encode($return);
            die;
        }

        $insert = $this->db->insert('booking_item',$arr);
        if($insert){
            $return['errcode'] = 0;
            $return['msg'] = 'ok';
            $return['data']['url'] = site_url('booking/booking/my_booking?id='.$arr['inter_id']);
            echo json_encode($return);
            die;
        }else{
            $return['msg'] = 'service error!';
            echo json_encode($return);
            die;
        }

	}

    //取消预约
    public function cancel(){
        $openid		= $this->session->userdata($this->inter_id."openid");
        $inter_id	= $this->session->userdata("inter_id");

        $id = $this->input->post('id', TRUE );
        $res = $this->db->update('booking_item',array('status'=>3,'cancel_time'=>date('Y-m-d H:i:s')),array('id'=>$id,'inter_id'=>$inter_id,'openid'=>$openid,'status'=>1));
        if($res){
            echo 1;
        }else{
            echo 0;
        }
        die;
    }

    /**
     * 跳转到我的预约，
     */
    public function my_booking()
    {
        $type = addslashes($this->input->get('type',true));
        $status = '';
        $type_name = '预约中';
        if($type == 'booking'){
            $arr['status'] = 1;
        }elseif($type == 'finish'){
            $arr['status'] = 2;
            $type_name = '已用餐';
        }elseif($type == 'cancel'){
            $arr['in_status'] = array(3,4);//3 个人取消 4 酒店取消
            $type_name = '已取消';
        }elseif($type == 'all'){
            $type_name = '全部';
        }
        if(empty($type)){
            $type = 'booking';
            $arr['status'] = 1;
        }
        $data['type'] = $type;//var_dump($type);die;
        $data['type_name'] = $type_name;
        $data['inter_id'] = $this->inter_id;
        $arr['openid']		= $this->session->userdata($this->inter_id."openid");
        $arr['inter_id']	= $this->inter_id;
        $this->load->model ( 'booking/booking_model' );
        $res = $this->booking_model->get_booking_item($arr);
        $data['res'] = $res;//var_dump($data);die;
        $this->display('booking/my_booking',$data);
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


}