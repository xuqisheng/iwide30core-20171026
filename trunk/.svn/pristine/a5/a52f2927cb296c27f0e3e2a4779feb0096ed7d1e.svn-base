<title>意见反馈</title>
</head>
<style>
body,html{background:#fff}
.webkitbox{padding-bottom:3%; text-align:left}
.webkitbox>*:first-child{max-width:5em;}
.new{float:none; padding:0.8% 0; text-align:center; margin-right:2%}
.tmp{padding-top:5%; margin-top:10%;; text-align:justify}
.ui_foot_fixed_btn{background:#f8f8f8;}
.ui_foot_fixed_btn > *{display:inline-block; border-radius:1rem; background:#ff7200; padding:2% 8%; color:#fff; border:1px solid #e09832;}
textarea{width:100%;height:8em;border-bottom:.1em solid #ccc;}
</style>
<body>

<div>
<textarea name="content" id="content" class="" placeholder="您可以在此输入您的建议或者需求以帮助我们改善我们的产品"></textarea>
</div>
<div class="ui_foot_fixed_btn">
	<a onclick="submit()">反馈</a>
</div>
</body>
</html>
<script>
var flag = true;
function submit(){
	if(flag){
		flag = false;
		$.post('<?php echo site_url('distribute/dis_v1/do_feeback')?>?id=<?php echo $inter_id?>',{content:$('#content').val(),<?php echo config_item('csrf_token_name') ?>: '<?php echo $this->security->get_csrf_hash() ?>'},function(data){
			if(data.errmsg == 'ok'){
				alert('提交成功，感谢您的反馈');
			}else{
				alert(data.errmsg == undefined ? '提交失败！' : data.errmsg);
			}
			flag = true;
		},'json');
	}
}
</script>