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
    <div class="hd">
        <h1 class="page_title">
        	<style rel="stylesheet" type="text/css">
        		.weui_icon_msg:before{
        			font-size: 70px;
        		}
        	</style>
        	<i class="weui_icon_msg weui_icon_warn"></i>
			<?php 
			if($order['pay_status'] == 4){
			?>
				<span style="font-size: 18pt; color: #000;">已退款！</span>
	        	<br />
	        	<span class="page_title" style="font-size: 15pt;">已退 ¥<label style="color:#fe7a1f;"><?php echo $order['pay_money']; ?></label>元</span>
			<?php 
			}else{
                ?>
				<span style="font-size: 18pt; color: #000;">支付失败！</span>
	        	<br />
	        	<span class="page_title" style="font-size: 15pt;">应支付 ¥<label style="color:#fe7a1f;"><?php echo $order['pay_money']; ?></label>元</span> 	
			<?php 
			}
			?>
        </h1>
    </div>
    <div class="bd spacing" style="padding:0px;">
         <div class="weui_panel">
            <div class="weui_panel_hd">支付详情</div>
            <div class="weui_panel_bd">
                <div class="weui_media_box weui_media_text">
                	<style>
                		.weui_cell:before{
                			border-top: none;
                		}
                	</style>
                    <div class="weui_cell ">
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
		                    <p>收款人编号</p>
		                </div>
		                <div class="weui_cell_ft"><?php echo $order['sale']; ?></div>
		            </div>
                    
                    <div class="weui_cell">
		                <div class="weui_cell_bd weui_cell_primary">
		                    <p>折扣</p>
		                </div>
		                <div class="weui_cell_ft">¥<?php echo number_format($order['discount_money'],2); ?></div>
		            </div>
                    
                    <div class="weui_cell">
		                <div class="weui_cell_bd weui_cell_primary">
		                    <p>实际支付</p>
		                </div>
		                <?php 
		                if($order['pay_status'] == 4){
		                ?>
		                	<div class="weui_cell_ft" style="color: #fe7a1f;">¥<?php echo number_format($order['pay_money'],2); ?></div>
		                <?php 
		                }else{
		                ?>
		                	<div class="weui_cell_ft" style="color: #fe7a1f;">未支付</div>	                	
		                <?php 
		                }
		                ?>
		            </div>
                    
                </div>
            </div>
            
        </div>
    </div>
    <div class="weui_btn_area">
		<a class="weui_btn weui_btn_primary" href="<?php echo $wx_pay_url?>" id="showTooltips">继续支付</a>
    </div>
    <div class="weui_btn_area" style="margin-bottom: 40px;">
        <a class="weui_btn weui_btn_default" href="<?php echo site_url('okpay/okpay/pay_record') ?>" id="showTooltips2">查看支付历史</a>
    </div>
    <div class="weui_btn_area">&nbsp;&nbsp;</div>

    <?php echo referurl('js','zepto.min.js',1,$media_path) ?>
    <script type="text/javascript">
    	$(document).ready(function(){
		    
    	});
    </script>
    <?php echo referurl('js','hide_menu.js',1,$media_path) ?>
</body>
</html>