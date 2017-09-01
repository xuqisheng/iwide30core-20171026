<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/ajaxForm.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/artDialog/lib/sea.js"></script>
<script type="text/javascript">
    var GV = {
        JS_ROOT:"<?php echo base_url(FD_PUBLIC) ?>/js/"
    };
</script>
<style type="text/css">.table-synchronize{float: left;width: 15%;}</style>
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
        <section class="content" style="position:relative;">
        
		<style>
        .fixed,.fixed2{position:absolute;top:18%;left:0px;width:100%;height:100%;padding:3%;z-index:821;}
        .bg_fff{background:rgba(255,255,255,1)}
		.j_box{border:2px solid #00c0ef;}
		.j_footer{padding-bottom:30px;}
		.face_film{position:fixed;height:100%;width:100%;top:0px;left:0px;background:rgba(0,0,0,0.5);z-index:820;}
        </style>
        <!--调整储值 START -->
        <div class="fixed"  style="display:none;">
            <?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_balance'), array('id'=>'EditBalanceInfo','class'=>'form-horizontal j_box bg_fff','enctype'=>'multipart/form-data')); ?>
                <input type="hidden" name="member_info_id" class="memberInfoId" value="" />
                <input type="hidden" name="openId" class="memberOpenid" value="" />
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">储值调整</h3>
                        <span class="glyphicon glyphicon-remove j_close" style="float:right;" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <div for="el_name" class="col-sm-2 col-xs-2 control-label text-right">会员ID:</div>
                    <div class="memberId"></div>
                </div>
                <div class="form-group row">
                    <div for="el_name" class="col-sm-2 col-xs-2 control-label text-right">会员卡号:</div>
                    <div class="memberCardNo"></div>
                </div>
                <div class="form-group row">
                    <div for="el_name" class="col-sm-2 col-xs-2 control-label text-right">会员姓名:</div>
                    <div class="memberName"></div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2 col-xs-2 control-label text-right">调整金额:</div>
                    <div class="col-sm-6  col-xs-6"  style="padding-left:0px;">
                        <input type="number" class="form-control" max="100000" style="width:90%;display:inline-block" name="amount" value="">元
                        <p style="color:#6e6e6e;font-size:12px;">只保留小数点后两位数，正数代表增加储值，负数代表减少储值；</p>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2 col-xs-2 control-label text-right">备注信息:</div>
                    <div class="col-sm-6  col-xs-6"  style="padding-left:0px;">
                        <textarea name ="note" style="width:90%;display:inline-block;height:240px;"></textarea>
                    </div>
                </div>
                <div class="j_footer row">
                    <div class="col-sm-6 col-xs-6 text-center">
                        <button type="button" class="preservation btn btn-info">保存</button>
                        <button type="reset" class="btn btn-default eliminate">清空</button>
                    </div>
                </div>
            <?php echo form_close() ?>
        </div>
        <!--调整储值 END -->
        <!--调整积分 START  -->
        <div class="fixed2"  style="display:none;">
            <?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_credit'), array('id'=>'EditCreditInfo','class'=>'form-horizontal j_box bg_fff','enctype'=>'multipart/form-data','method'=>'post')); ?>
                <input type="hidden" name="member_info_id" class="memberInfoId" value="" />
                <input type="hidden" name="openId" class="memberOpenid" value="" />
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">积分调整</h3>
                        <span class="glyphicon glyphicon-remove j_close" style="float:right;" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <div for="el_name" class="col-sm-2 col-xs-2 control-label text-right">会员ID:</div>
                    <div class="memberId"></div>
                </div>
                <div class="form-group row">
                    <div for="el_name" class="col-sm-2 col-xs-2 control-label text-right">会员卡号:</div>
                    <div class="memberCardNo"></div>
                </div>
                <div class="form-group row">
                    <div for="el_name" class="col-sm-2 col-xs-2 control-label text-right">会员姓名:</div>
                    <div class="memberName"></div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2 col-xs-2 control-label text-right">调整积分:</div>
                    <div class="col-sm-6  col-xs-6"  style="padding-left:0px;">
                        <input type="number" class="form-control" max="100000" style="width:90%;display:inline-block" name="amount" value="">元
                        <p style="color:#6e6e6e;font-size:12px;">正整数代表增加积分，负整数代表减少积分；</p>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2 col-xs-2 control-label text-right">备注信息:</div>
                    <div class="col-sm-6  col-xs-6"  style="padding-left:0px;">
                        <textarea name="note" style="width:90%;display:inline-block;height:240px;"></textarea>
                    </div>
                </div>
                <div class="j_footer row">
                    <div class="col-sm-6 col-xs-6 text-center">
                        <button type="button" class="preservation btn btn-info">保存</button>
                        <button type="reset" class="btn btn-default eliminate">清空</button>
                    </div>
                </div>
            <?php echo form_close() ?>
        </div>
        <!--调整储值 END -->
<?php echo $this->session->show_put_msg(); ?>

<!-- Horizontal Form -->
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">会员列表</h3>
	</div>
    <div class="box-body">
        <?php echo form_open(EA_const_url::inst()->get_url('*/*/index'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
        <table class="table table-bordered table-striped table-condensed dataTable no-footer">
            <thead>
                <tr>
                    <td>
                        <div class="table-synchronize">
                            <button type="button" data-action="<?php echo EA_const_url::inst()->get_url('*/*/synchronize_member_lvl');?>" class="btn btn-primary btn-sm synchronize"><i class="fa fa-cube">同步会员等级</i></button>
                        </div>
                        <strong class="">快速搜索：</strong>
                        <input style="width:58%" name="search_char" class="form-cotrol" placeholder="搜索会员姓名、卡号、电话、邮箱、身份证、昵称等..." />
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
                    <th>会员类型</th>
            		<th>会员名称</th>
                    <th>会员卡号</th>
                    <th>会员等级</th>
                    <th>会员积分</th>
                    <th>储值余额</th>
                    <th>有效卡券总数</th>
                    <th>是否冻结</th>
                    <th>是否登录</th>
                    <th>操作</th>
            	</tr>
            </thead>
            <thead>
                <?php foreach ($memberinfo as $key => $value) { ?>
                    <tr>
                        <td> <?php echo $value['member_info_id'] ?> </td>
                        <td> <?php echo $value['nickname'] ?> </td>
                        <td>
                            <?php if($value['member_mode']==1){ ?>
                                粉丝会员
                            <?php }else{ ?>
                                注册会员
                            <?php } ?>
                        </td>
                        <td> <?php echo $value['name'] ?> </td>
                        <td> <?php echo $value['membership_number'] ?> </td>
                        <td> <?php echo $value['member_lv_info'] ?> </td>
                        <td> <?php echo $value['credit'] ?> </td>
                        <td> <?php echo $value['balance']; ?> </td>
                        <td> <?php echo $value['card_count']; ?> </td>
                        <td>
                            <?php if($value['is_active']=='t'){ ?>
                                正常
                            <?php }else{ ?>
                                冻结/审核
                            <?php } ?>
                        </td>
                        <td>
                            <?php if($value['member_mode']==2){ ?>
                                <?php if($value['is_login']=='t'){ ?>
                                    <span style="color:#26EC0E" >已登录</span>
                                <?php }else{ ?>
                                    未登录
                                <?php } ?>
                            <?php }else{ ?>
                                默认登录
                            <?php } ?>
                        </td>
                        <td>
                        <a class="btn btn-default" href="<?php echo EA_const_url::inst()->get_url('*/*/add?member_info_id=').$value['member_info_id'].'&openid='.$value['open_id']; ?>">查看详细</a>
                        <button type="button" dataId="<?php echo $value['member_info_id']; ?>" dataOpenid="<?php echo $value['open_id']; ?>" dataCardNo="<?php echo $value['membership_number']; ?>" dataName="<?php echo $value['name']; ?>"  class="btn btn-default adjustment">调整储值</button>
                        <button type="button" dataId="<?php echo $value['member_info_id']; ?>" dataOpenid="<?php echo $value['open_id']; ?>" dataCardNo="<?php echo $value['membership_number']; ?>" dataName="<?php echo $value['name']; ?>"  class="btn btn-default integral">积分调整</button>

                        </td>
                    </tr>
                <?php } ?>
            </thead>

        </table>
        <div class="btn-group">
            <button type="button" class="btn btn-default ">
                <a href="<?php echo EA_const_url::inst()->get_url('*/*/index?last_member_info_id=').$last_member_info_id; ?>">
                    <i class="fa fa-edit"></i>&nbsp;
                    <?php if($last_member_info_id){ ?>
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

</div> -->
<div class="face_film" style="display:none;"></div>
<script>
$(function(){
    /*=================调整储值star===================*/
    var form1 = $("#EditBalanceInfo"),postUrl = $('#EditBalanceInfo').attr('action');
    form1.submit(function(){
        $.post( postUrl ,
            form1.serialize(),
            function(result,status){
                console.log(result);
                if(result['err']>=1){
                    alert(result['msg']);
                }else{
                    alert(result['msg']);
                    $('.j_close').click();
                    location.reload();
                }
            },'json');
        return false;
    });

	$('.adjustment').click(function(){
            $('.memberId').html( $(this).attr('dataId') );
            $('.memberCardNo').html( $(this).attr('dataCardNo') );
            $('.memberName').html( $(this).attr('dataName') );
            $('.memberInfoId').val( $(this).attr('dataId') );
            $('.memberOpenid').val( $(this).attr('dataOpenid') );
			$('.face_film,.fixed').fadeIn();
	});
	
	$('.fixed .preservation').click(function(){
	   form1.submit();
//	   $('.fixed,.face_film').fadeOut();
	});
    /*=================调整储值end===================*/

    /*=================调整积分star===================*/
    var form = $("#EditCreditInfo"),postUrl1 = $('#EditCreditInfo').attr('action');
    form.submit(function(){
        $.post( postUrl1 ,
            form.serialize(),
            function(result,status){
                if(result['err']>=1){
                    alert(result['msg']);
                }else{
                    alert(result['msg']);
                    $('.j_close').click();
                    location.reload();
                }
            },'json');
        return false;
    });

	$('.integral').click(function(){
        $('.memberId').html( $(this).attr('dataId') );
        $('.memberCardNo').html( $(this).attr('dataCardNo') );
        $('.memberName').html( $(this).attr('dataName') );
        $('.memberInfoId').val( $(this).attr('dataId') );
        $('.memberOpenid').val( $(this).attr('dataOpenid') );
        $('.face_film,.fixed2').fadeIn();
	});
	
	$('.fixed2 .preservation').click(function(){
		form.submit();
//		$('.fixed2,.face_film').fadeOut();
	});
    /*=================调整积分end===================*/

	$('.j_close').click(function(){
        form[0].reset();form1[0].reset();
		$('.fixed,.fixed2,.face_film').fadeOut();	
	});

    //按键触发
    $('.adjustment').keyup(function (event) {
        var keycode = event.which;
        switch (keycode){
            case 13:
                form1.submit();
                break;
            case 27:
                $('.j_close').click();
                break;
        }
    });

    $('.integral').keyup(function (event) {
        var keycode = event.which;
        switch (keycode){
            case 13:
                form.submit();
                break;
            case 27:
                $('.j_close').click();
                break;
        }
    });

    $(document).on('click','.synchronize',function (e) {
        e.preventDefault();
        var url = $(this).data('action'),obj=$(this),onthis = this,content = '确定要同步吗?';
        seajs.use([GV.JS_ROOT+'artDialog/src/dialog'], function (dialog){
            dialog({
                title:false,
                content:content,
                align:'right',
                btnStyle:'ui-dialog-mini',
                okValue: '确定',
                cancel: function () {
                    this.close();
                    onthis.focus(); //关闭时让触发弹窗的元素获取焦点
                    return true;
                },
                ok: function () {
                    var dthis = this;
                    var text = $.trim(obj.find('i').text());
                    obj.prop('disabled', true).addClass('disabled').find('i').text(text+'中...');
                    $.ajax({
                        url:url,
                        type:'get',
                        dataType:'json',
                        timeout:6000,
                        success: function (data) {
                            dthis.close();
                            if(data.status==1 && data.message=='ok'){
                                var d = dialog({
                                    content: '成功同步'+data.data+'条数据',quickClose:true
                                });
                                d.show(onthis);
                                setTimeout(function () {
                                    d.close().remove();
                                }, 3000);
                            }else if(data.status==1 && data.message=='complete'){
                                var d = dialog({
                                    content: '没有需要同步的用户',quickClose:true
                                });
                                d.showModal(onthis);
                                setTimeout(function () {
                                    d.close().remove();
                                }, 3000);
                            }else if(data.status==1 && data.message=='null'){
                                var d = dialog({
                                    content: '默认等级没有配置',quickClose:true
                                });
                                d.showModal(onthis);
                                setTimeout(function () {
                                    d.close().remove();
                                }, 3000);
                            }else{
                                var d = dialog({
                                    content: data.message?data.message:'请求失败,请刷新试试!',quickClose:true
                                });
                                d.showModal(onthis);
                                setTimeout(function () {
                                    d.close().remove();
                                }, 3000);
                            }
                            var text = obj.find('i').text();
                            obj.prop('disabled',false).removeClass('disabled').find('i').text(text.replace('中...', ''));
                        },
                        error: function (data) {
                            var d = dialog({
                                content: '请求异常,请刷新页面试试!',quickClose:true
                            });
                            d.showModal(onthis);
                            setTimeout(function () {
                                d.close().remove();
                            }, 3000);
                            var text = $.trim(obj.find('i').text());
                            obj.prop('disabled',false).removeClass('disabled').find('i').text(text.replace('中...', ''));
                        }
                    });
                },
                padding: 10,
                quickClose: true,
                cancelValue: '关闭'
            }).show(onthis);
        });
    });
});
</script>
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
