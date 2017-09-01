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
<script src="<?php echo base_url("public/member/nvitedkim/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/nvitedkim/js/ui_control.js");?>"></script>
<link href="<?php echo base_url("public/member/nvitedkim/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/nvitedkim/css/mycss.css");?>" rel="stylesheet">
<title><?php echo $dis_conf['page_title']?></title>
<style type="text/css">
    .bg1{background: url(<?php echo isset($dis_conf['background'])?$dis_conf['background']:'';?>) no-repeat;background-size: 100% 100%;}
    .times{left: 45%;}
</style>
</head>
<body class="bg1">
<div class="pageloading"><p class="isload">正在加载</p></div>
	<div class="times color_fff h32">
<!--        --><?php //if(isset($info['start_time']) && !empty($info['start_time'])) echo date('Y.m.d',$info['start_time']).' - ';?>
<!--        --><?php //if(isset($info['end_time']) && !empty($info['end_time'])) echo date('Y.m.d',$info['end_time']);?>
    </div>
	<div class="con_txt color_fff center P_17">
        <?php
            if(isset($dis_conf['act_config']['steps']) && !empty($dis_conf['act_config']['steps']))
                echo $dis_conf['act_config']['steps'];
            else
                echo '';
        ?>
        <br/>
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
    <a class="btn_h h30 center" href="<?php echo $member_jump_url;?>">马上参与</a>
<?php endif;?>
</body>
</html>
