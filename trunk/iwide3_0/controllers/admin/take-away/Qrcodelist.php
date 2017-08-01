<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Qrcodelist extends MY_Admin_Roomservice {

	protected $label_module= '房间订餐';
	protected $label_controller= '二维码列表';
	protected $label_action= '二维码';

    public function __construct(){
		parent::__construct();
	}
	

	public function index(){
        $filter = array();
        $filter['inter_id'] = $this->inter_id;
        $filter['sale_type'] = 3;
        $filter['hotel_id'] = empty($this->hotel_id)?'':$this->hotel_id;
        $per_page = 30;
        $cur_page = empty($this->uri->segment(4)) ? 0 : ($this->uri->segment(4));
        $this->load->model('roomservice/roomservice_qrcodes_model');
        $res = $this->roomservice_qrcodes_model->get_page($filter,$cur_page,$per_page);
        $data = isset($res[1])?$res[1]:array();
        $total_count = isset($res[0])?$res[0]:0;
        $base_url = site_url('take-away/qrcodelist/index/');
        //获取公众号下的酒店
        $this->load->model ( 'hotel/hotel_model' );
        $filterH = array('inter_id'=>$this->inter_id);
        if(!empty($this->session->get_admin_hotels())){
            $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
        }
        $hotels = $this->hotel_model->get_hotel_hash ($filterH );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
        $sale_type = array(1=>'客房内',2=>'堂食',3=>'外卖');
        //分页
        $this->pagination($per_page,$cur_page,$base_url,$total_count,4);
        $view_params = array (
            'pagination' => $this->pagination->create_links (),
            'hotels'=>$hotels,
            'sale_type'=>$sale_type,
            'res' =>$data,
            'inter_id' => $this->inter_id,
            'total'=>$total_count,
        );
        echo $this->_render_content ( $this->_load_view_file ( 'index' ), $view_params, TRUE );
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
	
	public function add()
	{
		if($this->inter_id== FULL_ACCESS){
            $message= $this->session->put_notice_msg('超管!');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
        };
        $post =  $this->input->post ();//var_dump($post);die;
        //$submit = addslashes($this->input->post('submit'));
        if($post){//add数据
            if(empty($post['qrcode_name']) ){
                $this->session->put_notice_msg('名称不能为空');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/add'));
            }
            $filter = array();
            $filter['inter_id'] = $this->inter_id;
            $filter['qrcode_name'] = $post['qrcode_name'];
            $filter['hotel_id'] = !empty($post['hotel_id'])?addslashes($post['hotel_id']):'';
            $filter['shop_id'] = !empty($post['shop_id'])?addslashes($post['shop_id']):'';
            $filter['type_id'] = isset($post['type_id'])?$post['type_id']:'';
           // $filter['sale_type'] = isset($post['sale_type'])?$post['sale_type']:'';
            $filter['add_time'] = date('Y-m-d H:i:s');
            //$url= EA_const_url::inst()->get_front_url($this->inter_id, 'roomservice/roomservice/index', array('id'=> $this->inter_id,'hotel_id'=>$filter['hotel_id'], 'shop_id'=>$filter['shop_id'],'type_id'=>$filter['type_id']));
            $url = $this->get_host() . "index.php/qrcodeurl/jump/run?id=".$this->inter_id.'&hid='.$filter['hotel_id'].'&sid='.$filter['shop_id'].'&tid='.$filter['type_id'].'&type=din';
            //生成二维码 并上传到服务器
            $path = 'qrcode/roomservice/qrcodelist/'.date('Ymd');
            $qrcode_name = date('His').'_'.$this->inter_id.'_'.$filter['type_id'].'_'.rand(100,999);
            $upload_url = $this->_get_qrcode_png($url,$qrcode_name,5,1,$path);
            if(empty($upload_url)){
                $this->session->put_notice_msg('生成二维码失败');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/add'));
                die;
            }
            $filter['url'] = $upload_url;
            $filter['sale_type'] = 3;
            $result =  $this->db->insert('roomservice_qrcodes',$filter);
            $message= ($result)?
                $this->session->put_success_msg('新增成功'):
                $this->session->put_notice_msg('新增失败');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
            die;
        }
        //页面
        $filter['inter_id'] = $this->inter_id;
        $filter['sale_type'] = 3;
       // $filter['hotel_id'] = $this->hotel_id;
        $this->load->model('roomservice/roomservice_shop_model');
        $shop = $this->roomservice_shop_model->get_list($filter);
        $view_params = array(
            'shop' => $shop,
        );
        $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
        echo $html;
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
        $filter['sale_type'] = 3;
        $this->load->model('roomservice/roomservice_qrcodes_model');
        $res=$this->roomservice_qrcodes_model->get_qrcodes($filter);
        $image = array();
        if(empty($res)){
            return false;
        }
        foreach($res as $arr){

                $arr['qrcode_name'] = iconv('utf-8', 'gb2312',$arr['qrcode_name']);
                $arr['shop_name'] = iconv('utf-8', 'gb2312',$arr['shop_name']);
                $image[]=array('image_src' => $arr['url'], 'image_name' => $arr['shop_name'].(empty($arr['type_id'])?'':$arr['type_id']).'.png');

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

    function img_upload_url() {
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
    }
    /**
     * 批量导入二维码
     */
    public function batch_import(){
        set_time_limit(0);
        error_reporting(-1);
        ini_set('display_errors', 1);
        $file = $this->img_upload_url ();

        $this->load->model('hotel/hotel_model','hotel_model');
//导入权限
        $entity_id = $this->session->get_admin_hotels ();

        if (! empty ( $entity_id )) {
            $company_info = $this->hotel_model->get_hotel_by_ids ( $this->session->get_admin_inter_id(), $entity_id,null,'key' );
        } else {
            $company_info = $this->hotel_model->get_all_hotels ( $this->session->get_admin_inter_id(),null,'key' );
        }
        //查询interId下的店铺
        $this->load->model('roomservice/roomservice_shop_model');
        $shop_info = $this->roomservice_shop_model->get_list(array('inter_id'=>$this->inter_id,'hotel_id'=>$entity_id,'is_delete'=>0,'sale_type'=>3));
        $shop_list = array();
        if($shop_info){
            foreach($shop_info as $k=>$v){
                $shop_list[$v['shop_id']] = $v;
            }
            unset($shop_info);
        }
//        $file="iwide30dev/www_admin/admin.xlsx";
// /var/www/iwide30dev/www_admin\../www_admin/2016033018313447.xlsx

        if ($file != 'error') {
// 			$file = $_SERVER ['DOCUMENT_ROOT'] . '\\' . $file;
            $this->load->model('plugins/Excel_model','excel_model');


            $data=$this->excel_model->load_exl($file);
            $sheet = $data->getSheet(0);
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            $highestColumm = $sheet->getHighestColumn(); // 取得总列数
//var_dump($highestRow);die;

            if($highestRow>100||$highestRow<=1){

                echo "limited";
                unlink ( $file );

            }else{
                $new_data =  array ();

                for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始
//                     for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
//                         $dataset[] = $sheet->getCell($column.$row)->getValue();
                    //                    echo $column.$row.":".$sheet->getCell($column.$row)->getValue()."<br />";
//                     }

                    if(!empty($company_info[$sheet->getCell('A'.$row)->getValue()])&& !empty($shop_list[$sheet->getCell('B'.$row)->getValue()]) && !empty($sheet->getCell('C'.$row)->getValue())){
//@author lGh 去掉无必要的验证 2016-3-28 17:41:40
//                         $check_data=array(
//                             'hotel_id'=>$sheet->getCell('A'.$row)->getValue(),
//                             'inter_id'=>$this->session->get_admin_inter_id(),
//                             'name'=>$company_info[$sheet->getCell('A'.$row)->getValue()]['name']
//                         );

//                         $check_hotel=$this->qrcode_model->getHotelbyIdName($check_data);


//                         if(empty($check_hotel)){

//                             echo $row;
//                             unlink ( $file );
//                             exit;

//                         }


                    }else{

                        echo $row;
                        unlink ( $file );
                        exit;

                    }

                    $new_data[$row]['hotel_id']= $sheet->getCell('A'.$row)->getValue();
                    $new_data[$row]['shop_id']= $sheet->getCell('B'.$row)->getValue();
                    $new_data[$row]['qrcode_name']= $sheet->getCell('C'.$row)->getValue();
                    $new_data[$row]['type_id']= !empty($sheet->getCell('D'.$row)->getValue())?$sheet->getCell('D'.$row)->getValue():0;
                    $new_data[$row]['inter_id']= $this->session->get_admin_inter_id();
                    $new_data[$row]['add_time']= date('Y-m-d H:i:s');
                    $url= EA_const_url::inst()->get_front_url($this->inter_id, 'roomservice/roomservice/index', array('id'=> $this->inter_id,'hotel_id'=>$new_data[$row]['hotel_id'], 'shop_id'=>$new_data[$row]['shop_id'],'type_id'=>$new_data[$row]['type_id']));
                    $url = $this->get_host() . "index.php/qrcodeurl/jump/run?id=".$this->inter_id.'&hid='.$new_data[$row]['hotel_id'].'&sid='.$new_data[$row]['shop_id'].'&tid='.$new_data[$row]['type_id'].'&type=din';
                    //生成二维码 并上传到服务器
                    $path = 'qrcode/roomservice/qrcodelist/'.date('Ymd');
                    $qrcode_name = date('His').'_'.$this->inter_id.'_'.$new_data[$row]['type_id'].'_'.rand(100,999);
                    $upload_url = $this->_get_qrcode_png($url,$qrcode_name,5,1,$path);

                    $new_data[$row]['url'] = $upload_url;
                    $new_data[$row]['sale_type'] = 3;//外卖
                }

                $this->db->insert_batch('roomservice_qrcodes',$new_data);

                if (file_exists ( $file )) {
                    unlink ( $file );
                }
                echo 'success';
            }

        } else {
            echo 'file error';
        }
    }



}
