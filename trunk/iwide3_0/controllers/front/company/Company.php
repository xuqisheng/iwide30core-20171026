<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Company extends MY_Front {
	public $common_data = array();
	public $openid;

    public function index()  //扫码登记资料
    {

        $model=$this->load->model('company/Company_price_model','Company_price_model');

        $model=$this->Company_price_model;
        if(!empty($_GET['cid'])){
            $cp_id=$_GET['cid'];
        }else{
            exit;
        }

        $company_id = $cp_id;

        if($company_id==false){
            echo "<script>alert('不存在该公司')</script>";exit;
        }

        $result=$model->getCompanyInfoById($company_id);
        $openid=$this->openid;

        $check_data=array(
            'inter_id'=>$result->inter_id,
            'openid'=>$openid,
            'company_id'=>$company_id
        );

        $check_openid = $model->check_register($check_data);

        $company_name = $result->company_name;

        if(empty($check_openid)){

            $data = $this->common_data;

            $data['company_name']=$company_name;
            $data['cid']=$company_id;

    //        $data['phone'] = '11';

            $this->display( 'company/price', $data );

        }else{

            $data = $this->common_data;

            $inter_id=$this->session->userdata ( 'inter_id' );

            $is_multy=$model->interIdMulty($inter_id);

            $data['is_multy']=$is_multy;

            $data['inter_id']=$inter_id;

            $data['company_name']=$company_name;

            $this->display( 'company/bing_status', $data );
        }
    }


    public function input_code()    //会员中心进入登记资料
    {
        $model=$this->load->model('company/Company_price_model','Company_price_model');

        $model=$this->Company_price_model;


        $data = $this->common_data;

        $openid=$this->openid;
        $inter_id=$this->inter_id;

        $check_data=array(
            'inter_id'=>$inter_id,
            'openid'=>$openid,
        );

        $check_openid = $model->checkRegisterByOpenid($check_data);

        if($check_openid){

            $result=$model->getCompanyInfoById($check_openid->company_id);

            $company_name = $result->company_name;

            $data['company_name']=$company_name;

            $is_multy=$model->interIdMulty($inter_id);

            $data['is_multy']=$is_multy;

            $data['inter_id']=$inter_id;

            $this->display( 'company/bing_status', $data );

        }else{

            $this->display( 'company/bind', $data );

        }


    }

    public function registered()   //登记成功结束页
    {

        $model=$this->load->model('company/Company_price_model','Company_price_model');

        $this->display( 'company/bing_status', '' );
    }



	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->userdata ( 'inter_id' );
		$this->openid = $this->session->userdata ( $this->inter_id . 'openid' );
		MYLOG::hotel_tracker($this->openid,  $this->inter_id);
		$this->load->model ( 'wx/Publics_model' );
		$this->load->model ( 'wx/Access_token_model' );
		$this->public = $this->Publics_model->get_public_by_id ( $this->inter_id );
		$this->common_data ['signPackage'] = $this->Access_token_model->getSignPackage ( $this->inter_id );
		$this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
		$this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();
	}

    public function main_model_name()
    {
        return 'company/Company_model';
    }

    function company_register(){     //职员登记


        $this->load->model('company/Company_price_model','Company_price_model');

        $model=$this->Company_price_model;

        $openid=$this->openid;
        $inter_id=$this->inter_id;

        if(!empty($_GET['cid']))$company_id=$_GET['cid'];
		else exit(0);




        $post_data=array(
            'openid'=>$openid,
            'inter_id'=>$inter_id,
            'company_id'=>$company_id,
        );

        $staff_info=$model-> getStaffInfoByCp($post_data);

//
            if($_POST['name'])$name=$_POST['name'];
            if($_POST['tel'])$tel=$_POST['tel'];


            $data=array(
                'openid'=>$openid,
                'company_id'=>$company_id,
                'inter_id'=>$inter_id,
                'name'=>$name,
                'tel'=>$tel,
                'update_time'=>'',
                'apply_time'=>date ( 'Y-m-d H:i:s' ),
                'status'=>1
            );

//        $check=$model->check_register($data);

        if(empty($staff_info)){

            $this->Company_price_model->m_save($data,false);

            $status=1;

        }else{

            $status=0;

        }

        echo json_encode($status);

    }


    public function check_company(){   //输入协议码后登记

        $model=$this->load->model('company/Company_price_model','Company_price_model');

        $model=$this->Company_price_model;

        $this->inter_id = $this->session->userdata ( 'inter_id' );

        $inter_id = $this->inter_id;

        if(!empty($_POST['cp_code'])){
            $cp_code=$_POST['cp_code'];
        }else{

            $cp_code='99999';
        }

        $status=$model->find_cpCode($inter_id,$cp_code);

        echo json_encode($status);


    }


    public function check_openid(){       //进入酒店页根据openid获取协议价，其中hotel_id还没获取

        $model=$this->load->model('company/Company_price_model','Company_price_model');

        $model=$this->Company_price_model;

        $this->inter_id = $this->session->userdata ( 'inter_id' );
        $this->openid = $this->session->userdata ( $this->inter_id . 'openid' );

        $inter_id = $this->inter_id;
        $openid = $this->openid;
        $hotel_id='';

        $data=array(
            'openid'=>$openid,
            'inter_id'=>$inter_id,
            'hotel_id'=>$hotel_id,
        );

        $find=$model->checkByOpenid($data);


    }
}







