<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->

<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datepicker/datepicker3.css'>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datepicker/bootstrap-datepicker.js'></script>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datepicker/locales/bootstrap-datepicker.zh-CN.js'></script>

<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.css'>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.js'></script>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/bootstrap-select/i18n/defaults-zh_CN.min.js'></script>

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
<?php $pk= $model->table_primary_key(); ?>

<div class="box box-info">
        
    <div class="tabbable " id="top_tabs"> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li <?php if($batch!=Soma_base::STATUS_TRUE && $batch!=Soma_base::STATUS_FALSE && $batch != 'export')echo 'class="active"';?>><a href="#tab1" data-toggle="tab"><i class="fa fa-edit"></i> 输入核销码 </a></li>
            <?php if( isset($inter_id) && $inter_id): ?>
            <li class=""><a href="#tab2" data-toggle="tab"><i class="fa fa-qrcode"></i> 扫码核销 </a></li>
            <?php else: ?>
            <li class="disabled"><a href="#tab2" data-toggle="tab"><i class="fa fa-qrcode"></i> 扫码核销 </a></li>
            <?php endif; ?>
            <li <?php if($batch=='export')echo 'class="active"';?>><a href="#tab3" data-toggle="tab"><i class="fa fa-download"></i> 核销数据 </a></li>
            <li <?php if($batch==Soma_base::STATUS_TRUE)echo 'class="active"';?>><a href="#tab4" data-toggle="tab"><i class="fa fa-edit"></i> 批量核销 </a></li>
            <li <?php if($batch==Soma_base::STATUS_FALSE)echo 'class="active"';?>><a href="#tab5" data-toggle="tab"><i class="fa fa-edit"></i> 产品延期 </a></li>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane <?php if($batch!=Soma_base::STATUS_TRUE && $batch!=Soma_base::STATUS_FALSE && $batch != 'export')echo 'active';?>" id="tab1">
<?php 
echo form_open( Soma_const_url::inst()->get_url('*/*/edit'), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) ); ?>
        		<div class="box-body "><br/>
                    <?php echo $btn; ?>
                    <?php echo $btn_search; ?><br/>
        		</div>
        		<!-- /.box-body -->
        		<div class="box-footer ">
                    <div class="col-sm-4 ">
                        <!-- <button type="reset" class="btn btn-default">清除</button> -->
                        <button type="submit" class="btn btn-info pull-right">查找</button>
                    </div>
        		</div>
            		<!-- /.box-footer -->
<?php echo form_close() ?>
            </div><!-- /#tab1-->
            
            <div class="tab-pane" id="tab2">
    			<div class="box-body">
    				<!-- 购买清单 -->
    				<div class="col-sm-12 " >
                    <?php if( isset($inter_id) && $inter_id): ?>
                        <div class="col-sm-3 col-sm-offset-1 ">
