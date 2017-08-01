<script type="text/javascript">
var selected= [];
$(document).ready(function() {
    $("#data-grid").DataTable({
        "aLengthMenu": [20,30,50,100,200],
        "iDisplayLength": 20,
        "bProcessing": true,
        "paging": true,
        "lengthChange": true,
        "ordering": true,
        "order": grid_sort,
        "info": true,
        "autoWidth": false,
        "language": {
            "sSearch": "快速搜索",
            "lengthMenu": "每页显示 _MENU_ 条记录",
            "zeroRecords": "找不到任何记录. ",
            "info": "当前显示第_PAGE_ / _PAGES_页，记录从 _START_ 到 _END_ ，共 _TOTAL_ 条",
            "infoEmpty": "",
            "infoFiltered": "(从 _MAX_ 条记录中过滤)",
            "paginate": {
                "sNext": "下一页",
                "sPrevious": "上一页",
            },
            "sSearchPlaceholder":'请输入关键字...',
            "sProcessing":'加载中...',
            "sLoadingRecords":'正在加载...',
            "sZeroRecords" : "没有您要搜索的内容",
        },
        "oClasses":{
            "sFilterInput":'form-control input-sm search-wd'
        },
        "processing": true,
        "rowCallback": function(row, data ) {
            if ( $.inArray(data.DT_RowId, selected) !== -1 ) {
                $(row).addClass('bg-gray');
            }
        },
        "columns": columnSet,
        "data": dataSet,
        "searching": true
    });

    $("#data-grid_length").children().append('&nbsp;&nbsp;&nbsp;').append( buttons );

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
});
</script>
