<?php

class GeoCoder {

    function __construct($customerno) {
        // Constructor.
        $this->_Customerno = $customerno;
        $this->_databaseManager = new driverdatabase(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
    }

    public function get_location_bylatlong($lat, $long) {
        $location_string = '';
        $latint = floor($lat);
        $longint = floor($long);
        $pdo = $this->db->CreatePDOConn();
        $geoloc_query = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( " . $lat . "- `lat` ) * PI( ) /180 /2 ) , 2 ) +
		 COS( " . $lat . " * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( " . $long . " - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
		 AS distance FROM geotest WHERE `latfloor` = " . $latint . " AND `longfloor` = " . $longint . " HAVING distance < 2 AND customerno = " . $this->_Customerno . " ORDER BY distance LIMIT 0,1 ";
        $arrResult = $pdo->query($geoloc_query)->fetchAll(PDO::FETCH_ASSOC);
        $this->db->ClosePDOConn($pdo);
        if (count($arrResult) == 1) {
            if ($arrResult[0]['distance'] > 1) {
                $location_string = round($arrResult[0]['distance'], 2) . " Km from " . $arrResult[0]['location'] . ", " . $arrResult[0]['city'] . ", " . $arrResult[0]['state'];
            } else {
                $location_string = "Near " . $arrResult[0]['location'] . ", " . $arrResult[0]['city'] . ", " . $arrResult[0]['state'];
            }
        } else {
            $geolocation_query = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( " . $lat . "- `lat` ) * PI( ) /180 /2 ) , 2 ) +
                     COS( " . $lat . " * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( " . $long . " - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
                     AS distance FROM geotest WHERE `latfloor` = " . $latint . " AND `longfloor` = " . $longint . " HAVING distance <10 AND customerno = 0 ORDER BY distance LIMIT 0,1 ";
            $pdo = $this->db->CreatePDOConn();
            $arrResult = $pdo->query($geolocation_query)->fetchAll(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            if (count($arrResult) == 1) {
                if ($arrResult[0]['distance'] > 1) {
                    $location_string = round($arrResult[0]['distance'], 2) . " Km from " . $arrResult[0]['location'] . ", " . $arrResult[0]['city'] . ", " . $arrResult[0]['state'];
                } else {
                    $location_string = "Near " . $arrResult[0]['location'] . ", " . $arrResult[0]['city'] . ", " . $arrResult[0]['state'];
                }
            } else {
                $location_string = "Unable to Pull Location";
            }
        }
        return $location_string;
    }

    /*
public function get_city_bylatlong($lat,$long)
{
$latint = floor($lat);
$longint = floor($long);

$LocQuery = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( ".$lat."- `lat` ) * PI( ) /180 /2 ) , 2 ) +
COS( ".$lat." * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( ".$long." - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
AS distance FROM geotest WHERE `latfloor` = ".$latint." AND `longfloor` = ".$longint." HAVING distance <1 AND customerno = ".$this->_Customerno." ORDER BY distance LIMIT 0,1 ";
$geoloc_query = sprintf($LocQuery);
$this->_databaseManager->executeQuery($geoloc_query);
if ($this->_databaseManager->get_rowCount() > 0)
{
while ($row = $this->_databaseManager->get_nextRow())
{
$location_string = $row['city'].", ".$row['state'];

}
return $location_string;
}
else{

$Query = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( ".$lat."- `lat` ) * PI( ) /180 /2 ) , 2 ) +
COS( ".$lat." * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( ".$long." - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
AS distance FROM geotest WHERE `latfloor` = ".$latint." AND `longfloor` = ".$longint." HAVING distance <10 AND customerno = 0 ORDER BY distance LIMIT 0,1 ";
$geolocation_query = sprintf($Query);
$this->_databaseManager->executeQuery($geolocation_query);

if ($this->_databaseManager->get_rowCount() > 0)
{
while ($row = $this->_databaseManager->get_nextRow())
{
$location_string = $row['city'].", ".$row['state'];

}
return $location_string;
}else{
return "Unable to Pull Location";
}
return null;
}
}

public function get_all_location()
{
$locations = Array();
//$Query = "SELECT cname,checkpointid,cgeolat, cgeolong, crad FROM `checkpoint` where customerno=%d AND isdeleted=0";
$Query = "SELECT * FROM `geotest` where customerno=%d ";

$checkpointQuery = sprintf($Query,$this->_Customerno);
$this->_databaseManager->executeQuery($checkpointQuery);

if ($this->_databaseManager->get_rowCount() > 0)
{
while ($row = $this->_databaseManager->get_nextRow())
{
$location = new VOVehicle();
$location->geotestid = $row['geotestid'];
$location->location = $row['location'];
$location->city = $row['city'];
$location->state = $row['state'];
$location->lat = $row['lat'];
$location->long = $row['long'];
$locations[] = $location;
}

return $locations;
}
return null;
}

public function get_location($geotestid)
{
$Query = "SELECT * FROM `geotest` where geotestid =%d AND customerno=%d ";
$locationQuery = sprintf($Query,Sanitise::Long($geotestid),$this->_Customerno);
$this->_databaseManager->executeQuery($locationQuery);

if ($this->_databaseManager->get_rowCount() > 0)
{
while ($row = $this->_databaseManager->get_nextRow())
{
$location = new VOVehicle();
$location->geotestid = $row['geotestid'];
$location->location = $row['location'];
$location->city = $row['city'];
$location->state = $row['state'];
$location->lat = $row['lat'];
$location->long = $row['long'];
}

return $location;
}
return null;
}

public function SaveLocation($location, $userid)
{
$lat = floor($location->geolat);
$long = floor($location->geolong);
$Query = "INSERT INTO geotest(`location`,`city`,`state`,`lat`,`long`,`latfloor`,`longfloor`,`customerno`) VALUES ('%s','%s','%s',%f,%f,%d,%d,%d)";
$SQL = sprintf($Query, Sanitise::String($location->location), Sanitise::String($location->city), Sanitise::String($location->state), Sanitise::Float($location->geolat),Sanitise::Float($location->geolong), Sanitise::Long($lat), Sanitise::Long($long), $this->_Customerno);
$this->_databaseManager->executeQuery($SQL);

}

public function EditLocation($location, $userid)
{
$lat = floor($location->geolat);
$long = floor($location->geolong);
$Query =  "Update geotest Set `location`='%s',`city`='%s',`state`='%s',`lat`='%f',`long`='%f',`latfloor`=%d,`longfloor`=%d WHERE geotestid = %d AND customerno = %d";
$SQL = sprintf($Query,Sanitise::String($location->location), Sanitise::String($location->city), Sanitise::String($location->state), Sanitise::Float($location->geolat),Sanitise::Float($location->geolong),
Sanitise::Long($lat), Sanitise::Long($long), Sanitise::Long($location->geotestid), $this->_Customerno);
$this->_databaseManager->executeQuery($SQL);
}

public function delete_location($delid)
{
$Query =  "DELETE FROM `geotest` WHERE geotestid = %d AND customerno = %d";
$SQL = sprintf($Query, Sanitise::Long($delid), $this->_Customerno);
$this->_databaseManager->executeQuery($SQL);
}

public function get_use_geolocation($customerno)
{
$Query = "SELECT use_geolocation FROM `customer` where customerno=%d ";
$locationQuery = sprintf($Query,$this->_Customerno);
$this->_databaseManager->executeQuery($locationQuery);

if ($this->_databaseManager->get_rowCount() > 0)
{
while ($row = $this->_databaseManager->get_nextRow())
{
$use_geolocation = $row['use_geolocation'];
}

return $use_geolocation;
}
return null;
}
 */
}
