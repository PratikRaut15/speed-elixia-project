<?php
ini_set('memory_limit','256M');

include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/bo/DocketManager.php");
date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");

if(isset($_POST['get_interaction'])){
	$docket=new DocketManager();
	$arrResult=$docket->getInteractionTypes();
	echo json_encode($arrResult);
}
if(isset($_POST['getTicketData'])){
    $docket=new DocketManager();
    $arrResult=$docket->fetchTickets(0,$_POST['ticketid']);
    echo json_encode($arrResult);
}
if(isset($_POST['get_purpose'])){
	$docket=new DocketManager();
	$arrResult=$docket->getPurposeTypes();
	echo json_encode($arrResult);
}
if(isset($_POST['get_crm'])){
	$docket=new DocketManager();
	$arrResult=$docket->getCRM();
	echo json_encode($arrResult);
}

if(isset($_POST['insert_docket'])){
    if(isset($_FILES)){

    }
    //print_r($_POST);
    //die();
    $ticketArray=explode(',',$_POST['ticketArray']);
    $docket=new DocketManager();
    $docketObj = new stdClass();
    $today = date("Y-m-d H:i:s");
    $docketObj->raiseOnDateTime = date("Y-m-d H:i:s", strtotime($_POST['raiseondate'] . " " . $_POST['raiseontime'] . ":00"));
    $docketObj->customerno = $_POST['customerno'];
    $docketObj->createBy = GetLoggedInUserId();
    $docketObj->teamId = $_POST['teamid'];
    $docketObj->timestamp = $today;
    $docketObj->interactionId = $_POST['interactionId'];
    $docketObj->purposeId = $_POST['purposes'];
    if($_POST['i_type']=='in'){
    	$docketObj->i_type=1;
    }else{
		$docketObj->i_type=0;
    }
    if($_POST['r_type']=='answered'){
    	$docketObj->r_type=1;
    }else{
    	$docketObj->r_type=0;
    }
    $docketId=$docket->insertDocket($docketObj);
    $ticketCount=$_POST['numberOfTickets'];
    $i=0;
    $c=0;
    $x=0;                              //counter for number of ticketids
    $createby = GetLoggedInUserId();
    $ticketObj=new stdClass();
    $ticketidArray = array();
    if($ticketCount>0){
        while($i<sizeof($ticketArray)){
            if($ticketArray[$i]==1){
                $ticketObj->ticket_title = $_POST['title'.$i];
                $ticketObj->tickettype= $_POST['type'.$i];
                $ticketObj->priority = $_POST['priority'.$i];
                $ticketObj->ticketdesc = $_POST['description'.$i];
                $ticketObj->ticketcust = $docketObj->customerno;
                $ticketObj->todaysdate = Sanitise::DateTime($today);
                $ticketObj->ticket_allot = $_POST['allot'.$i];
                $ticketObj->raiseondatetime = $docketObj->raiseOnDateTime;
                $ticketObj->expectedCloseDate = isset($_POST['expectedclosedate'.$i])? $_POST['expectedclosedate'.$i] : '0000-00-00 00:00:00';
                $ticketObj->ecdToBeUpdatedBy = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($today)));
                $ticketObj->ecdToBeUpdatedBy;
                $ticketObj->ticketmailid = $_POST['emailList'.$i];
                $ticketObj->ticketEmails = $_POST['emailIds'.$i];
                $ticketObj->sendticketmail = 1;
                $ticketObj->ccemailids = $_POST['CCList'.$i];
                $ticketObj->ticketEmails_CC = $_POST['emailIdsCC'.$i];
                $ticketObj->createby = $createby;
                $ticketObj->rel_mgr = $docketObj->teamId;
                $ticketObj->platform = 1;
                $ticketObj->prodId = $_POST['product'.$i];
                $ticketObj->docketId = $docketId;
                $outputResult = $docket->add_ticket($ticketObj);

                $ticketidArray['ticketid'.$x] =  $outputResult;
                $x++;
                $c++;
            }
            $i++;
        }
    }
    $resultArray = array();

   $resultArray['numberOfTickets'] =$c;
    $i=0;
    $j=0;
    $today = date("Y-m-d H:i:s");
    $createby = GetLoggedInUserId();
    $bucketObj=new stdClass();
    $cordObj = new stdClass();
    $bucketArray = explode(',',$_POST['bucketArray']);
    $bucketCount = $_POST['numberOfBuckets'];
    $bucketidArray = array();


	if($bucketCount>0)
    {
        while($i<sizeof($bucketArray))
        {
            if($bucketArray[$i]==1)
            {

                if(isset($_POST['scoordinator'.$i]))
                    {

                  $bucketObj->coordinator = $_POST['scoordinator'.$i];
                    }
                else
                    {

                $cordObj->coname = $_POST['coname'.$i];      //When co-ordinator not selected from dropdown
                $cordObj->cophone = $_POST['cophone'.$i];
                $cordObj->cordcust = $docketObj->customerno;
                $cordObj->createby = $createby;
                $cordObj->coname;
                $cordObj->cophone;
                $cordObj->today = $today;
                $bucketObj->coordinator= $docket->add_cordinator($cordObj);

                $bucketObj->coordinator;


                    }

            $operationtype = $_POST['OperationType'.$i];
            if($operationtype==1){


                    $bucketObj->apt_date = date("Y-m-d",strtotime($_POST['sapt_date'.$i]));
                    $bucketObj->priority= $_POST['spriority'.$i];
                    $bucketObj->vehicleno = $_POST['vehno'.$i];
                    $bucketObj->location = $_POST['slocation'.$i];
                    $bucketObj->timeslot = $_POST['stimeslot'.$i];
                    $bucketObj->details = $_POST['sdetails'.$i];
                    $bucketObj->no_inst = $_POST['no_inst'.$i];
                    $bucketObj->createby = $createby;
                    $bucketObj->bucketcust = $docketObj->customerno;
                    $bucketObj->todaysdate = Sanitise::DateTime($today);
                    $bucketObj->docketId = $docketId;
                    $bucketid = $docket->add_bucket($bucketObj);

                    }
            else{


                    $bucketObj->comments = $_POST["scomments".$i];
                    $bucketObj->deviceid = $_POST["deviceid".$i];
                    $bucketObj->simcardid = $_POST["simcardid".$i];
                    $bucketObj->bucketcust = $docketObj->customerno;
                    $bucketObj->apt_date = date("Y-m-d",strtotime($_POST['sapt_date'.$i]));
                    $bucketObj->priority= $_POST['spriority'.$i];
                    $bucketObj->location = $_POST['slocation'.$i];
                    $bucketObj->timeslot = $_POST['stimeslot'.$i];
                    $bucketObj->operation = $_POST['OperationType'.$i];
                    $bucketObj->details = $_POST['sdetails'.$i];
                    $bucketObj->email = $_POST["sendmailsuspect".$i];
                    $bucketObj->createby = $createby;
                    $bucketObj->todaysdate = Sanitise::DateTime($today);
                    $bucketObj->docketId = $docketId;
                    $bucketid = $docket->add_r_bucket($bucketObj);

                }
                $bucketidArray['bucketid'.$i] =  $bucketid;
            }
                 $i++;

            }

        }

        $resultArray['docketid'] = $docketId ;
        $resultArray['TicketIds'] = $ticketidArray;
        $resultArray['bucketIds'] = $bucketidArray;
        echo json_encode($resultArray);
}

