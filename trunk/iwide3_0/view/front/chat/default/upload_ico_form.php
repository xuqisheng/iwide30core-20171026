<!doctype html>
<html>
<head>
<?php echo referurl('js','jquery.js',2,$media_path) ?>
</head>
<body style="display:none">
<?php 
$w = isset($_GET['w'])?intval($_GET['w']):'';
$h = isset($_GET['h'])?intval($_GET['h']):'';
echo form_open_multipart('chat/uploadico/do_upload?w='.$w.'&h='.$h);?>
<input type="file" name="userfile" size="20" accept="image/*" />
</form>
<script type="text/javascript">
var error = '<?php echo strip_tags(str_replace("'","\'",$error));?>';
if(error){
	alert(error);
	error = '';
}
$("form input").change(function(){
	$("form").submit();
});
</script>
</body>
</html>