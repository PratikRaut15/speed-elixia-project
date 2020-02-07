

<?php
/**
 * Primarysales Master form
 */
$mob = new Sales($customerno, $userid);
$skulist = $mob->get_styleview();
$catlist = $mob->get_catview();
?>
<script type="text/javascript">
    var rowCount = 0;
    function addrow(frm) {
        rowCount++;
        var table = document.getElementById("myTable");
        var row = table.insertRow(1);
        row.id = rowCount+"trid";
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        cell1.innerHTML = "<select style='width:200px;' name='category[]' onchange = 'getskubycategory("+rowCount+");' id='category"+rowCount+"'><option value='0'>Select Category</option><?php
foreach ($catlist as $category) {
    echo "<option value='" . $category->categoryid . "'>" . $category->categoryname . "</option>";
}
?></select>";
        cell2.innerHTML = "<select style='width:300px;' name='sku[]' id='sku"+rowCount+"'><option value=\"0\">Select Sku</option> <?php foreach ($skulist as $skus) { ?> <option value='<?php echo $skus->skuid; ?>'><?php echo $skus->styleno; ?></option> <?php } ?></select>";
        cell3.innerHTML = "<input type='text' name='qty[]'  id='qty"+rowCount+"'/>";
        cell4.innerHTML = "<a href=\"javascript:void(0);\" onclick=\"myDeleteFunction(" + rowCount + ");\"><img src=\"../../images/hide.gif\" alt=\"Delete\"/></a><input type='hidden' id='countrow"+rowCount+"' value='"+rowCount+"' />";
    }

    function myDeleteFunction(a) {
        var trid = '#'+a+'trid';
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
        <form name="primarysalesform" id="primarysalesform" method="POST" action="sales.php?pg=prisales" onsubmit="addprimarysales();
                return false;">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Primary Sales </th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                    <?php
                    $usermanager = new UserManager();
                    $getuser = $usermanager->get_user($_SESSION['customerno'], $_SESSION['userid']);
                    $asmid = $getuser->heirarchy_id;
                    $salesid = $getuser->id;

                    if ($_SESSION['role_modal'] !== "sales_representative") {
                        ?>    
                        <tr><td class='frmlblTd'> Sales Person<span class="mandatory">*</span></td><td>
                                <select name="srcode" id="srcode" style="width:250px;">
                                    <option value="">Select</option>
                                    <?php
                                    foreach ($srdata as $row) {
                                        ?>
                                        <option value="<?php echo $row->userid; ?>"><?php echo $row->realname; ?></option>
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
                                <option value="">select</option>    
                                <?php
                                if (isset($distdata)) {
                                    $i = 0;
                                    foreach ($distdata as $row) {
                                        ?>  
                                        <option value="<?php echo $row->userid; ?>"><?php echo $row->realname; ?></option>
                                        <?php
                                        $i++;
                                    }
                                }
                                ?>  
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td class='frmlblTd'> Expected Delivery Date </td>
                        <td>
                            <input type="text" name="STdate" id="SDate" />
                            <input id="STime" class="input-mini" type="text" data-date="00:00" name="STime" />
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <table style="display: table; width: 100%" id="myTable">
                                <tr><th>Category<span style="color:red;">*</span></th><th>Sku list<span style="color:red;">*</span></th><th>Quantity<span style="color:red;">*</span></th>
                                    <th>
                                        <span style="float: right;" onclick="addrow(this.form);">
                                            <a> <img src="../../images/show.png" alt="Add Row"/> </a>
                                        </span>
                                    </th>
                                </tr>
                            </table>
                        </td>
                    </tr>



                    <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Send For Approval" class='btn btn-primary'></td></tr>
                </tbody>
            </table>
            <?php
            if ($_SESSION['role_modal'] == "sales_representative") {
                ?>
                <input type="hidden" id="srcode" name="srcode" value="<?php echo $salesid; ?>">
                <input type="hidden" id="asmid" name="asmid" value="<?php echo $asmid; ?>">
<?php } ?>
        </form>
    </center>
</div>