if(isset($_POST['fetch_dockets'])){
	//print_r($_POST);
	$docket=new DocketManager();
	$arrResult=$docket->fetchDockets($_POST['teamid'],$_POST['docketId']);
	echo json_encode($arrResult);
}
if(isset($_POST['updateTicket'])){
    $dm = new DocketManager();
    $ticketObj = new stdClass();
    $ticketObj->ticketid = $_POST['ticketid'];
    $ticketObj->emailList = $_POST['emailList'];
    $ticketObj->ccList = $_POST['ccList'];
    $ticketObj->allotTo = $_POST['allot'];
    $ticketObj->status = $_POST['status'];
    $ticketObj->priority = $_POST['priority'];
    $ticketObj->ecd = Sanitise::DateTime($_POST['ecd']);
    $ticketObj->createBy = GetLoggedInUserId();
    $ticketObj->created_datetime = Sanitise::DateTime($today);
    $ticketObj->additionalCharge = isset($_POST['additionalCharges'])? 1:0 ;
    $result = $dm->editTicket($ticketObj);
    if(isset($_POST['additionalCharges'])){
        $charge = new stdClass();
        $charge->description = $_POST['chargeDescription'];
        $charge->amount = $_POST['chargesField'];
        $charge->createdby = $_SESSION['sessionteamid'];
        $charge->customerno = $_POST['customerno'];
        $charge->ticketid = $_POST['ticketid'];
        $charge->bucketid = 0;
        $chargeResult = $dm->add_additional_charge($charge);
    }
}
if(isset($_POST['edit_ticket'])){
     $resultArray = array();
    $i=$_POST['id'];
    if(isset($_POST['ticketId'.$i])&&$_POST['ticketId'.$i]!=''){
        $todaydate = date("Y-m-d");
        $ticketid2 = $_POST["ticketId".$i];
        $ticket_title = $_POST["title".$i];
        $customerid = $_POST["customerno"];
        $ticket_allot = $_POST["allot".$i];

        $ticketdesc = $_POST["description".$i];
        $ticketstatus = $_POST["status".$i];
        $createdby = $_POST["teamid"];
        $expecteddate = $todaydate;
        $ticket_type = $_POST["type".$i];
        $sendemailcust = 0;
        $customeremailids = $_POST["emailList".$i];
        $ccemailids = $_POST['CCList'.$i];
        $priorityid = $_POST['priority'.$i];
        $last_allot_to=$ticket_allot;

        if (!empty($expecteddate) && $expecteddate != "1970-01-01") {
            $datetest = date("Y-m-d", strtotime($expecteddate));
            $todaydate1 = $datetest;
        } else {
            $todaydate1 = date("Y-m-d");
        }

        $add_count = 0;
        if ($eclosedate1 != $todaydate1) {
            $add_count = 1;
        }
        if (empty($message)) {
            $created_datetime = Sanitise::DateTime($today);
            $dm=new DocketManager();
            $obj=new stdClass();
            $obj->priorityid=$priorityid;
            $obj->sendMail=$sendemailcust;
            $obj->ticketMailIds = $customeremailids;
            $obj->ccemailids = $ccemailids;
            $obj->customerid=$customerid;
            $obj->eclosedate=$todaydate1;
            $obj->addcount=$add_count;
            $obj->ticket_title=$ticket_title;
            $obj->tickettype=$ticket_type;
            $obj->ticketid=$ticketid2;
            $obj->created_type =0;
            $obj->ticketdesc=$ticketdesc;
            $obj->ticketstatus=$ticketstatus;
            $obj->allotFrom=Sanitise::Long($_SESSION["sessionteamid"]);
            $obj->allotTo=$ticket_allot;
            $obj->createdby=$createdby;
            $obj->today=$created_datetime;
            $obj->prodId=$_POST['product'.$i];
            $update_ticketid= $dm->updateTicket($obj);

            $docketId = $_POST['docketId'];
            $resultArray['docket'] = $docketId;
            $resultArray['ticketid'] = $update_ticketid;
            echo json_encode($resultArray);
        }
    }else{

            $docket= new DocketManager();
            $ticketObj = new stdClass();
                $ticketObj->ticket_title = $_POST['title'.$i];
                $ticketObj->tickettype= $_POST['type'.$i];
                $ticketObj->priority = $_POST['priority'.$i];
                $ticketObj->ticketdesc = $_POST['description'.$i];
                $ticketObj->ticketcust = $_POST['customerno'];
                $ticketObj->todaysdate = Sanitise::DateTime($today);
                $ticketObj->ticket_allot = $_POST['allot'.$i];
                $ticketObj->raiseondatetime = $docketObj->raiseOnDateTime;
                $ticketObj->expectedCloseDate = isset($_POST['expectedclosedate'.$i])? $_POST['expectedclosedate'.$i] : '0000-00-00 00:00:00';
                $ticketObj->ticketmailid = $_POST['emailList'.$i];
                $ticketObj->sendticketmail = 1;
                $ticketObj->ccemailids = $_POST['CC'.$i];
                $ticketObj->createby = $_POST['teamid'];
                $ticketObj->rel_mgr = $docketObj->teamId;
                $ticketObj->platform = 1;
                $ticketObj->prodId = $_POST['product'.$i];
                $ticketObj->docketId = $_POST['docketId'];
                $new_ticketid = $docket->add_ticket($ticketObj);

                $resultArray['docket'] = $ticketObj->docketId;
                $resultArray['ticketid'] = $new_ticketid;
                echo json_encode($resultArray);

    }
}
if(isset($_POST['edit_bucket'])){
    $i=$_POST['id'];
    $today = date("Y-m-d H:i:s");
    $createby = GetLoggedInUserId();

        if(isset($_POST['BucketId'.$i])&&$_POST['BucketId'.$i]!='')
        {

            $dm=new DocketManager();
            $bucketObj=new stdClass();
            $cordObj = new stdClass();
            $NewbucketObj = new stdClass();

            if(isset($_POST['scoordinator'.$i]))
                {

                  $bucketObj->coordinator = $_POST['scoordinator'.$i];
                }
                else
                {

                $cordObj->coname = $_POST['coname'.$i];      //When co-ordinator not selected from dropdown
                $cordObj->cophone = $_POST['cophone'.$i];
                $cordObj->cordcust = $_POST["customerno"];
                $cordObj->createby = $createby;
                $cordObj->coname;
                $cordObj->cophone;
                $cordObj->today = $today;
                $bucketObj->coordinator= $dm->add_cordinator($cordObj);
                echo "Coordinator added successfully. ID : ".$bucketObj->coordinator;
                }

                if(isset($_POST['add_charge'.$i]))
                        {
                            $bucketObj->add_charge = 1;    //Additional Charge If applicable
                        }
                if(!isset($_POST['add_charge'.$i]))
                        {
                            $bucketObj->add_charge = 0;
                        }
                $bucketObj->customerno = $_POST["customerno"];
                $bucketObj->created_by =  $createby;
                $bucketObj->priorityid = $_POST["spriority".$i];
                $bucketObj->location = $_POST["location".$i];
                $bucketObj->vehicleno = $_POST['vehno'.$i];
                $bucketObj->timeslot = $_POST["stimeslot".$i];
                $bucketObj->operationtype = $_POST["OperationType".$i];
                $bucketObj->details = $_POST["details".$i];
                $bucketObj->creason = $_POST["creason".$i];
                $bucketObj->apt_date = $_POST["sapt_date".$i];

                $bucketObj->apt_date = date("Y-m-d", strtotime($bucketObj->apt_date));
                $bucketObj->today = $today;
                $bucketObj->bucketid = $_POST["BucketId".$i];



                if(isset($_POST["reschedule_date".$i]))     //If bucket is modified,cancelled it
                                                            //passes
                    {                                       // Date:- 1970-01-01
                    $bucketObj->reschedule_date = date("Y-m-d", strtotime($_POST["reschedule_date".$i]));
                     }

                    $bucketObj->sstatus = $_POST["sstatus".$i];


                    if ($bucketObj->sstatus == 0) {

                                                     //Modify Bucket
                        $dm->updateBucket($bucketObj);
                    }

                    if ($bucketObj->sstatus == 5) {
                                                       //Cancelled Bucket
                        $dm->updateBucket($bucketObj);

                    }

                    if ($bucketObj->sstatus == 1)             //Rescheduled Bucket
                    {


                          $dm->updateBucket($bucketObj);



                            $NewbucketObj->vehicleid = GetSafeValueString(isset($_POST["vehicleid".$i]) ? $_POST["vehicleid".$i] : 0 , "string");
                            $NewbucketObj->reschedule_date =  $bucketObj->reschedule_date;
                            $NewbucketObj->deviceid = $_POST["deviceid".$i];
                            $NewbucketObj->simcardid = $_POST["simcardid".$i];
                            $NewbucketObj->customerno = $_POST["customerno"];
                            $NewbucketObj->createby = $createby;
                            $NewbucketObj->priority= $_POST['spriority'.$i];
                            $NewbucketObj->operationtype= $_POST["OperationType".$i];
                            $NewbucketObj->location = $_POST['slocation'.$i];
                            $NewbucketObj->timeslot = $_POST['stimeslot'.$i];
                            $NewbucketObj->details = $_POST['sdetails'.$i];
                            $NewbucketObj->coordinator = $_POST['scoordinator'.$i];
                            $NewbucketObj->todaysdate = Sanitise::DateTime($today);
                            $NewbucketObj->docketId = $_POST['docketId'];
                            $NewbucketObj->prevBucketId=$bucketObj->bucketid;

                            $ebucket=$dm->add_e_bucket($NewbucketObj);
                            echo json_encode($ebucket);                     //ebucket is new bucket added
                                                                            //so modal should be updated with new bucketID
                    }

                    if(isset($_POST['add_charge'.$i]) && $_POST['add_charge'.$i]==1)
                    {




                        $BucketCharge_obj->ticketid=0;
                        $BucketCharge_obj->description=$_POST['charge_desc'.$i];
                        $BucketCharge_obj->amount=$_POST['amt_charge'.$i];
                        $BucketCharge_obj->bucketid = $_POST["BucketId".$i];
                        $dm->add_additional_charge($BucketCharge_obj);

                    }
    }

 else
        {
                $dm=new DocketManager();
                $bucketObj=new stdClass();
                $cordObj = new stdClass();

                 if(isset($_POST['scoordinator'.$i]))
                    {

                  $bucketObj->coordinator = $_POST['scoordinator'.$i];
                    }
                else
                    {

                $cordObj->coname = $_POST['coname'.$i];      //When co-ordinator not selected from dropdown
                $cordObj->cophone = $_POST['cophone'.$i];
                $cordObj->cordcust = $_POST["customerno"];
                $cordObj->createby = $createby;
                $cordObj->coname;
                $cordObj->cophone;
                $cordObj->today = $today;
                $bucketObj->coordinator= $dm->add_cordinator($cordObj);
                echo "Coordinator added successfully. ID : ".$bucketObj->coordinator;
                    }



            $operationtype = $_POST['OperationType'.$i];
            if($operationtype==1){

                    $bucketObj->apt_date = date("Y-m-d",strtotime($_POST['sapt_date'.$i]));
                    $bucketObj->priority= $_POST['spriority'.$i];
                    $bucketObj->vehicleno = $_POST['vehno'.$i];
                    $bucketObj->location = $_POST['slocation'.$i];
                    $bucketObj->timeslot = $_POST['stimeslot'.$i];
                    $bucketObj->details = $_POST['sdetails'.$i];
                    $bucketObj->no_inst = $_POST['no_inst'.$i];
                    $bucketObj->createby = $createby;
                    $bucketObj->bucketcust = $_POST["customerno"];
                    $bucketObj->todaysdate = Sanitise::DateTime($today);
                    $bucketObj->docketId = $_POST['docketId'];
                    $outputResult = $dm->add_bucket($bucketObj);

                    }
            else{


                    $bucketObj->comments = $_POST["scomments".$i];
                    $bucketObj->deviceid = $_POST["deviceid".$i];
                    $bucketObj->simcardid = $_POST["simcardid".$i];
                    $bucketObj->bucketcust = $_POST["customerno"];
                    $bucketObj->apt_date = date("Y-m-d",strtotime($_POST['sapt_date'.$i]));
                    $bucketObj->priority= $_POST['spriority'.$i];
                    $bucketObj->location = $_POST['slocation'.$i];
                    $bucketObj->timeslot = $_POST['stimeslot'.$i];
                    $bucketObj->operation = $_POST['OperationType'.$i];
                    $bucketObj->details = $_POST['sdetails'.$i];
                    $bucketObj->email = $_POST["sendmailsuspect".$i];
                    $bucketObj->createby = $createby;
                    $bucketObj->todaysdate = Sanitise::DateTime($today);
                    $bucketObj->docketId = $_POST['docketId'];
                    $outputResult = $dm->add_r_bucket($bucketObj);
                }
        }
}

