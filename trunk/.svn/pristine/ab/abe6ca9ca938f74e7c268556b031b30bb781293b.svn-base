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
<title>我的预约</title>
    <?php echo referurl('css','service.css',1,$media_path) ?>
    <?php echo referurl('css','global.css',1,$media_path) ?>
    <?php echo referurl('js','jquery.js',1,$media_path) ?>
    <?php echo referurl('js','ui_control.js',1,$media_path) ?>
    <?php echo referurl('js','alert.js',1,$media_path) ?>
<body>
<div class="pageloading"></div>
<page class="page h24">
	<header>
    	<div class="center bg_fff flex flexjustify tablayer color_main">
        	<a href="<?php echo site_url('booking/booking/my_booking?id='.$inter_id.'&type=booking')?>" <?php if($type=='booking'){?>class="iscur"<?php }?> type="booking"><tt>预约中</tt></a>
        	<a href="<?php echo site_url('booking/booking/my_booking?id='.$inter_id.'&type=finish')?>" <?php if($type=='finish'){?>class="iscur"<?php }?> type="finish"><tt>已用餐</tt></a>
        	<a href="<?php echo site_url('booking/booking/my_booking?id='.$inter_id.'&type=cancel')?>" <?php if($type=='cancel'){?>class="iscur"<?php }?> type="cancel"><tt>已取消</tt></a>
        	<a href="<?php echo site_url('booking/booking/my_booking?id='.$inter_id.'&type=all')?>" <?php if($type=='all'){?>class="iscur"<?php }?> type="all"><tt>全部</tt></a>
        </div>
    </header>
    <section class="scroll flexgrow orders" style="padding-bottom:10px">
        <?php if(!empty($res)){
            foreach($res as $k=>$v){
        ?>
        <div class="list_style_2 martop flexlist" orderid="<?php echo $v['id']?>">
            <div class="flex flexjustify h24">
            	<div class="color_999"><?php if($v['status']==1){echo $v['add_time'];}elseif($v['status']==3 or $v['status']==4){echo $v['cancel_time'];}else{echo $v['update_time'];}?></div>
				<div class="color_main"><?php if($v['status']==1){echo '预约中';}elseif($v['status']==2){echo '已用餐';}elseif($v['status']==3){echo '用户取消';}elseif($v['status']==4){echo '酒店取消';}?></div>
            </div>
        	<div class="flex">
                <div class="img"><div class="squareimg"><img src="<?php echo $v['img']?>"></div></div>
            	<div class="flexgrow">
                    <span class="h24"><?php echo $v['shop_name']?></span>
                	<div class="h20 color_999"><?php echo $v['book_time']?> <?php echo $v['num']?>人</div>
                </div>
            </div>
            <?php if($v['status']==1){?>
            <div class="btnlayer">
            	<div class="btn_void h22 color_999" onClick="cancel(this)">取消</div>
            </div>
			<?php }?>
        </div>
        <?php }}else{?>
        <div style="padding:30px 0" class="center color_999 h22">暂无订单结果</div>
        <?php }?>
    </section>
    <footer></footer>
</page>
</body>
<script>

/*$('.tablayer>*').click(function(){alert($(this).attr('type'));
	$(this).addClass('iscur').siblings().removeClass('iscur');
	pageloading();
	//getData();
});*/
function isnone(str){
	str = str?str:'暂无订单结果';
	str = '<div style="padding:30px 0" class="center color_999 h22">'+str+'</div>';
	$('.orders').html(str);
}
function getData(){
	var type = $('.tablayer .iscur').attr('type');
	$.ajax({
		url:'',
		data:type,
		dataType:"json",
		complete: function(data){
			removeload();
			if($('.flexlist').length<=0) isnone();
		},
		success: function(data){
			var str = '';
			$('.orders').html(str);
			if($('.flexlist').length<=0) isnone();
		}
	});
}
function cancel(dom){
	pageloading();
	var id = $(dom).parents('[orderid]').attr('orderid');
	$.post(
		'<?php echo site_url('booking/booking/cancel')?>',
		{'id':id,
        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
		function(data){
			if(data == 1){
                location.href = '<?php echo site_url('booking/booking/my_booking?id='.$inter_id.'&type=cancel') ?>';
            }else{
                $.MsgBox.Alert('取消失败');
            }
		},'json');
}

//getData();
</script>
</html>
