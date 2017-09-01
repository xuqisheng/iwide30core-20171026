<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
        <meta name="Maker" content="Taoja" tel="13544425200">
        <meta name="format-detection" content="telephone=no" />
        <title>智能调价</title>
        <style>
			body{
				margin: 0px;
				padding: 0px;
				font-size: 16px;
			font-family: 微软雅黑;
			}
			.w100{
				width: 100%;
			}
			.dimg{
				margin-top: 20px;
				width: 100%;
				text-align: center;
			}
			.wz{
				margin-top: 20px;
				text-align: center;
			}
		</style>
	</head>
	<body>
		<div class="dimg"><img class="<?php if($h=='ok'||$h=='cancel'){echo 'w100';}?>" src="<?php echo $img;?>"></div>
		<div class="wz"><?php echo $msg;?></div>
	</body>
</html>