<!doctype html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<script src="<?php echo base_url("public/member/public/js/viewport.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/ui_control.js");?>"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui_style.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/index.css");?>" rel="stylesheet">
<title><?php echo $publicinfo['name'];?></title>
</head>
<body>
<div class="head">
    <div class="padding">
        <?php if(isset($membermodel) && $membermodel=='login') {?>
            <?php if($member->is_login == 0) {?>
	        <div class="user_data"><a href="<?php echo base_url("index.php/member/account/register");?>">注册会员</a></div>
            <?php } else {?>
            	<div class="user_data"><a href="<?php echo base_url("index.php/member/perfectinfo");?>">完善资料</a></div>
            <?php } ?>
        <?php } else {?>
		    <?php if(isset($member) && !$member->is_active){?>
	            <div class="user_data"><a href="<?php echo base_url("index.php/member/perfectinfo");?>">完善资料</a></div>
	        <?php } else { ?>
                <div class="user_data"><a href="<?php echo base_url("index.php/member/perfectinfo");?>">修改资料</a></div>
            <?php } ?>
        <?php } ?>
        <div class="user_img"><a href="<?php if(isset($member)) {echo base_url("index.php/member/center/userinfo")."?mem_id=".$member->mem_id;} else {echo base_url("index.php/member/center/userinfo");}?>"><img src="<?php if(isset($info)) echo $info['headimgurl'];?>"></a></div>
       <!--  <div class="user_name"><?php if(isset($member) && isset($member->name) && !empty($member->name)) {echo $member->name;} elseif(isset($info)) { echo $info['nickname'];}?></div> -->
       <div class="user_name">
       		<?php
       			if(isset($member->is_login) && $member->is_login == 1)
       				{echo $member->name;}
       			else
       				{ echo $info['nickname'];}
       		?>
       	</div>
        <div class="viplv_black">
            <?php
                if($member->is_login==1){
                    if(isset($member->levelinfo)) {
                        echo $member->levelinfo;
                    } else {
                        echo "微信粉丝";
                    }
                }else{

                    echo "微信粉丝";
                }
            ?>
        </div>
    </div>
    <div class="mask">
    	<a href="<?php echo base_url("index.php/member/crecord/cards");?>" class="item">
        	<div><?php echo $card_num;?></div>
            <div>我的卡券</div>
        </a>
    	<a href="<?php echo base_url("index.php/member/crecord/balances");?>" class="item">
        	<div><?php if(isset($member)) {echo $member->balance;} else {echo "0";}?></div>
            <div>我的余额</div>
        </a>
    	<a href="<?php if($inter_id == 'a421641095'){echo '#';}else{ echo base_url("index.php/member/crecord/bonus");}?>" class="item">
        	<div><?php if(isset($member)) {echo $member->bonus;} else {echo "0";}?></div>
            <div>我的积分</div>
        </a>
    </div>
</div>

