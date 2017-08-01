<?php
class MY_Admin_Roomservice extends MY_Admin {
    public $db_shard_config= array();
    public $current_inter_id= '';
    public $view_file= '';

    public $inter_id = '';
    public $hotel_id = '';
    public $canteen_shop_id = '';

    public function __construct(){
        parent::__construct();
        $admin_profile = $this->session->userdata ( 'admin_profile' );
        $this->inter_id = $admin_profile['inter_id'];
        if($this->inter_id == FULL_ACCESS){

        }else{

        }
        if($admin_profile['entity_id']){
            $this->hotel_id = explode(',',$admin_profile['entity_id']);
        }
        if(!empty($admin_profile['canteen_shop_id'])){
            $this->canteen_shop_id = explode(',',$admin_profile['canteen_shop_id']);
        }
    }

    public function _grid($filter= array(), $viewdata=array()) {
        //print_r($filter);die;
        $model_name= $this->main_model_name();
        $model= $this->_load_model($model_name);

        //filter params: the same with table fields...
        //sort params: sort_direct, sort_field
        //page params: page_size, page_num
        $params= $this->input->get();
        if(is_array($filter) && count($filter)>0 )
            $params= array_merge($params, $filter);

        if(is_ajax_request()){
            //处理ajax请求
            $result= $model->filter_json($params );
            echo json_encode($result);

        } else {
            //HTML输出
            if( !$this->label_action ) $this->label_action= '信息列表';
            $this->_init_breadcrumb($this->label_action);

            //base grid data..
            $result= $model->filter($params);
            $fields_config= $model->get_field_config('grid');
            $default_sort= $model::default_sort_field();
            //print_r($fields_config);die;

            $view_params= array(
                'module'=> $this->module,
                'model'=> $model,
                'result'=> $result,
                'fields_config'=> $fields_config,
                'default_sort'=> $default_sort,
            );

            $view_params= $view_params+ $viewdata;

            $view_file= $this->view_file? $this->view_file: 'grid';
            $html= $this->_render_content($this->_load_view_file($view_file), $view_params, TRUE);
            //echo $html;die;
            echo $html;
        }
    }


