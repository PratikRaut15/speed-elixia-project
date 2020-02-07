<?php
include 'sales_function.php';

$skuid = isset($_REQUEST['skuid'])?$_REQUEST['skuid']:"0";
$data = array();
if(isset($_GET['skuimage'])){
        $error = false;
	$files = array();
        $skuid = $_GET['skuid'];
        $uploaddir = "../../customer/".$_SESSION['customerno']."/skuphotos/"; 
        $skufolder = "../../customer/".$_SESSION['customerno']."/skuphotos/"; 
        if(!file_exists($skufolder)){
            mkdir("../../customer/".$_SESSION['customerno']."/skuphotos/", 0777);
        }
       
	foreach($_FILES as $file)
	{
            $filename1 = str_replace(' ', '', $file['name']);
            $filename = $uploaddir .basename($filename1);
            $path_parts = pathinfo($filename);
            $ext = $path_parts['extension'];
            if($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png")
            {
                if(move_uploaded_file($file['tmp_name'], $filename))
                {
                        $files[] = $filename;
                }
                else
                {
                    $error = true;
                }
            }
	}
	$data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
        skuimagefilenameupdate($filename1,$skuid);
        
}
else
{
	$data = array('success' => 'Form was submitted', 'formData' => $_POST);
}
echo json_encode($data);



?>
