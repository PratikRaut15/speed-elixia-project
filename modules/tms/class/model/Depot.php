<?php

/**
 * Description of depot
 *
 * @author Mrudang Vora
 */
class Depot {

    // <editor-fold defaultstate="collapsed" desc="Private Variables">
    private $depotid;
    private $depotname;
    private $depotcode;
    private $zoneid;
    private $locationid;
    private $factoryid;
    private $customerno;
    private $multidrop;
    private $multidropid;

    // </editor-fold>
    // 
    // <editor-fold defaultstate="collapsed" desc="Public Properties">
    public function getdepotid() {
        return $this->depotid;
    }

    public function setdepotid($paramdepotid) {
        $this->depotid = $paramdepotid;
    }

    public function getdepotname() {
        return $this->depotname;
    }

    public function setdepotname($paramdepotname) {
        $this->depotname = $paramdepotname;
    }

    public function getdepotcode() {
        return $this->depotcode;
    }

    public function setdepotcode($paramdepotcode) {
        return $this->depotcode = $paramdepotcode;
    }

    public function getzoneid() {
        return $this->zoneid;
    }

    public function setzoneid($paramzoneid) {
        $this->zoneid = $paramzoneid;
    }

    public function getlocationid() {
        return $this->locationid;
    }

    public function setlocationid($paramlocationid) {
        $this->locationid = $paramlocationid;
    }
    public function getfactoryid() {
        return $this->factoryid;
    }

    public function setfactoryid($paramfactoryid) {
        $this->factoryid = $paramfactoryid;
    }

    public function getcustomerno() {
        return $this->customerno;
    }

    public function setcustomerno($paramcustomerno) {
        $this->customerno = $paramcustomerno;
    }

    public function getmultidrop() {
        return $this->multidrop;
    }

    public function setmultidrop($parammultidrop) {
        $this->multidrop = $parammultidrop;
    }

    public function getmultidropid() {
        return $this->multidropid;
    }

    public function setmultidropid($parammultidropid) {
        $this->multidropid = $parammultidropid;
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