<img src="<?php echo Soma_const_url::inst()->get_url('*/*/show_consume_code', array('id'=> $inter_id) ); ?>" />
    					</div>
    					<div class="col-sm-8 inline">
		<p><b>注意事项：</b></p>
		<ol>
			<li><p>使用此功能必须先对扫码微信号进行授权，<a href="<?php echo Soma_const_url::inst()->get_url('privilege/adminuser/profile'); ?>" target="_blank"><b>点击此处</b></a>中 <code>扫码授权</code>进行操作。</p></li>
			<li><p>授权只需一次操作，无须重复授权，本管理员有责任对授权账号进行<code>审核通过</code>和<code>清退操作</code>。</p></li>
			<li><p>授权后微信号所做的操作将等同于本管理员的操作，其操作<code>造成之损失由本管理员承担</code>。</p></li>
			<li><p>一旦进行扫码操作，即等同于同意以上内容</p></li>
			<li><p>一切授权工作完成，扫一扫<code>左边</code>二维码即可开始核销等操作</p></li>
		</ol>
		                </div>
                    <?php else: ?><div>未检测到对应公众号id，必须以商户账号身份登陆使用此功能</div>
                    <?php endif; ?>
    				</div>
    			</div>
    			<!-- /.box-body -->
            </div><!-- /#tab2-->

            <div class="tab-pane <?php if( $batch == 'export')echo 'active';?>" id="tab3">
                <?php echo form_open( Soma_const_url::inst()->get_url('*/*/remark_post'), array('class'=>'form-horizontal','id'=>'remarkPost') ); ?>
                <div class="box-body">
    				<div class="col-sm-12 " >
        				<div class='form-group '>
                            <?php if( $hotelIds ):?>
                            	<label for='el_hotel_id' class='col-sm-1 control-label'>酒店</label>
                            	<div class='col-sm-1'>
                            		<select class='form-control selectpicker show-tick' data-live-search='true' name='hotel_id' id='el_hotel_id' required >
                            		        <option value="" >全部</option>
                            		    <?php foreach ($hotelIds as $k=>$v):?>
                            		        <option value="<?php echo $k; ?>" <?php if( $k==$hotel_id ) echo 'selected="selected"'; ?>><?php echo $v['name']; ?></option>
                            		    <?php endforeach; ?>
                            		</select>
                            	</div>
                            <?php endif;?>
                            <label for="el_update_time" class="col-sm-1 control-label">开始时间</label>
                            <div class="col-sm-1">
                                <div class=" input-group date">
                                    <input type="text" class="form-control" name="start_time" size="16" id="el_start_time" value="<?php echo $start_time; ?>" required >
                                    <!-- <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span> -->
                                </div>
                            </div> 
                            <label for="el_update_time" class="col-sm-1 control-label">结束时间</label>
                            <div class="col-sm-1">
                                <div class=" input-group date">
                                    <input type="text" class="form-control" name="end_time" size="16" id="el_end_time" value="<?php echo $end_time; ?>" required >
                                    <!-- <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span> -->
                                </div>
                            </div>
                            <label for="el_consumer" class="col-sm-1 control-label">核销账号</label>
                            <div class="col-sm-1">
                                <div class=" input-group date">
                                    <input type="text" class="form-control" name="consumer" size="16" id="el_consumer" value="<?php echo $consumer; ?>" required >
                                    <!-- <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span> -->
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-info pull-left col-sm-offset-1" id='exportSearch'>查找</button>
                                <button type="button" class="btn btn-info pull-left col-sm-offset-1" id='exportList'>导出</button>
                            </div>
                        	<!-- <div class='col-sm-4'>
                        		<select class='form-control selectpicker show-tick' data-live-search='true' name='consumer_type' id='el_consumer_type' >
                        		        <option value="" ></option>
                        		    <?php //foreach ($settle_arr as $k=>$v): 
                        		          //if( isset($consumer_type) && $consumer_type==$model::CONSUME_TYPE_DEFAULT ) $selected= ' selected="selected" ';
                        		          //else $selected= '';
                        		    ?>
                        		        <option value="<?php //echo $k; ?>" <?php //echo $selected; ?>><?php// echo $v; ?></option>
                        		    <?php //endforeach; ?>
                        		</select>
                        	</div> -->
                        </div>
                        <div class='form-group '>
                            <label class="col-sm-1 control-label">核销商品</label>
                            <div class="col-sm-1">
                                <div class=" input-group date">
                                    <input type="text" class="form-control" name="name" size="16" id="el_name" value="<?php echo $name; ?>" required >
                                    <!-- <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span> -->
                                </div>
                            </div>
                            <label class="col-sm-1 control-label">备注</label>
                            <div class="col-sm-1">
                                <div class=" input-group date">
                                    <input type="text" class="form-control" name="remark" size="16" id="el_remark" value="<?php echo $remark; ?>" required >
                                    <!-- <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span> -->
                                </div>
                            </div>
                            <label class="col-sm-1 control-label">核销券码</label>
                            <div class="col-sm-2">
                                <div class=" input-group date">
                                    <input type="text" class="form-control" name="consumer_code" size="16" id="el_consumer_codedd" value="<?php echo $consumer_code; ?>" required >
                                    <!-- <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span> -->
                                </div>
                            </div>
                            <label class="col-sm-1 control-label">订单号</label>
                            <div class="col-sm-2">
                                <div class=" input-group date">
                                    <input type="text" class="form-control" name="order_id" size="16" id="el_order_id" value="<?php echo $order_id; ?>" required >
                                    <!-- <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span> -->
                                </div>
                            </div>
                            <!-- <div class="col-sm-2">
                                <button type="button" class="btn btn-info pull-left col-sm-offset-1" id='exportSearch'>查找</button>
                                <button type="button" class="btn btn-info pull-left col-sm-offset-1" id='exportList'>导出</button>
                            </div> -->
                        </div>
        				<!-- <div class="form-group ">
                            <label for="el_update_time" class="col-sm-2 control-label">开始时间</label>
                            <div class="col-sm-4">
                                <div class=" input-group date">
                                    <input type="text" class="form-control" name="start_time" size="16" id="el_start_time" value="<?php //echo $start_time; ?>" required >
                        			<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    			</div>
                            </div> 
                            <label for="el_update_time" class="col-sm-2 control-label">结束时间</label>
                            <div class="col-sm-4">
                                <div class=" input-group date">
                                    <input type="text" class="form-control" name="end_time" size="16" id="el_end_time" value="<?php //echo $end_time; ?>" required >
                        			<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    			</div>
                            </div>
                        </div> -->
    				</div>
					<!-- <div class="box-footer ">
						<div class="col-sm-4 col-sm-offset-4">
                            <button type="button" class="btn btn-info pull-left col-sm-offset-4" id='exportSearch'>查找</button>
							<button type="button" class="btn btn-info pull-left col-sm-offset-4" id='exportList'>导出</button>
						</div>
					</div> -->

                    <div class="box-body">
                        <div class=" col-sm-12 " >
                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            <h4 class="modal-title" id="myModalLabel">修改备注</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <textarea name="remark" class="form-control" id="remarkVal"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="iid" value="" id="itemId">
                                            <input type="hidden" name="curr_url" value="" id="currUrl">
                                            <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>关闭</button>
                                            <button type="button" class="btn btn-primary" data-dismiss="modal" id="btn_submit"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>保存</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <?php foreach ($consumer_fields_config as $k=>$v): ?>
                                            <th>
                                                <?php echo $v;?>
                                            </th>
                                        <?php endforeach; ?>
                                            <th>
                                                操作
                                            </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($export_data as $k=>$v):?>
                                        <tr>
                                            <!-- <td><?php echo $v['consumer_id'];?></td>
                                            <td><?php echo $v['consumer_code'];?></td>
                                            <td><?php echo $v['name'];?></td>
                                            <td><?php echo $v['price_package'];?></td>
                                            <td><?php echo $v['grand_total'];?></td>
                                            <td><?php echo $v['order_id'];?></td>
                                            <td><?php echo $v['remark'];?></td>
                                            <td><?php echo $v['consumer_time'];?></td>
                                            <td><?php echo $v['consumer_method'];?></td>
                                            <td><?php echo $v['consumer'];?></td> -->
                                            <?php foreach ($consumer_fields_config as $sk=>$sv): ?>
                                                <td><?php echo isset($v[$sk]) ? $v[$sk]:'';?>
                                            <?php endforeach; ?>
                                            <td><button type="button" class="btn btn-info pull-left col-sm-offset-4 editRemark" iid="<?php echo $v['item_id'];?>" remark_val="<?php echo $v['remark'];?>" >修改备注</button></td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-sm-4">
                                </div>
                                <div class="col-sm-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate">
                                        <ul class="pagination"><?php echo $pagination?></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

    			</div>
            <?php echo form_close() ?>
                <!-- /.box-body -->
            </div><!-- /#tab3-->

            <div class="tab-pane <?php if($batch==Soma_base::STATUS_TRUE)echo 'active';?>" id="tab4">
