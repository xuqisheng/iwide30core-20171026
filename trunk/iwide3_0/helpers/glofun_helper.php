<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('test'))
{
	function getAppid()
	{
		$CI = &get_instance();
		
		$CI->load->library('session');
		$appid=false;
		if($CI->session->has_userdata('inter_id')) {
			$appid = $CI->session->inter_id;
		} else {
			if($CI->input->get('id')) {
				$appid = $CI->input->get('id');
			}
		}

		return $appid;
	}
}