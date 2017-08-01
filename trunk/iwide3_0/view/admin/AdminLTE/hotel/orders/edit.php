<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
</head>

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

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">

<?php echo $this->session->show_put_msg(); ?>
<!-- Horizontal Form -->
<div class="box box-info">

    <div class="tabbable "> <!-- Only required for left/right tabs -->

<!-- form start -->
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
			<?php echo form_open( site_url('hotel/orders/edit_post'), array('id'=>'code_form','class'=>'form-horizontal','enctype'=>'multipart/form-data' ), array('orderid'=>$list['orderid'],'hotel_id'=>$list['hotel_id']) ); ?>
                <div class="box-body">
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">酒店</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['hname'];?></span>
						</div>
					</div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">单号</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['orderid'];?></span>
						</div>
					</div>
					 <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">入住人</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['name'];?></span>
						</div>
					</div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">电话</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['tel'];?></span>
						</div>
					</div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">房型</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['order_room'];?></span>
						</div>
					</div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">价格代码</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['order_price_code'];?></span>
						</div>
					</div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">支付方式</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['pay_name'];?></span>
						</div>
					</div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">原价</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['ori_price'];?></span>
						</div>
					</div>
		    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">使用优惠券</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['coupon_favour'];?></span>
						</div>
					</div>
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">积分抵用</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['point_favour'];?></span>
						</div>
					</div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">总价</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['real_price'];?></span>
						</div>
					</div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">房间数</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['roomnums'];?>&nbsp;间</span>
						</div>
					</div>
					<?php foreach ($list['order_details'] as $k=>$od){?>
						<div class="form-group  has-feedback">
							<label class="col-sm-2 control-label" style="color:#7B7B7B"><?php echo $od['sid'];?></label>
							<div class="col-sm-8">
								<span  class="form-control " style='border:0;'>入住/离店日期：<?php echo date('Y-m-d',strtotime($od['startdate']));?>/<?php echo date('Y-m-d',strtotime($od['enddate']));?></span>
								<span  class="form-control " style='border:0;'>状态：<?php echo $od['status_des'];?></span>
								<table>
								<tr>
								<td style="float: left;"><?php echo '价格明细：';?></td>
								<td style="padding-bottom: 10px;">
									<?php foreach ($od['price_detail'] as $date=>$price){?>
									<?php echo $date."：".$price."元";?><br>
									<?php }?>
								</td>
								</tr>
							</table>
							</div>
						</div>
					<?php }?>
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">下单时间</label>
						<div class="col-sm-8">
							<span  class="form-control " style='border:0;'><?php echo $list['f_order_time'];?></span>
						</div>
					</div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">保留时间</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" style='width: 30%;' name="holdtime" id="holdtime" placeholder="保留时间" value="<?php echo $list['holdtime']?>">
							<a style='text-decoration: underline;' href='javascript:void(0);' onclick='$("#holdtime").val("<?php echo date('Y-m-d 18:00',strtotime($list['startdate']));?>");'>入住日期18点</a>
							<a style='text-decoration: underline;' href='javascript:void(0);' onclick='$("#holdtime").val("<?php echo date('Y-m-d 12:00',strtotime($list['enddate']));?>");'>离店日期12点</a>
						</div>
					</div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">备注</label>
						<div class="col-sm-8">
							<input type="text" class="form-control " name="remark" id="remark" placeholder="备注" value="<?php echo $list['remark']?>">
						</div>
					</div>
                    <div class="form-group  has-feedback">
                        <label class="col-sm-2 control-label">PMS订单号</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control " name="mt_pms_orderid" id="remark" placeholder="PMS订单号"  onKeyUp="value=value.replace(/[^\w\.\/]/ig,'')" value="<?php echo $list['mt_pms_orderid']?>">
                        </div>
                    </div>
                    <div class="form-group  has-feedback">
                        <label class="col-sm-2 control-label">发票信息</label>
                        <div class="col-sm-8">
                            <?php if(isset($list['invoice_detail'])){?>
                            <span  class="form-control " style='border:0;'>类型：<?php echo $list['invoice_detail']['typename'];?></span>
                            <span  class="form-control " style='border:0;'>抬头：<?php echo $list['invoice_detail']['title'];?></span>
                            <?php if($list['invoice_detail']['type'] == 2){?>
	                            <span  class="form-control " style='border:0;'>纳税人识别号：<?php echo $list['invoice_detail']['code'];?></span>
	                            <span  class="form-control " style='border:0;'>注册地址：<?php echo $list['invoice_detail']['address'];?></span>
	                            <span  class="form-control " style='border:0;'>公司电话：<?php echo $list['invoice_detail']['phonecall'];?></span>
	                            <span  class="form-control " style='border:0;'>银行：<?php echo $list['invoice_detail']['bank'];?></span>
	                            <span  class="form-control " style='border:0;'>银行账号：<?php echo $list['invoice_detail']['account'];?></span>
                            <?php }?>
                            <?php }else{?>
                            	无
                            <?php }?>
                        </div>
                    </div>
					</div>
					</div>
                    <div class="box-footer ">
                        <div class="col-sm-4 col-sm-offset-4">
                            <button type="submit" class="btn btn-info pull-right">保存</button>
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
				<?php echo form_close() ?>
                <!-- /.box-body -->

            </div><!-- /#tab1-->
            
        </div><!-- /.tab-content -->

</div>
<!-- /.box -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

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
</script>
</body>
</html>
