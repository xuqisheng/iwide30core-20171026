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
        <h3 class="box-title">卡券统计</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-striped table-condensed dataTable no-footer">
        <thead>
            <tr>
                <th>总领取数量</th>
                <th>已使用数量</th>
                <th>已过期数量</th>
                <th>已核销数量</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $census['census_count']; ?></td>
                <td><?php echo $census['use_count']; ?></td>
                <td><?php echo $census['out_count']; ?></td>
                <td><?php echo $census['useoff_count']; ?></td>
            </tr>
        </tbody>
        </table>
    </div>
	<div class="box-header with-border">
		<h3 class="box-title">优惠券列表</h3>
	</div>
    <div class="box-body">
        <?php echo form_open(EA_const_url::inst()->get_url('*/*/card_user_info'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
        <table class="table table-bordered table-striped table-condensed dataTable no-footer">
            <input type="hidden" name="card_id" value="<?php echo $cardinfo['card_id']; ?>" />
            <thead>
                <tr>
                    <td>
                        <strong>快速搜索：</strong>
                        <input style="width:100%" name="search_char" value="<?php echo $search_char; ?>" class="form-cotrol" placeholder="搜索会员姓名、卡号、电话、邮箱、身份证、昵称等..." />
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search">查找</i></button>
                    </td>
                </tr>
            </thead>
        </table>
        <?php echo form_close() ?>
    </div>
	<div class="box-body">
        <table class="table table-bordered table-striped table-condensed dataTable no-footer">
        <thead>
        	<tr>
                <th>会员ID</th>
                <th>会员昵称</th>
        		<th>会员姓名</th>
        		<th>卡券名称</th>
                <th>领取来源</th>
                <th>领取时间</th>
        		<th>使用场景</th>
                <th>使用时间</th>
                <th>核销场景</th>
                <th>核销时间</th>
                <th>失效时间</th>
                <th>是否过期</th>
                <th>使用范围</th>
                <th>使用/核销备注</th>
        	</tr>
        </thead>
        <tbody>
        <?php foreach ($card_list as $key => $value) { ?>
            <tr>
                <td><?php echo $value['member_info_id']; ?></td>
                <td><?php echo $value['nickname']; ?></td>
                <td><?php echo $value['name']; ?></td>
                <td><?php echo $cardinfo['title']; ?></td>
                <td>
                    <?php if($value['receive_module']=="hotel"){ ?>
                        <span style="color:#26EC0E" >订房模块</span>
                    <?php }elseif($value['receive_module']=="vip"){ ?>
                        <span style="color:#26EC0E" >会员模块</span>
                    <?php }elseif($value['receive_module']=="shop"){ ?>
                        <span style="color:#26EC0E" >商城模块</span>
                    <?php }elseif($value['receive_module']=="soma"){ ?>
                        <span style="color:#26EC0E" >套票模块</span>
                    <?php }else{ ?>
                        未知来源
                    <?php }?>
                </td>
                <td>
                    <?php if($value['createtime']){ ?>
                        <?php echo date('Y-m-d H:i:s',$value['createtime']); ?>
                    <?php }else{ ?>
                        ------
                    <?php } ?>
                </td>
                <td>
                    <?php if($value['use_module']=="hotel"){ ?>
                        <span style="color:#E88927" >订房模块</span>
                    <?php }elseif($value['use_module']=="vip"){ ?>
                        <span style="color:#E88927" >会员模块</span>
                    <?php }elseif($value['use_module']=="shop"){ ?>
                        <span style="color:#E88927" >商城模块</span>
                    <?php }elseif($value['receive_module']=="soma"){ ?>
                        <span style="color:#E88927" >套票模块</span>
                    <?php }else{ ?>
                        未使用
                    <?php }?>
                </td>
                <td>
                    <?php if($value['use_time']){ ?>
                        <?php echo date('Y-m-d H:i:s',$value['use_time']); ?>
                    <?php }else{ ?>
                        ------
                    <?php } ?>
                </td>
                <td>
                    <?php if($value['useoff_module']=="hotel"){ ?>
                        <span style="color:#EA0041" >订房模块</span>
                    <?php }elseif($value['useoff_module']=="vip"){ ?>
                        <span style="color:#EA0041" >会员模块</span>
                    <?php }elseif($value['useoff_module']=="shop"){ ?>
                        <span style="color:#EA0041" >商城模块</span>
                    <?php }elseif($value['receive_module']=="soma"){ ?>
                        <span style="color:#EA0041" >套票模块</span>
                    <?php }else{ ?>
                        未核销
                    <?php }?>
                </td>
                <td>
                    <?php if($value['useoff_time']){ ?>
                        <?php echo date('Y-m-d H:i:s',$value['useoff_time']); ?>
                    <?php }else{ ?>
                        ------
                    <?php } ?>
                </td>
                <td>
                    <?php echo date('Y-m-d H:i:s',$value['expire_time']); ?>
                </td>
                <td>
                    <?php if($value['expire_time']>time() ){  ?>
                        <span style="color:#26EC0E" >否
                            <?php if(!$value['useoff_module']){ ?>
                                (未使用)
                            <?php }else{?>
                                (已使用)
                            <?php }?>
                        </span>
                    <?php }else{  ?>
                        <span style="color:#EA0041" >是
                            <?php if(!$value['useoff_module']){ ?>
                                (未使用)
                            <?php }else{?>
                                (已使用)
                            <?php }?>
                        </span>
                    <?php }?>
                </td>
                <td>
                    <?php foreach ($cardinfo['module'] as $k => $v){ ?>
                        <?php if($v=='vip'){ echo '会员/'; } ?>
                        <?php if($v=='hotel'){ echo '订房/'; } ?>
                        <?php if($v=='shop'){ echo '商城/'; } ?>
                        <?php if($v=='soma'){ echo '套票/'; } ?>
                    <?php } ?>

                </td>
                <td>
                    <?php echo $value['remark']; ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
        </table>
        <div class="btn-group">
            <button type="button" class="btn btn-default ">
                <a href="<?php echo EA_const_url::inst()->get_url('*/*/card_user_info?next_id='.$next_id.'&card_id='.$cardinfo['card_id']); ?>">
                    <i class="fa fa-edit"></i>&nbsp;
                    <?php if($next_id){ ?>
                        下一页
                    <?php }else{ ?>
                        第一页
                    <?php } ?>
                </a>
            </button>
        </div>
	</div>
		<!-- /.box-footer -->
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
