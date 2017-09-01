<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
<script src="<?php echo base_url('public/mall/multi/script/viewport.js')?>"></script>
<script src="<?php echo base_url('public/mall/multi/script/jquery.js')?>"></script>
<script src="<?php echo base_url('public/mall/multi/script/ui_control.js')?>"></script>
<link href="<?php echo base_url('public/mall/multi/style/global.css')?>" rel="stylesheet">
<script src="<?php echo base_url('public/mall/multi/script/jquery.touchwipe.min.js')?>"></script>
<script src="<?php echo base_url('public/mall/multi/script/imgscroll.js')?>"></script>
<script src="<?php echo base_url('public/mall/multi/script/jquery.jplayer.min.js')?>"></script>
<link href="<?php echo base_url('public/mall/multi/style/ui.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mail.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mailstatus.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/card.css')?>" rel="stylesheet">
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<title><?php echo $title?></title>
<script>
wx.config({
    debug: false,
    appId: '<?php echo $signPackage["appId"]?>',
    timestamp: <?php echo $signPackage["timestamp"]?>,
    nonceStr: '<?php echo $signPackage["nonceStr"]?>',
    signature: '<?php echo $signPackage["signature"]?>',
    jsApiList: [
        'addCard',
		'startRecord',
		'stopRecord',
		'onVoiceRecordEnd',
		'playVoice',
		'pauseVoice',
		'stopVoice',
		'onVoicePlayEnd',
		'uploadVoice',
		'downloadVoice',
		'onMenuShareTimeline',
		'onMenuShareAppMessage',
		'hideMenuItems',
		'showMenuItems'
    ]
});
wx.ready(function(){
	wx.hideMenuItems({
		menuList: [
			"menuItem:jsDebug",
			"menuItem:editTag",
			"menuItem:delete",
			"menuItem:copyUrl",
			"menuItem:originPage",
			"menuItem:readMode",
			"menuItem:openWithQQBrowser",
			"menuItem:openWithSafari"
		]
	});
  	wx.onMenuShareTimeline({
	    title: '<?php echo $share["title"]?>',
	    desc: '<?php echo $share["desc"]?>',
	    link: '<?php echo $share["link"]?>',
	    imgUrl: '<?php echo $share["imgUrl"]?>',
	    success: function () {
			window.location.href='<?php echo site_url('mall/wap/topic').'?id='.$inter_id.'&t='.$topic['identity'].'&saler='.$saler?>';
	    },
	    cancel: function () {}
	});
	wx.onMenuShareAppMessage({
	    title: '<?php echo $share["title"]?>',
	    desc: '<?php echo $share["desc"]?>',
	    link: '<?php echo $share["link"]?>', 
	    imgUrl: '<?php echo $share["imgUrl"]?>',
	    type: '<?php echo $share["type"]?>', 
	    dataUrl: '<?php echo $share["dataUrl"]?>',
	    success: function () { 
	    	window.location.href='<?php echo site_url('mall/wap/topic').'?id='.$inter_id.'&t='.$topic['identity'].'&saler='.$saler?>';
		},
	    cancel: function () {}
	});
	//录音
	var audio,recording,recordtime,mediastatus;
	audio = document.getElementById("audio");
	audio.loop = false; //歌曲循环
	audio.addEventListener("loadeddata", function(){//歌曲完整的加载完毕
		mediastatus='load';
	}, false);
	audio.addEventListener("play",function(){ //监听播放
		mediastatus='play';
	}, false);
	audio.addEventListener("pause",function(){  //监听暂停
		mediastatus='stop';
		bind_listen();
	}, false);
	audio.addEventListener("ended", function(){
		mediastatus='end';
		bind_listen();
	}, false);
	//录音消息id容器
	var voice = {
		src: '<?php if( isset($wishes['voice_url']) ) echo $wishes['voice_url'] ?>',
		serverId: '<?php if( isset($wishes['serverId']) ) echo $wishes['serverId'] ?>',
		localId: ''
	};
	function un_bind(){
		$('.record').unbind('touchstart');
		$('.record').unbind('touchend');
		$('.record').unbind('click');
	}
	function bind_record(){
		un_bind();
		$('.record').bind('click',record);
		$('.record').bind('touchend',record_end);
	}
	
	function bind_stoprecord(){
		un_bind();
		$('.record').bind('click',record_end);
	}
	function bind_listen(){
		un_bind();
		$('.record').addClass('listen');
		$('.listen p').html('点击试听录音');
		$('.record').bind('touchstart',listen);
	}
	function bind_stop(){
		un_bind();
		$('.listen p').html('点击停止播放');
		$('.record').bind('touchstart',stop_listen);
	}
	function record(event){    //开始录音
		event.preventDefault();
		$(this).css('background-color','#f8f8f8');
		recordtime=0;
		var tips='正在录音';
		$('.record p').html(tips);
		wx.startRecord();    //微信接口,开始录音
		recording = window.setInterval(function(){    //录音监控
			recordtime++;
			if ( recordtime % 4 !=0 )tips+='.';
			else tips ='正在录音';
			if(recordtime>=60){
				record_end();
				alert('录音结束');
			}
			$('.record p').html(tips);
		}, 1000);
		bind_stoprecord();
	}
	function handle_record_end(record){
		voice.localId = record.localId;
		wx.uploadVoice({
			localId: voice.localId, // 需要上传的音频的本地ID，由stopRecord接口获得
			isShowProgressTips: 1, // 默认为1，显示进度提示
			success: function ( _record) {   //上传录音成功
				voice.serverId = _record.serverId; // 返回音频的服务器端ID
				var bg_url= $('.addimg_box img').attr('src');
				if(bg_url==undefined) bg_url='';
				var msg= $('textarea').val();
				if(msg==undefined) msg='';
				$.getJSON("<?php echo site_url('mall/wap/save_vioce').'?id='.$inter_id.'&t='.$topic['identity'].'&saler='.$saler?>",{'sid':voice.serverId,'oid':'<?php echo $order['order_id'] ?>','msg':msg,'pic':bg_url},function(res){//保存到自己的服务器
					if( res.status==1 ){
						voice.src= res.url;	//将上传后的语音文件url放在本地
						bind_listen();
	
					} else {
						$('.record p').html('录音上传失败，请重新录音');
					}
				});//$.getJSON	
			}
		});//wx.uploadVoice
	}
	function record_end(){    //录音结束
		$(this).css('background-color','#ffffff');
		window.clearInterval(recording);
		if (recordtime <=0){
			wx.stopRecord();//wx.stopRecord
			$('.record p').html('录音时间过短,请重新录音');
			bind_record();
		} else{
			wx.stopRecord({
				success: function (record) {		//停止录音
					handle_record_end(record);
				}
			});//wx.stopRecord
		}
	}
	function listen(event){    //录音试听
		event.preventDefault();
		if ( voice.src ==''){ alert('录音加载失败, 请刷新页面重试');return;}
		audio.src= voice.src;
		alert(voice.src)
		alert(audio.src)
		audio.play();
		bind_stop();
	}
	function stop_listen (){
		bind_listen();
		audio.pause();
		audio.currentTime=0;
	}
	$('.record_again').click(function(){     //重新录音
		var clear=confirm('此操作将删除本订单已保存的录音, 是否继续?');
		if ( clear){
			$('.record').removeClass('listen');
			$('.record p').html('按住开始录音');
			bind_record();
			recordtime=0;
		}
	})
	//初始化绑定事件
	<?php if ( $is_record ): ?>bind_listen(); 
	<?php else: ?>bind_record();
	<?php endif; ?>

});
</script>
</head>
<body>
<div class="page_loading"><p class="isload">正在加载</p></div>
<audio style="display:none" id="audio"></audio>

