<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=320,initial-scale=1,user-scalable=0">
<link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/css/alert.css");?>"/>
<link href="<?php echo base_url("public/member/nvitedkim/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/nvitedkim/css/mycss.css");?>" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.css");?>"/>
<link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.min.css");?>"/>
<link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/example/example.css");?>"/>
<script src="<?php echo base_url("public/member/nvitedkim/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/nvitedkim/js/ui_control.js");?>"></script>
<script src="<?php echo base_url(FD_PUBLIC)?>/js/ajaxForm.js"></script>
<script type="text/javascript" src="<?php echo base_url("public/member/version4.0/js/alert.js");?>"></script>
<title><?php echo $dis_conf['page_title']?></title>
<style type="text/css">
    .weui_cell_ft{position: absolute; right: 0;display: none;}
    .weui_cell_warn .weui_cell_ft{display: inline-block;}
    .establish{display: inline-block;}
    .bg1{background: url(<?php echo isset($dis_conf['background'])?$dis_conf['background']:'';?>) no-repeat;background-size: 100% 100%;}
    .times{left: 45%;}
    .weui_input{width: auto;}
</style>
</head>
<body class="bg1">
<div class="pageloading"><p class="isload">正在加载</p></div>
<script>
pageloading();
</script>
	<div class="times color_fff h32">
<!--        --><?php //if(isset($info['start_time']) && !empty($info['start_time'])) echo date('Y.m.d',$info['start_time']).' - ';?>
<!--        --><?php //if(isset($info['end_time']) && !empty($info['end_time'])) echo date('Y.m.d',$info['end_time']);?>
    </div>
	<div class="color_fff center h30 form_txt">
        <?php if(isset($_GET['share']) && !empty($_GET['share'])):?>
        <?php
        if(isset($dis_conf['face_invite_config']['invitation_toface']) && !empty($dis_conf['face_invite_config']['invitation_toface']))
            echo str_replace(',', '', $dis_conf['face_invite_config']['invitation_toface']);
        else
            echo '';
        ?>
        <?php endif;?>
        <?php
        if(!isset($info) || empty($info)){
            echo '抱歉，活动消失了...';
        }else{
            if($info['start_time']>time()){
                echo '來早啦，活动还没开始呢！';
            }elseif ($info['isopen']=='2'){
                echo '活动已关闭';
            }
        }
        ?>
    </div>
<?php if(isset($info) && !empty($info) && $info['start_time']<=time() && $info['isopen']=='1'):?>
    <form id="fomr_con" class="fomr_con" action="<?php echo $save_url;?>" method="post" >
    	<input type="hidden" name="share" data-name="share" data-check="0" value="<?php echo isset($share)?$share:'';?>">
        <input type="hidden" name="actId" data-name="actId" data-check="0" value="<?php echo isset($info['id'])?$info['id']:0;?>">
        <input type="hidden" name="channel" data-name="channel" data-check="0" value="<?php echo isset($channel)?$channel:0;?>">
        <input type="hidden" name="smstype" data-name="smstype" data-check="0" value="0" />
        <div class="bg_fff fill_txt center">
            <?php if(!empty($register_config)):?>
            <?php
                if(isset($save_user)){
                    if(!isset($save_user['telephone']) || empty($save_user['telephone']))
                        $save_user['telephone'] = isset($save_user['cellphone'])?$save_user['cellphone']:'';
                }
            ?>
            <?php foreach ($register_config as $key => $item):?>
                <?php if($key!='phonesms'):?>
                    <?php if($item['type']!='select'):?>
                    <div class="txt_input m_b_10 border_e1e1e1 <?php echo $key;?>">
                        <input class="iphone" type="<?php echo $item['type'];?>" name="<?php echo $key;?>" pattern="<?php echo $item['regular']; ?>" data-name="<?php echo $item['name'];?>" data-check="<?php echo $item['check']; ?>" placeholder="<?php echo $item['note'];?>" value="<?php if(isset($save_user[$key]) && !empty($save_user[$key])) echo $save_user[$key];?>" />
                        <div class="weui_cell_ft">
                            <i class="weui_icon_warn"></i>
                        </div>
                    </div>
                    <?php elseif($key=='sex'):?>
                    <div class="txt_input m_b_10 border_e1e1e1 <?php echo $key;?>">
                        <select class="weui_input" data-name="<?php echo $item['name'];?>" name="<?php echo $key;?>">
                            <option value="3" <?php if(isset($save_user[$key]) && $save_user[$key]=='3') echo 'selected';?>><?php echo $item['note'];?></option>
                            <option value="2" <?php if(isset($save_user[$key]) && $save_user[$key]=='2') echo 'selected';?>>女</option>
                            <option value="1" <?php if(isset($save_user[$key]) && $save_user[$key]=='1') echo 'selected';?>>男</option>
                        </select>
                    </div>
                    <?php endif;?>
                <?php elseif($item['type']!='select'):?>
                    <div class="txt_input m_b_10 border_e1e1e1 <?php echo $key;?>">
                        <div class="obtian_code h26 center" style="width: 80px;">获取验证码</div>
                        <input class="code" data-name="<?php echo $item['name'];?>" name="<?php echo $key;?>" type="<?php echo $item['type'];?>" pattern="<?php echo $item['regular']; ?>" data-check="<?php echo $item['check']; ?>" placeholder="<?php echo $item['note'];?>" />
                        <div class="weui_cell_ft">
                            <i class="weui_icon_warn"></i>
                        </div>
                    </div>
                <?php endif;?>
            <?php endforeach;?>
            <botton type="submit" class="m_b_10 establish weui_btn_primary"><?php echo $save_name;?></botton>
            <?php endif;?>
            <?php if(isset($member_sync['value']) && $member_sync['value']=='perfect'):?>
            <div class="m_b_10 h24">已是完善会员信息？<a class="land" href="<?php echo EA_const_url::inst()->get_url('*/*/raiders',array('id'=>isset($user['inter_id'])?$user['inter_id']:0));?>">去抢邀金</a></div>
            <?php elseif(isset($member_sync['value']) && $member_sync['value']=='login'):?>
            <div class="m_b_10 h24">已是会员？<a class="land" href="<?php echo EA_const_url::inst()->get_url('*/*/login',array('id'=>isset($user['inter_id'])?$user['inter_id']:0));?>">去登录</a></div>
            <?php endif;?>
        </div>
    </form>
