<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
    <![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/css/bookingpublic.css">
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
          
          <div>
            输入要更新的日期:<input name="saler_date" value="" placeholder="例如：201603"/>
            <button id="create">生成</button>
            <span id="tips"></span>
          </div>

          <!-- /.content -->
        </div>

        <!-- /.content-wrapper -->

        <?php
        /* Footer Block @see footer.php */
        require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php';
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

    <script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script>
    var deal_times =0;
    $(function () {
      var ajax_create = function(url){
        $.ajax({
            url: url,
            method: 'get',
            success: function(datas){
              res = $.parseJSON(datas);
              $('#tips').html(res.msg);
              if(res.status==2){
                ajax_create(res.url);
              }else{
                $('#create').attr("disabled", false);
              }
              return;
            }
        })
      }
      $('#create').on('click',function(){
        var saler_date = $('input[name="saler_date"]').val();
        url = '<?php echo site_url ( "hotel/hotel_report/create_saler_order_data" )."?saler_date=";?>'+saler_date+'&deal_times='+deal_times;
        $('#tips').html('初始化ing...,请稍等');
        $('#create').attr("disabled", "disabled");
        ajax_create(url);
      });
    })
    </script>
</body>

</html>