
<div class="container-fluid">
    <?php
    $ticketdata = getissues();
    include 'panels/viewissues.php';
    if (isset($ticketdata)) {
        foreach ($ticketdata as $thisissue) {
            $ticketid = $thisissue['ticketid'];
            if ($thisissue['status'] == '0') {
                $status = "Open";
            } else if ($thisissue['status'] == '1') {
                $status = "Inprogress";
            } else if ($thisissue['status'] == '2') {
                $status = "Closed";
            } else if ($thisissue['status'] == '3') {
                $status = "Open";
            }
            $close_date1 = date("d-m-Y", strtotime($thisissue['getclosedate']));
            if ($close_date1 == "01-01-1970") {
                $close_date = "-";
            } else {
                $close_date = $close_date1;
            }

            $eclosedate1 = date("d-m-Y", strtotime($thisissue['eclosedate']));
            if ($eclosedate1 == "01-01-1970") {
                $eclose_date = "-";
            } else {
                $eclose_date = $eclosedate1;
            }

            echo "<tr>";
            echo "<td>SUP00" . $thisissue['ticketid'] . "</td>";
            echo "<td>" . $thisissue['ticket_type'] . "</td>";
            echo "<td>" . $thisissue['getusername'] . "</td>";
            echo "<td>" . $thisissue["timestamp"] . "</td>";
            echo "<td>$status</td>";
            echo "<td>" . $thisissue['getclosedesc'] . "</td>";
            echo "<td>" . $eclose_date . "</td>";
            echo "<td>" . $close_date . "</td>";
            // echo "<td></td>";
            //if($thisissue['status'] !='2')
            // {
            echo "<td><a  href = 'support.php?id=3&isid=" . $ticketid . "'> <i class='icon-pencil'></i> </a></td>";
            //}
//        else    
//        {
//            echo "<td></td>";
//        }
            echo "</tr>";
        }
    } else {
        echo
        "<tr>
         <td colspan='6'>No Case Created</td>
    <tr>";
    }
    ?>
</tbody>
</table>
</div>