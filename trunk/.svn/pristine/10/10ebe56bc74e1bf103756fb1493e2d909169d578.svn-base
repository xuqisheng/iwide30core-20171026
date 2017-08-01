
<script src="<?php echo base_url('public/mall/multi/script/jquery.touchwipe.min.js')?>"></script>
<link href="<?php echo base_url('public/mall/multi/style/goods_details.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/ui.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/im_buy.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/shopcar.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/card.css')?>" rel="stylesheet">
<title>立即购买</title>
<style>
body,html{ background:#fff;}
.headers{border-bottom:1px solid #ddd; overflow:hidden}
.floors { border-top:1px solid #e4e4e4; overflow:hidden}
.floors .add { float: none; background: none;}
.addcount { font-size:0 !important; float:left; margin-top:3%; width:5rem;}
.addcount > div {padding: 0.2rem 0.4rem;}
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
        <p><?php echo $details['gs_name']?>［自动发货］</p>
        <p class="txt_details"><?php echo $details['gs_desc']?></p>
        <p class="float_r"><!--已送出2345份--></p>
        <font class="ui_price"><?php echo $details['gs_wx_price']?></font>
        <del class="ui_price"><?php echo $details['gs_market_price']?></del>
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
<div class="floors">
    <div class="addcount">
        <div class="down"><img src="<?php echo base_url('public/mall/multi/images/ico/down.png')?>" /></div>
        <div class="num"><input type="tel" name="count" readonly value="1"></div>
        <div class="add"><img src="<?php echo base_url('public/mall/multi/images/ico/add.png')?>" /></div>
    </div>
	<button type="submit" class="buy btns">立即购买</button>
    <div class="float_r">合计 <span class="sum"><?php echo $details['gs_wx_price']?></span>&nbsp;&nbsp;</div>
</div>

<div style="padding-top:12%;"></div>
</form>
</body>
<script src="<?php echo base_url('public/mall/multi/script/command.js')?>"></script>
<script>	
function add_to_cart(id){
	var curEle = $('.numb');
	var cur = parseInt(curEle.html());
	$.getJSON("<?php echo site_url('mall/wap/add_to_cart'). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&pid="; ?>" +id,function(datas){
		if(datas.errmsg == 'ok')curEle.html(cur + 1);
		else alert('添加失败');
	});
}
$(function(){
	$('.headers').height($('.headers').width());
	$('.buy').click(function(e){
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
	$('.add').on('touchstart',function(e){
		var stock=<?php echo $details['gs_nums']?>;  // 库存
		if ($('.num input').val()>stock || $('.num input').val()>10)
			$('.num input').val($('.num input').val()-1)
		var tmp= $('font.ui_price').html();
		var sum= parseFloat($('.num input').val()*tmp);
		$('.sum').html(sum.toFixed(2));
		e.preventDefault();
	});
	$('.down').on('touchstart',function(e){
		var tmp= $('font.ui_price').html();
		var sum= parseFloat($('.num input').val()*tmp);
		$('.sum').html(sum.toFixed(2));
		e.preventDefault();
	});
})
</script>
</html>
