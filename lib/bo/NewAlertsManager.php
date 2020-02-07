<?php

require_once '../../lib/system/Validator.php';
require_once '../../lib/system/DatabaseManager.php';
require_once '../../lib/system/Sanitise.php';
require_once '../../lib/system/Date.php';
require_once '../../lib/system/utilities.php';

class NewAlertsManager {

    private $_databaseManager = null;
    public $updated_alerts = 0;
    public $inserted_alerts = 0;

    function __construct() {
        // Constructor
        if ($this->_databaseManager == null) {
            $this->_databaseManager = new DatabaseManager();
        }
    }

    public function alerts_changes($data, $where, $from = 'all', $active_name = null) {

        if (isset($data['user'])) {
            $update_user = "update user set ";
            $main_arr = array();
            foreach ($data['user'] as $column => $value) {
                $main_arr[] = " $column=$value ";
            }
            $update_user .= implode(',', $main_arr);
            $update_user .= " where customerno={$where['customerno']} and userid={$where['userid']} ";

            $this->_databaseManager->executeQuery($update_user);

            // echo "updated user===";
        }

        if (isset($data['alert'])) {

            $values = array();

            if ($from == 'spec') {
                $veh_arr = array();
            }
            foreach ($data['alert'] as $row) {

                $veh_alert_id = $this->update_if_exists($row, $from);
                if (!$veh_alert_id) {
                    if (!isset($keysString)) {
                        $keysString = implode(", ", array_keys($row));
                    }
                    $values[] = " (" . implode(", ", $row) . ") ";
                    $this->inserted_alerts++;
                }

                if ($from == 'spec' && $veh_alert_id) {
                    $veh_arr[] = $veh_alert_id;
                }
            }

            if ($from == 'spec' && $veh_alert_id) {
                $this->deactivate_alerts($veh_arr, $active_name, $where['userid']);
            }
            //echo $this->updated_alerts.' alerts updated=====';

            if (!empty($values)) {
                $ins_values = implode(',', $values);
                $insert_alert = "insert into vehiclewise_alert($keysString) values $ins_values";
                $this->_databaseManager->executeQuery($insert_alert);
                //echo $this->inserted_alerts." alerts inserted====";
            }
        }
    }

    function update_if_exists($row, $from) {

        $Query = "SELECT veh_alert_id FROM vehiclewise_alert WHERE customerno=%d and userid=%d and vehicleid=%d and isdeleted=0";
        $SQL = sprintf($Query, $row['customerno'], $row['userid'], $row['vehicleid']);
        $this->_databaseManager->executeQuery($SQL);
        $count = $this->_databaseManager->get_rowCount();

        if ($count > 0) {

            $veh_alert_id = true;
            if ($from == 'spec') {
                while ($spec_row = $this->_databaseManager->get_nextRow()) {
                    $veh_alert_id = $spec_row['veh_alert_id'];
                }
            }

            $update_alert = "update vehiclewise_alert set ";
            $main_arr = array();
            foreach ($row as $column => $value) {
                $main_arr[] = " $column=$value ";
            }
            $update_alert .= implode(',', $main_arr);
            $update_alert .= " where customerno={$row['customerno']} and userid={$row['userid']} and vehicleid={$row['vehicleid']}";

            $this->_databaseManager->executeQuery($update_alert);

            $this->updated_alerts++;
            return $veh_alert_id;
        } else {
            return false;
        }
    }

    function get_all_alert_time($userid) {
        //$Query = "select * from vehiclewise_alert WHERE customerno=%d and userid=%d and isdeleted=0";
        $Query = "  SELECT  va.*
                            ,u.start_alert
                            ,u.stop_alert
                    FROM    vehiclewise_alert as va 
                    INNER JOIN vehicle as v on v.customerno = va.customerno and v.vehicleid = va.vehicleid 
                    LEFT JOIN user as u ON u.userid = va.userid
                    WHERE   va.customerno = %d 
                    AND     va.userid = %d 
                    AND     va.isdeleted = 0
                    LIMIT   1";
        $SQL = sprintf($Query, $_SESSION['customerno'], $userid);
        $this->_databaseManager->executeQuery($SQL);
        $count = $this->_databaseManager->get_rowCount();

        if ($count < 1) {
            return array();
        }

        $max_data = array();
        $total = array();
        while ($row = $this->_databaseManager->get_nextRow()) {

            $active = 0;
            foreach ($row as $c => $v) {

                if (preg_match('/active/', $c)) {
                    $active = $v;
                    $add_v = is_null($v) ? 0 : (int) $v;
                    if (isset($total[$c])) {
                        $total[$c] += $add_v;
                    } else {
                        $total[$c] = $add_v;
                    }
                }

                $match = preg_match('/time/', $c);
                $v = substr($v, 0, -3);
                if ($match && isset($max_data[$c]) && $v > $max_data[$c] && $active == 1) {
                    $max_data[$c] = $v;
                } elseif ($match && !isset($max_data[$c]) && $active == 1) {
                    $max_data[$c] = $v;
                }
            }
            $max_data['start_alert'] = $row['start_alert'];
            $max_data['stop_alert'] = $row['stop_alert'];
        }

        if (!empty($total)) {
            foreach ($total as $key => $tot) {
                $c_name = str_replace('_active', '', $key);
                $max_data[$c_name] = $this->prefill_alert_inputs($count, $tot);
            }
        }

//        echo "<pre>";print_r($max_data);die();
        return $max_data;
    }

