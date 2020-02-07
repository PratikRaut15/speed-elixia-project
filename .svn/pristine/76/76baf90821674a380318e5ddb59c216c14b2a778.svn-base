<?php
require 'panels/viewfence.php';
$geofences = getfences();
if(!isset($geofences))
    echo "
    <tr>
        <td colspan='100%'>No Fence Created</td>
    </tr>";
else
{
    $counter=1;
    foreach ($geofences as $geofence)
    {
?>
<tr>
    <td><?php echo $counter++;?></td>
    <td><?php echo $geofence->fencename;?></td>
    <td>
        <a href='fencing.php?id=3&fid=<?php echo $geofence->fenceid;?>'><i class='icon-pencil'></i></a>
    </td>
    <!-- <td><a href = 'route.php?delid=<?php echo($geofence->fenceid); ?>' onclick="return confirm('Are you sure you want to delete?');"><i class='icon-trash'></i></a></td>   -->
    <td><a href = '#' onclick="deleteFence(<?php  echo $geofence->fenceid;?>)"><i class='icon-trash'></i></a></td>                                        
</tr>
<?php }
}?>
</tbody>
</table>

<script>
function deleteFence(fenceId)
{
	jQuery.ajax({
		type:'POST',
		url:'modifyFence_ajax.php',
		data:{action:'deleteFence',fenceId:fenceId},
		success:function(response){
            if(response=='OK')
            {
                document.location.reload();
            }
            else
            {
                alert("Something went wrong. Please contact to administrator");
            }
		}
	});    
}
</script>