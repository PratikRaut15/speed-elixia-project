<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
  #proposedindentmaster_filter{display: none}
  .dataTables_length{display: none}
</style>
<div class='container' >
  <div>
    <form id="frmIndentAlgo" method="post" action="tms.php?pg=view-indents">
      <label>Enter the date</label>
      <input type="text" name="date_required" id="SDate" value="" required maxlength="25">
      <input type="submit" value="Calculate" name="indentcal" onclick="validateIndentAlgo();" />
      <input type="hidden" name="todaysdate" id="todaysdate" value="<?php echo date('d-m-Y'); ?>" />
    </form>
  </div>

  <center>

    <?php
    //<editor-fold defaultstate="collapsed" desc="Get Combinations">
    $combiresult = array();
    $combination = array();

    function getCombinations(array $arrInput, $combinationLength) {
      global $combiresult, $combination;
      $combiresult = array();
      $combination = array();
      $lenOfInputArray = count($arrInput);
      inner(0, $combinationLength, $arrInput, $lenOfInputArray);
      return $combiresult;
    }

    function inner($start, $combiLength, $arrInput, $lenOfInputArray) {
      global $combiresult, $combination;

      if ($combiLength == 0) {
        array_push($combiresult, $combination);
      } else {
        for ($i = $start; $i <= $lenOfInputArray - $combiLength; ++$i) {
          array_push($combination, $arrInput[$i]);
          inner($i + 1, $combiLength - 1, $arrInput, $lenOfInputArray);
          array_pop($combination);
        }
      }
    }

    // </editor-fold>
    //
        if (isset($_POST['indentcal'])) {
      // <editor-fold defaultstate="collapsed" desc="Get Factory deliveries for a particular date">
      //echo "<pre>";
      //print_r($weight);
      //echo "<hr/>";
      $todaysdate = date('Y-m-d');
      $date = date('Y-m-d', strtotime($_POST['date_required']));
      if ($date < $todaysdate) {
        return;
      }
      $factoryid = '';
      if (isset($_SESSION['factoryid']) && $_SESSION['factoryid'] != '') {
        $factoryid = $_SESSION['factoryid'];
      }
      $objFactDepot = new Object();
      $objFactDepot->customerno = $_SESSION['customerno'];
      $objFactDepot->daterequired = $date;
      $objFactDepot->factoryid = $factoryid;
      //echo "Get FActory Wise SKU Weight Start<br/>" . date("Y-m-d H:i:s") . "<br/>";
      $factorydepotwise_records = get_skuweight_factorydepot($objFactDepot);
      //echo "Get FActory Wise SKU Weight End<br/>" . date("Y-m-d H:i:s") . "<br/>";
      // </editor-fold>
      //

      $objAlgo = new IndentAlgo($_SESSION['customerno'], $_SESSION['userid']);
      $result = $objAlgo->indentAlgo($factorydepotwise_records, $date);
      //$skuwiseResult = $result['skuwiseResult'];
      //$proposed_indents = $result['proposed_indents'];
      //$leftOverFactory = $result['leftOverFactory'];
      //$leftOverDepot = $result['leftOverDepot'];

      /* Left Over Details */

      $arrleftOverFactory = array();
      $arrleftOverDepot = array();
      $arrDepotCombinations = array();
      //echo "Left Over Multidepot Calculation Start" . date("Y-m-d H:i:s") . "<br/>";
      $objTMS = new TMS($_SESSION['customerno'], $_SESSION['userid']);
      $objLeftOver = new Object();
      $objLeftOver->customerno = $_SESSION["customerno"];
      $objLeftOver->factoryid = '';
      $objLeftOver->date = $date;
      $leftOverArray = get_leftover_details($objLeftOver);
      //print_r($leftOverArray);
      /* TODO - Remove below line and put in factory table */
      $multiDepotPlantList = array(1, 2, 11);
      foreach ($leftOverArray as $leftover) {
        if (in_array($leftover['factoryid'], $multiDepotPlantList)) {
          $arrleftOverFactory[] = $leftover['factoryid'];
          $arrleftOverDepot[] = $leftover['depotid'];
        }
      }
      if (isset($arrleftOverFactory) && !empty($arrleftOverFactory)) {
        $leftOverFactory = array_unique($arrleftOverFactory);
        $leftOverDepot = array_unique($arrleftOverDepot);
        asort($leftOverFactory);
        asort($leftOverDepot);
        //$leftOverDepot = array(1, 2, 3, 4);
        $arrLength = count($leftOverDepot);
        foreach ($leftOverFactory as $factory) {
          for ($count = $arrLength; $count > 1; $count--) {
            $combinationarr = getCombinations($leftOverDepot, $count);
            // $combinationstring = implode(',', $combi);
            foreach ($combinationarr as $combinationvalue) {
              $combinationstring = implode(',', $combinationvalue);
              $multidepotObj = new Object();
              $multidepotObj->factoryidparam = $factory;
              $multidepotObj->multidepotcombination = $combinationstring;
              $multidepotObj->custno = $_SESSION['customerno'];
              $multidepotObj->daterequired = $date;
              $multidepotObj->todaysdate = date('Y-m-d');
              $multidepotObj->userid = $_SESSION['userid'];
              $objTMS->getMultidepotLoad($multidepotObj);
            }
          }
        }
      }
      //echo "Left Over Multidepot Calculation End" . date("Y-m-d H:i:s") . "<br/>";

      /* Re-Write Code Get FactoryWise Records And Indent Algo */
      //echo "************* Rewrite Code For Multidepot STart ******* " . date("Y-m-d H:i:s") . "<br/>";
      $factorydepotwise_records_leftover = get_skuweight_factorydepot($objFactDepot);
      $result_leftover = $objAlgo->indentAlgo($factorydepotwise_records_leftover, $date);
      if (isset($result_leftover) && !empty($result_leftover)) {
        $skuwiseResult = $result_leftover['skuwiseResult'];
        $proposed_indents = $result_leftover['proposed_indents'];
      } else {
        $skuwiseResult = $result['skuwiseResult'];
        $proposed_indents = $result['proposed_indents'];
      }
      //echo "************* Rewrite Code For Multidepot End ******* " . date("Y-m-d H:i:s") . "<br/>";

      /* End Left Over Algo */
    }

    if (isset($skuwiseResult)) {
      ?>
      <h2>Factory Delivery Details</h2>
      <table>
        <tr>
          <td>Factory Name</td>
          <td>Depot Name</td>
          <td>Sku Name</td>
          <td>Weight(Tons)</td>
          <td>Date </td>
        </tr>
        <?php
        foreach ($skuwiseResult as $record) {
          ?>
          <tr>
            <td><?php echo $record->factoryname ?></td>
            <td><?php echo $record->depotname ?></td>
            <td><?php echo $record->skudescription ?></td>
            <td><?php echo $record->weight ?></td>
            <td><?php echo date('d-m-Y', strtotime($record->daterequired)) ?></td>
          </tr>
          <?php
        }
        ?>
      </table>
      <?php
    }
    ?>
    <br/>
    <br/>
  </center>
