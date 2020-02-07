<?php 

if (!isset($_SESSION)) {
    session_start();
}
include_once '../../lib/system/utilities.php';
include_once '../../lib/autoload.php';
include_once '../../lib/bo/VehicleManager.php';

  	function getTravelSettingList($nid='0'){
       $VehicleManager 			= new VehicleManager($_SESSION['customerno']);
       $travelSettings 			= $VehicleManager->getTravelSettingList($_SESSION['customerno'],$nid);
       return $travelSettings;
    }

	function addTravelSetting($starttime,$endtime,$threshold, $customerno, $created_on, $created_by){
		 $VehicleManager 		 			= new VehicleManager($_SESSION['customerno']);
		 $travelsettingObj 				 	= new stdClass();
		 $travelsettingObj->starttime 		= $starttime;
		 $travelsettingObj->endtime 		= $endtime;
		 $travelsettingObj->threshold 		= $threshold;
		 $travelsettingObj->customerno 		= $customerno;
		 $travelsettingObj->created_on 		= $created_on;
		 $travelsettingObj->created_by 		= $created_by;
		 $travelSettingsDetails 			= $VehicleManager->addTravelSetting($travelsettingObj);
		 return $travelSettingsDetails['varTravelsettinId'];
	}

    function editTravelSetting($travelsettingid,$starttime,$endtime,$threshold, $customerno, $created_on, $created_by){
		 $VehicleManager 		 				= new VehicleManager($_SESSION['customerno']);
		 $travelsettingObj 				 		= new stdClass();
		 $travelsettingObj->travelsettingid 	= $travelsettingid;
		 $travelsettingObj->starttime 			= $starttime;
		 $travelsettingObj->endtime 			= $endtime;
		 $travelsettingObj->threshold 			= $threshold;
		 $travelsettingObj->isdeleted 			= 0;
		 $travelsettingObj->customerno 			= $customerno;
		 $travelsettingObj->updated_on 			= $created_on;
		 $travelsettingObj->updated_by 			= $created_by;
		 $travelSettingsDetails 				= $VehicleManager->editTravelSetting($travelsettingObj);
		 return $travelSettingsDetails['varTravelSettingsId'];
	}
?>