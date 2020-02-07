<?php include 'panels/sim.php';
$devicedata = getsim();
if(!isset($devicedata))
    echo "<tr><td colspan=7>No Data Available</td></tr>";
else
{
    foreach($devicedata as $device)
    {
        echo "<tr>";
        $date = new DateTime($device->lastupdated);
        echo "<td>".$date->format('d-M-Y H:i')."</td>";
?>
    <td><?php if($device->gsmstrength<32){ echo (int)($device->gsmstrength/31*100);} else {echo '0';} ?></td>
    <?php if($_SESSION['Session_UserRole']=='elixir')
        {
            if ($device->gpsfixed=='A')
                echo "<td>Valid</td>";
            else if ($device->gpsfixed=='V')
                echo "<td class='notok'>Invalid</td>";
            if ($device->gsmregister==0)
                echo "<td class='notok'>Not Registered</td>";
            else if ($device->gsmregister==1)
                echo "<td>Registered Home Network</td>";
            else if ($device->gsmregister==2)
                echo "<td class='notok'>Searching New Network</td>";
            else if ($device->gsmregister==3)
                echo "<td class='notok'>Registration Denied</td>";
            else if ($device->gsmregister==4)
                echo "<td class='notok'>Unknown</td>";
            else if ($device->gsmregister==5)
                echo "<td class='notok'>Roaming</td>";
            if ($device->gprsregister==1)
                echo "<td>OK</td>";
            else if ($device->gprsregister==0)
                echo "<td class='notok'>Not OK</td>";
            else echo "<td>$device->gprsregister</td>";
        }
    echo "</tr>";
    }
}
?>

</tbody>
</table>
    </div>