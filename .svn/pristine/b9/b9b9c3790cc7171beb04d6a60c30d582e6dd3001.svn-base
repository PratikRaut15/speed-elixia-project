<?php
header("Access-Control-Allow-Origin: *");
//file required
//error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'on');
set_time_limit(60);
$RELATIVE_PATH_DOTS = "../../../";
require_once "../../../lib/system/utilities.php";
require_once "../../../lib/autoload.php";
require_once "class/class.api.php";

//ob_start("ob_gzhandler");
//ojbect creation
$apiobj = new api();

//TODO: Need to replace all the Superglobals with the below correct usage:
//$data['vehicleno'] = filter_input(INPUT_REQUEST, 'vehicleno');
/*
It is not safe. Someone could do something like this: www.example.com?_SERVER['anything']
or if he has any kind of knowledge he could try to inject something into another variable
 */
extract($_REQUEST);


	if ($action == "insert_email_newsLetter") {
	    $arrResult['status'] = 0;
	    $arrResult['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;

	    $obj = new stdClass();

	    $obj->email_id = $_POST['email'];
	  	$obj->gu_id = NewGuid();
		
	    $arrResult = $apiobj->insert_email_for_subscription($obj);
	    
	    echo json_encode($arrResult);
	}

	if ($action == "unsubscribe_newsLetter") {

	    $arrResult['status'] = 0;
	    $arrResult['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;

	    $obj = new stdClass();

	  	$obj->gu_ID = $_GET['gu_ID'];

	    $arrResult = $apiobj->update_subscription($obj);
		if(isset($arrResult) && $arrResult['isUpdated']==1){
			 $message = '<html><body style="background-color:#e1e1e1;font-family:"Heebo", sans-serif;">';
			 $message .= '<link href="https://fonts.googleapis.com/css?family=Heebo:100,300,400,500,700,800,900" rel="stylesheet">';
			 $message .= "\n\n<img style='width:30%;height:10%;float:right;' src=\"../../../images/logo.png\">";
			 $message .='<h2>You have been unsubscribed from Elixia Tech News Letter</h2>';
			 $message.= '</html></body>';
			 echo $message;
		}
		else{
			$message = '<html><body style="background-color:#e1e1e1;">';
			$message .= "\n\n<img style='width:30%;height:10%;float:right;' src=\"../../../images/logo.png\">";
			$message .='<h2>Please Try Again</h2>';
			$message.= '</html></body>';
			echo $message;
		}
	   
	}

	if($action == "product_query"){
		$to = array();
		$to[] = $_POST['email'];;

		$subject = "Product Query Confirmation - Elixia Tech";

		$body  = 'Dear Patron,';
		$body .= '<br/><br/>Thank you for your interest in our solution(s).';
		$body .= '<br/><br/>This is a confirmation email. Your request has been forwarded to our Sales Team and someone shall be in touch with you very soon.';
		$body .= '<br/><br/>Meanwhile, you can also reach out to us at sales@elixiatech.com or call us on 022 25137470/71 in the interim.';
		$body .= '<br/><br/>We shall connect with you soon. Thank you once again.<br/>';
		$body .= '<br/><br/>Warm Regards,<br/>';
		$body .= 'Team Elixia Tech<br/>';
		$body .= '<a href = "http://www.elixiatech.com" title = "elixiatech.com" target  ="_blank">www.elixiatech.com</a><br/>';
		$body .= '<br/><a href="www.elixiatech.com"><img src = "http://uat-speed.elixiatech.com/images/logo.png" alt = "Elixia Tech Solutions Ltd."  style="width:25%;height:10%;"/></a><br/>';

		$isTemplatedMessage=1; //To change the footer of email
        $isElixiaTech = 1; //To change the sender name


		sendMailUtil($to, "", "", $subject, $body,"","",$isTemplatedMessage,$isElixiaTech);
	}

   	function NewGuid(){ 
   		$apiobj = new api();	
        $s = strtoupper(md5(uniqid(rand(),true)));
        $guidText =
        substr($s,0,8) . '-' .
        substr($s,8,4) . '-' .
        substr($s,12,4). '-' .
        substr($s,16,4). '-' .
        substr($s,20);

        $arrResult = $apiobj->check_duplicate_GUID($guidText);

        if($arrResult['isExist']==1){
        	 NewGuid();
        }
        else{
        	return $guidText;
        }
    }

    if($action=="blogs_preview"){
    	$arrResult = $apiobj->blogsPreview();
    	echo json_encode($arrResult);

	}
	if($action=="fetch_blog_details"){
		$obj = new stdClass();
		$obj->blogLink = $_POST['jsonreq'];
    	$arrResult = $apiobj->blogsDetails($obj);
    	echo json_encode($arrResult);
	}

	if($action == "fetch_elixia_careers"){ 
		$jsonRequest = json_decode($jsonreq);
		$obj = new stdClass();
		$obj->careerId = isset($jsonRequest->careerId)?$jsonRequest->careerId:0;
    	$arrResult = $apiobj->fetchElixiaCarrers($obj);
    	echo json_encode($arrResult);
	}	
	

	if($action == "insert_Career_Details"){ 
		$jsonRequest = json_decode($jsonreq);
		$resultStatus = 1;
		$obj = new stdClass();
		
		$obj->name           = $jsonRequest->name;
		$obj->email          = $jsonRequest->email; 
		$obj->phone          = $jsonRequest->phone;	 
		$obj->coverLetter    = $jsonRequest->coverLetter;
		$obj->careerId       = $jsonRequest->careerId; 
		$obj->dept           = $jsonRequest->dept;
		$obj->role       	 = $jsonRequest->role;
		$arrResult 			 = $apiobj->insertJobApplicantDetails($obj);
		
		if($arrResult>0){

			$customerfolder = $RELATIVE_PATH_DOTS."customer/";
	        $resumeFolder = $customerfolder."Elixia_JobApplications/";
	    	$deptFolder=$resumeFolder.$obj->dept."/";
	    	$roleFolder=$deptFolder.$obj->role."/";
	        
	    	if (!file_exists($customerfolder)) {
	            mkdir($customerfolder, 0777);
	        }

	        if (!file_exists($resumeFolder)) {
	            mkdir($resumeFolder, 0777);
	        }
	      	
	      	if (!file_exists($deptFolder)) {
	            mkdir($deptFolder, 0777);
	        }
	       
	    	if (!file_exists($roleFolder)) {
	            mkdir($roleFolder, 0777);
	        }

	        $finalFolder = $roleFolder.$arrResult."/";
	        mkdir($finalFolder, 0777);
	        	
	        $filename = basename($_FILES['file']['name']);
	        $filename = str_replace(" ","_", $filename);
	        $path_parts = pathinfo($filename);
	       	$ext=$path_parts['extension'];
	        $fileUploaded=move_uploaded_file($_FILES['file']['tmp_name'],$finalFolder.$filename);
	        
	        if($fileUploaded==1){
	        	$appObj = new stdClass();
	        	$appObj->jobAppId 	= $arrResult;
	        	$appObj->fileName 	= $filename;
	        	$appObj->filePath 	= str_replace("../../../","../../", $finalFolder);
	        	$updateResult 	 	= $apiobj->updateJobApplicantDetails($appObj);

	        	if($updateResult==1){
	        			$resultStatus = 1;
						$arrToMailIds = array();
						$arrToMailIds[] = $obj->email;
						$subject = "Thanks for your applying";
						$body  = 'Hello '.$obj->name;
						$body .= '<br/>Thanks for applying at Exulia Tech.......';
						$body .= '<br/><br/><br/>Warm Regards,<br/>';
						$body .= 'Elixia Tech Solutions Ltd.<br/>';
						$body .= '<a href = "http://www.elixiatech.com" title = "elixiatech.com" target="_blank">www.elixiatech.com</a><br/>';
						$body .= '<br/><img src = "http://speed.elixiatech.com/images/logo.png" alt = "Elixia Tech Solutions Ltd."  style="width:25%;height:10%;"/><br/>';

						$isTemplatedMessage=1; //To change the footer of email
						$isElixiaTech = 1; //To change the sender name
						$attachmentFilePath = '';
						$attachmentFileName = '';

						$emailSent=sendMailUtil($arrToMailIds, "", "", $subject, $body, $attachmentFilePath, $attachmentFileName,$isTemplatedMessage,$isElixiaTech);
						
    					$resultStatus = 1;
	        	}else{
	        		$resultStatus = 0;
	        	}
	        }else{
	        	$resultStatus = 0;
	        }
		}else{
			$resultStatus = 0;
		}

        echo json_encode($resultStatus);

	}	
?>
