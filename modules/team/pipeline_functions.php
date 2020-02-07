<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/bo/pipelineManager.php");
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");

if(isset($_POST['action'])&&$_POST['action']=='deletepipeline'){
	$db = new DatabaseManager();
    $SQL = sprintf("update  " . DB_PARENT . ".sales_pipeline set isdeleted = 1,delete_platform = 1 where pipelineid=" . $_POST['pipelineid']);
    $db->executeQuery($SQL);
    echo "ok";
}

if(isset($_POST['submitPipeline'])){
    $obj = new stdClass();
    $obj->pipelinedate = GetSafeValueString($_POST["pipelinedate"], "string");
    $obj->companyname = GetSafeValueString($_POST["companyname"], "string");
    $obj->source = GetSafeValueString($_POST["source"], "string");
    $obj->product = GetSafeValueString($_POST["productSelected"], "string");
    $obj->industry = GetSafeValueString($_POST["industry"], "string");
    $obj->mode = GetSafeValueString($_POST["mode"], "string");
    $obj->location = GetSafeValueString($_POST["location"], "string");
    $obj->designation = GetSafeValueString($_POST["designation"], "string");
    $obj->contactperson = GetSafeValueString($_POST["contactperson"], "string");
    $obj->emailaddress = GetSafeValueString($_POST["emailaddress"], "string");
    $obj->contactno = GetSafeValueString($_POST["contactno"], "string");
    $obj->remarks = GetSafeValueString($_POST["remarks"], "string");
    $obj->assignto = GetSafeValueString($_POST["teamid"], "string");
    $obj->quotationDetails = GetSafeValueString($_POST["qDetails"], "string");
    $obj->assignto = isset($obj->assignto) ? $obj->assignto : 0;
    $obj->tepidity = GetSafeValueString($_POST["tepidity"], "string");
    $obj->quantity = GetSafeValueString($_POST["quantity"], "string");
    $obj->today = $today;
    $obj->create_platform = 1 ;
    $obj->stageid = 1;
    if ($obj->pipelinedate != "") {
        $obj->pipelinedate = date('Y-m-d', strtotime($obj->pipelinedate));
    } else {
        $obj->pipelinedate = date('Y-m-d');
    }

    $pM = new pipelineManager();
    $pipelineId = $pM->addPipeline($obj);

    if(isset($_FILES)){
        foreach ($_FILES as $key=>$file){
            if(($file['name']!='')){
                if($key=='quotation'){
                    $pM->uploadSalesFile($file,1,$pipelineId);
                }
                elseif($key=='brd'){
                    $pM->uploadSalesFile($file,2,$pipelineId);
                }
            }
        }
    }

    echo $pipelineId;
}

if(isset($_POST['updatePipeline'])){
    $obj = new stdClass();
    $obj->pipelineid = GetSafeValueString($_POST["pipelineId"], "string");
    $obj->pipelinedate = GetSafeValueString($_POST["pipelinedate"], "string");
    $obj->pipelinedate = isset($obj->pipelinedate) ? date('Y-m-d', strtotime($obj->pipelinedate)) : "";
    $obj->companyname = GetSafeValueString($_POST["companyname"], "string");
    $obj->source = GetSafeValueString($_POST["source"], "string");
    $obj->stage = GetSafeValueString($_POST["stage"], "string");
    $obj->devicecost = GetSafeValueString($_POST["devicecost"], "string");
    $obj->devicecost = isset($obj->devicecost) ? $obj->devicecost : "0";
    $obj->subscriptioncost = GetSafeValueString($_POST["subscriptioncost"], "string");
    $obj->subscriptioncost = isset($obj->subscriptioncost) ? $obj->subscriptioncost : "0";
    $obj->allot_to = GetSafeValueString($_POST["teamid"], "string");
    $obj->allot_to = isset($obj->allot_to) ? $obj->allot_to : "0";
    $obj->product = GetSafeValueString($_POST["productSelected"], "string");
    $obj->industry = GetSafeValueString($_POST["industry"], "string");
    $obj->mode = GetSafeValueString($_POST["mode"], "string");
    $obj->location = GetSafeValueString($_POST["location"], "string");
    $obj->remarks = GetSafeValueString($_POST["remarks"], "string");
    $obj->loss_reason = GetSafeValueString($_POST["loss_reason"], "string");
    $obj->quotationDetails = GetSafeValueString($_POST["qDetails"], "string");
    $obj->tepidity = GetSafeValueString($_POST["tepidity"], "string");
    $obj->quantity = GetSafeValueString($_POST["quantity"], "string");
    $obj->revivedate = date("Y-m-d",strtotime($_POST['reviveDate']));
    $obj->update_platform = 1;
    $obj->today = $today;
    $pM = new pipelineManager();
    $ret = $pM->updatePipeline($obj);
    
    if(isset($_FILES)){
        foreach ($_FILES as $key=>$file){
            if(($file['name']!='')){
                if($key=='quotation'){
                    $pM->uploadSalesFile($file,1,$obj->pipelineid);
                }
                elseif($key=='brd'){
                    $pM->uploadSalesFile($file,2,$obj->pipelineid);
                }
            }
        }
    }
    $message = "Data Updated Sucessfully";
    echo $message;
}

if(isset($_POST['revivePipeline'])){
    $obj = new stdClass();
    $obj->pipelineId = $_POST['pipelineId'];
    $obj->update_platform = 1;
    $pM = new pipelineManager();
    $ret = $pM->revivePipeline($obj);
    echo "ok";
}
if(isset($_POST['fetchSalesReport'])){
    $obj = new stdClass();
    $obj->teamid = $_POST['teamid'];
    $obj->date = $_POST['date'];
    $pM = new pipelineManager();
    $ret = $pM->fetchSalesReport($obj);
    echo json_encode($ret);
}
?>