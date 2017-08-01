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
<title><?php echo $dis_conf['page_title']?></title>
<script src="<?php echo base_url("public/member/nvitedkim/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/nvitedkim/js/ui_control.js");?>"></script>
<link href="<?php echo base_url("public/member/nvitedkim/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/nvitedkim/css/mycss.css");?>" rel="stylesheet">
</head>
<body class="bg1" style="height:100%">
<div class="pageloading"><p class="isload">正在加载</p></div>
<div class="empty" style="height:250px;"></div>
<div class="pad10 activity">
	<div class="con_height">
        <div class="webkitbox martop pad10px">
            <hr style="border-color:#bebebe;">
            <p class="center">奖励记录</p>
            <hr style="border-color:#bebebe;">
        </div>
        <div class="di_flex marg_10 pad5px center activ_column">
            <p>奖励日期</p>
            <p>获得奖励</p>
            <p>数量</p>
        </div>
    </div>
    <div class="pad10px j_detail" style="font-size:13px;">
        <div class="boxs">
            <?php if(isset($reward_record) && !empty($reward_record)):?>
                <?php foreach ($reward_record as $key => $item):?>
                    <div class="di_flex marg_10 pad5px center">
                        <p><?php echo date('Y.m.d',$item['createtime']);?></p>
                        <p class=""><?php echo isset($item['reward_name'])?$item['reward_name']:'';?></p>
                        <p class="color_main"><?php echo isset($item['reward_count'])?$item['reward_count']:'';?></p>
                    </div>
                <?php endforeach;?>
            <?php else:?>
                <div class="di_flex marg_10 pad5px center" style="margin: 0 auto; width: 37%;">
                    本次活动暂无个人奖励
                </div>
            <?php endif;?>
        </div>
    </div>
</div>
<script>
$(function(){
	var w_h=$(window).height();
	if(w_h<=417){
		$(".j_detail").css("height","228px");
	}else{
		var con_h=$(".con_height").height()+$(".empty").height();
		$(".j_detail").css("height",(w_h-con_h-20)+"px");
	}
})
</script>
</body>
</html>
