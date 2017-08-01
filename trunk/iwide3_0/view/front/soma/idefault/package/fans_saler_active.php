<body>
<div class="box">
    <div class="dc_txt">激活“微宝客”后推荐好友购买成功即可获得<br>最高<font class="profit">10%</font>收益</div>
    <div class="btn_list">
        <?php if(!$not_allow_hint): ?>
            <a href="<?php echo $act_url; ?>"><div class="money">马上激活</div></a>
        <?php else: ?>
            <span onclick="not_allow_hint()"><div class="money">马上激活</div></span>
        <?php endif; ?>
        <a href="<?php echo $rtn_url; ?>"><div class="recom">暂不激活</div></a>
    </div>
    <!--div class="what"><a class="what_txt" href="">什么是微宝客？</a></div-->
</div>
<div class="bt_img">
	<img src="<?php echo get_cdn_url('public/soma/fans_saler/imgs/bottom.png'); ?>" />
</div>
<!--
<div class="fixe">
	<div class="f_con">
    	<div class="btn_close">关闭</div>
		<div class="money_img"><img src="<?php echo get_cdn_url('public/soma/fans_saler/imgs/money.png'); ?>" /></div>
    	<div class="p_radius">
        	<div class="people"><img src="<?php echo get_cdn_url('public/soma/fans_saler/imgs/people.png'); ?>"/></div>
        </div>
        <div class="container">
        	<div class="explain">激活“微宝客”后推荐好友购买成功即可获得<br>最高<font class="percentage">10%</font>收益</div>
            <div class="f_btn">
                <a href=""><div class="f_money">马上激活</div></a>
                <a href=""><div class="f_recom">暂不激活</div></a>
            </div>
        </div>
    </div>
    <a href=""><div class="what_txt">什么是微宝客？</div></a>
</div>
-->
<script type="text/javascript">
function not_allow_hint()
{
    $.MsgBox.Confirm('你已经是酒店分销员，不能成为泛分销员',null,null,'<?php echo $lang->line('ok');?>', '<?php echo $lang->line('cancel');?>');
}
// if($(window).height()==416){
// 	$('.bt_img').css({"position":"static"});	
// };
/*弹窗苹果4兼容*/
// if($(window).height()==416){
// 	$('.p_radius').css({"width":"110px","height":"110px","margin-left":"-55px"});	
// 	$('.container').css({"padding-top":"55%"});
// };
$(function(){
	/*弹窗消失*/
	// $(".btn_close").click(function(){
	// 	$(".fixe").fadeOut();
	// })
	/*弹窗显示*/
	/*$("").click(function(){
		$(".fixe").fadeIn();	
	})*/
})
</script>
</body>
</html>