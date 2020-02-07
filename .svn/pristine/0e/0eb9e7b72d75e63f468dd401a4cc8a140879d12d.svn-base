<?php
/**
 * Attendance Edit Master form
 */


$mob = new Sales($customerno, $userid);
$data = $mob->getattendanceeditdata($atid);
$salesdata = $mob->getsaleslist();
$sridedit = $data['userid'];
$editdatetime = $data['createdon'];
$editdate = date('d/m/Y',strtotime($editdatetime));
$edittime = date('H:i',strtotime($editdatetime));
$status = $data['onoff'];


?>
<br/>
<div class='container'>
    <center>
        <form name="editattendanceform" id="editattendanceform" method="POST" action="sales.php?pg=attendanceedit" onsubmit="editattendancedata();
                return false;">
            <table class='table table-condensed' style="width: 50%;">
                <thead><tr><th colspan="100%" >Edit Attendance </th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                    <?php
                    if ($_SESSION['role_modal'] !== "sales_representative"){
                        ?>    
                        <tr><td class='frmlblTd'> Sales Person<span class="mandatory">*</span></td><td>
                                <select name="srcode" id="srcode" style="width:250px;">
                                    <option value="">Select</option>
                                    <?php
                                    foreach ($salesdata as $row) {
                                        if($sridedit==$row->userid){
                                            $selectvar = "selected";
                                        }else{
                                            $selectvar='';
                                        }
                                        ?>
                                        <option value="<?php echo $row->userid; ?>" <?php echo $selectvar;?> ><?php echo $row->realname; ?></option>
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
                            <input type="text" name="STdate" id="SDate" value="<?php echo $editdate; ?>">
                            <input id="STime" class="input-mini" type="text" data-date="<?php echo $edittime;?>" name="STime" value="<?php echo $edittime;?>">
                            <input type="hidden" name="status" value="<?php echo $status; ?>">
                        </td>
                    </tr>
                    <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="editatendance" value="Edit Attendance" class='btn btn-primary'></td></tr>
                </tbody>
            </table>
        </form>
    </center>
</div>