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
<link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/css/alert.css");?>"/>
<script type="text/javascript" src="<?php echo base_url("public/member/version4.0/js/alert.js");?>"></script>
<title><?php echo $dis_conf['page_title']?></title>
<style type="text/css">
    .Canon p{background: url(<?php echo base_url("public/member/nvitedkim");?>/images/di_03.jpg) no-repeat;}
</style>
</head>
<body style="background:#f4f6f5 !important;">
<div class="pageloading"><p class="isload">正在加载</p></div>
<!-------------------------------  以上为公共部分  ---------------------------------->


<script src="<?php echo base_url("public/member/nvitedkim/js/imgscroll.js");?>"></script>
<script src="<?php echo base_url("public/member/nvitedkim/js/jquery.touchwipe.min.js");?>"></script>
<div class="scroll_box"> 
  <div class="headerslide">
      <a class="slideson ui_img_auto_cut" href="notLogged?OldCardNO={$user_info.0.open_id}">
         <img src="<?php echo isset($dis_conf['banner'])?$dis_conf['banner']:'';?>" />
      </a>
  </div>
</div>
<div class="integral bg_fff f_s_185 border_bottom">
    <img class="cup" src="<?php echo base_url("public/member/nvitedkim");?>/images/cup.png"/>
    <?php if(!empty($total_number)):?>
    <span><?php echo $star_name;?><?php echo $total_number;?><?php echo $unit_name;?></span>
    <?php else:?>
    <span> -- </span>
    <?php endif;?>
</div>
<div class="link_list martop border_top border_bottom bg_fff">
    <a href="<?php echo EA_const_url::inst()->get_url('*/*/rank',array('id'=>isset($user['inter_id'])?$user['inter_id']:0));?>" class="arrow item border_bottom">
        <span class="iconfont h14">&#x0a10;</span>
        <span class="mark"><?php echo $rank_subtitle;?></span>
        <span class="h30"><?php echo $rank_title;?></span>
    </a>
    <a href="javascript:;" class="arrow border_bottom item Invitation">
        <span class="iconfont h14">&#xa3;</span>
        <span class="mark"><?php echo $canon_subtitle;?></span>
        <span class="h30"><?php echo $canon_title;?></span>
    </a>
    <a href="<?php echo EA_const_url::inst()->get_url('*/*/pointdetail',array('id'=>isset($user['inter_id'])?$user['inter_id']:0));?>" class="arrow border_bottom item">
        <span class="iconfont h14">&#xa3;</span>
        <span class="mark"><?php echo $point_subtitle;?></span>
        <span class="h30"><?php echo $point_title;?></span>
    </a>
    <a href="<?php echo EA_const_url::inst()->get_url('*/*/reward',array('id'=>isset($user['inter_id'])?$user['inter_id']:0));?>" class="arrow border_bottom item">
        <span class="iconfont h14">&#xa3;</span>
        <span class="mark"><?php echo $reward_subtitle;?></span>
        <span class="h30"><?php echo $reward_title;?></span>
    </a>
	<a href="<?php echo EA_const_url::inst()->get_url('*/*/actdec',array('id'=>isset($user['inter_id'])?$user['inter_id']:0));?>" class="arrow item">
    	<span class="iconfont h14">&#xa9;</span>
    	<span class="h30">活动说明</span>
    </a>
</div>
<div class="ui_pull" style="display:none;">
  <div class="Canon">
      <h1 class="j_title center">《“邀金”宝典 》</h1>
        <div class="scroll" style="height:70%;">
          <?php echo isset($dis_conf['act_config']['canon'])?$dis_conf['act_config']['canon']:'';?>
        </div>
        <div class="pull_foot_btn center">
            <a class="take_m" style="background: #16a087;" href="javascript:;">朕知道了</a>
            <a class="take_m" href="<?php echo EA_const_url::inst()->get_url('*/*/raiders',array('id'=>isset($user['inter_id'])?$user['inter_id']:0));?>">拿下邀金</a>
        </div>
    </div>
</div>
</body>
<script>
/*初始化滚动  imgrate为图片大小*/
$.fn.imgscroll({ partent_div:'scroll_box' , imgrate: 640/325,circlesize:'7px' });
$(function(){
    var w_h=$(window).height();
    if(w_h<=417){
        $("body,html").css("min-height",w_h+"px");
        $(".pull_foot_btn >.take_m").css("margin-top","7%");
    }
    $('.Invitation').click(function(){
        $('.ui_pull').show();
        $('.move_none').click(function(){
            $('.ui_pull').hide();
        });
    });
    $('.take_m').click(function(){
        $('.ui_pull').hide();
    });
})
</script>
</html>
