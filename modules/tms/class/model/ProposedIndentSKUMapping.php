<?php

/**
 * Description of ProposedIndentSKUMapping
 *
 * @author Mrudang Vora
 */
class ProposedIndentSkuMapping {

    private $proposed_indent_sku_mappingid;
    private $proposedindentid;
    private $skuid;
    private $no_of_units;
    private $weight;
    private $volume;
    private $customerno;

    public function getproposed_indent_sku_mappingid() {
        return $this->proposed_indent_sku_mappingid;
    }

    public function setproposed_indent_sku_mappingid($paramproposed_indent_sku_mappingid) {
        $this->proposed_indent_sku_mappingid = $paramproposed_indent_sku_mappingid;
    }

    public function getproposedindentid() {
        return $this->proposedindentid;
    }

    public function setproposedindentid($paramproposedindentid) {
        $this->proposedindentid = $paramproposedindentid;
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

    public function getweight() {
        return $this->weight;
    }

    public function setweight($paramweight) {
        $this->weight = $paramweight;
    }

    public function getvolume() {
        return $this->volume;
    }

    public function setvolume($paramvolume) {
        $this->volume = $paramvolume;
    }

    public function getcustomerno() {
        return $this->customerno;
    }

    public function setcustomerno($paramcustomerno) {
        $this->customerno = $paramcustomerno;
    }

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
