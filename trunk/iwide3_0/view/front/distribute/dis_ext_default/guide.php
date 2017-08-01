<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>微宝客介绍</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
<style>
*{margin:0px;padding:0px;font-family:"PingFang SC","微软雅黑","Microsoft YaHei",Helvetica,"Helvetica Neue",Tahoma,Arial,sans-serif;font-size:8px;}
a{text-decoration:none;}
.p_5{padding:0 5%;}
.m_5{margin:0 5%;}
.item_title{padding:3rem 0 20px 0;}
.titles_txt{text-align:center;font-size:1.75rem;color:#fff;background:#b69b69;width:14.875rem;margin:0 5px;border-radius:45px;padding:0.5rem 0;}
.title_con{font-size:1.75rem;color:#333;padding:0 0.35rem;}
.border_bottom{border-bottom:1px solid #dacdb4;flex:1;}
.display_flex{display:box;display:flex;align-items:center;}
.container_list img{display:block;width:100%;}
.bg_fafafa{background:#fafafa;}
.rule_list{margin-top:1.25rem;color:#555;}
.rule_list p{padding-left:1em;text-indent:-1em;font-size:1.75rem;}
.di::before{content:"•";color:#c5af87;font-size:1.75rem;margin:0 5px;}
.fool_btn{display:block;position:fixed;bottom:0px;left:0px;width:100%;color:#fff;background:#ff9900;text-align:center;;padding:2rem 0;font-size:1.875rem;font-weight:500;}
@media screen and (min-width:375px){
    html{
        font-size: 9.375px;
    }}
@media screen and (min-width:414px){
    html{
        font-size:10.35px;
    }
   }
</style>
</head>
<body>
<div class="p_5">
	<div class="item_title display_flex">
		<div class="border_bottom"></div>
		<div class="titles_txt">什么是微客宝?</div>
		<div class="border_bottom"></div>
	</div>
	<div class="title_con">微宝客是金房卡推出的分享返利产品。你可将公众号内的商品通过微信转发好友的形式分享给
你的朋友，你的朋友看到并购买后，公众号给予你一定金额的返利。</div>
</div>
<div class="container_list">
	<div class="item_title display_flex m_5" style="padding-bottom:0px;">
		<div class="border_bottom"></div>
		<div class="titles_txt">如何玩转微宝客?</div>
		<div class="border_bottom"></div>
	</div>
	<div class="p_5"><img src="<?php echo base_url('public/okpay/default/images/03.jpg')?>"></div>
	<div class="p_5 bg_fafafa"><img src="<?php echo base_url('public/okpay/default/images/05.jpg')?>"></div>
	<div class="p_5"><img src="<?php echo base_url('public/okpay/default/images/07.jpg')?>"></div>
	<div class="p_5 bg_fafafa"><img src="<?php echo base_url('public/okpay/default/images/10.jpg')?>"></div>
</div>
<div class="container_list">
	<div class="item_title display_flex m_5">
		<div class="border_bottom"></div>
		<div class="titles_txt">如何查看返佣?</div>
		<div class="border_bottom"></div>
	</div>
	<div class="p_5"><img src="<?php echo base_url('public/okpay/default/images/13.jpg')?>"></div>
</div>
<div class="p_5">
	<div class="item_title display_flex">
		<div class="border_bottom"></div>
		<div class="titles_txt">返利结算规则</div>
		<div class="border_bottom"></div>
	</div>
	<div class="title_con">根据根据用户在公众号商城交易产生的订单净额 按比例进行返利计算</div>
	<div class="rule_list">
		<P class="di">不同商品有不同的返利比例；</P>
		<P class="di">如果发生整单退货，则不进行返利结算；如果发生部分退货，结算余下的订单净额部分；</P>
		<P class="di">订单净额(仅指用户实际支付的订单款项)=商品销抵用券金额 - 退货金额 - 运费 - 满立减额 - 积 分等用户未支付款项的订单金额；</P>
	</div>
</div>
<div class="p_5" style="margin-bottom:15.6rem;">
	<div class="item_title display_flex">
		<div class="border_bottom"></div>
		<div class="titles_txt">返利支付周期</div>
		<div class="border_bottom"></div>
	</div>
	<div class="title_con">根据根据用户在公众号商城交易产生的订单净额 按比例进行返利计算；</div>
	<div class="rule_list">
		<P class="di">实际返利一天结算一次，例如：1.1日确认已有效 的返利订单，将在1.2日进行支付；</P>
		<P class="di">注：有效返利订单指的是好友购买后已确认收货 或卡券已消费；</P>
	</div>
</div>
<a class="fool_btn" href="<?php echo prep_url($acc_info['domain']).'/index.php/distribute/dis_ext/act_confirm?id='.$rinter_id.'&t='.base64_encode($rtn_url.'***'.$openid.'***'.$inter_id)?>">立即激活</a>
</body>
</html>
