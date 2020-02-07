<?php


if(isset($_FILES))
{
$docketid = $_POST['docketid'];
$customerno = $_POST['customerno'];
$ticketid = $_POST['ticketids'];
$ticketIdArray=explode(',',$ticketid);
print_r($ticketIdArray);
        $error = false;
        $files = array();
        $customerfolder = "../../customer/".$customerno."/";
      	$uploaddir = "../../customer/".$customerno."/support/";
        $supportfolder = "../../customer/".$customerno."/support/";
        $customers = "../../customer/";

        if(!file_exists($customers)){
        	mkdir("../../customer/".$customerno."/", 0777);
        }
        if(!file_exists($customerfolder)){
        	mkdir("../../customer/".$customerno."/", 0777);
        	
        }
        if (!file_exists($supportfolder)) {
            mkdir("../../customer/".$customerno."/support/", 0777);
        }
        $i=0;
        foreach ($_FILES as $file) {
        	
            $filename = $uploaddir . basename($file['name']);
            $path_parts = pathinfo($filename);
           	$new_filename = $path_parts['filename'];
            $ext = $path_parts['extension'];
  

            if ($ext == "zip" || $ext == "rar") {
                if (move_uploaded_file($file['tmp_name'], $uploaddir."D00".$docketid."-".$ticketIdArray[$i].".".$ext)) {
       
                    $files[] = $uploaddir . $file['name'];
                } else {
                    $error = true;
                }
            }
            $i++;
        }
        $data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
}
?>