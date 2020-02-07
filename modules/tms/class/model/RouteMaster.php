<?php

/**
 * Description of RouteMaster
 *
 * @author Mrudang Vora
 */
class RouteMaster {

    // <editor-fold defaultstate="collapsed" desc="Private Variables">
    private $routemasterid;
    private $routename;
    private $routedescription;
    private $fromlocationid;
    private $tolocationid;
    private $distance;
    private $travellingtime;
    private $customerno;

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Public Properties">
    public function getroutemasterid() {
        return $this->routemasterid;
    }

    public function setroutemasterid($paramroutemasterid) {
        $this->routemasterid = $paramroutemasterid;
    }

    public function getroutename() {
        return $this->routename;
    }
    public function getroutedescription() {
        return $this->routedescription;
    }

    public function setroutename($paramroutename) {
        $this->routename = $paramroutename;
    }
    public function setroutedescription($paramroutedescription) {
        $this->routedescription = $paramroutedescription;
    }

    public function getfromlocationid() {
        return $this->fromlocationid;
    }

    public function setfromlocationid($paramfromlocationid) {
        $this->fromlocationid = $paramfromlocationid;
    }

    public function gettolocationid() {
        return $this->tolocationid;
    }

    public function settolocationid($paramtolocationid) {
        $this->tolocationid = $paramtolocationid;
    }

    public function getdistance() {
        return $this->distance;
    }

    public function setdistance($paramdistance) {
        $this->distance = $paramdistance;
    }

    public function gettravellingtime() {
        return $this->travellingtime;
    }

    public function settravellingtime($paramtravellingtime) {
        $this->travellingtime = $paramtravellingtime;
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
