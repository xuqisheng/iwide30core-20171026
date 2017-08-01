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
        <h1><?php echo isset($breadcrumb_array['action'])? $breadcrumb_array['action']: ''; ?></h1>
        <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
    </section>
<!-- Main content -->
<section class="content">

<?php echo $this->session->show_put_msg(); ?>
<!-- Horizontal Form -->
<div class="box box-info">
<!--

<div class="tabbable "> <!-- Only required for left/right tabs -->

<style>
<!--
@font-face {font-family: 'iconfont';
    /*src: url('iconfont.eot');  IE9*/
    /*src: url('iconfont.eot?#iefix') format('embedded-opentype'),  IE6-IE8 */
    src: url('<?php echo base_url('public/fonts/iconfont.woff')?>') format('woff'), /* chrome、firefox */
    url('<?php echo base_url('public/fonts/iconfont.ttf')?>') format('truetype') /* chrome、firefox、opera、Safari, Android, iOS 4.2+*/
    /*url('iconfont.svg#svgFontName') format('svg');  iOS 4.1- */
}
.iconfont{font-family: "iconfont";
font-style: normal;
font-size: 1.4em;
vertical-align: middle;
display: inline-block;
margin: 62px 85px;}
-->
div[ad_id]{border:1px solid #e4e4e4; margin-left:50px; padding:0; width:200px;overflow:hidden}
div[ad_id] div{padding:2px 8px; word-break:break-all}
div[ad_id] p{width:200px;height:150px; background:#f8f8f8; overflow:hidden}
div[ad_id] p:after{content:"无图片"; font-style:italic; font-size:10px; color:#ccc; display:block; padding:10px;}
div[ad_id] img{max-width:300px; max-height:150px; min-width:200px;min-width:150px;}
div[ad_link]{ height:50px; overflow:hidden; font-family:arial; font-size:10px; line-height:1}
</style>
<!-- form start -->
    <?php echo form_open( site_url('hotel/ads/edit_post'), array('id'=>'subform','enctype'=>'multipart/form-data' ), array('hotel_id'=>$hotel_id,'code'=>$list['code']) ); ?>
    <input type='hidden' id='ads_str' name='ads_str' />
    <div class="box-body">
		<div style="margin-bottom:50px"><i class="fa fa-list-alt"></i> 基本信息 </div>
        <div class="form-group col-xs-8">
        	<label  class="col-sm-2">广告区域</label>
            <span class="col-sm-8"><?php echo $list['area_type_des']?></span>
    	</div>
        <?php if($code != 'index_middle'){?>
            <div class="form-group col-xs-8">
                <label class="col-sm-2">区域标题</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control " name="area_title" id="area_title" placeholder="区域标题" value="<?php echo $list['area_title']; ?>">
                </div>
            </div>
        <?php }?>
    <?php if($hotel_id!=0){?>
        <div class="form-group col-xs-8">
            <label class="col-sm-2">显示设定</label>
            <div class="col-sm-8">
                <select name='coexist' id='coexist'>
                <?php foreach($coexist_des as $c=>$des){?>
                	<option value='<?php echo $c;?>' <?php if($c==$list['coexist']){?> selected <?php }?>><?php echo $des;?></option>
				<?php }?>
                </select>
            </div>
        </div>
    <?php }?>
        <div class="form-group col-xs-8">
            <label class="col-sm-2">状态</label>
            <div class="col-sm-8">
                <select name='status' id='status'>
                <?php foreach($status_des as $c=>$des){?>
                    <option value='<?php echo $c;?>' <?php if($c==$list['status']){?> selected <?php }?>><?php echo $des;?></option>
                <?php }?>
                </select>
            </div>
        </div> 
		
    <div class="form-group  col-xs-12">
        <label class="col-sm-2">已添加广告</label>
    </div>
    <div id='ads'>
        <div id='hotel'>
        <?php if(!empty($ads['ads']['hotel'])){ foreach ($ads['ads']['hotel'] as $aa){?>
            <div key='key' ad_id='<?php echo $aa['id'];?>' class="form-group col-xs-8">    
                <p>
                <?php if($code=='index_middle'){?>
                    <em class="iconfont"><?php echo $aa['ad_img'];?></em>
                <?php }else{?>
                    <img src='<?php echo $aa['ad_img'];?>' />
                <?php }?>
                </p>
                <div label title="<?php echo $aa['ad_title'];?>"><?php echo $aa['ad_title'];?></div>
                <div ad_link title="<?php echo $aa['ad_link'];?>"><?php echo $aa['ad_link'];?></div>
                <span class="btn btn-danger btn-block btn-flat" onclick="dele(this)"><i class="fa fa-trash-o"></i> 删除</span>
            </div>
        <?php }}?>
        </div>
        <div id='public'>
        <?php if(!empty($ads['ads']['public'])){ foreach ($ads['ads']['public'] as $aa){?>
            <div ad_id="<?php echo $aa['id'];?>" class="form-group col-xs-8" >    
                <p>
                <?php if($code=='index_middle'){?>
                    <em class="iconfont"><?php echo $aa['ad_img'];?></em>
                <?php }else{?>
                    <img src='<?php echo $aa['ad_img'];?>' />
                <?php }?>
                </p>
                <div label title="<?php echo $aa['ad_title'];?>"><?php echo $aa['ad_title'];?></div>
                <div ad_link title="<?php echo $aa['ad_link'];?>"><?php echo $aa['ad_link'];?></div>
                <span class="btn btn-danger btn-block btn-flat disabled">公共设置</span>
            </div>
        <?php }}?>
        </div>
    </div>
    <div class="form-group col-xs-12">
        <label class="col-sm-2" style="color:#888; font-style:italic; font-weight:normal">可添加广告</label>
    </div>
    <?php if(!empty($ad_list)){ ?>
    <?php  foreach($ad_list as $a){ ?>
    <div class="form-group col-xs-8" key='add' ad_id='<?php echo $a['id'];?>'>
        <p>
        <?php if($code=='index_middle'){?>
            <em class="iconfont"><?php echo $a['ad_img'];?></em>
        <?php }else{?>
            <img src='<?php echo $a['ad_img'];?>' />
        <?php }?>
        </p>
        <div label title="<?php echo $a['ad_title'];?>"><?php echo $a['ad_title'];?></div>
        <div ad_link title="<?php echo $a['ad_link'];?>"><?php echo $a['ad_link'];?></div>
        <span class="btn btn-success btn-block btn-flat" onclick="add_ad(this)"><i class="fa fa-plus"></i> 添加</span> 
    </div>
    <?php } } else {?>
    <div class="form-group col-xs-12" style="color:#ccc;margin-left:50px">无</div>
    <?php }?>
    <div class="col-xs-12" style="margin:20px 0">
        <button type="button" onclick="sub()" class="btn btn-info" style="margin-right:40px">保存</button>
        <button type="button" onclick="undele()" class="btn btn-danger ">撤销删除</button>
    </div>
    <!-- /.box-footer -->
    </div>
    <?php echo form_close()?>
    <!-- /.box-body -->

</div>
</section>
<!-- /.content -->
</div>
<!-- /.box -->

</div>
<!-- /.content-wrapper -->

<?php
/* Footer Block @see footer.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'footer.php';
?>
<?php

/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'right.php';
?>
</div>
	<!-- ./wrapper -->
<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'commonjs.php';
?>
<!--
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>
-->
	<link rel="stylesheet"
		href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" />
	<script
		src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
	<script>
var ele=new Array();
$(function () {
	$(".wysihtml5").wysihtml5();
});
function undele(){
	if(ele.length>0){
		t_o=ele.pop();
		$(t_o).attr('key','key');
		$(t_o).show();
	}
}
function dele(obj){
	t_p=$(obj).parent();
	t_p.attr('key','');
	t_p.hide();
	ele.push(t_p);
}
function add_ad(obj){
	_parent=$(obj).parent();
	var tmp='<div key="key" ad_id="'+_parent.attr('ad_id')+'" class="form-group col-xs-8">'
    <?php if($code=='index_middle'){?>
        +'<p><em class="iconfont" >'+_parent.find('em').html()+'</em></p>'
    <?php }else{?>
		+'<p><img src="'+_parent.find('img').attr('src')+'" /></p>'
    <?php }?>
		+'<div label title="'+_parent.find('[label]').html()+'">'+_parent.find('[label]').html()+'</div>'
		+'<div ad_link title="'+_parent.find('[ad_link]').html()+'">'+_parent.find('[ad_link]').html()+'</div>'
		+'<span class="btn btn-danger btn-block btn-flat" onclick="dele(this)"><i class="fa fa-trash-o"></i> 删除</span>'
	$('#hotel').append(tmp);
}
function sub(){
	ranges=$("[key='key']");
	var ads='';
	$.each(ranges,function(i,n){
		ads+=','+$(n).attr('ad_id');
	});
	ads=ads.substring(1);
	$('#ads_str').val(ads);
	$('#subform').submit();
}
</script>
</body>
</html>
