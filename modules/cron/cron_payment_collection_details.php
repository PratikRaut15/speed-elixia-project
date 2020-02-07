<?php
require_once '../../lib/bo/CronManager.php';
$cronm = new CronManager();

$tasks = $cronm->get_payment_collection_report();
$apt_date = date('d-m-Y',strtotime("+ 1 day")); 
$fromDate =  date('01-m-Y');
$today = date('d-m-Y');
if(!empty($tasks))
{
    $x = 1;
    $message = "";
    $total_amount=0;
    foreach ($tasks as $key => $value) {
        $total_amount += $value->paid_amt; 
    } 

    $message = "Total Amount:".$total_amount;
    $message.="<br><br><table border='1'><tr><th>Sr No.</th><th>Date</th><th>Client Number</th><th>Client Name</th><th>Amount</th><th>Payment Mode</th></tr>";            
    foreach($tasks as $thistask)
    {
        $message.="<tr><td>".$x."</td><td>". date("d-m-Y", strtotime($thistask->paymentdate))."</td><td>".$thistask->customerno."</td><td>".$thistask->customercompany."</td><td>".$thistask->paid_amt."</td><td>".$thistask->pay_mode."</td></tr>";
        $x++;
    }
    $message.="</table>";
    // $email='amitt@elixiatech.com';
        
    }
    else
    {
        $message = "No data available!!";
    }
        $emails = Array();
        $emails[] = 'sanketsheth@elixiatech.com';
        $emails[] = 'amitt@elixiatech.com';
        $subject = "Daily Payment Collection Report from $fromDate to $today";
        // echo $subject;
        // echo $message; exit;
        foreach($emails as $email)
        {
            sendMail($email, $subject, $message);
        }


function sendMail( $to, $subject , $content)
{
    $subject = $subject;

    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";    
    if (!@mail($to, $subject, $content, $headers)) {
        // echo 'message sending failed'; exit;
        return false;
    }
    return true;        
}

?>