<header style="background:#fff; overflow:hidden; padding:0 4%;border-bottom:1px solid #e4e4e4;">
    <div class="from_user">
        <div><?php if(isset($fans_info['headimgurl'])): ?><img src="<?php echo $fans_info['headimgurl'] ?>" /><?php endif; ?></div>
        <div><?php echo $fans_info['nickname'] ?></div>
    </div>
    <div class="layout change_txt"><em></em><div>换一句</div></div>
    <div class="layout"><textarea id="msg_ctn" placeholder=""  maxlength="45" rows="3"></textarea></div>
    <div class="layout img_select">
    	<div class="pre_btn"><i></i></div>
        <div class="img_list">
        	<ul><li>无</li>
			<?php foreach($pics as $v): ?>
                <li><img src="<?php echo $v ?>" /></li>
			<?php endforeach; ?>
            </ul>
        </div>
        <div class="next_btn"><i></i></div>
    </div>
    <div class="layout addimg_box">
    <?php if( isset($wishes['bg_url']) && $wishes['bg_url'] ): ?>
    	<img src="<?php echo $wishes['bg_url'] ?>" />
	<?php else: ?>
    	<span>您可从右侧图册中选择一张图片送给您的好友</span>
     <?php endif; ?>
    </div>
    <div class="layout record_again"><em></em><div>重新录</div></div>
    <div class="layout record"><p>按住开始录音</p></div>
</header>

