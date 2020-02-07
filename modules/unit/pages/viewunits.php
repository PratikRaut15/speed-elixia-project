<?php
include 'panels/viewunits.php';
$units = getunits();
if(isset($units))
    foreach ($units as $unit)
    {
        echo "<tr>";
       
        echo "<td>$unit->unitno</td>";
        // echo "<td>".substr($unit->phone, 0, 3).'*******'."</td>";
		 echo "<td>
               <a href = 'unit.php?id=3&uid=$unit->uid'>
                 <i class='icon-book'></i> 
               </a>
           </td>";
        echo '</tr>';
    }
else
     echo "<tr><td colspan='100%'>Unable to fetch Units</td><tr>";
?>
</tbody>
</table>
