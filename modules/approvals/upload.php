<?php
include '../vehicle/vehicle_functions.php';
//print_r($_REQUEST);
//print_r($_FILES);
//print_r($_GET['files']);
$vehicleid = $_REQUEST['vehicleid'];
$data = array();

if(isset($_GET['puc']))
{	
	$error = false;
	$files = array();
        $uploaddir = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        $vehiclefolder = "../../customer/".$_SESSION['customerno']."/vehicleid/"; 
        if(!file_exists($vehiclefolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/", 0777);
        }
        $vehicleidfolder = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        if(!file_exists($vehicleidfolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid, 0777);
        }
	foreach($_FILES as $file)
	{
            //echo 'AAAA'.$file['type'].'AAAAA';
        if (($file["type"] == "image/gif") || ($file["type"] == "image/jpeg") || ($file["type"] == "image/jpg")
          || ($file["type"] == "image/pjpeg") || ($file["type"] == "image/x-png") || ($file["type"] == "image/png") || ($file["type"] == "application/pdf")){
        $filename = $uploaddir .basename($file['name']);
//        echo '<br>'.$uploaddir .$file['name'];
//        echo '<br>'.$file['tmp_name'];
            //echo $info = pathinfo($file['name']);
            $path_parts = pathinfo($filename);
            $ext = $path_parts['extension'];
            if(move_uploaded_file($file['tmp_name'], $uploaddir.'puc.'.$ext))
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
else if(isset($_GET['reg']))
{	
	$error = false;
	$files = array();
        $uploaddir = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        $vehiclefolder = "../../customer/".$_SESSION['customerno']."/vehicleid/"; 
        if(!file_exists($vehiclefolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/", 0777);
        }
        $vehicleidfolder = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        if(!file_exists($vehicleidfolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid, 0777);
        }
	foreach($_FILES as $file)
	{
            //echo 'AAAA'.$file['type'].'AAAAA';
        if (($file["type"] == "image/gif") || ($file["type"] == "image/jpeg") || ($file["type"] == "image/jpg")
          || ($file["type"] == "image/pjpeg") || ($file["type"] == "image/x-png") || ($file["type"] == "image/png") || ($file["type"] == "application/pdf")){
        $filename = $uploaddir .basename($file['name']);
//        echo '<br>'.$uploaddir .$file['name'];
//        echo '<br>'.$file['tmp_name'];
            //echo $info = pathinfo($file['name']);
            $path_parts = pathinfo($filename);
            $ext = $path_parts['extension'];
            if(move_uploaded_file($file['tmp_name'], $uploaddir.'registration.'.$ext))
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
else if(isset($_GET['ins']))
{	
	$error = false;
	$files = array();
        $uploaddir = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        $vehiclefolder = "../../customer/".$_SESSION['customerno']."/vehicleid/"; 
        if(!file_exists($vehiclefolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/", 0777);
        }
        $vehicleidfolder = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        if(!file_exists($vehicleidfolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid, 0777);
        }
	foreach($_FILES as $file)
	{
            //echo 'AAAA'.$file['type'].'AAAAA';
        if (($file["type"] == "image/gif") || ($file["type"] == "image/jpeg") || ($file["type"] == "image/jpg")
          || ($file["type"] == "image/pjpeg") || ($file["type"] == "image/x-png") || ($file["type"] == "image/png") || ($file["type"] == "application/pdf")){
        $filename = $uploaddir .basename($file['name']);
//        echo '<br>'.$uploaddir .$file['name'];
//        echo '<br>'.$file['tmp_name'];
            //echo $info = pathinfo($file['name']);
            $path_parts = pathinfo($filename);
            $ext = $path_parts['extension'];
            if(move_uploaded_file($file['tmp_name'], $uploaddir.'insurance.'.$ext))
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
else if(isset($_GET['other']))
{	
        $othername = $_REQUEST['othername'];
	$error = false;
	$files = array();
        $uploaddir = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        $vehiclefolder = "../../customer/".$_SESSION['customerno']."/vehicleid/"; 
        if(!file_exists($vehiclefolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/", 0777);
        }
        $vehicleidfolder = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        if(!file_exists($vehicleidfolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid, 0777);
        }
	foreach($_FILES as $file)
	{
            //echo 'AAAA'.$file['type'].'AAAAA';
        if (($file["type"] == "image/gif") || ($file["type"] == "image/jpeg") || ($file["type"] == "image/jpg")
          || ($file["type"] == "image/pjpeg") || ($file["type"] == "image/x-png") || ($file["type"] == "image/png") || ($file["type"] == "application/pdf")){
        $filename = $uploaddir .basename($file['name']);
//        echo '<br>'.$uploaddir .$file['name'];
//        echo '<br>'.$file['tmp_name'];
            //echo $info = pathinfo($file['name']);
            $path_parts = pathinfo($filename);
            $ext = $path_parts['extension'];
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
else if(isset($_GET['other1']))
{	
        $othername1 = $_REQUEST['othername1'];
	$error = false;
	$files = array();
        $uploaddir = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        $vehiclefolder = "../../customer/".$_SESSION['customerno']."/vehicleid/"; 
        if(!file_exists($vehiclefolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/", 0777);
        }
        $vehicleidfolder = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        if(!file_exists($vehicleidfolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid, 0777);
        }
	foreach($_FILES as $file)
	{
            //echo 'AAAA'.$file['type'].'AAAAA';
        if (($file["type"] == "image/gif") || ($file["type"] == "image/jpeg") || ($file["type"] == "image/jpg")
          || ($file["type"] == "image/pjpeg") || ($file["type"] == "image/x-png") || ($file["type"] == "image/png") || ($file["type"] == "application/pdf")){
        $filename = $uploaddir .basename($file['name']);
//        echo '<br>'.$uploaddir .$file['name'];
//        echo '<br>'.$file['tmp_name'];
            //echo $info = pathinfo($file['name']);
            $path_parts = pathinfo($filename);
            $ext = $path_parts['extension'];
            if(move_uploaded_file($file['tmp_name'], $uploaddir.$othername1.'.'.$ext))
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
}else if(isset($_GET['other2']))
{	
        $othername2 = $_REQUEST['othername2'];
	$error = false;
	$files = array();
        $uploaddir = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        $vehiclefolder = "../../customer/".$_SESSION['customerno']."/vehicleid/"; 
        if(!file_exists($vehiclefolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/", 0777);
        }
        $vehicleidfolder = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        if(!file_exists($vehicleidfolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid, 0777);
        }
	foreach($_FILES as $file)
	{
            //echo 'AAAA'.$file['type'].'AAAAA';
        if (($file["type"] == "image/gif") || ($file["type"] == "image/jpeg") || ($file["type"] == "image/jpg")
          || ($file["type"] == "image/pjpeg") || ($file["type"] == "image/x-png") || ($file["type"] == "image/png") || ($file["type"] == "application/pdf")){
        $filename = $uploaddir .basename($file['name']);
//        echo '<br>'.$uploaddir .$file['name'];
//        echo '<br>'.$file['tmp_name'];
            //echo $info = pathinfo($file['name']);
            $path_parts = pathinfo($filename);
            $ext = $path_parts['extension'];
            if(move_uploaded_file($file['tmp_name'], $uploaddir.$othername2.'.'.$ext))
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
else if(isset($_GET['quote']))
{	
	$error = false;
	$files = array();
        $uploaddir = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        $vehiclefolder = "../../customer/".$_SESSION['customerno']."/vehicleid/"; 
        if(!file_exists($vehiclefolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/", 0777);
        }
        $vehicleidfolder = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        if(!file_exists($vehicleidfolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid, 0777);
        }
	foreach($_FILES as $file)
	{
	
//        if (($file["type"] == "image/gif") || ($file["type"] == "image/jpeg") || ($file["type"] == "image/jpg")
//          || ($file["type"] == "image/pjpeg") || ($file["type"] == "image/x-png") || ($file["type"] == "image/png") || ($file["type"] == "application/pdf" || ($file["type"] == "binary/octet-stream"))){
        $filename = $uploaddir .basename($file['name']);
            $path_parts = pathinfo($filename);
            $ext = $path_parts['extension'];
            if($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png")
            {
                if(move_uploaded_file($file['tmp_name'], $uploaddir.'_quote.'.$ext))
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
else if(isset($_GET['acc']))
{	
        $maintenanceid = $_REQUEST['maintenanceid'];
	$error = false;
	$files = array();
        $uploaddir = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        $vehiclefolder = "../../customer/".$_SESSION['customerno']."/vehicleid/"; 
        if(!file_exists($vehiclefolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/", 0777);
        }
        $vehicleidfolder = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        if(!file_exists($vehicleidfolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid, 0777);
        }
	foreach($_FILES as $file)
	{
	
//        if (($file["type"] == "image/gif") || ($file["type"] == "image/jpeg") || ($file["type"] == "image/jpg")
//          || ($file["type"] == "image/pjpeg") || ($file["type"] == "image/x-png") || ($file["type"] == "image/png") || ($file["type"] == "application/pdf" || ($file["type"] == "binary/octet-stream"))){
        $filename = $uploaddir .basename($file['name']);
            $path_parts = pathinfo($filename);
            $ext = $path_parts['extension'];
            if($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png")
            {
                if(move_uploaded_file($file['tmp_name'], $uploaddir.$_GET['inp_id'].'_acc.'.$ext))
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
else if(isset($_GET['invoice']))
{	
	$error = false;
	$files = array();
        $uploaddir = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        $vehiclefolder = "../../customer/".$_SESSION['customerno']."/vehicleid/"; 
        if(!file_exists($vehiclefolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/", 0777);
        }
        $vehicleidfolder = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        if(!file_exists($vehicleidfolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid, 0777);
        }
	foreach($_FILES as $file)
	{
            //echo 'AAAA'.$file['type'].'AAAAA';
//        if (($file["type"] == "image/gif") || ($file["type"] == "image/jpeg") || ($file["type"] == "image/jpg")
 //         || ($file["type"] == "image/pjpeg") || ($file["type"] == "image/x-png") || ($file["type"] == "image/png") || ($file["type"] == "application/pdf")){
        $filename = $uploaddir .basename($file['name']);
//        echo '<br>'.$uploaddir .$file['name'];
//        echo '<br>'.$file['tmp_name'];
            //echo $info = pathinfo($file['name']);
            $path_parts = pathinfo($filename);
            $ext = $path_parts['extension'];
            if($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png")
            {
                if(move_uploaded_file($file['tmp_name'], $uploaddir.'_invoice.'.$ext))
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
else if(isset($_GET['acc_file']))
{	
	$error = false;
	$files = array();
        $uploaddir = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        $vehiclefolder = "../../customer/".$_SESSION['customerno']."/vehicleid/"; 
        if(!file_exists($vehiclefolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/", 0777);
        }
        $vehicleidfolder = "../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid."/"; 
        if(!file_exists($vehicleidfolder)){
                                    mkdir("../../customer/".$_SESSION['customerno']."/vehicleid/".$vehicleid, 0777);
        }
	foreach($_FILES as $file)
	{
            //echo 'AAAA'.$file['type'].'AAAAA';
//        if (($file["type"] == "image/gif") || ($file["type"] == "image/jpeg") || ($file["type"] == "image/jpg")
 //         || ($file["type"] == "image/pjpeg") || ($file["type"] == "image/x-png") || ($file["type"] == "image/png") || ($file["type"] == "application/pdf")){
        $filename = $uploaddir .basename($file['name']);
//        echo '<br>'.$uploaddir .$file['name'];
//        echo '<br>'.$file['tmp_name'];
            //echo $info = pathinfo($file['name']);
            $path_parts = pathinfo($filename);
            $ext = $path_parts['extension'];
            if($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png")
            {
                if(move_uploaded_file($file['tmp_name'], $uploaddir.$_GET['acc_file'].'.'.$ext))
                {
                        $files[] = $uploaddir .$file['name'];
                        send_incomplete_approval($vehicleid);
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