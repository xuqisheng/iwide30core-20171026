<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-select/bootstrap-select.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-select/i18n/defaults-zh_CN.js"></script>
<style>
  .dialog{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 100000;
    display: none;
  }
  .dialog .dialog-box{
display: -webkit-box;
display: -webkit-flex;
display: flex;
-webkit-box-align: center;
-webkit-align-items: center;
        align-items: center;
-webkit-box-pack: center;
-webkit-justify-content: center;
        justify-content: center;
        height: 100%;
  }
  .dialog .cont{
    min-width: 300px;
    min-height: 150px;
    background: #fff;
    position: relative;
    max-width: 90%;
    max-height: 90%;
  }
  .dialog .dialog-close{
    position: absolute;
    top: 0;
    right: 10px;
    color: #fff;
    line-height: 28px;
    font-size: 20px;
  }
  .dialog .title{
    font-size: 14px;
    line-height: 2;
    background: #666;
    color: #fff;
    padding-left: 10px;
  }
  .dialog .body{
    padding: 40px 20px 20px;
  }
  .dialog .dialog-control{
    text-align: center;
    margin-top: 40px;
  }
  .dialog i{
    font-style: normal;
    color: rgb(255,12,12);
  }
  .dialog .input-label{
    padding-right: 5px;
  }
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

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo isset($breadcrumb_array['action'])? $breadcrumb_array['action']: ''; ?>
            <small></small>
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">

<?php echo $this->session->show_put_msg(); ?>
<?php $pk= $model->table_primary_key(); $item_pk= $model->item_table_primary_key(); ?>
<!-- Horizontal Form -->
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">查询到的信息</h3>
	</div>
	<!-- /.box-header -->
<!-- form start -->
	<?php 
	echo form_open( $post_url, array('class'=>'form-horizontal goForm'), array($pk=>$model->m_get($pk) ) ); ?>
		<div class="box-body">
            <!-- <?php echo $btn_search; ?> -->
			<!-- 核销清单 -->
			<div class=" col-sm-12 " >
				<table class="table table-striped table-hover">
					<thead>
						<tr role="row">
							<?php foreach($grid_field as $v): ?>
							<th role="row">
								<?php echo $v; ?>
							</th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<tr>
						<?php foreach($grid_field as $k=> $v): ?>
							<td>
								<?php foreach($items as $sk=> $sv): ?>
									<?php if( $k == $sk ) echo $sv; ?>
								<?php endforeach; ?>
							</td>
						<?php endforeach; ?>
						</tr>
					</tbody>
				</table>
			</div>
			<?php if( $show_remark ):?>
			<div class="group">
				<label for="el_remark" class="col-sm-2 control-label">备注信息<!-- 1 --></label>
				<div class="col-sm-4 ">
		            <!-- <button type="reset" class="btn btn-default">清除</button> -->
		            <input type="text" class="form-control " name="remark" id="el_remark" placeholder="请填写备注信息" value="<?php echo isset( $remark_data ) ? $remark_data : '';?>" >
		        </div>
		    </div>
			<?php endif;?>
		</div>
		<!-- /.box-body -->
		<div class="box-footer ">
            <div class="col-sm-4 col-sm-offset-2">
                <!-- <button type="reset" class="btn btn-default">清除</button> -->
                <input type="hidden" name="<?php echo $item_pk; ?>" value="<?php echo $items[$item_pk]; ?>" />
                <input type="hidden" name="code" value="<?php echo $code; ?>" />
                <input type="hidden" name="consumer_hotel_id" value="<?php echo $items['hotel_id']; ?>" />
                <?php echo $button_str; ?>
            </div>
		</div>
		<!-- /.box-footer -->
	<?php echo form_close() ?>
</div>
<!-- /.box -->

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
<div class="dialog">
  <div class="dialog-box">
    <div class="cont">
      <a href="javascript:;" class="dialog-close">x</a>
      <div class="title">该商品为多店通用券</div>
      <div class="body">
        <span class="input-label">请选择消费酒店<i>*</i></span>
        <select class="selectpicker show-tick" data-live-search="true" title="搜索或下拉选择酒店" data-size="20" id="selecter">
          <?php foreach($mulit_hotels as $hotel): ?>
            <option value="<?php echo $hotel['hotel_id']; ?>"><?php echo $hotel['name']; ?></option>
          <?php endforeach; ?>
        </select>
        <div class="dialog-control">
        <a href="javascript:;" class="btn btn-info">确认核销</a>
      </div>
      </div>

    </div>
  </div>
</div>
<script>

  // 是否为多店通用
  <?php if($show_mulit_hotels): ?>
    var isMultiple = true;
  <?php else: ?>
    var isMultiple = false;
  <?php endif; ?>

  // 此变量放置点击按钮需要弹框的type值
  var shouldShowLayerTypes = ['1','2']
  var $dialog = $('.dialog')
  var $hotelId = $('#selecter')
  var $consumerHotelId = $('.goForm').find('[name="consumer_hotel_id"]')
  var $elRemark = $('#el_remark')
  var reg = /^适合门店：[^；]+；/
	$(function(){
    var type_id;
    $dialog.on('click', '.dialog-close',function () {
      $dialog.hide()
    })
    $dialog.on('click','.btn-info', function () {
      var hotelId = $hotelId.val();
      if (!hotelId) {
        return alert('请选择需要核销的酒店')
      }
      var is_true = confirm("你确认要进行该操作吗？");
      if( !is_true ){
        return false;
      }
      var name = $hotelId.find('option:selected').text()
      $consumerHotelId.val(hotelId);
      var remark = $elRemark.val()
      if (reg.test(remark)) {
        remark = remark.replace(reg, function (){
          return '适合门店：' + name + '；'
        })
      } else {
        remark = '适合门店：' + name + '；' + remark
      }
      $elRemark.val(remark);
      $dialog.hide();
      var href = $(".goForm").attr('action') + '&type=' + type_id;
      $(".goForm").attr('action',href).submit();
    })
		$(".button_consumer").click(function(e){
      type_id = $(this).attr('type_id');
      if (isMultiple && shouldShowLayerTypes.indexOf(type_id) > -1) {
        $dialog.show()
        return false;
      } else {
        var is_true = '';
        is_true = confirm("你确认要进行该操作吗？");
        if( !is_true ){
          return false;
        }
        var href = $(".goForm").attr('action') + '&type=' + type_id;
        $(".goForm").attr('action',href);
      }
			// return false;
		});
	});
</script>
</body>
</html>
