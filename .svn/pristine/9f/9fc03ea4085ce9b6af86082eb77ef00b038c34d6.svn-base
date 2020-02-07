<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include_once '../../lib/system/utilities.php';
require_once '../../lib/autoload.php';
$cronm = new CronManager();

$reminders = $cronm->fetchInvoiceReminders();
// echo "<pre>";
// print_r($reminders);
$sr=0;
if (!empty($reminders)) {
	$html = "<style>
				table, th, td {
				    border: 1px solid black;
				}
			</style>";
    $table = '<table style="border:1px black solid">
    			<tr>
    				<th> Sr no </th>
    				<th> Customer </th>
    				<th> Ledger </th>
    				<th> Product </th>
    				<th> Invoice Amount </th>
    				<th> Invoice Type </th>
    				<th> Cycle </th>
    				<th> Expected invoice date </th>
    				<th> Generate Invoice </th>
    			</tr>';
    $sr = 1;
    foreach ($reminders as $reminder) {
        $sr++;
        $table.="<tr>
        			<td>".$sr."</td>
        			<td>".$reminder['customercompany']."</td>
        			<td>".$reminder['ledgername']."</td>
        			<td>".$reminder['prod_name']."</td>
        			<td>".$reminder['invoiceAmount']."</td>
        			<td>".$reminder['inv_type_name']."</td>
        			<td>".$reminder['cycle_name']."</td>
        			<td>".$reminder['expectedInvDate']."</td>
        			<td><a href='../team/approveInvoice.php?invoiceId=".$reminder['inv_rem_id']."'>Approve</a></td>
    			</tr>";
    }
    $table.="</table>";

    $html .= "Dear Team,<br>
                Please find the Scheduled Invoice Reminders : <br><br>";
    $html .= $table;
    $subject = "Invoice Generation Reminder :" . date('d-m-Y', strtotime('-1 day', strtotime(date('Y-m-d'))));
    $to = array("sanketsheth@elixiatech.com", "mihir@elixiatech.com", "accounts@elixiatech.com");
    $strCCMailIds = "";
    $strBCCMailIds = "";
    $attachmentFilePath = "";
    $attachmentFileName = "";
    $isTemplatedMessage = 1;
    echo $html;
    @sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $html, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
}
?>