<?php 
echo form_open( Soma_const_url::inst()->get_url('*/*/batch_edit'), array('class'=>'form-horizontal', 'method' => 'get'), array($pk=>$model->m_get($pk) ) ); ?>
                <div class="box-body "><br/>
                    <div class="form-group ">
                        <label class='col-sm-2 control-label'>查询类型</label>
                        <div class='col-sm-6 inline'>
                            <select class='form-control selectpicker show-tick' name='type' id='el_type'>
                                <?php foreach($batch_type as $value => $label): ?>
                                    <option value="<?php echo $value; ?>">按<?php echo $label; ?>查询</option>
                                <?php endforeach; ?>
                                <!-- <option value="order" >按订单单号查询</option>
                                <option value="gift" >按赠送单号查询</option> -->
                            </select>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">查询单号</label>
                        <div class="col-sm-6 inline">
                            <input type="text" maxlength="12" style="font-size:25px;line-height:40px;height:40px;" class="form-control " name="order_id" id="el_order_id" value="">
                        </div>
                        <!-- <div style="height:40px;line-height:40px;">请输入单号</div> -->
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer ">
                    <div class="col-sm-4 ">
                        <input type="hidden" name="batch" value="1" />
                        <button type="submit" class="btn btn-info pull-right">查找</button>
                    </div>
                </div>
                    <!-- /.box-footer -->
