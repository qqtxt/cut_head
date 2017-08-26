<?php
$img=base64_decode($_REQUEST['img']);
?>
<!DOCTYPE html>
<html>
<head>
	<title>我的图片</title>
	<meta charset="utf-8"/>
</HEAD>
<body style="padding:0;margin:0;">
	<img style="width:100%;" src="<?php echo $img;?>"/>
</body>
</html>