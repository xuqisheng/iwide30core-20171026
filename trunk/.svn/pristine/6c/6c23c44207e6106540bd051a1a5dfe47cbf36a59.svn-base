<?php include 'header.php'?>
<head>
<meta name="viewport" content="width=device-width, user-scalable=no" />
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<?php echo referurl('css','comment/satisfaction.css',1,$media_path) ?>
<?php echo referurl('css','comment/saComment.css',1,$media_path) ?>
</head>
<?php if(!empty($comment_info)) {?>
<script>
location.replace('<?php echo site_url('hotel/hotel/hotel_comment').'?id='.$inter_id.'&h='.$order['hotel_id'];?>');
</script>
<?php }?>
<body>
<div class="wrap-outer">
	<div class="wrap">
	<?php if(empty($comment_info)) {?>
		<div class="sa-title">
			<img src="<?php echo referurl('img','sa-logo.png',1,$media_path) ?>">
			<h1><?php echo $order['hname']?><br>
				诚邀您分享入住体验</h1>
		</div>
		
		<ul class="satisefy-checks">
			<li class="one-check">
				<div class="ui-cb">
					<input type="radio" checked value="5" id="radio1" name="satisefaction" />
					<label for="radio1"></label>
				</div>
				<span>非常满意</span>
			</li>
			<li class="one-check">
				<div class="ui-cb">
					<input type="radio" value="4" id="radio2" name="satisefaction" />
					<label for="radio2"></label>
				</div>
				<span>满意</span>
			</li>
			<li class="one-check">
				<div class="ui-cb">
					<input type="radio" value="3" id="radio3" name="satisefaction" />
					<label for="radio3"></label>
				</div>
				<span>一般</span>
			</li>
			<li class="one-check">
				<div class="ui-cb">
					<input type="radio" value="2" id="radio4" name="satisefaction" />
					<label for="radio4"></label>
				</div>
				<span>不满意</span>
			</li>
			<li class="one-check">
				<div class="ui-cb">
					<input type="radio" value="1" id="radio5" name="satisefaction" />
					<label for="radio5"></label>
				</div>
				<span>非常不满意</span>
			</li>
		</ul>
		
		<ul class="detial-view">
			<li class="li-wid-1 view-tit"></li>
			<li class="li-wid-2 view-tit">满意</li>
			<li class="li-wid-3 view-tit">不满意</li>
			<li class="li-wid-1">服务态度</li>
			<li class="li-wid-2 cmmChosBtn cmmChosAgree" tag='service'></li>
			<li class="li-wid-3 cmmChosBtn" tag='service'></li>
			<li class="li-wid-1">清洁卫生</li>
			<li class="li-wid-2 cmmChosBtn cmmChosAgree" tag='clean'></li>
			<li class="li-wid-3 cmmChosBtn" tag='clean'></li>
			<li class="li-wid-1">性价比</li>
			<li class="li-wid-2 cmmChosBtn cmmChosAgree" tag='worth'></li>
			<li class="li-wid-3 cmmChosBtn" tag='worth'></li>
			<li class="li-wid-1">洗浴舒适度</li>
			<li class="li-wid-2 cmmChosBtn cmmChosAgree" tag='shower'></li>
			<li class="li-wid-3 cmmChosBtn" tag='shower'></li>
			<li class="li-wid-1">睡眠环境</li>
			<li class="li-wid-2 cmmChosBtn cmmChosAgree"  tag='sleep'></li>
			<li class="li-wid-3 cmmChosBtn" tag='sleep'></li>
			<li class="li-wid-1">网速</li>
			<li class="li-wid-2 cmmChosBtn cmmChosAgree" tag='net'></li>
			<li class="li-wid-3 cmmChosBtn" tag='net'></li>
		</ul>
		
		<div class="recommendind-rate">
			<h4>推荐朋友入住：</h4>
			<div class="recommending-bar">
				<div class="ui-cb-2">
					<input type="radio" id="recommending0" name="recommending-rate" value='0' class="i-radio-recom">
					<label for="recommending0" class="one-rate"></label>
				</div>
				<div class="ui-cb-2">
					<input type="radio" id="recommending1" name="recommending-rate" value='1' class="i-radio-recom">
					<label for="recommending1" class="one-rate"></label>
				</div>
				<div class="ui-cb-2">
					<input type="radio" id="recommending2" name="recommending-rate" value='2' class="i-radio-recom">
					<label for="recommending2" class="one-rate"></label>
				</div>
				<div class="ui-cb-2">
					<input type="radio" id="recommending3" name="recommending-rate" value='3' class="i-radio-recom">
					<label for="recommending3" class="one-rate"></label>
				</div>
				<div class="ui-cb-2">
					<input type="radio" id="recommending4" name="recommending-rate" value='4' class="i-radio-recom">
					<label for="recommending4" class="one-rate"></label>
				</div>
				<div class="ui-cb-2">
					<input type="radio" id="recommending5" name="recommending-rate" value='5' class="i-radio-recom">
					<label for="recommending5" class="one-rate"></label>
				</div>
				<div class="ui-cb-2">
					<input type="radio" id="recommending6" name="recommending-rate" value='6' class="i-radio-recom">
					<label for="recommending6" class="one-rate"></label>
				</div>
				<div class="ui-cb-2">
					<input type="radio" id="recommending7" name="recommending-rate" value='7' class="i-radio-recom">
					<label for="recommending7" class="one-rate"></label>
				</div>
				<div class="ui-cb-2">
					<input type="radio" id="recommending8" name="recommending-rate" value='8' class="i-radio-recom">
					<label for="recommending8" class="one-rate"></label>
				</div>
				<div class="ui-cb-2">
					<input type="radio" id="recommending9" name="recommending-rate" value='9' class="i-radio-recom">
					<label for="recommending9" class="one-rate"></label>
				</div>
				<div class="ui-cb-2">
					<input type="radio" id="recommending10" name="recommending-rate" value='10' class="i-radio-recom">
					<label for="recommending10" class="one-rate"></label>
				</div>
			</div>
			<span class="recom-end-point low-recommendding">不会推荐</span>
			<span class="recom-end-point high-recommendding">极力推荐</span>
		</div>
		
		<h2 class="share-text-title">分享您的入住体验</h2>
		
		<div class="share-area-wrap">
			<textarea id='msg' class="share-area" placeholder="说说您的心里话……"></textarea>
		</div>
		
		<div class="btn-wrap"><a id='sub_tips' href="javascript:sub_comment();" class="btns btn-balance" >提交点评</a></div>
		<input type='hidden' value='1' id='item_service' />
		<input type='hidden' value='1' id='item_clean' />
		<input type='hidden' value='1' id='item_worth' />
		<input type='hidden' value='1' id='item_shower' />
		<input type='hidden' value='1' id='item_sleep' />
		<input type='hidden' value='1' id='item_net' />
		<?php }?>
	</div>
