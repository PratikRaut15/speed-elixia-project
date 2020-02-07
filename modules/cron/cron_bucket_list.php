<?php
require_once '../../lib/bo/CronManager.php';
$cronm = new CronManager();

$tasks = $cronm->get_next_day_bucket();
$apt_date = date('d-m-Y',strtotime("+ 1 day")); 

if(isset($tasks))
{
    $x = 1;
    $message = "";
    $message.="<table border='1'><tr><th>Sr No.</th><th>Bucket ID</th><th>Customer #</th><th>Company</th><th>Vehicle No</th><th>Priority</th><th>Location</th><th>Time Slot</th><th>Purpose</th><th>Co-ordinator Name</th><th>Co-ordinator Phone</th></tr>";            
    foreach($tasks as $thistask)
    {
        $message.="<tr><td>".$x."</td><td>".$thistask->bucketid."</td><td>".$thistask->customerno."</td><td>".$thistask->customercompany."</td><td>".$thistask->vehicleno."</td><td>".$thistask->priority."</td><td>".$thistask->location."</td><td>".$thistask->timeslot."</td><td>".$thistask->purpose."</td><td>".$thistask->person_name."</td><td>".$thistask->cp_phone1."</td></tr>";
        $x++;
    }
    $message.="</table>";
        $emails = Array();
        $emails[] = 'sanketsheth1@gmail.com';
        $emails[] = 'mihir@elixiatech.com';
        $emails[] = 'mukundsheth2@gmail.com';
        $emails[] = 'operations@elixiatech.com';
        $emails[] = 'support@elixiatech.com';

        $subject = "Bucket List for ".$apt_date;
        
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