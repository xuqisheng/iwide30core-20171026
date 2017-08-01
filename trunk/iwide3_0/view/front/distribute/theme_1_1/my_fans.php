<title>我的粉丝</title>
<link href="<?php echo base_url('public/media/styles/fontchange.css')?>" rel="stylesheet">
<style>
body,html{background:#fff;}
</style>
</head>
<body>
<div class="fixed_header">
	<div class="webkitbox bg_d8">
    	<div class="iscur" rel='event_time' st='desc'>按发展时间排序<em class="iconfont">&#x3e;</em></div>
    	<div rel='total_amount' st='asc'>按收益额度排序<em class="iconfont">&#x3e;</em></div>
    	<div rel='last_order_date' st='asc'>按交易时间排序<em class="iconfont">&#x3e;</em></div>
    </div>
</div>

<div class="user_list" style="padding-top:13%"></div>
</body>
</body>
</html>
<script type="text/javascript">
	var source = <?php echo $saler_details?>;


	Array.prototype.sortObjectWith = function ( key, t, fix){
	    if( !this.length ){ return this;}          // 空数组
	    t = t ==='desc'? 'desc': 'asc';    // ascending or descending sorting, 默认 升序
	    fix = Object.prototype.toString.apply( fix )==='[object Function]'? fix: function(key){ return key; };
	    switch( Object.prototype.toString.apply( fix.call({},this[0][key]) ) ){
	        case '[object Number]':
	            return this.sort( function(a, b){ return t==='asc'?( fix.call({},a[key]) - fix.call({},b[key]) ) :( fix.call({},b[key]) - fix.call({},a[key])); } );
	        case '[object String]':
	            return this.sort( function(a, b){ return t==='asc'? fix.call({},a[key]).localeCompare( fix.call({},b[key])) : fix.call({},b[key]).localeCompare( fix.call({},a[key])); } );
	        default: return this;  // 关键字不是数字也不是字符串, 无法排序
	    }
	}
	function generate(datas){
		$('.user_list').html('');
		var str = '';
		if(datas.length > 0){
			for(var i=0;i < datas.length; i++){
				str += '<a href=\"<?php echo site_url('distribute/dis_v1/fans_blogs')?>?id=<?php echo $inter_id?>&fid=' + datas[i].id + '\" class=\"item\">';
				str += '<div class=\"user_img\"><img src=\"' + datas[i].headimgurl + '\" /></div>';
				//str = '<div class=\"new\">New</div>';
				str += '<div class="h1 txtclip">' + datas[i].nickname + ' <span class=\"h3\">' + datas[i].hotel_name + '</span></div>';
				str += '<div>产生收益：￥' + datas[i].total_amount + '</div><div>';
				if ( $('div[rel="last_order_date"]').hasClass('iscur') ){
					str+='最后交易';
					str += '时间：' + datas[i].last_order_date + '</div>';
				}else{ 
					str+='发展';
					str += '时间：' + datas[i].event_time + '</div>';
				}
				str += '</a>';
			}
		}else{
			str += '<p>您还没有粉丝哦!!!</p>';
		}
		$('.user_list').html(str);
	}

	$('.fixed_header .webkitbox div').click(function(){
		$(this).addClass('iscur').siblings().removeClass('iscur');
		var sortType = $(this).attr('st') == 'asc' ? 'desc' : 'asc';
		$(this).attr('st',sortType);
		var sortKey = $(this).attr('rel');
		source = source.sortObjectWith(sortKey,sortType,'fix');
		generate(source);
	});
	source = source.sortObjectWith('event_time','desc','fix');
	generate(source);
</script>