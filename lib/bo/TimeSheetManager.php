<?php


if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../../";
}
require_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';


class TimeSheetManager extends DatabaseManager
{   
    function __construct(){
        // Constructor.
        parent::__construct();
    }

//Kartik Joshi
    public function getCustomers($term){
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $queryCallSP = "CALL " . speedConstants::SP_GET_CUSTOMERS . "('" . $term . "')";
        $arrResult   = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        foreach ($arrResult as &$record) {
            $record['value'] = $record['customerno'] . " - " . $record['customercompany'];
        }
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }
    
    public function getProducts() {
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $today = date("Y-m-d H:i:s");

        $queryCallSP       = "CALL " . speedConstants::SP_GET_PRODUCTS;
        
        $arrResult         = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);

        return $arrResult;
    }

    public function fetchTeam($department,$teamId) {
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $today = date("Y-m-d H:i:s");
        $sp_params = "'".$department."'"
                    .",'".$teamId."'";

        $queryCallSP       = "CALL " . speedConstants::SP_GET_TASK_TEAM_LIST . "($sp_params)";
        
        $arrResult         = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);

        return $arrResult;
    }

    public function addMember($roleId,$teamId,$departmentId){
        $today = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "SELECT trId FROM teamRoles WHERE roleId = ".$roleId ." AND teamId = ".$teamId.";";
        $result = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC); 
        $found=0;
        if((isset($result)&&$result['trId']>0)||count($result)>0){
            $found = 1;
            return $found;
        }else{
            $found = 0;
        }
        $queryCallSP = "INSERT INTO teamRoles(`departmentId`,`roleId`,`teamId`,`createdBy`,`createdOn`) Values('".$departmentId ."','".$roleId."','".$teamId."','".GetLoggedInUserId()."','".$today."')";
        $result = $pdo->query($queryCallSP);    
        return $found;
    }

    public function insertTask($obj) {
        $today = date('Y-m-d H:i:s');
        $obj->task_name = Sanitise::String($obj->task_name);
        $obj->task_description = Sanitise::String($obj->task_description);
        $obj->status = Sanitise::String($obj->status);
        $obj->estimated_time = Sanitise::String($obj->estimated_time);
        $obj->department = Sanitise::String($obj->department);
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $sp_params         = "'" . $obj->task_name . "'" . 
                             ",'" . $obj->task_description . "'" .
                             ",'" . $obj->status . "'" .
                             ",'" . $obj->estimated_time . "'" .
                             ",'" . $obj->department . "'" .
                             ",'" . GetLoggedInUserId() . "'" .
                             ",'" . $today . "'" .
                             ",@lastInsertId";
                              
        $queryCallSP       = "CALL " . speedConstants::SP_INSERT_TASK . "($sp_params)";
        $result            = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @lastInsertId";
        $outputResult      = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $returnArray['taskId'] = $outputResult['@lastInsertId'];
        $db->ClosePDOConn($pdo);
        return $returnArray;
    }

    public function insertMemberMapping($userList,$taskId){
        $today = date('Y-m-d H:i:s');
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        if(is_array($userList)&&count($userList)>0){
            foreach($userList as $user){
                if($user!=0){
                    $queryCallSP = "INSERT INTO taskTeam(`taskId`,`trId`,`createdBy`,`createdOn`) Values(".$taskId.",".$user.",".GetLoggedInUserId().",'".$today."')";
                    $result = $pdo->query($queryCallSP);
                    $res = $result->rowCount();
                }
            }
        }
        return $res;
    }
    public function checkAndMapPeople($taskId,$teamId){
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $result = $this->fetchTeam($_SESSION['department'],$teamId);
        $trId = $result[0]['trId'];
        $queryCallSP = "SELECT count(ttId) as count from taskTeam where taskId = ".$taskId." AND trId=".$trId.";";
        $result = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        if($result['count']>0){
            return true;
        }
        $userList = array();
        $userList[] = $trId;
        $result = $this->insertMemberMapping($userList,$taskId);
        return $result;
    }
    public function insertProductMapping($productList,$taskId){
        $today = date('Y-m-d H:i:s');
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $query = "SELECT max(pMapId) as pMapId FROM taskProductMapping;";
        $result = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);
        if($result['pMapId']==NULL){
            $pMapId = 1;
        }else{
            $pMapId = $result['pMapId'] + 1;
        }
        $res = 0;
        if(is_array($productList)&&count($productList)>0){
            foreach($productList as $product){
                $queryCallSP = "INSERT INTO taskProductMapping(`taskId`,`pMapId`,`productId`) Values(".$taskId.",".$pMapId.",".$product.")";
                $result = $pdo->query($queryCallSP); 
                $res = $result->rowCount();
            }
        }
        $return = array();
        $return['pMapId'] = $pMapId;
        $return['rowsAffected'] = $res;
        return $return;
    }

    public function insertCustomerMapping($customerList,$taskId){
        $today = date('Y-m-d H:i:s');
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $query = "SELECT max(cMapId) as cMapId FROM taskCustomerMapping;";
        $result = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);
        if($result['cMapId']==NULL){
            $cMapId = 1;
        }else{
            $cMapId = $result['cMapId'] + 1;
        }
        $res = 0;
        if(is_array($customerList)&&count($customerList)>0){
            foreach($customerList as $customer){
                $queryCallSP = "INSERT INTO taskCustomerMapping(`taskId`,`cMapId`,`customerNo`) Values(".$taskId.",".$cMapId.",".$customer.")";
                $result = $pdo->query($queryCallSP);
                $res = $result->rowCount();
            }
        }
        $return = array();
        $return['cMapId'] = $cMapId;
        $return['rowsAffected'] = $res;
        return $return;
    }
    public function insertTaskMapping($taskId,$userTeam,$userList,$pMapId,$cMapId){
        $today = date('Y-m-d H:i:s');
        $db          = new DatabaseManager();
        $pdo         = $db->CreatePDOConnForTech();
        $res = array();
        foreach($userTeam as $k=>$user){
            $query = "INSERT INTO `taskMapping` (`taskId`,`teamId`,`trId`,`pMapId`,`cMapId`) VALUES (".$taskId.",".$user.",".$userList[$k].",".$pMapId.",".$cMapId.")";
            $result = $pdo->query($query);
            $tMapId = $pdo->lastInsertId();
            $res['rowsAffected'] = $result->rowCount();
            $res['tMapId'] = $tMapId;
        }
        return $res;
    }
    public function getTeamTasks($teamId=null,$taskId=null,$departmentId=null) {
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $today = date("Y-m-d H:i:s");

        $sp_params =   "'" . $teamId . "'"
                       .",'" . $taskId. "'"
                       .",'" . $departmentId. "'";
        
        $queryCallSP       = "CALL " . speedConstants::SP_GET_TEAM_TASKS. "($sp_params)";
        
        $arrResult         = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);

        return $arrResult;
    } 
    public function getTimesheetDetails ($tsId){
        $today = date('Y-m-d');
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "call ".speedConstants::SP_GET_TIMESHEET_DETAILS." (".$tsId.",".GetLoggedInUserId().");";
        $result = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);  
        return $result;
    }
    public function updateTimesheet($tMapId,$time,$teamId,$date){
        $today = date('Y-m-d H:i:s');
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "INSERT INTO timeSheet(`tMapId`,`time`,`date`,`teamId`,`createdBy`,`createdOn`) Values('".$tMapId."','".$time."','".$date."','".$teamId."','".$teamId."','".$today."')";
        $result = $pdo->query($queryCallSP);    
        $res = $result->rowCount();
        return $res;
    }
    public function closePreviousTask($tMapId,$teamId){
        //echo "closing previous task";
        $today = date('Y-m-d H:i:s');
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $res = array();
        $queryCallSP = "UPDATE taskMapping SET isClosed = 1 WHERE tMapId = ".$tMapId.";";
        $result = $pdo->query($queryCallSP);    
        $res['updateTaskMapping'] = $result->rowCount();
        return $res;
    }
    public function editTimesheet($tname,$tdesc,$tsId,$tMapId,$oldTaskId,$teamId,$time){
        $today = date('Y-m-d H:i:s');
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $res = array();
        $queryCallSP = "update timeSheet ts
                inner join taskMapping tm ON tm.tMapId = ".$tMapId." and tm.teamId = ts.teamId
                inner join task t ON t.taskId = tm.taskId
                SET t.taskName = '".$tname."',t.taskDesc= '".$tdesc."',
                        ts.tMapId = ".$tMapId.",
                        ts.time = '".$time."'
                WHERE ts.tsId = ".$tsId." AND ts.teamId = ".$teamId;
        //$queryCallSP = "UPDATE timeSheet SET tMapId = ".$tMapId.",time = '".$time."' WHERE tsId = ".$tsId." AND teamId = ".$teamId;
        $result = $pdo->query($queryCallSP);    
        $res['updateTimesheet'] = $result->rowCount();
        return $res;
    }
    public function searchTask($term,$teamId,$trId){
            $db          = new DatabaseManager();
            $pdo         = $db->CreatePDOConnForTech();
            
            $sp_params         = "'" .$term."'".
                                ",'" . $teamId . "'".
                                ",'" . $trId . "'" ;
                                  
            $queryCallSP       = "CALL " . speedConstants::SP_SEARCH_TASK . "($sp_params)";

            $result            = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
            $resultData = array();
            foreach($result as &$tasks){
                $tasks['value'] = $tasks['taskName'];
                $tasks['id'] = $tasks['taskId'];
            }
            return $result;
    }

    public function fetchTeamMembers($taskId){
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params =   "'" . $teamId . "'";
        
        $queryCallSP       = "CALL " . speedConstants::SP_GET_TIMESHEETS. "($sp_params)";
        
        $arrResult         = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        return $arrResult;
    }
