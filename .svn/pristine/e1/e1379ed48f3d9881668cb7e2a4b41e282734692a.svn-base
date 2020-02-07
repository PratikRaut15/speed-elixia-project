<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/system/utilities.php");
include_once("../../lib/bo/TeamManager.php");



if (isset($_POST['insert_email_data'])) {
	
	$tm = new TeamManager();
	$obj = new stdClass();
	


	$obj->title=$_POST['title'];
	$obj->email_body=$_POST['email_body'];
	$obj->email_subject=$_POST['email_subject'];
	$newsLetterId = $tm->insert_newsLetterContent($obj);
	$newsLetterId=$newsLetterId['newsLetterId'];
	if($newsLetterId>0){
		if(isset($_FILES) && $_POST['file']!=undefined){
			$error = false;
	        $files = array();
	        $customerfolder = "../../customer";
	        $newsLetterFolder = "../../customer/newsLetter/";
        	$title=$obj->title."_".$newsLetterId."/";

	        
        	if (!file_exists($customerfolder)) {
	            mkdir($customerfolder, 0777);
	        }

	        if (!file_exists($newsLetterFolder)) {
	            mkdir($newsLetterFolder, 0777);
	        }
	      	
	      	
	        mkdir($newsLetterFolder.$title, 0777);
	        
       

	        foreach ($_FILES as $file) {
	        	
	            $filename = basename($file['name']);
	            $filename = str_replace(" ","_", $filename);
	            $path_parts = pathinfo($filename);
	           	$new_filename = $path_parts['filename'];
	            $ext = $path_parts['extension'];
	  			
	            if ($ext == "pdf") {
	                if (move_uploaded_file($file['tmp_name'], $newsLetterFolder.$title.$filename)) {
	                	$fileObj = new stdClass();
	                	$fileObj->newsLetter_Id = $newsLetterId;
	                	$fileObj->file_path_newsLetter=$newsLetterFolder.$title;
	            		$fileObj->file_name = $filename;

	                	$filePath = $tm->update_newsLetterContent($fileObj);
	                	if($filePath>1){
	                		echo json_encode($newsLetterId);
	                	}
	                } 
	                else {
	                    $error = true;
	                }
	            }
	        }
	        $data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
		}
		else{
		echo json_encode($newsLetterId);
		}
	}
	else{
		echo json_encode($newsLetterId);
	}
}
if (isset($_REQUEST['get_ContentName'])) {
    $tm=new TeamManager();
    $arrResult=$tm->getEmailContentName($_REQUEST['term']);
    echo json_encode($arrResult);
}
if (isset($_POST['newsLetterContent'])) {
	$contentObj = new stdClass();
    $tm=new TeamManager();
    $contentObj->content_id=$_POST['content_id'];
    $arrResult=$tm->getnewsLetterContent($contentObj);
    $arrResult['email_body']=html_entity_decode($arrResult['email_body'],ENT_QUOTES, 'UTF-8');
    echo json_encode($arrResult);
}
if (isset($_POST['send_bulk_email'])) {
	
    $tm=new TeamManager();
    $email_subject = $_POST['email_subject'];
    $attachment_Link = $_POST['attachment_Link'];
    $file_name = $_POST['filename'];
    $emailBody = $_POST['emailBody'];
    $subject = $email_subject;
	$attachmentFilePath = $attachment_Link.$file_name;
	$attachmentFileName = $file_name;
 		if (!file_exists($attachmentFilePath)) {
 			$attachmentFilePath = "";
 			$attachmentFileName = "";
 		}	
 	
    $arrResult=$tm->getNewsLetter_Subs_Details();
    $emailSent = array();
    $i=0;
    foreach($arrResult as $subscribers){
		$arrToMailIds = array();
		$arrToMailIds[] = $subscribers['email_id'];

 		

        $body  = $emailBody;
        $body .= '<br/><br/><br/>Warm Regards,<br/>';
        $body .= 'Elixia Tech Solutions Ltd.<br/>';
        $body .= '<a href = "http://www.elixiatech.com" title = "elixiatech.com" target="_blank">www.elixiatech.com</a><br/>';
        $body .= '<br/><img src = "http://uat-speed.elixiatech.com/images/logo.png" alt = "Elixia Tech Solutions Ltd."  style="width:25%;height:10%;"/><br/>';
        $body .=  '<br/>To unsubscribe from these mailings, you may opt-out <a href="http://uat-speed.elixiatech.com/modules/api/elixiatech/index.php?action=unsubscribe_newsLetter&gu_ID='.$subscribers['guid'].'"\>here</a>';

        $isTemplatedMessage=1; //To change the footer of email
        $isElixiaTech = 1; //To change the sender name


        $emailSent[$i]=sendMailUtil($arrToMailIds, "", "", $subject, $body, $attachmentFilePath, $attachmentFileName,$isTemplatedMessage,$isElixiaTech);
        $i++;
      
    }
     print_r($emailSent);
}
?>