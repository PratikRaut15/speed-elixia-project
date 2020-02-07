<?php
require "../../lib/system/utilities.php";
require '../../modules/team/DocketManager.php';


$dm=new DocketManager();
$today = date("Y-m-d H:i:s");
$ids = array(); 
$tickets= $dm->fetchOverdueTickets();
echo "<pre>";
$emailmessage = 'The following ticket(s) alloted to you have not been updated yet. Please go to <a href ="http://speed.elixiatech.com/modules/team/myticket.php">My tickets</a> and update expected close date for the ticket(s).';
$message ="<table border='1'><tr><th>Ticket ID:</th><th>Title</th><th>Raised on</th><th>Overdue time</th><th>alloted to</th</tr>";


    $array = json_decode(json_encode($tickets), true);

        $breakTeamIdWise = array_reduce($array, function ($result, $currentItem) {
             if (isset($result[$currentItem['teamid']])) {
               $result[$currentItem['teamid']][] = $currentItem;
            }
            else {
               $result[$currentItem['teamid']][] = $currentItem;
            }
            return $result;
        });



        foreach($breakTeamIdWise as $teamid)
        {   

            $message ="<table border='1'><tr><th>Ticket ID:</th><th>Title</th><th>Raised on</th><th>Overdue time</th><th>alloted to</th</tr>";
            foreach($teamid as $ticketid)
            {

            $toArr = array();    
            $hourdiff = round((strtotime(date('Y-m-d H:i:s')) - strtotime($ticketid['estimateddate'])), 1);
            if(($hourdiff/86400)>=1){
                $hourdiff = round($hourdiff/86400) ." Day(s)";
            }elseif(($hourdiff/3600)>=1){
                $hourdiff = round($hourdiff/3600) ." Hour(s)";
            }elseif(($hourdiff/60)>=1){
                $hourdiff = round($hourdiff/60) ." Minute(s)";
            }else{
                $hourdiff .=" Seconds";
            }
            $message .= '<tr>';
            $message .= '<td>'.$ticketid['ticketid'].'</td>';
            $message .= '<td>'.$ticketid['title'].'</td>';
            $message .= '<td>'.$ticketid['estimateddate'].'</td>';
            $message .= '<td>'.$hourdiff.'</td>';
            $message .= '<td>'.$ticketid['name'].'</td>';
            $message .= '</tr>';
            $toArr[] = $ticketid['email'];
            
            }

        
            $message.="</table>";

            $subject = "Ticket expected date not updated" . date('d-M-Y', strtotime($today));
            $CCEmail = 'yash.elixiatech@gmail.com';
            $BCCEmail = 'kartik.elixiatech@gmail.com';
            $emailmessage.=$message;
            $dest2 = "";
            $file_name = "";
            echo "<pre>";
            
            print_r($toArr);
            echo $subject;
            echo $CCEmail;
            echo $BCCEmail;
            echo $emailmessage;
            $emailstatus = sendMailUtil($toArr, $CCEmail, $BCCEmail, $subject, $emailmessage, $dest2, $file_name);


            $message = '';
            $toArr = '';
            $subject = '';
            $CCEmail = '';
            $BCCEmail = '';
            $emailmessage= '';
            
        }


