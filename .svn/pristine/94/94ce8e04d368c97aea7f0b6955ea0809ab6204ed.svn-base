<?php
define('GLIMIT', 24); //google request limit
define('LBSlotReset', 4); //localbanya reset slotid
define('CLUSTRADIUS', 1);
include_once '../../lib/autoload.php';
class AlgoManager extends VersionedManager {
    public $algo_date, $zones, $slots; //main params
    public $testing = false, $max_box, $skip_box; //testing params
    function __construct($customerno, $algoDate = null, $Zones = null, $Slots = null) {
        parent::__construct($customerno);
        $this->algo_date = date('Y-m-d', strtotime($algoDate));
        $this->zones = $Zones;
        $this->slots = $Slots;
    }
    function testing_mode($testing, $max_box, $skip_box) {
        $this->testing = $testing;
        $this->max_box = $max_box;
        $this->skip_box = $skip_box;
    }
    function run_route_algo() {
        $cur_box = 0;
        foreach ($this->zones as $zoneid => $zoneName) {
            $last_sequence = array();
            foreach ($this->slots as $slotid => $sd) {
                $cur_box++;
                if ($this->testing && $cur_box > $this->max_box) {
                    die();
                }
                if ($this->testing && $cur_box <= $this->skip_box) {
                    continue;
                }
                //echo "$zoneid, $slotid, $this->algo_date";die();
                $vehicleids = $this->get_route_vehicles($zoneid, $slotid);
                //echo "<pre>";print_r($vehicleids);die();
                if (!empty($vehicleids)) {
                    $order_data = $this->Order_based_data($zoneid, $slotid, $vehicleids);
                    if (!empty($order_data)) {
                        //print_r($order_data);die();
                        if ($slotid == LBSlotReset) {
                            //for localbanya
                            $last_sequence = array();
                        }
                        $veh_orders = $this->veh_box_assigning($order_data, $vehicleids, $last_sequence, $zoneName['startll']);
                        //print_r($veh_orders);die();
                        if (!$veh_orders) {
                            echo "unable to generate latlong_arr_main<br/>";
                            continue;
                        } else {
                            $this->insertOrderSeq($veh_orders);
                            $last_sequence = $this->get_last_sequence($veh_orders);
                        }
                    } else {
                        //No order found for this zone,slot or box
                    }
                } else {
                    //Vehicles not assigned for this zone,slot or box
                }
            }
        }
    }
    function runBusRouteAlgorithm() {
        $cur_box = 0;
        foreach ($this->zones as $zoneid => $zName) {
            $last_sequence = array();
            foreach ($this->slots as $slotid => $sd) {
                $cur_box++;
                if ($this->testing && $cur_box > $this->max_box) {
                    die();
                }
                if ($this->testing && $cur_box <= $this->skip_box) {
                    continue;
                }
                //echo "$zoneid, $slotid, $this->algo_date";die();
                $vehicleids = $this->get_route_vehicles($zoneid, $slotid);
                //echo "<pre>";print_r($vehicleids);die();
                if (!empty($vehicleids)) {
                    $objBusRoute = new BusRouteManager($_SESSION['customerno'], $_SESSION['userid']);
                    $order_data = $objBusRoute->busStopData($zoneid, $slotid, $vehicleids);
                    if (!empty($order_data)) {
                        //print_r($order_data);die();
                        $arrBusStops = $this->splitArray($order_data);
                        foreach ($arrBusStops as $busStops) {
                            //$veh_orders = $this->busStopAssigning($busStops, $vehicleids, $last_sequence, $zName['startll']);
                            $veh_orders = $this->busStopAssigningByDistance($busStops, $vehicleids, $last_sequence, $zName['startll']);
                            if (!$veh_orders) {
                                echo "unable to generate latlong_arr_main<br/>";
                                continue;
                            } else {
                                $routeId = $this->getRouteId();
                                $routeId = $routeId + 1;
                                $this->insertBusStopSeq($veh_orders, $routeId);
                                $last_sequence = $this->getLastBusStopSequence($veh_orders);
                                $this->updateRouteId($routeId);
                            }
                        }
                    } else {
                        //No order found for this zone,slot or box
                    }
                } else {
                    //Vehicles not assigned for this zone,slot or box
                }
            }
        }
    }
    function getRouteId() {
        $sql = "SELECT * FROM routeSeed";
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['routeId'];
            }
        }
    }
    function updateRouteId($routeId) {
        $sql = "update routeSeed SET routeId = $routeId  where id = 1";
        $this->_databaseManager->executeQuery($sql);
    }
    function splitArray($arrData) {
        $arrResult = array();
        $arrCount = count($arrData);
        $lastCount = 0;
        $noOfBusStops = 0;
        $noOfBoxes = ceil($arrCount / MAX_ORDERS);
        if ($arrCount > MAX_ORDERS) {
            for ($i = 0; $i < $noOfBoxes; $i++) {
                $arrResult[] = array_slice($arrData, $noOfBusStops, (MAX_ORDERS - 1));
                $noOfBusStops = ($noOfBusStops + (MAX_ORDERS - 1));
            }
        } else {
            $arrResult[] = $arrData;
        }
        return $arrResult;
    }
    function run_route_algo_pickup() {
        //echo "<pre>";
        $cur_box = 0;
        foreach ($this->zones as $zoneid => $zoneName) {
            $last_sequence = array();
            foreach ($this->slots as $slotid => $sd) {
                $cur_box++;
                if ($this->testing && $cur_box > $this->max_box) {
                    die();
                }
                if ($this->testing && $cur_box <= $this->skip_box) {
                    continue;
                }
                //echo "$zoneid, $slotid, $this->algo_date";die();
                $vehicleids = $this->get_route_vehicles_pickup($zoneid, $slotid);
                //print_r($vehicleids);die();
                //echo "<pre>";print_r($vehicleids);die();
                if (!empty($vehicleids)) {
                    $order_data = $this->Order_based_data_pickup($zoneid, $slotid, $vehicleids);
                    //print_r($order_data);die();
                    foreach ($order_data as $key => $value) {
                        if (!empty($order_data)) {
                            //print_r($order_data);die();
                            //for localbanya
                            $last_sequence = array();
                            $key = (array) $key;
                            //print_r($key);
                            //print_r($zoneName['startll']);die();
                            $veh_orders = $this->veh_box_assigning_pickup($value, $key, $last_sequence, $zoneName['startll']);
                            //print_r($veh_orders);die();
                            if (!$veh_orders) {
                                echo "unable to generate latlong_arr_main<br/>";
                                continue;
                            } else {
                                $this->insertOrderSeq_pickup($veh_orders);
                                $last_sequence = $this->get_last_sequence($veh_orders);
                            }
                        } else {
                            //No order found for this zone,slot or box
                        }
                    }
                } else {
                    //Vehicles not assigned for this zone,slot or box
                }
            }
        }
    }
    private function veh_box_assigning($main_orders, $vehs, $last_sequence, $startll) {
        $vc = count($vehs);
        $ocount = count($main_orders);
        $veh_assigned = array();
        if ($vc > 1) {
            $first_key = key($main_orders); // First Element's Key
            $cgeolat = $main_orders[$first_key]['lat']; //first lat
            $cgeolong = $main_orders[$first_key]['longi']; //first long
            $clustor_loop = 0;
            $clustor = array();
            while (true) {
                foreach ($main_orders as $key => $sorder) {
                    $distance = calculate($sorder['lat'], $sorder['longi'], $cgeolat, $cgeolong);
                    if ($distance <= CLUSTRADIUS) {
                        //1 km
                        if (isset($clustor[$clustor_loop]) && count($clustor[$clustor_loop]) > MAX_ORDERS) {
                            break;
                        } else {
                            $clustor[$clustor_loop][] = $sorder;
                            unset($main_orders[$key]);
                        }
                    }
                }
                ++$clustor_loop;
                if (empty($main_orders)) {
                    break;
                } else {
                    $first_o_val = array_shift($main_orders);
                    $cgeolat = $first_o_val['lat'];
                    $cgeolong = $first_o_val['longi'];
                    $clustor[$clustor_loop][] = $first_o_val;
                }
            }
            print_r($clustor);die();
            $first_clust_arr = array();
            $incre = 0;
            foreach ($clustor as $clustdata) {
                if ($incre > GLIMIT) {
                    break;
                }
                $first_clust_arr[] = "{$clustdata[0]['lat']},{$clustdata[0]['longi']}";
                $incre++;
            }
            //print_r($first_clust_arr);die();
            if (empty($last_sequence)) {
                $first_clust_arr_temp = $first_clust_arr;
                $clust_csv = implode('|', $first_clust_arr_temp);
                $details = getTimeRqrd($startll, $clust_csv);
                if (!isset($details[0]->elements)) {
                    echo "get_nearest_order===no data found1==empty --last_sequence\r\nError : " . $details;
                    die();
                } else {
                    $temp_details = $details;
                    $vehloop = 1;
                    foreach ($vehs as $vehid) {
                        if ($vehloop > $ocount) {
                            break;
                        }
                        $least_time = $this->get_minimal_dist_data($temp_details[0]->elements);
                        $clustor[$least_time['key']][0]['time'] = $least_time['duration'];
                        $veh_assigned[$vehid][] = $clustor[$least_time['key']];
                        unset($temp_details[0]->elements[$least_time['key']]);
                        unset($first_clust_arr_temp[$least_time['key']]);
                        $vehloop++;
                    }
                    //print_r($veh_assigned);die();
                    $remaining_cluster = count($first_clust_arr_temp);
                    while ($remaining_cluster) {
                        $veh_lastll = $this->get_vehicles_lastll($veh_assigned);
                        $veh_lastll_csv = implode('|', $veh_lastll);
                        $des_csv = implode('|', $first_clust_arr_temp);
                        $details_c = getTimeRqrd($veh_lastll_csv, $des_csv);
                        if (!isset($details_c[0]->elements)) {
                            echo "details_c===no data found1==empty--lastsequence\r\nError : " . $details_c;
                            $remaining_cluster--;
                            continue;
                        } else {
                            $least_load = $this->get_min_clust_data($details_c, $veh_assigned, $vehs);
                            $clustkey = $this->get_key($least_load['clustkey'], $first_clust_arr_temp);
                            $vid = $vehs[$least_load['vkey']];
                            $veh_assigned[$vid][] = $clustor[$clustkey];
                            unset($first_clust_arr_temp[$clustkey]);
                        }
                        $remaining_cluster--;
                    }
                }
                //print_r($veh_assigned);die();
                $f_orders = array();
                foreach ($veh_assigned as $vehid => $clustors) {
                    foreach ($clustors as $orders) {
                        foreach ($orders as $singleorder) {
                            $f_orders[$vehid][] = $singleorder;
                        }
                    }
                }
                $f_orders = $this->get_route_time($f_orders);
            } else {
                $first_clust_arr_temp = $first_clust_arr;
                $remaining_cluster = count($first_clust_arr_temp);
                while ($remaining_cluster) {
                    if (empty($veh_assigned)) {
                        $veh_lastll_csv = implode('|', $last_sequence);
                    } else {
                        $veh_lastll = $this->get_vehicles_lastll($veh_assigned, $last_sequence, $vehs);
                        $veh_lastll_csv = implode('|', $veh_lastll);
                    }
                    $des_csv = implode('|', $first_clust_arr_temp);
                    $details_c = getTimeRqrd($veh_lastll_csv, $des_csv);
                    if (!isset($details_c[0]->elements)) {
                        echo "details_c===no data found1\r\nError : " . $details_c;
                        $remaining_cluster--;
                        continue;
                    } else {
                        $least_load = $this->get_min_clust_data($details_c, $veh_assigned, $vehs);
                        $clustkey = $this->get_key($least_load['clustkey'], $first_clust_arr_temp);
                        $vid = $vehs[$least_load['vkey']];
                        $clustor[$clustkey][0]['time'] = $least_load['duration'];
                        $veh_assigned[$vid][] = $clustor[$clustkey];
                        unset($first_clust_arr_temp[$clustkey]);
                    }
                    $remaining_cluster--;
                }
                $f_orders = array();
                foreach ($veh_assigned as $vehid => $clustors) {
                    foreach ($clustors as $orders) {
                        foreach ($orders as $singleorder) {
                            $f_orders[$vehid][] = $singleorder;
                        }
                    }
                }
                $f_orders = $this->get_route_time($f_orders);
            }
        } else {
            $orders_arr = array();
            $orderids = array();
            $ocount = 0;
            foreach ($main_orders as $oid => $odata) {
                if ($ocount == MAX_ORDERS) {
                    break;
                }
                $orders_arr[] = "{$odata['lat']},{$odata['longi']}";
                $orderids[] = $oid;
                $ocount++;
            }
            $orders_csv = implode('|', $orders_arr);
            if (!empty($last_sequence)) {
                $startll = $last_sequence[$vehs[0]];
            }
            $sll = "$startll|$orders_csv";
            $details = getTimeRqrd($sll, $orders_csv);
            if (!isset($details[0]->elements)) {
                echo "get_nearest_order===no data found1==last else\r\nError : " . $details;
            } else {
                $temp_details = $details;
                $i = 0;
                while ($ocount) {
                    $least_time = $this->get_minimal_dist_data($temp_details[$i]->elements);
                    $main_orders[$orderids[$least_time['key']]]['time'] = $least_time['duration'];
                    $veh_assigned[$vehs[0]][] = $main_orders[$orderids[$least_time['key']]];
                    unset($temp_details[$i]);
                    $temp_details = $this->remove_array($temp_details, $least_time['key']);
                    $i = $least_time['key'] + 1;
                    $ocount--;
                }
                $f_orders = $veh_assigned;
            }
        }
        return $f_orders;
    }

    public function runBusRouteAlgorithmByDistance() {

        $last_sequence = array();

        $vehicleids = $this->getBusRouteVehicles();
        //prettyPrint($vehicleids);
        if (!empty($vehicleids)) {
            $objBusRoute = new BusRouteManager($_SESSION['customerno'], $_SESSION['userid']);
            $busStopAssignedToVehicles = $objBusRoute->busStopData();
            //prettyPrint($busStopAssignedToVehicles);die();
            if (!empty($busStopAssignedToVehicles)) {
                $firstElement = key($busStopAssignedToVehicles); // First Element's Key
                $cgeolat = $busStopAssignedToVehicles[$firstElement]['lat']; //first lat
                $cgeolong = $busStopAssignedToVehicles[$firstElement]['lng']; //first long
                $arrCluster_loop = 0;
                $arrCluster = array();
                while (true) {
                    $i = 1;
                    foreach ($busStopAssignedToVehicles as $key => $busStop) {
                        if ($key != $firstElement) {
                            $distance = calculateDistance($busStop['lat'], $busStop['lng'], $cgeolat, $cgeolong);
                            if ($distance <= 0.5 && $distance != -1) {
                                //if ($distance == -1) {
                                //1 km
                                if (isset($arrCluster[$arrCluster_loop]) && count($arrCluster[$arrCluster_loop]) >= MAX_ORDERS) {
                                    count($arrCluster[$arrCluster_loop]);
                                    break;
                                } else {
                                    //echo $key . "Else-Condition";
                                    $arrCluster[$arrCluster_loop][] = $busStop;
                                    unset($busStopAssignedToVehicles[$key]);
                                    //prettyPrint($busStopAssignedToVehicles);

                                }
                            }
                        } else if ($key == $firstElement) {
                            $arrCluster[$arrCluster_loop][] = $busStopAssignedToVehicles[$firstElement];
                            unset($busStopAssignedToVehicles[$firstElement]);
                            //prettyPrint($arrCluster);
                        }

                    }
                    ++$arrCluster_loop;
                    if (empty($busStopAssignedToVehicles)) {
                        break;
                    } else {
                        $first_o_val = array_shift($busStopAssignedToVehicles);
                        $cgeolat = $first_o_val['lat'];
                        $cgeolong = $first_o_val['lng'];
                        $arrCluster[$arrCluster_loop][] = $first_o_val;
                    }
                }
                //prettyPrint($arrCluster);die();
                $finalList = array();
                $overCapacityList = array();
                $leftOverList = array();
                foreach ($vehicleids as $vehicle) {
                    $vehicleCapacity = $vehicle['seatCapacity'];
                    $vehicleTotalCapacity = $vehicle['seatCapacity'];
                    $tempCluster = array();
                    $tempArrCluster = array();
                    if (isset($tempArrCluster) && count($tempArrCluster) > 0) {
                        $arrCluster = $tempArrCluster;
                    }
                    foreach ($arrCluster as $arrKey => $cluster) {
                        if (isset($tempCluster) && count($tempCluster) > 0) {
                            $cluster = $tempCluster;
                        }
                        foreach ($cluster as $key => $busStop) {
                            if ($busStop['busStopStudentCount'] > $vehicleTotalCapacity) {
                                $overCapacityList[] = $busStop;
                                unset($cluster[$key]);
                            } else if ($busStop['busStopStudentCount'] <= $vehicleCapacity) {
                                $finalList[$vehicle['vehicleId']][] = $busStop;
                                $vehicleCapacity -= $busStop['busStopStudentCount'];
                                unset($cluster[$key]);
                                unset($arrCluster[$arrKey][$key]);
                            }
                        }
                        $tempCluster = $cluster;
                        if (count($cluster) < 1) {
                            unset($arrCluster[$arrKey]);
                        }
                        //break;
                    }
                    $tempArrCluster = $arrCluster;
                    //break;
                }
                //prettyPrint($finalList);die();
                $arrFiltersList = array();
                if (isset($finalList) && !empty($finalList)) {
                    $seq = array();
                    $tempStop = array();
                    $zName['startll'] = "19.250784,72.850693";
                    foreach ($finalList as $vehicleid => $stop) {
                        list($lat, $lng) = explode(',', $zName['startll']);
                        $tempStartLatLong['lat'] = $lat;
                        $tempStartLatLong['lng'] = $lng;
                        $finalSeq = array();
                        $objThis = $this;
                        while (count($stop) > 0) {
                            $distances = array_map(function ($s) use ($tempStartLatLong, $objThis) {
                                $a = array_slice($s, 0, -3);
                                //prettyPrint($tempStartLatLong);
                                $record = $objThis->calculateDistanceByDrivingMode($a, $tempStartLatLong);
                                if ($record != -1) {
                                    return $record;
                                }

                            }, $stop);
                            asort($distances);
                            $tempStartLatLong = array_slice($stop[key($distances)], 0, -3);
                            $finalSeq[] = $stop[key($distances)];
                            unset($stop[key($distances)]);
                        }
                        //prettyPrint($finalSeq);

                        if (isset($finalSeq) && !empty($finalSeq)) {
                            $this->insertBusStopSeq($finalSeq, $vehicleid);
                        }
                        //break;
                    }
                }
            }
        }

    }

    public function calculatedemo($a, $b) {
        //Earth's mean radius in km
        $cgeolat = $a['lat'];
        $cgeolong = $a['lng'];
        //prettyPrint($b);
        $devicelat = $b['lat'];
        $devicelong = $b['lng'];

        $ERadius = 6371;

        //Difference between devicelatlong and checkpointlatlong
        $diffLat = rad($cgeolat - $devicelat);
        $diffLong = rad($cgeolong - $devicelong);

        //Converting between devicelatlong to radians
        $devlat_rad = rad($devicelat);
        $devlong_rad = rad($cgeolat);

        //Calculation Using Haversine's formula
        //Applying Haversine formula
        $a = sin($diffLat / 2) * sin($diffLat / 2) + cos($devlat_rad) * cos($devlong_rad) * sin($diffLong / 2) * sin($diffLong / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        //Distance
        $diffdist = $ERadius * $c;

        return $diffdist;
    }

    function calculateDistanceByDrivingMode($a, $b) {

        $cgeolat = $a['lat'];
        $cgeolong = $a['lng'];
        //prettyPrint($b);
        $devicelat = $b['lat'];
        $devicelong = $b['lng'];

        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $devicelat . "," . $devicelong . "&destinations=" . $cgeolat . "," . $cgeolong . "&mode=driving&language=pl-PL";

        //echo "<br/><br/>";
        $distance = -1;

        $json = file_get_contents($url);
        $details = json_decode($json, true);
        if (isset($details['rows'][0]['elements'][0]['status']) && $details['rows'][0]['elements'][0]['status'] == "OK") {
            $distance = round(($details['rows'][0]['elements'][0]['distance']['value']) / 1000, 2);
        }
        return $distance;
    }

    private function veh_box_assigning_pickup($main_orders, $vehs, $last_sequence, $startll) {
        $vc = count($vehs);
        $ocount = count($main_orders);
        $veh_assigned = array();
        if ($vc > 1) {
            $first_key = key($main_orders); // First Element's Key
            $cgeolat = $main_orders[$first_key]['lat']; //first lat
            $cgeolong = $main_orders[$first_key]['longi']; //first long
            $clustor_loop = 0;
            $clustor = array();
            while (true) {
                foreach ($main_orders as $key => $sorder) {
                    $distance = calculate($sorder['lat'], $sorder['longi'], $cgeolat, $cgeolong);
                    if ($distance <= 1) {
                        //1 km
                        if (isset($clustor[$clustor_loop]) && count($clustor[$clustor_loop]) > MAX_ORDERS) {
                            break;
                        } else {
                            $clustor[$clustor_loop][] = $sorder;
                            unset($main_orders[$key]);
                        }
                    }
                }
                ++$clustor_loop;
                if (empty($main_orders)) {
                    break;
                } else {
                    $first_o_val = array_shift($main_orders);
                    $cgeolat = $first_o_val['lat'];
                    $cgeolong = $first_o_val['longi'];
                    $clustor[$clustor_loop][] = $first_o_val;
                }
            }
            //print_r($clustor);die();
            $first_clust_arr = array();
            $incre = 0;
            foreach ($clustor as $clustdata) {
                if ($incre > GLIMIT) {
                    break;
                }
                $first_clust_arr[] = "{$clustdata[0]['lat']},{$clustdata[0]['longi']}";
                $incre++;
            }
            //print_r($first_clust_arr);die();
            if (empty($last_sequence)) {
                $first_clust_arr_temp = $first_clust_arr;
                $clust_csv = implode('|', $first_clust_arr_temp);
                $details = getTimeRqrd($startll, $clust_csv);
                if (!isset($details[0]->elements)) {
                    echo "get_nearest_order===no data found1==empty --last_sequence<br/>";
                    die();
                } else {
                    $temp_details = $details;
                    $vehloop = 1;
                    foreach ($vehs as $vehid) {
                        if ($vehloop > $ocount) {
                            break;
                        }
                        $least_time = $this->get_minimal_dist_data($temp_details[0]->elements);
                        $clustor[$least_time['key']][0]['time'] = $least_time['duration'];
                        $veh_assigned[$vehid][] = $clustor[$least_time['key']];
                        unset($temp_details[0]->elements[$least_time['key']]);
                        unset($first_clust_arr_temp[$least_time['key']]);
                        $vehloop++;
                    }
                    //print_r($veh_assigned);die();
                    $remaining_cluster = count($first_clust_arr_temp);
                    while ($remaining_cluster) {
                        $veh_lastll = $this->get_vehicles_lastll($veh_assigned);
                        $veh_lastll_csv = implode('|', $veh_lastll);
                        $des_csv = implode('|', $first_clust_arr_temp);
                        $details_c = getTimeRqrd($veh_lastll_csv, $des_csv);
                        if (!isset($details_c[0]->elements)) {
                            echo "details_c===no data found1==empty--lastsequence<br/>";
                            $remaining_cluster--;
                            continue;
                        } else {
                            $least_load = $this->get_min_clust_data($details_c, $veh_assigned, $vehs);
                            $clustkey = $this->get_key($least_load['clustkey'], $first_clust_arr_temp);
                            $vid = $vehs[$least_load['vkey']];
                            $veh_assigned[$vid][] = $clustor[$clustkey];
                            unset($first_clust_arr_temp[$clustkey]);
                        }
                        $remaining_cluster--;
                    }
                }
                //print_r($veh_assigned);die();
                $f_orders = array();
                foreach ($veh_assigned as $vehid => $clustors) {
                    foreach ($clustors as $orders) {
                        foreach ($orders as $singleorder) {
                            $f_orders[$vehid][] = $singleorder;
                        }
                    }
                }
                $f_orders = $this->get_route_time($f_orders);
            } else {
                $first_clust_arr_temp = $first_clust_arr;
                $remaining_cluster = count($first_clust_arr_temp);
                while ($remaining_cluster) {
                    if (empty($veh_assigned)) {
                        $veh_lastll_csv = implode('|', $last_sequence);
                    } else {
                        $veh_lastll = $this->get_vehicles_lastll($veh_assigned, $last_sequence, $vehs);
                        $veh_lastll_csv = implode('|', $veh_lastll);
                    }
                    $des_csv = implode('|', $first_clust_arr_temp);
                    $details_c = getTimeRqrd($veh_lastll_csv, $des_csv);
                    if (!isset($details_c[0]->elements)) {
                        echo "details_c===no data found1<br/>";
                        $remaining_cluster--;
                        continue;
                    } else {
                        $least_load = $this->get_min_clust_data($details_c, $veh_assigned, $vehs);
                        $clustkey = $this->get_key($least_load['clustkey'], $first_clust_arr_temp);
                        $vid = $vehs[$least_load['vkey']];
                        $clustor[$clustkey][0]['time'] = $least_load['duration'];
                        $veh_assigned[$vid][] = $clustor[$clustkey];
                        unset($first_clust_arr_temp[$clustkey]);
                    }
                    $remaining_cluster--;
                }
                $f_orders = array();
                foreach ($veh_assigned as $vehid => $clustors) {
                    foreach ($clustors as $orders) {
                        foreach ($orders as $singleorder) {
                            $f_orders[$vehid][] = $singleorder;
                        }
                    }
                }
                $f_orders = $this->get_route_time($f_orders);
            }
        } else {
            $orders_arr = array();
            $orderids = array();
            $ocount = 0;
            foreach ($main_orders as $oid => $odata) {
                if ($ocount == MAX_ORDERS) {
                    break;
                }
                $orders_arr[] = "{$odata['lat']},{$odata['longi']}";
                $orderids[] = $oid;
                $ocount++;
            }
            $orders_csv = implode('|', $orders_arr);
            if (!empty($last_sequence)) {
                $startll = $last_sequence[$vehs[0]];
            }
            $sll = "$startll|$orders_csv";
            $details = getTimeRqrd($sll, $orders_csv);
            if (!isset($details[0]->elements)) {
                echo "get_nearest_order===no data found1==last else<br/>";
            } else {
                $temp_details = $details;
                $i = 0;
                while ($ocount) {
                    $least_time = $this->get_minimal_dist_data($temp_details[$i]->elements);
                    $main_orders[$orderids[$least_time['key']]]['time'] = $least_time['duration'];
                    $veh_assigned[$vehs[0]][] = $main_orders[$orderids[$least_time['key']]];
                    unset($temp_details[$i]);
                    $temp_details = $this->remove_array($temp_details, $least_time['key']);
                    $i = $least_time['key'] + 1;
                    $ocount--;
                }
                $f_orders = $veh_assigned;
            }
        }
        return $f_orders;
    }
    function get_minimal_dist_data($details) {
        $data;
        foreach ($details as $key => $val) {
            if (!isset($min_duration) || $val->duration->value < $min_duration) {
                $data = array('key' => $key, 'duration' => $val->duration->value, 'distance' => $val->distance->value);
                $min_duration = $val->duration->value;
            }
        }
        return $data;
    }
    function get_vehicles_lastll($veh_assigned, $last_sequence = false, $mapped_vehs = array()) {
        $lastll = array();
        foreach ($veh_assigned as $vehid => $clustdata) {
            $end_clust = end($clustdata);
            $odata = end($end_clust);
            $lastll[$vehid] = "{$odata['lat']},{$odata['longi']}";
        }
        if ($last_sequence) {
            $diff = array_diff_key($last_sequence, $lastll);
            if (!empty($diff)) {
                $lastll = $this->add_array($lastll, $diff, $mapped_vehs);
            }
        }
        return $lastll;
    }
    function getBusStop_lastll($veh_assigned, $last_sequence = false, $mapped_vehs = array()) {
        $lastll = array();
        foreach ($veh_assigned as $vehid => $clustdata) {
            $end_clust = end($clustdata);
            $odata = end($end_clust);
            $lastll[$vehid] = "{$odata['lat']},{$odata['lng']}";
        }
        if ($last_sequence) {
            $diff = array_diff_key($last_sequence, $lastll);
            if (!empty($diff)) {
                $lastll = $this->add_array($lastll, $diff, $mapped_vehs);
            }
        }
        return $lastll;
    }
    function add_array($lastll, $diff, $mapped) {
        $return = $lastll;
        foreach ($diff as $vehid => $ll) {
            $return[$vehid] = $ll;
        }
        $sorted = array();
        foreach ($mapped as $vehid) {
            $sorted[$vehid] = $return[$vehid];
        }
        return $sorted;
    }
    function remove_array($details, $delkey) {
        $clean = array();
        foreach ($details as $key => $test) {
            unset($test->elements[$delkey]);
            $clean[$key] = $test;
        }
        return $details;
    }
    function get_last_sequence($veh_assigned) {
        $lastll = array();
        foreach ($veh_assigned as $vehid => $orderdata) {
            $odata = end($orderdata);
            $lastll[$vehid] = "{$odata['lat']},{$odata['longi']}";
        }
        return $lastll;
    }
    function getLastBusStopSequence($veh_assigned) {
        $lastll = array();
        foreach ($veh_assigned as $vehid => $orderdata) {
            $odata = end($orderdata);
            $lastll[$vehid] = "{$odata['lat']},{$odata['lng']}";
        }
        return $lastll;
    }
    function get_key($clustkey, $first_clust_arr_temp) {
        $loop = 0;
        foreach ($first_clust_arr_temp as $key => $val) {
            if ($loop == $clustkey) {
                return $key;
            }
            $loop++;
        }
    }
    function get_min_clust_data($details, $veh_assigned, $vids) {
        foreach ($details as $vehkey => $orders) {
            //print_r($orders);die();
            $vid = $vids[$vehkey];
            if (!empty($veh_assigned)) {
                $weightT = vehicle_order_count($veh_assigned, $vid);
                $weight = ($weightT == 0) ? 1 : $weightT;
            } else {
                $weight = 1;
            }
            foreach ($orders->elements as $okey => $single_order) {
                //print_r($single_order);die();
                $load = $weight * $single_order->duration->value;
                if (!isset($min_load) || $load < $min_load) {
                    $min_load = $load;
                    $data = array('vkey' => $vehkey, 'load' => $min_load, 'clustkey' => $okey, 'duration' => $single_order->duration->value);
                }
            }
        }
        return $data;
    }
    function get_route_time($f_orders) {
        $sorted_orders = array();
        foreach ($f_orders as $vehid => $veh_orders) {
            if (count($veh_orders) == 1) {
                $sorted_orders[$vehid] = $veh_orders;
            } else {
                $startll = array("{$veh_orders[0]['lat']},{$veh_orders[0]['longi']}");
                $sorted_orders[$vehid][] = $veh_orders[0];
                unset($veh_orders[0]);
                $endll = array();
                $orderc = 0;
                foreach ($veh_orders as $sorder) {
                    if ($orderc == MAX_ORDERS) {
                        break;
                    }
                    $startll[] = "{$sorder['lat']},{$sorder['longi']}";
                    $endll[] = "{$sorder['lat']},{$sorder['longi']}";
                    $orderc++;
                }
                $startll_csv = implode('|', $startll);
                $endll_csv = implode('|', $endll);
                $details = getTimeRqrd($startll_csv, $endll_csv);
                if (!isset($details[0]->elements)) {
                    foreach ($veh_orders as $sngorders) {
                        $sorted_orders[$vehid][] = $sngorders;
                    }
                    echo "get_route_time===no data found1<br/>";
                    sleep(3);
                    continue;
                } else {
                    $temp_details = $details[0]->elements;
                    $ocount = count($endll);
                    $i = 1;
                    while ($ocount) {
                        $veh_orders[$i]['time'] = (int) $temp_details[$i - 1]->duration->value;
                        $sorted_orders[$vehid][] = $veh_orders[$i];
                        $ocount--;
                        $i++;
                    }
                }
            }
        }
        return $sorted_orders;
    }
    function getBusRouteTime($f_orders) {
        $sorted_orders = array();
        foreach ($f_orders as $vehid => $veh_orders) {
            if (count($veh_orders) == 1) {
                $sorted_orders[$vehid] = $veh_orders;
            } else {
                $startll = array("{$veh_orders[0]['lat']},{$veh_orders[0]['lng']}");
                $sorted_orders[$vehid][] = $veh_orders[0];
                unset($veh_orders[0]);
                $endll = array();
                $orderc = 0;
                foreach ($veh_orders as $sorder) {
                    if ($orderc == MAX_ORDERS) {
                        break;
                    }
                    $startll[] = "{$sorder['lat']},{$sorder['lng']}";
                    $endll[] = "{$sorder['lat']},{$sorder['lng']}";
                    $orderc++;
                }
                $startll_csv = implode('|', $startll);
                $endll_csv = implode('|', $endll);
                $details = getTimeRqrd($startll_csv, $endll_csv);
                if (!isset($details[0]->elements)) {
                    foreach ($veh_orders as $sngorders) {
                        $sorted_orders[$vehid][] = $sngorders;
                    }
                    echo "get_route_time===no data found1<br/>";
                    sleep(3);
                    continue;
                } else {
                    $temp_details = $details[0]->elements;
                    $ocount = count($endll);
                    $i = 1;
                    while ($ocount) {
                        $veh_orders[$i]['time'] = (int) $temp_details[$i - 1]->duration->value;
                        $sorted_orders[$vehid][] = $veh_orders[$i];
                        $ocount--;
                        $i++;
                    }
                }
            }
        }
        return $sorted_orders;
    }
    public function get_route_vehicles($fenceid, $slotid) {
        $Query = "SELECT a.vehicleid from " . SPEEDDB . ".routing_map as a";
        $Query .= " where a.customerno=$this->_Customerno and a.zoneid=$fenceid and a.slotid=$slotid and a.isdeleted=0  order by a.vehicleid";
        $vehicleid = array();
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $vehicleid[] = $row['vehicleid'];
            }
        }
        return $vehicleid;
    }
    public function get_route_vehicles_pickup($fenceid, $slotid) {
        $Query = "SELECT a.pickupboy from " . SPEEDDB . ".pickup_map as a";
        $Query .= " where a.customerno=$this->_Customerno and a.zoneid=$fenceid and a.slotid=$slotid and a.isdeleted=0  order by a.pickupboy";
        $vehicleid = array();
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $vehicleid[] = $row['pickupboy'];
            }
        }
        return $vehicleid;
    }
    public function getBusRouteVehicles() {
        $Query = "SELECT v.vehicleid, d.seatcapacity from " . SPEEDDB . ".vehicle as v INNER JOIN description d on d.vehicleid = v.vehicleid";
        $Query .= " where v.customerno=$this->_Customerno and v.groupid=1315 and v.isdeleted=0  order by v.vehicleid";
        $vehicleid = array();
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $arrdata['vehicleId'] = $row['vehicleid'];
                $arrdata['seatCapacity'] = $row['seatcapacity'];
                $vehicleid[] = $arrdata;
            }
        }
        return $vehicleid;
    }
    public function Order_based_data($fenceid, $slotid, $vehicleids) {
        $orders = Array();
        $Query = "SELECT a.areaid,a.order_id, a.id, a.lat, a.longi FROM " . DB_DELIVERY . ".master_orders as a";
        $Query .= " where a.delivery_date = '$this->algo_date' and a.customerno=$this->_Customerno and a.fenceid=$fenceid and a.slot=$slotid and a.is_sequence=0 and a.lat!='' and a.longi!='' ";
        $Query .= " order by a.areaid";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $orders[$row['id']] = array(
                    'lat' => $row['lat'],
                    'longi' => $row['longi'],
                    'vehicle_id' => $vehicleids[0], //default vehicle assigned
                    'order_id' => $row['id'],
                    'cust_order_id' => $row['order_id'],
                    'areaid' => $row['areaid'],
                );
            }
        }
        return $orders;
    }
    public function Order_based_data_pickup($fenceid, $slotid, $vehicleids) {
        $orders = Array();
        foreach ($vehicleids as $val) {
            $Query = "SELECT a.oid, a.orderid,  b.lat, b.lng FROM " . DB_PICKUP . ".pickup_order as a
            inner join " . DB_PICKUP . ".pickup_vendor as b on a.vendorno = b.vendorid ";
            $Query .= " where a.customerno=$this->_Customerno and pickupboyid=$val and orderid!='' and b.lat!='' and b.lng!='' ";
            //$Query .= " where a.delivery_date = '$this->algo_date' and a.customerno=$this->_Customerno and a.fenceid=$fenceid and a.slot=$slotid and a.lat!='' and a.longi!='' ";
            //$Query .= " order by a.areaid"; -- Needed For Multiple area
            $this->_databaseManager->executeQuery($Query);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextrow()) {
                    $orders[$val][] = array(
                        'lat' => $row['lat'],
                        'longi' => $row['lng'],
                        'vehicle_id' => $val, //default vehicle assigned
                        'order_id' => $row['oid'],
                        'cust_order_id' => $row['orderid'],
                        'areaid' => 1,
                    );
                }
            }
        }
        return $orders;
    }
    public function insertOrderSeq($final_sequence) {
        $insert_arr = array();
        $orders_arr = array();
        $orderdel = array();
        $date = date('Y-m-d H:i:s');
        $updated_by = retval_issetor($_SESSION['userid']);
        $columns = '(vehicle_id, order_id, sequence, time_taken, update_time, updated_by)';
        $i = 1;
        $delete_arr = array();
        foreach ($final_sequence as $vehid => $vehorders) {
            foreach ($vehorders as $data) {
                if (!empty($data['order_id'])) {
                    $delete_arr[] = $data['order_id'];
                    $delboyid = $this->get_deliveryboyid($data['vehicle_id']);
                    $time_taken = isset($data['time']) ? $data['time'] : '';
                    $insert_arr[] = " ($vehid, {$data['order_id']}, $i, '$time_taken', '$date',$updated_by) ";
                    $orders_arr[] = "{$data['order_id']}";
                    $orderdel[] = array(
                        'orderid' => $data['order_id'],
                        'delboyid' => $delboyid,
                    );
                    $i++;
                }
            }
            $i = 1;
        }
        if (!empty($delete_arr)) {
            $delete_val = implode(',', $delete_arr);
            $del_Query = "delete from " . DB_DELIVERY . ".order_route_sequence where order_id in ($delete_val)";
            $this->_databaseManager->executeQuery($del_Query);
        }
        if (!empty($insert_arr)) {
            $insert_val = implode(',', $insert_arr);
            $Query = "insert into " . DB_DELIVERY . ".order_route_sequence $columns values $insert_val";
            $this->_databaseManager->executeQuery($Query);
        }
//        if (!empty($orders_arr)) {
        //            $orders_val = implode(',', $orders_arr);
        //            $Query = "update " . DB_DELIVERY . ".master_orders SET is_sequence=1 where id IN($orders_val)";
        //            $this->_databaseManager->executeQuery($Query);
        //        }
        if (!empty($orderdel)) {
            foreach ($orderdel as $val) {
                $Query = "update " . DB_DELIVERY . ".master_orders SET deliveryboyid = " . $val['delboyid'] . " ,is_sequence=1 where id = " . $val['orderid'];
                $this->_databaseManager->executeQuery($Query);
            }
        }
    }
    public function insertOrderSeq_Reset($final_sequence, $vehicleid, $userid) {
        $insert_arr = array();
        $date = date('Y-m-d H:i:s');
        $updated_by = retval_issetor($userid);
        $columns = '(vehicle_id, order_id, sequence, time_taken, update_time, updated_by)';
        $i = 1;
        $delete_arr = array();
        foreach ($final_sequence as $data) {
            $delete_arr[] = $data['order_id'];
            $time_taken = isset($data['time']) ? $data['time'] : '';
            $insert_arr[] = " ($vehicleid, {$data['order_id']}, $i, '$time_taken', '$date', $updated_by) ";
            $i++;
        }
        $delete_val = implode(',', $delete_arr);
        $del_Query = "delete from " . DB_DELIVERY . ".order_route_sequence where order_id in ($delete_val)";
        $this->_databaseManager->executeQuery($del_Query);
        $insert_val = implode(',', $insert_arr);
        $Query = "insert into " . DB_DELIVERY . ".order_route_sequence $columns values $insert_val";
        $this->_databaseManager->executeQuery($Query);
    }
    public function insertOrderSeq_pickup($final_sequence) {
        $insert_arr = array();
        $date = date('Y-m-d H:i:s');
        $updated_by = retval_issetor($_SESSION['userid']);
        $columns = '(pickupid, orderid, sequence, time_taken, update_time, updated_by)';
        $i = 1;
        $delete_arr = array();
        foreach ($final_sequence as $vehid => $vehorders) {
            foreach ($vehorders as $data) {
                $delete_arr[] = $data['order_id'];
                $time_taken = isset($data['time']) ? $data['time'] : '';
                $insert_arr[] = " ($vehid, {$data['order_id']}, $i, '$time_taken', '$date', $updated_by) ";
                $i++;
            }
            $i = 1;
        }
        $delete_val = implode(',', $delete_arr);
        $del_Query = "delete from " . DB_PICKUP . ".order_route_sequence where orderid in ($delete_val)";
        $this->_databaseManager->executeQuery($del_Query);
        $insert_val = implode(',', $insert_arr);
        $Query = "insert into " . DB_PICKUP . ".order_route_sequence $columns values $insert_val";
        $this->_databaseManager->executeQuery($Query);
    }
    public function get_deliveryboyid($vehicleid) {
        $query = "select userid from user where delivery_vehicleid=" . $vehicleid;
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $userid = $row['userid'];
            }
        }
        return $userid;
    }
    public function insertBusStopSeq($final_sequence, $vehicleid) {
        $insert_arr = array();
        $orders_arr = array();
        $orderdel = array();

        $date = date('Y-m-d H:i:s');
        $updated_by = retval_issetor($_SESSION['userid']);

        $columns = '(vehicle_id, busStopId, sequence, update_time, updated_by)';
        $i = 1;
        $delete_arr = array();

        foreach ($final_sequence as $data) {
            //print_r($data);
            if (!empty($data['busStopId'])) {
                $delete_arr[] = $data['busStopId'];
                $time_taken = isset($data['time']) ? $data['time'] : '';
                $insert_arr[] = " ($vehicleid, {$data['busStopId']}, $i, '$date', $updated_by) ";
                $orders_arr[] = "{$data['busStopId']}";
                $orderdel[] = array(
                    'busStopId' => $data['busStopId'],
                );
                $i++;
            }
        }

        if (!empty($delete_arr)) {
            $delete_val = implode(',', $delete_arr);
            $del_Query = "delete from bus_route_sequence where busStopId in ($delete_val)";
            $this->_databaseManager->executeQuery($del_Query);
        }
        if (!empty($insert_arr)) {
            $insert_val = implode(',', $insert_arr);
            $Query = "insert into bus_route_sequence $columns values $insert_val";
            $this->_databaseManager->executeQuery($Query);
        }
        if (!empty($orderdel)) {
            foreach ($orderdel as $val) {
                $Query = "update busStop SET isAlloted=1 where busStopId = " . $val['busStopId'];
                $this->_databaseManager->executeQuery($Query);
            }
        }
    }
}
