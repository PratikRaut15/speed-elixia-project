<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/DatabaseManager.php");
include_once '../../lib/bo/VehicleManager.php';
include_once '../../modules/realtimedata/rtd_functions.php';

//


$_scripts[] = "../../scripts/prototype.js";
//$_scripts[] = "../../scripts/jquery-1.7.2.min.js";
//$_scripts[] = "../../scripts/jquery-ui-1.8.13.custom.min.js";
//$_scripts[] = "../../scripts/jQueryRotate.2.1.js";
$_scripts[] = "../../scripts/jquery-latest.js";
$_scripts[] = "../../scripts/tablesorter/jquery.tablesorter.js";



include("header.php");

?>

 <div class="panel">
<div class="paneltitle" align="center">Realtime data</div>
<div class="panelcontents">
<?php
//error_reporting(E_ALL);
    //ini_set('display_errors', 1);
    //ini_set('display_startup_errors', 1);

	    display_vehicledata_all();


 ?>
</div>

</div>


<?php
include("footer.php");
?>
<script>
jQuery.noConflict();
jQuery(document).ready(function()
    {

        jQuery("#myTable").tablesorter();
    }
);


</script>
