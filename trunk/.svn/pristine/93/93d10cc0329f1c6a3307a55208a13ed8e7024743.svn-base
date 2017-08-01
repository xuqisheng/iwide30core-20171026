<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH. 'models'. DS. 'soma'. DS. 'Activity_model.php');

class Activity_killsec_notice_model extends Activity_model {


    public function table_name($inter_id=NULL)
    {
        return $this->_shard_table('soma_activity_killsec_notice', $inter_id);
    }
    public function table_name_r($inter_id=NULL)
    {
		return $this->_shard_table_r('soma_activity_killsec_notice', $inter_id);
    }

	public function table_primary_key()
	{
	    return 'notice_id';
	}
	
	public function attribute_labels()
	{
		return array(

		);
	}




}
