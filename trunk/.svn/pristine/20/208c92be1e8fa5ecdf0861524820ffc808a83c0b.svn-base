<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>快乐付</title>
   
	<?php echo referurl('css','weui.css',1,$media_path) ?>
	<?php echo referurl('css','okpay.css',1,$media_path) ?>

	<style>
		.weui_cell_money{
			position:absolute; 
			right:5px; 
			top: 14px; 
			margin-right: 15px;
		}
		.weui_cell_money_yellow{
			color: #fe7a1f;
		}
		.weui_cell_money_black{
			color: #000000;
		}
		.logo{
			width:80px; 
			height:80px; 
			border-radius:50%; 
			overflow:hidden;
		}
		.logo_line_height{
			line-height: 1;
		}
	</style>
</head>
<body ontouchstart>
    <div class="hd">
        <h1 class="page_title logo_line_height">
        	<style>
        		.weui_icon_msg:before{
        			font-size: 70px;
        		}
        	</style>
        	<img src="<?php echo $hotel['intro_img']; ?>"  class="logo"/>
        	<br />
        	<span style="font-size: 18pt; color: #000; line-height: 0.2em;" ><?php echo $hotel['name']; ?></span>
        	<br />
        	<span  style="font-size: 15pt; ">总支付
        	<label style="color:#fe7a1f;">
        	<?php 
        	if(empty($paycount['paycount'])){
        		echo "0";
        	}else{
        		echo $paycount['paycount'];
        	}
        	?></label>元</span>
        </h1>
        
    </div>
    <div class="bd spacing" style="padding:0px;">
         <div class="weui_panel">
            <div class="weui_panel_hd">支付记录</div>
            <div class="weui_panel_bd ">
            <?php
				foreach($recods as $key=>$val){
			?>
		        <div class="pinfo weui_media_box weui_media_text logo_line_height" oid="<?php echo $val['out_trade_no']; ?>" sale="<?php echo $val['sale']; ?> />">
                    <h4 class="weui_media_title"><?php echo $val['pay_type_desc'] ?></h4>
	                <p class="weui_media_desc">
	                <?php
						if($val['pay_status'] == 1){
							?>
						<p>待支付</p>
							<?php
						}else if($val['pay_status'] == 2){
							?>
						<p>支付中</p>
							<?php
						}else if($val['pay_status'] == 3){
							?>
						<p>支付成功</p>
							<?php
						}else if($val['pay_status'] == 4){
								?>
						<p>已退款</p>		
						<?php 
						}else{
							?>
						<p>支付失败</p>
							<?php
						}
					?>
	                </p>
	                
 						<?php
 					if($val['pay_status'] == 4){
 							?>
 						<ul class="weui_media_info">
	                    	<li class="weui_media_info_meta">
	                    	<?php 
			                	echo date('Y.m.d H:i:s',$val['update_time']);
			              	?>
	                    	</li>
	                	</ul>
	                	<span class="weui_cell_money weui_cell_money_black" >- ¥<?php echo number_format($val['pay_money'],2); ?></span>
 							<?php 
 					}else{
 							?>
 						<ul class="weui_media_info">
	                    	<li class="weui_media_info_meta">
 							<?php 
			                if(empty($val['pay_time'])){
			                	echo date('Y.m.d H:i:s',$val['create_time']);
			                }else{
			                	echo date('Y.m.d H:i:s',$val['pay_time']);
			                }
			              ?>
 							</li>
	               		 </ul>
	               		 <span class="weui_cell_money weui_cell_money_yellow" >¥<?php echo number_format($val['pay_money'],2); ?></span>
 							<?php 
 					}
 						?>
					
                </div>
			<?php
				}
			?>
            </div>
        </div>
    </div>
    <div class="weui_btn_area">&nbsp;&nbsp;</div>
    <?php echo referurl('js','zepto.min.js',1,$media_path) ?>
    <script type="text/javascript">
		$(document).ready(function(){
			$(".pinfo").bind("click",function(){
				var oid = $(this).attr("oid");
				var paycode = $(this).attr("sale");
				location.href = "<?php echo site_url('okpay/okpay/pay_info') ?>?id=<?php echo $hotel['inter_id']; ?>&hotel_id=<?php echo $hotel['hotel_id']; ?>&oid="+oid+"&paycode="+paycode;
			});
		});
	</script>
	<?php echo referurl('js','hide_menu.js',1,$media_path) ?>
</body>
</html>