<?php

include_once("session.php");
include_once("../../lib/system/utilities.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");

class Dashboard {
    
}

function getCrm() {
    $datas = Array();
    $db = new DatabaseManager();
    $SQL = "SELECT manager_name,rid FROM " . DB_PARENT . ".relationship_manager";
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $data = new Dashboard();
            $data->rid = $row['rid'];
            $data->name = $row['manager_name'];
            $datas[] = $data;
        }
        return $datas;
    }
}

function getPending() {
    $datas = Array();
    $crmdata = getCrm();
    $db = new DatabaseManager();
    foreach ($crmdata as $this_crm) {
        $SQL = sprintf("SELECT sum(i.pending_amt) AS pending, customer.rel_manager, relationship_manager.manager_name FROM " . DB_PARENT . ".invoice i
                        INNER JOIN " . DB_PARENT . ".customer ON i.customerno = customer.customerno
                        INNER JOIN " . DB_PARENT . ".relationship_manager ON customer.rel_manager = relationship_manager.rid
                        WHERE i.pending_amt NOT IN(0) AND customer.customerno NOT IN (1,2,-1)
                        AND customer.rel_manager='%s' AND i.isdeleted=0", $this_crm->rid);
        $db->executeQuery($SQL);
        $data = new Dashboard();
        $row = $db->get_nextRow();
        $data->rname = $row['manager_name'];
        $data->pending = $row['pending'];
        $datas[] = $data;
    }
    return $datas;
}

function getTotalreceviables() {
    $db = new DatabaseManager();

    $SQL = "SELECT sum(pending_amt) AS Total FROM " . DB_PARENT . ".invoice WHERE customerno NOT IN (0,1,2) AND isdeleted=0";
    $db->executeQuery($SQL);
    $row = $db->get_nextRow();
    $tpending = $row['Total'];
    return $tpending;
}

function getPendingRenewals() {
    $db = new DatabaseManager();
    $SQL = "SELECT count(devices.invoiceno) AS prenewals FROM devices
           INNER JOIN vehicle ON devices.uid = vehicle.uid 
           INNER JOIN unit ON devices.uid = unit.uid 
           WHERE vehicle.isdeleted= 0 AND unit.customerno NOT IN (-1,1,2) AND devices.expirydate!='1970-01-01' 
           AND devices.expirydate!='0000-00-00' AND unit.trans_statusid NOT IN(22,23,24,10) AND devices.invoiceno=''";
    $db->executeQuery($SQL);
    $row = $db->get_nextRow();
    $trenewals = $row['prenewals'];
    return $trenewals;
}

function getExpiredRenewals() {
    $today = date("Y-m-d");
    $db = new DatabaseManager();
    $SQL = "SELECT count(devices.invoiceno) AS erenewals FROM devices
           INNER JOIN vehicle ON devices.uid = vehicle.uid 
           INNER JOIN unit ON devices.uid = unit.uid 
           WHERE vehicle.isdeleted= 0 AND unit.customerno NOT IN (-1,1,2) AND devices.expirydate!='1970-01-01' 
           AND devices.expirydate!='0000-00-00' AND unit.trans_statusid NOT IN(22,23,24,10) AND (devices.expirydate < '$today')";
    $db->executeQuery($SQL);
    $row = $db->get_nextRow();
    $erenewals = $row['erenewals'];

    return $erenewals;
}

function getExpiredIn30() {
    $start = date("Y-m-d");
    $end = date("Y-m-d", strtotime("+30 days"));
    $db = new DatabaseManager();
    $SQL = "SELECT count(devices.invoiceno) AS renewals FROM devices
           INNER JOIN vehicle ON devices.uid = vehicle.uid 
           INNER JOIN unit ON devices.uid = unit.uid 
           WHERE vehicle.isdeleted= 0 AND unit.customerno NOT IN (-1,1,2) AND devices.expirydate!='1970-01-01' 
           AND devices.expirydate!='0000-00-00' AND unit.trans_statusid NOT IN(22,23,24,10) AND (devices.expirydate BETWEEN'$start' AND '$end')";
    $db->executeQuery($SQL);
    $row = $db->get_nextRow();
    $renewals = $row['renewals'];
    return $renewals;
}

function getPendinginv() {
    $db = new DatabaseManager();
    $SQL = "SELECT count(devices.device_invoiceno) AS inv FROM devices
           INNER JOIN vehicle ON devices.uid = vehicle.uid 
           INNER JOIN unit ON devices.uid = unit.uid 
           WHERE vehicle.isdeleted= 0 AND unit.customerno NOT IN (-1,1,2) AND devices.device_invoiceno=''
           AND unit.trans_statusid NOT IN(22,23,24,10) AND unit.onlease=0";
    $db->executeQuery($SQL);
    $row = $db->get_nextRow();
    $pinv = $row['inv'];
    return $pinv;
}

function getOnlease() {
    $db = new DatabaseManager();
    $SQL = "SELECT count(unit.onlease) AS lease FROM unit
           INNER JOIN vehicle ON unit.uid = vehicle.uid 
           WHERE vehicle.isdeleted= 0 AND unit.customerno NOT IN (-1,1,2)
           AND unit.trans_statusid NOT IN(22,23,24,10) AND unit.onlease = 1";
    $db->executeQuery($SQL);
    $row = $db->get_nextRow();
    $lease = $row['lease'];
    return $lease;
}

function unit_inventory() {
    $db = new DatabaseManager();
    $SQL = "SELECT count(unit.unitno) AS office FROM unit
           INNER JOIN vehicle ON unit.uid = vehicle.uid 
           WHERE vehicle.isdeleted= 0 AND unit.customerno = 1
           AND unit.trans_statusid NOT IN(5,7,18,20,13,22,23,24,25)";
    $db->executeQuery($SQL);
    $row = $db->get_nextRow();
    $office = $row['office'];
    return $office;
}

function unit_customer() {
    $db = new DatabaseManager();
    $SQL = "SELECT count(unit.unitno) AS customer FROM unit
           INNER JOIN vehicle ON unit.uid = vehicle.uid 
           WHERE vehicle.isdeleted= 0 AND unit.customerno NOT IN(-1,1,2)
           AND unit.trans_statusid IN(5,22,23,24,25)";
    $db->executeQuery($SQL);
    $row = $db->get_nextRow();
    $customer = $row['customer'];
    return $customer;
}

function unit_repair() {
    $db = new DatabaseManager();
    $SQL = "SELECT count(unit.unitno) AS repair FROM unit
           INNER JOIN vehicle ON unit.uid = vehicle.uid 
           WHERE vehicle.isdeleted= 0 AND unit.customerno = -1";
    $db->executeQuery($SQL);
    $row = $db->get_nextRow();
    $repair = $row['repair'];
    return $repair;
}

function unit_field() {
    $db = new DatabaseManager();
    $SQL = "SELECT count(unit.unitno) AS field FROM unit
           INNER JOIN vehicle ON unit.uid = vehicle.uid 
           WHERE vehicle.isdeleted= 0 AND unit.customerno =1
           AND unit.trans_statusid IN(18,20)";
    $db->executeQuery($SQL);
    $row = $db->get_nextRow();
    $field = $row['field'];
    return $field;
}

function sim_inventory() {
    $db = new DatabaseManager();
    $SQL = "SELECT count(simcardno) AS soffice FROM simcard
           WHERE customerno = 1 AND trans_statusid NOT IN(18,19,21)";
    $db->executeQuery($SQL);
    $row = $db->get_nextRow();
    $soffice = $row['soffice'];
    return $soffice;
}

function sim_customer() {
    $db = new DatabaseManager();
    $SQL = "SELECT count(simcardno) AS simcust FROM simcard
           WHERE customerno NOT IN (1,-1) AND trans_statusid= 13";
    $db->executeQuery($SQL);
    $row = $db->get_nextRow();
    $simcust = $row['simcust'];
    return $simcust;
}

function sim_field() {
    $db = new DatabaseManager();
    $SQL = "SELECT count(simcardno) AS simfield FROM simcard
           WHERE trans_statusid IN(18,19,21) AND customerno = 1 ";
    $db->executeQuery($SQL);
    $row = $db->get_nextRow();
    $simfield = $row['simfield'];
    return $simfield;
}

function getCustno() {
    $cuno = Array();
    $db = new DatabaseManager();
    $SQL = "SELECT customerno FROM " . DB_PARENT . ".customer ";
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $c_no = new Dashboard();
            if ($row['customerno'] == 1 || $row['customerno'] == 2)
                continue;
            $c_no->customerno = $row['customerno'];
            $cuno[] = $c_no;
        }
    }
    return $cuno;
}

