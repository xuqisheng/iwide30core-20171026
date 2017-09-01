<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<!-- 新版本后台 v.2.0.0 -->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/version2-0-0.css'>
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
  <!--      <section class="content-header">
            <h1><?php echo isset($breadcrumb_array['action'])? $breadcrumb_array['action']: ''; ?>
                <small></small>
            </h1>
            <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>-->
        <!-- Main content -->
        <section class="content">

            <?php echo $this->session->show_put_msg(); ?>
            <?php //$pk= $model->table_primary_key(); ?>
            <!-- Horizontal Form -->
			<?php echo form_open('distribute/distribute_group/reward_add',array('id'=>'tosave','class'=>'','enctype'=>'multipart/form-data' ))?>

            <div class="whitetable">
                <div>
                    <span style="border-color:#3f51b5">基本配置</span>
                </div>
                <div class="bd_left list_layout">
                    <div>
                        <div>奖励名称</div>
                        <div class="input flexgrow">
                            <input type="text" name="reward_name" placeholder="奖励名称" value="<?php echo isset($posts['reward_name'])?$posts['reward_name']:''?>">
                        </div>
                    </div>
                    <div>
                        <div>关联分组</div>
                        <div class="input flexgrow">
                            <select name="group_id" id="group_id">
                                <option value="-1" >选择</option>
                                <?php if(!empty($group)){
                                        foreach($group as $k=>$v){
                                ?>
                                <option value="<?php echo $v['group_id']?>" <?php echo isset($posts['group_id'])&&$posts['group_id']== $v['group_id']?' selected ':''?>><?php echo $v['group_name']?></option>

                                <?php }}?>
                            </select>
                            <?php if(!empty($group)){
                                foreach($group as $kk=>$vv){
                                    ?>
                                    <input type="hidden" id="s_<?php echo $vv['group_id']?>" value="<?php echo date('Y-m-d',$vv['start_time'])?>"/>
                                    <input type="hidden" id="e_<?php echo $vv['group_id']?>" value="<?php echo date('Y-m-d',$vv['end_time'])?>"/>
                                    <input type="hidden" id="t_<?php echo $vv['group_id']?>" value="<?php echo $vv['check_type']?>"/>
                                <?php }}?>
                        </div>
                    </div>
                </div>
            </div>    
            <div class="whitetable">
                <div>
                    <span style="border-color:#3f51b5">规则配置</span>
                </div>
                <div class="bd_left list_layout">
                    <div>
                        <div>奖励类型</div>
                        <div class="input flexgrow">
                            <select name="reward_type" id="reward_type">
                                <option value="1" <?php echo isset($posts['reward_type'])&&$posts['reward_type']== 1?' selected ':''?>>全部订单计数奖励</option>
                                <option value="2" <?php echo isset($posts['reward_type'])&&$posts['reward_type']== 2?' selected ':''?>>超过部分计数奖励</option>
                                <option value="3" <?php echo isset($posts['reward_type'])&&$posts['reward_type']== 3?' selected ':''?>>不计数奖励</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div>核算单位</div>
                        <div class="input flexgrow">
                                <select name="reward_check" id="reward_check" disabled="disabled">
                                <option value="1" <?php echo isset($posts['reward_check'])&&$posts['reward_check']== 1?' selected ':''?>>按每个间夜奖励</option>
                                <option value="2" <?php echo isset($posts['reward_check'])&&$posts['reward_check']== 2?' selected ':''?>>按每个订单奖励</option>
                                <option value="3" <?php echo isset($posts['reward_check'])&&$posts['reward_check']== 3?' selected ':''?>>按每个订单交易额</option>
                            </select>
                        </div>

                    </div>
                    <div>
                        <div>核算值</div>
                        <div class="input flexgrow">
                            <input type="text" name="reward" placeholder="核算值" value="<?php echo isset($posts['reward'])?$posts['reward']:''?>">
                            <span id="hesuan" style="font-weight: bold">
                                <?php if(isset($posts['reward_check'])){
                                    ?>
                                    <?php if($posts['reward_check'] == 1){?>
                                    (元) 每个间夜奖励
                                    <?php }elseif($posts['reward_check'] == 2){?>
                                    (元) 每个订单奖励
                                    <?php }elseif($posts['reward_check'] == 3){?>
                                    (%) 交易额奖励
                                    <?php }
                                }?>
                            </span>
                        </div>
                    </div>
                    <div>
                        <div>开始时间</div>
                        <div class="input flexgrow">
                        <input disabled id="start_time" data-date-format="yyyy-mm-dd" type="text" name="start_time" aria-controls="data-grid" value="<?php echo isset($posts['start_time'])?$posts['start_time']:''?>">
                            </div>
                        </div>
                    <div>
                        <div>结束时间</div>
                        <div class="input flexgrow">
                        <input disabled id="end_time" data-date-format="yyyy-mm-dd" type="text" name="end_time" aria-controls="data-grid" value="<?php echo isset($posts['end_time'])?$posts['end_time']:''?>">
                        </div>
                    </div>

                    <div>
                        <div>名额上限</div>
                        <div class="input flexgrow">
                            <input type="text"  name="limit_count" placeholder="名额上限" value="<?php echo isset($posts['limit_count'])?$posts['limit_count']:''?>">
                        </div>
                    </div>
                    <div>
                        <div>状态</div>
                        <div class="input flexgrow">
                            <select name="status">
                                <option value="1" <?php echo isset($posts['status'])&&$posts['status']==1?' selected ':''?>>启用</option>
                                <option value="0" <?php echo isset($posts['status'])&&$posts['status']==0?' selected ':''?>>不启用</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
                <div class="bg_fff bd center pad10">
                     <button type="reset" class="bg_main button maright">清空</button>
                     <button class="bg_main button " type="submit" id="set_btn_save">保存</button>
                        <input name="ids" type="hidden" value="<?php echo isset($reward_id)?$reward_id:''?>">
                        <input name="submit" type="hidden" value="submit">
                </div>
                <?php echo form_close() ?>
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
</body>
</html>
<script>

    $(function(){
        $(".form_datetime").datepicker({format: 'yyyy-mm-dd'});
        var re_type = "<?php echo isset($posts['reward_type'])?$posts['reward_type']:0?>";
        if(re_type == 3){
            $("#check_form").hide();
            $("#hesuan").text('(元) 一次性奖励');
        }
        $('#group_id').change(function(){
            var val = $('#group_id').val();
            $("#start_time").val($("#s_"+val).val());
            $("#end_time").val($("#e_"+val).val());
            //每次改变分组 都出发
            $('#reward_type').val(1);
            $("#check_form").show();
            $('#reward_check').empty();
            if($("#t_"+val).val() == 1){
                $("#reward_check").append("<option value='1'>按每个间夜奖励</option>");
                $("#hesuan").text('(元) 每个间夜奖励');
            }else if($("#t_"+val).val() == 2){
                $("#reward_check").append("<option value='2'>按每个订单奖励</option>");
                $("#hesuan").text('(元) 每个订单奖励');
            }else if($("#t_"+val).val() == 3){
                $("#reward_check").append("<option value='3'>按交易额奖励</option>");
                $("#hesuan").text('(%) 交易额奖励');
            }
        });
        $('#reward_type').change(function(){
            var type = $(this).val();
            var val = $('#group_id').val();
            if(type == 3){//不计数奖励
                $("#check_form").hide();
                $("#hesuan").text('(元) 一次性奖励');
            }else{
                $("#check_form").show();
                if($("#t_"+val).val() == 1){
                    $("#hesuan").text('(元) 每个间夜奖励');
                }else if($("#t_"+val).val() == 2){
                    $("#hesuan").text('(元) 每个订单奖励');
                }else if($("#t_"+val).val() == 3){
                    $("#hesuan").text('(%) 交易额奖励');
                }
            }
        });
    });
    function sub(){
        if($("input[name='reward_name']").val() == ''){
            alert('奖励名不能为空');
            return false;
        }
        /*if($("input[name='begin_time']").val() == '' || $("input[name='end_time']").val() == ''){
            alert('日期不能为空');
            return false;
        }*/
        if($('#group_id').val() == '' || $('#group_id').val() == -1){
            alert('分组不能为空！');
            return false;
        }

        if($("input[name='reward']").val() == 0 || $("input[name='reward']").val() == '' || $("input[name='reward']").val() <0){
            alert('奖励核算值有误');
            return false;
        }
        if($("input[name='limit_count']").val() == 0 || $("input[name='limit_count']").val() == ''||$("input[name='limit_count']").val() <0){
            alert('名额上限数据有误');
            return false;
        }

        $('#tosave').form.submit();
    }
</script>
