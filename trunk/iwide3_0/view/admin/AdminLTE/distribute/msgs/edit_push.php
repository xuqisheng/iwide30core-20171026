<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
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
          <h1>消息编辑
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
                <div class="box-body">
                	<?php echo form_open('distribute/msgs/save_push/'.$this->uri->segment(4))?>
					    <label for="hotel_id">推送对象</label>
					    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#obj-model">选择</button>
					  <div class="form-group">
					    <label for="title">推送时间</label>
					    <div class=" input-group date">
				            <input type="text" class="form-control" name="push_time" size="16" id="push_time" placeholder="请填写推送时间,为空表示立即推送" value="<?php echo set_value("push_time"); ?>">
				            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
				    	</div>
					  </div>
					  <div class="form-group">
					    <label for="title">员工礼包</label>
					    <select class="form-control" name="package"><option value="" selected>不赠送礼包</option><?php foreach ($packages as $package):?>
					    <option value="<?php echo $package->inter_id.$package->package_id?>"><?php echo $package->name?></option><?php endforeach;?></select>
					  </div>
					  <div class="form-group">
					    <label for="btn_guide">引流按钮</label>
					    <input type="text" class="form-control" id="btn_guide" name="btn_guide" placeholder="请填写引流按钮" value="<?php echo set_value("title"); ?>" />
					  </div>
					  <div class="form-group">
					    <label for="lnk_guide">按钮链接</label>
					    <input type="text" class="form-control" id="lnk_guide" name="lnk_guide" placeholder="请填写按钮链接" value="<?php echo set_value("title"); ?>" />
					  </div>
					  <div class="form-group">
					    <label for="title">标题</label>
					    <input type="text" class="form-control" id="title" name="title" placeholder="请填写标题" value="<?php echo set_value("title"); ?>" />
					  </div>
					  <div class="form-group">
					    <label for="subtitle">简介</label>
					    <input type="text" id="subtitle" class="form-control" name="sub_title" placeholder="请填写简介" value="<?php echo set_value("sub_title"); ?>" >
					    <p class="help-block">显示在弹出提示框的内容</p>
					  </div>
					  <div class="form-group">
					    <label for="content">内容</label>
					    <textarea rows="" cols="" name="content" class="form-control"><?php echo set_value("content"); ?></textarea>
					  </div>
					  <button type="submit" class="btn btn-default"> <?php if($this->uri->segment(4) != 'qa'):?>发送<?php else:?>保存<?php endif;?></button>
					  <input type="hidden" name="salers" value="" />
					</form>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <div class="modal fade" id="obj-model">
			<div class="modal-dialog" style="width:1002px">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title">推送对象选择</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-3">
								<div class="panel panel-default">
								  <div class="panel-heading">所属公众号</div>
								  <div class="panel-body">
								    <dl id="lkpl">
								    	<dt><a name="check_all" rel='cpls' nel="hls" style="cursor:pointer">全选</a><input type="text" name="kpls" id="kpls" /></dt>
								    	<dd class="checkbox">loading...</dd>
								    </dl>
								  </div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="panel panel-default">
								  <div class="panel-heading">所属酒店</div>
								  <div class="panel-body">
								    <dl id="lkhl">
								    	<dt><a name="check_all" rel='chls' nel="dls" style="cursor:pointer">全选</a><input type="text" name="khls" id="khls" /></dt>
								    	<dd class="checkbox">loading...</dd>
								    </dl>
								  </div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="panel panel-default">
								  <div class="panel-heading">所属部门</div>
								  <div class="panel-body">
								    <dl id="lkdl">
								    	<dt><a name="check_all" rel='cdls' nel="sls" style="cursor:pointer">全选</a><input type="text" name="kdls" id="kdls" /></dt>
								    	<dd class="checkbox">loading...</dd>
								    </dl>
								  </div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="panel panel-default">
								  <div class="panel-heading">分销员</div>
								  <div class="panel-body">
								    <dl id="lksl">
								    	<dt><a name="check_all" rel='csls' nel="" style="cursor:pointer">全选</a><input type="text" name="ksls" id="ksls" /></dt>
								    	<dd class="checkbox">loading...</dd>
								    </dl>
								  </div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
						<button type="button" class="btn btn-primary" id="setModelConfirm">确定</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		<!-- /.modal -->
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

<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script>

