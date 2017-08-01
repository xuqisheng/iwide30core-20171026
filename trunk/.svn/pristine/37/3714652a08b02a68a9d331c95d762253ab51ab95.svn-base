<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Aclv2 extends MY_Migration {

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
INSERT INTO `{$db_prefix}core_node` (`node_id`, `module`, `project`, `parent`, `p_href`, `p_label`, `p_title`, `p_target`, `p_icon`, `sort`, `status`) VALUES


(94, 'adminhtml', 9, 0, '#', '金房卡商品中心', '金房卡商品中心', '', '', 1, 1),
(93, 'adminhtml', 9, 0, '#', '分销商品', '分销商品', '', '', 1, 1),
(92, 'adminhtml', 9, 0, '#', '分销规则', '分销规则', '', '', 1, 1),
(91, 'adminhtml', 9, 0, '#', '分销人员', '分销人员', '', '', 1, 1),
   
(83, 'adminhtml', 8, 0, '#', '员工管理', '员工管理', '', '', 1, 1),
(82, 'adminhtml', 8, 0, '#', '折扣管理', '折扣管理', '', '', 1, 1),
(81, 'adminhtml', 8, 0, '#', '订单流水', '订单流水', '', '', 1, 1),
    
	(7101, 'adminhtml', 7, 71, 'member/promoterule', '会员营销设置', '会员营销设置', '', 'fa-phone', 1, 1),
	(7102, 'adminhtml', 7, 71, 'member/chargelist/grid', '会员充值记录详情', '会员充值记录详情', '', 'fa-hourglass', 1, 1),
	(7103, 'adminhtml', 7, 71, 'member/registerinfo', '会员注册信息配置', '会员注册信息配置', '', 'fa-registered', 1, 1),
	(7104, 'adminhtml', 7, 71, 'member/memberlevel', '会员等级设置', '会员等级设置', '', 'fa-level-up', 1, 1),
	(7105, 'adminhtml', 7, 71, 'member/memberlist/grid', '全部会员资料', '全部会员资料', '', 'fa-male', 1, 1),

	(7201, 'adminhtml', 7, 72, 'member/membercat', '行业编辑', '行业编辑', '', 'fa-industry', 1, 1),
	(7202, 'adminhtml', 7, 72, 'member/bonusexplain', '积分说明', '积分说明', '', 'fa-spinner', 1, 1),
	(7203, 'adminhtml', 7, 72, 'member/bonusdetail', '积分明细', '积分明细', '', 'fa-hourglass-half', 1, 1),
	(7204, 'adminhtml', 7, 72, 'member/bonusrule', '积分规则', '积分规则', '', 'fa-navicon', 1, 1),

	(7301, 'adminhtml', 7, 73, 'member/carduserule', '卡券使用规则', '卡券使用规则', '', 'fa-sliders', 1, 1),
	(7303, 'adminhtml', 7, 73, 'member/cardtype/grid', '卡劵种类', '卡劵种类', '', 'fa-indent', 1, 1),
	(7305, 'adminhtml', 7, 73, 'member/cardlist/grid', '卡劵列表', '卡劵列表', '', 'fa-list', 1, 1);

(71, 'adminhtml', 7, 0, '#', '会员管理', '会员管理', '', 'fa-child', 1, 1),
(72, 'adminhtml', 7, 0, '#', '积分管理', '积分管理', '', 'fa-bitcoin', 1, 1),
(73, 'adminhtml', 7, 0, '#', '卡券管理', '卡券管理', '', 'fa-credit-card', 1, 1),
    
    
    
    (6301, 'adminhtml', 6, 63, 'mall/goods/add', '商品添加', '商品添加', '', 'fa-plus', 1, 1),
    (6302, 'adminhtml', 6, 63, 'mall/goods/index', '商品管理', '商品管理', '', 'fa-cube', 1, 1),
    (6303, 'adminhtml', 6, 63, 'mall/category/index', '商品分类', '商品分类', '', 'fa-cubes', 3, 1),
    (6304, 'adminhtml', 6, 63, 'mall/comments/index', '商品评论', '商品评论', '', 'fa-commenting', 5, 1),

(63, 'adminhtml', 6, 0, 'mall/goods/index', '商品管理', '商品管理', '', 'fa-cube', 4, 1),
(64, 'adminhtml', 6, 0, 'mall/topics/index', '酒店模板', '酒店模板', '', 'fa-file-o', 2, 1),
(65, 'adminhtml', 6, 0, 'mall/advs/index', '焦点图', '焦点图', '', 'fa-image', 3, 1),
(61, 'adminhtml', 6, 0, 'mall/orders/index', '订单管理', '订单管理', '', 'fa-cart-arrow-down', 8, 1),
(62, 'adminhtml', 6, 0, 'mall/wishes/index', '赠礼寄语', '赠礼寄语', '', 'fa-commenting', 7, 0),
(66, 'adminhtml', 6, 0, 'mall/items/index', '邮寄地址', '邮寄地址', '', 'fa-ambulance', 7, 0),
    (67, 'adminhtml', 6, 0, 'mall/carts/index', '购物车', '购物车监控', '', 'fa-cart-plus', 5, 0),
(68, 'adminhtml', 6, 0, 'mall/invoice/index', '发票管理', '发票管理', '', 'fa-ticket', 6, 1),
    (69, 'adminhtml', 6, 0, 'mall/address/index', '收货地址', '收货地址', '', 'fa-ambulance', 5, 0),
    
    
    
    (5101, 'adminhtml', 5, 51, 'soma/sales_order/index', '订单管理', '订单管理', '', 'fa-shopping-cart', 3, 1),
    (5102, 'adminhtml', 5, 51, 'soma/consumer_order/index', '核销管理', '核销管理', '', 'fa-gg', 2, 1),
    (5103, 'adminhtml', 5, 51, 'soma/refund_order/index', '退款管理', '退款管理', '', 'fa-cubes', 1, 1),
    (5104, 'adminhtml', 5, 51, 'soma/gift_order/index', '赠送管理', '赠送管理', '', 'fa-gift', 0, 1),
    (5105, 'adminhtml', 5, 51, 'soma/consumer_shipping/index', '订单邮寄', '订单邮寄', '', 'fa fa-ambulance', 2, 1),
    (5106, 'adminhtml', 5, 51, 'soma/reward_rule/index', '分销奖励规则', '分销奖励规则', '', 'fa fa-money', 0, 1),
    (5107, 'adminhtml', 5, 51, 'soma/reward_benefit/index', '分销奖励明细', '分销奖励明细', '', 'fa fa-money', 0, 1),
    (5108, 'adminhtml', 5, 51, 'soma/sales_coupon/index', '优惠券管理', '优惠券管理', '', 'fa fa-money', 0, 1),
    (5201, 'adminhtml', 5, 52, 'soma/cms_block/index', '推荐位管理', '推荐位管理', '', 'fa-thumbs-up', 0, 1),
    (5202, 'adminhtml', 5, 52, 'soma/message_wxtemp_template/index', '模板消息管理', '修改模板消息类型', '', 'fa-cube', 0, 2),
    (5203, 'adminhtml', 5, 52, 'soma/message_wxtemp_record/index', '模板消息记录', '模板消息记录', '', 'fa fa-file-o', 0, 2),
    (5301, 'adminhtml', 5, 53, 'soma/product_package/add', '套票添加', '套票添加', '', 'fa-plus', 1, 1),
    (5302, 'adminhtml', 5, 53, 'soma/product_package/index', '套票管理', '套票管理', '', 'fa-cube', 1, 1),
    (5303, 'adminhtml', 5, 53, 'soma/category_package/index', '套票分类', '套票分类', '', 'fa-cubes', 3, 1),
    (5401, 'adminhtml', 5, 54, 'soma/activity_package/add', '活动添加', '活动添加', '', 'fa-plus', 1, 1),
    (5402, 'adminhtml', 5, 54, 'soma/activity_package/index', '拼团活动', '拼图活动管理', '', 'fa-cube', 1, 1),
    (5403, 'adminhtml', 5, 54, 'soma/activity_killsec/index', '秒杀活动', '秒杀活动管理', '', 'fa-cube', 1, 1),
    (5501, 'adminhtml', 5, 55, 'soma/adv/add', '新增焦点图', '新增焦点图', '', 'fa-plus', 1, 1),
    (5502, 'adminhtml', 5, 55, 'soma/adv/index', '首页焦点图', '首页焦点图', '', 'fa-cube', 1, 1),
    (5503, 'adminhtml', 5, 55, 'soma/theme/index', '分享配置', '分享配置', '', 'fa-share-alt', 6, 1),
    (5504, 'adminhtml', 5, 55, 'soma/theme_config_use/theme', '皮肤选择', '皮肤选择', '', 'fa-puzzle-piece', 6, 1),
    (5601, 'adminhtml', 5, 56, 'soma/activity_package/stat', '拼团数据', '拼团数据', '', 'fa-money', 1, 2),
    (5602, 'adminhtml', 5, 56, 'soma/sales_order/stat', '营销数据', '营销数据', '', 'fa-money', 1, 2),
    (5603, 'adminhtml', 5, 56, 'soma/statis_sales/index', '订单数据', '订单数据', '', 'fa-shopping-cart', 3, 1),
    (5701, 'adminhtml', 5, 57, 'soma/reward_benefit/change_inter_id', '账号归属切换', '账号归属切换', '', 'fa-user', 3, 1),
    (5702, 'adminhtml', 5, 57, 'soma/reward_benefit/rebuild', '分销绩效扫描', '分销绩效扫描', '', 'fa-money', 3, 1),
    (5703, 'adminhtml', 5, 57, 'soma/consumer_order/consume', '内部直接核销', '内部直接核销', '', 'fa-search', 3, 1),
    (5704, 'adminhtml', 5, 57, 'soma/consumer_order/get_info', '查找信息工具', '查找信息工具', '', 'fa-search', 3, 1),
    (5705, 'adminhtml', 5, 57, 'soma/statis_sales/reflush', '统计缓存刷新', '统计缓存刷新', '', 'fa-shopping-cart', 3, 1),
    
(51, 'adminhtml', 5, 0, 'soma/sales_order/index', '交易管理', '交易管理', '', 'fa-cart-arrow-down', 1, 1),
(52, 'adminhtml', 5, 0, 'soma/adv/index', '内容管理', '内容管理', '', 'fa-file-o', 0, 1),
(53, 'adminhtml', 5, 0, 'soma/product_package/index', '套票管理', '套票管理', '', 'fa-ticket', 1, 1),
(54, 'adminhtml', 5, 0, 'soma/activity_package/index', '活动管理', '活动管理', '', 'fa-adn', 1, 1),
(55, 'adminhtml', 5, 0, 'soma/adv/index', '首页设置', '首页设置', '', 'fa-file-o', 1, 1),
(56, 'adminhtml', 5, 0, 'soma/sales_order/index', '数据分析', '数据分析', '', 'fa-bar-chart', 9, 1),
(57, 'adminhtml', 5, 0, 'soma/reward_benefit/rebuild', '应急工具箱', '应急工具箱', '', 'fa-medkit', 0, 1),
    
    
(36, 'adminhtml', 3, 0, '#', '评论管理', '评论管理', '', '', 1, 1),
(35, 'adminhtml', 3, 0, '#', '房价维护', '房价维护', '', '', 1, 1),
(34, 'adminhtml', 3, 0, '#', '房态维护', '房态维护', '', '', 1, 1),
(33, 'adminhtml', 3, 0, '#', '订单管理', '订单管理', '', '', 1, 1),
(32, 'adminhtml', 3, 0, '#', '添加酒店', '添加酒店', '', '', 1, 1),
(31, 'adminhtml', 3, 0, '#', '酒店列表', '酒店列表', '', '', 1, 1),
	

    (2101, 'adminhtml', 2, 21, 'basic/codegen/model', '代码生成', '代码生成', '', 'fa-code', 1, 1),
    
(23, 'adminhtml', 2, 0, 'basic/publics/index', '公众号管理', '公众号管理', '', 'fa-user', 1, 1),
(22, 'adminhtml', 2, 0, 'basic/notice/grid', '消息通知', '消息通知', '', 'fa-th', 1, 1),
(21, 'adminhtml', 2, 0, '', '开发工具', '开发工具', '', 'fa-wrench', 1, 1),

    

(11, 'adminhtml', 1, 0, 'privilege/node/grid', '菜单管理', '菜单管理', '', 'fa-th', 1, 1),
(12, 'adminhtml', 1, 0, 'privilege/node/icons', '菜单图标', '菜单图标', '', 'fa-fonticons', 1, 1),
(13, 'adminhtml', 1, 0, 'privilege/adminrole/grid', '权限角色', '权限角色', '', 'fa-legal', 1, 1),
(14, 'adminhtml', 1, 0, 'privilege/adminuser/grid', '后台管理员', '后台管理员', '', 'fa-user', 1, 1),
(15, 'adminhtml', 1, 0, 'privilege/adminlog/grid', '管理员日志', '管理员日志', '', 'fa-files-o', 1, 1),
(16, 'adminhtml', 1, 0, 'privilege/adminuser/staff', '账号管理', '员工账号管理', '', 'fa-user', 1, 1),
    


    (201, 'adminhtml', 3, 2, '#', '下单数据', '下单数据', '', '', 1, 1),

    (301, 'adminhtml', 3, 3, '#', '酒店信息预览', '酒店信息预览', '', '', 1, 1),
    (302, 'adminhtml', 3, 3, '#', '财务细则', '财务细则', '', '', 1, 1),
    (303, 'adminhtml', 3, 3, '#', '数据报表', '数据报表', '', '', 1, 1),

    (401, 'adminhtml', 3, 4, '#', '支付方式图表', '支付方式图表', '', '', 1, 1),
    (402, 'adminhtml', 3, 4, '#', '酒店订单量报表', '酒店订单量报表', '', '', 1, 1),
    (403, 'adminhtml', 3, 4, '#', '酒店订单量图表', '酒店订单量图表', '', '', 1, 1),
    (404, 'adminhtml', 3, 4, '#', '入住时间图表', '入住时间图表', '', '', 1, 1),
    (405, 'adminhtml', 3, 4, '#', '价格统计', '价格统计', '', '', 1, 1),
    (406, 'adminhtml', 3, 4, '#', '用户入住报表', '用户入住报表', '', '', 1, 1),
    (407, 'adminhtml', 3, 4, '#', '离店图表', '离店图表', '', '', 1, 1),
    (408, 'adminhtml', 3, 4, '#', '代金券使用比例', '代金券使用比例', '', '', 1, 1),
    (409, 'adminhtml', 3, 4, '#', '房型入住次数', '房型入住次数', '', '', 1, 1),
    (410, 'adminhtml', 3, 4, '#', '下单时间图表', '下单时间图表', '', '', 1, 1),

(1, 'adminhtml', 0, 0, 'privilege/auth/dashboard', '概览', '概览', '', 'fa-users', 1, 1),
(2, 'adminhtml', 0, 0, 'privilege/member/dashboard', '数据图表', '数据图表', '', 'fa-pie-chart', 1, 1),
(3, 'adminhtml', 0, 0, 'privilege/member/dashboard', '财务报表', '财务报表', '', 'fa-pie-chart', 1, 1),
(4, 'adminhtml', 0, 0, 'privilege/member/dashboard', '数据报表', '数据报表', '', 'fa-pie-chart', 1, 1);

EOF
;
		$query = $this->db->query($sql);
    }

    public function down()
    {
    	
    }
    
    
}