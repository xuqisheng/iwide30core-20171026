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
<meta name="viewport" content="width=320,user-scalable=0">
<title>我的帮派</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/global.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/group.css');?>">
<script src="<?php echo base_url('public/club/scripts/jquery.js');?>"></script>
<script src="<?php echo base_url('public/club/scripts/ui_control.js');?>"></script>
<script src="<?php echo base_url('public/club/scripts/alert.js');?>"></script>
</head>
<body>

<?php if(!empty($list)){ ?>
<style>
.ui_none{display:none}
</style>
<div class="tabmenus webkitbox center bg_fff">
	<div class="bg_E4E4E4 bg_main" type='0'>全部</div>
	<div class="bg_E4E4E4" type='1'>审核中</div>
	<div class="bg_E4E4E4" type='1'>已过期</div>
</div>
<div class="group_list" style="padding-top: 36px;">
    <?php foreach($list as $info){ ?>
    <div item class="bg_fff">
    	<div class="webkitbox justify bd_bottom">
            <div><em class="bg_main iconfont">&#xE600;</em><?php echo $info['club_name'];?></div>
            <div class="h18">
                <p class="total_num">上限人数:<?php echo $info['limited_amount'];?>人</p>
                <p status><?php
                    if($info['status']==1 && $info['valid']==1){ echo '已过期'; }
                    elseif($info['status']==1 && $info['valid']==5){ echo '未到期'; }
                    elseif($info['status']==1 && $info['valid']==6){ echo '价格代码失效'; }
                    elseif($info['status']==1 && $info['amount']>=$info['limited_amount']){ echo '已满'; }
                    elseif($info['status']==1 && $info['amount']<$info['limited_amount']){echo '已加入'.$info['amount'].'人';}
                    elseif($info['status']==0){ echo '审核中';}
                    elseif($info['status']==2){ echo '审核不通过';}
                    elseif($info['status']==3){ echo '社群客失效'; }
                    ?></p>
            </div>
    	</div>
        <div class="martop">
            <div class="webkitbox justify">
                <span class="showhotel">
                        <input type='hidden'  value='<?php echo $info['hotel_id'];?>'/>
                    <em class="color_main iconfont">&#xE607;</em><?php if($info['type']=='all'){ echo '全部酒店适用'; }elseif($info['type']=='part'){ echo '部分酒店适用'; }else{echo $hotels[$info['hotel_id']]; } ?></span>
                <span><em class="color_main iconfont">&#xE60d;</em><?php foreach($info['arr_price_codes'] as $arr_price_code){ ?><p><?php echo $price_code[$arr_price_code]['price_name'];?></p><?php }?></span>
            </div>
            <p class="h22 color_888 martop" style="padding-left:25px">有效时间：<?php echo $info['valid_time'];?></p>
            <p class="h22 color_888" style="padding-left:25px">生成时间：<?php echo $info['createtime'];?></p>

        </div>
        <?php if($info['status']==1 && ($info['valid']!=1 || $info['valid']!=5 || $info['valid']!=6)){ ?>
        <div class="list_foot color_main bd_top">
<!--            --><?php //if($info['mulity']==1){ ?>
            <div class="btn_main xs h24 showmember"><input type='hidden'  value='<?php echo $info['club_id'];?>'/>本帮成员</div>
<!--            --><?php //}?>
            <a class="btn_main xs h24" href="show_qrcode?cid=<?php echo $info['club_id']?>&id=<?php echo $inter_id;?>">分享帮主令</a>
        </div>
       <?php }?>
    </div>
    <?php }?>
</div>
<?php }?>

<div class="ui_pull detail_pull" id="detail_hotel" style="display:none" onClick="toclose()">
	<div class="detail_list bg_fff">
        <div class="center bg_main pad3 h30"><em class="iconfont">&#xE607;</em>酒店明细</div>
        <ul class="scroll" id="hotel_list"></ul>
        <div class="bg_555 color_main center pad10" id="hotelnum">共0家</div>
    </div>
</div>
<div class="ui_pull detail_pull" id="detail_member" style="display:none" onClick="toclose()">
	<div class="detail_list bg_fff">
        <div class="center bg_main pad3 h30"><em class="iconfont">&#xE607;</em>成员明细</div>
        <ul class="scroll" id="member_list"></ul>
        <div class="bg_555 color_main center pad10" id="membernum">共0人</div>
    </div>
</div>
<div class="ui_none"><div>你还没有添加~<a href="add_club" class="color_link">点此添加</a></div></div>

