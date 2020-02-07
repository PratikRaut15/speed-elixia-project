<?php
$RELATIVE_PATH_DOTS = "../../../../";

require_once "database.inc.php";
require_once $RELATIVE_PATH_DOTS . "lib/autoload.php";
date_default_timezone_set('Asia/Kolkata');

class api_new{

//<editor-fold defaultstate="collapsed" desc="Constructor">
// construct
    function __construct() {
        $this->db = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
    }

    function failure($text) {
        return array('Status' => '0', 'Message' => $text);
    }

    function success($message, $result) {
        return array('Status' => '1', 'Message' => $message, 'Result' => $result);
    }

    function fetchTeamList() {

       

        $arr_p = array();
        $json_p = array();

        $sql = "SELECT name,teamid from team WHERE company_roleId NOT IN(14) AND is_deleted = 0 ORDER BY name";
        $record = array();
        $record = $this->db->query($sql, __FILE__, __LINE__);
        if ($this->db->num_rows($record) > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $arr_p['teamid'] = $row["teamid"];
                $arr_p['name'] = $row["name"];
                $arr_p['status'] = $this->getBusyStatus($arr_p['teamid']);
                $arr_p['time'] = $this->getBusyStatusForTime($arr_p['teamid']);
                $json_p[] = $arr_p;
            }
            return $json_p;
        } 
        else {
            return null;
        }
    }

    function insertTeamAttendance($obj){
    		$todaydatetime = date("Y-m-d H:i:s");
            $db          = new DatabaseManager();
            $pdo         = $db->CreatePDOConnForTech();
            $sp_params         =    "'" .$obj->teamId. "'" . 
                                    ",'" .$obj->check_value . "'" .
                                    ",'" .$obj->location . "'" .
                                    ",'" .$todaydatetime . "'" .
                                    "," . "@isExecutedOut";
            $queryCallSP = "CALL " . speedConstants::SP_INSERT_TEAM_ATTENDANCE . "(" . $sp_params . ")";
            $arrResult   = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
            $outputResult      = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

            $db->ClosePDOConn($pdo);

            $result= $outputResult['isExecutedOut'];
            return $result;
    }

	function getOfficeLocation($obj){  
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $sp_params         =    "'" .$obj->center. "'" . 
                                 "," . "@locationOut";
        $queryCallSP = "CALL " . speedConstants::SP_GET_OFFICE_LOCATION . "(" . $sp_params . ")";
        $arrResult   = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @locationOut as locationOut";
        $outputResult      = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        $db->ClosePDOConn($pdo);

        $result= $outputResult['locationOut'];
        return $result;
    }

    function getBusyStatus($teamId){ 

            $db = new DatabaseManager(); 
            $today = date("Y-m-d");
            $pdo         = $db->CreatePDOConnForTech();
            $sp_params         =    "'" . $teamId. "'" . 
                                    ",'" . $today. "'" . 
                                     "," . "@isExistsOut,@latestTimeOut";
            $queryCallSP = "CALL " . speedConstants::SP_GET_TEAM_BUSY_STATUS . "(" . $sp_params . ")";
           
            $arrResult   = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            
            $db->ClosePDOConn($pdo);
      
            if($arrResult['isExistOut']==1){
                $arrResult['isExistOut']="2";
            }
            if($arrResult['isExistOut']==0){
                $arrResult['isExistOut']="3";
            }

            return $arrResult['isExistOut'];
    }

    function getBusyStatusForTime($teamId){ 

        $db = new DatabaseManager(); 
        $today = date("Y-m-d");
        $pdo         = $db->CreatePDOConnForTech();
        $sp_params         =    "'" . $teamId. "'" . 
                                ",'" . $today. "'" . 
                                 "," . "@isExistsOut,@latestTimeOut";
        $queryCallSP = "CALL " . speedConstants::SP_GET_TEAM_BUSY_STATUS . "(" . $sp_params . ")";
       
        $arrResult   = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        
        $db->ClosePDOConn($pdo);

        $arrResult['latestTimeOut'] = date("H:i",strtotime($arrResult['latestTimeOut']));

        // if($arrResult['latestTimeOut']=='00:00'){
        //     $arrResult['latestTimeOut']='N/A';
        // }

        return $arrResult['latestTimeOut'];
    }
}