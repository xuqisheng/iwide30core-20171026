<link href="<?php echo base_url('public/mall/multi/style/ui.css')?>" rel="stylesheet">
<script src="<?php echo base_url('public/mall/multi/script/ui_control.js')?>"></script>
<script src="<?php echo base_url('public/mall/multi/script/shanNan.js')?>"></script>
<script src="<?php echo base_url('public/mall/default/script/fenSan.js')?>"></script>
<link href="<?php echo base_url('public/mall/default/style/friends.css')?>?v=<?php echo time()?>" rel="stylesheet">
<title>朋友圈</title>
</head>
<body>
<div class="box">
	<div class="book img_auto_cut"><img src="<?php echo $comments['order_info']['items'][0]['gs_logo']?>"/></div>
	<div class="tit cen"><h2><?php echo $comments['order_info']['items'][0]['gs_name']?></h2></div>
	<div class="t_txt cen"> <?php echo $comments['order_info']['items'][0]['gs_desc']?></div>
	<div class="money cen"> ¥<?php echo $comments['order_info']['items'][0]['price']?></div>
	<div class="pin_lu">
		<font><?php echo $comments['nickname']?>：</font>
		<span><?php echo $comments['contents']?></span>
	</div>
	<?php if($is_mine):?><div class="f_btn">
		<a class="fenSan_b" href="#"><div class="btn fen_b">立即分享</div></a>
	</div><?php endif;?>
	<div class="f_btn">
		<a href="<?php echo site_url('mall/wap/index')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>"><div class="btn a_btn">立即购买</div></a>
	</div>
    <?php if($is_mine):?><div class="floot_txt">
    	<p>分享到朋友圈，让更多的朋友看到您的心得哦！更有机会获得一个随机红包奖励</p>
    </div>
</div>
<div class="fix">
    	<p class="a_img"><img src="<?php echo base_url('public/mall/default/images/arrow.png')?>"/></p>
        <p class="a_txt">点击并发送给朋友</p>
</div><?php endif;?>
</body>
</html>

