<?php
function sendemail($email,$message,$vehicle,$subject,$thiscustomerno)
{
    $cqm = new CommunicationQueueManager();
    $cvo = new VOCommunicationQueue();
    $cvo->email = $email;
    
    $cvo->message = $vehicle->vehicleno." $message.<br/> Powered by Elixia Tech.";
    $cvo->subject = $subject;
    $cvo->phone = "";
    $cvo->type = 0;
    $cvo->customerno = $thiscustomerno;    
    $cqm->InsertQ($cvo);
}
function sendmessage($phone,$message,$vehicle,$thiscustomerno)
{
    $cqm = new CommunicationQueueManager();
    $cvo = new VOCommunicationQueue();
    $cvo->phone = $phone;
    $cvo->message = $vehicle->vehicleno." $message. Powered by Elixia Tech.";
    $cvo->subject = "";
    $cvo->email = "";
    $cvo->type = 1;
    $cvo->customerno = $thiscustomerno;    
    $cqm->InsertQ($cvo); 
}
?>