<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
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
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-edit"></i> 输入核销码或订单号 </a></li>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
<?php 
echo form_open( Soma_const_url::inst()->get_url('*/*/get_info'), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) ); ?>
        		<div class="box-body "><br/>
                    <!-- <?php //echo $btn; ?> -->
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">选择公众号</label>
                        <div class="col-sm-6 inline">
                            <select class="form-control selectpicker show-tick" data-live-search="true" name="inter_id" id="interId">
                                <option value="">请选择公众号</option>
                                <?php foreach($publics as $k=>$v): ?>
                                    <option value="<?php echo $k;?>" <?php if( $k==$inter_id )echo 'selected';?>><?php echo $v;?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">核销码</label>
                        <div class="col-sm-6 inline">
                            <input type="text" maxlength="12" style="font-size:25px;line-height:40px;height:40px;" class="form-control " name="code" id="el_consumer_code" value="<?php echo $code;?>">
                        </div><div style="height:40px;line-height:40px;">请输入12位核销码</div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">订单号</label>
                        <div class="col-sm-6 inline">
                            <input type="text" maxlength="11" style="font-size:25px;line-height:40px;height:40px;" class="form-control " name="order_id" id="el_order_id" value="<?php echo $order_id;?>">
                        </div><div style="height:40px;line-height:40px;">请输入订单号</div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">赠送编号</label>
                        <div class="col-sm-6 inline">
                            <input type="text" maxlength="11" style="font-size:25px;line-height:40px;height:40px;" class="form-control " name="gift_id" id="el_gift_id" value="<?php echo $gift_id;?>">
                        </div><div style="height:40px;line-height:40px;">请输入赠送编号</div>
                    </div>
        		</div>
        		<!-- /.box-body -->
        		<div class="box-footer ">
                    <div class="col-sm-4 ">
                        <button type="submit" id="consumer" class="btn btn-info pull-right">查找</button>
                    </div>
        		</div>
            		<!-- /.box-footer -->
            		
            	
                <div class="box-body">
            	<?php if(isset($inter_id)):?>
                    <h4>公众号信息:【<?php echo $publics[$inter_id]; ?>】</h4>
                <?php endif;?>
                <!-- <h4>1.消费码状态位<?php foreach($codeStatus as $k=>$v){echo $k,'=>',$v,'、';}?></h4> -->
                <!-- <h4>2.订单状态位<?php foreach($orderStatus as $k=>$v){echo $k,'=>',$v,'、';}?></h4> -->
                <!-- <h4>3.消费状态位<?php foreach($ConsumerStatus as $k=>$v){echo $k,'=>',$v,'、';}?></h4> -->
                <!-- <h4>4.消费细单状态位<?php foreach($ConsumerItemStatus as $k=>$v){echo $k,'=>',$v,'、';}?></h4> -->
                <!-- <h4>5.赠送状态位<?php foreach($giftStatus as $k=>$v){echo $k,'=>',$v,'、';}?></h4> -->
                <!-- <h4>6.退款状态位<?php foreach($refundStatus as $k=>$v){echo $k,'=>',$v,'、';}?></h4> -->

