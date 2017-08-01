<?php 
class User extends CI_Model
{	
	const TALBE_USER = 'iwide_member_user';
	
	public function checkPermissions($usr,$pwd)
	{
		$readAdapter = $this->load->database('member_read',true);
		$query = $readAdapter->from(self::TALBE_USER)->where(array('username'=>$usr,'password'=>md5($pwd)))->get();
		return $query->row();
	}

}