<?php
require 'panels/viewzone.php';
$geozones = getzone();
if(!isset($geozones))
    echo "
    <tr>
        <td colspan='100%'>No Fence Created</td>
    </tr>";
else
{
    $counter=1;
    foreach ($geozones as $geozone)
    {
?>
<tr>
    <td><?php echo $counter++;?></td>
    <td><?php echo $geozone->zonename;?></td>
    <td>
        <a href='zone.php?id=3&zid=<?php echo $geozone->zoneid;?>'><i class='icon-pencil'></i></a>
    </td>
    <td><a href = 'route.php?delid=<?php echo($geozone->zoneid); ?>' onclick="return confirm('Are you sure you want to delete?');"><i class='icon-trash'></i></a></td>                                        
</tr>
<?php }
}?>
</tbody>
</table>