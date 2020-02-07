<?php
include 'vehicle_functions.php';
//print_r($_REQUEST);
//print_r($_FILES);
//print_r($_GET['files']);
$vehicleid = $_REQUEST['vehicleid'];
$data = array();

if(isset($_GET['puc']))
{	$pucname = $_GET['pucname'];
        $othername ="PUC_".date("Y-m-d");
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
            $filearr = explode(".",$file['name']);
            $ext = $filearr[1];
            if($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png")
            {
                if(move_uploaded_file($file['tmp_name'], $uploaddir.$othername.'.'.$ext))
                {
                    //$files[] = $uploaddir .$file['name'];
                    $files[] = $uploaddir .$othername.'.'.$ext;
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
else if(isset($_GET['speedgov']))
{	$speedname = $_GET['speedname'];
        $othername ="speedgov_".date("Y-m-d");
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
            $filearr = explode(".",$file['name']);
            $ext = $filearr[1];
            if($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png")
            {
                if(move_uploaded_file($file['tmp_name'], $uploaddir.$othername.'.'.$ext))
                {
                    //$files[] = $uploaddir .$file['name'];
                    $files[] = $uploaddir .$othername.'.'.$ext;
                    send_incomplete_approval($vehicleid);
                }
                else
                {
                    $error = true;
                }
            }
	}
	$data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
}else if(isset($_GET['fireext']))
{	$fireextname = $_GET['fireextname'];
        $othername ="fireext_".date("Y-m-d");
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
            $filearr = explode(".",$file['name']);
            $ext = $filearr[1];
            if($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png")
            {
                if(move_uploaded_file($file['tmp_name'], $uploaddir.$othername.'.'.$ext))
                {
                    //$files[] = $uploaddir .$file['name'];
                    $files[] = $uploaddir .$othername.'.'.$ext;
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
else if(isset($_GET['reg']))
{	$regname ="registration_".date("Y-m-d");
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
            $filearr = explode(".",$file['name']);
            $ext = $filearr[1];
            if($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png")
            {
                if(move_uploaded_file($file['tmp_name'], $uploaddir.$regname.'.'.$ext))
                {
                    //$files[] = $uploaddir .$file['name'];
                    $files[] = $uploaddir .$regname.'.'.$ext;
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
else if(isset($_GET['ins']))
{	
        $insname ="insurance_".date("Y-m-d");
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
            $filearr = explode(".",$file['name']);
            $ext = $filearr[1];
            
            if($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png")
            {
                if(move_uploaded_file($file['tmp_name'],$uploaddir.$insname.'.'.$ext))
                {
                    $files[] = $uploaddir .$insname.".".$ext;
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
}
else if(isset($_GET['other2']))
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
}else if(isset($_GET['other3']))
{	
        $othername3 = $_REQUEST['othername3'];
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
        $filename = $uploaddir .basename($file['name']);
        $path_parts = pathinfo($filename); 
        $ext = $path_parts['extension'];
            if($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png")
            {
                if(move_uploaded_file($file['tmp_name'], $uploaddir.$othername3.'.'.$ext))
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
}else if(isset($_GET['other4']))
{	
        $othername4 = $_REQUEST['othername4'];
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
        $filename = $uploaddir .basename($file['name']);
        $path_parts = pathinfo($filename); 
        $ext = $path_parts['extension'];
            if($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png")
            {
                if(move_uploaded_file($file['tmp_name'], $uploaddir.$othername4.'.'.$ext))
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
else if(isset($_GET['other5']))
{	
        $othername5 = $_REQUEST['othername5'];
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
        $filename = $uploaddir .basename($file['name']);
        $path_parts = pathinfo($filename); 
        $ext = $path_parts['extension'];
            if($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png")
            {
                if(move_uploaded_file($file['tmp_name'], $uploaddir.$othername5.'.'.$ext))
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
