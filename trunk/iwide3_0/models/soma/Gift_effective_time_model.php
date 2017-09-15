<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Gift_effective_time_model extends MY_Model_Soma {

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function table_name()
	{
		return 'iwide_soma_gift_effective_time';
	}

	public function table_primary_key()
	{
	    return 'id';
	}

}
