<?php

/**
 * Description of ResponseFreezeClass
 *
 * @author Ganesh 
 */
class ResponseFreezeClass {

    function __construct($arrResponse) {
        
        $this->vehicleno = isset($arrResponse['vehicleno']) ? $arrResponse['vehicleno'] : '';
        $this->freezestatus = isset($arrResponse['freezestatus']) ? $arrResponse['freezestatus'] : '0';
    }
    // <editor-fold defaultstate="collapsed" desc="Public Variables">
    public $vehicleno;
    public $freezestatus;
    // </editor-fold>
}
