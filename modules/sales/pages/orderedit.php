<?php
/**
 * Order Edit Master form
 */
//echo $orderid;die();
$mob = new Sales($customerno, $userid);
$editorderdata = $mob->get_secondarysales_details_api($orderid);
$addedby = $editorderdata[0]['addedby'];
$orderdate = $editorderdata[0]['orderdate'];
$orderStatus = $editorderdata[0]['orderStatus'];
//$orderId = $editorderdata[0]['soid'];
$date = date_create($orderdate);
$deltime = date_format($date, 'H:i');
$deldate = date_format($date, 'd-m-Y');
$sku = $editorderdata[0]['totalskus'];
$skulist = $mob->get_styleview();
$catlist = $mob->get_catview();
?>
<script type="text/javascript">
    var rowCount = 0;
    function addrow(frm) {
        rowCount++;
        var table = document.getElementById("myTable");
        var row = table.insertRow(1);
        row.id = rowCount + "trid";
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        cell1.innerHTML = "<select style='width:200px;' name='category[]' onchange = 'getskubycategory(" + rowCount + ");' id='category" + rowCount + "'><option value='0'>Select Category</option><?php
foreach ($catlist as $category) {
    echo "<option value='" . $category->categoryid . "'>" . $category->categoryname . "</option>";
}
?></select>";
        cell2.innerHTML = "<select style='width:300px;' name='sku[]' onchange = 'getinventoryQty(" + rowCount + ");' id='sku" + rowCount + "'><option value=\"0\">Select Sku</option> <?php foreach ($skulist as $skus) { ?> <option value='<?php echo $skus->skuid; ?>'><?php echo $skus->styleno; ?></option> <?php } ?></select>";
        cell3.innerHTML = "<input type='text' readonly value='0' name='invqty[]'  id='invqty" + rowCount + "'/>";
        cell4.innerHTML = "<input type='text' style='width:100px;' name='qty[]'  id='qty" + rowCount + "'/>";
        cell5.innerHTML = "<a href=\"javascript:void(0);\" onclick=\"myDeleteFunction(" + rowCount + ");\"><img src=\"../../images/hide.gif\" alt=\"Delete\"/></a><input type='hidden' id='countrow" + rowCount + "' value='" + rowCount + "' />";
    }

    function myDeleteFunction(a) {
        var trid = '#' + a + 'trid';
        jQuery(trid).remove();
    }
</script>
<?php
if ($_SESSION['role_modal'] == "ASM") {
    $supervisors = $mob->getsupervisors_byasm($_SESSION['userid'], $_SESSION['customerno']);
    $supid = array();
    foreach ($supervisors as $row) {
        $supid[] = $row->userid;
    }
    $srdata = $mob->get_sr_by_supervisors($supid, $_SESSION['customerno']);
    $srid = array();
    foreach ($srdata as $row) {
        $srid[] = $row->userid;
    }
    $distdata = $mob->getDistributordata_bysr($srid);
} else if ($_SESSION['role_modal'] == "Supervisor") {

    $srdata = $mob->get_sr_by_supervisors($_SESSION['userid'], $_SESSION['customerno']);
    $srid = array();
    foreach ($srdata as $row) {
        $srid[] = $row->userid;
    }
    $distdata = $mob->getDistributordata_bysr($srid);
} else if ($_SESSION['role_modal'] == "sales_representative") {
    $distdata = $mob->getDistributordata_bysr($_SESSION['userid']);
} else {
    $distdata = $mob->getDistributordata_bysr($_SESSION['userid'], 'ALL');
    $srdata = $mob->getsrlist($_SESSION['customerno']);
}
?>
<br/>
<div class='container'>
    <center>
        <form name="editorderform" id="editorderform" method="POST" action="sales.php?pg=orderedit&orderid=<?php echo $orderid; ?>" onsubmit="editorder();
                return false;">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Update Order</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                    <?php if ($_SESSION['role_modal'] !== "sales_representative") {
                        ?>
                        <tr><td class='frmlblTd'> Sales Person<span class="mandatory">*</span></td><td>
                                <select name="srcode" id="srcode" style="width:250px;">
                                    <option value="">Select</option>
                                    <?php
                                    foreach ($srdata as $row) {
                                        ?>
                                        <option value="<?php echo $row->userid; ?>" <?php
                                        if ($row->userid == $addedby) {
                                            echo "selected";
                                        }
                                        ?>  ><?php echo $row->realname; ?></option>
                                                <?php
                                            }
                                            ?>
                                </select>
                            </td>
                        </tr>
