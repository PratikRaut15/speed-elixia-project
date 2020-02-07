<?php

/**
  include_once '../../lib/system/Validator.php';
  include_once '../../lib/system/DatabaseTMSManager.php';
  include_once '../../lib/system/VersionedManager.php';
  include_once '../../lib/system/Sanitise.php';
  include_once '../../lib/model/VOGroup.php';
  include_once '../../lib/model/VOVehicle.php';
  include_once '../../lib/system/Date.php';
 * 
 */
require_once '../../lib/system/DatabaseHierarchyManager.php';

class HierarchyManager extends DatabaseHierarchyManager {

    function __construct($customerno, $userid) {
        // Constructor.
        parent::__construct($customerno, $userid);
        $this->customerno = $customerno;
        $this->userid = $userid;
        $this->todaysdate = date("Y-m-d H:i:s");
    }

    // <editor-fold defaultstate="collapsed" desc="GET_ALL_ROLES">

    public function getAllRoles($objRole) {
        $arrResult = null;
        try {
            $pdo = $this->CreatePDOConn();
            //Prepare parameters
            $sp_params = "'" . $objRole->roleid . "'"
                    . ",'" . $objRole->parentroleid . "'"
                    . ",'" . $objRole->moduleid . "'"
                    . ",'" . $objRole->customerno . "'";
            $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_ROLES, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
            $this->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, ($ex), $this->userid, constants::Maintenance, __FUNCTION__);
        }
        return $arrResult;
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="ADD ROLE">
    public function insertRole($objRole) {
        $currentroleid = 0;
        try {
            //Prepare parameters
            $sp_params = "'" . $objRole->rolename . "'"
                    . ",'" . $objRole->parentroleid . "'"
                    . "," . $objRole->moduleid . ""
                    . "," . $objRole->customerno . ""
                    . ",'" . $this->todaysdate . "'"
                    . ",'" . $this->userid . "'"
                    . "," . "@currentroleid";
            $query = $this->PrepareSP(constants::SP_INSERT_ROLE, $sp_params);
            $queryResult = $this->executeQuery($query);
            $outputVars = $this->executeQuery('SELECT @currentroleid');
            $result = mysql_fetch_assoc($outputVars);
            //print_r($result);
            $currentroleid = $result["@currentroleid"];
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, constants::Maintenance, __FUNCTION__);
        }
        return $currentroleid;
    }

    //</editor-fold>
    // <editor-fold defaultstate="collapsed" desc="UPDATE ROLE">
    public function updateRole($objRole) {
        $noOfRowAffected = 0;
        try {
            //Prepare parameters
            $sp_params = "'" . $objRole->roleid . "'"
                    . ",'" . $objRole->parentroleid . "'"
                    . ",'" . $objRole->rolename . "'"
                    . "," . $objRole->moduleid . ""
                    . "," . $objRole->customerno . ""
                    . ",'" . $this->todaysdate . "'"
                    . ",'" . $this->userid . "'";
            echo $query = $this->PrepareSP(constants::SP_UPDATE_ROLE, $sp_params);
            $queryResult = $this->executeQuery($query);
            $noOfRowAffected = $this->get_affectedRows();
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, constants::Maintenance, __FUNCTION__);
        }
        return $noOfRowAffected;
    }

    //</editor-fold>
    // <editor-fold defaultstate="collapsed" desc="DELETE ROLE">
    public function deleteRole($objRole) {
        $noOfRowAffected = 0;
        try {
            //Prepare parameters
            $sp_params = "'" . $objRole->roleid . "'"
                    . "," . $objRole->customerno . ""
                    . ",'" . $this->todaysdate . "'"
                    . ",'" . $this->userid . "'";
            $query = $this->PrepareSP(constants::SP_DELETE_ROLE, $sp_params);
            $queryResult = $this->executeQuery($query);
            $noOfRowAffected = $this->get_affectedRows();
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, constants::Maintenance, __FUNCTION__);
        }
        return $noOfRowAffected;
    }

    //</editor-fold>
    // <editor-fold defaultstate="collapsed" desc="GET_MAINTENANCE_TRANSACTION_TYPES">

    public function getTransactionTypes($objRole) {
        $arrResult = null;
        try {
            $pdo = $this->CreatePDOConn();
            //Prepare parameters
            $sp_params = "'" . $objRole->customerno . "'";
            $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_TRANSACTION_TYPES, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
            $this->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, ($ex), $this->userid, constants::Maintenance, __FUNCTION__);
        }
        return $arrResult;
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="GET_MAINTENANCE_TRANSACTION_TYPES">

    public function getTransactionConditions($objRole) {
        $arrResult = null;
        try {
            $pdo = $this->CreatePDOConn();
            //Prepare parameters
            $sp_params = "'" . $objRole->transactiontypeid . "'"
                    . ",'" . $objRole->conditionname . "'"
                    . ",'" . $objRole->customerno . "'";

            $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_TRANSACTION_CONDITIONS, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
            $this->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, ($ex), $this->userid, constants::Maintenance, __FUNCTION__);
        }
        return $arrResult;
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="ADD RULES">
    public function insertRule($objRole) {
        $currentruleid = 0;
        try {
            //Prepare parameters
            $sp_params = "'" . $objRole->conditionid . "'"
                    . ",'" . $objRole->minvalue . "'"
                    . ",'" . $objRole->maxvalue . "'"
                    . ",'" . $objRole->sequnceno . "'"
                    . ",'" . $objRole->approverid . "'"
                    . ",'" . $objRole->customerno . "'"
                    . ",'" . $this->todaysdate . "'"
                    . ",'" . $objRole->userid . "'"
                    . "," . "@currentruleid";
            $query = $this->PrepareSP(constants::SP_INSERT_TRANSACTION_RULES, $sp_params);
            $queryResult = $this->executeQuery($query);
            $outputVars = $this->executeQuery('SELECT @currentruleid');
            $result = mysql_fetch_assoc($outputVars);
            $currentruleid = $result["@currentruleid"];
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, constants::Maintenance, __FUNCTION__);
        }
        return $currentruleid;
    }

    //</editor-fold>
    // <editor-fold defaultstate="collapsed" desc="GET_MAINTENANCE_TRANSACTION_RULES">

    public function getTransactionRules($objRole) {
        $arrResult = null;
        try {
            $pdo = $this->CreatePDOConn();
            //Prepare parameters
            $sp_params = "'" . $objRole->customerno . "'"
                    . ",'" . $objRole->ruleid . "'"
                    . ",'" . $objRole->conditionid . "'";
            $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_TRANSACTION_RULES, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
            $this->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, ($ex), $this->userid, constants::Maintenance, __FUNCTION__);
        }
        return $arrResult;
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="DELETE RULE">
    public function deleteRule($objRole) {
        $noOfRowAffected = 0;
        try {
            //Prepare parameters
            $sp_params = "'" . $objRole->ruleid . "'"
                    . "," . $objRole->customerno . ""
                    . ",'" . $this->todaysdate . "'"
                    . ",'" . $this->userid . "'";
            $query = $this->PrepareSP(constants::SP_DELETE_TRANSACTION_RULE, $sp_params);
            $queryResult = $this->executeQuery($query);
            $noOfRowAffected = $this->get_affectedRows();
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, constants::Maintenance, __FUNCTION__);
        }
        return $noOfRowAffected;
    }

    //</editor-fold>

    public function updateRule($objRole) {
        $noOfRowAffected = 0;
        try {
            //Prepare parameters
            $sp_params = "'" . $objRole->ruleid . "'"
                    . ",'" . $objRole->minvalue . "'"
                    . ",'" . $objRole->maxvalue . "'"
                    . "," . $objRole->customerno . ""
                    . "," . $objRole->sequence . ""
                    . ",'" . $this->todaysdate . "'"
                    . ",'" . $this->userid . "'";
            $query = $this->PrepareSP(constants::SP_UPDATE_TRANSACTION_RULE, $sp_params);
            $queryResult = $this->executeQuery($query);
            $noOfRowAffected = $this->get_affectedRows();
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, constants::Maintenance, __FUNCTION__);
        }
        return $noOfRowAffected;
    }

    // <editor-fold defaultstate="collapsed" desc="GET_USERS_FOR_PARENT_ROLE">

    public function getUsersForParentRole($objRole) {
        $arrResult = null;
        $is_higheruser = 0;
        try {
            $pdo = $this->CreatePDOConn();
            //Prepare parameters
            $sp_params = "'" . $objRole->roleid . "'"
                    . ",'" . $objRole->moduleid . "'"
                    . ",'" . $objRole->customerno . "'"
                    . ",'" . $is_higheruser . "'"
            ;
            $this->PrepareSP(constants::SP_GET_USERS_FORPARENTROLE, $sp_params);
            $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_USERS_FORPARENTROLE, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
            $this->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, ($ex), $this->userid, constants::Maintenance, __FUNCTION__);
        }
        return $arrResult;
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="GET_USER_GROUPS">

    public function getUserGroups($objRole) {
        $arrResult = null;
        try {
            $pdo = $this->CreatePDOConn();
            //Prepare parameters
            $sp_params = "'" . $objRole->roleid . "'"
                    . ",'" . $objRole->moduleid . "'"
                    . ",'" . $objRole->customerno . "'";
            $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_USER_GROUPS, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
            $this->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, ($ex), $this->userid, constants::Maintenance, __FUNCTION__);
        }
        return $arrResult;
    }

    // </editor-fold>

    public function getHigherUsersForRoleid($objRole) {
        /*
          $arrResult = null;
          try {
          $pdo = $this->CreatePDOConn();
          //Prepare parameters
          $sp_params = "'" . $objRole->roleid . "'"
          . ",'" . $objRole->moduleid . "'"
          . ",'" . $objRole->customerno . "'"
          ;
          $this->PrepareSP(constants::SP_GET_HIGHER_USERS_FOR_ROLE, $sp_params);
          $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_HIGHER_USERS_FOR_ROLE, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
          $this->ClosePDOConn($pdo);
          } catch (Exception $ex) {
          $log = new Log();
          $log->createlog($this->customerno, ($ex), $this->userid, constants::Maintenance, __FUNCTION__);
          }
          return $arrResult;
          }
         * 
         */
        $arrResult = null;
        $is_higheruser = 1;
        try {
            $pdo = $this->CreatePDOConn();
            //Prepare parameters
            $sp_params = "'" . $objRole->roleid . "'"
                    . ",'" . $objRole->moduleid . "'"
                    . ",'" . $objRole->customerno . "'"
                    . ",'" . $is_higheruser . "'"
            ;
            $this->PrepareSP(constants::SP_GET_USERS_FORPARENTROLE, $sp_params);
            $arrResult = $pdo->query($this->PrepareSP(constants::SP_GET_USERS_FORPARENTROLE, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
            $this->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, ($ex), $this->userid, constants::Maintenance, __FUNCTION__);
        }
        return $arrResult;
    }
    
    public function getmenuslist_new($customerno) {  // get parent -child - subchild
        $data = array();
        $Query = "SELECT parent_menuid,menuid,menuname,moduleid,page,sequenceno,page FROM `menu_master` where customerno=0 OR customerno=".$customerno." AND isdeleted=0";
        $maintananceQuery = sprintf($Query);
        $this->executeQuery($maintananceQuery);
        if($this->get_rowCount() > 0){
            while($row = $this->get_nextRow()){
                $data[] = array(
                    'id' => $row['menuid'],
                    'text' => $row['menuname'],
                    'parentid' => $row['parent_menuid'],
                    'page' => $row['page']
                );
            }
        }
        // parent id 
        $parentarr = array();
        $i=0;
        foreach($data as $key=>$item){
            if($item['parentid']=='0'){
                $parentarr[$item['id']] = $item;
                $childrenarr = $this->get_menu_by_id($item['id']);
                $rowdata=array();
                foreach($childrenarr as $key=>$row){
                   $subchildnarr = $this->get_menu_by_id($row['id']);
                       $subchild= $subchildnarr;
                       $rowdata[] = array(
                        'id' => $row['id'],
                        'text' => $row['text'],
                        'parentid' => $row['parentid'],
                        'page' => $row['page'],
                        'children'=> $subchildnarr
                        );
                       $parentarr[$item['id']]['children'] = $rowdata;
                }
            }
        }
       
        $result = (array_chunk($parentarr, 1));
        $datalatest = array();
        for ($i = 0; $i < count($result); $i++) {
            $datalatest[] = array_shift($result[$i]);
        }
        $parentarr = array(
             'id'=> 0,
            'text'=> "All Menus",
            'children'=>$datalatest
        );
        
        return $parentarr; 
    }
    
    public function get_menu_by_id($parent){
        $data = array();
        $Query = "SELECT parent_menuid,menuid,menuname,moduleid,page,sequenceno FROM `menu_master` where parent_menuid=".$parent." AND isdeleted=0";
        $maintananceQuery = sprintf($Query);
        $this->executeQuery($maintananceQuery);
        $itemsByReference = array();
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $data[] = array(
                    'id' => $row['menuid'],
                    'text' => $row['menuname'],
                    'parentid' => $row['parent_menuid'],
                    'page' => $row['page']
                );
            }
        }
        return $data;
    }
    
    
    public function get_menu_by_id_check($parent,$ids){
        $data = array();
        $ids = implode(',', $ids);
        $Query = "SELECT parent_menuid,menuid,menuname,moduleid,page,sequenceno FROM `menu_master` where parent_menuid=".$parent." AND menuid IN(".$ids.") AND isdeleted=0";
        $maintananceQuery = sprintf($Query);
        $this->executeQuery($maintananceQuery);
        $itemsByReference = array();
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $data[] = array(
                    'id' => $row['menuid'],
                    'text' => $row['menuname'],
                    'parentid' => $row['parent_menuid'],
                    'page' => $row['page']
                );
            }
        }
        return $data;
    }
    
    
    
    public function getcustomerdetailmenu($userid){
        $data = array();
        $menuids = array();
        $datamenu = array();
        $Query = "SELECT umm.isactive,umm.add_permission, umm.edit_permission, umm.delete_permission,u.userid,mm.menuid,mm.menuname,mm.parent_menuid,mm.moduleid, mm.page FROM user as u  "
                . " inner join  `usermenu_mapping` as umm on u.userid=umm.userid AND umm.isactive=1 AND umm.isdeleted=0 "
                . " inner join `menu_master` as mm ON  umm.menuid = mm.menuid "
                . " where  u.isdeleted=0 AND u.userid=" . $userid;
        $MenuQuery = sprintf($Query);
        $this->executeQuery($MenuQuery);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $data[] = array(
                    'id' => $row['menuid'],
                    'text' => $row['menuname'],
                    'parentid' => $row['parent_menuid'],
                    'page' => $row['page'],
                    'add_permission' => $row['add_permission'],
                    'edit_permission' => $row['edit_permission'],
                    'delete_permission' => $row['delete_permission']
                    
                );
                $menuids[] = $row['menuid'];
                
                $datamenu[$row['menuid']] = array(
                    'id' => $row['menuid'],
                    'add_permission' => $row['add_permission'],
                    'edit_permission' => $row['edit_permission'],
                    'delete_permission' => $row['delete_permission']
                );
                
                
            }
        // parent id 
        $parentarr = array();
        $i=0;
        foreach($data as $key=>$item){
            if($item['parentid']=='0'){
                $parentarr[$item['id']] = $item;
                $childrenarr = $this->get_menu_by_id_check($item['id'],$menuids);
                $rowdata=array();
                foreach($childrenarr as $key=>$row){
                   $subchildnarr = $this->get_menu_by_id_check($row['id'],$menuids);
                       $subchild= $subchildnarr;
                       $rowdata[] = array(
                        'id' => $row['id'],
                        'text' => $row['text'],
                        'parentid' => $row['parentid'],
                        'page' => $row['page'],
                        'children'=> $subchildnarr
                        );
                       $parentarr[$item['id']]['children'] = $rowdata;
                }
            }
        }
        $result = (array_chunk($parentarr, 1));
        $datalatest = array();
        for ($i = 0; $i < count($result); $i++) {
            $datalatest[] = array_shift($result[$i]);
        }
        $parentarr = array(
             'id'=> 0,
            'text'=> "All Menus",
            'children'=>$datalatest,
            'getids' =>$menuids,
            'menuconfig'=>$datamenu
        );
        
        return $parentarr; 
        }
    }

}
