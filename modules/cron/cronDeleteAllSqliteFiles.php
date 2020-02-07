<style type="text/css">
    body{
        font-family:Arial;
        font-size: 11pt;
    }
    table{
        text-align: center;
        border-right:1px solid black;
        border-bottom:1px solid black;
        border-collapse:collapse;
        font-family:Arial;
        font-size: 10pt;
        width: 45%;
    }
    td, th{
        border-left:1px solid black;
        border-top:1px solid black;
        text-align: left;
    }
    .colHeading{
        background-color: #D6D8EC;
        width:30%;
    }
    span{
        font-weight:bold;
    }

    hr.style-six {
        border: 0;
        height: 0;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    }
</style>
<?php
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';

$totalCustomer = 2;
//
//$output = shell_exec('du -sh /var/www/html/speed/customer/'. $i.'/log');
$display = '';
$totalFiles = 0;
$totalSize = 0;
for ($i = 1; $i < $totalCustomer; $i++) {
	$files = array();
	$customerno = $i;
	//$file_path = "../../customer/" . $customerno . "/log";
	//$files = glob($file_path . '/*.txt');
	//$table = "<table>";

	$allFiles = listFolderFiles("../../customer/" . $customerno . "");
	print_r($allFiles);

}

function listFolderFiles($dir) {
	$ffs = scandir($dir);

	unset($ffs[array_search('.', $ffs, true)]);
	unset($ffs[array_search('..', $ffs, true)]);

	// prevent empty ordered elements
	if (count($ffs) < 1) {
		return;
	}

	echo '<ol>';
	foreach ($ffs as $ff) {
		echo '<li>' . $ff;
		if (is_dir($dir . '/' . $ff)) {
			listFolderFiles($dir . '/' . $ff);
		}

		echo '</li>';
	}
	echo '</ol>';
}

?>
