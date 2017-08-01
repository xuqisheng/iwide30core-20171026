<title><?php echo $title?></title>
<script src="<?php echo base_url('public/mall/multi/script/jquery.touchwipe.min.js')?>"></script>
<script src="<?php echo base_url('public/mall/multi/script/imgscroll.js')?>"></script>
<link href="<?php echo base_url('public/mall/multi/style/index.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/classify.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/card.css')?>" rel="stylesheet">
</head>
<body>
<header class="headers"> 
  <div class="headerslide">
  <?php foreach($advs as $ad): ?>
    <a class="slideson imgshow relative" href="<?php echo $ad['link'] ?>">
		<img src="<?php echo $ad['logo'] ?>">
    </a>
  <?php endforeach; ?>
  </div>
</header>
<?php /** 
<div class="rankimg">
	<a href="<?php echo site_url('mall/wap/plist'). '/all/news/'.  "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" class="more r_title">更多</a>
	<p class="r_title"><span>首发新品</span></p>
    <div class="r_con" style="overflow:hidden">
    	<ul class="rankimg_li"><?php $num= count($news)>4?4:count($news); $i= round($num/2)*2; 
				foreach($news as $v): $i--; if($i<0) break; ?><li>
                <a href="<?php echo site_url('mall/wap/goods_buy/'. $v['gs_id']). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" >
                    <img class="goods" src="<?php echo $v['gs_logo']?>" />
                    <p class="t_top"><?php echo $v['gs_name']?></p>
                    <p class="price">
                        <span><?php echo $v['gs_wx_price']?></span>
                        <del><?php echo $v['gs_market_price']?></del>
                    </p>
                </a> 
            </li><?php endforeach;?>
        </ul>
    </div>
</div>
<div class="rankimg">
	<a href="<?php echo site_url('mall/wap/plist'). '/all/sales/'.   "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" class="more r_title">更多</a>
	<p class="r_title"><span>热卖商品</span></p>
    <div class="r_con" style="overflow:hidden">
    	<ul class="rankimg_li"><?php $num= count($sales)>4?4:count($sales); $i= round($num/2)*2; 
				foreach($sales as $v): $i--; if($i<0) break; ?><li>
                <a href="<?php echo site_url('mall/wap/goods_buy/'. $v['gs_id']). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" >
                    <img class="goods" src="<?php echo $v['gs_logo']?>" />
                    <p class="t_top"><?php echo $v['gs_name']?></p>
                    <p class="price">
                        <span><?php echo $v['gs_wx_price']?></span>
                        <del><?php echo $v['gs_market_price']?></del>
                    </p>
                </a> 
            </li><?php endforeach;?>
        </ul>
    </div>
</div>
*/?>
<!-- 循环各普通分类 -->
<?php 
foreach( $cat_list as $ck=> $cv): 
$cat_tmp= $cv['products']; 
if(count($cat_tmp)>0): 
?>
<div class="rankimg">
	<a href="<?php echo site_url('mall/wap/plist/'. $cv['cat_id']).  "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" class="more r_title">更多</a>
	<p class="r_title"><span><?php echo $cv['cat_name'] ?></span></p>
    <div class="r_con" style="overflow:hidden">
    	<ul class="rankimg_li"><?php $num= count($cat_tmp)>4? 4: count($cat_tmp); $i= round($num/2)*2; 
				foreach($cat_tmp as $v): $i--; if($i<0) break; ?><li>
                <a href="<?php echo site_url('mall/wap/goods_buy/'. $v['gs_id']). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" >
                    <img class="goods" src="<?php echo $v['gs_logo']?>" />
                    <p class="t_top "><?php echo $v['gs_name']?></p>
                    <p class="price">
                        <span><?php echo $v['gs_wx_price']?></span>
                        <del><?php echo $v['gs_market_price']?></del>
                    </p>
                </a> 
            </li><?php endforeach;?>
        </ul>
    </div>
</div>
<?php endif; endforeach; ?>


<div class="l_b_btn radius">
	<a href="<?php echo site_url('mall/wap/my_orders'). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" class="person"></a>
</div>
</body>
<script>
$(function(){
	if ($('.nav_li').find('li').length<=3){
		$('.nav').hide();
	}
});
</script>
</html>