<!-- ***********************以下是根据订单号查找的信息****************************** -->
                <?php if( count($code_codeDetail)>0 ): ?>
                    <div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <table id="data-grid" class="table table-bordered table-striped table-condensed">
                            核销码信息：
                            <thead><tr>
                                <td>序号</td><td>消费ID</td><td>消费细单ID</td><td>订单号</td><td>订单细单ID</td><td>资产ID</td><td>资产细单ID</td><td>消费码</td><td>状态</td>
                            </tr></thead>
                            <tbody>
                                <tr>
                                    <td><?php echo 1; ?></td>
                                    <td><?php echo $code_codeDetail['consumer_id']; ?></td>
                                    <td><?php echo $code_codeDetail['consumer_item_id']; ?></td>
                                    <td><?php echo $code_codeDetail['order_id']; ?></td>
                                    <td><?php echo $code_codeDetail['order_item_id']; ?></td>
                                    <td><?php echo $code_codeDetail['asset_id']; ?></td>
                                    <td><?php echo $code_codeDetail['asset_item_id']; ?></td>
                                    <td><?php echo $code_codeDetail['code']; ?></td>
                                    <td><?php echo $codeStatus[$code_codeDetail['status']]; ?></td>
                                </tr>
                            </tbody>
                            
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if( count($code_orderDetail)>0 ): ?>
                    <div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <table id="data-grid" class="table table-bordered table-striped table-condensed">
                            订单信息：
                            <thead><tr>
                                <td>订单号</td><td>购买方式</td><td>openid</td><td>下单时间</td><td>实际金额</td><td>实付金额</td><td>退款状态</td><td>消费状态</td><td>状态</td>
                            </tr></thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $code_orderDetail['order_id']; ?></td>
                                    <td><?php echo $code_orderDetail['settlement']; ?></td>
                                    <td><?php echo $code_orderDetail['openid']; ?></td>
                                    <td><?php echo $code_orderDetail['create_time']; ?></td>
                                    <td><?php echo $code_orderDetail['subtotal']; ?></td>
                                    <td><?php echo $code_orderDetail['grand_total']; ?></td>
                                    <td><?php echo $orderRefundStatus[$code_orderDetail['refund_status']]; ?></td>
                                    <td><?php echo $orderConsumerStatus[$code_orderDetail['consume_status']]; ?></td>
                                    <td><?php echo $orderStatus[$code_orderDetail['status']]; ?></td>
                                </tr>
                                <?php if( count( $code_orderDetail['items'] ) > 0 ): ?>
                                    <?php foreach($code_orderDetail['items'] as $k=>$v ): ?>
                                        <thead><tr>
                                            <td>订单细单ID</td><td>订单号</td><td>openid</td><td>商品ID</td><td>商品名称</td><td>套票价</td><td>数量</td><td>过期时间</td><!-- <td>退款状态</td> -->
                                        </tr></thead>
                                        <tbody>
                                            <tr>
                                                <td><?php echo $v['item_id']; ?></td>
                                                <td><?php echo $v['order_id']; ?></td>
                                                <td><?php echo $v['openid']; ?></td>
                                                <td><?php echo $v['product_id']; ?></td>
                                                <td><?php echo $v['name']; ?></td>
                                                <td><?php echo $v['price_package']; ?></td>
                                                <td><?php echo $v['qty']; ?></td>
                                                <td><?php echo $v['expiration_date']; ?></td>
                                                <!-- <td><?php //echo $v['is_refund']; ?></td> -->
                                            </tr>
                                        </tbody>
                                    <?php endforeach; ?>
                                <?php endif;?>
                            </tbody>
                            
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if( count($code_assetItems)>0 ): ?>
                    <div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <p> 资产信息：</p>
                            <table id="data-grid" class="table table-bordered table-striped table-condensed">
                            <thead><tr>
                                <td>序号</td><td>资产ID</td><td>资产细单ID</td><td>上级ID</td><td>订单ID</td><td>赠送ID</td><td>原openid</td><td>openid</td><td>商品ID</td><td>原数量</td><td>数量</td><td>新增时间</td>
                            </tr></thead>
                            <tbody>
                                <?php $i=1; foreach($code_assetItems as $k=>$v ): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $v['asset_id']; ?></td>
                                        <td><?php echo $v['item_id']; ?></td>
                                        <td><?php echo $v['parent_id']; ?></td>
                                        <td><?php echo $v['order_id']; ?></td>
                                        <td><?php echo $v['gift_id']; ?></td>
                                        <td><?php echo $v['openid_origin']; ?></td>
                                        <td><?php echo $v['openid']; ?></td>
                                        <td><?php echo $v['product_id']; ?></td>
                                        <td><?php echo $v['qty_origin']; ?></td>
                                        <td><?php echo $v['qty']; ?></td>
                                        <td><?php echo $v['add_time']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if( count($code_consumerDetail)>0 ): ?>
                    <div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <p> 消费信息：</p>
                            <?php foreach( $code_consumerDetail as $k=>$v ): ?>
                                <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                <thead><tr>
                                    <td>消费ID</td><td>openid</td><td>消费类型</td><td>消费方法</td><td>消费时间</td><td>消费人</td><td>地址IP</td><td>状态</td>
                                </tr></thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo $v['consumer_id']; ?></td>
                                        <td><?php echo $v['openid']; ?></td>
                                        <td><?php echo $v['consumer_type']; ?></td>
                                        <td><?php echo isset( $ConsumerTypeStatus[$v['consumer_type']] )?$ConsumerTypeStatus[$v['consumer_type']]:''; ?></td>
                                        <td><?php echo isset( $ConsumerMothedStatus[$v['consumer_method']] ) ? $ConsumerMothedStatus[$v['consumer_method']]:''; ?></td>
                                        <td><?php echo $v['consumer']; ?></td>
                                        <td><?php echo $v['remote_ip']; ?></td>
                                        <td><?php echo $ConsumerStatus[$v['status']]; ?></td>
                                    </tr>
                                </tbody>
                                </table>
                                <?php if( count( $v['items'] ) > 0 ): ?>
                                    <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                        <thead><tr>
                                            <td>消费细单ID</td><td>消费ID</td><td>资产细单ID</td><td>订单细单ID</td><td>订单ID</td><td>openid</td><td>商品ID</td><td>商品名称</td><td>过期时间</td><td>消费码</td><td>消费数量</td><td>状态</td>
                                        </tr></thead>
                                        <tbody>
                                            <tr>
                                                <td><?php echo $v['items']['item_id']; ?></td>
                                                <td><?php echo $v['items']['consumer_id']; ?></td>
                                                <td><?php echo $v['items']['asset_item_id']; ?></td>
                                                <td><?php echo $v['items']['order_item_id']; ?></td>
                                                <td><?php echo $v['items']['order_id']; ?></td>
                                                <td><?php echo $v['items']['openid']; ?></td>
                                                <td><?php echo $v['items']['product_id']; ?></td>
                                                <td><?php echo $v['items']['name']; ?></td>
                                                <td><?php echo $v['items']['expiration_date']; ?></td>
                                                <td><?php echo $v['items']['consumer_code']; ?></td>
                                                <td><?php echo $v['items']['consumer_qty']; ?></td>
                                                <td><?php echo $ConsumerItemStatus[$v['items']['status']]; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php endif;?>
                            <?php endforeach;?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if( count($code_giftDetail)>0 ): ?>
                    <div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <p> 赠送信息：</p>
                            <?php foreach( $code_giftDetail as $k=>$v ): ?>
                                <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                    <thead><tr>
                                        <td>赠送ID</td><td>赠送人</td><td>接收人</td><td>赠送总数</td><td>赠送人数</td><td>赠送份数</td><td>赠送方式</td><td>创建时间</td><td>更新时间</td><td>状态</td>
                                    </tr></thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $v['gift_id']; ?></td>
                                            <td><?php echo $v['openid_give']; ?></td>
                                            <td><?php echo $v['openid_received']; ?></td>
                                            <td><?php echo $v['total_qty']; ?></td>
                                            <td><?php echo $v['count_give']; ?></td>
                                            <td><?php echo $v['per_give']; ?></td>
                                            <td><?php echo isset( $giftTypeStatus[$v['is_p2p']] )?$giftTypeStatus[$v['is_p2p']]:''; ?></td>
                                            <td><?php echo $v['create_time']; ?></td>
                                            <td><?php echo $v['update_time']; ?></td>
                                            <td><?php echo $giftStatus[$v['status']]; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php if( count( $v['items'] ) > 0 ): ?>
                                    <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                    <thead><tr>
                                        <td>序号</td><td>receiver_id</td><td>赠送ID</td><td>赠送人</td><td>接收人</td><td>获得份数</td><td>获得时间</td>
                                    </tr></thead>
                                    <tbody>
                                        <?php $i=1; foreach($v['items'] as $sk=>$sv):?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $sv['receiver_id']; ?></td>
                                                <td><?php echo $sv['gift_id']; ?></td>
                                                <td><?php echo $sv['openid_give']; ?></td>
                                                <td><?php echo $sv['openid']; ?></td>
                                                <td><?php echo $sv['get_qty']; ?></td>
                                                <td><?php echo $sv['get_time']; ?></td>
                                            </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                    </table>
                                <?php endif;?>
                            <?php endforeach;?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if( count($code_refundDetail)>0 ): ?>
                    <div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <p> 退款信息：</p>
                            <?php foreach( $code_refundDetail as $k=>$v ): ?>
                                <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                    <thead><tr>
                                        <td>退款ID</td><td>订单号</td><td>openid</td><td>退款金额</td><td>创建时间</td><td>状态</td>
                                    </tr></thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $v['refund_id']; ?></td>
                                            <td><?php echo $v['order_id']; ?></td>
                                            <td><?php echo $v['openid']; ?></td>
                                            <td><?php echo $v['refund_total']; ?></td>
                                            <td><?php echo $v['create_time']; ?></td>
                                            <td><?php echo $refundStatus[$v['status']]; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php if( count( $v['items'] ) > 0 ): ?>
                                    <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                    <thead><tr>
                                        <td>序号</td><td>item_id</td><td>退款ID</td><td>数量</td><td>价格</td>
                                    </tr></thead>
                                    <tbody>
                                        <?php $i=1; foreach($v['items'] as $sk=>$sv):?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $sv['item_id']; ?></td>
                                                <td><?php echo $sv['refund_id']; ?></td>
                                                <td><?php echo $sv['qty']; ?></td>
                                                <td><?php echo $sv['price']; ?></td>
                                            </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                    </table>
                                <?php endif;?>
                            <?php endforeach;?>
                        </div>
                    </div>
                <?php endif; ?>


