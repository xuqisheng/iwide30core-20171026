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
	"rowCallback": function(row, data ) {
		if ( $.inArray(data.DT_RowId, selected) !== -1 ) {
			$(row).addClass('bg-gray');
		}
	},
	"columns": columnSet,
	"data": dataSet,
	"searching": true,
	"columnDefs": [ {
            "targets": -1,
            "data": null,
            "defaultContent": "<a class=\"btn btn-default\" href=\"#\" name=\"edit\">编辑</a>"
        } ],
     "createdRow": function ( row, data, index ) {
         var a = $('td:last>a', row);
         a.attr('href',"javascript:doedit(" + data[0] + ");");
     }
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
	$( 'input', this.footer() ).on( 'keyup change', function () {
		if ( that.search() !== this.value ) {
			that
				.search( this.value )
				.draw();
		}
	} );
} );

$("#data-grid_length").children().append('&nbsp;&nbsp;&nbsp;').append( buttons );

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
