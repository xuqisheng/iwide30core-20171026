<title><?php echo $title?></title>
<script src="<?php echo base_url('public/mall/multi/script/jquery.touchwipe.min.js')?>"></script>
<script src="<?php echo base_url('public/mall/multi/script/imgscroll.js')?>"></script>
<link href="<?php echo base_url('public/mall/multi/style/index.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/imgscroll.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/classify.css')?>" rel="stylesheet">
</head>
<body>
<header class="headers"> 
  <div class="headerslide">
  <?php foreach($advs as $ad): ?>
    <a class="slideson imgshow relative" href="<?php echo $shp_advs->render_base_anchor($ad, "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}"); ?>">
		<img src="<?php echo $ad['logo'] ?>">
    </a>
  <?php endforeach; ?>
  </div>
</header>

<div class="nav">
	<div class="nav_con">
        <ul class="nav_li">
		<?php $color= array('#74C901','#F147B5','#FE8701','#227dda', '#74C901','#F147B5','#FE8701','#227dda', );
		$i=4; foreach($categories as $v): $i--; if($i<1) break; ?><li>
                <a href="<?php echo site_url('mall/wap/plist/'. $v['cat_id']). '/'. "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>">
					<div class="fen_list"><img src="<?php echo $v['cat_img']; ?>"></div>
					<p><?php echo $v['cat_name'] ?></p>
				</a>
            </li><?php endforeach;?>
            <li><a href="<?php echo site_url('mall/wap/plist/all/'). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>">
					<div class="fen_list"><img src="<?php echo base_url('public/mall/common/cat_img/all.png'); ?>"></div>
					<p>全部分类</p>
				</a>
            </li>
        </ul>
    </div>
</div>
<!--
<div class="special">
	<div class="special_con">
		<a><img src="<?php echo base_url('public/mall/multi/attachment/special_1.png')?>"></a>
        <p class="s_txt">专题活动</p>
    </div>
</div>
-->
<div class="rankimg">
	<a href="<?php echo site_url('mall/wap/plist'). '/all/sales/'.  "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" class="more r_title">更多</a>
	<p class="r_title">畅销排行</p>
    <div class="r_con" style="overflow:hidden">
    	<ul class="rankimg_li"><?php $num= count($sales)>4?4:count($sales); $i= round($num/2)*2; 
				foreach($sales as $v): $i--; if($i<0) break; ?><li>
                <a href="<?php echo site_url('mall/wap/goods_buy/'. $v['gs_id']). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" >
                    <img class="goods" src="<?php echo $v['gs_logo']?>" />
                    <p class="t_top txtclip"><?php echo $v['gs_name']?></p>
                    <p class="g_na txtclip"><?php echo $v['gs_desc']?></p>
                </a> 
            </li><?php endforeach;?>
        </ul>
    </div>
</div>
<div class="rankimg">
	<a href="<?php echo site_url('mall/wap/plist'). '/all/promo/'.   "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" class="more r_title">更多</a>
	<p class="r_title">促销商品</p>
    <div class="r_con" style="overflow:hidden">
    	<ul class="rankimg_li"><?php $num= count($promo)>4?4:count($promo); $i= round($num/2)*2; 
				foreach($promo as $v): $i--; if($i<0) break; ?><li>
                <a href="<?php echo site_url('mall/wap/goods_buy/'. $v['gs_id']). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" >
                    <img class="goods" src="<?php echo $v['gs_logo']?>" />
                    <p class="t_top txtclip"><?php echo $v['gs_name']?></p>
                    <p class="g_na txtclip"><?php echo $v['gs_desc']?></p>
                </a> 
            </li><?php endforeach;?>
        </ul>
    </div>
</div>

<!-- 循环各普通分类 -->
<?php 
foreach( $cat_list as $ck=> $cv): 
$cat_tmp= $cv['products']; 
if(count($cat_tmp)>0): 
?>
<div class="rankimg">
	<a href="<?php echo site_url('mall/wap/plist/'. $cv['cat_id']).  "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" class="more r_title">更多</a>
	<p class="r_title"><?php echo $cv['cat_name'] ?></p>
    <div class="r_con" style="overflow:hidden">
    	<ul class="rankimg_li"><?php $num= count($cat_tmp)>4? 4: count($cat_tmp); $i= round($num/2)*2; 
				foreach($cat_tmp as $v): $i--; if($i<0) break; ?><li>
                <a href="<?php echo site_url('mall/wap/goods_buy/'. $v['gs_id']). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" >
                    <img class="goods" src="<?php echo $v['gs_logo']?>" />
                    <p class="t_top txtclip"><?php echo $v['gs_name']?></p>
                    <p class="g_na txtclip"><?php echo $v['gs_desc']?></p>
                </a> 
            </li><?php endforeach;?>
        </ul>
    </div>
</div>
<?php endif; endforeach; ?>


<div class="l_b_btn radius">
	<a href="<?php echo site_url('mall/wap/cart'). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" class="car"></a>
	<a href="<?php echo site_url('mall/wap/my_orders'). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" class="person"></a>
</div>
<div style="padding-top:15%"></div>
</body>
<script>
$(function(){
	if ($('.nav_li').find('li').length<=3){
		$('.nav').hide();
	}
});
</script>
</html>
