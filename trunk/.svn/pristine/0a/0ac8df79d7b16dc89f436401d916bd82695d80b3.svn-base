<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>快乐付</title>
    <?php echo referurl('css','weui.css',1,$media_path) ?>
	<?php echo referurl('css','okpay.css',1,$media_path) ?>
	<?php echo referurl('css','mycss.css',1,$media_path) ?>
   
</head>
<body ontouchstart>
<?php if(isset($gift) && !empty($gift)){?>
<div class="heade">
    <div class="logo"><img src="<?php echo base_url("public/okpay/default/");?>/images/iconfont.png"/></div>
    <p class="num">领取成功</p>
</div>

<div class="content">
	<div class="con_name">
    	<div class="h_na img_auto_cut"><img src="<?php echo $hotel['intro_img']; ?>"/></div>
        <div class="h_txt">
        	<p class="tit">Hi~我是<?php echo $hotel['name']; ?>，</p>
            <?php if(isset($title) && !empty($title) && isset($gift['credit'])){?>
            <p class="h_con">鼓励劳动精神，特此奉上<?php echo $title.'和'.$gift['credit'].'积分'?></p>
            <?php }elseif(isset($title) && !empty($title)){?>
            <p class="h_con">鼓励劳动精神，特此奉上<?php echo $title;?></p>
            <?php }elseif(isset($gift['credit'])){?>
            <p class="h_con">鼓励劳动精神，特此奉上<?php echo $gift['credit'].'积分';?></p>
            <?php }?>
            <div class="arr"><img src="<?php echo base_url("public/okpay/default/");?>/images/aworr2.png"/></div>
        </div>
        <p style="clear:both"></p>
    </div>
    <?php if(isset($gift['card']) && !empty($gift['card'])){
        foreach($gift['card'] as $k=>$v){
    ?>
    <div class="coupon">
    	<div class="cou_con">
        	<font><?php echo intval($v['reduce_cost'])?>元</font>
        	<p class="cou_titl"><?php echo $v['title']?></p>
        	<p class="cou_text">券ID:<?php echo $v['card_id']?></p>
        	<p class="cou_time">有效期至<?php echo date('Y-m-d',$v['use_time_end'])?></p>
            <div class="bg_1"></div>
        </div>
    </div>
    <?php }}?>
    <?php if(isset($gift['credit'])){?>
    <div class="coupon">
    	<div class="cou_con">
        	<font><?php echo $gift['credit']?>分</font>
        	<p class="cou_titl">会员积分</p>
        	<p class="cou_time" style="margin-top:5%;">有效期:永久</p>
            <div class="bg_1"></div>
        </div>
    </div>
    <?php }?>
    <?php if(isset($gift['credit']) && isset($gift['card']) && !empty($gift['card'])){?>
    <p>会员积分&抵用券已放入账户<a href="<?php echo site_url('membervip/center?id='.$inter_id)?>">点击查看</a></p>
    <?php }elseif(isset($gift['credit'])){?>
        <p>会员积分<a href="<?php echo site_url('membervip/bonus')?>">点击查看</a></p>
    <?php }elseif(isset($gift['card']) && !empty($gift['card'])){?>
    <p>抵用券已放入账户<a href="<?php echo site_url('membervip/card')?>">点击查看</a></p>
    <?php }?>
</div>
<div class="floot">
	<!--<a href=""><div class="btn">添加至卡包</div></a>-->
    <a href="<?php echo site_url('okpay/okpay/pay_record') ?>"><p>查看支付历史</p></a>
</div>
<?php }else{?>
    <div class="heade">
        <div class="logo"><img src="<?php echo base_url("public/okpay/default/");?>/images/iconfont.png"/></div>
        <p class="num"><?php echo $msg?></p>
    </div>
    <div class="floot">
        <!--<a href=""><div class="btn">添加至卡包</div></a>-->
        <a href="<?php echo site_url('okpay/okpay/pay_record') ?>"><p>查看支付历史</p></a>
    </div>
<?php }?>
    <?php echo referurl('js','zepto.min.js',1,$media_path) ?>
    <script type="text/javascript">
    	window.onload=function(){
			 function img_auto_cut(parent){
				var _this =$('.img_auto_cut');
				if(parent!= undefined)
					_this = parent.find('.img_auto_cut');
				var _p_w,_p_h,_w,_h;
				for (var i=0; i<_this.length;i++){
					_p_w  = _this.eq(i).width();
					_p_h  = _this.eq(i).height();
					var _thisimg = _this.eq(i).find('img');
					if(_thisimg.length){	
						if ( _thisimg.height()>0){
							_w = _thisimg.width();
							_h = _thisimg.height();
						}
						else
							_thisimg.load(function(){
								_w = $(this).width();
								_h = $(this).height();
							})
						if( _h < _w  ){
							_thisimg.removeClass('_w_h').addClass('_h_w');
							_thisimg.css('left',(_p_w-_thisimg.width())/2);
						}
						else if( _h > _w ){
							_thisimg.removeClass('_h_w').addClass('_w_h');
							_thisimg.css('top',(_p_h-_thisimg.height())/2);
						}
					}
					console.warn(_p_w+' '+_p_h+' '+_w+' '+_h);
				}
			}
   			img_auto_cut();
    	};
    </script>
    <?php echo referurl('js','hide_menu.js',1,$media_path) ?>
</body>
</html>