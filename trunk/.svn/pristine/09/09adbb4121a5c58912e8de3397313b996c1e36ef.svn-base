<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<!-- 新版本后台 v.2.0.0 -->

<!-- 文框-->
<script src="http://test008.iwide.cn/public/js/ueditor_f/ueditor.config.js"></script>
<script src="http://test008.iwide.cn/public/js/ueditor_f/ueditor.all.js"></script>
<!-- 搜索框-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<!-- 日历 -->
<link rel="stylesheet" href="http://test008.iwide.cn/public/AdminLTE/plugins/datetimepicker/bootstrap-datetimepicker.css">
<script src="http://test008.iwide.cn/public/AdminLTE/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="http://test008.iwide.cn/public/AdminLTE/plugins/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js"></script>

<!--日期控件-->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
<style>
    /*右上角删除按钮，父元素需要做relative*/
    .candelete{position:relative}
    .candelete del{
        text-decoration:none;
        cursor:pointer;
        position:absolute; top:-8px; right:-8px; z-index:10;
    }
    .candelete del:after{
        content: "\f057";
        font: normal normal normal 14px/1 FontAwesome;
        font-size: 16px;
        text-rendering: auto;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    .candelete:hover del:after{color:#f00}

    /*	上传图片 */
    .addimg{display:inline-block; border:1px solid #dae0e9;}
    .addimg>div{ overflow:hidden; position:relative; width:120px; height:120px;}
    /*.addimg{ background:url(../img/add.png) center center no-repeat; background-size:59%}*/
    .addimg img{position:absolute;width:100%; min-height:100%; left:0; z-index:5}
    .addimg input{ position:absolute; left:100%;top:100%; z-index:0; opacity:0}
    #show_time_range li{
        float: left;margin-right: 10px;}

    #save {cursor: pointer;}

    .whitetable{ display:table; width:100%; }  /*表格排版*/
    .whitetable>*{ display:table-cell; vertical-align:middle; padding:15px;}
    .whitetable>*:first-child{text-align:center;width:20%}
    .whitetable>*:first-child>*{ padding:5px 7px; line-height:1; border-left:4px solid; display:inline-block}

    .list_layout>*{ display:flex; display: -webkit-flex;align-items:center; flex-wrap:wrap; }  /*列表排版*/
    .list_layout>*>*{ margin:0 7px;flex-shrink:0}
    .list_layout>* .layoutfoot{padding-left:103px; color:#d4d7db; width:100%;} /*备注文字的样式*/
    .list_layout>*>*:nth-child(2){max-width:400px}
    .list_layout>*>*:first-child{text-align:right;width:90px;}
    .list_layout>*>*:last-child{flex-shrink:1}
    .list_layout>*:last-child{ padding-bottom:0}

    .list_layout .ticket_credits{
       margin-top: 10px;}

    .list_layout .input input{
        line-height: 30px;
        padding: 0 10px;
        border: 1px solid #d9dfe9;
        height: 30px;
    }
    .batch_setting_btn,.one_setting_btn{cursor: pointer}
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
    <!-- 新版本后台 jie_h -->
    <link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/jie_h.css'>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="padding_20 color_333 font_12">
            <?php if(isset($ids)){?>
                <?php echo form_open( site_url('ticket/goods/edit?ids='.$ids), array('class'=>'form-horizontal','enctype'=>'multipart/form-data' ), array() ); ?>
            <?php }else{?>
                <?php echo form_open( site_url('ticket/goods/add'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data' ), array() ); ?>
            <?php }?>
                <div class="bg_fff padding_t_30" style="padding-bottom:40px;">
                    <div class="j_item margin_bottom_30">
                        <div class="flex row_line center padding_right_30 font_14 color_bfbfbf margin_bottom_25">
                            <div class=" width_100 text_right margin_right_15">基础信息</div>
                        </div>
                        <div class="j_tiem_conter">
                            <div class="flex between margin_bottom_25">
                                <div class="flex_1 ">
                                    <div class="flex">
                                        <div class="width_100 text_right margin_right_15">所属店铺</div>
                                        <div class="">
                                            <input type="hidden" name="hotel_id" id="hotel_id" value="<?php echo isset($posts['hotel_id'])?$posts['hotel_id']:''?>"/>
                                            <select name="shop_id" id="shop_id" class="selectpicker radius_3 height_30 bg_fff" data-live-search="true">
                                                <option value="">选择店铺</option>
                                                <?php if(!empty($shops)):?>
                                                    <?php foreach($shops as $k=>$v):?>
                                                        <option value="<?php echo $v['shop_id'];?>" iid="<?php echo $v['hotel_id']?>" <?php if(isset($posts['shop_id'])&&$posts['shop_id']==$v['shop_id']){?>selected="selected"<?php }?>><?php echo $v['shop_name'];?></option>
                                                    <?php endforeach;?>
                                                <?php endif;?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_1 flex">
                                    <div class="flex">
                                        <div class="width_100 text_right margin_right_15">商品名称</div>
                                        <div><input class="width_300 radius_3 border_eee_1 height_30 text_indent_3" required type="text" name="goods_name" value="<?php echo isset($posts['goods_name'])?$posts['goods_name']:''?>"/></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex between margin_bottom_25">

                                <div class="flex_1">
                                    <div class="flex">
                                        <div class="width_100 text_right margin_right_15">消费时段</div>
                                        <div>
                                            <ul id="show_time_range">
                                                <?php
                                                if (!empty($shop_sale_time))
                                                {
                                                    foreach ($shop_sale_time as $key => $value)
                                                    {
                                                ?>
                                                        <li>
                                                            <input class="none" type="checkbox" id="sale_time_<?php echo $key;?>" <?php echo $value['checked'] == 1 ? 'checked=""yes': '';?> name="ticket_sale_time[]" value="<?php echo $value['name'];?>"/>
                                                            <label class="margin_right_35" for="sale_time_<?php echo $key;?>">
                                                                <span class="diycheckbox sub"></span>
                                                                <span><?php echo $value['name'];?></span>
                                                            </label>
                                                        </li>
                                                <?php

                                                    }
                                                }
                                                ?>
                                            </ul>

                                        </div>
                                    </div>
                                </div>

                                <div class="flex_1">
                                    <div class="flex">
                                        <div class="width_100 text_right margin_right_15">展位推荐</div>
                                        <div>
                                            <input class="none" type="radio" id="yes" name="is_recommend" value="1" <?php if(isset($posts['is_recommend'])&&$posts['is_recommend'])echo 'checked';?> />
                                            <label class="margin_right_35" for="yes">
                                                <span class="diyradio"><tt></tt></span>
                                                <span>是</span>
                                            </label>
                                            <input class="none" type="radio" id="no" name="is_recommend" value="0" <?php if(isset($posts['is_recommend'])&&$posts['is_recommend']==0 || !isset($ids))echo 'checked';?>/>
                                            <label for="no">
                                                <span class="diyradio"><tt></tt></span>
                                                <span>否</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex between margin_bottom_25">
                                <div class="flex_1">
                                    <div class="flex">
                                        <div class="width_100 text_right margin_right_15">商品分组</div>
                                        <div>
                                            <div class="flex ">
                                                <select class="min_width_152 radius_3 border_eee_1 height_30" name="group_id" id="group_id">
                                                    <option value="0">请选择分组</option>
                                                    <?php if(!empty($group)):?>
                                                        <?php foreach($group as $k=>$v):?>
                                                            <option value="<?php echo $v['group_id'];?>" <?php if(isset($posts['group_id'])&&$posts['group_id']==$v['group_id']){?>selected="selected"<?php }?>><?php echo $v['group_name'];?></option>
                                                        <?php endforeach;?>
                                                    <?php endif;?>
                                                </select>
                                                <!-- <div class="margin_right_15"><img src="13213.jpg"></div>
                                                <div class="flex padding_0_8 height_30 font_14 color_b69b69 radius_3 border_eee_1 bg_f6f6f6">+ 新建分组</div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--
                                <div class="flex_1 flex">
                                    <div class="flex">
                                        <div class="width_100 text_right margin_right_15">商品原价</div>
                                        <div><input class="width_300 radius_3 border_eee_1 height_30 text_indent_3" required type="text" name="shop_price" value="<?php echo isset($posts['shop_price'])?$posts['shop_price']:''?>"/></div>
                                    </div>
                                </div>
                                -->
                            </div>

                            <div class="flex  margin_bottom_25">
                                <div class="width_100 text_right margin_right_15">商品副标</div>
                                <div><input style="width: 550px;" class=" width_300 radius_3 border_eee_1 height_30 text_indent_3" required="" type="text" name="goods_alias" value="<?php echo isset($posts['goods_alias'])?$posts['goods_alias']:''?>"></div>
                            </div>
                        </div>
                    </div>
                    <div class="j_item margin_bottom_30">
                        <div class="flex row_line center padding_right_30 font_14 color_bfbfbf margin_bottom_25">
                            <div class=" width_100 text_right margin_right_15">库存／规格</div>
                        </div>
                        <div class="j_tiem_conter">
                            <div class="flex between margin_bottom_25">
                                <div class="flex_1">
                                    <div class="flex">
                                        <div class="width_100 text_right margin_right_15">商品规格</div>
                                        <div>
                                            <div class="aa_norms pointer flex padding_0_8 height_30 font_14 color_b69b69 radius_3 border_eee_1 bg_f6f6f6">+ 添加规格</div>
                                        </div>
                                        <label style="margin-left: 10px;color:red;" class="flex">注意：先添加好规格再设置价格日历</label>
                                    </div>
                                </div>
                            </div>
                            <div class="flex wrap norms_list specifications_con">
                            <?php
                                if (!empty($spu))
                                {
                                    foreach ($spu as $key=>$value)
                                    {
                            ?>
                                <div class="width_50 spu_list">
                                    <div class="flex margin_bottom_25 specifications">
                                        <div class="spec_name width_100 text_right margin_right_15">规格<?php echo $key+1?>:</div>
                                        <div class="">
                                            <span>规格名</span><input
                                                class="spu_name width_130 radius_3 border_eee_1 height_30 text_indent_3"
                                                name="spu[id_<?php echo $value['spu_id']?>]" del-data = "<?php echo $value['spu_id']?>" price-data="" stock-data="" required type="text" value="<?php echo $value['spu_name']?>"/>
                                            <span> 原价</span>
                                            <input
                                                class="prime_price width_130 radius_3 border_eee_1 height_30 text_indent_3"
                                                name="prime_price[id_<?php echo $value['spu_id']?>]" equired type="text" value="<?php echo $value['prime_price']?>"/>
                                            <i class="iconfonts cursor none font_16 margin_left_15">&#xe60b;</i>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                }
                            }
                            ?>
                            </div>
                            <div class="flex">
                                <div class="width_100 text_right margin_right_15">商品编码</div>
                                <div><input class="width_300 radius_3 border_eee_1 height_30 text_indent_3" required type="text" name="goods_sn" value="<?php echo isset($posts['goods_sn'])?$posts['goods_sn']:''?>"/></div>
                            </div>
                        </div>
                    </div>
                    <div class="j_item margin_bottom_30">
                        <div class="flex row_line center padding_right_30 font_14 color_bfbfbf margin_bottom_25">
                            <div class="width_100 text_right margin_right_15">价格日历</div>
                        </div>
                        <div class="j_tiem_conter">
                            <div class="flex between margin_bottom_25">
                                <div class="flex_1">
                                    <div class="flex">
                                        <div class="width_100 text_right margin_right_15">价格日历</div>
                                        <div class="batch_setting pointer flex padding_0_8 height_30 font_14 color_b69b69 radius_3 border_eee_1 bg_f6f6f6">批量设置</div>
                                    </div>
                                </div>
                            </div>
                            <div class="padding_left_50 padding_right_30">
                                <div class="">
                                    <table class="date_select" style="width:100%;"  border="1" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th colspan="7">
                                                    <i class="d_last iconfonts radius_50 border_bfbfbf_1 cursor">&#xe68b;</i>
                                                    <span class="font_14 date_txt">2017  2月</span>
                                                    <i class="d_next iconfonts radius_50 border_bfbfbf_1 cursor">&#xe65b;</i>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="bg_f8fafc">
                                                <td>SUN 日</td>
                                                <td>MON 一</td>
                                                <td>TUE 二</td>
                                                <td>WED 三</td>
                                                <td>THU 四</td>
                                                <td>FRI 五</td>
                                                <td>SAT 六</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="j_item margin_bottom_30">
                        <div class="flex row_line center padding_right_30 font_14 color_bfbfbf margin_bottom_25">
                            <div class=" width_100 text_right margin_right_15">提前预约优惠</div>
                        </div>
                        <div class="j_tiem_conter">
                            <div class="flex between margin_bottom_25">
                                <div class="flex_1">
                                    <div class="flex">
                                        <div class="width_100 text_right margin_right_15">提前预约优惠</div>
                                        <div>
                                            <input class="none" type="radio" id="yes1" name="ticket_credits" onclick="change_ticket($(this).val())" value="1" <?php if(isset($posts['ticket_credits'])&&$posts['ticket_credits']==1 || empty($posts['ticket_credits']))echo 'checked';?>  />
                                            <label class="margin_right_35" for="yes1">
                                                <span class="diyradio"><tt></tt></span>
                                                <span>无效</span>
                                            </label>
                                            <input class="none" type="radio" id="no1" name="ticket_credits" onclick="change_ticket($(this).val())" value="2" <?php if(isset($posts['ticket_credits'])&&$posts['ticket_credits']==2)echo 'checked';?> />
                                            <label for="no1">
                                                <span class="diyradio"><tt></tt></span>
                                                <span>有效</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="bd_left list_layout">
                                        <div class="ticket_credits" <?php if(isset($posts['ticket_credits']) && $posts['ticket_credits']==1 || empty($posts['ticket_credits'])){?>style="display: none;"<?php }?>>
                                            <div>提前天数</div>
                                            <div class="input"><input name="ticket_day" value="<?php echo isset($posts['ticket_day'])?$posts['ticket_day']:'0'?>"><span>天</span></div>
                                        </div>
                                        <div class="ticket_credits" <?php if(isset($posts['ticket_credits']) && $posts['ticket_credits']==1 || empty($posts['ticket_credits'])){?>style="display: none;"<?php }?>>
                                            <div class="width_100 text_right margin_right_15">优惠方式</div>
                                            <div>
                                                <input class="none" type="radio" id="ticket_style1" name="ticket_style" onclick="change_ticket_style($(this).val())" type="radio" value="1" <?php if(isset($posts['ticket_style'])&&$posts['ticket_style']==1 || !isset($ids))echo 'checked';?> />
                                                <label class="margin_right_35" for="ticket_style1">
                                                    <span class="diyradio"><tt></tt></span>
                                                    <span>立减/份</span>
                                                </label>
                                                <input class="none" type="radio" id="ticket_style2" name="ticket_style" onclick="change_ticket_style($(this).val())" type="radio" value="2" <?php if(isset($posts['ticket_style'])&&$posts['ticket_style']==2)echo 'checked';?> />
                                                <label for="ticket_style2">
                                                    <span class="diyradio"><tt></tt></span>
                                                    <span>折扣</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="ticket_credits" <?php if(isset($posts['ticket_credits']) && $posts['ticket_credits']==1 || empty($posts['ticket_credits'])){?>style="display: none;"<?php }?>>
                                            <div class="ticket_limit_name">
                                                <?php
                                                echo !empty($posts['ticket_style']) && $posts['ticket_style'] == 2 ? '折扣额度': '立减额度';
                                                ?>
                                            </div>
                                            <div class="input"><input name="ticket_limit" value="<?php echo isset($posts['ticket_limit']) ? ($posts['ticket_style']==2 ?intval($posts['ticket_limit']):$posts['ticket_limit']):'0'?>"><span class="ticket_limit_unit"><?php
                                                    echo !empty($posts['ticket_style']) && $posts['ticket_style'] == 2 ? '折': '元';
                                                    ?></span></div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="j_item margin_bottom_30">
                        <div class="flex row_line center padding_right_30 font_14 color_bfbfbf margin_bottom_25">
                            <div class=" width_100 text_right margin_right_15">商品信息</div>
                        </div>
                        <div class="j_tiem_conter padding_right_30">
                            <div class="flex between margin_bottom_25">
                                <div class="flex_1">
                                    <div class="flex start">
                                        <div class="width_100 text_right margin_right_15">商品主图</div>
                                        <div class="">
                                            <div class="flex">
                                                <div class=" margin_right_15">

                                                    <input type="hidden" name="goods_img" id="el_intro_img_d" value='<?php if(!empty($posts['goods_img'])) echo $posts['goods_img'];?>'>
                                                    <div id="intro_img" class="addimgs trim" style="float: left;margin-right: 5px;">
                                                        <?php if(!empty($posts['goods_img'])) $detail_img = json_decode($posts['goods_img'],true);?>
                                                        <?php if(!empty($detail_img)){foreach($detail_img as $img){?>
                                                            <div class="addimg candelete"><del></del><div><img width="120" height="120" src="<?php echo $img;?>"/></div></div>
                                                        <?php }}?>
                                                    </div>
                                                    <div id="file_d" class="" style="float: left;"></div>
                                                </div>
                                                <span class="color_bfbfbf">缩略图建议在640 x 640像素</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex between margin_bottom_25">
                                <div class="flex_1">
                                    <div class="flex start">
                                        <div class="width_100 text_right margin_right_15">使用须知</div>
                                        <div class="flex_1 ">
                                            <div class="jfk-form__item material-add__article">
                                                <div class="ueditor-box">
                                                    <div class="ueditor" name="content_goods_notice" id="jfk-ueditor_goods_notice">
                                                        <textarea name="goods_notice" id="goods_notice"><?php echo isset($posts['goods_notice'])?$posts['goods_notice']:''?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex between margin_bottom_25">
                                <div class="flex_1">
                                    <div class="flex start">
                                        <div class="width_100 text_right margin_right_15">商品详情</div>
                                      <div class="flex_1 ">
                                            <div class="jfk-form__item material-add__article">
                                              <div class="ueditor-box">
                                                <div class="ueditor" name="content" id="jfk-ueditor">

                                                    <textarea name="goods_desc" id="goods_desc"><?php echo isset($posts['goods_desc'])?$posts['goods_desc']:''?></textarea>
                                                </div>
                                              </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex  margin_bottom_25">
                                <div class="width_100 text_right margin_right_15">商品排序</div>
                                <div><input class=" width_130 radius_3 border_eee_1 height_30 text_indent_3" required="" type="text" name="sort_order" value="<?php echo isset($posts['sort_order'])?$posts['sort_order']:''?>"></div>
                            </div>
                        </div>
                    </div>
                    <!--分享设置开始-->
                    <div class="j_item margin_bottom_30">
                        <div class="flex row_line center padding_right_30 font_14 color_bfbfbf margin_bottom_25">
                            <div class=" width_100 text_right margin_right_15">分享设置</div>
                        </div>
                        <div class="j_tiem_conter">
                            <div class="flex between margin_bottom_25">
                                <div class="flex_1">
                                    <div class="flex start">
                                        <div class="width_100 text_right margin_right_15">分享配图</div>
                                        <div class="">
                                            <div class="flex">
                                                <div class=" margin_right_15">

                                                    <input type="hidden"  name="share_img" id="el_share_img" value='<?php if(!empty($posts['share_img'])) echo $posts['share_img'];?>'>
                                                    <div id="share_img" class="addimgs trim">
                                                        <?php if(!empty($posts['share_img'])){?>
                                                            <div class="addimg candelete"><del></del><div><img src="<?php echo isset($posts['share_img']) ? $posts['share_img'] : '';?>"/></div></div>
                                                        <?php }?>
                                                    </div>
                                                    <div id="share_img_add"></div>
                                                </div>
                                                <span class="color_bfbfbf">缩略图建议在640 x 640像素</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex  margin_bottom_25">
                                <div class="width_100 text_right margin_right_15">分享标题</div>
                                <div><input style="width: 500px" class=" radius_3 border_eee_1 height_30 text_indent_3" required="" type="text" name="share_title" value="<?php echo isset($posts['share_title'])?$posts['share_title']:''?>"></div>
                            </div>
                            <div class="flex  margin_bottom_25">
                                <div class="width_100 text_right margin_right_15">分享内容</div>
                                <div><textarea name="share_spec" style="border:1px solid #eee;padding: 5px;width: 500px;" rows="6"  maxlength=""><?php echo isset($posts['share_spec'])?$posts['share_spec']:''?></textarea></div>
                            </div>
                        </div>
                    </div>
                    <!--分享设置结束-->
                    <div class="j_item margin_bottom_30">
                        <div class="flex row_line center padding_right_30 font_14 color_bfbfbf margin_bottom_25">
                            <div class=" width_100 text_right margin_right_15">其它</div>
                        </div>
                        <div class="j_tiem_conter">
                            <div class="flex between margin_bottom_25">
                                <div class="flex_1">
                                    <div class="flex">
                                        <div class="width_100 text_right margin_right_15">开售时间</div>
                                        <div>
                                            <input class="none" type="radio" id="yes2" name="sale_now" onclick="radiochange($(this).val())" value="1" <?php if(isset($posts['sale_now'])&&$posts['sale_now']==1 || empty($posts['sale_now']))echo 'checked';?>  />
                                            <label class="margin_right_35" for="yes2">
                                                <span class="diyradio"><tt></tt></span>
                                                <span>立即开售</span>
                                            </label>
                                            <!--
                                            <input class="time_selects_btn none" type="radio" id="timing" name="sale_now" onclick="radiochange($(this).val())"  value="2" <?php if(isset($posts['sale_now'])&&$posts['sale_now']==2)echo 'checked';?> />
                                            <label class="margin_right_35" for="timing">
                                                <span class="diyradio"><tt></tt></span>
                                                <span>定时售卖</span>
                                                <div class="input time_selects none" id="sale_now_show">
                                                    <input class="input xs timepicker width_130 radius_3 border_eee_1 height_30 text_indent_3" name="sale_start_time"
                                                           value="<?php if(isset($posts['sale_start_time']))echo $posts['sale_start_time']?date('H:i',strtotime($posts['sale_start_time'])):'';?>">
                                                    --
                                                    <input class="input xs timepicker width_130 radius_3 border_eee_1 height_30 text_indent_3" name="sale_end_time"
                                                           value="<?php if(isset($posts['sale_end_time']))echo$posts['sale_end_time'] ?date('H:i',strtotime($posts['sale_end_time'])):'';?>">
                                                </div>
                                            </label>-->
                                            <input class="none" type="radio" id="no2" name="sale_now" onclick="radiochange($(this).val())" value="3" <?php if(isset($posts['sale_now'])&&$posts['sale_now']==3 || empty($posts['sale_now']))echo 'checked';?>/>
                                            <label for="no2">
                                                <span class="diyradio"><tt></tt></span>
                                                <span>不开售</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="margin_40_0">
                        <input type="hidden" value="" name="del_spu_id" id="del_data"/>
                        <div class="padding_10_0 radius_3 font_weight font_16 color_fff bg_b69b69 width_300 center margin_auto" id="save">保存商品</div>
                    </div>
                </div>
            </form>
        </section>
    </div><!-- /.content-wrapper -->
    <div class="p_fixed color_333 none">
        <div class="flex w_h_100">
            <div class="batch_setting_con elastic_con_1 bg_fff radius_3 padding_t_20 padding_b_40 relative none">
                    <div class="absolute close_btn"><i class="iconfonts font_16">&#xe60a;</i></div>
                    <div class="ela_title font_16 center margin_bottom_45 font_weight">批量设置价格日历</div>
                    <div class="padding_r_80">
                        <div class="flex between margin_bottom_25">
                            <div class="flex_1">
                                <span style="width: 120px;" class="width_130 text_right inline_block margin_right_10">起始日期</span>
                                <span><input data-date-format="yyyy-mm-dd" class="radius_3 border_eee_1 height_30 text_indent_3 datepicker" type="text" value="<?php echo date('Y-m-d')?>" id="price_start_time"/></span>
                            </div>
                            <div class="flex_1">
                                <span style="width: 120px;" class="width_130 text_right inline_block margin_right_10">截止日期</span>
                                <span><input data-date-format="yyyy-mm-dd" class="radius_3 border_eee_1 height_30 text_indent_3 datepicker" type="text" id="price_end_time"/></span>
                            </div>
                        </div>
                        <div class="flex margin_bottom_25">
                            <div class="width_130 text_right margin_right_15">执行周期</div>
                            <div class="flex_1">
                                <div class="flex between weeks_list">
                                    <input class="none" type="checkbox" id="all_week" />
                                    <label class="" for="all_week">
                                        <span class="diycheckbox sub"></span>
                                        <span>全部</span>
                                    </label>
                                    <input class="none" type="checkbox" id="week_1" name="week" value="1"/>
                                    <label class="" for="week_1">
                                        <span class="diycheckbox sub"></span>
                                        <span>周一</span>
                                    </label>
                                    <input class="none" type="checkbox" id="week_2" name="week" value="2"/>
                                    <label class="" for="week_2">
                                        <span class="diycheckbox sub"></span>
                                        <span>周二</span>
                                    </label>
                                    <input class="none" type="checkbox" id="week_3" name="week" value="3"/>
                                    <label class="" for="week_3">
                                        <span class="diycheckbox sub"></span>
                                        <span>周三</span>
                                    </label>
                                    <input class="none" type="checkbox" id="week_4" name="week" value="4"/>
                                    <label class="" for="week_4">
                                        <span class="diycheckbox sub"></span>
                                        <span>周四</span>
                                    </label>
                                    <input class="none" type="checkbox" id="week_5" name="week" value="5"/>
                                    <label class="" for="week_5">
                                        <span class="diycheckbox sub"></span>
                                        <span>周五</span>
                                    </label>
                                    <input class="none" type="checkbox" id="week_6" name="week" value="6"/>
                                    <label class="" for="week_6">
                                        <span class="diycheckbox sub"></span>
                                        <span>周六</span>
                                    </label>
                                    <input class="none" type="checkbox" id="week_7" name="week" value="7"/>
                                    <label class="" for="week_7">
                                        <span class="diycheckbox sub"></span>
                                        <span>周日</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="flex margin_bottom_25 start">
                            <div class="width_130 text_right margin_right_15" style="padding-top:7px;">规格库存</div>
                            <div class="flex_1 all_model">
                                <!-- <div class="flex between margin_bottom_10">
                                    <div><span  class="inline_block width_100">成人席位 </span><input class="width_100 radius_3 border_eee_1 height_30 text_indent_3" required type="text" /> 元／份</div>
                                    <div>库存 <input class="width_100 radius_3 border_eee_1 height_30 text_indent_3" required type="text" /> 份</div>
                                </div>-->
                            </div>
                        </div>
                    </div>
                    <div class="margin_40_0" style="margin: 0 auto;text-align: center">
                        <button class="batch_setting_btn padding_10_0 radius_3 font_weight font_16 color_fff bg_b69b69 width_300 center margin_auto">保存</button>
                    </div>
            </div>
            <div class="one_settings elastic_con_1 bg_fff radius_3 padding_t_20 padding_b_40 relative none">
                <div>
                    <div class="absolute close_btn"><i class="iconfonts font_16">&#xe60a;</i></div>
                    <div class="ela_title font_16 center margin_bottom_45 font_weight">设置价格</div>
                    <div class="padding_r_80">
                        <div class="flex between margin_bottom_25">
                            <div class="">
                                <input type="hidden" id="date_id" value=""/>
                                <input type="hidden" id="date_list_price_index" value=""/>
                                <span class="width_130 text_right inline_block margin_right_15">设置日期</span>
                                <span><input data-date-format="yyyy-m-d" id="setting_date" class="radius_3 border_eee_1 height_30 text_indent_3 " required type="text" /></span>
                            </div>
                        </div>
                        <div class="flex margin_bottom_25 start">
                            <div class="width_130 text_right margin_right_15" style="padding-top:7px;">规格库存</div>
                            <div class="flex_1 single_model">
                                <!-- <div class="flex between margin_bottom_10">
                                    <div>成人席位 <input class="width_100 radius_3 border_eee_1 height_30 text_indent_3" required type="text" /> 元／份</div>
                                    <div>库存 <input class="width_100 radius_3 border_eee_1 height_30 text_indent_3" required type="text" /> 份</div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="margin_40_0" style="margin: 0 auto;text-align: center">
                        <button class="one_setting_btn padding_10_0 radius_3 font_weight font_16 color_fff bg_b69b69 width_300 center margin_auto">保存</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
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
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>
<!--kindEditor-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/plugins/code/prettify.css" />
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/kindeditor.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/plugins/code/prettify.js"></script>
<!--kindEditor-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datetimepicker/bootstrap-datetimepicker.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js"></script>
<!--
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
-->

</body>
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify_html5/jquery.uploadify.js"></script>
<script>
$(function(){

    var is_goods_id = '<?php echo !empty($ids) ? $ids : '';?>';
    $(".datepicker").datepicker({
        language: "zh-CN"
    });

    $('.selectpicker').selectpicker({
        width:300
    });
    $('#all_week').click(function(){
        if(!$(this).is(':checked')){
            $('.weeks_list input[type=checkbox]').each(function(){
                $(this).prop('checked',false);
            })
        }else{
            $('.weeks_list input[type=checkbox]').each(function(){
                $(this).prop('checked',true);
            })
        }
    })
    $('.weeks_list input[type=checkbox]').click(function(){
        var bool=true;
        $('.weeks_list input[type=checkbox]').not(':first').each(function(){
            if(!$(this).is(':checked')){
                bool=false;
            }
        })
        bool?$('#all_week').prop('checked',true):$('#all_week').prop('checked',false);
    })
    $(".timepicker").datetimepicker({
        format:"hh:ii", language: "zh-CN",startView:1, autoclose: true,
    });


    $(".timepicker_price").datetimepicker({
        format:"yyyy-mm-dd", language: "zh-CN",clearBtn: false,todayBtn: true,orientation: "auto left",
    });


    /*
    var editorId = 'appmsg_editor';
    var editor = UE.getEditor('jfk-ueditor',{
                id: editorId,
                wordCount: false,
                autoHeightEnabled: false,
                elementPathEnabled: false,
                initialFrameHeight: 200
            });
            */
    var json1=<?php echo $spu_data?>;
    //添加规格。。。

    var norms_number = $('.spu_list').length;


    $('.aa_norms').click(function(){
        norms_number++;
        var str='<div class="width_50 spu_list">' +
            '<div class="flex margin_bottom_25 specifications ">' +
            '<div class="spec_name width_100 text_right margin_right_15">规格'+norms_number+':</div>' +
            '<div class=""><span>规格名</span>' +
            '<input class="spu_name width_130 radius_3 border_eee_1 height_30 text_indent_3" name="spu[]" del-data = "" price-data = "" stock-data = "" type="text" required />'+
            '<span> 原价</span> '+
            '<input class="prime_price width_130 radius_3 border_eee_1 height_30 text_indent_3" name="prime_price[]" type="text" value="" required/>'+
            '<i class="iconfonts cursor none font_16 margin_left_15">&#xe60b;</i></div></div></div>';
        $('.norms_list').append(str)
    })
    $('.specifications_con').on('click','i',function(){

       var del_id = $(this).parent().find('.spu_name').attr('del-data');
        if (del_id != '')
        {
            var this_id = $('#del_data').val();
            $('#del_data').val(this_id +',' + del_id);
        }
        $(this).parents('.width_50').remove();
    })
    // 日历操作。。。
    $('.close_btn').click(function(){
        $('.batch_setting_con,.one_settings').fadeOut();
        $('.p_fixed').fadeOut();
    })

    //批量处理保存设置
    $('.batch_setting_btn').click(function()
    {
        var price_start_time = $('#price_start_time').val();
        var price_end_time = $('#price_end_time').val();

        if (price_start_time == '')
        {
            alert('请填写起始日期');return false;
        }

        if (price_end_time == '')
        {
            alert('请填写截止日期');return false;
        }

        var week_arr = [];
        $("input[name='week']:checked").each(function(i)
        {
            week_arr[i] = $(this).val();
        });
        if (week_arr == '')
        {
            alert('请选择执行周期');return false;
        }

        var spu_data = [];
        $(".batch_setting_con .spu_data").each(function(i)
        {
            var spu_price = {};
            spu_price.spu_id = $(this).attr('data');
            spu_price.spu_name = $(this).find('.inline_block').text();
            spu_price.spu_data_price = $(this).find('.spu_data_price').val();
            spu_price.spu_data_stock = $(this).find('.spu_data_stock').val();
            spu_price.prime_price = $(this).attr('prime_price');

            spu_data[i] = spu_price;

        });

        if (spu_data == '')
        {
            alert('请先添加商品规格');return false;
        }

        //alert(spu_data);
        cleat_date();
        var date_titl=$('.date_txt').html().split("  ");
        var mm=parseInt(date_titl[1]);
        date_titl[0]=parseInt(date_titl[0]);

        var data_setting = {};
        data_setting.spu_data           = spu_data;
        data_setting.price_start_time   = price_start_time;
        data_setting.price_end_time     = price_end_time;
        data_setting.week               = week_arr;
        data_setting.year               = date_titl[0];
        data_setting.mouth               = mm;
        data_setting.goods_id = "<?php echo !empty($ids) ? $ids : '';?>";
        data_setting.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';
        $.ajax({
            dataType:"json",
            type:'post',
            url:"<?php echo site_url('/ticket/goods/save_setting')?>",
            data:data_setting,
            beforeSend: function()
            {
                $('.batch_setting_btn').attr('disabled',true);
                $('.batch_setting_btn').css('background','#999');
                $('.batch_setting_btn').html('保存中');
            },
            success: function(res)
            {
                if (res.status == 200)
                {
                    if (res.data.spu_data.length > 0)
                    {
                        $('.norms_list').html('')
                        for (var i = 0;i<res.data.spu_data.length;i++)
                        {
                            var show_spu_id = '';
                            if (is_goods_id != '')
                            {
                                show_spu_id = 'id_'+res.data.spu_data[i].spu_id;
                            }
                            var str='<div class="width_50 spu_list">' +
                                '<div class="flex margin_bottom_25 specifications ">' +
                                '<div class="spec_name width_100 text_right margin_right_15">规格'+(i+1)+':</div>' +
                                '<div class=""><span>规格名</span>' +
                                '<input class="spu_name width_130 radius_3 border_eee_1 height_30 text_indent_3" ' +
                                'name="spu['+show_spu_id+']" del-data = "'+res.data.spu_data[i].spu_id+'" ' +
                                'value="'+res.data.spu_data[i].spu_name+'" price-data = "'+res.data.spu_data[i].spu_data_price+'"  stock-data = "'+res.data.spu_data[i].spu_data_stock+'" type="text" required />' +
                                '<span>原价</span> '+
                                '<input class="prime_price width_130 radius_3 border_eee_1 height_30 text_indent_3" name="prime_price['+show_spu_id+']" type="text" value="'+res.data.spu_data[i].prime_price+'" required/>'+
                                '<i class="iconfonts cursor none font_16 margin_left_15">&#xe60b;</i>' +
                                '</div></div></div>';

                            $('.norms_list').append(str)
                        }
                    }

                    $('.batch_setting_con,.one_settings').fadeOut();
                    $('.p_fixed').fadeOut();

                    json1 = res.data;
                    new_date(date_titl[0],mm);
                }
                else
                {
                    alert(res.msg);return false;
                }
            },
            complete: function()
            {
                $('.batch_setting_btn').attr('disabled',false);
                $('.batch_setting_btn').css('background','#b69b69');
                $('.batch_setting_btn').html('保存');

            }
        });


    });

    //单个设置
    $('.one_setting_btn').click(function()
    {
        var setting_date = $('#setting_date').val();
        var date_id      = $('#date_id').val();
        var date_list_price_index      = $('#date_list_price_index').val();

        if (setting_date == '')
        {
            alert('请填写设置日期');return false;
        }

        var spu_data_setting = [];
        $(".one_settings .spu_data").each(function(i)
        {
            var spu_price_setting = {};
            spu_price_setting.spu_id = $(this).attr('data');
            spu_price_setting.spu_name = $(this).find('.inline_block').text();
            spu_price_setting.spu_data_price = $(this).find('.spu_data_price').val();
            spu_price_setting.spu_data_stock = $(this).find('.spu_data_stock').val();
            spu_price_setting.prime_price = $(this).attr('prime_price');

            spu_data_setting[i] = spu_price_setting;

        });

        if (spu_data_setting == '')
        {
            alert('请先添加商品规格');return false;
        }

        var data_setting = {};
        data_setting.spu_data           = spu_data_setting;
        data_setting.setting_date       = setting_date;
        data_setting.date_id            = date_id;
        data_setting.goods_id = "<?php echo !empty($ids) ? $ids : '';?>";
        data_setting.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';
        $.ajax({
            dataType:"json",
            type:'post',
            url:"<?php echo site_url('/ticket/goods/save_one_setting')?>",
            data:data_setting,
            beforeSend: function()
            {
                $('.one_setting_btn').attr('disabled',true);
                $('.one_setting_btn').css('background','#999');
                $('.one_setting_btn').html('保存中');
            },
            success: function(res)
            {
                if (res.status == 200)
                {
                    $('.date_select .one_setting').eq(date_list_price_index).find('.date_pice').text(res.data.price);

                    if (res.data.spu_data.length > 0)
                    {
                        var str = '';

                        for (var i = 0;i<res.data.spu_data.length;i++)
                        {
                            var price_data = $('.norms_list .spu_list').eq(i).find('input ').attr('price-data');
                            var stock_data = $('.norms_list .spu_list').eq(i).find('input ').attr('stock-data');
                            var show_spu_id = '';
                            if (is_goods_id != '')
                            {
                                show_spu_id = 'id_'+res.data.spu_data[i].spu_id;
                            }

                            str +='<div class="width_50 spu_list"><div class="flex margin_bottom_25 specifications ">' +
                                '<div class="spec_name width_100 text_right margin_right_15">规格'+(i+1)+':</div>' +
                                '<div class=""><span>规格名</span><input class="spu_name width_130 radius_3 border_eee_1 height_30 text_indent_3"' +
                                ' name="spu['+show_spu_id+']" del-data = "'+res.data.spu_data[i].spu_id+'" ' +
                                'value="'+res.data.spu_data[i].spu_name+'" price-data = "'+price_data+'" stock-data = "'+stock_data+'" type="text" required />' +
                                '<span> 原价</span> '+
                                '<input class="prime_price width_130 radius_3 border_eee_1 height_30 text_indent_3" name="prime_price['+show_spu_id+']" type="text" value="'+res.data.spu_data[i].prime_price+'" required/>'+
                                '<i class="iconfonts cursor none font_16 margin_left_15">&#xe60b;</i></div></div></div>';
                        }
                        $('.norms_list').html('');
                        $('.norms_list').append(str)
                    }

                    $('.one_settings').fadeOut();
                    $('.p_fixed').fadeOut();

                }
                else
                {
                    alert(res.msg);return false;
                }
            },
            complete: function()
            {
                $('.one_setting_btn').attr('disabled',false);
                $('.one_setting_btn').css('background','#b69b69');
                $('.one_setting_btn').html('保存');

            }
        });
    });

    var arr_content=[];
    var arr_content_prime=[];
    var arr_content_id=[];
    var arr_content_price=[];
    var arr_content_stock=[];
    $('.batch_setting').click(function(){
        $('.batch_setting_con .all_model div').remove();
        arr_content=[];
        arr_content_prime=[];
        arr_content_id=[];
        arr_content_price=[];
        arr_content_stock=[];
        add_arr_content($('.batch_setting_con .all_model'));

        if (arr_content.length == 0)
        {
            alert('请先添加规格');return false;
        }
        $('.batch_setting_con').show();
        $('.p_fixed').fadeIn();
    });

    //单个设置价格日历
    $('.date_select').on('click','.one_setting',function()
    {
        var date_list_price_index = $('.date_select .one_setting').index($(this));
        $('#date_id').val($(this).attr('psp_sid'));
        var date_id = $(this).attr('psp_sid');
        //设置当前点击日期索引
        $('#date_list_price_index').val(date_list_price_index);

        if(!$(this).hasClass('color_bfbfbf')&&$(this).find('.date_nmber').html()!='')
        {
            //获取当前日历时间
            var date_titl=$('.date_txt').html().split("  ");
            var mm=parseInt(date_titl[1]);
            date_titl[0]=parseInt(date_titl[0]);
            var price_day = $(this).find('.date_nmber').text();

            var year_month = date_titl[0] +'-'+ mm +'-'+price_day;

            $('#setting_date').val(year_month);

            $('.one_settings .single_model div').remove();

            arr_content=[];
            arr_content_id=[];
            arr_content_prime=[];
            add_arr_content_one($('.one_settings .single_model'),date_id);

            if (arr_content.length >0)
            {
                $('.p_fixed').fadeIn();
            }

        }
    });

    function add_arr_content_one(obj1,date_id)
    {
        $('.specifications_con .spu_name').each(function ()
        {
            if ($(this).val() != '') {
                arr_content.push($(this).val());
                arr_content_id.push($(this).attr('del-data'));
                arr_content_prime.push($(this).parent().find('.prime_price').val());
            }
        });

        var goods_id = "<?php echo !empty($ids) ? $ids : '';?>";

        if (arr_content.length == 0)
        {
            alert('请先添加规格');return false;
        }
        if (date_id != '')
        {
            var info_arr_content = '';
            var get_date_info = {};
            get_date_info.date = date_id;
            get_date_info.goods_id = goods_id;
            get_date_info.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';
            //获取数据
            $.ajax({
                dataType: "json",
                type: 'post',
                url: "<?php echo site_url('/ticket/goods/get_date_info')?>",
                data: get_date_info,
                success: function (res)
                {
                    if (res.status == 200)
                    {
                        info_arr_content = res.data;

                        if (arr_content.length != 0)
                        {
                            for (var i = 0; i < arr_content.length; i++)
                            {
                                var obj_key_price = '';
                                var obj_key_stock = '';

                                if (info_arr_content != '')
                                {
                                    for (var j = 0; j < info_arr_content.length; j++)
                                    {
                                        if (goods_id == '')
                                        {
                                            if (arr_content[i] == info_arr_content[j].spu_name)
                                            {
                                                obj_key_price = info_arr_content[j].goods_price;
                                                obj_key_stock = info_arr_content[j].goods_stock;
                                            }
                                        }
                                        else
                                        {
                                            if (arr_content_id[i] == info_arr_content[j].spu_id)
                                            {
                                                obj_key_price = info_arr_content[j].goods_price;
                                                obj_key_stock = info_arr_content[j].goods_stock;
                                            }
                                        }
                                    }
                                }

                                var odiv = $('<div class="spu_data flex between margin_bottom_10" data="' + arr_content_id[i] + '" prime_price="' + arr_content_prime[i] + '" >' +
                                    '<div><div  class="inline_block width_100 ellipsis middle">' + arr_content[i] + '</div>' +
                                    '<input value="' + obj_key_price + '" class="spu_data_price width_100 radius_3 border_eee_1 height_30 text_indent_3 " ' +
                                    'required type="text" /> 元／份</div><div>库存 ' +
                                    '<input value="' + obj_key_stock + '" class="spu_data_stock width_100 radius_3 border_eee_1 height_30 text_indent_3" ' +
                                    'required type="text" /> 份</div></div>');
                                obj1.append(odiv);
                            }
                        }
                    }
                    $('.one_settings').show();

                },
                complete: function () {
                    //$(this).html('提交');
                }
            });

        }
        else
        {
            if(arr_content.length!=0)
            {
                for(var i=0;i<arr_content.length;i++){
                    var odiv=$('<div class="spu_data flex between margin_bottom_10" data="'+arr_content_id[i]+'" ><div><div  class="inline_block width_100 ellipsis middle">'+arr_content[i]+'</div><input class="spu_data_price width_100 radius_3 border_eee_1 height_30 text_indent_3 " required type="text" /> 元／份</div><div>库存 <input class="spu_data_stock width_100 radius_3 border_eee_1 height_30 text_indent_3" required type="text" /> 份</div></div>');
                    obj1.append(odiv);
                }
            }
            $('.one_settings').show();

        }

    }

    //规格获取
    function add_arr_content(obj1)
    {
        $('.specifications_con .spu_name').each(function()
        {
            if($(this).val()!=''){
                arr_content.push($(this).val());
                arr_content_prime.push($(this).parent().find('.prime_price').val());
                arr_content_id.push($(this).attr('del-data'));
                arr_content_price.push($(this).attr('price-data'));
                arr_content_stock.push($(this).attr('stock-data'));
            }
        });

        if(arr_content.length!=0)
        {
            for(var i=0;i<arr_content.length;i++){
                var odiv=$('<div class="spu_data flex between margin_bottom_10" data="'+arr_content_id[i]+'" prime_price="'+arr_content_prime[i]+'" >' +
                    '<div><div  class="inline_block width_100 ellipsis middle">'+arr_content[i]+'</div>' +
                    '<input class="spu_data_price width_100 radius_3 border_eee_1 height_30 text_indent_3 " value="'+arr_content_price[i]+'" required type="text" /> 元／份</div>' +
                    '<div>库存 <input class="spu_data_stock width_100 radius_3 border_eee_1 height_30 text_indent_3" value="'+arr_content_stock[i]+'" required type="text" /> 份</div>' +
                    '</div>');
                obj1.append(odiv);
            }
        }

    }

    //获取上个月价格日历数据
    $('.d_last').click(function()
    {
        cleat_date();
        var date_titl=$('.date_txt').html().split("  ");
        var mm=parseInt(date_titl[1])-1;
        if(mm<1){
            mm=12;
            date_titl[0]=parseInt(date_titl[0])-1;
        }

        var dateprice_stock = {};
        dateprice_stock.year = date_titl[0];
        dateprice_stock.month = mm;
        dateprice_stock.goods_id = "<?php echo !empty($ids) ? $ids : '';?>";
        dateprice_stock.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';
        $.ajax({
            dataType:"json",
            type:'post',
            url:"<?php echo site_url('/ticket/goods/get_dateprice_info')?>",
            data:dateprice_stock,
            success: function(res)
            {
                if (res.status == 200)
                {
                    json1 = res.data;
                    new_date(date_titl[0],mm);
                }
            },
            complete: function()
            {
                //$(this).html('提交');
            }
        });

    })
    $('.d_next').click(function()
    {
        cleat_date();
        var date_titl=$('.date_txt').html().split("  ");
        var mm=parseInt(date_titl[1])+1;
        if(mm>12){
            mm=1;
            date_titl[0]=parseInt(date_titl[0])+1;
        }

        var dateprice_stock = {};
        dateprice_stock.year = date_titl[0];
        dateprice_stock.month = mm;
        dateprice_stock.goods_id = "<?php echo !empty($ids) ? $ids : '';?>";
        dateprice_stock.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';
        $.ajax({
            dataType:"json",
            type:'post',
            url:"<?php echo site_url('/ticket/goods/get_dateprice_info')?>",
            data:dateprice_stock,
            success: function(res)
            {
                if (res.status == 200)
                {
                    json1 = res.data;
                    new_date(date_titl[0],mm);
                }
            },
            complete: function()
            {
                //$(this).html('提交');
            }
        });
    })
    // 日历插件
    new_date();
    function new_date(y,m){
        var new_date=new Date();
        var y=y||new_date.getFullYear();
        var m=m||new_date.getMonth()+1;
        $('.date_txt').html(y+'  '+m+'月');
        var set_date=new Date(y,m-1);
        var weeks=set_date.getDay();    //星期几
        var datas=new Date(y,m,0).getDate();    //当月有几天
        var nmber=weeks+datas<35?nmber=5:nmber=6;
        for(var i=0;i<nmber;i++){
            var otr=$('<tr></tr>');
            for(var j=0;j<7;j++){
                var otd=$('<td  class="one_setting"></td>').append($('<p class="date_nmber"></p>')).append($('<p class="date_pice">&nbsp</p>'));
                otr.append(otd);
            }
            $('.date_select tbody').append(otr);
       }
       var otd=$('.date_select .date_nmber');
       switch(weeks){
            case 0:
                for(var i=0;i<datas;i++){
                    otd.eq(i).html(i+1);
                }
            break;
            case 1:
                for(var i=0;i<datas;i++){
                    otd.eq(i+1).html(i+1);
                }
            break;
            case 2:
                for(var i=0;i<datas;i++){
                    otd.eq(i+2).html(i+1);
                }
            break;
            case 3:
                for(var i=0;i<datas;i++){
                    otd.eq(i+3).html(i+1);
                }
            break;
            case 4:
                for(var i=0;i<datas;i++){
                    otd.eq(i+4).html(i+1);
                }
            break;
            case 5:
                for(var i=0;i<datas;i++){
                    otd.eq(i+5).html(i+1);
                }
            break;
            case 6:
                for(var i=0;i<datas;i++){
                    otd.eq(i+6).html(i+1);
                }
            break;
       }
        if(json1.data!=''||json1.data.length!=0){   //判断库存。。。
            for(var b=0;b<json1.month.length;b++){
                var indezs=parseInt(json1.month[b].time.split('-')[2])-1+weeks;
                var stock=parseInt(json1.month[b].stock);
                // if(stock<=0){
                //     $('.date_pice').eq(indezs).parents('td').addClass('color_bfbfbf');
                // }
                $('.date_pice').eq(indezs).html(json1.month[b].money);
                $('.date_pice').eq(indezs).parents('td').attr('psp_sid',json1.month[b].psp_sid);
            }
        }
        var yy=new_date.getFullYear();
        var mm=new_date.getMonth()+1;
        var dd=new_date.getDate();
        var date_arr=json1.data.split('/');
         if(''+yy+toZoo(mm)>''+date_arr[0]+toZoo(date_arr[1])){
             $('.date_pice').parents('td').addClass('color_bfbfbf');
         }
         if(''+yy+toZoo(mm)==''+date_arr[0]+toZoo(date_arr[1])){
            for(var i=weeks;i<dd+weeks-1;i++){
                $('.date_pice').eq(i).parents('td').addClass('color_bfbfbf');
            }
         }
    }
})
function toZoo(str){  //补零
    return str<10?'0'+str:str;
}
function cleat_date(){  //清日历
    $('.date_select tbody tr').not(":first").remove();
}
</script>


<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify_html5/jquery.uploadify.js"></script>
<script>
    <?php
    //FULL Path: .../index.php/basic/browse/mall?t=images&p=a23523967|mall|goods|desc&token=test&CKEditor=el_gs_detail&CKEditorFuncNum=1&langCode=zh-cn
    $floder=  $inter_id ? $inter_id : 'inter_id';
    $subpath= $floder. '|ticket|goods|edit'; //基准路径定位在 /public/media/ 下
    $params= array(
        't'=>'images',
        'p'=>$subpath,
        'token'=>'test' //再完善校验机制
    );

    ?>
    <?php $timestamp = time();?>
    var editor2,editor1;
    $(function() {

        var commonItems = [
            'undo', 'redo', '|','cut', 'copy', 'paste',
            'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
            'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
            'superscript', 'clearhtml', 'quickformat',  '|',
            'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
            'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '/', 'image', 'multiimage',
            'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
            'anchor', 'link', 'unlink'
        ];

        KindEditor.ready(function(K) {
            //须知
            editor1 = K.create('#goods_notice', {
                cssPath : '<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/kindeditor/plugins/code/prettify.css',
                uploadJson : '<?php echo Soma_const_url::inst()->get_url('basic/upload/kind_do_upload', $params);?>',
                fileManagerJson : '<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/php/file_manager_json.php',
                allowFileManager : true,
                resizeType : 1,
                items : commonItems,
                afterCreate : function() {
                    setTimeout(function(){
                        $('.ke-container').css('width','800');
                        $('.ke-edit').height(300);
                        $('.ke-edit-iframe').height(300);
                    },1)
                }
            });



            //图文详情
            editor2 = K.create('#goods_desc', {
                cssPath : '<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/kindeditor/plugins/code/prettify.css',
                uploadJson : '<?php echo Soma_const_url::inst()->get_url('basic/upload/kind_do_upload', $params);?>',
                fileManagerJson : '<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/php/file_manager_json.php',
                allowFileManager : true,
                resizeType : 1,
                items : commonItems,
                afterCreate : function() {
                    setTimeout(function(){
                        $('.ke-container').css('width','800');
                        $('.ke-edit').height(300);
                        $('.ke-edit-iframe').height(300);
                    },1)
                }
            });
            prettyPrint()
        });


        //选择店铺
        $('#shop_id').change(function()
        {
            var value  = $(this).val();
            if(value=='')
            {
                return false;
            }
            $('#hotel_id').val($(this).children('option:selected').attr('iid'));
            //分组信息
            var url = '<?php echo site_url('ticket/goods_group/ajax_get_group_info')?>';
            $.post(url,{
                'shop_id':value,
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },function(res){
                var html = '<option value="0">请选择分组</option>';
                $('#group_id').html('');
                if(res.errcode == 0){

                    for(var i in res.data){
                        html += '<option value="'+res.data[i].group_id+'">'+res.data[i].group_name+'</option>';
                    }

                }else{
                    alert(res.msg);
                }
                $('#group_id').append(html);
            },'json');

            //营业时段信息

            var url_time = '<?php echo site_url('ticket/goods/get_shop_time_range')?>';
            $.post(url_time,{
                'shop_id':value,
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },function(res){

                var html = '';
                $('#show_time_range').html('');
                if(res.status == 200)
                {
                    for(var i in res.data)
                    {
                        html += '<li>';
                        html += '<input class="none" name="ticket_sale_time[]" value="'+ res.data[i].name +'" type="checkbox" id="sale_time_'+ i +'"  />';
                        html += '<label class="margin_right_35" for="sale_time_'+ i +'">';
                        html += '<span class="diycheckbox sub"></span>';
                        html += '<span>&nbsp;'+  res.data[i].name +'</span>';
                        html += '</label>';
                        html += '</li>';
                    }
                    $('#show_time_range').append(html);
                }
                else
                {
                    alert(res.msg);
                }
            },'json');


        });
        //分享图片
        function del_shareimg(){  //缩略图
            $(this).parent().remove();
            $("#el_share_img").val('');
            $('#share_img_add').show();
        }

        $('#share_img_add').uploadify({//缩略图
            'formData'     : {
                '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
                'timestamp' : '<?php echo time();?>',
                'token'     : '<?php echo md5('unique_salt' . time());?>'
            },
            'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
            'fileObjName': 'imgFile',
            'delimg':'<?php echo base_url(FD_PUBLIC) ?>/img/cancel.png',
            'buttonImage':"<?php echo base_url(FD_PUBLIC) ?>/img/imgadd.jpg",
            'fileTypeExts':'*.jpg;*.jpeg;*.gif;*.png',//文件类型
            'fileSizeLimit':'300', //限制文件大小
            'onUploadSuccess' : function(file, data) {
                var res = $.parseJSON(data);
                $('#el_share_img').val(res.url);
                var dom = $('<div class="addimg candelete"><del></del><div><img src="'+res.url+'"/></div></div>');
                $("#share_img").append(dom);
                dom.find('del').get(0).onclick=del_shareimg;
                $('#share_img_add').hide();
            },
            'onUploadError': function () {
                alert('上传失败');
            }
        });

        if($('#share_img').find('img').length>0){
            $("#share_img del").get(0).onclick=del_shareimg;
            $('#share_img_add').hide();
        }


        //上传图片
        function delimg(){
            var thisurl = $(this).parent().find('img').attr('src');
            $(this).parent().remove();
            var detail_img = $.parseJSON($("#el_intro_img_d").val());
            for(var k=0;k<detail_img.length;k++){
                if(detail_img[k] == thisurl){
                    detail_img.splice(k,1);
                }
            }
            detail_img = JSON.stringify(detail_img);
            $("#el_intro_img_d").val(detail_img);
            $('#file_d').show();
        }
        $('#file_d').uploadify({
            'formData'     : {
                '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
                'timestamp' : '<?php echo $timestamp;?>',
                'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
            },
            //'uploader' : '<?php echo site_url("basic/upload/hotel_upload") ?>',
            'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
            'delimg':'<?php echo base_url(FD_PUBLIC) ?>/img/cancel.png',
            'fileObjName': 'imgFile',
            'fileTypeExts':'*.jpg;*.jpeg;*.gif;*.png',//文件类型
            'fileSizeLimit':'300', //限制文件大小
            'buttonImage':"<?php echo base_url(FD_PUBLIC) ?>/img/imgadd.jpg",
            'onUploadSuccess' : function(file, data) {
                var res = $.parseJSON(data);
                if($("#el_intro_img_d").val()==''){
                    var detail_img = new Array();
                }else{
                    var detail_img = $.parseJSON($("#el_intro_img_d").val());
                }
                detail_img.push(res.url);
                detail_img = JSON.stringify(detail_img);

                $('#el_intro_img_d').val(detail_img);
                var dom = $('<div class="addimg candelete"><del></del><div><img src="'+res.url+'"/></div></div>');
                $("#intro_img").append(dom);
                dom.delegate('del','click',delimg);
                //$('#file_d').hide();
            },
            'onUploadError': function () {
                alert('上传失败');
            }
        });
        <?php if(!empty($detail_img)){?>
       // $('#file_d').hide();
        <?php }?>
        $('.addimg').delegate('del','click',delimg);
    });

    //提前预约优惠
    function change_ticket(change)
    {
        if (change==2)
        {
            $('.ticket_credits').show();
        }
        else
        {
            $('.ticket_credits').hide();
        }
    }

    //优惠方式类型
    function change_ticket_style(change)
    {
        var limit_name = '立减额度';
        var limit_unit = '元';
        if (change==2)
        {
            limit_name = '折扣额度';
            limit_unit = '折';
            var ticket_limit =  $('input[name="ticket_limit"]').val();
            ticket_limit = parseInt(ticket_limit);
            $('input[name="ticket_limit"]').val(ticket_limit);
        }
        $('.ticket_limit_name').html(limit_name);
        $('.ticket_limit_unit').html(limit_unit);
    }

    $('#save').click(function(){
        if($("input[name='goods_name']").val() == ''){
            alert('名字不能为空');
            return false;
        }

        var spu = $('.spu_list').length;
        if (spu > 10)
        {
            alert('目前开放最多能添加10个规格哦');return false;
        }
        else if (spu == 0)
        {
            alert('请添加商品规格');return false;
        }

        if ($("input[name='ticket_credits']:checked").val() == 2)
        {
            if ($("input[name='ticket_day']").val() <= 0)
            {
                alert('预约优惠提前天数需大于0');
                return false;
            }

            if ($("input[name='ticket_style']:checked").val() == 2)
            {
                if ($("input[name='ticket_limit']").val() >= 10 || $("input[name='ticket_limit']").val() <= 0)
                {
                    alert('折扣额度需在1到10范围');
                    return false;
                }
            }
            else
            {
                if ($("input[name='ticket_limit']").val() <= 0)
                {
                    alert('立减额度需大于0');
                    return false;
                }
            }
        }

        //$('input[name="spec_list"]').val(JSON.stringify(SpecData));
       // $('input[name="delete_spec"]').val(JSON.stringify(del_spec));
        // console.log($('input[name="spec_list"]').val());return;
        editor1.sync();
        editor2.sync();
        $('form').submit();
    })
</script>
</html>
