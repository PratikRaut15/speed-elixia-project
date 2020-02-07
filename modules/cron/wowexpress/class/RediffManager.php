<?php

class VORediff {
    
}

define('Mpath', '../');
require_once '../../../lib/system/Log.php';
include_once '../../../config.inc.php';
include_once '../../../lib/system/DatabasePickupManager.php';

class RediffManager extends DatabasePickupManager {

    public function __construct($customerno, $userid) {
        parent::__construct($customerno, $userid);
        $this->customerno = $customerno;
        $this->userid = $userid;
        $this->today = date("Y-m-d H:i:s");
    }

    public function gethubkeys() {
        $Query = "SELECT hubkey,hubname FROM `hubkeys` where isdeleted=0";
        $hubquery = sprintf($Query);
        $this->executeQuery($hubquery);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $hubdata[] = array(
                    'hubkey' => $row['hubkey'],
                    'hubname' => $row['hubname']
                );
            }
            return $hubdata;
        }
        return null;
    }

    function GetData($hubKey) {
        $transactionList = array();
        $url = 'http://rss.rediff.com/rss/rest/osm/pending/1/' . $hubKey;
        $xml = simpleXML_load_file($url, "SimpleXMLElement", LIBXML_NOCDATA);
        if ($xml === FALSE) {
            echo "error";
        } else {
            $xmlarr = json_decode(json_encode($xml), 1);

            foreach ($xmlarr as $row) {
                foreach ($row as $value) {
                    foreach ($value as $row1) {
                        if (empty($row1['starwbll'])) {
                            $awpno = 0;
                        } else {
                            $awpno = $row1['starwbll'];
                        }

                        if (empty($row1['vnoffzipc'])) {
                            $pincode = 0;
                        } else {
                            $pincode = $row1['vnoffzipc'];
                        }

                        if (empty($row1['vnoffmobile'])) {

                            $phone = 0;
                        } else {
                            $phone = $row1['vnoffmobile'];
                        }

                        if (empty($row1['vnoffphone'])) {
                            $phone2 = 0;
                        } else {
                            $phone2 = $row1['vnoffphone'];
                        }


                        $transactionList[] = array(
                            'awpno' => $awpno,
                            'transno' => $row1['strfnbr'],
                            'vendoraddress' => $row1['vnoffaddr'],
                            'phone' => $phone,
                            'phone2' => $phone2,
                            'state' => $row1['vnoffstate'],
                            'city' => $row1['vnoffcity'],
                            'vendorname' => $row1['vnname'],
                            'vendorno' => $row1['vnrfnbr'],
                            'pincode' => $pincode
                        );
                    }
                }
            }
            return $transactionList;
        }
    }

    function InsertPickupOrders($transactionList) {
        foreach ($transactionList as $trans) {
            $fulladdress = $trans['vendoraddress'];
            if (!empty($trans['city'])) {
                $fulladdress .= "," . $trans['city'];
            }
            if (!empty($trans['state'])) {
                $fulladdress .= "," . $trans['state'];
            }
            if (!empty($trans['pincode'])) {
                $pincode = $trans['pincode'];
            } else {
                $pincode = "";
            }

            $reddiffCustid = 1;
            $wowExpressCustno = 127;
            //Specifying user id as -1 as this is CRON inserting the data rather thatn actual user.
            $userid = -1;
            $sp_name = "insert_woworders";
            //Prepare parameters
            $sp_params = "'" . $pincode . "'"
                    . ",'" . Sanitise::String($trans['vendorname']) . "'"
                    . ",'" . Sanitise::String($trans['phone']) . "'"
                    . ",'" . Sanitise::String($trans['phone2']) . "'"
                    . ",'" . Sanitise::String($fulladdress) . "'"
                    . ",'" . Sanitise::String($trans['vendorno']) . "'"
                    . ",'" . Sanitise::String($trans['transno']) . "'"
                    . ",'" . Sanitise::String($trans['awpno']) . "'"
                    . ",'" . $reddiffCustid . "'"
                    . ",'" . $wowExpressCustno . "'"
                    . ",'" . $this->today . "'"
                    . ",'" . $userid . "'"
                    . "," . "@lastid";
            $query = "call " . $sp_name . "(" . $sp_params . ");";
            $this->executeQuery($query);
        }
    }

}

?>