</div>
<?php
/**
  echo "<br/>********************Left Over Factory*********************** <br/>";
  print_r($leftOverFactory);

  echo "<br/>********************Left Over Depot*********************** <br/>";
  print_r($leftOverDepot);
 *
 */
?>
<!-- For Date Selection -->
<?php
// print_r($proposedIndentArray);
if (isset($proposed_indents)) {
  ?>
  <div class='container' >
    <center>
      <h2>Proposed Indent Details</h2>
      <input type='hidden' id='forTable' value='productionMaster'/>
      <table class='display table table-bordered table-striped table-condensed' id="proposedindentmaster" >
        <thead>
          <tr>
            <td><input type="text" class='search_init' style="width:80%;" name='delievry_id'  autocomplete="off"/></td>
            <td><input type="text" class='search_init' name='factory' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='search_init' name='depot' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='search_init' name='transporter' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='search_init' name='vehicle' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='search_init' name='skumaping' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='search_init' name='weight' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='search_init' name='volume' style="width:90%;" autocomplete="off"/></td>
            <td colspan="2"></td>
          </tr>
          <tr class='dtblTh'>
            <th width="8%">ID</th>
            <th >Factory</th>
            <th >Depot</th>
            <th >Transporter </th>
            <th >Vehicle Type </th>
            <th >Date</th>
            <th >Weight </th>
            <th >Volume</th>
            <th>View</th>
            <th>Delete</th
          </tr>
        </thead>
      </table>
    </center>
  </div>
  <?php
}
//print_r($proposed_indents);
?>
<script type='text/javascript'>
  var data = <?php echo json_encode($proposed_indents); ?>;
  var tableId = 'proposedindentmaster';
  var tableCols = [
    {"mData": "proposedindentid"}
    , {"mData": "factoryname"}
    , {"mData": "depotname"}
    , {"mData": "transportername"}
    , {"mData": "vehiclecode"}
    , {"mData": "date_required"}
    , {"mData": "total_weight"}
    , {"mData": "total_volume"}
    , {"mData": "edit"}
    , {"mData": "delete"}
  ];
</script>