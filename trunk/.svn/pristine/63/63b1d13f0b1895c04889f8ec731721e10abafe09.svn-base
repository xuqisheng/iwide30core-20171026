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
<div class="box box-info"><!--

    <div class="tabbable "> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息</a></li>
        </ul>

<!-- form start -->

        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
			<?php echo form_open( site_url('hotel/brand/item_save'), array('id'=>'form1','class'=>'form-horizontal','enctype'=>'multipart/form-data' ),array('brand_id'=>$brand_id)); ?>
                <div class="box-body">
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">品牌名称</label>
						<div class="col-sm-8">
							<input type="text" class="form-control " name="name" id="name" placeholder="品牌名称" 
							value="<?php echo $list['name']; ?>">
						</div>
					</div>
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">状态</label>
						<div class="col-sm-8">
							<select name='status' id='status' >
							<option value="1" <?php if($list['status']==1) echo 'selected';?>>有效</option>
							<option value="2" <?php if($list['status']==2) echo 'selected';?>>无效</option>
							</select>
						</div>
					</div>
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">品牌下的酒店</label>
						<div class="col-sm-8">
						<?php if (!empty($hotels)){?>
							
							<?php if (count($hotels)>1){?>
							<input type="text" class="form-control " id="qhs" onchange='quick_search()' placeholder="输入省份、城市、酒店名、ID搜索" />
							<span id='search_tip' style='color:red'></span>
							<?php }?>
							<div id='hotel'>
							<?php foreach ($hotels as $h){?>
								<p><input type="checkbox" name='hotel_ids[]' keyword='<?php echo $h['name'].','.$h['city'].','.$h['province'].','.$h['hotel_id'];?>' value='<?php echo $h['hotel_id'];?>' <?php if (!empty($brand_hotels[$h['hotel_id']]))echo 'checked';?> /><?php echo $h['name'];?></p>
							<?php }?>
							</div>
							<?php }else{?>
							没有酒店
							<?php }?>
						</div>
					</div>
                    <div class="box-footer ">
                        <div class="col-sm-4 col-sm-offset-4">
                            <button type="button" onclick='sub()' class="btn btn-info pull-right">保存</button>
                            <label id='tips' style='color:red;'></label>
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
	<?php echo form_close() ?>
                <!-- /.box-body -->

            </div><!-- /#tab1-->
            
        </div><!-- /.tab-content -->

        </section><!-- /.content -->
</div>
<!-- /.box -->

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
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
var submit_tag=0;
var add_tag=0;
function quick_search() {
	var hk=$('#qhs').val();
	options=$('#hotel input');
	$('#search_tip').html('');
	if(hk){
		search_index=0;
		$.each(options,function(i,n){
			if($(n).attr('keyword').indexOf(hk)<=-1){
				search_index++;
				$(n).parent().css('display','none');
			}else{
				$(n).parent().css('display','block');
			}
		});
		if(search_index==options.length){
			$('#search_tip').html('无结果');
		}
	}else{
		$.each(options,function(i,n){
			$(n).parent().css('display','block');
		});
	}
}

function sub(){
	if(add_tag==1){
		$('#tips').html('已经添加，请勿重复添加。');
		return false;
	}
	var check=true;
	if(submit_tag==0){
		submit_tag=1;
		$('#tips').html('提交中');
		if(!check){
			submit_tag=0;
			return false;
		}
		$.post('<?php echo site_url('hotel/brand/brand_save')?>',
				{
					datas:JSON.stringify($('#form1').serializeArray()),
					<?php echo $csrf_token?>:'<?php echo $csrf_value?>'
				},
		function(data){
			if(data.status==10){
				add_tag=1;
			}
			$('#tips').html(data.message);
			submit_tag=0;
		},'json');
	}else{
		$('#tips').html('提交中，请勿重复提交');
	}
}
</script>
</body>
</html>
