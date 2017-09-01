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
<script src="<?php echo base_url("public/member/nvitedkim/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/nvitedkim/js/alert.js");?>"></script>
<script src="<?php echo base_url("public/member/nvitedkim/js/ui_control.js");?>"></script>
<link href="<?php echo base_url("public/member/nvitedkim/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/nvitedkim/css/mycss.css");?>" rel="stylesheet">
<title><?php echo $dis_conf['page_title']?></title>
</head>
<body style="height:100%;background:#fff;">
<div class="pageloading"><p class="isload">正在加载</p></div>
<!-------------------------------  以上为公共部分  ---------------------------------->

<div class="head_message color_fff relative">
<!--    <a href="" class="color_fff h8 absolute j_Explain"><em class="iconfont">&#x0a12;</em> <span style="text-decoration:underline">--><?php //echo $reward_name;?><!--说明</span></a>-->
    <div class="branch3 absolute border">
    	<div class="branch2">
        	<div class="branch1 color_fff center"><?php echo $total_value;?></div>
        </div>
        <div class="absolute sm_radius bg_fcbc30"></div>
        <div class="absolute sm_radius bg_2d9beb"></div>
        <div class="absolute sm_radius bg_62e269"></div>
    </div>
</div>
<div class="pad10 activity">
	<div class="con_height">
        <div class="webkitbox martop pad10px">
            <hr style="border-color:#bebebe;">
            <p class="center"><?php echo $reward_name;?>明细</p>
            <hr style="border-color:#bebebe;">
        </div>
        <div class="di_flex marg_10 pad5px center">
            <p>姓名</p>
            <p>领取时间</p>
            <?php if(isset($reward_info['mode']) && $reward_info['mode']=='1'):?>
            <p><?php echo $reward_title;?></p>
            <?php endif;?>
        </div>
    </div>
    <div class="pad10px j_detail">
        <div class="boxs">
            <?php foreach ($myrecord_data as $key => $vo):?>
            <div class="di_flex marg_10 pad5px center">
                <p><?php echo $vo['name'];?></p>
                <p class=""><?php echo date('Y-m-d H:i',$vo['reg_time']);?></p>
                <?php if(isset($reward_info['mode']) && $reward_info['mode']=='1'):?>
                <p class="color_main">+<?php echo $vo['reward_count'];?></p>
                <?php endif;?>
            </div>
            <?php endforeach;?>
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
		alert('本页面显示<?php echo $reward_name;?>，仅含本活动奖励的<?php echo $reward_name;?>，个人总<?php echo $reward_name;?>请前往官方微信-个人中心 查看');
	})
})
</script>
</html>
