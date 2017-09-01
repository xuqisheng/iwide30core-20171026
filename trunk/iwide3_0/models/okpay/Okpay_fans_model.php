<?php
class Okpay_fans_model extends MY_Model{
	function __construct() {
		parent::__construct ();
	}
	
	const TAB_OKPAY_FANS = 'fans';
	
	
	public function get_fans_nickname($inter_id,$openid){
		$this->_db('iwide_r1')->select('id,nickname' );
		$this->_db('iwide_r1')->where ( array (
				'inter_id' => $inter_id,
				'openid' => $openid
		) );
		return $this->_db('iwide_r1')->get( self::TAB_OKPAY_FANS )->row_array();
	}
	
}