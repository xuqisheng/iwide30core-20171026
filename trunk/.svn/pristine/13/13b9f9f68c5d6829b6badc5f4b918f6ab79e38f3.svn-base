<?php
class Company_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	function get_cprice_by_openid($inter_id,$hotel_id,$openid,$paras=array()){
		$this->load->model('company/Company_price_model','Company_price_model');
		$param['inter_id']=$inter_id;
		$param['openid']=$openid;
		$param['hotel_id']=$hotel_id;
		$result=$this->Company_price_model->checkByOpenid($param);
		if(!empty($result)){
			$company=$this->Company_price_model->getCompanyInfoById($result->company_id);
			$result->company_name=$company->company_name;
		}
		return $result;
	}

    function get_club_by_openid($inter_id,$hotel_id,$openid,$paras=array()){
        $this->load->model('club/Clubs_model','Clubs_model');
        $params['inter_id']=$inter_id;
        $params['openid']=$openid;
        $params['hotel_id']=$hotel_id;
        $result=$this->Clubs_model->checkByOpenid($params);
        return $result;
    }
} 