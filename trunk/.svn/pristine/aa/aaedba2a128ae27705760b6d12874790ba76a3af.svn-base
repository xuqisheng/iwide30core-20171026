<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>快乐付</title>
    <?php echo referurl('css','weui.css',1,$media_path) ?>
	<?php echo referurl('css','okpay.css',1,$media_path) ?>
   
</head>
<body ontouchstart>
<div>
    <div class="hd">
        <h1 class="page_title">
        	<style>
        		.weui_icon_msg:before{
        			font-size: 70px;
        		}
        	</style>
        	<i class="weui_icon_msg weui_icon_success"></i>
        	<span style="font-size: 18pt; color: #000;">订单支付成功！</span>
        </h1>
        
    </div>
    <div class="bd spacing" style="padding:0px;">
         <div class="weui_panel">
            <div class="weui_panel_hd">支付详情</div>
            <div class="weui_panel_bd">
                <div class="weui_media_box weui_media_text">
                	<style rel="stylesheet" type="text/css">
                		.weui_cell:before{
                			border-top: none;
                		}
                	</style>
                    <div class="weui_cell">
		                <div class="weui_cell_bd weui_cell_primary">
		                    <p>订单号码</p>
		                </div>
		                <div class="weui_cell_ft" style="color:#fe7a1f;"><?php echo $order['out_trade_no'];?></div>
		            </div>
		            <div class="weui_cell">
		                <div class="weui_cell_bd weui_cell_primary">
		                    <p>交易时间</p>
		                </div>
		                <div class="weui_cell_ft">
		                <?php
		                if(empty($order['pay_time'])){
		                	echo date('Y.m.d H:i:s',$order['create_time']);
		                }else{
		                	echo date('Y.m.d H:i:s',$order['pay_time']);
		                }
		                ?>
		                </div>
		            </div>
		            <div class="weui_cell">
		                <div class="weui_cell_bd weui_cell_primary">
		                    <p>收款商户</p>
		                </div>
		                <div class="weui_cell_ft"><?php echo $hotel['name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $order['pay_type_desc']; ?></div>
		            </div>
		            <div class="weui_cell">
		                <div class="weui_cell_bd weui_cell_primary">
		                    <p>支付用户</p>
		                </div>
		                <div class="weui_cell_ft"><?php echo $fans_nickname; ?></div>
		            </div>
		            <div class="weui_cell">
		                <div class="weui_cell_bd weui_cell_primary">
		                    <p>消费额</p>
		                </div>
		                <div class="weui_cell_ft">¥<?php echo number_format($order['money'],2); ?></div>
		            </div>
		            <div class="weui_cell">
		                <div class="weui_cell_bd weui_cell_primary">
		                    <p>实际收款</p>
		                </div>
		                <div class="weui_cell_ft">¥<?php echo number_format($order['pay_money'],2); ?></div>
		            </div>
		            <div class="weui_cell">
		                <div class="weui_cell_bd weui_cell_primary">
		                    <p>不优惠金额</p>
		                </div>
		                <div class="weui_cell_ft">¥<?php echo number_format($order['no_sale_money'],2); ?></div>
		            </div>
                    <div class="weui_cell">
		                <div class="weui_cell_bd weui_cell_primary">
		                    <p>折扣</p>
		                </div>
		                <div class="weui_cell_ft">¥<?php echo number_format($order['discount_money'],2); ?></div>
		            </div>
		            <div class="weui_cell">
		                <div class="weui_cell_bd weui_cell_primary">
		                    <p>收款人编号</p>
		                </div>
		                <div class="weui_cell_ft"><?php echo $order['sale']; ?></div>
		            </div>
                    
                </div>
            </div>
            
        </div>
    </div>
    <div class="weui_btn_area">
		<a class="weui_btn weui_btn_primary" href="<?php echo site_url('okpay/okpay/pay_gather_list') ?>?id=<?php echo $hotel['inter_id']; ?>" id="showTooltips">我的收款记录</a>
    </div>
    <div class="weui_btn_area">&nbsp;&nbsp;</div>
</div>
    <?php echo referurl('js','zepto.min.js',1,$media_path) ?>
    <?php echo referurl('js','hide_menu.js',1,$media_path) ?>
</body>
</html>