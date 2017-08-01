<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
</head>

<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/new.css">
<!-- 新版本后台 v.2.0.0 -->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/version2-0-0.css'>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php 
/* 顶部导航 */
echo $block_top;
?>

<?php 
/* 左栏菜单 */
echo $block_left;
?>


<?php echo $this->session->show_put_msg(); ?>
<style>
    .logo_wrap{display: none!important;}
</style>
<div class="content-wrapper">
<header class="headtitle"><?php echo $breadcrumb_html; ?></header>
<section class="content"  style="width:800px;font-size:14px;margin-left:20px;">
<?php echo form_open( site_url('okpay/orders/edid'), array('id'=>'code_form','class'=>'form-horizontal','enctype'=>'multipart/form-data' ), array('id'=>$model->m_get('id') )); ?>
	<?php /*打印订单*/ ?>
<div id="print_content">
    <style>
    #print_content{position: relative;}
	.print{overflow:hidden;}
    .print span{ min-width:5em; display:inline-block; font-family:arial;}
    .print>div{padding:18px;}
	.bdtop{ border-top:3px solid #f1f1f1;}
	.print>div>div{padding-left:5px}
	.left_table,.right_table{float:left;width:45%; padding-right:1%}
	.left_table>*,.right_table>*{padding-bottom:10px}
	.detail_list .item{padding:10px; margin-top:10px; margin-right: 0.1%; display:inline-block; width:32.5%;}
    .detail_list .item::nth-child(3n){margin-right:0%;}
	.detail_list .item>div{padding-bottom:8px; }
	.detail_list .item>div:first-child{border-bottom:1px dotted #e4e4e4; margin-bottom:8px;}
	.detail_list .item>div:last-child{ padding-bottom:0}
    .clearfix:after{content:" ";display:block;clear:both;height:0;}
    input{height:30px;line-height:30px;border:1px solid #d7e0f1;text-indent:3px;margin-right:5px;}
    .introduces{border:1px solid #d9dfe9;width:220px;padding:3px;resize: vertical;}
    .logo_wrap{position: absolute;bottom: 0px;left: 0px;width: 100%;text-align: center;}
    </style>
	<div class="print bg_fff">
        <div class="t_title" style="text-align:center;font-size:20px;"></div>
        <div style="overflow:hidden;" class="bdtop"  style="font-size:14px;">
            <div class="left_table">
                <div>
                    <span>消费金额</span>
                    <span><?php echo $model->m_get('money');?></span>
                </div>
                <div>
                    <span>实付金额</span>
                    <span><?php echo $model->m_get('pay_money');?></span>
                </div>
                <div>
                    <span>不优惠金额</span>
                    <span><?php echo $model->m_get('no_sale_money');?></span>
                </div>
                <div>
                    <span>折扣</span>
                    <span><?php echo $model->m_get('discount_money');?></span>
                </div>
                <div>
                    <span>交易场景</span>
                    <span><?php echo $model->m_get('pay_type_desc');?></span>
                </div>
                <div>
                    <span>订单号</span>
                    <span><?php echo $model->m_get('out_trade_no');?></span>
                </div>
                <div>
                    <span>酒店</span>
                    <span><?php echo $model->m_get('hotel_name');?></span>
                </div>
                <div>
                    <span>支付状态</span>
                    <span><?php echo $model->m_get('pay_status')==3?'已支付':($model->m_get('money')==4?'已退款':'未付款')?></span>
                </div>
                <div>
                    <span>备注</span>
                    <span><?php echo $model->m_get('remark')?></span>
                </div>
                <div>
                    <span>退款金额</span>
                    <span><?php echo $model->m_get('refund_money')?></span>
                </div>

            </div>
      	</div>

        <div class="logo_wrap"><img src="<?php echo base_url(FD_PUBLIC) ?>/img/logo.png" alt=""></div>

    </div>
</div>
    <div class="bg_fff bd center pad10 martop">
<!--        <button class="bg_main button spaced" type="submit" style="margin-right:200px;" >保存</button>
-->        <button class="bg_main button spaced" type="button" onclick="printme()">打印预览</button>
    </div>
    <?php echo form_close() ?>
    <!-- /.box-body -->

    </section>
</div><!-- /.content-wrapper -->

</div>
<?php 
/* Footer Block @see footer.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php';
?>
<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php';
?>
</div><!-- ./wrapper -->
<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php';
?>
<!--
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>
-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
$(function () {
	//CKEDITOR.replace('el_gs_detail');
	$(".wysihtml5").wysihtml5();
});
function printme(){
    var size = $('.print').css('font-size');
	var old =document.body.innerHTML;;
    $('html,body').css({"min-width":"100%"});
    $('input,.introduces').css({"border":"none"});
	document.body.innerHTML=document.getElementById('print_content').innerHTML;
	window.print();
    $('html,body').css({"min-width":""});
	document.body.innerHTML=old;
    window.location.reload();
}
</script>
</body>
</html>