if (isset($_REQUEST['get_customer'])) {
	$docket=new DocketManager();
	$arrResult=$docket->getCustomers($_REQUEST['term']);
	echo json_encode($arrResult);
}

if(isset($_POST['getPriorities'])){
	$docket=new DocketManager();
	$arrResult=$docket->getTicketPriorities();
	echo json_encode($arrResult);
}

if(isset($_POST['getTeamList'])){
    $docket=new DocketManager();
    $arrResult=$docket->fetchTeam();
    echo json_encode($arrResult);
}

if(isset($_POST['getTicketTypes'])){
    $docket=new DocketManager();
    $arrResult=$docket->getTicketTypes(0);
    echo json_encode($arrResult);
}

if(isset($_POST['getProducts'])){
    $docket=new DocketManager();
    $arrResult=$docket->getProducts();
    echo json_encode($arrResult);
}

if(isset($_POST['edit_docket'])){
    $ticketArray=explode(',',$_POST['ticketArray']);
    $docket=new DocketManager();
    $docketObj = new stdClass();
    $today = date("Y-m-d H:i:s");
    $docketObj->docketId=$_POST['docketid'];
    $docketObj->raiseOnDateTime = date("Y-m-d H:i:s", strtotime($_POST['raiseondate'] . " " . $_POST['raiseontime'] . ":00"));
    $docketObj->customerno = $_POST['customerno'];
    $docketObj->createBy = GetLoggedInUserId();
    $docketObj->teamId = $_POST['teamid'];
    $docketObj->timestamp = $today;
    $docketObj->interactionId = $_POST['interactionId'];
    $docketObj->purposeId = $_POST['purposeId'];
    if($_POST['i_type']=='in'){
        $docketObj->i_type=1;
    }else{
        $docketObj->i_type=0;
    }
    if($_POST['r_type']=='answered'){
        $docketObj->r_type=1;
    }else{
        $docketObj->r_type=0;
    }
    $docketId=$docket->editDocket($docketObj);
    $ticketCount=$_POST['numberOfTickets'];
    $i=0;
    $u=0;
    $a=0;
    $createby = GetLoggedInUserId();
    $ticketObj=new stdClass();
    if($ticketCount>0){
        while($i<sizeof($ticketArray)){
            if($ticketArray[$i]==1){
                $ticketObj->docketId = $docketObj->docketId;
                $ticketObj->ticket_title = $_POST['title'.$i];
                $ticketObj->tickettype= $_POST['type'.$i];
                $ticketObj->priority = $_POST['priority'.$i];
                $ticketObj->ticketdesc = $_POST['description'.$i];
                $ticketObj->ticketcust = $docketObj->customerno;
                $ticketObj->todaysdate = Sanitise::DateTime($today);
                $ticketObj->ticket_allot = $_POST['allot'.$i];
                //echo "allot to : ".$_POST['allot'.$i];
                $ticketObj->raiseondatetime = $docketObj->raiseOnDateTime;
                $ticketObj->expectedCloseDate = isset($_POST['expectedclosedate'.$i])? $_POST['expectedclosedate'.$i] : '0000-00-00 00:00:00';
                $ticketObj->ticketmailid = $_POST['emailList'.$i];
                $ticketObj->sendticketmail = 1;
                $ticketObj->ccemailids = $_POST['CC'.$i];
                $ticketObj->createby = $createby;
                $ticketObj->rel_mgr = $docketObj->teamId;
                $ticketObj->platform = 1;
                $ticketObj->prodId = $_POST['product'.$i];
                $ticketObj->addcount=0;
                //print_r($ticketObj);
                if($_POST['ticketId'.$i]!=''){
                    $ticketObj->addcount = 0;
                    if ($ticketObj->expectedCloseDate != $ticketObj->todaysdate) {
                        $ticketObj->addcount = 1;
                    }
                    $ticketObj->ticketDesc = $_POST['description'.$i];
                    $ticketObj->ticketId=$_POST['ticketId'.$i];
                    $ticketObj->allotFrom = GetLoggedInUserId();
                    $ticketObj->allotTo = $ticketObj->ticket_allot;
                    $ticketObj->ticketStatus = $_POST['status'.$i];
                    $ticketObj->createdBy = $ticketObj->allotFrom;
                    $ticketObj->today = $today;
                    $ticketObj->created_type = $_POST['createdType'.$i];
                    $outputResult=$docket->updateTicket($ticketObj);
                    $u++;
                }else{
                    $outputResult = $docket->add_ticket($ticketObj);
                    $a++;
                }
            }
            $i++;
        }
    }
    echo " ".$u." tickets updated. ".$a." tickets added.";
}

