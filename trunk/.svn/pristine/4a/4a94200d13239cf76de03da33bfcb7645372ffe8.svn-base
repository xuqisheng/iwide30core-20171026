<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
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
          <h1>
            <small></small>
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
        <?php if($this->session->get_admin_inter_id() == FULL_ACCESS):?>
          <div class="row">
            <div class="col-xs-12">
          		<select class="form-control" id="publics">
          			<?php foreach ($publics as $public):?><option value="<?php echo $public->inter_id;?>"<?php if($this->input->get('inter_id') == $public->inter_id):echo ' selected';endif;?>><?php echo $public->name;?></option><?php endforeach;?>
          		</select>
            </div>
          </div><?php endif;?>
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <?php echo $this->session->show_put_msg(); ?>
                <div class="box-header with-border">
                  <h3 class="box-title">结算设置</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                	<?php echo form_open('distribute/welfare/save_config',array('id'=>'setting_form','class'=>'form-inline'));?>
						<div class="row">
							<div class="col-xs-12">
								<div class="radio">
									<label class="radio-inline"><input type="radio" name="upper_limit_typ" value="1"<?php if(isset($config->upper_limit_typ) && $config->upper_limit_typ == 1):echo ' checked';endif;?> />&nbsp;结算上限金额为未结收益</label>
									<label class="radio-inline"><input type="radio" name="upper_limit_typ" value="2"<?php if(isset($config->upper_limit_typ) && $config->upper_limit_typ == 2):echo ' checked';endif;?> />&nbsp;自定义上限</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
						<div class="form-group">
							<label>每日结算次数上限</label>&nbsp;
							<select name="upper_limit_day_times" class="form-control input-sm">
							<option value="1"<?php if(isset($config->upper_limit_day_times) && $config->upper_limit_day_times == 1):echo ' selected';endif;?>>1次</option>
							<option value="2"<?php if(isset($config->upper_limit_day_times) && $config->upper_limit_day_times == 2):echo ' selected';endif;?>>2次</option>
							<option value="3"<?php if(isset($config->upper_limit_day_times) && $config->upper_limit_day_times == 3):echo ' selected';endif;?>>3次</option>
							<option value="4"<?php if(isset($config->upper_limit_day_times) && $config->upper_limit_day_times == 4):echo ' selected';endif;?>>4次</option>
							<option value="5"<?php if(isset($config->upper_limit_day_times) && $config->upper_limit_day_times == 5):echo ' selected';endif;?>>5次</option>
							</select>
						</div>
						<div class="form-group">
							<label>每日结算金额上限</label>&nbsp;<input class="form-control input-sm" type="text" id="upper_limit_day_amount" name="upper_limit_day_amount" value="<?php echo isset($config->upper_limit_day_amount) ? $config->upper_limit_day_amount : 200 ;?>" />
						</div>
						</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
						<div class="radio">
							<label class="radio-inline"><input type="radio" name="welfare" value="2"<?php if(isset($config->welfare) && $config->welfare == 2):echo ' checked';endif;?> />&nbsp;允许发放福利</label>
							<label class="radio-inline"><input type="radio" name="welfare" value="1"<?php if(isset($config->welfare) && $config->welfare == 1):echo ' checked';endif;?> />&nbsp;不允许发放福利</label>
							<p>(允许发放福利：结算页面选择发放福利后，该笔福利金额不会抵减未结收益)</p>
						</div>
						</div>
						</div>
						<div class="form-group"><a href="javascript:;" id="btn_save" class="btn btn-primary">保存</a></div>
                	</form>
                	</div>
                </div>
                <!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
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

<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script>
<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//'http://iwide.cn/',
];
$('#publics').change(function(){
	window.location.href="<?php site_url('distribute/distribute/hotel_config')?>"+'?inter_id='+$(this).val();
});
$(document).ready(function() {
$('#btn_save').click(function(){
	$.post("<?php echo site_url('distribute/welfare/save_config')?>",$('#setting_form').serialize(),function(datas){
		if(datas.errcode == 'ok'){
			alert('保存成功');
		}else{
			alert(datas.errmsg);
		}
	},'json');
	});
});
</script>
</body>
</html>
