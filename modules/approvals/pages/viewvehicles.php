<?php
 /*   require 'panels/viewvehicles.php';
    $x = 1;
    $vehicles = getvehicles_for_approval();    
    if(isset($vehicles))
    {	
        foreach($vehicles as $vehicle)
        {
            echo "<tr>";
            echo "<td>".$x++."</td>";
            echo "<td>$vehicle->vehicleno</td>";
            echo "<td>$vehicle->groupname</td>";            
            echo "<td>$vehicle->status_name</td>";
            echo "<td>$vehicle->approver</td>";                        
            echo "<td>$vehicle->sender</td>";                        
            echo "<td>$vehicle->timestamp</td>";            
            if($_SESSION["roleid"] == "1" || $_SESSION["roleid"] == "5")
            {
                echo "<td><a   href='approvals.php?id=3&vid=$vehicle->vehicleid' ><i class='icon-pencil'></i></a></td>";
            }
            echo "</tr>";
        }
    }
    else
        echo 
        "<tr>
            <td colspan=100%>No Pending Approvals</td>
        </tr>";
  * 
  */
?>
<!--    </tbody>
</table>-->
<?php
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/datatables/jquery.dataTables_new.css" type="text/css" />';
echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
?>
<?php
//require 'panels/viewvehicles.php';
$x = 1;
$vehicles = getvehicles_for_approval();
//print_r($vehicles);
$data = array();
if (isset($vehicles)) {
    foreach ($vehicles as $vehicle) {

        if ($_SESSION["roleid"] == "1" || $_SESSION["roleid"] == "5" || $_SESSION["roleid"] == "18" || $_SESSION["roleid"] == "19" || $_SESSION["roleid"] == "33") {
            $edit = "<td><a   href='approvals.php?id=3&vid=$vehicle->vehicleid' ><i class='icon-pencil'></i></a></td>";
        } else {
            $edit = "";
        }

        $data[] = array(
            "srno" => $x,
            "vehicleno" => $vehicle->vehicleno,
            "groupname" => $vehicle->groupname,
            "status_name" => $vehicle->status_name,
            "approver" => $vehicle->approver,
            "sender" => $vehicle->sender,
            "timestamp" => $vehicle->timestamp,
            'edit' => $edit
        );
        $x++;
    }
}
//print_r($data);
if (empty($data)) {
    $data = "";
} else {
    $data = json_encode($data);
}


?>

<div class="container">
    <?php if(empty($data)){ ?>
    <span class="error" style="font-size: 11px; color:red; width:250px;">No Records.</span>
    <?php } ?>
    <table class='display table table-bordered table-striped table-condensed' id="viewvehicles" style="width:90%">
        <thead>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <?php if ($_SESSION["roleid"] == "1" || $_SESSION["roleid"] == "5" || $_SESSION["roleid"] == "18" || $_SESSION["roleid"] == "19" || $_SESSION["roleid"] == "33") {
                    ?>
                    <td></td>
                <?php } ?>
            </tr>

            <tr class='dtblTh'>
                <th>Sr. No</th>     
                <th>Vehicle No</th>
                <th><?php echo($_SESSION['group']); ?></th>        
                <th>Status</th>
                <th>Approver</th>        
                <th>Sender</th>               
                <th>Date of Submission</th>
                <?php
                if ($_SESSION["roleid"] == "1" || $_SESSION["roleid"] == "5" || $_SESSION["roleid"] == "18" || $_SESSION["roleid"] == "19" || $_SESSION["roleid"] == "33") {
                    ?>             
                    <th>Approve / Reject</th>
                <?php } ?>
            </tr>

        </thead>     
    </table> 
</div>


<script type="text/javascript">
    $(document).ready(function () {
        var sortColumn = 0;
        
        var data = <?php echo $data;?>;


        $('#viewvehicles').dataTable({
            "processing": true,
            "order": [sortColumn, "asc"],
            "emptyTable": "No data available in table",
            "iDisplayLength": 20,
            "aaData": <?php echo $data; ?>,
            "aoColumns": [
                {"mData": "srno"},
                {"mData": "vehicleno"},
                {"mData": "groupname"},
                {"mData": "status_name"},
                {"mData": "approver"},
                {"mData": "sender"},
                {"mData": "timestamp"},
                {"mData": "edit"}
            ]

        });
    });
</script>



