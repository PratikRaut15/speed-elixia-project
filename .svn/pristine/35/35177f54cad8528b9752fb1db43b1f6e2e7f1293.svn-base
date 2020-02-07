<?php
include 'panels/viewchk.php';
$checkpoints = getenhchks();
if(isset($checkpoints))
foreach ($checkpoints as $checkpoint)
{
?>
<tr>
    <td><?php echo $checkpoint->cname;?></td>	
    <td><?php echo $checkpoint->vehicleno;?></td>
    <td><?php echo $checkpoint->comdet;?></td>    
    <td><a href='enh_checkpoint.php?id=3&enh_chkid=<?php echo $checkpoint->enh_checkpointid;?>' ><i class='icon-pencil'></i></a></td>
    <td><a href = 'route.php?delid=<?php echo($checkpoint->enh_checkpointid); ?>' onclick="return confirm('Are you sure you want to delete?');"><i class='icon-trash'></i></a></td>                                        
</tr>
<?php }
else
    echo "
    <tr>
        <td colspan='3'>No Checkpoint Added</td>
    </tr>";
?>
</tbody>
</table>

