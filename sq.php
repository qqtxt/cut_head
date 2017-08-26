<?php
//header('Content-type:text/html;charset=utf-8');
if( isset($_POST['chk'])){
	$toFile='./images/background.jpg';
	$myfile = $_FILES['myfile'];
	if (@move_uploaded_file($myfile['tmp_name'], $toFile)) {
		echo '上传成功！';
	} else {
		echo '上传失败,请重试！';
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>上传背景图片</title>
	<meta charset="utf-8"/>
</HEAD>
<body style="padding:0;margin:0;">
<form action="#" method="post" enctype="multipart/form-data">
<input type="file" name="myfile" required="">
<input type="hidden" name="chk" value="1">
<button type="submit">请上传600*1000JPG背景图</button>
</form>
</body>
</html>