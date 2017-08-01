<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Qrcodelist extends MY_Admin_Roomservice {

	protected $label_module= '打赏';
	protected $label_controller= '二维码列表';
	protected $label_action= '二维码';

    public function __construct(){
		parent::__construct();
	}
	

	public function index(){
        $filterH = array('inter_id'=>$this->inter_id);
        $filterH['status'] = 1;
        if(!empty($this->session->get_admin_hotels())){
            $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
        }

        //获取公众号下的酒店
        $this->load->model ( 'hotel/hotel_model' );
        $hotels = $this->hotel_model->get_hotel_hash ($filterH );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
        $this->load->model('distribute/qrcodes_model');
        $depts = $this->qrcodes_model->get_staff_depts($this->inter_id);
        $view_params = array (
            'hotels'=>$hotels,
            'inter_id' => $this->inter_id,
            'depts'      => $depts,
        );
        echo $this->_render_content ( $this->_load_view_file ( 'index' ), $view_params, TRUE );
	}

    public function get_qrcode_index_data(){
        $filter = array();
        $filter['inter_id'] = $this->inter_id;
        //get请求接收参数
        $filter['hotel_id'] = $this->input->get('hotel_id')?$this->input->get('hotel_id'):'';
        $filter['wd'] = $this->input->get('wd')?addslashes($this->input->get('wd')):'';
        $filter['dept'] = $this->input->get('dept')?addslashes($this->input->get('dept')):'';
        $filter['per_page'] = $this->input->get('per_page')?$this->input->get('per_page'):30;
        $filter['cur_page'] = $this->input->get('cur_page')?$this->input->get('cur_page'):1;

        /*$per_page = 15;
        $cur_page = empty($this->uri->segment(4)) ? 0 : ($this->uri->segment(4));*/
        $filterH = array('inter_id'=>$this->inter_id);
        $filterH['status'] = 1;
        if(!empty($this->session->get_admin_hotels())){
            $filterH['in_hotel_id'] = explode(',',$this->session->get_admin_hotels());
            $filter['in_hotel_id'] = explode(',',$this->session->get_admin_hotels());
        }

        //获取公众号下的酒店
        $this->load->model ( 'hotel/hotel_model' );
        $hotels = $this->hotel_model->get_hotel_hash ($filterH );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );

        //获取信息
        $this->load->model('tips/tips_qrcodes_model');
        $res = $this->tips_qrcodes_model->get_page($filter,$filter['cur_page'],$filter['per_page']);
        $data = isset($res[1])?$res[1]:array();
        $total_count = isset($res[0])?$res[0]:0;
        //总页数
        $total_page = ceil($total_count/$filter['per_page']);
        $return = array('errcode'=>0,'msg'=>'成功','data'=>array('total_count'=>$total_count,'total_page'=>$total_page,'cur_page'=>$filter['cur_page'],'result_data'=>$data));

        echo json_encode($return,JSON_UNESCAPED_UNICODE);
        die;
    }
    //域名
    private function get_host(){
        if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
            $host = 'http://klf.jinfangka.cn/';
        }else if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='testing' ){
            $host = 'http://credit.iwide.cn/';
        }else{
            $host = 'http://ihotels.iwide.cn/';
        }
        return $host;
    }


    //批量生成二维码
    public function create_qrcode(){
        if($this->inter_id== FULL_ACCESS){
            $message= $this->session->put_notice_msg('超管!');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
        };
        set_time_limit ( 0 );
        @ini_set('memory_limit','512M');
        //echo '开始生成：';
        //查询已经生成的
        $this->load->model ( 'tips/tips_qrcodes_model' );
        $salers = $this->tips_qrcodes_model->get_all_record($this->inter_id);
        $exist_salers = array();
        if(!empty($salers)){
            $exist_salers = array_column($salers,'saler');
        }
        //获取该次要生成的人
        $res = $this->tips_qrcodes_model->get_salers(array('inter_id'=>$this->inter_id,'exist_salers'=>$exist_salers));//var_dump($res);die;
        if(!empty($res)){//每次300
            foreach($res as $k=>$v){
                if(empty($v['qrcode_id'])){
                    continue;
                }
                $this->batch_insert($v);
                usleep(20);//缓冲一下
            }
            //循环完自己刷新
            sleep(3);
            $this->_redirect(EA_const_url::inst()->get_url('*/*/create_qrcode'));
        }else{
            echo '数据已经全部补全！';
            die;
        }
    }
	
	private function batch_insert($post)
	{
        $filter = array();
        $filter['inter_id'] = $post['inter_id'];
        $filter['hotel_id'] = !empty($post['hotel_id'])?addslashes($post['hotel_id']):'';
        $filter['saler'] = !empty($post['qrcode_id'])?addslashes($post['qrcode_id']):'';
        $filter['saler_name'] = isset($post['name'])?$post['name']:'';
        $filter['hotel_name'] = isset($post['hotel_name'])?$post['hotel_name']:'';
        $filter['add_time'] = date('Y-m-d H:i:s');
        //$url= EA_const_url::inst()->get_front_url($this->inter_id, 'roomservice/roomservice/index', array('id'=> $this->inter_id,'hotel_id'=>$filter['hotel_id'], 'shop_id'=>$filter['shop_id'],'type_id'=>$filter['type_id']));
        //$url = $this->get_host() . "index.php/tips/tips/index?id=".$this->inter_id.'&saler='. $filter['saler'];
        if($this->inter_id== 'a455510007' || $this->inter_id=='a450089706'){
            $url= EA_const_url::inst()->get_front_url($this->inter_id, 'subatips/tips/index', array('id'=> $this->inter_id,'saler'=>$filter['saler']));
        }else{
            $url= EA_const_url::inst()->get_front_url($this->inter_id, 'tips/tips/index', array('id'=> $this->inter_id,'saler'=>$filter['saler']));
        }
        //生成二维码 并上传到服务器
        $path = 'qrcode/tips/qrcodelist/'.date('YmdHi');
        $qrcode_name = '_'.$filter['inter_id'].'_'.$filter['saler'];
        $upload_url = $this->_get_qrcode_png($url,$qrcode_name,5,1,$path);
        if(empty($upload_url)){
            return false;
        }
        $filter['url'] = $upload_url;
        $result =  $this->db->insert('tips_qrcodes',$filter);
       if($result){
           return true;
       }else{
            return false;
       }
    }

    //下载二维码
    public function output_qrcode(){
        $s = $this->input->get('s',true);
        $all = $this->input->get('all',true);
        if(!empty($s)){
            $ids = explode('|',$s);
            $filter['in_id'] = $ids;
        }elseif(empty($s) && $all == 1){
            set_time_limit ( 0 );
            @ini_set('memory_limit','512M');
        }else{
            return false;
        }
        $filter['inter_id'] = $this->inter_id;
        $this->load->model ( 'tips/tips_qrcodes_model' );
        $res=$this->tips_qrcodes_model->get_qrcodes($filter);
        $image = array();
        if(empty($res)){
            return false;
        }
        foreach($res as $arr){

                $arr['saler_name'] = iconv('utf-8', 'gb2312',$arr['saler_name']);
                $image[]=array('image_src' => $arr['url'], 'image_name' => $arr['saler_name'].'_'.$arr['saler'].'.jpg');

        }
        $dfile =  tempnam('/tmp', 'tmp');//产生一个临时文件，用于缓存下载文件
        $this->load->library ( 'Zipfile' );
        $zip = new Zipfile();
//----------------------
        $filename = 'qrcodes.zip'; //下载的默认文件名

//        $image = array(
//            array('image_src' => 'http://b.hiphotos.baidu.com/album/pic/item/caef76094b36acafe72d0e667cd98d1000e99c5f.jpg?psign=e72d0e667cd98d1001e93901213fb80e7aec54e737d1b867', 'image_name' => '图片1.jpg'),
//            array('image_src' => 'http://cdn.duitang.com/uploads/item/201505/29/20150529200613_T2cKW.jpeg', 'image_name' => 'pic/图片2.jpg'),
//        );


        foreach($image as $v){
            $zip->add_file(file_get_contents($v['image_src']),  $v['image_name']);
            // 添加打包的图片，第一个参数是图片内容，第二个参数是压缩包里面的显示的名称, 可包含路径
            // 或是想打包整个目录 用 $zip->add_path($image_path);
        }
//----------------------
        $zip->output($dfile);

// 下载文件
        ob_clean();
        header('Pragma: public');
        header('Last-Modified:'.gmdate('D, d M Y H:i:s') . 'GMT');
        header('Cache-Control:no-store, no-cache, must-revalidate');
        header('Cache-Control:pre-check=0, post-check=0, max-age=0');
        header('Content-Transfer-Encoding:binary');
        header('Content-Encoding:none');
        header('Content-type:multipart/form-data');
        header('Content-Disposition:attachment; filename="'.$filename.'"'); //设置下载的默认文件名
        header('Content-length:'. filesize($dfile));
        $fp = fopen($dfile, 'r');
        while(connection_status() == 0 && $buf = @fread($fp, 8192)){
            echo $buf;
        }
        fclose($fp);
        @unlink($dfile);
        @flush();
        @ob_flush();
        exit();
    }

  /*  function img_upload_url() {
        $config ['upload_path'] = '../www_admin';
        $config ['allowed_types'] = '*';
        $config ['file_name'] = date ( 'YmdHis' ) . rand ( 10, 99 );
        // $config['allowed_types'] ='png|jpg|jpeg|bmp|gif';
        $config ['max_size'] = '20000';
        $this->load->library ( 'upload', $config );
        $this->upload->initialize ( $config );

        if ($this->upload->do_upload ( 'Filedata' )) {
            $a = $this->upload->data ();

//上传服务器后要更改地址
            return $config ['upload_path'] . '/' . $a ['file_name'];

//            return 'iwide30dev/www_admin' . '/' . $a ['file_name'];     //本地测试
        } else {
// 			$this->upload->display_errors('<p>', '</p>');
            echo  $this->upload->display_errors('<p>', '</p>');exit;
        }
    }*/

}
