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
                	<?php echo form_open('distribute/msgs/save_edit/'.$this->uri->segment(4))?>
					  <?php if($this->uri->segment(4) != 'qa'):?><div class="form-select">
					    <label for="hotel_id">酒店</label>
					    <select name='hotel_id' class="form-control sm">
					    	<option value="ALL">--所有酒店--</option>
					    	<?php foreach ($hotels as $hotel):?><option value="<?php echo $hotel['hotel_id']?>"><?php echo $hotel['name']?></option><?php endforeach;?>
					    </select>
					  </div><?php endif;?>
					  <div class="form-group">
					    <label for="title">标题</label>
					    <input type="text" class="form-control" id="title" name="title" placeholder="请填写标题" value="<?php echo set_value("title"); ?>" />
					  </div>
					  <div class="form-group">
					    <label for="subtitle">副标题</label>
					    <input type="text" id="subtitle" class="form-control" name="sub_title" placeholder="请填写副标题" value="<?php echo set_value("sub_title"); ?>" >
					    <p class="help-block">副标题显示在消息列表的标题下面</p>
					  </div>
					  <div class="form-group">
					    <label for="content">内容</label>
					    <textarea rows="" cols="" name="content" class="form-control"><?php echo set_value("content"); ?></textarea>
					  </div>
					  <button type="submit" class="btn btn-default"> <?php if($this->uri->segment(4) != 'qa'):?>发送<?php else:?>保存<?php endif;?></button>
					</form>
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

<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
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
});
</script>
</body>
</html>
