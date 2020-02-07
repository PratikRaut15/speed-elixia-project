<?php
include 'panels/viewchk.php';
$checkpoints = getchks();
$x = 1;
if(isset($checkpoints))
foreach ($checkpoints as $checkpoint)
{
?>
<tr>
    <td><?php echo $x++;?></td>
    <td><?php echo $checkpoint->cname;?></td>
    <td><?php echo $checkpoint->crad;?></td>
    <td>
        <?php
        if($checkpoint->eta != '00:00:00')
        {
            echo convertDateToFormat($checkpoint->eta,speedConstants::DEFAULT_TIME);
        }
        else
        {
            echo "N/A";
        }
        ?>
    </td>
    <td><?php echo $checkpoint->phoneno;?></td>
    <td><?php echo $checkpoint->email;?></td>
        <td><a href='czone.php?id=3&chkid=<?php echo $checkpoint->checkpointid;?>' ><i class='icon-pencil'></i></a></td>
        <td><a href = 'routecz.php?delid=<?php echo($checkpoint->checkpointid); ?>' onclick="return confirm('Are you sure you want to delete?');"><i class='icon-trash'></i></a></td>
</tr>
<?php }
else
    echo "
    <tr>
        <td colspan='7'>No <?php echo ".$checkpoint_name." Created</td>
    </tr>";
?>
</tbody>
</table>

