
<link href="<?php echo base_url('public/mall/multi/style/ui.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mail.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mailstatus.css')?>" rel="stylesheet">
<title><?php echo $title?></title>
</head>
<style>
body,html{background:#fff}
.color{ color:#ff5e5e}
.content,.buybtn{font-size:0}
.content,.content .item{ padding:0;}
.content .item,.content .item:last-child{width:50%;background:#fff; margin-bottom:0.1%; display:inline-block;border-bottom:1px solid #a6a6a6;box-sizing:border-box; border-right:1px solid #a6a6a6;}
.clips{word-break:break-all;overflow : hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;}
.hotelname,.desc{padding:0 5%; height:2.5em;}
.buybtn{width:96%; margin:2% 0;}
.buybtn,.buybtn span{ display:inline-block; background:#fff3ea;  overflow:hidden;vertical-align:middle}
.content .item .ui_price{ font-size:0.85rem;}
.buybtn .ui_price:before{ font-size:0.55rem}
.content .item .desc { white-space:normal; }
.buybtn span:first-child{width:59%; padding-left:5%;}
.buybtn span:last-child{width:36%; background:#ff5555; color:#fff;text-align:center; padding:5% 0;}
</style>
<body>
<div class="content">
<?php if( count($products)>0 ): foreach ($products as $v):?>
	<div class="item">
    	<a  href="<?php echo site_url('mall/wap/goods_buy/'.$v['gs_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>">
            <div class="item_img"><img src="<?php echo $v['gs_logo']; ?>" /></div>
            <div class="hotelname clips"><?php echo $v['gs_name']; ?></div>
            <div class="desc gray clips"><?php echo $v['gs_desc']; ?></div>
        </a>
        <a href="<?php echo site_url('mall/wap/goods_buy/'.$v['gs_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>" class="buybtn">
        	<span class="ui_price color"><?php echo $v['gs_wx_price']; ?></span>
            <span>立即购买</span>
        </a>
	</div>
<?php endforeach; else :?>
	<div class="ui_none">
    	<div>该分类下无商品~</div>
    </div>
<?php endif; ?>
</div>
</body>
</html>