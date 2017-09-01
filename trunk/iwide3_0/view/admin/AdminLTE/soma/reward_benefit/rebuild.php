<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->

<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datepicker/datepicker3.css'>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datepicker/bootstrap-datepicker.js'></script>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datepicker/locales/bootstrap-datepicker.zh-CN.js'></script>

<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.css'>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.js'></script>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/bootstrap-select/i18n/defaults-zh_CN.min.js'></script>

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

    <div class="tabbable " id="top_tabs"> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-search"></i> 订单搜索 </a></li>
        </ul>

<!-- form start -->
	<?php 
	//if( count($orders)>0 ):
    //	echo form_open( Soma_const_url::inst()->get_url('*/*/rebuild_post'), array('class'=>'form-horizontal'), array(  ) ); 
	//else:
    	echo form_open( Soma_const_url::inst()->get_url('*/*/*'), array('class'=>'form-horizontal'), array(  ) ); 
	//endif;
	?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
				<div class="box-body">
    				<div class='form-group '>
                    	<label for='el_inter_id' class='col-sm-2 control-label'>筛选条件</label>
                    	<div class='col-sm-4'>
                    		<select class='form-control selectpicker show-tick' data-live-search='true' name='inter_id' id='el_inter_id' required >
                    		        <option value="" ></option>
                    		    <?php foreach ($publics as $k=>$v): 
                    		          if( isset($inter_id) && $inter_id==$k ) $selected= ' selected="selected" ';
                    		          else $selected= '';
                    		    ?>
                    		        <option value="<?php echo $k; ?>" <?php echo $selected; ?>><?php echo $v; ?></option>
                    		    <?php endforeach; ?>
                    		</select>
                    	</div>
                    	<div class='col-sm-4'>
                    		<select class='form-control selectpicker show-tick' data-live-search='true' name='settlement' id='el_settlement' >
                    		        <option value="" ></option>
                    		    <?php foreach ($settle_arr as $k=>$v): 
                    		          if( isset($settlement) && $settlement==$k ) $selected= ' selected="selected" ';
                    		          else $selected= '';
                    		    ?>
                    		        <option value="<?php echo $k; ?>" <?php echo $selected; ?>><?php echo $v; ?></option>
                    		    <?php endforeach; ?>
                    		</select>
                    	</div>
                    </div>
    				<div class="form-group  has-feedback">
                        <label for="el_update_time" class="col-sm-2 control-label">起止支付时间</label>
                        <div class="col-sm-4">
                            <div class=" input-group date">
                                <input type="text" class="form-control" name="start_time" size="16" id="el_start_time" value="<?php echo $start_time; ?>" required >
                    			<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                			</div>
                        </div> 
                        <div class="col-sm-4">
                            <div class=" input-group date">
                                <input type="text" class="form-control" name="end_time" size="16" id="el_end_time" value="<?php echo $end_time; ?>" required >
                    			<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                			</div>
                        </div>
                    </div>
				
				    <?php if( count($orders)>0 ): ?>
    				<div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <table id="data-grid" class="table table-bordered table-striped table-condensed">
                            <thead><tr>
                                <td>订单号</td><td>购买方式</td><td>总计</td><td>实付</td><td>绩效</td><td>下单时间</td><td>付款时间</td><td>分销员ID</td><td>已同步？</td><td>操作</td>
                            </tr></thead>
                            
                            <tbody>
				                <?php foreach($orders as $k=>$v): ?>
<tr><td><?php echo $v['order_id']; ?></td><td><?php echo $v['settlement']; ?></td><td>￥<?php echo $v['subtotal']; ?></td><td>￥<?php echo $v['grand_total']; ?></td><td><?php echo $v['reward']; ?></td>
<td><?php echo $v['create_time']; ?></td><td><?php echo $v['payment_time']; ?></td><td><?php echo $v['saler_id']; ?></td><td><?php echo ($v['send'])? '已同步': '-'; ?></td>
<td>
<?php if( !$v['send'] ): ?>
<a href="<?php echo Soma_const_url::inst()->get_url('*/*/rebuild_post', array('order_id'=> $v['order_id'], 'inter_id'=> $v['inter_id'], ) ); ?>" target="_blank" >[同步业绩]</a>
<?php endif; ?>
</td></tr>
                                <?php endforeach; ?>
                            </tbody>
                            
                            </table>
                        </div>
                    </div>
				    <?php endif; ?>
					<div class="box-footer ">
						<div class="col-sm-4 col-sm-offset-4">
				            <?php if( count($orders)>0 ): ?>
							<!-- <button type="submit" class="btn pull-right">全部推送业绩</button> -->
							<button type="submit" class="btn btn-info pull-left">刷新数据</button>
				            <?php else: ?>
							<button type="submit" class="btn btn-info pull-left">扫描此时间段订单</button>
				            <?php endif; ?>
						</div>
					</div>
				</div>
				<!-- /.box-body -->

            </div><!-- /#tab1-->
            
	<?php echo form_close() ?>
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
<script>
$("#el_start_time").datepicker({ format:"yyyy-mm-dd", language: "zh-CN",clearBtn: false,todayBtn: false,orientation: "auto left" });
$("#el_end_time").datepicker({ format:"yyyy-mm-dd", language: "zh-CN",clearBtn: false,todayBtn: false,orientation: "auto left" });
</script>
</body>
</html>
