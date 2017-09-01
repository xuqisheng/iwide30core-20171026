<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<script src="/static/scripts/viewport.js"></script>
<script src="/static/scripts/jquery.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="/static/js/sha1.js"></script>
<title>《微信力量》签名版，主编谢晓萍亲自签名，限量发售！</title>
<meta name="description" content="专属于你的《微信力量》，限时抢购！" />
<div id='wx_pic' style='margin:0 auto;display:none;'>
<img src='http://wxsw.chat.iwide.cn/static/images/485132858627203326.jpg' />
</div>
<link href="/static/style/global.css" rel="stylesheet">
<link href="/static/style/page.css?v=6" rel="stylesheet">
<link href="/static/style/mycss.css?v=6" rel="stylesheet">
<?php 
if($addinfo['printed']==0 && $addinfo['status']==3){
	$lastcoupon = $this->db->query("select * from iw_coupon where sid='".$addinfo['cid']."' order by id desc limit 1")->result_array();
	$addnum = substr(time(),-1);
	$nowcoupon = $lastcoupon[0]['coupon'];
	
	$addnum+=1;
	$newcoupon1 = $nowcoupon+$addnum;
	//$newcoupon2 = $nowcoupon+$addnum+1;
	//$newcoupon3 = $nowcoupon+$addnum+2;
	
	$newcoupon = $newcoupon1;//.",".$newcoupon2.",".$newcoupon3;	
	$tempdata = array(
		'touser'=>$openid,
		'template_id'=>'5d98UwB-7NrE9yfvgUeQJ8XNMliTTOdCnXKw7SnDBi0',
		'url'=>'http://'.$hoteldata['domain'].'/index.php/fapi/getcoupon?ibd=832&couponid='.$newcoupon,
		'data'=>array(
			'name'=>array("value"=>"微信力量","color"=>"#173177"),
			'remark'=>array("value"=>"恭喜您，您还成功获得一个签名码，使用签名码即可购买一本主编亲笔签名的书哦","color"=>"#173177")
		)
	);
	$ret_temp_data = qfpost('http://'.$hoteldata['domain'].'/index.php/api/sendmsg',array('token'=>$accesstoken,'data'=>json_encode($tempdata)));
	$ret_tempobj = json_decode($ret_temp_data,true);
	if($ret_tempobj['errcode']==0){
		
		$this->db->insert('coupon',array('sid'=>$addinfo['cid'],'coupon'=>$newcoupon1,'pricerole'=>0,'pricetype'=>2,'addtime'=>time()));
		//$this->db->insert('coupon',array('sid'=>$addinfo['cid'],'coupon'=>$newcoupon2,'pricerole'=>0,'pricetype'=>2,'addtime'=>time()));
		//$this->db->insert('coupon',array('sid'=>$addinfo['cid'],'coupon'=>$newcoupon3,'pricerole'=>0,'pricetype'=>2,'addtime'=>time()));
		
		$this->db->update('custom_info',array('printed'=>1),array('id'=>$card['infoid']));
	}
}
?>
<title>购买成功</title>
<style>
.btn{width:7em}
</style>
</head>
<body>
<div class="page" style="display:block;">
    <div class="share_bg content">
        <div class="app_box center">
            <div class="apply">
                <p class="title">您已购买成功</p>
                <p class="tim">有效期为：12月3日——12月7日</p>
                <p class="draw"><img src="/static/img/draw.png"/></p>
                <div style="margin-top:6%; padding-bottom:2%; font-weight:bold;">领取卡券后可填写邮寄地址</div>
                <button id="addcard1" class="btn" name="addcard" type="button"><span><?php if(!$signaturecard){echo '已经领取';} else {echo '领取卡券';}?></span></button>	
            </div>
            <a href="/index.php/fapi?id=<?php echo $addinfo['cid'];?>" class="btn" style="margin-right:3em"><span>继续购买</span></a>	
            <a href="/index.php/fapi/order?id=<?php echo $addinfo['cid'];?>" class="btn"><span>我的订单</span></a>	
        </div>	
    </div>	
</div>
<script>
var appid='<?php echo $hoteldata['appid'];?>',timestamp='<?php echo $ntime;?>',ticket='<?php echo $ticket;?>',cardid='<?php echo $card['cardid'];?>',signaturecard='<?php echo $signaturecard;?>',nonceStr = 'qingfeng',url = location.href;
var signature = hex_sha1('jsapi_ticket='+ticket+'&noncestr='+nonceStr+'&timestamp='+timestamp+'&url='+url);
var cid = '<?php echo $card['id'];?>',paying='<?php echo $paying;?>',code='<?php echo $card['ucode'];?>';

if(paying){
    top.location.href='/index.php/fapi/addresult?id=<?php echo $card['infoid'];?>';
}

wx.config({
  debug: false,
  appId: appid,
  timestamp: timestamp,
  nonceStr: nonceStr,
  signature: signature,
  jsApiList: [
	'checkJsApi',
	'addCard',
	'chooseCard',
	'onMenuShareTimeline',
    'onMenuShareAppMessage',
	'openCard'
  ]
});

wx.ready(function(){

	wx.showMenuItems({
		menuList: ["menuItem:share:appMessage","menuItem:share:timeline"]
	});
	wx.onMenuShareTimeline({
		title: '《微信力量》签名版 ',
		desc: '《微信力量》签名版，主编谢晓萍亲自签名，限量发售！',
		link: 'http://wxsw.chat.iwide.cn/app/form/wxsw/',
		imgUrl: 'http://wxsw.chat.iwide.cn/static/images/485132858627203326.jpg',
		success: function () {
	
		},
		cancel: function () { 
		}
	});
	wx.onMenuShareAppMessage({
		title: '《微信力量》签名版 ',
		desc: '《微信力量》签名版，主编谢晓萍亲自签名，限量发售！',
		link: 'http://wxsw.chat.iwide.cn/app/form/wxsw/', 
		imgUrl: 'http://wxsw.chat.iwide.cn/static/images/485132858627203326.jpg',
		success: function () {   },
		cancel: function () { 
		   
		}
	});

});
	
$(function(){
    $("#addcard1").click(function(){
		toaddcard();
	});
});


function toaddcard(){
	if(!signaturecard){
		alert('已经领取');
		return false;
	}
	wx.addCard({
	  cardList: [
		{
		  cardId: cardid,
		  cardExt: '{"code":"'+code+'","timestamp": "'+timestamp+'", "signature":"'+signaturecard+'"}'
		}
	  ],
	  success: function (res) {
		$.post('/index.php/fapi/getcard',{cid:cid},function(d){});
		signaturecard = '';
		$("#addcard1 span").html('已经领取');
	  }
	});
}
</script>
</body>
</html>