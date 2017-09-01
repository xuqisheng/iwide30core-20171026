<!doctype html>
<html style="background:#f8f8f8;">
<head>
<meta charset="utf-8">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="yes" name="apple-touch-fullscreen">
<meta content="telephone=no,email=no" name="format-detection">
<meta content="fullscreen=yes,preventMove=no" name="ML-Config">
<script src="script/viewport.js"></script>
<title>送情意</title>

<script src="scripts/jquery-1.11.3.min.js"></script>
<link href="<?php echo base_url('public/mall/multi/style/global.css')?>" rel="stylesheet">
<style>
body,html{ background:#f0eff5;}
.first{color:#ababad; padding:4% 0; text-align:center; font-size:0.65rem;}
.store{border:1px solid #e2e2e2; border-right:0; border-left:0;}
.list{ border-top:1px #e2e2e2 solid; background:#fff; padding:3% 4%; padding-right:0;font-size:0.6rem;}
.list:first-child{ border-top:none;}
.list .range{float:right}
.list .name{font-size:0.7rem; color:#444; margin-bottom:2%;}
.list .range,.list .address{color:#858585;}
.list .ico{border-left:1px solid #e2e2e2;float:right; padding:2% 6%; margin-left:4%; width:1rem;}
.list .ico img{ width:100%;}
</style>
</head>
<body>
<!-- 只有一个门店时不显示这行-->
<div class="first">仅展示可领取门店</div>
<!--  end   -->
<div class="store">
    <?php foreach($business_list as $store):?>
    <div class="list">
    	<div class="ico"><img src="<?php echo base_url('public/mall/multi/images/ico/loactionIco.png')?>" /></div>
    	<div class="left">
            <div class="name"><?php echo $store->base_info->business_name?></div>
            <div class="address"><?php echo $store->base_info->province.$store->base_info->city.$store->base_info->district.$store->base_info->address?></div>
        </div>
    </div><?php endforeach;?>
</div>
</body>
</html>
