<?php
/**
 * Area Edit Master form
 */
?>
<?php
$mob = new Sales($customerno, $userid);
$areaeditdata = areaedit($_SESSION['customerno'], $_SESSION['userid'], $areaid);
if (isset($areaeditdata)) {
    foreach ($areaeditdata as $row) {
        $areaid = $row['areaid'];
        $olddistid = $row['distributorid'];
        $areaname = $row['areaname'];
    }
}

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
        <form name="areaeditform" id="areaeditform" method="POST" action="sales.php?pg=areaedit&areaid=<?php echo$areaid; ?>" onsubmit="updateareadata();
            return false;">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Update Area</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>

                    <tr><td class='frmlblTd'> Distributor <span class="mandatory">*</span></td><td>
                            <select name="distid" style="width:250px;">
                                <option value="0">Select</option>
                            <?php
                            foreach($distdata as $row){
                             ?>   
                                <option value="<?php echo $row->userid;?>" <?php if($row->userid==$olddistid ){echo "selected"; }?>> <?php echo $row->realname;?> </option>   
                             <?php
                            }
                            ?>
                            </select>
                        </td></tr>
                    <tr><td class='frmlblTd'> Area Name <span class="mandatory">*</span></td><td><input type="text" name="areaname" value="<?php echo $areaname; ?>"></td></tr>
                    <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Update" class='btn btn-primary'></td></tr>
                </tbody>
            </table>
            <input type="hidden" name="areaid" id="areaid" value="<?php echo $areaid; ?>"/>
        </form>
    </center>
</div>
