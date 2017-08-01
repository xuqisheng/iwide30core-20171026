<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Zipimage_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}


    function upload_zip($inter_id){

//        $res = $_SESSION['res'];

        $res=$this->db->query("SELECT * FROM `iwide_qrcode` WHERE inter_id='{$inter_id}'")->result();

//        $res = json_decode($data,true);
        $image = array();

        foreach($res as $arr){

            if($arr->id >= $_GET['b'] && $arr->id <= $_GET['e']){

                $arr->name = iconv('utf-8', 'gb2312',$arr->name);
                $arr->intro = iconv('utf-8', 'gb2312',$arr->intro);

                $image[]=array('image_src' => $arr->url, 'image_name' => $arr->id.'_'.$arr->name.'_'.$arr->intro.'.jpg');

            }

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


}