<?php endif;?>
<script>
removeload();
if($(window).height()>504){
	$(".form_txt").css("padding-top","58%");
}
if($(window).height()>416){
	$(".fomr_con").css({"position":"absolute","bottom":"0px","width":"100%"});
}
$(function(){
    var inter_id = "<?php echo $inter_id;?>";
    var numbe=59,times,sendto=1;
	var times;
	var iphone_nmu=/[1][0-9]{10}/g;
	$(document).on("click",".obtian_code",function(){
        if(sendto==1){
            var tel=$("input[name='phone']").val(),phonesms=$("input[name='phonesms']").val(),smstype=$("input[name='smstype']").val();
            var regular = new RegExp($("input[name='phone']").attr('pattern'));
            if(!tel || tel==''){
                new AlertBox({content:'请输入手机号码',type:'tip',site:'bottom'}).show();return false;
            }else if(!regular.test(tel)){
                new AlertBox({content:'请输入正确的手机号码',type:'tip',site:'bottom'}).show();return false;
            }

            //请求发送验证码
            var postUrl = "<?php echo site_url("membervip/sendsms");?>";
            var datas = {phone:tel,phonesms:phonesms,smstype:smstype};
            $.ajax({
                url:postUrl,
                type:'POST',
                data:datas,
                dataType:'json',
                timeout:6000,
                success: function (result) {
                    if(result.err=='0'){
                        new AlertBox({content:'短信已发送,请注意查收!',type:'tip',site:'bottom'}).show();
                    }
                },
                error: function () {
                    new AlertBox({content:'发送失败,请刷新重试或联系管理员!',type:'tip',site:'bottom'}).show();
                    return false;
                }
            });
        }
        sendto = 0;
        autopay_time();
	});
	function autopay_time(){
        $(".obtian_code").html('重新发送'+numbe+"s");
        times=setInterval(function(){
            numbe--;
            $(".obtian_code").html('重新发送'+numbe+"s");
			if(numbe==0){
				clearInterval(times);
				$(".obtian_code").html("重发验证码");
                numbe=59;
                sendto=1;
			}
		},1000);
	}

    /* 检测用户输入的是否合法 START */
    $("input").keyup(function(){
        var regular = new RegExp($(this).attr('pattern'));
        var inputValue = $(this).val();
        var inputName = $(this).attr('name');
        if(!regular.test(inputValue) && $(this).data("check")==1){
            $("."+inputName+"").addClass('weui_cell_warn');
            $(".weui_btn_primary").addClass('weui_btn_disabled');
        }else{
            $("."+inputName+"").removeClass('weui_cell_warn');
            $(".weui_btn_primary").removeClass('weui_btn_disabled');
        }
    });
    //失去焦点判断
    $("input").focusout(function(){
        var regular = new RegExp($(this).attr('pattern'));
        var inputValue = $(this).val();
        var inputName = $(this).attr('name');
        if(!regular.test(inputValue) && $(this).data("check")==1){
            $("."+inputName+"").addClass('weui_cell_warn');
            $(".weui_btn_primary").addClass('weui_btn_disabled');
        }else{
            $("."+inputName+"").removeClass('weui_cell_warn');
            $(".weui_btn_primary").removeClass('weui_btn_disabled');
        }
    });
    /* 检测用户输入的是否合法 END */

    /* 提交信息 START */
    $('.weui_btn_primary').click(function(){
        var form = $("#fomr_con"),form_url=form.attr("action"),btn = $(this),loadtip=null;
        var share=$("input[name='share']").val();
        form.ajaxSubmit({
            url:form_url,
            dataType:'json',
            timeout:30000,
            beforeSubmit: function(arr){
                /*验证提交数据*/
                var _null = false, _msg = '',inputobj={};
                for(i in arr){
                    var name = arr[i].name,value=$.trim(arr[i].value),obj=$("input[name='"+name+"']"),check=obj.data("check");
                    var regular = new RegExp(obj.attr('pattern'));
                    if(!value && (name!='share' && name!='actId')) {
                        inputobj = obj;
                        var text_name = obj.data("name");
                        _msg='请输入'+text_name;
                        _null = true;break;
                    }else if(!regular.test(value) && check==1 && (name!='share' && name!='actId')){
                        inputobj = obj;
                        var text_name = obj.data("name");
                        _msg=text_name+'不合法';
                        _null = true;break;
                    }
                }
                if(_null === true) {
                    new AlertBox({content:_msg,type:'tip',site:'bottom',time:2000}).show();
                    inputobj.focus();
                    return false;
                }
                /*end*/

                var text = btn.text();
                btn.prop('disabled', true).addClass('weui_btn_disabled').text(text+'中...');
                loadtip = new AlertBox({content:'正在<?php echo $save_name;?>',type:'loading',site:'mid'}).show();
            },
            success: function(result){
                if(loadtip) loadtip.closedLoading();
                var text = btn.text();
                btn.prop('disabled',false).removeClass('weui_btn_disabled').text(text.replace('中...', ''));
                if(result.err>1){
                    new AlertBox({content:result.msg,type:'info',site:'mid'}).show();
                }else if(result.err=='0'){
                    var is_package = 2;
                    if(inter_id=='a421641095' && result.is_package=='1'){
                        is_package = 1;
                    }
                    if(share && share!=''){
                        handle_trans(is_package);
                    }else{
                        handle_send_tmp();
                    }

//                    var is_sub = "<?php //echo $is_sub;?>//";
                    var share_memid = "<?php echo $share_memid;?>";
                    if(!share || share=='')
                        var locat_url="<?php echo site_url('membervip/invitatedkim');?>";
                    else
                        var locat_url = "<?php echo site_url('membervip/invitatedkim/subscribe');?>"+'?mid='+share_memid;
                    new AlertBox({content:'<?php echo $save_msg?>成功',type:'tip',site:'bottom',dourl:locat_url,time:300}).show();
                }
            },
            error:function () {
                if(loadtip) loadtip.closedLoading();
                var text = btn.text();
                btn.prop('disabled',false).removeClass('weui_btn_disabled').text(text.replace('中...', ''));
                new AlertBox({content:'网络异常,请求失败,请刷新重试或联系管理员!',type:'tip',site:'bottom'}).show();return false;
            }
        });
    });
    /* 提交信息 END */

    function handle_trans(is_package) {
        var share=$("input[name='share']").val(),actId=$("input[name='actId']").val(),channel=$("input[name='channel']").val(),post_url="<?php echo site_url('membervip/invitatedkim/handle_trans');?>";
        $.ajax({
            url:post_url,
            type:'POST',
            data:{share:share,actId:actId,channel:channel},
            dataType:'json',
            timeout:20000,
            success: function (result) {
                result.data.is_package = is_package;
                if(result.status=='1' && result.issend=='1'){
                    send_tmp_msg(result.data);
                }
                console.log(result);
            },
            error: function (XMLHttpRequest, textStatus) {
                console.log(XMLHttpRequest);
                console.log(textStatus);
//                new AlertBox({content:'发送失败,请刷新重试或联系管理员!',type:'tip',site:'bottom'}).show();return false;
            }
        });

    }

    function send_tmp_msg(datas) {
        var post_url="<?php echo site_url('membervip/invitatedkim/send_tmp_msg');?>",name = $("input[name='name']").val();
        datas.regname=name;
        $.ajax({
            url:post_url,
            type:'get',
            data:datas,
            dataType:'json',
            timeout:20000,
            success: function (result) {
                console.log(result);
            },
            error: function (XMLHttpRequest, textStatus) {
                console.log(XMLHttpRequest);
                console.log(textStatus);
//                new AlertBox({content:'发送失败,请刷新重试或联系管理员!',type:'tip',site:'bottom'}).show();return false;
            }
        });
    }

    function handle_send_tmp() {
        var name = $("input[name='name']").val(),post_url="<?php echo site_url('membervip/reg/send_tmp_msg');?>";
        $.ajax({
            url:post_url,
            type:'get',
            data:{name:name},
            dataType:'json',
            timeout:20000,
            success: function (result) {
                console.log(result);
            },
            error: function (XMLHttpRequest, textStatus) {
                console.log(XMLHttpRequest);
                console.log(textStatus);
//                new AlertBox({content:'发送失败,请刷新重试或联系管理员!',type:'tip',site:'bottom'}).show();return false;
            }
        });
    }
})
</script>
</body>
</html>
