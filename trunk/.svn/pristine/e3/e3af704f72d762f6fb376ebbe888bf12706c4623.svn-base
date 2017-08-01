<!-- DataTables -->
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/new.css">
<!-- <link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script> -->
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
    <div class="banner bg_fff p_0_20">
        价格管理/价格日历
    </div>

<section class="content">
<div class="row">
<style>
.box-body {overflow: visible;}
.calendar_price{position:relative; min-width:100%; }
.calendar_price *{ text-align:center; min-width:2em; box-sizing:content-box}
.calendar_price td:first-child{ padding:0 8px; min-width:3em;}
.calendar_price td[status]{ cursor:pointer;}
.calendar_price div[yuan]:before{content:"¥"}
.calendar_price td[status=null] div[yuan]:before{content:""}
.calendar_price td[status=able]{ color:#ff9900;}
.calendar_price td[status=null]{ background:#e9f8d7; color:#e9f8d7;}
.calendar_price td[status=close]{ background:#f7f7f7; color:#aaa;}
.calendar_price td[status=disable]{ background:#f7f7f7 !important; color:#aaa !important; cursor:not-allowed}
.calendar_price td[status=able]:hover,
.calendar_price td[status=close]:hover{background:#fffdef; color:#ff9900}
.calendar_price tr[setpricetype]:nth-child(odd){background:#f1f1f1;}
.calendar_price tr[setroomtype] td[status=able]{ color:rgb(100, 149, 237);}

.calendar_price div{padding:3px 10px; }
.calendar_price div:nth-child(2){ background:rgba(200,200,200,0.1)}
.calendar_price div:nth-child(3){ background:rgba(150,150,150,0.1)}
.calendar_price .roomtype{ background:#f2efff; font-weight:bold}
.calendar_price .pricetype td{padding:5px 0;color:#000}
.layer{background:rgba(0,0,0,0.2); width:100%; height:100%; position:fixed; top:0; left:0; z-index:9999;display:none}
.layer >div >*{ padding:8px 12px 0 12px;}
.layer *{ vertical-align:middle}
.layer input{margin-top:0}
.layer >div{background:#fff; position:absolute; top:20%; width:550px; left:30%;border-radius:10px; padding-bottom:12px; overflow:hidden}
.layer h4{background:#00c0ef; color:#fff; margin-top:0; padding-bottom:12px;}
.layer label{padding-right:12px; font-weight:normal;}
.layer tt{display:block; font-weight:bold}
.layer .form-control{display:inline-block;/* width:450px*/}
.close_layer{float:right; cursor:pointer}
.pageloading{position:fixed; top:45%; left:50%; padding:8px 15px;line-height:50px; color:#fff; background:rgba(0,0,0,0.5); border-radius:10px; text-align:center; z-index:9999999;}
.checkbox label{ margin-right:8px; padding-right:8px; border-right:1px solid #e4e4e4;}
.checkbox label:last-child{margin-right:0; border:0;}
tr[setroomtype]{ border-top:3px solid #e4e4e4}

.head_cont{padding:20px 0 20px 10px;}
.j_head >div >span:nth-of-type(1){display:inline-block;width:60px;text-align:center;}
.head_cont .actives{background:#ff9900;color:#fff;border:1px solid #ff9900 !important;margin-left:2%;}
.screen,.h_btn_list> div{display:inline-block;width:100px;border:1px solid #d7e0f1;text-align:center;padding:4px 0px;border-radius:5px;margin-right:8px;}

.hotel_star >div:nth-of-type(2) >div,.hotel_star >div{display:inline-block;}
.hotel_star >div:nth-of-type(1){margin-right:28px;}
.input_radio >div{margin-right:10px;display: inline-block;}
td >input{display:none;}
td >input+label{font-weight:normal;text-indent:25px;background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/radio1.png) no-repeat center left;background-size:15px;width:110px;height:30px;line-height:30px;}
td >input:checked+label{background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/radio2.png) no-repeat center left;background-size:15px;}

.input_radio >div >input{display:none;}
.input_radio >div >input+label{font-weight:normal;text-indent:25px;background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/bg.png) no-repeat center left;background-size:15px;height:30px;line-height:30px;}
.input_radio >div >input:checked+label{background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/bg2.png) no-repeat center left;background-size:15px;}

tbody td,thead th{text-align:center;padding:5px 0;border:1px solid #d7e0f1;}
tbody td p,thead th p{margin-bottom:3px;}
thead th{font-weight:normal;}
tbody tr td:first-child{width:150px;}
tbody tr td[status=close]{background:#f8f9fb;}
tbody td p:last-child,thead th p:last-child{margin-bottom:0px;}
.bg_f8f9fb{background:#f8f9fb;}
.f_w_n{font-weight:normal;}
.border_1{border:1px solid #d7e0f1;}
.relative{position:relative;}
.absolute{position:absolute;}
.rotate_180{transform:rotate(-180deg);}
.arrow{transition:0.2s;}
.d_none{display:none;}
td,.main_menu{cursor:pointer;}

.layer{background:rgba(0,0,0,0.2); width:100%; height:100%; position:fixed; top:0; left:0; z-index:9999;display:none}
.layer >div >*{ padding:8px 12px 0 12px;}
.layer *{ vertical-align:middle}
.layer input{margin-top:0}
.layer >div{background:#fff; position:absolute; top:20%; width:550px; left:30%;border-radius:10px; padding-bottom:12px; overflow:hidden}
.layer h4{background:#00c0ef; color:#fff; margin-top:0; padding-bottom:12px;}
.layer label{padding-right:12px; font-weight:normal;}
.layer tt{display:block; font-weight:bold}
.layer .form-control{display:inline-block;/* width:450px*/}
.close_layer{float:right; cursor:pointer}
.pageloading{position:fixed; top:45%; left:50%; padding:8px 15px;line-height:50px; color:#fff; background:rgba(0,0,0,0.5); border-radius:10px; text-align:center; z-index:9999999;}
#search{    background-color: #ff9900;border-radius: 5px;width: 100px;}
.drow_list{ display:none; position:absolute;width:100%; top:100%; left:0; background:#fff; border:1px solid #e4e4e4; padding:0; max-height:300px; overflow:auto; z-index:999}
.input_txt {height: auto;}
</style>
<div class="col-xs-12">
    <div class="box box-primary">
        <div class="box-body">
            <div class="form-group col-xs-6">
                <label>选择酒店</label>
                <div class="input-group" style="position:relative" id="drowdown">
                    <input placeholder="选择或输入关键字" type="text" class="form-control" id="search_hotel" <?php if (isset($hotels[0]['name'])) echo 'value="'.$hotels[0]['name'].'"';?>>
                    <span class="input-group-btn">
                        <button id="search" type="button" class="btn btn-info btn-flat">查询</button>
                    </span>
                    <ul class="drow_list">
                        <?php foreach ($hotels as $k => $hotel):?>
                            <li value="<?=$hotel['hotel_id']?>" <?php if ($k==0) echo 'class="cur"';?>><?=$hotel['name']?></li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
            <div class="form-group col-xs-12">
              <label>按房型显示</label>
              <div class="input_txt input_radio checkbox room"> </div>
            </div>
            <div class="form-group col-xs-12">
              <label>按价格代码显示</label>
              <div class="input_txt input_radio checkbox price"> </div>
            </div>
        </div>
    </div>
        <div class="bg_fff head_cont">
              <!-- 
               td status="able"     正常.可编辑
               td status="disable"  禁用.不可编辑
               td status="close"    关房.可编辑
               td status="null"     空数据.可编辑
               div kucun    库存
               div ftai     房态
               div yuan     房价
              -->
              <div style="padding:15px 0">
                  <button type="button" class="btn btn-info" id="before">&lt;前10天</button>
                  <input type="button" id="startdate" class="btn btn-default dateselect" data-date-format="yyyy-mm-dd" value="<?php echo $yestoday;?>">
                  <button type="button" class="btn btn-info" id="after">后10天&gt;</button>
              </div>
                <table class="calendar_price" border="1" bordercolor="#e4e4e4">
                    
                </table>
          </div>
</div> 

</div>
</section>
<div class="layer" id="edit_status">
	<div>
    	<h4><em class="close_layer">&times;</em><span class="layertitle">修改</span></h4>
        <div ftai><tt>房态</tt><select class="form-control"><option value="1">关</option><option value="2">开</option></select></div>
        <div mass><tt>开始日期</tt><input id="starttime" name="startdate" type="text" date-format="yyyy-mm-dd" class="form-control dateselect"></div>
        <div mass><tt>结束日期</tt><input id="endtime" name="enddate" type="text" date-format="yyyy-mm-dd" class="form-control dateselect"></div>
        <div mass><tt>按星期修改</tt>
        	<label><input name="week" type="checkbox" week="1"> 周一</label>
            <label><input name="week" type="checkbox" week="2"> 周二</label>
            <label><input name="week" type="checkbox" week="3"> 周三</label>
            <label><input name="week" type="checkbox" week="4"> 周四</label>
        	<label><input name="week" type="checkbox" week="5"> 周五</label>
            <label><input name="week" type="checkbox" week="6"> 周六</label>
            <label><input name="week" type="checkbox" week="0"> 周日</label>
        </div>
        <div yuan><tt>房价</tt><input type="text" class="form-control"></div>
        <div kucun><tt>库存</tt><input type="text" class="form-control"></div>
        <div><button type="button" class="btn btn-block btn-info" onClick="save();">保存</button></div>
    </div>
</div>
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
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/layDate.js"></script>
<!--日历调用结束-->
<script>
;!function(){
    laydate({
       elem: '#startdate',
       choose: function(dates){ //选择好日期的回调
        initprice()
    }
    }),
    laydate({
       elem: '#starttime'
    }),
    laydate({
       elem: '#endtime'
    })
}();
</script>
<script>
	var Dom,hotel ;  // 触发弹层的元素;
	var allroom = [];
	var allprice= [];
    var nowhotel = 0;
    var submitting = false;
    var tips=$('#tips');
	$('#search_hotel').bind('input propertychange',function(){
		var val=$(this).val();
		if(val!=''|| val!=undefined){
			for(var i=0;i<$('.drow_list li').length;i++){
				if ( $('.drow_list li').eq(i).html().indexOf(val)>=0)
					$('.drow_list li').eq(i).show();
				else
					$('.drow_list li').eq(i).hide();
			}
		}else{
			$('.drow_list li').show();
		}
	});
	function fillcheckbox(dom,array){
		$(dom).html('');
		for(var i=0;i<array.length;i++){
			var html ='<div><input type="checkbox" id="'+array[i].name+array[i].val+'" name="'+array[i].name+'" value="'+array[i].val+'"/><label for="'+array[i].name
            +array[i].val+'">'+array[i].text+'</label></div>';
			$(dom).append(html);
		}
        return true;
	}
    function getMyDay(date){
        var week; 
        if(date.getDay()==0) week="周日"
        if(date.getDay()==1) week="周一"
        if(date.getDay()==2) week="周二" 
        if(date.getDay()==3) week="周三"
        if(date.getDay()==4) week="周四"
        if(date.getDay()==5) week="周五"
        if(date.getDay()==6) week="周六"
        return week;
    }
    //初始化价格代码
    function initprice(){
        if(submitting){
            return;
        }
        submitting = true;
        //酒店
		var tmproom = [];
		var tmpprice= [];
        hotel = $('.cur').val();
        var roomcheck = new Array();
        $('input[name=setroomtype]:checked').each(function(){
            roomcheck.push($(this).val());
        });
        var pricecheck = new Array();
        $('input[name=setpricetype]:checked').each(function(){
            pricecheck.push($(this).val());
        });
        if( typeof(hotel) =='undefined'){
            alert('请选择酒店');return false;
        }
        //日期
		showtips();
        var begindate = $('#startdate').val();
        $.getJSON('<?php echo site_url('hotel/room_status/get_price_codes')?>',{'hotel':hotel,'begindate':begindate},function(datas){
            html = '<tr><th></th><th>日期</th>';
            for (var i in datas['date']) {
                html += '<th><div>' + datas['date'][i]['date'] + '</div><div>' + datas['date'][i]['week'] + '</div></th>';
            }
            html += '</tr>';
			var obj = {};
            for (var i in datas['codes']) {
				
				if( JSON.stringify(tmproom).indexOf(':"'+i+'"')<0){
					obj = {};
					obj['val']=i;
					obj['name']='setroomtype';
					obj['text']=datas['codes'][i]['roomname'];
					tmproom.push(obj); 
				}
				if( nowhotel!=hotel || roomcheck.length ==0 || $.inArray(i, roomcheck) != -1 ){
                    var style = 'style="display: table-row;"';
                }else{
                    var style = 'style="display: none;"';
                }

                html += '<tr setroomtype="'+i+'"'+ style +'><td class="roomtype">' + datas['codes'][i]['roomname'] + '</td><td><div>房态</div><div>库存</div></td>';
                for (var j in datas['date']) {

                    var nums = datas['codes'][i]['date_arr'][datas['date'][j]['date']]['nums'];
                    if(datas['codes'][i]['date_arr'][datas['date'][j]['date']]['ftai'] == '-1'){
                        html += '<td status="close" date="'+datas['date'][j]['date']+'"><div ftai val="1">关</div><div kucun>'+ nums +'</div></td>';
                    }else if(datas['codes'][i]['date_arr'][datas['date'][j]['date']]['ftai'] == '-2'){
                        html += '<td status="able" date="'+datas['date'][j]['date']+'"><div ftai val="1">开</div><div kucun>'+ nums +'</div></td>';
                    }else{
                        html += '<td status="close" date="'+datas['date'][j]['date']+'"><div ftai val="1"></div><div kucun>'+ nums +'</div></td>';
                    }
                }
                html += '</tr>';
				var sta = 'able';
                for (var j in datas['codes'][i]['codes']) {
					if( JSON.stringify(tmpprice).indexOf(':"'+j+'"')<0){
						obj = {};
						obj['val']=j;
						obj['name']='setpricetype';
						obj['text']=datas['codes'][i]['codes'][j]['name'];
						tmpprice.push(obj); 
					}
                    if(nowhotel!=hotel || (roomcheck.length ==0 || $.inArray(i, roomcheck) != -1) && (pricecheck.length ==0 || $.inArray(j, pricecheck) != -1)){
                        var style = 'style="display: table-row;"';
                    }else{
                        var style = 'style="display: none;"';
                    }

                    html += '<tr setpricetype="'+ i +'"'+style+'><td class="pricetype" code="'+j+'" status="able">'+ datas['codes'][i]['codes'][j]['name']+'<font class="fa fa-fw fa-edit"></font></td><td><div>房价</div><div>库存</div></td>';
                    for (var k in datas['date']) {
                        var price = datas['codes'][i]['codes'][j]['date_arr'][datas['date'][k]['date']]['price'];
                        var nums = datas['codes'][i]['codes'][j]['date_arr'][datas['date'][k]['date']]['nums'];
                        if ( price=='')price='-';
                        if ( nums == '')nums='-';
                        if ( price == '-' && nums == '-' ) sta = 'null';
                        else sta = 'able';
                        html += '<td status="'+sta+'" date="'+datas['date'][k]['date']+'"><div yuan>'+ price +'</div><div kucun>' + nums + '</div></td>';
                    }
                    html += '</tr>';
                }

            }
            if ( nowhotel!=hotel){
                nowhotel = hotel;
                fillcheckbox('.room',tmproom);
                fillcheckbox('.price',tmpprice);
            }

            $('.calendar_price').html(html);
            //绑定事件start
            $('td[status]').click(function(){
                if($(this).attr('status')=='disable')return false;
                Dom=$(this);
                $('#edit_status div[mass]').hide();
                if( Dom.parents('tr').attr('setroomtype')!=undefined){
                    $('#edit_status div[ftai]').show();
                    $('#edit_status div[yuan]').hide();
                }else{
                    $('#edit_status div[ftai]').hide();
                    $('#edit_status div[yuan]').show();
                }
                showlayer();
            })
            $('.pricetype').click(function(){
                $('#edit_status div[ftai]').hide();
                $('#edit_status div[mass]').show();
            })
            $('.close_layer').click(function(){
                $('#edit_status').hide();
            });
			submitting = false;
			rmtips();
            //绑定事件end
        });
    }
    //初始化日历
    function inittable(){
        var startdate = $('#startdate').val();
        var timestamp = Date.parse(new Date(startdate + " 00:00:00")) + 24*60*60*1000*10;
        var newDate = new Date();
        newDate.setTime(timestamp);
        var enddate = newDate.getFullYear() + '-' + PrefixInteger((newDate.getMonth()+1),2) + '-' + PrefixInteger(newDate.getDate(),2);

    }
	$('.drow_list li').click(function(){
        if(submitting){
            return;
        }
		$('#search_hotel').val($(this).text());
		$(this).addClass('cur').siblings().removeClass('cur');
	});

	$('td[status]').click(function(){
		if($(this).attr('status')=='disable')return false;
		Dom=$(this);
		$('#edit_status div[mass]').hide();
		if( Dom.parents('tr').attr('setroomtype')!=undefined){
            $('#edit_status div[ftai]').show();
			$('#edit_status div[yuan]').hide();
        }else{
            $('#edit_status div[ftai]').hide();
            $('#edit_status div[yuan]').show();
        }
		showlayer();
	})
	$('.pricetype').click(function(){
        $('#edit_status div[ftai]').hide();
        $('#edit_status div[mass]').show();
	})
	$('.close_layer').click(function(){
		$('#edit_status').hide();
	})
    function PrefixInteger(num, n) {
        return (Array(n).join(0) + num).slice(-n);
    }
    $('#before').click(function(){
        var now = $('#startdate').val() + " 00:00:00";
        var timestamp = Date.parse(new Date(now.replace(/-/g, "/"))) - 24*60*60*1000*10;
        var newDate = new Date();
        newDate.setTime(timestamp);
        $('#startdate').val(newDate.getFullYear() + '-' + PrefixInteger((newDate.getMonth()+1),2) + '-' + PrefixInteger(newDate.getDate(),2));
        initprice();
    })
    $('#after').click(function(){
        var now = $('#startdate').val() + " 00:00:00";
        var timestamp = Date.parse(new Date(now.replace(/-/g, "/"))) + 24*60*60*1000*10;
        var newDate = new Date();
        newDate.setTime(timestamp);
        $('#startdate').val(newDate.getFullYear() + '-' + PrefixInteger((newDate.getMonth()+1),2) + '-' + PrefixInteger(newDate.getDate(),2));
        initprice()
    })
    $('#search').click(function(){
        initprice();
    })
	function show_hide(dom,bool){
		if (bool) $(dom).show();
		else $(dom).hide();
	}
	function forprice( allprice,val ){
		for(var i=0; i<$('input[name=setpricetype]').length;i++){
			var dom =$('input[name=setpricetype]').eq(i);
			var attr = dom.attr('name');
			var _val = dom.val();
			var checked = dom.get(0).checked;
			if(checked||allprice){
				$('td[code='+_val+']').each(function(index, element) {
                    if($(this).parent().attr('setpricetype')==val)
						$(this).parent().show();
                });
			}
		}
	}
	function forroom (allroom,allprice){
		for(var i=0; i<$('input[name=setroomtype]').length;i++){
			var dom = $('input[name=setroomtype]').eq(i);
			var attr = dom.attr('name');
			var val = dom.val();
			var checked = dom.get(0).checked;
			if(allroom) checked = allroom;
			show_hide('tr['+attr+'='+val+']',checked);
			if(checked){	
				forprice(allprice,val);
			}
		}
	}
	$('.checkbox').click(function(e){
        if(e.target.tagName!="INPUT")
        return;
		var bool =false;
        $('input[name=setroomtype]').each(function(){ if( $(this).get(0).checked) bool=true;});
		$('input[name=setpricetype]').each(function(){ if( $(this).get(0).checked) bool=true;});
		if(!bool){
			show_hide('tr[setroomtype]',true);
			show_hide('tr[setpricetype]',true);
		}else{
			show_hide('tr[setroomtype]',false);
			show_hide('tr[setpricetype]',false);
			var allroom =true;
			$('input[name=setroomtype]').each(function(){if( $(this).get(0).checked)allroom=false; });
			var allprice=true;
			$('input[name=setpricetype]').each(function(){if( $(this).get(0).checked)allprice=false; });
			forroom (allroom,allprice);
		}
	});
	function resetlayer(){  //重置弹层;
		$('#edit_status input').val('');
		$('#edit_status div[ftai] select').val(1);
		$('#edit_status input[type=checkbox]').each(function() {
            $(this).get(0).checked=true;
        });
	}
	function showlayer(){ //显示弹层;
		var kucun =Dom.find('div[kucun]').html();
		var ftai  =Dom.find('div[ftai]').html();
		var yuan  =Dom.find('div[yuan]').html();
		resetlayer();
        if(ftai!=undefined&&ftai!='-'){
            ftai='房态(当前:'+ftai+')';
            $('#edit_status div[ftai] select').val(Dom.find('div[ftai]').attr('val'));
        } else ftai='房态';
        if(kucun!=undefined&&kucun!='-'){
            $('#edit_status div[kucun] input').val(kucun);
            kucun='库存(当前:'+kucun+')';
        } else kucun='库存';
        if(yuan!=undefined&&yuan!='-'){
            $('#edit_status div[yuan] input').val(yuan);
             yuan='房价(当前:¥'+yuan+')';
        }
        else yuan='房价';

        $('#edit_status div[kucun] tt').html(kucun);
        $('#edit_status div[yuan] tt').html(yuan);
        $('#edit_status div[ftai] tt').html(ftai);
		$('#edit_status').show();
		/*修改标题*/
		var tmp = '修改';
		var a = Dom.parents('tr').attr('setroomtype');
		var b = Dom.parents('tr').attr('setpricetype');
		var c = Dom.index();
		tmp+=$('.calendar_price th').eq(c).text()?' - '+$('.calendar_price th').eq(c).text() +' - ':' - ';
		tmp+=a?$('tr[setroomtype='+a+']').find('.roomtype').html():$('tr[setroomtype='+b+']').find('.roomtype').html();
		tmp+=b?' - '+Dom.parents('tr').find('.pricetype').html():' - 所有价格代码';
		$('.layertitle').html(tmp);
		/*end*/
	}
	function save(){
		var kucun,yuan,ftai,ftaival;
		kucun = $('#edit_status div[kucun] input').val();
		yuan  = $('#edit_status div[yuan] input').val();
		ftai  = $('#edit_status div[ftai] option:selected').text();
		ftaival  = $('#edit_status div[ftai] option:selected').val();
		if(ftaival==1)Dom.attr('status','able');
		if(ftaival==2&&!Dom.hasClass('pricetype'))Dom.attr('status','close');
		if(kucun=='')kucun='-';
		if(yuan =='')yuan ='-';
		// if(kucun==yuan)Dom.attr('status','null');
		
        var room_id = Dom.parents('tr').attr('setpricetype');
        var price_code = Dom.parents('tr').find('.pricetype').attr('code');
		if(Dom.parents('tr').attr('setroomtype')!=undefined){  /*触发元素为:房型*/
            date = Dom.attr('date');
            type = ftaival;
            room_id = Dom.parents('tr').attr('setroomtype');
            $.getJSON('<?php echo site_url('hotel/room_status/save_calendar_ftai')?>',{'hotel_id':hotel,'room_id':room_id,'date':date,'type':ftaival,'room_num':kucun},function(datas){
                if(datas.code == 0){
                    initprice();
                    showtips('已保存',true);
                }else{
                    showtips(datas.msg,true);
                }
            });
		}
		else if(Dom.hasClass('pricetype')){  /*触发元素为:价格代码*/	
            var startdate = $('input[name=startdate]').val();
			var enddate = $('input[name=enddate]').val();
            var weekarray = new Array();
            $('input[name=week]:checked').each(function(){
                weekarray.push($(this).attr('week'));
            });
            if(!startdate || !enddate || !weekarray.length){
                alert('日期与星期必填！');
                return;
            }
            $.getJSON('<?php echo site_url('hotel/room_status/save_calendar_price')?>',{'startdate':startdate,'enddate':enddate,'weekarray':weekarray,'hotel_id':hotel,'room_id':room_id,'price_code':price_code,'price':yuan,'room_num':kucun},function(datas){
                if(datas.code == 0){
                    initprice();
                    showtips('已保存',true);
                }else{
                    showtips(datas.msg,true);
                }
            });
		}
		else{  /*触发元素为:单个单元格*/
            daybox = new Array();
            daybox[0] = Dom.attr('date');
            $.getJSON('<?php echo site_url('hotel/room_status/save_calendar_price')?>',{'daybox':daybox,'hotel_id':hotel,'room_id':room_id,'price_code':price_code,'price':yuan,'room_num':kucun},function(datas){
                if(datas.code == 0){
                    initprice();
                    showtips('已保存',true);
                }else{
                    showtips(datas.msg,true);
                }
            });
		}
		$('#edit_status').hide();
	}
	function showtips(str,autoclose){
		rmtips();
		if(str==undefined||str=='')str='正在加载中...';
		$('body').append('<div class="pageloading">'+str+'</div>');
		if(autoclose!=undefined&&autoclose==true)
			window.setTimeout(rmtips,700);
	}
	function rmtips(){
		$('.pageloading').remove();
	}
    initprice();
</script>
</body>
</html>
