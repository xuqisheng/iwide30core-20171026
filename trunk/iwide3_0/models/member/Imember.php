<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Imember
{
	protected $CI;
	
	protected $model;
	
	public function __construct()
	{
		$this->CI = &get_instance();
	}
	
	public function __call($func,$args) {
		
		if(count($args)<2) {
			$hotel_id = 0;
			$inter_id = 0;
		} else {
			$hotel_id = end($args);
			$inter_id = prev($args);
		}
		return $this->getModel($inter_id,$hotel_id)->$func($args);
	}

	
	protected function getModel($inter_id='',$hotel_id=0)
	{
		if(!isset($this->model)) {
			$this->CI->load->library('PMS_Adapter',array('inter_id' => $inter_id,'hotel_id' => $hotel_id),'m_pmsa');
			$this->model =  $this->CI->m_pmsa;
		}
		return $this->model;
	}
}