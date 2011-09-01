<?php

/*
 * Code from CambioCMS project
 * @link http://code.google.com/p/cambiocms/source/browse/trunk/cms/includes/602_html.image.php
 */ 

class Ajde_Image extends Ajde_Object_Standard
{
	protected $_source;
	protected $_type;
	protected $_image;
	
	public function __construct($file)
	{
		$this->_source = $file;
		$this->_type = $this->fileExtension($file);
		// TODO: only get resource when needed??
		$this->_image = $this->getImageResource();
	}
	
	public function getImage()
	{
		ob_start();
		switch ($this->_type) {
			case "jpg": 
				imagejpeg($this->_image);
				break;
			case "png":
				imagepng($this->_image);
				break;
			case "gif":
				imagegif($this->_image);
				break;
		}
		$image = ob_get_contents();
		ob_end_clean();
		return $image;
	}
	
	// public function __sleep()
	// {
		// return array();
	// }

	public function __wakeup()
	{
		$this->_image = $this->getImageResource();
	}
	
	public function getImageResource()
	{
		switch ($this->_type) {
			case "jpg": 
				return imagecreatefromjpeg($this->_source);
				break;
			case "png":
				return imagecreatefrompng($this->_source);
				break;
			case "gif":
				return imagecreatefromgif($this->_source);
				break;
		}
	}
	
	public function resize($dim, $w_or_h) {
		
		$old_x=imageSX($this->_image);
		$old_y=imageSY($this->_image);
			
		if ($w_or_h = "w") {
			$thumb_w=$new_w;
			$thumb_h=$old_y*($new_w/$old_x);
		}
		if ($w_or_h = "h") {
			$thumb_w=$old_x*($new_h/$old_y);
			$thumb_h=$new_h;
		}
		
		$newimage = ImageCreateTrueColor($thumb_w,$thumb_h);
		
		$this->fastimagecopyresampled($newimage,$this->_image,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y,5);
		
		$this->_image = $newimage;		
	}
	
	public function crop($height, $width) {
		
		// no x or y correction
		$x_o = 0; //intval($_GET["x"]);
		$y_o = 0; //intval($_GET["y"]);
		
		$newimage=ImageCreateTrueColor($width,$height);
		
		$old_x=imageSX($this->_image);
		$old_y=imageSY($this->_image);
				
		$thumb_w=$width;
		$thumb_h=intval($old_y*($width/$old_x));
		
		$x_offset = 0;
		$y_offset = 0;
		
		if ($thumb_h < $height) {
			$thumb_h=$height;
			$thumb_w=intval($old_x*($height/$old_y));
				
			// hoogte kleiner dan breedte
			$x_offset = intval(($thumb_w - $width) / 2);
			//$x_offset = $x_offset * 2;
						
		} else {
			
			// hoogte groter
			$y_offset = ($thumb_h - $height) / 2;
			//$y_offset = $y_offset * 2;
			
		}
		
		$x_offset = $x_offset + $x_o;
		$y_offset = $y_offset + $y_o;
		
		$this->fastimagecopyresampled($newimage,$this->_image,-$x_offset,-$y_offset,0,0,$thumb_w,$thumb_h,$old_x,$old_y,5);
		
		$this->_image = $newimage;
	}
	
	// public function save($target) {
		// switch ($this->_type) {
			// case "jpg": 
				// imagejpeg($this->_image,$target);
				// break;
			// case "png":
				// imagepng($this->_image,$target);
				// break;
			// case "gif":
				// imagegif($this->_image,$target);
				// break;
		// }
	// }
	
	public function getMimeType()
	{
		switch ($this->_type) {
			case "jpg": 
				return "image/jpeg";
				break;
			case "png":
				return "image/png";
				break;
			case "gif":
				return "image/gif";
				break;
		}
	}
	
	public function destroy() {
		imagedestroy($this->_image); 
	}
	
	protected function fileExtension($filename) {
	    $path_info = pathinfo($filename);
	    return strtolower($path_info['extension']);
	}
	
	protected function fastimagecopyresampled (&$dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h, $quality = 3) {
		// Plug-and-Play fastimagecopyresampled function replaces much slower imagecopyresampled.
		// Just include this function and change all "imagecopyresampled" references to "fastimagecopyresampled".
		// Typically from 30 to 60 times faster when reducing high resolution images down to thumbnail size using the default quality setting.
		// Author: Tim Eckel - Date: 09/07/07 - Version: 1.1 - Project: FreeRingers.net - Freely distributable - These comments must remain.
		//
		// Optional "quality" parameter (defaults is 3). Fractional values are allowed, for example 1.5. Must be greater than zero.
		// Between 0 and 1 = Fast, but mosaic results, closer to 0 increases the mosaic effect.
		// 1 = Up to 350 times faster. Poor results, looks very similar to imagecopyresized.
		// 2 = Up to 95 times faster.  Images appear a little sharp, some prefer this over a quality of 3.
		// 3 = Up to 60 times faster.  Will give high quality smooth results very close to imagecopyresampled, just faster.
		// 4 = Up to 25 times faster.  Almost identical to imagecopyresampled for most images.
		// 5 = No speedup. Just uses imagecopyresampled, no advantage over imagecopyresampled.
		
		if (empty($src_image) || empty($dst_image) || $quality <= 0) { return false; }
		if ($quality < 5 && (($dst_w * $quality) < $src_w || ($dst_h * $quality) < $src_h)) {
		$temp = imagecreatetruecolor ($dst_w * $quality + 1, $dst_h * $quality + 1);
		imagecopyresized ($temp, $src_image, 0, 0, $src_x, $src_y, $dst_w * $quality + 1, $dst_h * $quality + 1, $src_w, $src_h);
		imagecopyresampled ($dst_image, $temp, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $dst_w * $quality, $dst_h * $quality);
		imagedestroy ($temp);
		} else imagecopyresampled ($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
		return true;
	}
	
}