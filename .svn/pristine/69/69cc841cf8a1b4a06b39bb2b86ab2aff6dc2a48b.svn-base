<?php
include '../../lib/system/utilities.php';
include '../../lib/components/ajaxpage.inc.php';
include '../../lib/bo/CommunicationQueueManager.php';
//date_default_timezone_set("Asia/Calcutta"); 
if(!isset($_SESSION)){
    session_start();
    if(!isset($_SESSION['timezone'])){
    $_SESSION['timezone'] = 'Asia/Kolkata';
}
    date_default_timezone_set(''.$_SESSION['timezone'].'');
}

class jsonop
{
    // Empty class!
}

$cm = new CommunicationQueueManager();
$notifs = $cm->getalerts($_SESSION['customerno']);

if(isset($notifs))
{
    $cm->setalert($_SESSION['customerno']);    
    foreach($notifs as $queue)
    {
        $output = new jsonop();
        $output->notif = "<font color='#000000'>".$queue->notif."</font>";
        $output->type = 0;
        $finaloutput[] = $output;
    }
}
if(!isset($output))
{
    if(isset($_POST['type']) && $_POST['type'] == 1)
    {
        $notifids = $cm->getrandnews();    
        $id = array_rand($notifids);
        $notifs = $cm->getnews($notifids[$id]);
        if(isset($notifs))
        {
            $output = new jsonop();
            $output->notif = $notifs->notif;
            if($notifids[$id] == 8)
            {
                $custids = $cm->getrandcust();            
                $custid = array_rand($custids);
                $company = $cm->getcompany($custids[$custid]);
                if(isset($company))
                {
                    $output->notif.=" <font color='#000000'>".$company->company."</font>";
                }
            }        
            $output->type = 1;
            $finaloutput[] = $output;
        }    
    }
    
    if(isset($_POST['type']) && $_POST['type'] == 2)
    {
        $notifids = $cm->getrandtips();    
        $id = array_rand($notifids);
        $notifs = $cm->getnews($notifids[$id]);
        if(isset($notifs))
        {
            $output = new jsonop();
            $output->notif = $notifs->notif;
            $output->type = 2;
            $finaloutput[] = $output;
        }    
    }    
    
    if(isset($_POST['type']) && $_POST['type'] == 3)
    {
        $notifids = $cm->getrandlinks();    
        $id = array_rand($notifids);
        $notifs = $cm->getnews($notifids[$id]);
        if(isset($notifs))
        {
            $output = new jsonop();
            $output->notif = $notifs->notif;
            $output->type = 3;
            $finaloutput[] = $output;
        }    
    }    
    
}

$ajaxpage = new ajaxpage();
$ajaxpage->SetResult($finaloutput);
$ajaxpage->Render();


?>
