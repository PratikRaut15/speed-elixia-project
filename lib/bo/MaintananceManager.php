<?php
include_once '../../lib/system/Validator.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/model/VOVehicle.php';
include_once '../../lib/model/VOMaintanance.php';
include_once '../../lib/system/Date.php';
include_once '../../lib/model/VOAccessory.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/DealerManager.php';
include_once '../../lib/comman_function/reports_func.php';

class MaintananceManager extends VersionedManager {

    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function get_session_role($roleid) {
        $roleName = '';
        if ($roleid == 1) {
            $roleName = $_SESSION['master'];
        } elseif ($roleid == 2) {
            $roleName = $_SESSION['statehead'];
        } elseif ($roleid == 3) {
            $roleName = $_SESSION['districthead'];
        } elseif ($roleid == 4) {
            $roleName = $_SESSION['cityhead'];
        } elseif ($roleid == 5) {
            $roleName = $_SESSION['administrator'];
        } else {
            $roleName = '';
        }
    }

    public function getparentroleid($roleid) {
        $parentroleid=' ';
        $Query = "select parentroleid from role where customerno=$this->_Customerno AND id=" . $roleid;
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()){
                $parentroleid = $row['parentroleid'];
            }
        }
        return $parentroleid;
    }

    public function get_all_transaction($statusid = null, $Isapproval = null) {
        $vm = new VehicleManager($_SESSION['customerno']);
        $groups = $vm->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);
        $getparentroleid = $this->getparentroleid($_SESSION['roleid']);
        $getparentroleid = isset($getparentroleid) ? $getparentroleid : '';



        $roles = $this->getRoles();
        $maintanances = Array();
        $Query = "SELECT user.heirarchy_id
                        , maintenance.statusid
                        , vehicle.vehicleid
                        , user.realname
                        , maintenance.submission_date
                        , maintenance.timestamp
                        , group.groupname
                        , maintenance.id
                        , maintenance.roleid
                        , maintenance.category
                        , maintenance.invoice_no as invnohist
                        , description.invoiceno as invno
                        , maintenance.invoice_amount as invhistamt
                        , maintenance.amount_quote
                        , description.invoiceamt as invamt
                        , vehicle.vehicleno
                        , maintenance_status.name
                        , dealer.name as dname
                        , role.id as approverRoleId
                        , role.role as approverRoleName
                        , maintenance.invoice_date
                        , maintenance.vehicle_out_date
                        , maintenance.meter_reading
                FROM        maintenance
                INNER JOIN  vehicle on maintenance.vehicleid=vehicle.vehicleid
                LEFT OUTER JOIN description ON maintenance.vehicleid = description.vehicleid
                left join   dealer on dealer.dealerid=maintenance.dealer_id and dealer.isdeleted=0
                LEFT OUTER JOIN `maintenance_status` ON `maintenance`.statusid = `maintenance_status`.id
                LEFT OUTER JOIN `user` ON `user`.userid = `maintenance`.userid
                LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                LEFT OUTER JOIN `role` ON `role`.id = maintenance.roleid
                WHERE maintenance.customerno =%d AND maintenance.statusid NOT IN (14, 15)";
        /*
          if ($_SESSION['roleid'] == '1' || $_SESSION['roleid'] == '8') {
          $Query .= " WHERE maintenance.customerno =%d";
          } else if ($_SESSION['roleid'] == '2') {
          $Query .=" LEFT OUTER JOIN city on city.cityid = group.cityid
          LEFT OUTER JOIN district on district.districtid = city.districtid
          LEFT OUTER JOIN state on state.stateid = district.stateid";
          $Query .= " WHERE maintenance.customerno =%d AND state.stateid = " . $_SESSION['heirarchy_id'] . " ";
          } else if ($_SESSION['roleid'] == '3') {
          $Query .=" LEFT OUTER JOIN city on city.cityid = group.cityid
          LEFT OUTER JOIN district on district.districtid = city.districtid ";
          $Query .= " WHERE maintenance.customerno =%d AND district.districtid = " . $_SESSION['heirarchy_id'] . " ";
          } else if ($_SESSION['roleid'] == '4') {
          $Query .=" LEFT OUTER JOIN city on city.cityid = group.cityid";
          $Query .= " WHERE maintenance.customerno =%d AND city.cityid = " . $_SESSION['heirarchy_id'] . " ";
          } else {
          $Query .= " WHERE maintenance.customerno =%d";
          }
         */
        if ($statusid != null)
            $Query.=" AND maintenance.statusid IN (7,10,11) ";
        if ($_SESSION['groupid'] != 0) {
            $Query.=" AND vehicle.groupid =%d ";
        } else {
            if ($groups[0] != 0) {
                $groupid_ids = implode(',', $groups);
                $Query.=" AND vehicle.groupid in (" . $groupid_ids . ") ";
            }
        }

        if ($_SESSION['groupid'] != 0) {
            $maintananceQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $maintananceQuery = sprintf($Query, $this->_Customerno);
        }
        $maintananceQuery.=" AND maintenance.isdeleted = 0 AND maintenance.is_cancelled=0";
        $maintananceQuery.=" ORDER BY maintenance.timestamp DESC";

        $this->_databaseManager->executeQuery($maintananceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $maintanance = new VOMaintanance();
                $maintanance->id = $row['id'];
                $maintanance->vehicleid = $row['vehicleid'];
                $maintanance->roleid = $row["approverRoleId"];
                $tempRole = $this->get_session_role($row["approverRoleId"]);
                $maintanance->role = ($tempRole == '') ? $row['approverRoleName'] : $tempRole;
                $maintanance->vehicleno = $row['vehicleno'];
                $maintanance->dname = $row['dname'];
                $maintanance->invoice_amount = $row['invhistamt'];
                $maintanance->statusname = $row['name'];
                $maintanance->sender = $row['realname'];
                $maintanance->statusid = $row['statusid'];
                $maintanance->group = $row['groupname'];
                $maintanance->quote_amount = $row['amount_quote'];
                $maintanance->meter_reading = $row['meter_reading'];
                if ($row['invoice_date'] == '0000-00-00' || $row['invoice_date'] == '1970-01-01' || $row['invoice_date'] == '') {
                    $maintanance->invoice_date = '';
                } else {
                    $maintanance->invoice_date = date('d-m-Y', strtotime($row['invoice_date']));
                }


                if ($row['vehicle_out_date'] == '0000-00-00' || $row['vehicle_out_date'] == '1970-01-01' || $row['vehicle_out_date'] == '') {
                    $maintanance->vehicle_out_date = '';
                } else {
                    $maintanance->vehicle_out_date = date('d-m-Y', strtotime($row['vehicle_out_date']));
                }

                if ($row['category'] == '0') {
                    $maintanance->category = "Battery";
                    $maintanance->trans = "B00" . $row["id"];
                }
                if ($row['category'] == '1') {
                    $maintanance->category = "Tyre";
                    $maintanance->trans = "T00" . $row["id"];
                }
                if ($row['category'] == '2') {
                    $maintanance->category = "Repair";
                    $maintanance->trans = "R00" . $row["id"];
                }
                if ($row['category'] == '3') {
                    $maintanance->category = "Service";
                    $maintanance->trans = "S00" . $row["id"];
                }
                if ($row['category'] == '5') {
                    $maintanance->category = "Accessory";
                    $maintanance->trans = "A00" . $row["id"];
                }
                $maintanance->timestamp = date("d-m-Y", strtotime($row["timestamp"]));
                $maintanance->submit_date = date("d-m-Y", strtotime($row["submission_date"]));
                $maintanance->chk_box = '';
                if (isset($row["approverRoleId"])) {
                    $val_chk = $maintanance->id . "-" . $maintanance->statusid . "-TRANS-" . $maintanance->invoice_amount;
                    if ($_SESSION['roleid'] == isset($roles['accountRoleId']) ? $roles['accountRoleId'] : "" || $_SESSION['roleid'] == isset($roles['masterRoleId']) ? $roles['masterRoleId'] : "" || $_SESSION['roleid'] == isset($roles['elixirRoleId']) ? $roles['elixirRoleId'] : "") {
                        if ($maintanance->statusid == 13) {
                            $maintanance->chk_box = "<input type='checkbox' class='call-checkbox' value='" . $val_chk . "'/>";
                        }
                    }
                }

                if ($Isapproval != null) {
                    $edit = "<a href='approvals.php?id=4&tid=$maintanance->id' ><i class='icon-pencil'></i></a>";
                    if ($_SESSION['roleid'] == $maintanance->roleid || $_SESSION['roleid'] == $roles['masterRoleId']) {
                        $maintanance->edit = $edit;
                    } else if (($maintanance->statusid == '7') && $_SESSION['roleid'] == $maintanance->roleid) {
                        $maintanance->edit = $edit;
                    } else {
                        $maintanance->edit = '';
                    }
                    $maintanance->approval_chkdata = $maintanance->id . "-" . $maintanance->statusid . "-TRANS-" . $maintanance->invoice_amount;
                } else {
                    $arrRolesWithAmendmentRights = array(isset($roles['elixirRoleId']) ? $roles['elixirRoleId'] : "", isset($roles['masterRoleId']) ? $roles['masterRoleId'] : "", isset($roles['stateRoleId']) ? $roles['stateRoleId'] : "", isset($roles['zoneRoleId']) ? $roles['zoneRoleId'] : "", isset($roles['regionRoleId']) ? $roles['regionRoleId'] : "", isset($roles['coExecutiveId']) ? $roles['coExecutiveId'] : "");
                    if (($maintanance->statusid == '13') && $_SESSION['customerno'] == 118 && $_SESSION['roleid'] == $roles['accountRoleId']) {
                        $maintanance->edit = "<a href='transaction.php?id=3&accid=$maintanance->id&vehicleid=$maintanance->vehicleid'><i class='icon-pencil'></i></a>";
                    } else if ($maintanance->statusid == '7' || $maintanance->statusid == '9' || $maintanance->statusid == '10' || $maintanance->statusid == '11' || $maintanance->statusid == '6') {
                        $maintanance->edit = "<a href='transaction.php?id=5&accid=$maintanance->id&vehicleid=$maintanance->vehicleid' ><img src='../../images/view.png'></img></td></a>";
                    } elseif ($maintanance->statusid == '14') {
                        $maintanance->edit = "<a href='transaction.php?id=5&accid=$maintanance->id&vehicleid=$maintanance->vehicleid' ><img src='../../images/view.png'></img></td></a> |
      <a href='javascript:void(0);' onclick='print_battery_closed($maintanance->id,$maintanance->vehicleid)'><img src='../../images/print.png'></img></a>";
                    } elseif (in_array($_SESSION['roleid'], $arrRolesWithAmendmentRights)) {
                        //$maintanance->edit="<a href='transaction.php?id=3&accid=$maintanance->id&vehicleid=$maintanance->vehicleid'><i class='icon-pencil'></i></a>";
                        $delete_trans = "";
                        if ($maintanance->statusid == '13') {
                            $delete_trans = "| <a a onClick=\"javascript: return confirm('Please confirm deletion');\"href='route_ajax.php?delmainid=$maintanance->id' ><i class='icon-trash'></i> ";
                        }
                        if ($maintanance->statusid == '8') {
                            $maintanance->edit = "<a href='transaction.php?id=3&accid=$maintanance->id&vehicleid=$maintanance->vehicleid'><i class='icon-pencil'></i></a> " . $delete_trans;
                        } else {
                            $maintanance->edit = "<a href='transaction.php?id=3&accid=$maintanance->id&vehicleid=$maintanance->vehicleid'><i class='icon-pencil'></i></a> " . $delete_trans;
                        }
                    } else if ($_SESSION['roleid'] != $roles['accountRoleId']) {
                        $maintanance->edit = "<a href='transaction.php?id=3&accid=$maintanance->id&vehicleid=$maintanance->vehicleid' ><i class='icon-pencil'></i></a>";
                    } else {
                        $maintanance->edit = "<a href='transaction.php?id=3&accid=$maintanance->id&vehicleid=$maintanance->vehicleid' ></i></td></a>";
                    }
                }
                //$maintanance->cancelled = $maintanance->id;
                $maintid = $maintanance->id;
                $rollback = "";
                $cancelled = "| <a href='javascript:void(0)' alt='cancelled' title='cancelled' onclick='transaction_cancelled(" . $maintid . ");return false;'><i class='icon-remove'></i></a>";

                if ($getparentroleid == 0) {
                    $rollback = "<img src='../../images/undo.png' alt='Rollback' title='Rollback' onclick='transaction_rollback(" . $maintid . ");return false;'></a></img>";
                }
                $maintanance->edit = $maintanance->edit . " " . $cancelled . " " . $rollback;
                $maintanance->invno = $row['invnohist'];
                $maintanances[] = $maintanance;
            }
            return $maintanances;
        }
        return null;
    }

    public function get_filtered_transaction($transid, $vehicleid, $categoryid, $statusid, $tyre, $parts, $dealerid, $Isapproval = null) {

        $roles = $this->getRoles();
        $vm = new VehicleManager($_SESSION['customerno']);
        $groups = $vm->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);
        $maintanances = Array();
        $transaction = str_split($transid, 3);
        $trans_substr = substr($transid, 3);
        $Queryadd = "";
        if ($transaction[0] != "B00" && $transaction[0] != "T00" && $transaction[0] != "R00" && $transaction[0] != "S00" && $transaction[0] != "" && $transaction[0] != "A00") {
            $trans_substr = substr($transid, 3);
        }
        if ($trans_substr != "") {
            $Queryadd = sprintf(" AND maintenance.id='%s' ", $trans_substr);
        }
        $vehicleQuery = "";
        if ($vehicleid != "" && $vehicleid != 1) {
            $vehicleQuery = sprintf(" AND maintenance.vehicleid=%d ", $vehicleid);
        }
        if ($vehicleid == 1) {
            $veharr = array();
            $vm = new VehicleManager($_SESSION['customerno']);
            $devices = $vm->get_approved_vehicles();
            if (!empty($devices)) {
                foreach ($devices as $device) {
                    $veharr[] = $device->vehicleid;
                }
                $vehstr = implode(",", $veharr);
                $vehicleQuery = sprintf(" AND maintenance.vehicleid IN(" . $vehstr . ")");
            }
        }
        $catQuery = "";
        if ($categoryid != '-1' && $categoryid != "00") {
            $catQuery = sprintf(" AND maintenance.category=%d ", $categoryid);
        }

        if ($categoryid == 00) {
            $catids = "0,1,2,3,4,5,6";
            $catQuery = sprintf(" AND maintenance.category IN (" . $catids . ")");
        }

        $statusQuery = "";
        if ($statusid != "" && $statusid != "1") {
            $statusQuery = sprintf(" AND maintenance.statusid=%d ", $statusid);
        }
        // to check if filter is for approval
        if ($statusid != "" && $statusid == "1") {
            $statusQuery = sprintf(" AND maintenance.statusid IN (7,10,11)");
        }

        if ($statusid != "" && $statusid == "0") {
            $statusQuery = sprintf(" AND maintenance.statusid IN (0,7,8,9,10,11,12,13,14)");
        }

        $tyreQuery = "";
        $partsQuery = "";
        if ($tyre != '0') {
            $partsQuery = "";
            $tyreQuery = sprintf(" AND maintenance.tyre_type LIKE '%s'", $tyre);
        }

        if ($parts != '-1') {
            $tyreQuery = "";
            $partsQuery = sprintf(" AND maintenance_parts.mid = maintenance.id ");
        }
        $dealerQuery = "";
        if ($dealerid != "" && $dealerid != '-1' && $dealerid != '1') {
            $dealerQuery = sprintf(" AND maintenance.dealer_id =%d ", $dealerid);
        }
        if ($dealerid == '-1') {
            $dm = new DealerManager($_SESSION['customerno']);
            $alldealer = $dm->get_all_dealers();
            if (isset($alldealer)) {
                $dealerarr = array();
                foreach ($alldealer as $alldealers) {
                    $dealerarr[] = $alldealers->dealerid;
                }
                $dealerstr = implode(",", $dealerarr);
                $dealerQuery = sprintf(" AND maintenance.dealer_id IN (" . $dealerstr . ")");
            }
        }

        $Query = "SELECT distinct maintenance.statusid
            , vehicle.vehicleid
            , user.realname
            , maintenance.submission_date
            , maintenance.timestamp
            , group.groupname
            , maintenance.id
            , maintenance.roleid
            , maintenance.category
            , vehicle.vehicleno
            , maintenance.invoice_amount
            , maintenance_status.name
            , maintenance.amount_quote
            , maintenance.invoice_no as invno
            , dealer.name as dname
            , role.id as approverRoleId
            , role.role as approverRoleName
            , maintenance.invoice_date
            , maintenance.vehicle_out_date
            , maintenance.meter_reading
            from maintenance
            inner join vehicle on maintenance.vehicleid=vehicle.vehicleid
            left join dealer on dealer.dealerid=maintenance.dealer_id and dealer.isdeleted=0 ";
        if ($categoryid == '2' && $parts != '-1') {
            $Query .="Inner Join maintenance_parts on maintenance_parts.partid = $parts ";
        }

        $Query .="  LEFT OUTER JOIN `maintenance_status` ON `maintenance`.statusid = `maintenance_status`.id
                    LEFT OUTER JOIN  `user`  ON `user`.userid = `maintenance`.userid
                    LEFT OUTER JOIN  `role`  ON `role`.id = maintenance.roleid
                    LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid";
        $Query .= " WHERE maintenance.customerno =%d AND maintenance.isdeleted=0 AND maintenance.is_cancelled=0";
        $Query.= $Queryadd;
        $Query.= $vehicleQuery;
        $Query.= $catQuery;
        $Query.= $statusQuery;
        $Query.= $dealerQuery;
        if ($categoryid == '1') {
            $Query.= $tyreQuery;
        }
        if ($categoryid == '2') {
            $Query.= $partsQuery;
        }
        if ($transaction[0] == "B00")
            $Query.=" AND maintenance.category = 0 ";
        if ($transaction[0] == "T00")
            $Query.=" AND maintenance.category = 1 ";
        if ($transaction[0] == "R00")
            $Query.=" AND maintenance.category = 2 ";
        if ($transaction[0] == "S00")
            $Query.=" AND maintenance.category = 3 ";
        if ($transaction[0] == "A00")
            $Query.=" AND maintenance.category = 5 ";
        if ($_SESSION['groupid'] != 0) {
            $Query.=" AND vehicle.groupid =%d";
        } else {
            if ($groups[0] != 0) {
                $groupid_ids = implode(',', $groups);
                $Query.=" AND vehicle.groupid in (" . $groupid_ids . ") ";
            }
        }

        if ($_SESSION['groupid'] != 0) {
            $maintananceQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $maintananceQuery = sprintf($Query, $this->_Customerno);
        }
        $maintananceQuery.=" ORDER BY maintenance.timestamp DESC";

        //$maintananceQuery = sprintf($Query, $this->_Customerno,$_SESSION['branchid']);
        //echo $maintananceQuery; die();
        $this->_databaseManager->executeQuery($maintananceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $maintanance = new VOMaintanance();
                $maintanance->id = $row['id'];
                $maintanance->vehicleid = $row['vehicleid'];
                $maintanance->roleid = $row["approverRoleId"];
                $tempRole = $this->get_session_role($row["approverRoleId"]);
                $maintanance->role = ($tempRole == '') ? $row['approverRoleName'] : $tempRole;
                $maintanance->vehicleno = $row['vehicleno'];
                $maintanance->dname = $row['dname'];
                $maintanance->statusname = $row['name'];
                $maintanance->sender = $row['realname'];
                $maintanance->statusid = $row['statusid'];
                $maintanance->invoice_amount = $row['invoice_amount'];
                $maintanance->quote_amount = $row['amount_quote'];
                $maintanance->group = $row['groupname'];
                $maintanance->invno = $row['invno'];
                $maintanance->meter_reading = $row['meter_reading'];
                if ($row['invoice_date'] == '0000-00-00' || $row['invoice_date'] == '1970-01-01' || $row['invoice_date'] == '') {
                    $maintanance->invoice_date = '';
                } else {
                    $maintanance->invoice_date = date('d-m-Y', strtotime($row['invoice_date']));
                }

                if ($row['vehicle_out_date'] == '0000-00-00' || $row['vehicle_out_date'] == '1970-01-01' || $row['vehicle_out_date'] == '') {
                    $maintanance->vehicle_out_date = '';
                } else {
                    $maintanance->vehicle_out_date = date('d-m-Y', strtotime($row['invoice_date']));
                }


                if ($row['category'] == '0') {
                    $maintanance->category = "Battery";
                    $maintanance->trans = "B00" . $row["id"];
                }
                if ($row['category'] == '1') {
                    $maintanance->category = "Tyre";
                    $maintanance->trans = "T00" . $row["id"];
                }
                if ($row['category'] == '2') {
                    $maintanance->category = "Repair";
                    $maintanance->trans = "R00" . $row["id"];
                }
                if ($row['category'] == '3') {
                    $maintanance->category = "Service";
                    $maintanance->trans = "S00" . $row["id"];
                }
                if ($row['category'] == '5') {
                    $maintanance->category = "Accessory";
                    $maintanance->trans = "A00" . $row["id"];
                }
                $maintanance->timestamp = date("d-m-Y", strtotime($row["timestamp"]));
                $maintanance->submit_date = date("d-m-Y", strtotime($row["submission_date"]));

                $maintanance->chk_box = '';
                if (isset($row["approverRoleId"])) {
                    $val_chk = $maintanance->id . "-" . $maintanance->statusid . "-TRANS-" . $maintanance->invoice_amount;
                    if ($_SESSION['roleid'] == $roles['accountRoleId'] || $_SESSION['roleid'] == $roles['masterRoleId'] || $_SESSION['roleid'] == $roles['elixirRoleId']) {
                        //$maintanance->chk_box = $val_chk;
                        if ($maintanance->statusid == 13) {
                            $maintanance->chk_box = "<input type='checkbox' class='call-checkbox' value='" . $val_chk . "'/>";
                        }
                    }
                }

                if ($Isapproval != null) {
                    $edit = "<a href='approvals.php?id=4&tid=$maintanance->id' ><i class='icon-pencil'></i></a>";
                    if ($_SESSION['roleid'] == $maintanance->roleid || $_SESSION['roleid'] == $roles['masterRoleId']) {
                        $maintanance->edit = $edit;
                    } else {
                        $maintanance->edit = '';
                    }
                    $maintanance->approval_chkdata = $maintanance->id . "-" . $maintanance->statusid . "-TRANS-" . $maintanance->invoice_amount;
                } else {
                    $arrRolesWithAmendmentRights = array($roles['elixirRoleId'], $roles['masterRoleId'], $roles['stateRoleId'], $roles['zoneRoleId'], $roles['regionRoleId']);
                    if (($maintanance->statusid == '13') && $_SESSION['customerno'] == 118 && $_SESSION['roleid'] == $roles['accountRoleId']) {
                        $maintanance->edit = "<a href='transaction.php?id=3&accid=$maintanance->id&vehicleid=$maintanance->vehicleid'><i class='icon-pencil'></i></a> | ";
                    } else if ($maintanance->statusid == '7' || $maintanance->statusid == '9' || $maintanance->statusid == '10' || $maintanance->statusid == '11' || $maintanance->statusid == '6') {
                        $maintanance->edit = "<a href='transaction.php?id=5&accid=$maintanance->id&vehicleid=$maintanance->vehicleid' ><img src='../../images/view.png'></img></td></a> | ";
                    } elseif ($maintanance->statusid == '14') {
                        $maintanance->edit = "<a href='transaction.php?id=5&accid=$maintanance->id&vehicleid=$maintanance->vehicleid' ><img src='../../images/view.png'></img></td></a> |
      <a href='javascript:void(0);' onclick='print_battery_closed($maintanance->id,$maintanance->vehicleid)'><img src='../../images/print.png'></img></a> | ";
                    } elseif (in_array($_SESSION['roleid'], $arrRolesWithAmendmentRights)) {
                        $delete_trans = "";
                        if ($maintanance->statusid == '13') {
                            $delete_trans = "| <a a onClick=\"javascript: return confirm('Please confirm deletion');\"href='route_ajax.php?delmainid=$maintanance->id' ><i class='icon-trash'></i>";
                        }
                        $maintanance->edit = "<a href='transaction.php?id=3&accid=$maintanance->id&vehicleid=$maintanance->vehicleid'><i class='icon-pencil'></i></a>" . $delete_trans;
                    } else if ($_SESSION['roleid'] != $roles['accountRoleId']) {
                        $maintanance->edit = "<a href='transaction.php?id=3&accid=$maintanance->id&vehicleid=$maintanance->vehicleid' ><i class='icon-pencil'></i></a> | ";
                    } else {
                        $maintanance->edit = "<a href='transaction.php?id=3&accid=$maintanance->id&vehicleid=$maintanance->vehicleid' ></i></td></a> | ";
                    }
                }
                $maintid = $maintanance->id;
                $cancelled = " <a href='javascript:void(0)' alt='cancelled' title='cancelled' onclick='transaction_cancelled(" . $maintid . ");return false;'><i class='icon-remove'></i></a>";
                $maintanance->edit = $maintanance->edit . " " . $cancelled;
                $maintanances[] = $maintanance;
            }
            return $maintanances;
        }
        return null;
    }

    public function get_fuel_details($fid) {
        $Query = "SELECT fs.fuel,fs.notes,fs.ofasnumber, fs.addedon, fs.amount,fs.additional_amount, fs.rate, fs.refno, fs.openingkm, fs.endingkm, fs.average, fs.dealerid, fs.vehicleid, fs.perdaykm , fs.refilldate from fuelstorrage as fs ";
        $Query .= " WHERE fs.customerno=$this->_Customerno and fs.fuelid=$fid";

        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $maintanance = new stdClass();

                $maintanance->fuel = $row['fuel'];
                $maintanance->notes = $row['notes'];
                $maintanance->ofasnumber = $row['ofasnumber'];
                $maintanance->added_date = date('d-m-Y', strtotime($row['addedon']));
                $maintanance->added_time = date('h:i', strtotime($row['addedon']));
                $maintanance->amount = $row['amount'];
                $maintanance->additional_amount = $row['additional_amount'];
                $maintanance->rate = $row['rate'];
                $maintanance->refno = $row['refno'];
                $maintanance->openingkm = $row['openingkm'];
                $maintanance->endingkm = $row['endingkm'];
                $maintanance->average = $row['average'];
                $maintanance->dealerid = $row['dealerid'];
                $maintanance->vehicleid = $row['vehicleid'];
                $maintanance->perdaykm = $row['perdaykm'];
                $maintanance->refilldate = date("d-m-Y", strtotime($row['refilldate']));
            }
            return $maintanance;
        }
        return null;
    }

    public function get_all_fuels() {
        $roles = $this->getRoles();
        $vm = new VehicleManager($_SESSION['customerno']);
        $groups = $vm->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);
        $maintanances = Array();
        $Query = "SELECT fs.fuelid as id
                        , fs.vehicleid
                        , fs.addedon
                        , fs.refno
                        , fs.timestamp
                        , fs.amount
                        , vehicle.vehicleno
                        , dealer.name as dname
                        , user.realname
                        , group.groupname
                        , user.roleid
                        , role.role as rolename
            FROM        fuelstorrage as fs
            INNER JOIN  vehicle on fs.vehicleid=vehicle.vehicleid
            LEFT JOIN   dealer on dealer.dealerid=fs.dealerid and dealer.isdeleted=0
            LEFT OUTER JOIN  `user`  ON `user`.userid = `fs`.userid
            LEFT OUTER JOIN `role` ON `role`.id = `user`.roleid
            LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid  ";

        $where = " WHERE fs.customerno=$this->_Customerno AND fs.isclosed=0 AND fs.isdeleted=0 ";
        if ($groups[0] != 0) {
            $groupid_ids = implode(',', $groups);
            $where.=" AND vehicle.groupid in (" . $groupid_ids . ") ";
        }

        $wq = $this->role_based_join();
        $where .= $wq['w'];
        $Query .= $wq['q'];

        $maintananceQuery = "$Query $where ORDER BY fs.timestamp DESC";
        $this->_databaseManager->executeQuery($maintananceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $maintanance = new VOMaintanance();
                $maintanance->id = $row['id'];
                $maintanance->trans = "FU00" . $row['id'];
                $maintanance->vehicleid = $row['vehicleid'];
                $maintanance->vehicleno = $row['vehicleno'];
                $maintanance->dname = $row['dname'];
                $maintanance->invoice_amount = $row['amount'];
                $maintanance->roleid = $row['roleid'];
                $maintanance->role = 'N/A';
                $maintanance->statusname = 'N/A';
                $maintanance->statusid = '0';
                $maintanance->category = "Fuel";
                $maintanance->sender = $row['realname'];
                $maintanance->group = $row['groupname'];
                $maintanance->timestamp = date("d-m-Y", strtotime($row["timestamp"]));
                $maintanance->submit_date = date("d-m-Y", strtotime($row["addedon"]));
                $maintanance->invno = $row['refno'];
                $maintanance->quote_amount = "N/A";
                $maintanance->invoice_date = "N/A";
                $maintanance->vehicle_out_date = "N/A";
                $maintanance->meter_reading = "N/A";

                $maintanance->chk_box = '';
                if (isset($row["roleid"])) {
                    $val_chk = $maintanance->id . "-" . $maintanance->statusid . "-FUEL-" . $maintanance->invoice_amount;
                    if ($_SESSION["roleid"] == $roles['accountRoleId'] || $_SESSION["roleid"] == $roles['masterRoleId'] || $_SESSION["roleid"] == $roles['elixirRoleId']) {
                        //$maintanance->chk_box = $val_chk;
                        $maintanance->chk_box = "<input type='checkbox' class='call-checkbox' value='" . $val_chk . "'/>";
                    }
                }

                if ($_SESSION["roleid"] == $roles['masterRoleId'] || $_SESSION["roleid"] == $roles['stateRoleId'] || $_SESSION["roleid"] == $roles['elixirRoleId']) {
                    $maintanance->edit = "<td><a href='transaction.php?id=7&fuelid=$maintanance->id' ><i class='icon-pencil'></i></td> |
    <a a onClick=\"javascript: return confirm('Please confirm deletion');\"href='route_ajax.php?delfuelid=$maintanance->id' ><i class='icon-trash'></i></td>";
                } else {
                    $maintanance->edit = "";
                }
                //$maintid =$maintanance->id;
                //$cancelled = "| <a href='javascript:void(0)' alt='cancelled' title='cancelled' onclick='transaction_cancelled(".$maintid.");return false;'><i class='icon-remove'></i></a>";
                //$maintanance->edit = $maintanance->edit." ".$cancelled;
                $maintanances[] = $maintanance;
            }
            return $maintanances;
        }
        return null;
    }

    public function get_filtered_fuels($transid, $vehicleid, $dealerid) {
        $roles = $this->getRoles();
        $vm = new VehicleManager($_SESSION['customerno']);
        $groups = $vm->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);
        $maintanances = Array();
        $Query = "SELECT fs.rate
                    , fs.openingkm
                    , fs.endingkm
                    , fs.average
                    , fs.amount
                    , fs.refno
                    , fs.fuelid as id
                    , fs.fuel
                    , fs.amount
                    , fs.vehicleid
                    , fs.addedon
                    , fs.timestamp
                    , vehicle.vehicleno
                    , dealer.name as dname
                    , user.realname
                    , group.groupname
                    , user.roleid
                    , role.role as rolename
                from fuelstorrage as fs
            inner join vehicle on fs.vehicleid=vehicle.vehicleid
            left join dealer on dealer.dealerid=fs.dealerid and dealer.isdeleted=0
            LEFT OUTER JOIN  `user`  ON `user`.userid = `fs`.userid
            LEFT OUTER JOIN  `role`  ON `role`.id = `user`.roleid
            LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid  ";
        $where = " WHERE fs.customerno=$this->_Customerno AND fs.isclosed=0  AND fs.isdeleted=0";
        if ($groups[0] != 0) {
            $groupid_ids = implode(',', $groups);
            $where.=" AND vehicle.groupid in (" . $groupid_ids . ") ";
        }
        $wq = $this->role_based_join();
        $where .= $wq['w'];
        $Query .= $wq['q'];

        if ($transid != '') {
            $tid_arr = str_split($transid, 4);
            if (strtolower($tid_arr[0]) == 'fu00' && isset($tid_arr[1])) {
                $tid = (int) $tid_arr[1];
                $where .= " and fs.fuelid=$tid ";
            } else {
                $where .= " and fs.fuelid=0 "; // this done to avoid fuel transaction shown for other category transactionid
            }
        }
        if ($vehicleid != '') {
            $vid = (int) $vehicleid;
            $where .= " and fs.vehicleid=$vid ";
        }
        //check dealer
        if ($dealerid != '' && $dealerid != '-1') {
            $delid = (int) $dealerid;
            $where .= " and fs.dealerid=$delid ";
        }
        $maintananceQuery = "$Query $where ORDER BY fs.timestamp DESC";
        //echo $maintananceQuery;
        $this->_databaseManager->executeQuery($maintananceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $maintanance = new VOMaintanance();
                $maintanance->id = $row['id'];
                $maintanance->trans = "FU00" . $row['id'];
                $maintanance->vehicleid = $row['vehicleid'];
                $maintanance->vehicleno = $row['vehicleno'];
                $maintanance->dname = $row['dname'];
                $maintanance->invoice_amount = $row['amount'];
                $maintanance->roleid = $row['roleid'];
                $maintanance->role = 'N/A';
                $maintanance->fuel = $row['fuel'];
                $maintanance->amount = $row['amount'];
                $maintanance->rate = $row['rate'];
                $maintanance->invno = $row['refno'];
                $maintanance->openingkm = $row['openingkm'];
                $maintanance->endingkm = $row['endingkm'];
                $maintanance->average = $row['average'];
                $maintanance->statusname = 'N/A';
                $maintanance->statusid = '0';
                $maintanance->category = "Fuel";
                $maintanance->sender = $row['realname'];
                $maintanance->group = $row['groupname'];
                $maintanance->timestamp = date("d-m-Y", strtotime($row["timestamp"]));
                $maintanance->submit_date = date("d-m-Y", strtotime($row["addedon"]));
                $maintanance->submit_datetime = $row["addedon"];
                $maintanance->quote_amount = "N/A";
                $maintanance->invoice_date = "N/A";
                $maintanance->vehicle_out_date = "N/A";
                $maintanance->meter_reading = "N/A";

                $maintanance->chk_box = '';
                if (isset($row["roleid"])) {
                    $val_chk = $maintanance->id . "-" . $maintanance->statusid . "-FUEL-" . $maintanance->invoice_amount;
                    if ($row["roleid"] == $roles['accountRoleId'] || $_SESSION["roleid"] == $roles['masterRoleId'] || $_SESSION["roleid"] == $roles['elixirRoleId']) {
                        //if ($maintanance->statusid == 13) {
                        $maintanance->chk_box = "<input type='checkbox' class='call-checkbox' value='" . $val_chk . "'/>";
                        //}
                    }
                }

                if ($_SESSION["roleid"] == $roles['masterRoleId'] || $_SESSION["roleid"] == $roles['stateRoleId'] || $_SESSION["roleid"] == $roles['elixirRoleId']) {
                    $maintanance->edit = "<td><a href='transaction.php?id=7&fuelid=$maintanance->id' ><i class='icon-pencil'></i></td> |
    <a a onClick=\"javascript: return confirm('Please confirm deletion');\"href='route_ajax.php?delfuelid=$maintanance->id' ><i class='icon-trash'></i></td>";
                } else {
                    $maintanance->edit = "";
                }
                //$maintid =$maintanance->id;
                //$cancelled = "| <a href='javascript:void(0)' alt='cancelled' title='cancelled' onclick='transaction_cancelled(".$maintid.");return false;'><i class='icon-remove'></i></a>";
                //$maintanance->edit =  $maintanance->edit." ".$cancelled;
                $maintanances[] = $maintanance;
            }
            return $maintanances;
        }
        return null;
    }

    public function role_based_join() {
        $Query = '';
        $where = '';
        /*
          if ($_SESSION['roleid'] == '2') {
          $Query .=" LEFT OUTER JOIN city on city.cityid = group.cityid
          LEFT OUTER JOIN district on district.districtid = city.districtid
          LEFT OUTER JOIN state on state.stateid = district.stateid";
          $where .= " and state.stateid = " . $_SESSION['heirarchy_id'] . " ";
          } else if ($_SESSION['roleid'] == '3') {
          $Query .=" LEFT OUTER JOIN city on city.cityid = group.cityid
          LEFT OUTER JOIN district on district.districtid = city.districtid ";
          $where .= " and district.districtid = " . $_SESSION['heirarchy_id'] . " ";
          } else if ($_SESSION['roleid'] == '4') {
          $Query .=" LEFT OUTER JOIN city on city.cityid = group.cityid";
          $where .= " and city.cityid = " . $_SESSION['heirarchy_id'] . " ";
          }
         */
        if ($_SESSION['groupid'] != 0) {
            $where .= " AND vehicle.groupid={$_SESSION['groupid']} ";
        }
        return array('q' => $Query, 'w' => $where);
    }

    public function getRoles() {
        $elixirRoleId = 1;
        $masterRoleId = 1;
        $stateRoleId = 2;
        $zoneRoleId = 3;
        $regionRoleId = 4;
        $groupRoleId = 5;
        switch ($_SESSION['customerno']) {
            case 63:
                $masterRoleId = 28;
                $zoneRoleId = 30;
                $regionRoleId = 31;
                break;
            case 64:
                $masterRoleId = 33;
                $zoneRoleId = 35;
                $regionRoleId = 36;
                $accountRoleId = '';
                break;
            case 118:
                $masterRoleId = 18;
                $stateRoleId = 19;
                $zoneRoleId = 20;
                $regionRoleId = 21;
                $groupRoleId = 22;
                $accountRoleId = 42;
                $coExecutiveId = 23;
                break;
            case 167:
                $masterRoleId = 24;
                $zoneRoleId = 26;
                $regionRoleId = 27;
                break;
            default:
                $masterRoleId = 1;
                $zoneRoleId = 3;
                $regionRoleId = 4;
                break;
        }
        $roles = compact("elixirRoleId", "masterRoleId", "stateRoleId", "zoneRoleId", "regionRoleId", "groupRoleId", "accountRoleId", "coExecutiveId");
        return $roles;
    }

    public function maintenance_percent($vehicleid, $quotation_amount, $mainid = null, $accidentid = null) {

        if ($mainid == null) {
            $mainid = 0;
        }
        if ($accidentid == null) {
            $accidentid = 0;
        }
        // type 0 for battery
        $roleid = 0;
        $invoice_total = 0;
        $quotation_total = 0;
        $cost_of_capitalisation = 0;
        $mahindra_amount = 0;
        $additional_amount = 0;
        $last_maintanance = "";

        // invoice total
        $Query = "SELECT sum(invoice_amount) as invoice_amount_total
            FROM maintenance WHERE maintenance.customerno = %d AND vehicleid=" . $vehicleid . "
           and statusid IN (13,14) AND isdeleted = 0 group by vehicleid ";
        $vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $invoice_total = $row['invoice_amount_total'];
            }
        }

        // additional amount total
        $Query = "SELECT additional_amount
            FROM vehicle WHERE customerno = %d AND vehicleid=" . $vehicleid . " AND isdeleted = 0 ";
        $vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $additional_amount = $row['additional_amount'];
            }
        }

        // Quotation total
        $Query = "SELECT sum(amount_quote) as quotation_amount_total
            FROM maintenance WHERE maintenance.customerno = %d AND vehicleid=" . $vehicleid . "
           and statusid IN (8,10) AND isdeleted = 0 AND id <> %d group by vehicleid ";
        $vehiclesQuery = sprintf($Query, $this->_Customerno, $mainid);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $quotation_total = $row['quotation_amount_total'];
            }
        }

        // mahindra_amount total
        $Query = "SELECT sum(mahindra_amount) as mahindra_amount_total
            FROM accident WHERE accident.customerno = %d AND vehicleid=" . $vehicleid . "
           and statusid IN (13,14) AND isdeleted = 0 group by vehicleid ";
        $vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $mahindra_amount = $row['mahindra_amount_total'];
            }
        }

        // cost of capitalisation
        $Query = "SELECT cost as cost_of_capitalisation FROM capitalization WHERE capitalization.customerno = %d AND vehicleid=" . $vehicleid . " ";
        $vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $cost_of_capitalisation = $row['cost_of_capitalisation'];
            }
        }

        if ($cost_of_capitalisation != '' && $cost_of_capitalisation != 0) {
            $maintenance_percent = ((($invoice_total + $mahindra_amount + $quotation_total + $additional_amount) / $cost_of_capitalisation) * 100);
            $expected_growth = (($invoice_total + $quotation_amount + $mahindra_amount + $quotation_total + $additional_amount) / $cost_of_capitalisation) * 100;
        } else {
            $maintenance_percent = 0;
            $expected_growth = 0;
        }

        $ret_arr = array();
        $ret_arr['cost_of_capitalisation'] = number_format($maintenance_percent, 2) . " %";
        $ret_arr['expected_growth'] = number_format($expected_growth, 2) . " %";
        return $ret_arr;
    }

    public function getparts($parts) {
        $partsnew = Array();
        if (isset($parts)) {
            foreach ($parts as $thispartid) {
                $Query = "SELECT part_name FROM `parts` where id=%d";
                $vehiclesQuery = sprintf($Query, Sanitise::Long($thispartid));
                $this->_databaseManager->executeQuery($vehiclesQuery);
                if ($this->_databaseManager->get_rowCount() > 0) {
                    while ($row = $this->_databaseManager->get_nextRow()) {
                        $partsnew[] = $row['part_name'];
                    }
                }
            }
            return $partsnew;
        }
        return "";
    }

    public function gettasks($parts) {
        $partsnew = Array();
        if (isset($parts)) {
            foreach ($parts as $thispartid) {
                $Query = "SELECT task_name FROM `task` where id=%d";
                $vehiclesQuery = sprintf($Query, Sanitise::Long($thispartid));
                $this->_databaseManager->executeQuery($vehiclesQuery);
                if ($this->_databaseManager->get_rowCount() > 0) {
                    while ($row = $this->_databaseManager->get_nextRow()) {
                        $partsnew[] = $row['task_name'];
                    }
                }
            }
            return $partsnew;
        }
        return "";
    }

    public function getPartsList($mainid) {
        $partsnew = Array();
        $Query = "select maintenance_parts.partid,maintenance_parts.m_id,maintenance_parts.qty,maintenance_parts.amount,maintenance_parts.discount,maintenance_parts.total,parts.part_name from maintenance_parts
inner join parts on parts.id = maintenance_parts.partid
where maintenance_parts.mid = %d and maintenance_parts.flag=1;";
        $SQL = sprintf($Query, $mainid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->partid = $row['partid'];
                $vehicle->qty = $row['qty'];
                $vehicle->amount = $row['amount'];
                $vehicle->total = $row['total'];
                $vehicle->part_name = $row['part_name'];
                $vehicle->discount = $row['discount'];
                $vehicle->mid = $row['m_id'];
                $partsnew[] = $vehicle;
            }
            return $partsnew;
        }
        return null;
    }

    public function getTasksList($mainid) {
        $partsnew = Array();
        $Query = "select maintenance_tasks.m_id,maintenance_tasks.partid,maintenance_tasks.discount,maintenance_tasks.qty,maintenance_tasks.amount,maintenance_tasks.total,task.task_name from maintenance_tasks
inner join task on task.id = maintenance_tasks.partid
where maintenance_tasks.mid = %d and maintenance_tasks.flag=2;";
        $SQL = sprintf($Query, $mainid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->partid = $row['partid'];
                $vehicle->qty = $row['qty'];
                $vehicle->amount = $row['amount'];
                $vehicle->total = $row['total'];
                $vehicle->part_name = $row['task_name'];
                $vehicle->discount = $row['discount'];
                $vehicle->taskid = $row['m_id'];
                $partsnew[] = $vehicle;
            }
            return $partsnew;
        }
        return null;
    }

    public function get_trans_status() {
        $vehicles = Array();
        $Query = "SELECT * FROM maintenance_status";
        $Query .= " WHERE maintenance_status.id IN (7,8,9,10,11,12,13)";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->statusid = $row['id'];
                $vehicle->name = $row['name'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_trans_approval_status() {
        $vehicles = Array();
        $Query = "SELECT * FROM maintenance_status";
        $Query .= " WHERE maintenance_status.id IN (7,10,11)";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->statusid = $row['id'];
                $vehicle->name = $row['name'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_approval_data_all_by_mainid($main_id) {
        $datas = array();
        $Query = "SELECT *,dealer.dealerid,maintenance.invoice_amount,maintenance.statusid,vehicle.vehicleid,maintenance.notes as mainnotes, maintenance.id AS transid, maintenance_status.name as statusname, maintenance.submission_date as submission,mp.repairtype,mt.tyrerepairid from maintenance
            INNER JOIN vehicle ON maintenance.vehicleid=vehicle.vehicleid
            LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
            INNER JOIN " . DB_PARENT . ".role ON maintenance.roleid= role.id
            INNER JOIN maintenance_status ON maintenance_status.id = maintenance.statusid
            LEFT OUTER JOIN dealer ON maintenance.dealer_id= dealer.dealerid
            LEFT OUTER JOIN maintenance_tyre_repair_mapping as mt ON maintenance.id=mt.maintenanceid
            LEFT OUTER JOIN maintenance_tyre_repair_type as mp ON mt.tyrerepairid = mp.tyrerepairid";
        $Query .= " WHERE maintenance.customerno =%d AND  maintenance.id=%d AND maintenance.isdeleted=0";

        $maintananceQuery = sprintf($Query, $this->_Customerno, $main_id);
        $this->_databaseManager->executeQuery($maintananceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $data = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $data = new stdClass();
                $data->vehicleid = $row["vehicleid"];
                $data->category = $row["category"];
                $data->transid = $row["transid"];
                $data->tyrerepairid = $row["tyrerepairid"];
                $data->dealerid = $row["dealerid"];
                $data->transnotes = $row["mainnotes"];
                $data->meter_reading = $row["meter_reading"];
                $data->tax = $row["tax"];
                $data->statusid = $row["statusid"];
                $data->invoice_amount = $row["invoice_amount"];
                $data->qtnamt = $row["amount_quote"];
                $datas[] = $data;
            }
        }
        return $datas;
    }

    public function get_approval_form_by_vehicleid($main_id){
        $maintanances = Array();
        $vehicleid = 0;
        $category = 0;
        $Query = "SELECT *,dealer.dealerid,vehicle.vehicleid,maintenance.invoice_amount,maintenance.notes as mainnotes, maintenance.id AS transid, maintenance_status.name as statusname, maintenance.submission_date as submission,mp.repairtype,mt.tyrerepairid from maintenance
            INNER JOIN vehicle ON maintenance.vehicleid=vehicle.vehicleid
            LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
            INNER JOIN " . DB_PARENT . ".role ON maintenance.roleid= role.id
            INNER JOIN maintenance_status ON maintenance_status.id = maintenance.statusid
            LEFT OUTER JOIN dealer ON maintenance.dealer_id= dealer.dealerid
            LEFT OUTER JOIN maintenance_tyre_repair_mapping as mt ON maintenance.id=mt.maintenanceid
            LEFT OUTER JOIN maintenance_tyre_repair_type as mp ON mt.tyrerepairid = mp.tyrerepairid";
        $Query .= " WHERE maintenance.customerno =%d AND  maintenance.id=%d AND maintenance.isdeleted=0";

        $maintananceQuery = sprintf($Query, $this->_Customerno, $main_id);
        $this->_databaseManager->executeQuery($maintananceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicleid = $row["vehicleid"];
                $category = $row["category"];
                $url = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $row['vehicleid'] . "/";
                if ($row['category'] == '0') {
                    $cat = "Battery";
                }
                if ($row['category'] == 1) {
                    $cat = "Tyre";
                }
                if ($row['category'] == 2) {
                    $cat = "Repair";
                }
                if ($row['category'] == 3) {
                    $cat = "Service";
                }
                if ($row['category'] == 5) {
                    $cat = "Accessory";
                }
                $catch_arr = $this->maintenance_percent($row['vehicleid'], $row['amount_quote'], $row["transid"], null);
                ?>
                <div class="table" style="width:51%;">
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="active"><a href="#transaction_current" data-toggle="tab">transaction</a></li>
                        <li><a href="#transaction_history" data-toggle="tab">history</a></li>
                        <li><a href="#vehicle_history" data-toggle="tab">Vehicle History</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="transaction_current">
                            <div>
                                <table class="table table-bordered table-striped">
                                    <th colspan="2">Vehicle Details 

                                        <span style="float: right; cursor: pointer;" data-toggle="modal" href="#vehicledetailsedit"><i class="icon-pencil"></i></span> 
                                    </th>
                                    <tr>
                                        <td width="50%">Vehicle No.</td><td><?php echo $row['vehicleno'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Branch</td><td><?php echo $row['groupname']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>GPS Odometer Reading</td><td><?php echo $row['odometer']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Vehicle Meter reading </td><td><?php echo $row['meter_reading']; ?></td>
                                    </tr>
                                </table>

                                <table class="table table-bordered table-striped">
                                    <th colspan="2">Transaction Details  <span style="float: right; cursor: pointer;" data-toggle="modal" href="#transdetailsedit"><i class="icon-pencil"></i></span></th>
                                    <tr>
                                        <td width="50%">Transaction ID</td><td><?php
                if ($row['category'] == '0') {
                    echo "B00" . $row['transid'];
                } elseif ($row['category'] == '1') {
                    echo "T00" . $row['transid'];
                } elseif ($row['category'] == '2') {
                    echo "R00" . $row['transid'];
                } elseif ($row['category'] == '3') {
                    echo "S00" . $row['transid'];
                } elseif ($row['category'] == '5') {
                    echo "A00" . $row['transid'];
                }
                ?></td>
                                    </tr>
                                    <tr>
                                        <td>Category</td><td><?php echo $cat; ?></td>
                                    </tr>

                <?php
                if ($_SESSION['customerno'] == 118 && $row['category'] == '0') {// for battery srno
                    ?>
                                        <tr>
                                            <td>New Battery Serial No.</td><td><div id="batt_srno"><?php echo $row['battery_srno']; ?> <?php if ($row['category'] == 0) { ?> <span style="float: right; cursor: pointer;" data-toggle="modal" href="#editBatteryDetails"><i class="icon-pencil"></i></span><?php } ?> </div></td>
                                        </tr>
                    <?php
                }
                ?>
                                    <tr>
                                        <td>Dealer name </td><td><?php echo $row['name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Notes</td><td><?php echo $row['mainnotes']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Status</td><td><?php echo $row['statusname']; ?></td>
                                    </tr>
                <?php
                if (($row['statusid'] == '10' || $row['statusid'] == '11') && $row['category'] != '5') {
                    ?>                  <tr>
                                            <td>Vehicle In Date</td><td><?php echo(date("d-m-Y", strtotime($row['vehicle_in_date']))); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Vehicle Out Date</td><td><?php echo(date("d-m-Y", strtotime($row['vehicle_out_date']))); ?></td>
                                        </tr>
                    <?php
                }
                ?>
                                </table>

                                <table class="table table-bordered table-striped">
                                    <th colspan="2">Quotation Details <span style="float: right; cursor: pointer;" data-toggle="modal" href="#qtndetails"><i class="icon-pencil"></i></span></th>
                                    <tr>
                                        <td width="50%">Quotation Amount (INR)</td><td><?php echo $row['amount_quote']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Quotation File</td>
                                        <td>
                <?php if ($row['file_name'] == "") { ?>
                                                <?php
                                                echo "NA";
                                            } else {
                                                ?>
                                                <a target="_blank" href="<?php echo $url . $row['file_name']; ?>" ><?php echo $row['file_name']; ?></a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Quotation Submission Date</td><td><?php echo date('d-m-Y', strtotime($row['submission'])) ?></td>
                                    </tr>

                <?php
                if ($row['category'] == '1' && $_SESSION['customerno'] != 118) {
                    ?>
                                        <tr>
                                            <td>Tyre Type</td>
                                            <td><?php
                    //echo   $row['tyre_type'];

                    if ($row['tyre_type']) {

                        $tyre_type = explode(",", $row['tyre_type']);
                        if (isset($tyre_type)) {
                            foreach ($tyre_type as $thistype) {
                                if ($thistype == '1') {

                                    $battery_detail[] = "Right Front";
                                }
                                if ($thistype == '2') {

                                    $battery_detail[] = "Right Back";
                                }
                                if ($thistype == '3') {

                                    $battery_detail[] = "Left Front";
                                }
                                if ($thistype == '4') {

                                    $battery_detail[] = "Left Back";
                                }
                                if ($thistype == '5') {

                                    $battery_detail[] = "Stepney";
                                }
                            }
                        }
                    }
                    if (isset($battery_detail)) {
                        echo implode(", ", $battery_detail);
                    } else {
                        echo "";
                    }
                    ?></td>
                                        </tr>
                                            <?php } ?>

                                    <?php
                                    if ($row['category'] == '1' && $_SESSION['customerno'] == 118) {
                                        $tyre_repair = isset($row['repairtype']) ? $row['repairtype'] : '';
                                        $tyre_repairid = isset($row['tyrerepairid']) ? $row['tyrerepairid'] : '';
                                        ?>
                                        <tr>
                                            <td>Tyre Repair Type</td><td><?php echo $tyre_repair; ?></td>
                                        </tr>
                    <?php
                    $tofind = strpos($row['tyre_type'], '-'); //check format
                    if ($tofind === false) {// for old tyre replacement without Srno.
                        ?>
                                            <tr>
                                                <td>Tyre Type</td><td>
                        <?php
                        $tyre_type = explode(",", $row['tyre_type']);

                        if (isset($tyre_type)) {
                            foreach ($tyre_type as $thistype) {
                                if ($thistype == '1') {

                                    $battery_detail[] = "Right Front";
                                }
                                if ($thistype == '2') {

                                    $battery_detail[] = "Right Back";
                                }
                                if ($thistype == '3') {

                                    $battery_detail[] = "Left Front";
                                }
                                if ($thistype == '4') {

                                    $battery_detail[] = "Left Back";
                                }
                                if ($thistype == '5') {

                                    $battery_detail[] = "Stepney";
                                }
                            }
                        }

                        if (isset($battery_detail)) {
                            echo implode(", ", $battery_detail);
                        } else {
                            echo "";
                        }
                        ?></td>
                                            </tr>
                                                <?php }
                                                ?>
                                    </table>
                                        <?php
                                        if ($row['category'] == '1' && $_SESSION['customerno'] == 118) {
                                            $tyre_srno = explode(",", $row['tyre_type']);
                                            //print_r($tyre_srno);echo "<br>";die();
                                            ?>
                                        <table class="table table-bordered table-striped">
                                            <th colspan="2">Tyre Serial No. Details 

                        <?php if ($tyre_repairid == 1) { ?>
                                                    <span style="float: right;" data-toggle="modal" href="#editTyreDetails"><i class="icon-pencil"></i></span>
                                                <?php } ?>
                                            </th>

                        <?php
                        foreach ($tyre_srno as $tyre_srnos) {
                            ?>
                                                <tr>
                                                    <td width="50%"><?php echo $tyre_srnos; ?></td>
                                                </tr>
                            <?php
                        }
                        ?>
                                        </table>
                                            <?php
                                        }
                                    }
                                    ?>

                                <div style="clear:both;"></div>
                                <!--Multiple Approval Modal -->
                                <div class="modal hide" id="TyreModal" role="dialog" >
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Closed Transactions</h4>
                                            </div>
                                            <div class="modal-body">
                                                <span id="NoOfApproval" style="color: #FF0000;"></span>
                                                <span id="approvalerror_note" style="color: #FF0000;display: none;">Note cannot be empty</span>
                                                <span id="approvalerror_checkbox" style="color: #FF0000;display: none;">Please Tick A Checkbox</span>
                                                <input type="hidden" name ="check_approval" id="check_approval" value=""/>
                                                <div class="clear"></div>
                                                <div style="width:50%; float:left;">
                                                    <label style="font-weight: bold;">Slip</label>
                                                    <input type="text" id="ofasnumber1" name="ofasnumber1" value=""  onkeypress="return nospecialchars(event)" maxlength="50" placeholder="e.g. 125867" >
                                                </div>
                                                <div style="width:50%; float:left;">
                                                    <label style="font-weight: bold;">Cheque No</label>
                                                    <input type="text" id="chequeno" name="chequeno" value=""  onkeypress="return nospecialchars(event)" maxlength="50" placeholder="e.g.000555" >
                                                </div>
                                                <div class="clear"></div>
                                                <div style="width:50%; float:left;">
                                                    <label style="font-weight: bold;">Cheque Amount</label>
                                                    <input type="text" id="chequeamt" name="chequeamt" value=""  onkeypress="return nospecialchars(event)" maxlength="50" placeholder="e.g. 124567" >
                                                </div>
                                                <div style="width:50%; float:left;">
                                                    <label style="font-weight: bold;">Cheque Date</label>
                                                    <input type="text" id="chequedate" name="chequedate">
                                                </div>

                                                <div class="clear"></div>
                                                <div style="width:100%; float:left;">
                                                    <label style="font-weight: bold;">Tds Amount</label>
                                                    <input type="text" id="tdsamt" name="tdsamt" value=""  onkeypress="return nospecialchars(event)" maxlength="50" placeholder="e.g. 200">
                                                </div>
                                                <div class="clear"></div>
                                                <div style="width:100%; float:left;">
                                                    <label style="font-weight: bold">Note</label>
                                                    <textarea name="note" id="note" rows="3" cols="40"></textarea>
                                                </div>
                                                <div class="clear"></div>
                                                <br>
                                                <div style="width:100%; float:right;">
                                                    <input type="button" class="btn btn-primary" onclick="push_closed_transaction('1')" value="Close Transactions">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="clear"></div>
                                                <div>
                                                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                <?php
                if ($row['category'] == '2' || $row['category'] == '3') {
                    $parts = $this->getPartsList($main_id);
                    //print_r($parts);
                    ?>
                                    <table class="table table-bordered table-striped">
                                        <th colspan="6">Parts Consumed
                                            <span style="float: right; cursor: pointer;" data-toggle="modal" href="#addPartsbox" alt="Add Parts"><i class="icon-plus"></i></span>
                                        </th>
                                        <tr>
                                            <td>Part</td>
                                            <td>Quantity</td>
                                            <td>Cost Per Unit</td>
                                            <td>Disc Per Unit</td>
                                            <td>Total</td>
                                            <td>&nbsp;</td>
                                        </tr>
                    <?php
                    $partamt = array();
                    if (!empty($parts)) {
                        ?>
                                            <?php
                                            foreach ($parts as $part) {
                                                if ($part->part_name != '') {
                                                    $partamt[] = $part->total;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $part->part_name; ?></td>
                                                        <td><?php echo $part->qty; ?></td>
                                                        <td><?php echo $part->amount; ?></td>
                                                        <td><?php echo $part->discount; ?></td>
                                                        <td><?php echo $part->total; ?></td>
                                                        <td>
                                                            <span style="float: left; cursor: pointer;" data-toggle="modal" href="#editparts<?php echo $part->mid; ?>" alt="Edit Parts"><i class='icon-pencil'></i></span>
                                                            | <a onclick='return confirm("Are you sure you want to delete parts?");' href='../../modules/approvals/edit_apporval_ajax.php?action=deleteparts&id=<?php echo $_GET['id']; ?>&tid=<?php echo $_GET['tid']; ?>&partid=<?php echo $part->mid; ?>'><i class="icon-trash"></i></a>

                                                            <!-- Edit parts row wise start-->
                                                            <div class="modal hide" id="editparts<?php echo $part->mid; ?>" role="dialog">
                                                                <div class="modal-dialog modal-sm">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            <h4 class="modal-title">Edit Parts</h4>
                                                                            <div align="center" style="width:100%;">
                                                                                <div style="text-align: right; float:left; width:30%; color:#000;">Parts</div><div style="float:left; width:70%;"><select name="parts_select_<?php echo $part->mid; ?>" id="parts_select_<?php echo $part->mid; ?>" style="width:200px;"><option value="<?php echo $part->partid; ?>"><?php echo $part->part_name; ?></option></select></div>
                                                                                <div style="clear:both;"></div>
                                                                                <div style="text-align: right; float:left; width:30%; color:#000;">Quantity</div>
                                                                                <div style="float:left; width:70%;"><input type="text" style="width:200px;" onkeypress="return isNumber(event)"  onblur="calculatetotalpop_parts(<?php echo $part->mid; ?>)" name="parts_qty<?php echo $part->mid; ?>" id="parts_qty<?php echo $part->mid; ?>" value="<?php echo $part->qty; ?>"></div>
                                                                                <div style="clear:both;"></div>
                                                                                <div style=" text-align: right; float:left; width:30%; color:#000;">Cost Per Unit</div>
                                                                                <div style="float:left; width:70%;"><input type="text" style="width:200px;" onkeypress="return isNumber(event)"  onblur="calculatetotalpop_parts(<?php echo $part->mid; ?>)" name="parts_amount<?php echo $part->mid; ?>" id="parts_amount<?php echo $part->mid; ?>" value="<?php echo $part->amount; ?>"></div>
                                                                                <div style="clear:both;"></div>
                                                                                <div style="text-align: right; float:left; width:30%; color:#000;">Disc Per Unit</div>
                                                                                <div style="float:left; width:70%;"><input type="text" style="width:200px;" onblur="calculatetotalpop_parts(<?php echo $part->mid; ?>)"  onkeypress="return isNumber(event)" name="parts_discs<?php echo $part->mid; ?>" id="parts_discs<?php echo $part->mid; ?>" value="<?php echo $part->discount; ?>"></div>
                                                                                <div style="clear:both;"></div>
                                                                                <div style="text-align: right; float:left; width:30%; color:#000;">Total </div>
                                                                                <div style="float:left; width:70%;"><input type="text" style="width:200px;" name="parts_tot<?php echo $part->mid; ?>" id="parts_tot<?php echo $part->mid; ?>" disabled="disable" value="<?php echo $part->total; ?>"></div>
                                                                                <input type="hidden" name="pid<?php echo $part->mid; ?>" id="pid<?php echo $part->mid; ?>" value="<?php echo $part->mid; ?>">
                                                                                <input type="hidden" name="getid<?php echo $part->mid; ?>" id="getid<?php echo $part->mid; ?>" value="<?php echo $_GET['id']; ?>">
                                                                                <input type="hidden" name="tid<?php echo $part->mid; ?>" id="tid<?php echo $part->mid; ?>" value="<?php echo $_GET['tid']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-body" style="text-align: center;">
                                                                            <input type="button" name="tyreedit" value="Update Part" class="btn btn-primary" onclick="editpartspop(<?php echo $part->mid; ?>);"/>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <div class="clear"></div>
                                                                            <div>
                                                                                <button class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Edit parts row wise end-->  
                                                        </td>
                                                    </tr>
                                <?php
                            }
                        }
                        ?>
                                            <?php
                                        }
                                        ?>
                                    </table>             
                                        <?php
                                        $tasks = $this->getTasksList($main_id);
                                        ?>
                                    <table class="table table-bordered table-striped">
                                        <th colspan="6">Task Performed 
                                            <span style="float: right; cursor: pointer;" data-toggle="modal" href="#addTaskbox" alt="Add Task"><i class="icon-plus"></i></span>
                                        </th>
                                        <tr>
                                            <td>Task</td>
                                            <td>Quantity</td>
                                            <td>Cost Per Unit</td>
                                            <td>Disc Per Unit</td>
                                            <td>Total</td>
                                            <td>&nbsp;</td>
                                        </tr>
                    <?php
                    $taskamtarr = array();
                    if (!empty($tasks)) {
                        ?>
                                            <?php
                                            foreach ($tasks as $task) {
                                                if ($task->part_name != '') {
                                                    $taskamtarr[] = $task->total;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $task->part_name; ?></td>
                                                        <td><?php echo $task->qty; ?></td>
                                                        <td><?php echo $task->amount; ?></td>
                                                        <td><?php echo $task->discount; ?></td>
                                                        <td><?php echo $task->total; ?></td>
                                                        <td>
                                                            <span style="float: left; cursor: pointer;" data-toggle="modal" href="#edittasks<?php echo $task->taskid; ?>" alt="Edit Task"><i class='icon-pencil'></i></span> | <a onclick='return confirm("Are you sure you want to delete task?");' href='../../modules/approvals/edit_apporval_ajax.php?action=deletetasks&id=<?php echo $_GET['id']; ?>&tid=<?php echo $_GET['tid']; ?>&taskid=<?php echo $task->taskid; ?>'><i class='icon-trash'></i></a>
                                                            <!-- Edit parts row wise start-->
                                                            <div class="modal hide" id="edittasks<?php echo $task->taskid; ?>" role="dialog">
                                                                <div class="modal-dialog modal-sm">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            <h4 class="modal-title">Edit Tasks</h4>
                                                                            <div align="center" style="width:100%;">
                                                                                <div style="text-align: right; float:left; width:30%; color:#000;">Tasks</div><div style="float:left; width:70%;"><select name="tasks_select_<?php echo $task->taskid; ?>" id="tasks_select_<?php echo $task->taskid; ?>" style="width:200px;"><option value="<?php echo $task->partid; ?>"><?php echo $task->part_name; ?></option></select></div>
                                                                                <div style="clear:both;"></div>
                                                                                <div style="text-align: right; float:left; width:30%; color:#000;">Quantity</div>
                                                                                <div style="float:left; width:70%;"><input type="text" style="width:200px;" onkeypress="return isNumber(event)"  onblur="calculatetotalpop_tasks(<?php echo $task->taskid; ?>)"  name="tasks_qty<?php echo $task->taskid; ?>" id="tasks_qty<?php echo $task->taskid; ?>" value="<?php echo $task->qty; ?>"></div>
                                                                                <div style="clear:both;"></div>
                                                                                <div style=" text-align: right; float:left; width:30%; color:#000;">Cost Per Unit</div>
                                                                                <div style="float:left; width:70%;"><input type="text" style="width:200px;"  onkeypress="return isNumber(event)" name="tasks_amount<?php echo $task->taskid; ?>" id="tasks_amount<?php echo $task->taskid; ?>" value="<?php echo $task->amount; ?>"></div>
                                                                                <div style="clear:both;"></div>
                                                                                <div style="text-align: right; float:left; width:30%; color:#000;">Disc Per Unit</div>
                                                                                <div style="float:left; width:70%;"><input type="text" style="width:200px;"  onblur="calculatetotalpop_tasks(<?php echo $task->taskid; ?>)" onkeypress="return isNumber(event)" name="tasks_discs<?php echo $task->taskid; ?>" id="tasks_discs<?php echo $task->taskid; ?>" value="<?php echo $task->discount; ?>"></div>
                                                                                <div style="clear:both;"></div>
                                                                                <div style="text-align: right; float:left; width:30%; color:#000;">Total </div>
                                                                                <div style="float:left; width:70%;"><input type="text" style="width:200px;" name="tasks_tot<?php echo $task->taskid; ?>" id="tasks_tot<?php echo $task->taskid; ?>" disabled="disable" value="<?php echo $task->total; ?>"></div>

                                                                                <input type="hidden" name="pid<?php echo $task->taskid; ?>" id="pid<?php echo $task->taskid; ?>" value="<?php echo $task->taskid; ?>">
                                                                                <input type="hidden" name="getid<?php echo $task->taskid; ?>" id="getid<?php echo $task->taskid; ?>" value="<?php echo $_GET['id']; ?>">
                                                                                <input type="hidden" name="tid<?php echo $task->taskid; ?>" id="tid<?php echo $task->taskid; ?>" value="<?php echo $_GET['tid']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-body" style="text-align: center;">
                                                                            <input type="button" name="taskedit" value="Update Task" class="btn btn-primary" onclick="edittaskpopup(<?php echo $task->taskid; ?>);"/>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <div class="clear"></div>
                                                                            <div>
                                                                                <button class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Edit parts row wise end-->  
                                                        </td>
                                                    </tr>
                                <?php
                            }
                        }
                        ?>

                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="100%" style="text-align: center;">NO task data </td>
                                            </tr>   
                    <?php } ?> 
                                    </table>
                                    <table class="table table-bordered table-striped">
                                        <th colspan="4">Tax <span style="float: right; cursor: pointer;" data-toggle="modal" href="#edittax"><i class="icon-pencil"></i></span></th>
                                        <tr>
                                            <td width="50%">Tax Amount  </td>
                                            <td><?php
                    $taxamt = isset($row['tax']) ? $row['tax'] : "0";
                    echo $row['tax'];
                    ?></td>
                                        </tr>
                                    </table>
                                    <table class="table table-bordered table-striped">
                                        <th colspan="4">Invoice Amount 
                                            <span style="float: right; cursor: pointer;" data-toggle="modal" href="#editinvoice"><i class="icon-pencil"></i></span>
                                        </th>
                                        <tr>
                                            <td width="50%">Invoice Amount</td>
                                            <td><?php
                    $total_inv_amt = "";
                    $totalarr = array_merge($partamt, $taskamtarr);
                    $total_inv_amt = round(array_sum($totalarr), 2);
                    $total_inv_amt = $total_inv_amt + $taxamt;
                    $total_inv_amt = round($total_inv_amt, 2);
                    $data = array('tid' => $_GET['tid'], 'invamt' => $total_inv_amt);
                    $invamt = "";
                    if (isset($total_inv_amt) && !empty($total_inv_amt)) {
                        if (isset($data)) {
                            $invamt = $this->updateinvamt_get($data);
                            //echo $total_inv_amt;
                            $totalinvamt = isset($invamt) ? $invamt : $row['invoice_amount'];
                            echo $totalinvamt;
                        } else {
                            echo $row['invoice_amount'];
                        }
                    } else {
                        echo $row['invoice_amount'];
                    }
                    ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <?php } elseif ($row['category'] == 0 || $row['category'] == 1) {
                                    ?>
                                    <table class="table table-bordered table-striped">
                                        <th colspan="4">Tax <span style="float: right; cursor: pointer;" data-toggle="modal" href="#edittax"><i class="icon-pencil"></i></span></th>
                                        <tr>
                                            <td width="50%">Tax Amount  </td>
                                            <td><?php
                                    $taxamt = isset($row['tax']) ? $row['tax'] : "0";
                                    echo $row['tax'];
                                    ?></td>
                                        </tr>
                                    </table>
                                <?php } ?>
                                <?php
                                if (($row['statusid'] == '10' || $row['statusid'] == '11') && $row['category'] != '5') {
                                    ?>
                                    <table class="table table-bordered table-striped">
                                        <th colspan="2">Invoice Details</th>
                                        <tr><td width="50%">Invoice Date</td><td><?php echo(date("d-m-Y", strtotime($row['invoice_date']))); ?></td></tr>
                                        <tr><td>Invoice Number</td><td><?php echo($row['invoice_no']); ?></td></tr>
                                        <tr><td>Invoice Amount (INR)</td><td><?php
//                                        if(isset($total_inv_amt) && !empty($total_inv_amt)){
//                                                if(isset($data)){
//                                                $invamt = $this->updateinvamt_get($data);
//                                                //echo $total_inv_amt;
//                                                $totalinvamt = isset($invamt)?$invamt:$row['invoice_amount'];
//                                                echo $totalinvamt;
//                                                }else{
//                                                    echo $row['invoice_amount'];
//                                                }
//                                            }else{
                                                echo $row['invoice_amount'];
                                                //}
                                                ?></td></tr>
                                        <tr>
                                            <td>Invoice File</td>
                                            <td>
                                                <?php if ($row['invoice_file_name'] == "") { ?>
                                                    <?php
                                                    echo "NA";
                                                } else {
                                                    ?>
                                                    <a target="_blank" href="<?php echo $url . $row['invoice_file_name']; ?>" ><?php echo $row['invoice_file_name']; ?></a>
                                                <?php } ?>
                                            </td>

                                        </tr>
                                        <?php
                                        if ($row['category'] == '2' && ($row['invoice_amount'] - $row['amount_quote']) > 0) {
                                            ?>
                                            <tr><td>Extra Amount (INR)</td><td><?php echo($row['invoice_amount'] - $row['amount_quote']); ?></td></tr>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                    <?php
                                }
                                if ($row['category'] == '5') {
                                    ?>

                                    <table class="table table-bordered table-striped">
                                        <th colspan="4">Accessory Details</th>
                                        <tr>
                                            <td width="15%"><b>Sr. No</b></td>
                                            <td width="35%"><b>Accessory</b></td>
                                            <td width="25%"><b>Cost</b></td>
                                            <td width="25%"><b>Max. Perm. Amount</b></td>
                                        </tr>
                                        <?php
                                        $accessories = $this->getaccessories_forapproval($row['transid']);
                                        if (isset($accessories)) {
                                            foreach ($accessories as $thisacc) {
                                                ?>
                                                <tr>
                                                    <td><?php echo($thisacc->count); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo($thisacc->name); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo($thisacc->cost); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo($thisacc->max_amount); ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </table>
                                    <?php
                                }
                                ?>
                                <table class="table table-bordered table-striped">
                                    <th colspan="2">Capitalization Details</th>

                                    <tr>
                                        <td width="50%">Current Ratio (Maintenance / Capitalisation) </td><td><?php echo $catch_arr['cost_of_capitalisation']; ?></td>
                                    </tr><tr>
                                        <td>Expected Growth </td><td><?php echo $catch_arr['expected_growth']; ?></td>
                                    </tr>
                                </table>
                            </div>
                            <?php
                            $flag = false;
                            $masterRoleId = 1;
                            $stateRoleId = 2;
                            $zoneRoleId = 3;
                            $regionRoleId = 4;
                            $groupRoleId = 0;
                            $adminRoleId = 5;
                            $coExecutiveId = "";

                            switch ($_SESSION['customerno']) {
                                case 63:
                                    $masterRoleId = 28;
                                    $zoneRoleId = 30;
                                    $regionRoleId = 31;
                                    break;
                                case 64:
                                    $masterRoleId = 33;
                                    $zoneRoleId = 35;
                                    $regionRoleId = 36;
                                    break;
                                case 118:
                                    $masterRoleId = 18;
                                    $stateRoleId = 19;
                                    $zoneRoleId = 20;
                                    $regionRoleId = 21;
                                    $groupRoleId = 22;
                                    $coExecutiveId = 23;
                                    break;
                                case 167:
                                    $masterRoleId = 24;
                                    $zoneRoleId = 26;
                                    $regionRoleId = 27;
                                    break;
                                default:
                                    $masterRoleId = 1;
                                    $stateRoleId = 2;
                                    $zoneRoleId = 3;
                                    $regionRoleId = 4;
                                    break;
                            }
                            $oldmasterRoleId = 1;
                            $oldstateRoleId = 2;
                            $oldzoneRoleId = 3;
                            $oldregionRoleId = 4;

                            if (($row['roleid'] == $regionRoleId || $row['roleid'] == $oldregionRoleId || $row['roleid'] == $coExecutiveId ) && ($_SESSION['roleid'] == $masterRoleId || $_SESSION['roleid'] == $stateRoleId || $_SESSION['roleid'] == $zoneRoleId || $_SESSION['roleid'] == $regionRoleId || $_SESSION['roleid'] == $coExecutiveId)) {
                                $flag = true;
                            } else if (($row['roleid'] == $zoneRoleId || $row['roleid'] == $oldzoneRoleId || $row['roleid'] == $coExecutiveId ) && ($_SESSION['roleid'] == $masterRoleId || $_SESSION['roleid'] == $stateRoleId || $_SESSION['roleid'] == $zoneRoleId || $_SESSION['roleid'] == $coExecutiveId)) {
                                $flag = true;
                            } else if (($row['roleid'] == $stateRoleId || $row['roleid'] == $oldstateRoleId || $row['roleid'] == $coExecutiveId) && ($_SESSION['roleid'] == $masterRoleId || $_SESSION['roleid'] == $stateRoleId || $_SESSION['roleid'] == $coExecutiveId)) {
                                $flag = true;
                            } else if (($row['roleid'] == $masterRoleId || $row['roleid'] == $coExecutiveId || $row['roleid'] == $oldmasterRoleId) && ($_SESSION['roleid'] == $masterRoleId || $_SESSION['roleid'] == $coExecutiveId )) {
                                $flag = true;
                            } else if ($row['roleid'] == $adminRoleId || $row['roleid'] == $coExecutiveId && ($_SESSION['roleid'] == $adminRoleId || $_SESSION['roleid'] == $coExecutiveId )) {
                                $flag = true;
                            }
                            if ($_SESSION['roleid'] == $masterRoleId || $_SESSION['roleid'] == $oldmasterRoleId || $row['roleid'] == $coExecutiveId) {
                                $flag = true;
                            }

                            if ($flag == true && ($row['statusid'] == '7')) {
                                ?>

                                <form class=" well">
                                    <label>Note</label>
                                    <textarea name="notes" id="notes" rows="4" cols="50"></textarea></br><span id="error_msg"></span>
                                    <input type="button" class='btn btn-primary' onclick="push_status(<?php echo $main_id; ?>, '8')" value="Approve">
                                    <input type="button" class='btn btn-danger' onclick="push_status(<?php echo $main_id; ?>, '9')" value="Reject">
                                </form>
                                <?php
                            } elseif ($flag == true && $row['statusid'] == '10') {
                                ?>
                                <form class=" well">
                                    <label>Note</label>
                                    <textarea name="notes" id="notes" rows="4" cols="50"></textarea></br><span id="error_msg"></span>
                                    <input type="button" class='btn btn-primary' onclick="push_status(<?php echo $main_id; ?>, '13')" value="Approve">
                                    <input type="button" class='btn btn-danger' onclick="push_status(<?php echo $main_id; ?>, '11')" value="Reject">
                                </form>
                                <?php
                            } elseif ($flag == true && $row['statusid'] == '11') {
                                ?>
                                <form class=" well">
                                    <input type="button" class='btn btn-danger' onclick="push_status(<?php echo $main_id; ?>, '12')" value="Cancel">
                                </form>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="tab-pane" id="transaction_history">
                            <table class="table table-bordered table-striped  ">
                                <tr>
                                    <td>Transaction ID</td>
                                    <td>Date of Submission</td>
                                    <td>Status</td>
                                    <td>View</td>


                                </tr>
                                <?php
                                $Query = "SELECT *, maintenance_history.submission_date as submission_date from maintenance_history
                        INNER JOIN vehicle on maintenance_history.vehicleid=vehicle.vehicleid
                        INNER JOIN " . DB_PARENT . ".role on maintenance_history.roleid= role.id
                        INNER JOIN maintenance_status on maintenance_history.statusid= maintenance_status.id";
                                $Query .= " WHERE maintenance_history.customerno =%d AND maintenance_history.vehicleid=%d AND maintenance_history.category=%d ORDER BY maintenance_history.maintananceid DESC";

                                $maintananceQuery = sprintf($Query, $this->_Customerno, $vehicleid, $category);
                                $this->_databaseManager->executeQuery($maintananceQuery);
                                if ($this->_databaseManager->get_rowCount() > 0) {
                                    while ($row = $this->_databaseManager->get_nextRow()) {

                                        $url = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $row['vehicleid'] . "/";
                                        ?>
                                        <tr>
                                            <td><?php
                                                if ($row['category'] == '0') {
                                                    echo "B00" . $row['maintananceid'];
                                                }
                                                if ($row['category'] == '1') {
                                                    echo "T00" . $row['maintananceid'];
                                                }
                                                if ($row['category'] == '2') {
                                                    echo "R00" . $row['maintananceid'];
                                                }
                                                if ($row['category'] == '3') {
                                                    echo "S00" . $row['maintananceid'];
                                                }
                                                if ($row['category'] == '5') {
                                                    echo "A00" . $row['maintananceid'];
                                                }
                                                ?></td>

                                            <td><?php echo date("d-m-Y", strtotime($row['submission_date'])); ?></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <?php
                                            //if($row['statusid'] == '14' || $row['statusid'] == '6')
                                            //{
                                            echo "<td><a href='javascript:void(0);' onclick='print_battery_closed(" . $row['maintananceid'] . "," . $row['vehicleid'] . ")'><img src='../../images/view.png'></img></td>";
                                            //}
                                            //else
                                            //{
                                            echo "<td></td>";
                                            //}
                                            ?>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </table>
                        </div>
                        <div class="tab-pane" id="vehicle_history" style="overflow:auto; height:620px">
                            <style>
                                table td, table th {
                                    border: 1px solid black;
                                }

                            </style>
                            <table  class="table table-condensed"  style=" width:90%">
                                <th colspan="100"> Vehicle History</th>
                                <tr>
                                    <td>
                                        <?php
                                        //error_reporting(0);
                                        //ini_set('display_errors', 'On');
                                        $inside = false;

                                        $hist = $this->get_veh_mnt_history($vehicleid, 0);
                                        if ($hist) {
                                            echo "<table>";
                                            echo "<thead><tr><th colspan='100%'>Battery history</th></tr><tr><th>#</th><th>Transaction ID</th><th>Meter Reading</th><th>Dealer Name</th><th>Notes</th><th>Invoice No.</th><th>Invoice Amt</th><th>Invoice Date</th><th>Quotation amount</th>";
                                            echo "<th>Modified Date</th><th>Status</th>";
                                            echo "</tr></thead><tbody>";

                                            $i = 1;
                                            foreach ($hist as $record) {
                                                $mr = date('d-M-Y H:i', strtotime($record->mdate));
                                                echo "<tr><td>$i</td><td>{$record->transid}</td><td>{$record->meter_reading}</td><td>{$record->dname}</td><td>{$record->notes}</td>";
                                                echo "<td>{$record->invno}</td><td>{$record->invamt}</td><td>{$record->invdate}</td><td>{$record->amount_quote}</td><td>$mr</td><td>{$record->msname}</td>";
                                                echo "</tr>";
                                                $i++;
                                            }
                                            echo "</tbody></table>";
                                            $inside = true;
                                        }
                                        $hist = $this->get_veh_mnt_history($vehicleid, 1);
                                        if ($hist) {
                                            echo "<table>";
                                            echo "<thead><tr><th colspan='100%'>Tyre history</th></tr><tr><th>#</th><th>Transaction ID</th><th>Meter Reading</th><th>Dealer Name</th><th>Notes</th><th>Invoice No.</th><th>Invoice Amt</th><th>Invoice Date</th><th>Quotation amount</th>";
                                            echo "<th>Modified Date</th><th>Status</th>";
                                            echo "<th>Tyre Type</th>";
                                            echo "<th>Tyre Serial No.</th>";
                                            echo "</tr></thead><tbody>";

                                            $i = 1;
                                            foreach ($hist as $record) {
                                                $mr = date('d-M-Y H:i', strtotime($record->mdate));
                                                echo "<tr><td>$i</td><td>{$record->transid}</td><td>{$record->meter_reading}</td><td>{$record->dname}</td><td>{$record->notes}</td>";
                                                echo "<td>{$record->invno}</td><td>{$record->invamt}</td><td>{$record->invdate}</td><td>{$record->amount_quote}</td><td>$mr</td><td>{$record->msname}</td>";
                                                echo "<td>{$record->repairtype}</td>";
                                                echo "<td>{$record->tyre}</td>";
                                                echo "</tr>";
                                                $i++;
                                            }
                                            echo "</tbody></table>";
                                            $inside = true;
                                        }
                                        $hist = $this->get_veh_mnt_history($vehicleid, 2);
                                        if ($hist) {
                                            echo "<table>";
                                            echo "<thead><tr><th colspan='100%'>Repair/Service History</th></tr>";
                                            echo "<tr><td colspan ='100%' style = 'text-align:center;font-weight:bold'>Lables : U - Unit Price , Q - Quantity , T - Total Amount</td></tr>";
                                            echo "<tr><th>#</th><th>Transaction ID</th><th>Meter Reading</th><th>Dealer Name</th><th>Notes</th><th>Invoice No.</th><th>Invoice Amt</th><th>Invoice Date</th><th>Quotation amount</th>";
                                            echo "<th>Modified Date</th><th>Status</th>";
                                            echo "<th>Parts</th><th>Tasks</th>";
                                            echo "</tr></thead><tbody>";

                                            $i = 1;
                                            foreach ($hist as $record) {
                                                //to check the parts and task in maintenance_parts/task table
                                                $record_parts = $this->getpartsby_maintenanceid($record->mid);
                                                $record_tasks = $this->gettaskby_maintenanceid($record->mid);
                                                $mr = date('d-M-Y H:i', strtotime($record->mdate));
                                                echo "<tr><td>$i</td><td>{$record->transid}</td><td>{$record->meter_reading}</td><td>{$record->dname}</td><td>{$record->notes}</td>";
                                                echo "<td>{$record->invno}</td><td>{$record->invamt}</td><td>{$record->invdate}</td><td>{$record->amount_quote}</td><td>$mr</td><td>{$record->msname}</td>";
                                                if (!empty($record_parts)) {
                                                    echo"<td>";
                                                    $j = 1;
                                                    foreach ($record_parts as $parts) {
                                                        echo $j . ") ";
                                                        echo $parts;
                                                        echo "<br />";
                                                        $j++;
                                                    }
                                                    echo"</td>";
                                                } else {
                                                    echo"<td> </td>";
                                                }
                                                if (!empty($record_tasks)) {
                                                    echo"<td>";
                                                    $k = 1;
                                                    foreach ($record_tasks as $tasks) {
                                                        echo $k . ") ";
                                                        echo $tasks;
                                                        echo "<br />";
                                                        $k++;
                                                    }
                                                    echo"</td>";
                                                } else {
                                                    echo"<td> </td>";
                                                }

                                                echo "</tr>";
                                                $i++;
                                            }
                                            echo "</tbody></table>";
                                            $inside = true;
                                        }
                                        $hist = $this->get_veh_mnt_history($vehicleid, 5);
                                        if ($hist) {
                                            echo "<table class='table newTable'>";
                                            echo "<thead><tr><th colspan='100%'>Accesories History</th></tr><tr><th>#</th><th>Transaction ID</th><th>Meter Reading</th><th>Dealer Name</th><th>Notes</th><th>Invoice No.</th><th>Invoice Amt</th><th>Invoice Date</th><th>Quotation amount</th>";
                                            echo "<th>Modified Date</th><th>Status</th>";
                                            echo "<th>Accessories</th>";
                                            echo "</tr></thead><tbody>";

                                            $i = 1;
                                            foreach ($hist as $record) {
                                                $mr = date('d-M-Y H:i', strtotime($record->mdate));
                                                echo "<tr><td>$i</td><td>{$record->transid}</td><td>{$record->meter_reading}</td><td>{$record->dname}</td><td>{$record->notes}</td>";
                                                echo "<td>{$record->invno}</td<td>{$record->invamt}</td><td>{$record->invdate}</td><td>{$record->amount_quote}</td><td>$mr</td><td>{$record->msname}</td>";
                                                echo "<td>{$record->access}</td>";
                                                echo "</tr>";
                                                $i++;
                                            }
                                            echo "</tbody></table>";
                                            $inside = true;
                                        }
                                        $hist = $this->get_filtered_fuels('', $vehicleid, null);
                                        if ($hist) { //Fuel history
                                            echo "<table class='table newTable'>";
                                            echo "<thead><tr><th colspan='100%'>Fuel History</th><tr><th>#</th><th>Transaction ID</th><th>Date & Time</th><th>Fuel (In Lt.)</th><th>Amount</th><th>Rate</th>";
                                            echo "<th>Ref.No</th><th>Opening Km</th><th>Ending Km</th><th>Average</th><th>Vendor</th>";
                                            echo "</tr></thead><tbody>";

                                            $i = 1;
                                            foreach ($hist as $record) {
                                                echo "<tr><td>$i</td><td>{$record->trans}</td><td>" . date('d-M-Y H:i', strtotime($record->submit_datetime)) . "</td><td>{$record->fuel}</td><td>{$record->amount}</td><td>{$record->rate}</td>";
                                                echo "<td>{$record->invno}</td>";
                                                echo "<td>{$record->openingkm}</td>";
                                                echo "<td>{$record->endingkm}</td>";
                                                echo "<td>{$record->average}</td>";
                                                echo "<td>{$record->dname}</td>";
                                                echo "</tr>";
                                                $i++;
                                                $inside = true;
                                            }
                                        }

                                        if (!$inside) {
                                            echo "<h2>No Data found</h2>";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                            </table>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }

    public function getTransactionSatus($mainid) {
        $Query = "SELECT maintenance_status.name as statusname from maintenance
                  INNER JOIN maintenance_status ON maintenance_status.id = maintenance.statusid
                  WHERE  maintenance.id=%d";

        $maintananceQuery = sprintf($Query, $mainid);
        $this->_databaseManager->executeQuery($maintananceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['statusname'];
            }
        }
    }

    public function getAccidentSatus($mainid) {
        $Query = "SELECT maintenance_status.name as statusname from accident
                  INNER JOIN maintenance_status ON maintenance_status.id = accident.statusid
                  WHERE  accident.id=%d";

        $maintananceQuery = sprintf($Query, $mainid);
        $this->_databaseManager->executeQuery($maintananceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['statusname'];
            }
        }
    }

    public function getTransactionRole($mainid) {
        $Query = "SELECT roleid from maintenance WHERE maintenance.id=%d AND maintenance.isdeleted=0";
        $maintananceQuery = sprintf($Query, $mainid);
        $this->_databaseManager->executeQuery($maintananceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['roleid'];
            }
        }
    }

    public function getAccidentRole($mainid) {
        $Query = "SELECT roleid from accident WHERE accident.id=%d";

        $maintananceQuery = sprintf($Query, $mainid);
        $this->_databaseManager->executeQuery($maintananceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['roleid'];
            }
        }
    }

    public function get_approval_form_by_vehicleid_formail($main_id) {
        $printdata = "";
        $maintanances = Array();
        $vehicleid = 0;
        $category = 0;
        $Query = "SELECT *,maintenance.notes as mainnotes, maintenance.id AS transid,maintenance.approval_notes, maintenance_status.name as statusname, vehicle.manufacturing_year, vehicle.purchase_date, vehicle.owner_name,make.name as makename,model.name as modelname from maintenance
            INNER JOIN vehicle ON maintenance.vehicleid=vehicle.vehicleid
            LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
            INNER JOIN " . DB_PARENT . ".role ON maintenance.roleid= role.id
            INNER JOIN maintenance_status ON maintenance_status.id = maintenance.statusid
            LEFT OUTER JOIN model ON vehicle.modelid = model.model_id
            LEFT OUTER JOIN make ON model.make_id = make.id
            LEFT OUTER JOIN dealer ON maintenance.dealer_id= dealer.dealerid";
        $Query .= " WHERE maintenance.customerno =%d AND  maintenance.id=%d AND maintenance.isdeleted=0";

        $maintananceQuery = sprintf($Query, $this->_Customerno, $main_id);
        $this->_databaseManager->executeQuery($maintananceQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicleid = $row["vehicleid"];
                $category = $row["category"];
                $url = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $row['vehicleid'] . "/";
                if ($row['category'] == '0') {
                    $cat = "Battery";
                }
                if ($row['category'] == 1) {
                    $cat = "Tyre";
                }
                if ($row['category'] == 2) {
                    $cat = "Repair";
                }
                if ($row['category'] == 3) {
                    $cat = "Service";
                }
                if ($row['category'] == 5) {
                    $cat = "Accessory";
                }
                $catch_arr = $this->maintenance_percent($row['vehicleid'], $row['amount_quote'], $row["transid"], null);
                $printdata .='';
                ?>
                <?php
                $printdata .='<div class="table" style="width:51%;">
                <div class="tab-content">
                <div class="tab-pane active" id="transaction_current">
                    	<div>
                        <table class="table table-bordered table-striped">
                        <th colspan="2">Vehicle Details</th>
                        <tr>
                        <td width="50%">Vehicle No.</td><td>' . $row['vehicleno'] . '</td>
                        </tr>
                        <tr>
                        <td>Model </td><td>' . $row['modelname'] . '</td>
                        </tr>
                        <tr>
                        <td>Make </td><td>' . $row['makename'] . '</td>
                        </tr>
                        <tr>
                        <td>Branch</td><td>' . $row['groupname'] . '</td>
                        </tr>
                        <tr>
                        <td>Manufacturing Year </td><td>' . $row['manufacturing_year'] . '</td>
                        </tr>
                        <tr>
                        <td>Owner Name </td><td>' . $row['owner_name'] . '</td>
                        </tr>
                        <tr>';
                $purchase = '';
                if ($row['purchase_date'] == '0000-00-00' && $row['purchase_date'] == '1970-01-01') {
                    $purchase = '';
                } else {
                    $purchase = date("d-m-Y", strtotime($row['purchase_date']));
                }
                $printdata .='<td>Purchse Date</td><td>' . $purchase . '</td>
                        </tr>
                        <tr>
                        <td>GPS Odometer Reading</td><td>' . $row['odometer'] . '</td>
                        </tr>
                        <tr>
                        <td>Vehicle Meter reading </td><td>' . $row['meter_reading'] . '</td>
                        </tr>
                    </table>

                    <table class="table table-bordered table-striped">
                        <th colspan="2">Transaction Details</th>
                        <tr>
                        <td width="50%">Transaction ID</td><td>';
                if ($row['category'] == '0') {
                    $printdata .="B00" . $row['transid'];
                } elseif ($row['category'] == '1') {
                    $printdata .= "T00" . $row['transid'];
                } elseif ($row['category'] == '2') {
                    $printdata .="R00" . $row['transid'];
                } elseif ($row['category'] == '3') {
                    $printdata .= "S00" . $row['transid'];
                } elseif ($row['category'] == '5') {
                    $printdata .= "A00" . $row['transid'];
                }

                $printdata .='</td>
                        </tr>
                        <tr>
                        <td>Category</td><td>' . $cat . '</td>
                        </tr>
                        <tr>
                        <td>Dealer name </td><td>' . $row['name'] . '</td>
                        </tr>
                        <tr>
                        <td>Notes</td><td>' . $row['mainnotes'] . '</td>
                        </tr>
                        <tr>
                        <td>Status</td><td>' . $row['statusname'] . '</td>
                        </tr>
                        <tr>
                        <td>Quotation Approval/Rejection Notes</td><td>' . $row['approval_notes'] . '</td>
                        </tr>
                        <tr>
                        <td>Payment Approval/Rejection Notes</td><td>' . $row['payment_approval_note'] . '</td>
                        </tr>
                        ';
                if (($row['statusid'] == '10' || $row['statusid'] == '11') && $row['category'] != '5') {


                    $printdata .=' <tr>
                            <td>Vehicle In Date</td><td>' . date("d-m-Y", strtotime($row['vehicle_in_date'])) . '</td>
                        </tr>
                        <tr>
                            <td>Vehicle Out Date</td><td>' . date("d-m-Y", strtotime($row['vehicle_out_date'])) . '</td>
                        </tr> ';
                }

                $printdata .=' </table>

                    <table class="table table-bordered table-striped">
                        <th colspan="2">Quotation Details</th>
                        <tr>
                        <td width="50%">Quotation Amount (INR)</td><td>' . $row['amount_quote'] . '</td>
                        </tr>
                        <tr>
                             <td>Quotation File</td>
                        <td>';
                if ($row['file_name'] == "") {
                    $printdata .="NA";
                } else {
                    $printdata .='<a target="_blank" href="' . $url . $row['file_name'] . '" >' . $row['file_name'] . '</a>';
                }
                $printdata .='      </td>
                        </tr>
                        <tr>
                        <td>Quotation Submission Date</td><td>' . date("d-m-Y", strtotime($row['submission_date'])) . '</td>
                        </tr>';


                if ($row['category'] == '1') {
                    $printdata .='		<tr>
                        <td>Tyre Type</td><td>';

                    if ($row['tyre_type']) {

                        $tyre_type = explode(",", $row['tyre_type']);
                        if (isset($tyre_type)) {
                            foreach ($tyre_type as $thistype) {
                                if ($thistype == '1') {

                                    $battery_detail[] = "Right Front";
                                }
                                if ($thistype == '2') {

                                    $battery_detail[] = "Right Back";
                                }
                                if ($thistype == '3') {

                                    $battery_detail[] = "Left Front";
                                }
                                if ($thistype == '4') {

                                    $battery_detail[] = "Left Back";
                                }
                                if ($thistype == '5') {

                                    $battery_detail[] = "Stepney";
                                }
                            }
                        }
                    }
                    if (isset($battery_detail)) {
                        $printdata .=implode(", ", $battery_detail);
                    } else {
                        $printdata .="";
                    }
                    $printdata .='</td>
            </tr>';
                }

                if ($row['category'] == '2') {
                    $printdata .='<tr><td>Parts Consumed</td><td>';
                    $parts = explode(",", $row['parts_list']);
                    $partsnew = $this->getparts($parts);
                    $printdata .=implode(", ", $partsnew);

                    $printdata .='</td></tr><tr><td>Tasks Performed</td><td> ';

                    $tasks = explode(",", $row['task_select_array']);
                    $tasksnew = $this->gettasks($tasks);
                    $printdata .=implode(", ", $tasksnew);

                    $printdata .=' </td></tr>';
                }

                $printdata .='</table>';

                if (($row['statusid'] == '10' || $row['statusid'] == '11') && $row['category'] != '5') {

                    $printdata.='<table class="table table-bordered table-striped">
                        <th colspan="2">Invoice Details</th>
                        <tr><td width="50%">Invoice Date</td><td>' . date("d-m-Y", strtotime($row['invoice_date'])) . '</td></tr>
                        <tr><td>Invoice Number</td><td>' . $row['invoice_no'] . '</td></tr>
                        <tr><td>Invoice Amount (INR)</td><td>' . $row['invoice_amount'] . '</td></tr>
                        <tr>
                             <td>Invoice File</td>
                        <td>';
                    if ($row['invoice_file_name'] == "") {
                        $printdata .="NA";
                    } else {
                        $printdata .='<a target="_blank" href="' . $url . $row['invoice_file_name'] . '" >' . $row['invoice_file_name'] . '</a>';
                    }
                    $printdata .='</td>

                        </tr>';

                    if ($row['category'] == '2' && ($row['invoice_amount'] - $row['amount_quote']) > 0) {

                        $printdata .='<tr><td>Extra Amount (INR)</td><td>' . $row['invoice_amount'] - $row['amount_quote'] . '</td></tr> ';
                    }

                    $printdata .='</table>';
                }
                if ($row['category'] == '5') {


                    $printdata .='<table class="table table-bordered table-striped">
                        <th colspan="4">Accessory Details</th>
                        <tr>
                        <td width="15%"><b>Sr. No</b></td>
                        <td width="35%"><b>Accessory</b></td>
                        <td width="25%"><b>Cost</b></td>
                        <td width="25%"><b>Max. Perm. Amount</b></td>
                        </tr>';

                    $accessories = $this->getaccessories_forapproval($row['transid']);
                    if (isset($accessories)) {
                        foreach ($accessories as $thisacc) {

                            $printdata .=' <tr>
                                <td>' . $thisacc->count . '
                                </td>
                                <td>
                                    ' . $thisacc->name . '
                                </td>
                                <td>
                                    ' . $thisacc->cost . '
                                </td>
                                <td>
                                    ' . $thisacc->max_amount . '
                                </td>
                                </tr> ';
                        }
                    }

                    $printdata .='</table> ';
                }

                $printdata .='<table class="table table-bordered table-striped">
                        <th colspan="2">Capitalization Details</th>

                         <tr>
                        <td width="50%">Current Ratio (Maintenance / Capitalisation) </td><td>' . $catch_arr['cost_of_capitalisation'] . '</td>
                        </tr><tr>
                        <td>Expected Growth </td><td>' . $catch_arr['expected_growth'] . '</td>
                        </tr>
                    </table>
                    </div>
                </div>
                </div>
                </div>';


                return $printdata;
            }
        }
    }

    public function get_approval_form_by_vehicleid_formail_getdata($main_id) {
        $printdata = "";
        $maintanances = Array();
        $vehicleid = 0;
        $category = 0;
        $Query = "SELECT *,maintenance.notes as mainnotes, maintenance.id AS transid,maintenance.approval_notes, maintenance_status.name as statusname, vehicle.manufacturing_year, vehicle.purchase_date, vehicle.owner_name,make.name as makename,model.name as modelname from maintenance
            INNER JOIN vehicle ON maintenance.vehicleid=vehicle.vehicleid
            LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
            INNER JOIN " . DB_PARENT . ".role ON maintenance.roleid= role.id
            INNER JOIN maintenance_status ON maintenance_status.id = maintenance.statusid
            LEFT OUTER JOIN model ON vehicle.modelid = model.model_id
            LEFT OUTER JOIN make ON model.make_id = make.id
            LEFT OUTER JOIN dealer ON maintenance.dealer_id= dealer.dealerid";
        $Query .= " WHERE maintenance.customerno =%d AND  maintenance.id=%d AND maintenance.isdeleted=0";

        $maintananceQuery = sprintf($Query, $this->_Customerno, $main_id);
        $this->_databaseManager->executeQuery($maintananceQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_recordSet();
        }
        return $row;
    }

    // added to get type for mail subject
    public function gettransaction_type_formail($main_id) {
        $cat = '';
        $Query = "SELECT * FROM maintenance WHERE customerno =%d AND id=%d AND isdeleted=0";
        $maintananceQuery = sprintf($Query, $this->_Customerno, $main_id);
        //echo $maintananceQuery;
        $this->_databaseManager->executeQuery($maintananceQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();

            if ($row['category'] == '0') {
                $cat = "Battery #B00" . $row['id'];
            }
            if ($row['category'] == 1) {
                $cat = "Tyre #T00" . $row['id'];
            }
            if ($row['category'] == 2) {
                $cat = "Repair #R00" . $row['id'];
            }
            if ($row['category'] == 3) {
                $cat = "Service #S00" . $row['id'];
            }
            if ($row['category'] == 5) {
                $cat = "Accessory #A00" . $row['id'];
            }
        }
        return $cat;
    }

    public function getaccessories_forapproval($mainid) {
        $accs = Array();
        $count = 0;
        $Query = "SELECT * FROM `accessory_map` INNER JOIN `accessory` ON accessory_map.accessoryid = accessory.id
                    where accessory_map.maintenanceid = %d AND  accessory_map.isdeleted=0";
        $GroupsQuery = sprintf($Query, $mainid);
        $this->_databaseManager->executeQuery($GroupsQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $count++;
                $acc = new VOAccessory();
                $acc->count = $count;
                $acc->id = $row['accessoryid'];
                $acc->name = $row['name'];
                $acc->cost = $row['cost'];
                $acc->max_amount = $row['max_amount'];
                $acc->quantity = $row['quantity'];
                $accs[] = $acc;
            }
            return $accs;
        }
        return null;
    }

    public function get_acc_approval_form_by_vehicle_id($acc_id) {
        $maintanances = Array();

        $Query = "SELECT *,accident.id as transid, accident.description as descri from accident
            inner join vehicle on accident.vehicleid=vehicle.vehicleid
            inner join " . DB_PARENT . ".role on accident.roleid= role.id
        ";
        $Query .= " WHERE accident.customerno =%d AND  accident.id=%d";

        $maintananceQuery = sprintf($Query, $this->_Customerno, $acc_id);
        $this->_databaseManager->executeQuery($maintananceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {

                $url = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $row['vehicleid'] . "/";
                $cat = "Accident";
                $catch_arr = $this->maintenance_percent($row['vehicleid'], $row['loss_amount'], null, $row["transid"]);
                ?>
                <div class="table" style="width: 51%;">
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="active"><a href="#transaction_current" data-toggle="tab">Transaction</a></li>
                        <!--<li><a href="#transaction_history" data-toggle="tab">history</a></li>-->

                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="transaction_current">
                            <div>
                                <table class="table table-bordered table-striped  ">
                                    <th colspan="2">Accident Details</th>
                                    <tr>
                                        <td width="50%">Transaction ID</td><td><?php echo "AC00" . $row['id']; ?></td>
                                    </tr>

                                    <tr>
                                        <td width="50%">Vehicle No.</td><td><?php echo $row['vehicleno']; ?></td>
                                    </tr>

                                    <tr>
                                        <td>Accident Datetime</td><td><?php echo $row['accident_datetime']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Accident Location</td><td><?php echo $row['accident_location']; ?></td>
                                    </tr>

                                    <tr>
                                        <td>Accident Description</td><td><?php echo $row['descri']; ?></td>
                                    </tr>

                                    <tr>
                                        <td>Driver Name </td><td><?php echo $row['drivername']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Licence validity from </td><td><?php echo $row['licence_validity_from']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Licence validity to </td><td><?php echo $row['licence_validity_to']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Licence Type </td><td><?php echo $row['licence_type']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Workshop Location</td><td><?php echo $row['workshop_location']; ?></td>
                                    </tr>

                                    <tr>
                                        <td>Estimated Loss Amount (INR)</td><td><?php echo $row['loss_amount']; ?></td>
                                    </tr>

                                    <tr>
                                        <td>Settlement Amount (INR)</td><td><?php echo $row['sett_amount']; ?></td>
                                    </tr>

                                    <tr>
                                        <td>Actual Amount (INR)</td><td><?php echo $row['actual_amount']; ?></td>
                                    </tr>

                                    <tr>
                                        <td><?php echo $_SESSION['customercompany']; ?> Amount (INR)</td><td><?php echo $row['mahindra_amount']; ?></td>
                                    </tr>

                                    <td>Category</td><td><?php echo $cat; ?></td>
                                </tr><tr>
                                <td>Current Ratio (Maintenance / Capitalisation) </td><td><?php echo $catch_arr['cost_of_capitalisation']; ?></td>
                            </tr><tr>
                                <td>Expected Growth </td><td><?php echo $catch_arr['expected_growth']; ?></td>

                            </tr>
                            <tr>
                                <td>File Links</td>
                                <td>
                <?php
                $uploaddir = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $row['vehicleid'] . "/";
                if (file_exists($uploaddir)) {
                    $dh = opendir($uploaddir);
                    //var_dump($acc_id).'==========';
                    $strcount = count($acc_id) + 1;
                    while (false !== ($filename = readdir($dh))) {
                        if (substr($filename, 0, $strcount) == $acc_id) {

                            $files_obj = array();
                            $files_obj['name'] = $filename;
                            $files_obj['url'] = $uploaddir . $filename;
                            ?>
                                                <a href="<?php echo $files_obj['url']; ?>" target="_blank"><?php echo $files_obj['name']; ?></a>
                                                <br/>
                                                <?php
                                            }
                                        }

                                        //$acc_detail['files'] = $files;
                                    } else {
                                        echo "NA";
                                    }
                                    ?>

                                </td>

                            </tr>
                        </table>
                    </div>
                <?php if ($_SESSION['roleid'] == $row['roleid'] && ($row['statusid'] == '7' || $row['statusid'] == '10' || $row['statusid'] == '11')) { ?>
                        <form class=" well">
                            <label>Note</label>
                            <textarea name="note" id="note" rows="4" cols="50"></textarea></br><span id="error_msg"></span>
                            <input type="button" class='btn btn-primary' onclick="push_acc_status(<?php echo $acc_id; ?>, '8')" value="Approve">
                            <input type="button" class='btn btn-danger' onclick="push_acc_status(<?php echo $acc_id; ?>, '9')" value="Reject">
                        </form>
                <?php } ?>
                </div>



                </div>

                </div>

                </div>


                <?php
            }
        }
    }

    public function get_acc_approval_form_by_vehicle_id_formail($acc_id) {
        $maintanances = Array();

        $Query = "SELECT *,accident.id as transid from accident
            inner join vehicle on accident.vehicleid=vehicle.vehicleid
            inner join " . DB_PARENT . ".role on accident.roleid= role.id
        ";
        $Query .= " WHERE accident.customerno =%d AND  accident.id=%d";

        $maintananceQuery = sprintf($Query, $this->_Customerno, $acc_id);
        $this->_databaseManager->executeQuery($maintananceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {

                $url = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $row['vehicleid'] . "/";
                $cat = "Accident";
                $catch_arr = $this->maintenance_percent($row['vehicleid'], $row['loss_amount'], null, $row["transid"]);
                $printdata .='';

                $printdata.='<div class="table" style="width: 51%;">
                <div class="tab-content">
                <div class="tab-pane active" id="transaction_current">
                    <div >
                    <table class="table table-bordered table-striped  ">
                        <th colspan="2">Accident Details</th>
                        <tr>
                        <td width="50%">Transaction ID</td><td>' . "AC00" . $row['id'] . '</td>
                        </tr>

                        <tr>
                        <td width="50%">Vehicle No.</td><td>' . $row['vehicleno'] . '</td>
                        </tr>

                        <tr>
                        <td>Accident Datetime</td><td>' . $row['accident_datetime'] . '</td>
                        </tr>
						<tr>
                        <td>Accident Location</td><td>' . $row['accident_location'] . '</td>
                        </tr>

						<tr>
                        <td>Accident Description</td><td>' . $row['description'] . '</td>
                        </tr>

						<tr>
                        <td>Driver Name </td><td>' . $row['drivername'] . '</td>
                        </tr>
						<tr>
                        <td>Licence validity from </td><td>' . $row['licence_validity_from'] . '</td>
                        </tr>
						<tr>
                        <td>Licence validity to </td><td>' . $row['licence_validity_to'] . '</td>
                        </tr>
						<tr>
                        <td>Licence Type </td><td>' . $row['licence_type'] . '</td>
                        </tr>
						<tr>
                        <td>Workshop Location</td><td>' . $row['workshop_location'] . '</td>
                        </tr>

						<tr>
                        <td>Estimated Loss Amount (INR)</td><td>' . $row['loss_amount'] . '</td>
                        </tr>

						<tr>
                        <td>Settlement Amount (INR)</td><td>' . $row['sett_amount'] . '</td>
                        </tr>

						<tr>
                        <td>Actual Amount (INR)</td><td>' . $row['actual_amount'] . '</td>
                        </tr>

						<tr>
                        <td>Mahindra Amount (INR)</td><td>' . $row['mahindra_amount'] . '</td>
                        </tr>

                        <td>Category</td><td><?php echo   $cat; ?></td>
                        </tr><tr>
                        <td>Current Ratio (Maintenance / Capitalisation) </td><td>' . $catch_arr['cost_of_capitalisation'] . '</td>
                        </tr><tr>
                        <td>Expected Growth </td><td>' . $catch_arr['expected_growth'] . '</td>

                        </tr>

                    </table>
                </div>

                </div>



                </div>

				</div>

</div>';


                return $dataprint;
            }
        }
    }

    public function get_newbatteryno($main_id, $status) {

        //$maintanances=array();
        $maintanance = new VOMaintanance();
        $Query = "SELECT battery_srno,vehicleid FROM maintenance WHERE id=%d AND statusid=%d AND customerno=%d AND category=0 AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::Long($main_id), $status, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();

            if ($row['battery_srno'] == "") {
                return "error";
            }
            $maintanance->battno = $row['battery_srno'];
            $maintanance->vehid = $row['vehicleid'];
            //$maintanances[]=$maintanance;
        }

        return $maintanance;
    }

    public function getTyreSrno($main_id, $status) {

        //$maintanances=array();
        $maintanance = new VOMaintanance();
        $Query = "SELECT tyre_type,vehicleid FROM maintenance WHERE id=%d AND statusid=%d AND customerno=%d AND category=1 AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::Long($main_id), $status, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();

            if ($row['tyre_type'] == "") {
                return "no_tyre";
            }
            $maintanance->tyre = $row['tyre_type'];
            $maintanance->vehid = $row['vehicleid'];
            //$maintanances[]=$maintanance;
        }

        return $maintanance;
    }

    public function set_pushclose($main_id, $status, $note, $slipno, $chequeno, $chequeamt, $chequedate, $tdsamt) {
        $today = date("Y-m-d H:i:s");
        $chequedate = date('Y-m-d', strtotime($chequedate));
        $main = $this->getlatest_maintenance_details($main_id);
        $accountRoleId = 42;
        if ($status == '13' && $this->_Customerno == 118) {
            $status = '14';

            $note = $main->payment_approval_note . " " . $note;

            $Query = "UPDATE maintenance SET timestamp='%s',payment_approval_date='%s',ofasno ='%s',chequeno='%s',chequeamt='%s',chequedate='%s',tdsamt='%s',payment_approval_note='%s',statusid=%d,roleid = %d WHERE id =%d AND customerno =%d";
            $SQL = sprintf($Query, Sanitise::DateTime($today), Sanitise::DateTime($today), $slipno, $chequeno, $chequeamt, $chequedate, $tdsamt, $note, $status, $accountRoleId, Sanitise::Long($main_id), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);

            $Query1 = "INSERT INTO maintenance_history(maintananceid,maintenance_date,meter_reading,dealer_id,vehicleid,timestamp,userid,customerno,roleid,notes,approval_notes,amount_quote,file_name,tyre_type,battery_srno,
parts_list,task_select_array,category,statusid,submission_date,approval_date,payment_submission_date,invoice_file_name,invoice_amount,invoice_date,invoice_no,vehicle_in_date,
vehicle_out_date,ofasno,chequeno,chequeamt,chequedate,tdsamt,payment_approval_date,payment_approval_note)
  VALUES( %d,'%s','%s',%d,%d,'%s',%d,%d,%d,'%s','%s','%s','%s','%s','%s','%s','%s',%d,%d,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')";
            $SQL1 = sprintf($Query1, $main_id, $main->maintenance_date, $main->meter_reading, $main->dealer_id, $main->vehicleid, Sanitise::DateTime($today), $main->userid, $main->customerno, $accountRoleId, $main->notes, $main->approval_notes, $main->amount_quote, $main->file_name, $main->tyre_type, $main->battery_srno, $main->parts_list, $main->task_select_array, $main->category, $status, $main->submission_date, $main->approval_date, $main->payment_submission_date, $main->invoice_file_name, $main->invoice_amount, $main->invoice_date, $main->invoice_no, $main->vehicle_in_date, $main->vehicle_out_date, $slipno, $chequeno, $chequeamt, $chequedate, $tdsamt, Sanitise::DateTime($today), $note);

            $this->_databaseManager->executeQuery($SQL1);
        }
    }

    public function set_pushclose_fuel($mainid, $note, $slipno, $chequeno, $chequeamt, $chequedate, $tdsamt) {
        $today = date("Y-m-d H:i:s");
        $data = $this->get_fueltransaction_details($mainid);
        $note = $data->notes . " " . $note;

        $chequedate = date('Y-m-d', strtotime($chequedate));
        $Query = "UPDATE fuelstorrage SET timestamp='%s',notes='%s',ofasnumber ='%s',chequeno='%s',chequeamt='%s',chequedate='%s',tdsamt='%s',isclosed=%d WHERE fuelid =%d AND customerno =%d";
        $SQL = sprintf($Query, Sanitise::DateTime($today), $note, $slipno, $chequeno, $chequeamt, $chequedate, $tdsamt, 1, Sanitise::Long($mainid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function get_fueltransaction_details($mainid) {
        $main = array();
        $QUERY = "SELECT * FROM fuelstorrage WHERE fuelid=%d AND customerno=%d and isdeleted=0";
        $SQL = sprintf($QUERY, $main_id, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $data = new stdClass();
            $data->fuelid = $row['fuelid'];
            $data->notes = $row['notes'];
            return $data;
        }
    }

    public function set_transaction_status($main_id, $status, $note) {
        $today = date("Y-m-d H:i:s");
        //$main = $this->getlatest_maintenance_details($main_id);
        //$notes = $main->notes; 
        //$note = $notes ." ".$note; 

        $accountRoleId = 42;
        if ($status == '13' && $this->_Customerno == 118) {
            // roleid hard core as it should go to accounts for trigon
            $behalfid = 0;
            if ($main->userid != $_SESSION['userid']) {
                $behalfid = $_SESSION['userid'];
            }

            $Query = "UPDATE maintenance SET timestamp='%s',behalfid=%d,payment_approval_date='%s',payment_approval_note='%s',statusid=%d,roleid = %d WHERE id =%d AND customerno =%d";
            $SQL = sprintf($Query, Sanitise::DateTime($today), $behalfid, Sanitise::DateTime($today), $note, $status, $accountRoleId, Sanitise::Long($main_id), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);

            $Query1 = "INSERT INTO maintenance_history(maintananceid,maintenance_date,meter_reading,dealer_id,vehicleid,timestamp,userid,behalfid,customerno,roleid,notes,approval_notes,amount_quote,file_name,tyre_type,battery_srno,parts_list,task_select_array,category,statusid,submission_date,approval_date,payment_submission_date,invoice_file_name,invoice_amount,invoice_date,invoice_no,vehicle_in_date,vehicle_out_date,ofasno,payment_approval_date,payment_approval_note)
                        VALUES(%d,'%s', %d, %d, %d, '%s', %d, %d,%d, %d, '%s', '%s', %d, '%s', '%s', '%s', '%s','%s',%d, %d,'%s','%s', '%s','%s',%d, '%s', '%s', '%s','%s','%s','%s','%s')";
            $SQL1 = sprintf($Query1, $main_id, $main->maintenance_date, $main->meter_reading, $main->dealer_id, $main->vehicleid, Sanitise::DateTime($today), $main->userid, $main->behalfid, $main->customerno, $accountRoleId, $main->notes, $main->approval_notes, $main->amount_quote, $main->file_name, $main->tyre_type, $main->battery_srno, $main->parts_list, $main->task_select_array, $main->category, $status, $main->submission_date, $main->approval_date, $main->payment_submission_date, $main->invoice_file_name, $main->invoice_amount, $main->invoice_date, $main->invoice_no, $main->vehicle_in_date, $main->vehicle_out_date, $main->ofasno, Sanitise::DateTime($today), $note);
            $this->_databaseManager->executeQuery($SQL1);
            $responce = 'ok';
        } else if ($status == '13' && $this->_Customerno != 118) {
            $behalfid = 0;
            if ($main->userid != $_SESSION['userid']) {
                $behalfid = $_SESSION['userid'];
            }

            $Query = "UPDATE maintenance SET timestamp='%s',behalfid=%d,payment_approval_date='%s',payment_approval_note='%s',statusid=%d WHERE id =%d AND customerno =%d";
            $SQL = sprintf($Query, Sanitise::DateTime($today), $behalfid, Sanitise::DateTime($today), $note, $status, Sanitise::Long($main_id), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);

            $Query1 = "INSERT INTO maintenance_history(maintananceid,behalfid,maintenance_date,meter_reading,dealer_id,vehicleid,timestamp,userid,customerno,roleid,notes,approval_notes,amount_quote,file_name,tyre_type,battery_srno,parts_list,task_select_array,category,statusid,submission_date,approval_date,payment_submission_date,invoice_file_name,invoice_amount,invoice_date,invoice_no,vehicle_in_date,vehicle_out_date,ofasno,payment_approval_date,payment_approval_note)
                        VALUES(%d,%d,'%s', %d, %d, %d, '%s', %d, %d, %d, '%s', '%s', %d, '%s', '%s', '%s', '%s','%s',%d, %d,'%s','%s', '%s','%s',%d, '%s', '%s', '%s','%s','%s','%s','%s')";
            $SQL1 = sprintf($Query1, $main_id, $main->behalfid, $main->maintenance_date, $main->meter_reading, $main->dealer_id, $main->vehicleid, Sanitise::DateTime($today), $main->userid, $main->customerno, $main->roleid, $main->notes, $main->approval_notes, $main->amount_quote, $main->file_name, $main->tyre_type, $main->battery_srno, $main->parts_list, $main->task_select_array, $main->category, $status, $main->submission_date, $main->approval_date, $main->payment_submission_date, $main->invoice_file_name, $main->invoice_amount, $main->invoice_date, $main->invoice_no, $main->vehicle_in_date, $main->vehicle_out_date, $main->ofasno, Sanitise::DateTime($today), $note);
            $this->_databaseManager->executeQuery($SQL1);

            $responce = 'ok';
        }
        if ($status == '12') {
            $behalfid = 0;
            if ($main->userid != $_SESSION['userid']) {
                $behalfid = $_SESSION['userid'];
            }
            $Query = "UPDATE maintenance SET behalfid=%d,timestamp='%s',payment_approval_date='%s',payment_approval_note='%s',statusid=%d WHERE id =%d AND customerno =%d";
            $SQL = sprintf($Query, $behalfid, Sanitise::DateTime($today), Sanitise::DateTime($today), $note, $status, Sanitise::Long($main_id), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);

            $Query1 = "INSERT INTO maintenance_history(behalfid,maintananceid,maintenance_date,meter_reading,dealer_id,vehicleid,timestamp,userid,customerno,roleid,notes,approval_notes,amount_quote,file_name,tyre_type,battery_srno,parts_list,task_select_array,category,statusid,submission_date,approval_date,payment_submission_date,invoice_file_name,invoice_amount,invoice_date,invoice_no,vehicle_in_date,vehicle_out_date,ofasno,payment_approval_date,payment_approval_note)
                        VALUES(%d,'%s', %d, %d, %d, '%s', %d, %d, %d, '%s', '%s', %d, '%s', '%s', '%s', '%s','%s',%d, %d,'%s','%s', '%s','%s',%d, '%s', '%s', '%s','%s','%s','%s','%s')";
            $SQL1 = sprintf($Query1, $main->behalfid, $main_id, $main->maintenance_date, $main->meter_reading, $main->dealer_id, $main->vehicleid, Sanitise::DateTime($today), $main->userid, $main->customerno, $main->roleid, $main->notes, $main->approval_notes, $main->amount_quote, $main->file_name, $main->tyre_type, $main->battery_srno, $main->parts_list, $main->task_select_array, $main->category, $status, $main->submission_date, $main->approval_date, $main->payment_submission_date, $main->invoice_file_name, $main->invoice_amount, $main->invoice_date, $main->invoice_no, $main->vehicle_in_date, $main->vehicle_out_date, $main->ofasno, Sanitise::DateTime($today), $note);
            $this->_databaseManager->executeQuery($SQL1);
            /*
              $Query1 = "UPDATE maintenance_history SET timestamp='%s',payment_approval_date='%s',payment_approval_note='%s',statusid=%d WHERE maintananceid =%d AND customerno =%d";
              $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::DateTime($today), $note, $status, Sanitise::Long($main_id), $this->_Customerno);
              $this->_databaseManager->executeQuery($SQL1);
             *
             */
            $responce = 'ok';
        } elseif ($status == '8') {
            $behalfid = 0;
            if ($main->userid != $_SESSION['userid']) {
                $behalfid = $_SESSION['userid'];
            }
            $Query = "UPDATE maintenance SET behalfid=%d,timestamp='%s',approval_date='%s',approval_notes='%s',statusid=%d WHERE id =%d AND customerno =%d";
            $SQL = sprintf($Query, $behalfid, Sanitise::DateTime($today), Sanitise::DateTime($today), $note, $status, Sanitise::Long($main_id), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);

            $Query1 = "INSERT INTO maintenance_history(behalfid,maintananceid,maintenance_date,meter_reading,dealer_id,vehicleid,timestamp,userid,customerno,roleid,notes,approval_notes,amount_quote,file_name,tyre_type,battery_srno,parts_list,task_select_array,category,statusid,submission_date,approval_date)
                        VALUES(%d,%d,'%s', %d, %d, %d, '%s', %d, %d, %d, '%s', '%s', %d, '%s', '%s', '%s', '%s','%s',%d, %d,'%s','%s')";
            $SQL1 = sprintf($Query1, $main->behalfid, $main_id, $main->maintenance_date, $main->meter_reading, $main->dealer_id, $main->vehicleid, Sanitise::DateTime($today), $main->userid, $main->customerno, $main->roleid, $main->notes, $note, $main->amount_quote, $main->file_name, $main->tyre_type, $main->battery_srno, $main->parts_list, $main->task_select_array, $main->category, $status, $main->submission_date, Sanitise::DateTime($today));
            $this->_databaseManager->executeQuery($SQL1);
            /*
              $Query1 = "UPDATE maintenance_history SET timestamp='%s',approval_date='%s',approval_notes='%s',statusid=%d WHERE maintananceid =%d AND customerno =%d";
              $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::DateTime($today), $note, $status, Sanitise::Long($main_id), $this->_Customerno);
              $this->_databaseManager->executeQuery($SQL1);
             *
             */
            $responce = 'ok';
        } elseif ($status == '9') {
            $behalfid = 0;
            if ($main->userid != $_SESSION['userid']) {
                $behalfid = $_SESSION['userid'];
            }
            $Query = "UPDATE maintenance SET behalfid=%d,timestamp='%s',approval_notes='%s',statusid=%d WHERE id =%d AND customerno =%d";
            $SQL = sprintf($Query, $behalfid, Sanitise::DateTime($today), $note, $status, Sanitise::Long($main_id), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);

            $Query1 = "INSERT INTO maintenance_history(behalfid,maintananceid,maintenance_date,meter_reading,dealer_id,vehicleid,timestamp,userid,customerno,roleid,notes,approval_notes,amount_quote,file_name,tyre_type,battery_srno,parts_list,task_select_array,category,statusid,submission_date,approval_date)
                        VALUES(%d,%d,'%s', %d, %d, %d, '%s', %d, %d, %d, '%s', '%s', %d, '%s', '%s', '%s', '%s','%s',%d, %d,'%s','%s')";
            $SQL1 = sprintf($Query1, $behalfid, $main_id, $main->maintenance_date, $main->meter_reading, $main->dealer_id, $main->vehicleid, Sanitise::DateTime($today), $main->userid, $main->customerno, $main->roleid, $main->notes, $note, $main->amount_quote, $main->file_name, $main->tyre_type, $main->battery_srno, $main->parts_list, $main->task_select_array, $main->category, $status, $main->submission_date, Sanitise::DateTime($today));
            $this->_databaseManager->executeQuery($SQL1);
            /*
              $Query1 = "UPDATE maintenance_history SET timestamp='%s',approval_date='%s',statusid=%d WHERE maintananceid =%d AND customerno =%d";
              $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::DateTime($today), $status, Sanitise::Long($main_id), $this->_Customerno);
              $this->_databaseManager->executeQuery($SQL1);
             *
             */
            $responce = 'ok';
        } elseif ($status == '11') {
            $behalfid = 0;
            if ($main->userid != $_SESSION['userid']) {
                $behalfid = $_SESSION['userid'];
            }
            $Query = "UPDATE maintenance SET behalfid=%d,timestamp='%s',approval_notes='%s',statusid=%d WHERE id =%d AND customerno =%d";
            $SQL = sprintf($Query, $behalfid, Sanitise::DateTime($today), $note, $status, Sanitise::Long($main_id), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);

            $Query1 = "INSERT INTO maintenance_history(behalfid,maintananceid,maintenance_date,meter_reading,dealer_id,vehicleid,timestamp,userid,customerno,roleid,notes,approval_notes,amount_quote,file_name,tyre_type,battery_srno,parts_list,task_select_array,category,statusid,submission_date,approval_date)
                        VALUES(%d,%d,'%s', %d, %d, %d, '%s', %d, %d, %d, '%s', '%s', %d, '%s', '%s', '%s', '%s','%s',%d, %d,'%s','%s')";
            $SQL1 = sprintf($Query1, $behalfid, $main_id, $main->maintenance_date, $main->meter_reading, $main->dealer_id, $main->vehicleid, Sanitise::DateTime($today), $main->userid, $main->customerno, $main->roleid, $main->notes, $note, $main->amount_quote, $main->file_name, $main->tyre_type, $main->battery_srno, $main->parts_list, $main->task_select_array, $main->category, $status, $main->submission_date, Sanitise::DateTime($today));
            $this->_databaseManager->executeQuery($SQL1);

            $responce = 'ok';
        }
        if ($status == '13' && $_SESSION['customerno'] == 118) {
            // for battery srno
            $res = $this->get_newbatteryno($main_id, 13);
            if ($res != "error") {
                //print_r($res);
                $query = "UPDATE maintenance_mapbattery SET batt_serialno='%s' WHERE vehicleid=%d AND customerno=%d ";
                $sql = sprintf($query, $res->battno, $res->vehid, $this->_Customerno);
                $this->_databaseManager->executeQuery($sql);
                $responce = 'ok';
            } else {
                $responce = 'ok';
            }

            //for tyre srno
            $tyre_res = $this->getTyreSrno($main_id, 13);
            //print_r($tyre_res);
            if ($tyre_res != "tyre_error") {
                $vehicleid = $tyre_res->vehid;
                $type = $tyre_res->tyre;
                //print_r($type);
                $srno = explode(",", $type);
                $listarr = array();
                $ty = array();
                $ty1 = array();

                foreach ($srno as $key => $val) {
                    $ty = explode('-', $val);
                    $ty1[$ty[0]] = $ty[1];
                }
                $listarr[] = $ty1;
                //print_r($listarr);

                foreach ($listarr as $this_listarr) {
                    if (array_key_exists('Right Front', $this_listarr)) {
                        $query1 = "SELECT * FROM maintenance_maptyre WHERE vehicleid=%d AND customerno=%d AND tyreid=1";
                        $sql1 = sprintf($query1, $vehicleid, $this->_Customerno);
                        $this->_databaseManager->executeQuery($sql1);
                        if ($this->_databaseManager->get_rowCount() > 0) {
                            $query2 = "UPDATE maintenance_maptyre SET serialno='%s'  WHERE vehicleid=%d AND customerno=%d AND tyreid=1";
                            $sql2 = sprintf($query2, $this_listarr['Right Front'], $vehicleid, $this->_Customerno);
                            $this->_databaseManager->executeQuery($sql2);
                        } else {
                            $query3 = "INSERT INTO maintenance_maptyre(vehicleid,customerno,tyreid,serialno) VALUES (%d,%d,%d,'%s')";
                            $sql3 = sprintf($query3, $vehicleid, $this->_Customerno, 1, Sanitise::String($this_listarr['Right Front']));
                            $this->_databaseManager->executeQuery($sql3);
                        }
                    }

                    if (array_key_exists('Left Front', $this_listarr)) {
                        $query7 = "SELECT * FROM maintenance_maptyre WHERE vehicleid=%d AND customerno=%d AND tyreid=2";
                        $sql7 = sprintf($query7, $vehicleid, $this->_Customerno);
                        $this->_databaseManager->executeQuery($sql7);
                        if ($this->_databaseManager->get_rowCount() > 0) {
                            $query8 = "UPDATE maintenance_maptyre SET serialno='%s'  WHERE vehicleid=%d AND customerno=%d AND tyreid=2";
                            $sql8 = sprintf($query8, $this_listarr['Left Front'], $vehicleid, $this->_Customerno);
                            $this->_databaseManager->executeQuery($sql8);
                        } else {
                            $query9 = "INSERT INTO maintenance_maptyre(vehicleid,customerno,tyreid,serialno) VALUES (%d,%d,%d,'%s')";
                            $sql9 = sprintf($query9, $vehicleid, $this->_Customerno, 2, Sanitise::String($this_listarr['Left Front']));
                            $this->_databaseManager->executeQuery($sql9);
                        }
                    }

                    if (array_key_exists('Right Back Out', $this_listarr)) {
                        $query10 = "SELECT * FROM maintenance_maptyre WHERE vehicleid=%d AND customerno=%d AND tyreid=3";
                        $sql10 = sprintf($query10, $vehicleid, $this->_Customerno);
                        $this->_databaseManager->executeQuery($sql10);
                        if ($this->_databaseManager->get_rowCount() > 0) {
                            $query11 = "UPDATE maintenance_maptyre SET serialno='%s'  WHERE vehicleid=%d AND customerno=%d AND tyreid=3";
                            $sql11 = sprintf($query11, $this_listarr['Right Back Out'], $vehicleid, $this->_Customerno);
                            $this->_databaseManager->executeQuery($sql11);
                        } else {
                            $query12 = "INSERT INTO maintenance_maptyre(vehicleid,customerno,tyreid,serialno) VALUES (%d,%d,%d,'%s')";
                            $sql12 = sprintf($query12, $vehicleid, $this->_Customerno, 3, Sanitise::String($this_listarr['Right Back Out']));
                            $this->_databaseManager->executeQuery($sql12);
                        }
                    }

                    if (array_key_exists('Right Back In', $this_listarr)) {
                        $query13 = "SELECT * FROM maintenance_maptyre WHERE vehicleid=%d AND customerno=%d AND tyreid=4";
                        $sql13 = sprintf($query13, $vehicleid, $this->_Customerno);
                        $this->_databaseManager->executeQuery($sql13);
                        if ($this->_databaseManager->get_rowCount() > 0) {
                            $query14 = "UPDATE maintenance_maptyre SET serialno='%s'  WHERE vehicleid=%d AND customerno=%d AND tyreid=4";
                            $sql14 = sprintf($query14, $this_listarr['Right Back In'], $vehicleid, $this->_Customerno);
                            $this->_databaseManager->executeQuery($sql14);
                        } else {
                            $query15 = "INSERT INTO maintenance_maptyre(vehicleid,customerno,tyreid,serialno) VALUES (%d,%d,%d,'%s')";
                            $sql15 = sprintf($query15, $vehicleid, $this->_Customerno, 4, Sanitise::String($this_listarr['Right Back In']));
                            $this->_databaseManager->executeQuery($sql15);
                        }
                    }

                    if (array_key_exists('Stepney', $this_listarr)) {
                        $query16 = "SELECT * FROM maintenance_maptyre WHERE vehicleid=%d AND customerno=%d AND tyreid=5";
                        $sql16 = sprintf($query16, $vehicleid, $this->_Customerno);
                        $this->_databaseManager->executeQuery($sql16);
                        if ($this->_databaseManager->get_rowCount() > 0) {
                            $query17 = "UPDATE maintenance_maptyre SET serialno='%s'  WHERE vehicleid=%d AND customerno=%d AND tyreid=5";
                            $sql17 = sprintf($query17, $this_listarr['Stepney'], $vehicleid, $this->_Customerno);
                            $this->_databaseManager->executeQuery($sql17);
                        } else {
                            $query18 = "INSERT INTO maintenance_maptyre(vehicleid,customerno,tyreid,serialno) VALUES (%d,%d,%d,'%s')";
                            $sql18 = sprintf($query18, $vehicleid, $this->_Customerno, 5, Sanitise::String($this_listarr['Stepney']));
                            $this->_databaseManager->executeQuery($sql18);
                        }
                    }

                    if (array_key_exists('Left Back Out', $this_listarr)) {
                        $query19 = "SELECT * FROM maintenance_maptyre WHERE vehicleid=%d AND customerno=%d AND tyreid=6";
                        $sql19 = sprintf($query19, $vehicleid, $this->_Customerno);
                        $this->_databaseManager->executeQuery($sql19);
                        if ($this->_databaseManager->get_rowCount() > 0) {
                            $query20 = "UPDATE maintenance_maptyre SET serialno='%s'  WHERE vehicleid=%d AND customerno=%d AND tyreid=6";
                            $sql20 = sprintf($query20, $this_listarr['Left Back Out'], $vehicleid, $this->_Customerno);
                            $this->_databaseManager->executeQuery($sql20);
                        } else {
                            $query21 = "INSERT INTO maintenance_maptyre(vehicleid,customerno,tyreid,serialno) VALUES (%d,%d,%d,'%s')";
                            $sql21 = sprintf($query21, $vehicleid, $this->_Customerno, 6, Sanitise::String($this_listarr['Left Back Out']));
                            $this->_databaseManager->executeQuery($sql21);
                        }
                    }
                    if (array_key_exists('Left Back In', $this_listarr)) {
                        $query22 = "SELECT * FROM maintenance_maptyre WHERE vehicleid=%d AND customerno=%d AND tyreid=7";
                        $sql22 = sprintf($query22, $vehicleid, $this->_Customerno);
                        $this->_databaseManager->executeQuery($sql22);
                        if ($this->_databaseManager->get_rowCount() > 0) {
                            $query23 = "UPDATE maintenance_maptyre SET serialno='%s'  WHERE vehicleid=%d AND customerno=%d AND tyreid=7";
                            $sql23 = sprintf($query23, $this_listarr['Left Back In'], $vehicleid, $this->_Customerno);
                            $this->_databaseManager->executeQuery($sql23);
                        } else {
                            $query24 = "INSERT INTO maintenance_maptyre(vehicleid,customerno,tyreid,serialno) VALUES (%d,%d,%d,'%s')";
                            $sql24 = sprintf($query24, $vehicleid, $this->_Customerno, 7, Sanitise::String($this_listarr['Left Back In']));
                            $this->_databaseManager->executeQuery($sql24);
                        }
                    }
                }
            } else {
                $responce = 'ok';
            }
        }
        return $responce;
    }

    public function set_acc_status($main_id, $status, $note) {
        //$main = $this->getlatest_acc_details($main_id);
        //$notes = $main->notes;  
        //$note = $notes ." ".$note; 
        $today = date("Y-m-d H:i:s");
        $Query = "UPDATE accident SET timestamp='%s',approval_date='%s',approval_notes='%s',statusid=%d WHERE id =%d AND customerno =%d";
        $SQL = sprintf($Query, Sanitise::DateTime($today), Sanitise::DateTime($today), $note, $status, Sanitise::Long($main_id), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);

        $Query1 = "UPDATE accident_history SET timestamp='%s',approval_date='%s',approval_notes='%s',statusid=%d WHERE accidentid =%d AND customerno =%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::DateTime($today), $note, $status, Sanitise::Long($main_id), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL1);
    }

    public function getMaintenanceHistory($maintenanceid) {
        $historys = array();
        $Query = "SELECT * FROM maintenance where customerno=%d AND id = %d AND isdeleted=0 order by id ASC ";
        $SQL = sprintf($Query, $this->_Customerno, $maintenanceid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $history = new VOMaintanance();
                $history->maintenance_dae = $row['maintenance_date'];
                $history->vehicle_in = $row['vehicle_in_date'];
                $history->vehicle_out = $row['vehicle_out_date'];
                $history->invoice_date = $row['invoice_date'];
                $history->invoice_no = $row['invoice_no'];
                $history->timestamp = $row['timestamp'];
                $history->userid = $row['userid'];
                $history->statusid = $row['statusid'];
                $history->submission_date = $row['submission_date'];
                $historys[] = $history;
            }
        }
        return $historys;
    }

    function get_veh_mnt_history($vehicleid, $type) {
        $vehid = (int) $vehicleid;
        $typeid = (int) $type;
        if ($type == 2) {
            $typeid = "2,3";
        } else {
            $typeid = (int) $type;
        }

        $query = "SELECT m.id,m.battery_srno ,m.meter_reading, m.tyre_type, m.parts_list, m.task_select_array, d.name as dname, ms.name as msname, m.notes, m.file_name, m.amount_quote, m.timestamp as mdate,m.category,
           m.invoice_no,m.invoice_date,m.invoice_amount";
        if ($type == 5) {
            $query .= " ,group_concat( acc.name) as access";
        }
        if ($type == 1) {
            $query .= " ,mt.repairtype,tr.tyrerepair_mapid";
        }
        $query .= " FROM maintenance as m";
        $query .= " left join dealer as d on d.dealerid=m.dealer_id";
        $query .= " inner join maintenance_status as ms on ms.id=m.statusid";
        if ($type == 1) {
            $query .= " left outer join maintenance_tyre_repair_mapping as tr on m.id = tr.maintenanceid and tr.isdeleted=0";
            $query .= " left outer join maintenance_tyre_repair_type as mt on tr.tyrerepairid = mt.tyrerepairid and mt.isdeleted=0";
        }
        if ($type == 5) {
            $query .= " left join accessory_map as am on m.id=am.maintenanceid and am.isdeleted=0";
            $query .= " left join accessory as acc on acc.id=am.accessoryid and acc.isdeleted=0";
        }
        $query .= " where m.customerno=$this->_Customerno and m.category IN ($typeid) and m.vehicleid=$vehid and m.isdeleted=0";
        if ($type == 5) {
            $query .= " group by m.id";
        }
        $query .= " ORDER BY m.id DESC";

        //echo $query;
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $historys = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $history = new stdClass();
                $history->categoryid = $row['category'];
                if ($type == 0) {
                    $history->cat = "Battery";
                    $history->transid = "B00" . $row['id'];
                }
                if ($type == 1) {
                    $history->cat = "Tyre";
                    $history->transid = "T00" . $row['id'];
                    if ($row['repairtype'] == '') {
                        $history->repairtype = "New";
                    } else {
                        $history->repairtype = $row['repairtype'];
                    }
                    $history->maprepairtypeid = $row['tyrerepair_mapid'];
                }
                if ($history->categoryid == 2) {
                    $history->cat = "Repair";
                    $history->transid = "R00" . $row['id'];
                }
                if ($history->categoryid == 3) {
                    $history->cat = "Service";
                    $history->transid = "S00" . $row['id'];
                }

                if ($type == 5) {
                    $history->cat = "Accessories";
                    $history->transid = "A00" . $row['id'];
                }
                $history->meter_reading = $row['meter_reading'];
                $history->dname = $row['dname'];
                $history->msname = $row['msname'];
                $history->notes = $row['notes'];
                $history->file_name = $row['file_name'];
                $history->amount_quote = $row['amount_quote'];
                $history->mdate = $row['mdate'];
                $history->tyre = $row['tyre_type'];
                $history->parts = $row['parts_list'];
                $history->tasks = $row['task_select_array'];
                $history->access = ri($row['access']);
                $history->mid = $row['id'];
                $history->invno = $row['invoice_no'];
                $history->battery_srno = $row['battery_srno'];
                $history->invamt = $row['invoice_amount'];

                if ($row['invoice_date'] == '0000-00-00' || $row['invoice_date'] == '1970-01-01') {
                    $history->invdate = '';
                } else {
                    $history->invdate = date("d-m-Y", strtotime($row['invoice_date']));
                }
                $historys[] = $history;
            }
            return $historys;
        } else {
            return null;
        }
    }

    public function getbatteryno_byvehicle($vehid) {

        $Query = "SELECT * FROM maintenance
                    WHERE vehicleid=%d AND customerno=%d AND category =0 ORDER BY id DESC LIMIT 1";

        $maintananceQuery = sprintf($Query, $vehid, $this->_Customerno);

        $this->_databaseManager->executeQuery($maintananceQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            //$maintanance = new VOMaintanance();
            return $row['battery_srno'];
        }
    }

    function get_accident_history($vehicleid) {
        $vehid = (int) $vehicleid;
        $query = "SELECT a.* from accident as a";
        $query .= " where a.vehicleid=$vehid and a.isdeleted=0";
        $query .= " ORDER BY a.id DESC";

        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $historys = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $history = new stdClass();
                $history->transid = "AC00" . $row['id'];
                $history->accident_datetime = $row['accident_datetime'];
                $history->accident_location = $row['accident_location'];
                $history->tpi_pd = $row['thirdparty_insured'] == 0 ? 'No' : 'Yes';
                $history->description = $row['description'];
                $history->drivername = $row['drivername'];
                $history->lvfrom = $row['licence_validity_from'];
                $history->lvto = $row['licence_validity_to'];
                $history->licence_type = $row['licence_type'];
                $history->workshop_location = $row['workshop_location'];
                $history->loss_amount = $row['loss_amount'];
                $history->sett_amount = $row['sett_amount'];
                $history->actual_amount = $row['actual_amount'];
                $history->mahindra_amount = $row['mahindra_amount'];

                $historys[] = $history;
            }
            return $historys;
        } else {
            return null;
        }
    }

    function get_transaction_details($vehicleid, $categoryid, $dealerid, $sdate1, $edate1, $statusid, $invoiceno = NULL) {
        $vehid = (int) $vehicleid;
        $typeid = (int) $categoryid;
        if ($sdate1 != "" && $edate1 != "") {
            $start_date = date("Y-m-d H:i:s", $sdate1);
            $end_date = date("Y-m-d H:i:s", $edate1);
        }
        $vm = new VehicleManager($_SESSION['customerno']);
        $groups = $vm->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);

        $query = "SELECT m.ofasno,m.chequeno,m.chequeamt,m.tdsamt,m.statusid,m.battery_srno,g.groupname,m.id,make.name as makename,model.name as modelname,m.vehicle_out_date, m.meter_reading, m.tyre_type, m.parts_list, m.task_select_array, d.name as dname, ms.name as msname, m.notes, m.file_name, m.amount_quote, m.timestamp,v.vehicleno,m.category,m.invoice_no,m.invoice_amount,m.invoice_date";
        if ($typeid == 5) {
            $query .= " ,group_concat( acc.name) as access";
        }

        $query .= " FROM maintenance as m";
        $query .= " left join dealer as d on d.dealerid=m.dealer_id";
        $query .= " inner join maintenance_status as ms on ms.id=m.statusid";
        $query .= " inner join vehicle as v on v.vehicleid=m.vehicleid";
        $query .= " left join `group` as g on g.groupid = v.groupid";
        $query .= " left join model on model.model_id= v.modelid ";
        $query .= " left join make on make.id= model.make_id ";
        if ($typeid == 5) {
            $query .= " left join accessory_map as am on m.id=am.maintenanceid and am.isdeleted=0";
            $query .= " left join accessory as acc on acc.id=am.accessoryid and acc.isdeleted=0";
        }
        $query .= " where m.customerno=$this->_Customerno AND m.isdeleted=0";
        if (isset($start_date) && isset($end_date)) {
            //$query .= " and m.invoice_date BETWEEN '$start_date' AND '$end_date' ";
            $query .= " and m.`timestamp` BETWEEN '$start_date' AND '$end_date'";
        }
        if ($categoryid != '-1' && $categoryid != '-2') {
            $query .=" and m.category=$categoryid";
        }

        if ($categoryid == '-2') {
            $query .=" and m.category IN (0,1,2,3,4,5)";
        }

        if ($dealerid != '-1' && $dealerid != '0') {
            $query .=" and m.dealer_id=$dealerid";
        }

        if ($dealerid == '0') {
            $dm = new DealerManager($_SESSION['customerno']);
            $alldealer = $dm->get_all_dealers();
            if (isset($alldealer)) {
                $dealerarr = array();
                foreach ($alldealer as $alldealers) {
                    $dealerarr[] = $alldealers->dealerid;
                }
                $dealerstr = implode(",", $dealerarr);
                $query .= " AND m.dealer_id IN (" . $dealerstr . ")";
            }
        }

        if ($statusid != '-1' && $statusid != '00') {
            $query .=" and m.statusid=$statusid";
        }

        if ($statusid == '00') {
            $query .=" and m.statusid IN (7,8,9,10,11,12,13,14,15)";
        }

        if ($vehid != "0") {
            $query .=" and m.vehicleid=$vehicleid";
        }
        if ($invoiceno != "") {
            $query .=" and m.invoice_no='" . $invoiceno . "'";
        }
        if ($_SESSION['groupid'] != 0) {
            $query .=" and v.groupid='" . $_SESSION['groupid'] . "'";
        } else {
            if ($groups[0] != 0) {
                $groupid_ids = implode(',', $groups);
                $query.=" AND v.groupid in (" . $groupid_ids . ") ";
            }
        }
        if ($typeid == 5) {
            $query .= " group by m.id";
        }
        $query .= " ORDER BY m.id DESC";

        //echo $query;

        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $historys = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $history = new stdClass();
                $history->meter_reading = $row['meter_reading'];
                $history->modelname = $row['modelname'];
                $history->makename = $row['makename'];
                $history->vehicleno = $row['vehicleno'];
                if ($row['category'] == 0) {
                    $history->cat = "Battery";
                    $history->transid = "B00" . $row['id'];
                }
                if ($row['category'] == 1) {
                    $history->cat = "Tyre";
                    $history->transid = "T00" . $row['id'];
                }
                if ($row['category'] == 2) {
                    $history->cat = "Repair";
                    $history->transid = "R00" . $row['id'];
                }
                if ($row['category'] == 3) {
                    $history->cat = "Service";
                    $history->transid = "S00" . $row['id'];
                }
                if ($row['category'] == 5) {
                    $history->cat = "Accessories";
                    $history->transid = "A00" . $row['id'];
                }
                $history->dname = $row['dname'];
                $history->msname = $row['msname'];
                $history->notes = $row['notes'];
                $history->file_name = $row['file_name'];
                $history->amount_quote = $row['amount_quote'];
                $history->mdate = date("d-m-Y", strtotime($row['timestamp']));
                $history->tyre = $row['tyre_type'];
                $history->parts = $row['parts_list'];
                $history->tasks = $row['task_select_array'];
                $history->access = ri($row['access']);
                $history->mid = $row['id'];
                $history->invoice_no = ri($row['invoice_no']);
                $history->invoice_amount = ri($row['invoice_amount']);
                $history->battery_srno = ri($row['battery_srno']);
                $history->tyre_type = ri($row['tyre_type']);
                $history->groupname = ri($row['groupname']);
                $history->slipno = ri($row['ofasno']);
                $history->chqno = ri($row['chequeno']);
                $history->chequeamt = ri($row['chequeamt']);
                $history->tdsamt = ri($row['tdsamt']);

                if ($row['invoice_date'] == '0000-00-00' || $row['invoice_date'] == '1970-01-01') {
                    $history->invoice_date = '';
                } else {
                    $history->invoice_date = date("d-m-Y", strtotime($row['invoice_date']));
                }

                if ($row['vehicle_out_date'] == '0000-00-00' || $row['vehicle_out_date'] == '1970-01-01') {
                    $history->vehicle_out_date = '';
                } else {
                    $history->vehicle_out_date = date("d-m-Y", strtotime($row['vehicle_out_date']));
                }

                $historys[] = $history;
            }
            return $historys;
        } else {
            return null;
        }
    }

    public function getlatest_acc_details($main_id) {
        $main = array();
        $QUERY = "SELECT approval_notes FROM accident WHERE id=%d AND customerno=%d and isdeleted=0";
        $SQL = sprintf($QUERY, $main_id, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $data = new stdClass();
            $data->notes = $row['approval_notes'];
            return $data;
        }
    }

    public function getlatest_maintenance_details($main_id) {
        $main = array();
        $QUERY = "SELECT * FROM maintenance WHERE id=%d AND customerno=%d and isdeleted=0";
        $SQL = sprintf($QUERY, $main_id, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $data = new VOMaintanance();
            $data->maintenance_date = $row['maintenance_date'];
            $data->meter_reading = $row['meter_reading'];
            $data->vehicle_in_date = $row['vehicle_in_date'];
            $data->vehicle_out_date = $row['vehicle_out_date'];
            $data->dealer_id = $row['dealer_id'];
            $data->invoice_date = $row['invoice_date'];
            $data->invoice_no = $row ['invoice_no'];
            $data->invoice_amount = $row['invoice_amount'];
            $data->tax = $row['tax'];
            $data->payment_id = $row['payment_id'];
            $data->vehicleid = $row['vehicleid'];
            $data->userid = $row['userid'];
            $data->statusid = $row['statusid'];
            $data->customerno = $row['customerno'];
            $data->roleid = $row['roleid'];
            $data->notes = $row['notes'];
            $data->approval_notes = $row['approval_notes'];
            $data->amount_quote = $row['amount_quote'];
            $data->file_name = $row['file_name'];
            $data->invoice_file_name = $row['invoice_file_name'];
            $data->tyre_type = $row['tyre_type'];
            $data->battery_srno = $row['battery_srno'];
            $data->parts_list = $row['parts_list'];
            $data->task_select_array = $row['task_select_array'];
            $data->category = $row['category'];
            $data->submission_date = $row['submission_date'];
            $data->approval_date = $row['approval_date'];
            $data->ofasno = $row['ofasno'];
            $data->payment_approval_date = $row['payment_approval_date'];
            $data->payment_submission_date = $row['payment_submission_date'];
            $data->payment_approval_note = $row['payment_approval_note'];
            $data->behalfid = $row['behalfid'];
            return $data;
        }
    }

    function get_tyre_names($tyre_arr, $tyre) {
        $tcsv = '';
        if ($tyre) {
            $t_arr = explode(',', $tyre);
            $f_arr = array();
            foreach ($t_arr as $t) {
                $f_arr[] = ri($tyre_arr[$t]);
            }
            $tcsv = implode(', ', $f_arr);
        }
        return $tcsv;
    }

    function get_parts_name($parts, $thispart) {
        $pcsv = '';
        if ($parts) {
            $p_arr = array_filter(explode(',', $thispart));
            if (!empty($p_arr)) {
                $f_arr = array();
                foreach ($p_arr as $p) {
                    if (ri($parts[$p]->part_name) != '') {
                        $f_arr[] = ri($parts[$p]->part_name);
                    }
                }
                $pcsv = implode(', ', $f_arr);
            }
        }
        return $pcsv;
    }

    function get_task_name($tasks, $thistask) {
        $tcsv = '';
        if ($tasks) {
            $t_arr = array_filter(explode(',', $thistask));
            if (!empty($t_arr)) {
                $f_arr = array();
                foreach ($t_arr as $t) {
                    if (ri($tasks[$t]->task_name) != '') {
                        $f_arr[] = ri($tasks[$t]->task_name);
                    }
                }
                $tcsv = implode(', ', $f_arr);
            }
        }
        return $tcsv;
    }

    public function get_all_part() {
        $parts = Array();
        $Query = "SELECT * FROM `parts` where customerno in(0,%d) AND isdeleted=0 order by part_name ASC";
        $GroupsQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($GroupsQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $part = new VOMaintanance();
                $part->id = $row['id'];
                $part->part_name = $row['part_name'];
                $part->customerno = $row['customerno'];
                $parts[$row['id']] = $part;
            }
            return $parts;
        }
        return null;
    }

    public function get_all_task() {
        $Tasks = Array();
        $Query = "SELECT * FROM `task` where customerno in(0,%d) AND isdeleted=0 ORDER BY task_name ASC";
        $GroupsQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($GroupsQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $Task = new VOMaintanance();
                $Task->id = $row['id'];
                $Task->task_name = $row['task_name'];
                $Task->customerno = $row['customerno'];
                $Tasks[$row['id']] = $Task;
            }
            return $Tasks;
        }
        return null;
    }

    public function getpartsby_maintenanceid($main_id) {
        $part_arr = array();
        $Query = "SELECT * FROM `maintenance_parts`
                  INNER JOIN parts ON maintenance_parts.partid = parts.id
                  where mid=%d";
        $GroupsQuery = sprintf($Query, Sanitise::Long($main_id));
        $this->_databaseManager->executeQuery($GroupsQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                //$part = new VOTask();
                $part = $row['partid'];
                $part_name = $row['part_name'];
                $unit_price = $row['amount'];
                $quantity = $row['qty'];
                $total = $row['total'];
                $part_arr[] = $part_name . "-" . " U : " . $unit_price . ", Q : " . $quantity . ", T : " . $total;
            }
        }
        return $part_arr;
    }

    public function gettaskby_maintenanceid($main_id) {
        $task_arr = array();
        $Query = "SELECT * FROM `maintenance_tasks`
                  INNER JOIN task ON maintenance_tasks.partid = task.id
                  where mid=%d";
        $SQL = sprintf($Query, Sanitise::Long($main_id));
        $this->_databaseManager->executeQuery($SQL);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                //$part = new VOTask();
                $task = $row['partid'];
                $task_name = $row['task_name'];
                $unit_price = $row['amount'];
                $quantity = $row['qty'];
                $total = $row['total'];
                $task_arr[] = $task_name . "-" . " U : " . $unit_price . ", Q : " . $quantity . ", T : " . $total;
            }
        }
        return $task_arr;
    }

    public function get_make_model($vehicleid) {
        $data = array();
        $Query = "SELECT make.name as makename,model.name as modelname,v.vehicleno,v.vehicleid FROM vehicle as v
                inner join model on model.model_id= v.modelid 
                inner join make on make.id= model.make_id 
                where 
                v.vehicleid = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $data[] = array(
                    "makename" => $row['makename'],
                    "modelname" => $row['modelname']
                );
            }
        }
        return $data;
    }

    public function DeleteTaskpopup($taskid) {
        $Query = "Delete from `maintenance_tasks` WHERE `m_id` =" . $taskid;
        $SQL = sprintf($Query);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function DeletePartspopup($partsid) {
        $Query = "Delete from `maintenance_parts` WHERE `m_id` = " . $partsid;
        $SQL = sprintf($Query, Sanitise::Long($partsid));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function edittaskpopup($data) {
        $Query = "UPDATE maintenance_tasks SET qty='%s', amount='%s',  discount='%s', total='%s' WHERE m_id =%d ";
        $SQL = sprintf($Query, Sanitise::String($data['partqty']), Sanitise::String($data['partamount']), Sanitise::String($data['partdisc']), Sanitise::String($data['parttot']), Sanitise::Long($data['pid']));
        $this->_databaseManager->executeQuery($SQL);
        return true;
    }

    public function editpartspopup($data) {
        $Query = "UPDATE maintenance_parts SET qty='%s', amount='%s',  discount='%s', total='%s' WHERE m_id =%d ";
        $SQL = sprintf($Query, Sanitise::String($data['partqty']), Sanitise::String($data['partamount']), Sanitise::String($data['partdisc']), Sanitise::String($data['parttot']), Sanitise::Long($data['pid']));
        $this->_databaseManager->executeQuery($SQL);
        return true;
    }

    public function addpartspopup($data, $tid) {
        $parts = "";
        $accessory_list = explode(",", $data);
        if (!empty($data)) {
            $total_amt = 0;
            $tax = 0;
            $disc = 0;
            $tot = 0;
            foreach ($accessory_list as $this_acc) {
                $this_acc_details = explode("-", $this_acc);
                $tot = $this_acc_details[1] * $this_acc_details[2];
                $disc = $this_acc_details[3] * $this_acc_details[2];
                $total_amt = $this_acc_details[4];
                $Query = "INSERT INTO `maintenance_parts`(mid,partid,qty,amount,tax_amount,discount,total,flag) VALUES (%d,%d,'%s','%s','%s','%s','%s',1)";
                $SQL = sprintf($Query, Sanitise::Long($tid), $this_acc_details[0], Sanitise::String($this_acc_details[2]), Sanitise::String($this_acc_details[1]), Sanitise::String($this_acc_details[4]), Sanitise::String($this_acc_details[3]), Sanitise::String($total_amt));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        return true;
    }

    public function addtaskspopup($data, $tid) {
        $tasks = "";
        $tot = 0;
        $total_amt = 0;
        $tax = 0;
        $disc = 0;
        $accessory_list1 = explode(",", $data);
        if (!empty($data)) {
            foreach ($accessory_list1 as $this_acc) {
                $this_acc_details = explode("-", $this_acc);
                $tot = $this_acc_details[1] * $this_acc_details[2];
                $disc = $this_acc_details[3] * $this_acc_details[2];
                $total_amt = $this_acc_details[4];
                $Query = "INSERT INTO `maintenance_tasks` (mid,partid,qty,amount,tax_amount,discount,total,flag) VALUES (%d,%d,%s,'%s',%s,'%s','%s',2)";
                $SQL = sprintf($Query, Sanitise::Long($tid), $this_acc_details[0], Sanitise::String($this_acc_details[2]), Sanitise::String($this_acc_details[1]), Sanitise::String($this_acc_details[4]), Sanitise::String($this_acc_details[3]), Sanitise::String($total_amt));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        return true;
    }

    public function updateTransdetails_vehicle($data) {
        $tid = $data['tid'];
        $Query = "UPDATE `maintenance` set meter_reading='%s' WHERE id=%d";
        $SQL = sprintf($Query, Sanitise::String($data['meterreading']), Sanitise::Long($tid));
        $this->_databaseManager->executeQuery($SQL);
        return true;
    }

    public function updatetaxamt($data) {
        $tid = $data['tid'];
        $Query = "UPDATE `maintenance` set tax='%s' WHERE id=%d";
        $SQL = sprintf($Query, Sanitise::String($data['taxamt']), Sanitise::Long($tid));
        $this->_databaseManager->executeQuery($SQL);
        return true;
    }

    public function updateinvamt($data) {
        $tid = $data['tid'];
        $Query = "UPDATE `maintenance` set invoice_amount='%s' WHERE id=%d";
        $SQL = sprintf($Query, Sanitise::String($data['invamt']), Sanitise::Long($tid));
        $this->_databaseManager->executeQuery($SQL);
        return true;
    }

    public function updateinvamt_get($data) {
        $invamt = "";
        $tid = $data['tid'];
        $Query = "UPDATE `maintenance` set invoice_amount='%s'  WHERE invoice_amount<>'%s'  AND id=%d";
        $SQL = sprintf($Query, Sanitise::String($data['invamt']), Sanitise::String($data['invamt']), Sanitise::Long($tid));
        $this->_databaseManager->executeQuery($SQL);

        $Query1 = "select invoice_amount from `maintenance` WHERE id=%d";
        $SQL1 = sprintf($Query1, Sanitise::Long($tid));
        $this->_databaseManager->executeQuery($SQL1);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $invamt = $row['invoice_amount'];
            }
        }
        return $invamt;
    }

    public function updatetransdetails($data) {
        $tid = $data['tid'];
        // add directories
        // $notes = $this->gettransactiondetails($tid); 
        $note = $data['transnotes'];

        $Query = "UPDATE `maintenance` set dealer_id='%d',notes='%s' WHERE id=%d";
        $SQL = sprintf($Query, Sanitise::Long($data['dealer']), Sanitise::String($note), Sanitise::Long($tid));
        $this->_databaseManager->executeQuery($SQL);
        return true;
    }

    public function gettransactiondetails($tid) {
        $Query1 = "select notes from `maintenance` WHERE id=%d";
        $SQL1 = sprintf($Query1, Sanitise::Long($tid));
        $this->_databaseManager->executeQuery($SQL1);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $notes = $row['notes'];
            }
        }
        return $notes;
    }

    public function updateqtndetails($data, $filedata) {
        $file_name_array = array();
        $tid = $data['tid'];
        $statusid = $data['statusid'];
        $vehicleid = $data['vehicleid'];
        $category = $data['category'];
        $mainid = $data['tid'];
        $qtnamt = $data['qtnamt'];
        // add directories
        $uploaddir = "../../customer/" . $this->_Customerno . "/vehicleid/" . $vehicleid . "/";
        $vehiclefolder = "../../customer/" . $this->_Customerno . "/vehicleid/";

        if (!file_exists($vehiclefolder)) {
            mkdir("../../customer/" . $this->_Customerno . "/vehicleid/", 0777);
        }
        $vehicleidfolder = "../../customer/" . $this->_Customerno . "/vehicleid/" . $vehicleid . "/";
        if (!file_exists($vehicleidfolder)) {
            mkdir("../../customer/" . $this->_Customerno . "/vehicleid/" . $vehicleid, 0777);
        }
        $error = false;
        if (!empty($filedata)) {
            foreach ($filedata as $file) {
                $filename = $uploaddir . basename($file['name']);
                $path_parts = pathinfo($filename);
                $ext = $path_parts['extension'];
                if ($ext == "pdf" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pjpeg" || $ext == "x-png") {
                    if (move_uploaded_file($file['tmp_name'], $uploaddir . '_quote.' . $ext)) {
                        $files[] = $uploaddir . $file['name'];
                    } else {
                        $error = true;
                        $resp['status'] = false;
                        $resp['status_msg'] = "File type no valid";
                    }
                }
            }
            $resp['status'] = true;
            $oldq1 = $uploaddir . '_quote.pdf';
            $oldq2 = $uploaddir . '_quote.png';
            $oldq3 = $uploaddir . '_quote.jpg';
            $oldq4 = $uploaddir . '_quote.jpeg';
            $newq1 = $uploaddir . $mainid . "_" . $category . '_quote.pdf';
            $newq2 = $uploaddir . $mainid . "_" . $category . '_quote.png';
            $newq3 = $uploaddir . $mainid . "_" . $category . '_quote.jpg';
            $newq4 = $uploaddir . $mainid . "_" . $category . '_quote.jpeg';
            if (file_exists($oldq1)) {
                rename($oldq1, $newq1);
                $file_name_array[] = $mainid . "_" . $category . '_quote.pdf';
            }
            if (file_exists($oldq2)) {
                rename($oldq2, $newq2);
                $file_name_array[] = $mainid . "_" . $category . '_quote.png';
            }
            if (file_exists($oldq3)) {
                rename($oldq3, $newq3);
                $file_name_array[] = $mainid . "_" . $category . '_quote.jpg';
            }
            if (file_exists($oldq4)) {
                rename($oldq4, $newq4);
                $file_name_array[] = $mainid . "_" . $category . '_quote.jpeg';
            }
            $this->update_file_names_for_transaction($mainid, $file_name_array, $statusid);
        }

        $Query = "UPDATE `maintenance` set amount_quote='%s' WHERE id=%d";
        echo $SQL = sprintf($Query, Sanitise::String($qtnamt), Sanitise::Long($tid));
        $this->_databaseManager->executeQuery($SQL);
        return true;
    }

    public function update_file_names_for_transaction($transactionid, $file_name_array, $statusid = null) {
        if ($transactionid != 0 && $transactionid != "") {
            // upload quotation file
            if (count($file_name_array) > 0) {
                $Query1 = "UPDATE maintenance set file_name='" . implode(',', $file_name_array) . "' WHERE maintenance.customerno = %d AND id = %d ";
                $vehiclesQuery = sprintf($Query1, $this->_Customerno, $transactionid);
                $this->_databaseManager->executeQuery($vehiclesQuery);
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function editbatterypopup($data) {
        $old_battsrno = $data['old_battsrno'];
        $new_battsrno = $data['new_battsrno'];
        $tid = $data['tid'];
        $vehicleid = $data['vehicleid'];

        $Query = "UPDATE `maintenance` set battery_srno='%s' WHERE id=%d";
        $SQL = sprintf($Query, Sanitise::String($new_battsrno), Sanitise::Long($tid));
        $this->_databaseManager->executeQuery($SQL);

//        $Query = "UPDATE `maintenance_history` set battery_srno='%s' WHERE maintananceid=%d";
//        $SQL = sprintf($Query, Sanitise::String($old_battsrno), Sanitise::Long($tid));
//        $this->_databaseManager->executeQuery($SQL);

        $Query = "UPDATE `maintenance_mapbattery` set batt_serialno='%s' WHERE vehicleid=%d";
        $SQL = sprintf($Query, Sanitise::String($new_battsrno), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
        return true;
    }

    public function edittyrepopup($form, $userid) {
        $today = date('Y-m-d H:i:s');
        $todaytime = date('Y-m-d h:i:s');
        $oldtyre_list = $form['oldtyre_list'];
        $newtyre_list = $form['newtyre_list'];
        $newtyre_tyreid_srno = $form['newtyre_tyreid_srno'];
        $tyre_repair = $form['tyrerepair'];
        $vehicleid = $form['vehicleid'];
        $tid = $form['tid'];
        //tyrerepair
        if ($tyre_repair != 0) {
            $Query = "UPDATE `maintenance` set tyre_type='%s' WHERE id=%d";
            $SQL = sprintf($Query, Sanitise::String($newtyre_list), Sanitise::Long($tid));
            $this->_databaseManager->executeQuery($SQL);

            // update tyre repair type
//                $Query = "UPDATE `maintenance_tyre_repair_mapping` set tyrerepairid='%s' WHERE maintenanceid=%d";
//                $SQL = sprintf($Query, Sanitise::String($tyre_repair), Sanitise::Long($tid));
//                $this->_databaseManager->executeQuery($SQL);
//                if ($tyre_repair == 1){
//                    $tyrelistnew = "";
//                    $newtyrelist_srno = "";
//                    $stringtyre = "";
//                    $newtyrelist_srno = $form['newtyre_tyreid_srno'];
//                    $tyrelistnew = explode(",", $newtyrelist_srno);
//                    
//                    if (count($tyrelistnew) > 0){
//                        for ($i = 0; count($tyrelistnew) > $i; $i++) {
//                            $stringtyre = explode("$", $tyrelistnew[$i]);
//                            $customerno = $this->_Customerno;
//                            $Query4 = "select * from maintenance_maptyre where vehicleid = " . $form['vehicleid'] ." AND tyreid=" . $stringtyre[0] . " AND isdeleted=0";
//                            $SQL4 = sprintf($Query4);
//                            $this->_databaseManager->executeQuery($SQL4);
//                            if ($this->_databaseManager->get_rowCount() > 0) {
//                                $Query5 = "update maintenance_maptyre set serialno='" . $stringtyre[1] . "', installedon='" . $today . "', updatedby=" . $userid . ", updatedon='" . $todaytime . "' where vehicleid = " . $form['vehicleid'] . " AND tyreid =" . $stringtyre[0];
//                                $SQL5 = sprintf($Query5);
//                                $this->_databaseManager->executeQuery($SQL5);
//                            }else {
//                                $Query6 = " INSERT INTO maintenance_maptyre (vehicleid, customerno, tyreid, serialno,installedon,createdby,createdon,isdeleted) "
//                                        . " VALUES (%d,%d,%d,'%s','%s',%d,'%s','%d')";
//                                $SQL6 = sprintf($Query6, Sanitise::String($form['vehicleid']), Sanitise::String($customerno), Sanitise::String($stringtyre[0]), Sanitise::String($stringtyre[1]), Sanitise::String($today), Sanitise::String($userid), Sanitise::String($todaytime), 0
//                                );
//                                $this->_databaseManager->executeQuery($SQL6);
//                            }
//                        }
//                    }
//                }
        }
    }

    public function getDealerList() {
        $data = array();
        $Query = "SELECT dealerid, name from dealer where isdeleted=0 AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($this->_Customerno));
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $data[] = array(
                    "dealerid" => $row['dealerid'],
                    "dealername" => $row['name']
                );
            }
        }
        return $data;
    }

    public function cancel_transaction($data) {
        $today = date('Y-m-d H:i:s');
        $transactionid = $data['transid'];
        $Query1 = "UPDATE maintenance set is_cancelled ='1' , statusid='15' WHERE maintenance.customerno = %d AND id = %d ";
        $MainQuery = sprintf($Query1, $this->_Customerno, $transactionid);
        $this->_databaseManager->executeQuery($MainQuery);
        $status = '15'; //cancelled status
        $main = $this->getlatest_maintenance_details($transactionid);
        $Query1 = "INSERT INTO maintenance_history(maintananceid,maintenance_date,meter_reading,dealer_id,vehicleid,timestamp,userid,customerno,roleid,notes,approval_notes,amount_quote,file_name,tyre_type,battery_srno,
parts_list,task_select_array,category,statusid,submission_date,approval_date,payment_submission_date,invoice_file_name,invoice_amount,invoice_date,invoice_no,vehicle_in_date,
vehicle_out_date,is_cancelled)
  VALUES( %d,'%s','%s',%d,%d,'%s',%d,%d,%d,'%s','%s','%s','%s','%s','%s','%s','%s',%d,%d,'%s','%s','%s','%s','%s','%s','%s','%s','%s',%d)";
        $SQL1 = sprintf($Query1, $transactionid, $main->maintenance_date, $main->meter_reading, $main->dealer_id, $main->vehicleid, Sanitise::DateTime($today), $main->userid, $main->customerno, $main->roleid, $main->notes, $main->approval_notes, $main->amount_quote, $main->file_name, $main->tyre_type, $main->battery_srno, $main->parts_list, $main->task_select_array, $main->category, $status, $main->submission_date, $main->approval_date, $main->payment_submission_date, $main->invoice_file_name, $main->invoice_amount, $main->invoice_date, $main->invoice_no, $main->vehicle_in_date, $main->vehicle_out_date, 1);
        $this->_databaseManager->executeQuery($SQL1);

        $status = "ok";
        return $status;
    }

    public function rollback_transaction($data){
        $today = date('Y-m-d H:i:s');
        $transactionid = $data['transid'];
        $main = $this->getlatest_maintenance_details($transactionid);
        $get_maintenancehistory_data = $this->getmaintenancehistorydata($main->statusid, $main->category, $transactionid);
        return $get_maintenancehistory_data;
    }

    public function getBehalfMembersZoneMasters($roleid, $userid, $role) {
        $data = array();
        if ($roleid == 33 || $roleid == 1) {
            $Query = "SELECT userid,realname,role,roleid from user where isdeleted=0 AND heirarchy_id=" . $userid . " AND customerno=" . $this->_Customerno;
            $SQL = sprintf($Query);
            $this->_databaseManager->executeQuery($SQL);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $data[] = array(
                        "userid" => $row['userid'],
                        "username" => $row['realname'],
                        "role" => $row['role'],
                        "roleid" => $row['roleid']
                    );
                }
            } else {
                $Query = "SELECT userid,realname,role,roleid from user where isdeleted=0 AND roleid=35 AND customerno=" . $this->_Customerno;
                $SQL = sprintf($Query);
                $this->_databaseManager->executeQuery($SQL);
                if ($this->_databaseManager->get_rowCount() > 0) {
                    while ($row = $this->_databaseManager->get_nextRow()) {
                        $data[] = array(
                            "userid" => $row['userid'],
                            "username" => $row['realname'],
                            "role" => $row['role'],
                            "roleid" => $row['roleid']
                        );
                    }
                }
            }
        }
        return $data;
    }

    public function getBehalfMembersRegionalManager($roleid, $userid, $role) {
        $data = array();
        if ($roleid == 35) {
            $Query = "SELECT userid,realname,role,roleid from user where isdeleted=0 AND heirarchy_id=" . $userid . " AND customerno=" . $this->_Customerno;
            $SQL = sprintf($Query);
            $this->_databaseManager->executeQuery($SQL);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $data[] = array(
                        "userid" => $row['userid'],
                        "username" => $row['realname'],
                        "role" => $row['role'],
                        "roleid" => $row['roleid']
                    );
                }
            }
        }
        return $data;
    }

    public function getRegionalManagerList($zoneuserid) {
        $data = array();
        $Query = "SELECT userid,realname,role,roleid from user where isdeleted=0 AND heirarchy_id=" . $zoneuserid . " AND customerno=" . $this->_Customerno;
        $SQL = sprintf($Query);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $data[] = array(
                    "userid" => $row['userid'],
                    "username" => $row['realname'],
                    "role" => $row['role'],
                    "roleid" => $row['roleid']
                );
            }
        }
        return $data;
    }

    public function getBranchManagerList($regionalid) {
        $data = array();
        $Query = "SELECT userid,realname,role,roleid from user where isdeleted=0 AND heirarchy_id=" . $regionalid . " AND customerno=" . $this->_Customerno;
        $SQL = sprintf($Query);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $data[] = array(
                    "userid" => $row['userid'],
                    "username" => $row['realname'],
                    "role" => $row['role'],
                    "roleid" => $row['roleid']
                );
            }
        }
        return $data;
    }

    public function getmaintenancehistorydata($statusid, $categoryid, $transactionid) {
        $catarr = array(0, 1, 3, 2, 5);
      //  $rollbackdata = array();
        if (in_array($categoryid, $catarr)) {
            $rollback = $this->update_is_rollbackstatus($transactionid,$statusid);
            
            $Query = "SELECT * from maintenance_history "
                    . " WHERE maintenance_history.customerno=%d AND "
                    . " maintenance_history.statusid!=%d AND "
                    . " maintenance_history.maintananceid =%d "
                    . " ORDER BY maintenance_history.hist_id DESC limit 1";
            $maintananceQuery = sprintf($Query, $this->_Customerno, $statusid, $transactionid);
            $this->_databaseManager->executeQuery($maintananceQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $rollbackdata[] = array(
                        "maintenance_date" => isset($row['maintenance_date'])?$row['maintenance_date']:'',
                        "meter_reading" => isset($row['meter_reading'])?$row['meter_reading']:'',
                        "vehicle_in_date" => isset($row['vehicle_in_date'])?$row['vehicle_in_date']:'',
                        "vehicle_out_date" => isset($row['vehicle_out_date'])?$row['vehicle_out_date']:'',
                        "dealer_id" => isset($row['dealer_id'])?$row['dealer_id']:'',
                        "invoice_date" => isset($row['invoice_date'])?$row['invoice_date']:'',
                        "invoice_no" => isset($row['invoice_no'])?$row['invoice_no']:'',
                        "invoice_amount" => isset($row['invoice_amount'])?$row['invoice_amount']:'',
                        "tax" => isset($row['tax'])?$row['tax']:'',
                        "payment_id" => isset($row['payment_id'])?$row['payment_id']:'',
                        "vehicleid" => isset($row['vehicleid'])?$row['vehicleid']:'',
                        "timestamp" => isset($row['timestamp'])?$row['timestamp']:'',
                        "userid" => isset($row['userid'])?$row['userid']:'',
                        "behalfid" => isset($row['behalfid'])?$row['behalfid']:'',
                        "roleid" => isset($row['roleid'])?$row['roleid']:'',
                        "notes" => isset($row['notes'])?$row['notes']:'',
                        "approval_notes" => isset($row['approval_notes'])?$row['approval_notes']:'',
                        "amount_quote" => isset($row['amount_quote'])?$row['amount_quote']:'',
                        "file_name" => isset($row['file_name'])?$row['file_name']:'',
                        "invoice_file_name" => isset($row['invoice_file_name'])?$row['invoice_file_name']:'',
                        "tyre_type" => isset($row['tyre_type'])?$row['tyre_type']:'',
                        "battery_srno" => isset($row['battery_srno'])?$row['battery_srno']:'',
                        "parts_list" => isset($row['parts_list'])?$row['parts_list']:'',
                        "task_select_array" => isset($row['task_select_array'])?$row['task_select_array']:'',
                        "category" => isset($row['category'])?$row['category']:'',
                        "statusid" => isset($row['statusid'])?$row['statusid']:'',
                        "isdeleted" => isset($row['isdeleted'])?$row['isdeleted']:'',
                        "submission_date" => isset($row['submission_date'])?$row['submission_date']:'',
                        "approval_date" => isset($row['approval_date'])?$row['approval_date']:'',
                        "ofasno" => isset($row['ofasno'])?$row['ofasno']:'',
                        "chequeno" => isset($row['chequeno'])?$row['chequeno']:'',
                        "chequeamt" => isset($row['chequeamt'])?$row['chequeamt']:'',
                        "chequedate" => isset($row['chequedate'])?$row['chequedate']:'',
                        "tdsamt" => isset($row['tdsamt'])?$row['tdsamt']:'',
                        "payment_approval_date" => isset($row['payment_approval_date'])?$row['payment_approval_date']:'',
                        "payment_submission_date" => isset($row['payment_submission_date'])?$row['payment_submission_date']:'',
                        "payment_approval_note" => isset($row['payment_approval_note'])?$row['payment_approval_note']:'',
                        "is_sentfinalpayment" => isset($row['is_sentfinalpayment'])?$row['is_sentfinalpayment']:'',
                        "emailalert" => isset($row['emailalert'])?$row['emailalert']:'',
                        "is_cancelled" => isset($row['is_cancelled'])?$row['is_cancelled']:''
                    );
                }
                $rollbackdata = $rollbackdata[0];
                $Query = "UPDATE maintenance SET 
                `maintenance_date` = '%s',
                `meter_reading` = '%s',
                `vehicle_in_date` = '%s',
                `vehicle_out_date` = '%s',
                `dealer_id` = '%s',
                `invoice_date` = '%s',
                `invoice_no` = '%s',
                `invoice_amount` = '%s',
                `tax` = '%s',
                `payment_id` = '%s',
                `vehicleid` = '%s',
                `timestamp` = '%s',
                `userid` =%d ,
                `behalfid` = %d,
                `roleid` = %d,
                `notes` = '%s',
                `approval_notes` ='%s' ,
                `amount_quote` ='%s' ,
                `file_name` = '%s',
                `invoice_file_name` ='%s',
                `tyre_type` = '%s',
                `battery_srno` = '%s',
                `parts_list` = '%s',
                `task_select_array` ='%s' ,
                `category` = '%s',
                `statusid` ='%s',
                `isdeleted` ='%s' ,
                `submission_date` = '%s',
                `approval_date` ='%s' ,
                `ofasno` = '%s',
                `chequeno` = '%s',
                `chequeamt` = '%s',
                `chequedate` = '%s',
                `tdsamt` = '%s',
                `payment_approval_date` ='%s' ,
                `payment_submission_date` ='%s' ,
                `payment_approval_note` ='%s' ,
                `is_sentfinalpayment` = '%s',
                `emailalert` = '%s',
                `is_cancelled` = '%s'
                WHERE id =%d AND customerno =%d";
                $SQL = sprintf($Query, 
                    $rollbackdata['maintenance_date'],
                    $rollbackdata['meter_reading'],
                    $rollbackdata['vehicle_in_date'],
                    $rollbackdata['vehicle_out_date'],
                    $rollbackdata['dealer_id'],
                    $rollbackdata['invoice_date'],
                    $rollbackdata['invoice_no'],
                    $rollbackdata['invoice_amount'],
                    $rollbackdata['tax'],
                    $rollbackdata['payment_id'],
                    $rollbackdata['vehicleid'],
                    $rollbackdata['timestamp'],
                    $rollbackdata['userid'],
                    $rollbackdata['behalfid'],
                    $rollbackdata['roleid'],
                    $rollbackdata['notes'],
                    $rollbackdata['approval_notes'],
                    $rollbackdata['amount_quote'],
                    $rollbackdata['file_name'],
                    $rollbackdata['invoice_file_name'],
                    $rollbackdata['tyre_type'],
                    $rollbackdata['battery_srno'],
                    $rollbackdata['parts_list'],
                    $rollbackdata['task_select_array'],
                    $rollbackdata['category'],
                    $rollbackdata['statusid'],
                    $rollbackdata['isdeleted'],
                    $rollbackdata['submission_date'],
                    $rollbackdata['approval_date'],
                    $rollbackdata['ofasno'],
                    $rollbackdata['chequeno'],
                    $rollbackdata['chequeamt'],
                    $rollbackdata['chequedate'],
                    $rollbackdata['tdsamt'],
                    $rollbackdata['payment_approval_date'],
                    $rollbackdata['payment_submission_date'],
                    $rollbackdata['payment_approval_note'],
                    $rollbackdata['is_sentfinalpayment'],
                    $rollbackdata['emailalert'],
                    $rollbackdata['is_cancelled'],
                    Sanitise::Long($transactionid),$this->_Customerno);
                //die($SQL); 
                $this->_databaseManager->executeQuery($SQL);
                $status='ok';
            }else{
                $status='notok';
            }
        }else{
            $status='notok';
        }
        return $status; 
        
    }
    
    public function update_is_rollbackstatus($transactionid,$statusid){
         $Query = "SELECT hist_id from maintenance_history "
                    . " WHERE maintenance_history.customerno=%d AND "
                    . " maintenance_history.statusid=%d AND "
                    . " maintenance_history.maintananceid =%d "
                    . " ORDER BY maintenance_history.hist_id DESC limit 1";
            $maintananceQuery = sprintf($Query, $this->_Customerno, $statusid, $transactionid);
            $this->_databaseManager->executeQuery($maintananceQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()){
                    $hist_id = $row['hist_id'];
                    $Query = "UPDATE maintenance_history SET is_rollback=1  WHERE `hist_id` = ".$hist_id." AND customerno =".$this->_Customerno;
                    $this->_databaseManager->executeQuery($Query);
                } 
            }
            return true;
    }

}
