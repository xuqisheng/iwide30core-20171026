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
<body style="height:100%;background:#f4f6f5">
<div class="pageloading"><p class="isload">正在加载</p></div>
<!-------------------------------  以上为公共部分  ---------------------------------->

<div class="container">
    <div class="di_flex head_t center coupon_pull border_bottom">
        <a class="<?php echo $action_type=='1'?'active':'';?>" href="<?php echo EA_const_url::inst()->get_url('*/*/rank',array('id'=>isset($user['inter_id'])?$user['inter_id']:0));?>">当日排行</a>
        <a class="<?php echo $action_type=='2'?'active':'';?>" href="<?php echo EA_const_url::inst()->get_url('*/*/rank',array('id'=>isset($user['inter_id'])?$user['inter_id']:0,'type'=>2));?>">当月排行</a>
        <a class="<?php echo $action_type=='3'?'active':'';?>" href="<?php echo EA_const_url::inst()->get_url('*/*/rank',array('id'=>isset($user['inter_id'])?$user['inter_id']:0,'type'=>3));?>">总排行</a>
    </div>
    <div class="my_name clearfix pad15 bg_fff">
        <div class="head_img"><a href="<?php echo EA_const_url::inst()->get_url('*/*/myrecord',array('id'=>isset($user['inter_id'])?$user['inter_id']:0));?>"><img src="<?php echo isset($myrank['headimgurl'])?$myrank['headimgurl']:'';?>" /></a></div>
        <div class="right_txt h32 m_t_5">当前排行:<font class="color_ff950d h36 weight"><?php echo isset($myrank['ranking'])?$myrank['ranking']:'无';?></font></div>
        <div class="name_txt">
            <p><?php echo isset($myrank['name'])?$myrank['name']:'';?></p>
            <p class="h26 m_t_1">推荐数：<?php echo isset($myrank['total_recom'])?$myrank['total_recom']:0;?></p>
        </div>
    </div>
</div>
<div class="pad10px bg_fff j_detail">
    <div class="boxs pad15">
        <?php if(!empty($first_list)):?>
        <?php foreach ($first_list as $key=>$vo):?>
        <div class="my_name clearfix bg_fff">
            <div class="head_img"><img src="<?php echo $vo['headimgurl'];?>" /></div>
            <div class="right_txt medal m_t_2"><img src="<?php echo $vo['icon'];?>" /></div>
            <div class="name_txt">
                <p><?php echo $vo['name'];?></p>
                <p class="h26 m_t_1">推荐数：<?php echo $vo['total_recom'];?></p>
            </div>
        </div>
        <?php endforeach;?>
        <?php endif;?>
        <?php if(!empty($rank_list)):?>
        <?php foreach ($rank_list as $key=>$vo):?>
        <div class="my_name clearfix bg_fff">
            <div class="head_img"><img src="<?php echo $vo['headimgurl'];?>" /></div>
            <div class="right_txt h36 weight m_t_5 color_b7b7b7 p_r_3">4</div>
            <div class="name_txt">
                <p><?php echo $vo['name'];?></p>
                <p class="h26 m_t_1">推荐数：<?php echo $vo['total_recom'];?></p>
            </div>
        </div>
        <?php endforeach;?>
        <?php endif;?>
    </div>
</div>
</body>
<script type="text/javascript">
$(function(){
	var w_h=$(window).height();
	if(w_h<=417){
		$(".j_detail").css("height","371");
	}else{
		var con_h=$(".container").height();
		$(".j_detail").css("height",(w_h-con_h)+"px");
	}
})
</script>
</html>