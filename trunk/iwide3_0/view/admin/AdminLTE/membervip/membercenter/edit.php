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
          <h1><?php echo isset($breadcrumb_array['action'])? $breadcrumb_array['action']: ''; ?>
            <small></small>
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
<?php echo $this->session->show_put_msg(); ?>
<!-- Horizontal Form -->
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">
            <strong>手机访问：</strong>
<code><?php if(isset($public)) echo "http://".$public['domain']."/index.php/membervip/center?id=".$public['inter_id'];?></code>
或<a data-toggle="modal" data-target="#myModal" href="#" ><code>打开二维码</code></a>，用微信扫一扫
        </h3>
	</div>
		<!-- /.box-footer -->
</div>
<!-- /.box -->
<!-- 二维码弹层 -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="background:#fff;width:280px;height:340px;margin:220px auto;"> 
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 >请用微信扫一扫</h4>
  </div>
  <div class="modal-body" style="margin:10px 0 15px 15px ;text-align:center;">
    <img id="qrcode-img" src="
    <?php echo str_replace('vapi/', '', PMS_PATH_URL).'tool/qr/get?str='."http://".$public['domain']."/index.php/membervip/center?id=".$public['inter_id']; ?>
    " />
  </div> 
</div>

<!-- Horizontal Form -->
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">会员中心配置</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-inline')); ?>
		<div class="box-body">
            <table class="table table-bordered table-striped table-condensed dataTable no-footer">
            <thead>
            	<tr>
            		<th>分组号码</th>
            		<th>模块名称</th>
            		<th>模块链接</th>
                    <th>超链接</th>
                    <th>图标样式</th>
            		<!-- <th>提示信息</th> -->
            	</tr>
            </thead>
            <tbody>
            <?php if(isset($public)){
                $center_url = "http://".$public['domain']."/index.php/";?>
            <?php }else{ ?>
                $center_url ="#" ;
            <?php }?>
            <?php foreach ($centerinfo as $key => $value){?>
            	<tr>
            		<td>
                        <select name="group_<?php echo $key; ?>" class="form-control">
                            <option value="1" <?php if($value['group']==1){ ?> selected <?php } ?>  >分组一</option>
                            <option value="2" <?php if($value['group']==2){ ?> selected <?php } ?>  >分组二</option>
                            <option value="3" <?php if($value['group']==3){ ?> selected <?php } ?>  >分组三</option>
                            <option value="4" <?php if($value['group']==4){ ?> selected <?php } ?>  >分组四</option>
                            <option value="5" <?php if($value['group']==5){ ?> selected <?php } ?>  >分组五</option>
                        </select>
                    </td>
                    <td><input type="text" name="modelname_<?php echo $key; ?>" value="<?php echo $value['modelname'] ?>" class="form-control" /></td>
                    <td>
                        <select name="modeltype_<?php echo $key;?>" class="form-control">
                            <option value="">--请选择模块链接--</option>
                            <option value="<?php echo $center_url.'membervip/center/info?id='.$public['inter_id']; ?>" <?php if($value['link']=="$center_url.'membervip/center/info?id='".$public['inter_id'].""){ ?> <?php } ?> >会员资料</option>
                            <option value="2" <?php if($value['link']=="2"){ ?> <?php } ?> >购物商城</option>
                            <option value="3" <?php if($value['link']=="3"){ ?> <?php } ?> >我的分销</option>
                            <option value="4" <?php if($value['link']=="4"){ ?> <?php } ?> >我要订房</option>
                            <option value="5" <?php if($value['link']=="5"){ ?> <?php } ?> >积分商城</option>
                        </select>
                    </td>
                    <td><input type="text" name="link_<?php echo $key; ?>" value="<?php echo $value['link'] ?>" class="form-control" /></td>
                    <td>
                    <?php if($public['inter_id']=='a483407432'||$public['inter_id']=='a480930558'){?>
                      <select name="ico_<?php echo $key; ?>" class="form-control">
                            <option value=""   <?php if($value['ico']==""){ ?> selected <?php } ?> >无</option>
                            <option value="junting01" <?php if($value['ico']=="junting01"){ ?> selected <?php } ?> >我的身份</option>
                            <option value="junting02" <?php if($value['ico']=="junting02"){ ?> selected <?php } ?> >酒店订单</option>
                            <option value="junting03" <?php if($value['ico']=="junting03"){ ?> selected <?php } ?> >商城订单</option>
                            <option value="junting04" <?php if($value['ico']=="junting04"){ ?> selected <?php } ?> >在线预定</option>
                            <option value="junting05" <?php if($value['ico']=="junting05"){ ?> selected <?php } ?> >会员集市</option>
                            <option value="junting06" <?php if($value['ico']=="junting06"){ ?> selected <?php } ?> >会员权益</option>
                            <option value="junting07" <?php if($value['ico']=="junting07"){ ?> selected <?php } ?> >我的客服</option>
                            <option value="junting08" <?php if($value['ico']=="junting08"){ ?> selected <?php } ?> >最新活动</option>
                            <option value="junting09" <?php if($value['ico']=="junting09"){ ?> selected <?php } ?> >关于我们</option>
                    </select>
                    <?php }else {?>
                        <select name="ico_<?php echo $key; ?>" class="form-control">
                            <option value=""   <?php if($value['ico']==""){ ?> selected <?php } ?> >无</option>
                            <option value="ui_ico31" <?php if($value['ico']=="ui_ico31"){ ?> selected <?php } ?> >会员资料</option>
                            <option value="ui_ico1" <?php if($value['ico']=="ui_ico1"){ ?> selected <?php } ?> >会员卡</option>
                            <option value="ui_ico2" <?php if($value['ico']=="ui_ico2"){ ?> selected <?php } ?> >礼品商城</option>
                            <option value="ui_ico3" <?php if($value['ico']=="ui_ico3"){ ?> selected <?php } ?> >积分商城</option>
                            <option value="ui_ico4" <?php if($value['ico']=="ui_ico4"){ ?> selected <?php } ?> >储值卡</option>
                            <option value="ui_ico32" <?php if($value['ico']=="ui_ico32"){ ?> selected <?php } ?> >凤凰礼卡</option>
                            <option value="ui_ico5" <?php if($value['ico']=="ui_ico5"){ ?> selected <?php } ?> >二维码</option>
                            <option value="ui_ico6" <?php if($value['ico']=="ui_ico6"){ ?> selected <?php } ?> >我的地址</option>
                            <option value="ui_ico8" <?php if($value['ico']=="ui_ico8"){ ?> selected <?php } ?> >记录</option>
                            <option value="ui_ico9" <?php if($value['ico']=="ui_ico9"){ ?> selected <?php } ?> >会员权益</option>
                            <option value="ui_ico11" <?php if($value['ico']=="ui_ico11"){ ?> selected <?php } ?> >积分</option>
                            <option value="ui_ico12" <?php if($value['ico']=="ui_ico12"){ ?> selected <?php } ?> >说明</option>
                            <option value="ui_ico13" <?php if($value['ico']=="ui_ico13"){ ?> selected <?php } ?> >消费记录</option>
                            <option value="ui_ico14" <?php if($value['ico']=="ui_ico14"){ ?> selected <?php } ?> >优惠券</option>
                            <option value="ui_ico33" <?php if($value['ico']=="ui_ico33"){ ?> selected <?php } ?> >快速订房</option>
                            <option value="ui_ico34" <?php if($value['ico']=="ui_ico34"){ ?> selected <?php } ?> >客房订单</option>
                            <option value="ui_ico15" <?php if($value['ico']=="ui_ico15"){ ?> selected <?php } ?> >我的订单</option>
                            <option value="ui_ico16" <?php if($value['ico']=="ui_ico16"){ ?> selected <?php } ?> >我的收藏</option>
                            <option value="ui_ico17" <?php if($value['ico']=="ui_ico17"){ ?> selected <?php } ?> >我的权益</option>
                            <option value="ui_ico18" <?php if($value['ico']=="ui_ico18"){ ?> selected <?php } ?> >我的消息</option>
                            <option value="ui_ico19" <?php if($value['ico']=="ui_ico19"){ ?> selected <?php } ?> >排行榜</option>
                            <option value="ui_ico37" <?php if($value['ico']=="ui_ico37"){ ?> selected <?php } ?> >分销注册</option>
                            <option value="ui_ico20" <?php if($value['ico']=="ui_ico20"){ ?> selected <?php } ?> >分销中心</option>
                            <option value="ui_ico21" <?php if($value['ico']=="ui_ico21"){ ?> selected <?php } ?> >会员卡绑定</option>
                            <option value="ui_ico22" <?php if($value['ico']=="ui_ico22"){ ?> selected <?php } ?> >退出登录</option>
                            <option value="ui_ico23" <?php if($value['ico']=="ui_ico23"){ ?> selected <?php } ?> >预约订房</option>
                            <option value="ui_ico24" <?php if($value['ico']=="ui_ico24"){ ?> selected <?php } ?> >我的套票</option>
                            <option value="ui_ico35" <?php if($value['ico']=="ui_ico35"){ ?> selected <?php } ?> >套票订单</option>
                            <option value="ui_ico36" <?php if($value['ico']=="ui_ico36"){ ?> selected <?php } ?> >协议客</option>
                        </select>
                        <?php }?>
                    </td>
            	</tr>
            <?php } ?>
                <!-- <tr><td style="" colspan ="5"><strong>新增列表信息:</strong></td></tr> -->
                <tr>
                    <td>
                        <select name="group" class="form-control">
                            <option value="">--请选择分组--</option>
                            <option value="1">分组一</option>
                            <option value="2">分组二</option>
                            <option value="3">分组三</option>
                            <option value="4">分组四</option>
                            <option value="5">分组五</option>
                        </select>
                    </td>
                    <td><input type="text" name="modelname" value="" class="form-control" /></td>
                    <td>
                        <select name="modeltype" class="form-control">
                            <option value="">--请选择模块--</option>
                            <option value="<?php if(isset($public)) echo "http://".$public['domain']."/index.php/membervip/center?id=".$public['inter_id'];?>">会员资料</option>
                            <option value="2">购物商城</option>
                            <option value="3">我的分销</option>
                            <option value="4">我要订房</option>
                            <option value="5">积分商城</option>
                        </select>
                    </td>
                    <td><input type="text" name="link" value="" class="form-control" /></td>
                    <td>
                       <?php if($public['inter_id']=='a483407432'||$public['inter_id']=='a480930558'){?>
                          <select name="ico" class="form-control">
                            <option value="junting01" >我的身份</option>
                            <option value="junting02"  >酒店订单</option>
                            <option value="junting03"  >商城订单</option>
                            <option value="junting04"  >在线预定</option>
                            <option value="junting05"  >会员集市</option>
                            <option value="junting06"  >会员权益</option>
                            <option value="junting07"  >我的客服</option>
                            <option value="junting08"  >最新活动</option>
                            <option value="junting09"  >关于我们</option>
                    </select>
                    <?php }else {?>
                        <select name="ico" class="form-control">
                            <option value="ui_ico31">会员资料</option>
                            <option value="ui_ico1">会员卡</option>
                            <option value="ui_ico2">礼品商城</option>
                            <option value="ui_ico3">积分商城</option>
                            <option value="ui_ico4">储值卡</option>
                            <option value="ui_ico32">凤凰礼卡</option>
                            <option value="ui_ico5">二维码</option>
                            <option value="ui_ico6">我的地址</option>
                            <option value="ui_ico8">记录</option>
                            <option value="ui_ico9">会员权益</option>
                            <option value="ui_ico11">积分</option>
                            <option value="ui_ico12">说明</option>
                            <option value="ui_ico13">消费记录</option>
                            <option value="ui_ico14">优惠券</option>
                            <option value="ui_ico33">快速订房</option>
                            <option value="ui_ico34">客房订单</option>
                            <option value="ui_ico15">我的订单</option>
                            <option value="ui_ico16">我的收藏</option>
                            <option value="ui_ico17">我的权益</option>
                            <option value="ui_ico18">我的消息</option>
                            <option value="ui_ico19">排行榜</option>
                            <option value="ui_ico37">分销注册</option>
                            <option value="ui_ico20">分销中心</option>
                            <option value="ui_ico21">会员卡绑定</option>
                            <option value="ui_ico22">退出登录</option>
                            <option value="ui_ico23">预约订房</option>
                            <option value="ui_ico24">我的套票</option>
                            <option value="ui_ico35">套票订单</option>
                            <option value="ui_ico36">协议客</option>
                        </select>
                        <?php }?>
                    </td>
                </tr>
            </tbody>
            </table>
		</div>
		<div class="box-footer ">
            <button type="submit" class="btn btn-primary">保存</button>
		</div>
		<!-- /.box-footer -->
	<?php echo form_close() ?>
</div>
<!-- /.box -->
<!-- Horizontal Form -->

<!-- <div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">微信菜单配置</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-inline')); ?>
		<div class="box-body">
            
		</div>
		<div class="box-footer ">
            <button type="submit" class="btn btn-primary">保存</button>
		</div>
		<!-- /.box-footer -->
	<?php echo form_close() ?>
</div> -->
<!-- Horizontal Form -->

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
</body>
</html>
