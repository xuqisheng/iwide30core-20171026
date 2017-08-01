<title>分销绑定</title>
</head>
<style>
body,html{background:#fff}
.webkitbox{padding-bottom:3%; text-align:left}
.webkitbox>*:first-child{max-width:5em;}
.new{float:none; padding:0.8% 0; text-align:center; margin-right:2%}
.tmp{padding-top:5%; margin-top:10%;; text-align:justify}
.ui_foot_fixed_btn{background:#f8f8f8;}
.ui_foot_fixed_btn > *{display:inline-block; border-radius:1rem; background:#ff7200; padding:2% 8%; color:#fff; border:1px solid #e09832;}
</style>
<body><ol>
    	<?php foreach ($query as $staff):?><li><a href="javascript:;" rel='bind' data='<?php echo $staff->qrcode_id.'-'.$staff->name?>' qid='<?php echo $staff->qrcode_id?>'><?php echo $staff->qrcode_id.'-'.$staff->name?></a>&nbsp;</li><?php endforeach;?>
    	</ol>
</body>
</html>
<script>
	$('a[rel=bind]').click(function(){
		var _this = $(this);
		if(confirm('绑定后产生的绩效将发放到 “'+_this.attr('data')+'” 的账户上，绑定后不可更改，请认真核对信息时候正确!!!')){
			$.get('<?php echo site_url('distribute/dis_v1/dobind')?>',{'qid':_this.attr('qid')},function(data){
				if(data){
					alert('绑定成功');
					window.location.reload();
				}else{
					alert('绑定失败');
				}
			});
		}
	});
</script>