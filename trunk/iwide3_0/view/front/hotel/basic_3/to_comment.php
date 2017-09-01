<?php include 'header.php'?>
<?php echo referurl('css','submit_order.css',1,$media_path) ?>
<header class="order_intro">
	<div class="hotelname"><?php echo $order['hname']?></div>
    <div class="datetime"><?php echo date('m月d日',strtotime($order['startdate']));?>-<?php echo date('m月d日',strtotime($order['enddate']));?>  共<?php echo round(strtotime($order['enddate'])-strtotime($order['startdate']))/86400;?>晚</div>
    <div class="room_type">房型:<?php echo $order['first_detail']['roomname'];?></div>
	<div class="sever"><?php if(!empty($first_room['imgs']['hotel_room_service'])) foreach($first_room['imgs']['hotel_room_service'] as $hs){ ?><?php echo $hs['info']; ?>&nbsp;<?php }?></div>
</header>
<?php if($comment==0) {?>
<div class="comment">
<p style="font-size:3.2em;">您还不能评论</p>
</div>
<div style="padding-top:12%;">
	<a href='orderdetail?id=<?php echo $inter_id;?>&oid=<?php echo $order['id'];?>'><button type="submit" class="footbtn " >返回订单详情</button></a>
</div>
<?php } else {?>
<?php if(empty($comment_info)) {?>
<div class="comment">
<input type="hidden" name='point' id='point' value='5'>
	<textarea placeholder="亲～住的舒服吗？服务满意吗？留下个脚印吧～" id="msg" maxlength="100" rows="3" oninput="changerow(this)"></textarea>
   <!-- <div class="addimg">
    	<div class="ui_img_auto_cut" ><img src="images/egimg/eg_banner02.png" /></div>
    	<div class="ui_img_auto_cut"></div>
    </div>-->
</div>

<div class="topoint">
	<span class="big ui_color_gray float">酒店评分&nbsp;</span>
    <ul> 
    	<li><em class="ui_star ui_star1"></em><p class="">很差</p></li>
    	<li><em class="ui_star ui_star1"></em><p class="">不太好</p></li>
    	<li><em class="ui_star ui_star1"></em><p class="">一般</p></li>
    	<li><em class="ui_star ui_star1"></em><p class="">不错</p></li>
    	<li><em class="ui_star ui_star1"></em><p class="">很赞</p></li>
    </ul>
</div>
<div style="padding-top:12%;">
	<button type="submit" class="footbtn disable" onclick="sub_comment()">提交评价</button>
</div>
<?php } else {?>
<div class="comment">
	<p style="font-size:3.2em;"><?php echo $comment_info['content'];?></p>
</div>

<div class="topoint">
	<span class="big ui_color_gray float">酒店评分&nbsp;</span>
    <ul> 
    	<?php for($i=0;$i<$comment_info['score'];$i++) {?>
    	<li><em class="ui_star ui_star1"></em></li>
    	<?php }?>
    </ul>
</div>
<div style="padding-top:12%;">
	<a href='index?id=<?php echo $inter_id;?>&h=<?php echo $order['hotel_id'];?>'><button type="submit" class="footbtn " >再订一单</button></a>
</div>
<?php } }?>
</body>
<script>
var prevend=0;
function totest(){
	if ($('#msg').val()==''){
		$('.footbtn').html('还未填写评论内容').addClass('disable');
		return false;
	}
	if ($('#msg').get(0).value.length<=5){
		$('.footbtn').html('评论内容不得少于5个字符').addClass('disable');
		return false;
	}
	$('.footbtn').html('提交评价');
	$('.footbtn').removeClass('disable');
}
function sub_comment(){
	if(prevend==1)return false;
	if ($('#msg').val()==''){
		$('.footbtn').html('还未填写评论内容').addClass('disable');
		return false;
	}
	if ($('#msg').get(0).value.length<=5){
		$('.footbtn').html('评论内容不得少于5个字符').addClass('disable');
		return false;
	}
	prevend=1;
	$('.footbtn').html('提交中');
	$.post('<?php echo site_url("hotel/hotel/comment_sub").'?id='.$inter_id; ?>',{
		content:$('#msg').val(),
		score:$('#point').val(),
		hotel_id:'<?php echo $order["hotel_id"];?>',
		orderid:'<?php echo $order["orderid"];?>',
		hotel_name:'<?php echo $order["hname"];?>',
		room_name:'<?php echo $order['first_detail']['roomname'];?>'
	},function(data){
		if(data.s==1){
			$.MsgBox.Alert('提示',data.errmsg,function(){
				location.reload();
			});
		}
		else{
			prevend=0;
			$('.footbtn').html(data.errmsg);
		}
	},'json');
}
$(function(){
	$('#msg').blur(function(){
		totest()
	})
	$('.topoint li').click(function(){
		$('.ui_star').removeClass('ui_star1');
		for ( var i=0; i<=$(this).index(); i++){
			$('.ui_star').eq(i).addClass('ui_star1');
		}
		$('#point').val($(this).index()+1);
	})
})
</script>
</html>
