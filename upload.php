<?php
//echo random_filename().'<br/>'.random_filename();exit;
header('Content-type:text/html;charset=utf-8');
session_start();
$base64_image_content = $_POST['imgBase64'];
//匹配出图片的格式
if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
	$type = $result[2];
	$new_file = "./images/head/";
	if(!file_exists($new_file))
	{
		//检查是否有该文件夹，如果没有就创建，并给予最高权限
		mkdir($new_file, 0755);
	}
	$png = $new_file.random_filename().".{$type}";
	$new_file1= $new_file.random_filename().'.jpg';
	if (file_put_contents($png, base64_decode(str_replace($result[1], '', $base64_image_content)))){
		//转成jpg
		$data = GetImageSize($png);
		switch ($data[2])
		{
			case 1:
				$im = imagecreatefromgif($png);break;
			case 2:
				$im = imagecreatefromjpeg($png);break;
			case 3:
				$im = imagecreatefrompng($png);break;
			default:
				echo '';
		}
		imagejpeg($im,$new_file1,100);
		@unlink($png);
		imagedestroy($im);
		$_SESSION['upload']=$new_file1;
		echo $new_file1;
	}else{
		echo '';
	}
}

/**
 * 生成随机的文件名
 *
 * @author: weber liu
 * @return string
 */
function random_filename()
{
	$name = date('YmdHis-').substr(sprintf("%.6f",microtime(true)),11);
	for ($i = 0; $i < 3; $i++){
		$name .= chr(mt_rand(97, 122));
	}
	return $name;
}
?>