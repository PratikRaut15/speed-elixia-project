<?php

/**
 * Description of ResponseBuzzerClass
 *
 * @author Ganesh 
 */
class ResponseLocationVehicleClass {

    function __construct($arrResponse) {

        $this->datetime = isset($arrResponse['time']) ? $arrResponse['time'] : '';
        $this->lat = isset($arrResponse['devicelat']) ? $arrResponse['devicelat'] : '';
        $this->long = isset($arrResponse['devicelong']) ? $arrResponse['devicelong'] : '';
        $this->vehiclespeed = isset($arrResponse['curspeed']) ? $arrResponse['curspeed'] : '';
        $this->temperature1 = isset($arrResponse['tempsensor1']) ? $arrResponse['tempsensor1'] : '';
        $this->temperature2 = isset($arrResponse['tempsensor2']) ? $arrResponse['tempsensor2'] : '';
        $this->temperature3 = isset($arrResponse['tempsensor3']) ? $arrResponse['tempsensor3'] : '';
        $this->temperature4 = isset($arrResponse['tempsensor4']) ? $arrResponse['tempsensor4'] : '';
        $this->digitalstatus = isset($arrResponse['digitalstatus']) ? $arrResponse['digitalstatus'] : '';
        $this->vehiclestatus = isset($arrResponse['status']) ? $arrResponse['status'] : '';
        $this->distance = isset($arrResponse['distance']) ? $arrResponse['distance'] : '';
        $this->cumulative_distance = isset($arrResponse['cumulative_distance']) ? $arrResponse['cumulative_distance'] : '';
    }

    // <editor-fold defaultstate="collapsed" desc="Public Variables">
    public $datetime;
    public $lat;
    public $long;
    public $vehiclespeed;
    public $temperature1;
    public $temperature2;
    public $temperature3;
    public $temperature4;
    public $digitalstatus;
    public $vehiclestatus;
    public $distance;
    public $cumulative_distance;
    

    // </editor-fold>
}
