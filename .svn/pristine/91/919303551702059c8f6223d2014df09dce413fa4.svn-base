<?php

//error_reporting(0);
//ini_set('display_errors', 'On');
require_once '../../lib/system/DatabaseTMSManager.php';

/**
 * class of TMS-module
 */
class IndentAlgo extends DatabaseTMSManager {

  public function __construct($customerno, $userid) {
    parent::__construct($customerno, $userid);
    $this->customerno = $customerno;
    $this->userid = $userid;
    $this->todaysdate = date("Y-m-d H:i:s");
  }

  //
  //<editor-fold defaultstate="collapsed" desc="Multi Sort Function ">

  /*
   *  This function sets up usort to sort a multiple dimmensional array,
   *  in asc or desc order, and case sensitive or not
   */
  public function multiKeySort(&$arrObjects, $sortFields, $reverse = false, $ignorecase = false) {
    // we want to make sure that field(s) we want to sort are in an array for the compare function
    if (!is_array($sortFields))
      $sortFields = array($sortFields);

    // our usort function that does all the work
    // notice the parameters
    usort($arrObjects, $this->sortcompare($sortFields, $reverse, $ignorecase));
  }

  /*
   * This is the usort compare function
   * It is preset to sort from asc and to not ignore case
   *
   * @return  bool  We only return a 1 , -1, or 0, which is what usort expects
   */

  public function sortcompare($sortFields, $reverse = false, $ignorecase = false) {
    return function($a, $b) use ($sortFields, $reverse, $ignorecase) {
      $cnt = 0;
      // check each sort field in the order specified
      foreach ($sortFields as $aField) {
        // check the value for ignorecase
        $ignore = is_array($ignorecase) ? $ignorecase[$cnt] : $ignorecase;
        // Determines whether to sort with or without case sensitive
        $result = $ignore ? strnatcasecmp($a[$aField], $b[$aField]) : strnatcmp($a[$aField], $b[$aField]);
        // the $result will be 1, -1, or 0
        // check to see if you want to reverse the sort order
        // to reverse the sort order you simply flip the return value by multplying the result by -1
        $revcmp = is_array($reverse) ? $reverse[$cnt] : $reverse;
        $result = $revcmp ? ($result * -1) : $result;

        // the first key that results in a non-zero comparison determines the order of the elements
        if ($result != 0)
          break;
        $cnt++;
      }
      //returns 1, -1, or 0
      return $result;
    };
  }