function getCustlogin() {
    $custno = getCustno();
    $cmobile = 0;
    $cweb = 0;
    $db = new DatabaseManager();
    foreach ($custno as $cno) {
        $SQL = sprintf("select  customer.customerno, customer.customercompany, login_history.type,
            SUM(CASE WHEN login_history.type = 1 THEN 1 ELSE 0 END) as mobilecount,    
            SUM(CASE WHEN login_history.type = 0 THEN 1 ELSE 0 END) as webcount    
            from " . DB_PARENT . ".login_history
            inner join user on user.userid = login_history.userid
            inner join " . DB_PARENT . ".customer on customer.customerno = login_history.customerno
            Where user.realname <> 'Elixir' AND customer.customerno= '%s' ", $cno->customerno);
        $db->executeQuery($SQL);
        $row = $db->get_nextRow();
        if ($row["mobilecount"] > 0) {
            $cmobile++;
        }
        if ($row["webcount"] > 0) {
            $cweb++;
        }
    }

    return Array($cmobile, $cweb);
}

function getUserlogin() {
    $db = new DatabaseManager();
    $SQL = "SELECT SUM(CASE WHEN login_history.type = 1 THEN 1 ELSE 0 END) as mobilecount,    
             SUM(CASE WHEN login_history.type = 0 THEN 1 ELSE 0 END) as webcount    
             from " . DB_PARENT . ".login_history
             inner join user on user.userid = login_history.userid
             inner join " . DB_PARENT . ".customer on customer.customerno = login_history.customerno
             Where user.realname <> 'Elixir'";
    $db->executeQuery($SQL);
    $row = $db->get_nextRow();
    $umobile = $row['mobilecount'];
    $uweb = $row['webcount'];
    return array($umobile, $uweb);
}

function unit_total($category) {
    $db = new DatabaseManager();
    if ($category == "inoffice") {
        $SQL = "SELECT count(unitno) AS tunit FROM unit WHERE customerno=1 AND trans_statusid IN(1,2,3,4,17) AND teamid = 0";
    }
    if ($category == "withfe") {
        $SQL = "SELECT count(unitno) AS tunit FROM unit WHERE customerno=1 AND trans_statusid IN(18,20) and teamid <> 0";
    }
    if ($category == "withcust") {
        $SQL = "SELECT  count(unitno) AS tunit 
                FROM    unit 
                INNER JOIN devices d ON d.uid = unit.uid 
                WHERE   unit.customerno NOT IN(-1,1,2) 
                AND     ((d.device_invoiceno <> '' AND trans_statusid IN(5,13,22))
                OR      (d.device_invoiceno = '' AND trans_statusid IN(5,13,22,23,10)))";
    }
    if ($category == "repair") {
        $SQL = "SELECT count(unitno) AS tunit FROM unit WHERE customerno=-1";
    }
    if ($category == "trashed") {
        $SQL = "SELECT count(unitno) AS tunit FROM unit WHERE trans_statusid=26";
    }
    $db->executeQuery($SQL);
    $row = $db->get_nextRow();
    $tunit = $row['tunit'];
    return $tunit;
}

function unit_status($s_category, $status, $invoiced = NULL) {
    $db = new DatabaseManager();
    if ($s_category == "inoffice" && $invoiced == NULL) {
        $SQL = sprintf("SELECT count(unitno) AS sunit FROM unit WHERE customerno=1 AND trans_statusid IN(%s)", $status);
        $db->executeQuery($SQL);
    }
    if ($s_category == "withfe" && $invoiced == NULL) {
        $SQL = sprintf("SELECT count(unitno) AS sunit FROM unit WHERE customerno=1 AND trans_statusid IN(%s)", $status);
        $db->executeQuery($SQL);
    }
    if ($s_category == "withcust" && $invoiced == 1) {
        $SQL = sprintf("    SELECT  count(u.unitno) AS sunit 
                            FROM    unit u
                            INNER JOIN devices d ON d.uid = u.uid
                            WHERE   u.customerno NOT IN (1,2,-1) 
                            AND     d.device_invoiceno <> '' 
                            AND     u.trans_statusid IN(%s)", $status);
        $db->executeQuery($SQL);
    }
    elseif ($s_category == "withcust" && $invoiced == 2) {
        $SQL = sprintf("    SELECT  count(u.unitno) AS sunit 
                            FROM    unit u
                            INNER JOIN devices d ON d.uid = u.uid
                            WHERE   u.customerno NOT IN (1,2,-1) 
                            AND     d.device_invoiceno = ''
                            AND     u.trans_statusid IN(%s)", $status);
        $db->executeQuery($SQL);
    }    
    elseif ($s_category == "withcust") {
        $SQL = sprintf("    SELECT  count(u.unitno) AS sunit 
                            FROM    unit u
                            INNER JOIN devices d ON d.uid = u.uid
                            WHERE   u.customerno NOT IN (1,2,-1) 
                            AND     u.trans_statusid IN(%s)", $status);
        $db->executeQuery($SQL);
    }

    $row = $db->get_nextRow();
    $sunit = $row['sunit'];
    return $sunit;
}

function unit_office_type() {
    $db = new DatabaseManager();
    $type = array();
    $SQL = "SELECT distinct type_value FROM unit";
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $Datacap = new Dashboard();
            $Datacap->type = $row['type_value'];
            $type[] = $Datacap;
        }
    }
    foreach ($type as $this_type) {
        $str = "";
        $SQLcount = sprintf("SELECT count(uid) as total_off, type_value FROM unit WHERE type_value = '%s' AND customerno =1 AND teamid =0 AND trans_statusid NOT IN(7,10,15,16,18,20)", $this_type->type);
        $db->executeQuery($SQLcount);
        if ($db->get_rowCount() > 0) {
            $value = Array();
            $category = (int) $this_type->type;
            $binarycategory = sprintf("%08s", DecBin($category));
            for ($shifter = 1; $shifter <= 10000; $shifter = $shifter << 1) {
                $binaryshifter = sprintf("%08s", DecBin($shifter));
                if ($category & $shifter) {
                    $value[] = $shifter;
                }
            }

            while ($row = $db->get_nextRow()) {


                if (in_array(0, $value)) {
                    $str .= "Basic+";
                }
                if (in_array(1, $value)) {
                    $str .= "AC+";
                }if (in_array(4, $value)) {
                    $str .= "Door+";
                }if (in_array(2, $value)) {
                    $str .= "Genset+";
                }
                if (in_array(8, $value)) {
                    $str .= "Temperature 1+";
                }
                if (in_array(16, $value)) {
                    $str .= "Temperature 2+";
                }
                if (in_array(32, $value)) {
                    $str .= "Panic+";
                }if (in_array(64, $value)) {
                    $str .= "Buzzer+";
                }
                if (in_array(128, $value)) {
                    $str .= "Immobilizer+";
                }
                if (in_array(1024, $value)) {
                    $str .= "Fuel Sensor+";
                }
                if (in_array(256, $value)) {
                    $str .= "Two Way Communication+";
                }
                if (in_array(512, $value)) {
                    $str .= "Portable+";
                }
                if (in_array(1024, $value)) {
                    $str .= "Fuel+";
                }
                if (in_array(2048, $value)) {
                    $str .= "Temperature 3+";
                }
                if (in_array(4096, $value)) {
                    $str .= "Temperature 4+";
                }
                if ($str == "")
                    $str = "Basic";
                if ($row['total_off'] == 0)
                    continue;
                $test[] = array(name => "$str", cnt => (int) $row['total_off'], typeval => (int) $row['type_value']);
            }
        }
    }
    return $test;
}

function unit_office_location() {
    $db = new DatabaseManager();
    $loctn_id = array();
    $SQL = "SELECT distinct unit_location_id FROM device_location ";
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $Datacap = new Dashboard();
            $Datacap->loctn_id = $row['unit_location_id'];
            $loctn_id[] = $Datacap;
        }
    }
    foreach ($loctn_id as $this_type){
               
        $db  = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'".$this_type->loctn_id."'";
        
        
        $queryCallSP       = "CALL " . speedConstants::SP_GET_DEVICE_LOCATION_COUNT . "($sp_params)";
        
        $result            = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);

        foreach($result as $data){
         $test[] = array('unitno'=>$data['unitno'],'location'=>$data['location'],'name' => $data['device_name'], 'count' => (int) $data['count']);
        }
    }
   return $test;
}

