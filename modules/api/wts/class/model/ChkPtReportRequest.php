<?php

/**
 * Description of Checkpoint Request
 *
 * @author Mrudang
 */
class ChkPtReportRequest {

    function __construct() {

    }

    // <editor-fold defaultstate="collapsed" desc="Public Variables">
    /**
     * userkey
     * @var string
     * @required
     */
    public $userkey;
    public $vehicleId;
    public $vehicleNo;
    public $startDateTime;
    public $endDateTime;
    public $customParams;

    // </editor-fold>
}
