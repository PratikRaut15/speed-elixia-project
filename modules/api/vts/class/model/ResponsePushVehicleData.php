<?php

/**
 * Description of ResponseClass
 *
 * @author Mrudang
 */
class ResponsePushVehicleData {

    function __construct($objPushVehicleDataRequest) {
        $this->vehicleNo = $objPushVehicleDataRequest->vehicleNo;
        $this->unitNo = $objPushVehicleDataRequest->unitNo;
        $this->customParams = $objPushVehicleDataRequest->customParams;
    }

    // <editor-fold defaultstate="collapsed" desc="Public Variables">
    /**
     * userkey
     * @var string
     * @required
     */
    public $vehicleNo;
    public $unitNo;
    public $isLiveDataUpdated;
    public $isReportDataUpdated;
    public $customParams;

    // </editor-fold>
}
