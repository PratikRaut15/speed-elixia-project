<?php

/**
 * Description of ProposedIndentTransporterMapping
 *
 * @author Mrudang Vora
 */
class ProposedIndentTransporterMapping {

 // <editor-fold defaultstate="collapsed" desc="Private Variables">
 private $pitmappingid;
 private $proposedindentid;
 private $proposed_transporterid;
 private $proposed_vehicletypeid;
 private $actual_vehicletypeid;
 private $vehicleno;
 private $drivermobileno;
 private $isAccepted;
 private $isAutoRejected;
 private $remarks;
 private $customerno;
 private $startdate;
 private $enddate;

 // </editor-fold>
 //
    // <editor-fold defaultstate="collapsed" desc="Public Properties">

 public function getpitmappingid() {
  return $this->pitmappingid;
 }

 public function setpitmappingid($parampitmappingid) {
  $this->pitmappingid = $parampitmappingid;
 }

 public function getproposedindentid() {
  return $this->proposedindentid;
 }

 public function setproposedindentid($paramproposedindentid) {
  $this->proposedindentid = $paramproposedindentid;
 }

 public function getproposed_transporterid() {
  return $this->proposed_transporterid;
 }

 public function setproposed_transporterid($paramproposed_transporterid) {
  $this->proposed_transporterid = $paramproposed_transporterid;
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

 public function getdrivermobileno() {
  return $this->drivermobileno;
 }

 public function setdrivermobileno($paramdrivermobileno) {
  $this->drivermobileno = $paramdrivermobileno;
 }

 public function getisAccepted() {
  return $this->isAccepted;
 }

 public function setisAccepted($paramisAccepted) {
  $this->isAccepted = $paramisAccepted;
 }

 public function getisAutoRejected() {
  return $this->isAutoRejected;
 }

 public function setisAutoRejected($paramisAutoRejected) {
  $this->isAutoRejected = $paramisAutoRejected;
 }

 public function getremarks() {
  return $this->remarks;
 }

 public function setremarks($paramremarks) {
  $this->remarks = $paramremarks;
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
