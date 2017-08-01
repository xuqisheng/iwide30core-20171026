<!-- DataTables -->
<link rel="stylesheet"
	href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
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
			<section class="content-header">
				<h1>
					<small></small>
				</h1>
				<ol class="breadcrumb"></ol>
			</section>
			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box">
							<div class="box-body">
								<table id="data-grid"
									class="table table-bordered table-striped table-condensed dataTable">
									<thead>
										<tr>
											<th>模块名</th>
											<th>支付方式</th>
										</tr>
									</thead>
                    <?php if(!empty($list)){ foreach($list as $lk=>$lt){ ?>
                    <tr><td><?php echo $lt['module_name'];?></td>
						<td><?php if(!empty($lt['pay_ways'])){ foreach($lt['pay_ways'] as $k=>$pw) {?>
             	<div><input type='checkbox' name='way' value='<?php echo $k;?>'
				<?php if($pw['status']==1) {?> checked='checked' <?php }?> /><?php echo $pw['des']; ?>
				显示的名字：<input type='text' name='pay_name' value="<?php if(!empty($pw['pay_name'])) { echo $pw['pay_name']; } ?>" placeholder='显示的名字' />
				显示的顺序：<input type='text' name='sort' value="<?php if(!empty($pw['sort'])) { echo $pw['sort']; } ?>" placeholder='显示的顺序,越大越前'/>
				<?php if($pw['pay_type']=='weixin') {?>
				订单超时时间(分钟)：<input type="text" name="outtime" value="<?php if(!empty($pw['outtime'])) { echo $pw['outtime']; } ?>" placeholder='默认15分钟'  onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"/>
				<?php } ?>
				<br /></div><?php }}?></td>
				<td><input type="button" onclick='save_para(this,"<?php echo $lk;?>")' value='保存' class="btn btn-default" /></td>
					</tr>
                    <?php }}?>
                                    <tr><td></td><td></td><td></td></tr>
                     <?php if(!empty($okpay_list)){?>
                                <tr>
                                    <td>快乐付支付配置</td>
                                    <td>选择(默认是微信支付)</td>
                                    <td></td>
                                </tr>
                         <tr>
                             <td></td>
                             <td>
                                 <select name="okpay_type" id="okpay_type">
                                 <?php foreach($okpay_list as $ok=>$ov){?>
                                        <option value="<?php echo $ov['pay_type']?>" <?php if($okpay==$ov['pay_type']){?> selected='selectec'<?php }?>><?php echo $ov['des']?></option>
                                 <?php }?>
                                 </select>
                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                 <input type="button" onclick='save_okpay_para($("#okpay_type").val().trim())' value='点击保存' class="btn btn-default" />
                             </td>
                             <td></td>
                         </tr>
                            <??>
                     <?php }?>
                  </table>
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->

		</div>
		<!-- /.content-wrapper -->
<?php
/* Footer Block @see footer.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'footer.php';
?>

<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'right.php';
?>

</div>
	<!-- ./wrapper -->

<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'commonjs.php';
?>
</body>
<script>
var data={};
var token_name='<?php echo $csrf_token;?>';
var token_value='<?php echo $csrf_value;?>';
function save_para(obj,module){
	ranges=$(obj).parent().parent().find('div');
	var payway='';
	var p_o='';
	$.each(ranges,function(i,n){
		p_o=$(n).find('input[name="way"]');
		pay_type=p_o.val();
		var status=1;
		data[pay_type]={};
		if(p_o.is(":checked")==false)
			status=0;
		data[pay_type]['status']=status;
		data[pay_type]['pay_name']=$(n).find('input[name="pay_name"]').val();
		data[pay_type]['sort']=$(n).find('input[name="sort"]').val();
	});
	data['outtime'] = $('input[name="outtime"]').val();
	if(data['outtime']!='' && (data['outtime']<6 || data['outtime']>30)){
		alert('超时时间只能填6-30分钟！');
		return false;
	}
	json=JSON.stringify(data);
	post_data={};
	post_data[token_name]=token_value;
	post_data['datas']=json;
	post_data['module']=module;
	$.post('<?php echo site_url('pay/pay/save_config')?>',post_data,function(data){
		if(data==1){
			alert('修改成功');
			location.reload();
		}
		else{
			alert('修改失败');
		}
	});
}
    function save_okpay_para(val){
        $.post('<?php echo site_url('pay/pay/save_okpay_config')?>',
            {'okpay_type':val, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
            function(data){
                if(data==1){
                    alert('修改成功');
                    location.reload();
                }
                else{
                    alert('修改失败');
                }
        });
    }
</script>
</html>
