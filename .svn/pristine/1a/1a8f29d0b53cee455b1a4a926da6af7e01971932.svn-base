<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../../";
}
require_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';


class pipelineManager extends DatabaseManager
{   
    function __construct(){
        // Constructor.
        parent::__construct();
    }
    
    public function addPipeline($obj){

        $db = new DatabaseManager();
        $creator =  GetLoggedInUserid();

        $sqlQuery = sprintf("INSERT INTO " . DB_PARENT . ".`sales_pipeline`(
        `pipeline_date`,
        `company_name`,
        `sourceid`,
        `productid`,
        `industryid`,
        `modeid`,
        `teamid`,
        `location`,
        `remarks`,
        `stageid`,
        `timestamp`,
        `teamid_creator`,
        `create_platform`,
        `tepidity`,
        `quantity`,
        `quotationDetails`
        )
        VALUES (
         '%s','%s',%d,'%s',%d,%d,%d,'%s','%s',%d,'%s',%d,%d, %d, %d,'%s');
         ", $obj->pipelinedate, $obj->companyname, $obj->source, $obj->product, $obj->industry, $obj->mode, $obj->assignto, $obj->location, $obj->remarks, $obj->stageid, $obj->today, $creator, $obj->create_platform, $obj->tepidity, $obj->quantity, $obj->quotationDetails);
       
        $db->executeQuery($sqlQuery);

        $mainid = $db->get_insertedId();

        $sqlQuery = sprintf("INSERT INTO " . DB_PARENT . ".`sales_pipeline_history`(
            `pipelineid`,
            `pipeline_date`,
            `company_name`,
            `sourceid`,
            `productid`,
            `industryid`,
            `modeid`,
            `teamid`,
            `location`,
            `remarks`,
            `stageid`,
            `timestamp`,
            `teamid_creator`,
            `quotationDetails`
            )
            VALUES (
             %d,'%s','%s',%d,'%s',%d,%d,%d,'%s','%s',%d,'%s',%d,'%s');
             ", $mainid, $obj->pipelinedate, $obj->companyname, $obj->source, $obj->product, $obj->industry, $obj->mode, $obj->assignto
                , $obj->location, $obj->remarks, $obj->stageid, $obj->today, GetLoggedInUserid(),$obj->quotationDetails);
        $db->executeQuery($sqlQuery);


