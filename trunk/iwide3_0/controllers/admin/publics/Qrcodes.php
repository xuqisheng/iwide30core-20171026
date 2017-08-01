<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 二维码信息管理
 * @author Ounianfeng
 * @since 2016-02-26 17:24
 */
class Qrcodes extends MY_Admin {

	protected $label_module= '二维码信息';
	protected $label_controller= '二维码列表';
	protected $label_action= '';
	private $inter_id;

	function __construct(){
		parent::__construct();
		$user_profiler = $this->session->userdata('admin_profile');
		$this->inter_id = $user_profiler['inter_id'];
	}

    protected function main_model_name()
    {
        return 'wx/qrcode_model';
    }
	public function index(){
		echo '';
	}

	public function qrcode_list() {
		$this->load->model('wx/qrcode_model');
		$this->load->library('pagination');
		$inter_id= $this->session->get_admin_inter_id();
		$config['per_page']          = 20;
		$page = empty($this->uri->segment(4)) ? 0 : ($this->uri->segment(4) - 1) * $config['per_page'];
		$config['use_page_numbers']  = TRUE;
		$config['cur_page']          = $page;
		$config['uri_segment']       = 4;
		$config['numbers_link_vars'] = array('class'=>'number');
		$config['cur_tag_open']      = '<a class="number current" href="#">';
		$config['cur_tag_close']     = '</a>';
		$config['base_url']          = site_url("publics/qrcodes/qrcode_list");
		$config['total_rows']        = $this->qrcode_model->get_qrcodes_list($inter_id)->num_rows();
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
		$query      = $this->qrcode_model->get_qrcodes_list($inter_id,$config['per_page'],$page);
		$this->load->model ( 'hotel/hotel_model' );
		$hotels = $this->hotel_model->get_hotel_hash ( array('inter_id'=>$inter_id) );
		$view_params= array(
				'pagination' => $this->pagination->create_links(),
				'hotels'     => $hotels,
				'res'        => $query->result()
		);
		$html= $this->_render_content($this->_load_view_file('grid'), $view_params, TRUE);
		echo $html;
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
        
//         $company_info=$this->hotel_model->get_all_hotels ( $this->session->get_admin_inter_id(),null,'key' );

//        $file="iwide30dev/www_admin/admin.xlsx";
// /var/www/iwide30dev/www_admin\../www_admin/2016033018313447.xlsx

		if ($file != 'error') {
// 			$file = $_SERVER ['DOCUMENT_ROOT'] . '\\' . $file;


			$this->load->model ( 'wx/qrcode_model' );
			$max_id = $this->qrcode_model->get_max_id ($this->session->get_admin_inter_id());
			//强制刷新access_token
			$this->load->model ( 'wx/access_token_model' );
			$this->access_token_model->reflash_access_token ( $this->session->get_admin_inter_id() );

            if (! $max_id )
		    $max_id = 0;

            $this->load->model('plugins/Excel_model','excel_model');


            $data=$this->excel_model->load_exl($file);
            $sheet = $data->getSheet(0);
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            $highestColumm = $sheet->getHighestColumn(); // 取得总列数


            if($highestRow>200||$highestRow<=1){

                echo "limited";
                unlink ( $file );

            }else{

                $new_data =  array ();
                $hotel_staff=array();

                for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始
//                     for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
//                         $dataset[] = $sheet->getCell($column.$row)->getValue();
    //                    echo $column.$row.":".$sheet->getCell($column.$row)->getValue()."<br />";
//                     }


                    if(!empty($company_info[$sheet->getCell('A'.$row)->getValue()])&&!empty($sheet->getCell('B'.$row)->getValue())){
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


                    $max_id ++;
                    $new_data[$row]['url']= $this->qrcode_model->get_qr_code ( $max_id,$this->session->get_admin_inter_id() );
                    $new_data[$row]['name']= $company_info[$sheet->getCell('A'.$row)->getValue()]['name'];
                    $new_data[$row]['intro']= $sheet->getCell('B'.$row)->getValue();
                    $new_data[$row]['keyword']= $sheet->getCell('C'.$row)->getValue();
                    $new_data[$row]['inter_id']= $this->session->get_admin_inter_id();
                    $new_data[$row]['create_date']= date('Y-m-d H:i:s');
                    $new_data[$row]['id']= $max_id;

                    $hotel_staff[$row]['qrcode_id']= $max_id;
                    $hotel_staff[$row]['name']           = $sheet->getCell('B'.$row)->getValue();
                    $hotel_staff[$row]['hotel_name']     = $company_info[$sheet->getCell('A'.$row)->getValue()]['name'];
                    $hotel_staff[$row]['hotel_id']       = $sheet->getCell('A'.$row)->getValue();
                    $hotel_staff[$row]['inter_id']       = $this->session->get_admin_inter_id();
                    $hotel_staff[$row]['status']       = 2;
                    $hotel_staff[$row]['second_dept']       = '场景区域';
                    $hotel_staff[$row]['status_time']       = date('Y-m-d H:i:s');


                }


    //			if (! $max_id ['id'])
    //				$max_id ['id'] = 0;
    //				$this->load->library ( 'Spreadsheet_Excel_Reader' );
    //				$data = new Spreadsheet_Excel_Reader ();
    //				$data->setOutputEncoding ( 'utf-8' );
    //				$data->read ( $file );
    //				$max_id = $max_id ['id'] + 1;
    //				$datas = $staffs = array ();
    //				for($i = 2; $i <= $data->sheets [0] ['numRows']; $i ++) {
    //					$temp ['id']             = $max_id;
    //					$temp ['keyword']        = $data->sheets [0] ['cells'] [$i] [1];
    //					$temp ['intro']          = $data->sheets [0] ['cells'] [$i] [2];
    //					$temp ['name']           = $data->sheets [0] ['cells'] [$i] [3];
    //					$temp ['url']            = $this->qrcode_model->get_qr_code ( $max_id );
    //					$temp ['create_date']    = date('Y-m-d H:i:s');
    //					$temp ['inter_id']       = $this->session->get_admin_inter_id();
    //					$staff['name']           = $data->sheets [0] ['cells'] [$i] [2];
    //					$staff['hotel_name']     = $data->sheets [0] ['cells'] [$i] [3];
    //					$staff['hotel_id']       = $data->sheets [0] ['cells'] [$i] [4];
    //					$staff['inter_id']       = $this->session->get_admin_inter_id();
    //					$staff['status']         = 2;
    //					$staff['qrcode_id']      = $max_id;
    //					$staff['is_distributed'] = 0;
    //					$staff['status_time']    = date('Y-m-d H:i:s');
    //					array_push ( $datas, $temp );
    //					array_push ( $staffs, $staff );
    //					$max_id ++;
    //				}

                    $this->qrcode_model->create_batch ( $new_data );
                    $this->qrcode_model->create_batch_staff ( $hotel_staff );

                    if (file_exists ( $file )) {
                        unlink ( $file );
                    }
                    echo 'success';
                }

		} else {
			echo 'file error';
		}
	}
	public function save_edit(){
		$this->load->model('wx/qrcode_model');
		if($this->qrcode_model->save_edit($this->session->get_admin_inter_id())){
			echo json_encode(array('errmsg'=>'ok'));
		}else{
			echo json_encode(array('errmsg'=>'fail'));
		}
	}
	/**
	 * 二维码详情JSON
	 */
	public function get_qrcode_json(){
		$id = $this->input->get('qid',true);
		if(!empty($id)){
			$this->load->model('wx/qrcode_model');
			$qr_details = $this->qrcode_model->get_detail($id,$this->session->get_admin_inter_id())->row_array();
			echo json_encode(array('errmsg'=>'ok','item'=>$qr_details));
		}else{
			echo json_encode(array('errmsg'=>'参数错误'));
		}
		
	}


    public function output_qrcode(){


//         $this->load->library ( 'Zipfile' );
        $inter_id= $this->session->get_admin_inter_id();

        $this->load->model('plugins/ZipImage_model','Zipimage_model');

        $this->Zipimage_model->upload_zip($inter_id);




    }

}