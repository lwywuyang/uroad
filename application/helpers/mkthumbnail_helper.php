<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('mkthumbnail')) {

	function mkthumbnail($src, $width = null, $height = null, $filename = null) {  
		    if (!isset($width) && !isset($height))  
		        return false;  
		    if (isset($width) && $width <= 0)  
		        return false;  
		    if (isset($height) && $height <= 0)  
		        return false;  
		  
		    $size = getimagesize($src);  
		    if (!$size)  
		        return false;  
		  
		    list($src_w, $src_h, $src_type) = $size;  
		    $src_mime = $size['mime'];  
		    switch($src_type) {  
		        case 1 :  
		            $img_type = 'gif';  
		            break;  
		        case 2 :  
		            $img_type = 'jpeg';  
		            break;  
		        case 3 :  
		            $img_type = 'png';  
		            break;  
		        case 15 :  
		            $img_type = 'wbmp';  
		            break;  
		        default :  
		            return false;  
		    }  
		  
		    if (!isset($width))  
		        $width = $src_w * ($height / $src_h);   	
		    if (!isset($height))  
		        $height = $src_h * ($width / $src_w);  
		  
		    $imagecreatefunc = 'imagecreatefrom' . $img_type;  
		    $src_img = $imagecreatefunc($src);  
		    $dest_img = imagecreatetruecolor($width, $height);  
		    imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $width, $height, $src_w, $src_h);  
		  
		    $imagefunc = 'image' . $img_type;  
		    if ($filename) {  
		        $imagefunc($dest_img, $filename);  
		    } else {  
		        header('Content-Type: ' . $src_mime);  
		        $imagefunc($dest_img);  
		    }  
		    imagedestroy($src_img);  
		    imagedestroy($dest_img);  
		    return true;  
		}  



		/** 
    * desription 压缩图片 
    * @param sting $imgsrc 图片路径 
    * @param string $imgdst 压缩后保存路径 
    */
     function get_small_image($imgsrc,$imgdst){ 
        list($width,$height,$type)=getimagesize($imgsrc);
        $new_width = ($width>600?600:$width)*0.9; 
        $new_height =($height>600?600:$height)*0.9; 
        switch($type){ 
            case 1: 
                $giftype= $this->check_gifcartoon($imgsrc); 
                if($giftype){ 
                  header('Content-Type:image/gif'); 
                  $image_wp=imagecreatetruecolor($new_width, $new_height); 
                  $image = imagecreatefromgif($imgsrc); 
                  imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height); 
                  imagejpeg($image_wp, $imgdst,75); 
                  imagedestroy($image_wp); 
                } 
                break; 
            case 2: 
                header('Content-Type:image/jpeg'); 
                $image_wp=imagecreatetruecolor($new_width, $new_height); 
                $image = imagecreatefromjpeg($imgsrc); 
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height); 
                imagejpeg($image_wp, $imgdst,75); 
                imagedestroy($image_wp); 
                break; 
            case 3: 
                header('Content-Type:image/png'); 
                $image_wp=imagecreatetruecolor($new_width, $new_height); 
                $image = imagecreatefrompng($imgsrc); 
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height); 
                imagejpeg($image_wp, $imgdst,75); 
                imagedestroy($image_wp); 
                break; 
        } 
    } 
    
    /** 
    * desription 判断是否gif动画 
    * @param sting $image_file 图片路径
    * @return boolean t 是 f 否 
    */
     function check_gifcartoon($image_file){ 
        $fp = fopen($image_file,'rb'); 
        $image_head = fread($fp,1024); 
        fclose($fp); 
        return preg_match("/".chr(0x21).chr(0xff).chr(0x0b).'NETSCAPE2.0'."/",$image_head)?false:true; 
    }

}


