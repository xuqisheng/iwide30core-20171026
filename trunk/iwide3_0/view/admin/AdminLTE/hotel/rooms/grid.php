<!-- DataTables -->
<link rel="stylesheet"
	href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->

<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/new.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<style>
.input_txt{height: 80px;}
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="modal fade" id="setModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">显示设置</h4>
      </div>
      <div class="modal-body">
        <div id='cfg_items'>
        <?php echo form_open('distribute/distri_report/save_cofigs','id="setting_form"')?>
        	
        </form></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" id="set_btn_save">保存</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="wrapper">

<?php
/* 顶部导航 */
echo $block_top;
?>

<?php
/* 左栏菜单 */
echo $block_left;
?>

<div style="color:#92a0ae;">
    <div class="over_x">
        <div class="content-wrapper" style="min-width:1130px;" >
            <div class="banner bg_fff p_0_20">
                <?php echo $breadcrumb_html; ?>
            </div>
            <div class="contents">
            	<div class="hottel_name ">
					<div class="input_txt">
						<select id="hotel" onchange="h_jump($(this).val())" name="hotel" class="w_450">
							<option value="">全部</option>
							<?php foreach ($hotels as $hotel):?>
						   		<option value="<?=$hotel['hotel_id']?>"<?php if($hotel_id == $hotel['hotel_id']):?> selected<?php endif;?>><?=$hotel['name']?></option>
							<?php endforeach;?>
						</select>
					   <?php if(count($hotels)>10){?>
					    <div style='margin-top: 5px;'>
					   	<input type="text" name="qhs" id="qhs" placeholder="快捷查询">
						  	<input type="button" onclick='quick_search()' value='查询' />
						  	<input type="button" onclick='go_hotel("next")' value='下一个' />
						  	<input type="button" onclick='go_hotel("prev")' value='上一个' />
						  	<input type="button" onclick='h_jump(0)' value='确定搜索' />
						  	<span id='search_tip' style='color:red'></span>
					   </div>
					   <?php }?>
					</div>
				</div>
				<div class="contents_list" style="font-size:13px;">
					<a class="f_r all_open_order color_72afd2" href="/index.php/hotel/rooms/add<?php if (!empty($hotel_id))echo '?h='.$hotel_id;?>">新增房型</a>
					<div class="classification display_flex bg_fff">
						<div class="add_active" value="在线">在线房</div>
						<div value="下线">下线房</div>
						<div value="线">所有房</div>
					</div>
				</div>
				<div class="box-body">
					<table id="coupons_table" class="table-striped table-condensed dataTable no-footer" style="width:100%;">
						<thead class="bg_f8f9fb form_thead">
							<tr class="bg_f8f9fb form_title">
								<th>房型ID</th>
								<th>房型名称</th>
								<th>房量</th>
								<th>床数</th>
								<th>面积</th>
								<th>所属酒店</th>
								<th>排序</th>
								<th>状态</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody class="containers dataTables_wrapper form-inline dt-bootstrap">
							<?php if(!empty($model->data_array)){?>
								<?php $status = array('可用'=>'在线','不可用'=>'下线');?>
				                <?php foreach ($model->data_array as $r){?>
								<tr class=" form_con">
									<td><?php echo $r['room_id'];?></td>
									<td><?php echo $r['name'];?></td>
									<td><?php echo $r['nums'];?></td>
									<td><?php echo $r['bed_num'];?></td>
									<td><?php echo $r['area'];?></td>
									<td><?php echo $r['hotel_id'];?></td>
									<td><?php echo $r['sort'];?></td>
									<td><?php echo $status[$r['status']];?></td>
									<td><a class="color_72afd2" href="/index.php/hotel/rooms/edit?ids=<?php echo $r['room_id'];?>">编辑</a></td>
								</tr>
				                <?php }?>
					        <?php }?>
						</tbody>
					</table>
				</div>
			</div>
        </div>
    </div>

</div>
     
<?php
/* Footer Block @see footer.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'footer.php';
?>

<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'right.php';
?>



<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'commonjs.php';
?>

<script>
$(function(){
	$('#coupons_table').DataTable({
		    "aLengthMenu": [8,50,100,200],
			"iDisplayLength": 20,
			"bProcessing": true,
			"paging": true,
			"lengthChange": true,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"searching": false,
			"language": {
				"sSearch": "搜索",
				"lengthMenu": "每页显示 _MENU_ 条记录",
				"zeroRecords": "找不到任何记录. ",
				"info": "",
				//"info": "当前显示第_PAGE_ / _PAGES_页，记录从 _START_ 到 _END_ ，共 _TOTAL_ 条",
				"infoEmpty": "",
				"infoFiltered": "(从 _MAX_ 条记录中过滤)",
				"paginate": {
					"sNext": "下一页",
					"sPrevious": "上一页",
				}
			},
			"searching": true
	});
	var table = $('#coupons_table').DataTable();
	$('.classification >div').click(function(){
		$(this).addClass('add_active').siblings().removeClass('add_active');
		table
	        .columns( 7 )
	        .search( $(this).attr('value') )
	        .draw();
	})
	table
        .columns( 7 )
        .search( $('.add_active').attr('value') )
        .draw();
})


var search_index=0;
var hid='';
function quick_search() {
	var hk=$('#qhs').val();
	if(hk){
		$('#search_tip').html('');
		options=$('#hotel option');
		search_index=0;
		$.each(options,function(i,n){
			$(n).css('color','#555');
			$(n).removeAttr('be_search');
			if(n.innerHTML.indexOf(hk)>-1){
				search_index++;
				$(n).css('color','red');
				$(n).attr('be_search',search_index);
				if(search_index==1){
					n.selected=true;
					hid=n.value;
				}
			}
		});
		if(search_index==0){
			$('#search_tip').html('无结果');
		}
	}
}; 
function go_hotel(direction){
	selected_option=$('#hotel').find('option:selected');
	selected_option=selected_option[0];
	now_index=$(selected_option).attr('be_search');
	if(now_index){
		search_index=now_index;
	}
	if(search_index){
		if(direction=='next'){
			search_index++;
		}else{
			search_index--;
		}
	}
	if(search_index){
		option=$('#hotel>option[be_search="'+search_index+'"]');
		if(option[0]!=undefined){
			option=option[0];
			option.selected=true;
			hid=option.value;
		}
	}
}

function h_jump(h){
	if(h==0){
		h=hid;
	}
	location.href="?h="+h;
}
</script>
</body>
</html>
