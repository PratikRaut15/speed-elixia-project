<?php
    if (isset($_GET['delid']) && $_GET['delid']) {
        delroute($_GET['delid']);
        header("Location : route.php?id=2");
    }
    include 'panels/viewroutes.php';
    $routes = getroutes();
    if (isset($routes)) {
        foreach ($routes as $route) {
            echo "<tr>";
            echo "<td>$route->routename</td>";
            echo "<td>";
            //<td colspan="100%"><div id="vehicle_list_route">
            $addedvehicles = getaddedvehicles($route->routeid);
            $vlist = array();

            if (isset($addedvehicles)) {
                foreach ($addedvehicles as $vehicle) {
                    $vlist[] = $vehicle->vehicleno;
                }
                echo $answer = implode(",", $vlist);
            } else {
                echo "No Vehicles Assigned";
            }
            echo "<td>$route->routeTat</td>";
        ?>
<?php
    echo "<td><a href = 'route.php?id=4&did=$route->routeid&routename=$route->routename&routeTat=$route->routeTat'><i class='icon-pencil'></i></a></td>";
        ?>
        <td><a href ='route.php?id=2&delid=<?php echo $route->routeid; ?>' onclick='return confirm("Are you sure you want to delete?");'><i class='icon-trash'></i></a></td>
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
