<?PHP
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');

require_once "../../lib/system/utilities.php";
require_once '../../lib/bo/CustomerManager.php';
require_once "../../lib/system/DatabaseSalesEngagementManager.php";
require_once "../../lib/system/utilities.php";
require_once '../salesengage/class/SalesEngagementManager.php';
require_once '../salesengage/salesengage_function.php';

//activity alert
$today = date('Y-m-d h:i:s');
//echo $today = date('2015-07-10 11:10:00');
//echo"<br>------------------------<br>";
$sales = new Saleseng(NULL,NULL);
$getdata = $sales->getactivity();
//echo"<pre>";
//print_r($getdata);
//echo"<pre>";
if(isset($getdata))
{
    foreach($getdata as $row)
    {
        $activityid = $row['activityId'];   
        $activitytime = $row['activitytime']; 
        $newtimestamp = strtotime($activitytime.'-'.$row["activity_reminder_duration"].' minute');
        $beforeactivitytime = date('Y-m-d H:i:s', $newtimestamp);
        //echo $beforeactivitytime."<br>";
        if(strtotime($today)==strtotime($beforeactivitytime))
        {
            if($row['activitytype']==1){  //echo "client";  
                $receipent_type = 1;
                $templates = $sales->gettemplateby_reminderid($reminderid,$receipent_type);  //get template from reminderid       
                $clientdata = $sales->getclientdetailsforcron($row['clientid']);     
                if(isset($clientdata)){
                    $cemail = $clientdata[0]['cemail'];
                    $cphone = $clientdata[0]['cphone'];

                    if($row['isemailrequested']==1 && !empty($cemail)){
                            $subject = $templates[0]['email_subject'];
                            $content = $templates[0]['emailtemplate'];
                            sendMail($cemail, $subject, $content);
                            $emailsend=1;
                    }else{
                        $emailsend =0;
                    }
                    if($row['issmsrequested']==1 && !empty($cphone)){
                            $message = $templates[0]['smstemplate'];
                            sendSMS($cphone,$message);
                            $smssend=1;
                    }else{
                        $smssend =0;
                    }
                }    
            }
            if($row['activitytype']==2){  //elixirs 
                $receipent_type = 2;
                $templates_elx = $sales->gettemplateby_reminderid($reminderid,$receipent_type);  //get template from reminderid       
                $activityid = $row['activityId'];   
                $userdata = $sales->getuserdetailsforcron($row['addedby']); 
                if(isset($userdata)){
                    $emailelixir = $userdata[0]['email'];
                    $phoneelixir = $userdata[0]['phone'];
                    $reminderid = $row['reminderid'];    

                    if($row['isemailrequested']==1 && !empty($cemail)){
                            $subject = $templates_elx[0]['email_subject'];
                            $content = $templates_elx[0]['emailtemplate'];
                            sendMail($cemail, $subject, $content);
                            $emailsend=1;
                    }else{
                            $emailsend=0;
                    }
                    
                    if($row['issmsrequested']==1 && !empty($cphone)){
                            $message = $templates_elx[0]['smstemplate'];
                            sendSMS($cphone,$message);
                            $smssend=1;
                    }else{
                            $smssend =0;
                    }
                }
            }
            $sales->activity_updatebycron($activityid,$emailsend,$smssend);
        }
    }
}

?>

