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
<?php $pk= $model->table_primary_key(); ?>

<div class="box box-info">
        
    <div class="tabbable " id="top_tabs"> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-edit"></i> 输入兑换码 </a></li>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
<?php 
echo form_open( Soma_const_url::inst()->get_url('*/*/exchange_info'), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) ); ?>
        		<div class="box-body "><br/>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">兑换码</label>
                        <div class="col-sm-6 inline">
                            <input type="text" maxlength="12" style="font-size:25px;line-height:40px;height:40px;" class="form-control " name="exchange_code" id="el_exchange_code" value="">
                        </div>
                    </div>
        		</div>
        		<!-- /.box-body -->
        		<div class="box-footer ">
                    <div class="col-sm-4 ">
                        <!-- <button type="reset" class="btn btn-default">清除</button> -->
                        <button type="submit" class="btn btn-info pull-right">查找</button>
                    </div>
        		</div>
            		<!-- /.box-footer -->
<?php echo form_close() ?>
            </div><!-- /#tab1-->
            
            <div class="tab-pane hide" id="tab2">
    			<div class="box-body">
    				<!-- 购买清单 -->
    				<div class="col-sm-12 " >
                    <?php if( isset($inter_id) && $inter_id): ?>
                        <div class="col-sm-3 col-sm-offset-1 ">
<img src="<?php echo Soma_const_url::inst()->get_url('*/*/show_consume_code', array('id'=> $inter_id) ); ?>" />
    					</div>
    					<div class="col-sm-8 inline">
		<p><b>注意事项：</b></p>
		<ol>
			<li><p>使用此功能必须先对扫码微信号进行授权，<a href="<?php echo Soma_const_url::inst()->get_url('privilege/adminuser/profile'); ?>" target="_blank"><b>点击此处</b></a>中 <code>扫码授权</code>进行操作。</p></li>
			<li><p>授权只需一次操作，无须重复授权，本管理员有责任对授权账号进行<code>审核通过</code>和<code>清退操作</code>。</p></li>
			<li><p>授权后微信号所做的操作将等同于本管理员的操作，其操作<code>造成之损失由本管理员承担</code>。</p></li>
			<li><p>一旦进行扫码操作，即等同于同意以上内容</p></li>
			<li><p>一切授权工作完成，扫一扫<code>左边</code>二维码即可开始核销等操作</p></li>
		</ol>
		                </div>
                    <?php else: ?><div>未检测到对应公众号id，必须以商户账号身份登陆使用此功能</div>
                    <?php endif; ?>
    				</div>
    			</div>
    			<!-- /.box-body -->
            </div><!-- /#tab2-->

        </div>
    </div>
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
?><!-- 
<script>
	$(function(){
		$("#el_consumer_code").blur(function(){
			var code = $(this).val();
			var url = "<?php echo $act_post_url; ?>";
			var csrf_token = "<?php echo $csrf_token; ?>";
			var csrf_value = "<?php echo $csrf_value; ?>";
			var code_search = $("#code_search");
			// alert(code);
			if( code ){
				$.ajax({
					type: 'POST',
					url: url,
					data: {csrf_token:csrf_value,code:code},
					success:function(msg){
						// alert(msg);
						// code_search.html('');
						code_search.append( msg );
					}
				});
			}
		});
	});
</script> -->
<script>
$("#el_start_time").datepicker({ format:"yyyy-mm-dd", language: "zh-CN",clearBtn: false,todayBtn: false,orientation: "auto left" });
$("#el_end_time").datepicker({ format:"yyyy-mm-dd", language: "zh-CN",clearBtn: false,todayBtn: false,orientation: "auto left" });
</script>
</body>
</html>
