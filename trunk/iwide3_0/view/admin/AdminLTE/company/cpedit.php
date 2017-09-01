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
	<div class="box-header with-border">
		<h3 class="box-title">合作单位：<?php echo $companyInfo['companyInfo']['company_name'];?></h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal'),array('company_id'=>$_GET['ids']) ); ?>
		<div class="box-body">

                <style>
				._point,._point li{list-style:none}
				._point li{padding:2px 0;}
				._point li>*{vertical-align:middle; margin-top:0; margin-right:3px;}
				._point li>*:hover,.actives{color:#4FA4FF; }
				.noe_check tt{float:right}
				._point span{  cursor:pointer;}
				</style>

			<div class="form-group has-feedback">
				<label class="col-sm-2 control-label">选择酒店:</label>
				<div class="col-sm-8">
					<ul class="form-control _select_hotel _point" name="hotel" style="height:auto">
                     	<li class="all_check"><input id="check_all" value='' type="checkbox" > <span>所有酒店</span></li>
				        <?php foreach($hotels as $key=>$name) {?>
               			<li class="noe_check" >
                        	<input value='<?php echo $key;?>' type="checkbox" <?php if(isset($company_hotel[$key])){ echo "checked";}?>>
                        	<span ><?php echo $name;?></span>
                        </li>
				       <?php } ?>
                    </ul>
				</div>
			</div>
			<input name="chose_hotel" id="_select_hotel" type="hidden" value="" />

            <div class="form-group  has-feedback">
                <label class="col-sm-2 control-label">协议代码:</label>
                <div class="col-sm-8">
                    <input class="form-control" name="cp_code" value="<?php if(!empty($companyInfo['priceInfo']->cp_code)) echo $companyInfo['priceInfo']->cp_code;?>">
                </div>
            </div>

            <div class="form-group  has-feedback">
                <label class="col-sm-2 control-label">价格代码:</label>
                <div class="col-sm-8">
                    <select class="form-control" name="ci_id">
                        <option value="0">请选择</option>
                        <?php foreach($all_price_code as $all_code) {?>
                            <option value="<?php echo $all_code['price_code'];?>" <?php if(isset($companyInfo['priceInfo']->price_code) && ($companyInfo['priceInfo']->price_code==$all_code['price_code'])) echo "selected";?>><?php echo $all_code['price_name'];?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

			<div class="form-group  has-feedback">
				<label class="col-sm-2 control-label">激活状态:</label>
				<div class="col-sm-8">
					<select class="form-control" name="is_active">
		                <option value="1" <?php if(isset($companyInfo['companyInfo']['company_status']) && ($companyInfo['companyInfo']['company_status']==1)) echo "selected";?>>激活</option>
		                <option value="2" <?php if(isset($companyInfo['companyInfo']['company_status']) && ($companyInfo['companyInfo']['company_status']==2)) echo "selected";?>>取消激活</option>
		            </select>
				</div>
			</div>

            <div class="form-group  has-feedback">
                <label class="col-sm-2 control-label">有效期:</label>
                <div class="col-sm-8" >
                    <input class="form-control"  name="valid_time" value="<?php if(!empty($companyInfo['priceInfo']->valid_time)) echo $companyInfo['priceInfo']->valid_time;?>">
                </div>
            </div>

		<?php if(isset($oldrule->cr_id)) {?>
            <input name="cr_id" type="hidden" value="<?php echo $oldrule->cr_id;?>" />
        <?php }?>
        <div id="hidden" style="display:hidden"></div>
		<div class="box-footer ">
            <button type="botton" onClick="_submit()" class="btn btn-primary">保存</button>
		</div>
        </div>
     <?php echo form_close() ?>
        </div>
        </section>
      </div>
</div><!-- ./wrapper -->
<?php 
/* Footer Block @see footer.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php';
?>
<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php';
?>
<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php';
?>
<div class="loading" style="position:fixed; top:45%; text-align:center; z-index:9999999; width:100%;display:none">
	<span style="padding:10px 20px; border:1px solid #e4e4e4; background:#fff;">数据正在加载..</span>
</div>
</body>

<script>
function ischeck_all(){      //检查是否全选
	var _this =$('.noe_check');
	for (var i=0; i<_this.length;i++)
		if (!_this.eq(i).find('input').get(0).checked) return false;
	return true;
}
function tocheck_all(bool){       //  bool为是否全选
	var _this=$('.noe_check');
	for (var i=0; i<_this.length;i++)
		_this.eq(i).find('input').get(0).checked=bool;
}
/// 行点击需要的模块  /////
function checked(_this){    //单选元素
	if( _this.get(0).checked){
		_this.get(0).checked=false;
	}else{
		_this.get(0).checked=true;		
	}
}
$('.all_check').click(function(){
	checked($('input',this));
	tocheck_all($('input',this).get(0).checked);
})
$('.noe_check').click(function(){
	checked($('input',this));
	$('#check_all').get(0).checked=ischeck_all();
})
/// 行点击需要的模块  /////

$('#check_all').click(function(e){
	e.stopPropagation(); //阻止父元素点击事件
	tocheck_all($(this).get(0).checked);
});
$('.noe_check input').click(function(e){
	e.stopPropagation(); //阻止父元素点击事件
	$('#check_all').get(0).checked=ischeck_all();
})

function _submit(){
	var temp='';
	for(var i=0; i<$('.noe_check input').length; i++){
		if ( $('.noe_check input').eq(i).get(0).checked){
			temp += $('.noe_check input').eq(i).val()+',';
		}
	}
	temp=temp.substring(0,temp.length-1);
	$('#_select_hotel').val(temp);
    $('.valid_time').val();
    $('.cp_code').val();
//	console.log(temp);
	$('.form-horizontal').submit();
	
}
//$("#check_all").click(function(){
//    if(this.checked){
//        $("#check_all :checkbox").attr("checked", true);
//        $(".noe_check :checkbox").attr("checked", true);
//    }else{
//        $("#check_all :checkbox").attr("checked", false);
//        $(".noe_check :checkbox").attr("checked", false);
//    }
//});

//
//$("#check_all").click(function(){
//    var valArr = new Array;
//    $(".noe_check :checkbox[checked]").each(function(i){
//        valArr[i] = $(this).val();
//    });
//    var vals = valArr.join(',');//转换为逗号隔开的字符串
//    alert(vals);
//});
//

</script>
</html>