<div class="orderlist" style=" margin-top:3%;">
    <div class="ordertitle bg_white">
        <span class="float_r hide"><?php echo $order['order_time'] ?></span>
        <span>订单号：<?php echo $order['out_order_id'] ?></span>
    </div>
    <div class="content"><?php $total_qty=0; foreach ($items as $v): ?>
        <div class="item">
            <div class="itemimg img_auto_cut"><img src="<?php echo $goods[$v['gs_id']]['gs_logo'] ?>" /></div>
            <div class="hotelname txtclip"><?php echo $v['gs_name'] ?></div>
            <div class="desc gray txtclip"><?php echo $goods[$v['gs_id']]['gs_desc'] ?></div>
            <div style="margin-top:3%">
                <span class="ui_price color"><?php echo $v['promote_price'] ?></span>
                <span class="count gray"><?php echo $v['qty'] ?></span>
            </div>
            <div style="clear:both; text-align:right; padding-right:3%;">
				有效期至<?php 
				if($order['consume_end']): 
					echo date('Y年m月d日', strtotime($order['consume_end'])); 
				endif;
				?>
			</div>
        </div><?php $total_qty+= $v['qty']; endforeach; ?>
     </div>
    <div class="orderfoot bg_white" style="text-align:left; padding-right:3%; text-align:justify">
        <span>送给朋友后，此订单内所有商品将归该好友所有，您可在“<a class="blue" href="" >个人中心</a>－<a class="blue" href="" >我的订单</a>”中查看此订单状态。</span>
    </div>
</div>
<div class="foot_btn couple">
	<div>送给朋友</div>
	<div>送给自己</div>
</div>

<!---  以下为弹层 --->
<div class="pull pullshare" style="text-align:right; display:none" onClick="toclose();">
    <div style="padding-right:3%">
        <img src="<?php echo base_url('public/mall/multi/images/ico/arrow.png')?>" style="width:10%"/>
    </div>
    <p style="padding-right:12%; font-size:0.8rem;">点击并发送给自己或朋友</p>
</div>

<div style="padding-top:15%"></div>
</body>
<script>
// addimg_box 图片尺寸;
var imgrate = 520/290;

//初始化界面
function setting(){
	$('.addimg_box').height($('.addimg_box').width()/imgrate);
	$('.addimg_box span').css('padding-top',$('.addimg_box').height()*0.7);
	$('.record p').css('padding-top',$('.record').height()*0.7);
	$('.img_list li').height($('.img_list li').width());
	$('.img_list li').css('line-height',$('.img_list li').height()+'px');
	$('.img_list ul').css('max-height',$('.img_list li').outerHeight()*3);
}
setting();
window.onresize=setting;
//文字
<?php $msg_var=''; foreach($messages as $v): if($v) $msg_var.= "'$v',"; endforeach; ?>
var messages=[<?php echo substr($msg_var, 0, -1); ?>];
var txt_index = 1;
$('#msg_ctn').val(messages[0]);
$('.change_txt').click(function(){
	if (txt_index>= messages.length )txt_index = 0;
	$('#msg_ctn').val(messages[txt_index]);
	txt_index++;
})
$('#msg_ctn').click(function(){
	if ( $(this).val()==messages[txt_index-1] )	$(this).val('');
})
// 选择图片
var scrollimg;
$('.next_btn').click(function(){
	window.clearInterval(scrollimg);
	var count =0;
	var scrollTop = $('.img_list ul').scrollTop();
	var selectimg_height =$('.img_list li').outerHeight();
	
	scrollimg = window.setInterval(function(){
		$('.img_list ul').scrollTop(scrollTop+count);
		count+= selectimg_height/5;
		if (count > selectimg_height*3)window.clearInterval(scrollimg);
	},10);
})

$('.pre_btn').click(function(){
	window.clearInterval(scrollimg);
	var count =0;
	var scrollTop = $('.img_list ul').scrollTop();
	var selectimg_height =$('.img_list li').outerHeight();
	
	scrollimg = window.setInterval(function(){
		$('.img_list ul').scrollTop(scrollTop-count);
		count += selectimg_height/5;
		if (count <=0)window.clearInterval(scrollimg);
	},10);
})

$('.img_list li').click(function(){
	var tmp= '<span style="padding-top:'+($('.addimg_box').height()*0.7)+'px">您可从右侧图册中选择一张图片送给您的好友</span>';
	if ( $(this).find('img').length >0)
		tmp= $(this).html();
	$('.addimg_box').html(tmp);
})

//分享
$('.foot_btn').click(function(){
	toshow($('.pull'));
})

</script>
</html>
