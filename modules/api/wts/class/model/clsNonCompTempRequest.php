<?php

/**
 * Description of RequestCheckpoint
 *
 * @author Mrudang
 */
class clsNonCompTempRequest {

    function __construct() {

    }

    // <editor-fold defaultstate="collapsed" desc="Public Variables">
    /**
     * userkey
     * @var string
     * @required
     */
    public $userkey;
    public $customerNo;
    public $vehicleId;
    public $vehicleNo;
    //public $startDateTime;
    //public $endDateTime;

    public $SDate;
    public $EDate;
    public $STime;
    public $ETime;
    public $interval;

    public $customParams;

    // </editor-fold>
}
