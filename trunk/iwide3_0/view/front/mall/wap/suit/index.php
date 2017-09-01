
<script src="<?php echo base_url('public/mall/multi/script/jquery.touchwipe.min.js')?>"></script>
<script src="<?php echo base_url('public/mall/multi/script/imgscroll.js')?>"></script>
<link href="<?php echo base_url('public/mall/multi/style/index.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/classify.css')?>" rel="stylesheet">
<style>
body,html{background:#d5d5d5}
.nav,.rankimg { margin-bottom:1.5%}
.rankimg .r_con {width:100%;}
.rankimg .r_title { color:#414141;line-height:normal; padding:2% 0}
.rankimg a.r_title {color:#dd8ecb}
.rankimg .r_con ul li { width: 49.7%; float: left;padding:0;border:0; margin-top:0.6%; overflow:hidden}
.rankimg .r_con ul li:nth-child(2n){ float:right;}
.rankimg .r_con ul li a{height: 4rem;}
.rankimg .r_con ul li a .goods {width:100%; height:auto;min-height: 4rem;float: none;}
.suit,.suit li{font-size:0}

.l_b_btn{background:rgba(0,0,0,0.8); left:auto;right:0; border-radius:0.3rem 0 0 0.3rem;}
.l_b_btn a{ float:none;}
.l_b_btn a:first-child{ border-right:0;border-bottom:1px solid rgba(255,255,255,0.4)}
</style>
<header class="headers"> 
  <div class="headerslide">
  <?php foreach($advs as $ad): ?>
      <a class="slideson imgshow relative" href="<?php echo $shp_advs->render_base_anchor($ad, "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}"); ?>">
          <img src="<?php echo $ad['logo'] ?>" /></a>
  <?php endforeach; ?>
  </div>
</header>
<div class="nav">
	<div class="nav_con">
        <ul class="nav_li">
		<?php $i=4; foreach($categories as $v): $i--; if($i<1) break; ?>
        	<li>
           	  <a href="<?php echo site_url('mall/wap/plist/'.$v['cat_id']).'/'. "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>">
					<div class="fen_list"><img src="<?php echo $v['cat_img']; ?>"></div>
					<p><?php echo $v['cat_name'] ?></p>
				</a>
            </li><?php endforeach;?>
            <li><a href="<?php echo site_url('mall/wap/plist/all/'). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>">
					<div class="fen_list"><img src="<?php echo base_url('public/mall/common/cat_img/all.png'); ?>"></div>
					<p>全部商品</p>
				</a>
            </li>
        </ul>
    </div>
</div>
<div class="rankimg">
	<a href="<?php echo site_url('mall/wap/plist'). '/all/sales/'.  "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" class="more r_title">更多</a>
	<p class="r_title">热门商品</p>
    <div class="r_con" style="overflow:hidden">
    	<ul class="rankimg_li">
			<?php foreach($advs2 as $ad): ?><li>
                <a href="<?php echo $shp_advs->render_base_anchor($ad, "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}"); ?>" >
                    <img class="goods" src="<?php echo $ad['logo']; ?>" />
                </a>
            </li><?php endforeach;?>
        </ul>
    </div>
</div>

<ul class="rankimg suit">
	<?php foreach($advs3 as $ad): ?>
    <li><a href="<?php echo $shp_advs->render_base_anchor($ad, "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}"); ?>" >
		<img src="<?php echo $ad['logo']; ?>" /></a>
    </li>
	<?php endforeach;?>
</ul>

<div class="l_b_btn radius">
	<a href="<?php echo site_url('mall/wap/cart'). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" class="car"></a>
	<a href="<?php echo site_url('mall/wap/my_orders'). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" class="person"></a>
</div>
</body>
<script>
//轮播图片比例
imgrate=640/400;  
</script>
</html>
