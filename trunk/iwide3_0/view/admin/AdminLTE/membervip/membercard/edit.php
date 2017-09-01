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
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo isset($breadcrumb_array['action'])? $breadcrumb_array['action']: ''; ?>
            <small></small>
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
<?php echo $this->session->show_put_msg(); ?>

<!-- Horizontal Form -->
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">优惠券列表</h3>
	</div>
		<div class="box-body">
            <table class="table table-bordered table-striped table-condensed dataTable no-footer">
            <thead>
            	<tr>
            		<th>卡券ID</th>
            		<th>卡券名称</th>
                    <th>卡券说明</th>
            		<th>卡券类型</th>
                    <th>卡券库存</th>
                    <th>创建时间</th>
                    <th>激活状态</th>
                    <th>操作</th>
            	</tr>
            </thead>
            <tbody>
            <?php foreach ($cardlist as $key => $value) {?>
                <tr>
                    <td><?php echo $value['card_id'] ?></td>
                    <td><?php echo $value['title'] ?></td>
                    <td><?php echo $value['card_note'] ?></td>
                    <td>
                        <?php if ($value['card_type']==1) { ?>
                            抵用券
                        <?php }elseif ($value['card_type']==2) { ?>
                            折扣券
                        <?php }elseif ($value['card_type']==3) { ?>
                            兑换券
                        <?php }elseif ($value['card_type']==4) { ?>
                            储值卡
                        <?php }else{ ?>
                            NO card
                        <?php } ?>
                    </td>
                    <td><?php echo $value['card_stock'] ?></td>
                    <td><?php echo $value['last_update_time'] ?></td>
                    <td>
                        <?php if($value['is_active']=='t'){ ?>
                            <span style="color:#18BF0E;" ><strong>已激活</strong></span>
                        <?php }else{ ?>
                            <span style="color:red;" ><strong>未激活</strong></span>
                        <?php }?>
                    </td>
                    <td>
                        <a href="<?php echo EA_const_url::inst()->get_url('*/*/add').'?card_id='.$value['card_id']; ?>">查看详情</a>&nbsp;&nbsp;
                        <a href="<?php echo EA_const_url::inst()->get_url('*/*/card_user_info').'?card_id='.$value['card_id']; ?>">领取详情</a>
                    </td>
                </tr>
            <?php }?>
            </tbody>
            </table>
            <div class="row">
                <div class="col-sm-7">
                    <div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate">
                        <ul class="pagination"><?php echo $pagination?></ul>
                    </div>
                </div>
            </div>
		</div>
		<!-- /.box-footer -->
</div>
<!-- /.box -->
<!-- Horizontal Form -->

<!-- <div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">微信菜单配置</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-inline')); ?>
		<div class="box-body">
            
		</div>
		<div class="box-footer ">
            <button type="submit" class="btn btn-primary">保存</button>
		</div>
		<!-- /.box-footer -->
	<?php echo form_close() ?>
</div> -->
<!-- Horizontal Form -->

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
</body>
</html>
