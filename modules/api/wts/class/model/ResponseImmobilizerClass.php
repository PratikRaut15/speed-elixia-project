<?php

/**
 * Description of ResponseImobClass
 *
 * @author Ganesh 
 */
class ResponseImmobilizerClass {
    function __construct($arrResponse) {
        $this->vehicle = isset($arrResponse['vehicle']) ? $arrResponse['vehicle'] : '';
    }
    // <editor-fold defaultstate="collapsed" desc="Public Variables">
    public $vehicle;
    public $immobilizestatus;
    // </editor-fold>
}
