<?php

/**
 * Description of Factory
 *
 * @author Mrudang Vora
 */
class Factory {

    // <editor-fold defaultstate="collapsed" desc="Private Variables">
    private $factoryid;
    private $factorycode;
    private $factoryname;
    private $zoneid;
    private $customerno;

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Public Properties">
    public function getfactoryid() {
        return $this->factoryid;
    }

    public function setfactoryid($paramfactoryid) {
        $this->factoryid = $paramfactoryid;
    }

    public function getfactorycode() {
        return $this->factorycode;
    }
    public function getfactoryname() {
        return $this->factoryname;
    }

    public function setfactorycode($paramfactorycode) {
        $this->factorycode = $paramfactorycode;
    }
    public function setfactoryname($paramfactoryname) {
        $this->factoryname = $paramfactoryname;
    }

    public function getzoneid() {
        return $this->zoneid;
    }

    public function setzoneid($paramzoneid) {
        $this->zoneid = $paramzoneid;
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
