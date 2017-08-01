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
<title>新增/编辑信息</title>
    <?php echo referurl('css','global.css',1,$media_path) ?>
    <?php echo referurl('css','service.css',1,$media_path) ?>
    <?php echo referurl('js','jquery.js',1,$media_path) ?>
    <?php echo referurl('js','ui_control.js',1,$media_path) ?>
    <?php echo referurl('js','alert.js',1,$media_path) ?>

    <script type='text/javascript'>
        var _vds = _vds || [];
        window._vds = _vds;
        (function(){
            _vds.push(['setAccountId', '9035a905d6d239a4']);
            (function() {
                var vds = document.createElement('script');
                vds.type='text/javascript';
                vds.async = true;
                vds.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'dn-growing.qbox.me/vds.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(vds, s);
            })();
        })();
    </script>
    <script type='text/javascript' src='https://assets.growingio.com/sdk/wx/vds-wx-plugin.js'></script>
</head>
<body>
<style>
a:after{border-color:#f0f0f0}
</style>
<div class="pageloading"></div>
<page class="page">
	<header></header>
    <section class="scroll flexgrow h26">
    	<div class="list_style_1">
            <div class="webkitbox justify input_item">
            	<span>联系人</span>
                <span><input placeholder="您的姓名" class="cachedata" name="contact" value="<?php echo isset($user_address['contact'])?$user_address['contact']:''?>"></span>
            </div>
            <div class="webkitbox justify input_item">
            	<span>电话</span>
                <span><input placeholder="联系电话" class="cachedata" name="phone" value="<?php echo isset($user_address['phone'])?$user_address['phone']:''?>"></span>
            </div>
            <!--
            <?php if($inter_id !='a469543253'&& $inter_id !='a469428180'){?>
            <a href="<?php echo site_url('ticket/ticket/map?id='.$inter_id.'&hotel_id='.$hotel_id.'&shop_id='.$shop_id .(empty($addr_id)?'':'&addr_id='.$addr_id))?>" class="webkitbox justify input_item">
            	<span>所在地区</span>
                <span><input placeholder="小区/写字楼/学校等" class="cachedata" name="select_addr" value="<?php echo isset($user_address['select_addr'])?$user_address['select_addr']:''?>"></span>
            </a>
            <div class="webkitbox justify input_item">
            	<span>详细地址</span>
                <span><input placeholder="详细地址" class="cachedata" name="address" value="<?php echo isset($user_address['address'])?$user_address['address']:''?>"></span>
            </div>
                <input type="hidden" name="longitude" value="<?php echo isset($user_address['longitude'])?$user_address['longitude']:''?>">
                <input type="hidden" name="latitude" value="<?php echo isset($user_address['latitude'])?$user_address['latitude']:''?>">
            <?php }else{//定制?>
                <a href="javascript:void(0)" class="webkitbox justify input_item" style="display: none;">
                    <span>所在地区</span>
                    <span><input placeholder="小区/写字楼/学校等" class="cachedata" name="select_addr" value="东莞电台"></span>
                </a>
                <div class="webkitbox justify input_item" style="display: none;">
                    <span>详细地址</span>
                    <span><input placeholder="详细地址" class="cachedata" name="address" value="耳朵去旅行"></span>
                </div>
                <input type="hidden" name="longitude" value="1">
                <input type="hidden" name="latitude" value="1">
            <?php }?>
            -->
            <input type="hidden" name="addr_id" value="<?php echo isset($addr_id)?$addr_id:''?>">
        </div>
    </section>
    <footer class="pad10">
        <button class="bg_main center pad10 bdradius" style="width:100%" type="button" id="saveAddr">保存联系信息</button>
    </footer>
</page>
</body>
<script>
	var user;
    $(function(){
		user= $.getsession('user');
		console.log(user)
		if(user!=''){
			try{
				user = $.parseJSON(user);
				$('input[ name="contact"]').val(user.contact);
				$('input[ name="phone"]').val(user.phone);
				$('input[ name="select_addr"]').val(user.select_addr);
				$('input[ name="address"]').val(user.address);
				if(user.latLng!=undefined){
					$("input[name='latitude']").val(user.latLng.lat);
					$("input[name='longitude']").val(user.latLng.lng);
				}
			}
			catch(e){
				//$.MsgBox.Alert('没有获取到正确的坐标');不提示直接清空数据
				user= {}
			}
		}else{
			user= {}
		}
        <?php if($inter_id !='a469543253'&& $inter_id !='a469428180'){?>
		$('input').change(function(){
			if($(this).val()!=''){
				user[$(this).attr('name')]=$(this).val();
				$.setsession('user',JSON.stringify(user));
			}
        });
        <?php }?>
        $('#saveAddr').click(function(){
            if($("input[name='contact']").val() == ''){
                $.MsgBox.Alert('姓名不能为空');
                return false;
            }
            if($("input[name='phone']").val() == ''){
                $.MsgBox.Alert('电话不能为空');
                return false;
            }
            /*
            if($("input[name='select_addr']").val() == ''){
                $.MsgBox.Alert('所在地区不能为空');
                return false;
            }
            if($("input[name='address']").val() == ''){
                $.MsgBox.Alert('详细地址不能为空');
                return false;
            }
            */
			$('input').each(function(){
				user[$(this).attr('name')]=$(this).val();
				$.setsession('user',JSON.stringify(user));
			});
            $.post('<?php echo site_url('ticket/ticket/saveAddress');?>',{
                'hotel_id':'<?php echo $hotel_id;?>',
                'shop_id':'<?php echo $shop_id?>',
                'contact':$("input[name='contact']").val(),
                'phone':$("input[name='phone']").val(),
                'select_addr':$("input[name='select_addr']").val(),
                'address':$("input[name='address']").val(),
                'longitude':$("input[name='longitude']").val(),
                'latitude':$("input[name='latitude']").val(),
                'addr_id':$("input[name='addr_id']").val(),
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },function(data){
                if(data.errcode == 0){
                    $.MsgBox.Alert(data.msg,function(){
                        $.setsession('user','');
                    	window.location.href = data.data.url;
					});
					$('#mb_btn_no').remove();
                }else{
                    $.MsgBox.Alert(data.msg);
                }
            },'json');
        });

    });
</script>
</html>
