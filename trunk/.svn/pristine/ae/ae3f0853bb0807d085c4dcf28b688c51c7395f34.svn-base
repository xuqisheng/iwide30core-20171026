<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    //全局变量
    var GV = {
        DIMAUB: "<?php echo base_url();?>",
        JS_ROOT: "<?php echo FD_PUBLIC ?>/js/",
    };
</script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/wind.js"></script>
<style type="text/css">
    .col-xs-12 .col-label{width: 100%;}.col-xs-12 .col-radio{display: inline-block;margin-left: 10px;}.col-radio-xs-12{width: 100%}
    .box-info-line{display: block;padding: 5px;border-bottom: 1px solid #f4f4f4;}.col-span{margin-left: 20px;}
    .form-inline .radio input[type=radio],.form-inline .radio input[type=checkbox]{margin-right: 10px;}
    .table .table-td-input{width:25%;}
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
          <h1><small></small></h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
      </section>

      <?php echo form_open(EA_const_url::inst()->get_url('*/*/save_rule'), array('class'=>'form-inline mysubform')); ?>
    <!-- Main content -->
    <section class="content">
        <?=$this->session->show_put_msg()?>
      <div class="row">
        <!-- right column -->
          <div class="col-xs-12">
              <div class="box box-primary">
                  <div class="box-header with-border">
                      <h3 class="box-title">会员间夜升级规则</h3>
                  </div>
                  <div class="box-body">
                    <table class="table table-bordered text-center table-condensed table-hover">
                        <thead>
                            <tr>
                                <th width="20%"><label>会员等级ID</label></th>
                                <th width="20%"><label>升级顺序</label></th>
                                <th width="20%"><label>会员名称</label></th>
                                <th width="20%"><label>升级间夜</label></th>
                                <th width="20%"><label>保级间夜</label></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($member_lvl as $vo):?>
                            <tr>
                                <td><?=$vo['member_lvl_id']?></td>
                                <td><input type="number" min="0" class="table-td-input" name="lvl_up_sort[<?=$vo['member_lvl_id']?>]" value="<?=$vo['lvl_up_sort']?>" /></td>
                                <td><?=$vo['lvl_name']?></td>
                                <td><input type="number" min="0" class="table-td-input" name="upgrade_night[<?=$vo['member_lvl_id']?>]" value="<?=!empty($vo['upgrade_night'])?$vo['upgrade_night']:''?>" /></td>
                                <td><input type="number" min="0" class="table-td-input" name="keep_night[<?=$vo['member_lvl_id']?>]" value="<?=!empty($vo['keep_night'])?$vo['keep_night']:''?>" /></td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                  </div>
              </div>
          </div>

          <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">升级间夜统计方式</h3>
            </div>
            <div class="box-body">
                <div class="form-group col-xs-12">
                    <label class="col-label">支付方式</label>
                    <div class="radio col-radio">
                        <label id="pay_code_all"><input type="checkbox" <?=$pay_code_checked?> name="pay_code_all" value="all" />全选</label>
                    </div>
                    <?php foreach ($pay_ways as $ko=>$item):?>
                    <?php $pay_checked = ''; if(is_array($pay_code)) $pay_checked = in_array($item['pay_type'],$pay_code)?'checked':'';?>
                    <div class="radio col-radio">
                        <label><input type="checkbox" class="checkbox-pay_code" <?=$pay_code_checked?><?=$pay_checked?> name="pay_code[]" value="<?=$item['pay_type']?>" /><?=$item['pay_name']?></label>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>

            <div class="box-info-line"></div>

            <div class="box-body">
                <div class="form-group col-xs-12">
                    <label class="col-label">价格代码</label>
                    <div class="radio col-radio">
                        <label id="price_code_all"><input type="checkbox" <?=$price_code_checked?> name="price_code_all" value="all" />全选</label>
                    </div>
                    <?php foreach ($price_codes as $ko=>$item):?>
                    <?php $price_checked = ''; if(is_array($price_code)) $price_checked = in_array($item['price_code'],$price_code)?'checked':'';?>
                    <div class="radio col-radio">
                        <label><input type="checkbox" class="checkbox-price_code" <?=$price_code_checked?><?=$price_checked?> name="price_code[]" value="<?=$item['price_code']?>"/><?=$item['price_name']?></label>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>

            <div class="box-info-line"></div>

              <div class="box-body">
                  <div class="form-group col-xs-12">
                      <label class="col-label">间夜统计人群</label>
                      <div class="radio col-radio-xs-12"><label><input type="radio" <?=$calculation==1?'checked':''?> name="calculation" value="1" />按本人入住计算</label><span class="col-span">本人在微信预订且本人入住并离店的订单计算间夜</span></div>
                      <div class="radio col-radio-xs-12 part_show_radio"><label><input type="radio" <?=$calculation_default_checked;?><?=$calculation==2?'checked':''?> name="calculation" value="2" />按预定人计算</label><span class="col-span">会员通过微信预订，且入住并离店的订单计算间夜（不限入住人）</span></div>
                  </div>
              </div>

              <div class="box-info-line"></div>

              <div class="box-body">
                  <div class="form-group col-xs-12">
                      <label class="col-label">间夜换算</label>
                      <div class="radio"><label><span>1个间夜=</span><input type="number" min="1" style="width: 20%" name="night" value="<?=!empty($night)?$night:''?>" />个升级间夜</label></div>
                  </div>
              </div>

              <div class="box-body" style="display: none;">
                  <div class="form-group col-xs-12">
                      <label class="col-label">升级后剩余的间夜量是否清零</label>
                      <div class="radio col-radio-xs-4"><label><input type="radio" <?=$isclear_default_checked;?><?=!empty($isclear) && $isclear==1?'checked':''?> name="isclear" value="1" />是</label><span class="col-span"></span></div>
                      <div class="radio col-radio-xs-4 part_show_radio"><label><input type="radio" <?=!empty($isclear) && $isclear==2?'checked':''?> name="isclear" value="2" />否</label><span class="col-span"></span></div>
                  </div>
              </div>
          </div>
        </div>      
        
        
        <div class="col-xs-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">
	              会员等级资格有效期
              </h3>
            </div>
              <div class="box-body">
                  <div class="form-group col-xs-12">
                      <div class="radio"><label><span>自达到该等级之日起</span><input type="number" min="1" style="width: 15%" name="expiremonth" value="<?=$expiremonth?>" />月内<span class="col-span">(购买的会员等级不可降级)</span></label></div>
                  </div>
              </div>
          </div>
        </div>   
       <div class="col-xs-12 ">
         <button type="submit" class="btn btn-primary dosave" style='margin-left: 40%'>保存</button>
         <label id='tips' style='color:red;'></label>
       </div>
      </div>
      <!-- /.row -->
    </section>
      <?php echo form_close() ?>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
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
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<!--<script src="--><?php //echo base_url(FD_PUBLIC) ?><!--/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>-->
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $(document).on('click','#pay_code_all',function (e) {
            e.preventDefault();
            if($("input[name=pay_code_all]").prop("checked")===true)
                $(this).parent().parent().find("input[type=checkbox]").prop("checked",false);
            else
                $(this).parent().parent().find("input[type=checkbox]").prop("checked",true);
        });

        $(document).on('click','#price_code_all',function (e) {
            e.preventDefault();
            if($("input[name=price_code_all]").prop("checked")===true)
                $(this).parent().parent().find("input[type=checkbox]").prop("checked",false);
            else
                $(this).parent().parent().find("input[type=checkbox]").prop("checked",true);
        });

        $(document).on('click','.checkbox-pay_code',function (e) {
            var totallen = $(".checkbox-pay_code").length;
            if($(this).prop("checked")===true){
                $(this).prop("checked",true);
            }else{
                $(this).prop("checked",false);
            }
            var _len = 0;
            $(".checkbox-pay_code").each(function (k,o) {
                if($(o).prop("checked")===true) _len++;
            });
            if(_len == totallen)
                $("input[name=pay_code_all]").prop("checked",true);
            else
                $("input[name=pay_code_all]").prop("checked",false);
        });

        $(document).on('click','.checkbox-price_code',function (e) {
            var totallen = $(".checkbox-price_code").length;
            if($(this).prop("checked")===true){
                $(this).prop("checked",true);
            }else{
                $(this).prop("checked",false);
            }
            var _len = 0;
            $(".checkbox-price_code").each(function (k,o) {
                if($(o).prop("checked")===true) _len++;
            });
            if(_len == totallen)
                $("input[name=price_code_all]").prop("checked",true);
            else
                $("input[name=price_code_all]").prop("checked",false);
        });

        Wind.use("ajaxForm","artDialog",function () {
            $(document).on('click', '.dosave', function (e) {
                e.preventDefault();
                var _this = this, ok_url = "<?php echo EA_const_url::inst()->get_url('*/*/');?>", btn = $(this);
                var form = $('.form-inline'), form_url = form.attr("action");
                //ie处理placeholder提交问题
                if ($.support.msie) {
                    form.find('[placeholder]').each(function () {
                        var input = $(this);
                        if (input.val() == input.attr('placeholder')) {
                            input.val('');
                        }
                    });
                }

                form.ajaxSubmit({
                    url: form_url,
                    dataType: 'json',
                    beforeSubmit: function (arr, $form, options) {
                        /*验证提交数据*/
                        var _null = false, _msg = '', inputos = $(".mysubform").find("input[type=number]"), _inputo = null;
                        for (i in inputos) {
                            var name = inputos[i].name, value = $.trim(inputos[i].value),type = inputos[i].type;
                            _inputo = inputos[i];
                            var reg = /^([+-])?[1-9]?[0-9]*\.[0-9]*$/;
                            switch (name){
                                case 'night':case 'expiremonth':
                                    var vo = reg.test(value);
                                    if ((!value && inputos[i].disabled === false) || vo) {
                                        _null = true;
                                        _msg = '';
                                    }
                                    break;
                                default:
                                    if(value && inputos[i].disabled === false && vo){
                                        _null = true;
                                        _msg = '';
                                    }
                                    break;
                            }
                            if (_null === true) break;
                        }

                        if (_null === true) {
                            $(_inputo).focus();
                            return false;
                        }
                        /*end*/

                        var text = btn.text();
                        btn.prop('disabled', true).addClass('disabled').text(text + '中...');
                    },
                    success: function (data) {
                        if (data.status == 1) {
                            btn.parent().append("<span style='color: #00b723;'>" + data.message + "</span>");
                            window.location.href=data.data.url;
                        } else {
                            btn.parent().append("<span style='color: #ff0040;'>" + data.message + "</span>");
                            setTimeout(function () {
                                btn.parent().find('span').fadeOut('normal', function () {
                                    btn.parent().find('span').remove();
                                });
                            }, 3000);
                        }
                    },
                    complete: function () {
                        var text = btn.text();
                        btn.prop('disabled', false).removeClass('disabled').text(text.replace('中...', ''));
                    },
                    error: function () {
                        btn.parent().append("<span style='color: #ff0040;'>请求异常,请刷新页面试试!</span>");
                        setTimeout(function () {
                            btn.parent().find('span').fadeOut('normal', function () {
                                btn.parent().find('span').remove();
                            });
                        }, 3000);
                    }
                });
            });
        });
    });
</script>
</body>
</html>
