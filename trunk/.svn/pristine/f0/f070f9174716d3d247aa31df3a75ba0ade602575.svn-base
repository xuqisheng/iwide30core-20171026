<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<style>
	.layer{position:fixed; top:0; left:0;overflow:hidden; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; display:none}
	.add_hotel_content{background:#f8f8f8; padding:15px; height:100%; float:right}
	.child_dom{ padding:2px 5px; margin:3px;background:#fff; border:1px solid #3c8dbc; display:inline-block;}
</style>
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
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        查看分销规则
        <small>Using Rules</small>
      </h1>
      <ol class="breadcrumb">
      </ol>
    </section>

<input type='hidden' name='<?php echo $csrf_token;?>' value='<?php echo $csrf_value;?>' />
<input type='hidden' name='rule_id' value='<?php echo $row['id'];?>' />
	<section class="content">
      <div class="row">
        <div class="col-xs-6">
          <div class="box box-solid">
            <div class="box-header with-border"><i class="fa fa-cube"></i><h3 class="box-title">规则名称</h3></div>
            <div class="box-body"><h5><?php echo $row['title'];?></h5></div>
          </div>
        </div>
        <div class="col-xs-6">
          <div class="box box-solid">
            <div class="box-header with-border"><i class="fa fa-database"></i><h3 class="box-title">分销规则</h3></div>
            <div class="box-body"><h5><?php echo $beyond[$row['beyond']];?>-
            <?php if($row['all']==1){?>
              <?php echo '全部价格代码';?>
            <?php }else{?>
            <?php foreach($row['price_typeid'] as $price_id){?>
            	<?php echo $price_code[$price_id]['price_name'].',';?>
            <?php }?>
            <?php }?>
            </h5></div>
          </div>
        </div>
        <div class="col-xs-6">
          <div class="box box-solid">
            <div class="box-header with-border"><i class="fa fa-credit-card"></i><h3 class="box-title">支付方式</h3></div>
            <div class="box-body"><h5>
            <?php foreach($pay_ways as $pay_way){?>
            	<?php if(in_array($pay_way->pay_type,$row['pay_wayid'])) echo $pay_way->pay_name.',';?>
            <?php }?></h5></div>
          </div>
        </div>
        <div class="col-xs-6">
          <div class="box box-solid">
            <div class="box-header with-border"><i class="fa fa-credit-card"></i><h3 class="box-title">激励类型</h3></div>
            <div class="box-body"><h5><?php echo $excitation_type[$row['excitation_type']];?></h5></div>
          </div>
        </div>
        <div class="col-xs-6">
          <div class="box box-solid">
            <div class="box-header with-border"><i class="fa fa-credit-card"></i><h3 class="box-title">比例设置</h3></div>
            <div class="box-body"><h5><?php echo $row['excitation_value']?><?php if($row['excitation_type']==1) echo '%'; else echo '元';?></h5></div>
          </div>
        </div>
        <div class="col-xs-6">
          <div class="box box-solid">
            <div class="box-header with-border"><i class="fa fa-credit-card"></i><h3 class="box-title">激励条件</h3></div>
            <div class="box-body">
              <h5><?php if($row['fullmoney']>0) echo '订单满'.$row['fullmoney'].'元';?></h5>
            </div>
          </div>
        </div>
        <div class="col-xs-12">
          <div class="box box-solid">
            <div class="box-header with-border">
              <i class="fa fa-pencil-square-o"></i>
              <h3 class="box-title">操作记录</h3>
            </div>
            <!-- /.box-header -->
            <?php if(!empty($admin_logs)){?>
            <div class="box-body">
               <ol type="1">
                <table>
                  <tr><th>序号</th><th>帐号</th><th style="width:40%">操作描述</th><th>IP地址</th><th>时间</th></tr>
                  <?php $k = 1;?>
                 	<?php foreach ($admin_logs as $l){?>
                    <tr style="border-bottom: 1px #000000 solid;">
                      <td><?php echo $k;?></td>
                      <td><?php echo $l['admin_name'];?></td>
                      <td><?php echo $l['desc'];?></td>
                      <td><?php echo $l['ip'];?></td>
                      <td><?php echo $l['record_time'];?></td>
                      <?php $k++;?>
                    <tr>
                  <?php }?>
                </table>
              </ol>
            </div>
            <?php }?>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      </section>
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
</body>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
$(function(){
});
</script>
</html>
