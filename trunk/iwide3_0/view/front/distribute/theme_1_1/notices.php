<title>我的消息</title>
</head>
<style>
.ui_btn_list .item{background-size:auto 0.7rem}
</style>
<body>
<div class="fixed_header">
	<div class="webkitbox bg_d8">
    	<div class="iscur" rel='sys'>系统消息</div>
    	<div rel='qa'>常见问题</div>
    </div>
</div>

<div class="ui_btn_list ui_border" style="margin-top:13%">
	<p style="text-align: center">没有消息记录...</p>
</div>

</body>
<script>
$('.fixed_header .webkitbox div').click(function(){
	$(this).addClass('iscur').siblings().removeClass('iscur');
	if($(this).attr('rel') == 'qa'){
		var str = '';
		$.getJSON("<?php echo site_url('distribute/dis_v1/msgs_asy');?>?id=<?php echo $inter_id?>&mstyp=qa",function(data){
			$.each(data,function(k,v){
				str += '<a href="<?php echo site_url('distribute/dis_v1/msg_det');?>?id=<?php echo $inter_id?>&mid='+v.eid+'" class="item">';
		    	str += '<p class="h2">'+v.title
				if(v.flag == 0){
					str += '<i style="color:red">New</i>';
				}
		    	str += '</p>';
		    	str += '<p class="h4 co_aaa">'+v.create_time+'</p>';
		    	str += '<p>'+v.title+'</p>';
		    	str += '<p>'+v.sub_title+'</p></a>';
			});
			if(str.length > 0){
				$('.ui_btn_list').html(str);
			}else{
				$('.ui_btn_list').html('<p style="text-align: center">没有资料...</p>');
			}
		});
	}else{
		window.location.reload();
	}
});
asy_msgs();
function asy_msgs(){
	var str = '';
	$.getJSON("<?php echo site_url('distribute/dis_v1/msgs_asy');?>?id=<?php echo $inter_id?>&mstyp=",function(data){
		$.each(data,function(k,v){
			str += '<a href="<?php echo site_url('distribute/dis_v1/msg_det');?>?id=<?php echo $inter_id?>&mid='+v.eid+'" class="item">';
			if(v.msg_typ == 0){
		    	str += '<p class="h2">酒店发放收益</p>';
			}else{
				str += '<p class="h2">'+v.title
				if(v.flag == 0){
					str += '<i style="color:red">New</i>';
				}
		    	str += '</p>';
			}
	    	str += '<p class="h4 co_aaa">'+v.create_time+'</p>';
	    	//str += '<p>'+v.title+'</p>';
	    	str += '<p>'+v.sub_title+'</p></a>';
		});
		if(str.length > 0){
			$('.ui_btn_list').html(str);
		}else{
			$('.ui_btn_list').html('<p style="text-align: center">没有资料...</p>');
		}
	});
}
</script>
</html>