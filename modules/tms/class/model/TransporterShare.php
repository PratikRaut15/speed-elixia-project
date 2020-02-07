<?php

/**
 * Description of TransporterShare
 *
 * @author Mrudang Vora
 */
class TransporterShare {

    // <editor-fold defaultstate="collapsed" desc="Private Variables">
    private $transportershareid;
    private $transporterid;
    private $factoryid;
    private $zoneid;
    private $sharepercent;
    private $customerno;

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Public Properties">
    public function gettransportershareid() {
        return $this->transportershareid;
    }

    public function settransportershareid($paramtransportershareid) {
        $this->transportershareid = $paramtransportershareid;
    }

    public function gettransporterid() {
        return $this->transporterid;
    }

    public function settransporterid($paramtransporterid) {
        $this->transporterid = $paramtransporterid;
    }
    
    public function getfactoryid() {
        return $this->factoryid;
    }

    public function setfactoryid($paramfactoryid) {
        $this->factoryid = $paramfactoryid;
    }

    public function getzoneid() {
        return $this->zoneid;
    }

    public function setzoneid($paramzoneid) {
        $this->zoneid = $paramzoneid;
    }

    public function getsharepercent() {
        return $this->sharepercent;
    }

    public function setsharepercent($paramsharepercent) {
        $this->sharepercent = $paramsharepercent;
    }

    public function getcustomerno() {
        return $this->customerno;
    }

    public function setcustomerno($paramcustomerno) {
        $this->customerno = $paramcustomerno;
    }

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Magic Functions">
    public function __set($name, $value) {
        $functionname = 'set' . $name;
        return $this->$functionname($value);
    }

    public function __get($name) {
        $functionname = 'get' . $name;
        return $this->$functionname();
    }

    // </editor-fold>
}
