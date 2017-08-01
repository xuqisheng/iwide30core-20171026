<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Weixin_text extends CI_Model
{
	
	protected $table_name = 'iwide_weixin_text';
	
	public function add_weixin_text($result,$time)
	{
		$writeAdapter = $this->load->database('member_write',true);
		//$sql = "INSERT INTO `iwide_weixin_text` set content = '$result' , edit_date = $time ";
		return $writeAdapter->insert('iwide_weixin_text',array('content' => "$result",'edit_date'=>"$time") );
	}
	
	
	
}