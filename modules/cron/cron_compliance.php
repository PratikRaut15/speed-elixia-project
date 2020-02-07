<?php
require_once '../../lib/bo/CronManager.php';
$cronm = new CronManager();

$tasks = $cronm->get_compliance();
$apt_date = date('d-m-Y',strtotime("- 1 day")); 
$apt_date_m = date('M',strtotime("- 1 day")); 

if(isset($tasks))
{
    $x = 1;
    $message = "";
    $percentage = $cronm->get_compliance_percentage();
    $percentage_m = $cronm->get_compliance_percentage_month();    
    $message ="Compliance Percentage for ".$apt_date.": ".$percentage."% <br/>";
    $message.="Monthly Compliance Percentage for ".$apt_date_m.": ".$percentage_m."% <br/> <br/>";        
    $message.="<table border='1'><tr><th>Sr No.</th><th>Bucket ID</th><th>Customer #</th><th>Company</th><th>Vehicle No</th><th>Purpose</th><th>Installer</th><th>Status</th><th>Compliance</th><th>Reason</th><th>Remarks</th></tr>";            
    foreach($tasks as $thistask)
    {
        $message.="<tr><td>".$x."</td><td>".$thistask->bucketid."</td><td>".$thistask->customerno."</td><td>".$thistask->customercompany."</td><td>".$thistask->vehicleno."</td><td>".$thistask->purpose."</td><td>".$thistask->name."</td><td>".$thistask->status."</td><td>".$thistask->is_compliance."</td><td>".$thistask->reason."</td><td>".$thistask->remarks."</td></tr>";
        $x++;
    }
    $message.="</table>";
    $message.="<br/>";
        $emails = Array();
        $emails[] = 'sanketsheth1@gmail.com';
        $emails[] = 'mihir@elixiatech.com';
        $emails[] = 'mukundsheth2@gmail.com';
        $emails[] = 'operations@elixiatech.com';
        $emails[] = 'support@elixiatech.com';

        $subject = "Compliance Report for ".$apt_date;
        
        foreach($emails as $email)
        {
            sendMail($email, $subject, $message);
        }
}

function sendMail( $to, $subject , $content)
{
    $subject = $subject;

    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";    
    if (!@mail($to, $subject, $content, $headers)) {
        // message sending failed
        return false;
    }
    return true;        
}

?>