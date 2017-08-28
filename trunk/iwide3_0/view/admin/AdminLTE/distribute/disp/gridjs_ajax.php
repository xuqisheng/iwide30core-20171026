var selected= [];
var table = $("#data-grid").DataTable({
	"aLengthMenu": [20,50,100,200],
	"iDisplayLength": 20,
	"bProcessing": true,
	"paging": true,
	"lengthChange": true,
	"ordering": true,
	"order": grid_sort,
	"info": true,
	"autoWidth": false,
	"language": {
		"sSearch": "搜索",
		"lengthMenu": "每页显示 _MENU_ 条记录",
		"zeroRecords": "找不到任何记录. ",
		"info": "当前显示第_PAGE_ / _PAGES_页，记录从 _START_ 到 _END_ ，共 _TOTAL_ 条",
		"infoEmpty": "",
		"infoFiltered": "(从 _MAX_ 条记录中过滤)",
		"paginate": {
			"sNext": "下一页",
			"sPrevious": "上一页",
		}
	},
	"processing": true,
	"serverSide": true,
	"ajax": {
		"type": 'POST',
		"url": url_ajax,
		"data": {<?php echo config_item('csrf_token_name') ?>: '<?php echo $this->security->get_csrf_hash() ?>' }
	},
	"rowCallback": function(row, data ) {
		if ( $.inArray(data.DT_RowId, selected) !== -1 ) {
			$(row).addClass('bg-gray');
		}
	},
	"columns": columnSet,
	//"data": dataSet,
	"searching": true
});

// Setup - add a text input to each footer cell
$('#data-grid tfoot th').each( function () {
	var title = $(this).text();
	if(title) $(this).html( '<input type="text" placeholder="'+title+'" class="form-control input-sm" size="8"/>' );
	else $(this).html( '-' );
} );

// Apply the search
table.columns().every( function () {
	var that = this;
	$( 'input', this.footer() ).on( 'change', function (k) {
		if ( that.search() !== this.value ) {
			var val = $.fn.dataTable.util.escapeRegex( $(this).val() );
			/** This search function will disabled while global paramster "searching"=false **/
			that.search( val, true ).draw();
			//that.search( val ? '^'+val+'$' : '', true, false ).draw();
		}
	} );
} );

//hidden global search 
$('#data-grid_filter input').each(function(){
    $(this).val('关键词在表格底部栏输入').prop('disabled', 'disabled');
});

$("#data-grid_length").children().append('&nbsp;&nbsp;&nbsp;').append( buttons );

$('#data-grid tbody').on('click', 'tr', function(){
	var id = this.id;
	var index = $.inArray(id, selected);
	if ( index===-1 ) selected.push( id );
	else selected.splice( index, 1 );

	$(this).toggleClass('bg-gray');
	if(selected.length==1){
		$('#grid-btn-edit').removeClass('disabled').bind('click', selected, function(ev){
			window.location= url_edit+ '?ids='+ ev.data;
		});
		$('#grid-btn-del').addClass('disabled').unbind();	//先清除原有绑定事件
		$('#grid-btn-del').removeClass('disabled').bind('click', selected, function(ev){
			if(confirm('您确定要删除这些数据吗？') ){
				window.location= url_delete+ '?ids='+ ev.data;
			}
		});
		$('#grid-btn-edit').addClass('bg-green');
		$('#grid-btn-del').addClass('bg-red');
		$('button.single-btn').each(function(i,e){$(this).removeClass('disabled').addClass('bg-green');});
		$('#grid-btn-del').attr('title', '批量').attr('data-placement','top').tooltip('show');
		
	} else if(selected.length>0){
		$('#grid-btn-edit').addClass('disabled').removeClass('bg-green').unbind();
		$('button.single-btn').each(function(i,e){$(this).addClass('disabled').removeClass('bg-green');});
		//删除事件只需绑定一次，否则删除按钮会多次弹出
	} else {
		$('#grid-btn-edit').addClass('disabled').removeClass('bg-green').unbind();
		$('#grid-btn-del').addClass('disabled').removeClass('bg-red').unbind();
		$('#grid-btn-del').tooltip('hide');
		$('button.single-btn').each(function(i,e){$(this).addClass('disabled').removeClass('bg-green');});
	}
});

$('#grid-btn-add').bind('click', selected, function(ev){
	window.location= url_add;
});

//为所有的按钮赋予点击事件,点击过程不解除捆绑
$(url_extra).each(function(i,e){
	$('#grid-btn-extra-'+i).bind('click', selected, function(ev){
		var t= $(this).attr('target');
		var u= e+ '?ids='+ selected;
		if( $(this).hasClass('single-btn') ){
			if( selected.length>0 && t.length>0 ) window.open(u,t);
			else if( selected.length>0 ) window.location= u;
			else alert('请至少选择一个项目');
		} else {
			if( t && t.length>0 ) window.open(u,t);
			else window.location= u;
		}
	});
});

<?php if( isset($fields_config) && count($fields_config) > config_item('grid_wide_columns') ): ?>
$("body").addClass('sidebar-collapse').trigger('collapsed.pushMenu');
<?php endif; ?>