function getFEdetails() {
    $db = new DatabaseManager();
    $Data = Array();
    $SQL = "SELECT name,teamid FROM " . DB_PARENT . ".team WHERE role='Service'";
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $Datacap = new Dashboard();
            $Datacap->name = $row["name"];
            $Datacap->id = $row["teamid"];
            $Data[] = $Datacap;
        }
    }
    return $Data;
}

function unit_allotment_fe() {
    $db = new DatabaseManager();
    $detail = getFEdetails();
    $allot_data = Array();
    foreach ($detail as $details) {
        $SQL = sprintf("SELECT count(unit.uid) as allot_total ,team.name FROM unit
                             INNER JOIN " . DB_PARENT . ".team on unit.teamid=team.teamid
                             WHERE unit.teamid='%s'", $details->id);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $Datacap = new Dashboard();
                $Datacap->name = $row["name"];
                if ($row["allot_total"] == "0")
                    continue;
                $Datacap->count = $row["allot_total"];
                $allot_data[] = $Datacap;
            }
        }
    }
    return $allot_data;
}

function unit_cust_count() {
    $cust_data = Array();
    $db = new DatabaseManager();
    $cno = getCustno();
    foreach ($cno as $this_cno) {
        $SQLcount = sprintf("SELECT count(uid) as total_cust, customer.customercompany,customer.customerno FROM unit 
                                INNER JOIN " . DB_PARENT . ".customer on unit.customerno=customer.customerno
                                WHERE unit.customerno='%s' AND unit.trans_statusid IN(5,6,13,14,22,23,10)", $this_cno->customerno);
        $db->executeQuery($SQLcount);
        if ($db->get_rowCount() > 0) {

            while ($row = $db->get_nextRow()) {
                $Datacap = new Dashboard();
                $Datacap->custno = $row['customerno'];
                $Datacap->custcompany = $row['customercompany'];
                if ($row['total_cust'] == 0)
                    continue;
                $Datacap->custcount = $row['total_cust'];
                $cust_data[] = $Datacap;
            }
        }
    }
    return $cust_data;
}

function sim_total($sim_category) {
    $db = new DatabaseManager();
    if ($sim_category == "inoffice") {
        $SQL = "SELECT count(simcardno) AS tsim FROM simcard WHERE customerno=1 AND trans_statusid IN(11,12)";
    }
    if ($sim_category == "withfe") {
        $SQL = "SELECT count(simcardno) AS tsim FROM simcard WHERE customerno=1 AND trans_statusid IN(19,21)";
    }
    if ($sim_category == "withcust") {
        $SQL = "SELECT count(simcardno) AS tsim FROM simcard WHERE customerno NOT IN(-1,1,2) AND trans_statusid IN(12,13,14,24,25)";
    }
    if ($sim_category == "repair") {
        $SQL = "SELECT count(simcardno) AS tsim FROM simcard WHERE customerno=-1";
    }
    $db->executeQuery($SQL);
    $row = $db->get_nextRow();
    $tsim = $row['tsim'];
    return $tsim;
}

function sim_status($sim_category, $status, $status1 = null) {
    $db = new DatabaseManager();
    if ($sim_category == "inoffice" && $status1 == null) {
        $SQL = sprintf("SELECT count(simcardno) AS s_sim FROM simcard WHERE customerno=1 AND trans_statusid IN('$status')", $status);
        $db->executeQuery($SQL);
    }
    if ($sim_category == "inoffice" && $status1 != null) {
        $SQL = sprintf("SELECT count(simcardno) AS s_sim FROM simcard WHERE customerno=1 AND trans_statusid IN(%d,%d)", $status, $status1);
        $db->executeQuery($SQL);
    }
    if ($sim_category == "withfe" && $status1 == null) {
        $SQL = sprintf("SELECT count(simcardno) AS s_sim FROM simcard WHERE customerno=1 AND trans_statusid IN('$status')", $status);
        $db->executeQuery($SQL);
    }
    if ($sim_category == "withcust" && $status1 == null) {
        $SQL = sprintf("SELECT count(simcardno) AS s_sim FROM simcard WHERE customerno NOT IN (1,2,-1) AND trans_statusid IN('$status')", $status);
        $db->executeQuery($SQL);
    }
    if ($sim_category == "withcust" && $status1 != null) {
        $SQL = sprintf("SELECT count(simcardno) AS s_sim FROM simcard WHERE customerno NOT IN (1,2,-1) AND trans_statusid IN(%d,%d)", $status, $status1);
        $db->executeQuery($SQL);
    }
    $row = $db->get_nextRow();
    $s_sim = $row['s_sim'];
    return $s_sim;
}

function getvendordetails() {
    $datavendor = Array();
    $db = new DatabaseManager();
    $SQL = "SELECT id FROM vendor";
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $Datacap = new Dashboard();
            $Datacap->venid = $row['id'];
            $datavendor[] = $Datacap;
        }
    }
    return $datavendor;
}

