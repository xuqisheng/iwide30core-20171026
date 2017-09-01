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
<style>
label{font-weight:normal !important;}
.boxs input{text-indent:3px;margin-left:3px;}
.boxs >div{margin-bottom:15px;}
.start_up>div{display:inline-block;}
.s_btn{width:100px;text-align:center;padding:6px 0px;border-radius:5px;margin-right:8px;background:#ff9900;color:#fff;border:1px solid #ff9900;}
.s_txt{margin-right:20px;}
.s_radio label{margin-right:10px;}
.s_radio input{vertical-align:top;}
.boxs >div >span{margin-left:15px;}
.s_explain{font-size:12px;color:#89a3d6;}
.w_420{width:420px;}
.w_80{width:80px;}

</style>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="color:#92a0ae;">
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
                <!--<div class="box-header with-border">
                    <h3 class="box-title">签到设置</h3>
                </div>-->
                <div class="box-body">
                    <?php echo form_open(EA_const_url::inst()->get_url('*/*/ajax_post'), array('class'=>'form-horizontal','id'=>'conf_form')); ?>
                        <div class="boxs">
                            <div class="start_up">
                                <div class="s_txt">是否启动</div>
                                <div class="s_radio">
                                    <?php if(!empty($confInfo)): ?>
                                        <input type="hidden" name="id" value="<?php echo $confInfo['id']; ?>">
                                    <?php endif; ?>
                                    <label><input type="radio" name="is_active" value="1" <?php echo ($confInfo && $confInfo['is_active'] == 1) ? 'checked' : ''; ?>>启动</label>
                                    <label><input type="radio" name="is_active" value="0" <?php echo (!$confInfo || $confInfo['is_active'] == 0) ? 'checked' : ''; ?>>关闭</label>
                                </div>
                            </div>
                            <div>每日签到获得<input class="w_80" type="text" name="bonus_day" value="<?php echo $confInfo ? $confInfo['bonus_day'] : ''; ?>" placeholder=""> 积分</div>
                            <div>每连续签到7天,额外获得<input class="w_80" type="text" name="bonus_extra" value="<?php echo $confInfo ? $confInfo['bonus_extra'] : ''; ?>" placeholder=""> 积分<span>(积分自动送给用户)</span></div>
                            <div>连续签到说明<input class="w_420" type="text" name="serial_content" value="<?php echo $confInfo ? $confInfo['serial_content'] : ''; ?>" placeholder=""> <span>(界面展示)</span></div>
                            <div>连续签到奖励说明<input class="w_420" type="text" name="serial_reward_content" value="<?php echo $confInfo ? $confInfo['serial_reward_content'] : ''; ?>" placeholder=""><span>(界面展示)</span></div>
                            <div class="s_explain">•签到活动只有一个,只有启动或关闭</div>
                            <div><button class="s_btn" id="conf_submit">保存</button></div>
                        </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </section><!-- /.content -->
    </div>
    <!-- Horizontal Form -->
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
    $(function(){
        var $conf_form = $('#conf_form');
        var $conf_submit = $('#conf_submit');

        $conf_submit.on('click',function(){
            $.ajax({
                type: "post",
                url: "<?php echo base_url('index.php/membervip/sign/ajax_post'); ?>",
                data: $conf_form.serialize(),
                dataType: "json",
                success: function(data){
                    if(data.err){
                        alert(data.msg);
                        return false;
                    }

                    alert('保存成功');
                    location.reload();
                }
            });

            return false;
        });
    });
</script>
</body>
</html>