$(document).ready(function() {
	$('textarea[name=content]').wysihtml5({
		"font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
		"emphasis": true, //Italics, bold, etc. Default true
		"lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
		"html": true, //Button which allows you to edit the generated HTML. Default false
		"link": true, //Button to insert a link. Default true
		"image": true, //Button to insert an image. Default true,
		"color": true //Button to change color of font  
	});
	get_pls();
	get_hls();
	get_dls();
	get_sls();
	$('#kpls').on('change',function(){
		get_pls();
	});
	$('#khls').on('change',function(){
		get_hls();
	});
	$('#kdls').on('change',function(){
		get_dls();
	});
	$('#ksls').on('change',function(){
		get_sls();
	});
	$('a[name=check_all]').on('click',function(){
		var _this = $(this);
		if(_this.html() == '全选'){
			checkAll(_this.attr('rel'),true);
			_this.html('全不选');
		}else{
			checkAll(_this.attr('rel'),false);
			_this.html('全选');
		}
		_this.attr('nel') != '' && eval('get_' + _this.attr('nel') + '()');
	});
	$('#setModelConfirm').on('click',function(){
		var salers = $('input[name="csls[]"]:checked');
		if(salers.size() < 1){
			alert('请至少选择一个分销员');
			return;
		}else{
			$('input[name=salers]').val(getObjStr($('input[name="csls[]"]:checked')));
			$('#obj-model').modal('hide');
		}
	});
});
function get_pls(){
	$.getJSON("<?php echo site_url('distribute/msgs/pls')?>",{'kpls':$('#kpls').val()},function(data){
		var tempHtml = '';
		$.each(data,function(k,v){
			tempHtml += '<dd class="checkbox"><label><input type="checkbox" name="cpls[]" value="'+k+'">'+v+'</label></dd>';
		}); 
		$('#lkpl>dd').remove();
		$('#lkpl').append(tempHtml);
		$('input[name="cpls[]"]').on('click',function(){
			get_hls();
		});
	});
}
function get_hls(){
	var pls = getObjStr($('input[name="cpls[]"]:checked'));
	$.post("<?php echo site_url('distribute/msgs/hts')?>",{'kpls':pls,'khls':$('#khls').val(),'<?php echo $this->security->get_csrf_token_name()?>':'<?php echo $this->security->get_csrf_hash()?>'},function(data){
		var tempHtml = '';
		$.each(data,function(k,v){
			tempHtml += '<dd class="checkbox"><label><input type="checkbox" name="chls[]" value="'+k+'">'+v+'</label></dd>';
		}); 
		$('#lkhl>dd').remove();
		$('#lkhl').append(tempHtml);
		$('input[name="chls[]"').on('click',function(){
			get_dls();
		});
		get_dls();
	},'json');
}
function get_dls(){
	var pls = getObjStr($('input[name="cpls[]"]:checked'));
	var hls = getObjStr($('input[name="chls[]"]:checked'));
	$.post("<?php echo site_url('distribute/msgs/depts')?>",{'kpls':pls,'khls':hls,'kdls':$('#kdls').val(),'<?php echo $this->security->get_csrf_token_name()?>':'<?php echo $this->security->get_csrf_hash()?>'},function(data){
		var tempHtml = '';
		$.each(data,function(k,v){
			tempHtml += '<dd class="checkbox"><label><input type="checkbox" name="cdls[]" value="'+v+'">'+v+'</label></dd>';
		}); 
		$('#lkdl>dd').remove();
		$('#lkdl').append(tempHtml);
		$('input[name="cdls[]"').on('click',function(){
			get_sls();
		});
		get_sls();
	},'json');
}
function get_sls(){
	var pls = getObjStr($('input[name="cpls[]"]:checked'));
	var hls = getObjStr($('input[name="chls[]"]:checked'));
	var dls = getObjStr($('input[name="cdls[]"]:checked'));
	$.post("<?php echo site_url('distribute/msgs/sals')?>",{'kpls':pls,'khls':hls,'kdls':dls,'ksls':$('#ksls').val(),'<?php echo $this->security->get_csrf_token_name()?>':'<?php echo $this->security->get_csrf_hash()?>'},function(data){
		var tempHtml = '';
		$.each(data,function(k,v){
			tempHtml += '<dd class="checkbox"><label><input type="checkbox" name="csls[]" value="'+k+'">'+v.name+'</label></dd>';
		}); 
		$('#lksl>dd').remove();
		$('#lksl').append(tempHtml);
	},'json');
}
function getObjStr(objArr){
	if(objArr.size() > 1){
		var tmpArr = new Array();
		$.each(objArr,function(k,v){
			tmpArr.push($(v).val());
		});
		return tmpArr.toString();
	}else{
		return objArr.val();
	}
}
function checkAll(rel,status){
	$('input[name="'+rel+'[]"]').prop('checked',status);
}
</script>
</body>
</html>
