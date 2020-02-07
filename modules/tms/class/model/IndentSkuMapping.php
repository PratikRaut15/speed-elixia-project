<?php

/**
 * Description of IndentSkuMapping
 *
 * @author Mrudang Vora
 */
class IndentSkuMapping {

    // <editor-fold defaultstate="collapsed" desc="Private Variables">
    private $indent_sku_mappingid;
    private $indentid;
    private $skuid;
    private $no_of_units;
    private $customerno;

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Public Properties">

    public function getindent_sku_mappingid() {
        return $this->indent_sku_mappingid;
    }

    public function setindent_sku_mappingid($paramindent_sku_mappingid) {
        $this->indent_sku_mappingid = $paramindent_sku_mappingid;
    }

    public function getindentid() {
        return $this->indentid;
    }

    public function setindentid($paramindentid) {
        $this->indentid = $paramindentid;
    }

    public function getskuid() {
        return $this->skuid;
    }

    public function setskuid($paramskuid) {
        $this->skuid = $paramskuid;
    }

    public function getno_of_units() {
        return $this->no_of_units;
    }

    public function setno_of_units($paramno_of_units) {
        $this->no_of_units = $paramno_of_units;
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
