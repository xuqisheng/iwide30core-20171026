
<link href="<?php echo base_url('public/mall/multi/style/ui.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mail.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mailstatus.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/choosebtn.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/bill.css')?>" rel="stylesheet">
<title>开具发票</title>
</head>
<body>
<div class="pullbill">
	<div class="whileblock ui_border choosebill">
    	<div class="choosebtn"><i></i></div>
        <span>是否需要开发票</span>
    </div>
    <div class="billtitle" style=" display:none">
    	<div style="padding:3%;">发票抬头</div>
        <input type="text" style="width:100%;" class="whileblock ui_border" placeholder="点击输入发票抬头信息">
        
    <!-- 未填  --  可编辑地址-->  
        <div style="padding:3%;">邮寄信息</div>
        <div class="ui_list bg_white">
            <a href="address.php" class="item billaddress">
                <div>收货人 18800001111</div>
                <div>收货地址：地址地址地址地址地址地址地址</div>
            </a>	
        </div>
    <!--  end  -->
    </div>
    
    
    
    <!-- 已填  --  显示发票详情 -->    
    <div style="padding:3%;">个人信息</div>
    <div class="ui_list bg_white">
        <div class="item">
            <div>收货人 18800001111</div>
            <div>收货地址：地址地址地址地址地址地址地址</div>
        </div>	
    </div>
    
    <div style="padding:3%;">运送信息</div>
    <div class="ui_list bg_white">
        <div class="item">
            <div>发货快递 顺丰速递</div>
            <div>运单编号 137283718378974</div>
        </div>	
    </div>
    <!-- end -->
    <div class="billnotic">温馨提示:<br>发票金额为现金支付金额（扣除抵用券、红包立减、返现金额等。）</div>
    <div class="footfixed">
    	<div class="fee">邮寄费：8元</div><!-- 已填 则不显示邮寄费--> 
    	<a class="surebtn bg_orange">确定</a>
    </div>
    <div style="padding-top:15%;font-size: 0;"></div>
</div>


</body>
<script>
var showbill =function(){
	if ( $('.choosebtn').hasClass('ischoose') ){
		$('.choosebtn').removeClass('ischoose');
		$('.billtitle').hide();
	}
	else{
		$('.choosebtn').addClass('ischoose');
		$('.billtitle').show();
	}
	
}
$(function(){
	showbill();
	$('.choosebill').click(function(){
		showbill();
	})
})
</script>
</html>
