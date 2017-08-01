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
<meta name="viewport"
	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui">
<!-- 全局控制 -->
<link rel="stylesheet"
	href="<?php echo base_url("public/member/highclass/css/global.css")?> "
	type="text/css">
<link rel="stylesheet"
	href="<?php echo base_url("public/member/highclass/css/mycss.css")?>"
	type="text/css">
<script
	src="<?php echo base_url("public/member/highclass/js/jquery.js")?>"></script>
<script
	src="<?php echo base_url("public/member/highclass/js/myjs.js")?>"></script>
<title><?php echo $inter_id=='a491796658'? '绑定':'登陆'?></title>
</head>
<body>
	<div class="gradient_bg padding_35">
		<section class="padding_0_15">
			<form class="form_list font_14">
		       <?php if(!empty($login_config) && is_array($login_config)):?>
            <?php foreach ($login_config as $key=>$vo):?>
                <?php if($vo['show']=='1' && $key!='phonesms'):?>
              
              <div class="flex form_item bd_bottom padding_18">
					<div class="margin_right_42 width_120">
						<div class="flex between">
						<?=$vo['name'];?>
					</div>
					</div>
					<div class="flex_1">
						<input
							pattern="<?php if($vo['check']=='1'):?><?=$vo['regular'];?><?php endif;?>"
							placeholder="<?=$vo['note'];?>" type="<?=$vo['type'];?>"
							name="<?=$key?>" data-name="<?php echo $vo['name'];?>"
							data-check="<?php echo $vo['check']; ?>" />
					</div>
				</div>
                <?php endif;?>
			                <?php if($vo['show']=='1' && $key=='phonesms'):?>
						<div class="flex form_item bd_bottom padding_18">
					<div class="margin_right_42 width_120 block">
						<div class="flex between">
							<span class="block">验</span> <span class="block">证</span> <span
								class="block">码</span>
						</div>
					</div>
					<div class="flex_1">
					<input type="<?=$vo['type'];?>" pattern="<?php if($vo['check']=='1'):?><?=$vo['regular'];?><?php endif;?>" placeholder="<?=$vo['note'];?>" name="<?=$key?>" data-name="<?php echo $vo['name'];?>" data-check="<?php echo $vo['check']; ?>" /></div>

					<div class="relative border_1_808080 verification">获取验证码</div>
				</div>
			                <?php endif;?>
			       <?php endforeach;?>
        <?php endif;?>

			<div class="margin_top_35 font_17">
					<a
						class="block width_85 center padding_15 auto iconfont entry_btn land_btn">&#xe607;&ensp;&#xe610;</a>
				</div>
				<div class="center margin_top_30">
					<a class="font_12 " href="<?php echo base_url("index.php/membervip/reg");?>">没有账号？ <span
						class="main_color1">马上注册</span><em class="iconfont font_12">&#xe61c;</em></a>
				</div>
			</form>
			<div
				class="flex layer_bg padding_20 radius_3 margin_top_40 centers padding_0_23">
				<div class="margin_right_23">
					<em class="iconfont main_color1 txt_show3">&#xe62a;</em>
				</div>
				<div class="font_12">
					注册可获得注册<font class="main_color1">大礼包</font>,享受更多<font
						class="main_color1">会员优惠</font>!
				</div>
			</div>
		</section>
	</div>
</body>
</html>