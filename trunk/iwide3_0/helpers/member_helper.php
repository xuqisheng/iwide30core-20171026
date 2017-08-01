<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('member_level'))
{
	function member_level($level=null)
	{
		static $memberLevel;
		
		if(!isset($memberLevel)) {
			$CI =& get_instance();
	
			$CI->load->model('member/config','mconfig');
	        $memberLevel = $CI->mconfig->getConfig('level',true)->value;
		}
        
        if(isset($memberLevel[$level])) {
			return $memberLevel[$level]['name'];
		} else {
			return $memberLevel;
		}
	}
}

if ( !function_exists('card_type'))
{
	function card_type($id=null)
	{
		static $cts;
		
		if(!isset($cts)) {
			$CI =& get_instance();
	
			$CI->load->model('member/icard');
			$cardTypes = $CI->icard->getCardTypeList();
			
			$cts = array();
			foreach($cardTypes as $cardtype) {
				$cts[$cardtype->ct_id]=$cardtype;
			}
		}

		if(isset($cts[$id])) {
			return $cts[$id]->type_name;
		} else {
			return $cts;
		}
	}
}