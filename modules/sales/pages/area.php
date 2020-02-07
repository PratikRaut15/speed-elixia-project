<?php
/**
 * Area Master form
 */
$mob = new Sales($customerno, $userid);

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
}
?>
<br/>
<div class='container'>
    <center>
        <form name="areaform" id="areaform" method="POST" action="sales.php?pg=area" onsubmit="addareadata();
            return false;">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Area Add</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>

                    <tr><td class='frmlblTd'> Distributor <span class="mandatory">*</span></td><td>

                            <select name="distid" style="width:250px;">
                                <option value="0">Select</option>
<?php
foreach ($distdata as $row) {
    ?>
                                    <option value="<?php echo $row->userid; ?>"><?php echo $row->realname; ?></option>";
    <?php
}
?>
                            </select>
                        </td></tr>
                    <tr><td class='frmlblTd'> Area Name <span class="mandatory">*</span></td><td><input type="text" name="areaname"></td></tr>
                    <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Add" class='btn btn-primary'></td></tr>
                </tbody>
            </table>
        </form>
    </center>
</div>
