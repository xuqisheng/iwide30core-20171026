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
<style>

#file_upload_1-button{ text-align:center;position: relative; top:15px; background:#F90; color:#fff;font-family: 微软雅黑;padding: 3px 28px;font-weight: 400;}
.radio{display:inline-block; margin-right:50px;}
table.table-bordered tbody td{cursor:default; text-align:center; vertical-align:middle}
#menu .fa{font-size:30px}
td[bgimg]{width:60px; height:60px; background-size:80% 80%; background-repeat:no-repeat; background-position:center center;}
.always{ background-image:url("<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/dist/img/always.png");}
.athour{background-image:url("<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/dist/img/athour.png"); }
.collect{background-image:url("<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/dist/img/collect.png");}
.order{background-image:url("<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/dist/img/order.png");}
.ticket{background-image:url("<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/dist/img/ticket.png");}
.eg_img{width:100%;height:100%;}
</style>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>首页配置
            <small></small>
          </h1>
          <ol class="breadcrumb"></ol>
        </section>
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-body">
                <?php echo $this->session->show_put_msg(); ?>
                <?php if (!empty($disp_set)&&$disp_set['skin_name']!=$default_skin){?>
                <div>当前所用皮肤不支持配置首页</div>
                <?php }else{?>
                   <?php echo form_open('hotel/home_setting/edit_post',array('method'=>'post'));?> 
                   <input type="hidden" name="id" value="<?php if(isset($id)) echo $id;?>">
					<div class="form-group col-xs-8">
						<label>首页展示</label>
						<table class="table table-bordered" id="home_disp">
                        	<tr><td data-toggle="popover" id="ori_btn"><label><input onclick="show_div('ori')" type="radio" name="home_disp" value="ori" <?php if(empty($disp_set) || $disp_set['view_subfix'] =='') echo 'checked';?>> 简版</label></td>
                        	<td data-toggle="popover" id="new_btn"><label><input onclick="show_div('new')" type="radio" name="home_disp" value="new" <?php if($disp_set['view_subfix'] =='new') echo 'checked';?>> 新版</label></td></tr>
                        </table>
					</div>
					<div id='ori_div' <?php if($disp_set['view_subfix'] =='new') echo 'style="display:none"';?>>
					</div>
					<div id='new_div' <?php  if(empty($disp_set) || $disp_set['view_subfix'] =='') echo 'style="display:none"';?>>
						<div class="form-group col-xs-8">
							<label>首页Logo</label>
							<input type="text" class="form-control" name="img" id="img" placeholder="建议尺寸：400*80" value="<?php if(isset($config['img'])) echo $config['img'];?>">
							<label id="upfiles" class="add_img"></label>
						</div>
	                  	<div class="form-group col-xs-8" id="sight">
	                    	<img style="max-width:100%; max-width:150px;" src="<?php if(isset($config['img'])) echo $config['img'];?>">
	                    </div>
	                    <div class="form-group  col-xs-8" style="clear:both">
	                        <label>首页菜单</label>
	                        <table class="table table-bordered" id="showmenu">
	                        	<tr><td><label><input type="radio" name="open" value="1" <?php if(!isset($config['open']) || $config['open'] ==1) echo 'checked';?>> 隐藏</label></td>
	                        	<td><label><input shomenu type="radio" name="open" value="2" <?php if(isset($config['open']) && $config['open'] ==2) echo 'checked';?>> 显示</label></td></tr>
	                        </table>
	                        <table class="table table-bordered" id="menu" <?php if(!isset($config['open']) || $config['open'] !=2) echo 'style="display:none"';?>>
	                        	<tr class="">
	                                <td>菜单1</td>
	                                <td><select class="form-control" name="menu[1][code]" >
	                                        <?php foreach ($select as $key => $value) {?>
	                                          <option value="<?php echo $key;?>" <?php if(isset($config['menu'][1]['code']) && $config['menu'][1]['code'] ==$key) echo 'selected';?>><?php echo $value;?></option>
	                                        <?php } ?>
	                                      </select>
	                                 </td>
	                                 <td bgimg  class="<?php if(isset($config['menu'][1]['code'])) echo $config['menu'][1]['code'];else echo 'always';?>"> </td>
	                                 <td><input type="text" class="form-control" name="menu[1][desc]" placeholder="添加描述，不超过8个字" value="<?php if(isset($config['menu'][1]['desc'])) echo $config['menu'][1]['desc'];?>"></td>
	                            </tr>
	                        	<tr>
	                                <td>菜单2</td>
	                                <td><select class="form-control" name="menu[2][code]" >
	                                        <?php foreach ($select as $key => $value) {?>
	                                          <option value="<?php echo $key;?>" <?php if(isset($config['menu'][2]['code']) && $config['menu'][2]['code'] ==$key) echo 'selected';?>><?php echo $value;?></option>
	                                        <?php } ?>
	                                      </select>
	                                 </td>
	                                 <td bgimg class="<?php if(isset($config['menu'][2]['code'])) echo $config['menu'][2]['code'];else echo 'always';?>"> </td>
	                                 <td><input type="text" class="form-control" name="menu[2][desc]" placeholder="添加描述，不超过8个字" value="<?php if(isset($config['menu'][2]['desc'])) echo $config['menu'][2]['desc'];?>"></td>
	                            </tr>
	                        	<tr>
	                                <td>菜单3</td>
	                                <td><select class="form-control" name="menu[3][code]">
	                                        <?php foreach ($select as $key => $value) {?>
	                                          <option value="<?php echo $key;?>" <?php if(isset($config['menu'][3]['code']) && $config['menu'][3]['code'] ==$key) echo 'selected';?>><?php echo $value;?></option>
	                                        <?php } ?>
	                                      </select>
	                                 </td>
	                                 <td bgimg class="<?php if(isset($config['menu'][3]['code'])) echo $config['menu'][3]['code'];else echo 'always';?>"> </td>
	                                 <td><input type="text" class="form-control" name="menu[3][desc]" placeholder="添加描述，不超过8个字" value="<?php if(isset($config['menu'][3]['desc'])) echo $config['menu'][3]['desc'];?>"></td>
	                            </tr>
	                        </table>
	                    </div>
                    </div>
                    <div class="col-xs-12" style="margin-top:20px">
                        <button type="submit" class="btn btn-info">保存</button>
                    </div>
                  <?php echo form_close();?>
                <?php }?>
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

