
<title><?php echo $title; ?></title>
<link href="<?php echo base_url('public/mall/default/style/wx_index.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/default/style/index.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/default/style/banner.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/default/style/imgscroll.css')?>" rel="stylesheet">
</head>
<style>
.myorder{position:absolute; bottom:3%; left:2%; width:12.5%}
.body_1.newbg{background-image:url('<?php echo $topic["theme_image"] ?>');}
html,body{background:<?php echo $topic['theme_color'] ?> !important;}
</style>
<body>
<?php if(isset($goods[0])): ?>
	<a href="<?php echo site_url('mall/wap/goods_buy'). '/'. $goods[0]['gs_id'] ?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>" class="body_1 newbg"></a>
<?php endif; ?>
<a href="<?php echo site_url('mall/wap/my_orders') ?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>" class="myorder"><img src="<?php echo base_url('public/mall/default/images/orderbtn.png')?>"></a>
</body>
</html>
