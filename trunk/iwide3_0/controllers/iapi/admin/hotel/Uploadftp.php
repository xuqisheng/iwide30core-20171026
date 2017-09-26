<?php

use App\libraries\Iapi\BaseConst;

defined('BASEPATH') or exit ('No direct script access allowed');

class Uploadftp extends MY_Admin_Iapi
{
    protected $label_module = NAV_HOTEL;
    protected $label_controller = '';
    protected $label_action = '';

    function __construct()
    {
        parent::__construct();
        $this->inter_id = $this->session->get_admin_inter_id();
        $this->common_data ['csrf_token'] = $this->security->get_csrf_token_name();
        $this->common_data ['csrf_value'] = $this->security->get_csrf_hash();
        $this->sysconfig = $this->config->config;
    }

    /**
     * 上传图片接口
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function upload_img() {

        if (isset($_FILES['imgFile'])) {
            if ($_FILES['imgFile']['type']=="application/octet-stream") {
                $file_ext = $this->get_ext($_FILES['imgFile']['name']);
                $type = $this->get_mines($file_ext);
                $_FILES['imgFile']['type'] = $type;
            }
        }

        $file_system_path = '/public/uploads/' .date("Ym"). '/';

        $config['upload_path']      = './public/base/tmp/';
        $config['allowed_types']    = 'jpg|jpeg|png|bmp';
        $config['max_size']     = 2048;
        $config['max_width']        = 1024;
        $config['max_height']       = 768;

        $config['file_name'] = 'qf'.date("dHis").rand(1000, 9999);

        $this->load->library('upload', $config);

        if ( !$this->upload->do_upload('file') )
        {
            $error = array('error' => strip_tags($this->upload->display_errors()));
            $this->out_put_msg(BaseConst::OPER_STATUS_FAIL_TOAST, '上传失败', $error);
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            ini_set('memory_limit', '1280M');

            $recof['image_library'] = 'gd2';
            $recof['source_image'] = $data['upload_data']['full_path'];
            //$recof['create_thumb'] = TRUE;
            $recof['maintain_ratio'] = TRUE;

            $image_width1 = $data['upload_data']['image_width'];
            $image_height1 = $data['upload_data']['image_height'];
            if ($image_width1>=$image_height1) {
                if ($image_width1>1024) {
                    $recof['width']  = 1024;
                }
            }
            /*
            else {
            	if ($image_height1>768) {
            		$recof['height']  = 768;
            	}
            }*/
            $this->load->library('image_lib', $recof);
            $this->image_lib->resize();

            //ftp开始
            $this->load->library('ftp');
            $configftp['hostname'] = $this->sysconfig['ftphostname'];
            $configftp['username'] = $this->sysconfig['ftpusername'];
            $configftp['password'] = $this->sysconfig['ftppassword'];
            $configftp['port']     = $this->sysconfig['ftpport'];
            $configftp['passive']  = $this->sysconfig['ftppassive'];
            $configftp['debug']    = $this->sysconfig['ftpdebug'];

            $this->ftp->connect($configftp);

            $toftppath = '/public_html'.$file_system_path;
            $isdir = $this->ftp->list_files($toftppath);

            if (empty($isdir)) {
                $newpath = '/';$arrpath = explode('/', $toftppath);
                foreach ($arrpath as $v) {
                    if ($v!='') {
                        $newpath = $newpath.$v.'/';
                        $isdirchild = $this->ftp->list_files($newpath);
                        if (empty($isdirchild)) {
                            $this->ftp->mkdir($newpath);
                        }
                    }
                }
            }

            $this->ftp->upload($data['upload_data']['full_path'], $toftppath.$data['upload_data']['file_name'], 'binary', 0775);
            $this->ftp->close();
            //ftp结束

            //
            if (empty($this->session->userdata['admin_profile']['username'])) {
                $this->out_put_msg(BaseConst::OPER_STATUS_NOTLOGIN, '未登录');
            }
//            $username = empty($this->session->userdata['admin_profile']['username']) ? 'demo':$this->session->userdata['admin_profile']['username'];
            $username = $this->session->userdata['admin_profile']['username'];
            $inter_id = $this->inter_id;

            $in['username'] = $username;
            $in['inter_id'] = $inter_id;
            $in['dir'] = date("Ym");
            $in['addtime'] = time();

            $in['filesize'] = $data['upload_data']['file_size'];
            $in['filetype'] = $data['upload_data']['image_type'];
            $in['filename'] = $data['upload_data']['client_name'];

            $file_domain=empty($this->sysconfig['ftp_cdn_url'])?$this->sysconfig['ftpurl']:$this->sysconfig['ftp_cdn_url'];
            $in['src'] = $file_domain.$file_system_path.$data['upload_data']['file_name'];

            $this->db->insert('upload',$in);

            $file_url = $in['src'];
//            echo json_encode(array('error' => 0, 'url' => $file_url));
            $this->out_put_msg(BaseConst::OPER_STATUS_SUCCESS, '', ['path' => $file_url]);

//            @unlink($config['upload_path'].$config['file_name'].$data['upload_data']['file_ext']);
//            exit;
        }

    }

}
