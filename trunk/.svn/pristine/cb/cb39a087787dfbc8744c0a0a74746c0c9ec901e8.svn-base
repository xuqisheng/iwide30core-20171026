<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
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
          <h1><?php echo $breadcrumb_array['action']; ?>
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
              <!-- 
                <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <style>
                  	#parent li p span{float:right;}
                  </style>
                  <div class="container">
                  <div class="row">
                  	<div class="col-xs-4" style="border:1px solid #ddd">
	                  <?php echo form_close();?>
	                  <ol id="parent">
	                  	<?php $i=1; foreach ($menus as $pk => $note):$i++;?>
	                  	<li mid="<?php echo $pk;?>" rid="<?php echo isset($note['id'])?$note['id']:'';?>" sort="<?php echo isset($note['sort'])?$note['sort']:'';?>">
	                  		<p><span mid="<?php echo $pk;?>" rid="<?php echo isset($note['id'])?$note['id']:'';?>">
	                  		<i class="fa fa-fw fa-pencil main_menu"></i>
	                  		<i class="fa fa-fw fa-remove"></i></span><?php echo isset($note['title'])?$note['title']:'';?></p>
	                  	<?php if(!empty($note['sub_items'])):?><ol class='sortable'>
	                  	<?php foreach ($note['sub_items'] as $k => $sub_note):?>
	                  	<li mid="<?php echo $k?>" rid="<?php echo isset($sub_note['id'])?$sub_note['id']:'';?>" sort="<?php echo isset($sub_note['sort'])?$sub_note['sort']:'';?>">
	                  	<p><span mid="<?php echo $pk?>" sid="<?php echo $k;?>" rid="<?php echo isset($sub_note['id'])?$sub_note['id']:'';?>">
	                  	<i class="fa fa-fw fa-pencil sub_menu"></i>
	                  	<i class="fa fa-fw fa-remove"></i></span><?php echo isset($sub_note['title'])?$sub_note['title']:'';?></p></li><?php endforeach;?></ol>
	                  	<?php endif;?></li><?php endforeach;?>
	                  </ol>
						<div class="form-group" style="text-align: center;">
							<button type="button" class="btn btn-primary" id="btn_save">保存菜单</button>&nbsp;&nbsp;&nbsp;&nbsp;
							<button type="button" class="btn btn-primary" id="btn_generate">生成菜单</button>
						</div>
						<p class="help-block" style="text-align: center; font-style:oblique">拖动菜单排序</p>
	                 </div>
	                 <div class="col-xs-1">&nbsp;
	                 </div>
	                 <div class="col-xs-6" style="border:1px solid #ddd">
	                 <form action="" id="edit_form">
	                 	<input type="hidden" value="" name="mid" id="mid" />
			        	<div class="form-group">
						    <label for="menu_name">菜单名称</label>
						    <input type="text" class="form-control" id="menu_name" placeholder="菜单名称">
						</div>
			        	<div class="form-group">
						    <label for="parent_name">父级菜单</label>
						    <select class="form-control" name="parent_name" id="parent_name">
						      <option value="0">-- 无 --</option>
							  <?php foreach ($menus as $note):?><option value="<?php echo $note['id']?>"><?php echo $note['title']?></option><?php endforeach;?>
							</select>
						</div>
			        	<div class="form-group">
						    <label for="menu_type">菜单类型</label>
						    <select class="form-control" name="menu_type" id="menu_type">
							  <option value="0">关键字回复</option>
							  <option value="1">URL外链</option>
							  <option value="2">拓展菜单</option>
							</select>
						</div>
						<div class="form-group" id="text_box">
						    <label for="parent_name">关键字回复</label>
						    <input type="text" class="form-control" id="menu_content" placeholder="">
						</div>
			        	<div class="form-group" id="sys_menu" style="display: none">
						    <label for="sys_menu">拓展菜单</label>
						    <select class="form-control" name="sys_menu" id="menu_select">
							  <option value="scancode_waitmsg">扫码带提示</option>
							  <option value="scancode_push">扫码推事件</option>
							  <option value="pic_sysphoto">系统拍照发图</option>
							  <option value="pic_photo_or_album">拍照或者相册发图</option>
							  <option value="pic_weixin">微信相册发图</option>
							  <option value="location_select">发送位置</option>
							</select>
						</div>
						<div class="form-group" style="text-align: right;">
							<button type="button" class="btn btn-primary" id="save_menu_item">保存</button>
						</div>
			        </form>
	                 </div>
                  </div></div>
                </div><!-- /.box-body -->
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
<div class="modal fade" id="menu_model">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">编辑菜单</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-primary">保存</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php';
?>
<script>
var datas = <?php echo json_encode($menus)?>;
var sys_menus = <?php echo json_encode($sys_menu)?>;
$(document).ready(function() {
	$('#menu_model').on('shown.bs.modal', function () {
		$('#menu_model').focus();
	})
});

