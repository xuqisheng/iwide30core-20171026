<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Shpv1 extends MY_Migration {

    public function up()
    {
    	$db_prefix= $this->get_dbtable_prefix();
    	
		$this->load->database();
        $sql= <<<EOF
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `{$db_prefix}shp_address` (
`id` bigint(20) NOT NULL,
  `openid` varchar(32) DEFAULT NULL,
  `hotel_id` bigint(20) DEFAULT '0',
  `inter_id` char(10) DEFAULT NULL,
  `country` varchar(32) DEFAULT NULL,
  `province` varchar(32) DEFAULT NULL,
  `city` varchar(32) DEFAULT NULL,
  `region` varchar(32) DEFAULT NULL,
  `address` varchar(150) DEFAULT NULL,
  `zip_code` varchar(15) DEFAULT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `contact` varchar(32) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='邮寄地址';

CREATE TABLE IF NOT EXISTS `{$db_prefix}shp_advs` (
`id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `cate` mediumint(9) DEFAULT '0',
  `hotel_id` bigint(20) DEFAULT '0',
  `inter_id` char(10) DEFAULT NULL,
  `logo` varchar(256) DEFAULT NULL,
  `link` varchar(256) DEFAULT NULL,
  `sort` tinyint(2) DEFAULT '0',
  `last_edit_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$db_prefix}shp_attrbutes` (
`attr_id` int(11) NOT NULL,
  `attr_value` text,
  `attr_link` varchar(256) DEFAULT NULL,
  `cat_id` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品属性表';

CREATE TABLE IF NOT EXISTS `{$db_prefix}shp_cart` (
`cart_id` bigint(20) NOT NULL,
  `openid` varchar(32) DEFAULT NULL,
  `gs_id` mediumint(9) DEFAULT NULL,
  `nums` mediumint(9) DEFAULT '0',
  `hotel_id` bigint(20) DEFAULT NULL,
  `inter_id` char(10) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  `add_time` datetime DEFAULT NULL,
  `attrs` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车';

CREATE TABLE IF NOT EXISTS `{$db_prefix}shp_category` (
`cat_id` smallint(6) NOT NULL,
  `cat_name` varchar(90) DEFAULT NULL,
  `cat_keyword` varchar(255) DEFAULT NULL,
  `cat_desc` varchar(255) DEFAULT NULL,
  `cat_sort` tinyint(4) DEFAULT '0',
  `parent_id` smallint(6) DEFAULT '0',
  `hotel_id` bigint(20) DEFAULT '0',
  `inter_id` char(10) DEFAULT NULL,
  `cat_img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='商品分类';

CREATE TABLE IF NOT EXISTS `{$db_prefix}shp_comments` (
`id` bigint(20) NOT NULL,
  `inter_id` char(10) DEFAULT '',
  `hotel_id` bigint(20) DEFAULT '0',
  `order_id` bigint(20) DEFAULT '0',
  `gs_id` mediumint(9) DEFAULT '0',
  `openid` varchar(32) DEFAULT '',
  `create_time` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `contents` varchar(3000) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$db_prefix}shp_coupons` (
`id` bigint(20) NOT NULL,
  `order_id` bigint(20) DEFAULT '0',
  `inter_id` char(10) DEFAULT NULL,
  `hotel_id` bigint(20) DEFAULT '0',
  `openid` varchar(32) DEFAULT '',
  `total_fee` decimal(10,0) DEFAULT '0',
  `fee_time` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0' COMMENT '0:默认，1:已发放，2:发放失败',
  `mch_billno` varchar(32) DEFAULT '',
  `send_time` datetime DEFAULT NULL,
  `send_listid` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$db_prefix}shp_discount` (
`id` int(11) NOT NULL,
  `hotel_id` bigint(20) DEFAULT '0',
  `inter_id` char(10) DEFAULT NULL,
  `gs_id` mediumint(9) DEFAULT '0',
  `type` tinyint(4) DEFAULT '0',
  `bcount` int(11) DEFAULT '0',
  `ecount` int(11) DEFAULT '0',
  `discount` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品促销定价表';

CREATE TABLE IF NOT EXISTS `{$db_prefix}shp_gift_log` (
`gt_id` bigint(20) NOT NULL,
  `ge_openid` varchar(32) DEFAULT NULL,
  `gt_openid` varchar(32) DEFAULT NULL,
  `ge_time` datetime DEFAULT NULL,
  `gt_time` datetime DEFAULT NULL,
  `ge_code` char(36) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  `order_id` bigint(20) DEFAULT '0',
  `order_items` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='赠送记录';

CREATE TABLE IF NOT EXISTS `{$db_prefix}shp_goods` (
`gs_id` mediumint(9) NOT NULL,
  `cat_id` smallint(6) DEFAULT NULL,
  `gs_name` varchar(120) DEFAULT NULL,
  `gs_brand` mediumint(9) DEFAULT NULL,
  `gs_nums` smallint(6) DEFAULT NULL,
  `gs_weight` decimal(10,3) DEFAULT NULL,
  `gs_market_price` decimal(10,2) DEFAULT NULL,
  `gs_wx_price` decimal(10,2) DEFAULT NULL,
  `gs_warm_nums` int(11) DEFAULT NULL,
  `gs_keyword` varchar(300) DEFAULT '',
  `gs_unit` varchar(16) DEFAULT '',
  `gs_sort` int(11) DEFAULT '0',
  `gs_desc` varchar(300) DEFAULT NULL,
  `gs_logo` varchar(256) DEFAULT NULL,
  `can_mail` tinyint(4) DEFAULT '0',
  `sales_good` tinyint(4) DEFAULT '0',
  `onsale` tinyint(4) DEFAULT NULL,
  `add_user` bigint(20) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `is_delete` tinyint(4) DEFAULT '0',
  `is_new` tinyint(4) DEFAULT '0',
  `is_hot` tinyint(4) DEFAULT '0',
  `is_promote` tinyint(4) DEFAULT '0',
  `last_update_time` datetime DEFAULT NULL,
  `last_update_user` mediumint(9) DEFAULT NULL,
  `hotel_id` bigint(20) DEFAULT '0',
  `inter_id` char(10) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='商品';

CREATE TABLE IF NOT EXISTS `{$db_prefix}shp_goods_attr` (
`gs_attr_id` int(11) NOT NULL COMMENT '子属性ID',
  `attr_id` smallint(6) DEFAULT NULL,
  `gs_id` mediumint(9) DEFAULT '0',
  `attr_value` text,
  `attr_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品属性表';

CREATE TABLE IF NOT EXISTS `{$db_prefix}shp_goods_gallery` (
`gry_id` int(11) NOT NULL,
  `gs_id` mediumint(9) DEFAULT '0',
  `gry_url` varchar(256) DEFAULT NULL,
  `gry_desc` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品相册';

CREATE TABLE IF NOT EXISTS `{$db_prefix}shp_orders` (
`order_id` bigint(20) NOT NULL,
  `topic_id` int(10) NOT NULL,
  `out_trade_no` char(16) DEFAULT NULL,
  `openid` varchar(32) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `order_time` datetime DEFAULT NULL,
  `hotel_id` bigint(20) DEFAULT NULL,
  `inter_id` char(10) DEFAULT NULL,
  `pay_status` tinyint(4) DEFAULT '0',
  `pay_time` datetime DEFAULT NULL,
  `transaction_id` varchar(32) DEFAULT NULL,
  `total_fee` decimal(10,2) DEFAULT NULL,
  `card_fee` decimal(10,2) DEFAULT NULL,
  `sub_fee` decimal(10,2) DEFAULT NULL,
  `shipping_fee` decimal(10,2) DEFAULT NULL,
  `saler` bigint(20) DEFAULT NULL,
  `fans_id` bigint(20) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品订单表';

CREATE TABLE IF NOT EXISTS `{$db_prefix}shp_order_items` (
`id` bigint(20) NOT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `gs_id` mediumint(9) DEFAULT NULL,
  `gs_name` varchar(120) DEFAULT NULL,
  `market_price` decimal(10,2) DEFAULT '0.00',
  `price` decimal(10,2) DEFAULT '0.00',
  `promote_price` decimal(10,2) DEFAULT NULL,
  `openid` varchar(32) DEFAULT NULL,
  `get_openid` varchar(32) DEFAULT NULL,
  `gs_unit` varchar(16) DEFAULT '',
  `gs_code` varchar(15) DEFAULT NULL,
  `get_time` datetime DEFAULT NULL,
  `consume_time` datetime DEFAULT NULL,
  `consumer` varchar(32) DEFAULT NULL,
  `ex_order` tinyint(4) DEFAULT '0',
  `status` tinyint(4) DEFAULT '0',
  `is_add_pack` tinyint(4) DEFAULT '0',
  `addr_id` bigint(20) DEFAULT NULL,
  `order_time` datetime DEFAULT NULL,
  `trans_time` datetime DEFAULT NULL,
  `trans_company` varchar(150) DEFAULT NULL,
  `trans_no` varchar(50) DEFAULT NULL,
  `send_name` varchar(32) DEFAULT NULL,
  `send_phone` varchar(32) DEFAULT NULL,
  `share_code` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单详细信息';

ALTER TABLE `{$db_prefix}shp_address`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `{$db_prefix}shp_advs`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `{$db_prefix}shp_attrbutes`
 ADD PRIMARY KEY (`attr_id`), ADD KEY `FK_Reference_4` (`cat_id`);

ALTER TABLE `{$db_prefix}shp_cart`
 ADD PRIMARY KEY (`cart_id`), ADD KEY `FK_Reference_10` (`gs_id`);

ALTER TABLE `{$db_prefix}shp_category`
 ADD PRIMARY KEY (`cat_id`);

ALTER TABLE `{$db_prefix}shp_comments`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `{$db_prefix}shp_coupons`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `{$db_prefix}shp_discount`
 ADD PRIMARY KEY (`id`), ADD KEY `FK_Reference_13` (`gs_id`);

ALTER TABLE `{$db_prefix}shp_gift_log`
 ADD PRIMARY KEY (`gt_id`), ADD KEY `FK_Reference_11` (`order_id`);

ALTER TABLE `{$db_prefix}shp_goods`
 ADD PRIMARY KEY (`gs_id`), ADD KEY `FK_Reference_5` (`cat_id`);

ALTER TABLE `{$db_prefix}shp_goods_attr`
 ADD PRIMARY KEY (`gs_attr_id`);

ALTER TABLE `{$db_prefix}shp_goods_gallery`
 ADD PRIMARY KEY (`gry_id`);

ALTER TABLE `{$db_prefix}shp_orders`
 ADD PRIMARY KEY (`order_id`);

ALTER TABLE `{$db_prefix}shp_order_items`
 ADD PRIMARY KEY (`id`), ADD KEY `FK_Reference_14` (`order_id`);

ALTER TABLE `{$db_prefix}shp_address`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{$db_prefix}shp_advs`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{$db_prefix}shp_attrbutes`
MODIFY `attr_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{$db_prefix}shp_cart`
MODIFY `cart_id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{$db_prefix}shp_category`
MODIFY `cat_id` smallint(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;

ALTER TABLE `{$db_prefix}shp_comments`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{$db_prefix}shp_coupons`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{$db_prefix}shp_discount`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{$db_prefix}shp_gift_log`
MODIFY `gt_id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{$db_prefix}shp_goods`
MODIFY `gs_id` mediumint(9) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;

ALTER TABLE `{$db_prefix}shp_goods_attr`
MODIFY `gs_attr_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '子属性ID';

ALTER TABLE `{$db_prefix}shp_goods_gallery`
MODIFY `gry_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{$db_prefix}shp_orders`
MODIFY `order_id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{$db_prefix}shp_order_items`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `{$db_prefix}shp_attrbutes`
ADD CONSTRAINT `FK_Reference_4` FOREIGN KEY (`cat_id`) REFERENCES `{$db_prefix}shp_category` (`cat_id`);

ALTER TABLE `{$db_prefix}shp_cart`
ADD CONSTRAINT `FK_Reference_10` FOREIGN KEY (`gs_id`) REFERENCES `{$db_prefix}shp_goods` (`gs_id`);

ALTER TABLE `{$db_prefix}shp_discount`
ADD CONSTRAINT `FK_Reference_13` FOREIGN KEY (`gs_id`) REFERENCES `{$db_prefix}shp_goods` (`gs_id`);

ALTER TABLE `{$db_prefix}shp_gift_log`
ADD CONSTRAINT `FK_Reference_11` FOREIGN KEY (`order_id`) REFERENCES `{$db_prefix}shp_orders` (`order_id`);

ALTER TABLE `{$db_prefix}shp_goods`
ADD CONSTRAINT `FK_Reference_5` FOREIGN KEY (`cat_id`) REFERENCES `{$db_prefix}shp_category` (`cat_id`);

ALTER TABLE `{$db_prefix}shp_order_items`
ADD CONSTRAINT `FK_Reference_14` FOREIGN KEY (`order_id`) REFERENCES `{$db_prefix}shp_orders` (`order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
        
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