<!DOCTYPE html>
<html lang="en">
<head>
<script src="<?php echo base_url("public/member/highclass/js/rem.js")?>"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui">
<!-- 全局控制 -->
<link rel="stylesheet" href="<?php echo base_url("public/member/highclass/css/global.css")?> "type="text/css">
<link rel="stylesheet" href="<?php echo base_url("public/member/highclass/css/mycss.css")?>" type="text/css">
<script src="<?php echo base_url("public/member/highclass/js/jquery.js")?>"></script>
<script src="<?php echo base_url("public/member/highclass/js/myjs.js")?>"></script>
<title>详细信息</title>
</head>
<body>
<div class="gradient_bg">
	<section class="padding_0_15 padding_top_18">
		<div class="padding_0_16 padding_bottom_40">
			<div class="padding_0_9">
				<div class="radius_10 overflow ka"><img src="<?=!empty($card['logo_url'])?$card['logo_url']:'';?>" alt=""></div>
			</div>
			
			 <div class="padding_top_25">
				<div class="flex between font_15">
					<div class=""><?=$card['title'];?></div>
					<div class="main_color1">
						<font class="margin_right_5">¥</font><em class="iconfonts font_21"><?=$card['money'];?></em>
					</div>
				</div>
				<div class="font_12 margin_top_13 color3"><?=$card['brand_name'];?></div>
			</div>
		</div>
		<div class="bd_top"></div>
		 <?php if(!empty($pay_type)):?>
		<section class="font_16">
			<div class="">
				<div class="width_109 center relative auto color_fff">
					支付方式
					<span class="shadow_b" style="display:block"></span>
				</div>
			</div>
			<div class="flex margin_top_35 pay_mode">
			<?php foreach ($pay_type as $key => $py):?>
			
				<div data-paytype="<?=$key?>" class=" <?php if($key == 'wechat'){?> layer_bg flex_1 center radius_3 pay_mode_item relative check_item <?php }else {?>margin_left_15 flex_1 layer_bg center radius_3 pay_mode_item relative <?php }?>" >
					<div class="check"><em></em></div>
					<div><em class="iconfont font_22"><?php if($key == 'wechat'){?>&#xe64e;<?php }else {?>&#xe640;<?php }?></em></div>
					<div class="margin_top_15"><font>  <?=$py?></font></div>
				</div>
				      <?php endforeach;?>
				</div>
		</section>
		        <?php endif;?>
		<section class="padding_bottom_77 margin_top_50">
			<div class="font_12 padding_left_20">
				<p class="color2 relative"><em class="iconfont absolute prompt">&#xe642;</em>使用说明</p>
				<p class="color3 margin_top_15"><?=$card['description'];?></p>
			</div>
		</section>
	</section>
	<section class="flex layer_bg fixed_btn font_17 color_fff">
		<div class="flex_1 padding_left_30 padding_17"><font class="font_12">¥</font><em><?=$card['money'];?></em></div>
		<div class="iconfont width_150 center main_bg1 padding_17">&#xe63b;&#xe63a;&#xe639;&#xe638;</div>
	</section>
</div>
</body>
</html>