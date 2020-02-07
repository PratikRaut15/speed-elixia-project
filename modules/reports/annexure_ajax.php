<?php
  include_once 'reports_common_functions.php';
$reportDate = new DateTime();
$date = $reportDate->format(speedConstants::DEFAULT_DATE);
$objRequest = new stdClass();
$objRequest->startDate  = isset($_POST['SDate'])?$_POST['SDate']:'';
$objRequest->endDate    = isset($_POST['EDate'])?$_POST['EDate']:'';
$objRequest->customerNo = isset($_POST['customerno'])?$_POST['customerno']:$_SESSION['customerno'];


$sdate = isset($_POST['SDate'])?$_POST['SDate']:'';
$edate = isset($_POST['EDate'])?$_POST['EDate']:'';
$customerno = isset($_POST['customerno']) ? $_POST['customerno'] : $_SESSION['customerno'];
$objUserManager = new UserManager();
$objCustomerManager = new CustomerManager();
$objVehicleManager = new VehicleManager($customerno);

$reportData = getAnnexureDetails($objRequest);
$customer_vehicles = vehicles_array($objVehicleManager->get_all_vehicles());
?>
<style>
    .newTable td{
        text-align: center;
    }
</style>
<table class="table newTable" style="width: 70%;">
    <thead>
        <tr>
            <th>#</th>
            <th>Vehicle No</th>
            <th>Is Active</th>
            <th>Is Temperature</th>
            <th>Is Humidity</th>
            <th>Is Digital </th>

        </tr>
    </thead>
    <tbody>
        <?php
        $datediffcheck = date_SDiff($sdate,$edate);
        if ($datediffcheck <= 30) {
          $reportData = getAnnexureDetails($objRequest);

            if ($reportData != NULL) {
              foreach($reportData as $subkey=>$subData){
                $x = 1;$vehicleno='';
                echo '<tr><th colspan="6">'.$subkey.'</th></tr>';
               foreach ($subData as $subkey => $data) {
                 $vehicleno = isset($customer_vehicles[$data['vehicleId']]['vehno'])?$customer_vehicles[$data['vehicleId']]['vehno']:'';
                 if($vehicleno!= ''){
                      echo '<tr>';
                      echo '<td>'.$x.'</td>';
                    //  echo '<td>'.$data->reportDate.'</td>';
                      echo '<td>'.$vehicleno.'</td>';
                      echo '<td>'.$data['isActive'].'</td>';
                      echo '<td>'.$data['isTemperature'].'</td>';
                      echo '<td>'.$data['isHumidity'].'</td>';
                      echo '<td>'.$data['isDigital'].'</td>';
                      echo '</tr>';
                      $x++;
                  }
               }
            }
          }  else {
                echo '<tr><td colspan="6">Data not available</td></tr>';
            }
        } else {
            echo "<script type='text/javascript'>jQuery('#error4').show();jQuery('#error4').fadeOut(3000);</script>";
        }
        ?>
    </tbody>
</table>
