<?php

if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../../";
}
require_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';


Class ImportManager extends DatabaseManager{

    function __construct(){
        // Constructor.
        parent::__construct();
    }

    public $insertArray = array();
    public $failedInserts = array();
    public $updateArray = array();

    public function check($tblPref,$valPref,$optParams){

        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $today = date('Y-m-d H:i:s');
        $custCheck = "";
        $deletedCheck = "";
        if($optParams->isCommon==0){
            $custCheck = " AND customerNo = '".$valPref->customerNo."'";
        }
        if($optParams->isDeletedCheck==1){
            $deletedCheck = " AND isdeleted = 0";
        }
        $query = "select ".$tblPref->idCol." from ".$tblPref->tableName." where ".$tblPref->valCol." = '".$valPref->term."' ".$custCheck." ".$deletedCheck." ".$optParams->addCondition." LIMIT 1;"; 
        //$query = "select ".$tblPref->idCol." from ".$tblPref->tableName." where ".$tblPref->valCol." LIKE '%".$valPref->term."%' ".$custCheck." ".$deletedCheck." ".$optParams->addCondition." LIMIT 1;"; 
        
        //echo $query.PHP_EOL;
        $arrResult   = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);
        if($arrResult[$tblPref->idCol]){
            return $arrResult[$tblPref->idCol];  
        }else{
            return null;
        }
    }

    public function checkAndInsert($tblPref,$valPref,$optParams,$dataPref,$seedPref=''){
        //var_dump($optParams);
        if($optParams->straightInsert == 1 && $optParams->dontInsert == 0){
            $lastInsertId = $this->insertRecord($tblPref,$valPref,$optParams,$dataPref,$seedPref);
            return $lastInsertId; 
        }
        $checkResult = $this->check($tblPref,$valPref,$optParams);
        if($checkResult){
            //echo $checkResult;
            if($dataPref->updateIfFound==1){
                //echo PHP_EOL.">Found, updating.";
                $lastInsertId = $this->updateRecord($tblPref,$valPref,$optParams,$dataPref,$checkResult);
                
            }else{
                //echo PHP_EOL.">Found, returning ID.";
                return $checkResult;
            }
        }else{
            if($optParams->dontInsert==0){
                //echo PHP_EOL.">Not found, inserting.";
                $lastInsertId = $this->insertRecord($tblPref,$valPref,$optParams,$dataPref,$seedPref);
            }
        }
          return $lastInsertId;
    }

    public function updateRecord($tblPref,$valPref,$optParams,$dataPref,$id){
        //print_r($valPref);
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        global $updateArray;
        $query = "UPDATE ".$tblPref->tableName." SET ";
        $i=0;
        foreach($dataPref->colList as $columnName){
            $query.= $columnName ." = '". $dataPref->valList[$i]."',";
            $i++;
        }
        $query = substr($query, 0, -1);
        $query .= " WHERE ".$tblPref->idCol." = ".$id.";";
        //echo PHP_EOL."".$query;
        $arrResult   = $pdo->query($query);
        if(isset($updateArray[$tblPref->tableName])){
            $updateArray[$tblPref->tableName] += 1;
        }else{
            $updateArray[$tblPref->tableName]=1;
        }
        return $id;
    }

    public function insertRecord($tblPref,$valPref,$optParams,$dataPref,$seedPref){
        global $insertArray;
        global $failedInserts;
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $query = "INSERT INTO ".$tblPref->tableName." ( ";
        foreach($dataPref->colList as $columnName){
            $query.="`".$columnName."`,";
        }
        if($optParams->isSeed==1){
            //print_r($seedPref);
            $seedNumber = $this->getSeed($seedPref,$valPref->customerNo);
            $query .= "`".$seedPref->seedCol."`,";
        }
        $query = substr($query, 0, -1);
        $query.=") VALUES (";
        foreach($dataPref->valList as $value){
            $query .= "'".$value."',";
        }    
        if($optParams->isSeed==1){
            $seedNumber = $this->getSeed($seedPref,$valPref->customerNo);
            $query .= ($seedNumber[$seedPref->idCol]+1).",";
        }
        $query = substr($query, 0, -1);
        $query.=");";
        //echo $query;
        $arrResult   = $pdo->query($query);
        $lastInsertId = $pdo->lastInsertId();
        if($lastInsertId!=0){
            if($optParams->isSeed==1){
                $this->updateSeed($seedPref,$valPref->customerNo);
            }
            if(isset($insertArray[$tblPref->tableName])){
                $insertArray[$tblPref->tableName] += 1;
            }else{
                $insertArray[$tblPref->tableName]=1;
            }
        }else{
           if(isset($failedInserts[$tblPref->tableName])){
                $failedInserts[$tblPref->tableName] += 1;
            }else{
                $failedInserts[$tblPref->tableName]=1;
            }
        }
        //print_r($failedInserts);
        return $lastInsertId;
    }

    public function cleanNonPritableChars($input) {
        if(is_array($input)){
            foreach($input as &$string){
                $string = cleanNonPritableChars($string);
            }
        }else{
            $input = str_replace(array('[\', \']'), '', $input);
            $input = str_replace("'", "'", $input);
            $input = str_replace('"', "", $input);
            $input = str_replace(' ', "", $input);
            $input = preg_replace('/\[.*\]/U', '', $input);
            $input = preg_replace('/&(amp;| &nbsp;)?#?[a-z0-9]+;/i', '-', $input);
            $input = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $input);
            $input = preg_replace(array('/[^a-z0-9-.,(&)\']/i'), ' ', $input);
        }
        return $input;
    }

    public function getSeed($seedPref,$customerNo){
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $query = "SELECT ".$seedPref->idCol." FROM ".$seedPref->seedTable." WHERE customerNo = ".$customerNo.";";
        //echo $query;
        $arrResult   = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);
        return $arrResult;
    }

    public function updateSeed($seedPref,$customerNo){
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $query = "UPDATE ".$seedPref->seedTable." SET ".$seedPref->idCol."=".$seedPref->idCol."+1 WHERE customerNo=".$customerNo.";";
        $arrResult   = $pdo->query($query);
        //echo $query.PHP_EOL;
    }
    public function getNextId($tblPref){
        $today = date('Y-m-d H:i:s');
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $query = "SELECT max(".$tblPref->idCol.") as ".$tblPref->idCol." FROM ".$tblPref->tableName.";";
        $result = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);
        if($result[$tblPref->idCol]==NULL){
            $retId = 1;
        }else{
            $retId = $result[$tblPref->idCol] + 1;
        }
        return $retId;
    }
}
?>