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
<meta name="viewport" content="width=320.1,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<title><?php echo $dis_conf['page_title']?></title>
<script src="<?php echo base_url("public/member/nvitedkim/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/nvitedkim/js/ui_control.js");?>"></script>
<link href="<?php echo base_url("public/member/nvitedkim/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/nvitedkim/css/mycss.css");?>" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/css/alert.css");?>"/>
<script type="text/javascript" src="<?php echo base_url("public/member/version4.0/js/alert.js");?>"></script>
<style type="text/css">
    .Canon p{background: url(<?php echo base_url("public/member/nvitedkim");?>/images/di_03.jpg) no-repeat;}
</style>
</head>
<body style="height:100%;background:#fff;">
<div class="pageloading"><p class="isload">正在加载</p></div>
<!-------------------------------  以上为公共部分  ---------------------------------->

<div class="head_message color_fff relative">
    <div class="branch3 absolute border">
    	<div class="branch2">
            <div class="branch1 color_fff center" style="padding-top:16%;">
                <p><?php echo $total_number;?></p>
                <p class="bra_text h26">可兑换：<?php echo $isuse_num;?>金</p>
                <p class="bra_text h26 J_btn_exchange j_bnt">兑换</p>
            </div>
        </div>
        <div class="absolute sm_radius bg_fcbc30"></div>
        <div class="absolute sm_radius bg_2d9beb"></div>
        <div class="absolute sm_radius bg_62e269"></div>
    </div>
</div>
<div class="pad10 activity">
	<div class="con_height">
        <div class="center color_5e616d">兑换说明:<br><?php echo isset($reward_info['exchange_note'])?$reward_info['exchange_note']:'';?></div>
        <div class="webkitbox martop pad10px">
            <hr style="border-color:#bebebe;">
            <p class="center">兑换记录</p>
            <hr style="border-color:#bebebe;">
        </div>
        <div class="di_flex marg_10 pad5px center activ_column">
            <p>兑换日期</p>
            <p>使用邀金</p>
            <p>兑换奖品</p>
        </div>
    </div>
    <div class="pad10px j_detail">
        <div class="boxs">
            <?php if(isset($exchange_record) && !empty($exchange_record)):?>
            <?php foreach ($exchange_record as $key => $item):?>
            <div class="di_flex marg_10 pad5px center">
                <p><?php echo date('Y.m.d',$item['createtime']);?></p>
                <p class=""><?php echo $item['use_credit'];?></p>
                <p class="color_main"><?php echo $item['title'];?></p>
            </div>
            <?php endforeach;?>
            <?php endif;?>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
$(function(){
	var w_h=$(window).height();
	if(w_h<=417){
		$(".j_detail").css("height","228px");
	}else{
		var con_h=$(".con_height").height()+$(".head_message").height();
		$(".j_detail").css("height",(w_h-con_h-20)+"px");
	}
	$('.j_Explain').click(function(){
		alert('本页面显示活动邀金，仅含本活动奖励的邀金，个人总其他奖励请前往“东呈酒店”官方微信-个人中心 查看');
	})

//    $(document).on('click','.J_btn_exchange',function () {
//        var btn = $(this);
//        alert(1);
//        var activited_id = "<?php //echo isset($info['id'])?$info['id']:0;?>//";
//        var text = btn.text();
//        btn.text(text+'中...');
////        var loadtip = new AlertBox({content:'正在兑换',type:'loading',site:'mid'}).show();
//        $.getJSON('<?php //echo EA_const_url::inst()->get_url('*/*/exchange_reward')?>//', {
//            activited_id: activited_id,
//        }, function (data){
////            if(loadtip) loadtip.closedLoading();
//            var text = btn.text();
//            btn.text(text.replace('中...', ''));
//            new AlertBox({content:data.msg,type:'info',site:'mid'}).show();
//        });
//    });

    $('.J_btn_exchange').click(function(){
        var btn = $(this);
        var activited_id = "<?php echo isset($info['id'])?$info['id']:0;?>";
        var loadtip = new AlertBox({content:'正在兑换',type:'loading',site:'mid'}).show();
        var postUrl = "<?php echo EA_const_url::inst()->get_url('*/*/exchange_reward')?>";
        var datas = {activited_id: activited_id};
        var text = btn.text();
        btn.text(text+'中...');
        $.ajax({
            url:postUrl,
            type:'POST',
            data:datas,
            dataType:'json',
            timeout:15000,
            success: function (data) {
                if(loadtip) loadtip.closedLoading();
                var text = btn.text();
                btn.text(text.replace('中...', ''));
                new AlertBox({content:data.msg,type:'info',site:'mid'}).show();
            },
            error: function () {
                new AlertBox({content:'发送失败,请刷新重试或联系管理员!',type:'tip',site:'bottom'}).show();return false;
            }
        });
    });
})
</script>
</html>
