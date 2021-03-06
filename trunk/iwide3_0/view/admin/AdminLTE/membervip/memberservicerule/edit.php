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
		<h3 class="box-title">规则列表</h3>
	</div>
		<div class="box-body">
            <table class="table table-bordered table-striped table-condensed dataTable no-footer">
            <thead>
            	<tr>
            		<th>规则ID</th>
                    <th>规则名称</th>
                    <th>领取渠道</th>
                    <th>领取次数</th>
            		<th>卡券/礼包名称</th>
                    <th>卡券/礼包说明</th>
            		<th>卡券/礼包类型</th>
                    <th>卡券/礼包库存</th>
                    <th>创建时间</th>
                    <th>激活状态</th>
                    <th>操作</th>
            	</tr>
            </thead>
            <tbody>
            <?php foreach ($cardlist as $key => $value) {?>
                <tr>
                    <td><?php echo $value['card_rule_id'] ?></td>
                    <td><?php echo $value['rule_title'] ?></td>
                    <td>
                        <?php if( $value['active']=='reg' ){ ?>
                            注册领取
                        <?php }elseif( $value['active']=='perfect' ){ ?>
                            完善资料领取
                        <?php }elseif( $value['active']=='gaze' ){ ?>
                            关注送券(自主领取)
                        <?php }elseif( $value['active']=='gazeini' ){ ?>
                            关注送券(默认领取)
                        <?php }elseif( $value['active']=='all' ){ ?>
                            通用
                        <?php }else{ ?>
                            暂无
                        <?php } ?>
                    </td>
                    <td><?php echo $value['frequency'] ?></td>
                    <td>
                        <?php if($value['is_package'] == 'f'):?>
                            <?php echo $value['title'] ?>
                        <?php else:?>
                            <?php echo $value['name'] ?>
                        <?php endif;?>
                    </td>
                    <td>
                        <?php if($value['is_package'] == 'f'):?>
                            <?php echo $value['card_note'] ?>
                        <?php else:?>
                            <?php echo $value['remark'] ?>
                        <?php endif;?>
                    <td>
                        <?php if($value['is_package'] == 'f'):?>
                            <?php if ($value['card_type']==1) { ?>
                                抵用券
                            <?php }elseif ($value['card_type']==2) { ?>
                                折扣券
                            <?php }elseif ($value['card_type']==3) { ?>
                                兑换券
                            <?php }elseif ($value['card_type']==4) { ?>
                                储值卡
                            <?php }else{ ?>
                                --
                            <?php } ?>
                        <?php else:?>
                            无
                        <?php endif;?>
                    </td>
                    <td>
                        <?php if($value['is_package'] == 'f'):?>
                            <?php echo $value['card_stock'] ?>
                        <?php else:?>
                            /
                        <?php endif;?>
                    <td><?php echo $value['createtime'] ?></td>
                    <td>
                        <?php if($value['is_active']=='t'){ ?>
                            <span style="color:#18BF0E;" ><strong>已激活</strong></span>
                        <?php }else{ ?>
                            <span style="color:red;" ><strong>未激活</strong></span>
                        <?php }?>
                    </td>
                    <td>
                        <a href="<?php echo EA_const_url::inst()->get_url('*/*/add').'?card_rule_id='.$value['card_rule_id']; ?>">编辑</a>
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
