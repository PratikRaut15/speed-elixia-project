<?php
/**
 * Shop Edit Master form
 */
$mob = new Sales($customerno, $userid);
$shoptype = shoptype($_SESSION['customerno'], $_SESSION['userid']);
?>
<?php
$shopeditdata = shopedit($_SESSION['customerno'], $_SESSION['userid'], $sid);
//echo'<pre>';
//print_r($shopeditdata);

if (isset($shopeditdata)) {
    foreach ($shopeditdata as $row) {
        $sid = $row['shopid'];
        $distributorid = $row['distributorid'];
        $salesid = $row['salesid'];
        $areaid = $row['areaid'];
        $shopname = $row['shopname'];
        $phone = $row['phone'];
        $phone2 = $row['phone2'];
        $owner = $row['owner'];
        $address = $row['address'];
        $emailid = $row['emailid'];
        $dob = $row['dob'];
        $shoptypeid = $row['shoptypeid'];
    }

    if (!empty($dob) && $dob != "1970-01-01") {
        $datetest = date("d-m-Y", strtotime($dob));
        $dob1 = $datetest;
    } else {
        $dob1 = date("d-m-Y");
    }
}
///get data 

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
        <form name="shopeditform" id="shopeditform" method="POST" action="sales.php?pg=shopedit&sid=<?php echo$sid; ?>" onsubmit="updateshopdata();
                return false;">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Update Shop</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                    <?PHP if ($_SESSION['role_modal'] != "sales_representative") { ?>
                        <tr>
                            <td class='frmlblTd'> Sales <span class="mandatory">*</span></td><td>
                                <select name="saleid" id="srcode" style="width:250px;">
                                    <option value="0">Select</option>
                                    <?php
                                    foreach ($srdata as $row) {
                                        ?>    
                                        <option value="<?php echo $row->userid; ?>"<?php
                                        if ($row->userid == $salesid) {
                                            echo "selected";
                                        }
                                        ?>><?php echo $row->realname; ?></option>
                                                <?php
                                            }
                                            ?>
                                </select>
                            </td>
                        </tr>
                    <?php } ?>

                    <tr><td class='frmlblTd'> Distributor <span class="mandatory">*</span></td><td>
                            <select name="distid" id="distid"  style="width:250px;">
                                <option value="0">Select</option>
                                <?php
                                foreach ($distdata as $row) {
                                    ?>
                                    <option value="<?php echo $row->userid; ?>" <?php
                                    if ($row->userid == $distributorid) {
                                        echo "selected";
                                    }
                                    ?>><?php echo $row->realname; ?></option>
                                            <?php
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
                                    $oldid = $res[$i]['id'];
                                    $oldval = $res[$i]['value'];
                                    ?>    
                                    <option value="<?php echo $oldid; ?>"<?php
                                    if ($oldid == $areaid) {
                                        echo "selected";
                                    }
                                    ?>><?php echo $oldval; ?></option>
                                            <?php
                                        }
                                        ?>
                            </select>
                        </td></tr>
                    <tr><td class='frmlblTd'> Shop Name <span class="mandatory">*</span></td><td><input type="text" name="shopname" value="<?php echo $shopname; ?>"></td></tr>
                    <tr><td class='frmlblTd'> Shop Type</td>
                        <td>
                            <select name="shoptype" id="shoptype" style="width:250px;">
                                <option value="">Select</option>
                                <?php
                                foreach ($shoptype as $row) {
                                    ?>    
                                    <option value="<?php echo $row['shid']; ?>" <?php
                                    if ($row['shid'] == $shoptypeid) {
                                        echo"selected='selected'";
                                    }
                                    ?> ><?php echo $row['shop_type']; ?></option>
                                        <?php }
                                        ?>
                            </select>
                        </td></tr>    

                    <tr><td class='frmlblTd'>Phone No</td><td><input type="text" name="sphoneno" maxlength="15"value="<?php echo $phone; ?>"></td></tr>
                    <tr><td class='frmlblTd'>Phone No 2</td><td><input type="text" name="sphoneno2" maxlength="15" value="<?php echo $phone2; ?>"></td></tr>
                    <tr><td class='frmlblTd'> Owner </td><td><input type="text" name="owner" value="<?php echo $owner; ?>"></td></tr>
                    <tr><td class='frmlblTd'> Address </td><td><textarea name='saddress' id='saddress'><?php echo $address; ?></textarea></td></tr>
                    <tr><td class='frmlblTd'>Email</td><td><input type="email" name="semail" value="<?php echo $emailid; ?>" ></td></tr>
                    <tr><td class='frmlblTd'>Birth Date</td><td><input type="text" name="cdob"  maxlength="15" value="<?php echo $dob1; ?>"></td></tr>

                    <?php
                    $sign = "../../customer/" . $_SESSION['customerno'] . "/secsales/signature/" . $sid . ".jpg";
                    if (file_exists($sign)) {
                        ?>
                        <tr>
                            <td>Signature</td>
                            <td colspan="3">
                                <img alt="signature" width="150px;" height="80px;" src="<?php echo $sign; ?>" style="border:1px solid #ccc; padding: 5px;" />
                            </td>
                        </tr>
                        <?php
                    }
                    $photo = "../../customer/" . $_SESSION['customerno'] . "/secsales/photo/" . $sid . ".jpg";
                    if (file_exists($photo)) {
                        ?>
                        <tr>
                            <td width="15%">Photos</td>
                            <td colspan="3">
                                <?php
                                echo '<img alt="photo" width="150px;" height="80px;" src="' . $photo . '" style="border:1px solid #ccc; padding: 5px;" margin-left:25px; />';
                                echo "&nbsp;&nbsp;";
                                echo"</td></tr>";
                            }
                            ?>
                    <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Update" class='btn btn-primary'></td></tr>         


                </tbody>
            </table>
            <input type="hidden" name="sid" id="sid" value="<?php echo $sid; ?>">
        </form>


    </center>
</div>