<style>
.tmp *{font-size:0.55rem;}
.tmp{text-align:center; width:93%; background:#fff; border-radius:8px; overflow:hidden; margin:4% auto; line-height:1.5; box-sizing:border-box}
.tmptitle{padding:3%; background:#ff9900; color:#fff;}
.tmptitle:before{content:'●'; float:left}
.tmptitle:after{content:'●'; float:right}
tt{ color:#ff9900}
#scroll span{width:1em; display:inline-block; white-space:nowrap; overflow:hidden}
#scrollbox{position:relative;overflow:hidden; display:none;margin-top:4%; min-height:50px;}
#scrollbox *{color:#888}
#scroll ul{overflow:hidden; position:absolute; z-index:2; top:0;width:100%}

</style>
<div style="padding:4% ;" class="tmp">
	<p><tt><?php echo $count_p_mem;?></tt>人已经成功领取永久铂金会员</p>
	<p><tt><?php echo $count_all_list;?></tt>个社群帮已经在江湖中成立 </p>
	<p><tt><?php echo $count_all;?></tt>人成功领取金卡折扣</p>
</div>
		<?php if(!empty($p_mem)){ ?>
<div class="tmp" style=" padding-bottom:4%" id="scroll" >
	<div class="tmptitle">你看！帮主领铂金啦！</div>
    <div id="scrollbox">
        <ul>
		<?php foreach($p_mem as $arr){  ?>
            <li>恭喜！<span><?php echo $arr['club_name']?></span>****的帮主已获取铂金会员</li>
        <?php  } ?>
        </ul>
    </div>
</div>
<?php } ?>
<script>
$(function(){

if($('#scroll').find('li').length){
	$('#scrollbox').show();
	var x = $('#scroll').find('li').length;
	if( x>=5) x=5;
	var _h =x*$('#scroll li').eq(0).height();
	$('#scrollbox').height(_h);
	console.log($('#scroll ul').scrollTop())
	if( $('#scroll').find('li').length>=6){
		var n = 1;
		var s = window.setInterval(function(){
			$('#scroll ul').animate({top:-_h*n});
			if( n >$('#scroll li').length/5-1 ) n=0;
			else n++;
		},4000);
	}
}
$('.tabmenus >*').click(function(){
	var _this = $(this);
	var length = 0;
	$(this).addClass('bg_main').siblings().removeClass('bg_main');
	if($('[item]').length>0){
		if($(this).attr('type')==0){
			$('[item]').show();
			$('.ui_none').hide();
		}else{
			$('[item]').each(function(index, element) {
				if($('[status]',this).html()==_this.html()){
					$(this).show();
					length++;
				}
				else $(this).hide();
			});
			if(length>0)$('.ui_none').hide();
			else{ $('.ui_none').show().find('div').html('什么都没有'+_this.html());}
		}
	}
})


$('.showmember').click(function(){
    var ids=$(this).find('input').val();
   // console.log(ids);
    var postUrl = "<?php echo site_url('club/Club/club_customer');?>";
    pageloading('请稍候');
    $.post(postUrl,{club_id:ids,'<?php echo $csrf_token; ?>':'<?php echo $csrf_value; ?>'},function(data){
        console.log(data);
        var html='';
        if(data.code==1){
            for(var i=0;i<data.info.length;i++)
                html=html+'<li>'+data.info[i].name+'</li>';
            $('#membernum').html('共'+data.info.length+'人');
        }else if(data.code==0){
            html='<li>加载失败</li>';
        }
        $('#member_list').html(html);
        toshow($('#detail_member'));
        removeload();
    },'json');
})


$('.showhotel').click(function(){
	var ids=$(this).find('input').val();
   // console.log(ids);
	var postUrl = "<?php echo site_url('club/Club/show_hotels');?>";
	pageloading('请稍候');
	$.post(postUrl,{id:ids,'<?php echo $csrf_token; ?>':'<?php echo $csrf_value; ?>'},function(data){
        console.log(data);
		var html='';
		if(data.code==1){
			for(var i=0;i<data.info.length;i++)
				html=html+'<li>'+data.info[i].name+'</li>';
			$('#hotelnum').html('共'+data.info.length+'家');
		}else if(data.code==0){
			html='<li>加载失败</li>';
		}
		$('#hotel_list').html(html);
		toshow($('#detail_hotel'));
		removeload();
	},'json');
});


})
</script>
</body>
</html>
