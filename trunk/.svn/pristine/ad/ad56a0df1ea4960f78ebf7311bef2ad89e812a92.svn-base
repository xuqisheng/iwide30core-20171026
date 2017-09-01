
<script src="<?php echo base_url('public/mall/multi/script/imgscroll.js')?>"></script>
<script src="<?php echo base_url('public/mall/multi/script/jquery.touchwipe.min.js')?>"></script>
<link href="<?php echo base_url('public/mall/multi/style/index3.css')?>" rel="stylesheet">
<title><?php echo $title?></title>
<style>
.imgorderbtn{position:fixed; left:5%; bottom:5%; width:20%; display:block; }
</style>
</head>
<body>
<header class="headers"> 
	<div class="headerslide">
		<?php foreach($advs as $ad): ?>
		<a class="slideson imgshow relative" href="<?php echo $shp_advs->render_base_anchor($ad, "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}"); ?>">
			<img src="<?php echo $ad['logo'] ?>">
		</a><?php endforeach;?>
	</div>
</header>

<div class="list">
	<?php foreach ($goods as $item):?>
	<a href="<?php echo site_url('mall/wap/goods_buy/'.$item['gs_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>" class="item">
    	<div class="name"><?php echo $item['gs_name']?></div>
        <div class="intro"><?php echo $item['gs_desc']?></div>
        <div class="blue">购买</div>
        <div class="imgbox"><img src="<?php echo $item['gs_logo']?>"></div>
    </a><?php endforeach;?>
</div>

<div style="padding-top:10%;">
    <a href="<?php echo site_url('mall/wap/my_orders/'.$item['gs_id']). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" >
        <img style="position:fixed; left:5%; bottom:5%; width:20%" src="<?php echo base_url('public/mall/multi/images/orderbtn.png')?>">
    </a>
</div>
</body>
</html>
