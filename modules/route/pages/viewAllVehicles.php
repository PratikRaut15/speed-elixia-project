<script src='../../scripts/route.js' type='text/javascript'></script>
<?php
/*if (isset($_GET['delid']) && $_GET['delid']) {
delroute($_GET['delid']);
header("Location : route.php?id=9");
}*/
include 'panels/viewAllVehicle.php';

$routes = getroutes();
$allVehicles = getvehiclesfroroute();
// print("<pre>"); print_r($allVehicles); die;

if (isset($allVehicles)) {
    foreach ($allVehicles as $vehicle) {
        echo "<tr>";
        echo "<td>$vehicle->vehicleno</td>";
        echo "<td style='text-align:center'>";
        $addedRoutes = getAllRoutes($vehicle->vehicleid);
        // print("<pre>"); print_r($addedRoutes); die;
        $rlist = array();

        if (isset($addedRoutes)) {
            foreach ($addedRoutes as $route) {
                // echo $route; die;
                $routeid = $route->routeid;
                $rlist[] = $route->routename;
            }
            echo $answer = implode(",", $rlist);
        } else {
            $routeid = "";
            echo $answer = "";
            echo "N/A";
        }
        echo "</td>";
        echo "<td colspan='2' style='text-align:center; width:40%'>";
        $futureRoutes = getFutureRoutes($vehicle->vehicleid);

        $flist = array();

        if (isset($futureRoutes)) {
            foreach ($futureRoutes as $route) {
                // echo $route; die;
                $routeid = $route->routeid;
                $flist[] = $route->routename;
            }
            echo $answer1 = implode(", ", $flist);
        } else {
            $routeid = "";
            echo $answer1 = "";
            echo "N/A";
        }
        echo "</td>";
        //echo "<td></td>";
        ?>


    <td style="width: 20%">
        <input type='button' class='btn  btn-default' onclick='addFutureRoute(<?php echo '"' . $vehicle->vehicleno . '","' . $routeid . '","' . $vehicle->vehicleid . '"'; ?>);' value='Add Future Routes' data-toggle='modal' data-target='#futureRouteModal' >
        <br>
       <!--  <input type='button' class='btn  btn-default' onclick='futureRouteList(<?php echo '"' . $vehicle->vehicleno . '","' . $routeid . '","' . $vehicle->vehicleid . '"'; ?>);' value='View Future Routes' data-toggle='modal' data-target='#futureRouteListModal' > -->
    </td>


    <!-- <a href = 'route.php?id=4&did=$route->routeid&routename=$route->routename&routeTat=$route->routeTat'><i class='icon-pencil'></i></a> -->

       <!--  <td><a href ='route.php?id=2&delid=<?php echo $route->routeid; ?>' onclick='return confirm("Are you sure you want to delete?");'><i class='icon-trash'></i></a></td> -->
       <?php echo "</tr>";
    }
} else {
    echo
        "<tr>
         <td colspan='6'>No route Created</td>
    <tr>";
}

?>
    </tbody>
</table>

<?php include_once 'panels/futureRouteModal.php';?>