    function prefill_alert_inputs($vehicle_count, $count) {
        $check_all = $check_spec = '';
        if ($count == $vehicle_count || $count == 0) {
            $check_all = 'checked';
        } else {
            $check_spec = 'checked';
        }
        $s = ($count <= 1) ? '' : 's';
        if (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == 3) {
            if (isset($_SESSION['Warehouse']) && !empty($_SESSION['Warehouse'])) {
                $veh = $_SESSION['Warehouse'];
            } else {
                $veh = "Warehouse";
            }
        } else {
            $veh = "Vehicle";
        }
        $veh_added = $count . " " . $veh . $s . " added";

        return array('checkAll' => $check_all, 'checkSpec' => $check_spec, 'count' => $count, 'vehText' => $veh_added);
    }

    function deactivate_alerts($ids, $column_name, $userid) {
        $not_ids = implode(',', $ids);
        $update_alert = "update vehiclewise_alert set %s=0 where customerno=%d and userid=%d and isdeleted=0 and veh_alert_id not in ($not_ids)";
        $SQL = sprintf($update_alert, $column_name, $_SESSION['customerno'], $userid);

        $this->_databaseManager->executeQuery($SQL);

        $count = $this->_databaseManager->get_affectedRows();
        //echo "$count alerts deactivated======";
        return true;
    }

    function get_vehicle_alert($column, $userid) {
        $column = (string) $column;
        //$Query = "select a.*, b.vehicleno from vehiclewise_alert as a left join vehicle as b on a.vehicleid=b.vehicleid WHERE a.customerno=%d and a.userid=%d and a.isdeleted=0 and %s=1";
        $Query = "select a.*, b.vehicleno from vehiclewise_alert as a inner join vehicle as b on a.vehicleid=b.vehicleid AND a.customerno = b.customerno WHERE a.customerno=%d and a.userid=%d and a.isdeleted=0 and %s=1";
        $SQL = sprintf($Query, $_SESSION['customerno'], $userid, $column . '_active');
        $this->_databaseManager->executeQuery($SQL);
        $count = $this->_databaseManager->get_rowCount();
        if ($count > 0) {
            $alerts = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $start = substr($row[$column . '_starttime'], 0, -3);
                $end = substr($row[$column . '_endtime'], 0, -3);
                $alerts[] = array(
                    'start' => $start,
                    'end' => $end,
                    'vehicleid' => $row['vehicleid'],
                    'vehno' => $row['vehicleno'],
                );
            }
            return $alerts;
        } else {
            return null;
        }
    }

    function get_vehicle_temp_range($column, $userid) {
        $column = (string) $column;

        $Query = "  SELECT  a.*
                            , b.vehicleno 
                    FROM    `advancetempalertrange` as tasr 
                    INNER JOIN `vehicle` as v on tasr.vehicleid = b.vehicleid 
                    AND     tasr.customerno = v.customerno 
                    WHERE   tasr.customerno = %d 
                    AND     tasr.userid = %d 
                    AND     tasr.isdeleted = 0 
                    AND     %s = 1";
        $SQL = sprintf($Query, $_SESSION['customerno'], $userid, $column . '_active');
        $this->_databaseManager->executeQuery($SQL);
        $count = $this->_databaseManager->get_rowCount();
        if ($count > 0) {
            $alerts = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $start = substr($row[$column . '_starttime'], 0, -3);
                $end = substr($row[$column . '_endtime'], 0, -3);
                $alerts[] = array(
                    'start' => $start,
                    'end' => $end,
                    'vehicleid' => $row['vehicleid'],
                    'vehno' => $row['vehicleno'],
                );
            }
            return $alerts;
        } else {
            return null;
        }
    }

    function get_temp_range($column, $userid) {
        $column = (string) $column;
        //$Query = "select a.*, b.vehicleno from vehiclewise_alert as a left join vehicle as b on a.vehicleid=b.vehicleid WHERE a.customerno=%d and a.userid=%d and a.isdeleted=0 and %s=1";
        $Query = "select a.*, b.vehicleno from vehiclewise_alert as a inner join vehicle as b on a.vehicleid=b.vehicleid AND a.customerno = b.customerno WHERE a.customerno=%d and a.userid=%d and a.isdeleted=0 and %s=1";
        $SQL = sprintf($Query, $_SESSION['customerno'], $userid, $column . '_active');
        $this->_databaseManager->executeQuery($SQL);
        $count = $this->_databaseManager->get_rowCount();
        if ($count > 0) {
            $alerts = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $start = substr($row[$column . '_starttime'], 0, -3);
                $end = substr($row[$column . '_endtime'], 0, -3);
                $alerts[] = array(
                    'start' => $start,
                    'end' => $end,
                    'vehicleid' => $row['vehicleid'],
                    'vehno' => $row['vehicleno'],
                );
            }
            return $alerts;
        } else {
            return null;
        }
    }

    function updateTempInrangeAlertRequired($uid, $status) {
        $query = "  UPDATE  `user` 
                    SET     `isTempInrangeAlertRequired` = %d 
                    WHERE   `userid` = %d";
        $SQL = sprintf($query, $status, $uid);
        $this->_databaseManager->executeQuery($SQL);
    }

    function updateAdvTempConfRange($uid, $status) {
        $query = "  UPDATE  `user` 
                    SET     `isAdvTempConfRange` = %d 
                    WHERE   `userid` = %d";
        $SQL = sprintf($query, $status, $uid);
        $this->_databaseManager->executeQuery($SQL);
    }

    function endswith($string, $test) {
        $strlen = strlen($string);
        $testlen = strlen($test);
        if ($testlen > $strlen)
            return false;
        return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
    }

    function updateAlertStartStopTime($userid, $STime, $ETime) {
        $query = "  UPDATE  `user` 
                    SET     `start_alert` = '%s'
                            , `stop_alert` = '%s'
                    WHERE   `userid` = %d";
        $SQL = sprintf($query, $STime, $ETime, $userid);
        $this->_databaseManager->executeQuery($SQL);
    }

}

?>
