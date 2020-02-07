<?php
include_once '../../lib/system/Validator.php';
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/model/VONation.php';
include_once '../../lib/system/Date.php';


class StateManager extends VersionedManager{

    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function get_all_nations($userid)
    {
        $nations = Array();
        $Query = "SELECT * FROM `nation` where customerno=%d AND isdeleted=0";
        $nationsQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($nationsQuery);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $Nation = new VONation();
                $Nation->nationid = $row['nationid'];
                $Nation->name = $row['name'];
                $nations[] = $Nation;
            }
            return $nations;
        }
        return null;
    }

    public function get_all_states($userid)
    {
        $nations = Array();
        $Query = "SELECT *, state.stateid, state.name as statename, nation.name as nationname, user.realname, state.timestamp FROM `state`
            INNER JOIN user ON user.userid = state.userid
            INNER JOIN nation ON nation.nationid = state.nationid
            where state.nationid = %d AND state.isdeleted=0 AND state.customerno=%d ORDER BY state.timestamp DESC";
        $nationsQuery = sprintf($Query,$_SESSION["heirarchy_id"], $this->_Customerno);
        $this->_databaseManager->executeQuery($nationsQuery);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $Nation = new VONation();
                $Nation->stateid = $row['stateid'];
                $Nation->statename = $row['statename'];
                $Nation->code = $row['code'];
                $Nation->nationid = $row['nationid'];
                $Nation->address = $row['address'];
                $Nation->realname = $row['realname'];
                $Nation->nation = $row['nationname'];
                $Nation->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['timestamp']));
                $nations[] = $Nation;
            }
            return $nations;
        }
        return null;
    }

    public function get_all_stateslist()
    {
        $Query = "SELECT * from state where customerno = %d";
        $stateQuery = sprintf($Query,$this->_Customerno);
        $this->_databaseManager->executeQuery($stateQuery);
        $states = array();
        if ($this->_databaseManager->get_rowCount() > 0)
        {


            while ($row = $this->_databaseManager->get_nextRow())
            {
                $state = new VONation();
                $state->stateid = $row['stateid'];
                $state->statename = $row['name'];
                $states[] = $state;
            }
            return $states;
        }
        return null;
    }


    public function get_state($stateid)
    {
        $Query = "SELECT * FROM `state` where customerno=%d AND stateid=%d AND isdeleted=0";
        $nationsQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($stateid));
        $this->_databaseManager->executeQuery($nationsQuery);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $Nation = new VONation();
                $Nation->stateid = $row['stateid'];
                $Nation->name = $row['name'];
                $Nation->code = $row['code'];
                $Nation->nationid = $row['nationid'];
                $Nation->address = $row['address'];
            }
            return $Nation;
        }
        return null;
    }

    public function add_state($name, $code, $nationid, $address,$userid)
    {
        $Query = "INSERT INTO `state` (code,address,name,nationid,customerno,userid,isdeleted,timestamp) VALUES ('%s','%s','%s','%d','%d','%d','0','%s')";
        $date = new Date();
        $todays = $date->MySQLNow();
        $SQL = sprintf($Query, Sanitise::String($code), Sanitise::String($address), Sanitise::String($name), Sanitise::Long($nationid), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($todays));
        $this->_databaseManager->executeQuery($SQL);
        $stateid = $this->_databaseManager->get_insertedId();
            $status = 'ok';
        return $status;

    }


    public function edit_state($stateid, $name, $code, $nationid, $address, $userid)
    {

        $Query = "UPDATE `state` SET `name` = '%s',`code` = '%s',`address` = '%s',`nationid`=%d,userid=%d WHERE stateid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::String($name), Sanitise::String($code), Sanitise::String($address), Sanitise::Long($nationid), Sanitise::Long($userid), Sanitise::Long($stateid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
            $status = 'ok';
        return $status;
    }

    public function DeleteState($stateid, $userid)
    {
        $Query = "UPDATE `state` SET isdeleted=1,userid=%d WHERE stateid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($stateid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }


}
