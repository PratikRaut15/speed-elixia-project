<?php

class VOSsales {
    
}

include_once '../../config.inc.php';
include_once '../../lib/autoload.php';
include_once '../../lib/system/utilities.php';

//include_once '../../lib/system/DatabaseSalesManager.php';

class Sales extends DatabaseSalesManager {

    public function __construct($customerno, $userid) {
        parent::__construct($customerno, $userid);
        $this->customerno = $customerno;
        $this->userid = $userid;
        $this->today = date("Y-m-d H:i:s");
    }

    public function getsales($asmid, $customerid) {
        $salesdata = null;
        $Query = sprintf("SELECT userid,role,roleid,email,realname,username,phone,heirarchy_id FROM " . SPEEDDB . ".`user` where isdeleted=0 AND customerno=" . $customerid . " AND heirarchy_id=" . $asmid);
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $salesuser = new stdClass();
                $salesuser->userid = $row['userid'];
                $salesuser->role = $row['role'];
                $salesuser->roleid = $row['roleid'];
                $salesuser->email = $row["email"];
                $salesuser->realname = $row['realname'];
                $salesuser->username = $row["username"];
                $salesuser->phone = $row['phone'];
                $salesuser->heirarchy_id = $row["heirarchy_id"];
                $salesdata[] = $salesuser;
            }
            return $salesdata;
        }
        return null;
    }

    public function getsales_bysupervisor($supid) {
        $salesdata = null;
        $Query = sprintf("SELECT userid,role,roleid,email,realname,username,phone,heirarchy_id FROM " . SPEEDDB . ".`user` where isdeleted=0 AND customerno=" . $this->customerno . " AND heirarchy_id=" . $supid);
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $salesuser = new stdClass();
                $salesuser->userid = $row['userid'];
                $salesuser->role = $row['role'];
                $salesuser->roleid = $row['roleid'];
                $salesuser->email = $row["email"];
                $salesuser->realname = $row['realname'];
                $salesuser->username = $row["username"];
                $salesuser->phone = $row['phone'];
                $salesuser->heirarchy_id = $row["heirarchy_id"];
                $salesdata[] = $salesuser;
            }
            return $salesdata;
        }
        return null;
    }

    public function getsupervisors_byasm($asmid, $customerno) {
        $salesdata = array();
        $Query = sprintf("SELECT userid,role,roleid,email,realname,username,phone,heirarchy_id FROM " . SPEEDDB . ".`user` where isdeleted=0 AND customerno=" . $customerno . " AND heirarchy_id=" . $asmid);
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $salesuser = new stdClass();
                $salesuser->userid = $row['userid'];
                $salesuser->role = $row['role'];
                $salesuser->roleid = $row['roleid'];
                $salesuser->email = $row["email"];
                $salesuser->realname = $row['realname'];
                $salesuser->username = $row["username"];
                $salesuser->phone = $row['phone'];
                $salesuser->heirarchy_id = $row["heirarchy_id"];
                $salesdata[] = $salesuser;
            }
            return $salesdata;
        }
        return null;
    }

    public function getDistributordata_bysr($srid, $all = NULL) {
        $salesdata = array();
        if ($all == 'ALL') {
            $salesdata = null;
            $Query = sprintf("SELECT userid,role,roleid,email,realname,username,phone,heirarchy_id FROM " . SPEEDDB . ".`user` where role = 'Distributor' AND isdeleted=0 AND customerno=" . $this->customerno);
            $this->executeQuery($Query);
            if ($this->get_rowCount() > 0) {
                while ($row = $this->get_nextRow()) {
                    $salesuser = new stdClass();
                    $salesuser->userid = $row['userid'];
                    $salesuser->role = $row['role'];
                    $salesuser->roleid = $row['roleid'];
                    $salesuser->email = $row["email"];
                    $salesuser->realname = $row['realname'];
                    $salesuser->username = $row["username"];
                    $salesuser->phone = $row['phone'];
                    $salesuser->heirarchy_id = $row["heirarchy_id"];
                    $salesdata[] = $salesuser;
                }
                return $salesdata;
            }
            return null;
        } else {
            $srid = implode(',', (array) $srid);
            $salesdata = null;
            $Query = sprintf("SELECT userid,role,roleid,email,realname,username,phone,heirarchy_id FROM " . SPEEDDB . ".`user` where role = 'Distributor' AND isdeleted=0 AND customerno=" . $this->customerno . " AND heirarchy_id IN ( $srid)");
            $this->executeQuery($Query);
            if ($this->get_rowCount() > 0) {
                while ($row = $this->get_nextRow()) {
                    $salesuser = new stdClass();
                    $salesuser->userid = $row['userid'];
                    $salesuser->role = $row['role'];
                    $salesuser->roleid = $row['roleid'];
                    $salesuser->email = $row["email"];
                    $salesuser->realname = $row['realname'];
                    $salesuser->username = $row["username"];
                    $salesuser->phone = $row['phone'];
                    $salesuser->heirarchy_id = $row["heirarchy_id"];
                    $salesdata[] = $salesuser;
                }

                return $salesdata;
            }
            return null;
        }
    }

    public function get_sr_by_supervisors($supids, $customerno, $all = NULL) {

        if ($all == "ALL") {
            $salesdata = null;
            $Query = sprintf("SELECT userid,role,roleid,email,realname,username,phone,heirarchy_id FROM " . SPEEDDB . ".`user` where role='sales_representative' AND isdeleted=0 AND customerno=" . $customerno);
            $this->executeQuery($Query);
            if ($this->get_rowCount() > 0) {
                while ($row = $this->get_nextRow()) {
                    $salesuser = new stdClass();
                    $salesuser->userid = $row['userid'];
                    $salesuser->role = $row['role'];
                    $salesuser->roleid = $row['roleid'];
                    $salesuser->email = $row["email"];
                    $salesuser->realname = $row['realname'];
                    $salesuser->username = $row["username"];
                    $salesuser->phone = $row['phone'];
                    $salesuser->heirarchy_id = $row["heirarchy_id"];
                    $salesdata[] = $salesuser;
                }

                return $salesdata;
            }
            return null;
        } else {

            $supids = implode(',', (array) $supids);
            $salesdata = null;
            $Query = sprintf("SELECT userid,role,roleid,email,realname,username,phone,heirarchy_id FROM " . SPEEDDB . ".`user` where isdeleted=0 AND customerno=" . $customerno . " AND heirarchy_id IN ( $supids)");
            $this->executeQuery($Query);
            if ($this->get_rowCount() > 0) {
                while ($row = $this->get_nextRow()) {
                    $salesuser = new stdClass();
                    $salesuser->userid = $row['userid'];
                    $salesuser->role = $row['role'];
                    $salesuser->roleid = $row['roleid'];
                    $salesuser->email = $row["email"];
                    $salesuser->realname = $row['realname'];
                    $salesuser->username = $row["username"];
                    $salesuser->phone = $row['phone'];
                    $salesuser->heirarchy_id = $row["heirarchy_id"];
                    $salesdata[] = $salesuser;
                }

                return $salesdata;
            }
            return null;
        }
    }

    public function getallusers() {
        $salesdata = null;
        $Query = sprintf("SELECT userid,role,roleid,email,realname,username,phone,heirarchy_id FROM " . SPEEDDB . ".`user` where role!='elixir' AND isdeleted=0 AND customerno=" . $this->customerno);
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $salesuser = new stdClass();
                $salesuser->userid = $row['userid'];
                $salesuser->role = $row['role'];
                $salesuser->roleid = $row['roleid'];
                $salesuser->email = $row["email"];
                $salesuser->realname = $row['realname'];
                $salesuser->username = $row["username"];
                $salesuser->phone = $row['phone'];
                $salesuser->heirarchy_id = $row["heirarchy_id"];
                $salesdata[] = $salesuser;
            }

            return $salesdata;
        }
        return null;
    }

    public function get_primary_count($prid) {
        $Query = sprintf("SELECT count(prid) as primarycount FROM primary_order_productlist where prid = " . $prid . " AND isdeleted=0 AND customerno=" . $this->customerno);
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            $row = $this->get_nextRow();
            $count = $row['primarycount'];
        }
        return $count;
    }

    public function get_secondary_count($soid) {
        $Query = sprintf("SELECT count(soid) as secondarycount FROM secondary_order_productlist where soid = " . $soid . " AND isdeleted=0 AND customerno=" . $this->customerno);
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            $row = $this->get_nextRow();
            $count = $row['secondarycount'];
        }
        return $count;
    }

    public function get_deadstock_count($stockid) {
        $Query = sprintf("SELECT count(dopid) as deadstockcount FROM deadstock_order_productlist where stockid = " . $stockid . " AND isdeleted=0 ");
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            $row = $this->get_nextRow();
            $count = $row['deadstockcount'];
        }
        return $count;
    }

    public function get_primary_product_details($prid) {
        $salesdata = null;
        $Query = sprintf("SELECT * FROM primary_order_productlist as pop left join category as c on c.categoryid = pop.categoryid  left join style as st on  pop.skuid = st.styleid  "
                . "  where pop.prid = " . $prid . " AND pop.isdeleted=0 AND pop.customerno=" . $this->customerno);
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $salesuser = new stdClass();
                $salesuser->categoryid = $row['categoryid'];
                $salesuser->skuid = $row['skuid'];
                $salesuser->quantity = $row['quantity'];
                $salesuser->status = $row['status'];
                $salesuser->poid = $row['poid'];
                $salesuser->prid = $row['prid'];
                $salesuser->categoryname = $row['categoryname'];
                $salesuser->styleno = $row['styleno'];
                $salesdata[] = $salesuser;
            }
        }
        return $salesdata;
    }

    public function get_secondary_product_details($orderid) {
        $salesdata = null;
        $Query = sprintf("SELECT sop.skuid,st.styleno,c.categoryid,c.categoryname,sop.quantity,sop.status,sop.soid,sop.sopid FROM secondary_order_productlist as sop"
                . "  left join style as st on st.styleid = sop.skuid left join category as c on c.categoryid = st.categoryid   where sop.soid = " . $orderid . " AND sop.isdeleted=0 AND sop.customerno=" . $this->customerno);
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $salesuser = new stdClass();
                $salesuser->skuid = $row['skuid'];
                $salesuser->quantity = $row['quantity'];
                $salesuser->status = $row['status'];
                $salesuser->soid = $row['soid'];
                $salesuser->sopid = $row['sopid'];
                $salesuser->styleno = $row['styleno'];
                $salesuser->categoryname = $row['categoryname'];
                $salesuser->categoryid = $row['categoryid'];
                $salesuser->totalqty = 50;   //It will come from distributor inventory
                $salesdata[] = $salesuser;
            }
        }
        return $salesdata;
    }

    public function get_deadstock_product_details($orderid) {
        $salesdata = null;
        $Query = sprintf("SELECT c.categoryname,c.categoryid,dop.skuid,dop.quantity,dop.stockid,dop.dopid,st.styleno FROM deadstock_order_productlist as dop left join style as st on dop.skuid = st.styleid  inner join category as c on st.categoryid = c.categoryid  where dop.stockid = " . $orderid . " AND dop.isdeleted=0");
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $salesuser = new stdClass();
                $salesuser->skuid = $row['skuid'];
                $salesuser->quantity = $row['quantity'];
                $salesuser->stockid = $row['stockid'];
                $salesuser->dopid = $row['dopid'];
                $salesuser->styleno = $row['styleno'];
                $salesuser->categoryid = $row['categoryid'];
                $salesuser->categoryname = $row['categoryname'];
                $salesdata[] = $salesuser;
            }
        }
        return $salesdata;
    }

    public function get_sales_all($customerno) {
        $salesdata = null;
        $Query = sprintf("SELECT userid,role,roleid,email,realname,username,phone,heirarchy_id FROM " . SPEEDDB . ".`user` where  isdeleted=0 AND customerno=" . $customerno . " AND role='sales_representative' ");
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $salesuser = new stdClass();
                $salesuser->userid = $row['userid'];
                $salesuser->role = $row['role'];
                $salesuser->roleid = $row['roleid'];
                $salesuser->email = $row["email"];
                $salesuser->realname = $row['realname'];
                $salesuser->username = $row["username"];
                $salesuser->phone = $row['phone'];
                $salesuser->heirarchy_id = $row["heirarchy_id"];
                $salesdata[] = $salesuser;
            }

            return $salesdata;
        }
        return null;
    }

    /**
     * To add new city in city-master
     * @param string $cityname
     */
    public function insert_citydata($cityname) {
        $Query = "Insert into city_master (customerno,cityname,entrytime,addedby) VALUES($this->customerno,'%s','$this->today',$this->userid)";
        $SQL = sprintf($Query, Sanitise::String($cityname));
        $this->executeQuery($SQL);
    }

    /**
     * to check if city name exists in city-master
     * @param string $cityname
     * @return boolean
     */
    public function is_city_exists($cityname) {
        $Query = "SELECT cityid FROM city_master WHERE customerno=$this->customerno AND cityname='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($cityname));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * to check if location name exists in location-master
     * @param string $location
     * @return boolean
     */
    public function is_location_exists($locationname, $cityid) {
        $Query = "SELECT location FROM location_master WHERE customerno=$this->customerno AND location='%s' and cityid=%d AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($locationname), $cityid);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * to check if trackie  emailid exists in trackie-master
     * @param string $emailid
     * @return boolean
     */
    public function is_trackie_email_exists($email) {
        $Query = "SELECT email FROM trackie WHERE customerno=$this->customerno AND email='%s' AND isdeleted=0";
        $SQL = sprintf($Query, $email);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * To add new trackie in trackie-master
     * @param string $trackiename,$phone,$email,$address,$weeklyoff
     */
    function insert_trackiedata($trackiename, $phone, $email, $address, $weeklyoff) {
        $Query = "Insert into trackie (customerno,name,phone,email,address,weekly_off,entrytime,addedby)VALUES($this->customerno,'%s',%d,'%s','%s','%s','$this->today',$this->userid)";
        $SQL = sprintf($Query, Sanitise::String($trackiename), $phone, Sanitise::String($email), Sanitise::String($address), $weeklyoff);
        $this->executeQuery($SQL);
    }

    /**
     * To add new location in location-master
     * @param string $locationname
     */
    public function insert_locationdata($cityid, $locationname) {
        $Query = "Insert into location_master (location,customerno,cityid,entrytime,addedby) VALUES('%s',$this->customerno,%d,'$this->today',$this->userid)";
        $SQL = sprintf($Query, Sanitise::String($locationname), $cityid, $this->userid);
        $this->executeQuery($SQL);
    }

    /**
     * City like query
     * @param string $q
     * @return type
     */
    public function getcitydata($q) {
        $getdata = array();
        $lq = "%$q%";
        $Query = "SELECT cityid,cityname FROM city_master WHERE customerno=$this->customerno AND cityname LIKE '%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($lq));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getdata[] = array(
                    'id' => $row['cityid'],
                    'value' => $row['cityname'],
                );
            }
            return $getdata;
        }
        return null;
    }

    /**
     * To check if service-name already exists
     * @param string $sname
     * @return boolean
     */
    public function is_service_exists($sname) {
        $Query = "SELECT serviceid FROM service_list WHERE customerno=$this->customerno AND service_name='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($sname));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * To insert service
     * @param string $sdata
     */
    public function add_service($sdata) {
        extract($sdata);
        $Query = "Insert into service_list (customerno,service_name,cost,expected_time, entrytime, addedby)
            VALUES($this->customerno,'%s',%f,%d, '$this->today', $this->userid)";
        $SQL = sprintf($Query, Sanitise::String($service_name), Sanitise::Float($cost), $expTime);
        $this->executeQuery($SQL);
    }

    /**
     * to check if category name exists in category
     * @param string $catname
     * @return boolean
     */
    public function is_category_exists($catname) {
        $Query = "SELECT categoryname FROM category WHERE customerno=$this->customerno AND categoryname='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($catname));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function is_shoptype_exists($stname) {
        $Query = "SELECT shop_type FROM shoptype WHERE customerno=$this->customerno AND shop_type='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($stname));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * To add new Category in category table
     * @param string $catname
     */
    public function add_categorydata($catname) {
        $Query = "Insert into category (customerno,categoryname,entrytime,addedby) VALUES($this->customerno,'%s','$this->today',$this->userid)";
        $SQL = sprintf($Query, Sanitise::String($catname));
        $this->executeQuery($SQL);
    }

    public function add_shoptypedata($stype) {
        $Query = "Insert into shoptype (customerno,shop_type,entrytime,addedby) VALUES($this->customerno,'%s','$this->today',$this->userid)";
        $SQL = sprintf($Query, Sanitise::String($stype));
        $this->executeQuery($SQL);
    }

    public function update_categorydata($catname, $catid) {
        $Query = "update category set categoryname='" . $catname . "' ,updatedtime='" . $this->today . "', updated_by='" . $this->userid . "' where categoryid=" . $catid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function update_shtypedata($stypename, $shid) {
        $Query = "update shoptype set shop_type='" . $stypename . "' ,updatedtime='" . $this->today . "', updated_by='" . $this->userid . "' where shid=" . $shid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function delete_categorydata($catid) {
        $Query = "update category set isdeleted=1 where categoryid=" . $catid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function delete_orderdata($oid) {
        $Query = "update orders set isdeleted=1 where orderid=" . $oid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    /**
     * to check if style no exists in style add
     * @param string $styleno
     * @return boolean
     */
    public function is_style_number_exists($styleno) {
        $Query = "SELECT styleno FROM style WHERE customerno=$this->customerno AND styleno='%s' AND isdeleted=0";
        $SQL = sprintf($Query, $styleno);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * To add new styleno in style table
     * @param string $styleno
     */
    public function insert_styledata($styleno, $category, $mrp, $distprice, $retprice, $carton) {
        $Query = "Insert into style (customerno,styleno,categoryid,mrp,distprice,retailprice,carton,entrytime,addedby) VALUES ($this->customerno,'%s','%s','%s','%s','%s','%s','$this->today',$this->userid)";
        $SQL = sprintf($Query, Sanitise::String($styleno), Sanitise::String($category), Sanitise::Float($mrp), Sanitise::Float($distprice), Sanitise::Float($retprice), Sanitise::String($carton)
        );
        $this->executeQuery($SQL);
    }

    public function update_styledata($skuid, $styleno, $category, $mrp, $distprice, $retprice, $carton) {
        $Query = "update style set styleno='" . $styleno . "',categoryid=" . $category . ",carton=" . $carton . ",styleno = '" . $styleno . "',mrp='" . $mrp . "',distprice='" . $distprice . "',retailprice='" . $retprice . "',updatedtime='" . $this->today . "', updated_by='" . $this->userid . "' where styleid=" . $skuid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function delete_styledata($skuid) {
        $Query = "update style set isdeleted=1  where skuid=" . $skuid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    /**
     * to check if State name exists in state
     * @param string $statename
     * @return boolean
     */
    public function is_state_exists($statename) {
        $Query = "SELECT statename FROM state WHERE customerno=$this->customerno AND statename='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($statename));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * To add new Statename in State table
     * @param string $statename
     */
    public function add_statedata($statename) {
        $Query = "Insert into state (customerno,statename,entrytime,addedby) VALUES($this->customerno,'%s','$this->today',$this->userid)";
        $SQL = sprintf($Query, Sanitise::String($statename));
        $this->executeQuery($SQL);
    }

    public function update_statedata($statename, $stateid) {
        $Query = "update state set statename='" . $statename . "',updatedtime='" . $this->today . "', updated_by='" . $this->userid . "'  where stateid=" . $stateid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function delete_statedata($stateid) {
        $Query = "update state set isdeleted=1  where stateid=" . $stateid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function getdevicelist() {
        $Query = "SELECT *, Now() as today FROM `devices` where customerno= $this->customerno";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $device = new stdClass();
                $device->deviceid = $row['deviceid'];
                $device->devicekey = $row['devicekey'];
                $device->salesid = $row['salesid'];
                if ($row["isregistered"] == 1) {
                    $device->status = "Valid";
                } else {
                    $device->status = "Expired";
                }
                $device->today = $row["today"];
                $device->contract = $row["contract"];
                $device->registrationapprovedon = $row["registrationapprovedon"];
                $devices[] = $device;
            }
            return $devices;
        }
        return null;
    }

    public function getsaleslist() {
        $Query = 'SELECT * FROM ' . SPEEDDB . '.`user` where role="sales_representative" AND customerno=%d AND isdeleted=0';
        $sales = Array();
        $salesQuery = sprintf($Query, $this->customerno);
        $this->executeQuery($salesQuery);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $sale = new stdClass();
                $sale->userid = $row['userid'];
                $sale->realname = $row['realname'];
                $sales[] = $sale;
            }
            return $sales;
        }
        return null;
    }

    public function getdevicefromsales($salesid) {
        $devicesQuery = sprintf("SELECT *, Now() as today FROM `devices` where salesid = %d AND customerno=%d", Sanitise::String($salesid), $this->customerno);
        $this->executeQuery($devicesQuery);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $device = new stdClass();
                $device->deviceid = $row['deviceid'];
                $device->devicekey = $row['devicekey'];
                $device->salesid = $row['salesid'];
                $device->today = $row['today'];
            }
            return $device;
        }
        return null;
    }

    public function mapdevicetosales($deviceid, $salesid) {
        $SQL = sprintf("Update devices
                        Set `salesid`=%d
                        WHERE deviceid = %d AND customerno = %d", Sanitise::Long($salesid), Sanitise::Long($deviceid), $this->customerno);
        $this->executeQuery($SQL);
    }

    public function demapdevice($deviceid) {
        $SQL = sprintf("Update devices
                        Set `salesid`=0
                        WHERE deviceid = %d AND customerno = %d", Sanitise::Long($deviceid), $this->customerno);
        $this->executeQuery($SQL);
    }

    public function get_category() {
        $Query = "SELECT categoryid,categoryname FROM category WHERE customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getcatdata[] = array(
                    'id' => $row['categoryid'],
                    'value' => $row['categoryname']
                );
            }
            return $getcatdata;
        }
        return null;
    }

    public function get_category_api() {
        $Query = "SELECT categoryid,categoryname FROM category WHERE customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getcatdata[] = array(
                    'categoryid' => $row['categoryid'],
                    'categoryname' => $row['categoryname']
                );
            }
            return $getcatdata;
        }
        return null;
    }

    public function get_shoptype_api() {
        $Query = "SELECT shid,shop_type FROM shoptype WHERE customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getcatdata[] = array(
                    'shid' => $row['shid'],
                    'shop_type' => $row['shop_type']
                );
            }
            return $getcatdata;
        }
        return null;
    }

    public function get_state() {
        $Query = "SELECT stateid,statename FROM state WHERE customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getstatedata[] = array(
                    'id' => $row['stateid'],
                    'value' => $row['statename']
                );
            }
            return $getstatedata;
        }
        return null;
    }

    /**
     * to check if ASM name exists in asm
     * @param string $asmname
     * @return boolean
     */
    public function is_asm_exists($asmname) {
        $Query = "SELECT asmname FROM ASM WHERE customerno=$this->customerno AND asmname='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($asmname));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * To add new asmname in ASM table
     * @param string $asmname
     */
    public function add_asmdata($stateid, $asmname) {
        $Query = "Insert into ASM (customerno,stateid,asmname,entrytime,addedby) VALUES($this->customerno,'%d','%s','$this->today',$this->userid)";
        $SQL = sprintf($Query, (int) $stateid, Sanitise::String($asmname));
        $this->executeQuery($SQL);
    }

    public function update_asmdata($asmid, $stateid, $asmname) {
        $Query = "update ASM set stateid='" . $stateid . "',asmname='" . $asmname . "',updatedtime='" . $this->today . "', updated_by='" . $this->userid . "'  where asmid=" . $asmid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function delete_asmdata($asmid) {
        $Query = "update ASM set isdeleted=1 where asmid=" . $asmid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function approve_stockdata($stockid) {
        $Query = "update primary_order set is_approved=1 where prid=" . $stockid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function approve_primarydata_api($stockid, $approval) {
        $Query = "update primary_order set is_approved=%d where prid=" . $stockid;
        $SQL = sprintf($Query, (int) $approval);
        $this->executeQuery($SQL);
        return 'ok';
    }

    public function reject_stockdata($id) {
        $Query = "update primary_order set is_approved=-1 where prid=" . $id;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function delete_primaryorder($id) {

        $Query = "UPDATE primary_order SET isdeleted=1, updated_by='" . $this->userid . "',updated_time='" . $this->today . "' where prid=" . $id;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);

        $Query2 = "UPDATE primary_order_productlist SET isdeleted=1 where prid=" . $id;
        $SQL2 = sprintf($Query2);
        $this->executeQuery($SQL2);
    }

    public function get_asm() {
        $Query = "SELECT asmid,asmname FROM ASM WHERE customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getasmdata[] = array(
                    'id' => $row['asmid'],
                    'value' => $row['asmname']
                );
            }
            return $getasmdata;
        }
        return null;
    }

    /**
     * to check if Srcode  exists in sales table
     * @param string $srcode
     * @return boolean
     */
    public function is_srcode_exists($srcode) {
        $Query = "SELECT srcode FROM sales WHERE customerno=$this->customerno AND srcode='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($srcode));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * To add new Sales data in Sales table
     * @param string $srcode all
     */
    public function add_salesdata($asmid, $srcode, $srname, $srphoneno, $dob1) {
        $dob = date('Y-m-d', strtotime($dob1));

        $Query = "Insert into sales (customerno,dob,asmid,srcode,srname,phone,entrytime,addedby) VALUES($this->customerno,'%s',%d,'%s','%s','%s','$this->today',$this->userid)";
        $SQL = sprintf($Query, Sanitise::string($dob), (int) $asmid, Sanitise::String($srcode), Sanitise::String($srname), Sanitise::String($srphoneno));
        $this->executeQuery($SQL);
    }

    public function update_salesdata($salesid, $asmid, $srcode, $srname, $srphoneno) {
        $Query = "update sales set asmid='" . $asmid . "',srcode='" . $srcode . "',srname='" . $srname . "',phone='" . $srphoneno . "',updatedtime='" . $this->today . "', updated_by='" . $this->userid . "'  where salesid=" . $salesid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function get_sales() {
        $Query = "SELECT * FROM sales WHERE customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getsalesdata[] = array(
                    'id' => $row['salesid'],
                    'value' => $row['srname']
                );
            }
            return $getsalesdata;
        }
        return null;
    }

    public function get_srcode() {
        $Query = "SELECT * FROM sales WHERE customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getsrcodedata[] = array(
                    'id' => $row['salesid'],
                    'value' => $row['srcode'],
                    'srname' => $row['srname']
                );
            }
            return $getsrcodedata;
        }
        return null;
    }

    public function get_distid() {
        $Query = "SELECT * FROM sales WHERE customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getsrcodedata[] = array(
                    'id' => $row['salesid'],
                    'value' => $row['srcode']
                );
            }
            return $getsrcodedata;
        }
        return null;
    }

    public function get_shop() {
        $getshopdata = array();
        $Query = "SELECT * FROM shop WHERE customerno = $this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getshopdata[] = array(
                    'id' => $row['shopid'],
                    'value' => $row['shopname']
                );
            }
            return $getshopdata;
        }
        return null;
    }

    public function get_shop_api($saleid, $areaid) {
        $getshopdata = array();
        $Query = "SELECT shoptypeid, shopid, shopname,areaid, sequence_no FROM shop WHERE areaid = $areaid AND salesid = " . $saleid . " AND customerno = $this->customerno AND isdeleted=0 ORDER BY sequence_no ASC";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getshopdata[] = array(
                    'shopid' => utf8_encode($row['shopid']),
                    'shopname' => utf8_encode($row['shopname']),
                    'areaid' => utf8_encode($row['areaid']),
                    'shoptypeid' => utf8_encode($row['shoptypeid']),
                    'sequence_no' => utf8_encode($row['sequence_no'])
                );
            }
            return $getshopdata;
        }
        return NULL;
    }

    public function get_shopcount() {
        $Query = "SELECT count(shopid) as shopidcount FROM shop WHERE  customerno = $this->customerno AND isdeleted=0 ";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $shopidcount = $row['shopidcount'];
            }
            return $shopidcount;
        }
        return NULL;
    }

    public function get_sku_api() {
        $getskudata = array();
        $Query = "SELECT styleid,styleno,categoryid,mrp,distprice,retailprice,carton FROM style WHERE customerno = $this->customerno AND isdeleted=0 ";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {

            while ($row = $this->get_nextRow()) {
                $getskudata[] = array(
                    'skuid' => utf8_encode($row['styleid']),
                    'categoryid' => utf8_encode($row['categoryid']),
                    'styleno' => utf8_encode($row['styleno']),
                );
            }
            return $getskudata;
        }
        return NULL;
    }

    public function get_area_api($distid) {
        $distidin = "";
        if (is_array($distid)) {
            $distid = implode(",", $distid);
            $distidin = " distributorid IN (" . $distid . ") AND ";
        }
        $getareadata = array();
        $Query = "SELECT areaid,areaname,distributorid FROM area WHERE  $distidin customerno = $this->customerno AND isdeleted=0 ";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {

            while ($row = $this->get_nextRow()) {
                $getareadata[] = array(
                    'areaid' => utf8_encode($row['areaid']),
                    'areaname' => utf8_encode($row['areaname']),
                    'distributorid' => utf8_encode($row['distributorid'])
                );
            }
            return $getareadata;
        }
        return NULL;
    }

    public function getareaidtodistid_api($areaid) {
        $Query = "select distributorid, areaid from area where areaid= $areaid AND customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        $distid = "0";
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $distid = $row['distributorid'];
            }
        }
        return $distid;
    }

    /**
     * to check if distcode  exists in distributor table
     * @param string $distcode
     * @return boolean
     */
    public function is_distcode_exists($distcode) {
        $Query = "SELECT distcode FROM distributor WHERE customerno=$this->customerno AND distcode='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($distcode));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * To add new Distributor data in distributor table
     * @param string $distid all
     */
    public function add_distdata($saleid, $distcode, $distname, $dob1, $distphone, $emailid) {
        $dob = date('Y-m-d', strtotime($dob1));
        $Query = "Insert into distributor (customerno,dob,demail,dphone,salesid,distcode,distname,entrytime,addedby) VALUES($this->customerno,'%s','%s',%d,%d,'%s','%s','$this->today',$this->userid)";
        $SQL = sprintf($Query, Sanitise::String($dob), Sanitise::String($emailid), (int) $distphone, (int) $saleid, Sanitise::String($distcode), Sanitise::String($distname));
        $this->executeQuery($SQL);
    }

    public function update_distdata($distid, $saleid, $distcode, $distname, $dob1, $distphone, $emailid) {
        $dob = date('Y-m-d', strtotime($dob1));
        $Query = "update distributor set dob = '" . $dob . "',salesid='" . $saleid . "',demail='" . $emailid . "',dphone='" . $distphone . "',distcode='" . $distcode . "',distname='" . $distname . "',updatedtime='" . $this->today . "', updated_by='" . $this->userid . "'  where distributorid=" . $distid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function delete_distdata($id) {
        $Query = "update distributor set isdeleted=1 where distributorid=" . $id;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

//    public function get_distributor() {
//        $Query = "SELECT distributorid,distname,distcode FROM distributor WHERE customerno=$this->customerno AND isdeleted=0";
//        $SQL = sprintf($Query);
//        $this->executeQuery($SQL);
//        if ($this->get_rowCount() > 0) {
//            while ($row = $this->get_nextRow()) {
//                $getdistdata[] = array(
//                    'id' => $row['distributorid'],
//                    'value' => $row['distcode']
//                );
//            }
//            return $getdistdata;
//        }
//        return null;
//    }

    public function get_saleswise_distributor($salesid) {
        $Query = "SELECT distributorid,distname,distcode,salesid FROM distributor WHERE salesid=" . $salesid . " AND customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getdistdata[] = array(
                    'id' => $row['distributorid'],
                    'value' => $row['distcode']
                );
            }
            return $getdistdata;
        }
        return null;
    }

    /**
     * to check if areaname  exists in area table
     * @param string $areaname
     * @return boolean
     */
    public function is_areaname_exists($areaname) {
        $Query = "SELECT areaname FROM area WHERE customerno=$this->customerno AND areaname='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($areaname));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * To add new Area Name data in area table
     * @param string $areaname all
     */
    public function add_areadata($distid, $areaname) {
        $Query = "Insert into area (customerno,distributorid,areaname,entrytime,addedby) VALUES($this->customerno,%d,'%s','$this->today',$this->userid)";
        $SQL = sprintf($Query, (int) $distid, Sanitise::String($areaname));
        $this->executeQuery($SQL);
    }

    public function update_areadata($areaid, $distid, $areaname) {
        $Query = "update area set distributorid='" . $distid . "',	areaname='" . $areaname . "',updatedtime='" . $this->today . "', updated_by='" . $this->userid . "'  where 	areaid=" . $areaid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function delete_areadata($id) {
        $Query = "update area set isdeleted=1 where areaid=" . $id;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function get_area() {
        $Query = "SELECT areaid,areaname FROM area WHERE customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getareadata[] = array(
                    'id' => $row['areaid'],
                    'value' => $row['areaname']
                );
            }
            return $getareadata;
        }
        return null;
    }

    /**
     * to check if shopname  exists in shop table
     * @param string $shopname
     * @return boolean
     */
    public function is_shopname_exists($shopname) {
        $Query = "SELECT shopname FROM shop WHERE customerno=$this->customerno AND shopname='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($shopname));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * To add new Area Name data in area table
     * @param string $areaname all
     */
    public function add_shopdata($shoptype, $prior_shopid, $distid, $saleid, $areaid, $shopname, $sphoneno, $sphoneno2, $owner, $saddress, $semail, $dob1) {
        $owner_shop = $owner . '-' . $shopname;
        $dob = date('Y-m-d', strtotime($dob1));
        $newseq = 1;
        if ($prior_shopid != 0) {
//      Shop sequnecing as per area
            $seqdata = $this->getseqid_byshopid($prior_shopid);
            $preshopid = $seqdata[0]['shopid'];
            $preareaid = $seqdata[0]['areaid'];
            $preseq = $seqdata[0]['sequence_no'];
            $newseq = $preseq + 1;
            $old_shopdata_area = $this->getshopdata_byarea($areaid);


            foreach ($old_shopdata_area as $row) {
                if ($row['shopid'] != $preshopid) {
                    $Query = "Update shop set sequence_no = sequence_no +1 where shopid = " . $row['shopid'] . " AND shopid<> " . $preshopid . "  AND  sequence_no > $preseq AND customerno=" . $this->customerno;
                    $SQL = sprintf($Query);
                    $this->executeQuery($SQL);
                }
            }
        }

        $Query = "Insert into shop (customerno,distributorid,dob,salesid,areaid,shopname,shoptypeid,phone,phone2,owner,owner_shop,address,emailid,sequence_no,entrytime,addedby) VALUES($this->customerno,%d,'%s',%d,%d,'%s',%d,'%s','%s','%s','%s','%s','%s','%s','$this->today',$this->userid)";
        $SQL = sprintf($Query, (int) $distid, Sanitise::string($dob), (int) $saleid, (int) $areaid, Sanitise::String($shopname), $shoptype, Sanitise::String($sphoneno), Sanitise::String($sphoneno2), Sanitise::String($owner), Sanitise::String($owner_shop), Sanitise::String($saddress), Sanitise::String($semail), (int) $newseq);
        $this->executeQuery($SQL);
    }

    public function add_shopdata_api($prior_shopid, $shoptype, $distid, $saleid, $areaid, $shopname, $sphoneno, $sphoneno2 = null, $owner, $saddress, $semail, $dob1, $signature = null, $photo = null, $status, $androidshopid, $goods_avail) {
        $seq = 0;
        $owner_shop = $owner . '-' . $shopname;
        $dob = date('Y-m-d', strtotime($dob1));
        if ($status == 1) {
            $androidshopid1 = $androidshopid;
        } else {
            $androidshopid1 = 0;
        }
        $newseq = 1;
        if ($prior_shopid != 0) {
//      Shop sequnecing as per area
            $seqdata = $this->getseqid_byshopid($prior_shopid);
            $preshopid = $seqdata[0]['shopid'];
            $preareaid = $seqdata[0]['areaid'];
            $preseq = $seqdata[0]['sequence_no'];
            $presalesid = $seqdata[0]['salesid'];
            $newseq = $preseq + 1;
            $old_shopdata_area = $this->getshopdata_byarea($areaid);


            foreach ($old_shopdata_area as $row) {
                if ($row['shopid'] != $preshopid) {
                    $Query = "Update shop set sequence_no = sequence_no +1 where salesid= " . $presalesid . " AND  areaid= " . $preareaid . " AND shopid = " . $row['shopid'] . " AND shopid<> " . $preshopid . "  AND  sequence_no > $preseq AND customerno=" . $this->customerno;
                    $SQL = sprintf($Query);
                    $this->executeQuery($SQL);
                }
            }
        }
        $Query = "Insert into shop (customerno,androidshop_id,distributorid,dob,salesid,areaid,shopname,shoptypeid,phone,phone2,owner,owner_shop,address,emailid,goods_availability,status,sequence_no,entrytime,addedby) VALUES($this->customerno,%d,%d,'%s',%d,%d,'%s',%d,'%s','%s','%s','%s','%s','%s',%d,%d,%d,'$this->today',$this->userid)";
        $SQL = sprintf($Query, (int) $androidshopid1, (int) $distid, Sanitise::string($dob), (int) $saleid, (int) $areaid, Sanitise::String($shopname), $shoptype, Sanitise::String($sphoneno), Sanitise::String($sphoneno2), Sanitise::String($owner), Sanitise::String($owner_shop), Sanitise::String($saddress), Sanitise::String($semail), (int) $goods_avail, (int) $status, (int) $newseq);
        $this->executeQuery($SQL);
        $id = $this->get_insertedId();
        $sql = "SELECT * from " . SPEEDDB . ".user WHERE userid = '$saleid' AND isdeleted = 0 limit 1";
        $result = $this->executeQuery($sql);
        if ($signature != NULL || $photo != NULL) {
            if ($this->get_rowCount() > 0) {
                while ($row = $this->get_nextRow()) {
                    $customerno = $row['customerno'];
                    $userid = $row['userid'];
                }
                $target_path = "../../customer/" . $customerno . "/secsales/";
                if (!is_dir($target_path)) {
                    mkdir($target_path, 0777, true) or die("Could not create directory");
                }
                $target_path_signature = $target_path . "signature/";
                $target_path_photo = $target_path . "photo/";

                if (!is_dir($target_path_signature)) {
                    mkdir($target_path_signature, 0777, true) or die("Could not create directory");
                }

                if (!is_dir($target_path_photo)) {
                    mkdir($target_path_photo, 0777, true) or die("Could not create directory");
                }


                if ($signature != NULL) {
                    $image = base64_decode($signature);
                    file_put_contents("../../customer/" . $customerno . "/secsales/signature/" . $id . ".jpg", $image);
                }

                if ($photo != NULL) {
                    $image1 = base64_decode($photo);
                    file_put_contents("../../customer/" . $customerno . "/secsales/photo/" . $id . ".jpg", $image1);
                }
            }
        }
    }

    public function update_shopdata($shoptypeid, $sid, $distid, $saleid, $areaid, $shopname, $sphoneno, $sphoneno2, $owner, $saddress, $semail, $dob1) {
        $owner_shop = $owner . '-' . $shopname;
        $dob = date('Y-m-d', strtotime($dob1));
        $Query = "update shop set dob='" . $dob . "',distributorid='" . $distid . "',salesid='" . $saleid . "',areaid='.$areaid.',shopname='" . $shopname . "',shoptypeid=" . $shoptypeid . ",phone='" . $sphoneno . "',phone2='" . $sphoneno2 . "',owner='" . $owner . "',owner_shop='" . $owner_shop . "',address='" . $saddress . "',emailid='" . $semail . "',updatedtime='" . $this->today . "', updated_by='" . $this->userid . "' where shopid=" . $sid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function delete_shopdata($id) {
        $Query = "update shop set isdeleted=1 where shopid=" . $id;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function delete_shoptypedata($id) {
        $Query = "update shoptype set isdeleted=1 where shid=" . $id;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function get_catview() {
        $catviewdata = array();
        $Query = "SELECT * FROM category WHERE customerno=$this->customerno AND isdeleted=0 order by categoryid desc";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $catview = new stdClass();
                $catview->categoryid = $row['categoryid'];
                $catview->categoryname = $row['categoryname'];
                $catview->customerno = $row['customerno'];
                $catview->entrytime = $row['entrytime'];
                $catview->addedby = $row['addedby'];
                $catview->updatedtime = $row['updatedtime'];
                $catview->updated_by = $row['updated_by'];
                $catview->isdeleted = $row['isdeleted'];
                $catviewdata[] = $catview;
            }
            return $catviewdata;
        }
        return null;
    }

    public function get_shoptypeview() {
        $stypedata = array();
        $Query = "SELECT * FROM shoptype WHERE customerno=$this->customerno AND isdeleted=0 order by shid desc";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $shview = new stdClass();
                $shview->shop_type = $row['shop_type'];
                $shview->shid = $row['shid'];
                $stypedata[] = $shview;
            }
            return $stypedata;
        }
        return null;
    }

    public function get_catedit($catid) {
        $cateditdata = array();
        $Query = "SELECT * FROM category WHERE customerno=$this->customerno AND categoryid =" . $catid . " AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $catedit = new stdClass();
                $catedit->categoryid = $row['categoryid'];
                $catedit->categoryname = $row['categoryname'];
                $catedit->customerno = $row['customerno'];
                $catedit->entrytime = $row['entrytime'];
                $catedit->addedby = $row['addedby'];
                $catedit->updatedtime = $row['updatedtime'];
                $catedit->updated_by = $row['updated_by'];
                $catedit->isdeleted = $row['isdeleted'];
//$support1->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['create_on_date']));
                $cateditdata[] = $catedit;
            }
            return $cateditdata;
        }
        return null;
    }

    public function get_shedit($shid) {
        $sheditdata = array();
        $Query = "SELECT * FROM shoptype WHERE customerno=$this->customerno AND shid =" . $shid . " AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $catedit = new stdClass();
                $catedit->shid = $row['shid'];
                $catedit->shop_type = $row['shop_type'];
                $sheditdata[] = $catedit;
            }
            return $sheditdata;
        }
        return null;
    }

    public function get_stateview() {
        $stateviewdata = array();
        $Query = "SELECT * FROM state WHERE customerno=$this->customerno AND isdeleted=0 order by stateid desc";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $staview = new stdClass();
                $staview->stateid = $row['stateid'];
                $staview->statename = $row['statename'];
                $staview->customerno = $row['customerno'];
                $staview->entrytime = $row['entrytime'];
                $staview->addedby = $row['addedby'];
                $staview->updatedtime = $row['updatedtime'];
                $staview->updated_by = $row['updated_by'];
                $staview->isdeleted = $row['isdeleted'];
//$support1->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['create_on_date']));
                $stateviewdata[] = $staview;
            }
            return $stateviewdata;
        }
        return null;
    }

    public function get_stateedit($stateid) {
        $stateditdata = array();
        $Query = "SELECT * FROM state WHERE customerno=$this->customerno AND stateid =" . $stateid . " AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $stedit = new stdClass();
                $stedit->stateid = $row['stateid'];
                $stedit->statename = $row['statename'];
                $stedit->customerno = $row['customerno'];
                $stedit->entrytime = $row['entrytime'];
                $stedit->addedby = $row['addedby'];
                $stedit->updatedtime = $row['updatedtime'];
                $stedit->updated_by = $row['updated_by'];
                $stedit->isdeleted = $row['isdeleted'];
//$support1->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['create_on_date']));
                $stateditdata[] = $stedit;
            }
            return $stateditdata;
        }
        return null;
    }

    public function get_styleview() {
        $styleviewdata = array();
        $Query = "SELECT * FROM style WHERE customerno=$this->customerno AND isdeleted=0  order by styleid desc ";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $styview = new stdClass();
                $styview->skuid = $row['styleid'];
                $styview->categoryid = $row['categoryid'];
                $styview->styleno = $row['styleno'];
                $styview->mrp = $row['mrp'];
                $styview->distprice = $row['distprice'];
                $styview->retailprice = $row['retailprice'];
                $styview->customerno = $row['customerno'];
                $styview->entrytime = $row['entrytime'];
                $styview->addedby = $row['addedby'];
                $styview->updatedtime = $row['updatedtime'];
                $styview->updated_by = $row['updated_by'];
                $styview->isdeleted = $row['isdeleted'];
//$support1->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['create_on_date']));
                $styleviewdata[] = $styview;
            }
            return $styleviewdata;
        }
        return null;
    }

    public function get_stockview() {
        $stockviewdata = array();
        $Query = "SELECT s.stockid,sl.srcode,d.distname,d.distcode,s.quantity,c.categoryname,st.styleno, s.stockdate FROM stock as s
                left join distributor as d on d.distributorid = s.distributorid
                left join sales as sl on sl.salesid = d.salesid
                left join style as st on st.skuid = s.skuid
                left join category as c on c.categoryid = st.categoryid
                WHERE s.customerno= $this->customerno AND s.isdeleted=0  order by s.stockid desc";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $styview = new stdClass();
                $styview->stockid = $row['stockid'];
                $styview->srcode = $row['srcode'];
                $styview->distcode = $row['distcode'];
                $styview->categoryname = $row['categoryname'];
                $styview->styleno = $row['styleno'];
                $styview->stockdate = $row['stockdate'];
                $styview->quantity = $row['quantity'];
                $stockviewdata[] = $styview;
            }
            return $stockviewdata;
        }
        return null;
    }

    public function get_stockedit($stockid) {
        $stockviewdata = array();
        $Query = "SELECT s.stockid,sl.srcode,sl.salesid,d.distributorid,d.distname,d.distname,d.distcode,s.quantity,c.categoryname,c.categoryid,st.styleno,st.skuid ,s.stockdate FROM stock as s
                left join distributor as d on d.distributorid = s.distributorid
                left join sales as sl on sl.salesid = d.salesid
                left join style as st on st.skuid = s.skuid
                left join category as c on c.categoryid = st.categoryid
                WHERE s.customerno= $this->customerno AND stockid = $stockid AND s.isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $styview = new stdClass();

                $styview->stockid = $row['stockid'];
                $styview->srcode = $row['srcode'];
                $styview->salesid = $row['salesid'];
                $styview->distcode = $row['distcode'];
                $styview->distname = $row['distname'];
                $styview->distributorid = $row['distributorid'];
                $styview->categoryid = $row['categoryid'];
                $styview->categoryname = $row['categoryname'];
                $styview->skuid = $row['skuid'];
                $styview->styleno = $row['styleno'];
                $styview->stockdate = $row['stockdate'];
                $styview->quantity = $row['quantity'];
                $stockviewdata[] = $styview;
            }
            return $stockviewdata;
        }
        return null;
    }

    public function get_styleedit($skuid) {
        $styleeditdata = array();
        $Query = "SELECT * FROM style WHERE customerno=$this->customerno AND styleid = $skuid AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $styedit = new stdClass();
                $styedit->styleid = $row['styleid'];
                $styedit->categoryid = $row['categoryid'];
                $styedit->styleno = $row['styleno'];
                $styedit->mrp = $row['mrp'];
                $styedit->distprice = $row['distprice'];
                $styedit->retailprice = $row['retailprice'];
                $styedit->customerno = $row['customerno'];
                $styedit->entrytime = $row['entrytime'];
                $styedit->addedby = $row['addedby'];
                $styedit->updatedtime = $row['updatedtime'];
                $styedit->updated_by = $row['updated_by'];
                $styedit->isdeleted = $row['isdeleted'];
                $styedit->carton = $row['carton'];
//$support1->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['create_on_date']));
                $styleeditdata[] = $styedit;
            }
            return $styleeditdata;
        }
        return null;
    }

    public function get_asmview() {
        $asmviewdata = array();
        $Query = "SELECT * FROM ASM left join state on state.stateid = ASM.stateid WHERE ASM.customerno=$this->customerno AND ASM.isdeleted=0 order by asmid desc";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $asmview = new stdClass();
                $asmview->asmid = $row['asmid'];
                $asmview->stateid = $row['stateid'];
                $asmview->asmname = $row['asmname'];
                $asmview->statename = $row['statename'];
                $asmview->customerno = $row['customerno'];
                $asmview->entrytime = $row['entrytime'];
                $asmview->addedby = $row['addedby'];
                $asmview->updatedtime = $row['updatedtime'];
                $asmview->updated_by = $row['updated_by'];
                $asmview->isdeleted = $row['isdeleted'];
                $asmviewdata[] = $asmview;
            }
            return $asmviewdata;
        }
        return null;
    }

    public function get_asmedit($asmid) {
        $asmeditdata = array();
        $Query = "SELECT * FROM ASM WHERE customerno=$this->customerno AND asmid = $asmid AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $asmedit = new stdClass();
                $asmedit->asmid = $row['asmid'];
                $asmedit->stateid = $row['stateid'];
                $asmedit->asmname = $row['asmname'];
                $asmedit->customerno = $row['customerno'];
                $asmedit->entrytime = $row['entrytime'];
                $asmedit->addedby = $row['addedby'];
                $asmedit->updatedtime = $row['updatedtime'];
                $asmedit->updated_by = $row['updated_by'];
                $asmedit->isdeleted = $row['isdeleted'];
//$support1->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['create_on_date']));
                $asmeditdata[] = $asmedit;
            }
            return $asmeditdata;
        }
        return null;
    }

    public function get_areaedit($areaid) {
        $areaditdata = array();
        $Query = "SELECT * FROM area WHERE customerno=$this->customerno AND areaid = $areaid AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $areaedit = new stdClass();
                $areaedit->areaid = $row['areaid'];
                $areaedit->distributorid = $row['distributorid'];
                $areaedit->areaname = $row['areaname'];
                $areaedit->customerno = $row['customerno'];
                $areaedit->entrytime = $row['entrytime'];
                $areaedit->addedby = $row['addedby'];
                $areaedit->updatedtime = $row['updatedtime'];
                $areaedit->updated_by = $row['updated_by'];
                $areaedit->isdeleted = $row['isdeleted'];
//$support1->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['create_on_date']));
                $areaditdata[] = $areaedit;
            }
            return $areaditdata;
        }
        return null;
    }

    public function get_areaview() {
        $areviewdata = array();
        $Query = "SELECT * FROM area WHERE customerno=$this->customerno AND isdeleted=0 order by  areaid desc";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $areaedit = new stdClass();
                $areaedit->areaid = $row['areaid'];
                $areaedit->distributorid = $row['distributorid'];
                $areaedit->areaname = $row['areaname'];
                $areaedit->customerno = $row['customerno'];
                $areaedit->entrytime = $row['entrytime'];
                $areaedit->addedby = $row['addedby'];
                $areaedit->updatedtime = $row['updatedtime'];
                $areaedit->updated_by = $row['updated_by'];
                $areaedit->isdeleted = $row['isdeleted'];
//$support1->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['create_on_date']));
                $areviewdata[] = $areaedit;
            }
            return $areviewdata;
        }
        return null;
    }

    public function get_distedit($distid) {
        $disteditdata = array();
        $Query = "SELECT * FROM distributor WHERE customerno=$this->customerno AND distributorid = $distid AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $areaedit = new stdClass();
                $areaedit->distid = $row['distributorid'];
                $areaedit->salesid = $row['salesid'];
                $areaedit->distcode = $row['distcode'];
                $areaedit->distname = $row['distname'];
                $areaedit->dob = $row['dob'];
                $areaedit->dphone = $row['dphone'];
                $areaedit->demail = $row['demail'];
                $disteditdata[] = $areaedit;
            }
            return $disteditdata;
        }
        return null;
    }

    public function get_distview() {
        $Query = "SELECT count(userid) as distcount FROM " . SPEEDDB . ".`user` as u WHERE role='Distributor' AND u.customerno=$this->customerno AND u.isdeleted=0 ";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $distcount = $row['distcount'];
            }
            return $distcount;
        }
        return null;
    }

    public function get_saleview() {
        $saleviewdata = array();
        $Query = "SELECT * FROM sales WHERE customerno=$this->customerno AND isdeleted=0 order by salesid desc";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $areaedit = new stdClass();
                $areaedit->salesid = $row['salesid'];
                $areaedit->asmid = $row['asmid'];
                $areaedit->srcode = $row['srcode'];
                $areaedit->srname = $row['srname'];
                $areaedit->phone = $row['phone'];
                $areaedit->customerno = $row['customerno'];
                $areaedit->entrytime = $row['entrytime'];
                $areaedit->addedby = $row['addedby'];
                $areaedit->updatedtime = $row['updatedtime'];
                $areaedit->updated_by = $row['updated_by'];
                $areaedit->isdeleted = $row['isdeleted'];
                $saleviewdata[] = $areaedit;
            }
            return $saleviewdata;
        }
        return null;
    }

    public function get_saleedit($saleid) {
        $saleeditdata = array();
        $Query = "SELECT * FROM sales WHERE customerno=$this->customerno AND salesid= $saleid AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $areaedit = new stdClass();
                $areaedit->salesid = $row['salesid'];
                $areaedit->asmid = $row['asmid'];
                $areaedit->srcode = $row['srcode'];
                $areaedit->srname = $row['srname'];
                $areaedit->phone = $row['phone'];
                $areaedit->customerno = $row['customerno'];
                $areaedit->entrytime = $row['entrytime'];
                $areaedit->addedby = $row['addedby'];
                $areaedit->updatedtime = $row['updatedtime'];
                $areaedit->updated_by = $row['updated_by'];
                $areaedit->isdeleted = $row['isdeleted'];
                $areaedit->dob = $row['dob'];
                $saleeditdata[] = $areaedit;
            }
            return $saleeditdata;
        }
        return null;
    }

    public function get_shopview() {
        $shopviewdata = array();
        $Query = "SELECT * FROM shop WHERE customerno=$this->customerno AND isdeleted=0 order by shopid desc";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $areaedit = new stdClass();
                $areaedit->shopid = $row['shopid'];
                $areaedit->distributorid = $row['distributorid'];
                $areaedit->salesid = $row['salesid'];
                $areaedit->areaid = $row['areaid'];
                $areaedit->shopname = $row['shopname'];
                $areaedit->phone = $row['phone'];
                $areaedit->phone2 = $row['phone2'];
                $areaedit->owner = $row['owner'];
                $areaedit->address = $row['address'];
                $areaedit->emailid = $row['emailid'];
                $areaedit->customerno = $row['customerno'];
                $areaedit->entrytime = $row['entrytime'];
                $areaedit->addedby = $row['addedby'];
                $areaedit->updatedtime = $row['updatedtime'];
                $areaedit->updated_by = $row['updated_by'];
                $areaedit->isdeleted = $row['isdeleted'];
                $shopviewdata[] = $areaedit;
            }
            return $shopviewdata;
        }
        return null;
    }

    public function get_shopedit($sid) {
        $shopeditdata = array();
        $Query = "SELECT * FROM shop WHERE customerno=$this->customerno AND shopid= $sid AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $areaedit = new stdClass();
                $areaedit->shopid = $row['shopid'];
                $areaedit->distributorid = $row['distributorid'];
                $areaedit->salesid = $row['salesid'];
                $areaedit->areaid = $row['areaid'];
                $areaedit->shopname = $row['shopname'];
                $areaedit->phone = $row['phone'];
                $areaedit->phone2 = $row['phone2'];
                $areaedit->owner = $row['owner'];
                $areaedit->address = $row['address'];
                $areaedit->emailid = $row['emailid'];
                $areaedit->dob = $row['dob'];
                $areaedit->shoptypeid = $row['shoptypeid'];
                $shopeditdata[] = $areaedit;
            }
            return $shopeditdata;
        }
        return null;
    }

    public function getdistid($saleid = NULL) {
        $salesdata = null;
        $Query = sprintf("SELECT userid,role,roleid,email,realname,username,phone,heirarchy_id FROM " . SPEEDDB . ".`user` where isdeleted=0 AND customerno=" . $this->customerno . " AND role = 'Distributor' AND heirarchy_id=" . $saleid);
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $salesuser = new stdClass();
                $salesuser->userid = $row['userid'];
                $salesuser->role = $row['role'];
                $salesuser->roleid = $row['roleid'];
                $salesuser->email = $row["email"];
                $salesuser->realname = $row['realname'];
                $salesuser->username = $row["username"];
                $salesuser->phone = $row['phone'];
                $salesuser->heirarchy_id = $row["heirarchy_id"];
                $salesdata[] = $salesuser;
            }
            return $salesdata;
        }
        return null;
    }

    public function getdistributordata_api($saleid = NULL) {
        $salesdata = null;
        $Query = sprintf("SELECT userid,role,roleid,email,realname,username,phone,heirarchy_id FROM " . SPEEDDB . ".`user` where isdeleted=0 AND customerno=" . $this->customerno . " AND role = 'Distributor' AND heirarchy_id=" . $saleid);
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $salesuser = new stdClass();
                $salesuser->distributorid = $row['userid'];
                $salesuser->role = $row['role'];
                $salesuser->roleid = $row['roleid'];
                $salesuser->distemail = $row["email"];
                $salesuser->distname = $row['realname'];
                $salesuser->dphone = $row['phone'];
                $salesdata[] = $salesuser;
            }
            return $salesdata;
        }
        return null;
    }

    public function getallshoplist($saleid = '') {
        if (!empty($saleid)) {
            if (is_array($saleid)) {
                $salesid = implode(',', $saleid);
            } else {
                $salesid = $saleid;
            }
            $salesidin = "AND salesid IN( $salesid )";
        } else {
            $salesidin = "";
        }
        $shopdata = array();
        $Query = "SELECT * FROM shop WHERE customerno=$this->customerno $salesidin  AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $shop = new stdClass();
                $shop->shopname = $row['shopname'];
                $shop->shopid = $row['shopid'];
                $shopdata[] = $shop;
            }
            return $shopdata;
        }
        return null;
    }

    public function get_shopsbydist($distid, $customerno) {
        $distdata = array();
        $query = "select u.realname,st.shop_type,sh.shopname,sh.phone,sh.emailid, sh.shoptypeid from shop as sh left join " . SPEEDDB . ".`user` as u  on u.userid = sh.salesid left join shoptype as st on st.shid = sh.shoptypeid where sh.customerno=" . $customerno . " AND sh.distributorid=" . $distid . " AND sh.isdeleted=0 order by sh.salesid desc";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $dist = new stdClass();
                $dist->realname = $row['realname'];
                $dist->shopname = $row['shopname'];
                $dist->phone = $row['phone'];
                $dist->shop_type = $row['shop_type'];
                $dist->emailid = $row['emailid'];
                $distdata[] = $dist;
            }
            return $distdata;
        }
        return NULL;
    }

    public function getareaid($distid) {
        $areviewdata = array();
        $Query = "SELECT * FROM area WHERE customerno=$this->customerno AND distributorid= $distid AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $areaedit = new stdClass();
                $areaedit->areaid = $row['areaid'];
                $areaedit->distributorid = $row['distributorid'];
                $areaedit->areaname = $row['areaname'];
                $areaedit->customerno = $row['customerno'];
                $areaedit->entrytime = $row['entrytime'];
                $areaedit->addedby = $row['addedby'];
                $areaedit->updatedtime = $row['updatedtime'];
                $areaedit->updated_by = $row['updated_by'];
                $areaedit->isdeleted = $row['isdeleted'];
//$support1->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['create_on_date']));
                $areviewdata[] = $areaedit;
            }
            return $areviewdata;
        }
        return null;
    }

    public function getshopid($areaid) {
        $shopeditdata = array();
        $Query = "SELECT * FROM shop WHERE customerno=$this->customerno AND areaid= $areaid AND isdeleted=0 order by sequence_no asc ";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $areaedit = new stdClass();
                $areaedit->shopid = $row['shopid'];
                $areaedit->distributorid = $row['distributorid'];
                $areaedit->salesid = $row['salesid'];
                $areaedit->areaid = $row['areaid'];
                $areaedit->shopname = $row['shopname'];
                $areaedit->phone = $row['phone'];
                $areaedit->phone2 = $row['phone2'];
                $areaedit->owner = $row['owner'];
                $areaedit->address = $row['address'];
                $areaedit->emailid = $row['emailid'];
                $areaedit->sequence_no = $row['sequence_no'];
                $shopeditdata[] = $areaedit;
            }
            return $shopeditdata;
        }
        return null;
    }

    public function getshoplistbysr($salesid) {
        $shopeditdata = array();
        $Query = "SELECT shopid,shopname FROM shop WHERE customerno=$this->customerno AND salesid= $salesid AND isdeleted=0 order by sequence_no asc ";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $areaedit = new stdClass();
                $areaedit->shopid = $row['shopid'];
                $areaedit->shopname = $row['shopname'];
                $shopeditdata[] = $areaedit;
            }
            return $shopeditdata;
        }
        return null;
    }

    public function getdistbyshopid($distid) {
        $shopeditdata = array();
        $Query = "SELECT * FROM shop WHERE customerno=$this->customerno AND distributorid= $distid AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $areaedit = new stdClass();
                $areaedit->shopid = $row['shopid'];
                $areaedit->distributorid = $row['distributorid'];
                $areaedit->salesid = $row['salesid'];
                $areaedit->areaid = $row['areaid'];
                $areaedit->shopname = $row['shopname'];
                $areaedit->phone = $row['phone'];
                $areaedit->phone2 = $row['phone2'];
                $areaedit->owner = $row['owner'];
                $areaedit->address = $row['address'];
                $areaedit->emailid = $row['emailid'];
                $shopeditdata[] = $areaedit;
            }
            return $shopeditdata;
        }
        return null;
    }

    public function getskuid($catid) {
        $styleviewdata = array();
        $Query = "SELECT * FROM style WHERE customerno=$this->customerno AND categoryid= $catid AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $styview = new stdClass();
                $styview->skuid = $row['styleid'];
                $styview->categoryid = $row['categoryid'];
                $styview->styleno = $row['styleno'];
                $styview->mrp = $row['mrp'];
                $styview->distprice = $row['distprice'];
                $styview->retailprice = $row['retailprice'];
                $styview->customerno = $row['customerno'];
                $styview->entrytime = $row['entrytime'];
                $styview->addedby = $row['addedby'];
                $styview->updatedtime = $row['updatedtime'];
                $styview->updated_by = $row['updated_by'];
                $styview->isdeleted = $row['isdeleted'];
//$support1->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['create_on_date']));
                $styleviewdata[] = $styview;
            }
            return $styleviewdata;
        }
        return null;
    }

    public function add_entrydata($srcode, $shopid, $entrydate, $entrytime, $remark) {
        if ($_SESSION['role_modal'] == 'ASM') {
            $isasm = 1;
        } else {
            $isasm = 0;
        }

        $newDate = date("Y-m-d", strtotime($entrydate));
        $entrydate = $newDate . ' ' . $entrytime . ":00";
        $Query = "Insert into entry (customerno,salesid,is_asm,shopid,entrydate,remark,entrytime,addedby) VALUES($this->customerno,%d,%d,%d,'%s','%s','$this->today',$this->userid)";
        $SQL = sprintf($Query, (int) $srcode, (int) $isasm, (int) $shopid, $entrydate, Sanitise::String($remark));
        $this->executeQuery($SQL);
    }

    public function add_stockdata($srcode, $distid, $catid, $skuid, $qty, $orderdate, $entrytime) {
        $newDate = date("Y-m-d", strtotime($orderdate));
        $orderdate1 = $newDate . ' ' . $entrytime . ":00";
        $Query = "Insert into stock (customerno,distributorid,skuid,quantity,stockdate,entrytime,addedby) VALUES($this->customerno,%d,%d,%d,'%s','$this->today',$this->userid)";
        $SQL = sprintf($Query, (int) $srcode, (int) $distid, (int) $skuid, (int) $quantity, $orderdate1);
        $this->executeQuery($SQL);
    }

    public function add_primarysalesdata($srcode, $distid, $orderdate, $entrytime, $skudata) {
        $newDate = date("Y-m-d", strtotime($orderdate));
        $orderdate1 = $newDate . ' ' . $entrytime . ":00";
        $isasm = 0;
        $isapproved = 0;
        if ($_SESSION['role_modal'] == "sales_representative") {
            $isasm = 0;
            $isapproved = 0;
        }
        if ($_SESSION['role_modal'] == 'Supervisor') {
            $isasm = 1;
            $isapproved = 1;
        }
        if ($_SESSION['role_modal'] == 'ASM') {
            $isasm = 2;
            $isapproved = 1;
        }
        if ($_SESSION['role_modal'] == 'Administrator' || $_SESSION['role_modal'] == 'elixir') {
            $isasm = 0;
            $isapproved = 1;
        }


        $Query = "INSERT INTO primary_order (customerno,distributorid,deliverydate,entrydate,is_asm,srid,is_approved,addedby) VALUES($this->customerno,%d,'%s','%s',%d,%d,%d,%d)";
        $SQL = sprintf($Query, (int) $distid, (string) $orderdate1, (string) $this->today, $isasm, (int) $srcode, $isapproved, $this->userid);
        $this->executeQuery($SQL);
        $id = $this->get_insertedId();
        $category = $skudata['category'];
        $sku = $skudata['sku'];
        $qty = $skudata['qty'];

///inventory add
        $invskulist = array();
        $getinventorylist = $this->get_inventory_list($distid, $this->userid);
        for ($i = 0; $i < count($getinventorylist); $i++) {
            $invskulist[] = $getinventorylist[$i]->styleid;
        }

        for ($i = 0; $i < count($category); $i++) {
            if (in_array($sku[$i], $invskulist)) {
                $this->update_inventory_pri_qty($distid, $sku[$i], $qty[$i]);
            } else {
                $this->insert_inventory_pri_qty($distid, $sku[$i], $qty[$i], $isasm);
            }

            $Query = "INSERT INTO primary_order_productlist (customerno,prid,categoryid,skuid,quantity,status,entrydate) VALUES($this->customerno,%d,%d,%d,%d,%d,'%s')";
            $SQL = sprintf($Query, (int) $id, (int) $category[$i], (int) $sku[$i], (int) $qty[$i], 0, (string) $this->today);
            $this->executeQuery($SQL);
        }
    }

    public function edit_primarysalesdata($prid, $srcode, $distid, $orderdate, $entrytime, $skudata) {
        $newDate = date("Y-m-d", strtotime($orderdate));
        $orderdate1 = $newDate . ' ' . $entrytime . ":00";

        if ($_SESSION['role_modal'] == "sales_representative") {
            $isasm = 0;
            $isapproved = 0;
        }
        if ($_SESSION['role_modal'] == 'Supervisor') {
            $isasm = 1;
            $isapproved = 1;
        }
        if ($_SESSION['role_modal'] == 'ASM') {
            $isasm = 2;
            $isapproved = 1;
        }
        if ($_SESSION['role_modal'] == 'Administrator' || $_SESSION['role_modal'] == 'elixir') {
            $isasm = 0;
            $isapproved = 1;
        }

        $Query = "Update  primary_order_productlist set isdeleted=1 where prid= " . $prid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);

        $Query = "Update primary_order set deliverydate='" . $orderdate1 . "',updated_time='" . $this->today . "',updated_by=" . $this->userid . ", is_asm=" . $isasm . ", is_approved = " . $isapproved . " where prid = " . $prid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);

        $category = $skudata['category'];
        $sku = $skudata['sku'];
        $qty = $skudata['qty'];

        $invskulist = array();
        $getinventorylist = $this->get_inventory_list($distid, $this->userid);

        for ($i = 0; $i < count($getinventorylist); $i++) {
            $invskulist[] = $getinventorylist[$i]->styleid;
        }

        for ($i = 0; $i < count($category); $i++) {
            if (in_array($sku[$i], $invskulist)) {
                $this->update_inventory_pri_qty($distid, $sku[$i], $qty[$i]);
            } else {
                $this->insert_inventory_pri_qty($distid, $sku[$i], $qty[$i], $isasm);
            }

            $Query = "INSERT INTO primary_order_productlist (customerno,prid,categoryid,skuid,quantity,status,entrydate) VALUES($this->customerno,%d,%d,%d,%d,%d,'%s')";
            $SQL = sprintf($Query, (int) $prid, (int) $category[$i], (int) $sku[$i], (int) $qty[$i], 0, (string) $this->today);
            $this->executeQuery($SQL);
        }
    }

    public function add_deadstockdata($reason, $srcode, $distid, $areaid, $shopid, $skudata) {

        if ($_SESSION['role_modal'] == "sales_representative") {
            $isasm = 0;
        }
        if ($_SESSION['role_modal'] == 'Supervisor') {
            $isasm = 1;
        }
        if ($_SESSION['role_modal'] == 'ASM') {
            $isasm = 2;
        }
        if ($_SESSION['role_modal'] == 'Administrator' || $_SESSION['role_modal'] == 'elixir') {
            $isasm = 0;
        }

        $Query = "INSERT INTO deadstock (customerno,srid,shopid,stockdate,is_asm,addedby,entrytime, reason) VALUES($this->customerno,%d,%d,'%s',%d,%d,'%s','%s')";
        $SQL = sprintf($Query, (int) $srcode, (int) $shopid, (string) $this->today, $isasm, $this->userid, $this->today, $reason);

        $this->executeQuery($SQL);
        $stockid = $this->get_insertedId();

        $category = $skudata['category'];
        $sku = $skudata['sku'];
        $qty = $skudata['qty'];

        for ($i = 0; $i < count($category); $i++) {
            $Query = "INSERT INTO deadstock_order_productlist (stockid,skuid,quantity) VALUES(%d,%d,'%s')";
            $SQL = sprintf($Query, (int) $stockid, $sku[$i], $qty[$i]);
            $this->executeQuery($SQL);
        }
    }

    public function edit_deadstockdata($reason, $srcode, $distid, $areaid, $shopid, $skudata, $stockid) {
        if ($role == "sales_representative") {
            $is_asm = 0;
        }
        if ($role == "ASM") {
            $is_asm = 2;
        }
        if ($role == "Supervisor") {
            $is_asm = 1;
        }

        $Query = "Update  deadstock_order_productlist set isdeleted=1 where stockid= " . $stockid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);

        $Query = "Update deadstock set updatedtime='" . $this->today . "',updated_by=" . $this->today . ", is_asm=" . $is_asm . " where stockid = " . $stockid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        $category = $skudata['category'];
        $sku = $skudata['sku'];
        $qty = $skudata['qty'];

        for ($i = 0; $i < count($category); $i++) {
            $Query = "INSERT INTO deadstock_order_productlist (stockid,skuid,quantity) VALUES(%d,%d,'%s')";
            $SQL = sprintf($Query, (int) $stockid, $sku[$i], $qty[$i]);
            $this->executeQuery($SQL);
        }
    }

    public function add_primarysalesdata_api($role, $distid, $skudata, $orderdate, $userkeyid) {
        $orderdate = date("Y-m-d h:i:s", strtotime($orderdate));
        $is_asm = 0;
        $is_approved = 0;
        if ($role == "sales_representative") {
            $is_asm = 0;
            $is_approved = 0;
        }
        if ($role == "ASM") {
            $is_asm = 2;
            $is_approved = 1;
        }
        if ($role == "Supervisor") {
            $is_asm = 1;
            $is_approved = 1;
        }
        $invskulist = array();
        $getinventorylist = $this->get_inventory_list($distid, $userkeyid);
        for ($i = 0; $i < count($getinventorylist); $i++) {
            $invskulist[] = $getinventorylist[$i]->styleid;
        }

        $Query = "INSERT INTO primary_order (customerno,distributorid,deliverydate,entrydate,is_asm,srid,is_approved,addedby) VALUES($this->customerno,%d,'%s','%s',%d,%d,%d,%d)";
        $SQL = sprintf($Query, (int) $distid, $orderdate, $this->today, $is_asm, $this->userid, $is_approved, $userkeyid);
        $this->executeQuery($SQL);
        $id = $this->get_insertedId();
        if (isset($skudata)) {
            foreach ($skudata as $row) {
                if (in_array($row['skuid'], $invskulist)) {
                    $this->update_inventory_pri_qty($distid, $row['skuid'], $row['quantity']);
                } else {
                    $this->insert_inventory_pri_qty($distid, $row['skuid'], $row['quantity'], $is_asm);
                }

                $Query = "INSERT INTO primary_order_productlist (customerno,prid,categoryid,skuid,quantity,status,entrydate) VALUES($this->customerno,%d,%d,%d,%d,%d,'%s')";
                $SQL = sprintf($Query, (int) $id, (int) $row['categoryid'], (int) $row['skuid'], (int) $row['quantity'], (int) $row['status'], (string) $this->today);
                $this->executeQuery($SQL);
            }
        }
        return $id;
    }

    public function insert_inventory_pri_qty($distid, $skuid, $qty, $is_asm) {
        $Query = "INSERT INTO inventory (customerno,distributorid,skuid,quantity,stockdate,entrydate,is_asm,addedby)VALUES($this->customerno,%d,%d,%d,'%s','%s',%d,%d)";
        $SQL = sprintf($Query, (int) $distid, $skuid, $qty, $this->today, $this->today, $is_asm, $this->userid);
        $this->executeQuery($SQL);
    }

    public function update_inventory_pri_qty($distid, $skuid, $qty) {
        $query = "Update inventory set  updated_by='" . $this->userid . "', updated_time ='" . $this->today . "',quantity= quantity +" . $qty . " where skuid = '" . $skuid . "' AND distributorid =" . $distid;
        $sql = sprintf($query);
        $this->executeQuery($sql);
    }

    public function update_inventory_secondarysales_qty($distid, $skuid, $qty) {
        $query = "Update inventory set  updated_by='" . $this->userid . "', updated_time ='" . $this->today . "',quantity= quantity -" . $qty . " where skuid = '" . $skuid . "' AND distributorid =" . $distid;
        $sql = sprintf($query);
        $this->executeQuery($sql);
    }

    public function insert_inventory_sec_qty($distid, $skuid, $qty, $is_asm) {
        $Query = "INSERT INTO inventory (customerno,distributorid,skuid,quantity,stockdate,entrydate,is_asm,addedby)VALUES($this->customerno,%d,%d,%d,'%s','%s',%d,%d)";
        $SQL = sprintf($Query, (int) $distid, $skuid, -$qty, $this->today, $this->today, $is_asm, $this->userid);
        $this->executeQuery($SQL);
    }

    public function edit_primarysalesdata_api($role, $skudata, $orderid, $userkeyid) {
        $is_asm = 0;
        $is_approved = 0;
        if ($role == "sales_representative") {
            $is_asm = 0;
            $is_approved = 0;
        }
        if ($role == "ASM") {
            $is_asm = 2;
            $is_approved = 1;
        }
        if ($role == "Supervisor") {
            $is_asm = 1;
            $is_approved = 1;
        }

        $Query = "Update  primary_order_productlist set isdeleted=1 where prid= " . $orderid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);

        $Query = "Update primary_order set updated_time='" . $this->today . "',updated_by=" . $userkeyid . ", is_asm=" . $is_asm . ", is_approved = " . $is_approved . " where prid = " . $orderid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);


        if (isset($skudata)) {
            foreach ($skudata as $row) {
                $Query = "INSERT INTO primary_order_productlist (customerno,prid,categoryid,skuid,quantity,status,entrydate) VALUES($this->customerno,%d,%d,%d,%d,%d,'%s')";
                $SQL = sprintf($Query, (int) $orderid, (int) $row['categoryid'], (int) $row['skuid'], (int) $row['quantity'], (int) $row['status'], (string) $this->today);
                $this->executeQuery($SQL);
            }
        }
        return 'ok';
    }

    public function add_secondarysalesdata_api($role, $shopid, $skudata, $orderdate, $is_deadstock, $reason, $discount, $distid) {
        $orderdate = date("Y-m-d h:i:s", strtotime($orderdate));
        $is_asm = 0;
        if ($role == "sales_representative") {
            $is_asm = 0;
        }
        if ($role == "ASM") {
            $is_asm = 2;
        }
        if ($role == "Supervisor") {
            $is_asm = 1;
        }
        if ($is_deadstock == 1) {   //deadstock insert
            $Query = "INSERT INTO deadstock (customerno,shopid,stockdate,is_asm,addedby,entrytime, reason) VALUES($this->customerno,%d,'%s',%d,%d,'%s','%s')";
            $SQL = sprintf($Query, (int) $shopid, (string) $orderdate, $is_asm, $this->userid, $this->today, $reason);

            $this->executeQuery($SQL);
            $id = $this->get_insertedId();

            if (isset($skudata)) {
                foreach ($skudata as $row) {
                    $Query = "INSERT INTO deadstock_order_productlist (stockid,skuid,quantity) "
                            . "VALUES(%d,%d,'%s')";
                    $SQL = sprintf($Query, (int) $id, $row['skuid'], $row['quantity']);
                    $this->executeQuery($SQL);
                }
            }
        } elseif ($is_deadstock == 0) {  //add secondary sales
            $Query = "INSERT INTO secondary_order (customerno,shopid,orderdate,entrydate,is_asm,addedby,is_deadstock, discount) VALUES($this->customerno,%d,'%s','%s',%d,%d,%d,%d)";
            $SQL = sprintf($Query, (int) $shopid, (string) $orderdate, (string) $this->today, $is_asm, $this->userid, $is_deadstock, $discount);
            $this->executeQuery($SQL);
            $id = $this->get_insertedId();

//update_inventory_secondarysales_qty
            $invskulist = array();
            $getinventorylist = $this->get_inventory_list($distid, $this->userid);
            for ($i = 0; $i < count($getinventorylist); $i++) {
                $invskulist[] = $getinventorylist[$i]->styleid;
            }

            if (isset($skudata)) {
                foreach ($skudata as $row) {

                    if (in_array($row['skuid'], $invskulist)) {
                        $this->update_inventory_secondarysales_qty($distid, $row['skuid'], $row['quantity']);
                    } else {
                        $this->insert_inventory_sec_qty($distid, $row['skuid'], $row['quantity'], $is_asm);
                    }

                    $Query = "INSERT INTO secondary_order_productlist (customerno,soid,skuid,quantity,status,entrydate) VALUES($this->customerno,%d,%d,%d,%d,'%s')";
                    $SQL = sprintf($Query, $id, $row['skuid'], $row['quantity'], $row['status'], (string) $this->today);
                    $this->executeQuery($SQL);
                }
            }
        }
        return $id;
    }

    public function add_orderdata($srcode, $shopid, $orderdate, $entrytime, $skudata) {
        $newDate = date("Y-m-d", strtotime($orderdate));
        $orderdate1 = $newDate . ' ' . $entrytime . ":00";
        $is_asm = 0;
        if ($_SESSION['role_modal'] == "sales_representative") {
            $is_asm = 0;
        }
        if ($_SESSION['role_modal'] == "ASM") {
            $is_asm = 2;
        }
        if ($_SESSION['role_modal'] == "Supervisor") {
            $is_asm = 1;
        }

//add secondary sales
        $Query = "INSERT INTO secondary_order (customerno,shopid,orderdate,entrydate,is_asm,addedby,is_deadstock) VALUES($this->customerno,%d,'%s','%s',%d,%d,%d)";
        $SQL = sprintf($Query, (int) $shopid, (string) $orderdate1, (string) $this->today, (int) $is_asm, $this->userid, 0);
        $this->executeQuery($SQL);
        $id = $this->get_insertedId();

        $category = $skudata['category'];
        $sku = $skudata['sku'];
        $qty = $skudata['qty'];

        $invskulist = array();
        $getinventorylist = $this->get_inventory_list($distid, $this->userid);
        for ($i = 0; $i < count($getinventorylist); $i++) {
            $invskulist[] = $getinventorylist[$i]->styleid;
        }


        for ($i = 0; $i < count($category); $i++) {
            if (in_array($sku[$i], $invskulist)) {
                $this->update_inventory_secondarysales_qty($distid, $sku[$i], $qty[$i]);
            } else {
                $this->insert_inventory_sec_qty($distid, $sku[$i], $qty[$i], $is_asm);
            }

            $Query = "INSERT INTO secondary_order_productlist (customerno,soid,skuid,quantity,status,entrydate) VALUES($this->customerno,%d,%d,%d,%d,'%s')";
            $SQL = sprintf($Query, $id, $sku[$i], $qty[$i], 0, $this->today);
            $this->executeQuery($SQL);
        }
    }

    public function update_orderdata($orderid, $distid, $areaid, $shopid, $orderdate, $entrytime, $skudata) {
        $newDate = date("Y-m-d", strtotime($orderdate));
        $orderdate1 = $newDate . ' ' . $entrytime . ":00";
        $is_asm = 0;
        if ($role == "sales_representative") {
            $is_asm = 0;
        }
        if ($role == "ASM") {
            $is_asm = 2;
        }
        if ($role == "Supervisor") {
            $is_asm = 1;
        }

        $Query = "Update  secondary_order_productlist set isdeleted=1 where soid= " . $orderid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);

        $Query = "Update secondary_order set orderdate='" . $orderdate1 . "',updatedtime='" . $this->today . "',updated_by=" . $this->userid . ", is_asm=" . $is_asm . " where soid = " . $orderid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);


        $category = $skudata['category'];
        $sku = $skudata['sku'];
        $qty = $skudata['qty'];

        $invskulist = array();
        $getinventorylist = $this->get_inventory_list($distid, $this->userid);
        for ($i = 0; $i < count($getinventorylist); $i++) {
            $invskulist[] = $getinventorylist[$i]->styleid;
        }

        for ($i = 0; $i < count($category); $i++) {

            if (in_array($row['skuid'], $invskulist)) {
                $this->update_inventory_secondarysales_qty($distid, $row['skuid'], $row['quantity']);
            } else {
                $this->insert_inventory_sec_qty($distid, $row['skuid'], $row['quantity'], $is_asm);
            }


            $Query = "INSERT INTO secondary_order_productlist (customerno,soid,skuid,quantity,status,entrydate) VALUES($this->customerno,%d,%d,%d,%d,'%s')";
            $SQL = sprintf($Query, $orderid, $sku[$i], $qty[$i], 0, $this->today);
            $this->executeQuery($SQL);
        }
    }

    public function edit_secondarysalesdata_api($role, $skudata, $orderid, $userkeyid) {
        $is_asm = 0;
        if ($role == "sales_representative") {
            $is_asm = 0;
        }
        if ($role == "ASM") {
            $is_asm = 2;
        }
        if ($role == "Supervisor") {
            $is_asm = 1;
        }

        $Query = "Update  secondary_order_productlist set isdeleted=1 where soid= " . $orderid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);

        $Query = "Update secondary_order set updatedtime='" . $this->today . "',updated_by=" . $userkeyid . ", is_asm=" . $is_asm . " where soid = " . $orderid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);


        if (isset($skudata)) {
            foreach ($skudata as $row) {
                $Query = "INSERT INTO secondary_order_productlist (customerno,soid,skuid,quantity,status,entrydate) VALUES($this->customerno,%d,%d,%d,%d,'%s')";
                $SQL = sprintf($Query, (int) $orderid, (int) $row['skuid'], (int) $row['quantity'], (int) $row['status'], (string) $this->today);
                $this->executeQuery($SQL);
            }
        }
        return 'ok';
    }

    public function edit_deadstockdata_api($role, $skudata, $orderid, $userkeyid) {
        $is_asm = 0;
        if ($role == "sales_representative") {
            $is_asm = 0;
        }
        if ($role == "ASM") {
            $is_asm = 2;
        }
        if ($role == "Supervisor") {
            $is_asm = 1;
        }

        $Query = "Update  deadstock_order_productlist set isdeleted=1 where stockid= " . $orderid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);

        $Query = "Update deadstock set updatedtime='" . $this->today . "',updated_by=" . $userkeyid . ", is_asm=" . $is_asm . " where stockid = " . $orderid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);


        if (isset($skudata)) {
            foreach ($skudata as $row) {
                $Query = "INSERT INTO  deadstock_order_productlist (stockid,skuid,quantity) VALUES(%d,%d,%d)";
                $SQL = sprintf($Query, (int) $orderid, (int) $row['skuid'], (int) $row['quantity']);
                $this->executeQuery($SQL);
            }
        }
        return 'ok';
    }

    public function is_entry_exists_today($srcode, $shopid, $latitude, $longitude, $radius, $status) {
        $todaydate = date("Y-m-d", strtotime($this->today));
        $Query = "SELECT salesid FROM entry WHERE salesid = " . $srcode . " AND DATE(`entrytime`) ='" . $todaydate . "' AND shopid = " . $shopid . " AND latitude = " . $latitude . " AND longtitude=" . $longitude . " AND radius=" . $radius . " AND customerno=$this->customerno ";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function add_entrydata_api($role, $srcode, $shopid, $remark = NULL, $latitude, $longitude, $radius, $datetime1 = NULL, $status) {
        if ($role == 'sales_representative') {
            $isasm = 0;
        } else {
            $isasm = 0;
        }
        if ($datetime1 != NULL) {
            $datetime = $datetime1;
        } else {
            $datetime = $this->today;
        }
        $Query = "Insert into entry (customerno,salesid,latitude,longtitude,radius,is_asm,shopid,entrydate,remark,status,entrytime,addedby) VALUES($this->customerno,%d,'%s','%s','%s',%d,%d,'%s','%s',%d,'$this->today',$this->userid)";
        $SQL = sprintf($Query, (int) $srcode, $latitude, $longitude, $radius, (int) $isasm, (int) $shopid, $datetime, Sanitise::String($remark), $status);
        $this->executeQuery($SQL);
    }

    public function add_deadstockdata_api($role, $reason = NULL, $srcode, $distid, $cartons, $skuid, $areaid, $shopid, $status) {
        if ($role == "sales_representative") {
            $isasm = 0;
            $isapproved = 0;
        }
        $Query = "Insert into deadstock (customerno,stockdate,distributorid,areaid,shopid,skuid,srid,quantity,reason,is_asm,is_approved,status,entrytime,addedby) VALUES($this->customerno,'$this->today',%d,%d,%d,%d,%d,%d,'%s',%d,%d,%d,'$this->today',$this->userid)";
        $SQL = sprintf($Query, (int) $distid, (int) $areaid, (int) $shopid, (int) $skuid, (int) $srcode, (int) $cartons, $reason, $isasm, $isapproved, $status);
        $this->executeQuery($SQL);
    }

    public function update_stockdata($stockid, $srcode, $distid, $catid, $skuid, $qty, $orderdate, $entrytime) {
        $newDate = date("Y-m-d", strtotime($orderdate));
        $orderdate1 = $newDate . ' ' . $entrytime . ":00";
        $Query = "update stock set distributorid =" . $distid . ",skuid=" . $skuid . ",stockdate='" . $orderdate1 . "',quantity=" . $qty . ",updatedtime ='" . $this->today . "', updated_by= " . $this->userid . "  where stockid=" . $stockid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function delete_stockdata($id) {
        $Query = "update stock set isdeleted=1 where stockid=" . $id;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function get_entryview() {
        $entryviewdata = array();
        $Query = "SELECT e.entryid, e.entrydate,e.remark,  d.distcode, d.distname, s.srcode, s.srname, sh.shopname FROM entry as e left join distributor as d  on e.distributorid = d. distributorid left join sales as s  on e.salesid = s.salesid left join shop as sh  on e.shopid = sh.shopid WHERE e. customerno=$this->customerno  AND e. isdeleted=0";

        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $areaedit = new stdClass();
                $areaedit->srcode = $row['srcode'];
                $areaedit->distcode = $row['distcode'];
                $areaedit->shopname = $row['shopname'];
                $areaedit->entrydate = $row['entrydate'];
                $areaedit->remark = $row['remark'];
                $shopviewdata[] = $areaedit;
            }
            return $shopviewdata;
        }
        return null;
    }

    public function get_entry_api($userid, $userkey_id) {
        $data = array();
        $Query = "select sh.shopname,u.realname,u.role,e.entryid,e.salesid,e.shopid,e.entrydate,e.latitude,e.longtitude,e.radius,e.addedby from entry as e
                    left join shop as sh on sh.shopid = e.shopid
                    INNER JOIN " . SPEEDDB . ".user as u  ON e.addedby = u.userid
                where e.entrydate >= DATE(NOW()) - INTERVAL 7 DAY AND e.salesid=" . $userid . " AND e.customerno=$this->customerno and e.isdeleted=0 ";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $data[] = array(
                    'orderid' => $row['entryid'],
                    'salesid' => $row['salesid'],
                    'shopid' => $row['shopid'],
                    'shopname' => $row['shopname'],
                    'entrydate' => $row['entrydate'],
                    'latitude' => $row['latitude'],
                    'longtitude' => $row['longtitude'],
                    'addedby' => $row['realname'],
                    'addedby_role' => $row['role'],
                    'addedby_id' => $row['addedby'],
                    'radius' => $row['radius']
                );
            }
        }
        return $data;
    }

    public function get_orderedit($oid) {
        $entryviewdata = array();
        $Query = "SELECT o.orderid,o.distributorid,o.areaid,o.shopid,o.catid,o.skuid, o.orderdate, o.quantity,u.userid "
                . "FROM orders as o  left join " . SPEEDDB . ".`user` as u  on o.salesid = u.userid "
                . " left join shop as sh  on o.shopid = sh.shopid "
                . "WHERE o.customerno=$this->customerno AND o.orderid= " . $oid . " AND o.isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $areaedit = new stdClass();
                $areaedit->orderid = $row['orderid'];
                $areaedit->salesid = $row['userid'];
                $areaedit->distid = $row['distributorid'];
                $areaedit->areaid = $row['areaid'];
                $areaedit->shopid = $row['shopid'];
                $areaedit->catid = $row['catid'];
                $areaedit->skuid = $row['skuid'];
                $areaedit->orderdate = $row['orderdate'];
                $areaedit->quantity = $row['quantity'];
                $shopviewdata[] = $areaedit;
            }
            return $shopviewdata;
        }
        return null;
    }

    public function get_all_asm_for_zone($zoneid) {
        if ($zoneid == 0) {
            $Query = "SELECT * from ASM INNER JOIN state ON state.stateid = asm.stateid
            where asm.customerno=%d ";
            $stateQuery = sprintf($Query, $this->_Customerno);
        } else {
            $Query = "SELECT * from ASM INNER JOIN state ON state.stateid = asm.stateid
            where asm.customerno=%d and state.zoneid=%d";
            $stateQuery = sprintf($Query, $this->_Customerno, $zoneid);
        }

        $this->executeQuery($stateQuery);
        $states = array();
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $state = new stdClass();
                $state->stateid = $row['stateid'];
                $state->statename = $row['statename'];
                $state->asmname = $row['asmname'];
                $state->asmid = $row['asmid'];
                $state->use_app = $row['use_app'];
                $states[] = $state;
            }
            return $states;
        }
        return null;
    }

    public function getdistareabyshopid_api($shopid) {
        $shopdata = array();
        $Query = "select distributorid, areaid from shop where customerno= $this->customerno AND shopid='" . $shopid . "' AND isdeleted=0";
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $shopdata[] = array(
                    'distid' => $row['distributorid'],
                    'areaid' => $row['areaid']
                );
            }
            return $shopdata;
        }
        return null;
    }

    public function getcatidbyskuid_api($skuid) {
        $Query = "select categoryid from style where isdeleted=0 AND customerno= $this->customerno AND skuid='" . $skuid . "'";
        $this->executeQuery($Query);
        $categoryid = 0;
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $categoryid = $row['categoryid'];
            }
        }
        return $categoryid;
    }

    public function getallasm() {
        $users = Array();
        $Query = 'SELECT * FROM ' . SPEEDDB . '.user  where role="ASM" and roleid=13 and user.customerno=%d and isdeleted=0';
        $userQuery = sprintf($Query, $this->customerno);
        $this->executeQuery($userQuery);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $user = new VOUser();
                $user->userid = $row['userid'];
                $user->username = $row['username'];
                $user->realname = $row['realname'];
                $user->role = $row['role'];
                $user->roleid = $row['roleid'];
                $users[] = $user;
            }
            return $users;
        }
        return null;
    }

    public function getallsupervisor() {
        $users = Array();
        $Query = 'SELECT * FROM ' . SPEEDDB . '.user  where role="Supervisor" and roleid=40 and user.customerno=%d and isdeleted=0';
        $userQuery = sprintf($Query, $this->customerno);
        $this->executeQuery($userQuery);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $user = new VOUser();
                $user->userid = $row['userid'];
                $user->username = $row['username'];
                $user->realname = $row['realname'];
                $user->role = $row['role'];
                $user->roleid = $row['roleid'];
                $users[] = $user;
            }
            return $users;
        }
        return null;
    }

    public function getallsr() {
        $users = Array();
        $Query = 'SELECT * FROM ' . SPEEDDB . '.user  where role="sales_representative" and roleid=14 and user.customerno=%d and isdeleted=0';
        $userQuery = sprintf($Query, $this->customerno);
        $this->executeQuery($userQuery);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $user = new VOUser();
                $user->userid = $row['userid'];
                $user->username = $row['username'];
                $user->realname = $row['realname'];
                $user->role = $row['role'];
                $user->roleid = $row['roleid'];
                $users[] = $user;
            }
            return $users;
        }
        return null;
    }

    public function getalldistributors() {
        $users = Array();
        $Query = 'SELECT * FROM ' . SPEEDDB . '.user  where role="Distributor" and roleid=41 and user.customerno=%d and isdeleted=0';
        $userQuery = sprintf($Query, $this->customerno);
        $this->executeQuery($userQuery);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $user = new VOUser();
                $user->userid = $row['userid'];
                $user->username = $row['username'];
                $user->realname = $row['realname'];
                $user->role = $row['role'];
                $user->roleid = $row['roleid'];
                $users[] = $user;
            }
            return $users;
        }
        return null;
    }

    public function getprimary() {
        $Query = "select count(prid) as pridcount from primary_order as a  where  a.is_approved<>-1 AND a.customerno = " . $this->customerno;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $pridcount = $row['pridcount'];
            }
            return $pridcount;
        }
        return null;
    }

    public function getdeadstock() {
        $Query = "SELECT count(s.stockid) as deadstockcount FROM deadstock as s WHERE s.customerno= $this->customerno AND s.isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $deadstockcount = $row['deadstockcount'];
            }
            return $deadstockcount;
        }
        return null;
    }

    public function get_distributor($userid, $status) {
        $getdistdata = array();
        if ($status == 'SR') {
            $Query = "SELECT distributorid,distname,distcode FROM distributor WHERE customerno=$this->customerno AND salesid=" . $userid . " AND isdeleted=0";
            $SQL = sprintf($Query);
            $this->executeQuery($SQL);
            if ($this->get_rowCount() > 0) {
                while ($row = $this->get_nextRow()) {
                    $getdistdata[] = array(
                        'id' => $row['distributorid'],
                        'value' => $row['distcode']
                    );
                }
                return $getdistdata;
            }
            return null;
        } else if ($status == 'ASM') {
            $ids = implode(',', $userid);
            $Query = "SELECT distributorid,distname,distcode FROM distributor WHERE customerno=$this->customerno AND salesid IN (" . $ids . ") AND isdeleted=0";
            $SQL = sprintf($Query);
            $this->executeQuery($SQL);
            if ($this->get_rowCount() > 0) {
                while ($row = $this->get_nextRow()) {
                    $getdistdata[] = array(
                        'id' => $row['distributorid'],
                        'value' => $row['distcode']
                    );
                }
                return $getdistdata;
            }
            return null;
        } else {
            $Query = "SELECT distributorid,distname,distcode FROM distributor WHERE customerno=$this->customerno AND isdeleted=0";
            $SQL = sprintf($Query);
            $this->executeQuery($SQL);
            if ($this->get_rowCount() > 0) {
                while ($row = $this->get_nextRow()) {
                    $getdistdata[] = array(
                        'id' => $row['distributorid'],
                        'value' => $row['distcode']
                    );
                }
                return $getdistdata;
            }
            return null;
        }
    }

    public function get_distributordetails_cron() {
        $Query = "SELECT distributorid,distname,distcode,dphone,demail,customerno  FROM distributor  WHERE customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getdistdata[] = array(
                    'distributorid' => $row['distributorid'],
                    'distcode' => $row['distcode'],
                    'dphone' => $row['dphone'],
                    'demail' => $row['demail'],
                    'customerno' => $row['customerno']
                );
            }
            return $getdistdata;
        }
        return null;
    }

    public function getsecondaryorders() {
        $Query = " SELECT count(soid) as seccount "
                . " FROM secondary_order  "
                . " WHERE customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $seccount = $row['seccount'];
            }
            return $seccount;
        }
        return null;
    }

    public function get_shopid_byandroidid($androidshopid) {
        $Query = "select shopid from shop where customerno= $this->customerno AND isdeleted=0 AND androidshop_id = " . $androidshopid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        $shopid = 0;
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $shopid = $row['shopid'];
            }
        }
        return $shopid;
    }

    public function delete_primarysales_api($id) {
        $Query = "UPDATE primary_order SET isdeleted=1, updated_by='" . $this->userid . "',updated_time='" . $this->today . "' where prid=" . $id;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);

        $Query2 = "UPDATE primary_order_productlist SET isdeleted=1 where prid=" . $id;
        $SQL2 = sprintf($Query2);
        $this->executeQuery($SQL2);

        return 'Order Deleted Successfully';
    }

    public function delete_secondarysales_api($id) {
        $Query = "UPDATE secondary_order SET isdeleted=1, updated_by='" . $this->userid . "',updatedtime='" . $this->today . "' where soid=" . $id;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);

        $Query2 = "UPDATE secondary_order_productlist SET isdeleted=1 where soid=" . $id;
        $SQL2 = sprintf($Query2);
        $this->executeQuery($SQL2);

        return 'Order Deleted Successfully';
    }

    public function delete_deadstock_api($orderid) {
        $Query = "UPDATE deadstock SET isdeleted=1, updated_by='" . $this->userid . "',updatedtime='" . $this->today . "' where stockid=" . $orderid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);

        $Query2 = "UPDATE deadstock_order_productlist SET isdeleted=1 where stockid=" . $orderid;
        $SQL2 = sprintf($Query2);
        $this->executeQuery($SQL2);

        return 'deadstock deleted successfully';
    }

    public function get_primarysales_details_api($orderid) {
        $data = array();
        $Query = "select a.prid,a.distributorid,a.entrydate,a.deliverydate,a.srid from primary_order as a
        where a.customerno=$this->customerno and a.isdeleted=0 and prid = %d";
        $SQL = sprintf($Query, (int) $orderid);
        $this->executeQuery($SQL);

        $totalskus = primary_product_details($orderid, $this->customerno, $this->userid);  //count style


        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $data[] = array(
                    'prid' => $row['prid'],
                    'distributorid' => $row['distributorid'],
                    'orderdate' => $row['deliverydate'],
                    'srid' => $row['srid'],
                    'totalskus' => $totalskus
                );
            }
            return $data;
        }
    }

    public function get_secondarysales_details_api($orderid) {
        $data = array();
        $Query = "select area.areaid,a.addedby,a.soid,a.orderdate,sh.areaid,sh.distributorid,a.shopid,sh.shopname,a.discount,a.is_asm
                  from secondary_order as a
                  left join shop as sh on sh.shopid=a.shopid
                  inner join area on area.areaid = sh.areaid
                  where a.customerno=$this->customerno and a.isdeleted=0 and a.soid = %d";
        $SQL = sprintf($Query, (int) $orderid);
        $this->executeQuery($SQL);

        $totalskus = secondary_product_details($orderid, $this->customerno, $this->userid);  //count style

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $data[] = array(
                    'soid' => $row['soid'],
                    'shopid' => $row['shopid'],
                    'shopname' => $row['shopname'],
                    'areaid' => $row['areaid'],
                    'orderdate' => $row['orderdate'],
                    'distributorid' => $row['distributorid'],
                    'areaid' => $row['areaid'],
                    'addedby' => $row['addedby'],
                    'totalskus' => $totalskus
                );
            }
            return $data;
        }
    }

    public function get_deadstock_details_api($orderid) {
        $data = array();
        $Query = "select a.stockid,sh.areaid,sh.distributorid,a.shopid,a.reason,ar.areaname,dist.realname,a.stockdate,a.is_asm from deadstock as a
            left join shop as sh on sh.shopid = a.shopid left join " . SPEEDDB . ".user as dist on dist.userid = sh.distributorid
            left join area as ar on ar.areaid = sh.areaid
    where a.customerno=$this->customerno and a.isdeleted=0 and a.stockid = %d";
        $SQL = sprintf($Query, (int) $orderid);
        $this->executeQuery($SQL);

        $totalskus = deadstock_product_details($orderid, $this->customerno, $this->userid);  //count style

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $data[] = array(
                    'stockid' => $row['stockid'],
                    'shopid' => $row['shopid'],
                    'reasons' => $row['reason'],
                    'stockdate' => $row['stockdate'],
                    'is_asm' => $row['is_asm'],
                    'areaid' => $row['areaid'],
                    'distid' => $row['distributorid'],
                    'totalskus' => $totalskus
                );
            }
            return $data;
        }
    }

    public function get_primarysales_orders_api($userid, $userkey_id) {
        $data = array();
        $Query = "select u.realname,u.role,a.prid,a.srid,a.distributorid,a.entrydate,a.deliverydate, a.addedby, a.is_approved, a.is_asm from primary_order as a
                INNER JOIN " . SPEEDDB . ".user as u  ON a.addedby = u.userid
            where MONTH(a.entrydate)= MONTH(CURDATE())AND a.srid=" . $userid . " AND a.customerno=$this->customerno and a.isdeleted=0 order by a.entrydate desc ";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $edit_option = 0;
                if ($row['addedby'] == $userkey_id && $row['is_approved'] == 0) {
                    $edit_option = 1;
                }
                $totalskus = primary_product_count($row['prid'], $this->customerno, $this->userid);  //count style
                $data[] = array(
                    'distributorid' => $row['distributorid'],
                    'entrydate' => $row['entrydate'],
                    'totalskus' => $totalskus,
                    'edit_option' => $edit_option,
                    'prid' => $row['prid'],
                    'srid' => $row['srid'],
                    'is_asm' => $row['is_asm'],
                    'addedby' => $row['realname'],
                    'addedby_role' => $row['role'],
                    'deliverydate' => $row['deliverydate'],
                    'isapproved' => $row['is_approved']
                );
            }
        }
        return $data;
    }

    public function get_secondarysales_orders_api($userid, $userkey_id, $shopid) {
        $data = array();
        $Query = "select u.realname,u.role,a.soid,a.addedby,a.shopid, a.entrydate,a.orderdate, a.is_asm from secondary_order as a
                    inner join " . SPEEDDB . ".user as u on a.addedby = u.userid
                    where  MONTH(a.entrydate) = MONTH(CURDATE()) AND  a.shopid =" . $shopid . " AND a.customerno=$this->customerno and a.isdeleted=0 AND is_deadstock = 0 order by a.entrydate desc ";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {

                $edit_option = 0;
                if ($row['addedby'] == $userkey_id) {
                    $edit_option = 1;
                }

                $totalskus = secondary_product_count($row['soid'], $this->customerno, $this->userid);  //count style
                $data[] = array(
                    'shopid' => $row['shopid'],
                    'entrydate' => $row['entrydate'],
                    'orderdate' => date(speedConstants::DEFAULT_DATETIME, strtotime($row['orderdate'])),
                    'totalstyles' => $totalskus,
                    'edit_option' => $edit_option,
                    'soid' => $row['soid'],
                    'is_asm' => $row['is_asm'],
                    'addedby' => $row['realname'],
                    'role' => $row['role']
                );
            }
        }
        return $data;
    }

    public function get_deadstock_orders_api($userid, $userkey_id, $shopid) {
        $data = array();
        $Query = " select a.addedby,a.is_asm,a.shopid,a.stockid,u.realname,u.role,a.stockdate,a.reason from  deadstock as a
                            inner join " . SPEEDDB . ".user as u on u.userid = a.addedby
                            where  a.entrytime >= DATE(NOW()) - INTERVAL 7 DAY AND  a.shopid = " . $shopid . " AND a.customerno= $this->customerno and a.isdeleted=0 ";

        $SQL = sprintf($Query);
        $this->executeQuery($SQL);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $edit_option = 0;
                if ($row['addedby'] == $userkey_id) {
                    $edit_option = 1;
                }

                $totalskus = deadstock_product_count($row['stockid'], $this->customerno, $this->userid);  //count style

                $data[] = array(
                    'stockid' => $row['stockid'],
                    'shopid' => $row['shopid'],
                    'reason' => $row['reason'],
                    'stockdate' => $row['stockdate'],
                    'totalstyles' => $totalskus,
                    'edit_option' => $edit_option,
                    'addedby' => $row['realname'],
                    'role' => $row['role']
                );
            }
        }

        return $data;
    }

    public function shopcount($userid, $role, $customerno) {
        if ($role == "ASM") {
            $supervisors = $this->getsupervisors_byasm($userid, $customerno);
            $supid = array();
            foreach ($supervisors as $row) {
                $supid[] = $row->userid;
            }
            $srdata = $this->get_sr_by_supervisors($supid, $customerno);
            $srid = array();
            foreach ($srdata as $row) {
                $srid[] = $row->userid;
            }
            $distdata = $this->getDistributordata_bysr($srid);
        } else if ($role == "Supervisor") {
            $srdata = $this->get_sr_by_supervisors($userid, $customerno);
            $srid = array();
            foreach ($srdata as $row) {
                $srid[] = $row->userid;
            }
            $distdata = $this->getDistributordata_bysr($srid);
        } else if ($role == "sales_representative") {
            $distdata = $this->getDistributordata_bysr($userid);
        } else {
            $distdata = $this->getDistributordata_bysr($userid, 'ALL');
        }
        $distid = array();
        foreach ($distdata as $row) {
            $distid[] = $row->userid;
        }
        $distid = implode(',', (array) $distid);






        $SQL = "SELECT count(*) as shopcount FROM shop WHERE distributorid IN (" . $distid . ") AND isdeleted=0  and customerno =$customerno";
        $Query = sprintf($SQL);

        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                return $row["shopcount"];
            }
        }
        return 0;
    }

    public function areacount($userid, $role, $customerno) {

        if ($role == "ASM") {
            $supervisors = $this->getsupervisors_byasm($userid, $customerno);
            $supid = array();
            foreach ($supervisors as $row) {
                $supid[] = $row->userid;
            }
            $srdata = $this->get_sr_by_supervisors($supid, $customerno);
            $srid = array();
            foreach ($srdata as $row) {
                $srid[] = $row->userid;
            }
            $distdata = $this->getDistributordata_bysr($srid);
        } else if ($role == "Supervisor") {
            $srdata = $this->get_sr_by_supervisors($userid, $customerno);
            $srid = array();
            foreach ($srdata as $row) {
                $srid[] = $row->userid;
            }
            $distdata = $this->getDistributordata_bysr($srid);
        } else if ($role == "sales_representative") {
            $distdata = $this->getDistributordata_bysr($userid);
        } else {
            $distdata = $this->getDistributordata_bysr($userid, 'ALL');
        }

        $distid = array();
        foreach ($distdata as $row) {
            $distid[] = $row->userid;
        }
        $distid = implode(',', (array) $distid);
        $SQL = "SELECT count(*) as areacount FROM area INNER JOIN speed.user ON " . SPEEDDB . ".`user`.userid = area.distributorid  WHERE area.distributorid IN (" . $distid . ")  AND area.isdeleted=0";
        $Query = sprintf($SQL);

        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                return $row["areacount"];
            }
        }
        return 0;
    }

    public function distcount($userid, $role, $customerno) {
        $distcount = 0;
        if ($role == "ASM") {
            $supervisors = $this->getsupervisors_byasm($userid, $customerno);
            $supid = array();
            foreach ($supervisors as $row) {
                $supid[] = $row->userid;
            }
            $srdata = $this->get_sr_by_supervisors($supid, $customerno);
            $srid = array();
            foreach ($srdata as $row) {
                $srid[] = $row->userid;
            }
            $distdata = $this->getDistributordata_bysr($srid);
        } else if ($role == "Supervisor") {
            $srdata = $this->get_sr_by_supervisors($userid, $customerno);
            $srid = array();
            foreach ($srdata as $row) {
                $srid[] = $row->userid;
            }
            $distdata = $this->getDistributordata_bysr($srid);
        } else if ($role == "sales_representative") {
            $distdata = $this->getDistributordata_bysr($userid);
        } else {
            $distdata = $this->getDistributordata_bysr($userid, 'ALL');
        }
        $distcount = count($distdata);
        return $distcount;
    }

    function get_otp_forgotpwd($username) {
        $customermanager = new CustomerManager();
        $smsStatus = new SmsStatus();

        $pdo = $this->CreatePDOConn();
        $todaysdate = date('Y-m-d H:i:s');
        $otpparam = '';
        $arr_p = Array();

        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Please enter registered username.";
//Prepare parameters
        $sp_params = "'" . $username . "'"
                . ",'" . $todaysdate . "'"
                . "," . "@userexists" . "";
        $sqlCallSP = "CALL " . SP_SPEED_FORGOT_PASSWORD . "($sp_params)";
        $result = $pdo->query($sqlCallSP)->fetch(PDO::FETCH_ASSOC);
//$this->ClosePDOConn($pdo);
//$this->db->next_result();
        $outputParamQuery = "SELECT @userexists as isUserExists";
        $outParamResult = $pdo->query($outputParamQuery)->fetch(PDO::FETCH_ASSOC);
        $isUserExists = $outParamResult['isUserExists'];
        if ($isUserExists) {
            $userid = $result['useridparam'];
            $otpparam = $result['otpparam'];
            $validuptodate = $result['otpvalidupto'];
            $email = $result['useremail'];
            $phone = $result['userphone'];
            $customerno = $result['custno'];
        }


        if ($otpparam == -1) {
            $arr_p['status'] = "unsuccessful";
            $arr_p['message'] = "Your otp request limit exceeded today.";
        } else {
            $isSMSSent = 0;
            $isEmailSent = 0;
            $statusMessage = '';
            $emailMessage = '';
            $smsMessage = '';
            $message = "OTP: " . $otpparam . "\r\n" . "Valid Until: " . date('d-M-Y h:i:s A', strtotime($validuptodate));

            if (!empty($phone)) {
                $smsStatus->customerno = $customerno;
                $smsStatus->userid = $userid;
                $smsStatus->vehicleid = 0;
                $smsStatus->mobileno = $phone;
                $smsStatus->message = $message;
                $smsStatus->cqid = 0;
                $smsStat = $customermanager->getSMSStatus($smsStatus);
                if ($smsStat == 0) {
                    $response = '';
                    $isSMSSent = sendSMSUtil($phone, $message, $response);
                    if ($isSMSSent == 1) {
                        $customermanager->sentSmsPostProcess($customerno, $phone, $message, $response, $isSMSSent, $userid, 0, 6);
                        $smsMessage = "OTP Number SMS sent successfully. ";
                    } else {
                        $smsMessage = "OTP Number SMS sending failed";
                    }
                }
            }

            if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $body = '';
                $body = $message;
                $body .= '<br/>Please login on your ElixiaSpeed Mobile App with your username and mentioned OTP.<br/><br/>';
                $subject = "ElixiaSpeed Forgot Password OTP";
                $arrToMailIds = array($email);
                $strCCMailIds = '';
                $strBCCMailIds = '';
                $attachmentFilePath = '';
                $attachmentFileName = '';
                $isEmailSent = $this->sendMail($arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $body, $attachmentFilePath, $attachmentFileName);

                if ($isEmailSent) {
                    $emailMessage = "OTP Number Email sent successfully";
//$statusMessage = $statusMessage != '' ? $statusMessage . ", " . $emailMessage : $emailMessage;
                } else {
                    $emailMessage = "OTP Number Email sending failed";
//$statusMessage = $statusMessage != '' ? $statusMessage . ", " . $emailMessage : $emailMessage;
                }
            }
            if ($smsMessage != '') {
                $statusMessage = $smsMessage;
            }
            if ($emailMessage != '') {
                $statusMessage .= "," . $emailMessage;
            }
            if ($smsMessage == '' && $emailMessage == '') {
                $statusMessage = "No Phone Number/Email ID Found";
            }
            $arr_p['status'] = "successful";
            $arr_p['message'] = $statusMessage;
        }

        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function sendMail(array $arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName) {
//include_once("../../cron/class.phpmailer.php");
        $isEmailSent = 0;
        $completeFilePath = '';
        if ($attachmentFilePath != '' && $attachmentFileName != '') {
            $completeFilePath = $attachmentFilePath . "/" . $attachmentFileName;
        }

        $mail = new PHPMailer();
        $mail->IsMail();

        $mail->ClearAddresses();
        $mail->ClearAllRecipients();
        $mail->ClearAttachments();
        $mail->ClearCustomHeaders();

        if (!empty($arrToMailIds)) {
            foreach ($arrToMailIds as $mailto) {
                $mail->AddAddress($mailto);
            }
            if (!empty($strCCMailIds)) {
                $mail->AddCustomHeader("CC: " . $strCCMailIds);
            }

            if (!empty($strBCCMailIds)) {
                $mail->AddCustomHeader("BCC: " . $strBCCMailIds);
            }
        }
        $mail->From = "noreply@elixiatech.com";
        $mail->FromName = "Elixia Speed";
        $mail->Sender = "noreply@elixiatech.com";
//$mail->AddReplyTo($from,"Elixia Speed");
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->IsHtml(true);
        if ($completeFilePath != '' && $attachmentFileName != '') {
            $mail->AddAttachment($completeFilePath, $attachmentFileName);
        }
//SEND Mail

        if ($mail->Send()) {
            $isEmailSent = 1; // or use booleans here
        }
        return $isEmailSent;
    }

    function update_password($userkey, $newpwd) {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "update password failed.";

        $todaysdate = date("Y-m-d H:i:s");
        $sp_params = "'" . $newpwd . "'"
                . ",'" . $userkey . "'"
                . ",'" . $todaysdate . "'";

        $queryCallSP = "CALL " . SP_UPDATE_NEWFORGOTPASSWORD . "($sp_params)";
        $result = $this->executeQuery($queryCallSP, __FILE__, __LINE__);
        $affectedRows = $this->get_affectedRows($result);
        if ($affectedRows > 0) {
            $arr_p['status'] = "successful";
            $arr_p['message'] = "update password successful.";
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

// checks for login
    function check_login($username, $password) {
        $retarray['status'] = "failure";
        $retarray['customername'] = null;
        $retarray['userkey'] = 0;
        $pdo = $this->CreatePDOConn();
        $todaysdate = date("Y-m-d H:i:s");
        $sp_params = "'" . $username . "'"
                . ",'" . $password . "'"
                . ",'" . $todaysdate . "'"
                . "," . '@usertype'
                . "," . '@userkeyparam';

        $queryCallSP = "CALL " . SP_AUTHENTICATE_FOR_LOGIN . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $this->ClosePDOConn($pdo);
        $outputParamsQuery = "SELECT @usertype AS usertype, @userkeyparam AS userkeyparam";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);

        if ($outputResult['userkeyparam'] != 0) {
            $usertype = $outputResult['usertype'];
            $userkeyparam = $outputResult['userkeyparam'];

            if ($usertype == 0 && $userkeyparam != 0) {
                $today = date("Y-m-d H:i:s");
                $retarray['userkey'] = $arrResult['userkey'];
                $retarray['realname'] = $arrResult['realname'];
                $retarray['customercompany'] = $arrResult['customercompany'];
                $retarray['role'] = $arrResult['role'];
                $retarray['customerno'] = $arrResult['customerno'];
                $retarray['userid'] = $arrResult['userid'];
                $retarray['status'] = 1;
//sr lists of ASM / supervisors
                $srlist = getsrlists_login($arrResult['role'], $arrResult['userid'], $arrResult['customerno']);
                $retarray['srlists'] = $srlist;
/////count add here //////
                $mob = new Sales($arrResult['customerno'], $arrResult['userid']);
                $retarray['shopcount'] = $mob->shopcount($arrResult['userid'], $arrResult['role'], $arrResult['customerno']);
                $retarray['areacount'] = $mob->areacount($arrResult['userid'], $arrResult['role'], $arrResult['customerno']);
                $retarray['distcount'] = $mob->distcount($arrResult['userid'], $arrResult['role'], $arrResult['customerno']);

                $sql = "UPDATE " . SPEEDDB . ".user SET lastlogin_android='" . $today . "' where userkey = '" . $arrResult['userkey'] . "' AND customerno= '" . $arrResult['customerno'] . "' LIMIT 1";
                $this->executeQuery($sql, __FILE__, __LINE__);
            } else if ($usertype == 1 && $userkeyparam != 0) {
                $retarray['status'] = "forgot_password_success";
                $retarray['userkey'] = $userkeyparam;
            } else {
                $retarray['status'] = "failure";
                $retarray['customername'] = null;
                $retarray['userkey'] = 0;
            }
        }
        echo json_encode($retarray);
        return $retarray;
    }

    function get_sr_list($role) {
        $data = array();
        if ($role == 'Supervisor') {
            $data = get_salespersons_by_supervisor($this->userid, $this->customerno);
        }if ($role == 'ASM') {
            $salesdata = get_supervisors_by_asm($this->userid, $this->customerno);
            $supids = array();
            if (isset($salesdata)) {
                foreach ($salesdata as $row) {
                    $supids[] = $row->userid;
                }
            }

            if (!empty($supids)) {
                $data = get_sr_by_supervisors($this->userid, $supids, $this->customerno);
            }
        }
        return $data;
    }

    function getsrlist($customerno) {
        $salesdata = null;
        $Query = sprintf("SELECT userid,role,roleid,email,realname,username,phone,heirarchy_id FROM " . SPEEDDB . ".`user` where role='sales_representative' AND isdeleted=0 AND customerno=" . $customerno);
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $salesuser = new stdClass();
                $salesuser->userid = $row['userid'];
                $salesuser->role = $row['role'];
                $salesuser->roleid = $row['roleid'];
                $salesuser->email = $row["email"];
                $salesuser->realname = $row['realname'];
                $salesuser->username = $row["username"];
                $salesuser->phone = $row['phone'];
                $salesuser->heirarchy_id = $row["heirarchy_id"];
                $salesdata[] = $salesuser;
            }

            return $salesdata;
        }
        return null;
    }

    public function geteditinventorydata($invid) {
        $Query = "SELECT i.invid,i.skuid,st.styleno,c.categoryname,i.quantity FROM inventory as i left join style as st on st.styleid = i.skuid left join category as c on c.categoryid = st.categoryid WHERE i.customerno=$this->customerno AND i.invid='%s' AND i.isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($invid));
        $this->executeQuery($SQL);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $salesuser = new stdClass();
                $salesuser->categoryname = $row['categoryname'];
                $salesuser->sku = $row['styleno'];
                $salesuser->qty = $row['quantity'];
                $salesuser->invid = $row['invid'];
                $salesdata[] = $salesuser;
            }
            return $salesdata;
        }
        return null;
    }

    public function getinventorydataqty($distid, $skuid) {
        $Query = "SELECT i.invid,i.skuid,st.styleno,c.categoryname,i.quantity FROM inventory as i left join style as st on st.styleid = i.skuid left join category as c on c.categoryid = st.categoryid WHERE i.distributorid = " . $distid . " AND  i.customerno=$this->customerno AND i.skuid=" . $skuid . " AND i.isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $salesuser = new stdClass();
                $salesuser->categoryname = $row['categoryname'];
                $salesuser->sku = $row['styleno'];
                $salesuser->qty = $row['quantity'];
                $salesuser->invid = $row['invid'];
                $salesdata[] = $salesuser;
            }
            return $salesdata;
        }
        return null;
    }

    public function add_inventorydata($distid, $skudata) {
        $is_asm = 0;
        if ($_SESSION['role_modal'] == "ASM") {
            $is_asm = 2;
        }
        if ($_SESSION['role_modal'] == "Supervisor") {
            $is_asm = 1;
        }

        $category = $skudata['category'];
        $sku = $skudata['sku'];
        $qty = $skudata['qty'];
        if (isset($category)) {
            for ($i = 0; $i < count($category); $i++) {

                $skuexists = $this->is_invsku_exists($sku[$i], $distid);
                if ($skuexists == FALSE) {
                    $Query = "INSERT INTO inventory (customerno,distributorid,skuid,quantity,stockdate,entrydate,is_asm,addedby) VALUES($this->customerno,%d,%d,%d,'%s','%s',%d,%d)";
                    $SQL = sprintf($Query, (int) $distid, $sku[$i], $qty[$i], $this->today, $this->today, $is_asm, $this->userid);
                    $this->executeQuery($SQL);
                }
            }
        }
    }

    public function is_invsku_exists($skuid, $distid) {
        $Query = "SELECT skuid FROM inventory WHERE customerno=$this->customerno AND skuid='%s' AND distributorid='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($skuid), Sanitise::String($distid));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function edit_inventorydata($invid, $qty) {
        $query = "Update inventory set  updated_by='" . $this->userid . "', updated_time ='" . $this->today . "',quantity=" . $qty . " where invid =" . $invid;
        $sql = sprintf($query);
        $this->executeQuery($sql);
    }

    public function add_inventorydata_api($role, $distid, $skudata, $userid) {
        $is_asm = 0;
        if ($role == "ASM") {
            $is_asm = 2;
        }
        if ($role == "Supervisor") {
            $is_asm = 1;
        }

        if (isset($skudata)) {
            foreach ($skudata as $row) {
                $skuexists = $this->is_invsku_exists($row['skuid'], $distid);
                if ($skuexists == FALSE) {
                    $Query = "INSERT INTO inventory (customerno,distributorid,skuid,quantity,stockdate,entrydate,is_asm,addedby) VALUES($this->customerno,%d,%d,%d,'%s','%s',%d,%d)";
                    $SQL = sprintf($Query, (int) $distid, $row['skuid'], $row['quantity'], $this->today, $this->today, $is_asm, $userid);
                    $this->executeQuery($SQL);
                }
            }
        }
    }

    public function edit_inventorydata_api($role, $qty, $invid, $userid) {

        $is_asm = 0;
        $is_approved = 0;
        if ($role == "ASM") {
            $is_asm = 2;
        }
        if ($role == "Supervisor") {
            $is_asm = 1;
        }


        $query = "Update inventory set updated_by='" . $userid . "', updated_time ='" . $this->today . "', quantity=" . $qty . " where invid =" . $invid;
        $sql = sprintf($query);
        $this->executeQuery($sql);
    }

    public function get_inventory_list($distid, $userid) {
        $Query = "select c.categoryname,i.invid,i.quantity,i.distributorid,u.realname as distributorname,st.styleid,st.categoryid,st.styleno,st.mrp,st.distprice,st.retailprice "
                . " from inventory as i left join " . SPEEDDB . ".user as u  on u.userid = i.distributorid left join style as st on st.styleid = i.skuid left join category as c on  c.categoryid = st.categoryid  where i.distributorid ='" . $distid . "' AND i.isdeleted=0";
        $sql = sprintf($Query);
        $this->executeQuery($sql);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $salesuser = new stdClass();
                $salesuser->distributorid = $row['distributorid'];
                $salesuser->distributorname = $row['distributorname'];
                $salesuser->styleid = $row['styleid'];
                $salesuser->categoryid = $row['categoryid'];
                $salesuser->categoryname = $row['categoryname'];
                $salesuser->styleno = $row['styleno'];
                $salesuser->mrp = $row['mrp'];
                $salesuser->distprice = $row['distprice'];
                $salesuser->retailprice = $row['retailprice'];
                $salesuser->quantity = $row['quantity'];
                $salesuser->orderid = $row['invid'];
                $salesdata[] = $salesuser;
            }
            return $salesdata;
        }
        return null;
    }

    public function get_sr_by_distdata($ids) {
        $salesdata = null;
        $Query = sprintf("SELECT userid,role,roleid,email,realname,username,phone,heirarchy_id FROM " . SPEEDDB . ".`user` where isdeleted=0 AND customerno=" . $this->customerno . " AND heirarchy_id IN( $supid) ");
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $salesuser = new stdClass();
                $salesuser->userid = $row['userid'];
                $salesuser->role = $row['role'];
                $salesuser->roleid = $row['roleid'];
                $salesuser->email = $row["email"];
                $salesuser->realname = $row['realname'];
                $salesuser->username = $row["username"];
                $salesuser->phone = $row['phone'];
                $salesuser->heirarchy_id = $row["heirarchy_id"];
                $salesdata[] = $salesuser;
            }
            return $salesdata;
        }
        return null;
    }

    public function get_editinventorydata($invid) {
        $Query = sprintf("SELECT i.distributorid,i.quantity,i.skuid,st.styleno,c.categoryid,c.categoryname FROM inventory as i left join style as st on st.styleid = i.skuid left join category as c on c.categoryid = st.categoryid  where i.isdeleted=0 AND i.customerno=" . $this->customerno . " AND i.invid = " . $invid);
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            $salesuser = new stdClass();
            while ($row = $this->get_nextRow()) {
                $salesuser = new stdClass();
                $salesuser->distributorid = $row['distributorid'];
                $salesuser->skuid = $row['skuid'];
                $salesuser->categoryid = $row['categoryid'];
                $salesuser->styleno = $row['styleno'];
                $salesuser->categoryname = $row['categoryname'];
                $salesuser->quantity = $row['quantity'];
                $salesdata[] = $salesuser;
            }
            return $salesdata;
        }
    }

    public function deleteinventory($invid) {
        $Query = "UPDATE inventory SET isdeleted=1, updated_by='" . $this->userid . "',updated_time='" . $this->today . "' where invid=" . $invid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        return 'Inventory Deleted successfully';
    }

    public function getseqid_byshopid($prior_shopid) {
        $shopdata = null;
        $Query = "select shopid,distributorid,areaid,salesid,sequence_no from shop where shopid = " . $prior_shopid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {

            while ($row = $this->get_nextRow()) {
                $shopdata[] = array(
                    'shopid' => $row['shopid'],
                    'distributorid' => $row['distributorid'],
                    'areaid' => $row['areaid'],
                    'salesid' => $row['salesid'],
                    'sequence_no' => $row['sequence_no']
                );
            }
            return $shopdata;
        }
        return $shopdata;
    }

    public function getshopdata_byarea($areaid) {
        $shopdata = null;
        $Query = "select shopid,distributorid,areaid,salesid,sequence_no from shop where areaid = " . $areaid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {

            while ($row = $this->get_nextRow()) {
                $shopdata[] = array(
                    'shopid' => $row['shopid'],
                    'distributorid' => $row['distributorid'],
                    'areaid' => $row['areaid'],
                    'salesid' => $row['salesid'],
                    'sequence_no' => $row['sequence_no']
                );
            }
            return $shopdata;
        }
        return $shopdata;
    }

    public function getarea_by_shoptable() {
        $Query = "select DISTINCT areaid from shop where customerno=" . $this->customerno;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $shopdata[] = array('areaid' => $row['areaid']);
            }
            return $shopdata;
        }
        return $shopdata;
    }

    public function update_sequence($areaid) {
        $Query = "select shopid,distributorid,sequence_no,areaid,salesid from shop where areaid =" . $areaid . " AND customerno=" . $this->customerno;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $shopdata[] = array(
                    'areaid' => $row['areaid'],
                    'shopid' => $row['shopid'],
                    'sequence_no' => $row['sequence_no'],
                    'salesid' => $row['salesid']
                );
            }
            $i = 0;
            foreach ($shopdata as $row) {
                $i++;
                echo"<br>" . $Query = "Update shop set sequence_no=" . $i . " where salesid = " . $row['salesid'] . " AND shopid = " . $row['shopid'] . " AND customerno=" . $this->customerno;
                $SQL = sprintf($Query);
                echo"<br>";
                $this->executeQuery($SQL);
            }
        }
        return $shopdata;
    }

    public function get_catdata_inv($distid) {
        echo $Query = sprintf("SELECT distinct(i.skuid),i.distributorid,i.quantity,st.styleno,c.categoryid,c.categoryname "
        . "FROM inventory as i "
        . "left join style as st on st.styleid = i.skuid "
        . "left join category as c on c.categoryid = st.categoryid "
        . " where i.isdeleted=0 AND i.customerno=" . $this->customerno . " AND i.isdeleted=0 AND i.distributorid = " . $distid);
        $this->executeQuery($Query);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getcatdata[] = array(
                    'categoryid' => $row['categoryid'],
                    'categoryname' => $row['categoryname']
                );
            }
            return $getcatdata;
        }
    }

    public function get_sku_invapi($distid, $categoryid) {
        $getskudata = array();
        $Query = "select st.styleid,st.categoryid,st.styleno,COALESCE(i.quantity, 0) as qty from style as st
                left join category as c on c.categoryid = st.categoryid
                left join inventory as i on i.skuid = st.styleid AND i.isdeleted=0 AND i.distributorid=" . $distid . "
                where st.isdeleted=0 AND st.customerno= $this->customerno AND st.categoryid=" . $categoryid;

        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getskudata[] = array(
                    'skuid' => utf8_encode($row['styleid']),
                    'categoryid' => utf8_encode($row['categoryid']),
                    'styleno' => utf8_encode($row['styleno']),
                    'quantity' => $row['qty']
                );
            }
            return $getskudata;
        }
        return NULL;
    }

}

?>
