<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Aclv1 extends MY_Migration {

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
CREATE TABLE IF NOT EXISTS `{$db_prefix}core_node` (
  `node_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(20) DEFAULT NULL,
  `project` tinyint(4) unsigned DEFAULT NULL,
  `parent` int(6) unsigned DEFAULT '0',
  `p_href` varchar(255) DEFAULT NULL,
  `p_label` varchar(100) DEFAULT NULL,
  `p_title` varchar(100) DEFAULT NULL,
  `p_target` varchar(20) DEFAULT NULL,
  `p_icon` varchar(20) DEFAULT NULL,
  `sort` tinyint(4) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`node_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
EOF
;
		$query = $this->db->query($sql);
		$sql= <<<EOF
CREATE TABLE IF NOT EXISTS `{$db_prefix}core_admin_role` (
  `role_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(20) DEFAULT NULL,
  `role_name` varchar(20) DEFAULT NULL,
  `role_label` varchar(100) DEFAULT NULL,
  `acl_desc` text,
  `parent` smallint(5) unsigned DEFAULT '0',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT '1',
  `is_open` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
EOF
;
		$query = $this->db->query($sql);
		$sql= <<<EOF
INSERT INTO `{$db_prefix}core_admin_role` VALUES
	(1, 'adminhtml', 'admin', '超级管理员', 'a:1:{s:9:"adminhtml";s:14:"ALL_PRIVILEGES";}', NULL, '0000-00-00 00:00:00', '2011-08-10 05:24:22', 1);
EOF
;
		$query = $this->db->query($sql);
		$sql= <<<EOF
CREATE TABLE IF NOT EXISTS `{$db_prefix}core_admin` (
  `admin_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` smallint(5) unsigned NOT NULL,
  `entity_id` varchar(225) NOT NULL,
  `username` varchar(64) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `head_pic` varchar(255) DEFAULT NULL,
  `parent_id` smallint(5) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT '1',
  `wx_code` char(35) DEFAULT NULL,
  `is_wx_report` tinyint(1) unsigned DEFAULT '0',
  `is_em_report` tinyint(1) unsigned DEFAULT '0',
  `is_sms_report` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`admin_id`),
  KEY `FK_core_admin_role` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
EOF
;
		$query = $this->db->query($sql);
		$sql= <<<EOF

INSERT INTO `{$db_prefix}core_admin` (`admin_id`, `role_id`, `entity_id`, `username`, `password`, `nickname`, `email`, `create_time`, `update_time`, `remark`, `status`, `wx_code`, `is_wx_report`, `is_em_report`, `is_sms_report`) VALUES
(11, 1, '1', 'admin', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'ben', 'email@gmail.com', '2015-10-29 07:14:34', '2015-11-10 05:24:09', '备注信息2', 1, 'gh_gd02g032u5232g', 0, 0, 0),
(12, 2, '1', 'test', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'ben', 'email@gmail.com', '2015-10-29 07:15:05', '2015-10-29 07:15:05', '备注信息2', 1, 'gh_gd02g032u5232g', 0, 0, 0);
EOF
;
		$query = $this->db->query($sql);
		$sql= <<<EOF
CREATE TABLE IF NOT EXISTS `{$db_prefix}core_admin_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL,
  `module` varchar(20) DEFAULT NULL,
  `action_type` tinyint(4) unsigned DEFAULT NULL,
  `action_time` datetime DEFAULT NULL,
  `action_info` varchar(255) DEFAULT NULL,
  `action_controller` varchar(50) DEFAULT NULL,
  `action_model` varchar(50) DEFAULT NULL,
  `remote_ip` int(11) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`log_id`),
  KEY `FK_core_log_admin` (`admin_id`),
  KEY `IDX_core_log_module` (`module`) USING BTREE,
  KEY `IDX_core_log_time` (`action_time`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
EOF
;
		$query = $this->db->query($sql);
		$sql= <<<EOF
ALTER TABLE `{$db_prefix}core_admin_log`
  ADD CONSTRAINT `FK_core_log_admin` FOREIGN KEY (`admin_id`) REFERENCES `{$db_prefix}core_admin` (`admin_id`) ON DELETE CASCADE;
EOF
;
		$query = $this->db->query($sql);
		
		$sql= <<<EOF
CREATE TABLE IF NOT EXISTS `iwide_core_admin_authid` (
  `auth_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL,
  `inter_id` char(10) DEFAULT NULL,
  `openid` varchar(32) NOT NULL,
  `nickname` varchar(45) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `apply_time` datetime DEFAULT NULL,
  `auth_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  `last_operation` datetime DEFAULT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`auth_id`),
  KEY `IDX_admin_authid_openid` (`openid`),
  KEY `IDX_admin_authid_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ;

ALTER TABLE `iwide_core_admin_authid`
    ADD CONSTRAINT `FK_core_admin_authid_main` FOREIGN KEY (`admin_id`) 
    REFERENCES `iwide_core_admin` (`admin_id`) ON DELETE CASCADE ON UPDATE NO ACTION;
		
    INSERT INTO `iwide_core_admin_authid` (`auth_id`, `admin_id`, `inter_id`, `openid`, `nickname`, `headimgurl`, `auth_time`, `delete_time`, `last_operation`, `status`) VALUES
(1, 1, 'a429262682', 'oX3WojiQIq19qG7Bzsfj7QKPETtE', 'ben', '', '2015-12-14 10:56:42', '', '2015-12-14 10:57:05', 2);
    
EOF
		;
		$query = $this->db->query($sql);
    }

    public function down()
    {
//         $this->dbforge->drop_table('core_node');
//         $this->dbforge->drop_table('core_admin_log');
//         $this->dbforge->drop_table('core_admin');
//         $this->dbforge->drop_table('core_admin_role');
    }
    
    
}