<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php';
?>
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify_html5/jquery.uploadify.js"></script>
<script>
function show_div(divname){
	if(divname=='ori'){
		$('#new_div').hide();
		$('#ori_div').show();
	}else if(divname=='new'){
		$('#ori_div').hide();
		$('#new_div').show();
	}
}
$(function () {
   $('#ori_btn').popover({
        trigger : 'hover',//鼠标以上时触发弹出提示框
        html:true,//开启html 为true的话，data-content里就能放html代码了
        content:"<img class='eg_img' src='http://file.cdn.iwide.cn/public/uploads/201612/qf071856348762.png'>",
        placement:'bottom'
    });
    $('#new_btn').popover({
        trigger : 'hover',
        html:true,
        content:"<img class='eg_img' src='http://file.cdn.iwide.cn/public/uploads/201612/qf071856136246.png'>",
        placement:'bottom'
    });
	$('#showmenu').click(function(){
		if($('input[shomenu]').get(0).checked)$('#menu').show();
		else $('#menu').hide();
	})
	// $('#img').parent().append('<input type="file" value="上传图片" id="upfiles">');
	$('#upfiles').uploadify({
		'formData'     : {
			'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
			'timestamp' : '',
			'token'     : ''
		},
		'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
		'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
		'fileObjName': 'imgFile',
        'fileTypeExts':'*.jpg;*.jpeg;*.gif;*.png',//文件类型
		'onUploadSuccess' : function(file, data, response) {
			var res = $.parseJSON(data);
			$('#img').val(res.url);
			$('#sight').html('<img style="max-width:100%; max-width:150px;" src="'+res.url+'" />');
		}
	});
	$('.uploadify-button-text').html('选择图片');
	$('select').change(function(){$(this).parents('tr').find('[bgimg]').removeClass().addClass($(this).val());});
});
</script>
</body>
</html>
