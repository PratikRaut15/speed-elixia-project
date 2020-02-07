<?php
include 'panels/viewchktype.php';
$checkpoints = getchktypes();
$x = 1;
if (isset($checkpoints))
    foreach ($checkpoints as $checkpoint) {
        $customerno = $checkpoint->customerno;
        if ($customerno != $_SESSION['customerno']) {
            $show = 'style="display:none;"';
        } else {
            $show = '';
        }
        ?>
        <tr>
            <td><?php echo $x++; ?></td>
            <td><?php echo $checkpoint->name; ?></td>

            <td><a <?php echo $show; ?> href='checkpointtype.php?id=3&ctid=<?php echo $checkpoint->ctid; ?>' ><i class='icon-pencil'></i></a></td>
            <td><a <?php echo $show; ?> href = 'route.php?delctid=<?php echo($checkpoint->ctid); ?>' onclick="return confirm('Are you sure you want to delete?');"><i class='icon-trash'></i></a></td>
        </tr>
        <?php
    } else
    echo "
    <tr>
        <td colspan='7'>No <?php echo " . $chkpt_type . " Created</td>
    </tr>";
?>
</tbody>
</table>

