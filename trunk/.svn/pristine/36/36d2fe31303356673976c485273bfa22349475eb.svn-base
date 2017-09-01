<title><?php echo $title?></title>
<link href="<?php echo base_url('public/mall/multi/style/ui.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mail.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mailstatus.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/card.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/gift_open.css')?>" rel="stylesheet">
</head>
<body>
<audio style="display:none" id="audio"></audio>

<?php if($wishes['bg_url'] || $wishes['voice_url'] || $wishes['message'] ): ?>
<header style="background:#fff; overflow:hidden; padding:0 4%;border-bottom:1px solid #e4e4e4;">
    <div class="from_user">
        <div><?php if(isset($wishes['headimgurl'])): ?><img src="<?php echo $wishes['headimgurl'] ?>" /><?php endif; ?></div>
        <div><?php echo $wishes['nickname'] ?></div>
    </div>
	<?php if( $wishes['message']): ?>
    <div class="layout"><p class="textarea"><em></em><?php echo $wishes['message'] ?></p></div>
	<?php endif; ?>
	<?php if($wishes['bg_url'] || $wishes['serverId']): ?>
    <div class="layout addimg_box">
    	<?php if($wishes['bg_url']): ?><img src="<?php echo $wishes['bg_url']; ?>" /><?php endif; ?>
        <?php if($wishes['serverId']): ?><div class="have_audio"><p>点击聆听好友给您的祝福</p></div><?php endif; ?>
    </div>
	<?php endif; ?>
</header>
<?php endif; ?>

<div class="orderlist" style=" margin-top:3%;">
    <div class="ordertitle bg_white hide">
        <span class="float_r"><?php echo $order['order_time'] ?></span>
        <span>订单号：<?php echo $order['out_order_id'] ?></span>
    </div>
    <div class="content"><?php $card_arr= array(); foreach ($items as $v): 
/**
cardList: [
	{
	  cardId: 'pDF3iY9tv9zCGCj4jTXFOo1DxHdo',
	  cardExt: '{"code": "", "openid": "", "timestamp": "1418301401", "signature":"f54dae85e7807cc9525ccc127b4796e021f05b33"}'
	},
	{
	  cardId: 'pDF3iY9tv9zCGCj4jTXFOo1DxHdo',
	  cardExt: '{"code": "", "openid": "", "timestamp": "1418301401", "signature":"f54dae85e7807cc9525ccc127b4796e021f05b33"}'
	}
],
**/
if( $v['card_use_type']== $goods_model::CARD_USE_TYPE_1 ){
	$tmp_code= $v['cards']['card_no'];
} else if( $v['card_use_type']==$goods_model::CARD_USE_TYPE_2 ){
	$tmp_code= $v['cards']['code'];
} else {
	$tmp_code= '卡号'. $v['cards']['card_no']. '卡密'. $v['cards']['code'];
	$tmp_code= 'card:'. $v['cards']['card_no']. 'pass:'. $v['cards']['code'];
	//$tmp_code= $v['cards']['code'];
}
//echo $tmp_code;
if($v['wx_card_id']){
    $public= $this->publics_model->get_public_by_id( $inter_id);
	$this->load->model('wx/access_token_model');
	//$card_arr = $this->access_token_model->getCardPackage($v['wx_card_id'], $inter_id, $tmp_code );
	//$signPackage= $this->access_token_model->getSignPackage($inter_id, $_SERVER['HTTP_REFERER']);
}
//print_r($card_arr);
?>
        <div class="item">
            <div class="itemimg img_auto_cut"><img src="<?php echo $goods[$v['gs_id']]['gs_logo'] ?>" /></div>
            <div class="hotelname txtclip"><?php echo $v['gs_name'] ?></div>
            <div class="desc gray txtclip"><?php echo $goods[$v['gs_id']]['gs_desc'] ?></div>
<?php if( isset($goods[$v['gs_id']]['card_use_type']) && $goods[$v['gs_id']]['card_use_type']==1 ): ?>
            <div style="margin-top:3%">
                <span class="gray">卡号: </span>
                <span class="color"><?php echo $v['cards']['card_no'] ?></span>
            </div>
<?php elseif( isset($goods[$v['gs_id']]['card_use_type']) && $goods[$v['gs_id']]['card_use_type']==2 ): ?>
            <div style="margin-top:3%">
                <span class="gray">卡密: </span>
                <span class="color"><?php echo $v['cards']['code'] ?></span>
            </div>
