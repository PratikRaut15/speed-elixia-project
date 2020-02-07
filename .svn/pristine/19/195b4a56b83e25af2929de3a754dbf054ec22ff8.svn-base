<?php
include 'driver_functions.php';
//print_r($_REQUEST);
//print_r($_FILES);
//print_r($_GET['files']);
$did = $_REQUEST['did'];
$data = array();

if(isset($_GET['driverfile1']))
{	
        echo $othername = $_REQUEST['filename'];
        echo $_SESSION['customerno'];
        $error = false;
	$files = array();
        $uploaddir = "../../customer/".$_SESSION['customerno']."/driver/".$did."/"; 
        $driverfolder = "../../customer/".$_SESSION['customerno']."/driver/"; 
        if(!file_exists($driverfolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/driver/", 0777);
        }
        $driveridfolder = "../../customer/".$_SESSION['customerno']."/driver/".$did."/"; 
        if(!file_exists($driveridfolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/driver/".$did, 0777);
        }
	foreach($_FILES as $file)
	{
           $filename = $uploaddir.basename($file['name']);
           $path_parts = pathinfo($filename);
           $ext = $path_parts['extension'];
            if($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png" || $ext == "txt")
            {
                if(move_uploaded_file($file['tmp_name'], $uploaddir.$othername.'.'.$ext))
                {
                        $files[] = $uploaddir.$file['name'];
                }
                else
                {
                    $error = true;
                }
            }
	}
	$data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
}
else
{
	$data = array('success' => 'Form was submitted', 'formData' => $_POST);
}
echo json_encode($data);
?>
