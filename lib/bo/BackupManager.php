<?php


if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
require_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';


class BackupManager extends DatabaseManager
{   
    function __construct()
    {
        // Constructor.
        parent::__construct();
    }
    public function fetchUnits($limit=0,$customerNo=0){
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $query = "SELECT ubId,unitNo,customerNo from unitBackup Where isProcessed = 0";
        if($customerNo != 0){
            $query .= " AND customerNo = ".$customerNo;
        }
        $query .= " order by customerNo";
        if(is_numeric($limit)){
            $query .= " LIMIT ".$limit;
        }
        //echo $query;
        $arrResult = $pdo->query($query)->fetchall(PDO::FETCH_ASSOC);
        return $arrResult;
    }
    public function updateUnitList(){
        $today = date('Y-m-d H:i:s');
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $query = "TRUNCATE table `unitBackup`";
        $arrResult = $pdo->query($query);
        $query = "INSERT into `unitBackup` (`unitNo`,`customerNo`,`isProcessed`,`timestamp`) 
        			SELECT unitno,customerno,0,'".$today."' from `speed`.`unit` WHERE customerno > 1 ORDER BY customerno";
        $arrResult = $pdo->query($query)->fetchall(PDO::FETCH_ASSOC);
        return $arrResult;
    }

    public function processBackup($ubId,$startTime,$endTime,$timeTaken,$errMsg){
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $query = "Update `unitBackup` SET isProcessed = 1, `startTime`='".$startTime."', `endTime`='".$endTime."',`timeTaken`='".$timeTaken."',`errorLog`='".$errMsg."' where ubId= ".$ubId."";
        $arrResult = $pdo->query($query);
    }
}