<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->

<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-select/bootstrap-select.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-select/i18n/defaults-zh_CN.js"></script>
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
	.layer{position:fixed; top:0; left:0;overflow:hidden; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; display:none}
	.add_hotel_content,.add_good_content{background:#f8f8f8; padding:15px; height:100%; float:right; overflow-y:scroll;width:850px;}
	.ftotal_code >*{width:15%; padding-bottom:5px}
	.child_dom{ padding:3px 5px; margin:5px;background:#fff; border:1px solid #3c8dbc;max-width:450px; vertical-align:middle}
	.parent_dom div{display:inline-block}
	.parent_dom .check{margin:2px}
	#coupons_table td{vertical-align:middle}
	/*编辑器BUG*/
	.ke-dialog{top:22%}
	.list_layout>*>*:nth-child(2).good_show_area{
		max-width: none;
	}
	.list_layout > .Ldn{
		display: none;
	}
	.list_layout > .Ldf{
		display: flex !important;
		display: -webkit-flex !important;
	}
	.good_layer_list_item{
		max-height: 160px;
		overflow: auto;
		display: flex;
		display: -webkit-flex;
		border-bottom: solid 1px #f4f4f4;
	}
	.good_layer_list_item:last-child{
		border-bottom: 0 none;
	}
	.good_layer_list_item .good_layer_list_item_row{
		width: 50%;
		text-align: left;
		padding: 6px;
		height: 30px;

	}
	.good_layer_list_item .good_layer_list_item_par_row{
		width: 50%;
		text-align: left;
	}
	.good_layer_list_item_par_row .good_layer_list_item_sub_row{
		width: 100%;
		height: 30px;
		padding: 6px;
		border-bottom: solid 1px #f4f4f4;
		border-left: solid 1px #f4f4f4;
	}
	.good_layer_list_item_par_row .good_layer_list_item_sub_row:last-child{
		border-bottom: 0 none;
	}
	table.good_layer_table > thead > tr > th,table.good_layer_table > tbody > tr > td{
		border: 1px solid #f4f4f4;
		padding: 0 !important;
	}
	table.good_layer_table ul{
		margin-bottom: 0;
	}
	table.good_checked_table td{
		border: solid 1px #d9dfe9;

	}
	table.good_checked_table th,table.good_checked_table td{
		height: 36px;
		line-height: 36px;
		text-align: center;
	}
	table.good_checked_table del{
		top: 0;
		right: 5px;
	}
	.Ldn{
		display: none!important;
	}
	.good_list_content_loading{
		line-height: 30px;
		text-align: center;
	}
    .none{
        display: none !important;
    }
</style>

<!-- 新版本后台 v.2.0.0 -->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/version2-0-0.css'>
<style>
.icon-arrow-left:after{content: "\e091";}
.icon-arrow-right:after{content: "\e092";}
div[required]>div:first-child:before{content:'*'; color:#f00}
.addimg{margin-right:15px}
#gallery_img_add-queue{ position:absolute;right:0;top:100%;}
.cancel{background:url(<?php echo base_url(FD_PUBLIC) ?>/img/cancel.png) no-repeat; float:left; width:12px; height:12px; background-size:100% 100%; margin:0 5px; cursor:pointer}
.cancel a{ color:transparent;}
/*规格表格，不需要则不添加到页面*/
.spectable .multirow { padding:0}
.spectable .multirow>*{ border-top:1px solid #d9dfe9; padding:5px 0; }
.spectable .multirow>*:first-child{ border-top-color:transparent}
.spectable .multirow span{min-height:25px;line-height:25px; display:block}
.SpecBox .diySpec,.SpecBox .addimg{margin-right:20px}
.SpecBox .input{width:80px}
.flex_aligntop{ padding-top:5px}
#DateSet .input{display:inline-block;}
/*日历弹层*/
#dateTable{ z-index:9999; display:none}
#dateTable >*{ background:#fff; padding:10px; text-align:center; margin: auto; width:600px; max-height:60%; overflow:auto;  position:relative;}
#dateTable .absolute{position:absolute; width:100%; bottom:4px; left:0;}
#dateTable table{margin:10px 0; background:#fff;}
#dateTable td{ height:40px; vertical-align:middle; position:relative}
#dateTable .tdMonth{color:#f00; position:absolute; left:0; top:-5px; z-index:10; width:100%;}
#dateTable .webkitbox{ margin:auto; width:80%;} 
#dateTable .input{margin-left:20px; margin-bottom:5px;}
.hotel-name{max-width: 300px;}

.bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn){
    width: 100%;
}

.bootstrap-select .btn{
    padding: 8px;
    font-size: 12px;
    line-height: 1;
    border-radius: 0px;
    background: #fff;
}

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<header class="headtitle"><?php echo $breadcrumb_html; ?></header>
    <!-- <form action="<?php echo $post_url;?>" method="post" enctype="multipart/form-data" accept-charset="utf-8" class="form-horizontal"> -->
    
    <form class="fixed" id="dateTable">
    	<div style="margin-top:10%;">
            <div>添加或修改</div>
            <table>
                <thead><tr></tr></thead>
                <tbody></tbody>
            </table>
        </div>
        <div>
        	<div class="webkitbox">
                <div class="input"><input id="dateStock" /><span>库存</span></div>
                <div class="input"><input id="datePrice"  /><span>价格|积分</span></div>
            </div>
            <button type="reset" class="button bg_main maright">重置</button>
            <button type="button" class="button bg_main maright" id="SaveDate">保存</button>
        </div>
    </form>
    <section class="content">
    <?php echo form_open( Soma_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal','id'=>'saveInfo','enctype'=>'multipart/form-data' ), array($pk=>$model->m_get($pk), 'inter_id' =>$model->m_get('inter_id') ) ); ?>

        <input type="hidden" name="succ_url" value="<?php echo $succ_url; ?>" style="display:none;">

        <div class="whitetable">
            <div>
                <span style="border-color:#3f51b5">基本信息</span>
            </div>
            <div class="bd_left list_layout">
                <div>
                	<div>显示语言</div>
                    <div class="flexgrow" style="max-width:100%">
                        <label class="check">
                            <input name="language" type="radio" value="<?php echo $model::LANG_CN; ?>" <?php if($model->m_get('language') == $model::LANG_CN || $model->m_get('language') == null): ?> checked <?php endif; ?>/>
                            <span class="diyradio"><tt></tt></span> 仅中文
                        </label>
                        <label class="check">
                            <input name="language" type="radio" value="<?php echo $model::LANG_EN; ?>" <?php if($model->m_get('language') == $model::LANG_EN): ?> checked <?php endif; ?>/>
                            <span class="diyradio"><tt></tt></span> 中英文
                        </label>
                    </div>
                </div>
                <div required>
                    <div>公众号</div>
                    <div class="select_input flexgrow">
                    	<div class="input"><input placeholder="搜索或下拉选择" value="<?php echo $interIds[$inter_id];?>"></div>
                        <?php if($interIds):?>
                            <div class="silde_layer bd">
                            <?php foreach($interIds as $k=>$v):?>
                            	<div data="<?php echo $k;?>"><?php echo $v;?></div>
                            <?php endforeach;?>
                            </div>
                        <?php endif;?>
                        <input type="hidden" required name="inter_id" value="<?php echo $inter_id;?>">
                    </div>
                </div>
                <div required>
                    <div>所属酒店</div>
                    
                    <div class="select_input flexgrow">
                        <select class="selectpicker show-tick" multiple data-live-search="true" title="搜索或下拉选择" data-size="20" id="selecter">
                        <?php
                            $hotel_id_str = $model->m_get('hotel_ids_str');
                            if(empty($hotel_id_str)) {
                                $hotel_id_str = '';
                            }
                            $select_ids = explode(',', $model->m_get('hotel_ids_str'));
                            $enable_ids = array();
                        ?>
                        <?php if($hotelIds):?>
                            <?php foreach($hotelIds as $k=>$v):?>
                                <!-- <option value="<?php echo $k;?>"><?php echo $v['name'];?></option> -->
                                <option value="<?php echo $k;?>" <?php if(in_array($k, $select_ids)): ?> selected <?php $enable_ids[] = $k; ?><?php endif; ?>><?php echo $v['name'];?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                        </select>
                     
                        <input required type="hidden" name="hotel_id" id="hotel_id" value="<?php echo implode(',', $enable_ids); ?>">
                    </div>
                    
                   <!-- <div class="select_input flexgrow">

                    	<div class="input">
                            <input placeholder="搜索或下拉选择" value="<?php
                                $first_hotel=current( $hotelIds );
                                $first_hotel_id = isset( $first_hotel['hotel_id'] )
                                                    ? $first_hotel['hotel_id']
                                                    : '';
                                $first_hotel_name = isset( $first_hotel['name'] )
                                                    ? $first_hotel['name']
                                                    : '';
                                echo isset( $hotelIds[$model->m_get('hotel_id')]['name'] )
                                        ? $hotelIds[$model->m_get('hotel_id')]['name']
                                        //: $first_hotel_name;
                                        : '请选择';
                            ?>"></div>
                        <?php if($hotelIds):?>
                            <div class="silde_layer bd">
                            <?php foreach($hotelIds as $k=>$v):?>
                            	<div data="<?php echo $k;?>"><?php echo $v['name'];?></div>
                            <?php endforeach;?>
                            </div>
                        <?php endif;?>
                        <input required type="hidden" name="hotel_id" value="<?php
                                                                    if( isset( $hotelIds[$model->m_get('hotel_id')] ) )
                                                                    {
                                                                        $hotel_id = $model->m_get('hotel_id');
                                                                    } else {
                                                                        $hotel_id = '';
                                                                    }
                                                                    echo $hotel_id;
                                                                ?>">
                    </div>-->
                </div>
                <div required>
                    <div>产品类型</div>
                    <div class="flexgrow" style="max-width:100%">
                        <?php if($product_type):?>
                            <?php $_k=0; foreach($product_type as $k=>$v):?>
                                <label class="check type_label <?php if($k == 2 || $k == 4) echo 'type_should_hide' ?>">
                                    <input name="type" type="radio" value="<?php echo $k;?>" <?php if($model->m_get('type')==$k||$_k==0)echo 'checked';$_k++;?><?php if($model->m_get('product_id')): ?> disabled <?php endif; ?>/>
                                    <span class="diyradio"><tt></tt></span>
                                    <?php echo $v;?>
                                </label>
                            <?php endforeach;?>
                        <?php endif;?>
                    </div>
                </div>
                <div required>
                    <div>商品分类</div>
                    <div class="flexgrow">
                        <?php if($cate_list):?>
                            <?php  $_k=0; foreach($cate_list as $k=>$v):?>
                                <label class="check">
                                    <input name="cat_id" type="radio" value="<?php echo $v['cat_id'];?>" <?php if($model->m_get('cat_id')==$v['cat_id']||$_k==0)echo 'checked';$_k++;?> />
                                    <span class="diyradio"><tt></tt></span><?php echo $v['cat_name'];?>
                                </label>
                            <?php endforeach;?>
                        <?php endif;?>
                    </div>
                </div>
                <div required>
                    <div>商品类型</div>
                    <div class="flexgrow" style="max-width:100%">
                        <?php if($goods_type):?>
                            <?php $_k=0; foreach($goods_type as $k=>$v):?>
                                <label class="check">
                                    <input name="goods_type" type="radio" value="<?php echo $k;?>" <?php if($model->m_get('goods_type')==$k||$_k==0)echo 'checked';$_k++;?><?php if($model->m_get('product_id')): ?> disabled <?php endif; ?>/>
                                    <span class="diyradio"><tt></tt></span>
                                    <?php echo $v;?>
                                </label>
                            <?php endforeach;?>
                        <?php endif;?>
                    </div>
                </div>
                <!--
                <div class="en">
                    <div>Categories</div>
                    <div class="flexgrow">
                        <?php if($cate_list):?>
                            <?php  $_k=0; foreach($cate_list as $k=>$v):?>
                                <label class="check">
                                    <input name="cat_id" type="radio" value="<?php echo $v['cat_id'];?>" <?php if($model->m_get('cat_id')==$v['cat_id']||$_k==0)echo 'checked';$_k++;?> />
                                    <span class="diyradio"><tt></tt></span><?php echo $v['cat_name'];?>
                                </label>
                            <?php endforeach;?>
                        <?php endif;?>
                    </div>
                </div>
                -->
            </div>
        </div>
    
    	
        <div class="whitetable">
            <div>
                <span style="border-color:#ff503f">商品信息</span>
            </div>
            <div class="bd_left list_layout">
                <div required class="<?php if($model->m_get('goods_type')!=$model::SPEC_TYPE_TICKET)echo 'none';?> djhxsb">
                    <div>对接核销设备</div>
                    <div class="flexgrow">
                        <?php if($conn_devices):?>
                            <?php $_k=0; foreach($conn_devices as $k=>$v):?>
                                <label class="check">
                                    <input name="conn_devices" type="radio" value="<?php echo $k;?>" <?php if($model->m_get('conn_devices')==$k||$_k==0)echo 'checked';$_k++;?> />
                                    <span class="diyradio"><tt></tt></span>
                                    <?php echo $v;?>
                                </label>
                            <?php endforeach;?>
                        <?php endif;?>
                    </div>
                </div>
                <div required>
                    <div>商品名称</div>
                    <div class="input flexgrow"><input required name="name" value="<?php echo $model->m_get('name');?>"></div>
                </div>
                <div class="en">
                    <div>Name</div>
                    <div class="input flexgrow"><input required name="name_en" value="<?php echo $model->m_get('name_en');?>"></div>
                </div>
                <div>
                    <div>关键词</div>
                    <div class="input flexgrow"><input required name="keyword" value="<?php echo $model->m_get('keyword');?>"></div>
                </div>
                <div required>
                    <div>缩略图</div>
                    <div>
                        <input type="hidden" required name="face_img" id="el_face_img" value='<?php if(!empty($model->m_get('face_img'))) echo $model->m_get('face_img');?>'>
                        <div id="face_img" class="addimgs trim">
                        <?php if(!empty($model->m_get('face_img'))):?>
                            <div class="addimg candelete"><del></del><div><img src="<?php echo $model->m_get('face_img');?>"/></div></div>
                        <?php endif;?>
                        </div>
                        <div id="face_img_add"></div>
                    </div>
                    <div class="layoutfoot">缩略图尺寸：480*480</div>
                </div>
                <div>
                    <div>产品相册</div>
                    <div class="flex" style="max-width:100%; position: relative;">
                        <input type="hidden" required name="gallery" id="el_gallery_img" value=''>
                        <div id="gallery_img" class="addimgs trim">
                        <?php if(!empty($gallery)):?>
                            <?php foreach($gallery as $v):?>
                                <div class="addimg candelete"><del></del><div><img src="<?php echo $v['gry_url'];?>"/></div></div>
                            <?php endforeach;?>
                        <?php endif;?>
                        </div>
                        <div id="gallery_img_add"></div>
                    </div>
                    	<div class="layoutfoot">图片大小必须 &lt; 300KB;</div>
                </div>
                <div id="carid" style="display:none">
                	<div>卡券ID</div>
                    <div class="input flexgrow">
                    <select name="card_id">
                        <?php if( $packages ):?>
                            <?php foreach($packages as $k=>$v ):?>
                                <option value="<?php echo $k;?>" <?php if( $model->m_get('card_id') == $k )echo 'selected';?>><?php echo $v;?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select></div>
                </div>
            </div>
			
        </div>
    
     	<div class="whitetable">
            <div>
                <span style="border-color:#3f92b5">商品规格</span>
            </div>
            <div class="bd_left list_layout">
            	<div style="padding-bottom:0" id="SpecControls" class="show1">
                	<div class="flex_aligntop">商品规格</div>
                    <div class="flexgrow" style="max-width:100%">
                    	<span class="button void xs marbtm" id="addSpec">添加规格类型</span> 
                        <!--- 添加规格模版DIV -->
                        <div class="whiteblock pad10 SpecBox candelete hide" id="model" limit='3'><!-- limit 最多添加3种规格 -->
                        	<del></del>
                        	<div class="flex flexcenter">
                                <div class="input maright">
                                <select class="selectSpec">
                                    <option value="1">颜色</option><option value="2">尺寸</option><option value="3">人数</option><option value="4">类型</option><option value="5">其他</option>
                                </select></div>
                                <label class="check hide"><input type="checkbox" /><span class="diyradio"><tt></tt></span>添加规格图片</label>
                            </div>
                            <div class="flex flexcenter martop diySpecs">
                                <span class="textlink addNewSpec"><i class="fa fa-plus"></i> 添加规格</span>
                            </div>
                            <div class="martop trim addimgs"></div>
                        </div>
                        <!-- end -->
                    </div>
                    <!--<div class="layoutfoot" id="Next" style="display:none">
                        <span class="button void xs marbtm color_nomal">生成规格表</span> 
                    </div>-->
                </div>
                <div id="SpecSet" style="display:none" class="show1">
                    <div class="flex_aligntop">商品配置</div>
                    <div class="flexgrow"  style="max-width:100%">
                    	<div class="diytable center spectable">
                        	<div class="thead"></div>
                        	<div class="tbody"></div>
                        </div>
                    </div>
                </div>
            	<div style="padding-bottom:0" class="show2">
                	<div class="flex_aligntop">使用日期</div>
                    <div class="flexgrow" style="max-width:100%">
                    	<span class="button void xs marbtm" id="addDate">添加使用日期</span> 
                    	<span class="button bg_main xs marbtm" id="editDate" style="display:none">修改</span> 
                        <div class="whiteblock pad10 candelete" id="DateSet" style="width:400px;display:none">
                        	<del></del>
                            <div class="flex flexgrow" style="align-items:center">
                                <span class="input"><input class="datepicker2" placeholder="开始日期"></span>
                                <span>-</span>
                                <span class="input"><input class="datepicker2" placeholder="结束日期"></span>
                                <button type="button" class="btn btn-default btn-sm" id="searchDate"><i class="fa fa-plus"></i></button>
                            </div>
                            <div style="max-height:300px; margin-top:10px;overflow:auto">
                            <div class="diytable center spectable">
                                <div class="thead"></div>
                                <div class="tbody"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="layoutfoot" id="Next" style="display:none">
                        <span class="button void xs marbtm color_nomal">生成规格表</span> 
                    </div>-->
                </div>
                <div required>
                    <?php if($model->m_get('type')==$model::PRODUCT_TYPE_POINT):?>
                    <?php else:?>
                    <?php endif;?>
                    <div>价格/积分</div>
                    <div class="flexgrow flex">
                        <div class="input maright"><input required name="price_package" placeholder="" id="el_price_package" value="<?php $price_package = $model->m_get('price_package'); if( $price_package ) {echo $price_package;}else {echo 0.01;} ?>"></div>
                        <div class="input"><input required name="price_market" placeholder="门市价" id="el_price_market" value="<?php $price_market = $model->m_get('price_market'); if( $price_market ) {echo $price_market;}else {echo 0.01;} ?>"></div>
                    </div>
                </div>
                <div required>
                    <div>总库存</div>
                    <div class="input flexgrow"><input required name="stock" placeholder="" value="<?php $stock = $model->m_get('stock'); if( $stock ) {echo $stock;}else {echo 0;} ?>"></div>
                    <!-- <div><label class="check"><input type="checkbox" /><span class="diyradio"><tt></tt></span>页面不显示库存</label></div> -->
                </div>
                <div>
                    <div>商品编码</div>
                    <div class="input flexgrow"><input name="sku" placeholder="" value="<?php echo $model->m_get('sku');?>"></div>
                </div>
				<div required class="add_good_item Ldn">
					<div class="flex_aligntop">添加商品</div>
					<div class="flexgrow"><span class="button void xs marbtm add_good_btn">添加商品</span> </div>
					<input type="hidden" name="combine_products" class="combine_products"/>
				</div>
				<div class="add_good_item Ldn">
					<div class="flex_aligntop">商品内容</div>
					<div class="flexgrow good_show_area" id="good_show_area"></div>
				</div>
            </div>
        </div>
        <div class="layer add_hotel">
            <div class="add_hotel_content">
            	<div class="bg_fff bd pad10">
                    <label class="check allhotelcheck"><input type="checkbox"><span class="diyradio"><tt></tt></span>全选所有</label>
                </div>
            	<div class="bg_fff bd pad10 martop">
                    <div><div class="input"><input type="text" class="searchtable" placeholder="搜索酒店名"></div></div>
                    <table id="coupons_table" class="table martop">
                        <thead><tr><th>酒店</th><th>房型</th></tr></thead>
                        <tbody id="hotel_content">

                        </tbody>
                    </table>
                    <div class="center"><ul class="pagination page_concol"><li class="paginate_button active"><a href="#">1</a></li></ul></div>
                </div>
            	<!-- <div class="bg_fff bd center pad10 layer_success martop"><button class="bg_main button spaced" type="button">保存</button></div> -->
            </div>
        </div>
        <div class="layer add_good">
            <div class="add_good_content">
            	数据正在加载中……
            	<!-- <div class="bg_fff bd center pad10 layer_success martop"><button class="bg_main button spaced" type="button">保存</button></div> -->
            </div>
        </div>
        <div class="whitetable">
            <div>
                <span style="border-color:#4caf50">商品属性</span>
            </div>
            <div class="bd_left list_layout">
                <div required class="combination_change_item noapi zyb coupon_item">
                    <div>微信订房</div>
                    <div class="flex flexcenter">
                        <label class="check"><input name="can_wx_booking" type="radio" value="<?php echo $model::CAN_NOT_WX_BOOKING;?>" checked /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input name="can_wx_booking" type="radio" value="<?php echo $model::CAN_WX_BOOKING;?>" <?php if($model->m_get('can_wx_booking')==$model::CAN_WX_BOOKING)echo 'checked';?> /><span class="diyradio"><tt></tt></span>支持“套票转预订”</label>
                        <label class="check"><input name="can_wx_booking" type="radio" value="<?php echo $model::CAN_PASS_TO_HOTEL_MODEL;?>" <?php if($model->m_get('can_wx_booking')==$model::CAN_PASS_TO_HOTEL_MODEL || $from == 'hotel')echo 'checked';?> /><span class="diyradio"><tt></tt></span>支持“订房套餐预订”</label>
                    </div>
                    <span class="button void xs marbtm add_hotel_btn" id="showBookingConfig" <?php $can_wx_booking=$model->m_get('can_wx_booking'); if( !$can_wx_booking || $can_wx_booking==$model::CAN_NOT_WX_BOOKING)echo 'style="display:none"';?>>添加适用门店</span>
                </div>
                <div required class="combination_change_item noapi zyb coupon_item">
                    <div>拆分使用</div>
                    <div class="flex flexcenter">
                        <label class="check"><input name="can_split_use" type="radio" value="<?php echo $model::STATUS_CAN_NO;?>" checked /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input name="can_split_use" type="radio" value="<?php echo $model::STATUS_CAN_YES;?>" <?php if($model->m_get('can_split_use')==$model::STATUS_CAN_YES)echo 'checked';?> /><span class="diyradio"><tt></tt></span>支持</label>
                	   <div class="input" id="el_can_split_use" <?php $can_split_use=$model->m_get('can_split_use'); if( !$can_split_use || $can_split_use==$model::STATUS_CAN_NO)echo 'style="display:none"';?> ><input name="use_cnt" placeholder="请输入可拆分数量" value="<?php $use_cnt = $model->m_get('use_cnt'); if( $use_cnt ) {echo $use_cnt;} ?>"></div>
                    </div>
                </div>
                <div required class="combination_change_item coupon_item">
                    <div>商品退款</div>
                    <div class="flex flexcenter">
                        <label class="check"><input name="can_refund" type="radio" value="<?php echo $model::CAN_REFUND_STATUS_FAIL;?>" checked /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input name="can_refund" type="radio" value="<?php echo $model::CAN_REFUND_STATUS_SEVEN;?>" <?php if($model->m_get('can_refund')==$model::CAN_REFUND_STATUS_SEVEN)echo 'checked';?> /><span class="diyradio"><tt></tt></span>七天退款</label>
                        <label class="check"><input name="can_refund" type="radio" value="<?php echo $model::CAN_REFUND_STATUS_ANY_TIME;?>" <?php if($model->m_get('can_refund')==$model::CAN_REFUND_STATUS_ANY_TIME)echo 'checked';?> /><span class="diyradio"><tt></tt></span>随时退款</label>
                    </div>
                </div>
                <div required class="combination_change_item noapi zyb coupon_item">
                    <div>商品邮寄</div>
                    <div class="flex flexcenter">
                        <label class="check"><input name="can_mail" type="radio" value="<?php echo $model::STATUS_CAN_NO;?>" checked /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input name="can_mail" type="radio" value="<?php echo $model::STATUS_CAN_YES;?>" <?php if($model->m_get('can_mail')==$model::STATUS_CAN_YES)echo 'checked';?> /><span class="diyradio"><tt></tt></span>支持</label>
                        <label class="check" style="display:none"><input name="is_hide_reserve_date" type="checkbox" value="<?php echo $model::STATUS_CAN_YES;?>" <?php if($model->m_get('is_hide_reserve_date')==$model::STATUS_CAN_YES)echo 'checked';?> /><span class="diyradio"><tt></tt></span>支持预约邮寄</label>
                    </div>
                </div>
                <div class="el_can_mail" style="display:none">
                	<div>邮费单位</div>
                    <div class="input flexgrow"><input name="shipping_fee_unit" value="<?php echo $model->m_get('shipping_fee_unit');?>"></div>
                </div>
                <div class="el_can_mail" style="display:none">
                	<div>补邮商品</div>
                    <div class="input flexgrow">
                        <!-- <input name="shipping_product_id" value="<?php echo $model->m_get('shipping_product_id');?>"> -->
                        <?php if($date_type):?>
                            <select name="shipping_product_id">
                                <!-- <?php foreach($date_type as $k=>$v):?>
                                    <option value=""><?php echo $v;?></option>
                                <?php endforeach;?> -->
                                 <?php echo $model->get_shipping_product_select_html($shipping_product_list); ?>
                            </select>
                        <?php endif;?>
                    </div>
                </div>
                <div class="el_can_mail" style="display:none">
                	<div>邮费说明</div>
                    <div class="input flexgrow"><input name="shipping_instruction" value="<?php echo $model->m_get('shipping_instruction');?>"></div>
                </div>
                <div required class="combination_change_item noapi zyb coupon_item">
                    <div>商品转赠</div>
                    <div class="flex flexcenter">
                        <label class="check"><input name="can_gift" type="radio" value="<?php echo $model::STATUS_CAN_NO;?>" <?php if($model->m_get('can_gift')==$model::STATUS_CAN_NO)echo 'checked';?> /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input name="can_gift" type="radio" value="<?php echo $model::STATUS_CAN_YES;?>" <?php if(empty($model->m_get('can_gift'))||$model->m_get('can_gift')==$model::STATUS_CAN_YES)echo 'checked';?> /><span class="diyradio"><tt></tt></span>支持</label>
                    </div>
                </div>
                <div required class="combination_change_item">
                    <div>到店消费</div>
                    <div class="flex flexcenter">
                        <label class="check"><input name="can_pickup" type="radio" value="<?php echo $model::STATUS_CAN_NO;?>" <?php if($model->m_get('can_pickup')==$model::STATUS_CAN_NO)echo 'checked';?> /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input name="can_pickup" type="radio" value="<?php echo $model::STATUS_CAN_YES;?>" <?php if(empty($model->m_get('can_pickup'))||$model->m_get('can_pickup')==$model::STATUS_CAN_YES)echo 'checked';?> /><span class="diyradio"><tt></tt></span>支持</label>
                    </div>
                </div>
                <!-- <div required class="combination_change_item"> -->
                <div required >
                    <div>短信通知</div>
                    <div class="flex flexcenter">
                        <label class="check"><input name="can_sms_notify" type="radio" value="<?php echo $model::STATUS_CAN_NO;?>" <?php if($model->m_get('can_sms_notify')==$model::STATUS_CAN_NO)echo 'checked';?> /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input name="can_sms_notify" type="radio" value="<?php echo $model::STATUS_CAN_YES;?>" <?php if(empty($model->m_get('can_sms_notify'))||$model->m_get('can_sms_notify')==$model::STATUS_CAN_YES)echo 'checked';?> /><span class="diyradio"><tt></tt></span>支持</label>
                    </div>
                </div>
                <div required class="combination_change_item coupon_item">
                    <div>提前预约</div>
                    <div class="flex flexcenter">
                        <label class="check"><input name="can_reserve" type="radio" value="<?php echo $model::STATUS_CAN_NO;?>" checked /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input name="can_reserve" type="radio" value="<?php echo $model::STATUS_CAN_YES;?>" <?php if($model->m_get('can_reserve')==$model::STATUS_CAN_YES)echo 'checked';?> /><span class="diyradio"><tt></tt></span>支持</label>
                    	<div class="input flexgrow" style="display:none"><input name="hotel_tel" placeholder="预约电话" value="<?php echo $model->m_get('hotel_tel');?>"></div>
                    </div>
                </div>
                <div required>
                    <div>已售数据</div>
                    <div class="flex flexcenter">
                        <label class="check"><input name="show_sales_cnt" type="radio" value="<?php echo $model::STATUS_CAN_NO;?>" <?php if($model->m_get('show_sales_cnt')==$model::STATUS_CAN_NO)echo 'checked';?> /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input name="show_sales_cnt" type="radio" value="<?php echo $model::STATUS_CAN_YES;?>" <?php if(empty($model->m_get('show_sales_cnt'))||$model->m_get('show_sales_cnt')==$model::STATUS_CAN_YES)echo 'checked';?> /><span class="diyradio"><tt></tt></span>支持</label>
                    </div>
                </div>
                <div required class="combination_change_item">
                    <div>开具发票</div>
                    <div class="flex flexcenter">
                        <label class="check"><input name="can_invoice" type="radio" value="<?php echo $model::STATUS_CAN_NO;?>" checked /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input name="can_invoice" type="radio" value="<?php echo $model::STATUS_CAN_YES;?>" <?php if($model->m_get('can_invoice')==$model::STATUS_CAN_YES)echo 'checked';?> /><span class="diyradio"><tt></tt></span>支持</label>
                    </div>
                </div>
                <div required>
                    <div>首页展示</div>
                    <div class="flex flexcenter">
                        <label class="check"><input name="is_hide" type="radio" value="<?php echo $model::STATUS_CAN_NO;?>" <?php if($model->m_get('is_hide')==$model::STATUS_CAN_NO)echo 'checked';?> /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input name="is_hide" type="radio" value="<?php echo $model::STATUS_CAN_YES;?>" <?php if(empty($model->m_get('is_hide'))||$model->m_get('is_hide')==$model::STATUS_CAN_YES)echo 'checked';?> /><span class="diyradio"><tt></tt></span>支持</label>
                    </div>
                </div>
                <div required >
                    <div>上架时间</div>
                    <div class="input flexgrow"><input required class="datepicker" name="validity_date" value="<?php $validity_date=$model->m_get('validity_date'); 
                        if( $validity_date ){echo $validity_date;}else{echo date('Y-m-d H:i:s',time());} ?>">
                    </div>
                </div>
                <div required>
                    <div>下架时间</div>
                    <div class="input flexgrow"><input required class="datepicker" name="un_validity_date" value="<?php $un_validity_date=$model->m_get('un_validity_date'); 
                        if( $un_validity_date ){echo $un_validity_date;}else{echo date('Y-m-d H:i:s',time()+86400);} ?>">
                    </div>
                </div>
                <div required class="dateuse_hide">
                    <div>失效模式</div>
                    <?php if($date_type):?>
                        <div class="input flexgrow"><select name="date_type" id="dateType">
                        <?php $dateType = ''; foreach($date_type as $k=>$v):?>
                        <option value="<?php echo $k;?>" <?php if($model->m_get('date_type')==$k) {echo 'selected';$dateType = $k;}?>><?php echo $v;?></option>
                        <?php endforeach;?>
                        </select></div>
                    <?php endif;?>
					<input type="hidden" name="date_type" disabled value="<?php echo $model->m_get('date_type'); ?>"/>
					
                </div>
                <div required class="dateuse_hide">
                    <div name="date_type"><?php if( $dateType == $model::DATE_TYPE_STATIC ):?>固定失效时间<?php else:?>存活时间<?php endif;?></div>
                    <div class="input flexgrow dateuse_hide_item-1 dateuse_hide_item" <?php if( $dateType == $model::DATE_TYPE_FLOAT ):?>style="display:none;"<?php endif;?>><input class="datepicker" name="expiration_date" value="<?php $expiration_date=$model->m_get('expiration_date');
                        if( $expiration_date ){echo $expiration_date;}else{echo date('Y-m-d H:i:s',time()+86400);} ?>"></div>
                    <div class="input flexgrow dateuse_hide_item-1 dateuse_hide_item" <?php if( $dateType == $model::DATE_TYPE_STATIC ):?>style="display:none;max-width: 400px;"<?php else:?>style="max-width: 400px;"<?php endif;?> ><input name="use_date" value="<?php echo $model->m_get('use_date');?>"></div>
					<div class="input flexgrow dateuse_hide_item-2 dateuse_hide_item" style="display:none;max-width:400px;"><input  value="2020-12-31 23:59:59" readonly="readonly"/></div>
					
                </div>
                <div class="<?php if($model->m_get('goods_type')!=$model::SPEC_TYPE_TICKET || $model->m_get('conn_devices')!=$model::DEVICE_ZHIYOUBAO)echo 'none';?> zyb2">
                    <div>备注：</div>
                    <div>对接智游宝特性 1.微信订房 不支持 2.拆分使用 不支持 3.商品邮寄 不支持 4.商品转赠 不支持</div>
                </div>
                
                 <div id="coupon_features">
                    <div>升级房券特性：</div>
                    <div>1.微信订房 不支持  2.拆分使用 不支持 3.商品邮寄 不支持  4.商品转赠 不支持  5.微信退款不支持 6.提前预约 不支持</div>
                </div>

            </div>
        </div>    
        <div class="whitetable">
            <div>
                <span style="border-color:#af4cac">商品详情</span>
            </div>
            <div class="bd_left list_layout">
                <div>
                    <div class="flex_aligntop">商品内容</div>
                    <div class="flexgrow">
                    <?php if($compose):?>
                        <?php foreach($compose as $k=>$v):?>
                            <div class="flex flexgrow" style="margin-bottom:2px">
                                <div class="input"><input name="compose[<?php echo $k; ?>][content]" placeholder="商品内容" value="<?php echo $v['content'];?>"></div>
                                <div class="input"><input name="compose[<?php echo $k; ?>][num]" placeholder="商品数量" type="number" value="<?php echo $v['num'];?>"></div>
                            </div>
                        <?php endforeach;?>
                    <?php endif;?>
                    </div>
                </div>
                <div class="en">
                    <div class="flex_aligntop">Description</div>
                    <div class="flexgrow">
                    <?php if($compose_en):?>
                        <?php foreach($compose_en as $k=>$v):?>
                            <div class="flex flexgrow" style="margin-bottom:2px">
                                <div class="input"><input name="compose_en[<?php echo $k; ?>][content]" placeholder="Description" value="<?php echo $v['content'];?>"></div>
                                <div class="input"><input name="compose_en[<?php echo $k; ?>][num]" placeholder="Quantity" type="number" value="<?php echo $v['num'];?>"></div>
                            </div>
                        <?php endforeach;?>
                    <?php endif;?>
                    </div>
                </div>
                <div>
                    <div class="flex_aligntop">购买须知</div>
                    <div class="flexgrow"><textarea name="order_notice" id="order_notice"><?php echo $model->m_get('order_notice');?></textarea></div>
                </div>
                <div class="en">
                    <div class="flex_aligntop">Kindly Reminder</div>
                    <div class="flexgrow"><textarea name="order_notice_en" id="order_notice_en"><?php echo $model->m_get('order_notice_en');?></textarea></div>
                </div>
                <div>
                    <div class="flex_aligntop">图文详情</div>
                    <div class="flexgrow"><textarea name="img_detail" id="img_detail"><?php echo $model->m_get('img_detail');?></textarea></div>
                </div>
                <div class="en">
                    <div class="flex_aligntop">Details</div>
                    <div class="flexgrow"><textarea name="img_detail_en" id="img_detail_en"><?php echo $model->m_get('img_detail_en');?></textarea></div>
                </div>
            </div>
        </div>
    
        <div class="whitetable">
            <div>
                <span style="border-color:#ebe814">其他设置</span>
            </div>
            <div class="bd_left list_layout">
                <div>
                    <div>酒店地址</div>
                    <div class="input flexgrow"><input required name="hotel_address" value="<?php echo $model->m_get('hotel_address');?>"></div>
                </div>
                <div class="en">
                    <div>Address</div>
                    <div class="input flexgrow"><input required name="hotel_address_en" value="<?php echo $model->m_get('hotel_address_en');?>"></div>
                </div>
                <div required>
                    <div>商品排序</div>
                    <div class="input flexgrow"><input required name="sort" value="<?php $sort=$model->m_get('sort'); 
                        if( $sort ){echo $sort;}else{echo 1;} ?>"></div>
                </div>
                <div required>
                    <div>商品状态</div>
                    <div class="input flexgrow">
                        <select name="status" required>
                            <?php if($status_type):?>
                                <?php foreach($status_type as $k=>$v):?>
                                    <?php if($k != 2):?>
                                        <option value="<?php echo $k;?>" <?php if( $model->m_get('status') == $k )echo 'selected';?>><?php echo $v;?></option>
                                    <?php endif;?>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="spec_list" />
        <input type="hidden" name="delete_spec" />
        <input type="hidden" name="dateset" />
        <div class="bg_fff bd center pad10"><button class="bg_main button spaced" type="button" id="save">保存配置</button></div>
    <?php echo form_close() ?>
<!--
    <?php //if( $model->m_get($pk) ):?>
        <?php //echo form_open( Soma_const_url::inst()->get_url('*/*/edit_focus'), array('class'=>'form-horizontal','id'=>'saveImg','enctype'=>'multipart/form-data' ), array($pk=>$model->m_get($pk), 'inter_id' =>$model->m_get('inter_id') ) ); ?>
            <div class="whitetable martop">
                <div> <span style="border-color:#3f51b5">相册管理</span> </div>
                <div class="bd_left list_layout">
                    <div id="gallery_img_show">
                        <div>所有产品图片</div>
                        <div>
                            <div class="addimgs trim">
                            <?php /*if(!empty($gallery)):?>
                                <?php foreach($gallery as $v):?>
                                    <div class="addimg candelete" img_id="<?php echo $v['gry_id'];?>" pro_id="<?php echo $v['product_id'];?>" title="<?php $v['gry_intro'];?>" style="cursor:pointer"><del></del><div><img src="<?php echo $v['gry_url'];?>"/></div></div>
                                <?php endforeach;?>
                            <?php endif;*/?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div>当前描述</div>
                        <div class="flexgrow" id="gallery_img_info"></div>
                    </div>
                </div>
            </div>
            <div class="whitetable">
                <div>   <span style="border-color:#3f51b5">上传预览</span></div>
                <div class="bd_left list_layout">
                    <div>
                        <div>上传产品图片</div>
                        <div>
                            <input type="hidden" name="gallery" id="el_gallery_img" value=''>
                            <div id="gallery_img" class="addimgs trim"></div>
                            <div id="gallery_img_add"></div>
                        </div>
                        <div class="layoutfoot">图片大小必须 &lt; 500KB; </div>
                    </div>
                    <div>
                        <div>图片描述</div>
                        <div class="input flexgrow"><input type="text" name="gry_intro" value=""></div>
                    </div>
                </div>
            </div>
            <div class="bg_fff bd center pad10"><button class="bg_main button spaced" type="button" id="postImg">上传到相册</button></div>
        <?php //echo form_close() ?>
    <?php //endif;?>
    -->

    </section>
</div><!-- /.content-wrapper -->
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



<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css"></script>
<!--
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
-->
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify_html5/jquery.uploadify.js"></script>
<script src="http://test008.iwide.cn/public/js/areaData.js"></script>
</body>
<script>
$('#selecter').on('change', function() {
    var _this = $(this);
    if (_this.val()) {
        $('#hotel_id').val(_this.val());
        console.log($('#hotel_id').val());
    }
});


// selecter
//初始化规格表;
var addimgstr	= '<label class="addimg"><input type="file"></label>';
var inputstr	= '<input class="input xs" tmpval="" title="点击修改">';
var setid		= <?php echo $auto_increment_id;?>;
var del_sn		= [];
var del_spec	= [];
var first		= true;
var setid_specid= {};
/*
JSON 数据示例
颜色-黄色 id:1, 尺寸-M id:1,类型-运动 id:5;  specid:115
颜色-黄色 id:1, 尺寸-M id:1,类型-热血 id:3;  specid:113
*/
<?php if( empty($spec_list) ):?>

var SpecData = {
	spec_type:[],
	spec_type_id:[],
	setting_id:[],
	spec_id:[],
	spec_name:[],
	spec_name_id:[],
/*	spec_type:['颜色','尺寸'],
	spec_type_id:['1','2'],   //修改
	setting_id:[11873,11874,11875,11876],
	spec_id:['11','12','21','22'],
	spec_name:[['1','2'],['1','2']],  //修改
	spec_name_id:[['1','2'],['1','2']],  //修改
	sepc_images:[],  //规格图片*/
	spec_static_name:['价格|积分','库存','SKU'],
	spec_static_en:['specprice','stock','sku'],
	data:{
//		'11873':{setting_id:11873,sku:"",spec_id:"11",spec_name:[1,1],spec_name_id:[1,1],specprice:"24",stock:""},
//		'11874':{setting_id:11874,sku:"",spec_id:"12",spec_name:[1,2],spec_name_id:[1,2],specprice:"24",stock:""},
//		'11875':{setting_id:11875,sku:"",spec_id:"21",spec_name:[2,1],spec_name_id:[2,1],specprice:"24",stock:""},
//		'11876':{setting_id:11876,sku:"",spec_id:"22",spec_name:[2,2],spec_name_id:[2,2],specprice:"24",stock:""},
//		'111':{
			
//			specid:'111', 		/*规格ID，和外层的spec_id数组对应*/
//			spec_name:['黄色','M','运动'],  //修改
//			spec_name_id:['1','1','1'],	   //修改
//			
//			specprice:500,		/*规格价格*/
//			stock:99,			/*库存*/
//			sku:'青春',			/*sku*/
//		},
	}
};
<?php else:?>
var SpecData = <?php echo $spec_list;?>;
(function(){
	if(SpecData.data.length<=0){
		SpecData.data={};
	}
	if(SpecData.spec_name.length>0){
		var limit = $('#model').attr('limit')!=undefined ?$('#model').attr('limit'):3;
		if(SpecData.spec_type.length==limit){
			$('#addSpec').hide();
		}
		for(var i =0;i <SpecData.spec_type.length;i++){
			var clone = $('#model').clone();
			clone.removeClass('hide').removeAttr('id');
			$("#model").before(clone);
			$('.selectSpec',clone).val(SpecData.spec_type_id[i]);
			$('.selectSpec',clone).bind('change',selectChange);
			$('.addNewSpec',clone).bind('click',addNewSpec);
			$('del',clone).on('click',SpecBoxDel);
			for(var j = 0;j<SpecData.spec_name[i].length;j++){
				var diySpec =  $('<span class="diySpec candelete">'+inputstr+'<del title="删除"></del></span>');
				diySpec.find('input').val(SpecData.spec_name[i][j]);
				diySpec.find('input').get(0).onblur=Diyspec;
				diySpec.find('input').attr('tmpval',SpecData.spec_name[i][j]);
				diySpec.find('del').get(0).onclick=deldiySpec;
				$('.addNewSpec',clone).before(diySpec);
				if(SpecData.spec_name[i][j].length>=5)$('.addNewSpec',clone).hide();
			}
		}
	}
}())
<?php endif;?>
//function showNext(){$('#Next').show();}
function isNull(){if(!jQuery.isEmptyObject(SpecData)&&SpecData.spec_type_id.length>0&&SpecData.spec_name_id.length>0)return false;else return true;}
function NewArray(array,index){  //将一个多维数组里的元素两两相乘合并为一个新数组
	array = array.concat();
	if(index==undefined)index=0;
	if(index+1>=array.length){return array=array[index];}
	var sum = [],i = 0,a=array[index].length,b=array[index+1].length;
	for(var m=0;m<a;m++)
		for(var n=0;n<b;n++)
		sum[i++]=array[index][m].toString()+array[index+1][n].toString();
	array.splice(index,2,sum);
	return NewArray(array,index);
}
function getRow(array,index,getCount){
	if(index==undefined)index=0;
	if(getCount==undefined)getCount=false;
	if(getCount){
		if(index-1<0)return array[index].length;
		return array[index].length*getRow(array,index-1,getCount);
	}else{
		if(index+1>=array.length)return array[index].length;
		return array[index].length*getRow(array,index+1);
	}
}
function showSpecTable(){
	console.log(SpecData);
	if(isNull()){	$('#SpecSet').hide();	return;	}
	$('#SpecSet').show();
	var _this = $('#SpecSet'), layernum = SpecData.spec_type_id.length , str = '';
	for(var i = 0;i<layernum;i++)str += '<div>'+SpecData.spec_type[i]+'</div>';
	for(var i = 0;i<SpecData.spec_static_name.length;i++)str+='<div>'+SpecData.spec_static_name[i]+'</div>';
	$('.thead',_this).html(str);
	//填充表格列
	str = '';
	for(var i=0;i<SpecData.spec_name.length;i++){
		var row = getRow(SpecData.spec_name,i)/SpecData.spec_name[i].length;
		var count=getRow(SpecData.spec_name,i,true);
		var css = 'height:'+ row*36+'px;line-height:'+ row*36+'px;padding:0';
		str+='<div class="multirow">';
		for(var m=0;m<count/SpecData.spec_name[i].length;m++)
			for( var j= 0;j<SpecData.spec_name[i].length;j++)
			str+='<div style="'+css+'"><span style="'+css+'">'+SpecData.spec_name[i][j]+'</span></div>';
		str+='</div>';
	}
	var data = SpecData.spec_id;
	for(var i = 0;i<SpecData.spec_static_en.length;i++){
		str+='<div class="multirow">';
		for(var j=0;j<data.length;j++){
			var id = SpecData.setting_id[j];
			var en =SpecData.spec_static_en[i];
			str+='<div><input class="input xs '+en+'" onChange="alterVal(\''+en+'\',this)" value="';
				if(SpecData.data[id]!=undefined) str+=SpecData.data[id][en];
			str+='" /></div>';
		}
		str+='</div>';
	}
	$('.tbody',_this).html(str);
}
function alterVal(en,_this){ //修改静态值 （价格/sKu/库存)
	var index  = $(_this).parent().index();
	var specid = SpecData.spec_id[index];
	for(var i=0;i<index;i++){
		if(SpecData.setting_id[i]==undefined)
			SpecData.setting_id[i]='';
	}
	if(SpecData.setting_id[index]==undefined||SpecData.setting_id[index]==''){
		SpecData.setting_id[index] = setid;setid++;
	}
	var id = SpecData.setting_id[index];
	var  array = specid.length>1?specid.split(""):[specid];
	//console.log(array,id);
	if(SpecData.data[id]==undefined){
		SpecData.data[id]={}
		SpecData.data[id]['spec_name']		=[];
		SpecData.data[id]['spec_name_id']	=[];
		SpecData.data[id]['spec_id']		=specid;
		SpecData.data[id]['setting_id']		=id;
		for(var j = 0;j<SpecData.spec_static_en.length;j++){
			SpecData.data[id][SpecData.spec_static_en[j]]='';
		}
		for(var i=0;i<array.length;i++){
			//console.log(array);
			SpecData.data[id].spec_name[i]=SpecData.spec_name[i][array[i]-1];
			SpecData.data[id].spec_name_id[i]=SpecData.spec_name_id[i][array[i]-1];
		}
	}
	SpecData.data[id][en]=$(_this).val();
}
function selectChange(){showNext();}
function deldiySpec(){
	$this = $(this);
	//event.stopPropagation();
	if(window.confirm('是否删除？')){
		var i	= $this.parents('.SpecBox').index()-1,exist=false;
		var val	= $this.siblings('input').val();
		$this.parent().siblings('.addNewSpec').show();
		if($this.parents('.SpecBox').find('.diySpec').length<=1) clearval();
		$this.parent().remove();
		if(SpecData.spec_id.length>0){
			var array = SpecData.setting_id.concat();
			for(var m=array.length-1;m>=0;m--){
				if(array[m]!=''&&SpecData.data[array[m]]!=undefined){
					if(val==SpecData.data[array[m]].spec_name[i]){
						del_spec.push(array[m]);
						delete SpecData.data[array[m]];
					}
				}
			}
			var spec_id= SpecData.spec_name_id.concat();
			spec_id.splice(i,1,[$this.index()+1]);
			spec_id= NewArray(spec_id);
			var delid = [];
			for(var j = 0;j<spec_id.length;j++){
				for(var k=SpecData.spec_id.length-1;k>=0;k--){
						//console.log(spec_id[j],SpecData.spec_id[k])
					if(spec_id[j]==SpecData.spec_id[k]&&SpecData.setting_id[k]!=undefined){
						delid.push(k);
					}
				}
			}
			for(var j=delid.length-1;j>=0;j--)SpecData.setting_id.splice(delid[j],1);
		}
		showNext();
		$.each(SpecData.data,function(m,n){
			for(var k=0;k<n.spec_name.length;k++){
				for(var l=0;l<SpecData.spec_name[k].length;l++){
					if( n.spec_name[k]==SpecData.spec_name[k][l]){
						n.spec_name_id[k]=SpecData.spec_name_id[k][l];
						n.spec_id=n.spec_name_id.join('');
						break;
					}
				}
			}
		})
	}
} 
function alterName(a,b,val){
	a = Number(a);
	$.each(SpecData.data,function(i,n){
		if(n.spec_name_id[a]==b+1){
		console.log(n.spec_name_id[a],b)
			n.spec_name[a]=val;
		}
	});
}
function Diyspec(){
	var val		= $(this).val();
	var tmpval	= $(this).attr('tmpval');
	var exist	= false;
	if( val !=''){
		var others	= $(this).parent().siblings().find('input');
		for( var i	= 0;i< others.length; i++){
			if( others.eq(i).val() == val){
				exist = true;
				break;
			}
		}
		if(exist){
			alert('已存在的规格');
			$(this).val(tmpval);
		}else if(val!=tmpval){
			var i = $(this).parents('.SpecBox').index()-1, j = 0 ;
			/*if(del_sn[i]==undefined)del_sn[i]=[];
			while(j<del_sn[i].length){
				if( del_sn[i][j] == val){
					del_sn[i].splice(j,1);
					break;
				}
				j++;
			}*/
			$(this).attr('tmpval',val);
			//console.log(i,$(this).parent().index())
			alterName(i,$(this).parent().index(),val);
			showNext();
		}
	}
	if( $(this).val()=='')	$(this).parent().remove();
}
function addNewSpec(){
	var parent = $(this).parents('.SpecBox');
	var add= $('<span class="diySpec candelete">'+inputstr+'<del title="删除"></del></span>');
	if($(this).siblings('.diySpec').length>=4){
		$(this).hide();
	}
	$(this).before(add);
	add.find('input').get(0).onblur=Diyspec;
	add.find('del').get(0).onclick=deldiySpec;
	add.find('input').focus();
}
function clearval(){
	for(var i=0;i<SpecData.setting_id.length;i++)
		del_spec.push(SpecData.setting_id[i]);
	SpecData.setting_id=[];
	SpecData.spec_name=[];
	SpecData.spec_name_id=[];
	SpecData.data={};
	SpecData.spec_type=[];
	SpecData.spec_type_id=[];
	SpecData.spec_id=[];
	SpecData.setting_id=[];
}
function SpecBoxDel(noConfirm,ele){
	//event.stopPropagation();
	var $this = ele ? ele : $(this)
	if( noConfirm || window.confirm('将清空数据表，是否删除?')){
		clearval();
		$this.parent().remove();
		$('#addSpec').show();
		showNext();
		if($('.SpecBox').length<=1)$('.show2').show();
	}
}
$('#addSpec').click(function(e){
	var limit = $('#model').attr('limit')!=undefined ?$('#model').attr('limit'):3;
	if($('.SpecBox').length==limit) $(this).hide();
	var clone = $('#model').clone();
	clone.removeClass('hide').removeAttr('id');
	$("#model").before(clone);
	$('.selectSpec',clone).bind('change',selectChange);
	$('.addNewSpec',clone).bind('click',addNewSpec);
	$('del',clone).on('click',SpecBoxDel);
	$('.show2').hide();
});
function showNext(){
	var _parent = $('#SpecControls');
	if($('.SpecBox',_parent).length<=1){ showSpecTable();return;}
	var index = 0;
	if($('.SpecBox',_parent).length-1!=SpecData.spec_name.length){
		clearval();
	}/*
	if(SpecData.spec_id.length>0){
		var array = SpecData.setting_id.concat();
		//console.log(array);
	  for(var i = 0;i<del_sn.length;i++){
		if(del_sn[i]==undefined)del_sn[i]=[];
		for(var j=0;j<del_sn[i].length;j++)
			for(var m=array.length-1;m>=0;m--){
				if(SpecData.data[array[m]]!=undefined){
					if(del_sn[i][j]==SpecData.data[array[m]].spec_name[i]){
						del_spec.push(array[m]);
						SpecData.setting_id.splice($.inArray(array[m],SpecData.setting_id),1);
						delete SpecData.data[array[m]];
					}
				}
			}
	  }
	}*/
	var tmp = SpecData.spec_id.concat();
	SpecData.spec_type=[];
	SpecData.spec_type_id=[];
	SpecData.spec_name=[];
	SpecData.spec_name_id=[];
	for(var i = 0;i<$('.SpecBox',_parent).length-1;i++){
		var _this = $('.SpecBox',_parent).eq(i);
		for(var j=0;j<$('.diySpec',_this).length;j++){
			SpecData.spec_type[index]		=_this.find('option:selected').text();
			SpecData.spec_type_id[index]	=_this.find('.selectSpec').val();
			if( j==0 ){
				SpecData.spec_name[index]	=[];
				SpecData.spec_name_id[index]=[];
			}
			SpecData.spec_name[index][j]	= $('.diySpec',_this).eq(j).find('input').val();
			SpecData.spec_name_id[index][j]	= j+1;
		}
		if($('.diySpec',_this).length>0)index++;
	}
	if(SpecData.spec_name_id.length>0)SpecData.spec_id = NewArray(SpecData.spec_name_id);
	if( SpecData.setting_id.length>0){
		 var i=tmp.length-1;j=SpecData.spec_id.length-1;
		 //console.log(tmp,SpecData.spec_id)
		 if(tmp.length<SpecData.spec_id.length){
			 while(i>=0){
				if(tmp[i]==SpecData.spec_id[j]) {i--;}
				else{
					//console.log(i);
					SpecData.setting_id.splice(i+1,0,'');
				}
				j--;
			 }
		 }/*else if(tmp.length>SpecData.spec_id.length){
			 while(i<SpecData.spec_id.length){
				if(tmp[j]==SpecData.spec_id[i]) {i++;}
				else{
					console.log('b')
					SpecData.setting_id.splice(i,1);
				}
				j++;
			 }
		 }*/
	}
	showSpecTable();
}
showSpecTable();
//规格JS----end 
$(".datepicker").datetimepicker({
	format:"yyyy-mm-dd hh:ii:ss", language: "zh-CN",clearBtn: false,todayBtn: false,orientation: "auto left",
});

$('.silde_layer>*').click(function(){
	$(this).parent().siblings().find('input').val($(this).html());
	$(this).parent().siblings('input').val($(this).attr('data'));
	if($(this).parent().siblings('input').attr('name')=='hotel_id')
		changeHotel($(this).attr('data'))
})
$('.select_input input').bind('input propertychange',function(){
	var _this = $(this).parent().siblings('.silde_layer').find('div');
	var val = $(this).val();
	if(val==''){
		_this.show();
	}else{
		_this.each(function(){
			if($(this).html().indexOf(val)>=0){
				$(this).show()
			}else{
				$(this).hide();
			}
		});
	}
});

function delimg(){  //缩略图
	$(this).parent().remove();
	$("#el_face_img").val('');
	$('#face_img_add').show();
}
$('#face_img_add').uploadify({//缩略图
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
		$('#el_face_img').val(res.url);
		var dom = $('<div class="addimg candelete"><del></del><div><img src="'+res.url+'"/></div></div>');
		$("#face_img").append(dom);
		dom.find('del').get(0).onclick=delimg;
		$('#face_img_add').hide();
	},   
	'onUploadError': function () {  
		alert('上传失败');  
	}
});
function delgallery(){  //相册
	$(this).parent().remove();
	$('#gallery_img_add').show();
	var thisurl = $(this).parent().find('img').attr('src');
	$(this).parent().remove();
	var detail_img = $.parseJSON($("#el_gallery_img").val());
	for(var k=0;k<detail_img.length;k++){
		if(detail_img[k] == thisurl){
			detail_img.splice(k,1);
		}
	}
	detail_img = JSON.stringify(detail_img);
	$("#el_gallery_img").val(detail_img);
}
$('#gallery_img_add').uploadify({//缩略图
	'formData'     : {
        '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
		'timestamp' : '<?php echo time();?>',
        'token'     : '<?php echo md5('unique_salt' . time());?>'
	},
	//'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
	'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
	'buttonImage':"<?php echo base_url(FD_PUBLIC) ?>/js/img/add_xs.png",
	'fileObjName': 'imgFile',
	'delimg':'<?php echo base_url(FD_PUBLIC) ?>/img/cancel.png',
	'fileTypeExts':'*.jpg;*.jpeg;*.gif;*.png',//文件类型
	'fileSizeLimit':'300', //限制文件大小
	'onUploadSuccess' : function(file, data) {
		var res = $.parseJSON(data);
		var detail_img = [];
		if($("#el_gallery_img").val()!=''){
			detail_img = $.parseJSON($("#el_gallery_img").val());
		}
		detail_img.push(res.url);
		if(detail_img.length>=5)
			$('#gallery_img_add').hide();
		detail_img = JSON.stringify(detail_img);

		$('#el_gallery_img').val(detail_img);
		var dom = $('<div class="addimg candelete"><del></del><div><img src="'+res.url+'"/></div></div>');
		$("#gallery_img").append(dom);
		dom.find('del').get(0).onclick=delgallery;
	},   
	'onUploadError': function () {  
		alert('上传失败');  
	}
});
if($('#face_img').find('img').length>0){
	$("#face_img del").get(0).onclick=delimg;
	$('#face_img_add').hide();
}
if($('#gallery_img').find('img').length>0){
    $("#gallery_img del").click(delgallery);
if($('#gallery_img').find('img').length>=5){
	$('#gallery_img_add').hide();
}}

$('#save').click(function(e){
	var $that = $(this);
	var bool =true;
	$('div[required]').each(function(){
		var  _this = $(this);
		_this.removeAttr('style');
		$(this).find('[required]').each(function(index, element) {
			if($(this).val()==''){
				bool = false;
				_this.css('color','#f00');
			}
        });
	});
	if($('input[name="can_split_use"]:checked').val() == <?php echo $model::STATUS_CAN_YES;?>){
		if( $('input[name="use_cnt"]').val()=='')
		{ bool=false;$('input[name="use_cnt"]').parents('[required]').css('color','#f00');}
	}
    if($('input[name="sort"]').val() > 500){
        bool = false;
        return alert('排序值不能大于500');
    }
	if($goodsType.filter(":checked").val() === '3'){
		if($.isEmptyObject(goodCheckedKeys)){
			//没选择商品
			bool = false;
			return alert('组合必须选择商品')
		}
	}
	if($('input[name=conn_devices]:checked').val()==2){
		var tmpbool = false;
		if($('input[name=sku]').val()==''){tmpbool=true;}
		$('.sku').each(function(index, element) {
            if($(this).val()=='') {tmpbool=true;}
        });
		if(tmpbool){alert('请填写商家编码和商品规格的sku');return}
	}
	if(bool){
		if($('input[name=can_wx_booking]:checked').val()==<?php echo $model::STATUS_CAN_YES;?>){
			if($('.add_hotel input:checked').length<=0){ alert('请先添加适用门店');return;}
		}
		if(DateSet.startdate==''||jQuery.isEmptyObject(DateSet.data)){
			if($('input[name="type"]:checked').val()!=2){
				$('input[name="spec_list"]').val(JSON.stringify(SpecData));
				$('input[name="delete_spec"]').val(JSON.stringify(del_spec));
			}
		}else{ $('input[name=dateset]').val(JSON.stringify(DateSet));}
		// console.log($('input[name="spec_list"]').val());return;
		// $('form').submit();
		editor1.sync();
		editor2.sync();
		editor3.sync();
		editor4.sync();
		//将商品添加进数据
		var goods = [];
		$.each(goodCheckedKeys, function(key,val){
			return goods.push(val);
		})
		$('input[name="combine_products"]').val(JSON.stringify(goods));
		//return console.log($('#saveInfo').serializeArray());
		$('#saveInfo').submit();
		$that.prop('disabled',true)
	}else{
		alert('带*为必填项');
	}
});
$('#postImg').click(function(){
    // alert(
    //$('input[name="spec_list"]').val(JSON.stringify(SpecData));
    // console.log($('input[name="spec_list"]').val());return;
    // $('form').submit();
    $('#saveImg').submit();
});
<?php 
//FULL Path: .../index.php/basic/browse/mall?t=images&p=a23523967|mall|goods|desc&token=test&CKEditor=el_gs_detail&CKEditorFuncNum=1&langCode=zh-cn
$floder= $model->m_get('inter_id')? $model->m_get('inter_id'): 'inter_id';
$subpath= $floder. '|soma|product_package|order_notice'; //基准路径定位在 /public/media/ 下
$params= array(
    't'=>'images',
    'p'=>$subpath,
    'token'=>'test' //再完善校验机制
);

$subpath_img_detail= $floder. '|soma|product_package|img_detail'; //基准路径定位在 /public/media/ 下
$params_img_detail= array(
    't'=>'images',
    'p'=>$subpath_img_detail,
    'token'=>'test' //再完善校验机制
);
?>
var editor1 ,editor2,editor3,editor4;
var show_can_mail = function(){
	var val = $('input[name="can_mail"]:checked').val()
	if(val == <?php echo $model::STATUS_CAN_YES;?>){
		$('.el_can_mail').show()
		$('input[name="is_hide_reserve_date"]').parent().show();
	}else{
		$('.el_can_mail').hide();
		$('input[name="is_hide_reserve_date"]').parent().hide();
	}
}
var show_can_reserve =function(){
	var val = $('input[name="can_reserve"]:checked').val();
	if(val == <?php echo $model::STATUS_CAN_YES;?>){
		$('input[name="hotel_tel"]').parent().show();
		$('input[name="hotel_tel"]').attr('required','true');
	}else{
		$('input[name="hotel_tel"]').parent().hide();
		$('input[name="hotel_tel"]').removeAttr('required');
	}		
}
var show_can_wx_booking=function(){
	var val = $('input[name="can_wx_booking"]:checked').val();
	if(val == <?php echo $model::STATUS_CAN_YES;?>){
		$('.add_hotel_btn').show();
	}else{
		$('.add_hotel_btn').hide();
	}		
}
var show_can_split_use=function(){
	var val = $('input[name="can_split_use"]:checked').val();
	if(val == <?php echo $model::STATUS_CAN_YES;?>){
		$('#el_can_split_use').show();
	}else{
		$('#el_can_split_use').hide();
	}       
}
var show_type_change = function(){
	var val = $('input[name="type"]:checked').val();
	if(val == 2){
		$('#carid').show();
		$('#SpecControls').hide();
		$('#SpecSet').hide();
	}else{
		$('#carid').hide();
		//add by fsy0718 组合不显示商品规格
		if($goodsType.filter(':checked').val() !== '3'){
			$('#SpecControls').show();
		}

		$('#SpecSet').show();
	}       
}
var show_date_type = function(){
	var val = $('select[name=date_type]').val();
	if(val == 2){
		$('input[name=expiration_date]').removeAttr('required').parent().hide();
		$('input[name=use_date]').attr('required',true).parent().show();
	}else{
		$('input[name=expiration_date]').attr('required',true).parent().show();
		$('input[name=use_date]').removeAttr('required').parent().hide();
	} 
    $('select[name=date_type]').val($('input[name=date_type]').val());
    $('select[name=date_type]').find('option[value='+val+']').attr('selected', true);
	$('div[name=date_type]').html($('select[name=date_type]').find('option:selected').text());
}
var show_can_getapi=function(){
	var val = $('input[name=conn_devices]:checked').val();
	if( val == 1){
		$('.noapi').show();
	}else{
		$('.noapi').hide();
	}
}
$(function () {
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
        //订购须知
         editor1 = K.create('#order_notice', {
             cssPath : '<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/kindeditor/plugins/code/prettify.css',
             uploadJson : '<?php echo Soma_const_url::inst()->get_url('basic/upload/kind_do_upload', $params);?>',
             fileManagerJson : '<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/php/file_manager_json.php',
             allowFileManager : true,
             resizeType : 1,
             items : commonItems,
             afterCreate : function() {
                 setTimeout(function(){
                     $('.ke-container').css('width','600');
                 },1)
             }
         });
         editor3 = K.create('#order_notice_en', {
             cssPath : '<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/kindeditor/plugins/code/prettify.css',
             uploadJson : '<?php echo Soma_const_url::inst()->get_url('basic/upload/kind_do_upload', $params);?>',
             fileManagerJson : '<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/php/file_manager_json.php',
             allowFileManager : true,
             resizeType : 1,
             items : commonItems,
             afterCreate : function() {
                 setTimeout(function(){
                     $('.ke-container').css('width','600');
                 },1)
             }
         });

        //图文详情
        editor2 = K.create('#img_detail', {
            cssPath : '<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/kindeditor/plugins/code/prettify.css',
            uploadJson : '<?php echo Soma_const_url::inst()->get_url('basic/upload/kind_do_upload', $params);?>',
            fileManagerJson : '<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/php/file_manager_json.php',
            allowFileManager : true,
            resizeType : 1,
            items : commonItems,
            afterCreate : function() {
                setTimeout(function(){
                    $('.ke-container').css('width','600');
                    $('.ke-edit').height(300);
                    $('.ke-edit-iframe').height(300);
                },1)
            }
        });
        editor4 = K.create('#img_detail_en', {
            cssPath : '<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/kindeditor/plugins/code/prettify.css',
            uploadJson : '<?php echo Soma_const_url::inst()->get_url('basic/upload/kind_do_upload', $params);?>',
            fileManagerJson : '<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/php/file_manager_json.php',
            allowFileManager : true,
            resizeType : 1,
            items : commonItems,
            afterCreate : function() {
                setTimeout(function(){
                    $('.ke-container').css('width','600');
                    $('.ke-edit').height(300);
                    $('.ke-edit-iframe').height(300);
                },1)
            }
        });
		prettyPrint()
    });
	var tmp_gallery_img = [];
	$('#gallery_img img').each(function(){
		tmp_gallery_img.push($(this).attr('src'));
	})
	$('#el_gallery_img').val(JSON.stringify(tmp_gallery_img));
	var show_language=function(){
		var val = $('input[name=language]:checked').val();
		if(val == 2){
			$('.en').show();
        }else{
			$('.en').hide();
        } 
	}
	$('input[name="can_mail"]').change(show_can_mail);
	$('input[name="can_reserve"]').change(show_can_reserve);
    $('input[name="can_wx_booking"]').change(show_can_wx_booking);
	$('input[name="can_split_use"]').change(show_can_split_use);
	$('input[name="type"]').change(show_type_change);
	$('select[name=date_type]').change(function(){
        $('input[name=date_type]').val($(this).val());
        show_date_type();
    });
	$('input[name=language]').change(show_language);
	$('input[name=conn_devices').change(show_can_getapi);
	show_can_mail();
	show_can_reserve();
	show_can_wx_booking();
	show_can_split_use();
	show_type_change();
	show_date_type();
	show_language();
	show_can_getapi();
	/*选择酒店房型*/
	ajaxevent_bind();
	good_ajaxevent_bind();
	$('.add_hotel_btn').click(function(){
		$('.add_hotel').stop().fadeIn();
		$('body').css('overflow','hidden');
	});
    if(DateSet.startdate!=''&&DateSet.enddate!=''){
        $('#addDate').trigger('click');
        FillView(new Date(DateSet.startdate*1000),new Date(DateSet.enddate*1000));
    }

	//组合添加商品
	$('.add_good_btn').click(function(){
		if(!goodHadGet){
			goodHadGet = true;
			get_good_lists(function(err, data){
				return render_good_list(err, data, goodHadGet);
			});
		}else if(!$.isEmptyObject(goodCheckedHasDel)){
			$.each(goodCheckedHasDel, function(key,val){
				$('input[data-key="' + key + '"]').prop('checked', false);
			})
			goodCheckedHasDel = {};
		}

		$('.add_good').stop().fadeIn();
		$('body').css('overflow', 'hidden');
	})
});
/*选择酒店房型*/
function layerclose(){
	$('.add_hotel').stop().fadeOut();
	$('body').removeAttr('style');
}

function ajaxevent_bind(){
	$('.add_hotel').bind('click',layerclose);	
	$('.layer_success').click(function(){
		$('.add_hotel').stop().fadeOut();
		$('body').removeAttr('style');
	})
	$('input[type="checkbox"]','.allcodecheck').click(function(){
		var bool =$(this).get(0).checked;
		var tmp = $('.codecheck');
		tmp.each(function() {
         	$(this).find('input').get(0).checked=bool;
        });
	})
	$('input[type="checkbox"]','.codecheck').click(function(){
		var bool   =$(this).get(0).checked;
		var parent =$(this).parents('.codecheck');
		var tmp    =parent.siblings('.allcodecheck');
		if(tmp.length>0){
			parent.siblings('.codecheck').each(function() {
				if( !$(this).find('input').get(0).checked ) 
					bool=$(this).find('input').get(0).checked;
			});
			tmp.find('input').get(0).checked=bool;
		}
	})
	$('input[type="checkbox"]','.allhotelcheck').click(function(){
		var bool   =$(this).get(0).checked;
		$('.add_hotel input').each(function() {
         	$(this).get(0).checked=bool;            
        });
	})
	$('input[type="checkbox"]','.hotelcheck').click(function(){
		var bool;
		$('.hotelcheck').each(function() {
			if( !$(this).find('input').get(0).checked ) 
				bool=$(this).find('input').get(0).checked;
		});
		$('input[type="checkbox"]','.allhotelcheck').get(0).checked=bool;
		bool=$(this).get(0).checked;
		$(this).parents('td').siblings().find('input').each(function(){
			$(this).get(0).checked=bool;
		});
	})
	$('.add_hotel_content').click(function(e){
		e.stopPropagation();
	})
	$('input[type="checkbox"]','.roomcheck').click(function(){
		var bool =$(this).get(0).checked;
		$(this).parents('.roomcheck').siblings().find('input').each(function() {
			$(this).get(0).checked=bool;
        });
	});
	$('input[type="checkbox"]','.total_code .codecheck').click(function(){
		var code = $(this).parents('.codecheck').attr('code');
		var bool = $(this).get(0).checked;
		$('.codecheck[code="'+code+'"]').find('input').each(function() {
            $(this).get(0).checked=bool;
        });
	})
	var tr_length  = $('#coupons_table tbody tr').length;
	if(tr_length>10){
		var page_length= tr_length/10;
		for( var i=10; i<tr_length;i++){
			$('#coupons_table tbody tr').eq(i).hide();
		}
		for (var i=1;i<page_length;i++){
			$('.page_concol').append('<li class="paginate_button"><a href="#">'+(i+1)+'</a></li>');
		}
		$('.paginate_button').click(function(){
			var _index=$(this).index();
			$(this).addClass('active').siblings().removeClass('active');
			$('#coupons_table tbody tr').hide();
			for( var i=_index*10; i<(_index+1)*10&&i<tr_length;i++){
				$('#coupons_table tbody tr').eq(i).show();
			}
		})
	}else{
		$('.page_concol').hide();
	}
	// $('.searchtable').bind('input propertychange',function(){
	// 	var val=$(this).val();
	// 	if(val==''){
	// 		if(tr_length>10){
	// 			$('.page_concol').show();
	// 			var _index=$('.paginate_button.active').index();
	// 			for( var i=_index*10; i<(_index+1)*10&&i<tr_length;i++){
	// 				$('#coupons_table tbody tr').eq(i).show();
	// 			}
	// 		}else{
	// 			$('#coupons_table tbody tr').show()
	// 		}
	// 	}
	// 	else{
	// 		$('.page_concol').hide();
	// 		$('.hotelcheck').each(function(index, element) {
 //                if( $(this).text().indexOf(val)>=0){
	// 				$(this).parents('tr').show();
	// 			}else{
	// 				$(this).parents('tr').hide();
	// 			}
 //            });
	// 	}
	// })
}

/*添加商品*/
var goodId = "<?php echo $model->m_get('product_id');?>"
var $goodShowArea  = $('#good_show_area');
//p.cat_id + '_' + spec.product_id +'_' + spec.setting_id
//修改商品数量事件
$goodShowArea.on('change', 'input[type="text"]', function(e){
	var $this = $(this);
	var val = $.trim($this.val())
	var cacheInfo = goodCheckedKeys[$this.data('key')];
	if(!/\d+/.test(val)){
		val = "1";
		$this.val(1);
		alert('商品数量必须大于0')
	}
	cacheInfo.num = val;
})
$goodShowArea.on('click', 'del', function(e){
	var $this = $(this);
	var $tr = $this.parents('tr');
	var key = $tr.data('key');
	if(key && confirm('是否删除该商品')){
		goodCheckedHasDel[key]  = 1;
		delete goodCheckedKeys[key];
		$tr.remove();
		if(!$goodShowArea.find('tbody tr').length){
			$goodShowArea.find('table').remove();
		}
	}
})
function goodCheckedItemsTrShow(key){
	var keys = key.split('_');
	var str;
	var data = goodsCache[keys[0]].products[keys[1]];
	var data2;
	var isSpec = keys.length === 3;
	var cacheProduct = goodProductOrSpecServerIDMaps[keys[0] + '_' + keys[1] + (isSpec ? '_' + keys[1] : '')]
	var param = {
		id: cacheProduct && cacheProduct.id || "-1",
		parent_pid: cacheProduct && cacheProduct.parent_pid || "-1",
		child_pid: "" + keys[1],
		num: "1",
		spec_id: isSpec ? keys[2] : "-1"
	}
	if(isSpec){
		data2 = data.spec_infos[keys[2]];
		str = '<td>'+data.name + ':' +  data2.specName + '</td><td>'+data2.spec_price+'</td><td>'+data2.spec_stock+'</td><td>'+data.use_cnt+'</td><td><div class="candelete"><input class="input xs" data-key="'+key+'" type="text" value="1"/><del></del></div></td>';
	}else{
		str = '<td>' + data.name + '</td><td>' + data.price_package + '</td><td>'+data.stock+ '</td><td>'+data.use_cnt+'</td><td><div class="candelete"><input class="input xs" type="text" data-key="'+key+'" value="1"/><del></del></div></td>';
	}
	goodCheckedKeys[key] = param;
	return str;	
}
function goodCheckedItemsShow(){
	var $table = $goodShowArea.find('table');
	var strArr = [];
	var i = 0;
	if(!$table.length){
		strArr.push('<table class="good_checked_table"><thead><th>商品名称</th><th>微信价</th><th>剩余库存</th><th>拆分次数</th><th>每单包含数量</th></thead><tbody>')
		$.each(goodCheckedKeys, function(key,_val){
			if(_val){
				i++;
				strArr.push('<tr class="tr_'+key+'" data-key="'+key+'">'+goodCheckedItemsTrShow(key)+'</tr>')
			}else{
				delete goodCheckedKeys[key];
			}
		});
		strArr.push('</tbody></table>')
		$goodShowArea.append(strArr.join(''))
	}else{
		$tbody = $table.find('tbody');
		$.each(goodCheckedKeys, function(key, _val){
			var $tr = $table.find('tr.tr_' + key);
			if(_val){
				i++;
				if(!$tr.length){
					strArr.push('<tr class="tr_'+key+'" data-key="'+key+'">'+goodCheckedItemsTrShow(key)+'</tr>')
				}
				
			}
			if(_val === 0){
				delete goodCheckedKeys[key];
				if($tr.length){
					$tr.remove();
				}
			}
		});
		if(strArr.length){
			$tbody.append(strArr.join(''));
		}else if(!$tbody.find('tr').length){
			$table.remove();
		}
	}
	goodLayerCanClose = i <= 20
	
}
//修改商品属性
var $canWxBooking = $('input[type="radio"][name="can_wx_booking"]');
var $canSplitUse = $('input[type="radio"][name="can_split_use"]');
var $canRefund = $('input[type="radio"][name="can_refund"]');
var $canMail = $('input[type="radio"][name="can_mail"]')
var $canGift = $('input[type="radio"][name="can_gift"]')
var $canPickup = $('input[type="radio"][name="can_pickup"]')
var $canReserve = $('input[type="radio"][name="can_reserve"]')
var $showSalesCnt = $('input[type="radio"][name="show_sales_cnt"]')
var $canInvoice = $('input[type="radio"][name="can_invoice"]')
var $isHide = $('input[type="radio"][name="is_hide"]')
var $dateTypeSelect = $('select[name="date_type"]');
var $dateTypeInput = $('input[name="date_type"]');
var $expirationDate = $('input[name="expiration_date"]');
var $dateSet = $('#DateSet');
var $show1 = $('.show1');
var $show2 = $('.show2');
var $addGoodItem = $('.add_good_item');
var $addGoodContent = $('.add_good_content');
var $goodsType = $('input[type="radio"][name="goods_type"]');
var $combinationChangeItem = $('.combination_change_item');
var $combinationInput = $('.combine_products')
var $productType = $('input[name="type"]');
var $productTypeShouldHide = $('.type_should_hide');
var $dateuseHideItem1 = $('.dateuse_hide_item-1');
var $dateuseHideItem2 = $('.dateuse_hide_item-2');
function str2(num){
	return (num < 10 ? '0' : '') + num;
}
function goodPropsChange(isCombination){
	if(isCombination){
		//删除商品规格
		var $specBox = $('.SpecBox');
		var $specBoxDel = $specBox.find('> del');
		if($specBox.filter(':visible').length){
			SpecBoxDel(true, $specBoxDel);
		}
		if($dateSet.filter(':visible').length){
			dateSetDel(true);
		}

		$show1.hide();
		$show2.hide();

		$addGoodItem.addClass('Ldf').removeClass('Ldn');
	    $combinationChangeItem.addClass('Ldn').removeClass('Ldf');
	    $combinationInput.prop('disabled', false);
		$productTypeShouldHide.hide();
		// $dateuseHideItem1.addClass('Ldn').find('input').prop('disabled',true);
		// $dateuseHideItem2.removeClass('Ldn').find('input').prop('disabled',false).prop('name','expiration_date')
		var productType  = $productType.filter(":checked").val();
		if(productType === '2' || productType === '4'){
			$productType.eq(0).prop('checked', true);
		}
	}else{
		resetGoodChecked()
		$show1.show();
		$show2.show();
		$productTypeShouldHide.show();
		$addGoodItem.addClass('Ldn').removeClass('Ldf');
		$goodShowArea.html('');
		$combinationChangeItem.addClass('Ldf').removeClass('Ldn');
		$dateuseHideItem1.removeClass('Ldn').find('input').prop('disabled',false);
		$dateuseHideItem2.addClass('Ldn').find('input').prop('disabled',true).prop('name',null)
	}
	$canWxBooking.eq(0).prop({'checked':true});
	$canSplitUse.eq(0).prop({'checked':true});
	$canRefund.eq(0).prop({'checked':true});
	$canMail.eq(0).prop({'checked':true});
	$canGift.eq(isCombination ? 0 : 1).prop({'checked':true});
	$canPickup.eq(isCombination ? 0 : 1).prop({'checked':true});
	$canReserve.eq(0).prop({'checked':true});


	$canInvoice.eq(0).prop({'checked':true});
    if(!goodId){
        $showSalesCnt.eq(1).prop({'checked':true});
        $isHide.eq(1).prop({'checked':true});
    }

	// $dateTypeSelect.val(1).prop('disabled', isCombination ? true : false);
    $dateTypeSelect.val(1).prop('disabled', isCombination ? false : false);
	$dateTypeInput.prop('disabled', isCombination ? false : true);
	show_can_mail();
	show_can_reserve();
	show_can_wx_booking();
	show_can_split_use();
	show_date_type();
}
function goodLayerClose(){
	shouldShowAllGood = false;
	goodCheckedItemsShow()
	if(goodLayerCanClose){
		goodLayerCanClose = false;
		$('.add_good').stop().fadeOut();
		$('body').removeAttr('style');
	}else{
		alert('最多能选择20个商品，请删除多余的商品');
	}

}
function good_ajaxevent_bind(){
	$('.add_good').bind('click', goodLayerClose);
	$('.add_good_content').click(function(e){
		e.stopPropagation();
	})
}
//获取商品目录
//商品缓存
var goodsCache = {};
//选择商品标识缓存
var goodCheckedKeys = {};
var goodCheckedInfos = <?php echo $combine_products; ?>;
//当前页面显示商品标识缓存
var goodShowItemKeys = {};
//如果用户编辑商品，需要保存product_id与spec_id对应的id
//比如11354_1254: 1
var goodProductOrSpecServerIDMaps = {};
var goodHadGet = false;
//当前选择的是否被删除
var goodCheckedHasDel = {};
var goodLayerCanClose = true;

// 如果商品类型，选择了升级房劵
function choiceCoupon (bol) {
    if (bol) {
      $('.coupon_item').removeClass('Ldf').addClass('Ldn');
      $('#coupon_features').show();
    } else {
        $('#coupon_features').hide();
    }
}

//切换组合
$goodsType.on('change', function(){
    goodId = null;
	val = $(this).val();
    $('input[name=date_type]').val(1)
    if(val == "2"){
        $(".djhxsb").removeClass("none");
    }else{
        $(".djhxsb").addClass("none");
        $("input[name='conn_devices']")[0].checked = true;
    }
	goodPropsChange(val === '3');
    choiceCoupon(val === '4' || val === 4)
})
$("input[name='conn_devices']").on("change",function(){
    if(this.value == "2"){
        $(".zyb").addClass("none").removeClass("Ldf");
        $(".zyb2").removeClass("none");
        $(".zyb input")[0].checked = true;
    }else{
        $(".zyb2").addClass("none");
        $(".zyb").removeClass("none").addClass("Ldf");

    }
});
// 打开页面切换组合状态
if($goodsType.filter(':checked').val() === '3'){
	goodPropsChange(true);
	parseGoodHasChecked();
} else if ($goodsType.filter(':checked').val() === '4') {
    choiceCoupon(true);
} else {
    $('#coupon_features').hide();
}
/*
@arguments callback(err, data) [function] 回调函数
@arguments pname [string]模糊商品名
@arguments page_num [number=1]页码
@arguments page_size [number=10] 一页最大记录数
*/
function get_good_lists(callback,pname, page_num, page_size){
	var data = {};
	var page = {
		'page_num': 1
	};
	pname ? data.pname = pname : '';
	page_size ? page['page_size'] = page_size : '';
	data.page = page;
	$.ajax({
		url: '/index.php/soma/product_package/get_compose_product_list',
		method: 'get',
		data: data,
		success: function(data){
			data = $.parseJSON(data);
			callback(null,data,pname);
		},
		fail: function(){
			callback(true,null,pname);
		}
	})
};
var get_good_list_by_name_timer = null;
function get_good_list_by_name(pname){
	shouldShowAllGood = true;
	goodGetKey = pname;
	if(!goodIsGetIng){
		goodIsGetIng = true;
		$addGoodContentLoading.show();
		$addGoodContentTable.hide();
	}
	get_good_lists(function(err, data,pname){
		goodIsGetIng = false;
		console.log(pname, goodGetKey)
		if(pname === goodGetKey){
			goodGetKey = null;
			$addGoodContentLoading.hide();
			render_good_list(err, data);
			$addGoodContentTable.show();
			$addGoodNoContent.hide();
			$addGoodHasContent.show();
		}
	},pname)
}
function resetGoodChecked(){
	goodCheckedKeys = {};
	$addGoodContent.find('input[type="checkbox"]').prop('checked', false);
	$addGoodContent.find('input[type="text"]').val('');
	$combinationInput.prop('disabled', true);
}

function parseGoodHasChecked(){
	var strArr = ['<table class="good_checked_table"><thead><tr><th>商品名称</th><th>微信价</th><th>剩余库存</th><th>拆分次数</th><th>每单包含数量</th></tr></thead><tbody>'];
	$.each(goodCheckedInfos, function(idx, product){
		var key = product.cat_id + '_' + product.child_pid + (+product.spec_id ? '_' + product.spec_id : '')
		strArr.push('<tr class="tr_' + key + '" data-key="' + key + '"><td>' + product.name + '</td><td>' + product.price_package + '</td><td>' + product.stock + '</td><td>'+product.use_cnt+'</td><td><div class="candelete"><input type="text" class="input xs" data-key="' + key + '" type="text" value="' + product.num + '"/><del></del></div></td></tr>');
		goodCheckedKeys[key] = {
			child_pid: product.child_pid,
			id: product.id,
			num: product.num,
			parent_pid: product.parent_pid,
			spec_id: product.spec_id
		};
		goodProductOrSpecServerIDMaps[key] = {
			id:product.id,
			parent_pid: product.parent_pid
		}
	})
	strArr.push('</tbody></table>')
	return $goodShowArea.append(strArr.join(''));
}

function render_good_list_td(p){
	var td = [];
	var key1 = p.cat_id + '_' + p.product_id;
	var hasSpec = p.spec_info.length > 0;
	goodsCache[p.cat_id].products[p.product_id] = p;
	td.push('<div class="good_layer_list_item_row"><label class="check"><input  data-key="'+ key1 + '" data-spec="'+p.spec_info.length+'" data-type="product" ' + (goodCheckedKeys[key1] ? 'checked="checked"' : '') + ' type="checkbox" class="good_cat_'+p.cat_id+' good_product_'+p.product_id+'"  value="'+p.product_id+'"/><span class="diyradio"><tt></tt></span>'+p.name+'</label></div><div class="good_layer_list_item_par_row">');
	if(hasSpec){
		goodsCache[p.cat_id].products[p.product_id].spec_infos = {};
		$.each(p.spec_info, function(_idx,spec){
			var specName = '';
			try{
				$.each(spec.setting_spec_compose, function(id,_spec){
					specName += (_spec.spec_name.join(';') || '')
				})
			}catch(e){
				console.error(e);
				specName = '';
			}
			goodsCache[p.cat_id].products[p.product_id].spec_infos[spec.setting_id] = spec;
			goodsCache[p.cat_id].products[p.product_id].spec_infos[spec.setting_id].specName = specName;
			var key2 = p.cat_id + '_' + spec.product_id + '_' + spec.setting_id
			td.push('<div class="good_layer_list_item_sub_row"><label class="check"><input data-type="spec" data-spec="0"  data-key="'+key2+'" class="good_cat_'+p.cat_id+' good_product_'+spec.product_id+'" type="checkbox" ' + (goodCheckedKeys[key2] ? 'checked="checked"' : '') + ' name="spec_' + spec.setting_id + '" value="' + spec.setting_id + '"/><span class="diyradio"><tt></tt></span>' + specName + '</label></div>');
			goodShowItemKeys[key2] = 1;
		});
	}else{
		goodShowItemKeys[key1] = 1;
		td.push('<div class="good_layer_list_item_sub_row">&nbsp;</div>')		
	}
	return td.join('');
}
var $addGoodHasContent;
var $addGoodNoContent;
var $addGoodContentLoading;
var $addGoodContentTable;
var goodIsGetIng = false;
//是否应该显示全部商品，如果是搜索过的，再把值置空，就应该显示全部
var shouldShowAllGood = false;
var goodGetKey;

function render_good_list(err, data, isFirst){
	if(err){
		$addGoodNoContent && $addGoodNoContent.show();
		$addGoodHasContent && $addGoodHasContent.hide();
	}else{
		var strArr = [];
		goodShowItemKeys = {};
		if(isFirst){
			add_good_list_event();
			strArr.push('<div class="good_list_no_content Ldn">暂无相关商品</div><div class="good_list_has_content"><div class="bg_fff bd pad10"><label class="check allgoodcheck"><input type="checkbox" data-type="all"><span class="diyradio"><tt></tt></span>选择所有</label></div><div class="bg_fff bd pad10 martop"><div><div class="input"><input type="text" class="searchtable" placeholder="搜索商品"></div></div><div class="Ldn good_list_content_loading">数据搜索中</div><table id="good_table" class="table good_layer_table martop"><thead><tr><th>分类</th><th>商品名</th><th>规格</th></tr></thead><tbody>')
		}
		if($.isEmptyObject(data)){
			strArr.push('<tr><td colspan="3">未搜索到相关商品</td></tr>')
		}else{
			$.each(data, function(id, product){
				var len = product.product.length;
				if(len){
					goodsCache[id] = {
						cat_name: product.cat_name,
						products: {}
					}
					strArr.push('<tr><td><div><label class="check"><input data-type="cat" class="good_cat_'+id+'" name="'+id + '" type="checkbox" value="' + id + '"/><span class="diyradio"><tt></tt></span>' + product['cat_name'] + '</label></div></td><td colspan="2"><ul class="good_layer_list">')
					var i = 0;
					while(i < len){
						var p = product.product[i];
						strArr.push('<li class="good_layer_list_item Ldf">' + render_good_list_td(p) + '</li>');
						
						i++;
					}
					strArr.push('</ul></td></tr>')
				}
				
			})
			
		}
		if(isFirst){
			strArr.push('</tbody></table></div></div>')
			$addGoodContent.html(strArr.join(''));
			$addGoodHasContent = $addGoodContent.find('.good_list_has_content');
			$addGoodNoContent = $addGoodContent.find('.good_list_no_content');
			$addGoodContentLoading = $addGoodContent.find('.good_list_content_loading');
			$addGoodContentTable = $addGoodContent.find('.good_layer_table');
		}else{
			$addGoodContent.find('tbody').html(strArr.join(''));
		}
	}
	return void(0);
}
function add_good_list_event(){
	$addGoodContent.on('change', 'input[type="checkbox"]', function(){
		var $that = $(this);
		var dType = $that.data('type');
		var isChecked = $that.prop('checked');
		var value = $that.val();
		var key = $that.data('key') || '';
		if(dType === 'all'){
			$addGoodContent.find('input[type="checkbox"]').prop('checked', isChecked);
			if(isChecked){
				$.extend(goodCheckedKeys, goodShowItemKeys);
			}else{
				$.each(goodShowItemKeys, function(key, value){
					goodCheckedKeys[key] = 0;
				});
			}
		}else{
			var keys = key.split('_');
			var changeInputs;
			if(dType === 'cat'){
				//如果是选择分类
				$changeInputs = $addGoodContent.find('.good_cat_' + value + ':not([data-type="cat"])').prop('checked', isChecked);
				$changeInputs.each(function(){
					var _key = $(this).data('key');
					var hasSpec = $(this).data('spec');
					//商品有规格必须选择到规格  或者无规格的商品
					if(hasSpec === 0){
						goodCheckedKeys[_key] = isChecked ? 1 : 0;
					}
				})
			}
			else {
				var $changeInputs = $addGoodContent.find('.good_product_' + keys[1] + '.good_cat_' + keys[0]);
				var $changeCatInputs = $addGoodContent.find('.good_cat_' + keys[0]);
				if(dType === 'product'){
					var $subInput = $changeInputs.filter('[data-type="spec"]');
					$subInput.prop('checked', isChecked);
					$changeInputs.each(function(){
						var _key = $(this).data('key');
						var hasSpec = $(this).data('spec');
						//商品有规格必须选择到规格  或者无规格的商品
						if(hasSpec === 0){
							goodCheckedKeys[_key] = isChecked ? 1 : 0;
						}
					})
				}
				if(dType === 'spec'){
					if(isChecked){
						$changeInputs.filter('[data-type="product"]').prop('checked', true);
					}
					goodCheckedKeys[key] = isChecked ? 1 : 0;
				}

			}
		}
	})
	$addGoodContent.on('keyup', '.searchtable', function(){
		var $self = $(this);
		var val = $.trim($self.val());
		if(val || shouldShowAllGood){
			get_good_list_by_name(val);
		}
	})

	return;
}

function changeHotel(val){
    while(true){
        <?php if($hotelIds):?>
            <?php foreach ($hotelIds as $k => $v):?>
                if( val == <?php echo $k;?> ){
                    $('input[name="hotel_address"]').val("<?php echo $hotelIds[$k]['hotel_address'];?>");
                    $('input[name="hotel_tel"]').val("<?php echo $hotelIds[$k]['hotel_tel'];?>");
                    break;
                }
            <?php endforeach;?>
        <?php else:?>
            break;
        <?php endif;?>
    }
}
$('.datepicker2').datepicker({format:'yyyy/mm/dd'});

<?php if( empty($dateset) ):?>
var DateSet = {
	thead :['日期','库存','价格|积分'],
	en:['date','stock','specprice'],
	startdate:'', /*PHP时间戳*/
	enddate:'',/*PHP时间戳*/
	data:{}/*使用PHP时间戳为索引*/
}
<?php else:?>
var DateSet = <?php echo $dateset;?>;
<?php endif;?>
var json = '{"date":"","stock":"","spec_name":[],"specprice":"","timeid":""}';/*timeid为PHP时间戳*/
function Num(num){return Number(num)>=10?num:'0'+num;}
function Radio(str,val){
	return '<label class="check"><input type="checkbox" value="'+val+'" /><span class="diyradio"><tt></tt></span>'+str+'</label>';
}
var weekname	= ['周日','周一','周二','周三','周四','周五','周六'];
var ST = END = new Date();
function FillDate(S,E){
	var num = (E.getTime()-S.getTime())/86400000;
	$('#dateTable thead tr').html('');
	$('#dateTable tbody').html('');
	for(var i=0;i<weekname.length;i++){
		var td=$('<td>'+Radio(weekname[i],i)+'</td>');
		$('#dateTable thead tr').append(td);
		td.get(0).onclick=function(){
			var index = $(this).index()+1;
			var bool =$(this).find('input').get(0).checked;
			var _td = $('#dateTable tbody td:nth-child('+index+')');
			_td.each(function(){
				if($(this).find('input').length)
					$(this).find('input').get(0).checked=bool;
			});
		}
	}
	var _s = new Date(S);
	var blank = _s.getDay();	
	var tr = $('<tr></tr>');
	for(var i=0;i<blank;i++){
		tr.append('<td></td>');
	}
	for(var i=0;i<=num;i++){
		if(tr.find('td').length>=7) tr = $('<tr></tr');
		var r =Radio(Num(_s.getDate()),_s.getTime());
		var m ='<div class="tdMonth">'+(_s.getMonth()+1)+'月</div>';
		if(_s.getDate()==1||_s.getTime()==S.getTime()){
			var td = $('<td>'+m+r+'</td>');
		}else{
			var td = $('<td>'+r+'</td>');
		}
		tr.append(td);
		$('#dateTable tbody').append(tr);
		_s.setDate(_s.getDate()+1);
	}
	if(tr.find('td').length<7&&tr.find('td').length>0){
		var l = tr.find('td').length;
		for(var i=0;i<7-l;i++)
			tr.append('<td></td>');
	}
}
function FillView(S,E){
	ST = new Date(S);
	END= new Date(E);

	var num  =(END.getTime()-ST.getTime())/86400000;
	var html1='';
	var html2='';
	for(var i=0;i<DateSet.thead.length;i++){
		html1+='<div>'+DateSet.thead[i]+'</div>';
		html2+='<div class="multirow">';
		var en = DateSet.en[i];
		var _s =new Date(S);
		for(var j=0;j<=num;j++){
			var val = '-';
			var _d  = DateSet.data[String(_s.getTime()/1000)];
			if( en == 'date'){
				val = _s.getFullYear()+'/'+Num(_s.getMonth()+1)+'/'+Num(_s.getDate());
			}
			if( _d != undefined && _d[en])
				val = _d[en];
			html2+='<div>'+val+'</div>';
			_s.setDate(_s.getDate()+1);
		}
        html2+='</div>';
	}
	$('#DateSet .spectable .thead').html(html1);
	$('#DateSet .spectable .tbody').html(html2);
}
$('#searchDate').click(function(){
	var st = $('#DateSet input').eq(0).val();
	var end= $('#DateSet input').eq(1).val();
	if( st==''|| end=='')return;
	st = new Date(st);
	end= new Date(end);
	if( st.getTime()>end.getTime()){alert('开始日期不能大于结束日期');return;}
	FillView(st,end);
})
$('#editDate').click(function(){
	if($('#DateSet .tbody').length>0){
		FillDate(ST,END);
		$('#dateTable').show();
	}
})
$('#dateTable').click(function(){
	$('#dateTable').hide();
})
$('#dateTable div').click(function(e){
	e.stopPropagation();
})
$('#addDate').click(function(){
	$('.show1').hide();
	$('#editDate').show();
	$('#DateSet').show();
	$('#addDate').hide();
	$('.dateuse_hide').removeAttr('required').hide();
})
function dateSetDel(noConfirm){
	if(noConfirm || confirm('删除后将不可恢复')){
		$('#DateSet').hide();
		$('#DateSet .thead').html('');
		$('#DateSet .tbody').html('');
		DateSet.data={};
		DateSet.startdate='';
		DateSet.enddate='';
		$('input[name=dateset]').val('');
		$('.show1').show();
		$('#editDate').hide();
		$('#DateSet').hide();
		$('#addDate').show();
		$('.dateuse_hide').attr('required','true').show();
	}
}
$('#DateSet del').click(dateSetDel)
$('#SaveDate').click(function(e){
	if($('#dateStock').val()==''||$('#datePrice').val()=='')return;
	try{
		DateSet.startdate=ST.getTime()/1000;
		DateSet.enddate  =END.getTime()/1000;
		/*不在选择范围内的日期*/
		$.each(DateSet.data,function(m,n){
			//console.log(m);
			if(m<DateSet.startdate||m>DateSet.enddate)
				delete DateSet.data[m];
		}); 
		$('#dateTable tbody input:checked').each(function(){
			json=JSON.parse(json);
			var val = $(this).val();
			var index = val/1000;
			var tmpDate = new Date(Number(val));
			//console.log(tmpDate);
			DateSet.data[index]= json;
			DateSet.data[index].timeid=index;
			DateSet.data[index].date  =tmpDate.getFullYear()+'/'+Num(tmpDate.getMonth()+1)+'/'+Num(tmpDate.getDate());
			
			DateSet.data[index].spec_name=[];
			DateSet.data[index].spec_name.push(DateSet.data[index].date);
			DateSet.data[index].stock =$('#dateStock').val();
			DateSet.data[index].specprice =$('#datePrice').val();
			json=JSON.stringify(json);
		});
		FillView(ST,END);
		alert('已完成');
	}catch(ev){
		alert(ev);
	}
});
//初始化页码
var _page = 1,
    is_ajax = true,
    is_page = true,
    str_search  = "";

var _initial = true;
//异步获取订房配置
$('#showBookingConfig').click(function(){
    if(_initial){
        //默认拿第一页数据
        _page = 1;
        $("#hotel_content").html("");
        initHtml(_page);
        _initial = false;//只有第一次点击才加载
    }
});
function initHtml(num){
    var id          = '<?php echo $model->m_get('product_id');?>';
    var tokenVal    = '<?php echo $this->security->get_csrf_hash();?>';
    is_ajax = false;
    $.ajax({
        url: '<?php echo Soma_const_url::inst()->get_url('*/*/ajax_get_booking_config');?>',/**/
        method: 'post',
        data: {id:id,<?php echo $this->security->get_csrf_token_name();?>:tokenVal,page:num,search:str_search},
        dataType:'json',
        success: function(sresult){
            var _html = "";
            is_page = sresult['end_page']
            var data = sresult['data']
            for(var item in data){
                _html += '<tr>'
                           + '<td>'
                               + '<div class="checkbox hotelcheck hotel-name">'
                               + '<label class="check hotelcheck">'
                               + '<input name="hotel_ids['+ item +']" value='+ item +' type="checkbox"';
                                if(data[item]['checked']){
                                    _html += 'checked="checked"'
                                }
                               _html += '><span class="diyradio"><tt></tt></span>'
                               +  data[item]['name']
                               +  '</label></div>'
                           + '</td>'
                           + '<td>';
                           if(data[item]['room_ids'] != ""){
                               for(var ids in data[item]['room_ids']){
                                    _html += '<div class="parent_dom">'
                                           + '<div class="checkbox roomcheck">'
                                           + '<label class="check codecheck" code="1">'
                                           + ' <input name="room_ids['+ item +']['+ ids +']" value="['+ ids +']" type="checkbox"';
                                           if(data[item]['room_ids'][ids]['checked']){
                                                _html += 'checked="checked"'
                                            }
                                            _html += '><span class="diyradio"><tt></tt></span>'
                                                    + data[item]['room_ids'][ids]['name']
                                                    + '</label></div>'
                                                    + '<div class="child_dom">'
                                            if(data[item]['room_ids'][ids]['price_codes'] != ""){
                                                for(var codes in data[item]['room_ids'][ids]['price_codes']){
                                                    _html += '<div class="checkbox codecheck" code="'
                                                           + data[item]['room_ids'][ids]['price_codes'][codes]['price_code']
                                                           + '"><label class="check codecheck" code="1">'
                                                           + '<input name="code_ids['+ item +']['+ ids +']['+ data[item]['room_ids'][ids]['price_codes'][codes]['price_code'] +']"'
                                                           + 'value="'+ data[item]['room_ids'][ids]['price_codes'][codes]['price_code'] +'" type="checkbox"';
                                                           if(data[item]['room_ids'][ids]['price_codes'][codes]['checked']){
                                                                _html += 'checked="checked"';
                                                            }
                                                            _html += '><span class="diyradio"><tt></tt></span>'
                                                            + data[item]['room_ids'][ids]['price_codes'][codes]['price_name']
                                                            +'</label></div>'
                                                }
                                            }
                                    _html += '</div></div>'
                               }
                           }
                _html += '</td></tr>'  
            }
            $("#hotel_content").append(_html);
            ajaxevent_bind();
            is_ajax = true;
        },
        fail: function(){
        },
        error: function(data){
            console.log(data);
            is_ajax = true;
        }
    })
}
$(".add_hotel_content").scroll(function() {
    //滚动加载
    if( $(this).scrollTop() > ($('#hotel_content').height() * 0.8) && is_ajax == true && is_page == false){
        _page++;
        initHtml(_page);
    }
});
 $('.searchtable').on('keyup',function(e){
    if(e.keyCode ==13){
        str_search = $(this).val();
        // 初始化数值
        _page = 1;
        $("#hotel_content").html("");
        initHtml(_page)
      }
 });
</script>
</html>
