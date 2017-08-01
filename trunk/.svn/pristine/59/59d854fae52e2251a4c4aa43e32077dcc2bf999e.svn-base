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
					分销业绩 <small></small>
				</h1>
				<ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
			</section>
			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box">
                <?php echo $this->session->show_put_msg(); ?>
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
							<!-- 
                <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
                </div><!-- /.box-header -->
							<div class="box-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTables_length" id="data-grid_length">
											<label>
												<!-- 每页显示 
									<select name="data-grid_length" aria-controls="data-grid" class="form-control input-sm">
										<option value="20">20</option>
										<option value="50">50</option>
										<option value="100">100</option>
										<option value="200">200</option>
									</select> 条记录&nbsp;&nbsp;&nbsp; -->
												<div class="btn-group">
													<button type="button" data-target="#myModal"
														data-toggle="modal" class="btn btn-sm bg-green"
														id="grid-btn-add">
														<i class="fa fa-plus"></i>&nbsp;新增
													</button>
												</div>
											</label>
										</div>
									</div>
									<!-- <div class="col-sm-6">
							<div id="data-grid_filter" class="dataTables_filter">
								<label>搜索<input type="search" class="form-control input-sm" placeholder="" aria-controls="data-grid"></label>
							</div>
						</div> -->
								</div>
								<table id="data-grid"
									class="table table-bordered table-striped table-condensed">
									<thead>
										<tr role="row">
											<th width="10%" class="sorting_desc" tabindex="0"
												aria-controls="data-grid" rowspan="1" colspan="1"
												aria-sort="descending"
												aria-label="场景ID: activate to sort column ascending">场景ID</th>
											<th width="5%" class="sorting" tabindex="0"
												aria-controls="data-grid" rowspan="1" colspan="1"
												aria-label="二维码: activate to sort column ascending">二维码</th>
											<th width="5%" class="sorting" tabindex="0"
												aria-controls="data-grid" rowspan="1" colspan="1"
												aria-label="酒店: activate to sort column ascending">酒店</th>
											<th width="10%" class="sorting" tabindex="0"
												aria-controls="data-grid" rowspan="1" colspan="1"
												aria-label="关键字: activate to sort column ascending">关键字</th>
											<th width="10%" class="sorting" tabindex="0"
												aria-controls="data-grid" rowspan="1" colspan="1"
												aria-label="简介: activate to sort column ascending">简介</th>
											<th width="10%" class="sorting" tabindex="0"
												aria-controls="data-grid" rowspan="1" colspan="1"
												aria-label="操作: activate to sort column ascending">操作</th>
										</tr>
									</thead>

									<tfoot></tfoot>
									<tbody><?php foreach ($res as $item):?>
                    	<tr>
											<td><?=$item->id?></td>
											<td><img src="<?=$item->url?>" style="width: 6em;" /></td>
											<td><?=$item->name?></td>
											<td><?=$item->keyword?></td>
											<td><?=$item->intro?></td>
											<td><a class="btn btn-default" href="javascript:;" rid="<?=$item->id?>" name="edit">编辑</a></td>
										</tr><?php endforeach;?>
                    </tbody>
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
									<div class="col-sm-7">
										<div class="dataTables_paginate paging_simple_numbers"
											id="data-grid_paginate">
											<ul class="pagination"><?php echo $pagination?></ul>
										</div>
									</div>
								</div>
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->
<?php
	/* Footer Block @see footer.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'footer.php';
?>

<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php';
?>

</div>
	<!-- ./wrapper -->

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
	$("a[name=edit]").click(function(){
		var id = $(this).attr('rid');
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
<!--        $.post("--><?php //echo site_url('publics/qrcodes/output_qrcode')?><!--",{-->
<!--            'data':'--><?php //echo json_encode($res);?><!--',-->
<!--            '--><?php //echo $this->security->get_csrf_token_name();?><!--':'--><?php //echo $this->security->get_csrf_hash();?><!--'-->
<!--        },function(data){-->
<!--            if(data.errmsg == 'ok'){-->
<!--                alert('保存成功');-->
<!--                window.location.reload();-->
<!--            }else{-->
<!--                alert('二维码信息保存失败');-->
<!--            }-->
<!--        },'json');-->
        if($('#output_begin').val()=='' || $('#output_end').val()==''){

            alert('请输入要导出的二维码的ID区间');return;

        }

        if(parseInt($('#output_begin').val())  > parseInt($('#output_end').val())){

            alert('请输入正确的ID区间');return;

        }

        var begin = $('#output_begin').val();
        var end = $('#output_end').val();

<!--        --><?php //$_SESSION['res']=$res;?>
//        location.href="./output_qrcode?b="+begin+"&e="+end;
        location.href="<?php echo site_url('publics/qrcodes/output_qrcode')?>"+"?b="+begin+"&e="+end
    });
});
<?php 
// $sort_index= $model->field_index_in_grid($default_sort['field']);
// $sort_direct= $default_sort['sort'];

?>


<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//'http://iwide.cn/',
];


$(document).ready(function() {
	$('#myModal').on('hidden.bs.modal', function (e) {
		$('#key').val('');
		$('#hotel').val('');
		$('#intro').val('');
		$('#keyword').val('');
	})
});
</script>
</body>
</html>