if(isset($_POST['fetchTickets'])){
    $docket=new DocketManager();
    $arrResult=$docket->fetchTickets($_POST['docketId'],$_POST['ticketId']);
    echo json_encode($arrResult);
}

if(isset($_POST['getStatus'])){
    $docket=new DocketManager();
    $arrResult=$docket->getStatus();
    echo json_encode($arrResult);
}

if(isset($_POST['bucket_allot_to'])){
    $docket=new DocketManager();
    $arrResult=$docket->getFieldEngineers();
    echo json_encode($arrResult);
}

if(isset($_POST['getDetails'])){
    $docket=new DocketManager();
    $arrResult=$docket->getDetails();
    echo json_encode($arrResult);
}

if(isset($_POST['getCordinator'])){
    $docket=new DocketManager();
    $cordObject = new stdClass();
    $cordObject->customerno = $_POST['customerno'];
    $arrResult=$docket->getCordinator($cordObject);
    echo json_encode($arrResult);
}

if (isset($_REQUEST['getVehicle'])) {
    $vehObj = new stdClass();
    $docket=new DocketManager();
    $vehObj->customerno= $_REQUEST['customerno'];
    $vehObj->term = $_REQUEST['term'];
    $arrResult=$docket->get_vehicle($vehObj);
    echo json_encode($arrResult);

}

