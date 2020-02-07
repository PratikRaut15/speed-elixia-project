<br/>
<h3>Expenses Allotment</h3>
<br/>
<script type="text/javascript" src="../reports/createcheck.js"></script>
<?php
$drivers = getdrivers_allocated();
include 'panels/viewdrivers_exp.php';
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
if (isset($drivers))
    foreach ($drivers as $driver) {
        $test = $driver->driverid;
        $totalfund = get_totalfund($test, $_SESSION['customerno']);

        echo "<tr>";
        echo "<td>$driver->drivername</td>";
        echo "<td id='amount_$test'>" . $totalfund . "</td>";

        echo"<td><a id='added_$test' style='display:none;'><img src='../../images/added.png' alt='added as checkpoint' width='18' height='18'/></a>
                 <a href='#test_$test' id='add_$test' data-toggle='modal'>
			<img src='../../images/add.png' alt='add as checkpoint' width='18' height='18'/>
                </a>
            </td>";

        //echo "<td><a id='history' href='javascript:void(0);'><img alt='History' title='History' src='../../images/history.png'></a></td>";
        echo "<div id='test_$test' class='modal hide in' style='width:550px; height:350px; display:none;'>
                <form>
                    <div class='modal-header'>
                        <button class='close' data-dismiss='modal'>×</button>
                        <h4 style='color:#0679c0'>Add Amount</h4>
                    </div>
                    <div class='modal-body'>
                        <span class='add-on' style='color:#000000'>Enter Amount </span>&nbsp;
                        <input type='text' name='amounttxt' id='amt_$test' value=''/></br></br>
                       <span class='add-on' style='color:#000000'>Amount Details </span>&nbsp;
                        <textarea  name='amountdetails' id='amtdetails_$test'></textarea></br></br>
                        <input type='hidden' name='customer' id='customer' value='$customerno'/>
                        <input type='hidden' name='userid' id='userid' value='$userid'/>
                        <input type='button' value='Submit' onclick='amountsave($test)' class='btn-primary'>    
                    </div> 
                </form>
                </div>
        </td>";
        echo "</tr>";
    } else
    echo
    "<tr>
         <td colspan='6'>No Driver Created</td>
    <tr>";
?>
</tbody>
</table>
<script>
jQuery(document).ready(function (){
    
});
    function amountsave(test) {
        var amount = jQuery("#amt_" + test).val();
        var amountdetail = jQuery("#amtdetails_" + test).val();
        var userid = jQuery("#userid").val();
        var customerno = jQuery("#customer").val();
        if (amount == '') {
            alert("Amount should not be blank");
        }
        if (amount !== '') {
            jQuery.ajax({
                type: "POST",
                url: "../driver/driverexp_ajax.php",
                data: "driverid=" + test + "&amount=" + amount + "&amountdetail="+escape(amountdetail)+"&userid=" + userid + "&customerno=" + customerno + "&action=addamount",
                success: function (result) {
                    var obj = jQuery.parseJSON(result);
                    if (obj.amount != "") {
                        jQuery("#amount_" + test).html("");
                        jQuery("#amount_" + test).html(obj.amount);
                        jQuery("#test_"+test).modal('hide');
                    }
                }
            });
        }
    }

</script>