function vendor_simcount() {
    $vendor = Array();
    $db = new DatabaseManager();
    $vendetail = getvendordetails();

    $SQL = "SELECT count(simcard.simcardno) as vcount,vendor.vendorname FROM simcard
            INNER JOIN vendor ON simcard.vendorid=vendor.id
            WHERE simcard.customerno=1 AND simcard.trans_statusid IN(11,12) GROUP BY simcard.vendorid";
    $db->executeQuery($SQL);


    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $Datacap = new Dashboard();
            $Datacap->vname = $row['vendorname'];
            $Datacap->vcount = $row['vcount'];
            $vendor[] = $Datacap;
        }
    }

    return $vendor;
}

function sim_cust_count() {
    $cust_data = Array();
    $db = new DatabaseManager();
    $cno = getCustno();
    foreach ($cno as $this_cno) {
        $SQLcount = sprintf("SELECT count(simcardno) as total_sim, vendor.vendorname,s.customerno,customer.customercompany from simcard as s
                                INNER JOIN vendor ON vendor.id=s.vendorid
                                INNER JOIN " . DB_PARENT . ".customer on s.customerno=customer.customerno
                                WHERE s.customerno='%s' AND s.trans_statusid IN(12,13,14,24,25)", $this_cno->customerno);
        $db->executeQuery($SQLcount);
        if ($db->get_rowCount() > 0) {

            while ($row = $db->get_nextRow()) {
                $Datacap = new Dashboard();
                $Datacap->custno = $row['customerno'];
                $Datacap->custcompany = $row['customercompany'];
                if ($row['total_sim'] == 0)
                    continue;
                $Datacap->simcount = $row['total_sim'];
                $Datacap->vname = $row['vendorname'];
                $cust_data[] = $Datacap;
            }
        }
    }
    return $cust_data;
}

function sim_withfe() {
    $fesim = array();
    $db = new DatabaseManager();
    $SQL = "SELECT count(id) as cnt,team.name FROM simcard 
            INNER JOIN " . DB_PARENT . ".team ON simcard.teamid=team.teamid
            where customerno IN (1) AND simcard.trans_statusid IN (19,21) group by simcard.teamid ORDER BY team.name ASC";
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $Datacap = new Dashboard();
            $Datacap->simcnt = $row['cnt'];
            $Datacap->tname = $row['name'];
            $fesim[] = $Datacap;
        }
    }

    return $fesim;
}

