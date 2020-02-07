<?php
/**
 * Edit Shop type Master form
 */
?>
<?php
$sheditdata = shedit($_SESSION['customerno'], $_SESSION['userid'], $stypeid);

if (isset($sheditdata)) {
    foreach ($sheditdata as $row) {
        $shid = $row['shid'];
        $shop_type = $row['shop_type'];
    }
}
?>

<br/>
<div class='container'>
    <center>
        <form name="stypeeditform" id="stypeeditform" method="POST" action="sales.php?pg=stypeedit" onsubmit="updateshdata();return false;">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Update Shop Type</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                    <tr><td class='frmlblTd'>Shop Type <span class="mandatory">*</span></td><td><input type="text" name="stypename" value="<?php echo $shop_type; ?>" required></td></tr>
                    <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="stypesubmit" value="Add" class='btn btn-primary'></td></tr>
                    <input type="hidden" name="shid" id="shid" value="<?php echo $shid; ?>">
                </tbody>
            </table>
        </form>
    </center>
</div>
