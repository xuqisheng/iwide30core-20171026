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
        查看规则
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
        	<span><?php if(isset($list['rule_name'])&&!empty($list['rule_name'])){ echo $list['rule_name'];}?></span>
        </div>
      	<div class="li_con">
        	<span>消费赠送积分：</span>
            <?php if(isset($give_rule)&&!empty($give_rule)){
                        if(isset($give_rule->consume->all)){   ?>
                            <span>全部会员  消费<?php echo $give_rule->consume->all->cost;?>元送<?php echo $give_rule->consume->all->amount;?></span><br>
                <?php  }else{
                            foreach($give_rule->consume as $key=>$arr){  ?>
                            <span><?php echo $levels[$key]?>  消费<?php echo $give_rule->consume->$key->cost;?>元送<?php echo $give_rule->consume->$key->amount;?></span><br>
                <?php       }
                        }
                }
             ?>
        </div>
        <div class="li_con">
          <span>评论赠送积分：</span>
          <?php if(isset($give_rule)&&!empty($give_rule)){
              if(isset($give_rule->comment->all)){   ?>
                  <span>全部会员  评论送<?php echo $give_rule->comment->all->amount;?></span><br>
              <?php  }else{
                  foreach($give_rule->comment as $key=>$arr){  ?>
                      <span><?php echo $levels[$key]?>  评论送<?php echo $give_rule->comment->$key->amount;?></span><br>
                  <?php       }
              }
          }
          ?>
        </div>
        <div class="li_con">
        	<span>酒店房型：</span>
			<?php if (empty($list['hotels_id'])){?>
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
        	<span>支付方式：</span>
        	<span><?php if(isset($paytype)&&!empty($paytype)){
                        foreach($paytype as $arr){
                            echo $pay_ways[$arr]->pay_name.'&nbsp&nbsp&nbsp';
                        }
                    }else{
                        echo "全部支付方式";
                    }?>
            </span>
        </div>

      	<div class="li_con">
        	<span>执行日期：</span>
        	<span><?php if(isset($list['valid_time'])&&!empty($list['valid_time'])){ echo $list['valid_time'];}?></span>
        </div>
      	<div class="li_con">
        	<span>优先级：</span>
        	<span><?php if(isset($list['priority'])&&!empty($list['priority'])){ echo $list['priority'];}?></span>
        </div>
      	<div class="li_con">
        	<span>状态：</span>
        	<span><span><?php if(isset($list['status'])&&$list['status']==1){ echo '激活';}else{ echo '未激活';}?></span></span>
        </div>
      </div>
      <h3>
        <b>操作记录</b>
      </h3>
      <table id="coupons_table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>序号</th> 
                <th>账号</th> 
                <th>操作描述</th> 
                <th>IP地址</th> 
                <th>时间</th> 
             </tr>
        </thead>
        <tbody>
            <?php if(isset($logs)){ foreach($logs as $arr){?>
            <tr>
                <td><?php echo $arr['log_id'];?></td>
                <td><?php echo $arr['admin']->nm;?></td>
                <td><?php echo $log_type[$arr['log_type']];?>了规则</td>
                <td><?php echo $arr['ip'];?></td>
                <td><?php echo $arr['record_time'];?></td>
            </tr>
            <?php }}?>
        </tbody>
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
			$.get('<?php echo site_url('hotel/Bonus/ajax_gr_hotel_rooms')?>',{
				rid:'<?php echo $list['bonus_grules_id']?>'
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
