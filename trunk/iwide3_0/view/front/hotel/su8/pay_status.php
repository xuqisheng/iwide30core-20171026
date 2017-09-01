<?php include 'header.php'?>
<?php echo referurl('css','submit_order.css',1,$media_path) ?>
<style>
body,html{ background:#f7f7f7;}
.block{ background:#fff;border:solid #e4e4e4; border-width:1px 0; margin-bottom:3%;}
.pay_sucess{ text-align:center;padding:3% 0;}
.form_list .item>span:first-child {width:auto; color:#555}
.form_list .item tt{ float:right; margin:1% 6% 0 0; font-size:10px;}
.ui_vote{ margin:3% auto; display:block; width:90%}
</style>
<body>
<div class="pay_sucess h4 block">
	<div class="iconfont" style="color:#3abb65;">&#x26;</div>
	<div>恭喜您，您已支付成功</div>
</div>
<div class="form_list">
	<div class="item ui_btn_block"  id="coupon_i">
    	<span>已获得150块代金券</span>
        <tt>查看我的优惠券</tt>
    </div>
</div>
<div class="form_list">
	<a  href="select_room" class="item ui_btn_block hide">
    	<span>自助选房</span>
    </a>
	<a href="" class="item ui_btn_block">
    	<span>查看订单详情</span>
    </a>
</div>

<div class="form_list">
	<div class="item ui_btn_block">
    	<span>打车去酒店</span>
    </div>
	<a  href="" class="item ui_btn_block">
    	<span>商城</span>
    </a>
	<a href="tel:40018-4001" class="item">
    	<span>致电酒店     40018-4001</span>
    </a>
</div>
<div class="ui_pull vote_pull" style="display:none">
	<div class="list">
        <div class="notic">
            <div class="title">温馨提示</div>
            <div class="content">
                <p>1.原则上每个间夜仅可使用 1 张住房抵用券，特殊注明可叠加使用多张券的房型除外</p>
                <p>2.抵用券不找零、不兑换，使用后不可取消，请谨慎使用</p>
            </div>
        </div>
        <div class="ui_vote">
            <p class="bordertop_img"></p>
            <div class="vote_con">
                <p class="price">50元</p>
                <p class="votename">优惠券</p>
                <p class="limit">说明</p>
                <p class="val_date">有效期至2016/04/15</p>
            </div>
            <p class="borderbtom_img"></p>
         </div>        
         <div class="ui_vote">
            <p class="bordertop_img"></p>
            <div class="vote_con">
                <p class="price">50元</p>
                <p class="votename">优惠券</p>
                <p class="limit">说明</p>
                <p class="val_date">有效期至2016/04/15</p>
            </div>
            <p class="borderbtom_img"></p>
         </div>
    </div>
</div>
<div class="h6 ui_color_gray" style="position:absolute; bottom:2%; width:100%; text-align:center">
	<span>速8酒店 | 金房卡科技</span>
</div>
</body>
<script>

	$('#coupon_i').click(function(){
		//pageloading();
		toshow($('.vote_pull'));
	});
</script>
</html>
