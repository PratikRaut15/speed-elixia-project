<?php

/**
 * Description of ResponseClass
 *
 * @author Mrudang
 */
class GetLatestVehicleResponse {

    function __construct($arrResponse) {
        $this->vehicleno = isset($arrResponse['vehicleno']) ? $arrResponse['vehicleno'] : '';
        $this->unitno = isset($arrResponse['unitno']) ? $arrResponse['unitno'] : '';
        $this->groupname = isset($arrResponse['groupname']) ? $arrResponse['groupname'] : '';
        $this->vehiclestatus = isset($arrResponse['vehiclestatus']) ? $arrResponse['vehiclestatus'] : '';
        $this->lat = isset($arrResponse['lat']) ? $arrResponse['lat'] : '';
        $this->lng = isset($arrResponse['lng']) ? $arrResponse['lng'] : '';
        $this->location = isset($arrResponse['location']) ? $arrResponse['location'] : '';
        $this->lastupdated = isset($arrResponse['lastupdated']) ? $arrResponse['lastupdated'] : '';
        $this->vehiclespeed = isset($arrResponse['vehiclespeed']) ? $arrResponse['vehiclespeed'] : '';
        $this->odometer = isset($arrResponse['odometer']) ? $arrResponse['odometer'] : '';
        $this->distance = isset($arrResponse['distance']) ? $arrResponse['distance'] : '';
        $this->temperature1 = isset($arrResponse['temp1']) ? $arrResponse['temp1'] : '';
        $this->temperature2 = isset($arrResponse['temp2']) ? $arrResponse['temp2'] : '';
        $this->temperature3 = isset($arrResponse['temp3']) ? $arrResponse['temp3'] : '';
        $this->temperature4 = isset($arrResponse['temp4']) ? $arrResponse['temp4'] : '';
        $this->drivername = isset($arrResponse['drivername']) ? $arrResponse['drivername'] : '';
    }

    // <editor-fold defaultstate="collapsed" desc="Public Variables">
    public $vehicleno;
    public $unitno;
    public $groupname;
    public $vehiclestatus;
    public $lat;
    public $lng;
    public $location;
    public $lastupdated;
    public $vehiclespeed;
    public $odometer;
    public $distance;
    public $temperature1;
    public $temperature2;
    public $temperature3;
    public $temperature4;
    public $drivername;

    // </editor-fold>
}
