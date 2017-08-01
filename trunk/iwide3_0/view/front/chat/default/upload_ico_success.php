<html>
<head>
</head>
<body>
<?php
$w = isset($_GET['w'])?intval($_GET['w']):'';
$h = isset($_GET['h'])?intval($_GET['h']):'';
unset($upload_data['file_path']);
unset($upload_data['full_path']);
$retdata = json_encode($upload_data);
?>
<script type="text/javascript">
parent.qfuploadico(<?php echo $retdata;?>);
location.href = '/index.php/chat/uploadico/?w=<?php echo $w;?>&h=<?php echo $h;?>';
</script>
</body>
</html>