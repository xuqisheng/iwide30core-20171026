<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Aclv3 extends MY_Migration {

    public function up()
    {
    	$db_prefix= $this->get_dbtable_prefix();
    	
		$this->load->database();
        $sql= <<<EOF
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
EOF
;
		$query = $this->db->query($sql);
		$sql= <<<EOF
ALTER TABLE `{$db_prefix}core_admin` 
    MODIFY COLUMN `entity_id` VARCHAR(255) DEFAULT NULL,
    ADD COLUMN `inter_id` VARCHAR(50) AFTER `role_id`;
EOF
;
		$query = $this->db->query($sql);
    }

    public function down()
    {
    	
    }
    
    
}