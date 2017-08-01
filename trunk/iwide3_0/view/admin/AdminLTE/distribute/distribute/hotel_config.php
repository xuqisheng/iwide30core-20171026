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
              <!-- 
                <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                	<form action="">
						<fieldset>
						<div class="form-group">
							<input type="radio" name="s_type" value="1"<?php if (isset($sub_config['typ']) && $sub_config['typ'] == 1):?> checked<?php endif;?> />关闭互动增粉
							<input type="radio" name="s_type" value="2"<?php if (isset($sub_config['typ']) && $sub_config['typ'] == 2):?> checked<?php endif;?> />开启互动增粉
						</div>
						<div class="form-group">
							<label>新增粉丝激励金额</label>
							<input class="form-control" type="text" id="fans_val" name="fans_val" value="<?php if (isset($sub_config['val'])): echo $sub_config['val'];else:echo ''; endif;?>" />
						</div>
						<div class="form-group"><a href="javascript:;" onclick="save_fans()" class="btn btn-primary">保存</a></div>
					</fieldset>
				</div></div>
				  <div class="box">
				<div class="box-body">
					<fieldset>
					<div class="form-group">
							<label class="control-label">绩效期限</label>
							<p class="control-label">粉丝通过分销员关注满
							<select id="distribute_time" name='distribute_time'>
								<?php for($i=1;$i<=12;$i++){?>
								<option value='<?php echo $i?>' <?php echo (isset($distribute_config_data['distribute_time']) && $distribute_config_data['distribute_time'] == $i)?'selected':''?>><?php echo $i?>个月</option>
								<?php }?>
							</select>
						后，粉丝产生交易不再计算绩效</p>
						</div>
						<div class="form-group">
							<label class="control-label">期限状态 </label>
							<input type="radio" name="distribute_status" value="0"  <?php if (isset($distribute_config_data['distribute_status']) && $distribute_config_data['distribute_status'] == 0):?> checked<?php endif;?> />不启用
							<input type="radio" name="distribute_status" value="1" <?php if (isset($distribute_config_data['distribute_status']) && $distribute_config_data['distribute_status'] == 1):?> checked<?php endif;?> />启用
						</div>
						
						<div class="form-group">
						<?php if (isset($distribute_config_data['distribute_status']) && $distribute_config_data['distribute_status'] == 1){?>
						<a href="javascript:;" disabled class="btn btn-primary">保存</a>
						<?php }else{?>
						<a href="javascript:;" onclick="save_distribute()" class="btn btn-primary">保存</a>
						<?php }?>
						<span style="color:#ff0000">注：该功能开通之后将不可取消</span>
						</div>
					</fieldset>
				</div></div>
              <div class="box">
				<div class="box-body">
					<fieldset>
						<div class="form-group">
							<label>套票激励金额</label>
							<input class="form-control" type="text" id="package_val" name="package_val" value="<?php if (isset($pac_config['val'])): echo $pac_config['val'];else:echo ''; endif;?>" />
						</div>
						<div class="form-group"><a href="javascript:;" onclick="save_package()" class="btn btn-primary">保存</a></div>
					</fieldset>
				</div></div>
              <div class="box">
				<div class="box-body">
					<fieldset>
						<div class="form-group">
							<label>商城激励类型</label>
							<input type="radio" name="m_type" value="0"<?php if (isset($mall_config['type']) && $mall_config['type'] == 0):?> checked<?php endif;?> />订单金额百分比
							<input type="radio" name="m_type" value="1"<?php if (isset($mall_config['type']) && $mall_config['type'] == 1):?> checked<?php endif;?> />每个订单激励固定金额
						</div>
						<div class="form-group">
							<label>商城激励金额</label>
							<input class="form-control" type="text" id="mall_val" name="mall_val" value="<?php if (isset($mall_config['val'])): echo $mall_config['val'];else:echo ''; endif;?>" />
						</div>
						<div class="form-group"><a href="javascript:;" onclick="save_mall()" class="btn btn-primary">保存</a></div>
					</fieldset>
				</div></div>
              <?php /*<!-- <div class="box">
				<div class="box-body">
					<fieldset>
						<div class="form-group">
							<label>订房激励类型</label>
							<!-- <input type="radio" name="r_type" value="1"<?php if (isset($room_config['type']) && $room_config['type'] == 1):?> checked<?php endif;?>/>优惠前房价百分比-->
							<input type="radio" name="r_type" value="2"<?php if (isset($room_config['type']) && $room_config['type'] == 2):?> checked<?php endif;?> />优惠后房价百分比
							<input type="radio" name="r_type" value="3"<?php if (isset($room_config['type']) && $room_config['type'] == 3):?> checked<?php endif;?> />每个订单激励固定金额
							<input type="radio" name="r_type" value="4"<?php if (isset($room_config['type']) && $room_config['type'] == 4):?> checked<?php endif;?> />每间/夜激励固定金额
						</div>
						<div class="row">
						<div class="col-md-12">
						<div class="form-group">
							<label>订房激励金额/百分比</label>
						</div>
						</div>
						</div>
						<div class="row">
						<div class="col-md-3">
						<div class="form-group">
							<label>员工</label>
							<input class="form-control" type="text" id="staff_amount" name="staff_amount" value="<?php if (isset($room_config['val_staff'])): echo $room_config['val_staff'];else:echo ''; endif;?>" />
						</div>
						</div>
						<div class="col-md-3">
						<div class="form-group">
							<label>酒店</label>
							<input class="form-control" type="text" id="hotel_amount" name="hotel_amount" value="<?php if (isset($room_config['val_hotel'])): echo $room_config['val_hotel'];else:echo ''; endif;?>" />
						</div>
						</div>
						<div class="col-md-3">
						<div class="form-group">
							<label>金房卡</label>
							<input class="form-control" type="text" id="jfk_amount" name="jfk_amount" value="<?php if (isset($room_config['val_jfk'])): echo $room_config['val_jfk'];else:echo ''; endif;?>" />
						</div>
						</div>
						<div class="col-md-3">
						<div class="form-group">
							<label>集团</label>
							<input class="form-control" type="text" id="group_amount" name="group_amount" value="<?php if (isset($room_config['val_group'])): echo $room_config['val_group'];else:echo ''; endif;?>" />
						</div>
						</div>
						</div>
						<div class="form-group"><a href="javascript:;" onclick="save_room()" class="btn btn-primary">保存</a></div>
					</fieldset>
					</div></div> */?>
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
$('#publics').change(function(){
	window.location.href="<?php site_url('distribute/distribute/hotel_config')?>"+'?inter_id='+$(this).val();
});
function save_fans(){
	$.post("<?php echo site_url('distribute/distribute/save_fans_config')?>",{'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>','amount':$('#fans_val').val(),'type':$('input[name=s_type]:checked').val()},function(datas){
		if(datas.errmsg == 'ok'){
			alert('保存成功');
		}else{
			alert('保存失败');
		}
	},'json');
}
function save_distribute(){
	$.post("<?php echo site_url('distribute/distribute/save_distribute_data_config')?>",
		{'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>','distribute_time':$('#distribute_time option:selected').val(),'distribute_status':$("input[name='distribute_status']:checked").val()},
		function(datas){
			if(datas.errmsg == 'ok'){
				alert('保存成功');
			}else{
				alert('保存失败');
			}
	},'json');
}
function save_package(){
	$.post("<?php echo site_url('distribute/distribute/save_package_config')?>",{'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>','amount':$('#package_val').val()},function(datas){
		if(datas.errmsg == 'ok'){
			alert('保存成功');
		}else{
			alert('保存失败');
		}
	},'json');
}
function save_room(){
	$.post("<?php echo site_url('distribute/distribute/save_room_config')?>",{'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>','staff_amount':$('#staff_amount').val(),'hotel_amount':$('#hotel_amount').val(),'jfk_amount':$('#jfk_amount').val(),'group_amount':$('#group_amount').val(),'type':$('input[name=r_type]:checked').val()},function(datas){
		if(datas.errmsg == 'ok'){
			alert('保存成功');
		}else{
			alert('保存失败');
		}
	},'json');
}
function save_mall(){
	$.post("<?php echo site_url('distribute/distribute/save_mall_config')?>",{'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>','amount':$('#mall_val').val(),'type':$('input[name=m_type]:checked').val()},function(datas){
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
