<?php
//print_r($_REQUEST);
//print_r($_FILES);
require('xlRead/php-excel-reader/excel_reader2.php');
require('xlRead/SpreadsheetReader.php');
date_default_timezone_set("Asia/Calcutta");

include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/bo/TimeSheetManager.php");
include_once("../../lib/bo/ImportManager.php");
$target_dir = "../../customer/team/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$msg = '';
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
if(isset($_FILES["fileToUpload"])){

	if ($_FILES["fileToUpload"]["size"] > 2000000) {
	    $msg .=  "<br>Your file is too large.";
	    $uploadOk = 0;
	}
	if($imageFileType != "xlsx"  ) {
	    $msg .= "<br>Only XLSX files are allowed.";
	    $uploadOk = 0;
	}

}else{
	$uploadOk = 0;
}
if ($uploadOk == 0) {
    echo   "<br>Your file was not uploaded due to following reason(s):";
    echo $msg;
// if everything is ok, try to upload file
} else {
	if(!file_exists($target_dir)){
		mkdir($target_dir,0777);
	}
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    	$Filepath = $target_file;
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
//defining constants for rows
	define("taskName",1);
	define("taskDesc",5);
	define("time",4);
	define("date",0);
	define("products",3);
	define("customers",2);
	define("teamId",$_REQUEST['teamId']);
	define("trId",$_REQUEST['trId']);
	define("dept",$_REQUEST['dept']);
//creating table objects
	$taskPref = new stdClass();
	    $taskPref->tableName = "task";
	    $taskPref->idCol = "taskId";
	    $taskPref->valCol = "taskName";
	$prodMapPref = new stdClass();
	    $prodMapPref->tableName = "taskProductMapping";
	    $prodMapPref->idCol = "pMapId";
	    $prodMapPref->valCol = "productId";
	$custMapPref = new stdClass();
	    $custMapPref->tableName = "taskCustomerMapping";
	    $custMapPref->idCol = "cMapId";
	    $custMapPref->valCol = "customerNo";
	$taskTeamPref = new stdClass();
	    $taskTeamPref->tableName = "taskTeam";
	    $taskTeamPref->idCol = "ttId";
	    $taskTeamPref->valCol = "taskId";
	$productPref = new stdClass();
	    $productPref->tableName = "elixiaOneProductMaster";
	    $productPref->idCol = "prodId";
	    $productPref->valCol = "prodName";
	$taskMapPref = new stdClass();
	    $taskMapPref->tableName = "taskMapping";
	    $taskMapPref->idCol = "tMapId";
	    $taskMapPref->valCol = "taskId";
	$timeSheetPref = new stdClass();
		$timeSheetPref->tableName = "timeSheet";
	    $timeSheetPref->idCol = "tsId";
	    $timeSheetPref->valCol = "tMapId";
//creating default preferences objects
	$valPref = new stdClass();
	    $valPref->customerNo = "2";
	$optParams = new stdClass();
	    $optParams->isSeed = 0;
	    $optParams->isMaster = 1;
	    $optParams->isCommon = 0;
	    $optParams->isDeletedCheck = 0;
	    $optParams->straightInsert = 0;
	    $optParams->dontInsert = 0;
	$dataPref = new stdClass();
	    $dataPref->updateIfFound = 0;
	$notFound=0;
	$found=0;
$departmentId = $_REQUEST['dept'];
$teamId = $_REQUEST['teamId'];
$trId = $_REQUEST['trId'];
$today = date('Y-m-d H:i:s');
	//print_r($_REQUEST);

try{
    $Spreadsheet = new SpreadsheetReader($Filepath);
    $Sheets = $Spreadsheet -> Sheets();   
    foreach ($Sheets as $Index => $Name){
        $Spreadsheet->ChangeSheet($Index);
        foreach ($Spreadsheet as $Key => $Row){
            if($Key<1){
                continue;
            }
            $im = new ImportManager();
            $time = explode(":",$Row[time]);
			$sum = 0;
			$i=0;
			$m = 3600;
			foreach($time as  $t){
				$sum += $t*$m;
				$m/=60;
				$i++;
				if($i==2){
					break;
				}
			}
			$time = $sum;

            $optParams->isCommon = 1;
            $optParams->straightInsert = 1;	
			$dataPref->colList = array('taskName','taskDesc','statusId','estimatedTime','departmentId','createdBy','createdOn');
            $dataPref->valList = array($Row[taskName],$Row[taskDesc],0,$time,$departmentId,$teamId,$today);
            $dataPref->updateIfFound=0;
            $optParams->addCondition="";
            $optParams->isSeed = 0;
            $valPref->term = $im->cleanNonPritableChars($Row[taskName]);
            $nextId = 0;
            $prodMapId = $im->getNextId($prodMapPref);
            $custMapId = $im->getNextId($custMapPref);
            $taskId = $im->checkAndInsert($taskPref,$valPref,$optParams,$dataPref);

            $dataPref->colList = array('taskId','trId','createdBy','createdOn');
            $dataPref->valList = array($taskId,$trId,$teamId,$today);
            $ttId = $im->checkAndInsert($taskTeamPref,$valPref,$optParams,$dataPref);


	    	$optParams->dontInsert = 1;
	    	$products = explode(",",$Row[products]);
	    	foreach($products as $product){
	    		$valPref->term = $im->cleanNonPritableChars($product);
	    		$prodIds[] = $im->checkAndInsert($productPref,$valPref,$optParams,$dataPref);
	    	}
	    	$optParams->dontInsert = 0;
            $optParams->straightInsert = 1;
	    	foreach($prodIds as $prodId){
	    		$valPref->term = $im->cleanNonPritableChars($prodId);
	            $dataPref->colList = array('taskId','pMapId','productId');
	            $dataPref->valList = array($taskId,$prodMapId,$prodId);
	    		$tpId[] = $im->checkAndInsert($prodMapPref,$valPref,$optParams,$dataPref);
	    	}

	    	$optParams->dontInsert = 0;
            $optParams->straightInsert = 1;
	    	$customerIds = explode(",",$Row[customers]);
	    	foreach($customerIds as $customerId){
	    		$valPref->term = $im->cleanNonPritableChars($customerId);
	            $dataPref->colList = array('taskId','cMapId','customerNo');
	            $dataPref->valList = array($taskId,$custMapId,$customerId);
	    		$tcId[] = $im->checkAndInsert($custMapPref,$valPref,$optParams,$dataPref);
	    	}

	    	$optParams->dontInsert = 0;
            $optParams->straightInsert = 1;
            $dataPref->colList = array('taskId','teamId','trId','pMapId','cMapId','isClosed');
            $dataPref->valList = array($taskId,$teamId,$trId,$prodMapId,$custMapId,1);
            $tMapId = $im->checkAndInsert($taskMapPref,$valPref,$optParams,$dataPref);

	    	$optParams->dontInsert = 0;
            $optParams->straightInsert = 1;
            $dataPref->colList = array('tMapId','time','date','teamId','createdBy','createdOn');
            $dataPref->valList = array($tMapId,$time,$Row[date],$teamId,$teamId,$today);
            $tsId = $im->checkAndInsert($timeSheetPref,$valPref,$optParams,$dataPref);
	    	$optParams->dontInsert = 0;
            $optParams->straightInsert = 0;
    	}
    }
}
catch (Exception $E){
    echo $E -> getMessage();
}
?>