<!-- ***********************以下是根据赠送编号查找的信息****************************** -->
                <?php if( count($gift_codeDetail)>0 ): ?>
                    <div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <p> 核销码信息：</p>
                            <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                <thead><tr>
                                    <td>序号</td><td>消费ID</td><td>消费细单ID</td><td>订单号</td><td>订单细单ID</td><td>资产ID</td><td>资产细单ID</td><td>消费码</td><td>状态</td>
                                </tr></thead>
                                <tbody>
                                    <?php $i=1; foreach($gift_codeDetail as $k=>$v ): ?>
                                        <tr>

                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $v['consumer_id']; ?></td>
                                            <td><?php echo $v['consumer_item_id']; ?></td>
                                            <td><?php echo $v['order_id']; ?></td>
                                            <td><?php echo $v['order_item_id']; ?></td>
                                            <td><?php echo $v['asset_id']; ?></td>
                                            <td><?php echo $v['asset_item_id']; ?></td>
                                            <td><?php echo $v['code']; ?></td>
                                            <td><?php echo $codeStatus[$v['status']]; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if( count($gift_selfGiftDetail)>0 ): ?>
                    <div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <p> 礼物接收信息：</p>
                            <?php foreach( $gift_selfGiftDetail as $k=>$v ): ?>
                                <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                    <thead><tr>
                                        <td>赠送ID</td><td>赠送人</td><td>接收人</td><td>赠送总数</td><td>赠送人数</td><td>赠送份数</td><td>赠送方式</td><td>创建时间</td><td>更新时间</td><td>状态</td>
                                    </tr></thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $v['gift_id']; ?></td>
                                            <td><?php echo $v['openid_give']; ?></td>
                                            <td><?php echo $v['openid_received']; ?></td>
                                            <td><?php echo $v['total_qty']; ?></td>
                                            <td><?php echo $v['count_give']; ?></td>
                                            <td><?php echo $v['per_give']; ?></td>
                                            <td><?php echo isset( $giftTypeStatus[$v['is_p2p']] )?$giftTypeStatus[$v['is_p2p']]:''; ?></td>
                                            <td><?php echo $v['create_time']; ?></td>
                                            <td><?php echo $v['update_time']; ?></td>
                                            <td><?php echo $giftStatus[$v['status']]; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php if( count( $v['items'] ) > 0 ): ?>
                                    <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                    <thead><tr>
                                        <td>序号</td><td>receiver_id</td><td>赠送ID</td><td>赠送人</td><td>接收人</td><td>获得份数</td><td>获得时间</td>
                                    </tr></thead>
                                    <tbody>
                                        <?php $i=1; foreach($v['items'] as $sk=>$sv):?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $sv['receiver_id']; ?></td>
                                                <td><?php echo $sv['gift_id']; ?></td>
                                                <td><?php echo $sv['openid_give']; ?></td>
                                                <td><?php echo $sv['openid']; ?></td>
                                                <td><?php echo $sv['get_qty']; ?></td>
                                                <td><?php echo $sv['get_time']; ?></td>
                                            </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                    </table>
                                <?php endif;?>
                            <?php endforeach;?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if( count($gift_assetItems)>0 ): ?>
                    <div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <p> 资产信息：</p>
                            <table id="data-grid" class="table table-bordered table-striped table-condensed">
                            <thead><tr>
                                <td>序号</td><td>资产ID</td><td>资产细单ID</td><td>上级ID</td><td>订单ID</td><td>赠送ID</td><td>原openid</td><td>openid</td><td>商品ID</td><td>原数量</td><td>数量</td><td>新增时间</td>
                            </tr></thead>
                            <tbody>
                                <?php $i=1; foreach($gift_assetItems as $k=>$v ): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $v['asset_id']; ?></td>
                                        <td><?php echo $v['item_id']; ?></td>
                                        <td><?php echo $v['parent_id']; ?></td>
                                        <td><?php echo $v['order_id']; ?></td>
                                        <td><?php echo $v['gift_id']; ?></td>
                                        <td><?php echo $v['openid_origin']; ?></td>
                                        <td><?php echo $v['openid']; ?></td>
                                        <td><?php echo $v['product_id']; ?></td>
                                        <td><?php echo $v['qty_origin']; ?></td>
                                        <td><?php echo $v['qty']; ?></td>
                                        <td><?php echo $v['add_time']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if( count($gift_consumerDetail)>0 ): ?>
                    <div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <p> 消费信息：</p>
                            <?php foreach( $gift_consumerDetail as $k=>$v ): ?>
                                <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                <thead><tr>
                                    <td>消费ID</td><td>openid</td><td>消费类型</td><td>消费方法</td><td>消费时间</td><td>消费人</td><td>地址IP</td><td>状态</td>
                                </tr></thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo $v['consumer_id']; ?></td>
                                        <td><?php echo $v['openid']; ?></td>
                                        <td><?php echo isset( $ConsumerTypeStatus[$v['consumer_type']] )?$ConsumerTypeStatus[$v['consumer_type']]:''; ?></td>
                                        <td><?php echo isset( $ConsumerMothedStatus[$v['consumer_method']] ) ? $ConsumerMothedStatus[$v['consumer_method']]:''; ?></td>
                                        <td><?php echo $v['consumer_time']; ?></td>
                                        <td><?php echo $v['consumer']; ?></td>
                                        <td><?php echo $v['remote_ip']; ?></td>
                                        <td><?php echo $ConsumerStatus[$v['status']]; ?></td>
                                    </tr>
                                </tbody>
                                </table>
                                <?php if( count( $v['items'] ) > 0 ): ?>
                                    <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                        <thead><tr>
                                            <td>消费细单ID</td><td>消费ID</td><td>资产细单ID</td><td>订单细单ID</td><td>订单ID</td><td>openid</td><td>商品ID</td><td>商品名称</td><td>过期时间</td><td>消费码</td><td>消费数量</td><td>状态</td>
                                        </tr></thead>
                                        <tbody>
                                            <tr>
                                                <td><?php echo $v['items']['item_id']; ?></td>
                                                <td><?php echo $v['items']['consumer_id']; ?></td>
                                                <td><?php echo $v['items']['asset_item_id']; ?></td>
                                                <td><?php echo $v['items']['order_item_id']; ?></td>
                                                <td><?php echo $v['items']['order_id']; ?></td>
                                                <td><?php echo $v['items']['openid']; ?></td>
                                                <td><?php echo $v['items']['product_id']; ?></td>
                                                <td><?php echo $v['items']['name']; ?></td>
                                                <td><?php echo $v['items']['expiration_date']; ?></td>
                                                <td><?php echo $v['items']['consumer_code']; ?></td>
                                                <td><?php echo $v['items']['consumer_qty']; ?></td>
                                                <td><?php echo $ConsumerItemStatus[$v['items']['status']]; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php endif;?>
                            <?php endforeach;?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if( count($gift_giftDetail)>0 ): ?>
                    <div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <p> 赠送信息：</p>
                            <?php foreach( $gift_giftDetail as $k=>$v ): ?>
                                <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                    <thead><tr>
                                        <td>赠送ID</td><td>赠送人</td><td>接收人</td><td>赠送总数</td><td>赠送人数</td><td>赠送份数</td><td>赠送方式</td><td>创建时间</td><td>更新时间</td><td>状态</td>
                                    </tr></thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $v['gift_id']; ?></td>
                                            <td><?php echo $v['openid_give']; ?></td>
                                            <td><?php echo $v['openid_received']; ?></td>
                                            <td><?php echo $v['total_qty']; ?></td>
                                            <td><?php echo $v['count_give']; ?></td>
                                            <td><?php echo $v['per_give']; ?></td>
                                            <td><?php echo isset( $giftTypeStatus[$v['is_p2p']] )?$giftTypeStatus[$v['is_p2p']]:''; ?></td>
                                            <td><?php echo $v['create_time']; ?></td>
                                            <td><?php echo $v['update_time']; ?></td>
                                            <td><?php echo $giftStatus[$v['status']]; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php if( count( $v['items'] ) > 0 ): ?>
                                    <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                    <thead><tr>
                                        <td>序号</td><td>receiver_id</td><td>赠送ID</td><td>赠送人</td><td>接收人</td><td>获得份数</td><td>获得时间</td>
                                    </tr></thead>
                                    <tbody>
                                        <?php $i=1; foreach($v['items'] as $sk=>$sv):?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $sv['receiver_id']; ?></td>
                                                <td><?php echo $sv['gift_id']; ?></td>
                                                <td><?php echo $sv['openid_give']; ?></td>
                                                <td><?php echo $sv['openid']; ?></td>
                                                <td><?php echo $sv['get_qty']; ?></td>
                                                <td><?php echo $sv['get_time']; ?></td>
                                            </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                    </table>
                                <?php endif;?>
                            <?php endforeach;?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if( count($gift_refundDetail)>0 ): ?>
                    <div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <p> 退款信息：</p>
                            <?php foreach( $gift_refundDetail as $k=>$v ): ?>
                                <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                    <thead><tr>
                                        <td>退款ID</td><td>订单号</td><td>openid</td><td>退款金额</td><td>创建时间</td><td>状态</td>
                                    </tr></thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $v['refund_id']; ?></td>
                                            <td><?php echo $v['order_id']; ?></td>
                                            <td><?php echo $v['openid']; ?></td>
                                            <td><?php echo $v['refund_total']; ?></td>
                                            <td><?php echo $v['create_time']; ?></td>
                                            <td><?php echo $refundStatus[$v['status']]; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php if( count( $v['items'] ) > 0 ): ?>
                                    <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                    <thead><tr>
                                        <td>序号</td><td>item_id</td><td>退款ID</td><td>数量</td><td>价格</td>
                                    </tr></thead>
                                    <tbody>
                                        <?php $i=1; foreach($v['items'] as $sk=>$sv):?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $sv['item_id']; ?></td>
                                                <td><?php echo $sv['refund_id']; ?></td>
                                                <td><?php echo $sv['qty']; ?></td>
                                                <td><?php echo $sv['price']; ?></td>
                                            </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                    </table>
                                <?php endif;?>
                            <?php endforeach;?>
                        </div>
                    </div>
                <?php endif; ?>