    //分页
    protected function pagination($per_page = 50,$cur_page = 1,$base_url = '',$total_rows = 10,$uri_segment = 5,$first_url = '',$search_url = '',$suffix = ''){
        $this->load->library('pagination');
        $config = array();
        if($first_url){
            $config ['first_url'] = $first_url;
        }
        if($suffix){
            $config ['suffix'] = $search_url;
        }
        $config['per_page']          = $per_page;
        $config['use_page_numbers']  = TRUE;
        $config['cur_page']          = $cur_page;
        $config['uri_segment']       = $uri_segment;
        $config['numbers_link_vars'] = array('class'=>'number');
        $config['cur_tag_open']      = '<a class="number current" href="#">';
        $config['cur_tag_close']     = '</a>';
        $config['base_url']          = $base_url;
        $config['total_rows']        = $total_rows;
        $config['cur_tag_open'] = '<li class="paginate_button active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="paginate_button">';
        $config['num_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="paginate_button first">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="paginate_button last">';
        $config['last_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="paginate_button previous">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="paginate_button next">';
        $config['next_tag_close'] = '</li>';
        $this->pagination->initialize($config);
    }

    /**
     * @author libinyan@mofly.cn
     * @param  [type]  $content  [二维码内容]
     * @param  boolean $filename [生成图片名，文件名空则直接显示图片，不保存文件]
     * @param  integer $size     [图片大小]
     * @param  integer $margin   [白边举例]
     * @return [type]
     */
    public function _get_qrcode_png($content, $filename=FALSE, $size=5, $margin=1, $base_path=FALSE )
    {
        $this->load->helper ( 'phpqrcode' );
        if( $filename===FALSE ){
            QRcode::png($content, FALSE, 'Q', $size, $margin, TRUE );
            return TRUE;

        } else {
            if( $base_path==FALSE )
                $base_path= 'qrcode'. '/'. $this->module. '/'. $this->controller. '/'. $this->action;
            $path= FCPATH. FD_PUBLIC. '/'. $base_path;
            //echo $path;die;
            if( !file_exists($path) ) @mkdir($path, 755, TRUE);
            $file= $path. '/'. $filename. '.png';
            //echo $file;die;
            QRcode::png($content, $file, 'Q', $size, $margin );

//ftp开始，初始化测试服务器ftp
            if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
                $this->ftp= $this->_ftp_server('prod');
            } else {
                $this->ftp= $this->_ftp_server('test');
            }
//$to_file = '/public_html'. $file_system_path;
            $to_file = $this->ftp->floder. FD_PUBLIC. '/'. $base_path. '/';
//echo $to_file;die;  //   /public_html/public/qrcode/mall/test/qr/
            $isdir = $this->ftp->list_files($to_file);
            if (empty($isdir)) {
                $newpath = '/';
                $arrpath = explode('/', $to_file);
                foreach ($arrpath as $v) {
                    if ($v && $v!= $this->ftp->floder ) {
                        $newpath .= $v. '/';
                        $isdirchild = $this->ftp->list_files($newpath);
                        if (empty($isdirchild)) {
                            $this->ftp->mkdir($newpath);
                        }
                    }
                }
            }
            $upload_name= $filename. '.png';
            $to_file= str_replace(array('\\','//'), array('/','/'), $to_file. '/'. $upload_name);
            if( !file_exists($file) ) echo '原上传文件不存在！';
            else $result= $this->ftp->upload($file, $to_file, 'binary', 0775);
            $this->ftp->close();
//ftp结束

//@unlink($file);   //二维码留底，不删除
            if($result){
                $upload_url= $this->ftp->weburl. '/'. FD_PUBLIC. '/'. $base_path.'/'. $upload_name;

            }else{
                $upload_url= '';

            }
            return $upload_url;
        }
    }

    /**
     * 导出订单
     * @param array $filter
     * @param int $per_page
     * @param int $page
     */
    public function ext_orders_report($filter = array(),$per_page = 30,$page = 1)
    {
        if(empty($filter))
        {
            echo 'data error!';
            die;
        }

        $this->load->model('roomservice/roomservice_orders_model');
        $this->load->model('roomservice/roomservice_shop_model');

        $confs = $this->roomservice_orders_model->grid_fields();  //需要列
        if ($filter['type'] != 4)
        {
            unset($confs['dissipate']);
        }
        else if ($filter['type'] != 3)
        {
            unset($confs['dada_status']);
        }
        $data = "";
        foreach ($confs as $key=>$item)
        {
            $data = $data.iconv('utf-8','gb2312',$item).",";
        }
        log_message("error","filter：".json_encode($confs));
        log_message("error","filter：".$data);
        $result = $this->roomservice_orders_model->get_list($filter,0,0);
        log_message("error","filter：".json_encode($filter));
        log_message("error","orders_ext：".json_encode($result));
        $data = $data."\n";
        $order_status = array(0=>'未确认',5=>'已确认',10=>'配送中',20=>'已完成',25=>'个人取消',26=>'酒店取消',27=>'系统取消');
        $pay_status = array(1=>'已支付',3=>'未支付',4=>'已退款');
        $pay_way = array(1=>'微信',2=>'储值',3=>'线下',4=>'威富通');
        $type = array(1=>'房间号',2=>'桌号',3=>'姓名',4=>'姓名');
        $dada_status = array('--', '待接单','待到店','配送中','已完成','已取消','预留','已过期','申请取消','指派单');

        foreach ($result as $item)
        {
            foreach ($confs as $key=>$val)
            {
                if($key == "add_time" || $key == "dissipate" || $key == "pay_time")
                {
                    $time = empty($item[$key]) ? '' : $item[$key];
                    $data = $data.iconv('utf-8','gb2312',$time." ").",";
                }
                else
                {
                    if ($key == 'shop_id')
                    {
                        $shop_info = $this->roomservice_shop_model->get(array('shop_id'=>$item[$key]));
                        $data = $data.iconv('utf-8','gb2312',$shop_info['shop_name']).",";
                    }
                    else if($key == 'pay_status')
                    {
                        $pay_st = isset($pay_status[$item[$key]]) ? $pay_status[$item[$key]] : '';
                        $data = $data.iconv('utf-8','gb2312',$pay_st).",";
                    }
                    else if($key == 'pay_way')
                    {
                        $pay_w = isset($pay_way[$item[$key]]) ? $pay_way[$item[$key]] : '';
                        $data = $data.iconv('utf-8','gb2312',$pay_w).",";
                    }
                    else if($key == 'order_status')
                    {
                        $order_st = isset($order_status[$item[$key]]) ? $order_status[$item[$key]] : '';
                        $data = $data.iconv('utf-8','gb2312',$order_st).",";
                    }
                    else if($key == 'dada_status')
                    {
                        $dada_st = isset($dada_status[$item[$key]]) ? $dada_status[$item[$key]] : '';
                        $data = $data.iconv('utf-8','gb2312',$dada_st).",";
                    }
                    else if($key == 'consignee')
                    {
                        //$consignee = isset($type[$item['type']]) ? $type[$item['type']] : '';
                        $consignee = $item[$key];
                        $data = $data.iconv('utf-8','gb2312',$consignee).",";
                    }
                    else
                    {
                        $data = $data.iconv('utf-8','gb2312',$item[$key]).",";
                    }
                }
            }

            $data = $data."\n";
        }
        log_message("error","orders_ext：".json_encode($data));
        // 发送标题强制用户下载文件
        header ('Content-Type: text/csv' );
        header ('Content-Disposition: attachment;filename="' . date ( 'YmdHis' ) . '.csv"' );
        header ('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $data;
    }
}