<?php
/**
 * Distributor Master form
 */
$mob = new Sales($customerno, $userid);
if ($_SESSION['role_modal'] == "ASM"){
    $salesdata = get_asm_salespersons($_SESSION['userid'], $_SESSION['customerno']);
} else {
    $salesdata = $mob->getsaleslist();
}
?>
<br/>
<div class='container'>
    <center>
        <form name="distributor" id="distributor" method="POST" action="sales.php?pg=dist" onsubmit="adddistdata();
                return false;">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Add Distributor </th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                    <?php if($_SESSION['role_modal']!="sales_representative"){?>
                    <tr><td class='frmlblTd'> Sales <span class="mandatory">*</span></td><td>
                            <select name="saleid" style="width:250px;">
                                <option value="0">Select</option>
                            <?php
                            foreach ($salesdata as $row) {
                            ?>
                                <option value="<?php echo $row->userid?>"><?php echo $row->realname; ?> </option>
                            <?php 
                            }
                            ?>
                            </select>
                        </td></tr>
                    <?php } ?>
                    <tr><td class='frmlblTd'> Distributor Code <span class="mandatory">*</span></td><td><input type="text" name="distcode"></td></tr>
                    <tr><td class='frmlblTd'> Distributor Name <span class="mandatory">*</span></td><td><input type="text" name="distname"></td></tr>
                    <tr><td class='frmlblTd'>Birth Date</td><td><input type="text" name="cdob"  maxlength="15"></td></tr>
                    <tr><td class='frmlblTd'>Phone No</td><td><input type="text" name="distphone"  maxlength="10"></td></tr>
                    <tr><td class='frmlblTd'>Email Id</td><td><input type="text" name="emailid" id="emailid"></td></tr>
                    <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Add" class='btn btn-primary'></td></tr>
                </tbody>
            </table>
            <?php if($_SESSION['role_modal']=="sales_representative"){?>
            <input type="hidden" name="saleid" id="saleid" value="<?php echo $userid; ?>">
            <?php  } ?>
        </form>
    </center>
</div>
