<link href="<?php echo base_url('media/'.$theme.'/style/goods_details.css')?>" rel="stylesheet">
<title>商品详情</title>
</head>
<body>
<div class="banner">
    <div class="ban_con"><img src="<?php echo $details['gs_logo']?>"/></div>
    <div class="b_txt">
        <font>¥<?php echo $details['gs_wx_price']?></font>
        <p><?php echo $details['gs_name']?></p>
        <p class="txt_details"><?php echo $details['gs_desc']?></p>
    </div>
</div>
<div class="banner"><?php echo $details['ext_attr'][0]['attr_value']?>
</div>

<div class="floors">
    <a href="<?php echo site_url('mall/wap/cart')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>" class="shopping">
        <div class="car_1">
            <img src="<?php echo base_url('media/'.$theme.'/images/ico/car_1.png')?>"/>
           <span class="numb"> <?php if($cart_count > 0):?><?php echo $cart_count?><?php else:?>0<?php endif;?></span>
        </div>
        <p>购物车</p>
    </a>
    <div class="add btns" onclick="add_to_cart(<?php echo $details['gs_id']?>)">加入购物车</div>
    <a href="<?php echo site_url('mall/wap/goods_buy/'.$details['gs_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>" class="buy btns">立即购买</a>
</div>
<div style="height:2.3rem"></div>
</body>
</html>
<script>
function add_to_cart(id){
	var curEle = $('.numb');
	var cur = parseInt(curEle.html());
	$.getJSON("<?php echo site_url('mall/wap/add_to_cart') ?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&pid="+id,function(datas){
		if(datas.errmsg == 'ok')curEle.html(cur + 1);
		else alert('添加失败');
	});
}
</script>