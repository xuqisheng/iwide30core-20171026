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
		<h3 class="box-title"><?php echo (isset($cr_id)&&!empty($cr_id)) ? '编辑': '新增'; ?>卡券使用规则</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal')); ?>
		<div class="box-body">
			<div class="form-group  has-feedback">
				<label class="col-sm-2 control-label">选择卡券:</label>
				<div class="col-sm-8">
					<select class="form-control" name="ci_id">
		                <option value="0">请选择</option>
		                <?php foreach($cards as $card) {?>
		                <option value="<?php echo $card->ci_id;?>" <?php if(isset($oldrule) && ($oldrule->ci_id==$card->ci_id)) echo "selected";?>><?php echo $card->title;?></option>
		                <?php } ?>
		            </select>
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">模块:</label>
				<div class="col-sm-8">
					<select name="module[]" multiple=true size="5" style="width:250px;">
		               <?php foreach($modules as $key=>$name) {?>
				           <option value=<?php echo $key;?> <?php if(isset($oldrule->module) && in_array($key,$oldrule->module)) echo "selected"?>><?php echo $name;?></option>
				       <?php } ?>
				   </select>
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">使用条件:</label>
				<div class="col-sm-8">
				    <ul class="list-unstyled">
			           <li><label class="col-sm-2"><input value="1" name="consume_balance_up" type="checkbox" <?php if(isset($oldrule->condition['consume_balance_up'])) echo "checked";?>> 消费金额满</label><div class="col-sm-9"><input class="form-control" type="text" name="balance_up" value="<?php if(isset($oldrule->condition['consume_balance_up'])) echo $oldrule->condition['consume_balance_up'];?>" /></div></li>
			           <li><label class="col-sm-2"><input value="1" name="consume_bonus_up" type="checkbox" <?php if(isset($oldrule->condition['consume_bonus_up'])) echo "checked";?>> 积分达到</label><div class="col-sm-9"><input class="form-control" type="text" name="bonus_up" value="<?php if(isset($oldrule->condition['consume_bonus_up'])) echo $oldrule->condition['consume_bonus_up'];?>" /></div></li>
			           <li><label class="col-sm-2"><input value="1" name="consume_product_up" type="checkbox" <?php if(isset($oldrule->condition['consume_product_up'])) echo "checked";?>> 同时消费</label><div class="col-sm-9"><input class="form-control" type="text" name="product_up" value="<?php if(isset($oldrule->condition['consume_product_up'])) echo $oldrule->condition['consume_product_up'];?>" /></div><div style="margin-top: 7px;" class="col-sm-1">个商品</div></li>
			           
		           </ul>
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">会员级别:</label>
				<div class="col-sm-8">
					<select name="member[]" multiple=true size="5" style="width:250px;">   
		               <option value="-1" <?php if(isset($oldrule->condition['member']) && in_array('-1',$oldrule->condition['member'])) echo "selected";?>>所有会员</option>
		               <?php foreach($members as $id=>$mem) {?>
		                   <option value="<?php echo $id;?>" <?php if(isset($oldrule->condition['member']) && in_array($id,$oldrule->condition['member'])) echo "selected";?>><?php echo $mem;?></option>
		                <?php } ?>
				   </select>
				</div>
			</div>
                <style>
				._point,._point li{list-style:none}
				._point li{padding:2px 0;}
				._point li>*{vertical-align:middle; margin-top:0; margin-right:3px;}
				._point li>*:hover,.actives{color:#4FA4FF; }
				.noe_check tt{float:right}
				._point span{  cursor:pointer;}
				</style>
			<div class="form-group  has-feedback" style="display:none">
				<label class="col-sm-2 control-label">酒店和房型:</label>
				<div class="col-sm-8">
					<ul class="form-control _point" name="hotel" style="height:auto">
                     	<li id="all_check"><input value='' type="checkbox" > <span>选择所有酒店和房型</span></li>
                    </ul>
				</div>
			</div>
			<div class="form-group has-feedback">
				<label class="col-sm-2 control-label">选择酒店:</label>
				<div class="col-sm-8">
					<ul class="form-control _select_hotel _point" name="hotel" style="height:auto">
                     	<li class="all_check"><input value='' type="checkbox" > <span>所有酒店</span></li>
				        <?php foreach($hotels as $key=>$name) {?>
               			<li class="noe_check" title="点击查看房型">
                        	<input value='<?php echo $key;?>' type="checkbox" <?php if(isset($oldrule->condition['hotel']) && $oldrule->condition['hotel']==$key) echo "ischeck"?>>
                        	<span title="点击查看房型"><?php echo $name;?></span>
                        </li>
				       <?php } ?>
                    </ul>
				</div>
			</div>
			<div class="form-group  has-feedback" style="display:none;">
				<label for="el_role_name" class="col-sm-2 control-label">商品类型:</label>
				<div class="col-sm-8">
                	<ul class="form-control hous_type _child _point" name="pro_category[]" multiple=true size="5" style="padding:6px 12px; height:auto">
					   <li class="all_type all_check"><input value="-1" type="checkbox" checked> <span>所有房型</span></li>
		               <?php foreach($pro_category as $key=>$name) {?>
				       <li class="noe_check"><input value="<?php echo $key;?>" type="checkbox" <?php if(isset($oldrule->condition['pro_category']) && in_array($key,$oldrule->condition['pro_category'])) echo "checked"?>><span><?php echo $name;?></span></li>
				       <?php } ?>
				   </ul>
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label for="el_role_name" class="col-sm-2 control-label">价格代码:</label>
				<div class="col-sm-8">
                <ul class=" form-control home_style _child _point" name="price_code[]" multiple=true size="5" style="padding:6px 12px; height:auto"></ul>
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label class="col-sm-2 control-label">参与商品:</label>
				<div class="col-sm-8">
					<select class="form-control" name="product_type" onChange="change();">
				        <option value="1" <?php if(isset($oldrule->product_type) && $oldrule->product_type==1) echo "selected"?>>所有商品</option>
				        <option value="2" <?php if(isset($oldrule->product_type) && $oldrule->product_type==2) echo "selected"?>>自定义商品</option>
				   </select>
				   &nbsp;&nbsp;
				   <ul id="products" class="list-unstyled" style="overflow:scroll;height:200px;<?php if(!isset($oldrule->product_type) || $oldrule->product_type==1) {?>display:none;<?php } ?>">
				       <?php foreach($products as $k=>$p) {?>
					   <li><input type="checkbox" name="product_id[]" value="<?php echo $k;?>" <?php if(isset($oldrule->product) && in_array($k,$oldrule->product)) echo "checked";?>><?php echo $p;?></li>
					   <?php } ?>
					</ul>
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label class="col-sm-2 control-label">数量限制:</label>
				<div class="col-sm-3">
					<select class="form-control" name="restriction">
					    <option value="0">请选择</option>
		                <option value="room_nights" <?php if(isset($oldrule->condition['restriction']) && key($oldrule->condition['restriction'])=='room_nights') echo "selected";?>>间夜数</option>
		                <option value="order" <?php if(isset($oldrule->condition['restriction']) && key($oldrule->condition['restriction'])=='order') echo "selected";?>>订单</option>
		            </select>
				</div>
				<div class="col-sm-2">
				    <input name="res_num" class="form-control" type="text" value="<?php if(isset($oldrule->condition['restriction'])) echo current($oldrule->condition['restriction']);?>" />
				</div>
			</div>
			<div class="form-group  has-feedback">
				<label class="col-sm-2 control-label">激活状态:</label>
				<div class="col-sm-8">
					<select class="form-control" name="is_active">
		                <option value="1" <?php if(isset($oldrule) && ($oldrule->is_active==1)) echo "selected";?>>激活</option>
		                <option value="0" <?php if(isset($oldrule) && ($oldrule->is_active==0)) echo "selected";?>>取消激活</option>
		            </select>
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
<div class="loading" style="position:fixed; top:45%; text-align:center; z-index:9999999; width:100%;">
	<span style="padding:10px 20px; border:1px solid #e4e4e4; background:#fff;">数据正在加载..</span>
</div>
</body>

<script>

//var $a = '<php echo $oldrule->condition['hotel'];>';
var $a={};
<?php if(isset($oldrule) && isset($oldrule->condition['pro_category'])) {?>
    var $a =<?php echo json_encode($oldrule->condition['pro_category']);?>;
<?php } ?>

function arrayIntersection ( a, b )
{
    var ai=0, bi=0;
    var result = new Array();
    while ( bi < b.length )
    {
        if ( a[ai] == b[bi] ) {
			result.push ( a[ai] );
            bi++;
			ai=0;
		}
        else  {
            ai++;
        }
		if ( ai>=a.length ){
			 ai=0;
			 bi++;
		}
    }
    return result;
}
var loading =function(str){
	if( str == undefined ) str='数据加载中,请稍候...';
	$('.loading').stop().show();
	$('.loading span').html(str);
}
var array_json =[];
var _json ={};
var clear_json =function(){
	_json={};
	_json ={"id":'',"name":'',"checked":false}//,"room":{}}
}
var get_json = function(_index,_this_val,check){
	array_json[_index]=_json;
	array_json[_index].room=[];
	$.get("<?php echo EA_const_url::inst()->get_url('*/*/getProCategory');?>", {hotel_id:_this_val},function(data){
		var i=0;
		for(key in data) {
			array_json[_index].room[i]={};
		    array_json[_index].room[i].id =key;
		    array_json[_index].room[i].name=data[key];
			array_json[_index].room[i].checked=false;
			if ($a[_this_val]!=undefined && $a[_this_val].indexOf(key)>=0){
				array_json[_index].room[i].checked=true;
			}
		    i++;  	
		}
	},"json");
}
for(var i=0;i<$('._select_hotel .noe_check input').length;i++){
	clear_json();
	//_json.room=_json;
	var _this=$('._select_hotel .noe_check input').eq(i);
	_json.id=_this.val();
	if ( _this.get(0).checked ){
		_json.checked=true;
	}
	_json.name=_this.attr('title');
	get_json(i,_this.val(),_json.checked);
}
function checked_all(_this){
	for ( var i=0; i<_this.find('.noe_check').length;i++){
		if(!_this.find('.noe_check').eq(i).find('input').get(0).checked){
			_this.find('.all_check input').get(0).checked=false;
			return;
		}
	}
	_this.find('.all_check input').get(0).checked=true;
}
$(document).ajaxStart(function() {window.ajax_loading = true;});
$(document).ajaxStop(function(){window.ajax_loading = false;$('.loading').hide();});
$('input').click(function(e){e.stopPropagation();});
function allcheck(_this,bool){
	var _tmp=_this.parent().siblings().find('input');
	for (var i=0; i<_tmp.length;i++)
		_tmp.eq(i).get(0).checked=bool;
}
function hous_type_click(){
	var _i=$('._select_hotel .actives').index()-1;
	if($(this).parent().hasClass('all_check')){
		var _bool =$(this).get(0).checked;
		allcheck($(this),_bool);
		for ( var i=0; i<array_json[_i].room.length;i++){
			array_json[_i].room[i].checked=_bool;
		}
	}else{
		var _j=$(this).parent().index()-1;
		array_json[_i].room[_j].checked=$(this).get(0).checked;
		checked_all($('.hous_type'));
	}
}
$('._select_hotel input').click(function(){
	var _i=$(this).parent().index()-1;
	var _bool =$(this).get(0).checked;
	if($(this).parent().hasClass('all_check')){
		_i++;
		allcheck($(this),_bool);
	}else{
		checked_all($('._select_hotel'));
	}	
	array_json[_i].checked=_bool;
})
$('._select_hotel li').click(function(){
	$(this).addClass('actives').siblings().removeClass('actives');
	var _i = $(this).index()-1;
	$('input',this).val();
	var count=0;
	var tmp = '<li class="all_type all_check"><input value="-1" type="checkbox"> <span>所有房型</span></li>';
	for ( var i=0; i<array_json[_i].room.length;i++){
		count++;
		tmp += '<li class="noe_check"><input value="';
		tmp += array_json[_i].room[i].id+'"';
		if ( array_json[_i].room[i].checked) tmp+= 'checked';
		tmp +=' type="checkbox"><span>'+array_json[_i].room[i].name+'</span></li>';
	}
	$('#_type ul').html(tmp);
	checked_all($('#_type'));
	$('.hous_type input').click(hous_type_click);
	$('#_type').slideDown();
});
//function allhotelcheck(_this,bool){
//	for (var i=0; i<$('input',_this).length;i++){
//		$('input',_this).eq(i).get(0).checked=bool;
//	}
//}
//$('#all_check input').click(function(){
//	var bool = $(this).get(0).checked;
//	allhotelcheck($('._select_hotel',bool);
//	allhotelcheck($('.hous_type',bool);
//	for( var i=0; i<array_json.length;i++){
//		
//	}
//})
function _submit(){
	if(window.ajax_loading ){
		alert('页面还未加载完毕,请稍候重试!');
		return;
	}

	var hotelid='';
	var temp = '';
	for(var i=0; i<array_json.length; i++){
		if (  array_json[i].checked==true ){
			hotelid+=array_json[i].id+',';
			var roomid='';
			for(var j=0;j<array_json[i].room.length; j++){
				if (  array_json[i].room[j].checked==true){
					roomid +=array_json[i].room[j].id+',';
				//console.log(_json_tmp[i].roomid[j].val)
				}
			}
			roomid  =roomid.substring(0,roomid.length-1);
			temp += '<input name="category['+array_json[i].id+']" type="hidden" value="'+roomid+'" />';
		}
	}
	hotelid=hotelid.substring(0,hotelid.length-1);
	temp += '<input name="hotel" type="hidden" value="'+hotelid+'" />';
	console.log(temp);
	$('#hidden').html(temp);
	$('.form-horizontal').submit();
	
}
$(document).ready(function() {
	$(".market_content>div").hide();
	$(".market_content div").eq(0).show();
	$(".market_list li").click(function() {
        $(".market_list li").eq($(this).index()).addClass("on").siblings().removeClass('on');
        $(".market_content>div").hide().eq($(this).index()).show(); 
    });
	$.get("<?php echo EA_const_url::inst()->get_url('*/*/getPriceCode');?>", {hotel_id:$('._select_hotel input').eq(2).val()}, function(data){
		var tmp='<li class="all_style all_check"><input value="-1" name="price_code[]" type="checkbox"><span>所有类型</span></li>';
		var i=0;
		var IsOn = "<?php echo $price_code1; ?>";
		for(key in data) {
			  		tmp +="<li class='noe_check'><input type='checkbox'";
				if( IsOn.indexOf(key )>=0 ){
					tmp +=" checked ";
				}
				tmp +=" name='price_code[]'  value='"+key+"'><span>"+data[key]+"</span></li>";
		}
		$('.home_style').html(tmp);
		$('.home_style input').click(function(){
			if($(this).parent().hasClass('all_check'))
				allcheck($(this),$(this).get(0).checked);
			else
				checked_all($('.home_style'));
		});
		 checked_all($('.home_style'));	
	 },"json");
	 checked_all($('._select_hotel '));
	 checked_all($('.hous_type '));
});


function change(){
	var select = $("select[name='product_type']").val();
	if(select==2) {
		$("#products").show();
	} else {
		$("#products").hide();
	}
}
</script>
</html>

