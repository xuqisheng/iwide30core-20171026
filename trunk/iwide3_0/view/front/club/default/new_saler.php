<link href="<?php echo base_url('public/distribute/default/styles/incom.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/distribute/default/styles/fill_in.css')?>" rel="stylesheet">
<title>填写信息</title>
</head>
<style>
<!--

.pull{ position:fixed; top:0; left:0; width:100%; height:100%;-webkit-overflow-scrolling:touch; overflow:scroll; background:#fff; color:#555; display:none;}
.pull div{background:#e4e4e4; padding:2%; text-align:center;}
.pull input{background:#fff; border-radius:0.2rem; border:1px solid #d3d3d3; text-align:center; padding:2%; width:90%;}
.pull dt{border-bottom: 1px solid #e4e4e4; padding:3%;}
.selsct span{display:inline-block}
.selsct span:last-child{width:70%; padding-right:3%; }
-->
</style>
<body>
<div class="nav">
	<div class="state">
    	<div class="lis_sta">
        	<div class="radi bg">1</div>
        	<p class="col">填写信息</p>
        </div>
    	<div class="lis_sta">
        	<div>2</div><!-- 广骏a467353624 -->
   			<p><?php if($inter_id == 'a467353624'):?>酒店审核<?php else: ?>后台审核<?php endif;?></p>
        </div>
    	<div class="lis_sta">
        	<div>3</div>
        	<p>审核结果</p>
        </div>
        <div class="yello"></div>
        <div class="c_99"></div>
    </div>
</div>
<div class="titl">填写帮主信息</div>
<div class="box">
	<div class="selsct">
    	<span class="front">姓&nbsp;&nbsp;名</span>
        <span><input type="text" class="use" placeholder="请输入姓名" name="name" id="name" value="<?php if (isset($saler['name'])):echo $saler['name'];endif;?>" /></span>
    </div>
<!--	<div class="selsct">
    	<span class="front"><?php /*if($inter_id == 'a467353624'):*/?>资格证号<?php /*else: */?>身份证号<?php /*endif;*/?></span>
        <span><input type="text" class="identit" placeholder="<?php /*if($inter_id == 'a467353624'):*/?>资格证号<?php /*else: */?>请输入身份证号<?php /*endif;*/?>" name="idnum" id="idnum" value="<?php /*if (isset($saler['id_card'])):echo $saler['id_card'];endif;*/?>" /></span>
    </div>-->
	<div class="selsct">
    	<span class="front">手机号码</span>
        <span><input type="text" class="phone" placeholder="请输入手机号码" name="cellphone" id="cellphone" value="<?php if (isset($saler['cellphone'])):echo $saler['cellphone'];endif;?>" /></span>
    </div>


    <!--div class="pull">
        <div><input type="text" placeholder="搜索酒店名" id="selecthotel" value=''/></div>
        <?php foreach ($hotels as $hotel):?>
        <?php if($hotel['inter_id'] != 'a449675133' || (strpos($hotel['name'],'集团') !== false || $hotel['status'] == 1)):?>
        <dt tid="<?php echo $hotel['hotel_id']?>"><?php echo $hotel['name']?></dt>
        <?php endif;?>
        <?php endforeach;?>
    </div>
    <input type="hidden" name="hotel" id="hotel" value='' />
	< <div class="selsct">
    	<span class="front">短信验证</span>
        <span><input type="text" placeholder="请输入验证码" name="rcode" id="rcode" /></span>
        <span class="floa">发送验证码</span>
    </div> -->
</div>
<!-- <div class="treaty"><span class="i_no">我同意</span><a href="">《分销协议》</a></div> -->
<div class="floot">
	<a href="javascript:;" onclick="submit()"><p style="padding:3.5% 0">提交</p></a>
</div>
<?php if($inter_id == 'a467353624'):?>
<div style="padding:3%;line-height:1.5; color:#888;">
	<p>在平等、自愿、公平、诚实、信用的基础上，根据相关法律法规的规定，就本人与金房卡就电子渠道注册及服务的有关事宜，现声明如下：</p>
	<p>1、本人同意注册二维码自愿并参与中秋节微信营销活动。</p>
	<p>2、本人已明确需要客户扫描本人二维码并完成电子支付购买产品后才能获得相应的提成奖励。</p>
	<p>3、本人保证不会强制客户购买产品。</p>
	<p>4、本人确认已清楚了解本次营销活动的所有内容，愿意遵守活动规则。</p>
</div>
<?php endif;?>
<div>

</div>
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
<div class="tmp" style=" padding-bottom:4%" id="scroll" >
	<div class="tmptitle">你看！帮主领铂金啦！</div>
    <div id="scrollbox">
        <ul>
            <?php
                if(!empty($p_mem)){
                    foreach($p_mem as $arr){  ?>
            <li>恭喜！<span><?php echo $arr['club_name']?></span>****的帮主已获取铂金会员</li>
       <?php           }
                }
            ?>
        </ul>
    </div>
</div>
</body>
</html>
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
})
var testval={
	name:/^[\u4E00-\u9FA5]{2,4}/g,
	identity:/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/,
	_phon:/^[1]\d{10}$/
}
function submit(){
	var sub = true;
	var name      = $('#name').val();
//	var idnum     = $('#idnum').val();
	var cellphone = $('#cellphone').val();
//	var rcode     = $('#rcode').val();

<?php if($inter_id == 'a467353624'):?>
	if(name == undefined  || name == ''){
		alert('请输入姓名');return false;
	}
/*	if(idnum == undefined || idnum==''){
		alert('请输入正确的资格证');return false;
	}*/
	if(cellphone == undefined ||!testval._phon.test(cellphone)){
		alert('请输入正确手机号码');return false;
	}
	if(department == undefined || department == ''){
		alert('请输入车队');return false;
	}
	if($('#hotel').val() == ''){
		alert('请选择分公司');return false;
	}
<?php else:?>
	if(name == undefined  || name == ''){
		alert('请输入姓名');return false;
	}
//	if(idnum == undefined  || !testval.identity.test(idnum)){
//		alert('请输入正确身份证');return false;
//	}
	if(cellphone == undefined ||!testval._phon.test(cellphone)){
		alert('请输入正确手机号码');return false;
	}

<?php endif;?>
	if(sub){
		sub = false;
		$.post("<?php echo site_url('club/Club/do_reg')?>?id=<?php echo $inter_id?>",
            {<?php if(isset($saler['id'])):?>'id':<?php echo $saler['id'];?>,<?php endif;?>
                "name":name,
                "cellphone":cellphone,
                "status":<?php echo $status;?>,
                "is_club":<?php echo $is_club;?>,
                "hidden":<?php echo $hidden;?>,
                "source":'<?php echo $source;?>',
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},function(datas){
			if(datas.code==1)){

                alert('信息提交成功');

                <?php if(isset($source) && $source=='friendship'){  ?>

				    location.href = "<?php echo site_url('club/Club/index?id=').$inter_id; ?>";

                <?php }else{ ?>

                    window.location.reload();

                <?php }?>

            }else{
				alert('信息提交失败');
				sub=true;
			}
		},'json');
	}else{
		alert('正在提交数据');
	}
}
$('.s_h').click(function(){
	toshow($('.pull'));
	$('.pull dt').stop().show();
})
$('.pull').scroll(function(e){	e.preventDefault(); });
$('.pull dt').click(function(){
	$('#hotel').val($(this).attr('tid'));
	$('#selecthotel').val('');
	$('.s').html($(this).html()).removeClass('error').addClass('all_right');
	$('.pull').stop().hide();
})
$('#selecthotel').bind('input propertychange', function() {
	var val=$(this).val();
	if( val ==''){$('.pull dt').stop().show();}
	else{
		for( var i=0; i<$('.pull dt').length; i++){
			if ( $('.pull dt').eq(i).html().indexOf(val) >= 0)
				$('.pull dt').eq(i).stop().show();
			else
				$('.pull dt').eq(i).stop().hide();
		}
	}
});
$(document).on('blur','input[name]',function(){
	var _v =$(this).val();
	if(_v==''|| ($(this).hasClass('identit')&&!testval.identity.test(_v))||($(this).hasClass('phone')&&!testval._phon.test(_v))){
		$(this).parent().removeClass('all_right').addClass('error');
	}
	else{
		$(this).parent().removeClass('error').addClass('all_right');
	}
	//$('.pull').stop().hide();
})
</script>