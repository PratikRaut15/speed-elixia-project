<?php
$kind = '';
require 'panels/viewvehicles.php';
$vehicles = getvehicles($kind);
$x = 1;

if (isset($vehicles)) {
  foreach ($vehicles as $vehicle) {
    echo "<tr>";

    echo "<td>" . $x++ . "</td>";
    echo "<td>$vehicle->vehicleno</td>";
    if ($_SESSION['groupid'] != null) {
      echo "<td>$vehicle->groupname</td>";
    }
    echo "<td>$vehicle->genset1</td>";
    echo "<td>$vehicle->genset2</td>";
    echo "<td>$vehicle->transmitter1</td>";
    echo "<td>$vehicle->transmitter2</td>";
    echo "<td><a href='genset.php?id=2&vid=$vehicle->vehicleid' ><i class='icon-pencil'></i></a></td>";
    echo "</tr>";
  }
} else {
  echo "<tr><td colspan='100%'  style='text-align:center;'>No Vehicles Created</td></tr>";
}
?>
</tbody>
</table>
