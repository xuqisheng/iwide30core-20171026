<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" c ontent="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=320,user-scalable=0">
<title>首页</title>

    <?php echo referurl('css','service.css',1,$media_path) ?>
    <?php echo referurl('css','global.css',1,$media_path) ?>

    <?php echo referurl('js','jquery.js',1,$media_path) ?>
    <?php echo referurl('js','ui_control.js',1,$media_path) ?>
    <?php echo referurl('js','alert.js',1,$media_path) ?>

</head>
<body>
<div class="pageloading"></div>
<page class="page">
    <header></header>
    <section class="mainboxs">
        <ul class="scroll salelist">
            <li>
            	<div class="img"><div class="squareimg"><img src="<?php echo base_url('public/booking/default/images/egimg/img1.jpg')?>"></div></div>
            	<div class="flexgrow">
                	<div class="h30" shopname>天堂岛池畔餐厅</div>
                	<div class="h20 color_999 pad multiclip">位于泳池畔的全日制西餐厅，仿佛置身热带岛屿，沐浴在阳光下的醉人海滩景致。拥有充满舞台设计感的开放式厨房，技艺精湛的国际厨师团队为您主理世界各地风味佳肴，带给您美妙愉悦的用餐体验，令您味蕾绽放，回味悠长。</div>
                    <div class="flex flexjustify h26">
                        <div>今日预约量： <span class="color_minor"><?php echo isset($shop2)?$shop2:0?>桌</span></div>
                    	<div class="btn_main select_type h26" href="<?php echo site_url('booking/booking/show?id='.$inter_id.'&sid=2')?>" booktype="alldate">立即预约</div>
                	</div>
                </div>
            </li>
            <li>
            	<div class="img"><div class="squareimg"><img src="<?php echo base_url('public/booking/default/images/egimg/img2.jpg')?>"></div></div>
            	<div class="flexgrow">
                	<div class="h30" shopname>凤轩中餐厅</div>
                	<div class="h20 color_999 pad multiclip">凤轩中餐厅精选上乘新鲜食材，提供地道粤式及经典川菜美馔。荣获TARGET杂志年度优选中餐厅奖，极富创意的餐厅主厨以其娴熟的烹饪技艺为客人奉上顶级的味觉盛宴。4个私密优雅的包间融合了高品位的欧式装潢艺术，让您尊享超凡的用餐体验，是各类商务宴请和社交活动的首选之地。</div>
                    <div class="flex flexjustify h26">
                        <div>今日预约量： <span class="color_minor"><?php echo isset($shop1)?$shop1:0?>桌</span></div>
                    	<div class="btn_main select_type h26" href="<?php echo site_url('booking/booking/show?id='.$inter_id.'&sid=1')?>" booktype="partdate">立即预约</div>
                	</div>
                </div>
            </li>
        </ul>
	</section>
    <section class="floatlayer">
    	<a href="<?php echo site_url('booking/booking/my_booking?id='.$inter_id)?>" class="squareimg"><img src="<?php echo base_url('public/booking/default/images/mine.png')?>"></a>
    </section>
    <footer></footer>
</page>
</body>
<script>
$('.btn_main').click(function(){
	$.setsession('booktype',$(this).attr('booktype'));
	$.setsession('shopname',$(this).parents('li').find('[shopname]').html());
	window.location.href=$(this).attr('href');
})
</script>
</html>
