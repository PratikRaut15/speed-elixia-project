<?php
include 'panels/viewloc.php';
$locations = getlocs();
if(isset($locations))
foreach ($locations as $location)
{
?>
<tr>
    <td><?php echo $location->location;?></td>	
    <td><?php echo $location->city;?></td>
    <td><?php echo $location->state;?></td>    
        <td><a href='location.php?id=3&geotestid=<?php echo $location->geotestid;?>' ><i class='icon-pencil'></i></a></td>
            <td><a href = 'route.php?delid=<?php echo($location->geotestid); ?>' onclick="return confirm('Are you sure you want to delete?');"><i class='icon-trash'></i></a></td>                        
</tr>
<?php }
else
    echo "
    <tr>
        <td colspan='7'>No Location Added</td>
    </tr>";
?>
</tbody>
</table>

