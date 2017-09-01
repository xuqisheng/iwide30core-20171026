<!doctype html>
<html>
<head>
<?php echo referurl('js','jquery.js',2,$media_path) ?>
</head>
<body style="display:none">
<?php echo form_open_multipart('chat/upload/do_upload');?>
<input type="file" name="userfile" size="20" accept="image/*" />
</form>
<script type="text/javascript">
var error = '<?php echo strip_tags(str_replace("'","\'",$error));?>';
if(error){
	alert(error);
	error = '';
}
$("form input").change(function(){
    if(typeof(parent.qfupready)!='undefined'){
	    parent.qfupready(this);
	}
	else {
	    $("form").submit();
	}
});
</script>
</body>
</html>