        $sqlContactQuery = sprintf("INSERT INTO " . DB_PARENT . ".`sales_contact` (
        `pipelineid` ,
        `designation` ,
        `name` ,
        `phone` ,
        `email` ,
        `timestamp` ,
        `teamid_creator`
        )
        VALUES (
            %d,'%s','%s','%s','%s','%s',%d
        );", $mainid, $obj->designation, $obj->contactperson, $obj->contactno, $obj->emailaddress, $obj->today, GetLoggedInUserid());
        $db->executeQuery($sqlContactQuery);
        return $mainid;
    }

    public function updatePipeline($obj){
        $db = new DatabaseManager();
        $sqlQuery = sprintf("INSERT INTO " . DB_PARENT . ".`sales_pipeline_history`(`pipelineid`,
                                        `pipeline_date`,
                                        `company_name`,
                                        `sourceid`,
                                        `productid`,
                                        `industryid`,
                                        `modeid`,
                                        `teamid`,
                                        `location`,
                                        `remarks`,
                                        `stageid`,
                                        `device_cost`,
                                        `subscription_cost`,
                                        `timestamp`,
                                        `teamid_creator`,
                                        `loss_reason`,
                                        `tepidity`,
                                        `quantity`,
                                        `quotationDetails`,
                                        `update_platform`,
                                        `revive_date`)
                                VALUES (%d,'%s','%s',%d,'%s',%d,%d,%d,'%s','%s',%d,'%s','%s','%s',%d,'%s',%d,%d,'%s',%d, '%s');", Sanitise::Long($obj->pipelineid)
                    , Sanitise::Date($obj->pipelinedate)
                    , Sanitise::String($obj->companyname)
                    , Sanitise::Long($obj->source)
                    , Sanitise::String($obj->product)
                    , Sanitise::Long($obj->industry)
                    , Sanitise::Long($obj->mode)
                    , Sanitise::Long($obj->allot_to)
                    , Sanitise::String($obj->location)
                    , Sanitise::String($obj->remarks)
                    , Sanitise::Long($obj->stage)
                    , Sanitise::String($obj->devicecost)
                    , Sanitise::String($obj->subscriptioncost)
                    , Sanitise::DateTime($obj->today)
                    , Sanitise::Long(GetLoggedInUserId())
                    , Sanitise::String($obj->loss_reason)
                    , Sanitise::Long($obj->tepidity)
                    , Sanitise::Long($obj->quantity)
                    , Sanitise::String($obj->quotationDetails)
                    , Sanitise::Long($obj->update_platform)
                    , Sanitise::String($obj->revivedate));

            $db->executeQuery($sqlQuery);
            $today = date('Y-m-d H:i:s');

            $db = new DatabaseManager();
            $sqlmainupdate = sprintf("  UPDATE  " . DB_PARENT . ".`sales_pipeline` 
                                        set     `pipeline_date` = '%s'
                                                ,company_name = '%s'
                                                ,sourceid = %d
                                                ,productid = '%s'
                                                ,industryid = %d
                                                ,modeid = %d
                                                ,teamid = %d
                                                ,location = '%s'
                                                ,remarks = '%s'
                                                ,stageid = %d
                                                ,device_cost= '%s'
                                                ,subscription_cost = '%s'
                                                ,loss_reason = '%s'
                                                ,tepidity = %d
                                                ,quantity = %d
                                                ,update_platform = %d
                                                ,quotationDetails = '%s'
                                                ,revive_date = '%s'
                                                ,timestamp = '%s'
                                        where   pipelineid = %d" 
                    , Sanitise::Date($obj->pipelinedate)
                    , Sanitise::String($obj->companyname)
                    , Sanitise::Long($obj->source)
                    , Sanitise::String($obj->product)
                    , Sanitise::Long($obj->industry)
                    , Sanitise::Long($obj->mode)
                    , Sanitise::Long($obj->allot_to)
                    , Sanitise::String($obj->location)
                    , Sanitise::String($obj->remarks)
                    , Sanitise::Long($obj->stage)
                    , Sanitise::String($obj->devicecost)
                    , Sanitise::String($obj->subscriptioncost)
                    , Sanitise::String($obj->loss_reason)
                    , Sanitise::Long($obj->tepidity)
                    , Sanitise::Long($obj->quantity)
                    , Sanitise::Long($obj->update_platform)
                    , Sanitise::String($obj->quotationDetails)
                    , Sanitise::String($obj->revivedate)
                    , Sanitise::DateTime($today)
                    , Sanitise::Long($obj->pipelineid));

            $db->executeQuery($sqlmainupdate);
    }

    public function uploadSalesFile($file, $docType, $pipelineId){
        $today = date('Y-m-d H:i:s');
        $teamid = GetLoggedInUserId();
        $db  = new DatabaseManager();
        $filename = $uploaddir . basename($file['name']);
        $path_parts = pathinfo($filename);
        $ext = $path_parts['extension'];
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" .$pipelineId."'".
                     ",'".$docType."'".
                     ",'".$ext."'".
                     ",'".$today."'".
                     ",'".GetLoggedInUserId()."'".
                     ",@isexecutedOut,@versionOut,@docTypeOut";
        $queryCallSP       = "CALL " . speedConstants::SP_VERSION_FILE . "($sp_params)";
        $result            = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isexecutedOut,@versionOut,@docTypeOut";
        $outputResult              = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $version = $outputResult['@versionOut'];
        $docTypeName = $outputResult['@docTypeOut'];
        $files = array();
        if (!file_exists("../../pipeline/")) {
            mkdir("../../pipeline/", 0777,true);
        }
        $uploaddir = "../../pipeline/" . $pipelineId."/";
        $pipelineFolder = "../../pipeline/" . $pipelineId."/";
        if (!file_exists($pipelineFolder)) {
            mkdir("../../pipeline/" . $pipelineId."/", 0777,true);
        }
        
        if (move_uploaded_file($file['tmp_name'], $uploaddir . $docTypeName .'_'. $version.'.' . $ext)) {
            $files[] = $uploaddir . $file['name'];
        } else {
            $error = true;
        }
            
        
        $data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
        //echo $uploaddir . $docTypeName .'_'. $version.'.' . $ext;
        return $data;
    }

    public function fetchPipelineFiles($pipelineId){
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" .$pipelineId."'";
        $queryCallSP       = "CALL " . speedConstants::SP_FETCH_PIPELINE_FILES . "($sp_params)";
        $result            = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $ret = array();
        foreach ($result as &$file){
            $file['fileName'] = $file['docTypeName']."_".$file['version'].".".$file['extensionTypeName'];
            $file['downloadPath'] = "../../pipeline/".$pipelineId."/".$file['fileName'];
            $ret[$file['docTypeName']][]=$file;
        }
        return $ret;
    }

    public function revivePipeline($obj){
        $db  = new DatabaseManager();
        $today = date('Y-m-d H:i:s');
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" .$obj->pipelineId."',"
                    ."'" .$obj->update_platform."',"
                    ."'" .$today. "'";
        $queryCallSP       = "CALL " . speedConstants::SP_REVIVE_PIPELINE . "($sp_params)";
        $result            = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isexecutedOut,@versionOut,@docTypeOut";
        $outputResult              = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $version = $outputResult['@versionOut'];  
    }

    public function fetchSRStats($obj){
        $db  = new DatabaseManager();
        $today = date('Y-m-d H:i:s');
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" .$obj->teamid."',"
                    ."'" .$obj->weekStart."',"
                    ."'" .$obj->monthStart. "',"
                    ."'" .$obj->today."'";
        $queryCallSP       = "CALL " . speedConstants::SP_FETCH_SR_STATS . "($sp_params)";
        $result            = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        return $result;      
    }

    public function updatePipeline_Customer($obj){
        $db  = new DatabaseManager();
        $today = date('Y-m-d H:i:s');
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" .$obj->contactId."'"
                    .",'" .$obj->customerNo."'"
                    .",'" .$obj->pipeLineId."'";
        $queryCallSP       = "CALL " . speedConstants::SP_UPDATE_CUSTOMER_PIPLELINE . "($sp_params)";
        $result  = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        return $result;      
    }

    public function getDashboardDetails($salesuserId,$companyRoleId){
        $db     = new DatabaseManager();
        $today  = date('Y-m-d');
        $currentMonth = date('m');
      //  $currentMonth = '02';
        //sessionteamid
        $userTeamId = $salesuserId;
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" .$userTeamId."'"
                    .",'".$currentMonth."'"
                    .",'".$today."'"
                    .",'".$companyRoleId."'";
          $queryCallSP  = "CALL " . speedConstants::SP_GET_TEAM_SALES_DASHBOARD_DETAILS . "($sp_params)";        $result  = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);

        return $result;    
    }

}

?>