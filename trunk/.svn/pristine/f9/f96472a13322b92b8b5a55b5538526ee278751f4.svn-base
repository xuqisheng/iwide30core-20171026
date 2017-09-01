<title><?php echo $msg->title?> - 我的消息</title>
</head>
<style>
body,html{background:#fff}
.webkitbox{padding-bottom:3%; text-align:left}
.webkitbox>*:first-child{max-width:5em;}
.new{float:none; padding:0.8% 0; text-align:center; margin-right:2%}
.tmp{padding-top:5%; margin-top:10%;; text-align:justify}
.ui_foot_fixed_btn{background:#f8f8f8;}
.ui_foot_fixed_btn > *{display:inline-block; border-radius:1rem; background:#ff7200; padding:2% 8%; color:#fff; border:1px solid #e09832;}
</style>
<body>
<div class="pad3">
	<div class="webkitbox">
    	<p class="new h5"><?php echo empty($msg_typs[$msg->msg_typ]) ? '常见问题' : $msg_typs[$msg->msg_typ]?></p>
        <div><?php if($msg->msg_typ == 0):?>
        	<p>酒店给您发放了昨天的收益</p><?php else:?>
        	<p><?php echo $msg->title?></p>
        	<?php endif;?>
            	<p class="h4 co_aaa"><?php echo $msg->create_time?></p>
            </div>
    </div>
    <?php echo $msg->content?>
</div>
<?php if($msg->msg_typ == 0):?>
<div class="ui_foot_fixed_btn">
	<a href="<?php echo site_url('distribute/dis_v1/incomes')?>?id=<?php echo $inter_id?>&pn=<?php echo $msg->remark?>">发放清单</a>
</div>
<?php endif;?>
<?php if(isset($extendtions['package']) && !empty($extendtions['package'])):?>
<style>
.clearfix:after {content: "" ;display:block;height:0;clear:both;visibility:hidden;} 
.receive{width:100%;border-top:1px solid #e4e4e4;padding:0.8rem 0.4rem;box-sizing:border-box;-webkit-box-sizing:border-box;} 
.rec_btn{float:right;border:1px solid #2d87e2;color:#2d87e2;font-size:0.6rem;padding:0.4rem 0.7rem;border-radius:8px;margin-top:0.4rem;}
.rec_img{width:2.8rem;height:2.8rem;overflow:hidden;border-radius:50%;float:left;margin-right:2%;vertical-align:middle;}
.rec_txt{margin-top:0.2rem;}
.rec_txt p{line-height:1.5}
.rec_txt p:nth-of-type(1){color:#555;font-size:0.7rem;}
.rec_txt p:nth-of-type(2){color:#000;font-size:0.75rem;}
</style>
<div class="receive clearfix">
    <a href="<?php echo site_url('distribute/dis_v1/package')?>?id=<?php echo $inter_id?>&mid=<?php echo $this->input->get('mid')?>" class="rec_btn">立即领取</a>
    <img class="rec_img" src="<?php echo $public_info['logo']?>"/>
    <div class="rec_txt">
        <p><?php echo $public_info['name']?></p>
        <p><?php echo $package->name?></p>
    </div>
</div>
<?php endif;?>
<?php if(isset($extendtions['guide'])):?>
<div class="ui_foot_fixed_btn">
	<a href="<?php echo $extendtions['guide']['link']?>"><?php echo $extendtions['guide']['button']?></a>
</div>
<?php endif;?>
</body>
</html>