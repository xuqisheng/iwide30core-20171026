<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<style>
<!--
table tr td img{width: 6em;}
-->
</style>
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
                  <table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead><tr><?php 
//print_r($fields_config);die;
	    foreach ($fields_config as $k=> $v):
		     ?><th <?php if(isset($v['grid_width'])) echo 'width="'. $v['grid_width']. '"'; ?> ><?php echo $v['label'];?></th><?php 
	    endforeach; ?></tr></thead>
                    <tfoot><tr><?php 
	    foreach ($fields_config as $k=> $v): 
	       if( isset($v['type']) && $v['type']=='combobox' ) {
	           $search_label= '';
	       } else {
	           $search_label= $v['label'];
	       }
		     ?><th style="text-align:center;"><?php echo $search_label; ?></th><?php 
		endforeach; ?></tr></tfoot>
                    
                  </table>
                  <div class="row">
									<div class="col-sm-5"><a href='<?php echo base_url('public/samples/区域二维码导入标准文档.xlsx')?>'>下载模板</a>
										<div class="dataTables_info" id="data-grid_info" role="status"
											aria-live="polite">
											<input type="file" name="file" id="file" value="批量导入" />(公众号参数须为正式的参数才可生成正式号的二维码)
                                            <p>
                                                <a href="javascript:$('#file').uploadify('upload','*')">确定</a>|
                                                <a href="javascript:$('#file').uploadify('cancel')">取消上传</a>
                                            </p>
                                            
										</div>
									</div>
                                    <div class="col-sm-5">
                                        <div class="dataTables_info" id="data-grid_info" role="status"
                                             aria-live="polite">
                                            从第<input id="output_begin" style="width: 50px;">导出到第
                                            <input id="output_end" style="width:50px">
                                            <a class="btn btn-default"   id="output" name="output">批量导出</a>
                                        </div>
                                    </div>
								</div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <!-- 编辑二维码弹层 -->
							<div id="myModal" class="modal fade" tabindex="-1" role="dialog"
								aria-labelledby="myModalLabel" aria-hidden="true"
								style="background: #fff; width: 420px; height: 308px; margin: 200px auto;">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"
										aria-hidden="true">×</button>
									<h4>编辑二维码</h4>
								</div>
								<div class="modal-body" style="margin: 10px 0 15px 15px; text-align: center;">
				    <?php 
	echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('id'=>'qform','class'=>'form-horizontal') ); ?>
						<input type="hidden" name="key" id="key" value="" />
									<div class="form-group ">
										<label class="col-sm-3 control-label">酒店</label>
										<div class="col-sm-8">
											<select class="form-control" name="hotel" id="hotel">
											<?php foreach ($hotels as $hotel):?><option value="<?php echo $hotel['hotel_id']?>###<?php echo $hotel['name']?>" rel="<?php echo $hotel['name']?>"><?php echo $hotel['name']?></option>
											<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group  has-feedback">
										<label for="el_birthday" class="col-sm-3 control-label">关键字</label>
										<div class="col-sm-8">
											<input type="datebox" class="form-control " name="keyword"
												id="keyword" placeholder="关键字" value="">
										</div>
									</div>
									<div class="form-group  has-feedback">
										<label for="el_birthday" class="col-sm-3 control-label">简介</label>
										<div class="col-sm-8">
											<input type="datebox" class="form-control " name="intro"
												id="intro" placeholder="简介" value="">
										</div>
									</div>
									<input type="button" class="btn btn-primary" id="btn_save" value="保存" />
									</form>
								</div>
							</div>
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
<script src="<?php echo base_url('public/uploadify/jquery.uploadify.min.js')?>"></script>
<script>
<?php $timestamp=time()?>
$(function() {
	$('#file').uploadify({
		'formData'     : {'timestamp' : '{$timestamp}}','token':'<?php echo md5('unique_salt'.$timestamp)?>','<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'},
		'button_width':50,
        'auto':false,
		'button_height':23,
        'fileTypeExts':'*.xls;*.xlsx',
        'multi':false,
		'buttonText':'批量导入',
		'swf'      : '<?php echo base_url('public/uploadify/uploadify.swf')?>',
		'uploader' : '<?php echo site_url('publics/qrcodes/batch_import')?>',
        'onUploadSuccess' : function(file, data, response) {
        	if(data == 'success' || data == ''){
        		location.reload();
        	}else if(data=='limited'){
                alert('最多一次录入200条！');
            }else if(data=='file error'){
        		alert( '操作失败，请重试！');
        	}else{
                alert('第'+data+'行录入的信息有误！')
            }
        }
	});
	$('#btn_save').click(function(){
		$.post("<?php echo site_url('publics/qrcodes/save_edit')?>",{
				'key':$('#key').val(),
				'hotel':$('#hotel').val(),
				'intro':$('#intro').val(),
				'keyword':$('#keyword').val(),
				'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'
			},function(data){
			if(data.errmsg == 'ok'){
				alert('保存成功');
				window.location.reload();
			}else{
				alert('二维码信息保存失败');
			}
		},'json');
	});


    $('#output').click(function(){
        if($('#output_begin').val()=='' || $('#output_end').val()==''){

            alert('请输入要导出的二维码的ID区间');return;

        }

        if(parseInt($('#output_begin').val())  > parseInt($('#output_end').val())){

            alert('请输入正确的ID区间');return;

        }

        var begin = $('#output_begin').val();
        var end = $('#output_end').val();

        location.href="<?php echo site_url('publics/qrcodes/output_qrcode')?>"+"?b="+begin+"&e="+end
    });
});
function doedit(id){
	$.getJSON("<?php echo site_url('publics/qrcodes/get_qrcode_json')?>",{'qid':id},function(data){
		if(data.errmsg == 'ok'){
			$('#key').val(data.item.id);
			$('#hotel').val(data.item.name);
			(data.item.name !='') && $('#hotel option[rel='+data.item.name+']').attr('selected',true);
			$('#intro').val(data.item.intro);
			$('#keyword').val(data.item.keyword);
			$('#myModal').modal('show');
		}else{
			alert('系统繁忙');
		}
	});
}
<?php 
$sort_index= $model->field_index_in_grid($default_sort['field']);
$sort_direct= $default_sort['sort'];

