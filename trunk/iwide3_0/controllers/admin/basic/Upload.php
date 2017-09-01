<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends MY_Admin {

	protected $label_module= NAV_BASIC;		//统一在 constants.php 定义
	protected $label_controller= '上传管理';	//在文件定义
	protected $label_action= '';				//在方法中定义


	/**
	 * 输出js调用 
	 * @example $this->_feedback($fn, "", "文件上传失败，请检查上传目录设置和目录读写权限"); 
	 * @param  [type] $fn      [description]
	 * @param  [type] $fileurl [description]
	 * @param  [type] $message [description]
	 * @return [type]          [description]
	 */
	public function _feedback($fn, $fileurl, $message) 
	{ 
		$str='<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$fn.', \''.$fileurl.'\', \''.$message.'\');</script>'; 
		exit($str); 
	}

    /*
     * 上传处理函数，初始化等在父类实现，配置信息可以传入，覆盖
     */
    protected function _do_upload($post, $fieldname, $area = 'file', $path = NULL)
    {
        if (isset($_FILES[$fieldname]['size']) && $_FILES[$fieldname]['size'] > 0) {
            $upload_ext = substr(strrchr($_FILES[$fieldname]['name'], '.'), 1);

            $base_path = 'media/' . $path . '/';
            //echo $base_path;die;   //media/a449493496/mall/goods/gs_detail/
            $upload_name = date('YmdHis') . '.' . $upload_ext;

            $upload_path = FD_ . $base_path;
            //echo $upload_path;die;  //...htdocs\www_admin/public\media/a449493496/mall/goods/gs_detail/

            $this->_init_upload_config(array(
                'upload_path' => $upload_path,
                'file_name' => $upload_name,
            ));
            if (!$this->upload->do_upload($fieldname)) {
                $error = $this->upload->display_errors();
                $this->_feedback(1, "", $error);

            } else {

//ftp开始，初始化测试服务器ftp
//$this->ftp= $this->_ftp_server('test');
                $this->ftp = $this->_ftp_server('prod');
//$to_file = '/public_html'. $file_system_path;
                $to_file = $this->ftp->floder . FD_PUBLIC . '/' . $base_path;
//echo $to_file;die;  //public/media/a449493496/mall/goods/gs_detail/
                $isdir = $this->ftp->list_files($to_file);
                if (empty($isdir)) {
                    $newpath = '/';
                    $arrpath = explode('/', $to_file);
                    foreach ($arrpath as $v) {
                        if ($v != '') {
                            $newpath = $newpath . $v . '/';
                            $isdirchild = $this->ftp->list_files($newpath);
                            if (empty($isdirchild)) {
                                $this->ftp->mkdir($newpath);
                            }
                        }
                    }
                }
                $this->ftp->upload($upload_path . $upload_name, $to_file . $upload_name, 'binary', 0775);
                $this->ftp->close();
//ftp结束

                @unlink($upload_path . $upload_name);
                $upload_url = $this->ftp->weburl . '/' . FD_PUBLIC . '/' . $base_path . $upload_name;

                //保存上传完之后的URL
                $post[$fieldname] = $upload_url;
                return $post;
            }
        }
    }
	
	public function _ftp_test()
	{
	    //ftp开始
	    $this->load->library('ftp');
	    $configftp['hostname'] = config_item('ftp_hostname');
	    $configftp['username'] = config_item('ftp_username');
	    $configftp['password'] = config_item('ftp_password');
	    $configftp['port']     = config_item('ftp_port');
	    $configftp['passive']  = config_item('ftp_passive');
	    $configftp['debug']    = config_item('ftp_debug');
	    $result= $this->ftp->connect($configftp);
//var_dump($this->ftp);die;
//var_dump($result);die;

	    $to_file = '/test2/';
	    $isdir = $this->ftp->list_files($to_file);
//var_dump($isdir);die;
	    
	    if (empty($isdir)) {
	        $newpath = '/';
	        $arrpath = explode('/', $to_file);
	        foreach ($arrpath as $v) {
	            if ($v!= '') {
	                $newpath = $newpath. $v. '/';
	                $isdirchild = $this->ftp->list_files($newpath);
	                if (empty($isdirchild)) {
	                    $this->ftp->mkdir($newpath);
	                }
	            }
	        }
	    }
	    $upload_path= 'F:\lby\Pictures\4e4a20a4462309f764f1dcd2760e0cf3d6cad650.jpg';
	    $this->ftp->upload($upload_path, $to_file. '4.jpg', 'binary', 0775);
	    $this->ftp->close();
	    //ftp结束
	    @unlink($upload_path);
	}

	/**
	 * 素材统一展示
	 * @example ../index.php/basic/upload/browse?t=images&p=a23523967|mall|goods|gs_detail&token=35HxSsg6s8g6&CKEditor=el_gs_detail&CKEditorFuncNum=1&langCode=zh-cn
	 * @return [type] [description]
	 */
	public function browse()
	{
		echo '文件列表';
	}

	/**
	 * 商城模块上传处理
	 * @example ../index.php/basic/upload/do_upload?t=images&p=a23523967|mall|goods|gs_detail&token=35HxSsg6s8g6&CKEditor=el_gs_detail&CKEditorFuncNum=1&langCode=zh-cn
	 *   t:上传类型；image|file|flash
	 *   p:上传路径：分别为公众号/资源名称（同时为控制器）/字段名称，基准路径定位在 public/media/ 下
	 *   token:校验token
	 *   后面参数为ckeditor自动追加
	 * @return [type] [description]
	 */
	public function do_upload()
	{
		if( empty($_GET['CKEditorFuncNum']) )
			$this->_feedback(1, "", "错误的功能调用请求");

		$fn= $_GET['CKEditorFuncNum'];

		$post= $this->input->get();
		//TODO：校验token的正确性。
		
		if( isset($post['token']) ){
			$type= isset($post['t'])? $post['t']: 'images';
			switch($post['t']){
				case 'file':
				case 'images':
				default:
					$fieldname= 'upload';
					$post= $this->_do_upload($post, $fieldname, NULL, str_replace('|', '/', $post['p']) );
					//die('上传成功');
					$this->_feedback($fn, $post[$fieldname], "上传成功");
					break;
			}

		} else {
			//die('校验失败');
			 $this->_feedback($fn, "", "TOKEN校验失败"); 
		}

		//TODO：把上传文件插入数据库

	}

    /****kindeditor upload **/

    public function kind_do_upload()
    {

        require_once 'JSON.php';

        $post= $this->input->get();
        //TODO：校验token的正确性。

        if( isset($post['token']) ){
            $type= isset($post['t'])? $post['t']: 'images';
            switch($post['t']){
                case 'file':
                case 'images':
                    $fieldname= 'imgFile';
                    $post= $this->kind_do_upload_process($post, $fieldname, NULL, str_replace('|', '/', $post['p']) );
                    echo json_encode(array('error' => 0, 'url' => $post[$fieldname]));
    //                    var_dump($post);exit;
                    //die('上传成功');
    //                    $this->alert("上传成功。");
                    break;
                default:
                    $fieldname= 'imgFile';
                    $post= $this->kind_do_upload_process($post, $fieldname, NULL, str_replace('|', '/', $post['p']) );
                    echo json_encode(array('error' => 0, 'url' => $post[$fieldname]));
//                    var_dump($post);exit;
                    //die('上传成功');
//                    $this->alert("上传成功。");
                    break;
            }

        } else {
            //die('校验失败');
        }

        //TODO：把上传文件插入数据库

    }

    /*
     * 上传处理函数，初始化等在父类实现，配置信息可以传入，覆盖
     */
    protected function kind_do_upload_process($post, $fieldname, $area = 'file', $path = NULL)
    {
        if (isset($_FILES[$fieldname]['size']) && $_FILES[$fieldname]['size'] > 0) {
            $upload_ext = substr(strrchr($_FILES[$fieldname]['name'], '.'), 1);

            $base_path = 'media/' . $path . '/';
            //echo $base_path;die;   //media/a449493496/mall/goods/gs_detail/
            $upload_name = date('YmdHis').rand(1000,9999) . '.' . $upload_ext;

            $upload_path = FD_ . $base_path;
            //echo $upload_path;die;  //...htdocs\www_admin/public\media/a449493496/mall/goods/gs_detail/

            $this->_init_upload_config(array(
                'upload_path' => $upload_path,
                'file_name' => $upload_name,
            ));
            if (!$this->upload->do_upload($fieldname)) {
                $error = $this->upload->display_errors();
                $this->alert($error);
            } else {

//ftp开始，初始化测试服务器ftp
//$this->ftp= $this->_ftp_server('test');
                $this->ftp = $this->_ftp_server('prod');
//$to_file = '/public_html'. $file_system_path;
                $to_file = $this->ftp->floder . FD_PUBLIC . '/' . $base_path;
//echo $to_file;die;  //public/media/a449493496/mall/goods/gs_detail/
                $isdir = $this->ftp->list_files($to_file);
                if (empty($isdir)) {
                    $newpath = '/';
                    $arrpath = explode('/', $to_file);
                    foreach ($arrpath as $v) {
                        if ($v != '') {
                            $newpath = $newpath . $v . '/';
                            $isdirchild = $this->ftp->list_files($newpath);
                            if (empty($isdirchild)) {
                                $this->ftp->mkdir($newpath);
                            }
                        }
                    }
                }
                $this->ftp->upload($upload_path . $upload_name, $to_file . $upload_name, 'binary', 0775);
                $this->ftp->close();
//ftp结束

                @unlink($upload_path . $upload_name);
                $upload_url = $this->ftp->weburl . '/' . FD_PUBLIC . '/' . $base_path . $upload_name;

                //保存上传完之后的URL
                $post[$fieldname] = $upload_url;
                return $post;
            }
        }
    }

    function alert($msg) {
        header('Content-type: text/html; charset=UTF-8');
        $json = new Services_JSON();
        echo $json->encode(array('error' => 1, 'message' => $msg));
        exit;
    }

    /****kindeditor upload **/

}
