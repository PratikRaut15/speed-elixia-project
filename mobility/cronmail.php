<?php
include_once("session/sessionhelper.php");
include_once("db.php");
include_once("lib/system/utilities.php");
include_once("constants/constants.php");
include_once("lib/bo/CommunicationQueueManager.php");
include_once("lib/model/VOCommunicationQueue.php");

function sendMail( $to, $subject , $content)
{
    $subject = $subject;

    $headers = "From: noreply@elixiamail.com\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";    
    if (!@mail($to, $subject, $content, $headers)) {
        // message sending failed
        return false;
    }
    return true;        
}

function sendSMS($phone, $message, $customerno)
{
    if($customerno == 14)
    {
        $url = 'https://belitaindia:25f47ce1b0653fc384daf9baff8a0cfb279c56f4@twilix.exotel.in/v1/Accounts/belitaindia/Sms/send';
        $fields = array(
                    'From' => 'LM-BELITA',
                    'To' => $phone,
                    'Body' => $message);

        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        $result = curl_exec($ch);
        curl_close($ch);    
        return true;        
    }
    else
    {
        $url = "http://india.msg91.com/sendhttp.php?user=sanketsheth&password=271257&mobiles=".urlencode($phone)."&message=".urlencode($message)."&sender=Elixia&route=4";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);    
        return true;
    }

/*    
    $urloutput=file_get_contents($url);
    echo $urloutput; //should print "N,-1,Cart id must be provided"    
    return true;
 * 
 */
}

$cqm = new CommunicationQueueManager();
$queues = $cqm->getqueue();
if(isset($queues))
{
    foreach($queues as $thisqueue)
    {
        if($thisqueue->type == 0)
        {
            $cvo = new VOCommunicationQueue();            
            if(sendMail($thisqueue->email, $thisqueue->subject, $thisqueue->message) == false)
            {
                $cvo->queueid = $thisqueue->id;
                $cvo->email = $thisqueue->email;
                $cvo->subject = $thisqueue->subject;
                $cvo->message = $thisqueue->message;
                $cvo->type = $thisqueue->type;
                $cvo->phone = $thisqueue->phone;
                $cvo->datecreated = $thisqueue->datecreated;
                $cvo->customerno = $thisqueue->customerno;                
                $cvo->senderror = 1;
                $cvo->confirmation = 1;
                $cqm->InsertHistory($cvo);
            }
            else
            {
                $cvo->queueid = $thisqueue->id;
                $cvo->email = $thisqueue->email;
                $cvo->subject = $thisqueue->subject;
                $cvo->message = $thisqueue->message;
                $cvo->type = $thisqueue->type;
                $cvo->phone = $thisqueue->phone;
                $cvo->datecreated = $thisqueue->datecreated;
                $cvo->customerno = $thisqueue->customerno;                                
                $cvo->senderror = 0;
                $cvo->confirmation = 1;
                $cqm->InsertHistory($cvo);                                
            }
            $cqm->DeleteQueue($thisqueue->id);
        }                 
        elseif($thisqueue->type == 1)
        {
            
             $cvo = new VOCommunicationQueue();            
            if(sendSMS($thisqueue->phone, $thisqueue->message, $thisqueue->customerno) == false)
            {
                $cvo->queueid = $thisqueue->id;
                $cvo->email = $thisqueue->email;
                $cvo->subject = $thisqueue->subject;
                $cvo->message = $thisqueue->message;
                $cvo->type = $thisqueue->type;
                $cvo->customerno = $thisqueue->customerno;                                
                $cvo->phone = $thisqueue->phone;
                $cvo->datecreated = $thisqueue->datecreated;
                $cvo->senderror = 1;
                $cvo->confirmation = 1;
                $cqm->InsertHistory($cvo);
            }
            else
            {
                $cvo->queueid = $thisqueue->id;
                $cvo->email = $thisqueue->email;
                $cvo->subject = $thisqueue->subject;
                $cvo->message = $thisqueue->message;
                $cvo->type = $thisqueue->type;
                $cvo->phone = $thisqueue->phone;
                $cvo->customerno = $thisqueue->customerno;                                
                $cvo->datecreated = $thisqueue->datecreated;
                $cvo->senderror = 0;
                $cvo->confirmation = 1;
                $cqm->InsertHistory($cvo);                                
            }
            $cqm->DeleteQueue($thisqueue->id);           
        }        
    }
}
?>