<?php
function storefile($var, $location, $filename=NULL, $maxfilesize=NULL) {
   $ok = false;

   // Check file
   $mime = $_FILES[$var]["type"];
   if($mime=="image/jpeg" || $mime=="image/pjpeg") {
     // Mime type is correct
     // Check extension
     $name  = $_FILES[$var]["name"];
     $array = explode(".", $name);
     $nr    = count($array);
     $ext  = strtolower($array[$nr-1]);
     if($ext=="jpg" || $ext=="jpeg") {
       $ok = true;
     }
   }
  
   if(isset($maxfilesize)) {
     if($_FILES[$var]["size"] > $maxfilesize) {
       $ok = false;
     }
   }
  
   if($ok==true) {
     $tempname = $_FILES[$var]['tmp_name'];
     if(isset($filename)) {
       $uploadpath = $location.$filename;
     } else {
       $uploadpath = $location.$_FILES[$var]['name'];
     }
     if(is_uploaded_file($_FILES[$var]['tmp_name'])) { 
       while(move_uploaded_file($tempname, $uploadpath)) {
         // Wait for the script to finish its upload   
       }
     }
     return true;
   } else {
     return false;
   }
  }


function thumbnail($source_image,$thumb_path,$image_name,$thumb_width)
{
    $src_img = imagecreatefromjpeg("$source_image");
    $origw=imagesx($src_img);
    $origh=imagesy($src_img);

    if($origw != $thumb_width)
    {
        $new_w=$thumb_width;
        $new_h=$thumb_width;

        if ($new_w && ($origw < $origh)) {
            $new_w = ($new_h / $origh) * $origw;
        } else {
            $new_h = ($new_w / $origw) * $origh;
        }

        $dst_img = imagecreatetruecolor ($new_w,$new_h);

        imagecopyresampled  ($dst_img,$src_img,0,0,0,0,$new_w,$new_h,imagesx($src_img),imagesy($src_img));
    }
    else
    {
        $dst_img = $src_img; // Same size, don't resample.
    }

    imagejpeg($dst_img, "$thumb_path$image_name");
    return true;
}
?>