<?php else: ?>
            <div style="margin-top:3%">
                <span class="gray">卡号: </span>
                <span class="color"><?php echo $v['cards']['card_no'] ?></span>
            </div>
            <div style="margin-top:3%">
                <span class="gray">卡密: </span>
                <span class="color"><?php echo $v['cards']['code'] ?></span>
            </div>
<?php endif; ?>

        </div><?php endforeach; ?>
    </div>
    <div class="orderfoot bg_white" style="text-align:left; padding-right:3%; text-align:justify; display:none">
        <span>放入微信卡包之后可转增好友或自己使用。<a href="" class="color">前往使用&gt;&gt;</a></span>
    </div>
</div>
<div class="foot_btn single add_to_bag">
	<a href="<?php echo site_url('mall/wap/topic').'?id='.$inter_id.'&t='.$topic['identity'].'&saler='.$saler?>" style="color:#fff;">我也要送</a>
</div>
<div style="padding-top:15%"></div>
</body>
<script>
// addimg_box 图片尺寸;  
var imgrate = 520/290;

//录音消息id容器
var voice = {
	src: '<?php if( isset($wishes['voice_url']) ) echo $wishes['voice_url'] ?>',
	serverId: '<?php if( isset($wishes['serverId']) ) echo $wishes['serverId'] ?>',
	localId: ''
};

//初始化界面
function setting(){
	if ( $('.addimg_box').find('img').length <=0){
		imgrate= 600/150;
		$('.have_audio').addClass('noimg');
	}
	$('.addimg_box').height($('.addimg_box').width()/imgrate);
	$('.have_audio p').css('padding-top',$('.addimg_box').height()*0.7);
}
setting();
window.onresize=setting;

//播放录音
function audio_status(_status){
	if ( _status ){
		$('.have_audio').addClass('stop').css('background-color','transparent');
		$('p','.have_audio').html('点击停止播放');
	}
	else {
		$('.have_audio').removeClass('stop').css('background-color','rgba(0,0,0,0.6)');
		$('p','.have_audio').html('点击聆听好友给您的祝福');
	}
}
var isplay = false;
$('.have_audio').click(function(){
	console.log(0);
	if(voice.localId==''){
		console.log(5);
		wx.downloadVoice({
			serverId: voice.serverId, // 需要下载的音频的服务器端ID，由uploadVoice接口获得
			isShowProgressTips: 1, // 默认为1，显示进度提示
			success: function (res) {
				console.log(4);
				voice.localId=res.localId;
				wx.playVoice({
					localId: voice.localId, // 需要暂停的音频的本地ID，由stopRecord接口获得
					success: function (res) {
						isplay=true;
						audio_status(isplay);
					}
				});
				wx.onVoicePlayEnd({
					success: function (res) {
						isplay=false;
						audio_status(isplay);
					}
				});
			}
		});
	}
	else{
		if (isplay){
			isplay=false;
			wx.stopVoice({
				localId: voice.localId
			});
		}
		else{
			isplay=true;
			wx.playVoice({
				localId: voice.localId, // 需要暂停的音频的本地ID，由stopRecord接口获得
			});
		}
		audio_status(isplay);
	}
})

//放入卡包事件
<?php if( count($card_arr)==0 ): ?>
//$('add_to_bag').css('background','#999999');
//$('add_to_bag div').html('没有可加入卡包的卡券');
<?php else: ?>
//var add_card_lock=false;
//$('.add_to_bag').click(function(){
//	if(add_card_lock==false){
//		wx.addCard({
//	      cardList: <?php 
//	      print_r($card_arr);
// 	      foreach ($card_arr as $k=>$v){
// 	          $card_arr[$k]['cardId']= $card_arr[$k]['card_id'];
// 	          $card_arr[$k]['cardExt']= '{"code":"'. $v['card_ext']['code']. '","openid":"'. $v['card_ext']['openid']
// 	            . '","timestamp":'. $v['card_ext']['timestamp']//. ',"nonce_str":"'. $v['card_ext']['nonce_str']
// 	            . '","signature":"'. $v['card_ext']['signature']. '"}';
// 	          unset($card_arr[$k]['card_id']);
// 	          unset($card_arr[$k]['card_ext']);
// 	      }
//	      echo json_encode($card_arr);
//	      ?>,
//	      success: function (res) {
//	    	add_card_lock=true;
//	        $('add_to_bag div').html('已成功添加卡券。');
//			$('.add_to_bag').css('background','#999999');
//	      }
//	    });
//	}
//});
<?php endif; ?>
</script>
</html>
