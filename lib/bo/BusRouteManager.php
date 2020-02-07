<?php
class BusRouteManager extends VersionedManager {
    public function __construct($customerno, $userid) {
        parent::__construct($customerno, $userid);
        $this->customerno = $customerno;
        $this->userid = $userid;
        $this->todaysdate = date("Y-m-d H:i:s");
    }

    public function insertStudentData($objData) {
        $studentId = 0;
        try {
            $sqlQuery = "INSERT INTO student_master(firstName, lastName, enrollmentNo, centerId, grade, division, address, isBusStudent, lat, lng, accuracy, distance,
            timeInMin, customerno, created_by, created_on) VALUES ('%s', '%s', '%s', %d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %d, %d, '%s')";
            $exeuteQuery = sprintf($sqlQuery,
                Sanitise::String($objData->firstName),
                Sanitise::String($objData->lastName),
                Sanitise::String($objData->enrollmentNo),
                Sanitise::Long($objData->centerId),
                Sanitise::String($objData->grade),
                Sanitise::String($objData->division),
                Sanitise::String($objData->address),
                Sanitise::Long($objData->isBusStudent),
                Sanitise::String($objData->lat),
                Sanitise::String($objData->lng),
                Sanitise::Long($objData->accuracy),
                Sanitise::String($objData->distance),
                Sanitise::String($objData->timeInMin),
                $this->customerno,
                $this->userid,
                $this->todaysdate
            );
            $this->_databaseManager->executeQuery($exeuteQuery);
            $studentId = $this->_databaseManager->get_insertedId();
        } catch (Exception $e) {
            $log = new Log();
            $log->createlog($this->customerno, $e, $this->userid, 'BusRoute', __FUNCTION__);
        }
        return $studentId;
    }

    public function getAllStudents($isBusStudent = '0', $busStopId = null) {
        $arrStudents = null;
        try {
            $isBusStudentCondition = '';
            if ($isBusStudent == '2') {
                $isBusStudentCondition .= " and isBusStudent = 1 ";
            } elseif ($isBusStudent == '3') {
                $isBusStudentCondition .= " and isBusStudent = 0 ";
            }

            if (isset($busStopId)) {
                $sqlQuery = "SELECT sm.*, bsm.busStopStudentMappingId  FROM busStopStudentMapping as bsm
                            INNER JOIN student_master as sm on bsm.studentId = sm.studentId
                            WHERE bsm.customerno = %d and bsm.busStopId =%d and bsm.isdeleted  = 0";
                $executeQuery = sprintf($sqlQuery, $this->customerno, $busStopId);
            } else {
                $sqlQuery = "SELECT * FROM student_master WHERE customerno = %d " . $isBusStudentCondition . " and isdeleted  = 0";
                $executeQuery = sprintf($sqlQuery, $this->customerno);
            }

            $queryResult = $this->_databaseManager->executeQuery($executeQuery);
            $arrStudents = $this->_databaseManager->get_recordSet();
        } catch (Exception $e) {
            $log = new Log();
            $log->createlog($this->customerno, $e, $this->userid, 'BusRoute', __FUNCTION__);
        }
        return $arrStudents;
    }

    public function insertBusStop($objData) {
        $busStopId = 0;
        try {
            $sqlQuery = "INSERT INTO busStop(schoolId, lat, lng, distanceFromSchool, timeInMin, address, zoneId, customerno, created_by, created_on)
            VALUES (1, '%s', '%s', '%s', '%s', '%s', %d, %d, '%s', %d)";
            $executeQuery = sprintf($sqlQuery,
                Sanitise::String($objData->lat),
                Sanitise::String($objData->lng),
                Sanitise::String($objData->distanceFromSchool),
                Sanitise::String($objData->timeInMin),
                Sanitise::String($objData->address),
                Sanitise::Long($objData->zone),
                $this->customerno,
                $this->userid,
                $this->todaysdate
            );
            $this->_databaseManager->executeQuery($executeQuery);
            $busStopId = $this->_databaseManager->get_insertedId();
        } catch (Exception $e) {
            $log = new Log();
            $log->createlog($this->customerno, $e, $this->userid, 'BusRoute', __FUNCTION__);
        }
        return $busStopId;
    }

    public function insertBusStopStudentMapping($objData) {
        $busStopStudentMappingId = 0;
        try {
            $sqlQuery = "INSERT INTO busStopStudentMapping(busStopId, schoolId, studentId, distanceFromStop, timeInMin, customerno, created_by, created_on)
            VALUES (%d, 1, %d, '%s', '%s', %d, '%s', %d)";
            $executeQuery = sprintf($sqlQuery,
                Sanitise::String($objData->busStopId),
                Sanitise::String($objData->studentId),
                Sanitise::String($objData->distanceFromStop),
                Sanitise::String($objData->timeInMin),
                $this->customerno,
                $this->userid,
                $this->todaysdate
            );
            $this->_databaseManager->executeQuery($executeQuery);
            $busStopStudentMappingId = $this->_databaseManager->get_insertedId();
        } catch (Exception $e) {
            $log = new Log();
            $log->createlog($this->customerno, $e, $this->userid, 'BusRoute', __FUNCTION__);
        }
        return $busStopStudentMappingId;
    }

    public function getAllBusStops($objBusStop) {
        $arrBusStops = null;
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            $sp_params = "'" . $objBusStop->busStopId . "'"
            . ",'" . $objBusStop->zoneId . "'"
            . ",'" . $objBusStop->routeId . "'"
            . ",'" . $objBusStop->customerno . "'";
            $arrBusStops = $pdo->query($this->_databaseManager->PrepareSP(speedConstants::SP_GET_BUS_STOPS, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
            $this->_databaseManager->ClosePDOConn($pdo);
        } catch (Exception $e) {
            $log = new Log();
            $log->createlog($this->customerno, $e, $this->userid, 'BusRoute', __FUNCTION__);
        }
        return $arrBusStops;
    }

    public function getVehiclesForMapping() {
        $arrVehicles = null;
        $groupIdCondition = '';
        if ($_SESSION['groupid'] != '0') {
            $groupIdCondition .= " and v.groupid = " . $_SESSION['groupid'] . "";
        }
        try {
            $arrBusStops = null;
            $sqlQuery = "SELECT v.vehicleid, v.vehicleno, description.seatcapacity FROM vehicle v
            LEFT JOIN description on description.vehicleid = v.vehicleid
            WHERE v.customerno = %d $groupIdCondition and v.isdeleted  = 0";
            $executeQuery = sprintf($sqlQuery, $this->customerno);
            $queryResult = $this->_databaseManager->executeQuery($executeQuery);
            $arrVehicles = $this->_databaseManager->get_recordSet();
        } catch (Exception $e) {
            $log = new Log();
            $log->createlog($this->customerno, $e, $this->userid, 'BusRoute', __FUNCTION__);
        }
        return $arrVehicles;
    }

    public function getZoneSlotOrdersCount() {
        $date = date('Y-m-d', strtotime($this->todaysdate));
        $groupQ = '';
        if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
            $grpid = (int) $_SESSION['groupid'];
            $groupQ = " AND centerId = $grpid ";
        }
        $orders = array();
        $total = 0;
        $zoneZero = 0;
        $Query = "SELECT 1 as zoneId, busStopId FROM busStop WHERE isdeleted=0 AND customerno = %d";
        $SQL = sprintf($Query, $this->customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $zone = $row['zoneId'];
                $slot = 1;
                if (!isset($orders[$zone][$slot])) {
                    $orders[$zone][$slot] = 1;
                } else {
                    $orders[$zone][$slot] = $orders[$zone][$slot] + 1;
                }
                $total++;
            }
        }
        return array(
            'orders' => $orders,
            'zoneZero' => $zoneZero,
            'total' => $total,
        );
    }

    public function getMappedOrders() {
        $orders = array();
        $Query = "SELECT a.vehicle_id, c.vehicleno, b.busStopId, a.sequence, b.zoneId
            FROM bus_route_sequence as a
            INNER JOIN busStop as b on b.busStopId = a.busStopId
            left join " . SPEEDDB . ".vehicle as c on a.vehicle_id=c.vehicleid where b.isdeleted=0 AND b.customerno=$this->customerno ";
        $SQL = sprintf($Query);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $zone = $row['zoneId'];
                $slot = 1;
                $seq = $row['sequence'];
                $vehicle_id = $row['vehicle_id'];
                if ($zone == 0) {
                    continue;
                }
                $orders[$zone][$slot][$vehicle_id][$seq] = array(
                    'oid' => $row['busStopId'],
                    'vehno' => $row['vehicleno'],
                    'areaid' => $row['zoneId'],
                );
            }
        }
        return array(
            'orders' => $orders,
        );
    }

    public function busStopData() {
        $data = array();
        $Query = "SELECT a.zoneId,a.busStopId, a.lat, a.lng, a.distanceFromSchool, a.timeInMin FROM busStop as a";
        $Query .= " where a.customerno=$this->customerno  and a.lat!='' and a.lng!='' and isAlloted=0 ";
        $Query .= " order by a.timeInMin ASC,  a.distanceFromSchool ASC ";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $data[] = $row;
            }
            foreach ($data as $row) {
                $orders[$row['busStopId']] = array(
                    'lat' => $row['lat'],
                    'lng' => $row['lng'],
                    //'vehicle_id' => $vehicleids[0], //default vehicle assigned
                    'busStopId' => $row['busStopId'],
                    'busStopStudentCount' => $this->countBusStopStudent($row['busStopId']),
                    'areaid' => $row['zoneId'],
                    'distanceFromSchool' => $row['distanceFromSchool'],
                    'timeInMin' => $row['timeInMin'],
                );
            }
        }
        return $orders;
    }

    public function routeBusStopData($routeId) {
        $data = array();
        $Query = "SELECT a.zoneId,a.busStopId, a.lat, a.lng, a.routeid FROM busStop as a";
        $Query .= " where a.customerno=$this->customerno  and a.lat!='' and a.lng!='' and a.routeid = ".$routeId;
        $Query .= " AND isdeleted = 0 order by a.busStopId ASC ";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $data[] = $row;
            }
            foreach ($data as $row) {
                $orders[$row['busStopId']] = array(
                    'lat' => $row['lat'],
                    'lng' => $row['lng'],
                    'busStopId' => $row['busStopId'],
                    'busStopStudentCount' => $this->countBusStopStudent($row['busStopId']),
                    'areaid' => 1
                );
            }
        }
        return $orders;
    }

    public function countBusStopStudent($busStopId) {
        $Query = "SELECT count(busStopStudentMappingId) as studentCount from busStopStudentMapping where busStopId = $busStopId";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextrow();
            return $row['studentCount'];
        }
    }

    public function vehicleBusStopSequence($vehid) {
        $busStops = array();
        $Query = "SELECT b.busStopId, b.lat, b.lng, b.address, b.distanceFromSchool
        FROM `bus_route_sequence` as a
        INNER JOIN busStop as b on a.busStopId=b.busStopId AND b.isdeleted=0
        WHERE a.vehicle_id =$vehid  order by a.sequence ";
        $SQL = sprintf($Query);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $busStops[] = array(
                    "lat" => $row['lat'],
                    "longi" => $row['lng'],
                    "accuracy" => 1,
                    "slot" => 1,
                    "busStopId" => $row['busStopId'],
                    "distanceFromSchool" => $row['distanceFromSchool'],
                    "pop_display" => "BusStop-Id: {$row['busStopId']}<br>Address: {$row['address']}",
                );
            }
        }
        return $busStops;
    }

    public function getAllRoutesByVehicle($vehicleId) {
        $arrRoutes = null;
        try {
            $sqlQuery = "SELECT distinct(routeId) from bus_route_sequence where vehicle_id=%d";
            $executeQuery = sprintf($sqlQuery, Sanitise::Long($vehicleId));
            $queryResult = $this->_databaseManager->executeQuery($executeQuery);
            $arrRoutes = $this->_databaseManager->get_recordSet();
        } catch (Exception $e) {
            $log = new Log();
            $log->createlog($this->customerno, $e, $this->userid, 'BusRoute', __FUNCTION__);
        }
        return $arrRoutes;
    }

    function get_all_student() {
        $students = Array();
        $Query = "SELECT sm.*, sm.address as studentaddress, g.groupname, b.busStopId, b.address as busStopAddress   FROM `student_master` as sm
        INNER JOIN `group` as g on g.groupid = sm.centerId
        LEFT JOIN busStopStudentMapping as bsm on bsm.studentId = sm.studentId
        LEFT JOIN busStop as b on b.busStopId = bsm.busStopId
        WHERE sm.customerno=%d AND sm.isdeleted=0 ";
        $GroupsQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($GroupsQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $student = new stdClass();
                $student->studentId = $row['studentId'];
                $student->firstName = $row['firstName'];
                $student->lastName = $row['lastName'];
                $student->enrollmentNo = $row['enrollmentNo'];
                $student->centerId = $row['centerId'];
                $student->board = $row['board'];
                $student->grade = $row['grade'];
                $student->division = $row['division'];
                $student->address = $row['studentaddress'];
                $student->building = $row['building'];
                $student->street = $row['street'];
                $student->landmark = $row['landmark'];
                $student->area = $row['area'];
                $student->station = $row['station'];
                $student->city = $row['city'];
                $student->pincode = $row['pincode'];
                $student->groupname = $row['groupname'];
                $student->busStopAddress = "Bus Stop ".$row['busStopId']." - ".$row['busStopAddress'];
                $students[] = $student;
            }
            return $students;
        }
        return null;
    }

    public function getAllBusRoutes($objBusRoute) {
        $arrBusRoutes = null;
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            $sp_params = "'" . $objBusRoute->busStopId . "'"
            . ",'" . $objBusRoute->vehicleid . "'"
            . ",'" . $objBusRoute->routeId . "'";
            //echo $this->_databaseManager->PrepareSP(speedConstants::SP_GET_BUS_ROUTES, $sp_params);
            $arrBusRoutes = $pdo->query($this->_databaseManager->PrepareSP(speedConstants::SP_GET_BUS_ROUTES, $sp_params))->fetchAll(PDO::FETCH_ASSOC);
            $this->_databaseManager->ClosePDOConn($pdo);
        } catch (Exception $e) {
            $log = new Log();
            $log->createlog($this->customerno, $e, $this->userid, 'BusRoute', __FUNCTION__);
        }
        return $arrBusRoutes;
    }

    public function insertBusStopData($objData) {
        $studentId = 0;
        try {
            $sqlQuery = "INSERT INTO busStop(schoolId, lat, lng, address, busStopName, routeId, customerno, created_by, created_on)
            VALUES (%d,'%s','%s','%s','%s', %d, %d, %d, '%s')";
            $exeuteQuery = sprintf($sqlQuery,
                Sanitise::String($objData->schoolId),
                Sanitise::String($objData->lat),
                Sanitise::String($objData->lng),
                Sanitise::String($objData->address),
                Sanitise::String($objData->busStopName),
                Sanitise::String($objData->routeId),
                $this->customerno,
                $this->userid,
                $this->todaysdate
            );
            $this->_databaseManager->executeQuery($exeuteQuery);
            $studentId = $this->_databaseManager->get_insertedId();
        } catch (Exception $e) {
            $log = new Log();
            $log->createlog($this->customerno, $e, $this->userid, 'BusRoute', __FUNCTION__);
        }
        return $studentId;
    }
}
