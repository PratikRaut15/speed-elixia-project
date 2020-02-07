<?php
include 'reports_common_functions.php';
$date = date("d-m-Y");
$title = "Inactive Vehicle Report for Date " . $date;
$objCustomerManager = new CustomerManager();
$objUserManager = new UserManager();
$vehicleManager = new VehicleManager($_SESSION['customerno']);
$arrGroups = array();
?>
<br/>
<table><tr><td>
<h4><?php echo $title; ?> <a onclick="get_inactive_vehiclepdfreport(<?php echo $_SESSION['customerno']; ?>);" href="javascript:void(0)">
                    <img title="Export to PDF" class="exportIcons" alt="Export to PDF" src="../../images/pdf_icon.png">
                  </a>
                  <a href="javascript:void(0)" onclick="get_inactive_vehiclexlsreport(<?php echo $_SESSION['customerno']; ?>);return false;">
                    <img title="Export to Excel" class="exportIcons" alt="Export to Excel" src="../../images/xls.gif">
                  </a></h4>
                  </td></tr><tr>
                 <td><input id="SDate" name="SDate" type="hidden" value="<?php echo $date; ?>" required/></td>
                <td><input id="EDate" name="EDate" type="hidden" value="<?php echo $date; ?>" required/></td></tr>
                </table>
<table class="table newTable" style="width: 70%;">
    <thead>
        <tr>
        <?php if ($_SESSION["customerno"] != 64) {?>
            <th>Sr No.</th>
            <th>Vehicle No</th>
            <th>Group Name</th>
            <th>Unit No</th>
            <th>Simcard No.</th>
            <th>Last Updated</th>
            <th>Inactive Days</th>
            <th>Bucket</th>
            <th>Status</th>
            <th>Regional SAP Code</th>
            <th>Zonal SAP Code</th>
<?php } else {?>
            <th>Sr. No.</th>
            <th>Vehicle No.</th>
            <th>Group Name</th>
            <th>Unit No</th>
            <th>Simcard No.</th>
            <th>Region Name</th>
            <th>Zone Name</th>
            <th>Last Updated</th>
            <th>Inactive Days</th>
            <th>Bucket</th>
            <th>Status</th>
            <th>Branch User</th>
            <th>SAP Code</th>
            <th>Mobile</th>
            <th>Regional User</th>
            <th>SAP Code</th>
            <th>Mobile</th>
            <th>Zonal User</th>
            <th>SAP Code</th>
            <th>Mobile</th>
<?php }?>
        </tr>
        </tr>
    </thead>
    <tbody>
        <?php
$userDetails = $objUserManager->getusersforcustomer($_SESSION["customerno"]);
foreach ($userDetails as $user) {
    $userGroups = $objUserManager->get_groups_fromuser($_SESSION["customerno"], $user->userid);
    if (isset($userGroups) && !empty($userGroups)) {
        foreach ($userGroups as $group) {
            $arrGroups[] = $group->groupid;
        }
    }
}
$arrGroups = array_unique($arrGroups);
//print_r($arrGroups);
$DATA = getInactiveVehicle($arrGroups);
$lessthan_hour_ago = date("Y-m-d H:i:s", strtotime('-1 hour'));
if ($DATA != NULL) {
    $x = 1;
    foreach ($DATA as $data => $dataVal) {
        if ($_SESSION["customerno"] == 64) {
            $heirarchyDetails = $objUserManager->vehicleHeirarchy($_SESSION["customerno"], $_SESSION["userid"], $dataVal->vehicleid);
        }
        if (strtotime($dataVal->lastupdated) < strtotime($lessthan_hour_ago) && $dataVal->simcardno != '') {
            ?>
                    <tr>
                    <td><?php echo $x; ?></td>
                    <td><?php echo $dataVal->vehicleno; ?></td>
                    <td><?php echo $dataVal->groupname; ?></td>
                    <td><?php echo $dataVal->unitno; ?></td>
                    <td><?php echo $dataVal->simcardno; ?></td>
                          <?php if ($_SESSION["customerno"] == 64) {?>
                            <td><?php echo $heirarchyDetails[0]['regionname']; ?></td>
                            <td><?php echo $heirarchyDetails[0]['zonename']; ?></td>
                    <?php }?>
                    <td><?php echo date('d-m-Y h:i:s', strtotime($dataVal->lastupdated)); ?></td>
                    <td><?php echo $dataVal->inactive_days; ?></td>
                    <td><?php echo $dataVal->bucket_days; ?></td>
                    <td><?php echo $dataVal->reason; ?></td>
                    <td><?php echo $heirarchyDetails[0]['realname']; ?></td>
                    <td><?php echo $heirarchyDetails[0]['username']; ?></td>
                    <td><?php echo $heirarchyDetails[0]['phone']; ?></td>
                      <?php
if ($_SESSION["customerno"] == 64) {?>
                    <td><?php echo $heirarchyDetails[0]['regionalUserName']; ?></td>
                    <td><?php echo $heirarchyDetails[0]['regionalUserSAP']; ?></td>
                    <td><?php echo $heirarchyDetails[0]['regionalUserSAPPhone']; ?></td>
                      <td><?php echo $heirarchyDetails[0]['zonalUserName']; ?></td>
                      <td><?php echo $heirarchyDetails[0]['zonalUserSAP']; ?></td>
                    <td><?php echo $heirarchyDetails[0]['zonalUserSAPPhone']; ?></td>
                    <?php }
            ?>
                    </tr>
                  <?php $x++;
        }
    }
} else {
    echo '<tr><td colspan="8">Data not available</td></tr>';
}
?>
    </tbody>
</table>