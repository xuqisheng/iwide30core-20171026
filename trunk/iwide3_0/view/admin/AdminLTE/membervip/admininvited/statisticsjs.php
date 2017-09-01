<script type="text/javascript">
$(document).ready(function() {
    var selected= [];
    $("#data-grid").DataTable({
        "aLengthMenu": [20],
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
            "lengthMenu": "_MENU_",
            "zeroRecords": "找不到任何记录. ",
            "info": "当前显示第_PAGE_ / _PAGES_页，记录从 _START_ 到 _END_ ，共 _TOTAL_ 条",
            "infoEmpty": "",
            "infoFiltered": "(从 _MAX_ 条记录中过滤)",
            "paginate": {
                "sNext": "下一页",
                "sPrevious": "上一页",
            },
            "sSearchPlaceholder":'请输入姓名、昵称、卡号、电话等...',
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
        "searching": false
    });

    $(".col-sm-6").each(function (o,e) {
        if(o==0) {
            $(e).removeClass('col-sm-6');
            $(e).addClass('col-sm-3');
        }
        if(o==1) {
            $(e).removeClass('col-sm-6');
            $(e).addClass('col-sm-9');
        }
    });

    $('<div id="data-grid_filter" class="dataTables_filter"></div>').appendTo($('.col-sm-9'));

    $("#data-grid_length").children().append('&nbsp;&nbsp;&nbsp;').append( buttons );
    $("#data-grid_filter").prepend( inputs );
    $("#data-grid_filter").append( sinputs );
    $("#data-grid_filter").append( selects );
    $("#data-grid_filter").append( sbuttions );


    $('.date-export1').datetimepicker({
        format:'Y-m-d',
        lang:'ch',
        timepicker:false,
        scrollInput:false
    });
    $('.date-export2').datetimepicker({
        format:'Y-m-d',
        lang:'ch',
        timepicker:false,
        scrollInput:false
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
});
</script>
