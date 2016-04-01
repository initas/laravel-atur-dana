<?php

namespace App\Http\Controllers\API;

use App\Libraries\Embed;

class EmbedController extends MainController
{
	#EMBED
	public function getOriginal($file_name)
	{
		Embed::original($file_name);
	}
	public function getCropped($w, $h, $file_name)
	{
		Embed::cropImage($w, $h, $file_name);
	}
	public function getCroppedXY($x, $y, $w, $h, $file_name)
	{
		Embed::cropImageXY($x, $y, $w, $h, $file_name);
	}
	public function getThumb($size, $file_name)
	{
		Embed::cropImage($size, $size, $file_name);
	}
	public function getByHeight($newsize, $file_name)
	{
		Embed::resizeImage($newsize, $file_name, true);
	}
	public function getByWidth($newsize, $file_name)
	{
		Embed::resizeImage($newsize, $file_name);
	}
}