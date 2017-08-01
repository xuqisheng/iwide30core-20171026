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
<script>
function printme(){
	var old =document.body.innerHTML;
	for(var i=0;i<document.getElementsByClassName('form-group').length;i++){
		document.getElementsByClassName('form-group')[i].style.marginBottom='0px';
	}
	document.body.innerHTML=document.getElementsByClassName('box-body')[0].innerHTML;
	window.print();
	document.body.innerHTML=old;
}
</script>
<div class="tab-content">
<div class="tab-pane active" id="tab1">
<?php echo form_open( site_url('hotel/orders/edit_post'), array('id'=>'code_form','class'=>'form-horizontal','enctype'=>'multipart/form-data' ) ); ?>
    <div class="box-body">
      <div class=" col-xs-6">
        <div class="form-group has-feedback">
            <label class="col-sm-2 control-label">店铺名称</label>
            <div class="col-sm-8">
                <span  class="form-control " style='border:0;'><?php echo $shop['shop_name'];?></span>
            </div>
        </div>
          <div class="form-group has-feedback">
              <label class="col-sm-2 control-label">名称</label>
              <div class="col-sm-8">
                  <span  class="form-control " style='border:0;'>顾客消费小票</span>
              </div>
          </div>
        <div class="form-group  has-feedback">
            <label class="col-sm-2 control-label"><?php if($order['type']==1){echo '房号';}elseif($order['type']==2){echo '桌号';}elseif($order['type']==3){echo '外卖地址';}?></label>
            <div class="col-sm-8">
                <span  class="form-control " style='border:0;'><?php echo $order['address'];?></span>
            </div>
        </div>
         <div class="form-group  has-feedback">
            <label class="col-sm-2 control-label">订单状态</label>
            <div class="col-sm-8">
                <span  class="form-control " style='border:0;'><?php echo $orderModel->os_array[$order['order_status']];?></span>
            </div>
        </div>
          <div class="form-group  has-feedback">
              <label class="col-sm-2 control-label">订单编号</label>
              <div class="col-sm-8">
                  <span  class="form-control " style='border:0;'><?php echo $order['order_sn'];?></span>
              </div>
          </div>
        <div class="form-group  has-feedback">
            <label class="col-sm-2 control-label">消费内容</label>
            <div class="col-sm-8">
                <span  class="form-control " style='border:0;'> </span>
                <?php if(!empty($order['order_detail'])){
                        foreach($order['order_detail'] as $k=>$v){
                    ?>

                <span  class="form-control " style='border:0;'>商品名：<?php echo $v['goods_name'].'-'.$v['spec_name']?>  数量 <?php echo $v['goods_num']?> 单价<?php echo $v['goods_price'] ?></span>
                <?php }}?>
            </div>

        </div>
          <div class="form-group  has-feedback">
              <label class="col-sm-2 control-label">订单金额</label>
              <div class="col-sm-8">
                  <span  class="form-control " style='border:0;'><?php echo $order['sub_total'];?></span>
              </div>
          </div>


    	</div>
  	</div>
        <div class="box-footer ">
            <div class="col-sm-4 col-sm-offset-4">
                <button type="button" class="btn btn-info" onclick="printme()">打印</button>
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
