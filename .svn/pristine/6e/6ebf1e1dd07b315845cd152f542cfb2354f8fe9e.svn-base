<?php

/**
 * Description of Zone
 *
 * @author Mrudang Vora
 */
class Zone {

    // <editor-fold defaultstate="collapsed" desc="Private Variables">

    private $zoneid;
    private $zonename;
    private $customerno;
    private $userid;
    private $todaysdate;

    // </editor-fold>
    // 
    // <editor-fold defaultstate="collapsed" desc="Public Properties">

    public function getzoneid() {
        return $this->zoneid;
    }

    public function setzoneid($paramzoneid) {
        $this->zoneid = $paramzoneid;
    }

    public function getzonename() {
        return $this->zonename;
    }

    public function setzonename($paramzonename) {
        $this->zonename = $paramzonename;
    }

    public function getcustomerno() {
        return $this->customerno;
    }

    public function setcustomerno($paramcustomerno) {
        $this->customerno = $paramcustomerno;
    }

    public function gettodaysdate() {
        return $this->todaysdate;
    }

    public function settodaysdate($paramtodaysdate) {
        return $this->todaysdate = $paramtodaysdate;
    }

    public function getuserid() {
        return $this->userid;
    }

    public function setuserid($paramuserid) {
        return $this->userid = $paramuserid;
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
