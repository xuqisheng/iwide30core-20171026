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
          <h1>微信小程序轮播图管理
            <small></small>
          </h1>
          <ol class="breadcrumb"></ol>
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
                <style>
                .img-row{border-bottom:1px solid #ddd;vertical-align:middle;padding:5px;margin:10px;}
                .img-row input{margin-top: 10%;}
                .col-img img{width: 100%;max-width: 200px;max-height: 100px;}
                </style>
                <div class="box-body">
                <form>
                  <div class="form-group">
                  	<label>图片链接</label>
                  	<input type="text" name="imgurl" id="imgurl" class="form-control" /><input type="file" id="upfiles" />
                  </div>
                  <div class="form-group">
                  	<label>图片描述</label>
                  	<input type="text" size=2 name="imgdescribe" id="imgdescribe" class="form-control" />
                  </div>
                  <div class="form-group">
                  	<label>图片排序</label>
                  	<input type="text" size=2 name="imgsort" id="imgsort" class="form-control" />
                  </div>
                  <button class="btn btn-primary" id="btn_save" type="button">保存</button>
                  </form>
                  <div class="row">&nbsp;</div>
                  <div class="row">
                  	<div class="col-md-3 col-img">图片</div>
                  	<div class="col-md-3">图片描述</div>
                  	<div class="col-md-2">状态</div>
                  	<div class="col-md-2">排序</div>
                  	<div class="col-md-2">操作</div>
                  </div>
                  <?php foreach ($datas as $item){?>
                  <div class="row img-row" id="item<?php echo $item['id']?>">
                  	<div class="col-md-3 col-img" >
                  		<img src="<?php echo $item['image_url']?>" id="imgsurl1" value="<?php echo $item['image_url']?>" alt="<?php echo $item['info']?>" />
                  	</div>
                  	<div class="col-md-3">
                  		<input type="text" name="img_alt"  id="info" value="<?php echo $item['info']?>" class="form-control" />
                  	</div>
                    <div class="col-md-2">
                    	<span><input type="radio" name="img_status_<?php echo $item['id']?>" <?php if ($item['status']==0)echo 'checked';?> value="0" />显示</span>
                    	<span><input type="radio" name="img_status_<?php echo $item['id']?>" <?php if ($item['status']==1)echo 'checked';?> value="1" />不显示</span>
                    </div>
                    <div class="col-md-2">
                    	<input type="text" size=2 name="img_sort"  id="sort" value="<?php echo $item['sort']?>" class="form-control" />
                    </div>
                  	<div class="col-md-2">
                    	<button  class="form-control update" iid="<?php echo $item['id']?>">保存</button>
                  		<button  class="form-control remove" iid="<?php echo $item['id']?>">删除</button>
                  	</div>
                  </div><?php }?>
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
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript">
	<?php $timestamp = time();?>
	$(function() {
		$('#upfiles').uploadify({
			'formData'     : {
				'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
				'timestamp' : '<?php echo $timestamp;?>',
				'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
			},
			'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
			'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
			'file_post_name': 'imgFile',
		    'onUploadSuccess' : function(file, data, response) {
			    var res = $.parseJSON(data);
        		$('#imgurl').val(res.url);
        	}
		});
		$("#btn_save").click(function(){
			var imgurl = $('#imgurl').val();
			var describe   = $('#imgdescribe').val();
            var sort   = $('#imgsort').val();
			if(imgurl == '' || imgurl == undefined){
				alert('请填写图片路径');
                return;
			}
			$.post("<?php echo site_url('app/imgs/afocus_save')?>",
				{
					'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
					'imgurl':imgurl,
					'sort':sort,
					'describe':describe
				},function(datas){
					if(datas.message == 'ok'){
						alert('保存成功');
						window.location.replace(location.href);
					}else{
						alert(datas.message);
					}
				},'json');
		});
		$('.remove').click(function(){
			if(confirm('删除后将不可恢复，确定要删除吗？')){
				$.getJSON("<?php echo site_url('app/imgs/afocus_del')?>",
					{
						'key':$(this).attr('iid')
					},function(datas){
						if(datas.message == 'ok'){
							alert('删除成功');
							window.location.replace(location.href);
						}else{
							alert('删除失败，请重试');
						}
					});
			}
		});

        $('.update').click(function(){
            if(confirm('保存修改？')){
            	var ranges=$('input[name="img_status_'+$(this).attr('iid')+'"]');
            	var status=0;
            	$.each(ranges,function(i,n){
        			if($(n).is(":checked")==true){
        				status=$(n).val();
        			}
        		});
                $.getJSON("<?php echo site_url('app/imgs/afocus_update')?>",
                    {
                    	'key':$(this).attr('iid'),
                    	'sort':$(this).parent().parent().find('#sort').val(),
                    	'info':$(this).parent().parent().find('#info').val(),
                    	'status':status
                    },function(datas){
	                    if(datas.message == 'ok'){
	                        alert('保存成功');
	                    }else{
	                        alert('保存失败，请重试');
	                    }
	                });
            }
        });
	});
</script>
</body>
</html>
