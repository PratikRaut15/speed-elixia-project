<?php
include_once '../../lib/system/Validator.php';
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/model/VONation.php';
include_once '../../lib/system/Date.php';


class DealerManager extends VersionedManager{

    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function get_all_dealers()
    {
        $nations = Array();
        $Query = "SELECT *, user.realname, dealer.timestamp FROM `dealer` INNER JOIN user ON user.userid = dealer.userid where dealer.customerno=%d AND dealer.isdeleted=0 ORDER BY dealer.name ASC";
        $cityQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($cityQuery);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $Nation = new VONation();
                $Nation->name = $row['name'];
                $Nation->phoneno = $row['phone'];
                $Nation->cellphone = $row['cellphone'];
                $Nation->dealerid = $row['dealerid'];
                $Nation->dealername = $row['name'];
                $Nation->vendor = $row['vendor'];
                $Nation->notes = $row['notes'];
                $Nation->address = $row['address'];
                $Nation->realname = $row['realname'];
                $Nation->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['timestamp']));
                $nations[] = $Nation;
            }
            return $nations;
        }
        return null;
    }


    public function get_city_from_group($groupid)
    {
        $Query = "SELECT cityid FROM `group` where customerno=%d AND groupid=%d AND isdeleted=0";
        $nationsQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($groupid));
        $this->_databaseManager->executeQuery($nationsQuery);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                return $row["cityid"];
            }
        }
        return null;
    }

    public function get_groupid_from_vehicleid($vehicleid)
    {
        $Query = "SELECT groupid FROM `vehicle` where customerno=%d AND vehicleid=%d AND isdeleted=0";
        $nationsQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($nationsQuery);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                return $row["groupid"];
            }
        }
        return null;
    }

    public function pull_dealers($form)
    {
        $data = "<option value='-1'>Select Dealer</option>";
        $groupid = $this->get_groupid_from_vehicleid($form["dealer_vehicle_id"]);
        $cityid = $this->get_city_from_group($groupid);
        $Query = "SELECT * FROM `dealer`
                where customerno=%d AND isdeleted=0 AND (cityid = %d OR cityid = '0')";    //condtion added for master dealer
        $cityQuery = sprintf($Query, $this->_Customerno, $cityid);
        $this->_databaseManager->executeQuery($cityQuery);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $length = strlen(decbin($row['vendor']));
                if($length==1){
                   $vendor = '00000'.decbin($row['vendor']);
                }
                else if($length==2){
                   $vendor = '0000'.decbin($row['vendor']);
                }
                else if($length==3){
                   $vendor = '000'.decbin($row['vendor']);
                }
                else if($length==4){
                   $vendor = '00'.decbin($row['vendor']);
                }
                else if($length==5){
                   $vendor = '0'.decbin($row['vendor']);
                }
                else if($length==6){
                   $vendor = decbin($row['vendor']);
                }
                $batt = substr($vendor, 0, 1);
                $tyre = substr($vendor, 1, 1);
                $service = substr($vendor, 2, 2);
                $vehicle = substr($vendor, 4, 1);
                $accessory = substr($vendor, 5, 1);
                if($form["vehicle_type"]=='0' && $batt=='1'){
                    $data.= "<option value='".$row['dealerid']."'>".$row['name']."</option>";
                }
                else if($form["vehicle_type"]=='1' && $tyre=='1'){
                    $data.= "<option value='".$row['dealerid']."'>".$row['name']."</option>";
                }
                else if(($form["vehicle_type"]=='2' && $service=='11') || ($form["vehicle_type"]=='2' && $service=='01') || ($form["vehicle_type"]=='2' && $service=='10')){
                    $data.= "<option value='".$row['dealerid']."'>".$row['name']."</option>";
                }
                else if($form["vehicle_type"]=='6' && $vehicle=='1'){
                    $data.= "<option value='".$row['dealerid']."'>".$row['name']."</option>";
                }
                else if($form["vehicle_type"]=='5' && $accessory=='1'){
                    $data.= "<option value='".$row['dealerid']."'>".$row['name']."</option>";
                }
            }
        }
        return $data;
    }

    public function pull_dealers_by_vehicle($vehicleid, $type)
    {
        $data = "<option value='-1'>Select Dealer</option>";
        $groupid = $this->get_groupid_from_vehicleid($vehicleid);
        $cityid = $this->get_city_from_group($groupid);
        //AND (cityid = %d OR cityid = '0')
        $Query = "SELECT * FROM `dealer`
                where customerno=%d AND isdeleted=0  order by name ASC"; //condtion added for master dealer
       $cityQuery = sprintf($Query, $this->_Customerno, $cityid);
        $this->_databaseManager->executeQuery($cityQuery);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $length = strlen(decbin($row['vendor']));
                if($length==1){
                   $vendor = '000000'.decbin($row['vendor']);
                }
                else if($length==2){
                   $vendor = '00000'.decbin($row['vendor']);
                }
                else if($length==3){
                   $vendor = '0000'.decbin($row['vendor']);
                }
                else if($length==4){
                   $vendor = '000'.decbin($row['vendor']);
                }
                else if($length==5){
                   $vendor = '00'.decbin($row['vendor']);
                }
                else if($length==6){
                   $vendor = '0'.decbin($row['vendor']);
                }
                else if($length==7){
                   $vendor = decbin($row['vendor']);
                }
                $batt = substr($vendor, 0, 1);
                $tyre = substr($vendor, 1, 1);
                $service = substr($vendor, 2, 2);
                $vehicle = substr($vendor, 4, 1);
                $accessory = substr($vendor, 5, 1);
                $fuel = substr($vendor, 6, 1);
                if($type=='0' && $batt=='1'){
                    $data.= "<option value='".$row['dealerid']."'>".$row['name']."</option>";
                }
                else if($type=='1' && $tyre=='1'){
                    $data.= "<option value='".$row['dealerid']."'>".$row['name']."</option>";
                }
                else if(($type=='2' && $service=='11') || ($type=='2' && $service=='01') || ($type=='2' && $service=='10')){
                    $data.= "<option value='".$row['dealerid']."'>".$row['name']."</option>";
                }
                else if($type=='6' && $vehicle=='1'){
                    $data.= "<option value='".$row['dealerid']."'>".$row['name']."</option>";
                }
                else if($type=='5' && $accessory=='1'){
                    $data.= "<option value='".$row['dealerid']."'>".$row['name']."</option>";
                }
                else if($type=='7' && $fuel=='1'){
                    $data.= "<option value='".$row['dealerid']."'>".$row['name']."</option>";
                }
            }
        }
        return $data;
    }

    public function pull_dealers_by_vehicle_arr($vehicleid, $type)
    {
        $data_arr = array();
        $groupid = $this->get_groupid_from_vehicleid($vehicleid);
        $cityid = $this->get_city_from_group($groupid);
        // AND (cityid = %d OR cityid = '0')
        $Query = "SELECT * FROM `dealer` where customerno=%d AND isdeleted=0  order by name ASC"; //condtion added for master dealer
        $cityQuery = sprintf($Query, $this->_Customerno, $cityid);
        $this->_databaseManager->executeQuery($cityQuery);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $length = strlen(decbin($row['vendor']));
                if($length==1){
                   $vendor = '000000'.decbin($row['vendor']);
                }
                else if($length==2){
                   $vendor = '00000'.decbin($row['vendor']);
                }
                else if($length==3){
                   $vendor = '0000'.decbin($row['vendor']);
                }
                else if($length==4){
                   $vendor = '000'.decbin($row['vendor']);
                }
                else if($length==5){
                   $vendor = '00'.decbin($row['vendor']);
                }
                else if($length==6){
                   $vendor = '0'.decbin($row['vendor']);
                }
                else if($length==7){
                   $vendor = decbin($row['vendor']);
                }
                $batt = substr($vendor, 0, 1);
                $tyre = substr($vendor, 1, 1);
                $service = substr($vendor, 2, 2);
                $vehicle = substr($vendor, 4, 1);
                $accessory = substr($vendor, 5, 1);
                $fuel = substr($vendor, 6, 1);
                if($type=='0' && $batt=='1'){
                    $data_arr[$row['dealerid']] = $row['name'];
                }
                else if($type=='1' && $tyre=='1'){
                    $data_arr[$row['dealerid']] = $row['name'];
                }
                else if(($type=='2' && $service=='11') || ($type=='2' && $service=='01') || ($type=='2' && $service=='10')){
                    $data_arr[$row['dealerid']] = $row['name'];
                }
                else if($type=='6' && $vehicle=='1'){
                    $data_arr[$row['dealerid']] = $row['name'];
                }
                else if($type=='5' && $accessory=='1'){
                    $data_arr[$row['dealerid']] = $row['name'];
                }
                else if($type=='7' && $fuel=='1'){
                    $data_arr[$row['dealerid']] = $row['name'];
                }
            }
        }
        return $data_arr;
    }

    public function get_dealers_by_type($type,$roleid,$heirarchy_id)
    {
        $nations = Array();
        $branch_query = "";
        if($roleid == '8')
        {
            $cityid = $this->get_city_from_group($heirarchy_id);
            $branch_query = sprintf(" AND cityid = %d ",$cityid);
        }
        elseif($roleid == '4')
        {
            $branch_query = sprintf(" AND cityid = %d ",$heirarchy_id);
        }
        $Query = "SELECT * FROM `dealer` where customerno=%d AND isdeleted=0  $branch_query order by name ASC";

        $cityQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($cityQuery);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $length = strlen(decbin($row['vendor']));
                if($length==1){
                   $vendor = '000000'.decbin($row['vendor']);
                }
                else if($length==2){
                   $vendor = '00000'.decbin($row['vendor']);
                }
                else if($length==3){
                   $vendor = '0000'.decbin($row['vendor']);
                }
                else if($length==4){
                   $vendor = '000'.decbin($row['vendor']);
                }
                else if($length==5){
                   $vendor = '00'.decbin($row['vendor']);
                }
                else if($length==6){
                   $vendor = '0'.decbin($row['vendor']);
                }
                else if($length==7){
                   $vendor = decbin($row['vendor']);
                }
                //echo $type;
                $batt = substr($vendor, 0, 1);
                $tyre = substr($vendor, 1, 1);
                $service = substr($vendor, 2, 2);
                $vehicle = substr($vendor, 4, 1);
                $accessory = substr($vendor, 5, 1);
                $fuel = substr($vendor, 6, 1);

                if($type=='1' && $batt=='1'){
                $Nation = new VONation();
                $Nation->name = $row['name'];
                $Nation->phoneno = $row['phone'];
                $Nation->cellphone = $row['cellphone'];
                $Nation->dealerid = $row['dealerid'];
                $Nation->vendor = $row['vendor'];
                $Nation->notes = $row['notes'];
                $Nation->address = $row['address'];
                $nations[] = $Nation;
                }
                else if($type=='2' && $tyre=='1'){
                $Nation = new VONation();
                $Nation->name = $row['name'];
                $Nation->phoneno = $row['phone'];
                $Nation->cellphone = $row['cellphone'];
                $Nation->dealerid = $row['dealerid'];
                $Nation->vendor = $row['vendor'];
                $Nation->notes = $row['notes'];
                $Nation->address = $row['address'];
                $nations[] = $Nation;
                }
                else if(($type=='4' && $service=='11') || ($type=='4' && $service=='01') || ($type=='4' && $service=='10')){
                $Nation = new VONation();
                $Nation->name = $row['name'];
                $Nation->phoneno = $row['phone'];
                $Nation->cellphone = $row['cellphone'];
                $Nation->dealerid = $row['dealerid'];
                $Nation->vendor = $row['vendor'];
                $Nation->notes = $row['notes'];
                $Nation->address = $row['address'];
                $nations[] = $Nation;
                }
                else if($type=='5' && $vehicle=='1'){
                $Nation = new VONation();
                $Nation->name = $row['name'];
                $Nation->phoneno = $row['phone'];
                $Nation->cellphone = $row['cellphone'];
                $Nation->dealerid = $row['dealerid'];
                $Nation->vendor = $row['vendor'];
                $Nation->notes = $row['notes'];
                $Nation->address = $row['address'];
                $nations[] = $Nation;
                }
                else if($type=='6' && $accessory=='1'){
                $Nation = new VONation();
                $Nation->name = $row['name'];
                $Nation->phoneno = $row['phone'];
                $Nation->cellphone = $row['cellphone'];
                $Nation->dealerid = $row['dealerid'];
                $Nation->vendor = $row['vendor'];
                $Nation->notes = $row['notes'];
                $Nation->address = $row['address'];
                $nations[] = $Nation;
                }
                else if($type=='7' && $fuel=='1'){
                $Nation = new VONation();
                $Nation->name = $row['name'];
                $Nation->phoneno = $row['phone'];
                $Nation->cellphone = $row['cellphone'];
                $Nation->dealerid = $row['dealerid'];
                $Nation->vendor = $row['vendor'];
                $Nation->notes = $row['notes'];
                $Nation->address = $row['address'];
                $nations[] = $Nation;
                }
            }
            return $nations;
        }
        return null;
    }

    public function get_dealer($dealerid)
    {
        $Query = "SELECT * FROM `dealer` where customerno=%d AND dealerid=%d AND isdeleted=0";
        $nationsQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($dealerid));
        $this->_databaseManager->executeQuery($nationsQuery);

        if ($this->_databaseManager->get_rowCount() > 0)
        {
            while ($row = $this->_databaseManager->get_nextRow())
            {
                $Nation = new VONation();
                $Nation->name = $row['name'];
                $Nation->phoneno = $row['phone'];
                $Nation->cellphone = $row['cellphone'];
                $Nation->dealerid = $row['dealerid'];
                $Nation->vendor = $row['vendor'];
                $Nation->notes = $row['notes'];
                $Nation->address = $row['address'];
                $Nation->code = $row['code'];
                $Nation->cityid = $row['cityid'];
                $Nation->other1 = $row['other1'];
                $Nation->other2 = $row['other2'];

            }
            return $Nation;
        }
        return null;
    }

    public function get_city($cityid)
    {
        $Query = "SELECT * FROM `city` where customerno=%d AND cityid=%d AND isdeleted=0";
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
            }
            return $Nation;
        }
        return null;
    }

    public function add_dealer($name, $phoneno, $cellphone, $notes, $address, $vendor, $code, $userid,$other1, $other2)
    {
        $vendordec = bindec($vendor);
        $Query = "INSERT INTO `dealer` (name,address,phone,cellphone,notes,vendor,customerno,userid,other1,other2,isdeleted,timestamp,code) VALUES ('%s','%s','%s','%s','%s','%s',%d,%d,'%s','%s','0','%s','%s')";
        $date = new Date();
        $todays = $date->MySQLNow();
        $SQL = sprintf($Query, Sanitise::String($name), Sanitise::String($address), Sanitise::String($phoneno), Sanitise::String($cellphone), Sanitise::String($notes), Sanitise::String($vendordec), $this->_Customerno, Sanitise::Long($userid), Sanitise::String($other1), Sanitise::String($other2),  Sanitise::DateTime($todays), Sanitise::String($code));
        $this->_databaseManager->executeQuery($SQL);
        $dealerid = $this->_databaseManager->get_insertedId();
        $response = $dealerid;
        echo $response;
    }


    public function edit_dealer($dealerid, $name, $phoneno, $cellphone, $notes, $address, $vendor, $code, $userid, $other1, $other2)
    {
        $vendordec = bindec($vendor);
        $Query = "UPDATE `dealer` SET `name` = '%s',`address`= '%s',`phone`='%s',`cellphone`='%s',`notes`='%s',`vendor`='%s',code='%s',userid=%d, other1='%s', other2='%s' WHERE dealerid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::String($name), Sanitise::String($address), Sanitise::String($phoneno), Sanitise::String($cellphone), Sanitise::String($notes), Sanitise::String($vendordec), Sanitise::String($code), Sanitise::Long($userid), Sanitise::String($other1), Sanitise::String($other2), Sanitise::Long($dealerid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $response = $dealerid;
        return $response;

    }

    public function DeleteDealer($dealerid, $userid)
    {
        $Query = "UPDATE `dealer` SET isdeleted=1,userid=%d WHERE dealerid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($dealerid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }


}
