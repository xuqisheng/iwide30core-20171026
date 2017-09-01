<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet" />
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
    .box-body .table-striped>tbody>tr>td{background: #FFFFFF;vertical-align: middle;text-align: center;}
    .control-group{background: #fff;  padding: 10px; float: left;width: 100%;}
    .controls{float: left;padding: 5px;width: 30%;}.control-input{float: right;}
    .controls span{margin-left: 10%;}.control-group .title{}
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php /* 顶部导航 */ echo $block_top; ?>

    <?php /* 左栏菜单 */ echo $block_left; ?>

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
        <style>
        .controls1{float:left;padding:5px;}
		.m_r_75{margin-right:11px;}
		.m_r_20{margin-rgiht:20px;}
		.m_l_10{margin-left:10px;}
		.p_3{padding:3px !important;}
		.width_60{width:60px;}
		.min_w_80{width:100px;}
		.width_120{width:120px;display:inline-block;text-align:left;}
		.width_376{width:376px;}
		.f_notes{font-size:10px;color:#999999;float:left;padding-left:10px;}
        </style>
        <section class="content">
                <?php echo $this->session->show_put_msg(); ?>
            <!-- Horizontal Form -->
<!--                 <form id="form1"> -->
            <?php echo form_open(EA_const_url::inst()->get_url('*/*/disetting_edit'), array('class'=>'form-inline','enctype'=>'multipart/form-data')); ?>
                <input type="hidden" name="id" value="<?php if(isset($info['id'])) echo $info['id'];else echo 0;?>">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">活动页面设置</h3>
                </div>
                        <div class="box-body">
                            <table class="table table-bordered table-striped table-condensed dataTable no-footer">
                            <tbody>
                                <tr>
                                    <td class="min_w_80">页面标题</td>
                                    <td>
                                        <div class="control-group p_3">
                                            <div class="controls1 m_r_75">
                                                <input type="radio" <?php if($action_title=='default') echo 'checked';?> name="action_title" value="default" />
                                                <label class="">默认</label>
                                                <span class="m_r_20 m_l_10 width_120">"邀金"哪里跑</span>
                                            </div>
                                            <div class="controls1">
                                                <input type="radio" <?php if($action_title=='custom') echo 'checked';?> name="action_title" value="custom" />
                                                <label class="" >自定义</label>
                                                <div class="control-input m_l_10">
                                                    <input type="text" name="custom_title" placeholder="自定义页面标题" value="<?php echo $custom_title;?>"/>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                	<td class="min_w_80">活动正文</td>
                                    <td>
                                        <div class="controls1" style="width:90%">
                                            <script type="text/plain" id="steps" name="steps"><?php echo $steps;?></script>
                                        </div> 
                                    </td>
                                </tr>
                                <tr>
                                    <td class="min_w_80">个人战绩</td>
                                    <td>
                                        <div class="control-group p_3">
                                            <div class="controls1 m_r_75">
                                                <input type="radio" <?php if($action_center=='default') echo 'checked';?> name="action_center" value="default" />
                                                <label class="">默认</label>
                                                <span class="m_r_20 m_l_10 width_120">个人战绩</span>
                                            </div>
                                            <div class="controls1">
                                                <input type="radio" <?php if($action_center=='custom') echo 'checked';?> name="action_center" value="custom" />
                                                <label class="" >自定义</label>
                                                <div class="control-input m_l_10">
                                                    <input type="text" name="custom_center" placeholder="自定义" value="<?php echo $custom_center;?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="min_w_80">分享</td>
                                    <td>
                                        <div class="control-group p_3">
                                            <div class="controls1 m_r_75">
                                                <input type="radio" <?php if($action_toface=='default') echo 'checked';?> name="action_toface" value="default" />
                                                <label class="">默认</label>
                                                <span class="m_r_20 m_l_10 width_120">当面邀请</span>
                                            </div>
                                            <div class="controls1">
                                                <input type="radio" <?php if($action_toface=='custom') echo 'checked';?> name="action_toface" value="custom" />
                                                <label class="" >自定义</label>
                                                <div class="control-input m_l_10">
                                                    <input type="text" name="custom_toface" placeholder="自定义邀请" value="<?php echo $custom_toface;?>"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group p_3">
                                            <div class="controls1 m_r_75">
                                                <input type="radio" <?php if($action_share=='default') echo 'checked';?> name="action_share" value="default" />
                                                <label class="">默认</label>
                                                <span class="m_r_20 m_l_10 width_120">千里传音</span>
                                            </div>
                                            <div class="controls1">
                                                <input type="radio" <?php if($action_share=='custom') echo 'checked';?> name="action_share" value="custom" />
                                                <label class="" >自定义</label>
                                                <div class="control-input m_l_10">
                                                    <input type="text" name="custom_share" placeholder="自定义分享" value="<?php echo $custom_share;?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group p_3">
                                            <div class="controls1 width_60">标题:</div>
                                            <div class="controls1 width_376">
                                                <input type="text" style="width:100%;" name="title_share"  value="<?php echo $title_share;?>" placeholder="请输入分享标题">
                                            </div>                 											
                                        </div>
                                        <div class="control-group p_3">
                                            <div class="controls1 width_60">副标题:</div>
                                            <div class="controls1 width_376">
                                                <input type="text" style="width:100%;" name="title2_share"  value="<?php echo $title2_share;?>" placeholder="请输入分享副标题">
                                            </div>                 											
                                        </div>
                                    </td>
                                </tr> 
                            </tbody>
                        </table>
                     </div>
                     </div>
         <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">会员注册邀请语</h3>
                </div>
                        <div class="box-body">
                            <table class="table table-bordered table-striped table-condensed dataTable no-footer">
                            <tbody>
                                <tr>
                                    <td class="min_w_80">邀请语</td>
                                    <td>
                                        <div class="control-group p_3">
                                            <div class="controls1" style="width:440px">
                                                <script type="text/plain" id="invitation_toface" name="invitation_toface"><?php echo $invitation_toface;?></script>
                                            </div>                 											
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>分享推送LOGO</td>
                                    <td>
                                        <div class="control-group p_3">
                                            <div class="tv-thumb" style="float: left;width: 130px;text-align: center;">
                                                <input type="hidden" name="share_banner" id="share_banner" value="<?php if(isset($info['share_banner'])) echo $info['share_banner'];?>">
                                                <a class="thumb-row" href="javascript:void(0);" onclick="flashupload('thumb_images', 'banner上传','share_banner',thumb_images,'front,kiminvited,1,1024,jpg|gif|png');return false;">
                                                    <?php if(isset($info['share_banner']) && $info['share_banner'] ):?>
                                                        <img src="<?php echo $info['share_banner'];?>" id="share_banner_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;" />
                                                    <?php else:?>
                                                        <img src="<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png" id="share_banner_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;" />
                                                    <?php endif;?>
                                                </a>
                                                <input type="button" style="margin-top: 10px;color: #fff;font-weight: bold;" class="btn btn-small" onclick="$('#share_banner_preview').attr('src','<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png');$('#share_banner_preview').val('');return false;" value="取消图片">
                                            </div>
                                        </div>
                                        <div class="f_notes">建议图片大小为：200/200px,jpg格式</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                     </div>
                     </div>
         <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">新会员注册后通知页面</h3>
                </div>
                        <div class="box-body">
                            <table class="table table-bordered table-striped table-condensed dataTable no-footer">
                            <tbody>
                                <tr>
                                    <td class="min_w_80">背景图</td>
                                    <td>
                                        <div class="control-group p_3">
                                            <div class="tv-thumb" style="float: left;width: 130px;text-align: center;">
                                                <input type="hidden" name="notice_banner" id="notice_banner" value="<?php if(isset($info['notice_banner'])) echo $info['notice_banner'];?>">
                                                <a class="thumb-row" href="javascript:void(0);" onclick="flashupload('thumb_images', '背景图上传','notice_banner',thumb_images,'front,kiminvited,1,1024,jpg|gif|png');return false;">
                                                    <?php if(isset($info['notice_banner']) && $info['notice_banner'] ):?>
                                                        <img src="<?php echo $info['notice_banner'];?>" id="notice_banner_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                    <?php else:?>
                                                        <img src="<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png" id="notice_banner_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                    <?php endif;?>
                                                </a>
                                                <input type="button" style="margin-top: 10px;color: #fff;font-weight: bold;" class="btn btn-small" onclick="$('#notice_banner_preview').attr('src','<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png');$('#notice_banner_preview').val('');return false;" value="取消图片">
                                            </div>
                                        </div>
                                        <div class="f_notes">建议图片大小为：640/325px,jpg格式</div>
                                    </td>
                                </tr>
                                <tr>
                                	<td>获得优惠提示</td>
                                	<td>
                                        <div class="control-group p_3" style="text-align:left">
                                            <div class="controls1 width_376">
                                                <input type="text" style="width:100px;" name="value1" value="<?php echo $reg_value1;?>"  placeholder="你获得了">  <i>邀请人名称</i>
                                                <input type="text" style="width:150px;" name="value2"  value="<?php echo $reg_value2;?>" placeholder="赠送的38元会员卡">
                                            </div>                 											
                                        </div>
                                     </td>
                                </tr>
                                <tr>
                                    <td>关注提示</td>
                                    <td>
                                        <div class="control-group p_3" style="text-align:left">
                                            <div class="controls1" style="width: 50%;">
                                                <input type="text" style="width: 100%;" name="value3" value="<?php echo $reg_value3;?>"  placeholder="请扫二维码关注金房卡大酒店，在会员中心登陆后即可查看你获得的优惠。">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                     </div>
             	</div>
         <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">邀金中心</h3>
                </div>
                        <div class="box-body">
                            <table class="table table-bordered table-striped table-condensed dataTable no-footer">
                            <tbody>
                                <tr>
                                    <td class="min_w_80">活动积分</td>
                                    <td>
                                        <div class="control-group p_3">
                                            <div class="controls1 m_r_75">
                                                <input type="radio" <?php if($action_point=='default') echo 'checked';?> name="action_point" value="default" />
                                                <label class="">默认</label>
                                                <span class="m_r_20 m_l_10 width_120">活动邀金</span>
                                            </div>
                                            <div class="controls1">
                                                <input type="radio" <?php if($action_point=='custom') echo 'checked';?> name="action_point" value="custom" />
                                                <label class="" >自定义</label>
                                                <div class="control-input m_l_10">
                                                    <input type="text" name="point" placeholder="自定义活动积分" value="<?php echo $point;?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group p_3">
                                            <div class="controls1 width_60">副标题</div>
                                            <div class="controls1 width_376">
                                                <input type="text" style="width:100%;" name="point_detail" value="<?php echo $point_detail;?>" placeholder="查看明细">
                                            </div>                 											
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="min_w_80">我的奖励</td>
                                    <td>
                                        <div class="control-group p_3">
                                            <div class="controls1 m_r_75">
                                                <input type="radio" <?php if($action_reward=='default') echo 'checked';?> name="action_reward" value="default" />
                                                <label class="">默认</label>
                                                <span class="m_r_20 m_l_10 width_120">我的奖励</span>
                                            </div>
                                            <div class="controls1">
                                                <input type="radio" <?php if($action_reward=='custom') echo 'checked';?> name="action_reward" value="custom" />
                                                <label class="" >自定义</label>
                                                <div class="control-input m_l_10">
                                                    <input type="text" name="reward" placeholder="自定义我的奖励" value="<?php echo $reward;?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group p_3">
                                            <div class="controls1 width_60">副标题</div>
                                            <div class="controls1 width_376">
                                                <input type="text" style="width:100%;" name="reward_detail"  value="<?php echo $reward_detail;?>" placeholder="查看明细">
                                            </div>                 											
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>排行榜</td>
                                    <td>
                                        <div class="control-group p_3">
                                            <div class="controls1 m_r_75">
                                                <input type="radio" <?php if($action_rank=='default') echo 'checked';?> name="action_rank" value="default" />
                                                <label class="">默认</label>
                                                <span class="m_r_20 m_l_10 width_120">邀金榜</span>
                                            </div>
                                            <div class="controls1">
                                                <input type="radio" <?php if($action_rank=='custom') echo 'checked';?> name="action_rank" value="custom" />
                                                <label class="" >自定义</label>
                                                <div class="control-input m_l_10">
                                                    <input type="text" name="custom_rank" placeholder="自定义排行榜" value="<?php echo $custom_rank;?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group p_3">
                                            <div class="controls1 width_60">标题:</div>
                                            <div class="controls1 width_376">
                                                <input type="text" style="width:100%;" name="title_rank" value="<?php echo $title_rank;?>"  placeholder="请输入排行榜标题">
                                            </div>                 											
                                        </div>
                                        <div class="control-group p_3">
                                            <div class="controls1 width_60">副标题:</div>
                                            <div class="controls1 width_376">
                                                <input type="text" style="width:100%;" name="title2_rank"  value="<?php echo $title2_rank;?>" placeholder="请输入排行榜副标题">
                                            </div>                 											
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>宝典</td>
                                    <td>
                                        <div class="control-group p_3" >
                                            <div class="controls1 m_r_75">
                                                <input type="radio" <?php if($action_canon=='default') echo 'checked';?> name="action_canon" value="default" />
                                                <label class="">默认</label>
                                                <span class="m_r_20 m_l_10 width_120">邀金宝典</span>
                                            </div>
                                            <div class="controls1">
                                                <input type="radio" <?php if($action_canon=='custom') echo 'checked';?> name="action_canon" value="custom" />
                                                <label class="" >自定义</label>
                                                <div class="control-input m_l_10">
                                                    <input type="text" name="custom_canon" placeholder="自定义宝典" value="<?php echo $custom_canon;?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group p_3">
                                            <div class="controls1 width_60">标题</div>
                                            <div class="controls1 width_376">
                                                <input type="text" style="width:100%;" name="canon_title"  value="<?php echo $canon_title;?>" placeholder="请输入宝典标题">
                                            </div>                 											
                                        </div>
                                        <div class="control-group p_3">
                                            <div class="controls1 width_60">副标题</div>
                                            <div class="controls1 width_376">
                                                <input type="text" style="width:100%;" name="canon_title2" value="<?php echo $canon_title2;?>" placeholder="请输入宝典副标题">
                                            </div>                 											
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                	<td>活动宝典</td>
                                    <td>
                                        <div class="controls1" style="width:90%">
                                            <script type="text/plain" id="canon" name="canon"><?php echo $canon;?></script>
                                        </div> 
                                    </td>
                                </tr>
                                <tr>
                                	<td>活动说明</td>
                                    <td>
                                        <div class="controls1" style="width:90%">
                                            <script type="text/plain" id="description" name="description"><?php echo $description;?></script>
                                        </div> 
                                    </td>
                                </tr>
                                <tr>
                                    <td>Banner</td>
                                    <td>
                                        <div class="control-group p_3">
                                            <div class="tv-thumb" style="float: left;width: 130px;text-align: center;">
                                                <input type="hidden" name="banner" id="banner" value="<?php if(isset($info['banner'])) echo $info['banner'];?>">
                                                <a class="thumb-row" href="javascript:void(0);" onclick="flashupload('thumb_images', 'banner上传','banner',thumb_images,'front,kiminvited,1,1024,jpg|gif|png');return false;">
                                                    <?php if(isset($info['banner']) && $info['banner'] ):?>
                                                        <img src="<?php echo $info['banner'];?>" id="banner_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                    <?php else:?>
                                                        <img src="<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png" id="banner_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                    <?php endif;?>
                                                </a>
                                                <input type="button" style="margin-top: 10px;color: #fff;font-weight: bold;" class="btn btn-small" onclick="$('#banner_preview').attr('src','<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png');$('#banner_preview').val('');return false;" value="取消图片">
                                            </div>
                                        </div>
                                        <div class="f_notes">建议图片大小为：640/325px,jpg格式</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>背景图</td>
                                    <td>
                                        <div class="control-group p_3">
                                            <div class="tv-thumb" style="float: left;width: 130px;text-align: center;">
                                                <input type="hidden" name="background" id="background" value="<?php if(isset($info['background'])) echo $info['background'];?>">
                                                <a class="thumb-row" href="javascript:void(0);" onclick="flashupload('thumb_images', '背景图上传','background',thumb_images,'front,kiminvited,1,1024,jpg|gif|png');return false;">
                                                    <?php if(isset($info['background']) && $info['background'] ):?>
                                                        <img src="<?php echo $info['background'];?>" id="background_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                    <?php else:?>
                                                        <img src="<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png" id="background_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                    <?php endif;?>
                                                </a>
                                                <input type="button" style="margin-top: 10px;color: #fff;font-weight: bold;" class="btn btn-small" onclick="$('#background_preview').attr('src','<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png');$('#background').val('');return false;" value="取消图片">
                                            </div>
                                        </div>
                                        <div class="f_notes">建议图片大小为：640/1008px,jpg格式</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>活动说明<br>Banner</td>
                                    <td>
                                        <div class="control-group p_3">
                                            <div class="tv-thumb" style="float: left;width: 130px;text-align: center;">
                                                <input type="hidden" name="thumb_act" id="thumb_act" value="<?php if(isset($info['thumb_act'])) echo $info['thumb_act'];?>">
                                                <a class="thumb-row" href="javascript:void(0);" onclick="flashupload('thumb_images', '背景图上传','thumb_act',thumb_images,'front,kiminvited,1,1024,jpg|gif|png');return false;">
                                                    <?php if(isset($info['thumb_act']) && $info['thumb_act'] ):?>
                                                        <img src="<?php echo $info['thumb_act'];?>" id="thumb_act_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                    <?php else:?>
                                                        <img src="<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png" id="thumb_act_preview" style="width: 100%; cursor: hand;border: 2px solid #c0c0c0;"/>
                                                    <?php endif;?>
                                                </a>
                                                <input type="button" style="margin-top: 10px;color: #fff;font-weight: bold;" class="btn btn-small" onclick="$('#thumb_act_preview').attr('src','<?php echo base_url(FD_PUBLIC);?>/images/default-thumb.png');$('#thumb_act').val('');return false;" value="取消图片">
                                            </div>
                                        </div>
                                        <div class="f_notes">建议图片大小为：640/455px,jpg格式</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="100">
                                        <div class="" style="padding:10px 0;width:420px;">
                                            <button id="bnt_sub" type="submit" class="btn btn-primary dosave">保存</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                if(isset($public))
                                    $center_url = "http://".$public['domain']."/index.php/";
                                else
                                    $center_url ="#" ;
                            ?>
                            </tbody>
                            </table>
                        </div>
                        <!-- /.box-footer -->
<!--                 </form> -->
            </div>
                    <?php echo form_close() ?>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/content_addtop.js"></script>
    <script type="text/javascript">
        //编辑器路径定义
        var editorURL = GV.DIMAUB;
    </script>
    <script type="text/javascript" src="<?php echo base_url(FD_PUBLIC) ?>/js/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" src="<?php echo base_url(FD_PUBLIC) ?>/js/ueditor/ueditor.all.min.js"></script>
    <script type="text/javascript">
        $(function () {
            //编辑器
            var toolbars = [['fullscreen','source','|', 'bold', 'removeformat', 'pasteplain', '|','insertorderedlist', 'insertunorderedlist', 'selectall','emotion','date', 'time','drafts']];

            var toolbars1 = [['fullscreen','source','undo', 'redo', '|', 'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|', 'rowspacingtop', 'rowspacingbottom', 'lineheight', '|', 'fontfamily', 'fontsize', '|', 'directionalityltr', 'directionalityrtl', 'indent', '|', 'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|', 'simpleupload', 'emotion', 'map','pagebreak', 'background', '|', 'horizontal', 'date', 'time', 'spechars', '|', 'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', '|', 'print', 'searchreplace', 'help', 'drafts']];

            var editor1 = new baidu.editor.ui.Editor({toolbars:toolbars,maximumWords:200,elementPathEnabled:false,maxUndoCount:200});
            editor1.render('invitation_toface');
            var editor2 = new baidu.editor.ui.Editor({toolbars:toolbars1,elementPathEnabled:false});
            editor2.render('steps');
            var editor3 = new baidu.editor.ui.Editor({toolbars:toolbars1,elementPathEnabled:false});
            editor3.render('description');
            var editor4 = new baidu.editor.ui.Editor({toolbars:toolbars1,elementPathEnabled:false});
            editor4.render('canon');
            Wind.use("ajaxForm", function () {
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
                            var _null = false, _msg = '',$_inputo = null;
                            for (i in arr) {
                                var name = arr[i].name, value = $.trim(arr[i].value);
                                $_inputo = $("input[name='"+name+"']");
                                switch (name) {
                                    case 'invitation_toface':
                                        if (!value) {
                                            _null = true;
                                            _msg = '请输入邀请语';
                                        }
                                        break;
                                    case 'title_share':
                                        if (!value) {
                                            _null = true;
                                            _msg = '请输入分享标题';
                                        }
                                        break;
                                    case 'title_rank':
                                        if (!value) {
                                            _null = true;
                                            _msg = '请输入排行榜标题';
                                        }
                                        break;
                                    case 'canon_title':
                                        if (!value) {
                                            _null = true;
                                            _msg = '请输入宝典标题';
                                        }
                                        break;
                                }
                                if (_null === true) break;
                            }

                            if (_null === true) {
                                $_inputo.focus();
                                return false;
                            }
                            /*end*/
                            var text = btn.text();
                            btn.prop('disabled', true).addClass('disabled').text(text + '中...');
                        },
                        success: function (data) {
                            if (data.status == 1) {
                                var btnval = data.data.isadd === false ? '编辑' : '添加';
                                btn.parent().append("<span style='color: #3c8dbc;'>" + data.message + "</span>");
                                setTimeout(function () {
                                    btn.parent().find('span').fadeOut('normal', function () {
                                        btn.parent().find('span').remove();
                                    });
                                }, 3000);
                                window.location.reload();
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
    <?php /* Footer Block @see footer.php */ require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php'; ?>
    <?php /* Right Block @see right.php */ require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php'; ?>
</div><!-- ./wrapper -->
<?php /* Right Block @see right.php */ require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php'; ?>
</body>
</html>