<?php foreach($basicinfo as $group) {?>
<div class="ui_btn_list ui_border">
    <?php foreach($group as $info) {?>
        <?php if($info['module']=='member') {?>
	        <a href="<?php if(isset($membermodel) && $membermodel=='login' && !$member->is_login) { echo base_url("index.php/member/account/login"); } else { echo base_url("index.php/member/center/userinfo?mem_id=".$member->mem_id);}?>" class="item">
	    	    <em class="ui_ico <?php echo $info['icocss'];?>"></em>
	    	    <tt><?php echo $info['name'];?><?php if(isset($membermodel) && $membermodel=='login' && $member->is_login && !empty($member->membership_number)) echo "(".$member->membership_number.")";?></tt>
	    	    <?php if(isset($membermodel) && $membermodel=='login' && !$member->is_login) {?>
	    	    <span>会员登录</span>
	    	    <?php } ?>
	        </a>
	    <?php } elseif($info['module']=='membercharge') {?>
	        <a href="<?php if(isset($membermodel) && $membermodel=='login' && !$member->is_login) { echo base_url("index.php/member/account/login"); } else { echo base_url("index.php/member/ccharge/gocharge");}?>" class="item">
	    	    <em class="ui_ico <?php echo $info['icocss'];?>"></em>
	    	    <tt><?php echo $info['name'];?><?php if(isset($membermodel) && $membermodel=='login' && $member->is_login && !empty($member->membership_number)) echo "(".$member->membership_number.")";?></tt>
	    	    <?php if(isset($membermodel) && $membermodel=='login' && !$member->is_login) {?>
	    	    <span>会员登录</span>
	    	    <?php } ?>
	        </a>
        <?php } elseif($info['module']=='address') {?>
            <a href="<?php if(isset($member)) {echo base_url("index.php/member/center/addresslist")."?memid=".$member->mem_id;} else {echo base_url("index.php/member/center");}?>" class="item">
		    	<em class="ui_ico <?php echo $info['icocss'];?>"></em>
		    	<tt><?php echo $info['name'];?></tt>
		    </a>
		<?php } elseif($info['module']=='qrcode'&&(isset($membermodel) && $membermodel=='login' && $member->is_login&&$this->inter_id=='a421641095')) {?>
		    <a href="<?php echo base_url("index.php/member/center/qrcode")?>" class="item">
		    	<em class="ui_ico <?php echo $info['icocss'];?>"></em>
		    	<tt><?php echo $info['name'];?></tt>
		    </a>
		<?php } elseif($info['module']=='cardstore') {?>
		    <a href="<?php echo base_url("index.php/member/cardstore")?>" class="item">
		    	<em class="ui_ico <?php echo $info['icocss'];?>"></em>
		    	<tt><?php echo $info['name'];?></tt>
		    </a>
		<?php } elseif($info['module']=='link' && !empty($info['link'])) {?>
			<?php if($info['name']=='分销中心') {?>
		    	<?php if(isset($isDistribution)&&$isDistribution==1){ ?>
			    	<a href="<?php echo $info['link'];?>" class="item">
				    	<em class="ui_ico <?php echo $info['icocss'];?>"></em>
				    	<tt><?php echo $info['name'];?></tt>
				    </a>
		    	<?php }?>
            <?php }elseif($info['name']=='社群客') { ?>
                <?php if(isset($is_club)&&$is_club==1){ ?>
                    <a href="<?php echo $info['link'];?>" class="item">
                        <em class="ui_ico <?php echo $info['icocss'];?>"></em>
                        <tt><?php echo $info['name'];?></tt>
                    </a>
                <?php }?>
		    <?php }else{ ?>
			    <a href="<?php echo $info['link'];?>" class="item">
			    	<em class="ui_ico <?php echo $info['icocss'];?>"></em>
			    	<tt><?php echo $info['name'];?></tt>
			    </a>
		    <?php }?>

		<?php } elseif($info['module']=='mycard') {?>
		    <a href="<?php echo base_url("index.php/member/crecord/cards");?>" class="item">
		    	<em class="ui_ico <?php echo $info['icocss'];?>"></em>
		    	<tt><?php echo $info['name'];?></tt>
		    </a>
	    <?php } elseif($info['module']=='mycharge') {?>
		    <a href="<?php echo base_url("index.php/member/crecord/balances");?>" class="item">
		    	<em class="ui_ico <?php echo $info['icocss'];?>"></em>
		    	<tt><?php echo $info['name'];?></tt>
		    </a>
	    <?php } elseif($info['module']=='mybonus') {?>
		    <a href="<?php echo base_url("index.php/member/crecord/bonus");?>" class="item">
		    	<em class="ui_ico <?php echo $info['icocss'];?>"></em>
		    	<tt><?php echo $info['name'];?></tt>
		    </a>
		<?php } elseif($info['module']=='vcard') {?>
		    <a href="<?php echo base_url("index.php/member/cardstore/myvcard")?>" class="item">
		    	<em class="ui_ico <?php echo $info['icocss'];?>"></em>
		    	<tt><?php echo $info['name'];?></tt>
		    </a>
		<?php } elseif($info['module']=='yzvcard') {?>
		    <a href="<?php echo base_url("index.php/member/yuanzhou/yzvcard/bind");?>" class="item">
		    	<em class="ui_ico <?php echo $info['icocss'];?>"></em>
		    	<tt><?php echo $info['name'];?></tt>
		    </a>
		<?php } ?>
    <?php } ?>
</div>
<?php } ?>
<div id="show_message" style="display:none"><?php if(isset($message)) echo $message;?></div>
<script>
$(document).ready(function(){
	if($("#show_message").html().length) {
		alert($("#show_message").html());
	}
})
</script>
</body>
</html>