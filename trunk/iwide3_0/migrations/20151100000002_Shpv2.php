<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Shpv2 extends MY_Migration {

    public function up()
    {
    	$db_prefix= $this->get_dbtable_prefix();
    	
		$this->load->database();
        $sql= <<<EOF
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
            
CREATE TABLE IF NOT EXISTS `{$db_prefix}shp_topic` (
  `topic_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identity` VARCHAR(45) NOT NULL,
  `hotel_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `inter_id` char(10) NOT NULL,
  `page_theme` varchar(50) NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `page_starttime` DATETIME NOT NULL,
  `page_endtime` DATETIME NOT NULL,
    
  `share_title` varchar(255) NOT NULL,
  `share_link` varchar(255) NOT NULL,
  `share_img` varchar(255) NOT NULL,
  `share_desc` mediumtext NOT NULL,
  `share_title_gift` varchar(255) NOT NULL,
  `share_link_gift` varchar(255) NOT NULL,
  `share_img_gift` varchar(255) NOT NULL,
  `share_desc_gift` mediumtext NOT NULL,
    
  `is_invoice` TINYINT(1) UNSIGNED NOT NULL,
    
  `status` TINYINT(1) UNSIGNED NOT NULL,
  `sort` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='专题配置';
        
CREATE TABLE `{$db_prefix}shp_topic_advs` (
  `topic_id` INTEGER(10) UNSIGNED NOT NULL,
  `adv_id` INTEGER(10) UNSIGNED NOT NULL
)
ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE `{$db_prefix}shp_topic_goods` (
  `topic_id` INTEGER(10) UNSIGNED NOT NULL,
  `gs_id` INTEGER(10) UNSIGNED NOT NULL
)
ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE `{$db_prefix}shp_topic_category` (
  `topic_id` INTEGER(10) UNSIGNED NOT NULL,
  `cat_id` smallint(6) UNSIGNED NOT NULL
)
ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `{$db_prefix}shp_invoice` (
  `invoice_id` int(10) unsigned NOT NULL,
  `hotel_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `inter_id` char(10) NOT NULL,
    
  `title` varchar(50) NOT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `out_trade_no` varchar(20) NOT NULL,
    
  `address_id` int(11) unsigned DEFAULT NULL,
    
  `subtotal` decimal(12,2) DEFAULT NULL COMMENT '总计',
  `grand_total` decimal(12,2) DEFAULT NULL COMMENT '小计',
  `shipping_amount` decimal(12,2) DEFAULT NULL COMMENT '运费',
  `discount_amount` decimal(12,2) DEFAULT NULL COMMENT '折扣额',
    
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `status` TINYINT(1) UNSIGNED NOT NULL,
  PRIMARY KEY (`invoice_id`)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='发票申请';
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