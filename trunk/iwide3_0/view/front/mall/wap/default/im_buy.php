
<script src="<?php echo base_url('public/mall/multi/script/imgscroll.js')?>"></script>
<script src="<?php echo base_url('public/mall/multi/script/jquery.touchwipe.min.js')?>"></script>
<link href="<?php echo base_url('public/mall/multi/style/goods_details.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/ui.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/im_buy.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/shopcar.css')?>" rel="stylesheet">
<title>立即购买</title>
</head>
<body>
<div class="headers"> 
  <div class="headerslide">
  	  <a class="slideson imgshow relative" href="#">
    	<img src="<?php echo $details['gs_logo']?>">
      </a>
  </div>
</div><!-- headers---end--->
<div class="intro_detail">
    <div class="b_txt">
        <font class="ui_price"><?php echo $details['gs_wx_price']?></font>
        <p><?php echo $details['gs_name']?></p>
        <p class="txt_details"><?php echo $details['gs_desc']?></p>
    </div>
</div>
<div class="detail_txt"><?php echo count($details['ext_attr'])>0? $details['ext_attr'][0]['attr_value'] : ''?></div>

<!--
<div class="floors">
    <div class="shopping">
        <div class="car_1">
            <img src="<?php echo base_url('public/mall/default/images/ico/car_1.png')?>"/>
            <span class="numb">50</span>
        </div>
        <p>购物车</p>
    </div>
    <div class="add btns">加入购物车</div>
    <div class="buy btns">立即购买</div>
</div>
-->
<div class="buy btns confirm" style="position:fixed; bottom:0">立即购买</div>
<div class="pull" style="display:none; z-index:99; opacity:0.5"></div>
<form method="post" action="<?php echo site_url('mall/wap/noconfirm')?>" >
	<input type="hidden" name="key" value="<?php echo $details['gs_id'] ?>" />
	<input type="hidden" name="id" value="<?php echo $inter_id ?>" />
	<input type="hidden" name="t" value="<?php echo $topic['identity'] ?>" />
	<input type="hidden" name="saler" value="<?php echo $saler?>" />
	<input type="hidden" name="f" value="<?php echo $fans_id?>" />
<div class="con">
	<div class="c_detail">
    	<ul>
        	<li>
            	<div class="name_img">
                	<img src="<?php echo $details['gs_logo']?>"/>
                </div>
                 
                <div class="name_con">
                    <span class="wrong"><img src="<?php echo base_url('public/mall/default/images/ico/wrong.png')?>"></span>
                    <p class="txtclip"><?php echo $details['gs_name']?></p>
                    <p class="text_det" style="margin-bottom:1%;"><?php echo $details['gs_desc']?></p>
                    <p class="float_r">库存: <?php echo $details['gs_nums']?></p>
                    <p><font class="ui_price"><?php echo $details['gs_wx_price']?></font></p>
                </div>
                <div style="clear:both"></div>
            </li>
            <li class="buy_num">
                <div class="addcount" style="margin-top:0">
                    <div class="down"><img src="<?php echo base_url('public/mall/default/images/ico/down.png')?>" /></div>
                    <div class="num"><input type="tel" name="count" readonly value="1"></div>
                    <div class="add"><img src="<?php echo base_url('public/mall/default/images/ico/add.png')?>" /></div>
                </div>
            	<dd style="padding-top:1%">购买数量</dd>
            </li>
            <!--<li class="pay">
            	<p>支付方式</p>
                <p class="btn2">
                	<span class="active">微信支付</span>
                	<span>支付宝支付</span>
                </p>
            </li>
            <li class="pay">
            	<p>购买类型</p>
                <p class="btn2">
                	<span class="active">送给朋友</span>
                	<span>自己购买</span>
                </p>
            </li>
            <li class="voucher">
            	代金券<font>请选择</font>
            </li>-->
        </ul>
        <p class="note"><?php if( isset($topic['shipping_desc']) && $topic['shipping_desc'] ):
    	       echo $topic['shipping_desc'];
    	    else: ?>全国包邮；港澳台、新疆、西藏除外<?php endif; ?></p>
    </div>
	<button type="submit" class="confirm">确定</button>
</div>
</form>
<div style="padding-top:12%;"></div>
</body>
<script src="<?php echo base_url('public/mall/default/script/command.js')?>"></script>
<script>
imgrate=1;
$(function(){
	$('.buy').click(function(){
		$('.con').stop().animate({bottom:'0'},function(){
			$('.pull').fadeIn();
			of();
		});
	});
	$('.wrong').click(function(){
		$('.con').stop().animate({bottom:'-100%'},function(){
			$('.pull').fadeOut();
			none_of();
		});
	});
	$('.pull').click(function(){
		$('.con').stop().animate({bottom:'-100%'},function(){
			$('.pull').fadeOut();
			none_of();
		});
	});
	$('.num input').change(function(){
		var price = $('.con .ui_price').html();
		$('.con .ui_price').html(price*$(this).val())
	});
})
</script>
</html>
