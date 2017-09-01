<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
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
                <div class="box-header">
                  <h3 class="box-title">绩效发放设置</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                	<?php echo form_open('distribute/distribute/save_deliv_config','id="setting_form"')?>
						<div class="radiobox">
							<label><input type="radio" name="mode" value='0'<?php if(isset($conf->mode) && $conf->mode == 0):echo ' checked';endif;?>>自动发放</label>
							<label><input type="radio" name="mode" value='1'<?php if(isset($conf->mode) && $conf->mode == 1):echo ' checked';endif;?>>线下发放</label>
						</div>
						<div class="form-group">
							<label>发放周期</label>
							<select name="send_cycle" class="form-control">
								<?php for($i=1;$i<31;$i++):?>
								<option value='<?php echo $i?>'<?php if(isset($conf->cycle) && $conf->cycle == $i):echo ' selected';endif;?>><?php echo $i?>天</option>
								<?php endfor;?>
							</select>
						</div>
						<div class="form-group">
							<label>发放时间</label>
							<select name="send_time" class="form-control">
								<option value='09:00'<?php if(isset($conf->send_time) && $conf->send_time == '09:00:00'):echo ' selected';endif;?>>09:00</option>
								<option value='10:00'<?php if(isset($conf->send_time) && $conf->send_time == '10:00:00'):echo ' selected';endif;?>>10:00</option>
								<option value='11:00'<?php if(isset($conf->send_time) && $conf->send_time == '11:00:00'):echo ' selected';endif;?>>11:00</option>
								<option value='12:00'<?php if(isset($conf->send_time) && $conf->send_time == '12:00:00'):echo ' selected';endif;?>>12:00</option>
								<option value='14:00'<?php if(isset($conf->send_time) && $conf->send_time == '14:00:00'):echo ' selected';endif;?>>14:00</option>
								<option value='15:00'<?php if(isset($conf->send_time) && $conf->send_time == '15:00:00'):echo ' selected';endif;?>>15:00</option>
								<option value='16:00'<?php if(isset($conf->send_time) && $conf->send_time == '16:00:00'):echo ' selected';endif;?>>16:00</option>
							</select>
						</div>
						<div class="form-group">
							<label>发放这个日期之后的绩效</label>
							<input type="text" name="after_time" class="form_datetime form-control" data-date-format="yyyymmdd" value="<?php if(isset($conf->send_after_time)):echo $conf->send_after_time;endif;?>" />
							<p class="help-block">此项如果不填，系统会发放在此之前的所有未发放的绩效</p>
						</div>
						<div class="form-group"><a href="javascript:;" onclick="save_conf()" class="btn btn-primary">保存</a></div>
                	</form>
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

<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script>
<?php 

$buttions= '';	//button之间不能有字符空格，用php组装输出
$buttions.= '<button type="button" class="btn btn-sm bg-green" id="grid-btn-add"><i class="fa fa-plus"></i>&nbsp;发放绩效</button>';
/*$buttions.= '<button type="button" class="btn btn-sm disabled" id="grid-btn-edit"><i class="fa fa-edit"></i>&nbsp;编辑</button>';
$buttions.= '<button type="button" class="btn btn-sm disabled" id="grid-btn-del"><i class="fa fa-trash"></i>&nbsp;删除</button>';*/
/*有更多的按钮，URL在此定义，id依次编号 id="grid-btn-extra0-1-2-...*/
//$buttions.= '<button type="button" class="btn btn-default" id="grid-btn-extra-0"><i class="fa fa-trash"></i>&nbsp;导出</button>';
?>
var buttons = $('<div class="btn-group"><?php echo $buttions; ?></div>');


//var url_add= '<?php echo EA_const_url::inst()->get_url("*/*/add"); ?>';			//跟button对应
//var url_edit= '<?php echo EA_const_url::inst()->get_url("*/*/edit"); ?>';		//跟button对应
//var url_delete= '<?php echo EA_const_url::inst()->get_url("*/*/delete"); ?>';	//跟button对应
<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//'http://iwide.cn/',
];
$(".form_datetime").datepicker({format: 'yyyy-mm-dd 00:00:00'});
$('#publics').change(function(){
	window.location.href="<?php site_url('distribute/distribute/hotel_config')?>"+'?inter_id='+$(this).val();
});
function save_conf(){
	$.post($('#setting_form').attr('action'),$('form').serialize(),function(datas){
		if(datas.errmsg == 'ok'){
			alert('保存成功');
		}else{
			alert('保存失败');
		}
	},'json');
}
$(document).ready(function() {
});
</script>
</body>
</html>
