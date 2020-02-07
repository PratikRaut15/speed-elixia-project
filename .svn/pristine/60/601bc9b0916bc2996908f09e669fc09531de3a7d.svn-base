<?php 
include 'panels/device.php';
$devicedata = getdevice();;
if(!isset($devicedata))
    echo "<tr>
            <td colspan=12>No Data Available</td>
        </tr>";
else
{
    foreach($devicedata as $device)
    {
        $temp = 'Not Active';
        $s = "analog".$device->tempsen1;                    
        if($device->tempsen1 != 0 && $device->$s != 0)
        {
            $temp = gettemp($device->$s);
        }
        else
            $temp = '-';

        $s = "analog".$device->tempsen2;        
        if($device->tempsen2 != 0 && $device->$s != 0)
        {
            $temp = gettemp($device->$s);
        }
        else
            $temp = '-';

        echo "<tr>";
        $date = new DateTime($device->lastupdated);
?>
    <td><?php echo $date->format('d-M-Y H:i');?></td>
    <?php
    if($device->gsmstrength<32)
    {
        echo "<td>";
        echo (int)($device->gsmstrength/31*100);
        echo "</td>";
    }
    else
        echo "<td class=notok>0</td>";
    ?>
    </td>
    <td><?php echo $device->inbatt/1000;?></td>
    <?php
    if ($device->tamper==1)
        echo "<td class=notok>Yes</td>";
    else
        echo "<td>No</td>";
    ?>
    <?php
    if ($device->powercut==0)
        echo "<td class=notok>Yes</td>";
    else
        echo "<td>No</td>";
    ?>
    <td><?php echo "Not Active";#echo $device->analog1;?></td>
    <td><?php 
        if($temp!='-')
            echo "$temp <sup>0</sup>C";
        else
            echo "$temp";
        ?>
    </td>    
    <td><?php 
            if($device->acsensor == 1)
            {    
                if($device->digitalio == 0) echo("ON"); 
                else echo("OFF"); 
            }
            else
            {
                echo("Not Active");
            }
        ?>
    </td>
<?php 
echo "</tr>";
    }
}
?>
</tbody>
</table>
</div>
