<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>快乐付</title>
    <?php echo referurl('css','weui.css',1,$media_path) ?>
	<?php echo referurl('css','okpay.css',1,$media_path) ?>
	<style>
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
        	<img src="<?php echo $saler['ext']['headimgurl']; ?>"  class="logo"/>
        	<br />
        	<span style="font-size:18pt;color:#000;line-height: 0.2em;" >分销号：<?php echo $saler['qrcode_id']; ?></span>
        	<br />
        	<span  style="font-size:15pt;color:#a3a3a3">总收款
        		<label style="color:#fe7a1f;">¥
        		<?php
				if(empty($sale_count['paymoney'])){
					echo "0";
				}else{
					echo $sale_count['paymoney'];
				}
				?>
        </h1>
        
    </div>
    <div class="bd spacing" style="padding:0px;">
    	<style>
    		.weui_bar_item_on:before{
    			background: #ffffff;
    		}
    		.weui_navbar{
    			background: #ffffff;
				color:#a3a3a3;
    		}
			..weui_panel:befor{
				content: " ";
				position: absolute;
				left: 0;
				bottom: 0;
				width: 100%;
				height: 1px;
    			border-top: 1px solid #BCBAB6;
				color: #BCBAB6;
				-webkit-transform-origin: 0 100%;
				transform-origin: 0 100%;
				-webkit-transform: scaleY(0.5);
				transform: scaleY(0.5);	
			}
			.weui_navbar_item:after {
				content: " ";
				position: absolute;
				right: 0;
				top: 0;
				width: 1px;
				height: 100%;
				border-right: 1px solid #cccccc;
				color: #cccccc;
				-webkit-transform-origin: 0 100%;
				transform-origin: center;
				-webkit-transform: scaleX(0.5);
				transform: scale(0.5);
				right: -1px;
			}
			.weui_tab {
				top: 1px;
			}
    		.weui_bar_item_on{
    			background-color: #ffffff;
    		}
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
			.weui_navbar + .weui_tab_bd {
				padding-top:42px;
				padding-bottom: 0;
			}
    	</style>

    	<div class="weui_panel ">
    		
        	<div class="weui_tab">
	            <div class="weui_navbar">
	                <div v="1" class="link_div weui_navbar_item <?php if(1 == intval($page_type)){ ?>" style="color: #ff8828;   <?php  } ?>">
	                    所有收款(<?php echo $sale_all_count['paytimes']?>)
	                </div>
	                <div v="2" class="link_div weui_navbar_item <?php if(2 == intval($page_type)){ ?>" style="color: #ff8828;  <?php  } ?>" >
	                    今日收款(<?php echo $sale_day_count['paytimes']?>)
	                </div>
	                <div v="3" class="link_div weui_navbar_item <?php if(3 == intval($page_type)){ ?>" style="color: #ff8828;   <?php  } ?>">
	                    本月收款(<?php echo $sale_month_count['paytimes']?>)
	                </div>
	            </div>
	            <div class="weui_tab_bd"></div>
	        </div>
	        
	        <div class="weui_panel_bd ">
                <?php
				foreach($sale_list as $key=>$val){
					if($val['pay_status'] == 3){
				?>
						<div class="weui_media_box weui_media_text logo_line_height" >
		                    <h4 class="weui_media_title"><?php echo $val['pay_type_desc']; ?></h4>
			                <p class="weui_media_desc">支付成功</p>
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
		                </div>
				<?php
					}elseif($val['pay_status'] == 4){
				?>
						<div class="weui_media_box weui_media_text logo_line_height">
		                    <h4 class="weui_media_title"><?php echo $val['pay_type_desc']; ?></h4>
			                <p class="weui_media_desc">已退款</p>
			                <ul class="weui_media_info">
			                    <li class="weui_media_info_meta">
			                    <?php echo date('Y.m.d H:i:s',$val['update_time']); ?>
			                    </li>
			                </ul>
			                <span class="weui_cell_money weui_cell_money_black" >- ¥<?php echo number_format($val['pay_money'],2); ?></span>
		                </div>
				<?php
					}
				}
			?>
		    </div>
        </div>
        <div style="text-align:center;">
            <img style="display:block;width:60px;height:55px;margin:20px auto" src="http://ihotels.iwide.cn/public/okpay/default/images/none.png">
            <p style="line-height:1.5;color:#a3a3a3;">没有查到相关信息～</p> 
        </div>
    </div>
    <div class="weui_btn_area">&nbsp;&nbsp;</div>

    <?php echo referurl('js','zepto.min.js',1,$media_path) ?>
    <script type="text/javascript">
    	$(document).ready(function(){
    		 
		    $(".link_div").bind("click",function(){
		    	var link_type = parseInt($(this).attr("v"));
				if(link_type == 1){
					location.href = "<?php echo site_url('okpay/okpay/pay_gather_list') ?>?id=<?php echo $saler['inter_id']?>&type=1";
				}else if(link_type == 2){
					location.href = "<?php echo site_url('okpay/okpay/pay_gather_list') ?>?id=<?php echo $saler['inter_id']?>&type=2";
				}else{
					location.href = "<?php echo site_url('okpay/okpay/pay_gather_list') ?>?id=<?php echo $saler['inter_id']?>&type=3";
				}
			});
		    
    	});
    </script>
    <?php echo referurl('js','hide_menu.js',1,$media_path) ?>
</body>
</html>