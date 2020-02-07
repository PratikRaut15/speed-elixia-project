<?php

/**
 * Description of Vehicle
 *
 * @author Mrudang Vora
 */
class Vehicle {

    // <editor-fold defaultstate="collapsed" desc="Private Variables">
    private $vehicleid;
    private $transporterid;
    private $vehicletypeid;
    private $vehicleno;
    private $customerno;

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Public Properties">
    public function getvehicleid() {
        return $this->vehicleid;
    }

    public function setvehicleid($paramvehicleid) {
        $this->vehicleid = $paramvehicleid;
    }

    public function gettransporterid() {
        return $this->transporterid;
    }

    public function settransporterid($paramtransporterid) {
        $this->transporterid = $paramtransporterid;
    }

    public function getvehicletypeid() {
        return $this->vehicletypeid;
    }

    public function setvehicletypeid($paramvehicletypeid) {
        $this->vehicletypeid = $paramvehicletypeid;
    }

    public function getvehicleno() {
        return $this->vehicleno;
    }

    public function setvehicleno($paramvehicleno) {
        $this->vehicleno = $paramvehicleno;
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
