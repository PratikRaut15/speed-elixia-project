<?php
include 'dealer_functions.php';
//print_r($_REQUEST);
//print_r($_FILES);
//print_r($_GET['files']);
$dealerid = $_REQUEST['dealerid'];

$data = array();

if(isset($_GET['dealerfile1']))
{	
        echo$othername = $_REQUEST['filename'];
        echo $_SESSION['customerno'];
	$error = false;
	$files = array();
        $uploaddir = "../../customer/".$_SESSION['customerno']."/dealer/".$dealerid."/"; 
        $dealerfolder = "../../customer/".$_SESSION['customerno']."/dealer/"; 
        if(!file_exists($dealerfolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/dealer/", 0777);
        }
        $dealeridfolder = "../../customer/".$_SESSION['customerno']."/dealer/".$dealerid."/"; 
        if(!file_exists($dealeridfolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/dealer/".$dealerid, 0777);
        }
	foreach($_FILES as $file)
	{
           $filename = $uploaddir .basename($file['name']);
           $path_parts = pathinfo($filename);
            $ext = $path_parts['extension'];
            if($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png" || $ext == "txt")
            {
                if(move_uploaded_file($file['tmp_name'], $uploaddir.$othername.'.'.$ext))
                {
                        $files[] = $uploaddir .$file['name'];
                }
                else
                {
                    $error = true;
                }
            }
	}
	$data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
}
else if(isset($_GET['dealerfile2']))
{	
        echo$othername = $_REQUEST['filename2'];
        echo $_SESSION['customerno'];
	$error = false;
	$files = array();
        $uploaddir = "../../customer/".$_SESSION['customerno']."/dealer/".$dealerid."/"; 
        $dealerfolder = "../../customer/".$_SESSION['customerno']."/dealer/"; 
        if(!file_exists($dealerfolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/dealer/", 0777);
        }
        $dealeridfolder = "../../customer/".$_SESSION['customerno']."/dealer/".$dealerid."/"; 
        if(!file_exists($dealeridfolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/dealer/".$dealerid, 0777);
        }
	foreach($_FILES as $file)
	{
           $filename = $uploaddir .basename($file['name']);
           $path_parts = pathinfo($filename);
            $ext = $path_parts['extension'];
            if($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png" || $ext == "txt")
            {
                if(move_uploaded_file($file['tmp_name'], $uploaddir.$othername.'.'.$ext))
                {
                        $files[] = $uploaddir .$file['name'];
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

//echo json_encode($data);

?>
