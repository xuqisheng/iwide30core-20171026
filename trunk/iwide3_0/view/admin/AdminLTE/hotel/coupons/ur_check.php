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
        查看用券规则
        <small>Using Rules</small>
      </h1>
      <ol class="breadcrumb">
      </ol>
    </section>

<input type='hidden' name='<?php echo $csrf_token;?>' value='<?php echo $csrf_value;?>' />
<input type='hidden' name='rule_id' value='<?php echo $list['rule_id'];?>' />
	<section class="content">
      <div class="row">
        <div class="col-xs-6">
          <div class="box box-solid">
            <div class="box-header with-border"><i class="fa fa-database"></i><h3 class="box-title">卡券类型</h3></div>
            <div class="box-body"><h5><?php if ($list['rule_type']=='voucher') echo '代金券：';
                    else if ($list['rule_type']=='discount') echo '折扣券：';
                    else if ($list['rule_type']=='exchange') echo '礼品券：';
                    ?>
                    <?php if (!empty($coupon_types)){foreach ($coupon_types as $c){?>
                  	<?php if (!empty($list['coupon_ids'])&&in_array($c['card_id'], $list['coupon_ids'])){?> <?php echo $c['title'];?><?php }?>
                   <?php }}?>
                    </h5></div>
          </div>
        </div>
        <div class="col-xs-6">
          <div class="box box-solid">
            <div class="box-header with-border"><i class="fa fa-cube"></i><h3 class="box-title">规则名称</h3></div>
            <div class="box-body"><h5><?php echo $list['rule_name'];?></h5></div>
          </div>
        </div>
        <!--div class="col-xs-4">
          <div class="box box-solid">
            <div class="box-header with-border"><i class="fa fa-clone"></i><h3 class="box-title">其他规则</h3></div>
            <div class="box-body"><h5>无</h5></div>
          </div>
        </div--> 
        <div class="col-xs-6">
          <div class="box box-solid">
		<?php if (empty($list['rule_dates'])||$list['rule_dates']['r']==1){?>
            <div class="box-header with-border"><i class="fa fa-times"></i><h3 class="box-title">不可用日期</h3></div>
		<?php }else{?>
            <div class="box-header with-border"><i class="fa fa-calendar"></i><h3 class="box-title">使用日期</h3></div>
		<?php }?>
            <div class="box-body"><h5><?php if (!empty($list['rule_dates']['d']['r']['week'])){?>
					<?php if (in_array(1, $list['rule_dates']['d']['r']['week'])){echo ' 周一'; }?>
                    <?php if (in_array(2, $list['rule_dates']['d']['r']['week'])){echo ' 周二'; }?>
                    <?php if (in_array(3, $list['rule_dates']['d']['r']['week'])){echo ' 周三'; }?>
                    <?php if (in_array(4, $list['rule_dates']['d']['r']['week'])){echo ' 周四'; }?>
                    <?php if (in_array(5, $list['rule_dates']['d']['r']['week'])){echo ' 周五'; }?>
                    <?php if (in_array(6, $list['rule_dates']['d']['r']['week'])){echo ' 周六'; }?>
                    <?php if (in_array(0, $list['rule_dates']['d']['r']['week'])){echo ' 周日'; }?>
                    <?php }?>
                    <?php if (!empty($list['rule_dates']['d']['d'])){foreach ($list['rule_dates']['d']['d'] as $d){$tmp=explode('-', $d); echo date('Y.m.d',strtotime($tmp[0]));if (!empty($tmp[1])) echo '-'.date('Y.m.d',strtotime($tmp[1]));echo ' '; }}?>
                    <?php if (empty($list['rule_dates']['d']['r']['week'])&&empty($list['rule_dates']['d']['d'])){echo '无';}?></h5></div>
          </div>
        </div>
        <div class="col-xs-6">
          <div class="box box-solid">
            <div class="box-header with-border"><i class="fa fa-check-square"></i><h3 class="box-title">是否激活</h3></div>
            <div class="box-body"><h5><?php if ($list['status']==1){echo '已激活';}else{ echo '未激活'; }?></h5></div>
          </div>
        </div>        
        <div class="col-xs-6">
          <div class="box box-solid">
            <div class="box-header with-border"><i class="fa fa-bar-chart"></i><h3 class="box-title">会员等级</h3></div>
            <div class="box-body"><h5><?php if (empty($list['extra_rule']['level'])){?>全部会员等级
                  <?php }else{if (!empty($member_levels)){ foreach ($member_levels as $level=>$name){ if (!empty($list['extra_rule']['level'])&&in_array($level, $list['extra_rule']['level'])){ echo $name.'&nbsp;'; } }} }?></h5></div>
          </div>
        </div>
        <div class="col-xs-6">
          <div class="box box-solid">
            <div class="box-header with-border"><i class="fa fa-credit-card"></i><h3 class="box-title">支付方式</h3></div>
            <div class="box-body"><h5><?php if (empty($list['extra_rule']['paytype'])){?>全部支付方式
                  <?php }else{if (!empty($pay_ways)){foreach ($pay_ways as $k=>$p){if (!empty($list['extra_rule']['paytype'])&&in_array($p->pay_type, $list['extra_rule']['paytype'])){ echo $p->pay_name.'&nbsp;';} } } }?></h5></div>
          </div>
        </div>
        
        <div class="col-xs-12">
          <div class="box box-solid">
            <div class="box-header with-border">
              <i class="fa fa-bookmark"></i>
              <h3 class="box-title">使用门店</h3><label id='tips' style='color:red;'></label>
            </div>
            <?php if (empty($list['hotel_rooms'])){?>
            <div class="box-body"><h5>全部门店和房型</h5></div>
            <?php }else{?>
            <div class="box-body show_hotel_btn">
                <div class="col-xs-12" style="margin-bottom:15px;">
                    <div class="btn btn-default btn-xs"><i class="fa fa-eye"></i> 查看指定的适用门店</div>   
                </div>
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
          <!-- /.box -->
        </div>
        <!-- ./col -->
        
        <div class="col-xs-12">
          <div class="box box-solid">
            <div class="box-header with-border">
              <i class="fa fa-pencil-square-o"></i>
              <h3 class="box-title">操作记录</h3>
            </div>
            <!-- /.box-header -->
            <?php if(!empty($logs)){?>
            <div class="box-body">
               <ol type="1">
               	<?php foreach ($logs as $l){?>
                  <li class="text-red"><?php echo $l['log_des'];?></li>
                  <?php }?>
              </ol>
            </div>
            <?php }?>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- ./col -->
      </div>
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
			$.get('<?php echo site_url('hotel/coupons/ajax_ur_hotel_rooms')?>',{
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
