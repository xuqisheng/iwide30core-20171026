<?php include 'header.php'?>
<style>
.img{margin-right:10px;width:80px; max-width:80px; min-width:80px}
</style>
<header style="padding-top:37px">
	<div class="headfixed webkitbox center pad3 bg_fff bd_bottom">
    	<a href="<?php echo Hotel_base::inst()->get_url("MYORDER",array('type'=>$type,'wfk'=>1))?>" class="<?php if($wfk==1){?>color_main<?php }?>">待付款订单</a>
    	<a href="<?php echo Hotel_base::inst()->get_url("MYORDER",array('type'=>$type))?>" class="bd_left <?php if($wfk===null){?>color_main<?php }?>">所有订单</a>
    </div>
</header>
<script>
function cancel_order(order_id){
	if(confirm('您确定要取消吗？')){
		pageloading('请稍候');
		$.get('<?php echo Hotel_base::inst()->get_url("CANCEL_MAIN_ORDER")?>',{
			oid:order_id
		},function(data){
			removeload();
			if(data.s==1){
				$.MsgBox.Alert(data.errmsg,function(){
					location.reload();
				});
			}
			else{
				$.MsgBox.Alert(data.errmsg);
			}
			
		},'json')
	}
}
</script>
<?php if(!empty($orders)){foreach($orders as $o){?>
<div class="martop">
    <div class="webkitbox justify h24 pad3 bg_fff bd">
        <div>订单编号：<?php echo $o['first_detail']['orderid'];?></div>
        <div class="color_main"><?php echo $o['status_des'];?></div>
    </div>
    <a href="<?php echo Hotel_base::inst()->get_url("INDEX",array('oid'=>$o['id'],'type'=>$o['price_type']))?>" class="webkitbox h24 pad3">
    	<div class="img">
        	<div class="squareimg"><img src="<?php echo $o['himg'];?>"></div>
        </div>
        <div class="color_888">
            <div class="h36 color_000"><?php if(isset($o['first_detail']['roomname'])){echo $o['first_detail']['roomname'];}?></div>
            <div><?php echo $o['first_detail']['price_name'];?>  数量：<?php echo $o['roomnums'];?> 张</div>
            <div>时间：<?php echo date('Y.m.d',strtotime($o['startdate']));?></div>
            <div>地址： <?php echo $o['haddress'];?> </div>
        </div>
    </a>
    <div class="webkitbox center h28 bg_fff bd">
        <?php if($o['status']==0 || $o['status']==9){ ?><div class="pad10 color_888" onclick="cancel_order('<?php echo $o['first_detail']['orderid'];?>')">取消订单</div><?php }?>
        <?php if($o['status']==9){ ?>
	        <?php if($o['paytype'] == 'weifutong'){?>
	        	<a href="<?php echo site_url('wftpay/hotel_order').'?id='.$inter_id.'&orderid='.$o['first_detail']['orderid'];?>" class="bd_left color_main" style="padding:4px;">立即支付<div class="color_999 h20" timeout="<?php echo $o['timeout'];?>"></div></a>
	    	<?php }elseif($o['paytype']=='lakala'){?>
	    		<a href="<?php echo site_url('lakalapay/hotel_order').'?id='.$inter_id.'&orderid='.$o['first_detail']['orderid'];?>" class="bd_left color_main" style="padding:4px;">立即支付<div class="color_999 h20" timeout="<?php echo $o['timeout'];?>"></div></a>
	    	<?php }else{?>
	    		<a href="<?php echo site_url('wxpay/hotel_order').'?id='.$inter_id.'&orderid='.$o['first_detail']['orderid'];?>" class="bd_left color_main" style="padding:4px;">立即支付<div class="color_999 h20" timeout="<?php echo $o['timeout'];?>"></div></a>
	        <?php }?>
        <?php }?>
	<?php if($o['status']==1 || $o['status']==2 || $o['status']==4 || $o['status']==5 || $o['status']==11){ ?>
        <a href="<?php echo Hotel_base::inst()->get_url("INDEX",array('h'=>$o['hotel_id'],'type'=>$o['price_type']))?>" class="pad10 bd_left color_888">再来一单</a>
    <?php }?>
    </div>
</div>
<?php }}?>
</body>
<script>
$(function(){
	$('[timeout]').each(function() {
		if($(this).attr('timeout')=='')return;
		try{
			var $this = $(this);
			var time=parseInt( $this.attr('timeout')); //剩余秒数
			if(isNaN(time))return;
			var tmp=window.setInterval(function(){
				var theTime = parseInt(time--);// 秒
				if(time<=0){
					$this.html('支付超时');
					$this.parent().attr('href','javascript:void(0);');
					window.clearInterval(tmp);
					return;
				}
				var theTime1 = 0;// 分
				var theTime2 = 0;// 小时
				if(theTime > 60) {
					theTime1 = parseInt(theTime/60);
					theTime = parseInt(theTime%60);
					if(theTime1 > 60) {
						theTime2 = parseInt(theTime1/60);
						theTime1 = parseInt(theTime1%60);
					}
				}
				var result = parseInt(theTime);
				if(theTime1 > 0) {
				result = parseInt(theTime1)+":"+result;
				}
				if(theTime2 > 0) {
				result = parseInt(theTime2)+":"+result;
				}
				$this.html('支付倒计时 '+ result);
			},1000);
		}catch(e){
			$.MsgBox.Alert(e);
		}
    });
})
</script>
</html>
