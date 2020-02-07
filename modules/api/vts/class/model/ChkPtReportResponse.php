<?php

/**
 * Description of CheckpointResponse
 *
 * @author Mrudang
 */
class ChkPtReportResponse {

    function __construct($objChkPtData) {
        $this->checkpointName = $objChkPtData->checkpointName;
        $this->startTime = $objChkPtData->startTime;
        $this->endTime = $objChkPtData->endTime;
        $this->inTemperature = isset($objChkPtData->inTemperature) ? $objChkPtData->inTemperature : "";
        $this->outTemperature = isset($objChkPtData->outTemperature) ? $objChkPtData->outTemperature : "";
        $this->timeSpent = $objChkPtData->timeSpent;
        $this->eta = $objChkPtData->eta;
        $this->etaStatus = $objChkPtData->etaStatus;
        $this->cumulativeDistance = $objChkPtData->cumulativeDistance;
    }

    // <editor-fold defaultstate="collapsed" desc="Public Variables">
    public $checkpointName;
    public $startTime;
    public $endTime;
    public $inTemperature;
    public $outTemperature;
    public $timeSpent;
    public $eta;
    public $etaStatus;
    public $cumulativeDistance;

    // </editor-fold>
}
