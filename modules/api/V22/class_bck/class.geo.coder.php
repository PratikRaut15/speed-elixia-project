<?php

class GeoCoder {

    function __constuctor($customerno) {
        // Constructor.
        $this->_Customerno = $customerno;
        $this->_databaseManager = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
    }

    public function get_location_bylatlong($lat, $long) {
        $use_geolocation = $this->get_use_geolocation();
        $pullLocation = 0;
        $address = null;
        $latint = floor($lat);
        $longint = floor($long);
        $pdo = $this->db->CreatePDOConn();

        $LocQuery = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( " . $lat . "- `lat` ) * PI( ) /180 /2 ) , 2 ) +
        COS( " . $lat . " * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( " . $long . " - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
        AS distance, checkpointid FROM geotest WHERE `latfloor` = " . $latint . " AND `longfloor` = " . $longint . " HAVING  customerno IN(" . $this->_Customerno . ") ORDER BY distance LIMIT 0,1 ";
        $geoloc_query = sprintf($LocQuery);
        $arrResult = $pdo->query($geoloc_query)->fetchAll(PDO::FETCH_ASSOC);
        $this->db->ClosePDOConn($pdo);
        if (count($arrResult) == 1) {
            $city_state = '';


            if($arrResult[0]['created_on'] == "0000-00-00 00:00:00"){
                    $city_state = ", " . $arrResult[0]['city'] . ", " . $arrResult[0]['state'];
                }

                if ($arrResult[0]['checkpointid'] != 0 && $arrResult[0]['distance'] < 0.5) {
                    return $location_string = round($arrResult[0]['distance'], 2) . " Km from " . $arrResult[0]['location'] . $city_state;
                }
                else if($arrResult[0]['distance'] < 0.1 ) {
                   return $location_string = "Near " . $arrResult[0]['location'] . $city_state;
                }
        }

        $LocQuery = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( " . $lat . "- `lat` ) * PI( ) /180 /2 ) , 2 ) +
        COS( " . $lat . " * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( " . $long . " - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
        AS distance FROM geotest WHERE `latfloor` = " . $latint . " AND `longfloor` = " . $longint . " HAVING distance < 0.1 AND customerno IN(0) ORDER BY distance LIMIT 0,1 ";
        $geoloc_query = sprintf($LocQuery);
        $arrResult = $pdo->query($geoloc_query)->fetchAll(PDO::FETCH_ASSOC);
        $this->db->ClosePDOConn($pdo);
        if (count($arrResult) == 1) {
            $city_state = '';
            if ($arrResult[0]['created_on'] == "0000-00-00 00:00:00") {
                $city_state = ", " . $arrResult[0]['city'] . ", " . $arrResult[0]['state'];
            }
            $location_string = "Near " . $arrResult[0]['location'] . $city_state;

            return $location_string;
        }
        else {
            $pullLocation = 1;
            if (isset($use_geolocation) && $use_geolocation == 1) {
                $API = "http://www.speed.elixiatech.com/location.php?lat=" . $lat . "&long=" . $long . "";
                $location = json_decode(file_get_contents("$API&sensor=false"));
                if (isset($location) && isset($location->results[0]->formatted_address) && $location->results[0]->formatted_address != "" && $location->status != 'ZERO_RESULTS' && $location->status != 'OVER_QUERY_LIMIT') {
                    @$address = "Near " . $location->results[0]->formatted_address;
                    $location1 = $this->checkLocationAddress($location->results[0]->formatted_address, $lat, $long);
                    if($location1 ==''){
                        $this->insertGeoLocation($location, $lat, $long);
                        $pullLocation = 0;
                        return $address;
                    }
                }
            }
            if ($pullLocation == 1) {
                $pdo = $this->db->CreatePDOConn();
                $Query = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( " . $lat . "- `lat` ) * PI( ) /180 /2 ) , 2 ) +
                COS( " . $lat . " * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( " . $long . " - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
                AS distance FROM geotest WHERE `latfloor` = " . $latint . " AND `longfloor` = " . $longint . " HAVING distance < 10 AND customerno IN(0," . $this->_Customerno . ") ORDER BY distance LIMIT 0,1 ";
                $geolocation_query = sprintf($Query);
                $arrResult = $pdo->query($geoloc_query)->fetchAll(PDO::FETCH_ASSOC);
                $this->db->ClosePDOConn($pdo);
                if (count($arrResult) == 1) {

                    $city_state = '';
                    if ($arrResult[0]['created_on'] == "0000-00-00 00:00:00") {
                        $city_state = ", " . $arrResult[0]['city'] . ", " . $arrResult[0]['state'];
                    }


                    $location_string = round($row['distance'], 2) . " Km from " . $row['location'] . $city_state;

                    return $location_string;
                }
                else {
                    return "Unable to Pull Location";
                }
                return null;
            }
        }
    }

    public function get_use_geolocation() {
        $pdo = $this->db->CreatePDOConn();
        $Query = "SELECT use_geolocation FROM `customer` where customerno=%d ";
        $locationQuery = sprintf($Query, $this->_Customerno);
        if ($pdo->query($locationQuery)) {
            $arrResult = $pdo->query($locationQuery)->fetchAll(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);

            if (count($arrResult) == 1) {

                $use_geolocation = $arrResult[0]['use_geolocation'];


                return $use_geolocation;
            }
        }
    }
    public function checkLocationAddress($location, $lat, $long)
    {
        $location1='';
        $pdo = $this->db->CreatePDOConn();
        $sqlQuery = "SELECT * FROM geotest WHERE (lat LIKE '%$lat%' and `long` LIKE '%$long%') OR location LIKE '%$location%'";
        //$locationQuery = sprintf($sqlQuery);
        $this->_databaseManager->executeQuery($sqlQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $location1 = $row['location'];
            }

            return $location1;
        }
        if ($pdo->query($sqlQuery)) {
            $arrResult = $pdo->query($sqlQuery)->fetchAll(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);

            if (count($arrResult) == 1) {

                $location1 = $arrResult[0]['location'];


                return $location1;
            }
        }
    }
    public function insertGeoLocation($location, $lat, $long) {
        $road = '';
        $city = '';
        $state = '';
        $country = '';
        $today = date('Y-m-d h:i:s');
        if (isset($location->results[0]->address_components)) {
            foreach ($location->results[0]->address_components as $add) {
                if ($add->types[0] == 'country') {
                    $country = $add->long_name;
                }
                if ($add->types[0] == 'administrative_area_level_1') {
                    $state = $add->long_name;
                }
                if ($add->types[0] == 'administrative_area_level_2') {
                    $city = $add->long_name;
                }
                if ($add->types[0] == 'route') {
                    $road = $add->long_name;
                }
            }
        }

        $objLocation = new stdClass();
        $objLocation->lat = $lat;
        $objLocation->long = $long;
        if ($road == "Unnamed Road" && $location->results[1]->formatted_address != "") {
            $objLocation->location = $location->results[1]->formatted_address;
        }
        else {
            $objLocation->location = $location->results[0]->formatted_address;
        }
        $objLocation->city = $city;
        $objLocation->state = $state;
        $objLocation->country = $country;
        $objLocation->latfloor = floor($lat);
        $objLocation->longfloor = floor($long);
        $objLocation->customerno = 0;
        $pdo = $this->db->CreatePDOConn();
        $Query = "INSERT INTO geotest(`location`,`city`,`state`,`country`,`lat`,`long`,`latfloor`,`longfloor`,`customerno`,`created_on`) VALUES ('%s','%s','%s','%s',%f,%f,%d,%d,%d,'%s')";
        $SQL = sprintf($Query, $objLocation->location, $objLocation->city, $objLocation->state, $objLocation->country, $objLocation->lat, $objLocation->long, $objLocation->latfloor, $objLocation->longfloor, $objLocation->customerno, $today);
        $arrResult = $pdo->query($SQL);
        $this->db->ClosePDOConn($pdo);
    }

}
