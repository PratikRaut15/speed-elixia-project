<?php

include_once "session.php";
include_once "db.php";
include_once "../../lib/system/utilities.php";
include_once "loginorelse.php";
include_once "../../constants/constants.php";
include_once "../../lib/bo/TeamManager.php";
include_once "../../lib/system/Sanitise.php";
include_once "../../lib/system/class.phpmailer.php";
	
	

	if(isset($_POST['fetch_devices_info'])){
		$tm = new TeamManager();
		$customer_array = array();
		$team_id = $_POST['teamId'];
		$company_roleId = $_SESSION['company_roleId'];
		
		if($company_roleId==1 || $company_roleId==2 || $company_roleId==3){
    		$team_id = 0;
    	}
		
		$customer_array = $tm->get_AllCust_DeviceInfo($team_id);
		echo json_encode($customer_array);
	}

	if(isset($_POST['fetch_devices_infoDetails'])){
			$tm = new TeamManager();
			$activeDeviceArray  =  array();
			$expiredDeviceArray = array();
			$toBeExpDeviceArray = array();
			$customerNo = $_POST['custNo'];
			$devTypeId = $_POST['devType'];
			$final_array = array();

			if($devTypeId==1){
				$final_array   = $tm->get_ActiveDevice_info($customerNo);
			}
			else if($devTypeId==2){
				$final_array  = $tm->get_ExpiredDevice_info($customerNo);
			}
			else if($devTypeId==3){
				$final_array  = $tm->get_toBe_ExpDevice_info($customerNo);
			}
			
			echo json_encode($final_array);
	}
?>