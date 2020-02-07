<?php
if(isset($_GET['delid']) && $_GET['delid'])
{
    deldealer($_GET['delid']);
}
    $dealers = getdealers($_SESSION['userid']);
    $x = 1;
    include 'panels/viewdealers.php';
    if(isset($dealers))
    foreach($dealers as $dealer)
    {
        echo "<tr>";
        echo "<td>".$x++."</td>";        
        echo "<td>$dealer->name</td>";
        echo "<td>$dealer->realname</td>";                
        echo "<td>$dealer->timestamp</td>";                        
        echo "<td><a href = 'dealer.php?id=4&dealerid=$dealer->dealerid&dealername=$dealer->name'><i class='icon-pencil'></i></a></td>";
        ?>
        <td><a href = 'dealer.php?id=2&delid=<?php echo($dealer->dealerid); ?>' onclick="return confirm('Are you sure you want to delete?');"><i class='icon-trash'></i></a></td>
        <?php
        echo "</tr>";
    }
 else
     echo 
     "<tr>
         <td colspan='6'>No Dealer Created</td>
    <tr>";
?>
    </tbody>
</table>
