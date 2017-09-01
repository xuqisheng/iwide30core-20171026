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
        <h1 class="page_title" style="margin-right:0px;margin-left:0px;">
        	<style>
        		.weui_icon_msg:before{
        			font-size:28px;
        		}
        	</style>
        	<i class="weui_icon_msg weui_icon_success"></i>
        	<span style="font-size:15pt; color:#000;">支付成功！</span><span class="page_title" style="font-size:15pt;color:#000;margin-left:0px;margin-right:0px;">已支付 ¥<label style="color:#fe7a1f;"><?php echo $order['pay_money']; ?></label>元</span>
        </h1>
        
    </div>
    <div class="bd spacing" style="padding:0px;">
         <div class="weui_panel"><!--
            <div class="weui_panel_hd">支付详情</div>-->
            <div class="weui_panel_bd">
                <div class="weui_media_box weui_media_text" style="padding:10px 0px;font-size:12px;">
                	<style rel="stylesheet" type="text/css">
					.weui_cell:before{
						border-top:none;
					}
					.weui_cell_5{    
						-webkit-box-flex:0.6;
						-webkit-flex:0.6;
						-ms-flex:0.6;
						flex:0.6;
					}
					.weui_cell_2{
						-webkit-box-flex:2;
						-webkit-flex:2;
						-ms-flex:2;
						flex:2;
					}
					.weui_cell_lt{
						text-align:left;
    					color:#888;
					}
                	</style>
                    <div class="weui_cell">
		                <div class="weui_cell_bd weui_cell_5">
		                    <p>订单号码</p>
		                </div>
		                <div class="weui_cell_lt weui_cell_2" style="color:#fe7a1f;"><?php echo $order['out_trade_no'];?></div>
		            </div>
		            <div class="weui_cell">
		                <div class="weui_cell_bd weui_cell_5">
		                    <p>交易时间</p>
		                </div>
		                <div class="weui_cell_lt weui_cell_2">
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
		                <div class="weui_cell_bd weui_cell_5">
		                    <p>收款商户</p>
		                </div>
		                <div class="weui_cell_lt weui_cell_2"><?php echo $hotel['name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $order['pay_type_desc']; ?><!--<?php echo $hotel['name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $order['pay_type_desc']; ?>--></div>
		            </div>
                    
                    <div class="weui_cell">
		                <div class="weui_cell_bd weui_cell_5">
		                    <p>消费金额</p>
		                </div>
		                <div class="weui_cell_lt weui_cell_2">¥<?php echo number_format($order['money'],2); ?></div>
		            </div>
                    
                    <div class="weui_cell">
		                <div class="weui_cell_bd weui_cell_5">
		                    <p>优惠金额</p>
		                </div>
		                <div class="weui_cell_lt weui_cell_2">¥<?php echo number_format($order['discount_money'],2); ?></div>
		            </div>
                    
                    <!-- <div class="weui_cell">
		                <div class="weui_cell_bd weui_cell_5">
		                    <p>优惠活动</p>
		                </div>
		                <div class="weui_cell_lt weui_cell_2">满100减20</div>
		            </div> -->
		            
		           <!--  <div class="weui_cell">
		                <div class="weui_cell_bd weui_cell_5">
		                    <p>优惠券</p>
		                </div>
		                <div class="weui_cell_lt weui_cell_2">说满100减20</div>
		            </div> -->
                    
                    <div class="weui_cell">
		                <div class="weui_cell_bd weui_cell_5">
		                    <p>实际支付</p>
		                </div>
		                <div class="weui_cell_lt weui_cell_2" style="color: #fe7a1f;">¥<?php echo number_format($order['pay_money'],2); ?></div>
		            </div>
                </div>
            </div>
            
        </div>
    </div>
    <?php 
    if(!empty($store_name) && !empty($store_url)){
    ?>
    	<div class="weui_btn_area">
    	<a class="weui_btn weui_btn_primary" href="<?php echo $store_url; ?>" id="showTooltips"><?php echo $store_name; ?></a>
    	</div>
    <?php 
    }
    ?>
    
    
    <style>
	.package{
		margin: 22px 0px;
		padding: 20px;	
	}
    .head_img{
		float: left;
		width: 45pt;
		height: 45pt;
		border-radius: 50%;
		overflow: hidden;
		margin-right: 9pt;
	}
	.head_img >img{
		width:100%;
		height:auto;	
	}
	.receive{
		float:right;
		color:#2d87e2;
		border: 1px solid #2d87e2;
		font-size: 12px;
		padding: 5px 10px;
		border-radius: 5px;
		margin-top: 2%;	
	}
	.txt_con{
		line-height:2;
	}
	.name1{
		font-size: 13px;
		color:#777;
	}
	.na_con{
		font-size:16px;
	}
    </style>
    <?php if(isset($can_get_package) && $can_get_package){?>
    <div class="package weui_panel">
    	<img class="head_img" style="float:left;width:45pt;height:45pt;border-radius:50%;overflow:hidden;" src="<?php echo $hotel['intro_img']; ?>"/>
        <a class="receive" href="<?php echo $package_url; ?>">立即领取</a>
        <div class="txt_con">
        	<div class="name1"><?php echo $hotel['name']; ?></div>
        	<div class="na_con">快乐付消费大礼包</div>
        </div>
    </div>
    <?php }?>
    <!--<div class="weui_btn_area">
    	<a class="weui_btn" style="background:#fe8f00;color:#fff;" href="<?php /*echo $store_url; */?>" id="showTooltips">去商场逛逛</a>
    </div>-->
   <!-- <div class="weui_btn_area" style="margin-bottom: 40px;text-align:center;font-size:14px">
	        <a class="" href="<?php echo site_url('okpay/okpay/pay_record') ?>" id="showTooltips2">查看支付历史</a>
	</div>-->
    <div class="weui_btn_area">&nbsp;&nbsp;</div>
    
    <?php echo referurl('js','zepto.min.js',1,$media_path) ?>
    <script type="text/javascript">
    	$(document).ready(function(){
    		
    		$("#showDialog1").bind("click",function(){
    			var $dialog = $('#dialog1');
	            $dialog.show();
	            $dialog.find('.weui_btn_dialog').one('click', function () {
	                $dialog.hide();
	            });
    		});
    		
    		$("#showDialog2").bind("click",function(){
    			 var $dialog = $('#dialog2');
                $dialog.show();
                $dialog.find('.weui_btn_dialog').one('click', function () {
                    $dialog.hide();
                });
    		});
    		
		    
		    
    	});
    </script>
    <?php echo referurl('js','hide_menu.js',1,$media_path) ?>
</body>
</html>