<?php

/**
 * Description of RequestCheckpoint
 *
 * @author Mrudang
 */
class clsVehSummaryResponse {

    function __construct($arrResponse) {
        $this->objReqData = $arrResponse['objReqData'];
        $this->vehicleno = isset($arrResponse['vehicleno']) ? $arrResponse['vehicleno'] : '';
        $this->distanceCovered = isset($arrResponse['distance']) ? $arrResponse['distance'] : '';
        $this->temp1Compliance = isset($arrResponse['temp1Compliance']) ? $arrResponse['temp1Compliance'] : '';
        $this->temp2Compliance = isset($arrResponse['temp2Compliance']) ? $arrResponse['temp2Compliance'] : '';
        $this->temp3Compliance = isset($arrResponse['temp3Compliance']) ? $arrResponse['temp3Compliance'] : '';
        $this->temp4Compliance = isset($arrResponse['temp4Compliance']) ? $arrResponse['temp4Compliance'] : '';
        $this->timestamp = isset($arrResponse['timestamp']) ? $arrResponse['timestamp'] : '';
    }

    // <editor-fold defaultstate="collapsed" desc="Public Variables">
    public $objReqData;
    public $vehicleno;
    public $distanceCovered;
    public $temp1Compliance;
    public $temp2Compliance;
    public $temp3Compliance;
    public $temp4Compliance;
    public $timestamp;

    // </editor-fold>
}
