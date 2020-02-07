<?php
include_once '../../lib/system/Validator.php';
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/model/VONation.php';
include_once '../../lib/system/Date.php';


class NationManager extends VersionedManager{

    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function get_all_nations()
    {
        $nations = Array();
        $Query = "SELECT *, user.realname FROM `nation` INNER JOIN user ON user.userid = nation.userid where nation.customerno=%d AND nation.isdeleted=0 ORDER BY nation.timestamp DESC";
        $nationsQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($nationsQuery);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $Nation = new VONation();
                $Nation->nationid = $row['nationid'];
                $Nation->name = $row['name'];
                $Nation->realname = $row['realname'];
                $Nation->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['timestamp']));
                $nations[] = $Nation;
            }
            return $nations;
        }
        return null;
    }

    public function get_nation($nationid)
    {
        $Query = "SELECT * FROM `nation` where customerno=%d AND nationid=%d AND isdeleted=0";
        $nationsQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($nationid));
        $this->_databaseManager->executeQuery($nationsQuery);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $Nation = new VONation();
                $Nation->nationid = $row['nationid'];
                $Nation->name = $row['name'];
                $Nation->code = $row['code'];
                $Nation->address = $row['address'];
            }
            return $Nation;
        }
        return null;
    }

    public function add_nation($name, $code, $address,$userid)
    {
        $Query = "INSERT INTO `nation` (code,address,name,customerno,userid,isdeleted,timestamp) VALUES ('%s','%s','%s','%d','%d','0','%s')";
        $date = new Date();
        $todays = $date->MySQLNow();
        $SQL = sprintf($Query, Sanitise::String($code), Sanitise::String($address), Sanitise::String($name), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($todays));
        $this->_databaseManager->executeQuery($SQL);
        $nationid = $this->_databaseManager->get_insertedId();
        if($nationid != ''){
            $status = 'ok';
        }
        else if($nationid == ''){
            $status = 'notok';
        }
        return $status;

    }


    public function edit_nation($nationid, $name, $code, $address, $userid)
    {

        $Query = "UPDATE `nation` SET `name` = '%s',`code` = '%s',`address` = '%s',userid=%d WHERE nationid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::String($name), Sanitise::String($code), Sanitise::String($address), Sanitise::Long($userid), Sanitise::Long($nationid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
             $status = 'ok';
        return $status;
    }

    public function DeleteNation($nationid, $userid)
    {
        $Query = "UPDATE `nation` SET isdeleted=1,userid=%d WHERE nationid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($nationid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }


}