if(isset($_POST['getTimeslot'])){
    $docket=new DocketManager();
    $arrResult=$docket->getTimeslot();
    echo json_encode($arrResult);
}

if(isset($_POST['getBuckets'])){

    $docket=new DocketManager();
    $arrResult=$docket->getBuckets($_POST['docketId']);
    echo json_encode($arrResult);
}
if(isset($_POST['translateEmails'])){
    $e_array=array();
    $earray=array();
    $ids=array();
    $db=new DatabaseManager();
    $ids = explode(',',$_POST['ids']);
    foreach($ids as $id){
        $SQL = sprintf("SELECT email_id FROM  ". DB_ELIXIATECH .".report_email_list WHERE eid=%d", $id);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $earray["id"] = $id;
                $earray["emailid"] = $row['email_id'];
                $e_array[] = $earray;
            }
        }
    }
    $retArray['ids']=$e_array;
    $e_array=array();
    $earray=array();
    $ids=array();
    $ids = explode(',',$_POST['CCs']);
    foreach($ids as $id){
        $SQL = sprintf("SELECT email_id FROM  ". DB_ELIXIATECH .".report_email_list WHERE eid=%d", $id);
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $earray["id"] = $id;
                $earray["emailid"] = $row['email_id'];
                $e_array[] = $earray;
            }
        }
    }
    $retArray['cc']=$e_array;
    echo json_encode($retArray);
}

