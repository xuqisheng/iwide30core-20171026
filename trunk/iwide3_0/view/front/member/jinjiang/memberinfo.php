<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>会员资料</title>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.min.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/example/example.css");?>"/>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/zepto.min.js");?>"></script>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/example.js");?>"></script>
    <script src="<?php echo base_url("public/member/version4.0/js/login.js");?>"></script>
    <style>
.weui_cell:before{width:0}
.weui_cells{padding-left:15px}
.weui_cells .weui_cell{padding-left:0; border-top:1px solid #e4e4e4;}
.weui_cells .weui_cell:first-child{border-top:0}
</style>
</head>
<body ontouchstart>
    <div class="vip_content" style="display:none;">
        <!--FROM DATA START-->
        <form id="SaveMemberInfo" action="<?php echo base_url("index.php/membervip/perfectinfo/save");?>" method="post" >
            <input type="hidden" name="smstype" value="0" />
        <div class="bd">
            <div class="weui_cells weui_cells_form">
                <?php if($modify_config['name']['show']){ ?>
                <div class="weui_cell name">
                    <div class="weui_cell_hd">
                        <label for='' class="weui_label"><?php echo $modify_config['name']['name']; ?>
                        </label>

                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" readonly type="<?php echo $modify_config['name']['type']; ?>" value="<?php if($centerinfo['name']!='微信用户'){echo $centerinfo['name'];} ?>" />
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <?php }?>
                <?php if($modify_config['phone']['show']){ ?>
                <div class="weui_cell phone">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $modify_config['phone']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" readonly type="<?php echo $modify_config['phone']['type']; ?>" value="<?php echo $centerinfo['cellphone'] ?>"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <?php }?>
                <?php if($modify_config['email']['show']){ ?>
                <div class="weui_cell email">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $modify_config['email']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" readonly type="<?php echo $modify_config['email']['type']; ?>" value="<?php echo $centerinfo['email'] ?>"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <?php }?>
                <?php if($modify_config['sex']['show']){ ?>
                <div class="weui_cell sex">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $modify_config['sex']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <select class="weui_input" name="sex" >
                            <option <?php if($centerinfo['sex']=="3" ||$centerinfo['sex']=="3"){ echo 'selected'; } ?> value="3" >请选择</option>
                            <option <?php if($centerinfo['sex']=="2"){ echo 'selected'; } ?> value="2" >女</option>
                            <option <?php if($centerinfo['sex']=="1"){ echo 'selected'; } ?> value="1" >男</option>
                        </select>
                    </div>
                </div>
                <?php }?>
                <?php if($modify_config['birthday']['show']){ ?>
                <div class="weui_cell birthday">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $modify_config['birthday']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" name="birthday" type="date" value="<?php echo date('Y-m-d',$centerinfo['birth']); ?>" pattern="<?php echo $modify_config['birthday']['regular']; ?>" >
                    </div>
                </div>
                <?php }?>
                <?php if($modify_config['idno']['show']){ ?>
                <div class="weui_cell idno">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $modify_config['idno']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" readonly type="<?php echo $modify_config['idno']['type']; ?>" value="<?php echo $centerinfo['id_card_no'] ?>" id="idno"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <script>
				var tmpval =  document.getElementById("idno").value;
				var val='';
				for( var i=0;i<tmpval.length-4;i++)val+=tmpval[i];
				val+='****';
				document.getElementById("idno").value=val;
				</script>
                <?php }?>
            </div>
            <div class="weui_cells_title"></div>
            <?php if($centerinfo['member_mode']==2){ ?>
            <div class="bd" style="width:62.5%; padding-top:20px;margin:auto;">
                <a href="javascript:;" class="weui_btn weui_btn_primary" style="background:#005bac">退出登录</a>
            </div>
            <?php } ?>
        </div>
        </form>

        <!--FROM DATA END-->
    </div>
    <!--Loading START-->
    <div id="loadingToast" class="weui_loading_toast" style="">
        <div class="weui_mask_transparent"></div>
        <div class="weui_toast">
            <div class="weui_loading">
                <div class="weui_loading_leaf weui_loading_leaf_0"></div>
                <div class="weui_loading_leaf weui_loading_leaf_1"></div>
                <div class="weui_loading_leaf weui_loading_leaf_2"></div>
                <div class="weui_loading_leaf weui_loading_leaf_3"></div>
                <div class="weui_loading_leaf weui_loading_leaf_4"></div>
                <div class="weui_loading_leaf weui_loading_leaf_5"></div>
                <div class="weui_loading_leaf weui_loading_leaf_6"></div>
                <div class="weui_loading_leaf weui_loading_leaf_7"></div>
                <div class="weui_loading_leaf weui_loading_leaf_8"></div>
                <div class="weui_loading_leaf weui_loading_leaf_9"></div>
                <div class="weui_loading_leaf weui_loading_leaf_10"></div>
                <div class="weui_loading_leaf weui_loading_leaf_11"></div>
            </div>
            <p class="weui_toast_content">努力加载中</p>
        </div>
    </div>
    <!--Loading END-->
    <!--dialog start -->
    <div class="weui_dialog_alert" id="dialog2" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd"><strong class="weui_dialog_title">操作提示</strong></div>
            <div class="weui_dialog_bd">当前账号或密码错误</div>
            <div class="weui_dialog_ft">
                <a href="javascript:;" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
    </div>
    <!--dialog end -->
    <script type="text/javascript">
        //通用JS
        $(function(){
            /* 等待加载 START */
            $('.vip_content').attr('style',"");
            $("#loadingToast").attr('style',"display:none;");
            /* 等待加载 END */
            /* OUTLOGIN START */
            $('.weui_btn_primary').click(function(){
                $.post("<?php echo base_url("index.php/membervip/login/outlogin");?>",
                   function(result){
                     if(result['err']>1){
                        $('.weui_dialog_bd').html(result['msg']);
                        $('#dialog2').attr('style','');
                    }else{
                        $('.weui_dialog_bd').html(result['msg']);
                        $('#dialog2').attr('style','');
                        window.location.href="<?php echo base_url('index.php/membervip/center');?>"; 
                    }
                   }, "json");
            })
            /* OUTLOGIN NED */
        });
        
    </script>
</body>
</html>
