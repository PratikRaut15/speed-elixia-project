<?php
if(isset($_GET['delid']) && $_GET['delid'])
{
    delsmstrack($_GET['delid']);
}
    include 'panels/viewsms.php';
    $smstracks = getsmstrack();
    if(isset($smstracks))
    foreach($smstracks as $smstrack)
    {
        echo "<tr>";
        echo "<td>$smstrack->name</td>";
        echo "<td>$smstrack->phoneno</td>";
        echo "<td><a href = 'smstracking.php?id=4&did=$smstrack->id&name=$smstrack->name'><i class='icon-pencil'></i></a></td>";
        echo "<td><a href = 'smstracking.php?id=2&delid=$smstrack->id'><i class='icon-trash'></i></a></td>";
        echo "</tr>";
    }
 else{
     echo 
     "<tr>
         <td colspan='6'>No Sms Tracking Created</td>
    <tr>";
     }
     $userphones = getuserphone();
    if(isset($userphones))
    foreach($userphones as $userphone)
    {
        echo "<tr>";
        echo "<td>$userphone->name</td>";
        echo "<td>$userphone->phoneno</td>";
        echo "<td colspan='2'><a href = 'smstracking.php?id=4&userid=$userphone->id&name=$userphone->name'><i class='icon-pencil'></i></a></td>";
        //echo "<td><a href = 'smstracking.php?id=2&delid=$userphone->id'><i class='icon-trash'></i></a></td>";
        echo "</tr>";
    }
 else{
     echo 
     "<tr>
         <td colspan='6'>No Sms Tracking Created</td>
    <tr>";
     }
?>
    </tbody>
</table>
