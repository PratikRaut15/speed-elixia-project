<?php

/**
 * Description of Location
 *
 * @author Mrudang Vora
 */
class Location {

    // <editor-fold defaultstate="collapsed" desc="Private Variables">
    private $locationid;
    private $locationname;
    private $customerno;
    private $userid;
    private $todaysdate;

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Public Properties">

    public function getlocationid() {
        return $this->locationid;
    }

    public function setlocationid($paramlocationid) {
        $this->locationid = $paramlocationid;
    }

    public function getlocationname() {
        return $this->locationname;
    }

    public function setlocationname($paramlocationname) {
        $this->locationname = $paramlocationname;
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
