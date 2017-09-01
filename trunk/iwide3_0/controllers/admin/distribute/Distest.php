<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Distest extends MY_Admin {

// 	protected $label_module= NAV_HOTELS;
	protected $label_module= '分销';
	protected $label_controller= '分销';
	protected $label_action= '';
	
	function __construct(){
		parent::__construct();
	}
	
	protected function main_model_name()
	{
		return 'distribute/distribute_model';
	}
	public function index(){
		$sql = "SELECT * FROM `365temp`";
// 		$query = $this->db->get('365temp')->result();
		$query = $this->db->query($sql)->result();
		echo $this->db->last_query();
		foreach ($query as $item){
			$sql = "update iwide_distribute_grade_all set grade_amount=".$item->iprice.",order_amount=".$item->iprice.",grade_total=grade_amount_rate*".$item->iprice." where grade_id=".$item->oid." and inter_id='a445223616' and grade_table='iwide_hotels_order'";
			echo $this->db->query($sql);
			echo $this->db->last_query().'<br />';
		}
	}
	
	public function bgy_mm(){
		$sql = "SELECT * FROM `iwide_member_additional` WHERE inter_id='a421641095' AND ma_id>142143 AND membership_number=''";
		$query = $this->db->query($sql)->result();
		$soap = new SoapClient('http://218.13.33.122:8089/webservice/interface.asmx?wsdl', array('encoding'=>'UTF-8'));
		$user = new UserInfo();
		$i=0;
		$ii=0;
		foreach ($query as $item){
			$i+=1;
			$user->mobile = $item->telephone;
			$params=array('sU'=>$user,'Err'=>array('0','0','0'),'user_cd'=>'crs1','password'=>'crs1','lang'=>'CN');
			$result = $soap->__Call('GetUserInfo',array('parameters'=>$params));
			if(isset($result->GetUserInfoResult->UserInfo->Ic_num)){
				$ii+=1;
				$this->db->where(array('inter_id'=>'a421641095','ma_id'=>$item->ma_id));
				$this->db->update('member_additional',array('membership_number'=>$result->GetUserInfoResult->UserInfo->Ic_num));
				
			}
		}
		echo $i.'<br />';
		echo $ii.'<br />';
	}
	
}
class UserInfo {
	public $Ic_num="";
	public $Ic_typ="P";
	public $Ic_ref="";
	public $Ic_pwd="";
	public $ic_stus="";
	public $gh_num="";
	public $Company_num="";
	public $tot_rvu=0;
	public $gh_typ="";
	public $gh_nm="";
	public $addr="";
	public $postal="";
	public $mobile="";
	public $email="";
	public $officefax="";
	public $geo1="";
	public $geo2="";
	public $geo3="";
	public $crtf_typ="";
	public $crtf_num="";
	public $birthday="0001-01-01";
	public $sex_cd="01";
	public $nation="";
	public $notice="";
	public $tot_score=0;
	public $phone="";
	public $degree="";
	public $htl_cd="";
	public $lang_cd="";
	public $officephone="";
	public $Degree_cd="";
	public $crd_num="";
	public $crtf_nm="";
	public $vip="";
	public $interest="";
	public $hskp_notice="";
	public $pos_notice="";
	public $org_dt="0001-01-01";
	public $org_oper="";
	public $bind_dt="0001-01-01";
	public $chg_oper="";
	public $geo1_nm="";
	public $geo2_nm="";
	public $geo3_nm="";
	public $org_cd="";
	public $hissumscore=0;
	public $ic_bal=0;
	public $trnflg="";
	public $staffname="";
	public $UniteParam="";
	public $staffhtlcd="";
	public $ed_dt="";
	public $other="";
	public $typdrpt="";
	public $flgdrpt="";
	public $s_score="";
	public $is_notlogin='';
	public $introducer="";
	public $track2="";
	public $track3="";
	public $reco_person="";
	public $send_oper="";
	public $to_dt="";
	public $quad_rt="";
	public $crd_dt="0001-01-01";
	public $crtf_dt="0001-01-01";
	public $ic_score=0;
	public $tot_vst=0;
}