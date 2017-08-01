<?php include 'header.php'?>
<?php echo referurl('js','touchwipe.js',3,$media_path) ?>
<?php echo referurl('css','search_result.css',1,$media_path) ?>
<?php echo referurl('css','tab_list.css',2,$media_path) ?>
<header>
	<div class="headfixed ui_tab">
    	<div class="ofte_go cur">常住酒店</div>
    	<div class="my_like">我的收藏</div>
    </div>
</header>
<div class="hotel_list">
	<div class="item">
    	<a href="room_list.html" class="wipe">
            <div class="ui_img_auto_cut"><img src="images/egimg/eg01.png" /></div>
            <div class="allin_box">
                <div class="name">广州天美酒店公寓体育中心店</div>
                <div class="coupon">
                    <div class="ui_price">398</div>
                    <div class="backvote">入住返券</div>
                </div>
                <div class="ever"><span class="ui_color_gray">12条评论</span></div>
                <div class="sever">
                    <em class="ui_sever ui_sever_ico1"></em>
                    <em class="ui_sever ui_sever_ico2"></em>
                    <em class="ui_sever ui_sever_ico3"></em>
                    <em class="ui_sever ui_sever_ico4"></em>
                </div>
        	</div>
        </a>
        <div class="delete">删除常住</div>
    </div>
</div>

</body>
<script>
$(function(){
	
	$('.ui_tab div').click(function(){
		$(this).addClass('cur').siblings().removeClass('cur');
		if ( $(this).index()==1){
			
		}
	})
	var wipeleft = function(_this){
		$(_this).siblings().css({'transform':'translateX(-6em)','line-height':$(_this).siblings().height()+'px'});
		$(_this).css({'transform':'translateX(-6em)'});
	}
	var wiperight= function(_this){
		$(_this).css({'transform':'translateX(0px)'});
		$(_this).siblings().css({'transform':'translateX(0px)'});
	}
	$(".wipe").touchwipe({
		 wipeLeft: function(_this) { wipeleft(_this);},
		 wipeRight: function(_this) { wiperight(_this); },
		 min_move_x: 15,
		 min_move_y: 15,
		 preventDefaultEvents: true
	});
	
	$('.delete').on('click',function(){
		var r=confirm('删除后不可恢复，确定删除？');
		if( !r ) return false;
		$(this).parent().remove();
	})
});
</script>
</html>