<!-- ***********************以下是根据订单号查找的信息****************************** -->
                <?php if( count($order_codeDetail)>0 ): ?>
                    <div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <p> 核销码信息：</p>
                            <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                <thead><tr>
                                    <td>序号</td><td>消费ID</td><td>消费细单ID</td><td>订单号</td><td>订单细单ID</td><td>资产ID</td><td>资产细单ID</td><td>消费码</td><td>状态</td>
                                </tr></thead>
                                <tbody>
                                    <?php $i=1; foreach($order_codeDetail as $k=>$v ): ?>
                                        <tr>

                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $v['consumer_id']; ?></td>
                                            <td><?php echo $v['consumer_item_id']; ?></td>
                                            <td><?php echo $v['order_id']; ?></td>
                                            <td><?php echo $v['order_item_id']; ?></td>
                                            <td><?php echo $v['asset_id']; ?></td>
                                            <td><?php echo $v['asset_item_id']; ?></td>
                                            <td><?php echo $v['code']; ?></td>
                                            <td><?php echo $codeStatus[$v['status']]; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if( count($order_orderDetail)>0 ): ?>
                    <div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <p> 订单信息：</p>
                            <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                <thead><tr>
                                    <td>订单号</td><td>购买方式</td><td>openid</td><td>下单时间</td><td>实际金额</td>
                                        <td>实付金额</td><td>退款状态</td><td>消费状态</td><td>状态</td>
                                </tr></thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo $order_orderDetail['order_id']; ?></td>
                                        <td><?php echo $order_orderDetail['settlement']; ?></td>
                                        <td><?php echo $order_orderDetail['openid']; ?></td>
                                        <td><?php echo $order_orderDetail['create_time']; ?></td>
                                        <td><?php echo $order_orderDetail['subtotal']; ?></td>
                                        <td><?php echo $order_orderDetail['grand_total']; ?></td>
                                        <td><?php echo $orderRefundStatus[$order_orderDetail['refund_status']]; ?></td>
                                        <td><?php echo $orderConsumerStatus[$order_orderDetail['consume_status']]; ?></td>
                                        <td><?php echo $orderStatus[$order_orderDetail['status']]; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php if( count( $order_orderDetail['items'] ) > 0 ): ?>
                            <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                <thead><tr>
                                    <td>订单细单ID</td><td>订单号</td><td>openid</td><td>商品ID</td><td>商品名称</td>
                                        <td>套票价</td><td>数量</td><td>过期时间</td><!-- <td>退款状态</td> -->
                                </tr></thead>
                                <tbody>
                                <?php foreach($order_orderDetail['items'] as $k=>$v ): ?>
                                    <tr>
                                        <td><?php echo $v['item_id']; ?></td>
                                        <td><?php echo $v['order_id']; ?></td>
                                        <td><?php echo $v['openid']; ?></td>
                                        <td><?php echo $v['product_id']; ?></td>
                                        <td><?php echo $v['name']; ?></td>
                                        <td><?php echo $v['price_package']; ?></td>
                                        <td><?php echo $v['qty']; ?></td>
                                        <td><?php echo $v['expiration_date']; ?></td>
                                        <!-- <td><?php //echo $v['is_refund']; ?></td> -->
                                    </tr>
                                </tbody>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php endif;?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if( count($order_assetItems)>0 ): ?>
                    <div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <p> 资产信息：</p>
                            <table id="data-grid" class="table table-bordered table-striped table-condensed">
                            <thead><tr>
                                <td>序号</td><td>资产ID</td><td>资产细单ID</td><td>上级ID</td><td>订单ID</td><td>赠送ID</td><td>原openid</td><td>openid</td><td>商品ID</td><td>原数量</td><td>数量</td><td>新增时间</td>
                            </tr></thead>
                            <tbody>
                                <?php $i=1; foreach($order_assetItems as $k=>$v ): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $v['asset_id']; ?></td>
                                        <td><?php echo $v['item_id']; ?></td>
                                        <td><?php echo $v['parent_id']; ?></td>
                                        <td><?php echo $v['order_id']; ?></td>
                                        <td><?php echo $v['gift_id']; ?></td>
                                        <td><?php echo $v['openid_origin']; ?></td>
                                        <td><?php echo $v['openid']; ?></td>
                                        <td><?php echo $v['product_id']; ?></td>
                                        <td><?php echo $v['qty_origin']; ?></td>
                                        <td><?php echo $v['qty']; ?></td>
                                        <td><?php echo $v['add_time']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if( count($order_consumerDetail)>0 ): ?>
                    <div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <p> 消费信息：</p>
                                <?php foreach( $order_consumerDetail as $k=>$v ): ?>
                                <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                    <thead><tr>
                                        <td>消费ID</td><td>openid</td><td>消费类型</td><td>消费方法</td><td>消费时间</td><td>消费人</td><td>地址IP</td><td>状态</td>
                                    </tr></thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $v['consumer_id']; ?></td>
                                            <td><?php echo $v['openid']; ?></td>
                                            <td><?php echo isset( $ConsumerTypeStatus[$v['consumer_type']] )?$ConsumerTypeStatus[$v['consumer_type']]:''; ?></td>
                                            <td><?php echo isset( $ConsumerMothedStatus[$v['consumer_method']] ) ? $ConsumerMothedStatus[$v['consumer_method']]:''; ?></td>
                                            <td><?php echo $v['consumer_time']; ?></td>
                                            <td><?php echo $v['consumer']; ?></td>
                                            <td><?php echo $v['remote_ip']; ?></td>
                                            <td><?php echo $ConsumerStatus[$v['status']]; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php if( count( $v['items'] ) > 0 ): ?>
                                    <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                    <thead><tr>
                                        <td>消费细单ID</td><td>消费ID</td><td>资产细单ID</td><td>订单细单ID</td><td>订单ID</td><td>openid</td><td>商品ID</td><td>商品名称</td><td>过期时间</td><td>消费码</td><td>消费数量</td><td>状态</td>
                                    </tr></thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $v['items']['item_id']; ?></td>
                                            <td><?php echo $v['items']['consumer_id']; ?></td>
                                            <td><?php echo $v['items']['asset_item_id']; ?></td>
                                            <td><?php echo $v['items']['order_item_id']; ?></td>
                                            <td><?php echo $v['items']['order_id']; ?></td>
                                            <td><?php echo $v['items']['openid']; ?></td>
                                            <td><?php echo $v['items']['product_id']; ?></td>
                                            <td><?php echo $v['items']['name']; ?></td>
                                            <td><?php echo $v['items']['expiration_date']; ?></td>
                                            <td><?php echo $v['items']['consumer_code']; ?></td>
                                            <td><?php echo $v['items']['consumer_qty']; ?></td>
                                            <td><?php echo $ConsumerItemStatus[$v['items']['status']]; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php endif;?>
                            <?php endforeach;?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if( count($order_giftDetail)>0 ): ?>
                    <div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <p> 赠送信息：</p>
                            <?php foreach( $order_giftDetail as $k=>$v ): ?>
                                <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                    <thead><tr>
                                        <td>赠送ID</td><td>赠送人</td><td>接收人</td><td>赠送总数</td><td>赠送人数</td><td>赠送份数</td><td>赠送方式</td><td>创建时间</td><td>更新时间</td><td>状态</td>
                                    </tr></thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $v['gift_id']; ?></td>
                                            <td><?php echo $v['openid_give']; ?></td>
                                            <td><?php echo $v['openid_received']; ?></td>
                                            <td><?php echo $v['total_qty']; ?></td>
                                            <td><?php echo $v['count_give']; ?></td>
                                            <td><?php echo $v['per_give']; ?></td>
                                            <td><?php echo isset( $giftTypeStatus[$v['is_p2p']] )?$giftTypeStatus[$v['is_p2p']]:''; ?></td>
                                            <td><?php echo $v['create_time']; ?></td>
                                            <td><?php echo $v['update_time']; ?></td>
                                            <td><?php echo $giftStatus[$v['status']]; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php if( count( $v['items'] ) > 0 ): ?>
                                    <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                    <thead><tr>
                                        <td>序号</td><td>receiver_id</td><td>赠送ID</td><td>赠送人</td><td>接收人</td><td>获得份数</td><td>获得时间</td>
                                    </tr></thead>
                                    <tbody>
                                        <?php $i=1; foreach($v['items'] as $sk=>$sv):?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $sv['receiver_id']; ?></td>
                                                <td><?php echo $sv['gift_id']; ?></td>
                                                <td><?php echo $sv['openid_give']; ?></td>
                                                <td><?php echo $sv['openid']; ?></td>
                                                <td><?php echo $sv['get_qty']; ?></td>
                                                <td><?php echo $sv['get_time']; ?></td>
                                            </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                    </table>
                                <?php endif;?>
                            <?php endforeach;?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if( count($order_refundDetail)>0 ): ?>
                    <div class='form-group '>
                        <div class="col-sm-12 col-sm-offset-0">
                            <p> 退款信息：</p>
                            <?php foreach( $order_refundDetail as $k=>$v ): ?>
                                <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                    <thead><tr>
                                        <td>退款ID</td><td>订单号</td><td>openid</td><td>退款金额</td><td>创建时间</td><td>状态</td>
                                    </tr></thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $v['refund_id']; ?></td>
                                            <td><?php echo $v['order_id']; ?></td>
                                            <td><?php echo $v['openid']; ?></td>
                                            <td><?php echo $v['refund_total']; ?></td>
                                            <td><?php echo $v['create_time']; ?></td>
                                            <td><?php echo $refundStatus[$v['status']]; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php if( count( $v['items'] ) > 0 ): ?>
                                    <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                    <thead><tr>
                                        <td>序号</td><td>item_id</td><td>退款ID</td><td>数量</td><td>价格</td>
                                    </tr></thead>
                                    <tbody>
                                        <?php $i=1; foreach($v['items'] as $sk=>$sv):?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $sv['item_id']; ?></td>
                                                <td><?php echo $sv['refund_id']; ?></td>
                                                <td><?php echo $sv['qty']; ?></td>
                                                <td><?php echo $sv['price']; ?></td>
                                            </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                    </table>
                                <?php endif;?>
                            <?php endforeach;?>
                        </div>
                    </div>
                <?php endif; ?>



            </div>

<?php echo form_close() ?>
            </div><!-- /#tab1-->
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
?>
<script>
    $("#consumer").click(function(){
        var inter_id = $("#interId").val();
        var code = $("#el_consumer_code").val();
        if( !inter_id ){
            alert( '请输入消费码或者选择公众号' );
            return false;
        }
    });
</script>
</body>
</html>