if(isset($_POST['fetchBucketInfo'])){
   $docket=new DocketManager();
   $arrResult=$docket->fetchDocketInfo($_POST['docketId']);
   echo json_encode($arrResult);
}


if(isset($_POST['get_tickets_buckets'])){

   $dm = new DocketManager();
   $ret = $dm->fetchDocketInfo($_POST['DocketIds']);
   echo json_encode($ret);
}



if (isset($_REQUEST['work']) && $_REQUEST['work'] == "insertmailforTech" && isset($_REQUEST['dataTest']) && isset($_REQUEST['customerno1']))
{
    $dm = new DocketManager;
    $insertEmailObj=new stdClass();

    $insertEmailObj->emailText = $_REQUEST['dataTest'];
    $insertEmailObj->customerno = $_REQUEST['customerno1'];
    $email = $dm->insertEmailIdforTech($insertEmailObj);
    echo $email;
}
if (isset($_REQUEST['work']) && $_REQUEST['work'] == "getmailforTech" && isset($_REQUEST['customerno'])) {

    $customerno = $_REQUEST['customerno'];
    $term = $_REQUEST['term'];
    $devicemanager = new DeviceManager($customerno);
    $mailIds = $devicemanager->getEmailListforTech($term);
    echo $mailIds;
}

if(isset($_POST['pullBucketHistory'])){
    $docket=new DocketManager();
    $arrResult=$docket->pullBucketHistory($_POST['bucketid']);
    echo json_encode($arrResult);
}


