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
    <link href="<?php echo base_url("public/member/phase2/styles/global.css");?>" rel="stylesheet">
    <link href="<?php echo base_url("public/member/phase2/styles/mycss.css");?>" rel="stylesheet">
    <script src="<?php echo base_url("public/member/phase2/scripts/jquery.js");?>"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/ui_control.js");?>"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/alert.js");?>"></script>
    <title>充值</title>
</head>
<body>
<style>
body, html { background: #fff; }
.btn_void{ width:30%;margin: 1.5%; margin-bottom:15px;}
.btn_void.cur{background:#ff9900; color:inherit}
.btn_void.cur>*{ color:#fff}
</style>
<link href="<?php echo base_url("public/member/phase2/styles/green.css");?>" rel="stylesheet">
<div class="balance bg_l_g_fec50f_ffa70a color_fff">
    <p>账户<?php echo $this->_ci_cached_vars['filed_name']['balance_name'];?></p>
    <p><?php if(empty($total_deposit)) echo 0;else echo $total_deposit;?></p>
</div>
<div class="flex flexjustify pad15 flexwrap color_main" style="justify-content:flex-start;align-items: stretch">
    <?php foreach ($deposit_list as $key => $value) { ?>
	<div class="btn_void bdradius c_9b9b9b depositData" depositState='f' depositId= "<?php echo $value['deposit_card_id'] ?>" depositMoney = "<?php echo $value['money'] ?>">
<!--    	<div class="h34 color_000">--><?php //echo $value['money']; ?><!--</div>-->
        <div class="h34 color_000"><?php echo $value['title'] ?></div>
        <div class="h22 description showdes_<?php if(isset($value['deposit_card_id']) && !empty($value['deposit_card_id'])) echo $value['deposit_card_id']; ?>"><?php if(isset($value['description']) && !empty($value['description'])) echo $value['description'];?></div>
    </div>
	<?php } ?>
    <?php if(isset($inter_id) && $inter_id!='a464919542' && $inter_id !='a484191907'):?>
    <div class="btn_void bdradius c_9b9b9b diy_money" style="padding:15px 10px"><div class="h34 color_000">自定义</div></div>
    <?php endif;?>
</div>
<div class="list_style bd showdis_div" style="display:none">
    <div class="input_item">
        <div>分销号</div>
        <input type="number" class="input" name="distribution_num" placeholder="分销号请咨询酒店客服,或不填写" value="" />
    </div>
</div>
<div class="list_style bd diy_input" style="display:none">
    <div class="input_item">
        <div>充值金额</div>
        <input id="UserMoney"  type="text" name="money" placeholder="充值金额只能为10的整数倍" />
    </div>
</div>
<div class="pad15" style="margin-top:20%">
    <div class="f_btn bg_ff9900 bdradius martop" id="submitBtn">充值</div>
</div>
    <!--dialog end -->
    <script type="text/javascript">
        //通用JS
		var tmpval='';
        $(function(){
            var depositId,depositMoney;
            $('.depositData').click(function(){
                depositId = $(this).attr('depositId');
                depositMoney = $(this).attr('depositMoney');
                var DepositState = $(this).attr('depositState');
                $(this).addClass('cur').siblings().removeClass('cur');
                $(this).attr('depositState','t');
				$('.diy_input').hide();
				$('#UserMoney').val('');
                $("input[name='distribution_num']").prop('disabled',false);
                <?php if($inter_id == 'a472731996'){?>
                    $('.showdis_div').show();
                <?php }?>
                var description = $.trim($('.showdes_'+depositId).html());
                if(description){
                    $('.showdes_div').show();
                    $.MsgBox.Alert(description);
                }
                else{
                    $('.showdes_div').hide();
                   // $.MsgBox.Alert('暂无说明!');
                }
            });
			$('.diy_money').click(function(){
                $('.showdes_div').hide();
                $('.showdis_div').hide();
                $("input[name='distribution_num']").prop('disabled',true);
                $("input[name='distribution_num']").val('');
                depositId = 0;
				depositMoney = 0;
                $(this).addClass('cur').siblings().removeClass('cur');
				$('.diy_input').show();
				$('#UserMoney').val(tmpval);
				$('.depositData').each(function(index, element) {
                    $(this).attr('depositState','f');
                });
			});
			$('#UserMoney').blur(function(){
				tmpval=$('#UserMoney').val();
			});
            $('#submitBtn').click(function(){
				if( !$('.diy_input').is(':hidden')){
					if($('#UserMoney').val()==''){
						alert('请输入充值金额');
						return;
					}
					if(isNaN($('#UserMoney').val())||Number($('#UserMoney').val())%10!=0){
						alert($('#UserMoney').attr('placeholder'));
						return;
					}
				}
                var PostUrl = "<?php echo base_url('index.php/membervip/depositcard/save_deposit_order');?>";
                var Money = $('#UserMoney').val();
                var distribution_num = $.trim($("input[name='distribution_num']").val());
                var datas = {depositId:depositId,depositMoney:depositMoney,money:Money};
                if(distribution_num) datas.distribution_num = distribution_num;
                $.post(PostUrl, datas, function(result){
                    if(typeof(result.err)=='undefined'){
                        //微信支付
                        window.location.href="<?php echo base_url('index.php/wxpay/vip_pay?orderId=');?>"+result.data;
                    }else{
                        $.MsgBox.Alert(result.msg);
                    }
               }, "json");
            });
        });
    </script>
</body>
</html>
