<?php
//Total SMS Balance report send to sanketsheth@elixiatech.com
$mailTo = array("sanketsheth@elixiatech.com");
require "../../lib/system/utilities.php";

function callCURL($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $combined = curl_exec($ch);
    curl_close($ch);
    return $combined;
}

function getResult($urls) {
    $return = array();
    foreach ($urls as $name => $url) {
        $response = callCURL($url);
        $return[$name] = $response;
    }
    return $return;
}

$urls = array("Speed" => "http://103.242.119.152/vendorsms/CheckBalance.aspx?user=elixia_speed&password=elixia@123"
    , "Trace" => "http://103.242.119.152/vendorsms/CheckBalance.aspx?user=elixia_trace&password=elixia@123"
    , "MDLZ-TMS" => "http://103.242.119.152/vendorsms/CheckBalance.aspx?user=elixia_tms&password=elixia@123");

$result = getResult($urls);
$i = 0;
$table = "<table><tr><th>Sr No</th><th>Module</th><th>SMS Balance</th></tr>";
foreach ($result as $module => $row) {
    $i++;
    $table.="<tr><td>" . $i . "</td><td>" . $module . "</td><td>" . $row . "</td></tr>";
}
$table.="</table>";

$html = file_get_contents('../emailtemplates/smsBalance.html');
$html = str_replace("{{TABLE}}", $table, $html);

$strCCMailIds = "";
$strBCCMailIds = "mrudang.vora@elixiatech.com";
$subject = "Total SMS Balance";
$attachmentFilePath = "";
$attachmentFileName = "";
$isTemplatedMessage = 1;
$isMailSent = sendMailUtil($mailTo, $strCCMailIds, $strBCCMailIds, $subject, $html, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
if($isMailSent == 1){
    echo 'Sent';
}  else {
    echo 'Not Sent';
}
?>

