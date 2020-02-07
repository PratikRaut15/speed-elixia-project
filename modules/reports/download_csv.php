<?php
@session_start();
error_reporting("Error");
header("Content-Disposition: attachment; filename=report.csv");
header("Content-Length: ".strlen($_SESSION[csv]));
header("Connection: close");
header("Content-Type: text/x-csv;");
header('Content-Type: text/csv');
echo urldecode(stripslashes($_SESSION[csv]));
unset($_SESSION[csv]);
?>