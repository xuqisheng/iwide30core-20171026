
<script src="<?php echo base_url('public/mall/multi/script/imgscroll.js')?>"></script>
<script src="<?php echo base_url('public/mall/multi/script/jquery.touchwipe.min.js')?>"></script>
<link href="<?php echo base_url('public/mall/multi/style/goods_details.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/ui.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/im_buy.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/shopcar.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/imgscroll.css')?>" rel="stylesheet">
<title>立即购买</title>
<style>
body,html{ background:#fff;}
.headers{border-bottom:1px solid #ddd;}
</style>
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

<div class="pull" style="display:none; z-index:99; opacity:0.5"></div>
<form method="get" action="<?php echo site_url('mall/wap/noconfirm')?>">
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
                    <span class="wrong"><img src="<?php echo base_url('public/mall/multi/images/ico/wrong.png')?>"></span>
                    <p class="txtclip"><?php echo $details['gs_name']?></p>
                    <p class="text_det" style="margin-bottom:1%;"><?php echo $details['gs_desc']?></p>
                    <p class="float_r">库存: <?php echo $details['gs_nums']?></p>
                    <p><font class="ui_price"><?php echo $details['gs_wx_price']?></font></p>
                </div>
                <div style="clear:both"></div>
            </li>
            <li class="buy_num">
                <div class="addcount" style="margin-top:0">
                    <div class="down"><img src="<?php echo base_url('public/mall/multi/images/ico/down.png')?>" /></div>
                    <div class="num"><input type="tel" name="count" readonly value="1"></div>
                    <div class="add"><img src="<?php echo base_url('public/mall/multi/images/ico/add.png')?>" /></div>
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
<div class="floors">
    <a href="<?php echo site_url('mall/wap/cart')?>?id=<?php echo $inter_id?>&saler=<?php echo $saler?>&t=<?php echo $topic['identity'] ?>" class="shopping">
        <div class="car_1">
           <img src="<?php echo base_url('public/mall/multi/images/ico/car_1.png')?>" />
           <span class="numb"> <?php if($cart_count > 0):?><?php echo $cart_count?><?php else:?>0<?php endif;?></span>
        </div>
        <p>购物车</p>
    </a> 
    <div class="relative">
        <!--div class="fastbuy btns">秒杀暂未开始</div-->
        <div class="buy btns">立即购买</div>
        <div class="add btns" onclick="add_to_cart(<?php echo $details['gs_id']?>)">加入购物车</div>
    </div>
</div>

<div style="padding-top:12%;"></div>
</form>
</body>
<script src="<?php echo base_url('public/mall/multi/script/command.js')?>"></script>
<script>	
function add_to_cart(id){
	var curEle = $('.numb');
	var cur = parseInt(curEle.html());
	$.getJSON("<?php echo site_url('mall/wap/add_to_cart') ?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&pid="+id,function(datas){
		if(datas.errmsg == 'ok')curEle.html(cur + 1);
		else alert('添加失败');
	});
}
var cutdown_time;
function overtime( _start, _end){
	_start = new Date(_start);
	_end   = new Date(_end);
	minus  = _end-_start;
	var s =  minus/1000; 
	var m = 0;// 分
	var h = 0;// 小时
	if(s > 60){
		m = parseInt(s/60);
		s = parseInt(s%60);
		if(m > 60){
			h = parseInt(m/60);
			m = parseInt(m%60);
		}
	}
	if ( h>24){ $('.fastbuy').html('秒杀暂未开始'); return;}
	if (h<=0&&m<=0&&s<=0){ // 倒计时结束 
		$('.fastbuy').html('立即秒杀');
		return;
	}
	$('.fastbuy').html(h+':'+m+':'+s);
	cutdown_time= window.setInterval(function(){
		s--;
		if ( s<0 ){m--;s=59;if ( m<0 ){h--;m=59;}
			if(h<0 ){  // 倒计时结束 
				$('.fastbuy').html('立即秒杀');
				window.clearInterval(cutdown_time);
				return;
		}}
		$('.fastbuy').html(h+':'+m+':'+s);
	},10);
}
$(function(){
	$('.headers').height($('.headers').width());
	overtime('2015/12/24 19:20:12','2015/12/24 20:21:15');
	$('.fastbuy').click(function(){
		
	})
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
	$(".headers").touchwipe({
		 preventDefaultEvents: false
	});
})
</script>
</html>
