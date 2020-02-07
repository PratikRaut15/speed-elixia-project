<?php
include_once "session.php";
include_once "db.php";
include_once "../../lib/system/utilities.php";
include_once "loginorelse.php";
include_once "../../constants/constants.php";
include_once "../../lib/bo/TeamManager.php";
include_once "../../lib/system/Sanitise.php";
include_once "../../lib/system/class.phpmailer.php";
	
	$tm = new TeamManager();

	if(isset($_POST['insert_distributorCustomer_details'])){
		global $tm;
		$distributorObj = new stdClass();
		$distributorObj->customerName 	= $_POST['c_name'];
		$distributorObj->companyName	= $_POST['comp_name'];
		$distributorObj->address 		= $_POST['address'];
		$distributorObj->phone			= $_POST['phone'];
		$distributorObj->email			= $_POST['email'];
		$distributorId					= $tm->insert_distributor_details($distributorObj);
		//$distributorId=1;
		if($distributorId>0){
			if(isset($_FILES)){	

				$error = false;
		        $files = array();

		        $customerfolder = "../../customer";
		        $distributorFolder = $customerfolder."/distributor_details";
	        	$title=$distributorFolder."/".$distributorId;
	        	$addressFolder = $title."/address_proof/";
		        $photoFolder = $title."/photo_proof/";

	        	if (!file_exists($customerfolder)) {
		            mkdir($customerfolder, 0777);
		        }
		        if (!file_exists($distributorFolder)) {
		            mkdir($distributorFolder, 0777);
		        }
		        if (!file_exists($title)){
		        	mkdir($title,0777);
		        }
		        if (!file_exists($addressFolder)){
		        	mkdir($addressFolder, 0777);
		        }
		        if (!file_exists($photoFolder)){
		        	mkdir($photoFolder, 0777);
		        }
		        if(isset($_FILES['addressFile'])){
		        	$_FILES['addressFile']['proof_type']=1;
		        	upload_files_function($_FILES['addressFile'],$addressFolder,$distributorId);
		        }
		        if(isset($_FILES['photoFile'])){
		        	$_FILES['photoFile']['proof_type']=2;
		        	upload_files_function($_FILES['photoFile'],$photoFolder,$distributorId);
		        }
		        echo json_encode($distributorId);
			}
			else{
				echo json_encode($distributorId);
			}
		}
		else{
			echo json_encode($distributorId);
		}
	}

	if(isset($_POST['fetch_distributor_details'])){
	 	global $tm;
	 	$distributorDetailObj = new stdClass();
		$distributorDetailObj->teamId 	= isset($_POST['teamid'])? $_POST['teamid']:"";
		$distributorDetailObj->dcId 	= isset($_POST['dcId'])? $_POST['dcId']:"";
		$distributor_detailsArray = $tm->get_distributor_details($distributorDetailObj);
		echo json_encode($distributor_detailsArray);
	}

	if(isset($_POST['update_distributorCustomer_details'])){
		global $tm;
		$distributorObj = new stdClass();

		$distributorObj->dc_id          = $_POST['dc_ID'];
		$distributorObj->customerName 	= $_POST['c_name'];
		$distributorObj->companyName	= $_POST['comp_name'];
		$distributorObj->address 		= $_POST['address'];
		$distributorObj->phone			= $_POST['phone'];
		$distributorObj->email			= $_POST['email'];
		$distributorId					= $tm->update_distributor_details($distributorObj);

		if($distributorId>0){
			if(isset($_FILES)){	

				$error = false;
		        $files = array();

		        $customerfolder = "../../customer";
		        $distributorFolder = $customerfolder."/distributor_details";
	        	$title=$distributorFolder."/".$distributorId;
	        	$addressFolder = $title."/address_proof/";
		        $photoFolder = $title."/photo_proof/";

	        	if (!file_exists($customerfolder)) {
		            mkdir($customerfolder, 0777);
		        }
		        if (!file_exists($distributorFolder)) {
		            mkdir($distributorFolder, 0777);
		        }
		        if (!file_exists($title)){
		        	mkdir($title,0777);
		        }
		        if (!file_exists($addressFolder)){
		        	mkdir($addressFolder, 0777);
		        }
		        if (!file_exists($photoFolder)){
		        	mkdir($photoFolder, 0777);
		        }
		        if(isset($_FILES['addressFile'])){
		        	$_FILES['addressFile']['proof_type']=1;
		        	upload_files_function($_FILES['addressFile'],$addressFolder,$distributorId);
		        }
		        if(isset($_FILES['photoFile'])){
		        	$_FILES['photoFile']['proof_type']=2;
		        	upload_files_function($_FILES['photoFile'],$photoFolder,$distributorId);
		        }
		        echo json_encode($distributorId);
			}
			else{
				echo json_encode($distributorId);
			}
		}
		else{
			echo json_encode($distributorId);
		}
	}

	if(isset($_POST['insert_vehicle_details'])){
		global $tm;
		$vehicleNo = array();
		$engineNo = array();
		$chasisNo = array();
		$finalArray = array();

		$vehicleNo	=	$_POST['vehicle_no'];
		$engineNo	=	$_POST['engine_no'];
		$chasisNo	=	$_POST['chasis_no'];
		$dc_id 		=   $_POST['dcId'];
		
		$finalArray = array_map(function($vehicleNo,$engineNo,$chasisNo){  
		return array_merge(array($vehicleNo),array($engineNo),array($chasisNo));
		},$vehicleNo,$engineNo,$chasisNo);  

		
			foreach($finalArray as $key=>$value){
				
				$distributorVehObj 			= new stdClass();
				$distributorVehObj->dcId    =   $dc_id;
				$distributorVehObj->vehNo 	=	$value[0];
				$distributorVehObj->engNo   =	$value[1];
				$distributorVehObj->chaNo   =	$value[2];
			    $final_result[$key]=$tm->insert_distributorVehicleDetails($distributorVehObj);
				
			}

			foreach($final_result as $val){
	        	if($val=="0"){
	        		$final_response =0;
	        	}else{
	        		$final_response=1;
	        	}
	        }

            echo json_encode($final_response);
	}

	if(isset($_POST['fetch_distributorVehicle_details'])){
		global $tm;
	 	$distributorVehDetailObj = new stdClass();
		$distributorVehDetailObj->teamId 	= isset($_POST['teamid'])? $_POST['teamid']:"";
		$distributorVehDetailObj->dcId 	= isset($_POST['dcId'])? $_POST['dcId']:"";
		$distributorVehDetailObj->dvId 	= isset($_POST['dvId'])? $_POST['dvId']:"";
		$distributor_detailsArray = $tm->get_distributorVeh_details($distributorVehDetailObj);
		echo json_encode($distributor_detailsArray);
	}

	if(isset($_POST['update_distributorVeh_details'])){
		global $tm;
		
		$distributorVehObj 			= 	new stdClass();
		$distributorVehObj->dv_id   =   $_POST['dv_id'];
		$distributorVehObj->vehNo 	=	$_POST['vehicle_no'];
		$distributorVehObj->engNo   =	$_POST['engine_no'];
		$distributorVehObj->chaNo   =	$_POST['chasis_no'];
		$final_result=$tm->update_distributorVehicleDetails($distributorVehObj);
		echo json_encode($final_result);
	}
	function upload_files_function($fileObj,$folderName,$distributorId){

		$proofType = $fileObj['proof_type'];

		if($proofType==1){
		 	$filename = basename($fileObj['name']);
		 	$path_parts = pathinfo($filename);
		 	$ext = "pdf";
		 	$new_filename = "addProof.".$ext;
		}

		if($proofType==2){
		 	$filename = basename($fileObj['name']);
		 	$path_parts = pathinfo($filename);
		 	$ext = "pdf";
		 	$new_filename = "photoProof.".$ext;
		}


		if (move_uploaded_file($fileObj['tmp_name'], $folderName.$new_filename)) {
			global $tm;
			$fileObj = new stdClass();
			$fileObj->distributor_id = $distributorId;
			$fileObj->file_path=$folderName;
			$fileObj->file_name = $new_filename;
			$fileObj->proof_type =$proofType;
			$filePath_Id = $tm->insert_distributorFileName($fileObj);
		} 
		else {
			$error = true;
		}
	}
?>