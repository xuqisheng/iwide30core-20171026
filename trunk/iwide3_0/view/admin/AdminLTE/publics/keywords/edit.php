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

    <div class="tabbable "> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 图文关键字 </a></li>
            <li><a href="#tab2" data-toggle="tab"><i class="fa fa-list-alt"></i> 文本关键字 </a></li>
        </ul>

<!-- form start -->

        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
				<div class="row">
					<?php echo form_open( site_url('publics/keywords/edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data' ) ); ?>
					<div class="col-md-6">
		                <div class="box-body">
		                    <div class="form-group ">
								<label class="col-md-3 control-label">关键字</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="keyword" id="keyword" placeholder="关键字" value="">
								</div>
							</div>
							 <div class="form-group" style="display: none">
								<label class="col-md-3 control-label">匹配类型</label>
								<div class="col-md-8">
									<input type="radio" name="match_type" value='1' />模糊匹配
									<input type="radio" name="match_type" value='2' checked />精确匹配
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">预定义关键字(鼠标移上查看说明)</label>
								<div class="col-md-8">
									<a class='default_keyword' title='关注时推送的消息' href="javascript:void(0)">关注自动回复</a>
									<a class='default_keyword' title='扫描分销员二维码时总会推送此消息(需分销员状态为正常且参与分销，若该二维码单独配置了关键字，则使用其对应的关键字的配置)，当类型为图文消息时，会自动将图文链接带上分销员id(可用于做推荐购买)，你亦可在链接上传入分销号(SALERID)和分销员酒店id(SALERHOTEL)，如saler=SALERID&hotel=SALERHOTEL' href="javascript:void(0)">分销二维码扫描推送</a>
								</div>
							</div>
							 <div class="form-group">
								<label class="col-md-3 control-label">匹配素材</label>
								<div class="col-md-8">
									<style>
									.li_hei li{height:30px;}
									</style>
									<ul class="list-unstyled li_hei" id="articals">
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="box-body">
							<div class="form-group">
								<label class="control-label">素材</label>
								<select multiple class="form-control" id="list">
								  <?php foreach ($news as $item):?><option value="<?php echo $item->id?>"><?php echo $item->title?></option><?php endforeach;?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-12">
		                <div class="box-footer ">
		                    <div class="col-md-6 col-md-offset-0">
		                       <button type="button" class="btn btn-info pull-right" id="btn_save">保存</button>
		                    </div>
		                </div>
	                </div>
		                    <!-- /.box-footer -->
						<?php echo form_close() ?>
				</div>
                <!-- /.box-body -->

            </div><!-- /#tab1-->
            <div class="tab-pane" id="tab2">
				<div class="row">
					<?php echo form_open( site_url('publics/keywords/edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data' ) ); ?>
					<div class="col-md-6">
		                <div class="box-body">
		                    <div class="form-group ">
								<label class="col-md-3 control-label">关键字</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="keyword" id="keyword" placeholder="关键字" value="">
								</div>
							</div>
							 <div class="form-group" style="display: none">
								<label class="col-md-3 control-label">匹配类型</label>
								<div class="col-md-8">
									<input type="radio" name="match_type" value='1' />模糊匹配
									<input type="radio" name="match_type" value='2' checked />精确匹配
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">预定义关键字(鼠标移上查看说明)</label>
								<div class="col-md-8">
									<a class='default_keyword' title='关注时推送的消息' href="javascript:void(0)">关注自动回复</a>
									<a class='default_keyword' title='扫描分销员二维码时总会推送此消息，当类型为图文时，会自动将图文链接带上分销员id，是的，可以用来做推荐购买' href="javascript:void(0)">分销二维码扫描推送</a>
								</div>
							</div>
							 <div class="form-group">
								<label class="col-md-3 control-label">回复文字</label>
								<div class="col-md-8">
								<textarea rows="3" name="content" class="form-control"></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
		                <div class="box-footer ">
		                    <div class="col-md-6 col-md-offset-0">
		                       <button type="button" class="btn btn-info pull-right" id="btn_save">保存</button>
		                    </div>
		                </div>
	                </div>
		                    <!-- /.box-footer -->
						<?php echo form_close() ?>
				</div>
                <!-- /.box-body -->

            </div><!-- /#tab2-->
            
        </div><!-- /.tab-content -->

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
<!--
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>
-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
function del_artical(obj){
	$(obj).parent().parent().parent().remove();
}
$(function () {
	$('.default_keyword').click(function(){
		$("input[name='keyword']").val($(this).html());
	});
	$('#list').dblclick(function(){
		var _this = $(this);
		$('#articals').append('<li><div class="row"><div class="col-md-8">'+_this.find('option:selected').text()+'</div><div class="col-md-2"><input type="text" name="sort" rel="'+_this.val()+'" size="3" value="0" /></div><div class="col-md-2"><i class="fa fa-fw fa-remove" onclick="del_artical(this)"></i></div></div></li>');
	});
	$('#tab1 #btn_save').click(function(){
		var lis = $('#tab1 #articals input');
		if(lis.length > 0){
			var ars = '';
			$.each(lis,function(k,v){
				var _this = $(this);
				ars += ','+_this.val()+':'+_this.attr('rel');
			});
			$.post("<?php echo site_url('publics/keywords/save')?>",{'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>','keyword':$('#tab1 #keyword').val(),'match_type':$("#tab1 input[name='match_type']:checked").val(),'ars':ars.substring(1)},function(datas){
				if(datas.errmsg == 'ok'){
					alert('保存成功');
					window.location.href="<?php echo site_url('publics/keywords/index')?>";
				}else{
					alert('保存失败，请重试!');
				}
			},'json');
		}else{
			alert('请至少选择一个素材');
		}
	});
	$('#tab2 #btn_save').click(function(){
		var lis = $('#tab2 [name=content]').val();
		if(lis.length > 0){
			$.post("<?php echo site_url('publics/keywords/save/text')?>",{'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>','keyword':$('#tab2 #keyword').val(),'match_type':$("#tab2 input[name='match_type']:checked").val(),'ars':lis},function(datas){
				if(datas.errmsg == 'ok'){
					alert('保存成功');
					window.location.href="<?php echo site_url('publics/keywords/index')?>";
				}else{
					alert('保存失败，请重试!');
				}
			},'json');
		}else{
			alert('请填写回复内容');
		}
	});
	//CKEDITOR.replace('el_gs_detail');
	$(".wysihtml5").wysihtml5();
});
</script>
</body>
</html>
