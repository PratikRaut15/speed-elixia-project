<?php
    $drivers = getdrivers_allocated();
    include 'panels/viewdrivers.php';
    if(isset($drivers))
    foreach($drivers as $driver)
    {
        echo "<tr>";
       
        echo "<td>$driver->drivername</td>";
        echo "<td>$driver->driverlicno</td>";
        echo "<td>$driver->driverphone</td>";
        echo "<td>$driver->vehicleno</td>";
        
				
		 echo "<td><a  href = 'driver.php?id=4&did=$driver->driverid'>
                <i class='icon-pencil'></i> </a></td>";
                 ?>
<!--   Delete Vehicle
            <td>
                <a href = 'route.php?deldid=<?php echo($driver->driverid); ?>' onclick="return confirm('Are you sure you want to delete?');"><i class='icon-trash'></i></a>
            </td>                
-->
                 <?php
        echo "</tr>";
    }
 else
     echo 
     "<tr>
         <td colspan='6'>No Driver Created</td>
    <tr>";
?>
    </tbody>
</table>