if(isset($_POST['insertNote'])){
    $obj = new stdClass();
    $obj->ticketid = $_POST['ticketid'];
    $obj->note = $_POST['note'];
    $obj->teamId = GetLoggedInUserId();
    $obj->today = Sanitise::DateTime($today);
    $docket=new DocketManager();
    $arrResult=$docket->insertNote($obj);
    if($arrResult == 1){
        echo "Success";
    }else{
        echo "Failure";
    }
}
if(isset($_POST['pullNotes'])){
    $db = new DatabaseManager();
    $ticketid = $_POST['ticketid'];
    $db=new DatabaseManager();
    $pdo = $db->CreatePDOConnForTech();
    $sp_params = "'" .$ticketid. "'";
    $queryCallSP = "CALL " . speedConstants::SP_PULL_NOTES . "($sp_params)";
    $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
    if(!empty($arrResult)){
        echo json_encode($arrResult);
    }
}

if(isset($_POST['get_ticket_analysis'])){
    $docket=new DocketManager();
    $analysis_obj = new stdClass();
    $year = date('Y');
    $month = $_POST['month_ticket'];
    $join_date = $month.$year;
    $teamId = $_POST['teamId_ticket'];
    $status = $_POST['status_ticket'];
    if($teamId == 0)
    {
       $analysis_obj->teamId = "";
    }
    else{
        $analysis_obj->teamId = $teamId;
    }
    if($status == -1)
    {
       $analysis_obj->status = "";
    }
    else{
        $analysis_obj->status = $status;
    }
    $analysis_obj->startdate= date('Y-m-d',strtotime('first day of '.$join_date));
    $analysis_obj->enddate = date('Y-m-d',strtotime('last day of '.$join_date));
    $arrResult=$docket->getTicketAnalysis($analysis_obj);
    echo json_encode($arrResult);
}
if(isset($_POST['get_status'])){
    $docket=new DocketManager();
    $arrResult=$docket->getTicketStatus();
    echo json_encode($arrResult);
}

if(isset($_POST['get_status_bucket'])){
    $docket=new DocketManager();
    $arrResult=$docket->getBucketStatus();
    echo json_encode($arrResult);
}

if(isset($_POST['get_bucket_analysis'])){
    $docket=new DocketManager();
    $analysis_obj = new stdClass();
    $year = date('Y');
    $month = $_POST['month_bucket'];
    $join_date = $month.$year;
    $teamId = $_POST['teamId_bucket'];
    $status = $_POST['status_bucket'];
    $purpose = $_POST['OperationType'];
    if($teamId == 0)
    {
       $analysis_obj->teamId = "";
    }
    else{
        $analysis_obj->teamId = $teamId;
    }
    if($status == -1)
    {
       $analysis_obj->status = "";
    }
    else{
        $analysis_obj->status = $status;
    }
    if($purpose == -1)
    {
       $analysis_obj->purpose = "";
    }
    else{
        $analysis_obj->purpose = $purpose;
    }
    $analysis_obj->startdate= date('Y-m-d',strtotime('first day of '.$join_date));
    $analysis_obj->enddate = date('Y-m-d',strtotime('last day of '.$join_date));
    $arrResult=$docket->getBucketAnalysis($analysis_obj);
    echo json_encode($arrResult);
}
?>