$buttions= '';	//button之间不能有字符空格，用php组装输出
$buttions.= '<button type="button" data-target="#myModal" data-toggle="modal" class="btn btn-sm bg-green" id="grid-btn-add"> <i class="fa fa-plus"></i>&nbsp;新增 </button>';
//$buttions.= '<button type="button" class="btn btn-sm disabled" id="grid-btn-del"><i class="fa fa-trash"></i>&nbsp;删除</button>';
/*有更多的按钮，URL在此定义，id依次编号 id="grid-btn-extra0-1-2-...*/
//$buttions.= '<button type="button" class="btn btn-default" id="grid-btn-extra-0"><i class="fa fa-trash"></i>&nbsp;导出</button>';
if(isset($js_filter_btn)) $buttions.= $js_filter_btn;
?>
var buttons = $('<div class="btn-group"><?php echo $buttions; ?></div>');

var grid_sort= [[ <?php echo $sort_index ?>, "<?php echo $sort_direct ?>" ]];

var dataSet= <?php echo json_encode($result['data']); ?>;
var columnSet= <?php echo json_encode( $model->get_column_config($fields_config) ); ?>;
var url_add= '<?php echo EA_const_url::inst()->get_url("*/*/add"); ?>';			//跟button对应
var url_edit= '<?php echo EA_const_url::inst()->get_url("*/*/edit"); ?>';		//跟button对应
var url_delete= '<?php echo EA_const_url::inst()->get_url("*/*/delete"); ?>';	//跟button对应
var url_ajax= '<?php echo EA_const_url::inst()->get_url("*/*/*"); ?>';
<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//'http://iwide.cn/',
];


$(document).ready(function() {
<?php 
$num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;

//如 view/mall/gridjs.php 存在，则会覆盖 view/privilege/gridjs.php，个性化的部分请拷贝到模块内修改
if( count($result['data'])<$num){
    if( $module ) $m= $module;
    else $m= 'privilege';
    $gridjs= VIEWPATH. $tpl .DS . $m. DS. 'qrcodes/gridjs.php';
    if( file_exists($gridjs) )
        require_once $gridjs;
    else 
        require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs.php';
    
} else {
    if( $module ) $m= $module;
    else $m= 'privilege';
    $gridjs= VIEWPATH. $tpl .DS . $m. DS. 'qrcodes/gridjs_ajax.php';
    if( file_exists($gridjs) )
        require_once $gridjs;
    else 
        require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs_ajax.php';
}
?>

<?php if(isset($js_filter)) echo $js_filter; ?>

});
$('#myModal').on('hidden.bs.modal', function (e) {
	$('#key').val('');
	$('#hotel').val('');
	$('#intro').val('');
	$('#keyword').val('');
})
</script>
</body>
</html>
