<?php

/**
 * class of Sales Engagement -module
 */
class Saleseng extends DatabaseSalesEngagementManager {

    public function __construct($customerno, $userid) {
        parent::__construct($customerno, $userid);
        $this->customerno = $customerno;
        $this->userid = $userid;
        $this->today = date("Y-m-d H:i:s");
    }

    public function getlistofproduct() {
        $Query = "SELECT productid, productname FROM products WHERE isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $productdata[] = array(
                    'id' => $row['productid'],
                    'productname' => $row['productname'],
                );
            }
            return $productdata;
        }
        return null;
    }

    public function insert_clientdata($clname, $caddress, $cemail, $cmobile, $cbirthdate) {
        $dob1 = date("Y-m-d", strtotime($cbirthdate));
        $emails = implode(',', $cemail);
        $query = "call insert_client( '" . $clname . "', '" . $caddress . "','" . $cmobile . "','" . $emails . "' ,'" . $dob1 . "',$this->customerno,'" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function update_clientdata($clientid, $clname, $caddress, $cemail, $cmobile, $cbirthdate) {
        $dob1 = date("Y-m-d", strtotime($cbirthdate));
        $emails = implode(',', $cemail);
        $query = "call update_client( '" . $clientid . "','" . $clname . "', '" . $caddress . "','" . $cmobile . "','" . $emails . "' ,'" . $dob1 . "','" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function delete_clientdata($clientid) {
        $query = "call delete_client( ".$clientid.",1,'". $this->today ."',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function delete_srcorderdata($srcordid) {
        $query = "call delete_sourceorder( '" . $srcordid . "',1,'" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function getclientdata_byid($id) {
        $Query = "SELECT * FROM clients WHERE customerno=$this->customerno AND clientid='%d' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($id));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $clientdata[] = array(
                    'id' => $row['clientid'],
                    'clientname' => $row['name'],
                    'address' => $row['address'],
                    'cemail' => $row['email'],
                    'mobileno' => $row['mobileno'],
                    'dob' => $row['dob']
                );
            }
            return $clientdata;
        }
        return null;
    }

    public function getclientdata_byid_cron($id) {
        $Query = "SELECT * FROM clients WHERE clientid='%d' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($id));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $clientdata[] = array(
                    'id' => $row['clientid'],
                    'clientname' => $row['name'],
                    'address' => $row['address'],
                    'email' => $row['email'],
                    'mobileno' => $row['mobileno'],
                    'dob' => $row['dob']
                );
            }
            return $clientdata;
        }
        return null;
    }

    public function insert_productdata($pname, $unitprice) {
        $query = "call insert_product( '" . $pname . "','" . $unitprice . "',$this->customerno,'" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function update_productdata($pid, $pname, $unitprice) {
        $query = "call update_product( '" . $pid . "','" . $pname . "','" . $unitprice . "','" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function getproductdata_byid($id) {
        $Query = "SELECT * FROM products WHERE customerno=$this->customerno AND productid='%d' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($id));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $productdata[] = array(
                    'id' => $row['productid'],
                    'productname' => $row['productname'],
                    'unitprice' => $row['unit_price'],
                );
            }
            return $productdata;
        }
        return null;
    }

    public function delete_productdata($productid) {
        $query = "call delete_product( '" . $productid . "',1,'" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function insert_addstagedata($stagename) {
        $query = "call insert_stages( '" . $stagename . "',$this->customerno,'" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function insert_addlostdata($lostreason) {
        $query = "call insert_lostreason( '" . $lostreason . "',$this->customerno,'" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function insert_addsourceorderdata($srcorder) {
        $query = "call insert_sourceorder( '" . $srcorder . "',$this->customerno,'" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function getstagedata_byid($id) {
        $Query = "SELECT * FROM stages WHERE customerno=$this->customerno AND stageid='%d' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($id));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $stagedata[] = array(
                    'id' => $row['stageid'],
                    'stagename' => $row['stagename'],
                );
            }
            return $stagedata;
        }
        return null;
    }

    public function getlostdata_byid($id) {
        $Query = "SELECT lostreasonid,reasons FROM lost_reasons WHERE customerno=$this->customerno AND lostreasonid='%d' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($id));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $lostdata[] = array(
                    'id' => $row['lostreasonid'],
                    'lostname' => $row['reasons'],
                );
            }
            return $lostdata;
        }
        return null;
    }

    public function getsourceorder_byid($id) {
        $Query = "SELECT * FROM sourceorder WHERE customerno=$this->customerno AND srcordid='%d' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($id));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $srcorddata[] = array(
                    'id' => $row['srcordid'],
                    'sourceorder' => $row['source_order'],
                );
            }
            return $srcorddata;
        }
        return null;
    }

    public function update_stagedata($stageid, $stagename) {
        $query = "call update_stages( $stageid,'" . $stagename . "','" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function update_lostdata($lostid, $lostreason) {
        $query = "call update_lostreason( $lostid,'" . $lostreason . "','" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function update_srcorderdata($srcid, $srcorder) {
        $query = "call update_sourceorder( $srcid,'" . $srcorder . "','" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function delete_stage($stageid) {
        $query = "call delete_stages( '" . $stageid . "',1,'" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function delete_lost($lostid) {
        $query = "call delete_lostreason( '" . $lostid . "',1,'" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function insert_reminderdata($remindername) {
        $query = "call insert_reminder( '" . $remindername . "',$this->customerno,'" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function update_reminderdata($rid, $remindername) {
        $query = "call update_reminder( '" . $rid . "','" . $remindername . "','" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function delete_reminder($reminderid) {
        $query = "call delete_reminder( '" . $reminderid . "','1','" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function delete_order($orderid) {

        $query = "select orderid from seed where orderid='%s' AND customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($query, Sanitise::String($orderid));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {

            $query = "call delete_seed( '" . $orderid . "','1','" . $this->today . "',$this->userid)";
            $SQL = sprintf($query);
            $this->executeQuery($SQL);
        }

        $query = "call delete_orders( '" . $orderid . "','1','" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);

        $query1 = "call delete_productsinorder( '" . $orderid . "','1','" . $this->today . "',$this->userid)";
        $SQL1 = sprintf($query1);
        $this->executeQuery($SQL1);
    }

    public function getreminderdata_byid($rid) {
        $Query = "SELECT reminderid,remindername FROM reminders WHERE customerno=$this->customerno AND reminderid='%d' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($rid));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $reminderdata[] = array(
                    'id' => $row['reminderid'],
                    'remindername' => $row['remindername'],
                );
            }
            return $reminderdata;
        }
        return null;
    }

    public function getsaleseditdata($id) {
        $Query = "SELECT srid,srname,sremail,srphone FROM salesmanager WHERE customerno=$this->customerno AND srid='%d' AND isdeleted=0";
        echo $SQL = sprintf($Query, Sanitise::String($id));
        exit;
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $salesdata[] = array(
                    'id' => $row['srid'],
                    'srname' => $row['srname'],
                    'sremail' => $row['sremail'],
                    'srphone' => $row['srphone']
                );
            }
            return $salesdata;
        }
        return null;
    }

    public function getclientdata($q) {
        $getdata = array();
        $lq = "%$q%";
        $Query = "SELECT clientid,name FROM clients WHERE customerno=$this->customerno AND name LIKE '%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($lq));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getdata[] = array(
                    'id' => $row['clientid'],
                    'value' => $row['name'],
                );
            }
            return $getdata;
        }
        return null;
    }

    public function is_stageorreminder_exists($reminderid, $stageid) {
        if (!empty($stageid)) {
            $Query = "SELECT StageId FROM templates WHERE customerno=$this->customerno AND StageId='%s' AND isdeleted=0";
            $SQL = sprintf($Query, $stageid);
        } else if (!empty($reminderid)) {
            $Query = "SELECT reminderid FROM templates WHERE customerno=$this->customerno AND reminderid='%s' AND isdeleted=0";
            $SQL = sprintf($Query, $reminderid);
        }
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function is_stagetemplate_exists($stageid) {
        $Query = "SELECT StageId FROM templates WHERE customerno=$this->customerno AND StageId='%s' AND isdeleted=0";
        $SQL = sprintf($Query, $stageid);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            $status = 1;
            return $status;
        } else {
            $status = 0;
            return $status;
        }
    }

    public function gettemplateby_stageid($stageid) {
        $Query = "SELECT * FROM templates WHERE customerno=$this->customerno AND StageId='%s' AND recipienttype =1 AND isdeleted=0";
        $SQL = sprintf($Query, $stageid);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $gettempdata[] = array(
                    'template_type' => $row['template_type'],
                    'email_subject' => $row['email_subject'],
                    'emailtemplate' => $row['emailtemplate'],
                    'smstemplate' => $row['smstemplate'],
                    'recipienttype' => $row['recipienttype']
                );
            }
            return $gettempdata;
        }
        return null;
    }

    public function gettemplateby_reminderid($remid, $recipienttype) {
        $Query = "SELECT * FROM templates WHERE  reminderid='%s' AND recipienttype ='%s' AND isdeleted=0";
        $SQL = sprintf($Query, $remid, $recipienttype);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $gettempdata[] = array(
                    'template_type' => $row['template_type'],
                    'email_subject' => $row['email_subject'],
                    'emailtemplate' => $row['emailtemplate'],
                    'smstemplate' => $row['smstemplate'],
                    'recipienttype' => $row['recipienttype']
                );
            }
            return $gettempdata;
        }
        return null;
    }

    public function insertemaillog($toemail, $subject, $messagebody, $orderid, $activityid, $isemailsent) {
        $messagebody = str_replace("'", "''", $messagebody);
        $query = "call insert_emaillog( '" . $toemail . "','" . $subject . "','" . $messagebody . "','" . $orderid . "','" . $activityid . "','" . $this->customerno . "','" . $isemailsent . "','" . $this->today . "')";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function insertsmslog($mobileno, $message, $orderid, $activityid, $issmssent, $response) {
        $message = str_replace("'", "''", $message);
        $query = "call insert_smslog( '" . $mobileno . "','" . $message . "','" . $orderid . "','" . $activityid . "','" . $this->customerno . "','" . $issmssent . "','" . $this->today . "','" . $response . "')";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function getproducts_email($pid1) {
        $pid1in = implode(",", $pid1);
        $Query = "SELECT productname,unit_price FROM products WHERE customerno=$this->customerno AND productid IN (" . $pid1in . ") AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getproductnames[] = array(
                    'productname' => $row['productname'],
                    'unit_price' => $row['unit_price']
                );
            }
            return $getproductnames;
        }
        return null;
    }

    public function is_client_email_exists($email) {
            $Query = "SELECT email FROM clients WHERE customerno=$this->customerno AND email='%s' AND isdeleted=0";
            $SQL = sprintf($Query, $email);
            $this->executeQuery($SQL);
            if ($this->get_rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        
    }

    public function is_product_exists($pname) {
        $Query = "SELECT productname FROM products WHERE customerno=$this->customerno AND productname='%s' AND isdeleted=0";
        $SQL = sprintf($Query, $pname);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function is_stage_exists($stagename) {
        $Query = "SELECT stagename FROM stages WHERE customerno=$this->customerno AND stagename='%s' AND isdeleted=0";
        $SQL = sprintf($Query, $stagename);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function is_lost_exists($lostreason) {
        $Query = "SELECT lostreasonid,reasons FROM lost_reasons WHERE customerno=$this->customerno AND reasons='%s' AND isdeleted=0";
        $SQL = sprintf($Query, $lostreason);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function is_sourceorder_exists($srcorder) {
        $Query = "SELECT source_order FROM sourceorder WHERE customerno=$this->customerno AND source_order='%s' AND isdeleted=0";
        $SQL = sprintf($Query, $srcorder);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function is_reminder_exists($remindername) {
        $Query = "SELECT remindername FROM reminders WHERE customerno=$this->customerno AND remindername='%s' AND isdeleted=0";
        $SQL = sprintf($Query, $remindername);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getstageautodata($q) {
        $getdata = array();
        $lq = "%$q%";
        $Query = "SELECT stageid,stagename FROM stages WHERE customerno=$this->customerno AND stagename LIKE '%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($lq));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getdata[] = array(
                    'id' => $row['stageid'],
                    'value' => $row['stagename'],
                );
            }
            return $getdata;
        }
        return null;
    }

    public function getstagedataselect() {
        $getdata = array();
        $Query = "SELECT stageid,stagename FROM stages WHERE customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getdata[] = array(
                    'id' => $row['stageid'],
                    'value' => $row['stagename']
                );
            }
            return $getdata;
        }
        return null;
    }

    public function getordersourceselect() {
        $getdata = array();
        $Query = "SELECT srcordid,source_order FROM sourceorder WHERE customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getdata[] = array(
                    'id' => $row['srcordid'],
                    'value' => $row['source_order']
                );
            }
            return $getdata;
        }
        return null;
    }

    public function getproductlist() {
        $Query = "SELECT productid,productname FROM products WHERE customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getdata[] = array(
                    'id' => $row['productid'],
                    'value' => $row['productname']
                );
            }
            return $getdata;
        }
        return null;
    }

    public function getlostreasons() {
        $Query = "SELECT lostreasonid,reasons FROM lost_reasons WHERE customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getdata[] = array(
                    'id' => $row['lostreasonid'],
                    'value' => $row['reasons']
                );
            }
            return $getdata;
        }
        return null;
    }

    public function getreminderdata($q) {
        $getdata = array();
        $lq = "%$q%";
        $Query = "SELECT reminderid,remindername FROM reminders WHERE customerno=$this->customerno AND remindername LIKE '%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($lq));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getdata[] = array(
                    'id' => $row['reminderid'],
                    'value' => $row['remindername'],
                );
            }
            return $getdata;
        }
        return null;
    }

    public function getreminderdataselect() {
        $getdata = array();
        $Query = "SELECT reminderid,remindername FROM  reminders WHERE customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getdata[] = array(
                    'id' => $row['reminderid'],
                    'value' => $row['remindername'],
                );
            }
            return $getdata;
        }
        return null;
    }

    public function insert_orderdata($prids, $ordersource, $clientid, $stageid, $eocd, $emailchk, $smschk, $emailsend, $smssend, $additionalcost, $totalcost) {
        $eocd1 = date("Y-m-d", strtotime($eocd));
        $query = "call insert_orders( @lastid,'" . $clientid . "','" . $stageid . "','" . $eocd1 . "','" . $emailchk . "','" . $smschk . "','" . $emailsend . "','" . $smssend . "','" . $additionalcost . "','" . $totalcost . "',$this->customerno,'" . $this->today . "',$this->userid,'" . $ordersource . "')";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
        $Query = "select @lastid as lastid1";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $lastid = $row['lastid1'];
            }
        }

        if (!empty($prids)) {
            foreach ($prids as $key => $value) {
                $query = "call insert_productsinorder( '" . $lastid . "','" . $value . "',$this->customerno,'" . $this->today . "',$this->userid)";
                $SQL = sprintf($query);
                $this->executeQuery($SQL);
            }
        }
        return $lastid;
    }

    public function update_orderdata($lostreasons, $lostnotes, $ordersource, $prids, $orderid, $clientid, $stageid, $eocd, $emailchk, $smschk, $emailsend, $smssend, $additionalcost, $totalcost) {
        $eocd1 = date("Y-m-d", strtotime($eocd));
        $query = "call update_orders('" . $orderid . "','" . $clientid . "','" . $stageid . "','" . $eocd1 . "','" . $emailchk . "','" . $smschk . "','" . $emailsend . "','" . $smssend . "','" . $additionalcost . "','" . $totalcost . "','" . $lostnotes . "','" . $this->today . "',$this->userid,'" . $ordersource . "','" . $lostreasons . "')";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
        //update products in orders
        if (!empty($prids)) {
            $prid = array();
            foreach ($prids as $key => $value) {
                $prid[] = $value;
            }

            $Query = "SELECT productid FROM productsinorder WHERE orderid='%s' AND isdeleted=0";
            $SQL = sprintf($Query, Sanitise::String($orderid));
            $this->executeQuery($SQL);
            if ($this->get_rowCount() > 0) {
                $oldproductid = array();
                while ($row = $this->get_nextRow()) {
                    $oldproductid[] = $row['productid'];
                }
                //print_r($oldproductid); exit;
                $test1 = array_filter(array_diff($prid, $oldproductid));
                $test2 = array_filter(array_diff($oldproductid, $prid));

//               print_r($test1);
                //              print_r($test2);
                //            exit;
                if (!empty($test1)) {
                    $this->insertprids($test1, $orderid);
                }
                if (!empty($test2)) {
                    $this->Delprids($test2, $orderid);
                }
            } else {
                $this->insertprids($prid, $orderid);
            }
        }
    }

    public function insertprids($pids, $orderid) {
        foreach ($pids as $key => $value) {
            $Query = "Insert into productsinorder (customerno,orderid,productid,entrytime,addedby) VALUES($this->customerno,'%s','%s','$this->today',$this->userid)";
            $SQL = sprintf($Query, Sanitise::String($orderid), Sanitise::String($value));
            $this->executeQuery($SQL);
        }
    }

    public function Delprids($test2, $orderid) {
        foreach ($test2 as $key => $value) {
            $Query1 = "update productsinorder set isdeleted=1, updatedtime='" . $this->today . "', updated_by='" . $this->userid . "' where productid='" . $value . "' AND orderid=" . $orderid;
            $SQL1 = sprintf($Query1);
            $this->executeQuery($SQL1);
        }
    }

    public function getorderdata_byid($id) {
        $Query = "SELECT o.orderid,o.srcordid,o.addedby, c.clientid,c.email,c.mobileno, c.name, s.stagename, s.stageid, o.expectedordercomplitiondate, o.isemailrequested, o.issmsrequested,o.additionalcost, o.totalamount FROM orders as o inner join clients as c on c.clientid = o.clientid left join stages as s on s.stageid = o.stageid WHERE o.customerno=$this->customerno AND o.orderid=%d AND o.isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($id));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $orderdata[] = array(
                    'orderid' => $row['orderid'],
                    'clientid' => $row['clientid'],
                    'name' => $row['name'],
                    'stageid' => $row['stageid'],
                    'stagename' => $row['stagename'],
                    'lastdate' => $row['expectedordercomplitiondate'],
                    'isemailrequested' => $row['isemailrequested'],
                    'issmsrequested' => $row['issmsrequested'],
                    'additionalcost' => $row['additionalcost'],
                    'totalamount' => $row['totalamount'],
                    'cemail' => $row['email'],
                    'mobileno' => $row['mobileno'],
                    'elixirid' => $row['addedby'],
                    'srcorderid' => $row['srcordid']
                );
            }
            return $orderdata;
        }
        return null;
    }

    public function getorderproductlist_byid($id) {
        $Query = "select pio.orderid, pio.productid, p.productname, p.unit_price from  productsinorder as pio left join products as p  on p.productid = pio.productid WHERE pio.customerno=$this->customerno AND pio.orderid=%d AND pio.isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($id));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $productlist[] = array(
                    'orderid' => $row['orderid'],
                    'productid' => $row['productid'],
                    'productname' => $row['productname'],
                    'unit_price' => $row['unit_price']
                );
            }
            return $productlist;
        }
        return null;
    }

    public function chk_orderstage_ifchange($orderid, $stageid) {
        $query = "select * from orders where orderid = %d AND stageid =%d";
        $SQL = sprintf($query, Sanitise::String($orderid), Sanitise::String($stageid));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            $st = 0; //nochanges
        } else {
            $query = "update orders set isemailrequested=0, issmsrequested=0, isemailsent=0, issmssent=0 where orderid =%d";
            $SQL = sprintf($query, Sanitise::String($orderid));
            $this->executeQuery($SQL);
            $st = 1; //changes 
        }
        return $st;
    }

    public function insert_tempalate($templatetype, $reminderid1, $stageid1, $emailsubject, $emailtemp, $smstemp, $rtype) {

        if ($templatetype == "1") {
            $reminderid = $reminderid1;
            $stageid = "";
        }
        if ($templatetype == '2') {
            $stageid = $stageid1;
            $reminderid = "";
        }
        $query = "call insert_templates('" . $reminderid . "','" . $stageid . "','" . $templatetype . "','" . $emailsubject . "','" . $emailtemp . "','" . $smstemp . "','" . $rtype . "','" . $this->customerno . "','" . $this->today . "','" . $this->userid . "')";
        $this->executeQuery($query);
    }

    public function update_tempalate($templateid, $templatetype, $reminderid1, $stageid1, $emailsubject, $emailtemp, $smstemp, $rtype) {
        if ($templatetype == "1") {
            $reminderid = $reminderid1;
            $stageid = "";
        }

        if ($templatetype == '2') {
            $stageid = $stageid1;
            $reminderid = "";
        }
        $query = "call update_templates( '" . $templateid . "','" . $reminderid . "','" . $stageid . "','" . $templatetype . "','" . $emailsubject . "','" . htmlentities($emailtemp) . "','" . $smstemp . "','" . $rtype . "','" . $this->today . "',$this->userid)";
        $this->executeQuery($query);
    }

    public function delete_template($tempid) {
        $query = "call delete_templates( '" . $tempid . "','1','" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function gettemplatedata_byid($id) {
        $Query = "SELECT s.stagename,r.remindername,t.templateid,t.email_subject, t.reminderid, t.StageId, t.template_type, t.isemailrequested, t.issmsrequested, t.emailtemplate, t.smstemplate, t.recipienttype 
FROM templates as t left join reminders as r on r.reminderid = t.reminderid 
left join stages as s on s.stageid = t.stageid
WHERE t.customerno=$this->customerno AND t.templateid=%d AND t.isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($id));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $orderdata[] = array(
                    'reminderid' => $row['reminderid'],
                    'stageid' => $row['StageId'],
                    'typeid' => $row['template_type'],
                    'isemailrequested' => $row['isemailrequested'],
                    'issmsrequested' => $row['issmsrequested'],
                    'emailtemplate' => $row['emailtemplate'],
                    'smstemplate' => $row['smstemplate'],
                    'recipienttype' => $row['recipienttype'],
                    'remindername' => $row['remindername'],
                    'stagename' => $row['stagename'],
                    'templateid' => $row['templateid'],
                    'email_subject' => $row['email_subject']
                );
            }
            return $orderdata;
        }
        return null;
    }

    public function getactivity_byid($id) {
        $Query = "select * from activities as a left join reminders as r on r.reminderid = a.reminderid where a.customerno=" . $this->customerno . " AND a.orderid =%d AND a.isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($id));
        $this->res = $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $activitydata[] = array(
                    'activityId' => $row['activityId'],
                    'orderid' => $row['orderid'],
                    'reminderid' => $row['reminderid'],
                    'remindername' => $row['remindername'],
                    'notes' => $row['notes'],
                    'activitytime' => $row['activitytime'],
                    'activity_reminder_duration' => $row['activity_reminder_duration'],
                    'isactivitydone' => $row['isactivitydone'],
                    'isemailrequested' => $row['isemailrequested'],
                    'issmsrequested' => $row['issmsrequested'],
                    'isemailsent' => $row['isemailsent'],
                    'issmssent' => $row['issmssent'],
                    'paymentamount' => $row['paymentamount'],
                    'activitytype' => $row['activitytype'],
                );
            }
            return $activitydata;
        }
        return null;
    }

    public function insert_activitydata($orderid, $rmid, $notes, $sdate, $stime, $activityrduration, $activitystatus, $emailreq, $smsreq, $paymentamt, $activitytype, $userid) {
        $activitytime1 = $sdate . ' ' . $stime;
        $query = "call insert_activities('" . $orderid . "','" . $rmid . "','" . $notes . "','" . $activitytime1 . "','" . $activityrduration . "','" . $activitystatus . "','" . $emailreq . "','" . $smsreq . "','0','0','" . $paymentamt . "','" . $activitytype . "','" . $userid . "','" . $this->customerno . "','" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function editgetactivity_byid($id, $aid) {
        $Query = "select * from activities as a left join reminders as r on r.reminderid = a.reminderid where a.customerno=" . $this->customerno . " AND a.orderid =%d AND a.activityId = %d AND a.isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($id), Sanitise::String($aid));
        $this->res = $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $activitydata[] = array(
                    'activityId' => $row['activityId'],
                    'orderid' => $row['orderid'],
                    'reminderid' => $row['reminderid'],
                    'remindername' => $row['remindername'],
                    'notes' => $row['notes'],
                    'activitytime' => $row['activitytime'],
                    'activity_reminder_duration' => $row['activity_reminder_duration'],
                    'isactivitydone' => $row['isactivitydone'],
                    'isemailrequested' => $row['isemailrequested'],
                    'issmsrequested' => $row['issmsrequested'],
                    'isemailsent' => $row['isemailsent'],
                    'issmssent' => $row['issmssent'],
                    'paymentamount' => $row['paymentamount'],
                    'activitytype' => $row['activitytype'],
                );
            }
            return $activitydata;
        }
        return null;
    }

    public function update_activitydata($activityid, $orderid, $rmid, $notes, $sdate, $stime, $activityrduration, $activitystatus, $emailreq, $smsreq, $paymentamt, $activitytype, $userid) {
        $activitytime1 = $sdate . ' ' . $stime;
        $query = "call update_activities('" . $activityid . "','" . $orderid . "','" . $rmid . "','" . $notes . "','" . $activitytime1 . "','" . $activityrduration . "','" . $activitystatus . "','" . $emailreq . "','" . $smsreq . "','0','0','" . $paymentamt . "','" . $activitytype . "','" . $userid . "','" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function delete_activity($actid) {
        $query = "call delete_activities( '" . $actid . "','1','" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function insert_salesdata($srname, $sremail, $srphone) {
        $userkey = mt_rand(0, 999999);
        $query = "call insert_salesmanager( '" . $srname . "', '" . $sremail . "','" . $srphone . "','" . $userkey . "',$this->customerno,'" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function update_salesdata($srid, $srname, $sremail, $srphone) {
        $query = "call update_salesmanager( '" . $srid . "','" . $srname . "','" . $sremail . "','" . $srphone . "','" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function delete_salesdata($srid) {
        $query = "call delete_salesmanager('" . $srid . "','1','" . $this->today . "',$this->userid)";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }

    public function getactivity() {  //for cron
        $Query = "select *,o.addedby from activities as a left join reminders as r on r.reminderid = a.reminderid left join orders as o on o.orderid = a.orderid  where a.isdeleted=0 AND a.isactivitydone=0 ";
        $SQL = sprintf($Query);
        $this->res = $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $activitydata[] = array(
                    'activityId' => $row['activityId'],
                    'orderid' => $row['orderid'],
                    'reminderid' => $row['reminderid'],
                    'remindername' => $row['remindername'],
                    'notes' => $row['notes'],
                    'activitytime' => $row['activitytime'],
                    'activity_reminder_duration' => $row['activity_reminder_duration'],
                    'isactivitydone' => $row['isactivitydone'],
                    'isemailrequested' => $row['isemailrequested'],
                    'issmsrequested' => $row['issmsrequested'],
                    'isemailsent' => $row['isemailsent'],
                    'issmssent' => $row['issmssent'],
                    'paymentamount' => $row['paymentamount'],
                    'activitytype' => $row['activitytype'],
                    'userid' => $row['userid'],
                    'clientid' => $row['clientid'],
                    'addedby' => $row['addedby']
                );
            }
            return $activitydata;
        }
        return null;
    }

    public function getuserdetailsforcron($addedby) {
        $query = "select email, phone from " . SPEEDDB . ".`user` where userid=%d";
        $SQL = sprintf($query, Sanitise::String($addedby));
        $this->res = $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row1 = $this->get_nextRow()) {
                $userdata[] = array(
                    'email' => $row1['email'],
                    'phone' => $row1['phone']
                );
            }
            return $userdata;
        }
        return NULL;
    }

    public function getclientdetailsforcron($clientid) {
        $query = "select email,name, mobileno from clients where clientid=%d";
        $SQL = sprintf($query, Sanitise::String($clientid));
        $this->res = $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row1 = $this->get_nextRow()) {
                $clientdata[] = array(
                    'cemail' => $row1['email'],
                    'cphone' => $row1['mobileno'],
                    'cname' => $row1['name']
                );
            }
            return $clientdata;
        }
        return NULL;
    }

    public function activity_updatebycron($activityid, $emailsend, $smssend) {
        $Query = "update activities set isemailrequested='" . $emailsend . "',isactivitydone =1,issmsrequested='" . $smssend . "' where activityId='" . $activityid . "'";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }
    
    
    public function check_seed_exists($lastid,$clientid){
        $query = "select * from seed where customerno='%s' AND clientid='%s' AND isdeleted=0";
        $SQL = sprintf($query, Sanitise::String($this->customerno),Sanitise::String($clientid));
        $this->res = $this->executeQuery($SQL);
        if($this->get_rowCount()==1){
             $addid = $this->get_rowCount() +1;
            $ordno = "ORD-" . $this->customerno . '-' . $clientid . '-' . $addid;
            $query = "INSERT INTO seed (clientid,orderid,orderno,customerno,entrytime,addedby) VALUES ('%s','%s','%s','$this->customerno','$this->today','$this->userid');";
            $SQL = sprintf($query, Sanitise::String($clientid), Sanitise::String($lastid), Sanitise::String($ordno));
            $this->res = $this->executeQuery($SQL);
            $this->update_orderno_ordertable($lastid, $ordno);
        }else if ($this->get_rowCount() > 1) {
                $addid = $this->get_rowCount() +1;
                $ordno = "ORD-" . $this->customerno . '-' . $clientid . '-' . $addid; 
                $query = "INSERT INTO seed (clientid,orderid,orderno,customerno,entrytime,addedby) VALUES ('%s','%s','%s','$this->customerno','$this->today','$this->userid');";
                $SQL = sprintf($query, Sanitise::String($clientid), Sanitise::String($lastid), Sanitise::String($ordno));
                $this->res = $this->executeQuery($SQL);
                $this->update_orderno_ordertable($lastid, $ordno);
            } else {
            $ordno = "ORD-" . $this->customerno . '-' . $clientid . "-1";
            $query = "INSERT INTO seed (clientid,orderid,orderno,customerno,entrytime,addedby) VALUES ('%s','%s','%s','$this->customerno','$this->today','$this->userid');";
            $SQL = sprintf($query, Sanitise::String($clientid), Sanitise::String($lastid), Sanitise::String($ordno));
            $this->res = $this->executeQuery($SQL);
            $this->update_orderno_ordertable($lastid, $ordno);
        }
    }
     

    public function update_orderno_ordertable($lastid, $ordno) {
        $Query1 = "update orders set orderno='" . $ordno . "', updatedtime='" . $this->today . "', updated_by='" . $this->userid . "' where orderid=" . $lastid;
        $SQL1 = sprintf($Query1);
        $this->executeQuery($SQL1);
    }

    public function updatesmsforsalesengage($smsleft, $id) {
        $SQL = sprintf("UPDATE " . SPEEDDB . ".`customer` SET smsleft=%d WHERE customerno=%d", Sanitise::Long($smsleft), Sanitise::Long($id));
        $this->executeQuery($SQL);
    }

    public function pullsmsdetails($id) {
        $SQL = sprintf("SELECT totalsms,smsleft FROM " . SPEEDDB . ".`customer` WHERE customerno=%d", Sanitise::Long($id));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {

                $smsdetails[] = array(
                    'totalsms' => $row['totalsms'],
                    'smsleft' => $row['smsleft']
                );
            }
            return $smsdetails;
        }
        return false;
    }
    
    public function SaveClients($salesdata, $userid) {
        if (isset($salesdata->name)) {
            $this->Insert($salesdata, $userid);
        } 
    }
    
    public function Insert($salesdata, $userid){
        
       $query = "call insert_client( '" . $salesdata->name. "', '" . $salesdata->address. "','" . $salesdata->mobileno. "','" . $salesdata->email. "' ,'" . $salesdata->dob . "',$this->customerno,'" . $this->today . "',$this->userid)";
       $SQL = sprintf($query);
        $this->executeQuery($SQL);
    }


    
}
?>