  // </editor-fold>
  //
    //<editor-fold defaultstate="collapsed" desc="Indent Algo Function ">
  function indentAlgo($factorydepotwise_records, $date) {
    // <editor-fold defaultstate="collapsed" desc="Get all details for a particular delivery">
    $factorywiseResult = array();
    $skuwiseResult = array();
    $transwise_weights = array();
    $leftOverFactory = array();
    $leftOverDepot = array();
    foreach ($factorydepotwise_records as $fact) {
      $objResult = new Object();

      // <editor-fold defaultstate="collapsed" desc="Get depot details for current delivery">
      /* Get particular depot details */
      $objDepot = new Depot();
      $objDepot->depotid = $fact['depotid'];
      $currentDepotDetail = get_depots($objDepot);
      // </editor-fold>
      //
      // <editor-fold defaultstate="collapsed" desc="Get factory details for current delivery">
      /* Get particular factory details */
      $objFactory = new Factory();
      $objFactory->factoryid = $fact['factoryid'];
      $currentFactoryDetail = get_factory($objFactory);
      // </editor-fold>
      //
      // <editor-fold defaultstate="collapsed" desc="Get transporters with their share details for current delivery">
      /* Get transporters by factory and zone */
      $objTransporterShare = new TransporterShare();
      $objTransporterShare->factoryid = $fact['factoryid'];
      $objTransporterShare->zoneid = $currentDepotDetail[0]['zoneid'];
      $transportersListWithShare = get_transportershare($objTransporterShare);

      $transporterlist = array();
      foreach ($transportersListWithShare as $trans) {
        $transporter = new Object();
        $transporter->factoryid = $fact['factoryid'];
        $transporter->depotid = $fact['depotid'];
        $transporter->zoneid = isset($currentDepotDetail[0]['zoneid']) ? $currentDepotDetail[0]['zoneid'] : 0;
        $transporter->depotname = isset($currentDepotDetail[0]['depotname']) ? $currentDepotDetail[0]['depotname'] : '';
        $transporter->factoryname = isset($currentFactoryDetail[0]['factoryname']) ? $currentFactoryDetail[0]['factoryname'] : '';
        $transporter->percentshare = $trans['sharepercent'];
        $transporter->transporterid = $trans['transporterid'];
        $transporter->transportername = $trans['transportername'];

        /* Get Actual share percent for transporter */
        $objTransporterActualShare = new TransporterActualShare();
        $objTransporterActualShare->transporterid = $trans['transporterid'];
        $objTransporterActualShare->factoryid = $fact['factoryid'];
        $objTransporterActualShare->zoneid = $transporter->zoneid;
        $arrTransporterActualShare = get_transporteractualshare($objTransporterActualShare);

        /*  Assign Actual Percent */
        $transporter->actualpercent = isset($arrTransporterActualShare[0]['actualpercent']) ? $arrTransporterActualShare[0]['actualpercent'] : 0;
        $transporter->deviation = $transporter->percentshare - $transporter->actualpercent;
        /* Calculate Deviation Percent */
        $transporter->deviationPercent = $transporter->percentshare + $transporter->deviation;

        // <editor-fold defaultstate="collapsed" desc="Get vehicle types for current transporter">
        /* Get vehicle types for the transporter */
        $vehicletypelist = array();
        $objVehTypeTransMapping = new Object();
        $objVehTypeTransMapping->customerno = $_SESSION['customerno'];
        $objVehTypeTransMapping->transporterid = $trans['transporterid'];
        $vehicletypes = get_vehtypetransporter_mapping($objVehTypeTransMapping);

        foreach ($vehicletypes as $vehtype) {
          $obj = new Object();
          $obj->transporterid = $vehtype['transporterid'];
          $obj->vehicletypeid = $vehtype['vehicletypeid'];
          $obj->vehiclecode = $vehtype['vehiclecode'];
          $obj->vehWeight = $vehtype['weight'];
          $obj->vehVolume = $vehtype['volume'];
          $vehicletypelist[] = $obj;
        }
        $transporter->vehtypes = $vehicletypelist;
        // </editor-fold>

        $transporterlist[] = $transporter;
      }
      // </editor-fold>

      $objResult->date_required = $fact['date_required'];
      $objResult->totalweight_from_factory_to_depot = $fact['weight'];
      $objResult->depotid = $fact['depotid'];
      $objResult->depotname = isset($currentDepotDetail[0]['depotname']) ? $currentDepotDetail[0]['depotname'] : '';
      $objResult->factoryid = $fact['factoryid'];
      $objResult->factoryname = isset($currentFactoryDetail[0]['factoryname']) ? $currentFactoryDetail[0]['factoryname'] : '';
      $objResult->zoneid = isset($currentDepotDetail[0]['zoneid']) ? $currentDepotDetail[0]['zoneid'] : 0;
      /*
       * Sort in descending order of deviation but if both deviation
       * are same, sort in descending order of deviation percent
       */
      usort($transporterlist, function($a, $b) {
        if ($a->deviation == $b->deviation) {
          return ($a->deviationPercent > $b->deviationPercent) ? -1 : 1;
        }
        return ($a->deviation > $b->deviation) ? -1 : 1;
      });
      $objResult->transporters = $transporterlist;

      $factorywiseResult[] = $objResult;
    }
    //echo "GET FactoryWise Records" . $this->todaysdate . "<br/>";
    //print_r($factorywiseResult);
    //die();
    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Process each delivery to create proposed-indents">
    $arrProposedIndents = array();
    $arrLeftOver = array();
    //echo "Proposed Indent Calculation start<br/>" . $this->todaysdate . "<br/>";
    foreach ($factorywiseResult as $factRecord) {
      $objProposedIndentDetail = new Object();
      //echo $factRecord->factoryid . " to " . $factRecord->depotid . "<br/>";
      //echo "Recalculate the actual percent Start<br/>" . $this->todaysdate . "<br/>";
      // <editor-fold defaultstate="collapsed" desc="Recalculate the actual percent for every factory delivery loop 2 onwards">
      /*
       * This is required to ensure that we get updated actual prcent with deviation
       * if there are 2 factory deliveries for 2 different depots belonging to same zone.
       * ***** Not required for first loop as we have already fetched the actual percent. *******
       * ***** reset() returns first element of an array ******
       */
      if ($factRecord != reset($factorywiseResult)) {
        $transporterlist = array();
        foreach ($factRecord->transporters as $transporter) {
          $objTransporterActualShare = new TransporterActualShare();
          $objTransporterActualShare->transporterid = $transporter->transporterid;
          $objTransporterActualShare->factoryid = $transporter->factoryid;
          $objTransporterActualShare->zoneid = $transporter->zoneid;
          $arrTransporterActualShare = get_transporteractualshare($objTransporterActualShare);


          $transporter->actualpercent = isset($arrTransporterActualShare[0]['actualpercent']) ? $arrTransporterActualShare[0]['actualpercent'] : 0;
          $transporter->deviation = $transporter->percentshare - $transporter->actualpercent;

          $transporter->deviationPercent = $transporter->percentshare + $transporter->deviation;

          $transporterlist[] = $transporter;
        }
        usort($transporterlist, function($a, $b) {
          if ($a->deviation == $b->deviation) {
            return ($a->deviationPercent > $b->deviationPercent) ? -1 : 1;
          }
          return ($a->deviation > $b->deviation) ? -1 : 1;
        });
        $factRecord->transporters = $transporterlist;
      }
      //</editor-fold>
      //echo "Recalculate the actual percent END<br/>" . $this->todaysdate . "<br/>";
      //
      //echo "SKU Details For Each Factory Depot start<br/>" . $this->todaysdate . "<br/>";
      // <editor-fold defaultstate="collapsed" desc="Get sku details for each factory-depot route">

      $objSkuWeight = new Object();
      $objSkuWeight->customerno = $_SESSION['customerno'];
      $objSkuWeight->factoryid = $factRecord->factoryid;
      $objSkuWeight->depotid = $factRecord->depotid;
      $objSkuWeight->daterequired = $factRecord->date_required;
      $skuwise_records = get_skuweight($objSkuWeight);

      $skuDetailsFromFactToDepot = array();
      $totalVolFromFactToDepot = 0;
      foreach ($skuwise_records as $skuwtrecord) {
        $skudet = new Object();
        $skudet->skuid = isset($skuwtrecord['skuid']) ? $skuwtrecord['skuid'] : 0;
        $skudet->skucode = isset($skuwtrecord['skucode']) ? $skuwtrecord['skucode'] : '';
        $skudet->skudescription = isset($skuwtrecord['sku_description']) ? $skuwtrecord['sku_description'] : '';
        $skudet->totalWeight = isset($skuwtrecord['skuweight']) ? $skuwtrecord['skuweight'] : 0;
        $skudet->unitWeight = isset($skuwtrecord['unitweight']) ? $skuwtrecord['unitweight'] : 0;
        $skudet->unitVolume = isset($skuwtrecord['unitvolume']) ? $skuwtrecord['unitvolume'] : 0;
        $skudet->noOfUnits = ($skudet->unitWeight != 0) ? ($skudet->totalWeight / $skudet->unitWeight) : 0;
        $skudet->totalVolume = $skudet->noOfUnits * $skudet->unitVolume;
        $skudet->skuTypeId = isset($skuwtrecord['skutypeid']) ? $skuwtrecord['skutypeid'] : 0;
        $totalVolFromFactToDepot += $skudet->totalVolume;
        $skuDetailsFromFactToDepot[] = $skudet;

        // <editor-fold defaultstate="collapsed" desc="Prepare factory delivery details to show on UI page">
        /* Prepare factory delivery details */
        $objFactDelivery = new Object();
        $objFactDelivery->customerno = $_SESSION['customerno'];
        $objFactDelivery->factoryid = $factRecord->factoryid;
        $objFactDelivery->depotid = $factRecord->depotid;
        $objFactDelivery->daterequired = $factRecord->date_required;
        $objFactDelivery->factoryname = $factRecord->factoryname;
        $objFactDelivery->depotname = $factRecord->depotname;
        $objFactDelivery->skucode = $skudet->skucode;
        $objFactDelivery->skudescription = $skudet->skudescription;
        $objFactDelivery->weight = $skudet->totalWeight;
        $skuwiseResult[] = $objFactDelivery;
        // </editor-fold>
      }
      //print_r($skuwiseResult);
      // </editor-fold>
      //echo "SKU Details For Each Factory Depot END <br/>" . $this->todaysdate . "<br/>";
      //
      //echo "Get Vehicle Types For SKU Start<br/>" . $this->todaysdate . "<br/>";
      // <editor-fold defaultstate="collapsed" desc="Get Vehicle Types suitable for above SKU">
      /* Get vehicle types in descending order of their weight */
      $objVehType = new VehicleType();
      $objVehType->customerno = $_SESSION['customerno'];
      $isRefVehicle = 0;
      foreach ($skuDetailsFromFactToDepot as $skuDet) {
        //TODO: Get sku type from DB as of now hardcoded to 1 for REF
        //Check whether any of the sku type is ref
        if ($skuDet->skuTypeId == 1) {
          $objVehType->skuTypeId = $skuDet->skuTypeId;
          $isRefVehicle = 1;
          break;
        } else if ($isRefVehicle != 1) {
          $objVehType->skuTypeId = $skuDet->skuTypeId;
        }
      }
      $vehTypeList = get_vehicletypes($objVehType);
      // </editor-fold>
      //echo "Get Vehicle Types For SKU END<br/>" . $this->todaysdate . "<br/>";
      //
      //echo "Get min vol and wt vehicle start<br/>" . $this->todaysdate . "<br/>";
      // <editor-fold defaultstate="collapsed" desc="Get min volume and min weight vehicle for logic to continue and set logic flags">

      /* We need $vehTypeList to be kept as is
       * so that it can be used for further calculation */
      $tempVehTypeList = $vehTypeList;

      /* Get minimum weight and volume for vehicletypes */
      $this->multiKeySort($tempVehTypeList, array('volume'), array(false));
      $minVehicleVolume = $tempVehTypeList[0]['volume'];
      $this->multiKeySort($tempVehTypeList, array('weight'), array(false));
      $minVehicleWeight = $tempVehTypeList[0]['weight'];

      $totalWeightFromFactToDepot = $factRecord->totalweight_from_factory_to_depot;
      //Setting flags/temp values to total weight and volume
      $tempWeight = $totalWeightFromFactToDepot;
      $tempVolume = $totalVolFromFactToDepot;
      /*
        echo "Total Weight:" . $tempWeight . "<br/>";
        echo "Total Volume:" . $tempVolume . "<br/>";
       */

      // </editor-fold>
      //echo "Get min vol and wt vehicle start<br/>" . $this->todaysdate . "<br/>";
      //
      //echo "Loop For Vehicle List To Make Load Start<br/>" . $this->todaysdate . "<br/>";
      // <editor-fold defaultstate="collapsed" desc="Loop around the above vehicle type list till we reach the minimum load which doesn't fit any vehicles">
      $arrLeftOverSku = array();
      $unprocessedTransporters = array();
      $arrTransWithCurrentVehType = array();
      $tempSkuDetails = array();
      foreach ($vehTypeList as $vehType) {
        $vehTypeDetail = new Object();
        $vehTypeDetail->vehTypeId = $vehType['vehicletypeid'];
        $vehTypeDetail->vehCode = $vehType['vehiclecode'];
        $vehTypeDetail->vehDescription = $vehType['vehicledescription'];
        $vehTypeDetail->vehWeight = $vehType['weight'];
        $vehTypeDetail->vehVolume = $vehType['volume'];

        // <editor-fold defaultstate="collapsed" desc="Get Vehicle Count for the load to fit in curent vehicle type">
        //echo "Vehicle By Weight:";
        $tempVehTypeCountByWeight = $tempWeight / $vehTypeDetail->vehWeight;
        //echo "<br/>";
        //echo "Vehicle By Vol:";
        $tempVehTypeCountByVolume = $tempVolume / $vehTypeDetail->vehVolume;
        //echo "<br/>";
        $vehicleCount = 0;
        $isVehicleCountByWt = 0;
        $isVehicleCountByVol = 0;
        if ($tempVehTypeCountByWeight > $tempVehTypeCountByVolume) {
          $vehicleCount = $tempVehTypeCountByWeight;
          $isVehicleCountByWt = 1;
          $isVehicleCountByVol = 0;
        } else if ($tempVehTypeCountByVolume > $tempVehTypeCountByWeight) {
          $vehicleCount = $tempVehTypeCountByVolume;
          $isVehicleCountByWt = 0;
          $isVehicleCountByVol = 1;
        } else {
          $vehicleCount = $tempVehTypeCountByWeight;
          $isVehicleCountByWt = 1;
          $isVehicleCountByVol = 0;
        }
        $vehicleCount = floor($vehicleCount);
        /**
          echo "Vehicle Code:" . $vehTypeDetail->vehCode . "<br/>";
          echo "Vehicle Count: " . $vehicleCount = floor($vehicleCount);
          echo "<br/>";
         *
         */
        // </editor-fold>
        //
        //Proceed to logic only if there is enough load to create atleast 1 vehicle
        if ($vehicleCount >= 1) {
          // <editor-fold defaultstate="collapsed" desc="Pre-requisite and clean up activities for SKUs">
          //Clear off temp sku details before setting it to new value.
          unset($tempSkuDetails);
          /*
            If anything exists in SKU left over array,
            we need to assign the same to skuDetails and
            then we need to clear it
           */
          if (count($arrLeftOverSku) > 0) {
            $tempSkuDetails = $arrLeftOverSku;
            unset($arrLeftOverSku);
          } else {
            $tempSkuDetails = $skuDetailsFromFactToDepot;
          }
          // </editor-fold>
          //
          // <editor-fold defaultstate="collapsed" desc="Prepare SKU details to be loaded in each vehicle">

          $totalWeightPerVehicle = 0;
          $totalVolumePerVehicle = 0;
          $arrSkuDetails = array();
          foreach ($tempSkuDetails as $skuDetail) {
            $objSkuDetail = new Object();
            $objSkuDetail->SkuId = $skuDetail->skuid;
            $objSkuDetail->Skucode = $skuDetail->skucode;
            $objSkuDetail->Skudescription = $skuDetail->skudescription;
            $objSkuDetail->SkuUnits = floor($skuDetail->noOfUnits);
            $objSkuDetail->SkuUnitsPerVehicle = floor($skuDetail->noOfUnits / $vehicleCount);
            $objSkuDetail->SkuWeightPerVehicle = $objSkuDetail->SkuUnitsPerVehicle * $skuDetail->unitWeight;
            $objSkuDetail->SkuVolumePerVehicle = $objSkuDetail->SkuUnitsPerVehicle * $skuDetail->unitVolume;
            $totalWeightPerVehicle += $objSkuDetail->SkuWeightPerVehicle;
            $totalVolumePerVehicle += $objSkuDetail->SkuVolumePerVehicle;

            if ($isVehicleCountByWt == 1) {
              if ($totalWeightPerVehicle > $vehTypeDetail->vehWeight) {
                $remainingWeight = $totalWeightPerVehicle - $vehTypeDetail->vehWeight;
                //Remove the exceeded weight added and the volume added
                $totalWeightPerVehicle -= $objSkuDetail->SkuWeightPerVehicle;
                $totalVolumePerVehicle -= $objSkuDetail->SkuVolumePerVehicle;
                $objSkuDetail->SkuWeightPerVehicle = $objSkuDetail->SkuWeightPerVehicle - $remainingWeight;
                $objSkuDetail->SkuUnitsPerVehicle = floor($objSkuDetail->SkuWeightPerVehicle / $skuDetail->unitWeight);
                $objSkuDetail->SkuVolumePerVehicle = $objSkuDetail->SkuUnitsPerVehicle * $skuDetail->unitVolume;
                //Add the recalculated weight and volume actually added
                $totalWeightPerVehicle += $objSkuDetail->SkuWeightPerVehicle;
                $totalVolumePerVehicle += $objSkuDetail->SkuVolumePerVehicle;
              }
            } else if ($isVehicleCountByVol == 1) {
              if ($totalVolumePerVehicle > $vehTypeDetail->vehVolume) {
                $remainingVolume = $totalVolumePerVehicle - $vehTypeDetail->vehVolume;
                //Remove the exceeded volume added and the weight added
                $totalVolumePerVehicle -= $objSkuDetail->SkuVolumePerVehicle;
                $totalWeightPerVehicle -= $objSkuDetail->SkuWeightPerVehicle;
                $objSkuDetail->SkuVolumePerVehicle = $objSkuDetail->SkuVolumePerVehicle - $remainingVolume;
                $objSkuDetail->SkuUnitsPerVehicle = floor($objSkuDetail->SkuVolumePerVehicle / $skuDetail->unitVolume);
                $objSkuDetail->SkuWeightPerVehicle = $objSkuDetail->SkuUnitsPerVehicle * $skuDetail->unitWeight;
                //Add the recalculated weight and volume actually added
                $totalVolumePerVehicle += $objSkuDetail->SkuVolumePerVehicle;
                $totalWeightPerVehicle += $objSkuDetail->SkuWeightPerVehicle;
              }
            }
            $objSkuDetail->SkuTotalUnits = $skuDetail->noOfUnits;
            $arrSkuDetails[] = $objSkuDetail;

            $objLeftSkuDetail = new Object();
            $objLeftSkuDetail->skuid = $objSkuDetail->SkuId;
            $objLeftSkuDetail->skucode = $objSkuDetail->Skucode;
            $objLeftSkuDetail->skudescription = $objSkuDetail->Skudescription;
            $objLeftSkuDetail->unitWeight = $skuDetail->unitWeight;
            $objLeftSkuDetail->unitVolume = $skuDetail->unitVolume;
            $objLeftSkuDetail->noOfUnits = floor($objSkuDetail->SkuTotalUnits - ($vehicleCount * $objSkuDetail->SkuUnitsPerVehicle));
            $objLeftSkuDetail->totalWeight = $objLeftSkuDetail->noOfUnits * $skuDetail->unitWeight;
            $objLeftSkuDetail->totalVolume = $objLeftSkuDetail->noOfUnits * $skuDetail->unitVolume;
            $objLeftSkuDetail->skuTypeId = $skuDetail->skuTypeId;
            $arrLeftOverSku[] = $objLeftSkuDetail;
          }
          // </editor-fold>
          //
          // <editor-fold defaultstate="collapsed" desc="Assign the loaded vehicles to transporters">
          //
          // <editor-fold defaultstate="collapsed" desc="Pre-requisite and clean up activities for transporters">
          unset($arrTransWithCurrentVehType);
          $transporterCountInFactToZone = count($factRecord->transporters);
          $unprocessedTransporterCount = count($unprocessedTransporters);
          $vehtypeid = $vehTypeDetail->vehTypeId;
          /*
            If there are any unprocessed transporters from previous loop,
            we need to consider them and give the remaining load to them.
           */
          if ($unprocessedTransporterCount > 0) {
            //Arrange the unprocessed transporters in descending order of deviation
            usort($unprocessedTransporters, function($a, $b) {
              if ($a->deviation == $b->deviation) {
                return ($a->deviationPercent > $b->deviationPercent) ? -1 : 1;
              }
              return ($a->deviation > $b->deviation) ? -1 : 1;
            });
            //Find transporters having the current vehicle type
            $arrTransWithCurrentVehType = array_filter($unprocessedTransporters, function($transporterElement) use($vehtypeid) {
              return array_filter($transporterElement->vehtypes, function($vehType) use($vehtypeid) {
                return $vehType->vehicletypeid == $vehtypeid;
              });
            });
          }

          /*
            In first loop, by default the $arrTransWithCurrentVehType would not be set
            as all transporters are yet to be processed.
            Second loop onwards, if there are no unprocessed transporters with the current vehicle type
            but other transporter has this type of vehicle, then we would give the loaded vehicle
            to any other transporters who has this vehicle type
           */
          if (!isset($arrTransWithCurrentVehType)) {
            //Arrange the unprocessed transporters in descending order of devaition
            usort($factRecord->transporters, function($a, $b) {
              if ($a->deviation == $b->deviation) {
                return ($a->deviationPercent > $b->deviationPercent) ? -1 : 1;
              }
              return ($a->deviation > $b->deviation) ? -1 : 1;
            });
            //Find transporters having the current vehicle type
            $arrTransWithCurrentVehType = array_filter($factRecord->transporters, function($transporterElement) use($vehtypeid) {
              return array_filter($transporterElement->vehtypes, function($vehType) use($vehtypeid) {
                return $vehType->vehicletypeid == $vehtypeid;
              });
            });
          }

          $transporterCount = count($arrTransWithCurrentVehType);

          /*
            Find remaining transporters who doesn't have current vehicle type
            and needs to be processed in next cycle.
           */
          if ($unprocessedTransporterCount > 0) {
            $unprocessedTransporters = array_udiff($unprocessedTransporters, $arrTransWithCurrentVehType, function ($obj_a, $obj_b) {
              return $obj_a->transporterid - $obj_b->transporterid;
            });
          } else {
            $unprocessedTransporters = array_udiff($factRecord->transporters, $arrTransWithCurrentVehType, function ($obj_a, $obj_b) {
              return $obj_a->transporterid - $obj_b->transporterid;
            });
          }
          // </editor-fold>
          //
          // <editor-fold defaultstate="collapsed" desc="Divide the vehicles as per share and create indent for particular transporter">

          /* Divide the vehicles to transporters in that zone as per share */
          $tempVehCountPerTransporter = 0;
          /*
            If we don't find any transporter with the current vehicle type,
            we need to re-add the already prepared SKU load for next iteration.
           */
          if (count($arrTransWithCurrentVehType) == 0) {
            $arrLeftOverSku = $tempSkuDetails;
          } else if (!empty($arrTransWithCurrentVehType) && count($arrTransWithCurrentVehType) > 0) {
            //By default, we know that atleast 1 indent would be created if $arrTransWithCurrentVehType is set
            $shouldWeCreateIndent = 1;
            foreach ($arrTransWithCurrentVehType as $transporter) {
              $vehicleCountPerTransporter = 0;
              $objProposedIndentDetail->vehicletypeid = $vehTypeDetail->vehTypeId;
              $objProposedIndentDetail->transporterid = $transporter->transporterid;
              $objProposedIndentDetail->transportername = $transporter->transportername;
              if ($shouldWeCreateIndent == 1) {
                //
                // <editor-fold defaultstate="collapsed" desc="Calculating vehicles to be assigned to the current transporter">
                /*
                  If transporter count is greater than 1, divide the vehicles between them as per percent
                  Also if transporter count = 1 but if there are remaining transporters,
                  then remaining load would be divided amongst remaining transporters with other vehicle type
                  Else
                  assign all the vehicles to single transporter
                 */
                if ($transporterCount > 1 || unprocessedTransporterCount > 1 || ($transporterCount == 1 && $unprocessedTransporterCount > 0)) {
                  /* Round up the vehicle count */
                  $vehicleCountPerTransporter = ceil($vehicleCount * $transporter->deviationPercent / 100);

                  /* Add the vehicles to be alloted to temp counter */
                  $tempVehCountPerTransporter += $vehicleCountPerTransporter;

                  /* Calculate difference between actual and temp vehicle count */
                  $diff = $vehicleCount - $tempVehCountPerTransporter;

                  /* If difference is negative, we have to add that negative
                   * diff to get the actual available vehicle count */
                  if ($diff < 0) {
                    $vehicleCountPerTransporter = $vehicleCountPerTransporter + $diff;
                    $tempVehCountPerTransporter = $tempVehCountPerTransporter + $diff;
                  }
                  /*
                    Assign the difference if any to the last transporter
                   */
                  if ($diff > 0 && $transporter === end($arrTransWithCurrentVehType)) {
                    $vehicleCountPerTransporter = $vehicleCountPerTransporter + $diff;
                  }
                } else {
                  $vehicleCountPerTransporter = $vehicleCount;
                  $tempVehCountPerTransporter += $vehicleCountPerTransporter;
                }
                // </editor-fold>
                //
                // <editor-fold defaultstate="collapsed" desc="Proposed Indent Creation">

                $objProposedIndentDetail->factoryid = $factRecord->factoryid;
                $objProposedIndentDetail->factoryname = $factRecord->factoryname;
                $objProposedIndentDetail->depotid = $factRecord->depotid;
                $objProposedIndentDetail->depotname = $factRecord->depotname;
                $objProposedIndentDetail->vehcode = $vehTypeDetail->vehCode;
                $objProposedIndentDetail->vehdescription = $vehTypeDetail->vehDescription;
                $objProposedIndentDetail->total_weight = $totalWeightPerVehicle;
                $objProposedIndentDetail->total_volume = $totalVolumePerVehicle;
                $objProposedIndentDetail->skudetails = $arrSkuDetails;
                $objProposedIndentDetail->factoryrequireddate = $date;

                for ($i = 0; $i < $vehicleCountPerTransporter; $i++) {
                  $objProposedIndent = new ProposedIndent();
                  $objProposedIndent->factoryid = $objProposedIndentDetail->factoryid;
                  $objProposedIndent->depotid = $objProposedIndentDetail->depotid;
                  $objProposedIndent->total_weight = $objProposedIndentDetail->total_weight;
                  $objProposedIndent->total_volume = $objProposedIndentDetail->total_volume;
                  $objProposedIndent->date_required = $objProposedIndentDetail->factoryrequireddate;
                  $objProposedIndent->customerno = $_SESSION['customerno'];

                  $objPITMapping = new ProposedIndentTransporterMapping();
                  $objPITMapping->proposed_transporterid = $objProposedIndentDetail->transporterid;
                  $objPITMapping->proposed_vehicletypeid = $objProposedIndentDetail->vehicletypeid;
                  $objPITMapping->customerno = $_SESSION['customerno'];

                  $arrPISMapping = array();
                  foreach ($objProposedIndentDetail->skudetails as $PISMapdetail) {
                    $objSKUMapping = new ProposedIndentSkuMapping();
                    $objSKUMapping->skuid = $PISMapdetail->SkuId;
                    $objSKUMapping->no_of_units = $PISMapdetail->SkuUnitsPerVehicle;
                    $objSKUMapping->weight = $PISMapdetail->SkuWeightPerVehicle;
                    $objSKUMapping->volume = $PISMapdetail->SkuVolumePerVehicle;
                    $objSKUMapping->customerno = $_SESSION['customerno'];
                    $arrPISMapping[] = $objSKUMapping;
                  }
                  $proposedindentid = insert_proposed_indent($objProposedIndent, $objPITMapping, $arrPISMapping);

                  // <editor-fold defaultstate="collapsed" desc="COMMENTED OUT - SEND EMAIL AND SMS">
                  /*
                    if (isset($proposedindentid)) {

                    $factoryEmail = '';
                    $BCCEmail = '';
                    $email_Transporter=array();
                    $email_factory = array();
                    $transporterEmailArr = array();
                    $transporterPhoneArr = array();
                    $objTransporter = new Transporter();
                    $objTransporter->customerno = $_SESSION["customerno"];
                    $objTransporter->transporterid = $objProposedIndentDetail->transporterid;
                    $email_Transporter = get_transporter_officials($objTransporter);
                    if (isset($email_Transporter) && !empty($email_Transporter)) {
                    foreach ($email_Transporter as $emailTransporter) {
                    if (isset($emailTransporter['email']) && trim($emailTransporter['email']) != '') {
                    $transporterEmailArr[] = trim($emailTransporter['email']);
                    }
                    if (isset($emailTransporter['phone']) && trim($emailTransporter['phone']) != '') {
                    $transporterPhoneArr[] = $emailTransporter['phone'];
                    }
                    }
                    }
                    $objFactory = new Factory();
                    $objFactory->customerno = $_SESSION['customerno'];
                    $objFactory->factoryid = $objProposedIndentDetail->factoryid;
                    $email_factory = get_factory_officials($objFactory);
                    foreach ($email_factory as $emailFactory) {
                    if ($emailFactory === end($email_factory)) {
                    $factoryEmail .= $emailFactory['email'];
                    } else {
                    $factoryEmail .= $emailFactory['email'] . ",";
                    }
                    }
                    $factoryEmail .= ',' . constants::adminemail;
                    // print_r($factoryEmailArr);
                    $BCCEmail = constants::bccemail;
                    //print_r($BCCEmailArr);
                    $subject = "Vehicle Requirement for Mondelez - #" . $proposedindentid . "";
                    $message = '';
                    $message .='Hi<br/><br/>';
                    $message .='Please follow Vehicle requirement schedule as below <br/><br/>';
                    $message .='<table border="1">';
                    $message .='<tr>';
                    $message .='<th>ID</th>';
                    $message .='<th>Vehicle Requirement Date</th>';
                    $message .='<th>Factory</th>';
                    $message .='<th>Depot</th>';
                    $message .='<th>Transporter</th>';
                    $message .='<th>Proposed Vehicle</th>';
                    $message .='</tr>';
                    $message .='<tr>';
                    $message .='<td>' . $proposedindentid . '</td>';
                    $message .='<td>' . $objProposedIndentDetail->factoryrequireddate . '</td>';
                    $message .='<td>' . $objProposedIndentDetail->factoryname . '</td>';
                    $message .='<td>' . $objProposedIndentDetail->depotname . '</td>';
                    $message .='<td>' . $objProposedIndentDetail->transportername . '</td>';
                    $message .='<td>' . $objProposedIndentDetail->vehcode . ' - ' . $objProposedIndentDetail->vehdescription . '</td>';
                    $message .='</tr>';
                    $message .='</table><br/><br/>';
                    $message .= constants::Portallink;
                    $message .= constants::Thanks;
                    $message .= constants::CompanyName;
                    $message .= constants::CompanyImage;
                    $message .='';
                    $msg = "Hi" . " \r\n" . "Please follow Vehicle requirement schedule as below" . " \r\n" . $objProposedIndentDetail->factoryname . " To " . $objProposedIndentDetail->depotname . " " . $objProposedIndentDetail->vehcode . "-" . $objProposedIndentDetail->vehdescription . " On " . date("d-m-Y", strtotime($objProposedIndentDetail->factoryrequireddate)) . "" . "\r\n" . constants::CompanyName;

                    if (!empty($transporterEmailArr)) {
                    $attachmentFilePath = '';
                    $attachmentFileName = '';
                    //sendMail($transporterEmailArr, $factoryEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName);
                    }
                    if (!empty($transporterPhoneArr)) {
                    $smscount = getSMSCount($_SESSION['customerno']);
                    if ($smscount > 0) {
                    $isSMSSent = 0;//sendSMS($transporterPhoneArr, $msg, $response);
                    if ($isSMSSent) {
                    updateSMSCount($smscount, $msg, $_SESSION['customerno']);
                    $todaysdate = date("Y-m-d H:i:s");
                    foreach ($transporterPhoneArr as $phone) {
                    $smsLogId = insertSMSLog($phone, $msg, $response, $proposedindentid, $isSMSSent, $_SESSION['customerno'], $todaysdate);
                    }
                    }
                    }
                    }

                    }
                   *
                   */
                  // </editor-fold>
                }
                // </editor-fold>
                //
                            // <editor-fold defaultstate="collapsed" desc="Update min volume and min weight vehicle till which the logic should be continued">
                foreach ($arrSkuDetails as $objSkuDet) {
                  $tempWeight = $tempWeight - ($vehicleCountPerTransporter * $objSkuDet->SkuWeightPerVehicle);
                  $tempVolume = $tempVolume - ($vehicleCountPerTransporter * $objSkuDet->SkuVolumePerVehicle);
                }
                // </editor-fold>
                //
                            if ($vehicleCount == $tempVehCountPerTransporter) {
                  $shouldWeCreateIndent = 0;
                }
              }
              // <editor-fold defaultstate="collapsed" desc="Updating the actual share">
              $objTransActualShare = new TransporterActualShare();
              $objTransActualShare->transporterid = $transporter->transporterid;
              $objTransActualShare->factoryid = $factRecord->factoryid;
              $objTransActualShare->zoneid = $factRecord->zoneid;
              $objTransActualShare->shared_weight = $totalWeightPerVehicle * $vehicleCountPerTransporter;
              $objTransActualShare->total_weight = $totalWeightPerVehicle * $vehicleCount;
              update_transporteractualshare($objTransActualShare);
              // </editor-fold>
            }
            // <editor-fold defaultstate="collapsed" desc="Updating the actual share for remaining transporters">
            /*
             * Update the actual share as 0 as they didn't receive any tonnage in this cycle
             * and update total share as total weight from factory to depot for all other
             * unprocessed transporters for this delivery.
             */
            foreach ($unprocessedTransporters as $unprocTransporter) {
              $objUnProcTransActualShare = new TransporterActualShare();
              $objUnProcTransActualShare->transporterid = $unprocTransporter->transporterid;
              $objUnProcTransActualShare->factoryid = $factRecord->factoryid;
              $objUnProcTransActualShare->zoneid = $factRecord->zoneid;
              $objUnProcTransActualShare->shared_weight = 0;
              $objUnProcTransActualShare->total_weight = $totalWeightPerVehicle * $vehicleCount;
              update_transporteractualshare($objUnProcTransActualShare);
            }
          }
          // </editor-fold>
          //echo "SKU detail per vehicle: <br/>";
          //print_r($arrSkuDetails);
          //echo "Remaining Weight After:" . $tempWeight . "<br/>";
          //echo "Remaining Volume After:" . $tempVolume . "<br/>";
          // </editor-fold>
          // </editor-fold>
        } else if ($tempVolume >= $minVehicleVolume || $tempWeight >= $minVehicleWeight) {
          continue;
        } else {
          // <editor-fold defaultstate="collapsed" desc="Left Over SKUs">
          //echo "Leftover Weight:" . $tempWeight . "<br/>";
          //echo "Leftover Volume:" . $tempVolume . "<br/>";
          if (empty($arrLeftOverSku)) {
            foreach ($skuDetailsFromFactToDepot as $skuDetail) {
              $objLeftSkuDetail = new Object();
              $objLeftSkuDetail->skuid = $skuDetail->skuid;
              $objLeftSkuDetail->skucode = $skuDetail->skucode;
              $objLeftSkuDetail->skudescription = $skuDetail->skudescription;
              $objLeftSkuDetail->unitWeight = $skuDetail->unitWeight;
              $objLeftSkuDetail->unitVolume = $skuDetail->unitVolume;
              $objLeftSkuDetail->noOfUnits = floor($skuDetail->noOfUnits);
              $objLeftSkuDetail->totalWeight = $skuDetail->noOfUnits * $skuDetail->unitWeight;
              $objLeftSkuDetail->totalVolume = $skuDetail->noOfUnits * $skuDetail->unitVolume;
              $objLeftSkuDetail->skuTypeId = $skuDetail->skuTypeId;
              $arrLeftOverSku[] = $objLeftSkuDetail;
            }
          }
          $leftover = new Object();
          $leftover->factoryid = $factRecord->factoryid;
          $leftover->factoryname = $factRecord->factoryname;
          $leftover->depotid = $factRecord->depotid;
          $leftover->depotname = $factRecord->depotname;
          $leftover->weight = $tempWeight;
          $leftover->volume = $tempVolume;
          $leftover->date = $date;
          $leftover->leftOverSku = $arrLeftOverSku;
          insert_leftover($leftover);
          $arrLeftOver[] = $leftover;
          /* Left Over Factory And Depot For Left Over Algo Combination */
          $leftOverFactory[] = $factRecord->factoryid;
          $leftOverDepot[] = $factRecord->depotid;
          //print_r($arrLeftOver);
          //If Complete Delivery Is Left Over Make It isProcessed
          $objFactDelivery = new FactoryDeliveryDetails;
          $objFactDelivery->factoryid = $factRecord->factoryid;
          $objFactDelivery->depotid = $factRecord->depotid;
          $objFactDelivery->date_required = $date;
          $objFactDelivery->isprocessed = 1;
          update_factory_delivery($objFactDelivery);

          break;
          // </editor-fold>
        }
        if ($vehType === end($vehTypeList)) {
          /*
            If the current vehicle type is the last vehicle type,
            and still there is some load left, we need to insert in left over
            If Left over is already prepared, it would break out of the loop previously
            and below code would never be executed.
           */
          if (empty($arrLeftOverSku)) {
            foreach ($skuDetailsFromFactToDepot as $skuDetail) {
              $objLeftSkuDetail = new Object();
              $objLeftSkuDetail->skuid = $skuDetail->skuid;
              $objLeftSkuDetail->skucode = $skuDetail->skucode;
              $objLeftSkuDetail->skudescription = $skuDetail->skudescription;
              $objLeftSkuDetail->unitWeight = $skuDetail->unitWeight;
              $objLeftSkuDetail->unitVolume = $skuDetail->unitVolume;
              $objLeftSkuDetail->noOfUnits = floor($skuDetail->noOfUnits);
              $objLeftSkuDetail->totalWeight = $skuDetail->noOfUnits * $skuDetail->unitWeight;
              $objLeftSkuDetail->totalVolume = $skuDetail->noOfUnits * $skuDetail->unitVolume;
              $objLeftSkuDetail->skuTypeId = $skuDetail->skuTypeId;
              $arrLeftOverSku[] = $objLeftSkuDetail;
            }
          }
          $leftover = new Object();
          $leftover->factoryid = $factRecord->factoryid;
          $leftover->factoryname = $factRecord->factoryname;
          $leftover->depotid = $factRecord->depotid;
          $leftover->depotname = $factRecord->depotname;
          $leftover->weight = $tempWeight;
          $leftover->volume = $tempVolume;
          $leftover->date = $date;
          $leftover->leftOverSku = $arrLeftOverSku;
          insert_leftover($leftover);
          $arrLeftOver[] = $leftover;
          /* Left Over Factory And Depot For Left Over Algo Combination */
          $leftOverFactory[] = $factRecord->factoryid;
          $leftOverDepot[] = $factRecord->depotid;
          //print_r($arrLeftOver);
          //If Complete Delivery Is Left Over Make It isProcessed
          $objFactDelivery = new FactoryDeliveryDetails;
          $objFactDelivery->factoryid = $factRecord->factoryid;
          $objFactDelivery->depotid = $factRecord->depotid;
          $objFactDelivery->date_required = $date;
          $objFactDelivery->isprocessed = 1;
          update_factory_delivery($objFactDelivery);
        }
      }
      // </editor-fold>
      //echo "Loop For Vehicle List To Make Load end<br/>" . $this->todaysdate . "<br/>";
    }
    //echo "Proposed Indent Calculation End <br/>" . $this->todaysdate . "<br/>";
    //print_r($arrProposedIndents);
    //print_r($arrLeftOver);
    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Get Proposed Indent details to show on this page">
    //echo "GET Proposed Indent Start <br/>" . $this->todaysdate . "<br/>";
    $factoryid = '';
    if (isset($_SESSION['factoryid']) && $_SESSION['factoryid'] != '') {
      $factoryid = $_SESSION['factoryid'];
    }
    $proposed_indents = array();
    $objProposedIndent = new ProposedIndent();
    $objProposedIndent->customerno = $_SESSION["customerno"];
    $objProposedIndent->proposedindentid = '';
    $objProposedIndent->date_required = $date;
    $objProposedIndent->factoryid = $factoryid;

    $proposedIndentArray = get_proposed_indent($objProposedIndent);
    foreach ($proposedIndentArray as $proposedIndent) {
      $proposedIndentarr['proposedindentid'] = $proposedIndent['proposedindentid'];
      $proposedIndentarr['factoryname'] = $proposedIndent['factoryname'];
      $proposedIndentarr['depotname'] = $proposedIndent['depotname'];
      $proposedIndentarr['transportername'] = $proposedIndent['transportername'];
      $proposedIndentarr['vehiclecode'] = $proposedIndent['vehiclecode'];
      $proposedIndentarr['date_required'] = $proposedIndent['date_required'];
      $proposedIndentarr['total_weight'] = $proposedIndent['total_weight'];
      $proposedIndentarr['total_volume'] = $proposedIndent['total_volume'];
      $proposedIndentarr['edit'] = "<a href='tms.php?pg=view-proposedindent-sku&eid=" . $proposedIndent['proposedindentid'] . "'><img src='../../images/edit.png'/></a>";
      //$proposedIndentarr['delete'] = "<a href='action.php?action=del-factory-production&did=" . $proposedIndent['proposedindentid'] . "' " . $alert . "><img src='../../images/delete1.png'/></a>";
      $proposedIndentarr['delete'] = '';

      $proposed_indents[] = $proposedIndentarr;
    }
    //echo "GET Proposed Indent End <br/>" . $this->todaysdate . "<br/>";
    // </editor-fold>
    //

        /* Unique Array Elements With Ascending Order For Factory And Depot */
    $arrleftOverFactory = array_unique($leftOverFactory);
    asort($arrleftOverFactory);
    $arrleftOverDepot = array_unique($leftOverDepot);
    asort($arrleftOverDepot);

    $Algoresult = array(
       'skuwiseResult' => $skuwiseResult,
       'proposed_indents' => $proposed_indents,
       'leftOverFactory' => $arrleftOverFactory,
       'leftOverDepot' => $arrleftOverDepot
    );
    return $Algoresult;
  }

  // </editor-fold>
}

?>
