<?php 
include 'panels/additional.php';
$devicedata = getmisc();
if(!isset($devicedata))
    echo "<tr>
            <td colspan=7>No Data Available</td>
        </tr>";
else
{
    foreach($devicedata as $device)
    {
        echo "<tr>";
        $date = new DateTime($device->lastupdated);
?>
    <td><?php echo $date->format('d-M-Y H:i');?></td>
    <td>
        <?php 
        if ($_SESSION['Session_UserRole']=='elixir')
        {
            include '../common/elixirreasons.php';
        }
        else
        {
            include '../common/userreasons.php';
        }
        ?>
    </td>
    <?php
        if($_SESSION['Session_UserRole']=='elixir')
        {
            if($device->online_offline=='0') 
                echo "<td>Online</td>";
            else 
                echo "<td class='notok'>Offline</td>";
            echo '</td>';
        }
    ?>
    <?php
        if($_SESSION['Session_UserRole']=='elixir')
        {
            echo "<td>$device->analog3</td>";
            echo "<td>$device->analog4</td>";
        }
    ?>
    <?php if ($_SESSION['Session_UserRole']=='elixir') {?>
    <td><?php echo $device->commandkey;?></td>
    <td><?php echo $device->commandkeyval;?></td>
    <?php }?>
<?php echo "</tr>";
    }
}
?>
</tbody>
</table>
</div>