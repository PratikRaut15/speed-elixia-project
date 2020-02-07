<?php

/**
 * Description of Sku
 *
 * @author Mrudang Vora
 */
class Sku {

    // <editor-fold defaultstate="collapsed" desc="Private Variables">
    private $skuid;
    private $skucode;
    private $sku_description;
    private $volume;
    private $weight;
    private $netgross;
    private $typeid;
    private $type;
    private $customerno;

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Public Properties">
    public function getskuid() {
        return $this->skuid;
    }

    public function setskuid($paramskuid) {
        $this->skuid = $paramskuid;
    }

    public function getskucode() {
        return $this->skucode;
    }

    public function gettypeid() {
        return $this->typeid;
    }

    public function gettype() {
        return $this->type;
    }

    public function setskucode($paramskucode) {
        $this->skucode = $paramskucode;
    }

    public function settypeid($paramtypeid) {
        $this->typeid = $paramtypeid;
    }

    public function settype($paramtype) {
        $this->type = $paramtype;
    }

    public function getsku_description() {
        return $this->sku_description;
    }

    public function setsku_description($paramsku_description) {
        $this->sku_description = $paramsku_description;
    }

    public function getvolume() {
        return $this->volume;
    }

    public function setvolume($paramvolume) {
        $this->volume = $paramvolume;
    }

    public function getweight() {
        return $this->weight;
    }

    public function setweight($paramweight) {
        $this->weight = $paramweight;
    }

    public function getnetgross() {
        return $this->netgross;
    }

    public function setnetgross($paramnetgross) {
        $this->netgross = $paramnetgross;
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
