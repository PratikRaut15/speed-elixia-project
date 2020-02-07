<?php
/**
 * Attendance Master form
 */
$mob = new Sales($customerno, $userid);

if ($_SESSION['role_modal'] == "ASM") {
    $salesdata = get_asm_salespersons($_SESSION['userid'], $_SESSION['customerno']);
    $userid = array();
    foreach ($salesdata as $row) {
        $userid[] = $row->userid;
    }
    //$shoplist  = $mob->getallshoplist($userid);
} else if ($_SESSION['role_modal'] == "sales_representative") {
   // $shoplist  = $mob->getallshoplist($userid);
} else {
    $salesdata = $mob->getsaleslist();
    //$shoplist  = $mob->getallshoplist();
}
?>
<br/>
<div class='container'>
    <center>
        <form name="attdform" id="attendanceform" method="POST" action="sales.php?pg=attendanceadd" onsubmit="addattendancedata();
                return false;">
            <table class='table table-condensed' style="width:50%;">
                <thead><tr><th colspan="100%" >Add Attendance </th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>

                    <?php
                    if ($_SESSION['role_modal'] !== "sales_representative") {
                        ?>    
                        <tr><td class='frmlblTd'> Sales Person<span class="mandatory">*</span></td><td>
                                <select name="srcode" id="srcodeentry" style="width:250px;">
                                    <option value="">Select</option>
                                    <?php
                                    foreach ($salesdata as $row) {
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
                        <td class='frmlblTd'> Date </td>
                        <td>
                            <input type="text" name="STdate" id="SDate">
                            <input id="STime" class="input-mini" type="text" data-date="00:00" name="STime">
                        </td>
                    </tr>
                    <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="addatendance" value="Add Attendance" class='btn btn-primary'></td></tr>
                </tbody>
            </table>
        </form>
    </center>
</div>