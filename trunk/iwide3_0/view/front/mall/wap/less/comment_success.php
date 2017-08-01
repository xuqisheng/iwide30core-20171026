<link href="<?php echo base_url('public/mall/multi/style/ui.css')?>" rel="stylesheet">
<script src="<?php echo base_url('public/mall/multi/script/ui_control.js')?>"></script>
<link href="<?php echo base_url('public/mall/multi/style/publish.css')?>" rel="stylesheet">
<title>发表成功</title>
<style>
.form{width:900px;margin:auto;background:red;}
</style>
</head>
<body>
<div class="success">
	<div class="logo img_auto_cut"><img src="<?php echo base_url('public/mall/multi/images/ico/ico_09.png')?>"/></div>
    <p class="p_s">发表成功</p>
	<div class="txt">分享到朋友圈，让更多的朋友看到您的心得哦！ 更有机会获得一个随机红包奖励</div>
	<a href="<?php echo site_url('mall/wap/index')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>"><p class="t_ali">再买一次 </p></a>
</div>
</body>
<script type="text/javascript">
window.onload=function(){
	//setTimeout(function(){window.location.href="";},3000)
}
</script>
</html>

