<html>
<head>
</head>
<body>
<?php
unset($upload_data['file_path']);
unset($upload_data['full_path']);
$retdata = json_encode($upload_data);
?>
<script type="text/javascript">
parent.qfupload(<?php echo $retdata;?>);
location.href = '/index.php/chat/upload/';
</script>
</body>
</html>