</div>
<?php echo referurl('js','satisfaction.js',1,$media_path) ?>
</body>
<script>
var prevend=0;
var isrecommending=false;
function totest(){
	$('input[name="recommending-rate"]').each(function(index, element) {
        if($(this).get(0).checked) isrecommending=true;
    });
	if ($('#msg').val()==''){
		$('#sub_tips').html('还未填写评论内容').addClass('disable');
		return false;
	}
	if ($('#msg').get(0).value.length<=5){
		$('#sub_tips').html('评论内容不得少于5个字符').addClass('disable');
		return false;
	}
	if (!isrecommending){
		$('#sub_tips').html('还未选择是否推荐朋友入住').addClass('disable');
		return false;
	}
	$('#sub_tips').html('提交点评');
	$('#sub_tips').removeClass('disable');
	return true;
}
$('input[name="recommending-rate"]').click(totest);
$("#msg").change(totest);
function sub_comment(){
	if(prevend==1 || !totest())return false;
	prevend=1;
	$('#sub_tips').html('提交中');
	var extra={};
	extra['feel']={};
	extra['feel']['service']=$('#item_service').val();
	extra['feel']['clean']=$('#item_clean').val();
	extra['feel']['worth']=$('#item_worth').val();
	extra['feel']['shower']=$('#item_shower').val();
	extra['feel']['sleep']=$('#item_sleep').val();
	extra['feel']['net']=$('#item_net').val();
	extra['recommend']=$("input[name='recommending-rate']:checked").val();
	$.post('<?php echo site_url("hotel/hotel/comment_sub").'?id='.$inter_id; ?>',{
		content:$('#msg').val(),
		score:$("input[name='satisefaction']:checked").val(),
		hotel_id:'<?php echo $order["hotel_id"];?>',
		orderid:'<?php echo $order["orderid"];?>',
		hotel_name:'<?php echo $order["hname"];?>',
		room_name:'<?php echo $order['first_detail']['roomname'];?>',
		extra_info:JSON.stringify(extra)
	},function(data){
		if(data.s==1){
			$.MsgBox.Alert('提示',data.errmsg,function(){
				location.replace('<?php echo site_url('hotel/hotel/hotel_comment').'?id='.$inter_id.'&h='.$order['hotel_id'];?>');
			});
		}
		else{
			prevend=0;
			$('#sub_tips').html(data.errmsg);
		}
	},'json');
}
</script>
</html>
