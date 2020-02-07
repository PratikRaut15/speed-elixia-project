<?php
include_once '../../lib/system/Validator.php';
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/model/VONation.php';
include_once '../../lib/system/Date.php';


class CityManager extends VersionedManager{

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
        $Query = "SELECT * FROM `district` where customerno=%d AND isdeleted=0";
        $nationsQuery = sprintf($Query, $this->_Customerno);
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
                $Nation->nationid = $row['nationid'];
                $Nation->address = $row['address'];
                $nations[] = $Nation;
            }
            return $nations;
        }
        return null;
    }

    public function get_all_cities($userid)
    {
        $nations = Array();
        $Query = "SELECT *, city.timestamp, city.name as cityname, district.name as districtname, state.name as statename, nation.name as nationname, user.realname FROM `city`
            INNER JOIN district ON district.districtid = city.districtid
            INNER JOIN state ON state.stateid = district.stateid
            INNER JOIN nation ON nation.nationid = state.nationid
            INNER JOIN user on user.userid = city.userid
            where ";
        if($_SESSION["roleid"] == '3')
        {
            $Query.= " city.districtid = %d AND ";
        }
        elseif($_SESSION["roleid"] == '2')
        {
            $Query.= " district.stateid = %d AND ";
        }
        else
        {
            $Query.= " state.nationid = %d AND ";
        }
        $Query.= " city.customerno=%d AND city.isdeleted=0 ORDER BY city.timestamp DESC";
        $cityQuery = sprintf($Query,$_SESSION["heirarchy_id"], $this->_Customerno);
        $this->_databaseManager->executeQuery($cityQuery);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $Nation = new VONation();
                $Nation->cityid = $row['cityid'];
                $Nation->districtid = $row['districtid'];
                //$Nation->stateid = $row['stateid'];
                $Nation->cityname = $row['cityname'];
                $Nation->districtname = $row['districtname'];
                $Nation->statename = $row['statename'];
                $Nation->nationname = $row['nationname'];
                $Nation->realname = $row['realname'];
                $Nation->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['timestamp']));
                $Nation->code = $row['code'];
                //$Nation->nationid = $row['nationid'];
                $Nation->address = $row['address'];
                $nations[] = $Nation;
            }
            return $nations;
        }
        return null;
    }

    public function get_all_cities_nation($nationid)
    {
        $nations = Array();
        $Qnation = "";
        $Qnation = sprintf(" state.nationid = %d AND ",$nationid);
        $Query = "SELECT city.name as cityname, city.cityid FROM `city`
            INNER JOIN district ON district.districtid = city.districtid
            INNER JOIN state ON state.stateid = district.stateid
            where ";
            $Query.= $Qnation;
        $Query.= " city.customerno=%d AND city.isdeleted=0 ORDER BY city.timestamp DESC";
        $cityQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($cityQuery);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $Nation = new VONation();
                $Nation->cityid = $row['cityid'];
                $Nation->name = $row['cityname'];
                $nations[] = $Nation;
            }
            return $nations;
        }
        return null;
    }

    public function get_district($districtid)
    {
        $Query = "SELECT * FROM `district` where customerno=%d AND districtid=%d AND isdeleted=0";
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
            }
            return $Nation;
        }
        return null;
    }

    public function get_city($cityid)
    {
        $Query = "SELECT district.stateid, state.nationid, city.cityid, city.districtid, city.name, city.code, city.address FROM `city` INNER JOIN district ON district.districtid = city.districtid INNER JOIN state ON state.stateid = district.stateid where city.customerno=%d AND city.cityid=%d AND city.isdeleted=0";
        $nationsQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($cityid));
        $this->_databaseManager->executeQuery($nationsQuery);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $Nation = new VONation();
                $Nation->cityid = $row['cityid'];
                $Nation->districtid = $row['districtid'];
                $Nation->name = $row['name'];
                $Nation->code = $row['code'];
                $Nation->address = $row['address'];
                $Nation->stateid = $row['stateid'];
                $Nation->nationid = $row['nationid'];
            }
            return $Nation;
        }
        return null;
    }

    public function add_city($name, $code, $districtid, $address,$userid)
    {
        $Query = "INSERT INTO `city` (districtid,code,address,name,customerno,userid,isdeleted,timestamp) VALUES (%d,'%s','%s','%s','%d','%d','0','%s')";
        $date = new Date();
        $todays = $date->MySQLNow();
        $SQL = sprintf($Query, Sanitise::Long($districtid), Sanitise::String($code), Sanitise::String($address), Sanitise::String($name), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($todays));
        $this->_databaseManager->executeQuery($SQL);
        $status = "ok";
        return $status;
    }


    public function edit_city($cityid, $districtid, $name, $code, $stateid, $address, $userid)
    {

        $Query = "UPDATE `city` SET `name` = '%s',`code` = '%s',`address` = '%s',`districtid`=%d,userid=%d WHERE cityid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::String($name), Sanitise::String($code), Sanitise::String($address), Sanitise::Long($districtid), Sanitise::Long($userid), Sanitise::Long($cityid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $status = "ok";
        return $status;
    }

    public function DeleteCity($cityid, $userid)
    {
        $Query = "UPDATE `city` SET isdeleted=1,userid=%d WHERE cityid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($cityid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }


}