function totalDeviceInstalled_byfe() {
    $install = array();
    $db = new DatabaseManager();
    $SQL = "SELECT count(servicecall.uid) as tinstall,team.name FROM " . DB_PARENT . ".servicecall 
            INNER JOIN " . DB_PARENT . ".team ON team.teamid=servicecall.teamid 
            WHERE servicecall.type IN(0,1) AND team.role='Service' group by servicecall.teamid
            ORDER BY team.name ASC";
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $Datacap = new Dashboard();
            $Datacap->tinstall = $row['tinstall'];
            $Datacap->tname = $row['name'];
            $install[] = $Datacap;
        }
    }
    return $install;
}

function totalDeviceInstalled_monthwise() {
    $curr_year = date("Y");
    $minstall = array();
    $db = new DatabaseManager();
    $SQL = sprintf("SELECT count(sc.uid) as minstall,YEAR(th.trans_time) AS 'year', MONTH(th.trans_time) AS 'month' FROM " . DB_PARENT . ".servicecall as sc
            INNER JOIN " . DB_PARENT . ".trans_history as th ON th.thid = sc.thid
            INNER JOIN " . DB_PARENT . ".team ON team.teamid=sc.teamid 
            WHERE sc.type IN(0,1) AND team.role='Service' group by `year`,`month` HAVING `year`=%d", $curr_year);
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $Datacap = new Dashboard();
            $Datacap->monthname = date("F", mktime(0, 0, 0, $row['month'], 10));
            $Datacap->monthno = $row['month'];
            $Datacap->minstall = $row['minstall'];
            $minstall[] = $Datacap;
        }
    }
    return $minstall;
}

function getUnitDetails() {
    $db = new DatabaseManager();
}

?>