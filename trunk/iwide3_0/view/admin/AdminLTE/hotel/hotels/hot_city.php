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
<section class="content-header"><h1>热门城市</h1></section>
<!-- Main content -->
<section class="content">
<div class="row">

    <div class="col-xs-12">
        <div class="box box-solid">
            <div class="box-header with-border"><i class="fa fa-pencil-square-o"></i><h3 class="box-title">编辑热门城市</h3></div>
            <div class="box-body">
                <div class="form-group col-xs-6">
                    <label>默认城市（可以为空）</label>
                    <div class="col-sm-5 input-group">
                        <input type="text" class="form-control" id="default_city" value="<?php echo $d_city?>">
                    </div>
                </div>

                <div class="form-group col-xs-6">
                    <label>热门城市数量（空的上限为3个）</label>
                    <div class="col-sm-5 input-group">
                        <input type="text" class="form-control" id="hot_city_amount" value="<?php echo $amount?>">
                    </div>
                </div>

                <div class="form-group col-xs-6">
                  <label>添加城市</label>
                  <div class="col-sm-10 input-group">
                    <input type="text" class="form-control" id="city_name">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-info btn-flat" id="addbtn">添加</button>
                    </span>
                  </div>
                </div>
                <div class="form-group col-xs-12">
                	<div class="col-xs-4" style="padding:0">
                        <label>当前热门城市</label>
                        <table class="table table-bordered table-striped" id="city_list" style="text-align:center">
                           <?php if(isset($hot_city) && !empty($hot_city)){foreach($hot_city as $arr){ ?>
                            <tr>
                                <td city_name><?php echo $arr;?></td>
                                <td><button type="button" " class="btn btn-danger btn-xs">删除</button></td>
                            </tr>
                           <?php }}?>
                        </table>
                    </div>
               </div>

                <div class="form-group col-xs-2">
                    <button type="button" id='save' class="btn btn-block btn-info">保存</button>
                </div>
            </div>
        </div>
    </div>

</div><!-- /.row -->
</section><!-- /.content -->

</div><!-- /.content-wrapper -->
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
<script>
$('#addbtn').click(function(){
	var html = '';
	var val  = $('#city_name').val();
	if(val!=''){
        html +='<tr><td city_name>'+val+'</td><td><button type="button" class="btn btn-danger btn-xs">删除</button></td></tr>';
        $('#city_list').append(html);
    }

})
$('.table').on('click','.btn',function(){
    $(this).parents('tr').remove();
});

$('#save').click(function(){
	var city=[];
    $('#city_list').find('[city_name]').each(function(index, element) {
        city[index]=$(this).text();
    });

    $.get('<?php echo site_url('hotel/hotels/hot_city_post')?>',{
        city:city,
        default_city:$('#default_city').val(),
        amount:$('#hot_city_amount').val()
    },function(data){
        if(data.status==1){
            alert(data.msg);
        }else{
            alert(data.msg);
        }
    },'json');

})
</script>
</body>
</html>
