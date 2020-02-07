<?php
include_once '../../lib/system/Validator.php';
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/model/VONation.php';
include_once '../../lib/system/Date.php';


class DistrictManager extends VersionedManager{

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
        $Query = "SELECT * FROM `state` where customerno=%d AND isdeleted=0";
        $nationsQuery = sprintf($Query, $this->_Customerno);
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
                $nations[] = $Nation;
            }
            return $nations;
        }
        return null;
    }

    public function get_all_districts($userid)
    {
        $nations = Array();
        $Query = "SELECT *, district.timestamp, district.name as districtname, state.name as statename, nation.name as nationname, user.realname FROM `district`
            INNER JOIN state ON state.stateid = district.stateid
            INNER JOIN nation ON nation.nationid = state.nationid
            INNER JOIN user ON user.userid = district.userid
            where ";
        if($_SESSION["roleid"] == "2")
        {
            $Query.=" district.stateid = %d AND ";
        }
        else
        {
            $Query.=" state.nationid = %d AND ";
        }
        $Query.=" district.customerno=%d AND district.isdeleted=0 ORDER BY district.timestamp DESC";
        $nationsQuery = sprintf($Query,$_SESSION["heirarchy_id"], $this->_Customerno);
        $this->_databaseManager->executeQuery($nationsQuery);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $Nation = new VONation();
                $Nation->districtid = $row['districtid'];
                $Nation->stateid = $row['stateid'];
                $Nation->districtname = $row['districtname'];
                $Nation->statename = $row['statename'];
                $Nation->nationname = $row['nationname'];
                $Nation->realname = $row['realname'];
                $Nation->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['timestamp']));;
                $Nation->districtname = $row['districtname'];
                $Nation->code = $row['code'];
                //$Nation->nationid = $row['nationid'];
                $Nation->address = $row['address'];
                $nations[] = $Nation;
            }
            return $nations;
        }
        return null;
    }

    public function get_district($districtid)
    {
        $Query = "SELECT district.districtid, district.stateid,district.name, district.code, district.address, state.nationid FROM `district` INNER JOIN state ON state.stateid = district.stateid where district.customerno=%d AND district.districtid=%d AND district.isdeleted=0";
        $nationsQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($districtid));
        $this->_databaseManager->executeQuery($nationsQuery);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $Nation = new VONation();
                $Nation->districtid = $row['districtid'];
                $Nation->stateid = $row['stateid'];
                $Nation->name = $row['name'];
                $Nation->code = $row['code'];
                $Nation->address = $row['address'];
                $Nation->nationid = $row['nationid'];
            }
            return $Nation;
        }
        return null;
    }

    public function add_district($name, $code, $stateid, $address,$userid)
    {
        $Query = "INSERT INTO `district` (stateid,code,address,name,customerno,userid,isdeleted,timestamp) VALUES (%d,'%s','%s','%s','%d','%d','0','%s')";
        $date = new Date();
        $todays = $date->MySQLNow();
        $SQL = sprintf($Query, Sanitise::Long($stateid), Sanitise::String($code), Sanitise::String($address), Sanitise::String($name), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($todays));
        $this->_databaseManager->executeQuery($SQL);
        $status = 'ok';
        return $status;

    }


    public function edit_district($districtid, $stateid, $name, $code, $address, $userid)
    {

        $Query = "UPDATE `district` SET `name` = '%s',`code` = '%s',`address` = '%s',`stateid`=%d,userid=%d WHERE districtid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::String($name), Sanitise::String($code), Sanitise::String($address), Sanitise::Long($stateid), Sanitise::Long($userid), Sanitise::Long($districtid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $status = 'ok';
        return $status;

    }

    public function DeleteDistrict($districtid, $userid)
    {
        $Query = "UPDATE `district` SET isdeleted=1,userid=%d WHERE districtid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($districtid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }


}
