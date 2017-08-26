<?php
session_start();
//合成推广图片 $user_id
$path  = "./images/product/".date('Ym').'/';
$img=$path.random_filename().".jpg";//最终生成图片
$defaut_two='./images/blank-430.jpg';//默认二维码
make_dir($path);
//背景
$img_two_background = './images/background.jpg';
$img_two=$_SESSION['upload'];//二维码
$img=mergerTwo($img_two_background, $img_two, $img, 192.5, 200,$defaut_two);
//删除上传头像
@unlink($img_two);
header('location:./show.php?img='.base64_encode($img));


/*函数mergerCopyImg给头像加3px 白色边框
* @param string $img_head_background    138*138背景图片
* @param string $img_head               二维码里面头像
* @param string $jpg                    通过该函数创建出来的新图片
* @param int    $img_head_x             头像的X轴
* @param int    $img_head_y             头像的Y轴
* @param string $img_head_default       二维码里面头像出错用默认图像
*/
function mergerCopyImg($img_head_background, $img_head, $jpg, $img_head_x=0, $img_head_y=0, $img_head_default = 'head.jpg'){
    list($i_h_b_width, $i_h_b_height) = getimagesize($img_head_background); // 获取$img_head_background的宽高
    $canvas = imagecreatetruecolor($i_h_b_width, $i_h_b_height);  // 创建画布
    $new_img = imagecreatefromjpeg($img_head_background); //创建一个新图像
    imagecopy($canvas, $new_img, 0, 0, 0, 0, $i_h_b_width, $i_h_b_height); //copy图片$img_head_background
    imagedestroy($new_img);  //销毁图片$new_img
    $new_img = imagecreatefromjpeg($img_head); // 创建一个新图像
    if($new_img){
        list($i_h_width, $i_h_height) = getimagesize($img_head); // 获取$img_head的宽高
        $result = imagecopyresized($canvas, $new_img, $img_head_x, $img_head_y, 0, 0, $i_h_width, $i_h_height, $i_h_width, $i_h_height); //将一幅图像中的一块矩形区域拷贝到另一个图像中，并设置大小
    }else{
        $result = false;
    }
    //如果头像出错
    if(!$result){
        $new_img = imagecreatefromjpeg($img_head_default);
        list($default_width, $default_height) = getimagesize($img_head_default);
        imagecopyresized($canvas, $new_img, $img_head_x, $img_head_y, 0, 0, $default_width, $default_height, $default_width, $default_height);
    }
    imagedestroy($new_img); //销毁图片
    imagejpeg($canvas, $jpg);
}

/*函数mergerImg将头像放到二维码中间
* @param String $img_two            二维码
* @param String $jpg                头像
* @param String $jpg2               通过该函数合成出来的新图片
* @param int    $jpg_x              头像X轴
* @param int    $jpg_y              头像Y轴    
* @param String $img_two_default    二维码不存在默认二维码
*/
function mergerImg($img_two, $jpg, $jpg2, $jpg_x=0, $jpg_y=0, $img_two_default='blank-430.jpg') {
    if(!file_exists($img_two)){
        $img_two = $img_two_default;
    }
    //先把二维码copy到新图上  获取图片的宽高
    list($i_t_width, $i_t_height) = getimagesize($img_two);
    $canvas = imagecreatetruecolor($i_t_width, $i_t_height);//创建画布
    $new_img = imagecreatefromjpeg($img_two);
    imagecopy($canvas, $new_img, 0, 0, 0, 0, $i_t_width, $i_t_height);
    imagedestroy($new_img);
    //再把小图放到新图二维码
    $new_img = imagecreatefromjpeg($jpg);
    list($t_j_width, $t_j_height) = getimagesize($jpg);
    imagecopy($canvas, $new_img, $jpg_x, $jpg_y, 0, 0, $t_j_width, $t_j_height);
    imagedestroy($new_img);
    //header("Content-type: image/jpeg");
    imagejpeg($canvas,$jpg2); //输出图像到浏览器或文件
}

/* 函数mergerTwo把二维码放至图片中
* @param String $img_two_background    背景图片
* @param String $jpg2                   二维码
* @param String $jpg3                   通过该函数合成出来的新图片
* @param int    $jpg2_x                 起始位置二维码X轴
* @param int    $jpg2_y                 起始位置二维码Y轴
* @param String $img_two_default        默认二维码图片
*/
function mergerTwo($img_two_background, $jpg2, $jpg3, $jpg2_x=0, $jpg2_y=0, $img_two_default = 'blank-430.jpg') {
    //先把背景图片放入画布
    list($i_t_b_width, $i_t_b_height) = getimagesize($img_two_background);
    $canvas = imagecreatetruecolor($i_t_b_width, $i_t_b_height);
    $new_img = imagecreatefromjpeg($img_two_background);
    imagecopy($canvas, $new_img, 0, 0, 0, 0, $i_t_b_width, $i_t_b_height);
    imagedestroy($new_img);	
	//再把二维码放入画布
    list($jpg2_width, $jpg2_height) = getimagesize($jpg2);	
	$rate=$jpg2_width/215;
	$new_height=$jpg2_height/$rate;//新的高
    $new_img = imagecreatefromjpeg($jpg2);
    $result = imagecopyresized($canvas, $new_img, $jpg2_x, $jpg2_y, 0, 0, 215, $new_height, $jpg2_width, $jpg2_height);
	//不成功合成默认二维码
    if(!$result){
        list($i_h_d_width, $i_h_d_height) = getimagesize($img_two_default);
        $new_img = imagecreatefromjpeg($img_two_default);
        imagecopyresized($canvas, $new_img, $jpg2_x, $jpg2_y, $i_h_d_width*0.5, $i_h_d_heght*0.5, $i_h_d_width, $i_h_d_height);
    }
    imagedestroy($new_img);
    imagejpeg($canvas, $jpg3);
	return $jpg3;
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
/**
 * 检查目标文件夹是否存在，如果不存在则自动创建该目录
 *
 * @access      public
 * @param       string      folder     目录路径。不能使用相对于网站根目录的URL
 *
 * @return      bool
 */
function make_dir($folder)
{
	$reval = false;

	if (!is_dir($folder))
	{
		/* 如果目录不存在则尝试创建该目录 */
		@umask(0);

		/* 将目录路径拆分成数组 */
		preg_match_all('/([^\/]*)\/?/i', $folder, $atmp);

		/* 如果第一个字符为/则当作物理路径处理 */
		$base = ($atmp[0][0] == '/') ? '/' : '';

		/* 遍历包含路径信息的数组 */
		foreach ($atmp[1] AS $val)
		{
			if ('' != $val)
			{
				$base .= $val;

				if ('..' == $val || '.' == $val)
				{
					/* 如果目录为.或者..则直接补/继续下一个循环 */
					$base .= '/';

					continue;
				}
			}
			else
			{
				continue;
			}

			$base .= '/';

			if (!is_dir($base))
			{
				/* 尝试创建目录，如果创建失败则继续循环 */
				if (@mkdir(rtrim($base, '/'), 0777))
				{
					@chmod($base, 0777);
					$reval = true;
				}
			}
		}
		clearstatcache();
	}
	else
	{
		/* 路径已经存在。*/
		$reval = true;
	}	
	return $reval;
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>裁剪图片</title>
	<meta charset="utf-8"/>
</HEAD>
<body style="padding:0;">
	<img style="width:100%;" src="<?php echo $img;?>"/>
</body>
</html>