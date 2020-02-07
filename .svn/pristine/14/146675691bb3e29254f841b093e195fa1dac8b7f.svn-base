<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");

include("header.php");

class InvAddress {
    
}

$db = new DatabaseManager();
if (isset($_POST['add_address']) || isset($_GET['cno'])) {
    $customerno = GetSafeValueString(isset($_POST['cid']) ? $_POST['cid'] : $_GET['cno'], "string");
}
if (isset($_POST['add_address'])) {
    $invname = GetSafeValueString($_POST['invname'], "string");
    $add1 = GetSafeValueString($_POST['add1'], "string");
    $add2 = GetSafeValueString($_POST['add2'], "string");
    $add3 = GetSafeValueString($_POST['add3'], "string");

    $SQL = "INSERT INTO ".DB_PARENT.".invoice_customer(customerno, invoicename, address1, address2, address3) VALUES ('$customerno','$invname','$add1','$add2','$add3')";
    $db->executeQuery($SQL);
}

//-----------populate customerno list-------
function getcustomer_detail() {
    $db = new DatabaseManager();
    $customernos = Array();
    $SQL = sprintf("SELECT customerno,customername,customercompany FROM ".DB_PARENT.".customer");
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $customer = new InvAddress();
            $customer->customerno = $row['customerno'];
            $customer->customername = $row['customername'];
            $customer->customercompany = $row['customercompany'];
            $customernos[] = $customer;
        }
        return $customernos;
    }
    return false;
}
?>


<!-------------------------------------->
<div class="panel">
    <div class="paneltitle" align="center">Add Invoice Address</div> 
    <div class="panelcontents">
        <form method="post" name="invoiceform" id="invoiceform" action="invoice_address.php">
            <table>
                <tr><td>Client</td>
                    <td>    
                        <select name="cid" id="cid" style="width:200px;" onchange="getCustAddress()">
                            <option value="0">Select Client</option>
                            <?php
                            $cms = getcustomer_detail();
                            foreach ($cms as $customer) {
                                ?> 
                                <option value="<?php echo($customer->customerno); ?>"
                                <?php
                                if (isset($customerno) && $customer->customerno == $customerno) {
                                    echo "selected";
                                }
                                ?> ><?php echo $customer->customerno; ?> - <?php echo $customer->customercompany ?></option>
                                        <?php
                                    }
                                    ?> 

                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Invoice Client Name</td>
                    <td>
                        <input type ="text" name ="invname" id="invname">
                    </td>
                </tr>
                <tr>
                    <td>Address Line 1</td>
                    <td>
                        <input type ="text" name ="add1" id="add1" size="50">
                    </td>
                </tr>
                <tr>
                    <td>Address Line 2</td>
                    <td>
                        <input type ="text" name ="add2" id="add2" size="50">
                    </td>
                </tr>
                <tr>
                    <td>Address Line 3</td>
                    <td>
                        <input type ="text" name ="add3" id="add3" size="50">
                    </td>
                </tr>
            </table>
            <input type="submit" id="add_address" name="add_address" class="btn btn-default" value="Add Details">
        </form>
    </div>

</div>
<br>
<div class="panel">
    <div class="paneltitle" align="center">Address List</div>
    <div class="panelcontents">
        <table border="1" width="100%">
            <tr>
                <th>Sr No.</th>
                <th>Invoice Name</th>
                <th>Address Line1</th>
                <th>Address Line2</th>
                <th>Address Line3</th>
                <th>Edit</th>

            </tr>
            <tbody id="demo" style="text-align: center;">

            </tbody>
        </table>
    </div>
</div>

<script>
    jQuery(document).ready(function () {
        var cno = <?php echo $customerno; ?>;
        
        var selected_cno = jQuery("#cid").find('option:selected').val();
        
        if (cno == selected_cno) {
            getCustAddress();
        }
    });
    function getCustAddress()
    {
        //alert("test");
        var custno = jQuery('#cid').val();
        jQuery.ajax({
            type: "POST",
            url: "inview_address.php",
            cache: false,
            data: {cno: custno},
            success: function (res) {
                var data = jQuery.parseJSON(res);
                //console.log(data);
                details_addinvoice(data);
            }
        });

    }

    function details_addinvoice(data)
    {
        var tags = '';
        var imgsrc = "../../images/edit.png";
        jQuery(data).each(function (i, v) {
            tags += "<tr><td>" + v.x + "</td>";
            tags += "<td>" + v.invmane + "</td>";
            tags += "<td>" + v.add1 + "</td>";
            tags += "<td>" + v.add2 + "</td>";
            tags += "<td>" + v.add3 + "</td>";
            tags += "<td><a href='invedit_address.php?inid=" + v.invid + "'><img src='" + imgsrc + "'></a></td></tr>"
        });

        if (data.length === 0) {
                        var emp = '';
                        emp += "<tr>"
                        emp += "<td colspan=100% style='text-align:center'>No Data Found</td>"
                        emp += "</tr>"
                        jQuery('#demo').html(emp);

                    }

                    else {
                        jQuery('#demo').html(tags);
                    }
    }
</script>