<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Kargov1 extends MY_Migration {

    public function up()
    {
    	$db_prefix= $this->get_dbtable_prefix();
    	
		$this->load->database();
        $sql= <<<EOF
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
            
CREATE TABLE IF NOT EXISTS `{$db_prefix}shp_wishes` (
  `wish_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `inter_id` char(10) NOT NULL,
  `order_id` int(10) unsigned DEFAULT NULL,
  `code` int(10) unsigned DEFAULT NULL,
    
  `openid` varchar(32) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
    
  `message` varchar(255) DEFAULT NULL,
  `bg_url` varchar(255) DEFAULT NULL,
    
  `serverId` varchar(255) unsigned DEFAULT NULL,
  `voice_url` varchar(255) DEFAULT NULL,
    
  `create_time` datetime DEFAULT NULL,
  `lastview_time` datetime DEFAULT NULL,
  `view_count` int(10) UNSIGNED DEFAULT '0',
    
  `status` TINYINT(1) UNSIGNED NOT NULL,
  PRIMARY KEY (`wish_id`)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='赠礼寄语';
EOF
;
        $sql_array= explode(';', $sql);
        foreach ($sql_array as $v){
            $query = $this->db->query($v);
        }
    }

    public function down()
    {
    	
    }
    
    
}