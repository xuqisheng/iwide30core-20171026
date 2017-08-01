<link href="<?php echo base_url('public/mall/default/style/pay_success.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/default/style/footer.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/default/style/feeling.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/default/style/shaking.css')?>" rel="stylesheet">
<title>点击礼盒领取礼物</title>
</head>
<body> 
<!-- 蒙版 -->
<div class="pull pullgift" style="text-align:center; background:rgba(53,57,60,0.9)">
    <img style="width:55%; padding-top:23%;" src="<?php echo base_url('public/mall/default/images/txt1.png')?>" onclick="save_receive()"/>
</div>
<div style="min-height:90%">
    <div class="fromuser">
        <div class="userimg"><img src="<?php echo $details['headimgurl']?>" /></div>
        <div class="name"><?php echo $details['nickname']?></div>
        <div class="time"><?php echo $details['ge_time']?></div>
        <div class="wish">送你一份礼物</div>
    </div>
    <div class="giftbox">
        <div class="boximg">
            <div class="lid"><img src="<?php echo base_url('public/mall/default/images/gift02.png')?>"></div>
            <div class="relative">
                <img src="<?php echo base_url('public/mall/default/images/gift01.png')?>">
                <img style="position:absolute;width:18%;right:13%; bottom:13%; opacity:0.5" src="<?php echo base_url('public/mall/default/images/logo2.png')?>">
            </div>
        </div>
    </div>
</div>
<a class="again" style="position:static; padding:4% 0;" href=""><span>关注公众号 <img src="<?php echo base_url('public/mall/default/images/ico/goIco.png')?>" /></span></a>
</body>
<script>
$(window).load(function(){
	$('.pullgift').fadeIn();
	$('body').addClass('overflow');
	$('html').addClass('overflow');
	$('.giftbox').css({
		"position":"relative",
		"z-index" :"999",
		"width"	  :"100%",
	});
	$('.boximg').addClass('shaking');
	window.setTimeout(function(){
		$('.boximg').removeClass('shaking');
	},600);	
	$('.boximg').click(function(){
		var _this=$(this);
			_this.addClass('shaking');
		window.setTimeout(function(){
			_this.find('.lid img').attr('src',"<?php echo base_url('public/mall/default/images/gift03.png')?>");
			//动作结束 
			_this.removeClass('shaking');
			$('.pull').fadeOut(function(){save_receive();});
		},600);		
	})
});
function save_receive(){
	$.getJSON("<?php echo site_url('mall/wap/save_receive')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>",{'sc':'<?php echo $sc?>','o':'<?php echo $order_id?>'},function(data){
		window.location.href="<?php echo site_url('mall/wap/vote/'.$order_id)?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>";
	});
}
</script>
</html>
