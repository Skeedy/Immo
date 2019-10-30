<?php
if(!empty($_GET['filename'])) {
	require_once 'lib/init.php';

	if(!empty($_GET['external'])) {
		$gd_image = @imagecreatefromjpeg($_GET['filename']);
		if(!$gd_image) {
			$gd_image = imagecreate(354, 200);
			imagecolorallocate($gd_image, 0, 0, 0);
		}
	}
	else {
		if(extension_loaded('ffmpeg') && file_exists($_GET['filename'])) {
			$mov = new ffmpeg_movie($_GET['filename']);
			$frame = $mov->getFrame(10);
			if($frame)
				$gd_image = $frame->toGDImage();
		}
	}		
	if($gd_image) {
		$width = imagesx($gd_image);
		$height = imagesy($gd_image);
		if($width / $height > _THUMB_WIDTH / _THUMB_HEIGHT) {
			$new_height = _THUMB_HEIGHT;
			$new_width = _THUMB_HEIGHT * ($width / $height);
		}
		else {
			$new_width = _THUMB_WIDTH;
			$new_height = _THUMB_WIDTH / ($width / $height);
		}
		$img = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($img, $gd_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		$img2 = imagecreatetruecolor(_THUMB_WIDTH, _THUMB_HEIGHT);
		imagecopyresampled($img2, $img, 0, 0, intval(($new_width - _THUMB_WIDTH) / 2), intval(($new_height - _THUMB_HEIGHT) / 2), _THUMB_WIDTH, _THUMB_HEIGHT, _THUMB_WIDTH, _THUMB_HEIGHT);
		
		$insert = imagecreatefrompng('img/video_mask.png');  
		$cut = imagecreatetruecolor(_THUMB_WIDTH, _THUMB_HEIGHT); 
		imagecopy($cut, $img2, 0, 0, 0, 0, _THUMB_WIDTH, _THUMB_HEIGHT); 			
		imagecopy($cut, $insert, 0, 0, 0, 0, _THUMB_WIDTH, _THUMB_HEIGHT); 			
		imagecopymerge($img2, $cut, 0, 0, 0, 0, _THUMB_WIDTH, _THUMB_HEIGHT, 70);
		
		//test vidéo privée
		if(!empty($_GET['external'])) {
			if(preg_match('/youtube\.com\/vi\/([\w-]{11})\/[0-9]\.jpg/', $_GET['filename'], $matches)) {
				if(!@file_get_contents("http://gdata.youtube.com/feeds/api/videos/{$matches[1]}?fields=title")) {
					$font = 'fonts/arial.ttf';
					imagettftext($img2, 30, 0, 3, 40, imagecolorallocate($img2, 255, 0, 0), $font, "PRIVÉE");
				}
			}
		}
	
		header("Content-type: image/jpeg");
		imagejpeg($img2, NULL, 80);
		imagedestroy($gd_image);
		imagedestroy($img);
		imagedestroy($img2);
		imagedestroy($insert);
		imagedestroy($cut);
		$success = 1;
	}		
}
if(empty($success)) {	
	$img = imagecreatefrompng('img/video_mask.png');
	imagealphablending($img, true);
	imagesavealpha($img, true);
	header("Content-type: image/png");
	imagepng($img);
	imagedestroy($img);
}