$(function() {
    $('.sortable').sortable({ stop: function(event, ui) {}});   
    $('.sortable').bind('sortstop', function(event, ui) {
    	//ui.item.attr('sort',ui.item.index());
    	lisort(ui.item.parent());
    }); 
    function lisort(parent){
        var lis = $('li',parent);
		$.each(lis,function(k,v){
			var _this = $(v);
			_this.attr('sort',_this.index());
		});
    }
    $('.main_menu').click(function(){
        var _id = $(this).parent().attr('mid');
        if(datas[_id].sub_items.length > 0)
            $('#parent_name').prop('disabled',true);
        else
            $('#parent_name').prop('disabled',false);
        $('#mid').val($(this).parent().attr('rid'));
		$('#edit_form #menu_name').val(datas[_id].title);
		$('#menu_type').val(datas[_id].menu_type);
		$('#parent_name').val(0);
		if(datas[_id].menu_type == 0){
			$('#text_box').css('display','');
			$('#sys_menu').css('display','none');
			$('#text_box label').html('关键字');
			$('#menu_content').val(datas[_id].keyword);
		}else if(datas[_id].menu_type == 1){
			$('#text_box').css('display','');
			$('#sys_menu').css('display','none');
			$('#text_box label').html('URL');
			$('#menu_content').val(datas[_id].url);
		}else if(datas[_id].menu_type == 2){
			$('#text_box').css('display','none');
			$('#sys_menu').css('display','');
			$('#menu_select').val(datas[_id].extend_menu);
		}
		$('#edit_form #menu_name').val(datas[_id].title);
		$('#edit_form #sort').val(datas[_id].sort);
    });
    $('.sub_menu').click(function(){
        $('#parent_name').prop('disabled',false);
        var _mid = $(this).parent().attr('mid');
        var _sid = $(this).parent().attr('sid');
        $('#mid').val($(this).parent().attr('rid'));
		$('#edit_form #sort').val(datas[_mid].sub_items[_sid].sort);
        $('#edit_form #menu_name').val(datas[_mid].sub_items[_sid].title);
		$('#menu_type').val(datas[_mid].sub_items[_sid].menu_type);
		$('#parent_name').val(datas[_mid].sub_items[_sid].parent_id);
		if(datas[_mid].sub_items[_sid].menu_type == 0){
			$('#text_box').css('display','');
			$('#sys_menu').css('display','none');
			$('#text_box label').html('关键字');
			$('#menu_content').val(datas[_mid].sub_items[_sid].keyword);
		}else if(datas[_mid].sub_items[_sid].menu_type == 1){
			$('#text_box').css('display','');
			$('#sys_menu').css('display','none');
			$('#text_box label').html('URL');
			$('#menu_content').val(datas[_mid].sub_items[_sid].url);
		}else if(datas[_mid].sub_items[_sid].menu_type == 2){
			$('#text_box').css('display','none');
			$('#sys_menu').css('display','');
			$('#menu_select').val(datas[_mid].sub_items[_sid].extend_menu);
		}
		$('#edit_form #menu_name').val(datas[_mid].sub_items[_sid].title);
    });
    $('.fa-remove').click(function(){
        if(confirm('删除后将不可恢复，确定要删除吗？')){
	        var _id = $(this).parent().parent().parent().attr('rid');
	    	$.post("<?php echo site_url('publics/menu/delete_menu_item')?>",{'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>','ids':_id},function(datas){
				if(datas.errmsg == 'ok'){
					alert('删除成功');
					window.location.reload();
				}else{
					alert('操作失败');
				}
			},'json');
        }
    });
    $( "#parent" ).sortable({
    	stop: function(event, ui) {}
      });
    $('#parent').bind('sortstop', function(event, ui) {
    	var lis = $('#parent>li');
		$.each(lis,function(k,v){
			var _this = $(v);
			_this.attr('sort',_this.index());
		});
    });
    $('#menu_type').change(function(){
        var val = $(this).val();
		if(val == 0){
			$('#text_box').css('display','');
			$('#sys_menu').css('display','none');
			$('#text_box label').html('关键字');
		}else if(val == 1){
			$('#text_box').css('display','');
			$('#sys_menu').css('display','none');
			$('#text_box label').html('URL');
		}else if(val == 2){
			$('#text_box').css('display','none');
			$('#sys_menu').css('display','');
		}
    });
  });
	$('#save_menu_item').click(function(){
		var val = $('#menu_content').val().replace(/\s/g,"");
		if($('#menu_content').siblings().html()=='URL'){
			console.log(val)
			var key = '&saler=';
			var key2= '&code=';
			if( val.indexOf(key)>=0 ){
				alert('URL不可携带saler参数');
				return;
			}
			if( val.indexOf(key2)>=0 ){
				alert('URL不可携带code参数');
				return;
			}
		}
		$.post("<?php echo site_url('publics/menu/save_menu_item')?>",{'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
					'name':$('#menu_name').val(),
					'parent':$('#parent_name').val(),
					'menu_type':$('#menu_type').val(),
					'menu_content':val,
					'sort':$('#sort').val(),
					'id':$('#mid').val(),
					'sys_val':$('#menu_select').val()},function(datas){
			if(datas.errmsg == 'ok'){
				alert('保存成功');
				window.location.reload();
			}else{
				alert(datas.errmsg);
			}
		},'json');
	});
	$('#btn_save').click(function(){
		var lis = $('#parent li');
		var posStr = '';
		$.each(lis,function(k,v){
			var obj = $(v);
			posStr += ',' + obj.attr('rid') + ':' + obj.attr('sort');
		});
		$.post("<?php echo site_url('publics/menu/save_menu')?>",{'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>','sort':posStr.substring(1)},function(datas){
			if(datas.errmsg == 'ok'){
				alert('保存成功');
			}else{
				alert('操作失败');
			}
		},'json');
	});
	$('#btn_generate').click(function(){
		$.getJSON("<?php echo site_url('publics/menu/generate')?>",{'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'},function(datas){
			if(datas.errmsg == 'ok'){
				alert('菜单生成成功.');
			}else{
				alert(datas.errmsg + ',菜单生成失败，请重试!!!');
			}
		});
	});
</script>
</body>
</html>
