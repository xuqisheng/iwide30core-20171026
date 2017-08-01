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
	    <script src="<?php echo base_url("public/member/phase2/scripts/ui_control.js"); ?>"></script>
	        <link href="<?php echo base_url("public/member/phase2/styles/global.css"); ?>" rel="stylesheet">
    <link href="<?php echo base_url("public/member/phase2/styles/mycss.css"); ?>" rel="stylesheet">
        <script src="<?php echo base_url("public/member/phase2/scripts/alert.js");?>"></script>
	        <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<title>个人信息</title>
</head>
<body>
	<div class="gradient_bg padding_35">
		<section class="padding_0_15">
			<form class="form_list font_14" id="SaveMemberInfo"
				action="<?php echo base_url("index.php/membervip/perfectinfo/save");?>"
				method="post">
		<?php if($modify_config['name']['show']){ ?>
			<div class="flex form_item bd_bottom padding_18">
					<div class="margin_right_42 width_120"><?php echo $modify_config['name']['name']; ?></div>
					<div class="flex_1">
						<input class="font_14"
							type="<?php echo $modify_config['name']['type']; ?>"
							value="<?php if($centerinfo['name']!='微信用户'){echo $centerinfo['name'];} ?>"
							name="name" disabled="disabled"
							pattern="<?php echo $modify_config['name']['regular']; ?>"
							  />
					</div>
				</div>
			<?php }?>
					<?php if($modify_config['sex']['show']){ ?>
			<div class="flex form_item bd_bottom padding_18">
					<div class="margin_right_42 width_120">
						<div class="flex between">
							<span class="block">性</span> <span class="block">别</span>
						</div>
					</div>
					<div class="flex_1 bg_arrow">
						<select class="font_15" name="sex" disabled="disabled">
							<option
								<?php if($centerinfo['sex']=="3" ||$centerinfo['sex']=="3"){ echo 'selected'; } ?>
								value="3">-</option>
							<option <?php if($centerinfo['sex']=="2"){ echo 'selected'; } ?>
								value="2">女</option>
							<option <?php if($centerinfo['sex']=="1"){ echo 'selected'; } ?>
								value="1">男</option>
						</select>
					</div>
				</div>
				<?php }?>
						<?php if($modify_config['birthday']['show']){ ?>
				<div class="flex form_item bd_bottom padding_18">
					<div class="margin_right_42 width_120"><?php echo $modify_config['birthday']['name']; ?></div>
					<div class="flex_1  bg_arrow">
						<input name="birthday" class="weui_input diydate" type="date"
							type="text"
							value="<?php echo date('Y-m-d', $centerinfo['birth'] ); ?>" disabled="disabled"
							pattern="<?php echo $modify_config['birthday']['regular']; ?>" />
					</div>
				</div>
				        <?php }?>
				        		<?php if($modify_config['idno']['show']){ ?>
				<div class="flex form_item bd_bottom padding_18">
					<div class="margin_right_42 width_120 block">身份证号</div>
					<div class="flex_1">
						<input type="<?php echo $modify_config['idno']['type']; ?>"
							value="<?php echo $centerinfo['id_card_no'] ?>" disabled="disabled"
							pattern="<?php echo $modify_config['idno']['regular']; ?>"
							name="idno" />

					</div>
				</div>
				<?php }?>
						<?php if($modify_config['email']['show']){ ?>
				<div class="flex form_item bd_bottom padding_18">
					<div class="margin_right_42 width_120">
						<div class="flex between">
							<span class="block">邮</span> <span class="block">箱</span> <span
								class="block">地</span> <span class="block">址</span>
						</div>
					</div>
					<div class="flex_1">
						<input type="<?php echo $modify_config['email']['type']; ?>"
							value="<?php echo $centerinfo['email'] ?>" disabled="disabled"
							pattern="<?php echo $modify_config['email']['regular']; ?>"
							name="email" />

					</div>
				</div>
				<?php }?>
				        <?php if($modify_config['phone']['show']){ ?>
				<div class="flex form_item bd_bottom padding_18">
					<div class="margin_right_42 width_120">
						<div class="flex between">
							<span class="block">手</span> <span class="block">机</span> <span
								class="block">号</span> <span class="block">码</span>
						</div>
					</div>
					<div class="flex_1">
						<input type="<?php echo $modify_config['phone']['type']; ?>"
							value="<?php echo $centerinfo['cellphone'] ?>" name="phone" disabled="disabled"
							pattern="<?php echo $modify_config['phone']['regular']; ?>"
						 />

					</div>
				</div>
				<?php }?>
				<?php if ($centerinfo['is_login']=='t'){?>
				<div class="margin_top_75 font_17">
					<a class="block width_85 center padding_15 auto iconfont entry_btn" href="<?php echo base_url("index.php/membervip/perfectinfo/index");?>"
						>&#xe635;&#xe636;&#xe634;&#xe637;</a>
				</div>
				<div class="center margin_top_30">
					<a class="font_12 sign_btn " href="#"><span class="main_color1  ">退出登录</span></a>
				</div>
				<?php }?>
			</form>
		</section>
	</div>
	<script type="text/javascript">
    //通用JS
    $(function () {
        /* OUTLOGIN START */
        $('.sign_btn').click(function () {
            pageloading();
            $.post("<?php echo base_url("index.php/membervip/login/outlogin");?>",
                function (result) {
                    removeload();
                    if (!result) {
                        $.MsgBox.Alert('请求失败,请刷新重试或联系管理员!');
                        return false;
                    }
                    if (result.err > 1) {
                         $.MsgBox.Alert(result['msg']);
                    } else if (result.err == '0') {
                        var locat_url = "<?php echo base_url('index.php/membervip/center');?>";
                        $.MsgBox.Confirm(result.msg,function(){window.location.href=locat_url;});
						$('#mb_btn_no').remove();
                    }
                }, "json");
        })
        /* OUTLOGIN NED */
    });

</script>
</body>
</html>