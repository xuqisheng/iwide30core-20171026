<?php require_once 'header.php';?>
<?php echo referurl('css','show_square.css',2,$media_path) ?>
<?php echo referurl('css','animate.css',2,$media_path) ?>
<?php echo referurl('js','action.js',2,$media_path) ?>
<body>
<?php if($active){ ?>
<a href="/index.php/chat/talk/active?iad=<?php echo $active['id'];?>" class="ui_notic_default"> <?php echo $active['title'];?> ， 点击查看更多活动详情。</a>
<?php } ?>

<div class="page_loading"><div>
    <p><img src="<?php echo referurl('img','ico/loading.gif',2,$media_path) ?>" /></p>
    <p>正在加载</p>
</div></div>

<div class="page">
<input type="hidden" id="userid" value="<?php echo $userinfo['id'];?>" />
	<div class="ui_canvas ui_square_h ciycle">
        <div class="ui_canvas_bg" style="display:none"></div>
    	<div class="ui_canvas_mid ui_square_h ciycle">
        	<div class="ui_canvas_min ui_square_h ciycle">
    			<div class="user_img ui_canvas_center"><img src="<?php echo $userinfo['logo'];?>" /></div>
                <div class="user_img ui_canvas_around"></div>
                <div class="user_img ui_canvas_around"></div>
            </div>
    		<div class="user_img ui_canvas_around"></div>
            <div class="user_img ui_canvas_around"></div>
        </div>
        <div class="user_img ui_canvas_around"></div>
    </div>
    <div class="relative" style="text-align:center;">
    	<span style="color:#59a752;font-size:0.55rem;">点击头像可与同住酒店的朋友聊天哦～</span>
        <div class="again_btn">换一批</div>
        <div class="get_bonus_btn" style="display:none"><img src="<?php echo referurl('img','img06.png',2,$media_path) ?>" /></div>
    </div>
    <div class="foot_btn">
    	<a href="/index.php/chat/talk/mymsg" class="my_message_btn">
        	<span>
            	<img src="<?php echo referurl('img','ico/ico06.png',2,$media_path) ?>" />
            	<div class="ui_count" style="display:none"></div>
            </span>
            <p>我的消息</p>
        </a>
    	<a href="/index.php/chat/talk/myactive" class="my_activity_btn">
        	<span>
            	<img src="<?php echo referurl('img','ico/ico07.png',2,$media_path) ?>" />
            	<div class="ui_count hide"></div>
            </span>
            <p>我的活动</p>
        </a>
    	<div class="send_bonus_btn">
        	<span>
            	<img src="<?php echo referurl('img','ico/ico08.png',2,$media_path) ?>" />
            	<div class="ui_count hide"></div>
            </span>
            <p><!--我要发红包-->我的酒瓶</p>
        </div>
    </div>
    <!-- 用户详情  -->
    <div class="pull pull_user_detail" style="display:none">
    	<div class="user_detail">
            <div class="pull_close">&times;</div>
            <div class="pull_user_img"><img src="/public/chat/public/attachment/userimg02.jpg" /></div>
            <div class="user_name"></div>
            <div class="user_local"></div>
            <a href="" class="send_btn">发消息</a>
        </div>
    </div>
    
    <!----- 红包弹层 ----->
    <div class="pull pull_send_bonus" style="display:none">
    	<div class="bonus_detail">
            <div class="pull_close">&times;</div>
            <div style=" color:#d65645; font-size:0.9rem; padding:7% 0">发红包</div>
        	<form id="form1" name="form1" method="post" action="">
                <div class="input_box">
                    <span><input type="tel" name="num" placeholder="填写个数">个</span>
                	<span>红包个数</span>
                </div>
				<div class="input_title">需要发出的红包数量</div>
                <div class="input_box">
                    <span><input type="tel" name="money" placeholder="填写金额">元</span>
                	<span>总金额</span>
                </div>
                <div class="input_title">每位住友能获得1个随机金额红包</div>
                <textarea rows="3" name="desri" maxlength="120" placeholder="恭喜发财，大吉大利"></textarea>
                <div class="ui_price">0.00</div>
                <input type="submit" name="dosubmit" class="send_btn" value="塞钱进红包"><?php if($csrf){echo '<input type="hidden" name="'.$csrf['name'].'" value="'.$csrf['hash'].'" />';}?>
        	</form>
        </div>
    </div>
</div>
<script>
online();
var memberonline = {};
$(function(){
	$('.send_bonus_btn').click(function(){
		//toshow($('.pull_send_bonus'));
		location.href='/index.php/chat/bottle/mainbottle/';
	});
	$('.ui_canvas_around').click(function(){
	    var data = eval("(" + $(this).find('img').attr('data') + ")");
		if(data.logo){
		    if(parseInt(data.id)==parseInt($('#userid').val())){
			    return false;				
			}
			$('.pull_user_detail .pull_user_img img').attr('src',data.logo);
			
			$('.pull_user_detail .user_name').html(data.nickname);
			
			$('.pull_user_detail .user_local').html(data.province+' '+data.area);
			
			$('.pull_user_detail .send_btn').attr('href','/index.php/chat/talk/makefri?uid='+data.id);
			
			if(data.sex==1){
				$('.pull_user_detail .user_name').addClass('ui_male');
			}else{
				$('.pull_user_detail .user_name').addClass('ui_female');
			}
		}
		
		toshow($('.pull_user_detail'));
		$('.pull_user_img').addClass('ui_img_auto_cut')
		img_auto_cut();
	});
	$('.pull_close').click(function(){
		toclose();
	});
	
	$.get('',{submit:1},function(d){
		memberonline = d;
		scan();
	},'json');
	
	
	$('.again_btn').click(function(){
	    useri = 0;
		$.get('',{submit:1},function(d){
		    memberonline = d;
	        scan(true);
		},'json');
	});
	
	$('.get_bonus_btn').click(function(){
		$.get('/index.php/chat/talk/bonus',{submit:1},function(d){
		    if(d>0){
			    alert('成功领到一个红包！');
				location.href = '/index.php/chat/talk/msg?iad='+d;
			}
			if(d=='err:no'){
			    alert('暂无人发布红包！');
			}
			if(d=='err:over'){
			    alert('您来迟了，红包已经被领完！');
			}
		});
	});	
	
    $("input[name=money]").keyup(function(){
	    money = $("input[name=money]").val();
		
		var re = /^[1-9]+[0-9]*]*$/;
		if(!re.test(money)){
            alert("请输入正整数");
			$("input[name=money]").val(parseInt($(".ui_price").html()));
            return false; 
		} 
	
		$(".ui_price").html(money+'.00');
		
    });

})
</script>