<?php echo form_close() ?>
            </div><!-- /#tab4-->

            <div class="tab-pane <?php if($batch==Soma_base::STATUS_FALSE)echo 'active';?>" id="tab5">
<?php 
echo form_open( Soma_const_url::inst()->get_url('*/*/expire_edit'), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) ); ?>
                <div class="box-body "><br/>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">商品ID</label>
                        <div class="col-sm-6 inline">
                            <input type="text" maxlength="8" style="font-size:25px;line-height:40px;height:40px;" class="form-control " name="pid" id="el_pid" value="">
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer ">
                    <div class="col-sm-4 ">
                        <button type="submit" class="btn btn-info pull-right">查找</button>
                    </div>
                </div>
                    <!-- /.box-footer -->
<?php echo form_close() ?>
            </div><!-- /#tab4-->

        </div>
    </div>
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
?><!-- 
<script>
	$(function(){
		$("#el_consumer_code").blur(function(){
			var code = $(this).val();
			var url = "<?php echo $act_post_url; ?>";
			var csrf_token = "<?php echo $csrf_token; ?>";
			var csrf_value = "<?php echo $csrf_value; ?>";
			var code_search = $("#code_search");
			// alert(code);
			if( code ){
				$.ajax({
					type: 'POST',
					url: url,
					data: {csrf_token:csrf_value,code:code},
					success:function(msg){
						// alert(msg);
						// code_search.html('');
						code_search.append( msg );
					}
				});
			}
		});
	});
</script> -->
<script>
$("#el_start_time").datepicker({ format:"yyyy-mm-dd", language: "zh-CN",clearBtn: false,todayBtn: false,orientation: "auto left" });
$("#el_end_time").datepicker({ format:"yyyy-mm-dd", language: "zh-CN",clearBtn: false,todayBtn: false,orientation: "auto left" });
$("#exportSearch").click(function(){
    var url = '<?php echo current_url();?>';
    var go_url='?batch=export';
    if( $("#el_start_time").val() ){
        go_url +='&start='+$("#el_start_time").val();
    }
    if( $("#el_end_time").val() ){
        go_url +='&end='+$("#el_end_time").val();
    }
    if( $("#el_hotel_id").val() ){
        go_url +='&hotel_id='+$("#el_hotel_id").val();
    }
    if( $("#el_consumer").val() ){
        go_url +='&consumer='+$("#el_consumer").val();
    }
    if( $("#el_consumer_codedd").val() ){
        go_url +='&consumer_code='+$("#el_consumer_codedd").val();
    }
    if( $("#el_name").val() ){
        go_url +='&name='+$("#el_name").val();
    }
    if( $("#el_order_id").val() ){
        go_url +='&order_id='+$("#el_order_id").val();
    }
    if( $("#el_remark").val() ){
        go_url +='&remark='+$("#el_remark").val();
    }
    window.location= url+ go_url;
});
$("#exportList").click(function(){
    var url = '<?php echo Soma_const_url::inst()->get_url('*/*/export_list');?>';
    var go_url= '?1';
    
    if( $("#el_start_time").val() ){
        go_url +='&start_time='+$("#el_start_time").val();
    }
    if( $("#el_end_time").val() ){
        go_url +='&end_time='+$("#el_end_time").val();
    }
    if( $("#el_hotel_id").val() ){
        go_url +='&hotel_id='+$("#el_hotel_id").val();
    }
    if( $("#el_consumer").val() ){
        go_url +='&consumer='+$("#el_consumer").val();
    }
    if( $("#el_consumer_codedd").val() ){
        go_url +='&consumer_code='+$("#el_consumer_codedd").val();
    }
    if( $("#el_name").val() ){
        go_url +='&name='+$("#el_name").val();
    }
    if( $("#el_order_id").val() ){
        go_url +='&order_id='+$("#el_order_id").val();
    }
    if( $("#el_remark").val() ){
        go_url +='&remark='+$("#el_remark").val();
    }
    window.location= url+ go_url;
});
$(".editRemark").click(function(){
    var remark_val = $(this).attr('remark_val');
    var iid = $(this).attr('iid');
    $("#remarkVal").val(remark_val);
    $("#itemId").val(iid);
    $("#currUrl").val('<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>');
    $("#myModal").modal();
});
$("#btn_submit").click(function(){
    var val = $("#remarkVal").val();
    if( val == '' ){
        alert( '请输入备注信息！' );
        return false;
    }
    $("#remarkPost").submit();
});

</script>
</body>
</html>
