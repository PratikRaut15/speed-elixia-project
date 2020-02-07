<?php

/**
 * Description of ResponseBuzzerClass
 *
 * @author Ganesh 
 */
class ResponseBuzzerClass {

    function __construct($arrResponse) {
        
        $this->vehicle = isset($arrResponse['vehicle']) ? $arrResponse['vehicle'] : '';
    }
    // <editor-fold defaultstate="collapsed" desc="Public Variables">
    public $vehicle;
    // </editor-fold>
}
