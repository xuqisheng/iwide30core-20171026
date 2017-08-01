<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<style>
	.layer{position:fixed; top:0; left:0;overflow:hidden; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; display:none}
	.add_hotel_content{background:#f8f8f8; padding:15px; height:100%; float:right}
	.child_dom{ padding:2px 5px; margin:3px;background:#fff; border:1px solid #3c8dbc; display:inline-block;}
</style>
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
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        查看全额兑换的使用规则
      </h1>
      <ol class="breadcrumb">
      </ol>
    </section>

<input type='hidden' name='<?php echo $csrf_token;?>' value='<?php echo $csrf_value;?>' />
<input type='hidden' name='rule_id' value='<?php echo $list['rule_id'];?>' />
	<section class="content">
    <style>
    .li_con{margin:2% 0;}
	.li_con >span:nth-of-type(1){display:inline-block;min-width:80px;text-align:right;}
	.li_con >span:nth-of-type(2){padding-left:10px;}
    </style>
      <div class="row r_cont" style="padding:2% 3%;">
      	<div class="li_con">
        	<span>规则名称：</span>
        	<span><?php echo $list['rule_name'];?></span>
        </div>
        <div class="li_con">
        	<span>酒店房型：</span>
			<?php if (empty($list['hotel_rooms'])){?>
            <div class="box-body" style="display:inline-block;padding-top:0px;padding-bottom:0px;" ><h5>全部门店和房型</h5></div>
            <?php }else{?>
            <div class="box-body show_hotel_btn" style="display:inline-block;padding-top:0px;padding-bottom:0px;">
                    <div class="btn btn-default btn-xs"><i class="fa fa-eye"></i> 查看指定的适用门店<font style="text-decoration:underline;color:#0000FF;">查看明细</font></div>   
            </div>     
            <div class="box-body show_hotel" style="display:none">
                <div class="col-xs-6">
                    <div class="btn btn-default btn-xs close_hotel_btn"><i class="fa fa-angle-up"></i> 收起</div>
                </div>
                <div class="pulltips" style="display:none"><i class="fa fa-spinner"></i> 正在加载...</div>   
                <!-- /.table-body --> 
                <table id="coupons_table" class="table table-bordered table-striped">
                </table>
              <!-- /.table-body end -->
            </div>
            <?php }?>
        </div>
      	<div class="li_con">
        	<span>积分规则：</span>
        	<span><?php if ($list['ex_way']==1){?> <?php echo $list['ex_value'] ?> 积分兑换 1 元<?php }else if ($list['ex_way']==2){?>固定 <?php echo $list['ex_value'];?> <?php }?></span>
        </div>
      	<div class="li_con">
        	<span>执行日期：</span>
        	<span><?php if (!empty($list['start_time']))echo date('Y-m-d',$list['start_time']);else echo '-';?> ~ <?php if (!empty($list['end_time']))echo date('Y-m-d',$list['end_time']);else echo '-';?></span>
        </div>
      	<div class="li_con">
        	<span>优先级：</span>
        	<span><?php echo $list['priority'];?></span>
        </div>
      	<div class="li_con">
        	<span>状态：</span>
        	<span><?php if ($list['status']==1){?>激活<?php }else{?>不激活<?php }?></span>
        </div>
      </div>
      <h3>
        <b>操作记录</b>
      </h3>
      <table id="coupons_table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>序号</th> 
                <th>帐号</th> 
                <th>操作描述</th> 
                <th>IP地址</th> 
                <th>时间</th> 
             </tr>
        </thead>
        <?php if (!empty($logs)){?>
        <tbody>
        <?php foreach ($logs as $l){?>
            <tr>
                <td><?php echo $l['log_id'];?></td>
                <td><?php echo $l['admin_name'];?></td>
                <td><?php echo $l['log_des'];?></td>
                <td><?php echo $l['ip'];?></td>
                <td><?php echo $l['record_time'];?></td>
            </tr>
            <?php }?>
        </tbody>
        <?php }?>
      </table>
      <!-- /.row -->
      </section>
 </div>
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
</body>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
var isfirst=true;
var isdown =false;
function set_table(){
	$('#coupons_table').DataTable({
		  "paging": true,
		  "lengthChange": true,
		  "searching": true,
		  "ordering": true,
		  "info": false,
		  "autoWidth": false,
		  "oLanguage":{
			  "oPaginate":{ "sFirst": "页首","sPrevious": "上一页","sNext": "下一页","sLast": "页尾"},
			  "sLengthMenu": "每页显示 _MENU_ 条数据",
			  "sEmptyTable": "暂无相关数据",
		  },
	});
}
$(function(){
// 	set_table();//表格初始化;
	$('.show_hotel_btn').click(function(){
		if(isdown) return;
		if(isfirst){
			isdown=true;
			$('.pulltips').show();
			$.get('<?php echo site_url('hotel/bonus/ajax_ur_hotel_rooms')?>',{
				rid:'<?php echo $list['rule_id']?>'
				},function(data){
					if(data.status==1){
						$('#coupons_table').html('<thead><tr><th>酒店</th><th>房型</th></tr></thead>');
						s='';
						$.each(data.data.hotel_rooms,function(i,n){
							if(n.check!=undefined&&n.check==1){
								s+='<tr><td>'+n.name+'</td>';
								s+='<td>';
								if(n.rooms!=undefined&&n.rooms!=[]){
									$.each(n.rooms,function(ri,rn){
										if(rn.check!=undefined&&rn.check==1){
											s+='<div class="parent_dom"><div class="checkbox roomcheck">'+rn.name+'</div>';
											if(rn.codes!=undefined&&rn.codes!=[]){
												$.each(rn.codes,function(rci,rcn){
													if(rcn.check!=undefined&&rcn.check==1){
														s+='<div class="child_dom">'+rcn.name+'</div> '
													}
												});
											}
											s+='</div>';
										}
									});
								}
								s+='</td></tr>';
							}
						});
						$('#coupons_table').append(s);
						isfirst=false;
						isdown=false;
						$('.show_hotel_btn').hide();
						$('.show_hotel').show();
						set_table();
						$('.pulltips').hide();
					}else{
						$('.pulltips').html(data.message).css('color','#F00');
					}
				},'json');	
		}else{
			$('.show_hotel_btn').hide();
			$('.show_hotel').show();
		}
	})
	$('.close_hotel_btn').click(function(){
		$('.show_hotel').hide();
		$('.show_hotel_btn').show();
	})
});
</script>
</html>
