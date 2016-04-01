<?php

namespace App\Libraries;

class Embed{
	const FOLDER_PATH = 'uploaded';
	public static function original($file_name){
		$path = self::FOLDER_PATH.'/'.$file_name;
		$path = self::validatePath($path);
		
		$imageInfo = getimagesize($path);
		$mime = $imageInfo['mime'];
		
     	self::setHeader($file_name, $mime);
		echo file_get_contents($path);
		exit;
	}
	
	public static function resizeImage($newsize, $file_name, $byHeight=false){
		$path = self::FOLDER_PATH.'/'.$file_name;
		$path = self::validatePath($path);

		$path = isset($_GET['path']) ? $_GET['path'] : $path;
		
		$imageInfo = getimagesize($path);
		$mime = $imageInfo['mime'];
		$ratio = $imageInfo[0]/$imageInfo[1];
		
		if($byHeight){
			if($ratio > 1){
				$width = $newsize;
				$height = $newsize/$ratio;
			}else{
				$width = $newsize * $ratio;
				$height = $newsize;
			}
		}else{
			if($ratio > 1){
				$width = $newsize*$ratio;
				$height = $newsize;
			}else{
				$width = $newsize;
				$height = $newsize / $ratio;
			}
		}
		
		$resampled_image = imagecreatetruecolor($width,$height);
		if($mime == 'image/png'){
			imagealphablending( $resampled_image, false );
			imagesavealpha( $resampled_image, true );
		}
		
		$image = self::createImage($path, $mime);
		imagecopyresampled($resampled_image,$image,0,0,0,0,$width,$height,$imageInfo[0],$imageInfo[1]);
		
		$resampled_image = self::setOrientation($path, $mime, $resampled_image);
		
     	self::setHeader($file_name, $mime);
		self::imageOutput($resampled_image, $mime);
	}
	
	public static function cropImage($w, $h, $file_name){
		$path = self::FOLDER_PATH.'/'.$file_name;
		$path = self::validatePath($path);
		
		$path = isset($_GET['path']) ? $_GET['path'] : $path;
		
		$imageInfo = getimagesize($path);
		$width = $imageInfo[0];
		$height = $imageInfo[1];
		$mime = $imageInfo['mime'];
		
		$resampled_image = imagecreatetruecolor($w, $h);
		if($mime == 'image/png'){
			imagealphablending( $resampled_image, false );
			imagesavealpha( $resampled_image, true );
		}
		
		$src_img = self::createImage($path, $mime);
		 
		$width_new = $height * $w / $h;
		$height_new = $width * $h / $w;
		
		
		if($width_new > $width){
			$h_point = (($height - $height_new) / 2);
			imagecopyresampled($resampled_image, $src_img, 0, 0, 0, $h_point, $w, $h, $width, $height_new);
		}else{
			$w_point = (($width - $width_new) / 2);
			imagecopyresampled($resampled_image, $src_img, 0, 0, $w_point, 0, $w, $h, $width_new, $height);
		}
		
		$resampled_image = self::setOrientation($path, $mime, $resampled_image);
		
     	self::setHeader($file_name, $mime);
		self::imageOutput($resampled_image, $mime);
	}
	
	public static function cropImageXY($x, $y, $w, $h, $file_name){
		$path = 'img/image/'.$file_name;
		$imageInfo = getimagesize($path);
		$width = $imageInfo[0];
		$height = $imageInfo[1];
		$mime = $imageInfo['mime'];
		
		$dst_img = imagecreatetruecolor($w, $h);
		if($mime == 'image/png'){
			imagealphablending( $dst_img, false );
			imagesavealpha( $dst_img, true );
		}
		
		$src_img = self::createImage($path, $mime);
		 
		$width_new = $height * $w / $h;
		$height_new = $width * $h / $w;
		
		
		if($width_new > $width){
			$h_point = (($height - $height_new) / 2);
			imagecopyresampled($dst_img, $src_img, 0, 0, 0, $x, $w, $h, $width, $height_new);
		}else{
			$w_point = (($width - $width_new) / 2);
			imagecopyresampled($dst_img, $src_img, 0, 0, $x, $y, $w, $h, $width_new, $height);
		}
		
     	self::setHeader($file_name, $mime);
		self::imageOutput($dst_img, $mime);
	}
	
	#utillities
	private static function setOrientation($path, $mime, $resampled_image){
		if($mime=='image/jpeg'){
			$exif = exif_read_data($path);
			if (!empty($exif['Orientation'])){
				$orientation = $exif['Orientation'];
				switch($orientation) {
					case 3:
						$resampled_image = imagerotate($resampled_image, 180, 0);
					break;
					case 6:
						$resampled_image = imagerotate($resampled_image, -90, 0);
					break;
					case 8:
						$resampled_image = imagerotate($resampled_image, 90, 0);
					break;
				}
			}
		}
		return $resampled_image;
	}
	private static function validatePath($image_url){
		//dd($image_url);
		if(!file_exists($image_url)){
			$image_url = 'images/not-found.png';
		}

		return $image_url;
	}
	private static function createImage($path, $mime){
		if($mime == 'image/jpeg'){
			$image = imagecreatefromjpeg($path);
		}elseif($mime == 'image/png'){
			$image = imagecreatefrompng($path);
		}elseif($mime == 'image/gif'){
			$image = imagecreatefromgif($path);
		}else{
			dd($mime.' not supported');	
		}

		return $image;
	}
	private static function imageOutput($file, $mime){
		
		if($mime == 'image/jpeg'){
			imagejpeg($file, null, 75);
		}elseif($mime == 'image/png'){
			imagepng($file, null, 0);
		}elseif($mime == 'image/gif'){
			imagegif($file, null, 75);
		}
		imagedestroy($file);
		exit;
	}
	
	private static function setHeader($file_name, $mime){
		$etag = '"'. md5($file_name) .'"';
		if(isset($_SERVER['HTTP_IF_NONE_MATCH'])){
			if($_SERVER['HTTP_IF_NONE_MATCH'] == $etag){
				header('HTTP/1.1 304 Not Modified');
				header('Content-Length: 0');
				exit;
			}
		}
		$expiry = 604800; // (60*60*24*7)
		header('ETag: '. $etag);
		header('Last-Modified: '. gmdate('D, d M Y H:i:s', time()) .' GMT');
		header('Expires:'.        gmdate('D, d M Y H:i:s', time() + $expiry) .' GMT');
		header('Content-type: '.$mime);
	}
	
}