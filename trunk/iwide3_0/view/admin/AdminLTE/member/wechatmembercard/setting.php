<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<head>
    <link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet"/>
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
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/content_addtop.js"></script>
    
<!-- 新版本后台 v.2.0.0 -->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/version2-0-0.css'>
<style>
    div[required]>div:first-child:before {
        content: '*';
        color: #f00
    }

    .addimg {
        margin-right: 15px
    }

    .flex_aligntop {
        padding-top: 5px
    }

    .warning {
        color: #f00
    }
    .quantity {position:fixed; width:100%; height:100%; z-index:999999; top:0; left:0;background: rgba(0,0,0,0.5);}
    .quantity .layer{background:#fff; border-radius:5px; margin:auto; width:300px; padding:20px 10px 10px 10px;}
    .quantity .layer>*{margin-bottom:10px}
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php /* 顶部导航 */
echo $block_top; ?>

<?php /*左栏菜单*/
echo $block_left; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">

<form id="myForm" action="<?php echo base_url('index.php/membervip/wxmember/saveconfig'); ?>" method="post">

<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
       value="<?php echo $this->security->get_csrf_hash(); ?>"/>

    <?php if(isset($qrc)){ ?>
        <div class="whitetable">
            <div>
                <span style="border-color:#3f51b5">领取地址</span>
            </div>
            <div class="bd_left list_layout">
                <div>
                    <div>超链接</div>
                    <div class="flexgrow">http://<?php echo $get_link;?></div>
                </div>
                <?php if(isset( $qrc['show_qrcode_url'])){?>
                <div>
                    <div>二维码</div>
                    <div class="flexgrow"><img src="<?php echo $qrc['show_qrcode_url'];?>" style="max-width: 150px;" /></div>
                </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>

<?php if(isset($error_msg)){ ?>
    <div class="whitetable">
        <div style="color:red"><?php echo $error_msg;?></div>
    </div>
<?php } ?>
<div class="whitetable">
<div>
    <span style="border-color:#3f51b5">激活方式 <b front="red">(创建后不可更改)</b></span>
</div>
<div class="bd_left list_layout">
    <div>
        <div><label class="check"><input class="activate_type" name="activate_type"  type="radio" value="pms_activate"  <?php  if(isset($base_info['brand_name'])) echo 'disabled ';?> <?php if( (!isset($wx_activate) && !isset($auto_activate)) || (!$wx_activate && !$auto_activate))  echo ' checked';?> /><span class="diyradio"><tt></tt></span>对接PMS</label></div>
        <div><label class="check"><input class="activate_type" name="activate_type"  type="radio" value="wx_activate" <?php  if(isset($wx_activate) && $wx_activate) echo 'checked '; if(isset($base_info['brand_name'])) echo 'disabled';?> /><span class="diyradio"><tt></tt></span>一键激活</label></div>
<!--        <div><label class="check"><input class="activate_type" name="activate_type"  type="radio" value="auto_activate"  --><?php // if(isset($base_info['brand_name']))echo 'disabled';?><!-- /><span class="diyradio"><tt></tt></span>自动激活</label></div>-->
    </div>
    <div  class="activate_select" style="display: none">
        <div>
            <div><label class="check"><input name="activate_select[]"  type="checkbox" value="telephone" checked disabled/><span class="diyradio"><tt></tt></span>手机号码</label></div>
        </div>
        <div class="activate_select">
            <div><label class="check"><input name="activate_select[]"  type="checkbox" value="name" checked  disabled /><span class="diyradio"><tt></tt></span>姓名</label></div>
        </div>
        <div class="activate_select">
            <div><label class="check"><input name="activate_select[]"  type="checkbox" value="sex" <?php  if(isset($base_info['brand_name']))echo 'disabled';?>  /><span class="diyradio"><tt></tt></span>性别</label></div>
        </div>
        <div class="activate_select">
            <div><label class="check"><input name="activate_select[]"  type="checkbox" value="email" <?php  if(isset($base_info['brand_name']))echo 'disabled';?>  /><span class="diyradio"><tt></tt></span>邮箱</label></div>
        </div>
        <div class="activate_select">
            <div><label class="check"><input name="activate_select[]"  type="checkbox" value="idNo"  <?php  if(isset($base_info['brand_name']))echo 'disabled';?> /><span class="diyradio"><tt></tt></span>身份证</label></div>
        </div>
    </div>

<!--    <div class="activate_select">-->
<!--        <div><label class="check"><input name="can_modify"  type="checkbox" value="can"  --><?php // if($wx_activate)echo 'disabled';?><!-- /><span class="diyradio"><tt></tt></span>可修改</label></div>-->
<!--    </div>-->
</div>
</div>
<div class="whitetable">
    <div>
        <span style="border-color:#3f51b5">基本信息</span>
        <input type="hidden" name="card_id" value="<?php if (isset($base_info)) echo $base_info['id']; ?>">
    </div>
    <div class="bd_left list_layout">
        <div required>
            <div>商户名称</div>
            <div class="flexgrow input"><input required name="base_info[brand_name]" placeholder="" value="<?php echo isset($base_info['brand_name']) ? $base_info['brand_name']:'';?>"
                    <?php  if(isset($base_info['brand_name']))echo 'disabled';?>></div>
        </div>
        <div required>
            <div>会员卡名称</div>
            <div class="flexgrow input"><input type="text" maxlength="9" required name="base_info[title]" placeholder="字数上限为9个汉字" value="<?php echo isset($base_info['title']) ? $base_info['title']:'';?>"></div>
        </div>


        <div required>
            <div>会员卡发行量</div>
            <div class="flexgrow input">
                <input  type="number" required name="base_info[sku][quantity]" placeholder="发行量上限100万" maxlength="6" value="<?php echo isset($base_info['sku']['quantity']) ? $base_info['sku']['quantity']:'';?>" <?php if(isset($base_info['sku']['quantity'])) echo 'disabled';?>>
            </div>
            <?php if(isset($base_info['sku']['quantity'])) {?>
            <span class="button void xs marbtm" onClick="$('.quantity').show();">存量修改请点击这里</span>
			<?php }?>
        </div>
        <div required>
            <div>会员卡logo</div>
            <div>
                <input type="hidden" required name="base_info[logo_url]" id="logo_url" value='<?php if(isset($base_info['logo_url']) && $base_info['logo_url'] != '') echo $local_logo_url; ?>'>
                <div id="add_logo" class="addimgs trim">
                <?php if (isset($base_info['logo_url']) && $base_info['logo_url'] != ''):?>
                    <div class="addimg candelete"><del></del><div><img src="<?php echo $local_logo_url; ?>"/></div></div>
                <?php endif;?>
                </div>
                <div id="add_logo_add"></div>
            </div>
            <div class="layoutfoot">缩略图尺寸：480*480</div>
        </div>
        <div required>
            <div>卡券背景</div>
            <div>
                <input type="hidden" required name="background_pic_url" id="bg_pic_url" value='<?php if (isset($background_pic_url) && $background_pic_url != '') echo $local_background_url; ?>'>
                <div id="bg_pic" class="addimgs trim">
                <?php if (isset($background_pic_url) && $background_pic_url != ''):?>
                    <div class="addimg candelete"><del></del><div><img src="<?php echo $local_background_url; ?>"/></div></div>
                <?php endif;?>
                </div>
                <div id="bg_pic_add"></div>
            </div>
            <div class="layoutfoot"><a target="_blank" href="https://mp.weixin.qq.com/cgi-bin/readtemplate?t=cardticket/card_cover_tmpl&type=info&lang=zh_CN">点击查看图片规范</a></div>
        </div>
    </div>
</div>


                <input type="hidden" name="base_info[date_info][type]" value="DATE_TYPE_PERMANENT"><!--永久有效-->
                <input type="hidden" name="base_info[color]" value="Color010"><!--颜色-->
                <input type="hidden" name="base_info[code_type]" value="CODE_TYPE_QRCODE"><!--展示方式-->
                <input type="hidden" name="discount" value="0"><!--会员卡优惠-->

                <?php /*二期
                <tr>
                    <td class="min_w_80">有效期</td>
                    <td>
                        <div class="control-group p_3">
                            <div class="controls1 m_r_75">
                                <input type="radio" name="date_info" checked value="DATE_TYPE_PERMANENT">
                                <label>永久有效</label>
                                <!--                                                    <input type="radio" name="date_info" checked value="DATE_TYPE_PERMANENT"> <label>固定时间</label>-->
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="min_w_80">可用时段</td>
                    <td>
                        <div class="control-group p_3">
                            <div class="controls1 m_r_75">
                                <input type="radio" name="" checked value="DATE_TYPE_PERMANENT"> <label>全部时段 </label>
                                <!--<input type="radio" name="date_info" checked value="DATE_TYPE_PERMANENT"> <label>部分时段</label>-->
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="min_w_80">会员卡优惠</td>
                    <td>
                        <div class="control-group p_3">
                            <div class="controls1 m_r_75">
                                <div>
                                    <input type="checkbox" name="" checked value=""> <label>积分优惠 </label>
                                    <input type="checkbox" name="" checked value=""> <label>折扣优惠 </label>
                                </div>
                            </div>
                            <!--积分优惠-->
                            <div>

                            </div>
                            <!--折扣优惠\-->
                            <div>

                            </div>
                        </div>
                    </td>
                </tr>
            */?>
<div class="whitetable custom_link_setting">
    <div>
        <span style="border-color:#3f51b5">快捷栏设置</span>
        <br/>（最多可选3项，创建后不可更改）
    </div>
    <div class="bd_left list_layout">
        <div>
            <div><label class="check"><input name="custom_field_check[]" value="supply_balance" type="checkbox" <?php if(isset($supply_balance) && $supply_balance) echo 'checked disabled';?> /><span class="diyradio"><tt></tt></span>选用</label></div>
            <div class="flex flexcenter flexgrow">
                <div class="input maright">余额（不可自定义配置，选用后不可撤销更改)</div>
                <div class="input maright"></div>
                <div class="input"></div>
            </div>
        </div>
        <div>
            <div><label class="check"><input name="custom_field_check[]"  type="checkbox" value="supply_bonus"  <?php if(isset($supply_bonus) && $supply_bonus) echo 'checked disabled';?> /><span class="diyradio"><tt></tt></span>选用</label></div>
            <div class="flex flexcenter flexgrow">
                <div class="input maright">积分（不可自定义配置，选用后不可撤销更改)</div>
                <div class="input maright"></div>
                <div class="input"></div>
            </div>
        </div>
        <div>
            <div><label class="check"><input name="custom_field_check[]" value="custom_field1" type="checkbox"  <?php if(isset($custom_field1) && !empty($custom_field1)  && !empty($custom_field1['name'])) echo 'checked disabled';?> /><span class="diyradio"><tt></tt></span>选用</label></div>
            <div class="flex flexcenter flexgrow">
                <div class="input maright">
                    <select name="custom_field1[name_type]">
                        <option value="FIELD_NAME_TYPE_LEVEL" <?php if(isset($custom_field1['name_type']) && $custom_field1['name_type']=='FIELD_NAME_TYPE_LEVEL') echo 'selected disabled';?> >等级</option>
                        <option value="FIELD_NAME_TYPE_COUPON" <?php if(isset($custom_field1['name_type']) && $custom_field1['name_type']=='FIELD_NAME_TYPE_COUPON') echo 'selected disabled';?>>优惠券</option>
                        <option value="">自定义</option>
                    </select>
                </div>
                <div class="input maright">
                    <input placeholder="名字，最多3个汉字" name="custom_field1[name]" value="<?php if(isset($custom_field1['name']) && !empty($custom_field1['name'])) echo $custom_field1['name'];?>" maxlength="3"/>
                </div>
                <div class="input"><input type="text" placeholder="链接" name="custom_field1[url]" value="<?php if(isset($custom_field1['url']) && !empty($custom_field1['url'])) echo $custom_field1['url'];?>" /></div>
            </div>
        </div>
        <div>
            <div><label class="check"><input  name="custom_field_check[]" value="custom_field2" type="checkbox"  <?php if(isset($custom_field2) && !empty($custom_field2) && !empty($custom_field1['name'])) echo 'checked disabled';?> /><span class="diyradio"><tt></tt></span>选用</label></div>
            <div class="flex flexcenter flexgrow">
                <div class="input maright">
                    <select name="custom_field2[name_type]">
                        <option value="FIELD_NAME_TYPE_LEVEL" <?php if(isset($custom_field2['name_type']) && $custom_field2['name_type']=='FIELD_NAME_TYPE_LEVEL') echo 'selected disabled';?> >等级</option>
                        <option value="FIELD_NAME_TYPE_COUPON" <?php if(isset($custom_field2['name_type']) && $custom_field2['name_type']=='FIELD_NAME_TYPE_COUPON') echo 'selected disabled';?>>优惠券</option>
                        <option value="">自定义</option>
                    </select>
                </div>
                <div class="input maright"><input placeholder="名字，最多3个汉字" name="custom_field2[name]" value="<?php if(isset($custom_field2['name']) && !empty($custom_field2['name'])) echo $custom_field2['name'];?>"  maxlength="3"/></div>
                <div class="input"><input type="text" placeholder="链接" name="custom_field2[url]" value="<?php if(isset($custom_field2['url']) && !empty($custom_field2['url'])) echo $custom_field2['url'];?>" /></div>
            </div>
        </div>
        <div>
            <div><label class="check"><input name="custom_field_check[]" value="custom_field3"  type="checkbox"  <?php if(isset($custom_field3) && !empty($custom_field3) && !empty($custom_field1['name'])) echo 'checked disabled';?> /><span class="diyradio"><tt></tt></span>选用</label></div>
            <div class="flex flexcenter flexgrow">
                <div class="input maright">
                    <select name="custom_field3[name_type]">
                        <option value="FIELD_NAME_TYPE_LEVEL" <?php if(isset($custom_field3['name_type']) && $custom_field3['name_type']=='FIELD_NAME_TYPE_LEVEL') echo 'selected disabled';?> >等级</option>
                        <option value="FIELD_NAME_TYPE_COUPON" <?php if(isset($custom_field3['name_type']) && $custom_field3['name_type']=='FIELD_NAME_TYPE_COUPON') echo 'selected disabled';?>>优惠券</option>
                        <option value="">自定义</option>
                    </select>
                </div>
                <div class="input maright"><input placeholder="名字，最多3个汉字" name="custom_field3[name]" value="<?php if(isset($custom_field3['name']) && !empty($custom_field3['name'])) echo $custom_field3['name'];?>"  maxlength="3"/></div>
                <div class="input"><input type="text" placeholder="链接" name="custom_field3[url]" value="<?php if(isset($custom_field3['url']) && !empty($custom_field3['url'])) echo $custom_field3['url'];?>" /></div>
            </div>
        </div>

    </div>
</div>
<div class="whitetable">
    <div>
        <span style="border-color:#3f51b5">按钮设置</span>
    </div>
    <div class="bd_left list_layout">
        <div>
            <div>按钮文字</div>
            <div class="flexgrow input"><input type="text" maxlength="6" name="base_info[center_title]" placeholder="最多显示6个汉字" value="<?php echo isset($base_info['center_title']) ? $base_info['center_title'] :'' ;?>"> <span class="warning">最多6个汉字</span></div>
        </div>
        <div>
            <div>提示语</div>
            <div class="flexgrow input"><input type="text" maxlength="8"  name="base_info[center_sub_title]" placeholder="入口下方的提示语" value="<?php echo isset($base_info['center_sub_title']) ? $base_info['center_sub_title'] :'' ;?>"> <span class="warning">最多8个汉字</span></div>
        </div>
        <div>
            <div>设置链接</div>
            <div class="flexgrow input"><input type="text" maxlength="128"  name="base_info[center_url]" placeholder="http://" value="<?php echo isset($base_info['center_url']) ? $base_info['center_url'] :'' ;?>"></div>
        </div>
    </div>
</div>

<div class="whitetable">
    <div>
        <span style="border-color:#3f51b5">会员卡详情</span>
    </div>
    <div class="bd_left list_layout">
        <div required>
            <div class="flex_aligntop">特权说明</div>
            <div class="flexgrow input"><textarea name="prerogative" placeholder="特权说明,300字以内"><?php if(isset($prerogative)) echo $prerogative;?></textarea></div>
        </div>
        <div required>
            <div class="flex_aligntop">使用须知</div>
            <div class="flexgrow input"><textarea name="base_info[description]" placeholder="使用须知,1000字以内"><?php if(isset($base_info['description'])) echo $base_info['description'];?></textarea></div>
        </div>
        <div required>
            <div class="flex_aligntop">使用说明</div>
            <div class="flexgrow input"><input type="text"   maxlength="9" name="base_info[notice]" placeholder="使用说明,9字以内" value="<?php if(isset($base_info['notice'])) echo $base_info['notice'];?>"/><span class="warning">最多9个汉字</span></div>
        </div>
    </div>
</div>


<div class="whitetable">
    <div>
        <span style="border-color:#3f51b5">入口一<br/>(选填，激活后可以见)</span>
    </div>
    <div class="bd_left list_layout">
        <div>
            <div class="flex_aligntop">入口名称</div>
            <div class="flexgrow input"><input type="text" maxlength="5" name="custom_cell1[name]" placeholder="限填5个字" value="<?php if(isset($custom_cell1["name"])) echo $custom_cell1["name"];?>"/><span class="warning">最多5个汉字</span></div>
        </div>
        <div>
            <div class="flex_aligntop">引导语</div>
            <div class="flexgrow input"><input type="text" maxlength="6" name="custom_cell1[tips]" placeholder="限填6个字"  value="<?php if(isset($custom_cell1["tips"])) echo $custom_cell1["tips"];?>"/><span class="warning">最多6个汉字</span></div>
        </div>
        <div>
            <div class="flex_aligntop">跳转地址</div>
            <div class="flexgrow input"><input type="text" name="custom_cell1[url]" placeholder="http://"  value="<?php if(isset($custom_cell1["tips"])) echo $custom_cell1["url"];?>"/></div>
        </div>
    </div>
</div>
<div class="whitetable">
    <div>
        <span style="border-color:#3f51b5">入口二<br/>(选填，激活后可以见)</span>
    </div>
    <div class="bd_left list_layout">
        <div>
            <div class="flex_aligntop">入口名称</div>
            <div class="flexgrow input"><input type="text" maxlength="5" name="custom_cell2[name]" placeholder="限填5个字"  value="<?php if(isset($custom_cell2["name"])) echo $custom_cell2["name"];?>"/><span class="warning">最多5个汉字</span></div>
        </div>
        <div>
            <div class="flex_aligntop">引导语</div>
            <div class="flexgrow input"><input type="text" maxlength="6" name="custom_cell2[tips]" placeholder="限填6个字"  value="<?php if(isset($custom_cell2["tips"])) echo $custom_cell2["tips"];?>"/><span class="warning">最多6个汉字</span></div>
        </div>
        <div>
            <div class="flex_aligntop">跳转地址</div>
            <div class="flexgrow input"><input type="text" name="custom_cell2[url]" placeholder="http://"/  value="<?php if(isset($custom_cell2["url"])) echo $custom_cell2["url"];?>"></div>
        </div>
    </div>
</div>

<div class="whitetable">
    <div>
        <span style="border-color:#3f51b5">营销入口<br/>(选填)</span>
    </div>
    <div class="bd_left list_layout">
        <div>
            <div class="flex_aligntop">入口名称</div>
            <div class="flexgrow input"><input type="text" maxlength="5" name="base_info[promotion_url_name]" placeholder="限填5个字" value="<?php if(isset($base_info["promotion_url_name"])) echo $base_info["promotion_url_name"];?>"/><span class="warning">最多5个汉字</span></div>
        </div>
        <div>
            <div class="flex_aligntop">引导语</div>
            <div class="flexgrow input"><input type="text" maxlength="6" name="base_info[promotion_url_sub_title]" placeholder="限填6个字"  value="<?php if(isset($base_info["promotion_url_sub_title"])) echo $base_info["promotion_url_sub_title"];?>"/><span class="warning">最多6个汉字</span></div>
        </div>
        <div>
            <div class="flex_aligntop">跳转地址</div>
            <div class="flexgrow input"><input type="text" name="base_info[promotion_url]" placeholder="http://"  value="<?php if(isset($base_info["promotion_url"])) echo $base_info["promotion_url"];?>"/></div>
        </div>
    </div>
</div>

<div class="bg_fff bd center pad10">
    <?php if(!isset($error_msg) || empty($error_msg)){ ?>
    <button class="bg_main button spaced dosave" type="submit" id="btn_sub"  >保存配置</button>
    <?php } ?>
</div>

</form>

<form id="quantityForm" action="<?php echo base_url('index.php/membervip/wxmember/modify_quantity'); ?>" method="post">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
           value="<?php echo $this->security->get_csrf_hash(); ?>"/>
    <input type="hidden" name="card_id" value="<?php if (isset($base_info)) echo $base_info['id']; ?>" id="cardId">
    <div class="quantity" onClick="$(this).hide()" style="display:none">
        <div class="layer" style="text-align:center" onClick="event.stopPropagation()">
            <div class="input"><input value="1" placeholder="数量" type="number" name="modifyNumber"></div>
            <div class="input">
                <select name="type" id="modifyType">
                    <option value="1" selected>增加</option>
                    <option value="2">减少</option>
                </select>
            </div>
            <div class="bg_main button" id="layer_btn" onClick="quantity(this)">保存修改</div>
        </div>
    </div>
</form>
    </section>
</div><!-- /.content-wrapper -->
			</div>
            </div>
            </div>
<!-- ./wrapper -->
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify_html5/jquery.uploadify.js"></script>
<script type="application/javascript">

<?php if(isset($base_info)){?>
$('input[name="custom_field_check[]"]').attr("disabled",true);
$('.custom_link_setting select').attr("disabled",true);
<?php }?>
function del_logo(){  //缩略图
	$(this).parent().remove();
	$("#logo_url").val('');
	$('#add_logo_add').show();
}
$('#add_logo_add').uploadify({//缩略图
	'formData'     : {
        '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
		'timestamp' : '<?php echo time();?>',
        'token'     : '<?php echo md5('unique_salt' . time());?>'
	},
	//'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
	'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
	'fileObjName': 'imgFile',
	'delimg':'<?php echo base_url(FD_PUBLIC) ?>/img/cancel.png',
	'buttonImage':"<?php echo base_url(FD_PUBLIC) ?>/js/img/add_xs.png",
	'fileTypeExts':'*.jpg;*.jpeg;*.gif;*.png',//文件类型
	'fileSizeLimit':'300', //限制文件大小
	'onUploadSuccess' : function(file, data) {
		var res = $.parseJSON(data);
		$('#logo_url').val(res.url);
		var dom = $('<div class="addimg candelete"><del></del><div><img src="'+res.url+'"/></div></div>');
		$("#add_logo").append(dom);
		dom.find('del').get(0).onclick=del_logo;
		$('#add_logo_add').hide();
	},   
	'onUploadError': function () {  
		alert('上传失败');  
	}
});
if($("#add_logo img").length>0){
    $("#add_logo del").get(0).onclick=del_logo;
    $('#add_logo_add').hide();
}

function delimg(){  //缩略图
	$(this).parent().remove();
	$("#bg_pic_url").val('');
	$('#bg_pic_add').show();
}
$('#bg_pic_add').uploadify({//缩略图
	'formData'     : {
        '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
		'timestamp' : '<?php echo time();?>',
        'token'     : '<?php echo md5('unique_salt' . time());?>'
	},
	//'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
	'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
	'fileObjName': 'imgFile',
	'delimg':'<?php echo base_url(FD_PUBLIC) ?>/img/cancel.png',
	'buttonImage':"<?php echo base_url(FD_PUBLIC) ?>/js/img/add_xs.png",
	'fileTypeExts':'*.jpg;*.jpeg;*.gif;*.png',//文件类型
	'fileSizeLimit':'300', //限制文件大小
	'onUploadSuccess' : function(file, data) {
		var res = $.parseJSON(data);
		$('#bg_pic_url').val(res.url);
		var dom = $('<div class="addimg candelete"><del></del><div><img src="'+res.url+'"/></div></div>');
		$("#bg_pic").append(dom);
		dom.find('del').get(0).onclick=delimg;
		$('#bg_pic_add').hide();
	},   
	'onUploadError': function () {  
		alert('上传失败');  
	}
});
if($("#bg_pic img").length>0){
    $("#bg_pic del").get(0).onclick=delimg;
    $('#bg_pic_add').hide();
}

$(function () {
    Wind.use("ajaxForm", function () {
        $(document).on('click', '.dosave', function (e) {
            $('#btn_sub').attr("disable",true);
            e.preventDefault();
            var form = $('#myForm');
            var form_url = form.attr("action");
//            console.log(form_url);
            form.ajaxSubmit({
                url: form_url,
                dataType: 'json',
                type: 'post',
                beforeSubmit: function (arr) {
//                    console.log(JSON.stringify(arr));

                },
                success: function (data) {
                    console.log(data);
                    $('#btn_sub').removeAttr("disable");
                    if (data.errcode == 0 && data.errmsg =="ok") {
                        alert('保存成功');
                        window.location.reload();
                    } else if(data.err >0 && data.msg !='' ){
                        alert(data.msg);
                    }else{
                        alert('保存失败');
                    }
                },
                error: function (XmlHttpRequest, textStatus, errorThrown) {
                    $('#btn_sub').removeAttr("disable");
                    console.log(textStatus);
                    console.log(XmlHttpRequest);
                    console.log(errorThrown);
                    alert('网络异常');
                }
            });
        });
    });


    $(document).on('click','.activate_type',function(e){
        if(this.value =='wx_activate'){
            $('.activate_select').css("display","");
        }else{
            $('.activate_select').css("display","none")
        }
    });
});
</script>
<?php /* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'commonjs.php'; ?>
</body>
</html>
<script>
    var saving = false;
    function quantity(that){
        if(saving){alert('正在执行，请稍候');return;}
        $(that).html('正在保存...');
        saving = true;
        var form = $('#quantityForm');
        var form_url = form.attr("action");
        form.ajaxSubmit({
            url: form_url,
            dataType: 'json',
            type: 'post',
            beforeSubmit: function (arr) {
//                    console.log(JSON.stringify(arr));

            },
            success: function (data) {
                console.log(data);
                $('#btn_sub').removeAttr("disable");
                if (data.errcode == 0 && data.errmsg =="ok") {
                    alert('保存成功');
                    $('.quantity').hide();
                    $(that).html('保存修改');
                    window.location.reload();
                } else if(data.err >0 && data.msg !='' ){
                    alert(data.msg);
                }else{
                    $('.quantity').hide();
                    $(that).html('保存修改');
                    alert('保存失败');
                }
            },
            error: function (XmlHttpRequest, textStatus, errorThrown) {
                $('#btn_sub').removeAttr("disable");
                console.log(textStatus);
                console.log(XmlHttpRequest);
                console.log(errorThrown);
                alert('网络异常');
            }
        });
    }
</script>