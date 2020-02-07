<?php
/**
 * Shop Master form
 */
$mob = new Sales($customerno, $userid);
$shoptype = shoptype($_SESSION['customerno'], $_SESSION['userid']);

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
    $srdata = $mob->get_sr_by_supervisors($_SESSION['userid'], $_SESSION['customerno'], 'ALL');
    $distdata = $mob->getDistributordata_bysr($_SESSION['userid'], 'ALL');
}
?>
<br/>
<div class='container'>
    <center>
        <form name="shopform" id="shopform" method="POST" action="sales.php?pg=shop" onsubmit="addshopdata();
                return false;">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Add Shop</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                    <?php if ($_SESSION['role_modal'] != "sales_representative") { ?>
                        <tr><td class='frmlblTd'> Sales <span class="mandatory">*</span></td><td>
                                <select name="saleid" id="srcode" style="width:250px;">
                                    <option value="0">Select</option>
                                    <?php
                                    foreach ($srdata as $row) {
                                        echo"<option value=" . $row->userid . ">" . $row->realname . "</option>";
                                    }
                                    ?>
                                </select>
                            </td></tr>
                    <?php } ?>
                    <tr><td class='frmlblTd'> Distributor <span class="mandatory">*</span></td><td>
                            <select name="distid" id="distid" style="width:250px;">
                                <option value="0">Select</option>
                                <?php
                                foreach ($distdata as $row) {
                                    echo"<option value=" . $row->userid . ">" . $row->realname . "</option>";
                                }
                                ?>
                            </select>
                        </td></tr>


                    <tr><td class='frmlblTd'> Area <span class="mandatory">*</span></td><td>
                            <?php
                            $res = get_area($_SESSION['customerno'], $_SESSION['userid']);
                            $c = count($res);
                            ?>                    
                            <select name="areaid" id="areaid" style="width:250px;">
                                <option value="0">Select</option>
                                <?php
                                for ($i = 0; $i < $c; $i++) {
                                    echo"<option value=" . $res[$i]['id'] . ">" . $res[$i]['value'] . "</option>";
                                }
                                ?>
                            </select>
                        </td></tr>
                    <tr><td class="frmlblTd">Add After <span class="mandatory">*</span></td>
                        <td>
                            <select name="shopid" id="shopid">
                            </select>
                        </td></td></tr>
                    <tr><td class='frmlblTd'> Shop Name <span class="mandatory">*</span></td><td><input type="text" name="shopname"></td></tr>
                    <tr><td class='frmlblTd'> Shop Type</td>
                        <td>
                            <select name="shoptype" id="shoptype" style="width:250px;">
                                <option value="">Select</option>
                                <?php
                                foreach ($shoptype as $row) {
                                    ?>    
                                    <option value="<?php echo $row['shid']; ?>"><?php echo $row['shop_type']; ?></option>
                                <?php }
                                ?>
                            </select>
                        </td></tr>
                    <tr><td class='frmlblTd'>Phone No</td><td><input type="text" name="sphoneno" maxlength="15"></td></tr>
                    <tr><td class='frmlblTd'>Phone No 2</td><td><input type="text" name="sphoneno2" maxlength="15"></td></tr>
                    <tr><td class='frmlblTd'>Birth Date</td><td><input type="text" name="cdob"  maxlength="15"></td></tr>
                    <tr><td class='frmlblTd'> Owner </td><td><input type="text" name="owner"></td></tr>
                    <tr><td class='frmlblTd'> Address </td><td><textarea name='saddress' id='saddress'></textarea></td></tr>
                    <tr><td class='frmlblTd'>Email</td><td><input type="email" name="semail" ></td></tr>
                    <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Add" class='btn btn-primary'></td></tr>
                </tbody>
            </table>
            <?php if ($_SESSION['role_modal'] == "sales_representative") { ?>
                <input type="hidden" id="saleid" name="saleid" value="<?php echo $userid; ?>">
            <?php } ?>

        </form>
    </center>
</div>
