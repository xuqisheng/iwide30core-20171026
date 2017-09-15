<!-- DataTables -->
<link rel="stylesheet"
  href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/huang.css">
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/date-timepicker/jquery.datetimepicker.css'>
<script type="text/javascript" src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/date-timepicker/jquery.datetimepicker.min.js"></script>
<script type="text/javascript">
    //全局变量
    var GV = {
        DIMAUB: "<?php echo base_url();?>",
        JS_ROOT: "<?php echo FD_PUBLIC;?>/js/",
        JS__ROOT:"<?php echo base_url(FD_PUBLIC);?>/js/"
    };
</script>
<script src="<?php echo base_url(FD_PUBLIC);?>/js/wind.js"></script>
<style type="text/css">
    html, body{min-width: 100%;}
    div.dataTables_filter label{text-align:center;width: 50%;}
    .search-wd {  width: 80% !important;  }
    .expot{width: 100%;margin-bottom: 10px;}
    .expot span{display: inline-block;}
    .expot input{width:30%;display: inline-block;}
    table.table-bordered th:last-child, table.table-bordered td:last-child{text-align: center;vertical-align: middle;}
    table.table-bordered td:last-child>.btn-sm{padding:2px 6px;margin: 0 2px;}
    #expot{ display: none;}
    #selects_membeb{display:inline-block;width:auto;vertical-align:middle;margin-right:25px;}
    .table-striped>tbody>tr:nth-of-type(odd) {  background-color: #ffffff;  }
    .color_F99E12 {  margin: 0 5px;  }
    #data-grid_wrapper >.row:first-child{background:#fff;padding:10px;}
    table.dataTable th{background: #f8f9fb;}
    .h_btn_list{display: inline-block}
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
        <style>
            .buttons div{
                height:30px;line-height: 30px;
            }
        </style>
        <div style="border-bottom: 1px solid #000;width:100%;height:300px;" class="buttons">
            <div id="summaryAdd">储值数据统计:增加</div>
            <div id="summaryUse" >储值数据统计:减少</div>
            <div id="summaryAddExport">储值数据统计:导出（增加）</div>
            <div id="summaryUseExport">储值数据统计:导出（减少）</div>
        </div>

         <div class="data-show" id="Results">

         </div>

</body>

<script>
    $('#summaryAdd').on("click",function(){
        $.ajax({
            url:"/index.php/iapi/v1/membervip/analysis/balance_analysis",
            data:{

            },
            dataType: "JSON",
            success:function(e){
                var data = e.webdata;
                $('#Results').html(data.admin_add);
            }
        });
    });


</script>
</html>