function sum_the_time($time1, $time2) {
      $times = array($time1, $time2);
      $seconds = 0;
      foreach ($times as $time)
      {
        list($hour,$minute,$second) = explode(':', $time);
        $seconds += $hour*3600;
        $seconds += $minute*60;
        $seconds += $second;
      }
      $hours = floor($seconds/3600);
      $seconds -= $hours*3600;
      $minutes  = floor($seconds/60);
      $seconds -= $minutes*60;
      if($seconds < 9)
      {
      $seconds = "0".$seconds;
      }
      if($minutes < 9)
      {
      $minutes = "0".$minutes;
      }
        if($hours < 9)
      {
      $hours = "0".$hours;
      }
      return "{$hours}:{$minutes}:{$seconds}";
    }
    public function fetchTimeSheet($teamId,$date){
        //$date = date('Y-m-d');
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params =   "'" . $teamId . "'"
                      .",'" . $date . "'";
        
        $queryCallSP       = "CALL " . speedConstants::SP_GET_TIMESHEETS. "($sp_params)";
        
        $arrResult['data']         = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $total = 0;
        $lockedFlag = false;
        if(count($arrResult['data'])>0){
            foreach($arrResult['data'] as &$timesheet){
                //$timesheet['time']=floatval($timesheet['time']);
                //$timesheet['time'] .= ':00';
                //echo $timesheet['time'];
                //die();
                $total = $this->sum_the_time($total,$timesheet['time']);
                if($timesheet['status']=='Locked'){
                    $lockedFlag = true;
                }
            }
            $timeArr = explode(':', $total);
            $decTime = ($timeArr[0]) + ($timeArr[1]/60) ;
 

        }else{
            $result = $this->checkIfLocked($teamId,$date);

            //print_r($result);
            if($result['count']>0){
            $dayType = $result['dayCode'];
            $arrResult['dayType']=$dayType;
                $lockedFlag = true;
            }else{ 
                $lockedFlag = false;
            }
            $total = 0;
            $decTime=0;
        }
        $arrResult['status'] = 'Locked';
        if($lockedFlag==false){
            $arrResult['status'] = 'Unlocked';
        }
        $arrResult['total'] = $decTime;
        return $arrResult;
    }
    public function checkIfLocked($teamId, $date){
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        
        $queryCallSP       = "SELECT count(`ltId`) as count,dtm.dayCode 
                            FROM `locked_timesheets` 
                            LEFT JOIN day_type_master dtm ON dtm.dtId = locked_timesheets.dayType 
                            WHERE `teamId` = ".$teamId." AND `date` = date('".$date."');";
        
        $arrResult        = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        //print_r($arrResult);
        return $arrResult;
        // if($arrResult['count']>0){
        //     return true;
        // }else{
        //     return false;
        // }

    }
    public function lockTimesheet($obj){
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "SELECT dtId from day_type_master WHERE dayCode = '".$obj->dayType."' LIMIT 1;";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $dayType = $arrResult['dtId'];
        $queryCallSP = "INSERT INTO `locked_timesheets` (`teamId`,`date`,`dayType`,`duration`,`createdBy`,`createdOn`)
                        VALUES (".$obj->teamId.",'".$obj->date."',".$dayType.",".$obj->duration.",".GetLoggedInUserId().",'".date('Y-m-d')."')";
        $result = $pdo->query($queryCallSP);  
        $result = $result->rowCount();
        return $result;
    }

    public function getTeamRoles($departmentId){
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'".$departmentId."'";
        $queryCallSP = "CALL ".speedConstants::SP_GET_TEAM_ROLES."(".$sp_params.")";
        $result = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);    
        return $result;
    }
    public function getTeamList($term){
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "SELECT teamid, name as value, department_id FROM team WHERE name LIKE('".$term."%');";
        $result = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);  
        return $result;
    }
    public function getTime($teamId,$tsId,$date){
        $today = date("Y-m-d H:i:s");
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $queryCallSP = "select sum(`time`) as `sum` from timeSheet where teamId = ".$teamId." and `date` = date('".$date."')";
        if($tsId != null){
            $queryCallSP .=" and tsId <> ".$tsId.";";
        }
        $result = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);  
        return $result['sum'];
    }
}