<?php } ?>
                    <tr>
                        <td class='frmlblTd'> Distributor <span class="mandatory">*</span> </td><td>
                            <select name="distid" id="distid" style="width:250px;">
                                <option value="0">Select</option>
                                <?php
                                foreach ($distdata as $row) {
                                    ?>
                                    <option value="<?php echo $row->userid; ?>" <?php
                                            if ($row->userid == $editorderdata[0]['distributorid']) {
                                                echo "selected";
                                            }
                                            ?>><?php echo $row->realname ?> </option>
    <?php
}
?>
                            </select>
                        </td>
                    </tr>

                    <tr><td class='frmlblTd'> Area <span class="mandatory">*</span> </td><td>
                            <?php
                            if (isset($editorderdata[0]['distributorid'])) {
                                $areaid = $editorderdata[0]['areaid'];
                                $distid = $editorderdata[0]['distributorid'];
                                $areadata = $mob->getareaid($distid);
                            }
                            ?>

                            <select name="areaid" id='areaid' style="width:250px;">
                                <option value="0">Select</option>
                                <?php
                                        foreach ($areadata as $row) {
                                            ?>
                                            <option value="<?php echo $row->areaid; ?>" <?php
                                            if ($row->areaid == $areaid) {
                                                echo "selected";
                                            }
                                            ?>><?php echo $row->areaname ?> </option>
                                        <?php
                                    }
                                    ?>
                            </select>
                        </td>
                    </tr>
                    <tr><td class='frmlblTd'> Shop <span class="mandatory">*</span> </td><td>

                            <?php
                            if (isset($editorderdata[0]['areaid'])) {
                                $areaid = $editorderdata[0]['areaid'];
                                $shopid = $editorderdata[0]['shopid'];
                                $shopdata = $mob->getshopid($areaid);
                            }
                            ?>

                            <select name="shopid" id='shopid' style="width:250px;">
                                <?php
                                        foreach ($shopdata as $row) {
                                            ?>
                                            <option value="<?php echo $row->shopid; ?>" <?php
                                            if ($row->shopid == $shopid) {
                                                echo "selected";
                                            }
                                            ?>><?php echo $row->shopname ?> </option>
                                        <?php
                                    }
                                    ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'> Date </td>
                        <td>
                            <input type="text" name="STdate" id="SDate" value="<?php echo $deldate; ?>">
                            <input id="STime" class="input-mini" type="text" data-date="<?php echo $deltime; ?>"  name="STime">
                        </td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'> Order Status  </td>
                        <td>
                            <?php
                                if($orderStatus == 0){
                                    echo "Order Approved";
                                }else{
                                    ?>
                                    <input type="checkbox" name="orderStatus" id="orderStatus" value="0"> Appove Order
                                    <?php
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table style="display: table; width: 100%" id="myTable">
                                <tr><th>Category<span style="color:red;">*</span></th><th>Sku list<span style="color:red;">*</span></th><th>Inventory Qty</th><th>Quantity<span style="color:red;">*</span></th>
                                    <th>
                                        <span style="float: right;" onclick="addrow(this.form);">
                                            <a> <img src="../../images/show.png" alt="Add Row"/> </a>
                                        </span>
                                    </th>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="100%">
                            <table style="display: table; width: 100%">
                                <tr><th colspan="100%">Product list History</th></tr>
                                <tr>
                                    <th>Category</th><th>Sku list</th><th>Quantity</th>
<?php
if (isset($sku)) {
    foreach ($sku as $row) {
        ?>
                                        <tr>
                                            <td><?php echo $row->categoryname; ?></td>
                                            <td><?php echo $row->styleno; ?></td>
                                            <td><?php echo $row->quantity; ?></td>
                                        </tr>
        <?php
    }
}
?>
                    </tr>
            </table>
            </td>
            </tr>

            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Update Order" class='btn btn-primary'></td></tr>
            </tbody>
            </table>
            <input type="hidden" name="orderid" id="orderid" value="<?php echo $orderid; ?>">
<?php if ($_SESSION['role_modal'] == "sales_representative") { ?>
                <input type="hidden" name="srcode" id="srcode" value="<?php echo $_SESSION['userid']; ?>">
<?php } ?>
        </form>
    </center>
</div>
