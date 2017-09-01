
<link href="<?php echo base_url('public/mall/multi/style/ui.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mail.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mailstatus.css')?>" rel="stylesheet">
<title><?php echo $title?></title>
</head>
<style>
.content{ background:#fff;border-bottom:1px solid #e4e4e4;}
.content .item .itemimg{ width:3rem; height:3rem;}
.content .item .cardid { margin-top:2%;}
.hotelname{ overflow:hidden; text-overflow:ellipsis; white-space:nowrap}
</style>
<body>
<div class="content">
<?php if( count($products)>0 ): foreach ($products as $v):?>
	<a href="<?php echo site_url('mall/wap/goods_buy/'.$v['gs_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>" class="item">
        <div class="itemimg img_auto_cut"><img src="<?php echo $v['gs_logo']; ?>" /></div>
        <div class="hotelname"><?php echo $v['gs_name']; ?></div>
        <div class="desc gray"><?php echo $v['gs_desc']; ?></div>
        <span class="ui_price color"><?php echo $v['gs_wx_price']; ?></span>
	</a>
<?php endforeach; else :?>
	<div class="ui_none">
    	<div>该分类下无商品~</div>
    </div>
<?php endif; ?>
</div>
</body>
</html>