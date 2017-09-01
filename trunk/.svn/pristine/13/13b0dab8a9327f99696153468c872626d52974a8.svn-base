<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	后台上传文件
*	@author knight
*	@time 2016-09-30
*	@version 1.0
*	@
*/
class Flashupload extends MY_Admin_Api
{
	//配置列表
	public function index(){
        $view_params = array();
        $CI =& get_instance();
        $hidden[$CI->security->get_csrf_token_name()] = $CI->security->get_csrf_hash();
        if (is_array($hidden))
        {
            foreach ($hidden as $name => $value)
            {
                $view_params[$name] = html_escape($value);
            }
        }
        $args = (isset($_REQUEST['args']) && !empty($_REQUEST['args']))?$_REQUEST['args']:'depositcard,1,20240,jpg|jpeg|gif|png';
        $args = explode(',', $args);
        $view_params['filemodel'] = (isset($args[0]) && !empty($args[0]))?$args[0]:'front';
        $view_params['file_dir'] = (isset($args[1]) && !empty($args[1]))?$args[1]:'depositcard';
        $view_params['filetype_post'] = (isset($args[4]) && !empty($args[4]))?$args[4]:'jpg|jpeg|gif|png';
        $view_params['filetype_title'] = str_replace('|', ',', $view_params['filetype_post']);
        $view_params['file_size_limit'] = (isset($args[3]) && !empty($args[3]))?$args[3]:'20240';
        $view_params['file_upload_limit'] = (isset($args[2]) && !empty($args[2]))?$args[2]:'1';
        $filetype_post = explode('|', $view_params['filetype_post']);
        $view_params['file_types'] = '';
        foreach ($filetype_post as $item){
            $view_params['file_types'] .= '*.'.$item.';';
        }
        $view_params['filedata'] = (isset($_GET['filedata']) && !empty($_GET['filedata']))?$_GET['filedata']:'logo_url';
        /*echo '<pre>';
        var_dump($view_params);exit;*/
        $this->_render_content($this->_load_view_file('index'),$view_params,false);
	}

	//上传操作
	public function swfupload(){
        $data['inter_id'] = $this->session->get_admin_inter_id();
        $file_dir = $_POST['file_dir'];
        $filedata = $_POST['filedata'];
        $filemodel = $_POST['filemodel'];
        $imgurl = $this->_do_upload($data, $filedata,$filemodel, $file_dir);
        log_message('ERROR', json_encode($imgurl));
        if(isset($imgurl[$filedata])){
            $data[$filedata] = $imgurl[$filedata];
            // 对上传文件数组信息处理
            $files   =  $this->dealFiles($_FILES);
            foreach ($files as $key => $file){
                $name  = strip_tags($file['name']);
                echo "1," . $data[$filedata].",".'1,'.$name;
            }
            exit;
        }
        //上传失败，返回错误
        exit("0," . '上传错误');
    }

    /**
     * 转换上传文件数组变量为正确的方式
     * @access private
     * @param array $files  上传的文件变量
     * @return array
     */
    private function dealFiles($files) {
        $fileArray  = array();
        $n          = 0;
        foreach ($files as $key=>$file){
            if(is_array($file['name'])) {
                $keys       =   array_keys($file);
                $count      =   count($file['name']);
                for ($i=0; $i<$count; $i++) {
                    $fileArray[$n]['key'] = $key;
                    foreach ($keys as $_key){
                        $fileArray[$n][$_key] = $file[$_key][$i];
                    }
                    $n++;
                }
            }else{
                $fileArray = $files;
                break;
            }
        }
        return $fileArray;
    }
}
?>