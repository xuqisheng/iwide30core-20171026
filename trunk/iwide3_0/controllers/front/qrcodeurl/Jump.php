<?php
/*
 * 二维码跳转
 */
class Jump extends MY_Controller {
	function __construct() {
		parent::__construct ();
		$this->debug = $this->input->get ( 'debug' );
		error_reporting ( 0 );
		if (! empty ( $this->debug )) {
			error_reporting ( E_ALL );
			ini_set ( 'display_errors', 1 );
        }
		$this->load->library('MYLOG');
	}

    //房间微服务跳转链接
    public function run(){
        $type = $this->input->get('type', TRUE );
        if(empty($type)){
            echo 'error';
            die;
        }
        if($type=='din'){
            $inter_id = !empty($this->input->get('id', TRUE ))?addslashes($this->input->get('id', TRUE )):'';
            $hotel_id = !empty($this->input->get('hid', TRUE ))?intval($this->input->get('hid', TRUE )):'';
            $shop_id= !empty($this->input->get('sid', TRUE ))?intval($this->input->get('sid', TRUE )):'';
            $type_id= !empty($this->input->get('tid', TRUE ))?addslashes($this->input->get('tid', TRUE )):'';
            //查询inter_id 的网址
            if($inter_id && $hotel_id && $shop_id){
               $public = $this->db->get_where ( 'publics', array (
                    'inter_id' => $inter_id
                ) )->row_array ();
                if($public && !empty($public['domain'])){
                    $host = 'http://'.$public['domain'] . '/index.php/';
                    $url= $host . 'roomservice/roomservice/index?id='.$inter_id.'&hotel_id='.$hotel_id.'&shop_id='.$shop_id.'&type_id='.$type_id;
                    redirect($url);
                }else{
                    echo 'data error';
                    die;
                }
            }else{
                echo 'data error';
                die;
            }
        }

    }


    //房间微服务跳转链接
    public function run_ticket(){
        $type = $this->input->get('type', TRUE );
        if(empty($type)){
            echo 'error';
            die;
        }
        if($type=='din'){
            $inter_id = !empty($this->input->get('id', TRUE ))?addslashes($this->input->get('id', TRUE )):'';
            $hotel_id = !empty($this->input->get('hid', TRUE ))?intval($this->input->get('hid', TRUE )):'';
            $shop_id= !empty($this->input->get('sid', TRUE ))?intval($this->input->get('sid', TRUE )):'';
            $type_id= !empty($this->input->get('tid', TRUE ))?addslashes($this->input->get('tid', TRUE )):'';
            //查询inter_id 的网址
            if($inter_id && $hotel_id && $shop_id){
                $public = $this->db->get_where ( 'publics', array (
                    'inter_id' => $inter_id
                ) )->row_array ();
                if($public && !empty($public['domain'])){
                    $host = 'http://'.$public['domain'] . '/index.php/';
                    $url= $host . 'ticket/ticket/index?id='.$inter_id.'&hotel_id='.$hotel_id.'&shop_id='.$shop_id.'&type_id='.$type_id;
                    redirect($url);
                }else{
                    echo 'data error';
                    die;
                }
            }else{
                echo 'data error';
                die;
            }
        }

    }

    //房间微服务跳转链接
    public function run_ticket_book(){
        $type = $this->input->get('type', TRUE );
        if(empty($type)){
            echo 'error';
            die;
        }
        if($type=='din'){
            $inter_id = !empty($this->input->get('id', TRUE ))?addslashes($this->input->get('id', TRUE )):'';
            $hotel_id = !empty($this->input->get('hid', TRUE ))?intval($this->input->get('hid', TRUE )):'';
            $shop_id= !empty($this->input->get('sid', TRUE ))?intval($this->input->get('sid', TRUE )):'';
            $type_id= !empty($this->input->get('tid', TRUE ))?addslashes($this->input->get('tid', TRUE )):'';
            //查询inter_id 的网址
            if($inter_id && $hotel_id && $shop_id){
                $public = $this->db->get_where ( 'publics', array (
                    'inter_id' => $inter_id
                ) )->row_array ();
                if($public && !empty($public['domain'])){
                    $host = 'http://'.$public['domain'] . '/index.php/';
                    $url= $host . 'ticket/book/goods_list?id='.$inter_id.'&hotel_id='.$hotel_id.'&shop_id='.$shop_id.'&type_id='.$type_id;
                    redirect($url);
                }else{
                    echo 'data error';
                    die;
                }
            }else{
                echo 'data error';
                die;
            }
        }

    }


    /**
     * 封装curl的调用接口，get的请求方式
     * @param string 请求URL
     * @param array  请求参数值array(key=>value,...)
     * @param second 超时时间
     * @return mixed 请求成功返回成功结构，否则返回FALSE
     */
    private function doCurlGetRequest($url, $data = array(), $timeout = 10){
        if($url == "" || $timeout <= 0){
            return false;
        }
        if($data != array()){
            $url = $url . '?' . http_build_query($data);
        }
        $con = curl_init(( string )$url);
        curl_setopt($con, CURLOPT_HEADER, false);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($con, CURLOPT_TIMEOUT, ( int )$timeout);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);

        $res = curl_exec($con);
        curl_close($con);
        return $res;

    }

    /**
     * 封装curl的调用接口，post的请求方式
     * @param string URL
     * @param string POST表单值
     * @param array  扩展字段值
     * @param second 超时时间
     * @return mixed 请求成功返回成功结构，否则返回FALSE
     */
    private function doCurlPostRequest($url, $requestString, $extra = array(), $timeout = 10){
        if($url == "" || $requestString == "" || $timeout <= 0){
            return false;
        }
        $con = curl_init(( string )$url);
        curl_setopt($con, CURLOPT_HEADER, false);
        curl_setopt($con, CURLOPT_POSTFIELDS, $requestString);
        curl_setopt($con, CURLOPT_POST, true);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($con, CURLOPT_TIMEOUT, ( int )$timeout);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($con, CURLOPT_SSL_VERIFYHOST, 0);

        if(!empty ($extra) && is_array($extra)){
            $headers = array();
            foreach($extra as $opt => $value){
                if(strexists($opt, 'CURLOPT_')){
                    curl_setopt($con, constant($opt), $value);
                } elseif(is_numeric($opt)){
                    curl_setopt($con, $opt, $value);
                } else{
                    $headers [] = "{$opt}: {$value}";
                }
            }
            if(!empty ($headers)){
                curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
            }
        }
        $res = curl_exec($con);
        curl_close($con);
        return $res;
    }


}