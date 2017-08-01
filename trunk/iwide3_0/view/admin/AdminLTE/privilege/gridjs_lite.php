var selected= [<?php if( isset($tr_slt) ) echo $tr_slt; ?>];
$("#data-grid").DataTable({
	"aLengthMenu": [10],
	"iDisplayLength": 10,
	"bProcessing": true,
	"paging": true,
	"lengthChange": true,
	"ordering": true,
	"order": grid_sort,
	"info": true,
	"autoWidth": false,
	"language": {
		"sSearch": "搜索",
		"lengthMenu": "每页_MENU_条记录",
		"zeroRecords": "找不到任何记录. ",
		"info": "第_PAGE_/_PAGES_页，从_START_到_END_ ，共_TOTAL_条",
		"infoEmpty": "",
		"infoFiltered": "(原_MAX_条)",
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
	"data": dataSet,
	"searching": true
});
 
$('#data-grid tbody').on('click', 'tr', function(){
    <?php if( isset($click_event ) ): //js事件全部置换   ?>
        <?php echo $click_event;  ?>
    <?php else: ?>
        <?php if(isset($click_event_before)) echo $click_event_before; //点击事件前置js   ?>

        <?php if(isset($grid_single) && $grid_single===TRUE): //表格单选  ?>

        	selected=[this.id];
        	$(this).toggleClass('bg-gray');
        <?php else: //表格可以多选  ?>

        	var id = this.id;
        	var index = $.inArray(id, selected);
        	if ( index===-1 ) selected.push( id );
        	else selected.splice( index, 1 );

        	$(this).toggleClass('bg-gray');
        <?php endif; ?>

        <?php if(isset($click_event_after)) echo $click_event_after; //点击事件后置js  ?>
    <?php endif; ?>
});
  