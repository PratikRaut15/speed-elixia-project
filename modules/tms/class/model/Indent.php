<?php

/**
 * Description of Indent
 *
 * @author Mrudang Vora
 */
class Indent {

 // <editor-fold defaultstate="collapsed" desc="Private Variables">
 private $indentid;
 private $transporterid;
 private $proposed_vehicletypeid;
 private $actual_vehicletypeid;
 private $vehicleno;
 private $factoryid;
 private $depotid;
 private $proposedindentid;
 private $indent_sku_mappingid;
 private $date_required;
 private $actual_deliverydate;
 private $loadstatus;
 private $shipmentno;
 private $remark;
 private $totalweight;
 private $totalvolume;
 private $isdelivered;
 private $customerno;
 private $startdate;
 private $enddate;

 // </editor-fold>
 //
    // <editor-fold defaultstate="collapsed" desc="Public Properties">

 public function getindentid() {
  return $this->indentid;
 }

 public function setindentid($paramindentid) {
  $this->indentid = $paramindentid;
 }

 public function gettransporterid() {
  return $this->transporterid;
 }

 public function settransporterid($paramtransporterid) {
  $this->transporterid = $paramtransporterid;
 }

 public function getproposed_vehicletypeid() {
  return $this->proposed_vehicletypeid;
 }

 public function setproposed_vehicletypeid($paramproposed_vehicletypeid) {
  $this->proposed_vehicletypeid = $paramproposed_vehicletypeid;
 }

 public function getactual_vehicletypeid() {
  return $this->actual_vehicletypeid;
 }

 public function setactual_vehicletypeid($paramactual_vehicletypeid) {
  $this->actual_vehicletypeid = $paramactual_vehicletypeid;
 }

 public function getvehicleno() {
  return $this->vehicleno;
 }

 public function setvehicleno($paramvehicleno) {
  $this->vehicleno = $paramvehicleno;
 }

 public function getfactoryid() {
  return $this->factoryid;
 }

 public function setfactoryid($paramfactoryid) {
  $this->factoryid = $paramfactoryid;
 }

 public function getdepotid() {
  return $this->depotid;
 }

 public function setdepotid($paramdepotid) {
  $this->depotid = $paramdepotid;
 }

 public function getproposedindentid() {
  return $this->proposedindentid;
 }

 public function setproposedindentid($paramproposedindentid) {
  $this->proposedindentid = $paramproposedindentid;
 }

 public function getdate_required() {
  return $this->date_required;
 }

 public function setdate_required($paramdate_required) {
  $this->date_required = $paramdate_required;
 }

 public function getactual_deliverydate() {
  return $this->actual_deliverydate;
 }

 public function setactual_deliverydate($paramactual_deliverydate) {
  $this->actual_deliverydate = $paramactual_deliverydate;
 }

 public function getindent_sku_mappingid() {
  return $this->indent_sku_mappingid;
 }

 public function setindent_sku_mappingid($paramindent_sku_mappingid) {
  $this->indent_sku_mappingid = $paramindent_sku_mappingid;
 }

 public function getloadstatus() {
  return $this->loadstatus;
 }

 public function setloadstatus($paramloadstatus) {
  $this->loadstatus = $paramloadstatus;
 }

 public function getshipmentno() {
  return $this->shipmentno;
 }

 public function setshipmentno($paramshipmentno) {
  $this->shipmentno = $paramshipmentno;
 }

 public function getremark() {
  return $this->remark;
 }

 public function setremark($paramremark) {
  $this->remark = $paramremark;
 }

 public function gettotalweight() {
  return $this->totalweight;
 }

 public function settotalweight($paramtotalweight) {
  $this->totalweight = $paramtotalweight;
 }

 public function gettotalvolume() {
  return $this->totalvolume;
 }

 public function settotalvolume($paramtotalvolume) {
  $this->totalvolume = $paramtotalvolume;
 }

 public function getisdelivered() {
  return $this->isdelivered;
 }

 public function setisdelivered($paramisdelivered) {
  $this->isdelivered = $paramisdelivered;
 }

 public function getstartdate() {
  return $this->startdate;
 }

 public function setstartdate($paramstartdate) {
  $this->startdate = $paramstartdate;
 }

 public function getenddate() {
  return $this->enddate;
 }

 public function setenddate($paramenddate) {
  $this->enddate = $paramenddate;
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
