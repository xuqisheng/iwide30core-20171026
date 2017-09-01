<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-select/bootstrap-select.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-select/i18n/defaults-zh_CN.min.js"></script>
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
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <?php echo $this->session->show_put_msg(); ?>
                <div class="box-header">
                  <h3 class="box-title">绩效发放设置</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                	<div class="alert alert-info" role="alert" id="ifo">绩效支付：以下酒店勾选后绩效由“<?php if($deliver_account): echo $publics[$deliver_account];else: echo "--";endif;?>”统一支付，未勾选酒店由后台配置账户支付</div>
                	<div class="panel panel-default">
					  <div class="panel-body">
	                	<?php echo form_open('distribute/distribute/accounts_cfgs','id="setting_form"')?>
							<ul class="list-inline"><?php foreach ($ac_settings as $acset) : ?>
								<li>
									<div class="checkbox">
										<label><input type="checkbox" name="asts[]" value="<?php echo $acset->inter_id?>"<?php if($acset->deliver == 2):echo ' checked';endif;?> /><?php echo $publics[$acset->inter_id]?></label>
									</div>
								</li><?php endforeach;?>
							</ul>
		                	<p class="help-block">（注）这里只显示已配置发放绩效规则的公众号。</p>
							<a class="btn btn-primary" id="save">保存</a>
	                	</form>
					  </div>
					</div>
                	<div class="panel panel-default">
					  <div class="panel-heading">
					  	发放绩效的第三方公众号设置
					  </div>
					  <div class="panel-body">
					  <div class="alert alert-info" role="alert" id="infos"><?php if($deliver_account): echo "当前设置的发放账号是\"{$publics[$deliver_account]}\"";else: echo "当前还没有设置发放账号";endif;?></div>
					  	<select class="selectpicker" data-live-search="true" name="ps"><?php foreach ($publics as $k=>$v):?>
					  		<option value="<?php echo $k?>"<?php echo $k == $deliver_account ? ' selected' : '';?>><?php echo $v?></option><?php endforeach;?>
					  	</select>
					  	<a href="javascript:;" class="btn btn-primary" id="p_s">保存</a>
					  </div>
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
$(document).ready(function() {
	$('#save').on('click',function(){
		if(confirm('确定保存吗？')){
			save_conf();
		}
	});
	$('#p_s').on('click',function(){
		if(confirm('确定保存吗？')){
			save_dsc($('select[name=ps]').val());
		}
	});
});
function save_conf(){
	$.post($('#setting_form').attr('action'),$('form').serialize(),function(datas){
		if(datas == 'success'){
			alert('保存成功');
		}else{
			alert('保存失败');
		}
		return false;
	});
}
function save_dsc(id){
	$.get('<?php echo site_url('distribute/distribute/ds_set')?>',{'iid':id},function(datas){
		if(datas == 'success'){
			$('#infos').html('当前设置的发放账号是"' + $('.filter-option').html() + '"');
			$('#ifo').html('绩效支付：以下酒店勾选后绩效由“' + $('.filter-option').html() + '”统一支付，未勾选酒店由后台配置账户支付');
			alert('保存成功');
		}else{
			alert('保存失败');
		}
	});
}
</script>
</body>
</html>
